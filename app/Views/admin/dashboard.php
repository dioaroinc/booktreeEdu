<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="stat-grid">
    <div class="stat"><span>수강생</span><strong><?= $stats['students'] ?></strong><a href="<?= base_url('/admin/users') ?>">회원 관리 →</a></div>
    <div class="stat"><span>교육과정</span><strong><?= $stats['courses'] ?></strong><a href="<?= base_url('/admin/courses') ?>">과정 관리 →</a></div>
    <div class="stat"><span>강의(영상)</span><strong><?= $stats['lessons'] ?></strong><a href="<?= base_url('/admin/courses') ?>">강의 관리 →</a></div>
    <div class="stat"><span>시험 문항</span><strong><?= $stats['questions'] ?></strong></div>
    <div class="stat"><span>시험 응시</span><strong><?= $stats['attempts'] ?></strong><a href="<?= base_url('/admin/progress') ?>">수강 현황 →</a></div>
    <div class="stat"><span>통과 처리</span><strong><?= $stats['passed'] ?></strong></div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card__head"><h2>교육과정</h2><a href="<?= base_url('/admin/courses/new') ?>" class="btn btn-primary btn-sm">+ 과정 등록</a></div>
        <table class="table table--compact">
            <thead><tr><th>과정</th><th>강의</th><th>총 시간</th><th>상태</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($courses as $c): ?>
                <tr>
                    <td><?= esc($c['icon']) ?> <?= esc($c['title']) ?></td>
                    <td><?= (int) $c['lesson_count'] ?>개</td>
                    <td><?= format_minutes((int) $c['total_minutes']) ?></td>
                    <td><?= $c['is_active'] ? '<span class="status-pill status-passed">공개</span>' : '<span class="status-pill status-none">비공개</span>' ?></td>
                    <td><a href="<?= base_url('/admin/courses/' . $c['id'] . '/lessons') ?>">강의 관리</a></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="card__head"><h2>최근 시험 응시</h2><a href="<?= base_url('/admin/progress') ?>" class="btn btn-soft btn-sm">전체 현황</a></div>
        <table class="table table--compact">
            <thead><tr><th>일시</th><th>회원</th><th>강의</th><th>점수</th><th>결과</th></tr></thead>
            <tbody>
            <?php foreach ($recent as $r): ?>
                <tr>
                    <td><?= date('m-d H:i', strtotime($r['created_at'])) ?></td>
                    <td><a href="<?= base_url('/admin/users/' . $r['user_id'] . '/progress') ?>"><?= esc($r['user_name']) ?></a></td>
                    <td class="ellipsis"><?= esc($r['lesson_title']) ?></td>
                    <td><?= (int) $r['score'] ?>점</td>
                    <td><span class="status-pill status-<?= $r['passed'] ? 'passed' : 'none' ?>"><?= $r['passed'] ? '합격' : '불합격' ?></span></td>
                </tr>
            <?php endforeach ?>
            <?php if ($recent === []): ?><tr><td colspan="5" class="muted">아직 응시 기록이 없습니다.</td></tr><?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card__head"><h2>수강생 현황 (일부)</h2><a href="<?= base_url('/admin/progress') ?>" class="btn btn-soft btn-sm">전체 보기</a></div>
    <table class="table table--compact">
        <thead><tr><th>이름</th><th>지점</th><th>아이디</th><th>시청 완료</th><th>통과</th><th>최근 활동</th></tr></thead>
        <tbody>
        <?php foreach ($overview as $u): ?>
            <tr>
                <td><a href="<?= base_url('/admin/users/' . $u['id'] . '/progress') ?>"><?= esc($u['name']) ?></a></td>
                <td><?= esc($u['branch_name']) ?></td>
                <td><?= esc($u['login_id']) ?></td>
                <td><?= (int) $u['watched_count'] ?></td>
                <td><?= (int) $u['passed_count'] ?></td>
                <td><?= $u['last_activity'] ? date('Y-m-d H:i', strtotime($u['last_activity'])) : '-' ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
