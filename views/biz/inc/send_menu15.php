<div class="send_menu">
	<ul>
        <li><a href="/biz/partner/lists" <?=strpos("/".$_SERVER['REQUEST_URI'], "/biz/partner/lists") == true ? "class='sm_on'" : ""?>>파트너관리</a></li>
        <?if($this->member->item("mem_level") >= 100){?>
        <li><a href="/biz/partner/company_lists" <?=strpos("/".$_SERVER['REQUEST_URI'], "/biz/partner/company_lists") == true ? "class='sm_on'" : ""?>>업체관리</a></li>
        <?}?>

        <?if($this->member->item("mem_level") >= 150){?>
        <li><a href="/biz/partner/no_lists" <?=strpos("/".$_SERVER['REQUEST_URI'], "/biz/partner/no_lists") == true ? "class='sm_on'" : ""?>>파트너(사용안함)</a></li>
        <?}?>
	</ul>
</div>
