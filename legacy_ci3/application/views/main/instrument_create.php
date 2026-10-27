<div class="popup_type_1">
    <div class="popup_title">악기 생성/수정하기</div>

    <div class="popup_inner">
        <form name="form_instrument">
            <input type="hidden" name="num" value="<?=$info -> num?>" />
            <div class="ipt_title">악기 이름</div>
            <input type="text" name="name" placeholder="예) 피아노" class="ipt_box" value="<?=$info -> name?>" />

            <div class="ipt_title">사용 여부</div>
            <input type="radio" name="isActive" value="0" <?=$info -> isActive==0?"checked":""?> /> 사용
            <input type="radio" name="isActive" value="1" <?=$info -> isActive==1?"checked":""?> /> 사용 안 함
        </form>
    </div>

    <div class="popup_bt_cancel" onclick="bgbkShut()">취소</div>
    <div class="popup_bt_confirm" onclick="goInstrument()">확인</div>
</div>