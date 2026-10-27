<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="breadcrumb">
    <a href="<?= base_url('/academy') ?>">강의실</a> ›
    <a href="<?= base_url('/academy/course/' . $lesson['course_id']) ?>"><?= esc($lesson['course_title']) ?></a> ›
    <a href="<?= base_url('/academy/lesson/' . $lesson['id']) ?>"><?= esc($lesson['title']) ?></a> › 시험
</div>

<div class="quiz-wrap">
    <div class="quiz-head">
        <h1 class="page-title">📝 <?= esc($lesson['title']) ?> 확인 시험</h1>
        <p class="page-sub">총 <?= count($questions) ?>문항 · 합격 기준 <?= (int) $lesson['pass_score'] ?>점 · 각 문항의 정답을 하나씩 선택하세요.
            <?php if ($progress && (int) $progress['quiz_attempts'] > 0): ?>
                (지금까지 <?= (int) $progress['quiz_attempts'] ?>회 응시, 최고 <?= (int) $progress['quiz_best'] ?>점)
            <?php endif ?>
        </p>
    </div>

    <form method="post" action="<?= base_url('/academy/quiz/' . $lesson['id']) ?>" id="quizForm">
        <?= csrf_field() ?>

        <?php foreach ($questions as $i => $q): ?>
            <fieldset class="quiz-q" data-index="<?= $i + 1 ?>">
                <legend><span class="quiz-q__num">Q<?= $i + 1 ?></span> <?= nl2br(esc($q['question'])) ?></legend>
                <div class="quiz-q__choices">
                    <?php for ($n = 1; $n <= 4; $n++): ?>
                        <?php if (trim((string) $q['choice' . $n]) === '') continue; ?>
                        <label class="choice">
                            <input type="radio" name="answer[<?= $q['id'] ?>]" value="<?= $n ?>" required>
                            <span class="choice__num"><?= $n ?></span>
                            <span class="choice__text"><?= esc($q['choice' . $n]) ?></span>
                        </label>
                    <?php endfor ?>
                </div>
            </fieldset>
        <?php endforeach ?>

        <div class="quiz-submit">
            <div class="quiz-submit__count"><span id="answered">0</span> / <?= count($questions) ?> 문항 답변</div>
            <button type="submit" class="btn btn-primary btn-lg">답안 제출하기</button>
            <a href="<?= base_url('/academy/lesson/' . $lesson['id']) ?>" class="btn btn-soft">강의로 돌아가기</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    var form  = document.getElementById('quizForm');
    var total = <?= count($questions) ?>;
    var out   = document.getElementById('answered');

    function count() {
        var names = {};
        form.querySelectorAll('input[type=radio]:checked').forEach(function (el) { names[el.name] = 1; });
        out.textContent = Object.keys(names).length;
    }
    form.addEventListener('change', count);
    form.addEventListener('submit', function (e) {
        count();
        if (parseInt(out.textContent, 10) < total) {
            e.preventDefault();
            alert('모든 문항에 답해주세요. (' + out.textContent + ' / ' + total + ')');
            return;
        }
        if (!confirm('답안을 제출하시겠습니까?')) e.preventDefault();
    });
})();
</script>
<?= $this->endSection() ?>
