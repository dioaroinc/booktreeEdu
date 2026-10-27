<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= isset($title) ? esc($title) . ' - ' : '' ?>관리자 - 책나무 온라인 교육</title>

    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/favicon-32x32.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/app.css') ?>">
    <?= $this->renderSection('head') ?>
</head>
<body class="admin-body">
<?php
    $uri    = uri_string();
    $active = static fn (string $prefix): string => str_starts_with($uri, $prefix) ? 'active' : '';
?>
<aside class="admin-side">
    <a href="<?= base_url('/admin') ?>" class="admin-side__brand">
        <img src="<?= base_url('assets/images/new_white_logo.png') ?>" alt="책나무">
        <span>교육 관리자</span>
    </a>
    <nav class="admin-side__nav">
        <a href="<?= base_url('/admin') ?>" class="<?= $uri === 'admin' ? 'active' : '' ?>">📊 대시보드</a>
        <a href="<?= base_url('/admin/courses') ?>" class="<?= $active('admin/courses') ?: $active('admin/lessons') ?: $active('admin/sections') ?>">📚 교육과정 · 강의</a>
        <a href="<?= base_url('/admin/users') ?>" class="<?= $active('admin/users') ?>">🧑‍🏫 회원 관리</a>
        <a href="<?= base_url('/admin/progress') ?>" class="<?= $active('admin/progress') ?>">📈 수강 현황</a>
        <div class="admin-side__sep"></div>
        <a href="<?= base_url('/academy') ?>">🎓 수강생 화면 보기</a>
        <a href="<?= base_url('/') ?>">🏠 홍보 메인 페이지</a>
        <a href="<?= base_url('/logout') ?>">↩ 로그아웃</a>
    </nav>
    <div class="admin-side__user"><?= esc(current_user_name()) ?> 님</div>
</aside>

<div class="admin-main">
    <header class="admin-top">
        <button class="nav-toggle" type="button" aria-label="메뉴" onclick="document.body.classList.toggle('side-open')">☰</button>
        <h1><?= esc($title ?? '관리자') ?></h1>
    </header>
    <div class="admin-content">
        <?= flash_alerts() ?>
        <?= $this->renderSection('content') ?>
    </div>
</div>

<script src="<?= base_url('assets/js/app.js') ?>?v=<?= filemtime(FCPATH . 'assets/js/app.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
