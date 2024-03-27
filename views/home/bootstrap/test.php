<div class="main_top">
	<!--div class="select_bar fr">
		<ul>
			<li class="selected">A.I 스마트설정</li>
			<li class="">커스텀설정</li>
			<li class="">수동설정</li>
		</ul>
	</div-->
	<div class="notice_bar">
		<span class="title">알립니다.</span>
		<span class="notice_text"><? if(!empty($notice->post_id)){ ?><a href="/post/<?=$notice->post_id?>">[<?=$notice->post_date?>] <?=$notice->post_title?></a><? } ?></span>
	</div>
</div>
<div class="main_contents">
	<div class="main_card_list row3">
		<div class="card line_color1">
			<h3 class="title">나의 고객</h3>
			<p class="value"><?=number_format($mycnt->cus_cnt)?>명</p>
		</div>
		<div class="card line_color2">
			<h3 class="title">스마트 전단 행사</h3>
			<p class="value"><?=number_format($mycnt->psd_cnt)?>건</p>
		</div>
		<div class="card line_color3">
			<h3>오늘의 발송현황</h3>
			<p class="value"><?=number_format($mycnt->mst_qty)?>건</p>
		</div>
	</div>
	<? if($mem_pos_id != ""){ //포스연동 ID가 있는 경우 ?>
	<div class="main_card_list row2">
		<div class="card">
			<div class="card_option">
				<ul>
					<!--<li class="<?=($chart['date_type'] == "DAY") ? "on" : ""?>">일</li>-->
					<li class="<?=($chart['date_type'] == "WEEK") ? "on" : ""?>" id="BTN_SALES_WEEK" onclick="daily_data('SALES', 'WEEK');" style="cursor: pointer;!important;">주</li>
					<li class="<?=($chart['date_type'] == "MONTH") ? "on" : ""?>" id="BTN_SALES_MONTH" onclick="daily_data('SALES', 'MONTH');" style="cursor: pointer;!important;">월</li>
				</ul>
			</div>
			<h3>매출현황</h3>
			<div class="card_row">
				<div class=""><h4>오늘&nbsp;매출</h4><p class="chart_value" id="TODAY_SALES_TOT"><?=number_format($chart['TODAY_SALES_TOT'])?>원</p></div>
				<div class=""><h4>어제&nbsp;매출</h4><p class="chart_value" id="YESTERDAY_SALES_TOT"><?=number_format($chart['YESTERDAY_SALES_TOT'])?>원</p></div>
			</div>
			<div class="card_row">
				<div class=""><h4 id="SALES_TYPE1"><?=($chart['date_type'] == "MONTH") ? "이번달" : "이번주"?>&nbsp;매출</h4><p class="chart_value" id="SALES_TOT"><?=number_format(($chart['date_type'] == "MONTH") ? $chart['MONTH_SALES_TOT'] : $chart['WEEK_SALES_TOT'])?>원</p></div>
				<div class=""><h4 id="SALES_TYPE2"><?=($chart['date_type'] == "MONTH") ? "지난달" : "지난주"?>&nbsp;매출</h4><p class="chart_value" id="PREV_SALES_TOT"><?=number_format(($chart['date_type'] == "MONTH") ? $chart['MONTH_PREV_SALES_TOT'] : $chart['WEEK_PREV_SALES_TOT'])?>원</p></div>
			</div>
			<div class="chart_wrap" id="graph_sales"></div>
		</div>
		<div class="card">
			<div class="card_title">
				<div class="card_option">
					<ul>
						<!--<li class="<?=($chart['date_type'] == "DAY") ? "on" : ""?>">일</li>-->
						<li class="<?=($chart['date_type'] == "WEEK") ? "on" : ""?>" id="BTN_VISIT_WEEK" onclick="daily_data('VISIT', 'WEEK');" style="cursor: pointer">주</li>
						<li class="<?=($chart['date_type'] == "MONTH") ? "on" : ""?>" id="BTN_VISIT_MONTH" onclick="daily_data('VISIT', 'MONTH');" style="cursor: pointer">월</li>
					</ul>
				</div>
				<h3>매장방문고객</h3>
			</div>
			<div class="card_row">
				<div class=""><h4>오늘&nbsp;방문자</h4><p class="chart_value" id="TODAY_VISIT_TOT"><?=number_format($chart['TODAY_VISIT_TOT'])?>명</p></div>
				<div class=""><h4>어제&nbsp;방문자</h4><p class="chart_value" id="YESTERDAY_VISIT_TOT"><?=number_format($chart['YESTERDAY_VISIT_TOT'])?>명</p></div>
			</div>
			<div class="card_row">
				<div class=""><h4 id="VISIT_TYPE1"><?=($chart['date_type'] == "MONTH") ? "이번달" : "이번주"?>&nbsp;방문자</h4><p class="chart_value" id="VISIT_TOT"><?=number_format(($chart['date_type'] == "MONTH") ? $chart['MONTH_VISIT_TOT'] : $chart['WEEK_VISIT_TOT'])?>명</p></div>
				<div class=""><h4 id="VISIT_TYPE1"><?=($chart['date_type'] == "MONTH") ? "지난달" : "지난주"?>&nbsp;방문자</h4><p class="chart_value" id="PREV_VISIT_TOT"><?=number_format(($chart['date_type'] == "MONTH") ? $chart['MONTH_PREV_VISIT_TOT'] : $chart['WEEK_PREV_VISIT_TOT'])?>명</p></div>
			</div>
			<div class="chart_wrap" id="graph_visit"></div>
		</div>
	</div>
	<!-- 메인 차트 관련 -->
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script>
		Highcharts.setOptions({
			lang: {
				thousandsSep: ','
			}
		});
		//매출현황 차트
		Highcharts.chart('graph_sales', {
			title: {
				text: '<?=($chart['date_type'] == "MONTH") ? $chart['month_title'] : $chart['week_title']?>'
			},
			legend: {
				layout: 'horizontal',
				align: 'center',
				verticalAlign: 'bottom'
			},

			xAxis: {
				categories: [ <?=($chart['date_type'] == "MONTH") ? $chart['MONTH_SALES_CAT'] : $chart['WEEK_SALES_CAT']?> ]
			},
			yAxis: {
				title: {
					text: '주문건수'
				}
			},
			series: [{
				showInLegend: false,
				name: '매출액',
				data: [ <?=($chart['date_type'] == "MONTH") ? $chart['MONTH_SALES_AMT'] : $chart['WEEK_SALES_AMT']?> ]
			}]
		});
		
		//매장방문고객 차트
		Highcharts.chart('graph_visit', {
			title: {
				text: '<?=($chart['date_type'] == "MONTH") ? $chart['month_title'] : $chart['week_title']?>'
			},
			legend: {
				layout: 'horizontal',
				align: 'center',
				verticalAlign: 'bottom'
			},

			xAxis: {
				categories: [ <?=($chart['date_type'] == "MONTH") ? $chart['MONTH_VISIT_CAT'] : $chart['WEEK_VISIT_CAT']?> ]
			},
			yAxis: {
				title: {
					text: '방문자수'
				}
			},
			series: [{
				showInLegend: false,
				name: '방문자수',
				data: [ <?=($chart['date_type'] == "MONTH") ? $chart['MONTH_VISIT_CNT'] : $chart['WEEK_VISIT_CNT']?> ]
			}]
		});

		//일별통계 실시간 조회
		function daily_data(data_type, date_type){
			//alert("data_type : "+ data_type +", date_type : "+ date_type);
			if(data_type == "SALES"){ //데이타 구분(ALL.전체, VISIT.방문정보, SALES.매출정보)
				var chart1 = $('#graph_sales').highcharts(); //매출현황 차트
				var SALES_CAT = "<?=$chart['WEEK_SALES_CAT']?>"; //주별 카테고리
				var SALES_AMT = "<?=$chart['WEEK_SALES_AMT']?>"; //주별 데이타
				var SALES_TOT = "<?=$chart['WEEK_SALES_TOT']?>"; //이번주 매출
				var PREV_SALES_TOT = "<?=$chart['WEEK_PREV_SALES_TOT']?>"; //지난주 매출
				var TITLE = "<?=$chart['week_title']?>"; //주별 타이틀
				if(date_type == "MONTH"){
					SALES_CAT = "<?=$chart['MONTH_SALES_CAT']?>"; //월별 카테고리
					SALES_AMT = "<?=$chart['MONTH_SALES_AMT']?>"; //월별 데이타
					SALES_TOT = "<?=$chart['MONTH_SALES_TOT']?>"; //지난달 매출
					PREV_SALES_TOT = "<?=$chart['MONTH_PREV_SALES_TOT']?>"; //지난달 매출
					TITLE = "<?=$chart['month_title']?>"; //월별 타이틀
				}
				//alert("SALES_CAT : "+ SALES_CAT +"\n"+ "SALES_AMT : "+ SALES_AMT); return;
				var data1 = SALES_AMT.split(", "); //매출 데이타
				var vdata = [];
				for (var i in data1) {
					vdata.push(Number(data1[i]));
				}
				var xdata = SALES_CAT.split(", "); //x축
				chart1.series[0].update({
					data: vdata
				});
				chart1.xAxis[0].update({
					categories: xdata
				});
				chart1.setTitle({text: TITLE}); //타이틀
				chart1.redraw();
				$("#SALES_TOT").html(numberWithCommas(SALES_TOT) +"원"); //이번주/달 매출
				$("#PREV_SALES_TOT").html(numberWithCommas(PREV_SALES_TOT) +"원"); //지난주/달 매출
				$("#BTN_SALES_WEEK").removeClass("on");
				$("#BTN_SALES_MONTH").removeClass("on");
				if (date_type == "WEEK") {
					$("#BTN_SALES_WEEK").addClass("on");
					$("#SALES_TYPE1").html("이번주 매출");
					$("#SALES_TYPE2").html("지난주 매출");
				} else if (date_type == "MONTH") {
					$("#BTN_SALES_MONTH").addClass("on");
					$("#SALES_TYPE1").html("이번달 매출");
					$("#SALES_TYPE2").html("지난달 매출");
				}
			}else if(data_type == "VISIT"){ //데이타 구분(ALL.전체, VISIT.방문정보, SALES.매출정보)
				var chart1 = $('#graph_visit').highcharts(); //방문현황 차트
				var VISIT_CAT = "<?=$chart['WEEK_VISIT_CAT']?>"; //주별 카테고리
				var VISIT_CNT = "<?=$chart['WEEK_VISIT_CNT']?>"; //주별 데이타
				var VISIT_TOT = "<?=$chart['WEEK_VISIT_TOT']?>"; //이번주 매출
				var PREV_VISIT_TOT = "<?=$chart['WEEK_PREV_VISIT_TOT']?>"; //지난주 매출
				var TITLE = "<?=$chart['week_title']?>"; //주별 타이틀
				if(date_type == "MONTH"){
					VISIT_CAT = "<?=$chart['MONTH_VISIT_CAT']?>"; //월별 카테고리
					VISIT_CNT = "<?=$chart['MONTH_VISIT_CNT']?>"; //월별 데이타
					VISIT_TOT = "<?=$chart['MONTH_VISIT_TOT']?>"; //지난달 매출
					PREV_VISIT_TOT = "<?=$chart['MONTH_PREV_VISIT_TOT']?>"; //지난달 매출
					TITLE = "<?=$chart['month_title']?>"; //월별 타이틀
				}
				//alert("VISIT_CAT : "+ VISIT_CAT +"\n"+ "VISIT_CNT : "+ VISIT_CNT); return;
				var data1 = VISIT_CNT.split(", "); //방문 데이타
				var vdata = [];
				for (var i in data1) {
					vdata.push(Number(data1[i]));
				}
				var xdata = VISIT_CAT.split(", "); //x축
				chart1.series[0].update({
					data: vdata
				});
				chart1.xAxis[0].update({
					categories: xdata
				});
				chart1.setTitle({text: TITLE}); //타이틀
				chart1.redraw();
				$("#VISIT_TOT").html(numberWithCommas(VISIT_TOT) +"명"); //이번주 방문자
				$("#PREV_VISIT_TOT").html(numberWithCommas(PREV_VISIT_TOT) +"명"); //지난주 방문자
				$("#BTN_VISIT_WEEK").removeClass("on");
				$("#BTN_VISIT_MONTH").removeClass("on");
				if (date_type == "WEEK") {
					$("#BTN_VISIT_WEEK").addClass("on");
					$("#VISIT_TYPE1").html("이번주 방문자");
					$("#VISIT_TYPE2").html("지난주 방문자");
				} else if (date_type == "MONTH") {
					$("#BTN_VISIT_MONTH").addClass("on");
					$("#VISIT_TYPE1").html("이번달 방문자");
					$("#VISIT_TYPE2").html("지난달 방문자");
				}
			}
		}

		//금액 콤마 찍기
		function numberWithCommas(x) {
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
	</script>
	<? }else{ //if($this->member->item("mem_pos_id") != ""){ //포스연동 ID가 없는 경우 ?>
	<div class="pos_none">
		<p>
			스마트 매장관리시스템 '지니'를 이용하시는 고객님,<br />
			매장정보를 보다 편리하게 관리하시려면 포스사에 지니를 소개해주세요~<br />
			지니 & (주)대형네트웍스 : <span>1522-7985</span>
		</p>
	</div>
	<? } //if($this->member->item("mem_pos_id") != ""){ //포스연동 ID가 있는 경우 ?>
</div>