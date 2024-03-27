<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>무통장입금목록</h3>
		</div>
		<div class="pendingbank_box">
			<div class="pdb_l">
				전체 : <span><?=number_format($total_rows)?></span> 건
				 / 전체합계 : <span><?if(!empty($tsum)){ echo number_format($tsum);}else{echo "0";}?></span> 원
			</div>
			<div class="pdb_r">
        <?
            $mon_cnt = 0;
            // echo count($monthlist);
           foreach($monthlist as $r) {
               $mon_cnt = $mon_cnt + 1;
               if($mon_cnt==1){
                   echo "";
               }
               echo "".$r->mon."<span>월</span>_".number_format($r->tsum)."<span>원</span>";
               if($mon_cnt != count($monthlist)){
                   echo "<span> / </span> ";
               }else{
                   echo "";
               }
            } ?>
					</div>
				</div>
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
		<div class="white_box">
            <div class="search_box search_period" id="status">
				<ul class="search_period">
					<a href="index"><li class="submit active">입금내역</li></a>
					<a href="/biz/manager/pendingbank/errlist"><li class="submit">미처리내역</li></a>
                    <? if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
                    <a href="/biz/manager/pendingbank/secondlist"><li class="submit">2차매칭</li></a>
                    <? } ?>
				</ul>
				<input type="hidden" id="set_date" name="set_date">
			</div>
            <div class="search_box" style="display:inline-block; width:100%;">
            <div class="fl">
                <? // 2022-04-06 세금계산서 발행 관련으로 추가 시작
    			if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
    			<select name="bill_down" id="bill_down">
    				<option value="A" <?=($bill_down=='A')? ' selected' : '' ?>>전체</option>
    				<option value="N" <?=($bill_down=='N')? ' selected' : '' ?>>Download 안함</option>
    				<option value="Y" <?=($bill_down=='Y')? ' selected' : '' ?>>Download 완료</option>
    			</select>
    			<select name="bill_issuer" id="bill_issuer">
    				<option value="A" <?=($bill_issuer=='A')? ' selected' : '' ?>>전체</option>
    				<option value="N" <?=($bill_issuer=='N')? ' selected' : '' ?>>미발행</option>
    				<option value="Y" <?=($bill_issuer=='Y')? ' selected' : '' ?>>발행</option>
    			</select>
    			<? } // 2022-04-06 세금계산서 발행 관련으로 추가 끝 ?>
        		<select name="date_type" id="date_type" style="display:none">
        			<option value="reg">요청일</option>
        			<option value="due">처리일</option>
        		</select>
        		<input type="text" class="datepicker" name="startDate" id="startDate" value="<?=$startDate?>" readonly="readonly"> ~
        		<input type="text" class="datepicker" name="endDate" id="endDate" value="<?=$endDate?>" readonly="readonly">
            </div>
    		<div class="fl mg_l20">
				<!--2021.07.30입금자명 검색 추가-->
				<div class="box-search">
						 <select class="" name="sfield" id="sfield">
                            <option value="1"  <?=($this->input->get("sfield")=='1')? ' selected' : '' ?>>입금자</option>
                            <option value="2"  <?=($this->input->get("sfield")=='2')? ' selected' : '' ?>>업체명</option>
						 	<!-- <?php echo element('search_option', $view); ?> -->
					   </select>
						 <input type="text" class="" name="skeyword" id="skeyword"  value="<?=$skeyword?>"  placeholder="Search for..." onkeypress="if(event.keyCode==13){ open_page(1); }">
						 <button class="btn md yellow" id="search_btn" name="search_submit" type="button" onclick="open_page(1);">검색</button>
             <a href="/biz/manager/pendingbank/" class="btn md" style="<?=(empty($skeyword))? 'display:none;' : 'display:inline-block;' ?> line-height:34px;">목록으로</a>
				</div>
				<!--//입금자명 검색 추가-->
				</div>
				<div class="fr">
    		<input type="button" style="border-radius:0;" class="btn_excel_down" id="excel_down" value="엑셀 다운로드" onclick="due_download()"/>
 			<? // 2022-04-06 세금계산서 발행 관련으로 추가 시작
			if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
    		<input type="button" class="btn_excel_down" id="taxbill_down" style="border-radius:0;" value="세금계산서" onclick="taxbill_download()"/>
    		<button class="btn md" id="taxbill_btn" name="taxbill_btn" type="button" onclick="bill_issuer_update()">발행처리</button>
    		<? } // 2022-04-06 세금계산서 발행 관련으로 추가 끝 ?>
    		</div>
            </div>
			<table class="table_list">
				<thead>
					<tr>
						<? // 2022-04-06 세금계산서 발행 관련으로 추가 시작
						if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
                    	<th class="align-center" width="30">
							<label class="checkbox_container">
								<input type="checkbox" name="check_all" id="row_check_all" onclick="bill_row_check_all();">
								<span class="checkmark"></span>
							</label>
						</th>
						<? } // 2022-04-06 세금계산서 발행 관련으로 추가 끝 ?>
						<th>No</th>
						<? // 2022-04-06 세금계산서 발행 관련으로 변경 시작
						if ($this->member->item('mem_userid') != 'dhnadmin' && $this->member->item('mem_userid') != 'dhn') { ?>
						<th>회원아이디</th>
						<? } // 2022-04-06 세금계산서 발행 관련으로 변경 끝 ?>
                        <th>소속</th>
						<th>업체명</th>
						<th>입금자명(사업자번호)</th>
						<th>연락처</th>
						<th>요청일시</th>
						<th>총결제해야할금액</th>
						<th>결제한금액</th>
						<th>미수금액</th>
						<? // 2022-04-06 세금계산서 발행 관련으로 추가 시작
						if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
						<th>세금계산서발행</th>
						<? } // 2022-04-06 세금계산서 발행 관련으로 추가 끝 ?>
						<!-- <? if($this->member->item('mem_level') >= 100) {?>
						<th>수정</th>
						<? } ?> -->
					</tr>
				</thead>
				<tbody>
				<?
				   $cnt = 0;
				   foreach($list as $r) {
					   $num = $total_rows-($perpage*($param['page']-1))-$cnt;
					   $cnt = $cnt + 1;
				?>
					<tr class="<?php if ($r->dep_status == '1') {echo 'success';}?>">
                    	<? // 2022-04-06 세금계산서 발행 관련으로 추가 시작
						if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
                    	<td class="align-center">
                    		<? if ($r->taxbill_state == "N") { ?>
							<label class="checkbox_container">
								<input type="checkbox" name="row_check" value="<?=$r->dep_id.",".$r->taxbill_down ?>" onclick="bill_row_check();">
								<span class="checkmark"></span>
							</label>
							<? } else { ?>
							&nbsp;
							<? } ?>
						</td>
                    	<? } // 2022-04-06 세금계산서 발행 관련으로 추가 끝 ?>
						<td><?=$num?></td>
						<? // 2022-04-06 세금계산서 발행 관련으로 변경 시작
						if ($this->member->item('mem_userid') != 'dhnadmin' && $this->member->item('mem_userid') != 'dhn') { ?>
						<td><?=$r->mem_userid?></td>
						<? } // 2022-04-06 세금계산서 발행 관련으로 변경 끝 ?>
                        <td><?=$r->adminname?></td>
						<td><?=$r->mem_username?></td>
						<td><?=$r->mem_realname?></td>
						<td><?=$r->mem_phone?></td>
						<td><?=display_datetime($r->dep_datetime, 'full')?></td>
						<td class="align-right"><?=number_format($r->dep_cash_request).'원'?></td>
						<td class="align-right"><?=number_format($r->dep_cash). '원'?></td>
						<td class="align-right"><?=number_format($r->dep_cash_request - abs($r->dep_cash)).'원'?></td>
						<? // 2022-04-06 세금계산서 발행 관련으로 추가 시작
						if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
						<td><span id="bill_<?=$r->dep_id?>"><?=($r->taxbill_state == "Y") ? "발행" : "미발행" ?><?=($r->taxbill_down == "Y") ? "(다운로드완료)" : ""?></span></td>
						<? } // 2022-04-06 세금계산서 발행 관련으로 추가 끝 ?>
						<? if($this->member->item('mem_level') >= 100) {?>
						<!-- <td><?php if ( !$r->dep_status){ ?><a href="<?php echo $this->pagedir; ?>/write/<?=$r->dep_id?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a><?php } ?></td> -->
						<? } ?>
					</tr>
				<?php
					}

					if ( $cnt == 0) {
				?>
					<tr>
						<td colspan="<?=($this->member->item('mem_level') >= 100) ? "11" : "10"?>" class="nopost">자료가 없습니다</td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
			<div class="tc"><?=$page_html?></div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
<? // 2022-04-06 세금계산서 발행 관련으로 추가 시작
if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
    function bill_row_check_all(){
        if($("#row_check_all").prop("checked")) {
            $("input:checkbox[name='row_check']").prop("checked",true);
        } else {
            $("input:checkbox[name='row_check']").prop("checked",false);
        }
        //$.uniform.update();
    }

    function bill_row_check() {
        var row_check_count = $("input:checkbox[name='row_check']").length;
        var row_checked_count = $("input:checkbox[name='row_check']:checked").length;

        if (row_check_count === row_checked_count) {
        	$("#row_check_all").prop("checked", true);
        } else {
        	$("#row_check_all").prop("checked", false);
       	}
        //$.uniform.update();
    }

	function bill_row_check_value(str_separator) {
		var row_check_value = "";

		$("input:checkbox[name='row_check']:checked").each(function() {
			var arr = $(this).val().split(",");
			row_check_value += arr[0] + str_separator;
			//row_check_value += $(this).val() + str_separator;
		});
		return row_check_value.slice(0, -1);
	}

	function bill_excel_download_check() {
		var check_result = true;

		$("input:checkbox[name='row_check']:checked").each(function() {
			var arr = $(this).val().split(",");
			// alert(arr[1]);
			if (arr[1] == "N") {
				check_result = false;
			}
		});

		return check_result;
	}

	function bill_issuer_update() {
		var rowCount = <?=$total_rows ?>;

		if (rowCount < 1) {
			 //$(".content").html("조회된 정산 자료가 없습니다.");
			 //$("#myModal").modal('show');
			 alert("조회된 무통장입금 목록이 없습니다.");
			 return;
	 	} else {
	 		if ($("input:checkbox[name='row_check']:checked").length < 1) {
				 //$(".content").html("선택된 업체가 없습니다.");
				 //$("#myModal").modal('show');
				alert("선택된 무통장입금 항목이 없습니다."); return;
	 	 	} else {
		 	 	var checked_len = $("input:checkbox[name='row_check']:checked").length;
				var checked_value = bill_row_check_value(",");
				var download_check = bill_excel_download_check();

				alert(download_check);
				if (download_check) {
					// alert(checked_value);

					var result_confirm = confirm(checked_len + "건을 세금계산서 발행처리를 하시겠습니까?");

					if (result_confirm) {
						$.ajax({
							 url: "/biz/manager/pendingbank/bill_issuer_update",
							 type: "POST",
							 data: {
								  <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
								  oids: checked_value
							 },
							 beforeSend: function () {
								  //$('#overlay').fadeIn();
							 },
							 complete: function () {
								  //$('#overlay').fadeOut();
							 },
							 success: function (json) {
								if(!json['success']) {
									alert("저장중 오류가 발생하였습니다.");
								}else{
									alert(checked_len + "건이 발행처리 되었습니다.");
									location.reload();
								}
							 },
							 error: function (data, status, er) {
								  alert("처리중 오류가 발생하였습니다.");
							 }
						});
					}
				} else {
					alert("세금계산서 엑셀파일은 다운로드하지 않은 항목이 선택되었습니다.");
				}
	 	 	}
	 	}
	}

 	//무통장입금 세금계산서 엑셀 다운로드
	function taxbill_download() {
 	 	var rowCount = <?=$total_rows ?>;
 	 	if (rowCount < 1) {
			 //$(".content").html("조회된 정산 자료가 없습니다.");
			 //$("#myModal").modal('show');
			 alert("조회된 무통장입금 목록이 없습니다."); return;
 	 	} else {
 	 	 	if ($("input:checkbox[name='row_check']:checked").length < 1) {
 				 //$(".content").html("선택된 업체가 없습니다.");
 				 //$("#myModal").modal('show');
				alert("선택된 무통장입금 항목이 없습니다."); return;
 	 	 	} else {
				var checked_value = bill_row_check_value(",");
// 				var now_link = document.location.href;
// 				var now_link_para = now_link.split("?");

// 				alert(now_link);
// 				alert(now_link_para);
// 				return;

                $.ajax({
                    url: '/biz/manager/pendingbank/download_taxbill',
                    type: "post",
                    data: {
                        "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                      , "oids" : checked_value
                    },
                    dataType:'json',
                }).done(function (data){
                    var a_dump = $("<a>");
                    a_dump.attr("href",data.file);
                    $("body").append(a_dump);
                    a_dump.attr("download","test.xlsx");
                    a_dump[0].click();
                    a_dump.remove();

					$.ajax({
						 url: "/biz/manager/pendingbank/bill_down_update",
						 type: "POST",
						 data: {
							  <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
							  oids: checked_value
						 },
						 beforeSend: function () {
							  //$('#overlay').fadeIn();
						 },
						 complete: function () {
							  //$('#overlay').fadeOut();
						 },
						 success: function (json) {
							if(!json['success']) {
								alert("저장중 오류가 발생하였습니다.");
							}else{
								// alert(checked_len + "건이 발행처리 되었습니다.");
								location.reload();
							}
						 },
						 error: function (data, status, er) {
							  alert("처리중 오류가 발생하였습니다.");
						 }
					});
                });


                // var form = document.createElement("form");
                // document.body.appendChild(form);
                // form.setAttribute("method", "post");
                // form.setAttribute("action", "/biz/manager/pendingbank/download_taxbill");
                // var scrfField = document.createElement("input");
                // scrfField.setAttribute("type", "hidden");
                // scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
                // scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
                // form.appendChild(scrfField);
                //
                // var valueField = document.createElement("input");
                // valueField.setAttribute("type", "hidden");
                // valueField.setAttribute("name", "oids");
                // valueField.setAttribute("value", checked_value);
                // form.appendChild(valueField);
                //
                // var valueField = document.createElement("input");
                // valueField.setAttribute("type", "hidden");
                // valueField.setAttribute("name", "oids");
                // valueField.setAttribute("value", checked_value);
                // form.appendChild(valueField);
                //
                // var valueField = document.createElement("input");
                // valueField.setAttribute("type", "hidden");
                // valueField.setAttribute("name", "uri");
                // valueField.setAttribute("value", now_link);
                // form.appendChild(valueField);
                //
                // form.submit();


                // $("input:checkbox[name='row_check']").prop("checked",false);
 	 	 	}
 	 	}
 	}

 	//무통장입금 세금계산서 엑셀 다운로드
	function taxbill_download__() {
 	 	var rowCount = <?=$total_rows ?>;
 	 	if (rowCount < 1) {
			 //$(".content").html("조회된 정산 자료가 없습니다.");
			 //$("#myModal").modal('show');
			 alert("조회된 무통장입금 목록이 없습니다."); return;
 	 	} else {
 	 	 	if ($("input:checkbox[name='row_check']:checked").length < 1) {
 				 //$(".content").html("선택된 업체가 없습니다.");
 				 //$("#myModal").modal('show');
				alert("선택된 무통장입금 항목이 없습니다."); return;
 	 	 	} else {
				var checked_value = bill_row_check_value(",");

				$.ajax({
					 url: "/biz/manager/pendingbank/download_taxbill",
					 type: "POST",
					 data: {
						  <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
						  oids: checked_value
					 },
					 beforeSend: function () {
						  //$('#overlay').fadeIn();
					 },
					 complete: function () {
						  //$('#overlay').fadeOut();
					 },
					 success: function (json) {
						if(!json['success']) {
							alert("세금계산서 엑셀 다운로드중 오류가 발생하였습니다.");
						}else{
							location.reload();
						}
					 },
					 error: function (data, status, er) {
						  alert("처리중 오류가 발생하였습니다.");
					 }
				});


 	 	 	}
 	 	}
 	}
<? } // 2022-04-06 세금계산서 발행 관련으로 추가 끝 ?>


    //2021-06-29 엑셀 다운로드 관련 시작
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
    // if(sdate!=""){
    //     firstDay = sdate;
    // }
    //
    // if(edate!=""){
    //     lastDay = edate;
    // }


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
 	// 2022-04-06 세금계산서 발행 관련으로 한줄 주석
    //$("#startDate").val(getFormatDate(firstDay));
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
    // 2022-04-06 세금계산서 발행 관련으로 한줄 주석
    //$("#endDate").val(getFormatDate(lastDay));
    var end = $("#endDate").val();

    function due_download() {

    	var type = $('#date_type').val();
    	var start_date = $('#startDate').val();
    	var end_date = $('#endDate').val();

    	var form = document.createElement("form");
    	document.body.appendChild(form);
    	form.setAttribute("method", "post");
    	form.setAttribute("action", "/biz/manager/pendingbank/due_download");

    	var scrfField = document.createElement("input");
    	scrfField.setAttribute("type", "hidden");
    	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
    	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    	form.appendChild(scrfField);

    	var kindField = document.createElement("input");
    	kindField.setAttribute("type", "hidden");
    	kindField.setAttribute("name", "search_type");
    	kindField.setAttribute("value", type);
    	form.appendChild(kindField);

    	var resultField = document.createElement("input");
    	resultField.setAttribute("type", "hidden");
    	resultField.setAttribute("name", "start_date");
    	resultField.setAttribute("value", start_date);
    	form.appendChild(resultField);

    	var resultField = document.createElement("input");
    	resultField.setAttribute("type", "hidden");
    	resultField.setAttribute("name", "end_date");
    	resultField.setAttribute("value", end_date);
    	form.appendChild(resultField);

    	form.submit();
    }
  	//2021-06-29 엑셀 다운로드 관련 끝

		// //검색
		// function open_page(page){
		// 	var sfield = $("#sfield").val(); //검색타입
		// 	var skeyword = $("#skeyword").val(); //검색내용
		// 	var form = document.createElement("form");
    	// document.body.appendChild(form);
    	// form.setAttribute("method", "post");
    	// form.setAttribute("action", "/biz/manager/pendingbank?page="+page);
        //
    	// var scrfField = document.createElement("input");
    	// scrfField.setAttribute("type", "hidden");
    	// scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
    	// scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    	// form.appendChild(scrfField);
        //
    	// var kindField = document.createElement("input");
    	// kindField.setAttribute("type", "hidden");
    	// kindField.setAttribute("name", "sfield");
    	// kindField.setAttribute("value", sfield);
    	// form.appendChild(kindField);
        //
    	// var resultField = document.createElement("input");
    	// resultField.setAttribute("type", "hidden");
    	// resultField.setAttribute("name", "skeyword");
    	// resultField.setAttribute("value", skeyword);
    	// form.appendChild(resultField);
		// 	form.submit();
		// }
        //검색
    	function open_page(page){
    		var sfield = $("#sfield").val(); //검색타입
    		var skeyword = $("#skeyword").val(); //검색내용
    		var dep_to_type = "<?=$this->input->get('dep_to_type')?>"; //내역구분
    		var dep_pay_type = "<?=$this->input->get('dep_pay_type')?>"; //내역구분
            var start_date = $('#startDate').val();
        	var end_date = $('#endDate').val();

        	<? // 2022-04-06 세금계산서 발행 관련으로 추가 시작
        	if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
        	var bill_issuer = $("#bill_issuer").val();
        	var bill_down = $("#bill_down").val();
        	<? } // 2022-04-06 세금계산서 발행 관련으로 추가 끝 ?>

    		var pram = "";
    		if(sfield != "" && skeyword != "") pram += "&sfield="+ sfield +"&skeyword="+ skeyword;
    		if(dep_to_type != "") pram += "&dep_to_type="+ dep_to_type;
    		if(dep_pay_type != "") pram += "&dep_pay_type="+ dep_pay_type;
            if(start_date != "") pram += "&startDate="+ start_date;
            if(end_date != "") pram += "&endDate="+ end_date;
        	<? // 2022-04-06 세금계산서 발행 관련으로 추가 시작
            if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
            if(bill_issuer != "") pram += "&issuer="+bill_issuer;
            if(bill_down != "") pram += "&down="+bill_down;
            <? } // 2022-04-06 세금계산서 발행 관련으로 추가 끝 ?>
    		//alert("page : "+ page +", tagid : "+ tagid);
    		location.href = "?page="+ page + pram;
    	}


</script>
