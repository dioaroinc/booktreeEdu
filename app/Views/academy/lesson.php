<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="breadcrumb">
    <a href="<?= base_url('/academy') ?>">강의실</a> ›
    <a href="<?= base_url('/academy/course/' . $lesson['course_id']) ?>"><?= esc($lesson['course_title']) ?></a> ›
    <?= esc($lesson['title']) ?>
</div>

<div class="player-wrap">
    <div class="player-head">
        <div>
            <?php if ($lesson['section_title']): ?><div class="player-section"><?= esc($lesson['section_title']) ?></div><?php endif ?>
            <h1 class="player-title"><?= esc($lesson['title']) ?></h1>
            <div class="player-meta">총 <?= format_minutes((int) $lesson['duration_min']) ?> · 시험 문항 <?= (int) $lesson['question_count'] ?>개 · 합격 <?= (int) $lesson['pass_score'] ?>점</div>
        </div>
        <div class="player-status" id="videoStatus" data-status="<?= (int) $progress['video_status'] ?>">
            <?= (int) $progress['video_status'] === VIDEO_DONE ? '✅ 시청 완료' : ((int) $progress['video_status'] === VIDEO_STARTED ? '▶ 시청중' : '미시청') ?>
        </div>
    </div>

    <div class="video-frame"
         id="player"
         data-type="<?= esc($lesson['video_type'], 'attr') ?>"
         data-src="<?= esc($src, 'attr') ?>"
         data-youtube-id="<?= esc(youtube_id($lesson['video_url']), 'attr') ?>"
         data-progress-url="<?= base_url('/academy/lesson/' . $lesson['id'] . '/progress') ?>"
         data-resume="<?= (int) $progress['video_seconds'] ?>"
         data-done="<?= (int) $progress['video_status'] === VIDEO_DONE ? 1 : 0 ?>">
        <?php if ($lesson['video_type'] === VIDEO_TYPE_YOUTUBE): ?>
            <div id="ytPlayer"></div>
        <?php elseif ($src !== ''): ?>
            <video id="htmlPlayer" controls controlsList="nodownload" playsinline preload="metadata" src="<?= esc($src, 'attr') ?>"></video>
        <?php else: ?>
            <div class="video-empty">영상이 아직 등록되지 않았습니다.</div>
        <?php endif ?>
    </div>

    <div class="player-tools">
        <div class="player-progress">
            <div class="progress-bar"><div class="progress-bar__fill" id="watchBar" style="width: 0%"></div></div>
            <span id="watchText">재생 위치가 자동 저장됩니다.</span>
        </div>
        <div class="player-actions">
            <?php if ((int) $lesson['question_count'] > 0): ?>
                <a href="<?= base_url('/academy/quiz/' . $lesson['id']) ?>" class="btn btn-dark" id="quizBtn"
                   <?= (int) $lesson['require_video'] === 1 && (int) $progress['video_status'] !== VIDEO_DONE ? 'data-locked="1"' : '' ?>>
                    문제풀기
                </a>
            <?php endif ?>
            <?php if ($next): ?>
                <a href="<?= base_url('/academy/lesson/' . $next['id']) ?>" class="btn btn-primary">다음 강의 →</a>
            <?php endif ?>
            <a href="<?= base_url('/academy/course/' . $lesson['course_id']) ?>" class="btn btn-soft">강의 목록</a>
        </div>
    </div>

    <?php if ($lesson['description']): ?>
        <div class="player-desc">
            <h3>강의 소개</h3>
            <p><?= nl2br(esc($lesson['description'])) ?></p>
        </div>
    <?php endif ?>

    <?php if ((int) $lesson['require_video'] === 1): ?>
        <div class="notice">영상을 <strong>끝까지 시청</strong>하면 자동으로 시청 완료 처리되고 시험에 응시할 수 있습니다.</div>
    <?php endif ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/player.js') ?>?v=<?= filemtime(FCPATH . 'assets/js/player.js') ?>"></script>
<?= $this->endSection() ?>
