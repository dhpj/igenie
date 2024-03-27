<!-- 타이틀 영역
<div class="tit_wrap">
	충전하기<?//php echo element('unique_id', $view); ?>
</div>
타이틀 영역 END -->
<?
 if($eve_cnt>0){
?>
<script>
	// $(document).ready(function(){
	// 	$('input:radio[name=eve_list]').change(function(){
	// 		var select_val = $(this).val();
	// 		console.log(select_val);
	// 		switch (select_val) {
	// 			case '1':
	// 				$('#total_p').text('300,000원 / 5%');
	// 				$('#total_eve').text('15,000원');
	// 				$('#total_calv').text('315,000원');
	// 				break;
	// 			case '2':
	// 				$('#total_p').text('500,000원 / 7%');
	// 				$('#total_eve').text('35,000원');
	// 				$('#total_calv').text('535,000원');
	// 				break;
	// 			case '3':
	// 				$('#total_p').text('1,000,000원 / 8%');
	// 				$('#total_eve').text('80,000원');
	// 				$('#total_calv').text('1,080,000원');
	// 				break;
	// 			case '4':
	// 				$('#total_p').text('2,000,000원 / 9%');
	// 				$('#total_eve').text('180,000원');
	// 				$('#total_calv').text('2,180,000원');
	// 				break;
	// 			case '5':
	// 				$('#total_p').text('3,000,000원 / 10%');
	// 				$('#total_eve').text('300,000원');
	// 				$('#total_calv').text('3,300,000원');
	// 				break;
    //
    //
    //
	// 		}
	// 	});
    //
	// });

</script>
<? } ?>
<!-- 3차 메뉴 -->
<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu10.php'); //상단 서브 메뉴 ?>
<div id="mArticle">
	<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/deposit/bootstrap/_inc_top.php'); //예치금 잔액 ?>
    <?
     if($eve_cnt>0){
    ?>
	<!-- <div class="form_section">
		<div class="inner_tit mg_t30">
			<h3>이벤트충전 안내</h3>
		</div>
		<div class="white_box">
			<p class="point_info_tit">
				<i class="xi-info"></i> 금액을 선택하시면 서비스 충전금액을 확인할 수 있습니다. <span>입금시 서비스금액 자동충전</span>됩니다.
			</p>
			<div class="input_content_wrap">
				<ul class="service_point">
					<li>
				    <input type="radio" name="eve_list" id="tag_all1" class="tag_list" style="cursor:pointer;" value="1" checked="">
				    <label for="tag_all" style="cursor:pointer;">300,000 충전시 <i class="xi-arrow-right"></i> <span>5%(15,000원)</span> 서비스충전</label>
				  </li>
					<li>
				    <input type="radio" name="eve_list" id="tag_all2" class="tag_list" style="cursor:pointer;" value="2">
				    <label for="tag_all" style="cursor:pointer;">500,000 충전시 <i class="xi-arrow-right"></i> <span>7%(35,000원)</span> 서비스충전</label>
				  </li>
					<li>
				    <input type="radio" name="eve_list" id="tag_all3" class="tag_list" style="cursor:pointer;" value="3">
				    <label for="tag_all" style="cursor:pointer;">1,000,000 충전시 <i class="xi-arrow-right"></i> <span>8%(80,000원)</span> 서비스충전</label>
				  </li>
					<li>
				    <input type="radio" name="eve_list" id="tag_all4" class="tag_list" style="cursor:pointer;" value="4">
				    <label for="tag_all" style="cursor:pointer;">2,000,000 충전시 <i class="xi-arrow-right"></i> <span>9%(180,000원)</span> 서비스충전</label>
				  </li>
					<li>
				    <input type="radio" name="eve_list" id="tag_all5" class="tag_list" style="cursor:pointer;" value="5">
				    <label for="tag_all" style="cursor:pointer;">3,000,000 충전시 <i class="xi-arrow-right"></i> <span>10%(300,000원)</span> 서비스충전</label>
				  </li>
				</ul>
				<p class="point_re1">
					<strong>입금금액</strong> <span id="total_p">300,000원 / 5% </span>  = <span class="fr"><i class="xi-plus-circle-o"></i> <span id="total_eve">15,000원</span></span>
				</p>
				<p class="point_re2">
					<strong>총 충전금액</strong>
					<span class="fr"><i class="xi-won"></i> <span id="total_calv">315,000원</span></span>
				</p>
			</div>
			<div class="input_content_wrap">
				<p class="point_info_tit">
					<i class="xi-info"></i> 환불 요청시 이벤트(서비스) 금액은 전액 차감 후 환불 됩니다.
				</p>
				<ul class="point_info">
					<li>- 300,000원 이상 500,000원 미만 충전시 15,000원 충전</li>
					<li>- 500,000원 이상 1,000,000원 미만 충전시 35,000원 충전</li>
					<li>- 1,000,000원 이상 2,000,000원 미만 충전시 80,000원 충전</li>
					<li>- 2,000,000원 이상 3,000,000원 미만 충전시 180,000원 충전</li>
					<li>- 3,000,000원 이상 충전시 300,000원 충전</li>
				</ul>
			</div>
		</div>
	</div> -->
<? } ?>
	<div class="form_section">
		<div class="inner_tit mg_t30">
			<h3>계좌이체 안내</h3>
		</div>
		<div class="white_box">
			<div class="input_content_wrap">
				<div class="bank_content">
					<p class="bank_num"><i class="xi-info-o"></i> 입금 계좌번호 : <?=$this->config->item('payment_bank_name')?> <span><?=$this->config->item('payment_bank_number')?></span> (예금주 : <?=$this->config->item('payment_bank_owner')?>)</p>
					<p class="bank_info1"><i class="xi-info-o"></i> 계좌이체시 <span>입금자명에 사업자번호를 입력</span>(입금을 원하시는 업체의 사업자번호)해주셔야 자동입금처리가 됩니다.</p>
					<p class="bank_info2">
						- <span class="fc_red">사업자번호 입력시 "-" 빼고 입력</span>해주시기 바랍니다.
					<br  />- 입금 후 좌메뉴 상단 나의 예치금 옆 <span class="material-icons">cached</span> 새로고침 아이콘을 클릭하시면 반영된 예치금으로 확인하실 수 있습니다.
					<br  />- 입금자명을 잘못 입력하셨을 경우 1522-7985로 문의하시기 바랍니다.(입금날짜, 입금자명, 입금금액을 알려주시면 됩니다.)</p>
					<div class="bank_img">
						<div class="bank_img_box">
							<p class="bank1_name">기업은행 뱅킹예시</p>
							<p class="bank1_img"><img src="/images/bank_ibk.jpg" alt="기업은행 뱅킹예시"></p>
						</div>
						<div class="bank_img_box">
							<p class="bank2_name">농협 뱅킹예시</p>
							<p class="bank2_img"><img src="/images/bank_nh.jpg" alt="농협은행 뱅킹예시"></p>
						</div>
                        <?
                        $eve1_flag="N";
                        // if(!empty(config_item('eve1_member'))){
                        //     foreach(config_item('eve1_member') as $r){
                        //         if($r==$this->member->item("mem_id")){
                        //             $eve1_flag="Y";
                        //         }
                        //     }
                        // }
                         // if($eve_cnt>0||$eve1_flag=="Y"){
                         //    $nowTime = date("Y-m-d H:i:s"); // 현재 시간
                         //    $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime1'))); // 종료 시간
                         //    $eventTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime1'))); // 이벤트 시작 시간
                         //    if($nowTime < $expTime && $nowTime >= $eventTime){

                        ?>
                                <!-- <div class="bank_img_box">
        							<p class="bank3_name">충전금 이벤트</p>
        							<p class="bank2_img"><img src="/images/bank_event_20230524.jpg" alt="이벤트"></p>
        						</div> -->
                        <?
                        //     }
                        //
                        // }
                        ?>
					</div>
				</div>
			</div>
			<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/deposit/bootstrap/_inc_bottom.php'); //세금계산서 발급 안내 ?>
		</div>
	</div>
</div><!-- mArticle END -->
