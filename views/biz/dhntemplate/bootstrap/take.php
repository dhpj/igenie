    <link rel="stylesheet" type="text/css" href="/css/bss_css/bootstrap-select.css?v=<?=date("ymdHis")?>"/>
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
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu3.php');
?>
<!-- //3차 메뉴 -->
<!-- 타이틀 영역
<div class="tit_wrap">
	템플릿 관리
</div>
타이틀 영역 END -->
<div id="mArticle" class="dhntemplate_wrap">
	<div class="form_section">
		<div class="inner_tit">
			<h3>템플릿 가져오기</h3>
		</div>
		<div class="white_box">
			<div class="msg_box" style="width: 80%;">
				<div class="send_list">
					<input type="text" class="form-control input-width-large" name="file_info_display" id="file_info_display" placeholder="엑셀파일" style="width:55% !important;" readonly>
                    <input type="file" id="excel_upload_file" accept=".xlsx, .xls" style="display: none;" />
                    <button id="openModalBtn" class="btn btn-primary btn md fr" data-toggle="modal" data-target="#excel_modal" style="margin-left:5px;">이력 확인</button>
                    <button class="btn md fr" style="margin-left:5px;" onclick="uploadExcel()">전송</button>
                    <button class="btn md excel fr"  onclick="chooseExcelFile()">엑셀 가져오기</button>
				</div>
			</div>
			
			<div class="modal fade" id="excel_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">이력 확인
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" style="font-size: 40px">&times;</span>
                                </button>
                            </h5>
                        </div>
                        <div class="modal-body" style="max-height: 800px; overflow-y:auto;">
                            
                        </div>
                        <div class="modal-bottom">
                        	
                        </div>
                    </div>
                </div>
            </div>
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
					<td style="text-align:left;">
						<select name="senderkey" id="senderkey" class="selectpicker" data-live-search="true">
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
					<td style="text-align:left;">
						<input type="text" class="form-control input-width-large" name="tcode"
							   id="tcode" placeholder="템플릿 Code를 입력해주세요." style="width:400px !important;">
					</td>
				</tr>
			</table>
			<div class="btn_al_cen">
				<input type="button" class="btn_st1" style="cursor:pointer;" value="가져오기" name="register" id="register" onClick="taket();">
			</div>
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

<script>
        function chooseExcelFile() {
            var input = document.getElementById('excel_upload_file');
            input.click(); // 파일 선택 창 열기
            input.addEventListener('change', function() {
                var file = input.files[0];
                if (file) {
                    // 파일 확장자 확인
                    var fileName = file.name;
                    var ext = fileName.split('.').pop().toLowerCase();
                    if (ext === 'xlsx' || ext === 'xls') {
                        var fileInfoDisplay = document.getElementById('file_info_display');
                        fileInfoDisplay.value = fileName;
                    } else {
                        alert('엑셀 파일이 아닙니다.');
                        input.value = ''; // 파일 선택 취소
                    }
                }
            });
        }

        function uploadExcel() {
            var input = $('#excel_upload_file')[0];
            var file = input.files[0];
            if (file) {
                var formData = new FormData();
                
                formData.append('excel_upload_file', file);
                formData.append('<?= $this->security->get_csrf_token_name() ?>', '<?= $this->security->get_csrf_hash() ?>');
                formData.append('public_flag',"<?=$public_flag?>");
                
                $.ajax({
                    url: '/dhnbiz/template/excel_upload',
                    type: 'POST',
                    data:formData,
                    processData: false, 
                    contentType: false,
                    beforeSend:function(){
                        $('#overlay').fadeIn();
                    },
                    complete:function(){
                        $('#overlay').fadeOut();
                    },
                    success: function (json) {
                        var data = JSON.parse(json);
                        var code = data.code;
                        var message = data.message;
                        var all = data.all;
                        var success = data.success;
                        var fail = data.fail;

                        if (code === "200") {
                            alert("전체: " + all + "개, 성공: " + success + "개, 실패: " + fail + "개");
                        } else {
                            alert("오류: " + message);
                        }
                    },
                    error: function () {

                        $(".content").html("처리되지 않았습니다.");
                        $('#myModal').modal({backdrop: 'static'});
                    }
                });
            } else {
                alert('엑셀 파일을 선택하세요');
            }
        }
    </script>
    
    
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

		$("#openModalBtn").click(function() {
        	var page = $(this).data('page');
            search_template(page);
        });
	});

	function search_template(page) {
        if (page == 0) {
            page = 1;
        }
        
        $.ajax({
            url: "/dhnbiz/template/template_record_data",
            type: "POST",
            data:{
                page: page,
                '<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>'
            },
            dataType: "json",
            success: function(data) {
                //console.log(data);
                //var tableHtml = "<table class='table'><thead><tr><th>식별번호</th><th>업체명</th><th>프로필키</th><th>템플릿코드</th><th>결과코드</th><th>상태이유</th><th>날짜</th><th>그룹번호</th></tr></thead><tbody>";
                var tableHtml = "<table class='table'><thead><tr><th>식별번호</th><th>업체명</th><th>템플릿코드</th><th>상태이유</th><th>날짜</th><th>그룹번호</th></tr></thead><tbody>";
                for (var i = 0; i < data.list.length; i++) {
                    tableHtml += "<tr>";
                    tableHtml += "<td>" + data.list[i].tpl_id + "</td>";
                    tableHtml += "<td>" + data.list[i].tpl_company + "</td>";
                    tableHtml += "<td>" + data.list[i].tpl_code + "</td>";
                    //tableHtml += "<td>" + data.list[i].tpl_res_code + "</td>";
                    tableHtml += "<td>" + data.list[i].tpl_reason + "</td>";
                    tableHtml += "<td>" + data.list[i].tpl_record_date + "</td>";
                    tableHtml += "<td>" + data.list[i].tpl_group_num + "</td>";
                    tableHtml += "</tr>";
                }
                tableHtml += "</tbody></table>";

                
                $("#excel_modal .modal-body").html(tableHtml);

                var pageHtml = "<div class='align-center mt30'>";
                if(data.page_html !== '') {
                    pageHtml += data.page_html;
                }
                pageHtml += "</div>";
                $("#excel_modal .modal-bottom").html(pageHtml);
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }

	$("#nav li").addClass("current open");

	$(document).unbind("keyup").keyup(function (e) {
		// var code = e.which;
		// if (code == 13) {
		// 	$(".btn-primary").click();
		// }
	});

	//템플릿 가져오기
	function taket(){
		var senderKey = $("#senderkey").val();
		var tcode = $("#tcode").val();
		if(senderKey == ""){
			alert("프로필 업체를 선택하세요");
			$("#senderkey").focus();
			return;
		}
		if(tcode == ""){
			alert("템플릿 Code를 입력하세요");
			$("#tcode").focus();
			return;
		}
		//alert("senderKey : "+ senderKey +"\n"+"tcode : "+ tcode); return;
		$.ajax({
			url: "/dhnbiz/template/taket",
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
				//alert("정상적으로 가져오기가 처리 되었습니다.");
				//$("#tcode").val("");
				//$("#tcode").focus();
				var message = json['message'];
				var code = json['code'];
				//alert(message);
				//location.href = "/dhnbiz/template/public_lists";
				//return;
				showSnackbar(message, 1500);
					setTimeout(function() {
					location.href = "/dhnbiz/template/public_lists";
				}, 1000); //1초 지연
			},
			error: function () {
				//$(".content").html("처리되지 않았습니다.");
				//$('#myModal').modal({backdrop: 'static'});
				alert("처리되지 않았습니다.");
				return;
			}
		});
	}

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
