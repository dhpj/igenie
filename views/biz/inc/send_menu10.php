<div class="send_menu">
	<ul>
		<li><a href="/deposit"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/deposit/redbank") == true) ? " class='sm_on'" : ""?>><i class="xi-battery-80"></i> 충전하기</a></li>
		<!--<li><a href="/deposit/vbank"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/deposit/vbank") == true) ? " class='sm_on'" : ""?>><i class="xi-money"></i> 계좌입금내역</a></li>-->
		<li><a href="/deposit/history"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/deposit/history") == true) ? " class='sm_on'" : ""?>><i class="xi-money"></i> 나의 지갑</a></li>
        <? $nowTime = date("Y-m-d H:i:s"); // 현재 시간
        // $in_ip = $_SERVER['REMOTE_ADDR'];
        // if($in_ip=="61.75.230.209"){
        // }
         ?>
    <?
    if($eve_cnt>0||$this->member->item("mem_id")==2||$this->member->item("mem_id")==3||$this->member->item("mem_id")==1260){
            $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime1'))); // 시작 시간
            // $startTime = date("Y-m-d H:i:s", strtotime('2022-07-27 00:00:00')); // 시작 시간
            $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime1'))); // 종료 시간
            if($nowTime < $expTime && $nowTime >= $startTime){
            ?>
            <li><a href="/deposit/eventpage"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/deposit/eventpage") == true) ? " class='sm_on'" : ""?>><i class="xi-gift-o"></i> 이벤트충전</a></li>
            <!-- <li><a href="/deposit/event2page"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/deposit/event2page") == true) ? " class='sm_on'" : ""?>><i class="xi-gift-o"></i> 소개 이벤트</a></li> -->
    <? }else{
        if($this->member->item("mem_id")==2||$this->member->item("mem_id")==3){ ?>
            <li><a href="/deposit/eventpage"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/deposit/eventpage") == true) ? " class='sm_on'" : ""?>><i class="xi-gift-o"></i> 이벤트충전</a></li>
    <?    }
    }
 }
// else if($eve_cnt2>0||$this->member->item("mem_id")==962){
//         // $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime2'))); // 시작 시간
//         $startTime = date("Y-m-d H:i:s", strtotime('2022-08-05 00:00:00')); // 시작 시간
//         $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime2'))); // 종료 시간
//         if($nowTime < $expTime && $nowTime >= $startTime){
            ?>
        <!-- <li><a href="/deposit/eventpage"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/deposit/eventpage") == true) ? " class='sm_on'" : ""?>><i class="xi-gift-o"></i> 이벤트충전</a></li> -->
    <?// } } ?>
        <?
            if ($this->member->item('mem_id') == 3 || $this->member->item('mem_id') == 962){
        ?>
            <li><a href="/deposit/write"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/deposit/write") == true) ? " class='sm_on'" : ""?>><i class="xi-wallet"></i> 선충전</a></li>
            <li><a href="/deposit/prelist"<?=(strpos("/".$_SERVER['REQUEST_URI'], "/deposit/prelist") == true) ? " class='sm_on'" : ""?>><i class="xi-money"></i> 선충전이력</a></li>
        <?
            }

        ?>
	</ul>
</div>
