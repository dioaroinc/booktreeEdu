<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgressModel extends Model
{
    protected $table         = 'progress';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id', 'lesson_id', 'video_status', 'video_seconds', 'video_done_at',
        'quiz_attempts', 'quiz_best', 'quiz_passed', 'quiz_passed_at',
    ];
    protected $useTimestamps = true;

    public function get(int $userId, int $lessonId): ?array
    {
        return $this->where(['user_id' => $userId, 'lesson_id' => $lessonId])->first();
    }

    /**
     * 회원의 진도 전체를 lesson_id 키로 반환
     */
    public function mapByUser(int $userId): array
    {
        $map = [];
        foreach ($this->where('user_id', $userId)->findAll() as $row) {
            $map[(int) $row['lesson_id']] = $row;
        }

        return $map;
    }

    public function getOrCreate(int $userId, int $lessonId): array
    {
        $row = $this->get($userId, $lessonId);
        if ($row) {
            return $row;
        }

        $id = $this->insert(['user_id' => $userId, 'lesson_id' => $lessonId]);

        return $this->find($id);
    }

    /**
     * 영상 재생 위치 저장 및 시청완료 처리
     */
    public function updateVideo(int $userId, int $lessonId, int $seconds, bool $done): array
    {
        $row  = $this->getOrCreate($userId, $lessonId);
        $data = [
            'video_seconds' => max($seconds, 0),
        ];

        if ($done) {
            if ((int) $row['video_status'] !== VIDEO_DONE) {
                $data['video_status']  = VIDEO_DONE;
                $data['video_done_at'] = date('Y-m-d H:i:s');
            }
        } elseif ((int) $row['video_status'] === VIDEO_NONE) {
            $data['video_status'] = VIDEO_STARTED;
        }

        $this->update($row['id'], $data);

        return $this->find($row['id']);
    }

    /**
     * 시험 결과 반영
     */
    public function recordQuiz(int $userId, int $lessonId, int $score, bool $passed): array
    {
        $row  = $this->getOrCreate($userId, $lessonId);
        $data = [
            'quiz_attempts' => (int) $row['quiz_attempts'] + 1,
            'quiz_best'     => max((int) $row['quiz_best'], $score),
        ];

        if ($passed && (int) $row['quiz_passed'] !== 1) {
            $data['quiz_passed']    = 1;
            $data['quiz_passed_at'] = date('Y-m-d H:i:s');
        }

        $this->update($row['id'], $data);

        return $this->find($row['id']);
    }

    /**
     * 회원별 요약: 전체 강의 수 / 통과 수 / 통과율
     */
    public function summaryForUser(int $userId): array
    {
        $total  = $this->db->table('lessons')->where('is_active', 1)->countAllResults();
        $passed = $this->db->table('progress p')
            ->join('lessons l', 'l.id = p.lesson_id')
            ->where('p.user_id', $userId)
            ->where('p.quiz_passed', 1)
            ->where('l.is_active', 1)
            ->countAllResults();

        return [
            'total'  => $total,
            'passed' => $passed,
            'rate'   => $total > 0 ? (int) round($passed / $total * 100) : 0,
        ];
    }

    /**
     * 전체 회원 수강 현황 (관리자)
     */
    public function overview(): array
    {
        return $this->db->table('users u')
            ->select('u.id, u.login_id, u.name, u.branch_name, u.type, u.status, u.last_login_at')
            ->select('(SELECT COUNT(*) FROM progress p JOIN lessons l ON l.id = p.lesson_id WHERE p.user_id = u.id AND p.quiz_passed = 1 AND l.is_active = 1) AS passed_count')
            ->select('(SELECT COUNT(*) FROM progress p JOIN lessons l ON l.id = p.lesson_id WHERE p.user_id = u.id AND p.video_status = 2 AND l.is_active = 1) AS watched_count')
            ->select('(SELECT MAX(p.updated_at) FROM progress p WHERE p.user_id = u.id) AS last_activity')
            ->where('u.type', USER_TYPE_STUDENT)
            ->orderBy('u.name')
            ->get()->getResultArray();
    }
}
