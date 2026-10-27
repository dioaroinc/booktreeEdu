<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\ProgressModel;
use App\Models\QuestionModel;
use App\Models\QuizAttemptModel;
use App\Models\SectionModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * 수강생 강의실
 */
class Academy extends BaseController
{
    protected CourseModel $courses;
    protected LessonModel $lessons;
    protected SectionModel $sections;
    protected QuestionModel $questions;
    protected ProgressModel $progress;
    protected QuizAttemptModel $attempts;

    public function initController($request, $response, $logger): void
    {
        parent::initController($request, $response, $logger);

        $this->courses   = model(CourseModel::class);
        $this->lessons   = model(LessonModel::class);
        $this->sections  = model(SectionModel::class);
        $this->questions = model(QuestionModel::class);
        $this->progress  = model(ProgressModel::class);
        $this->attempts  = model(QuizAttemptModel::class);
    }

    /**
     * 강의실 홈: 과정 목록 + 나의 진도
     */
    public function index(): string
    {
        $userId      = current_user_id();
        $courses     = $this->courses->listWithCounts(true);
        $progressMap = $this->progress->mapByUser($userId);

        foreach ($courses as &$course) {
            $lessons          = $this->lessons->byCourse((int) $course['id']);
            $course['passed'] = 0;
            foreach ($lessons as $lesson) {
                $p = $progressMap[(int) $lesson['id']] ?? null;
                if ($p && (int) $p['quiz_passed'] === 1) {
                    $course['passed']++;
                }
            }
            $course['rate'] = $course['lesson_count'] > 0
                ? (int) round($course['passed'] / $course['lesson_count'] * 100) : 0;
        }
        unset($course);

        return view('academy/index', [
            'title'   => '나의 강의실',
            'courses' => $courses,
            'summary' => $this->progress->summaryForUser($userId),
        ]);
    }

    /**
     * 과정별 강의 목록
     */
    public function course(int $id): string
    {
        $course = $this->courses->find($id);
        if (! $course || (int) $course['is_active'] !== 1) {
            throw PageNotFoundException::forPageNotFound('교육과정을 찾을 수 없습니다.');
        }

        $userId      = current_user_id();
        $lessons     = $this->lessons->byCourse($id);
        $progressMap = $this->progress->mapByUser($userId);

        // 단원별 그룹핑
        $sections = $this->sections->byCourse($id);
        $grouped  = [];
        foreach ($sections as $section) {
            $grouped[$section['id']] = ['title' => $section['title'], 'lessons' => []];
        }
        $grouped[0] = ['title' => '', 'lessons' => []];

        $index  = 0;
        $passed = 0;
        foreach ($lessons as $lesson) {
            $index++;
            $lesson['index']    = $index;
            $lesson['progress'] = $progressMap[(int) $lesson['id']] ?? null;
            if ($lesson['progress'] && (int) $lesson['progress']['quiz_passed'] === 1) {
                $passed++;
            }
            $key = (int) ($lesson['section_id'] ?? 0);
            if (! isset($grouped[$key])) {
                $key = 0;
            }
            $grouped[$key]['lessons'][] = $lesson;
        }
        $grouped = array_filter($grouped, static fn ($g) => $g['lessons'] !== []);

        return view('academy/course', [
            'title'    => $course['title'],
            'course'   => $course,
            'grouped'  => $grouped,
            'total'    => count($lessons),
            'passed'   => $passed,
            'rate'     => count($lessons) > 0 ? (int) round($passed / count($lessons) * 100) : 0,
            'summary'  => $this->progress->summaryForUser($userId),
        ]);
    }

    /**
     * 강의 영상 시청
     */
    public function lesson(int $id): string
    {
        $lesson = $this->loadLesson($id);
        $userId = current_user_id();

        $progress = $this->progress->get($userId, $id);
        if (! $progress) {
            $progress = $this->progress->updateVideo($userId, $id, 0, false);
        }

        return view('academy/lesson', [
            'title'    => $lesson['title'],
            'lesson'   => $lesson,
            'progress' => $progress,
            'next'     => $this->lessons->nextLesson($lesson),
            'src'      => video_src($lesson),
        ]);
    }

    /**
     * (AJAX) 영상 진도 저장
     */
    public function progress(int $id): ResponseInterface
    {
        $lesson  = $this->loadLesson($id);
        $seconds = (int) $this->request->getPost('seconds');
        $done    = (bool) $this->request->getPost('done');

        $row = $this->progress->updateVideo(current_user_id(), (int) $lesson['id'], $seconds, $done);

        return $this->response->setJSON([
            'ok'           => true,
            'video_status' => (int) $row['video_status'],
            'seconds'      => (int) $row['video_seconds'],
        ]);
    }

    /**
     * 시험 응시 화면
     */
    public function quiz(int $id): string|RedirectResponse
    {
        $lesson    = $this->loadLesson($id);
        $userId    = current_user_id();
        $progress  = $this->progress->get($userId, $id);
        $questions = $this->questions->byLesson($id);

        if ($questions === []) {
            return redirect()->to('/academy/lesson/' . $id)->with('info', '이 강의에는 아직 등록된 시험 문항이 없습니다.');
        }

        if ((int) $lesson['require_video'] === 1 && (! $progress || (int) $progress['video_status'] !== VIDEO_DONE)) {
            return redirect()->to('/academy/lesson/' . $id)->with('error', '강의 영상을 끝까지 시청한 후 시험에 응시할 수 있습니다.');
        }

        return view('academy/quiz', [
            'title'     => $lesson['title'] . ' - 시험',
            'lesson'    => $lesson,
            'questions' => $questions,
            'progress'  => $progress,
        ]);
    }

    /**
     * 시험 제출 → 채점
     */
    public function submitQuiz(int $id): RedirectResponse
    {
        $lesson    = $this->loadLesson($id);
        $userId    = current_user_id();
        $questions = $this->questions->byLesson($id);

        if ($questions === []) {
            return redirect()->to('/academy/lesson/' . $id);
        }

        $answers = (array) $this->request->getPost('answer');
        $correct = 0;
        $record  = [];

        foreach ($questions as $q) {
            $given               = (int) ($answers[$q['id']] ?? 0);
            $record[$q['id']]    = $given;
            if ($given === (int) $q['answer']) {
                $correct++;
            }
        }

        $total  = count($questions);
        $score  = (int) round($correct / $total * 100);
        $passed = $score >= (int) $lesson['pass_score'];

        $attemptId = $this->attempts->insert([
            'user_id'   => $userId,
            'lesson_id' => $id,
            'score'     => $score,
            'correct'   => $correct,
            'total'     => $total,
            'passed'    => $passed ? 1 : 0,
            'answers'   => json_encode($record),
        ]);

        $this->progress->recordQuiz($userId, $id, $score, $passed);

        return redirect()->to('/academy/quiz/' . $id . '/result/' . $attemptId);
    }

    /**
     * 시험 결과
     */
    public function quizResult(int $id, int $attemptId): string
    {
        $lesson  = $this->loadLesson($id);
        $attempt = $this->attempts->find($attemptId);

        if (! $attempt || (int) $attempt['user_id'] !== current_user_id() || (int) $attempt['lesson_id'] !== $id) {
            throw PageNotFoundException::forPageNotFound();
        }

        $given     = json_decode($attempt['answers'] ?? '[]', true) ?: [];
        $questions = $this->questions->byLesson($id);

        return view('academy/quiz_result', [
            'title'     => $lesson['title'] . ' - 시험 결과',
            'lesson'    => $lesson,
            'attempt'   => $attempt,
            'questions' => $questions,
            'given'     => $given,
            'next'      => $this->lessons->nextLesson($lesson),
            'progress'  => $this->progress->get(current_user_id(), $id),
        ]);
    }

    /**
     * 나의 교육 현황
     */
    public function mypage(): string
    {
        $userId      = current_user_id();
        $progressMap = $this->progress->mapByUser($userId);
        $courses     = $this->courses->activeList();

        foreach ($courses as &$course) {
            $course['lessons'] = $this->lessons->byCourse((int) $course['id']);
            foreach ($course['lessons'] as &$lesson) {
                $lesson['progress'] = $progressMap[(int) $lesson['id']] ?? null;
            }
            unset($lesson);
        }
        unset($course);

        return view('academy/mypage', [
            'title'    => '나의 교육 현황',
            'courses'  => $courses,
            'summary'  => $this->progress->summaryForUser($userId),
            'attempts' => $this->attempts->where('user_id', $userId)->orderBy('id', 'DESC')->findAll(20),
        ]);
    }

    public function howto(): string
    {
        return view('academy/howto', ['title' => '사이트 이용법']);
    }

    private function loadLesson(int $id): array
    {
        $lesson = $this->lessons->findWithCourse($id);
        if (! $lesson || (int) $lesson['is_active'] !== 1) {
            throw PageNotFoundException::forPageNotFound('강의를 찾을 수 없습니다.');
        }

        return $lesson;
    }
}
