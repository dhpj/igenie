<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
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
<!-- 타이틀 영역 -->
<div class="tit_wrap">
	정산관리
</div>
<!-- 타이틀 영역 END -->

<style type="text/css">
.table-responsive th { vertical-align:middle !important; }
.red { color:red; }
</style>
<script type="text/javascript">
<!--
var val = [];
//-->
</script>
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>정산내역(발송통계)</h3>
		</div>
		<div class="white_box">
			<div class="search_wrap">
				<!-- <button class="btn btn-default align-right" id="summary_btn" type="button" onclick="summary()" style="float:right;">
					월 정산
					</button> -->
				<form action="/biz/manager/settlement" method="post" id="mainForm">
			        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
	                <div class="btn-group btn-group-sm" role="group">
						<label for="startDate">정산년월</label>&nbsp;
						<input type="text" class="form-control input-width-small inline datepicker"
								name="startDate" id="startDate" readonly="readonly"
								style="cursor: pointer;background-color: white" value="<?=$param['startDate']?>" onChange="submit_handler()">&nbsp;
						<label for="userid">중간관리자</label>&nbsp;
						<select name="userid" id="userid" class="select2 input-width-large search-static" onChange="submit_handler()">
							<option value="ALL">-ALL-</option>
						<?foreach($users as $r) {?>
							<option value="<?=$r->mem_userid?>" <?=($param['userid']==$r->mem_userid) ? 'selected' : ''?>><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
						<?}?>
						</select>&nbsp;
						<!--
						<label for="companyName">업체명</label>&nbsp;
						<input type="text" class="form-control input-width-small inline"
								name="companyName" id="companyName"
								style="cursor: pointer;background-color: white" value="<?=$param['companyName']?>">&nbsp;
						-->
						<button class="btn md yellow" id="search" type="button" onclick="submit_handler()" style="float:right;">조회</button>
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
						<col width="*"/><?//업체명?>
						<col width="7%"/><?//월사용료?>
						<col width="7%"/><?//충전?>
						<col width="7%"/><?//알림톡?>
                        <col width="7%"/><?//친구톡<br/>텍스트?>
                        <col width="7%"/><?//친구톡<br/>이미지?>
                        <!--<col width="7%"/><?//친구톡<br/>와이드<br/>이미지?>-->
						<col width="7%"/><?//WEB(A)<BR>KT,LG?>
						<col width="7%"/><?//WEB(A)<BR>SKT?>
						<col width="7%"/><?//WEB(C)?>
						<col width="7%"/><?//WEB(C)<BR>SMS?>
						<col width="7%"/><?//발송<br/>합계?>
						<col width="7%"/><?//전송매출?>
						<col width="7%"/><?//수익금액?>
						</colgroup>
                    <thead>
                        <tr>
                            <th class="align-center">업체명</th>
                            <th class="align-center">월사용료</th>
                            <th class="align-center">충전</th>
                            <th class="align-center">알림톡</th>
                            <th class="align-center">친구톡<br/>텍스트</th>
                            <th class="align-center">친구톡<br/>이미지</th>
                            <!--<th class="align-center">친구톡<br/>와이드<br/>이미지</th>-->
                            <th class="align-center">WEB(A)<BR>KT,LG</th>
                            <th class="align-center">WEB(A)<BR>SKT</th>
                            <th class="align-center">WEB(C)</th>
                            <th class="align-center">WEB(C)<BR>SMS</th>
                            <th class="align-center">발송<br/>합계</th>
                            <th class="align-center">전송매출</th>
                            <th class="align-center">수익금액</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
						$sum_charge = 0;
						$sum_fullcare = 0;
						$sum_at = 0;
						$sum_ft = 0;
						$sum_ft_img = 0;
						$sum_ft_w_img = 0;
						$tcnt = 0;
						$sum_grs = 0;
						$sum_grs_biz = 0;
						$sum_grs_sms = 0;
						$sum_grs_mms =0;
						$sum_nas = 0;
						$sum_nas_sms = 0;
						$sum_nas_mms = 0;
						$sum_smt = 0;
						$sum_smt_sms = 0;
						$sum_smt_mms = 0;
						$sum_send = 0;
						$sum_send_amount = 0;
						$sum_margin_amt = 0;
						$sum_monthly_fee = 0;
						foreach ($list as $r) {
							$row_sum = $r->at + $r->ft + $r->ft_img + $r->ft_w_img + $r->grs + $r->grs_biz + $r->grs_sms + $r->nas + $r->nas_sms + $r->nas_mms + $r->grs_mms + $r->grs_biz + $r->smt + $r->smt_sms + $r->smt_mms;
							$row_amount_sum = $r->at_amount + $r->ft_amount + $r->ft_img_amount + $r->ft_w_img_amount + $r->grs_amount + $r->grs_biz_amount + $r->grs_sms_amount + $r->nas_amount + $r->nas_sms_amount + $r->nas_mms_amount + $r->grs_mms_amount + $r->smt_amount + $r->smt_sms_amount + $r->smt_mms_amount;
							$row_margin_sum = $r->at_amt + $r->ft_amt + $r->ft_img_amt + $r->ft_w_img_amt + $r->grs_amt + $r->grs_biz_amt + $r->grs_sms_amt + $r->nas_amt + $r->nas_sms_amt + $r->nas_mms_amt + $r->grs_mms_amt + $r->smt_amt + $r->smt_sms_amt + $r->smt_mms_amt + $r->full_care;
							$sum_charge = $sum_charge  + $r->deposit;
							$sum_fullcare = $sum_fullcare + $r->full_care;
							$sum_at = $sum_at + $r->at;
							$sum_ft = $sum_ft + $r->ft;
							$sum_ft_img = $sum_ft_img + $r->ft_img;
							$sum_ft_w_img = $sum_ft_w_img  + $r->ft_w_img;
							$sum_grs = $sum_grs + $r->grs;
							$sum_grs_biz = $sum_grs_biz + $r->grs_biz;
							$sum_grs_sms = $sum_grs_sms + $r->grs_sms;
							$sum_grs_mms = $sum_grs_mms + $r->grs_mms;
							$sum_nas = $sum_nas + $r->nas;
							$sum_nas_sms = $sum_nas_sms + $r->nas_sms;
							$sum_nas_mms = $sum_nas_mms + $r->nas_mms;
							$sum_smt = $sum_smt + $r->smt;
							$sum_smt_sms = $sum_smt_sms + $r->smt_sms;
							$sum_smt_mms = $sum_smt_mms + $r->smt_mms;
							$sum_send = $sum_send + $row_sum;
							$sum_send_amount = $sum_send_amount + $row_amount_sum;
							$sum_margin_amt = $sum_margin_amt + $row_margin_sum;
							$sum_monthly_fee = $sum_monthly_fee  + $r->monthly_fee;
                            ?>
                            <tr>
                            	<? if ($r->mem_level >= 10) { ?>
                                <th class="align-center"><a href="#" onclick="show_page('<?=$r->mem_userid?>')"><?php echo $r->vendor ?></a></th><?//업체명?>
                                <? } else { ?>
                                <th class="align-center"><?php echo $r->vendor ?></th><?//업체명?>
                                <? } ?>
                                <td class="align-right"><?php echo number_format($r->monthly_fee); ?></td><?//월사용료?>
                                <td class="align-right"><?php echo number_format($r->deposit); ?></td><?//충전?>
                                <td class="align-right"><?php echo number_format($r->at); ?></td><?//알림톡?>
                                <td class="align-right"><?php echo number_format($r->ft); ?></td><?//친구톡 텍스트?>
                                <td class="align-right"><?php echo number_format($r->ft_img); ?></td><?//친구톡 이미지?>
                                <!--<td class="align-right"><?php echo number_format($r->ft_w_img); ?></td><?//친구톡 와이드 이미지?>-->
                                <td class="align-right"><?php echo number_format($r->grs); ?></td><?//WEB(A) KT,LG?>
                                <td class="align-right"><?php echo number_format($r->grs_biz); ?></td><?//WEB(A) SKT?>
                                <td class="align-right"><?php echo number_format($r->smt); ?></td><?//WEB(C)?>
                                <td class="align-right"><?php echo number_format($r->smt_sms); ?></td><?//WEB(C) SMS?>
                                <td class="align-right"><?php echo number_format($row_sum); ?></td><?//발송 합계?>
                                <td class="align-right"><?php echo number_format($row_amount_sum); ?></td><?//전송매출?>
                                <td class="align-right"><?php echo number_format($row_margin_sum); ?></td><?//수익금액?>
                            </tr>
							<?php
							$tcnt = $tcnt + 1;
                            }
                    if (  $tcnt < 1) {
                    ?>
                        <tr>
                            <td colspan="20" class="nopost"><p class="icon_nopost">자료가 없습니다.</p></td>
                        </tr>
                    <?php
                    } else {
                    ?>
                            <tr>
                                <th class="align-center">합  계</th>
                                <td class="align-right"><?php echo number_format($sum_monthly_fee); ?></td><?//월사용료?>
                                <td class="align-right"><?php echo number_format($sum_charge); ?></td><?//충전?>
                                <td class="align-right"><?php echo number_format($sum_at); ?></td><?//알림톡?>
                                <td class="align-right"><?php echo number_format($sum_ft); ?></td><?//친구톡 텍스트?>
                                <td class="align-right"><?php echo number_format($sum_ft_img); ?></td><?//친구톡 이미지?>
                                <!--<td class="align-right"><?php echo number_format($sum_ft_w_img); ?></td><?//친구톡 와이드 이미지?>-->
                                <td class="align-right"><?php echo number_format($sum_grs); ?></td><?//WEB(A) KT,LG?>
                                <td class="align-right"><?php echo number_format($sum_grs_biz); ?></td><?//WEB(A) SKT?>
                                <td class="align-right"><?php echo number_format($sum_smt); ?></td><?//WEB(C)?>
                                <td class="align-right"><?php echo number_format($sum_smt_sms); ?></td><?//WEB(C) SMS?>
                                <td class="align-right"><?php echo number_format($sum_send); ?></td><?//발송 합계?>
                                <td class="align-right"><?php echo number_format($sum_send_amount); ?></td><?//전송매출?>
                                <td class="align-right"><?php echo number_format($sum_margin_amt); ?></td><?//수익금액?>
                            </tr>
                    <? } ?>
                    </tbody>
                </table>
					<br />
					 <!--
					<div class="btn-group pull-right" role="group" aria-label="...">
						<a href="/biz/manager/summary"><button type="button" class="btn btn-outline btn-success btn-sm">목록</button></a>
					</div>
					 -->
            </div>
			<br/>
			<div class="align-left">
			  <a type="button" class="btn btn-sm" style="cursor:pointer;" onclick="download_summary()"><i class="icon-arrow-down"></i> 엑셀 다운로드</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
  $("#nav li.nav120").addClass("current open");

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

  function summary() {
		if(confirm($("#startDate").val() + "월 정산을 진행 하시겠습니까?")) {
			 var form = document.createElement("form");
			 document.body.appendChild(form);
			 form.setAttribute("method", "post");
			 form.setAttribute("action", "/biz/manager/settlement/month_summary/"+$("#startDate").val());
			 var scrfField = document.createElement("input");
			 scrfField.setAttribute("type", "hidden");
			 scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			 scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
			 form.appendChild(scrfField);

            form.submit();
		}
  }

	//CSV 파일 다운로드
	function download_summary2() {
		 var form = document.createElement("form");
		 document.body.appendChild(form);
		 form.setAttribute("method", "post");
		 form.setAttribute("action", "/biz/manager/settlement/download2/admin");
		 var scrfField = document.createElement("input");
		 scrfField.setAttribute("type", "hidden");
		 scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		 scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		 form.appendChild(scrfField);
    	form.submit();
	}
	//CSV 파일 다운로드
	function download_summary() {

		var rowCount = <?= $tcnt ?>;
		if (rowCount < 1) {
			 $(".content").html("조회된 정산 자료가 없습니다.");
			 $("#myModal").modal('show');

		} else {
            var val = [];
            var tot = [];
           <? $sum_charge=0;
            $sum_fullcare = 0;
            $sum_at=0;
            $sum_ft=0;
            $sum_ft_img=0;
            $sum_ft_w_img=0;
            $tcnt = 0;
            $sum_grs =0;
            $sum_grs_biz = 0;
            $sum_grs_sms = 0;
            $sum_grs_mms=0;
            $sum_nas = 0;
            $sum_nas_sms = 0;
            $sum_nas_mms = 0;
            $sum_smt = 0;
            $sum_smt_sms = 0;
            $sum_smt_mms = 0;
            $sum_send = 0;
            $sum_send_amount = 0;
            $sum_margin_amt = 0;
			$sum_monthly_fee = 0;
            foreach ($list as $r) {
                $row_sum = $r->at + $r->ft + $r->ft_img + $r->ft_w_img + $r->grs + $r->grs_biz + $r->grs_sms + $r->nas + $r->nas_sms + $r->nas_mms + $r->grs_mms + $r->grs_biz + $r->smt + $r->smt_sms + $r->smt_mms;
                $row_amount_sum = $r->at_amount + $r->ft_amount + $r->ft_img_amount + $r->grs_amount + $r->grs_biz_amount + $r->grs_sms_amount + $r->nas_amount + $r->nas_sms_amount + $r->nas_mms_amount + $r->grs_mms_amount + $r->smt_amount + $r->smt_sms_amount + $r->smt_mms_amount;
                $row_margin_sum = $r->at_amt + $r->ft_amt + $r->ft_img_amt + $r->grs_amt + $r->grs_biz_amt + $r->grs_sms_amt + $r->nas_amt + $r->nas_sms_amt + $r->nas_mms_amt + $r->grs_mms_amt + $r->smt_amt + $r->smt_sms_amt + $r->smt_mms_amt + $r->full_care;
                $sum_charge  = $sum_charge  + $r->deposit;
                $sum_fullcare = $sum_fullcare + $r->full_care;
                $sum_at      = $sum_at      + $r->at;
                $sum_ft      = $sum_ft      + $r->ft;
                $sum_ft_img  = $sum_ft_img  + $r->ft_img;
                $sum_ft_w_img  = $sum_ft_w_img  + $r->ft_w_img;
                $sum_grs     = $sum_grs     + $r->grs;
                $sum_grs_biz = $sum_grs_biz + $r->grs_biz;
                $sum_grs_sms = $sum_grs_sms + $r->grs_sms;
                $sum_grs_mms     = $sum_grs_mms     + $r->grs_mms;
                $sum_nas     = $sum_nas     + $r->nas;
                $sum_nas_sms = $sum_nas_sms + $r->nas_sms;
                $sum_nas_mms     = $sum_nas_mms     + $r->nas_mms;
                $sum_smt     = $sum_smt     + $r->smt;
                $sum_smt_sms = $sum_smt_sms + $r->smt_sms;
                $sum_smt_mms     = $sum_smt_mms     + $r->smt_mms;
                $sum_send        = $sum_send        + $row_sum;
                $sum_send_amount = $sum_send_amount + $row_amount_sum;
                $sum_margin_amt  = $sum_margin_amt  + $row_margin_sum;
				$sum_monthly_fee = $sum_monthly_fee  + $r->monthly_fee;
                ?>
				  sep = {
						"vendor"	: '<?=$r->vendor?>',
						"monthly_fee"	: '<?=$r->monthly_fee?>',
						"deposit"	: '<?=$r->deposit?>',
						"full_care"	: '<?=$r->full_care?>',
						"at"		: '<?=$r->at?>',
						"ft"		: '<?=$r->ft?>',
						"ft_img"	: '<?=$r->ft_img?>',
						"ft_w_img"	: '<?=$r->ft_w_img?>',
						"grs"		: '<?=$r->grs?>',
						"grs_biz"	: '<?=$r->grs_biz?>',
						"grs_sms"	: '<?=$r->grs_sms?>',
						"grs_mms"	: '<?=$r->grs_mms?>',
						"nas"		: '<?=$r->nas?>',
						"nas_sms"	: '<?=$r->nas_sms?>',
						"nas_mms"	: '<?=$r->nas_mms?>',
						"smt"		: '<?=$r->smt?>',
						"smt_sms"	: '<?=$r->smt_sms?>',
						"smt_mms"	: '<?=$r->smt_mms?>',
						"row_sum"   : '<?=$row_sum?>',
						"row_amount": '<?=$row_amount_sum?>',
						"row_margin": '<?=$row_margin_sum?>',

				  };
				  val.push(sep);
			  <?}?>
				  sum = {
                        "monthly_fee"	: '<?=$sum_monthly_fee?>',
                        "deposit"	: '<?=$sum_charge?>',
						"full_care"	: '<?=$sum_fullcare?>',
                        "at"		: '<?=$sum_at?>',
                        "ft"		: '<?=$sum_ft?>',
                        "ft_img"	: '<?=$sum_ft_img?>',
                        "ft_w_img"	: '<?=$sum_ft_w_img?>',
                        "grs"		: '<?=$sum_grs?>',
                        "grs_biz"	: '<?=$sum_grs_biz?>',
                        "grs_sms"	: '<?=$sum_grs_sms?>',
                        "grs_mms"	: '<?=$sum_grs_mms?>',
                        "nas"		: '<?=$sum_nas?>',
                        "nas_sms"	: '<?=$sum_nas_sms?>',
                        "nas_mms"	: '<?=$sum_nas_mms?>',
                        "smt"		: '<?=$sum_smt?>',
                        "smt_sms"	: '<?=$sum_smt_sms?>',
                        "smt_mms"	: '<?=$sum_smt_mms?>',
                        "row_sum"	: '<?=$sum_send?>',
                        "row_amount": '<?=$sum_send_amount?>',
                        "row_margin": '<?=$sum_margin_amt?>'
				  };
				  tot.push(sum);

             var value = JSON.stringify(val);
             var total = JSON.stringify(tot);
			 var form = document.createElement("form");
			 document.body.appendChild(form);
			 form.setAttribute("method", "post");
			 form.setAttribute("action", "/biz/manager/settlement/download/admin");
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

            var totalField = document.createElement("input");
            totalField.setAttribute("type", "hidden");
            totalField.setAttribute("name", "total");
            totalField.setAttribute("value", total);
            form.appendChild(totalField);
            form.submit();
		}
	}
</script>
