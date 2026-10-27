<div class="title">학기 관리</div>
<div class="sub_title">학기를 사용을 관리합니다.</div>
<br />
<div class="title_bt" onclick="createSemester()">학기 생성하기</div>
<div class="clear"></div>


<div class="tb_w">

    <div class="tb_t">
        <div class="td10">ID</div>
        <div class="td20">학기명</div>
        <div class="td15">등록된 악보</div>
        <div class="td15">생성일</div>
        <div class="td15">상태</div>
        <div class="td15">상태변경일</div>
        <div class="td10">수정</div>
        <div class="clear"></div>
    </div>
<?php
    foreach($list as $ls){
?>
    <div class="tb_tr">
        <div class="td10"><?=$ls -> num?></div>
        <div class="td20"><?=$ls -> name?></div>
        <div class="td15">0</div>
        <div class="td15"><?=$ls -> createdAt?></div>
        <div class="td15"><?=$ls -> isActive==0?"사용중":"사용안함"?></div>
        <div class="td15"><?=$ls -> updatedAt?>&nbsp;</div>
        <div class="td10"><a onclick="createSemester(<?=$ls -> num?>)">수정</a></div>
        <div class="clear"></div>
    </div>
<?php
    }
?>

</div><!--tb_w END-->


<script>
    function createSemester(num){

        bgbk();

        $.ajax({
                url: "/main/semester_create",
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

    function goSemester(){

        if(!document.form_semester.name.value){
            alert('학기 이름을 입력하세요');
            return;
        }

        if(!document.form_semester.isActive.value){
            alert('사용여부를 선택하세요');
            return;
        }

		var queryString = $("form[name=form_semester]").serialize() ;

        $.ajax({
            type : 'POST',
            url : '/main/semester_insert',
            data : queryString,
            dataType : 'json',
            complete : function(xhr, status){
                
                if(xhr.responseText == "success"){
                    alert('학기가 생성/수정 되었습니다');
                    location.reload();
                }
            }
        });
    }
</script>