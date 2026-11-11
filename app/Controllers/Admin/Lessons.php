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
                'ref_no' => '', 'lecturer' => '', 'lecture_date' => '', 'is_required' => 0,
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

        $videoType   = $this->request->getPost('video_type');
        $lectureDate = trim((string) $this->request->getPost('lecture_date'));
        $data        = [
            'course_id'     => (int) $this->request->getPost('course_id'),
            'section_id'    => (int) $this->request->getPost('section_id') ?: null,
            'title'         => trim($this->request->getPost('title')),
            'description'   => trim((string) $this->request->getPost('description')),
            'ref_no'        => trim((string) $this->request->getPost('ref_no')) ?: null,
            'lecturer'      => trim((string) $this->request->getPost('lecturer')) ?: null,
            'lecture_date'  => $lectureDate !== '' ? $lectureDate : null,
            'is_required'   => $this->request->getPost('is_required') ? 1 : 0,
            'video_type'    => $videoType,
            'video_url'     => trim((string) $this->request->getPost('video_url')),
            'duration_min'  => (int) $this->request->getPost('duration_min'),
            'pass_score'    => (int) $this->request->getPost('pass_score'),
            'require_video' => $this->request->getPost('require_video') ? 1 : 0,
            'sort_order'    => (int) $this->request->getPost('sort_order'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // 유튜브 URL 검증 (자료실 번호가 있으면 비워 두고 나중에 본사 자료실에서 가져올 수 있음)
        if ($videoType === VIDEO_TYPE_YOUTUBE && youtube_id($data['video_url']) === '') {
            if ($data['video_url'] !== '' || $data['ref_no'] === null) {
                return redirect()->back()->withInput()->with('error', '올바른 유튜브 주소(또는 영상 ID)를 입력하세요. 자료실 번호만 입력하고 비워 두면 나중에 "본사 자료실에서 영상 주소 가져오기"로 채울 수 있습니다.');
            }
        }

        if ($videoType === VIDEO_TYPE_URL && $data['video_url'] === '' && $data['ref_no'] === null) {
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
     * 본사 자료실(bbs)에서 자료실 번호(ref_no)로 영상 주소 가져오기
     *
     *  - 영상 주소가 비어 있고 자료실 번호가 있는 강의만 채운다 (이미 입력된 주소는 유지)
     *  - bbs.youtube 가 있으면 유튜브, 없고 bbs.vid 가 있으면 본사 서버의 영상 파일 주소
     *  - 본사 DB 접속 정보는 .env 의 database.booktree.* (booktree.remoteAuth 와 무관하게 사용)
     */
    public function syncFromHq(): RedirectResponse
    {
        $model    = model(LessonModel::class);
        $courseId = (int) $this->request->getPost('course_id');
        $back     = $courseId ? '/admin/courses/' . $courseId . '/lessons' : '/admin/courses';

        // 1) 영상 강의 목록(컬럼 + 과정 4개 + 강의 45건)이 아직 DB 에 없으면 먼저 반영한다.
        try {
            $prepared = $this->prepareVideoList();

            $builder = $model->where('ref_no IS NOT NULL')->where('ref_no !=', '')
                ->groupStart()->where('video_url', '')->orWhere('video_url IS NULL')->groupEnd()
                ->groupStart()->where('video_file', '')->orWhere('video_file IS NULL')->groupEnd();
            if ($courseId) {
                $builder->where('course_id', $courseId);
            }
            $targets = $builder->findAll();
        } catch (\Throwable $e) {
            log_message('error', '영상 강의 목록 준비 실패: ' . $e->getMessage());

            return redirect()->to($back)->with('error', '영상 강의 목록을 준비하지 못했습니다. (' . $e->getMessage() . ')');
        }

        if ($targets === []) {
            return redirect()->to($back)->with('info', trim($prepared . ' 영상 주소가 비어 있는 강의가 없습니다. (이미 입력된 주소는 바꾸지 않습니다)'));
        }

        $refs = array_values(array_unique(array_map(static fn ($l) => (int) $l['ref_no'], $targets)));

        // 2) 본사 자료실(bbs)에서 영상 주소 조회
        try {
            $hq   = \Config\Database::connect('booktree');
            $rows = $hq->table('bbs')->select('num, youtube, vid, vid_ext')->whereIn('num', $refs)->get()->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', '본사 자료실 조회 실패: ' . $e->getMessage());

            return redirect()->to($back)->with('error', trim($prepared . ' 본사 DB 에 연결하지 못했습니다. .env 의 database.booktree.* 설정을 확인하세요. (' . $e->getMessage() . ')'));
        }

        $byNum = [];
        foreach ($rows as $row) {
            $byNum[(int) $row['num']] = $row;
        }

        // 3) 강의에 반영
        $updated = 0;
        $noVideo = [];
        $missing = [];

        try {
            foreach ($targets as $lesson) {
                $num = (int) $lesson['ref_no'];
                if (! isset($byNum[$num])) {
                    $missing[] = $num;

                    continue;
                }
                $post    = $byNum[$num];
                $youtube = trim((string) $post['youtube']);
                $vid     = trim((string) $post['vid']);

                if ($youtube !== '' && youtube_id($youtube) !== '') {
                    $model->update($lesson['id'], ['video_type' => VIDEO_TYPE_YOUTUBE, 'video_url' => $youtube]);
                    $updated++;
                } elseif ($vid !== '') {
                    $model->update($lesson['id'], ['video_type' => VIDEO_TYPE_URL, 'video_url' => HQ_VIDEO_BASE . $vid]);
                    $updated++;
                } else {
                    $noVideo[] = $num;
                }
            }
        } catch (\Throwable $e) {
            log_message('error', '영상 주소 저장 실패: ' . $e->getMessage());

            return redirect()->to($back)->with('error', trim($prepared . ' 영상 주소 ' . $updated . '건 저장 후 오류가 났습니다. (' . $e->getMessage() . ')'));
        }

        $msg = trim($prepared . ' 본사 자료실에서 영상 주소 ' . $updated . '건을 가져왔습니다.');
        if ($noVideo !== []) {
            $msg .= ' 영상이 없는 글: ' . implode(', ', $noVideo) . '.';
        }
        if ($missing !== []) {
            $msg .= ' 자료실에 없는 번호: ' . implode(', ', $missing) . '.';
        }

        return redirect()->to($back)->with($updated > 0 || $prepared !== '' ? 'success' : 'info', $msg);
    }

    /**
     * 영상 강의 목록이 아직 DB 에 반영되지 않았으면 반영한다. (deploy/videos_2026-11-02.sql 과 같은 작업)
     *
     * 카페24 에는 SSH 가 없어 `spark migrate` 를 못 돌리므로, 관리자 버튼만으로 끝낼 수 있게 한다.
     * 이미 반영돼 있으면 아무것도 하지 않는다. 반환값은 화면에 보여줄 안내 문구(한 일이 없으면 빈 문자열).
     */
    private function prepareVideoList(): string
    {
        $db    = db_connect();
        $notes = [];

        // lessons 테이블의 자료실 번호 / 강연자 / 교육일 / 필수 컬럼 (AddLessonMeta 마이그레이션과 동일)
        $columns = [
            'ref_no'       => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'after' => 'description', 'comment' => '본사 자료실(bbs) 글 번호'],
            'lecturer'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'ref_no'],
            'lecture_date' => ['type' => 'DATE', 'null' => true, 'after' => 'lecturer'],
            'is_required'  => ['type' => 'TINYINT', 'null' => false, 'default' => 0, 'after' => 'lecture_date', 'comment' => '1=신규 원장 필수 시청(★)'],
        ];

        $added = false;
        foreach ($columns as $name => $definition) {
            if (! $db->fieldExists($name, 'lessons')) {
                \Config\Database::forge()->addColumn('lessons', [$name => $definition]);
                $db->resetDataCache();
                $added = true;
            }
        }

        if ($added) {
            $notes[] = 'DB 구조를 업데이트했습니다.';

            // 나중에 로컬에서 spark migrate 를 돌려도 같은 작업을 반복하지 않도록 이력을 남긴다.
            $version = '2026-11-02-000001';
            if ($db->tableExists('migrations') && $db->table('migrations')->where('version', $version)->countAllResults() === 0) {
                $last = $db->table('migrations')->selectMax('batch')->get()->getRowArray();
                $db->table('migrations')->insert([
                    'version'   => $version,
                    'class'     => 'App\Database\Migrations\AddLessonMeta',
                    'group'     => 'default',
                    'namespace' => 'App',
                    'time'      => time(),
                    'batch'     => (int) ($last['batch'] ?? 0) + 1,
                ]);
            }
        }

        // 자료실 번호가 달린 강의가 하나도 없으면 영상 리스트(과정 4개 + 강의 45건)를 등록
        $countListed = static fn (): int => (int) $db->table('lessons')->where('ref_no IS NOT NULL')->where('ref_no !=', '')->countAllResults();

        if ($countListed() === 0) {
            \Config\Database::seeder()->call(\App\Database\Seeds\VideoListSeeder::class);
            $notes[] = '영상 강의 ' . $countListed() . '건을 등록했습니다.';
        }

        return implode(' ', $notes);
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
