<div class="crumbs">
	<ul id="breadcrumbs" class="breadcrumb">
			<li><i class="icon-home"></i>관리자</li>
			<li class="current"><a href="/biz/manager/summary2">정산내역(발송통계)</a></li>
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
<div class="box content_wrap" style="width: 1422px;">
    <div class="box-table">
           <div class="box-table-header">
                <button class="btn btn-default align-right" id="summary_btn" type="button" onclick="summary()" style="float:right;">
				월 정산
				</button>
			    <form action="/biz/manager/summary2" method="post" id="mainForm">
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
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
						<colgroup>
						<col width="*"/>
						<col width="9.5%"/>
						<col width="5.5%"/>
						<col width="5.5%"/>
						<col width="5.5%"/>
						<col width="5.5%"/>
						<col width="5.5%"/>
						<col width="5.5%"/>
						<col width="5.5%"/>
						<col width="5.5%"/>
						<col width="5.5%"/>
						<col width="5.5%"/>
						<col width="9.5%"/>
						<col width="9.5%"/>
						</colgroup>
                    <thead>
                        <tr>
                            <th class="align-center">업체명</th>
                            <th class="align-center">충전</th>
                            <th class="align-center">알림톡</th>
                            <th class="align-center">친구톡<br/>텍스트</th>
                            <th class="align-center">친구톡<br/>이미지</th>
                            <th class="align-center">WEB(A)</th>
                            <th class="align-center">WEB(A)<BR>SMS</th>
                            <th class="align-center">WEB(B)</th>
                            <th class="align-center">WEB(B)<BR>SMS</th>
                            <th class="align-center">015<BR>저가문자</th>
                            <th class="align-center">폰문자</th>
                            <th class="align-center">발송<br/>합계</th>
                            <th class="align-center">전송매출</th>
                            <th class="align-center">수익금액</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
    					  $sum_charge=0;
    					  $sum_at=0;
    					  $sum_ft=0;
    					  $sum_ft_img=0;
    					  $sum_phn=0;
    					  $tcnt = 0;
    					  $sum_grs =0;
    					  $sum_grs_sms = 0;
    					  $sum_nas = 0;
    					  $sum_nas_sms = 0;
    					  $sum_015 = 0;
    					  $sum_send = 0;
    					  $sum_send_amount = 0;
    					  $sum_margin_amt = 0;

                        foreach ($list as $r) { 
                            $row_sum = $r->at + $r->ft + $r->ft_img + $r->grs + $r->grs_sms + $r->nas + $r->nas_sms + $r->f_015 + $r->phn;
                            $row_amount_sum = $r->at_amount + $r->ft_amount + $r->ft_img_amount + $r->grs_amount + $r->grs_sms_amount + $r->nas_amount + $r->nas_sms_amount + $r->f_015_amount + $r->phn_amount;
                            $row_margin_sum = $r->at_amt + $r->ft_amt + $r->ft_img_amt + $r->grs_amt + $r->grs_sms_amt + $r->nas_amt + $r->nas_sms_amt + $r->f_015_amt + $r->phn_amt;
                            
                            $sum_charge  = $sum_charge  + $r->deposit;
                            $sum_at      = $sum_at      + $r->at;
                            $sum_ft      = $sum_ft      + $r->ft;
                            $sum_ft_img  = $sum_ft_img  + $r->ft_img;
                            $sum_phn     = $sum_phn     + $r->phn;
                            $sum_grs     = $sum_grs     + $r->grs;
                            $sum_grs_sms = $sum_grs_sms + $r->grs_sms;
                            $sum_nas     = $sum_nas     + $r->nas;
                            $sum_nas_sms = $sum_nas_sms + $r->nas_sms;
                            $sum_015     = $sum_015     + $r->f_015;

                            $sum_send        = $sum_send        + $row_sum;
                            $sum_send_amount = $sum_send_amount + $row_amount_sum;
                            $sum_margin_amt  = $sum_margin_amt  + $row_margin_sum;
                            
                            ?>
                            <tr>
                                <th class="align-center"><a href="#" onclick="show_page('<?=$r->mem_userid?>')"><?php echo $r->vendor ?></a></th>
                                <td class="align-right"><?php echo number_format($r->deposit); ?></th>
                                <td class="align-right"><?php echo number_format($r->at); ?></th>
                                <td class="align-right"><?php echo number_format($r->ft); ?></th>
                                <td class="align-right"><?php echo number_format($r->ft_img); ?></th>
                                <td class="align-right"><?php echo number_format($r->grs); ?></th>
                                <td class="align-right"><?php echo number_format($r->grs_sms); ?></th>
                                <td class="align-right"><?php echo number_format($r->nas); ?></th>
                                <td class="align-right"><?php echo number_format($r->nas_sms); ?></th>
                                <td class="align-right"><?php echo number_format($r->f_015); ?></th>
                                <td class="align-right"><?php echo number_format($r->phn); ?></th>
                                <td class="align-right"><?php echo number_format($row_sum); ?></th>
                                <td class="align-right"><?php echo number_format($row_amount_sum); ?></th>
                                <td class="align-right"><?php echo number_format($row_margin_sum); ?></th>
                            </tr>
                            
							<?php 
							$tcnt = $tcnt + 1;
                            }
                    if (  $tcnt < 1) {
                    ?>
                        <tr>
                            <td colspan="14" class="nopost">자료가 없습니다</td>
                        </tr>
                    <?php
                    } else {
                    ?>
                            <tr>
                                <th class="align-center">합  계</th>
                                <td class="align-right"><?php echo number_format($sum_charge); ?></th>
                                <td class="align-right"><?php echo number_format($sum_at); ?></th>
                                <td class="align-right"><?php echo number_format($sum_ft); ?></th>
                                <td class="align-right"><?php echo number_format($sum_ft_img); ?></th>
                                <td class="align-right"><?php echo number_format($sum_grs); ?></th>
                                <td class="align-right"><?php echo number_format($sum_grs_sms); ?></th>
                                <td class="align-right"><?php echo number_format($sum_nas); ?></th>
                                <td class="align-right"><?php echo number_format($sum_nas_sms); ?></th>
                                <td class="align-right"><?php echo number_format($sum_015); ?></th>
                                <td class="align-right"><?php echo number_format($sum_phn); ?></th>
                                <td class="align-right"><?php echo number_format($sum_send); ?></th>
                                <td class="align-right"><?php echo number_format($sum_send_amount); ?></th>
                                <td class="align-right"><?php echo number_format($sum_margin_amt); ?></th>
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
				  <a type="button" class="btn btn-sm" onclick="download_summary()"><i class="icon-arrow-down"></i> 엑셀 다운로드</a>
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
			 form.setAttribute("action", "/biz/manager/summary2/month_summary/"+$("#startDate").val());
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
		 form.setAttribute("action", "/biz/manager/summary2/download2/admin");
		 var scrfField = document.createElement("input");
		 scrfField.setAttribute("type", "hidden");
		 scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		 scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		 form.appendChild(scrfField);
    	form.submit();
	}
	//CSV 파일 다운로드
	function download_summary() {
		
		var list = '<?=json_encode($list)?>';
		if (list == '[]') {
			 $(".content").html("통계가 없습니다.");
			 $("#myModal").modal('show');

		} else {
            var val = [];
            var tot = [];
           <? $sum_charge=0;
			  $sum_at=0;
			  $sum_ft=0;
			  $sum_ft_img=0;
			  $sum_phn=0;
			  $tcnt = 0;
			  $sum_grs =0;
			  $sum_grs_sms = 0;
			  $sum_nas = 0;
			  $sum_nas_sms = 0;
			  $sum_015 = 0;
			  $sum_send = 0;
			  $sum_send_amount = 0;
			  $sum_margin_amt = 0;

            foreach ($list as $r) { 
                $row_sum = $r->at + $r->ft + $r->ft_img + $r->grs + $r->grs_sms + $r->nas + $r->nas_sms + $r->f_015 + $r->phn;
                $row_amount_sum = $r->at_amount + $r->ft_amount + $r->ft_img_amount + $r->grs_amount + $r->grs_sms_amount + $r->nas_amount + $r->nas_sms_amount + $r->f_015_amount + $r->phn_amount;
                $row_margin_sum = $r->at_amt + $r->ft_amt + $r->ft_img_amt + $r->grs_amt + $r->grs_sms_amt + $r->nas_amt + $r->nas_sms_amt + $r->f_015_amt + $r->phn_amt;
                
                $sum_charge  = $sum_charge  + $r->deposit;
                $sum_at      = $sum_at      + $r->at;
                $sum_ft      = $sum_ft      + $r->ft;
                $sum_ft_img  = $sum_ft_img  + $r->ft_img;
                $sum_phn     = $sum_phn     + $r->phn;
                $sum_grs     = $sum_grs     + $r->grs;
                $sum_grs_sms = $sum_grs_sms + $r->grs_sms;
                $sum_nas     = $sum_nas     + $r->nas;
                $sum_nas_sms = $sum_nas_sms + $r->nas_sms;
                $sum_015     = $sum_015     + $r->f_015;

                $sum_send        = $sum_send        + $row_sum;
                $sum_send_amount = $sum_send_amount + $row_amount_sum;
                $sum_margin_amt  = $sum_margin_amt  + $row_margin_sum;
                
                ?>
				  sep = {
						"vendor"	: '<?=$r->vendor?>',
						"deposit"	: '<?=$r->deposit?>',
						"at"		: '<?=$r->at?>',
						"ft"		: '<?=$r->ft?>',
						"ft_img"	: '<?=$r->ft_img?>',
						"grs"		: '<?=$r->grs?>',
						"grs_sms"	: '<?=$r->grs_sms?>',
						"nas"		: '<?=$r->nas?>',
						"nas_sms"	: '<?=$r->nas_sms?>',
						"f_015"		: '<?=$r->f_105?>',
						"phn"		: '<?=$r->phn?>',
						"row_sum"   : '<?=$row_sum?>',
						"row_amount": '<?=$row_amount_sum?>',
						"row_margin": '<?=$row_margin_sum?>',
						 
				  };
				  val.push(sep);
			  <?}?>
				  sum = {
                        "deposit"	: '<?=$sum_charge?>',
                        "at"		: '<?=$sum_at?>',
                        "ft"		: '<?=$sum_ft?>',
                        "ft_img"	: '<?=$sum_ft_img?>',
                        "grs"		: '<?=$sum_grs?>',
                        "grs_sms"	: '<?=$sum_grs_sms?>',
                        "nas"		: '<?=$sum_nas?>',
                        "nas_sms"	: '<?=$sum_nas_sms?>',
                        "f_015"		: '<?=$sum_015?>',
                        "phn"		: '<?=$sum_phn?>',
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
			 form.setAttribute("action", "/biz/manager/summary2/download/admin");
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