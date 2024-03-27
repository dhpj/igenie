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
<script type="text/javascript" src="/bizM/js/jquery.multi-select.min.js"></script>
<!-- <link rel="stylesheet" type="text/css" href="/bizM/css/example-styles.css" />
<link rel="stylesheet" type="text/css" href="/bizM/css/demo-styles.css" /> -->
<!-- //3차 메뉴 -->
<div id="mArticle">
	    <div class="snb_nav" style="margin-bottom: -1px; margin-left: 1px">
        <ul>
            <!-- li><a href="/biz/statistics/day">일별</a></li-->
            <li><a href="javascript:statistics_pages_load('/biz/statistics/day')">일별</a></li>
            <li><a href="javascript:statistics_pages_load('/biz/statistics/daily')">일일통계</a></li>
            <li><a href="javascript:statistics_pages_load('/biz/statistics/month')">월별</a></li>
            <li class="active"><a href="javascript:statistics_pages_load('/biz/statistics/monthly')">월별통계</a></li>
        </ul>
    	</div>
	<div class="white_box">
		<div class="inner_content">
            <div class="search_box white_box">
                <div class="fr">
                	<button class="btn md" onclick="download_static()"><i class="icon-arrow-down"></i> 엑셀 다운로드</button>
                </div>
                <!-- form action="/biz/statistics/monthly" method="post" id="mainForm"-->
            	<!-- input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' /-->
            	<input type="text" class="form-control input-width-medium inline datepicker" name="startDate" id="startDate" value="<?=$param['startDate']?>" readonly="readonly" style="cursor: pointer;background-color: white">
            	<select name="pf_key" id="pf_key" onChange="search();">
            	<option value="ALL">-ALL-</option>
            	<?foreach($profile as $r) {?>
            	<!-- option value="<?=$r->spf_key?>" <?=($param['pf_key']==$r->spf_key) ? 'selected' : ''?>><?=$r->spf_company?>(<?=$r->spf_friend?>)</option-->
            	<option value="<?=$r->mem_id?>" <?=($param['pf_key']==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?></option>
            	<?}?>
            	</select>
            	<? if($this->member->item('mem_level')>50 || $this->member->item('mem_id') == '962') {?>
            	<select name="branch" id="branch" multiple>
            		<?foreach($branch as $b) { ?>
            		<option value="<?=$b->dbo_id ?>" <?=($this->funn->is_str_in_char(',', $param['branch'],$b->dbo_id)) ? 'selected' : ''?>><?=$b->dbo_name?></option>
            		<? } ?>
            	</select>
            	<select name="salesperson" id="salesperson" multiple>
            		<?
            		$pre_dbo_id = 0;
            		$dataCnt = 0;
            		foreach($salesperson as $s) {
            		    if($pre_dbo_id != $s->dbo_id) {
                		    if($dataCnt > 0) {
                		        // optgroup 닫기 ?>
                	</optgroup>
                	<?
                		    }
                		    $pre_dbo_id = $s->ds_dbo_id; ?>
                	<optgroup label="<?=$s->dbo_name?>">
                	<?
            		    }
            		    $dataCnt++;
            		?>
            			<option value="<?=$s->ds_id?>" <?=($this->funn->is_str_in_char(',', $param['salesperson'],$s->ds_id)) ? 'selected' : ''?>><?=$s->ds_name?> <?=$s->ds_position?></option>
            		<? } ?>
            	</select>
            	<? } ?>
            	<? if($this->member->item('mem_level')>=50) {?>
				<select name="orderby" id="orderby">
	            	<option value="0" <?=($param['orderby'] == "0") ? 'selected' : ''?>>정렬 선택</option>
	            	<? if($this->member->item('mem_level')>50) {?>
	            	<option value="1" <?=($param['orderby'] == "1") ? 'selected' : ''?>>관리업체명, 업체명</option>
	            	<option value="2" <?=($param['orderby'] == "2") ? 'selected' : ''?>>지점, 영업자</option>
	            	<? } else { ?>
	            	<option value="1" <?=($param['orderby'] == "1") ? 'selected' : ''?>>업체명</option>
	            	<? } ?>
	            	<option value="3" <?=($param['orderby'] == "3") ? 'selected' : ''?>>발송수량</option>
		        </select>
				<div class="stat_checkbox">
					<label class="checkbox_container">
						<input type="checkbox" id="zero_include" name="zero_include" <?=$param['zero_include'] == "YES" ? 'checked' : ''?>>
						<span class="checkmark"></span>
					</label>
					<span class="txt">미발송 업체포함</span>
				</div>
            	<? } ?>
            	<? if($this->member->item('mem_level')==17) { ?>
            		<select name="orderby" id="orderby">
            			<option value="0" <?=($param['orderby'] == "0") ? 'selected' : ''?>>정렬 선택</option>
            			<option value="1" <?=($param['orderby'] == "1") ? 'selected' : ''?>>업체명</option>
            			<option value="3" <?=($param['orderby'] == "3") ? 'selected' : ''?>>발송수량</option>
            		</select>
                	<select name="managerType" id="managerType">
                        <option value="S" <?=($param['managerType']=="S") ? 'selected' : ''?>>영업 업체 전체</option>
                        <option value="M" <?=($param['managerType']=="M") ? 'selected' : ''?>>관리 업체 전체</option>
                        <option value="O" <?=($param['managerType']=="O") ? 'selected' : ''?>>관리 업체만</option>
                    </select>
    				<div class="stat_checkbox">
    					<label class="checkbox_container">
    						<input type="checkbox" id="zero_include" name="zero_include" <?=$param['zero_include'] == "YES" ? 'checked' : ''?>>
    						<span class="checkmark"></span>
    					</label>
    					<span class="txt">미발송 업체포함</span>
    				</div>
            	<? } ?>
            	<button class="btn md" id="search" type="button"
            	onclick="search()">조회
            	</button>
                <!-- /form -->
                <br><div class="fr">총 <? echo $list_cnt;?>건 조회</div>

            </div>
		 	<div class="table_list white_box">
                <table id="data_grid">
                 	<colgroup>
                 		<col width="50px"><?//번호?>
                 		<col width="100"><?//소속?>
                        <col width="150"><?//업체명?>
                        <? if($this->member->item('mem_level')>50) { ?>
                        <col width="200px"><?//영업자/지점?>
                        <? } ?>
						<col width="100px"><?//발송요청?>
                        <col width="100px"><?//발송예약?>
						<? if($this->member->item('mem_level')>50) { ?>
						<col width="120px"><?//발송성공 (대기)?>
						<? } else { ?>
						<col width="100px"><?//발송성공?>
                        <? } ?>
                        <col width="100px"><?//발송실패?>
                        <col width="70px"><?//발송성공 > 알림톡?>
                        <col width="70px"><?//발송성공 > 이미지 알림톡?>
                        <col width="70px"><?//발송성공 > 친구톡 텍스트?>
                        <col width="70px"><?//발송성공 > 친구톡 이미지?>
                        <!--
                        <col width="70"><?//발송성공 > 친구톡 와이드 이미지?>
						<? if($this->member->item('mem_level')>=50) { ?>
                        <col width="70"><?//발송성공 > WEB(A) KT,LG?>
                        <col width="70"><?//발송성공 > WEB(A) SKT?>
                        <? } else {?>
                        <col width="70"><?//발송성공 > WEB(A)?>
                        <? } ?>
                        <col width="70"><?//발송성공 > WEB(A) SMS?>-->
                        <!--<col width="70"><?//발송성공 > WEB(A) MMS?>-->
						<!--
                        <col width="70"><?//발송성공 > WEB(B)?>
                        <col width="70"><?//발송성공 > WEB(B) SMS?>
                        <col width="70"><?//발송성공 > WEB(B) MMS?>
						-->
                        <col width="70px"><?//발송성공 > WEB(C)?>
                        <col width="70px"><?//발송성공 > WEB(C) SMS?>
                        <!--<col width="70"><?//발송성공 > WEB(C) MMS?>-->
                        <col width="70px"><?//발송성공 > RCS SMS?>
                        <col width="70px"><?//발송성공 > RCS LMS?>
                        <col width="70px"><?//발송성공 > RCS MMS?>
						<col width="70px"><?//발송실패 > 알림톡?>
						<col width="70px"><?//발송실패 > 이미지 알림톡?>
                        <col width="70px"><?//발송실패 > 친구톡 텍스트?>
                        <col width="70px"><?//발송실패 > 친구톡 이미지?>
                        <!--<col width="70"><?//발송실패 > 친구톡 와이드 이미지?>
                        <col width="70"><?//발송실패 > WEB(A)?>
                        <col width="70"><?//발송실패 > WEB(A) SMS?>
                        <col width="70"><?//발송실패 > WEB(A) MMS?>-->
						<!--
                        <col width="70"><?//발송실패 > WEB(B)?>
                        <col width="70"><?//발송실패 > WEB(B) SMS?>
                        <col width="70"><?//발송실패 > WEB(B) MMS?>
						-->
                        <col width="70px"><?//발송실패 > WEB(C)?>
                        <col width="70px"><?//발송실패 > WEB(C) SMS?>
                        <!--<col width="70"><?//발송실패 > WEB(C) MMS?>-->
                        <col width="70px"><?//발송실패 > RCS SMS?>
                        <col width="70px"><?//발송실패 > RCS LMS?>
                        <col width="70px"><?//발송실패 > RCS MMS?>
                        <? if($this->member->item('mem_level')>90) { ?>
                        <col widht="100px"><?//인센티브?>
                        <? }?>
                    </colgroup>
                    <thead>
                    <tr>
                    	<th class="text-center" rowspan="2">순번</th>
                    	<th class="text-center" rowspan="2">소속</th>
                        <th class="text-center" rowspan="2">업체명</th>
                        <? if($this->member->item('mem_level')>50) { ?>
                        <th class="text-center" rowspan="2">영업자<BR>영업지점</th>
                        <? } ?>
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
                        <th class="text-center" colspan="9">발송성공</th>
                        <? } else {?>
                        <th class="text-center" colspan="9">발송성공</th>
                        <? } ?>
                        <th class="text-center" colspan="9">발송실패</th>
                        <? if($this->member->item('mem_level')>90) { ?>
                        <th class="text-center" rowspan="2">인센<BR>티브</th>
                        <? }?>
                    </tr>
                    <tr>
                        <th class="text-center" height="40" style="padding:0;letter-spacing:-1px;background-color: rgb(250, 224, 212);"  >알림톡</th>
                        <th class="text-center" height="40" style="padding:0;letter-spacing:-1px;background-color: rgb(250, 224, 212);"  >이미지<br/>알림톡</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(250, 244, 192);">친구톡<br/>텍스트</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(250, 236, 197);">친구톡<br/>이미지</th>
                        <!--
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(250, 236, 197);">친구톡<br/>와이드<br />이미지</th>
                        <? if($this->member->item('mem_level')>=50) { ?>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(228, 247, 186);">WEB(A)<br>KT, LG</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(228, 247, 186);">WEB(A)<br>SKT</th>
                        <? } else {?>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(228, 247, 186);">WEB(A)</th>
                        <? } ?> -->
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(228, 247, 186);">WEB(A)<BR>SMS</th>-->
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(228, 247, 186);">WEB(A)<BR>MMS</th>-->
						<!--
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(212, 244, 250);">WEB(B)</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(212, 244, 250);">WEB(B)<BR>SMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(212, 244, 250);">WEB(B)<BR>MMS</th>
						-->
						<? if($this->member->item('mem_level')>=30) {
						?>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">WEB(C)<BR>SMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">WEB(C)<BR>LMS</th>
                        <? } else { ?>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">문자<BR>SMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">문자<BR>LMS</th>
                        <? } ?>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">RCS<BR>SMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">RCS<BR>LMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">RCS<BR>MMS</th>
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">WEB(C)<BR>MMS</th>-->
                        <!-- th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(218, 217, 255);">015<BR>저가문자</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;background-color: rgb(232, 217, 255);">폰문자</th-->
                        <th class="text-center" height="40" style="padding:0;letter-spacing:-1px;">알림톡</th>
                        <th class="text-center" height="40" style="padding:0;letter-spacing:-1px;">이미지<br/>알림톡</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">친구톡<br/>텍스트</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">친구톡<br/>이미지</th>
                        <!-- <th class="text-center" style="padding:0;letter-spacing:-1px;">친구톡<br/>와이드<br />이미지</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(A)</th> -->
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(A)<BR>SMS</th>-->
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(A)<BR>MMS</th>-->
						<!--
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(B)</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(B)<BR>SMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(B)<BR>MMS</th>
						-->
						<? if($this->member->item('mem_level')>=30) {
						?>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(C)<BR>SMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(C)<BR>LMS</th>
                        <? } else { ?>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">문자<BR>SMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">문자<BR>LMS</th>
                        <? } ?>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">RCS<BR>SMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">RCS<BR>LMS</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">RCS<BR>MMS</th>
                        <!--<th class="text-center" style="padding:0;letter-spacing:-1px;">WEB(C)<BR>MMS</th>-->
                        <!-- th class="text-center" style="padding:0;letter-spacing:-1px;">015<BR>저가문자</th>
                        <th class="text-center" style="padding:0;letter-spacing:-1px;">폰문자</th-->
                    </tr>
                    </thead>
                    <tbody>
                        <?
                        $row_no = $list_cnt + 1;
                        $sum_req=0;
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
                        // RCS 추가
                        $sum_rcs_sms = 0;
                        $sum_rcs = 0;
                        $sum_rcs_mms = 0;
                        $sum_rcs_tpl = 0;
                        // 인센티브 추가
                        $sum_incentive = 0;

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
                        // RCS 추가
                        $sum_err_rcs_sms = 0;
                        $sum_err_rcs = 0;
                        $sum_err_rcs_mms = 0;
                        $sum_err_rcs_tpl = 0;

                        $succ = 0;
                        foreach($list as $r) {
                            $row_no -= 1;
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
                                    +$r->mst_smt_mms
                                    +$r->mst_rcs_sms
                                    +$r->mst_rcs
                                    +$r->mst_rcs_mms
                                    +$r->mst_rcs_tpl;
                                    //+$r->qty_wait + $r->qty_rcs_wait;

                            //$fail = $r->mst_qty - ( $succ + $r->qty_wait);
                            $fail = $r->mst_qty - $succ - $r->reserved_qty;

                            $err_phn = $r->qty_phn;

                            $sum_req+=$r->mst_qty;
                            $sum_reserved+=$r->reserved_qty;
                            $sum_ok+=$succ;
                            if($this->member->item('mem_level')>50) {
                                $sum_wait += ($r->qty_wait + $r->qty_rcs_wait);
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
                            // RCS 추가
                            $sum_rcs_sms+=$r->mst_rcs_sms;
                            $sum_rcs+=$r->mst_rcs;
                            $sum_rcs_mms+=$r->mst_rcs_mms;
                            $sum_rcs_tpl+=$r->mst_rcs_tpl;

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
                            // RCS 추가
                            $sum_err_rcs_sms+=$r->qty_rcs_sms;
                            $sum_err_rcs+=$r->qty_rcs;
                            $sum_err_rcs_mms+=$r->qty_rcs_mms;
                            $sum_err_rcs_tpl+=$r->qty_rcs_tpl;

                            $sum_incentive+=round($r->incen, 1);
                            
                            ?>
							<tr>
                            	<td class="text-center" style="padding:5px 0;"><? echo $row_no; ?></td> <? //순번?>
                            	<td class="text-center" style="padding:5px 0;"><? echo $r->parent_name; ?></td> <? //소속?>
                                <td class="text-center" style="padding:5px 0;"><? echo $r->mem_username; ?></td> <? //업체명?>
                                <? if($this->member->item('mem_level')>50) { ?>
                                <td class="text-center" style="padding:5px 0;"><? echo ((($r->ds_name) ? $r->ds_name." ".$r->ds_position : '-')).(($r->dbo_name) ? " (".$r->dbo_name.")" : ''); ?></td>  <? //영업자/지점?>
                                <? }?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_qty)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->reserved_qty)?></td>
                                <? if($this->member->item('mem_level')>50) { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($succ)?> (<?=number_format($r->qty_wait)?>)</td>
                                <? } else {?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($succ)?></td>
                                <? }?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($fail)?></td>
                                <!-- td class="text-right" style="padding:8px 1px 8px 0px;" id='4' rowspan="2"><?=number_format($r->qty_wait)?></td-->
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_at)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_ai)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_ft)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_ft_img)?></td>
								<!--
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='7' rowspan="2"><?=number_format($r->mst_ft_wide_img)?></td>
                                <? if($this->member->item('mem_level')>=50) { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='8_1' rowspan="2"><?=number_format($r->mst_grs - $r->mst_grs_biz_qty)?></td><?//발송성공 > WEB(A)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='9' rowspan="2"><?=number_format($r->mst_grs_biz_qty)?></td>
                                <? } else { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='8_2' rowspan="2"><?=number_format($r->mst_grs)?></td>
                                <? } ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='10' rowspan="2"><?=number_format($r->mst_grs_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='11' rowspan="2"><?=number_format($r->mst_grs_mms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='12' rowspan="2"><?=number_format($r->mst_nas)?></td><?//발송성공 > WEB(B)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='13' rowspan="2"><?=number_format($r->mst_nas_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='14' rowspan="2"><?=number_format($r->mst_nas_mms)?></td>
								-->
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_smt_sms)?></td><?//발송성공 > WEB(C)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_smt)?></td>
                                <!--<td class="text-right" style="padding:8px 1px 8px 0px;" id='17' rowspan="2"><?=number_format($r->mst_smt_mms)?></td>-->
                                <!-- <td class="text-right" style="padding:8px 1px 8px 0px;" rowspan="2"><?=number_format($r->mst_015)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" rowspan="2"><?=number_format($r->mst_phn)?></td> -->
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_rcs_sms)?></td><?//발송성공 > RCS?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_rcs)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->mst_rcs_mms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->err_at)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->err_ai)?></td>
								<td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->err_ft)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->err_ft_img)?></td>
								<!--
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='20' rowspan="2"><?=number_format($r->err_ft_wide_img)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='21' rowspan="2"><?=number_format($r->qty_grs)?></td><?//발송실패 > WEB(A)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='22' rowspan="2"><?=number_format($r->qty_grs_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='23' rowspan="2"><?=number_format($r->qty_grs_mms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='24' rowspan="2"><?=number_format($r->qty_nas)?></td><?//발송실패 > WEB(B)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='25' rowspan="2"><?=number_format($r->qty_nas_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" id='26' rowspan="2"><?=number_format($r->qty_nas_mms)?></td>
								-->
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->qty_smt_sms)?></td><?//발송실패 > WEB(C)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->qty_smt)?></td>
                                <!--<td class="text-right" style="padding:8px 1px 8px 0px;" id='29' rowspan="2"><?=number_format($r->qty_smt_mms)?></td>-->
                                <!-- <td class="text-right" style="padding:8px 1px 8px 0px;" rowspan="2"><?=number_format($r->qty_015)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;" rowspan="2"><?=number_format($err_phn)?></td> -->
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->qty_rcs_sms)?></td><?//발송실패 > RCS?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->qty_rcs)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->qty_rcs_mms)?></td>
                                <? if($this->member->item('mem_level')>90) { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($r->incen, 1)?></td>
                                <? }?>
                            </tr>
                            <!--
                            <tr>
                                <td class="text-center" style="padding:5px 0; background-color:#f1f1f1;" id='0'><? echo $r->parent_name; ?></td>
                                <? if($this->member->item('mem_level')>50) { ?>
                                <td class="text-center" style="padding:5px 0; background-color:#f1f1f1;" id='0'><? echo (($r->dbo_name) ? $r->dbo_name : '-'); ?></td>
                                <? }?>
                            </tr>
                            -->
                    <?}
                    if($this->member->item('mem_level')>=50) {
                        $sum_grs = $sum_grs - $sum_grs_biz;
                    }
                    ?>
                     </tbody>
                     <tfoot>
                            <tr>
                            	<? if($this->member->item('mem_level')>50) { ?>
                                <td class="text-center" style="padding:0;" colspan="4">합계</td>
                                <? } else { ?>
                                <td class="text-center" style="padding:0;" colspan="3">합계</td>
                                <? } ?>
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
								<!--
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(250, 236, 197);"><?=number_format($sum_ft_wide_img)?></td>
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
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_smt_sms)?></td><?//발송성공 > WEB(C)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_smt)?></td>
                                <!--<td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_smt_mms)?></td>-->
                                <!-- <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_015)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(232, 217, 255);"><?=number_format($sum_phn)?></td> -->
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_rcs_sms)?></td><?//발송성공 > RCS?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_rcs)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;background-color: rgb(218, 217, 255);"><?=number_format($sum_rcs_mms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_at)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_ai)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_ft)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_ft_img)?></td>
                                <!--
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_ft_wide_img)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_grs)?></td><?//발송실패 > WEB(A)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_grs_sms)?></td>
								<td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_grs_mms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_nas)?></td><?//발송실패 > WEB(B)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_nas_sms)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_nas_mms)?></td>
								-->
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_smt_sms)?></td><?//발송실패 > WEB(C)?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_smt)?></td>
                                <!--<td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_smt_mms)?></td>-->
                                <!-- <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_015)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_phn)?></td> -->
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_rcs_sms)?></td><?//발송실패 > RCS?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_rcs)?></td>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_err_rcs_mms)?></td>
                                <? if($this->member->item('mem_level')>90) { ?>
                                <td class="text-right" style="padding:8px 1px 8px 0px;"><?=number_format($sum_incentive, 1)?></td>
                                <? }?>
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

	$("select").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
		search();
	});


	$(document).ready(function() {
		$('#branch').multiSelect({
			'noneText': '지점 선택',
			'presets' : [{
				name: 'All',
				options: []
			},{
				name: '전체 지점',
				all: true
			}]
		});

		$('#salesperson').multiSelect({
			'presetsHTML':'<div class="multi-select-presets border_n">',
			'noneText': '영업자 선택',
			'presets' : [{
				name: '선택 전체 해제',
				options: []
			}]
		});

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
		format: "yyyy-mm",
		todayHighlight: true,
		viewMode: "months",
		minViewMode: "months",
		language: "kr",
		autoclose: true,
		startDate: '-2y'
	}).on('changeDate', function (selected) {
		search();
//             var startDate = new Date(selected.date.valueOf());
//             $('#endDate').datepicker('setStartDate', startDate);
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


	function test() {
		var text = $('#branch').val();
		for(var i=0; i<text.length; i++) {
			alert(text[i]);
		}
	}

	function page(page) {
		var form = document.getElementById('mainForm');
		var pageField = document.createElement("input");
		pageField.setAttribute("type", "hidden");
		pageField.setAttribute("name", "page");
		pageField.setAttribute("value", page);
		form.appendChild(pageField);
		form.submit();
	}

	function search() {
		if ($('#startDate').val() == "") {
			$(".content").html("조회하실 기간을 선택해주세요.");
			$("#myModal").modal('show');
			return;
		}


		var startDate = $("#startDate").val();
		var pf_key = $("#pf_key").val();

		var branch_value = "";
		var salesperson_value = "";
		var zero_include_value = "NO";	// NO:미발송업체 미포함, 'YES':미발송업체 포함
		var orderby_value = "";
		var managerType = "";

		<? if($this->member->item('mem_level')>50 || $this->member->item('mem_id') == '962') { ?>
		var branch = $("#branch").val();
		if (branch != null && branch.length > 0) {
			branch_value = branch.join(',');
		}

		var salesperson = $("#salesperson").val();
		if (salesperson != null && salesperson.length > 0) {
			salesperson_value = salesperson.join(',');
		}
		<? } ?>

		<? if($this->member->item('mem_level')>=50 || $this->member->item('mem_id') == '962') { ?>
		if($('#zero_include').is(":checked") == true) {
			zero_include_value = "YES";
		}
		orderby_value = $('#orderby').val();
		//return;

		<? } ?>
		
		<? if($this->member->item('mem_level')==17) { ?>
		if($('#zero_include').is(":checked") == true) {
			zero_include_value = "YES";
		}
		orderby_value = $('#orderby').val();
		managerType = $('#managerType').val();
		<? } ?>

		var form = document.createElement("form");

		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/statistics/monthly");

		var scrfField = document.createElement("input");
		scrfField.setAttribute("type", "hidden");
		scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		form.appendChild(scrfField);

		var startDateField = document.createElement("input");
		startDateField.setAttribute("type", "hidden");
		startDateField.setAttribute("name", "startDate");
		startDateField.setAttribute("value", startDate);
		form.appendChild(startDateField);

		var pf_keyField = document.createElement("input");
		pf_keyField.setAttribute("type", "hidden");
		pf_keyField.setAttribute("name", "pf_key");
		pf_keyField.setAttribute("value", pf_key);
		form.appendChild(pf_keyField);

		var branchField = document.createElement("input");
		branchField.setAttribute("type", "hidden");
		branchField.setAttribute("name", "branch");
		branchField.setAttribute("value", branch_value);
		form.appendChild(branchField);

		var salespersonField = document.createElement("input");
		salespersonField.setAttribute("type", "hidden");
		salespersonField.setAttribute("name", "salesperson");
		salespersonField.setAttribute("value", salesperson_value);
		form.appendChild(salespersonField);

		var orderbyField = document.createElement("input");
		orderbyField.setAttribute("type", "hidden");
		orderbyField.setAttribute("name", "orderby");
		orderbyField.setAttribute("value", orderby_value);
		form.appendChild(orderbyField);

		var zero_includeField = document.createElement("input");
		zero_includeField.setAttribute("type", "hidden");
		zero_includeField.setAttribute("name", "zero_include");
		zero_includeField.setAttribute("value", zero_include_value);
		form.appendChild(zero_includeField);

		var managerTypeField = document.createElement("input");
		managerTypeField.setAttribute("type", "hidden");
		managerTypeField.setAttribute("name", "managerType");
		managerTypeField.setAttribute("value", managerType);
		form.appendChild(managerTypeField);
	
		jsLoading();
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
			<?
			$row_no = $list_cnt + 1;
			$sum_req=0;
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
            // RCS 추가
            $sum_rcs_sms = 0;
            $sum_rcs = 0;
            $sum_rcs_mms = 0;
            $sum_rcs_tpl = 0;

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
            // RCS 추가
            $sum_err_rcs_sms = 0;
            $sum_err_rcs = 0;
            $sum_err_rcs_mms = 0;
            $sum_err_rcs_tpl = 0;
            // 인센티브 추가
            $sum_incentive = 0;
            
            $succ = 0;
            $user_falg = "U";

            foreach($list as $r) {
                $row_no -= 1;
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
                        +$r->mst_smt_mms
                        +$r->mst_rcs_sms
                        +$r->mst_rcs
                        +$r->mst_rcs_mms
                        +$r->mst_rcs_tpl;
                        //+$r->qty_wait + $r->qty_rcs_wait;

                //$fail = $r->mst_qty - ( $succ + $r->qty_wait);
                $fail = $r->mst_qty - $succ - $r->reserved_qty;

                $err_phn = $r->qty_phn;

                $sum_req+=$r->mst_qty;
                $sum_reserved+=$r->reserved_qty;
                $sum_ok+=$succ;
                if($this->member->item('mem_level')>50) {
                    $user_falg = "A";
                    $sum_wait += ($r->qty_wait + $r->qty_rcs_wait);
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
                // RCS 추가
                $sum_rcs_sms+=$r->mst_rcs_sms;
                $sum_rcs+=$r->mst_rcs;
                $sum_rcs_mms+=$r->mst_rcs_mms;
                $sum_rcs_tpl+=$r->mst_rcs_tpl;

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
                // RCS 추가
                $sum_err_rcs_sms+=$r->qty_rcs_sms;
                $sum_err_rcs+=$r->qty_rcs;
                $sum_err_rcs_mms+=$r->qty_rcs_mms;
                $sum_err_rcs_tpl+=$r->qty_rcs_tpl;

                $sum_incentive+=round($r->incen, 1);
                
                // $pf_ynm = $r->mem_username.((empty($r->parent_name)) ? '' : "(".$r->parent_name.")");
                // $salesperson = $r->ds_name." ".$r->ds_position.((empty($r->dbo_name)) ? '' : "(".$r->dbo_name.")");
                $salesperson = $r->ds_name." ".$r->ds_position;
// 							$pf_ynm = $r->mem_username."(".$r->parent_name.")";
// 							$salesperson = $r->ds_name." ".$r->ds_position."(".$r->dbo_name.")"
				?>
				  sep = {
						<? /* ?>
						"pf_ynm"				: '<?=($r->mem_username)?>',
						"salesperson"			: '<?=($r->ds_name."".$r->ds_position)?>',
						<? */ ?>
                    	"row_no"				: '<?=$row_no?>',
                    	"pf_pnm"				: '<?=($r->parent_name)?>',
                    	"pf_ynm"				: '<?=($r->mem_username)?>',
                    	"sales"					: '<?=($r->dbo_name)?>',
                    	"salesperson"			: '<?=$salesperson?>',
						"cnt_total"				: '<?=($r->mst_qty)?>',
						"cnt_reserved_total"	: '<?=($r->reserved_qty)?>',
						"cnt_succ_total"		: '<?=($succ)?>',
						"cnt_wait_total"		: '<?=($r->qty_wait)?>',
						"cnt_invalid_total"		: '<?=($fail)?>',
                        <? if($this->member->item('mem_level')>90) { ?>
                        "cnt_incentive"			: '<?=round($r->incen, 1)?>',
                        <? } ?>
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
						"cnt_succ_rcs"			: '<?=($r->mst_rcs)?>',
						"cnt_succ_rcs_sms"		: '<?=($r->mst_rcs_sms)?>',
						"cnt_succ_rcs_mms"		: '<?=($r->mst_rcs_mms)?>',
						"cnt_succ_rcs_tpl"		: '<?=($r->mst_rcs_tpl)?>',
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
						"cnt_fail_smt_mms"		: '<?=($r->qty_smt_mms)?>',
						"cnt_fail_rcs"			: '<?=($r->qty_rcs)?>',
						"cnt_fail_rcs_sms"		: '<?=($r->qty_rcs_sms)?>',
						"cnt_fail_rcs_mms"		: '<?=($r->qty_rcs_mms)?>',
						"cnt_fail_rcs_tpl"		: '<?=($r->qty_rcs_tpl)?>',
                        "pf_phone"				: '<?=($r->mem_emp_phone)?>'
						/*"cnt_fail_015"			: '<?=($r->qty_015)?>',
						"cnt_fail_phn"			: '<?=($r->qty_phn)?>'*/
				  };
				  val.push(sep);
				  <? /* ?>
				  sep = {
							"pf_ynm"				: '<?=($r->parent_name)?>',
							"salesperson"			: '<?=($r->dbo_name)?>',
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
							//"cnt_succ_015"			: '<?=($mem_price['mad_price_015'] - $parent_price['mad_price_015'])?>',
							//"cnt_succ_phn"			: '<?=($mem_price['mad_price_phn'] - $parent_price['mad_price_phn'])?>',
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
							//"cnt_fail_015"			: '',
							//"cnt_fail_phn"			: ''
					  };

				  sep = {
							"pf_ynm"				: '<?=($r->parent_name)?>',
							"salesperson"			: '<?=($r->dbo_name)?>',
							"cnt_total"				: '',
							"cnt_reserved_total"	: '',
							"cnt_succ_total"		: '',
							"cnt_wait_total"		: '',
							"cnt_invalid_total"		: '',
							"cnt_succ_kakao"		: '',
							"cnt_succ_kakao_img"	: '',
							"cnt_succ_friend"		: '',
							"cnt_succ_friend_img"	: '',
							"cnt_succ_friend_wide_img"	: '',
							<? if($this->member->item('mem_level')>=50) { ?>
							"cnt_succ_grs"			: '',
							"cnt_succ_grs_biz"		: '',
							<? } else { ?>
							"cnt_succ_grs"			: '',
							<? } ?>
							"cnt_succ_grs"			: '',
							"cnt_succ_grs_sms"		: '',
							"cnt_succ_nas"			: '',
							"cnt_succ_nas_sms"		: '',
							"cnt_succ_grs_mms"		: '',
							"cnt_succ_nas_mms"		: '',
							"cnt_succ_smt"			: '',
							"cnt_succ_smt_sms"		: '',
							"cnt_succ_smt_mms"		: '',
							//"cnt_succ_015"			: '',
							//"cnt_succ_phn"			: '',
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
							//"cnt_fail_015"			: '',
							//"cnt_fail_phn"			: ''
					  };
				  val.push(sep);
				  <? */ ?>
			  <?}
			  if($this->member->item('mem_level')>=50) {
				  $sum_grs = $sum_grs - $sum_grs_biz;
			  }
			  ?>
			  	  var get_title =
				  sum = {
						"get_total_row_cnt"					: '<?=($list_cnt)?>',
						"get_user_flag"                     : '<?=($user_falg)?>',
						"get_title"              	        : '<?=($param['startDate'])?>',
						"get_statistics_cnt_total"			: '<?=($sum_req)?>',
						"get_statistics_cnt_reserved"		: '<?=($sum_reserved)?>',
						"get_statistics_cnt_succ_total"		: '<?=($sum_ok)?>',
						"get_statistics_cnt_wait_total"		: '<?=($sum_wait)?>',
						"get_statistics_cnt_invalid_total"	: '<?=($sum_fail)?>',
                        <? if($this->member->item('mem_level')>90) { ?>
                        "get_statistics_cnt_incentive"		: '<?=$sum_incentive?>',
                        <? } ?>
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
						"get_statistics_cnt_succ_rcs"		: '<?=($sum_rcs)?>',
						"get_statistics_cnt_succ_rcs_sms"	: '<?=($sum_rcs_sms)?>',
						"get_statistics_cnt_succ_rcs_mms"	: '<?=($sum_rcs_mms)?>',
						"get_statistics_cnt_succ_rcs_tpl"	: '<?=($sum_rcs_tpl)?>',
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
						"get_statistics_cnt_fail_smt_mms"	: '<?=($sum_err_smt_mms)?>',
						"get_statistics_cnt_fail_rcs"		: '<?=($sum_err_rcs)?>',
						"get_statistics_cnt_fail_rcs_sms"	: '<?=($sum_err_rcs_sms)?>',
						"get_statistics_cnt_fail_rcs_mms"	: '<?=($sum_err_rcs_mms)?>',
						"get_statistics_cnt_fail_rcs_tpl"	: '<?=($sum_err_rcs_tpl)?>'
						/*"get_statistics_cnt_fail_015"		: '<?=($sum_err_015)?>',
						"get_statistics_cnt_fail_phn"		: '<?=($sum_err_phn)?>'*/
				  };
				  tot.push(sum);

			var value = JSON.stringify(val);
			var total = JSON.stringify(tot);
			var form = document.createElement("form");
			document.body.appendChild(form);
			form.setAttribute("method", "post");
			form.setAttribute("action", "/biz/statistics/download/index/2");

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

	function statistics_pages_load(path_str) {
		jsLoading();
		location.href = path_str;
	}
</script>
