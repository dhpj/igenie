<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>요금안내</title>
<link rel="stylesheet" type="text/css" href="http://igenie.co.kr/views/_layout/bootstrap/css/import.css?v=220221105511" />
<link rel="stylesheet" type="text/css" href="/views/deposit/bootstrap/css/style.css?v=220221105511" />
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css" />
<style>
.service_box3{width:1000px; height:1680px; margin-bottom:30px; background:#fff url('/images/service_bg_20220221.jpg') no-repeat top center; background-size: 100%;}
.service_box3 ul{padding:777px 0 0 150px;}
.service_box3 li{font-size:20px; margin-bottom:67px;}
.service_box3 li span{color:#f00;}
.service_box3 li img{margin:-3px 5px 0 5px;}
.service_box3 li input{}
.service_box3 .point_re01{margin:-20px 125px 0 125px; font-size:20px; color:#fff;}
.service_box3 .point_re01 .fr{float:right; font-size:25px;}
.service_box3 .point_re02{margin:40px 125px 0 125px; font-size:20px; color:#fff;}
.service_box3 .point_re02 .fr{float:right; font-size:25px;}

/* The container */
.service_box3 li .r_container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.service_box3 li .r_container input {
  position: absolute;
  opacity: 0;
  width:100%;
  height:100%;
  top:0;
  bottom:0;
  left:0;
  right:0;
  cursor: pointer;
  z-index: 2;
}

/* Create a custom radio button */
.service_box3 li .checkmark {
  position: absolute;
  top: 5px;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
  z-index: 1;
}

/* On mouse-over, add a grey background color */
.service_box3 li .r_container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.service_box3 li .r_container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.service_box3 li .checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.service_box3 li .r_container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.service_box3 li .r_container .checkmark:after {
 	top: 9px;
	left: 9px;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: white;
}

</style>
</head>

<body>
        <script>
        	$(document).ready(function(){
        		$('input:radio[name=eve_list]').change(function(){
        			var select_val = $(this).val();
        			console.log(select_val);
        			switch (select_val) {
        				case '1':
        					$('#total_p').text('300,000원 / 5%');
        					$('#total_eve').text('15,000원');
        					$('#total_calv').text('315,000원');
        					break;
        				case '2':
        					$('#total_p').text('500,000원 / 7%');
        					$('#total_eve').text('35,000원');
        					$('#total_calv').text('535,000원');
        					break;
                        case '3':
        					$('#total_p').text('1,000,000원 / 8%');
        					$('#total_eve').text('80,000원');
        					$('#total_calv').text('1,080,000원');
        					break;
        				case '4':
        					$('#total_p').text('2,000,000원 / 9%');
        					$('#total_eve').text('180,000원');
        					$('#total_calv').text('2,180,000원');
        					break;
        				case '5':
        					$('#total_p').text('3,000,000원 / 10%');
        					$('#total_eve').text('300,000원');
        					$('#total_calv').text('3,300,000원');
        					break;
        			}
        		});

        	});

        </script>


        <div id="mArticle">

        	<div class="form_section">
        		<!-- <div class="inner_tit mg_t30">
        			<h3>이벤트충전 안내</h3>
        		</div> -->
                <div class="service_box3" style="margin:0 auto;">
        			<ul>
        				<li>
        					<label for="tag_all" class="r_container">300,000 충전시 <img src="/images/icon_arr_s.png" /> <span>5%(15,000원)</span> 서비스충전
                               <input type="radio" name="eve_list" id="tag_all1" class="tag_list" value="1" checked="checked">
                               <span class="checkmark"></span>
                            </label>
        			    </li>
        				<li>
        					<label for="tag_all" class="r_container">500,000 충전시 <img src="/images/icon_arr_s.png" /> <span>7%(35,000원)</span> 서비스충전
                               <input type="radio" name="eve_list" id="tag_all2" class="tag_list" value="2">
                               <span class="checkmark"></span>
                            </label>
        			  </li>
                      <li>
                          <label for="tag_all" class="r_container">1,000,000 충전시 <img src="/images/icon_arr_s.png" /> <span>8%(80,000원)</span> 서비스충전
                             <input type="radio" name="eve_list" id="tag_all3" class="tag_list" value="3">
                             <span class="checkmark"></span>
                          </label>
                    </li>
        				<li>
        					<label for="tag_all" class="r_container">2,000,000 충전시 <img src="/images/icon_arr_s.png" /> <span>9%(180,000원)</span> 서비스충전
                               <input type="radio" name="eve_list" id="tag_all4" class="tag_list" value="4">
                               <span class="checkmark"></span>
                            </label>
        			  </li>
        				<li>
        					<label for="tag_all" class="r_container">3,000,000 충전시 <img src="/images/icon_arr_s.png" /> <span>10%(300,000원)</span> 서비스충전
                               <input type="radio" name="eve_list" id="tag_all5" class="tag_list" value="5">
                               <span class="checkmark"></span>
                            </label>
        			  </li>
        			</ul>
        			<p class="point_re01">
        				<strong>입금금액</strong> <span id="total_p">300,000원 / 5% </span>  = <span class="fr"><i class="xi-won"></i> <span id="total_eve">15,000원</span></span>
        			</p>
        			<p class="point_re02">
        				<strong>총 충전금액</strong>
        				<span class="fr"><i class="xi-won"></i> <span id="total_calv">315,000원</span></span>
        			</p>
        		</div>

        	</div>

        </div><!-- mArticle END -->
    </body>
    </html>
