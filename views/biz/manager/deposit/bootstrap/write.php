<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
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
<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<!--<h3>결제내역 관리</h3>-->
			<h3>예치금 충전/조정</h3>
		</div>
		<div class="white_box">
			<?php
			echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
			$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
			echo form_open(current_full_url(), $attributes);
			?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"    value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<table class="table_list">
				<colgroup>
				<col width="150px">
				<col width="*">
				</colgroup>
				<tbody>
				<tr>
					<th>업체명<span class="required">*</span></th>
					<td class="tl">
						<input type="text" class="form-control input-width-large inline" id="mem_username" name="mem_username" value="" Readonly>
						<button type="button" class="btn_myModal" onClick="partner_open();">업체검색</button>
						<input type="hidden" name="findname" id="findname" />
						<input type="hidden" name="mem_userid" id="mem_userid" />
						<input type="hidden" id="userid" name="userid" value="">
					</td>
				</tr>
				<tr>
					<th><?php echo $this->depositconfig->item('deposit_name'); ?> 변동<span class="required">*</span></th>
					<td class="tl">
                        <select name="dep_kind" id="dep_kind" class="valid" onchange="chageDepKind(this.value);">
                            <option value="4">선충전</option>
                            <option value="40">선충전차감</option>
							<option value="2">차감</option>
							<option value="1">충전</option>
                            <option value="9">바우처충전</option>
                            <option value="6">계정 이동 충전</option>
                            <option value="7">계정 이동 차감</option>
                            <option value="1">보너스 충전</option>
                            <option value="2">보너스 차감</option>
						</select>
						<input type="number" class="form-control input-width-medium" name="dep_deposit" id="dep_deposit" value="<?php echo set_value('dep_deposit', element('dep_deposit', element('data', $view))); ?>" >
						<div class="help-inline mg_t10"><font color='black'>* <font color='red'>[ 충전/차감/선충전/선충전차감 ]</font> 중 선택 후, 조정할 예치금을 입력해주세요.</font></div>
					</td>
				</tr>
				<tr>
					<th style="line-height:120%;">나의지갑&nbsp;<br/>사용내역<span class="required">*</span></th>
					<td class="tl">
						<textarea class="form-control" rows="5" name="dep_content">선충전<?php echo set_value('dep_content', element('dep_content', element('data', $view))); ?></textarea>
					</td>
				</tr>
				<tr>
					<th>관리자 메모</th>
					<td>
						<textarea class="form-control" rows="5" name="dep_admin_memo"><?php echo set_value('dep_admin_memo', element('dep_admin_memo', element('data', $view))); ?></textarea>
					</td>
				</tr>
				</tbody>
			</table>
			<?php
				echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
				$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
				echo form_open(current_full_url(), $attributes);
			?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"    value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />

				<div class="btn_al_cen" role="group" aria-label="...">
					<!--<button type="submit" class="btn_st1">저장하기</button>-->
					<button type="button" class="btn_st1" onClick="form_check();">저장하기</button>
				</div>

			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fadminwrite').validate({
        //ALERT($('#mem_userid').val());
        rules: {
            mem_userid: { required:true, minlength:3, maxlength:20 },
            dep_deposit: { required:true, number:true},
            dep_content: 'required',
            dep_kind :'required'
        }
    });
});

//]]>

</script>
<script type="text/javascript">
<!--
/*$(document).ready(function() {
	$("#nav li.nav100").addClass("current open");
	$(".modal-content").css('width', '320px');
	$(".modal-body").css('height', '120px');
	$("#myModal").css('overflow-x', 'hidden');
	$("#myModal").css('overflow-y', 'hidden');

	$('#fadminwrite').submit(function() {
		if($('#findname').val()=='') {
			$(".content").html("아이디 확인을 클릭하세요.");
			$('#myModal').modal({backdrop: 'static'});
			return false;
		}
		return true;
	});
});*/

function chageDepKind(value){ //20220608 조지영

    if(value == "9"){
        // $("#div_date").show();
        $("textarea[name=dep_content]").val("바우처 충전금 입금");
        $("#dep_deposit").val('2200000');
    }else{

        var text = $("#dep_kind option:selected").text();
        if(text=="보너스 충전"||text=="보너스 차감"){
            $("textarea[name=dep_content]").val("보너스");
        }else{
            $("textarea[name=dep_content]").val(text);
        }

    }


}

//아이디 확인
function check_account(id) {
	id_check = true;
	var formId = '#' + id;
	var mb_id=  $(formId).val();

	if ($(formId).val().trim().length == 0 || !mb_id) {
		$(".content").html("아이디를 입력해주세요.");
		$('#myModal').modal({backdrop: 'static'});
	}
	else {
		$('#id_findname').html('');
		$('#findname').val('');
		 $.ajax({
			  url: '/biz/common/check_id/',
			  type: "POST",
			  data: {
					"<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
					mb_id: mb_id
			  },
			  success: function (json) {
				  $('#mem_userid').val($('#userid').val());
					get_result(json);
			  },
			  error: function (data, status, er) {
					$(".content").html("처리중 오류가 발생하였습니다. 관리자에게 문의하세요.");
					$('#myModal').modal({backdrop: 'static'});
			  }
		 });
		 function get_result(json) {
			  if(json['result']==true) {
					$('#id_findname').html(json['name'] + " (" + json['nick'] + ")");
					$('#findname').val(json['name'] + " (" + json['nick'] + ")");
			  }
			  else{
					$('#id_findname').html('');
					$('#findname').val('');
			  }
		 }
	}
}

//저장하기
function form_check(){
	//alert("form_check()"); return false;
	var frm = document.fadminwrite;
	if(frm.userid.value == ""){
		alert("업체명을 선택하세요.");
		partner_open(); //업체검색
		return false;
	}
	if(frm.dep_deposit.value == ""){
		alert("예치금 변동 금액을 입력하여 주십시오.");
		frm.dep_deposit.focus();
		return false;
	}
    if((frm.dep_kind.value!='2' || frm.dep_kind.value!='40') && Number(frm.dep_deposit.value < 0)){
		alert("양수를 입력하여 주십시오.");
		frm.dep_deposit.focus();
		return false;
	}
	if(frm.dep_content.value == ""){
		alert("내용 항목은 필수 입력입니다.");
		frm.dep_content.focus();
		return false;
	}
	//alert("form_check() > OK"); return false;
	frm.submit();
	jsLoading(); //로딩중 호출
}
//-->
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
        <?
			if(!empty($users)){
				foreach($users as $r) {
		?>
        <option value="<?=$r->mem_id?>"><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
        <?
				}
			}
		?>
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
					html += '  <li><button class=\'btn_st_sm\' onClick=\'partner_choice("'+ mem_id +'", "'+ mem_userid +'");\'>선택</button></li>'; //선택
					html += '</ul>';
				});
				$("#id_partner_list").append(html);
			}
		});
	}

	//업체목록 모달창 데이타 선택
	function partner_choice(mem_id, mem_userid){
		var mem_username = $("#td_mem_username"+ mem_id).html(); //업체명
		//alert(mem_id +", "+ mem_username); return;
		//$("#mem_id").val(mem_id); //업체번호
		$("#userid").val(mem_userid); //업체 아이디
		$("#mem_userid").val(mem_userid); //업체 아이디
		$("#mem_username").val(mem_username); //업체명
		$("#findname").val(mem_username); //표시명
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
