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
					발신내역
				</div>
<!-- 타이틀 영역 END -->
<!-- 컨텐츠 전체 영역 -->
				<? 
				$sms_sender_num = $rs->mst_sms_callback;
				?>
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3><? if($rs->spf_company) { echo $rs->spf_company."(".$rs->spf_friend.")"; } else { echo $rs->mem_username; } ?></B><input type="hidden" id="pf_key" name="pf_key" value="<?=$rs->spf_key?>"><input type="hidden" id="sms_sender" name="sms_sender" value="<?=$rs->spf_sms_callback?>"></h3>
						</div>
						<div class="inner_content clearfix">
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
	                                           echo "<input type='button' class='btn btn-default' id='delete' value='삭제' onclick='reserve_cancel(".$rs->mst_id.")'/>";
	                                      }?>
									</div>
								</div>
								<div class="input_content_wrap">
									<label class="input_tit">발신타입</label>
									<div class="input_content">
										<? 
										$sendedTypeText1 = '-';
										$sendedTypeText2 = '-';
										switch($rs->mst_type1) {
										    case 'ft' :
										        $sendedTypeText1 = "친구톡";
										        break;
										    case 'at' :
										        $sendedTypeText1 = "알림톡";
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
										    }
										}
										echo "<div class=\"send_type fir\">".$sendedTypeText1."</div>";
										echo "<div class=\"send_type sec\">".$sendedTypeText2."</div>";
	                                    ?>
										
									</div>
								</div>
								<div class="input_content_wrap">
									<label class="input_tit">총 발신개수</label>
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
								        
								        $statusClass1 = '';  // 상태 span class 1차
								        $statusText1 = '';   // 상태 텍스트 1차
								        $statusClass2 = '';  // 상태 span class 2차
								        $statusText2 = '';   // 상태 텍스트 2차
								        
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
								        }
								        
								        $kakaoTalkCount = $rs->mst_ft + $rs->mst_err_ft + $rs->mst_at + $rs->mst_err_at + $rs->mst_ft_img + $rs->mst_err_ft_img;
								        $messageCount = $rs->mst_grs + $rs->mst_err_grs + $rs->mst_grs_sms + $rs->mst_err_grs_sms + $rs->mst_grs_mms + $rs->mst_err_grs_mms + $rs->mst_nas + $rs->mst_err_nas + $rs->mst_nas_sms + $rs->mst_err_nas_sms + $rs->mst_nas_mms + $rs->mst_err_nas_mms + $rs->mst_smt + $rs->mst_err_smt;
								        
								        if ($this->member->item("mem_level") >= 100) {
								            if ($messageCount > 0) {
								                if ($rs->mst_type1 != "") {
								                    $resultDelayCount2 = '('.number_format($rs->mst_wait).')';
								                } else {
    								                ($kakaoTalkCount > 0) ? $resultDelayCount2 = '('.number_format($rs->mst_wait).')' : $resultDelayCount1 = '('.number_format($rs->mst_wait).')';
								                }
								            }
								            
								        }
								        
								        $totalQty = $rs->mst_grs + $rs->mst_err_grs + $rs->mst_grs_sms + $rs->mst_err_grs_sms + $rs->mst_grs_mms + $rs->mst_err_grs_mms + $rs->mst_nas + $rs->mst_err_nas + $rs->mst_nas_sms + $rs->mst_err_nas_sms + $rs->mst_nas_mms + $rs->mst_err_nas_mms + $rs->mst_ft + $rs->mst_err_ft + $rs->mst_at + $rs->mst_err_at + $rs->mst_ft_img + $rs->mst_err_ft_img + $r->mst_smt + $r->mst_err_smt;

								        if ($rs->mst_qty == $totalQty && $rs->mst_qty > 0) {
								            $statusClass1 = 'sending_stat end';
								            $statusText1 = '정산완료';
								            $statusClass2 = '';
								            $statusText2 = '';
								        } else {
								            if ($rs->mst_type1 !== '' && $rs->mst_type2 !== '') { //1차, 2차 모두 사용
								                if ($kakaoTalkCount > 0) {
								                    $statusClass1 = 'sending_stat_now send';
								                    $statusText1 = '발송완료';
								                    if($rs->mst_wait > 0) {
								                        if ($messageCount > 0) {
								                            $statusClass2 = 'sending_stat_now send';
								                            $statusText2 = '발송완료';
								                        } else {
								                            $statusClass2 = 'sending_stat_now request';
								                            $statusText2 = '결과수신';
								                        }
								                    } else {
								                        $statusClass2 = '';
								                        $statusText2 = '';
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
									<? } ?>
									</div>
								</div>
								<div class="input_content_wrap">
									<label class="input_tit">버튼	</label>
									<div class="input_content">
										<? if($rs->mst_button) {
										    //echo $rs->mst_button;
                                        $btns = json_decode($rs->mst_button);

                                        $no = 0;

                                        foreach($btns as $btn) {
                                            $b = json_decode($btn, true);

                                            if(strlen($b[0]["name"])> 1) {
                                                if($b[0]["type"] == "WL") {
                                                    $linkbutton = "";
                                                    $linkbutton = "<div class='input_link_wrap'>
														<button class='btn md link_name fl' onclick='urlConfirm(\"".$b[0]["url_mobile"]."\")'>".$b[0]["name"]."</button>
														<div style='overflow: hidden;'>";
                                                    if($b[0]["url_mobile"] != "") {
                                                        $linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]["url_mobile"]."' disabled>";
                                                    } else {
                                                        $linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' disabled>";
                                                    }
                                                    if($b[0]["url_pc"] != "") {
                                                        $linkbutton = $linkbutton."<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]["url_pc"]."' disabled>";
                                                    }
                                                    $linkbutton = $linkbutton."</div>
													</div>";
                                                    echo $linkbutton;
                                                
                                                } else if($b[0]["type"] == "AL") {
                                                    
                                                    $linkbutton = "";
                                                    $linkbutton = "<div class='input_link_wrap'>
														<button class='btn md link_name fl' onclick='urlConfirm(\"".$b[0]["scheme_android"]."\")'>".$b[0]["name"]."</button>
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
							</div>
							<div class="info_detail_msg fl flex">
								<? if( $rs->mst_kind != "MS") { ?>
								<?//=str_replace("\n", "<br/>", $rs->mst_content)?>
									<!-- 친구톡/알림톡 미리보기 -->
									<div class="mobile_preview detail">
										<div class="preview_circle"></div>
										<div class="preview_round">친구톡(알림톡) 미리보기</div>
										<div class="preview_msg_wrap">
											<div class="preview_msg_window">
												<div class="preview_box_profile">
													<span class="profile_thumb">
														<img src="/img/icon/icon_profile.jpg">
													</span>
													<span class="profile_text"><?= $rs->spf_company; ?></span>
												</div>
												<div class="preview_box_msg" id="phone_standard">
												<? if($rs->mst_img_content) {?>
													<A HREF="javascript:CaricaFoto('<?=$rs->mst_img_content?>')" BORDER="0"><img id="image" name="image" src="<?=$rs->mst_img_content?>" style="width:100%; margin-bottom:5px;"></A>
												<?php } ?>
												<?=str_replace("\n", "<br/>", $rs->mst_content)?>
												            									<? if($rs->mst_button) {
            										    //echo $rs->mst_button;
                                                    $btns = json_decode($rs->mst_button);
            
                                                    $no = 0;
            
                                                    foreach($btns as $btn) {
                                                        $no = $no + 1;
                                                        $b = json_decode($btn, true);
            
                                                        if(strlen($b[0]["name"])> 1) {
                                                            if($b[0]["type"] == "WL") {
                                                                $linkbutton = "";
                                                                $linkbutton = '<div id="btn_preview_div'.$no.'" class="'.$no.'" style="height: 200px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">'.
                                                                '<p data-always-visible="1" id="btn_preview'.$no.'" data-rail-visible="0" cols="20" readonly="readonly" '.
				                                                'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"'.
				                                                '>'.$b[0]["name"].'</p></div>';
                                                                echo $linkbutton;
                                                            
                                                            } else if($b[0]["type"] == "AL") {
                                                                $linkbutton = "";
                                                                $linkbutton = '<div id="btn_preview_div'.$no.'" class="'.$no.'" style="height: 200px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">'.
                                                                    '<p data-always-visible="1" id="btn_preview'.$no.'" data-rail-visible="0" cols="20" readonly="readonly" '.
                                                                    'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"'.
                                                                    '>'.$b[0]["name"].'</p></div>';
                                                                echo $linkbutton;
                                                            }
                                                        }
                                                    }
                                                    //json_decode(json_decode($rs->mst_button)[0])[0]->name; 
                                                }?>
												<textarea name="msg" id="lms" name="lms" cols="30" rows="5" style="display:none"><?=$rs->mst_lms_content?></textarea>
												</div>
											</div>
										</div>
										<div class="preview_home"></div>
									</div><!-- mobile_preview END -->
								<? } 
								if(!is_null($rs->mst_content) && strlen($rs->mst_lms_content)>10) { ?>
									<!-- SMS 미리보기 -->
									<div class="sms_preview detail">
										<? if ($rs->mst_type2 == "wa" || $rs->mst_type2 == "wb" || $rs->mst_type2 == "wc" ) { ?>
										<div class="msg_type lms"></div>
										<div class="preview_circle"></div>
										<div class="preview_round">문자 미리보기</div>
										<div class="preview_msg_wrap">
											<div class="preview_msg_window lms">
												<?=str_replace("\n", "<br/>", $rs->mst_lms_content)?>
											</div>
										</div>										    
										<? } else if ($rs->mst_type2 == "was" || $rs->mst_type2 == "wbs" || $rs->mst_type2 == "wcs") { ?>
										<div class="msg_type sms"></div>
										<div class="preview_circle"></div>
										<div class="preview_round">문자 미리보기</div>
										<div class="preview_msg_wrap">
											<div class="preview_msg_window sms">
												<?=str_replace("\n", "<br/>", $rs->mst_lms_content)?>
											</div>
										</div>	
										<? } else if ($rs->mst_type2 == "wam" || $rs->mst_type2 == "wbm" || $rs->mst_type2 == "wcm") { ?>
										<div class="msg_type mms"></div>
										<div class="preview_circle"></div>
										<div class="preview_round">문자 미리보기</div>
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
									</div><!-- mobile_preview END -->								    
								<? } ?>																										
							</div>							
						</div>
					</div>
					<div class="form_section">
						<div class="inner_tit">
							<button class="btn md fr excel" onclick="download_history()">엑셀 다운로드</button>
							<h3>발신 목록 (총 <span class="list_total"><?=number_format($total_rows)?></span>건)</h3>
						</div>
						<div class="inner_content">
							<div class="search_wrap">
								<div class="table_btn_group clearfix">
									
						<? if($isManager == 'Y') {?>
                            <button class="btn md fr icon_resend" id="btnresend" onclick="resend()"/>실패건 재전송</button>
						<? } ?>
									<div class="search_box">
										<select style="width: 120px;" id="searchType">
			                                <option value="all">전체</option>
			                                <option value="at" <?=($param['search_type']=="at") ? "selected" : ""?>>알림톡</option>
			                                <option value="ft" <?=($param['search_type']=="ft") ? "selected" : ""?>>친구톡</option>
			                                <option value="gs" <?=($param['search_type']=="gs") ? "selected" : ""?>>WEB(A)</option>
			                                <option value="ns" <?=($param['search_type']=="ns") ? "selected" : ""?>>WEB(B)</option>
			                                <option value="sm" <?=($param['search_type']=="sm") ? "selected" : ""?>>WEB(C)</option>
			                                <!-- 2019.09.20 주석처리 
			                                <option value="15" <?=($param['search_type']=="15") ? "selected" : ""?>>015저가문자</option>
			                                <option value="ph" <?=($param['search_type']=="ph") ? "selected" : ""?>>폰문자</option-->
										</select>
			                            <select style="width: 120px;" id="searchResult">
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
			                            <input type="text" class="form-control input-width-medium inline" id="searchStr" name="searchStr" placeholder="전화번호 입력" value="<?=$param['search_for']?>"/>
			                            <input type="button" class="btn md color" id="check" value="조회" onclick="open_page(1)"/>
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
												<? if($r->MESSAGE_TYPE=="at") {
									                    echo "알림톡";
									                } else if($r->MESSAGE_TYPE=="ft") {
									                    if($r->wide == "Y") {
									                        echo "친구톡(와이드)";
									                    } else {
    									                    if(!empty($r->IMAGE_URL)) {
    									                        echo "친구톡(이미지)";
    									                    } else {
    									                        echo "친구톡";
    									                    }
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
							                <td><?=($r->RESULT_MSG=="Y") ? "<span class='icon_success'>성공</span>".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL, $r->wide)."" : (($r->MESSAGE_TYPE=="pn") ? (($r->CODE=="M103") ? "<span class='fail_note'>수신거부(폰문자)</span>" : "문자:발송대기"): (($r->MESSAGE_TYPE=="S" || $r->MESSAGE_TYPE=="L" || $r->MESSAGE_TYPE=="M") ? "<span class='icon_fail'>발신 실패</span>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL,$r->wide).")" : $this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL,$r->wide)." <span class='icon_fail'>실패</span><span class='fail_note'>(".$this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE).")</span>"))?>
							                </td>
							                <? } else { ?>
							                <td><?=($r->RESULT_MSG=="Y" || $r->RESULT_MSG=="W") ? "<span class='icon_success'>성공</span><br/>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL, $r->wide).")" : (($r->MESSAGE_TYPE=="pn") ? (($r->CODE=="M103") ? "<font color='red'>수신거부(폰문자)<font>" : "문자<br/>발송대기"): (($r->MESSAGE_TYPE=="S" || $r->MESSAGE_TYPE=="L" || $r->MESSAGE_TYPE=="M") ? "발신실패<br/>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL, $r->wide).")" : $this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL, $r->wide)." ".(($r->RESULT_MSG=="M") ? "" : "실패")."<span class='fail_note'>(".$this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE).")</span>"))?>
							                </td>
							                <? } ?>
										</tr>
<?}?>
										
									</tbody>
								</table>
							</div>
							<div class="align-center mt30"><?=$page_html?></div>		
	
						</div>
					</div>					
				</div><!-- mArticle END -->
<!-- 컨텐츠 전체 영역 END -->


<!--<div class="align-right" id="resend_btn" hidden>
<button class="btn" type="button" id="resend" onclick="resend();"><i class="icon-pencil"></i>LMS로 재발송</button>
</div>

<!--	                
<?=($r->MESSAGE_TYPE=="gs"||$r->MESSAGE_TYPE=="ns"||$r->MESSAGE_TYPE=="15"||$r->MESSAGE_TYPE=="ph") ? $rs->mst_lms_content : $rs->mst_content?><input id="img_url" type="hidden" value="<?=$r->IMAGE_URL?>"><input id="img_link" type="hidden" value="<?=$r->IMAGE_LINK?>"></td>
<!-- 2019.01.22 이수환 대기항목 삭제, 성공 = (중간 관리자, 일반 사용자일때), 상위관리자는 현행으로
<td class="text-center"><?=($r->RESULT_MSG=="Y") ? "성공<br/>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL).")" : (($r->MESSAGE_TYPE=="pn") ? (($r->CODE=="M103") ? "<font color='red'>수신거부(폰문자)<font>" : "문자<br/>발송대기"): (($r->MESSAGE_TYPE=="S" || $r->MESSAGE_TYPE=="L" || $r->MESSAGE_TYPE=="M") ? "발신실패<br/>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL).")" : $this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL)." 실패<br><font color='red'>".$this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE)."</font>"))?></td> -->



<form id="mainForm" name="mainForm" method="post">
<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
</form>

<div class="modal select fade" id="resend_box" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel" aria-hidden="true" style="overflow:hidden;">
	<div class="modal-dialog modal-lg sendbox-dialog" id="modal">
		<div class="clear modal-content mt20">
			<h4 class="modal-title" align="center">실패 재전송</h4>
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
        				실패 메세지를 재 전송 하시겠습니까?
    				</div>
    				<div class="col-xs-12 mt10" align="center">
    					<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
    					<button type="button" class="btn btn-primary" id="code" name="code" onclick="resend_proc();">확인</button>
    					<br/><br/>
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
        			url: "/biz/sender/history/get_datetime",
        			type: "POST",
        			data: {<?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>"},
        			success: function (json) {
                    	var mst_reserved_datetime = "<?= $this->funn->format_date($rs->mst_reserved_dt, '-', 14) ?>";
        				var reservedDate = new Date(mst_reserved_datetime);
        				
        				var today_temp = json['now_date'];
        				reservedDate.setMinutes(reservedDate.getMinutes() - 10);
        				var today = new Date(today_temp);
        				
        				if(today >= reservedDate) {
        					alert("예약발송을 취소할 수 없습니다.\n 예약발송 시간 10분전부터 취소할 수 없습니다.");
        					location.reload();
        				} else {
        					if(confirm('예약 발송을 취소 하시겠습니까?')) {
        						var form = document.createElement("form");
        						form.setAttribute("method", "post");
        						form.setAttribute("action", "/biz/sender/history/reserve_cnacel");
        						
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
        				}
         			},
         			error: function() {
         			}
        		});
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
		function urlConfirm(url) {

			//var parent = obj.parentElement;

			var pattern = new RegExp("^(?:http(s)?:\\/\\/)?[\\wㄱ-힣.-]+(?:\\.[\\w\\.-]+)+[\\wㄱ-힣\\-\\._~:/?#[\\]@!\\$&\'\\(\\)\\*\\+,;=.]+$", "gm");
			
			
			//var url = $(parent).find('input').val();

			if(url != "") {
				if(pattern.test(url)) {
	    		 	if (url.toLowerCase().indexOf("http://") == 0 || url.toLowerCase().indexOf("https://") == 0) {
	    	 			url = url;
	    	 		} else {
	    	 			url = "http://" + url;
	    	 			$(parent).find('input').val(url);
	    	 		}
	    	 		window.open(url, '_blank', 'location=no, resizable=no');
				} else {
					$(".content").html("링크 경로가 잘 못되었습니다.");
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
        	document.location.href='/biz/sender/send/lms/resend<? echo '/'.$rs->mst_id; ?>' ;
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
				form.setAttribute("action", "/biz/sender/download");

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
    </script>