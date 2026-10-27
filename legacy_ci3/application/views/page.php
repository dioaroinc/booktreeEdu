<?php		
	$total_go = $total_page - 1;
	
	if($total_page < 10){
		$start_page = 0;
		$end_page = $total_page;
	}
	else{

		if($now_page < 6){
			$start_page = 0;
		}
		else{
			$start_page = $now_page - 5;
		}
		
		if($total_page - $now_page < 5){
			$end_page = $total_page;
		}
		else{
			$end_page = $now_page + 5;	
		}
	}

?>
<div class="pagena">
  <div class="pagena_m" onclick="location.href='<?=$url?>/0/<?=$search_word?>'">처음</div>
<?php
  for($i=$start_page;$i<$end_page;$i++){
    $g = $i + 1;
?>
  <div class="pagena_m<?=$i==$now_page?"_s":""?>" onclick="location.href='<?=$url?><?=$i?>/<?=$search_word?>'"><?=$g?></div>
<?php
  }
?>
  <div class="pagena_m" onclick="location.href='<?=$url?><?=$total_go?>/<?=$search_word?>'">끝</div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
