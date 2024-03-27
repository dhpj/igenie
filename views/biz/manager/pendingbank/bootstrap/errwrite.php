<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>무통장미처리내역</h3>
		</div>
		<div class="box-table">
			<table class="tpl_ver_form" width="100%">
				<colgroup>
				<col width="200">
				<col width="*">
				</colgroup>
				<tbody>
					<input type="hidden" id="idx" value="<?=$red->idx?>">
					<input type="hidden" id="usmsid" value="<?=$red->usmsid?>">
					<tr>
						<th>
							<select id="sc">
								<option value="username">업체명</option>
								<option value="biz_reg_rep_name">대표자</option>
								<option value="biz_reg_no">사업자번호</option>
							</select>
						</th>
						<td style="text-align:left;">
							<input type="text" id="sv" value="" placeholder="검색내용" onKeypress="if(event.keyCode==13){ search(); }">
							<button type="button" class="btn_st_sm" onClick="search();">검색</button>
							<select id="memid" style="margin-left:10px;display:none;">
							</select>
						</td>
					</tr>
					<tr>
						<th>입금자명(사업자번호)</th>
						<td style="text-align:left; font-size:20px;">
						<?php echo $red->sender; ?>
						</td>
					</tr>
					<tr>
						<th>요청일시</th>
						<td style="text-align:left; font-size:20px;">
						<?php echo $red->cre_date; ?>
						</td>
					</tr>
					<tr>
						<th>실제결제한 금액</th>
						<td style="text-align:left; font-size:20px; color:#f00;">
						<?php echo number_format($red->money); ?>원
						</td>
					</tr>
					<tr>
						<th>관리자 메모</th>
						<td>
						<textarea class="form-control" rows="5" id="memo"></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			<div class="btn_al_cen" role="group" aria-label="...">
				<button type="button" class="btn_st1" onClick="list();">목록으로</button>
				<button type="button" class="btn_st1" style="margin-left:10px;" onClick="saved();">입금확인</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	//업체명 검색
	function search(){
		var sc = $("#sc").val().trim(); //검색타입
		var sv = $("#sv").val().trim(); //검색내용
		//alert("sv : "+ sv); return;
		if(sv == ""){
			alert("검색 내용을 입력하세요.");
			$("#sv").focus();
			return;
		}
		$('#memid').show();
		$('#memid').empty();
		$('#memid').append($("<option value=''>- 업체명 선택 -</option>"));
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
					$('#memid').append($("<option value='"+ mem_id +"'>"+ mem_username +" ("+ mem_nickname +") ["+ mem_userid +"]</option>"));
				});
			}
		});
	}

	//목록
	function list(){
		location.href = "/biz/manager/pendingbank/errlist"; //무통장입금목록 페이지
	}

	//저장
	function saved(){
		var idx = $("#idx").val(); //일련번호
		var usmsid = $("#usmsid").val(); //레드뱅킹ID
		var memid = $("#memid").val(); //회원번호
		var memo = $("#memo").val().trim(); //관리자 메모
		//alert("data_id : "+ data_id +"\n"+"tem_id : "+ tem_id +"\n"+"psd_title : "+ psd_title +"\n"+"psd_date : "+ psd_date); return;
		if(idx == "" || usmsid == ""){
			alert("잘못된 접근입니다.");
			$("#memid").focus();
			return;
		}
		if(memid == "" || memid == null){
			alert("업체명을 검색 후 업체명을 선택하세요.");
			$("#sv").focus();
			return;
		}
		if(memo == ""){
			alert("관리자 메모를 입력하세요.");
			$("#memo").focus();
			return;
		}
		//alert("OK");
		if(!confirm("저장 하시겠습니까?")){
			return;
		}
		$.ajax({
			url: "/biz/manager/pendingbank/errwrite_save",
			type: "POST",
			data: {
				  "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
				, "idx" : idx //일련번호
				, "usmsid" : usmsid //레드뱅킹ID
				, "memid" : memid //회원번호
				, "memo" : memo //관리자 메모
			},
			success: function (json) {
				//alert("json.code : "+ json.code +", json.msg : "+ json.msg);
				if(json.code == "0") { //성공
					showSnackbar("정상적으로 무통장입금 처리 되었습니다.", 1500);
					setTimeout(function() {
						location.href = "/biz/manager/pendingbank/index"; //무통장입금목록 페이지
					}, 1000); //1초 지연
				}else{
					alert(json.msg);
				}
			}
		});
	}
</script>
