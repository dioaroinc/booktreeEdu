<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $table         = 'sections';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['course_id', 'title', 'sort_order'];
    protected $useTimestamps = true;

    public function byCourse(int $courseId): array
    {
        return $this->where('course_id', $courseId)->orderBy('sort_order')->orderBy('id')->findAll();
    }
}
