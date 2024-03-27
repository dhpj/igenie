<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>결제내역 목록 (<?php echo element('total_rows', element('data', $view), 0); ?>건)</h3>
		</div>
		<div class="white_box">
			<?php
			echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
			echo form_open(current_full_url(), $attributes);
			?>
			<div class="box-table-header">
				<div class="fr">
    				<input type="text" class="datepicker" name="startDate" id="startDate" value="<?=$param['startDate'];?>" readonly="readonly"> ~
    				<input type="text" class="datepicker" name="endDate" id="endDate" value="<?=$param['endDate'];?>" readonly="readonly">
					<input type="button" class="btn_excel_down" id="excel_down" value="엑셀 내려받기" onclick="deposit_download()"/>&nbsp;&nbsp;&nbsp;&nbsp;
				전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
				<div class="btn-group btn-group-sm" role="group">
					<input type="hidden" id="depToType" value="<?=$this->input->get('dep_to_type')?>" />
					<input type="hidden" id="depPayType" value="<?=$this->input->get('dep_pay_type')?>" />
					<input type="hidden" id="preList" value="<?=$this->input->get('p')?>" />

					<a href="<?php echo '/biz/manager/deposit'; ?>" class="btn md <?php echo ( ! $this->input->get('dep_from_type') && ! $this->input->get('dep_to_type') && ! $this->input->get('p')) ? 'yellow' : '';?>">전체내역</a>
					<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit" class="btn md <?php echo ($this->input->get('dep_to_type') === 'deposit' && ! $this->input->get('dep_pay_type')) ? 'yellow' : '';?>">충전내역</a>
                    <a href="<?php echo '/biz/manager/deposit'; ?>?p=y" class="btn md <?php echo ($this->input->get('p') === 'y') ? 'yellow' : '';?>">선충전</a>
					<?/*php if ($this->input->get('dep_to_type') === 'deposit') { */?>
					<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=bank" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'bank') ? 'yellow' : '';?>">무통장</a>
					<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=card" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'card') ? 'yellow' : '';?>">카드</a>
					<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=realtime" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'realtime') ? 'yellow' : '';?>">실시간</a>
					<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=vbank" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'vbank') ? 'yellow' : '';?>">가상계좌</a>
					<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=phone" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'phone') ? 'yellow' : '';?>">핸드폰</a>
                    <a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=vcash&amp;dep_pay_type=voucher" class="btn md <?php echo ($this->input->get('dep_pay_type') === 'voucher') ? 'yellow' : '';?>">바우처</a>
					<!-- <a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=service" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'service') ? 'btn-info' : 'btn-default';?>">서비스</a>
					<a href="<?php echo '/biz/manager/deposit'; ?>?dep_to_type=deposit&amp;dep_pay_type=point" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'point') ? 'btn-info' : 'btn-default';?>">포인트결제</a>-->
					<?/*php } */?>
					<!--<a href="<?php echo '/biz/manager/deposit'; ?>?dep_from_type=deposit" class="btn btn-sm <?php echo ($this->input->get('dep_from_type') === 'deposit') ? 'btn-success' : 'btn-default';?>">사용내역</a>-->

				</div>

			<?php
			ob_start();
			?>
				<!--div class="" role="group" aria-label="...">
					<a href="<?php echo element('listall_url', $view); ?>" class="btn md">전체목록</a>
	<?/*if($_SERVER['REMOTE_ADDR']=='112.163.89.66') {? >
					<a href="< ?php echo element('write_url', $view); ? >" class="btn btn-outline btn-danger btn-sm">< ?php echo $this->depositconfig->item('deposit_name'); ? > 변동내역추가</a>
	< ?}*/?>
				</div-->
			<?php
			$buttons = ob_get_contents();
			ob_end_flush();
			?>
			</div>
			<table class="table_list mg_t10">
				<colgroup>
				<col width="5%" />
                <col width="8%" />
				<col width="15%" />
				<col width="7%" />
				<col width="15%" />
				<col width="7%" />
				<!-- <col width="9%" /> -->
				<col width="*" />
				<col width="7%" />
                <col width="7%" />
				<!-- <col width="9%" /> -->
				<col width="8%" />
				</colgroup>
				<thead>
				<tr>
					<th><a href="<?php echo element('dep_id', element('sort', $view)); ?>">번호</a></th>
                    <th>소속</th>
					<th>업체명</th>
					<th>입금자명</th>
					<th>구분</th>
					<th>일시</th>
					<!-- <th>결제</th> -->
					<th>내용</th>
<!--					<th><?php echo $this->depositconfig->item('deposit_name'); ?> 변동</th>-->
					<th>현금/카드</th>
                    <th>이벤트</th>
					<!-- <th>보너스<br/>포인트</th> -->
					<th>수정</th>
				</tr>
				</thead>
				<tbody>
			<?php
            $sTime = date("Y-m-d H:i:s", strtotime('2020-12-01 00:00:00')); // 시작 시간
			if (element('list', element('data', $view))) {
				foreach (element('list', element('data', $view)) as $result) {?>
				<tr>
					<td class="align-center"><?php echo number_format(element('num', $result)); ?></td>
                    <td class="align-center"><?=element('myadmin', $result)?></td>
					<td class="align-center"><?php echo html_escape(element('mem_username', $result)); ?></td>
					<td class="align-center"><?php echo element('mem_realname', $result); ?></td>
					<td>
                    <? $dTime = date("Y-m-d H:i:s", strtotime(element('dep_datetime', $result))); // 등록시간 ?>
                    <?php if (element('dep_deposit', $result) >= 0) { ?>
                        <?php if ((element('dep_pay_type', $result) == 'pservice' || strpos(element('dep_content', $result),"선충전")!==false || strpos(element('dep_admin_memo', $result),"선충전")!==false) && $dTime>$sTime){ ?>
                            <button type="button" class="btn btn-xs btn-primary" >+ 선충전</button>
                        <? }else{ ?>
                            <button type="button" class="btn btn-xs btn-primary" >충전</button>
                        <?} ?>
                    <?php } else { ?>
                        <?php if ((element('dep_pay_type', $result) == 'pservice' || element('dep_content', $result) == '예치금 선충전 차감'|| strpos(element('dep_content', $result),"선충전")!==false || strpos(element('dep_admin_memo', $result),"선충전")!==false) && $dTime>$sTime) { ?>
                            <button type="button" class="btn btn-xs btn-danger" >- 선충전</button>
                        <? }else{ ?>
                            <button type="button" class="btn btn-xs btn-danger" >사용</button>
                        <?} ?>
					<?php } ?>
					<?php echo element('dep_type_display', $result); ?>
					<?php if( element('is_test', $result) ){ ?>
					<span class="btn btn-xs btn-warning">테스트</span>
					<?php } ?>
					</td>
					<td class="align-center"><?php echo display_datetime(element('dep_datetime', $result), 'full'); ?></td>
					<!-- <td class="align-center"><?php echo element('dep_pay_type', $result) ? $this->depositlib->paymethodtype[element('dep_pay_type', $result)] : ''; ?></td> -->
					<td><?php echo nl2br(html_escape(element('dep_content', $result))); if(!empty(element('dep_admin_memo', $result))){ echo ' - '.nl2br(html_escape(element('dep_admin_memo', $result))); } ?></td>
					<!--<td class="text-right"><?php echo number_format(element('dep_deposit', $result) + element('amt_point', $result)); ?><?php echo $this->depositconfig->item('deposit_unit'); ?></td>-->
					<td class="text-right"><?php echo number_format(element('dep_deposit', $result)) . '원'; ?></td>
                    <td class="text-right"><?php if(element('service_amt', $result)>0){ echo number_format(element('service_amt', $result)) . '원';} ?></td>
					<!-- <td class="text-right"><?php echo number_format(element('amt_point', $result)); ?></td> -->
					<td><a href="<?php echo $this->pagedir; ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a>
                        <a href="/biz/main?<?=element('mem_id', $result)?>" class="btn btn-outline btn-default btn-xs">전환</a></td>
				</tr>
			<?php
				}
			}
			if ( ! element('list', element('data', $view))) {?>
				<tr>
					<td colspan="12" class="nopost">자료가 없습니다</td>
				</tr>
			<?php
				}?>
				</tbody>
			</table>
			<?//php echo element('paging', $view); ?>
			<div class="page_cen"><?=$page_html?></div>
			<div style="width:100%; margin-bottom:30px; display:inline-block;">
			<div class="pull-left ml20 fr"><?php echo admin_listnum_selectbox();?></div>
			<?php echo $buttons; ?>
			<?php echo form_close(); ?>
			<!--<form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">-->
				<div class="fl" style="">
					<div class="box-search">
							<select class="" name="sfield" id="sfield">
							<?php echo element('search_option', $view); ?>
							</select>
								<input type="text" class="" name="skeyword" id="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." onKeypress="if(event.keyCode==13){ open_page(1); }">
								<button class="btn btn-default btn-md" name="search_submit" type="button" onClick="open_page(1);">검색!</button>
					</div>
				</div>
			<!--</form>-->
		</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#nav li.nav100").addClass("current open");

	  // 2021-06-29 엑셀 다운로드 관련 시작
	  function getFormatDate(date){
	      var year = date.getFullYear();
	      var month = (1 + date.getMonth());
	      month = month >= 10 ? month : '0' + month;
	      var day = date.getDate();
	      day = day >= 10 ? day : '0' + day;
	      return year + '-' + month + '-' + day;
	  }

	  var date = new Date();
	  var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
	  var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

	  $('#startDate').datepicker({
	      format: "yyyy-mm-dd",
	      todayHighlight: true,
	      language: "kr",
	      autoclose: true,
	      //startDate: '-6m',
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
	      endDate: '+1m'
	  }).on('changeDate', function (selected) {
	      var endDate = new Date(selected.date.valueOf());
	      $('#startDate').datepicker('setEndDate', endDate);
	  });
	  $("#endDate").val(getFormatDate(lastDay));
	  var end = $("#endDate").val();

	  function deposit_download() {

	  	var dep_to_type = $('#depToType').val();
	  	var dep_pay_type = $('#depPayType').val();
	  	var start_date = $('#startDate').val();
	  	var end_date = $('#endDate').val();
	  	var pre_list = $('#preList').val();

	  	var form = document.createElement("form");
	  	document.body.appendChild(form);
	  	form.setAttribute("method", "post");
	  	form.setAttribute("action", "/biz/manager/deposit/deposit_download");

	  	var scrfField = document.createElement("input");
	  	scrfField.setAttribute("type", "hidden");
	  	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
	  	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
	  	form.appendChild(scrfField);

	  	var resultField = document.createElement("input");
	  	resultField.setAttribute("type", "hidden");
	  	resultField.setAttribute("name", "dep_to_type");
	  	resultField.setAttribute("value", dep_to_type);
	  	form.appendChild(resultField);

	  	var resultField = document.createElement("input");
	  	resultField.setAttribute("type", "hidden");
	  	resultField.setAttribute("name", "dep_pay_type");
	  	resultField.setAttribute("value", dep_pay_type);
	  	form.appendChild(resultField);

	  	var resultField = document.createElement("input");
	  	resultField.setAttribute("type", "hidden");
	  	resultField.setAttribute("name", "pre_list");
	  	resultField.setAttribute("value", pre_list);
	  	form.appendChild(resultField);
	  	
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

	//검색
	function open_page(page){
		var sfield = $("#sfield").val(); //검색타입
		var skeyword = $("#skeyword").val(); //검색내용
		var dep_to_type = "<?=$this->input->get('dep_to_type')?>"; //내역구분
		var dep_pay_type = "<?=$this->input->get('dep_pay_type')?>"; //내역구분
        var varp = "<?=$this->input->get('p')?>"; //내역구분
		var pram = "";
		if(sfield != "" && skeyword != "") pram += "&sfield="+ sfield +"&skeyword="+ skeyword;
		if(dep_to_type != "") pram += "&dep_to_type="+ dep_to_type;
		if(dep_pay_type != "") pram += "&dep_pay_type="+ dep_pay_type;
        if(varp != "") pram += "&p="+ varp;
		//alert("page : "+ page +", tagid : "+ tagid);
		location.href = "?page="+ page + pram;
	}
</script>
