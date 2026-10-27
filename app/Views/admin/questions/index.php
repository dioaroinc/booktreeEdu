<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="breadcrumb">
    <a href="<?= base_url('/admin/courses') ?>">교육과정</a> ›
    <a href="<?= base_url('/admin/courses/' . $lesson['course_id'] . '/lessons') ?>"><?= esc($lesson['course_title']) ?></a> ›
    <?= esc($lesson['title']) ?> › 시험 문항
</div>

<?php
    $f = $edit ?? ['id' => 0, 'question' => '', 'choice1' => '', 'choice2' => '', 'choice3' => '', 'choice4' => '', 'answer' => 1, 'explanation' => '', 'sort_order' => count($questions) + 1];
?>
<div class="grid-2 grid-2--form">
    <div class="card">
        <div class="card__head"><h2><?= $edit ? '문항 수정' : '문항 추가' ?></h2>
            <?php if ($edit): ?><a href="<?= base_url('/admin/lessons/' . $lesson['id'] . '/questions') ?>" class="btn btn-soft btn-sm">새 문항 작성</a><?php endif ?>
        </div>
        <form method="post" action="<?= base_url('/admin/questions/save') ?>" class="form">
            <?= csrf_field() ?>
            <input type="hidden" name="lesson_id" value="<?= (int) $lesson['id'] ?>">
            <input type="hidden" name="id" value="<?= (int) $f['id'] ?>">

            <label>문제 <span class="req">*</span>
                <textarea name="question" rows="3" required placeholder="예) 책나무 독서교육에서 &quot;독서&quot;의 의미로 가장 알맞은 것은?"><?= esc(old('question', $f['question'])) ?></textarea>
            </label>

            <?php for ($n = 1; $n <= 4; $n++): ?>
                <label class="choice-input">
                    <input type="radio" name="answer" value="<?= $n ?>" <?= (int) old('answer', $f['answer']) === $n ? 'checked' : '' ?> title="정답으로 지정">
                    <span class="choice__num"><?= $n ?></span>
                    <input type="text" name="choice<?= $n ?>" value="<?= esc(old('choice' . $n, $f['choice' . $n])) ?>" placeholder="보기 <?= $n ?><?= $n > 2 ? ' (선택)' : '' ?>" <?= $n <= 2 ? 'required' : '' ?> maxlength="500">
                </label>
            <?php endfor ?>
            <p class="hint">왼쪽 ◉ 를 눌러 정답 보기를 지정하세요. 보기 3·4는 비워두면 2지선다/3지선다로 출제됩니다.</p>

            <label>해설 (결과 화면에 표시)
                <textarea name="explanation" rows="2" placeholder="정답 이유를 간단히 적어주세요"><?= esc(old('explanation', $f['explanation'])) ?></textarea>
            </label>
            <label>순서
                <input type="number" name="sort_order" value="<?= (int) old('sort_order', $f['sort_order']) ?>" style="width:100px">
            </label>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg"><?= $edit ? '수정 저장' : '문항 추가' ?></button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card__head"><h2>등록된 문항 (<?= count($questions) ?>)</h2>
            <a href="<?= base_url('/academy/quiz/' . $lesson['id']) ?>" target="_blank" class="btn btn-soft btn-sm">수강생 화면으로 보기</a>
        </div>
        <?php foreach ($questions as $i => $q): ?>
            <div class="q-item <?= $edit && (int) $edit['id'] === (int) $q['id'] ? 'q-item--editing' : '' ?>">
                <div class="q-item__head">
                    <strong>Q<?= $i + 1 ?>.</strong> <?= esc($q['question']) ?>
                </div>
                <ol class="q-item__choices">
                    <?php for ($n = 1; $n <= 4; $n++): ?>
                        <?php if (trim((string) $q['choice' . $n]) === '') continue; ?>
                        <li class="<?= (int) $q['answer'] === $n ? 'is-answer' : '' ?>"><?= esc($q['choice' . $n]) ?><?= (int) $q['answer'] === $n ? ' ✔' : '' ?></li>
                    <?php endfor ?>
                </ol>
                <div class="q-item__actions">
                    <a href="<?= base_url('/admin/lessons/' . $lesson['id'] . '/questions?edit=' . $q['id']) ?>" class="btn btn-soft btn-sm">수정</a>
                    <form method="post" action="<?= base_url('/admin/questions/' . $q['id'] . '/delete') ?>" class="inline" data-confirm="이 문항을 삭제할까요?">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger-soft btn-sm">삭제</button>
                    </form>
                </div>
            </div>
        <?php endforeach ?>
        <?php if ($questions === []): ?><p class="muted">아직 문항이 없습니다. 왼쪽에서 첫 문항을 추가하세요.</p><?php endif ?>
    </div>
</div>
<?= $this->endSection() ?>
