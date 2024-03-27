<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu2.php');
?>
<!-- //3차 메뉴 -->
		<div id="mArticle">
			<div class="form_section">
				<div class="inner_tit">
					<h3>발신번호 목록 (전체 <b style="color: red"><?=number_format($total_rows)?></b>개)</h3>
					<button class="btn_tr" onclick="location.href='/biz/sendphnmng/write'">발신번호 등록하기</button>
				</div>
				<div class="white_box">
					<div class="search_box">
							<input type="text" class="form-control input-width-medium inline" id="userid" name="userid" placeholder="업체명" value="<?=$param['userid']?>"/>
							<select class="" id="searchType">
								 <option value="X" <?=$param['search_type']=='X' ? 'selected' : ''?>>ALL</option>
								 <option value="O" <?=$param['search_type']=='O' ? 'selected' : ''?>>승인</option>
								 <option value="A" <?=$param['search_type']=='A' ? 'selected' : ''?>>심사중</option>
								 <option value="R" <?=$param['search_type']=='R' ? 'selected' : ''?>>반려</option>
							</select>&nbsp;
							<input type="text" class="form-control input-width-medium inline"
									 id="searchStr" name="searchStr" placeholder="발신번호" value="<?=$param['search_for']?>"/>&nbsp;
							<input type="button" class="btn btn-default" id="check" value="조회" onclick="open_page(1)"/>
					</div>
					<form action="/biz/sendphnmng/remove" method="post" id="mainForm">
					<table class="table_list">
						<colgroup>
                            <? if($this->member->item("mem_level")>=100) { ?>
							<col width="5%">
                            <? } ?>
                            <col width="10%"><?//등록일시?>
							<col width="*"><?//업체명?>
							<col width="12%"><?//발신번호?>
							<col width="8%"><?//사용용도?>
							<col width="8%"><?//인증방법?>

							<col width="8%"><?//상태?>
							<col width="8%"><?//사용여부?>
                            <? if($this->member->item("mem_level")>=100) { ?>
                            <col width="6%"><?//사용변경?>
                            <? } ?>
							<col width="5%"><?//첨부파일?>
						</colgroup>
						<thead>
						<tr>
                            <? if($this->member->item("mem_level")>=100) { ?>
							<th class="checkbox-column">
								<label class="checkbox_container">
									<input type="checkbox" id="check_all" value="" name="">
									<span class="checkmark"></span>
								</label>
							</th>
                            <? } ?>
                            <th class="text-center">등록일시</th>
							<th class="text-center">업체명</th>
							<th class="text-center">발신번호</th>
							<th class="text-center">사용용도</th>
							<th class="text-center">인증방법</th>

							<th class="text-center">상태</th>
							<th class="text-center">사용여부</th>
                            <? if($this->member->item("mem_level")>=100) { ?>
                            <th class="text-center">사용변경</th>
                            <? } ?>
							<th class="text-center">첨부파일</th>
						</tr>
						</thead>
						<tbody class="tbody">
							<? $uid = "";
                               $pre_uid = "";
                            foreach($list as $row) {?>
							<tr <?=($row->row_cnt>1&&$row->use_flag=='Y')? "class='number_use'" : ""?>>
                                <? if($this->member->item("mem_level")>=100) { ?>
								<td class="checkbox-column" style="vertical-align: middle !important;">
								  <label class="checkbox_container">
									<input type="checkbox" id="stn_id" value="<?=$row->idx?>" name="stn_ids[]" class="chk">
									<span class="checkmark"></span>
								  </label>
								</td>
                                <? } ?>
                                <? $uid = $row->mem_id;

                                //if($uid!=$pre_uid){ ?>
                                <td class="text-center" style="vertical-align: middle !important;"><?=$row->reg_date?></td>
                                <td class="text-center" style="vertical-align: middle !important;" ><?=$row->mem_username?></td>
                                <? //} ?>
								<td class="text-center" style="vertical-align: middle !important;"><?=$this->funn->format_phone($row->send_tel_no,"-")?></td>
								<td class="text-left" style="vertical-align: middle !important;"><?=$row->memo?></td>
								<td class="text-center" style="vertical-align: middle !important;"><?=$row->auth_type?></td>

								<td class="text-center" style="vertical-align: middle !important;"><? switch($row->auth_flag) { case "A": echo "심사중"; break; case "R":echo "반려"; break; case "O":echo "승인"; break; }?></td>
								<td class="text-center" style="vertical-align: middle !important;"><? switch($row->use_flag) { case "Y": echo "사용"; break; case "N":echo "미사용"; break; }?></td>
                                <? if($this->member->item("mem_level")>=100) { ?>
                                <td class="text-center" style="vertical-align: middle !important;"><? switch($row->use_flag) { case "Y": if($row->cnt>1&&$row->auth_flag=='O'){echo "<button class='btn sm'  type='button' onclick='chguse(\"".$row->idx."\", \"".$row->mem_id."\")'>사용하기</button>";}else{ echo "<button class='btn sm'  type='button' onclick='chguse(\"".$row->idx."\", \"".$row->mem_id."\")'>사용하기</button>";} break; case "N": if($row->auth_flag == 'O'){ echo "<button class='btn sm'  type='button' onclick='chguse(\"".$row->idx."\", \"".$row->mem_id."\")'>사용하기</button>";}else{ echo "";} break; }?></td>
								<?}?>
								<td class="text-center" style="vertical-align: middle !important;"><? if($row->auth_type == "file" && $row->att_url != "") { ?><img onclick="img_detail('/<?=$row->att_url?>', '<?=$row->idx?>');" id="img_preview_<?=$row->idx?>" role="presentation" src="/<?=$row->att_url?>" style="left: -5px; top: 0px; width: 40px; height: 30px;"> <? } ?>
									<!--<span>
										<?
									//dirname($row->att_url)
									echo $oldpath."<br/>";
									echo dirname($oldpath)."<br/>";
									echo basename($oldname)."<br/>";
									echo $row->idx."<br/>";
									?></span>-->


								 </td>
							</tr>
							<?  $pre_uid = $row->mem_id;
                                }?>
						</tbody>
					</table>
                    <? if($this->member->item("mem_level")>=100) { ?>
                    <div class="row">
                       <div class="fl">
                           <button class="btn_st1" type="button" onclick="selectDelRow()"><i class="xi-call-missed"></i> 선택삭제</button>
                           <!-- <button class="btn_st1" type="button" onclick="selectUseRow()"><i class="xi-call-incoming"></i> 선택사용</button> -->
                       </div>
                       <div class="fr">

                           <button class="btn_st1 " type="button" onclick="selectAppRow()"><i class="xi-check-circle-o"></i> 선택승인</button>
                           <button class="btn_st1 " type="button" onclick="selectRejRow()"><i class="xi-ban"></i> 선택반려</button>

                       </div>
                   </div>
                   <? } ?>
					<div class="page_cen"><?echo $page_html?></div>
				</div>
			</div>
		</div>
        <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="modal">
                <div class="modal-content">
                    <br/>
                    <div class="modal-body">
                        <div class="content">
                        </div>
                        <div class="btn_al_cen mg_t20 mg_b50">

																<input type="hidden" id="hdn_idx" />
																<input type="hidden" id="hdn_oldpath" />
																<label for="img_file" class="btn_st1" style="font-size:13px;">수정</label>
																<input type="file" title="이미지 파일" id="img_file" onChange="imgChange(this);" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
																<button type="button" class="btn_st1" data-dismiss="modal" id="identify">
                                    닫기
                                </button><!--<sapn id="testspan1"></span>-->
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
                        <div class="btn_al_cen mg_t20 mg_b50">
                                <button type="button" class="btn_st1" data-dismiss="modal">취소</button>
                                <button type="submit" class="btn_st1">확인</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script type="text/javascript">

				var set_imgpath_id = "";
				var request = new XMLHttpRequest();

				$("#nav li.nav16").addClass("current open");

        $('#searchStr').unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                open_page(1);
            }
        });

        function open_page(page) {
            var uid = $('#userid').val();
            var type = $('#searchType').val();
            var searchFor = $('#searchStr').val();
            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/biz/sendphnmng");
            document.body.appendChild(form);
            var csrfField = document.createElement("input");
            csrfField.setAttribute("type", "hidden");
            csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
            csrfField.setAttribute("value", '<?=$this->security->get_csrf_hash()?>');
            form.appendChild(csrfField);
            var uidField = document.createElement("input");
            uidField.setAttribute("type", "hidden");
            uidField.setAttribute("name", "uid");
            uidField.setAttribute("value", uid);
            form.appendChild(uidField);
            var srchforField = document.createElement("input");
            srchforField.setAttribute("type", "hidden");
            srchforField.setAttribute("name", "search_type");
            srchforField.setAttribute("value", type);
            form.appendChild(srchforField);
            var srchtxtField = document.createElement("input");
            srchtxtField.setAttribute("type", "hidden");
            srchtxtField.setAttribute("name", "search_for");
            srchtxtField.setAttribute("value", searchFor);
            form.appendChild(srchtxtField);
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "page");
            hiddenField.setAttribute("value", page);
            form.appendChild(hiddenField);

            form.submit();
        }

        //삭제
        function selectDelRow() {
            if ($("input:checkbox[id='stn_id']").is(":checked") == false) {
                $(".content").html("삭제할 발신번호를 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else {
                $("#mainForm").attr("action", "/biz/sendphnmng/remove");
                $(".content").html("삭제하시겠습니까?");
                $("#myModalCheck").modal({backdrop: 'static'});
                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".submit").click();
                    }
                });
            }
        }

        //선택사용
        function selectUseRow() {
            if ($("input:checkbox[id='stn_id']").is(":checked") == false) {
                $(".content").html("사용할 발신번호를 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else {
                $("#mainForm").attr("action", "/biz/sendphnmng/usephnno");
                $(".content").html("해당 발신번호를 사용 하시겠습니까?");
                $("#myModalCheck").modal({backdrop: 'static'});
                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".submit").click();
                    }
                });
            }
        }

        function selectAppRow() {
        	if ($("input:checkbox[id='stn_id']").is(":checked") == false) {
                $(".content").html("승인할 발신번호를 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else {
                $("#mainForm").attr("action", "/biz/sendphnmng/approval");
                $(".content").html("승인하시겠습니까?");
                $("#myModalCheck").modal({backdrop: 'static'});
                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".submit").click();
                    }
                });
            }
        }

        function selectRejRow() {
        	if ($("input:checkbox[id='stn_id']").is(":checked") == false) {
                $(".content").html("반려할 발신번호를 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else {
                $("#mainForm").attr("action", "/biz/sendphnmng/reject");
                $(".content").html("반려하시겠습니까?");
                $("#myModalCheck").modal({backdrop: 'static'});
                $(document).unbind("keyup").keyup(function (e) {
                    var code = e.which;
                    if (code == 13) {
                        $(".submit").click();
                    }
                });
            }
        }

				//발신프로필 21-06-21 수정 추가
        function img_detail(img_url, idx) {
					  $("#hdn_idx").val(idx);
						var str = img_url;
						var oldpath = str.replace('/uploads/member_certi/', '');
						//var oldpath = img_url;
						$("#hdn_oldpath").val(oldpath);
						//$("#testspan1").html(idx + " " + oldpath);
            var html = '<img align="center" id="pre_img_preview" src="' + img_url + '" style="width:100%;"/>';
            $(".content").html(html);
            $(".modal-dialog").css("max-width","700px").css("height","auto").css("top","0"); //TODO 높이설정
            $("#myModal").modal('show');

        }

        //등록, 삭제 JSON 응답 메세지
        var message = '';
        if (message != "") {
            $(".content").html(message);
            $('#myModal').modal({backdrop: 'static'});
            $('#myModal').on('hidden.bs.modal', function () {
                location.href = "list";
            });
        }
        var message2 = '';
        var success = '';
        var fail = '';
        if (message2 != "") {
            $(".content").html(message2 + " (성공 : " + success + "건, 실패 : " + fail + "건)");
            $('#myModal').modal({backdrop: 'static'});
            $('#myModal').on('hidden.bs.modal', function () {
                location.href = "list";
            });
        }
        var fail_message = '';

        if (fail_message != "") {
            $(".content").html(fail_message);
            $('#myModal').modal({backdrop: 'static'});
            $('#myModal').on('hidden.bs.modal', function () {
                location.href = "list";
            });
        }

        $('#mainForm').submit(function() {
            $('#overlay').fadeIn();
            return true;
        });

        //슈퍼관리자 시작
        function search() {
            var form = document.createElement("form");
            document.body.appendChild(form);
            form.setAttribute("method", "post");
            form.setAttribute("action", "/sendphnmng");
            document.body.appendChild(form);

            form.submit();
        }
        //슈퍼관리자 끝

        //숫자 여부 확인
        function SetNum(obj){
            var val = obj.value;
            var re = /[^0-9]/gi;
            obj.value = val.replace(re, "");
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

        function chguse(id, uid){
            if(id!=""&&uid!=""){
                $.ajax({
                     url: "/biz/sendphnmng/chguse",
                     type: "POST",
                     data: {
                          "<?=$this->security->get_csrf_token_name()?>": "<?=$this->security->get_csrf_hash()?>",
                          "id": id,
                          "uid": uid
                     },
                     success: function (json) {
                         showSnackbar(json.msg, 1500);
                         if(json.code == "0"){
                             setTimeout(function() {
     							 location.reload();
     						}, 1500);
                         }


                     },
                     error: function (data, status, er) {
                          showSnackbar("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.", 1500);
                     }
                });
            }
        }

        //SMS발신번호 수정
        function sender_modify(obj) {
				var pf_key = $(obj).parent().parent().find("#pro_key").val().trim();
            var sms_sender = $(obj).parent().parent().find("#sms_sender").val().trim();
            if(sms_sender == ""){
                $(".content").html("SMS 번호를 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else {
					$.ajax({
						 url: "/biz/sendphnmng/modify",
						 type: "POST",
						 data: {
							  <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
							  pf_key: pf_key,
							  sms_sender: sms_sender
						 },
						 beforeSend: function () {
							  $('#overlay').fadeIn();
						 },
						 complete: function () {
							  $('#overlay').fadeOut();
						 },
						 success: function (json) {
							 if(!json['success']) {
								$(".content").html("저장중 오류가 발생하였습니다.");
								$("#myModal").modal('show');
							 }
						 },
						 error: function (data, status, er) {
							  $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
							  $("#myModal").modal('show');
						 }
					});
				}
        }

        //프로필 상태 조회
        function sender_refresh(spf_key, appr_id) {
			$.ajax({
				 url: "/biz/sendphnmng/refresh",
				 type: "POST",
				 data: {
					  <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					  spf_key: spf_key,
					  appr_id: appr_id
				 },
				 beforeSend: function () {
					  $('#overlay').fadeIn();
				 },
				 complete: function () {
					  $('#overlay').fadeOut();
				 },
				 success: function (json) {
					 if(!json['success']) {
						$(".content").html("저장중 오류가 발생하였습니다.");
						$("#myModal").modal('show');
					 }
					 open_page(1);
				 },
				 error: function (data, status, er) {
					  $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다." + status);
					  $("#myModal").modal('show');
				 }
			});
        }

		//전체선택
		$("#check_all").click(function(){
	        var chk = $(this).is(":checked");//.attr('checked');
	        if(chk) $(".tbody .chk").prop('checked', true);
	        else  $(".tbody .chk").prop('checked', false);
	    });


		//이미지 선택 클릭시
		function imgChange(input){

			var id = $("#hdn_idx").val();
			var oldpath = $("#hdn_oldpath").val();



		//	var id = "_"+ $("#goods_step_id").val(); //선택된 STEP ID
			//alert("id : "+ id +", input.value : "+ input.value); return;
			if(input.value.length > 0) {
				//alert("input.value : "+ input.value);
				if (input.files && input.files[0]) {
				//	remove_img(id);
					var fileSize = input.files[0].size; //파일사이즈
					var maxSize = "5 * 1024 * 1024"; //파일 제한 사이지(byte)
					//alert("fileSize : "+ fileSize +", maxSize : "+ maxSize);
					if(maxSize < fileSize){
						jsFileRemove(input); //파일 초기화
						alert("이미지 파일 사이즈는 "+ jsFileSize(maxSize,0) +" 이내로 등록 가능합니다.\n\n현재 파일 사이즈는 "+ jsFileSize(fileSize,1) +" 입니다.");
						return;
					}
					//alert("첨부파일 사이즈 체크 완료"); return;
					readURL(input, "img_preview_"+ id, id, oldpath);
					//modal2.style.display = "none"; //상품 이미지 추가 모달창 닫기
				}
			}
		}

		//배경 이미지 초기화
			function remove_img(rowid){
			//	$("#img_preview"+ rowid).attr("src", "");
			//	$("#img_preview"+ rowid).css("display", "none");
			//	$("#div_preview"+ rowid).css({"background":"url('')"});
			//	$("#div_preview"+ rowid).addClass("templet_img_in2");
			//	$("#pre_img_preview"+ rowid).attr("src", "");
			//	$("#pre_img_preview"+ rowid).css("display", "none");
			//	$("#pre_div_preview"+ rowid).css({"background":"url('')"});
			//	$("#pre_div_preview"+ rowid).addClass("templet_img_in3");
				$("#img_preview_"+ rowid).attr("src", "");

			}

		//이미지 경로 세팅
		function readURL(input, divid, id, oldpath){
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
				//	$("#"+ divid).css({"background":"url(" + e.target.result + ")"});
				//	$("#pre_"+ divid).css({"background":"url(" + e.target.result + ")"});
				//	$("#pre_"+ divid).attr("tabindex", -1).focus(); //미리보기 포커스
					$("#"+ divid).css({"src":"'" + e.target.result + "'"});

				}
				reader.readAsDataURL(input.files[0]);
				set_imgpath_id = divid;

				//이미지 추가

				request = new XMLHttpRequest();
				var formData = new FormData();
				formData.append("imgfile", input.files[0]); //이미지
				formData.append("oldpath", oldpath); // 이전 파일 경로 및 파일이름
				formData.append("idx", id); // 수정할 idx
				formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
				request.onreadystatechange = imgCallback;
				request.open("POST", "/biz/sendphnmng/imgfile_save");
				request.send(formData);


			}
		}

		//리턴값 처리
		function imgCallback(){
			//console.log(request.responseText);

			//dump(request.responseText);
			if(request.readyState  == 4) {
				var obj = JSON.parse(request.responseText);
				if(obj.code == "0") { //성공
					$("#"+ set_imgpath_id).attr("src", obj.imgpath);
					$("#"+ set_imgpath_id).removeAttr("onclick");
					$("#"+ set_imgpath_id).attr("onclick", "img_detail('"+obj.imgpath+"','"+ obj.idx+"')");
					$("#pre_img_preview").attr("src", obj.imgpath);

					//$("#pre_"+ set_imgpath_id).html(obj.imgpath);
				} else { //오류
				}
			}
		}


		function dump(obj) {
		    var out = '';
		    for (var i in obj) {
		        out += i + ": " + obj[i] + "\n";
		    }

		    alert(out);

		    // or, if you wanted to avoid alerts...

		    var pre = document.createElement('pre');
		    pre.innerHTML = out;
		    document.body.appendChild(pre)
		}
</script>
