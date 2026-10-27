<div class="popup_type_1">
    <div class="popup_title">곡 생성/수정하기</div>

    <div class="popup_inner">
        <form name="form_music">
            <input type="hidden" name="num" value="<?=$info -> num?>" />
            <div class="ipt_title">종류</div>
            <select name="type" class="ipt_sel">
	            <option value="1" <?=$info -> type==1?"selected":""?>>일반음악</option>
	            <option value="2" <?=$info -> type==2?"selected":""?>>종교음악</option>
            </select>
            
    
            <div class="ipt_title">곡 명</div>
            <input type="text" name="name" placeholder="" class="ipt_box" value="<?=$info -> name?>" />

            <div class="ipt_title">사용 여부</div>
            <input type="radio" name="isActive" value="0" <?=$info -> isActive==0?"checked":""?> /> 사용
            <input type="radio" name="isActive" value="1" <?=$info -> isActive==1?"checked":""?> /> 사용 안 함
        </form>
    </div>

    <div class="popup_bt_cancel" onclick="bgbkShut()">취소</div>
    <div class="popup_bt_confirm" onclick="goMusic()">확인</div>
</div>