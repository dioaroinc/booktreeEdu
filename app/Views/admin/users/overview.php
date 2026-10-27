<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<p class="muted">전체 공개 강의 <?= $total ?>개 기준 수강생별 이수 현황입니다. 이름을 누르면 강의별 상세 기록을 볼 수 있습니다.</p>

<div class="card">
    <table class="table">
        <thead>
        <tr>
            <th>이름</th><th>지점</th><th>아이디</th><th style="width:110px">시청 완료</th><th style="width:110px">통과</th><th style="width:200px">통과율</th><th>최근 활동</th><th>최근 로그인</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <?php $rate = $total > 0 ? (int) round($r['passed_count'] / $total * 100) : 0; ?>
            <tr>
                <td><a href="<?= base_url('/admin/users/' . $r['id'] . '/progress') ?>"><strong><?= esc($r['name']) ?></strong></a></td>
                <td><?= esc($r['branch_name']) ?></td>
                <td><?= esc($r['login_id']) ?></td>
                <td><?= (int) $r['watched_count'] ?> / <?= $total ?></td>
                <td><?= (int) $r['passed_count'] ?> / <?= $total ?></td>
                <td>
                    <div class="progress-bar progress-bar--inline"><div class="progress-bar__fill" style="width: <?= $rate ?>%"></div></div>
                    <small><?= $rate ?>%</small>
                </td>
                <td><?= $r['last_activity'] ? date('Y-m-d H:i', strtotime($r['last_activity'])) : '-' ?></td>
                <td><?= $r['last_login_at'] ? date('Y-m-d H:i', strtotime($r['last_login_at'])) : '-' ?></td>
            </tr>
        <?php endforeach ?>
        <?php if ($rows === []): ?><tr><td colspan="8" class="muted">수강생이 없습니다.</td></tr><?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
