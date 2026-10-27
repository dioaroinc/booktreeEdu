<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonModel extends Model
{
    protected $table         = 'lessons';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'course_id', 'section_id', 'title', 'description', 'video_type', 'video_url', 'video_file',
        'duration_min', 'pass_score', 'require_video', 'sort_order', 'is_active',
    ];
    protected $useTimestamps = true;

    /**
     * 과정의 강의 목록 (문항 수 포함), 단원/정렬 순
     */
    public function byCourse(int $courseId, bool $activeOnly = true): array
    {
        $builder = $this->db->table('lessons l')
            ->select('l.*, (SELECT COUNT(*) FROM questions q WHERE q.lesson_id = l.id) AS question_count')
            ->where('l.course_id', $courseId)
            ->orderBy('l.sort_order')->orderBy('l.id');

        if ($activeOnly) {
            $builder->where('l.is_active', 1);
        }

        return $builder->get()->getResultArray();
    }

    public function findWithCourse(int $id): ?array
    {
        return $this->db->table('lessons l')
            ->select('l.*, c.title AS course_title, s.title AS section_title')
            ->select('(SELECT COUNT(*) FROM questions q WHERE q.lesson_id = l.id) AS question_count')
            ->join('courses c', 'c.id = l.course_id')
            ->join('sections s', 's.id = l.section_id', 'left')
            ->where('l.id', $id)
            ->get()->getRowArray();
    }

    /**
     * 같은 과정 내 다음 강의
     */
    public function nextLesson(array $lesson): ?array
    {
        $list  = $this->byCourse((int) $lesson['course_id']);
        $found = false;

        foreach ($list as $row) {
            if ($found) {
                return $row;
            }
            if ((int) $row['id'] === (int) $lesson['id']) {
                $found = true;
            }
        }

        return null;
    }

    public function countActive(): int
    {
        return $this->where('is_active', 1)->countAllResults();
    }
}
