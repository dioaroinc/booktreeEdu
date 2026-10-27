<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="toolbar">
    <p class="muted">교육과정을 등록하고, 각 과정 안에 단원과 강의(영상 + 시험)를 추가합니다.</p>
    <a href="<?= base_url('/admin/courses/new') ?>" class="btn btn-primary">+ 교육과정 등록</a>
</div>

<div class="card">
    <table class="table">
        <thead>
        <tr>
            <th style="width:60px">순서</th>
            <th>과정명</th>
            <th>부제</th>
            <th style="width:80px">강의</th>
            <th style="width:100px">총 시간</th>
            <th style="width:80px">상태</th>
            <th style="width:260px">관리</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($courses as $c): ?>
            <tr>
                <td><?= (int) $c['sort_order'] ?></td>
                <td><span class="color-dot" style="background: <?= esc($c['color'], 'attr') ?>"></span> <?= esc($c['icon']) ?> <strong><?= esc($c['title']) ?></strong></td>
                <td class="muted"><?= esc($c['subtitle']) ?></td>
                <td><?= (int) $c['lesson_count'] ?>개</td>
                <td><?= format_minutes((int) $c['total_minutes']) ?></td>
                <td><?= $c['is_active'] ? '<span class="status-pill status-passed">공개</span>' : '<span class="status-pill status-none">비공개</span>' ?></td>
                <td class="actions">
                    <a href="<?= base_url('/admin/courses/' . $c['id'] . '/lessons') ?>" class="btn btn-primary btn-sm">강의 관리</a>
                    <a href="<?= base_url('/admin/courses/' . $c['id'] . '/edit') ?>" class="btn btn-soft btn-sm">수정</a>
                    <form method="post" action="<?= base_url('/admin/courses/' . $c['id'] . '/delete') ?>" class="inline" data-confirm="'<?= esc($c['title'], 'js') ?>' 과정을 삭제할까요?">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger-soft btn-sm">삭제</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
        <?php if ($courses === []): ?><tr><td colspan="7" class="muted">등록된 교육과정이 없습니다.</td></tr><?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
