<div class="crumbs">
	<ul id="breadcrumbs" class="breadcrumb">
			<li><i class="icon-home"></i>관리자</li>
			<li class="current"><a href="/biz/manager/summary">정산내역(발송통계)</a></li>
	</ul>
</div>
<style type="text/css">
.table-responsive th { vertical-align:middle !important; }
.red { color:red; }
</style>
<div class="box content_wrap">
    <div class="box-table">
           <div class="box-table-header">
			    <form action="/biz/manager/summary" method="post" id="mainForm">
		        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
                <div class="btn-group btn-group-sm" role="group">
						<label for="startDate">정산년월</label>&nbsp;
						<input type="text" class="form-control input-width-small inline datepicker"
								name="startDate" id="startDate" readonly="readonly"
								style="cursor: pointer;background-color: white" value="<?=$param['startDate']?>">&nbsp;
						<label for="userid">업체</label>&nbsp;
						<select name="userid" id="userid" class="select2 input-width-large search-static">
							<option value="ALL">-ALL-</option>
						<?foreach($users as $r) {?>
							<option value="<?=$r->mem_userid?>" <?=($param['userid']==$r->mem_userid) ? 'selected' : ''?>><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
						<?}?>
						</select>
						&nbsp;
						<button class="btn btn-default" id="search" type="button" onclick="submit_handler()" style="float:right;">
							조회
						</button>
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
            </div>
            <div class="mt20"><!--전체 : <span id="total_amount"></span>건--></div>
		<div class="box-table">
			<table class="tpl_ver_form" width="100%">
				<colgroup>
				<col width="20%">
				<col width="35%">
				<col width="20%">
				<col width="*">
				</colgroup>
				<tbody>
					<tr>
						<th>업체명</th>
						<td colspan="3"><?=$member->mem_username?> (<?=$member->mem_userid?>)</td>
					</tr>
					<tr>
						<th>담당자</th>
						<td><?=$member->mem_nickname?></td>
						<th>연락처</th>
						<td><?=$member->mem_phone?></td>
					</tr>
					<tr>
						<th>예치금</th>
						<td>예치금 : <?=number_format($member->mem_deposit, 2)?> &nbsp; &nbsp; , &nbsp; &nbsp; 포인트 : <?=number_format($member->mem_point, 2)?></td>
						<th>사용가능</th>
						<td class="align-right"><font color="blue" style="letter-spacing:1px;"><?=number_format($coin, 2)?></font></td>
					</tr>
					<tr>
						<th>사용내역</th>
						<td colspan="3">
							<table summary="" class="table1" width="100%">
								<caption class="dpn"></caption>
								<colgroup>
								<col width="*" />
								<col width="30%" />
								<col width="30%" />
								</colgroup>
								<thead>
								<tr>
									<th>내용</th>
									<th>건수</th>
									<th>금액</th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<th>충전금액</th>
									<td class="align-right"><?=number_format($list->cnt_charge)?></td>
									<td class="align-right"><?=number_format($list->amt_charge, 2)?></td>
								</tr>
								<tr>
									<th>알림톡</th>
									<td class="align-right"><?=number_format($list->cnt_at)?></td>
									<td class="align-right"><?=number_format($list->amt_at, 2)?></td>
								</tr>
								<tr>
									<th>친구톡(텍스트)</th>
									<td class="align-right"><?=number_format($list->cnt_ft)?></td>
									<td class="align-right"><?=number_format($list->amt_ft, 2)?></td>
								</tr>
								<tr>
									<th>친구톡(이미지)</th>
									<td class="align-right"><?=number_format($list->cnt_ft_img)?></td>
									<td class="align-right"><?=number_format($list->amt_ft_img, 2)?></td>
								</tr>
								<tr>
									<th>폰문자</th>
									<td class="align-right"><?=number_format($list->cnt_phn)?></td>
									<td class="align-right"><?=number_format($list->amt_phn, 2)?></td>
								</tr>
								<tr>
									<th class="red">환불(발송실패)</th>
									<td class="align-right red"><?=number_format($list->cnt_refund)?></td>
									<td class="align-right red"><?=number_format($list->amt_refund, 2)?></td>
								</tr>
								<tr>
									<th><strong>* 합 계 *</strong></th>
									<td class="align-right"><strong><?=number_format($list->cnt_total - $list->cnt_refund)?></strong></td>
									<td class="align-right"><strong><?=number_format($list->amt_amount - $list->amt_refund, 2)?></strong></td>
								</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			<div class="btn-group pull-right" role="group" aria-label="...">
				<a href="/biz/manager/summary"><button type="button" class="btn btn-outline btn-success btn-sm">목록</button></a>
			</div>
		</div>
    </div>
</div>
<script type="text/javascript">
  $("#nav li.nav110").addClass("current open");

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
</script>