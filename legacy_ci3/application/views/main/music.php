<div class="title">곡(악보) 관리</div>
<div class="sub_title">곡과 해당 곡에 등록된 악보를 관리합니다. 클릭하시면 악보 관련한 상세한 정보를 확인하실 수 있습니다.</div>
<br />
<div class="title_bt" onclick="createMusic()">곡 생성하기</div>
<div class="clear"></div>


<div class="tb_w">

    <div class="tb_t">
        <div class="td10">No</div>
        <div class="td20">이름</div>
        <div class="td10">곡타입</div>
        <div class="td10">생성일</div>        
        <div class="td10">등록된 악보</div>                
        <div class="td10">공유된 학기</div>
        <div class="td5">상태</div>
        <div class="td15">상태변경일</div>
        <div class="td5">설정</div>
        <div class="td5">수정</div>
        <div class="clear"></div>
    </div>
<?php
    foreach($list as $ls){
?>
    <div class="tb_tr">
        <div class="td10"><?=$ls -> num?></div>
        <div class="td20"><?=$ls -> name?></div>
        <div class="td10"><?=$ls -> type==1?"일반":"종교"?></div>
        <div class="td10"><?=$ls -> createdAt?></div>
        <div class="td10">0</div>        
        <div class="td10">0</div>
        <div class="td5"><?=$ls -> isActive==0?"사용중":"사용안함"?></div>
        <div class="td15"><?=$ls -> updatedAt?>&nbsp;</div>
        <div class="td5"><a onclick="location.href='/main/music_view/<?=$ls -> num?>'">악보 설정</a></div>
        <div class="td5"><a onclick="createMusic(<?=$ls -> num?>)">수정</a></div>
        <div class="clear"></div>
    </div>
<?php
    }
?>

</div><!--tb_w END-->


<script>
    function createMusic(num){

        bgbk();

        $.ajax({
                url: "/main/music_create",
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

    function goMusic(){

        if(!document.form_music.name.value){
            alert('이름을 입력하세요');
            return;
        }

		var queryString = $("form[name=form_music]").serialize() ;

        $.ajax({
            type : 'POST',
            url : '/main/music_insert',
            data : queryString,
            dataType : 'json',
            complete : function(xhr, status){
                
                if(xhr.responseText == "success"){
                    alert('곡이 생성/수정 되었습니다');
                    location.reload();
                }
            }
        });
    }
</script>