<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<section class="auth">
    <div class="auth__box">
        <img src="<?= base_url('assets/images/new_white_logo.png') ?>" alt="책나무" class="auth__logo">
        <h1>온라인 교육 로그인</h1>
        <p class="auth__sub">책나무 지점SW 계정과 동일한 아이디로 로그인하세요.</p>

        <?= flash_alerts() ?>

        <form method="post" action="<?= base_url('/login') ?>" class="auth__form">
            <?= csrf_field() ?>
            <label>아이디
                <input type="text" name="login_id" value="<?= esc(old('login_id')) ?>" placeholder="책나무 가맹점 아이디" autocomplete="username" autofocus required>
            </label>
            <label>비밀번호
                <input type="password" name="password" placeholder="비밀번호 입력" autocomplete="current-password" required>
            </label>
            <button type="submit" class="btn btn-primary btn-block btn-lg">로그인</button>
        </form>

        <div class="auth__links">
            <a href="<?= base_url('/find') ?>">비밀번호를 잊으셨나요?</a>
            <a href="<?= base_url('/') ?>">메인으로</a>
        </div>

        <div class="auth__info">
            책나무 온라인 교육 이용계정은 책나무 지원소프트웨어 지점 계정과 동일합니다.<br>
            로그인 관련 문의: <strong>책나무 본사 1522-9320</strong>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
