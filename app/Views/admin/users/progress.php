<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="breadcrumb"><a href="<?= base_url('/admin/users') ?>">회원 관리</a> › <?= esc($user['name']) ?></div>

<div class="page-head">
    <div>
        <h2 class="page-title"><?= esc($user['name']) ?> <small class="muted">(<?= esc($user['login_id']) ?>)</small></h2>
        <p class="page-sub"><?= esc($user['branch_name']) ?> · <?= esc($user['phone']) ?> · 최근 로그인 <?= $user['last_login_at'] ? date('Y-m-d H:i', strtotime($user['last_login_at'])) : '-' ?></p>
    </div>
    <div class="score-badge">
        <span>전체 통과율</span>
        <strong><?= $summary['rate'] ?>%</strong>
        <small><?= $summary['passed'] ?> / <?= $summary['total'] ?> 강의 통과</small>
    </div>
</div>

<?php foreach ($courses as $course): ?>
    <div class="card">
        <div class="card__head"><h2><?= esc($course['icon']) ?> <?= esc($course['title']) ?></h2></div>
        <table class="table table--compact">
            <thead><tr><th style="width:60px">강의</th><th>제목</th><th>영상</th><th>재생 위치</th><th>응시</th><th>최고점</th><th>상태</th><th>통과일</th></tr></thead>
            <tbody>
            <?php foreach ($course['lessons'] as $i => $lesson): ?>
                <?php $p = $lesson['progress']; [$state, $label] = lesson_status_label($p); ?>
                <tr>
                    <td>제<?= $i + 1 ?>강</td>
                    <td><?= esc($lesson['title']) ?></td>
                    <td><?= $p ? ((int) $p['video_status'] === VIDEO_DONE ? '시청완료' : ((int) $p['video_status'] === VIDEO_STARTED ? '시청중' : '-')) : '-' ?></td>
                    <td><?= $p ? gmdate('i:s', (int) $p['video_seconds']) : '-' ?></td>
                    <td><?= $p ? (int) $p['quiz_attempts'] . '회' : '-' ?></td>
                    <td><?= $p && (int) $p['quiz_attempts'] > 0 ? (int) $p['quiz_best'] . '점' : '-' ?></td>
                    <td><span class="status-pill status-<?= $state ?>"><?= $label ?></span></td>
                    <td><?= $p && $p['quiz_passed_at'] ? date('Y-m-d H:i', strtotime($p['quiz_passed_at'])) : '-' ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php endforeach ?>

<?php if ($attempts !== []): ?>
    <div class="card">
        <div class="card__head"><h2>시험 응시 기록</h2></div>
        <table class="table table--compact">
            <thead><tr><th>일시</th><th>강의 ID</th><th>점수</th><th>정답</th><th>결과</th></tr></thead>
            <tbody>
            <?php foreach ($attempts as $a): ?>
                <tr>
                    <td><?= date('Y-m-d H:i', strtotime($a['created_at'])) ?></td>
                    <td>#<?= (int) $a['lesson_id'] ?></td>
                    <td><?= (int) $a['score'] ?>점</td>
                    <td><?= (int) $a['correct'] ?> / <?= (int) $a['total'] ?></td>
                    <td><span class="status-pill status-<?= $a['passed'] ? 'passed' : 'none' ?>"><?= $a['passed'] ? '합격' : '불합격' ?></span></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php endif ?>
<?= $this->endSection() ?>
