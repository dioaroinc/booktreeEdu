<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="breadcrumb"><a href="<?= base_url('/admin/courses') ?>">교육과정</a> › <?= esc($course['title']) ?></div>

<div class="toolbar">
    <p class="muted">강의를 등록하면 수강생 화면에 바로 표시됩니다. 각 강의의 「문항」에서 시험 문제를 등록하세요.</p>
    <div>
        <a href="<?= base_url('/admin/courses/' . $course['id'] . '/lessons/new') ?>" class="btn btn-primary">+ 강의 등록 (영상 업로드)</a>
        <button type="button" class="btn btn-soft" onclick="document.getElementById('sectionForm').classList.toggle('hidden')">+ 단원 추가</button>
        <form method="post" action="<?= base_url('/admin/lessons/sync-hq') ?>" class="inline" data-confirm="영상 주소가 비어 있는 강의를 본사 자료실 번호로 조회해 채웁니다. 진행할까요?">
            <?= csrf_field() ?>
            <input type="hidden" name="course_id" value="<?= (int) $course['id'] ?>">
            <button type="submit" class="btn btn-dark">⇅ 본사 자료실에서 영상 주소 가져오기</button>
        </form>
    </div>
</div>

<div class="card hidden" id="sectionForm">
    <form method="post" action="<?= base_url('/admin/sections/save') ?>" class="form form--inline">
        <?= csrf_field() ?>
        <input type="hidden" name="course_id" value="<?= (int) $course['id'] ?>">
        <label>단원 이름 <input type="text" name="title" placeholder="예) 1. 홈페이지 메인 프로그램 Part 1" required></label>
        <label>순서 <input type="number" name="sort_order" value="<?= count($sections) + 1 ?>" style="width:80px"></label>
        <button type="submit" class="btn btn-primary">단원 저장</button>
    </form>
</div>

<?php if ($sections !== []): ?>
    <div class="card">
        <div class="card__head"><h2>단원</h2></div>
        <table class="table table--compact">
            <thead><tr><th style="width:60px">순서</th><th>단원명</th><th style="width:220px">관리</th></tr></thead>
            <tbody>
            <?php foreach ($sections as $s): ?>
                <?php $fid = 'secForm' . (int) $s['id']; ?>
                <tr>
                    <td><input type="number" name="sort_order" form="<?= $fid ?>" value="<?= (int) $s['sort_order'] ?>" class="input-sm" style="width:60px"></td>
                    <td><input type="text" name="title" form="<?= $fid ?>" value="<?= esc($s['title']) ?>" class="input-sm" style="width:100%"></td>
                    <td class="actions">
                        <form method="post" action="<?= base_url('/admin/sections/save') ?>" id="<?= $fid ?>" class="inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                            <input type="hidden" name="course_id" value="<?= (int) $course['id'] ?>">
                            <button type="submit" class="btn btn-soft btn-sm">저장</button>
                        </form>
                        <form method="post" action="<?= base_url('/admin/sections/' . $s['id'] . '/delete') ?>" class="inline" data-confirm="단원을 삭제할까요? (소속 강의는 유지됩니다)">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger-soft btn-sm">삭제</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php endif ?>

<div class="card">
    <div class="card__head"><h2>강의 목록</h2></div>
    <table class="table">
        <thead>
        <tr>
            <th style="width:60px">순서</th>
            <th>강의명</th>
            <th style="width:140px">강연자 · 교육일</th>
            <th style="width:70px">자료실</th>
            <th style="width:100px">영상</th>
            <th style="width:60px">문항</th>
            <th style="width:60px">합격</th>
            <th style="width:80px">상태</th>
            <th style="width:226px">관리</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lessons as $l): ?>
            <?php $hasVideo = ($l['video_type'] === VIDEO_TYPE_FILE) ? ! empty($l['video_file']) : trim((string) $l['video_url']) !== ''; ?>
            <tr>
                <td><?= (int) $l['sort_order'] ?></td>
                <td>
                    <?php if (! empty($l['is_required'])): ?><span class="badge-required">★ 필수</span><?php endif ?>
                    <strong><?= esc($l['title']) ?></strong>
                    <?php if (! empty($sectionTitles[$l['section_id']])): ?><div class="muted" style="font-size:12px"><?= esc($sectionTitles[$l['section_id']]) ?></div><?php endif ?>
                </td>
                <td class="muted" style="font-size:12px"><?= esc($l['lecturer'] ?? '') ?><?= ! empty($l['lecture_date']) ? '<br>' . date('Y.m.d', strtotime($l['lecture_date'])) : '' ?></td>
                <td><?= esc($l['ref_no'] ?? '-') ?></td>
                <td>
                    <?php if (! $hasVideo): ?><span class="warn-text">⚠ 영상 없음</span>
                    <?php elseif ($l['video_type'] === VIDEO_TYPE_YOUTUBE): ?>▶ 유튜브
                    <?php elseif ($l['video_type'] === VIDEO_TYPE_FILE): ?>📁 업로드 파일
                    <?php else: ?>🔗 외부 URL<?php endif ?>
                </td>
                <td><?= (int) $l['question_count'] ?>개</td>
                <td><?= (int) $l['pass_score'] ?>점</td>
                <td><?= $l['is_active'] ? '<span class="status-pill status-passed">공개</span>' : '<span class="status-pill status-none">비공개</span>' ?></td>
                <td class="actions">
                    <a href="<?= base_url('/admin/lessons/' . $l['id'] . '/questions') ?>" class="btn btn-dark btn-sm">문항 (<?= (int) $l['question_count'] ?>)</a>
                    <a href="<?= base_url('/admin/lessons/' . $l['id'] . '/edit') ?>" class="btn btn-soft btn-sm">수정</a>
                    <a href="<?= base_url('/academy/lesson/' . $l['id']) ?>" class="btn btn-soft btn-sm" target="_blank">보기</a>
                    <form method="post" action="<?= base_url('/admin/lessons/' . $l['id'] . '/delete') ?>" class="inline" data-confirm="'<?= esc($l['title'], 'js') ?>' 강의와 문항, 수강 기록을 모두 삭제할까요?">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger-soft btn-sm">삭제</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
        <?php if ($lessons === []): ?><tr><td colspan="9" class="muted">등록된 강의가 없습니다. 「강의 등록」으로 첫 강의를 추가하세요.</td></tr><?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
