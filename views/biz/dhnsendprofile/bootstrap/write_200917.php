	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
		<!-- 3차 메뉴 -->
		<?php
		include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu2.php');
		?>
		<!-- //3차 메뉴 -->
 <div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>발신프로필 등록</h3>
							<button class="btn_tr" onclick="location.href='/dhnbiz/sendprofile/lists'">뒤로가기</button>
						</div>
						<form class="form-horizontal row-border" id="writeform" name="writeform" method="post"
                          action="/dhnbiz/sendprofile/write" enctype="multipart/form-data">
                        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
                        <input type='hidden' name='actions' value='sendprofile_save' />
                        <input type='hidden' name='api_code' id='api_code' value='' />
                        <input type='hidden' name='api_data' id='api_data' value='' />
						<div class="white_box" id="form1">
							<div class="input_content_wrap">
								<label class="input_tit">플러스친구<span class="required">*</span></label>
								<div class="input_content">
									<input type="text" class="form-control required input-width-large" name="pf_yid" id="pf_yid" placeholder="검색용 아이디를 입력해주세요." onclick="plus_friend();" onChange="plus_friend();">
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">사업자등록증<span class="required">*</span></label>
								<div class="input_content">
									<input type="text" id="pf_name"
                                                   class="textbox form-control input-width-large" readonly="readonly"
                                                   placeholder="파일이 선택되지 않았습니다."/>
                                            <label for="biz_license" class="btn btn-default find h34">파일찾기</label>
                                            <input type="file" id="biz_license" name="biz_license" class="upload-hidden" accept="image/jpeg, image/png"
                                                   onchange="javascript: var a = this.value; var b = a.slice(12); document.getElementById('pf_name').value=b;if($('#biz_license').val()!=''){
                                                       $('.danger').remove();
                                                    } CheckUploadFileSize(this)">
                                            <span class="help-block msg"> &nbsp;jpg, png 파일만 업로드 됩니다. 최대 500KB </span>
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">카테고리<span class="required">*</span></label>
								<div class="input_content">
									<select name="category" id="category">
                                        	<?foreach($category["data"] as $row ) {

                                        	    ?>
                                        	    <option value="<?echo $row["parentCode"].$row["code"];?>"><?echo $row["name"];?></option>

                                        		<?
                                        	}?>
                                        </select>
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">사업자등록번호<span class="required">*</span></label>
								<div class="input_content">
									<input type="text" class="form-control required input-width-large" name="licenseNumber" id="licenseNumber" onkeypress="return check_digit(event);" placeholder="사업자번호를 입력해 주세요.">
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">관리자 휴대폰번호<span class="required">*</span></label>
								<div class="input_content">
									<div class="form-inline" id="phn_div">
                                            <input type="text" class="form-control required input-width-large" name="phoneNumber" id="phoneNumber" onkeypress="return check_digit(event);" placeholder="관리자 휴대폰번호를 입력해 주세요.">
											<input type="hidden" name="token_code" id="token_code" value="" />
                                            <input class="btn btn-default h34" type="button" id="sendbtn" name="numbtn" value="인증번호 전송" onclick="sendMessage()">
                                        </div>
								</div>
							</div>
							<div class="input_content_wrap" id="profile_key" style="display:none;">
								<label class="input_tit">발신프로필 Key<span class="required">*</span></label>
								<div class="input_content">
									<input type="text" class="form-control input-width-large" name="pf_key" id="pf_key" placeholder="발신프로필 키를 알고있는 경우 입력해주세요.">
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">인증번호<span class="required">*</span></label>
								<div class="input_content">
									<input type="text" class="form-control required input-width-large" name="token" id="token" placeholder="인증번호를 입력해 주세요." onkeypress="return check_digit(event);" onchange="noSpaceForm(this);">
								</div>
							</div>
						</div>
						<div class="btn_al_cen">
							<input class="btn_st3" type="button" id="cancel" name="cancel" value="취소" onclick="location.href='/dhnbiz/sendprofile/lists'">
              <input type="submit" onclick="check();" value="등록" class="btn_st3" name="register" id="register">
						</div>
					</div>
 </div>
</form>
    <style>
        .textbox {
            float: left;
            cursor: default !important;
            background-color: white !important;
        }

        input[type="file"] {
            position: absolute;
            clip: rect(0, 0, 0, 0);
        }

        .file label {
            margin-left: 3px;
        }

        .find {
            width: 100px;
        }

        .input-width-large {
            width: 250px !important;
        }

        #writeform label.error {
            color: #A74544;
        }

    </style>

    <script type="text/javascript">

            $(document).ready(function () {
                $("#writeform").validate({
                    rules: {
                        biz_license: {required: true},
                        pf_yid: {required: true},
                        token: {required: true},
                        phoneNumber: {required: true}
                    },
                    messages: {
                        biz_license: {required: ""},
                        pf_yid: {required: "검색용 아이디를 입력해주세요."},
                        token: {required: "인증번호를 입력해주세요."},
                        phoneNumber: {required: "관리자 휴대폰번호를 입력해주세요."}
                    },
                    errorPlacement: function (error, element) {
                        if (element.attr("name") == "phoneNumber") {
                            error.insertAfter($("#phn_div"));
                        }
                        else {
                            error.insertAfter(element);
                        }
                    }
                });
            });

    </script>

    <script type="text/javascript">
        $("#nav li.nav50").addClass("current open");

        $(document).unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                $(".btn-primary").click();
            }
        });

        //사업자 등록증 크기 제한
        function CheckUploadFileSize(objFile) {
            var maxSize = 500 * 1024;
            var fileSize = objFile.files[0].size;
            if (fileSize > maxSize) {
                $(".content").html("최대 500KB까지만 가능합니다.");
                $('#myModal').modal({backdrop: 'static'});
                $("#biz_license").val("");
                $("#pf_name").val("");
            }
        }


        //인증번호 전송
        function sendMessage() {
            var yellowId = $("#pf_yid").val();
            var phoneNumber = $("#phoneNumber").val();
            if (phoneNumber != "" && yellowId != "") {
				$('#token_code').val('');
                $.ajax({
                    url: "/dhnbiz/sendprofile/sendToken",
                    type: "POST",
                    data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>', 'pf_yid': yellowId, 'phoneNumber': phoneNumber},
                    beforeSend:function(){
                        $('#overlay').fadeIn();
                    },
                    complete:function(){
                        $('#overlay').fadeOut();
                    },
                    success: function (json) {
                        var message = json['message'];
                        var code = json['code'];
                        if (code == 'success') {
							$('#token_code').val(code);
                            $(".content").html("인증번호가 전송되었습니다.");
                            $('#myModal').modal({backdrop: 'static'});
                        } else {
                            $(".content").html(message);
                            $('#myModal').modal({backdrop: 'static'});
                        }
                    },
                    error: function () {
                        $(".content").html("처리되지 않았습니다.");
                        $('#myModal').modal({backdrop: 'static'});
                    }
                });
            } else if (phoneNumber == "" || yellowId == "") {
                $(".content").html("플러스친구 아이디와 관리자 휴대폰번호를 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            }
        }

        function check() {
            if($('#biz_license').val()=='') {
                if($(".danger").val()!='') {
                    var danger = "<p class='danger' value='danger' style='color:#A74544 ;font-weight: 600;'>사업자 등록증 이미지를 선택해주세요.</p>";
                    $('.msg').after(danger);
                }
            }
        }


        $(window).load(function() {
            var code = '';

            //등록, 삭제 JSON 응답 메세지
            var message = '';

            if (message != "") {
                var b = $(".content").html(message);
                $('#myModal').modal({backdrop: 'static'});
                $(".modal-dialog").css("word-wrap", "break-word");

                var pf_ynm = '';
                if (pf_ynm != "") {
                    (function (i, s, o, g, r, a, m) {
                        i['GoogleAnalyticsObject'] = r;
                        i[r] = i[r] || function () {
                                    (i[r].q = i[r].q || []).push(arguments)
                                }, i[r].l = 1 * new Date();
                        a = s.createElement(o),
                                m = s.getElementsByTagName(o)[0];
                        a.async = 1;
                        a.src = g;
                        m.parentNode.insertBefore(a, m)
                    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

                    ga('create', 'UA-29375674-2', 'auto');
                    ga('send', 'event', '발신프로필 등록', '<?=$this->member->item('mem_username')?>', '', 1);
                }
            }
        });

        $('#writeform').submit(function() {
            var yellowId = $("#pf_yid").val().replace(/ /gi,"");
            var biz_license = $("#biz_license").val().replace(/ /gi,"");
            var phoneNumber = $("#phoneNumber").val().replace(/ /gi,"");
            var category = $("#category").val().replace(/ /gi,"");
            var licenseNumber = $("#licenseNumber").val().replace(/ /gi,"");
            var token = $("#token").val().replace(/ /gi,"");
				var pf_key = "";

				var filename = "";
				if($("#biz_license").val()!="") {
					if($("#biz_license").val().indexOf('\\') > -1) {
						filename = $("#biz_license").val().substring($("#biz_license").val().lastIndexOf('\\')+1);
					} else {
						if($("#biz_license").val().indexOf('/') > -1) {
							filename = $("#biz_license").val().substring($("#biz_license").val().lastIndexOf('/')+1);
						} else {
							filename = $("#biz_license").val();
						}
					}
				}

				var success=false;
				try {
					var form = new FormData();
					form.append("userId", "<?=$admin_sw_id?>");
					form.append("plusFriend", $("#pf_yid").val());
					form.append("phoneNumber", $("#phoneNumber").val());
					form.append("categoryCode", $("#category").val());
					form.append("licenseNumber", $("#licenseNumber").val());
					form.append("token", $("#token").val());
					form.append("filename", filename);
					form.append("file", $("input[name=biz_license]")[0].files[0]);

	                $.ajax({
	                    url: "/dhnbiz/sendprofile/sendercreate",
	                    type: "POST",
	                    data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>'
		                     ,'yellowId': $("#pf_yid").val()
		                     ,'phoneNumber': $("#phoneNumber").val()
		                     ,'token':$("#token").val()
		                     ,'categoryCode':$("#category").val()
		                     },
	                    beforeSend:function(){
	                        $('#overlay').fadeIn();
	                    },
	                    complete:function(){
	                        $('#overlay').fadeOut();
	                    },
	                    success: function (json) {
	                        var message = json['message'];
	                        var code = json['code'];
	                        if (code == '200') {
								$(".content").html("발신프로필 등록이 완료 되었습니다.");
	                            $('#myModal').modal({backdrop: 'static'});
	                            return true;
	                        } else {
	                            $(".content").html(message);
	                            $('#myModal').modal({backdrop: 'static'});
	                        }
	                    },
	                    error: function () {
	                        $(".content").html("처리되지 않았습니다.");
	                        $('#myModal').modal({backdrop: 'static'});
	                    }
	                });


				}catch(e){ alert(e.message); }

				return false;
        });


        //숫자 여부 확인
        function check_digit(evt){
            var code = evt.which?evt.which:event.keyCode;
            if(code < 48 || code > 57){
                return false;
            }
        }

        //공백 제거
        function noSpaceForm(obj) {
            var str_space = /\s/;  //공백체크
            if(str_space.exec(obj.value)) { //공백체크
                obj.focus();
                obj.value = obj.value.replace(/(\s*)/g,''); //공백제거
                return false;
            }
        }

        function plus_friend() {
            var pf_yid = $("#pf_yid").val();
            if(pf_yid.substr(0,1)!="@") {
                var default_val = "@"+pf_yid;
                $("#pf_yid").val(default_val);
            }
        }
    </script>
