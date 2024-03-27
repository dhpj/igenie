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
<!--<div class="tit_wrap">
	발송통계
</div>-->
<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu11.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	    <div class="snb_nav" style="margin-bottom: -1px; margin-left: 1px">
        <ul>
            <li><a href="/biz/statistics/day">일별</a></li>
            <li class="active"><a href="/biz/statistics/daily">일일통계</a></li>
            <li><a href="/biz/statistics/month">월별</a></li>
            <li><a href="/biz/statistics/monthly">월별통계</a></li>
        </ul>
    	</div>
	<div class="white_box">
		<div class="inner_content">
            <div class="search_box white_box">
                <div class="fr">
                	<button class="btn md" onclick="download_static()"><i class="icon-arrow-down"></i> 엑셀 다운로드</button>
                </div>
                <form action="/biz/statistics/daily" method="post" id="mainForm">
                	<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
                	<input type="text" class="form-control input-width-medium inline datepicker" name="startDate" id="startDate" value="<?=$param['startDate']?>" readonly="readonly" style="cursor: pointer;background-color: white">
                	<select name="pf_key" id="pf_key" onChange="submit_handler();">
                	<option value="ALL">-ALL-</option>
                	<?foreach($profile as $r) {?>
                	<!-- option value="<?=$r->spf_key?>" <?=($param['pf_key']==$r->spf_key) ? 'selected' : ''?>><?=$r->spf_company?>(<?=$r->spf_friend?>)</option-->
                	<option value="<?=$r->mem_id?>" <?=($param['pf_key']==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?></option>
                	<?}?>
                	</select>
                	<button class="btn md" id="search" type="button"
                	onclick="submit_handler()">조회
                	</button>
                </form>
            </div>
		 	<div class="table_list white_box">
                <table id="data_grid">
                 	<colgroup>
                        <col width="150"><?//업체명?>
						<col width="100px"><?//발송요청?>
                        <col width="100px"><?//발송예약?>
						<? if($this->member->item('mem_level')>50) { ?>
						<col width="120px"><?//발송성공 (대기)?>
						<? } else { ?>
						<col width="100px"><?//발송성공?>
                        <? } ?>
                        <col width="100px"><?//발송실패?>
                        <col width="70"><?//발송성공 > 알림톡?>
                        <col width="70"><?//발송성공 > 이미지 알림톡?>
                        <col width="70"><?//발송성공 > 친구톡 텍스트?>
                        <col width="70"><?//발송성공 > 친구톡 이미지?>
                        <col width="70"><?//발송성공 > 친구톡 와이드 이미지?>
						<!--<? if($this->member->item('mem_level')>=50) { ?>
                        <col width="70"><?//발송성공 > WEB(A) KT,LG?>
                        <col width="70"><?//발송성공 > WEB(A) SKT?>
                        <? } else {?>
                        <col width="70"><?//발송성공 > WEB(A)?>
                        <? } ?>
                        <col width="70"><?//발송성공 > WEB(A) SMS?>
                        <col width="70"><?//발송성공 > WEB(A) MMS?>-->
						<!--
                        <col width="70"><?//발송성공 > WEB(B)?>
                        <col width="70"><?//발송성공 > WEB(B) SMS?>
                        <col width="70"><?//발송성공 > WEB(B) MMS?>
						-->
                        <col width="70"><?//발송성공 > WEB(C)?>
                        <col width="70"><?//발송성공 > WEB(C) SMS?>
                        <!--<col width="70"><?//발송성공 > WEB(C) MMS?>-->
						<col width="70"><?//발송실패 > 알림톡?>
						<col width="70"><?//발송실패 > 이미지 알림톡?>
                        <col width="70"><?//발송실패 > 친구톡 텍스트?>
                        <col width="70"><?//발송실패 > 친구톡 이미지?>
                        <col width="70"><?//발송실패 > 친구톡 와이드 이미지?>
                        <!--<col width="70"><?//발송실패 > WEB(A)?>
                        <col width="70"><?//발송실패 > WEB(A) SMS?>
                        <col width="70"><?//발송실패 > WEB(A) MMS?>-->
						<!--
                        <col width="70"><?//발송실패 > WEB(B)?>
                        <col width="70"><?//발송실패 > WEB(B) SMS?>
                        <col width="70"><?//발송실패 > WEB(B) MMS?>
						-->
                        <col width="70"><?//발송실패 > WEB(C)?>
                        <col width="70"><?//발송실패 > WEB(C) SMS?>
                        <!--<col width="70"><?//발송실패 > WEB(C) MMS?>-->
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="text-center" rowspan="2" width="200px">업체명</th>
                        <th class="text-center" rowspan="2">발송<BR>요청</th>
                        <th class="text-center" rowspan="2">발송<BR>예약</th>
                        <? if($this->member->item('mem_level')>50) { ?>
                        <th class="text-center" rowspan="2">발송성공<BR>(대기)</th>
                        <? } else { ?>
                        <th class="text-center" rowspan="2">발송<BR>성공</th>
                        <? } ?>
                        <th class="text-center" rowspan="2">발송<BR>실패</th>
                        <!-- th class="text-center" rowspan="2">대기</th-->
                        <? if($this->member->item('mem_level')>=50) { ?>
                        <th class="text-center" colspan="7">발송성공</th>
                        <? } else {?>
                        <th class="text-center" colspan="7">발송성공</th>
                        <? } ?>
                        <th class="text-center" colspan="7">발송실패</th>
                    </tr>
                    <tr>
                        <th class="text-center" height="40" style="padding:0;letter-spacing:-1px;background-color: rgb(250, 224, 212);"  >알림톡</th>
                        <th class="text-center" height="40" style="padding:0;letter-spacing:-1px;background-color: rgb(250, 224, 212);"  >이미지<br/>알림톡</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(250, 244, 192);">친구톡<br/>텍스트</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(250, 236, 197);">친구톡<br/>이미지</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(250, 236, 197);">친구톡<br/>와이드<br />이미지</th>
                        <!-- 
                        <? if($this->member->item('mem_level')>=50) { ?>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(228, 247, 186);">WEB(A)<br>KT, LG</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(228, 247, 186);">WEB(A)<br>SKT</th>
                        <? } else {?>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(228, 247, 186);">WEB(A)</th>
                        <? } ?>-->
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(228, 247, 186);">WEB(A)<BR>SMS</th>-->
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(228, 247, 186);">WEB(A)<BR>MMS</th>-->
						<!--
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(212, 244, 250);">WEB(B)</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(212, 244, 250);">WEB(B)<BR>SMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(212, 244, 250);">WEB(B)<BR>MMS</th>
						-->
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">WEB(C)</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">WEB(C)<BR>SMS</th>
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">WEB(C)<BR>MMS</th>-->
                        <!-- th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">015<BR>저가문자</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(232, 217, 255);">폰문자</th-->
                        <th class="text-center" height="40" style="padding:0;letter-spacing:-1px;">알림톡</th>
                        <th class="text-center" height="40" style="padding:0;letter-spacing:-1px;">이미지<br/>알림톡</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">친구톡<br/>텍스트</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">친구톡<br/>이미지</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">친구톡<br/>와이드<br />이미지</th>
                        <!-- <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(A)</th>-->
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(A)<BR>SMS</th>-->
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(A)<BR>MMS</th>-->
						<!--
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(B)</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(B)<BR>SMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(B)<BR>MMS</th>
						-->
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(C)</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(C)<BR>SMS</th>
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(C)<BR>MMS</th>-->
                        <!-- th class="text-center" style="padding:0;letter-spacing:-1px;">015<BR>저가문자</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">폰문자</th-->
                    </tr>
                    </thead>
                    <tbody>
                        <?$sum_req=0;
                          $sum_reserved=0;
                          $sum_ok=0;
                          $sum_fail=0;
                          $sum_at=0;
                          $sum_ai=0;
                          $sum_ft=0;
                          $sum_ft_img=0;
                          $sum_ft_wide_img=0;
                          $sum_phn=0;
                          $sum_015=0;
                          $sum_grs=0;
                          $sum_nas=0;
                          $sum_grs_sms=0;
                          $sum_nas_sms=0;
                          $sum_grs_mms=0;
                          $sum_nas_mms=0;
                          $sum_grs_biz = 0;
                          $sum_smt = 0;
                          $sum_smt_sms = 0;
                          $sum_smt_mms = 0;

                          $sum_err_at=0;
                          $sum_err_ai=0;
                          $sum_err_ft=0;
                          $sum_err_ft_img=0;
                          $sum_err_ft_wide_img=0;
                          $sum_err_phn=0;
                          $sum_err_015=0;
                          $sum_err_grs=0;
                          $sum_err_nas=0;
                          $sum_err_grs_sms=0;
                          $sum_err_nas_sms=0;
                          $sum_wait = 0;
                          $sum_err_grs_mms=0;
                          $sum_err_nas_mms=0;
                          $sum_err_smt = 0;
                          $sum_err_smt_sms = 0;
                          $sum_err_smt_mms = 0;

                          $succ = 0;
        				  foreach($list as $r) {
                                    $succ =  $r->mst_at
                                            +$r->mst_ai
        						            +$r->mst_ft
        						            +$r->mst_ft_img
        						            +$r->mst_ft_wide_img
        						            +$r->mst_phn
        						            +$r->mst_sms
        						            +$r->mst_lms
        						            +$r->mst_mms
        						            +$r->mst_015
        						            +$r->mst_grs
        						            +$r->mst_nas
        						            +$r->mst_grs_sms
        						            +$r->mst_nas_sms
        						            +$r->mst_grs_mms
        						            +$r->mst_nas_mms
        						            +$r->mst_smt
        						            +$r->mst_smt_sms
        						            +$r->mst_smt_mms;
        						            //+$r->qty_wait;

        						    //$fail = $r->mst_qty - ( $succ + $r->qty_wait);
        						    $fail = $r->mst_qty - $succ - $r->reserved_qty;

        							$err_phn = $r->qty_phn;

                                    $sum_req+=$r->mst_qty;
                                    $sum_reserved+=$r->reserved_qty;
                                    $sum_ok+=$succ;
                                    if($this->member->item('mem_level')>50) {
                                        $sum_wait += $r->qty_wait;
                                    }

                                    $sum_fail+=$fail;
                                    $sum_at+=$r->mst_at;
                                    $sum_ai+=$r->mst_ai;
                                    $sum_ft+=$r->mst_ft;
                                    $sum_ft_img+=$r->mst_ft_img;
                                    $sum_ft_wide_img+=$r->mst_ft_wide_img;
                                    $sum_grs+=$r->mst_grs;
                                    $sum_grs_sms+=$r->mst_grs_sms;
                                    $sum_nas+=$r->mst_nas;
                                    $sum_nas_sms+=$r->mst_nas_sms;
                                    $sum_015+=$r->mst_015;
                                    $sum_phn+=$r->mst_phn;
                                    $sum_grs_mms+=$r->mst_grs_mms;
                                    $sum_grs_biz+=$r->mst_grs_biz_qty;
                                    $sum_nas_mms+=$r->mst_nas_mms;
                                    $sum_smt+=$r->mst_smt;
                                    $sum_smt_sms+=$r->mst_smt_sms;
                                    $sum_smt_mms+=$r->mst_smt_mms;

                                    $sum_err_at+=$r->err_at;
                                    $sum_err_ai+=$r->err_ai;
                                    $sum_err_ft+=$r->err_ft;
        							$sum_err_ft_img+=$r->err_ft_img;
        							$sum_err_ft_wide_img+=$r->err_ft_wide_img;
        							$sum_err_grs+=$r->qty_grs;
        							$sum_err_grs_sms+=$r->qty_grs_sms;
        							$sum_err_nas+=$r->qty_nas;
        							$sum_err_nas_sms+=$r->qty_nas_sms;
        							$sum_err_015+=$r->qty_015;
        							$sum_err_phn+=$err_phn;
        							//$sum_wait = $sum_wait + $r->qty_wait;
        							$sum_err_grs_mms+=$r->qty_grs_mms;
        							$sum_err_nas_mms+=$r->qty_nas_mms;
        							$sum_err_smt+=$r->qty_smt;
        							$sum_err_smt_sms+=$r->qty_smt_sms;
        							$sum_err_smt_mms+=$r->qty_smt_mms;

        						  ?>

                            <tr>
                                <td class="text-center" style="padding:0;" id='0'><? echo $r->mem_username."<BR><div style='background-color: rgb(190, 190, 190);'>".$r->parent_name."</div>"; ?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='1'><?=number_format($r->mst_qty)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='1'><?=number_format($r->reserved_qty)?></td>
                                <? if($this->member->item('mem_level')>50) { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($succ)?> (<?=number_format($r->qty_wait)?>)</td>
                                <? } else {?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($succ)?></td>
                                <? }?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='3'><?=number_format($fail)?></td>
                                <!-- td class="text-right" style="padding:8px 1px 8px 0px;" id='4'><?=number_format($r->qty_wait)?></td-->
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='5'><?=number_format($r->mst_at)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='5_1'><?=number_format($r->mst_ai)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='6'><?=number_format($r->mst_ft)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='7'><?=number_format($r->mst_ft_img)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='7'><?=number_format($r->mst_ft_wide_img)?></td>
								<!--
                                <? if($this->member->item('mem_level')>=50) { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='8_1'><?=number_format($r->mst_grs - $r->mst_grs_biz_qty)?></td><?//발송성공 > WEB(A)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='9'><?=number_format($r->mst_grs_biz_qty)?></td>
                                <? } else { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='8_2'><?=number_format($r->mst_grs)?></td>
                                <? } ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='10'><?=number_format($r->mst_grs_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='11'><?=number_format($r->mst_grs_mms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='12'><?=number_format($r->mst_nas)?></td><?//발송성공 > WEB(B)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='13'><?=number_format($r->mst_nas_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='14'><?=number_format($r->mst_nas_mms)?></td>
								-->
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='15'><?=number_format($r->mst_smt)?></td><?//발송성공 > WEB(C)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='16'><?=number_format($r->mst_smt_sms)?></td>
                                <!--<td class="text-right" style="padding:8px 1px 8px 0px;" id='17'><?=number_format($r->mst_smt_mms)?></td>-->
                                <!-- <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_015)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_phn)?></td> -->
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='18'><?=number_format($r->err_at)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='18_1'><?=number_format($r->err_ai)?></td>
								<td class="text-right" style="padding:8px 1px 8px 0px;" id='19'><?=number_format($r->err_ft)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='20'><?=number_format($r->err_ft_img)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='20'><?=number_format($r->err_ft_wide_img)?></td>
								<!--
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='21'><?=number_format($r->qty_grs)?></td><?//발송실패 > WEB(A)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='22'><?=number_format($r->qty_grs_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='23'><?=number_format($r->qty_grs_mms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='24'><?=number_format($r->qty_nas)?></td><?//발송실패 > WEB(B)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='25'><?=number_format($r->qty_nas_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='26'><?=number_format($r->qty_nas_mms)?></td>
								-->
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='27'><?=number_format($r->qty_smt)?></td><?//발송실패 > WEB(C)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='28'><?=number_format($r->qty_smt_sms)?></td>
                                <!--<td class="text-right" style="padding:8px 1px 8px 0px;" id='29'><?=number_format($r->qty_smt_mms)?></td>-->
                                <!-- <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->qty_015)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($err_phn)?></td> -->
                            </tr>
                    <?}
                    if($this->member->item('mem_level')>=50) {
                        $sum_grs = $sum_grs - $sum_grs_biz;
                    }
                    ?>
                     </tbody>
                     <tfoot>
                            <tr>
                                <td class="text-center" style="padding:0;">합계</td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_req)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_reserved)?></td>
                                <? if($this->member->item('mem_level')>50) { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_ok)?> (<?=number_format($sum_wait)?>)</td>
                                <? } else {?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_ok)?></td>
                                <? }?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_fail)?></td>
                                <!-- td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_wait)?></td-->
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 224, 212);"><?=number_format($sum_at)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 224, 212);"><?=number_format($sum_ai)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 244, 192);"><?=number_format($sum_ft)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 236, 197);"><?=number_format($sum_ft_img)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 236, 197);"><?=number_format($sum_ft_wide_img)?></td>
								<!--
                                <? if($this->member->item('mem_level')>=50) { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(228, 247, 186);"><?=number_format($sum_grs)?></td><?//발송성공 > WEB(A)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(228, 247, 186);"><?=number_format($sum_grs_biz)?></td>
                                <? } else { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(228, 247, 186);"><?=number_format($sum_grs)?></td>
                                <? } ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(228, 247, 186);"><?=number_format($sum_grs_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(228, 247, 186);"><?=number_format($sum_grs_mms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(212, 244, 250);"><?=number_format($sum_nas)?></td><?//발송성공 > WEB(B)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(212, 244, 250);"><?=number_format($sum_nas_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(212, 244, 250);"><?=number_format($sum_nas_mms)?></td>
								-->
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_smt)?></td><?//발송성공 > WEB(C)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_smt_sms)?></td>
                                <!--<td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_smt_mms)?></td>-->
                                <!-- <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_015)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(232, 217, 255);"><?=number_format($sum_phn)?></td> -->
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_at)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_ai)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_ft)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_ft_img)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_ft_wide_img)?></td>
                                <!--
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_grs)?></td><?//발송실패 > WEB(A)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_grs_sms)?></td>
								<td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_grs_mms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_nas)?></td><?//발송실패 > WEB(B)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_nas_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_nas_mms)?></td>
								-->
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_smt)?></td><?//발송실패 > WEB(C)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_smt_sms)?></td>
                                <!--<td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_smt_mms)?></td>-->
                                <!-- <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_015)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_phn)?></td> -->
                            </tr>
                    </tfoot>
                </table>
            </div>
		</div>
	</div>
</div>




    <style>
        .text-center {
            vertical-align: middle !important;
            line-height: 1;
        }

        .text-right {
            vertical-align: middle !important;
            line-height: 1;
        }

        #search {
            margin-left: 3px;
        }


    </style>
    <script type="text/javascript">
        $("#nav li.nav40").addClass("current open");

        $("select").on("changed.bs.select",
			function(e, clickedIndex, newValue, oldValue) {
			submit_handler();
		 });

		$(document).ready(function() {
			/*$('#data_grid').DataTable( {
				scrollY:        '50vh',
				scrollCollapse: true,
				paging:         false,
				searching:false,
				"ordering": false,
				"info":     false
			} );*/
		});

        $('#startDate').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            language: "kr",
            autoclose: true,
            startDate: '-90d',
			endDate: '+0d'
        }).on('changeDate', function (selected) {
            //var startDate = new Date(selected.date.valueOf());
            //$('#endDate').datepicker('setStartDate', startDate);
            submit_handler();
        });

//         var start = $("#startDate").val();
//         $('#endDate').datepicker({
//             format: "yyyy-mm-dd",
//             todayHighlight: true,
//             language: "kr",
//             autoclose: true,
//             startDate: start
//         }).on('changeDate', function (selected) {
//             var endDate = new Date(selected.date.valueOf());
//             $('#startDate').datepicker('setEndDate', endDate);
//         });

        $(document).unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                $(".btn-primary").click();
            }
        });

        function page(page) {
            var form = document.getElementById('mainForm');
            var pageField = document.createElement("input");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);
            form.appendChild(pageField);
            form.submit();
        }

        function submit_handler() {
            var form = document.getElementById('mainForm');

            if (($('#startDate').val() == "") || ($('#endDate').val() == "")) {
                $(".content").html("조회하실 기간을 선택해주세요.");
                $("#myModal").modal('show');
            } else {
                $('#overlay').fadeIn();
                form.submit();
            }
        }

        //CSV 파일 다운로드
        function download_static() {
            var list = '<?=json_encode($list)?>';
            if (list == '[]') {
                $(".content").html("통계가 없습니다.");
                $("#myModal").modal('show');

            } else {
                var val = [];
                var tot = [];
                <?$sum_req=0;
                          $sum_reserved=0;
                          $sum_ok=0;
                          $sum_fail=0;
                          $sum_at=0;
                          $sum_ai=0;
                          $sum_ft=0;
                          $sum_ft_img=0;
                          $sum_ft_wide_img=0;
                          $sum_phn=0;
                          $sum_015=0;
                          $sum_grs=0;
                          $sum_nas=0;
                          $sum_grs_sms=0;
                          $sum_nas_sms=0;
                          $sum_grs_mms=0;
                          $sum_nas_mms=0;
                          $sum_grs_biz = 0;
                          $sum_smt = 0;
                          $sum_smt_sms = 0;
                          $sum_smt_mms = 0;

                          $sum_err_at=0;
                          $sum_err_ai=0;
                          $sum_err_ft=0;
                          $sum_err_ft_img=0;
                          $sum_err_ft_wide_img=0;
                          $sum_err_phn=0;
                          $sum_err_015=0;
                          $sum_err_grs=0;
                          $sum_err_nas=0;
                          $sum_err_grs_sms=0;
                          $sum_err_nas_sms=0;
                          $sum_wait = 0;
                          $sum_err_grs_mms=0;
                          $sum_err_nas_mms=0;
                          $sum_err_smt = 0;
                          $sum_err_smt_sms = 0;
                          $sum_err_smt_mms = 0;

                          $succ = 0;
                          $user_falg = "U";
                          
                          foreach($list as $r) {
                                    $succ =  $r->mst_at
                                            +$r->mst_ai
        						            +$r->mst_ft
        						            +$r->mst_ft_img
        						            +$r->mst_ft_wide_img
        						            +$r->mst_phn
        						            +$r->mst_sms
        						            +$r->mst_lms
        						            +$r->mst_mms
        						            +$r->mst_015
        						            +$r->mst_grs
        						            +$r->mst_nas
        						            +$r->mst_grs_sms
        						            +$r->mst_nas_sms
        						            +$r->mst_grs_mms
        						            +$r->mst_nas_mms
        						            +$r->mst_smt
        						            +$r->mst_smt_sms
        						            +$r->mst_smt_mms;
        						            //+$r->qty_wait;

        						    //$fail = $r->mst_qty - ( $succ + $r->qty_wait);
        						    $fail = $r->mst_qty - $succ - $r->reserved_qty;

        							$err_phn = $r->qty_phn;

                                    $sum_req+=$r->mst_qty;
                                    $sum_reserved+=$r->reserved_qty;
                                    $sum_ok+=$succ;
                                    if($this->member->item('mem_level')>50) {
                                        $user_falg = "A";
                                        $sum_wait += $r->qty_wait;
                                    }

                                    $sum_fail+=$fail;
                                    $sum_at+=$r->mst_at;
                                    $sum_ai+=$r->mst_ai;
                                    $sum_ft+=$r->mst_ft;
                                    $sum_ft_img+=$r->mst_ft_img;
                                    $sum_ft_wide_img+=$r->mst_ft_wide_img;
                                    $sum_grs+=$r->mst_grs;
                                    $sum_grs_sms+=$r->mst_grs_sms;
                                    $sum_nas+=$r->mst_nas;
                                    $sum_nas_sms+=$r->mst_nas_sms;
                                    $sum_015+=$r->mst_015;
                                    $sum_phn+=$r->mst_phn;
                                    $sum_grs_mms+=$r->mst_grs_mms;
                                    $sum_grs_biz+=$r->mst_grs_biz_qty;
                                    $sum_nas_mms+=$r->mst_nas_mms;
                                    $sum_smt+=$r->mst_smt;
                                    $sum_smt_sms+=$r->mst_smt_sms;
                                    $sum_smt_mms+=$r->mst_smt_mms;

                                    $sum_err_at+=$r->err_at;
                                    $sum_err_ai+=$r->err_ai;
                                    $sum_err_ft+=$r->err_ft;
        							$sum_err_ft_img+=$r->err_ft_img;
        							$sum_err_ft_wide_img+=$r->err_ft_wide_img;
        							$sum_err_grs+=$r->qty_grs;
        							$sum_err_grs_sms+=$r->qty_grs_sms;
        							$sum_err_nas+=$r->qty_nas;
        							$sum_err_nas_sms+=$r->qty_nas_sms;
        							$sum_err_015+=$r->qty_015;
        							$sum_err_phn+=$err_phn;
        							//$sum_wait = $sum_wait + $r->qty_wait;
        							$sum_err_grs_mms+=$r->qty_grs_mms;
        							$sum_err_nas_mms+=$r->qty_nas_mms;
        							$sum_err_smt+=$r->qty_smt;
        							$sum_err_smt_sms+=$r->qty_smt_sms;
        							$sum_err_smt_mms+=$r->qty_smt_mms;
					?>
					  sep = {
							"pf_ynm"				: '<?=($r->mem_username)?>',
							"cnt_total"				: '<?=($r->mst_qty)?>',
							"cnt_reserved_total"	: '<?=($r->reserved_qty)?>',
							"cnt_succ_total"		: '<?=($succ)?>',
							"cnt_wait_total"		: '<?=($r->qty_wait)?>',
							"cnt_invalid_total"		: '<?=($fail)?>',
							"cnt_succ_kakao"		: '<?=($r->mst_at)?>',
							"cnt_succ_kakao_img"	: '<?=($r->mst_ai)?>',
							"cnt_succ_friend"		: '<?=($r->mst_ft)?>',
							"cnt_succ_friend_img"	: '<?=($r->mst_ft_img)?>',
							"cnt_succ_friend_wide_img"	: '<?=($r->mst_ft_wide_img)?>',
							<? if($this->member->item('mem_level')>=50) { ?>
							"cnt_succ_grs"			: '<?=($r->mst_grs - $r->mst_grs_biz_qty)?>',
							"cnt_succ_grs_biz"		: '<?=($r->mst_grs_biz_qty)?>',
							<? } else { ?>
							"cnt_succ_grs"			: '<?=($r->mst_grs)?>',
							<? } ?>
							"cnt_succ_grs_sms"		: '<?=($r->mst_grs_sms)?>',
							"cnt_succ_nas"			: '<?=($r->mst_nas)?>',
							"cnt_succ_nas_sms"		: '<?=($r->mst_nas_sms)?>',
							/*"cnt_succ_015"			: '<?=($r->mst_015)?>',
							"cnt_succ_phn"			: '<?=($r->mst_phn)?>',*/
							"cnt_succ_grs_mms"		: '<?=($r->mst_grs_mms)?>',
							"cnt_succ_nas_mms"		: '<?=($r->mst_nas_mms)?>',
							"cnt_succ_smt"			: '<?=($r->mst_smt)?>',
							"cnt_succ_smt_sms"		: '<?=($r->mst_smt_sms)?>',
							"cnt_succ_smt_mms"		: '<?=($r->mst_smt_mms)?>',
							"cnt_fail_kakao"		: '<?=($r->err_at)?>',
							"cnt_fail_kakao_img"	: '<?=($r->err_ai)?>',
							"cnt_fail_friend"		: '<?=($r->err_ft)?>',
							"cnt_fail_friend_img"	: '<?=($r->err_ft_img)?>',
							"cnt_fail_friend_wide_img"	: '<?=($r->err_ft_wide_img)?>',
							"cnt_fail_grs"			: '<?=($r->qty_grs)?>',
							"cnt_fail_grs_sms"		: '<?=($r->qty_grs_sms)?>',
							"cnt_fail_nas"			: '<?=($r->qty_nas)?>',
							"cnt_fail_nas_sms"		: '<?=($r->qty_nas_sms)?>',
							"cnt_fail_grs_mms"		: '<?=($r->qty_grs_mms)?>',
							"cnt_fail_nas_mms"		: '<?=($r->qty_nas_mms)?>',
							"cnt_fail_smt"			: '<?=($r->qty_smt)?>',
							"cnt_fail_smt_sms"		: '<?=($r->qty_smt_sms)?>',
							"cnt_fail_smt_mms"		: '<?=($r->qty_smt_mms)?>'
							/*"cnt_fail_015"			: '<?=($r->qty_015)?>',
							"cnt_fail_phn"			: '<?=($r->qty_phn)?>'*/
					  };
					  val.push(sep);
					  sep = {
								"pf_ynm"				: '<?=($r->parent_name)?>',
								"cnt_total"				: '마진',
								"cnt_reserved_total"	: '',
								"cnt_succ_total"		: '',
								"cnt_wait_total"		: '',
								"cnt_invalid_total"		: '',
								"cnt_succ_kakao"		: '<?=($mem_price['mad_price_at'] - $parent_price['mad_price_at'])?>',
								"cnt_succ_kakao_img"	: '<?=($mem_price['mad_price_at'] - $parent_price['mad_price_at'])?>',
								"cnt_succ_friend"		: '<?=($mem_price['mad_price_ft'] - $parent_price['mad_price_ft'])?>',
								"cnt_succ_friend_img"	: '<?=($mem_price['mad_price_ft_img'] - $parent_price['mad_price_ft_img'])?>',
								"cnt_succ_friend_wide_img"	: '<?=($mem_price['mad_price_ft_wide_img'] - $parent_price['mad_price_ft_wide_img'])?>',
								<? if($this->member->item('mem_level')>=50) { ?>
								"cnt_succ_grs"			: '<?=($mem_price['mad_price_grs'] - $parent_price['mad_price_grs'])?>',
								"cnt_succ_grs_biz"		: '<?=($mem_price['mad_price_grs'] - $parent_price['mad_price_grs_biz'])?>',
								<? } else { ?>
								"cnt_succ_grs"			: '<?=($mem_price['mad_price_grs'] - $parent_price['mad_price_grs'])?>',
								<? } ?>
								"cnt_succ_grs"			: '<?=($mem_price['mad_price_grs'] - $parent_price['mad_price_grs'])?>',
								"cnt_succ_grs_sms"		: '<?=($mem_price['mad_price_grs_sms'] - $parent_price['mad_price_grs_sms'])?>',
								"cnt_succ_nas"			: '<?=($mem_price['mad_price_nas'] - $parent_price['mad_price_nas'])?>',
								"cnt_succ_nas_sms"		: '<?=($mem_price['mad_price_nas_sms'] - $parent_price['mad_price_nas_sms'])?>',
								"cnt_succ_grs_mms"		: '<?=($mem_price['mad_price_grs_mms'] - $parent_price['mad_price_grs_mms'])?>',
								"cnt_succ_nas_mms"		: '<?=($mem_price['mad_price_nas_mms'] - $parent_price['mad_price_nas_mms'])?>',
								"cnt_succ_smt"			: '<?=($mem_price['mad_price_smt'] - $parent_price['mad_price_smt'])?>',
								"cnt_succ_smt_sms"		: '<?=($mem_price['mad_price_smt_sms'] - $parent_price['mad_price_smt_sms'])?>',
								"cnt_succ_smt_mms"		: '<?=($mem_price['mad_price_smt_mms'] - $parent_price['mad_price_smt_mms'])?>',
								/*"cnt_succ_015"			: '<?=($mem_price['mad_price_015'] - $parent_price['mad_price_015'])?>',
								"cnt_succ_phn"			: '<?=($mem_price['mad_price_phn'] - $parent_price['mad_price_phn'])?>',*/
								"cnt_fail_kakao"		: '',
								"cnt_fail_kakao_img"	: '',
								"cnt_fail_friend"		: '',
								"cnt_fail_friend_img"	: '',
								"cnt_fail_grs"			: '',
								"cnt_fail_grs_sms"		: '',
								"cnt_fail_nas"			: '',
								"cnt_fail_nas_sms"		: '',
								"cnt_fail_grs_mms"		: '',
								"cnt_fail_nas_mms"		: '',
								"cnt_fail_smt"			: '',
								"cnt_fail_smt_sms"		: '',
								"cnt_fail_smt_mms"		: ''
								/*"cnt_fail_015"			: '',
								"cnt_fail_phn"			: ''*/
						  };
					  val.push(sep);
				  <?}
				  if($this->member->item('mem_level')>=50) {
				      $sum_grs = $sum_grs - $sum_grs_biz;
				  }
				  ?>
					  sum = {
							"get_user_flag"                     : '<?=($user_falg)?>',
							"get_statistics_cnt_total"			: '<?=($sum_req)?>',
							"get_statistics_cnt_reserved"		: '<?=($sum_reserved)?>',
							"get_statistics_cnt_succ_total"		: '<?=($sum_ok)?>',
							"get_statistics_cnt_wait_total"		: '<?=($sum_wait)?>',
							"get_statistics_cnt_invalid_total"	: '<?=($sum_fail)?>',
							"get_statistics_cnt_succ_kakao"		: '<?=($sum_at)?>',
							"get_statistics_cnt_succ_kakao_img"	: '<?=($sum_ai)?>',
							"get_statistics_cnt_succ_friend"	: '<?=($sum_ft)?>',
							"get_statistics_cnt_succ_friend_img": '<?=($sum_ft_img)?>',
							"get_statistics_cnt_succ_friend_wide_img": '<?=($sum_ft_wide_img)?>',
							<? if($this->member->item('mem_level')>=50) { ?>
							"get_statistics_cnt_succ_grs"		: '<?=($sum_grs)?>',
							"get_statistics_cnt_succ_grs_biz"	: '<?=($sum_grs_biz)?>',
							<? } else { ?>
							"get_statistics_cnt_succ_grs"		: '<?=($sum_grs)?>',
							<? } ?>
							"get_statistics_cnt_succ_grs_sms"	: '<?=($sum_grs_sms)?>',
							"get_statistics_cnt_succ_nas"		: '<?=($sum_nas)?>',
							"get_statistics_cnt_succ_nas_sms"	: '<?=($sum_nas_sms)?>',
							"get_statistics_cnt_succ_grs_mms"	: '<?=($sum_grs_mms)?>',
							"get_statistics_cnt_succ_nas_mms"	: '<?=($sum_nas_mms)?>',
							"get_statistics_cnt_succ_smt"		: '<?=($sum_smt)?>',
							"get_statistics_cnt_succ_smt_sms"	: '<?=($sum_smt_sms)?>',
							"get_statistics_cnt_succ_smt_mms"	: '<?=($sum_smt_mms)?>',
							/*"get_statistics_cnt_succ_015"		: '<?=($sum_015)?>',
							"get_statistics_cnt_succ_phn"		: '<?=($sum_phn)?>',*/
							"get_statistics_cnt_fail_kakao"		: '<?=($sum_err_at)?>',
							"get_statistics_cnt_fail_kakao_img"	: '<?=($sum_err_ai)?>',
							"get_statistics_cnt_fail_friend"	: '<?=($sum_err_ft)?>',
							"get_statistics_cnt_fail_friend_img": '<?=($sum_err_ft_img)?>',
							"get_statistics_cnt_fail_friend_wide_img": '<?=($sum_err_ft_wide_img)?>',
							"get_statistics_cnt_fail_grs"		: '<?=($sum_err_grs)?>',
							"get_statistics_cnt_fail_grs_sms"	: '<?=($sum_err_grs_sms)?>',
							"get_statistics_cnt_fail_nas"		: '<?=($sum_err_nas)?>',
							"get_statistics_cnt_fail_nas_sms"	: '<?=($sum_err_nas_sms)?>',
							"get_statistics_cnt_fail_grs_mms"	: '<?=($sum_err_grs_mms)?>',
							"get_statistics_cnt_fail_nas_mms"	: '<?=($sum_err_nas_mms)?>',
							"get_statistics_cnt_fail_smt"		: '<?=($sum_err_smt)?>',
							"get_statistics_cnt_fail_smt_sms"	: '<?=($sum_err_smt_sms)?>',
							"get_statistics_cnt_fail_smt_mms"	: '<?=($sum_err_smt_mms)?>'
							/*"get_statistics_cnt_fail_015"		: '<?=($sum_err_015)?>',
							"get_statistics_cnt_fail_phn"		: '<?=($sum_err_phn)?>'*/
					  };
					  tot.push(sum);

                var value = JSON.stringify(val);
                var total = JSON.stringify(tot);
                var form = document.createElement("form");
                document.body.appendChild(form);
                form.setAttribute("method", "post");
                form.setAttribute("action", "/biz/statistics/download/index/1");

                var scrfField = document.createElement("input");
                scrfField.setAttribute("type", "hidden");
                scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
                scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
                form.appendChild(scrfField);

                var scrfField = document.createElement("input");
                scrfField.setAttribute("type", "hidden");
                scrfField.setAttribute("name", "author");
                <? if($this->member->item('mem_level')>=50) { ?>
                scrfField.setAttribute("value", "admin");
                <? } else { ?>
                scrfField.setAttribute("value", "user");
                <? } ?>
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
