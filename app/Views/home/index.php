<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- ───────── HERO ───────── -->
<section class="hero">
    <div class="container hero__inner">
        <div class="hero__text">
            <span class="eyebrow">책나무 가맹 원장님 전용</span>
            <h1>언제 어디서나<br>책나무 <em>온라인 교육</em></h1>
            <p>신규원장교육부터 지점SW 사용법, 보수교육까지.<br>
               동영상 강의를 보고 바로 시험으로 확인하는 책나무 공식 교육 시스템입니다.</p>
            <div class="hero__cta">
                <a href="<?= base_url(is_logged_in() ? '/academy' : '/login') ?>" class="btn btn-primary btn-lg">교육 시작하기</a>
                <a href="#demo" class="btn btn-ghost btn-lg">데모 강의 보기</a>
            </div>
            <ul class="hero__stats">
                <li><strong><?= $stats['courses'] ?></strong><span>교육과정</span></li>
                <li><strong><?= $stats['lessons'] ?></strong><span>동영상 강의</span></li>
                <li><strong>24h</strong><span>언제나 수강</span></li>
            </ul>
        </div>
        <div class="hero__visual">
            <div class="hero__card hero__card--video">
                <div class="hero__play">▶</div>
                <div class="hero__card-title">제1강 접속방법, 메인프로그램 소개</div>
                <div class="hero__card-sub">총 10분 · 시험 문항 5개</div>
            </div>
            <div class="hero__card hero__card--quiz">
                <div class="hero__quiz-q">Q. 책나무 독서교육에서 "독서"의 의미는?</div>
                <div class="hero__quiz-a hero__quiz-a--on">읽고, 생각하고, 쓰는 모든 과정</div>
                <div class="hero__quiz-a">책을 빠르게 많이 읽는 것</div>
            </div>
            <div class="hero__card hero__card--pass">통과율 <strong>76%</strong></div>
        </div>
    </div>
</section>

<!-- ───────── 특징 ───────── -->
<section class="section features">
    <div class="container">
        <div class="section__head">
            <span class="eyebrow">왜 온라인 교육인가요?</span>
            <h2>지점 운영에 꼭 필요한 교육을<br>가장 편한 방법으로</h2>
        </div>
        <div class="features__grid">
            <div class="feature">
                <div class="feature__icon">🎬</div>
                <h3>동영상 강의</h3>
                <p>본사 교육팀이 제작한 강의 영상을 PC·태블릿·모바일에서 반복 시청할 수 있습니다. 보던 위치가 자동 저장됩니다.</p>
            </div>
            <div class="feature">
                <div class="feature__icon">📝</div>
                <h3>강의별 확인 시험</h3>
                <p>영상 시청 후 바로 문제를 풀어 이해도를 확인합니다. 합격 기준에 못 미치면 다시 응시할 수 있습니다.</p>
            </div>
            <div class="feature">
                <div class="feature__icon">📈</div>
                <h3>나의 교육 현황</h3>
                <p>과정별 진도와 통과율을 한눈에. 본사에서도 지점별 교육 이수 현황을 확인합니다.</p>
            </div>
            <div class="feature">
                <div class="feature__icon">🔐</div>
                <h3>지점 계정 그대로</h3>
                <p>별도 가입 없이 책나무 지점SW 계정으로 로그인하면 바로 수강할 수 있습니다.</p>
            </div>
        </div>
    </div>
</section>

<!-- ───────── 교육과정 ───────── -->
<section class="section courses" id="courses">
    <div class="container">
        <div class="section__head">
            <span class="eyebrow">교육과정</span>
            <h2>원장님의 단계에 맞는 교육</h2>
            <p>신규 개원부터 운영 심화까지, 책나무가 함께합니다.</p>
        </div>
        <div class="courses__grid">
            <?php foreach ($courses as $course): ?>
                <article class="course-card" style="--course-color: <?= esc($course['color'], 'attr') ?>">
                    <div class="course-card__icon"><?= esc($course['icon']) ?></div>
                    <h3><?= esc($course['title']) ?></h3>
                    <p class="course-card__sub"><?= esc($course['subtitle']) ?></p>
                    <p class="course-card__desc"><?= esc($course['description']) ?></p>
                    <div class="course-card__meta">
                        <span>🎬 강의 <?= (int) $course['lesson_count'] ?>개</span>
                        <span>⏱ <?= format_minutes((int) $course['total_minutes']) ?></span>
                    </div>
                    <a href="<?= base_url(is_logged_in() ? '/academy/course/' . $course['id'] : '/login') ?>" class="btn btn-outline">수강하러 가기</a>
                </article>
            <?php endforeach ?>
        </div>
    </div>
</section>

<!-- ───────── 이용 방법 ───────── -->
<section class="section how" id="how">
    <div class="container">
        <div class="section__head">
            <span class="eyebrow">이용 방법</span>
            <h2>3단계로 끝나는 교육 이수</h2>
        </div>
        <ol class="steps">
            <li class="step">
                <div class="step__num">1</div>
                <h3>지점 계정으로 로그인</h3>
                <p>책나무 지점SW 와 동일한 아이디·비밀번호로 로그인합니다. 계정 문의는 본사 1522-9320.</p>
            </li>
            <li class="step">
                <div class="step__num">2</div>
                <h3>강의 영상 시청</h3>
                <p>과정을 선택하고 순서대로 영상을 시청합니다. 끝까지 보면 자동으로 시청 완료 처리됩니다.</p>
            </li>
            <li class="step">
                <div class="step__num">3</div>
                <h3>확인 시험 통과</h3>
                <p>강의별 확인 시험에서 합격 점수 이상을 받으면 해당 강의가 <strong>통과</strong>로 표시됩니다.</p>
            </li>
        </ol>
    </div>
</section>

<!-- ───────── 데모 ───────── -->
<section class="section demo" id="demo">
    <div class="container demo__inner">
        <div class="demo__text">
            <span class="eyebrow">데모 강의</span>
            <h2>강의와 시험, 이렇게 진행됩니다</h2>
            <p>지점 계정으로 로그인하면 강의 영상 시청 → 진도 저장 → 확인 시험 → 결과 확인까지 전 과정을 바로 진행할 수 있습니다.</p>
            <ul class="demo__list">
                <li>영상은 보던 위치가 저장되어 다음에 이어서 볼 수 있습니다.</li>
                <li>영상을 끝까지 보면 시험이 열리고, 합격하면 강의가 통과로 표시됩니다.</li>
                <li>불합격해도 영상을 다시 보고 재응시할 수 있습니다.</li>
            </ul>
            <a href="<?= base_url(is_logged_in() ? '/academy' : '/login') ?>" class="btn btn-primary btn-lg">강의실 입장</a>
        </div>
        <div class="demo__video">
            <div class="video-frame">
                <iframe src="https://www.youtube.com/embed/<?= youtube_id('https://www.youtube.com/watch?v=5cJ-tHkI4MA') ?>?rel=0"
                        title="데모 강의 영상" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen loading="lazy"></iframe>
            </div>
            <div class="demo__caption">제1강 접속방법, 메인프로그램 소개, 진단평가 (데모 영상)</div>
        </div>
    </div>
</section>

<!-- ───────── CTA ───────── -->
<section class="cta">
    <div class="container cta__inner">
        <h2>지금 바로 교육을 시작하세요</h2>
        <p>책나무 지점 계정으로 로그인하면 모든 과정을 무료로 수강할 수 있습니다.</p>
        <a href="<?= base_url(is_logged_in() ? '/academy' : '/login') ?>" class="btn btn-light btn-lg">교육 시작하기</a>
    </div>
</section>

<?= $this->endSection() ?>
