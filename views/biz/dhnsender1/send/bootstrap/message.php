		  <meta http-equiv="Expires" content="0">
        <meta http-equiv="Pragma" content="no-cache">
    <script type="text/javascript" src="/js/plugins/moment-with-locales.js"></script>
<script type="text/javascript">
<!--
	var edit_control = "templi_cont";
//-->
</script>
<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					메시지 작성하기
				</div>
<!-- 타이틀 영역 END -->
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_content">
							<div class="msg_inner">
								<div class="msg_banner"><!--img src="../../../img/msg_title.jpg"-->
									<strong>고객을 부르는 카카오 마트톡!</strong>
									<p>마케팅을 아무리해도 접객이 별로면 "제로"다!</p>
								</div>
								<div class="msg_main">
									<div class="msg_select friendtalk" onclick="location.href='/dhnbiz/sender/send/friend'">
										<div class="type_info">
										<h3>친구톡(일반 이미지형)</h3>
										<p>- 다양한 사이즈의 이미지 전송
										<br>- 텍스트 최대 1,000자까지 작성 가능
										<br>- 버튼링크 최대 5개 지원
										<br>- 친구톡 발송 실패시 2차문자 발송 가능</p>
										</div>
										<button class="btn btn_msg_select">메시지 작성</button>
									</div>
									<? if ($this->member->item('mem_userid') != 'dhn_07') { ?>
									<div class="msg_select friendtalk_w" onclick="location.href='/dhnbiz/sender/send/friend_wide'">
									<? } else {?>
									<div class="msg_select friendtalk_w">
									<? } ?>
										<div class="type_info">
										<h3>친구톡(와이드 이미지형)</h3>
										<p>- 텍스트 최대 76자까지 가능
										<br>- 대표이미지 사이즈 고정(800*600)
										<br>- 버튼링크 최대 1개 지원
										<br>- 친구톡 발송 실패시 2차문자 발송 가능</p>
										</div>
										<button class="btn btn_msg_select">메시지 작성</button>
									</div>
								</div>
								
								<div class="msg_main">
									<div class="msg_select alimtalk" onclick="location.href='/dhnbiz/sender/send/talk'">
										<div class="type_info">
										<h3>알림톡 발송</h3>
										<p>핸드폰 전화번호를 기반으로 친구추가 없이 카카오톡 앱을 통해 정보성 메시지를 고객에게 바로 보내는 정보형 비즈 메시지입니다.</p>
										</div>
										<div class="view_msg">
											
										</div>
										<button class="btn btn_msg_select">알림톡 작성</button>
									</div>
									
									<div class="msg_select sms" onclick="location.href='/dhnbiz/sender/send/lms'">
										<h3>문자 발송</h3>
										<p>SMS, LMS등 문자메시지를 전송합니다.</p>
										<button class="btn btn_msg_select">문자 작성</button>
									</div>
								</div>
								
								<div class="msg_main">
									<!--div class="msg_select msg_coupon" onclick="location.href='/dhnbiz/sender/send/coupon'">
										<h3>타겟고객 쿠폰 발송</h3>
										<p>쿠폰을 알림톡으로 발송할 수 있습니다.</p>
										<button class="btn btn_msg_select">쿠폰 작성</button>
									</div-->
									<? if ($this->member->item('mem_userid') != 'dhn_07') { ?>
									<div class="msg_select collect_select" onclick="location.href='/dhnbiz/sender/send/talk/nft_talk'">
									<? } else  { ?>
									<div class="msg_select collect_select">
									<? } ?>
										<h3>친구모으기</h3>
										<p>내 고객중 아직 친구등록이 안된 고객에게만<br>자동으로 알림톡을 보내 친구등록을 유도합니다.</p>
										<!--button class="btn btn_msg_select">친구모으기 바로가기</button-->
									</div>	
									<? if ($this->member->item('mem_userid') != 'dhn_07') { ?>
									<div class="msg_select delivery_msg" onclick="location.href='/dhnbiz/sender/send/talk_m'">
									<? } else  { ?>
									<div class="msg_select delivery_msg">
									<? } ?>
									
										<h3>모바일 배송알림톡</h3>
										<p>배송관련 알림톡을 모바일에서 간편하게 보낼수 있습니다.</p>
										<div class="ani_car"><img src="../../../img/delivery_bg.png"></div>
										
										<!--button class="btn btn_msg_select">모바일 배송알림톡 바로가기</button-->
									</div>
								</div>
							</div>
						</div>
					</div>

				</div><!-- mArticle END -->