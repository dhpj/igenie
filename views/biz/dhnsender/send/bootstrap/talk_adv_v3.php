<script type="text/javascript" src="/js/plugins/moment-with-locales.js"></script>
<script type="text/javascript" src="/js/plugins/bootstrap-datetimepicker.js"></script>
 <!-- head>
	<meta http-equiv="Expires" content="0"/>
	<meta http-equiv="Pragma" content="no-cache"/>
</head -->
<script type="text/javascript">
<!--
	var edit_control = "templi_cont";

    var lmsflag = "sms";

    var buttonArr = [];
    // var buttonNameArr = [];

    var sendFlag = false;


//-->
</script>
<!-- 타이틀 영역 -->
<input type="hidden" id="navigateURL" value="" />
<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu1.php');
?>
<script>
	var ms = '<?=($mem->mem_2nd_send) ? substr(strtoupper($mem->mem_2nd_send), 0, 1) : ""?>';
	var at_price = '<?=$this->Biz_dhn_model->price_at?>';
	var sms_price = '<?=$this->Biz_dhn_model->price_sms?>';
	var lms_price = '<?=$this->Biz_dhn_model->price_lms?>';
	var mms_price = '<?=$this->Biz_dhn_model->price_mms?>';
	var phn_price = '<?=$this->Biz_dhn_model->price_phn?>';

    <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
        at_price = '<?=$this->Biz_dhn_model->price_v_at?>';
    	sms_price = '<?=$this->Biz_dhn_model->price_v_smt_sms?>';
    	lms_price = '<?=$this->Biz_dhn_model->price_v_smt?>';
    	mms_price = '<?=$this->Biz_dhn_model->price_v_smt_mms?>';
    <? } ?>

    function eaSendBtn(){
        $('.btn_send').prop('disabled', false);
        console.log($('.btn_send').prop('disabled'));
    }

</script>
<!--<div class="tit_wrap" id="id_send_area">
	메시지 작성
</div>-->
<!-- 컨텐츠 전체 영역 -->
<!-- form action="/dhnbiz/sender/talk/send" method="post" id="sendForm" name="sendForm" onsubmit="return false;" enctype="multipart/form-data" -->
<!-- input type='hidden' name='sms_sender' id='sms_sender' value='<?=$tpl->spf_sms_callback?>' /-->
<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
<input type='hidden' name='pf_yid_ori' id='pf_yid_ori' value='<?=$spf->spf_friend?>' />
<input type='hidden' name='hidden_cont' id='hidden_cont' value='<?=$tpl->tpl_contents?>' />
<input type='hidden' name='tpl_button' id='tpl_button' value='<?=$tpl->tpl_button?>' />
<input type='hidden' name='uplo' id='tpl_button' value='<?=$tpl->tpl_button?>' />
<!-- <input type='hidden' name='psd_code' id='psd_code' value='<?=$psd_code?>' />
<input type='hidden' name='home_code' id='home_code' value='<?=$home_code?>' /> -->
<input type='hidden' name='tmp_flag' id='tmp_flag' value='<?=$param['tmp_flag']?>' />
<div id="mArticle">
    <div style="position:absolute; top:110px; right:20px;">
        <img src="/images/shome_tip.jpg" style="width:550px;">
    </div>
	<div class="form_section">
		<div class="inner_tit">
			<h3>알림톡 작성하기</h3>

			<span class="fr btn_guide"><a href="/manual/send"><span class="material-icons">pageview</span> 동영상 가이드 바로가기</a></span>
		</div>
		<div class="inner_content preview_info">
			<div id="send_template_content" class="preview_info">
			</div>
			<div class="input_content_wrap">
				<label class="input_tit">
					<p class="txt_st_eng">STEP 5.</p>
					<p>수신고객 선택</p>
				</label>
				<div id="reception_list_parent" class="input_content">
					<div id="reception_list" class="checks">
						<?php // 2019-07-09 친구여부 추가및 명칭변경 ?>
						<!-- input type="radio" name="customer" value="upload" id="switch_upload" checked /><label for="switch_upload">파일업로드로 전송</label>
						<input type="radio" name="customer" value="db" id="switch_select" /><label for="switch_select" style="margin-left: -1px;">그룹별 선택</label>
						<input type="radio" name="customer" value="private" id="switch_customer" /><label for="switch_customer" style="margin-left: -1px;">연락처 직접 입력</label-->
						<? $mem_bigpos_yn = $this->member->item("mem_bigpos_yn"); //빅포스 연동 2021-02-03 ?>
						<? //$mem_bigpos_yn = "N"; //빅포스 연동 ?>
                        <? if (empty($fail_list)){?>
						<? if($mem_bigpos_yn == "Y"){ //빅포스 연동 2021-02-03 ?>
							<input type="radio" name="customer" value="pos" id="switch_pos" checked><label for="switch_pos">포스고객</label>
							<input type="radio" name="customer" value="db" id="switch_select"><label for="switch_select">그룹선택</label>
						<? }else{ ?>
							<input type="radio" name="customer" value="db" id="switch_select" checked><label for="switch_select">그룹선택</label>
						<? } ?>
						<input type="radio" name="customer" value="upload" id="switch_upload"><label for="switch_upload">파일업로드</label>
                    <? } else {
                        $fail_flag = true;
                        echo "<input type='hidden' id='fail_flag' value='" . $mid . "'>";
                        echo "<input type='hidden' id='fail_mtype' value='" . $mtype . "'>";
                        echo "<input type='hidden' id='fail_uid' value='" . $uid . "'>";
                        }
                    ?>
						<input type="radio" name="customer" value="private" id="switch_customer"><label for="switch_customer">직접입력</label>
						<!--<input type="radio" name="customer" value="fr" id="switch_friend"><label for="switch_friend" style="margin-left: -6px;margin-right: 0px;">플친여부</label>-->
						<div class="clearfix" style="margin-bottom: 10px;"></div>
						<script type="text/javascript">
							//수신고객 체크의 경우
							$("[name=customer]").click(function(){
								//alert("this.value");
								if($(this).prop("checked")){ //포스고객
									//alert("this.value : "+ this.value);
									var chk_data = this.value;
									if(chk_data == "pos"){
										$("#pos").show();
									}else{
										$("#pos").hide();
										if(chk_data == "private"){ //직접입력
											$("#add-todo-input").focus(); //연락처 포커스
										}
									}
								}
							});
						</script>
						<div id="pos" style="display:<?=($mem_bigpos_yn == "Y") ? "" : "none"?>;">
							<div class="msg_box form_check">
								<div class="send_list">
									<ul>
										<li class="check_all"><input type="checkbox" name="posAll" id="posAll" checked disabled><label for="posAll">포스고객목록 <span class="send_amount">(<?=$pos_user_cnt?>명)</span></label></li>
									</ul>
								</div>
								<div class="bottom tr">
									총 발송 <span class="num" id="posSendTotal"><?=number_format($pos_user_cnt)?></span>건
								</div>
							</div>
						</div>
						<div id="upload">
							<div class="msg_box">
								<div class="btn_group" style="padding: 10px; text-align: center; border-bottom: 1px solid #dedede;">
									<button class="btn md excel" onclick="download();">엑셀양식 다운로드</button>
									<!-- <button class="btn md txt">TXT양식 다운로드</button> -->
								</div>
								<div class="send_list">
									<label class="file" title=""><input type="file" name="filecount" id="filecount" accept=".xls, .xlsx, .txt" multiple="multiple" onchange="this.parentNode.setAttribute('title', this.value.replace(/^.*[\\/]/, ''));readURL(this);" /></label>
								</div>
								<div class="bottom tr">
									총 발송 <span class="num" id="upload_tel_count">0</span>건<span style="display:none;"> (에러 : <span class="num" id="upload_tel_error_count">0</span>건)</span>
								</div>
							</div>
						</div>
						<div id="db">
							<div class="msg_box form_check">
								<div class="send_list" style="display:none;">
									<ul>
									<li class="check_all"><input type="checkbox" name="checkAll" id="all"><label for="all">전체 선택</label></li>
									<?
										$kindCount = 1;
										foreach($kind as $r) {
									?>
									<li><input type="checkbox" name="checkOne" id="group<?=$kindCount ?>" value="<?=($r->ab_kind == "") ? 'NONE' : $r->ab_kind ?>"><label for="group<?=$kindCount ?>"><?= $r->ab_kind == '' ? '구분없음' : $r->ab_kind ?></label><span class="send_amount">(<?=$r->ab_kind_cnt ?>명)</span></li>
									<?
											$kindCount += 1;
										}
									?>
									</ul>
								</div>
								<div class="send_list" style="display:none;">
									<select name="groupId" id="groupId" style="width:100%;" onChange="selGroupSendTotal();">
									  <option value="all§§<?=$customer_cnt?>">전체 선택 (<?=$customer_cnt?>명)</option>
									  <? foreach($customer_group as $r) { ?>
									  <option value="<?=$r->cg_id?>§§<?=$r->cg_cnt?>"><?=($r->cg_level==2) ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ ' : '&nbsp;&nbsp;'?><?=$r->cg_gname?> (<?=$r->cg_cnt?>명)</option>
									  <? } ?>
									</select>
								</div>
								<div class="send_list">
									<ul>
										<li class="check_all"><input type="checkbox" name="groupAll" id="groupAll" checked><label for="groupAll">전체고객목록 <span class="send_amount">(<?=$customer_cnt?>명)</span></label></li>
										<? foreach($customer_group as $r) { ?>
										<li><input type="checkbox" name="groupChk" id="group<?=$r->cg_id?>" value="<?=$r->cg_id?>" checked><label for="group<?=$r->cg_id?>"><?=$r->cg_gname?> <span class="send_amount">(<?=$r->cg_cnt?>명)</span></label></li>
										<? } ?>
									</ul>
								</div>
								<div class="bottom tr">
									총 발송 <span class="num" id="groupSendTotal"><?=number_format($customer_cnt)?></span>건
								</div>
							</div>
							<!-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>-->
							<script type="text/javascript">
							//전체고객목록 체크의 경우
							$("[name=groupAll]").click(function(){
								if($(this).prop("checked")){
									var temp = $(this).parents("li");
									var spans = $(temp).find("span");
									var sendCount = Number($(spans).html().replace("(", "").replace("명)", ""));
									if (sendCount > 60000) {
										alert("최대 60,000명까지 가능합니다.");
										return;
									}
									$("#groupSendTotal").text(sendCount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")); //총 발송 건수 표기 (콤마 추가)
									$('input:checkbox[name="groupChk"]').each(function(){
										$(this).prop("checked", true); //개별그룹 체크해제
									});
								}else{
                                    $('input:checkbox[name="groupChk"]').each(function(){
										$(this).prop("checked", false); //개별그룹 체크해제
									});
									$("#groupSendTotal").text("0"); //총 발송 건수 표기
								}
							});

                            var groupChkcnt = $('input:checkbox[name="groupChk"]').length;

							//개별그룹 체크의 경우
							$("[name=groupChk]").click(function(){
                                var groupChk_check = $('input:checkbox[name="groupChk"]:checked').length;
                                if(groupChk_check==groupChkcnt){
                                    if(groupChkcnt==1){
                                        $("[name=groupAll]").prop("checked", false); //전체고객목록 체크해제
                                    }else{
                                        $("[name=groupAll]").prop("checked", true); //전체고객목록 체크해제
                                    }
                                }else{
                                    $("[name=groupAll]").prop("checked", false); //전체고객목록 체크해제
                                }
								var groupSendTotal = 0;
                                var chkwherein = "";
								$('input:checkbox[name="groupChk"]').each(function(){
									if(this.checked) {
										//alert(this.value);
										var temp = $(this).parents("li");
										var spans = $(temp).find("span");
										var sendCount = $(spans).html().replace("(", "").replace("명)", "");
										if ((groupSendTotal + parseInt(sendCount)) > 60000) {
											alert("최대 60,000명까지 가능합니다.");
											return;
										}
										groupSendTotal += parseInt(sendCount);
                                        if(chkwherein==""){
                                            chkwherein = chkwherein + this.value;
                                        }else{
                                            chkwherein = chkwherein + ", " + this.value;
                                        }
									}
								});
                                // console.log(chkwherein);
                                var fullchk = "";
                                if(groupChk_check==groupChkcnt){
                                    if(groupChkcnt!=1){
                                        fullchk = "Y";
                                    }else{
                                        fullchk = "N";
                                    }
                                }else{
                                    fullchk = "N";
                                }
                                if(groupChk_check==0){
                                    $("#groupSendTotal").text("0");
                                }else{
                                    $.ajax({
                            			url: "/dhnbiz/sender/counttel/load_count_tel_list",
                            			type: "POST",
                            			data: {
                            				  "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                            				, "chkwherein" : chkwherein //선택 그룹
                                            , "fullchk" : fullchk //전체여부
                            			},
                            			success: function (json) {
                                            if(json.code=='0'){
                                                groupSendTotal = json.cnt;
                                                $("#groupSendTotal").text(groupSendTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")); //총 발송 건수 표기 (콤마 추가)
                                            }
                            			}
                            		});
                                }




                                // $("#groupSendTotal").text(groupSendTotal.replace(/\B(?=(\d{3})+(?!\d))/g, ",")); //총 발송 건수 표기 (콤마 추가)
							});

							//그룹 선택의 경우
							function selGroupSendTotal() {
								var groupSendTotal = 0;
								var groupId_sp = $("#groupId").val().split("§§");
								var groupId = groupId_sp[0]; //그룹번호
								var groupSendTotal = groupId_sp[1]; //인원수
								//alert("groupSendTotal 1 : "+ groupSendTotal);
								if (groupSendTotal > 60000) {
									alert("최대 60,000명까지 가능합니다.");
									return;
								}
								$("#groupSendTotal").text(groupSendTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")); //총 발송 건수 표기 (콤마 추가)
							}

							function allCheckFunc( obj ) {
								$("[name=checkOne]").prop("checked", $(obj).prop("checked") );
							}

							/* 체크박스 체크시 전체선택 체크 여부 */
							function oneCheckFunc( obj )
							{
								var allObj = $("[name=checkAll]");
								var objName = $(obj).attr("name");
								if( $(obj).prop("checked") )
								{
									checkBoxLength = $("[name="+ objName +"]").length;
									checkedLength = $("[name="+ objName +"]:checked").length;

									if( checkBoxLength == checkedLength ) {
										allObj.prop("checked", true);
									} else {
										allObj.prop("checked", false);
									}
								}
								else
								{
									allObj.prop("checked", false);
								}
							}

							function setGroupSendTotal() {
								var groupSendTotal = 0;
								$('input:checkbox[name="checkOne"]').each(function() {
									if(this.checked) {
										//alert(this.value);
										var temp = $(this).parents("li");
										var spans = $(temp).find("span");
										var sendCount = $(spans).html().replace("(", "").replace("명)", "");
										if ((groupSendTotal + parseInt(sendCount)) > 60000) {
											$(".content").html("최대 60,000명까지 가능합니다.");
											//$('#myModal').modal({backdrop: 'static'});
											this.checked = false;
											modal_open('myModal');
											return;
										}
										groupSendTotal += parseInt(sendCount);

									}
								 });
								$("#groupSendTotal").text(groupSendTotal);
							}

							$(function(){
								$("[name=checkAll]").click(function(){
									allCheckFunc( this );
									setGroupSendTotal();
								});
								$("[name=checkOne]").each(function(){
									$(this).click(function(){
										oneCheckFunc( $(this) );
										setGroupSendTotal();
									});
								});
							});
							</script>
						</div>
						<div id="private">
							<div class="msg_box">
								<div class="send_list">
									<ul class="num_list" id="num_list">
									</ul>
									<div class="send_num_add">
										<button class="btn md dark fr" id="add-todo-button">전화번호 추가</button>
										<div style="overflow: hidden;">
											<input type="text" id="add-todo-input" name="phoneNum" maxlength="13" style="width:100%; margin-right: -2px;" placeholder="연락처를 입력해 주세요">
										</div>
									</div>
								</div>
								<div class="bottom tr">
									총 발송 <span class="num" id="input_phone_count">0</span>건
								</div>
							</div>
						</div>
						<?php // 2019-07-09 친구여부 추가 ?>
						<div id="fr">
							<div class="msg_box">
								<div class="send_list">
									<ul>
										<li><input type="radio" name="friend_yn" value="FT" id="switch_FT" checked><label for="switch_FT" style="margin-left: -1px;">플러스 친구<span class="send_amount">(<?=$ftCount ?>명)</span></label></li>
										<li><input type="radio" name="friend_yn" value="NFT" id="switch_NFT"><label for="switch_NFT" style="margin-left: -1px;">플러스 친구 아님<span class="send_amount">(<?=$nftCount ?>명)</span></label></li>
									</ul>
								</div>
								<div class="bottom tr">
									총 발송 <span class="num" id="friend_yn_tel_count"><?=$ftCount ?></span>건
								</div>
							</div>
						</div>
						<script type="text/javascript">
							$(function(){
								$("[name=friend_yn]").each(function(){
									$(this).click(function(){
										$('input:radio[name="friend_yn"]').each(function() {
											if(this.checked) {
												//alert(this.value);
												var temp = $(this).parents("li");
												var spans = $(temp).find("span");
												var sendCount = $(spans).html().replace("(", "").replace("명)", "");

												if ((parseInt(sendCount)) > 60000) {
													$(".content").html("최대 60,000명까지 가능합니다.");
													//$('#myModal').modal({backdrop: 'static'});
													modal_open('myModal');
													return;
												}
												$("#friend_yn_tel_count").text(sendCount);
											}
										 });
									});
								});
							});
						</script>
						<?php // 2019-07-09 친구여부 추가 끝 ?>
					</div>
				</div>
			</div><!-- input_content_wrap END -->
			<div class="input_content_wrap">
				<label class="input_tit">예약발송</label>
				<div class="input_content">
					<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_reserve" class="trigger" id="reserve_check" onclick="ReserveCheck();"><i></i></label>
					<div class="switch_content" id="hidden_fields_reserve" style="display: none;">
						<div class="widget-content reserve_content">
							<div id="server_time" class="current_time"><?php echo date("Y-m-d H:i:s", time()); ?></div>
							<script>
							var srv_time = "<?php print date("F d, Y H:i:s", time()); ?>";
							var now = new Date(srv_time);
							setInterval("server_time()", 1000);
							function server_time()
							{
								now.setSeconds(now.getSeconds()+1);
								var year = now.getFullYear();
								var month = now.getMonth() + 1;
								var date = now.getDate();
								var hours = now.getHours();
								var minutes = now.getMinutes();
								var seconds = now.getSeconds();
								if (month < 10){
									month = "0" + month;
								}
								if (date < 10){
									date = "0" + date;
								}
								if (hours < 10){
									hours = "0" + hours;
								}
								if (minutes < 10){
									minutes = "0" + minutes;
								}
								if (seconds < 10){
									seconds = "0" + seconds;
								}
								document.getElementById("server_time").innerHTML = "<span class='realdate'>" + year + "년 " + month + "월 " + date + "일</span>" + "<span class='realtime'>" + hours + " : " + minutes + " : " + seconds + "</span>";
							}
							</script>
							<div class="datepicker_wrap">
								<div class='input-group date fl' id='datetimepicker' style="width: 135px;">
									<input type='text' class="form-control" id="reserve"/>
									<span class="input-group-addon"><span class="material-icons" style="font-size:18px;cursor:pointer;">date_range</span></span>
								</div>
								<select style="width:75px; margin-left:5px;" id="reserve_hours">
									<option value="0">00시</option>
									<option value="1">01시</option>
									<option value="2">02시</option>
									<option value="3">03시</option>
									<option value="4">04시</option>
									<option value="5">05시</option>
									<option value="6">06시</option>
									<option value="7">07시</option>
									<option value="8">08시</option>
									<option value="9">09시</option>
									<option value="10">10시</option>
									<option value="11">11시</option>
									<option value="12">12시</option>
									<option value="13">13시</option>
									<option value="14">14시</option>
									<option value="15">15시</option>
									<option value="16">16시</option>
									<option value="17">17시</option>
									<option value="18">18시</option>
									<option value="19">19시</option>
									<option value="20">20시</option>
									<option value="21">21시</option>
									<option value="22">22시</option>
									<option value="23">23시</option>
								</select>
								<select style="width:75px;" id="reserve_minutes">
									<option value="0">00분</option>
									<option value="5">05분</option>
									<option value="10">10분</option>
									<option value="15">15분</option>
									<option value="20">20분</option>
									<option value="25">25분</option>
									<option value="30">30분</option>
									<option value="35">35분</option>
									<option value="40">40분</option>
									<option value="45">45분</option>
									<option value="50">50분</option>
									<option value="55">55분</option>
								</select>
							</div>
							<script type="text/javascript">
								//예약발송 체크박스 체크
								function ReserveCheck() {
									if($("#reserve_check").is(":checked") == true) {
										//$('#datetimepicker').datepicker();
										$('#datetimepicker').datepicker({
											format:"yyyy-mm-dd",
											todayHighlight: true,
											language: "kr",
											startDate: "-0d",
											endDate: "+1m",
											autoclose: true
										}).datepicker("setDate", "now");
										var default_date_str = moment().add(15,'minutes');
										var default_date = new Date(default_date_str);
										$("#reserve_hours").val(default_date.getHours());
										//alert(default_date.getMinutes());
										var minutes = default_date.getMinutes();
										if (minutes > 0 && minutes <= 5) {
											minutes = 0;
										} else if (minutes > 5 && minutes <= 10) {
											minutes = 5;
										} else if (minutes > 10 && minutes <= 15) {
											minutes = 10;
										} else if (minutes > 15 && minutes <= 20) {
											minutes = 15;
										} else if (minutes > 20 && minutes <= 25) {
											minutes = 20;
										} else if (minutes > 25 && minutes <= 30) {
											minutes = 25;
										} else if (minutes > 30 && minutes <= 35) {
											minutes = 30;
										} else if (minutes > 35 && minutes <= 40) {
											minutes = 35;
										} else if (minutes > 40 && minutes <= 45) {
											minutes = 40;
										} else if (minutes > 45 && minutes <= 50) {
											minutes = 45;
										} else if (minutes > 50 && minutes <= 55) {
											minutes = 50;
										} else if (minutes > 55 && minutes <= 60) {
											minutes = 55;
										}
										$("#reserve_minutes").val(minutes);
									}
								}
							</script>
						</div>
					</div>
				</div>
			</div><!-- input_content_wrap END -->
			<div class="input_content_wrap">
				<label class="input_tit">테스트 발송</label>
				<div class="input_content">
					<div class="msg_box">
						<div class="send_list">
							<ul class="num_list" id="test_num_list">
							</ul>
							<div class="test_send_num_add">
								<button class="btn md yellow fr" id="btn-test-send" style="margin-left: 5px;" onclick="test_send();">테스트 발송</button>
								<button class="btn md dark fr" id="test_add-todo-button">전화번호 추가</button>
								<div style="overflow: hidden;">
									<input type="text" id="test_add-todo-input" name="phoneNum" maxlength="13" style="margin-right: -1px;" placeholder="테스트 발송 연락처를 입력해 주세요">
								</div>
							</div>
						</div>
						<? if($isManager == 'Y') { ?>
						<div class="bottom">
							<button class="btn md dark fr" onclick="testManager_onAddBtnClick()">번호추가</button>
							<input type="text" id="test_manager_add-todo-nickname" name="nickname" maxlength="50" value="<?=$mem->mem_nickname?>" style="width: 80px;">
							<input type="text" id="test_manager_add-todo-input" name="phoneNum" maxlength="13" value="<?=$mem->mem_emp_phone?>" style="width: 120px;">
							<a href="javascript:manager_phone_modify()" class="btn_st_sm">수정</a>
						</div>
						<? } ?>
					</div>
				</div>
			</div>
		</div><!-- inner_content END -->
	</div><!-- form_section END -->
	<div class="btn_send_cen">
		<!-- button class="btn lg">취소</button-->
		<button class="btn_send"  onclick="all_send();">알림톡발송</button>
	</div>
</div><!-- mArticle END -->
<!-- input 숫자만 입력가능/자동 하이픈 -->
<script type="text/javascript">
	var get_buttons = '<?=$tpl->tpl_button?>';
	var get_btn = get_buttons.replace(/&quot;/gi, "\"");
	var get_btn_content = JSON.parse(get_btn);
	var get_btn_cnt = 0;
	for (var i = 0; i < get_btn_content.length; i++) {
		get_btn_cnt++;
	}
	//alert("get_btn_cnt : "+ get_btn_cnt);

	function autoHypenPhone(str){
		str = str.replace(/[^0-9]/g, '');
		var tmp = '';
		if( str.length < 4){
			return str;
		}else if(str.length < 7){
			tmp += str.substr(0, 3);
			tmp += '-';
			tmp += str.substr(3);
			return tmp;
		}else if(str.length == 8){
			tmp += str.substr(0, 4);
			tmp += '-';
			tmp += str.substr(4);
			return tmp;
		}else if(str.length < 11){
			tmp += str.substr(0, 3);
			tmp += '-';
			tmp += str.substr(3, 3);
			tmp += '-';
			tmp += str.substr(6);
			return tmp;
		}else{
			tmp += str.substr(0, 3);
			tmp += '-';
			tmp += str.substr(3, 4);
			tmp += '-';
			tmp += str.substr(7);
			return tmp;
		}
		return str;
	}

	var phoneNum1 = document.getElementById('add-todo-input');
	phoneNum1.onkeyup = function(event){
			event = event || window.event;
			var _val = this.value.trim();
			this.value = autoHypenPhone(_val);
	}

	var phoneNum2 = document.getElementById('test_add-todo-input');
	phoneNum2.onkeyup = function(event){
			event = event || window.event;
			var _val = this.value.trim();
			this.value = autoHypenPhone(_val);
	}
</script>
<!-- 전화번호 추가/삭제 스크립트 -->
<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script> -->
<!-- 첫번째 -->
<script >
function onAddBtnClick() {
    // get the current value of .add-todo-input
    let newTodoText = $('#add-todo-input').val();

    // get the current value of .add-todo-input
    if (newTodoText === '') {
    	return;
    }

    // add a new todo with the text of .add-todo-input
    $('#num_list').append('<li class="todo-list-item"><span name="input_phone_no">' + newTodoText + '</span><button class="btn sm del fr" id="num_del">삭제</button></li>');
    // empty the .add-todo-input
    $('#add-todo-input').val('');



    var phoneCount = parseInt($("#input_phone_count").text());
    //alert(phoneCount);
    $("#input_phone_count").text(phoneCount + 1);
}

// adding a new todo
$('#add-todo-button').click(onAddBtnClick);
$('#add-todo-input').keypress(function (event) {
    if (event.which === 13) {
    	onAddBtnClick();
    }
});

// $('.todo-list-item').remove()
$('#num_list').on(
    'click',
    '#num_del',
    function () {
      $(this).parent().remove();
      var phoneCount = parseInt($("#input_phone_count").text());
      //alert(phoneCount);
      $("#input_phone_count").text(phoneCount - 1);
});
</script>

<!-- 두번째 -->
<script >
function testManager_onAddBtnClick() {
    // get the current value of .add-todo-input
    let newTodoText = $('#test_manager_add-todo-input').val();

    // get the current value of .add-todo-input
    if (newTodoText === '') {
    return;
    }

    newTodoText = autoHypenPhone(newTodoText);

    // add a new todo with the text of .add-todo-input
    $('#test_num_list').append('<li class="test_todo-list-item"><span name="test_input_phone_no">' + newTodoText + '</span><button class="btn sm del fr" id="num_del">삭제</button></li>');
    // empty the .add-todo-input
    $('#test_add-todo-input').val('');
}

function test_onAddBtnClick() {
    // get the current value of .add-todo-input
    let newTodoText = $('#test_add-todo-input').val();

    // get the current value of .add-todo-input
    if (newTodoText === '') {
    return;
    }

    // add a new todo with the text of .add-todo-input
    $('#test_num_list').append('<li class="test_todo-list-item"><span name="test_input_phone_no">' + newTodoText + '</span><button class="btn sm del fr" id="num_del">삭제</button></li>');
    // empty the .add-todo-input
    $('#test_add-todo-input').val('');
}

// adding a new todo
$('#test_add-todo-button').click(test_onAddBtnClick);
$('#test_add-todo-input').keypress(function (event) {
    if (event.which === 13) {
    test_onAddBtnClick();
    }
});

// $('.todo-list-item').remove()
$('#test_num_list').on(
    'click',
    '#num_del',
    function () {
      $(this).parent().remove();
});
</script>
<!-- /form-->
<div id="sendbox_select" class="dh_modal">
	<div class="modal-dialog modal-lg sendbox-dialog" id="modal">
		<div class="modal-content">
			<h4 class="modal-title" align="center">내 문자함 (알림톡)</h4>
			<div class="modal-new select-body">
				<div class="clear sendbox-tab_wrap">
					 <ul class="sendbox-tab">
						<li class="on"><a href="javascript:void(bind_sendbox('history'))">보낸문자</a></li>
						<li><a href="javascript:void(bind_sendbox('save'))">저장문자</a></li>
					 </ul>
					 <div style="float:right;">
						 <select class="select2 input-width-medium" id="searchType_sendbox" onchange="getSelectValue(this.form);">
							<option value="subject">문자제목</option>
							<option value="content">문자내용</option>
						 </select>
						 <input type="text" class="form-control input-width-medium inline"
								  id="searchFor_sendbox" name="search" placeholder="검색어 입력" value=""/>
						 <input type="button" class="btn btn-default" id="check" value="조회" onclick="location.href='javascript:search_sendbox();'"/>
					 </div>
				</div>
				<div class="widget-content" id="sendbox_list"></div>
				<div class="btn_wrap">
					<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
					<button type="button" class="btn btn-primary" id="code" name="code" onclick="select_sendbox();">확인</button>
					<br/><br/>
				</div>
			</div>
		</div>
	</div>
</div>
<!--템플릿 선택-->
<div id="template_danger" class="dh_modal">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
			<span id="close_template_danger" class="dh_close">&times;</span>
			<div class="modal-tit">템플릿 선택하기</div>
			<div class="modal_body">
				<div class="content">
					<div class="row align-center" id="danger">
						<p class="alert alert-danger">
							승인된 템플릿이 없습니다. 템플릿을 등록해주세요.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="template_select" class="dh_modal">
	<div class="modal-dialog modal-lg select-dialog" id="modal" data-keyboard="false" data-backdrop="static">
		<div class="modal-content alim_templet">
			<span id="close_template_select" class="dh_close">&times;</span>
			<div class="widget-content" id="template_list"></div>
		</div>
	</div>
</div>

<div id="myModal" class="dh_modal">
	<div class="modal-dialog width_400" id="modal">
		<div class="modal-content">
			<span id="close_myModal" class="dh_close">&times;</span>
			<div class="modal-new icon_alarm">
				<div class="content identify"></div>
				<div class="modal_bottom">
					<button type="button" class="btn md enter" onClick="modal_close('myModal');">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="myModalCheck" class="dh_modal">
	<div class="modal-dialog" id="modalCheck">
		<div class="modal-content">
			<span id="close_myModalCheck" class="dh_close">&times;</span>
			<div class="modal-tit">
				템플릿 선택하기
			</div>
			<div class="modal-new">
				<div class="content"></div>
				<div class="modal_bottom">
					<button type="button" class="btn md submit">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="myModalSelect" class="dh_modal">
	<div class="modal-dialog" id="modalCheck">
		<div class="modal-content select_send">
			<span id="close_myModalSelect" class="dh_close">&times;</span>
			<div class="modal-tit">
				템플릿 선택하기
			</div>
			<div class="modal-new">
				<div class="content"></div>
				<div class="modal_bottom">
					<button type="button" class="btn md select-send" onclick="select_move();">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 발송후 발송 내역 -->
<div id="myModalAll" class="dh_modal">
	<div class="modal-dialog" id="modalCheck">
		<div class="modal-content">
			<span id="close_myModalAll" class="dh_close">&times;</span>
			<div class="modal-tit">발송정보</div>
			<div class="modal-new">
				<div class="content"></div>
				<div class="modal_bottom">
					<button type="button" class="btn md cancel" onClick="modal_close('myModalAll');eaSendBtn();">취소</button>
					<button type="button" class="btn md all" onclick="modal_close('myModalAll');all_move();">확인</button>
				</div>
			</div>

		</div>
	</div>
</div>
<div id="myModalTest" class="dh_modal">
	<div class="modal-dialog" id="modalCheck">
		<div class="modal-content">
			<span id="close_myModalTest" class="dh_close">&times;</span>
			<div class="modal-tit">테스트 발송</div>
			<div class="modal-new">
				<div class="content"></div>
				<div class="modal_bottom">
					<button type="button" class="btn md cancel" onClick="modal_close('myModalTest');">취소</button>
					<button type="button" class="btn md test-send" onclick="modal_close('myModalTest');test_move();">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="myModalDel" class="dh_modal">
	<div class="modal-dialog" id="modalCheck">
		<div class="modal-content select_send">
			<span id="close_myModalDel" class="dh_close">&times;</span>
			<div class="modal-tit">
				템플릿 선택하기
			</div>
			<div class="modal-new">
				<div class="content"></div>
				<div class="modal_bottom">
					<button type="button" class="btn md del">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="myModalSelDel" class="dh_modal">
	<div class="modal-dialog" id="modalCheck">
		<div class="modal-content select_send">
			<span id="close_myModalSelDel" class="dh_close">&times;</span>
			<div class="modal-tit">
				템플릿 선택하기
			</div>
			<div class="modal-new">
				<div class="content"></div>
				<div class="modal_bottom">
					<button type="button" class="btn md selDel">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="myModalTemp" class="dh_modal">
	<div class="modal-dialog" id="modalCheck">
		<div class="modal-content select_send">
			<span id="close_myModalTemp" class="dh_close">&times;</span>
			<div class="modal-tit">
				템플릿 선택하기
			</div>
			<div class="modal-new">
				<div class="content"></div>
				<div class="modal_bottom">
					<button type="button" class="btn md temp">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="myModalUpload" class="dh_modal">
	<div class="modal-dialog" id="modalCheck">
		<div class="modal-content select_send">
			<span id="close_myModalUpload" class="dh_close">&times;</span>
			<div class="modal-tit">
				템플릿 선택하기
			</div>
			<div class="modal-new">
				<div class="content"></div>
				<div>
					<button type="button" class="btn md up" onClick="check();">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="myModalDownload" class="dh_modal">
	<div class="modal-dialog" id="modalCheck">
		<div class="modal-content select_send">
			<span id="close_myModalDownload" class="dh_close">&times;</span>
			<div class="modal-tit">
				템플릿 선택하기
			</div>
			<div class="modal-new">
				<div class="content"></div>
				<div class="modal_bottom">
					<button type="button" class="btn md down">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="myModalFilterAll" class="dh_modal">
	<div class="modal-dialog modal-lg" id="modalCheck">
		<div class="modal-content select_send">
			<span id="close_myModalFilterAll" class="dh_close">&times;</span>
			<div class="modal-tit">
				템플릿 선택하기
			</div>
			<div class="modal-new">
				<div class="content">
				</div>
				<div>
						<button type="button" class="btn md modal" data-dismiss="modal">선택추가</button>
						<button type="button" class="btn md filter_all">전체추가</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="myModalUserMSGList" class="dh_modal">
	<div class="modal-dialog modal-lg">
			<div class="modal-content alim_templet">
				<span id="close_myModalUserMSGList" class="dh_close">&times;</span>
						<!--div style="padding-bottom:20px;">
							<select class="select2 input-width-medium" id="searchKind">
									<option value="all">전체</option>
									<option value="FT">친구</option>
									<option value="SMS">SMS</option>
									<option value="LMS">LMS</option>
									<option value="MMS">MMS</option>
							</select>
							<input type="text" class="form-control input-width-medium inline" id="searchMsg" name="searchMsg" placeholder="메시지" value=""/>
							<input type="button" class="btn btn-default" id="check" value="조회" onclick="search_msg(1)"/>
						</div-->
						<div class="content" id="modal_user_msg_list"></div><? //views/biz/dhnsender/user_msg_list.php 에서 가져옮 ?>
						<div class="tc" style="display:none;">
							<button type="button" class="btn md btn-primary include_phns modal_del" name="code" onclick="delete_msg()">선택 삭제</button>
							<button type="button" class="btn md btn-default modal_cancel dismiss" data-dismiss="modal" onclick="cancelClick();">취소</button>
							<button type="button" class="btn md btn-primary include_phns" name="code" onclick="include_msg()">선택 추가</button>
						</div>
					</div>
			</div>
	</div>

    <div id="myModalUserEditorList" class="dh_modal">
    	<div class="modal-dialog modal-lg">
    			<div class="modal-content alim_templet">
    				<span id="close_myModalUserEditorList" class="dh_close">&times;</span>
    						<!--div style="padding-bottom:20px;">
    							<select class="select2 input-width-medium" id="searchKind">
    									<option value="all">전체</option>
    									<option value="FT">친구</option>
    									<option value="SMS">SMS</option>
    									<option value="LMS">LMS</option>
    									<option value="MMS">MMS</option>
    							</select>
    							<input type="text" class="form-control input-width-medium inline" id="searchMsg" name="searchMsg" placeholder="메시지" value=""/>
    							<input type="button" class="btn btn-default" id="check" value="조회" onclick="search_msg(1)"/>
    						</div-->
    						<div class="content" id="modal_user_msg_list"></div><? //views/biz/dhnsender/user_msg_list.php 에서 가져옮 ?>
    						<div class="tc" style="display:none;">
    							<button type="button" class="btn md btn-default modal_cancel dismiss" data-dismiss="modal" onclick="cancelClick();">취소</button>
    							<button type="button" class="btn md btn-primary include_phns" name="code" onclick="include_msg()">선택 추가</button>
    						</div>
    					</div>
    			</div>
    	</div>
</div>
<div id="myModalLoadCustomers" class="dh_modal">
	<div class="modal-dialog select-dialog">
		<div class="modal-content select_send">
			<i class="material-icons modal_close" data-dismiss="modal">close</i>
			<div class="modal-tit">
				고객정보 가져오기
			</div>
			<div class="modal-new select-body" style="height: 600px;">

				<div class="mb20 mt10">
					<div style="padding-bottom:20px;">
						<select class="select2 input-width-medium" id="searchType">
							<option value="all">전체</option>
							<option value="normal">정상</option>
							<option value="reject">수신거부</option>
						</select>&nbsp;
						<input type="text" class="form-control input-width-medium inline" id="searchStr" name="searchStr" placeholder="전화번호 입력" value=""/>&nbsp;
						고객구분
						<select class="select2 input-width-medium" id="searchGroup" >
						<option value="">전체</option>
						<?foreach($kind as $r) {?>
						<option value="<?=$r->ab_kind?>"><?=$r->ab_kind?></option>
						<?}?>
							<!-- 2019.01.17. 이수환 "고객구분없음" 추가 -->
							<option value="NONE">고객구분없음</option>
							<option value="FT">친구등록</option>
							<option value="NFT">친구등록안됨</option>
						</select>&nbsp;

						<input type="text" class="form-control input-width-medium inline"
							   id="searchName" name="searchName" placeholder="고객명 입력" value=""/>&nbsp;
						<input type="button" class="btn btn-default" id="check" value="조회" onclick="search_question(1)"/>
					</div>

					<div class="widget-content id='modal_customer_list" style="overflow-y:scroll; height: 440px;" style="border:1px solid #aaa;">
								<div align="left" style="overflow-y:scroll; height: 380px;">
									<table class="table table-hover table-striped table-bordered table-highlight-head table-checkable">
										 <!-- 추가수정 수신거부, 메모 기능(넓이 수정/추가) -->
										 <colgroup>
											  <col width="10%">
											  <col width="20%">
											  <col width="15%">
											  <col width="15%">
											  <col width="30%">
											  <col width="10%">
										 </colgroup>
										 <thead>
										 <tr p style="cursor:default">
											  <th class="checkbox-column" style="width:10% !important"><input type="checkbox" name="sel_all" id="sel_all" class="uniform"></th>
											  <th class="text-center">등록일</th>
											  <th class="text-center">전화번호</th>
											  <th class="text-center">상태</th>
											  <!-- 추가수정 수신거부, 메모 기능(메모,수정 타이틀 추가) -->
											  <th class="text-center">메모</th>
										 </tr>
										 </thead>
										 <tbody>
											<!-- 고객 목록 표시 -->
										 </tbody>
									</table>
								</div>
								<style>
								.text-center { vertical-align: middle !important; }
								</style>
								<script type="text/javascript">
								$( document ).ready(function() {
									//$('tr input').uniform();
								});

								$('tbody tr').click(function() {
									var check = $(this).find('.checker').children('span').is('.checked');
									if(check == true) {
										$(this).find('td input[type="checkbox"]').prop('checked', true);
										$(this).addClass('checked');
									} else {
										$(this).find('td input[type="checkbox"]').prop('checked', false);
										$(this).removeClass('checked');
									}
									$.uniform.update();
								});

								//수신번호 전체 선택 안되는 현상 수정
								$("#sel_all").click(function(){
									if($("#sel_all").prop("checked")) {
										$("input:checkbox[name='selCustomer']").prop("checked",true);
										$.uniform.update();
									} else {
										$("input:checkbox[name='selCustomer']").prop("checked",false);
										$.uniform.update();
									}
								});
								</script>
					</div>
				</div>
				<div class="modal_bottom">
					<br/>
					<button type="button" class="btn md btn-default dismiss" data-dismiss="modal">취소</button>
					<button type="button" class="btn md btn-primary include_phns" name="code" onclick="include_customer()">선택 추가</button>
					<button type="button" class="btn md btn-primary include_phns" name="code" onclick="include_customer('GS')">구분 전체추가</button>
					<br/><br/>
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
	.modal-open {
		overflow: hidden;
		position: fixed;
		width: 100%;
	}
</style>
<script type="text/javascript">
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

    function insert_char(n, textType){
        var adds = n ;
        var txtArea;

        if (!textType) textType = "alrim";
        if (textType === "alrim") {
        	txtArea = $('#templi_cont');
        } else {
        	txtArea = $('#lms');
        }

        var txtValue = txtArea.val();

        var selectPos = txtArea.prop('selectionStart');// 커서 위치 지정
        var beforeTxt = txtValue.substring(0, selectPos);  // 기존텍스트 ~ 커서시작점 까지의 문자
        var afterTxt = txtValue.substring(txtArea.prop('selectionEnd'), txtValue.length);   // 커서끝지점 ~ 기존텍스트 까지의 문자

        txtArea.val( beforeTxt + adds + afterTxt );

        selectPos = selectPos + adds.length;
        txtArea.prop({
            'selectionStart': selectPos,
            'selectionEnd': selectPos
        });

        txtArea.focus();

        if (textType === "alrim") {
        	view_preview();
        	//templi_chk();
        	//onPreviewText();
        } else {
			chkword_lms();
			onPreviewLms();
        }
     }

    <?/*--------------------------------------*
	   | 2차 발신 알림톡 가져오기 버튼 클릭시 |
	   *--------------------------------------*/?>
	function getArimtalkCont() {
		var dumyTempli_cont = $("#dumy_templi_cont").text();

		//alert("dumyTempli_cont : "+ dumyTempli_cont);
		if(dumyTempli_cont != ""){
			if(get_btn_cnt > 0){ //버튼이 있는 경우

				var btn_url = "\n";
				var newline = "\n";

				var maxnum = $("input[name=btn_url1]").length;
				for(i=0;i<maxnum;i++){
					if($('.btn_loop_'+i).val() !=''){



						var btn_name_temp = $('.btn_name_loop_'+i).val();
						btn_url += newline + btn_name_temp.replace(/\s/gi, "") + "\n" + $('.btn_loop_'+i).val(); //스마트쿠폰 링크 URL
					}
				}

				if(btn_url != ""){
					dumyTempli_cont += btn_url+"\n";
				}
			}
			dumyTempli_cont = dumyTempli_cont.replace(/<p> <\/p>/gi, '\n\n');
			// if(get_btn_cnt > 0){ //버튼이 있는 경우
			// 	var link_type = document.getElementById("link_type0").value; //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
			// 	var btn_url = "";
			// 	if(link_type == "editor"){ //에디터
			// 		btn_url = document.getElementById("dhnl_url").value; //에디터 링크 URL
			// 	}else if(link_type == "smart"){ //스마트전단
			// 		btn_url = document.getElementById("psd_url").value; //스마트전단 링크 URL
			// 	}
			// 	if(btn_url != ""){
			// 		dumyTempli_cont += "\n\n행사보기\n"+ btn_url;
			// 	}
			// }
		}
		//alert("dumyTempli_cont : "+ dumyTempli_cont);
		var temp = dumyTempli_cont.split("#{");
		//alert("temp.length : "+ temp.length); return;
		var temp2 = new Array();
		var returnTempli_cont = dumyTempli_cont;
		var var_name_length = $("textarea[name=var_name]").length;
		//alert("var_name_length : "+ var_name_length); return;
		for (var i = 0; i < temp.length; i++) {
			if (i == 0) {
				returnTempli_cont = temp[i];
			} else {
				var varsplit = temp[i].split("}")
				var varName = "#{" + varsplit[0] + "}";
				//alert("["+ i +"] varName : "+ varName);
				//var var_name = $("input[name=var_name]")[(i-1)].value;
				var var_name = $("textarea[name=var_name]")[(i-1)].value;
				//alert("["+ i +"] var_name : "+ var_name);
				if (var_name == "") {
					var_name = varName;
				}
				returnTempli_cont = returnTempli_cont + var_name;
				returnTempli_cont = returnTempli_cont + varsplit[1];
			}
		}
        returnTempli_cont = returnTempli_cont.replace("#{회원정보 등록일}", "행사 기간");
        returnTempli_cont = returnTempli_cont.replace("#{변수내용}", "행사 기간");
        returnTempli_cont = returnTempli_cont.replace("#{날짜}", "행사 날짜");
        returnTempli_cont = returnTempli_cont.replace("#{00}", "00");
		var cont = returnTempli_cont.replace(/\n\n\* 수신을 원치 않으시면, 상단 '알림톡차단' 버튼을 눌러주세요!/gi, ''); //2020-12-11 추가
    	$("#lms").val(cont);
    	chkword_lms();
    	onPreviewLms();
    }

		function getLinkCont() {
			var dumyTempli_cont = '';
			if(get_btn_cnt > 0){ //버튼이 있는 경우

				var btn_url = "";
				var newline = "";

				var maxnum = $("input[name=btn_url1]").length;
				for(i=0;i<maxnum;i++){
					if($('.btn_loop_'+i).val() !=''){
						if(btn_url!=''){
							newline = "\n";
						}
						var btn_name_temp = $('.btn_name_loop_'+i).val();
						btn_url += newline + btn_name_temp.replace(/\s/gi, "") + "\n" + $('.btn_loop_'+i).val(); //스마트쿠폰 링크 URL
					}
				}

				if(btn_url != ""){
					dumyTempli_cont = btn_url;
				}
			}
			// }
			// //alert("dumyTempli_cont : "+ dumyTempli_cont);
			// var temp = dumyTempli_cont.split("#{");
			// //alert("temp.length : "+ temp.length); return;
			// var temp2 = new Array();
			// var returnTempli_cont = dumyTempli_cont;
			// var var_name_length = $("textarea[name=var_name]").length;
			// //alert("var_name_length : "+ var_name_length); return;
			// for (var i = 0; i < temp.length; i++) {
			// 	if (i == 0) {
			// 		returnTempli_cont = temp[i];
			// 	} else {
			// 		var varsplit = temp[i].split("}")
			// 		var varName = "#{" + varsplit[0] + "}";
			// 		//alert("["+ i +"] varName : "+ varName);
			// 		//var var_name = $("input[name=var_name]")[(i-1)].value;
			// 		var var_name = $("textarea[name=var_name]")[(i-1)].value;
			// 		//alert("["+ i +"] var_name : "+ var_name);
			// 		if (var_name == "") {
			// 			var_name = varName;
			// 		}
			// 		returnTempli_cont = returnTempli_cont + var_name;
			// 		returnTempli_cont = returnTempli_cont + varsplit[1];
			// 	}
			// }
			// var cont = returnTempli_cont.replace(/\n\n\* 수신을 원치 않으시면, 상단 '알림톡차단' 버튼을 눌러주세요!/gi, ''); //2020-12-11 추가
	    	$("#lms").val(dumyTempli_cont);
	    	chkword_lms();
	    	onPreviewLms();
	    }

	function replaceKakaoEmoticon(msg_text, emoticonSize)
	{
		if (!emoticonSize) emoticonSize = 30;
		msg_text = msg_text.replace(/\(미소\)/g, "<img src=\"/images/kakaoimg/kakaoimg_001.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(윙크\)/g, "<img src=\"/images/kakaoimg/kakaoimg_002.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(방긋\)/g, "<img src=\"/images/kakaoimg/kakaoimg_003.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(반함\)/g, "<img src=\"/images/kakaoimg/kakaoimg_004.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(눈물\)/g, "<img src=\"/images/kakaoimg/kakaoimg_005.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(절규\)/g, "<img src=\"/images/kakaoimg/kakaoimg_006.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(크크\)/g, "<img src=\"/images/kakaoimg/kakaoimg_007.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(메롱\)/g, "<img src=\"/images/kakaoimg/kakaoimg_008.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(잘자\)/g, "<img src=\"/images/kakaoimg/kakaoimg_009.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(잘난척\)/g, "<img src=\"/images/kakaoimg/kakaoimg_010.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(헤롱\)/g, "<img src=\"/images/kakaoimg/kakaoimg_011.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(놀람\)/g, "<img src=\"/images/kakaoimg/kakaoimg_012.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(아픔\)/g, "<img src=\"/images/kakaoimg/kakaoimg_013.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(당황\)/g, "<img src=\"/images/kakaoimg/kakaoimg_014.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(풍선껌\)/g, "<img src=\"/images/kakaoimg/kakaoimg_015.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(버럭\)/g, "<img src=\"/images/kakaoimg/kakaoimg_016.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(부끄\)/g, "<img src=\"/images/kakaoimg/kakaoimg_017.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(궁금\)/g, "<img src=\"/images/kakaoimg/kakaoimg_018.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(흡족\)/g, "<img src=\"/images/kakaoimg/kakaoimg_019.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(깜찍\)/g, "<img src=\"/images/kakaoimg/kakaoimg_020.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(으으\)/g, "<img src=\"/images/kakaoimg/kakaoimg_021.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(민망\)/g, "<img src=\"/images/kakaoimg/kakaoimg_022.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(곤란\)/g, "<img src=\"/images/kakaoimg/kakaoimg_023.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(잠\)/g, "<img src=\"/images/kakaoimg/kakaoimg_024.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(행복\)/g, "<img src=\"/images/kakaoimg/kakaoimg_025.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(안도\)/g, "<img src=\"/images/kakaoimg/kakaoimg_026.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(우웩\)/g, "<img src=\"/images/kakaoimg/kakaoimg_027.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(외계인\)/g, "<img src=\"/images/kakaoimg/kakaoimg_028.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(외계인녀\)/g, "<img src=\"/images/kakaoimg/kakaoimg_029.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(공포\)/g, "<img src=\"/images/kakaoimg/kakaoimg_030.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(근심\)/g, "<img src=\"/images/kakaoimg/kakaoimg_031.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(악마\)/g, "<img src=\"/images/kakaoimg/kakaoimg_032.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(썩소\)/g, "<img src=\"/images/kakaoimg/kakaoimg_033.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(쳇\)/g, "<img src=\"/images/kakaoimg/kakaoimg_034.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(야호\)/g, "<img src=\"/images/kakaoimg/kakaoimg_035.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(좌절\)/g, "<img src=\"/images/kakaoimg/kakaoimg_036.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(삐침\)/g, "<img src=\"/images/kakaoimg/kakaoimg_037.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(하트\)/g, "<img src=\"/images/kakaoimg/kakaoimg_038.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(실연\)/g, "<img src=\"/images/kakaoimg/kakaoimg_039.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(별\)/g, "<img src=\"/images/kakaoimg/kakaoimg_040.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(브이\)/g, "<img src=\"/images/kakaoimg/kakaoimg_041.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(오케이\)/g, "<img src=\"/images/kakaoimg/kakaoimg_042.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(최고\)/g, "<img src=\"/images/kakaoimg/kakaoimg_043.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(최악\)/g, "<img src=\"/images/kakaoimg/kakaoimg_044.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(그만\)/g, "<img src=\"/images/kakaoimg/kakaoimg_045.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(땀\)/g, "<img src=\"/images/kakaoimg/kakaoimg_046.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(알약\)/g, "<img src=\"/images/kakaoimg/kakaoimg_047.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(밥\)/g, "<img src=\"/images/kakaoimg/kakaoimg_048.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(커피\)/g, "<img src=\"/images/kakaoimg/kakaoimg_049.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(맥주\)/g, "<img src=\"/images/kakaoimg/kakaoimg_050.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(소주\)/g, "<img src=\"/images/kakaoimg/kakaoimg_051.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(와인\)/g, "<img src=\"/images/kakaoimg/kakaoimg_052.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(치킨\)/g, "<img src=\"/images/kakaoimg/kakaoimg_053.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(축하\)/g, "<img src=\"/images/kakaoimg/kakaoimg_054.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(음표\)/g, "<img src=\"/images/kakaoimg/kakaoimg_055.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(선물\)/g, "<img src=\"/images/kakaoimg/kakaoimg_056.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(케이크\)/g, "<img src=\"/images/kakaoimg/kakaoimg_057.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(촛불\)/g, "<img src=\"/images/kakaoimg/kakaoimg_058.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(컵케이크a\)/g, "<img src=\"/images/kakaoimg/kakaoimg_059.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(컵케이크b\)/g, "<img src=\"/images/kakaoimg/kakaoimg_060.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(해\)/g, "<img src=\"/images/kakaoimg/kakaoimg_061.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(구름\)/g, "<img src=\"/images/kakaoimg/kakaoimg_062.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(비\)/g, "<img src=\"/images/kakaoimg/kakaoimg_063.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(눈\)/g, "<img src=\"/images/kakaoimg/kakaoimg_064.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(똥\)/g, "<img src=\"/images/kakaoimg/kakaoimg_065.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(근조\)/g, "<img src=\"/images/kakaoimg/kakaoimg_066.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(딸기\)/g, "<img src=\"/images/kakaoimg/kakaoimg_067.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(호박\)/g, "<img src=\"/images/kakaoimg/kakaoimg_068.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(입술\)/g, "<img src=\"/images/kakaoimg/kakaoimg_069.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(야옹\)/g, "<img src=\"/images/kakaoimg/kakaoimg_070.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(돈\)/g, "<img src=\"/images/kakaoimg/kakaoimg_071.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(담배\)/g, "<img src=\"/images/kakaoimg/kakaoimg_072.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(축구\)/g, "<img src=\"/images/kakaoimg/kakaoimg_073.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(야구\)/g, "<img src=\"/images/kakaoimg/kakaoimg_074.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(농구\)/g, "<img src=\"/images/kakaoimg/kakaoimg_075.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(당구\)/g, "<img src=\"/images/kakaoimg/kakaoimg_076.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(골프\)/g, "<img src=\"/images/kakaoimg/kakaoimg_077.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(카톡\)/g, "<img src=\"/images/kakaoimg/kakaoimg_078.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(꽃\)/g, "<img src=\"/images/kakaoimg/kakaoimg_079.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(총\)/g, "<img src=\"/images/kakaoimg/kakaoimg_080.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		msg_text = msg_text.replace(/\(크리스마스\)/g, "<img src=\"/images/kakaoimg/kakaoimg_081.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");
		msg_text = msg_text.replace(/\(콜\)/g, "<img src=\"/images/kakaoimg/kakaoimg_082.png\" width=\"" + emoticonSize + "\" height=\"" + emoticonSize + "\" />");

		return msg_text;
	}

	//알림톡 미리보기 - 템플릿내용
	function templi_preview(obj) {
		<?if($tpl) {?>
        var tpl_extra = $("#tpl_extra").val().replace(/\n/g, "<br>");
		var tpl_ad = $("#tpl_ad").val().replace(/\n/g, "<br>");
		if (tpl_extra != "" && tpl_extra!='NONE') {
			tpl_extra = "<br>" + tpl_extra;
		}else{
            tpl_extra = '';
        }
		if (tpl_ad != "" && tpl_ad!='NONE') {
			tpl_ad = "<br>" + tpl_ad;
		}else{
            tpl_ad = '';
        }
		var dumyTempli_cont = $("#dumy_templi_cont").text().replace(/\n/g, "<br>");
		var temp = dumyTempli_cont.split("#{");
		var temp2 = new Array();
		var returnTempli_cont = dumyTempli_cont;

		for (var i = 0; i < temp.length; i++) {
			if (i == 0) {
				returnTempli_cont = temp[i];
			} else {
				var varsplit = temp[i].split("}")
				var varName = "#{" + varsplit[0] + "}";

				//var var_name = $("input[name=var_name]")[(i-1)].value;
				var var_name = $("textarea[name^='var_name']")[(i-1)].value;
				// console.log(var_name);
				//alert(var_name);
				if (var_name == "") {
					//var_name = varName;
					if (varName == "#{변수내용}" || varName == "#{회원정보 등록일}") {
						var_name = "행사 기간";
					} else if (varName == "#{업체명}") {
						var_name = "업체명";
					} else if (varName == "#{업체전화번호}" || varName == "#{전화번호}") {
						var_name = "전화번호";
					} else if (varName == "#{날짜}") {
						var_name = "행사 날짜";
                    } else if (varName == "#{00}") {
						var_name = "00";
					} else {
						var_name = varName;
					}

				}
				returnTempli_cont = returnTempli_cont + var_name.replace(/(\n|\r\n)/g, '<br>');
				returnTempli_cont = returnTempli_cont + varsplit[1];
			}
		}
		//alert(returnTempli_cont);
        returnTempli_cont = returnTempli_cont + tpl_extra + tpl_ad;
		returnTempli_cont = replaceKakaoEmoticon(returnTempli_cont, 20);
		$("#text").html(returnTempli_cont);
		<?}?>
	}


	<? /*--------------------*
        |  2차 문자 미리보기 |
        *--------------------*/ ?>
	function onPreviewLms() {
		<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
		//var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var lms_header_temp = $("#span_adv").text() + $("#companyName").val();
		var lms_footer_temp = "";
		if($("#kakotalk_link_text").text() == "") {
			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		} else {
			lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		}
		//var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

		var msg_text = "";

		if ($("#lms").val().replace(/\n/g, "").length > 0) {
			msg_text = msg_text + lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
		} else {
			msg_text = msg_text + lms_header_temp + "\n\n메세지를 입력해주세요.\n\n" + lms_footer_temp;
		}

        // 2019.01.21 이수환 특수문자 제거 테스트
        //msg_text = msg_text.replace(/[\u2100-\u2120\u2123-\u214F\u2190-\u21FF\u0080-\u008F\uFE50-\uFE6F\u2460-\u24FF\u2300-\u259F\u25A2\u25A4-\u25A5\u25A7-\u25B1\u25B4-\u25B5\u25B8-\u25BB\u25BE-\u25BF\u25C2-\u25C5\u25C8-\u25CA\u25CC-\u25CD\u25D2-\u2604\u2607-\u260D\u260F-\u261D\u261F-\u263F\u2641\u2643-\u2660\u2662\u2664\u2666\u2668-\u2669\u266B\u266D-\u303F\u3100-\u32FF\u2150-\u2189\u2200-\u2298\u229A-\u22FF\u0080-\u00FF\u2000-\u203A\u203C-\u206F\uFF00-\uFF02\uFF04-\uFF05\uFF07-\uFF1F\uFF21-\uFFEF\u3130-\u318F\u0000-\u0009\u3300-\u33FF\u0400-\u04FF\u2070-\u209C\u2714]/gi, "");
        //msg_text = msg_text.replace(/[\u200B\u3000\u3164\uDB40\u272A]/gi, "");
        //msg_text = msg_text.replace(/[\u2027]/gi, " ");

		msg_text = msg_text.replace(/\n/g, "<br>");
		$("#lms_preview").html(msg_text);
	}

	//미리보기 - 버튼링크
	function link_preview() {
		<?if($tpl) {?>
		/*
		var buttons = '<?=$tpl->tpl_button?>';
		var btn = buttons.replace(/&quot;/gi, "\"");
		var btn_content = JSON.parse(btn);
		var previewText = $("#text").html();
		var strBtn = "";
		if(btn_content.length > 0){
			strBtn += "<div style=\"margin-top: 4px;\">";
			for (var i = 0; i < btn_content.length; i++) {
				strBtn += "<p class=\"tem_btn\">"+ btn_content[i]["name"] +"</p>";
			}
			strBtn += "</div>";
		}
		$("#text").html(previewText + strBtn);
		*/
		mobile_preview(); //모바일 미리보기 세팅
		<?}?>
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

	//수신번호 추가
	function number_add() {
		var lastItemNo = $("#tel tbody tr:last").attr("class");
		var no = parseInt(lastItemNo) + 1;
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		if ($("#templi_code").val() == null) {
			$(".content").html("<h4 class='modal_icon_alarm'>템플릿을 먼저 선택해주세요.</h4>");
			//$('#myModal').modal({backdrop: 'static'});
			modal_open('myModal');
			return false;
		} else if ($("input:checkbox[value='1']").is(":checked") == true) {
			if (row_index <= 100) {
				if (no == 2) {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input name="sel_del" id="' + no + '" class="uniform" value="' + no + '" type="checkbox"></td>' +
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
							'</tr>';
					$("." + 1).after(tr);
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#" + no).uniform();
				} else if (row_index >= 5) {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input class="uniform" name="sel_del" id="' + no + '" value="' + no + '" type="checkbox"></td>'+
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>'+
							'</tr>';
					$("." + lastItemNo).after(tr);
					$("#" + no).uniform();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "235px");
					$(this).css("overflow", "scroll");
					$("tr." + no).focus();
					$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
				} else {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input class="uniform" name="sel_del" id="' + no + '"  value="' + no + '" type="checkbox"></td>' +
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
							'</tr>';
					$("." + lastItemNo).after(tr);
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#" + no).uniform();
				}
			} else {
				$(".content").html("최대 100개까지 가능합니다.");
				//$("#myModal").modal({backdrop: 'static'});
				modal_open('myModal');
				return false;
			}
		} else {
			if (row_index <= 100) {
				if (row_index > 5) {
					var tr = '<tr class="' + no + '" name="' + no + '" id="tel_tbody_tr" name="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input type="checkbox" name="sel_del" id="' + no + '"  value="' + no + '" class="uniform"></td>' +
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
							'</tr>';
					$("." + lastItemNo).after(tr);
					$("#" + no).uniform();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "235px");
					$(this).css("overflow", "scroll");
					$("tr." + no).focus();
					$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
				} else {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input class="uniform" name="sel_del" id="' + no + '"  value="' + no + '" type="checkbox"></td>' +
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
							'</tr>';
					$("." + lastItemNo).after(tr);
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#" + no).uniform();
				}
			} else {
				$(".content").html("최대 100개까지 가능합니다.");
				//$("#myModal").modal({backdrop: 'static'});
				modal_open('myModal');
				return false;
			}
		}
	}

	//수신번호 개별삭제
	function tel_remove(obj) {
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		if ($("#templi_code").val() == null) {
			$(".content").html("<h4'>템플릿을 먼저 선택해주세요.</h4>");
			//$('#myModal').modal({backdrop: 'static'});
			modal_open('myModal');
			return false;
		} else if (row_index == 2) {
			var tel_number = $("#tel_number").val();
			if (tel_number == "") {
				$(".content").html("삭제할 수신 번호가 없습니다.");
				//$("#myModal").modal({backdrop: 'static'});
				//$(document).unbind("keyup").keyup(function (e) {
				//	var code = e.which;
				//	if (code == 13) {
				//		$(".enter").click();
				//	}
				//});
				modal_open('myModal');
				return false;
			} else {
				//$(".content").html("삭제하시겠습니까?");
				//$("#myModalDel").modal({backdrop: 'static'});
				//$("#myModalDel").unbind("keyup").keyup(function (e) {
				//	var code = e.which;
				//	if (code == 13) {
				//		tel_del(obj);
				//	}
				//});
				//$(".del").click(function () {
				//	tel_del(obj);
				//	$(".del").unbind("click");
				//});
				if(confirm("삭제 하시겠습니까?")){
					tel_del(obj);
				}
			}
		} else {
			//$(".content").html("삭제하시겠습니까?");
			//$("#myModalDel").modal({backdrop: 'static'});
			//$("#myModalDel").unbind("keyup").keyup(function (e) {
			//	var code = e.which;
			//	if (code == 13) {
			//		tel_del(obj);
			//	}
			//});
			//$(".del").click(function () {
			//	tel_del(obj);
			//	$(".del").unbind("click");
			//});
			if(confirm("삭제 하시겠습니까?")){
				tel_del(obj);
			}
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
			var tr = '<tr class="' + 1 + '" id="tel_tbody_tr">' +
					'<td class="checkbox-column" style="width:10% !important"><input class="uniform" name="sel_del" id="'+ 1 +'"  value="' + 1 + '" type="checkbox"></td>' +
					'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
					'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
					'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
					'</tr>';
			$("#tel_tbody").html(tr);
			var height = $("#tel_tbody").prop("scrollHeight");
			$("#tel_tbody").css("height", "47px");
			$("#1").uniform();
		}
	}

	//수신번호 선택삭제
	function selectDelRow() {
		if ($("#templi_code").val() == null) {
			$(".content").html("<h4>템플릿을 먼저 선택해주세요.</h4>");
			//$('#myModal').modal({backdrop: 'static'});
			modal_open('myModal');
			return false;
		} else if ($("input:checkbox[name='sel_del']").is(":checked") == false) {
			$(".content").html("삭제할 수신 번호를 선택해주세요.");
			//$('#myModal').modal({backdrop: 'static'});
			modal_open('myModal');
			return false;
		} else {
			$("input[name=sel_del]:checked").each(function () {
				var obj = $(this).val();
				var table = document.getElementById("tel");
				var row_index = table.rows.length;
				if (row_index == 2) {
					var tel_number = $("#tel_number").val();
					if (tel_number == "") {
						$(".content").html("삭제할 수신 번호가 없습니다.");
						//$("#myModal").modal({backdrop: 'static'});
						//$(document).unbind("keyup").keyup(function (e) {
						//	var code = e.which;
						//	if (code == 13) {
						//		$(".enter").click();
						//	}
						//});
						modal_open('myModal');
						return false;
					} else {
						//$(".content").html("삭제하시겠습니까?");
						//$("#myModalSelDel").modal({backdrop: 'static'});
						//$("#myModalSelDel").unbind("keyup").keyup(function (e) {
						//	var code = e.which;
						//	if (code == 13) {
						//		$(".selDel").click();
						//	}
						//});
						//$(".selDel").click(function() {
						//	tel_select_del(obj);
						//	$(".submit").unbind("click");
						//});
						if(confirm("삭제 하시겠습니까?")){
							tel_select_del(obj);
						}
					}
				} else {
					//$(".content").html("삭제하시겠습니까?");
					//$("#myModalSelDel").modal({backdrop: 'static'});
					//$("#myModalSelDel").unbind("keyup").keyup(function (e) {
					//	var code = e.which;
					//	if (code == 13) {
					//		$(".selDel").click();
					//	}
					//});
					//$(".selDel").click(function() {
					//	tel_select_del(obj);
					//	$(".selDel").unbind("click");
					//});
					if(confirm("삭제 하시겠습니까?")){
						tel_select_del(obj);
					}
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
					var tr = '<tr class="' + 1 + '" id="tel_tbody_tr">' +
							'<td class="checkbox-column" style="width:10% !important"><input class="uniform" name="sel_del" id="'+ 1 +'"  value="' + 1 + '" type="checkbox"></td>' +
							'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
							'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
							'</tr>';
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
			$(".content").html("<h4>템플릿을 먼저 선택해주세요.</h4>");
			//$("#myModal").modal({backdrop: 'static'});
			//$('#myModal').on('hidden.bs.modal', function () {
				$("#tel_number").val("");
			//});
			modal_open('myModal');
			return false;
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

	function showNeedCoin() {
		 $(".content").html("충전 잔액이 부족합니다.");
		 //$('#myModal').modal({backdrop: 'static'});
		 $('#filecount').filestyle('clear');
		 modal_open('myModal');
		return false;
	 }

	function showLimitOver()
	{
		 $(".content").html("금일 발송 가능 건수를 모두 사용하였습니다.");
		 //$('#myModal').modal({backdrop: 'static'});
		 modal_open('myModal');
		return false;
	}

	function search_sendbox() {
		try {
			open_page_sendbox(1);
		}catch(e){}
	}

	//버튼 링크 복사
	function btnUrlCopy(obj, bno){
		var parent = obj.parentElement;
		//alert(parent);
		var url = $(parent).find('input').val();
		//$("input[name=btn_url2]")[1].val(url);
		$("input[name=btn_url2]")[1].value = url;
		//alert("url : "+ url +", bno : "+ bno);
	}

<?/*--------------------*
    | 링크 확인창 띄우기 |
    *--------------------*/?>
    function urlConfirm(obj, bno) {
    	var parent = obj.parentElement;
    	var pattern = new RegExp("^(?:http(s)?:\\/\\/)?[\\wㄱ-힣.-]+(?:\\.[\\w\\.-]+)+[\\wㄱ-힣\\-\\._~:/?#[\\]@!\\$&\'\\(\\)\\*\\+,;=.]+$", "gm");
    	var url = $(parent).find('input').val();
		var bizurl = $('#biz_url').val();
        var id = Number(bno)-1;
		var link_type = document.getElementById("link_type"+id).value; //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
		//alert("link_type : "+ link_type +"\n"+ "bno : "+ bno +"\n"+ "url : "+ url); return;
    	if(url != "") {
    		if(pattern.test(url)) {
    		 	if (url.toLowerCase().indexOf("http://") == 0 || url.toLowerCase().indexOf("https://") == 0) {
    	 			url = url;
    	 		} else {
    	 			url = "http://" + url;
    	 			$(parent).find('input').val(url);
    	 		}
				if((link_type == "editor" && bno == "1") || (link_type == "smartEditor" && bno == "2")){ //(에디터 && 1번째 버튼) or (스마트전단+에디터 && 2번째 버튼)
					//alert("url : "+ url); return;
					var html = $('#summernote').summernote('code'); //에디터 내용
					//alert("html : "+ html); return;
					if(html == '' || html == '<p><br></p>' || html == '<p><span style="font-size: 18px;">﻿</span><br></p>'){ //에디터의 경우
						$('#summernote').summernote('code', '');
						alert("에디터 내용을 입력하세요.");
						window.scroll(0, getOffsetTop(document.getElementById("id_STEP3"))); //STEP 3 영역으로 이동
						return;
					}
					//alert("html : "+ html); return;
					$.ajax({
						url: "/dhnbiz/sender/talk_v3/ums_save",
						type: "POST",
						data: {
							"<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
							,"dhnlurl": $("#dhnl_url").val() //에디터 링크 URL
							,"bizurl" : bizurl //에디터코드
							,"html" : html //에디터내용
							,"link_type": link_type //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
							,"psd_code": $("#psd_code").val() //스마트전단 코드
							,"psd_url": $("#psd_url").val() //스마트전단 링크 URL
							,"pcd_code": $("#pcd_code").val() //스마트쿠폰 코드
							,"pcd_type": $("#pcd_type").val() //스마트쿠폰 타입
							,"pcd_url": $("#pcd_url").val() //스마트쿠폰 링크 URL
                            ,"home_code": $("#home_code").val() //스마트홈 코드
                            ,"home_url": $("#home_url").val() //스마트홈 링크 URL
						},
						success: function (json) {}
					});
					//window.open(url, '_blank', 'location=no, resizable=no');
					editor_preview(url); //에디터 미리보기
				}else if((link_type == "smart" || link_type == "smartEditor") && bno == "1"){ //스마트전단 && 첫번째 버튼
					//window.open(url, '', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
					smart_preview(url); //스마트전단 미리보기
				}else if(link_type == "coupon" && bno == "1"){ //스마트쿠폰 && 첫번째 버튼
					//window.open(url, '', 'width=420, height=780, location=no, resizable=no, scrollbars=yes');
					coupon_preview(url, $("#pcd_type").val()); //스마트쿠폰 미리보기
				}else{ //기타 버튼의 경우
					window.open(url, '', ''); //새창 뛰움
				}
    		} else {
				alert("링크 경로가 잘못 되었습니다.");
				$(parent).find('input').focus();
				return;
    		}
    	} else {
			alert("링크 경로를 입력하세요.");
			$(parent).find('input').focus();
			return;
    	}
    }

<?/*----------------------------------*
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	*----------------------------------*/?>
	var ms = '<?=($mem->mem_2nd_send) ? substr(strtoupper($mem->mem_2nd_send), 0, 1) : ""?>';
    <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
        var at_price = '<?=$this->Biz_dhn_model->price_v_at?>';
    	var sms_price = '<?=$this->Biz_dhn_model->price_v_smt_sms ?>';
    	var lms_price = '<?=$this->Biz_dhn_model->price_v_smt ?>';
    	var mms_price = '<?=$this->Biz_dhn_model->price_v_smt_mms?>';
    <? }else{ ?>
        var at_price = '<?=$this->Biz_dhn_model->price_at?>';
    	var sms_price = '<?=$this->Biz_dhn_model->price_sms?>';
    	var lms_price = '<?=$this->Biz_dhn_model->price_lms?>';
    	var mms_price = '<?=$this->Biz_dhn_model->price_mms?>';
    <? } ?>
    var phn_price = '<?=$this->Biz_dhn_model->price_phn?>';

<?if($mem->mem_2nd_send=='phn') { echo "var resend_price = '".$this->Biz_dhn_model->price_phn."';\nvar resend_price_sms = '0';"; }
	else if($mem->mem_2nd_send=='sms') { echo "var resend_price = '".$this->Biz_dhn_model->price_sms."';\nvar resend_price_sms = '0';"; }
	else if($mem->mem_2nd_send=='lms') { echo "var resend_price = '".$this->Biz_dhn_model->price_lms."';\nvar resend_price_sms = '0';"; }
	else if($mem->mem_2nd_send=='mms') { echo "var resend_price = '".$this->Biz_dhn_model->price_mms."';\nvar resend_price_sms = '0';"; }
	else if($mem->mem_2nd_send=='GREEN_SHOT') { echo "var resend_price = '".$this->Biz_dhn_model->price_grs."';\nvar resend_price_sms = '".$this->Biz_dhn_model->price_grs_sms."';"; }
	else if($mem->mem_2nd_send=='NASELF') { echo "var resend_price = '".$this->Biz_dhn_model->price_nas."';\nvar resend_price_sms = '".$this->Biz_dhn_model->price_nas_sms."';"; }
	else if($mem->mem_2nd_send=='SMART') { echo "var resend_price = '".$this->Biz_dhn_model->price_smt."';\nvar resend_price_sms = '".$this->Biz_dhn_model->price_smt_sms."';"; }

	else { echo "var resend_price = '0';\nvar resend_price_sms = '0';"; }
?>

	var resend_type = ms;			<?/* 2차발신 종류 */?>
	var resend_type_name = "<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?>";	<?/* 2차발신 이름 */?>
	var charge_type = 0;				<?/* 발신단가 최대값 */?>
	<?/* 발신단가의 최대값과 재발신 방법을 설정 */?>
	function check_resend_method()
	{
		<?/* mms로 지정했어도 이미지가 없으면 LMS 또는 90자 미만이면 sms로 보내야 합니다. */?>
		<?/* 재발신 단가 산정 */?>

		if ($("input:checkbox[id='lms_select']").is(":checked") == false) {
			//alert("알림톡만 발송");
			charge_type = at_price;
			resend_type_name = "폰문자";
			resend_type = "P";
		} else {
    		//alert("알림톡 + 2차문자 발송");
			var temp_mst_kind = "<?= $mem->mem_2nd_send ?>";
			var lms_len = getByteLength($('#lms'));
			var sms_price = '<?=$this->Biz_dhn_model->price_sms ?>';
			var lms_price = '<?=$this->Biz_dhn_model->price_lms ?>';
			if(temp_mst_kind == "GREEN_SHOT"){ //웹(A)
				sms_price = '<?=$this->Biz_dhn_model->price_smt_sms ?>';
				lms_price = '<?=$this->Biz_dhn_model->price_grs ?>';
			}else if(temp_mst_kind == "NASELF"){ //웹(B)
				sms_price = '<?=$this->Biz_dhn_model->price_nas_sms ?>';
				lms_price = '<?=$this->Biz_dhn_model->price_nas ?>';
			}else if(temp_mst_kind == "SMART"){ //웹(C)
                <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
                    sms_price = '<?=$this->Biz_dhn_model->price_v_smt_sms ?>';
        			lms_price = '<?=$this->Biz_dhn_model->price_v_smt ?>';
                <? }else{ ?>
        			sms_price = '<?=$this->Biz_dhn_model->price_smt_sms ?>';
        			lms_price = '<?=$this->Biz_dhn_model->price_smt ?>';
                <? } ?>
			}
			if(lms_len <= 90){
				charge_type = sms_price;
				resend_type_name = "SMS";
        		resend_type = "S";
			}else{
				charge_type = lms_price;
				resend_type_name = "LMS";
        		resend_type = "L";
			}
			<? if($this->member->item("mem_userid") == "dhn" or $this->member->item("mem_userid") == "015227985"){ //관리자, 애드톡 ?>
			//alert("2차 발송의 경우 > temp_mst_kind : "+ temp_mst_kind +"\n"+ "lms_len : "+ lms_len +"\n"+ "charge_type : "+ charge_type +"\n"+ "sms_price : "+ sms_price +"\n"+ "lms_price : "+ lms_price +"\n"+ "resend_type_name : "+ resend_type_name +"\n"+ "resend_type : "+ resend_type);
			<? } ?>
		}
	}
<?/*----------------------------------*
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	*----------------------------------*/?>

	var senderBox = $('#sms_sender').val();
	coin=0;

	//템플릿 변수값 체크
	function isEmptyVariable() {
		var returnCheck = true;
		var i = 0;
		$("textarea[name^='var_name']").each(function(){
			var var_name = $(this).val().trim();
			//alert("var_name["+ i +"] : "+ var_name);
			if(var_name == "") {
				returnCheck = i;
				return false;
			}
			i++;
		});
		//alert("returnCheck : "+ returnCheck);
		return returnCheck;
	}

	//URL 체크
	function checkURL(strURL) {
		var returnCheck = false;
		var pattern = new RegExp("^(?:http(s)?:\\/\\/)?[\\wㄱ-힣.-]+(?:\\.[\\w\\.-]+)+[\\wㄱ-힣\\-\\._~:/?#[\\]@!\\$&\'\\(\\)\\*\\+,;=.]+$", "gm");
		if(strURL != "") {
			if(pattern.test(strURL)) {
				returnCheck = true;
			} else {
				returnCheck = false;
			}
		}
		return returnCheck;
	}

	//한글포함 문자열 길이를 구한다
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

	function getTempli() {
		var dumyTempli_cont = $("#dumy_templi_cont").text();
		var temp = dumyTempli_cont.split("#{");
		var temp2 = new Array();
		var returnTempli_cont = dumyTempli_cont;
		for (var i = 0; i < temp.length; i++) {
			if (i == 0) {
				returnTempli_cont = temp[i];
			} else {
				var varsplit = temp[i].split("}")
				//var varName = "#{" + varsplit[0] + "}";
				//var var_name = $("input[name=var_name]")[(i-1)].value;
				var var_name = $("textarea[name^='var_name']")[(i-1)].value;
				returnTempli_cont = returnTempli_cont + var_name;
				returnTempli_cont = returnTempli_cont + varsplit[1];
			}
		}
		return returnTempli_cont;
	}

	//전체고객정보 불러오기 하여 전체 발송버튼 클릭 [업로드 발송 포함] 또는 수신목록이 표시된 상태에서 전체발송 클릭
	function all_send() {
		// 2020.03.30 트라이얼 요청으로 후불제 마트 발송시 confirm pop content_msg 수정
		var mem_pay_type = "<?=$mem->mem_pay_type ?>";
		var emptyVar = isEmptyVariable(); //템플릿 변수값 체크
		var sendPhoneCount = 0;
        var link_type_cnt = $("[name='link_type']").length;
        var home_chk1 = $("#chk_a").is(":checked");//스마트전단
        var home_chk2 = $("#chk_b").is(":checked");//에디터
        var home_chk3 = $("#chk_c").is(":checked");//스마트쿠폰
        var home_chk4 = $("#chk_d").is(":checked");//직접입력
        var order_url = "";
        if(home_chk4){
            order_url = $("#hdn_order_url").val();
        }
        // var link_type = document.getElementById("link_type").value; //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
        var link_type = "";
        if(link_type_cnt>1){
            if(buttonArr.indexOf("smart")>-1&&buttonArr.indexOf("editor")>-1){
                link_type = "smartEditor";
            }else{
                if(buttonArr.indexOf("smart")>-1){
                    link_type = "smart";
                }else if(buttonArr.indexOf("editor")>-1){
                    link_type = "editor";
                }else if(buttonArr.indexOf("coupon")>-1){
                    link_type = "coupon";
                }else{
                    link_type = "self";
                }
            }
        }else{
            if(link_type_cnt>0){
                link_type = document.getElementById("link_type0").value;
            }
        }

        // console.log("link_type : "+link_type);
        // console.log("home_chk1 : "+home_chk1);
        // console.log("home_chk2 : "+home_chk2);
        // console.log("home_chk3 : "+home_chk3);
        // console.log("home_chk4 : "+home_chk4);

        if(link_type=="home"&&home_chk1==false&&home_chk2==false&&home_chk3==false&&home_chk4==false){
            alert("스마트홈 기능을 선택하세요."); return;
        }



		var psd_url = document.getElementById("psd_url").value; //스마트전단 링크 URL
		var psd_code = document.getElementById("psd_code").value; //스마트전단 code
		var templi_id = document.getElementById("templi_id").value; //템플릿번호
		var summ = $('#summernote').summernote('code'); //에디터내용
		//alert("link_type : "+ link_type +", psd_url : "+ psd_url +", psd_code : "+ psd_code +", templi_id : "+ templi_id); return;
		if($("#templi_code").val() == null) {
			//$(".content").html("<h4>템플릿을 먼저 선택해주세요.</h4>");
			//$('#myModal').modal({backdrop: 'static'});
			alert("템플릿을 먼저 선택해주세요."); return;
		}
		if (emptyVar !== true) {
			if(emptyVar == 1){
				alert("날짜를 입력해주세요.");
			}else if(emptyVar == 2){
				alert("행사내용을 입력해주세요.");
			}else{
				alert("템플릿 "+ (emptyVar+1) +"번째 변수를 입력해주세요.");
			}
			$("textarea[name=var_name]")[emptyVar].focus();
			return;
		}
		// if ($('#lms_select').prop('checked') && $("#lms").val().replace(/ /gi, "") == "") {
		//
		// 	// $("#lms_select").attr('checked', false);
		// 	// $("#hidden_fields_sms").show();
		// 	alert("2차 발송 내용을 입력해주세요.");
		// 	$("#lms").focus();
		// 	return;
		// }
		if (templi_charCount() > 1000) {
			alert("알림톡은 내용을 1,000자 이내로 입력하여야 합니다.");
			$("textarea[name=var_name]")[0].focus();
			return;
		}
		if(get_btn_cnt > 0){ //링크버튼형
			//alert("psd_code : "+ psd_code);
			if((link_type == "smart" || link_type == "smartEditor" || (link_type == "home" && home_chk1) ) && psd_code == ""){ //스마트전단의 경우
				alert("스마트전단을 선택하세요.");
				smart_page('1'); //스마트전단 불러오기 모달창 열기
				return false;
			}else if((link_type == "coupon" || (link_type == "home" && home_chk3))&& pcd_code == ""){ //스마트쿠폰의 경우
				alert("스마트쿠폰을 선택하세요.");
				coupon_page('1'); //스마트쿠폰 불러오기 모달창 열기
				return false;
			}
		}
		if ((link_type == "editor" || link_type == "smartEditor" || (link_type == "home" && home_chk2)) && (summ == '' || summ == '<p><br></p>' || summ == '<p><span style="font-size: 18px;">﻿</span><br></p>')){ //에디터의 경우
			alert("에디터 내용을 입력하세요.");
			//$("#link_type").focus();
			window.scroll(0, getOffsetTop(document.getElementById("id_STEP3"))); //STEP 3 영역으로 이동
			return;
		}
        if (link_type == "home" && home_chk4 && order_url == ''){ //주문하기의 경우
			alert("주문하기 url을 입력하세요.");
			//$("#link_type").focus();
			window.scroll(0, getOffsetTop(document.getElementById("id_STEP3"))); //STEP 3 영역으로 이동
			return;
		}
		if (chkword_lmsCount() > 2000){
			alert("2차 발송 내용은 2,000byte(한글 1,000자)이내로\n입력하여야 합니다.");
			$("#lms").focus();
			return;
		}
		if ($("#switch_upload").is(":checked") && parseInt($("#upload_tel_count").text()) == 0) {		// upload 발송
			alert("수신 전화번호 엑셀파일 업로드하세요."); return;
		}
		if ($("#switch_select").is(":checked")) {	//고객 DB에서 그룹 선택
    		//if ($("input[name=checkOne]:checked").length == 0) {
    		//if ($("#groupId").val() == "") {
			//	alert("고객 그룹을 선택하세요."); return;
    		//}
			//alert("groupId : "+ $("#groupId").val());
			var groupSendTotal = $("#groupSendTotal").text().replace(",", "");
			//alert("groupSendTotal : "+ groupSendTotal); return;
			if (groupSendTotal == "" || groupSendTotal == "0") {
				alert("고객 그룹을 선택하세요."); return;
    		}
    	}
		if ($("#switch_customer").is(":checked") && $("span[name=input_phone_no]").length == 0) {	// 연락처 직접 입력
    		if ($("span[name=input_phone_no]").length == 0) {
    			//$(".content").html("연락처를 입력하세요.");
    			//$('#myModal').modal({backdrop: 'static'});
				alert("연락처를 입력 후 [전화번호 추가] 버튼을 클릭하세요.");
				$("#add-todo-input").focus();
				return;
    		}
    	}
		if ($("#switch_friend").is(":checked") && $("input[name=friend_yn]:checked").length == 0) {	// 플러스 친구 여부 확인 2019-07-09 추가
			if ($("input[name=friend_yn]").length == 0) {
				//$(".content").html("플러스 친구 여부를 선택하세요.");
				//$('#myModal').modal({backdrop: 'static'});
				alert("플러스 친구 여부를 선택하세요."); return;
			}
		}
		// if ($('#lms_select').prop('checked') && $("#lms").val().replace(/ /gi, "") == "") {
		//
		// 	$("#lms_select").attr('checked', false);
		// 	$("#hidden_fields_sms").show();
		//
		// 	// alert("2차 발송 내용을 입력해주세요.");
		// 	// $("#lms").focus();
		// 	// return;
		// }
		if(1 == 1){
			//alert("Start"); return;
			//alert("groupId : "+ $("#groupId").val()); return;
			var check = true;
			// 2019.11.15 추가 시작(이수환)
			$.ajax({
				url: "/dhnbiz/sender/talk_v3/equal_send_mem_id",
				type: "POST",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
						   "senderMem_id" : <?=$this->member->item("mem_id") ?>},
				success: function (json) {
					var senderCheck = json['senderCheck'];
					if (senderCheck == "F") {
	    				check = false;
	    				$(".content").html("알림톡 작성한 계정과 발송계정이 틀립니다.<br>발송계정 다시 로그인 후 다시 작성하세요.");
	    				//$('#myModal').modal({backdrop: 'static'});
	    				//$(document).unbind("keyup").keyup(function (e) {
	    				//	var code = e.which;
	    				//	if (code == 13) {
	    				//		$(".enter").click();
	    				//	}
	    				//});
						modal_open('myModal');
						return false;
					} else {
						check = true;
						<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
						//var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
						var lms_header_temp = $("#span_adv").text() + $("#companyName").val();
						var lms_footer_temp = "";
						if($("#kakotalk_link_text").text() == "") {
							lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
						} else {
							lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
						}
						//var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

						var lms_msg = lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
						$.ajax({
							url: "/dhnbiz/sender/check_special_char",
							type: "POST",
							data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
								   "lmsText" : lms_msg, "lmsTitle": "2차 발송"},
							success: function (json) {
								var checkMode = json['checkMode'];
								var tatalBytes = json['tatalBytes'];
								var checkMsg = json['checkMsg'];

								if (checkMode == "0") {
				    				$(".content").html(checkMsg);
				    				//$('#myModal').modal({backdrop: 'static'});
				    				check = false;
									modal_open('myModal');
									return false;
								}

								if(check == true) {
					    			var mem_talk_time_clear_yn = "<?=$this->member->item('mem_talk_time_clear_yn')?>"; //알림톡 발송시간 해제 2020-12-30
									//alert("mem_talk_time_clear_yn : "+ mem_talk_time_clear_yn);
									if($("#reserve_check").is(":checked") == true) {
					    				if($("#reserve").val().trim() == ""){
					    					check = false;
											alert("예약 발송 일자를 선택해주세요.");
											return;
					    				} else if ($("#reserve_hours").val().trim() == "") {
											alert("예약 발송 시간를 선택해주세요.");
											return;
					    					check = false;
					    				} else if ($("#reserve_minutes").val().trim() == "") {
											alert("예약 발송 분를 선택해주세요.");
											return;
					    					check = false;
					    				} else {
					    					if($("#reserve_hours").val() < 8 || $("#reserve_hours").val() > 19){
												if((mem_talk_time_clear_yn == "Y" && $('#lms_select').prop('checked') && $("#reserve_hours").val() > 20) && "<?=$mem->mem_sms_time_clear_yn?>" == "N"){
													check = false;
													alert("2차발송 선택시 예약시간을 8:00 ~ 20:50분 사이로 입력해주세요.");
													return;
												}else if(mem_talk_time_clear_yn != "Y"){
													check = false;
													alert("예약시간을 8:00 ~ 19:50분 사이로 입력해주세요.");
													return;
												}
											}
											//var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
											var reserve_hours = ($("#reserve_hours").val() < 10) ? "0" + $("#reserve_hours").val() : "" + $("#reserve_hours").val();
											var reserve_minutes = ($("#reserve_minutes").val() < 10) ? "0" + $("#reserve_minutes").val() : "" + $("#reserve_minutes").val();
											var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "") + reserve_hours + reserve_minutes;
											var reserveDt = reserve+"00";
											var now = moment().add(10,'minutes');
											var min = now.format("YYYYMMDDHHmm");
											var minDt = min+"00";
											if(reserveDt < minDt){
												check = false;
												alert("예약발송은 최소 10분 이후로 입력해주세요.");
												return;
											}
					    				}
					    			} else {
										var st = sTime();
										var date = new Date(st);
										//var hour = parseInt(date.getHours());
										var hour = numPad(date.getHours(), 2);
										//hour = numPad("7", 2);
										var minute = numPad(date.getMinutes(), 2);
										var time = hour +""+ minute;
										//time = "2200";
										//alert("date : "+ date +"\n"+"hour : "+ hour +"\n"+"minute : "+ minute +"\n"+"time : "+ time);
										if((time < "0800" || time > "2000") && "<?=$mem->mem_sms_time_clear_yn?>" == "N"){
											if(mem_talk_time_clear_yn == "Y" && $('#lms_select').prop('checked') && time > "2100"){
												check = false;
												alert("2차발송 선택시 발송시간은 8:00 ~ 21:00시 사이로 발송할 수 있습니다.");
												return;
											}else if(mem_talk_time_clear_yn != "Y"){
												check = false;
												alert("발송시간은 8:00 ~ 20:00시 사이로 발송할 수 있습니다.");
												return;
											}
										}
									}
								}

								if (check == true) {
									var btn_no = 0;
									var pattern = new RegExp("^(?:http(s)?:\\/\\/)?[\\wㄱ-힣.-]+(?:\\.[\\w\\.-]+)+[\\wㄱ-힣\\-\\._~:/?#[\\]@!\\$&\'\\(\\)\\*\\+,;=.]+$", "gm");
									//alert("all_send() > link_type : "+ link_type); return;
									$("div[name='field-data']").each(function () {
										btn_no++;
										var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
										if (btn_type != undefined) {
											var focus;
											var btn_url1 = $(this).find(document.getElementsByName("btn_url1")).val() || "";
											var btn_url2 = $(this).find(document.getElementsByName("btn_url2")).val() || "";
											//alert("["+ btn_no +"] btn_url1 : "+ btn_url1);
											if(btn_url1 != ""){
                                                if (btn_type == "WL") {
                                                    if(btn_url1.indexOf("https://")>-1){
                                                        check = false;
                                                        focus = $(this).find(document.getElementsByName("btn_url1"));
                                                        alert(btn_no+"번째 버튼 링크 URL 주소가 올바르지 않습니다.");
                                                        return false;
                                                    }
                                                }
												// if(pattern.test(btn_url1)) {
													if (btn_url1.toLowerCase().indexOf("http://") == 0 || btn_url1.toLowerCase().indexOf("https://") == 0) {
													} else {
														btn_url1 = "http://" + btn_url1;
														$(this).find(document.getElementsByName("btn_url1")).val(btn_url1);
													}
												// }else{
												// 	check = false;
					    						// 	focus = $(this).find(document.getElementsByName("btn_url1"));
												// 	alert(btn_no+"번째 버튼 링크 URL 주소가 올바르지 않습니다.");
												// 	focus.focus();
												// 	return false;
												// }
											}
											if(btn_url2 != ""){
												if (btn_url2.toLowerCase().indexOf("http://") == 0 || btn_url2.toLowerCase().indexOf("https://") == 0) {
												} else {
													btn_url2 = "http://" + btn_url2;
													$(this).find(document.getElementsByName("btn_url2")).val(btn_url2);
												}
											}
											if (btn_type == "WL") {
					    						if (btn_url1 == "" || !checkURL(btn_url1)) {
					    							check = false;
					    							focus = $(this).find(document.getElementsByName("btn_url1"));
													if(link_type == "smart" && btn_no == 1){
														alert("스마트전단을 선택하세요.");
														smart_page('1'); //스마트전단 불러오기 모달창 열기
													}else if(link_type == "coupon" && btn_no == 1){
														alert("스마트쿠폰을 선택하세요.");
														coupon_page('1'); //스마트쿠폰 불러오기 모달창 열기
													}else{
														alert(btn_no+"번째 버튼 링크가 입력되지 않았거나 URL 주소가 올바르지 않습니다.");
														focus.focus();
													}
													return;
					    						}
												if (btn_url2 == "" || !checkURL(btn_url2)) {
					    							if (btn_url1 == "" || !checkURL(btn_url1)) {
														check = false;
														focus = $(this).find(document.getElementsByName("btn_url2"));
														alert(btn_no+"번째 버튼 링크를 입력해주세요.");
														focus.focus();
														return;
													}else{
														$(this).find(document.getElementsByName("btn_url2")).val(btn_url1);
													}
					    						}
					    					} else if (btn_type == "AL") {
					    						if (btn_url1 == "" || !checkURL(btn_url1)) {
					    							check = false;
					    							focus = $(this).find(document.getElementsByName("btn_url31"));
					    							$(".content").html("앱링크 버튼의 Android 버튼링크를 입력해주세요.");
					    							$("#myModal").modal('show');
					    							$('#myModal').on('hidden.bs.modal', function () {
					    								focus.focus();
					    							});
					    						} else if (btn_url2 == "" || !checkURL(btn_url2)) {
					    							check = false;
					    							focus = $(this).parent().find(document.getElementsByName("btn_url32"));
					    							$(".content").html("앱링크 버튼의 iOS 버튼링크를 입력해주세요.");
					    							$("#myModal").modal('show');
					    							$('#myModal').on('hidden.bs.modal', function () {
					    								focus.focus();
					    							});
					    						}
					    					}
										}
									});
								}

								if (check == true) {
									if ($('#lms_select').prop('checked') && $("#lms").val().replace(/ /gi, "") == "") {
										$("#lms_select").attr('checked', false);
										$("#hidden_fields_sms").hide();
									// alert("2차 발송 내용을 입력해주세요.");
									// $("#lms").focus();
									// return;
										}
								}

								if (check == true) {
									var _2ndflag = "N";
									if ($('#lms_select').prop('checked')) _2ndflag = "Y";
									//alert("_2ndflag : "+ _2ndflag);

									$.ajax({
										url: "/dhnbiz/sender/coin",
										type: "POST",
										data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
								 		       "2ndflang":_2ndflag
											},
										success: function (json) {
											coin = json['coin'];
											var limit = parseInt(json['limit']);
											var sent = parseInt(json['sent']);
                                            var bcoin = json.bcoin;
											var limit_msg = "";
											// if(limit > 0 && sent >= limit) { showLimitOver("("+sent+"/"+limit+")"); return; }
											// if(limit > 0) {
											// 	limit_msg = "<div class='modal_info_row'><label>금일 발송가능</label>" + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</div>";
											// }
											<? if( $this->member->item("mem_userid") == "dhn"){ //DHN마트톡 ?>
											//alert("coin : "+ coin +", limit : "+ limit +", sent : "+ sent);
											<? }else if( $this->member->item("mem_userid") == "015227985"){ //DHN애드톡 ?>
											//alert("coin : "+ coin +", limit : "+ limit +", sent : "+ sent);
											<? } ?>

											var kakao = Math.floor((Number(coin) + Number(bcoin)) / Number(at_price));
											var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/, '0');

											check_resend_method();
											<? if( $this->member->item("mem_userid") == "015227985"){ //DHN애드톡 ?>
											//alert("charge_type : "+ charge_type);
											<? } ?>

											// 재발신 가능 건수를 산정합니다.
											var resends = (charge_type > 0) ? Math.floor((Number(coin) + Number(bcoin)) / Number(charge_type)) : 0;
											var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');

											if (!$("#switch_upload").is(":checked")) {
												var row_index = 0;
												if ($("#switch_select").is(":checked")) { // 고객 DB에서 그룹 선택
													row_index = parseInt($("#groupSendTotal").text().replace(/,/gi, ""));
												} else if ($("#switch_customer").is(":checked")) { // 연락처 직접 입력
													row_index = parseInt($("#input_phone_count").text().replace(/,/gi, ""));
												} else if ($("#switch_friend").is(":checked")) {	//플러스 친구 여부 선택 2019-07-09 추가
													row_index = parseInt($("#friend_yn_tel_count").text().replace(/,/gi, ""));
												} else if ($("#switch_pos").is(":checked")) {	//포스고객 선택 2021-02-03 추가
													row_index = parseInt($("#posSendTotal").text().replace(/,/gi, ""));
												}

												//alert("row_index : "+ row_index);
												if(row_index == "0"){
													alert("수신고객이 없습니다.");
													return false;
												}

												<? if( $this->member->item("mem_userid") == "dhn"){ //DHN마트톡 ?>
												//coin = 5;
												<? }else if( $this->member->item("mem_userid") == "015227985"){ //DHN애드톡 ?>
												//alert("coin : "+ coin +", charge_type : "+ charge_type +", row_index : "+ row_index);
												<? } ?>
												if ((Number(coin) + Number(bcoin)) - (Number(charge_type) * Number(row_index)) < 0) {
													$(".content").html("잔액이 부족합니다.<br>잔액 : "+coin+"<br>예상금액 : "+ (Number(charge_type) * Number(row_index)));
													modal_open('myModal');
													return false;
												} else {
													// 2020.03.30 트라이얼 요청으로 후불제 마트 발송시 confirm pop content_msg 수정
													//if (mem_pay_type === "A") {
													if(1 == 1){
														var content_msg = "";
														var s2nd_msg = "";
														var send_time = "";

														if($("#reserve_check").is(":checked") == true) {
						    		    					var reserve_hours = ($("#reserve_hours").val() < 10) ? "0" + $("#reserve_hours").val() : "" + $("#reserve_hours").val();
						    		    					var reserve_minutes = ($("#reserve_minutes").val() < 10) ? "0" + $("#reserve_minutes").val() : "" + $("#reserve_minutes").val();
						    		    					var reserve = $("#reserve").val();
						    		    					send_time = reserve + " " + reserve_hours + ":" + reserve_minutes;
														} else {
															send_time = "즉시 발송";
														}

														if (ms!="" && ($('#lms').val().replace(/ /gi, "") != "") && $("#lms_select").is(":checked") == true) {
															s2nd_msg = " + 2차문자";
														}

														content_msg = "<div class='modal_info_row'><label>발송 종류</label>알림톡" + s2nd_msg + "</div>";
														content_msg += "<div class='modal_info_row'><label>발송 시간</label>" + send_time + "</div>";
														content_msg += "<div class='modal_info_row'><label>발송 건수</label>" + row_index.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "건</div>";
                                                        <?if($mem->mrg_recommend_mem_id != "962" && $this->member->item('mem_id') != '962'){?>
														if (ms!="" && ($('#lms').val().replace(/ /gi, "") != "") && $("#lms_select").is(":checked") == true) {	// 2차문자 발송
															content_msg += "<div class='modal_info_row'><label>알림톡 예상 금액</label>"+ ((Number(at_price) * Number(row_index)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원(" + row_index + " X " + at_price + ")</div>";
															content_msg += "<div class='modal_info_row'><label>2차문자 예상 금액</label>"+ ((Number(charge_type) * Number(row_index)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원(" + row_index + " X " + charge_type + ")</div>";
														} else {	// 2차문자 미발송
															content_msg += "<div class='modal_info_row'><label>예상 발송 금액</label>"+ ((Number(at_price) * Number(row_index)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원(" + row_index + " X " + at_price + ")</div>";
														}
                                                        <?}?>
														$(".content").html(limit_msg + content_msg + "<p class='modal_info_confirm'>전체 발송하시겠습니까?</p>");
													}
													//} else {
    												//	var content_msg = "알림톡 발송 가능 건수 : " + k + "건";
    												//	if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
    												//		content_msg += "<br/>" + "2차문자 발송 가능 건수 : " + resend_cnt + "건";
    												//	}
    												//	content_msg += "<br/><br/>" + "알림톡 발송 예상 금액 : " + (Number(at_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + at_price + ")";
    												//	if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
    												//		content_msg += "<br/>" + "2차문자 발송 예상 금액 : " + (Number(charge_type) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + charge_type + ")";
    												//	}

    												//	$(".content").html(limit_msg + content_msg + "<br/><br/>전체 발송하시겠습니까? (발송할 건수 : " + row_index + "건)");
													//}

													//$("#myModalAll").modal({backdrop: 'static'});
													//$("#myModalAll").unbind("keyup").keyup(function (e) {
													//	var code = e.which;
													//	if (code == 13) {
													//		$(".all").click();
													//	}
													//});
													//$(".all").click(function () {
													//	$("#myModalAll").modal('hide');
													//});
                                                    $('.btn_send').prop('disabled', true);
                                                    console.log($('.btn_send').prop('disabled'));
													modal_open('myModalAll');
												}
											} else {
												<?/* 업로드 된 건수 */?>
												var upload_send = $("#upload_tel_count").text();
												//alert("upload_send : "+ upload_send);
												<?/* 발송가능 건수 표시 : 업로드 된 자료의 경우 업로드 내용으로만 판단할 수 있으므로 잔액이 부족하다는 메시지를 보여줄 수 없습니다. */?>
												if ((Number(coin) + Number(bcoin)) - (Number(charge_type) * Number(upload_send)) < 0) {
													$(".content").html("잔액이 부족합니다.<br>잔액 : "+coin+"<br>예상금액 : "+(Number(charge_type) * Number(upload_send)));
													modal_open('myModal');
													return false;
												} else {
													// 2020.03.30 트라이얼 요청으로 후불제 마트 발송시 confirm pop content_msg 수정
													//if (mem_pay_type === "A") {
														var content_msg = "";
														var s2nd_msg = "";
														var send_time = "";

														if($("#reserve_check").is(":checked") == true) {
						    		    					var reserve_hours = ($("#reserve_hours").val() < 10) ? "0" + $("#reserve_hours").val() : "" + $("#reserve_hours").val();
						    		    					var reserve_minutes = ($("#reserve_minutes").val() < 10) ? "0" + $("#reserve_minutes").val() : "" + $("#reserve_minutes").val();
						    		    					var reserve = $("#reserve").val();
						    		    					send_time = reserve + " " + reserve_hours + ":" + reserve_minutes;
														} else {
															send_time = "즉시 발송";
														}

														if (ms!="" && ($('#lms').val().replace(/ /gi, "") != "") && $("#lms_select").is(":checked") == true) {
															s2nd_msg = " + 2차문자";
														}
														var row_index = upload_send.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
														//alert("row_index : "+ row_index);
														content_msg = "<div class='modal_info_row'><label>발송 종류</label>알림톡" + s2nd_msg + "</div>";
														content_msg += "<div class='modal_info_row'><label>발송 시간</label>" + send_time + "</div>";
														content_msg += "<div class='modal_info_row'><label>발송 건수</label>" + upload_send.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "건</div>";
                                                        <?if($mem->mrg_recommend_mem_id != "962" && $this->member->item('mem_id') != '962'){?>
														if (ms!="" && ($('#lms').val().replace(/ /gi, "") != "") && $("#lms_select").is(":checked") == true) {	// 2차문자 발송
															//content_msg += "<div class='modal_info_row'><label>예상 발송 금액</label>"+ ((Number(charge_type) * Number(upload_send)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원</div>";
															content_msg += "<div class='modal_info_row'><label>알림톡 예상 금액</label>"+ ((Number(at_price) * Number(row_index)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원(" + row_index + " X " + at_price + ")</div>";
															content_msg += "<div class='modal_info_row'><label>2차문자 예상 금액</label>"+ ((Number(charge_type) * Number(row_index)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원(" + row_index + " X " + charge_type + ")</div>";
														} else {	// 2차문자 미발송
															content_msg += "<div class='modal_info_row'><label>예상 발송 금액</label>"+ ((Number(at_price) * Number(upload_send)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원(" + row_index + " X " + at_price + ")</div>";
														}
                                                        <?}?>
														$(".content").html(limit_msg + content_msg + "<p class='modal_info_confirm'>업로드 파일 전체 발송하시겠습니까?</p>");

													//} else {
    			    								//	var content_msg = "알림톡 발송 가능 건수 : " + k + "건";
    			    								//	if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
    			    								//		content_msg += "<br/>" + "2차문자 발송 가능 건수 : " + resend_cnt + "건";
    			    								//	}
    			    								//	content_msg += "<br/><br/>" + "알림톡 발송 예상 금액 : " + (Number(at_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + at_price + ")";
    			    								//	if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
    			    								//		content_msg += "<br/>" + "2차문자 발송 예상 금액 : " + (Number(charge_type) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + charge_type + ")";
    			    								//	}
    			    								//	$(".content").html(limit_msg + content_msg + "<br/><br/>업로드 파일을 전체 발송하시겠습니까? (발송할 건수 : " + upload_send + "건)");
													//}

													//$("#myModalAll").modal({backdrop: 'static'});
			    									//$(document).unbind("keyup").keyup(function (e) {
			    									//	var code = e.which;
			    									//	if (code == 13) {
			    									//		$(".all").click();
			    									//	}
			    									//});
			    									//$(".all").click(function () {
			    									//	$("#myModalAll").modal("hide");
			    									//});
                                                    $('.btn_send').prop('disabled', true);
                                                    console.log($('.btn_send').prop('disabled'));
													modal_open('myModalAll');
												}
											}
										}
									});
								}
							}
						});
					}
				},
    			error: function () {
    				check = false;
    				$(".content").html("<h4 class='modal_icon_alarm'>시스템 오류로 처리되지 않았습니다.</h4>");
    				//$('#myModal').modal({backdrop: 'static'});
    				//$(document).unbind("keyup").keyup(function (e) {
    				//	var code = e.which;
    				//	if (code == 13) {
    				//		$(".enter").click();
    				//	}
    				//});
					modal_open('myModal');
					return false;
    			}
			});
			// 2019.11.15 추가 끝(이수환)
		}
	}

	//전체발송(전체고객정보불러오기 포함) 버튼 클릭시 실제 발송 처리하는 부분입니다.
	function all_move() {
        if(sendFlag==false){
            sendFlag = true;
            $('.cancel').focus();
            // $('.cancel').trigger('click');
            // $('#myModalAll').remove();
            check_shop2();
    		var nextNavigateURL = "/dhnbiz/sender/history";
    		var templi_cont = getTempli();
    		var code = document.getElementById("templi_code").value;
    		var profile = document.getElementById("pf_key").value;
    		var kind = document.getElementById("kind").value;
    		var couponid = $("#couponid").val();
    		var kind = ms;
    		var lms_header_temp = "";
    		var lms_footer_temp = "";
    		var msg = "";
    		senderBox = $('#sms_sender').val();
            var home_chk4 = $("#chk_d").is(":checked");//직접입력
            var order_url = "";
            if(home_chk4){
                order_url = $("#hdn_order_url").val();
            }

    		if ($("input:checkbox[id='lms_select']").is(":checked") == true && $("#lms").val().trim() != "") {
    			<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
    			//lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
    			lms_header_temp = $("#span_adv").text() + $("#companyName").val();
    			if($("#kakotalk_link_text").text() == "") {
    				lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
    			} else {
    				lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
    			}
    			//lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

        		msg = lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
                // 2019.01.21 이수환 특수문자 제거 테스트
                //msg = msg.replace(/[\u2100-\u2120\u2123-\u214F\u2190-\u21FF\u0080-\u008F\uFE50-\uFE6F\u2460-\u24FF\u2300-\u259F\u25A2\u25A4-\u25A5\u25A7-\u25B1\u25B4-\u25B5\u25B8-\u25BB\u25BE-\u25BF\u25C2-\u25C5\u25C8-\u25CA\u25CC-\u25CD\u25D2-\u2604\u2607-\u260D\u260F-\u261D\u261F-\u263F\u2641\u2643-\u2660\u2662\u2664\u2666\u2668-\u2669\u266B\u266D-\u303F\u3100-\u3130\u3164-\u32FF\u2150-\u2189\u2200-\u2298\u229A-\u22FF\u0080-\u00FF\u2000-\u203A\u203C-\u206F\uFF00-\uFF02\uFF04-\uFF05\uFF07-\uFF1F\uFF21-\uFFEF\u3164-\u318F\u0000-\u0009\u3300-\u33FF\u0400-\u04FF\u2070-\u209C]/gi, "");
                //msg = msg.replace(/[\u200B\u3000\u3164\uDB40\u272A]/gi, "");
                //msg = msg.replace(/[\u2027]/gi, " ");
    		}

    		var reserveDt = "00000000000000";
    		if($("#reserve_check").is(":checked") == true) {
    			if($("#reserve").val().trim() != "" && $("#reserve_hours").val() != "" && $("#reserve_minutes").val() != ""){
    				var reserve_hours = ($("#reserve_hours").val() < 10) ? "0" + $("#reserve_hours").val() : "" + $("#reserve_hours").val();
    				var reserve_minutes = ($("#reserve_minutes").val() < 10) ? "0" + $("#reserve_minutes").val() : "" + $("#reserve_minutes").val();
    				var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "") + reserve_hours + reserve_minutes;
    				reserveDt = reserve+"00";
    			}
    		}

    		var btn = [];
    		for (var b = 1; b <= 5; b++) {
    			btn[b] = new Array();
    		}
    		var obj = [];
    		var btn_num = 1;

    		$("div[name='field-data']").each(function () {
    			var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
    			if (btn_type != undefined) {
    				obj[btn_num] = new Object();
    				obj[btn_num].type = btn_type;
    				obj[btn_num].name = $(this).find(document.getElementsByName("btn_name")).val();
    				if (btn_type == "WL") {
    					obj[btn_num].url_mobile = $(this).find(document.getElementsByName("btn_url1")).val();
    					if ($(this).find(document.getElementsByName("btn_url2")).val() != undefined) {
    					obj[btn_num].url_pc = $(this).find(document.getElementsByName("btn_url2")).val();
    					}
    				} else if (btn_type == "AL") {
    					obj[btn_num].scheme_android = $(this).find(document.getElementsByName("btn_url1")).val().trim();
    					obj[btn_num].scheme_ios = $(this).find(document.getElementsByName("btn_url2")).val().trim();
    				}
    				btn[btn_num].push(obj[btn_num]);
    				btn_num++;
    			}
    		});
    		var btn1 = JSON.stringify(btn[1]);
    		var btn2 = JSON.stringify(btn[2]);
    		var btn3 = JSON.stringify(btn[3]);
    		var btn4 = JSON.stringify(btn[4]);
    		var btn5 = JSON.stringify(btn[5]);

    		// 2019.01.31. 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
    		var mst_type1 = '';
    		var mst_type2 = '';
    		mst_type1 = 'at';
    		if(msg != '' && $("input:checkbox[id='lms_select']").is(":checked") == true) { //2차 문자가 있는 경우
    			//웹(A)LMS : wa, 웹(A)SMS : was, 웹(A) MMS : wam, 웹(B)LMS : wb, 웹(B)SMS : wbs, 웹(B) : wbm, 폰문자: phn
    			var temp_mst_kind = "<?= $mem->mem_2nd_send ?>";
    			var lms_len = getByteLength($('#lms'));
    			if(temp_mst_kind == "GREEN_SHOT") { //웹(A)
    				if(lms_len <= 90){
    					mst_type2 = "wcs";
    				}else{
    					mst_type2 = "wa";
    				}
    			} else if (temp_mst_kind == "NASELF"){ //웹(B)
    				if(lms_len <= 90){
    					mst_type2 = "wbs";
    				}else{
    					mst_type2 = "wb";
    				}
    			} else if (temp_mst_kind == "SMART"){ //웹(C)
    				if(lms_len <= 90){
    					mst_type2 = "wcs";
    				}else{
    					mst_type2 = "wc";
    				}
    			} else {
    				mst_type2 = temp_mst_kind;
    			}
    		}
    		<? if($this->member->item("mem_userid") == "dhn"){ ?>
    		//alert("resend_type : "+ resend_type +", mst_type1 : "+ mst_type1 +", mst_type2 : "+ mst_type2 +", getByteLength : "+ getByteLength($('#lms'))); return;
    		<? } ?>
            var link_type_cnt = $("[name='link_type']").length;
    		// var link_type = $("#link_type").val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
            var link_type = "";
            if(link_type_cnt>1){
                if(buttonArr.indexOf("smart")>-1&&buttonArr.indexOf("editor")>-1){
                    link_type = "smartEditor";
                }else{
                    if(buttonArr.indexOf("smart")>-1){
                        link_type = "smart";
                    }else if(buttonArr.indexOf("editor")>-1){
                        link_type = "editor";
                    }else if(buttonArr.indexOf("coupon")>-1){
                        link_type = "coupon";
                    }else{
                        link_type = "self";
                    }
                }
            }else{
                if(link_type_cnt>0){
                    link_type = document.getElementById("link_type0").value;
                }
            }
    		//alert("link_type : "+ link_type +", psd_code : "+ $("#psd_code").val() +", psd_url : "+ $("#psd_url").val()); return;
    		if ($("#switch_upload").is(":checked") && parseInt($("#upload_tel_count").text()) > 0) { //파일업로드 발송
    			//customer_all_send = parseInt($("#upload_tel_count").text());
    			var upload_count = parseInt($("#upload_tel_count").text());
    			var file_data = new FormData();
    			//alert(templi_cont);
    			//alert("파일업로드 발송 > link_type : "+ link_type +", psd_code : "+ $("#psd_code").val() +", psd_url : "+ $("#psd_url").val()); return;
    			file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
    			file_data.append("pf_key", profile);
    			file_data.append("templi_code", code);
    			file_data.append("upload_count", upload_count);
    			file_data.append("reserveDt", reserveDt);
    			file_data.append("btn1", btn1);
    			file_data.append("btn2", btn2);
    			file_data.append("btn3", btn3);
    			file_data.append("btn4", btn4);
    			file_data.append("btn5", btn5);
    			file_data.append("senderBox", senderBox);
    			file_data.append("templi_cont", templi_cont);
    			file_data.append("msg", msg);
    			file_data.append("kind", kind);
    			file_data.append("tit", msg.substring(0,35));
    			// 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
    			file_data.append("mst_type1", mst_type1);
    			file_data.append("mst_type2", mst_type2);
    			file_data.append("dhnlurl", $("#dhnl_url").val());//에디터 링크 URL
    			file_data.append("bizurl", $("#biz_url").val()); //에디터코드
    			file_data.append("html", $('#summernote').summernote('code')); //에디터내용
    			file_data.append("link_type", link_type); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2020-09-16 추가
    			file_data.append("psd_code", $("#psd_code").val()); //스마트전단 코드
    			file_data.append("psd_url", $("#psd_url").val()); //스마트전단 링크 URL
    			file_data.append("pcd_code", $("#pcd_code").val()); //스마트쿠폰 코드
    			file_data.append("pcd_type", $("pcd_type").val()); //스마트쿠폰 타입
    			file_data.append("pcd_url", $("#pcd_url").val()); //스마트쿠폰 링크 URL
                file_data.append("home_code", $("#home_code").val()); //스마트홈 코드
    			file_data.append("home_url", $("#home_url").val()); //스마트홈 링크 URL
                file_data.append("order_url", order_url); //스마트홈 링크 URL
                file_data.append("order_btn", $("#hdn_order_btn").val());
                <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
                file_data.append("voucher", "V"); //스마트쿠폰 링크 URL
                <? } else if ($mem->bonus_deposit > 0){?>
                file_data.append("voucher", "B");
                <? } ?>

    			$.ajaxSettings.traditional = true;
    			//alert(couponid);
    			$.ajax({
    				url: "/dhnbiz/sender/talk_v3/adv_send",
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
    						$('#navigateURL').val("");
    						var code = json['code'];
    						var message = json['message'];
    						if(code == "success") {
    							//$('#navigateURL').val(document.location.href);
    							$('#navigateURL').val(nextNavigateURL);
    							//alert($('#navigateURL').val());
    							//$(".content").html("발송 요청되었습니다.");
    							//$('#myModal').modal({backdrop: 'static'});
    							//$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
    							//$(document).unbind("keyup").keyup(function (e) {
    							//	 var code = e.which;
    							//	 if (code == 13) {
    							//		  $(".enter").click();
    							//	 }
    							//});
    							alert('발송 요청되었습니다.');
    							document.location.href = $('#navigateURL').val();
    							return;
    						} else {
    							$(".content").html("발송 실패되었습니다.<br/>"+message);
    							//$('#myModal').modal({backdrop: 'static'});
    							//$(document).unbind("keyup").keyup(function (e) {
    							//	 var code = e.which;
    							//	 if (code == 13) {
    							//		  $(".enter").click();
    							//	 }
    							//});
    							modal_open('myModal');
    							return false;
    						}
    				},
    				error: function () {
    						$(".content").html("처리되지 않았습니다.");
    						//$('#myModal').modal({backdrop: 'static'});
    						//$(document).unbind("keyup").keyup(function (e) {
    						//	var code = e.which;
    						//	if (code == 13) {
    						//		 $(".enter").click();
    						//	}
    						//});
    						modal_open('myModal');
    						return false;
    					}
    			});
    		//} else if ($("#switch_select").is(":checked") && $("input[name=checkOne]:checked").length > 0) {	// 고객 DB에서 그룹 선택
    		//} else if ($("#switch_select").is(":checked")) {	// 고객 DB에서 그룹 선택
    		} else if ($("#switch_select").is(":checked") || $("#switch_pos").is(":checked")) { // 고객 DB에서 그룹 선택 or 포스고객 2021-02-03
    			//alert("고객 DB에서 그룹 선택 or 포스고객"); return;
    			var customer_all_count = 0; //발송건수
    			var customer_filter = "";
    			if ($("#switch_select").is(":checked")) {	// 고객 DB에서 그룹 선택
    				customer_all_count = parseInt($("#groupSendTotal").text()); //발송건수
    				customer_filter = "";

    				//그룹체크의 경우 추가 2020-11-13
    				var groupId = ""; //그룹번호
    				if($("[name=groupAll]").prop("checked")){ //전체고객목록 체크의 경우
    					customer_filter = "groupIdall";
    				}else{
    					var groupId = "";
    					$('input:checkbox[name="groupChk"]:checked').each(function(){ //체크된것만 가져옮
    						if(groupId == ""){
    							groupId = $(this).val();
    						}else{
    							groupId += ", "+ $(this).val();
    						}
    					});
    					//alert("groupId : "+ groupId);
    					customer_filter = "groupChkId"+ groupId;
    				}
    			}else if ($("#switch_pos").is(":checked")) {	// 포스고객 2021-02-03
    				customer_all_count = parseInt($("#posSendTotal").text()); //발송건수
    				customer_filter = "groupPos";
    			}
    			//alert("customer_all_count : "+ customer_all_count +", customer_filter : "+ customer_filter); return;
    			$.ajaxSettings.traditional = true;
    			$.ajax({
    				url: "/dhnbiz/sender/talk_v3/adv_send",
    				type: "POST",
    				data: {
    					"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>",
    					 "tel_number[]":tel_number,"templi_cont":templi_cont,"msg":msg,"kind":kind,
    					"templi_code":code,"pf_key":profile,"senderBox":senderBox,
    					"btn1":btn1,"btn2":btn2,"btn3":btn3,"btn4":btn4,"btn5":btn5,"customer_all_count": customer_all_count,"customer_filter": customer_filter,"reserveDt":reserveDt, "couponid":couponid,
    					"mst_type1" : mst_type1, "mst_type2" : mst_type2,	// 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
    					"dhnlurl": $("#dhnl_url").val(),
    					"bizurl": $("#biz_url").val(),
    					"link_type": link_type, //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2020-09-16 추가
    				    "html": $('#summernote').summernote('code')
    				    ,"psd_code": $("#psd_code").val() //스마트전단 코드
    				    ,"psd_url": $("#psd_url").val() //스마트전단 링크 URL
    					,"pcd_code": $("#pcd_code").val() //스마트쿠폰 코드
    				    ,"pcd_type": $("#pcd_type").val() //스마트쿠폰 타입
    				    ,"pcd_url": $("#pcd_url").val() //스마트쿠폰 링크 URL
                        ,"home_code": $("#home_code").val() //스마트홈 코드
    				    ,"home_url": $("#home_url").val() //스마트홈 링크 URL
                        ,"order_url": order_url //주문하기 링크 URL
                        ,"order_btn": $("#hdn_order_btn").val()
                        <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
                        ,"voucher": "V"
                        <? } else if ($mem->bonus_deposit > 0){?>
                        ,"voucher": 'B'
                        <? } ?>
    				},
    				beforeSend: function () {
    					$('#overlay').fadeIn();
    				},
    				complete: function () {
    					$('#overlay').fadeOut();
    				},
    				success: function (json) {
    					$('#navigateURL').val("");
    					var code = json['code'];
    					var message = json['message'];
    					if (code == "success") {
    						//$('#navigateURL').val(document.location.href);
    						$('#navigateURL').val(nextNavigateURL);
    						//alert($('#navigateURL').val());
    						//$(".content").html("발송 요청되었습니다.");
    						//$('#myModal').modal({backdrop: 'static'});
    						//$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
    						//$(document).unbind("keyup").keyup(function (e) {
    						//	var code = e.which;
    						//	if (code == 13) {
    						//		$(".enter").click();
    						//	}
    						//});
    						alert('발송 요청되었습니다.');
    						document.location.href = $('#navigateURL').val();
    						return;
    					} else {
    						$(".content").html("발송 실패되었습니다.<br/>" + message);
    						//$('#myModal').modal({backdrop: 'static'});
    						//$(document).unbind("keyup").keyup(function (e) {
    						//	var code = e.which;
    						//	if (code == 13) {
    						//		$(".enter").click();
    						//	}
    						//});
    						modal_open('myModal');
    						return false;
    					}
    				},
    				error: function () {
    					$(".content").html("처리되지 않았습니다.");
    					//$('#myModal').modal({backdrop: 'static'});
    					//$(document).unbind("keyup").keyup(function (e) {
    					//	var code = e.which;
    					//	if (code == 13) {
    					//		$(".enter").click();
    					//	}
    					//});
    					modal_open('myModal');
    					return false;
    				}
    			});

    		} else if ($("#switch_customer").is(":checked") && $("span[name=input_phone_no]").length > 0) {	// 연락처 직접 입력
                if ($("#fail_flag").length > 0){
                    var tel_number = new Array();
                    $.ajaxSettings.traditional = true;
                    $.ajax({
                        url: "/dhnbiz/sender/talk_v3/adv_send_fail",
                        type: "POST",
                        data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>",
                        "tel_number[]":tel_number,"templi_cont":templi_cont,"msg":msg,"kind":kind,
                        "templi_code":code,"pf_key":profile,"senderBox":senderBox,
                        "btn1":btn1,"btn2":btn2,"btn3":btn3,"btn4":btn4,"btn5":btn5,"reserveDt":reserveDt, "couponid":couponid,
                        "mst_type1" : mst_type1, "mst_type2" : mst_type2,	// 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
                        "dhnlurl": $("#dhnl_url").val(),
                        "bizurl": $("#biz_url").val(),
                        "link_type": link_type, //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2020-09-16 추가
                        "html": $('#summernote').summernote('code')
                        ,"psd_code": $("#psd_code").val() //스마트전단 코드
                        ,"psd_url": $("#psd_url").val() //스마트전단 링크 URL
                        ,"pcd_code": $("#pcd_code").val() //스마트쿠폰 코드
                        ,"pcd_type": $("#pcd_type").val() //스마트쿠폰 타입
                        ,"pcd_url": $("#pcd_url").val() //스마트쿠폰 링크 URL
                        ,"home_code": $("#home_code").val() //스마트홈 코드
                        ,"home_url": $("#home_url").val() //스마트홈 링크 URL
                        ,"order_url": order_url //주문하기 링크 URL
                        ,"order_btn": $("#hdn_order_btn").val()
                        ,"mid" : $("#fail_flag").val()
                        ,"mtype" : $("#fail_mtype").val()
                        ,"uid" : $("#fail_uid").val()
                        <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
                            ,"voucher": "V"
                            <? } else if ($mem->bonus_deposit > 0){?>
                            ,"voucher": 'B'
                            <? } ?>
                        },
                        beforeSend: function () {
                            $('#overlay').fadeIn();
                        },
                        complete: function () {
                            $('#overlay').fadeOut();
                        },
                        success: function (json) {
                            $('#navigateURL').val("");
                            var code = json['code'];
                            var message = json['message'];
                            if(code == "success") {
                                //$('#navigateURL').val(document.location.href);
                                $('#navigateURL').val(nextNavigateURL);
                                //alert($('#navigateURL').val());
                                //$(".content").html("발송 요청되었습니다.");
                                //$('#myModal').modal({backdrop: 'static'});
                                //$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
                                //$(document).unbind("keyup").keyup(function (e) {
                                //	 var code = e.which;
                                //	 if (code == 13) {
                                //		  $(".enter").click();
                                //	 }
                                //});
                                alert('발송 요청되었습니다.');
                                document.location.href = $('#navigateURL').val();
                                return;
                            } else {
                                $(".content").html("발송 실패되었습니다.<br/>"+message);
                                //$('#myModal').modal({backdrop: 'static'});
                                //$(document).unbind("keyup").keyup(function (e) {
                                //	 var code = e.which;
                                //	 if (code == 13) {
                                //		  $(".enter").click();
                                //	 }
                                //});
                                modal_open('myModal');
                                return false;
                            }
                        },
                        error: function () {
                            $(".content").html("처리되지 않았습니다.");
                            //$('#myModal').modal({backdrop: 'static'});
                            //$(document).unbind("keyup").keyup(function (e) {
                            //	var code = e.which;
                            //	if (code == 13) {
                            //		$(".enter").click();
                            //	}
                            //});
                            modal_open('myModal');
                            return false;
                        }
                    });
                } else {
                    var tel_number = new Array();
                    $("span[name=input_phone_no]").each(function () {
                        //var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
                        var array = $(this).text().replace(/-/gi, "");
                        tel_number.push(array);
                    });

                    $.ajaxSettings.traditional = true;
                    $.ajax({
                        url: "/dhnbiz/sender/talk_v3/adv_send",
                        type: "POST",
                        data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>",
                        "tel_number[]":tel_number,"templi_cont":templi_cont,"msg":msg,"kind":kind,
                        "templi_code":code,"pf_key":profile,"senderBox":senderBox,
                        "btn1":btn1,"btn2":btn2,"btn3":btn3,"btn4":btn4,"btn5":btn5,"reserveDt":reserveDt, "couponid":couponid,
                        "mst_type1" : mst_type1, "mst_type2" : mst_type2,	// 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
                        "dhnlurl": $("#dhnl_url").val(),
                        "bizurl": $("#biz_url").val(),
                        "link_type": link_type, //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2020-09-16 추가
                        "html": $('#summernote').summernote('code')
                        ,"psd_code": $("#psd_code").val() //스마트전단 코드
                        ,"psd_url": $("#psd_url").val() //스마트전단 링크 URL
                        ,"pcd_code": $("#pcd_code").val() //스마트쿠폰 코드
                        ,"pcd_type": $("#pcd_type").val() //스마트쿠폰 타입
                        ,"pcd_url": $("#pcd_url").val() //스마트쿠폰 링크 URL
                        ,"home_code": $("#home_code").val() //스마트홈 코드
                        ,"home_url": $("#home_url").val() //스마트홈 링크 URL
                        ,"order_url": order_url //주문하기 링크 URL
                        ,"order_btn": $("#hdn_order_btn").val()
                        <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
                            ,"voucher": "V"
                            <? } else if ($mem->bonus_deposit > 0){?>
                            ,"voucher": 'B'
                            <? } ?>
                        },
                        beforeSend: function () {
                            $('#overlay').fadeIn();
                        },
                        complete: function () {
                            $('#overlay').fadeOut();
                        },
                        success: function (json) {
                            $('#navigateURL').val("");
                            var code = json['code'];
                            var message = json['message'];
                            if(code == "success") {
                                //$('#navigateURL').val(document.location.href);
                                $('#navigateURL').val(nextNavigateURL);
                                //alert($('#navigateURL').val());
                                //$(".content").html("발송 요청되었습니다.");
                                //$('#myModal').modal({backdrop: 'static'});
                                //$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
                                //$(document).unbind("keyup").keyup(function (e) {
                                //	 var code = e.which;
                                //	 if (code == 13) {
                                //		  $(".enter").click();
                                //	 }
                                //});
                                alert('발송 요청되었습니다.');
                                document.location.href = $('#navigateURL').val();
                                return;
                            } else {
                                $(".content").html("발송 실패되었습니다.<br/>"+message);
                                //$('#myModal').modal({backdrop: 'static'});
                                //$(document).unbind("keyup").keyup(function (e) {
                                //	 var code = e.which;
                                //	 if (code == 13) {
                                //		  $(".enter").click();
                                //	 }
                                //});
                                modal_open('myModal');
                                return false;
                            }
                        },
                        error: function () {
                            $(".content").html("처리되지 않았습니다.");
                            //$('#myModal').modal({backdrop: 'static'});
                            //$(document).unbind("keyup").keyup(function (e) {
                            //	var code = e.which;
                            //	if (code == 13) {
                            //		$(".enter").click();
                            //	}
                            //});
                            modal_open('myModal');
                            return false;
                        }
                    });

                }
    		} else if ($("#switch_friend").is(":checked") && $("input[name=friend_yn]:checked").length > 0) {	// 플러스 친구 여부 선택  2019-07-09 추가
    			var customer_all_count = parseInt($("#friend_yn_tel_count").text());
    			var customer_filter = "";
    			$("input[name=friend_yn]:checked").each(function() {
    				customer_filter += $(this).val();
    			});

    			$.ajaxSettings.traditional = true;
    			$.ajax({
    				url: "/dhnbiz/sender/talk_v3/adv_send",
    				type: "POST",
    				data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>",
    						  "tel_number[]":tel_number,"templi_cont":templi_cont,"msg":msg,"kind":kind,
    					"templi_code":code,"pf_key":profile,"senderBox":senderBox,
    					"btn1":btn1,"btn2":btn2,"btn3":btn3,"btn4":btn4,"btn5":btn5,"customer_all_count": customer_all_count,"customer_filter": customer_filter,"reserveDt":reserveDt, "couponid":couponid,
    					"mst_type1" : mst_type1, "mst_type2" : mst_type2,	// 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
    					"dhnlurl": $("#dhnl_url").val(),
    				    "bizurl": $("#biz_url").val(),
    					"link_type": link_type, //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2020-09-16 추가
    				    "html": $('#summernote').summernote('code')
    				    ,"psd_code": $("#psd_code").val() //스마트전단 코드
    				    ,"psd_url": $("#psd_url").val() //스마트전단 링크 URL
    					,"pcd_code": $("#pcd_code").val() //스마트쿠폰 코드
    				    ,"pcd_type": $("#pcd_type").val() //스마트쿠폰 타입
    				    ,"pcd_url": $("#pcd_url").val() //스마트쿠폰 링크 URL
                        ,"home_code": $("#home_code").val() //스마트홈 코드
    				    ,"home_url": $("#home_url").val() //스마트홈 링크 URL
                        ,"order_url": order_url //주문하기 링크 URL
                        ,"order_btn": $("#hdn_order_btn").val()
                        <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
                        ,"voucher": "V"
                        <? } else if ($mem->bonus_deposit > 0){?>
                        ,"voucher": 'B'
                        <? } ?>
    				},
    				beforeSend: function () {
    					$('#overlay').fadeIn();
    				},
    				complete: function () {
    					$('#overlay').fadeOut();
    				},
    				success: function (json) {
    					$('#navigateURL').val("");
    					var code = json['code'];
    					var message = json['message'];
    					if (code == "success") {
    						//$('#navigateURL').val(document.location.href);
    						$('#navigateURL').val(nextNavigateURL);
    						//alert($('#navigateURL').val());
    						//$(".content").html("발송 요청되었습니다.");
    						//$('#myModal').modal({backdrop: 'static'});
    						//$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
    						//$(document).unbind("keyup").keyup(function (e) {
    						//	var code = e.which;
    						//	if (code == 13) {
    						//		$(".enter").click();
    						//	}
    						//});
    						alert('발송 요청되었습니다.');
    						document.location.href = $('#navigateURL').val();
    						return;
    					} else {
    						$(".content").html("발송 실패되었습니다.<br/>" + message);
    						//$('#myModal').modal({backdrop: 'static'});
    						//$(document).unbind("keyup").keyup(function (e) {
    						//	var code = e.which;
    						//	if (code == 13) {
    						//		$(".enter").click();
    						//	}
    						//});
    						modal_open('myModal');
    						return false;
    					}
    				},
    				error: function () {
    					$(".content").html("처리되지 않았습니다.");
    					//$('#myModal').modal({backdrop: 'static'});
    					//$(document).unbind("keyup").keyup(function (e) {
    					//	var code = e.which;
    					//	if (code == 13) {
    					//		$(".enter").click();
    					//	}
    					//});
    					modal_open('myModal');
    					return false;
    				}
    			});

    		}
        }

	}

	//테스트 발송 클릭시
	function test_send() {
		var st = sTime();
		var date = new Date(st);
		//var hour = parseInt(date.getHours());
		var hour = numPad(date.getHours(), 2);
		//hour = numPad("7", 2);
		var minute = numPad(date.getMinutes(), 2);
		var time = hour +""+ minute;
        var home_chk1 = $("#chk_a").is(":checked");//스마트전단
        var home_chk2 = $("#chk_b").is(":checked");//에디터
        var home_chk3 = $("#chk_c").is(":checked");//스마트쿠폰
        var home_chk4 = $("#chk_d").is(":checked");//직접입력
        var order_url = "";
        if(home_chk4){
            order_url = $("#hdn_order_url").val();
        }
		//alert("date : "+ date +"\n"+"hour : "+ hour +"\n"+"minute : "+ minute +"\n"+"time : "+ time);
		<? if($this->member->item('mem_level') < 100){ ?>
		//if(time < "0800" || time > "2000"){
		//	alert("발송시간은 8:00 ~ 20:00시 사이로 발송할 수 있습니다.");
		//	return;
		//}
		<? } ?>
		// 2020.03.30 트라이얼 요청으로 후불제 마트 발송시 confirm pop content_msg 수정
		var mem_pay_type = "<?=$mem->mem_pay_type ?>";
		var emptyVar = isEmptyVariable(); //템플릿 변수값 체크
		//alert("emptyVar : "+ emptyVar); return;
		var sendPhoneCount = 0;
		// var link_type = document.getElementById("link_type").value; //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
        var link_type_cnt = $("[name='link_type']").length;
        var link_type = "";
        if(link_type_cnt>1){
            if(buttonArr.indexOf("smart")>-1&&buttonArr.indexOf("editor")>-1){
                link_type = "smartEditor";
            }else{
                if(buttonArr.indexOf("smart")>-1){
                    link_type = "smart";
                }else if(buttonArr.indexOf("editor")>-1){
                    link_type = "editor";
                }else if(buttonArr.indexOf("coupon")>-1){
                    link_type = "coupon";
                }else{
                    link_type = "self";
                }
            }
        }else{
            if(link_type_cnt>0){
                link_type = document.getElementById("link_type0").value;
            }
        }

        if(link_type=="home"&&home_chk1==false&&home_chk2==false&&home_chk3==false&&home_chk4==false){
            alert("스마트홈 기능을 선택하세요."); return;
        }

		var psd_url = document.getElementById("psd_url").value; //스마트전단 링크 URL
		var psd_code = document.getElementById("psd_code").value; //스마트전단 code
		var templi_id = document.getElementById("templi_id").value; //템플릿번호
		var summ = $('#summernote').summernote('code'); //에디터내용
		//alert("link_type : "+ link_type +", psd_url : "+ psd_url +", psd_code : "+ psd_code); return;
		if($("#templi_code").val() == null) {
			alert("템플릿을 먼저 선택해주세요."); return;
		}
		if (emptyVar !== true) {
			if(emptyVar == 1){
				alert("날짜를 입력해주세요.");
			}else if(emptyVar == 2){
				alert("행사내용을 입력해주세요.");
			}else{
				alert("템플릿 "+ (emptyVar+1) +"번째 변수를 입력해주세요.");
			}
			$("textarea[name=var_name]")[emptyVar].focus();
			return;
		}
		// if ($('#lms_select').prop('checked') && $("#lms").val().replace(/ /gi, "") == "") {
		// 	alert("2차 발송 내용을 입력해주세요.");
		// 	$("#lms").focus();
		// 	return;
		// }
		if (templi_charCount() > 1000) {
			alert("알림톡은 내용을 1,000자 이내로 입력하여야 합니다.");
			$("textarea[name=var_name]")[0].focus();
			return;
		}
		if(get_btn_cnt > 0){ //링크버튼형
			//alert("psd_code : "+ psd_code);
			if((link_type == "smart" || link_type == "smartEditor"|| (link_type == "home" && home_chk1)) && psd_code == ""){ //스마트전단의 경우
				alert("스마트전단을 선택하세요.");
				smart_page('1'); //스마트전단 불러오기 모달창 열기
				return false;
			}else if((link_type == "coupon" || (link_type == "home" && home_chk3))&& pcd_code == ""){ //스마트쿠폰의 경우
				alert("스마트쿠폰을 선택하세요.");
				coupon_page('1'); //스마트쿠폰 불러오기 모달창 열기
				return false;
			}
		}
		if ((link_type == "editor" || link_type == "smartEditor" || (link_type == "home" && home_chk2)) && (summ == '' || summ == '<p><br></p>' || summ == '<p><span style="font-size: 18px;">﻿</span><br></p>')){ //에디터의 경우
			alert("에디터 내용을 입력하세요.");
			//$("#link_type").focus();
			window.scroll(0, getOffsetTop(document.getElementById("id_STEP3"))); //STEP 3 영역으로 이동
			return;
		}
        if (link_type == "home" && home_chk4 && order_url == ''){ //주문하기의 경우
			alert("주문하기 url을 입력하세요.");
			//$("#link_type").focus();
			window.scroll(0, getOffsetTop(document.getElementById("id_STEP3"))); //STEP 3 영역으로 이동
			return;
		}
		if (chkword_lmsCount() > 2000) {
			alert("2차 발송 내용은 2,000byte(한글 1,000자)이내로\n입력하여야 합니다.");
			$("#lms").focus();
			return;
		}
		 if ($("span[name=test_input_phone_no]").length == 0) {	// 연락처 직접 입력
			alert("테스트 발송 연락처를 입력 후 [전화번호 추가] 버튼을 클릭하세요.");
			$("#test_add-todo-input").focus();
			return;
		}
		if(1 == 1){
			var check = true;

			// 2019.11.15 추가 시작(이수환)
			$.ajax({
				url: "/dhnbiz/sender/talk_v3/equal_send_mem_id",
				type: "POST",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
						   "senderMem_id" : <?=$this->member->item("mem_id") ?>},
				success: function (json) {
					var senderCheck = json['senderCheck'];
					if (senderCheck == "F") {
	    				check = false;
	    				$(".content").html("알림톡 작성한 계정과 발송계정이 틀립니다.<br>발송계정 다시 로그인 후 다시 작성하세요.");
	    				//$('#myModal').modal({backdrop: 'static'});
	    				//$(document).unbind("keyup").keyup(function (e) {
	    				//	var code = e.which;
	    				//	if (code == 13) {
	    				//		$(".enter").click();
	    				//	}
	    				//});
						modal_open('myModal');
						return false;
					} else {
						check = true;
						<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
						//var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
						var lms_header_temp = $("#span_adv").text() + $("#companyName").val();
						var lms_footer_temp = "";
						if($("#kakotalk_link_text").text() == "") {
							lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
						} else {
							lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
						}
						//var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

						var lms_msg = lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;

						$.ajax({
							url: "/dhnbiz/sender/check_special_char",
							type: "POST",
							data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
								   "lmsText" : lms_msg, "lmsTitle": "2차 발송"},
							success: function (json) {
								var checkMode = json['checkMode'];
								var tatalBytes = json['tatalBytes'];
								var checkMsg = json['checkMsg'];

								if (checkMode == "0") {
				    				$(".content").html(checkMsg);
				    				//$('#myModal').modal({backdrop: 'static'});
				    				check = false;
									modal_open('myModal');
									return false;
								}

								if(check == true) {
									var btn_no = 0;
									var pattern = new RegExp("^(?:http(s)?:\\/\\/)?[\\wㄱ-힣.-]+(?:\\.[\\w\\.-]+)+[\\wㄱ-힣\\-\\._~:/?#[\\]@!\\$&\'\\(\\)\\*\\+,;=.]+$", "gm");
									//alert("test_send() > link_type : "+ link_type);
					    			$("div[name='field-data']").each(function () {
					    				btn_no++;
										var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
					    				if (btn_type != undefined) {
					    					var focus;
					    					var btn_url1 = $(this).find(document.getElementsByName("btn_url1")).val() || "";
											var btn_url2 = $(this).find(document.getElementsByName("btn_url2")).val() || "";
											if(btn_url1 != ""){
                                                if (btn_type == "WL") {
                                                    if(btn_url1.indexOf("https://")>-1){
                                                        check = false;
                                                        focus = $(this).find(document.getElementsByName("btn_url1"));
                                                        alert(btn_no+"번째 버튼 링크 URL 주소가 올바르지 않습니다.");
                                                        return false;
                                                    }
                                                }
												//if(pattern.test(btn_url1)) {
													if (btn_url1.toLowerCase().indexOf("http://") == 0 || btn_url1.toLowerCase().indexOf("https://") == 0) {
													} else {
														btn_url1 = "http://" + btn_url1;
														$(this).find(document.getElementsByName("btn_url1")).val(btn_url1);
													}
												//}else{
												//	check = false;
					    						//	focus = $(this).find(document.getElementsByName("btn_url1"));
												//	alert(btn_no+"번째 버튼 링크 URL 주소가 올바르지 않습니다.");
												//	focus.focus();
												//	return false;
												//}
											}
											if(btn_url2 != ""){
												if (btn_url2.toLowerCase().indexOf("http://") == 0 || btn_url2.toLowerCase().indexOf("https://") == 0) {
												} else {
													btn_url2 = "http://" + btn_url2;
													$(this).find(document.getElementsByName("btn_url2")).val(btn_url2);
												}
											}
					    					if (btn_type == "WL") {
					    						if (btn_url1 == "" || !checkURL(btn_url1)) {
					    							check = false;
					    							focus = $(this).find(document.getElementsByName("btn_url1"));
													if(link_type == "smart" && btn_no == 1){
														alert("스마트전단을 선택하세요.");
														smart_page('1'); //스마트전단 불러오기 모달창 열기
													}else{
														alert(btn_no+"번째 버튼 링크가 입력되지 않았거나 URL 주소가 올바르지 않습니다.");
														focus.focus();
													}
													return false;
					    						} else if (btn_url2 == "" || !checkURL(btn_url2)) {
													if (btn_url1 == "" || !checkURL(btn_url1)) {
														check = false;
														focus = $(this).find(document.getElementsByName("btn_url1"));
														alert(btn_no+"번째 버튼 링크를 입력해주세요.");
														focus.focus();
														return false;
													}else{
														$(this).find(document.getElementsByName("btn_url2")).val(btn_url1);
													}
					    						}
					    					} else if (btn_type == "AL") {
					    						if (btn_url1 == "" || !checkURL(btn_url1)) {
					    							check = false;
					    							focus = $(this).find(document.getElementsByName("btn_url31"));
					    							$(".content").html("앱링크 버튼의 Android 버튼링크를 입력해주세요.");
					    							$("#myModal").modal('show');
					    							$('#myModal').on('hidden.bs.modal', function () {
					    								focus.focus();
					    							});
					    						} else if (btn_url2 == "" || !checkURL(btn_url2)) {
					    							check = false;
					    							focus = $(this).parent().find(document.getElementsByName("btn_url32"));
					    							$(".content").html("앱링크 버튼의 iOS 버튼링크를 입력해주세요.");
					    							$("#myModal").modal('show');
					    							$('#myModal').on('hidden.bs.modal', function () {
					    								focus.focus();
					    							});
					    						}
					    					}
					    				}
					    			});
								}
								//alert("Test send 1"); return false;
								if (check == true) {
									if ($('#lms_select').prop('checked') && $("#lms").val().replace(/ /gi, "") == "") {
										$("#lms_select").attr('checked', false);
										$("#hidden_fields_sms").hide();
									// alert("2차 발송 내용을 입력해주세요.");
									// $("#lms").focus();
									// return;
										}
								}

								if (check == true) {
									var _2ndflag = "N";
									if ($('#lms_select').prop('checked'))
										_2ndflag = "Y";

									$.ajax({
										url: "/dhnbiz/sender/coin",
										type: "POST",
										data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
								 		       "2ndflang":_2ndflag
											},
										success: function (json) {
											coin = json['coin'];
											var limit = parseInt(json['limit']);
											var sent = parseInt(json['sent']);
                                            var bcoin = json.bcoin;
											var limit_msg = "";
											// if(limit > 0 && sent >= limit) { showLimitOver("("+sent+"/"+limit+")"); return; }
											// if(limit > 0) {
											// 	limit_msg = "<div class='modal_info_row'><label>금일 발송가능</label>" + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</div>";
											// }

											var kakao = Math.floor((Number(coin) + Number(bcoin)) / Number(at_price));
											var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/, '0');

											check_resend_method();
											// 재발신 가능 건수를 산정합니다.
											var resends = (charge_type > 0) ? Math.floor((Number(coin) + Number(bcoin)) / Number(charge_type)) : 0;
											var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');

											var row_index = $("span[name=test_input_phone_no]").length;

											if ((Number(coin) + Number(bcoin)) - (Number(charge_type) * Number(row_index)) < 0) {
												$(".content").html("잔액이 부족합니다.<BR>잔액:"+coin+"<BR>예상금액:"+(Number(charge_type) * Number(row_index)));
												//$('#myModal').modal({backdrop: 'static'});
												//$(document).unbind("keyup").keyup(function (e) {
												//	var code = e.which;
												//	if (code == 13) {
												//		$(".enter").click();
												//	}
												//});
												modal_open('myModal');
												return false;
											} else {
												// 2020.03.30 트라이얼 요청으로 후불제 마트 발송시 confirm pop content_msg 수정
												//if (mem_pay_type === "A") {
													var content_msg = "";
													var s2nd_msg = "";
													var send_time = "즉시 발송";

													if (ms!="" && ($('#lms').val().replace(/ /gi, "") != "") && $("#lms_select").is(":checked") == true) {
														s2nd_msg = " + 2차문자";
													}

													content_msg = "<div class='modal_info_row'><label>발송 종류</label>알림톡" + s2nd_msg + "</div>";
													content_msg += "<div class='modal_info_row'><label>발송 시간</label>" + send_time + "</div>";
													content_msg += "<div class='modal_info_row'><label>발송 건수</label>" + row_index.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "건</div>";
                                                    <?if($mem->mrg_recommend_mem_id != "962" && $this->member->item('mem_id') != '962'){?>
													if (ms!="" && ($('#lms').val().replace(/ /gi, "") != "") && $("#lms_select").is(":checked") == true) {	// 2차문자 발송
														//content_msg += "<div class='modal_info_row'><label>예상 발송 금액</label>"+ ((Number(charge_type) * Number(row_index)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원</div>";
														content_msg += "<div class='modal_info_row'><label>알림톡 예상 금액</label>"+ ((Number(at_price) * Number(row_index)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원(" + row_index + " X " + at_price + ")</div>";
														content_msg += "<div class='modal_info_row'><label>2차문자 예상 금액</label>"+ ((Number(charge_type) * Number(row_index)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원(" + row_index + " X " + charge_type + ")</div>";
													} else {	// 2차문자 미발송
														content_msg += "<div class='modal_info_row'><label>예상 발송 금액</label>"+ ((Number(at_price) * Number(row_index)).toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "원(" + row_index + " X " + at_price + ")</div>";
													}
                                                    <?}?>
													$(".content").html(limit_msg + content_msg + "<p class='modal_info_confirm'>테스트 발송하시겠습니까?</p>");

												//} else {
    											//	var content_msg = "알림톡 발송 가능 건수 : " + k + "건";
    											//	if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
    											//		content_msg += "<br/>" + "2차문자 발송 가능 건수 : " + resend_cnt + "건";
    											//	}
    											//	content_msg += "<br/><br/>" + "알림톡 발송 예상 금액 : " + (Number(at_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + at_price + ")";
    											//	if(ms!="" && $('#lms').val().replace(/ /gi, "") != "") {
    											//		content_msg += "<br/>" + "2차문자 발송 예상 금액 : " + (Number(charge_type) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + row_index + ")";
    											//	}
    											//	$(".content").html(limit_msg + content_msg + "<br/><br/>테스트 발송하시겠습니까? (발송할 건수 : " + row_index + "건)");
												//}

												//$("#myModalTest").modal({backdrop: 'static'});
												//$("#myModalTest").unbind("keyup").keyup(function (e) {
												//	var code = e.which;
												//	if (code == 13) {
												//		$(".test-send").click();
												//	}
												//});
												//$(".test-send").click(function () {
												//	$("#myModalTest").modal('hide');
												//});

												modal_open('myModalTest');
											}
										}
									});
								}
							}
						});
					}
				},
    			error: function () {
    				check = false;
    				$(".content").html("시스템 오류로 처리되지 않았습니다.");
    				//$('#myModal').modal({backdrop: 'static'});
    				//$(document).unbind("keyup").keyup(function (e) {
    				//	var code = e.which;
    				//	if (code == 13) {
    				//		$(".enter").click();
    				//	}
    				//});
					modal_open('myModal');
					return false;
    			}
			});
			// 2019.11.15 추가 끝(이수환)
		}
	}

	function test_move() {
        check_shop2();
		var templi_cont = getTempli();
		var couponid = $("#couponid").val();
		var code = document.getElementById("templi_code").value;
		var profile = document.getElementById("pf_key").value;
		var kind = document.getElementById("kind").value;
		var kind = ms;
		var lms_header_temp = "";
		var lms_footer_temp = "";
		var msg = "";
		senderBox = $('#sms_sender').val();
        var home_chk4 = $("#chk_d").is(":checked");//직접입력
        var order_url = "";
        if(home_chk4){
            order_url = $("#hdn_order_url").val();
        }
		if ($("input:checkbox[id='lms_select']").is(":checked") == true && $("#lms").val().trim() != "") {
			<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
			//lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
			lms_header_temp = $("#span_adv").text() + $("#companyName").val();
			if($("#kakotalk_link_text").text() == "") {
				lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
			} else {
				lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
			}
			//lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

    		msg = lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
		}

		var reserveDt = "00000000000000";
		var btn = [];
		for (var b = 1; b <= 5; b++) {
			btn[b] = new Array();
		}
		var obj = [];
		var btn_num = 1;
		//alert("test_move > 1");
		$("div[name='field-data']").each(function () {
			var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
			if (btn_type != undefined) {
				obj[btn_num] = new Object();
				obj[btn_num].type = btn_type;
				obj[btn_num].name = $(this).find(document.getElementsByName("btn_name")).val();
				if (btn_type == "WL") {
					obj[btn_num].url_mobile = $(this).find(document.getElementsByName("btn_url1")).val();
					if ($(this).find(document.getElementsByName("btn_url2")).val() != undefined) {
						obj[btn_num].url_pc = $(this).find(document.getElementsByName("btn_url2")).val();
					}
				} else if (btn_type == "AL") {
					obj[btn_num].scheme_android = $(this).find(document.getElementsByName("btn_url1")).val().trim();
					obj[btn_num].scheme_ios = $(this).find(document.getElementsByName("btn_url2")).val().trim();
				}
				btn[btn_num].push(obj[btn_num]);
				btn_num++;
			}
		});
		//alert("test_move > 2");
		var btn1 = JSON.stringify(btn[1]);
		var btn2 = JSON.stringify(btn[2]);
		var btn3 = JSON.stringify(btn[3]);
		var btn4 = JSON.stringify(btn[4]);
		var btn5 = JSON.stringify(btn[5]);

		// 2019.01.31. 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
		var mst_type1 = '';
		var mst_type2 = '';
		mst_type1 = 'at';
		if(msg != '' && $("input:checkbox[id='lms_select']").is(":checked") == true) { //2차 문자가 있는 경우
			//웹(A)LMS : wa, 웹(A)SMS : was, 웹(A) MMS : wam, 웹(B)LMS : wb, 웹(B)SMS : wbs, 웹(B) : wbm, 폰문자: phn
			var temp_mst_kind = "<?= $mem->mem_2nd_send ?>";
			var lms_len = getByteLength($('#lms'));
			if(temp_mst_kind == "GREEN_SHOT") { //웹(A)
				if(lms_len <= 90){
					mst_type2 = "wcs";
				}else{
					mst_type2 = "wa";
				}
			} else if (temp_mst_kind == "NASELF"){ //웹(B)
				if(lms_len <= 90){
					mst_type2 = "wbs";
				}else{
					mst_type2 = "wb";
				}
			} else if (temp_mst_kind == "SMART"){ //웹(C)
				if(lms_len <= 90){
					mst_type2 = "wcs";
				}else{
					mst_type2 = "wc";
				}
			} else {
				mst_type2 = temp_mst_kind;
			}
		}
		<? if($this->member->item("mem_userid") == "dhn"){ ?>
		//alert("resend_type : "+ resend_type +", mst_type1 : "+ mst_type1 +", mst_type2 : "+ mst_type2 +", getByteLength : "+ getByteLength($('#lms'))); return;
		<? } ?>

		// var link_type = $("#link_type").val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
        var link_type_cnt = $("[name='link_type']").length;
        var link_type = "";
        if(link_type_cnt>1){
            if(buttonArr.indexOf("smart")>-1&&buttonArr.indexOf("editor")>-1){
                link_type = "smartEditor";
            }else{
                if(buttonArr.indexOf("smart")>-1){
                    link_type = "smart";
                }else if(buttonArr.indexOf("editor")>-1){
                    link_type = "editor";
                }else if(buttonArr.indexOf("coupon")>-1){
                    link_type = "coupon";
                }else{
                    link_type = "self";
                }
            }
        }else{
            if(link_type_cnt>0){
                link_type = document.getElementById("link_type0").value;
            }
        }
		//alert("link_type : "+ link_type +", psd_code : "+ $("#psd_code").val() +", psd_url : "+ $("#psd_url").val()); return;
		if ($("span[name=test_input_phone_no]").length > 0) {
			var tel_number = new Array();
			$("span[name=test_input_phone_no]").each(function () {
				//var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
				var array = $(this).text().replace(/-/gi, "");
				tel_number.push(array);
			});

			$.ajaxSettings.traditional = true;
			$.ajax({
				url: "/dhnbiz/sender/talk_v3/adv_send",
				type: "POST",
				data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>",
					"tel_number[]":tel_number,"templi_cont":templi_cont,"msg":msg,"kind":kind,
					"templi_code":code,"pf_key":profile,"senderBox":senderBox,
					"btn1":btn1,"btn2":btn2,"btn3":btn3,"btn4":btn4,"btn5":btn5,"reserveDt":reserveDt, "couponid":couponid,
					"mst_type1" : mst_type1, "mst_type2" : mst_type2	// 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
					,"dhnlurl": $("#dhnl_url").val() //에디터 링크 URL
				    ,"bizurl": $("#biz_url").val() //에디터코드
				    ,"html": $('#summernote').summernote('code') //에디터내용
					,"link_type": link_type //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
				    ,"psd_code": $("#psd_code").val() //스마트전단 코드
				    ,"psd_url": $("#psd_url").val() //스마트전단 링크 URL
				    ,"pcd_code": $("#pcd_code").val() //스마트쿠폰 코드
				    ,"pcd_type": $("#pcd_type").val() //스마트쿠폰 타입
				    ,"pcd_url": $("#pcd_url").val() //스마트쿠폰 링크 URL
                    ,"home_code": $("#home_code").val() //스마트홈 코드
				    ,"home_url": $("#home_url").val() //스마트홈 링크 URL
                    ,"order_url": order_url //주문하기 링크 URL
                    ,"order_btn": $("#hdn_order_btn").val()
                    <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
                    ,"voucher": "V"
                    <? } else if ($mem->bonus_deposit > 0){?>
                    ,"voucher": 'B'
                    <? } ?>
				},
				beforeSend: function () {
					$('#overlay').fadeIn();
				},
				complete: function () {
					$('#overlay').fadeOut();
				},
				success: function (json) {
						//$('#navigateURL').val("");
						var code = json['code'];
						var message = json['message'];
						if(code == "success") {
							//$('#navigateURL').val(document.location.href);
							//$('#navigateURL').val(nextNavigateURL);
							//alert($('#navigateURL').val());
							$(".content").html("테스트 발송 완료되었습니다.");
							//$('#myModal').modal({backdrop: 'static'});
							////$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
							//$(document).unbind("keyup").keyup(function (e) {
							//	 var code = e.which;
							//	 if (code == 13) {
							//		  $(".enter").click();
							//	 }
							//});
							modal_open('myModal');
						} else {
							$(".content").html("테스트 발송 실패되었습니다.<br/>"+message);
							//$('#myModal').modal({backdrop: 'static'});
							//$(document).unbind("keyup").keyup(function (e) {
							//	 var code = e.which;
							//	 if (code == 13) {
							//		  $(".enter").click();
							//	 }
							//});
							modal_open('myModal');
							return false;
						}
				},
				error: function () {
					$(".content").html("처리되지 않았습니다.");
					//$('#myModal').modal({backdrop: 'static'});
					//$(document).unbind("keyup").keyup(function (e) {
					//	var code = e.which;
					//	if (code == 13) {
					//		$(".enter").click();
					//	}
					//});
					modal_open('myModal');
					return false;
				}
			});
		}
	}

	//알림톡 글자수 제한 - 템플릿 내용
	function templi_chk() {
		<?if ($tpl) {?>
		var limit_length = 1000;
		var dumyTempli_cont = $("#dumy_templi_cont").text();
		var temp = dumyTempli_cont.split("#{");
		var temp2 = new Array();
		var returnTempli_cont = dumyTempli_cont;
        // 2021-09-02 부가정보, 광고 정보 알림톡  2줄 추가
		var tpl_extra = $("#tpl_extra").val();
		var tpl_ad = $("#tpl_ad").val();

		for (var i = 0; i < temp.length; i++) {
			if (i == 0) {
				returnTempli_cont = temp[i];
			} else {
				var varsplit = temp[i].split("}")
				//var varName = "#{" + varsplit[0] + "}";

				//var var_name = $("input[name=var_name]")[(i-1)].value;
				var var_name = $("textarea[name^='var_name']")[(i-1)].value;
				//alert(var_name);
				//if (var_name == "") {
				//	var_name = varName;
				//}
				returnTempli_cont = returnTempli_cont + var_name;
				returnTempli_cont = returnTempli_cont + varsplit[1];
			}
		}

        // 2021-09-02 부가정보, 광고 정보 2줄 추가
		if (tpl_extra != "") {
			returnTempli_cont = returnTempli_cont + "\n" + tpl_extra;
		}
		if (tpl_ad != "") {
			returnTempli_cont = returnTempli_cont + "\n" + tpl_ad;
		}

		var msg_length = returnTempli_cont.length;

		var limit_length = 1000;
		//var msg_length = $("#templi_cont").val().length;
		if (msg_length <= limit_length) {
			$("#type_num").html(msg_length);
		/* 2020-10-20 주석처리함
		} else if (msg_length > limit_length) {
			$(".content").html("템플릿 내용을 1000자 이내로 입력해주세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				//$("input[name=var_name]")[0].focus();
				$("textarea[name=var_name]")[0].focus();
			});
			$("#type_num").html(msg_length);
			//var cont = $("#templi_cont").val();
			//var cont_slice = cont.slice(0,1000);
			//$("#templi_cont").val(cont_slice);
			//$("#type_num").html(1000);
			// return;
		*/
		} else {
			$("#type_num").html('<span class="noted_red">※ 메시지 내용이 '+ comma(limit_length) +'자가 넘어 발송할 수 없습니다.</span>'+ comma(msg_length));
		}
		<?}?>
	}

	//알림톡 글자수 제한 - 템플릿 내용
	function templi_charCount() {
		var dumyTempli_cont = $("#dumy_templi_cont").text();
		var temp = dumyTempli_cont.split("#{");
		var temp2 = new Array();
        // 2021-09-02 부가정보, 광고 정보 알림톡  2줄 추가
		var tpl_extra = $("#tpl_extra").val();
		var tpl_ad = $("#tpl_ad").val();
		var returnTempli_cont = dumyTempli_cont;
		for (var i = 0; i < temp.length; i++) {
			if (i == 0) {
				returnTempli_cont = temp[i];
			} else {
				var varsplit = temp[i].split("}")
				//var varName = "#{" + varsplit[0] + "}";
				//var var_name = $("input[name=var_name]")[(i-1)].value;
				var var_name = $("textarea[name^='var_name']")[(i-1)].value;
				//alert(var_name);
				//if (var_name == "") {
				//	var_name = varName;
				//}
				returnTempli_cont = returnTempli_cont + var_name;
				returnTempli_cont = returnTempli_cont + varsplit[1];
			}
		}

        // 2021-09-02 부가정보, 광고 정보 2줄 추가
		if (tpl_extra != "") {
			returnTempli_cont = returnTempli_cont + "\n" + tpl_extra;
		}
		if (tpl_ad != "") {
			returnTempli_cont = returnTempli_cont + "\n" + tpl_ad;
		}

		return returnTempli_cont.length;
	}

	// 템플릿 선택 버튼
	function btnSelectTemplate() {
		var count = '';
		//console.log('count', count);
		if('' == "none") {
			$('#template_danger').modal('show');
		} else {
			$("#template_select").modal({backdrop: 'static'});
			$('#template_select').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					//console.log('code == 27');
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
				//$('#template_select').css({"overflow-y": "scroll"});
			}
		);
	}

	//템플릿 선택
	function template() {
		if("<?=$param['tmp_profile']?>" != "") {
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
			$(".content").html("<p class='modal_text_notice'>템플릿을 먼저 선택해주세요.</p>");
			//$('#myModal').modal({backdrop: 'static'});
			modal_open('myModal');
			return false;
		} else {
			document.location.href="/uploads/customer_list.xlsx";
		}
	}

	//업로드
	function readURL(input) {
		var file = document.getElementById('filecount').value;
		file = file.slice(file.indexOf(".") + 1).toLowerCase();
		if (file == "xls" || file == "xlsx" || file=="txt") {
			var formData = new FormData();
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			formData.append("templ_id", "<?=$tpl->tpl_id?>");
			formData.append("templ_profile", "<?=$tpl->tpl_profile_key?>");
			formData.append("templ_code", "<?=$tpl->tpl_code?>");
			formData.append("file", $("input[name=filecount]")[0].files[0]);
			formData.append("ext", file);
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
						//$('#myModal').modal({backdrop: 'static'});
						//$('#filecount').filestyle('clear');
						modal_open('myModal');
						return false;
					} else if (json['nrow_len'] > 60000) {
						$(".content").html("대량 발송은 최대 6만건까지 가능합니다.");
						//$('#myModal').modal({backdrop: 'static'});
						//$('#filecount').filestyle('clear');
						modal_open('myModal');
						return false;
					} else if (json['nrow_len'] != undefined){
						var bulk_send = json['nrow_len'];
						var error_count = json['tel_err_cnt'];
						var upload_file = json['upload_file'];
						var coin = json['coin'];
						var limit_count;
						var sms_count = 0; try { sms_count=json['sms_count']; }catch(e){}
						var lms_count = json['lms_count'];


						if (ms != "") {
							// 2020-01-30 변경
							//limit_count = Number(resend_price)*Number(bulk_send);
							limit_count = Number(at_price)*Number(bulk_send);
						} else {
							limit_count = Number(at_price)*Number(bulk_send);
						}

						if(coin >= limit_count) {
							$("#upload_tel_count").html(bulk_send);
							$("#upload_tel_error_count").html(error_count);
						} else {
							$(".content").html("충전 잔액이 부족합니다.");
							//$('#myModal').modal({backdrop: 'static'});
							//$('#filecount').filestyle('clear');
							modal_open('myModal');
							return false;
						}
					} else {
						var cols = json['col_value'];
						var rows = new Array();

						var btnMaxCols = 1;
						var btn_content;

						var buttons = '<?=$tpl->tpl_button?>';
						var btn = buttons.replace(/&quot;/gi, "\"");
						btn_content = JSON.parse(btn);
						for (var i=0;i<=btn_content.length-1;i++) {
							if (btn_content[i]["linkType"] == "WL" && btn_content[i]["linkPc"] != null || btn_content[i]["linkType"] == "AL") {
								btnMaxCols = btnMaxCols + 2;
							} else if (btn_content[i]["linkType"] == "WL" && btn_content[i]["linkPc"] == null) {
								btnMaxCols ++;
							}
							if(i==btn_content.length-1){
								break;
							}
						}

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
							if(ms!='') {
								var sms_cont = String(cols[i][2]); // 2차발신 내용
								if (btnMaxCols > 1) {
									sms_cont = String(cols[i][Number(btnMaxCols)+1]); // 2차발신 내용(버튼이 있을경우 밀려남)
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
							//$('#myModal').modal({backdrop: 'static'});
							//$('#filecount').filestyle('clear');
							modal_open('myModal');
							return false;
						} else {
							//$("#text").val(rows[0][1]);
							//var height = $("#text").prop("scrollHeight");
							//if (height < 468) {
							//	$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
							//} else {
							//	var message = $("#text").val();
							//	var height = $("#text").prop("scrollHeight");
							//	if (message == "") {
							//		$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
							//	} else {
							//		$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
							//	}
							//}
							/*
							var hidden = $("#hidden_cont").val();
							var hidden_length = hidden.length;
							$("#type_num").html(hidden_length);
							$("#templi_cont").val(hidden);
							//$("#templi_cont").attr("readonly",true);
							$("#templi_cont").css("background-color","#EEEEEE");
							if (btnMaxCols > 1) {
								$("#alimtalk_table tr").each(function () {
									var hidden_url = $(this).find("#hidden_url").val();
									$(this).find("#btn_url").val(hidden_url);
									$(this).find("#btn_url").attr("readonly",true);
									$(this).find("#btn_url").css("cursor","default")
								});
							}
							if (ms!="") {
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
									tr = '<tr class="' + no + '" id="tel_tbody_tr">' +
											'<td class="checkbox-column" style="width:10% !important"><input name="sel_del" id="' + no + '" class="uniform" value="' + no + '" type="checkbox"></td>' +
											'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
											'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" value="' + num + '" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);">' +
											'<input type="hidden" id="tel_temp" name="tel_temp" value="'+temp+'"/>';
									if(btnMaxCols > 1) {
										var url_count = 1;
										for (var b = 2 ; b<=btnMaxCols ; b++) {
											var btn_url = String(rows[i][b]).trim();
											tr += '<input type="hidden" id="tel_url' + url_count + '" name="tel_url" value="'+btn_url+'"/>';
											url_count++;
										}
										if (ms!="") {
											var sms = String(rows[i][Number(btnMaxCols) + 1]);
											tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="' + sms + '"/>';
										}
									}
									if (ms!="") {
										var sms = rows[i][2];
										tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="'+sms+'"/>';
									}
									tr += '</td>' +
											'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
											'</tr>';
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
									tr += '<tr class="' + no + '" id="tel_tbody_tr">' +
													'<td class="checkbox-column" style="width:10% !important"><input name="sel_del" id="' + no + '" class="uniform" value="' + no + '" type="checkbox"></td>' +
													'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
													'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" value="' + num + '" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);">' +
													'<input type="hidden" id="tel_temp" name="tel_temp" value="'+temp+'"/>';
									if(btnMaxCols > 1) {
										var url_count = 1;
										for (var b = 2 ; b<=btnMaxCols ; b++) {
											var btn_url = String(rows[i][b]).trim();
											tr += '<input type="hidden" id="tel_url' + url_count + '" name="tel_url" value="'+btn_url+'"/>';
											url_count++;
										}
										if (ms!="") {
											var sms = String(rows[i][Number(btnMaxCols) + 1]);
											tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="' + sms + '"/>';
										}
									}
									if (ms!="") {
										var sms = rows[i][2];
										tr += '<input type="hidden" id="tel_sms" name="tel_sms" value="'+sms+'"/>';
									}
									tr += '</td>' +
													'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
													'</tr>';
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
							}*/
						}
					}
				}
			});
		} else {
			$(".content").html("xls,xlsx 파일만 가능합니다.");
			//$('#myModal').modal({backdrop: 'static'});
			//$('#myModal').on('hidden.bs.modal', function () {
				//$('#filecount').filestyle('clear');
				//var table = document.getElementById("tel");
				//var row_index = table.rows.length;

				//for (var i = 1; i < row_index; i++) {
				//	var tr = $("." + i);
				//	tr.remove();
				//	var height = $("#tel_tbody").prop("scrollHeight");
				//	$("#tel_tbody").css("height", "0px");
				//}

				//var add = '<tr class="' + 1 + '" id="tel_tbody_tr">' +
				//		'<td class="checkbox-column" style="width:10% !important"><input name="sel_del" id="' + 1 + '" class="uniform" value="' + 1 + '" type="checkbox"></td>' +
				//		'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
				//		'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
				//		'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
				//		'</tr>';
				//$("#tel_tbody").html(add);
				//var height = $("#tel_tbody").prop("scrollHeight");
				//$("#tel_tbody").css("height", "47px");
				//$("#1").uniform();
			//});
			//$(document).unbind("keyup").keyup(function (e) {
			//	var code = e.which;
			//	if (code == 13) {
			//		$(".enter").click();
			//	}
			//});
			modal_open('myModal');
			return false;
		}
	}

	//미리보기  함수들
	function view_preview(){
		//$("#image").remove();
		$("#btn_preview1").remove();
		$("#pre_title").remove();

		templi_preview(document.getElementById('templi_cont'));
		//scroll_prevent();
		templi_chk();
		link_preview();
	}

    function view_btn_name() {
        if($("#hdn_order_btn").val()!=""){
            $("#order_btn_review").html("<span class=\"material-icons\">link</span> " + $("#hdn_order_btn").val());
        }else{
            $("#order_btn_review").html("<span class=\"material-icons\">link</span> 주문하기");
        }
	}

	<? // 2019.08.08 쿠폰 관련 함수 ?>
	function view_result() {
		if($("#img_link").val()=='') {
			alert("이미지를 선택 하세요.");
		} else {
			$("#text").html("");
			//$("#image").remove();
			//$("#btn_preview1").remove();
			//$("#pre_title").remove();

    		re_templi_preview($("#id_cc_memo").get(0));
			readImage($("#cp_img_link").val());
    		re_link_preview();
    		//scroll_prevent();
    		templi_chk();
		}
	}

	//업로드 선택시
	function upload() {
		if ($("#templi_code").val() == null) {
			$('#filecount').attr('disabled','disabled');
			$(".content").html("<p class='modal_text_notice'>템플릿을 먼저 선택해주세요.</p>");
			//$('#myModal').modal({backdrop: 'static'});
			//$(".enter").click(function () {
			//	$("#filecount").removeAttr("disabled");
			//})
			modal_open('myModal');
			return false;
		} else {
			$('#filecount').attr('disabled','disabled');
			$(".content").html("입력하신 수신 번호가 초기화됩니다.<br/>업로드 하시면 업로드 파일의 내용으로 발송됩니다.<br/>업로드 하시겠습니까?");
			//$('#myModalUpload').modal({backdrop: 'static'});
			//$('#myModalUpload').unbind("keyup").keyup(function (e) {
			//	var code = e.which;
			//	if (code == 27) {
			//		$(".btn-default").click();
			//	} else if (code == 13) {
			//		check();
			//	}
			//});
			//$(".up").click(function () {
			//	check();
			//});
			modal_open('myModalUpload');
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
			if (ms!="") {
				$("#lms").attr("readonly",false);
				$("#lms").css("cursor","default");
				$("#lms").css("background-color","white");
			}
			$("#alimtalk_table tr").each(function () {
				$(this).find("#btn_url").attr("readonly",false);
			});

			if($('#filecount').val()!=""){
				$("#number_add").click(function () {
					add_number();
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
				var tr = '<tr class="' + 1 + '" id="tel_tbody_tr">' +
						'<td class="checkbox-column" style="width:10% !important"><input name="sel_del" id="' + 1 + '" class="uniform" value="' + 1 + '" type="checkbox"></td>' +
						'<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
						'<td width="40%"><input type="text" class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeypress="return check_digit(event);"></td>' +
						'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제"><i class="icon-trash"></i> 삭제</a></td>' +
						'</tr>';
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

	function insert_kakao_link(check){
		//var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
		var pf_yid = $("#pf_yid_ori").val().replace(/[ ]*$/g, '');
		var long_url = "http://plus-talk.kakao.com/plus/home/" + pf_yid;

		$.ajax({
			url: "/dhnbiz/short_url",
			type: "POST",
			data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", "long_url":long_url},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				var short_url = "카톡친구추가 바랍니다! : " + json['short_url'];
//				var content;
//				var index;
//				var cont;
				//cont = $("#lms").val();
//				cont = $("#lms_footer").html().replace("<br>", "\n");
				<? // 문자 풋부분 카카오톡 링크 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
				if (check.checked) {
					$("#kakotalk_link_text").html(short_url);
				} else {
					$("#kakotalk_link_text").html("");
				}
				chkword_lms();
				onPreviewLms();
//				if (check.checked) {
//					if (new RegExp(/무료수신거부/gi).test(cont)) {
//						content = cont.replace("무료수신거부", short_url + "\n무료수신거부");
//						$("#lms_footer").html(content.replace("\n", "<br>"));
//						//content = $("#lms").val().replace("무료수신거부", short_url + "무료수신거부");
//						//$("#lms").val(content).focus();
//					} else {
//						content = cont.replace(cont, cont + short_url);
//						$("#lms_footer").html(content.replace("\n", "<br>"));
//						//content = $("#lms").val().replace(cont, cont + "<BR>" + short_url);
//						//$("#lms").val(content).focus();
//					}
//					//2차발신 입력란 가변 처리

//					//resize_cont(document.getElementById("lms"));
//					chkword_lms();
//					//미리보기 insert_kakao_link
//					//onPreviewText();
//				} else {
//					if ((new RegExp(/무료수신거부/gi).test(cont))) {
//						index = cont.indexOf("무료수신거부");
//						var first = cont.slice(0, index - 1).replace(short_url, "");
//						var last = cont.slice(index);
//						content = first + last;
//						//$("#lms").val(first + last).focus();
//						$("#lms_footer").html(content.replace("\n", "<br>"));
//						chkword_lms();
//					} else {
//						index = cont.indexOf(short_url);
//						content = cont.slice(0, index - 1);
//						$("#lms_footer").html(content.replace("\n", "<br>"));
//						//$("#lms").val(cont.slice(0, index - 1));
//						chkword_lms();
//					}
//					//onPreviewText(check);
//					//if ($("#lms").prop("scrollHeight") < 412 && $("#lms").prop("scrollHeight") > 103) {
//					//	$("#lms").css("height", "1px").css("height", ($("#lms").prop("scrollHeight") - 1) + "px");
//					//} else {
//					//	resize_cont(document.getElementById("lms"));
//					//}
//					chkword_lms();
//				}
//				onPreviewLms();
			}
		});

	}

	//알림톡, lms 입력란 가변 처리
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
	}

	<? /*-------------------------*
        | 2차발신 내용 글자수 제한 |
        *--------------------------*/?>
	function chkword_lms() {
		chkbyte($("#lms_header"), $("#lms"), $("#lms_footer"), $("#lms_num"), <?=(($mem->mem_2nd_send=='sms') ? '90' : '2000')?>);
	}

	function chkbyte($obj_header, $obj, $obj_footer, $num, maxByte) {
		var oriMaxByte = maxByte;
		var phn = '<?=$this->Biz_dhn_model->reject_phn?>';
		var phn_len = phn.length + 16;

		<? // 문자 헤드부분 회사명 변경가능하도록 수정 문자길이체크 처리 2019-07-25 ?>
		var lms_header_temp = $obj_header.find("#span_adv").text() + $obj_header.find("#companyName").val();
		//var lms_header_temp = $obj_header.html().replace("<BR>", "\n").replace("<br>", "\n");
		var lms_footer_temp = "";
		if($("#kakotalk_link_text").text() == "") {
			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		} else {
			lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		}
		//var lms_footer_temp = $obj_footer.html().replace("<BR>", "\n").replace("<br>", "\n");

		var strValue = lms_header_temp + "\n" + $obj.val() + "\n" + lms_footer_temp;
		var strLen = strValue.length || 0;


		//var strValue = $obj.val();
		//var strLen = strValue.length;
		var totalByte = 0;
		var len = 0;
		var oneChar = "";
		var str2 = "";

		for (var i = 0; i < strLen; i++) {
			oneChar = strValue.charAt(i);
			if (escape(oneChar).length > 4) {
				 totalByte += 2;
			} else {
				 totalByte++;
			}
//			<?/* 입력한 문자 길이보다 넘치면 잘라내기 위해 저장 */?>
//		 	if (totalByte <= maxByte) {
//		 		len = i + 1;
//		 	}
		}
		$num.html(totalByte);
        var isdhn = $("#hdn_isdhn").val();
		if (totalByte <= 90) {
			$("#lms_limit_str").html("SMS");
			$("#lms_character_limit").html("90");
            if(isdhn=="N"){
                $(".msg_type").attr("class", "msg_type sms");
            }
			$(".msg_type_txt").attr("class", "msg_type_txt sms");
            lmsflag="sms";
		} else {
            if(lmsflag=="sms"){
                showSnackbar("90Byte가 초과되어 LMS로 전환됩니다.", 1500);
            }
			$("#lms_limit_str").html("LMS");
			$("#lms_character_limit").html("2000");
            if(isdhn=="N"){
                $(".msg_type").attr("class", "msg_type lms");
            }
			$(".msg_type_txt").attr("class", "msg_type_txt lms");
            lmsflag="lms";
		}


//      <?/* 넘어가는 글자는 자른다. */?>
//		if (totalByte > maxByte) {
//			$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용을 " + oriMaxByte + "자(한글 " + parseInt(oriMaxByte / 2) + "자) 이내로<br/>입력해주세요.<br/>(실제내용 " + (maxByte - phn_len - 20) + "자 이내)");
//		 	$("#myModal").modal({backdrop: 'static'});
//		 	$('#myModal').on('hidden.bs.modal', function () {
//		 		$("#lms").focus();
//		 	});

//		 	$obj.val(str2);
//		 	$num.html(totalByte);
//			chkbyte($obj_header, $obj, $obj_footer, $num, oriMaxByte);
//		}
	}
// 	function chkword_lms($obj)
// 	{
// 		var strValue = $obj.val();
// 		var strLen = strValue.length;
// 		var totalByte = 0;
// 		var oneChar = "";
// 		for (var i = 0; i < strLen; i++) {
// 			oneChar = strValue.charAt(i);
// 			if (escape(oneChar).length > 4) {
// 				 totalByte += 2;
// 			} else {
// 				 totalByte++;
// 			}
// 		}
// 		return totalByte;
// 	}

	function chkword_lmsCount() {
		<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
		//var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var lms_header_temp = $("#span_adv").text() + $("#companyName").val();
		var lms_footer_temp = "";
		if($("#kakotalk_link_text").text() == "") {
			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		} else {
			lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		}
		//var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

		var strValue = lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
		var strLen = strValue.length || 0;


		//var strValue = $obj.val();
		//var strLen = strValue.length;
		var totalByte = 0;
		var len = 0;
		var oneChar = "";
		var str2 = "";

		for (var i = 0; i < strLen; i++) {
			oneChar = strValue.charAt(i);
			if (escape(oneChar).length > 4) {
				 totalByte += 2;
			} else {
				 totalByte++;
			}
		}

		return totalByte;
	}


	//lms 발신여부 체크박스
	function select_lms(check){
		var sms_sender = $("#sms_sender").val();
		if (sms_sender == "None" || sms_sender.replace(/ /gi, "") == "") {
			$(".content").html("발신번호가 등록되지 않았습니다.<br>" +
				"발신프로필 목록에서 발신번호를 등록해주세요.");
			//$('#myModal').modal({backdrop: 'static'});
			$("input:checkbox[id='lms_select']").removeAttr('checked');
			//$(".uniform").uniform();
			modal_open('myModal');
			return false;
		} else {
			if (check.checked) {
				var pf_ynm = $("#pf_ynm").val();
				var phn = '<?=$this->Biz_dhn_model->reject_phn?>';
					 var cont = $("#templi_cont").val();
<?if($mem->mem_2nd_send=='phn') { /* 2차발신 체크시 폰문자인 경우에만 수신거부 문구를 삽입해요 */ ?>
					 if(cont.indexOf("(광고)") < 0) { cont = "(광고)" + cont; }
				if (phn != "None" && phn.replace(/ /gi, "") != "") {
						if(cont.indexOf("무료수신거부") < 0) { cont = cont + "\n\n무료수신거부 : " + phn; }
				} else {
						if(cont.indexOf("무료수신거부") < 0) { cont = cont + "\n\n무료수신거부 : "; }
				}
<?}?>
				if ($("#lms").val().replace(/ /gi, "") == "") {
					$("#lms").val(cont);
					$("#text").val(cont);
				}
				$("#lms_kakaolink_select").removeAttr("disabled");
				$("#lms_kakaolink_select").uniform();
				$("#lms").removeAttr("disabled").css("background-color", "white").focus();
				resize_cont(document.getElementById("lms"));

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
				chkword_lms();
			} else {
				//카카오 친구추가 링크 체크박스 선택 되어있을 경우 해제
				if ($("input:checkbox[id='lms_kakaolink_select']").is(":checked") == true) {
					$("input:checkbox[id='lms_kakaolink_select']").removeAttr('checked');
				}
				$("#lms_kakaolink_select").attr("checked", false).attr('disabled', true);
				$("#lms_kakaolink_select").uniform();
				$("#lms").val("").css("background-color", "#EEEEEE").attr('disabled', 'disabled').css("height", "103px");
				//$('.uniform').uniform();

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

	// 고객정보 선택 불러오기
	function load_customer_select() {
		if ($("#templi_code").val() == null || $("#templi_code").val()=='') {
			$(".content").html("<p class='modal_text_notice'>템플릿을 먼저 선택해주세요.</p>");
			//$('#myModal').modal({backdrop: 'static'});
			modal_open('myModal');
			return false;
		} else {
			if ($("#upload_result").val() != undefined) {
				$("#upload_result").remove();
				check('customer_select');
			}

			$("#myModalLoadCustomers").modal({backdrop: 'static'});
			$("#myModalLoadCustomers").on('shown.bs.modal', function () {
				//$('.uniform').uniform();
				//$('select.select2').select2();
			});
			$('#myModalLoadCustomers').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					$(".btn-default.dismiss").click();
				} else if (code == 13) {
					include_customer()
				}
			});
			$("#myModalLoadCustomers .include_phns").click(function () {
				include_customer();
			});
			open_page(1);
		}
	}

	// 고객정보 구분 불러오기 2019.01.18 이수환 추가
	function load_customer_gubun() {
		load_customer_all($('#searchGroup2').val());
	}

	// 고객정보 전체 불러오기
	function load_customer_all(filter) {
		filter = ((typeof(filter)=='undefined' || filter==null) ? '' : filter);
		if ($("#templi_code").val() == null || $("#templi_code").val()=='') {
			$(".content").html("<p class='modal_text_notice'>템플릿을 먼저 선택해주세요.</p>");
			//$('#myModal').modal({backdrop: 'static'});
			modal_open('myModal');
			return false;
		} else {
			if (document.getElementById('filecount').value != '') {
				 check('customer_all');
			}
			$.ajax({
				 url: "/dhnbiz/sender/friend/load_customer",
				 data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", 'filter': filter },
				 type: "POST",
				 success: function (json) {
					  var customer_count = json['customer_count'];
					  if (customer_count > 60000) {
							$(".content").html("최대 60,000명까지 가능합니다.");
							//$('#myModal').modal({backdrop: 'static'});
							modal_open('myModal');
							return false;
					  } else if (customer_count > 0) {
							$("#number_add").hide();
							$("#number_del").hide();
							$("#send_select").hide();
							$("#tel").hide();
							/*
							$(".reserve").attr("hidden", true); //대량발송 예약 막음
							$(".reserve_content").attr("hidden", true); //대량발송 예약 막음
							$("input:checkbox[id='reserve_check']").removeAttr('checked'); //대량발송 예약 막음
							$("#reserve_check").uniform(); //대량발송 예약 막음
							*/
							$("#upload_result").remove();
							// 2019.01.17. 이수환 고객구분없음 추가 및 수정
							//var filterText = (filter == 'NONE') ?  '고객구분없음' : filter;
							//$(".tel_content").after('<div class="widget-content" id="upload_result"><p>'+((filter=='') ? '전체' : filterText)+' 고객 : ' + customer_count + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="customer_all_send" value="' + customer_count + '"><input type="hidden" id="customer_filter" value="' + filter + '"></div>');
							//$(".tel_content").after('<div class="widget-content" id="upload_result"><p>'+((filter=='') ? '전체' : filter)+' 고객 : ' + customer_count + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="customer_all_send" value="' + customer_count + '"><input type="hidden" id="customer_filter" value="' + filter + '"></div>');
					  } else {
							$(".content").html("고객정보가 없습니다.");
							//$('#myModal').modal({backdrop: 'static'});
							modal_open('myModal');
							return false;
					  }
				 },
				 error: function (data, status, er) {
					  $(".content").html("처리중 오류가 발생하였습니다.");
					  $("#myModal").modal('show');
				 }
			});
			$("#myModalFilterAll").modal('hide');
		}
	}

	// 친구톡 실패 고객정보 전체 불러오기
	function load_customer_nft(filter) {
		filter = ((typeof(filter)=='undefined' || filter==null) ? '' : filter);
		/*if ($("#templi_code").val() == null || $("#templi_code").val()=='') {
			$(".content").html("<p class='modal_text_notice'>템플릿을 먼저 선택해주세요.</p>");
			$('#myModal').modal({backdrop: 'static'});
		} else*/ {
			if (document.getElementById('filecount').value != '') {
				 check('customer_all');
			}
			$.ajax({
				 url: "/dhnbiz/sender/friend/load_customernft",
				 data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", 'filter': filter },
				 type: "POST",
				 success: function (json) {
					  var customer_count = json['customer_count'];
					  if (customer_count > 60000) {
						$(".content").html("최대 60,000명까지 가능합니다.");
						//$('#myModal').modal({backdrop: 'static'});
						modal_open('myModal');
						return false;
					  } else if (customer_count > 0) {
							$("#number_add").hide();
							$("#number_del").hide();
							$("#send_select").hide();
							$("#tel").hide();
/*
							$(".reserve").attr("hidden", true); //대량발송 예약 막음
							$(".reserve_content").attr("hidden", true); //대량발송 예약 막음
							$("input:checkbox[id='reserve_check']").removeAttr('checked'); //대량발송 예약 막음
							$("#reserve_check").uniform(); //대량발송 예약 막음
							*/
							$("#upload_result").remove();
							$(".tel_content").after('<div class="widget-content" id="upload_result"><p>'+((filter=='') ? '전체' : filter)+' 고객 : ' + customer_count + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="customer_all_send" value="' + customer_count + '"><input type="hidden" id="friend_list" value="' + customer_count + '"></div>');
					  } else {
							$(".content").html("고객정보가 없습니다.");
							//$('#myModal').modal({backdrop: 'static'});
							modal_open('myModal');
							return false;
					  }
				 },
				 error: function (data, status, er) {
					  $(".content").html("처리중 오류가 발생하였습니다.");
					  $("#myModal").modal('show');
				 }
			});
			$("#myModalFilterAll").modal('hide');
		}
	}

	function include_customer(group ) {
		if(($("#sel_all").prop('checked') && $('#searchGroup').val()!='') || group == 'GS') {
			// 2019.01.17 이수환 고객구분없음 추가로 추가및 수정
			var searchGroupText = $('#searchGroup').val();
			if(searchGroupText == "NONE") {
				searchGroupText = "고객구분없음";
			}
			//$(".content").html("선택하신 " + $('#searchGroup').val() + " 고객 전체를 추가 하시겠습니까?");
			//$(".content").html("선택하신 " + searchGroupText + " 고객 전체를 추가 하시겠습니까?");
			//$('#myModalFilterAll').modal({backdrop: 'static'});
			//$('#myModalFilterAll').unbind("keyup").keyup(function (e) {
			//	var code = e.which;
			//	if (code == 27) {
			//		$(".btn-default").click();
			//	} else if (code == 13) {
			//		load_customer_all($('#searchGroup').val());
			//		return;
			//	}
			//});
			//$(".filter_all").click(function () {
			//	load_customer_all($('#searchGroup').val());
			//	return;
			//});
			if(confirm("선택하신 " + searchGroupText + " 고객 전체를 추가 하시겠습니까?")){
				load_customer_all($('#searchGroup').val());
				return;
			}
		}
		$("input[name='selCustomer']:checked").each(function () {
			var checked = $(this).parents('tr:eq(0)');
			var kind = $(checked).find('td:eq(2)').find('#kind').val();
			var name = $(checked).find('td:eq(3)').text();
			var phn = $(checked).find('td:eq(4)').text();
			add_number(phn, kind, name);
		});
		$("#myModalLoadCustomers").modal('hide');
		$("#myModalLoadCustomers .include_phns").unbind("click");
	}

	//발신번호 추가
	function add_number(phn, kind, name) {
		  if(typeof(kind)=="undefined") { kind = ""; }
		  if(typeof(name)=="undefined") { name = ""; }
		var lastItemNo = $("#tel tbody tr:last").attr("class");
		var no = parseInt(lastItemNo) + 1;
		var table = document.getElementById("tel");
		var row_index = table.rows.length;
		var phnNumVal = '';
		if (typeof phn != 'undefined') {
			phnNumVal = ' value=' +  phn + ' ';
		}

		if ($("#templi_code").val() == null || $("#templi_code").val()=='') {
			$(".content").html("<p class='modal_text_notice'>템플릿을 먼저 선택해주세요.</p>");
			//$('#myModal').modal({backdrop: 'static'});
			modal_open('myModal');
			return false;
		} else if ($("input:checkbox[value='1']").is(":checked") == true) {
			var custom_check = row_index-1;
			if(phn != undefined && no == 2 && $("."+custom_check).find("#tel_number").val() == ""){
				$("."+custom_check).remove();
				no=1;
			}

			if (row_index <= 60000) {
				if (row_index >= 5) {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
							'<input class="uniform" name="sel_del" id="'+ no +'" value="' + no + '" type="checkbox"></td>' +
									 '<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
									 '<td width="40%"><input type="text" ' + phnNumVal +
							'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
							'<i class="icon-trash"></i> 삭제</a></td></tr>';
					$("." + lastItemNo).after(tr);
					$("#" + no).uniform();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "235px");
					$(this).css("overflow", "scroll");
					$("tr."+no).focus();
					$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
				} else {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
							'<input class="uniform" name="sel_del" id="'+ no +'"  value="' + no + '" type="checkbox"></td>'+
									 '<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
									 '<td width="40%"><input type="text" ' +  phnNumVal +
							'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
							'<i class="icon-trash"></i> 삭제</a></td></tr>';
					if (no == 1) {
						$("#tel_tbody").html(tr);
						$("#tel_tbody").css("height", "47px");
					} else {
						$("." + lastItemNo).after(tr);
					}
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#"+no).uniform();
				}
			} else {
				$(".content").html("최대 60,000개까지 가능합니다.");
				//$("#myModal").modal({backdrop: 'static'});
				modal_open('myModal');
				return false;
			}
		} else {
			var custom_check = row_index-1;
			if(phn != undefined && no == 2 && $("."+custom_check).find("#tel_number").val() == ""){
				$("."+custom_check).remove();
				no=1;
			}

			if (row_index <= 60000) {
				if (row_index > 5) {
					var tr = '<tr class="' + no + '" name="'+no+'" id="tel_tbody_tr" name="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
							'<input type="checkbox" name="sel_del" id="'+ no +'"  value="'+no+'" class="uniform"></td>'+
									 '<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
									 '<td width="40%"><input type="text" ' + phnNumVal +
							'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
							'<i class="icon-trash"></i> 삭제</a></td></tr>';
					$("." + lastItemNo).after(tr);
					$("#" + no).uniform();
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", "235px");
					$(this).css("overflow", "scroll");
					$("tr."+no).focus();
					$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
				} else {
					var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
							'<input class="uniform" name="sel_del" id="'+ no +'"  value="' + no + '" type="checkbox"></td>'+
									 '<td width="15%"><span id="ab_kind"></span></td><td width="15%"><span id="ab_name"></span></td>' +
									 '<td width="40%"><input type="text" ' +  phnNumVal +
							'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
							'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn btn-sm" title="삭제">' +
							'<i class="icon-trash"></i> 삭제</a></td></tr>';
					if (no == 1) {
						$("#tel_tbody").html(tr);
						$("#tel_tbody").css("height", "47px");
					} else {
						$("." + lastItemNo).after(tr);
					}
					var height = $("#tel_tbody").prop("scrollHeight");
					$("#tel_tbody").css("height", (height) + "px");
					$("#"+no).uniform();
				}
			} else {
				$(".content").html("최대 60,000개까지 가능합니다.");
				//$("#myModal").modal({backdrop: 'static'});
				modal_open('myModal');
				return false;
			}
		}
	}

	//검색 조회
	// copy from customer_list
	function search_question(page) {

		open_page(page);
	}

	// copy from customer_list
	function open_page(page) {
		var type = $('#searchType').val() || 'all';
		var searchFor = $('#searchStr').val() || '';
		var searchGroup = $('#searchGroup').val() || '';
		var searchName = $('#searchName').val() || '';
		//console.log('open_page', page);
		$('#myModalLoadCustomers .widget-content').html('').load(
			"/dhnbiz/customer/inc_lists",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_type": type,
				"search_for": searchFor,
				"search_group": searchGroup,
				"search_name": searchName,
				'page': page,
				'is_modal': true
			},
			function () {
				//$('.uniform').uniform();
				//$('select.select2').select2();
			}
		);
	}

	//이미지 선택시-에이젝스
	function imageSelect() {
		cw = screen.availWidth;     //화면 넓이
		ch = screen.availHeight;    //화면 높이
		sw = 720;    //띄울 창의 넓이
		sh = 630;    //띄울 창의 높이
		ml = (cw - sw) / 2;        //가운데 띄우기위한 창의 x위치
		mt = (ch - sh) / 2;         //가운데 띄우기위한 창의 y위치

		imgSelectBox = window.open("/dhnbiz/sender/images", 'tst', 'width=' + sw + ',height=' + sh + ',top=' + mt + ',left=' + ml + ',location=no, resizable=no');
	}

	function setImageValue(selctImgValue, selctImgLink) {
		$("#img_url").val(selctImgValue);

		var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
		$("input[name=img_link]").val(selctImgLink);
	}

	//카톡 템플릿 내용 세팅
	function setTempllate() {
		//var dumyTempli_cont = $("#dumy_templi_cont").text().replace(/\n/g, "<br>");
		var dumyTempli_cont = $("#dumy_templi_cont").text();
		dumyTempli_cont = dumyTempli_cont.replace(/\n\n/g, "<p>&nbsp;</p>");
		dumyTempli_cont = dumyTempli_cont.replace(/\n/g, "<br>");
		//alert("dumyTempli_cont : "+ dumyTempli_cont);
		var msg_var = new Array();
		<?
			if(!empty($tmp_msg_var)){
				$idx = 0;
				foreach($tmp_msg_var as $var){
					$idx++;
		?>
		msg_var[<?=$idx?>] = "<?=preg_replace('/\r\n|\r|\n/','#br#',$var->var_text)?>";
		<?
				}
			}
		?>
		var temp = dumyTempli_cont.split("#{");
		var temp2 = new Array();
		var returnTempli_cont = "";
		for (var i = 0; i < temp.length; i++) {
			if (i == 0) {
				returnTempli_cont = returnTempli_cont + temp[i];
			} else {
				var varsplit = temp[i].split("}")
				var varName = "#{" + varsplit[0] + "}";
				var strMsg = "";
				if(msg_var[i]) strMsg = msg_var[i].replace(/#br#/gi, '\n');
				if (varName == "#{변수내용}" || varName == "#{회원정보 등록일}") {
					// 멀티라인이 필요한 변수, 자동 높이 조정 가능
					returnTempli_cont = returnTempli_cont + "<textarea name=\"var_name\" id=\"var_name\" class=\"var single\" onkeyup=\"return view_preview();\" maxlength='25' placeholder=\"행사 기간 : 00.00.00.\">"+ strMsg +"</textarea>";
				} else if (varName == "#{업체명}") {
					returnTempli_cont = returnTempli_cont + "<textarea name=\"var_name\" class=\"var single\" onkeyup=\"return view_preview();\" onKeypress=\"if(event.keyCode==13) { event.preventDefault(); }\" maxlength='20' placeholder=\"업체명\"><?= $this->member->item('mem_username')?></textarea>";
				} else if (varName == "#{업체전화번호}" || varName == "#{전화번호}" ) {
					var tel = "<?=$this->funn->format_phone($this->member->item('mem_phone'),"-")?>";
					returnTempli_cont = returnTempli_cont + "<textarea name=\"var_name\" class=\"var single\" onkeyup=\"return view_preview();\" onKeypress=\"if(event.keyCode==13) { event.preventDefault(); }\" maxlength='15' placeholder=\"전화번호\">"+ tel +"</textarea>";
				} else if (varName == "#{날짜}") {
					// 멀티라인이 필요없는 변수 한줄짜리.
					returnTempli_cont = returnTempli_cont + "<textarea name=\"var_name\" class=\"var single\" onkeyup=\"return view_preview();\" onKeypress=\"if(event.keyCode==13) { event.preventDefault(); }\" maxlength='15' placeholder=\"행사 날짜\">"+ strMsg +"</textarea>";
                } else if (varName == "#{00}") {
					// 멀티라인이 필요없는 변수 한줄짜리.
                    if(strMsg.match(/^[0-9]+$/) != null){
                        returnTempli_cont = returnTempli_cont + "<textarea name=\"var_name\" class=\"var single\" onkeyup=\"return view_preview();\" onKeypress=\"if(event.keyCode==13) { event.preventDefault(); }\" maxlength='4' placeholder=\"00\" style='width:35px;'>"+ strMsg +"</textarea>";
                    }else{
                        returnTempli_cont = returnTempli_cont + "<textarea name=\"var_name\" class=\"var single\" onkeyup=\"return view_preview();\" onKeypress=\"if(event.keyCode==13) { event.preventDefault(); }\" maxlength='4' placeholder=\"00\" style='width:35px;'></textarea>";
                    }

				} else {
					// 멀티라인이 필요없는 변수 한줄짜리.
					returnTempli_cont = returnTempli_cont + "<textarea name=\"var_name\" class=\"var single\" onkeyup=\"return view_preview();\" onKeypress=\"if(event.keyCode==13) { event.preventDefault(); }\" maxlength='15' placeholder=\"" + varName + "\">"+ strMsg +"</textarea>";
				}
				returnTempli_cont = returnTempli_cont + varsplit[1];
			}
		}
		$("#templi_cont").html(returnTempli_cont);
	}

	//담당자 연락처 수정
	function manager_phone_modify() {
		var nickname = $('#test_manager_add-todo-nickname').val();
		var phoneNum = $('#test_manager_add-todo-input').val();
		//alert("nickname : "+ nickname +", phoneNum : "+ phoneNum); return;

		if (!nickname) {
			//$(".content").html("담당자 성명을 입력하세요.");
			//$('#myModal').modal({backdrop: 'static'});
			alert("담당자 성명을 입력하세요.");
			$('#test_manager_add-todo-nickname').focus();
			return;
		}
		if (!phoneNum) {
			//$(".content").html("담당자 연락처를 입력하세요.");
			//$('#myModal').modal({backdrop: 'static'});
			alert("담당자 연락처를 입력하세요.");
			$('#test_manager_add-todo-input').focus();
			return;
		}

		$.ajax({
			url: "/dhnbiz/myinfo/manager_info_modify",
			type: "POST",
			data: {"nickname": nickname, "phoneNum": phoneNum, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function(json){
				//$(".content").html("담당자 정보가 수정되었습니다.");
				//$("#myModal").modal('show');
				alert("담당자 정보가 수정되었습니다.");
				return;
			},
			error: function (data, status, er) {
				//alert("data : "+ data +", status : "+ status +", er : "+ er.toString());
				//$(".content").html("처리중 오류가 발생하였습니다.");
				//$("#myModal").modal('show');
				alert("처리중 오류가 발생하였습니다.");
				return;
			}
		});
	}

	$(document).on('keyup keypress', 'form input', function(e) {
		  if(e.which == 13) {
		    e.preventDefault();
		    return false;
		  }
	});


	$(document).ready(function() {
	<? if(!empty($spf)) { ?>
		var tmp_flag = "<?=$param['tmp_flag']?>";
		$('#send_template_content').html('').load(
			"/dhnbiz/sender/send/select_template_adv_v3",
			{ <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				add: "<?=$add?>",
				tmp_flag: "<?=$param['tmp_flag']?>",
				tmp_code: "<?=$param['tmp_code']?>",
				tmp_profile: "<?=$param['tmp_profile']?>",
                tmp_profile2: "<?=$param['tmp_profile2']?>",
				iscoupon:"<?=$param['iscoupon']?>",
				sb_send: "<?=$param['sb_send']?>",
				sb_id: "<?=$param['sb_id']?>",
				sb_kind: "<?=$param['sb_kind']?>"
				,psd_code: "<?=$psd_code?>"
				,pcd_code: "<?=$pcd_code?>"
				,pcd_type: "<?=$pcd_type?>"
		   }, function() {
				$("#lms_select").change(function() {
					if($(this).is(":checked")) {
						$("#hidden_fields_sms").css("display", "block");
					} else {
						$("#hidden_fields_sms").css("display", "none");
					}
				});
				// var mem_2nd_send = '<?=$this->member->item("mem_2nd_send")?>';
				// alert(mem_2nd_send);
				// console.log('<?=$sendtelauth?>');
				<? if($this->member->item("mem_2nd_send")!='NONE'&&$parent_id!='962'&&$this->member->item("mem_id")!='962'&&$sendtelauth>0){?>
				<? //if($this->member->item("mem_2nd_send")!='NONE'){?>
				$("#lms_select").attr('checked', true);
				$("#hidden_fields_sms").show();
				<? } ?>

				setTempllate(); //카톡 템플릿 내용 세팅
				templi_preview(document.getElementById('templi_cont'));
				link_preview();
				templi_chk();
				//bindKeyUpEvent();

				if($("#lms_header").length >= 1) {
					<? // 문자 헤드부분 회사명 변경가능하도록 수정 2019-07-25 ?>
    				//var lms_header = "(광고) " + $("#pf_ynm").val();

    				var phn = "080-888-7985";
    				<?if( $mem->mem_2nd_send=='015') { ?>
    				phn = '080-888-7985'; //'080-1111-1111';
    				<?} else if( $mem->mem_2nd_send=='NASELF') { ?>
    				phn = '080-888-7985'; //'080-855-5947';
    				<?} else if($mem->mem_2nd_send=='GREEN_SHOT') { ?>
    				phn = '080-888-7985'; //'080-156-0186';
    				<?} else if($mem->mem_2nd_send=='PHONE') { ?>
    				phn = '080-888-7985'; //'080-889-8383';
    				<?}
    				if($this->member->item("mem_id") == 228 || $this->member->item("mem_id") == 93 || $this->member->item("mem_id") == 519
    				    || $this->member->item("mem_id") == 445 || $this->member->item("mem_id") == 446 || $this->member->item("mem_id") == 447
    				    || $this->member->item("mem_id") == 448 || $this->member->item("mem_id") == 484 || $this->member->item("mem_id") == 572) {

                    ?>
					phn = '080-053-7590';
					<? } ?>

					<? // 문자 헤드부분 회사명 변경가능하도록 수정 2019-07-25 ?>
    				//var lms_footer = "무료수신거부 : " + phn;
    				//$("#lms_header").html(lms_header);
    				//$("#lms_footer").html(lms_footer);
    				$("#companyName").val($("#pf_ynm").val());
    				$("#unsubscribe_num").val(phn);

    				chkword_lms();
    				onPreviewLms();
				}
		   }
		);
	<?} else { ?>
		var html  ='';
			html +='<div class="input_content_wrap">';
			html +='<label class="input_tit">발신프로필 선택</label>';
			html +='<div class="input_content" id="send_friend_content">';
			html +='<div class="alert alert-danger align-center">';
			//html +='<p style="margin-bottom: 25px;">등록된 발신프로필이 없습니다. 발신프로필 등록후 사용가능합니다.</p>';
			//html +='<button  class="btn md plus" onclick="location.href=\'/biz/sendprofile/write\'">발신프로필 등록하기</button>';
			html +='<p style="margin-bottom: 0px;">등록된 발신프로필이 없습니다. 관리자에게 문의 하세요.</p>';
			html +='</div>';
			html +='</div>';
			html +='</div>';
		$('#send_template_content').html(html);
	<? } ?>
	});

    //템플릿내용 선택
	function selected_template(bottonId, tmp_flag) {
        iscoupon = $("#couponwrite").val();
		nft_value = $("#NFTVALUE").val();
		var psd_code = $("#psd_code").val();
		//alert("psd_code : "+ psd_code);
        var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/dhnbiz/sender/send/talk_adv_v3");

		var csrfField = document.createElement("input");
		csrfField.setAttribute("type", "hidden");
		csrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		csrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		form.appendChild(csrfField);

		var selectedField = document.createElement("input");
		selectedField.setAttribute("type", "hidden");
		selectedField.setAttribute("name", "tmp_code");
		selectedField.setAttribute("value", bottonId);
		form.appendChild(selectedField);

		var selectedField = document.createElement("input");
		selectedField.setAttribute("type", "hidden");
		selectedField.setAttribute("name", "psd_code");
		selectedField.setAttribute("value", psd_code);
		form.appendChild(selectedField);

		var selectedField = document.createElement("input");
		selectedField.setAttribute("type", "hidden");
		selectedField.setAttribute("name", "pcd_code");
		selectedField.setAttribute("value", $("#pcd_code").val());
		form.appendChild(selectedField);

		var selectedField = document.createElement("input");
		selectedField.setAttribute("type", "hidden");
		selectedField.setAttribute("name", "pcd_type");
		selectedField.setAttribute("value", $("#pcd_type").val());
		form.appendChild(selectedField);

		var selectedField = document.createElement("input");
		selectedField.setAttribute("type", "hidden");
		selectedField.setAttribute("name", "add");
		selectedField.setAttribute("value", "<?=$add?>");
		form.appendChild(selectedField);

		var selectedField = document.createElement("input");
		selectedField.setAttribute("type", "hidden");
		selectedField.setAttribute("name", "tmp_flag");
		selectedField.setAttribute("value", tmp_flag);
		form.appendChild(selectedField);

        <? if (!empty($fail_list)){?>
            var selectedField = document.createElement("input");
    		selectedField.setAttribute("type", "hidden");
    		selectedField.setAttribute("name", "cnt");
    		selectedField.setAttribute("value", '<?=$fail_cnt?>');
    		form.appendChild(selectedField);

            var selectedField = document.createElement("input");
    		selectedField.setAttribute("type", "hidden");
    		selectedField.setAttribute("name", "date");
    		selectedField.setAttribute("value", '<?=$fail_date?>');
    		form.appendChild(selectedField);

            var selectedField = document.createElement("input");
    		selectedField.setAttribute("type", "hidden");
    		selectedField.setAttribute("name", "mid");
    		selectedField.setAttribute("value", '<?=$mid?>');
    		form.appendChild(selectedField);

            var selectedField = document.createElement("input");
    		selectedField.setAttribute("type", "hidden");
    		selectedField.setAttribute("name", "mtype");
    		selectedField.setAttribute("value", '<?=$mtype?>');
    		form.appendChild(selectedField);

            var selectedField = document.createElement("input");
    		selectedField.setAttribute("type", "hidden");
    		selectedField.setAttribute("name", "uid");
    		selectedField.setAttribute("value", '<?=$uid?>');
    		form.appendChild(selectedField);

        <? } ?>

		form.submit();
    }

	//링크 타입 변경
	function chg_link_type(id){
		// var link_type_option = document.getElementById("link_type_option"+id).value;
		var link_type = document.getElementById("link_type"+id).value; //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
		// var tpl_id = "<?=$tpl->tpl_id?>";
		var btn_url = "";

        if(link_type=="home"){
            $(".btn_loop_"+id).attr("readonly", true);
            $(".btn_loop_"+id).css("background-color", "#f2f2f2"); //링크 배경색 변경
            $(".btn_loop_"+ id).val(document.getElementById("home_url").value);
            if(id==0){
                $("#div_home_box").show();
            }
        }else{
            $(".btn_loop_"+id).attr("readonly", false);
            $(".btn_loop_"+id).attr("placeholder", 'url을 입력하세요');
            $(".btn_loop_"+id).css("background-color", "#ffffff"); //링크 배경색 변경
            $(".btn_loop_"+ id).val('');
            if(id==0){
                $("#div_home_box").hide();
            }
        }

		onPreviewLms();
		chkword_lms();
	}

	//스마트전단 선택
	function smart_choice(code){
		//alert("code : "+ code); return;
		//스마트전단 링크 URL 만들기
        // $("#div_smart_url").text("http://<?=$_SERVER['HTTP_HOST']?>/smart/view/"+code);
        $("#hdn_smart_url").val("http://<?=$_SERVER['HTTP_HOST']?>/smart/view/"+code);
        $("#psd_url").val("http://<?=$_SERVER['HTTP_HOST']?>/smart/view/"+code);
        $("#psd_code").val(code);

        $.ajax({
            url: "/spop/screen/check_psd",
            type: "POST",
            data: {
                  "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                , "code" : code //스마트전단 코드
            },
            success: function (json) {
                if(json.code=='0'){
                    console.log("전단복사");
                }else{
                    console.log("전단이미있음");
                }
            }
        });

		//스마트전단 불러오기 Modal 닫기
		modal_smart.style.display = "none";
	}

	//스마트쿠폰 선택
	function coupon_choice(code, type){

        // $("#div_coupon_url").text("http://<?=$_SERVER['HTTP_HOST']?>/smart/coupon/"+code);
        $("#hdn_coupon_url").val("http://<?=$_SERVER['HTTP_HOST']?>/smart/coupon/"+code);
        $("#pcd_url").val("http://<?=$_SERVER['HTTP_HOST']?>/smart/coupon/"+code);
        $("#pcd_code").val(code);
        $("#pcd_type").val(type);


		//스마트전단 불러오기 Modal 닫기
		modal_coupon.style.display = "none";
	}

    //에디터 선택
    function editor_choice(obj, code){
        var chk_cnt = $('input:checkbox[name="chk_btn"]:checked').length;
        // console.log("선택개수 : "+chk_cnt);
        if(chk_cnt>3){
            $(obj).attr("checked", false);
            showSnackbar("버튼은 3개까지 사용하실 수 있습니다.", 1500);
            return false;
        }

        if(obj.checked){
            // $("#div_editor_url").text("http://<?=$_SERVER['HTTP_HOST']?>/at/"+code);
            $("#hdn_editor_url").val("http://<?=$_SERVER['HTTP_HOST']?>/at/"+code);
            $("#dhnl_url").val("http://<?=$_SERVER['HTTP_HOST']?>/at/"+code);
            $("#div_editor").show();
            $("#div_editor_box").show();
        }else{
            // $("#div_editor_url").text("");
            $("#hdn_editor_url").val("");
            $("#dhnl_url").val("");
            $('#summernote').summernote('code','');
            $("#div_editor").hide();
            $("#div_editor_box").hide();
        }
    }

    //스마트전단 체크박스
    function smart_chk_change(obj, code){
        var chk_cnt = $('input:checkbox[name="chk_btn"]:checked').length;
        // console.log("선택개수 : "+chk_cnt);
        if(chk_cnt>3){
            $(obj).attr("checked", false);
            showSnackbar("버튼은 3개까지 사용하실 수 있습니다.", 1500);
            return false;
        }

        if(obj.checked){
            if(code){
                $("#hdn_smart_url").val("http://<?=$_SERVER['HTTP_HOST']?>/smart/view/"+code);
                $("#psd_url").val("http://<?=$_SERVER['HTTP_HOST']?>/smart/view/"+code);
            }
            $("#div_smart").show();
        }else{
            $("#div_smart").hide();
            $("#hdn_smart_url").val("");
            $("#psd_url").val("");
        }
    }

    //스마트쿠폰 체크박스
    function coupon_chk_change(obj, code){
        var chk_cnt = $('input:checkbox[name="chk_btn"]:checked').length;
        // console.log("선택개수 : "+chk_cnt);
        if(chk_cnt>3){
            $(obj).attr("checked", false);
            showSnackbar("버튼은 3개까지 사용하실 수 있습니다.", 1500);
            return false;
        }

        if(obj.checked){
            if(code){
                $("#hdn_coupon_url").val("http://<?=$_SERVER['HTTP_HOST']?>/smart/coupon/"+code);
                $("#pcd_url").val("http://<?=$_SERVER['HTTP_HOST']?>/smart/coupon/"+code);
            }
            $("#div_coupon").show();
        }else{
            $("#hdn_coupon_url").val("");
            $("#pcd_url").val("");
            $("#div_coupon").hide();
        }
    }


    //스마트쿠폰 체크박스
    function order_chk_change(obj){
        var chk_cnt = $('input:checkbox[name="chk_btn"]:checked').length;
        // console.log("선택개수 : "+chk_cnt);
        if(chk_cnt>3){
            $(obj).attr("checked", false);
            showSnackbar("버튼은 3개까지 사용하실 수 있습니다.", 1500);
            return false;
        }

        if(obj.checked){
            $("#div_order").show();
        }else{
            $("#div_order").hide();
        }
    }

	//알림톡 내용저장
	function msg_save(use_yn){
		var temp_id = $('#templi_id').val(); //템플릿번호
		var vars = []; //알림톡 변수 내용
		$("textarea[name^='var_name']").each(function(){
			vars.push($(this).val());
		});
		//alert("vars : "+ vars); return;
		var umsurl = $("#dhnl_url").val(); //UMS 링크주소
		var ums_cont = $('#summernote').summernote('code'); //UMS 내용
		var temp_cont = getTempli(); //알림톡 내용
		var lms_msg = ""; //2차문자내용
		if($('#lms_select').prop('checked')){
			lms_msg = document.getElementById("lms").value; //2차문자내용
		}
		//alert("lms_msg : "+ lms_msg); return;
		$.ajax({
    		url: "/dhnbiz/sender/send/talk_adv_v3/save_adv_msg",
    		type: "POST",
    		data: {
    			 <?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>"
    			,"temp_id": temp_id //템플릿번호
    			,"vars": vars //알림톡 변수 내용
    			,"umsurl":umsurl //UMS 링크주소
    			,"ums_cont": ums_cont //UMS 내용
    			,"temp_cont":temp_cont //알림톡 내용
    			,"lms_msg":lms_msg //2차문자내용
    			,"use_yn":use_yn //사용여부
    		},
    		beforeSend: function () {
    			//$('#overlay').fadeIn();
    		},
    		complete: function () {
    			//$('#overlay').fadeOut();
    		},
    		success: function (json) {
    			$('#navigateURL').val("");
    			var code = json['code'];
    			var message = json['message'];
    			if (code == "0") {
    				if(use_yn == "Y"){
						$(".content").html("<h4>저장이 완료 되었습니다.</h4>");
						//$('#myModal').modal({backdrop: 'static'});
						//$(document).unbind("keyup").keyup(function (e) {
						//	var code = e.which;
						//	if (code == 13) {
						//		$(".enter").click();
						//	}
						//});
						modal_open('myModal');
						return false;
					}
    			} else {
    				$(".content").html("<h4>저장이 실패되었습니다.</h4>" + message);
    				//$('#myModal').modal({backdrop: 'static'});
    				//$(document).unbind("keyup").keyup(function (e) {
    				//	var code = e.which;
    				//	if (code == 13) {
    				//		$(".enter").click();
    				//	}
    				//});
					modal_open('myModal');
					return false;
    			}
    		},
    		error: function () {
    			$(".content").html("<h4>처리되지 않았습니다.</h4>");
    			//$('#myModal').modal({backdrop: 'static'});
    			//$(document).unbind("keyup").keyup(function (e) {
    			//	var code = e.which;
    			//	if (code == 13) {
    			//		$(".enter").click();
    			//	}
    			//});
				modal_open('myModal');
				return false;
    		}
		});
	}

	//알림톡 내용저장 불러오기
	function open_msg_lms_list() {
		/*$("#myModalUserMSGList").modal({backdrop: 'static'});
		$("#myModalUserMSGList").on('shown.bs.modal', function () {
			//$('.uniform').uniform();
			//$('select.select2').select2();
		});
		$('#myModalUserMSGList').unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 27) {
				$(".dismiss").click();
			} else if (code == 13) {
				include_customer()
			}
		});
		$("#myModalUserMSGList .include_phns").click(function () {
			include_customer();
		});*/
		open_page_user_lms_msg('1');
		modal_open('myModalUserMSGList');
	}

	//불러오기 내용저장 불러오기 페이지 로딩
	function open_page_user_lms_msg(page, delids) {
		var searchMsg = $('#searchMsg').val() || '';
		$('#myModalUserMSGList .content').html('').load(
			"/dhnbiz/sender/send/talk_adv_v3/msg_save_list",
			{
				<?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>",
				"search_msg": searchMsg,
				"del_ids[]":delids,
				'page': page,
				'is_modal': true
			},
			function () {
				//$('.uniform').uniform();
				//$('select.select2').select2();
			}
		);
    }

    //알림톡 내용저장 불러오기
	function open_msg_editor_list() {
		open_page_user_editer_msg('1');
		modal_open('myModalUserEditorList');
	}

	//불러오기 내용저장 불러오기 페이지 로딩
	function open_page_user_editer_msg(page, delids) {
		var searchMsg = $('#searchEditor').val() || '';
		$('#myModalUserEditorList .content').html('').load(
			"/dhnbiz/sender/send/talk_adv_v3/editor_save_list",
			{
				<?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>",
				"search_msg": searchMsg,
				"del_ids[]":delids,
				'page': page,
				'is_modal': true
			},
			function () {
				//$('.uniform').uniform();
				//$('select.select2').select2();
			}
		);
    }

    //알림 불러오기 모달에서 검색하기
	function search_msg(page) {
    	//alert("page : "+ page);
		open_page_user_lms_msg(page); //저장 메시지 검색하기
    }

	//알림톡 불러오기 모달에서 삭제하기
	function sel_delete_msg(msgIds){
		var result = confirm('선택한 메세지를 삭제 하시겠습니까?');
		if(result) { //yes
			open_page_user_lms_msg(1, msgIds); //저장 메시지 불러오기
		}
	}

    //에디터 불러오기 모달에서 검색하기
	function search_editor(page) {
    	//alert("page : "+ page);
		open_page_user_editer_msg(page); //에디터 검색하기
    }

    //알림톡 불러오기 모달에서 불러오기
    function include_editor(save_msg_id) {
		//alert("save_msg_id : "+ save_msg_id); return;
		//alert("save_msg_id : "+ save_msg_id);
        $.ajax({
            url: "/dhnbiz/sender/send/talk_adv_v3/select_load_editor",
            type: "POST",
            data: {
                  "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                , "save_msg_id" : save_msg_id //선택 그룹
            },
            success: function (json) {
                if(json.code=='0'){
                    // groupSendTotal = json.cnt;
                    // $("#groupSendTotal").text(groupSendTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")); //총 발송 건수 표기 (콤마 추가)
                    // $('.note-editable').html(json.html);
                    $('#summernote').summernote('code', json.html);
                    setTimeout(function(){
        				modal_close('myModalUserEditorList');
        			},50);
                }
            }
        });

    }

	//알림톡 불러오기 모달에서 불러오기
    function include_msg(save_msg_id) {
		//alert("save_msg_id : "+ save_msg_id); return;
		//alert("save_msg_id : "+ save_msg_id);
        buttonArr = [];
		$('#send_template_content').html('').load(
				"/dhnbiz/sender/send/select_template_adv_v3",
				{ <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					save_msg_id: save_msg_id,
					tmp_code: "<?=$param['tmp_code']?>",
					tmp_profile: "<?=$param['tmp_profile']?>",
                    tmp_profile2: "<?=$param['tmp_profile2']?>",
					iscoupon:"<?=$param['iscoupon']?>",
					sb_send: "<?=$param['sb_send']?>",
					sb_id: "<?=$param['sb_id']?>",
					sb_kind: "<?=$param['sb_kind']?>",
					psd_code: "<?=$psd_code?>",
					pcd_code: "<?=$pcd_code?>",
					pcd_type: "<?=$pcd_type?>",
					add: "<?=$add?>"
			   }, function() {
					$("#lms_select").change(function() {
						if($(this).is(":checked")) {
							$("#hidden_fields_sms").css("display", "block");
						} else {
							$("#hidden_fields_sms").css("display", "none");
						}
					});

					//setTempllate();
					//alert("templi_cont : "+ document.getElementById('templi_cont').value);
					templi_preview(document.getElementById('templi_cont'));
					//link_preview();
					mobile_preview(); //모바일 미리보기 세팅
					templi_chk();
					//bindKeyUpEvent();
					//alert("lms_header : "+ $("#lms_header").length);
					if($("#lms_header").length >= 1) {
						<? // 문자 헤드부분 회사명 변경가능하도록 수정 2019-07-25 ?>
	    				//var lms_header = "(광고) " + $("#pf_ynm").val();
	    				var phn = "080-888-7985";
	    				<?if( $mem->mem_2nd_send=='015') { ?>
	    				phn = '080-888-7985'; //'080-1111-1111';
	    				<?} else if( $mem->mem_2nd_send=='NASELF') { ?>
	    				phn = '080-888-7985'; //'080-855-5947';
	    				<?} else if($mem->mem_2nd_send=='GREEN_SHOT') { ?>
	    				phn = '080-888-7985'; //'080-156-0186';
	    				<?} else if($mem->mem_2nd_send=='PHONE') { ?>
	    				phn = '080-888-7985'; //'080-889-8383';
	    				<?}
	    				if($this->member->item("mem_id") == 228 || $this->member->item("mem_id") == 93 || $this->member->item("mem_id") == 519
	    				    || $this->member->item("mem_id") == 445 || $this->member->item("mem_id") == 446 || $this->member->item("mem_id") == 447
	    				    || $this->member->item("mem_id") == 448 || $this->member->item("mem_id") == 484 || $this->member->item("mem_id") == 572) {

	                    ?>
						phn = '080-053-7590';
						<? } ?>

						<? // 문자 헤드부분 회사명 변경가능하도록 수정 2019-07-25 ?>
	    				//var lms_footer = "무료수신거부 : " + phn;
	    				//$("#lms_header").html(lms_header);
	    				//$("#lms_footer").html(lms_footer);
						$("#companyName").val($("#pf_ynm").val());
	    				$("#unsubscribe_num").val(phn);
					}
					chkword_lms();
					onPreviewLms();
			   }
			);
			setTimeout(function(){
				//$("#selected_temp3").focus();
				//$("#myModalUserMSGList").modal('hide');
				//$("#myModalUserMSGList .include_phns").unbind("click");
				modal_close('myModalUserMSGList');
			},50);
			//console.timeEnd("a");
    }

	//글자수(Byte)를 반환
	function getByteLength($obj)
	{
		<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
		//var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var lms_header_temp = $("#span_adv").text() + $("#companyName").val();
		var lms_footer_temp = "";
		if($("#kakotalk_link_text").text() == "") {
			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		} else {
			lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		}
		//var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

		var totalByte = 0;
		var oneChar = "";

		var strValue = lms_header_temp + "\n" + $obj.val() + "\n" + lms_footer_temp;
		var strLen = strValue.length;

		for (var i = 0; i < strLen; i++) {
			oneChar = strValue.charAt(i);
			if (escape(oneChar).length > 4) {
				 totalByte += 2;
			} else {
				 totalByte++;
			}
		}
		return totalByte;
	}

	//서버시간 호출
	function sTime() {
		let xmlHttp;
		if (window.XMLHttpRequest) {
			xmlHttp = new XMLHttpRequest(); // IE 7.0이상, 크롬, 파이어폭스등
		} else if (window.ActiveXObject) {
			xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
		} else {
			return "";
		}

		xmlHttp.open('HEAD', window.location.href.toString(), false);
		xmlHttp.setRequestHeader("Content-Type", "text/html");
		xmlHttp.send('');

		//서버의 Date 값 response new Date()객체에 넣기 전에 시간표준이 GMT로 표시
		return xmlHttp.getResponseHeader("Date");
	}

    function check_shop2(){
        $.ajax({
            url: "/spop/screen/check_shop2",
            type: "POST",
            data: {
                  "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"

            },
            success: function (json) {
                if(json.code=='0'){
                    console.log("매장정보복사");
                }else{
                    console.log("매장정보있음");
                }
            }
        });
    }

    $(document).ready(function (e){
        if ("<?=$fail_flag?>" == "1"){
            $('#switch_customer').trigger('click');
            var phn_array = <? echo json_encode($fail_list)?>;
            var append_str = "";
            var cnt = 0;
            phn_array.forEach(function(phn){
                cnt++;
                append_str += '<li class="todo-list-item"><span name="input_phone_no">' + phn.phnno + '</span><button class="btn sm del fr" id="num_del">삭제</button></li>';
            });
            $('#num_list').append(append_str);
            $("#input_phone_count").text(cnt);
            $("#reception_list").attr("hidden", true);
            $("#reception_list_parent").append("발송날짜 : <?=$fail_date?> (<?=number_format($fail_cnt)?>건)");
        }
    });
</script>
