<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="toolbar">
    <form method="get" action="<?= base_url('/admin/users') ?>" class="form form--inline">
        <input type="text" name="q" value="<?= esc($keyword) ?>" placeholder="이름 / 아이디 / 지점 검색">
        <button type="submit" class="btn btn-soft">검색</button>
    </form>
    <a href="<?= base_url('/admin/users/new') ?>" class="btn btn-primary">+ 회원 등록</a>
</div>

<p class="muted">지점SW 계정으로 처음 로그인한 회원은 자동으로 목록에 추가됩니다(.env 의 booktree.remoteAuth 활성화 시). 여기서 직접 계정을 만들 수도 있습니다.</p>

<div class="card">
    <table class="table">
        <thead>
        <tr>
            <th>이름</th><th>아이디</th><th>지점</th><th>연락처</th><th>구분</th><th>출처</th><th>상태</th><th>최근 로그인</th><th style="width:230px">관리</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><strong><?= esc($u['name']) ?></strong></td>
                <td><?= esc($u['login_id']) ?></td>
                <td><?= esc($u['branch_name']) ?></td>
                <td><?= esc($u['phone']) ?></td>
                <td><?= (int) $u['type'] === USER_TYPE_ADMIN ? '<span class="status-pill status-admin">관리자</span>' : '수강생' ?></td>
                <td><?= $u['source'] === 'booktree' ? '지점SW' : '직접 등록' ?></td>
                <td><?= $u['status'] ? '<span class="status-pill status-passed">사용</span>' : '<span class="status-pill status-none">정지</span>' ?></td>
                <td><?= $u['last_login_at'] ? date('Y-m-d H:i', strtotime($u['last_login_at'])) : '-' ?></td>
                <td class="actions">
                    <a href="<?= base_url('/admin/users/' . $u['id'] . '/progress') ?>" class="btn btn-dark btn-sm">수강 현황</a>
                    <a href="<?= base_url('/admin/users/' . $u['id'] . '/edit') ?>" class="btn btn-soft btn-sm">수정</a>
                    <?php if ((int) $u['id'] !== current_user_id()): ?>
                        <form method="post" action="<?= base_url('/admin/users/' . $u['id'] . '/delete') ?>" class="inline" data-confirm="'<?= esc($u['name'], 'js') ?>' 회원과 수강 기록을 삭제할까요?">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger-soft btn-sm">삭제</button>
                        </form>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
        <?php if ($users === []): ?><tr><td colspan="9" class="muted">회원이 없습니다.</td></tr><?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
