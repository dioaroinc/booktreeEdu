<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= isset($title) ? esc($title) . ' - ' : '' ?>책나무 온라인 교육</title>

    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/favicon-32x32.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/app.css') ?>">
    <?= $this->renderSection('head') ?>
</head>
<body class="app-body">

<header class="app-header">
    <div class="container app-header__inner">
        <a href="<?= base_url('/academy') ?>" class="brand">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="책나무">
            <span>온라인 교육</span>
        </a>
        <nav class="app-nav">
            <a href="<?= base_url('/academy') ?>" class="<?= uri_string() === 'academy' ? 'active' : '' ?>">강의실</a>
            <a href="<?= base_url('/academy/mypage') ?>" class="<?= uri_string() === 'academy/mypage' ? 'active' : '' ?>">나의 교육 현황</a>
            <a href="<?= base_url('/academy/howto') ?>" class="<?= uri_string() === 'academy/howto' ? 'active' : '' ?>">사이트 이용법</a>
            <a href="https://www.booktreei.com/user/index" target="_blank" rel="noopener">지점SW</a>
            <?php if (is_admin()): ?>
                <a href="<?= base_url('/admin') ?>" class="nav-admin">관리자</a>
            <?php endif ?>
        </nav>
        <div class="app-user">
            <span class="app-user__name"><?= esc(current_user_name()) ?></span> 님
            <a href="<?= base_url('/logout') ?>" class="app-user__logout">로그아웃</a>
        </div>
        <button class="nav-toggle" type="button" aria-label="메뉴" onclick="document.body.classList.toggle('nav-open')">☰</button>
    </div>
</header>

<main class="app-main container">
    <?= flash_alerts() ?>
    <?= $this->renderSection('content') ?>
</main>

<footer class="app-footer">
    <div class="container">책나무 온라인 교육 · 문의 1522-9320 · Copyright © 책나무 주식회사</div>
</footer>

<script src="<?= base_url('assets/js/app.js') ?>?v=<?= filemtime(FCPATH . 'assets/js/app.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
