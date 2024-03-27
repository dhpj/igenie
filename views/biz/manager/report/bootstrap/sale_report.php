<div class="tit_wrap">
	매출현황
</div>
<!-- 본문 영역 -->
<script type="text/javascript">
<!--
var val = [];
//-->
</script>
<div id="mArticle">
	<form class="white_box" action="/biz/manager/report/sale_report" method="post" id="mainForm">
	<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
	<div style="text-align: center" role="group">
		<div class="fl">
			<select name="userid" id="userid" onChange="submit_handler();">
				<option value="ALL">-ALL-</option>
			<?foreach($users as $r) {?>
				<option value="<?=$r->mem_userid?>" <?=($param['userid']==$r->mem_userid) ? 'selected' : ''?>><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
			<?}?>
			</select>
			<button class="btn btn-default" id="search" type="button" onclick="submit_handler()" style="float:right;margin-left:5px;">조회</button>
		</div>
		<button class="btn md excel fr" type="button" onclick="download_sale_report()">엑셀 다운로드</button>
		<input type="text" class="stat_date" name="startDate" id="startDate" readonly="readonly" value="<?=$param['startDate']?>"onChange="submit_handler();">
	</div>
	</form>
	<?php
ob_start();
?>
	<div class="btn-group pull-right" role="group" aria-label="...">
	</div>
<?php
$buttons = ob_get_contents();
ob_end_flush();
?>
<!-- table start -->
<div class="table_stat white_box">
	<div class="table_stat_header">
		<table cellspacing="0" cellpadding="0" border="0">
			<colgroup>
					<col width="100px">
					<col width="100px">
					<col width="70px">
					<col width="70px">
					<col width="70px">
					<col width="70px">
					<col width="70px">
					<col width="70px">
					<col width="70px">
					<col width="70px">
					<col width="70px">
					<col width="70px">
					<col width="100px">
				</colgroup>
				<thead>
					<tr>
						<th rowspan="2">소속</th>
						<th rowspan="2">업체명</th>

						<th colspan="5">전월 실적</th>
						<th colspan="5">당월 실적</th>
						<th rowspan="2">잔고</th>
					</tr>
					<tr>
						<th class="">충전금액</th>
						<th class="">카카오</th>
						<th class="">재발신</th>
						<th class="">실패</th>
						<th class="">매출합계</th>

						<th class="">충전금액</th>
						<th class="">카카오</th>
						<th class="">재발신</th>
						<th class="">실패</th>
						<th class="">매출합계</th>
					</tr>
				</thead>
		</table>
	</div>
	<div class="table_stat_body">
		<table cellspacing="0" cellpadding="0" border="0">
			<colgroup>
				<col width="100px">
				<col width="100px">
				<col width="70px">
				<col width="70px">
				<col width="70px">
				<col width="70px">
				<col width="70px">
				<col width="70px">
				<col width="70px">
				<col width="70px">
				<col width="70px">
				<col width="70px">
				<col width="100px">
			</colgroup>
			<tbody>
			<?php
			$cnt=0; $tcnt=0;
			$parent = ""; $sum_deposit=0;$sum_point=0;$sub_deposit=0;$sub_point=0;
			$sum_charge1=0;$sum_amount1=0;$sum_refund1=0;$sum_kakao1=0;$sum_resend1=0;$sum_qty1=0;$sum_amount2=0;$sum_charge2=0;$sum_refund2=0;$sum_kakao2=0;$sum_resend2=0;$sum_qty2=0;
		  $sub_charge1=0;$sub_amount1=0;$sub_refund1=0;$sub_kakao1=0;$sub_resend1=0;$sub_qty1=0;$sub_amount2=0;$sub_charge2=0;$sub_refund2=0;$sub_kakao2=0;$sub_resend2=0;$sub_qty2=0;
	foreach ($list as $r) {
			  if($r->mst_charge1==0 && $r->mst_refund1==0 && $r->mst_qty1==0 && $r->mst_charge2==0 && $r->mst_refund2==0 && $r->mst_qty2==0 && $r->mem_deposit==0 && $r->mem_point==0) { continue; }
			if($parent=="" || $parent != $r->parent_name) {
			if($cnt > 0) {?>
				<tr>
					<td colspan="2"><?=$parent?> 소계</td>
					<td><?php echo number_format($sub_charge1 - $sub_refund1); ?></td>
					<td><?php echo number_format($sub_kakao1); ?></td>
					<td><?php echo number_format($sub_resend1); ?></td>
					<td><?php echo number_format($sub_qty1 - ($sub_kakao1 + $sub_resend1)); ?></td>
					<td><?php echo number_format($sub_amount1); ?></td>
					<td><?php echo number_format($sub_charge2 - $sub_refund2); ?></td>
					<td><?php echo number_format($sub_kakao2); ?></td>
					<td><?php echo number_format($sub_resend2); ?></td>
					<td><?php echo number_format($sub_qty2 - ($sub_kakao2 + $sub_resend2)); ?></td>
					<td><?php echo number_format($sub_amount2); ?></td>
					<td><?php echo number_format($sub_deposit + $sub_point); ?></td>
				</tr>
				<script type="text/javascript">
					sep = {
						"parent": '<?=$parent?> 소계',
						"company": '',
						"charge1": '<?=$sub_charge1?>',
						"refund1": '<?=$sub_refund1?>',
						"kakao1": '<?=$sub_kakao1?>',
						"resend1": '<?=$sub_resend1?>',
						"qty1": '<?=$sub_qty1?>',
						"amount1": '<?=$sub_amount1?>',
						"charge2": '<?=$sub_charge2?>',
						"refund2": '<?=$sub_refund2?>',
						"kakao2": '<?=$sub_kakao2?>',
						"resend2": '<?=$sub_resend2?>',
						"amount2": '<?=$sub_amount2?>',
						"qty2": '<?=$sub_qty2?>',
						"deposit": '<?=$sub_deposit?>',
						"point": '<?=$sub_point?>'
					};
					val.push(sep);
				</script>
		  <?		}
					$sub_charge1=0;$sub_amount1=0;$sub_refund1=0;$sub_kakao1=0;$sub_resend1=0;$sub_qty1=0;$sub_amount2=0;$sub_charge2=0;$sub_refund2=0;$sub_kakao2=0;$sub_resend2=0;$sub_qty2=0;
					$sub_deposit=0;$sub_point=0;
					$cnt = 0;
					$parent = $r->parent_name;
			  }
			  $sub_amount1+=$r->mst_amount1;$sub_charge1+=$r->mst_charge1;$sub_refund1+=$r->mst_refund1;$sub_kakao1+=$r->mst_kakao1;$sub_resend1+=$r->mst_resend1;$sub_qty1+=$r->mst_qty1;
			  $sub_amount2+=$r->mst_amount2;$sub_charge2+=$r->mst_charge2;$sub_refund2+=$r->mst_refund2;$sub_kakao2+=$r->mst_kakao2;$sub_resend2+=$r->mst_resend2;$sub_qty2+=$r->mst_qty2;
			  $sub_point+=$r->mem_point;$sub_deposit+=$r->mem_deposit;

			  $sum_amount1+=$r->mst_amount1;$sum_charge1+=$r->mst_charge1;$sum_refund1+=$r->mst_refund1;$sum_kakao1+=$r->mst_kakao1;$sum_resend1+=$r->mst_resend1;$sum_qty1+=$r->mst_qty1;
			  $sum_amount2+=$r->mst_amount2;$sum_charge2+=$r->mst_charge2;$sum_refund2+=$r->mst_refund2;$sum_kakao2+=$r->mst_kakao2;$sum_resend2+=$r->mst_resend2;$sum_qty2+=$r->mst_qty2;
			  $sum_point+=$r->mem_point;$sum_deposit+=$r->mem_deposit;
	?>
				<tr>
					<td><?php echo ($cnt > 0) ? "&nbsp;" : html_escape($r->parent_name); ?></td>
					<td><?php echo html_escape($r->mem_username); ?></td>
					<td><?php echo number_format($r->mst_charge1 - $r->mst_refund1); ?></td>
					<td><?php echo number_format($r->mst_kakao1); ?></td>
					<td><?php echo number_format($r->mst_resend1); ?></td>
					<td><?php echo number_format($r->mst_qty1 - ($r->mst_kakao1 + $r->mst_resend1)); ?></td>
					<td><?php echo number_format($r->mst_amount1); ?></td>
					<td><?php echo number_format($r->mst_charge2 - $r->mst_refund2); ?></td>
					<td><?php echo number_format($r->mst_kakao2); ?></td>
					<td><?php echo number_format($r->mst_resend2); ?></td>
					<td><?php echo number_format($r->mst_qty2 - ($r->mst_kakao2 + $r->mst_resend2)); ?></td>
					<td><?php echo number_format($r->mst_amount2); ?></td>
					<td><?php echo number_format($r->mem_deposit + $r->mem_point); ?></td>
				</tr>
				<script type="text/javascript">
				<!--
				  sep = {
						"parent": '<?=$r->parent_name?>',
						"company": '<?=$r->mem_username?>',
						"charge1": '<?=$r->mst_charge1?>',
						"refund1": '<?=$r->mst_refund1?>',
						"kakao1": '<?=$r->mst_kakao1?>',
						"resend1": '<?=$r->mst_resend1?>',
						"qty1": '<?=$r->mst_qty1?>',
						"amount1": '<?=$r->mst_amount1?>',
						"charge2": '<?=$r->mst_charge2?>',
						"refund2": '<?=$r->mst_refund2?>',
						"kakao2": '<?=$r->mst_kakao2?>',
						"resend2": '<?=$r->mst_resend2?>',
						"qty2": '<?=$r->mst_qty2?>',
						"amount2": '<?=$r->mst_amount2?>',
						"deposit": '<?=$r->mem_deposit?>',
						"point": '<?=$r->mem_point?>'
				  };
				  val.push(sep);
				//-->
				</script>
	<?php
				$cnt++; $tcnt++;
		   }
			if($cnt > 1) {?>
			<tr>
				<td colspan="2"><?=$parent?> 소계</td>
				<td><?php echo number_format($sub_charge1 - $sub_refund1); ?></td>
				<td><?php echo number_format($sub_kakao1); ?></td>
				<td><?php echo number_format($sub_resend1); ?></td>
				<td><?php echo number_format($sub_qty1 - ($sub_kakao1 + $sub_resend1)); ?></td>
				<td><?php echo number_format($sub_amount1); ?></td>
				<td><?php echo number_format($sub_charge2 - $sub_refund2); ?></td>
				<td><?php echo number_format($sub_kakao2); ?></td>
				<td><?php echo number_format($sub_resend2); ?></td>
				<td><?php echo number_format($sub_qty2 - ($sub_kakao2 + $sub_resend2)); ?></td>
				<td><?php echo number_format($sub_amount2); ?></td>
				<td><?php echo number_format($sub_deposit + $sub_point); ?></td>
			</tr>
			<script type="text/javascript">
				<!--
				  sep = {
						"parent": '<?=$parent?> 소계',
						"company": '',
						"charge1": '<?=$sub_charge1?>',
						"refund1": '<?=$sub_refund1?>',
						"kakao1": '<?=$sub_kakao1?>',
						"resend1": '<?=$sub_resend1?>',
						"qty1": '<?=$sub_qty1?>',
						"amount1": '<?=$sub_amount1?>',
						"charge2": '<?=$sub_charge2?>',
						"refund2": '<?=$sub_refund2?>',
						"kakao2": '<?=$sub_kakao2?>',
						"resend2": '<?=$sub_resend2?>',
						"qty2": '<?=$sub_qty2?>',
						"amount2": '<?=$sub_amount2?>',
						"deposit": '<?=$sub_deposit?>',
						"point": '<?=$sub_point?>'
				  };
				  val.push(sep);
				//-->
				</script>
		<?	}
			if($tcnt > 0) {?>
				<tr class="total_all">
					<td colspan="2">* 합계 *</td>
					<td><?php echo number_format($sum_charge1 - $sum_refund1); ?></td>
					<td><?php echo number_format($sum_kakao1); ?></td>
					<td><?php echo number_format($sum_resend1); ?></td>
					<td><?php echo number_format($sum_qty1 - ($sum_kakao1 + $sum_resend1)); ?></td>
					<td><?php echo number_format($sum_amount1); ?></td>
					<td><?php echo number_format($sum_charge2 - $sum_refund2); ?></td>
					<td><?php echo number_format($sum_kakao2); ?></td>
					<td><?php echo number_format($sum_resend2); ?></td>
					<td><?php echo number_format($sum_qty2 - ($sum_kakao2 + $sum_resend2)); ?></td>
					<td><?php echo number_format($sum_amount2); ?></td>
					<td><?php echo number_format($sum_deposit + $sum_point); ?></td>
				</tr>
				<script type="text/javascript">
				<!--
				  sep = {
						"parent": '합계',
						"company": '',
						"charge1": '<?=$sum_charge1?>',
						"refund1": '<?=$sum_refund1?>',
						"kakao1": '<?=$sum_kakao1?>',
						"resend1": '<?=$sum_resend1?>',
						"qty1": '<?=$sum_qty1?>',
						"amount1": '<?=$sum_amount1?>',
						"charge2": '<?=$sum_charge2?>',
						"refund2": '<?=$sum_refund2?>',
						"kakao2": '<?=$sum_kakao2?>',
						"resend2": '<?=$sum_resend2?>',
						"qty2": '<?=$sum_qty2?>',
						"amount2": '<?=$sum_amount2?>',
						"deposit": '<?=$sum_deposit?>',
						"point": '<?=$sum_point?>'
				  };
				  val.push(sep);
				//-->
				</script>
			<?php }
	if (!$list || $tcnt < 1) {
	?>
		<tr>
			<td colspan="13" class="nopost">자료가 없습니다</td>
		</tr>
	<?php
	}
	?>
			</tbody>
		</table>
	</div>
</div>
<a href="/biz/manager/report/sale_report"><button class="btn md">목록</button></a>
</div><!-- mArticle END -->
<style type="text/css">
.table-responsive th { vertical-align:middle !important; }
.red { color:red; }
.blue { color:blue; }
.green { color:green; }
</style>
<script type="text/javascript">
  $("#nav li.nav40").addClass("current open");

  var start = $("#startDate").val();
  $("#startDate").datepicker({
		format: "yyyy-mm",
		viewMode: "months",
		minViewMode: "months",
		language: "kr",
		autoclose: true
  });

  $(document).unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			 $(".btn-primary").click();
		}
  });

  function substr(datestr) {
		var result = datestr.substring(0, 7);
		return result;
  }

  function submit_handler() {
		if (($('#startDate').val() == "")) {
			 $(".content").html("조회하실 년월을 선택해주세요.");
			 $("#myModal").modal('show');
		} else {
			 var form = document.getElementById("mainForm");
			 form.submit();
		}
  }

  function show_page(uid) {
	  $('#userid').val(uid);
	  submit_handler();
  }

	//CSV 파일 다운로드
	function download_sale_report() {
		var list = '<?=json_encode($list)?>';
		if (list == '[]') {
			 $(".content").html("통계가 없습니다.");
			 $("#myModal").modal('show');

		} else {
			//alert("val : "+ val);
			var value = JSON.stringify(val);
			//alert("value : "+ value);
			//alert("vendor : "+ $('#userid option:selected').text() +", startDate : "+ $('#startDate').val()); return;

			var form = document.createElement("form");
			document.body.appendChild(form);
			form.setAttribute("method", "post");
			form.setAttribute("action", "/biz/manager/report/sale_report_download");

			var scrfField = document.createElement("input");
			scrfField.setAttribute("type", "hidden");
			scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
			form.appendChild(scrfField);

			var vendorField = document.createElement("input");
			vendorField.setAttribute("type", "hidden");
			vendorField.setAttribute("name", "vendor");
			vendorField.setAttribute("value", $('#userid option:selected').text());
			form.appendChild(vendorField);

			var monthField = document.createElement("input");
			monthField.setAttribute("type", "hidden");
			monthField.setAttribute("name", "startDate");
			monthField.setAttribute("value", $('#startDate').val());
			form.appendChild(monthField);

			var valueField = document.createElement("input");
			valueField.setAttribute("type", "hidden");
			valueField.setAttribute("name", "value");
			valueField.setAttribute("value", value);
			form.appendChild(valueField);

			form.submit();
		}
	}
</script>
