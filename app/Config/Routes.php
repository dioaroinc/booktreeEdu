<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─────────────────────────────────────────────
// 공개 페이지 (홍보용 메인 / 로그인)
// ─────────────────────────────────────────────
$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');
$routes->get('find', 'Auth::find');

// 구 CI3 주소 호환
$routes->get('web', 'Auth::login');
$routes->get('web/index', 'Auth::login');
$routes->get('web/logout', 'Auth::logout');
$routes->addRedirect('main', 'academy');
$routes->addRedirect('main/index', 'academy');
$routes->get('academy/lecture/(:num)', 'Academy::course/$1');

// ─────────────────────────────────────────────
// 수강생 영역 (로그인 필요 - Filters 설정의 auth 필터)
// ─────────────────────────────────────────────
$routes->group('academy', static function (RouteCollection $routes): void {
    $routes->get('/', 'Academy::index');
    $routes->get('course/(:num)', 'Academy::course/$1');
    $routes->get('lesson/(:num)', 'Academy::lesson/$1');
    $routes->post('lesson/(:num)/progress', 'Academy::progress/$1');
    $routes->get('quiz/(:num)', 'Academy::quiz/$1');
    $routes->post('quiz/(:num)', 'Academy::submitQuiz/$1');
    $routes->get('quiz/(:num)/result/(:num)', 'Academy::quizResult/$1/$2');
    $routes->get('mypage', 'Academy::mypage');
    $routes->get('howto', 'Academy::howto');
});

// ─────────────────────────────────────────────
// 관리자 영역 (관리자 권한 필요 - admin 필터)
// ─────────────────────────────────────────────
$routes->group('admin', static function (RouteCollection $routes): void {
    $routes->get('/', 'Admin\Dashboard::index');

    // 교육과정
    $routes->get('courses', 'Admin\Courses::index');
    $routes->get('courses/new', 'Admin\Courses::form');
    $routes->get('courses/(:num)/edit', 'Admin\Courses::form/$1');
    $routes->post('courses/save', 'Admin\Courses::save');
    $routes->post('courses/(:num)/delete', 'Admin\Courses::delete/$1');

    // 강의 (영상)
    $routes->get('courses/(:num)/lessons', 'Admin\Lessons::index/$1');
    $routes->get('courses/(:num)/lessons/new', 'Admin\Lessons::form/$1');
    $routes->get('lessons/(:num)/edit', 'Admin\Lessons::form/0/$1');
    $routes->post('lessons/save', 'Admin\Lessons::save');
    $routes->post('lessons/(:num)/delete', 'Admin\Lessons::delete/$1');
    $routes->post('sections/save', 'Admin\Lessons::saveSection');
    $routes->post('sections/(:num)/delete', 'Admin\Lessons::deleteSection/$1');

    // 퀴즈 문항
    $routes->get('lessons/(:num)/questions', 'Admin\Questions::index/$1');
    $routes->post('questions/save', 'Admin\Questions::save');
    $routes->post('questions/(:num)/delete', 'Admin\Questions::delete/$1');

    // 회원 / 수강 현황
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/new', 'Admin\Users::form');
    $routes->get('users/(:num)/edit', 'Admin\Users::form/$1');
    $routes->post('users/save', 'Admin\Users::save');
    $routes->post('users/(:num)/delete', 'Admin\Users::delete/$1');
    $routes->get('users/(:num)/progress', 'Admin\Users::progress/$1');
    $routes->get('progress', 'Admin\Users::overview');
});
