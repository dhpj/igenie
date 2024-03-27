<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<?php
if ($this->depositconfig->item('use_deposit_cash_to_deposit')) {
    $sform['view'] = $view;
    if ($this->cbconfig->item('use_payment_pg') && element('use_pg', $view)) {
        $this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form1name', $view), $sform);
    }
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="modal">
		<div class="modal-content">
			<div class="modal-body">
				<div class="content"></div>
				<div class="modal_bottom">
				<button type="button" class="btn md btn-primary" data-dismiss="modal">확인</button>
				</div>
			</div>
		</div>
  </div>
</div>
<!-- 타이틀 영역
<div class="tit_wrap">
	충전하기<?//php echo element('unique_id', $view); ?>
</div>
타이틀 영역 END -->
<!-- 3차 메뉴 -->
<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu10.php'); //상단 서브 메뉴 ?>
<div id="mArticle">
	<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/deposit/bootstrap/_inc_top.php'); //예치금 잔액 ?>
	<div class="form_section">
		<div class="inner_tit mg_t30">
			<h3><?php echo $this->depositconfig->item('deposit_name'); ?> 충전</h3>
      <button id="myBtn" class="btn_myModal">크롬 결제 시 오류 해결방법</button>
<!-- The Modal -->
<div id="myModal2" class="dh_modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-tit">
      <span class="dh_close">&times;</span>
      크롬 결제 시 오류 해결방법
    </div>
    <div class="modal-con">
      구글 크롬 브라우저가 80버전으로 업데이트 되면서, 크롬 브라우저에서 결제가 실패되는 현상이 있습니다.
  이는 사용자의 환경에서 사이트 내 저장된 쿠키와 충돌하여 일어나는 현상으로,
  이 경우 아래와 같이 변경 후 사용 부탁 드립니다.<br />
  아래 설정이 어려울 경우, 엣지, 네이버웨일 등 다른 브라우저 이용 권장 드립니다.<br /><br />
  <span class="fsize_up">[ 설정방법 ]</span> <br />
  1. 크롬 실행 후 주소입력란에 <span class="fcolor_red">"chrome://flags"</span> 를 입력하여 이동<br />
  2. 검색창에 <span class="fcolor_red">"samesite"</span> 검색<br />
  3. 검색 후 목록에서 첫번째 <span class="fcolor_red">SameSite by default cookies</span> 항목을 <br /> &nbsp; &nbsp; Default -> <span class="fcolor_blue">Disabled</span> 로 변경<br />
  4. 목록의 세번째 <span class="fcolor_red">Cookies without SameSite must be secure</span> 항목을 <br /> &nbsp; &nbsp; Default -> <span class="fcolor_blue">Disabled</span> 로 변경<br />
  5. 맨 하단에 <span class="fcolor_red">Relaunch</span> 를 클릭하여 재실행 후 사용<br /><br />
  <p>
    <a class="post_link" href="http://igenie.co.kr/post/5" target="_blank">자세히보기(공지사항으로 이동)</a><br /><br />
    <span class="fsize_up">T. 문의 : 1522-7985</span>
  </p>
    </div>
  </div>
</div>

<script>
// Get the modal
var modal = document.getElementById("myModal2");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("dh_close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
		</div>
		<div class="white_box">
		    <?php
		    $attributes = array('class' => 'form-horizontal', 'name' => 'fpayment', 'id' => 'fpayment', 'autocomplete' => 'off');
		    echo form_open(site_url('deposit/update'), $attributes);
		    if ($this->cbconfig->item('use_payment_pg') && element('use_pg', $view)) {
		        $this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form2name', $view), $sform);
		    }
		    ?>
	        <input type="hidden" name="deposit_real" value="0" />
	        <input type="hidden" name="unique_id" value="<?php echo element('unique_id', $view); ?>" />
	        <input type="hidden" name="good_mny" value="0" />
	        <input type="hidden" name="unique_mem_id" value="<?=$this->member->item("mem_id")?>" /><?//2020-11-04 추가?>
			<div class="input_content_wrap">
				<label class="input_tit">충전할 금액</label>
				<div class="input_content">
					<div class="charging_checks">
						<?php
			            if (element('cashtodep', $view)) {
			                foreach (element('cashtodep', $view) as $key => $val) {
			                    if ( ! element('0', $val) OR ! element('1', $val)) {
			                        continue;
			                    }
			                    if (is_null($this->member->item('min_charge_amt')) || $this->member->item('min_charge_amt') <= element('1', $val)) {
			            ?>
						<label for="charge_<?php echo element('0', $val); ?>">
						  <input type="hidden" name="deposit_value[]" value="<?php echo element('1', $val); ?>" />
						  <input type="radio" name="money_value" value="<?php echo element('0', $val); ?>" id="charge_<?php echo element('0', $val); ?>" onClick="document.getElementById('charge_etc').value=this.value" />
						  <?php echo number_format(element('0', $val));?>원
						</label>
						<?php
						        }
						    }
						}
						?>
						<label for="money_value">
                          <input type="radio" name="money_value" id="money_value" class="money_value" value="-1" checked />
                          직접입력
						</label>
					</div>
					<div class="money_box mg_t10">
					  <input type="text" class="deposit_money" id="charge_etc" name="deposit_value[]" value="" min="30000" max="3000000" onkeyup="numberFormat(this)">
					  <span class="money_w">원</span>
					</div>
				</div>
			</div>
			<div class="input_content_wrap" style="display:none;">
				<label class="input_tit">결제방법</label>
				<div class="input_content">
		            <div class="checks">
						<?
							$payment_vbank = $this->member->item('payment_vbank');
							if($payment_vbank == "") $payment_vbank = "o"; //2020-11-16 빈값 예외 처리
						?>
						<?php if ($this->cbconfig->item('use_payment_bank') && $this->member->item('payment_bank')) {
		                    /* <input class="" type="radio" name="pay_type" value="bank" id="pay_type_bank" checked /><label for="pay_type_bank">무통장입금(<?php echo nl2br($this->member->item('paymemt_bank_info')); ?>)</label> */
		                ?>
		                	<input class="" type="radio" name="pay_type" value="bank" id="pay_type_bank" /><label for="pay_type_bank">계좌이체</label>
		                <?php } ?>
		                <?php  if ($this->cbconfig->item('use_payment_card') && $this->member->item('mem_payment_card')) { ?>
		                    <input type="radio" name="pay_type" value="card" id="pay_type_card" /><label for="pay_type_card">신용카드</label>
		                <?php } ?>
		                <?php if ($this->cbconfig->item('use_payment_realtime') && $this->member->item('payment_realtime')) { ?>
		                    <input type="radio" name="pay_type" value="realtime" id="pay_type_realtime" /><label for="pay_type_realtime">계좌이체</label>
		                <?php } ?>
		                <?php if ($this->cbconfig->item('use_payment_vbank') && $payment_vbank) {  /* <input type="radio" name="pay_type" value="vbank" id="pay_type_vbank" /><label for="pay_type_vbank">가상계좌</label> */ ?>
		                	<input type="radio" name="pay_type" value="vbank" id="pay_type_vbank" checked><label for="pay_type_vbank">무통장입금</label>
		                <?php } ?>
		                <?php if ($this->cbconfig->item('use_payment_phone') && $this->member->item('payment_phone')) { ?>
		                	<input type="radio" name="pay_type" value="phone" id="pay_type_phone" /><label for="pay_type_phone">휴대폰결제</label>
		                <?php } ?>
		            </div>
				</div>
			</div>
			<?
				//입금자명
				$mem_realname = $this->member->item('mem_username'); //상호
				$mem_realname = ($mem_realname) ? $mem_realname : $this->member->item('mem_bank_user'); //송금인 이름
				$mem_realname = ($mem_realname) ? $mem_realname : $this->member->item('mem_nickname'); //담당자 이름
				$mem_realname = ($mem_realname) ? $mem_realname : "송금인"; //기본값

				//이메일
				$mem_email = $this->member->item('mem_email'); //이메일
				$mem_email = ($mem_email) ? $mem_email : "test@igenie.co.kr"; //기본값 이메일

				//휴대폰 번호
				$mem_phone = $this->member->item('mem_emp_phone'); //담당자 연락처
				$mem_phone = ($mem_phone) ? $mem_phone : $this->member->item('mem_phone'); //SMS 발신 전화번호
				$mem_phone = ($mem_phone) ? $mem_phone : "0100000000"; //기본값
			?>
			<div class="input_content_wrap" style="display:none;">
				<label class="input_tit">입금자명</label>
				<div class="input_content">
					<input type="text" name="mem_realname" value="<?=$mem_realname?>" style="vertical-align: middle;" /><i class="label_notice" style="vertical-align: middle; color: red; margin-left: 10px;">* 입력한 입금자명과 실제 입금자명이 틀릴 경우 입금확인이 되지 않습니다.</i>
				</div>
			</div>
			<div class="input_content_wrap" style="display:none;">
				<label class="input_tit">이메일</label>
				<div class="input_content">
					<input type="email" name="mem_email" value="<?=$mem_email?>" style="width:300px;" /><i class="label_notice" style="vertical-align: middle; color: red; margin-left: 10px;">* 세금계산서 발행 이메일 주소를 입력해주세요.</i>
				</div>
			</div>
			<div class="input_content_wrap" style="display:none;">
				<label class="input_tit">휴대폰 번호</label>
				<div class="input_content">
					<input type="text" name="mem_phone" value="<?=$mem_phone?>"/><i class="label_notice" style="vertical-align: middle; color: red; margin-left: 10px;">* 휴대폰번호를 입력해주세요.</i>
				</div>
			</div>

	        <div class="alert alert-success bank-info">
	            <div><strong>계좌안내</strong></div>
	            <div><?php echo nl2br($this->member->item('paymemt_bank_info')); /*nl2br($this->cbconfig->item('payment_bank_info'));*/ ?> </div>
	        </div>
	        <?php
	        if ($this->cbconfig->item('use_payment_pg')) {
	            $this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form3name', $view), $sform);
	            }
	        ?>
	        <?php if ($this->depositconfig->item('deposit_charge_point')) { ?>
	        <p><i class="fa fa-dot-circle-o"></i> 결제시, 결제 금액의 <?php echo $this->depositconfig->item('deposit_charge_point'); ?>% 가 포인트로 적립됩니다.</p>
			<?php } ?>
			<? include_once($_SERVER['DOCUMENT_ROOT'].'/views/deposit/bootstrap/_inc_bottom.php'); //세금계산서 발급 안내 ?>
		</div>
	</div>
</div><!-- mArticle END -->
<script type="text/javascript">
//<![CDATA[
/*
$(document).on('change', 'input[name= pay_type]', function() {
    if ($("input[name='pay_type']:checked").val() === 'bank') {
        $('.bank-info').show();
    } else {
        $('.bank-info').hide();
    }
});
*/
//]]>
</script>

<?php
    echo form_close();
}

if ($this->depositconfig->item('use_deposit_point_to_deposit')) {
?>
<div class="page-header widget-header">
    <h4>포인트로 <?php echo $this->depositconfig->item('deposit_name'); ?> 충전</h4>
</div>
<div class="">
    <p class="credit_tit">현재 포인트 : <span class="textblue"><?php echo number_format($this->member->item('mem_point')); ?> 포인트</span></p>
    <div class="credit_form">
        <a class="btn btn-default" href="javascript:;" role="button" onClick="open_point_to_deposit();" title="포인트를 <?php echo $this->depositconfig->item('deposit_name'); ?>(으)로 전환하기"><i class="fa fa-refresh"></i> 전환하기</a>
    </div>
    <p><i class="fa fa-dot-circle-o"></i> 포인트로 <?php echo $this->depositconfig->item('deposit_name'); ?> 충전이 가능합니다</p>
    <?php if ($this->depositconfig->item('deposit_point_min')) {?>
        <p><i class="fa fa-dot-circle-o"></i> <?php echo $this->depositconfig->item('deposit_point_min'); ?> 포인트 이상부터 충전 가능합니다.</p>
    <?php } ?>
    <p><i class="fa fa-dot-circle-o"></i> 포인트 <?php echo $this->depositconfig->item('deposit_point'); ?> 점당 <?php echo $this->depositconfig->item('deposit_name'); ?> 1<?php echo $this->depositconfig->item('deposit_unit'); ?> (으)로 전환됩니다.</p>
</div>
<?php
}

if ($this->depositconfig->item('use_deposit_deposit_to_point')) {
?>
<div class="page-header widget-header">
    <h4><?php echo $this->depositconfig->item('deposit_name'); ?>을(를) 포인트로 전환</h4>
</div>
<div class="credit">
    <p class="credit_tit">현재 <?php echo $this->depositconfig->item('deposit_name'); ?> 소유 : <span class="textblue"><?php echo number_format($this->member->item('total_deposit') + 0); ?> <?php echo $this->depositconfig->item('deposit_unit'); ?></span></p>
    <div class="credit_form">
        <a class="btn btn-default" href="javascript:;" role="button" onClick="open_deposit_to_point();" title="<?php echo $this->depositconfig->item('deposit_name'); ?>(을)를 포인트로 전환하기"><i class="fa fa-refresh"></i> 전환하기</a>
    </div>
    <p><i class="fa fa-dot-circle-o"></i> <?php echo $this->depositconfig->item('deposit_name'); ?>(을)를 포인트로 전환하실 수 있습니다</p>
    <p><i class="fa fa-dot-circle-o"></i> <?php echo $this->depositconfig->item('deposit_name'); ?> 1<?php echo $this->depositconfig->item('deposit_unit'); ?>당 <?php echo $this->depositconfig->item('deposit_refund_point'); ?>포인트로 전환됩니다</p>
    <?php if ($this->depositconfig->item('deposit_refund_point_min')) { ?>
        <p><i class="fa fa-dot-circle-o"></i> 최소 <?php echo $this->depositconfig->item('deposit_refund_point_min'); ?><?php echo $this->depositconfig->item('deposit_unit'); ?> 이상 전환이 가능합니다</p>
    <?php } ?>
</div>
<?php
}
?>


<!--
<div class="page-header widget-header">
    <h4><?php echo $this->depositconfig->item('deposit_name'); ?> 최근 변동내역</h4>
</div>
<div class="credit mb50">
    <div class="credit_info">
        <span class="pull-left">현재 나의 <?php echo $this->depositconfig->item('deposit_name'); ?> : <?php echo number_format($this->member->item('total_deposit') + 0); ?> <?php echo $this->depositconfig->item('deposit_unit'); ?></span>
        <span class="pull-right"><a href="<?php echo site_url('deposit/mylist'); ?>" title="나의 <?php echo $this->depositconfig->item('deposit_name'); ?> 최근 변동내역 보기">더보기</a></span>
    </div>
    <table class="table table-hover table-striped table-bordered table-highlight-head t_center">
        <thead>
            <tr>
                <th>날짜</th>
                <th class="text-center">충전</th>
                <th class="text-center">사용</th>
                <th class="text-center">잔액</th>
                <th class="col-md-6 col-md-offset-1">내용</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (element('list', $view)) {
            foreach (element('list', $view) as $result) {
        ?>
            <tr>
                <td><?php echo display_datetime(element('dep_deposit_datetime', $result)); ?></td>
                <td class="text-right text-primary"><?php if (element('dep_deposit', $result) >= 0) { echo number_format(element('dep_deposit', $result)) . ' ' . html_escape($this->depositconfig->item('deposit_unit')); } ?></td>
                <td class="text-right text-danger"><?php if (element('dep_deposit', $result) < 0) { echo number_format(abs(element('dep_deposit', $result))) . ' ' . html_escape($this->depositconfig->item('deposit_unit')); } ?></td>
                <td class="text-right"><?php echo number_format(element('dep_deposit_sum', $result)) . ' ' . html_escape($this->depositconfig->item('deposit_unit')); ?></td>
                <td><?php echo nl2br(html_escape(element('dep_content', $result))); ?></td>
            </tr>
        <?php
            }
        }
        if ( ! element('list', $view)) {
        ?>
            <tr>
                <td colspan="5" class="nopost">자료가 없습니다</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</div>
-->
<script type="text/javascript">
	var use_pg = '<?php echo element('use_pg', $view) ? '1' : ''; ?>';
	var pg_type = '<?php echo $this->cbconfig->item('use_payment_pg'); ?>';
	var payment_unique_id = '<?php echo element('unique_id', $view); ?>';
	var good_name = '<?php echo html_escape(element('good_name', $view)); ?>';
	var ptype = 'deposit';
	$(document).ready(function() {
		$(".modal-content").css('width', '600px');
		$(".modal-body").css('height', '250px');
		$("#myModal").css('overflow-x', 'hidden');
		$("#myModal").css('overflow-y', 'hidden');

		$('input:radio[name="money_value"]').change(
			function(){
				if (this.checked && this.value == '-1') {
					$("#charge_etc").prop("readonly", false);
				} else {
					var data = comma(this.value);
					//alert("data : "+ data);
					$("#charge_etc").val(data);
					$("#charge_etc").prop("readonly", true);
				}
			});

		var func = fpayment_check;
		fpayment_check = function() {

			var f = document.fpayment;
			var mem_realname_val = f.mem_realname.value,
				mem_email_val = f.mem_email.value,
				mem_phone_val = f.mem_phone.value,
				phone_regexp = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/,
				email_regexp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

			var price_ =0;
			var money_val = document.getElementsByName('money_value');
			var deposit_val = document.getElementsByName('deposit_value[]');

			/*
			for (i = 0; i < money_val.length; i++) {
				if (money_val[i].checked) {
					if(parseInt(money_val[i].value) < 0) {
						price_ = parseInt(deposit_val[i].value);
					}else {
						price_ = parseInt(money_val[i].value);
					}
					break;
				}
			}
			*/
			price_ = $("#charge_etc").val();
			if( price_ == "" ){
				alert("충전할 금액을 입력 또는 선택하세요.");
				$("#charge_etc").focus();
				return false;
			}
			if( mem_realname_val == "" ){
				alert("입금자명을 입력하세요.");
				f.mem_realname.select();
				return false;
			}
			if( ! jQuery.trim(mem_realname_val) ){
				alert("실명을 올바르게 입력해 주세요.");
				f.mem_realname.select();
				return false;
			}
			if( mem_email_val == "" ){
				alert("이메일을 입력하세요.");
				f.mem_email.select();
				return false;
			}
			if( !email_regexp.test(mem_email_val) ){
				alert("이메일을 올바르게 입력해 주세요.");
				f.mem_email.select();
				return false;
			}
			if( mem_phone_val == "" ){
				alert("휴대폰 번호를 입력하세요.");
				f.mem_phone.select();
				return false;
			}
			if( !phone_regexp.test(mem_phone_val) ){
				alert("휴대폰 번호를 올바르게 입력해 주세요.");
				f.mem_phone.select();
				return false;
			}
			var pay_type = $("input[name='pay_type']:checked").val(); //결제방법(vbank.가상계좌입금)
			//alert("pay_type : "+ pay_type); return false;
			//alert("pay_type : "+ pay_type);
			var result = confirm('입금자명 : ' + f.mem_realname.value + '\n입금금액 : '+ price_ + '원\n\n계속 진행 하시겠습니까?');
			if(result) { //yes
				//var charge = $("#money_value").val();
				//if(charge == -1) {
				//	 $("#money_value").val ( $("#charge_etc").val() );
				//}
				//alert($("#money_value").val() + ", " + $("#charge_etc").val());
				//return false;
				price_ = price_.replace(/[^0-9]/g,''); //숫자만 추출
				$("#charge_etc").val(price_);

				if ($("input[name='pay_type']:checked").val() === 'bank') {
					$('.bank-info').show();
					$(".content").html("<strong><?php echo nl2br($this->member->item('paymemt_bank_info'));/*nl2br($this->cbconfig->item('payment_bank_info'));*/ ?></strong><br/><br/>위의 계좌로 입금해 주세요!");
					$('#myModal').modal({backdrop: 'static'});
					$('.btn-primary').click(function() {
						func();
					});
				} else {
					$('.bank-info').hide();
					func();
				}
			} else {
				//no
			}
		}

		$('.money_value').click(function() {
	// 		if($(this).val() > 300000) {
	// 			$('#pay_type_card').parent().hide();
	// 			$('#pay_type_realtime').parent().hide();
	// 			//$('#pay_type_vbank').parent().hide();
	// 			$('#pay_type_phone').parent().hide();
	// 		} else {
	// 			$('#pay_type_card').parent().show();
	// 			$('#pay_type_realtime').parent().show();
	// 			//$('#pay_type_vbank').parent().show();
	// 			$('#pay_type_phone').parent().show();
	// 		}
		});
	});

	//숫자가 입력되면 3자리 마다 콤마 찍기
	function numberFormat(obj) {
	  obj.value = comma(uncomma(obj.value));
	}

	// 콤마 찍기
	function comma(str) {
	  str = String(str);
	  return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
	}

	// 콤마 풀기
	function uncomma(str) {
	  str = String(str);
	  return str.replace(/[^\d]+/g, '');
	}

	//직접업력란 포커스
	$("#charge_etc").focus();

</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/deposit.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/payment.js'); ?>"></script>

<?php
if ($this->cbconfig->item('use_payment_pg') && element('use_pg', $view)) {
    $this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form4name', $view), $sform);
}
