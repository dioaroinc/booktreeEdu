<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="guide">

    <p class="guide__lead">
        책나무 온라인 교육을 운영하는 관리자를 위한 안내입니다.
        맨 위 <strong>처음 셋팅 점검</strong>에서 지금 상태를 확인하고, 아래 순서대로 따라 하면 됩니다.
    </p>

    <nav class="guide-toc" aria-label="가이드 목차">
        <a href="#g-check">처음 셋팅 점검</a>
        <a href="#g-login">1. 관리자 로그인</a>
        <a href="#g-course">2. 교육과정</a>
        <a href="#g-lesson">3. 강의 · 영상</a>
        <a href="#g-quiz">4. 시험 문항</a>
        <a href="#g-user">5. 회원 관리</a>
        <a href="#g-progress">6. 수강 현황</a>
        <a href="#g-rule">7. 통과 기준</a>
    </nav>

    <!-- ───────── 처음 셋팅 점검 ───────── -->
    <section class="card guide-section" id="g-check">
        <h2>처음 셋팅 점검</h2>
        <p class="muted">지금 이 서버의 상태를 자동으로 확인한 결과입니다. <span class="guide-dot guide-dot--warn"></span> 표시가 있으면 조치해 주세요.</p>
        <ul class="guide-check">
            <?php foreach ($checks as $c): ?>
                <li class="guide-check__item guide-check__item--<?= esc($c['state'], 'attr') ?>">
                    <span class="guide-dot guide-dot--<?= esc($c['state'], 'attr') ?>"></span>
                    <div>
                        <strong><?= esc($c['label']) ?></strong>
                        <span><?= esc($c['detail']) ?></span>
                    </div>
                    <?php if (! empty($c['action'])): ?>
                        <form method="post" action="<?= base_url($c['action']['url']) ?>" class="guide-check__action" data-confirm="<?= esc($c['action']['confirm'], 'attr') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger-soft btn-sm"><?= esc($c['action']['label']) ?></button>
                        </form>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>

        <h3>운영을 시작하기 전에 할 일</h3>
        <ol class="guide-steps">
            <li><a href="<?= base_url('/admin/users') ?>">회원 관리</a>에서 <code>admin</code> 계정의 비밀번호를 바꿉니다.</li>
            <li><a href="<?= base_url('/admin/courses') ?>">교육과정 · 강의</a>에서 과정과 강의 목록이 맞는지 확인하고, <span class="ui">본사 자료실에서 영상 주소 가져오기</span>를 눌러 영상 주소를 채웁니다.</li>
            <li>「⚠ 영상 없음」으로 남은 강의는 <span class="ui">수정</span>에서 영상을 직접 넣거나, 준비될 때까지 「공개」를 끕니다.</li>
            <li>시험이 필요한 강의에는 <span class="ui">문항</span>에서 문제를 등록합니다.</li>
            <li><a href="<?= base_url('/academy') ?>" target="_blank" rel="noopener">수강생 화면</a>에서 영상 재생과 시험 응시가 되는지 직접 한 번 해 봅니다.</li>
        </ol>
        <p class="hint">홍보 메인과 로그인 화면에 안내되는 체험용 수강생 계정(<code>demo</code>)을 더 이상 쓰지 않으려면 회원 관리에서 「정지」로 바꾸세요.</p>
    </section>

    <!-- ───────── 1. 관리자 로그인 ───────── -->
    <section class="card guide-section" id="g-login">
        <h2>1. 관리자 로그인과 권한</h2>
        <p>관리자 화면은 <strong>관리자 권한이 있는 계정</strong>으로 로그인하면 자동으로 열립니다. 수강생 화면 상단에도 빨간색 「관리자」 메뉴가 생깁니다.</p>
        <table class="table guide-table">
            <thead><tr><th style="width:210px">계정 종류</th><th>관리자가 되는 조건</th></tr></thead>
            <tbody>
            <tr>
                <td><strong>본사(지점SW) 계정</strong></td>
                <td>본사 회원 등급이 <strong>최종관리자(100)</strong>, <strong>본사직원 교육담당(70)</strong>, <strong>본사직원 주문담당(71)</strong>이면 로그인할 때 자동으로 관리자가 됩니다. 그 밖의 계정은 수강생입니다.</td>
            </tr>
            <tr>
                <td><strong>직접 만든 계정</strong></td>
                <td>회원 관리에서 「구분」을 <strong>관리자 (본사)</strong>로 지정한 계정입니다.</td>
            </tr>
            </tbody>
        </table>
        <ul class="guide-list">
            <li>본사 계정은 지점SW 와 같은 아이디 · 비밀번호를 씁니다. 처음 로그인하는 순간 회원 목록에 자동으로 추가됩니다.</li>
            <li>본사에서 등급을 바꿨다면, 해당 계정의 비밀번호가 바뀌어 다시 본사 확인을 거칠 때 반영됩니다. 급하면 회원 관리에서 「구분」을 직접 바꾸세요.</li>
        </ul>
    </section>

    <!-- ───────── 2. 교육과정 ───────── -->
    <section class="card guide-section" id="g-course">
        <h2>2. 교육과정 만들기</h2>
        <p>구조는 <strong>교육과정 → (단원) → 강의 → 시험 문항</strong> 순서입니다. 단원은 강의가 많을 때 묶어 주는 용도이고 없어도 됩니다.</p>
        <ol class="guide-steps">
            <li><a href="<?= base_url('/admin/courses') ?>">교육과정 · 강의</a>에서 <span class="ui">+ 교육과정 등록</span>을 누릅니다.</li>
            <li>과정명, 부제, 소개를 적고 <strong>아이콘</strong>과 <strong>대표 색상</strong>을 팔레트에서 고릅니다. 아래 미리보기에서 바로 확인할 수 있습니다.</li>
            <li><strong>정렬 순서</strong>는 숫자가 작을수록 위에 나옵니다. <strong>공개</strong>를 꺼 두면 수강생에게 보이지 않습니다.</li>
            <li>저장한 뒤 목록에서 <span class="ui">강의 관리</span>를 눌러 강의를 추가합니다.</li>
        </ol>
        <ul class="guide-list">
            <li><strong>잠시 숨기려면 「공개」를 끄세요.</strong> 과정을 비공개로 하면 그 안의 강의는 수강생에게 보이지 않고 통과율 계산에서도 빠집니다. 수강 기록은 그대로 남아 다시 공개하면 이어집니다.</li>
            <li>강의가 들어 있는 과정은 삭제할 수 없습니다. 강의를 먼저 삭제해야 하며, 강의를 삭제하면 그 강의의 시험 문항과 수강 기록이 함께 지워집니다.</li>
        </ul>
    </section>

    <!-- ───────── 3. 강의 · 영상 ───────── -->
    <section class="card guide-section" id="g-lesson">
        <h2>3. 강의 등록과 영상 넣기</h2>
        <p>과정의 <span class="ui">강의 관리</span> 화면에서 <span class="ui">+ 강의 등록 (영상 업로드)</span>를 누릅니다. 영상은 세 가지 방법 중 하나로 넣습니다.</p>
        <table class="table guide-table">
            <thead><tr><th style="width:170px">방법</th><th>언제 쓰나</th><th style="width:38%">주의할 점</th></tr></thead>
            <tbody>
            <tr>
                <td><strong>▶ 유튜브</strong> <span class="status-pill status-passed">권장</span></td>
                <td>유튜브에 「일부 공개(링크가 있는 사용자만)」로 올린 뒤 주소를 붙여 넣습니다. 서버 용량과 속도 걱정이 없습니다.</td>
                <td>「비공개」 영상은 재생되지 않습니다. 사내망에서 유튜브가 막힌 지점은 볼 수 없습니다.</td>
            </tr>
            <tr>
                <td><strong>📁 영상 파일 업로드</strong></td>
                <td>유튜브에 올릴 수 없는 영상을 서버에 직접 올립니다. (mp4, webm, mov)</td>
                <td>이 서버의 업로드 한도는 파일 1개당 <strong><?= esc($uploadLimit) ?></strong>입니다. 더 큰 파일은 유튜브나 외부 주소를 쓰세요.</td>
            </tr>
            <tr>
                <td><strong>🔗 외부 영상 URL</strong></td>
                <td>본사 서버나 클라우드에 이미 올라가 있는 MP4 파일의 주소를 그대로 씁니다.</td>
                <td>주소가 <code>https://</code> 로 시작하고 브라우저에서 바로 재생되는 파일이어야 합니다.</td>
            </tr>
            </tbody>
        </table>

        <h3>본사 자료실 영상 한 번에 연결하기</h3>
        <p>강의의 <strong>본사 자료실 번호</strong> 칸에 자료실 글 번호를 적어 두면, <span class="ui">⇅ 본사 자료실에서 영상 주소 가져오기</span> 버튼이 그 글에 등록된 영상 주소를 찾아 넣어 줍니다.</p>
        <ul class="guide-list">
            <li>영상 주소가 <strong>비어 있는 강의만</strong> 채웁니다. 이미 넣은 주소는 바꾸지 않으니 여러 번 눌러도 안전합니다.</li>
            <li>결과에 <strong>「영상이 없는 글」</strong>이 나오면 자료실 글에 영상이 없는 것이고, <strong>「자료실에 없는 번호」</strong>가 나오면 번호가 틀린 것입니다. 해당 강의만 직접 수정하세요.</li>
        </ul>

        <h3>그 밖의 항목</h3>
        <table class="table guide-table">
            <tbody>
            <tr><td style="width:210px"><strong>강연자 · 교육 날짜</strong></td><td>수강생 강의 목록에 함께 표시됩니다.</td></tr>
            <tr><td><strong>신규 원장 필수 시청</strong></td><td>체크하면 강의 제목 앞에 <span class="badge-required">★ 필수</span> 표시가 붙습니다.</td></tr>
            <tr><td><strong>총 시간(분)</strong></td><td>안내용 숫자입니다. 비워 두면 표시하지 않습니다.</td></tr>
            <tr><td><strong>합격 점수(%)</strong></td><td>시험 합격 기준입니다. 기본값은 80점입니다.</td></tr>
            <tr><td><strong>영상 시청 완료 후에만 시험 응시</strong></td><td>켜 두면 영상을 끝까지 본 사람만 시험을 볼 수 있습니다. 보통 켜 둡니다.</td></tr>
            <tr><td><strong>공개</strong></td><td>끄면 수강생에게 보이지 않고 통과율 계산에서도 빠집니다.</td></tr>
            </tbody>
        </table>
    </section>

    <!-- ───────── 4. 시험 문항 ───────── -->
    <section class="card guide-section" id="g-quiz">
        <h2>4. 시험 문항 등록</h2>
        <ol class="guide-steps">
            <li>강의 목록에서 해당 강의의 <span class="ui">문항 (0)</span> 버튼을 누릅니다.</li>
            <li>문제와 보기를 적고, 보기 왼쪽의 ◉ 를 눌러 <strong>정답</strong>을 지정합니다. 보기 3 · 4를 비우면 2지선다 · 3지선다가 됩니다.</li>
            <li><strong>해설</strong>을 적어 두면 수강생의 시험 결과 화면에 함께 나옵니다.</li>
            <li><span class="ui">문항 추가</span>로 저장하고, <span class="ui">수강생 화면으로 보기</span>로 실제 시험 화면을 확인합니다.</li>
        </ol>
        <ul class="guide-list">
            <li>점수는 <strong>맞힌 문항 수 ÷ 전체 문항 수 × 100</strong>입니다. 문항이 5개면 한 문제에 20점입니다.</li>
            <li><strong>문항이 하나도 없는 강의</strong>는 시험 없이, 영상을 끝까지 보면 통과로 처리됩니다.</li>
            <li>이미 응시한 사람이 있는 강의의 문항을 바꾸면, 과거 응시 기록의 문항별 표시가 어긋날 수 있습니다. 점수와 합격 여부는 그대로 유지됩니다.</li>
        </ul>
    </section>

    <!-- ───────── 5. 회원 관리 ───────── -->
    <section class="card guide-section" id="g-user">
        <h2>5. 회원 관리</h2>
        <ul class="guide-list">
            <li><strong>지점 원장님 계정은 만들 필요가 없습니다.</strong> 지점SW 아이디로 처음 로그인하면 자동으로 등록됩니다. (목록의 「출처」에 <strong>지점SW</strong> 로 표시됩니다)</li>
            <li>지점SW 계정이 없는 사람에게는 <span class="ui">+ 회원 등록</span>으로 계정을 직접 만들어 줍니다.</li>
            <li><strong>폐점하거나 그만둔 지점</strong>은 「상태」를 <strong>정지</strong>로 바꾸세요. 본사에서 폐점 처리해도, 이미 한 번 로그인했던 계정은 여기서 정지해야 완전히 막힙니다.</li>
            <li>본사 계정의 비밀번호는 <strong>지점SW 에서 바꾸는 것이 원칙</strong>입니다. 바꾼 뒤 새 비밀번호로 로그인하면 교육 사이트에도 반영됩니다.</li>
            <li>회원을 삭제하면 그 사람의 수강 기록도 함께 지워집니다. 기록을 남기려면 삭제 대신 정지를 쓰세요.</li>
        </ul>
    </section>

    <!-- ───────── 6. 수강 현황 ───────── -->
    <section class="card guide-section" id="g-progress">
        <h2>6. 수강 현황 확인</h2>
        <ul class="guide-list">
            <li><a href="<?= base_url('/admin/progress') ?>">수강 현황</a>에서 수강생별 <strong>시청 완료 수, 통과 수, 통과율, 최근 활동</strong>을 한눈에 봅니다.</li>
            <li>이름을 누르면 강의별로 <strong>재생 위치, 응시 횟수, 최고 점수, 통과일</strong>과 시험 응시 기록이 나옵니다.</li>
            <li><a href="<?= base_url('/admin') ?>">대시보드</a>에는 최근 시험 응시 10건과 전체 숫자가 나옵니다.</li>
            <li>통과율은 <strong>공개 과정에 들어 있는 공개 강의 전체</strong>를 기준으로 계산합니다. 강의를 새로 공개하면 모든 수강생의 통과율이 그만큼 내려갑니다.</li>
        </ul>
    </section>

    <!-- ───────── 7. 통과 기준 ───────── -->
    <section class="card guide-section" id="g-rule">
        <h2>7. 시청 완료와 통과 기준</h2>
        <table class="table guide-table">
            <thead><tr><th style="width:140px">상태</th><th>뜻</th></tr></thead>
            <tbody>
            <tr><td><span class="status-pill status-none">미수강</span></td><td>아직 영상을 열지 않았습니다.</td></tr>
            <tr><td><span class="status-pill status-watching">수강중</span></td><td>영상을 보기 시작했습니다. 재생 위치가 10초마다 저장되어 다음에 이어서 볼 수 있습니다.</td></tr>
            <tr><td><span class="status-pill status-video_done">시험 대기</span></td><td>영상을 <strong>95% 이상</strong> 봤거나 끝까지 재생했습니다. 이제 시험을 볼 수 있습니다.</td></tr>
            <tr><td><span class="status-pill status-passed">통과</span></td><td>시험에서 합격 점수 이상을 받았습니다. 한 번 통과하면 다시 풀어서 점수가 낮게 나와도 통과가 유지됩니다.</td></tr>
            <tr><td><span class="status-pill status-passed">시청 완료</span></td><td>시험 문항이 없는 강의를 끝까지 봤습니다. 통과로 계산됩니다.</td></tr>
            </tbody>
        </table>
    </section>

</div>
<?= $this->endSection() ?>
