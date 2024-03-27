<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu6.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>무통장미처리목록</h3>
		</div>
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
		<div class="white_box">
			<div class="fr">
			<input type="text" class="datepicker" name="startDate" id="startDate" value="<?=$param['startDate'];?>" readonly="readonly"> ~
			<input type="text" class="datepicker" name="endDate" id="endDate" value="<?=$param['endDate'];?>" readonly="readonly">
			<input type="button" class="btn_excel_down" id="excel_down" value="엑셀 내려받기" onclick="unprocessed_download()"/>
			</div>
			<div class="search_box search_period" id="status">
				<ul class="search_period">
					<a href="index"><li class="submit">입금내역</li></a>
					<a href="errlist"><li class="submit active">미처리내역</li></a>
                    <? if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
                    <a href="/biz/manager/pendingbank/secondlist"><li class="submit">2차매칭</li></a>
                    <? } ?>
				</ul>
				<input type="hidden" id="set_date" name="set_date">
				<span>전체 : <?=number_format($total_rows)?>건</span>
			</div>
			<table class="table_list">
				<thead>
					<tr>
						<th>No</th>
						<th>입금자명(사업자번호)</th>
						<th>요청일시</th>
						<th>결제한금액</th>
						<th>미처리금액</th>
						<? if($this->member->item('mem_level') >= 100) {?>
						<th>수정</th>
						<? } ?>
					</tr>
				</thead>
				<tbody>
				<?
				   $cnt = 0;
				   foreach($list as $r) {
					   $num = $total_rows-($perpage*($param['page']-1))-$cnt;
					   $cnt = $cnt + 1;
				?>
					<tr class="<?php if ($r->dep_status == '1') {echo 'success';}?>" <?=($r->state=="M")? " style='background-color:#ffcac6;'" : "" ?>>
						<td><?=$num?></td>
						<td><?=$r->sender?></td>
						<td><?=display_datetime($r->cre_date, 'full')?></td>
						<td class="align-right"><?=number_format($r->money).'원'?></td>
						<td class="align-right"><?=number_format($r->money).'원'?></td>
						<? if($this->member->item('mem_level') >= 100) {?>
						<td>
                            <? if($r->state=="F"){ ?>
                            <a href="<?php echo $this->pagedir; ?>/errwrite/<?=$r->usmsid?>" class="btn btn-outline btn-default btn-xs">수정</a>
                            <? }else if($r->state=="M"){ ?>
                                이관됨
                            <? }?>
                        </td>
						<? } ?>
					</tr>
				<?php
					}

					if ( $cnt == 0) {
				?>
					<tr>
						<td colspan="<?=($this->member->item('mem_level') >= 100) ? "6" : "5"?>" class="nopost">자료가 없습니다</td>
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

    function unprocessed_download() {

    	var type = $('#date_type').val();
    	var start_date = $('#startDate').val();
    	var end_date = $('#endDate').val();

    	var form = document.createElement("form");
    	document.body.appendChild(form);
    	form.setAttribute("method", "post");
    	form.setAttribute("action", "/biz/manager/pendingbank/unprocessed_download");

    	var scrfField = document.createElement("input");
    	scrfField.setAttribute("type", "hidden");
    	scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
    	scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
    	form.appendChild(scrfField);

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

	function open_page(page) {
		var url = "/biz/manager/pendingbank/errlist?page="+ page;
		location.href = url;
	}
</script>
