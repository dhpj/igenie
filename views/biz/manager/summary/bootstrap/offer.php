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
<script type="text/javascript">
<!--
var val = [];
//-->
</script>
<div class="box content_wrap">
    <div class="box-table">
           <div class="box-table-header">
			    <form action="/biz/manager/summary" method="post" id="mainForm">
		        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
		        <input type='hidden' name='userid' id='userid' value='' />
                <div class="btn-group btn-group-sm" role="group">
						<label for="startDate">정산년월</label>&nbsp;
						<input type="text" class="form-control input-width-small inline datepicker"
								name="startDate" id="startDate" readonly="readonly"
								style="cursor: pointer;background-color: white" value="<?=$param['startDate']?>">&nbsp;
						<label for="userid">업체</label>&nbsp;
						<select name="uid" id="uid" class="select2 input-width-large search-static">
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
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
						<colgroup>
						<col width="*"/>
						<col width="9%"/>
						<col width="8%"/>
						<col width="8%"/>
						<col width="8%"/>
						<col width="8%"/>
						<col width="8%"/>
						<col width="8%"/>
						<col width="9%"/>
						<col width="9%"/>
						</colgroup>
                    <thead>
                        <tr>
                            <th class="align-center">업체명</th>
                            <th class="align-center">충전</th>
                            <th class="align-center">알림톡</th>
                            <th class="align-center">친구톡<br/>텍스트</th>
                            <th class="align-center">친구톡<br/>이미지</th>
                            <th class="align-center">폰문자</th>
                            <th class="align-center red">환불</th>
                            <th class="align-center">발송<br/>합계</th>
                            <th class="align-center">전송매출</th>
                            <th class="align-center">수익금액</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
						  $sum_charge=0;$sum_at=0;$sum_ft=0;$sum_ft_img=0;$sum_sms=0;$sum_lms=0;$sum_mms=0;$sum_refund=0;$sum_phn=0;$sum_cnt=0;$sum_ramt=0;$sum_amt=0;$sum_back=0;
						  $cnt = 0;
						  foreach($list as $r) {
							  if(!$r || ($r["cnt_total"]==0 && $r["amt_amount"]==0)) { continue; }
							  $sum_charge+=$r["amt_charge"];$sum_at+=$r["cnt_at"];$sum_ft+=$r["cnt_ft"];$sum_ft_img+=$r["cnt_ft_img"];$sum_sms+=$r["cnt_sms"];$sum_lms+=$r["cnt_lms"];$sum_mms+=$r["cnt_mms"];
							  $sum_phn+=$r["cnt_phn"];$sum_refund+=$r["cnt_refund"];$sum_cnt+=$r["cnt_total"];$sum_ramt+=$r["amt_refund"];$sum_amt+=($r["amt_amount"]-$r["amt_refund"]);$sum_back+=($r["payback"]+$r["refund"]);
							  $cnt++;
                    ?>
                        <tr class="success">
                            <td><?php echo "<a href='#' onclick='show_page(\"".$r["mem_userid"]."\")'>".html_escape($r["mem_username"])."</a>"; ?></td>
                            <td class="align-right"><?php echo number_format($r["amt_charge"]); ?></td>
                            <td class="align-right"><?php echo number_format($r["cnt_at"]); ?></td>
                            <td class="align-right"><?php echo number_format($r["cnt_ft"]); ?></td>
                            <td class="align-right"><?php echo number_format($r["cnt_ft_img"]); ?></td>
                            <td class="align-right"><?php echo number_format($r["cnt_phn"]); ?></td>
                            <td class="align-right red"><?php echo number_format($r["cnt_refund"]); ?></td>
                            <td class="align-right"><?php echo number_format($r["cnt_total"]-$r["cnt_refund"]); ?></td>
                            <td class="align-right"><?php echo number_format($r["amt_amount"]-$r["amt_refund"]); ?></td>
                            <td class="align-right"><?php echo number_format($r["payback"]+$r["refund"]); ?></td>
                        </tr>
								<script type="text/javascript">
								<!--
								  sep = {
										"vendor": '<?=$r["mem_username"]?>',
										"charge": '<?=$r["amt_charge"]?>',
										"at": '<?=$r["cnt_at"]?>',
										"ft": '<?=$r["cnt_ft"]?>',
										"ft_img": '<?=$r["cnt_ft_img"]?>',
										"phn": '<?=$r["cnt_phn"]?>',
										"sms": '<?=$r["cnt_sms"]?>',
										"lms": '<?=$r["cnt_lms"]?>',
										"mms": '<?=$r["cnt_mms"]?>',
										"refund": '<?=$r["cnt_refund"]?>',
										"total": '<?=($r["cnt_total"]-$r["cnt_refund"])?>',
										"amount": '<?=($r["amt_amount"]-$r["amt_refund"])?>',
										"back": '<?=($r["payback"]+$r["refund"])?>'
								  };
								  val.push(sep);
								//-->
								</script>
                    <?php }
							if($cnt > 1) {?>
								<tr class="success">
									 <th class="align-center">* 합계 *</th>
									 <td class="align-right"><?php echo number_format($sum_charge); ?></td>
									 <td class="align-right"><?php echo number_format($sum_at); ?></td>
									 <td class="align-right"><?php echo number_format($sum_ft); ?></td>
									 <td class="align-right"><?php echo number_format($sum_ft_img); ?></td>
									 <td class="align-right"><?php echo number_format($sum_phn); ?></td>
									 <td class="align-right red"><?php echo number_format($sum_refund); ?></td>
									 <td class="align-right"><?php echo number_format($sum_cnt-$sum_refund); ?></td>
									 <td class="align-right"><?php echo number_format($sum_amt); ?></td>
									 <td class="align-right"><?php echo number_format($sum_back); ?></td>
								</tr>
								<script type="text/javascript">
								<!--
								  sep = {
										"vendor": '* 합계 *',
										"at": '<?=$sum_at?>',
										"ft": '<?=$sum_ft?>',
										"ft_img": '<?=$sum_ft_img?>',
										"phn": '<?=$sum_phn?>',
										"sms": '<?=$sum_sms?>',
										"lms": '<?=$sum_lms?>',
										"mms": '<?=$sum_mms?>',
										"refund": '<?=$sum_refund?>',
										"total": '<?=($sum_cnt-$sum_refund)?>',
										"amount": '<?=$sum_amt?>',
										"back": '<?=$sum_back?>'
								  };
								  val.push(sep);
								//-->
								</script>
							<?php }
                    if (!$list || $cnt < 1) {
                    ?>
                        <tr>
                            <td colspan="10" class="nopost">자료가 없습니다</td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
					<br />
					<div class="btn-group pull-right" role="group" aria-label="...">
						<a href="/biz/manager/summary"><button type="button" class="btn btn-outline btn-success btn-sm">목록</button></a>
					</div>
            </div>
				<div class="align-left">
				  <a type="button" class="btn btn-sm" onclick="download_summary()"><i
							 class="icon-arrow-down"></i> 엑셀 다운로드</a>
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
			$('#userid').val($('#uid').val());
			form.submit();
		}
  }
  function show_page(uid) {
		var form = document.getElementById("mainForm");
		$('#userid').val(uid);
		form.submit();
  }
	//CSV 파일 다운로드
	function download_summary() {
		var list = '<?=json_encode($list)?>';
		if (list == '[]') {
			 $(".content").html("통계가 없습니다.");
			 $("#myModal").modal('show');

		} else {
			 var value = JSON.stringify(val);
			 var form = document.createElement("form");
			 document.body.appendChild(form);
			 form.setAttribute("method", "post");
			 form.setAttribute("action", "/biz/manager/summary/download");
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
</script>