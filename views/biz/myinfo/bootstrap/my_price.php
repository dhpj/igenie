<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu4.php');
?>
<!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>이용상품</h3>
		</div>
		<div class="white_box">
                                <form id="join"
                                      action="/biz/myinfo/modify" method="post"
                                      enctype="multipart/form-data" autocomplete="off">
									<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
														<div class="myinfo_list">
														<dl>
															<dt>계정</dt>
															<dd><?=$rs->mem_userid?></dd>
														</dl>
														<dl>
															<dt>업체명</dt>
															<dd><?=$rs->mem_username?></dd>
														</dl>
														<dl>
															<dt>발송 단가</dt>
															<dd>
																알림톡 : ₩<?=number_format($rs->mad_price_at, 2)?> &nbsp;&nbsp;
																친구톡 : ₩<?=number_format($rs->mad_price_ft, 2)?> &nbsp;&nbsp;
																친구톡 이미지 : ₩<?=number_format($rs->mad_price_ft_img, 2)?> &nbsp;&nbsp;
															<?
								$mem2nd = false;
								if ($rs->mem_2nd_send=="GREEN_SHOT" || $rs->mem_2nd_send=="NASELF" || $rs->mem_2nd_send=="SMART") {
									$mem2nd = true;
								}

								if ($mem2nd == ture) {
										$partWeb = "";
										$smsPrice = 0;
										$lmsPrice = 0;
										$mmsPrice = 0;


										if ($rs->mem_2nd_send=="GREEN_SHOT") {
												$smsPrice = $rs->mad_price_grs_sms;
												$lmsPrice = $rs->mad_price_grs;
												$mmsPrice = $rs->mad_price_grs_mms;
												if ($rs->mem_id == 3) {
														$partWeb = "(A)";
												}
										} else if ($rs->mem_2nd_send=="NASELF") {
												$smsPrice = $rs->mad_price_nas_sms;
												$lmsPrice = $rs->mad_price_nas;;
												$mmsPrice = $rs->mad_price_nas_mms;
												if ($rs->mem_id == 3) {
														$partWeb = "(B)";
												}
										} else if ($rs->mem_2nd_send=="SMART") {
												$smsPrice = $rs->mad_price_smt_sms;
												$lmsPrice = $rs->mad_price_smt;
												$mmsPrice = $rs->mad_price_smt_mms;
												if ($rs->mem_id == 3) {
														$partWeb = "(C)";
												}
										}
						?>
						단문 문자(SMS)<?=$partWeb ?> : ₩<?=number_format($smsPrice, 2)?> &nbsp;&nbsp; 장문 문자(LMS)<?=$partWeb ?> : ₩<?=number_format($lmsPrice, 2)?> <!-- &nbsp;&nbsp;  이미지 문자(MMS)<?=$partWeb ?> : ₩<?=number_format($mmsPrice, 2)?>-->
						<?
								}
							?>
															</dd>
														</dl>
														<dl style='display:<?=$second_send_flag ? '' : 'none' ?>'>
															<dt>발송실패시 2차 발송</dt>
															<dd>
																<div class="checks">
																<input type="radio" id="second_01" name="mem_2nd_send" value="NONE" <?=($rs->mem_2nd_send=="" || $rs->mem_2nd_send=="NONE") ? "checked" : "disabled"?>  /><label for="second_01">보내지 않음</label>
																<? if ($rs->mem_id == 3) { ?>
							<input type="radio" id="second_02" name="mem_2nd_send" value="GREEN_SHOT" <?=($rs->mem_2nd_send=="GREEN_SHOT") ? "checked" : "disabled"?> /><label for="second_02">WEB문자(A)</label>
							<!--<input type="radio" id="second_03" name="mem_2nd_send" value="NASELF" <?=($rs->mem_2nd_send=="NASELF") ? "checked" : "disabled"?> /><label for="second_03">WEB문자(B)</label>-->
							<!-- 2019.09.19 주석처리 -->
							<!--  input type="radio" class="" name="mem_2nd_send" value="015" <?=($rs->mem_2nd_send=="015") ? "checked" : "disabled"?> />&nbsp;015문자&nbsp; -->
																	<!-- 2019.09.19 DOOIT을 SMART로 변경 -->
							<input type="radio" id="second_04" name="mem_2nd_send" value="SMART" <?=($rs->mem_2nd_send=="SMART") ? "checked" : "disabled"?> /><label for="second_04">WEB문자(C)</label>
							<? } else { ?>
							<input type="radio" id="second_05" name="mem_2nd_send" value="GREEN_SHOT" <?=($mem2nd==true) ? "checked" : "disabled"?> /><label for="second_05">WEB문자</label>
							<? } ?>
																</div>
															</dd>
														</dl>
														</div>
                            <div class="mg_t20">
                                 <p><font color='red'>※ 모든 발송 단가는 부가세 포함 금액 입니다.</font></p>
                                 <!-- 2019.09.19 주석처리 -->
                                 <!-- p>※ 폰문자나 SMS/LMS 발송의 경우 기본 발송금액과 차이가 있습니다.</p-->
                                 <p>※ 선 충전 예치금이 부족한 경우 메시지 발송이 제한됩니다.</p>
                                <!--  <p>※ 발송실패시 포인트로 환불되며 재발송시 포인트가 우선적으로 차감됩니다.</p> -->
                            </div>
                        </form>
		</div>
	</div>
</div>

    <style>
        .input-width-large {
            width: 250px !important;
        }
    </style>

    <script type="text/javascript">
        $('#biusnessNum').filestyle({
            buttonName: 'btn',
            buttonText: ' 파일찾기',
            icon: false
        });
    </script>

    <script type="text/javascript">

        var check_password_ = false;
        var check_password_rule_ = false;
        var check_password_new_ = false;
        var auth_result_ = false;
        var email_result_ = false;
        var file_check_ = 0;

        //CSRF token획득
        function getCookie(name) {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }

        //확인창 확인버튼
        function click_btn_custom() {
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".btn-custom").click();
                }
            });
        }

        // 공백 제거
        function noSpaceForm(obj) {
            var str_space = /\s/;  // 공백체크
            if (str_space.exec(obj.value)) { //공백 체크
                obj.focus();
                obj.value = obj.value.replace(/(\s*)/g, ''); // 공백제거
                return false;
            }
        }

        //빈칸 여부 확인
        function check_empty(id) {
            var get_id = '#' + id;
            if ($(get_id).val().trim().length > 0) {
                document.getElementById(id + '_result').style.display = 'none';
            }
            if (!id || $('#mb_passwd').val().trim().length == 0) {
                document.getElementById('mb_passwd_rule').style.display = 'none';
            }
            if (!id || $('#new_mb_passwd').val().trim().length == 0) {
                document.getElementById('new_mb_passwd_rule').style.display = 'none';
            }
        }

        //이메일 유효성 검사
        function email_check(email) {
            var regex = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            return (email != '' && email != 'undefined' && regex.test(email) === true);
        }

        function email_check_result() {
            var admin_email = $('#admin_email').val();
            if (!email_check(admin_email) || !admin_email) {
                document.getElementById('admin_email_rule').innerHTML = '올바른 형식의 이메일을 입력해주세요.';
                document.getElementById('admin_email_rule').style.color = '#942a25';
                document.getElementById('admin_email_rule').style.display = 'block';
                email_result_ = false;
            }
            else if (admin_email && email_check(admin_email)) {
                document.getElementById('admin_email_rule').innerHTML = '올바른 형식의 이메일 입니다.';
                document.getElementById('admin_email_rule').style.color = '#2a6496';
                document.getElementById('admin_email_rule').style.display = 'block';
                email_result_ = true;
            }
        }

        //패스워드 확인
        function check_password_rule() {

            var password = $('#new_mb_passwd').val();
            var checkNumber = password.search(/[0-9]/g);
            var checkEnglish = password.search(/[a-z]/ig);

            if (password) {
                if (password && $('#new_mb_passwd').val().trim().length > 0) {
                    document.getElementById('new_mb_passwd_result').style.display = 'none';

                    if (!/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[azA-Z0-9])/.test(password) || checkNumber < 0 || checkEnglish < 0) {
                        if (!/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[azA-Z0-9])/.test(password) || password.length<6 || password.length>15) {
                            document.getElementById('new_mb_passwd_rule').innerHTML = '숫자와 영문자, 특수문자 조합으로 6~15자리를 사용해야 합니다.';
                            document.getElementById('new_mb_passwd_rule').style.color = '#942a25';
                            document.getElementById('new_mb_passwd_rule').style.display = 'block';
                            check_password_rule_ = false;
                        }
                        if (checkNumber < 0 || checkEnglish < 0) {
                            document.getElementById('new_mb_passwd_rule').innerHTML = '숫자와 영문자, 특수문자를 혼용하여야 합니다.';
                            document.getElementById('new_mb_passwd_rule').style.color = '#942a25';
                            document.getElementById('new_mb_passwd_rule').style.display = 'block';
                            check_password_rule_ = false;
                        }
                    }
                    else {
                        document.getElementById('new_mb_passwd_rule').innerHTML = '사용가능한 비밀번호 입니다.';
                        document.getElementById('new_mb_passwd_rule').style.color = '#2a6496';
                        document.getElementById('new_mb_passwd_rule').style.display = 'block';
                        check_password_rule_ = true;
                    }
                } else {
                    document.getElementById('new_mb_passwd_rule').innerHTML = '공백문자를 사용할 수 없습니다. ';
                    document.getElementById('new_mb_passwd_rule').style.color = '#942a25';
                    document.getElementById('new_mb_passwd_rule').style.display = 'block';
                    check_password_rule_ = false;
                }
            }
            else {
                document.getElementById('new_mb_passwd_result').style.display = 'none';
                check_password_rule_ = false;
            }

        }


        //비밀번호 일치 확인 (현재 비밀번호)
        function check_password() {
            var mb_passwd = $('#mb_passwd').val();

            if ($('#mb_passwd').val().trim().length == 0) { //공백문자 일 경우(mb_passwd)
                document.getElementById('mb_passwd_result').style.display = 'block';
                check_password_ = false;
            }
            else if (!mb_passwd) {
                document.getElementById('mb_passwd_result').style.display = 'block';
                check_password_ = false;
            }
            else {
                document.getElementById('mb_passwd_result').style.display = 'none';
                document.getElementById('mb_passwd_rule').style.display = 'none';

                $.ajax({
                    url: '/biz/myinfo/password',
                    type: "POST",
                    data: {
                        <?=$this->security->get_csrf_token_name()?>: '<?=$this->security->get_csrf_hash()?>',
                        mb_passwd: mb_passwd
                    },
                    success: function (json) {
                        get_result(json);
                    },
                    error: function (data, status, er) {
                        document.getElementById('mb_passwd_rule').innerHTML = '처리중 오류가 발생하였습니다. 관리자에게 문의하세요';
                        document.getElementById('mb_passwd_rule').style.color = '#942a25';
                        document.getElementById('mb_passwd_rule').style.display = 'block';
                        check_password_ = false;
                    }
                });
                function get_result(json) {
                    document.getElementById('mb_passwd_result').style.display = 'none';
                    if (json['result'] == true) {
                        document.getElementById('mb_passwd_rule').innerHTML = '비밀번호가 일치 합니다.';
                        document.getElementById('mb_passwd_rule').style.color = '#2a6496';
                        document.getElementById('mb_passwd_rule').style.display = 'block';
                        check_password_ = true;
                    }
                    else {
                        document.getElementById('mb_passwd_rule').innerHTML = '비밀번호가 일치하지 않습니다.';
                        document.getElementById('mb_passwd_rule').style.color = '#942a25';
                        document.getElementById('mb_passwd_rule').style.display = 'block';
                        check_password_ = false;
                    }
                }
            }
        }
        //비밀번호 일치 확인 (새로운 비밀번호)
        function check_password_new() {
            var new_mb_passwd = $('#new_mb_passwd').val();
            var new_mb_passwd2 = $('#new_mb_passwd2').val();

            if ($('#new_mb_passwd').val().trim().length == 0 || $('#new_mb_passwd2').val().trim().length == 0) {
                if ($('#new_mb_passwd').val().trim().length == 0) { //공백문자 일 경우(mb_passwd)
                    document.getElementById('mb_passwd_result').style.display = 'block';
                    check_password_new_ = false;
                }
                if ($('#new_mb_passwd2').val().trim().length == 0) { //공백문자 일 경우(mb_passwd2)
                    document.getElementById('mb_passwd2_result').style.display = 'block';
                    check_password_new_ = false;
                }
            }
            else if (!new_mb_passwd || !new_mb_passwd2) {

                if (!new_mb_passwd) {
                    document.getElementById('mb_passwd_result').style.display = 'block';
                    check_password_new_ = false;
                }
                if (!new_mb_passwd2) {
                    document.getElementById('mb_passwd2_result').style.display = 'block';
                    check_password_new_ = false;
                }

            }
            else {
                document.getElementById('new_mb_passwd_result').style.display = 'none';
                if (new_mb_passwd != new_mb_passwd2) {
                    document.getElementById('new_mb_passwd2_rule').innerHTML = '비밀번호가 일치하지 않습니다.';
                    document.getElementById('new_mb_passwd2_rule').style.color = '#942a25';
                    document.getElementById('new_mb_passwd2_rule').style.display = 'block';
                    check_password_new_ = false;
                } else {
                    document.getElementById('new_mb_passwd2_rule').innerHTML = '비밀번호가 일치 합니다.';
                    document.getElementById('new_mb_passwd2_rule').style.color = '#2a6496';
                    document.getElementById('new_mb_passwd2_rule').style.display = 'block';
                    check_password_new_ = true;
                }
            }
        }

        //전송 버튼 후 30초 비활성화
        $(function () {
            $("#check_admin_tel").click(function () {
                $("#check_admin_tel").attr("disabled", "disabled");
                setTimeout(function () {
                    $("#check_admin_tel").removeAttr("disabled");
                }, 30000);
            });
        });

        //인증번호 전송 -> 난수생성 response
        function send_authentication_number() {
            var admin_tel = $('#admin_tel').val();

            if (admin_tel && $('#admin_tel').val().trim().length > 0) {
                $.ajax({
                    url: '/check_auth_number/',
                    type: "POST",
                    data: {
                        <?=$this->security->get_csrf_token_name()?>: '<?=$this->security->get_csrf_hash()?>',
                        admin_tel: admin_tel
                    },
                    success: function (json) {
                        authentication_ = json['num']
                        document.getElementById('admin_tel_result').innerHTML = '인증번호를 전송하였습니다.';
                        document.getElementById('admin_tel_result').style.color = '#2a6496';
                        document.getElementById('admin_tel_result').style.display = 'block';
                    },
                    error: function (data, status, er) {
                        document.getElementById('admin_tel_result').innerHTML = '처리중 오류가 발생하였습니다. 관리자에게 문의하세요';
                        document.getElementById('admin_tel_result').style.color = '#942a25';
                        document.getElementById('admin_tel_result').style.display = 'block';
                    }
                });
                document.getElementById('check_admin_tel').innerHTML = '재전송';
            }
            else {
                document.getElementById('admin_tel_result').style.display = 'block';
            }
        }

        //인증번호 일치여부
        function auth_result() {
            var authentication_num = $('#authentication_num').val();

            if (authentication_num && $('#authentication_num').val().trim().length > 0) {
                if (authentication_num == authentication_) {
                    document.getElementById('authentication_num_rule').innerHTML = '인증되었습니다.';
                    document.getElementById('authentication_num_rule').style.color = '#2a6496';
                    document.getElementById('authentication_num_rule').style.display = 'block';
                    auth_result_ = true;
                }
                else {
                    document.getElementById('authentication_num_rule').innerHTML = '인증번호가 잘못되었습니다.';
                    document.getElementById('authentication_num_rule').style.color = '#942a25';
                    document.getElementById('authentication_num_rule').style.display = 'block';
                    auth_result_ = false;
                }
            } else {
                document.getElementById('authentication_num_result').innerHTML = '인증번호를 입력해주세요';
                document.getElementById('authentication_num_result').style.color = '#942a25';
                document.getElementById('authentication_num_result').style.display = 'block';
                auth_result_ = false;
            }
        }

        //파일 용량 및 확장자 확인 ->500KB제한
        function check_size() {
            var thumbext = document.getElementById('bisness_file').value;

            thumbext = thumbext.slice(thumbext.indexOf(".") + 1).toLowerCase();

            if (thumbext) {
                document.getElementById('bisness_file_result').style.display = 'none';
                if (thumbext != "jpg" && thumbext != "png" && thumbext != "jpeg") { //확장자를 확인
                    document.getElementById('bisness_file_rule').innerHTML = 'jpg, png 파일이 아닙니다. 다시 선택해 주세요.';
                    document.getElementById('bisness_file_rule').style.color = '#942a25';
                    document.getElementById('bisness_file_rule').style.display = 'block';
                    file_check_ = 2;
                }
                else {
                    if ($("#bisness_file")[0].files[0].size > 500000) //파일 용량 체크 (500KB 제한)
                    {
                        document.getElementById('bisness_file_rule').innerHTML = '500KB를 초과하였습니다. 다시 선택해 주세요.';
                        document.getElementById('bisness_file_rule').style.color = '#942a25';
                        document.getElementById('bisness_file_rule').style.display = 'block';
                        file_check_ = 2;
                    } else {
                        document.getElementById('bisness_file_rule').innerHTML = '업로드 가능한 파일입니다.';
                        document.getElementById('bisness_file_rule').style.color = '#2a6496';
                        document.getElementById('bisness_file_rule').style.display = 'block';
                        file_set = true;
                        file_check_ = 1;
                    }
                }
            } else {
                document.getElementById('bisness_file_rule').style.display = 'none';
                file_check_ = 0;
            }
        }

        //submit
        function complete() {
            var mb_passwd = $('#mb_passwd').val();
            var admin_email = $('#admin_email').val();
            var admin_tel = $('#admin_tel').val();
            var admin_name = $('#admin_name').val();
            var new_mb_passwd = $('#new_mb_passwd').val();
            var new_mb_passwd2 = $('#new_mb_passwd2').val();
            var check_new_password_result = true;
            var check_file_result = true;

            check_password();
            email_check_result();



            if (new_mb_passwd) {
                if (!new_mb_passwd2 || check_password_rule_ == false || check_password_new_ == false) {
                    if (!new_mb_passwd2) {
                        $('#mb_passwd2').focus();
                        check_new_password_result = false;
                        document.getElementById('new_mb_passwd2_result').style.display = 'block';
                    }
                    else if (check_password_rule_ == false) {
                        $('#new_mb_passwd').focus();
                        check_new_password_result = false;
                        document.getElementById('new_mb_passwd_rule').style.display = 'none';
                    }
                    else if (check_password_new_ == false) {
                        $('#new_mb_passwd2').focus();
                        check_new_password_result = false;
                        document.getElementById('new_mb_passwd_rule').style.display = 'none';
                    }
                }
                else {
                    check_new_password_result = true;
                }
            }

            if (file_check_ == 2) {
                check_file_result = false;
                check_size();
            } else {
                check_file_result = true;
            }


                if (check_new_password_result == true && check_password_ == true && admin_name && admin_email && email_result_ == true && check_file_result == true) {


                $(".content").html("사용자 정보를 변경하였습니다.");
                $("#myModal").modal('show');
                click_btn_custom();
                $('#myModal').on('hidden.bs.modal', function () {
                    document.getElementById("join").submit();
                });

                }
        else {
            if (email_result_ == false) {
                $('#admin_email').focus();
            }
            if (auth_result_ == false) {
                $('#authentication_num').focus();
            }
            if (!admin_tel) {
                document.getElementById('admin_tel_result').style.display = 'block';
                $('#admin_tel').focus();
            }
            if (!admin_name) {
                document.getElementById('admin_name_result').style.display = 'block';
                $('#admin_name').focus();
            }

            if (check_file_result == false) {
                check_size();
            }
            if (check_new_password_result == false) {
                $('#new_mb_passwd').focus();
                check_password_rule();
                check_password_new();
            }

            if (check_password_ == false) {
                document.getElementById('mb_passwd_result').style.display = 'block';
                $('#mb_passwd').focus();
            }
        }
    }
    </script>
