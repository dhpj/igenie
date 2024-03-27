<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>이미지관리</title>
<link rel="canonical" href="http://dhn.webthink.co.kr/" /><link rel="stylesheet" type="text/css" href="http://dhn.webthink.co.kr/assets/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="http://dhn.webthink.co.kr/assets/css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="/bizM/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="http://dhn.webthink.co.kr/views/_layout/bootstrap/css/style.css" />
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" />
<!-- link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/ui-lightness/jquery-ui.css" /-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap-theme.min.css'); ?>" />
<link href="/bizM/css/datepicker3.css" rel="stylesheet" type="text/css">

<!-- Theme -->
<link href="/bizM/css/main.css" rel="stylesheet" type="text/css">
<link href="/bizM/css/plugins.css" rel="stylesheet" type="text/css">
<!--<link href="/collected_statics/assets/css/responsive.css" rel="stylesheet" type="text/css" />-->
<link href="/bizM/css/icons.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/bizM/css/font-awesome.min.css">
<!--[if IE 7]>
<link rel="stylesheet" href="/collected_statics/assets/css/fontawesome/font-awesome-ie7.min.css">
<![endif]-->
<!--[if IE 8]>
<link href="assets/css/ie8.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href="/bizM/css/css.css" rel="stylesheet" type="text/css">
<link href="/css/common.css" rel="stylesheet" type="text/css">
<!--=== JavaScript ===-->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<!-- script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script-->

<script type="text/javascript" src="/bizM/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-datepicker.kr.js"></script>
<script type="text/javascript" src="/bizM/js/bootstrap-filestyle.min.js"></script>
<script type="text/javascript" src="/bizM/js/lodash.compat.min.js"></script>
</head>

<body style="overflow-y:auto;">

<!-- div class="modal fade" id="myModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true" xmlns="http:/www.w3.org/1999/html">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
        	<br/>
            <div class="modal_body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" xmlns="http:/www.w3.org/1999/html">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal_content">
            <br/>
            <div class="modal_body">
                <div class="content2">
                </div>
                <div>
                    <p align="right">
                        <button type="button" id="close_btn" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" id="confirm_btn" class="btn btn-primary" data-dismiss="modal">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div -->

<form action="/biz/sender/images" method="post" id="mainForm">
    <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
    <div class="modal-dialog modal-lg select-dialog" id="modal">
        <div class="modal_content">
	        <div class="modal_title">
		        <h2>이미지 선택</h2>
	        </div>
	        <!--div class="tab_group">
			    <button class="btn" type="button"><i class="material-icons">add</i> 이미지추가</button>
			</div-->
            <div class="modal_body">
                    <div class="thumb">
                        <ul class="thumblist" >
                        <?$cnt=0;
						foreach($list as $r) { $cnt++;?>

                            <li class="img_select">
								<input type="checkbox" class="uniform" name="chk_image" id="chk<?=$cnt?>" value="<?=$r->img_url?>" tag="<?=$base_url?>/pop/<?=$r->img_filename?>" ><label for="chk<?=$cnt?>"></label>
                                <div class="thumb_img" style="background-image: url('<?=$r->img_url?>');" onclick="javascript:check_image('chk<?=$cnt?>');">
                                    <input type="hidden" id="img_<?=$r->img_id?>" value="<?=$base_url?>/pop/<?=$r->img_filename?>">
                                </div>
                            </li>

                        <?}?>
                        </ul>
                    </div>
            </div>
            <div class="tc"><?=$page_html?></div>
            <div class="modal_bottom">
                <button class="btn btn-default" data-dismiss="modal" type="button" onclick="javascript:window.close();">취소
                </button>
                <button class="btn btn-primary" type="button" onclick="javascript:sendValue()">확인
                </button>
            </div>

        </div>
    </div><!--content_wrap-->
</form>

<style type="text/css">
    html {
        overflow: hidden;
    }

    .modal {
        position: fixed !important;
        left: 25% !important;
        top: 55% !important;
    }

    .select-dialog {
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
    }
</style>

<script type="text/javascript">
    $("#nav li.nav01").addClass("current open");
    //$('#myModal2').modal("hide");

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

    //선택된 이미지 확인
    function check_image(obj) {
        var sum = 0, tag = [];
        var chk = document.getElementsByName(obj);
        var chk_id = document.getElementsByName("chk_image[]");
        var chk_url = document.getElementsByName("chk_image_url[]");
        var tot = chk.length;

        for (var i = 0; i < tot; i++) {
            if (chk[i].checked == true) {
                var url = chk[i].value;
                chk_id[i].checked = true;
                chk_url[i].checked = true;
                tag[sum] = url.slice(url.lastIndexOf('/') + 1);
                sum++;
            } else {
                chk_id[i].checked = false;
                chk_url[i].checked = false;
            }
        }
        return tag
    }

    function check_image_url(obj) {
        var sum = 0, tag = [];
        var chk = document.getElementsByName(obj);
        var chk_url = document.getElementsByName("chk_image_url[]");
        var tot = chk.length;

        for (var i = 0; i < tot; i++) {
            if (chk[i].checked == true) {
                chk_url[i].checked = true;
                tag[sum] = chk_url[i].value;
                sum++;
            } else {
                chk_url[i].checked = false;
            }
        }
        return tag
    }


    function sendValue() {
        var checkbox = document.getElementsByName("chk_image");
        var check_count = 0;
        var image_url = "";
        var image_link = "";
        for(var i = 1; i <= checkbox.length; i++) {
            if($("#chk"+i).is(":checked") == true) {
                check_count++;
                image_url = $("#chk"+i).val();
					 if(typeof($("#chk"+i).attr("tag"))!=="undefined" && $("#chk"+i).attr("tag")!=null)
						image_link = $("#chk"+i).attr("tag");
					 else
						 image_link = $("#chk"+i).val();
            }
        }
        if (check_count == 1) {
            opener.setImageValue(image_url, image_link);
            window.close();
        } else if (check_count == 0) {
            $(".content").html("이미지를 선택해주세요.");
            $("#myModal").modal('show');
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".btn-primary").click();
                }
            });
        }
        else {
            $(".content").html("이미지는 1개만 선택이 가능합니다.");
            $("#myModal").modal('show');
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".btn-primary").click();
                }
            });
        }
    }

    //이미지 삭제
    function delete_image() {
        var csrftoken = getCookie('csrftoken');
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
                    url: "/sender/image_delete",
                    type: "POST",
                    data: {
                        csrfmiddlewaretoken: csrftoken,
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
                    $('#myModal').on('hidden.bs.modal', function () {
                        window.location.href = '/sender/images';
                    });
                }
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
</body>
</html>
