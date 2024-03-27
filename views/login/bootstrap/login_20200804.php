<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
		<div class="container">
				<div class="login_header">
					<a href="/"><img src="/img/logo/logo_o2o_dark.svg"><span><b>지니</b>파트너센터</span></a>
					<div class="sign_top">
						<span class="sign_text">아직 파트너계정이 없으신가요?</span>
						<a href="/register"><button type="button" class="btn_sign">회원가입</button></a>
					</div>
				</div>
				<div class="login_body">
					<div class="login_container">
						<div class="login_wrap">
							<div class="login_field">
								<div class="desc">
									<h1>가장 확실한 홍보가 필요하세요?<br><b>지니</b>로 시작해 보세요!</h1>
									<p>카카오톡을 이용한 알림톡, 친구톡 및 문자전송 서비스입니다.</p>
								</div>
					            <?php
			                    echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
			                    echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			                    echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			                    $attributes = array('class' => 'form-horizontal', 'name' => 'flogin', 'id' => 'flogin');
			                    echo form_open(current_full_url(), $attributes);
			                    ?>
								<input type="hidden" name="csrf-token" content="{{csrf_token()}}"/>
					            <input type="text" name="mem_userid" value="<?php echo set_value('mem_userid'); ?>" accesskey="L" onkeyup="fn_press_han(this)" placeholder="아이디">
					            <input type="password" name="mem_password" placeholder="비밀번호">
					            <input type="checkbox" name="autologin" id="autologin" value="1" /><label for="autologin">아이디 저장</label>
					            <button type="submit" label="로그인하기" class="btn_login">로그인</button>
								<?php echo form_close(); ?>
								<div class="divider">서비스 링크</div>
								<div class="service_wrap">
									<div class="banner cs" onclick="window.open('http://www.367.co.kr')"><i class="feather-24" data-feather="monitor"></i>원격 지원 서비스</div>
									<div class="banner kakaofriends" onclick="window.open('https://pf.kakao.com/_xlKcgC')"><img src="../images/2019/icon_kakaotalk.png" width="20px;" style="vertical-align: middle;">DHN 카카오 채널</div>
									<div class="banner blog" onclick="window.open('https://blog.naver.com/sjk4556')"><strong>N</strong> 네이버블로그</div>
								</div>
								<!--div class="divider">꼭! 확인하세요.</div-->
							</div>
						</div>
						<div class="login_banner">
								<h2>카카오톡 채널<br>for Business</h2>
								<p>세상의 모든 비즈니스를 완성하는 카카오톡 채널.</p>
								<p class="call">1522-7985</p>
						</div>
					</div>
				</div>
		</div>
<script type="text/javascript">
//<![CDATA[
    $(function() {
        $('#flogin').validate({
            rules: {
                mem_userid : { required:true, minlength:3 },
                mem_password : { required:true, minlength:4 }
            }
        });
    });

    function fn_press_han(obj)
    {
      if(event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39
      || event.keyCode == 46 ) return;
      obj.value = obj.value.replace(/[\ㄱ-ㅎㅏ-ㅣ가-힣]/g, '');
    }
//]]>
</script>
