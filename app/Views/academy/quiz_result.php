<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="breadcrumb">
    <a href="<?= base_url('/academy') ?>">강의실</a> ›
    <a href="<?= base_url('/academy/course/' . $lesson['course_id']) ?>"><?= esc($lesson['course_title']) ?></a> ›
    <a href="<?= base_url('/academy/lesson/' . $lesson['id']) ?>"><?= esc($lesson['title']) ?></a> › 시험 결과
</div>

<?php $passed = (int) $attempt['passed'] === 1; ?>
<div class="result-card result-card--<?= $passed ? 'pass' : 'fail' ?>">
    <div class="result-card__icon"><?= $passed ? '🎉' : '😢' ?></div>
    <div class="result-card__body">
        <div class="result-card__label"><?= $passed ? '축하합니다! 시험에 통과했습니다.' : '아쉽게도 합격 기준에 미달했습니다.' ?></div>
        <div class="result-card__score"><?= (int) $attempt['score'] ?><small>점</small></div>
        <div class="result-card__meta">
            <?= (int) $attempt['total'] ?>문항 중 <?= (int) $attempt['correct'] ?>개 정답 · 합격 기준 <?= (int) $lesson['pass_score'] ?>점
            · 응시 <?= (int) ($progress['quiz_attempts'] ?? 1) ?>회
        </div>
    </div>
    <div class="result-card__actions">
        <?php if (! $passed): ?>
            <a href="<?= base_url('/academy/quiz/' . $lesson['id']) ?>" class="btn btn-light btn-lg">다시 풀기</a>
            <a href="<?= base_url('/academy/lesson/' . $lesson['id']) ?>" class="btn btn-ghost-light">강의 다시 보기</a>
        <?php elseif ($next): ?>
            <a href="<?= base_url('/academy/lesson/' . $next['id']) ?>" class="btn btn-light btn-lg">다음 강의 →</a>
            <a href="<?= base_url('/academy/course/' . $lesson['course_id']) ?>" class="btn btn-ghost-light">강의 목록</a>
        <?php else: ?>
            <a href="<?= base_url('/academy/course/' . $lesson['course_id']) ?>" class="btn btn-light btn-lg">강의 목록으로</a>
        <?php endif ?>
    </div>
</div>

<h2 class="sub-title">문항별 결과</h2>

<?php foreach ($questions as $i => $q): ?>
    <?php
        $mine    = (int) ($given[$q['id']] ?? 0);
        $correct = $mine === (int) $q['answer'];
    ?>
    <div class="review-q review-q--<?= $correct ? 'ok' : 'no' ?>">
        <div class="review-q__head">
            <span class="review-q__badge"><?= $correct ? '정답' : '오답' ?></span>
            <span class="quiz-q__num">Q<?= $i + 1 ?></span> <?= nl2br(esc($q['question'])) ?>
        </div>
        <div class="review-q__choices">
            <?php for ($n = 1; $n <= 4; $n++): ?>
                <?php if (trim((string) $q['choice' . $n]) === '') continue; ?>
                <div class="choice choice--static <?= $n === (int) $q['answer'] ? 'choice--answer' : '' ?> <?= $n === $mine && ! $correct ? 'choice--wrong' : '' ?>">
                    <span class="choice__num"><?= $n ?></span>
                    <span class="choice__text"><?= esc($q['choice' . $n]) ?></span>
                    <?php if ($n === (int) $q['answer']): ?><span class="choice__tag">정답</span><?php endif ?>
                    <?php if ($n === $mine && ! $correct): ?><span class="choice__tag choice__tag--wrong">내 답</span><?php endif ?>
                </div>
            <?php endfor ?>
        </div>
        <?php if ($q['explanation']): ?>
            <div class="review-q__exp">💡 <?= esc($q['explanation']) ?></div>
        <?php endif ?>
    </div>
<?php endforeach ?>
<?= $this->endSection() ?>
