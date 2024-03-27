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
									<div class="input_content">
										<?=$rs->SMS_SENDER?>0531234567
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
										            ($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(B) LMS' : $sendedTypeText2 = '웹(B) MMS';
										            break;
										        case 'wbs' :
										            ($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(B) SMS' : $sendedTypeText2 = '웹(B) SMS';
										            break;
										        case 'wbm' :
										            ($sendedTypeText1 === '-') ? $sendedTypeText1 = '웹(B) MMS' : $sendedTypeText2 = '웹(B) MMS';
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
										            ($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(LMS)' : $sendedTypeText2 = '문자(MMS)';
										            break;
										        case 'wbs' :
										            ($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(SMS)' : $sendedTypeText2 = '문자(SMS)';
										            break;
										        case 'wbm' :
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
										<span class="sending_stat end">수신완료</span>
									</div>
								</div>
								<div class="input_content_wrap">
									<label class="input_tit">버튼</label>
									<div class="input_content">
										<? if($rs->mst_button) {
                                        $btns = json_decode($rs->mst_button);
                                        $no = 0;
                                        ?>

										<?
                                        foreach($btns as $btn) {
                                            $b = json_decode($btn);
                                            if(strlen($b[0]->name)> 1) {
                                                if($b[0]->type == "WL") {
                                                    echo "<div class='input_link_wrap'>
														<button class='btn md link_name fl'>".$b[0]->name."</button>
														<div style='overflow: hidden;'>
														<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]->url_mobile."' disabled>
														</div>
													</div>";
                                                
                                                } else if($b[0]->type == "AL") {
                                                    echo "<div class='input_link_wrap'>
														<button class='btn md link_name fl'>".$b[0]->name."</button>
														<div style='overflow: hidden;'>
														<input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]->scheme_android."' disabled><input type='text' style='width: 100%; margin-left: -1px;' value='".$b[0]->scheme_ios."' disabled>
														</div>
													</div>";
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
								<!--<?=str_replace("\n", "<br/>", $rs->mst_content)?>-->
									<!-- 친구톡/알림톡 미리보기 -->
									<div class="mobile_preview detail">
										<div class="preview_circle"></div>
										<div class="preview_round">친구톡(알림톡) 미리보기</div>
										<div class="preview_msg_wrap">
											<div class="preview_msg_window">
												<div class="preview_box_profile">
													<span class="profile_thumb">
														<img src="/img/logo/logo_dhn.png">
													</span>
													<span class="profile_text">대형네트웍스</span>
												</div>
												<div class="preview_box_msg" id="phone_standard">
												<? if($rs->mst_img_content) {?>
													<img id="image" name="image" src="<?=$rs->mst_img_content?>" style="width:100%; margin-bottom:5px;">
												<?php } ?>
												<?=str_replace("\n", "<br/>", $rs->mst_content)?>
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
										<? if ($rs->mst_type2 == "wa" || $rs->mst_type2 == "wb") { ?>
										<div class="msg_type lms"></div>
										<div class="preview_circle"></div>
										<div class="preview_round">문자 미리보기</div>
										<div class="preview_msg_wrap">
											<div class="preview_msg_window lms">
												<?=str_replace("\n", "<br/>", $rs->mst_lms_content)?>
											</div>
										</div>										    
										<? } else if ($rs->mst_type2 == "was" || $rs->mst_type2 == "wbs") { ?>
										<div class="msg_type sms"></div>
										<div class="preview_circle"></div>
										<div class="preview_round">문자 미리보기</div>
										<div class="preview_msg_wrap">
											<div class="preview_msg_window sms">
												<?=str_replace("\n", "<br/>", $rs->mst_lms_content)?>
											</div>
										</div>	
										<? } else if ($rs->mst_type2 == "was" || $rs->mst_type2 == "wbs") { ?>
										<div class="msg_type mms"></div>
										<div class="preview_circle"></div>
										<div class="preview_round">문자 미리보기</div>
										<div class="preview_msg_wrap">
											<div class="preview_msg_window mms">
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
			                                <option value="15" <?=($param['search_type']=="15") ? "selected" : ""?>>015저가문자</option>
			                                <option value="ph" <?=($param['search_type']=="ph") ? "selected" : ""?>>폰문자</option>
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
										foreach($list as $r) { $num++;?>
										<tr>
											<td alt="<?=$r->MSGID?>"><?=number_format($perpage * ($param['page']-1) + $num)?></td>
											<td><?=substr("0".substr($r->PHN, 2),0,3)."****".substr($r->PHN, -4)?></td>
											<td>
												<? if($r->MESSAGE_TYPE=="at") {
									                    echo "알림톡";
									                } else if($r->MESSAGE_TYPE=="ft") {
									                    if(!empty($r->IMAGE_URL)) {
									                        echo "친구톡(이미지)";
									                    } else {
									                        echo "친구톡";
									                    }
									                } else if ($r->SMS_KIND=="S" && !is_null(($r->SMS_KIND))) {
									                    echo "단문 문자(SMS)";
									                } else {
									                    echo "장문 문자(LMS)";
									                }?>
							                </td>
											<? if ($this->member->item("mem_level") >= 100) { ?>
							                <td><?=($r->RESULT_MSG=="Y") ? "<span class='icon_success'>성공</span>".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL)."" : (($r->MESSAGE_TYPE=="pn") ? (($r->CODE=="M103") ? "<span class='fail_note'>수신거부(폰문자)</span>" : "문자:발송대기"): (($r->MESSAGE_TYPE=="S" || $r->MESSAGE_TYPE=="L" || $r->MESSAGE_TYPE=="M") ? "<span class='icon_fail'>발신 실패</span>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL).")" : $this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL)."<span class='icon_fail'>실패</span><span class='fail_note'>(".$this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE).")</span>"))?>
							                </td>
							                <? } else { ?>
							                <td><?=($r->RESULT_MSG=="Y" || $r->RESULT_MSG=="W") ? "<span class='icon_success'>성공</span><br/>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL).")" : (($r->MESSAGE_TYPE=="pn") ? (($r->CODE=="M103") ? "<font color='red'>수신거부(폰문자)<font>" : "문자<br/>발송대기"): (($r->MESSAGE_TYPE=="S" || $r->MESSAGE_TYPE=="L" || $r->MESSAGE_TYPE=="M") ? "발신실패<br/>(".$this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL).")" : $this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE, $r->IMAGE_URL)." ".(($r->RESULT_MSG=="M") ? "" : "실패")."<span class='fail_note'>(".$this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE).")</span>"))?>
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
					
					<div class="footer">
						<div class="cs_info">
							<div class="cs_call">
								<span>1522-7985</span>
							</div>
							<div class="cs_support">
								<a href="#"><span class="icon_cs request">1:1문의</span></a>
								<a href="#"><span class="icon_cs remote">원격지원</span></a>
							</div>
						</div>
	
						<div class="info">
							<ul>
								<li>사업자등록번호 : 364-88-00974</li>
								<li>통신판매업 : 신고번호 2018-창원의창-0272호</li>
								<li>대표이사 : 송종근 </li>
								<li class="paragraph">주소 : 경상남도 창원시 의창구 차룡로 48번길 54(팔용동) 경남창원산학융합원 기업연구관 302호</li>
								<li>대표전화 : 1522-79854</li>
								<li>팩스 : 0505-299-0001</li>
								<li>개인정보처리방침</li>
								<li>원격지원</li>
							</ul>
						</div>
						<div class="copyright">
							Copyright © DHN. All rights reserved.
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



<form id="mainForm" name="mainForm" method="post" action="/biz/sender/history_dt?<?=$key?>">
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
	        <!-- 추가수정 실패목록 있으면 재발송 버튼 hidden 삭제처리 -->
		$( document ).ready(function() {
			$("#nav li.nav10").addClass("current open");
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

        $('.searchBox input').unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                open_page(1);
            }
        });

        function reserve_cancel(mst_id) {
            
			if(confirm('예약 발송을 취소 하시겠습니까?')) {
				var form = document.createElement("form");
				form.setAttribute("method", "post");
				form.setAttribute("action", "/biz/sender/history_dt/reserve_cnacel");
				
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

    	function scroll_prevent() {
    		window.onscroll = function(){
    			var arr = document.getElementsByTagName('textarea');
    			for( var i = 0 ; i < arr.length ; i ++  ){
    				try { arr[i].blur(); }catch(e){}
    			}
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
        	document.location.href='/biz/sender/send/lms_dt/resend<? echo '/'.$rs->mst_id; ?>' ;
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
