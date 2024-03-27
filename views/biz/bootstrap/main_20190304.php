<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					레이아웃
				</div>
<!-- 타이틀 영역 END -->
<!-- 탭메뉴 영역 
				<div class="tab_menu">
					<ul>
						<li class="on"><a href="#">알림톡</a></li>
						<li><a href="#">친구톡</a></li>
						<li><a href="#">문자</a></li>
					</ul>
				</div>
<!-- 탭메뉴 영역 END-->
<!-- 본문 영역 -->
				<div id="mArticle">
					<div class="card_list">
						<div class="card_item">
								<div class="card-body">
									<i class="zmdi zmdi-plus zmdi-hc-fw zmdi-hc-lg icon_more fr"></i>
									<h4 class="card_tit"><i class="zmdi zmdi-calendar-note zmdi-hc-fw"></i>예약정보</h4>
									<em class="card_info">99</em>
									<em class="card_info acount">994,980</em>										
								</div>
						</div>
						<div class="card_item">
								<div class="card-body">
									<a href="#" class="more_info">상세정보</a>
									<h4 class="card_tit"><i class="zmdi zmdi-mail-send zmdi-hc-lg"></i> 3월 누적발송현황</h4>
									<em class="card_info">1,980,000</em>
									<em class="card_info acount"><label>총 계약건수</label>1,500,000</em>										
								</div>
						</div>
						<div class="card_item">
								<div class="card-body">
									<h4 class="card_tit">TODAY 발송건수</h4>
									<em class="card_info">99건</em>										
								</div>
						</div>
						<div class="card_item">
								<div class="card-body">
									<h4 class="card_tit">예약건수</h4>
									<em class="card_info">99건</em>										
								</div>
						</div>
						
						<div class="card_item">
								<div class="card-body">
									<h4 class="card_tit"><i class="zmdi zmdi-accounts-list zmdi-hc-lg"></i> 고객현황</h4>
									<em class="card_info">99건</em>										
								</div>
						</div>
						<div class="card_item">
								<div class="card-head">
									<i class="zmdi zmdi-plus zmdi-hc-fw zmdi-hc-lg fr" style="margin-top: 1em;"></i>
									<h3><i class="zmdi zmdi-accounts zmdi-hc-lg"></i> 고객현황</h3>
								</div>
								<div class="card-body">
									내용
								</div>
						</div>
						<div class="card_item">
								<div class="card-head">
									<i class="zmdi zmdi-plus zmdi-hc-fw zmdi-hc-lg fr" style="margin-top: 1em;"></i>
									<h3><i class="zmdi zmdi-home zmdi-hc-fw"></i> 공지사항</h3>
								</div>
								<div class="card-body">
									<ul>
										<a href="#">
										<li class="dashboard_notice">
											<div class="tit"><p>[일반]상세페 상세 페이지 진 단상세페 상세상세페 상세페 이지 진단  </p></div>
											<div class="date">2019.03.04</div>
										</li>
										</a>
										<a href="#">
										<li class="dashboard_notice">
											<div class="tit"><p>[일반]상세페이지 진단 업종 확대 안내</p></div>
											<div class="date">2019.03.04</div>
										</li>
										</a>
										<a href="#">
										<li class="dashboard_notice">
											<div class="tit"><p>[일반]상세페이지 진단 업종 확대 안내</p></div>
											<div class="date">2019.03.04</div>
										</li>
										</a>
										<a href="#">
										<li class="dashboard_notice">
											<div class="tit"><p>[일반]상세페 상세 페이지 진 단상세페 상세상세페 상세페 이지 진단  </p></div>
											<div class="date">2019.03.04</div>
										</li>
										</a>
										<a href="#">
										<li class="dashboard_notice">
											<div class="tit"><p>[일반]상세페 상세 페이지 진 단상세페 상세상세페 상세페 이지 진단  </p></div>
											<div class="date">2019.03.04</div>
										</li>
										</a>
									</ul>
								</div>
						</div>
					</div>
					<div class="form_section">
						<div class="inner_tit">
							<h3>발신 목록 (전체 <b style="color: red"><?=number_format($total_rows)?></b>개)</h3>
						</div>
						<div class="inner_content">
							
						</div>
					</div>
					


					<!--div class="table_stat">
						<div class="table_stat_header">
							<table cellspacing="0" cellpadding="0" border="0">
								<colgroup>
									<col width="80px">
									<col width="100px">
									<col width="80px">
									<col width="80px">
									<col width="80px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="50px">
									<col width="50px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="50px">
									<col width="50px">		
								</colgroup>
								<thead>
									<tr>
										<th rowspan="2">발송일</th>
										<th rowspan="2">업체명</th>
										<th rowspan="2">요청</th>
										<th rowspan="2">성공</th>
										<th rowspan="2">실패</th>
										
										<th colspan="2">알림톡</th>
										<th colspan="2">친구톡</th>
										<th colspan="2">친구톡(이미지)</th>
										<th colspan="2">WEB(A)<em class="sms">SMS<em></th>
										<th colspan="2">WEB(A)<em class="lms">LMS<em></th>
										<th colspan="2">WEB(A)<em class="mms">MMS<em></th>
										<th colspan="2">WEB(B)<p>SMS<p></th>
										<th colspan="2">WEB(B)<p>LMS<p></th>
										<th colspan="2">WEB(B)<p>MMS<p></th>
									</tr>
									<tr>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
										<th class="tit_succ">성공</th>
										<th class="tit_fail">실패</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="table_stat_body">
							<table cellspacing="0" cellpadding="0" border="0">
								<colgroup>
									<col width="80px">
									<col width="100px">
									<col width="80px">
									<col width="80px">
									<col width="80px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="50px">
									<col width="50px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="70px">
									<col width="50px">
									<col width="50px">		
								</colgroup>
								<tbody>
									<tr>
										<td>2019.03.01</td>
										<td class="name">365할인마트_창원_구암대동점</td>
										<td>23,118,973</td>
										<td>23,118,973</td>
										<td>23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">0</td>
										<td class="succ">3453455</td>
										<td class="fail">0</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">0</td>
									</tr>
									<tr>
										<td class="date">2019.03.01</td>
										<td class="name">365할인마트_창원_구암대동점</td>
										<td class="total">12341234</td>
										<td class="total">1234</td>
										<td class="total">1234123</td>
										<td class="succ">122334</td>
										<td class="fail">1312244</td>
										<td class="succ">1212313</td>
										<td class="fail">1312343</td>
										<td class="succ">344234</td>
										<td class="fail">3453453</td>
										<td class="succ">345345</td>
										<td class="fail">34534534</td>
										<td class="succ">345345</td>
										<td class="fail">345345</td>
										<td class="succ">0</td>
										<td class="fail">34534</td>
										<td class="succ">3453455</td>
										<td class="fail">0</td>
										<td class="succ">345345</td>
										<td class="fail">0</td>
										<td class="succ">34534</td>
										<td class="fail">0</td>
									</tr>
									<tr>
										<td>2019.03.01</td>
										<td class="name">365할인마트_창원_구암대동점</td>
										<td>12341234</td>
										<td>1234</td>
										<td>1234123</td>
										<td class="succ">122334</td>
										<td class="fail">1312244</td>
										<td class="succ">1212313</td>
										<td class="fail">1312343</td>
										<td class="succ">344234</td>
										<td class="fail">3453453</td>
										<td class="succ">345345</td>
										<td class="fail">34534534</td>
										<td class="succ">345345</td>
										<td class="fail">345345</td>
										<td class="succ">0</td>
										<td class="fail">34534</td>
										<td class="succ">3453455</td>
										<td class="fail">0</td>
										<td class="succ">345345</td>
										<td class="fail">0</td>
										<td class="succ">34534</td>
										<td class="fail">0</td>
									</tr>
									<tr>
										<td>2019.03.01</td>
										<td class="name">365할인마트_창원_구암대동점</td>
										<td>12341234</td>
										<td>1234</td>
										<td>1234123</td>
										<td class="succ">122334</td>
										<td class="fail">1312244</td>
										<td class="succ">1212313</td>
										<td class="fail">1312343</td>
										<td class="succ">344234</td>
										<td class="fail">3453453</td>
										<td class="succ">345345</td>
										<td class="fail">34534534</td>
										<td class="succ">345345</td>
										<td class="fail">345345</td>
										<td class="succ">0</td>
										<td class="fail">34534</td>
										<td class="succ">3453455</td>
										<td class="fail">0</td>
										<td class="succ">345345</td>
										<td class="fail">0</td>
										<td class="succ">34534</td>
										<td class="fail">0</td>
									</tr>
									<tr>
										<td>2019.03.01</td>
										<td class="name">365할인마트_창원_구암대동점</td>
										<td>12341234</td>
										<td>1234</td>
										<td>1234123</td>
										<td class="succ">122334</td>
										<td class="fail">1312244</td>
										<td class="succ">1212313</td>
										<td class="fail">1312343</td>
										<td class="succ">344234</td>
										<td class="fail">3453453</td>
										<td class="succ">345345</td>
										<td class="fail">34534534</td>
										<td class="succ">345345</td>
										<td class="fail">345345</td>
										<td class="succ">0</td>
										<td class="fail">34534</td>
										<td class="succ">3453455</td>
										<td class="fail">0</td>
										<td class="succ">345345</td>
										<td class="fail">0</td>
										<td class="succ">34534</td>
										<td class="fail">0</td>
									</tr>
									<tr>
										<td>2019.03.01</td>
										<td class="name">365할인마트_창원_구암대동점</td>
										<td>12341234</td>
										<td>1234</td>
										<td>1234123</td>
										<td class="succ">122334</td>
										<td class="fail">1312244</td>
										<td class="succ">1212313</td>
										<td class="fail">1312343</td>
										<td class="succ">344234</td>
										<td class="fail">3453453</td>
										<td class="succ">345345</td>
										<td class="fail">34534534</td>
										<td class="succ">345345</td>
										<td class="fail">345345</td>
										<td class="succ">0</td>
										<td class="fail">34534</td>
										<td class="succ">3453455</td>
										<td class="fail">0</td>
										<td class="succ">345345</td>
										<td class="fail">0</td>
										<td class="succ">34534</td>
										<td class="fail">0</td>
									</tr>
									<tr>
										<td>2019.03.01</td>
										<td class="name">365할인마트_창원_구암대동점</td>
										<td>12341234</td>
										<td>1234</td>
										<td>1234123</td>
										<td class="succ">122334</td>
										<td class="fail">1312244</td>
										<td class="succ">1212313</td>
										<td class="fail">1312343</td>
										<td class="succ">344234</td>
										<td class="fail">3453453</td>
										<td class="succ">345345</td>
										<td class="fail">34534534</td>
										<td class="succ">345345</td>
										<td class="fail">345345</td>
										<td class="succ">0</td>
										<td class="fail">34534</td>
										<td class="succ">3453455</td>
										<td class="fail">0</td>
										<td class="succ">345345</td>
										<td class="fail">0</td>
										<td class="succ">34534</td>
										<td class="fail">0</td>
									</tr>
									<tr>
										<td>2019.03.01</td>
										<td class="name">365할인마트_창원_구암대동점</td>
										<td>12341234</td>
										<td>1234</td>
										<td>1234123</td>
										<td class="succ">122334</td>
										<td class="fail">1312244</td>
										<td class="succ">1212313</td>
										<td class="fail">1312343</td>
										<td class="succ">344234</td>
										<td class="fail">3453453</td>
										<td class="succ">345345</td>
										<td class="fail">34534534</td>
										<td class="succ">345345</td>
										<td class="fail">345345</td>
										<td class="succ">0</td>
										<td class="fail">34534</td>
										<td class="succ">3453455</td>
										<td class="fail">0</td>
										<td class="succ">345345</td>
										<td class="fail">0</td>
										<td class="succ">34534</td>
										<td class="fail">0</td>
									</tr>
									<tr class="total">
										<td colspan="2">소계</td>
										<td>23,118,973</td>
										<td>23,118,973</td>
										<td>23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
										<td class="succ">23,118,973</td>
										<td class="fail">23,118,973</td>
									</tr>
								</tbody>
							</table>
						</div>	
					</div-->

				</div><!-- mArticle END -->
				
				
				
			
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				