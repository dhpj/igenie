<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
		<div class="container">
				<div class="login_header">
					<a href="/"><img src="/img/logo/logo_o2o_dark.svg"><span><b>오투오메시지</b>파트너센터</span></a>
					<div class="sign_top">
						<span class="sign_text">아직 파트너계정이 없으신가요?</span>
						<a href="/register"><button type="button" class="btn_sign">회원가입</button></a>
					</div>
				</div>
				<div class="join_title">회원가입</div>
				<div class="join_body">
					<div class="join_container">
						<div class="join_wrap">
							<div class="login_field">
								<div class="desc">
									<h1>10초만에 회원가입하고, 알림톡을 보내보세요!</h1>
									<p>카카오톡을 이용한 알림톡, 친구톡 및 문자전송 서비스입니다.</p>
								</div>
							    <?php
							    echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
							    echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
							    $attributes = array('class' => 'form-horizontal', 'name' => 'fregisterform', 'id' => 'fregisterform');
							    echo form_open(current_full_url(), $attributes);
							    ?>
							    <div style="display: flex; margin: 0 -10px;">
							        <input type="hidden" name="register" value="1" />
							        <div style="flex: 1; margin: 0 10px;">
							                <p>회원가입약관</p>
							                <textarea class="form-control" rows="6" readonly="readonly"><?php echo html_escape(element('member_register_policy1', $view)); ?></textarea>
					                        <div class="checkbox">
						                        <label for="agree">
						                            <input type="checkbox" name="agree" id="agree" value="1" /> 회원가입약관의 내용에 동의합니다.
						                        </label>
					                        </div>
							        </div>
							        <div style="flex: 1; margin: 0 10px;">
							                <p>개인정보취급방침안내</p>
							                <textarea class="form-control" rows="6" readonly="readonly"><?php echo html_escape(element('member_register_policy2', $view)); ?></textarea>
					                        <div class="checkbox">
						                        <label for="agree2">
						                            <input type="checkbox" name="agree2" id="agree2" value="1" /> 개인정보취급방침안내의 내용에 동의합니다.
						                        </label>
					                        </div>
							        </div>
							    </div>
							<?php if ($this->cbconfig->item('use_selfcert') && ($this->cbconfig->item('use_selfcert_phone') OR $this->cbconfig->item('use_selfcert_ipin'))) { ?>
							                <label class="control-label">본인인증 선택</label>
							                        <input type="hidden" name="selfcert_type" id="selfcert_type" value="" />
							                        <?php if ($this->cbconfig->item('use_selfcert_phone')) { ?>
							                            <button type="button" class="btn btn-warning btn-sm" name="mem_selfcert" id="btn_mem_selfcert_phone">휴대폰인증</button>
							                        <?php } ?>
							                        <?php if ($this->cbconfig->item('use_selfcert_ipin')) { ?>
							                            <button type="button" class="btn btn-primary btn-sm" name="mem_selfcert" id="btn_mem_selfcert_ipin">아이핀인증</button>
							                        <?php } ?>
							<?php } ?>
													<button type="submit" label="로그인하기" class="btn_login">오투오메시지 시작하기</button>
							    <?php echo form_close(); ?>
							
							<?php if ($this->cbconfig->item('use_selfcert') && ($this->cbconfig->item('use_selfcert_phone') OR $this->cbconfig->item('use_selfcert_ipin'))) {
							    $this->managelayout->add_js(base_url('assets/js/member_selfcert.js'));
							} ?>
					            
								<div class="divider">서비스 링크</div>
								<div class="service_wrap">
									<div class="banner cs" onclick="window.open('http://www.367.co.kr')"><i class="feather-24" data-feather="monitor"></i>원격 지원 서비스</div>
									<div class="banner kakaofriends" onclick="window.open('https://pf.kakao.com/_xlKcgC')">DHN 카카오 채널</div>
								</div>
								<!--div class="divider">꼭! 확인하세요.</div-->
							</div>
						</div>
						<!--div class="login_banner">
								<h2>카카오톡 채널<br>for Business</h2>
								<p>세상의 모든 비즈니스를 완성하는 카카오톡 채널.</p>
								<p class="call">1522-7985</p>
						</div-->
					</div>
				</div>
		</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fregisterform').validate({
        rules: {
            agree: {required :true},
            agree2: {required :true}
<?php if ($this->cbconfig->item('use_selfcert') && ($this->cbconfig->item('use_selfcert_phone') OR $this->cbconfig->item('use_selfcert_ipin')) && $this->cbconfig->item('use_selfcert_required')) { ?>
            , selfcert_type: {required :true}
        }
        , messages: {
            selfcert_type: "본인인증 후 회원가입이 가능합니다"
<?php } ?>
        }
    });
});
//]]>
</script>
