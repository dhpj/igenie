	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p class="btn_al_cen mg_b20">
                            <button type="button" class="btn_st1" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
		<!-- 3차 메뉴 -->
		<?php
		include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu2.php');
		?>
		<!-- //3차 메뉴 -->
 <div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>발신프로필 등록</h3>
			<button class="btn_tr" onclick="location.href='/dhnbiz/sendprofile/lists'">목록으로</button>
		</div>
		<form class="form-horizontal row-border" id="writeform" name="writeform" method="post"
		  action="/dhnbiz/sendprofile/write" enctype="multipart/form-data">
		<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
		<input type='hidden' name='actions' value='sendprofile_save' />
		<input type='hidden' name='api_code' id='api_code' value='' />
		<div class="white_box" id="form1">
			<div class="input_content_wrap">
				<label class="input_tit">업체명<span class="required">*</span></label>
				<div class="input_content">
					<input type="text" class="form-control input-width-large inline" id="read_username" name="mem_username" value="<?//=$this->member->item("mem_username")?>" Readonly>
					<button type="button" class="btn_myModal" style="margin-left:2px;" onClick="partner_open();">업체검색</button>
					<input type='hidden' name='mem_id' id='mem_id' value='<?//=$this->member->item('mem_id')?>' />
					<input type='hidden' name='mem_username' id='mem_username' value='<?//=$this->member->item("mem_username")?>' />
				</div>
			</div>
			<div class="input_content_wrap">
				<label class="input_tit">플러스친구<span class="required">*</span></label>
				<div class="input_content">
					<input type="text" class="form-control required input-width-large" name="pf_yid" id="pf_yid" placeholder="검색용 아이디를 입력해주세요." onclick="plus_friend();" onChange="plus_friend();">
				</div>
			</div>
			<div class="input_content_wrap">
				<label class="input_tit">사업자등록증<span class="required">*</span></label>
				<div class="input_content">
					<input type="text" id="pf_name"
								   class="textbox form-control input-width-large" readonly="readonly"
								   placeholder="파일이 선택되지 않았습니다."/>
							<label for="biz_license" class="btn btn-default find h34" style="cursor:pointer;">파일찾기</label>
							<input type="file" id="biz_license" name="biz_license" class="upload-hidden" accept="image/jpeg, image/png"
								   onchange="javascript: var a = this.value; var b = a.slice(12); document.getElementById('pf_name').value=b;if($('#biz_license').val()!=''){
									   $('.danger').remove();
									} CheckUploadFileSize(this)">
							<span class="help-block msg"> &nbsp;jpg, png 파일만 업로드 됩니다. 최대 500KB </span>
				</div>
			</div>
			<div class="input_content_wrap">
				<label class="input_tit">카테고리<span class="required">*</span></label>
				<div class="input_content">
					<select name="category" id="category">
							<?foreach($category["data"] as $row ) {
								?>
								<option value="<?echo $row["parentCode"].$row["code"];?>"><?echo $row["name"];?></option>

								<?
							}?>
						</select>
				</div>
			</div>
			<div class="input_content_wrap">
				<label class="input_tit">사업자등록번호<span class="required">*</span></label>
				<div class="input_content">
					<input type="text" class="form-control required input-width-large" name="licenseNumber" id="licenseNumber" onkeypress="return check_digit(event);" placeholder="사업자번호를 입력해 주세요.">
				</div>
			</div>
			<div class="input_content_wrap">
				<label class="input_tit">관리자 휴대폰번호<span class="required">*</span></label>
				<div class="input_content">
					<div class="form-inline" id="phn_div">
							<input type="text" class="form-control required input-width-large" name="phoneNumber" id="phoneNumber" onkeypress="return check_digit(event);" placeholder="관리자 휴대폰번호를 입력해 주세요.">
							<input type="hidden" name="token_code" id="token_code" value="" />
							<input class="btn btn-default h34" style="cursor:pointer;margin-left:2px;" type="button" id="sendbtn" name="numbtn" value="인증번호 전송" onclick="sendMessage()">
						</div>
				</div>
			</div>
			<div class="input_content_wrap" id="profile_key" style="display:none;">
				<label class="input_tit">발신프로필 Key<span class="required">*</span></label>
				<div class="input_content">
					<input type="text" class="form-control input-width-large" name="pf_key" id="pf_key" placeholder="발신프로필 키를 알고있는 경우 입력해주세요.">
				</div>
			</div>
			<div class="input_content_wrap">
				<label class="input_tit">인증번호<span class="required">*</span></label>
				<div class="input_content">
					<input type="text" class="form-control required input-width-large" name="token" id="token" placeholder="인증번호를 입력해 주세요." onkeypress="return check_digit(event);" onchange="noSpaceForm(this);">
				</div>
			</div>
		</div>
		<div class="btn_al_cen">
			<input class="btn_st3" type="button" id="cancel" name="cancel" value="취소" onclick="location.href='/dhnbiz/sendprofile/lists'">
			<!--<input type="submit" onclick="check();" value="등록" class="btn_st3" name="register" id="register">-->
			<input type="button" onclick="check();" value="등록" class="btn_st3" name="register" id="register">
		</div>
	</div>
 </div>
</form>
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
			//alert("2121212");
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
			$(".btn-primary").click();
		}
	});

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
		if($('#pf_yid').val()=='') {
			alert("플러스친구 검색용 아이디를 입력해주세요.");
			$('#pf_yid').focus();
			return false;
		}
		if($('#phoneNumber').val()=='') {
			alert("관리자 휴대폰번호를 입력해주세요.");
			$('#phoneNumber').focus();
			return false;
		}
		//alert("yellowId : "+ yellowId +", phoneNumber : "+ phoneNumber); return;
		if (phoneNumber != "" && yellowId != "") {
			$('#token_code').val('');
			$.ajax({
				url: "/dhnbiz/sendprofile/sendToken",
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
					if (code == 'success' || code == '200') {
						$('#token_code').val(code);
						//$(".content").html("인증번호가 전송되었습니다.");
						//$('#myModal').modal({backdrop: 'static'});
						alert("인증번호가 전송되었습니다.");
						return true;
					} else {
						//$(".content").html(message);
						//$('#myModal').modal({backdrop: 'static'});
						alert(message);
						return false;
					}
				},
				error: function () {
					//$(".content").html("처리되지 않았습니다.");
					//$('#myModal').modal({backdrop: 'static'});
					alert("처리되지 않았습니다.");
					return false;
				}
			});
		} else if (phoneNumber == "" || yellowId == "") {
			//$(".content").html("플러스친구 아이디와 관리자 휴대폰번호를 입력해주세요.");
			//$('#myModal').modal({backdrop: 'static'});
			alert("플러스친구 아이디와 관리자 휴대폰번호를 입력해주세요.");
			return false;
		}
	}

	//등록 버튼 클릭
	function check() {
		if($('#mem_id').val()=='') {
			alert("업체명을 선택해주세요.");
			partner_open(); //업체목록 모달창 열기
			return false;
		}
		if($('#pf_yid').val()=='') {
			alert("플러스친구 검색용 아이디를 입력해주세요.");
			$('#pf_yid').focus();
			return false;
		}
		if($('#biz_license').val()=='') {
			//if($(".danger").val()!='') {
			//    var danger = "<p class='danger' value='danger' style='color:#A74544 ;font-weight: 600;'>사업자 등록증 이미지를 선택해주세요.</p>";
			//    $('.msg').after(danger);
			//}
			alert("사업자 등록증 이미지를 선택해주세요.");
			$('#biz_license').focus();
			return false;
		}
		if($('#licenseNumber').val()=='') {
			alert("사업자등록번호를 입력해주세요.");
			$('#licenseNumber').focus();
			return false;
		}
		if($('#phoneNumber').val()=='') {
			alert("관리자 휴대폰번호를 입력해주세요.");
			$('#phoneNumber').focus();
			return false;
		}
		if($('#token').val()=='') {
			alert("인증번호를 입력해주세요.");
			$('#token').focus();
			return false;
		}
		sendercreate();
	}

	$(window).load(function() {
		var code = '';
		//alert("aaaaaaaaa");
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

	//$('#writeform').submit(function() {
	function sendercreate(){
		var yellowId = $("#pf_yid").val().replace(/ /gi,"");
		var biz_license = $("#biz_license").val().replace(/ /gi,"");
		var phoneNumber = $("#phoneNumber").val().replace(/ /gi,"");
		var category = $("#category").val().replace(/ /gi,"");
		var licenseNumber = $("#licenseNumber").val().replace(/ /gi,"");
		var token = $("#token").val().replace(/ /gi,"");
		var pf_key = "";
		var filename = "";
		if($("#biz_license").val()!="") {
			if($("#biz_license").val().indexOf('\\') > -1) {
				filename = $("#biz_license").val().substring($("#biz_license").val().lastIndexOf('\\')+1);
			} else {
				if($("#biz_license").val().indexOf('/') > -1) {
					filename = $("#biz_license").val().substring($("#biz_license").val().lastIndexOf('/')+1);
				} else {
					filename = $("#biz_license").val();
				}
			}
		}
		var success=false;
		try {
			var form = new FormData();
			form.append("userId", "<?=$admin_sw_id?>");
			form.append("plusFriend", $("#pf_yid").val());
			form.append("phoneNumber", $("#phoneNumber").val());
			form.append("categoryCode", $("#category").val());
			form.append("licenseNumber", $("#licenseNumber").val());
			form.append("token", $("#token").val());
			form.append("filename", filename);
			form.append("file", $("input[name=biz_license]")[0].files[0]);
			form.append("mem_id", $("#mem_id").val());
			form.append("mem_username", $("#mem_username").val());
			$.ajax({
				url: "/dhnbiz/sendprofile/sendercreate",
				type: "POST",
				data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>'
					 ,'yellowId': $("#pf_yid").val()
					 ,'phoneNumber': $("#phoneNumber").val()
					 ,'token':$("#token").val()
					 ,'categoryCode':$("#category").val()
					 ,'mem_id':$("#mem_id").val()
				},
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
						//$(".content").html("발신프로필 등록이 완료 되었습니다.");
						//$('#myModal').modal({backdrop: 'static'});
						alert("발신프로필 등록이 완료 되었습니다.");
						location.href = "/dhnbiz/sendprofile/lists'";
						return true;
					} else {
						//$(".content").html(message);
						//$('#myModal').modal({backdrop: 'static'});
						alert(message);
						return false;
					}
				},
				error: function () {
					//$(".content").html("처리되지 않았습니다.");
					//$('#myModal').modal({backdrop: 'static'});
					alert("처리되지 않았습니다.");
					return false;
				}
			});
		}catch(e){ alert(e.message); }
		return false;
	}
	//});


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
<div id="partner_modal" class="dh_modal">
  <!-- Modal content -->
  <div class="modal-content"> <span id="partner_close" class="dh_close">&times;</span>
    <p class="modal-tit"> 업체 목록 </p>
    <div class="search_input">
      <label for="partner_userid">소속</label>
      &nbsp;
      <select name="partner_userid" id="partner_userid" data-live-search="true" onChange="partner_search()';">
        <option value="ALL">-ALL-</option>
        <? foreach($users as $r) { ?>
        <option value="<?=$r->mem_id?>"><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
        <? } ?>
      </select>
      &nbsp;
      <select class="select2 input-width-medium" id="searchType">
        <option value="username">업체명</option>
        <option value="phone">전화번호</option>
        <option value="nickname">담당자 이름</option>
      </select>
      &nbsp;
      <input type="text" class="" id="searchStr" name="searchStr" placeholder="검색어 입력" value="">
      &nbsp;
      <input type="button" class="btn md" style="cursor:pointer;" id="check" value="조회" onclick="partner_search();">
    </div>
    <dl class="cus_search_result">
      <dt class="cus_fixed">
        <span>소속</span>
      	<span>업체명 (담당자)</span>
      	<span>계정선택</span>
      </dt>
      <dd id="id_partner_list"><?//업체목록 모달창 데이타 조회 영역?>
      </dd>
    </dl>
    <!--//pagination-->
  </div>
</div>
<script>
	//업체목록 모달창 페이지
	var partner_page = 1; //페이지
	var partner_total = 0; //전체수
	var partner_modal = document.getElementById("partner_modal"); //모달차 이름

	//업체목록 모달창 검색내용 엔터키
	$("#searchStr").keydown(function(key) {
		if (key.keyCode == 13) {
			partner_search(); //업체목록 모달창 검색
		}
	});

	//업체목록 모달창 검색
	function partner_search(){
		partner_remove(); //업체목록 모달창 초기화
		partner_list(); //업체목록 모달창 데이타 조회
	}

	//업체목록 모달창 초기화
	function partner_remove(){
		partner_page = 1; //페이지
		partner_total = 0; //전체수
		$("#id_partner_list").html(""); //초기화
	}

	//업체목록 모달창 스크롤시
	$("#id_partner_list").scroll(function(){
		//alert("id_partner_list"); return;
		var dh = $("#id_partner_list")[0].scrollHeight;
		var dch = $("#id_partner_list")[0].clientHeight;
		var dct = $("#id_partner_list").scrollTop();
		//alert("스크롤 : " + dh + "=" + dch +  " + " + dct); return;
		if(dh == (dch+dct)) {
			var rowcnt = $(".btn_st_sm").length;
			//alert("partner_total : " + partner_total + " / rowcnt : " + rowcnt); return;
			if(rowcnt < partner_total) {
				partner_list(); //업체목록 모달창 데이타 조회
			}
		}
	});

	//업체목록 모달창 데이타 조회
	function partner_list(){
		var uid = $("#partner_userid").val(); //소속
		var search_type = $("#searchType").val(); //검색타입
		var search_for = $("#searchStr").val(); //검색내용
		//alert("uid : "+ uid +", search_type : "+ search_type +", search_for : "+ search_for); return;
		//alert("partner_page : "+ partner_page +", searchType : "+ searchType +", searchStr : "+ searchStr);
		$.ajax({
			url: "/biz/sendphnmng/partner_list",
			type: "POST",
			data: {"perpage" : "10", "page" : partner_page, "uid" : uid, "search_type" : search_type, "search_for" : search_for, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				//partner_page = json.page;
				partner_page++;
				partner_total = json.total;
				//alert("partner_page : "+ partner_page +", partner_total : "+ partner_total);
				var html = "";
				$.each(json.list, function(key, value){
					var mem_id = value.mem_id; //업체번호
					var mem_userid = value.mem_userid; //업체 아이디
					var mem_nickname = value.mem_nickname; //담당자
					var mrg_recommend_mem_username = value.mrg_recommend_mem_username; //소속
					var mem_username = value.mem_username; //업체명
					html += '<ul>';
					html += '  <li>'+ mrg_recommend_mem_username +'</li>'; //소속
					html += '  <li id="td_mem_username'+ mem_id +'">'+ mem_username +' ('+ mem_nickname +')</li>'; //업체명 (담당자)
					html += '  <li><button class=\'btn_st_sm\' onClick=\'partner_choice("'+ mem_id +'", "'+ mem_username +'");\'>선택</button></li>'; //선택
					html += '</ul>';
				});
				$("#id_partner_list").append(html);
			}
		});
	}

	//업체목록 모달창 데이타 선택
	function partner_choice(mem_id, mem_username){
		var read_username = $("#td_mem_username"+ mem_id).html(); //업체명
		//alert(mem_id +", "+ mem_username); return;
		$("#mem_id").val(mem_id); //업체번호
		$("#mem_username").val(mem_username); //업체명
		$("#read_username").val(read_username); //업체명 (담당자)
		partner_modal.style.display = "none"; //업체목록 모달창 닫기
	}

	//업체목록 모달창 열기
	function partner_open(){
		partner_modal.style.display = "block";

		//업체목록 모달창 초기화
		partner_page = 1; //페이지
		partner_total = 0; //전체수
		$("#id_partner_list").html(""); //초기화

		//업체목록 모달창 데이타 조회
		partner_search();

		var span = document.getElementById("partner_close");
		span.onclick = function() {
			partner_modal.style.display = "none"; //업체목록 모달창 닫기
		}
		window.onclick = function(event) {
			if (event.target == partner_modal) {
				partner_modal.style.display = "none"; //업체목록 모달창 닫기
			}
		}
	}
</script>
