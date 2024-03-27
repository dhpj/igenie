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
<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					템플릿 관리
				</div>
<!-- 타이틀 영역 END -->
 <div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>템플릿 가져오기</h3>
						</div>
						<div class="inner_content">
							<form class="form-horizontal row-border" id="writeform" name="writeform" method="post"
                              action="/biz/templatedhn/take" enctype="multipart/form-data">
                            <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
                           
                            <input type='hidden' name='api_code' id='api_code' value='' />
                            <input type='hidden' name='api_data' id='api_data' value='' />
                            <table class="tpl_ver_form" width="100%" id="form1">
                                <colgroup>
                                    <col width="200">
                                    <col width="*">
                                </colgroup>
                                <? 
                                if(!empty($profile)) {
                                ?>
                                <tr>
                                    <th>프로필 업체<span class="required">*</span></th>
                                    <td>
                                        <select name="senderkey" id="senderkey">
                                        	<?
                                        	//var_dump($category);
                                        	foreach($profile as $row ) {
                                        	    ?>
                                        	    <option value="<?=$row->spf_key?>" <?=($senderkey ==$row->spf_key)?"selected":""?>><?=$row->spf_company?></option>
                                        		<?  
                                        	}?>
                                        </select>
                                    </td>
                                </tr>
                                <?
                                } else {
                                ?>
                                	<input type="hidden" id="mem_id" value="<?=$this->member->item('mem_id')?>" />
                                <? } ?>
                                <tr id="profile_key" >
                                    <th>템플릿 Code<span class="required">*</span></th>
                                    <td>
                                        <input type="text" class="form-control input-width-large" name="tcode"
                                               id="tcode" placeholder="템플릿 Code를 입력해주세요." style="width:400px !important;">
                                    </td>
                                </tr>
                            </table>
                            <div class="mt30 align-center">
                                <input class="btn" type="button" id="cancel" name="cancel" value="취소"
                                       onclick="location.href='/biz/senddhnprofile/lists'">
                                <input type="submit" value="등록" class="btn btn-custom" name="register" id="register">
                            </div>
                        </form>
						</div>
					</div>
					<div class="footer">
						<div class="cs_info">
							<div class="cs_call">
								<span>1522-7985</span>
							</div>
							<div class="cs_support">
								<!--a href="#"><span class="icon_cs request">1:1문의</span></a-->
								<a target="_blank" href="http://367.co.kr/"><span class="icon_cs remote">원격지원</span></a>
							</div>
						</div>
	
						<div class="info">
							<ul>
								<li>사업자등록번호 : 364-88-00974</li>
								<li>통신판매업 : 신고번호 2018-창원의창-0272호</li>
								<li>대표이사 : 송종근 </li>
								<li class="paragraph">주소 : 경상남도 창원시 의창구 차룡로 48번길 54(팔용동) 경남창원산학융합원 기업연구관 302호</li>
								<li>대표전화 : 1522-79854</li>
								<li>팩스 : 0505-299-0001</li>
								<li>개인정보처리방침</li>
								<li>원격지원</li>
							</ul>
						</div>
						<div class="copyright">
							Copyright © DHN. All rights reserved.
						</div>
					</div>
 </div>


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
        $("#nav li").addClass("current open");

        $(document).unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                $(".btn-primary").click();
            }
        });

 
        $('#writeform').submit(function() {
            var senderKey = $("#senderkey").val();
            var tcode = $("#tcode").val();

                $.ajax({
                    url: "/biz/templatedhn/taket",
                    type: "POST",
                    data: {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>', 
                           'senderKey':senderKey,
                           'tcode': tcode},
                    beforeSend:function(){
                        $('#overlay').fadeIn();
                    },
                    complete:function(){
                        $('#overlay').fadeOut();
                    },
                    success: function (json) {
                        if(json['coin'] == "200") {
                            $(".content").html("정상적으로 처리 되었습니다.");
                            $('#myModal').modal({backdrop: 'static'});

                        } else {
                            $(".content").html("가죠오는 중 오류가 발생 했습니다.");
                            $('#myModal').modal({backdrop: 'static'});
                        }
 
                    },
                    error: function () {
                 
                        $(".content").html("처리되지 않았습니다.");
                        $('#myModal').modal({backdrop: 'static'});
                    }
                });

				if (senderKey !="" && tcode != "") {
					$('#overlay').fadeOut();
					return true;
				}
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