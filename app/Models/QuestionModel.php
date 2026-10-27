<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table         = 'questions';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'lesson_id', 'question', 'choice1', 'choice2', 'choice3', 'choice4', 'answer', 'explanation', 'sort_order',
    ];
    protected $useTimestamps = true;

    public function byLesson(int $lessonId): array
    {
        return $this->where('lesson_id', $lessonId)->orderBy('sort_order')->orderBy('id')->findAll();
    }
}
