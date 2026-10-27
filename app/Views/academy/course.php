<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="breadcrumb"><a href="<?= base_url('/academy') ?>">강의실</a> › <?= esc($course['title']) ?></div>

<div class="page-head" style="--course-color: <?= esc($course['color'], 'attr') ?>">
    <div>
        <h1 class="page-title"><?= esc($course['icon']) ?> <?= esc($course['title']) ?></h1>
        <p class="page-sub"><?= esc($course['subtitle']) ?></p>
    </div>
    <div class="score-badge">
        <span>이 과정 통과율</span>
        <strong><?= $rate ?>%</strong>
        <small><?= $passed ?> / <?= $total ?> 강의 통과</small>
    </div>
</div>

<div class="lecture-info">
    <strong><?= esc(current_user_name()) ?></strong>님은 현재 전체 <?= $summary['total'] ?>개의 교육 중
    <strong><?= $summary['passed'] ?></strong>개 교육을 통과하셨습니다. (전체 통과율 <?= $summary['rate'] ?>%)
</div>

<?php foreach ($grouped as $group): ?>
    <?php if ($group['title'] !== ''): ?>
        <div class="section-bar"><?= esc($group['title']) ?></div>
    <?php endif ?>

    <?php foreach ($group['lessons'] as $lesson): ?>
        <?php [$state, $label] = lesson_status_label($lesson['progress']); ?>
        <div class="lesson-row lesson-row--<?= $state ?>">
            <div class="lesson-row__num">제<?= $lesson['index'] ?>강</div>
            <div class="lesson-row__body">
                <div class="lesson-row__title"><?= esc($lesson['title']) ?></div>
                <div class="lesson-row__meta">
                    총 <?= format_minutes((int) $lesson['duration_min']) ?> · 시험 문항 <?= (int) $lesson['question_count'] ?>개 · 합격 <?= (int) $lesson['pass_score'] ?>점
                    <?php if ($lesson['progress'] && (int) $lesson['progress']['quiz_attempts'] > 0): ?>
                        · 최고 점수 <?= (int) $lesson['progress']['quiz_best'] ?>점 (<?= (int) $lesson['progress']['quiz_attempts'] ?>회 응시)
                    <?php endif ?>
                </div>
            </div>
            <div class="lesson-row__status status-<?= $state ?>"><?= $label ?></div>
            <div class="lesson-row__actions">
                <?php if ($state === 'passed'): ?>
                    <a href="<?= base_url('/academy/lesson/' . $lesson['id']) ?>" class="btn btn-soft">강의 다시 보기</a>
                    <a href="<?= base_url('/academy/quiz/' . $lesson['id']) ?>" class="btn btn-soft-dark">문제 다시 풀기</a>
                <?php elseif ($state === 'video_done'): ?>
                    <a href="<?= base_url('/academy/lesson/' . $lesson['id']) ?>" class="btn btn-soft">강의 다시 보기</a>
                    <a href="<?= base_url('/academy/quiz/' . $lesson['id']) ?>" class="btn btn-dark">문제풀기</a>
                <?php elseif ($state === 'watching'): ?>
                    <a href="<?= base_url('/academy/lesson/' . $lesson['id']) ?>" class="btn btn-danger">강의 계속 보기</a>
                    <a href="<?= base_url('/academy/quiz/' . $lesson['id']) ?>" class="btn btn-dark">문제풀기</a>
                <?php else: ?>
                    <a href="<?= base_url('/academy/lesson/' . $lesson['id']) ?>" class="btn btn-primary">강의보기</a>
                    <a href="<?= base_url('/academy/quiz/' . $lesson['id']) ?>" class="btn btn-dark">문제풀기</a>
                <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>
<?php endforeach ?>

<?php if ($total === 0): ?>
    <div class="empty">이 과정에는 아직 등록된 강의가 없습니다.</div>
<?php endif ?>
<?= $this->endSection() ?>
