<?
	$home_url = "/smart/view/". $code; //스마트전단 홈
	if($ScreenShotYn != "") $home_url .= "?ScreenShotYn=". $ScreenShotYn;
    if($this->input->get("home") != "") $home_url .= "&home=". $this->input->get("home");
	$pram = "code=". $code; //파라메타
    if($this->input->get("home") != "") $pram .= "&home=". $this->input->get("home");
	if($ScreenShotYn != "") $pram .= "&ScreenShotYn=". $ScreenShotYn;
	$back_url = "/smart/cart?". $pram; //이전페이지
	if(!empty($_SESSION['user_cart'])){
		$total_amt = $this->Cart_model->cartamt(); //총주문금액
	}else{
		$total_amt = 0;
	}
    //익산원협 예외처리
    $iksan_yn = "N";
    if($_SESSION['shop_mem_id']=="1249"||$_SESSION['shop_mem_id']=="1896"||$_SESSION['shop_mem_id']=="732"){
        $iksan_yn = "Y";
    }

    $total_amt = str_replace(',', '',$total_amt);

	//배달비 세팅 2021-03-25
	$delivery_amt = ($os->delivery_amt != "") ? $os->delivery_amt : 3000; //배달비
	$free_delivery_amt = ($os->free_delivery_amt != "") ? $os->free_delivery_amt : 50000; //무료배달 최소금액
	if($total_amt >= $free_delivery_amt){
		$delivery_amt = 0;
	}
	$total_amt = number_format($this->funn->numbers_only($total_amt) + $delivery_amt);

	/*
	$order = array();
	$order['receiver'] = "테스트"; //고객명
	$order['phnno'] = "01012345678"; //휴대폰번호
	$order['postcode'] = "51391"; //우편번호
	$order['addr1']= "경상남도 창원시 의창구 차룡로 48번길 54"; //주소
	$order['addr2']= "(팔용동) 기업연구관 302호"; //상세주소
	$order['charge_type'] = "2"; //결재수단 (1:현장결제(카드), 2:현장결제(현금))
	$order['point_save_no'] = ""; //마트적립번호
	$order['user_req'] = "배달전 미리 연락 주세요."; //고객 요청사항

	//배달정보 저장하기 사용시 쿠키생성
	$deliv_save = "Y"; //배달정보 저장하기
	$coo_time = 60 * 60 * 24 * 30; //쿠키값 설정 30일(24시간 * 30)
	if($deliv_save == "Y"){
		setcookie("cookie_deliv_save", $deliv_save, time() + $coo_time, "/"); //배달정보 저장하기 => 쿠키저장
		setcookie("cookie_deliv_receiver", $this->funn->encrypt($order['receiver']), time() + $coo_time, "/"); //고객명 => 쿠키저장
		setcookie("cookie_deliv_phnno", $this->funn->encrypt($order['phnno']), time() + $coo_time, "/"); //휴대폰번호 => 쿠키저장
		setcookie("cookie_deliv_postcode", $this->funn->encrypt($order['postcode']), time() + $coo_time, "/"); //우편번호 => 쿠키저장
		setcookie("cookie_deliv_addr1", $this->funn->encrypt($order['addr1']), time() + $coo_time, "/"); //주소 => 쿠키저장
		setcookie("cookie_deliv_addr2", $this->funn->encrypt($order['addr2']), time() + $coo_time, "/"); //상세주소 => 쿠키저장
		setcookie("cookie_deliv_charge_type", $order['charge_type'], time() + $coo_time, "/"); //결제방법 => 쿠키저장
		setcookie("cookie_deliv_point_save_no", $this->funn->encrypt($order['point_save_no']), time() + $coo_time, "/"); //마트적립번호 => 쿠키저장
		setcookie("cookie_deliv_user_req", $this->funn->encrypt($order['user_req']), time() + $coo_time, "/"); //요청사항 => 쿠키저장
		//echo "쿠키저장<br>";
	}else{
		setcookie("cookie_deliv_save", "", time() - $coo_time, "/"); //배달정보 저장하기 => 쿠키제거
		setcookie("cookie_deliv_receiver", "", time() - $coo_time, "/"); //고객명 => 쿠키제거
		setcookie("cookie_deliv_phnno", "", time() - $coo_time, "/"); //휴대폰번호 => 쿠키제거
		setcookie("cookie_deliv_postcode", "", time() - $coo_time, "/"); //우편번호 => 쿠키제거
		setcookie("cookie_deliv_addr1", "", time() - $coo_time, "/"); //주소 => 쿠키제거
		setcookie("cookie_deliv_addr2", "", time() - $coo_time, "/"); //상세주소 => 쿠키제거
		setcookie("cookie_deliv_charge_type", "", time() - $coo_time, "/"); //결제방법 => 쿠키제거
		setcookie("cookie_deliv_point_save_no", "", time() - $coo_time, "/"); //마트적립번호 => 쿠키제거
		setcookie("cookie_deliv_user_req", "", time() - $coo_time, "/"); //요청사항 => 쿠키제거
		//echo "쿠키제거<br>";
	}

	echo "cookie_deliv_save : ". $_COOKIE["cookie_deliv_save"] ."<br>";
	echo "cookie_deliv_receiver : ". $this->funn->decrypt($_COOKIE["cookie_deliv_receiver"]) ."<br>";
	echo "cookie_deliv_phnno : ". $this->funn->decrypt($_COOKIE["cookie_deliv_phnno"]) ."<br>";
	echo "cookie_deliv_addr1 : ". $this->funn->decrypt($_COOKIE["cookie_deliv_addr1"]) ."<br>";
	echo "cookie_deliv_addr2 : ". $this->funn->decrypt($_COOKIE["cookie_deliv_addr2"]) ."<br>";
	*/
?>
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<div class="wrap_smart">
  <div class="smart_header fix_none">
    <span class="logo"><a href="<?=$home_url?>"><img src="/dhn/images/logo_v2.png" alt="<?=config_item('site_name')?>"></a></span>
    <span class="header_txt"><?=config_item('site_full_name')?></span>
  </div>
  <div class="smart_menu" style="margin-top:0;">
    <a href="<?=$back_url?>"><i class="material-icons">arrow_back</i></a>
    <span class="smart_title">주문하기</span>
  </div>
  <div class="smart_order">
    <dl class="order_info">
      <dt>고객명</dt>
      <dd>
        <input type="text" id="receiver" style="width: 100%" placeholder="고객명" value='<?=$this->funn->decrypt($_COOKIE["cookie_deliv_receiver"])?>' maxlength="20">
      </dd>
    </dl>
    <dl class="order_info">
      <dt>휴대폰번호</dt>
      <dd>
        <input type="tel" id="phnno" style="width: 100%" placeholder="휴대폰번호" value='<?=$this->funn->format_phone($this->funn->decrypt($_COOKIE["cookie_deliv_phnno"]),"-")?>' maxlength="20" onKeyup="phone_chk(this);">
      </dd>
    </dl>
    <dl class="order_info" <?=($iksan_yn=="Y")? "style='display:none;'" : "" ?>>
      <dt>배달주소</dt>
      <dd class="post_wrap">
        <input type="text" id="postcode" placeholder="우편번호" style="width:100px;cursor:pointer;" onclick="execDaumPostcode();" value='<?=$this->funn->decrypt($_COOKIE["cookie_deliv_postcode"])?>' maxlength="6">
        <input type="button" style="cursor:pointer;" onclick="execDaumPostcode();" value="우편번호 찾기"><br>
        <input type="text" id="address" placeholder="주소" class="mg_t5" style="width:100%;cursor:pointer;" onclick="execDaumPostcode();" value='<?=$this->funn->decrypt($_COOKIE["cookie_deliv_addr1"])?>' maxlength="128">
        <input type="text" id="detailAddress" placeholder="상세주소" class="mg_t5" style="width: 100%" value='<?=$this->funn->decrypt($_COOKIE["cookie_deliv_addr2"])?>' maxlength="128">
        <!-- iOS에서는 position:fixed 버그가 있음, 적용하는 사이트에 맞게 position:absolute 등을 이용하여 top,left값 조정 필요 -->
        <div id="layer" style="display:none;position:fixed;overflow:hidden;z-index:1;-webkit-overflow-scrolling:touch;">
          <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1;width:20px;" onclick="closeDaumPostcode()" alt="닫기 버튼">
        </div>
      </dd>
    </dl>
    <dl class="order_info">
      <dt>총결제금액</dt>
      <dd><div class="good_price"><?=$total_amt?><span>원</span></div></dd>
    </dl>
    <dl class="order_info">
      <dt>결제방법</dt>
      <dd>
        <div class="checks">
          <input type="radio" name="charge_type" id="charge_type1" value="1"<?=($_COOKIE["cookie_deliv_charge_type"] == "" OR $_COOKIE["cookie_deliv_charge_type"] == "1") ? " checked" : ""?>> <label for="charge_type1">현장결제(카드)</label>
          <input type="radio" class="mg_l10" name="charge_type" id="charge_type2" value="2"<?=($_COOKIE["cookie_deliv_charge_type"] == "2") ? " checked" : ""?>> <label for="charge_type2">현장결제(현금)</label>
					<?if($shop->mem_is_pay=='Y'){?>
		          <p>
		            <input type="radio" class="" name="charge_type" id="charge_type3" value="3"<?=($_COOKIE["cookie_deliv_charge_type"] == "3") ? " checked" : ""?>> <label for="charge_type3">현장결제(지역화폐)</label>
					<?}
						if($shop->mem_is_account=='Y'){?>
								<input type="radio" class="mg_l10" name="charge_type" id="charge_type4" value="4"<?=($_COOKIE["cookie_deliv_charge_type"] == "4") ? " checked" : ""?>> <label for="charge_type4">계좌이체</label>
						</p>
					<?}?>
        </div>
				<div class="bank_info" id="account_info" style="display:<?=($_COOKIE["cookie_deliv_charge_type"] == "4") ? "" : "none"?>;">
					<p><label>입금계좌</label></p>
					<p><?=$shop->account_info?></p><!-- 계좌이체 선택시 노출 -->
				</div>
      </dd>
    </dl>
    <dl class="order_info">
      <dt>추가주문 <br />및 요청사항</dt>
      <dd>
        <textarea id="user_req" style="width:100%;height:60px;padding:5px;" placeholder="요청사항"></textarea>
      </dd>
    </dl>
    <div class="mg_t15">
      <input type="checkbox" id="deliv_save"<?=($_COOKIE["cookie_deliv_save"] == "Y"||$iksan_yn=="Y") ? " checked" : ""?>><label for="deliv_save">배달정보 저장하기</label>
    </div>
    <div class="policy_agree mg_t15">
      <input type="checkbox" id="terms" <?=($iksan_yn=="Y")? "checked" : "" ?>><label for="terms">개인정보 처리방침에 동의합니다.</label>
      <a class="policy_all" href="javascript:privacy();">전문보기</a>
    </div>
    <button class="btn_order" onclick="order();">주문하기</button>
  </div>
  <!-- <div class="smart_footer">
     <p>Copyright © DHN Corp. All rights reserved.</p>
  </div> -->
  <? include_once($_SERVER['DOCUMENT_ROOT'].'/views/smart/bootstrap/_inc_smart_footer.php'); ?>
</div>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
	// 우편번호 찾기 화면을 넣을 element
	var element_layer = document.getElementById('layer');

	function closeDaumPostcode() {
		// iframe을 넣은 element를 안보이게 한다.
		element_layer.style.display = 'none';
	}

	//우편번호 찾기
	function execDaumPostcode() {
		new daum.Postcode({
			oncomplete: function(data) {
				// 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

				// 각 주소의 노출 규칙에 따라 주소를 조합한다.
				// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
				var addr = ''; // 주소 변수
				var extraAddr = ''; // 참고항목 변수
				var chkAddr = ''; // 참고항목 변수

				//사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
				if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
					addr = data.roadAddress;
				} else { // 사용자가 지번 주소를 선택했을 경우(J)
					addr = data.jibunAddress;
				}

				// 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
				if(data.userSelectedType === 'R'){
					// 법정동명이 있을 경우 추가한다. (법정리는 제외)
					// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
					if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
						//extraAddr += data.bname;
					}
					// 건물명이 있고, 공동주택일 경우 추가한다.
					if(data.buildingName !== '' && data.apartment === 'Y'){
						//extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
						document.getElementById("detailAddress").value = data.buildingName +" ";
					}
					// 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
					if(extraAddr !== ''){
						//extraAddr = ' (' + extraAddr + ')';
					}
				}

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				document.getElementById("postcode").value = data.zonecode;
				document.getElementById("address").value = addr;
				// 커서를 상세주소 필드로 이동한다.
				document.getElementById("detailAddress").focus();

				// iframe을 넣은 element를 안보이게 한다.
				// (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
				element_layer.style.display = 'none';
			},
			width : '100%',
			height : '100%',
			maxSuggestItems : 5
		}).embed(element_layer);

		// iframe을 넣은 element를 보이게 한다.
		element_layer.style.display = 'block';

		// iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
		initLayerPosition();
	}

	// 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
	// resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
	// 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
	function initLayerPosition(){
		var width = 100; //우편번호서비스가 들어갈 element의 width
		var height = 400; //우편번호서비스가 들어갈 element의 height
		var borderWidth = 5; //샘플에서 사용하는 border의 두께

		// 위에서 선언한 값들을 실제 element에 넣는다.
		element_layer.style.width = width + '%';
		element_layer.style.height = height + 'px';
		element_layer.style.border = borderWidth + 'px solid';
		// 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
		element_layer.style.left = 0 + 'px';
		element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
	}

	//전문보기
	function privacy(){
		var url = "/smart/privacy?code=<?=$code?>";
		var ScreenShotYn = "<?=$ScreenShotYn?>";
		if(ScreenShotYn != "") url += "&ScreenShotYn="+ ScreenShotYn;
		location.href = url;
	}
	$(function(){
		$("input:radio[name=charge_type]").click(function(){
			var type_val = $(this).val();
			if(type_val == 4){
				$("#account_info").css("display", "");
			}else{
				$("#account_info").css("display", "none");
			}
		});
	});

	//주문하기
	function order(){
        $.ajax({
            url: "/smart/check_purchase_datetime",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
              , "psd_id" : "<?=$screen_data->psd_id?>"
            },
            success: function (json){
                if (json.code == "0"){
                    var receiver = $.trim($("#receiver").val()); //주문자명
            		var phnno = $.trim($("#phnno").val().replace(/-/gi, '')); //휴대폰번호
            		var postcode = $("#postcode").val(); //우편번호
            		var addr1 = $("#address").val(); //주소
            		var addr2 = $.trim($("#detailAddress").val()); //상세주소
            		var charge_type = $(':radio[name="charge_type"]:checked').val(); //결재수단 (1:현장결제(카드), 2:현장결제(현금), 3:현장결제(지역화폐), 4:계좌이체)
            		var user_req = $.trim($("#user_req").val()); //요청사항
            		var terms = $("#terms").prop('checked'); //개인정보 처리방침에 동의
            		var deliv_save = $("#deliv_save").prop('checked'); //배달정보 저장하기
            		//alert("deliv_save : "+ deliv_save); return;
            		//alert("charge_type : "+ charge_type); return;
            		if(!receiver) {
            			showSnackbar('고객명을 입력해 주세요.', 1500);
            			$("#receiver").focus();
            			return;
            		}
            		if(!phnno) {
            			showSnackbar('휴대폰 번호를 입력해 주세요.', 1500);
            			$("#phnno").focus();
            			return;
            		}
            		if(phnno.length < 6) {
            			showSnackbar('올바른 휴대폰 번호가 아닙니다.', 1500);
            			$("#phnno").focus();
            			return;
            		}
                    <? if($iksan_yn!="Y"){//익산원협 예외처리 ?>
            		if(!postcode && !addr1) {
            			showSnackbar('주소를 검색해 주세요.', 1500);
            			//$("#address").focus();
            			execDaumPostcode();
            			return;
            		}
            		if(!addr2) {
            			showSnackbar('상세주소를 입력해 주세요.', 1500);
            			$("#detailAddress").focus();
            			return;
            		}
                    <? } ?>
            		if(charge_type == "") {
            			showSnackbar('결제방법을 선택하세요.', 1500);
            			return;
            		}
            		if(!terms) {
            			showSnackbar('개인정보 처리방침에 동의하셔야 합니다', 1500);
            			return;
            		}
            		if(deliv_save){
            			deliv_save = "Y";
            		}else{
            			deliv_save = "N";
            		}
            		//alert("주문 OK"); return;
            		//if(confirm("주문 하시겠습니까?"))
            		{
            			$.ajax({
            				url: "/smart/ordercomplete",
            				type: "POST",
            				data: {
            					"receiver" : receiver, //주문자명
            					"phnno" : phnno, //휴대폰번호
            					"postcode" : postcode, //우편번호
            					"addr1" : addr1, //주소
            					"addr2" : addr2, //상세주소
            					"charge_type" : charge_type, //결재수단 (1:현장결제(카드), 2:현장결제(현금), 3:현장결제(지역화폐), 4:계좌이체)
            					"deliv_amt" : "<?=$delivery_amt?>", //배달비
            					"point_save_no" : "", //마트적립번호
            					"user_req" : user_req, //요청사항
            					"deliv_save" : deliv_save, //배달정보 저장하기
            					"code" : "<?=$code?>",
            					"ScreenShotYn" : "<?=$ScreenShotYn?>",
                                "home" : "<?=$this->input->get("home")?>",
            					"<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
            				},
            				success: function (json){
            					if(json.code == "0") {
            						showSnackbar("주문이 완료 되었습니다.", 1500);
            						setTimeout(function (){
            							location.href = "/smart/orderview?oid="+ json.oid +"&phn="+ phnno +"<?=($pram != "") ? "&". $pram : ""?>"; //주문완료 페이지 이동
            						}, 1500);
            					}else if(json.code == "1"){
            						alert("처리 중 오류가 발생 했습니다.\n"+ json.message);
            						location.href = "<?=$home_url?>"; //스마트전단 홈 이동
            					}else{
            						showSnackbar("처리 중 오류가 발생 했습니다.<br>"+ json.message, 2000);
            					}
            	 			}
            			});
            		}
                }else if(json.code == "1") {
                    showSnackbar("구매할 수 없는 시간입니다.", 1500);
                }
            }
        });

	}
</script>
