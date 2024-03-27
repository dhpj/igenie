<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" id="modal">
		<div class="modal-content">
			<br/>
			<div class="modal-body">
				<div class="content">
				</div>
				<div>
					<p align="right">
						<br/><br/>
						<button type="button" class="btn btn-primary" data-dismiss="modal">확인</button>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 타이틀 영역 -->
<div class="tit_wrap">
	발신프로필 관리
</div>
<!-- 타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<!--<h3>발신프로필 등록</h3>-->
			<h3>등록된 프로필 가져오기</h3>
			<button class="btn_tr" onclick="location.href='/dhnbiz/sendprofile/lists'">뒤로가기</button>
		</div>
			<form class="form-horizontal row-border" id="writeform" name="writeform" method="post"
			  action="/biz/senddhnprofile/take" enctype="multipart/form-data">
				<div class="white_box">
					<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
					<input type='hidden' name='api_code' id='api_code' value='' />
					<input type='hidden' name='api_data' id='api_data' value='' />
					<table class="tpl_ver_form" width="100%" id="form1">
						<colgroup>
							<col width="200">
							<col width="*">
						</colgroup>
						<tr>
							<th>프로필 업체<span class="required">*</span></th>
							<td style="text-align:left;">
								<select id="sc">
									<option value="username">업체명</option>
									<option value="biz_reg_no">사업자번호</option>
								</select>
								<input type="text" id="sv" value="" placeholder="검색내용" onKeypress="if(event.keyCode==13){ search(); }">
								<button type="button" class="btn_st_search" onClick="search();">검색</button>
								<select name="mem_id" id="mem_id" style="margin-left:10px;display:none;">
									<option value="">선택하세요.</option>
								</select>
							</td>
						</tr>
						<tr id="profile_key" >
							<th>발신프로필Key<span class="required">*</span></th>
							<td style="text-align:left;">
								<input type="text" class="form-control input-width-large" name="pf_key"
									   id="pf_key" placeholder="발신프로필 키를 알고있는 경우 입력해주세요." style="width:400px !important;">
							</td>
						</tr>
					</table>
				</div>
				<div class="btn_al_cen">
					<input type="button" name="cancel" id="cancel" value="목록" class="btn_st3" onclick="location.href='/dhnbiz/sendprofile/lists'">
					<input type="button" name="register" id="register" value="등록" class="btn_st3" onClick="saved();">
				</div>
		</form>
	</div>
	<!--<div class="footer">
		<div class="cs_info">
			<div class="cs_call">
				<span>1522-7985</span>
			</div>
			<div class="cs_support">
				<a href="#"><span class="icon_cs request">1:1문의</span></a>
				<a target="_blank" href="http://367.co.kr/"><span class="icon_cs remote">원격지원</span></a>
			</div>
		</div>

		<div class="info">
			<ul>
				<li>사업자등록번호 : 364-88-00974</li>
				<li>통신판매업 : 신고번호 2018-창원의창-0272호</li>
				<li>대표이사 : 송종근 </li>
				<li class="paragraph">주소 : 경상남도 창원시 의창구 차룡로 48번길 54(팔용동) 경남창원산학융합원 기업연구관 302호</li>
				<li>대표전화 : 1522-79854</li>
				<li>팩스 : 0505-299-0001</li>
				<li>개인정보처리방침</li>
				<li>원격지원</li>
			</ul>
		</div>
		<div class="copyright">
			Copyright © DHN. All rights reserved.
		</div>
	</div>-->
</div>
<style>
	.textbox {
		float: left;
		cursor: default !important;
		background-color: white !important;
	}

	input[type="file"] {
		position: absolute;
		clip: rect(0, 0, 0, 0);
	}

	.file label {
		margin-left: 3px;
	}

	.find {
		width: 100px;
	}

	.input-width-large {
		width: 250px !important;
	}

	#writeform label.error {
		color: #A74544;
	}
</style>
<script type="text/javascript">
	$(document).ready(function () {
		$("#writeform").validate({
			rules: {
				biz_license: {required: true},
				pf_yid: {required: true},
				token: {required: true},
				phoneNumber: {required: true}
			},
			messages: {
				biz_license: {required: ""},
				pf_yid: {required: "검색용 아이디를 입력해주세요."},
				token: {required: "인증번호를 입력해주세요."},
				phoneNumber: {required: "관리자 휴대폰번호를 입력해주세요."}
			},
			errorPlacement: function (error, element) {
				if (element.attr("name") == "phoneNumber") {
					error.insertAfter($("#phn_div"));
				}
				else {
					error.insertAfter(element);
				}
			}
		});
	});
</script>
<script type="text/javascript">
	$("#nav li.nav50").addClass("current open");

	$(document).unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			//$(".btn-primary").click();
		}
	});

	//업체명 검색
	function search(){
		var sc = $("#sc").val().trim(); //검색타입
		var sv = $("#sv").val().trim(); //검색내용
		//alert("sv : "+ sv); return;
		if(sv == ""){
			alert("검색 내용을 입력하세요.");
			$("#sv").focus();
			return false;
		}
		$('#mem_id').show();
		$('#mem_id').empty();
		$('#mem_id').append($("<option value=''>- 업체명 선택 -</option>"));
		//업체명 검색
		$.ajax({
			url: "/biz/manager/pendingbank/search_mem_id",
			type: "POST",
			data: {
				  "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
				, "sc" : sc //검색타입
				, "sv" : sv //검색내용
			},
			success: function (json) {
				//alert("json.code : "+ json.code +", json.msg : "+ json.msg);
				$.each(json, function(key, value){
					var mem_id = value.mem_id; //회원번호
					var mem_userid = value.mem_userid; //회원ID
					var mem_username = value.mem_username; //업체명
					var mem_nickname = value.mem_nickname; //담당자명
					$('#mem_id').append($("<option value='"+ mem_id +"'>"+ mem_username +" ("+ mem_nickname +") ["+ mem_userid +"]</option>"));
				});
			}
		});
	}

	//사업자 등록증 크기 제한
	function CheckUploadFileSize(objFile) {
		var maxSize = 500 * 1024;
		var fileSize = objFile.files[0].size;
		if (fileSize > maxSize) {
			$(".content").html("최대 500KB까지만 가능합니다.");
			$('#myModal').modal({backdrop: 'static'});
			$("#biz_license").val("");
			$("#pf_name").val("");
		}
	}

	//인증번호 전송
	function sendMessage() {
		var yellowId = $("#pf_yid").val();
		var phoneNumber = $("#phoneNumber").val();
		if (phoneNumber != "" && yellowId != "") {
			$('#token_code').val('');
			$.ajax({
				url: "/biz/senddhnprofile/sendToken",
				type: "POST",
				data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>', 'pf_yid': yellowId, 'phoneNumber': phoneNumber},
				beforeSend:function(){
					$('#overlay').fadeIn();
				},
				complete:function(){
					$('#overlay').fadeOut();
				},
				success: function (json) {
					var message = json['message'];
					var code = json['code'];
					if (code == '200') {
						$('#token_code').val(code);
						$(".content").html("인증번호가 전송되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
					} else {
						$(".content").html(message );
						$('#myModal').modal({backdrop: 'static'});
					}
				},
				error: function () {
					$(".content").html("처리되지 않았습니다.");
					$('#myModal').modal({backdrop: 'static'});
				}
			});
		} else if (phoneNumber == "" || yellowId == "") {
			$(".content").html("플러스친구 아이디와 관리자 휴대폰번호를 입력해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		}
	}

	function check() {
		if($('#biz_license').val()=='') {
			if($(".danger").val()!='') {
				var danger = "<p class='danger' value='danger' style='color:#A74544 ;font-weight: 600;'>사업자 등록증 이미지를 선택해주세요.</p>";
				$('.msg').after(danger);
			}
		}
	}

	$(window).load(function() {
		var code = '';
		//등록, 삭제 JSON 응답 메세지
		var message = '';
		if (message != "") {
			var b = $(".content").html(message);
			$('#myModal').modal({backdrop: 'static'});
			$(".modal-dialog").css("word-wrap", "break-word");
			var pf_ynm = '';
			if (pf_ynm != "") {
				(function (i, s, o, g, r, a, m) {
					i['GoogleAnalyticsObject'] = r;
					i[r] = i[r] || function () {
								(i[r].q = i[r].q || []).push(arguments)
							}, i[r].l = 1 * new Date();
					a = s.createElement(o),
							m = s.getElementsByTagName(o)[0];
					a.async = 1;
					a.src = g;
					m.parentNode.insertBefore(a, m)
				})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
				ga('create', 'UA-29375674-2', 'auto');
				ga('send', 'event', '발신프로필 등록', '<?=$this->member->item('mem_username')?>', '', 1);
			}
		}
	});

	//저장
	function saved(){
		var senderKey = $("#pf_key").val();
		var mem_id = $("#mem_id").val();
		//alert("senderKey : "+ senderKey +", mem_id : "+ mem_id); return;
		if(mem_id == ""){
			alert("프로필 업체를 선택하세요.");
			$("#mem_id").focus();
			return false;
		}
		if(senderKey == ""){
			alert("발신프로필Key를 입력하세요.");
			$("#pf_key").focus();
			return false;
		}
		var pf_key = "";
		var filename = "";
		var success=false;
		$.ajax({
			url: "/biz/senddhnprofile/refresh",
			type: "POST",
			data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>',
				   'mem_id':mem_id,
				   'senderKey': senderKey},
			beforeSend:function(){
				$('#overlay').fadeIn();
			},
			complete:function(){
				$('#overlay').fadeOut();
			},
			success: function (json) {
				var message = json['message'];
				var code = json['code'];
				if(code =='200'){
					alert(message);
					location.href='/dhnbiz/sendprofile/lists';
				}else{
					alert(message);
				}
				//if (code == '200') {
				//	$(".content").html(message);
				//	$('#myModal').modal({backdrop: 'static'});
				//} else {
				//	$(".content").html(message );
				//	$('#myModal').modal({backdrop: 'static'});
				//}
				// alert(message);
			},
			error: function () {
				//$(".content").html("처리되지 않았습니다.");
				//$('#myModal').modal({backdrop: 'static'});
				alert("처리되지 않았습니다.");
			}
		});

		if (senderKey !="" && mem_id != "") {
			$('#overlay').fadeOut();
			return true;
		}
		return false;
	}

	//숫자 여부 확인
	function check_digit(evt){
		var code = evt.which?evt.which:event.keyCode;
		if(code < 48 || code > 57){
			return false;
		}
	}

	//공백 제거
	function noSpaceForm(obj) {
		var str_space = /\s/;  //공백체크
		if(str_space.exec(obj.value)) { //공백체크
			obj.focus();
			obj.value = obj.value.replace(/(\s*)/g,''); //공백제거
			return false;
		}
	}

	function plus_friend() {
		var pf_yid = $("#pf_yid").val();
		if(pf_yid.substr(0,1)!="@") {
			var default_val = "@"+pf_yid;
			$("#pf_yid").val(default_val);
		}
	}
</script>