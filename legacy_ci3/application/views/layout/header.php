
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="책나무 동영상 교육 시스템" />
    <meta name="keywords" content="책나무 동영상 교육 시스템" />
    <meta name="author" content="타카그룹" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- 호환성 버튼 없애는 코드 -->
    <meta http-equiv="Page-exit" content="BlendTrans(Duration=0.3)">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" type="text/css" media="(min-width: 600px)" href="/garage/index.css?ver=<?=date('s')?>" />
    <link rel="stylesheet" type="text/css" media="(max-width: 600px)" href="/garage/index_m.css?ver=<?=date('s')?>" />

    <link rel="icon" type="image/png" sizes="32x32" href="/garage/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/garage/images/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/garage/images/favicon-16x16.png">

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&family=Noto+Serif+KR:wght@200;300;400;500;600;700;900&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <title><?=$now_title?> - 책나무 동영상 교육 시스템</title>
</head>
<body>

    <div class="upper">
        <a href="https://www.booktreei.com" target="_blank">책나무 홈페이지</a>&nbsp;&nbsp;&nbsp;
        <a href="https://www.booktreei.com/user/index" target="_blank" style="color: #09472b; font-weight: bold">지점SW</a>&nbsp;&nbsp;&nbsp;
    </div>

    <div class="header">
        <div class="header_logo"></div>
        <div class="header_title">온라인 동영상 교육</div>
        <div class="header_hello">
            <span><?=$_SESSION['name']?></span> 님 안녕하세요! &nbsp;&nbsp;
            <a href="/web/logout">[로그아웃]</a>
        </div>
        <div class="clear"></div>
    </div>


    <div class="sidebar">

        <div class="sidebar_m<?=$now_loc=='class_1'?"_s":""?>" onclick="location.href='/academy/lecture/1'">신규원장교육</div>
        <div class="sidebar_m<?=$now_loc=='class_2'?"_s":""?>" onclick="location.href='/academy/lecture/2'">지점SW 사용교육</div>
        <div class="sidebar_m<?=$now_loc=='class_3'?"_s":""?>" onclick="location.href='/academy/lecture/3'">보수교육</div>

        <div class="sidebar_n<?=$now_loc=='mypage'?"_s":""?>" onclick="alert('준비중입니다.')">나의 교육 현황</div>
        <div class="sidebar_n<?=$now_loc=='howto'?"_s":""?>" onclick="alert('준비중입니다.')">사이트 이용법</div>
        <div class="clear"></div>

    </div>