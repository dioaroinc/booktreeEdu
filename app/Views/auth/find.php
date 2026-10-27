<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<section class="auth">
    <div class="auth__box">
        <img src="<?= base_url('assets/images/new_white_logo.png') ?>" alt="책나무" class="auth__logo">
        <h1>비밀번호 찾기</h1>
        <p class="auth__sub">온라인 교육 계정은 책나무 지점SW 계정과 동일하게 관리됩니다.</p>

        <div class="auth__info auth__info--big">
            비밀번호 재설정은 보안을 위해 본사에서 직접 처리합니다.<br><br>
            <strong>책나무 본사 1522-9320</strong><br>
            (평일 09:00 ~ 18:00)<br><br>
            가맹점명과 원장님 성함을 알려주시면 확인 후 안내해 드립니다.
        </div>

        <div class="auth__links">
            <a href="<?= base_url('/login') ?>">로그인으로 돌아가기</a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
