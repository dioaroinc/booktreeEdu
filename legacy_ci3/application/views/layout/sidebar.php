<div class="sidebar">
    

    <div class="sidebar_t">관리 메뉴</div>
    <div class="sidebar_m<?=$now_loc=='semester'?"_s":""?>" onclick="location.href='/main/semester'">🏫 학기 관리</div>
    <div class="sidebar_m<?=$now_loc=='instrument'?"_s":""?>" onclick="location.href='/main/instrument'">🎺 악기 관리</div>
    <div class="sidebar_m<?=$now_loc=='user'?"_s":""?>" onclick="location.href='/main/user'">🧑🏻‍🎓 회원 관리</div>
    <div class="sidebar_m<?=$now_loc=='music'?"_s":""?>" onclick="location.href='/main/music'">♪ 곡(악보) 관리</div>

    <div class="sidebar_t">서비스 설정</div>
    <div class="sidebar_m<?=$now_loc==''?"":""?>" onclick="location.href='/main/semester'">계정 관리</div>
    <div class="sidebar_m<?=$now_loc==''?"":""?>" onclick="location.href='/main/instrument'">음악이론 관리</div>
    <div class="sidebar_m<?=$now_loc==''?"":""?>" onclick="location.href='/main/user'">연습관리표</div>
    <div class="sidebar_m<?=$now_loc==''?"":""?>" onclick="location.href='/main/music'">랭킹</div>

</div>
<div class="rarea">