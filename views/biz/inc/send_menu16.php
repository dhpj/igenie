<div class="send_menu">
	<ul>
        <!-- <li><a href="/card/settlement" <?=strpos("/".$_SERVER['REQUEST_URI'], "/card/settlement") == true ? "class='sm_on'" : ""?>>주정산관리</a></li> -->
        <? if($this->member->item('mem_level') >= 10){ //최고admin관리자만 ?>
        <li><a href="/card/cardlist" <?=strpos("/".$_SERVER['REQUEST_URI'], "/card/cardlist") == true ? "class='sm_on'" : ""?>>카드정산</a></li>
        <?}?>
        <? if($this->member->item('mem_level') >= 150){ //최고admin관리자만 ?>
        <li><a href="/card/cardstat" <?=strpos("/".$_SERVER['REQUEST_URI'], "/card/cardstat") == true ? "class='sm_on'" : ""?>>카드정산통계</a></li>
        <li><a href="/card/dcalendar" <?=strpos("/".$_SERVER['REQUEST_URI'], "/card/dcalendar") == true ? "class='sm_on'" : ""?>>일정산달력</a></li>
        <li><a href="/card/wcalendar" <?=strpos("/".$_SERVER['REQUEST_URI'], "/card/wcalendar") == true ? "class='sm_on'" : ""?>>주정산달력</a></li>
        <? } ?>
        <? if($this->member->item('mem_level') >= 10){ //최고admin관리자만 ?>
        <li><a href="/card/cardorder" <?=strpos("/".$_SERVER['REQUEST_URI'], "/card/cardorder") == true ? "class='sm_on'" : ""?>>카드결제현황</a></li>
        <?}?>
        <? if($this->member->item('mem_level') >= 150){ //최고admin관리자만 ?>
        <li><a href="/card/cardsublist" <?=strpos("/".$_SERVER['REQUEST_URI'], "/card/cardsublist") == true ? "class='sm_on'" : ""?>>카드결제업체</a></li>
        <?}?>
	</ul>
</div>
