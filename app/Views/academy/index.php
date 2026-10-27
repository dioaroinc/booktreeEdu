<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-head">
    <div>
        <h1 class="page-title">나의 강의실</h1>
        <p class="page-sub"><strong><?= esc(current_user_name()) ?></strong> 님, 안녕하세요! 수강할 교육과정을 선택하세요.</p>
    </div>
    <div class="score-badge">
        <span>전체 통과율</span>
        <strong><?= $summary['rate'] ?>%</strong>
        <small><?= $summary['passed'] ?> / <?= $summary['total'] ?> 강의 통과</small>
    </div>
</div>

<div class="course-list">
    <?php foreach ($courses as $course): ?>
        <a href="<?= base_url('/academy/course/' . $course['id']) ?>" class="course-row" style="--course-color: <?= esc($course['color'], 'attr') ?>">
            <div class="course-row__icon"><?= esc($course['icon']) ?></div>
            <div class="course-row__body">
                <h2><?= esc($course['title']) ?></h2>
                <p><?= esc($course['subtitle']) ?></p>
                <div class="course-row__meta">
                    <span>🎬 강의 <?= (int) $course['lesson_count'] ?>개</span>
                    <span>⏱ <?= format_minutes((int) $course['total_minutes']) ?></span>
                    <span>✅ 통과 <?= $course['passed'] ?>개</span>
                </div>
                <div class="progress-bar"><div class="progress-bar__fill" style="width: <?= $course['rate'] ?>%"></div></div>
            </div>
            <div class="course-row__rate">
                <strong><?= $course['rate'] ?>%</strong>
                <span><?= $course['rate'] >= 100 ? '이수 완료' : ($course['passed'] > 0 ? '수강중' : '시작하기') ?> →</span>
            </div>
        </a>
    <?php endforeach ?>

    <?php if ($courses === []): ?>
        <div class="empty">아직 개설된 교육과정이 없습니다.</div>
    <?php endif ?>
</div>
<?= $this->endSection() ?>
