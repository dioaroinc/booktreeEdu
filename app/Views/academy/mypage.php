<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-head">
    <div>
        <h1 class="page-title">나의 교육 현황</h1>
        <p class="page-sub"><strong><?= esc(current_user_name()) ?></strong> 님 · <?= esc(session('user_branch')) ?></p>
    </div>
    <div class="score-badge">
        <span>전체 통과율</span>
        <strong><?= $summary['rate'] ?>%</strong>
        <small><?= $summary['passed'] ?> / <?= $summary['total'] ?> 강의 통과</small>
    </div>
</div>

<?php foreach ($courses as $course): ?>
    <div class="section-bar" style="background: <?= esc($course['color'], 'attr') ?>"><?= esc($course['icon']) ?> <?= esc($course['title']) ?></div>
    <div class="table-wrap">
        <table class="table">
            <thead>
            <tr>
                <th style="width:60px">강의</th>
                <th>제목</th>
                <th style="width:110px">영상</th>
                <th style="width:90px">응시</th>
                <th style="width:90px">최고점</th>
                <th style="width:100px">상태</th>
                <th style="width:150px">통과일</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($course['lessons'] as $i => $lesson): ?>
                <?php $p = $lesson['progress']; [$state, $label] = lesson_status_label($p); ?>
                <tr>
                    <td>제<?= $i + 1 ?>강</td>
                    <td><a href="<?= base_url('/academy/lesson/' . $lesson['id']) ?>"><?= esc($lesson['title']) ?></a></td>
                    <td><?= $p ? ((int) $p['video_status'] === VIDEO_DONE ? '시청완료' : ((int) $p['video_status'] === VIDEO_STARTED ? '시청중' : '-')) : '-' ?></td>
                    <td><?= $p ? (int) $p['quiz_attempts'] . '회' : '-' ?></td>
                    <td><?= $p && (int) $p['quiz_attempts'] > 0 ? (int) $p['quiz_best'] . '점' : '-' ?></td>
                    <td><span class="status-pill status-<?= $state ?>"><?= $label ?></span></td>
                    <td><?= $p && $p['quiz_passed_at'] ? date('Y-m-d H:i', strtotime($p['quiz_passed_at'])) : '-' ?></td>
                </tr>
            <?php endforeach ?>
            <?php if ($course['lessons'] === []): ?>
                <tr><td colspan="7" class="muted">등록된 강의가 없습니다.</td></tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
<?php endforeach ?>

<?php if ($attempts !== []): ?>
    <h2 class="sub-title">최근 시험 응시 기록</h2>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>일시</th><th>강의</th><th>점수</th><th>정답</th><th>결과</th></tr></thead>
            <tbody>
            <?php
                $lessonTitles = [];
                foreach ($courses as $c) { foreach ($c['lessons'] as $l) { $lessonTitles[$l['id']] = $l['title']; } }
            ?>
            <?php foreach ($attempts as $a): ?>
                <tr>
                    <td><?= date('Y-m-d H:i', strtotime($a['created_at'])) ?></td>
                    <td><a href="<?= base_url('/academy/quiz/' . $a['lesson_id'] . '/result/' . $a['id']) ?>"><?= esc($lessonTitles[$a['lesson_id']] ?? '(삭제된 강의)') ?></a></td>
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
