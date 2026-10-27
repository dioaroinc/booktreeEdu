<?php

/**
 * 책나무 온라인 교육 공통 헬퍼
 */

if (! function_exists('is_logged_in')) {
    function is_logged_in(): bool
    {
        return (bool) session()->get('user_id');
    }
}

if (! function_exists('is_admin')) {
    function is_admin(): bool
    {
        return (int) session()->get('user_type') === USER_TYPE_ADMIN;
    }
}

if (! function_exists('current_user_id')) {
    function current_user_id(): int
    {
        return (int) session()->get('user_id');
    }
}

if (! function_exists('current_user_name')) {
    function current_user_name(): string
    {
        return (string) session()->get('user_name');
    }
}

if (! function_exists('youtube_id')) {
    /**
     * 유튜브 URL(watch / youtu.be / shorts / embed) 또는 ID 문자열에서 영상 ID 추출
     */
    function youtube_id(?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }

        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $url)) {
            return $url;
        }

        $patterns = [
            '/[?&]v=([A-Za-z0-9_-]{11})/',
            '/youtu\.be\/([A-Za-z0-9_-]{11})/',
            '/\/embed\/([A-Za-z0-9_-]{11})/',
            '/\/shorts\/([A-Za-z0-9_-]{11})/',
            '/\/live\/([A-Za-z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $m)) {
                return $m[1];
            }
        }

        return '';
    }
}

if (! function_exists('video_src')) {
    /**
     * 강의 레코드의 실제 재생 소스 URL
     */
    function video_src(array $lesson): string
    {
        switch ($lesson['video_type']) {
            case VIDEO_TYPE_FILE:
                return $lesson['video_file'] ? base_url('uploads/videos/' . $lesson['video_file']) : '';

            case VIDEO_TYPE_YOUTUBE:
                $id = youtube_id($lesson['video_url']);

                return $id ? 'https://www.youtube.com/embed/' . $id . '?enablejsapi=1&rel=0&modestbranding=1' : '';

            default:
                return (string) $lesson['video_url'];
        }
    }
}

if (! function_exists('format_minutes')) {
    function format_minutes(?int $minutes): string
    {
        $minutes = (int) $minutes;
        if ($minutes <= 0) {
            return '-';
        }
        if ($minutes < 60) {
            return $minutes . '분';
        }

        return intdiv($minutes, 60) . '시간 ' . ($minutes % 60 ? ($minutes % 60) . '분' : '');
    }
}

if (! function_exists('lesson_status_label')) {
    /**
     * 진도 레코드 → [상태코드, 라벨]
     */
    function lesson_status_label(?array $progress): array
    {
        if (! $progress) {
            return ['none', '미수강'];
        }
        if ((int) $progress['quiz_passed'] === 1) {
            return ['passed', '통과'];
        }
        if ((int) $progress['video_status'] === VIDEO_DONE) {
            return ['video_done', '시험 대기'];
        }
        if ((int) $progress['video_status'] === VIDEO_STARTED) {
            return ['watching', '수강중'];
        }

        return ['none', '미수강'];
    }
}

if (! function_exists('flash_alerts')) {
    /**
     * 세션 플래시 메시지(success / error)를 HTML 로 반환
     */
    function flash_alerts(): string
    {
        $html    = '';
        $session = session();

        foreach (['success', 'error', 'info'] as $type) {
            $msg = $session->getFlashdata($type);
            if ($msg) {
                $html .= '<div class="alert alert-' . $type . '">' . esc($msg) . '</div>';
            }
        }

        return $html;
    }
}
