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
						<button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">확인
						</button>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 타이틀 영역 -->
<div class="tit_wrap">
	발송내역
</div>
<!-- 타이틀 영역 END -->
<!-- 3차 메뉴 -->
<?php
//include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu1.php');
?>
<!-- 컨텐츠 전체 영역 -->
<?
    $failSendFlag = false;
	$sms_sender_num = $rs->mst_sms_callback;
?>
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3><? if($rs->spf_company) { echo $rs->spf_company."(".$rs->spf_friend.")"; } else { echo $rs->mem_username; } ?></B><input type="hidden" id="pf_key" name="pf_key" value="<?=$rs->spf_key?>"><input type="hidden" id="sms_sender" name="sms_sender" value="<?=$rs->spf_sms_callback?>"></h3>
			<button class="btn_tr" onclick="location.href='/dhnbiz/sender/history'">목록으로</button>
		</div>
		<div class="inner_content preview_info clearfix">
			<div class="info_detail fl">
				<div class="input_content_wrap">
					<label class="input_tit">일련번호</label>
					<div class="input_content">
						<?=$rs->mst_id?>
					</div>
				</div>
				<div class="input_content_wrap">
					<label class="input_tit">발신 전화번호</label>
					<div class="input_content" id="sms_sender_num">
					</div>
				</div>
				<div class="input_content_wrap">
					<label class="input_tit">발신시간</label>
					<div class="input_content">
						<?=$rs->mst_datetime?>
						<?
						   if ($rs->mst_reserved_dt!="00000000000000") {
							   if ($rs->mst_reserved_dt > cdate('YmdHis',strtotime('+0 minutes'))) {
								   echo "<br/><font color='blue'>".$this->funn->format_date($rs->mst_reserved_dt, '-', 14)."</font><br/><br/>";
							   } else {
								   echo "<br/>".$this->funn->format_date($rs->mst_reserved_dt, '-', 14)."<br/><br/>";
							   }
							   if($rs->mst_reserved_dt > cdate('YmdHis',strtotime('+10 minutes')))
							   echo "<input type='button' class='btn btn-default' id='delete' value='삭제' onclick='reserve_cancel(".$rs->mst_id.")' style='cursor:pointer;'/>";
						  }
						  ?>
					</div>
				</div>
				<div class="input_content_wrap">
					<label class="input_tit">발신타입</label>
					<div class="input_content">
						<?
						$sendedTypeText1 = '-';
						$sendedTypeText2 = '-';
                        $sendedTypeText3 = '-';
						switch($rs->mst_type1) {
							case 'ft' :
								$sendedTypeText1 = "친구톡";
								break;
							case 'at' :
								$sendedTypeText1 = "알림톡";
								break;
							case 'ai' :
							    $sendedTypeText1 = "알림톡(이미지)";
							    break;
							case 'fti' :
								$sendedTypeText1 = "친구톡(이미지)";
								break;
							case 'ftw' :
								$sendedTypeText1 = "친구톡(와이드)";
								break;
						}
						if ($this->member->item("mem_level") >= 100) {
							switch($rs->mst_type2) {
								case 'wa' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(A) LMS' : $sendedTypeText2 = '웹(A) LMS';
									break;
								case 'was' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(A) SMS' : $sendedTypeText2 = '웹(A) SMS';
									break;
								case 'wam' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(A) MMS' : $sendedTypeText2 = '웹(A) MMS';
									break;
								case 'wb' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(B) LMS' : $sendedTypeText2 = '웹(B) LMS';
									break;
								case 'wbs' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(B) SMS' : $sendedTypeText2 = '웹(B) SMS';
									break;
								case 'wbm' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(B) MMS' : $sendedTypeText2 = '웹(B) MMS';
									break;
								case 'wc' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(C) LMS' : $sendedTypeText2 = '웹(C) LMS';
									break;
								case 'wcs' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(C) SMS' : $sendedTypeText2 = '웹(C) SMS';
									break;
								case 'wcm' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(C) MMS' : $sendedTypeText2 = '웹(C) MMS';
									break;
                                case 'rc' :
								    ($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS LMS' : $sendedTypeText2 = 'RCS LMS';
                                    ($rs->mst_type3!="")? (($rs->mst_type3=="wc")? $sendedTypeText2 = "웹(C) LMS" : $sendedTypeText2 = "웹(C) SMS") : "";
								    break;
								case 'rcs' :
								    ($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS SMS' : $sendedTypeText2 = 'RCS SMS';
                                    ($rs->mst_type3!="")? (($rs->mst_type3=="wc")? $sendedTypeText2 = "웹(C) LMS" : $sendedTypeText2 = "웹(C) SMS") : "";
								    break;
                                case 'rct' :
								    ($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS 템플릿' : $sendedTypeText2 = 'RCS 템플릿';
                                    ($rs->mst_type3!="")? (($rs->mst_type3=="wc")? $sendedTypeText2 = "웹(C) LMS" : $sendedTypeText2 = "웹(C) SMS") : "";
								    break;
								case 'phn' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '폰문자 LMS' : $sendedTypeText2 = '폰문자 LMS';
									break;
								case 'phns' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '폰문자 SMS' : $sendedTypeText2 = '폰문자 SMS';
									break;
								case 'phnm' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '폰문자 MMS' : $sendedTypeText2 = '폰문자 MMS';
									break;
								case '015' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '015문자 LMS' : $sendedTypeText2 = '015문자 LMS';
									break;
								case '015s' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '015문자 SMS' : $sendedTypeText2 = '015문자 SMS';
									break;
								case '015m' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '015문자 MMS' : $sendedTypeText2 = '015문자 MMS';
									break;
								case 'at' || 'AL' : //2021-01-05 2차 알림톡 추가
								    ($sendedTypeText1 === '-') ? $sendedTypeText1 = '알림톡' : $sendedTypeText2 = '알림톡';
								    break;
								case 'ai' : //2021-01-05 2차 알림톡 추가
								    ($sendedTypeText1 === '-') ? $sendedTypeText1 = '알림톡(이미지)' : $sendedTypeText2 = '알림톡(이미지)';
								    break;
							}
                            switch($rs->mst_type3&&$rs->mst_kind!="rc") {

						        case 'wc' :
						            $sendedTypeText3 = '웹(C) LMS';
						            break;
						        case 'wcs' :
						            $sendedTypeText3 = '웹(C) SMS';
						            break;
						        case 'wcm' :
						            $sendedTypeText3 = '웹(C) MMS';
						            break;

						    }
						} else {
							switch($rs->mst_type2) {
								case 'wa' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(LMS)' : $sendedTypeText2 = '문자(LMS)';
									break;
								case 'was' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(SMS)' : $sendedTypeText2 = '문자(SMS)';
									break;
								case 'wam' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(MMS)' : $sendedTypeText2 = '문자(MMS)';
									break;
								case 'wb' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(LMS)' : $sendedTypeText2 = '문자(LMS)';
									break;
								case 'wbs' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(SMS)' : $sendedTypeText2 = '문자(SMS)';
									break;
								case 'wbm' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(MMS)' : $sendedTypeText2 = '문자(MMS)';
									break;
								case 'wc' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(LMS)' : $sendedTypeText2 = '문자(LMS)';
									break;
								case 'wcs' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(SMS)' : $sendedTypeText2 = '문자(SMS)';
									break;
								case 'wcm' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(MMS)' : $sendedTypeText2 = '문자(MMS)';
									break;
                                case 'rc' :
								    ($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS LMS' : $sendedTypeText2 = 'RCS LMS';
                                    ($rs->mst_type3!="")? (($rs->mst_type3=="wc")? $sendedTypeText2 = "문자(LMS)" : $sendedTypeText2 = "문자(SMS)") : "";
								    break;
								case 'rcs' :
								    ($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS SMS' : $sendedTypeText2 = 'RCS SMS';
                                    ($rs->mst_type3!="")? (($rs->mst_type3=="wc")? $sendedTypeText2 = "문자(LMS)" : $sendedTypeText2 = "문자(SMS)") : "";

								    break;
                                case 'rct' :
								    ($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS 템플릿' : $sendedTypeText2 = 'RCS 템플릿';
                                    ($rs->mst_type3!="")? (($rs->mst_type3=="wc")? $sendedTypeText2 = "문자(LMS)" : $sendedTypeText2 = "문자(SMS)") : "";
								    break;
								case 'phn' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '폰문자 LMS' : $sendedTypeText2 = '폰문자 LMS';
									break;
								case 'phns' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '폰문자 SMS' : $sendedTypeText2 = '폰문자 SMS';
									break;
								case 'phnm' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '폰문자 MMS' : $sendedTypeText2 = '폰문자 MMS';
									break;
								case '015' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '015문자 LMS' : $sendedTypeText2 = '015문자 LMS';
									break;
								case '015s' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '015문자 SMS' : $sendedTypeText2 = '015문자 SMS';
									break;
								case '015m' :
									($sendedTypeText1 === '-') ? $sendedTypeText1 = '015문자 MMS' : $sendedTypeText2 = '015문자 MMS';
									break;
								case 'at' || 'AL' : //2021-01-05 2차 알림톡 추가
								    ($sendedTypeText1 === '-') ? $sendedTypeText1 = '알림톡' : $sendedTypeText2 = '알림톡';
								    break;
								case 'ai' : //2021-01-05 2차 알림톡 추가
								    ($sendedTypeText1 === '-') ? $sendedTypeText1 = '알림톡(이미지)' : $sendedTypeText2 = '알림톡(이미지)';
								    break;
							}
						}
                        // log_message("ERROR", "sendedTypeText2 : ".$sendedTypeText2." / rs->mst_type3 : ".$rs->mst_type3);
						echo "<div class=\"send_type fir\">".$sendedTypeText1."</div>";
						echo "<div class=\"send_type sec\">".$sendedTypeText2."</div>";
                        if($rs->mst_type3&&$rc->mst_kind!="rc") {
						    echo "<div class=\"send_type thi\">".$sendedTypeText3."</div>";
						}
						?>
					</div>
				</div>
				<div class="input_content_wrap">
					<label class="input_tit">총 발신건수</label>
					<div class="input_content">
						<span class="num_total"><?=$rs->mst_qty?></span>
					</div>
				</div>
				<div class="input_content_wrap">
					<label class="input_tit">상태</label>
					<div class="input_content">
					<?
						$reservedStatus = ''; // 예약관련 정보 html class명이나 비교에 사용. 예약건 : reserve, 아니면 ''
						$sendedDate = '';   // 발신 시간; 예약이 있을시에는 예약시간.
						$companyName = '';  // 회사명 또는 사용자 명
						$friendName = '';   // 플러스 친구 명 앞두에 ()포함.
						$summaryContent = '';   // 문자 내용 summary

						//$summaryContent = preg_replace('\n', '</p><p style="display: block">', $summaryContent);

						$sucessCount1 = '-';  // 1차 발송 성공 수량
						$resultDelayCount1 = '';  //1차 발송 결과 대기 수량(문자에 한함)
						$failCount1 = '-';    // 1차 발송 실패 수량
						$sucessCount2 = '-';  // 2차 발송 성공 수량
						$resultDelayCount2 = '';  //2차 발송 결과 대기 수량
						$failCount2 = '-';    // 2차 발송 실패 수량
                        $sucessCount3 = '-';  // 3차 발송 성공 수량
				        $resultDelayCount3 = '';  // 3차 발송 결과 대기 수량
				        $failCount3 = '-';    // 3차 발송 실패 수량

						$statusClass1 = '';  // 상태 span class 1차
						$statusText1 = '';   // 상태 텍스트 1차
						$statusClass2 = '';  // 상태 span class 2차
						$statusText2 = '';   // 상태 텍스트 2차
                        $statusClass3 = '';  // 상태 span class 3차
						$statusText3 = '';   // 상태 텍스트 3차

						$kakaoTalkCount = 0;  // 알림톡(성공/실패), 친구톡(성공/실패), 친구톡이미지(성공/실패) 합계
						$messageCount = 0;    // 웹(A)LMS, 웹(A)SMS, 웹(A)MMS, 웹(B)LMS, 웹(B)SMS, 웹(B)MMS 각각 성공/실패 합계
						$reservedFlag = false;
						if ($rs->mst_reserved_dt!="00000000000000") {

							$senderDate = $this->funn->format_date($rs->mst_reserved_dt, '-', 14);

							if (strtotime($senderDate) > strtotime(date("Y-m-d H:i:s"))) {
								$reservedStatus = 'reserve';
								$statusClass1 = 'sending_stat reserve';
								$statusText1 = '발송예약';
								$statusClass2 = '';
								$statusText2 = '';
                                $statusClass3 = '';
							    $statusText3 = '';
								$reservedFlag = true;
							}
						} else {
							$senderDate = $rs->mst_datetime;
						}

						if ($rs->spf_company) {
							$companyName = $rs->spf_company;
							$friendName = '('.$rs->spf_friend.')';
						} else {
							$companyName = $rs->mem_username;
						}

						if ($rs->mst_ft > 0 || $rs->mst_err_ft > 0) {
							$sucessCount1 = number_format($rs->mst_ft);
							$failCount1 = number_format($rs->mst_err_ft);
						}
						if ($rs->mst_at > 0 || $rs->mst_err_at > 0) {
							$sucessCount1 = number_format($rs->mst_at);
							$failCount1 = number_format($rs->mst_err_at);
						}
						if ($rs->mst_ft_img > 0 || $rs->mst_err_ft_img > 0) {
							$sucessCount1 = number_format($rs->mst_ft_img);
							$failCount1 = number_format($rs->mst_err_ft_img);
						}

						switch($rs->mst_type2) {
							case 'wa' :
								if ($rs->mst_grs > 0 || $rs->mst_err_grs > 0) {
									if ($rs->mst_type1 != "") {
										$sucessCount2 = number_format($rs->mst_wait + $rs->mst_grs);
										$failCount2 = number_format($rs->mst_err_grs);
									} else {
										($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_wait + $rs->mst_grs) : $sucessCount2 = number_format($rs->mst_wait + $rs->mst_grs);
										($failCount1 === '-') ? $failCount1 = number_format($rs->mst_err_grs) : $failCount2 = number_format($rs->mst_err_grs);
									}
								}
								break;
							case 'was' :
								if ($rs->mst_grs_sms > 0 || $rs->mst_err_grs_sms > 0) {
									if ($rs->mst_type1 != "") {
										$sucessCount2 = number_format($rs->mst_wait + $rs->mst_grs_sms);
										$failCount2 = number_format($rs->mst_err_grs_sms);
									} else {
										($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_wait + $rs->mst_grs_sms) : $sucessCount2 = number_format($rs->mst_wait + $rs->mst_grs_sms);
										($failCount1 === '-') ? $failCount1 = number_format($rs->mst_err_grs_sms) : $failCount2 = number_format($rs->mst_err_grs_sms);
									}
								}
								break;
							case 'wam' :
								if ($rs->mst_grs_mms > 0 || $rs->mst_err_grs_mms > 0) {
									if ($rs->mst_type1 != "") {
										$sucessCount2 = number_format($rs->mst_wait + $rs->mst_grs_mmsmst_grs_mms);
										$failCount2 = number_format($rs->mst_err_grs_mms);
									} else {
									($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_wait + $rs->mst_grs_mms) : $sucessCount2 = number_format($rs->mst_wait + $rs->mst_grs_mmsmst_grs_mms);
									($failCount1 === '-') ? $failCount1 = number_format($rs->mst_err_grs_mms) : $failCount2 = number_format($rs->mst_err_grs_mms);
									}
								}
								break;
							case 'wb' :
								if ($rs->mst_nas > 0 || $rs->mst_err_nas > 0) {
									if ($rs->mst_type1 != "") {
										$sucessCount2 = number_format($rs->mst_wait + $rs->mst_nas);
										$failCount2 = number_format($rs->mst_err_nas);
									} else {
										($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_wait + $rs->mst_nas) : $sucessCount2 = number_format($rs->mst_wait + $rs->mst_nas);
										($failCount1 === '-') ? $failCount1 = number_format($rs->mst_err_nas) : $failCount2 = number_format($rs->mst_err_nas);
									}
								}
								break;
							case 'wbs' :
								if ($rs->mst_nas_sms > 0 || $rs->mst_err_nas_sms > 0) {
									if ($rs->mst_type1 != "") {
										$sucessCount2 = number_format($rs->mst_wait + $rs->mst_nas_sms);
										$failCount2 = number_format($rs->mst_err_nas_sms);
									} else {
										($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_wait + $rs->mst_nas_sms) : $sucessCount2 = number_format($rs->mst_wait + $rs->mst_nas_sms);
										($failCount1 === '-') ? $failCount1 = number_format($rs->mst_err_nas_sms) : $failCount2 = number_format($rs->mst_err_nas_sms);
									}
								}
								break;
							case 'wbm' :
								if ($rs->mst_nas_mms > 0 || $rs->mst_err_nas_mms > 0) {
									if ($rs->mst_type1 != "") {
										$sucessCount2 = number_format($rs->mst_wait + $rs->mst_nas_mms);
										$failCount2 = number_format($rs->mst_err_nas_mms);
									} else {
										($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_wait + $rs->mst_nas_mms) : $sucessCount2 = number_format($rs->mst_wait + $rs->mst_nas_mms);
										($failCount1 === '-') ? $failCount1 = number_format($rs->mst_err_nas_mms) : $failCount2 = number_format($rs->mst_err_nas_mms);
									}
								}
								break;
							case 'wc' :
								if ($rs->mst_smt > 0 || $rs->mst_err_smt > 0) {
									if ($rs->mst_type1 != "") {
										$sucessCount2 = number_format($rs->mst_wait + $rs->mst_smt);
										$failCount2 = number_format($rs->mst_err_smt);
									} else {
										($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_wait + $rs->mst_smt) : $sucessCount2 = number_format($rs->mst_wait + $rs->mst_smt);
										($failCount1 === '-') ? $failCount1 = number_format($rs->mst_err_smt) : $failCount2 = number_format($rs->mst_err_smt);
									}
								}
								break;
							case 'wcs' :
								if ($rs->mst_smt > 0 || $rs->mst_err_smt > 0) {
									if ($rs->mst_type1 != "") {
										$sucessCount2 = number_format($rs->mst_wait + $rs->mst_smt);
										$failCount2 = number_format($rs->mst_err_smt);
									} else {
										($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_wait + $rs->mst_smt) : $sucessCount2 = number_format($rs->mst_wait + $rs->mst_smt);
										($failCount1 === '-') ? $failCount1 = number_format($rs->mst_err_smt) : $failCount2 = number_format($rs->mst_err_smt);
									}
								}
								break;
							case 'wcm' :
								if ($rs->mst_smt > 0 || $rs->mst_err_smt > 0) {
									if ($rs->mst_type1 != "") {
										$sucessCount2 = number_format($rs->mst_wait + $rs->mst_smt);
										$failCount2 = number_format($rs->mst_err_smt);
									} else {
										($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_wait + $rs->mst_smt) : $sucessCount2 = number_format($rs->mst_wait + $rs->mst_smt);
										($failCount1 === '-') ? $failCount1 = number_format($rs->mst_err_smt) : $failCount2 = number_format($rs->mst_err_smt);
									}
								}
								break;
                            case 'rc' :
                            case 'rcs' :
                            case 'rcm' :
                            case 'rct' :
								if ($rs->mst_rcs > 0 || $rs->mst_err_rcs > 0) {
									if ($rs->mst_type1 != "") {
									    $sucessCount2 = number_format($rs->mst_wait + $rs->mst_rcs);
										$failCount2 = number_format($rs->mst_err_rcs);
									} else {
									    ($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_wait + $rs->mst_rcs) : $sucessCount2 = number_format($rs->mst_wait + $rs->mst_rcs);
										($failCount1 === '-') ? $failCount1 = number_format($rs->mst_err_rcs) : $failCount2 = number_format($rs->mst_err_rcs);
									}
								}
								break;
							case 'at' || 'AL' : //2021-01-05 2차 알림톡 추가
								if ($rs->mst_at > 0 || $rs->mst_err_at > 0) {
									if ($rs->mst_type1 != "at" || $rs->mst_type1 != "AL") {
										$sucessCount2 = number_format($rs->mst_wait + $rs->mst_at);
										$failCount2 = number_format($rs->mst_err_at);
									}
								}
								break;
						}

                        switch($rs->mst_type3&&$rs->mst_kind!="rc") {

    			            case 'wc' :
    			                if ($rs->mst_smt > 0 || $rs->mst_err_smt > 0) {
    		                        $sucessCount3 = number_format($rs->mst_wait + $rs->mst_smt);
    		                        $failCount3 = number_format($rs->mst_err_smt);
    			                }
    			                break;
    			            case 'wcs' :
    			                if ($rs->mst_smt > 0 || $rs->mst_err_smt > 0) {
    		                        $sucessCount3 = number_format($rs->mst_wait + $rs->mst_smt);
    		                        $failCount3 = number_format($rs->mst_err_smt);
    			                }
    			                break;
    			            case 'wcm' :
    			                if ($rs->mst_smt > 0 || $rs->mst_err_smt > 0) {
    		                        $sucessCount3 = number_format($rs->mst_wait + $rs->mst_smt);
    		                        $failCount3 = number_format($rs->mst_err_smt);
    			                }
    			                break;
    			        }

				        if ($rs->mst_type2 == 'at' || $rs->mst_type2 == 'ai') {   // 알림톡외 카카옥톡 발송 카운트
				            $kakaoTalkCount = $rs->mst_ft + $rs->mst_err_ft + $rs->mst_ft_img + $rs->mst_err_ft_img;
				            $alimTalkCount = $rs->mst_at + $rs->mst_err_at;
				        } else {
				            $kakaoTalkCount = $rs->mst_ft + $rs->mst_err_ft + $rs->mst_at + $rs->mst_err_at + $rs->mst_ft_img + $rs->mst_err_ft_img;
				            $alimTalkCount = 0;
				        }
						// $kakaoTalkCount = $rs->mst_ft + $rs->mst_err_ft + $rs->mst_at + $rs->mst_err_at + $rs->mst_ft_img + $rs->mst_err_ft_img;
						$messageCount = $rs->mst_grs + $rs->mst_err_grs + $rs->mst_grs_sms + $rs->mst_err_grs_sms + $rs->mst_grs_mms + $rs->mst_err_grs_mms + $rs->mst_nas + $rs->mst_err_nas + $rs->mst_nas_sms + $rs->mst_err_nas_sms + $rs->mst_nas_mms + $rs->mst_err_nas_mms + $rs->mst_smt + $rs->mst_err_smt + $rs->mst_rcs + $rs->mst_err_rcs;

						// if ($this->member->item("mem_level") >= 100) {
						// 	if ($messageCount > 0) {
						// 		if ($rs->mst_type1 != "") {
						// 			$resultDelayCount2 = '('.number_format($rs->mst_wait).')';
						// 		} else {
						// 			($kakaoTalkCount > 0) ? $resultDelayCount2 = '('.number_format($rs->mst_wait).')' : $resultDelayCount1 = '('.number_format($rs->mst_wait).')';
						// 		}
						// 	}
                        //
						// }
                        if ($this->member->item("mem_level") >= 100) {

				                if ($rs->mst_type2 == 'at' || $rs->mst_type2 == 'ai') {
				                    $resultDelayCount1 = '';
				                    $resultDelayCount2 = '';
				                    $resultDelayCount3 = '';
                                    if ($rs->mst_type3&&$rs->mst_kind!="rc") {
        				                if($messageCount > 0) {
        				                    $resultDelayCount1 = '';
        				                    $resultDelayCount2 = '';
        				                    $resultDelayCount3 = '('.number_format($rs->mst_wait).')';
        				                }
        				            }
				                } else {
				                    if ($messageCount > 0) {
				                        if ($rs->mst_type1 != '') {
				                            $resultDelayCount1 = '';
				                            $resultDelayCount2 = '('.number_format($rs->mst_wait).')';
				                        } else {
				                            $resultDelayCount1 = '('.number_format($rs->mst_wait).')';
				                            $resultDelayCount2 = '';
				                            //($kakaoTalkCount > 0) ? $resultDelayCount2 = '('.number_format($rs->mst_wait).')' : $resultDelayCount1 = '('.number_format($rs->mst_wait).')';

				                        }
				                    }
				                    $resultDelayCount3 = '';
				                }


				        }

						$totalQty = $rs->mst_grs + $rs->mst_err_grs + $rs->mst_grs_sms + $rs->mst_err_grs_sms + $rs->mst_grs_mms + $rs->mst_err_grs_mms + $rs->mst_nas + $rs->mst_err_nas + $rs->mst_nas_sms + $rs->mst_err_nas_sms + $rs->mst_nas_mms + $rs->mst_err_nas_mms + $rs->mst_ft + $rs->mst_err_ft + $rs->mst_at + $rs->mst_err_at + $rs->mst_ft_img + $rs->mst_err_ft_img + $rs->mst_smt + $rs->mst_err_smt + $rs->mst_rcs + $rs->mst_err_rcs;

						if ($rs->mst_qty == $totalQty && $rs->mst_qty > 0) {
							$statusClass1 = 'sending_stat end';
							$statusText1 = '정산완료';
							$statusClass2 = '';
							$statusText2 = '';
                            $statusClass3 = '';
						    $statusText3 = '';
						} else {
							if ($rs->mst_type1 !== '' && $rs->mst_type2 !== '') { //1차, 2차 모두 사용
								if ($kakaoTalkCount > 0) {
									$statusClass1 = 'sending_stat_now send';
									$statusText1 = '발송완료';
									if($rs->mst_wait > 0) {
										if ($messageCount > 0) {
                                            if($rs->mst_type3 !== ''&&$rs->mst_kind!="rc"){
                                                $statusClass3 = 'sending_stat_now send';
    											$statusText3 = '발송완료';
                                            }else{
                                                $statusClass2 = 'sending_stat_now send';
    											$statusText2 = '발송완료';
                                            }

										} else {
											$statusClass2 = 'sending_stat_now request';
											$statusText2 = '결과수신';
                                            if($rs->mst_type3 !== ''&&$rs->mst_kind!="rc"){
                                                $statusClass3 = 'sending_stat_now request';
    											$statusText3 = '결과수신';
                                            }
										}
									} else {
										$statusClass2 = '';
										$statusText2 = '';
                                        $statusClass3 = '';
            						    $statusText3 = '';
									}
								} else {
									if ($statusClass1 === '' && $statusText1 === '') {
										if ($messageCount > 0) {
											$statusClass1 = 'sending_stat_now send';
											$statusText1 = '발송완료';
										} else {
											$statusClass1 = 'sending_stat_now request';
											$statusText1 = '결과수신';
										}
										$statusClass2 = '';
										$statusText2 = '';
									}
								}
							} else if ($rs->mst_type1 !== '' && $rs->mst_type2 === '') {  // 1차 알림톡, 친구톡, 친구톡이미지만 사용
								if($kakaoTalkCount > 0) {
									$statusClass1 = 'sending_stat end';
									$statusText1 = '발송완료';
								} else {
									if (!$reservedFlag) {
										$statusClass1 = 'sending_stat send';
										$statusText1 = '결과수신';
									}

								}
								$statusClass2 = '';
								$statusText2 = '';
							} else if ($rs->mst_type1 === '' && $rs->mst_type2 !== '') {  // 웹(A)LMS, 웹(A)SMS, 웹(A)MMS, 웹(B)LMS, 웹(B)SMS, 웹(B)MMS만 사용
								if ($rs->mst_qty > 0 && $messageCount == 0) {
									if (!$reservedFlag) {
										$statusClass1 = 'sending_stat send';
										$statusText1 = '결과수신';
									}
								} else if ($rs->mst_qty > 0 && $messageCount > 0){
									$statusClass1 = 'sending_stat end';
									$statusText1 = '발송완료';
								}
								$statusClass2 = '';
								$statusText2 = '';
							} else {
								$statusClass1 = '';
								$statusText1 = '';
								$statusClass2 = '';
								$statusText2 = '';
							}
						}
						if($statusClass1 =='sending_stat reserve' || $statusClass1 =='sending_stat end') {
					?>
						<span class="<?=$statusClass1 ?>"><?=$statusText1 ?></span>
					<? } else { ?>
						<div class="send_type fir"><span class="<?=$statusClass1 ?>"><?=$statusText1 ?></span></div>
						<div class="send_type sec"><span class="<?=$statusClass2 ?>"><?=$statusText2 ?></span></div>
                        <? if(!empty($rs->mst_type3)&&$rs->mst_kind!="rc") { ?>
							<div clase="send_type thi"><span class="<?=$statusClass3 ?>"><?=$statusText3 ?></span></div>
						<? } ?>
					<? } ?>
					</div>
				</div>
                <? if($rs->mst_img_link_url) {?>
				<div class="input_content_wrap">
					<label class="input_tit">이미지 링크</label>
					<div class="input_content">
						<div class="input_link_wrap">
								<input type='text' style='flex:1' value='<?=$rs->mst_img_link_url?>' readonly>
								<button class="btn md confirm" style="margin-left: -1px; border-radius: 0;" onclick="window.open('<?=$rs->mst_img_link_url?>','link_url','width=800,height=600,location=no,status=no,scrollbars=no');">링크확인</button>
						</div>
					</div>
				</div>
				<? } ?>
				<div class="input_content_wrap">
					<label class="input_tit">
					<? if($rs->mst_kind == "MS" && $ums->link_type == "editor"){ ?>
						에디터 링크
					<? }else if($rs->mst_agreeid != ""){ //2021-01-20 개인정보동의 ?>
						개인정보동의
					<? }else if($rs->mst_type2 == "at" || $rs->mst_type2 == "ai"){ //2021-01-05 2차 알림톡 추가 ?>
						<span>1차</span> 버튼
					<? }else{ ?>
						버튼
					<? } ?>
					</label>
					<div class="input_content">
						<? if($rs->mst_button) {
						$btns = json_decode($rs->mst_button);
						$no = 0;
						foreach($btns as $btn) {
							$no++;
							$b = json_decode($btn, true);
							if($rs->mst_agreeid != "" && $no == 1){ //2021-01-20 개인정보동의
								echo "<button class='btn md link_name fl' onclick='modal_open_agree();' style='margin-top:-5px;'>결과확인</button>";
							}else{
								if(strlen($b[0]["name"])> 1) {
									if($b[0]["type"] == "WL") {
										$linkbutton = "";
										$linkbutton = "<div class='input_link_wrap'>
											<button class='btn md link_name fl' onclick='urlConfirm(\"".$b[0]["url_mobile"]."\", \"". $no ."\")'>".$b[0]["name"]."</button>
											<div style='overflow: hidden;'>";
										if($b[0]["url_mobile"] != "") {
											$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]["url_mobile"]."' disabled>";
										} else {
											$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' disabled>";
										}
										if($b[0]["url_pc"] != "" && $b[0]["url_mobile"] == "") {
											$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]["url_pc"]."' disabled>";
										}
										$linkbutton = $linkbutton."</div>
										</div>";
										echo $linkbutton;
									} else if($b[0]["type"] == "AL") {
										$linkbutton = "";
										$linkbutton = "<div class='input_link_wrap'>
											<button class='btn md link_name fl' onclick='urlConfirm(\"".$b[0]["scheme_android"]."\", \"". $no ."\")'>".$b[0]["name"]."</button>
											<div style='overflow: hidden;'>";
										if($b[0]["scheme_android"] != "") {
											$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]["scheme_android"]."' disabled>";
										} else {
											$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' disabled>";
										}
										if($b[0]["scheme_ios"] != "") {
											$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]["scheme_ios"]."' disabled>";
										}
										$linkbutton = $linkbutton."</div>
										</div>";
										echo $linkbutton;
									} else if($b[0]["type"] == "AC") {
										$linkbutton = "";
										$linkbutton = "<div class='input_link_wrap'>
											<button class='btn md link_name fl' >".$b[0]["name"]."</button>
											<div style='overflow: hidden;'>";

											$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' disabled>";

										$linkbutton = $linkbutton."</div>
										</div>";
										echo $linkbutton;
									}
								}
							}
						}
						//json_decode(json_decode($rs->mst_button)[0])[0]->name;
						if($rs->mst_kind == "MS" && $ums->link_type == "editor"){ //1차문자 & 에디터
							$linkbutton = "";
							$linkbutton .= "<div class='input_link_wrap'>";
							$linkbutton .= "  <button class='btn md link_name fl' onclick='urlConfirm(\"http://". $_SERVER['HTTP_HOST'] ."/at/".$ums->short_url."\", \"1\")'>링크확인</button>";
							$linkbutton .= "  <div style='overflow: hidden;'>";
							$linkbutton .= "    <input type='text' style='width: 100%; margin-left: -1px;' value='http://". $_SERVER['HTTP_HOST'] ."/at/".$ums->short_url."' disabled>";
							$linkbutton .= "  </div>";
							$linkbutton .= "</div>";
							echo $linkbutton;
						}
					}
                    if($rs->mst_type2=="rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rcm"||$rs->mst_type2=="rct"){
                        if($rcsd->msr_button1&&$rcsd->msr_button1!="[]") {

                           $btns = json_decode($rcsd->msr_button1, true);
                           $no = 0;
                           if($btns[0]["action"]["urlAction"]!=""){
                               $linkbutton = "<div class='input_link_wrap'>
                                   <button class='btn md link_name fl' onclick='urlConfirm(\"".$btns[0]["action"]["urlAction"]["openUrl"]["url"]."\", \"". $no ."\")'>".$btns[0]["action"]["displayText"]."</button>
                                   <div style='overflow: hidden;'>
                                   <input type='text' style='width: 100%; margin-left: -1px;' value='".$btns[0]["action"]["urlAction"]["openUrl"]["url"]."' disabled>
                                   </div>
                               </div>";
                           }else{
                               $linkbutton = "<div class='input_link_wrap'>
                                   <button class='btn md link_name fl' >".$btns[0]["action"]["displayText"]."</button>
                                   <div style='overflow: hidden;'>
                                   <input type='text' style='width: 100%; margin-left: -1px;' value='".$btns[0]["action"]["dialerAction"]["dialPhoneNumber"]["phoneNumber"]."' disabled>
                                   </div>
                               </div>";
                           }

                           echo $linkbutton;
                           //json_decode(json_decode($rs->mst_button)[0])[0]->name;
                       }


                        if($rcsd->msr_button2&&$rcsd->msr_button2!="[]") {
                           $btns = json_decode($rcsd->msr_button2, true);
                           $no = 1;
                           if($btns[0]["action"]["urlAction"]!=""){
                               $linkbutton = "<div class='input_link_wrap'>
                                   <button class='btn md link_name fl' onclick='urlConfirm(\"".$btns[0]["action"]["urlAction"]["openUrl"]["url"]."\", \"". $no ."\")'>".$btns[0]["action"]["displayText"]."</button>
                                   <div style='overflow: hidden;'>
                                   <input type='text' style='width: 100%; margin-left: -1px;' value='".$btns[0]["action"]["urlAction"]["openUrl"]["url"]."' disabled>
                                   </div>
                               </div>";
                           }else{
                               $linkbutton = "<div class='input_link_wrap'>
                                   <button class='btn md link_name fl' >".$btns[0]["action"]["displayText"]."</button>
                                   <div style='overflow: hidden;'>
                                   <input type='text' style='width: 100%; margin-left: -1px;' value='".$btns[0]["action"]["dialerAction"]["dialPhoneNumber"]["phoneNumber"]."' disabled>
                                   </div>
                               </div>";
                           }
                           echo $linkbutton;
                           //json_decode(json_decode($rs->mst_button)[0])[0]->name;
                       }
                        if($rcsd->msr_button3&&$rcsd->msr_button3!="[]") {
                           $btns = json_decode($rcsd->msr_button3, true);
                           $no = 2;
                           if($btns[0]["action"]["urlAction"]!=""){
                               $linkbutton = "<div class='input_link_wrap'>
                                   <button class='btn md link_name fl' onclick='urlConfirm(\"".$btns[0]["action"]["urlAction"]["openUrl"]["url"]."\", \"". $no ."\")'>".$btns[0]["action"]["displayText"]."</button>
                                   <div style='overflow: hidden;'>
                                   <input type='text' style='width: 100%; margin-left: -1px;' value='".$btns[0]["action"]["urlAction"]["openUrl"]["url"]."' disabled>
                                   </div>
                               </div>";
                           }else{
                               $linkbutton = "<div class='input_link_wrap'>
                                   <button class='btn md link_name fl' >".$btns[0]["action"]["displayText"]."</button>
                                   <div style='overflow: hidden;'>
                                   <input type='text' style='width: 100%; margin-left: -1px;' value='".$btns[0]["action"]["dialerAction"]["dialPhoneNumber"]["phoneNumber"]."' disabled>
                                   </div>
                               </div>";
                           }
                           echo $linkbutton;
                           //json_decode(json_decode($rs->mst_button)[0])[0]->name;
                       }
                        //json_decode(json_decode($rs->mst_button)[0])[0]->name;
                        if($rs->mst_kind == "MS" && $ums->link_type == "editor"){ //1차문자 & 에디터
                            $linkbutton = "";
                            $linkbutton .= "<div class='input_link_wrap'>";
                            $linkbutton .= "  <button class='btn md link_name fl' onclick='urlConfirm(\"http://". $_SERVER['HTTP_HOST'] ."/at/".$ums->short_url."\", \"1\")'>링크확인</button>";
                            $linkbutton .= "  <div style='overflow: hidden;'>";
                            $linkbutton .= "    <input type='text' style='width: 100%; margin-left: -1px;' value='http://". $_SERVER['HTTP_HOST'] ."/at/".$ums->short_url."' disabled>";
                            $linkbutton .= "  </div>";
                            $linkbutton .= "</div>";
                            echo $linkbutton;
                        }
                    }
                    ?>
					</div>
				</div>
				<? if($rs->mst_type2 == "at" || $rs->mst_type2 == "ai"){ //2021-01-05 2차 알림톡 추가 ?>
				<div class="input_content_wrap">
					<label class="input_tit">
						<span>2차</span> 버튼
					</label>
					<div class="input_content">
						<? if($rs->mst_2nd_alim_btn) {
						$btns = json_decode($rs->mst_2nd_alim_btn);
						$no = 0;
						foreach($btns as $btn) {
							$no++;
							$b = json_decode($btn, true);
							if(strlen($b[0]["name"])> 1) {
								if($b[0]["type"] == "WL") {
									$linkbutton = "";
									$linkbutton = "<div class='input_link_wrap'>
										<button class='btn md link_name fl' onclick='urlConfirm(\"".$b[0]["url_mobile"]."\", \"". $no ."\")'>".$b[0]["name"]."</button>
										<div style='overflow: hidden;'>";
									if($b[0]["url_mobile"] != "") {
										$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]["url_mobile"]."' disabled>";
									} else {
										$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' disabled>";
									}
									if($b[0]["url_pc"] != "" && $b[0]["url_mobile"] == "") {
										$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]["url_pc"]."' disabled>";
									}
									$linkbutton = $linkbutton."</div>
									</div>";
									echo $linkbutton;
								} else if($b[0]["type"] == "AL") {
									$linkbutton = "";
									$linkbutton = "<div class='input_link_wrap'>
										<button class='btn md link_name fl' onclick='urlConfirm(\"".$b[0]["scheme_android"]."\", \"". $no ."\")'>".$b[0]["name"]."</button>
										<div style='overflow: hidden;'>";
									if($b[0]["scheme_android"] != "") {
										$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]["scheme_android"]."' disabled>";
									} else {
										$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' disabled>";
									}
									if($b[0]["scheme_ios"] != "") {
										$linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]["scheme_ios"]."' disabled>";
									}
									$linkbutton = $linkbutton."</div>
									</div>";
									echo $linkbutton;
								}
							}
						}
						//json_decode(json_decode($rs->mst_button)[0])[0]->name;
					}?>
					</div>
				</div>
				<? } ?>
                <?
                    if($this->member->item("mem_id") == $rs->detail_mem_id){
                    // if($this->member->item("mem_id") == 3){
                        if (((($rs->mst_type1 == "ft" || $rs->mst_type1 == "at" || $rs->mst_type1 == "ai" || $rs->mst_type1 == "fti" || $rs->mst_type1 == "ftw") && $rs->mst_type2 == "")
                        || ((($rs->mst_type2=="rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rcm"||$rs->mst_type2=="rct"
                        || $rs->mst_type2=="wc"||$rs->mst_type2=="wcs"||$rs->mst_type2=="wcm")) && $rs->mst_type1 == "")) && $statusText1 == "정산완료" && $fail_send_flag){
                ?>
                <div class="input_content_wrap">
                    <label class="input_tit">
                        <span>실패건 재발송</span>
                    </label>
                    <div class="input_content">
                        <input type="button" class="btn md color" style="cursor:pointer;" id="check" value="재전송" onClick="send_failed()">
                    </div>
                </div>
                <?
                        }
                    }
                ?>
				<? if($ums->link_type == "editor" or $ums->link_type == "smartEditor" or ($ums->link_type == "home"&&!empty($ums->url))) { //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터) ?>
				<div class="input_content_wrap">
					<label class="input_tit">에디터 내용수정</label>
					<div class="input_content">
						<p class="info_text">*발송 후라도 내용 수정후 저장 시 수정된 내용이 적용됩니다.</p>
						<!-- Summernote editor 시작 -->
						<script src="/summernote/summernote-lite.js"></script>
						<script src="/summernote/lang/summernote-ko-KR.js"></script>
						<link rel="stylesheet" href="/summernote/summernote-lite.css">
						<div id="summernote"></div>
						<script>
							$(document).ready(function() {
								$('#summernote').summernote({
									height: 240,                 // 에디터 높이
									minHeight: null,             // 최소 높이
									maxHeight: null,             // 최대 높이
									focus: false,                  // 에디터 로딩후 포커스를 맞출지 여부
									lang: "ko-KR",					// 한글 설정
									placeholder: '내용을 입력하세요.',	//placeholder 설정
									toolbar: [
										['fontsize', ['fontsize']],
										['style', ['bold', 'italic', 'strikethrough']],
										['color', ['forecolor','color']],
										['insert',['picture']]
									  ],
									callbacks: {
										onImageUpload: function(image) {
											uploadImage(image[0]);
										}
									},
								});

								$('#summernote').summernote('fontSize', 18);
								<? if(!empty($ums)){ ?>
									$('#summernote').summernote('code', '<?=str_replace("175.199.47.43", "14.43.241.107", $ums->html)?>');
								<? } ?>
							});

							function uploadImage(image) {
								var data = new FormData();
								data.append("image_file", image);
								data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
								$.ajax({
									url: 'http://<?=$_SERVER['HTTP_HOST']?>/dhnbiz/sender/image_upload/talkadvimg',
									cache: false,
									contentType: false,
									processData: false,
									data: data,
									type: "post",
									success: function(json) {
										//console.log(json);
										if(json['code']=='S') {
											var image = $('<img>').attr('src', json['imgurl']);
											$('#summernote').summernote("insertNode", image[0]);
										}
									},
									error: function(data) {
										console.log(data);
									}
								});
							}
						</script>
						<div class="tc mt-10">
							<button type="button" class="btn md yellow" type="button" onclick="ums_save('<?=$ums->short_url?>')">저장하기</button>
						</div>
					</div>
				</div>
				<? } ?>
				<!--
				<div class="input_content_wrap">
					<label class="input_tit">전단수정</label>
					<div class="input_content">
						<button class="btn_cus_add" id="cus_Btn"><i class="material-icons">add</i>수정하기</button>
						<div id="cus_Modal" class="cus_modal" style="display: none;">
							<div class="cus_modal-content"> <span class="cus_close">×</span>
							<p class="modal-tit"> 에디터수정 </p>
						   에디터
							<div class="btn_al_cen mg_t20">
								<button type="button" class="btn_st1" onclick="templet_tag_save();"><i class="material-icons">done</i>등록</button>
								<button type="button" class="btn_st1" onclick="modal_tag_close();" style="margin-left:5px;"><i class="material-icons">clear</i>취소</button>
							</div>
						</div>
					</div>
					<script>
						// Get the modal
						var modal = document.getElementById("cus_Modal");

						// Get the button that opens the modal
						var btn = document.getElementById("cus_Btn");

						// Get the <span> element that closes the modal
						var span = document.getElementsByClassName("cus_close")[0];

						// When the user clicks the button, open the modal
						btn.onclick = function() {
						  modal.style.display = "block";
						}

						// When the user clicks on <span> (x), close the modal
						span.onclick = function() {
						  modal.style.display = "none";
						}

						// When the user clicks anywhere outside of the modal, close it
						window.onclick = function(event) {
						  if (event.target == modal) {
							modal.style.display = "none";
						  }
						}
						</script>
					</div>
				</div>
				-->
			</div>
			<div class="info_detail_msg fl" <?=($rs->mst_kind!="rc"&&!empty($rs->mst_type3))? "style='width:930px;left:650px;'" : ""?>>
				<? if( $rs->mst_kind != "MS") { ?>
				<?//=str_replace("\n", "<br/>", $rs->mst_content)?>
					<!-- 친구톡/알림톡 미리보기 -->
					<div class="mobile_preview detail">
						<div class="preview_circle"></div>
                        <div class="preview_round"><span>1차</span><? if($rs->mst_type1 == "at"){ ?>알림톡<? }else if($rs->mst_type1 == "ai"){?>알림톡(이미지)<?}else if($rs->mst_type2 == "rcs"){?>RCS SMS
                            <?}else if($rs->mst_type2 == "rc"){?>RCS LMS<?}else if($rs->mst_type2 == "rct"){?>RCS템플릿<?}else if($rs->mst_type2 == "rcm"){?>RCS MMS<?}else{ ?>친구톡<? } ?> 미리보기</div>
						<div class="preview_msg_wrap">
							<div class="preview_msg_window" <?=($rs->mst_type2=="rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rct"||$rs->mst_type2=="rcm")? "style='background:#fff;border: 1px solid #eee;'" : ""?>>
								<div class="preview_box_profile" <?= ($rs->mst_type2=="rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rct"||$rs->mst_type2=="rcm")? "style='margin-bottom:10px;'" : "" ?>>
                                    <? if($rs->mst_type2!="rc"&&$rs->mst_type2!="rcs"&&$rs->mst_type2!="rct"&&$rs->mst_type2!="rcm"){ ?>
									<span class="profile_thumb">
										<img src="/img/icon/icon_profile.jpg">
									</span>
                                <? }else{ ?>
                                    <span class="material-icons" style="font-size:12px;">arrow_back_ios</span>
                                <? } ?>
                                <span class="profile_text" <?=($rs->mst_type2=="rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rct"||$rs->mst_type2=="rcm")? "style = 'color:#000;margin-bottom:20px;font-size:12px;letter-spacing:-1px;'" : "" ?>>
                                    <?= ($rs->mst_type2=="rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rct"||$rs->mst_type2=="rcm")? $rcsbrand->brd_company : $rs->spf_company ?></span>
								</div>
								<div class="preview_box_msg" id="phone_standard" <?=($rs->mst_type2=="rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rct"||$rs->mst_type2=="rcm")? "style = 'background:#eee;border: 0px;'" : "" ?>>
								<? if($rs->mst_img_content) {?>
									<A HREF="javascript:CaricaFoto('<?=$rs->mst_img_content?>')" BORDER="0"><img id="image" name="image" src="<?=$rs->mst_img_content?>" style="width:100%; margin-bottom:5px;"></A>
								<?php } ?>
								<? if ($rs->mst_type1 == "ai") { ?>
									<img id="image" name="image" src="<?=$tmp_rs->tpl_imageurl?>" style="width:100%; margin-bottom:5px;">
								<? } ?>

                                <? if ($rs->mst_type2 == "rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rct"||$rs->mst_type2=="rcm") { ?>
									<?=str_replace("\n", "<br/>", $rcsd->msr_content)?>
								<? }else{ ?>
                                    <?=str_replace("\n", "<br/>", $rs->mst_content)?>
                                <? } ?>
								<!--<?=str_replace("\n", "<br />", $tmp_rs->tpl_extra)?>-->
								<!--<?=str_replace("\n", "<br />", $tmp_rs->tpl_ad)?>-->
								<? if($rs->mst_button) {
									$btns = json_decode($rs->mst_button);
									$no = 0;
									foreach($btns as $btn) {
										$no = $no + 1;
										$b = json_decode($btn, true);
										if(strlen($b[0]["name"])> 1) {
											if($b[0]["type"] == "WL") {
												$linkbutton = "";
												$linkbutton = '<div id="btn_preview_div'.$no.'" class="'.$no.'" style="border:1px solid !important; border-color: #e8e8e8 !important; height:35px; margin-top:'. (($no == 1) ? "10px" : "5px") .' !important;border-radius:2px;">'.
												'<p data-always-visible="1" id="btn_preview'.$no.'" data-rail-visible="0" cols="20" readonly="readonly" '.
												'style="text-align: center !important; padding-top:7px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"'.
												'>'.$b[0]["name"].'</p></div>';
												echo $linkbutton;
											} else if($b[0]["type"] == "AL") {
												$linkbutton = "";
												$linkbutton = '<div id="btn_preview_div'.$no.'" class="'.$no.'" style="border:1px solid !important; border-color: #e8e8e8 !important; height:35px; margin-top:'. (($no == 1) ? "10px" : "5px") .' !important;border-radius:2px;">'.
													'<p data-always-visible="1" id="btn_preview'.$no.'" data-rail-visible="0" cols="20" readonly="readonly" '.
													'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"'.
													'>'.$b[0]["name"].'</p></div>';
												echo $linkbutton;
											}else if($b[0]["type"] == "AC") {
												$linkbutton = "";
												$linkbutton = '<div id="btn_preview_div'.$no.'" class="'.$no.'" style="border:1px solid !important; border-color: #e8e8e8 !important; height:35px; margin-top:'. (($no == 1) ? "10px" : "5px") .' !important;border-radius:2px;">'.
												'<p data-always-visible="1" id="btn_preview'.$no.'" data-rail-visible="0" cols="20" readonly="readonly" '.
												'style="text-align: center !important; padding-top:7px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"'.
												'>'.$b[0]["name"].'</p></div>';
												echo $linkbutton;
											}
										}
									}
									//json_decode(json_decode($rs->mst_button)[0])[0]->name;
								}?>
                                <? if($rcsd->msr_button1&&$rcsd->msr_button1!="[]") {
									$btns = json_decode($rcsd->msr_button1, true);
									$no = 0;
                                    $linkbutton = "";
                                    $linkbutton = '<div id="btn_preview_div'.$no.'" class="'.$no.'" style="height:35px; margin-top:'. (($no == 1) ? "10px" : "5px") .' !important;border-radius:2px;">'.
                                    '<p data-always-visible="1" id="btn_preview'.$no.'" data-rail-visible="0" cols="20" readonly="readonly" '.
                                    'style="text-align: center !important; padding-top:7px !important; color: #5bc0de; overflow:hidden;border:0;resize:none;cursor:default;"'.
                                    '>'.$btns[0]["action"]["displayText"].'</p></div>';
                                    echo $linkbutton;
									//json_decode(json_decode($rs->mst_button)[0])[0]->name;
								}?>
                                <? if($rcsd->msr_button2&&$rcsd->msr_button2!="[]") {
									$btns = json_decode($rcsd->msr_button2, true);
									$no = 1;
                                    $linkbutton = "";
                                    $linkbutton = '<div id="btn_preview_div'.$no.'" class="'.$no.'" style="height:35px; margin-top:0px;margin-bottom:-7px;" !important;border-radius:2px;">'.
                                    '<p data-always-visible="1" id="btn_preview'.$no.'" data-rail-visible="0" cols="20" readonly="readonly" '.
                                    'style="text-align: center !important; padding-top:0px !important; color: #5bc0de; overflow:hidden;border:0;resize:none;cursor:default;"'.
                                    '>'.$btns[0]["action"]["displayText"].'</p></div>';
                                    echo $linkbutton;
									//json_decode(json_decode($rs->mst_button)[0])[0]->name;
								}?>
                                <? if($rcsd->msr_button3&&$rcsd->msr_button3!="[]") {
									$btns = json_decode($rcsd->msr_button3, true);
									$no = 2;
                                    $linkbutton = "";
                                    $linkbutton = '<div id="btn_preview_div'.$no.'" class="'.$no.'" style="height:35px; margin-top:0px;" !important;border-radius:2px;">'.
                                    '<p data-always-visible="1" id="btn_preview'.$no.'" data-rail-visible="0" cols="20" readonly="readonly" '.
                                    'style="text-align: center !important; padding-top:0px !important; color: #5bc0de; overflow:hidden;border:0;resize:none;cursor:default;"'.
                                    '>'.$btns[0]["action"]["displayText"].'</p></div>';
                                    echo $linkbutton;
									//json_decode(json_decode($rs->mst_button)[0])[0]->name;
								}?>
								<textarea name="msg" id="lms" name="lms" cols="30" rows="5" style="display:none"><?=$rs->mst_lms_content?></textarea>
								</div>
							</div>
						</div>
						<div class="preview_home"></div>
					</div><!-- mobile_preview END -->
				<? } ?>
                <?    if ($rs->mst_type2 == "at" || $rs->mst_type2 == "ai") { //2021-01-05 2차 알림톡 추가 ?>
                   <div class="mobile_preview detail" <?=($rs->mst_kind!="rc"&&!empty($rs->mst_type3))? "style='margin-left: 10px;'" : ""?>>
                       <div class="preview_circle"></div>
                       <div class="preview_round"><span>2차</span>알림톡 미리보기</div>
                       <div class="preview_msg_wrap">
                           <div class="preview_msg_window">
                               <div class="preview_box_profile">
                                   <span class="profile_thumb">
                                       <img src="/img/icon/icon_profile.jpg">
                                   </span>
                                   <span class="profile_text"><?= $rs->spf_company; ?></span>
                               </div>
                               <div class="preview_box_msg" id="phone_standard">
                                   <? if ($rs->mst_type2 == "ai") { ?>
   									<img id="image" name="image" src="<?=$tmp_rs->tpl_imageurl?>" style="width:100%; margin-bottom:5px;">
   								<? } ?>
                               <?=str_replace("\n", "<br/>", $rs->mst_2nd_alim_cont)?>
                               <? if($rs->mst_2nd_alim_btn) {
                                   $btns = json_decode($rs->mst_2nd_alim_btn);
                                   $no = 0;
                                   foreach($btns as $btn) {
                                       $no = $no + 1;
                                       $b = json_decode($btn, true);
                                       if(strlen($b[0]["name"])> 1) {
                                           if($b[0]["type"] == "WL") {
                                               $linkbutton = "";
                                               $linkbutton = '<div id="btn_preview_div'.$no.'" class="'.$no.'" style="border:1px solid !important; border-color: #e8e8e8 !important; height:35px; margin-top:'. (($no == 1) ? "10px" : "5px") .' !important;border-radius:2px;">'.
                                               '<p data-always-visible="1" id="btn_preview'.$no.'" data-rail-visible="0" cols="20" readonly="readonly" '.
                                               'style="text-align: center !important; padding-top:7px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"'.
                                               '>'.$b[0]["name"].'</p></div>';
                                               echo $linkbutton;
                                           } else if($b[0]["type"] == "AL") {
                                               $linkbutton = "";
                                               $linkbutton = '<div id="btn_preview_div'.$no.'" class="'.$no.'" style="border:1px solid !important; border-color: #e8e8e8 !important; height:35px; margin-top:'. (($no == 1) ? "10px" : "5px") .' !important;border-radius:2px;">'.
                                                   '<p data-always-visible="1" id="btn_preview'.$no.'" data-rail-visible="0" cols="20" readonly="readonly" '.
                                                   'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"'.
                                                   '>'.$b[0]["name"].'</p></div>';
                                               echo $linkbutton;
                                           }
                                       }
                                   }
                                   //json_decode(json_decode($rs->mst_button)[0])[0]->name;
                               }?>
                               <textarea name="msg" id="lms" name="lms" cols="30" rows="5" style="display:none"><?=$rs->mst_2nd_alim_cont?></textarea>
                               </div>
                           </div>
                       </div>
                       <div class="preview_home"></div>
                   </div>
               <? } ?>
				<? if(!is_null($rs->mst_content) && strlen($rs->mst_lms_content)>10){
                    if(!empty($rs->mst_type3)&&$rs->mst_kind!="rc"){
                    ?>
                    <!-- SMS 미리보기 -->
					<div class="sms_preview detail" style="margin-left:10px;">
						<? if ($rs->mst_type3 == "wa" || $rs->mst_type3 == "wb" || $rs->mst_type3 == "wc") { ?>
						<div class="msg_type <?=($rs->mst_type3=="wcs")? "sms" :"lms"?>"></div>
						<div class="preview_circle"></div>
						<div class="preview_round"><span>3차</span>문자 미리보기</div>
						<div class="preview_msg_wrap">
							<div class="preview_msg_window <?=($rs->mst_type3=="wcs")? "sms" :"lms"?>">
								<?=str_replace("\n", "<br/>", $rs->mst_lms_content)?>
							</div>
						</div>
                    <? } else if ($rs->mst_type3 == "was" || $rs->mst_type3 == "wbs" || $rs->mst_type3 == "wcs") { ?>
						<div class="msg_type  <?=($rs->mst_type3=="wc")? "lms" :"sms"?>"></div>
						<div class="preview_circle"></div>
						<div class="preview_round"><span>3차</span>문자 미리보기</div>
						<div class="preview_msg_wrap">
							<div class="preview_msg_window <?=($rs->mst_type3=="wc")? "lms" :"sms"?>">
								<?=str_replace("\n", "<br/>", $rs->mst_lms_content)?>
							</div>
						</div>
                    <? } else if ($rs->mst_type3 == "wam" || $rs->mst_type3 == "wbm" || $rs->mst_type3 == "wcm") { ?>
						<div class="msg_type mms"></div>
						<div class="preview_circle"></div>
						<div class="preview_round"><span>3차</span>문자 미리보기</div>
						<div class="preview_msg_wrap">
							<div class="preview_msg_window mms">
								<?php if(!empty($mms_img->origin1_path)) {?>
									<img style="margin-bottom: 10px" src="<?=substr($mms_img->origin1_path, 13, strlen($mms_img->origin1_path)-13)?>" />
								<?php  } ?>
								<?php if(!empty($mms_img->origin2_path)) {?>
									<img style="margin-bottom: 10px" src="<?=substr($mms_img->origin2_path, 13, strlen($mms_img->origin2_path)-13)?>" />
								<?php  } ?>
								<?php if(!empty($mms_img->origin3_path)) {?>
									<img style="margin-bottom: 10px" src="<?=substr($mms_img->origin3_path, 13, strlen($mms_img->origin3_path)-13)?>" />
								<?php  } ?>
								<?=str_replace("\n", "<br/>", $rs->mst_lms_content)?>
							</div>
						</div>
						<? } ?>
						<div class="preview_home"></div>
					</div>

                    <?}else{ ?>
					<!-- SMS 미리보기 -->
					<div class="sms_preview detail">
						<? if ($rs->mst_type2 == "wa" || $rs->mst_type2 == "wb" || $rs->mst_type2 == "wc" || $rs->mst_type2 == "rc"  ) { ?>
						<div class="msg_type <?=($rs->mst_type3=="wcs")? "sms" :"lms"?>"></div>
						<div class="preview_circle"></div>
						<div class="preview_round"><span><?=($rs->mst_type1 != "") ? "2" : (($rs->mst_type3!="")? "2" :"1")?>차</span>문자 미리보기</div>
						<div class="preview_msg_wrap">
							<div class="preview_msg_window <?=($rs->mst_type3=="wcs")? "sms" :"lms"?>">
								<?=str_replace("\n", "<br/>", $rs->mst_lms_content)?>
							</div>
						</div>
						<? } else if ($rs->mst_type2 == "was" || $rs->mst_type2 == "wbs" || $rs->mst_type2 == "wcs" || $rs->mst_type2 == "rcs") { ?>
						<div class="msg_type  <?=($rs->mst_type3=="wc")? "lms" :"sms"?>"></div>
						<div class="preview_circle"></div>
						<div class="preview_round"><span><?=($rs->mst_type1 != "") ? "2" : (($rs->mst_type3 != "")? "2" : "1")?>차</span>문자 미리보기</div>
						<div class="preview_msg_wrap">
							<div class="preview_msg_window <?=($rs->mst_type3=="wc")? "lms" :"sms"?>">
								<?=str_replace("\n", "<br/>", $rs->mst_lms_content)?>
							</div>
						</div>
						<? } else if ($rs->mst_type2 == "wam" || $rs->mst_type2 == "wbm" || $rs->mst_type2 == "wcm") { ?>
						<div class="msg_type mms"></div>
						<div class="preview_circle"></div>
						<div class="preview_round"><span><?=($rs->mst_type1 != "") ? "2" : "1"?>차</span>문자 미리보기</div>
						<div class="preview_msg_wrap">
							<div class="preview_msg_window mms">
								<?php if(!empty($mms_img->origin1_path)) {?>
									<img style="margin-bottom: 10px" src="<?=substr($mms_img->origin1_path, 13, strlen($mms_img->origin1_path)-13)?>" />
								<?php  } ?>
								<?php if(!empty($mms_img->origin2_path)) {?>
									<img style="margin-bottom: 10px" src="<?=substr($mms_img->origin2_path, 13, strlen($mms_img->origin2_path)-13)?>" />
								<?php  } ?>
								<?php if(!empty($mms_img->origin3_path)) {?>
									<img style="margin-bottom: 10px" src="<?=substr($mms_img->origin3_path, 13, strlen($mms_img->origin3_path)-13)?>" />
								<?php  } ?>
								<?=str_replace("\n", "<br/>", $rs->mst_lms_content)?>
							</div>
						</div>
						<? } ?>
						<div class="preview_home"></div>
					</div>
				<? }} ?>

			</div>
		</div>
	</div>
	<div class="form_section" id="div_send_list">
		<div class="inner_tit mg_t30">
			<button class="btn md fr excel" style="cursor:pointer; line-height:100%;" onclick="download_history()">엑셀 다운로드</button>
			<h3>발신 목록 (총 <span class="list_total"><?=number_format($total_rows)?></span>건)</h3>
		</div>
		<div class="white_box">
			<div class="search_wrap">
				<div class="table_btn_group clearfix">
					<? if($isManager == 'Y') {?>
						<!--<button class="btn md fr icon_resend" id="btnresend" onclick="resend()"/>실패건 재전송</button>-->
					<? } ?>
					<div class="search_box">
						<select style="width: 120px;" id="searchType" onChange="open_page(1);">
							<option value="all">전체</option>
                            <? if($rs->mst_type1=='at'||$rs->mst_type2=='at'||$rs->mst_type1=='AL'||$rs->mst_type2=='AL'){ ?>
							<option value="at" <?=($param['search_type']=="at") ? "selected" : ""?>>알림톡</option>
                            <? } ?>
                            <? if($rs->mst_type1=='ai'||$rs->mst_type2=='ai'){ ?>
							<option value="ai" <?=($param['search_type']=="ai") ? "selected" : ""?>>알림톡(이미지)</option>
                            <? } ?>
							<option value="ft" <?=($param['search_type']=="ft") ? "selected" : ""?>>친구톡</option>
							<option value="sms" <?=($param['search_type']=="sms") ? "selected" : ""?>>문자메시지</option>
                            <? if($rs->mst_type2=='rc'||$rs->mst_type2=='rcs'||$rs->mst_type2=='rcm'||$rs->mst_type2=='rct'){ ?>
                            <option value="rc" <?=($param['search_type']=="rc") ? "selected" : ""?>>RCS메시지</option>
                            <? } ?>
							<!-- 2021-03-29 문자메시지로 변경
							<option value="gs" <?=($param['search_type']=="gs") ? "selected" : ""?>>WEB(A)</option>
							<option value="ns" <?=($param['search_type']=="ns") ? "selected" : ""?>>WEB(B)</option>
							<option value="sm" <?=($param['search_type']=="sm") ? "selected" : ""?>>WEB(C)</option>-->
							<!-- 2019.09.20 주석처리
							<option value="15" <?=($param['search_type']=="15") ? "selected" : ""?>>015저가문자</option>
							<option value="ph" <?=($param['search_type']=="ph") ? "selected" : ""?>>폰문자</option-->
						</select>
						<select style="width: 120px;" id="searchResult" onChange="open_page(1);">
							<option value="all">전체</option>
							<!-- 2019.01.22 이수환 대기항목 삭제, 성공 = 대기 + 성공(중간 관리자, 일반 사용자일때), 상위관리자는 현행으로
							<option value="Y" <?=($param['search_result']=="Y") ? "selected" : ""?>>성공</option>
							<option value="W" <?=($param['search_result']=="W") ? "selected" : ""?>>대기</option>
							<option value="N" <?=($param['search_result']=="N") ? "selected" : ""?>>실패</option> -->
							<? if ($this->member->item("mem_level") >= 100) { ?>
							<option value="Y" <?=($param['search_result']=="Y") ? "selected" : ""?>>성공</option>
							<option value="W" <?=($param['search_result']=="W") ? "selected" : ""?>>대기</option>
							<option value="N" <?=($param['search_result']=="N") ? "selected" : ""?>>실패</option>
							<? } else { ?>
							<option value="YW" <?=($param['search_result']=="YW") ? "selected" : ""?>>성공</option>
							<option value="N" <?=($param['search_result']=="N") ? "selected" : ""?>>실패</option>
							<? } ?>
						</select>
						<input type="hidden" id="search_yn" value="<?=$param['search_yn']?>">
						<input type="text" class="form-control input-width-medium inline" id="searchStr" name="searchStr" placeholder="전화번호 입력" value="<?=$param['search_for']?>"/>
						<input type="button" class="btn md color" style="cursor:pointer;" id="check" value="조회" onClick="open_page(1);">
					</div>
				</div>
			</div>
			<div class="table_list">
				<table cellpadding="0" cellspacing="0" border="0">
					<colgroup>
							<col width="10%">
							<col width="30%">
							<col width="30%">
							<col width="30%">
					</colgroup>
					<thead>
						<tr>
							<th>NO.</th>
							<th>수신 전화번호</th>
							<th>발신타입</th>
							<th>결과</th>
						</tr>
					</thead>
					<tbody>
						<?$num = 0;
						//UTF-16 이나 UTF-8 UCS_2 UCS_4
						foreach($list as $r) { $num++; ?>
						<tr>
							<td alt="<?=$rs->MSGID?>"><?=number_format($perpage * ($param['page']-1) + $num)?></td>
							<td><?=substr("0".substr($r->PHN, 2),0,3)."****".substr($r->PHN, -4)?></td>
							<td>
								<? if($r->MESSAGE_TYPE=="at" || $r->MESSAGE_TYPE=="AL") {
										echo "알림톡";
									}else if($r->MESSAGE_TYPE=="ai") {
    										echo "알림톡(이미지)";
									}else if($r->MESSAGE_TYPE=="ft") {
										if($r->wide == "Y") {
											echo "친구톡(와이드)";
										} else {
											if(!empty($r->IMAGE_URL)) {
												echo "친구톡(이미지)";
											} else {
												echo "친구톡";
											}
										}
									}else if($r->MESSAGE_TYPE=="rc"||$r->MESSAGE_TYPE=="RC") {
										if($r->SMS_KIND=="S") {
											echo "RCS SMS";
										} else if($r->SMS_KIND=="L") {
											echo "RCS LMS";
										} else if($r->SMS_KIND=="M") {
											echo "RCS MMS";
										} else if($r->SMS_KIND=="T") {
											echo "RCS 템플릿";
										}
									} else if ($r->SMS_KIND=="S" && !is_null(($r->SMS_KIND))) {
										echo "단문 문자(SMS)";
									} else if ($r->SMS_KIND=="L" && !is_null(($r->SMS_KIND))){
										echo "장문 문자(LMS)";
									} else if ($r->SMS_KIND=="M" && !is_null(($r->SMS_KIND))){
										echo "이미지 문자(MMS)";
									}?>
							</td>
							<? if ($this->member->item("mem_level") >= 100) { ?>
							<td><?=($r->RESULT_MSG=="Y") ? "<span class='icon_success'>성공</span>".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL, $r->wide)."" : (($r->MESSAGE_TYPE=="pn") ? (($r->CODE=="M103") ? "<span class='fail_note'>수신거부(폰문자)</span>" : "문자:발송대기"): (($r->MESSAGE_TYPE=="S" || $r->MESSAGE_TYPE=="L" || $r->MESSAGE_TYPE=="M") ? "<span class='icon_fail'>발신 실패</span>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL,$r->wide).")" : $this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL,$r->wide)." <span class='icon_fail'>실패</span><span class='fail_note'>(".$this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE, $r->CODE).")</span>"))?>
							</td>
							<? } else { ?>
							<td><?=($r->RESULT_MSG=="Y" || $r->RESULT_MSG=="W") ? "<span class='icon_success'>성공</span><br/>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL, $r->wide).")" : (($r->MESSAGE_TYPE=="pn") ? (($r->CODE=="M103") ? "<font color='red'>수신거부(폰문자)<font>" : "문자<br/>발송대기"): (($r->MESSAGE_TYPE=="S" || $r->MESSAGE_TYPE=="L" || $r->MESSAGE_TYPE=="M") ? "<span class='icon_fail'>발신 실패</span><br/>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL, $r->wide).")" : $this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL, $r->wide)." ".(($r->RESULT_MSG=="M") ? "" : "<span class='icon_fail'>실패</span>")."<span class='fail_note'>(".$this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE, $r->CODE).")</span>"))?>
							</td>
							<? } ?>
						</tr>
						<?}?>
					</tbody>
				</table>
			</div>
			<div class="page_cen"><?=$page_html?></div>
		</div>
	</div>
</div><!-- mArticle END -->
<!-- 컨텐츠 전체 영역 END -->
<!--<div class="align-right" id="resend_btn" hidden>
<button class="btn" type="button" id="resend" onclick="resend();"><i class="icon-pencil"></i>LMS로 재발송</button>
</div>-->
<!--
<?=($r->MESSAGE_TYPE=="gs"||$r->MESSAGE_TYPE=="ns"||$r->MESSAGE_TYPE=="15"||$r->MESSAGE_TYPE=="ph") ? $rs->mst_lms_content : $rs->mst_content?><input id="img_url" type="hidden" value="<?=$r->IMAGE_URL?>"><input id="img_link" type="hidden" value="<?=$r->IMAGE_LINK?>"></td>-->
<!-- 2019.01.22 이수환 대기항목 삭제, 성공 = (중간 관리자, 일반 사용자일때), 상위관리자는 현행으로
<td class="text-center"><?=($r->RESULT_MSG=="Y") ? "성공<br/>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL).")" : (($r->MESSAGE_TYPE=="pn") ? (($r->CODE=="M103") ? "<font color='red'>수신거부(폰문자)<font>" : "문자<br/>발송대기"): (($r->MESSAGE_TYPE=="S" || $r->MESSAGE_TYPE=="L" || $r->MESSAGE_TYPE=="M") ? "발신실패<br/>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL).")" : $this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL)." 실패<br><font color='red'>".$this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE)."</font>"))?></td> -->

<form id="mainForm" name="mainForm" method="post">
<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
</form>
<div class="modal select fade" id="resend_box" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel" aria-hidden="true" style="overflow:hidden;">
	<div class="modal-dialog modal-lg sendbox-dialog" id="modal">
		<div class="clear modal-content mt20">
			<!--<h4 class="modal-title" align="center">실패 재전송</h4>-->
			<div class="modal-body select-body">
    			<div class="row">
                    <div class="col-xs-12 mb10 mt20" align="center">
    				<!-- <div class="clear sendbox-tab_wrap">
                        <td colspan = "2" style="border: 1px solid #ccc;">
                            <select class="select2 input-width-medium" name="mem_2nd_send" id="mem_2nd_send"   >
                                <option value="GREEN_SHOT"  />WEB문자(A)</option>
                                <option value="NASELF"  />WEB문자(B)</option>
            				</select>
        				</td>
        				-->
        				실패 메세지를 재전송 하시겠습니까?
    				</div>
    				<div class="modal_bottom mg_t30">
    					<button type="button" class="btn md modal_cancel" data-dismiss="modal">취소</button>
    					<button type="button" class="btn md test-send" id="code" name="code" onclick="resend_proc();">확인</button>
    				</div>
    			</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" id="modal">
		<div class="modal-content">
			<br/>
			<div class="modal-body">
				<div class="content identify">
				</div>
				<div>
					<p align="right">
						<br/><br/>
						<button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">
							확인
						</button>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" id="modal">
        <span  id="identify" class="dh_close"  data-dismiss="modal" style="margin-top:-30px;margin-right:-30px;">×</span>
		<div class="modal-content">
			<br/>
			<div class="modal-body" style="margin-top:-20px;">
                재전송할 서비스를 선택해주세요<br/><br/>
				<div class="content identify" style="margin-top:-10px;">
                    <?if ($rs->mst_type1=="ft"||$rs->mst_type1=="at"||$rs->mst_type1=="fti"||$rs->mst_type1=="ftw"||
                          $rs->mst_type2=="rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rcm"||$rs->mst_type2=="rct" || $rs->mst_type2=="wc"||$rs->mst_type2=="wcs"||$rs->mst_type2=="wcm"){?>
                    <button type="button" class="btn_st_sm" onClick="set_fail_phn('talk_img_adv_v3', '<?=$rs->mst_id?>')">
                        이미지 알림톡
                    </button>
                    <?}?>
                    <?if ($rs->mst_type1=="ft"||$rs->mst_type1=="ai"||$rs->mst_type1=="fti"||$rs->mst_type1=="ftw"||
                          $rs->mst_type2=="rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rcm"||$rs->mst_type2=="rct" || $rs->mst_type2=="wc"||$rs->mst_type2=="wcs"||$rs->mst_type2=="wcm"){?>
                    <button type="button" class="btn_st_sm" onClick="set_fail_phn('talk_adv_v3', '<?=$rs->mst_id?>')">
                        텍스트 알림톡
                    </button>
                    <?}?>
                    <!-- <button type="button" class="btn btn-primary enter" onClick="set_fail_phn('friend_v2', '<?=$rs->mst_id?>')">
                        친구톡
                    </button> -->
                    <?if ($rs->mst_type1=="ft"||$rs->mst_type1=="ai"||$rs->mst_type1=="fti"||$rs->mst_type1=="ftw"||$rs->mst_type1=="at"||
                          $rs->mst_type2=="rc"||$rs->mst_type2=="rcs"||$rs->mst_type2=="rcm"||$rs->mst_type2=="rct"){?>
                    <button type="button" class="btn_st_sm" onClick="set_fail_phn('lms', '<?=$rs->mst_id?>')">
                        문자메시지
                    </button>
                    <?}?>
                    <?if (($rs->mst_type1=="ft"||$rs->mst_type1=="ai"||$rs->mst_type1=="fti"||$rs->mst_type1=="ftw"||$rs->mst_type1=="at"||
                         $rs->mst_type2=="wc"||$rs->mst_type2=="wcs"||$rs->mst_type2=="wcm") && $rs->mem_rcs_yn == "Y"){?>
                    <button type="button" class="btn_st_sm" onClick="set_fail_phn('rcs_v1', '<?=$rs->mst_id?>', 'S')">
                        RCS SMS
                    </button>
                    <button type="button" class="btn_st_sm" onClick="set_fail_phn('rcs_v1', '<?=$rs->mst_id?>', 'L')">
                        RCS LMS
                    </button>
                    <!-- <button type="button" class="btn btn-primary enter" onClick="set_fail_phn('rcs_v1', '<?=$rs->mst_id?>')", 'M'>
                        RCS MMS
                    </button> -->
                    <?}?>

				</div>
				<div>
					<p align="right">
						<br/><br/>
						<!-- <button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">
							취소
						</button> -->
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
function CaricaFoto(img){
	foto1= new Image();
	foto1.src=(img);
	Controlla(img);
}
function Controlla(img){
	if((foto1.width!=0)&&(foto1.height!=0)){
		viewFoto(img);
	}
	else{
		funzione="Controlla('"+img+"')";
		intervallo=setTimeout(funzione,20);
	}
}

function viewFoto(img){
	largh=foto1.width+20;
	altez=foto1.height+20;
	stringa="width="+largh+",height="+altez;
	finestra=window.open(img,"",stringa);
}

function autoHypenPhone(str){
	str = str.replace(/[^0-9]/g, '');
	var tmp = '';
	if( str.length < 4){
		return str;
	}else if(str.length < 7){
		tmp += str.substr(0, 3);
		tmp += '-';
		tmp += str.substr(3);
		return tmp;
	}else if(str.length < 11){
		tmp += str.substr(0, 3);
		tmp += '-';
		tmp += str.substr(3, 3);
		tmp += '-';
		tmp += str.substr(6);
		return tmp;
	}else{
		tmp += str.substr(0, 3);
		tmp += '-';
		tmp += str.substr(3, 4);
		tmp += '-';
		tmp += str.substr(7);
		return tmp;
	}
	return str;
}

		<!-- 추가수정 실패목록 있으면 재발송 버튼 hidden 삭제처리 -->
	$( document ).ready(function() {
		$("#nav li.nav10").addClass("current open");
		var sms_sender = autoHypenPhone("<?=$sms_sender_num ?>");
		$("#sms_sender_num").html(sms_sender);
<?	if(count($list) > 0) {?>
		var receivers = [];
<?		foreach($list as $r) {?>
		var result = '<?=$r->RESULT?>';
		if (result == '실패') {
			var phn = '<?=$r->PHN?>';
			receivers.push(phn);
		}
<?		}?>
		/*
		if (receivers != "" && $("#img_url").val().replace(/ /gi,"") == "") {
		$("#resend_btn").attr("hidden", false);
		} else if (receivers != "" && $("#img_url").val().replace(/ /gi,"") != "") {
		$("#resend").html('<i class="icon-pencil"></i>MMS로 재발송');
		$("#resend_btn").attr("hidden", false);
		}
		*/
<?	}?>
scroll_prevent();
});
</script>
<script type="text/javascript">
	$("#nav li.nav01").addClass("current open");

	$('#searchStr').unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13 && $('#searchStr').val() != "") {
			open_page(1);
		}
	});

	function reserve_cancel(mst_id) {
		var mst_reserved_dt = "<?=$rs->mst_reserved_dt ?>";
		if (mst_reserved_dt!="00000000000000") {
			$.ajax({
				url: "/dhnbiz/sender/history/get_datetime",
				type: "POST",
				data: {<?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>"},
				success: function (json) {
					var mst_reserved_datetime = "<?= $this->funn->format_date($rs->mst_reserved_dt, '-', 14) ?>";
					var reservedDate = new Date(mst_reserved_datetime);

					var today_temp = json['now_date'];
					reservedDate.setMinutes(reservedDate.getMinutes() - 10);
					var today = new Date(today_temp);
					//console.log('today : '+ today + ' - reservedDate :' + reservedDate);
					if(today >= reservedDate) {
						alert("예약발송을 취소할 수 없습니다.\n 예약발송 시간 10분전부터 취소할 수 없습니다.");
						location.reload();
					} else {
						if(confirm('예약 발송을 취소 하시겠습니까?')) {

							$.ajax({
								url: "/dhnbiz/sender/history/get_datetime",
								type: "POST",
								data: {<?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>"},
								success: function (json) {
									var mst_reserved_datetime = "<?= $this->funn->format_date($rs->mst_reserved_dt, '-', 14) ?>";
									var reservedDate = new Date(mst_reserved_datetime);

									var today_temp = json['now_date'];
									reservedDate.setMinutes(reservedDate.getMinutes() - 10);
									var today = new Date(today_temp);
									//console.log('today : '+ today + ' - reservedDate :' + reservedDate);
									if(today >= reservedDate) {
										alert("예약발송을 취소할 수 없습니다.\n 예약발송 시간 10분전부터 취소할 수 없습니다.");
										location.reload();
									} else {
										var form = document.createElement("form");
										form.setAttribute("method", "post");
										form.setAttribute("action", "/dhnbiz/sender/history/reserve_cnacel");

										var param_mem_id = document.createElement("input");
										param_mem_id.setAttribute("type", "hidden");
										param_mem_id.setAttribute("name", "mst_id");
										param_mem_id.setAttribute("value", mst_id);
										form.appendChild(param_mem_id);

										var param_csrf = document.createElement("input");
										param_csrf.setAttribute("type", "hidden");
										param_csrf.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
										param_csrf.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");

										form.appendChild(param_csrf);

										document.body.appendChild(form);
										form.submit();
									}
								},
								error: function() {
								}
							});


						}
					}
				},
				error: function() {
				}
			});

//            	var mst_reserved_datetime = "<?= $this->funn->format_date($rs->mst_reserved_dt, '-', 14) ?>";
// 				var reservedDate = new Date(mst_reserved_datetime);
// 				var today = new Date();
// 				reservedDate.setMinutes(reservedDate.getMinutes() - 10);
// 				//alert("today : " + today + "/n reservedDate : " + reservedDate);

// 				if(today >= reservedDate) {
// 					alert("예약발송을 취소할 수 없습니다.\n 예약발송 시간 10분전부터 취소할 수 없습니다.");
// 					location.reload();
// 				} else {
// 					if(confirm('예약 발송을 취소 하시겠습니까?')) {
// 						var form = document.createElement("form");
// 						form.setAttribute("method", "post");
// 						form.setAttribute("action", "/biz/sender/history/reserve_cnacel");

// 						var param_mem_id = document.createElement("input");
// 						param_mem_id.setAttribute("type", "hidden");
// 						param_mem_id.setAttribute("name", "mst_id");
// 						param_mem_id.setAttribute("value", mst_id);
// 						form.appendChild(param_mem_id);

// 						var param_csrf = document.createElement("input");
// 						param_csrf.setAttribute("type", "hidden");
					param_csrf.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
					param_csrf.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");

// 						form.appendChild(param_csrf);

// 						document.body.appendChild(form);
// 						form.submit();
// 					}
// 				}
		}
	}

	function scroll_prevent() {
		window.onscroll = function(){
			var arr = document.getElementsByTagName('textarea');
			for( var i = 0 ; i < arr.length ; i ++  ){
				try { arr[i].blur(); }catch(e){}
			}
		}
	}

	<?/*--------------------*
	   | 링크 확인창 띄우기 |
	   *--------------------*/?>
	function urlConfirm(url, no) {
		//var parent = obj.parentElement;
		//alert("["+ no +"] url : "+ url);
		var pattern = new RegExp("^(?:http(s)?:\\/\\/)?[\\wㄱ-힣.-]+(?:\\.[\\w\\.-]+)+[\\wㄱ-힣\\-\\._~:/?#[\\]@!\\$&\'\\(\\)\\*\\+,;=.]+$", "gm");
		//var url = $(parent).find('input').val();
		var link_type = "<?=$ums->link_type?>";
		if(url != "") {
			//if(pattern.test(url)) {
			if(url != "") {
				if(url.toLowerCase().indexOf("http://") == 0 || url.toLowerCase().indexOf("https://") == 0){
					url = url;
				} else {
					url = "http://" + url;
					$(parent).find('input').val(url);
				}
                smart_preview(url);
				// if((link_type == "editor" && no == 1) || (link_type == "smartEditor" && no == 2)){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터)
				// 	editor_preview(url); //에디터 전단보기
				// }else if((link_type == "smart" || link_type == "smartEditor") && no == 1){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터)
				// 	smart_preview(url); //스마트전단 전단보기
				// }else if(link_type == "coupon" && no == 1){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터)
				// 	coupon_preview(url, '<?=$ums->pcd_type?>'); //스마트전단 전단보기
				// }else{
				// 	// window.open(url, '_blank', 'location=no, resizable=no');
                //     smart_preview(url);
				// }
			} else {
				$(".content").html("링크 경로가 잘못되었습니다.");
				$("#myModal").modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$(parent).find('input').focus();
				});
			}
		} else {
			$(".content").html("링크 경로를 입력하세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$(parent).find('input').focus();
			});
		}
	}

	function open_page(page) {
		var type = $('#searchType').val();
		var result = $('#searchResult').val();
		var searchFor = $('#searchStr').val();
		var form = document.getElementById("mainForm");

		var typeField = document.createElement("input");
		typeField.setAttribute("type", "hidden");
		typeField.setAttribute("name", "search_type");
		typeField.setAttribute("value", type);
		form.appendChild(typeField);
		var resultField = document.createElement("input");
		resultField.setAttribute("type", "hidden");
		resultField.setAttribute("name", "search_result");
		resultField.setAttribute("value", result);
		form.appendChild(resultField);
		var forField = document.createElement("input");
		forField.setAttribute("type", "hidden");
		forField.setAttribute("name", "search_for");
		forField.setAttribute("value", searchFor);
		form.appendChild(forField);
		var pageField = document.createElement("input");
		pageField.setAttribute("type", "hidden");
		pageField.setAttribute("name", "page");
		pageField.setAttribute("value", page);
		form.appendChild(pageField);
		var field = document.createElement("input");
		field.setAttribute("type", "hidden");
		field.setAttribute("name", "search_yn");
		field.setAttribute("value", "Y");
		form.appendChild(field);
		form.submit();
	}

	$('#duration a').click(function() {
		$('#duration a').removeClass('active');
		$(this).addClass('active');
//            console.log($('#duration a.active').attr('value'));
		open_page(1);
	});

	<!-- 추가수정 실패목록 lms 재발송 스크립트 추가 -->
	function resend() {
		if (<?=$rs->mst_mem_id?> == <?=$this->member->item("mem_id")?>) {
			$('#resend_box').modal('show');
		} else {
			$(".content").html("파트너 ID로 로고인 및 계정변경후 발송하세요.");
			$("#myModal").modal({backdrop: 'static'});
		}

	}

	function resend_proc() {
		$('#resend_box').modal('hide');
		document.location.href='/dhnbiz/sender/send/lms/resend<? echo '/'.$rs->mst_id; ?>' ;
// 			$.ajax({
// 				url: "/biz/sender/lms/all",
// 				type: "POST",
// 				data: {
//					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
//					"kind": "<?=$list[0]->SMS_KIND?>",
//					"senderBox": "<?=$list[0]->SMS_SENDER?>",
//					"smslms_kind":"<?=$list[0]->SMS_KIND?>",
// 					"mem_2nd_send":$("#mem_2nd_send").val(),
// 					"msg":$("#lms").val(),
//					"fali_mst_id":"<?=$rs->mst_id?>",
// 					"reserveDt": "00000000000000"
// 				},
// 				success: function (json) {
// 					var customer_count = json['customer_count'];

// 					if (customer_count > 60000) {
// 						$(".content").html("최대 60,000명까지 가능합니다.");
// 						$('#myModal').modal({backdrop: 'static'});
// 					} else if (customer_count > 0) {
// 						$(".content").after('<div class="widget-content" id="upload_result"><p> 전체 ' + customer_count + ' 건 재발신 하였습니다.</p></div>');
// 						$('#myModal').modal({backdrop: 'static'});
// 					} else {
// 						$(".content").html("고객정보가 없습니다.");
// 						$('#myModal').modal({backdrop: 'static'});
// 					}
// 				},
// 				error: function (data, status, er) {
// 					$(".content").html("처리중 오류가 발생하였습니다.");
// 					$("#myModal").modal('show');
// 				}
// 			});

	}

	function download_history()
	{
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/dhnbiz/sender/download");

		var scrfField = document.createElement("input");
		scrfField.setAttribute("type", "hidden");
		scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		form.appendChild(scrfField);
		var keyField = document.createElement("input");
		keyField.setAttribute("type", "hidden");
		keyField.setAttribute("name", "searchKey");
		keyField.setAttribute("value", '<?=$key?>');
		form.appendChild(keyField);
		var kindField = document.createElement("input");
		kindField.setAttribute("type", "hidden");
		kindField.setAttribute("name", "searchType");
		kindField.setAttribute("value", $('#searchType').val());
		form.appendChild(kindField);
		var resultField = document.createElement("input");
		resultField.setAttribute("type", "hidden");
		resultField.setAttribute("name", "searchResult");
		resultField.setAttribute("value", $('#searchResult').val());
		form.appendChild(resultField);
		var srchField = document.createElement("input");
		srchField.setAttribute("type", "hidden");
		srchField.setAttribute("name", "searchStr");
		srchField.setAttribute("value", $('#searchStr').val());
		form.appendChild(srchField);
		form.submit();
	}

	//UMS 수정
	function ums_save(short_url) {
		$.ajax({
			url: "/dhnbiz/sender/history/ums_save",
			type: "POST",
			data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>",
				"html": $('#summernote').summernote('code'),
				"short_url":short_url,
				"mst_id":"<?=$key?>"
			},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				alert("UMS 내용이 변경 되었습니다.");
				return;
			},
			error: function () {
				alert("처리되지 않았습니다.");
				return;
			}
		});
	}

	//검색 후 전화번호 입력란 포커스
	//alert("search_yn : "+ $('#search_yn').val());
	if($('#search_yn').val() == "Y"){
		$('#searchStr').focus();
	}
</script>
<!-- 개인정보동의 조회 모달 -->
<div class="modal select fade" id="myModalAgreeResult" tabindex="-1" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<i class="material-icons modal_close" data-dismiss="modal">close</i>
			<div class="modal_title">결과조회</div>
			<div style="padding:15px;">
				<div class="search_wrap fr">
					<select id="search_state" onChange="open_agree_result(1);">
						<option value="">- 상태전체 -</option>
						<option value="0">발송완료</option>
						<option value="1">확인완료</option>
						<option value="2">수신동의</option>
					</select>
					<input type="text" id="search_phn" style="margin-left:5px;" placeholder="전화번호를 입력해 주세요" onKeypress="if(event.keyCode==13){ open_agree_result(1); }">
					<button type="button" class="btn md" style="margin-left:5px;" onclick="open_agree_result(1)">검색</button>
				</div>
				<div class="content" id="modal_user_result_list"></div>
			</div>
		</div>
	</div>
</div>
<script>
	//개인정보동의 조회
	function modal_open_agree(){
		$("#myModalAgreeResult").modal({backdrop: 'static'});
 		$("#myModalAgreeResult").on('shown.bs.modal', function () {
 			$('.uniform').uniform();
 			$('select.select2').select2();
 		});
 		$('#myModalAgreeResult').unbind("keyup").keyup(function (e) {
 			var code = e.which;
 			if (code == 27) {
 				$(".btn-default.dismiss").click();
 			}
 		});
 		open_agree_result(1);
 	}
    function open_agree_result(page) {
		var search_state = $('#search_state').val(); //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색
		var search_phn = $('#search_phn').val(); //연락처 검색
		$('#myModalAgreeResult .content').html('').load(
			"/agreeview/agree_result",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"
				, "perpage" : "10" //개시물수
				, "page" : page //페이지
				, "agreeid" : "<?=$rs->mst_agreeid?>" //개인정보동의번호
			    , "search_state" : search_state //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색
			    , "search_phn" : search_phn //연락처 검색
			},
			function () {
				$('.uniform').uniform();
				$('select.select2').select2();
			}
		);
    }

    function send_failed(){
        $('#myModal2').modal('show');
    }

    function set_fail_phn(addr, mid, rcs_type = null){
        $.ajax({
			url: "/dhnbiz/sender/history/set_fail_phn",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
			  , "mid" : mid
              , "uid" : "<?=$rs->mem_userid?>"
              , "type" : "<?=$sendedTypeText1?>"
			},
			success: function (json) {
                if (json.code == "OK"){
                    location.href = "/dhnbiz/sender/send/" + addr + "?mid=" + mid + "&uid=" + "<?=$rs->mem_userid?>&cnt=" + json.msg + "&date=<?=$rs->mst_datetime?>" + (rcs_type != null ? "&type=" + rcs_type : "") + "&mtype=<?=$sendedTypeText1?>";
                } else {
                    alert(json.msg);
                }
			},
		});
    }
</script>
