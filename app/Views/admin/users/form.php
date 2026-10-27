<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card card--narrow">
    <form method="post" action="<?= base_url('/admin/users/save') ?>" class="form" autocomplete="off">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">

        <div class="form-row">
            <label>아이디 <span class="req">*</span>
                <input type="text" name="login_id" value="<?= esc(old('login_id', $user['login_id'])) ?>" required maxlength="50" autocomplete="off">
            </label>
            <label>비밀번호 <?= $user['id'] ? '' : '<span class="req">*</span>' ?>
                <input type="password" name="password" <?= $user['id'] ? 'placeholder="변경할 때만 입력"' : 'required' ?> minlength="4" autocomplete="new-password">
            </label>
        </div>
        <div class="form-row">
            <label>이름 <span class="req">*</span>
                <input type="text" name="name" value="<?= esc(old('name', $user['name'])) ?>" required maxlength="100">
            </label>
            <label>지점명
                <input type="text" name="branch_name" value="<?= esc(old('branch_name', $user['branch_name'])) ?>" maxlength="100" placeholder="예) 책나무 서울송파잠실점">
            </label>
        </div>
        <div class="form-row">
            <label>연락처
                <input type="text" name="phone" value="<?= esc(old('phone', $user['phone'])) ?>" maxlength="30">
            </label>
            <label>이메일
                <input type="email" name="email" value="<?= esc(old('email', $user['email'])) ?>" maxlength="100">
            </label>
        </div>
        <div class="form-row">
            <label>구분
                <select name="type">
                    <option value="1" <?= (int) old('type', $user['type']) === USER_TYPE_STUDENT ? 'selected' : '' ?>>수강생 (가맹점 원장)</option>
                    <option value="100" <?= (int) old('type', $user['type']) === USER_TYPE_ADMIN ? 'selected' : '' ?>>관리자 (본사)</option>
                </select>
            </label>
            <label class="check" style="align-self:end">
                <input type="checkbox" name="status" value="1" <?= old('status', $user['status']) ? 'checked' : '' ?>> 사용 (로그인 허용)
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">저장</button>
            <a href="<?= base_url('/admin/users') ?>" class="btn btn-soft btn-lg">취소</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
