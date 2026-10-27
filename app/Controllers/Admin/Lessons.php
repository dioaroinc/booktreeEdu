<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\ProgressModel;
use App\Models\QuestionModel;
use App\Models\QuizAttemptModel;
use App\Models\SectionModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Lessons extends BaseController
{
    /**
     * 과정별 강의 목록
     */
    public function index(int $courseId): string
    {
        $course = model(CourseModel::class)->find($courseId);
        if (! $course) {
            throw PageNotFoundException::forPageNotFound();
        }

        $sections = model(SectionModel::class)->byCourse($courseId);
        $lessons  = model(LessonModel::class)->byCourse($courseId, false);

        $sectionTitles = [];
        foreach ($sections as $s) {
            $sectionTitles[$s['id']] = $s['title'];
        }

        return view('admin/lessons/index', [
            'title'         => $course['title'] . ' - 강의 관리',
            'course'        => $course,
            'sections'      => $sections,
            'sectionTitles' => $sectionTitles,
            'lessons'       => $lessons,
        ]);
    }

    /**
     * 강의 등록/수정 폼
     */
    public function form(int $courseId = 0, int $id = 0): string
    {
        $lesson = null;
        if ($id) {
            $lesson = model(LessonModel::class)->find($id);
            if (! $lesson) {
                throw PageNotFoundException::forPageNotFound();
            }
            $courseId = (int) $lesson['course_id'];
        }

        $course = model(CourseModel::class)->find($courseId);
        if (! $course) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/lessons/form', [
            'title'    => $lesson ? '강의 수정' : '강의 등록',
            'course'   => $course,
            'sections' => model(SectionModel::class)->byCourse($courseId),
            'lesson'   => $lesson ?? [
                'id' => 0, 'course_id' => $courseId, 'section_id' => null, 'title' => '', 'description' => '',
                'video_type' => VIDEO_TYPE_YOUTUBE, 'video_url' => '', 'video_file' => '',
                'duration_min' => 10, 'pass_score' => 80, 'require_video' => 1, 'sort_order' => 0, 'is_active' => 1,
            ],
            'maxUpload' => ini_get('upload_max_filesize') . ' / post_max_size ' . ini_get('post_max_size'),
        ]);
    }

    /**
     * 강의 저장 (영상 파일 업로드 포함)
     */
    public function save(): RedirectResponse
    {
        $rules = [
            'course_id'    => 'required|integer',
            'title'        => 'required|max_length[200]',
            'video_type'   => 'required|in_list[youtube,file,url]',
            'duration_min' => 'permit_empty|integer',
            'pass_score'   => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'sort_order'   => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $model = model(LessonModel::class);
        $id    = (int) $this->request->getPost('id');
        $old   = $id ? $model->find($id) : null;

        $videoType = $this->request->getPost('video_type');
        $data      = [
            'course_id'     => (int) $this->request->getPost('course_id'),
            'section_id'    => (int) $this->request->getPost('section_id') ?: null,
            'title'         => trim($this->request->getPost('title')),
            'description'   => trim((string) $this->request->getPost('description')),
            'video_type'    => $videoType,
            'video_url'     => trim((string) $this->request->getPost('video_url')),
            'duration_min'  => (int) $this->request->getPost('duration_min'),
            'pass_score'    => (int) $this->request->getPost('pass_score'),
            'require_video' => $this->request->getPost('require_video') ? 1 : 0,
            'sort_order'    => (int) $this->request->getPost('sort_order'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // 유튜브 URL 검증
        if ($videoType === VIDEO_TYPE_YOUTUBE && youtube_id($data['video_url']) === '') {
            return redirect()->back()->withInput()->with('error', '올바른 유튜브 주소(또는 영상 ID)를 입력하세요.');
        }

        if ($videoType === VIDEO_TYPE_URL && $data['video_url'] === '') {
            return redirect()->back()->withInput()->with('error', '영상 파일 주소(URL)를 입력하세요.');
        }

        // 파일 업로드
        if ($videoType === VIDEO_TYPE_FILE) {
            $file = $this->request->getFile('video_file');

            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $allowed = ['mp4', 'webm', 'ogg', 'm4v', 'mov'];
                $ext     = strtolower($file->getClientExtension());
                $mime    = (string) $file->getMimeType();

                // 확장자와 실제 파일 내용(MIME) 둘 다 확인
                $mimeOk = str_starts_with($mime, 'video/') || $mime === 'application/octet-stream';
                if (! in_array($ext, $allowed, true) || ! $mimeOk) {
                    return redirect()->back()->withInput()->with('error', '업로드 가능한 영상 형식: ' . implode(', ', $allowed) . ' (감지된 형식: ' . $mime . ')');
                }

                if (! is_dir(VIDEO_UPLOAD_PATH)) {
                    mkdir(VIDEO_UPLOAD_PATH, 0775, true);
                }

                $newName = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $file->move(VIDEO_UPLOAD_PATH, $newName);
                $data['video_file'] = $newName;

                // 이전 파일 정리
                if ($old && $old['video_file'] && $old['video_file'] !== $newName && is_file(VIDEO_UPLOAD_PATH . $old['video_file'])) {
                    @unlink(VIDEO_UPLOAD_PATH . $old['video_file']);
                }
            } elseif ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                return redirect()->back()->withInput()->with('error', '파일 업로드 실패: ' . $file->getErrorString() . ' (서버 업로드 제한: ' . ini_get('upload_max_filesize') . ')');
            } elseif (! $old || ! $old['video_file']) {
                return redirect()->back()->withInput()->with('error', '영상 파일을 선택하세요.');
            }
        }

        if ($id) {
            $model->update($id, $data);
        } else {
            $id = $model->insert($data);
        }

        return redirect()->to('/admin/courses/' . $data['course_id'] . '/lessons')->with('success', '강의가 저장되었습니다.');
    }

    public function delete(int $id): RedirectResponse
    {
        $model  = model(LessonModel::class);
        $lesson = $model->find($id);
        if (! $lesson) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($lesson['video_file'] && is_file(VIDEO_UPLOAD_PATH . $lesson['video_file'])) {
            @unlink(VIDEO_UPLOAD_PATH . $lesson['video_file']);
        }

        model(QuestionModel::class)->where('lesson_id', $id)->delete();
        model(ProgressModel::class)->where('lesson_id', $id)->delete();
        model(QuizAttemptModel::class)->where('lesson_id', $id)->delete();
        $model->delete($id);

        return redirect()->to('/admin/courses/' . $lesson['course_id'] . '/lessons')->with('success', '강의가 삭제되었습니다.');
    }

    /**
     * 단원 추가/수정
     */
    public function saveSection(): RedirectResponse
    {
        $courseId = (int) $this->request->getPost('course_id');
        $id       = (int) $this->request->getPost('id');
        $title    = trim((string) $this->request->getPost('title'));

        if ($title === '' || ! $courseId) {
            return redirect()->back()->with('error', '단원 이름을 입력하세요.');
        }

        $data = [
            'course_id'  => $courseId,
            'title'      => $title,
            'sort_order' => (int) $this->request->getPost('sort_order'),
        ];

        $model = model(SectionModel::class);
        $id ? $model->update($id, $data) : $model->insert($data);

        return redirect()->to('/admin/courses/' . $courseId . '/lessons')->with('success', '단원이 저장되었습니다.');
    }

    public function deleteSection(int $id): RedirectResponse
    {
        $model   = model(SectionModel::class);
        $section = $model->find($id);
        if (! $section) {
            throw PageNotFoundException::forPageNotFound();
        }

        model(LessonModel::class)->where('section_id', $id)->set('section_id', null)->update();
        $model->delete($id);

        return redirect()->to('/admin/courses/' . $section['course_id'] . '/lessons')->with('success', '단원이 삭제되었습니다. (소속 강의는 유지됩니다)');
    }
}
