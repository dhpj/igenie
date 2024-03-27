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
<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu4.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>사용내역</h3>
			<button class="btn_st1 fr mg_t20" type="button" onclick="download_use_history()"><i class="icon-arrow-down"></i>엑셀 다운로드</button>
		</div>
		<div class="white_box">
			<form id="mainForm" name="mainForm" method="post" action="/biz/myinfo/use_history">
			<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
				<div class="search_wrap">
					<div class="fr">전체 : <?=number_format($total_rows)?>건</div>
					<ul class="search_period">
						<li id="today"<? if($param['set_date'] == "" or $param['set_date'] == "today"){ ?> class="active"<? } ?> value="today" onclick="javascript:submit_handler_set('today')"><a type="button">오늘</a></li>
						<li id="week"<? if($param['set_date'] == "week"){ ?> class="active"<? } ?> value="week" onclick="javascript:submit_handler_set('week')"><a type="button">1주일</a></li>
						<li id="1month"<? if($param['set_date'] == "1month"){ ?> class="active"<? } ?> value="1month" onclick="javascript:submit_handler_set('1month')"><a type="button">1개월</a></li>
						<li id="3month"<? if($param['set_date'] == "3month"){ ?> class="active"<? } ?> value="3month" onclick="javascript:submit_handler_set('3month')"><a type="button">3개월</a></li>
						<li id="6month"<? if($param['set_date'] == "6month"){ ?> class="active"<? } ?> value="6month" onclick="javascript:submit_handler_set('6month')"><a type="button">6개월</a></li>
					</ul>
					<input type="hidden" id="set_date" name="set_date">
					<input type="text" class="form-control input-width-small inline datepicker" name="startDate" id="startDate" value="<?=$param['startDate']?>" readonly="readonly" style="cursor: pointer;background-color:white;width:90px;text-align:center;"> ~
					<input type="text" class="form-control input-width-small inline datepicker" name="endDate" id="endDate" value="<?=$param['endDate']?>" readonly="readonly" style="cursor: pointer;background-color:white;width:90px;text-align:center;">
					<button class="btn md yellow" style="margin-left:8px;" id="search" type="button" onclick="javascript:submit_handler()">조회</button>
				</div>
				<div class="table_list">
					<table>
						<colgroup>
							<col width="20%">
							<col width="*">
							<col width="20%">
							<col width="20%">
						</colgroup>
						<thead>
						<tr>
							<th>사용날짜</th>
							<th>사용내역</th>
							<th>개수</th>
							<th>가격</th>
						</tr>
						</thead>
						<tbody>
					<?foreach($list as $row) {?>
						<tr>
							<td class="td01"><?=$row->amt_datetime?></td>
							<td><?=$row->amt_memo?></td>
							<td><?=number_format($row->amt_cnt)?></td>
							<td>₩<?=number_format($row->amt_amount, 1)?></td>
						</tr>
					<?}?>
						</tbody>
					</table>
				</div>
				<div class="page_cen"><?echo $page_html?></div>
			</form>
		</div>
	</div>
</div>
 <script type="text/javascript">
	$('#startDate').datepicker({
		format: "yyyy-mm-dd",
		todayHighlight: true,
		language: "kr",
		autoclose: true,
		startDate: '-180d',
		endDate: '-1d'
	}).on('changeDate', function (selected) {
		var startDate = new Date(selected.date.valueOf());
		$('#endDate').datepicker('setStartDate', startDate);
	});

	var start = $("#startDate").val();
	$('#endDate').datepicker({
		format: "yyyy-mm-dd",
		todayHighlight: true,
		language: "kr",
		autoclose: true,
		startDate: start,
		endDate: '-1d'
	}).on('changeDate', function (selected) {
		var endDate = new Date(selected.date.valueOf());
		$('#startDate').datepicker('setEndDate', endDate);
	});


	function open_page(page) {
		var form = document.getElementById("mainForm");

		var pageField = document.createElement("input");
		pageField.setAttribute("type", "hidden");
		pageField.setAttribute("name", "page");
		pageField.setAttribute("value", page);
		form.appendChild(pageField);

		var field = document.createElement("input");
		field.setAttribute("type", "hidden");
		field.setAttribute("name", "set_date");
		field.setAttribute("value", "<?=$param['set_date']?>");
		form.appendChild(field);

		form.submit();
	}

	function submit_handler() {
		var form = document.getElementById('mainForm');

		if (($('#startDate').val() == "") || ($('#endDate').val() == "")) {
			$(".content").html("조회하실 기간을 선택해주세요.");
			$("#myModal").modal('show');
		} else {
			form.submit();
		}
	}

	function submit_handler_set(day) {
		var form = document.getElementById('mainForm');

		if(day == 'today'){
			$('input[name="set_date"]').attr('value','today');
		}
		else if (day == 'week'){
			$('input[name="set_date"]').attr('value','week');
		}
		else if (day == '1month'){
			$('input[name="set_date"]').attr('value','1month');
		}
		else if (day == '3month'){
			$('input[name="set_date"]').attr('value','3month');
		}
		else if (day == '6month'){
			$('input[name="set_date"]').attr('value','6month');
		}
		else {
			$('input[name="set_date"]').attr('value',false);
		}
		form.submit();
	}

	jQuery.download = function (url, data, method) {
		//url and data options required
		if (url && data) {
			//data can be string of parameters or array/object
			data = typeof data == 'string' ? data : jQuery.param(data);
			//split params into form inputs
			var inputs = '';
			jQuery.each(data.split('&'), function () {
				var pair = this.split('=');
				inputs += '<input type="hidden" name="' + pair[0] + '" value="' + pair[1] + '" />';
			});
			//send request
			jQuery('<form action="' + url + '" method="' + (method || 'post') + '">' + inputs + '</form>')
					.appendTo('body').submit().remove();
		}
		;
	};

	//CSV 파일 다운로드
	function download_use_history() {
		var startDate = $('#startDate').val();
		var endDate = $('#endDate').val();

		var list = <?=json_encode($list, JSON_UNESCAPED_UNICODE)?>;

		if(list==''){
			$(".content").html("사용내역이 없습니다.");
			$("#myModal").modal('show')

			$(document).unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 13) {
					$(".btn-primary").click();
				}
			});

		} else {
			var value = JSON.stringify(list);
			var form = document.createElement("form");
			document.body.appendChild(form);

			form.setAttribute("method", "post");
			form.setAttribute("action", "/biz/myinfo/download_use_history");
			var scrfField = document.createElement("input");
			scrfField.setAttribute("type", "hidden");
			scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
			form.appendChild(scrfField);
			var valueField = document.createElement("input");
			valueField.setAttribute("type", "hidden");
			valueField.setAttribute("name", "value");
			valueField.setAttribute("value", value);
			form.appendChild(valueField);
			form.submit();
		}
	}

	//사용날짜 동적으로 Rowspan 하기
	$(".td01").each(function() {
		var rows = $(".td01:contains('" + $(this).text() + "')");
		if (rows.length > 1) {
			rows.eq(0).attr("rowspan", rows.length);
			rows.not(":eq(0)").remove();
		}
	});
</script>