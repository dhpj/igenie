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
						<col width="8.5%"/>
						<col width="8.5%"/>
						<col width="8.5%"/>
						<col width="8.5%"/>
						<col width="8.5%"/>
						<col width="8.5%"/>
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
                            <th class="align-center">폰문자</th>
                            <th class="align-center red">환불</th>
                            <th class="align-center">발송<br/>합계</th>
                            <th class="align-center">전송매출</th>
                            <th class="align-center">수익금액</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
						  $sum_charge=0;$sum_at=0;$sum_ft=0;$sum_ft_img=0;$sum_sms=0;$sum_lms=0;$sum_mms=0;$sum_refund=0;$sum_refund1=0;$sum_phn=0;$sum_phn1=0;$sum_cnt=0;$sum_ramt=0;$sum_ramt1=0;$sum_amt=0;$sum_back=0;
						  $sum_at_amt=0;$sum_ft_amt=0;$sum_ft_img_amt=0;$sum_phn_amt=0;$sum_phn_amt1=0;
						  $sum_aat=0;$sum_aft=0;$sum_aft_img=0;$sum_asms=0;$sum_alms=0;$sum_amms=0;$sum_aphn1=0;$sum_aphn2=0;$sum_aphn3=0;$sum_aramt1=0;$sum_aramt2=0;$sum_aramt3=0;
						  $tcnt = 0;
                    foreach ($list as $list_row) {
							  if(!$list_row) { continue; }
							  $vendor = ""; $vendor_nm = "";
							  $sub_charge=0;$sub_at=0;$sub_ft=0;$sub_ft_img=0;$sub_sms=0;$sub_lms=0;$sub_mms=0;$sub_refund=0;$sub_refund1=0;$sub_phn=0;$sub_phn1=0;$sub_cnt=0;$sub_ramt=0;$sub_ramt1=0;$sub_amt=0;$sub_back=0;
	  						  $sub_aat=0;$sub_aft=0;$sub_aft_img=0;$sub_asms=0;$sub_alms=0;$sub_amms=0;$sub_aphn1=0;$sub_aphn2=0;$sub_aphn3=0;$sub_aramt1=0;$sub_aramt2=0;$sub_aramt3=0;
							  $cnt = 0;
							  foreach($list_row as $r) {
								  if(!$r || ($r["cnt_total"]==0 && $r["amt_amount"]==0)) { continue; }
								  $vendor = "<a href='#' onclick='show_page(\"".$r["mem_offerid"]."\")'>".html_escape($r["mem_offername"])."</a>";
								  $vendor_nm = $r["mem_offername"];

								  $sub_charge+=$r["amt_charge"];$sub_at+=$r["cnt_at"];$sub_ft+=$r["cnt_ft"];$sub_ft_img+=$r["cnt_ft_img"];$sub_sms+=$r["cnt_sms"];$sub_lms+=$r["cnt_lms"];$sub_mms+=$r["cnt_mms"];
								  $sub_phn+=$r["cnt_phn"];$sub_phn1+=$r["cnt_phn1"];$sub_refund+=$r["cnt_refund"];$sub_refund1+=$r["cnt_refund1"];$sub_cnt+=$r["cnt_total"];$sub_ramt+=$r["amt_refund"];
								  $sub_ramt1+=$r["amt_refund1"];$sub_amt+=($r["amt_amount"]-$r["amt_refund"]-$r["amt_refund1"]);$sub_back+=($r["payback"]+$r["refund"]+$r["refund1"]);

								  $sub_aat+=$r["adm_at"];$sub_aft+=$r["adm_ft"];$sub_aft_img+=$r["adm_ft_img"];$sub_asms+=$r["adm_sms"];$sub_alms+=$r["adm_lms"];$sub_amms+=$r["adm_mms"];
								  $sub_aphn1+=$r["adm_phn"];$sub_aphn2+=$r["adm_phn1"];//$sub_aphn3+=$r["adm_phn2"];
								  $sub_aramt1+=$r["adm_refund"];$sub_aramt2+=$r["adm_refund1"];//$sub_aramt3+=$r["adm_refund2"];

								  $sum_charge+=$r["amt_charge"];$sum_at+=$r["cnt_at"];$sum_ft+=$r["cnt_ft"];$sum_ft_img+=$r["cnt_ft_img"];$sum_sms+=$r["cnt_sms"];$sum_lms+=$r["cnt_lms"];$sum_mms+=$r["cnt_mms"];
								  $sum_phn+=$r["cnt_phn"];$sum_phn1+=$r["cnt_phn1"];$sum_refund+=$r["cnt_refund"];$sum_refund1+=$r["cnt_refund1"];$sum_cnt+=$r["cnt_total"];$sum_ramt+=$r["amt_refund"];
								  $sum_ramt1+=$r["amt_refund1"];$sum_amt+=($r["amt_amount"]-$r["amt_refund"]-$r["amt_refund1"]);$sum_back+=($r["payback"]+$r["refund"]+$r["refund1"]);

								  $sum_at_amt+=$r["amt_at"];$sum_ft_amt+=$r["amt_ft"];$sum_ft_img_amt+=$r["amt_ft_img"];$sum_phn_amt+=$r["amt_phn"];$sum_phn_amt1+=$r["amt_phn1"];

								  $sum_aat+=$r["adm_at"];$sum_aft+=$r["adm_ft"];$sum_aft_img+=$r["adm_ft_img"];$sum_asms+=$r["adm_sms"];$sum_alms+=$r["adm_lms"];$sum_amms+=$r["adm_mms"];
								  $sum_aphn1+=$r["adm_phn"];$sum_aphn2+=$r["adm_phn1"];//$sum_aphn3+=$r["adm_phn2"];
								  $sum_aramt1+=$r["adm_refund"];$sum_aramt2+=$r["adm_refund1"];//$sum_aramt3+=$r["adm_refund2"];

								  $cnt++; $tcnt++;
								  continue;
                    /*? >
                        <tr class="success">
                            <td>< ?php echo ($cnt > 1) ? "&nbsp;" : "<a href='#' onclick='show_page(\"".$r["mem_offerid"]."\")'>".html_escape($r["mem_offername"])."</a>"; ? ></td>
                            <td>< ?php echo "<a href='#' onclick='show_page(\"".$r["mem_userid"]."\")'>".html_escape($r["mem_username"])."</a>"; ? ></td>
                            <td class="align-right">< ?php echo number_format($r["amt_charge"]); ? ></td>
                            <td class="align-right">< ?php echo number_format($r["cnt_at"]); ? ></td>
                            <td class="align-right">< ?php echo number_format($r["cnt_ft"]); ? ></td>
                            <td class="align-right">< ?php echo number_format($r["cnt_ft_img"]); ? ></td>
                            <td class="align-right">< ?php echo number_format($r["cnt_phn"]); ? ></td>
                            <!--<td class="align-right"><?php echo number_format($r["cnt_sms"]); ? ></td>
                            <td class="align-right">< ?php echo number_format($r["cnt_lms"]); ? ></td>
                            <td class="align-right">< ?php echo number_format($r["cnt_mms"]); ? ></td>-->
                            <td class="align-right red"><?php echo number_format($r["cnt_refund"]); ? ></td>
                            <td class="align-right">< ?php echo number_format($r["cnt_total"]-$r["cnt_refund"]); ? ></td>
                            <td class="align-right">< ?php echo number_format($r["amt_amount"]-$r["amt_refund"]); ? ></td>
                            <td class="align-right">< ?php echo number_format($r["payback"]+$r["refund"]); ? ></td>
                        </tr>
                    < ?php */
								}
								if($cnt > 0) {?>
                        <tr class="success">
                            <th class="align-center"><?=$vendor?></th>
                            <td class="align-right"><?php echo number_format($sub_charge); ?></td>
                            <td class="align-right"><?php echo number_format($sub_at); ?></td>
                            <td class="align-right"><?php echo number_format($sub_ft); ?></td>
                            <td class="align-right"><?php echo number_format($sub_ft_img); ?></td>
                            <td class="align-right"><?php echo number_format($sub_phn+$sub_phn1); ?></td><!-- 폰문자 합계 -->
                            <td class="align-right red"><?php echo number_format($sub_refund+$sub_refund1); ?></td>
                            <td class="align-right"><?php echo number_format($sub_cnt-$sub_refund-$sub_refund1); ?></td>	<!-- 전체수량 - 환불수량 -->
                            <td class="align-right"><?php echo number_format($sub_amt); ?></td>				<!-- 전체금액(환불포함) -->
                            <td class="align-right"><?php echo number_format($sub_back); ?></td>			<!-- 수익금액(환불포함) -->
                        </tr>
								<script type="text/javascript">
								<!--
								  sep = {
										"vendor": '<?=$vendor_nm?>',
										"charge": '<?=$sub_charge?>',
										"at": '<?=$sub_at?>',
										"ft": '<?=$sub_ft?>',
										"ft_img": '<?=$sub_ft_img?>',
										"phn": '<?=$sub_phn+$sub_phn1?>',
										"sms": '<?=$sub_sms?>',
										"lms": '<?=$sub_lms?>',
										"mms": '<?=$sub_mms?>',
										"refund": '<?=$sub_refund+$sub_refund1?>',
										"total": '<?=($sub_cnt-$sub_refund-$sub_refund1)?>',
										"amount": '<?=$sub_amt?>',
										"back": '<?=$sub_back?>'
								  };
								  val.push(sep);
								//-->
								</script>
                    <?php }
						  }
							if($tcnt > 1) {
								$kakao_gross = $sum_aat + $sum_aft + $sum_aft_img; //* $member->mad_price_at + $sum_ft * $member->mad_price_ft + $sum_ft_img * $member->mad_price_ft_img;
								$sms_gross = $sum_asms + $sum_alms + $sum_amms; // * $member->mad_price_sms + $sum_lms * $member->mad_price_lms + $sum_mms * $member->mad_price_mms;
								//$phn_gross = ($sum_phn - $sum_refund) * $adm_phn['prcom'];	// - $sum_ramt; : 지불해야하는금액(나의단가)
								//$phn_gross1 = ($sum_phn1 - $sum_refund1) * $adm_phn['dooit'];	// - $sum_ramt; : 지불해야하는금액(나의단가)
								$phn_gross = ($sum_aphn1 + $sum_aramt1);	// - $sum_ramt; : 지불해야하는금액(나의단가누적)
								$phn_gross1 = ($sum_aphn2 + $sum_aramt2);	// - $sum_ramt; : 지불해야하는금액(나의단가누적)
								?>
								<tr class="success">
									 <th class="align-center">* 합계 *</th>
									 <td class="align-right"><?php echo number_format($sum_charge); ?></td>
									 <td class="align-right"><?php echo number_format($sum_at); ?></td>
									 <td class="align-right"><?php echo number_format($sum_ft); ?></td>
									 <td class="align-right"><?php echo number_format($sum_ft_img); ?></td>
									 <td class="align-right"><?php echo number_format($sum_phn+$sum_phn1); ?></td>
									 <td class="align-right red"><?php echo number_format($sum_refund+$sum_refund1); ?></td>
									 <td class="align-right"><?php echo number_format($sum_cnt-$sum_refund-$sum_refund1); ?></td>
									 <td class="align-right"><?php echo number_format($sum_amt); ?></td>
									 <td class="align-right"><?php echo number_format($sum_back); ?></td>
								</tr>
								<tr class="success">
									 <th class="align-center" colspan="2">* 합계금액 *</th>
									 <td class="align-right"><?php echo number_format($sum_at_amt); ?></td>
									 <td class="align-right"><?php echo number_format($sum_ft_amt); ?></td>
									 <td class="align-right"><?php echo number_format($sum_ft_img_amt); ?></td>
									 <td class="align-right"><?php echo number_format($sum_phn_amt+$sum_phn_amt1); ?></td>
									 <td class="align-right red"><?php echo number_format($sum_ramt+$sum_ramt1); ?></td>
									 <td class="align-right">&nbsp;</td>
									 <td class="align-right"><?php echo number_format($sum_amt); ?></td>
									 <td class="align-right">&nbsp;</td>
								</tr>
								<tr class="success">
									 <th class="align-center" colspan="2">* 소요내역 *</th>
									 <td class="align-right"><?php echo number_format($sum_aat, 2); ?></td>
									 <td class="align-right"><?php echo number_format($sum_aft, 2); ?></td>
									 <td class="align-right"><?php echo number_format($sum_aft_img, 2); ?></td>
									 <td class="align-right"><?php echo number_format(($sum_aphn1+$sum_aphn2), 2); ?></td>
									 <td class="align-right red"><?php echo number_format(($sum_aramt1+$sum_aramt2) * -1, 2); ?></td>
									 <td colspan="2" class="align-right">Kakao: <strong style="letter-spacing:0px;"><font color="blue"><?php echo number_format($kakao_gross, 2); ?></font></strong></td>
									 <td class="align-right"><font color="red"><?php echo number_format($sum_amt - $kakao_gross - $sms_gross - $phn_gross - $phn_gross1 - $sum_back); ?></font></td>
								</tr>
								<script type="text/javascript">
								<!--
								  sep = {
										"vendor": '* 합계 *',
										"charge": '<?=$sum_charge?>',
										"at": '<?=$sum_at?>',
										"ft": '<?=$sum_ft?>',
										"ft_img": '<?=$sum_ft_img?>',
										"phn": '<?=$sum_phn+$sum_phn1?>',
										"sms": '<?=$sum_sms?>',
										"lms": '<?=$sum_lms?>',
										"mms": '<?=$sum_mms?>',
										"refund": '<?=$sum_refund+$sum_refund1?>',
										"total": '<?=($sum_cnt-$sum_refund-$sum_refund1)?>',
										"amount": '<?=$sum_amt?>',
										"back": '<?=$sum_back?>'
								  };
								  val.push(sep);

								  sep = {
										"vendor": '* 소요내역 *',
										"charge": '<?=$sum_charge?>',
										"at": '<?=$sum_aat?>',
										"ft": '<?=$sum_aft?>',
										"ft_img": '<?=$sum_aft_img?>',
										"phn": '<?=($sum_aphn1 + $sum_aphn2)?>',
										"sms": '<?=$sum_asms?>',
										"lms": '<?=$sum_alms?>',
										"mms": '<?=$sum_amms?>',
										"refund": '<?=(($sum_aramt1 + $sum_aramt2) * -1)?>',
										"total": 'Kakao:',
										"amount": '<?=$kakao_gross?>',
										"back": '<?=($sum_amt - $kakao_gross - $sms_gross - $phn_gross - $phn_gross1 - $sum_back)?>'
								  };
								  val.push(sep);
<?/*									phn": '<?=($sum_phn  * $adm_phn['prcom'] + $sum_phn1  * $adm_phn['dooit'])?>',
										"refund": '<?=$sum_ramt?>', */?>
								//-->
								</script>
							<?php }
                    if (!$list || $tcnt < 1) {
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
					<div class="btn-group pull-left" role="group" aria-label="..." style="line-height:16pt;">
						<p>PR컴퍼니 (발송량 - 환불량) * 단가 : <?="(".number_format($sum_phn)." - ".number_format($sum_refund).") * ".number_format($adm_phn['prcom'], 2)." = <strong style='color:blue;'>".number_format($phn_gross, 2)?></strong>원</p> <?/*(($sum_phn - $sum_refund) * $member->mad_price_phn)*/?>
						<p><?=($phn_gross1 > 0) ? "두잇 (발송량 - 환불량) * 단가 : "."(".number_format($sum_phn1)." - ".number_format($sum_refund1).") * ".number_format($adm_phn['dooit'], 2)." = <strong style='color:blue;'>".number_format($phn_gross1, 2)."</strong>원, " : ""?></p> <?/*(($sum_phn1 - $sum_refund1) * $member->mad_price_phn)*/?>
						<p>스윗트렉커 (알림톡 * 단가) + (친구톡 * 단가) + (친구톡이미지 * 단가) : <?="(".number_format($sum_at)." * ".number_format($member->mad_price_at, 2).") + (".number_format($sum_ft)." * ".number_format($member->mad_price_ft, 2).") + (".number_format($sum_ft_img)." * ".number_format($member->mad_price_ft_img, 2).") = <strong style='color:blue;'>".number_format($kakao_gross, 2)?></strong>원</p>
						<p><font color='red'>수익금액 (전송매출 - 폰문자요금 - 스윗트렉커요금 - 중간관리자수익) : <?=number_format($sum_amt, 2)." - ".number_format($phn_gross + $phn_gross1, 2)." - ".number_format($kakao_gross, 2)." - ".number_format($sum_back, 2)." = <strong>".number_format($sum_amt - $kakao_gross - $sms_gross - $phn_gross - $phn_gross1 - $sum_back, 2)?></strong>원</font></p>
						<p>※ 모든 금액은 부가세 포함 금액입니다.</p>
					</div>
					<div class="btn-group pull-right" role="group" aria-label="...">
						<a href="/biz/manager/summary"><button type="button" class="btn btn-outline btn-success btn-sm">목록</button></a>
					</div>
            </div>
				<br/>
				<div class="align-left">
				  <a type="button" class="btn btn-sm" onclick="download_summary()"><i class="icon-arrow-down"></i> 엑셀 다운로드</a>
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