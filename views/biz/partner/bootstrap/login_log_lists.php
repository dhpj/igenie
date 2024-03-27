<!-- 타이틀 영역 -->
<div class="tit_wrap">
	로그인 현황
</div>
<!-- 타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>로그인 현황 (전체 <b style="color: red" id="total_rows">0</b>개)</h3>
			<!-- <button class="btn_tr" onclick="location.href='/biz/partner/write'">파트너 등록하기</button> -->
		</div>
		<div class="white_box" id="search_box">
			<div class="search_box">
				<label for="userid">소속 </label>
				<select name="userid" id="userid" <?//class="selectpicker"?> data-live-search="true" onChange="search_question(1);">
					<option value="ALL">- ALL -</option>
				<?foreach($users as $r) {?>
					<option value="<?=$r->mem_id?>" <?=($param['userid']==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
				<?}?>
				</select>
                <span style="margin-left:10px;">휴면상태 </span>
				<select class="select2 input-width-medium" id="dormancy" onChange="search_question(1);">
					<option value="ALL"<?=$dormancy=='ALL' ? 'selected' : ''?>>- ALL -</option>
					<option value="N" <?=$dormancy=='N' ? 'selected' : ''?>>정상</option>
					<option value="Y" <?=$dormancy=='Y' ? 'selected' : ''?>>휴면</option>
				</select>
                <span style="margin-left:10px;">바우처 </span>
                <select class="select2 input-width-medium" id="voucher" onChange="search_question(1);">
					<option value="ALL"<?=$voucher=='ALL' ? 'selected' : ''?>>- ALL -</option>
					<option value="N" <?=$voucher=='N' ? 'selected' : ''?>>일반</option>
					<option value="Y" <?=$voucher=='Y' ? 'selected' : ''?>>바우처</option>
				</select>
                <span style="margin-left:10px;">날짜 </span>
                <input type="text" class="datepicker" name="startDate" id="startDate" value="<?=$startDate?>" readonly="readonly" style="width:100px;"> ~
        		<input type="text" class="datepicker" name="endDate" id="endDate" value="<?=$endDate?>" readonly="readonly" style="width:100px;">
        		<input type="button" class="btn_excel_down fr" id="excel_down" value="엑셀 다운로드" onclick="due_download()"/>
                <!-- <input type="text" onChange="search_question(1);" class="datepicker" name="startDate" id="selectDate" value="<?=$selectdate?>" readonly="readonly" placeholder="날짜를 선택하세요"> -->


				<select class="select2 input-width-medium" id="searchType" style="margin-left:10px;">
					<option value="1" <?=$search_type=='1' ? 'selected' : ''?>>업체명</option>
					<option value="2" <?=$search_type=='2' ? 'selected' : ''?>>로그내용</option>
					<option value="3" <?=$search_type=='3' ? 'selected' : ''?>>아이피</option>
				</select>
				<input type="text" class="" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$param['search_for']?>" onKeypress="if(event.keyCode==13){ search_question(1); }">
				<input type="button" class="btn md" id="check" value="조회" onclick="search_question(1)"/>
                <a href="/biz/partner/login_log_lists/" class="btn md fr" style="<?=(empty($search_for)&&empty($selectdate))? 'display:none;' : 'display:inline-block;' ?> line-height:34px;">목록으로</a>

			</div>
			<div class="widget-content">

                <table>
                	<colgroup>

                		<col width="13%"><?//접속시간?>
                        <col width="5%"><?//상품?>
                        <col width="10%"><?//소속?>
                		<col width="6%"><?//계정?>
                		<col width="10%"><?//업체명?>
                        <col width="8%"><?//사업자번호?>
                		<col width="8%"><?//아이피?>
                        <col width="10%"><?//접속경로?>
                		<col width="*"><?//로그내용?>
                        <col width="*"><?//로그아웃?>
                		<col width="5%"><?//휴면?>

                	</colgroup>
                	<thead>
                	<tr>
                		<th>접속시간</th>
                        <th>상품</th>
                        <th>소속</th>
                		<th>계정(ID)</th>
                		<th>업체명</th>
                        <th>사업자번호</th>
                		<th>아이피</th>
                        <th>접속경로 / 이전페이지</th>
                		<th>로그내용</th>
                        <th>로그아웃</th>
                		<th>휴면상태</th> <!-- 2021.12.30 조지영 휴면계정 노출 작업 -->
                	</tr>
                	</thead>
                	<tbody>
                		<?
                			$offer = "";
                			foreach($rs as $row) {
                		?>
                		<tr>

                			<!-- <td><?if($offer==$row->mrg_recommend_mem_id) { echo '&nbsp;'; } else { echo $row->mrg_recommend_mem_username; $offer=$row->mrg_recommend_mem_id; }?></td><?//소속?> -->
                			<td><?=$row->mll_datetime?></td><?//접근시간?>
                            <td>지니</td><? //소속 ?>
                            <td><?=$row->adminname?></td><? //소속 ?>
                			<td><?=$row->mem_userid?></td><?//계정?>
                			<td><?=$row->mem_username?></td><?//업체명?>
                            <td><?
                            $biz_text = "";
                            if(!empty($row->mem_biz_reg_no)){
                                if(strlen($row->mem_biz_reg_no)==10){
                                    $biz_text = substr($row->mem_biz_reg_no, 0, 3)."-".substr($row->mem_biz_reg_no, 3, 2)."-".substr($row->mem_biz_reg_no, 5, 5);
                                    echo $biz_text;
                                }
                            }
                            ?></td><?//사업자번호?>
                            <td><?=$row->mll_ip?></td><?//아이피?>
                            <td><?=$row->mll_url?>
                                <? if(!empty($row->mll_referer)){ ?>
                                <br/><?=$row->mll_referer?>
                                <? } ?>
                            </td>
                            <?  $turm_string="";
                            if(!empty($row->logout_log)){
                                $turm_time = strtotime(date($row->logout_log)." GMT") - strtotime(date($row->mll_datetime)." GMT");
                                if($trum_time > 3600){
                                    $turm_string = date('H시 i분 s초', $turm_time);
                                }else{
                                    $turm_string = date('i분 s초', $turm_time);
                                }

                             } ?>
                			<td><?=($row->mll_success=='1')? "<span style='color:blue;'>[성공]</span> " : "<span style='color:red;'>[실패]</span> "?><?=$row->mll_reason?><?=(!empty($row->logout_log))? "<br/>".$turm_string  : ""?></td><?//로그내용?>
                            <td><?=(!empty($row->logout_log))? $row->logout_log : ""?></td><?//로그아웃?>

                			<td><?=!empty($row->dormant_mem_id) ? ($row->mdd_dormant_flag == 1 ? "휴면" : "정상" ): "정상" ?></td>
                		</tr>
                		<?
                			}
                		?>
                	</tbody>
                </table>
                <input type="file" name="filecount" id="filecount" multiple onchange="readURL();" style="cursor: default; padding: 20px; width: 100px;display:none">
                </div>
                <div class="page_cen"><?echo $page_html?></div>
                <script>
                	$("#total_rows").html("<?=number_format($total_rows)?>");
                </script>
            </div>
		</div>
	</div>
</div>
    <style>
        .text-center {
            vertical-align: middle !important;
        }

        .text-left {
            vertical-align: middle !important;
        }
    </style>

    <script type="text/javascript">

    function getFormatDate(date){
        date = new Date(date);
        var year = date.getFullYear();
        var month = (1 + date.getMonth());
        month = month >= 10 ? month : '0' + month;
        var day = date.getDate();
        day = day >= 10 ? day : '0' + day;
        return year + '-' + month + '-' + day;
    }

    var date = new Date();
    var sdate = "<?=$startDate?>";
    var edate = "<?=$endDate?>";
    var firstDay = new Date(date.getFullYear(), date.getMonth() - 2, 1);
    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    if(sdate!=""){
        firstDay = sdate;
    }

    if(edate!=""){
        lastDay = edate;
    }


    $('#startDate').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        language: "kr",
        autoclose: true,
        startDate: '-3m',
        //endDate: '-1d'
    }).on('changeDate', function (selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#endDate').datepicker('setStartDate', startDate);
    });
    $("#startDate").val(getFormatDate(firstDay));
    var start = $("#startDate").val();

    $('#endDate').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        language: "kr",
        autoclose: true,
        startDate: start,
        endDate: '+3m'
    }).on('changeDate', function (selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#startDate').datepicker('setEndDate', endDate);
    });
    $("#endDate").val(getFormatDate(lastDay));
    var end = $("#endDate").val();

    function due_download() {

    	var uid = $('#userid').val();
        var type = $('#searchType').val();
        var searchFor = $('#searchStr').val();
        // var selectdate = $('#selectDate').val();
        var dormancy = $('#dormancy').val(); //휴면 상태 체크
        var voucher = $('#voucher').val();
        var start_date = $('#startDate').val();
        var end_date = $('#endDate').val();

    	var form = document.createElement("form");
    	document.body.appendChild(form);
    	form.setAttribute("method", "post");
    	form.setAttribute("action", "/biz/partner/due_download");

    	var scrfField = document.createElement("input");
    	scrfField.setAttribute("type", "hidden");
    	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
    	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    	form.appendChild(scrfField);

        var resultField = document.createElement("input");
    	resultField.setAttribute("type", "hidden");
    	resultField.setAttribute("name", "type");
    	resultField.setAttribute("value", type);
    	form.appendChild(resultField);

        var resultField = document.createElement("input");
    	resultField.setAttribute("type", "hidden");
    	resultField.setAttribute("name", "searchFor");
    	resultField.setAttribute("value", searchFor);
    	form.appendChild(resultField);

        var resultField = document.createElement("input");
    	resultField.setAttribute("type", "hidden");
    	resultField.setAttribute("name", "dormancy");
    	resultField.setAttribute("value", dormancy);
    	form.appendChild(resultField);

        var resultField = document.createElement("input");
    	resultField.setAttribute("type", "hidden");
    	resultField.setAttribute("name", "voucher");
    	resultField.setAttribute("value", voucher);
    	form.appendChild(resultField);

    	var resultField = document.createElement("input");
    	resultField.setAttribute("type", "hidden");
    	resultField.setAttribute("name", "startDate");
    	resultField.setAttribute("value", start_date);
    	form.appendChild(resultField);

    	var resultField = document.createElement("input");
    	resultField.setAttribute("type", "hidden");
    	resultField.setAttribute("name", "endDate");
    	resultField.setAttribute("value", end_date);
    	form.appendChild(resultField);

    	form.submit();
    }


		$(document).ready(function() {
			// open_page(1);
		});

        $('.searchBox input').unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                open_page(1);
            }
        });

        $("#nav li.nav60").addClass("current open");

        $(document).ajaxStart(function(){
            $("#wait").css("display", "block");
        });

        $(document).ajaxComplete(function(){
            $("#wait").css("display", "none");
        });

        //검색 조회
        function search_question(page) {
            open_page(page);
        }

        //검색
		// function open_page(page) {
        //     var uid = $('#userid').val();
        //     var contract_type = $('#contract_type').val(); //계약형태
        //     var monthly_fee_yn = $('#monthly_fee_yn').val(); //월사용료 2021-02-09 추가
        //     var type = $('#searchType').val();
		// 				var searchFor = $('#searchStr').val();
		// 				var dormancy_yn = $('#dormancy_yn').val(); //휴면 상태 체크
        //
        //     $('.widget-content').html('').load(
        //        "/biz/partner/inc_lists",
		// 			{
		// 				"<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
		// 				"uid": uid,
		// 				"contract_type": contract_type,
		// 				"monthly_fee_yn": monthly_fee_yn, //월사용료 2021-02-09 추가
		// 				"search_type": type,
		// 				"search_for": searchFor,
		// 				"dormancy_yn" : dormancy_yn, //휴면유무 2021.12.30 추가
		// 				'page': page
		// 			}
        //     );
		// 	if(page != "" && page != "1"){
		// 		window.scroll(0, getOffsetTop(document.getElementById("search_box"))); //검색박스 위치 이동
		// 	}else{
		// 		$(window).scrollTop(0); //상단 위치 이동
		// 	}
        // }

        //검색
    	function open_page(page){
            var uid = $('#userid').val();
            var type = $('#searchType').val();
    		var searchFor = $('#searchStr').val();
            // var selectdate = $('#selectDate').val();
    		var dormancy = $('#dormancy').val(); //휴면 상태 체크
            var voucher = $('#voucher').val(); //바우처 체크
            var start_date = $('#startDate').val();
        	var end_date = $('#endDate').val();

    		var pram = "";
            if(uid != "") pram += "&uid="+ uid;
            // if(selectdate != "") pram += "&selectdate="+ selectdate;
            if(dormancy != "") pram += "&dormancy="+ dormancy;
            if(voucher != "") pram += "&voucher="+ voucher;
    		if(type != "" && searchFor != "") pram += "&search_type="+ type +"&search_for="+ searchFor;
            if(start_date != "") pram += "&startDate="+ start_date;
            if(end_date != "") pram += "&endDate="+ end_date;
            // if(varp != "") pram += "&p="+ varp;
    		//alert("page : "+ page +", tagid : "+ tagid);
    		location.href = "?page="+ page + pram;
    	}


    </script>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content identify">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">
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
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-primary customer_save" data-dismiss="modal" onclick="customer_save();">
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
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default dismiss" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary submit">확인</button>
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
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary all" onclick="all_move();">확인</button>
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
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary del">확인</button>
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
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary selDel">확인</button>
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
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary temp">확인</button>
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
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary up">확인</button>
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
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary down">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //업로드
    var up_mem_id = -1;
    var up_mem_userid = "";

    function btn_upload_click(_mem_id, _mem_userid) {
        up_mem_id = _mem_id;
        up_mem_userid  = _mem_userid;
        var real = $("#real" + up_mem_id).val();
        if(real == 0) {
            $("#filecount").click();
        } else {
            readURL();
        }
    }

    //고객삭제
	function btn_delete_click(_mem_id, _mem_userid) {
    	 if(confirm("고객 리스트 전체를 삭제 하시겠습니까?")){
	        var formData = new FormData();
	    	formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
		    formData.append("mem_id",_mem_id);
	        formData.append("mem_userid",_mem_userid);
	        $.ajax({
	            url: "/biz/customer/deletebylist",
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
					alert("정상적으로 삭제 되었습니다.");
				}
	        });
    	 }

    }

    function readURL() {
        var file = document.getElementById('filecount').value;
        file = file.slice(file.indexOf(".") + 1).toLowerCase();
        var real = $("#real" + up_mem_id).val();

        if (file == "xls" || file == "xlsx" || file == "csv") {
			//var formUrl = "/biz/customer/uploadbylist";
			var formUrl = "/biz/customer/upload";
			var formData = new FormData();
            formData.append("file", $("input[name=filecount]")[0].files[0]);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			formData.append("real", real);
			formData.append("mem_id",up_mem_id);
			formData.append("mem_userid",up_mem_userid);
			//alert("formUrl : "+ formUrl); return;
            $.ajax({
                url: formUrl,
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
                    var status = json['status'];
                    if (status == 'error') {
                        var msg = json['msg'];
                        $(".content").html("올바르지 않은 파일입니다.<br>"+msg);
                        $('#myModal').modal({backdrop: 'static'});
                        $('#filecount').filestyle('clear');
                    } else if (status == 'success') {
                        //var upload_count = json['nrow_len'];
                        var upload_count = json['uploaded'];
                        if(real == 0) {
                            //$("#number_add").hide();
                            //$("#number_del").hide();
                            //$("#tel").hide();
                            //$("#upload_result").remove();
                            $("#btn_upload" + up_mem_id).html(upload_count + "건 등록");
                            $("#real" + up_mem_id).val("1");
                        } else {
                            alert("고객등록 완료 되었습니다.");
                            $("#btn_upload" + up_mem_id).html("고객등록");
                            $("#real" + up_mem_id).val("0");
                        }
                        //$(".tel_content").after('<div class="widget-content" style="margin-top:-10px;" id="upload_result"><p>업로드 결과 : ' + upload_count + ' 명의 고객이 업로드 되었습니다.</p><input type="hidden" id="upload_all_send" value="' + upload_count + '"></div>');
                    }
                }
            });
        } else {
            $(".content").html("xls,xlsx 파일만 가능합니다.");
            $('#myModal').modal({backdrop: 'static'});
            $('#myModal').on('hidden.bs.modal', function () {
                $('#filecount').filestyle('clear');
            });
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".enter").click();
                }
            });
        }
    }
</script>
