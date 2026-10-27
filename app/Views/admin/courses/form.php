<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card card--narrow">
    <form method="post" action="<?= base_url('/admin/courses/save') ?>" class="form">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $course['id'] ?>">

        <label>과정명 <span class="req">*</span>
            <input type="text" name="title" value="<?= esc(old('title', $course['title'])) ?>" required maxlength="150" placeholder="예) 신규원장교육">
        </label>
        <label>부제
            <input type="text" name="subtitle" value="<?= esc(old('subtitle', $course['subtitle'])) ?>" maxlength="255" placeholder="예) 책나무 가맹 원장님을 위한 필수 기본 과정">
        </label>
        <label>소개
            <textarea name="description" rows="5" placeholder="메인 페이지와 강의실에 표시되는 과정 소개"><?= esc(old('description', $course['description'])) ?></textarea>
        </label>

        <?php
            $icons  = ['🌱', '📘', '📚', '💻', '🖥️', '📝', '🎓', '🏫', '🧑‍🏫', '👩‍🏫', '📖', '✏️', '🔍', '💡', '⭐', '🏆', '🎯', '📊', '📈', '🗂️', '🛒', '💳', '🔔', '🧩', '🎬', '🗣️', '🤝', '🌳', '🍀', '🚀'];
            $colors = ['#136a43', '#0f88ca', '#b5651d', '#7b3fa0', '#c2185b', '#d84315', '#2e7d32', '#00838f', '#f9a825', '#546e7a', '#3949ab', '#6d4c41'];
            $curIcon  = old('icon', $course['icon']) ?: '📘';
            $curColor = old('color', $course['color']) ?: '#136a43';
        ?>
        <label>아이콘
            <input type="hidden" name="icon" id="iconInput" value="<?= esc($curIcon, 'attr') ?>">
        </label>
        <div class="palette palette--icons" id="iconPalette">
            <?php foreach ($icons as $ic): ?>
                <button type="button" class="palette__item <?= $ic === $curIcon ? 'is-selected' : '' ?>" data-value="<?= esc($ic, 'attr') ?>" title="<?= esc($ic, 'attr') ?>"><?= $ic ?></button>
            <?php endforeach ?>
            <?php if (! in_array($curIcon, $icons, true)): ?>
                <button type="button" class="palette__item is-selected" data-value="<?= esc($curIcon, 'attr') ?>"><?= esc($curIcon) ?></button>
            <?php endif ?>
        </div>

        <label style="margin-top:18px">대표 색상
            <input type="hidden" name="color" id="colorInput" value="<?= esc($curColor, 'attr') ?>">
        </label>
        <div class="palette palette--colors" id="colorPalette">
            <?php foreach ($colors as $c): ?>
                <button type="button" class="palette__swatch <?= strtolower($c) === strtolower($curColor) ? 'is-selected' : '' ?>" data-value="<?= $c ?>" style="background: <?= $c ?>" title="<?= $c ?>"></button>
            <?php endforeach ?>
            <label class="palette__custom" title="직접 선택">
                <input type="color" id="colorCustom" value="<?= esc($curColor, 'attr') ?>">
                <span>직접 선택</span>
            </label>
        </div>

        <div class="palette-preview" id="palettePreview" style="--course-color: <?= esc($curColor, 'attr') ?>">
            <span class="palette-preview__icon" id="previewIcon"><?= esc($curIcon) ?></span>
            <span class="palette-preview__text">미리보기 · <strong id="previewTitle"><?= esc(old('title', $course['title']) ?: '과정명') ?></strong></span>
        </div>

        <div class="form-row">
            <label>정렬 순서
                <input type="number" name="sort_order" value="<?= (int) old('sort_order', $course['sort_order']) ?>">
            </label>
        </div>

        <label class="check">
            <input type="checkbox" name="is_active" value="1" <?= old('is_active', $course['is_active']) ? 'checked' : '' ?>> 공개 (수강생에게 표시)
        </label>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">저장</button>
            <a href="<?= base_url('/admin/courses') ?>" class="btn btn-soft btn-lg">취소</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    var iconInput  = document.getElementById('iconInput');
    var colorInput = document.getElementById('colorInput');
    var custom     = document.getElementById('colorCustom');
    var preview    = document.getElementById('palettePreview');
    var titleInput = document.querySelector('input[name=title]');

    function select(container, btn) {
        container.querySelectorAll('.is-selected').forEach(function (el) { el.classList.remove('is-selected'); });
        if (btn) btn.classList.add('is-selected');
    }

    document.getElementById('iconPalette').addEventListener('click', function (e) {
        var btn = e.target.closest('.palette__item');
        if (!btn) return;
        iconInput.value = btn.dataset.value;
        document.getElementById('previewIcon').textContent = btn.dataset.value;
        select(this, btn);
    });

    var colorPalette = document.getElementById('colorPalette');
    colorPalette.addEventListener('click', function (e) {
        var btn = e.target.closest('.palette__swatch');
        if (!btn) return;
        colorInput.value = btn.dataset.value;
        custom.value     = btn.dataset.value;
        preview.style.setProperty('--course-color', btn.dataset.value);
        select(colorPalette, btn);
    });
    custom.addEventListener('input', function () {
        colorInput.value = custom.value;
        preview.style.setProperty('--course-color', custom.value);
        select(colorPalette, null);
    });
    titleInput.addEventListener('input', function () {
        document.getElementById('previewTitle').textContent = titleInput.value || '과정명';
    });
})();
</script>
<?= $this->endSection() ?>
