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
<div class="box content_wrap" style="width: 1622px;">
    <div class="box-table">
           <div class="box-table-header">
                <button class="btn btn-default align-right" id="summary_btn" type="button" onclick="summary()" style="float:right;">
				월 정산
				</button>           
			    <form action="/biz/manager/summary2" method="post" id="mainForm">
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
						<col width="3.0%"/>
						<col width="2%"/>
						<col width="3.5%"/>
						<col width="3.0%"/>
						<col width="2%"/>
						<col width="3.5%"/>
						<col width="3.0%"/>
						<col width="2%"/>
						<col width="3.5%"/>
						<col width="3.0%"/>
						<col width="2%"/>
						<col width="3.5%"/>
						<col width="3.0%"/>
						<col width="2%"/>
						<col width="3.5%"/>
						<col width="3.0%"/>
						<col width="2%"/>
						<col width="3.5%"/>
						<col width="3.0%"/>
						<col width="2%"/>
						<col width="3.5%"/>
						<col width="3.0%"/>
						<col width="2%"/>
						<col width="3.5%"/>
						<col width="3.0%"/>
						<col width="2%"/>
						<col width="3.5%"/>
						<col width="4.5%"/>
						</colgroup>
                    <thead>
                        <tr>
                            <th class="align-center" rowspan="2">업체명</th>
                            <th class="align-center" colspan="3">알림톡</th>
                            <th class="align-center" colspan="3">친구톡<br/>텍스트</th>
                            <th class="align-center" colspan="3">친구톡<br/>이미지</th>
                            <th class="align-center" colspan="3">WEB(A)</th>
                            <th class="align-center" colspan="3">WEB(A)<BR>SMS</th>
                            <th class="align-center" colspan="3">WEB(B)</th>
                            <th class="align-center" colspan="3">WEB(B)<BR>SMS</th>
                            <th class="align-center" colspan="3">015<BR>저가문자</th>
                            <th class="align-center" colspan="3">폰문자</th>
                            <th class="align-center" rowspan="2">총 수익</th>
                        </tr>
                        <tr>
                            <th class="align-center">건수</th>
                            <th class="align-center">마진</th>
                            <th class="align-center">금액</th>
                            <th class="align-center">건수</th>
                            <th class="align-center">마진</th>
                            <th class="align-center">금액</th>
                            <th class="align-center">건수</th>
                            <th class="align-center">마진</th>
                            <th class="align-center">금액</th>
                            <th class="align-center">건수</th>
                            <th class="align-center">마진</th>
                            <th class="align-center">금액</th>
                            <th class="align-center">건수</th>
                            <th class="align-center">마진</th>
                            <th class="align-center">금액</th>
                            <th class="align-center">건수</th>
                            <th class="align-center">마진</th>
                            <th class="align-center">금액</th>
                            <th class="align-center">건수</th>
                            <th class="align-center">마진</th>
                            <th class="align-center">금액</th>
                            <th class="align-center">건수</th>
                            <th class="align-center">마진</th>
                            <th class="align-center">금액</th>
                            <th class="align-center">건수</th>
                            <th class="align-center">마진</th>
                            <th class="align-center">금액</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    <?php
    					  $sum_amt=0;
    					  $sum_at_amt=0;
    					  $sum_ft_amt=0;
    					  $sum_ft_img_amt=0;
    					  $sum_grs_amt=0;
    					  $sum_grs_sms_amt=0;
    					  $sum_nas_amt=0;
    					  $sum_nas_sms_amt=0;
    					  $sum_015_amt=0;
    					  $sum_phn_amt=0;
    					  
    					  $tcnt = 0;
    					  
    					  $sum_at=0;
    					  $sum_ft=0;
    					  $sum_ft_img=0;
    					  $sum_phn=0;
    					  $sum_grs =0;
    					  $sum_grs_sms = 0;
    					  $sum_nas = 0;
    					  $sum_nas_sms = 0;
    					  $sum_015 = 0;
    					  
    					  $sum_send = 0;
    					  $sum_send_amount = 0;
    					  $sum_margin_amt = 0;

                        foreach ($list as $r) { 
                            $row_margin_sum = $r->at_amt + $r->ft_amt + $r->ft_img_amt + $r->grs_amt + $r->grs_sms_amt + $r->nas_amt + $r->nas_sms_amt + $r->f_015_amt + $r->phn_amt;
                            
                            $sum_at      = $sum_at      + $r->at;
                            $sum_ft      = $sum_ft      + $r->ft;
                            $sum_ft_img  = $sum_ft_img  + $r->ft_img;
                            $sum_phn     = $sum_phn     + $r->phn;
                            $sum_grs     = $sum_grs     + $r->grs;
                            $sum_grs_sms = $sum_grs_sms + $r->grs_sms;
                            $sum_nas     = $sum_nas     + $r->nas;
                            $sum_nas_sms = $sum_nas_sms + $r->nas_sms;
                            $sum_015     = $sum_015     + $r->f_015;

                            $sum_at_amt      = $sum_at_amt      + $r->at_amt;
                            $sum_ft_amt      = $sum_ft_amt      + $r->ft_amt;
                            $sum_ft_img_amt  = $sum_ft_img_amt  + $r->ft_img_amt;
                            $sum_phn_amt     = $sum_phn_amt     + $r->phn_amt;
                            $sum_grs_amt     = $sum_grs_amt     + $r->grs_amt;
                            $sum_grs_sms_amt = $sum_grs_sms_amt + $r->grs_sms_amt;
                            $sum_nas_amt     = $sum_nas_amt     + $r->nas_amt;
                            $sum_nas_sms_amt = $sum_nas_sms_amt + $r->nas_sms_amt;
                            $sum_015_amt     = $sum_015_amt     + $r->f_015_amt;
                            
                            $sum_margin_amt  = $sum_margin_amt  + $row_margin_sum;
                            
                            ?>
                            <tr>
                                <th class="align-center"><?php echo $r->vendor ?></th>
                                <td class="align-right"><?php echo number_format($r->at); ?></th>
                                <td class="align-right"><?php echo number_format($r->at_price, 2); ?></th>
                                <td class="align-right"><?php echo number_format($r->at_amt); ?></th>
                                <td class="align-right"><?php echo number_format($r->ft); ?></th>
                                <td class="align-right"><?php echo number_format($r->ft_price, 2); ?></th>
                                <td class="align-right"><?php echo number_format($r->ft_amt); ?></th>
                                <td class="align-right"><?php echo number_format($r->ft_img); ?></th>
                                <td class="align-right"><?php echo number_format($r->ft_img_price, 2); ?></th>
                                <td class="align-right"><?php echo number_format($r->ft_img_amt); ?></th>
                                <td class="align-right"><?php echo number_format($r->grs); ?></th>
                                <td class="align-right"><?php echo number_format($r->grs_price, 2); ?></th>
                                <td class="align-right"><?php echo number_format($r->grs_amt); ?></th>
                                <td class="align-right"><?php echo number_format($r->grs_sms); ?></th>
                                <td class="align-right"><?php echo number_format($r->grs_sms_price, 2); ?></th>
                                <td class="align-right"><?php echo number_format($r->grs_sms_amt); ?></th>
                                <td class="align-right"><?php echo number_format($r->nas); ?></th>
                                <td class="align-right"><?php echo number_format($r->nas_price, 2); ?></th>
                                <td class="align-right"><?php echo number_format($r->nas_amt); ?></th>
                                <td class="align-right"><?php echo number_format($r->nas_sms); ?></th>
                                <td class="align-right"><?php echo number_format($r->nas_sms_price, 2); ?></th>
                                <td class="align-right"><?php echo number_format($r->nas_sms_amt); ?></th>
                                <td class="align-right"><?php echo number_format($r->f_015); ?></th>
                                <td class="align-right"><?php echo number_format($r->f_015_price, 2); ?></th>
                                <td class="align-right"><?php echo number_format($r->f_015_amt); ?></th>
                                <td class="align-right"><?php echo number_format($r->phn); ?></th>
                                <td class="align-right"><?php echo number_format($r->phn_price, 2); ?></th>
                                <td class="align-right"><?php echo number_format($r->phn_amt); ?></th>
                                <td class="align-right"><?php echo number_format($row_margin_sum); ?></th>
                            </tr>
                            
							<?php 
							$tcnt = $tcnt + 1;
                            }
                   if ( $tcnt < 1) {
                    ?>
                        <tr>
                            <td colspan="29" class="nopost">자료가 없습니다</td>
                        </tr>
                    <?php
                    } else {
                    ?>
                            <tr>
                                <th class="align-center">합  계</th>
                                <td class="align-right"><?php echo number_format($sum_at); ?></th>
                                <td class="align-right" colspan="2" ><?php echo number_format($sum_at_amt); ?></th>
                                <td class="align-right"><?php echo number_format($sum_ft); ?></th>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_ft_amt); ?></th>
                                <td class="align-right"><?php echo number_format($sum_ft_img); ?></th>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_ft_img_amt); ?></th>
                                <td class="align-right"><?php echo number_format($sum_grs); ?></th>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_grs_amt); ?></th>
                                <td class="align-right"><?php echo number_format($sum_grs_sms); ?></th>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_grs_sms_amt); ?></th>
                                <td class="align-right"><?php echo number_format($sum_nas); ?></th>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_nas_amt); ?></th>
                                <td class="align-right"><?php echo number_format($sum_nas_sms); ?></th>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_nas_sms_amt); ?></th>
                                <td class="align-right"><?php echo number_format($sum_f_015); ?></th>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_f_015_amt); ?></th>
                                <td class="align-right"><?php echo number_format($sum_phn); ?></th>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_phn_amt); ?></th>
                                <td class="align-right"><?php echo number_format($sum_margin_amt); ?></th>
                            </tr>
                                                
                    <?
                    }
                    ?>
                    </tbody>
                </table>
					<br />
					<!-- 
					<div class="btn-group pull-right" role="group" aria-label="...">
						<a href="/biz/manager/summary"><button type="button" class="btn btn-outline btn-success btn-sm">목록</button></a>
					</div>
					 -->
            </div>
				<div class="align-left">
				  <a type="button" class="btn btn-sm" onclick="download_summary()"><i
							 class="icon-arrow-down"></i> 엑셀 다운로드</a>
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
            var val = [];
            var tot = [];
            <?php
					  $sum_amt=0;
					  $sum_at_amt=0;
					  $sum_ft_amt=0;
					  $sum_ft_img_amt=0;
					  $sum_grs_amt=0;
					  $sum_grs_sms_amt=0;
					  $sum_nas_amt=0;
					  $sum_nas_sms_amt=0;
					  $sum_015_amt=0;
					  $sum_phn_amt=0;
					  
					  $tcnt = 0;
					  
					  $sum_at=0;
					  $sum_ft=0;
					  $sum_ft_img=0;
					  $sum_phn=0;
					  $sum_grs =0;
					  $sum_grs_sms = 0;
					  $sum_nas = 0;
					  $sum_nas_sms = 0;
					  $sum_015 = 0;
					  
					  $sum_send = 0;
					  $sum_send_amount = 0;
					  $sum_margin_amt = 0;

                  foreach ($list as $r) { 
                      $row_margin_sum = $r->at_amt + $r->ft_amt + $r->ft_img_amt + $r->grs_amt + $r->grs_sms_amt + $r->nas_amt + $r->nas_sms_amt + $r->f_015_amt + $r->phn_amt;
                      
                      $sum_at      = $sum_at      + $r->at;
                      $sum_ft      = $sum_ft      + $r->ft;
                      $sum_ft_img  = $sum_ft_img  + $r->ft_img;
                      $sum_phn     = $sum_phn     + $r->phn;
                      $sum_grs     = $sum_grs     + $r->grs;
                      $sum_grs_sms = $sum_grs_sms + $r->grs_sms;
                      $sum_nas     = $sum_nas     + $r->nas;
                      $sum_nas_sms = $sum_nas_sms + $r->nas_sms;
                      $sum_015     = $sum_015     + $r->f_015;

                      $sum_at_amt      = $sum_at_amt      + $r->at_amt;
                      $sum_ft_amt      = $sum_ft_amt      + $r->ft_amt;
                      $sum_ft_img_amt  = $sum_ft_img_amt  + $r->ft_img_amt;
                      $sum_phn_amt     = $sum_phn_amt     + $r->phn_amt;
                      $sum_grs_amt     = $sum_grs_amt     + $r->grs_amt;
                      $sum_grs_sms_amt = $sum_grs_sms_amt + $r->grs_sms_amt;
                      $sum_nas_amt     = $sum_nas_amt     + $r->nas_amt;
                      $sum_nas_sms_amt = $sum_nas_sms_amt + $r->nas_sms_amt;
                      $sum_015_amt     = $sum_015_amt     + $r->f_015_amt;
                      
                      $sum_margin_amt  = $sum_margin_amt  + $row_margin_sum;
                      
                      ?>
                      
				  sep = {
						"vendor"		: '<?=$r->vendor?>',
						"at"			: '<?=$r->at?>',
						"at_price"		: '<?=$r->at_price?>',
						"at_amt"		: '<?=$r->at_amt?>',
						"ft"			: '<?=$r->ft?>',
						"ft_price"		: '<?=$r->ft_price?>',
						"ft_amt"		: '<?=$r->ft_amt?>',
						"ft_img"		: '<?=$r->ft_img?>',
						"ft_img_price"	: '<?=$r->ft_img_price?>',
						"ft_img_amt"	: '<?=$r->ft_img_amt?>',
						"grs"			: '<?=$r->grs?>',
						"grs_price"		: '<?=$r->grs_price?>',
						"grs_amt"		: '<?=$r->grs_amt?>',
						"grs_sms"		: '<?=$r->grs_sms?>',
						"grs_sms_price"	: '<?=$r->grs_sms_price?>',
						"grs_sms_amt"	: '<?=$r->grs_sms_amt?>',
						"nas"			: '<?=$r->nas?>',
						"nas_price"		: '<?=$r->nas_price?>',
						"nas_amt"		: '<?=$r->nas_amt?>',
						"nas_sms"		: '<?=$r->nas_sms?>',
						"nas_sms_price"	: '<?=$r->nas_sms_price?>',
						"nas_sms_amt"	: '<?=$r->nas_sms_amt?>',
						"f_015"			: '<?=$r->f_105?>',
						"f_015_price"	: '<?=$r->f_015_price?>',
						"f_015_amt"		: '<?=$r->f_015_amt?>',
						"phn"			: '<?=$r->phn?>',
						"phn_price"		: '<?=$r->phn_price?>',
						"phn_amt"		: '<?=$r->phn_amt?>',
						"row_margin"	: '<?=$row_margin_sum?>'
						 
				  };
				  val.push(sep);
			  <?}?>
				  sum = {
                        "at"			: '<?=$sum_at?>',
                        "at_amt"		: '<?=$sum_at_amt?>',
                        "ft"			: '<?=$sum_ft?>',
                        "ft_amt"		: '<?=$sum_ft_amt?>',
                        "ft_img"		: '<?=$sum_ft_img?>',
                        "ft_img_amt"	: '<?=$sum_ft_img_amt?>',
                        "grs"			: '<?=$sum_grs?>',
                        "grs_amt"		: '<?=$sum_grs_amt?>',
                        "grs_sms"		: '<?=$sum_grs_sms?>',
                        "grs_sms_amt"	: '<?=$sum_grs_sms_amt?>',
                        "nas"			: '<?=$sum_nas?>',
                        "nas_amt"		: '<?=$sum_nas_amt?>',
                        "nas_sms"		: '<?=$sum_nas_sms?>',
                        "nas_sms_amt"	: '<?=$sum_nas_sms_amt?>',
                        "f_015"			: '<?=$sum_015?>',
                        "f_015_amt"		: '<?=$sum_015_amt?>',
                        "phn"			: '<?=$sum_phn?>',
                        "phn_amt"		: '<?=$sum_phn_amt?>',
                        "row_sum_margin": '<?=$sum_margin_amt?>'
				  };
				  tot.push(sum);
			
             var value = JSON.stringify(val);
             var total = JSON.stringify(tot);
             
			 var form = document.createElement("form");
			 document.body.appendChild(form);
			 form.setAttribute("method", "post");
			 form.setAttribute("action", "/biz/manager/summary2/download/offer");
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