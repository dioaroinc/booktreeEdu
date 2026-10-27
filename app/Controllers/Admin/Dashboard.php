<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\ProgressModel;
use App\Models\QuestionModel;
use App\Models\QuizAttemptModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $db = db_connect();

        $stats = [
            'students'  => model(UserModel::class)->where('type', USER_TYPE_STUDENT)->countAllResults(),
            'courses'   => model(CourseModel::class)->countAllResults(),
            'lessons'   => model(LessonModel::class)->countAllResults(),
            'questions' => model(QuestionModel::class)->countAllResults(),
            'attempts'  => model(QuizAttemptModel::class)->countAllResults(),
            'passed'    => $db->table('progress')->where('quiz_passed', 1)->countAllResults(),
        ];

        $recent = $db->table('quiz_attempts a')
            ->select('a.*, u.name AS user_name, u.branch_name, l.title AS lesson_title')
            ->join('users u', 'u.id = a.user_id')
            ->join('lessons l', 'l.id = a.lesson_id')
            ->orderBy('a.id', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        return view('admin/dashboard', [
            'title'    => '관리자 대시보드',
            'stats'    => $stats,
            'recent'   => $recent,
            'courses'  => model(CourseModel::class)->listWithCounts(),
            'overview' => array_slice(model(ProgressModel::class)->overview(), 0, 8),
        ]);
    }
}
