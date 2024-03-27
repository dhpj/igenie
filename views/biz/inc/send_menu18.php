<div class="send_menu">
	<ul>
        <li><a href="/biz/manager/statistics/index" <?=strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/statistics/index") == true ? "class='sm_on'" : ""?>>통계메인</a></li>
        <li><a href="/biz/manager/statistics/companylist" <?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/statistics/companylist") || strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/statistics/detail")) == true ? "class='sm_on'" : ""?>>업체통계</a></li>
	</ul>
</div>
