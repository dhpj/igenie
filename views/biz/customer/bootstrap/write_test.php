<!-- 타이틀 영역 -->
<div class="tit_wrap">
	고객관리
</div>
<!-- 타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>고객 등록</h3>
			<button class="btn_tr" onclick="location.href='/biz/customer/lists'">목록으로</button>
		</div>
		<div class="white_box" style="display:inline-block;">
			<div class="msg_box">
				<div class="send_list tasker" id="tasker">
					<table class="table_list" id="tel">
						<thead>
						<tr id="tel_tr">
							<th class="checkbox-column" style="width:5%; !important"><input type="checkbox" id="all_select" class="uniform"></th>
							<th width="20%">고객구분</th>
							<th width="17%">전화번호</th>
							<th width="17%">이름 (선택입력)</th>
							<th width="*">주소 (선택입력)</th>
							<th width="7%">삭제</th>
						</tr>
						</thead>
						<tbody id="tel_tbody" style="height:47px">
						</tbody>
					</table>
				</div>
				<div class="bottom">
					<button class="btn_st2" type="button" id="number_add" ><i class="icon-plus"></i> 번호 추가</button>
					<button class="btn_st2" type="button" id="number_del" onclick="selectDelRow();"><i class="icon-trash"></i> 선택 삭제</button>
				</div>
			</div>
			<button class="btn_st1" onclick="all_save();">고객정보 저장하기</button>
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
        height: 1020px;
    }
</style>
<script type="text/javascript">
    $("#nav li.nav20").addClass("current open");
    $(document).ready(function() {
    	clearPhoneList();
    });

    //window.onload = function () {
    //    clearPhoneList();
    //};
    function scroll_prevent() {
        window.onscroll = function(){
            var arr = document.getElementsByTagName('textarea');
            for( var i = 0 ; i < arr.length ; i ++  ){
                arr[i].blur();
            }
        }
    }

    $("#myModal").unbind("keyup").keyup(function (e) {
        var code = e.which;
        if (code == 13) {
            $(".enter").click();
        }
    });

    $("#number_add").click(function () {
        number_add();
    });


    function search_enter() {
        $(document).unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                $("#check").click();
            }
        });
    }

    //발신번호 추가
    function number_add() {
        var lastItemNo = $("#tel tbody tr:last").attr("class");
		if(lastItemNo == undefined) lastItemNo = 0;
        //alert("lastItemNo : "+ lastItemNo);
		var no = parseInt(lastItemNo) + 1;
        var table = document.getElementById("tel");
        var row_index = table.rows.length;
        if ($("input:checkbox[value='1']").is(":checked") == true) {
            if (row_index <= 5000) {
                var tr = phnHtmlRow(no, '', '', '');
                if (no==2) {
                    $("." + 1).after(tr);
                    //var height = $("#tel_tbody").prop("scrollHeight");
                    //$("#tel_tbody").css("height", (height) + "px");
                    $("#" + no).uniform();
                } else if (row_index >= 5) {
                    $("." + lastItemNo).after(tr);
                    $("#" + no).uniform();
                    //$("#tel_tbody").css("height", "235px");
                    $(this).css("overflow", "scroll");
                    $("tr."+no).focus();
                    //$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
                } else {
                    $("." + lastItemNo).after(tr);
                    //var height = $("#tel_tbody").prop("scrollHeight");
                    //$("#tel_tbody").css("height", (height) + "px");
                    $("#"+no).uniform();
                }
            } else {
                $(".content").html("최대 5,000개까지 가능합니다.");
                $("#myModal").modal({backdrop: 'static'});
            }
        } else {
            if (row_index <= 5000) {
                var tr = phnHtmlRow(no, '', '', '');
                if (row_index > 5) {
                    $("." + lastItemNo).after(tr);
                    $("#" + no).uniform();
                    //var height = $("#tel_tbody").prop("scrollHeight");
                    //$("#tel_tbody").css("height", "235px");
                    $(this).css("overflow", "scroll");
                    $("tr."+no).focus();
                    //$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
                } else {
                    $("." + lastItemNo).after(tr);
                    //var height = $("#tel_tbody").prop("scrollHeight");
                    //$("#tel_tbody").css("height", (height) + "px");
                    $("#"+no).uniform();
                }
            } else {
                $(".content").html("최대 5,000개까지 가능합니다.");
                $("#myModal").modal({backdrop: 'static'});
            }
        }
    }

    // 발신번호 삭제
    function tel_remove(obj) {
        var table = document.getElementById("tel");
        var row_index = table.rows.length;
        /*if(row_index==2) {
            var tel_number = $("#tel_number").val();
            if(tel_number==""){
                $(".content").html("삭제할 수신 번호가 없습니다.");
                $("#myModal").modal({backdrop: 'static'});
                $("#myModal").unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".enter").click();
                    }
                });
            } else {
                $(".content").html("삭제하시겠습니까?");
                $("#myModalCheck").modal({backdrop: 'static'});
                $("#myModalCheck").unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        tel_del(obj);
                    }
                });
                $(".submit").click(function() {
                    tel_del(obj);
                    $(".submit").unbind("click");
                });
            }
        } else {
            $(".content").html("삭제하시겠습니까?");
            $("#myModalCheck").modal({backdrop: 'static'});
            $("#myModalCheck").unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    tel_del(obj);
                }
            });
            $(".submit").click(function() {
                tel_del(obj);
                $(".submit").unbind("click");
            });
        }*/
		//alert("obj : "+ obj +", row_index : "+ row_index);
        var tr = $("." + obj);
		//if(!confirm("삭제 하시겠습니까?")){
		//	return;
		//}
		var tr = $("." + obj);
		tr.remove();
		if(row_index==2) { //1건인 경우
			var tr = phnHtmlRow(1, '', '', '');
            $("#tel_tbody").html(tr);
		}
    }
    function tel_del(obj) {
        $("#myModalCheck").modal("hide");
        var table = document.getElementById("tel");
        var row_index = table.rows.length;
        var tr = $("." + obj);
        if (row_index != 2) {
            if (row_index > 6) {
                tr.remove();
                //var height = $("#tel_tbody").prop("scrollHeight");
                //$("#tel_tbody").css("height", "235px");
                $(this).css("overflow", "scroll");
            } else {
                tr.remove();
                //var height = $("#tel_tbody").prop("scrollHeight");
                //$("#tel_tbody").css("height", (height - 47) + "px");
            }
        } else {
            tr.remove();
            var tr = phnHtmlRow(1, '', '', '');
            $("#tel_tbody").html(tr);
            //var height = $("#tel_tbody").prop("scrollHeight");
            //$("#tel_tbody").css("height", "47px");
            //$("#1").uniform();
        }
    }

    //발신번호 선택삭제
    function selectDelRow() {
        if ($("input:checkbox[name='sel_del']").is(":checked") == false) {
            $(".content").html("삭제할 수신 번호를 선택해주세요.");
            $('#myModal').modal({backdrop: 'static'});
        } else {
            var table = document.getElementById("tel");
            var row_index = table.rows.length;
			//alert("row_index : "+ row_index);
			//if(!confirm("삭제하 시겠습니까?")){
			//	return;
			//}
			//alert("row_index : "+ row_index);
			$("input[name=sel_del]:checked").each(function () {
                var obj = $(this).val();
				//alert("obj : "+ obj);
				/*if (row_index == 2) {
                    var tel_number = $("#tel_number").val();
					//alert("tel_number : "+ tel_number);
                    if (tel_number == "") {
                        $(".content").html("삭제할 수신 번호가 없습니다.");
                        $("#myModal").modal({backdrop: 'static'});
                        $("#myModal").unbind("keyup").keyup(function (e) {
                            var code = e.which;
                            if (code == 13) {
                                $(".enter").click();
                            }
                        });
                    } else {
                        $(".content").html("삭제하시겠습니까?");
                        $("#myModalCheck").modal({backdrop: 'static'});
                        $("#myModalCheck").unbind("keyup").keyup(function (e) {
                            var code = e.which;
							 alert("code : "+ code);
                            if (code == 13) {
                                $(".submit").click();
                            }
                        });
                        $(".submit").click(function() {
                           tel_select_del(obj);
                            $(".submit").unbind("click");
                        });
                    }
                } else {
                    $(".content").html("삭제하시겠습니까?");
                    $("#myModalCheck").modal({backdrop: 'static'});
                    $("#myModalCheck").unbind("keyup").keyup(function (e) {
                        var code = e.which;
                        if (code == 13) {
                            $(".submit").click();
                        }
                    });
                    $(".submit").click(function() {
                        tel_select_del(obj);
                        $(".submit").unbind("click");
                    });
                }*/
				tel_select_del(obj);
            });
        }
    }

    //팝업창 선택 삭제
    function tel_select_del(obj) {
        $("#myModalCheck").modal("hide");
		//alert("tel_select_del("+ obj +")");
        var tr = $("." + obj);
        var table = document.getElementById("tel");
        var row_index = table.rows.length;
        var cnt = $("input[name=sel_del]:checked").length;
		//alert("cnt : "+ cnt);
        var row = row_index-cnt;
        if(cnt!=0) {
            if (row_index >= 7) {
                tr.remove();
                ///var height = $("#tel_tbody").prop("scrollHeight");
                //$("#tel_tbody").css("height", "235px");
                $(this).css("overflow", "scroll");
            } else if (row < 6 && row >= 2) {
                tr.remove();
                //var height = $("#tel_tbody").prop("scrollHeight");
                //$("#tel_tbody").css("height", (row*47-47) + "px");
            } else if (1 >= row) {
                tr.remove();
                if(row==1) {
                    var tr = phnHtmlRow(1, '', '', '');
                    $("#tel_tbody").html(tr);
                    //var height = $("#tel_tbody").prop("scrollHeight");
                    //$("#tel_tbody").css("height", "47px");
                    //$("#1").uniform();
                }
            }
        }
    }

    //전체 고객 저장
    function all_save() {
		var table = document.getElementById("tel");
		var row_index = table.rows.length - 1;
		for (var i = 0; i < row_index; i++) {
			if (document.getElementsByName('tel_number')[i].value == "") {
				alert("[Line "+ (i+1) +"] 전화 번호를 입력해주세요.");
				document.getElementsByClassName('tel_number')[i].focus();
				return;
			}
		}
		//alert("등록 고객수에 따라 시간이 소요될 수 있습니다.");
		customer_save(); //고객정보 저장하기
    }

    //고객정보 저장하기
	function customer_save () {
		var table = document.getElementById("tel");
		var row_index = table.rows.length - 1;
		var tel_number = new Array();
		var tel_name = new Array();
		var tel_group = new Array();
		var tel_addr = new Array();
		for (var i = 0; i < row_index; i++) {
			var num = document.getElementsByName('tel_number')[i].value; //전화번호
			if (typeof num != 'undefined') {
				tel_number.push(num);
				var name = document.getElementsByName('tel_name')[i].value; //이름
				if (typeof name != 'undefined') {
					tel_name.push(name);
				} else {
					tel_name.push("");
				}
				var kind = document.getElementsByName('tel_group')[i].value; //고객구분
				if (typeof kind != 'undefined') {
					tel_group.push(kind);
				} else {
					tel_group.push("");
				}
				var addr = document.getElementsByName('tel_addr')[i].value; //주소
				if (typeof addr != 'undefined') {
					tel_addr.push(addr);
				} else {
					tel_addr.push("");
				}
			}
		}
		//alert("tel_number : "+ tel_number +", tel_name : "+ tel_name +", tel_addr : "+ tel_addr);
		$.ajax({
			url: "/biz/customer/write",
			type: "POST",
			data: {
					 <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					 tels: tel_number, names: tel_name, groups: tel_group, addrs : tel_addr},
			success: function (json) {
				if (json['success'] != 'success') {
					var msg = json['msg'];
					$(".content").html("전화번호를 저장하지 못했습니다.<br>" + msg);

				} else if (json['success'] == 'success') {
					// 전화번호 리스트 clear
					clearPhoneList();
					alert("전화번호가 저장되었습니다.");
					location.href = "/biz/customer/lists";
					return;
				} else {
					alert("전화번호를 저장하지 못했습니다.");
					return;
				}
			}
		});
    }

    function clearPhoneList() {
        // 전화번호 리스트 clear (can be first)
        $("#tel_tbody").html('');
		var tr = phnHtmlRow(1, '', '', '');
		$("#tel_tbody").html(tr);
		$("#tel_tbody").prop("scrollHeight");
        //$("#tel_tbody").css("height", "47px");
        //$("#1").uniform();
		$('.bootstrap-filestyle input').val('');
    }

    // input enter 키 방지
    function captureReturnKey(e) {
        if(e.keyCode==13 && e.srcElement.type != 'textarea')
            return false;
    }

    //엑셀양식 다운로드
    function download() {
		//document.location.href="/uploads/customer_list.xlsx";
		document.location.href="/uploads/sample/customer_list_201102.xlsx";
		return;
        /*var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/biz/customer/download");
        form.submit();*/
    }

    //고객 등록 Input 태그
    function phnHtmlRow(idx, phone, name, addr) {
        phone = phone || '';
        name = name || '';
		//고객 등록 Input 태그
		var html =
		html += '<tr class="'+ idx +'">';
		html += '  <td class="checkbox-column">';
		html += '    <label class="checkbox_container">';
		html += '      <input type="checkbox" name="sel_del" id="sel_del'+ idx +'" value="'+ idx +'" >';
		html += '      <span class="checkmark"></span>';
		html += '    </label>';
		html += '  </td>';
		html += '  <td>';
		html += '    <select id="tel_group" name="tel_group" style="width:100%;" tabindex="1">';
		html += '      <option value="0">그룹선택</option>';
		<? foreach($customer_group as $r){ ?>
		html += '      <option value="<?=$r->cg_id?>"><?=($r->cg_level==2) ? '&nbsp;&nbsp;&nbsp;&nbsp;└ ' : ''?><?=$r->cg_gname?></option>';
		<? } ?>
		html += '    </select>';
		html += '  </td>';
		html += '  <td >';
		html += '    <input type="text" class="form-control input-width-medium inline tel_number" style="width:100%;" value="'+ phone +'" id="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);">';
		html += '  </td>';
		html += '  <td>';
		html += '    <input type="text" class="form-control input-width-medium inline tel_name" style="width:100%;" value="'+ name +'" id="tel_name" name="tel_name" placeholder="이름 입력" tabindex="1">';
		html += '  </td>';
		html += '  <td style="text-align:left; !important;">';
		html += '    <input type="text" class="form-control input-width-medium inline tel_addr" style="width:100%; max-width:100%; !important;" value="'+ addr +'" id="tel_addr" name="tel_addr" placeholder="주소 입력" tabindex="1">';
		html += '  </td>';
		html += '  <td><a href="javascript:tel_remove('+ idx +');" class="btn  btn-sm" title="삭제">';
		html += '    <i class="icon-trash"></i> 삭제</a>';
		html += '  </td>';
		html += '</tr>';
		//alert("html : "+ html);
        return html;
    }

    //업로드
    function readURL() {
        var file = document.getElementById('filecount').value;
        file = file.slice(file.indexOf(".") + 1).toLowerCase();
        //alert("file : "+ file);
		if (file == "xls" || file == "xlsx" || file == "csv") {
            var formData = new FormData();
            formData.append("file", $("input[name=filecount]")[0].files[0]);
				formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
				formData.append("real", "0");
            $.ajax({
                url: "/biz/customer/upload",
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
					//alert("status : "+ status);
                    if (status == 'error') {
                        var msg = json['msg'];
                        $(".content").html("올바르지 않은 파일입니다.<br>"+msg);
                        $('#myModal').modal({backdrop: 'static'});
                        $('#filecount').filestyle('clear');
                    } else if (status == 'success') {
                        var upload_count = json['nrow_len'];
                        //$("#number_add").hide();
                        //$("#number_del").hide();
                        //$("#tel").hide();
                        //$("#upload_result").remove();
						//alert("업로드 결과 : " + upload_count + "명의 고객이 업로드 되었습니다.");
						$("#uploadCount").text(upload_count);

                        //$(".tel_content").after('<div class="widget-content" style="margin-top:-10px;" id="upload_result"><p>업로드 결과 : ' + upload_count + ' 명의 고객이 업로드 되었습니다.</p><input type="hidden" id="upload_all_send" value="' + upload_count + '"></div>');
                    }
                }
            });
        /*} else if (!(file.equals("xls") || file.equals("xlsx"))) {
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
                var add = phnHtmlRow(1, '', '', '');
                $("#tel_tbody").html(add);
                $("#tel_tbody").prop("scrollHeight");
                $("#tel_tbody").css("height", "47px");
                //$("#1").uniform();
            });
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".enter").click();
                }
            });*/
        }else{
			alert("엑셀(xls, xlsx) 파일만 가능합니다.");
			return;
		}
    }

    //업로드 선택시
    function upload() {
        $('#filecount').attr('disabled', 'disabled');
        $(".content").html("업로드 하시겠습니까?");
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

    function check() {
        var file = document.getElementById('filecount').value;
        if(file) {
            $("#number_add").show();
            $("#number_del").show();
            $("#tel").show();
            $("#upload_result").remove();
        }
        if ($('#filecount').is('[disabled=disabled]') == true) {
            $("#filecount").removeAttr("disabled");
            if ($('#filecount').val() != "") {
                $("#number_add").click(function () {
                    number_add();
                });
            }
            $('#filecount').filestyle('clear');
            var table = document.getElementById("tel");
            var row_index = table.rows.length;
            for (var i = 1; i < row_index; i++) {       // ????
                var tr = $("." + i);
                tr.remove();
                $("#tel_tbody").prop("scrollHeight");
                $("#tel_tbody").css("height", "0px");
                tr = phnHtmlRow(1, '', '', '');
                $("#tel_tbody").html(tr);
                $("#tel_tbody").prop("scrollHeight");
                $("#tel_tbody").css("height", "47px");
                $("#1").uniform();
            }
            $("#myModalUpload").modal('hide');
            $("#filecount").removeAttr("disabled");
            $("#filecount").attr('onclick', '');
            $("#filecount").click();
            $(".up").unbind("click");
            return $("#filecount").attr('onchange', 'readURL()');
        }
    }


</script>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content identify">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal" id="identify">
                            확인
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalCustomer" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content identify">
                </div>
                <div>
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal" onclick="customer_save();">
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
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
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
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1" onclick="all_move();">확인</button>
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
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
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
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
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
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
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
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
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
                    <p class="btn_al_cen mg_b50">
                        <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                        <button type="button" class="btn_st1">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
