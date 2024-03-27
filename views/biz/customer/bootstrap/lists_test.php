<?
	$excel_sample = "/uploads/sample/customer_list.xlsx?v=". cdate("ymdHms"); //엑셀양식 다운로드 경로
?>
<!-- 타이틀 영역 -->
<div class="tit_wrap">
	고객관리
</div>
<!-- 타이틀 영역 END -->
<!-- 컨텐츠 전체 영역 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>전체고객목록<span id="div_title"><span></h3>
			<button class="btn_tr" onclick="location.href='/biz/customer/write'">개별고객등록</button>
		</div>
		<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
		<input type='hidden' id='searchGlv' name='searchGlv' value='' />
		<input type='hidden' id='searchGid' name='searchGid' value='' />
		<input type='hidden' id='add' name='add' value='<?=$add?>' />
		<div class="clearfix"></div>
		<div class="white_box">
			<div class="search_box" style="margin:0;">
				<span class="search_tit">고객검색</span>
				<!--<button class="btn md fr" onclick="selectSend()"><i class="icon-envelope-alt"></i> 선택 고객 발신</button>-->
				<select id="searchType" onChange="search_question(1);" class="mg_l10">
					<option value="all">전체</option>
					<option value="normal">정상</option>
					<option value="reject">수신거부</option>
				</select>
				<input type="text" id="searchStr" name="searchStr" placeholder="전화번호 입력" value="" onKeypress="if(event.keyCode==13){ search_question(1); }">
				<select id="searchGroup" style="display:none;">
					<option value="">전체</option>
					<?foreach($kind as $r) {?>
					<option value="<?=$r->ab_kind?>"><?=$r->ab_kind?></option>
					<?}?>
					<!-- 2019.01.17. 이수환 "고객구분없음" 추가 -->
					<option value="NONE">고객구분없음</option>
					<option value="FT">친구등록</option>
					<option value="NFT">친구등록안됨</option>
				</select>
				<input type="text" id="searchName" name="searchName" placeholder="고객명 입력" value="" onKeypress="if(event.keyCode==13){ search_question(1); }">
				<input type="button" class="btn md color" style="cursor:pointer;" id="check" value="조회" onclick="search_question(1);"/>
				<button class="btn md excel fr mg_l5" id="check" onclick="download()">고객목록 다운로드</button>
			</div>
			</div>
			<div class="white_box mg_t20">
			<div class="cus_box_top">
				<div class="cus_tit">
					그룹이동
					<select id="newGid" class="mg_l10">
						<option value="">그룹선택</option>
						<option value="0">전체고객목록</option>
						<? foreach($customer_group as $r){ ?>
						<option value="<?=$r->cg_id?>"><?=($r->cg_level==2) ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ ' : '&nbsp;&nbsp;'?><?=$r->cg_gname?></option>
						<? } ?>
					</select>
					<input type="button" class="btn md color" style="cursor:pointer;" id="check" value="그룹변경" onclick="chg_group();"/>
				</div>
				<div class="cus_btn">
					<span class="cus_info">* 현재 선택된 그룹에 고객을 등록합니다.</span>
					<label for="excel_file" class="btn_excel_up" style="cursor:pointer;">고객등록 파일 업로드</label>
					<input name="excel_file" id="excel_file" accept=".xls, .xlsx" type="file" onChange="excel_upload();" style="display:none;">
					<button class="btn_excel_down mg_l5" id="check" onclick="location.href='<?=$excel_sample?>'">고객등록 양식 다운로드</button>
				</div>
			</div>
			<div class="widget-content"><?//고객 목록 영역?>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content identify">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal" id="identify">
                            확인
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalCustomer" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content identify">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal" onclick="customer_save();">
                            확인
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalCheck" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalAll" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1" onclick="all_move();">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalDel" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalSelDel" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalTemp" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalUpload" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalDownload" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	  $(document).ready(function() {
			open_page(1);
	  });
	$("#nav li.nav20").addClass("current open");

	$('.searchBox input').unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			open_page(1);
		}
	});

	$(document).ajaxStart(function(){
		$("#wait").css("display", "block");
	});

	$(document).ajaxComplete(function(){
		$("#wait").css("display", "none");
	});

	//엑셀 다운로드
	function download(){

		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/customer/download");

		var scrfField = document.createElement("input");
		scrfField.setAttribute("type", "hidden");
		scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		form.appendChild(scrfField);

		var searchType = document.createElement("input");
		searchType.setAttribute("type", "hidden");
		searchType.setAttribute("name", "search_type");
		searchType.setAttribute("value", $("#searchType").val());
		form.appendChild(searchType);

		var searchStr = document.createElement("input");
		searchStr.setAttribute("type", "hidden");
		searchStr.setAttribute("name", "search_for");
		searchStr.setAttribute("value", $("#searchStr").val());
		form.appendChild(searchStr);

		var searchGroup = document.createElement("input");
		searchGroup.setAttribute("type", "hidden");
		searchGroup.setAttribute("name", "search_group");
		searchGroup.setAttribute("value", $("#searchGroup").val());
		form.appendChild(searchGroup);

		var searchName = document.createElement("input");
		searchName.setAttribute("type", "hidden");
		searchName.setAttribute("name", "search_name");
		searchName.setAttribute("value", $("#searchName").val());
		form.appendChild(searchName);

		var input = document.createElement("input");
		input.setAttribute("type", "hidden");
		input.setAttribute("name", "searchGlv");
		input.setAttribute("value", $("#searchGlv").val()); //그룹레벨
		form.appendChild(input);

		var input = document.createElement("input");
		input.setAttribute("type", "hidden");
		input.setAttribute("name", "searchGid");
		input.setAttribute("value", $("#searchGid").val()); //그룹번호
		form.appendChild(input);

		//alert("searchGlv : "+ $("#searchGlv").val() +", searchGid : "+ $("#searchGid").val()); return;
		form.submit();
	}

	//발신번호 선택삭제 called from customer_list_content
	function selectSend(page) {
		if ($("input:checkbox[name='selCustomer']").is(":checked") == false) {
			$(".content").html("발신 번호를 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else {
			var form = document.createElement("form");
			var element0 = document.createElement("input");
			var element1 = document.createElement("input");

			form.method = "POST";
			form.action = "/biz/sender/send/friend";

			var recipientIds = [];
			$("input[name='selCustomer']:checked").each(function () {
				var id = $(this).val();
				recipientIds.push(id);
			});

			element0.value="<?=$this->security->get_csrf_hash()?>";
			element0.name="<?=$this->security->get_csrf_token_name()?>";
			element1.value=recipientIds;
			element1.name="receivers";
			form.appendChild(element0);
			form.appendChild(element1);

			document.body.appendChild(form);

			form.submit();
		}
	}

	//발신번호 선택삭제 called from customer_list_content
	function selectDelRow(page) {
		if ($("input:checkbox[name='selCustomer']").is(":checked") == false) {
			//$(".content").html("삭제할 수신 번호를 선택해주세요.");
			//$('#myModal').modal({backdrop: 'static'});
			alert("삭제할 수신 번호를 선택해주세요.");
			return;
		} else {
			/*$(".content").html("삭제하시겠습니까?");
			$("#myModalCheck").modal({backdrop: 'static'});
			$("#myModalCheck").unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 13) {        // enter-key
					$("#myModalCheck").modal("hide");
					//$(".submit").click();
				} else if (code == 27) { // esc-key
					$("#myModalCheck").modal("hide");
				}
			});
			$(".submit").click(function() {
				$("#myModalCheck").modal("hide");
				tel_select_del(page);
				$(".submit").unbind("click");
			});*/
			if(confirm("삭제 하시겠습니까?")){
				tel_select_del(page);
			}
	   }
	}

	//팝업창 선택 삭제
	function tel_select_del(page) {
		var customerIds = [];
		$("input[name='selCustomer']:checked").each(function () {
			var id = $(this).val();
			customerIds.push(id);
		});
		alert("정상적으로 삭제 되었습니다.");
		open_page(page, customerIds);
	}

	//전체 고객 삭제
	function allDelRow() {
		/*
		$(".content").html("등록된 전체 고객이 삭제됩니다.모두 삭제하시겠습니까?");
		$("#myModalCheck").modal({backdrop: 'static'});
		$("#myModalCheck").unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 13) {        // enter-key
				$("#myModalCheck").modal("hide");
				//$(".submit").click();
			} else if (code == 27) { // esc-key
				$("#myModalCheck").modal("hide");
			}
		});
		$(".submit").click(function() {
			$("#myModalCheck").modal("hide");
			tel_all_del();
			$(".submit").unbind("click");
		});
		*/
		if(confirm("등록된 전체 고객이 삭제됩니다.\n모두 삭제하시겠습니까?")){
			if(confirm("정말 삭제하시겠습니까?\n등록된 전체 고객이 삭제됩니다.")){
				tel_all_del();
			}
		}
	}

	function tel_all_del() {
		open_page(1, 'all');
		alert("정상적으로 삭제 되었습니다.");
	}

	//검색 조회
	function search_question(page) {
		   /*
		var searchFor = $('#searchStr').val();
		var searchGroup = $('#searchGroup').val();
		var searchName = $('#searchName').val();
		if (searchFor == "" && searchGroup=="" && searchName=="") {
			$(".content").html("검색할 내용을 입력해 주세요.");
			$('#myModal').modal({backdrop: 'static'});
			return;
		}
			*/
		open_page(page);
	}

	//조회
	function open_page(page, delIds){
		var add = $('#add').val();
		var type = $('#searchType').val();
		var searchFor = $('#searchStr').val();
		var searchGroup = $('#searchGroup').val();
		var searchName = $('#searchName').val();
		$('#searchGlv').val('');
		$('#searchGid').val('');
		// console.log('page:', page);
		$('.widget-content').html('').load(
			"/biz/customer/inc_lists",
			{
				"<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
				"add": add,
				"search_type": type,
				"search_for": searchFor,
				"search_group": searchGroup,
				"search_name": searchName,
				"del_ids[]": delIds,
				"page": page
			},
			function () {
				$(window).scrollTop(0);
				//$('.uniform').uniform();
				//$('select.select2').select2();
			}
		);
	}

	//그룹별 조회
	function group_search(glv, gid, gnm1, gnm2){
		var add = $('#add').val();
		$('#searchType').val('all');
		$('#searchStr').val('');
		$('#searchGroup').val('');
		$('#searchName').val('');
		$('#searchGlv').val(glv);
		$('#searchGid').val(gid);
		//alert("gnm1 : "+ gnm1 +", gnm2 : "+ gnm2);
		$('.widget-content').html('').load(
			"/biz/customer/inc_lists",
			{
				"<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
				"add": add,
				"glv": glv,
				"gid": gid,
				"add": "<?=$add?>",
				"page": 1
			},
			function () {
				//$(window).scrollTop(0);
				window.scroll(0, getOffsetTop(document.getElementById("div_title")));
				if(gnm1 == "N" && gnm2 == "N"){
				}else if(gnm1 != "" && gnm2 != ""){
					$("#div_title").html(" > "+ gnm1 +" > <span class='group_on'>"+ gnm2 +"</span>");
				}else if(gnm1 != "" && gnm2 == ""){
					$("#div_title").html(" > <span class='group_on'>"+ gnm1 +"</span>");
				}else{
					$("#div_title").html("");
				}
			}
		);
	}

	//추가수정 수신거부, 메모 기능
	function customer_modify(obj) {
		if(!confirm("수정 하시겠습니까?")){
			return;
		}
		$.ajax({
			url: "/biz/customer/modify",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				customer_id: $(obj).parent().parent().find("#customer_id").val().trim(),
				status_box: $(obj).parent().parent().find("#status_box").val().trim(),
				//kind: encodeURIComponent($(obj).parent().parent().find("#kind").val().trim()),
				addr: encodeURIComponent($(obj).parent().parent().find("#addr").val().trim()),
				memo: encodeURIComponent($(obj).parent().parent().find("#memo").val().trim())
			},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				if(!json['success']) {
					//$(".content").html("저장중 오류가 발생하였습니다.");
					//$("#myModal").modal('show');
					alert("수정중 오류가 발생하였습니다.");
				}else{
					alert("정상적으로 수정 되었습니다.");
				}
			},
			error: function (data, status, er) {
				//$(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
				//$("#myModal").modal('show');
				alert("처리중 오류가 발생하였습니다.\n관리자에게 문의해주시기 바랍니다.");
			}
		});
	}

	function chkword_addr(obj) {
		var limit_length = 150;
		var memo_length = obj.value.length;
		if (memo_length > limit_length) {
			var cont = obj.value;
			var cont_slice = cont.slice(0, 150);
			obj.value=cont_slice;
		}
	}

	function chkword_memo(obj) {
		var limit_length = 200;
		var memo_length = obj.value.length;
		if (memo_length > limit_length) {
			var cont = obj.value;
			var cont_slice = cont.slice(0, 200);
			obj.value=cont_slice;
		}
	}

	function chkword_kind(obj) {
		var limit_length = 15;
		var memo_length = obj.value.length;
		if (memo_length > limit_length) {
			var cont = obj.value;
			var cont_slice = cont.slice(0, 15);
			obj.value=cont_slice;
		}
	}

	//엡셀 업로드
	function excel_upload() {
		var file = document.getElementById('excel_file').value;
		var group_lv = $("#searchGlv").val();
		var group_id = $("#searchGid").val();
		file = file.slice(file.indexOf(".") + 1).toLowerCase();
		//alert("group_id : "+ group_id +", file : "+ file +", excel_file : "+ $("input[name=excel_file]")[0].files[0]); return;
		if (file == "xls" || file == "xlsx" || file == "csv"){
			var formData = new FormData();
			formData.append("group_id", group_id);
			formData.append("file", $("input[name=excel_file]")[0].files[0]);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			//alert("group_id : "+ group_id +", file : "+ file +", excel_file : "+ $("input[name=excel_file]")[0].files[0]); return;
			$.ajax({
				url: "/biz/customer/excel_upload",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					//$('#overlay').fadeIn();
				},
				complete: function () {
					//$('#overlay').fadeOut();
				},
				success: function (json) {
					var status = json['status'];
					//alert("status : "+ status);
					if (status == 'error') {
						var msg = json['msg'];
						$(".content").html("올바르지 않은 파일입니다.<br>"+msg);
						$('#myModal').modal({backdrop: 'static'});
						$("#excel_file").val("");
					} else if (status == 'success') {
						var upload_count = json['ok'];
						alert(upload_count +"건 업로드 되었습니다.");
						$("#excel_file").val("");
						group_search(group_lv, group_id, "N", "N");
						return;
					}
				}
            });
        }else{
			alert("엑셀(xls, xlsx) 파일만 가능합니다.");
			$("#excel_file").val("");
			return;
		}
    }

	//그룹이동
	function chg_group(){
		var newGid = $("#newGid").val();
		var group_lv = $("#searchGlv").val();
		var group_id = $("#searchGid").val(); 
		if(newGid == ""){
			alert("이동할 그룹을 선택해주세요.");
			$("#newGid").focus();
			return;
		}
		if ($("input:checkbox[name='selCustomer']").is(":checked") == false) {
			alert("그룹이동할 수신 번호를 선택해주세요.");
			return;
		} else {
			var orgGid = $("#searchGid").val();
			//alert("orgGid : "+ orgGid +", newGid : "+ newGid);
			var chkIds = [];
			$("input[name='selCustomer']:checked").each(function () {
				var id = $(this).val();
				chkIds.push(id);
			});
			//alert("orgGid : "+ orgGid +", newGid : "+ newGid +", chkIds : "+ chkIds);
			var formData = new FormData();
			formData.append("orgGid", orgGid); //이전 그룹번호
			formData.append("newGid", newGid); //변경 그룹번호
			formData.append("chkIds", chkIds); //선택한 고객번호
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			//alert("group_id : "+ group_id +", file : "+ file +", excel_file : "+ $("input[name=excel_file]")[0].files[0]); return;
			$.ajax({
				url: "/biz/customer/chg_group",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					//$('#overlay').fadeIn();
				},
				complete: function () {
					//$('#overlay').fadeOut();
				},
				success: function (json) {
					var status = json['status'];
					//alert("status : "+ status);
					if (status == 'error') {
						alert("오류로 처리되지 않았습니다.");
					} else if (status == 'success') {
						alert("그룹이동이 성공적으로 처리 되었습니다.");
						$("#newGid").val(""); //그룹이동 선택값 초기화
						group_search(group_lv, group_id, "N", "N");
					}
				},
				error: function (data, status, er) {
					alert("처리중 오류가 발생하였습니다.");
				}
            });
		}
	}
</script>
