<div class="title">악기 관리</div>
<div class="sub_title">악기 사용을 관리합니다.</div>
<br />
<div class="title_bt" onclick="createInstrument()">악기 생성하기</div>
<div class="clear"></div>


<div class="tb_w">

    <div class="tb_t">
        <div class="td10">ID</div>
        <div class="td20">학기명</div>
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
        <div class="td15"><?=$ls -> createdAt?></div>
        <div class="td15"><?=$ls -> isActive==0?"사용중":"사용안함"?></div>
        <div class="td15"><?=$ls -> updatedAt?>&nbsp;</div>
        <div class="td10"><a onclick="createInstrument(<?=$ls -> num?>)">수정</a></div>
        <div class="clear"></div>
    </div>
<?php
    }
?>

</div><!--tb_w END-->


<script>
    function createInstrument(num){

        bgbk();

        $.ajax({
                url: "/main/instrument_create",
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

    function goInstrument(){

        if(!document.form_instrument.name.value){
            alert('악기기 이름을 입력하세요');
            return;
        }

        if(!document.form_instrument.isActive.value){
            alert('사용여부를 선택하세요');
            return;
        }

		var queryString = $("form[name=form_instrument]").serialize() ;

        $.ajax({
            type : 'POST',
            url : '/main/instrument_insert',
            data : queryString,
            dataType : 'json',
            complete : function(xhr, status){
                
                if(xhr.responseText == "success"){
                    alert('악기가 생성/수정 되었습니다');
                    location.reload();
                }
            }
        });
    }
</script>