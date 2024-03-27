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
	<!--<div class="main_card_list row2">
		<div class="card">
			<div class="card_option">
				<ul>
					<li class="" >일</li>
					<li class="">주</li>
					<li class="on">월</li>
				</ul>
			</div>
			<h3>매출현황</h3>
			<div class="card_row">
				<div class=""><h4>오늘매출</h4><p class="chart_value">34,564원</p></div>
				<div class=""><h4>어제매출</h4><p class="chart_value">34,564원</p></div>
			</div>
			<div class="chart_wrap" id="graph_saleamt"></div>
		</div>
		<div class="card">
			<div class="card_title">
				<div class="card_option">
					<ul>
						<li class="" >일</li>
						<li class="">주</li>
						<li class="on">월</li>
					</ul>
				</div>
				<h3>매장방문고객</h3>
			</div>
			<div class="card_row">
				<div class=""><h4>오늘방문자</h4><p class="chart_value">322명</p></div>
				<div class=""><h4>어제방문자</h4><p class="chart_value">638명</p></div>
			</div>
			<div class="chart_wrap" id="graph_hits"></div>
		</div>
	</div>-->
	<div class="pos_none">
		<p>
			스마트 매장관리시스템 '지니'를 이용하시는 고객님,<br />
			매장정보를 보다 편리하게 관리하시려면 포스사에 지니를 소개해주세요~<br />
			지니 & (주)대형네트웍스 : <span>1522-7985</span>
		</p>
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
    Highcharts.chart('graph_saleamt', {
        title: {
            text: '<?=date('Y년 n월 j일')?>'
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom'
        },

        xAxis: {
            categories: [ 20, 21, 22, 23, 24, 25, 26 ]
        },
        yAxis: {
            title: {
                text: '주문건수'
            }
        },
        series: [{
            showInLegend: false,
            name: '매출액',
            data: [ 21744, 17722, 20185, 19771, 20185, 21744, 20185 ]
        }]
    });
    Highcharts.chart('graph_hits', {
        title: {
            text: '<?=date('Y년 n월 j일')?>'
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom'
        },

        xAxis: {
            categories: [ 20, 21, 22, 23, 24, 25, 26 ]
        },
        yAxis: {
            title: {
                text: '방문자수'
            }
        },
        series: [{
            showInLegend: false,
            name: '방문자수',
            data: [ 43934, 52503, 57177, 69658, 97031, 119931, 137133 ]
        }]
    });
</script>
