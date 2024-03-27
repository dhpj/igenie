 <?
	//---- 오늘 날짜
	$thisyear = date('Y'); // 4자리 연도
	$thismonth = date('n'); // 0을 포함하지 않는 월
	$today = date('j'); // 0을 포함하지 않는 일

	//------ $year, $month 값이 없으면 현재 날짜
	$year = isset($_GET['year']) ? $_GET['year'] : $thisyear;
	$month = isset($_GET['month']) ? $_GET['month'] : $thismonth;
	$day = isset($_GET['day']) ? $_GET['day'] : $today;
	//echo "year : ". $year .", month : ". $month .", day : ". $day ."<br>";

	$prev_month = $month - 1;
	$next_month = $month + 1;
	$prev_year = $next_year = $year;
	if ($month == 1) {
		$prev_month = 12;
		$prev_year = $year - 1;
	} else if ($month == 12) {
		$next_month = 1;
		$next_year = $year + 1;
	}
	$preyear = $year - 1;
	$nextyear = $year + 1;

	$predate = date("Y-m-d", mktime(0, 0, 0, $month - 1, 1, $year));
	$nextdate = date("Y-m-d", mktime(0, 0, 0, $month + 1, 1, $year));

	//echo "prev_year : ". $prev_year .", prev_month : ". $prev_month .", next_year : ". $next_year .", next_month : ". $next_month ."<br>";

	// 1. 총일수 구하기
	$max_day = date('t', mktime(0, 0, 0, $month, 1, $year)); // 해당월의 마지막 날짜
	//echo '총요일수'.$max_day.'<br />';

	// 2. 시작요일 구하기
	$start_week = date("w", mktime(0, 0, 0, $month, 1, $year)); // 일요일 0, 토요일 6

	// 3. 총 몇 주인지 구하기
	$total_week = ceil(($max_day + $start_week) / 7);

	// 4. 마지막 요일 구하기
	$last_week = date('w', mktime(0, 0, 0, $month, $max_day, $year));
?>
<div class="tit_wrap">
	발송내역
</div>
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>발송내역 (전체 <b style="color: red"><?=number_format($total_rows)?></b>건)</h3>
		</div>
    <div class="btn_view_list">
      <a href="/dhnbiz/sender/history" class="list_off"><i class="xi-list"></i> 전체 발송목록</a>
      <a href="/dhnbiz/sender/history/monthly" class="list_on"><i class="xi-calendar-list"></i> 월별 발송건수</a>
    </div>
		<div class="white_box">
			<div class="calenda_haeder">
			  <span class="ch_pre" onclick="location.href='?year=<?=$prev_year?>&month=<?=$prev_month?>';" title="이전달">❮</span>
			  <span class="ch_c1"><?=$year?></span> <span class="ch_c2">년</span>
			  <span class="ch_c1"><?=$month?></span> <span class="ch_c2">월</span>
			  <span class="ch_next" onclick="location.href='?year=<?=$next_year?>&month=<?=$next_month?>';" title="다음달">❯</span>
			  <span class="ch_today"><a onclick="location.href='?year=<?=$thisyear?>&month=<?=$thismonth?>';" title="오늘로" style="cursor:pointer;">Today</a></span>
			</div>
			<table class="calenda" summary="calenda">
			  <caption>
			  calenda
			  </caption>
			  <colgroup>
			  <col width="15%">
			  <col width="14%">
			  <col width="14%">
			  <col width="14%">
			  <col width="14%">
			  <col width="14%">
			  <col width="15%">
			  </colgroup>
			  <thead>
				<tr>
				  <th>SUN</th>
				  <th>MON</th>
				  <th>TUE</th>
				  <th>WED</th>
				  <th>THU</th>
				  <th>FRI</th>
				  <th>SAT</th>
				</tr>
			  </thead>
			  <tbody>
					<?
						//화면에 표시할 화면의 초기값을 1로 설정
						$day=1;
						//총 주 수에 맞춰서 세로줄 만들기
						for($i=1; $i <= $total_week; $i++){
							echo '<tr>'. chr(13) . chr(10);
							//총 가로칸 만들기
							for ($j = 0; $j < 7; $j++) {
								//첫번째 주이고 시작요일보다 $j가 작거나 마지막주이고 $j가 마지막 요일보다 크면 표시하지 않음
								if ($year == $thisyear && $month == $thismonth && $day == date("j")) { //오늘 날짜의 경우
									$td_style = ' class="today"'; //오늘날짜 스타일
								}else{
									$td_style = '';
								}
								echo '<td'. $td_style .'>'. chr(13) . chr(10);
								if(!(($i == 1 && $j < $start_week) || ($i == $total_week && $j > $last_week))){
									$day_style = ' class="date"'; //평일 스타일
									$data_span = ''; //데이타 표기
									if(!empty($mst_list)) { //발송내역 조회
										foreach($mst_list as $data) {
											$mst_date = $data->mst_date; //발송일자(YYYYMMDD)
											$mst_qty = $data->mst_qty; //발송건수
											$mst_at = $data->mst_at; //알림톡 성공 건수
											$mst_err_at = $data->mst_err_at; //알림톡 실패 건수
											$mst_ft = $data->mst_ft; //친구톡 성공 건수
											$mst_err_ft = $data->mst_err_ft; //친구톡 실패 건수
											$mst_sms = $data->mst_sms; //문자 성공 건수
											$mst_err_sms = $data->mst_err_sms; //문자 실패 건수
											$calendar_date = $year . $this->funn->fnZeroAdd($month) . $this->funn->fnZeroAdd($day); //달력일자
											if($mst_date == $calendar_date && $mst_qty > 0 && ($mst_at > 0 or $mst_err_at > 0)){ //알림톡 발송건수 표기
												$data_span .= '<div class="send_kakao">알림톡(성공<span>'. $mst_at .'</span>/실패<span>'. $mst_err_at .'</span>)</div>';
											}
											if($mst_date == $calendar_date && $mst_qty > 0 && ($mst_ft > 0 or $mst_err_ft > 0)){ //친구톡 발송건수 표기
												$data_span .= '<div class="send_kakao">친구톡(성공<span>'. $mst_ft .'</span>/실패<span>'. $mst_err_ft .'</span>)</div>';
											}
											if($mst_date == $calendar_date && $mst_qty > 0 && ($mst_sms > 0 or $mst_err_sms > 0)){ //문자 발송건수 표기
												$data_span .= '<div class="send_sms">문자(성공<span>'. $mst_sms .'</span>/실패<span>'. $mst_err_sms .'</span>)</div>';
											}
										}
									}
									echo '<span'. $day_style .'>'. $day .'</span>'. $data_span; //날짜표시
									$day++;//날짜 증가
								}
								echo '</td>';
							}
							echo '</tr>';
						}
					?>
				</tbody>
			</table>
		</div>
	</div><!-- form_section End -->
</div><!-- #mArticle end -->
