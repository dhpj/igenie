<style>
@font-face {
  font-family: 'Gmarket Sans'; font-style: normal; font-weight: 700;
  src:local('Gmarket Sans Bold'), local('GmarketSans-Bold'),
  url('http://script.ebay.co.kr/fonts/GmarketSansBold.woff2') format('woff2'),
  url('http://script.ebay.co.kr/fonts/GmarketSansBold.woff') format('woff');
}

@font-face {
  font-family: 'Gmarket Sans'; font-style: normal; font-weight: 500;
  src:local('Gmarket Sans Medium'), local('GmarketSans-Medium'),
  url('http://script.ebay.co.kr/fonts/GmarketSansBold.woff2') format('woff2'),
  url('http://script.ebay.co.kr/fonts/GmarketSansBold.woff') format('woff');
}

@font-face {
  font-family: 'Gmarket Sans'; font-style: normal; font-weight: 300;
  src:local('Gmarket Sans Light'), local('GmarketSans-Light'),
  url('http://script.ebay.co.kr/fonts/GmarketSansBold.woff2') format('woff2'),
  url('http://script.ebay.co.kr/fonts/GmarketSansBold.woff') format('woff');
}

@media (max-width:480px) {
	.sample_wrap{width:100%; letter-spacing: -0.05em;}
	.sample_box{width:80%; height:520px; text-align: center; margin:50px auto; background: url(/images/sample_box_bgm.jpg) no-repeat top center; background-size: contain;}
	.sample_box01{padding-top:90px;}
	.sample_box01 .tst1{font-weight: bold; font-family: 'Gmarket Sans Bold', 'Gmarket Sans'; font-size:20px; color:#404040;}
	.sample_box01 .tst2{font-family: 'Gmarket Sans Medium', 'Gmarket Sans'; font-size:15px; color:#404040; opacity:0.5; padding:0 10%;  word-break: keep-all;}
	.sample_box01 .tst3{font-family: 'Gmarket Sans Medium', 'Gmarket Sans'; font-size: 12px; color:#404040; margin-top: 15px; padding:0 20%; word-break: keep-all;}
	.sample_box02{padding-top:20px; border-top:solid 1px #ddd; width:90%; margin:20px auto; position:relative;}
	.sample_box02 input{border:solid 1px #ddd; height:35px; width:150px; vertical-align:middle;}
	.sample_box02 .btn_send{height:35px; padding:0 10px; vertical-align:middle; background-color: #0d09cc; color:#fff;font-family: 'Gmarket Sans Medium', 'Gmarket Sans'; font-size: 14px;}
	.sample_box02 .tst4{font-family: 'Gmarket Sans Medium', 'Gmarket Sans'; font-size: 12px; color:#ab39d2; margin-top:10px; padding:0 10%; word-break: keep-all;}

}
@media all and (min-width:1200px){
.sample_wrap{width:100%; letter-spacing: -0.05em;}
.sample_box{width:659px; height:520px; text-align: center; margin:100px auto; background: url(/images/sample_box_bg.jpg) no-repeat top center;}
.sample_box01{padding-top:150px;}
.sample_box01 .tst1{font-weight: bold; font-family: 'Gmarket Sans Bold', 'Gmarket Sans'; font-size: 35px; color:#404040;}
.sample_box01 .tst2{font-family: 'Gmarket Sans Medium', 'Gmarket Sans'; font-size:18px; color:#404040; opacity:0.5;}
.sample_box01 .tst3{font-family: 'Gmarket Sans Medium', 'Gmarket Sans'; font-size: 15px; color:#404040; margin-top: 15px;}
.sample_box02{padding-top:90px;}
.sample_box02 input{border:solid 1px #ddd; height:35px;}
.sample_box02 .btn_send{height:35px; padding:0 20px; background-color: #0d09cc; color:#fff;font-family: 'Gmarket Sans Medium', 'Gmarket Sans'; font-size: 14px;}
.sample_box02 .tst4{font-family: 'Gmarket Sans Medium', 'Gmarket Sans'; font-size: 12px; color:#ab39d2; margin-top:10px;}
}
</style>
<div class="sample_wrap">
<div class="sample_box">
	      <div class="sample_box01">
				 <p class="tst1">
					 내맘대로 스마트전단 만들기
				 </p>
				 <p class="tst2">
					 #템플릿도 마음껏 사용하세요!  #완전 무료  #저작권 걱정 없음
				 </p>
				 <p class="tst3">
					 원하는 템플릿을 선택해서 내맘대로 만드는 손쉬운 디자인<br />
					 SNS/문자 홍보, 매장용 인쇄 POP 등 다양한 프로모션으로 매출상승!
				 </p>
				</div>
				<div class="sample_box02">
					<input type="text" name="test_num" id="test_num" class="test_num" placeholder="핸드폰번호 입력">
					<button class="btn_send" type="button" onclick="test_send()">샘플발송신청</button>
					<p class="tst4">
					 * 샘플 테스트 발송은 1개의 전화번호당 1일 1회 발송 가능합니다.
					</p>
	      </div>
 </div>
</div>
<script type="text/javascript">
	//전화번호 입력시 자동 대시(하이픈, "-") 삽입
	$(document).on("keyup", "#test_num", function() {
		$(this).val( $(this).val().replace(/[^0-9]/g, "").replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/,"$1-$2-$3").replace("--", "-") );
	});

	//샘플발송신청
	function test_send(){
		//alert("본사로 문의 바랍니다. ( 1522-7985 )"); return;
		var test_num = $("#test_num").val().trim().replace(/[^0-9]/g,""); //휴대폰번호 숫자만 추출
		//alert("test_num : "+ test_num); return;
		if(test_num == ""){
			alert("휴대폰번호가 입력되지 않았거나 올바른 형식이 아닙니다.");
			$("#test_num").focus();
			return;
		}
		var regExp = /(01[016789])(\d{4}|\d{3})\d{4}$/g;
		if(!regExp.test(test_num)){
			alert("휴대폰번호가 올바른 형식이 아닙니다.");
			$("#test_num").focus();
			return;
		}
		//alert("test_num : "+ test_num +", regExp : "+ regExp); return;
		$.ajax({
			url: "/login/alimtalk_test2",
			type: "POST",
			data: {<?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>",
				   "receive_phn" : $("#test_num").val()
				  },
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				if(json.code == 0){
					alert("샘플 테스트 발송은 1개의 전화번호당 1일 1회 발송 가능합니다.");
				}else{
					alert(json["response_message"]);
					$("#test_num").val("");
				}
			}
		});
	}

	//실시간 계약현황
	$.ajax({
		url: "/homepage/contract_list",
		type: "POST",
		data: {<?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>"},
		success: function (json) {
			var html = "";
			$.each(json, function(key, value){
				var mem_id = value.mem_id; //회원번호
				var mem_username = value.mem_username; //업체명
				var mem_full_care_yn = value.mem_full_care_yn; //풀케어 사용여부
				var mem_full_care = "";
				if(mem_full_care_yn == "Y"){
					mem_full_care = '<span class="standard">스탠다드서비스</span>';
				}else{
					mem_full_care = '<span class="premium">프리미엄서비스</span>';
				}
				mem_userf = mem_username.substr(0,2)
				mem_userl = mem_username.substr(2).replace(/(.)/gi, "*");
				mem_username =mem_userf + mem_userl;

				html +="<li>";
				html +="<span>계약완료</span><span>"+ mem_username +"</span>"+ mem_full_care + "<span class='today_date'>[ <?=cdate('y.m.d')?> ]</span>";
				html +="</li>";
			});
			$("#contract_list").html(html);
		}
	});
</script>
