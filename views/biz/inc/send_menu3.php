<div class="send_menu">
	<ul>
		<!--<li><a href="/dhnbiz/template/lists"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/template/lists") == true OR (strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/template/write/") == true AND strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/template/write/Y") == false)) ? " class='sm_on'" : ""?>><i class="xi-user"></i> 나의 템플릿</a></li>-->
		<li><a href="/dhnbiz/template/public_lists"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/template/public_lists") == true OR strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/template/write/Y") == true) ? " class='sm_on'" : ""?>><i class="xi-group"></i>템플릿 목록</a></li>
		<li><a href="/dhnbiz/template/take"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/template/take") == true) ? " class='sm_on'" : ""?>><i class="xi-user"></i>템플릿 가져오기</a></li>
		<li><a href="/dhnbiz/template/category"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/dhnbiz/template/category") == true) ? " class='sm_on'" : ""?>><i class="xi-folder-zip-o"></i> 템플릿 카테고리 관리</a></li>
	</ul>
</div>
