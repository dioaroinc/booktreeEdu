<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizAttemptModel extends Model
{
    protected $table         = 'quiz_attempts';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['user_id', 'lesson_id', 'score', 'correct', 'total', 'passed', 'answers'];
    protected $useTimestamps = true;
    protected $updatedField  = '';

    public function byUserLesson(int $userId, int $lessonId): array
    {
        return $this->where(['user_id' => $userId, 'lesson_id' => $lessonId])
            ->orderBy('id', 'DESC')->findAll();
    }
}
