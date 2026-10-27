<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="책나무 가맹 원장님을 위한 온라인 동영상 교육 시스템. 신규원장교육, 지점SW 사용교육, 보수교육을 언제 어디서나 수강하세요.">
    <meta name="author" content="책나무 주식회사">
    <meta property="og:title" content="책나무 온라인 교육">
    <meta property="og:description" content="책나무 가맹 원장님을 위한 온라인 동영상 교육 시스템">
    <meta property="og:image" content="<?= base_url('assets/images/new_meta_image.png') ?>">
    <title><?= isset($title) ? esc($title) . ' - ' : '' ?>책나무 온라인 교육</title>

    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/favicon-16x16.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/apple-icon-180x180.png') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/app.css') ?>">
    <?= $this->renderSection('head') ?>
</head>
<body class="<?= $bodyClass ?? '' ?>">

<header class="site-header">
    <div class="container site-header__inner">
        <a href="<?= base_url('/') ?>" class="brand">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="책나무">
            <span>온라인 교육</span>
        </a>
        <nav class="site-nav">
            <a href="<?= base_url('/#courses') ?>">교육과정</a>
            <a href="<?= base_url('/#how') ?>">이용방법</a>
            <a href="<?= base_url('/#demo') ?>">데모 강의</a>
            <a href="https://www.booktreei.com" target="_blank" rel="noopener">책나무 홈페이지</a>
            <?php if (is_logged_in()): ?>
                <a href="<?= base_url(is_admin() ? '/admin' : '/academy') ?>" class="btn btn-primary btn-sm">
                    <?= is_admin() ? '관리자' : '내 강의실' ?>
                </a>
            <?php else: ?>
                <a href="<?= base_url('/login') ?>" class="btn btn-primary btn-sm">로그인</a>
            <?php endif ?>
        </nav>
        <button class="nav-toggle" type="button" aria-label="메뉴" onclick="document.body.classList.toggle('nav-open')">☰</button>
    </div>
</header>

<?php $flash = flash_alerts(); ?>
<?php if ($flash !== '' && ! str_contains(uri_string(), 'login')): ?>
    <div class="container" style="padding-top:16px"><?= $flash ?></div>
<?php endif ?>

<?= $this->renderSection('content') ?>

<footer class="site-footer">
    <div class="container">
        <div class="site-footer__grid">
            <div>
                <img src="<?= base_url('assets/images/new_white_logo.png') ?>" alt="책나무" class="site-footer__logo">
                <p>책나무 주식회사 · 인재코칭 SINCE 2009<br>
                   온라인 교육 문의: 본사 1522-9320</p>
            </div>
            <div class="site-footer__links">
                <a href="https://www.booktreei.com" target="_blank" rel="noopener">책나무 홈페이지</a>
                <a href="https://www.booktreei.com/user/index" target="_blank" rel="noopener">지점SW 바로가기</a>
                <a href="<?= base_url('/login') ?>">교육 로그인</a>
            </div>
        </div>
        <div class="site-footer__copy">Copyright © 책나무 주식회사 All rights reserved.</div>
    </div>
</footer>

<script src="<?= base_url('assets/js/app.js') ?>?v=<?= filemtime(FCPATH . 'assets/js/app.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
