<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table         = 'courses';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['title', 'subtitle', 'description', 'icon', 'color', 'sort_order', 'is_active'];
    protected $useTimestamps = true;

    public function activeList(): array
    {
        return $this->where('is_active', 1)->orderBy('sort_order')->orderBy('id')->findAll();
    }

    /**
     * 과정 목록 + 강의 수 (관리자/메인용)
     */
    public function listWithCounts(bool $activeOnly = false): array
    {
        $builder = $this->db->table('courses c')
            ->select('c.*, (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id AND l.is_active = 1) AS lesson_count')
            ->select('(SELECT COALESCE(SUM(l.duration_min),0) FROM lessons l WHERE l.course_id = c.id AND l.is_active = 1) AS total_minutes')
            ->orderBy('c.sort_order')->orderBy('c.id');

        if ($activeOnly) {
            $builder->where('c.is_active', 1);
        }

        return $builder->get()->getResultArray();
    }
}
