<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					발신내역
				</div>
<!-- 타이틀 영역 END -->
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>발신 목록 (전체 <b style="color: red"><?=number_format($total_rows)?></b>개)</h3>
						</div>
						<div class="inner_content">
							<!-- 테이블 상단 검색 영역 -->
						    <div class="search_wrap" id="duration">
							    <ul class="search_period">
								    <li id="today" value="today">오늘</li>
								    <li id="week" value="week">1주일</li>
								    <li id="1month" value="1month">1개월</li>
								    <li id="3month" value="3month">3개월</li>
								    <li id="6month" value="6month">6개월</li>
							    </ul>
						        <input type="hidden" id="set_date" name="set_date">
							    <select style="width: 120px; margin-right: 7px;" id="searchType">
							        <option value="all">검색항목</option>
							        <option value="b.mem_username" <?=($param['search_type']=="b.mem_username") ? "selected" : "selected"?>>업체명</option>
							    </select>
							    <input type="text" style="margin-right: 7px;" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$param['search_for']?>"/>
							    <input type="button" class="btn md color" id="check" value="조회" onclick="open_page(1)"/>
						    </div>
							<!--div class="icon_tooltip">
								<i class="fas fa-question-circle"></i>
								<div class="icon_tooltip_wrap">툴팁 내용이 들어가는 영역입니다.</div>
							</div-->
							<!-- 테이블 상단 검색 영역 END -->
					    
							<div class="table_list">
							<table cellpadding="0" cellspacing="0" border="0">
							    <colgroup>
								        <col width="100px">
								        <col width="100px">
								        <col width="200px">
								        <col width="300px">
								        <col width="200px">
								        <col width="170px">
								        <col width="300px">
								        <col width="130px">
								        <? if ($this->member->item("mem_level") >= 100) { ?>
								        <col width="130px">
								        <? } ?>
							    </colgroup>
								<thead>
									<tr>
										<th>발신번호</th>
										<th>발신시간</th>
										<th>업체명</th>
										<th>메시지 내용</th>
										<th>총발송</th>
										<th>발신타입</th>
										<th>발송내역</th>
										<th>상태</th>
										<? if ($this->member->item("mem_level") >= 100) { ?>
								        <th>발송자</th>
								        <? } ?>
									</tr>
								</thead>
								<tbody>
									<? foreach($list as $r) {
									    $reservedStatus = ''; // 예약관련 정보 html class명이나 비교에 사용. 예약건 : reserve, 아니면 ''
								        $sendedDate = '';   // 발신 시간; 예약이 있을시에는 예약시간.
								        $companyName = '';  // 회사명 또는 사용자 명
								        $friendName = '';   // 플러스 친구 명 앞두에 ()포함.
								        $summaryContent = '';   // 문자 내용 summary
								        
								        $summaryContent = '<p>'.substr($r->mst_content, 0, 100).'...</p>';
								        $summaryContent = str_replace("\n", '</p><p>', $summaryContent);
								        //$summaryContent = preg_replace('\n', '</p><p style="display: block">', $summaryContent);

								        $sendedTypeText1 = '-'; // 1차 발신 종류 명칭
								        $sendedTypeText2 = '-'; // 2차 발신 종류 명칭
								        
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
								        if ($r->mst_reserved_dt!="00000000000000") {
								            
							                $senderDate = $this->funn->format_date($r->mst_reserved_dt, '-', 14);
							                
							                if (strtotime($senderDate) > strtotime(date("Y-m-d H:i:s"))) {
							                    $reservedStatus = 'reserve';
							                    $statusClass1 = 'sending_stat reserve';
							                    $statusText1 = '발송예약';
							                    $statusClass2 = '';
							                    $statusText2 = '';
							                    $reservedFlag = true;
							                } 
								        } else {
								            $senderDate = $r->mst_datetime;
								        }
									       
								        if ($r->spf_company) {
								            $companyName = $r->spf_company;
								            $friendName = '('.$r->spf_friend.')';
								        } else {
								            $companyName = $r->mem_username;
								        }
								        
								        switch($r->mst_type1) {
								            case 'ft' : 
								                $sendedTypeText1 = '친구톡';
								                break;
								            case 'at' : 
								                $sendedTypeText1 = '알림톡';
								                break;
								            case 'fti' : 
								                $sendedTypeText1 = '친구톡(이미지)';
								                break;
								        }
								        
								        if ($this->member->item("mem_level") >= 100) {
    								        switch($r->mst_type2) {
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
								            switch($r->mst_type2) {
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
								        
								        if ($r->mst_ft > 0 || $r->mst_err_ft > 0) {
								            $sucessCount1 = number_format($r->mst_ft);
								            $failCount1 = number_format($r->mst_err_ft);
								        }
								        if ($r->mst_at > 0 || $r->mst_err_at > 0) {
								            $sucessCount1 = number_format($r->mst_at);
								            $failCount1 = number_format($r->mst_err_at);
								        }
								        if ($r->mst_ft_img > 0 || $r->mst_err_ft_img > 0) {
								            $sucessCount1 = number_format($r->mst_ft_img);
								            $failCount1 = number_format($r->mst_err_ft_img);
								        }
								        
								        switch($r->mst_type2) {
								            case 'wa' :
								                if ($r->mst_grs > 0 || $r->mst_err_grs > 0) {
								                    ($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_grs) : $sucessCount2 = number_format($r->mst_wait + $r->mst_grs);
								                    ($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_grs) : $failCount2 = number_format($r->mst_err_grs);
								                }
								                break;
								            case 'was' :
								                if ($r->mst_grs_sms > 0 || $r->mst_err_grs_sms > 0) {
								                    ($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_grs_sms) : $sucessCount2 = number_format($r->mst_wait + $r->mst_grs_sms);
								                    ($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_grs_sms) : $failCount2 = number_format($r->mst_err_grs_sms);
								                }
								                break;
								            case 'wam' :
								                if ($r->mst_grs_mms > 0 || $r->mst_err_grs_mms > 0) {
								                    ($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_grs_mms) : $sucessCount2 = number_format($r->mst_wait + $r->mst_grs_mmsmst_grs_mms);
								                    ($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_grs_mms) : $failCount2 = number_format($r->mst_err_grs_mms);
								                }
								                break;
								            case 'wb' :
								                if ($r->mst_nas > 0 || $r->mst_err_nas > 0) {
								                    ($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_nas) : $sucessCount2 = number_format($r->mst_wait + $r->mst_nas);
								                    ($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_nas) : $failCount2 = number_format($r->mst_err_nas);
								                }
								                break;
								            case 'wbs' :
								                if ($r->mst_nas_sms > 0 || $r->mst_err_nas_sms > 0) {
								                    ($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_nas_sms) : $sucessCount2 = number_format($r->mst_wait + $r->mst_nas_sms);
								                    ($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_nas_sms) : $failCount2 = number_format($r->mst_err_nas_sms);
								                }
								                break;
								            case 'wbm' :
								                if ($r->mst_nas_mms > 0 || $r->mst_err_nas_mms > 0) {
								                    ($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_nas_mms) : $sucessCount2 = number_format($r->mst_wait + $r->mst_nas_mms);
								                    ($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_nas_mms) : $failCount2 = number_format($r->mst_err_nas_mms);
								                }
								                break;
								        }
								        
								        $kakaoTalkCount = $r->mst_ft + $r->mst_err_ft + $r->mst_at + $r->mst_err_at + $r->mst_ft_img + $r->mst_err_ft_img;
								        $messageCount = $r->mst_grs + $r->mst_err_grs + $r->mst_grs_sms + $r->mst_err_grs_sms + $r->mst_grs_mms + $r->mst_err_grs_mms + $r->mst_nas + $r->mst_err_nas + $r->mst_nas_sms + $r->mst_err_nas_sms + $r->mst_nas_mms + $r->mst_err_nas_mms;
								        
								        if ($this->member->item("mem_level") >= 100) {
								            if ($messageCount > 0) {
								                ($kakaoTalkCount > 0) ? $resultDelayCount2 = '('.number_format($r->mst_wait).')' : $resultDelayCount1 = '('.number_format($r->mst_wait).')';
								                }
								            
								        }
								        
								        $totalQty = $r->mst_grs + $r->mst_err_grs + $r->mst_grs_sms + $r->mst_err_grs_sms + $r->mst_grs_mms + $r->mst_err_grs_mms + $r->mst_nas + $r->mst_err_nas + $r->mst_nas_sms + $r->mst_err_nas_sms + $r->mst_nas_mms + $r->mst_err_nas_mms + $r->mst_ft + $r->mst_err_ft + $r->mst_at + $r->mst_err_at + $r->mst_ft_img + $r->mst_err_ft_img;

								        if ($r->mst_qty == $totalQty && $r->mst_qty > 0) {
								            $statusClass1 = 'sending_stat end';
								            $statusText1 = '정산완료';
								            $statusClass2 = '';
								            $statusText2 = '';
								        } else {
								            if ($r->mst_type1 !== '' && $r->mst_type2 !== '') { //1차, 2차 모두 사용
								                if ($kakaoTalkCount > 0) {
								                    $statusClass1 = 'sending_stat_now send';
								                    $statusText1 = '발송완료';
								                    if($r->mst_wait > 0) {
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
								            } else if ($r->mst_type1 !== '' && $r->mst_type2 === '') {  // 1차 알림톡, 친구톡, 친구톡이미지만 사용
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
								            } else if ($r->mst_type1 === '' && $r->mst_type2 !== '') {  // 웹(A)LMS, 웹(A)SMS, 웹(A)MMS, 웹(B)LMS, 웹(B)SMS, 웹(B)MMS만 사용
								                if ($r->mst_qty > 0 && $messageCount == 0) {
								                    if (!$reservedFlag) {
    								                    $statusClass1 = 'sending_stat send';
    								                    $statusText1 = '결과수신';
								                    }
								                } else if ($r->mst_qty > 0 && $messageCount > 0){
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
									?>
									<tr class="<?=$reservedStatus?>" onclick="go_view(<?=$r->mst_id?>)" style="cursor: pointer;">
										<td>
											<?=$r->mst_id?>
										</td>
										<td>
											<?=$senderDate?>
										</td>
										<td class="td_name">
											<?=$companyName.$friendName ?>
										</td>
										<td class="td_msg tl">
											<span class="align-left center-block bs-tooltip content" id="tmpl_cont" name="tmpl_cont" data-placement="right" data-html="true" data-original-title="<?=str_replace("\"", "\'",str_replace("\n", "<br />", $r->mst_content))?>">
							                	<?=$summaryContent ?>
							                </span>
										</td>
										<td class="num_total">
											<?=number_format($r->mst_qty)?>
										</td>
										<td>
											<span class="num_wrap fir"><?=$sendedTypeText1 ?></span>
											<span class="num_wrap sec"><?=$sendedTypeText2 ?></span>
										</td>
										<td>
    							          	<div class="num_wrap">
    								        	<span class="num_sucess"><?=$sucessCount1 ?><span class="stb"><?=$resultDelayCount1 ?></span></span>
    								        	<span class="num_fail"><?=$failCount1 ?></span>
    							          	</div>
    							          	<div class="num_wrap">
    								        	<span class="num_sucess"><?=$sucessCount2 ?><span class="stb"><?=$resultDelayCount2 ?></span></span>
    								        	<span class="num_fail"><?=$failCount2 ?></span>
    							          	</div>											
										</td>
										<td>
											<span class="<?=$statusClass1 ?>"><?=$statusText1 ?></span>
											<span class="<?=$statusClass2 ?>"><?=$statusText2 ?></span>
										</td>
										<? if ($this->member->item("mem_level") >= 100) { ?>
								        <td>
											<?=$r->send_mem_user_id?>
										</td>
								        <? } ?>
									</tr>
																	        
									<?}?>
								</tbody>
							</table>
							</div>

							<div class="align-center mt30"><?=$page_html?></div>
						</div>
					</div><!-- form_section End -->
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
				</div><!-- #mArticle end -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br><br>
                            <button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">확인
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $("#nav li.nav10").addClass("current open");

        $('.searchBox input').unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                open_page(1);
            }
        });

//         window.onload = function () {
            var set_date = '<?=($param['duration']) ? $param['duration'] : 'week'?>';
             $("#"+set_date).addClass('active');
//         };

        function open_page(page) {
        	
            var duration = $('#duration ul li.active').attr('value') || 'week';
            var type = $('#searchType').val() || 'all';
            var searchFor = $('#searchStr').val() || '';

            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "get");
            form.setAttribute("action", "/biz/sender/history_dt");
				var cfrsField = document.createElement("input");
				cfrsField.setAttribute("type", "hidden");
				cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
				cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
            form.appendChild(cfrsField);
            var pageField = document.createElement("input");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);
            form.appendChild(pageField);
            var typeField = document.createElement("input");
            typeField.setAttribute("type", "hidden");
            typeField.setAttribute("name", "search_type");
            typeField.setAttribute("value", type);
            form.appendChild(typeField);
            var searchForField = document.createElement("input");
            searchForField.setAttribute("type", "hidden");
            searchForField.setAttribute("name", "search_for");
            searchForField.setAttribute("value", searchFor);
            form.appendChild(searchForField);
            var durationField = document.createElement("input");
            durationField.setAttribute("type", "hidden");
            durationField.setAttribute("name", "duration");
            durationField.setAttribute("value", duration);
            form.appendChild(durationField);
            form.submit();
        }


        function go_view(mst_id) {
            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/sender/history_dt/view/" + mst_id);

            var cfrsField = document.createElement("input");
			cfrsField.setAttribute("type", "hidden");
			cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
			cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
            form.appendChild(cfrsField);
/*
            var mst_id = document.createElement("input");
            mst_id.setAttribute("type", "hidden");
            mst_id.setAttribute("name", "mst_id");
            mst_id.setAttribute("value", mst_id);
            form.appendChild(mst_id);
*/
            form.submit();
        }
        
        $('#duration ul li').click(function() {
            $('#duration ul li').removeClass('active');
            $(this).addClass('active');
            // console.log($('#duration a.active').attr('value'));
            open_page(1);
        });

        function page(page) {
            var duration = $('#duration ul li .active').attr('value');
            var type = $('#searchType').val();
            var searchFor = $('#searchStr').val();

            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/sender/history_dt");
            var pageField = document.createElement("input");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);
            form.appendChild(pageField);
            var typeField = document.createElement("input");
            typeField.setAttribute("type", "hidden");
            typeField.setAttribute("name", "search_type");
            typeField.setAttribute("value", type);
            form.appendChild(typeField);
            var searchForField = document.createElement("input");
            searchForField.setAttribute("type", "hidden");
            searchForField.setAttribute("name", "search_for");
            searchForField.setAttribute("value", searchFor);
            form.appendChild(searchForField);
            var durationField = document.createElement("input");
            durationField.setAttribute("type", "hidden");
            durationField.setAttribute("name", "duration");
            durationField.setAttribute("value", duration);
            form.appendChild(durationField);
            form.submit();
        }

    </script>
