<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProgressModel;
use App\Models\UserModel;

/**
 * 관리자 운영 · 셋팅 가이드
 *
 * 설명 글은 뷰(admin/guide.php)에 있고, 여기서는 "지금 이 서버의 상태"만 점검해서 넘긴다.
 * 점검이 실패해도 가이드 화면은 열려야 하므로 모든 조회는 예외를 삼킨다.
 */
class Guide extends BaseController
{
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
     * 처음 셋팅할 때 확인할 항목들. state: ok(정상) / warn(조치 필요) / info(참고)
     *
     * @return list<array{state:string,label:string,detail:string}>
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
