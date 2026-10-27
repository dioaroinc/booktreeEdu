<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\UserModel;

/**
 * 홍보용 메인 랜딩 페이지
 */
class Home extends BaseController
{
    public function index(): string
    {
        $courses = model(CourseModel::class)->listWithCounts(true);

        $stats = [
            'courses'  => count($courses),
            'lessons'  => model(LessonModel::class)->countActive(),
            'students' => model(UserModel::class)->where('type', USER_TYPE_STUDENT)->where('status', 1)->countAllResults(),
        ];

        return view('home/index', [
            'courses' => $courses,
            'stats'   => $stats,
        ]);
    }
}
