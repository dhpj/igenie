<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

	<div class="login_wrap">

			<div class="logo">
				<img src="/dhn/images/logo_v2.png">
				<p class="logo_txt">AI 스마트 매장관리시스템</p>
			</div>

			<div class="login_field">
			<?php
			echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
			echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-horizontal', 'name' => 'flogin', 'id' => 'flogin');
			echo form_open(current_full_url(), $attributes);
			?>
			<input type="hidden" name="url" value="<?php echo html_escape($this->input->get_post('url')); ?>" />
	            <input type="text" name="mem_userid" value="<?php echo set_value('mem_userid'); ?>" accesskey="L" placeholder="아이디 또는 이메일"/>
	            <input type="password" name="mem_password"  placeholder="비밀번호"/>
	            <div class="autologin">
		            <input type="checkbox" name="autologin" id="autologin" value="1" />
		            <label for="autologin">자동로그인</label>
					<!--div class="autologinalert" style="display:none;">
						자동로그인 기능을 사용하시면, 브라우저를 닫더라도 로그인이 계속 유지될 수 있습니다. 자동로그이 기능을 사용할 경우 다음 접속부터는 로그인할 필요가 없습니다. 단, 공공장소에서 이용 시 개인정보가 유출될 수 있으니 꼭 로그아웃을 해주세요.
					</div-->
	            </div>
	            <button type="submit" label="로그인하기" class="btn_login">로그인</button>
				<!--div class="divider">또는</div>
			<?php echo form_close(); ?>
				<div class="join_link">
			<?php
			if ($this->cbconfig->item('use_sociallogin')) {
				$this->managelayout->add_js(base_url('assets/js/social_login.js'));
			?>
						<?php if ($this->cbconfig->item('use_sociallogin_facebook')) {?>
							<a href="javascript:;" onClick="social_connect_on('facebook');" title="페이스북 로그인"><img src="<?php echo base_url('assets/images/social_facebook.png'); ?>" width="22" height="22" alt="페이스북 로그인" title="페이스북 로그인" /></a>
						<?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_twitter')) {?>
							<a href="javascript:;" onClick="social_connect_on('twitter');" title="트위터 로그인"><img src="<?php echo base_url('assets/images/social_twitter.png'); ?>" width="22" height="22" alt="트위터 로그인" title="트위터 로그인" /></a>
						<?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_google')) {?>
							<a href="javascript:;" onClick="social_connect_on('google');" title="구글 로그인"><img src="<?php echo base_url('assets/images/social_google.png'); ?>" width="22" height="22" alt="구글 로그인" title="구글 로그인" /></a>
						<?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_naver')) {?>
							<a href="javascript:;" onClick="social_connect_on('naver');" title="네이버 로그인"><img src="<?php echo base_url('assets/images/social_naver.png'); ?>" width="22" height="22" alt="네이버 로그인" title="네이버 로그인" /></a>
						<?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_kakao')) {?>
							<a href="javascript:;" onClick="social_connect_on('kakao');" title="카카오 로그인"><button class="join_kakao">카카오계정으로 로그인</button></a>
						<?php } ?>
			<?php } ?>
				</div-->
				<!--<div class="join_link">
					<a href="<?php echo site_url('register'); ?>">회원가입</a><a href="<?php echo site_url('findaccount'); ?>">아이디/패스워드 찾기</a>
				</div>-->
			</div>
            <p class="nicedocu" style="margin-top:20px; text-align:center; width:100%;"><a href="/login/nicedocu" target="_blank" style="width:450px; display: inline-block; margin-bottom:20px; border-radius: 3px;border: 1px #404040 solid; font-size: 15px; font-weight: 700;  padding: 15px 0;">카카오공식딜러사 진위여부 확인</a></p>
	</div>

	<!-- <div class="smart_mart_img" style="width:350px;margin:0 auto 20px;">
		<img src="./images/smart_mart_img.png"  style="width:370px;"/>
	</div> -->

	<div class="login_btxt">
		<p>
			* 본 사이트는 <span>구글 크롬, 네이버 웨일 브라우저</span>에 최적화되어 있습니다.
		</p>
		<p>
			<a href="https://www.google.com/chrome/" target="_blank">크롬 설치하기</a> <a href="https://whale.naver.com/" target="_blank">웨일 설치하기</a>
		</p>
	</div>


	<div class="access"></div>

	<div id="parent_dormancy" class="<?=$dormancy == 1 ? "bg_black" : ""?>"></div>
	<!-- <div id="parent_dormancy" class=""></div> -->
	<div id="dormancy" class="dormancy" style="<?=$dormancy == 1 ? "" : "display:none;"?>">
		<img src="/img/dormancy.jpg"><button id="btn_dormancy" onclick="close_dormancy();">X</button>
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
    $(document).on('change', "input:checkbox[name='autologin']", function() {
    	if (this.checked) {
    		$('.autologinalert').show(300);
    	} else {
    		$('.autologinalert').hide(300);
    	}
    });

		function close_dormancy(){
				$(location).attr("href", "/Login");
    }
//]]>
</script>
