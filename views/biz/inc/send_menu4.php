<div class="send_menu">
	<ul>
		<li><a href="/biz/myinfo/info"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/myinfo/info") == true) ? " class='sm_on'" : ""?>><i class="xi-profile-o"></i> 매장정보</a></li>
		<li><a href="/biz/myinfo/my_price"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/myinfo/my_price") == true) ? " class='sm_on'" : ""?>><i class="xi-gift-o"></i> 이용상품</a></li>
    <? if ($this->member->item('mem_pay_type') != 'A') {?>
		<!--<li><a href="/biz/myinfo/charge_history"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/myinfo/charge_history") == true) ? " class='sm_on'" : ""?>><i class="xi-battery-80"></i> 충전내역</a></li>
    <li><a href="/biz/myinfo/use_history"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/myinfo/use_history") == true) ? " class='sm_on'" : ""?>><i class="xi-money"></i> 사용내역</a></li>-->
        <? //if($this->member->item("mem_id")==3){ ?>
		<li><a href="/biz/myinfo/refund"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/myinfo/refund") == true) ? " class='sm_on'" : ""?>><i class="xi-credit-card"></i> 환불신청</a></li>
        <? //} ?>
    <? } ?>
	</ul>
</div>
