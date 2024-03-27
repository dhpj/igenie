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

								
								
								
        <div class="panel-heading">회원 정보</div>
        <div class="panel-body">
            <?php
            echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
            echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
            $attributes = array('class' => 'form-horizontal', 'name' => 'fregisterform', 'id' => 'fregisterform');
            echo form_open_multipart(current_full_url(), $attributes);
                foreach (element('html_content', $view) as $key => $value) {
            ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="<?php echo element('field_name', $value); ?>"><?php echo element('display_name', $value); ?></label>
                        <div class="col-lg-8">
                            <?php echo element('input', $value); ?>
                            <?php if (element('description', $value)) { ?>
                                <p class="help-block"><?php echo element('description', $value); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                <?php
                }
                if ($this->cbconfig->item('use_member_photo') && $this->cbconfig->item('member_photo_width') > 0 && $this->cbconfig->item('member_photo_height') > 0) {
                ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="mem_photo">사업자등록증</label>
                        <div class="col-lg-8">
	                        
	                        <div class="send_list">
	                        <label class="file" title=""><input type="file" name="mem_photo" id="mem_photo"></label>
	                        </div>
                            <!--input type="file" name="mem_photo" id="mem_photo" /-->
                            <p class="help-block">가로길이 : <?php echo number_format($this->cbconfig->item('member_photo_width')); ?>px, 세로길이 : <?php echo number_format($this->cbconfig->item('member_photo_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
                        </div>
                    </div>
                <?php
                }
                if ($this->cbconfig->item('use_member_icon') && $this->cbconfig->item('member_icon_width') > 0 && $this->cbconfig->item('member_icon_height') > 0) {
                ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="mem_icon">회원아이콘</label>
                        <div class="col-lg-8">
                            <label class="file" title=""><input type="file" name="mem_icon" id="mem_icon"></label>
                            <p class="help-block">가로길이 : <?php echo number_format($this->cbconfig->item('member_icon_width')); ?>px, 세로길이 : <?php echo number_format($this->cbconfig->item('member_icon_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="form-group">
                    <label class="col-lg-3 control-label" for="mem_open_profile">정보공개</label>
                    <div class="col-lg-8">
                        <div class="checkbox">
                            <label for="mem_open_profile">
                                <input type="checkbox" name="mem_open_profile" id="mem_open_profile" value="1" <?php echo set_checkbox('mem_open_profile', '1', true); ?> />
                                다른분들이 나의 정보를 볼 수 있도록 합니다.
                            </label>
                            <?php
                            if (element('open_profile_description', $view)) {
                            ?>
                                <p class="help-block"><?php echo element('open_profile_description', $view); ?></p>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                if ($this->cbconfig->item('use_note')) {
                ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">쪽지기능사용</label>
                        <div class="col-lg-8">
                            <div class="checkbox">
                                <label for="mem_use_note">
                                    <input type="checkbox" name="mem_use_note" id="mem_use_note" value="1" <?php echo set_checkbox('mem_use_note', '1', true); ?> />
                                    쪽지를 주고 받을 수 있습니다.
                                </label>
                                <?php if (element('use_note_description', $view)) { ?>
                                    <p class="help-block"><?php echo element('use_note_description', $view); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="form-group">
                    <label class="col-lg-3 control-label">이메일 수신여부</label>
                    <div class="col-lg-8">
                        <div class="checkbox">
                            <label for="mem_receive_email" >
                                <input type="checkbox" name="mem_receive_email" id="mem_receive_email" value="1" <?php echo set_checkbox('mem_receive_email', '1', true); ?> /> 수신
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">SMS 문자 수신</label>
                    <div class="col-lg-8    ">
                        <div class="checkbox">
                            <label for="mem_receive_sms">
                                <input type="checkbox" name="mem_receive_sms" id="mem_receive_sms" value="1" <?php echo set_checkbox('mem_receive_sms', '1', true); ?> /> 수신
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php if ($this->cbconfig->item('use_recaptcha')) { ?>
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-8    captcha" id="recaptcha"><button type="button" id="captcha"></button></div>
                        <input type="hidden" name="recaptcha" />
                    <?php } else { ?>
                        <label class="col-lg-3 control-label"><img src="<?php echo base_url('assets/images/preload.png'); ?>" width="160" height="40" id="captcha" alt="captcha" title="captcha" /></label>
                        <div class="col-lg-8    ">
                            <input type="text" name="captcha_key" id="captcha_key" class="form-control px150" value="" />
                            <p class="help-block">좌측에 보이는 문자를 입력해주세요</p>
                        </div>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <div class="col-lg-9 col-lg-offset-3">
                        <!--a href="<?php echo site_url(); ?>" class="btn btn-default btn-sm">취소</a-->
                    </div>
                </div>
                <div style="display: block; text-align: center">
                <button type="submit" class="btn_join">회원가입</button>
                </div>
            <?php echo form_close(); ?>
        </div>								
								
								
								
								

								<!--div class="divider">꼭! 확인하세요.</div-->
							</div>
						</div>
					</div>
				</div>
		</div>

<!-- main start -->




<?php
$this->managelayout->add_css(base_url('assets/css/datepicker3.css'));
$this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
$this->managelayout->add_js(base_url('assets/js/bootstrap-datepicker.js'));
$this->managelayout->add_js(base_url('assets/js/bootstrap-datepicker.kr.js'));
$this->managelayout->add_js(base_url('assets/js/member_register.js'));
if ($this->cbconfig->item('use_recaptcha')) {
    $this->managelayout->add_js(base_url('assets/js/recaptcha.js'));
} else {
    $this->managelayout->add_js(base_url('assets/js/captcha.js'));
}
?>

<script type="text/javascript">
//<![CDATA[
$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    language: 'kr',
    autoclose: true,
    todayHighlight: true
});
$(function() {
	$("#mem_username").parent().parent().find("label").html("업체명");
	$("#mem_nickname").parent().parent().find("label").html("담당자이름");
	$("#mem_phone").parent().parent().find("label").html("담당자연락처");
   $('#fregisterform').validate({
        onkeyup: false,
        onclick: false,
        rules: {
            mem_userid: {required :true, minlength:3, maxlength:20, is_userid_available:true},
            mem_email: {required :true, email:true, is_email_available:true},
            mem_password: {required :true, is_password_available:true},
            mem_password_re : {required: true, equalTo : '#mem_password' },
            mem_nickname: {required :true, is_nickname_available:true}
            <?php if ($this->cbconfig->item('use_recaptcha')) { ?>
                , recaptcha : {recaptchaKey:true}
            <?php } else { ?>
                , captcha_key : {required: true, captchaKey:true}
            <?php } ?>
        },
        messages: {
            recaptcha: '',
            captcha_key: '자동등록방지용 코드가 올바르지 않습니다.'
        }
    });
});
//]]>
</script>
