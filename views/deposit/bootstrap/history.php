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
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu10.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>나의 지갑</h3>
      <span class="t_small">
        <span class="material-icons">contact_support</span> 예약발송이 걸려있을 경우, 예약발송 예상차감으로 인해 현재 화면과 잔액이 맞지 않을 수 있습니다.
      </span>
			<button class="btn_st1 fr mg_t20" type="button" onclick="download_all_history()"><i class="icon-arrow-down"></i>엑셀 다운로드</button>
		</div>
		<div class="white_box">
			<form id="mainForm" name="mainForm" method="post" action="/deposit/history">
			<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
				<div class="search_wrap">

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
					<button class="btn md black" style="margin-left:8px;" id="search" type="button" onclick="javascript:submit_handler()">조회</button>
          <select style="width:100px;" id="selectinout" name="selectinout" onchange="submit_select()" class="fr">
            <option value="0" <?=($selectinouts=='0')? selected : '' ?>>입출금</option>
            <option value="1" <?=($selectinouts=='1')? selected : '' ?>>출금</option>
            <option value="2" <?=($selectinouts=='2')? selected : '' ?>>입금</option>
          </select>
          <div class="fr mg_r10 mg_t5">전체 : <?=number_format($total_rows)?>건 </div>
				</div>
				<div class="table_list01">
					<table>
						<colgroup>
							<col width="15%">
							<col width="*">
							<col width="15%">
                            <? if($voucher_on>0){ ?>
                            <col width="15%">
                            <? } ?>
                            <col width="15%">
							<col width="15%">
						</colgroup>
						<thead>
						<tr>
							<th>날짜</th>
							<th>사용내역</th>
							<th>가격</th>
                            <? if($voucher_on>0){ ?>
                            <th>바우처잔액</th>
                            <? } ?>
							<th>잔액</th>
                            <?//if($bonus_on>0){?>
                            <th>보너스</th>
                            <?//}?>
						</tr>
						</thead>
						<tbody>
					<?foreach($list as $row) {?>
            <?
            $alname='';
            if($row->amt_kind=='1'){
                if(strpos($row->amt_memo,"선충전")!==false){
                    $alname = "<span class='wallet_ac'>선충전</span> 선충전";
                }else{
                    $alname = "<span class='wallet_ac'>적립</span>".$row->amt_memo;
                }

            }else if($row->amt_kind=='9'){

                  $alname = "<span class='wallet_ac'>바우처 입금</span>";

            }else if($row->amt_kind=='2'){
              if($row->amt_memo=='예치금 선충전 차감'||strpos($row->amt_memo,"선충전")!==false){
                  $alname = "<span class='wallet_mn'>선충전 차감</span>".$row->amt_memo;
              }else{
                  $alname = "<span class='wallet_mn'>차감</span>".$row->amt_memo;
              }

          }else if($row->amt_kind=='4'){
              $alname = "<span class='wallet_ac'>선충전</span>".$row->amt_memo;
            }else if($row->amt_kind=='3'){
                if(strpos($row->amt_memo, "RCS")>-1){
                       $alname = "<span class='wallet_rf'>환불</span>RCS 발송 실패 ";
                   }else{
                       $alname = "<span class='wallet_rf'>환불</span>문자 발송 실패 ";
                   }
            }else{
              $voucher = "";
              if(strpos($row->amt_memo, ",바우처")){$voucher = "(바우처)";}
               if(strpos($row->amt_memo, "보너스")){$voucher = "(보너스)";}
              if($row->amt_kind=='P'){
                if(strpos($row->amt_memo, "SMS")){ $sm='SMS'; }else{ $sm="LMS";}
                $alname = "<span class='wallet_sm'>문자(".$sm.")</span>";
              }else if($row->amt_kind=='R'){
                if(strpos($row->amt_memo, "SMS")){ $sm='SMS'; }else if(strpos($row->amt_memo, "LMS")){ $sm="LMS";}else if(strpos($row->amt_memo, "MMS")){ $sm="MMS";}else{ $sm="템플릿";}
                $alname = "<span class='wallet_rc'>RCS(".$sm.")</span>";
              }else if($row->amt_kind=='A'){
                $alname = "<span class='wallet_al'>알림톡(텍스트)</span>";
              }else if($row->amt_kind=='E'){
                $alname = "<span class='wallet_al'>알림톡(이미지)</span>";
              }else if($row->amt_kind=='F'){
                $alname = "<span class='wallet_al'>친구톡(텍스트)</span>";
              }else if($row->amt_kind=='I'){
                $alname = "<span class='wallet_al'>친구톡(이미지)</span>";
              }else{
                $alname = $row->amt_memo;
              }
            }
             ?>
						<tr>
							<td class="td01"><?=$row->amt_datetime?></td>
							<td class="wallet"><?=$alname?> <? if($row->amt_kind!='1'&&$row->amt_kind!='2'){ echo " <span class='num'>".number_format($row->amt_cnt)."</span>건".$voucher;}?></td>
							<td class="al_right"><? if($row->amt_kind=='1'||$row->amt_kind=='3'||$row->amt_kind=='4'||$row->amt_kind=='9'||$row->amt_kind=='6'){ echo "<span class='wallet_pl'><i class='xi-plus-circle-o'></i></span>";}else{ echo "<span class='wallet_mi'><i class='xi-minus-circle-o'></i></span>";}?><?=number_format($row->amt_amount, 1)?> 원</td>
                            <? if($voucher_on>0){ ?>
                            <td class="al_right"><?=number_format($row->vamt)?> 원</td>
                            <? } ?>
                            <?//if($bonus_on>0){?>
							<td class="al_right"><?=number_format($row->tamt)?> 원</td>
                            <?//}?>
                            <td class="al_right"><?=number_format($row->bamt)?> 원</td>
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
		endDate: '0d'
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
		endDate: '0d'
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

  function submit_select() {
		var form = document.getElementById('mainForm');
    <? if($param['set_date'] == "today"){ ?>
      $('input[name="set_date"]').attr('value','today');
~  <? }else if($param['set_date'] == "week"){ ?>
      $('input[name="set_date"]').attr('value','week');
    <?}else if($param['set_date'] == "1month"){ ?>
      $('input[name="set_date"]').attr('value','1month');
    <?}else if($param['set_date'] == "3month"){ ?>
      $('input[name="set_date"]').attr('value','3month');
    <?}else if($param['set_date'] == "6month"){ ?>
      $('input[name="set_date"]').attr('value','6month');
    <? }else{ ?>
      $('input[name="set_date"]').attr('value',false);
      <? } ?>

			form.submit();

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
	function download_all_history() {
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
			// var value = JSON.stringify(list);
			var form = document.createElement("form");
			document.body.appendChild(form);

			form.setAttribute("method", "post");
			form.setAttribute("action", "/biz/myinfo/download_all_history");
			var scrfField = document.createElement("input");
			scrfField.setAttribute("type", "hidden");
			scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
			form.appendChild(scrfField);
			// var valueField = document.createElement("input");
			// valueField.setAttribute("type", "hidden");
			// valueField.setAttribute("name", "value");
			// valueField.setAttribute("value", value);
			// form.appendChild(valueField);
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
