<div class="title">회원 관리</div>
<div class="sub_title">등록된 회원을 관리합니다. 지휘자를 누르면 지휘자에 소속된 강사, 회원 정보를 확인하실 수 있습니다.</div>
<br />
<div class="title_bt" onclick="createUser()">지휘자 생성하기</div>
<div class="clear"></div>


<div class="tb_w">

    <div class="tb_t">
        <div class="td10">No</div>
        <div class="td20">이름</div>
        <div class="td10">아이디</div>
        <div class="td10">등록된 강사</div>        
        <div class="td10">등록된 학생</div>                
        <div class="td15">생성일</div>
        <div class="td5">상태</div>
        <div class="td15">상태변경일</div>
        <div class="td5">수정</div>
        <div class="clear"></div>
    </div>
<?php
    foreach($list as $ls){
?>
    <div class="tb_tr">
        <div class="td10"><?=$ls -> num?></div>
        <div class="td20"><?=$ls -> name?></div>
        <div class="td10"><?=$ls -> id?></div>
        <div class="td10">0</div>
        <div class="td10">0</div>        
        <div class="td15"><?=$ls -> createdAt?></div>
        <div class="td5"><?=$ls -> isActive==0?"사용중":"사용안함"?></div>
        <div class="td15"><?=$ls -> updatedAt?>&nbsp;</div>
        <div class="td5"><a onclick="createUser(<?=$ls -> num?>)">수정</a></div>
        <div class="clear"></div>
    </div>
<?php
    }
?>

</div><!--tb_w END-->


<script>
    function createUser(num){

        bgbk();

        $.ajax({
                url: "/main/user_create",
                type: "POST",
                dataType: "html",
                data: {
                    "num" : num
                },
                complete: function(xhr, textStatus) {
                    $("#bg_bk").html(xhr.responseText);
                }
            }); //ajax End


    }

    function goUser(){

        if(!document.form_user.name.value){
            alert('이름을 입력하세요');
            return;
        }

        if(!document.form_user.id.value){
            alert('아이디를 입력하세요');
            return;
        }
        
        if(!document.form_user.isActive.value){
            alert('사용여부를 선택하세요');
            return;
        }

		var queryString = $("form[name=form_user]").serialize() ;

        $.ajax({
            type : 'POST',
            url : '/main/user_insert',
            data : queryString,
            dataType : 'json',
            complete : function(xhr, status){
                
                if(xhr.responseText == "success"){
                    alert('지휘자가 생성/수정 되었습니다');
                    location.reload();
                }
            }
        });
    }
</script>