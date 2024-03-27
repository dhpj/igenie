<style>
.table_stat_header table, .table_stat_body table{width:3500px !important;}
.table_stat_body table td{text-align: right !important;}
</style>
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
<div id="mArticle">
		<div class="white_box">
			<form action="/biz/manager/settlement" method="post" id="mainForm">
	        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
	        <input type='hidden' name='userid' id='userid' value='' />
				<div class="btn-group btn-group-sm" role="group">
					<label for="startDate">정산년월</label>&nbsp;
					<input type="text" class="form-control input-width-small inline datepicker" name="startDate" id="startDate" readonly="readonly" style="cursor: pointer;background-color: white" value="<?=$param['startDate']?>" onChange="submit_handler()">&nbsp;&nbsp;
					<label for="userid">중간관리자</label>&nbsp;
					<select name="uid" id="uid" class="select2 input-width-large search-static" onChange="submit_handler()">
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
					<button class="btn md" id="search" type="button" onclick="submit_handler()" style="float:right;">
						조회
					</button>
                </div>
				</form>
                <?php
                ob_start();
                ?>
                    <div class="btn-group pull-right" role="group" aria-label="..."></div>
                <?php
                $buttons = ob_get_contents();
                ob_end_flush();
                ?>

					<div class="table_stat">
						<div class="table_stat_header">
							<table cellspacing="0" cellpadding="0" border="0">
								<colgroup>
								<? if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
								<!--<col width="50px" />-->
								<? } ?>
								<col width="180px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="70px"/>
								<col width="70px"/>
								<col width="80px"/>
								<col width="100px"/>
								</colgroup>
			                    <thead>
			                        <tr>
			                        	<th class="align-center" rowspan="2">업체명</th>
			                            <th class="align-center" rowspan="2">Fullcare</th>
			                            <th class="align-center" colspan="3">알림톡</th>
			                            <th class="align-center" colspan="3">친구톡<br/>텍스트</th>
			                            <th class="align-center" colspan="3">친구톡<br/>이미지</th>
			                            <th class="align-center" colspan="3">친구톡<br/>와이드<br/>이미지</th>
			                            <th class="align-center" colspan="3">WEB(A)<BR>KT,LG</th>
			                            <th class="align-center" colspan="3">WEB(A)<BR>SKT</th>
			                            <th class="align-center" colspan="3">WEB(A)<BR>SMS</th>
			                            <th class="align-center" colspan="3">WEB(A)<BR>MMS</th>
			                            <th class="align-center" colspan="3">WEB(B)</th>
			                            <th class="align-center" colspan="3">WEB(B)<BR>SMS</th>
			                            <th class="align-center" colspan="3">WEB(B)<BR>MMS</th>
			                            <th class="align-center" colspan="3">WEB(C)</th>
			                            <th class="align-center" colspan="3">WEB(C)<BR>SMS</th>
			                            <th class="align-center" colspan="3">WEB(C)<BR>MMS</th>
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
		                    </table>
                    	</div>
                    <div class="table_stat_body">
                    <table cellspacing="0" cellpadding="0" border="0">
	                    <colgroup>
							<col width="180px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="70px"/>
							<col width="70px"/>
							<col width="80px"/>
							<col width="100px"/>
						</colgroup>
					<tbody>
                    <?php

                        //$sum_amt=0;
                        $sum_full_care = 0;
                        $sum_at_amt=0;
                        $sum_ft_amt=0;
                        $sum_ft_img_amt=0;
                        $sum_ft_w_img_amt=0;
                        $sum_grs_amt=0;
                        $sum_grs_biz_amt = 0;
                        $sum_grs_sms_amt=0;
                        $sum_grs_mms_amt=0;
                        $sum_nas_amt=0;
                        $sum_nas_sms_amt=0;
                        $sum_nas_mms_amt=0;
                        $sum_smt_amt=0;
                        $sum_smt_sms_amt=0;
                        $sum_smt_mms_amt=0;
                        //$sum_015_amt=0;
                        //$sum_phn_amt=0;

                        $tcnt = 0;

                        $sum_at=0;
                        $sum_ft=0;
                        $sum_ft_img=0;
                        $sum_ft_w_img=0;
                        //$sum_phn=0;
                        $sum_grs =0;
                        $sum_grs_biz =0;
                        $sum_grs_sms = 0;
                        $sum_grs_mms = 0;
                        $sum_nas = 0;
                        $sum_nas_sms = 0;
                        $sum_nas_mms = 0;
                        $sum_smt = 0;
                        $sum_smt_sms = 0;
                        $sum_smt_mms = 0;
                        //$sum_015 = 0;

                        //$sum_send = 0;
                        //$sum_send_amount = 0;
                        $sum_margin_amt = 0;

                        foreach ($list as $r) {
                            $row_margin_sum = 0;
                            $sum_at      = $sum_at      + $r->at;
                            $sum_ft      = $sum_ft      + $r->ft;
                            $sum_ft_img  = $sum_ft_img  + $r->ft_img;
                            $sum_ft_w_img  = $sum_ft_w_img  + $r->ft_w_img;
                            //$sum_phn     = $sum_phn     + $r->phn;
                            $sum_grs     = $sum_grs     + $r->grs;
                            $sum_grs_biz = $sum_grs_biz + $r->grs_biz;
                            $sum_grs_sms = $sum_grs_sms + $r->grs_sms;
                            $sum_grs_mms = $sum_grs_mms + $r->grs_mms;
                            $sum_nas     = $sum_nas     + $r->nas;
                            $sum_nas_sms = $sum_nas_sms + $r->nas_sms;
                            $sum_nas_mms = $sum_nas_mms + $r->nas_mms;
                            $sum_smt     = $sum_smt     + $r->smt;
                            $sum_smt_sms = $sum_smt_sms + $r->smt_sms;
                            $sum_smt_mms = $sum_smt_mms + $r->smt_mms;
                            //$sum_015     = $sum_015     + $r->f_015;
                            //$view_flag = ($this->member->item("mem_userid") == $r->mem_userid || $this->member->item("mem_userid") == "dhn") ? true : false;
                            $view_flag = true;
                            if ($view_flag) {
                                $row_margin_sum = $r->at_amt + $r->ft_amt + $r->ft_img_amt + $r->ft_w_img_amt + $r->grs_amt + $r->grs_biz_amt + $r->grs_sms_amt + $r->grs_mms_amt + $r->nas_amt + $r->nas_sms_amt + $r->nas_mms_amt + $r->smt_amt + $r->smt_sms_amt + $r->smt_mms_amt + $r->full_care;
                                $sum_full_care = $sum_full_care + $r->full_care;
                                $sum_at_amt      = $sum_at_amt      + $r->at_amt;
                                $sum_ft_amt      = $sum_ft_amt      + $r->ft_amt;
                                $sum_ft_img_amt  = $sum_ft_img_amt  + $r->ft_img_amt;
                                //$sum_phn_amt     = $sum_phn_amt     + $r->phn_amt;
                                $sum_grs_amt     = $sum_grs_amt     + $r->grs_amt;
                                $sum_grs_biz_amt = $sum_grs_biz_amt + $r->grs_biz_amt;
                                $sum_grs_sms_amt = $sum_grs_sms_amt + $r->grs_sms_amt;
                                $sum_grs_mms_amt = $sum_grs_mms_amt + $r->grs_mms_amt;
                                $sum_nas_amt     = $sum_nas_amt     + $r->nas_amt;
                                $sum_nas_sms_amt = $sum_nas_sms_amt + $r->nas_sms_amt;
                                $sum_nas_mms_amt = $sum_nas_mms_amt + $r->nas_mms_amt;
                                $sum_smt_amt     = $sum_smt_amt     + $r->smt_amt;
                                $sum_smt_sms_amt = $sum_smt_sms_amt + $r->smt_sms_amt;
                                $sum_smt_mms_amt = $sum_smt_mms_amt + $r->smt_mms_amt;
                                //$sum_015_amt     = $sum_015_amt     + $r->f_015_amt;
                            }

                            $sum_margin_amt  = $sum_margin_amt  + $row_margin_sum;

                            ?>
                            <tr>
                            	<? if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
                            	<!--<td class="align-center"><input type="checkbox" name="row_check" value="<?=$r->end_mem_id ?>" onclick="offer_row_check();" class="uniform" ></td>-->
                            	<? } ?>
                            	<th class="align-center"><?php echo $r->vendor ?></th>
                            	<td class="align-right"><?php echo ($view_flag) ? number_format(round($r->full_care, 2)) : '0.00'; ?></td>
                                <td class="align-right"><?php echo number_format($r->at); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->at_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->at_amt) : 0 ?></td>
                                <td class="align-right"><?php echo number_format($r->ft); ?></th>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->ft_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->ft_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->ft_img); ?></th>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->ft_img_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->ft_img_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->ft_w_img); ?></th>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->ft_w_img_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->ft_w_img_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->grs); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->grs_margin, 2) :'0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->grs_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->grs_biz); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->grs_biz_margin, 2) :'0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->grs_biz_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->grs_sms); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->grs_sms_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->grs_sms_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->grs_mms); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->grs_mms_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->grs_mms_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->nas); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->nas_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->nas_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->nas_sms); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->nas_sms_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->nas_sms_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->nas_mms); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->nas_mms_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->nas_mms_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->smt); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->smt_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->smt_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->smt_sms); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->smt_sms_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->smt_sms_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($r->smt_mms); ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->smt_mms_margin, 2) : '0.00'; ?></td>
                                <td class="align-right"><?php echo ($view_flag) ? number_format($r->smt_mms_amt) : 0; ?></td>
                                <td class="align-right"><?php echo number_format($row_margin_sum); ?></td>
                            </tr>

							<?php
							$tcnt = $tcnt + 1;
                            }
                   if ( $tcnt < 1) {
                    ?>
                        <tr>
                            <td colspan="46" class="nopost"><p class="icon_nopost">자료가 없습니다.</p></td>
                        </tr>
                    <?php
                    } else {
                    ?>
                            <tr>
                            	<? if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
                            	<!--<th class="align-center"></th>-->
                            	<? } ?>
                                <th class="align-center">합  계</th>
                                <td class="align-right"><?php echo number_format(round($sum_full_care)); ?></td>
                                <td class="align-right"><?php echo number_format($sum_at); ?></td>
                                <td class="align-right" colspan="2" ><?php echo number_format($sum_at_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_ft); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_ft_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_ft_img); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_ft_img_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_ft_w_img); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_ft_w_img_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_grs); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_grs_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_grs_biz); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_grs_biz_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_grs_sms); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_grs_sms_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_grs_mms); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_grs_mms_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_nas); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_nas_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_nas_sms); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_nas_sms_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_nas_mms); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_nas_mms_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_smt); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_smt_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_smt_sms); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_smt_sms_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_smt_mms); ?></td>
                                <td class="align-right" colspan="2"><?php echo number_format($sum_smt_mms_amt); ?></td>
                                <td class="align-right"><?php echo number_format($sum_margin_amt); ?></td>
                            </tr>

                    <?
                    }
                    ?>
                    </tbody>
                </table>
			</div>
			</div>
					<!--
					<div class="btn-group pull-right" role="group" aria-label="...">
						<a href="/biz/manager/summary"><button type="button" class="btn btn-outline btn-success btn-sm">목록</button></a>
					</div>
					 -->

			<div class="align-left">
				<a type="button" class="btn btn-sm" onclick="download_summary()"><i class="icon-arrow-down"></i> 목록 엑셀 다운로드</a>
				<? if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
				<a type="button" class="btn btn-sm" onclick="download_monthly_settlement()"><i class="icon-arrow-down"></i> 월정산 엑셀 다운로드</a>
				<a type="button" class="btn btn-sm" onclick="download_day_settlement()"><i class="icon-arrow-down"></i> 일정산 엑셀 다운로드</a>
				<a type="button" class="btn btn-sm" onclick="download_monthly_settlement_parent()"><i class="icon-arrow-down"></i> 월정산상위관리지 엑셀 다운로드</a>
				<a type="button" class="btn btn-sm" onclick="download_settlement_email()"><i class="icon-arrow-down"></i> 정산 메일 발송용 엑셀 다운로드</a>
				<? } ?>
			</div>






		</div>
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
			 form.setAttribute("action", "/biz/manager/settlement/month_summary/"+$("#startDate").val());
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

<? if ($this->member->item('mem_userid') == 'dhnadmin' || $this->member->item('mem_userid') == 'dhn') { ?>
    function offer_row_check_all(){
      if($("#row_check_all").prop("checked")) {
          $("input:checkbox[name='row_check']").prop("checked",true);
      } else {
          $("input:checkbox[name='row_check']").prop("checked",false);
      }
      $.uniform.update();
    }

    function offer_row_check() {
    	var row_check_count = $("input:checkbox[name='row_check']").length;
    	var row_checked_count = $("input:checkbox[name='row_check']:checked").length;

    	if (row_check_count === row_checked_count) {
    		$("#row_check_all").prop("checked", true);
    	} else {
    		$("#row_check_all").prop("checked", false);
        }
    	$.uniform.update();
    }

	function offer_row_check_value(str_separator) {
		var row_check_value = "";

		$("input:checkbox[name='row_check']:checked").each(function() {
			row_check_value += $(this).val() + str_separator;
		});

		return row_check_value.slice(0, -1);
	}

	function offer_col_check_value(str_separator) {
		var row_check_value = "";

		$("input:checkbox[name='col_check']:checked").each(function() {
			row_check_value += $(this).val() + str_separator;
		});

		return row_check_value.slice(0, -1);
	}

 	function download_settlement_email() {
 	 	var rowCount = <?= $tcnt ?>;
 	 	if (rowCount < 1) {
			 $(".content").html("조회된 정산 자료가 없습니다.");
			 $("#myModal").modal('show');
 	 	} else {
 	 	 	if ($("input:checkbox[name='row_check']:checked").length < 1) {
 				 $(".content").html("선택된 업체가 없습니다.");
 				 $("#myModal").modal('show');
 	 	 	} else {
				var checked_value = offer_row_check_value(",");
				var val = {
						"mem_id" : checked_value,
						"period_name" : "<?=$param['startDate']?>"
					};

				var value = JSON.stringify(val);

                var form = document.createElement("form");
                document.body.appendChild(form);
                form.setAttribute("method", "post");
                form.setAttribute("action", "/biz/manager/settlement/download_email");
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
 	}

 	function download_monthly_settlement() {
 	 	var rowCount = <?= $tcnt ?>;
 	 	if (rowCount < 1) {
			 $(".content").html("조회된 정산 자료가 없습니다.");
			 $("#myModal").modal('show');
 	 	} else {
 	 	 	if ($("input:checkbox[name='row_check']:checked").length < 1) {
 				 $(".content").html("선택된 업체가 없습니다.");
 				 $("#myModal").modal('show');
 	 	 	} else {
				var checked_value = offer_row_check_value(",");
				var val = {
						"mem_id" : checked_value,
						"period_name" : "<?=$param['startDate']?>"
					};

				var value = JSON.stringify(val);

                var form = document.createElement("form");
                document.body.appendChild(form);
                form.setAttribute("method", "post");
                form.setAttribute("action", "/biz/manager/settlement/download_monthly_settlement");
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
 	}

 	function download_day_settlement() {
 	 	var rowCount = <?= $tcnt ?>;
 	 	if (rowCount < 1) {
			 $(".content").html("조회된 정산 자료가 없습니다.");
			 $("#myModal").modal('show');
 	 	} else {
 	 	 	if ($("input:checkbox[name='row_check']:checked").length < 1) {
                $(".content").html("선택된 업체가 없습니다.");
                $("#myModal").modal('show');
 	 	 	} else {
                if ($("input:checkbox[name='col_check']:checked").length < 4) {
                	$(".content").html("항목을 3개 이상 선택하세요.");
                	$("#myModal").modal('show');
				} else {
					var checked_value = offer_row_check_value(",");
					var checked_col_value = offer_col_check_value(",");

					var val = {
							"mem_id" : checked_value,
							"col_part_name" : checked_col_value,
							"period_name" : "<?=$param['startDate']?>"
						};

					var value = JSON.stringify(val);

	                var form = document.createElement("form");
	                document.body.appendChild(form);
	                form.setAttribute("method", "post");
	                form.setAttribute("action", "/biz/manager/settlement/download_day_settlement");
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
 	 	}
 	}

	function download_monthly_settlement_parent() {
 	 	var rowCount = <?= $tcnt ?>;
 	 	if (rowCount < 1) {
			 $(".content").html("조회된 정산 자료가 없습니다.");
			 $("#myModal").modal('show');
 	 	} else {
			var val = {
					"mem_userid" : "<?=$param['userid'] ?>",
					"period_name" : "<?=$param['startDate']?>"
				};

			var value = JSON.stringify(val);

            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/manager/settlement/download_monthly_settlement_parent");
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

	<? } ?>

	//CSV 파일 다운로드
	function download_summary() {
		var rowCount = <?= $tcnt ?>;
		if (rowCount < 1) {
			 $(".content").html("조회된 정산 자료가 없습니다.");
			 $("#myModal").modal('show');

		} else {
            var val = [];
            var tot = [];
            <?php
                //$sum_amt=0;
                $sum_full_care = 0;
                $sum_at_amt=0;
                $sum_ft_amt=0;
                $sum_ft_img_amt=0;
                $sum_ft_w_img_amt=0;
                $sum_grs_amt=0;
                $sum_grs_biz_amt = 0;
                $sum_grs_sms_amt=0;
                $sum_grs_mms_amt=0;
                $sum_nas_amt=0;
                $sum_nas_sms_amt=0;
                $sum_nas_mms_amt=0;
                $sum_smt_amt=0;
                $sum_smt_sms_amt=0;
                $sum_smt_mms_amt=0;
                //$sum_015_amt=0;
                //$sum_phn_amt=0;

                $tcnt = 0;

                $sum_at=0;
                $sum_ft=0;
                $sum_ft_img=0;
                $sum_ft_w_img=0;
                //$sum_phn=0;
                $sum_grs =0;
                $sum_grs_biz =0;
                $sum_grs_sms = 0;
                $sum_grs_mms = 0;
                $sum_nas = 0;
                $sum_nas_sms = 0;
                $sum_nas_mms = 0;
                $sum_smt = 0;
                $sum_smt_sms = 0;
                $sum_smt_mms = 0;
                //$sum_015 = 0;

                //$sum_send = 0;
                //$sum_send_amount = 0;
                $sum_margin_amt = 0;

                  foreach ($list as $r) {
                      $row_margin_sum = 0;
                      $sum_at      = $sum_at      + $r->at;
                      $sum_ft      = $sum_ft      + $r->ft;
                      $sum_ft_img  = $sum_ft_img  + $r->ft_img;
                      $sum_ft_w_img  = $sum_ft_w_img  + $r->ft_w_img;
                      //$sum_phn     = $sum_phn     + $r->phn;
                      $sum_grs     = $sum_grs     + $r->grs;
                      $sum_grs_biz = $sum_grs_biz + $r->grs_biz;
                      $sum_grs_sms = $sum_grs_sms + $r->grs_sms;
                      $sum_grs_mms = $sum_grs_mms + $r->grs_mms;
                      $sum_nas     = $sum_nas     + $r->nas;
                      $sum_nas_sms = $sum_nas_sms + $r->nas_sms;
                      $sum_nas_mms = $sum_nas_mms + $r->nas_mms;
                      $sum_smt     = $sum_smt     + $r->smt;
                      $sum_smt_sms = $sum_smt_sms + $r->smt_sms;
                      $sum_smt_mms = $sum_smt_mms + $r->smt_mms;
                      //$sum_015     = $sum_015     + $r->f_015;
                      //$view_flag = ($this->member->item("mem_userid") == $r->mem_userid || $this->member->item("mem_userid") == "dhn") ? true : false;
                      $row_margin_sum = $r->at_amt + $r->ft_amt + $r->ft_img_amt + $r->ft_w_img_amt + $r->grs_amt + $r->grs_biz_amt + $r->grs_sms_amt + $r->grs_mms_amt + $r->nas_amt + $r->nas_sms_amt + $r->nas_mms_amt + $r->smt_amt + $r->smt_sms_amt + $r->smt_mms_amt + $r->full_care;
                      $sum_full_care = $sum_full_care + $r->full_care;
                      $sum_at_amt      = $sum_at_amt      + $r->at_amt;
                      $sum_ft_amt      = $sum_ft_amt      + $r->ft_amt;
                      $sum_ft_img_amt  = $sum_ft_img_amt  + $r->ft_img_amt;
                      $sum_ft_w_img_amt  = $sum_ft_w_img_amt  + $r->ft_w_img_amt;
                      //$sum_phn_amt     = $sum_phn_amt     + $r->phn_amt;
                      $sum_grs_amt     = $sum_grs_amt     + $r->grs_amt;
                      $sum_grs_biz_amt = $sum_grs_biz_amt + $r->grs_biz_amt;
                      $sum_grs_sms_amt = $sum_grs_sms_amt + $r->grs_sms_amt;
                      $sum_grs_mms_amt = $sum_grs_mms_amt + $r->grs_mms_amt;
                      $sum_nas_amt     = $sum_nas_amt     + $r->nas_amt;
                      $sum_nas_sms_amt = $sum_nas_sms_amt + $r->nas_sms_amt;
                      $sum_nas_mms_amt = $sum_nas_mms_amt + $r->nas_mms_amt;
                      $sum_smt_amt     = $sum_smt_amt     + $r->smt_amt;
                      $sum_smt_sms_amt = $sum_smt_sms_amt + $r->smt_sms_amt;
                      $sum_smt_mms_amt = $sum_smt_mms_amt + $r->smt_mms_amt;
                      //$sum_015_amt     = $sum_015_amt     + $r->f_015_amt;

                      $sum_margin_amt  = $sum_margin_amt  + $row_margin_sum;

                      ?>

				  sep = {
						"vendor"		: '<?=$r->vendor?>',
						"full_care"		: '<?=$r->full_care?>',
						"at"			: '<?=$r->at?>',
						"at_margin"		: '<?=$r->at_margin?>',
						"at_amt"		: '<?=$r->at_amt?>',
						"ft"			: '<?=$r->ft?>',
						"ft_margin"		: '<?=$r->ft_margin?>',
						"ft_amt"		: '<?=$r->ft_amt?>',
						"ft_img"		: '<?=$r->ft_img?>',
						"ft_img_margin"	: '<?=$r->ft_img_margin?>',
						"ft_img_amt"	: '<?=$r->ft_img_amt?>',
						"ft_w_img"		: '<?=$r->ft_w_img?>',
						"ft_w_img_margin"	: '<?=$r->ft_w_img_margin?>',
						"ft_w_img_amt"	: '<?=$r->ft_w_img_amt?>',
						"grs"			: '<?=$r->grs?>',
						"grs_margin"	: '<?=$r->grs_margin?>',
						"grs_amt"		: '<?=$r->grs_amt?>',
						"grs_biz"		: '<?=$r->grs_biz?>',
						"grs_biz_margin": '<?=$r->grs_biz_margin?>',
						"grs_biz_amt"	: '<?=$r->grs_biz_amt?>',
						"grs_sms"		: '<?=$r->grs_sms?>',
						"grs_sms_margin": '<?=$r->grs_sms_margin?>',
						"grs_sms_amt"	: '<?=$r->grs_sms_amt?>',
						"grs_mms"		: '<?=$r->grs_mms?>',
						"grs_mms_margin": '<?=$r->grs_mms_margin?>',
						"grs_mms_amt"	: '<?=$r->grs_mms_amt?>',
						"nas"			: '<?=$r->nas?>',
						"nas_margin"	: '<?=$r->nas_margin?>',
						"nas_amt"		: '<?=$r->nas_amt?>',
						"nas_sms"		: '<?=$r->nas_sms?>',
						"nas_sms_margin": '<?=$r->nas_sms_margin?>',
						"nas_sms_amt"	: '<?=$r->nas_sms_amt?>',
						"nas_mms"		: '<?=$r->nas_mms?>',
						"nas_mms_margin": '<?=$r->nas_mms_margin?>',
						"nas_mms_amt"	: '<?=$r->nas_mms_amt?>',
						"smt"			: '<?=$r->smt?>',
						"smt_margin"	: '<?=$r->smt_margin?>',
						"smt_amt"		: '<?=$r->smt_amt?>',
						"smt_sms"		: '<?=$r->smt_sms?>',
						"smt_sms_margin": '<?=$r->smt_sms_margin?>',
						"smt_sms_amt"	: '<?=$r->smt_sms_amt?>',
						"smt_mms"		: '<?=$r->smt_mms?>',
						"smt_mms_margin": '<?=$r->smt_mms_margin?>',
						"smt_mms_amt"	: '<?=$r->smt_mms_amt?>',
						"row_margin"	: '<?=$row_margin_sum?>'

				  };
				  val.push(sep);
			  <?}?>
				  sum = {
						"full_care"		: '<?=$sum_full_care?>',
                        "at"			: '<?=$sum_at?>',
                        "at_amt"		: '<?=$sum_at_amt?>',
                        "ft"			: '<?=$sum_ft?>',
                        "ft_amt"		: '<?=$sum_ft_amt?>',
                        "ft_img"		: '<?=$sum_ft_img?>',
                        "ft_img_amt"	: '<?=$sum_ft_img_amt?>',
                        "ft_w_img"		: '<?=$sum_ft_w_img?>',
                        "ft_w_img_amt"	: '<?=$sum_ft_w_img_amt?>',
                        "grs"			: '<?=$sum_grs?>',
                        "grs_amt"		: '<?=$sum_grs_amt?>',
                        "grs_biz"		: '<?=$sum_grs_biz?>',
                        "grs_biz_amt"	: '<?=$sum_grs_biz_amt?>',
                        "grs_sms"		: '<?=$sum_grs_sms?>',
                        "grs_sms_amt"	: '<?=$sum_grs_sms_amt?>',
                        "grs_mms"		: '<?=$sum_grs_mms?>',
                        "grs_mms_amt"	: '<?=$sum_grs_mms_amt?>',
                        "nas"			: '<?=$sum_nas?>',
                        "nas_amt"		: '<?=$sum_nas_amt?>',
                        "nas_sms"		: '<?=$sum_nas_sms?>',
                        "nas_sms_amt"	: '<?=$sum_nas_sms_amt?>',
                        "nas_mms"		: '<?=$sum_nas_mms?>',
                        "nas_mms_amt"	: '<?=$sum_nas_mms_amt?>',
                        "smt"			: '<?=$sum_smt?>',
                        "smt_amt"		: '<?=$sum_smt_amt?>',
                        "smt_sms"		: '<?=$sum_smt_sms?>',
                        "smt_sms_amt"	: '<?=$sum_smt_sms_amt?>',
                        "smt_mms"		: '<?=$sum_smt_mms?>',
                        "smt_mms_amt"	: '<?=$sum_smt_mms_amt?>',
                        "row_sum_margin": '<?=$sum_margin_amt?>'
				  };
				  tot.push(sum);

             var value = JSON.stringify(val);
             var total = JSON.stringify(tot);

			 var form = document.createElement("form");
			 document.body.appendChild(form);
			 form.setAttribute("method", "post");
			 form.setAttribute("action", "/biz/manager/settlement/download/offer");
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
