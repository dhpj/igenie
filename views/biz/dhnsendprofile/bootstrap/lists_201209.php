    <!-- form action="/dhnbiz/sendprofile/remove" method="post" id="mainForm" -->
    <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="modal">
                <div class="modal-content">
                    <br/>
                    <div class="modal-body">
                        <div class="content">
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
                                <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                                <button type="submit" class="btn btn-primary submit">확인</button>
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
							<h3>발신 프로필 목록 (전체 <b style="color: red"><?=number_format($total_rows)?></b>개)</h3>
							<button class="btn_tr" onclick="location.href='/dhnbiz/sendprofile/write'">프로필 등록하기</button>
							<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 보임 ?>
								<button class="btn_tr2" onclick="location.href='/biz/senddhnprofile/take'">등록된 프로필 가져오기</button>
							<? } //if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 보임 ?>
						</div>
						<div class="white_box">
							<div class="search_box">
								<label for="userid"></label>
								<select name="userid" id="userid" class="">
									<option value="ALL">-ALL-</option>
								<? foreach($users as $r){ ?>
									<option value="<?=$r->mem_id?>" <?=($param['userid']==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
								<? } ?>
								</select>
								<select class="" id="searchType">
									 <option value="company" <?=$param['search_type']=='company' ? 'selected' : ''?>>업체명</option>
									 <option value="friend" <?=$param['search_type']=='friend' ? 'selected' : ''?>>플러스친구</option>
								</select>
								<input type="text" class="searchBox" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$param['search_for']?>" />
								<input type="button" class="btn md" id="check" value="조회" onclick="open_page(1)"/>
							</div>
							<div class="table_list">
								<table cellpadding="0" cellspacing="0" border="0">
									<colgroup>
								        <col width="60px">
								        <col width="160px"><?//등록일시?>
								        <col width="180px"><?//업체명?>
								        <col width="180px"><?//플러스친구	?>
								        <col width="*"><?//발신 프로필 키?>
								        <col width="80px"><?//상태	?>
								        <col width="150px"><?//SMS발신번호?>
								        <col width="80px"><?//수정?>
								        <col width="80px"><?//확인?>
								    </colgroup>
								    <thead>
									    <tr>
										    <th>
											  <label class="checkbox_container">
												<input type="checkbox" id="" value="" name="">
												<span class="checkmark"></span>
											  </label>
											</th>
										    <th>등록일시</th>
										    <th>업체명</th>
										    <th>플러스친구</th>
										    <th>발신 프로필 키</th>
										    <th>상태</th>
										    <th>SMS발신번호</th>
										    <th>수정</th>
										    <th>확인</th>
									    </tr>
								    </thead>
								    <tbody>
									    <?foreach($list as $row) {?>
									    <tr>
										    <td>
											  <label class="checkbox_container">
												<input type="checkbox" id="pf_key" value="<?=$row->spf_key?>" name="pf_key[]">
												<span class="checkmark"></span>
											  </label>
											</td>
										    <td><?=$row->spf_datetime?></td>
										    <td><?=$row->spf_company?></td>
										    <td><?=$row->spf_friend?></td>
										    <td><?=$row->spf_key?></td>
										    <td><? if($row->spf_status=='S') { echo "차단"; } else if($row->spf_status=='D') {echo "삭제";} else { echo "정상";} ?></td><!-- SMS발신하는업체만 표시 -->
										    <td>
												<?if($row->spf_mem_id==$this->member->item('mem_id')) {?>
													<input type="tel" class="form-control" style="width: 100%;" id="sms_sender" name="sms_sender" maxlength="25" onkeyup="SetNum(this);" value="<?=$row->spf_sms_callback?>" onchange="noSpaceForm(this);">
												<?} else { ?>
													<? echo $this->funn->format_phone($row->spf_sms_callback,"-"); ?>
												<? } ?>
												</td>
										    <td><input type="hidden" name="pro_key" id="pro_key" value="<?=$row->spf_key?>"><?if($row->spf_mem_id==$this->member->item('mem_id')) {?><button class="btn sm" type="button" onclick="sender_modify(this);">수정</button><?}?></td>
										    <td><button class="btn sm" onclick="sender_refresh('<?=$row->spf_key?>', '<?=$row->spf_appr_id?>');">확인</button></td>
									    </tr>
									    <?}?>
								    </tbody>
								</table>
							</div>
							<div class="page_cen"><?echo $page_html?></div>
						</div>
					</div>
				</div><!-- mArticle END -->
    <!-- /form -->

    <script type="text/javascript">
        $("#nav li.nav50").addClass("current open");

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
            form.setAttribute("action", "/dhnbiz/sendprofile/lists");
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
            if ($("input:checkbox[id='pf_key']").is(":checked") == false) {
                $(".content").html("삭제할 발신 프로필을 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else {
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
            form.setAttribute("action", "/sendprofile/list");
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

        //SMS발신번호 수정
        function sender_modify(obj) {
				var pf_key = $(obj).parent().parent().find("#pro_key").val().trim();
            var sms_sender = $(obj).parent().parent().find("#sms_sender").val().trim();
            if(sms_sender == ""){
                $(".content").html("SMS 번호를 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
            } else {
					$.ajax({
						 url: "/dhnbiz/sendprofile/modify",
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
				 url: "/dhnbiz/sendprofile/refresh",
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

</script>
