<div class="tit_wrap">
	발송내역
</div>
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>발송내역 (전체 <b style="color: red"><?=number_format($total_rows)?></b>건)</h3>
		</div>
		<div class="btn_view_list">
			<a href="/dhnbiz/sender/history" class="list_on"><i class="xi-list"></i> 전체 발송목록</a>
			<a href="/dhnbiz/sender/history/monthly" class="list_off"><i class="xi-calendar-list"></i> 월별 발송건수</a>
		</div>
		<div class="white_box">
			<!-- 테이블 상단 검색 영역 -->
			<div class="search_wrap" id="duration">
				<ul class="search_period">
					<li id="today" value="today">오늘</li>
					<li id="week" value="week">1주일</li>
					<li id="1month" value="1month">1개월</li>
					<li id="3month" value="3month">3개월</li>
					<li id="6month" value="6month">6개월</li>
                    <li id="custom" value="custom">기간선택</li>
				</ul>
                <span class="dateBox" style="margin-right: 10px; <?if ($param['duration'] != 'custom') {echo 'display:none';}?>">
					<input type="text" class="datepicker" name="startDate" id="startDate" value="<?=$param['startDate'];?>" readonly="readonly"> ~
					<input type="text" class="datepicker" name="endDate" id="endDate" value="<?=$param['endDate'];?>" readonly="readonly">
			    </span>
				<input type="hidden" id="set_date" name="set_date">
				<select style="margin-right: 7px;" id="searchType">
					<option value="all">검색항목</option>
					<option value="b.mem_username" <?=($param['search_type']=="b.mem_username") ? "selected" : "selected"?>>업체명</option>
                    <option value="a.mst_content" <?=($param['search_type']=="a.mst_content") ? "selected" : ""?>>메시지내용</option>
				</select>
				<input type="text" style="margin-right: 7px;" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$param['search_for']?>"/>
                <select style="width: 100px; margin-right: 7px;" id="platformType">
			    	<option value="all" <?=($param['platform_type']=="all" or empty($param['platform_type'])) ? "selected" : ""?>>전체발송</option>
			    	<option value="S" <?=($param['platform_type']=="S") ? "selected" : ""?>>지니발송</option>
			    	<option value="A" <?=($param['platform_type']=="A") ? "selected" : ""?>>API발송</option>
			    </select>
				<input type="button" class="btn md color" style="cursor:pointer;" id="check" value="조회" onClick="open_page(1);">
			</div>
			<!-- 테이블 상단 검색 영역 END -->
		</div>
		<div class="white_box mg_t20">
			<div class="table_list">
				<table cellpadding="0" cellspacing="0" border="0">
					<colgroup>
							<col width="80px">
							<col width="120px">
							<col width="150px">
							<col width="*">
							<col width="140px">
							<col width="170px">
							<col width="270px">
							<col width="130px">
							<? if ($this->member->item("mem_level") >= 100) { ?>
							<col width="100px">
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

							//$summaryContent = '<p>'. substr($r->mst_content, 0, 100) .'...</p>';
							//$summaryContent = '<p>'. mb_substr($r->mst_content, 0, 60, "UTF-8") .'...</p>';
                            if($r->mst_kind =="rc"){
                                $summaryContent = '<p>'. $this->funn->StringCut($r->msr_content, 60, "..") .'</p>';
                            }else{
                                $summaryContent = '<p>'. $this->funn->StringCut($r->mst_content, 60, "..") .'</p>';
                            }
							$summaryContent = str_replace("\n", '</p><p>', $summaryContent);
							//$summaryContent = preg_replace('\n', '</p><p style="display: block">', $summaryContent);

							$sendedTypeText1 = '-'; // 1차 발신 종류 명칭
							$sendedTypeText2 = '-'; // 2차 발신 종류 명칭
                            $sendedTypeText3 = '-'; // 3차 발신 종류 명칭

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
								case 'ai' :
								    $sendedTypeText1 = '알림톡(이미지)';
								    break;
								case 'fti' :
									$sendedTypeText1 = '친구톡(이미지)';
									break;
								case 'ftw' :
									$sendedTypeText1 = '친구톡(와이드)';
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
									    break;
									case 'rcs' :
									    ($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS SMS' : $sendedTypeText2 = 'RCS SMS';
									    break;
                                    case 'rcm' :
									    ($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS MMS' : $sendedTypeText2 = 'RCS MMS';
									    break;
                                    case 'rct' :
										($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS(템플릿)' : $sendedTypeText2 = 'RCS(템플릿)';
										break;
                                    case 'wp1' :
                                    case 'wp2' :
										($sendedTypeText1 === '-') ? $sendedTypeText1 = '폰문자 LMS' : $sendedTypeText2 = '폰문자 LMS';
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
									case 'at' :
										($sendedTypeText1 === '-') ? $sendedTypeText1 = '알림톡' : $sendedTypeText2 = '알림톡'; //2021-01-05 2차 알림톡 추가
										break;
									case 'ai' :
									    ($sendedTypeText1 === '-') ? $sendedTypeText1 = '알림톡(이미지)' : $sendedTypeText2 = '알림톡(이미지)'; //2021-01-05 2차 알림톡 추가
									    break;
								}

                                switch($r->mst_type3) {

    								case 'wc' :
                                        if($r->mst_kind =="rc"){
        									$sendedTypeText2 = '웹(C) LMS';
                                        }else{
                                            $sendedTypeText3 = '웹(C) LMS';
                                        }
    									break;
    								case 'wcs' :
                                        if($r->mst_kind =="rc"){
                                            $sendedTypeText2 = '웹(C) SMS';
                                        }else{
                                            $sendedTypeText3 = '웹(C) SMS';
                                        }
    									break;
    								case 'wcm' :
                                        if($r->mst_kind =="rc"){
                                            $sendedTypeText2 = '웹(C) MMS';
                                        }else{
                                            $sendedTypeText3 = '웹(C) MMS';
                                        }
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
										($sendedTypeText1 === '-') ? $sendedTypeText1 = '문자(MMS)' : $sendedTypeText2 = '문자(LMS)';
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
									    break;
									case 'rcs' :
									    ($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS SMS' : $sendedTypeText2 = 'RCS SMS';
									    break;
                                    case 'rcm' :
									    ($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS MMS' : $sendedTypeText2 = 'RCS MMS';
									    break;
                                    case 'rct' :
										($sendedTypeText1 === '-') ? $sendedTypeText1 = 'RCS(템플릿)' : $sendedTypeText2 = 'RCS(템플릿)';
										break;
                                    case 'wp1' :
                                    case 'wp2' :
										($sendedTypeText1 === '-') ? $sendedTypeText1 = '폰문자 LMS' : $sendedTypeText2 = '폰문자 LMS';
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
									case 'at' :
										($sendedTypeText1 === '-') ? $sendedTypeText1 = '알림톡' : $sendedTypeText2 = '알림톡'; //2021-01-05 2차 알림톡 추가
										break;
									case 'ai':
									    ($sendedTypeText1 === '-') ? $sendedTypeText1 = '알림톡(이미지)' : $sendedTypeText2 = '알림톡(이미지)'; //2021-01-05 2차 알림톡 추가
									    break;

								}
                                switch($r->mst_type3) {

                                    case 'wc' :
                                        $sendedTypeText2 = '문자(LMS)';
                                        break;
                                    case 'wcs' :
                                        $sendedTypeText2 = '문자(SMS)';
                                        break;
                                    case 'wcm' :
                                        $sendedTypeText2 = '문자(MMS)';
                                        break;
                                    }
							}

							if ($r->mst_ft > 0 || $r->mst_err_ft > 0) {
								$sucessCount1 = number_format($r->mst_ft);
								$failCount1 = number_format($r->mst_err_ft);
							}
							if($r->mst_type1 == "at" || $r->mst_type1 == 'ai'){ //2021-01-05 2차 알림톡 추가
								if ($r->mst_at > 0 || $r->mst_err_at > 0) {
									$sucessCount1 = number_format($r->mst_at);
									$failCount1 = number_format($r->mst_err_at);
								}
							}
							if ($r->mst_ft_img > 0 || $r->mst_err_ft_img > 0) {
								$sucessCount1 = number_format($r->mst_ft_img);
								$failCount1 = number_format($r->mst_err_ft_img);
							}

							switch($r->mst_type2) {
								case 'wa' :
									if ($r->mst_grs > 0 || $r->mst_err_grs > 0 || $r->mst_wait > 0) {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_grs);
											$failCount2 = number_format($r->mst_err_grs);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_grs) : $sucessCount2 = number_format($r->mst_wait + $r->mst_grs);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_grs) : $failCount2 = number_format($r->mst_err_grs);
										}
									}
									break;
								case 'was' :
									if ($r->mst_grs_sms > 0 || $r->mst_err_grs_sms > 0 || $r->mst_wait > 0) {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_grs_sms);
											$failCount2 = number_format($r->mst_err_grs_sms);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_grs_sms) : $sucessCount2 = number_format($r->mst_wait + $r->mst_grs_sms);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_grs_sms) : $failCount2 = number_format($r->mst_err_grs_sms);
										}
									}
									break;
								case 'wam' :
									if ($r->mst_grs > 0 || $r->mst_err_grs > 0 || $r->mst_wait > 0) {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_grs);
											$failCount2 = number_format($r->mst_err_grs);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_grs) : $sucessCount2 = number_format($r->mst_wait + $r->mst_grs);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_grs) : $failCount2 = number_format($r->mst_err_grs);
										}
									} else {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_grs);
											$failCount2 = number_format($r->mst_err_grs);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_grs) : $sucessCount2 = number_format($r->mst_wait + $r->mst_grs);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_grs) : $failCount2 = number_format($r->mst_err_grs);
										}
									}
									break;
								case 'wb' :
									if ($r->mst_nas > 0 || $r->mst_err_nas > 0 || $r->mst_wait > 0) {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_nas);
											$failCount2 = number_format($r->mst_err_nas);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_nas) : $sucessCount2 = number_format($r->mst_wait + $r->mst_nas);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_nas) : $failCount2 = number_format($r->mst_err_nas);
										}
									}
									break;
								case 'wbs' :
									if ($r->mst_nas_sms > 0 || $r->mst_err_nas_sms > 0 || $r->mst_wait > 0) {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_nas_sms);
											$failCount2 = number_format($r->mst_err_nas_sms);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_nas_sms) : $sucessCount2 = number_format($r->mst_wait + $r->mst_nas_sms);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_nas_sms) : $failCount2 = number_format($r->mst_err_nas_sms);
										}
									}
									break;
								case 'wbm' :
									if ($r->mst_nas > 0 || $r->mst_err_nas > 0 || $r->mst_wait > 0) {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_nas);
											$failCount2 = number_format($r->mst_err_nas);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_nas) : $sucessCount2 = number_format($r->mst_wait + $r->mst_nas);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_nas) : $failCount2 = number_format($r->mst_err_nas);
										}
									} else {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_nas);
											$failCount2 = number_format($r->mst_err_nas);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_nas) : $sucessCount2 = number_format($r->mst_wait + $r->mst_nas);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_nas) : $failCount2 = number_format($r->mst_err_nas);
										}
									}
									break;
								case 'wc' :
									if ($r->mst_smt > 0 || $r->mst_err_smt > 0 || $r->mst_wait > 0) {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_smt);
											$failCount2 = number_format($r->mst_err_smt);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_smt) : $sucessCount2 = number_format($r->mst_wait + $r->mst_smt);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_smt) : $failCount2 = number_format($r->mst_err_smt);
										}
									}
									break;
                                case 'wp1' :
                                case 'wp2' :
    								if ($rs->mst_imc > 0) {
    									if ($rs->mst_type1 != "") {
    										$sucessCount2 = number_format($rs->mst_imc);
    										$failCount2 = 0;
    									} else {
    										($sucessCount1 === '-') ? $sucessCount1 = number_format($rs->mst_imc) : $sucessCount2 = number_format($rs->mst_imc);
    										($failCount1 === '-') ? $failCount1 = 0 : 0;
    									}
    								}
    								break;
								case 'wcs' :
									if ($r->mst_smt > 0 || $r->mst_err_smt > 0 || $r->mst_wait > 0) {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_smt);
											$failCount2 = number_format($r->mst_err_smt);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_smt) : $sucessCount2 = number_format($r->mst_wait + $r->mst_smt);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_smt) : $failCount2 = number_format($r->mst_err_smt);
										}
									}
									break;
								case 'wcm' :
									if ($r->mst_smt > 0 || $r->mst_err_smt > 0 || $r->mst_wait > 0) {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_smt);
											$failCount2 = number_format($r->mst_err_smt);
										} else {
											($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_smt) : $sucessCount2 = number_format($r->mst_wait + $r->mst_smt);
											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_smt) : $failCount2 = number_format($r->mst_err_smt);
										}
									}
									break;
                                case 'rc' :
                                case 'rcs' :
                                case 'rcm' :
                                case 'rct' :
									if ($r->mst_rcs > 0 || $r->mst_smt > 0 || $r->mst_err_rcs > 0 || $r->mst_err_smt > 0 || $r->mst_wait > 0) {
										if ($r->mst_type1 != "") {
											$sucessCount2 = number_format($r->mst_wait + $r->mst_rcs);
											$failCount2 = number_format($r->mst_err_rcs);
										} else {
                                            if($r->mst_type3 != ""){
                                                $sucessCount1 = number_format($r->mst_wait + $r->mst_rcs);
    											$failCount1 = number_format($r->mst_err_rcs);
                                                $sucessCount2 = number_format($r->mst_smt);
                                                $failCount2 = number_format($r->mst_err_smt);
                                            }else{
                                                ($sucessCount1 === '-') ? $sucessCount1 = number_format($r->mst_wait + $r->mst_rcs) : $sucessCount2 = number_format($r->mst_wait + $r->mst_rcs);
    											($failCount1 === '-') ? $failCount1 = number_format($r->mst_err_rcs) : $failCount2 = number_format($r->mst_err_rcs);
                                            }

										}
									}
									break;
								case 'at' : //2021-01-05 2차 알림톡 추가
									if ($r->mst_at > 0 || $r->mst_err_at > 0) {
										$sucessCount2 = number_format($r->mst_at);
										$failCount2 = number_format($r->mst_err_at);
									}
									break;
								case 'ai' :
								    if ($r->mst_at > 0 || $r->mst_err_at > 0) {
								        $sucessCount2 = number_format($r->mst_at);
								        $failCount2 = number_format($r->mst_err_at);
								    }
								    break;
							}
                            if($r->mst_kind != "rc"){
                                switch($r->mst_type3) {

    					            case 'wc' :
    					                if ($r->mst_smt > 0 || $r->mst_err_smt > 0) {
    					                    $sucessCount3 = number_format($r->mst_wait + $r->mst_smt);
    					                    $failCount3 = number_format($r->mst_err_smt);
    					                }
    					                break;
    					            case 'wcs' :
    					                if ($r->mst_smt > 0 || $r->mst_err_smt > 0) {
    					                    $sucessCount3 = number_format($r->mst_wait + $r->mst_smt);
    					                    $failCount3 = number_format($r->mst_err_smt);
    					                }
    					                break;
    					            case 'wcm' :
    					                if ($r->mst_smt > 0 || $r->mst_err_smt > 0) {
    					                    $sucessCount3 = number_format($r->mst_wait + $r->mst_smt);
    					                    $failCount3 = number_format($r->mst_err_smt);
    					                }
    					                break;
    					        }
                            }


							$kakaoTalkCount = $r->mst_ft + $r->mst_err_ft + $r->mst_at + $r->mst_err_at + $r->mst_ft_img + $r->mst_err_ft_img;
							$messageCount = $r->mst_grs + $r->mst_err_grs + $r->mst_grs_sms + $r->mst_err_grs_sms + $r->mst_grs_mms + $r->mst_err_grs_mms + $r->mst_nas + $r->mst_err_nas + $r->mst_nas_sms + $r->mst_err_nas_sms + $r->mst_nas_mms + $r->mst_err_nas_mms + $r->mst_smt + $r->mst_err_smt+ $r->mst_rcs + $r->mst_err_rcs;

							if ($this->member->item("mem_level") >= 100) {
								if ($messageCount > 0) {
									if ($r->mst_type1 != "") {
										$resultDelayCount2 = '('.number_format($r->mst_wait).')';
									} else {
										($kakaoTalkCount > 0) ? $resultDelayCount2 = '('.number_format($r->mst_wait).')' : $resultDelayCount1 = '('.number_format($r->mst_wait).')';
									}
								}
                                if ($r->mst_type3) {
				                    if($messageCount > 0&&$r->mst_kind != "rc") {
				                        $resultDelayCount1 = '';
				                        $resultDelayCount2 = '';
				                        $resultDelayCount3 = '('.number_format($r->mst_wait).')';
				                    }
				                }
							}

							$totalQty = $r->mst_grs + $r->mst_err_grs + $r->mst_grs_sms + $r->mst_err_grs_sms + $r->mst_grs_mms + $r->mst_err_grs_mms + $r->mst_nas + $r->mst_err_nas + $r->mst_nas_sms + $r->mst_err_nas_sms + $r->mst_nas_mms + $r->mst_err_nas_mms + $r->mst_ft + $r->mst_err_ft + $r->mst_at + $r->mst_err_at + $r->mst_ft_img + $r->mst_err_ft_img + $r->mst_smt + $r->mst_err_smt + $r->mst_rcs + $r->mst_err_rcs;

							if ($r->mst_qty == $totalQty && $r->mst_qty > 0) {
								$statusClass1 = 'sending_stat end';
								$statusText1 = '정산완료';
								$statusClass2 = '';
								$statusText2 = '';
                                $statusClass3 = '';
								$statusText3 = '';
							} else {

								if ($r->mst_type1 !== '' && $r->mst_type2 !== '') { //1차, 2차 모두 사용
									if ($kakaoTalkCount > 0) {
										$statusClass1 = 'sending_stat_now send';
										$statusText1 = '발송완료';
										if($r->mst_wait > 0) {
                                            if($r->mst_type3 !== ''){
                                                if ($messageCount > 0) {
    												$statusClass3 = 'sending_stat_now send';
    												$statusText3 = '발송완료';
                                                    $statusClass2 = 'sending_stat_now send';
    												$statusText2 = '발송완료';
    											} else {
                                                    $statusClass2 = 'sending_stat_now send';
    												$statusText2 = '발송완료';
    												$statusClass3 = 'sending_stat_now request';
    												$statusText3 = '결과수신';
    											}
                                            }else{
                                                if ($messageCount > 0) {
    												$statusClass2 = 'sending_stat_now send';
    												$statusText2 = '발송완료';
    											} else {
    												$statusClass2 = 'sending_stat_now request';
    												$statusText2 = '결과수신';
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
								} else if ($r->mst_type1 !== '' && $r->mst_type2 === '') {  // 1차 알림톡, 친구톡, 친구톡이미지만 사용
									if($kakaoTalkCount > 0) {
										$statusClass1 = 'sending_stat send';
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
										$statusClass1 = 'sending_stat send';
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
								<? if($r->link_type == "smart" or $r->link_type == "coupon" or $r->link_type == "editor" or $r->link_type == "smartEditor" or $r->link_type == "home"){ ?>
								<p class="send_linktype">
									<? if($r->link_type == "smart"){ ?>
									<span class="send_smart">스마트전단</span>
									<? }else if($r->link_type == "coupon"){ ?>
									<span class="send_coupon">스마트쿠폰</span>
									<? }else if($r->link_type == "editor"){ ?>
									<span class="send_editor">에디터사용</span>
									<? }else if($r->link_type == "smartEditor"){ ?>
									<span class="send_smart">스마트전단</span>
									<span class="send_editor">에디터사용</span>
                                    <? }else if($r->link_type == "home"){ ?>
                                        <? if(!empty($r->psd_url)){ ?>
                                            <span class="send_smart">스마트전단</span>
                                        <? } ?>
                                        <? if(!empty($r->url)){ ?>
                                            <span class="send_editor">에디터사용</span>
                                        <? } ?>
                                        <? if(!empty($r->pcd_code)){ ?>
                                            <span class="send_coupon">스마트쿠폰</span>
                                        <? } ?>
                                        <? if(!empty($r->order_url)){ ?>
                                            <span class="send_coupon">주문하기</span>
                                        <? } ?>
                                    <? } ?>
									<? if($r->stmall_yn == "Y"){ //스마트전단 주문하기 사용의 경우 ?>
									<span class="material-icons send_order">shopping_cart</span>
									<? } ?>
								</p>
								<? } ?>
								<div class="gn_tooltip"><?=$summaryContent ?>
								<span class="gn_tooltiptext">
									<?=(!empty($r->mst_content))? str_replace("\"", "\'",str_replace("\n", "<br />", $r->mst_content)) : str_replace("\"", "\'",str_replace("\n", "<br />", $r->msr_content))?>
								</span>
								</div>
							</td>
							<td class="num_total">
								<?=number_format($r->mst_qty)?>
							</td>
							<td>
								<span class="num_wrap fir"><?=$sendedTypeText1 ?></span>
								<span class="num_wrap sec"><?=$sendedTypeText2 ?></span>
                                <? if(!empty($r->mst_type3)&&$r->mst_kind!="rc") { ?>
								<span class="num_wrap thi"><?=$sendedTypeText3 ?></span>
								<? } ?>
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
                                <? if(!empty($r->mst_type3)&&$r->mst_kind!="rc") { ?>
					          	<div class="num_wrap">
						        	<span class="num_sucess"><?=$sucessCount3 ?><span class="stb"><?=$resultDelayCount3 ?></span></span>
						        	<span class="num_fail"><?=$failCount3 ?></span>
					          	</div>
					          	<? } ?>
							</td>
							<td>
								<span class="<?=$statusClass1 ?>"><?=$statusText1 ?></span>
								<span class="<?=$statusClass2 ?>"><?=$statusText2 ?></span>
                                <? if(!empty($r->mst_type3)&&$r->mst_kind!="rc") { ?>
								<span class="<?=$statusClass3 ?>"><?=$statusText3 ?></span>
								<? } ?>
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
			<div class="page_cen"><?=$page_html?></div>
		</div>
	</div><!-- form_section End -->
</div><!-- #mArticle end -->
<script type="text/javascript">
	$("#nav li.nav10").addClass("current open");

	$('#searchStr').unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			open_page(1);
		}
	});

	 //window.onload = function () {
		var set_date = '<?=($param['duration']) ? $param['duration'] : 'week'?>';
		 $("#"+set_date).addClass('active');
	 //};

     //2021-10-14 커스텀 날짜 추가
     $('#startDate').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            language: "kr",
            autoclose: true,
            startDate: '-6m',
            endDate: '-1d'
        }).on('changeDate', function (selected) {
            var startDate = new Date(selected.date.valueOf());
            $('#endDate').datepicker('setStartDate', startDate);
        });

        var start = $("#startDate").val();

        $('#endDate').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            language: "kr",
            autoclose: true,
            startDate: start,
            endDate: '+1m'
        }).on('changeDate', function (selected) {
            var endDate = new Date(selected.date.valueOf());
            $('#startDate').datepicker('setEndDate', endDate);
        });

        var end = $("#endDate").val();
        //2021-10-14 커스텀 날짜 추가 끝

	function open_page(page) {
		var duration = $('#duration ul li.active').attr('value') || 'week';
		var type = $('#searchType').val() || 'all';
		var searchFor = $('#searchStr').val() || '';

        // 2021-10-14 API, 사이트발송 구분
        var platform = $('#platformType').val();

        var start = $("#startDate").val();
        var end = $("#endDate").val();

		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "get");
		form.setAttribute("action", "/dhnbiz/sender/history");
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

        // 2021-02-18 API, 사이트발송 구분
        var platformField = document.createElement("input");
        platformField.setAttribute("type", "hidden");
        platformField.setAttribute("name", "platform_type");
        platformField.setAttribute("value", platform);
        form.appendChild(platformField);

		var durationField = document.createElement("input");
		durationField.setAttribute("type", "hidden");
		durationField.setAttribute("name", "duration");
		durationField.setAttribute("value", duration);
		form.appendChild(durationField);

        //2021-10-14 커스텀 날짜 기능 추가
        var startDateField = document.createElement("input");
        startDateField.setAttribute("type", "hidden");
        startDateField.setAttribute("name", "startDate");
        startDateField.setAttribute("value", start);
        form.appendChild(startDateField);

        var endDateField = document.createElement("input");
        endDateField.setAttribute("type", "hidden");
        endDateField.setAttribute("name", "endDate");
        endDateField.setAttribute("value", end);
        form.appendChild(endDateField);

		form.submit();
	}

	//발송내역 상세화면 이동
	function go_view(mst_id) {
		/*
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/dhnbiz/sender/history/view/" + mst_id);

		var cfrsField = document.createElement("input");
		cfrsField.setAttribute("type", "hidden");
		cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		form.appendChild(cfrsField);

		//var mst_id = document.createElement("input");
		//mst_id.setAttribute("type", "hidden");
		//mst_id.setAttribute("name", "mst_id");
		//mst_id.setAttribute("value", mst_id);
		//form.appendChild(mst_id);

		form.submit();
		*/
		location.href = "/dhnbiz/sender/history/view/" + mst_id;
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
		form.setAttribute("action", "/sender/history");
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
