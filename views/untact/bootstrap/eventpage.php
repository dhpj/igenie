<link rel="stylesheet" href="/css/animate.css">
<link rel="stylesheet" type="text/css" href="/views/untact/bootstrap/css/style.css?v=<?=date("ymdHis")?>"/>
<link rel="stylesheet" type="text/css" href="/views/untact/bootstrap/css/voucher.css?v=<?=date("ymdHis")?>"/>

<script src="/js/wow.min.js"></script>
<script>
new WOW().init();
</script>

<link rel="stylesheet" type="text/css" href="/views/deposit/bootstrap/css/style.css?v=<?=date("ymdHis")?>"/>

<div class="grant_wrap deposit_event_wrap">

    <!-- 이벤트 시작 -->
    <div id="mArticle" class="eve_231116">
        <div class="form_section">
            <div class="inner_tit mg_t30">
                <h3><a href="/" target="_blank"><img src="/dhn/images/logo_v2.png" alt="지니"></a></h3>
            </div>
            <div class="service_box">
                <ul>
                    <li>
                        <label for="tag_all" class="r_container">300,000 충전시 <img src="/images/icon_arr_s.png" /> <span>5%<em>15,000원</em></span> 서비스충전
                        <input type="radio" name="eve_list" id="tag_all1" class="tag_list" value="1" checked="checked">
                        <span class="checkmark"></span>
                        </label>
                    </li>
                    <li>
                        <label for="tag_all" class="r_container">500,000 충전시 <img src="/images/icon_arr_s.png" /> <span>7%<em>35,000원</em></span> 서비스충전
                        <input type="radio" name="eve_list" id="tag_all2" class="tag_list" value="2">
                        <span class="checkmark"></span>
                        </label>
                    </li>
                    <li>
                        <label for="tag_all" class="r_container">1,000,000 충전시 <img src="/images/icon_arr_s.png" /> <span>10%<em>100,000원</em></span> 서비스충전
                        <input type="radio" name="eve_list" id="tag_all3" class="tag_list" value="3">
                        <span class="checkmark"></span>
                        </label>
                    </li>
                    <li>
                        <label for="tag_all" class="r_container">3,000,000 충전시 <img src="/images/icon_arr_s.png" /> <span>13%<em>390,000원</em></span> 서비스충전
                        <input type="radio" name="eve_list" id="tag_all4" class="tag_list" value="4">
                        <span class="checkmark"></span>
                        </label>
                    </li>
                    <li>
                        <label for="tag_all" class="r_container">5,000,000 충전시 <img src="/images/icon_arr_s.png" /> <span>17%<em>850,000원</em></span> 서비스충전
                        <input type="radio" name="eve_list" id="tag_all5" class="tag_list" value="5">
                        <span class="checkmark"></span>
                        </label>
                    </li>
                    <li>
                        <label for="tag_all" class="r_container">10,000,000 충전시 <img src="/images/icon_arr_s.png" /> <span>20%<em>2,000,000원</em></span> 서비스충전
                        <input type="radio" name="eve_list" id="tag_all6" class="tag_list" value="6">
                        <span class="checkmark"></span>
                        </label>
                    </li>
                </ul>
                <p class="point_re01">
                    <strong>서비스충전 금액</strong> <span id="total_p">300,000원 x 5% </span>  = <span class="fr"><i class="xi-won"></i> <span id="total_eve">15,000</span></span>
                </p>
                <p class="point_re02">
                    <strong>총 충전 금액</strong>
                    <span class="fr"><i class="xi-won"></i> <span id="total_calv">315,000</span></span>
                </p>
                <div class="charge_box"></div>
            </div>
            <div class="charge_notice">
                <h6>환불 및 해지 시 이벤트(서비스)금액은 전액 차감 후 환불됩니다.</h6>
                <ul>
                    <li>- 300,000원 이상 ~ 500,000원 미만 충전시/ 15,000원 서비스충전</li>
                    <li>- 500,000원 이상 ~ 1,000,000원 미만 충전시/ 35,000원 서비스충전</li>
                    <li>- 1,000,000원 이상 ~ 3,000,000원 미만 충전시/ 100,000원 서비스충전</li>
                    <li>- 3,000,000원 이상 ~ 5,000,000원 미만 충전시/ 390,000원 서비스충전</li>
                    <li>- 5,000,000원 이상 ~ 10,000,000원 미만 충전시/ 850,000원 서비스충전</li>
                    <li>- 10,000,000원 이상 충전시/ 2,000,000원 서비스충전</li>
                </ul>
                <div class="charge_btn_wrap">
                    <a class="btn_charge" href="/">지니 바로가기</a>
                    <dl class="call_num">
                        <dt>서비스문의</dt>
                        <dd>1522-7985</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div><!-- mArticle END -->
	<!--// 스마트상점 -->

	<div class="grant_bot">
		<div class="cer_logo">
			<ul>
				<li><img src="/images/logo_innobiz.png" alt="" /></li>
				<li><img src="/images/logo_cert1.png" alt="" /></li>
				<li><img src="/images/logo_cert2.png" alt="" /></li>
				<li><img src="/images/logo_cert3.png" alt="" /></li>
				<li><img src="/images/logo_cert4.png" alt="" /></li>
				<li><img src="/images/logo_cert.png" alt="" /></li>
			</ul>
		</div>
		<div class="content">
			사업자등록번호 : 364-88-00974 | 통신판매업 : 신고번호 2018-창원의창-0272호 |
			특수한 유형의 부가통신사업자 등록번호: 제 3-02-19-0001호 | 대표이사 : 송종근 <br />
			Copyright © DHN Corp. All rights reserved.
		</div>
	</div>

</div>

<script>
    $(document).ready(function(){
        $('input:radio[name=eve_list]').change(function(){
            var select_val = $(this).val();
            console.log(select_val);
            switch (select_val) {
                case '1':
                    $('#total_p').text('300,000원 x 5%');
                    $('#total_eve').text('15,000');
                    $('#total_calv').text('315,000');
                    break;
                case '2':
                    $('#total_p').text('500,000원 x 7%');
                    $('#total_eve').text('35,000');
                    $('#total_calv').text('535,000');
                    break;
                case '3':
                    $('#total_p').text('1,000,000원 x 10%');
                    $('#total_eve').text('100,000');
                    $('#total_calv').text('1,100,000');
                    break;
                case '4':
                    $('#total_p').text('3,000,000원 x 13%');
                    $('#total_eve').text('390,000');
                    $('#total_calv').text('3,390,000');
                    break;
                case '5':
                    $('#total_p').text('5,000,000원 x 17%');
                    $('#total_eve').text('850,000');
                    $('#total_calv').text('5,850,000');
                    break;
                case '6':
                    $('#total_p').text('10,000,000원 x 20%');
                    $('#total_eve').text('2,000,000');
                    $('#total_calv').text('12,000,000');
                    break;
            }
        });
    });
</script>
