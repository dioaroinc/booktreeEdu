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

        <div class="form-row">
            <label>아이콘 (이모지)
                <input type="text" name="icon" value="<?= esc(old('icon', $course['icon'])) ?>" maxlength="20" placeholder="🌱">
            </label>
            <label>대표 색상
                <input type="color" name="color" value="<?= esc(old('color', $course['color'])) ?>">
            </label>
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
