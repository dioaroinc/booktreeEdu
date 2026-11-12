<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProgressModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * 관리자 운영 · 셋팅 가이드
 *
 * 설명 글은 뷰(admin/guide.php)에 있고, 여기서는 "지금 이 서버의 상태"만 점검해서 넘긴다.
 * 점검이 실패해도 가이드 화면은 열려야 하므로 모든 조회는 예외를 삼킨다.
 */
class Guide extends BaseController
{
    /** 설치할 때 함께 들어가는 데모 과정 (DemoSeeder 가 만든 것) */
    private const DEMO_COURSE_TITLES = ['신규원장교육', '지점SW 사용교육', '보수교육'];

    public function index(): string
    {
        return view('admin/guide', [
            'title'  => '운영 가이드',
            'checks' => $this->checks(),
            'server' => [
                'PHP 버전'        => PHP_VERSION,
                '실행 환경'       => ENVIRONMENT === 'production' ? '운영 (production)' : ENVIRONMENT,
                '사이트 주소'     => base_url(),
                '파일 1개 업로드 한도' => (string) ini_get('upload_max_filesize'),
                '전송 전체 한도'  => (string) ini_get('post_max_size'),
                '실행 시간 제한'  => ini_get('max_execution_time') . '초',
            ],
        ]);
    }

    /**
     * 데모 과정과 그 안의 데모 강의를 모두 삭제한다. (강의의 시험 문항 · 수강 기록 · 응시 기록 포함)
     * 데모 수강생 계정(demo)은 건드리지 않는다.
     */
    public function removeDemo(): RedirectResponse
    {
        $db = db_connect();

        try {
            $courses = $this->demoCourses();
            if ($courses === []) {
                return redirect()->to('/admin/guide')->with('info', '삭제할 데모 과정이 없습니다. (이미 삭제되었습니다)');
            }

            $courseIds = array_map('intval', array_column($courses, 'id'));
            $lessons   = $db->table('lessons')->select('id, video_file')->whereIn('course_id', $courseIds)->get()->getResultArray();
            $lessonIds = array_map('intval', array_column($lessons, 'id'));

            $db->transException(true)->transStart();
            if ($lessonIds !== []) {
                $db->table('questions')->whereIn('lesson_id', $lessonIds)->delete();
                $db->table('progress')->whereIn('lesson_id', $lessonIds)->delete();
                $db->table('quiz_attempts')->whereIn('lesson_id', $lessonIds)->delete();
                $db->table('lessons')->whereIn('id', $lessonIds)->delete();
            }
            $db->table('sections')->whereIn('course_id', $courseIds)->delete();
            $db->table('courses')->whereIn('id', $courseIds)->delete();
            $db->transComplete();

            // DB 삭제가 끝난 뒤에 업로드 파일 정리 (데모 강의는 보통 외부 주소라 파일이 없다)
            foreach ($lessons as $lesson) {
                if ($lesson['video_file'] && is_file(VIDEO_UPLOAD_PATH . $lesson['video_file'])) {
                    @unlink(VIDEO_UPLOAD_PATH . $lesson['video_file']);
                }
            }
        } catch (\Throwable $e) {
            log_message('error', '데모 과정 삭제 실패: ' . $e->getMessage());

            return redirect()->to('/admin/guide')->with('error', '데모 과정을 삭제하지 못했습니다. (' . $e->getMessage() . ')');
        }

        return redirect()->to('/admin/guide')->with('success', '데모 과정 ' . count($courses) . '개와 데모 강의 ' . count($lessonIds) . '개를 삭제했습니다.');
    }

    /**
     * 남아 있는 데모 과정 목록 (강의 수 포함)
     *
     * @return list<array{id:string,title:string,is_active:string,lesson_count:string}>
     */
    private function demoCourses(): array
    {
        return db_connect()->table('courses c')
            ->select('c.id, c.title, c.is_active')
            ->select('(SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) AS lesson_count', false)
            ->whereIn('c.title', self::DEMO_COURSE_TITLES)
            ->orderBy('c.id')
            ->get()->getResultArray();
    }

    /**
     * 처음 셋팅할 때 확인할 항목들. state: ok(정상) / warn(조치 필요) / info(참고)
     *
     * @return list<array{state:string,label:string,detail:string,action?:array{url:string,label:string,confirm:string}}>
     */
    private function checks(): array
    {
        $checks = [];
        $add    = static function (string $state, string $label, string $detail) use (&$checks): void {
            $checks[] = ['state' => $state, 'label' => $label, 'detail' => $detail];
        };

        // 1) 초기 관리자 비밀번호
        try {
            $admin = model(UserModel::class)->findByLoginId('admin');
            if ($admin && (int) $admin['status'] === 1 && password_verify('admin1234', (string) $admin['password'])) {
                $add('warn', '초기 관리자 비밀번호', 'admin 계정이 설치할 때의 초기 비밀번호 그대로입니다. 회원 관리에서 바로 바꿔 주세요.');
            } elseif ($admin && (int) $admin['status'] === 1) {
                $add('ok', '초기 관리자 비밀번호', 'admin 계정의 비밀번호가 변경되어 있습니다.');
            } else {
                $add('ok', '초기 관리자 계정', 'admin 계정이 없거나 정지되어 있습니다.');
            }
        } catch (\Throwable $e) {
            $add('info', '초기 관리자 비밀번호', '확인하지 못했습니다.');
        }

        // 2) 본사 계정 로그인 연동
        $remote = env('booktree.remoteAuth');
        if ($remote === true || $remote === 'true') {
            $hasHost = (string) env('database.booktree.hostname') !== '';
            $add(
                $hasHost ? 'ok' : 'warn',
                '본사(지점SW) 계정 로그인',
                $hasHost
                    ? '켜져 있습니다. 원장님은 지점SW 아이디·비밀번호로 바로 로그인할 수 있습니다.'
                    : '켜져 있지만 본사 DB 접속 정보(database.booktree.*)가 비어 있습니다.'
            );
        } else {
            $add('info', '본사(지점SW) 계정 로그인', '꺼져 있습니다. 회원 관리에서 직접 만든 계정만 로그인할 수 있습니다.');
        }

        // 2-1) 설치할 때 들어간 데모 과정이 남아 있는지
        try {
            $demo = $this->demoCourses();
            if ($demo !== []) {
                $names   = implode(', ', array_map(static fn ($c) => $c['title'] . '(강의 ' . (int) $c['lesson_count'] . '개)', $demo));
                $lessons = array_sum(array_map(static fn ($c) => (int) $c['lesson_count'], $demo));
                $checks[] = [
                    'state'  => 'warn',
                    'label'  => '데모 과정',
                    'detail' => '설치할 때 들어간 데모 과정이 남아 있습니다: ' . $names . '. 실제 운영에 쓰지 않으면 삭제하세요.',
                    'action' => [
                        'url'     => '/admin/guide/remove-demo',
                        'label'   => '데모 과정 삭제',
                        'confirm' => '데모 과정 ' . count($demo) . '개와 그 안의 강의 ' . $lessons . '개를 삭제합니다. 강의의 시험 문항과 수강 기록도 함께 지워지며 되돌릴 수 없습니다. 삭제할까요?',
                    ],
                ];
            } else {
                $add('ok', '데모 과정', '데모 과정이 남아 있지 않습니다.');
            }
        } catch (\Throwable $e) {
            $add('info', '데모 과정', '확인하지 못했습니다.');
        }

        // 3) 영상 / 시험이 비어 있는 공개 강의
        try {
            $db      = db_connect();
            $noVideo = (int) $db->query(
                'SELECT COUNT(*) AS c FROM lessons l WHERE ' . ProgressModel::ACTIVE_LESSON_SQL . ' AND ('
                . '(video_type = ? AND (video_file IS NULL OR video_file = \'\')) OR '
                . '(video_type <> ? AND (video_url IS NULL OR video_url = \'\')))',
                [VIDEO_TYPE_FILE, VIDEO_TYPE_FILE]
            )->getRow('c');
            $noQuiz = (int) $db->query(
                'SELECT COUNT(*) AS c FROM lessons l WHERE ' . ProgressModel::ACTIVE_LESSON_SQL . ' '
                . 'AND NOT EXISTS (SELECT 1 FROM questions q WHERE q.lesson_id = l.id)'
            )->getRow('c');
            $total = (int) $db->table('lessons l')->where(ProgressModel::ACTIVE_LESSON_SQL, null, false)->countAllResults();

            $add(
                $noVideo > 0 ? 'warn' : 'ok',
                '영상이 없는 공개 강의',
                $noVideo > 0
                    ? "공개 강의 {$total}개 중 {$noVideo}개에 영상이 없습니다. 수강생에게 「영상이 아직 등록되지 않았습니다」로 보입니다."
                    : "공개 강의 {$total}개 모두 영상이 등록되어 있습니다."
            );
            $add(
                'info',
                '시험 문항이 없는 공개 강의',
                $noQuiz > 0
                    ? "{$noQuiz}개입니다. 이런 강의는 영상을 끝까지 보면 바로 통과 처리됩니다."
                    : '없습니다. 모든 공개 강의에 시험 문항이 있습니다.'
            );
        } catch (\Throwable $e) {
            $add('info', '강의 점검', '확인하지 못했습니다. (' . $e->getMessage() . ')');
        }

        // 4) 서버 폴더 쓰기 권한
        $folders = [
            '영상 업로드 폴더 (public/uploads/videos)' => VIDEO_UPLOAD_PATH,
            '로그 폴더 (writable/logs)'                 => WRITEPATH . 'logs',
            '세션 폴더 (writable/session)'              => WRITEPATH . 'session',
        ];
        foreach ($folders as $label => $path) {
            $ok = is_dir($path) && is_writable($path);
            $add($ok ? 'ok' : 'warn', $label, $ok ? '쓰기 가능합니다.' : '쓸 수 없습니다. FTP 에서 폴더 권한을 755(안 되면 777)로 바꿔 주세요.');
        }

        return $checks;
    }
}
