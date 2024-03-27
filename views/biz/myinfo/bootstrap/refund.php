    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div class="btn_al_cen">
                            <button type="button" class="btn_st1" data-dismiss="modal">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 3차 메뉴 -->
    <?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu4.php');
    ?>
    <!-- //3차 메뉴 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>환불신청</h3>
		</div>
		<div class="white_box">
			<div class="panel_info">
				<p>* 환불을 신청하시기 전에 아래의 사항을 반드시 확인하시기 바랍니다.</p>
                <ul>
                    <li>환불처리기간은 환불 신청일+7일 이후 월요일 입금(영업일기준) 처리됩니다.</li>
                    <li>이벤트(서비스)예치금은 환불정책에서 제외됩니다.</li>
                    <li>부가세 10% 공제 후 [소비자 피해보상규정 인터넷콘텐츠업]에서 규정한 정책에 따라 10% 추가 공제한 금액이 환불됩니다.</li>
                    <li>환불신청은 회원가입 시 가입했던 이름의 통장사본 파일을 첨부해야 처리됩니다.</li>
                    <li>환불요청금액이 1만원 이상일 때, 환불신청이 가능하며 실제 고객님의 결제하신 금액만 환불됩니다. (보너스 포인트, 무상으로 지급된 포인트는 환불대상에서 제외)</li>
                </ul>
            </div>

                            <div class="myinfo_list">
                              <dl>
                                <dt>현재잔액</dt>
                                <dd><p id="amount"><?=number_format(floor($total_deposit))?> 원</p></dd>
                              </dl>
                              <dl <?=($total_event>0)? "" : "style=display:none;" ?>>
                                <dt>이벤트예치금</dt>
                                <dd><p id="amount"><?=($total_event>0)? "-".number_format($total_event)." 원 (이벤트금액은 환불정책에서 제외됩니다)" : ""?></p></dd>
                              </dl>
                              <dl>
                                <dt>환불신청금액<span class="required">*</span></dt>
                                <dd><input id="refund_amount_req" onkeyup="SetNum(this); amount(); standard();" type="text" class="form-control input-width-large" maxlength='25'/></dd>
                              </dl>
                              <dl>
                                <dt>환불확정금액</dt>
                                <dd><input id="refund_amount" type="text" class="form-control input-width-large" maxlength='25' disabled style="cursor: default; background-color: white; color: black;"/><span> * 부가세 10% 선공제 후 환불규정에 따라 10% 추가로 공제됩니다.</span></dd>
                              </dl>
                              <dl>
                                <dt>환불은행<span class="required">*</span></dt>
                                <dd><input id="bank_nm" type="text" onkeyup="standard();" class="form-control input-width-large" maxlength='25'/></dd>
                              </dl>
                              <dl>
                                <dt>계좌번호<span class="required">*</span></dt>
                                <dd><input id="bank_account" type="text" onkeyup="SetNum(this); standard();" class="form-control input-width-large" placeholder="'-' 없이 입력해주세요" maxlength='25'/></dd>
                              </dl>
                              <dl>
                                <dt>통장사본<span class="required">*</span></dt>
                                <dd>
                                  <input id="file_attach" name="file_attach" type="file" onclick="standard();" onchange="CheckUploadFileSize(this)" maxlength="25" accept="image/jpg, image/png"/>
                                            <span class="help-block msg"> &nbsp;jpg, png 파일만 업로드 됩니다. (최대 500KB) </span>
                                </dd>
                              </dl>
                              <dl>
                                <dt>예금주<span class="required">*</span></dt>
                                <dd><input id="bank_depositor" type="text" onkeyup="standard();" class="form-control input-width-large" maxlength='25'/></dd>
                              </dl>
                              <dl>
                                <dt>환불사유<span class="required">*</span></dt>
                                <dd>
                                  <textarea id="refund_resn" onkeyup="standard();" cols="30" rows="3" class="form-control autosize" placeholder="환불 사유를 간단하게 작성해 주세요."></textarea>
                                            <p class="help-block"><span id="type_num">0</span>/100자</p>
                                </dd>
                              </dl>
                              <dl>
                                <dt>연락처<span class="required">*</span></dt>
                                <dd><input id="contact" type="text" onkeyup="SetNum(this); standard();" class="form-control input-width-large" maxlength='25'/></dd>
                              </dl>
                              <dl>
                                <dt>비밀번호<span class="required">*</span></dt>
                                <dd><input id="refund_pw" onkeyup="standard(); javascript:var quit_pw = $('#refund_pw').val();var space = quit_pw.replace(/ /gi,'');$('#refund_pw').val(space);" type="password" class="form-control input-width-large" maxlength='25'/></dd>
                              </dl>
                            </div>

                            <div class="btn_al_cen mg_b50">
                                <a onclick="javascript:location.reload();" class="btn_st3">취소</a>
                                <a type="button" onclick="refund();" class="btn_st3">환불 신청</a>
                            </div>
		</div>
	</div>
</div>


    <script>
        //확인창 확인버튼
        $(document).unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                $(".enter").click();
            }
        });

        //숫자만 입력 가능
        function SetNum(obj) {
            val = obj.value;
            re = /[^0-9]/gi;
            obj.value = val.replace(re, "");
        }

        function standard(){
            var amount = '<?=floor($total_deposit)?>';
            if (parseFloat(amount) < 10000) {
                $('#file_attach').attr('disabled','disabled');
                $(".content").html("현재 잔액이 1만원 이상일 때, 환불 신청이 가능합니다.");
                $("#myModal").modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $('#refund_amount_req').val('');
                    $("#refund_amount").val('');
                    $("#bank_nm").val('');
                    $("#bank_account").val('');
                    $("#bank_depositor").val('');
                    $("#refund_resn").val('');
                    $("#contact").val('');
                    $("#refund_pw").val('');
                    $("#type_num").html(0);
                    $('#refund_amount_req').blur();
                });
                $(".enter").click(function () {
                    $("#file_attach").removeAttr("disabled");
                })
            }
        }

        //글자수 제한 - 환불 은행
        $("#bank_nm").keyup(function(){
            var limit_length = 40;
            var msg_length = $(this).val().length;
            if( msg_length > limit_length ){
                $("#bank_nm").focus();
                var cont = $("#bank_nm").val();
                var cont_slice = cont.slice(0,40);
                $("#bank_nm").val(cont_slice);
                return;
            }
        });

        //글자수 제한 - 환불 계좌번호
        $("#bank_account").keyup(function(){
            var limit_length = 90;
            var msg_length = $(this).val().length;
            if( msg_length > limit_length ){
                $("#bank_account").focus();
                var cont = $("#bank_account").val();
                var cont_slice = cont.slice(0,90);
                $("#bank_account").val(cont_slice);
                return;
            }
        });

        //글자수 제한 - 예금주
        $("#bank_depositor").keyup(function(){
            var limit_length = 40;
            var msg_length = $(this).val().length;
            if( msg_length > limit_length ){
                $("#bank_depositor").focus();
                var cont = $("#bank_depositor").val();
                var cont_slice = cont.slice(0,40);
                $("#bank_depositor").val(cont_slice);
                return;
            }
        });

        //글자수 제한 - 연락처
        $("#contact").keyup(function(){
            var limit_length = 40;
            var msg_length = $(this).val().length;
            if( msg_length > limit_length ){
                $("#contact").focus();
                var cont = $("#contact").val();
                var cont_slice = cont.slice(0,40);
                $("#contact").val(cont_slice);
                return;
            }
        });

        //글자수 제한 - 환불 사유 내용
        $("#refund_resn").keyup(function(){
            var limit_length = 100;
            var msg_length = $(this).val().length;
            if( msg_length <= limit_length ) {
                $("#type_num").html(msg_length);
            } else if( msg_length > limit_length ){
                $(".content").html("환불 사유를 100자 이내로 입력해주세요.");
                $("#myModal").modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#refund_resn").focus();

                });
                var cont = $("#refund_resn").val();
                var cont_slice = cont.slice(0,100);
                $("#refund_resn").val(cont_slice);
                $("#type_num").html(100);
                return;
             } else {
                $("#type_num").html(msg_length);
            }
        });

        //파일 업로드 크기 제한
       function CheckUploadFileSize(objFile) {
            var maxSize = 500 * 1024;
            var fileSize = objFile.files[0].size;
            if (fileSize > maxSize) {
                $(".content").html("최대 500KB까지만 가능합니다.");
                $("#myModal").modal({backdrop: 'static'});
                objFile.outerHTML = objFile.outerHTML;
            }
        }

        function amount() {
            var amount = '<?=floor($total_deposit)?>';
            var eve_amt = '<?=$total_event?>';
            var amt = amount - eve_amt;

            var refund_amount_req = $('#refund_amount_req').val();

            // console.log("eve_amt : "+eve_amt+ " amt : " +amt + " refund_amount_req : " + refund_amount_req);
            if(amt<0){
                $(".content").html("환불신청 가능금액이 없습니다");
                $("#myModal").modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#refund_amount_req").focus();
                });
                $('#refund_amount_req').val('');
                $('#refund_amount').val('');
            }else{
                if (parseFloat(amt) < parseFloat(refund_amount_req)) {
                    $(".content").html("현재잔액보다 환불신청금액이 큽니다.");
                    $("#myModal").modal({backdrop: 'static'});
                    $(this).on('hidden.bs.modal', function () {
                        $("#refund_amount_req").focus();
                    });
                    $('#refund_amount_req').val('');
                    $('#refund_amount').val('');

                } else {
                    if (refund_amount_req == '') {
                        $('#refund_amount').val('');
                    } else {
                        // var y_refund = amt * 9/10;
                        if(refund_amount_req <= amt){
                            var fee = refund_amount_req * 9 / 10;
                            var fee2 = fee * 9 / 10;
                            $('#refund_amount').val(parseInt(fee2));
                        }else{
                            $(".content").html("현재잔액보다 환불신청금액이 큽니다.");
                            $("#myModal").modal({backdrop: 'static'});
                            $(this).on('hidden.bs.modal', function () {
                                $("#refund_amount_req").focus();
                            });
                            $('#refund_amount_req').val('');
                            $('#refund_amount').val('');
                        }

                    }
                }
            }

        }

        function refund() {
            var refund_amount_req = $("#refund_amount_req").val();
            var refund_amount = $("#refund_amount").val();
            var bank_nm = $("#bank_nm").val();
            var bank_account = $("#bank_account").val();
            var file_attach = $("#file_attach").val();
            var bank_depositor = $("#bank_depositor").val();
            var refund_resn = $("#refund_resn").val();
            var contact = $("#contact").val();
            var refund_pw = $("#refund_pw").val();
            var amount = '<?=$total_deposit?>';
            if (parseFloat(amount) < 10000) {
                $(".content").html("현재 잔액이 1만원 이상일 때, 환불 신청이 가능합니다.");
                $("#myModal").modal({backdrop: 'static'});
            } else if (parseFloat(refund_amount_req) < 10000) {
                $(".content").html("환불신청금액을 1만원 이상 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#refund_amount_req").focus();
                });
            } else if (refund_amount_req.replace(/ /gi,"") == "") {
                $(".content").html("환불신청금액을 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#refund_amount_req").focus();
                });
            } else if (bank_nm.replace(/ /gi,"") == "") {
                $(".content").html("환불은행을 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#bank_nm").focus();
                });
            } else if (bank_account.replace(/ /gi,"") == "") {
                $(".content").html("계좌번호를 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#bank_account").focus();
                });
            } else if (file_attach.replace(/ /gi,"") == "") {
                $(".content").html("통장사본을 선택해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#file_attach").focus();
                });
            } else if (bank_depositor.replace(/ /gi,"") == "") {
                $(".content").html("예금주를 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#bank_depositor").focus();
                });
            } else if (refund_resn.replace(/ /gi,"") == "") {
                $(".content").html("환불사유를 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#refund_resn").focus();
                });
            } else if (contact.replace(/ /gi,"") == "") {
                $(".content").html("연락처를 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#contact").focus();
                });
            } else if (refund_pw.replace(/ /gi,"") == "") {
                $(".content").html("비밀번호를 입력해주세요.");
                $('#myModal').modal({backdrop: 'static'});
                $(this).on('hidden.bs.modal', function () {
                    $("#refund_pw").focus();
                });
            } else {
                var file_data = new FormData();
				file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
                file_data.append("refund_amount_req", refund_amount_req);
                file_data.append("refund_amount", refund_amount);
                file_data.append("bank_nm", bank_nm);
                file_data.append("bank_account", bank_account);
                file_data.append("file_attach", $("input[name=file_attach]")[0].files[0]);
                file_data.append("bank_depositor", bank_depositor);
                file_data.append("refund_resn", refund_resn);
                file_data.append("contact", contact);
                file_data.append("refund_pw", refund_pw);

                $.ajax({
                    url: "/biz/myinfo/refund",
                    type: "POST",
                    data: file_data,
                    processData: false,
                    contentType: false,
                    beforeSend:function(){
                        $('#overlay').fadeIn();
                    },
                    complete:function(){
                        $('#overlay').fadeOut();
                    },
                    success: function (json) {
                        var result = json['result'];
                        if (result == true) {
                            $(".content").html("환불 신청이 정상적으로 등록되었습니다.");
                            $('#myModal').modal({backdrop: 'static'});
                            $('#myModal').on('hidden.bs.modal', function () {
                                location.href='/biz/myinfo/refund'
                            });
                        } else if(result==false) {
                            $(".content").html(json['message']);
                            $('#myModal').modal({backdrop: 'static'});
                            $('#myModal').on('hidden.bs.modal', function () {
                                $("#refund_pw").focus();
                                $("#refund_pw").val('');
                            });
                        }
                    },
                    error: function () {
                        $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의하세요.");
                        $('#myModal').modal({backdrop: 'static'});
                    }
                });
            }
        }
    </script>
