    <head>
        <meta http-equiv="Expires" content="0"/>
        <meta http-equiv="Pragma" content="no-cache"/>
    </head>
    <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb">
            <li><i class="icon-home"></i><a href="/dhnbiz/sender/send/talk">발신</a></li>
            <li><a href="#">발신</a></li>
            <li class="current"><a href="#" title="">알림톡</a></li>
        </ul>
    </div>
    <div class="snb_nav">
        <ul class="clear">
            <li class="active"><a href="/dhnbiz/sender/send/talk">알림톡</a></li>
            <li><a href="/dhnbiz/sender/send/friend">친구톡</a></li>
        </ul>
    </div><!--//.snb_nav-->
    <form action="/dhnbiz/sender/talk/send" method="post" id="sendForm" name="sendForm" onsubmit="return false;" enctype="multipart/form-data">
	 <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
    <div class="content_wrap">
        <div class="col-xs-8">
            <div class="widget box mt20">
                <div class="widget-header">
                    <h4>템플릿 선택</h4>
                </div>
                <div class="widget-content" id="send_template_content" >



                </div>
				</div>

            <div class="widget box mt20">
                <div class="widget-header">
                    <h4>전화번호 파일 업로드 및 양식 다운로드</h4>
                </div>
                <div class="widget-content">
                    <div class="row">
                        <div class="col-xs-9 file">
                            <button type="button" onclick="upload();" class="btn pull-right" style="width:112px; margin:0px -6px 0 350px; position:absolute; z-index:10;"><i class="icon-folder-open"></i> 파일업로드</button></td>
                            <input type="file" name="filecount" id="filecount" multiple="multiple" onchange="readURL(this);" style="cursor: default; padding: 20px 20px 20px 20px !important;">
                        </div>
                        <div class="col-xs-2">
                            <button class="btn pull-right" type="button" onclick="download();" style="width:158px;margin-right:-60px;"><i class="icon-cloud-download"></i>업로드 양식 다운로드</button>
                        </div>
                    </div>
                    <br/><p style="color:#a94442;" align="right">템플릿별로 양식을 다운로드 하시기 바랍니다.</p>
                    <script type="text/javascript">
                        $('#filecount').filestyle({
                            input: true,
                            buttonName: '',
                            iconName: 'icon-folder-open',
                            buttonText: '파일업로드'
                        });
                    </script>
                    <style>
                        .file input{
                            cursor: default !important;
                        }
                        .file button{
                            position: absolute;
                            z-index: -1;
                        }
                    </style>
                </div>
            </div>
            <div class="widget box mt10">
                <div class="widget-header tel_header">
                    <h4 class="tel_h4">수신 번호</h4>
                </div>
                <div class="widget-content tel_content">
                    <table class="table table-fixedheader table-checkable mt10 table-center" id="tel">
                        <thead>
                        <tr id="tel_tr">
                            <th class="checkbox-column" style="width:10% !important"><input type="checkbox" id="all_select" class="uniform"></th>
                            <th width="70%">전화번호</th>
                            <th width="20%">삭제</th>
                        </tr>
                        </thead>
                        <tbody id="tel_tbody" style="height:47px">
                            <tr class="1" id="tel_tbody_tr">
                                <td class="checkbox-column" style="width:10% !important"><input type="checkbox" name="sel_del" id="sel_del" value="1" class="uniform"></td>
                                <td width="70%">
                                    <input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number"
                                                       placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);">
                                </td>
                                <td width="20%"><a href="javascript:tel_remove(1);" id="tel_remove" name="tel_remove" class="btn btn-sm" title="삭제"><i
                                        class="icon-trash"></i> 삭제</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                

                <div class="widget-content">
                    <div class="mt20 align-center">
                        <button class="btn" type="button" id="number_add" onclick="number_add();"><i class="icon-plus"></i> 번호 추가</button>
                        <button class="btn" type="button" id="select_del" onclick="selectDelRow();"><i class="icon-trash"></i> 선택 삭제</button>
                        <button class="btn select_send" id="select_send" type="button" onclick="send_select();"><i class="icon-ok"></i> 선택 발송</button>
                        <button class="btn btn-custom alll" type="button" onclick="all_send();"><i class="icon-download-alt"></i> 전체 발송</button>
                        <br><br><br><br><br><br><br><br>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="preview_wrap">
            
                <p><img src="/bizM/images/img_phone.jpg" alt="" style="width:100%; height: 550px;"></p>
            
                <div class="abs_box">
                    <p class="pre_txt">미리보기</p>
                    <div class="scroller" data-height="470px" data-always-visible="1" data-rail-visible="0">
                        <div class="txt" id="phone_standard">
                            <span><img src="/bizM/images/bullet_edge.png" alt=""></span>
                            
                            <p class="message" style="height: 20px;">템플릿을 선택해주세요.</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--//.content_wrap-->
    </form>
    <!--템플릿 선택-->
    <div class="modal fade" id="template_danger" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal" style="width:400px;">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                        <div class="row align-center" id="danger" style="height: 300px; padding-top: 70px;">
                            <p class="alert alert-danger">
                                승인된 템플릿이 없습니다. 템플릿을 등록해주세요.
                            </p>
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-top: 85px;">닫기</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal select fade" id="template_select" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y:hidden;">
        <div class="modal-dialog modal-lg select-dialog" id="modal">
            <div class="modal-content">
                <br/>
                <h4 class="modal-title" align="center">템플릿 선택하기</h4>
                <div class="modal-body select-body">
                    <div class="select-content">
                        <div class="mb20 mt10 clear">
                            <select class="select2 input-width-medium" id="searchType2" onchange="getSelectValue(this.form);">
										 <option value="ALL">검색항목</option>
										 <option value="company">업체명</option>
										 <option value="code">템플릿코드</option>
										 <option value="name">템플릿명</option>
										 <option value="contents">메세지 내용</option>
                            </select>&nbsp;
                            <input type="text" class="form-control input-width-medium inline"
                                   id="searchStr2" name="search" placeholder="검색어 입력" value=""/>&nbsp;
                            <input type="button" class="btn btn-default" id="check" value="조회" onclick="location.href='javascript:search_profile();'"/>
                        </div>

                        <div class="widget-content" id="template_list">

								
								</div>
                    </div>
                    <div align="center">
                        <br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary" id="code" name="code" onclick="select_template();">확인</button>
                        <br/><br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content identify">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">
                                확인
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalCheck" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default dismiss" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary submit">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalSelect" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content select_send">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary select-send" onclick="select_move();">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalAll" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary all" onclick="all_move();">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalDel" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary del">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalSelDel" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary selDel">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalTemp" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary temp">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalUpload" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary up">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalDownload" tabindex="-1" role="dialog"
         aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary down">확인</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
    .text {
        vertical-align: middle !important;
        line-height: 20px !important;
    }
    .scrolltbody {
        border-collapse: collapse;
        padding: 0!important;
    }
    .scrolltbody th { text-align: center }
    .select{
        vertical-align: middle;
        top: 50%;
        transform: translateY(-50%);
        overflow-x: hidden;
        overflow-y: hidden;
        height: 720px;
    }
    .select-dialog {
        width: 820px;
        height: 1020px;
    }
    .select-body {
        width: 100%;
        height: 650px;
    }
    #wrap {
        position: absolute;
    }
    .modal-open {
        overflow: hidden;
        position: fixed;
        width: 100%;
    }
</style>
<script type="text/javascript">
    $("#nav li.nav01").addClass("current open");

    //스크롤 올라가는 현상 방지
    function scroll_prevent() {
        window.onscroll = function(){
          var arr = document.getElementsByTagName('textarea');
            for( var i = 0 ; i < arr.length ; i ++  ){
                arr[i].blur();
            }
        }
    }

    //확인모달 엔터 누르면 닫기
    $("#myModal").unbind("keyup").keyup(function (e) {
        var code = e.which;
        if (code == 13) {
            $(".enter").click();
        }
    });

    //템플릿 선택 - 검색 포커스일때, 아닐때 엔터
    $("#template_select").unbind("keyup").keyup(function (e) {
        var code = e.which;
        if ($("#search").is(":focus")) {
            if(code == 13) {
                search_enter();
            }
        } else{
            if(code == 13) {
                select_template();
            }
        }
    });

    //수신번호 추가 클릭시 이동
    $("#number_add").click(function () {
            number_add();
    });

    //템플릿 선택 모달
    var templi = '';
    if(templi!="") {
        var count = '';
        if('' == "none") {
            $('#template_danger').modal('show');
        } else {
            $('#template_select').modal('show');
        }
    }

    //템플릿 선택시 CSS 변경
    var selected="";
    var selected_profile="";
    $(".scrolltbody tbody tr").click(function() {
        $(".scrolltbody tbody tr").css("background-color", "white");
        $(".scrolltbody tbody tr").css("color", "dimgrey");
        $(this).css("background-color", "#4d7496");
        $(this).css("color", "white");
        selected = $(this).find(".tmp_code").text();
        selected_profile = $(this).find(".tmp_profile").text();
    });

    //미리보기 - 클릭시 그림자 효과 없애기
    $("#text").click(function() {
        $(this).css("box-shadow","none");
    });

    //수신번호 전체 선택 안되는 현상 수정
    $("#all_select").click(function(){
        if($("#all_select").prop("checked")) {
            $("input:checkbox[name='sel_del']").prop("checked",true);
        } else {
            $("input:checkbox[name='sel_del']").prop("checked",false);
        }
    });

    //미리보기 - 템플릿내용
    function templi_preview(obj) {
        $("#text").val(obj.value);
        var height = $("#text").prop("scrollHeight");
        if (height < 468) {
            $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
        } else {
            var message = $("#text").val();
            var height = $("#text").prop("scrollHeight");
            if (message == "") {
                $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
            } else {
                $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                $("#templi_cont").keyup(function() {
                    $(".scroller").scrollTop($(".scroller")[0].scrollHeight);
                });
            }
        }
    }

    //미리보기 - 버튼링크
    function link_preview() {
        
    }

    //템플릿 선택 - 모달 확인
    function select_template() {
			selected = $("#template_list").find(".active").find(".tpl_id").val();
			selected_profile = $("#template_list").find(".active").find(".pf_key").val();
		  if(selected=="") {
            $(".content").html("템플릿을 먼저 선택해주세요.");
            $("#myModal").modal({backdrop: 'static'});
            $("#myModal").on('hidden.bs.modal', function () {
                $("#template_select").focus();
            });
        } else {
				var form = document.createElement("form");
				document.body.appendChild(form);
				form.setAttribute("method", "post");
				form.setAttribute("action", "/dhnbiz/sender/send/talk");
				var csrfField = document.createElement("input");
				csrfField.setAttribute("type", "hidden");
				csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
				csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
				form.appendChild(csrfField);
            var selectedField = document.createElement("input");
            selectedField.setAttribute("type", "hidden");
            selectedField.setAttribute("name", "tmp_code");
            selectedField.setAttribute("value", selected);
            form.appendChild(selectedField);
            var selProfileField = document.createElement("input");
            selProfileField.setAttribute("type", "hidden");
            selProfileField.setAttribute("name", "tmp_profile");
            selProfileField.setAttribute("value", selected_profile);
            form.appendChild(selProfileField);
            form.submit();
        }
    }

    //템플릿 선택 - 검색 후 엔터 누를 경우
    function search_enter() {
        $(document).unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                $("#check").click();
            }
        });
    }

    //예약발송 체크박스 체크
    function ReserveCheck() {
        if($("#reserve_check").is(":checked") == true) {
            $(".reserve_content").attr("hidden", false);
        } else {
            $(".reserve_content").attr("hidden", true);
        }
    }

    //수신번호 추가
    function number_add() {
        var lastItemNo = $("#tel tbody tr:last").attr("class");
        var no = parseInt(lastItemNo) + 1;
        var table = document.getElementById("tel");
        var row_index = table.rows.length;
        if ($("#templi_code").val() == null) {
            $(".content").html("템플릿을 먼저 선택해주세요.");
            $('#myModal').modal({backdrop: 'static'});
        } else if ($("input:checkbox[value='1']").is(":checked") == true) {
            if (row_index <= 100) {
                if (no == 2) {
                    var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                            '<input name="sel_del" id="' + no + '" class="uniform" value="' + no + '" type="checkbox"></td><td width="70%"><input type="text" ' +
                            'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
                            '<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
                            '<i class="icon-trash"></i> 삭제</a></td></tr>';
                    $("." + 1).after(tr);
                    var height = $("#tel_tbody").prop("scrollHeight");
                    $("#tel_tbody").css("height", (height) + "px");
                    $("#" + no).uniform();
                } else if (row_index >= 5) {
                    var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                            '<input class="uniform" name="sel_del" id="' + no + '" value="' + no + '" type="checkbox"></td><td width="70%"><input type="text" ' +
                            'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
                            '<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
                            '<i class="icon-trash"></i> 삭제</a></td></tr>';
                    $("." + lastItemNo).after(tr);
                    $("#" + no).uniform();
                    var height = $("#tel_tbody").prop("scrollHeight");
                    $("#tel_tbody").css("height", "235px");
                    $(this).css("overflow", "scroll");
                    $("tr." + no).focus();
                    $("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
                } else {
                    var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                            '<input class="uniform" name="sel_del" id="' + no + '"  value="' + no + '" type="checkbox"></td><td width="70%"><input type="text" ' +
                            'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
                            '<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
                            '<i class="icon-trash"></i> 삭제</a></td></tr>';
                    $("." + lastItemNo).after(tr);
                    var height = $("#tel_tbody").prop("scrollHeight");
                    $("#tel_tbody").css("height", (height) + "px");
                    $("#" + no).uniform();
                }
            } else {
                $(".content").html("최대 100개까지 가능합니다.");
                $("#myModal").modal({backdrop: 'static'});
            }
        } else {
            if (row_index <= 100) {
                if (row_index > 5) {
                    var tr = '<tr class="' + no + '" name="' + no + '" id="tel_tbody_tr" name="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                            '<input type="checkbox" name="sel_del" id="' + no + '"  value="' + no + '" class="uniform"></td><td width="70%"><input type="text" ' +
                            'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
                            '<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
                            '<i class="icon-trash"></i> 삭제</a></td></tr>';
                    $("." + lastItemNo).after(tr);
                    $("#" + no).uniform();
                    var height = $("#tel_tbody").prop("scrollHeight");
                    $("#tel_tbody").css("height", "235px");
                    $(this).css("overflow", "scroll");
                    $("tr." + no).focus();
                    $("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
                } else {
                    var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                            '<input class="uniform" name="sel_del" id="' + no + '"  value="' + no + '" type="checkbox"></td><td width="70%"><input type="text" ' +
                            'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
                            '<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn btn-sm" title="삭제">' +
                            '<i class="icon-trash"></i> 삭제</a></td></tr>';
                    $("." + lastItemNo).after(tr);
                    var height = $("#tel_tbody").prop("scrollHeight");
                    $("#tel_tbody").css("height", (height) + "px");
                    $("#" + no).uniform();
                }
            } else {
                $(".content").html("최대 100개까지 가능합니다.");
                $("#myModal").modal({backdrop: 'static'});
            }
        }
    }

    //수신번호 개별삭제
    function tel_remove(obj) {
        var table = document.getElementById("tel");
        var row_index = table.rows.length;
        if ($("#templi_code").val() == null) {
            $(".content").html("템플릿을 먼저 선택해주세요.");
            $('#myModal').modal({backdrop: 'static'});
        } else if (row_index == 2) {
            var tel_number = $("#tel_number").val();
            if (tel_number == "") {
                $(".content").html("삭제할 수신 번호가 없습니다.");
                $("#myModal").modal({backdrop: 'static'});
                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".enter").click();
                    }
                });
            } else {
                $(".content").html("삭제하시겠습니까?");
                $("#myModalDel").modal({backdrop: 'static'});
                $("#myModalDel").unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        tel_del(obj);
                    }
                });
                $(".del").click(function () {
                    tel_del(obj);
                    $(".del").unbind("click");
                });
            }
        } else {
            $(".content").html("삭제하시겠습니까?");
            $("#myModalDel").modal({backdrop: 'static'});
            $("#myModalDel").unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    tel_del(obj);
                }
            });
            $(".del").click(function () {
                tel_del(obj);
                $(".del").unbind("click");
            });
        }
    }
    function tel_del(obj) {
        $("#myModalDel").modal("hide");
        var table = document.getElementById("tel");
        var row_index = table.rows.length-1;
        var tr = $("." + obj);

        if (row_index != 1) {
            if (row_index > 5) {
                tr.remove();
                var height = $("#tel_tbody").prop("scrollHeight");
                $("#tel_tbody").css("height", "235px");
                $(this).css("overflow", "scroll");
            } else if (row_index < 6) {
                tr.remove();
                var height = $("#tel_tbody").prop("scrollHeight");
                $("#tel_tbody").css("height", (height - 47) + "px");
            }
        } else {
            tr.remove();
            var tr = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                    '<input class="uniform" name="sel_del" id="'+ 1 +'"  value="' + 1 + '" type="checkbox"></td><td width="70%"><input type="text" ' +
                    'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
                    '<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
                    '<i class="icon-trash"></i> 삭제</a></td></tr>';
            $("#tel_tbody").html(tr);
            var height = $("#tel_tbody").prop("scrollHeight");
            $("#tel_tbody").css("height", "47px");
            $("#1").uniform();
        }
    }

    //수신번호 선택삭제
    function selectDelRow() {
        if ($("#templi_code").val() == null) {
            $(".content").html("템플릿을 먼저 선택해주세요.");
            $('#myModal').modal({backdrop: 'static'});
        } else if ($("input:checkbox[name='sel_del']").is(":checked") == false) {
            $(".content").html("삭제할 수신 번호를 선택해주세요.");
            $('#myModal').modal({backdrop: 'static'});
        } else {
            $("input[name=sel_del]:checked").each(function () {
                var obj = $(this).val();
                var table = document.getElementById("tel");
                var row_index = table.rows.length;
                if (row_index == 2) {
                    var tel_number = $("#tel_number").val();
                    if (tel_number == "") {
                        $(".content").html("삭제할 수신 번호가 없습니다.");
                        $("#myModal").modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".enter").click();
                            }
                        });
                    } else {
                        $(".content").html("삭제하시겠습니까?");
                        $("#myModalSelDel").modal({backdrop: 'static'});
                        $("#myModalSelDel").unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".selDel").click();
                            }
                        });
                        $(".selDel").click(function() {
                            tel_select_del(obj);
                            $(".submit").unbind("click");
                        });
                    }
                } else {
                    $(".content").html("삭제하시겠습니까?");
                    $("#myModalSelDel").modal({backdrop: 'static'});
                    $("#myModalSelDel").unbind("keyup").keyup(function (e) {
                        var code = e.which;
                        if (code == 13) {
                            $(".selDel").click();
                        }
                    });
                    $(".selDel").click(function() {
                        tel_select_del(obj);
                        $(".selDel").unbind("click");
                    });
                }
            });
        }
    }
    function tel_select_del(obj) {
        $("#myModalSelDel").modal("hide");
        var tr = $("." + obj);
        var table = document.getElementById("tel");
        var row_index = table.rows.length;
        var cnt = $("input[name=sel_del]:checked").length;
        var row = row_index-cnt;
        if(cnt!=0) {
            if (row_index >= 7) {
                tr.remove();
                var height = $("#tel_tbody").prop("scrollHeight");
                $("#tel_tbody").css("height", "235px");
                $(this).css("overflow", "scroll");
            } else if (row < 6 && row >= 2) {
                tr.remove();
                var height = $("#tel_tbody").prop("scrollHeight");
                $("#tel_tbody").css("height", (row*47-47) + "px");
            } else if (1 >= row) {
                tr.remove();
                $("#tel_number").val("");
                $("input[name=sel_del]").prop("checked", false);
                $('#all_select').prop('checked',false);
                $.uniform.update('#all_select');
                if(row==1) {
                    var tr = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                            '<input class="uniform" name="sel_del" id="'+ 1 +'"  value="' + 1 + '" type="checkbox"></td><td width="70%"><input type="text" ' +
                            'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
                            '<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
                            '<i class="icon-trash"></i> 삭제</a></td></tr>';
                    $("#tel_tbody").html(tr);
                    var height = $("#tel_tbody").prop("scrollHeight");
                    $("#tel_tbody").css("height", "47px");
                    $("#1").uniform();
                }
            }
        }
    }

    //수신번호 숫자만 입력 가능
    //숫자 여부 확인
    function check_digit(evt){
        if($("#templi_code").val()==null){
            $(".content").html("템플릿을 먼저 선택해주세요.");
            $("#myModal").modal({backdrop: 'static'});
            $('#myModal').on('hidden.bs.modal', function () {
                $("#tel_number").val("");
            });
        } else {
            var code = evt.which ? evt.which : event.keyCode;
            if (code < 48 || code > 57) {
                return false;
            }
        }
    }

    //모달 검색
    function search() {
        var search = $("#search").val();
        var selectBox = $("#selectBox option:selected").val();
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/dhnbiz/sender/send/template");
        var csrfField = document.createElement("input");
        csrfField.setAttribute("type", "hidden");
        csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(csrfField);
        var selectedField = document.createElement("input");
        selectedField.setAttribute("type", "hidden");
        selectedField.setAttribute("name", "search");
        selectedField.setAttribute("value", search);
        form.appendChild(selectedField);
        var selectedField2 = document.createElement("input");
        selectedField2.setAttribute("type", "hidden");
        selectedField2.setAttribute("name", "selectBox");
        selectedField2.setAttribute("value", selectBox);
        form.appendChild(selectedField2);
        form.submit();
    }

    //모달 페이징
    function page(page) {
        var search = $("#search").val();
        var selectBox = $("#selectBox option:selected").val();
        var form = document.createElement("form");
        document.body.appendChild(form); 
        form.setAttribute("method", "post");
        form.setAttribute("action", "/dhnbiz/sender/send/template");
        var csrfField = document.createElement("input");
        csrfField.setAttribute("type", "hidden");
        csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(csrfField);
        var pageField = document.createElement("input");
        pageField.setAttribute("type", "hidden");
        pageField.setAttribute("name", "page");
        pageField.setAttribute("value", page);
        form.appendChild(pageField);
        var searchField = document.createElement("input");
        searchField.setAttribute("type", "hidden");
        searchField.setAttribute("name", "search");
        searchField.setAttribute("value", search);
        form.appendChild(searchField);
        var selectField = document.createElement("input");
        selectField.setAttribute("type", "hidden");
        selectField.setAttribute("name", "selectBox");
        selectField.setAttribute("value", selectBox);
        form.appendChild(selectField);
        form.submit();
    }

    
    //선택 발송
    coin=0;
    function send_select() {
        window.onscroll = function(){
            var arr = document.getElementsByTagName('textarea');
            for( var i = 0 ; i < arr.length ; i ++  ){
                arr[i].off('blur');
            }
        };
        if($("#tel_temp").val()==undefined) {
            if ($("#templi_code").val() == null) {
                $(".content").html("템플릿을 먼저 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else if (document.getElementById("templi_cont").value.replace(/ /gi,"") == "") {
                $(".content").html("템플릿 내용을 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $('#myModal').on('hidden.bs.modal', function () {
                    $("#templi_cont").focus();
                });
            } else if (ms == "L" && document.getElementById("lms").value.replace(/ /gi,"") == "") {
                $(".content").html("LMS 내용을 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $('#myModal').on('hidden.bs.modal', function () {
                    $("#lms").focus();
                });
            } else if (ms == "S" && document.getElementById("sms").value.replace(/ /gi,"") == "") {
                $(".content").html("SMS 내용을 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $('#myModal').on('hidden.bs.modal', function () {
                    $("#sms").focus();
                });
            } else if ($("input:checkbox[name='sel_del']").is(":checked") == false) {
                $(".content").html("수신 번호를 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $('#myModal').on('hidden.bs.modal', function () {
                    $("#all_select").focus();
                });
                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".enter").click();
                    }
                });
            } else {
                var check = true;
                $("input:checkbox[name=sel_del]:checked").each(function () {
                    var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
                    if (array == "") {
                        check = false;
                        $(".content").html("수신 번호를 입력해주세요.");
                        $('#myModal').modal({backdrop: 'static'});
                        $('#myModal').on('hidden.bs.modal', function () {
                            $("input:checkbox[name=sel_del]:checked").each(function () {
                                array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
                                if (array.replace(/ /gi,"") == "") {
                                    $(this).parent().parent().parent().parent().find("#tel_number").focus();
                                }
                            });
                        });
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".enter").click();
                            }
                        });
                        return false;
                    }
                });
                $("#alimtalk_table tr").each(function () {
                    var focus;
                    if($(this).find("#btn_url").val() != undefined) {
                        if ($(this).find("#btn_url").val().trim() == "") {
                            check = false;
                            focus = $(this).find("#btn_url");
                            $(".content").html("버튼링크를 입력해주세요.");
                            $("#myModal").modal('show');
                            $('#myModal').on('hidden.bs.modal', function () {
                                focus.focus();
                            });

                        }
                    }
                });
                if($("#reserve_check").is(":checked") == true) {
                    if($("#reserve").val().trim() == ""){
                        check = false;
                        $(".content").html("예약 발송 일시를 선택해주세요.");
                        $('#myModal').modal({backdrop: 'static'});
                    } else {
                        var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
                        var reserveDt = reserve+"00";
                        var now = moment().add(10,'minutes');
                        var min = now.format("YYYYMMDDHHmm");
                        var minDt = min+"00";
                        if(reserveDt < minDt){
                            check = false;
                            $(".content").html("예약발송은 최소 10분 이후로 입력해주세요.");
                            $('#myModal').modal({backdrop: 'static'});
                        } else {
                            check = true;
                        }
                    }
                }

                if(check == false) return false;
                $.ajax({
                    url: "/dhnbiz/sender/coin",
                    type: "POST",
                    success: function (json) {
                        coin = json['coin'];
                        var kakao = Math.floor(Number(coin) / Number(at_price));
                        var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                        var sms = Math.floor(Number(coin) / Number(sms_price));
                        var s = sms.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                        var lms = Math.floor(Number(coin) / Number(lms_price));
                        var l = lms.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                        var cnt = $("input[name=sel_del]:checked").length;
                        if (ms == "S") {
                            $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/>SMS 발송 가능 건수 : " + s + "건<br/><br/>선택 발송하시겠습니까? (발송할 건수 : "+cnt+"건)");
                            $("#myModalSelect").modal({backdrop: 'static'});
                            $("#myModalSelect").unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".select-send").click();
                                }
                            });
                            $(".select-send").click(function () {
                                $("#myModalSelect").modal('hide');
                            });
                        } else if (ms == "L") {
                            $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/>LMS 발송 가능 건수 : " + l + "건<br/><br/>선택 발송하시겠습니까? (발송할 건수 : "+cnt+"건)");
                            $("#myModalSelect").modal({backdrop: 'static'});
                            $("#myModalSelect").unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".select-send").click();
                                }
                            });
                            $(".select-send").click(function () {
                                $("#myModalSelect").modal('hide');
                            });
                        } else {
                            $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/><br/>선택 발송하시겠습니까? (발송할 건수 : "+cnt+"건)");
                            $("#myModalSelect").modal({backdrop: 'static'});
                            $("#myModalSelect").unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".select-send").click();
                                }
                            });
                            $(".select-send").click(function () {
                                $("#myModalSelect").modal('hide');
                            });
                        }
                    }
                });
            }
        } else {
            var check = true;
            $("input:checkbox[name=sel_del]:checked").each(function () {
                var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
                if (array == "") {
                    check = false;
                    $(".content").html("수신 번호를 입력해주세요.");
                    $('#myModal').modal({backdrop: 'static'});
                    $('#myModal').on('hidden.bs.modal', function () {
                        $("input:checkbox[name=sel_del]:checked").each(function () {
                            array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
                            if (array.replace(/ /gi,"") == "") {
                                $(this).parent().parent().parent().parent().find("#tel_number").focus();
                            }
                        });
                    });
                    $(document).unbind("keyup").keyup(function (e) {
                        var code = e.which;
                        if (code == 13) {
                            $(".enter").click();
                        }
                    });
                    return false;
                }
            });
            if($("#reserve_check").is(":checked") == true) {
                if($("#reserve").val().trim() == ""){
                    check = false;
                    $(".content").html("예약 발송 일시를 선택해주세요.");
                    $('#myModal').modal({backdrop: 'static'});
                } else {
                    var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
                    var reserveDt = reserve+"00";
                    var now = moment().add(10,'minutes');
                    var min = now.format("YYYYMMDDHHmm");
                    var minDt = min+"00";
                    if(reserveDt < minDt){
                        check = false;
                        $(".content").html("예약발송은 최소 10분 이후로 입력해주세요.");
                        $('#myModal').modal({backdrop: 'static'});
                    } else {
                        check = true;
                    }
                }
            }

            if(check == false) return false;
            if ($("input:checkbox[name='sel_del']").is(":checked") == false) {
                $(".content").html("수신 번호를 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $('#myModal').on('hidden.bs.modal', function () {
                    $("#all_select").focus();
                });
                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".enter").click();
                    }
                });
            } else {
                $.ajax({
                    url: "/dhnbiz/sender/coin",
                    type: "POST",
                    success: function (json) {
                        coin = json['coin'];
                        var kakao = Math.floor(Number(coin) / Number(at_price));
                        var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                        var sms = Math.floor(Number(coin) / Number(sms_price));
                        var s = sms.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                        var lms = Math.floor(Number(coin) / Number(lms_price));
                        var l = lms.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                        var cnt = $("input[name=sel_del]:checked").length;
                        if (ms == "S") {
                            $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/>SMS 발송 가능 건수 : " + s + "건<br/><br/>업로드 파일을 선택 발송하시겠습니까?<br/>(발송할 건수 : "+cnt+"건)");
                            $("#myModalSelect").modal({backdrop: 'static'});
                            $("#myModalSelect").unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".select-send").click();
                                }
                            });
                            $(".select-send").click(function () {
                                $("#myModalSelect").modal('hide');
                            });
                        } else if (ms == "L") {
                            $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/>LMS 발송 가능 건수 : " + l + "건<br/><br/>업로드 파일을 선택 발송하시겠습니까?<br/>(발송할 건수 : "+cnt+"건)");
                            $("#myModalSelect").modal({backdrop: 'static'});
                            $("#myModalSelect").unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".select-send").click();
                                }
                            });
                            $(".select-send").click(function () {
                                $("#myModalSelect").modal('hide');
                            });
                        } else {
                            $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/><br/>업로드 파일을 선택 발송하시겠습니까?<br/>(발송할 건수 : "+cnt+"건)");
                            $("#myModalSelect").modal({backdrop: 'static'});
                            $("#myModalSelect").unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".select-send").click();
                                }
                            });
                            $(".select-send").click(function () {
                                $("#myModalSelect").modal('hide');
                            });
                        }
                    }
               });
            }
        }
    }
    function select_move() {
        var templi_cont = $("#templi_cont").val();
        var code = document.getElementById("templi_code").value;
        var profile = document.getElementById("pf_key").value;
        var kind = document.getElementById("kind").value;
        var msg = '';
        var tit = '';
        if(ms=="L"){
            msg = document.getElementById("lms").value;
            tit = document.getElementById("tit").value;
        } else if(ms=="S") {
            msg = document.getElementById("sms").value;
        } else {
            kind='';
            senderBox = '';
        }
        var reserveDt = "00000000000000";
        if($("#reserve_check").is(":checked") == true) {
            if($("#reserve").val().trim() != ""){
                var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
                reserveDt = reserve+"00";
            }
        }

        var tel_number = new Array();
        var tel_templi = new Array();
        var tel_sms = new Array();
        var tel_tit = new Array();

        var btn = [];
        for (var b=1;b<=5;b++) {
            btn[b] = new Array();
        }
        var obj = [];

        $("input:checkbox[name=sel_del]:checked").each(function (index, item) {
            var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
            tel_number.push(array);

            if($("#tel_temp").val() != undefined) {
                var temp = $(this).parent().parent().parent().parent().find("#tel_temp").val().trim();
                tel_templi.push(temp);
            } else {
                tel_templi.push(templi_cont);
            }
            if($("#tel_sms").val() != undefined) {
                var sms = $(this).parent().parent().parent().parent().find("#tel_sms").val().trim();
                tel_sms.push(sms);
            } else {
                tel_sms.push(msg);
            }
            if(ms=="L"){
                if($("#tel_sms").val() != undefined) {
                    var tot = $(this).parent().parent().parent().parent().find("#tel_sms").val().trim();
                    //var tit_slice = tot.slice(0,100);
                    var tit_slice = cutTextLength(tot, 20);
                    tel_tit.push(tit_slice);
                } else {
                    tit = document.getElementById("tit").value;
                    tel_tit.push(tit);
                }
            } else if (ms=="S"){
                var tit = '';
                tel_tit.push(tit);
            } else {
                var tit = '';
                tel_tit.push(tit);
            }
            var btn_num = 1;
            var url_count = 1;
            $("#alimtalk_table tr").each(function () {
                var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
                if(btn_type != undefined) {
                    obj[btn_num] = new Object();
                    obj[btn_num].type = btn_type;
                    obj[btn_num].name = $(this).find(document.getElementsByName("btn_name")).val();
                    if (btn_type == "WL") {
                        if($(item).parent().parent().parent().parent().find("#tel_url"+url_count).val() != undefined && $(this).next('tr').find("#hidden_url").val() != undefined) {
                            obj[btn_num].url_mobile = $(item).parent().parent().parent().parent().find("#tel_url"+url_count).val();
                            url_count++;
                            obj[btn_num].url_pc = $(item).parent().parent().parent().parent().find("#tel_url"+url_count).val();
                            url_count++;
                        } else if($(item).parent().parent().parent().parent().find("#tel_url"+url_count).val() != undefined && $(this).next('tr').find("#hidden_url").val() == undefined) {
                            obj[btn_num].url_mobile = $(item).parent().parent().parent().parent().find("#tel_url"+url_count).val();
                            url_count++;
                        } else {
                            obj[btn_num].url_mobile = $(this).find(document.getElementsByName("btn_url1")).val();
                            if ($(this).next('tr').find("#hidden_url").val() != undefined) {
                                obj[btn_num].url_pc = $(this).next('tr').find(document.getElementsByName("btn_url2")).val();
                            }
                        }
                    } else if (btn_type == "AL") {
                        var ios_count = url_count+1;
                        if($(item).parent().parent().parent().parent().find("#tel_url"+url_count).val() != undefined && $("#tel_url"+ios_count).val() != undefined) {
                            obj[btn_num].scheme_android = $(item).parent().parent().parent().parent().find("#tel_url"+url_count).val();
                            url_count++;
                            obj[btn_num].scheme_ios = $(item).parent().parent().parent().parent().find("#tel_url"+url_count).val();
                            url_count++;
                        } else {
                            obj[btn_num].scheme_android = $(this).find(document.getElementsByName("btn_url1")).val();
                            obj[btn_num].scheme_ios = $(this).next('tr').find(document.getElementsByName("btn_url2")).val();
                        }
                    }
                    btn[btn_num].push(obj[btn_num]);
                    btn_num++;
                }
            });
        });
        var btn1 = JSON.stringify(btn[1]);
        var btn2 = JSON.stringify(btn[2]);
        var btn3 = JSON.stringify(btn[3]);
        var btn4 = JSON.stringify(btn[4]);
        var btn5 = JSON.stringify(btn[5]);
        $.ajaxSettings.traditional = true;
        $.ajax({
            url: "/dhnbiz/sender/talk/select",
            type: "POST",
            data: {"tel_number":tel_number,"templi_cont":tel_templi,"msg":tel_sms,"kind":kind,
                "tit":tel_tit,"templi_code":code,"pf_key":profile,"senderBox":senderBox,
                "btn1":btn1,"btn2":btn2,"btn3":btn3,"btn4":btn4,"btn5":btn5,"reserveDt":reserveDt},
            beforeSend: function () {
                $('#overlay').fadeIn();
            },
            complete: function () {
                $('#overlay').fadeOut();
            },
            success: function (json) {
                var success = json['success'];
                var fail = json['fail'];
                var num = json['num'];
                var cnt = $("input[name=sel_del]:checked").length;
                var success_type = json['success_type'];
                var fail_type = json['fail_type'];
                var success_alim = '';
                var success_sms = '';
                var success_lms = '';
                var message = json['message'];
                var reserve = false;
                for (var i = 0; i < message.length; i++) {
                    if(message[i] == 'R000') {
                        reserve = true;
                    }
                }
                if (reserve == true) {
                    $(".content").html("예약발송 처리 되었습니다. <br>예약된 내역은 [발신 목록]에서 확인이 가능합니다.");
                    $('#myModal').modal({backdrop: 'static'});
                    $(document).unbind("keyup").keyup(function (e) {
                        var code = e.which;
                        if (code == 13) {
                            $(".enter").click();
                        }
                    });
                } else {
                    for (var i = 0; i < success_type.length; i++) {
                        if (success_type[i] == 'at') {
                            success_alim++;
                        } else if (success_type[i] == 'S') {
                            success_sms++;
                        } else if (success_type[i] == 'L') {
                            success_lms++;
                        }
                    }

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

                    if (success_alim != '') {
                        ga('send', 'event', '발송', '알림톡', '성공', success_alim);
                    }
                    if (success_sms != '') {
                        ga('send', 'event', '발송', 'SMS', '성공', success_sms);
                    }
                    if (success_lms != '') {
                        ga('send', 'event', '발송', 'LMS', '성공', success_lms);
                    }

                    if (fail != '0') {
                        var fail_alim = '';
                        var fail_sms = '';
                        var fail_lms = '';
                        var fail_etc = '';
                        for (var i = 0; i < fail_type.length; i++) {
                            if (fail_type[i] == 'at') {
                                fail_alim++;
                            } else if (fail_type[i] == 'S') {
                                fail_sms++;
                            } else if (fail_type[i] == 'L') {
                                fail_lms++;
                            } else if (fail_type[i] == '') {
                                fail_etc++;
                            }
                        }

                        if (fail_alim != '') {
                            ga('send', 'event', '발송', '알림톡', '실패', fail_alim);
                        }
                        if (fail_sms != '') {
                            ga('send', 'event', '발송', 'SMS', '실패', fail_sms);
                        }
                        if (fail_lms != '') {
                            ga('send', 'event', '발송', 'LMS', '실패', fail_lms);
                        }
                        if (fail_etc != '') {
                            ga('send', 'event', '발송', '기타(기타 오류)', '실패', fail_etc);
                        }

                        $(".content").html("발송 결과 : (성공 : " + success + "건, 실패 : " + fail + "건)<br/>실패 번호 : " + num);
                        $('#myModal').modal({backdrop: 'static'});
                        $(".modal-dialog").css("word-wrap", "break-word");
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".enter").click();
                            }
                        });
                    } else {
                        $(".content").html("발송 결과 : (성공 : " + success + "건, 실패 : " + fail + "건)");
                        $('#myModal').modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".enter").click();
                            }
                        });
                    }
                }
            },
            error: function () {
                $(".content").html("처리되지 않았습니다.");
                $('#myModal').modal({backdrop: 'static'});
                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".enter").click();
                    }
                });
            }
        });
    }

    //전체 발송
    function all_send() {
        window.onscroll = function(){
            var arr = document.getElementsByTagName('textarea');
            for( var i = 0 ; i < arr.length ; i ++  ){
                arr[i].off('blur');
            }
        };
        if($("#tel_temp").val() == undefined && $("#bulk_send").val() == undefined) {
            if ($("#templi_code").val() == null) {
                $(".content").html("템플릿을 먼저 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else if (document.getElementById("templi_cont").value.replace(/ /gi, "") == "") {
                $(".content").html("템플릿 내용을 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $('#myModal').on('hidden.bs.modal', function () {
                    $("#templi_cont").focus();
                });
            } else if (ms == "L" && document.getElementById("lms").value.replace(/ /gi, "") == "") {
                $(".content").html("LMS 내용을 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $('#myModal').on('hidden.bs.modal', function () {
                    $("#lms").focus();
                });
            } else if (ms == "S" && document.getElementById("sms").value.replace(/ /gi, "") == "") {
                $(".content").html("SMS 내용을 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $('#myModal').on('hidden.bs.modal', function () {
                    $("#sms").focus();
                });
            } else {
                var table = document.getElementById("tel");
                var row_index = table.rows.length - 1;
                for (var i = 0; i < row_index; i++) {
                    if (document.getElementsByClassName('tel_number')[i].value.replace(/ /gi,"") == "") {
                        $(".content").html("수신 번호를 입력해주세요.");
                        $('#myModal').modal({backdrop: 'static'});
                        $('#myModal').on('hidden.bs.modal', function () {
                            document.getElementsByClassName('tel_number')[i].focus();
                        });
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".enter").click();
                            }
                        });
                        return;
                    }
                }
                var check = true;
                $("#alimtalk_table tr").each(function () {
                    var focus;
                    if($(this).find("#btn_url").val() != undefined) {
                        if ($(this).find("#btn_url").val().trim() == "") {
                            check = false;
                            focus = $(this).find("#btn_url");
                            $(".content").html("버튼링크를 입력해주세요.");
                            $("#myModal").modal('show');
                            $('#myModal').on('hidden.bs.modal', function () {
                                focus.focus();
                            });

                        }
                    }
                });
                if($("#reserve_check").is(":checked") == true) {
                    if ($("#reserve").val().trim() == "") {
                        check = false;
                        $(".content").html("예약 발송 일시를 선택해주세요.");
                        $('#myModal').modal({backdrop: 'static'});
                    } else {
                        var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
                        var reserveDt = reserve + "00";
                        var now = moment().add(10,'minutes');
                        var min = now.format("YYYYMMDDHHmm");
                        var minDt = min+"00";
                        if (reserveDt < minDt) {
                            check = false;
                            $(".content").html("예약발송은 최소 10분 이후로 입력해주세요.");
                            $('#myModal').modal({backdrop: 'static'});
                        } else {
                            check = true;
                        }
                    }
                }
                if(check == false) return false;
                $.ajax({
                    url: "/dhnbiz/sender/coin",
                    type: "POST",
                    success: function (json) {
                        coin = json['coin'];
                        var kakao = Math.floor(Number(coin) / Number(at_price));
                        var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                        var sms = Math.floor(Number(coin) / Number(sms_price));
                        var s = sms.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                        var lms = Math.floor(Number(coin) / Number(lms_price));
                        var l = lms.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                        var table = document.getElementById("tel");
                        var row_index = table.rows.length - 1;
                        if (ms == "S") {
                            $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/>SMS 발송 가능 건수 : " + s + "건<br/><br/>전체 발송하시겠습니까? (발송할 건수 : " + row_index + "건)");
                            $("#myModalAll").modal({backdrop: 'static'});
                            $(document).unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".all").click();
                                }
                            });
                            $(".all").click(function () {
                                $("#myModalAll").modal("hide");
                            });
                        } else if (ms == "L") {
                            $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/>LMS 발송 가능 건수 : " + l + "건<br/><br/>전체 발송하시겠습니까? (발송할 건수 : " + row_index + "건)");
                            $("#myModalAll").modal({backdrop: 'static'});
                            $(document).unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".all").click();
                                }
                            });
                            $(".all").click(function () {
                                $("#myModalAll").modal("hide");
                            });
                        } else {
                            $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/><br/>전체 발송하시겠습니까? (발송할 건수 : " + row_index + "건)");
                            $("#myModalAll").modal({backdrop: 'static'});
                            $(document).unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".all").click();
                                }
                            });
                            $(".all").click(function () {
                                $("#myModalAll").modal("hide");
                            });
                        }
                    }
                });
            }
        } else if($("#bulk_send").val()!=undefined){
            var check = true;
            if($("#reserve_check").is(":checked") == true) {
                if ($("#reserve").val().trim() == "") {
                    check = false;
                    $(".content").html("예약 발송 일시를 선택해주세요.");
                    $('#myModal').modal({backdrop: 'static'});
                } else {
                    var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
                    var reserveDt = reserve + "00";
                    var now = moment().add(10,'minutes');
                    var min = now.format("YYYYMMDDHHmm");
                    var minDt = min+"00";
                    if (reserveDt < minDt) {
                        check = false;
                        $(".content").html("예약발송은 최소 10분 이후로 입력해주세요.");
                        $('#myModal').modal({backdrop: 'static'});
                    } else {
                        check = true;
                    }
                }
            }
            if(check == false) return false;
            $.ajax({
                url: "/dhnbiz/sender/coin",
                type: "POST",
                success: function (json) {
                    coin = json['coin'];
                    var kakao = Math.floor(Number(coin) / Number(at_price));
                    var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                    var sms = Math.floor(Number(coin) / Number(sms_price));
                    var s = sms.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                    var lms = Math.floor(Number(coin) / Number(lms_price));
                    var l = lms.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                    var table = document.getElementById("tel");
                    var bulk_send = $("#bulk_send").val();
                    if (ms == "S") {
                        $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/>SMS 발송 가능 건수 : " + s + "건<br/><br/>업로드 파일을 전체 발송하시겠습니까?<br/>(발송할 건수 : " + bulk_send + "건)");
                        $("#myModalAll").modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".all").click();
                            }
                        });
                        $(".all").click(function () {
                            $("#myModalAll").modal("hide");
                        });
                    } else if (ms == "L") {
                        $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/>LMS 발송 가능 건수 : " + l + "건<br/><br/>업로드 파일을 전체 발송하시겠습니까?<br/>(발송할 건수 : " + bulk_send + "건)");
                        $("#myModalAll").modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".all").click();
                            }
                        });
                        $(".all").click(function () {
                            $("#myModalAll").modal("hide");
                        });
                    } else {
                        $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/><br/>업로드 파일을 전체 발송하시겠습니까?<br/>(발송할 건수 : " + bulk_send + "건)");
                        $("#myModalAll").modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".all").click();
                            }
                        });
                        $(".all").click(function () {
                            $("#myModalAll").modal("hide");
                        });
                    }
                }
           });
        } else {
            var table = document.getElementById("tel");
            var row_index = table.rows.length - 1;
            for (var i = 0; i < row_index; i++) {
                if (document.getElementsByClassName('tel_number')[i].value.replace(/ /gi, "") == "") {
                    $(".content").html("수신 번호를 입력해주세요.");
                    $('#myModal').modal({backdrop: 'static'});
                    $('#myModal').on('hidden.bs.modal', function () {
                        document.getElementsByClassName('tel_number')[i].focus();
                    });
                    $(document).unbind("keyup").keyup(function (e) {
                        var code = e.which;
                        if (code == 13) {
                            $(".enter").click();
                        }
                    });
                    return;
                }
            }
            var check = true;
            if($("#reserve_check").is(":checked") == true) {
                if ($("#reserve").val().trim() == "") {
                    check = false;
                    $(".content").html("예약 발송 일시를 선택해주세요.");
                    $('#myModal').modal({backdrop: 'static'});
                } else {
                    var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
                    var reserveDt = reserve + "00";
                    var now = moment().add(10,'minutes');
                    var min = now.format("YYYYMMDDHHmm");
                    var minDt = min+"00";
                    if (reserveDt < minDt) {
                        check = false;
                        $(".content").html("예약발송은 최소 10분 이후로 입력해주세요.");
                        $('#myModal').modal({backdrop: 'static'});
                    } else {
                        check = true;
                    }
                }
            }
            if(check == false) return false;
            $.ajax({
                url: "/dhnbiz/sender/coin",
                type: "POST",
                success: function (json) {
                    coin = json['coin'];
                    var kakao = Math.floor(Number(coin) / Number(at_price));
                    var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                    var sms = Math.floor(Number(coin) / Number(sms_price));
                    var s = sms.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                    var lms = Math.floor(Number(coin) / Number(lms_price));
                    var l = lms.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
                    var table = document.getElementById("tel");
                    var row_index = table.rows.length - 1;
                    if (ms == "S") {
                        $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/>SMS 발송 가능 건수 : " + s + "건<br/><br/>업로드 파일을 전체 발송하시겠습니까?<br/>(발송할 건수 : " + row_index + "건)");
                        $("#myModalAll").modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".all").click();
                            }
                        });
                        $(".all").click(function () {
                            $("#myModalAll").modal("hide");
                        });
                    } else if (ms == "L") {
                        $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/>LMS 발송 가능 건수 : " + l + "건<br/><br/>업로드 파일을 전체 발송하시겠습니까?<br/>(발송할 건수 : " + row_index + "건)");
                        $("#myModalAll").modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".all").click();
                            }
                        });
                        $(".all").click(function () {
                            $("#myModalAll").modal("hide");
                        });
                    } else {
                        $(".content").html("알림톡 발송 가능 건수 : " + k + "건<br/><br/>업로드 파일을 전체 발송하시겠습니까?<br/>(발송할 건수 : " + row_index + "건)");
                        $("#myModalAll").modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".all").click();
                            }
                        });
                        $(".all").click(function () {
                            $("#myModalAll").modal("hide");
                        });
                    }
            }});
        }
    }
    /**
     * 한글포함 문자열 길이를 구한다
     */
    function getTextLength(str) {
        var len = 0;
        for (var i = 0; i < str.length; i++) {
            if (escape(str.charAt(i)).length == 6) {
                len++;
            }
            len++;
        }
        return len;
    }
    function cutTextLength(msg, len) {
        var text = msg;
        var leng = text.length;
        while(getTextLength(text) > len){
            leng--;
            text = text.substring(0, leng);
        }
        return text;
    }
    function all_move() {
        var templi_cont = $("#templi_cont").val();
        var code = document.getElementById("templi_code").value;
        var profile = document.getElementById("pf_key").value;
        var kind = document.getElementById("kind").value;
        var msg = '';
        if(ms=="S") {
            msg = document.getElementById("sms").value;
        } else if (ms=="L"){
            msg = document.getElementById("lms").value;
        } else {
            kind = '';
            senderBox = '';
        }
        var reserveDt = "00000000000000";
        if($("#reserve_check").is(":checked") == true) {
            if($("#reserve").val().trim() != ""){
                var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
                reserveDt = reserve+"00";
            }
        }

        if($("#bulk_send").val()!=undefined){
            reserveDt = "00000000000000"; //대량발송 예약 막음
            var btn_arr = new Array();
            var btn_obj = new Object();
            $("#alimtalk_table tr").each(function () {
                var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
                if(btn_type != undefined) {
                    btn_obj = new Object();
                    btn_obj.type = btn_type;
                    btn_obj.name = $(this).find(document.getElementsByName("btn_name")).val();
                    if (btn_type == "WL") {
                        btn_obj.url_mobile = $(this).find(document.getElementsByName("btn_url1")).val();
                        if ($(this).next('tr').find("#hidden_url").val() != undefined) {
                            btn_obj.url_pc = $(this).next('tr').find(document.getElementsByName("btn_url2")).val();
                        }
                    } else if (btn_type == "AL") {
                        btn_obj.scheme_android = $(this).find(document.getElementsByName("btn_url1")).val();
                        btn_obj.scheme_ios = $(this).next('tr').find(document.getElementsByName("btn_url2")).val();
                    }
                    btn_arr.push(btn_obj);
                }
            });
            var buttons = JSON.stringify(btn_arr);
            var file_data = new FormData();
            file_data.append("templi_code", code);
            file_data.append("pf_key", profile);
            file_data.append("file", $("input[id=filecount]")[0].files[0]);
            file_data.append("reserveDt", reserveDt);
            file_data.append("buttons", buttons);
            $.ajaxSettings.traditional = true;
            $.ajax({
                url: "/dhnbiz/sender/talk/send",
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
                    var code = json['code'];
                    var message = json['message'];
                    if(code == "success") {
                        $(".content").html("발송 요청되었습니다.");
                        $('#myModal').modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".enter").click();
                            }
                        });
                    } else {
                        $(".content").html("발송 실패되었습니다.<br/>"+message);
                        $('#myModal').modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".enter").click();
                            }
                        });

                    }
                },
                error: function () {
                    $(".content").html("처리되지 않았습니다.");
                    $('#myModal').modal({backdrop: 'static'});
                    $(document).unbind("keyup").keyup(function (e) {
                        var code = e.which;
                        if (code == 13) {
                            $(".enter").click();
                        }
                    });
                }
            });
        }else{
            var tel_number = new Array();
            var tel_templi = new Array();
            var tel_sms = new Array();
            var tel_tit = new Array();
            var btn = [];
            for (var b=1;b<=5;b++) {
                btn[b] = new Array();
            }
            var obj = [];
            $("input:checkbox[name=sel_del]").each(function (index, item) {
                var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
                tel_number.push(array);
                if($("#tel_temp").val() != undefined) {
                    var temp = $(this).parent().parent().parent().parent().find("#tel_temp").val().trim();
                    tel_templi.push(temp);
                } else {
                    tel_templi.push(templi_cont);
                }
                if($("#tel_sms").val() != undefined) {
                    var sms = $(this).parent().parent().parent().parent().find("#tel_sms").val().trim();
                    tel_sms.push(sms);
                } else {
                    tel_sms.push(msg);
                }
                if(ms=="L"){
                    if($("#tel_sms").val() != undefined) {
                        var tot = $(this).parent().parent().parent().parent().find("#tel_sms").val().trim();
                        //var tit_slice = tot.slice(0,100);
                        var tit_slice = cutTextLength(tot, 20);
                        tel_tit.push(tit_slice);
                    } else {
                        tit = document.getElementById("tit").value;
                        tel_tit.push(tit);
                    }
                } else if(ms=="S"){
                    var tit = '';
                    tel_tit.push(tit);
                } else {
                    var tit = '';
                    tel_tit.push(tit);
                }
                var btn_num = 1;
                var url_count = 1;
                $("#alimtalk_table tr").each(function () {
                    var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
                    if(btn_type != undefined) {
                        obj[btn_num] = new Object();
                        obj[btn_num].type = btn_type;
                        obj[btn_num].name = $(this).find(document.getElementsByName("btn_name")).val();
                        if (btn_type == "WL") {
                            if($(item).parent().parent().parent().parent().find("#tel_url"+url_count).val() != undefined && $(this).next('tr').find("#hidden_url").val() != undefined) {
                                obj[btn_num].url_mobile = $(item).parent().parent().parent().parent().find("#tel_url"+url_count).val();
                                url_count++;
                                obj[btn_num].url_pc = $(item).parent().parent().parent().parent().find("#tel_url"+url_count).val();
                                url_count++;
                            } else if($(item).parent().parent().parent().parent().find("#tel_url"+url_count).val() != undefined && $(this).next('tr').find("#hidden_url").val() == undefined) {
                                obj[btn_num].url_mobile = $(item).parent().parent().parent().parent().find("#tel_url"+url_count).val();
                                url_count++;
                            } else {
                                obj[btn_num].url_mobile = $(this).find(document.getElementsByName("btn_url1")).val();
                                if ($(this).next('tr').find("#hidden_url").val() != undefined) {
                                    obj[btn_num].url_pc = $(this).next('tr').find(document.getElementsByName("btn_url2")).val();
                                }
                            }
                        } else if (btn_type == "AL") {
                            var ios_count = url_count+1;
                            if($(item).parent().parent().parent().parent().find("#tel_url"+url_count).val() != undefined && $("#tel_url"+ios_count).val() != undefined) {
                                obj[btn_num].scheme_android = $(item).parent().parent().parent().parent().find("#tel_url"+url_count).val();
                                url_count++;
                                obj[btn_num].scheme_ios = $(item).parent().parent().parent().parent().find("#tel_url"+url_count).val();
                                url_count++;
                            } else {
                                obj[btn_num].scheme_android = $(this).find(document.getElementsByName("btn_url1")).val();
                                obj[btn_num].scheme_ios = $(this).next('tr').find(document.getElementsByName("btn_url2")).val();
                            }
                        }
                        btn[btn_num].push(obj[btn_num]);
                        btn_num++;
                    }
                });
            });
            var btn1 = JSON.stringify(btn[1]);
            var btn2 = JSON.stringify(btn[2]);
            var btn3 = JSON.stringify(btn[3]);
            var btn4 = JSON.stringify(btn[4]);
            var btn5 = JSON.stringify(btn[5]);
            $.ajaxSettings.traditional = true;
            $.ajax({
                url: "/dhnbiz/sender/talk/send",
                type: "POST",
                data: {"tel_number":tel_number,"templi_cont":tel_templi,"msg":tel_sms,"kind":kind,
                    "tit":tel_tit,"templi_code":code,"pf_key":profile,"senderBox":senderBox,
                    "btn1":btn1,"btn2":btn2,"btn3":btn3,"btn4":btn4,"btn5":btn5,"reserveDt":reserveDt},
                beforeSend: function () {
                               $('#overlay').fadeIn();
                           },
                complete: function () {
                                $('#overlay').fadeOut();
                           },
                success: function (json) {
                    var success = json['success'];
                    var fail = json['fail'];
                    var num = json['num'];
                    charge = json['coin'];
                    var success_type = json['success_type'];
                    var fail_type = json['fail_type'];
                    var success_alim='';
                    var success_sms='';
                    var success_lms='';
                    var message = json['message'];
                    var reserve = false;
                    for (var i = 0; i < message.length; i++) {
                        if(message[i] == 'R000') {
                            reserve = true;
                        }
                    }
                    if (reserve == true) {
                        $(".content").html("예약발송 처리 되었습니다. <br>예약된 내역은 [발신 목록]에서 확인이 가능합니다.");
                        $('#myModal').modal({backdrop: 'static'});
                        $(document).unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".enter").click();
                            }
                        });
                    } else {
                        if (success != '0') {
                            for (var i = 0; i < success_type.length; i++) {
                                if (success_type[i] == 'at') {
                                    success_alim++;
                                } else if (success_type[i] == 'S') {
                                    success_sms++;
                                } else if (success_type[i] == 'L') {
                                    success_lms++;
                                }
                            }
                        }

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

                        if (success_alim != '') {
                            ga('send', 'event', '발송', '알림톡', '성공', success_alim);
                        }
                        if (success_sms != '') {
                            ga('send', 'event', '발송', 'SMS', '성공', success_sms);
                        }
                        if (success_lms != '') {
                            ga('send', 'event', '발송', 'LMS', '성공', success_lms);
                        }

                        if (fail != '0') {
                            var fail_alim = '';
                            var fail_sms = '';
                            var fail_lms = '';
                            var fail_etc = '';
                            for (var i = 0; i < fail_type.length; i++) {
                                if (fail_type[i] == 'at') {
                                    fail_alim++;
                                } else if (fail_type[i] == 'S') {
                                    fail_sms++;
                                } else if (fail_type[i] == 'L') {
                                    fail_lms++;
                                } else if (fail_type[i] == '') {
                                    fail_etc++;
                                }
                            }

                            if (fail_alim != '') {
                                ga('send', 'event', '발송', '알림톡', '실패', fail_alim);
                            }
                            if (fail_sms != '') {
                                ga('send', 'event', '발송', 'SMS', '실패', fail_sms);
                            }
                            if (fail_lms != '') {
                                ga('send', 'event', '발송', 'LMS', '실패', fail_lms);
                            }
                            if (fail_etc != '') {
                                ga('send', 'event', '발송', '기타(기타 오류)', '실패', fail_etc);
                            }

                            $(".content").html("발송 결과 : (성공 : " + success + "건, 실패 : " + fail + "건)<br/>실패 번호 : " + num);
                            $('#myModal').modal({backdrop: 'static'});
                            $(".modal-dialog").css("word-wrap", "break-word");
                            $(document).unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".enter").click();
                                }
                            });
                        } else {
                            $(".content").html("발송 결과 : (성공 : " + success + "건, 실패 : " + fail + "건)");
                            $('#myModal').modal({backdrop: 'static'});
                            $(document).unbind("keyup").keyup(function (e) {
                                var code = e.which;
                                if (code == 13) {
                                    $(".enter").click();
                                }
                            });
                        }
                    }
                },
                error: function () {
                    $(".content").html("처리되지 않았습니다.");
                    $('#myModal').modal({backdrop: 'static'});
                    $(document).unbind("keyup").keyup(function (e) {
                        var code = e.which;
                        if (code == 13) {
                            $(".enter").click();
                        }
                    });
                }
            });
        }
    }

    //글자수 제한 - 템플릿 내용
    function templi_chk() {
        var limit_length = 1000;
        var msg_length = $("#templi_cont").val().length;
        if (msg_length <= limit_length) {
            $("#type_num").html(msg_length);
        } else if (msg_length > limit_length) {
            $(".content").html("템플릿 내용을 1000자 이내로 입력해주세요.");
            $("#myModal").modal({backdrop: 'static'});
            $('#myModal').on('hidden.bs.modal', function () {
                $("#templi_cont").focus();
            });
            var cont = $("#templi_cont").val();
            var cont_slice = cont.slice(0,1000);
            $("#templi_cont").val(cont_slice);
            $("#type_num").html(1000); 
             return;
         } else {
            $("#type_num").html(msg_length);  
        }
    }
    //글자수 제한 - LMS 내용
    $("#lms").keyup(function(){
        var limit_length = 1000;
        var msg_length = $(this).val().length;
        if( msg_length <= limit_length ) {    
            $("#lms_num").html(msg_length); 
        } else if( msg_length > limit_length ){
            $(".content").html("LMS 내용을 1000자 이내로 입력해주세요.");
            $("#myModal").modal({backdrop: 'static'});
            $('#myModal').on('hidden.bs.modal', function () {
                $("#lms").focus();
            });
            var cont = $("#lms").val();
            var cont_slice = cont.slice(0,1000);
            $("#lms").val(cont_slice);
            $("#lms_num").html(1000); 
             return;
         } else {
            $("#lms_num").html(msg_length);  
        }
    });
    //글자수 제한 - SMS 내용
    $("#sms").keyup(function(){
        var limit_length = 90;
        var msg_cont = $(this).val();
        var msg_length = $(this).val().length;
        var byte = 0;
        var len = 0;
        var char = "";
        for(var i=0;i<msg_length;i++){
            char = msg_cont.charAt(i);
            if(escape(char).length>4) {
                byte+=2;
            } else {
                byte++;
            } if(byte<=limit_length){
                len=i+1;
            } if(byte>limit_length) {
                $(".content").html("SMS 내용을 90Byte 이내로 입력해주세요.");
                $("#myModal").modal({backdrop: 'static'});
                $('#myModal').on('hidden.bs.modal', function () {
                    $("#sms").focus();
                });
                var cont_slice = msg_cont.slice(0,len);
                $("#sms").val(cont_slice);
                $("#sms_num").html(90);
                return;
            } else {
                $("#sms_num").html(byte);
            }
        }
        $("#sms_num").html(byte);
    });

    // 템플릿 선택 버튼
    function btnSelectTemplate() {
        var count = '';
        console.log('count', count);
        if('' == "none") {
            $('#template_danger').modal('show');
        } else {
            $("#template_select").modal({backdrop: 'static'});
            $('#template_select').unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 27) {
                    console.log('code == 27');
                    $("#template_select").modal("hide");
                }
                else if ($("#search").is(":focus")) {
                    if (code == 13) {
                        search_enter(); // todo check
                    }
                } else {
                    if (code == 13) {
                        select_profile();
                    }
                }
            });
        }

        $('#template_select .widget-content').html('').load(
            "/dhnbiz/sender/send/template",
            {
					 <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
                "page": 1
            },
            function() {
                $('#template_select').css({"overflow-y": "scroll"});
            }
        );
    }

    //템플릿 선택
    function template() {
        if(''!="") {
            $(".content").html("입력하신 모든 내용이 초기화됩니다.<br/>선택하시겠습니까?");
            $("#myModalTemp").modal({backdrop: 'static'});
            $("#myModalTemp").unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".temp").click();
                    } else if (code == 27) {
                        $(".btn-default").click();
                    }
                });
                $(".temp").click(function () {
                    var form = document.createElement("form");
                    document.body.appendChild(form);
                    form.setAttribute("method", "post");
                    form.setAttribute("action", "/dhnbiz/sender/send/template");
						  var csrfField = document.createElement("input");
						  csrfField.setAttribute("type", "hidden");
						  csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
						  csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
						  form.appendChild(csrfField);
                    form.submit();
                });
        }else{
            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/dhnbiz/sender/send/template");
				var csrfField = document.createElement("input");
				csrfField.setAttribute("type", "hidden");
				csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
				csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
				form.appendChild(csrfField);
            form.submit();
        }
    }

    //업로드 양식 다운로드
    function download() {
        if ($("#templi_code").val() == null) {
            $(".content").html("템플릿을 먼저 선택해주세요.");
            $('#myModal').modal({backdrop: 'static'});
        } else {
            
            var tmpli_cont = $("#hidden_cont").val();

            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/dhnbiz/sender/at_download");
            var tmpliField = document.createElement("input");
            tmpliField.setAttribute("type", "hidden");
            tmpliField.setAttribute("name", "tmpli_cont");
            tmpliField.setAttribute("value", tmpli_cont);
            form.appendChild(tmpliField);
            var btnField = document.createElement("input");
            btnField.setAttribute("type", "hidden");
            btnField.setAttribute("name", "buttons");
            btnField.setAttribute("value", buttons);
            form.appendChild(btnField);
            var smsField = document.createElement("input");
            smsField.setAttribute("type", "hidden");
            smsField.setAttribute("name", "policy_sms");
            smsField.setAttribute("value", policy_sms);
            form.appendChild(smsField);
            form.submit();
        }
    }

    //업로드
    function readURL(input) {
        var file = document.getElementById('filecount').value;
        file = file.slice(file.indexOf(".") + 1).toLowerCase();
        if (file == "xls" || file == "xlsx") {
            var formData = new FormData();
            formData.append("file", $("input[name=filecount]")[0].files[0]);
            $.ajax({
                url: "/dhnbiz/sender/upload",
                type: "POST",
                data: formData,
        	    processData: false,
        	    contentType: false,
                beforeSend: function () {
                    $('#overlay').fadeIn();
                },
                complete: function () {
                    $('#overlay').fadeOut();
                },
                success: function (json) {
                    var status = json['status'];
                    if (status == 'error') {
                        var msg = json['msg'];
                        $(".content").html("올바르지 않은 파일입니다.<br/>"+msg);
                        $('#myModal').modal({backdrop: 'static'});
                        $('#filecount').filestyle('clear');
                    } else if (json['nrow_len'] > 60000) {
                        $(".content").html("대량 발송은 최대 6만건까지 가능합니다.");
                        $('#myModal').modal({backdrop: 'static'});
                        $('#filecount').filestyle('clear');
                    } else if (json['nrow_len'] != undefined){
                        var bulk_send = json['nrow_len'];
                        var upload_file = json['upload_file'];
                        var coin = json['coin'];
                        var limit_count;
                        if (ms == "S") {
                            limit_count = Number(sms_price)*Number(bulk_send);
                        } else if (ms == "L") {
                            limit_count = Number(lms_price)*Number(bulk_send);
                        } else {
                            limit_count = Number(at_price)*Number(bulk_send);
                        }
                        if(coin >= limit_count) {
                            $("#number_add").hide();
                            $("#select_del").hide();
                            $("#select_send").hide();
                            $("#tel").hide();
                            var hidden = $("#hidden_cont").val();
                            var hidden_length = hidden.length;
                            $("#type_num").html(hidden_length);
                            var message = $("#text").val(hidden);
                            var height = $("#text").prop("scrollHeight");
                            if (height < 468) {
                                $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                            } else {
                                var message = $("#text").val();
                                var height = $("#text").prop("scrollHeight");
                                if (message == "") {
                                    $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                                } else {
                                    $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                                    $("#templi_cont").keyup(function() {
                                        $(".scroller").scrollTop($(".scroller")[0].scrollHeight);
                                    });
                                }
                            }

                            if(ms=="S") {
                                $("#sms").val("").attr("readonly",true).css("cursor","default").css("background-color","#EEEEEE");
                                $("#sms_num").html(0);
                            } else if (ms=="L") {
                                $("#lms").val("").attr("readonly", true).css("cursor", "default").css("background-color", "#EEEEEE");
                                $("#lms_num").html(0);
                            }
                            $("#alimtalk_table tr").each(function () {
                                var hidden_url = $(this).find("#hidden_url").val();
                                $(this).find("#btn_url").val(hidden_url);
                                $(this).find("#btn_url").attr("readonly",true);
                                $(this).find("#btn_url").css("cursor","default")
                            });
                            $("#templi_cont").val(hidden).attr("readonly", true).css("background-color", "#EEEEEE");
                            $(".reserve").attr("hidden", true); //대량발송 예약 막음
                            $(".reserve_content").attr("hidden", true); //대량발송 예약 막음
                            $("input:checkbox[id='reserve_check']").removeAttr('checked'); //대량발송 예약 막음
                            $("#reserve_check").uniform(); //대량발송 예약 막음
                            $(".tel_content").after('<div class="widget-content" id="upload_result"><p>업로드 결과 : ' + bulk_send + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="bulk_send" value="' + bulk_send + '"></div><br>');
                        } else {
                            $(".content").html("충전 잔액이 부족합니다.");
                            $('#myModal').modal({backdrop: 'static'});
                            $('#filecount').filestyle('clear');
                        }
                    } else {
                        var cols = json['col_value'];
                        var rows = new Array();

                        var btnMaxCols = 1;
                        var btn_content;
                        
                        for (var i=0;i<cols.length;i++){
                            var num = cols[i][0].replace(/[^ㄱ-ㅎ가-힣|^a-z|^A-Z|^0-9\n,.]/gim, "").replace(/\.+0/,"").replace(/-/gim, ""); // 전화번호
                            var tmpli_cont = String(cols[i][1]); // 템플릿 내용
                            var btn_cont = true;
                            if (btnMaxCols > 1) {
                                for (var btn = 2; btn <= btnMaxCols; btn++) {
                                    if (String(cols[i][btn]).replace(/\s/gi, '') == "") {
                                        btn_cont = false;
                                    }
                                }
                            }
                            if(ms=='L' || ms=='S') {
                                var sms_cont = String(cols[i][2]); //SMS 내용
                                if (btnMaxCols > 1) {
                                    sms_cont = String(cols[i][Number(btnMaxCols)+1]); //SMS 내용(버튼이 있을경우 밀려남)
                                    if (num.replace(/ /gi, '') != "" && (!(new RegExp(/[^0-9]/gi).test(num))) && tmpli_cont.replace(/\s/gi, '') != "" && sms_cont.replace(/\s/gi, '') != "" && btn_cont != false) {
                                        rows.push(cols[i]);
                                    }
                                } else {
                                    if (num.replace(/ /gi, '') != "" && (!(new RegExp(/[^0-9]/gi).test(num))) && tmpli_cont.replace(/\s/gi, '') != "" && sms_cont.replace(/\s/gi, '') != "") {
                                        rows.push(cols[i]);
                                    }
                                }
                            } else if (btnMaxCols > 1) {
                                if (num.replace(/ /gi, '') != "" && (!(new RegExp(/[^0-9]/gi).test(num))) && tmpli_cont.replace(/\s/gi, '') != "" && btn_cont != false) {
                                    rows.push(cols[i]);
                                }
                            } else {
                                if (num.replace(/ /gi, '') != "" && (!(new RegExp(/[^0-9]/gi).test(num))) && tmpli_cont.replace(/\s/gi, '') != "") {
                                    rows.push(cols[i]);
                                }
                            }
                        }
                        if (rows.length == 0) {
                            $(".content").html("파일의 내용이 모두 누락되었습니다.");
                            $('#myModal').modal({backdrop: 'static'});
                            $('#filecount').filestyle('clear');
                        } else {
                            $("#text").val(rows[0][1]);
                            var height = $("#text").prop("scrollHeight");
                            if (height < 468) {
                                $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                            } else {
                                var message = $("#text").val();
                                var height = $("#text").prop("scrollHeight");
                                if (message == "") {
                                    $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                                } else {
                                    $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                                }
                            }
                            var hidden = $("#hidden_cont").val();
                            var hidden_length = hidden.length;
                            $("#type_num").html(hidden_length);
                            $("#templi_cont").val(hidden);
                            $("#templi_cont").attr("readonly",true);
                            $("#templi_cont").css("background-color","#EEEEEE");
                            if (btnMaxCols > 1) {
                                $("#alimtalk_table tr").each(function () {
                                    var hidden_url = $(this).find("#hidden_url").val();
                                    $(this).find("#btn_url").val(hidden_url);
                                    $(this).find("#btn_url").attr("readonly",true);
                                    $(this).find("#btn_url").css("cursor","default")
                                });
                            }
                            if(ms=="S") {
                                $("#sms").val("");
                                $("#sms").attr("readonly",true);
                                $("#sms").css("cursor","default");
                                $("#sms").css("background-color","#EEEEEE");
                                $("#sms_num").html(0);
                            } else if (ms=="L") {
                                $("#lms").val("");
                                $("#lms").attr("readonly",true);
                                $("#lms").css("cursor","default");
                                $("#lms").css("background-color","#EEEEEE");
                                $("#lms_num").html(0);
                            }
                            $("#number_add").unbind("click");
                            $("#number_add").click(function () {
                                if(document.getElementById('filecount').value!="") {
                                    $(".content").html("파일 업로드 되어 번호를 추가할 수 없습니다.");
                                    $("#myModal").modal({backdrop: 'static'});
                                }
                            });
                            var fail = cols.length-rows.length;
                            if(fail != 0 && cols.length != fail) {
                                $(".content").html("파일의 내용 중 누락된 데이터가 " + fail + "건 있습니다.");
                                $('#myModal').modal({backdrop: 'static'});
                            }
                            if (rows.length < 6) {
                                for (var i = 0; i < rows.length; i++) {
                                    var tr = '';
                                    var num = String(rows[i][0]).trim().replace(/-/gim, "");
                                    var temp = String(rows[i][1]).replace(/"/gim,'&quot;');
                                    var no = i + 1;
                                    tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                                            '<input name="sel_del" id="' + no + '" class="uniform" value="' + no + '" type="checkbox"></td><td width="70%"><input type="text" ' +
                                            'class="form-control input-width-medium inline tel_number" value="' + num + '" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);">' +
                                            '<input type="hidden" id="tel_temp" name="tel_temp" value="'+temp+'"/>';
                                    if(btnMaxCols > 1) {
                                        var url_count = 1;
                                        for (var b = 2 ; b<=btnMaxCols ; b++) {
                                            var btn_url = String(rows[i][b]).trim();
                                            tr += '<input type="hidden" id="tel_url' + url_count + '" name="tel_url" value="'+btn_url+'"/>';
                                            url_count++;
                                        }
                                        if (ms=="S" || ms=="L") {
                                            var sms = String(rows[i][Number(btnMaxCols) + 1]);
                                            tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="' + sms + '"/>';
                                        }
                                    }
                                    if (ms=="S" || ms=="L") {
                                        var sms = rows[i][2];
                                        tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="'+sms+'"/>';
                                    }
                                    tr += '</td><td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
                                            '<i class="icon-trash"></i> 삭제</a></td></tr>';
                                    no = no - 1;
                                    if (i == 0) {
                                        $("#tel_tbody").html(tr);
                                        $("#tel_tbody").css("height", "47px");
                                    } else {
                                        $("." + no).after(tr);
                                        var height = $("#tel_tbody").prop("scrollHeight");
                                        $("#tel_tbody").css("height", (height) + "px");
                                    }
                                    no = no + 1;
                                    $("#" + no).uniform();
                                }
                            } else {
                                for (var i = 0; i < rows.length; i++) {
                                    var tr = '';
                                    var num = String(rows[i][0]).trim().replace(/-/gim, "");
                                    var temp = String(rows[i][1]).replace(/"/gim,'&quot;');
                                    var no = i + 1;
                                    tr += '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                                            '<input name="sel_del" id="' + no + '" class="uniform" value="' + no + '" type="checkbox"></td><td width="70%"><input type="text" ' +
                                            'class="form-control input-width-medium inline tel_number" value="' + num + '" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);">' +
                                            '<input type="hidden" id="tel_temp" name="tel_temp" value="'+temp+'"/>';
                                    if(btnMaxCols > 1) {
                                        var url_count = 1;
                                        for (var b = 2 ; b<=btnMaxCols ; b++) {
                                            var btn_url = String(rows[i][b]).trim();
                                            tr += '<input type="hidden" id="tel_url' + url_count + '" name="tel_url" value="'+btn_url+'"/>';
                                            url_count++;
                                        }
                                        if (ms=="S" || ms=="L") {
                                            var sms = String(rows[i][Number(btnMaxCols) + 1]);
                                            tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="' + sms + '"/>';
                                        }
                                    }
                                    if (ms=="S" || ms=="L") {
                                        var sms = rows[i][2];
                                        tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="'+sms+'"/>';
                                    }
                                    tr += '</td><td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
                                            '<i class="icon-trash"></i> 삭제</a></td></tr>';
                                    no = no - 1;
                                    if (i == 0) {
                                        $("#tel_tbody").html(tr);
                                        $("#tel_tbody").css("height", "47px");
                                    } else {
                                        $("." + no).after(tr);
                                        $("#tel_tbody").css("height", "235px");
                                    }
                                    no = no + 1;
                                    $("#" + no).uniform();
                                }
                            }
                        }
                    }
                }
            });
        } else {
            $(".content").html("xls,xlsx 파일만 가능합니다.");
            $('#myModal').modal({backdrop: 'static'});
            $('#myModal').on('hidden.bs.modal', function () {
                $('#filecount').filestyle('clear');
                var table = document.getElementById("tel");
                var row_index = table.rows.length;

                for (var i = 1; i < row_index; i++) {
                    var tr = $("." + i);
                    tr.remove();
                    var height = $("#tel_tbody").prop("scrollHeight");
                    $("#tel_tbody").css("height", "0px");
                }

                var add = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                    '<input name="sel_del" id="' + 1 + '" class="uniform" value="' + 1 + '" type="checkbox"></td><td width="70%"><input type="text" ' +
                    'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
                    '<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
                    '<i class="icon-trash"></i> 삭제</a></td></tr>';
                $("#tel_tbody").html(add);
                var height = $("#tel_tbody").prop("scrollHeight");
                $("#tel_tbody").css("height", "47px");
                $("#1").uniform();
            });
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".enter").click();
                }
            });
        }
    }

    //업로드 선택시
    function upload() {
        if ($("#templi_code").val() == null) {
            $('#filecount').attr('disabled','disabled');

            $(".content").html("템플릿을 먼저 선택해주세요.");
            $('#myModal').modal({backdrop: 'static'});

            $(".enter").click(function () {
                $("#filecount").removeAttr("disabled");
            })
        } else {
            $('#filecount').attr('disabled','disabled');
            $(".content").html("입력하신 수신 번호가 초기화됩니다.<br/>업로드 하시면 업로드 파일의 내용으로 발송됩니다.<br/>업로드 하시겠습니까?");
            $('#myModalUpload').modal({backdrop: 'static'});
            $('#myModalUpload').unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 27) {
                    $(".btn-default").click();
                } else if (code == 13) {
                    check();
                }
            });
            $(".up").click(function () {
                check();
            });
        }
    }
    function check(){
        if($('#filecount').is('[disabled=disabled]')==true) {
            if($("#bulk_send").val()!=undefined){
                $("#upload_result").remove();
                $("#bulk_send").remove();
                $("#tel").show();
                $("#number_add").show();
                $("#select_del").show();
                $("#select_send").show();
            }
            $(".reserve").attr("hidden", false); //대량발송 예약 막음
            $("#filecount").removeAttr("disabled");

            $("#myModalUpload").modal('hide');
            var contents = document.getElementById('templi_cont').value;
            $("#text").css("height","1px").css("height",($("#text").prop("0px")));
            $('#text').val(contents);
            var height = $("#text").prop("scrollHeight");
            if (height < 460) {
                $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
            } else {
                $("#text").css("height", "1px").css("height", "460px");
            }
            $("#templi_cont").attr("readonly",false);
            $("#templi_cont").css("background-color","white");
            if(ms=="S") {
                $("#sms").attr("readonly",false);
                $("#sms").css("cursor","default");
                $("#sms").css("background-color","white");
            } else if (ms=="L") {
                $("#lms").attr("readonly",false);
                $("#lms").css("cursor","default");
                $("#lms").css("background-color","white");
            }
            $("#alimtalk_table tr").each(function () {
                $(this).find("#btn_url").attr("readonly",false);
            });

            if($('#filecount').val()!=""){
                $("#number_add").click(function () {
                    number_add();
                });
            }
            $('#filecount').filestyle('clear');
            var table = document.getElementById("tel");
            var row_index = table.rows.length;
            for (var i = 1; i < row_index; i++) {
                var tr = $("." + i);
                tr.remove();
                var height = $("#tel_tbody").prop("scrollHeight");
                $("#tel_tbody").css("height", "0px");
                var tr = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
                        '<input name="sel_del" id="' + 1 + '" class="uniform" value="' + 1 + '" type="checkbox"></td><td width="70%"><input type="text" ' +
                        'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
                        '<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
                        '<i class="icon-trash"></i> 삭제</a></td></tr>';
                $("#tel_tbody").html(tr);
                var height = $("#tel_tbody").prop("scrollHeight");
                $("#tel_tbody").css("height", "47px");
                $("#1").uniform();
            }
            $("#filecount").removeAttr("disabled");
            $("#filecount").attr('onclick', '');
            $("#filecount").click();
            $(".up").unbind("click");
            return $("#filecount").attr('onchange','readURL()');
        }
   }

	$(document).ready(function() {
	  $('#send_template_content').html('').load(
			"/dhnbiz/sender/send/select_template",
			{ <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				tmp_code: "<?=$param['tmp_code']?>",
				tmp_profile: "<?=$param['tmp_profile']?>"
		   },
			function() {
				$("#btn_add").attr("hidden", true);
				$("#modalBtn").attr("disabled",true).css("cursor","default").css("background-color","#2E6492");
				$("#templi_cont").val('').attr("disabled",true).css("cursor","default");
				$("#btn_name").val('').attr("disabled",true).css("cursor","default");
				$("#btn_url").val('').attr("disabled",true).css("cursor","default");
				$("#lms_select").attr("checked",true).attr("disabled",true).css("cursor","default");
				var msg = ''.replace(/&lt;br \/&gt;/gi, "\r").replace(/&amp;gt;/gim, ">").replace(/&amp;lt;/gim, "<").replace(/&amp;amp;/gim, "&");
				if(''.replace(/ /gi,"") != ""){
					 document.getElementById('templi_length').innerHTML = "0/400자";
					 $("#lms_ptag").html('<input type="checkbox" id="lms_select" class="uniform" onchange="select_lms(this)" checked>MMS 발신 여부');
					 $("#lms_select").attr("disabled", true).css("cursor","default");
					 var mms = "<tr id='mms' name='mms'><th>MMS</th><td colspan='5'>" +
								"<textarea name='mms_cont' id='mms_cont' cols='30' rows='5' class='form-control autosize' style='resize:none; cursor:default;' " +
								"placeholder='MMS 내용을 입력해주세요.' onkeyup='onPreviewText();resize_cont(this);chkword_mms();scroll_prevent();'>" + msg + "</textarea>" +
								"<p class='help-block align-left' style='width:80% !important'><input type='checkbox' id='mms_kakaolink_select' " +
								"class='uniform' onclick='insert_kakao_link(this);' style='cursor: default;'> 카카오 친구추가 링크 넣기</p>" +
								"<p class='help-block align-right'><span id='mms_num'>0</span>/2000자</p></td></tr>";
					 $("#smslms").remove();
					 $("#templi").after(mms);
					 var img_url = '';
					 $("#img_url").val(img_url);
					 $("#img_link").attr("readonly", false);
					 var img_link = '';
					 $("#img_link").val(img_link);
					 var image = "<img id='image' name='image' src='" + img_url + "' style='width:100%; margin-bottom:5px;'/>";
					 $("#text").before(image);
					 chkword_mms();
				} else {
					 $("#lms_kakaolink_select").attr("disabled",false);
					 $("#lms_kakaolink_select").uniform();
					 $("#lms").attr("disabled",false).val(msg).focus();
					 chkword_lms();
					 resize_cont(document.getElementById("lms"));
				}
				$("#text").val(msg);
				onPreviewText();
			}
	  );
	});

    //lms 발신여부 체크박스
    function select_lms(check){
        var sms_sender = $("#sms_sender").val();
        if (sms_sender == "None" || sms_sender.replace(/ /gi, "") == "") {
            $(".content").html("발신번호가 등록되지 않았습니다.<br>" +
                "발신프로필 목록에서 발신번호를 등록해주세요.");
            $('#myModal').modal({backdrop: 'static'});
            $("input:checkbox[id='lms_select']").removeAttr('checked');
            $(".uniform").uniform();
        } else {
            if (check.checked) {
                var pf_ynm = $("#pf_ynm").val();
                var phn = '';
                if (phn != "None" && phn.replace(/ /gi, "") != "") {
                    var cont = $("#templi_cont").val().replace("(광고)", "(광고) " + pf_ynm).replace("수신거부 : 홈>친구차단", "무료수신거부 : " + phn);
                } else {
                    var cont = $("#templi_cont").val().replace("(광고)", "(광고) " + pf_ynm).replace("수신거부 : 홈>친구차단", "무료수신거부 : ");
                }
                if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
                    if ($("#mms_cont").val().replace(/ /gi, "") == "") {
                        $("#mms_cont").val(cont);
                        $("#text").val(cont);
                    }
                    $("#mms_kakaolink_select").removeAttr("disabled");
                    $("#mms_kakaolink_select").uniform();
                    $("#mms_cont").removeAttr("disabled").css("background-color", "white").focus();
                    resize_cont(document.getElementById("mms_cont")); //mms textarea 가변 처리 되게 수정 TODO
                } else {
                    if ($("#lms").val().replace(/ /gi, "") == "") {
                        $("#lms").val(cont);
                        $("#text").val(cont);
                    }
                    $("#lms_kakaolink_select").removeAttr("disabled");
                    $("#lms_kakaolink_select").uniform();
                    $("#lms").removeAttr("disabled").css("background-color", "white").focus();
                    resize_cont(document.getElementById("lms"));
                }

                //미리보기 링크 삭제
                $("#friendtalk_table tr").each(function () {
                    if ($(this).find("#no").text()) {
                        var name = $(this).find("#no").parent().attr("name");
                        $("#btn_preview_div" + name).remove();
                    }
                });

                $("#text").css("margin-bottom", "0px");

                var height = $("#text").prop("scrollHeight");
                if (height < 408) {
                    $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                } else {
                    var message = $("#text").val();
                    var height = $("#text").prop("scrollHeight");
                    if (message == "") {
                        $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                    } else {
                        $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                        $(".scroller").scrollTop($(".scroller")[0].scrollHeight);
                    }
                }
                if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
                    chkword_mms();
                } else {
                    chkword_lms();
                }
            } else {
                //카카오 친구추가 링크 체크박스 선택 되어있을 경우 해제
                if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
                    if ($("input:checkbox[id='mms_kakaolink_select']").is(":checked") == true) {
                        $("input:checkbox[id='mms_kakaolink_select']").removeAttr('checked');
                    }
                    $("#mms_kakaolink_select").attr("checked", false).attr('disabled', true);
                    $("#mms_kakaolink_select").uniform();
                    $("#mms_cont").val("").css("background-color", "#EEEEEE").attr('disabled', 'disabled').css("height", "103px");
                } else {
                    if ($("input:checkbox[id='lms_kakaolink_select']").is(":checked") == true) {
                        $("input:checkbox[id='lms_kakaolink_select']").removeAttr('checked');
                    }
                    $("#lms_kakaolink_select").attr("checked", false).attr('disabled', true);
                    $("#lms_kakaolink_select").uniform();
                    $("#lms").val("").css("background-color", "#EEEEEE").attr('disabled', 'disabled').css("height", "103px");
                }
                $('.uniform').uniform();

                //미리보기 친구톡으로 변경되는 부분
                var templi_cont = $("#templi_cont").val();
                $("#text").val(templi_cont);
                //미리보기 링크 보이기
                $("#friendtalk_table tr").each(function () {
                    if ($(this).find("#no").text()) {
                        var current_btn_num = $(this).find("#no").parent().attr("name");
                        link_name(document.getElementById("btn_type" + current_btn_num), current_btn_num);
                    }
                });
                $("#text").css("margin-bottom", "10px");
                var height = $("#text").prop("scrollHeight");
                if (height < 408) {
                    $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                } else {
                    var message = $("#text").val();
                    var height = $("#text").prop("scrollHeight");
                    if (message == "") {
                        $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                    } else {
                        $("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
                        $(".scroller").scrollTop($(".scroller")[0].scrollHeight);
                    }
                }
            }
        }
    }
    //글자수 제한 - LMS 내용
    function chkword_lms() {
            var limit_length = 1000;
            var msg = $("#lms").val();
            var slice=msg.slice(0,20);
            $('#tit').val(slice);
            var msg_length = $("#lms").val().length;
            if (msg_length <= limit_length) {
                $("#lms_num").html(msg_length);
            } else if (msg_length > limit_length) {
                $(".content").html("LMS 내용을 1000자 이내로 입력해주세요.");
                $("#myModal").modal({backdrop: 'static'});
                $('#myModal').on('hidden.bs.modal', function () {
                    $("#lms").focus();
                });
                var cont = $("#lms").val();
                var cont_slice = cont.slice(0, 1000);
                $("#lms").val(cont_slice);
                $("#lms_num").html(1000);
                return;
            } else {
                $("#lms_num").html(msg_length);
            }
    }
    //친구톡, lms 입력란 가변 처리
    function resize_cont(obj) {
        $(this).on('keyup', function(evt){
          if(evt.keyCode == 8){
            if (obj.scrollHeight < 103){
                obj.style.height = "1px";
                obj.style.height = "103px";
            }
          }
        });
        if (obj.scrollHeight <= 409 && obj.scrollHeight > 113 ) {
            obj.style.height = "1px";
            obj.style.height = (20 + obj.scrollHeight) + "px";
        }else if (obj.scrollHeight > 412){
            obj.style.height = "1px";
            obj.style.height = "412px";
        }else if (obj.scrollHeight <= 430 && obj.scrollHeight > 133) {
            obj.style.height = "1px";
            obj.style.height = (obj.scrollHeight-20) + "px";
        } else if (obj.scrollHeight < 103){
            obj.style.height = "1px";
            obj.style.height = "103px";
        }
        obj.focus();
        $("#templi_cont").scrollTop($("#templi_cont")[0].scrollHeight);
 //mms로 변경됐을때,,조건문 걸기 TODO
    }
    // 미리보기 LMS 우선
    function onPreviewText() {
        var file = document.getElementById('filecount').value;
        if (file == ''){
            var cont='';
            if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
                cont=$('#mms_cont').val();
            } else {
                cont=$('#lms').val();
            }
            if (!cont || cont == "" ) {
                cont=$('#templi_cont').val();
            }
            $('#text').val(cont);

            var height = $(".message").prop("scrollHeight");
            if( height < 408 ) {
                $("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
            } else {
                var message = $("#text").val();
                if(message==""){
                    $("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
                } else {
                    $("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
                    $(".scroller").scrollTop($(".scroller")[0].scrollHeight);
                }
            }
            if (cont && cont == "") {
                $('#text').val(' 메세지를 입력해주세요.');
            }
        }
        scroll_prevent();
    }
    function insert_kakao_link(check){
        var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
        var long_url = "http://plus-talk.kakao.com/plus/home/" + pf_yid;
        $.ajax({
            url: "/dhnbiz/sender/friend/short_url",
            type: "POST",
            data: {"long_url":long_url},
            beforeSend: function () {
                $('#overlay').fadeIn();
            },
            complete: function () {
                $('#overlay').fadeOut();
            },
            success: function (json) {
                var short_url = json['short_url'];
                var content;
                var index;
                var cont;
                if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
                    cont = $("#mms_cont").val();
                } else {
                    cont = $("#lms").val();
                }
                if (check.checked) {
                    if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
                        if (new RegExp(/무료수신거부/gi).test($("#mms_cont").val())) {
                            content = $("#mms_cont").val().replace("무료수신거부", short_url + "\r무료수신거부");
                            $("#mms_cont").val(content).focus();
                        } else {
                            content = $("#mms_cont").val().replace(cont, cont + "\r" + short_url);
                            $("#mms_cont").val(content).focus();
                        }
                        resize_cont(document.getElementById("mms_cont"));
                        chkword_mms();
                    } else {
                        if (new RegExp(/무료수신거부/gi).test($("#lms").val())) {
                            content = $("#lms").val().replace("무료수신거부", short_url + "\r무료수신거부");
                            $("#lms").val(content).focus();
                        } else {
                            content = $("#lms").val().replace(cont, cont + "\r" + short_url);
                            $("#lms").val(content).focus();
                        }
                        //lms 입력란 가변 처리
                        
                        resize_cont(document.getElementById("lms"));
                        chkword_lms();
                    }
                    //미리보기 insert_kakao_link
                    onPreviewText();
                } else {
                    if ((new RegExp(/무료수신거부/gi).test(cont))) {
                        index = cont.indexOf("무료수신거부");
                        var first = cont.slice(0, index - 1).replace(short_url, "");
                        var last = cont.slice(index);
                        if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
                            $("#mms_cont").val(first + last).focus();
                            chkword_mms();
                        } else {
                            $("#lms").val(first + last).focus();
                            chkword_lms();
                        }
                    } else {
                        index = cont.indexOf(short_url);
                        if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
                            $("#mms_cont").val(cont.slice(0, index - 1)).focus();
                            chkword_mms();
                        } else {
                            $("#lms").val(cont.slice(0, index - 1)).focus();
                            chkword_lms();
                        }
                    }
                    onPreviewText(check);
                    if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
                        if ($("#mms_cont").prop("scrollHeight") < 412 && $("#mms_cont").prop("scrollHeight") > 103) {
                            $("#mms_cont").css("height", "1px").css("height", ($("#mms_cont").prop("scrollHeight") - 1) + "px");
                        } else {
                            resize_cont(document.getElementById("mms_cont"));
                        }
                        chkword_mms();
                    } else {
                        if ($("#lms").prop("scrollHeight") < 412 && $("#lms").prop("scrollHeight") > 103) {
                            $("#lms").css("height", "1px").css("height", ($("#lms").prop("scrollHeight") - 1) + "px");
                        } else {
                            resize_cont(document.getElementById("lms"));
                        }
                        chkword_lms();
                    }
                }
            }
        });
    }
</script>
