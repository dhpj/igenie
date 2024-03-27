	<div class="modal fade" id="myModalImg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" xmlns="http://www.w3.org/1999/html" style="overflow-y: hidden; width: 100%; height: 100%">
		<div class="modal-dialog modal-lg" id="modal" style="width:620px;">
			<div class="modal-content" style="width:620px !important;">
				<div class="modal-body">
					<div class="content imgw100"></div>
					<div class="mg_t20 mg_b20">
							<button type="button" class="btn_st1" data-dismiss="modal">확인</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-left: 40%; margin-top: 15%; overflow: hidden;">
		<div class="modal-dialog modal-lg" id="modal">
			<div class="modal-content" style="width: 320px;">
				<br>
				<div class="modal-body" style="height: 120px;">
					<div class="content"></div>
					<div>
						<p align="right">
							<br><br>
							<button type="button" class="btn btn-primary" data-dismiss="modal">확인</button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	 <div class="tit_wrap">
      파트너 관리
    </div>
    <div class="content_wrap">
		<div id="mArticle">
		<div class="form_section">
		<div class="inner_tit">
			<h3>파트너 상세보기</h3>
			<span class="fr">
			  <!--<a type="button" href="/biz/partner/edit?<?=$param['key']?>" class="btn_insert_p">수정하기</a>-->
			  <?if($this->member->item("mem_level")>=10) {?>
			  <input type="button" class="btn_userchange" id="check" value="<?=$rs->mem_username?> 계정으로 보기" onclick="document.location.href='/biz/main?<?=$rs->mem_id?>'"/>
			  <?}?>
			</span>
		</div>
	<div class="white_box">
		<form action="/partner/view/aaa#">
			<table class="tpl_ver_form" width="100%">
				<colgroup>
					<col width="200">
					<col width="*">
				</colgroup>
				<tbody>
				<tr>
					<th style="vertical-align: middle !important;">계정</th>
<?if($this->member->item('mem_level')>=$this->Biz_model->manager_lv) {?>
					<td><?=$rs->mem_userid?></td>
<?} else {?>
					<td colspan="3"><?=$rs->mem_userid?></td>
<?}?>
				</tr>
				<tr>
					<th style="vertical-align: middle !important;">업체명</th>
<?if($this->member->item('mem_level')>=$this->Biz_model->manager_lv) {?>
					<td><?=$rs->mem_username?></td>
<?} else {?>
					<td colspan="3"><?=$rs->mem_userid?></td>
<?}?>
				</tr>
				<tr>
					<th style="vertical-align: middle !important;">잔액</th>
					<? if($rs->mem_pay_type == 'A') { ?>
					<td>후불제</td>
					<? } else { ?>
					<td>예치금 : <?=number_format($rs->total_deposit)?>원<?=($rs->voucher_deposit>0&&$rs->mem_voucher_yn=="Y")? " + 바우처 : ".number_format($rs->voucher_deposit)."원" : "" ?> (사용가능 : <font color='red' style="letter-spacing:1px;"><strong><?php echo number_format($this->Biz_dhn_model->getAbleCoin($rs->mem_id, $rs->mem_userid)+(($rs->voucher_deposit>0&&$rs->mem_voucher_yn=="Y")? $rs->voucher_deposit : 0)); ?></strong></font>원)</td>
					<? } ?>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
    <!-- <div class="snb_nav" style="margin-bottom: -2px; margin-left: 1px">
        <ul class="clear">
            <li class="active"><a href="/biz/partner/view?<?=$param['key']?>">정보</a></li>
            <li><a href="/biz/partner/partner_charge?<?=$param['key']?>">충전내역</a></li>
            <li><a href="/biz/partner/partner_sent?<?=$param['key']?>">발신목록</a></li>
            <li><a href="/biz/partner/partner_recipient?<?=$param['key']?>">고객리스트</a></li>
        </ul>
    </div> -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content" style="width: 320px;">
                <br>
                <div class="modal-body" style="height: 120px;">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br><br>
                            <button type="button" class="btn btn-custom" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="white_box mg_t20">
                <form action="/partner/view/aaa#" style="display:inline-block;">
													<div class="busi_box_l">
														<?	if($rs->mem_photo) {?>
																										<a href="#" onclick="show_sheet('/mem_sheet/<?=$rs->mem_photo?>')"><img src="/mem_sheet/<?=$rs->mem_photo?>"/></a>&nbsp;
														<?	}
															//foreach($biz_sheet as $bz) {?>
														<!--  											<a href="#" onclick="show_sheet('/biz_sheet/<?=$bz["spf_biz_sheet"]?>')"><img src="/biz_sheet/<?=$bz["spf_biz_sheet"]?>" style="height:50px;" /></a>&nbsp;-->
														<?	//}?>
							  <div class="name">사업자등록증</div>
							</div>
							<div class="busi_box_r">
                            <table class="tpl_ver_form" width="100%">
                                <colgroup>
                                    <col width="200">
                                    <col width="*">
                                </colgroup>
                                <tbody>
                                <tr>
                                    <th style="vertical-align: middle; line-height:130%; height: 45px; !important;">전화번호<br>(SMS 발신번호)</th>
                                    <td><?=$this->funn->format_phone($rs->mem_phone,"-")?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">담당자이름</th>
                                    <td><?=$rs->mem_nickname?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">담당자 연락처</th>
                                    <td><?=$this->funn->format_phone($rs->mem_emp_phone,"-")?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">담당자 이메일</th>
                                    <td><?=$rs->mem_email?></td>
                                </tr>
                                <tr style="display:none">
                                    <th style="vertical-align: middle !important;">플러스 친구 매니저 계정</th>
                                    <td><?=$rs->mem_pf_manager?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">송금인 이름</th>
                                    <td><?=$rs->mem_bank_user?></td>
                                </tr>
                                <tr style="display:none">
                                    <th style="vertical-align: middle !important;">매장URL(카페,블로그)</th>
                                    <td><?=$rs->mad_free_hp?></td>
                                </tr>
                                <tr>
                                    <th>일 발신한도 지정</th>
                                    <td style="line-height:2.3;"><?=number_format($rs->mem_day_limit)?>건&nbsp;&nbsp;<span class="pt20">( ※ 0으로 지정하면 제한없음 )</span></td>
                                </tr>
                                <!-- 2019.10.09 Full Care 종류 -->
                                <tr style="display:none">
                                    <th style="vertical-align: middle !important;">Full Care 종류</th>
                                    <td><?=$rs->mem_full_care_type?></td>
                                </tr>
                                <!-- 2019.02.18 Full Care비용 추가 -->
                                <tr style="display:none">
                                    <th style="vertical-align: middle !important;">Full Care 비용</th>
                                    <td><?=number_format($rs->mad_price_full_care)?>원</td>
                                </tr>
                                 <tr style="display:none">
                                    <th style="vertical-align: middle !important;">링크 버튼 이름</th>
                                    <td><?=$rs->mem_linkbtn_name?></td>
                                </tr>
                                <tr>
                                    <th>2차 발신</th>
                                    <td style="line-height:2.3;"><?=$this->funn->get_2send_kind($rs->mem_2nd_send)?></span></td>
                                </tr>
                                <tr>
                                    <th>충전방식</th>
                                    <td style="line-height:2.3;"><? if($rs->mem_pay_type == 'A') { echo "후불"; } else if($rs->mem_pay_type == 'T') {echo "정액제( ".$rs->mem_fixed_from_date." ~ ".$rs->mem_fixed_to_date." )"; } else { echo "선불"; } ?></span></td>
                                </tr>
                                <!-- 2019.10.09 현재 영업 담당자, 플러스 친구수(카카오 기준) -->
                                <tr>
                                    <th style="vertical-align: middle !important;">결재방법</th>
                                    <td><?=($rs->mem_payment_vbank == "Y")?"가상계좌(이니시스)":"계좌이체(레드뱅킹)"?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">계약형태</th>
                                    <td><?=($rs->mem_contract_type=="P")?"프리미엄":"스탠다드"?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">월사용료</th>
                                    <td><?=number_format($rs->mad_price_full_care) ?>원</td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">스마트전단 주문하기</th>
                                    <td><?=($rs->mem_stmall_yn=="Y")?"사용":"사용안함"?></td>
                                </tr>
								<? if($rs->mem_stmall_yn=="YY"){ ?>
                                <tr>
                                    <th style="vertical-align: middle !important;">주문 알림 수신 전화번호</th>
                                    <td>
										<?
											$mem_stmall_alim_phn = $rs->mem_stmall_alim_phn; //주문 알림 수신 번호
											$orstr = explode(',', $mem_stmall_alim_phn);
											for($i=0; $i<count($orstr); $i++){
												$stmall_alim_phn = $orstr[$i];
												$stmall_alim_phn = $this->funn->format_phone($stmall_alim_phn, "-");
												if($i > 0){
													echo "<span style='margin-left:10px;'></span>";
												}
												echo $stmall_alim_phn;
											}
										?>
									</td>
                                </tr>
								<? } ?>
								<? if($this->member->item('mem_level')>=100){ ?>
                                <tr>
                                    <th style="vertical-align: middle !important;"><span class="fc_blue">세금계산서 발행일</span></th>
                                    <td><?=$rs->mem_tex_io_date?>일</td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;"><span class="fc_blue">알림톡 발송시간 해제</span></th>
                                    <td><?=($rs->mem_talk_time_clear_yn=="Y")?"설정함":"설정안함"?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;"><span class="fc_blue">문자 발송시간 해제</span></th>
                                    <td><?=($rs->mem_sms_time_clear_yn=="Y")?"설정함":"설정안함"?></td>
                                </tr>
								<? } ?>
                                <tr>
                                    <th style="vertical-align: middle !important;">지니 연동 ID</th>
                                    <td><?=$rs->mem_userid?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">지니 연동 Key</th>
                                    <td><?=$rs->mem_site_key?></td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">포스연동</th>
                                    <td><?=($rs->mem_pos_yn=="Y")?"사용":"사용안함"?></td>
                                </tr>
                                <? if($this->member->item('mem_level')>=100){
                                //ib플래그 + 그룹추가
                                $group1 = "마트친구";
                                $group2 = "dhn공용";
                                if(config_item('ib_flag')=="Y"){
                                    $group1 = "지니";
                                    $group2 = "하늘";
                                }
                                ?>
								<!-- 영업지점추가 -->
                                <tr>
            						<th style="vertical-align: middle !important;">계약 영업 지점</th>
            						<td><?=$branch?>
            						</td>
            					</tr>
            					<tr>
									<th style="vertical-align: middle !important;">계약 영업 담당자</th>
									<td><?=$salesperson?>
									</td>
								</tr>
                                <tr>
    								<th style="vertical-align: middle !important;">관리 지점</th>
    								<td><?=$branch_for_manage?>
    								</td>
    							</tr>
    							<tr>
									<th style="vertical-align: middle !important;">관리 담당자</th>
									<td><?=$salesperson_for_manage?>
									</td>
								</tr>
                            <?if ($this->member->item('mem_level') >= 150){?>
                                <tr>
									<th style="vertical-align: middle !important;">인센티브</th>
									<td><?=$rs->mad_incentive?>
									</td>
								</tr>
                            <?}?>
                                <tr>
    									<th style="vertical-align: middle !important;">발신UI</th>
    									<td><?=($rs->mem_send_smart_yn=="Y")? "스마트전단" : "스마트홈" ?></td>
    							</tr>
                                <? //ib플래그 + 그룹추가 ?>
                                <tr>
                                    <th style="vertical-align: middle !important;">템플릿 그룹</th>
                                    <td>
                                    <?=!empty($member_temp->mtg_useyn1) ? "  [그룹]".$group1 : "" ?>
                                    <?=!empty($member_temp->mtg_useyn2) ? "  [그룹]".$group2 : "" ?>
                                    </td>
                                </tr>
								<? } else { ?>
								<tr>
                                    <th style="vertical-align: middle !important;">영업 담당자</th>
                                    <td>
                                    <?=$rs->mem_sales_name ?>
                                    </td>
                                </tr>
								<? } ?>
                                <tr>
                                    <th style="vertical-align: middle !important;">RCS발송 계약여부</th>
                                    <td>
                                    <?=($rs->mem_rcs_yn=="Y") ? "RCS 발송함" : "RCS 발송안함" ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle !important;">휴면상태</th>
                                    <td>
                                    <?=!empty($dormancy->mem_id) ? $dormancy->mdd_dormant_flag == 1 ? "휴면" : "정상" : "정상" ?>
                                    </td>
                                </tr>
                                <tr style="display:none">
                                    <th style="vertical-align: middle !important;">플러스 친구수(카카오 기준)</th>
                                    <td><?=number_format($rs->mem_pf_cnt)?>명</td>
                                </tr>
                                <tr class="mobile_none">
												<th style="vertical-align: middle !important;">발신 단가<p color="blue">※ 사용안함 : 0원 입력</p></th>
                                    <td>

													 <!--table class="tpl_ver_form" width="100%">
														  <colgroup>
                                                                <col width="8%">
                                                                <col width="8%">
                                                                <col width="8%">
                                                                <col width="8%">
                                                     	<? if($this->member->item('mem_level')>=100) { ?>
														<? if($rs->mem_level >= 50) {  ?>
                                                                <col width="8%">
                                                                <col width="8%">
                                                        <? } else { ?>
                                                                <col width="8%">
                                                        <? } } else {?>
                                                        		<col width="8%">
                                                        <? } ?>
                                                        		<col width="8%">
                                                                <col width="8%">
                                                                <col width="8%">
                                                                <col width="8%">
                                                                <col width="8%">
                                                                <col width="8%">
                                                                <col width="8%">
                                                                <col width="*">
														  </colgroup>
														  <thead>
														  <tr>
																<th>알림톡</th>
																<th>친구톡<BR>(텍스트)</th>
																<th>친구톡<BR>(이미지)</th>
																<th>친구톡<BR>(와이드이미지)</th>
														<? if($this->member->item('mem_level')>=100) { ?>
														<? if($rs->mem_level >= 50) {  ?>
																<th>WEB(A)<BR>KT, LGT</th>
																<th>WEB(A)<BR>SKT</th>
														<? } else { ?>
																<th>WEB(A)</th>
														<? } } else {?>
																<th>WEB(A)</th>
														<? } ?>
																<th>WEB(A)<BR>SMS</th>
																<th>WEB(A)<BR>MMS</th>
																<th>WEB(B)</th>
																<th>WEB(B)<BR>SMS</th>
																<th>WEB(B)<BR>MMS</th>
																<th>WEB(C)</th>
																<th>WEB(C)<BR>SMS</th>
																<th>WEB(C)<BR>MMS</th>
															</tr>
														  </thead>
														  <tbody>
														  <tr>
																<th style="background: white"><?=number_format($rs->mad_price_at, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_ft, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_ft_img, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_ft_w_img, 2)?></th>
																<? if($this->member->item('mem_level')>=100) { ?>
																<? if($rs->mem_level >= 50) { ?>
																<th style="background: white"><?=number_format($rs->mad_price_grs, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_grs_biz, 2)?></th>
																<? } else { ?>
																<th style="background: white"><?=number_format($rs->mad_price_grs, 2)?></th>
																<? } } else {?>
																<th style="background: white"><?=number_format($rs->mad_price_grs, 2)?></th>
																<? } ?>
																<th style="background: white"><?=number_format($rs->mad_price_grs_sms, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_grs_mms, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_nas, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_nas_sms, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_nas_mms, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_smt, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_smt_sms, 2)?></th>
																<th style="background: white"><?=number_format($rs->mad_price_smt_mms, 2)?></th>
														  </tr>
														  </tbody>
													 </table-->

													 <!--div class="price_table">
														 <table>
															 <colgroup>
															 	<col width="40%">
															 	<col width="30%">
															 	<col width="30%">
															 </colgroup>
															 <thead>
															 </thead>
															 	<tr>
																 	<th rowspan="4">카카오톡</th>
																 	<td>알림톡</td>
																 	<td><?=number_format($rs->mad_price_at, 2)?></td>
															 	</tr>
															 	<tr>
																 	<td>친구톡(텍스트)</td>
																 	<td><?=number_format($rs->mad_price_ft, 2)?></td>
															 	</tr>
															 	<tr>
																 	<td>친구톡(이미지)</td>
																 	<td><?=number_format($rs->mad_price_ft_img, 2)?></td>
															 	</tr>
															 	<tr>
																 	<td>친구톡 와이드</td>
																 	<td><?=number_format($rs->mad_price_ft_w_img, 2)?></td>
															 	</tr>
															 	<? if($this->member->item('mem_level')>=100) { ?>
															 	<? if($rs->mem_level >= 50) { ?>
															 	<tr>
																 	<th rowspan="4">WEB <strong>A</strong></th>
																 	<td>LMS(KT/LGU+)</td>
																 	<td><?=number_format($rs->mad_price_grs, 2)?></td>
															 	</tr>
															 	<tr>
																 	<td>LMS(SKT)</td>
																 	<td><?=number_format($rs->mad_price_grs_biz, 2)?></td>
															 	</tr>
															 	<? } else { ?>
															 	<tr>
																 	<th rowspan="3">WEB <strong>A</strong></th>
																 	<td>LMS</td>
																 	<td><?=number_format($rs->mad_price_grs, 2)?></td>
															 	</tr>
															 	<? } } else {?>
															 	<tr>
																 	<th rowspan="3">WEB <strong>A</strong></th>
																 	<td>LMS</td>
																 	<td><?=number_format($rs->mad_price_grs, 2)?></td>
															 	</tr>
															 	<? } ?>
															 	<tr>
																 	<td>SMS</td>
																 	<td><?=number_format($rs->mad_price_grs_sms, 2)?></td>
															 	</tr>
															 	<tr>
																 	<td>MMS</td>
																 	<td><?=number_format($rs->mad_price_grs_mms, 2)?></td>
															 	</tr>

															 	<tr>
																 	<th rowspan="3">WEB <strong>B</strong></th>
																 	<td>LMS</td>
																 	<td><?=number_format($rs->mad_price_nas, 2)?></td>
															 	</tr>
															 	<tr>
																 	<td>SMS</td>
																 	<td><?=number_format($rs->mad_price_nas_sms, 2)?></td>
															 	</tr>
															 	<tr>
																 	<td>MMS</td>
																 	<td><?=number_format($rs->mad_price_nas_mms, 2)?></td>
															 	</tr>
															 	<tr>
																 	<th rowspan="3">WEB <strong>C</strong></th>
																 	<td>LMS</td>
																 	<td><?=number_format($rs->mad_price_smt, 2)?></td>
															 	</tr>
															 	<tr>
																 	<td>SMS</td>
																 	<td><?=number_format($rs->mad_price_smt_sms, 2)?></td>
															 	</tr>
															 	<tr>
																 	<td>MMS</td>
																 	<td><?=number_format($rs->mad_price_smt_mms, 2)?></td>
															 	</tr>
															 <tbody>
															 </tbody>
														 </table>
													</div-->
													<div class="price_list">
														<div class="table_top">※ 사용안함 : 0원 입력<span class="notice fr">※ 발신단가는 부가세 포함 가격입니다!</span></div>

														<table>
															<colgroup>
																<col width="33%">
																<col width="33%">
																<col width="*">
																<!-- <col width="25%"> -->
															</colgroup>
															 <thead>
																 <tr class="kakao">
																	 <th>알림톡</th>
																	 <th>친구톡(텍스트)</th>
																	 <th>친구톡(이미지)</th>
																	 <!-- <th>친구톡(와이드)</th> -->
																 </tr>
															 </thead>
															 <tbody>
																 <tr>
																	 <td>
                                                                         <?=number_format($rs->mad_price_at, 2)?>
                                                                 <?
                                                                     if($this->member->item('mem_level') >= 150){
                                                                         if (in_array($rs->mem_sales_mng_id, config_item('salesman'))){
                                                                 ?>
                                                                             (<span style='color:red;'><?=number_format($rs->mad_price_at - config_item('send_amount')[0], 2)?></span>)
                                                                 <?
                                                                         }
                                                                     }
                                                                 ?>
                                                                     </td>
																	 <td><?=number_format($rs->mad_price_ft, 2)?></td>
																	 <td><?=number_format($rs->mad_price_ft_img, 2)?></td>
																	 <!-- <td><?=number_format($rs->mad_price_ft_w_img, 2)?></td> -->
																 </tr>
															 </tbody>
														 </table>
                                                         <table>
 														<colgroup>
 															<col width="33%">
                                                            <? if($rs->mem_rcs_yn=="Y"){ ?>
 															<col width="33%">
                                                            <? } ?>
 															<!-- <col width="25%" style="display:none"> -->
 															<col width="*">
 														</colgroup>
 														 <thead>
 															 <tr>
 																 <th>문자</th>
 																 <th>Web문자 <strong></strong></th>
                                                                 <? if($rs->mem_rcs_yn=="Y"){ ?>
                                                                     <th>RCS 문자 <strong></strong></th>
                                                                 <? } ?>
 																 <!-- <th style="display:none">WEB <strong>B</strong></th> -->
 																 <!-- <th>폰문자 <strong></strong></th> -->
 															 </tr>
 														 </thead>
 														 <tbody>
 															 <tr>
                                                                  <th>SMS</th>
                                                                  <td><?=number_format($rs->mad_price_smt_sms, 2)?></td>
                                                                  <? if($rs->mem_rcs_yn=="Y"){ ?>
                                                                      <td><?=number_format($rs->mad_price_rcs_sms, 2)?></td>
                                                                  <? } ?>
 																<!-- <td rowspan="3"><?=number_format($rs->mad_price_imc, 2)?></td> -->
 															 </tr>
 															 <tr>
                                                                  <th>LMS</th>
  																<td><?=number_format($rs->mad_price_smt, 2)?></td>
                                                                <? if($rs->mem_rcs_yn=="Y"){ ?>
                                                                    <td><?=number_format($rs->mad_price_rcs, 2)?></td>
                                                                <? } ?>
 															 </tr>
 															 <tr>
 																 <th>MMS</th>
 																 <td><?=number_format($rs->mad_price_smt_mms, 2)?></td>
                                                                 <? if($rs->mem_rcs_yn=="Y"){ ?>
                                                                     <td><?=number_format($rs->mad_price_rcs_mms, 2)?></td>
                                                                 <? } ?>
 															 </tr>
 														 </tbody>
 													 </table>
													 </div>
												</td>
										  </tr>
                                          <tr class="mobile_none" <?=($rs->mem_voucher_yn=="N")? "style='display:none;'" : ""?>>
                                            <th style="vertical-align: middle !important;">바우처 발신 단가<p color="blue">※ 사용안함 : 0원 입력</p></th>
                                            <td>
                                                <div class="price_list">
                                                    <div class="table_top">※ 사용안함 : 0원 입력<span class="notice fr">※ 발신단가는 부가세 포함 가격입니다!</span></div>

                                                    <table>
                                                        <colgroup>
                                                            <col width="33%">
                                                            <col width="33%">
                                                            <col width="*">
                                                        </colgroup>
                                                         <thead>
                                                             <tr class="kakao">
                                                                 <th>알림톡</th>
                                                                 <th>친구톡(텍스트)</th>
                                                                 <th>친구톡(이미지)</th>
                                                             </tr>
                                                         </thead>
                                                         <tbody>
                                                             <tr>
                                                                 <td><?=number_format($rs->vad_price_at, 2)?></td>
                                                                 <td><?=number_format($rs->vad_price_ft, 2)?></td>
                                                                 <td><?=number_format($rs->vad_price_ft_img, 2)?></td>
                                                             </tr>
                                                         </tbody>
                                                     </table>
                                                    <table>
                                                        <colgroup>
                                                            <col width="33%">
                                                            <? if($rs->mem_rcs_yn=="Y"){ ?>
                                                                <col width="33%">
                                                            <? } ?>
                                                            <col width="*">
                                                        </colgroup>
                                                         <thead>
                                                             <tr>
                                                                 <th>문자</th>
                                                                 <th>Web문자 <strong></strong></th>
                                                                 <? if($rs->mem_rcs_yn=="Y"){ ?>
                                                                      <th>RCS문자 <strong></strong></th>
                                                                 <? } ?>
                                                             </tr>
                                                         </thead>
                                                         <tbody>
                                                             <tr>
                                                                 <th>SMS</th>
                                                                 <td><?=number_format($rs->vad_price_smt_sms, 2)?></td>
                                                                 <? if($rs->mem_rcs_yn=="Y"){ ?>
                                                                      <td><?=number_format($rs->vad_price_rcs_sms, 2)?></td>
                                                                 <? } ?>
                                                             </tr>
                                                             <tr>
                                                                 <th>LMS</th>
                                                                <td><?=number_format($rs->vad_price_smt, 2)?></td>
                                                                <? if($rs->mem_rcs_yn=="Y"){ ?>
                                                                     <td><?=number_format($rs->vad_price_rcs, 2)?></td>
                                                                <? } ?>
                                                             </tr>
                                                             <tr>
                                                                 <th>MMS</th>
                                                                 <td><?=number_format($rs->vad_price_smt_mms, 2)?></td>
                                                                 <? if($rs->mem_rcs_yn=="Y"){ ?>
                                                                      <td><?=number_format($rs->vad_price_rcs_mms, 2)?></td>
                                                                 <? } ?>
                                                             </tr>
                                                         </tbody>
                                                     </table>
                                                 </div>
                                            </td>
                                        </tr>
                                <tr style="display:none">
                                    <th style="vertical-align: middle !important;" >충전가중치</th>
                                    <td>
                                            <table class="tpl_hor_form" width="100%" style="display:none">
                                                <thead>
                                                <tr>
                                                    <th>금액</th>
                                                    <th>가중치(%)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[0])?></th>
                                                    <td><?=number_format($rs->mad_weight1, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[1])?></th>
                                                    <td><?=number_format($rs->mad_weight2, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[2])?></th>
                                                    <td><?=number_format($rs->mad_weight3, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[3])?></th>
                                                    <td><?=number_format($rs->mad_weight4, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[4])?></th>
                                                    <td><?=number_format($rs->mad_weight5, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[5])?></th>
                                                    <td><?=number_format($rs->mad_weight6, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[6])?></th>
                                                    <td><?=number_format($rs->mad_weight7, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[7])?></th>
                                                    <td><?=number_format($rs->mad_weight8, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[8])?></th>
                                                    <td><?=number_format($rs->mad_weight9, 2)?>%</td>
                                                </tr>
                                                <tr>
                                                    <th style="word-break:break-all;">₩<?=number_format($this->Biz_model->charge[9])?></th>
                                                    <td><?=number_format($rs->mad_weight10, 2)?>%</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
													</div>
                </form>
								<div class="btn_al_cen mg_b20">
									<a type="button" href="/biz/partner/edit?<?=$param['key']?>" class="btn_st3" <?=($this->member->item('mem_level')==17)? "style='display:none;'" : "" ?>>수정하기</a>
                                    <a type="button" href="/biz/partner/lists" class="btn_st3">목록으로</a>
								</div>
				    </div>
				</div>
      </div>
    </div>
    <style>
        .select2-no-results {
            display: none !important;
        }

        textarea {
            resize: none;
        }
    </style>
    <script type="text/javascript">
        $("#nav li.nav60").addClass("current open");

        $("#wrap").css('position', 'absolute');
        $("#myModal.modal-content").css('width', '320px');
        $("#myModal.modal-body").css('height', '120px');
        $("#myModal").css('margin-left', '40%');
        $("#myModal").css('margin-top', '15%');
        $("#myModal").css('overflow-x', 'hidden');
        $("#myModal").css('overflow-y', 'hidden');

        //CSRF token획득
        function getCookie(name) {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }

        //확인창 확인버튼
        function click_btn_custom() {
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".btn-custom").click();
                }
            });
        }

        //예-아니오에서의 확인버튼
        function click_btn_primary() {
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".btn-primary").click();
                }
            });
        }

		function show_sheet(img) {
			$("#myModalImg").find(".content").html("<img src='"+img+"' width='570' border='0' />");
			$('#myModalImg').modal({backdrop: 'static'});
		}
    </script>
