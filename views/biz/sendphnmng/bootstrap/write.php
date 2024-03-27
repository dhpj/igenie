
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="modal">
    <div class="modal-content">
      <div class="modal-body">
        <div class="content"> </div>
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
<? if($phncnt <= 9) { ?>
<div id="mArticle" class="sendphnmng_wrap">
  <div class="form_section">
    <div class="inner_tit">
      <h3>발신번호 등록</h3>
      <button class="btn_tr" onclick="location.href='/biz/sendphnmng'">목록으로</button>
    </div>
    <div class="white_box">
      <div class="notice_wrap">
        <p>- 사전에 등록한 발신번호로만 문자메시지 전송이 가능합니다.</p>
        <p>- 발신번호는 전기통신사업법 제84조 2에 의거, 발신번호 사전등록제에 따라 본인인증 과정을 거쳐야합니다.</p>
        <p>- 타인 명의의 발신번호 사용 시 불이익이 발생할 수 있습니다.</p>
        <p>- 휴대폰 인증번호 / ARS 인증 전화를 수신할 수 없는 대표번호, 특수번호인 경우 ‘통신서비스 이용증명원'을 제출하여야 하며 가입 통신사에서 발급받을 수 있습니다.</p>
        <p>- 서류 인증 요청 시, 영업일 기준 2일 이내에 심사 후 처리해드립니다.</p>
      </div>
      <div class="input_content_wrap">
        <label class="input_tit">업체명<span class="required">*</span></label>
        <div class="input_content checks">
          <input type="text" class="form-control input-width-large inline" id="mem_username" name="mem_username" value="<?=$this->member->item("mem_username")?>" Readonly>
		  <button type="button" class="btn_myModal" onClick="partner_open();">업체변경</button>
		  <input type="hidden" id="mem_id" name="mem_id" value="<?=$this->member->item("mem_id")?>">
        </div>
      </div>
      <div class="input_content_wrap">
        <label class="input_tit">인증방식<span class="required">*</span></label>
        <div class="input_content checks">
          <input type="radio" id="auth_ars_radio" name="radio" style="margin-left: 10px;" value="ars" onclick="auth_ars();" checked="">
          <label for="auth_ars_radio">ARS 인증</label>
          <input type="radio" id="auth_mob_radio" name="radio" value="kakao" onclick="auth_mob();" >
          <label for="auth_mob_radio">전화인증</label>
          <input type="radio" id="auth_doc_radio" name="radio" style="margin-left: 10px;" value="doc" onclick="auth_doc();">
          <label for="auth_doc_radio">서류 인증</label>
          <!--  <input type="radio" id="auth_kakao_radio" name="radio" style="margin-left: 10px;" value="doc" onclick="auth_mob();"> 카카오 인증   -->
          <input type="hidden" id="auth_admin_radio" name="radio" style="margin-left: 10px;" value="doc" onclick="auth_mob();">
        </div>
        <div class="input_content" id="mobile_tr">
          <div id="auth_mob_td">
            <div id="auth_mob_sel" hidden="">
              <div class="inline" style="margin-right: 2px;">
                <select name="auth_mob_num_1" id="auth_mob_num_1" <?//class="selectpicker"?> data-live-search="true" data-width="70px">
                  <option value="010">010</option>
                  <option value="011">011</option>
                  <option value="016">016</option>
                  <option value="017">017</option>
                  <option value="018">018</option>
                  <option value="019">019</option>
                </select>
              </div>
              -
              <input id="auth_mob_num_2" type="text" onkeyup="setNum(this); change_tel(); if($('#auth_mob_num_2').val() != ''){
                                                document.getElementById('auth_send_result').style.display = 'none';}
                                                if($('#auth_mob_num_2').val().length == 4) { $('#auth_mob_num_3').focus(); }" maxlength="4" class="form-control input-width-mini inline" style="margin:0 2px; width:60px;">
              -
              <input id="auth_mob_num_3" type="text" onkeyup="setNum(this); change_tel(); if($('#auth_mob_num_2').val() != ''){
                                                document.getElementById('auth_send_result').style.display = 'none';
                                            }" maxlength="4" class="form-control input-width-mini inline" style="margin:0 2px; width:60px;">
            </div>
            <div id="auth_ars_sel" class="inline" hidden="">
              <input id="auth_ars_num" type="text" onkeyup="setNum(this); change_tel(); if($('#auth_ars_num').val() != ''){
                                            document.getElementById('auth_send_result').style.display = 'none';
                                        }" class="form-control input-width-large inline" style="margin-right: 2px;" placeholder="전화번호를 입력하세요.">
            </div>
            <button id="auth_send_btn" onclick="check_duplicated_tel('auth');" class="btn md">인증번호 전송</button>
          </div>
          <div id="auth_doc_td" hidden="">
            <input id="auth_doc_num" type="text" onkeyup="setNum(this); if($('#auth_doc_num').val() != ''){
                                            document.getElementById('auth_send_result').style.display = 'none';
                                        }" class="form-control input-width-large inline" placeholder="전화번호를 입력하세요.">
          </div>
          <text id="auth_send_result" class="mt10" style="display:none; color:#f00;">전화번호를 입력해주세요.</text>
        </div>
      </div>
      <!--
							<div class="input_content_wrap">
								<label class="input_tit">전화번호<span class="required">*</span>
                                <i style="cursor: default;" class="icon-question-sign bs-tooltip" id="tel_tooltip" data-toggle="tooltip" data-container="body" data-placement="top" data-html="true" data-original-title="<p class=\&quot;btn_guide\&quot;>- <b>유선전화번호</b> : 지역번호 포함한 전화번호(17개 지역번호 포함)</p>
                                    <p class=&quot;btn_guide&quot;>- <b>이동통신전화번호</b> : 010-ABYY-YYYY</p>
                                    <p class=&quot;btn_guide&quot;>- <b>전국대표전화번호</b>(15YY,16YY,18YY) : 지역번호 및 내선번호 포함 금지</p>
                                    <p class=&quot;btn_guide&quot;>- <b>공통서비스식별번호</b>(0N0계열) : 번호 앞에 지역번호 사용금지</p>
                                    <p class=&quot;btn_guide&quot;>- 특수번호(112, 1335 등)는 해당 사용자(국가, 지방자치단체, 공공기관 등)에 한하여 사용 가능</p>">
                                </i></label>
							</div>
-->
      <div class="input_content_wrap" id="auth_mob_tr">
        <label class="input_tit">인증번호<span class="required">*</span></label>
        <div class="input_content">
          <input id="auth_num" type="text" onkeyup="setNum(this);" maxlength="6" class="form-control input-width-large inline">
          <text id="auth_result" class="mt10" style="display:none; color:#f00;">인증번호를 입력해주세요.</text>
        </div>
      </div>
      <div class="input_content_wrap" id="auth_doc_tr" hidden="">
        <label class="input_tit">첨부파일<span class="required">*</span></label>
        <div class="input_content">
          <div class="file">
            <input type="text" id="certificate_name" class="textbox form-control input-width-large" readonly="readonly" placeholder="파일이 선택되지 않았습니다.">
            <label for="certificate_file" class="btn h34" style="cursor:pointer;">파일찾기</label>
            <input type="file" id="certificate_file" name="certificate_file" class="upload-hidden" accept="image/jpeg, image/png" onchange="javascript: var a = this.value; var b = a.slice(12); document.getElementById('certificate_name').value=b;
	                                           if($('#certificate_file').val()!=''){
	                                               document.getElementById('file_result').style.display = 'none';
	                                           } CheckUploadFileSize(this)">
            <p>jpg, png 파일만 업로드 됩니다. 최대 500KB </p>
            <text id="file_result" class="mt10" style="display:none; color:#f00;">파일을 첨부하여 주세요.</text>
          </div>
        </div>
      </div>
      <div class="input_content_wrap">
        <label class="input_tit">사용 용도<span class="required">*</span></label>
        <div class="input_content">
          <input id="comments" type="text" maxlength="200" class="form-control input-width-large inline" value="문자 발송">
          <text id="comments_result" class="mt10" style="display:none; color:#f00;">파일을 첨부하여 주세요.</text>
        </div>
      </div>
      <div class="input_content_wrap" id="auth_bulk_tr" hidden="">
        <label class="input_tit">첨부파일<span class="required">*</span></label>
        <div class="input_content">
          <input type="text" id="bulk_file_name" class="textbox form-control input-width-large" readonly="readonly" placeholder="파일이 선택되지 않았습니다.">
          <label for="bulk_file" class="btn md">파일찾기</label>
          <input type="file" id="bulk_file" name="bulk_file" class="upload-hidden" accept=".xls, .xlsx" onchange="javascript: var a = this.value; var b = a.slice(12); document.getElementById('bulk_file_name').value=b;
                                                       if($('#bulk_file').val()!=''){
                                                           document.getElementById('bulk_result').style.display = 'none';
                                                        } check_upload_file();">
          <button class="btn" type="button" onclick="javascript: download_bulk_sample();" style="width:158px;margin-right:-60px;"><i class="icon-cloud-download"></i>업로드
          양식 다운로드 </button>
          <div class="help-block" id="upload_result" style="margin-left: 2px;">총 <span id="total_count" style="font-weight: bold;">0</span>개의 번호 중 <span id="add_count" style="font-weight: bold;">0</span>개의 번호가 업로드 되었습니다.<br>
            - 이용용도 누락 발신번호 : <span id="invalid_count">0</span>개<br>
            - 세칙 위반 발신번호 : <span id="vio_count">0</span>개<br>
            - 중복된 번호 : <span id="dup_count">0</span>개 </div>
          <input type="hidden" id="add_list">
          <input type="hidden" id="invalid_list">
          <input type="hidden" id="vio_list">
          <input type="hidden" id="dup_list">
          <input type="hidden" id="comments_list">
          <text id="bulk_result" class="mt10" style="display:none; color:#f00;">파일을 첨부하여 주세요.</text>
        </div>
      </div>
      <div class="input_content_wrap" id="auth_admin_tr" hidden="">
        <label class="input_tit">인증번호<span class="required">*</span></label>
        <div class="input_content">
          <input id="bulk_auth_num" type="text" onkeyup="setNum(this);" class="form-control input-width-large inline">
          <button id="bulk_auth_send_btn" onclick="check_duplicated_tel('bulk');" class="btn btn-primary">인증번호 전송</button>
          <text class="help-block">[내정보]에 등록된 담당자 연락처로 전송됩니다.</text>
          <text id="bulk_auth_result" class="mt10" style="display:none; color:#f00;">인증번호를 입력해주세요.</text>
        </div>
      </div>
    </div>
    <div class="btn_al_cen">
      <button class="btn_st3" onclick="check_duplicated_tel('add');">등록하기</button>
    </div>
  </div>
</div>
<? } else { ?>
<div class="content_wrap">
  <div class="row">
    <div class="col-xs-12">
      <div class="widget mt20">
        <div class="widget-content">
          <div class="well align-center">
            <li class="align-left">- 사전에 등록한 발신번호로만 문자메시지 전송이 가능합니다.</li>
            <li class="align-left">- 발신번호는 최대 9개 까지 등록 가능 합니다. </li>
            <li class="align-left">- 불필요한 번호를 삭제 하신 후 다시 등록 하시기 바랍니다. </li>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<? } ?>
<div class="modal fade" id="myModal_add_callback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="modal_add_callback">
    <div class="modal-content"> <br>
      <div class="modal-body">
        <div class="content_add_callback"> </div>
        <div class="btn_al_cen mg_t20 mg_b50">
          <button type="button" id="callback_dismiss" class="btn_st1" data-dismiss="modal">취소 </button>
          <button type="button" id="callback_confirm" class="btn_st1" onclick="add_callback();" data-dismiss="modal">확인 </button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal_add_callback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="modal_add_callback">
    <div class="modal-content"> <br>
      <div class="modal-body">
        <div class="content_add_callback"> </div>
        <div class="btn_al_cen mg_t20 mg_b50">
          <button type="button" id="callback_dismiss" class="btn_st1" data-dismiss="modal">취소 </button>
          <button type="button" id="callback_confirm" class="btn_st1" onclick="add_callback();" data-dismiss="modal">확인 </button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal_add_callback2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" id="modal_add_callback2">
    <div class="modal-content"> <br>
      <div class="modal-body">
        <div class="content_add_callback2"> </div>
        <div class="btn_al_cen mg_t20 mg_b50">
          <button type="button" id="callback_dismiss2" class="btn_st1" data-dismiss="modal">취소 </button>
          <button type="button" id="callback_confirm2" class="btn_st1" onclick="add_callback_file();" data-dismiss="modal">확인 </button>
        </div>
      </div>
    </div>
  </div>
</div>
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
		//alert("uid : "+ uid +", search_type : "+ search_type +", search_for : "+ search_for);
		//alert("partner_page : "+ partner_page +", searchType : "+ searchType +", searchStr : "+ searchStr); return;
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
					html += '  <li><button class=\'btn_st_sm\' onClick=\'partner_choice("'+ mem_id +'");\'>선택</button></li>'; //선택
					html += '</ul>';
				});
				$("#id_partner_list").append(html);
			}
		});
	}

	//업체목록 모달창 데이타 선택
	function partner_choice(mem_id){
		var mem_username = $("#td_mem_username"+ mem_id).html(); //업체명
		//alert(mem_id +", "+ mem_username); return;
		$("#mem_id").val(mem_id); //업체번호
		$("#mem_username").val(mem_username); //업체명
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
<style>
/.textbox {
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
	//예-아니오에서의 확인버튼
	function enter_myModal() {
		$("#myModal").unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 13) {
				$("#identify").click();
			}
		});
	}
	function enter_onkeyup() {
		$("#myModal_add_callback").unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 13) {
				$("#callback_confirm").click();
			}
		});
	}
	function enter_onkeyup2() {
		$("#myModal_add_callback2").unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 13) {
				$("#callback_confirm2").click();
			}
		});
	}

	//인증번호 재전송 가능 시간(초)
	var setTime = 300;

	//CSRF token
	function getCookie(name) {
		var cookieValue = null;
		if (document.cookie && document.cookie != '') {
			var cookies = document.cookie.split(';');
			for (var i = 0; i < cookies.length; i++) {
				var cookie = jQuery.trim(cookies[i]);
				// Does this cookie string begin with the name we want?
				if (cookie.substring(0, name.length + 1) == (name + '=')) {
					cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
					break;
				}
			}
		}
		return cookieValue;
	}

	//핸드폰 인증 클릭시
	function auth_mob() {
		$("#auth_mob_td").attr("hidden", false);
		$("#auth_doc_td").attr("hidden", true);
		$("#auth_mob_sel").addClass("inline");
		$("#auth_mob_sel").show;
		$("#auth_ars_sel").removeClass("inline");
		$("#auth_ars_sel").hide;
		$("#mobile_tr").show();
		$("#auth_com_tr").show();
		$("#auth_mob_tr").show();
		$("#auth_doc_tr").hide();
		$("#auth_bulk_tr").hide();
		$("#auth_admin_tr").hide();
		$("#auth_num").val("");
		//$("#comments").val("");
		$("#auth_send_btn").text('인증번호 전송');
		document.getElementById('auth_result').style.display = 'none';
		document.getElementById('auth_send_result').style.display = 'none';
		document.getElementById('bulk_result').style.display = 'none';
		document.getElementById('bulk_auth_result').style.display = 'none';
		change_tel();
	}

	//ARS 인증 클릭시
	function auth_ars() {
		$("#auth_mob_td").attr("hidden", false);
		$("#auth_doc_td").attr("hidden", true);
		$("#auth_mob_sel").removeClass("inline");
		$("#auth_mob_sel").hide;
		$("#auth_ars_sel").addClass("inline");
		$("#auth_ars_sel").show;
		$("#mobile_tr").show();
		$("#auth_com_tr").show();
		$("#auth_mob_tr").show();
		$("#auth_doc_tr").hide();
		$("#auth_bulk_tr").hide();
		$("#auth_admin_tr").hide();
		$("#auth_num").val("");
		//$("#comments").val("");
		$("#auth_send_btn").text('ARS 인증요청');
		document.getElementById('auth_result').style.display = 'none';
		document.getElementById('auth_send_result').style.display = 'none';
		document.getElementById('bulk_result').style.display = 'none';
		document.getElementById('bulk_auth_result').style.display = 'none';
		change_tel();
	}

	//서류 인증 클릭시
	function auth_doc() {
		$("#auth_mob_td").attr("hidden", true);
		$("#auth_doc_td").attr("hidden", false);
		$("#mobile_tr").show();
		$("#auth_com_tr").show();
		$("#auth_mob_tr").hide();
		$("#auth_doc_tr").show();
		$("#auth_bulk_tr").hide();
		$("#auth_admin_tr").hide();
		$("#auth_num").val("");
		//$("#comments").val("");
		document.getElementById('auth_result').style.display = 'none';
		document.getElementById('auth_send_result').style.display = 'none';
		document.getElementById('bulk_result').style.display = 'none';
		document.getElementById('bulk_auth_result').style.display = 'none';
		change_tel();
	}

	//대리인 인증 클릭시
	function auth_admin() {
		$("#auth_mob_td").attr("hidden", true);
		$("#auth_doc_td").attr("hidden", true);
		$("#auth_mob_tr").hide();
		$("#auth_doc_tr").hide();
		$("#mobile_tr").hide();
		$("#auth_com_tr").hide();
		$("#auth_bulk_tr").show();
		$("#auth_admin_tr").show();
		$("#auth_num").val("");
		//$("#comments").val("");
		document.getElementById('auth_result').style.display = 'none';
		document.getElementById('auth_send_result').style.display = 'none';
		document.getElementById('bulk_result').style.display = 'none';
		document.getElementById('bulk_auth_result').style.display = 'none';
		var auth_num = 'None';
		if (!auth_num || auth_num == 'None') {
			document.getElementById('bulk_auth_result').innerHTML = '전화번호가 등록되어 있지 않습니다.<br>(우측 상단 계정을 클릭하시어 [내 정보]에서 담당자 연락처를 등록 바랍니다.)';
			document.getElementById('bulk_auth_result').style.color = '#f00';
			document.getElementById('bulk_auth_result').style.display = 'block';
		}
		change_tel();
	}

	//숫자 여부 확인
	function setNum(obj) {
		var val = obj.value;
		var re = /[^0-9]/gi;
		obj.value = val.replace(re, "");
	}

	//인증번호 전송
	function auth_send(auth_num){
	   // alert(2)
		var auth_mob_radio = document.getElementById("auth_mob_radio").checked;
		var auth_ars_radio = document.getElementById("auth_ars_radio").checked;
		var auth_admin_radio = document.getElementById("auth_admin_radio");
		var user_type = 'M';
		var is_admin = 'False';

		if (auth_mob_radio == true || is_admin == true || user_type != 'P') {
			var result = document.getElementById('auth_send_result');
			var btn = $('#auth_send_btn');
			if (is_admin == true && auth_admin_radio.checked == true
				|| user_type != 'P' && auth_admin_radio.checked == true) {
				result = document.getElementById('bulk_auth_result');
				btn = $('#bulk_auth_send_btn');
			} else if (is_admin == true && auth_ars_radio == true
				|| user_type != 'P' && auth_ars_radio == true) {
				ars_auth_send(auth_num);
			}
			if (!btn.is('[disabled=disabled]') == true) {
				sms_auth_send(result, btn, auth_num);
			}
		} else if (auth_ars_radio == true) {
			if (!(document.getElementById('auth_send_btn').getAttribute('disabled') == 'disabled')) {
				ars_auth_send(auth_num);
			}
		}
	}

	//카카오 인증
	function sms_auth_send(obj, btn, auth_num) {
		var mem_id = $("#mem_id").val();
		var csrftoken = getCookie('csrftoken');
		obj.innerHTML = '잠시만 기다려 주세요.';
		obj.style.color = '#2a6496';
		obj.style.display = 'block';
		$.ajax({
			url: "/biz/sendphnmng/send_auth_number/",
			type: "POST",
			data: {
				"<?=$this->security->get_csrf_token_name()?>":'<?=$this->security->get_csrf_hash()?>',
				"auth_num": auth_num,
				"mem_id": mem_id,
				"location": "auth_mob"
			},
			success: function (json) {
				var result = json['result'];
				if (result == 'success') {
					tid = setInterval('auth_time()', 1000);
					obj.innerHTML = '인증번호를 전송하였습니다.';
					obj.style.color = '#2a6496';
					obj.style.display = 'block';
					btn.attr('disabled', 'disabled');
					btn.css("cursor", "default");
				} else if(result=='not_valid_no') {
					obj.innerHTML = '발신번호가 유효하지 않습니다.<br>확인 후 다시 시도하세요.';
					obj.style.color = '#f00';
					obj.style.display = 'block';
				} else {
					obj.innerHTML = '처리중 오류가 발생하였습니다.1<br>관리자에게 문의해주시기 바랍니다.';
					obj.style.color = '#f00';
					obj.style.display = 'block';
				}
			},
			error: function () {
				obj.innerHTML = '처리중 오류가 발생하였습니다.2<br>관리자에게 문의해주시기 바랍니다.';
				obj.style.color = '#f00';
				obj.style.display = 'block';
			}
		});
	}

	//ars 인증
	function ars_auth_send(auth_num) {
		var auth_ars_radio = document.getElementById("auth_ars_radio");
		document.getElementById('auth_send_result').innerHTML = 'ARS 진행중입니다.(전화를 끊은 후 등록해주세요.)';
		document.getElementById('auth_send_result').style.color = '#2a6496';
		document.getElementById('auth_send_result').style.display = 'block';
		$("#auth_send_btn").attr("disabled", "disabled").css("cursor", "default");
		tid = setInterval('auth_time()', 1000);
		var csrftoken = getCookie('csrftoken');
		var mem_id = $("#mem_id").val();
		$.ajax({
			url: "/biz/sendphnmng/send_auth_number/",
			type: "POST",
			data: {
				"<?=$this->security->get_csrf_token_name()?>":'<?=$this->security->get_csrf_hash()?>',
				"auth_num": auth_num,
				"mem_id": mem_id,
				"location": "auth_ars"
			},
			success: function (json) {
				var result = json['result'];
				var msg = json['msg'];
				if (auth_ars_radio.checked == true && $('#auth_send_btn').is('[disabled=disabled]') == true) {
					if (result == "success") {
						document.getElementById('auth_send_result').innerHTML = 'ARS 인증요청이 성공하였습니다.';
						document.getElementById('auth_send_result').style.color = '#2a6496';
						document.getElementById('auth_send_result').style.display = 'block';
					} else if(result=='not_valid_no') {
						obj.innerHTML = '발신번호가 유효하지 않습니다.<br>확인 후 다시 시도하세요.';
						obj.style.color = '#f00';
						obj.style.display = 'block';
					} else {
						document.getElementById('auth_send_result').innerHTML = '처리중 오류가 발생하였습니다.4<br>관리자에게 문의해주시기 바랍니다.';
						document.getElementById('auth_send_result').style.color = '#f00';
						document.getElementById('auth_send_result').style.display = 'block';
						change_tel();
					}
				}
			},
			error: function () {
				document.getElementById('auth_send_result').innerHTML = '처리중 오류가 발생하였습니다.5<br>관리자에게 문의해주시기 바랍니다.';
				document.getElementById('auth_send_result').style.color = '#f00';
				document.getElementById('auth_send_result').style.display = 'block';
				change_tel();
			}
		});
	}

	//발신번호 중복 체크
	function check_duplicated_tel(obj) {
		var auth_num = '';
		var result = true;
		var mobile_regex = /^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080|007)\-?\d{3,4}\-?\d{4,5}$|^(15|16|18)\d{2}\-?\d{4,5}$/;
		var general_num_regex = /^(02|0[3-6]\d)(15|16|18)\d{2}\-?\d{4,5}$/;
		var auth_mob_radio = document.getElementById("auth_mob_radio").checked;
		var auth_ars_radio = document.getElementById("auth_ars_radio").checked;
		var auth_doc_radio = document.getElementById("auth_doc_radio").checked;
		//var auth_kakao_radio = document.getElementById("auth_kakao_radio").checked;
		var user_type = 'M';
		var is_admin = 'False';
		if (auth_mob_radio == true  ) {
			var auth_mob_num_1 = $("#auth_mob_num_1").val();
			var auth_mob_num_2 = $("#auth_mob_num_2").val();
			var auth_mob_num_3 = $("#auth_mob_num_3").val();
			auth_num = auth_mob_num_1 + auth_mob_num_2 + auth_mob_num_3;
			document.getElementById('auth_send_result').style.display = 'none';
			if (!auth_mob_num_2) {
				document.getElementById('auth_send_result').innerHTML = '전화번호를 입력해주세요.';
				document.getElementById('auth_send_result').style.color = '#f00';
				document.getElementById('auth_send_result').style.display = 'block';
				$('#auth_mob_num_2').focus();
				result = false;
			} else if (!auth_mob_num_3) {
				document.getElementById('auth_send_result').innerHTML = '전화번호를 입력해주세요.';
				document.getElementById('auth_send_result').style.color = '#f00';
				document.getElementById('auth_send_result').style.display = 'block';
				$('#auth_mob_num_3').focus();
				result = false;
			}
		} else if (auth_ars_radio == true) {
			auth_num = $("#auth_ars_num").val();
			if (!auth_num) {
				document.getElementById('auth_send_result').innerHTML = '전화번호를 입력해주세요.';
				document.getElementById('auth_send_result').style.color = '#f00';
				document.getElementById('auth_send_result').style.display = 'block';
				$('#auth_ars_num').focus();
				result = false;
			}
		} else if (auth_doc_radio == true) {
			auth_num = $("#auth_doc_num").val();
			if (!auth_num) {
				document.getElementById('auth_send_result').innerHTML = '전화번호를 입력해주세요.';
				document.getElementById('auth_send_result').style.color = '#f00';
				document.getElementById('auth_send_result').style.display = 'block';
				$('#auth_doc_num').focus();
				result = false;
			}
		} /*else if (is_admin == true || user_type != 'P') {
			if (auth_admin_radio.checked == true && obj == 'bulk') {
				auth_num = 'None';
				if (!auth_num || auth_num == 'None') {
					document.getElementById('bulk_auth_result').innerHTML = '전화번호가 등록되어 있지 않습니다.<br>(우측 상단 계정을 클릭하시어 [내 정보]에서 담당자 연락처를 등록 바랍니다.)';
					document.getElementById('bulk_auth_result').style.color = '#f00';
					document.getElementById('bulk_auth_result').style.display = 'block';
				}
			}
		}*/
		if (result == true && !mobile_regex.test(auth_num)
			|| result == true && !general_num_regex.test(auth_num)
			|| result == true && is_admin == true
			|| result == true && user_type != 'P') {
			if (is_admin == true || user_type != 'P') {
				// if ((/*auth_admin_radio.checked != true &&*/ !mobile_regex.test(auth_num)) || (/*auth_admin_radio.checked != true &&*/ general_num_regex.test(auth_num))) {
				// 	document.getElementById('auth_send_result').innerHTML = '전화번호 형식이 올바르지 않습니다.' ;
				// 	document.getElementById('auth_send_result').style.color = '#f00';
				// 	document.getElementById('auth_send_result').style.display = 'block';
				// 	result = false;
				// }
			} else {
				document.getElementById('auth_send_result').innerHTML = '전화번호 형식이 올바르지 않습니다.';
				document.getElementById('auth_send_result').style.color = '#f00';
				document.getElementById('auth_send_result').style.display = 'block';
				result = false;
			}
		}
		/*if (is_admin == true && auth_admin_radio.checked == true ||
			user_type != 'P' && auth_admin_radio.checked == true) {
			if (obj != 'bulk') {
				complete(auth_num);
			} else if (obj == 'bulk') {
				auth_send(auth_num);
			}
		} else*/
		var mem_id = $("#mem_id").val();
		if (result == true) {
			//alert("mem_id : "+ mem_id); return;
			$.ajax({
				url: "/biz/sendphnmng/check_duplicated_tel",
				type: "POST",
				data: {
					"<?=$this->security->get_csrf_token_name()?>":'<?=$this->security->get_csrf_hash()?>',
					"mem_id": mem_id,
					"auth_num": auth_num
				},
				success: function (json) {
					var count = json['result'];
					if (Number(count) > 0) {
						document.getElementById('auth_send_result').innerHTML = '이미 등록되어 있는 번호입니다.';
						document.getElementById('auth_send_result').style.color = '#f00';
						document.getElementById('auth_send_result').style.display = 'block';
						$('#auth_mob_num_3').focus();
					} else {
						if (obj == 'auth') {
						   auth_send(auth_num);
						} else if (obj == 'add') {
							complete(auth_num);
						}
					}
				},
				error: function () {
					document.getElementById('auth_send_result').innerHTML = '처리중 오류가 발생하였습니다.6<br>관리자에게 문의해주시기 바랍니다.';
					document.getElementById('auth_send_result').style.color = '#f00';
					document.getElementById('auth_send_result').style.display = 'block';
				}
			});
		}
	}

	//인증 재전송 시간 5분 체크
	function auth_time() {
		var btn = document.getElementById('auth_send_btn');
		var auth_admin_radio = document.getElementById("auth_admin_radio");
		var user_type = 'M';
		var is_admin = 'False';
		if (is_admin == true && auth_admin_radio.checked == true
			|| user_type != 'P' && auth_admin_radio.checked == true) {
			btn = document.getElementById('bulk_auth_send_btn');
		}
		var auth_time = '';
		if (Math.floor(setTime / 60) > 0) {
			auth_time += Math.floor(setTime / 60) + "분";
		}
		if (Math.floor(setTime / 60) > 0 && setTime % 60 > 0) {
			auth_time += " ";
		}
		if (setTime % 60 > 0) {
			auth_time += setTime % 60 + "초";
		}

		if (Math.floor(setTime / 60) == 0 && setTime % 60 == 0) {
			btn.innerHTML = '재전송';
			setTime = 300;
			clearInterval(tid);
			$("#auth_send_btn").removeAttr("disabled").css("cursor", "pointer");
			$("#bulk_auth_send_btn").removeAttr("disabled").css("cursor", "pointer");
		} else {
			btn.innerHTML = '재전송 (' + auth_time + ')';
		}
		setTime--;
	}

	//전화번호 변경시 재전송시간 초기화
	function change_tel() {
		if ($("#auth_send_btn").is('[disabled=disabled]') == true
			|| $("#bulk_auth_send_btn").is('[disabled=disabled]') == true) {
			clearInterval(tid);
			$("#auth_send_btn").removeAttr("disabled").css("cursor", "pointer");
			$("#bulk_auth_send_btn").removeAttr("disabled").css("cursor", "pointer");
			var auth_mob_radio = document.getElementById("auth_mob_radio").checked;
			var auth_ars_radio = document.getElementById("auth_ars_radio").checked;
			if (auth_mob_radio == true) {
				document.getElementById('auth_send_btn').innerHTML = '인증번호 전송';
			} else if (auth_ars_radio == true) {
				$("#auth_send_btn").text('ARS 인증요청');
			}
			document.getElementById('bulk_auth_send_btn').innerHTML = '인증번호 전송';
			setTime = 300;
		}
	}

	//서류 인증 - 파일 업로드 크기 500KB 제한
	function CheckUploadFileSize(objFile) {
		if (objFile.files[0].size > 500000) {
			$(".content").html("최대 500KB까지만 가능합니다.");
			$("#myModal").modal({backdrop: 'static'});
			enter_myModal();
			$("#certificate_name").val("");
			$("#certificate_file").val("");
			objFile.outerHTML = objFile.outerHTML;
		}
	}



	//대리인 인증 - 샘플 양식 다운로드
	function download_bulk_sample() {
		var form = document.createElement("form");
		document.body.appendChild(form); 
		form.setAttribute("method", "post");
		form.setAttribute("action", "/callback/download_bulk_sample");
		form.submit();
	}

	//대리인 인증 - 업로드 확인
	function check_upload_file() {
		if ($("input[name=bulk_file]")[0].files[0]) {
			var formData = new FormData();
			formData.append("bulk_file", $("input[name=bulk_file]")[0].files[0]);
			$.ajax({
				url: "/callback/upload_bulk_tel",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					$('#overlay').fadeIn();
				},
				complete: function () {
					$('#overlay').fadeOut();
				},
				success: function (json) {
					$("#upload_result").css("color", "#adadad");
					$("#total_count").text('0').css("color", "#adadad");
					$("#add_count").text('0').attr('onclick', '').css('cursor', 'default').css("color", "#adadad");
					$("#invalid_count").text('0').attr('onclick', '').css('cursor', 'default').css("color", "#adadad");
					$("#vio_count").text('0').attr('onclick', '').css('cursor', 'default').css("color", "#adadad");
					$("#dup_count").text('0').attr('onclick', '').css('cursor', 'default').css("color", "#adadad");
					$("#add_list").val('');
					$("#vio_list").val('');
					$("#dup_list").val('');
					$("#comments_list").val('');
					var status = json['status'];
					if (status == 'error') {
						var msg = json['msg'];
						$(".content").html("올바르지 않은 파일입니다.<br/>"+msg);
						$('#myModal').modal({backdrop: 'static'});
						enter_myModal();
						$('#bulk_file').val('');
						$('#bulk_file_name').val('');
					} else {
						var total = json['total'];
						var add_list = json['add_list'];
						var invalid_list = json['invalid_list'];
						var violation_list = json['violation_list'];
						var dup_list = json['dup_list'];
						var comments_list = json['comments_list'];
						$("#upload_result").css("color", "black");
						$("#total_count").text(total).css('color', '#4d7496');
						$("#add_count").text(add_list.length).css('color', '#4d7496');
						$("#invalid_count").text(invalid_list.length).css('color', '#f00');
						$("#vio_count").text(violation_list.length).css('color', '#f00');
						$("#dup_count").text(dup_list.length).css('color', '#f00');
						if (add_list.length > 0) {
							$("#add_count").attr('onclick', 'click_count("add")').css('cursor', 'pointer');
						}
						if (invalid_list.length > 0) {
							$("#invalid_count").attr('onclick', 'click_count("invalid")').css('cursor', 'pointer');
						}
						if (violation_list.length > 0) {
							$("#vio_count").attr('onclick', 'click_count("vio")').css('cursor', 'pointer');
						}
						if (dup_list.length > 0) {
							$("#dup_count").attr('onclick', 'click_count("dup")').css('cursor', 'pointer');
						}
						var add_list_arr = new Array();
						var invalid_list_arr = new Array();
						var vio_list_arr = new Array();
						var dup_list_arr = new Array();
						var comments_list_arr = new Array();
						for (var i = 0; i<add_list.length; i++) {
							add_list_arr.push(add_list[i]);
							comments_list_arr.push(comments_list[i]);
						}
						for (var i = 0; i<invalid_list.length; i++) {
							invalid_list_arr.push(invalid_list[i]);
						}
						for (var i = 0; i<violation_list.length; i++) {
							vio_list_arr.push(violation_list[i]);
						}
						for (var i = 0; i<dup_list.length; i++) {
							dup_list_arr.push(dup_list[i]);
						}
						var add_list_str = JSON.stringify(add_list_arr);
						var invalid_list_str = JSON.stringify(invalid_list_arr);
						var vio_list_arr = JSON.stringify(vio_list_arr);
						var dup_list_arr = JSON.stringify(dup_list_arr);
						var comments_list_str = JSON.stringify(comments_list_arr);

						$("#add_list").val(add_list_str);
						$("#invalid_list").val(invalid_list_str);
						$("#vio_list").val(vio_list_arr);
						$("#dup_list").val(dup_list_arr);
						$("#comments_list").val(comments_list_str);
					}
				},
				error: function () {
						$(".content").html("처리중 오류가 발생하였습니다.7<br/>관리자에게 문의해주시기 바랍니다.");
						$("#myModal").modal('show');
						enter_myModal();
				}
			});
		} else {
			$("#upload_result").css("color", "#adadad");
			$("#total_count").text('0').css("color", "#adadad");
			$("#add_count").text('0').attr('onclick', '').css('cursor', 'default').css("color", "#adadad");
			$("#invalid_count").text('0').attr('onclick', '').css('cursor', 'default').css("color", "#adadad");
			$("#vio_count").text('0').attr('onclick', '').css('cursor', 'default').css("color", "#adadad");
			$("#dup_count").text('0').attr('onclick', '').css('cursor', 'default').css("color", "#adadad");
			$("#add_list").val('');
			$("#vio_list").val('');
			$("#dup_list").val('');
			$("#comments_list").val('');
		}
	}

	function click_count(obj) {
		var list = '';
		if (obj == 'add') {
			list = JSON.parse($("#add_list").val());
		} else if (obj == 'invalid') {
			list = JSON.parse($("#invalid_list").val());
		} else if (obj == 'vio') {
			list = JSON.parse($("#vio_list").val());
		} else if (obj == 'dup') {
			list = JSON.parse($("#dup_list").val());
		}
		var list_html = '';
		for (var i = 0; i < list.length; i++){
			list_html += ''+
				'<tr align="center" name="select">' +
				'<td class="text" style="width: 100%">' + list[i] + '</td>' +
				'</tr>';
		}

		list_html = '' +
			'<div style="max-height: 304px; overflow: auto;">' +
			'<table class="table table-bordered table-highlight-head" style="position: fixed; width:90% !important;">' +
			'<thead>' +
			'<tr style="cursor:default;">' +
			'<th>전화번호</th>' +
			'</tr>' +
			'</thead>' +
			'<table class="table table-bordered table-highlight-head" style="margin-top: 37px; width:100% !important;">' +
			'<tbody class="table-content" style="width:100%; max-height:100px;">' +
			list_html +
			'</tbody>' +
			'</table></div>';

		$(".content").html(list_html);
		$("#myModal").css("height", "500px");
		$("#myModal").modal('show');
		enter_myModal();
	}

	//발신번호 등록
	function complete(auth_mob_num) {
		document.getElementById('auth_result').style.display = 'none';
		document.getElementById('auth_send_result').style.display = 'none';
		document.getElementById('comments_result').style.display = 'none';
		document.getElementById('file_result').style.display = 'none';
		document.getElementById('bulk_result').style.display = 'none';
		var auth_mob_radio = document.getElementById("auth_mob_radio");
		var auth_ars_radio = document.getElementById("auth_ars_radio");
		var auth_doc_radio = document.getElementById("auth_doc_radio");
		var auth_admin_radio = document.getElementById("auth_admin_radio");
		var user_type = 'M';
		var is_admin = 'False';

		if (auth_mob_radio.checked == true || auth_ars_radio.checked == true) {
			if (!$("#auth_num").val()) {
				document.getElementById('auth_result').innerHTML = '인증번호를 입력해주세요.';
				document.getElementById('auth_result').style.color = '#f00';
				document.getElementById('auth_result').style.display = 'block';
			} else if (!$("#comments").val().trim()) {
				document.getElementById('comments_result').innerHTML = '사용 용도를 입력해주세요.';
				document.getElementById('comments_result').style.color = '#f00';
				document.getElementById('comments_result').style.display = 'block';
			} else {
				document.getElementById('auth_result').style.display = 'none';
				var mem_id = $("#mem_id").val();
				$.ajax({
					url: "/biz/sendphnmng/check_auth_number/",
					type: "POST",
					data: {
						"<?=$this->security->get_csrf_token_name()?>":'<?=$this->security->get_csrf_hash()?>',
						"mobile": auth_mob_num,
						"mem_id": mem_id,
						"auth_code": $("#auth_num").val()
					},
					success: function (json) {
						var result = json['result'];
						if (result == 'EmptyCode') {
							document.getElementById('auth_result').innerHTML = '인증번호를 받아주시기 바랍니다.';
							document.getElementById('auth_result').style.color = '#f00';
							document.getElementById('auth_result').style.display = 'block';
						} else if (result == 'NoMatchCode') {
							document.getElementById('auth_result').innerHTML = '인증번호가 일치하지 않습니다.';
							document.getElementById('auth_result').style.color = '#f00';
							document.getElementById('auth_result').style.display = 'block';
						} else if (result == 'TimeOut') {
							document.getElementById('auth_result').innerHTML = '인증시간이 초과 되어 인증번호를 다시 받아주시기 바랍니다.';
							document.getElementById('auth_result').style.color = '#f00';
							document.getElementById('auth_result').style.display = 'block';
						} else if (user_type == 'P' && check_callback_count() >= 10) {
							$(".content").html("발신번호는 최대 10개까지 등록가능합니다.<br><br>추가 등록을 원하시는 경우 아래 이메일로 문의해주세요.<br>alimtalk@sweettracker.co.kr");
							$("#myModal").modal('show');
							enter_myModal();
						} else if (result == 'Success') {
							//$(".content_add_callback").html("발신번호를 등록 하시겠습니까?");
							//$("#myModal_add_callback").modal('show');
							//enter_onkeyup();
							//alert("발신번호가 등록 되었습니다."); return;
							$("#callback_confirm").click();
						} else {
							$(".content").html("처리중 오류가 발생하였습니다.8<br>관리자에게 문의해주시기 바랍니다.");
							$("#myModal").modal('show');
							enter_myModal();
						}
					},
					error: function () {
						$(".content").html("처리중 오류가 발생하였습니다.9<br>관리자에게 문의해주시기 바랍니다.");
						$("#myModal").modal('show');
						enter_myModal();
					}
				});
			}
		} else if (auth_doc_radio.checked == true) {
		   // alert(1);

			if($('#certificate_file').val()=='') {
				document.getElementById('file_result').innerHTML = '파일을 업로드 해주시기 바랍니다.';
				document.getElementById('file_result').style.color = '#f00';
				document.getElementById('file_result').style.display = 'block';
			} else if (!$("#comments").val().trim()) {
				document.getElementById('comments_result').innerHTML = '사용 용도를 입력해주세요.';
				document.getElementById('comments_result').style.color = '#f00';
				document.getElementById('comments_result').style.display = 'block';
			} else if (user_type == 'P' && check_callback_count() >= 10) {
				$(".content").html("발신번호는 최대 10개까지 등록가능합니다.<br><br>추가 등록을 원하시는 경우 아래 이메일로 문의해주세요.<br>alimtalk@sweettracker.co.kr");
				$("#myModal").modal('show');
				enter_myModal();
			} else {
				//$(".content_add_callback2").html("발신번호를 등록 하시겠습니까?");
				//$("#myModal_add_callback2").modal('show');
				//enter_onkeyup2();
				//alert("발신번호가 등록 되었습니다."); return;
				$("#callback_confirm2").click();
			}
		} else if (is_admin == true || user_type != 'P') {
			if (auth_admin_radio.checked == true) {
				var auth_mob_num = 'None';
				if ($('#bulk_file').val() == '') {
					document.getElementById('bulk_result').innerHTML = '파일을 업로드 해주시기 바랍니다.';
					document.getElementById('bulk_result').style.color = '#f00';
					document.getElementById('bulk_result').style.display = 'block';
				} else if ($("#add_count").text() <= 0) {
					document.getElementById('bulk_result').innerHTML = '등록할 번호가 없습니다.';
					document.getElementById('bulk_result').style.color = '#f00';
					document.getElementById('bulk_result').style.display = 'block';
				} else if (!$("#bulk_auth_num").val()) {
					document.getElementById('bulk_auth_result').innerHTML = '인증번호를 입력해주세요.';
					document.getElementById('bulk_auth_result').style.color = '#f00';
					document.getElementById('bulk_auth_result').style.display = 'block';
				} else {
					$.ajax({
						url: "/check_auth_number/",
						type: "POST",
						data: {
							"mobile": auth_mob_num,
							"auth_code": $("#bulk_auth_num").val()
						},
						success: function (json) {
							var result = json['result'];
							if (result == 'EmptyCode') {
								document.getElementById('bulk_auth_result').innerHTML = '인증번호를 받아주시기 바랍니다.';
								document.getElementById('bulk_auth_result').style.color = '#f00';
								document.getElementById('bulk_auth_result').style.display = 'block';
							} else if (result == 'NoMatchCode') {
								document.getElementById('bulk_auth_result').innerHTML = '인증번호가 일치하지 않습니다.';
								document.getElementById('bulk_auth_result').style.color = '#f00';
								document.getElementById('bulk_auth_result').style.display = 'block';
							} else if (result == 'TimeOut') {
								document.getElementById('bulk_auth_result').innerHTML = '인증시간이 초과 되어 인증번호를 다시 받아주시기 바랍니다.';
								document.getElementById('bulk_auth_result').style.color = '#f00';
								document.getElementById('bulk_auth_result').style.display = 'block';
							} else if (result == 'Success') {
								//$(".content_add_callback2").html("발신번호를 등록 하시겠습니까?");
								//$("#myModal_add_callback2").modal('show');
								//enter_onkeyup2();
								//alert("발신번호가 등록 되었습니다."); return;
								$("#callback_confirm2").click();
							} else {
								$(".content").html("처리중 오류가 발생하였습니다.10<br>관리자에게 문의해주시기 바랍니다.");
								$("#myModal").modal('show');
								enter_myModal();
							}
						},
						error: function () {
							$(".content").html("처리중 오류가 발생하였습니다.11<br>관리자에게 문의해주시기 바랍니다.");
							$("#myModal").modal('show');
							enter_myModal();
					}
					});

				}
			}
		}
	}

	function add_callback() {
		var auth_mob_num = '';
		var auth_mob_radio = document.getElementById("auth_mob_radio");
		var auth_ars_radio = document.getElementById("auth_ars_radio");
		if (auth_mob_radio.checked == true) {
			var auth_mob_num_1 = $("#auth_mob_num_1").val();
			var auth_mob_num_2 = $("#auth_mob_num_2").val();
			var auth_mob_num_3 = $("#auth_mob_num_3").val();
			auth_mob_num = auth_mob_num_1 + auth_mob_num_2 + auth_mob_num_3;
			var auth_method = 'mobile';
		} else if (auth_ars_radio.checked == true) {
			auth_mob_num = $("#auth_ars_num").val();
			var auth_method = 'ars';
		}
		var comments = $("#comments").val();
		var mem_id = $("#mem_id").val();
		$.ajax({
			url: "/biz/sendphnmng/callback_add",
			type: "POST",
			data: {
				"<?=$this->security->get_csrf_token_name()?>":'<?=$this->security->get_csrf_hash()?>',
				"auth_num": auth_mob_num,
				"auth_method": auth_method,
				"mem_id": mem_id,
				"comments": comments
			},
			success: function (json) {
				var result = json['result'];
				if (result == null) {
					$(".content").html("발신번호가 등록되었습니다.");
					$("#myModal").modal('show');
					enter_myModal();
					$('#myModal').on('hidden.bs.modal', function () {
						location.href = "/biz/sendphnmng/";
					});
				} else {
					$(".content").html("처리중 오류가 발생하였습니다.12<br>관리자에게 문의해주시기 바랍니다.");
					$("#myModal").modal('show');
					enter_myModal();
				}
			},
			error: function () {
				$(".content").html("처리중 오류가 발생하였습니다.13<br>관리자에게 문의해주시기 바랍니다.");
				$("#myModal").modal('show');
				enter_myModal();
			}
		});
	}

	function add_callback_file() {
		var auth_method = '';
		var auth_doc_radio = document.getElementById("auth_doc_radio");
		var auth_admin_radio = document.getElementById("auth_admin_radio");
		var formData = new FormData();
		var mem_id = $("#mem_id").val();
		if (auth_doc_radio.checked == true) {
			auth_method = 'file';
			formData.append("<?=$this->security->get_csrf_token_name()?>", '<?=$this->security->get_csrf_hash()?>');
			formData.append("auth_num", $("#auth_doc_num").val());
			formData.append("auth_method", auth_method);
			formData.append("mem_id", mem_id);
			formData.append("comments", $("#comments").val());
			formData.append("certificate_file", $("input[id=certificate_file]")[0].files[0]);
		} else if (auth_admin_radio.checked == true) {
			auth_method = 'agent';
			var csrfField = document.createElement("input");
			csrfField.setAttribute("type", "hidden");
			csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			csrfField.setAttribute("value", '<?=$this->security->get_csrf_hash()?>');
			formData.appendChild(csrfField);
			formData.append("auth_method", auth_method);
			formData.append("mem_id", mem_id);
			formData.append("auth_num_list", $("#add_list").val());
			formData.append("comments_list", $("#comments_list").val());
			formData.append("bulk_file", $("input[id=bulk_file]")[0].files[0]);
		}
		$.ajax({
			url: "/biz/sendphnmng/callback_add",
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function (json) {
				var result = json['result'];
				if (result == null) {
					$(".content").html("발신번호가 등록되었습니다.");
					$("#myModal").modal('show');
					enter_myModal();
					$('#myModal').on('hidden.bs.modal', function () {
						location.href = "/biz/sendphnmng/";
					});
				} else {
					$(".content").html("처리중 오류가 발생하였습니다.14<br>관리자에게 문의해주시기 바랍니다.");
					$("#myModal").modal('show');
					enter_myModal();
				}
			},
			error: function () {
				$(".content").html("처리중 오류가 발생하였습니다.15<br>관리자에게 문의해주시기 바랍니다.");
				$("#myModal").modal('show');
				enter_myModal();
			}
		});
	}

	function check_callback_count() {
		var callback_count;
		$.ajax({
			url: "/callback/check_callback_count",
			type: "POST",
			async: false,
			success: function (json) {
				callback_count = json['callback_count'];
			},
			error: function () {
				$(".content").html("처리중 오류가 발생하였습니다.16<br>관리자에게 문의해주시기 바랍니다.");
				$("#myModal").modal('show');
				enter_myModal();
			}
		});
		return callback_count;
	}


  $(document).ready( function () {
		// $("#auth_mob_sel").addClass("inline");
		// $("#auth_mob_sel").show;
    $("#auth_ars_sel").addClass("inline");
    $("#auth_ars_sel").show;
	});
</script>
<script type="text/javascript">
	/*$("#nav li.nav16").addClass("current open");

	$(document).unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			$(".btn-primary").click();
		}
	});*/
</script>
