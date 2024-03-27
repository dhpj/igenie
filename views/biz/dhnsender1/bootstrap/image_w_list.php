    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" xmlns="http://www.w3.org/1999/html" style="overflow-y: hidden; width: 100%; height: 100%">
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
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content2">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" id="close_btn" class="btn btn-default" data-dismiss="modal">취소
                            </button>
                            <button type="button" id="confirm_btn" class="btn btn-primary" data-dismiss="modal">확인
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					이미지 관리
				</div>
<!-- 타이틀 영역 END -->
    <form action="/biz/sender/image_w_list" method="post" id="mainForm">
        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
 				<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>이미지 목록</h3>
						</div>
						<div class="tab_group">
						    <button class="btn" type="button" onclick="javascript:click_bntimage()"><i class="material-icons">add</i> 이미지추가</button>
						    <button class="btn" type="button" onclick="javascript:click_bntwimage()"><i class="material-icons">add</i> 와이드이미지추가</button>
						    <button class="btn" type="button" onclick="javascript:delete_image()"><i class="material-icons">delete</i> 선택삭제</button>
						</div>
						<div class="inner_content">
							<input type="file" name="image_file" id="image_file" accept="image/*" value="upload" style="display: none;" class="upload-hidden" accept="image/jpeg, image/png" onchange="img_size();">
							<input type="file" name="image_w_file" id="image_w_file" accept="image/*" value="upload" style="display: none;" class="upload-hidden" accept="image/jpeg, image/png" onchange="wide_img_size();">

							<div class="notice_wrap">
                                <p>* 이미지 ==> 권장 사이즈 : 720 x 720px / 제한 사이즈 - 가로 500px 미만 또는 가로:세로 비율이 2:1 미만 또는 3:4 초과시 업로드 불가 / 지원 파일형식 및 크기 : jpg, png / 최대 500KB</p>
                                <p>* 와이드이미지 ==> 제한 사이즈 : 800 x 600px / 지원 파일형식 및 크기 : jpg, png / 최대 2MB</p>
                                <p>* 이미지 상세보기를 클릭하여 이미지를 확인하세요. </p>
                            </div>
                            <table class="table table-hover table-striped table-bordered table-highlight-head table-checkable">
                                <tbody>
                                    <div class="thumb">
                                    <ul class="thumblist">
                                    <?foreach($list as $r) {?>
                                        <li>
	                                        <input type="checkbox" class="uniform" name="chk_image[]" value="<?=urlencode($r->img_filename."|".$r->img_id)?>" onclick="javascript:check_image('chk_image[]')">
	                                        <div class="thumb_img" style="background-image: url('<?=$r->img_url?>')">
	                                        	<input type="hidden" id="img_<?=$r->img_id?>" value="<?=$base_url?>/pop/<?=$r->img_filename?>">
	                                        	<? if ($r->img_wide == "Y" ) { ?>
	                                        	<div class="icon_wide">와이드전용</div>
	                                        	<? } ?>
	                                        </div>
	                                        <div class="img_detail" onclick="img_detail(<?=$r->img_id?>);">
		                                        <span class="btn_detail">이미지 상세보기</span>
	                                        </div>
                                        </li>
									<?}?>
                                </ul>
                                </div>
                                </tbody>
                            </table>
                            <div class="align-center mt30"><?=$page_html?></div>
                            
						</div>
					</div>
 				</div>
    </form>

    <style type="text/css">
        #myModal {
            vertical-align: middle;

            transform: translateY(-50%);
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .modal-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }

        .thumblist {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .thumblist img {
            vertical-align: middle;
            display: inline-block;
            height: 100%;
            -ms-interpolation-mode: bicubic; /* Better image scaling in IE */
        }

        p {
            clear: both
        }

    </style>

    <script type="text/javascript">
        $("#nav li.nav10").addClass("current open");
        //예-아니오에서의 확인버튼
        function click_btn_primary() {
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".btn-primary").click();
                }
            });
        }

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

        //이미지 업로드 버튼 클릭
        function click_bntimage() {
            $("#image_file").trigger("click");

        }

        function click_bntwimage() {
            $("#image_w_file").trigger("click");

        }
        
        //파일 용량 및 확장자 확인 ->500KB제한
        function upload(check) {
            var thumbext = document.getElementById('image_file').value;
            var file_data = new FormData();
            file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
            file_data.append("image_file", $("input[name=image_file]")[0].files[0]);

            thumbext = thumbext.slice(thumbext.indexOf(".") + 1).toLowerCase();

            if (thumbext) {
/*
                if ($("#image_file")[0].files[0].size > 500000) //파일 용량 체크 (500KB 제한)
                {
                    $(".content").html("500KB를 초과하였습니다. 다시 선택해 주세요.");
                    $("#myModal").modal('show');
                    $(document).unbind("keyup").keyup(function (e) {
                        var code = e.which;
                        if (code == 13) {
                            $(".btn-primary").click();
                        }
                    });
                } else if (check == false){
                    $(".content").html("가로 500px 미만 또는 가로:세로 비율이 2:1 미만 또는 3:4 초과시 업로드 불가합니다.");
                    $("#myModal").modal('show');
                    $(document).unbind("keyup").keyup(function (e) {
                        var code = e.which;
                        if (code == 13) {
                            $(".btn-primary").click();
                        }
                    });
                } else {
*/
                    $.ajax({
                        url: "/biz/sender/image_upload",
                        type: "POST",
                        data: file_data,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            $('#overlay').fadeIn();
                        },
                        complete: function () {
                            $('#overlay').fadeOut();
                        },
                        success: function (json) {
                            showResult(json);
                        },
                        error: function (data, status, er) {
                            $(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오..");
                            $("#myModal").modal('show');
                        }
                    });
                    function showResult(json) {
                    	 
                        if (json['code'] == 'success') {
                            window.location.href = '/biz/sender/image_w_list';
                        }
                        else {
                            var message = json['message'];
                            console.log(message);
                            var messagekr = '';
                            if (message == 'UnknownException') {
                                messagekr = '관리자에게 문의하십시오';
                            } else if (message == 'InvalidImageMaxLengthException') {
                                messagekr = '이미지 용량을 초과하였습니다 (최대500KB)';
                            } else if (message == 'InvalidImageSizeException') {
                                messagekr = '가로 500px 미만 또는 가로*로 비율이 1:1.5 초과시 업로드 불가합니다';
                            } else if (message == 'InvalidImageFormatException') {
                                messagekr = '지원하지 않는 이미지 형식입니다 (jpg,png만 가능)';
                            } else {
                                messagekr = '관리자에게 문의하십시오.(' + message + ")";
                            }

                            var text = '이미지 업로드에 실패하였습니다' + '\n' + messagekr;
                            $(".content").html(text.replace(/\n/g, "<br/>"));
                            $("#myModal").modal('show');
                            $(document).unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".btn-primary").click();
                                }
                            });
                        }
                    }

              //  }
            }
        }

        //파일 용량 및 확장자 확인 ->2MB제한
        function upload_wide() {
            var thumbext = document.getElementById('image_w_file').value;
            var file_data = new FormData();
            file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
            file_data.append("image_file", $("input[name=image_w_file]")[0].files[0]);
			
            thumbext = thumbext.slice(thumbext.indexOf(".") + 1).toLowerCase();

            if (thumbext) {
                $.ajax({
                    url: "/biz/sender/image_w_upload",
                    type: "POST",
                    data: file_data,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#overlay').fadeIn();
                    },
                    complete: function () {
                        $('#overlay').fadeOut();
                    },
                    success: function (json) {
                        showResult(json);
                    },
                    error: function (data, status, er) {
                        $(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오..");
                        $("#myModal").modal('show');
                    }
                });
                function showResult(json) {
                	 
                    if (json['code'] == 'success') {
                        window.location.href = '/biz/sender/image_w_list';
                    }
                    else {
                        var message = json['message'];
                        console.log(message);
                        var messagekr = '';
                        if (message == 'UnknownException') {
                            messagekr = '관리자에게 문의하십시오';
                        } else if (message == 'InvalidImageMaxLengthException') {
                            messagekr = '이미지 용량을 초과하였습니다 (최대2MB)';
                        //} else if (message == 'InvalidImageSizeException') {
                        //    messagekr = '가로 500px 미만 또는 가로*로 비율이 1:1.5 초과시 업로드 불가합니다';
                        } else if (message == 'InvalidImageFormatException') {
                            messagekr = '지원하지 않는 이미지 형식입니다 (jpg,png만 가능)';
                        } else {
                            messagekr = '관리자에게 문의하십시오.(' + message + ")";
                        }

                        var text = '이미지 업로드에 실패하였습니다' + '\n' + messagekr;
                        $(".content").html(text.replace(/\n/g, "<br/>"));
                        $("#myModal").modal('show');
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".btn-primary").click();
                            }
                        });
                    }
                }
            }
        }  
              
        function img_size(){
            var img_file = $("#image_file")[0].files[0];
            var _URL = window.URL || window.webkitURL;
            var img = new Image();
            img.src = _URL.createObjectURL(img_file);
            img.onload = function() {
                var img_width = img.width;
                var img_height = img.height;
                var img_check = true;
                if (img_width/img_height < 0.75 && img_width/img_height > 2) { //이미지 사이즈 체크
                    img_check = false;
                }
                upload(img_check);
            };
        }

        function wide_img_size(){
            var img_file = $("#image_w_file")[0].files[0];
            var _URL = window.URL || window.webkitURL;
            var img = new Image();
            img.src = _URL.createObjectURL(img_file);
            img.onload = function() {
                var img_width = img.width;
                var img_height = img.height;
                //var img_check = true;
                //if (img_width/img_height < 0.75 && img_width/img_height > 2) { //이미지 사이즈 체크
                //    img_check = false;
                //}
                upload_wide();
            };
        }

        //이미지 상세보기
        function img_detail(obj) {
            var img_url = $("#img_"+obj).val();
            var html = '<div align="center"><table class="tpl_ver_form"><tr><th style="width:90px;">이미지 URL</th><td style="word-break: break-all;">' + img_url + '</td></tr></table><br><br><img align="center" src="' + img_url + '" style="width:100%;"/></div>';
            $(".content").html(html);
            $(".modal-dialog").css("width","420px").css("height","450px").css("top","10%"); //TODO 높이설정
            $("#myModal").modal('show');

        }

        //선택된 이미지 이름 확인
        function check_image(obj) {
            var sum = 0, tag = [];
            var chk = document.getElementsByName(obj);
            var tot = chk.length;
            for (var i = 0; i < tot; i++) {
                if (chk[i].checked == true) {
                    var url = chk[i].value;
                    tag[sum] = url.slice(url.lastIndexOf('/') + 1);
                    sum++;
                }
            }

            return tag
        }


        //이미지 삭제
        function delete_image() {
            var image_name = check_image("chk_image[]");

            var obj_image_name = [];
            for (var i = 0; i < check_image("chk_image[]").length; i++) {
                obj_image_name.push(image_name[i]);
            }
            if (check_image("chk_image[]").length > 0) {

                $(".content2").html(check_image("chk_image[]").length + "개를 삭제 하시겠습니까?");
                $("#myModal2").modal('show');
                click_btn_primary();
                $("#confirm_btn").click(function () {

                    $.ajax({
                        url: "/biz/sender/image_delete",
                        type: "POST",
                        data: {
                            <?=$this->security->get_csrf_token_name()?>: '<?=$this->security->get_csrf_hash()?>',
                            image_name: JSON.stringify({image_name: obj_image_name}),
                            count: check_image("chk_image[]").length
                        },
                        beforeSend: function () {
                            $('#overlay').fadeIn();
                        },
                        complete: function () {
                            $('#overlay').fadeOut();
                        },
                        success: function (json) {
                            showResult(json);
                        },
                        error: function (data, status, er) {
                            $(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오.");
                            $("#myModal").modal('show');
                        }
                    });
                    function showResult(json) {
                            var text = '이미지 삭제 요청하였습니다.' + '\n' + '[요청결과] 성공 : ' + json['success'] + '건, ' + '실패 :' + json['fail'] + '건';
                            $(".content").html(text.replace(/\n/g, "<br/>"));
                            $("#myModal").modal('show');
                            $("#myModal").unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".btn-primary").click();
                                }
                            });
                        }
                        $('#myModal').on('hidden.bs.modal', function () {
                            window.location.href = '/biz/sender/image_w_list';
                        });
                });


            } else {
                
                $(".content").html("삭제할 이미지를 선택해주세요.");
                $("#myModal").modal('show');
                click_btn_primary();
            }
        }


        function open_page(page) {
            var form = document.getElementById('mainForm');
            var cfrsField = document.createElement("input");
            var pageField = document.createElement("input");
				cfrsField.setAttribute("type", "hidden");
				cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
				cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
            pageField.setAttribute("type", "hidden");
            pageField.setAttribute("name", "page");
            pageField.setAttribute("value", page);
            form.appendChild(pageField);
            form.submit();
        }

        $('#mainForm').submit(function () {
            $('#overlay').fadeIn();
            return true;
        });

    </script>
