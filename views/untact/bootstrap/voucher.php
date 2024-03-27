<link rel="stylesheet" href="/css/animate.css">
<link rel="stylesheet" type="text/css" href="/views/untact/bootstrap/css/voucher.css?v=<?=date("ymdHis")?>"/>

<script src="/js/wow.min.js"></script>
<script>
new WOW().init();
</script>

<div class="grant_wrap">
	<div class="grant_top">
		<div class="grant_logo">
			<a href="/" target="_blank"><img src="/images/t_logo.png" alt="지니"/></a>
            <img src="/images/untact/tab_notice_05.png" alt="둘다신청" class="tab_notice_05">
		</div>
		<!-- <div class="grant_tap">
			<p class="tab01" class="on">K비대면 바우처<br/><span>(발송비용 지원)</span></p>
			<p class="tab02">스마트상점<br/><span>(스마트전단 풀케어)</span></p>
			<span id="tab02" onclick="grant_tap02()" class="" style="<?=$this->member->item('mem_id') == 2 ? '' : 'display:none'?>">스마트상점</span>
			<p class="tab_icon_01"><img src="/images/untact/tab_icon_01.png"></p>
			<p class="tab_icon_02" style="display:none;"><img src="/images/untact/tab_icon_02.png"></p>
			<p class="tab_notice_01"><img src="/images/untact/tab_notice_03.png" alt="둘다신청" onclick="window.open('/untact/voucher#voucher')"></p>
			<p class="tab_notice_02" style="display:none;"><img src="/images/untact/tab_notice_04.png" alt="둘다신청"  onclick="window.open('/untact/voucher?type=ss#smartMall')"></p>
		</div> -->
		<div class="grant_btn">
			<a href="/login">지니 바로가기</a>
		</div>
	</div>

	<!-- 비대면바우처 -->
	<div class="voucher_wrap">
		<div class="voucher_visual grant_visual">
			<div class="voucher_slogan">
				<p class="tst1 wow fadeInUp">
					1인 사업자부터<br />
                    마트, 정육점 및 소상공인 누구나!<br />
                    <span>70%</span> 발송비용 지원받고<br />
					지니를 이용하세요!
				</p>
				<p class="tst2 wow fadeInUp">
					지니는 '2023년 비대면 서비스 바우처'의<br />
					공급기업으로 선정되어, 지니 이용료의 70%를 지원합니다.
				</p>
				<p class="tst3 wow fadeInUp">
					<a href="#voucher">신청하기</a>
				</p>
                <img src="/images/untact/img_ing.gif" class="img_ing wow fadeInUp">
			</div>
		</div>
		<div class="process_wrap">
			<div class="content">
				<p class="b_tit1">K비대면 바우처 <b class="halfLine">신청 절차</b></p>
				<!-- <p class="b_tit1">K비대면 바우처 & 스마트상점 <b class="halfLine">신청 절차</b></p> -->
				<p class="b_tit2"><span class="tag_br">지니 발송비용을 </span>70% 할인 받을 수 있는 스마트한 신청 방법!</p>
				<!-- <p class="b_tit2"><span class="tag_br">지니 발송비용과 스마트전단 풀케어를</span>70% 할인 받을 수 있는 스마트한 신청 방법!</p> -->

                <p class="process_m"><img src="/images/untact/img_process_02.jpg" alt="신청절차"></p>
                <!-- 스마트상점제외 -->
                <!-- <p class="process_m"><img src="/images/untact/img_process_01.jpg" alt="신청절차"></p>
                <div class="process">
                    <p>1인 사업자 포함<br>소상공인이라면<br><b class="halfLine">누구나!</b></p>
                    <ul>
    					<li onclick="window.open('/untact/voucher#voucher')"><b class="halfLine">K비대면 바우처 신청</b>심사진행 및<br>지원대상자 확정</li>
    					<li>결제신청</li>
    					<li>계약 승인<br>유무 확인</li>
    					<li>지니 발송비용<br><b class="halfLine">70% 할인!!</b></li>
                    </ul>
                    <ul>
                        <li onclick="window.open('/untact/voucher?type=ss#smartMall_product')"><b class="halfLine">스마트상점 신청</b>중점기술<br>제품구매 신청</li>
    					<li>계약 체결 및<br>자부담금 입금</li>
    					<li>계약 승인<br>유무 확인</li>
    					<li>지니 플랫폼<br>이용금액<br><b class="halfLine">70% 할인!!</b></li>
    				</ul>
                    <a href="/untact/voucher?type=ss#smartMall_product" target="_blank" class="notice_txt_03"><img src="/images/untact/notice_txt_03.png"></a>
                    <a href="#voucher" target="_blank" class="notice_txt_04"><img src="/images/untact/notice_txt_04.png"></a>
                </div> -->
			</div>
		</div>
		<!-- <div class="voucher_mbox1"> -->
		<div class="smartMall_mbox1 grant_mbox1">
			<div class="content">
				<p class="b_tit1"><b>지니</b>는 어떤 수요업체가 적합한가요?</p>
				<p class="b_tit2">아래와 같은 경우에 해당한다면 비대면 서비스 바우처 지원을 통해 지니를 이용해보세요!</p>
				<ul class="mbox_type_02">
					<li>
						<p><img src="/images/untact/img_mart_01.png"></p>
						<dl>
							<dt><span>마트</span></dt>
							<dd>코로나 이후<br/>온라인 주문에<br/>익숙해진 고객님들께<br/>저희 매장도<br/><span class="halfLine">온라인으로 홍보</span>하고<br/><span class="halfLine">직접 주문 & 배송</span>까지 하니, 매출이 쑥쑥<br/>오르더라고요!</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_02.png"></p>
						<dl>
							<dt><span>미용실</span></dt>
							<dd>고객님들께<br/>현장에서 설명드리면<br/>고민하시다 구매로 이어지는 경우는 적은데,<br/><br/><span class="halfLine">스마트전단</span>으로<br/>한 번 더 홍보하면<br/><span class="halfLine">구매율</span>이 높아지더라고요!</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_03.png"></p>
						<dl>
							<dt><span>카페</span></dt>
							<dd>오픈 이벤트 메뉴를<br/>홍보 디자인 전문가에게 맡겨야 하나 고민하고 있었는데<br/><span class="halfLine">금전, 소요되는 시간</span>이 부담스러웠어요.<br/><br/>
							하지만<br/><span class="halfLine">스마트전단</span>으로<br/>간단하게 제작하여 홍보할 수 있어서<br/>너무 편해요!</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_04.png"></p>
						<dl>
							<dt><span>정육점</span></dt>
							<dd>삼겹살데이 등 다양한<br/> 할인 행사 기간에 <br/><span class="halfLine">다양한 디자인</span>으로 홍보를 하니<br/>손님분들도 지겹지 않다고 좋아하시더라고요.</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_05.png"></p>
						<dl>
							<dt><span>안경점</span></dt>
							<dd>문자로만<br/>이벤트 내용을 홍보하는 것보다 스마트전단으로<br/><span class="halfLine">간편하게 제작해서 발송</span>하니<br/>관심도가 높아졌어요!</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_06.png"></p>
						<dl>
							<dt><span>네일</span></dt>
							<dd>이달의네일,<br/>신상 네일 디자인을<br/>알아봐 주시길 기다리는 것보다 <span class="halfLine">직접 홍보</span>하니<br/>고객님들이 오히려<br/> 앞으로 받으시는<br/><span class="halfLine">스마트전단</span>을 기다리시더라고요!</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
				</ul>
				<!-- <ul class="mbox_type_02">
					<li>
						<p><img src="/images/untact/img_mart_07.png"></p>
						<dl>
							<dt><span>마트</span></dt>
							<dd>코로나19 이후 X팡, X마켓 등 온라인 쇼핑이 활성화되면서<br/>
								오프라인 매장 유지에 많은 어려움이 있었어요.<br/><br/>
								그 중 롯X마트 같이 대형 마트들은 마케팅으로<br/>
								고객을 확보하는 것을 보고 우리도 마케팅을 해볼까?<br/>
								고민하던 중 지니를 알게 되어<br/>
								<span class="halfLine">저렴한 비용과 손쉬운 전단 작업</span>으로<br/> 떨어졌던 매출을 다시 올릴 수 있었어요!
							</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_08.png"></p>
						<dl>
							<dt><span>정육점</span></dt>
							<dd>우리 정육점도 할인 행사 이벤트가 많은데,<br/>
								손님들이 모르시는 경우가 많더라고요.<br/><br/>
								찾아와주시길 기다리기만 하면 안 되겠다 싶어서<br/>
								지니를 이용하게 되었어요!<br/>
								<span class="halfLine">다양한 행사를 빠르게 홍보</span>할 수 있어서 너무 좋아요!
							</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
				</ul> -->
				<!-- <ul>
					<li>
						코로나19로 급작스럽게 비대면<br />
						매출을 올리고자 하는 기업
					</li>
					<li>
						비대면 형태로 손쉽게<br />
						스마트한 광고전단이 필요한 기업
					</li>
					<li>
						매출급감으로<br />
						대비가 필요한 업체
					</li>
				</ul> -->
			</div>
		</div>
		<div class="voucher_mbox2 grant_mbox2">
			<div class="content">
				<p class="b_tit1">고객님들이 선호하는 트렌드 홍보로 <b class="halfLine">확실한 매출 보장</b></p>
				<p class="b_tit2"><span class="tag_br">비대면 CRM 고객관리 솔루션 서비스에</span>필요한 모든 기능을 제공합니다.</p>

				<ul class="use_genie">
					<li><img src="/images/untact/img_use_01.png"></li>
					<li><img src="/images/untact/img_use_02.png"></li>
					<li><img src="/images/untact/img_use_03.png"></li>
					<li><img src="/images/untact/img_use_04.png"></li>
				</ul>

				<!-- <div class="b_tit3">
					<p><img src="http://igenie.co.kr/images/untact/untact_icon_card.png"></p>
					<span class="halfLine">전자결제 도입!</span>
				</div>
				<ul class="untact_icon">
					<li>스마트전단</li>
					<li>CS고객관리</li>
					<li>고객분포</li>
					<li>스마트POP</li>
					<li>타겟마켓팅</li>
					<li>통계페이지</li>
					<li>스마트쿠폰</li>
					<li>인쇄&저장&공유</li>
					<li>OPEN API</li>
					<li>회원주문 정보연동</li>
					<li>실시간 대시보드</li>
					<li>상품주문</li>
				</ul> -->
			</div>
		</div>
		<div class="voucher_mbox3 grant_mbox3">
			<div class="content">
				<p class="b_tit1">지니 <span>발송비용 70% 할인</span>으로<br>
				마케팅 비용부담을 덜어내세요!</p>
				<!-- <img src="/images/untact/untact_price.jpg" /> -->

				<table class="untact_price">
					<tr>
						<th rowspan="2">상품가격<br>(세금계산서)</th>
						<th colspan="2">공급가액</th>
						<th rowspan="2">부과세</th>
					</tr>
					<tr>
						<th class="c1">정부지원금<span>70%</span></th>
						<th class="c2">자부담금<span>30%</span></th>
					</tr>
					<tr>
						<td>2,200,000원</td>
						<td class="c1"><em>1,400,000원</em></td>
						<td class="c2"><em>600,000원</em></td>
						<td>200,000원</td>
					</tr>
				</table>
				<span class="untact_price_notice">* 부과세는 추후 국세청을 통해 환급받을 수 있습니다.</span>
				<!-- <a href="/untact/voucher?type=ss" target="_blank" class="notice_txt_02"><img src="/images/untact/notice_txt_02.png"></a> -->
			</div>
		</div>
		<div class="voucher_mbox4 grant_mbox4" style="height:740px;">
			<div class="content">
				<p class="b_tit1"><b>비대면 서비스 바우처</b> 지원 사업 안내</p>
				<div class="voucher_info grant_info">
					<dl>
						<dt>지원대상</dt>
						<dd>
							<p>전국 모든 마트, 정육점 및 소상공인 <span class="tag_br">(1인 사업자, 간이과세자 신청 가능)</span></p>
							<p>
								* 기존고객과 신규고객 모두 신청 가능<br>
								<span class="tag_br">* 20년, 21년, 22년 바우처를 일부라도 지원받은</span><span class="tag_mL8">수요기업은 23년도에 재신청 불가</span><br>
								* 장애인·여성기업은 최대 2회까지 신청(지원) 가능<br>
							</p>
						</dd>
					</dl>
					<dl>
						<dt>지원혜택</dt>
						<dd>
							<p>지니 계약시 사용할 수 있는 <span class="halfLine">최대 220만원</span> <span class="tag_br">(자부담 30% 포함) 이내 바우처를 지급</span></p>
							<p>* 전국 모든 마트, 정육점 및 소상공인 (1인 사업자, 간이과세자 신청 가능)</p>
						</dd>
					</dl>
					<dl>
						<dt>신청기간</dt>
						<dd>
							<ul class="apply_date">
                                <li>(1차) 05.24(수) 09:00 ~ 06.08(목) 18:00 <span class="tag_br"> (07.04 선정기업 발표) </span><em>마감</em></li>
    							<!-- <li>(2차) 07.03(월) 09:00 ~ 07.14(금) 18:00 <span class="tag_br">(7월말 선정기업 발표예정)</span><em>마감</em></li> -->
    							<li>(2차) 08.01(화) 09:00 ~ 08.14(월) 18:00 <span class="tag_br"> (09.11 선정기업 발표) </span><em>마감</em></li>
    							<li class="halfLine">(3차) 11.27(월) 09:00 ~ 12.11(월) 18:00 <span class="tag_br"> (24년 1월초 선정기업 발표예정) </span><em>진행중</em></li>
                            </ul>
							<p class="apply_date_txt">
                                * <em>예산 소진 시 신청 조기마감</em> 예정으로, <span class="tag_br tag_mL8"><em>다음 차수 신청이 생략</em>될 수 있으며 마감 시 플랫폼에</span><span class="tag_mL8">게시하여 안내할 예정</span>
                                <span class="tag_br tag_mL8">단, 사업포기자 등이 발생한 경우 2,3차 접수가 재개될 수 있음</span>
                            </p>
				        </dd>
					</dl>
					<dl>
						<dt>신청방법</dt>
						<dd>
							<p>아래의 신청폼을 제출해 주시면 신속히 <span class="tag_br">지원 신청 안내 드리겠습니다.</span></p>
							<p>* 해당 사업에 대한 자세한 내용은 이곳을 통해<span class="tag_br tag_mL8">확인하실 수 있습니다.</span></p>
						</dd>
					</dl>
				</div>
			</div>
		</div>
		<div class="voucher_mbox5 grant_mbox5" id="voucher">
			<div class="content">
				<p class="b_tit1">지니 <b>발송비용</b> <span class="halfLine">70%</span>를 지원받으세요.</p>
				<form id="voucher-form" action="/untact/save" method="post">
				<div class="voucher_form grant_form">
					<ul>
						<!-- <li class="choice_add">
							<span>희망상품(*)</span>
							<div>
								<input type="checkbox" name="choice_add_v" id="choice_add_v">
								<label for="choice_add_v">K비대면 바우처</label>
								<input type="checkbox" name="choice_add_s" id="choice_add_s">
								<label for="choice_add_s">스마트상점</label>
								<a href="/untact/voucher?type=ss" target="_blank">스마트상점 안내</a>
							</div>
						</li> -->
						<li>
							<span>이름(*)</span> <input type="text" id="user_name" name="user_name" placeholder="이름" class="">
						</li>
						<li>
							<span>전화번호(*)</span> <input type="text" id="tel" name="tel" placeholder="전화번호" class="" onKeyup="this.value=this.value.replace(/[^-0-9]/g,'');">
						</li>
						<li>
							<span>이메일</span> <input type="text" id="email" name="email" placeholder="이메일" class="">
						</li>
						<li>
							<span>업체명(*)</span> <input type="text" id="company_name" name="company_name" placeholder="업체명" class="">
						</li>
						<!-- <li>
							<span>업종(*)</span>
                            <select id="sector" name="sector">
								<option value="" selected="">업종선택</option>
                            <?
                                foreach(config_item('sectors') as $key => $a){
                            ?>
                                <option value="<?=$key?>"><?=$a?></option>
                            <?
                                }
                            ?>
							</select>
						</li> -->
						<li>
							<span>문의내용</span>
							<textarea id="content" name="content" cols="100" rows="6" placeholder="문의내용"></textarea>
						</li>
					</ul>
					<input type="hidden" name="state" value="신청">
				</div>
				<p>
					<button id="voucher_submit_btn" class="grant_apply_btn">비대면 서비스 바우처 지원사업 신청하기</button>
				</p>
				</form>
			</div>
		</div>
		<p class="btn_apply">
			<a href="#voucher">신청하기</a>
		</p>
	</div>
	<!--// 비대면 바우처 -->

	<!-- 스마트상점 -->
	<div class="smartMall_wrap" style="display:none;">
		<div class="smartMall_visual grant_visual">
			<div class="smartMall_slogan">
				<p class="tst1 wow fadeInUp">
					1인 사업자포함 소상공인이라면<br />
					최대 <span>500만원 지원</span>받고<br />
					풀케어 서비스를 이용하세요!
				</p>
				<p class="tst2 wow fadeInUp">
					중소벤처기업부와 소상공인시장진흥공단의<br />
					'2023년 스마트상점 기술보급사업'으로 선정되어,<br />
					국비지원을 통해 지원하게 되었습니다.
				</p>
				<!-- <p class="tst3 wow fadeInUp">
					<a href="#smartMall">신청하기</a>
				</p> -->
				<!-- <p class="tst4 wow fadeInUp">
					~ 05.14(일) 18시, 1차마감<br>
                    2차~13차까지 모집 예정
				</p>
				<p class="tst5 wow fadeInUp">
					<img src="/images/untact/untact_img_03.png">
				</p> -->
				<!-- <p class="tst6 wow fadeInUp">
                    asdf
				</p> -->
				<p id="demo" class="wow fadeInUp"></p>
<script>
var startDate = new Date('May 14, 2023 18:00:00');

var x = setInterval(function() {

    var now = new Date().getTime();

    var distance = startDate - now;

    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    if (distance > 0) {
        // $('.tst6').html(days + "일 " + hours + "시간 " + minutes + "분 " + seconds + "초 남음");
		document.getElementById("demo").innerHTML = "<span class='cd_day'>" + days + "</span>" + "일" + "<span class='cd_hours'>" + hours + "</span>" + "시간"
		+ "<span class='cd_minutes'>" + minutes + "</span>" + "분" + "<span class='cd_seconds'>" + seconds + "</span>" + "초 남음";
    } else {
        clearInterval(x);
        $('#demo').css('display', 'none');
    }
}, 100);
</script>
			</div>
		</div>
        <div class="process_wrap">
            <div class="content">
                <p class="b_tit1">K비대면 바우처 & 스마트상점 <b class="halfLine">신청 절차</b></p>
                <p class="b_tit2"><span class="tag_br">지니 발송비용과 스마트전단 풀케어를</span>70% 할인 받을 수 있는 스마트한 신청 방법!</p>

                <p class="process_m"><img src="/images/untact/img_process_01.jpg" alt="신청절차"></p>
                <div class="process">
                    <p>1인 사업자 포함<br>소상공인이라면<br><b class="halfLine">누구나!</b></p>
                    <ul>
    					<li onclick="window.open('/untact/voucher#voucher')"><b class="halfLine">K비대면 바우처 신청</b>심사진행 및<br>지원대상자 확정</li>
    					<li>결제신청</li>
    					<li>계약 승인<br>유무 확인</li>
    					<li>지니 발송비용<br><b class="halfLine">70% 할인!!</b></li>
                    </ul>
                    <ul>
                        <li onclick="window.open('/untact/voucher?type=ss#smartMall_product')"><b class="halfLine">스마트상점 신청</b>중점기술<br>제품구매 신청</li>
    					<li>계약 체결 및<br>자부담금 입금</li>
    					<li>계약 승인<br>유무 확인</li>
    					<li>지니 플랫폼<br>이용금액<br><b class="halfLine">70% 할인!!</b></li>
    				</ul>
                    <a href="#smartMall_product" target="_blank" class="notice_txt_03"><img src="/images/untact/notice_txt_03.png"></a>
                    <a href="#smartMall" target="_blank" class="notice_txt_04"><img src="/images/untact/notice_txt_04.png"></a>
                </div>
            </div>
        </div>
		<div class="smartMall_mbox1 grant_mbox1">
			<div class="content">
				<p class="b_tit1">지니 <b>스마트전단 풀케어</b> 는 어떤 수요업체가 적합한가요?</p>
				<p class="b_tit2">손쉽게 스마트한 광고 전단 제작을 원하는 <span class="halfLine">모든 업체!</span> 이번 기회를 통해 지니 스마트 전단 풀케어 서비스를 이용해보세요!</p>
				<!-- <ul class="mbox_type_01">
					<li>
						디자인&발송 작업 할 <br />
						시간, 인력이 필요한 기업
					</li>
					<li>
						코로나19로 급작스럽게 비대면<br />
						매출을 올리고자 하는 기업
					</li>
					<li>
						비대면 형태로 손쉽게<br />
						스마트한 광고전단이 필요한 기업
					</li>
					<li>
						매출급감으로<br />
						대비가 필요한 업체
					</li>
				</ul> -->
				<ul class="mbox_type_02">
					<li>
						<p><img src="/images/untact/img_mart_01.png"></p>
						<dl>
							<dt><span>마트</span></dt>
							<dd>코로나 이후<br/>온라인 주문에<br/>익숙해진 고객님들께<br/>저희 매장도<br/><span class="halfLine">온라인으로 홍보</span>하고<br/><span class="halfLine">직접 주문 & 배송</span>까지 하니, 매출이 쑥쑥<br/>오르더라고요!</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_02.png"></p>
						<dl>
							<dt><span>미용실</span></dt>
							<dd>고객님들께<br/>현장에서 설명드리면<br/>고민하시다 구매로 이어지는 경우는 적은데,<br/><br/><span class="halfLine">스마트전단</span>으로<br/>한 번 더 홍보하면<br/><span class="halfLine">구매율</span>이 높아지더라고요!</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_03.png"></p>
						<dl>
							<dt><span>카페</span></dt>
							<dd>오픈 이벤트 메뉴를<br/>홍보 디자인 전문가에게 맡겨야 하나 고민하고 있었는데<br/><span class="halfLine">금전, 소요되는 시간</span>이 부담스러웠어요.<br/><br/>
							하지만<br/><span class="halfLine">스마트전단</span>으로<br/>간단하게 제작하여 홍보할 수 있어서<br/>너무 편해요!</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_04.png"></p>
						<dl>
							<dt><span>정육점</span></dt>
							<dd>삼겹살데이 등 다양한<br/> 할인 행사 기간에 <br/><span class="halfLine">다양한 디자인</span>으로 홍보를 하니<br/>손님분들도 지겹지 않다고 좋아하시더라고요.</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_05.png"></p>
						<dl>
							<dt><span>안경점</span></dt>
							<dd>문자로만<br/>이벤트 내용을 홍보하는 것보다 스마트전단으로<br/><span class="halfLine">간편하게 제작해서 발송</span>하니<br/>관심도가 높아졌어요!</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
					<li>
						<p><img src="/images/untact/img_mart_06.png"></p>
						<dl>
							<dt><span>네일</span></dt>
							<dd>이달의네일,<br/>신상 네일 디자인을<br/>알아봐 주시길 기다리는 것보다 <span class="halfLine">직접 홍보</span>하니<br/>고객님들이 오히려<br/> 앞으로 받으시는<br/><span class="halfLine">스마트전단</span>을 기다리시더라고요!</dd>
						</dl>
						<div class="clippy_wrap"><p class="clippy"></p></div>
					</li>
				</ul>
			</div>
		</div>
		<div class="smartMall_mbox2 grant_mbox2">
			<div class="content">
				<p class="b_tit1"><b>풀케어 서비스</b>란? </p>
				<p class="b_tit2">고객에게 보다 신뢰성 있는 홍보를 위해<span class="tag_br">고퀄리티의 이미지 제작과 효과적인 발송 패턴을 분석하여</span>발송까지 꼼꼼하게 챙겨드립니다.</p>
				<p class="b_tit4"><span class="halfLine">정말 아무것도 모르셔도 시작하실 수 있습니다!</span></p>

				<div class="b_tit3">
					<p><img src="/images/untact/untact_img_01.png"></p>
					<ul class="untact_text">
						<li><span class="material-icons">check</span>채널 생성 및 비즈니스 인증</li>
						<li><span class="material-icons">check</span>모바일 전단, 쿠폰 무한 제작</li>
						<li><span class="material-icons">check</span>배너 디자인 대행</li>
						<li><span class="material-icons">check</span>채널 게시물 포스팅 및 유지 관리</li>
						<li><span class="material-icons">check</span>메시지 발송 대행</li>
					</ul>
				</div>

				<p class="b_tit1"><b>자기주도형 서비스</b>란? </p>
				<p class="b_tit2">
					사용자 혼자서 DIY 형태로 "스마트 전단" 기능을 이용하여<span class="tag_br">정말 쉽고 간편하게 제작이 가능하기에,</span>
					홍보하고자 하는 고객에게 직접 발송하는 서비스
				</p>

				<!-- <ul class="untact_icon">
					<li>스마트전단</li>
					<li>CS고객관리</li>
					<li>고객분포</li>
					<li>스마트POP</li>
					<li>타겟마켓팅</li>
					<li>통계페이지</li>
					<li>스마트쿠폰</li>
					<li>인쇄&저장&공유</li>
					<li>OPEN API</li>
					<li>회원주문 정보연동</li>
					<li>실시간 대시보드</li>
					<li>상품주문</li>
					<li>전자결제도입</li>
				</ul> -->
			</div>
		</div>
		<div class="smartMall_mbox3 grant_mbox3">
			<div class="content">
				<p class="b_tit1">지니 <b>풀케어</b> 이용요금 <span class="halfLine">70% 할인</span>으로<br>
				마케팅 비용부담을 덜어내세요!</p>

				<figure class="img_01">
					<img src="/images/untact/untact_img_02.jpg">
				</figure>

				<table class="untact_price">
					<tr class="care_type">
						<th style="width:20%">구분</th>
						<th style="width:19%" class="c2">자기주도형 <b>DIY</b></th>
						<th style="width:20%" class="c2">풀케어 <span>A</span></th>
						<th style="width:20%" class="c2">풀케어 <span>B</span></th>
						<th style="width:21%" class="c2">풀케어 <span>C</span></th>
					</tr>
					<tr>
						<td>디자인<br>제작횟수</td>
						<td>자체제작</td>
						<td>월 <b>4</b> 회 / <small>주 1회</small></td>
						<td>월 <b>8</b> 회 / <small>주 2회</small></td>
						<td>월 <b>12</b> 회 / <small>주 3회</small></td>
					</tr>
					<tr>
						<td>풀케어<br>제공<br>서비스</td>
						<td>자체제작</td>
						<td colspan="3">
							<ul class="fullCare_text">
								<li>이미지품목 24개 제작</li>
								<li>텍스트 목록 무제한 제공</li>
								<li>쿠폰 디자인무료 제작</li>
								<li>메인 디자인 무료 제작</li>
								<li>디자인 템플릿 제공</li>
								<li>인쇄 전단 업로드 대행</li>
							</ul>
						</td>
					</tr>
					<tr class="c_price">
						<td>정상가<br><em class="p1">지원금</em><br><em class="p2">자부담금</em></td>
						<td>업체문의</td>
						<td>2,376,000원<br><em class="p1">-1,663,200원</em><br><em class="p2">712,800원</em></td>
						<td>3,960,000‬원<br><em class="p1">-2,772,000‬원</em><br><em class="p2">1,188,000‬원</em></td>
						<td>5,280,000‬원<br><em class="p1">-3,696,000‬원</em><br><em class="p2">1,584,000‬원</em></td>
					</tr>
				</table>
				<span class="untact_price_notice">※ VAT포함 (부가세는 할인적용 금액이 아닌 정상가 기준으로 적용)</span>
				<a href="/untact/voucher" target="_blank" class="notice_txt_01"><img src="/images/untact/notice_txt_01.png"></a>
			</div>
		</div>
		<div class="smartMall_mbox4 grant_mbox4">
			<div class="content">
				<p class="b_tit1"><b>스마트상점 풀케어 서비스</b> 지원 사업 안내</p>
				<div class="smartMall_info grant_info">
					<dl>
						<dt>지원대상</dt>
						<dd>
							<p>전국 모든 소상공인</p>
							<p>
								* [소상공인 기본법] 제2조에 따른 소상공인<br>
								* 신청일 현재 정상적으로 영업 중인 점포<br>
								* 20~22년 스마트 기술보급사업 기지원 상점은<span class="tag_br tag_mL8">재신청 불가</span>
							</p>
						</dd>
					</dl>
					<dl>
						<dt>지원혜택</dt>
						<dd>
							<p>스마트전단 풀케어 계약시 <span class="halfLine">상품가의 70%</span><span class="tag_br">(공급가액 기준) 지원받을 수 있습니다.</span></p>
							<p>
								* 전국 모든 소상공인<br>
								* 취약계층 국비 80%(공급가액 기준) 지원<br>
								* 취약계층 : 간이과세자, 1인 사업장, 장애인(기업) 中<span class="tag_br tag_mL8">1개만 해당해도 적용 가능</span>
							</p>
						</dd>
					</dl>
					<dl>
						<dt>신청기간</dt>
						<dd>
							<p>2023.04.17(월) 09:00 ~ <span class="halfLine">05.14(일) 18:00</span> <span class="tag_br"><em>(선착순 접수)</em></span></p>
							<p>* 해당 사업은 선착순 접수로 조기 마감될 수 있습니다.</p>
						</dd>
					</dl>
					<dl>
						<dt>신청방법</dt>
						<dd>
							<p>아래의 신청폼을 제출해 주시면 신속히 <span class="tag_br">지원 신청 안내 드리겠습니다.</span></p>
							<p>* 해당 사업에 대한 자세한 내용은 이곳을 통해<span class="tag_br tag_mL8">확인하실 수 있습니다.</span></p>
						</dd>
					</dl>
				</div>
			</div>
		</div>
		<div class="smartMall_mbox6 grant_mbox6" id="smartMall_product">
			<div class="content">
				<p class="b_tit1">지니 풀케어는 <b>[스마트상점 중점기술]</b> 신청이 <span class="halfLine">필수</span>입니다.</p>
				<figure><img src="/images/untact/untact_img_04.jpg"></figure>

				<div class="grant_notice">
					<!-- <a href="http://jhin.co.kr/d" target="_blank" class="btn_recomend">추천 중점기술 신청하기</a> -->
					<a href="https://www.sbiz.or.kr/smst/bsns/product/list.do?key=2111306033994" target="_blank">중점기술 더보러가기</a>
				</div>
			</div>
		</div>
		<!-- <div class="smartMall_mbox5 grant_mbox5" id="smartMall">
			<div class="content">
				<p class="b_tit1">지니 <b>풀케어</b> <span class="halfLine">최대 500만원</span> 지원 받으세요.</p>
				<form id="smartMall-form" action="/untact/ss_save" method="post">
				<div class="smartMall_form grant_form">
					<ul>
						<li class="choice_add">
							<span>희망상품(*)</span>
							<div>
								<input type="checkbox" name="choice_add_v" id="choice_add_v2">
								<label for="choice_add_v2">K비대면 바우처</label>
								<input type="checkbox" name="choice_add_s" id="choice_add_s2">
								<label for="choice_add_s2">스마트상점</label>
								<a href="/untact/voucher" target="_blank">K비대면 바우처 안내</a>
							</div>
						</li>
						<li>
							<span>이름(*)</span> <input type="text" id="user_name2" name="user_name" placeholder="이름" class="">
						</li>
						<li>
							<span>전화번호(*)</span> <input type="text" id="tel2" name="tel" placeholder="전화번호" class="" onKeyup="this.value=this.value.replace(/[^-0-9]/g,'');">
						</li>
						<li>
							<span>이메일</span> <input type="text" id="email2" name="email" placeholder="이메일" class="">
						</li>
						<li>
							<span>업체명(*)</span> <input type="text" id="company_name2" name="company_name" placeholder="업체명" class="">
						</li>
						<li>
							<span>업종(*)</span>
                            <select id="sector" name="sector">
								<option value="" selected="">업종선택</option>
                            <?
                                foreach(config_item('sectors') as $key => $a){
                            ?>
                                <option value="<?=$key?>"><?=$a?></option>
                            <?
                                }
                            ?>
							</select>
						</li>
						<li>
							<span>추천업체명<br>(추천인)</span> <input type="text" id="" name="" placeholder="추천업체명 or 추천인" class="">
						</li>
						<li>
							<span>문의내용</span>
							<textarea id="content" name="content" cols="100" rows="6" placeholder="문의내용"></textarea>
						</li>
					</ul>
					<input type="hidden" name="state" value="신청">
				</div>
				<p>
					<button id="smartMall_submit_btn" class="grant_apply_btn">스마트상점 지원사업 신청하기</button>
				</p>
				</form>
			</div>
		</div>
		<p class="btn_apply">
			<a href="#smartMall">신청하기</a>
		</p> -->
	</div>
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
$(function() {

	$('#voucher_submit_btn').click(function(){
		if($('#user_name').val() == ''){
			alert('이름 입력해주세요.');
			$('#user_name').focus();
			return false;
		}
		if($('#tel').val() == ''){
			alert('전화번호를 입력해주세요.');
			$('#tel').focus();
			return false;
		}
		if($('#company_name').val() == ''){
			alert('업체명을 입력해주세요.');
			$('#company_name').focus();
			return false;
		}
		// if($('#customer_type').val() == 'ALL'){
		// 	alert('고객유형을 선택해주세요.');
		// 	$('#customer_type').focus();
		// 	return false;
		// }
		return true;
	});

    $('#smartMall_submit_btn').click(function(){
		if($('#user_name2').val() == ''){
			alert('이름 입력해주세요.');
			$('#user_name2').focus();
			return false;
		}
		if($('#tel2').val() == ''){
			alert('전화번호를 입력해주세요.');
			$('#tel2').focus();
			return false;
		}
		if($('#company_name2').val() == ''){
			alert('업체명을 입력해주세요.');
			$('#company_name2').focus();
			return false;
		}
		if($('#customer_type2').val() == 'ALL'){
			alert('고객유형을 선택해주세요.');
			$('#customer_type2').focus();
			return false;
		}
		return true;
	});
});

// 비대면바우처 스마트상점 탭
$(function() {
    $('.tab01').addClass('on');
    $('.tab_icon_02').hide();
    $('.smartMall_wrap').hide();

    $('.tab01').click(function(){
        $('.tab01').addClass('on');
        $('.tab02').removeClass('on');
        $('.voucher_wrap').show();
    	$('.smartMall_wrap').hide();
    	$('.tab_notice_01').show();
    	$('.tab_notice_02').hide();
    	$('.tab_icon_01').show();
    	$('.tab_icon_02').hide();
    });
    $('.tab02').click(function(){
        $('.tab01').removeClass('on');
        $('.tab02').addClass('on');
        $('.voucher_wrap').hide();
    	$('.smartMall_wrap').show();
    	$('.tab_notice_01').hide();
    	$('.tab_notice_02').show();
    	$('.tab_icon_01').hide();
    	$('.tab_icon_02').show();
    });

    // 모바일 접속시
    // if ($(window).width() < 480){
    //     $('.grant_wrap').addClass('grant_wrap_m');
    // }

    if ('<?=$this->input->get('type')?>' == 'ss'){
        $('.tab02').trigger('click');
    }
});

</script>
