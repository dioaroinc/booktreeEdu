<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="page-head">
    <div>
        <h1 class="page-title">사이트 이용법</h1>
        <p class="page-sub">책나무 온라인 교육은 아래 순서로 진행됩니다.</p>
    </div>
</div>

<ol class="steps steps--vertical">
    <li class="step">
        <div class="step__num">1</div>
        <h3>교육과정 선택</h3>
        <p><a href="<?= base_url('/academy') ?>">강의실</a>에서 수강할 과정(신규원장교육 / 지점SW 사용교육 / 보수교육)을 선택합니다.</p>
    </li>
    <li class="step">
        <div class="step__num">2</div>
        <h3>강의 영상 시청</h3>
        <p>「강의보기」를 누르면 영상이 재생됩니다. 재생 위치가 자동 저장되어 다음에 「강의 계속 보기」로 이어서 볼 수 있습니다. 영상을 끝까지 보면 <strong>시청 완료</strong>로 표시됩니다.</p>
    </li>
    <li class="step">
        <div class="step__num">3</div>
        <h3>확인 시험 응시</h3>
        <p>시청 완료 후 「문제풀기」로 시험을 봅니다. 합격 기준(기본 80점) 이상이면 해당 강의가 <strong>통과</strong> 처리됩니다. 불합격 시 영상을 다시 보고 재응시할 수 있으며, 통과 후에도 다시 풀어볼 수 있습니다.</p>
    </li>
    <li class="step">
        <div class="step__num">4</div>
        <h3>나의 교육 현황 확인</h3>
        <p><a href="<?= base_url('/academy/mypage') ?>">나의 교육 현황</a>에서 과정별 진도, 응시 횟수, 최고 점수, 통과일을 확인할 수 있습니다.</p>
    </li>
</ol>

<div class="notice">
    <strong>자주 묻는 질문</strong><br><br>
    <strong>Q. 영상이 재생되지 않아요.</strong><br>
    최신 Chrome, Edge, Safari 브라우저를 사용해 주세요. 유튜브 영상은 사내망에서 차단된 경우 재생되지 않을 수 있습니다.<br><br>
    <strong>Q. 비밀번호를 잊었어요.</strong><br>
    온라인 교육 계정은 지점SW 계정과 동일합니다. 본사(1522-9320)로 문의해 주세요.<br><br>
    <strong>Q. 모바일에서도 가능한가요?</strong><br>
    네, 스마트폰·태블릿 브라우저에서 동일하게 수강할 수 있습니다.
</div>
<?= $this->endSection() ?>
