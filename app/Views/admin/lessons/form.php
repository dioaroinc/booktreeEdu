<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="breadcrumb">
    <a href="<?= base_url('/admin/courses') ?>">교육과정</a> ›
    <a href="<?= base_url('/admin/courses/' . $course['id'] . '/lessons') ?>"><?= esc($course['title']) ?></a> › <?= esc($title) ?>
</div>

<?php $type = old('video_type', $lesson['video_type']); ?>
<div class="card card--narrow">
    <form method="post" action="<?= base_url('/admin/lessons/save') ?>" class="form" enctype="multipart/form-data" id="lessonForm">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $lesson['id'] ?>">
        <input type="hidden" name="course_id" value="<?= (int) $course['id'] ?>">

        <label>강의명 <span class="req">*</span>
            <input type="text" name="title" value="<?= esc(old('title', $lesson['title'])) ?>" required maxlength="200" placeholder="예) 접속방법, 메인프로그램 소개, 진단평가">
        </label>

        <div class="form-row">
            <label>단원
                <select name="section_id">
                    <option value="">(단원 없음)</option>
                    <?php foreach ($sections as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= (int) old('section_id', $lesson['section_id']) === (int) $s['id'] ? 'selected' : '' ?>><?= esc($s['title']) ?></option>
                    <?php endforeach ?>
                </select>
            </label>
            <label>정렬 순서
                <input type="number" name="sort_order" value="<?= (int) old('sort_order', $lesson['sort_order']) ?>">
            </label>
        </div>

        <label>강의 소개
            <textarea name="description" rows="4" placeholder="강의 화면 아래에 표시되는 설명"><?= esc(old('description', $lesson['description'])) ?></textarea>
        </label>

        <fieldset class="fieldset">
            <legend>영상</legend>
            <div class="radio-tabs">
                <label><input type="radio" name="video_type" value="file" <?= $type === 'file' ? 'checked' : '' ?>> 📁 영상 파일 업로드 (MP4)</label>
                <label><input type="radio" name="video_type" value="youtube" <?= $type === 'youtube' ? 'checked' : '' ?>> ▶ 유튜브</label>
                <label><input type="radio" name="video_type" value="url" <?= $type === 'url' ? 'checked' : '' ?>> 🔗 외부 영상 URL</label>
            </div>

            <div class="video-opt" data-type="file">
                <label>영상 파일 (mp4, webm, mov)
                    <input type="file" name="video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime,.mp4,.webm,.m4v,.mov">
                </label>
                <?php if ($lesson['video_file']): ?>
                    <p class="muted">현재 파일: <a href="<?= base_url('uploads/videos/' . $lesson['video_file']) ?>" target="_blank"><?= esc($lesson['video_file']) ?></a> — 새 파일을 선택하면 교체됩니다.</p>
                <?php endif ?>
                <p class="hint">서버 업로드 제한: <?= esc($maxUpload) ?> (php.ini 의 upload_max_filesize / post_max_size 로 조정). 용량이 큰 영상은 유튜브(비공개·미등록) 업로드 후 주소 입력을 권장합니다.</p>
            </div>

            <div class="video-opt" data-type="youtube">
                <label>유튜브 주소 또는 영상 ID
                    <input type="text" name="video_url" value="<?= esc(old('video_url', $lesson['video_type'] === 'youtube' ? $lesson['video_url'] : '')) ?>" placeholder="https://www.youtube.com/watch?v=XXXXXXXXXXX" id="ytUrl">
                </label>
                <p class="hint">유튜브에서 「미등록(링크가 있는 사용자만)」으로 올리면 검색에 노출되지 않습니다. 영상이 끝까지 재생되면 자동으로 시청 완료 처리됩니다.</p>
            </div>

            <div class="video-opt" data-type="url">
                <label>영상 파일 주소 (https://…/video.mp4)
                    <input type="text" name="video_url_ext" value="<?= esc(old('video_url', $lesson['video_type'] === 'url' ? $lesson['video_url'] : '')) ?>" placeholder="https://cdn.example.com/lecture1.mp4" id="extUrl">
                </label>
                <p class="hint">다른 서버(CDN, 클라우드 스토리지)에 올린 MP4 주소를 직접 입력합니다.</p>
            </div>
        </fieldset>

        <div class="form-row">
            <label>총 시간(분)
                <input type="number" name="duration_min" value="<?= (int) old('duration_min', $lesson['duration_min']) ?>" min="0">
            </label>
            <label>합격 점수(%)
                <input type="number" name="pass_score" value="<?= (int) old('pass_score', $lesson['pass_score']) ?>" min="0" max="100" required>
            </label>
        </div>

        <label class="check">
            <input type="checkbox" name="require_video" value="1" <?= old('require_video', $lesson['require_video']) ? 'checked' : '' ?>> 영상 시청 완료 후에만 시험 응시 가능
        </label>
        <label class="check">
            <input type="checkbox" name="is_active" value="1" <?= old('is_active', $lesson['is_active']) ? 'checked' : '' ?>> 공개 (수강생에게 표시)
        </label>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg" id="saveBtn">저장</button>
            <a href="<?= base_url('/admin/courses/' . $course['id'] . '/lessons') ?>" class="btn btn-soft btn-lg">취소</a>
            <span class="muted" id="uploadMsg"></span>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    var form   = document.getElementById('lessonForm');
    var radios = form.querySelectorAll('input[name=video_type]');
    var opts   = form.querySelectorAll('.video-opt');

    function sync() {
        var type = form.querySelector('input[name=video_type]:checked').value;
        opts.forEach(function (o) { o.classList.toggle('hidden', o.dataset.type !== type); });
    }
    radios.forEach(function (r) { r.addEventListener('change', sync); });
    sync();

    // 외부 URL 입력값을 video_url 로 합쳐서 전송
    form.addEventListener('submit', function () {
        var type = form.querySelector('input[name=video_type]:checked').value;
        var yt   = document.getElementById('ytUrl');
        var ext  = document.getElementById('extUrl');
        if (type === 'url') { yt.value = ext.value; }
        ext.removeAttribute('name');
        if (type === 'file') {
            var f = form.querySelector('input[name=video_file]');
            if (f.files.length) {
                document.getElementById('saveBtn').disabled = true;
                document.getElementById('uploadMsg').textContent = '영상 업로드 중… 창을 닫지 마세요.';
            }
        }
    });
})();
</script>
<?= $this->endSection() ?>
