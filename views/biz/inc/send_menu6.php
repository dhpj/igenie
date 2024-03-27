<div class="send_menu">
	<ul>
    <li><a href="/biz/manager/pendingbank/index"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/pendingbank/index") == true || strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/pendingbank/errlist") == true) ? " class='sm_on'" : ""?>><i class="xi-won"></i> 무통장입금목록</a></li>
		<li><a href="/biz/manager/pendingbank/bank_stat"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/pendingbank/bank_stat") == true) ? " class='sm_on'" : ""?>><i class="xi-chart-line"></i> 무통장입금통계</a></li>
    <li><a href="/biz/manager/vbank"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/vbank") == true) ? " class='sm_on'" : ""?>><i class="xi-coupon"></i> 가상계좌입금목록</a></li>
    <li><a href="/biz/manager/deposit/write"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/deposit/write") == true) ? " class='sm_on'" : ""?>><i class="xi-wallet"></i> 예치금 충전/조정</a></li>
    <li><a href="/biz/manager/deposit"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/deposit") == true and strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/deposit/write") == false and strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/deposit/prelists") == false) ? " class='sm_on'" : ""?>><i class="xi-receipt"></i> 결제내역목록</a></li>
    <li><a href="/biz/manager/deposit/prelists"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/manager/deposit/prelists") == true) ? " class='sm_on'" : ""?>><i class="xi-money"></i> 선충전목록</a></li>
    <li><a href="/biz/refund/lists"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/biz/refund/lists") == true) ? " class='sm_on'" : ""?>><i class="xi-exchange"></i> 환불요청목록</a></li>
	</ul>
</div>
