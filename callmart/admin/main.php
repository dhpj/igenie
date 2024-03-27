<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=no">
	<title>대형네트웍스마트 관리자</title>
	<link href="css/basic.css" rel="stylesheet" type="text/css">
	<link href="css/admin.css" rel="stylesheet" type="text/css">
	
</head>
<body>
	<div class="wrap">
		<div class="gnb">
			<div class="logo">대형네트웍스마트 관리자</div>
			<div class="login_info">logout</div>
		</div>
		<div class="container">
			<div class="snb">
				<button class="btn create">주문내역</button>
				<ul>
					<li>주문내역</li>
					<li>상품등록</li>
					<li>상품조회/수정</li>
					<li>관리자정보</li>
				</ul>
			</div>
			<div class="contents">
				<h2 style="text-align: center">
					<p id="time-result"></p>				
				</h2>
				<div class="content">
					<div class="board_row">
						<div class="board_box active">
							<div class="box_contents">
								<h3>신규주문</h3>
								<p class="sub_title">발송전인 신규 주문건입니다.</p>
								<p class="box_data ea">3</p>
							</div>
						</div>
						<div class="board_box">
							<div class="box_contents">
								<h3>배송완료</h3>
								<p class="sub_title">배송이 완료된 주문건입니다.</p>
								<p class="box_data ea">3</p>
							</div>
						</div>
						<div class="board_box">
							<div class="box_contents">
								<h3>전체 주문 현황</h3>
								<p class="sub_title">오늘의 주문 상황입니다.</p>
								<p class="box_data ea">6</p>
							</div>
						</div>
						
					</div>
					<div class="board_row">
						<div class="board_box">
							<div class="box_title">
								최근 1주일간 주문건수
							</div>
							<div class="box_contents" id="container"></div>
						</div>
						<div class="board_box">
							<div class="box_title">
								최근 1주일간 주문금액
							</div>
							<div class="box_contents" id="container2"></div>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>
<script src='https://code.highcharts.com/highcharts.js'></script>
<script src='https://code.highcharts.com/modules/exporting.js'></script>
<script id="rendered-js">
Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: ['월', '화', '수', '목', '금', '토', '일']
    },
    yAxis: {
        title: {
            text: ''
        }
    },   
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    },
    series: [{
        name: '주문건수',
        data: [5, 7, 6, 14, 18, 21, 25]
    },{
        name: '주문금액',
        data: [100000, 150000, 350000, 140000, 180000, 210000, 250000]
    }]
});
Highcharts.chart('container2', {
    chart: {
        type: 'line'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: ['월', '화', '수', '목', '금', '토', '일']
    },
    yAxis: {
        title: {
            text: ''
        }
    },   
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    },
    series: [{
        name: '주문금액',
        data: [100000, 150000, 350000, 140000, 180000, 210000, 250000]
    }]
});
    </script>
    	<script type="text/javascript">
		var d = new Date();
		var currentDate = d.getFullYear() + "년 " + ( d.getMonth() + 1 ) + "월 " + d.getDate() + "일";
		var currentTime = d.getHours() + "시 " + d.getMinutes() + "분 " + d.getSeconds() + "초";
		var result = document.getElementById("time-result");
		result.innerHTML = currentDate + "<span class='current_time'>" + currentTime + "</span>";
	</script>	
</body>
</html>