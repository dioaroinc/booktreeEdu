
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

    <title>책나무 동영상 교육 : Booktree online education </title>
</head>
<body class="body_green">

    <div class="login_form">
        <div class="login_logo"></div>
        <div class="login_title">로그인 후 이용하실 수 있습니다.</div>
        
        <div class="login_txt">아이디</div>
        <input type="text" class="login_ipt" placeholder="책나무 가맹점 아이디"  id="login_id" />
        <div class="login_txt">비밀번호</div>
        <input type="password" class="login_ipt" placeholder="비밀번호 입력" id="login_pass" />
        <div class="login_pass"><a href="/web/find">비밀번호 찾기</a></div>

        <div class="login_bt" onclick="goLogin()">로그인</div>

        <div class="login_info">
            책나무 동영상 교육 사이트 이용계정은 <br />
            책나무 지원소프트웨어 지점 계정과 동일합니다.<br />
            로그인 관련 정보는 <br /><span>책나무 본사로(1522-9320) 문의바랍니다.</span>
        </div>

    </div>

    <div class="login_footer">
        Copyright © 책나무 주식회사 All rights reserved.
    </div>

    

    <script>
        function goLogin(){

            var id = document.getElementById("login_id").value;
            var pass = document.getElementById("login_pass").value;

            if(!id){
                alert('아이디를 입력하세요');
                return;
            }

            if(!pass){
                alert('비밀번호를 입력하세요');
                return;
            }

            $.ajax({
                url: "/web/auth",
                type: "POST",
                dataType: "html",
                data: {
                    "id" : id,
                    "pass" : pass
                },
                complete: function(xhr, textStatus) {
                    if (textStatus == 'success') {
                        
                        console.log(xhr.responseText);

                        if(xhr.responseText == "failed"){
                            alert('아이디/비밀번호를 확인하시기 바랍니다');
                            return;
                        }

                        if(xhr.responseText == "pending"){
                            alert('승인을 기다리고 있습니다. 승인 후 이용가능합니다.');
                            return;
                        }

                        if(xhr.responseText == "success"){
                            location.href='/main/index';
                        }
                    }
                }
            }); //ajax End


        }
    </script>


</body>
</html>
