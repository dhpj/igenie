		  <meta http-equiv="Expires" content="0">
        <meta http-equiv="Pragma" content="no-cache">
    <script type="text/javascript" src="/js/plugins/moment-with-locales.js"></script>
    <script type="text/javascript" src="/js/plugins/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
<!--
	var edit_control = "templi_cont";
//-->
</script>
<input type="hidden" id="navigateURL" value="" />
<!-- 타이틀 영역 -->
				<div class="tit_wrap">
					메시지
				</div>
<!-- 타이틀 영역 END -->
    <!--form action="/biz/sender/Lms/all" method="post" id="sendForm" name="sendForm"-->
<!-- 컨텐츠 전체 영역 -->
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>문자메시지 작성</h3>
						</div>
						<div class="inner_content preview_info">
    						<input type="hidden" id="pf_ynm" value="<?=$rs->spf_company?>"/>
                            <input type="hidden" id="pf_yid" value="<?=$rs->spf_friend?>"/>
                            <input type="hidden" id="sms_sender" value="<? if(empty($rs->spf_sms_callback)) {echo $po->mem_phone;} else {echo $rs->spf_sms_callback;} ?>"/>
                            <input type="hidden" id="max_msg_byte" value="2000"/>
                            <input type="hidden" id="smslms_kind_r" value="N"/>
                            <input type="hidden" name="pf_key" id="pf_key" value="<?=$rs->spf_key?>">
                    		<input type="hidden" name="kind" id="kind" value="<?=$rs->spf_key_type?>">
    						<div class="input_content_wrap">
    							<label class="input_tit">업체명</label>
    							<div class="input_content">
    									<?=($rs->spf_key) ? $rs->spf_company."(".$rs->spf_friend.")" : $po->mem_username ?>
    									<!-- 메시지 미리보기 -->
    									<div class="sms_preview">
    										<div class="msg_type sms"></div>
    										<div class="preview_circle"></div>
    										<div class="preview_round"></div>
    										<div class="preview_msg_wrap">
    											<div class="preview_msg_window sms" id="text"></div>
    											<div class="preview_option">
    												<button class="btn_preview_option" onclick="msg_save('SVAE');">내용저장</button>
    												<button class="btn_preview_option" onclick="open_lmsmsg_list();">불러오기</button>
    											</div>
    										</div>
    										<div class="preview_home"></div>
    									</div><!-- mobile_preview END -->
    											
    							</div>
    						</div><!-- input_content_wrap END -->
    								
    						<div class="input_content_wrap">
    									<label class="input_tit">문자내용</label>
    									<div class="input_content">
    											<div class="msg_box">
    												<div class="txt_ad" id="lms_header"></div>
    												<textarea class="input_msg" id="lms" name="lms" placeholder="메세지를 입력해주세요." onkeyup="onPreviewText();chkword_lms();"></textarea>
    												<span class="txt_byte"><span class="info_desc">40자가 넘어가면 장문 문자로 변경됩니다.</span><span class="msg_type_txt sms" id="lms_limit_str"></span><span id="lms_num">0</span>/<span id="lms_character_limit">80</span> bytes</span>
    												<div class="txt_ad" id="lms_footer"></div>
    											</div>
    											
    											<input type="checkbox" id="advertising_add" class="input_cb"  onclick="insert_advertising(this);" checked><label for="advertising_add">(광고) 삽입</label>&nbsp;&nbsp;
    											<input type="checkbox" id="lms_kakaolink_select" class="lms_kakaolink_select" onclick="insert_kakao_link(this);"><label for="link_add">친구추가 링크 삽입</label>
    											<button class="btn md fr btn_emoji">특수문자</button>												
    											<div class="clearfix"></div>
    											
    											<div class="toggle_layer_wrap toggle_layer_emoji">
    											<h3>특수문자<i class="material-icons icon_close fr" id="icon_close">close</i></h3>
        											<div class="layer_content emoticon_wrap clearfix">
        												<ul>
                        									<li onclick="insert_char('☆')">☆</li>
                        									<li onclick="insert_char('★')">★</li>
                        									<li onclick="insert_char('○')">○</li>
                        									<li onclick="insert_char('●')">●</li>
                        									<li onclick="insert_char('◎')">◎</li>
                        									<li onclick="insert_char('⊙')">⊙</li>
                        									<li onclick="insert_char('◇')">◇</li>
                        									<li onclick="insert_char('◆')">◆</li>
                        									<li onclick="insert_char('□')">□</li>
                        									<li onclick="insert_char('■')">■</li>
                        									<li onclick="insert_char('▣')">▣</li>
                        									<li onclick="insert_char('△')">△</li>
                        									<li onclick="insert_char('▲')">▲</li>
                        									<li onclick="insert_char('▽')">▽</li>
                        									<li onclick="insert_char('▼')">▼</li>
                        									<li onclick="insert_char('◁')">◁</li>
                        									<li onclick="insert_char('◀')">◀</li>
                        									<li onclick="insert_char('▷')">▷</li>
                        									<li onclick="insert_char('▶')">▶</li>
                        									<li onclick="insert_char('♡')">♡</li>
                        									<li onclick="insert_char('♥')">♥</li>
                        									<li onclick="insert_char('♧')">♧</li>
                        									<li onclick="insert_char('♣')">♣</li>
                        									<li onclick="insert_char('◐')">◐</li>
                        									<li onclick="insert_char('◑')">◑</li>
                        									<li onclick="insert_char('▦')">▦</li>
                        									<li onclick="insert_char('☎')">☎</li>
                        									<li onclick="insert_char('♪')">♪</li>
                        									<li onclick="insert_char('♬')">♬</li>
                        									<li onclick="insert_char('※')">※</li>
                        									<li onclick="insert_char('＃')">＃</li>
                        									<li onclick="insert_char('＠')">＠</li>
                        									<li onclick="insert_char('＆')">＆</li>
                        									<li onclick="insert_char('㉿')">㉿</li>
                        									<li onclick="insert_char('™')">™</li>
                        									<li onclick="insert_char('℡')">℡</li>
                        									<li onclick="insert_char('Σ')">Σ</li>
                        									<li onclick="insert_char('Δ')">Δ</li>
                        									<li onclick="insert_char('♂')">♂</li>
                        									<li onclick="insert_char('♀')">♀</li>
        												</ul>
        											</div>
        											<div class="layer_content emoticon_wrap_multi clearfix">
        												<ul>
                        									<li onclick="insert_char('^0^')">^0^</li>
                        									<li onclick="insert_char('☆(~.^)/')">☆(~.^)/</li>
                        									<li onclick="insert_char('づ^0^)づ')">づ^0^)づ</li>
                        									<li onclick="insert_char('(*^.^)♂')">(*^.^)♂</li>
                        									<li onclick="insert_char('(o^^)o')">(o^^)o</li>
                        									<li onclick="insert_char('o(^^o)')">o(^^o)</li>
                        									<li onclick="insert_char('=◑.◐=')">=◑.◐=</li>
                        									<li onclick="insert_char('_(≥∇≤)ノ')">_(≥∇≤)ノ</li>
                        									<li onclick="insert_char('q⊙.⊙p')">q⊙.⊙p</li>
                        									<li onclick="insert_char('^.^')">^.^</li>
                        									<li onclick="insert_char('(^.^)V')">(^.^)V</li>
                        									<li onclick="insert_char('*^^*')">*^^*</li>
                        									<li onclick="insert_char('^o^~~♬')">^o^~~♬</li>
                        									<li onclick="insert_char('^.~')">^.~</li>
                        									<li onclick="insert_char('S(*^___^*)S')">S(*^___^*)S</li>
                        									<li onclick="insert_char('^Δ^')">^Δ^</li>
                        									<li onclick="insert_char('＼(*^▽^*)ノ')">＼(*^▽^*)ノ</li>
                        									<li onclick="insert_char('^L^')">^L^</li>
                        									<li onclick="insert_char('^ε^')">^ε^</li>
                        									<li onclick="insert_char('^_^')">^_^</li>
                        									<li onclick="insert_char('(ノ^O^)ノ')">(ノ^O^)ノ</li>
        												</ul>
        											</div>
    										</div>
    									</div>
    						</div><!-- input_content_wrap END -->
    						<!-- script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script-->
    						<script >
    							$(".btn_emoji").click(function () {
    							$(".toggle_layer_emoji").slideToggle("fast");
    							});
    							
    							$(".icon_close").click(function () {
    							$(this).parent().parent(".toggle_layer_wrap").slideToggle("fast")
    							});
    						</script>
    						<div class="input_content_wrap">
    									<label class="input_tit">이미지</label>
    									<div class="input_content">
    										<button class="btn_add" onclick="add_mms_image('')">이미지 추가하기</button>
    										<p class="noted">* 이미지 추가시 자동으로 MMS로 변환됩니다.</p>
    										
    										<div id="pre_set" last-id="0" style="display: none;">
    											<div style="margin-top: 10px;">
    												<div class="delete_box fr"><button class="btn dark fr btn_minus" style="margin-left: 5px" onclick="delete_mms_image(this)"></button></div>
    												<div style="overflow: hidden;">
    													<div style="overflow: hidden;">
    														<input type="hidden" id="userfile" name="userfile" />
    														<input type="hidden" id="urluserfile" name="urluserfile" />
    														<label class="file" title=""><input type="file" id="fileInput" name="fileInput" accept="image/jpeg" onchange="this.parentNode.setAttribute('title', this.value.replace(/^.*[\\/]/, ''));chageInputFile(this);" /></label>
    													</div>
    												</div>
    											</div>													
    											<div class="selection_content"></div>
    										</div>
    										<div id="mms_image"></div>
    									</div>
    						</div><!-- input_content_wrap END -->
    						<!-- 버튼 추가/삭제 스크립트 -->
    						<script type="text/javascript">
    						 function add_mms_image(x) {
    						    // 원본 찾아서 pre_set으로 저장.
    						    var pre_set = document.getElementById('pre_set');
    						    // last-id 속성에서 필드ID르 쓸값 찾고
    						    var fieldid = parseInt(pre_set.getAttribute('last-id'));

    						    if (fieldid < 3) {	// 3개만 생성
        						    if ($("#userfile" + fieldid).val() == "") {
        								$(".content").html("이미지를 추가하시기 전에 " + fieldid +"번 이미지를 업로드 해주세요.");
        								$('#myModal').modal({backdrop: 'static'});
        						    } else {
            						    // 다음에 필드ID가 중복되지 않도록 1 증가.
            						    pre_set.setAttribute('last-id', fieldid + 1 );
            						
            						    // 복사할 div 엘리먼트 생성
            						    var div = document.createElement('div');
    
            						 	// 내용 복사
            						    var pre_set_innerHTML = pre_set.innerHTML;
            						    pre_set_innerHTML = pre_set_innerHTML.replace("\"userfile\"", ("\"userfile" + (fieldid + 1)) + "\"");
            						    pre_set_innerHTML = pre_set_innerHTML.replace("\"userfile\"", ("\"userfile" + (fieldid + 1)) + "\"");
            						    pre_set_innerHTML = pre_set_innerHTML.replace("\"fileInput\"", ("\"fileInput" + (fieldid + 1)) + "\"");
            						    pre_set_innerHTML = pre_set_innerHTML.replace("\"fileInput\"", ("\"fileInput" + (fieldid + 1)) + "\"");
            						    pre_set_innerHTML = pre_set_innerHTML.replace("\"urluserfile\"", ("\"urluserfile" + (fieldid + 1)) + "\"");
            						    pre_set_innerHTML = pre_set_innerHTML.replace("\"urluserfile\"", ("\"urluserfile" + (fieldid + 1)) + "\"");
            						    
            						    div.innerHTML = pre_set_innerHTML;
            						    // 복사된 엘리먼트의 id를 'field-data-XX'가 되도록 지정.
            						    div.id = 'mms-image-' + (fieldid);
            						    div.setAttribute('name', "mms-images");
            						    // selection_content 영역에 내용 변경.
            						    var temp = div.getElementsByClassName('selection_content')[0];
            						    temp.innerText = x;
            						    // delete_box에 삭제할 fieldid 정보 건네기
            						    var deleteBox = div.getElementsByClassName('delete_box')[0];
            						    // target이라는 속성에 삭제할 div id 저장
            						    deleteBox.setAttribute('target',div.id);
            						    // #mms_image에 복사한 div 추가.
            						    document.getElementById('mms_image').appendChild(div);
        						    }
    						    } 
    						}
    						
    						function delete_mms_image(obj) {
								var pre_set = document.getElementById('pre_set');
								var fieldid = parseInt(pre_set.getAttribute('last-id'));
									
                                // 삭제할 ID 정보 찾기
                                var target = obj.parentNode.getAttribute('target');
                                // 삭제할 element 찾기
                                var field = document.getElementById(target);
                                // filedId 검색 및 마지막 숫자만 분리
								var filedId = field.id;
								var fieldIdNo = parseInt(filedId.substring(filedId.lastIndexOf("-") + 1));


								img_delete(fieldIdNo + 1, fieldid - 1);
								
                                // #field 에서 삭제할 element 제거하기
							    document.getElementById('mms_image').removeChild(field);

							    $("#image"+(fieldIdNo + 1)).remove();

								//$("#btn_preview_div" + (fieldIdNo + 1)).remove();

								for (var i = (fieldIdNo + 1); i < fieldid; i++) {
									var rowTargetId = "mms-image-" + i;
									var rowTarget = document.getElementById(rowTargetId);
									
									modFieldIdNo = i - 1;
									rowTarget.id = "mms-image-" + modFieldIdNo;

									//var preViewTargetId = "btn_preview_div" + (i + 1);
									//var preViewTarget = document.getElementById("btn_preview_div" + (i + 1));
									//preViewTarget.id = "btn_preview_div" + i;
									
									//var btnRreViewTarget = document.getElementById("btn_preview" + (i + 1));
									//btnRreViewTarget.id = "btn_preview" + i;

									
								    // delete_box에 삭제할 fieldid 정보 건네기
								    var deleteBox = rowTarget.getElementsByClassName('delete_box')[0];
								    // target이라는 속성에 삭제할 div id 저장
								    deleteBox.setAttribute('target',rowTarget.id);
									
									var rowTargetHtml = rowTarget.innerHTML;
									
									$("#image"+(i + 1)).attr('id', "image" + i);

									rowTargetHtml = rowTargetHtml.replace("\"userfile" + (i + 1) + "\"", ("\"userfile" + i) + "\"");
									rowTargetHtml = rowTargetHtml.replace("\"userfile" + (i + 1) + "\"", ("\"userfile" + i) + "\"");
        						    rowTargetHtml = rowTargetHtml.replace("\"userfile" + (i + 1) + "\"", ("\"userfile" + i) + "\"");
									rowTargetHtml = rowTargetHtml.replace("\"fileInput" + (i + 1) + "\"", ("\"fileInput" + i) + "\"");
        						    rowTargetHtml = rowTargetHtml.replace("\"fileInput" + (i + 1) + "\"", ("\"fileInput" + i) + "\"");
									rowTargetHtml = rowTargetHtml.replace("\"urluserfile" + (i + 1) + "\"", ("\"urluserfile" + i) + "\"");
        						    rowTargetHtml = rowTargetHtml.replace("\"urluserfile" + (i + 1) + "\"", ("\"urluserfile" + i) + "\"");
					
									rowTarget.innerHTML = rowTargetHtml;
								}

								fieldid -= 1;
							    pre_set.setAttribute('last-id', fieldid);
							    if (fieldid == 0) {
							    	$("#smslms_kind").val("");
							    	chkword_lms();
							    }
    						}
    						
    						function chageInputFile(obj) {
								if(window.FileReader){  // modern browser
									var filename = $(obj)[0].files[0].name;
								} else {  // old IE
									var filename = $(obj).val().split('/').pop().split('\\').pop();  // 파일명만 추출
								}
								
							 	var fileInputId = $(obj).attr('id');
							 	var userfileId = "userfile" + fileInputId.substr(fileInputId.length - 1, 1);
							 	
							 	
								$("#"+userfileId).val(filename);
								img_upload(userfileId, fileInputId);
								chkword_lms();
					
    						}
    						</script>
    						
    						<div class="input_content_wrap">
    							<label class="input_tit">발송방법</label>
    							<input type="hidden" name="mem_2nd_send" id="mem_2nd_send" value="<?=$mem->mem_2nd_send ?>" />
    							<input type="hidden" name="smslms_kind" id="smslms_kind" />
    							<div class="input_content">
    								<select>
        								<option disabled="disabled" <?=($mem->mem_2nd_send=="GREEN_SHOT") ? "selected" : ""?>>인터넷문자 WEB(A)</option>
        								<option disabled="disabled" <?=($mem->mem_2nd_send=="NASELF") ? "selected" : ""?>>일반문자 WEB(B)</option>
    								</select>
    							</div>
    						</div><!-- input_content_wrap END -->
    						<?if(!$fail_mst_id) { echo $fail_mst_id;?>
    						
    						<div class="input_content_wrap">
    							<label class="input_tit">수신고객 선택</label>
    							<div class="input_content">
    								<div class="checks">
    									<input type="radio" name="customer" value="upload" id="switch_upload" checked /><label for="switch_upload">파일업로드로 전송</label>
    									<input type="radio" name="customer" value="db" id="switch_select" /><label for="switch_select" style="margin-left: -1px;">그룹별 선택</label>
    									<input type="radio" name="customer" value="private" id="switch_customer" /><label for="switch_customer" style="margin-left: -1px;">연락처 직접 입력</label>
    								    <div class="clearfix" style="margin-bottom: 10px;"></div>
    								   
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
    												총 발송 <span class="num" id="upload_tel_count">0</span>건
    											</div>
    										</div>
    								    </div>
    								    <div id="db">
    										<div class="msg_box form_check">
    											<div class="send_list">
    												<ul>
    												<li class="check_all"><input type="checkbox" name="checkAll" id="all"><label for="all">전체 선택</label></li>
    												<?
    												$kindCount = 1;
    												foreach($kind as $r) { log_message("ERROR", "r->ab_kind : ".$r->ab_kind." r->ab_kind_cnt : ".$r->ab_kind_cnt);?>
    												
    												<li><input type="checkbox" name="checkOne" id="group<?=$kindCount ?>" value="<?=($r->ab_kind == "") ? 'NONE' : $r->ab_kind ?>"><label for="group<?=$kindCount ?>"><?= $r->ab_kind == '' ? '구분없음' : $r->ab_kind ?></label><span class="send_amount">(<?=$r->ab_kind_cnt ?>명)</span></li>
    												<?
    												$kindCount += 1;
    												}
    												
    												?>
    												</ul>
    											</div>
    											<div class="bottom tr">
    												총 발송 <span class="num" id="groupSendTotal">0</span>건
    											</div>
    										</div>
    										<!-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>-->  
    										<script type="text/javascript">
    										
    										
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
    														$('#myModal').modal({backdrop: 'static'});
    														this.checked = false;
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
    								</div>												
    							</div>
    						</div><!-- input_content_wrap END -->
    						<? } else { ?>
							<div class="input_content_wrap">
								<label class="input_tit">수신고객</label>
								<div class="input_content">
									<div class="msg_box form_check">
										<div class="send_list">
											발송 실패 고객
										</div>
										<div class="bottom tr">
											총 발송 <span class="num" id="not_friend_list"><?=$fail_qty?></span>건
										</div>
										<input type="hidden" id="customer_all_send" value="" />
										<input type="hidden" id="friend_list" value="" />
									</div>
								</div>
							</div>																																								    						
    						<? } ?>
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
    													<div class='input-group date fl' id='datetimepicker' style="width: 200px;">
    									                    <input type='text' class="form-control" id="reserve"/>
    									                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    													</div>
    													<select style="width: 80px; margin-left: 10px;" id="reserve_hours">
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
    													<select style="width: 80px; margin-left: 10px;" id="reserve_minutes">
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
        															$('#datetimepicker').datepicker({format:"yyyy-mm-dd"}).datepicker("setDate", "now");
    
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
    
        													//$('#datetimepicker').datepicker({format:"yyyy-mm-dd"}).datepicker("setDate", "now");
    																									
                                    						//var default_date = moment().add(10,'minutes');
                                    						//var max_date = moment().add(1,'months').add(15,'minutes');
    //                                 						$('#datetimepicker').datepicker({
    //                                 							locale : 'ko',
    //                                 							format : 'YYYY-MM-DD',
    //                                 							defaultDate : moment(),
    //                                 							//minDate: moment().add(10,'minutes'),
    //                                 							//maxDate: moment().add(1,'months').add(15,'minutes'),
    //                                 							ignoreReadonly: true
    //                                 						});
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
    											<button class="btn md dark fr" id="test_add-todo-button">전화번호 추가</button>
    											<div style="overflow: hidden;">
    												<input type="text" id="test_add-todo-input" name="phoneNum" maxlength="13" style="width:100%; margin-right: -2px;" placeholder="테스트 발송 연락처를 입력해 주세요">
    								    		</div>
    							    		</div> 
    									</div>
    									<button class="btn md dark fr" id="btn-test-send" onclick="test_send();">테스트 발송</button>
    								</div>
    								<br>
    							</div>
    						</div>

						</div><!-- inner_content END -->
					</div><!-- form_section END -->

					<div class="btn_group_lg">
						<!-- button class="btn lg">취소</button-->
						<button class="btn lg yellow" onclick="all_send();">보내기</button>
					</div>
					<div class="footer">
						<div class="cs_info">
							<div class="cs_call">
								<span>1522-7985</span>
							</div>
							<div class="cs_support">
								<a href="#"><span class="icon_cs request">1:1문의</span></a>
								<a href="#"><span class="icon_cs remote">원격지원</span></a>
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
				</div><!-- mArticle END -->
<!-- 컨텐츠 전체 영역 END -->
<!-- input 숫자만 입력가능/자동 하이픈 -->
<script type="text/javascript">
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
	<? if(!$fail_mst_id) { ?>	
	var phoneNum1 = document.getElementById('add-todo-input');
	phoneNum1.onkeyup = function(event){
			event = event || window.event;
			var _val = this.value.trim();
			this.value = autoHypenPhone(_val) ;
	}
	<? } ?>
	var phoneNum2 = document.getElementById('test_add-todo-input');
	phoneNum2.onkeyup = function(event){
			event = event || window.event;
			var _val = this.value.trim();
			this.value = autoHypenPhone(_val) ;
	}
</script>
<? if(!$fail_mst_id) { ?>	
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
<? } ?>
<!-- 두번째 -->
<script >
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

	<div class="modal select fade" id="sendbox_select" tabindex="-1" role="dialog"
		 aria-labelledby="myModalLabel" aria-hidden="true" style="overflow:hidden;">
		<div class="modal-dialog modal-lg sendbox-dialog" id="modal">
			<div class="clear modal-content">
				<h4 class="modal-title" align="center">내 문자함 (친구톡)</h4>
				<div class="modal-body select-body">
					<div class="clear sendbox-tab_wrap">
						 <ul class="sendbox-tab">
							<li class="on"><a href="javascript:void(bind_sendbox('history'))">보낸문자</a></li>
							<li><a href="javascript:void(bind_sendbox('save'))">저장문자</a></li>
						 </ul>
						 <div style="float:right;">
							 <select class="select2 input-width-medium" id="searchType_sendbox" onchange="getSelectValue(this.form);">
								<option value="subject">문자제목</option>
								<option value="content">문자내용</option>
							 </select>&nbsp;
							 <input type="text" class="form-control input-width-medium inline"
									  id="searchFor_sendbox" name="search" placeholder="검색어 입력" value=""/>&nbsp;
							 <input type="button" class="btn btn-default" id="check" value="조회" onclick="location.href='javascript:search_sendbox();'"/>
						 </div>
					</div>


					<div class="widget-content" id="sendbox_list">


					</div>
					<div class="btn_wrap" align="center">
						<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
						<button type="button" class="btn btn-primary" id="code" name="code" onclick="select_sendbox();">확인</button>
						<br/><br/>
					</div>
				</div>
			</div>
		</div>
	</div>




	 <!--프로필 선택-->
    <div class="modal fade" id="profile_danger" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal" style="width:400px;">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                        <div class="row align-center" id="danger" style="height: 300px; padding-top: 70px;">
                            <p class="alert alert-danger">
                                    프로필이 없습니다. 프로필을 등록해 주세요.
                            </p>
                            <br/><button type="button" class="btn btn-default" data-dismiss="modal" style="margin-top: 85px;">닫기</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal select fade" id="profile_select" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y:hidden;">
        <div class="modal-dialog modal-lg select-dialog" id="modal">
            <div class="modal-content">

                <br/>
                <h4 class="modal-title" align="center">발신프로필 선택하기</h4>
                <div class="modal-body select-body">
                    <div class="select-content">
                        <div class="mb20 mt10 clear">
                            <select class="select2 input-width-medium" id="searchType2" onchange="getSelectValue(this.form);">
                                <option value="all">검색항목</option>
                                <option value="pf_ynm">업체명</option>
                                <option value="pf_key">프로필 키</option>
                            </select>&nbsp;
                            <input type="text" class="form-control input-width-medium inline"
                                   id="searchStr2" name="search" placeholder="검색어 입력" value=""/>&nbsp;
                            <input type="button" class="btn btn-default" id="check" value="조회" onclick="location.href='javascript:search_profile();'"/>
                        </div>

                        <div class="widget-content" id="profile_list">


								</div>
                    </div>
                    <div align="center">
                        <br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary" id="code" name="code" onclick="select_profile();">확인</button>
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
    <div class="modal fade" id="myModalTest" tabindex="-1" role="dialog"
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
                            <button type="button" class="btn btn-primary test-send" onclick="test_move();">확인</button>
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
    <div class="modal fade" id="myModalFilterAll" tabindex="-1" role="dialog"
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
                            <button type="button" class="btn btn-default" data-dismiss="modal">선택추가</button>
                            <button type="button" class="btn btn-primary filter_all">전체추가</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal select fade" id="myModalUserMSGList" tabindex="-1" role="dialog" aria-labelledby="myModalCheckLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg select-dialog">
            <div class="modal-content">
                <br/>
                <h4 class="modal-title" align="center">내 문자함</h4>
                <div class="modal-body select-body" style="height: 600px;">
                    <div >
                        <div style="padding-bottom:20px;">
                            <select class="select2 input-width-medium" id="searchKind">
                                <option value="all">전체</option>
                                <option value="FT">친구</option>
                                <option value="SMS">SMS</option>
                                <option value="LMS">LMS</option>
                                <option value="MMS">MMS</option>
                            </select>&nbsp;
                            <input type="text" class="form-control input-width-medium inline" id="searchMsg" name="searchMsg" placeholder="메시지" value=""/>&nbsp;
                            <input type="button" class="btn btn-default" id="check" value="조회" onclick="search_msg(1)"/>
                        </div>

                        <div class="content" id="modal_user_msg_list" style="overflow-y:scroll; height: 460px;" style="border:1px solid #aaa;">
									
                        </div>
                    </div>
                    <div align="center">
                        <br/>
                        <button type="button" class="btn btn-primary include_phns" name="code" onclick="delete_msg()">선택 삭제</button>
                        <button type="button" class="btn btn-default dismiss" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary include_phns" name="code" onclick="include_msg()">선택 추가</button>
                        <br/><br/>
                    </div>

                </div>
            </div>
        </div>
    </div>
        
    <div class="modal select fade" id="myModalLoadCustomers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden style="overflow-y:hidden;">
        <div class="modal-dialog modal-lg select-dialog">
            <div class="modal-content">
                <br/>
                <h4 class="modal-title" align="center">고객정보 가져오기</h4>
                <div class="modal-body select-body" style="height: 600px;">

                    <div class="mb20 mt10">
                        <div style="padding-bottom:20px;">
                            <select class="select2 input-width-medium" id="searchType">
                                <option value="all">전체</option>
                                <option value="normal">정상</option>
                                <option value="reject">수신거부</option>
                            </select>&nbsp;
                            <input type="text" class="form-control input-width-medium inline"
                                   id="searchStr" name="searchStr" placeholder="전화번호 입력" value=""/>&nbsp;
         고객구분
                            <select class="select2 input-width-medium" id="searchGroup" >    
                            <option value="">전체</option>
                            <?foreach($kind as $r) {?>
                            <option value="<?=$r->ab_kind?>"><?=$r->ab_kind?></option>
                            <?}?>   
                                <!-- 2019.01.17. 이수환 "고객구분없음" 추가 -->
                            	<option value="NONE">고객구분없음</option>   
                            </select>&nbsp;
                            <input type="text" class="form-control input-width-medium inline"
                                   id="searchName" name="searchName" placeholder="고객명 입력" value=""/>&nbsp;
                            <input type="button" class="btn btn-default" id="check" value="조회" onclick="search_question(1)"/>
                        </div>

                        <div class="widget-content" id="modal_customer_list" style="overflow-y:scroll; height: 440px;" style="border:1px solid #aaa;">
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
										//$.uniform.update();

										//$("#fileInput1").on('change', function(){  // 값이 변경되면
										//	if(window.FileReader){  // modern browser
										//		var filename = $(this)[0].files[0].name;
										//	} else {  // old IE
										//		var filename = $(this).val().split('/').pop().split('\\').pop();  // 파일명만 추출
										//	}
										//	$("#userfile1").val(filename);
										//	img_upload("userfile1", "fileInput1");
										    
										//});

										//$("#fileInput2").on('change', function(){  // 값이 변경되면
										//	if(window.FileReader){  // modern browser
										//		var filename = $(this)[0].files[0].name;
										//	} else {  // old IE
										//		var filename = $(this).val().split('/').pop().split('\\').pop();  // 파일명만 추출
										//	}
								 
										//	$("#userfile2").val(filename);
										//	img_upload("userfile2", "fileInput2");

										//});

										//$("#fileInput3").on('change', function(){  // 값이 변경되면
										//	if(window.FileReader){  // modern browser
										//		var filename = $(this)[0].files[0].name;
										//	} else {  // old IE
										//		var filename = $(this).val().split('/').pop().split('\\').pop();  // 파일명만 추출
										//	}
										 
										//	$("#userfile3").val(filename);
										//	img_upload("userfile3", "fileInput3");
											 
										//});


										<?if($fail_mst_id) {?>
										
											set_lms_by_user_msg(<?=$fail_mst_id?>, "P");
											//$("#number_add").hide();
											//$("#number_del").hide();
											//$("#send_select").hide();
											//$("#tel").hide();
											//$("#upload_result").remove();
											//$("#file_upload_div").hide();
											//$("#custom_load_div").hide();
											//$(".tel_content").after('<div class="widget-content" id="upload_result"><p><?=$fail_qty?> 명의 수신자가 지정되었습니다.</p><input type="hidden" id="customer_all_send" value="<?=$fail_qty?>"> </div>');
										<? } ?>
										
										<? if($sendtelauth == 0) { ?>
										$(".content").html("메세지 발송을 위해 발신번호(<? if(empty($rs->spf_sms_callback)) {echo $po->mem_phone;} else {echo $rs->spf_sms_callback;} ?>)를 <BR>사전에 등록 하세요.");
										$('#myModal').modal({backdrop: 'static'});
										<? } ?>
										
									});

									//$('tbody tr').click(function() {
									//	var check = $(this).find('.checker').children('span').is('.checked');

									//	if(check == true) {
									//		$(this).find('td input[type="checkbox"]').prop('checked', true);
									//		$(this).addClass('checked');
									//	} else {
									//		$(this).find('td input[type="checkbox"]').prop('checked', false);
									//		$(this).removeClass('checked');
									//	}
									//	$.uniform.update();
									//});

									//수신번호 전체 선택 안되는 현상 수정
									//$("#sel_all").click(function(){
									//	if($("#sel_all").prop("checked")) {
									//		$("input:checkbox[name='selCustomer']").prop("checked",true);
									//		$.uniform.update();
									//	} else {
									//		$("input:checkbox[name='selCustomer']").prop("checked",false);
									//		$.uniform.update();
									//	}
									//});
									</script>
                        </div>
                    </div>
                    <div align="center">
                        <br/>
                        <button type="button" class="btn btn-default dismiss" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary include_phns" name="code" onclick="include_customer()">선택 추가</button>
                        <button type="button" class="btn btn-primary include_phns" name="code" onclick="include_customer('GS')">구분 전체추가</button>
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
            height: 650px;
        }
        .select-body {
            width: 100%;
            height: 650px;
        }
        .modal-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }
    </style>
	<script type="text/javascript">
	$("#nav li.nav10").addClass("current open");

    var chusermode = "N" ;	// 고객이 클릭을 아직 하지 않았다.
    
    function insert_char(n){
        var adds = n ;
    
        var txtArea = document.getElementById('lms');
        var txtValue = txtArea.value;
        var selectPos = txtArea.selectionStart; // 커서 위치 지정
        var beforeTxt = txtValue.substring(0, selectPos);  // 기존텍스트 ~ 커서시작점 까지의 문자
        var afterTxt = txtValue.substring(txtArea.selectionEnd, txtValue.length);   // 커서끝지점 ~ 기존텍스트 까지의 문자
    
        txtArea.value = beforeTxt + adds + afterTxt;
    
        selectPos = selectPos + adds.length;
        txtArea.selectionStart = selectPos; // 커서 시작점을 추가 삽입된 텍스트 이후로 지정
        txtArea.selectionEnd = selectPos; // 커서 끝지점을 추가 삽입된 텍스트 이후로 지정

        $("#lms").focus(); 
        onPreviewText();
        resize_cont(txtArea);
        chkword_lms();
    }
    
    var mms_id = "";
    var old_mem_send = "LMS";
    
// 	function scroll_prevent() {
// 		window.onscroll = function(){
// 			var arr = document.getElementsByTagName('textarea');
// 			for( var i = 0 ; i < arr.length ; i ++  ){
// 				try { arr[i].blur(); }catch(e){}
// 			}
// 		}
// 	}

	$("#myModal").unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			$(".enter").click();
		}
	});

	$(document).ready(function() {
		<?if(date('H') < '08' || date('H') > '16') {?>
		$(".content").html("저녁9시~아침8시 까지는 문자 발송이 차단<?=($mem->mem_2nd_send) ? '되어  일반문자 로만 전송됩니다.' : ' 됩니다.'?>");
		$('#myModal').modal({backdrop: 'static'});
		$('#myModal').on('hidden.bs.modal', function () {
			location.href='/biz/sender/send/message';
		});
        <?}?>	
		var phn = "";
		<?if( $mem->mem_2nd_send=='015') { ?>
			phn = '080-1111-1111';
		<?} else if( $mem->mem_2nd_send=='NASELF') { ?>
			phn = '080-855-5947';
		<?} else if($mem->mem_2nd_send=='GREEN_SHOT') { ?>
			phn = '080-156-0186';
		<?} else if($mem->mem_2nd_send=='PHONE') { ?>
			phn = '080-889-8383';
		<?}?>

		var lms_header = "(광고) " + $("#pf_ynm").val();
		var lms_footer = "무료수신거부 : " + phn;
		//var content = lms_header  + "\n\n" + lms_footer; 
		//$("#lms").val(content);
        $("#lms_header").html(lms_header);
        $("#lms_footer").html(lms_footer);		 
		onPreviewText();
		chkword_lms();
	});
	// 템플릿 클릭 선택
	var selected="";
	$(".scrolltbody tbody tr").click(function() {
		$(".scrolltbody tbody tr").css("background-color", "white");
		$(".scrolltbody tbody tr").css("color", "dimgrey");
		$(this).css("background-color", "#4d7496");
		$(this).css("color", "white");
		selected = $(this).find(".pf_key").text();
	});

	//미리보기 클릭시 그림자 효과 없애기
	//$("#text").click(function() {
	//	$(this).css("box-shadow","none");
	//});
	$("#linkView").click(function() {
		$(this).css("box-shadow","none");
	});
	// 템플릿내용 미리보기
	// var contents = document.getElementById('templi_cont').value;
	//var height = $(".message").prop("scrollHeight");
	//if( height < 408 ) {
	//	$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
	//} else {
	//	$("#text").css("height","1px").css("height","460px");
	//}
	$(function() {
		$("#templi_cont.autosize").keyup(function () {
			//var height = $(".message").prop("scrollHeight");
			//if( height < 408 ) {
			////	$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
			//} else {
			//	var message = $("#text").val();
			//	var height = $(".message").prop("scrollHeight");
			//	if(message==""){
			//		$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
			//	} else {
			//		$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
			//		$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
			//	}
			//}
		});
	});

    function click_bntimage(no) {
        $("#image_file"+no).trigger("click");

    }
    	
	// 미리보기 2차발신 우선
	function onPreviewText() {
		var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var msg_text = "";	

		if ($("#lms").val().replace(/\n/g, "").length > 0) {
			msg_text = msg_text + lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
		} else {
			msg_text = msg_text + lms_header_temp + "\n\n메세지를 입력해주세요.\n\n" + lms_footer_temp;
		}

		msg_text = msg_text.replace(/\n/g, "<br>");
		
		preview_text = $("#text").html();
		if(preview_text.lastIndexOf("\">") < 0) {
			preview_text = "";
		} else {
			preview_text = preview_text.substring(0, preview_text.lastIndexOf("\">") + 2);
		}
		$("#text").html(preview_text + msg_text);

		
		//var file = document.getElementById('filecount').value;
		//if (file == ''){
		//	var cont=$('#lms').val();
		//	if (!cont || cont == "" ) {
		//		cont=$('#templi_cont').val();
		//	}
		//	$('#text').val(cont);

			//var height = $(".message").prop("scrollHeight");
			//if( height < 408 ) {
			//	$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
			//} else {
			//	var message = $("#text").val();
			//	if(message==""){
			//		$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
			//	} else {
			//		$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
			//		$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
			//	}
			//}
			//if (cont && cont == "") {
			//	$('#text').val(' 메세지를 입력해주세요.');
			//}
		//}
		///scroll_prevent();
	}

	//프로필 선택 모달 확인
	//function select_profile() {
	//	var activeTr = $("#profile_list tbody tr.active");

	//	if(activeTr.length == 0 ) {
	//		$("#profile_select").css("tabindex","-1");
	//		$("#myModal").css("tabindex","1");
	//		$(".content").html("프로필을 먼저 선택해주세요.");
	//		$("#myModal").modal({backdrop: 'static'});
	//		$('#myModal').on('hidden.bs.modal', function () {
	//			$("#profile_select").focus();
	//		});
	//	} else {
	//		var profileKey = activeTr.find('.pf_key').text();

	//		$('#send_friend_content').html('').load(
	//			"/biz/sender/send/select_profile_dt",
	//			{
	//				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
	//				"profileKey": profileKey
	//			},
	//			function() {
	//				$("#profile_select").modal("hide");
	//				$('.uniform').uniform();

	//				var current_btn_num = $("#friendtalk_table tr:last").attr("name");
	//				$("#no").attr("hidden", false);
	//				$("#btn_type_td").attr("hidden", false);
	//				$("#wl_1_"+current_btn_num).attr("hidden",false);
	//				$("#wl_2_"+current_btn_num).attr("hidden",false);
	//				$("#wl_3_"+current_btn_num).attr("hidden",false);
	//				$("#wl_4_"+current_btn_num).attr("hidden",false);
	//				$("#wl_5_"+current_btn_num).attr("hidden",false);
	//				$("#btn_del").attr("hidden", false);
	//				$("#btn_add_msg").attr("hidden", true);
	//				$("#btn_type1").val("WL");
	//				$("#btn_type1").select2();
	//				//$("#btn_type1 option:eq(1)").prop("selected", true);
	//				//$("#btn_type1").select2().select2("val", "WL");
	//				var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
	//				var pf_ynm = $("#pf_ynm").val();
	//				$("#btn_name2_1").val("세일전단지");//pf_ynm);
	//				$("input[name=btn_url21]").val("http://plus-talk.kakao.com/plus/home/" + pf_yid);
	//				refreshPreview();
	//				chkword_templi();
	//				link_name(document.getElementById("btn_type1"),1);
	//			}
	//		);
	//	}
	//}

	//function refreshPreview() {
	//	var html = '<p class="message">' +
	//	'        <textarea id="text" data-always-visible="1"' +
	///	'data-rail-visible="0" cols="25" readonly="readonly"' +
	//	'style="margin-left:-5px;margin-top:-4px;overflow:hidden;border:0;background-color:white;resize:none;cursor:default;" placeholder=" 메세지를 입력해주세요."> 메세지를 입력해주세요.</textarea>' +
	//	'</p>';
	//	$('#phone_standard').html('').html(html);
	//	onPreviewText();
	//}

	//function search_enter() {
	//	$(document).unbind("keyup").keyup(function (e) {
	//		var code = e.which;
	//		if (code == 13) {
	//			$("#check").click();
	//		}
	//	});
	//}

	//친구톡, 2차발신 입력란 가변 처리
	//function resize_cont(obj) {
	//	$(this).on('keyup', function(evt){
	//		if(evt.keyCode == 8){
	//			if (obj.scrollHeight < 103){
	//				obj.style.height = "1px";
	//				obj.style.height = "103px";
	//			}
	//		}
	//	});
	//	if (obj.scrollHeight <= 409 && obj.scrollHeight > 113 ) {
	//		obj.style.height = "1px";
	//		obj.style.height = (20 + obj.scrollHeight) + "px";
	//	} else if (obj.scrollHeight > 412){
	//		obj.style.height = "1px";
	//		obj.style.height = "412px";
	//	} else if (obj.scrollHeight <= 430 && obj.scrollHeight > 133) {
	//		obj.style.height = "1px";
	//		obj.style.height = (obj.scrollHeight-20) + "px";
	//	} else if (obj.scrollHeight < 103){
	//		obj.style.height = "1px";
	//		obj.style.height = "103px";
	//	}
	//	obj.focus();
	//}

	<?/*---------------------------*
		| 카카오 친구추가 링크 넣기 |
		*---------------------------*/?>
	function insert_kakao_link(check){
		var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
		var long_url = "http://plus-talk.kakao.com/plus/home/" + pf_yid;
		$.ajax({
			url: "/biz/short_url",
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
				var content;
				var index;
				var cont;
				//cont = $("#lms").val();
				cont = $("#lms_footer").html().replace("<br>", "\n");
				if (check.checked) {
					if (new RegExp(/무료수신거부/gi).test(cont)) {
						content = cont.replace("무료수신거부", short_url + "\n무료수신거부");
						$("#lms_footer").html(content.replace("\n", "<br>"));
						//content = $("#lms").val().replace("무료수신거부", short_url + "무료수신거부");
						//$("#lms").val(content).focus();
					} else {
						content = cont.replace(cont, cont + short_url);
						$("#lms_footer").html(content.replace("\n", "<br>"));
						//content = $("#lms").val().replace(cont, cont + "<BR>" + short_url);
						//$("#lms").val(content).focus();
					}
					//2차발신 입력란 가변 처리

					//resize_cont(document.getElementById("lms"));
					chkword_lms();
					//미리보기 insert_kakao_link
					//onPreviewText();
				} else {
					if ((new RegExp(/무료수신거부/gi).test(cont))) {
						index = cont.indexOf("무료수신거부");
						var first = cont.slice(0, index - 1).replace(short_url, "");
						var last = cont.slice(index);
						content = first + last;
						//$("#lms").val(first + last).focus();
						$("#lms_footer").html(content.replace("\n", "<br>"));
						chkword_lms();
					} else {
						index = cont.indexOf(short_url);
						content = cont.slice(0, index - 1);
						$("#lms_footer").html(content.replace("\n", "<br>"));
						//$("#lms").val(cont.slice(0, index - 1));
						chkword_lms();
					}
					//onPreviewText(check);
					//if ($("#lms").prop("scrollHeight") < 412 && $("#lms").prop("scrollHeight") > 103) {
					//	$("#lms").css("height", "1px").css("height", ($("#lms").prop("scrollHeight") - 1) + "px");
					//} else {
					//	resize_cont(document.getElementById("lms"));
					//}
					chkword_lms();
				}
				onPreviewText();
			}
		});
	}

	<? /*-------------*
		| (광고) 넣기 |
		*-------------*/?>
	function insert_advertising(check){
		var cont;
		var content;
		cont = $("#lms_header").html().replace("<br>", "\n");
		if (check.checked) {
			if (!(new RegExp(/\(광고\)/gi).test(cont))) {
    			content = "(광고)" + cont;
    			$("#lms_header").html(content.replace("\n", "<br>"));
    			chkword_lms();
			}
		} else {
			if ((new RegExp(/\(광고\)/gi).test(cont))) {
				index = cont.indexOf("(광고)");
				content = cont.slice(0, index - 1).replace("(광고)", "");
				//$("#lms").val(first + last).focus();
				$("#lms_header").html(content.replace("\n", "<br>"));
				chkword_lms();
			}
		}
		onPreviewText();
	}
	
	//예약발송 체크박스 체크
	//function ReserveCheck() {
	//	if($("#reserve_check").is(":checked") == true) {
	//		$(".reserve_content").attr("hidden", false);
	//	} else {
	//		$(".reserve_content").attr("hidden", true);
	//	}
	//}

	//발신번호 추가
	//function add_number(phn, kind, name) {
	//	if(typeof(kind)=="undefined") { kind = ""; }
	//	if(typeof(name)=="undefined") { name = ""; }
	//	var lastItemNo = $("#tel tbody tr:last").attr("class");
	//	var no = parseInt(lastItemNo) + 1;
	//	var table = document.getElementById("tel");
	//	var row_index = table.rows.length;
	//	var phnNumVal = '';
	//	if (typeof phn != 'undefined') {
	//		phnNumVal = ' value=' +  phn + ' ';
	//	}

		/*if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else */
	//	if ($("input:checkbox[value='1']").is(":checked") == true) {
	//		var custom_check = row_index-1;
	//		if(phn != undefined && no == 2 && $("."+custom_check).find("#tel_number").val() == ""){
	//			$("."+custom_check).remove();
	//			no=1;
	//		}

	//		if (row_index <= 60000) {
	//			if (row_index >= 5) {
	//				var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
	//					'<input class="uniform" name="sel_del" id="'+ no +'" value="' + no + '" type="checkbox"></td>' +
	//					'<td width="15%"><span id="ab_kind">'+kind+'</span></td><td width="15%"><span id="ab_name">'+name+'</span></td>' +
	//					'<td width="40%"><input type="text" ' + phnNumVal +
	//					'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
	//					'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
	//					'<i class="icon-trash"></i> 삭제</a></td></tr>';
	//				$("." + lastItemNo).after(tr);
	//				$("#" + no).uniform();
	//				var height = $("#tel_tbody").prop("scrollHeight");
	//				$("#tel_tbody").css("height", "235px");
	//				$(this).css("overflow", "scroll");
	//				$("tr."+no).focus();
	//				$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
	//			} else {
	//				var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
	//					'<input class="uniform" name="sel_del" id="'+ no +'"  value="' + no + '" type="checkbox"></td>'+
	//					'<td width="15%"><span id="ab_kind">'+kind+'</span></td><td width="15%"><span id="ab_name">'+name+'</span></td>' +
	//					'<td width="40%"><input type="text" ' +  phnNumVal +
	//					'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
	//					'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
	//					'<i class="icon-trash"></i> 삭제</a></td></tr>';
	//				if (no == 1) {
	//					$("#tel_tbody").html(tr);
	//					$("#tel_tbody").css("height", "47px");
	//				} else {
	//					$("." + lastItemNo).after(tr);
	//				}
	//				var height = $("#tel_tbody").prop("scrollHeight");
	//				$("#tel_tbody").css("height", (height) + "px");
	//				$("#"+no).uniform();
	//			}
	//		} else {
	//			$(".content").html("최대 60,000개까지 가능합니다.");
	//			$("#myModal").modal({backdrop: 'static'});
	//		}
	//	} else {
	//		var custom_check = row_index-1;
	//		if(phn != undefined && no == 2 && $("."+custom_check).find("#tel_number").val() == ""){
	//			$("."+custom_check).remove();
	//			no=1;
	//		}

	//		if (row_index <= 60000) {
	//			if (row_index > 5) {
	//				var tr = '<tr class="' + no + '" name="'+no+'" id="tel_tbody_tr" name="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
	//					'<input type="checkbox" name="sel_del" id="'+ no +'"  value="'+no+'" class="uniform"></td>'+
	//					'<td width="15%"><span id="ab_kind">'+kind+'</span></td><td width="15%"><span id="ab_name">'+name+'</span></td>' +
	//					'<td width="40%"><input type="text" ' + phnNumVal +
	//					'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
	//					'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
	//					'<i class="icon-trash"></i> 삭제</a></td></tr>';
	//				$("." + lastItemNo).after(tr);
	//				$("#" + no).uniform();
	//				var height = $("#tel_tbody").prop("scrollHeight");
	//				$("#tel_tbody").css("height", "235px");
	//				$(this).css("overflow", "scroll");
	//				$("tr."+no).focus();
	//				$("#tel_tbody").scrollTop($("#tel_tbody")[0].scrollHeight);
	//			} else {
	//				var tr = '<tr class="' + no + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
	//					'<input class="uniform" name="sel_del" id="'+ no +'"  value="' + no + '" type="checkbox"></td>'+
	//					'<td width="15%"><span id="ab_kind">'+kind+'</span></td><td width="15%"><span id="ab_name">'+name+'</span></td>' +
	//					'<td width="40%"><input type="text" ' +  phnNumVal +
	//					'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
	//					'<td width="20%"><a href="javascript:tel_remove(' + no + ');" id="tel_remove" class="btn btn-sm" title="삭제">' +
	//					'<i class="icon-trash"></i> 삭제</a></td></tr>';
	//				if (no == 1) {
	//					$("#tel_tbody").html(tr);
	//					$("#tel_tbody").css("height", "47px");
	//				} else {
	//					$("." + lastItemNo).after(tr);
	//				}
	//				var height = $("#tel_tbody").prop("scrollHeight");
	//				$("#tel_tbody").css("height", (height) + "px");
	//				$("#"+no).uniform();
	//			}
	//		} else {
	//			$(".content").html("최대 60,000개까지 가능합니다.");
	//			$("#myModal").modal({backdrop: 'static'});
	//		}
	//	}
	//}

	// 발신번호 삭제
	//function tel_remove(obj) {
	//	var table = document.getElementById("tel");
	//	var row_index = table.rows.length;
		/*if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else*/
	//	if(row_index==2) {
	//		var tel_number = $("#tel_number").val();
	//		if(tel_number==""){
	//			$(".content").html("삭제할 수신 번호가 없습니다.");
	//			$("#myModal").modal({backdrop: 'static'});
	//			$("#myModal").unbind("keyup").keyup(function (e) {
	//				var code = e.which;
	//				if (code == 13) {
	//					$(".enter").click();
	//				}
	//			});
	//		} else {
	//			$(".content").html("삭제하시겠습니까?");
	//			$("#myModalCheck").modal({backdrop: 'static'});
	//			$("#myModalCheck").unbind("keyup").keyup(function (e) {
	//				var code = e.which;
	//				if (code == 13) {
	//					tel_del(obj);
	//				}
	//			});
	//			$(".submit").click(function() {
	//				tel_del(obj);
	//				$(".submit").unbind("click");
	//			});
	//		}
	//	} else {
	//		$(".content").html("삭제하시겠습니까?");
	//		$("#myModalCheck").modal({backdrop: 'static'});
	//		$("#myModalCheck").unbind("keyup").keyup(function (e) {
	//			var code = e.which;
	//			if (code == 13) {
	//				tel_del(obj);
	//			}
	//		});
	//		$(".submit").click(function() {
	//			tel_del(obj);
	//			$(".submit").unbind("click");
	//		});
	//	}
	//}

	//function tel_del(obj) {
	//	$("#myModalCheck").modal("hide");
	//	var table = document.getElementById("tel");
	//	var row_index = table.rows.length;
	//	var tr = $("." + obj);
	//	if (row_index != 2) {
	//		if (row_index > 6) {
	//			tr.remove();
	//			var height = $("#tel_tbody").prop("scrollHeight");
	//			$("#tel_tbody").css("height", "235px");
	//			$(this).css("overflow", "scroll");
	//		} else {
	//			tr.remove();
	//			var height = $("#tel_tbody").prop("scrollHeight");
	//			$("#tel_tbody").css("height", (height - 47) + "px");
	//		}
	//	} else {
	//		tr.remove();
	//		var tr = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
	//			'<input class="uniform" name="sel_del" id="' + 1 + '"  value="' + 1 + '" type="checkbox"></td>'+
	//			'<td width="15%"><span id="ab_kind">&nbsp;</span></td><td width="15%"><span id="ab_name">&nbsp;</span></td>' +
	//			'<td width="40%"><input type="text" ' +
	//			'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
	//			'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
	//			'<i class="icon-trash"></i> 삭제</a></td></tr>';
	//		$("#tel_tbody").html(tr);
	//		var height = $("#tel_tbody").prop("scrollHeight");
	//		$("#tel_tbody").css("height", "47px");
	//		$("#1").uniform();
	//	}
	//}

	//발신번호 선택삭제
	//function selectDelRow() {
		/*if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else 
			*/
	//	if ($("input:checkbox[name='sel_del']").is(":checked") == false) {
	//		$(".content").html("삭제할 수신 번호를 선택해주세요.");
	//		$('#myModal').modal({backdrop: 'static'});
	//	} else {
	//		$("input[name=sel_del]:checked").each(function () {
	//			var obj = $(this).val();
	//			var table = document.getElementById("tel");
	//			var row_index = table.rows.length;
	//			if (row_index == 2) {
	//				var tel_number = $("#tel_number").val();
	//				if (tel_number == "") {
	//					$(".content").html("삭제할 수신 번호가 없습니다.");
	//					$("#myModal").modal({backdrop: 'static'});
	//					$("#myModal").unbind("keyup").keyup(function (e) {
	//						var code = e.which;
	//						if (code == 13) {
	//							$(".enter").click();
	//						}
	//					});
	//				} else {
	//					$(".content").html("삭제하시겠습니까?");
	//					$("#myModalCheck").modal({backdrop: 'static'});
	//					$("#myModalCheck").unbind("keyup").keyup(function (e) {
	//						var code = e.which;
	//						if (code == 13) {
	//							$(".submit").click();
	//						}
	//					});
	//					$(".submit").click(function() {
	//						tel_select_del(obj);
	//						$(".submit").unbind("click");
	//					});
	//				}
	//			} else {
	//				$(".content").html("삭제하시겠습니까?");
	//				$("#myModalCheck").modal({backdrop: 'static'});
	//				$("#myModalCheck").unbind("keyup").keyup(function (e) {
	//					var code = e.which;
	//					if (code == 13) {
	//						$(".submit").click();
	//					}
	//				});
	//				$(".submit").click(function() {
	//					tel_select_del(obj);
	//					$(".submit").unbind("click");
	//				});
	//			}
	//		});
	//	}
	//}

	//팝업창 선택 삭제
	//function tel_select_del(obj) {
	//	$("#myModalCheck").modal("hide");
	//	var tr = $("." + obj);
	//	var table = document.getElementById("tel");
	//	var row_index = table.rows.length;
	//	var cnt = $("input[name=sel_del]:checked").length;
	//	var row = row_index-cnt;
	//	if(cnt!=0) {
	//		if (row_index >= 7) {
	//			tr.remove();
	//			var height = $("#tel_tbody").prop("scrollHeight");
	//			$("#tel_tbody").css("height", "235px");
	//			$(this).css("overflow", "scroll");
	//		} else if (row < 6 && row >= 2) {
	//			tr.remove();
	//			var height = $("#tel_tbody").prop("scrollHeight");
	//			$("#tel_tbody").css("height", (row*47-47) + "px");
	//		} else if (1 >= row) {
	//			tr.remove();
	//			if(row==1) {
	//				var tr = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
	//					'<input class="uniform" name="sel_del" id="'+ 1 +'"  value="' + 1 + '" type="checkbox"></td>'+
	//					'<td width="15%"><span id="ab_kind">&nbsp;</span></td><td width="15%"><span id="ab_name">&nbsp;</span></td>' +
	//					'<td width="40%"><input type="text" ' +
	//					'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
	//					'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
	//					'<i class="icon-trash"></i> 삭제</a></td></tr>';
	//				$("#tel_tbody").html(tr);
	//				var height = $("#tel_tbody").prop("scrollHeight");
	//				$("#tel_tbody").css("height", "47px");
	//				$("#1").uniform();
	//			}
	//		}
	//	}
	//}

	//발신번호 숫자만 입력 가능
	function SetNum(obj) {
		/*if($("#pf_key").val()==null || $("#pf_key").val()==''){
			$(".content").html("프로필을 먼저 선택해주세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				obj.value = "";
			});
		} else { */
			var val = obj.value;
			var re = /[^0-9]/gi;
			obj.value = val.replace(re, "");
		//}
	}

	//모달 검색
	function search() {
		var search = $("#search").val();
		var selectBox = $("#selectBox option:selected").val();
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/sender/send/profile6");
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

	//프로파일 모달 검색
	function search_profile() {
		var type = $('#searchType2').val() || 'all';
		var searchFor = $('#searchStr2').val() || '';

		$('#profile_list').html('').load(
			"/biz/sender/send/profile1",
			{
				"search_type": type,
				"search_for": searchFor
				// page 불필요.. 향후에도 필요할 일이 없다고 보임
			}
		);
	}

	function search_sendbox() {
		try {
			open_page_sendbox(1);
		}catch(e){}
	}

	//모달 페이징
	function page(page) {
		var search = $("#search").val();
		var selectBox = $("#selectBox option:selected").val();
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/sender/send/profile2");
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

	function showLimitOver()
	{
		$(".content").html("금일 발송 가능 건수를 모두 사용하였습니다.");
		$('#myModal').modal({backdrop: 'static'});
		$('#filecount').filestyle('clear');
	}


<?/*----------------------------------*
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	*----------------------------------*/?>
	var ms = '<?=($mem->mem_2nd_send) ? substr(strtoupper($mem->mem_2nd_send), 0, 1) : ""?>';
	var ft_price = '<?=$this->Biz_model->price_ft?>';
	var ft_img_price = '<?=$this->Biz_model->price_ft_img?>';
	var sms_price = '<?=$this->Biz_model->price_sms?>';
	var lms_price = '<?=$this->Biz_model->price_lms?>';
	var mms_price = '<?=$this->Biz_model->price_mms?>';
	var phn_price = '<?=$this->Biz_model->price_phn?>';
<?if($mem->mem_2nd_send=='phn') { echo "var resend_price = '".$this->Biz_model->price_phn."';"; }
	else if($mem->mem_2nd_send=='sms') { echo "var resend_price = '".$this->Biz_model->price_sms."';"; }
	else if($mem->mem_2nd_send=='lms') { echo "var resend_price = '".$this->Biz_model->price_lms."';"; }
	else if($mem->mem_2nd_send=='mms') { echo "var resend_price = '".$this->Biz_model->price_mms."';"; }
	else { echo "var resend_price = '0';"; }
?>

	var resend_type = ms;			<?/* 2차발신 종류 */?>
	var resend_type_name = "<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?>";	<?/* 2차발신 이름 */?>
	var charge_type = 0;				<?/* 발신단가 최대값 */?>
	var send_price = 0;

	// 문자 발신 단가 설정
	function set_price() 
	{
		var send_method = $('#mem_2nd_send').val();
		var userfile = ($('#userfile1').val() == undefined) ? "" : $('#userfile1').val(); 
		var smslmskind = $('#smslms_kind').val();
		$('#smslms_kind_r').val(smslmskind);
		send_price = 0;

		//alert("send_method : " + send_method);
		//alert("userfile : " + userfile);
		//alert("smslmskind : " + smslmskind);
		
		switch(send_method) {
		case '015' :
			send_price = <? if($this->Biz_model->price_015 > 0) { echo $this->Biz_model->price_015 ; } else { echo 0; } ?>;
			break;
		case 'PHONE' :
			send_price = <? if($this->Biz_model->price_phn > 0) { echo $this->Biz_model->price_phn ; } else { echo 0; } ?>;
			break;
		case 'GREEN_SHOT' :
			if($('#smslms_kind').val() == 'SMS') {
				send_price = <? if($this->Biz_model->price_grs_sms > 0) { echo $this->Biz_model->price_grs_sms ; } else { echo 0; } ?>;
			} else {
				if(userfile != "") {
					send_price = <? if($this->Biz_model->price_grs_mms > 0) { echo $this->Biz_model->price_grs_mms ; } else { echo 0; } ?>;
					$('#smslms_kind_r').val("MMS");
				} else { 
					send_price = <? if($this->Biz_model->price_grs > 0) { echo $this->Biz_model->price_grs ; } else { echo 0; } ?>;
				}
			}
			break;
		case 'NASELF' :
			if($('#smslms_kind').val() == 'SMS') {
				send_price = <? if($this->Biz_model->price_nas_sms > 0) { echo $this->Biz_model->price_nas_sms ; } else { echo 0; } ?>;
			} else if($('#smslms_kind').val() == 'LMS') {
				if(userfile != "") {
					send_price = <? if($this->Biz_model->price_nas_mms > 0) { echo $this->Biz_model->price_nas_mms ; } else { echo 0; } ?>;
					$('#smslms_kind_r').val("MMS");
				} else {
					send_price = <? if($this->Biz_model->price_nas > 0) { echo $this->Biz_model->price_nas ; } else { echo 0; } ?>;
				}
			} else if($('#smslms_kind').val() == 'MMS') {
				send_price = <? if($this->Biz_model->price_mms > 0) { echo $this->Biz_model->price_mms ; } else { echo 0; } ?>;
			}
			break;
		}
	}
	
	<?/* 발신단가의 최대값과 재발신 방법을 설정 */?>
	function check_resend_method()
	{
		<?/* mms로 지정했어도 이미지가 없으면 LMS 또는 90자 미만이면 sms로 보내야 합니다. */?>
		<?/* 재발신 단가 산정 */?>
<?if($mem->mem_2nd_send=='phn') {	/* 폰문자인경우와 웹문자인 경우 분리하여 가장 높은 단가를 산정합니다. */ ?>
		if ($('#lms').val().replace(/ /gi, "") != "" && resend_price > send_price) //mms일때
			{ charge_type = resend_price; resend_type_name = "폰문자"; resend_type = "P"; }
<?} else {?>
		if (ms=="M" && $("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != "" && $('#lms').val().replace(/ /gi, "") != "" && resend_price > charge_type) <?/* mms일때 */?>
			{ charge_type = resend_price; resend_type_name = "MMS"; resend_type = "M"; }
		if ($("#img_url").val() == "" && $('#lms').val().replace(/ /gi, "") != "" && getByteLength($('#lms')) > 90 && lms_price > charge_type) <?/* mms가 아니고 글자수가 90byte초과일때 */?>
			{ charge_type = lms_price; resend_type_name = "LMS"; resend_type = "L"; }
		if ($("#img_url").val() == "" && $('#lms').val().replace(/ /gi, "") != "" && getByteLength($('#lms')) <= 90 && sms_price > charge_type) <?/* sms일때 */?>
			{ charge_type = sms_price; resend_type_name = "SMS"; resend_type = "S"; }
<?}?>
		<?/* 친구톡, 친구톡 이미지 가격 산정 */?>
		if ($("#img_url").val().replace(/ /gi, "") != undefined && $("#img_url").val() != "" && $('#lms').val().replace(/ /gi, "") == "" && ft_img_price > charge_type) <?/* 친구톡 이미지 */?>
			{ charge_type = ft_img_price; }
		if (ft_price > charge_type)	<?/* 친구톡 */?>
			{ charge_type = ft_price; }
	}
<?/*----------------------------------*
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	*----------------------------------*/?>


	coin=0;

	<?/*----------------------------------------------------------*
	   | 수신번호 목록에서 일부 선택(체크) 하여 선택발송버튼 클릭 |
	   *----------------------------------------------------------*/?>
// 	function select_send() {
// 		window.onscroll = function(){
// 			var arr = document.getElementsByTagName('textarea');
// 			for( var i = 0 ; i < arr.length ; i ++  ){
// 				try { arr[i].off('blur'); }catch(e){}
// 			}
// 		};
// 		/*if($("#pf_key").val()==null || $("#pf_key").val()==''){
// 			$(".content").html("프로필을 먼저 선택해주세요.");
// 			$('#myModal').modal({backdrop: 'static'});
// 		} else*/
// 		if ('0' == undefined && document.getElementById("templi_cont").value.replace(/ /gi,"") == "") {
// 			$(".content").html("템플릿 내용을 입력해주세요.");
// 			$('#myModal').modal({backdrop: 'static'});
// 			$('#myModal').on('hidden.bs.modal', function () {
// 				$("#templi_cont").focus();
// 			});
// 		} else if ($("input:checkbox[id='lms_select']").is(":checked") == true && $('#lms').val().replace(/ /gi,"")==""){
//			$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용을 입력해주세요.");
// 			$('#myModal').modal({backdrop: 'static'});
// 			$('#myModal').on('hidden.bs.modal', function () {
// 				$("#lms").focus();
// 			});
// 		} else if ($("#img_url").val() != undefined && $("#img_url").val() != "" && $("#img_link").val().replace(/ /gi, "") == "") {
// 			// 이미지 url이 등록되면 이미지 링크도 넣어야 합니다.
// 			$(".content").html("이미지 링크를 입력해주세요.");
// 			$('#myModal').modal({backdrop: 'static'});
// 			$('#myModal').on('hidden.bs.modal', function () {
// 				$("#img_link").focus();
// 			});
// 		} else if ($("input:checkbox[name='sel_del']").is(":checked") == false) {
// 			$(".content").html("수신 번호를 선택해주세요.");
// 			$('#myModal').modal({backdrop: 'static'});
// 			$('#myModal').on('hidden.bs.modal', function () {
// 				$("#all_select").focus();
// 			});
// 			$(document).unbind("keyup").keyup(function (e) {
// 				var code = e.which;
// 				if (code == 13) {
// 					$(".enter").click();
// 				}
// 			});
// 		} else {
// 			var check = true;
// 			$("#friendtalk_table tr").each(function () {
// 				var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
// 				if (btn_type != undefined) {
// 					var focus;
// 					if (btn_type == "N" && $(this).find("#no").is(":hidden") == false) {
// 						check = false;
// 						$(".content").html("버튼타입을 선택해주세요.");
// 						$("#myModal").modal('show');
// 					} else if (btn_type == "DS" && $(this).find(document.getElementsByName("btn_name1")).val().trim() == "") {
// 						check = false;
// 						focus = $(this).find(document.getElementsByName("btn_name1"));
// 						$(".content").html("배송조회 버튼의 버튼명을 입력해주세요.");
// 						$("#myModal").modal('show');
// 						$('#myModal').on('hidden.bs.modal', function () {
// 							focus.focus();
// 						});
// 					} else if (btn_type == "WL") {
// 						if($(this).find(document.getElementsByName("btn_name2")).val().trim() == "") {
// 							check = false;
// 							focus = $(this).find(document.getElementsByName("btn_name2"));
// 							$(".content").html("웹링크 버튼의 버튼명을 입력해주세요.");
// 							$("#myModal").modal('show');
// 							$('#myModal').on('hidden.bs.modal', function () {
// 								focus.focus();
// 							});
// 						} else if ($(this).find(document.getElementsByName("btn_url21")).val().trim() == "" || $(this).find(document.getElementsByName("btn_url21")).val().trim() == "http://") {
// 							check = false;
// 							focus = $(this).find(document.getElementsByName("btn_url21"));
// 							$(".content").html("웹링크 버튼의 Mobile 버튼링크를 입력해주세요.");
// 							$("#myModal").modal('show');
// 							$('#myModal').on('hidden.bs.modal', function () {
// 								focus.focus();
// 							});
// 						}
// 					} else if (btn_type == "AL") {
// 						if($(this).find(document.getElementsByName("btn_name3")).val().replace(/ /gi, "") == "") {
// 							check = false;
// 							focus = $(this).find(document.getElementsByName("btn_name3"));
// 							$(".content").html("앱링크 버튼의 버튼명을 입력해주세요.");
// 							$("#myModal").modal('show');
// 							$('#myModal').on('hidden.bs.modal', function () {
// 								focus.focus();
// 							});
// 						} else if ($(this).find(document.getElementsByName("btn_url31")).val().replace(/ /gi, "") == "") {
// 							check = false;
// 							focus = $(this).find(document.getElementsByName("btn_url31"));
// 							$(".content").html("앱링크 버튼의 Android 버튼링크를 입력해주세요.");
// 							$("#myModal").modal('show');
// 							$('#myModal').on('hidden.bs.modal', function () {
// 								focus.focus();
// 							});
// 						} else if ($(this).next('tr').find(document.getElementsByName("btn_url32")).val().replace(/ /gi, "") == "") {
// 							check = false;
// 							focus = $(this).parent().find(document.getElementsByName("btn_url32"));
// 							$(".content").html("앱링크 버튼의 iOS 버튼링크를 입력해주세요.");
// 							$("#myModal").modal('show');
// 							$('#myModal').on('hidden.bs.modal', function () {
// 								focus.focus();
// 							});
// 						}
// 					} else if (btn_type == "BK" && $(this).find(document.getElementsByName("btn_name4")).val().replace(/ /gi, "") == "") {
// 						check = false;
// 						focus = $(this).find(document.getElementsByName("btn_name4"));
// 						$(".content").html("봇키워드 링크 버튼의 버튼명을 입력해주세요.");
// 						$("#myModal").modal('show');
// 						$('#myModal').on('hidden.bs.modal', function () {
// 							focus.focus();
// 						});
// 					} else if (btn_type == "MD" && $(this).find(document.getElementsByName("btn_name5")).val().replace(/ /gi, "") == "") {
// 						check = false;
// 						focus = $(this).find(document.getElementsByName("btn_name5"));
// 						$(".content").html("메시지전달 링크 버튼의 버튼명을 입력해주세요.");
// 						$("#myModal").modal('show');
// 						$('#myModal').on('hidden.bs.modal', function () {
// 							focus.focus();
// 						});
// 					}
// 				}
// 			});
// 			$("input:checkbox[name=sel_del]:checked").each(function () {
// 				var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
// 				if (array == "") {
// 					check = false;
// 					$(".content").html("수신 번호를 입력해주세요.");
// 					$('#myModal').modal({backdrop: 'static'});
// 					$('#myModal').on('hidden.bs.modal', function () {
// 						$("input:checkbox[name=sel_del]:checked").each(function () {
// 							array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
// 							if (array == "") {
// 								$(this).parent().parent().parent().parent().find("#tel_number").focus();
// 							}
// 						});
// 					});
// 					$('#myModal').unbind("keyup").keyup(function (e) {
// 						var code = e.which;
// 						if (code == 13) {
// 							$(".enter").click();
// 						}
// 					});
// 					return false;
// 				}
// 			});
// 			if($("#reserve_check").is(":checked") == true) {
// 				if($("#reserve").val().trim() == ""){
// 					check = false;
// 					$(".content").html("예약 발송 일시를 선택해주세요.");
// 					$('#myModal').modal({backdrop: 'static'});
// 				} else {
// 					var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
// 					var reserveDt = reserve+"00";
// 					var now = moment().add(10,'minutes');
// 					var min = now.format("YYYYMMDDHHmm");
// 					var minDt = min+"00";
// 					if(reserveDt < minDt){
// 						check = false;
// 						$(".content").html("예약발송은 최소 10분 이후로 입력해주세요.");
// 						$('#myModal').modal({backdrop: 'static'});
// 					} else {
// 						check = true;
// 					}
// 				}
// 			}

// 			set_price();
// 			if(send_price <= 0) 
// 			{
// 				alert('적용 단가 오류 - 확인이 필요 합니다.');
// 				return;
// 			}			
// 			if(check == false) return false;

// 			$.ajax({
// 				url: "/biz/sender/coin",
// 				type: "POST",
//				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"},
// 				success: function (json) {
// 					coin = json['coin'];
// 					var limit = Number(json['limit']);
// 					var sent = Number(json['sent']);
// 					var limit_msg = "";
// 					if(limit > 0 && sent >= limit) { showLimitOver(); return; }
// 					if(limit > 0) { limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>"; }

// 					var kakao = Math.floor(Number(coin) / Number(send_price));
// 					var k = kakao.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
// 					var kakao_img = Math.floor(Number(coin) / Number(ft_img_price));
// 					var k_i = kakao_img.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/, '0');
// 					var cnt = $("input[name=sel_del]:checked").length;

// 					//check_resend_method();
//					<?/* 재발신 가능 건수를 산정합니다. */?>
// 					var resends = (send_price > 0) ? Math.floor(Number(coin) / Number(send_price)) : 0;
// 					var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');

// 					if (Number(coin) - (Number(send_price) * Number(cnt)) < 0) {
// 						$(".content").html("잔액이 부족합니다.");
// 						$('#myModal').modal({backdrop: 'static'});
// 						$(document).unbind("keyup").keyup(function (e) {
// 							var code = e.which;
// 							if (code == 13) {
// 								$(".enter").click();
// 							}
// 						});
// 					} else {
// 						var content_msg = " 발송 가능 건수 : " + resend_cnt + "건";
						
// 						$(".content").html(limit_msg + content_msg + "<br/><br/>선택 발송하시겠습니까?<br/>발송할 건수 : " + cnt + "건<br/>발송금액 : " + (cnt * send_price) + " 원");
// 						$("#myModalSelect").modal({backdrop: 'static'});
// 						$("#myModalSelect").unbind("keyup").keyup(function (e) {
// 							var code = e.which;
// 							if (code == 13) {
// 								$(".select-send").click();
// 							}
// 						});
// 						$(".select-send").click(function () {
// 							$("#myModalSelect").modal('hide');
// 						});
// 					}
// 				}
// 			});
// 		}
// 	}

	<?/*----------------------------------------------------------------------------*
		| 수신번호 목록을 체크한 후 선택발송을 클릭했을때 실제 발송하는 부분 입니다. |
		*----------------------------------------------------------------------------*/?>
// 	function select_move() {
//		var senderBox = $("#sms_sender").val();						<?/* callback번호 */?>
// 		var smsOnly='';
//		var templi_cont = $("#templi_cont").val();					<?/* 친구톡 내용 */?>
//		var profile = document.getElementById("pf_key").value;	<?/* 발신프로필 key */?>
// 		var msg=msg = $('#lms').val();
// 		var smslms_kind = $("#smslms_kind").val();
// 		var mem_2nd_send = $("#mem_2nd_send").val();
//		var kind = (smslms_kind=="SMS") ? "S":"L";												<?/* 2차발신 방법 */?>
// 		var tit = $("#tit").val();
// 		var img_url = '';
// 		var img_link = '';
// 		if ($("#img_url").val() != undefined && $("#img_url") != "" && $("#img_link") != "") {
// 			img_url = $("#img_url").val();
// 			img_link = $("#img_link").val();
// 		}
// 		if ('0' != 0 && $("#tel_temp").val() == undefined){
// 			smsOnly = 'Y';
// 		}

// 		var btn = [];
// 		for (var b = 1; b <= 5; b++) {
// 			btn[b] = new Array();
// 		}
// 		var obj = [];
// 		var btn_num = 1;
//		<?/* 버튼 */?>
// 		$("#friendtalk_table tr").each(function () {
// 			var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
// 			if (btn_type != undefined && btn_type != "N") {
// 				obj[btn_num] = new Object();
// 				obj[btn_num].type = btn_type;
// 				if (btn_type == "WL") {
// 					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name2")).val().trim();
// 					obj[btn_num].url_mobile = $(this).find(document.getElementsByName("btn_url21")).val().trim();
// 					obj[btn_num].url_pc = $(this).next('tr').find(document.getElementsByName("btn_url22")).val().trim();
// 				} else if (btn_type == "AL") {
// 					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name3")).val().trim();
// 					obj[btn_num].scheme_android = $(this).find(document.getElementsByName("btn_url31")).val().trim();
// 					obj[btn_num].scheme_ios = $(this).next('tr').find(document.getElementsByName("btn_url32")).val().trim();
// 				} else if (btn_type == "BK") {
// 					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name4")).val().trim();
// 				} else if (btn_type == "MD") {
// 					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name5")).val().trim();
// 				}
// 				btn[btn_num].push(obj[btn_num]);
// 				btn_num++;
// 			}
// 		});
// 		var btn1 = JSON.stringify(btn[1]);
// 		var btn2 = JSON.stringify(btn[2]);
// 		var btn3 = JSON.stringify(btn[3]);
// 		var btn4 = JSON.stringify(btn[4]);
// 		var btn5 = JSON.stringify(btn[5]);

// 		var tel_number = new Array();
// 		$("input:checkbox[name=sel_del]:checked").each(function () {
// 			var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
// 			tel_number.push(array);

// 		});
//		<?/* 예약발송 설정 */?>
// 		var reserveDt = "00000000000000";
// 		if($("#reserve_check").is(":checked") == true) {
// 			if($("#reserve").val().trim() != ""){
// 				var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
// 				reserveDt = reserve+"00";
// 			}
// 		}

// 		$.ajaxSettings.traditional = true;
// 		$.ajax({
// 			url: "/biz/sender/lms/all",
// 			type: "POST",
// 			data: {
//				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
// 				"templi_cont": templi_cont,
// 				"msg": msg,
// 				"kind": kind,
// 				"senderBox": senderBox,
// 				"tit": tit,
// 				"pf_key": profile,
// 				"img_url": img_url, "img_link": img_link,
// 				"btn1": btn1, "btn2": btn2, "btn3": btn3, "btn4": btn4, "btn5": btn5,
// 				"smsOnly": smsOnly,
// 				"tel_number[]": tel_number,
// 				"reserveDt": reserveDt,
// 				"smslms_kind":smslms_kind,
// 				"mem_2nd_send":mem_2nd_send,
// 				"mms_id":mms_id,
//				"fail_mst_id":"<? if($fail_mst_id) { echo $fail_mst_id; } else { echo ""; } ?>"
// 			},
// 			beforeSend: function () {
// 				$('#overlay').fadeIn();
// 			},
// 			complete: function () {
// 				$('#overlay').fadeOut();
// 			},
// 			success: function (json) {
// 				$('#navigateURL').val("");
// 				var code = json['code'];
// 				var message = json['message'];
// 				if (code == "success") {
// 					//$('#navigateURL').val(document.location.href);
// 					$('#navigateURL').val("/biz/sender/history_dt");
// 					$(".content").html("발송 요청되었습니다.");
// 					$('#myModal').modal({backdrop: 'static'});
// 					$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
// 					$(document).unbind("keyup").keyup(function (e) {
// 						var code = e.which;
// 						if (code == 13) {
// 							$(".enter").click();
// 						}
// 					});
// 				} else {
// 					$(".content").html("발송 실패되었습니다.<br/>" + message);
// 					$('#myModal').modal({backdrop: 'static'});
// 					$(document).unbind("keyup").keyup(function (e) {
// 						var code = e.which;
// 						if (code == 13) {
// 							$(".enter").click();
// 						}
// 					});
// 				}
// 			},
			
// 			error: function () {
// 				$(".content").html("처리되지 않았습니다.");
// 				$('#myModal').modal({backdrop: 'static'});
// 				$(document).unbind("keyup").keyup(function (e) {
// 					var code = e.which;
// 					if (code == 13) {
// 						$(".enter").click();
// 					}
// 				});
// 			}
// 		});
// 	}

	<?/*----------------------------------------------------------------------------------------------------------------*
	   | 전체고객정보 불러오기 하여 전체 발송버튼 클릭 [업로드 발송 포함] 또는 수신목록이 표시된 상태에서 전체발송 클릭 |
	   *----------------------------------------------------------------------------------------------------------------*/?>
	function all_send() {
		if ($('#lms').val().replace(/ /gi,"")==""){
			$(".content").html("문자 내용을 입력해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#lms").focus();
			});
		} else if (getByteLength($("#lms")) > 2000) {
			$(".content").html("문자 내용은 최대 2,000bytes (한글 1,000자)'?> 이내로<br/>입력하여야 합니다.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#lms").focus();
			});
		} <? if(!$fail_mst_id) { ?> else if ($("#switch_upload").is(":checked") && parseInt($("#upload_tel_count").text()) == 0) {		// upload 발송
			//if (parseInt($("#upload_tel_count").text()) < 0) {
			$(".content").html("수신 전화번호 엑셀파일 업로드하세요.");
			$('#myModal').modal({backdrop: 'static'});
		//} 
    	} else if ($("#switch_select").is(":checked") && $("input[name=checkOne]:checked").length == 0) {	// 고객 DB에서 그룹 선택
    		if ($("input[name=checkOne]:checked").length == 0) {
    			$(".content").html("고객 그룹을 선택하세요.");
    			$('#myModal').modal({backdrop: 'static'});
    		} 
    	} else if ($("#switch_customer").is(":checked") && $("span[name=input_phone_no]").length == 0) {	// 연락처 직접 입력
    		if ($("span[name=input_phone_no]").length == 0) {
    			$(".content").html("연락처를 입력하세요.");
    			$('#myModal').modal({backdrop: 'static'});
    		}
    	} <? } else {?> else if (parseInt($("#not_friend_list").text()) == 0) {	// 연락처 직접 입력
    			$(".content").html("발송 실패 고객이 없습니다.");
    			$('#myModal').modal({backdrop: 'static'});
    	} <? } ?> else {
			var check = true;
			if($("#reserve_check").is(":checked") == true) {
				if($("#reserve").val().trim() == ""){
					$(".content").html("예약 발송 일자를 선택해주세요.");
					$('#myModal').modal({backdrop: 'static'});
					check = false;
				} else if ($("#reserve_hours").val().trim() == "") {
					$(".content").html("예약 발송 시간를 선택해주세요.");
					$('#myModal').modal({backdrop: 'static'});
					check = false;
				} else if ($("#reserve_minutes").val().trim() == "") {
					$(".content").html("예약 발송 분를 선택해주세요.");
					$('#myModal').modal({backdrop: 'static'});
					check = false;
				} else {
					//var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
					var reserve_hours = ($("#reserve_hours").val() < 10) ? "0" + $("#reserve_hours").val() : "" + $("#reserve_hours").val();
					var reserve_minutes = ($("#reserve_minutes").val() < 10) ? "0" + $("#reserve_minutes").val() : "" + $("#reserve_minutes").val();
					var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "") + reserve_hours + reserve_minutes;
					var reserveDt = reserve+"00";
					var now = moment().add(10,'minutes');
					var min = now.format("YYYYMMDDHHmm");
					var minDt = min+"00";
					if(reserveDt < minDt){
						$(".content").html("예약발송은 최소 10분 이후로 입력해주세요.");
						$('#myModal').modal({backdrop: 'static'});
						check = false;
					}
				}
			}
			if (check == true) {
				set_price();
				if(send_price <= 0) 
				{
					$(".content").html("적용 단가 오류 - 확인이 필요 합니다.");
					$('#myModal').modal({backdrop: 'static'});
					check = false;
				}			
			}

			if (check == true) {
				$.ajax({
					url: "/biz/sender/coin",
					type: "POST",
					data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"},
					success: function (json) {
						coin = json['coin'];
						var limit = Number(json['limit']);
						var sent = Number(json['sent']);
						var limit_msg = "";
						if(limit > 0 && sent >= limit) { showLimitOver(); return; }
						if(limit > 0) { limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>"; }

						//alert("coin : "  + coin);
						//alert("send_price : " + send_price);
//						check_resend_method();
						// 재발신 가능 건수를 산정합니다.
						var resends = (send_price > 0) ? Math.floor(Number(coin) / Number(send_price)) : 0;
						var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');

						if (!$("#switch_upload").is(":checked")) {
							var row_index = 0;
							var content_msg = " 발송 가능 건수 : " + resend_cnt + "건<br><br>";	

							<? if(!$fail_mst_id) { ?>
							if ($("#switch_select").is(":checked")) {	// 고객 DB에서 그룹 선택
								row_index = parseInt($("#groupSendTotal").text());
							} else if ($("#switch_customer").is(":checked")) {	// 연락처 직접 입력
								row_index = parseInt($("#input_phone_count").text());
							}
							<? } else { ?>
							var row_index = <?=$fail_qty?>;
							<? } ?>					
							if ($("#switch_select").is(":checked")) {	// 고객 DB에서 그룹 선택
								row_index = parseInt($("#groupSendTotal").text());
							} else if ($("#switch_customer").is(":checked")) {	// 연락처 직접 입력
								row_index = parseInt($("#input_phone_count").text());
							}

							if (Number(coin) - (Number(send_price) * Number(row_index)) < 0) {
								$(".content").html("잔액이 부족합니다.");
								$('#myModal').modal({backdrop: 'static'});
								$(document).unbind("keyup").keyup(function (e) {
									var code = e.which;
									if (code == 13) {
										$(".enter").click();
									}
								});
							} else {
								if ($("#smslms_kind").val() == "SMS") {
									content_msg += "단문(SMS) 문자 발송 예상 금액 : "  + (Number(send_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + send_price + ")";
								} else if ($("#smslms_kind").val() == "LMS") {
									content_msg += "장문(LMS) 문자 발송 예상 금액 : "  + (Number(send_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + send_price + ")";
								} else if ($("#smslms_kind").val() == "MMS") {
									content_msg += "MMS 문자 발송 예상 금액 : "  + (Number(send_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + send_price + ")";
								} else {
									content_msg += "문자 발송 예상 금액 : "  + (Number(charge_type) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + charge_type + ")";
								}
								$(".content").html(limit_msg + content_msg + "<br/><br/>전체 발송하시겠습니까?<br/> (발송할 건수(" + $("#smslms_kind_r").val() +") : " + row_index + "건)");
								$("#myModalAll").modal({backdrop: 'static'});
								$("#myModalAll").unbind("keyup").keyup(function (e) {
									var code = e.which;
									if (code == 13) {
										$(".all").click();
									}
								});
								$(".all").click(function () {
									$("#myModalAll").modal('hide');
								});
							}							
						} else {
							// 재발신 가능 건수를 산정합니다.
							//var resends = (send_price > 0) ? Math.floor(Number(coin) / Number(send_price)) : 0;
							//var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
							<?/* 업로드 된 건수 */?>
							var upload_send = $("#upload_tel_count").text();
							<?/* 발송가능 건수 표시 : 업로드 된 자료의 경우 업로드 내용으로만 판단할 수 있으므로 잔액이 부족하다는 메시지를 보여줄 수 없습니다. */?>
							var content_msg = " 발송 가능 건수 : " + resend_cnt + "건<br><br>";
							if ($("#smslms_kind").val() == "SMS") {
								content_msg += "단문(SMS) 문자 발송 예상 금액 : "  + (Number(send_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + send_price + ")";
							} else if ($("#smslms_kind").val() == "LMS") {
								content_msg += "장문(LMS) 문자 발송 예상 금액 : "  + (Number(send_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + send_price + ")";
							} else if ($("#smslms_kind").val() == "MMS") {
								content_msg += "MMS 문자 발송 예상 금액 : "  + (Number(send_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + send_price + ")";
							} else {
								content_msg += "문자 발송 예상 금액 : "  + (Number(charge_type) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + charge_type + ")";
							}
							
							$(".content").html(limit_msg + content_msg + "<br/><br/>업로드 파일을 전체 발송하시겠습니까?<br/> 발송할 건수 : " + upload_send + " 건" );

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
    	}
	}
	function all_send2() {
// 		window.onscroll = function(){
// 			var arr = document.getElementsByTagName('textarea');
// 			for( var i = 0 ; i < arr.length ; i ++  ){
// 				try { arr[i].off('blur'); }catch(e){}
// 			}
// 		};
		
		if($("#upload_send").val() == undefined) {
			 
			if ($("input:checkbox[id='lms_select']").is(":checked") == true && $('#lms').val().replace(/ /gi,"")==""){
				$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용을 입력해주세요.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#lms").focus();
				});
			} else if ($("input:checkbox[id='lms_select']").is(":checked") && getByteLength($("#lms")) > 1980) {
				$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?>내용은 <?=($mem->mem_2nd_send=='sms') ? '90자(한글 45자)' : '2,000자(한글 1,000자)'?> 이내로<br/>입력하여야 합니다.<br/>(실제내용 1,980자 이내)");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#lms").focus();
				});
<?if($mem->mem_2nd_send=='phn' || $mem->mem_2nd_send=='015') { /* 2차발신 체크시 폰문자인 경우에만 수신거부 문구를 삽입합니다 */ ?>
			} else if ($('#lms_select').prop('checked') && $("#lms").val().indexOf("(광고)") < 0) {
				$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용 첫부분에 (광고) 문구를 삽입하여야 합니다.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#lms").focus();
				});
			} else if ($('#lms_select').prop('checked') && $("#lms").val().indexOf("무료거부") < 0) {
				$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용에 무료거부 문구와 연락처를 넣어야 합니다.");
				$('#myModal').modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#lms").focus();
				});
<?}?>
			} else {
			<?/*----------------*
			   | 전체 발송 처리 |
			   *----------------*/?>
				var check = true;
				$("#friendtalk_table tr").each(function () {
					var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
					if (btn_type != undefined) {
						var focus;
						if (btn_type == "N" && $(this).find("#no").is(":hidden") == false) {
							check = false;
							$(".content").html("버튼타입을 선택해주세요.");
							$("#myModal").modal('show');
						} else if (btn_type == "DS" && $(this).find(document.getElementsByName("btn_name1")).val().trim() == "") {
							check = false;
							focus = $(this).find(document.getElementsByName("btn_name1"));
							$(".content").html("배송조회 버튼의 버튼명을 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						} else if (btn_type == "WL") {
							if($(this).find(document.getElementsByName("btn_name2")).val().trim() == "") {
								check = false;
								focus = $(this).find(document.getElementsByName("btn_name2"));
								$(".content").html("웹링크 버튼의 버튼명을 입력해주세요.");
								$("#myModalf").modal('show');
								$('#myModal').on('hidden.bs.modal', function () {
									focus.focus();
								});
							} else if ($(this).find(document.getElementsByName("btn_url21")).val().trim() == "" || $(this).find(document.getElementsByName("btn_url21")).val().trim() == "http://") {
								check = false;
								focus = $(this).find(document.getElementsByName("btn_url21"));
								$(".content").html("웹링크 버튼의 Mobile 버튼링크를 입력해주세요.");
								$("#myModal").modal('show');
								$('#myModal').on('hidden.bs.modal', function () {
									focus.focus();
								});
							}
						} else if (btn_type == "AL") {
							if($(this).find(document.getElementsByName("btn_name3")).val().replace(/ /gi, "") == "") {
								check = false;
								focus = $(this).find(document.getElementsByName("btn_name3"));
								$(".content").html("앱링크 버튼의 버튼명을 입력해주세요.");
								$("#myModal").modal('show');
								$('#myModal').on('hidden.bs.modal', function () {
									focus.focus();
								});
							} else if ($(this).find(document.getElementsByName("btn_url31")).val().replace(/ /gi, "") == "") {
								check = false;
								focus = $(this).find(document.getElementsByName("btn_url31"));
								$(".content").html("앱링크 버튼의 Android 버튼링크를 입력해주세요.");
								$("#myModal").modal('show');
								$('#myModal').on('hidden.bs.modal', function () {
									focus.focus();
								});
							} else if ($(this).next('tr').find(document.getElementsByName("btn_url32")).val().replace(/ /gi, "") == "") {
								check = false;
								focus = $(this).parent().find(document.getElementsByName("btn_url32"));
								$(".content").html("앱링크 버튼의 iOS 버튼링크를 입력해주세요.");
								$("#myModal").modal('show');
								$('#myModal').on('hidden.bs.modal', function () {
									focus.focus();
								});
							}
						} else if (btn_type == "BK" && $(this).find(document.getElementsByName("btn_name4")).val().replace(/ /gi, "") == "") {
							check = false;
							focus = $(this).find(document.getElementsByName("btn_name4"));
							$(".content").html("봇키워드 링크 버튼의 버튼명을 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						} else if (btn_type == "MD" && $(this).find(document.getElementsByName("btn_name5")).val().replace(/ /gi, "") == "") {
							check = false;
							focus = $(this).find(document.getElementsByName("btn_name5"));
							$(".content").html("메시지전달 링크 버튼의 버튼명을 입력해주세요.");
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								focus.focus();
							});
						}
					}
				});
				if ($("#customer_all_send").val() == undefined) {
					var table = document.getElementById("tel");
					var row_index = table.rows.length - 1;
					for (var i = 0; i < row_index; i++) {
						if (document.getElementsByClassName('tel_number')[i].value == "") {
							check = false;
							$(".content").html("수신 번호를 입력해주세요.");
							$('#myModal').modal({backdrop: 'static'});
							$('#myModal').on('hidden.bs.modal', function () {
								document.getElementsByClassName('tel_number')[i].focus();
							});
							$('#myModal').unbind("keyup").keyup(function (e) {
								var code = e.which;
								if (code == 13) {
									$(".enter").click();
								}
							});
							return false;
						}
					}
				}
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

				set_price();
				if(send_price <= 0) 
				{
					alert('적용 단가 오류 - 확인이 필요 합니다.');
					return;
				}
				if (check == false) return false;
				$.ajax({
					url: "/biz/sender/coin",
					type: "POST",
					data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"},
					success: function (json) {
						coin = json['coin'];
						var limit = Number(json['limit']);
						var sent = Number(json['sent']);
						var limit_msg = "";
						if(limit > 0 && sent >= limit) { showLimitOver(); return; }
						if(limit > 0) { limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>"; }

//						check_resend_method();
						// 재발신 가능 건수를 산정합니다.
//						var resends = (charge_type > 0) ? Math.floor(Number(coin) / Number(charge_type)) : 0;
//						var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');

						var table = document.getElementById("tel");
						var row_index = 0;
						if($("#customer_all_send").val() == undefined) {
							row_index = table.rows.length - 1;
						} else {
							row_index = $("#customer_all_send").val();
						}

						if (Number(coin) - (Number(send_price) * Number(row_index)) < 0) {
							$(".content").html("잔액이 부족합니다.");
							$('#myModal').modal({backdrop: 'static'});
							$(document).unbind("keyup").keyup(function (e) {
								var code = e.which;
								if (code == 13) {
									$(".enter").click();
								}
							});
						} else {
							$(".content").html("<br/><br/>전체 발송하시겠습니까?<br/> (발송할 건수(" + $("#smslms_kind_r").val() +") : " + row_index + "건)<br/>발송금액 : " + (row_index * send_price) + " 원");
							$("#myModalAll").modal({backdrop: 'static'});
							$("#myModalAll").unbind("keyup").keyup(function (e) {
								var code = e.which;
								if (code == 13) {
									$(".all").click();
								}
							});
							$(".all").click(function () {
								$("#myModalAll").modal('hide');
							});
						}
					}
				});
			}
		} else if($("#upload_send").val()!=undefined){
			<?/*-----------------------*
			   | 업로드 전체 발송 처리 |
			   *-----------------------*/?>
			var check = true;
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

			set_price();
			if(send_price <= 0) 
			{
				alert('적용 단가 오류 - 확인이 필요 합니다.');
				return;
			}
			
			if (check == false) return false;
			$.ajax({
				url: "/biz/sender/coin",
				type: "POST",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					coin = json['coin'];
					var limit = Number(json['limit']);
					var sent = Number(json['sent']);
					var limit_msg = "";
					if(limit > 0 && sent >= limit) { showLimitOver(); return; }
					if(limit > 0) { limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>"; }

					//check_resend_method();
					// 재발신 가능 건수를 산정합니다.
					var resends = (send_price > 0) ? Math.floor(Number(coin) / Number(send_price)) : 0;
					var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
					<?/* 업로드 된 건수 */?>
					var upload_send = $("#upload_send").val();
					<?/* 발송가능 건수 표시 : 업로드 된 자료의 경우 업로드 내용으로만 판단할 수 있으므로 잔액이 부족하다는 메시지를 보여줄 수 없습니다. */?>
					var content_msg = " 발송 가능 건수 : " + resend_cnt + "건";
					
					$(".content").html(limit_msg + content_msg + "<br/><br/>업로드 파일을 전체 발송하시겠습니까?<br/> 발송할 건수 : " + upload_send + " 건 <br/>발송금액 : " + (upload_send*send_price) + " 원" );

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
			});
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
	/* 문자열을 지정 길이만큼 자른다 */
	function cutTextLength(msg, len) {
		var text = msg;
		var leng = text.length;
		while(getTextLength(text) > len){
			leng--;
			text = text.substring(0, leng);
		}
		return text;
	}

/*-------------------------------------------------------------------------------*/
	<?/*--------------------------------------------------------------------------------*
		| 전체발송(전체고객정보불러오기 포함) 버튼 클릭시 실제 발송 처리하는 부분입니다. |
		*--------------------------------------------------------------------------------*/?>
	function all_move() {
		var nextNavigateURL = "/biz/sender/history_dt";
		var senderBox = $("#sms_sender").val();
		var smsOnly='';
		var templi_cont = $("#templi_cont").val();
		var profile = document.getElementById("pf_key").value;
		var kind = ms;
		//var msg = $('#lms').val();
		var lms_header_temp = "";
		var lms_footer_temp = "";
		var msg = "";
		lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		msg = lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
        // 2019.01.21 이수환 특수문자 제거 테스트
        msg = msg.replace(/[\u2100-\u2120\u2123-\u214F\u2190-\u21FF\u0080-\u008F\uFE50-\uFE6F\u2460-\u24FF\u2300-\u259F\u25A2\u25A4-\u25A5\u25A7-\u25B1\u25B4-\u25B5\u25B8-\u25BB\u25BE-\u25BF\u25C2-\u25C5\u25C8-\u25CA\u25CC-\u25CD\u25D2-\u2604\u2607-\u260D\u260F-\u261D\u261F-\u263F\u2641\u2643-\u2660\u2662\u2664\u2666\u2668-\u2669\u266B\u266D-\u303F\u3100-\u32FF\u2150-\u2189\u2200-\u2298\u229A-\u22FF\u0080-\u00FF\u2000-\u203A\u203C-\u206F\uFF00-\uFF02\uFF04-\uFF05\uFF07-\uFF1F\uFF21-\uFFEF\u3130-\u318F\u0000-\u0009\u3300-\u33FF\u0400-\u04FF\u2070-\u209C\u2714]/gi, ""); 
        msg = msg.replace(/[\u200B\u3000\u3164\uDB40\u272A]/gi, "");
        msg = msg.replace(/[\u2027]/gi, " ");
        
		var tit = $("#tit").val();

		// 2019.01.31. 이수환 수정 추가  <?/* 2차발신 방법 */?>
		var temp_kind = "";	
		if ($("#smslms_kind").val()=="SMS") { temp_kind = "S"; }
		else if ($("#smslms_kind").val()=="LMS") { temp_kind = "L"; }
		else {temp_kind = "M";}
		
		// 2019.01.31. 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
		var mst_type1 = '';		// 1차 문자 type 공백
		var mst_type2 = '';
		// 2차 문자가 있으면(웹(A)LMS : wa, 웹(A)SMS : was, 웹(A) MMS : wam, 웹(B)LMS : wb, 웹(B)SMS : wbs, 웹(B) : wbm, 폰문자: phn
		var temp_mst_kind = $("#mem_2nd_send").val();
		if(temp_mst_kind == "GREEN_SHOT") {
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			} 
			mst_type2 = "wa" + temp_resend_type;
		} else if (temp_mst_kind == "NASELF"){
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			} 
			mst_type2 = "wb" + temp_resend_type;
		} else if (temp_mst_kind == "015") {
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			} 
			mst_type2 = "015" + temp_resend_type;
		} else if (temp_mst_kind == "PHONE") {
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			} 
			mst_type2 = "phn" + temp_resend_type;
		}
		// 2019.01.31. 이수환 추가 끝 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)

		var reserveDt = "00000000000000";
		if($("#reserve_check").is(":checked") == true) {
			if($("#reserve").val().trim() != "" && $("#reserve_hours").val() != "" && $("#reserve_minutes").val() != ""){
				var reserve_hours = ($("#reserve_hours").val() < 10) ? "0" + $("#reserve_hours").val() : "" + $("#reserve_hours").val();
				var reserve_minutes = ($("#reserve_minutes").val() < 10) ? "0" + $("#reserve_minutes").val() : "" + $("#reserve_minutes").val();
				var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "") + reserve_hours + reserve_minutes;
				reserveDt = reserve+"00";
			}
		}

		if ($("#switch_upload").is(":checked") && parseInt($("#upload_tel_count").text()) > 0) {		// upload 발송
			var upload_count = parseInt($("#upload_tel_count").text());
			var file_data = new FormData();
			file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			file_data.append("pf_key", profile);
			file_data.append("senderBox", senderBox);
			file_data.append("upload_count", upload_count);
			file_data.append("reserveDt", reserveDt);
			file_data.append("file", $("input[id=filecount]")[0].files[0]);
			file_data.append("smslms_kind", $("#smslms_kind").val());
			file_data.append("mem_2nd_send",$("#mem_2nd_send").val());
			file_data.append("mms_id",mms_id);
			file_data.append("templi_cont", msg);
			file_data.append("msg", msg);
			file_data.append("kind", kind);
			file_data.append("tit", tit);
			file_data.append("smsOnly", smsOnly);
			
			file_data.append("mst_type1", mst_type1);		// 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
			file_data.append("mst_type2", mst_type2);		// 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문자

			$.ajaxSettings.traditional = true;
			$.ajax({
				url: "/biz/sender/lms/all",
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
					if (code == "success") {
						//$('#navigateURL').val(document.location.href);
						$('#navigateURL').val(nextNavigateURL);
						$(".content").html("발송 요청되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("발송 실패되었습니다.<br/>" + message);
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
		} else if ($("#switch_select").is(":checked") && $("input[name=checkOne]:checked").length > 0) {	// 고객 DB에서 그룹 선택
			var customer_all_count = parseInt($("#groupSendTotal").text());
			var customer_filter = "";
			var customer_filter_count = 0;
			$("input[name=checkOne]:checked").each(function() {
				if (customer_filter_count == 0) {
					customer_filter += $(this).val();
				} else {
					customer_filter += ("," + $(this).val());
				}
				customer_filter_count++;
			});	
			var smslms_kind = $("#smslms_kind").val();
			var mem_2nd_send = $("#mem_2nd_send").val();

			//alert(customer_filter);
			
			$.ajax({
				url: "/biz/sender/lms/all",
				type: "POST",
				data: {
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"templi_cont": templi_cont,
					"msg": msg, 
					"kind": kind, 
					"tit": tit,
					"tit": tit, 
					"pf_key": profile, 
					"img_url": "", 
					"img_link": "",
					"btn1": "", 
					"btn2": "", 
					"btn3": "", 
					"btn4": "", 
					"btn5": "",
					"senderBox": senderBox, 
					"smsOnly": smsOnly,
					"customer_all_count": customer_all_count,
					"customer_filter": customer_filter,
					"reserveDt": reserveDt, 
					"smslms_kind":smslms_kind, 
					"mem_2nd_send":mem_2nd_send,
					"mms_id":mms_id,
					"fail_mst_id":"<? if($fail_mst_id) { echo $fail_mst_id; } else { echo ""; } ?>",
					"mst_type1" : mst_type1, // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
					"mst_type2" : mst_type2	// 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문자
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
						$(".content").html("발송 요청되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("발송 실패되었습니다.<br/>" + message);
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
		} else if ($("#switch_customer").is(":checked") && $("span[name=input_phone_no]").length > 0) {	// 연락처 직접 입력
			var tel_number = new Array();
			$("span[name=input_phone_no]").each(function () {
				//var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
				var array = $(this).text().replace(/-/gi, "");
				tel_number.push(array);
			});
			var smslms_kind = $("#smslms_kind").val();
			var mem_2nd_send = $("#mem_2nd_send").val();
			//alert(smslms_kind + ", " + mem_2nd_send);
			$.ajaxSettings.traditional = true;
			$.ajax({
				url: "/biz/sender/lms/all",
				type: "POST",
				data: {
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"templi_cont": templi_cont, 
					"msg": msg, 
					"kind": kind, 
					"senderBox": senderBox, 
					"tit": tit,
					"pf_key": profile, 
					"img_url": "", 
					"img_link": "",
					"btn1": "", 
					"btn2": "", 
					"btn3": "", 
					"btn4": "", 
					"btn5": "",
					"smsOnly": smsOnly, 
					"tel_number[]": tel_number, 
					"reserveDt": reserveDt, 
					"smslms_kind":smslms_kind, 
					"mem_2nd_send":mem_2nd_send,
					"mms_id":mms_id,
					"fail_mst_id":"<? if($fail_mst_id) { echo $fail_mst_id; } else { echo ""; } ?>",
					"mst_type1" : mst_type1, // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
					"mst_type2" : mst_type2	// 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문자
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
						$(".content").html("발송 요청되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("발송 실패되었습니다.<br/>" + message);
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
		} <? if($fail_mst_id) { ?> else {
			var customer_all_count = parseInt($("#groupSendTotal").text());
			var customer_filter = "";
			var smslms_kind = $("#smslms_kind").val();
			var mem_2nd_send = $("#mem_2nd_send").val();

			$.ajax({
				url: "/biz/sender/lms/all",
				type: "POST",
				data: {
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"templi_cont": templi_cont,
					"msg": msg, 
					"kind": kind, 
					"tit": tit,
					"tit": tit, 
					"pf_key": profile, 
					"img_url": "", 
					"img_link": "",
					"btn1": "", 
					"btn2": "", 
					"btn3": "", 
					"btn4": "", 
					"btn5": "",
					"senderBox": senderBox, 
					"smsOnly": smsOnly,
					"customer_all_count": customer_all_count,
					"customer_filter": customer_filter,
					"reserveDt": reserveDt, 
					"smslms_kind":smslms_kind, 
					"mem_2nd_send":mem_2nd_send,
					"mms_id":mms_id,
					"fail_mst_id":"<? if($fail_mst_id) { echo $fail_mst_id; } else { echo ""; } ?>",
					"mst_type1" : mst_type1, // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
					"mst_type2" : mst_type2	// 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문자
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
						$(".content").html("발송 요청되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("발송 실패되었습니다.<br/>" + message);
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
		} 
		<? } ?>
	}
	function all_move2() {
		var senderBox = $("#sms_sender").val();
		var smsOnly='';
		var templi_cont = $("#templi_cont").val();
		var profile = document.getElementById("pf_key").value;
		var kind = ms;
		var msg = $('#lms').val();
		var tit = $("#tit").val();

		var img_url = '';
		var img_link = '';
		if ($("#img_url").val() != undefined && $("#img_url") != "" && $("#img_link") != "") {
			img_url = $("#img_url").val();
			img_link = $("#img_link").val();
		}
		if ('0' != 0 && $("#tel_temp").val() == undefined){
			smsOnly = 'Y';
		}

		var btn = [];
		for (var b = 1; b <= 5; b++) {
			btn[b] = new Array();
		}
		var obj = [];
		var btn_num = 1;
		$("#friendtalk_table tr").each(function () {
			var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
			if (btn_type != undefined && btn_type != "N") {
				obj[btn_num] = new Object();
				obj[btn_num].type = btn_type;
				if (btn_type == "WL") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name2")).val().trim();
					obj[btn_num].url_mobile = $(this).find(document.getElementsByName("btn_url21")).val().trim();
					obj[btn_num].url_pc = $(this).next('tr').find(document.getElementsByName("btn_url22")).val().trim();
				} else if (btn_type == "AL") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name3")).val().trim();
					obj[btn_num].scheme_android = $(this).find(document.getElementsByName("btn_url31")).val().trim();
					obj[btn_num].scheme_ios = $(this).next('tr').find(document.getElementsByName("btn_url32")).val().trim();
				} else if (btn_type == "BK") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name4")).val().trim();
				} else if (btn_type == "MD") {
					obj[btn_num].name = $(this).find(document.getElementsByName("btn_name5")).val().trim();
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

		var reserveDt = "00000000000000";
		if($("#reserve_check").is(":checked") == true) {
			if($("#reserve").val().trim() != ""){
				var reserve = $("#reserve").val().replace(/ /gi, "").replace(/-/gi, "").replace(/시/gi, "").replace(/분/gi, "");
				reserveDt = reserve+"00";
			}
		}

		if ($("#customer_all_send").val() != undefined) { // 전체 고객 발송
			var customer_all_count = $("#customer_all_send").val();
			var customer_filter = $("#customer_filter").val();
			var smslms_kind = $("#smslms_kind").val();
			var mem_2nd_send = $("#mem_2nd_send").val();

			//alert(smslms_kind + ", " + mem_2nd_send);
			
			$.ajax({
				url: "/biz/sender/lms/all",
				type: "POST",
				data: {
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"templi_cont": templi_cont,
					"msg": msg, 
					"kind": kind, 
					"tit": tit,
					"tit": tit, 
					"pf_key": profile, 
					"img_url": img_url, 
					"img_link": img_link,
					"btn1": btn1, 
					"btn2": btn2, 
					"btn3": btn3, 
					"btn4": btn4, 
					"btn5": btn5,
					"senderBox": senderBox, 
					"smsOnly": smsOnly,
					"customer_all_count": customer_all_count,
					"customer_filter": customer_filter,
					"reserveDt": reserveDt, 
					"smslms_kind":smslms_kind, 
					"mem_2nd_send":mem_2nd_send,
					"mms_id":mms_id,
					"fail_mst_id":"<? if($fail_mst_id) { echo $fail_mst_id; } else { echo ""; } ?>"
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
						$('#navigateURL').val("/biz/sender/history_dt");
						$(".content").html("발송 요청되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("발송 실패되었습니다.<br/>" + message);
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
		} else if ($("#upload_send").val() != undefined) { // 업로드 발송
			var upload_count = $("#upload_send").val();
			var file_data = new FormData();
			file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			file_data.append("pf_key", profile);
			file_data.append("senderBox", senderBox);
			file_data.append("upload_count", upload_count);
			file_data.append("reserveDt", reserveDt);
			file_data.append("file", $("input[id=filecount]")[0].files[0]);
			file_data.append("smslms_kind", $("#smslms_kind").val());
			file_data.append("mem_2nd_send",$("#mem_2nd_send").val());
			file_data.append("mms_id",mms_id);
			file_data.append("templi_cont", msg);
			file_data.append("msg", msg);
			file_data.append("kind", kind);
			file_data.append("tit", tit);
			file_data.append("smsOnly", smsOnly);

			$.ajaxSettings.traditional = true;
			$.ajax({
				url: "/biz/sender/lms/all",
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
					if (code == "success") {
						//$('#navigateURL').val(document.location.href);
						$('#navigateURL').val("/biz/sender/history_dt");
						$(".content").html("발송 요청되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("발송 실패되었습니다.<br/>" + message);
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
		} else {
			var tel_number = new Array();
			$("input:checkbox[name=sel_del]").each(function () {
				var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
				tel_number.push(array);
			});
			var smslms_kind = $("#smslms_kind").val();
			var mem_2nd_send = $("#mem_2nd_send").val();
			//alert(smslms_kind + ", " + mem_2nd_send);
			$.ajaxSettings.traditional = true;
			$.ajax({
				url: "/biz/sender/lms/all",
				type: "POST",
				data: {
					<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					"templi_cont": templi_cont, 
					"msg": msg, 
					"kind": kind, 
					"senderBox": senderBox, 
					"tit": tit,
					"pf_key": profile, 
					"img_url": img_url, 
					"img_link": img_link,
					"btn1": btn1, 
					"btn2": btn2, 
					"btn3": btn3, 
					"btn4": btn4, 
					"btn5": btn5,
					"smsOnly": smsOnly, 
					"tel_number[]": tel_number, 
					"reserveDt": reserveDt, 
					"smslms_kind":smslms_kind, 
					"mem_2nd_send":mem_2nd_send,
					"mms_id":mms_id,
					"fail_mst_id":"<? if($fail_mst_id) { echo $fail_mst_id; } else { echo ""; } ?>"
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
						$('#navigateURL').val("/biz/sender/history_dt");
						$(".content").html("발송 요청되었습니다.");
						$('#myModal').modal({backdrop: 'static'});
						$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".enter").click();
							}
						});
					} else {
						$(".content").html("발송 실패되었습니다.<br/>" + message);
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
		}
	}

	<?/*--------------------------------*
	   | 테스트 발송시 값 체크하는 함수 |
	   *--------------------------------*/?>
	function test_send() {
		if ($('#lms').val().replace(/ /gi,"")==""){
			$(".content").html("문자 내용을 입력해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#lms").focus();
			});
		} else if (getByteLength($("#lms")) > 2000) {
			$(".content").html("문자 내용은 최대 2,000bytes (한글 1,000자)'?> 이내로<br/>입력하여야 합니다.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#lms").focus();
			});
		} else if ($("span[name=test_input_phone_no]").length == 0) {	// 연락처 직접 입력
			$(".content").html("테스트 발송 연락처를 입력하세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else {
			var check = true;
			if (check == true) {
				set_price();
				if(send_price <= 0) 
				{
					$(".content").html("적용 단가 오류 - 확인이 필요 합니다.");
					$('#myModal').modal({backdrop: 'static'});
					check = false;
				}			
			}

			if (check == true) {
				$.ajax({
					url: "/biz/sender/coin",
					type: "POST",
					data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"},
					success: function (json) {
						coin = json['coin'];
						var limit = Number(json['limit']);
						var sent = Number(json['sent']);
						var limit_msg = "";
						if(limit > 0 && sent >= limit) { showLimitOver(); return; }
						if(limit > 0) { limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>"; }

						//alert("coin : "  + coin);
						//alert("send_price : " + send_price);
//						check_resend_method();
						// 재발신 가능 건수를 산정합니다.
						var resends = (send_price > 0) ? Math.floor(Number(coin) / Number(send_price)) : 0;
						var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');

						// 재발신 가능 건수를 산정합니다.
						//var resends = (send_price > 0) ? Math.floor(Number(coin) / Number(send_price)) : 0;
						//var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
						<?/* 업로드 된 건수 */?>
						var upload_send = $("#upload_tel_count").text();
						<?/* 발송가능 건수 표시 : 업로드 된 자료의 경우 업로드 내용으로만 판단할 수 있으므로 잔액이 부족하다는 메시지를 보여줄 수 없습니다. */?>
						var content_msg = " 발송 가능 건수 : " + resend_cnt + "건<br><br>";
						if ($("#smslms_kind").val() == "SMS") {
							content_msg += "단문(SMS) 문자 발송 예상 금액 : "  + (Number(send_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + send_price + ")";
						} else if ($("#smslms_kind").val() == "LMS") {
							content_msg += "장문(LMS) 문자 발송 예상 금액 : "  + (Number(send_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + send_price + ")";
						} else if ($("#smslms_kind").val() == "MMS") {
							content_msg += "MMS 문자 발송 예상 금액 : "  + (Number(send_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + send_price + ")";
						} else {
							content_msg += "문자 발송 예상 금액 : "  + (Number(charge_type) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + charge_type + ")";
						}
						
						$(".content").html(limit_msg + content_msg + "<br/><br/>업로드 파일을 전체 발송하시겠습니까?<br/> 발송할 건수 : " + upload_send + " 건" );

						$("#myModalTest").modal({backdrop: 'static'});
						$(document).unbind("keyup").keyup(function (e) {
							var code = e.which;
							if (code == 13) {
								$(".test-send").click();
							}
						});
						$(".test-send").click(function () {
							$("#myModalTest").modal("hide");
						});
					}
				});				
			}
    	}
	}

    <?/*--------------------------------------------------------*
       | 테스트 발송 버튼 클릭시 실제 발송 처리하는 부분입니다. |
       *--------------------------------------------------------*/?>
	function test_move() {
		var senderBox = $("#sms_sender").val();
		var smsOnly='';
		var templi_cont = $("#templi_cont").val();
		var profile = document.getElementById("pf_key").value;
		var kind = ms;
		//var msg = $('#lms').val();
		var lms_header_temp = "";
		var lms_footer_temp = "";
		var msg = "";
		lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		msg = lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
        // 2019.01.21 이수환 특수문자 제거 테스트
        msg = msg.replace(/[\u2100-\u2120\u2123-\u214F\u2190-\u21FF\u0080-\u008F\uFE50-\uFE6F\u2460-\u24FF\u2300-\u259F\u25A2\u25A4-\u25A5\u25A7-\u25B1\u25B4-\u25B5\u25B8-\u25BB\u25BE-\u25BF\u25C2-\u25C5\u25C8-\u25CA\u25CC-\u25CD\u25D2-\u2604\u2607-\u260D\u260F-\u261D\u261F-\u263F\u2641\u2643-\u2660\u2662\u2664\u2666\u2668-\u2669\u266B\u266D-\u303F\u3100-\u32FF\u2150-\u2189\u2200-\u2298\u229A-\u22FF\u0080-\u00FF\u2000-\u203A\u203C-\u206F\uFF00-\uFF02\uFF04-\uFF05\uFF07-\uFF1F\uFF21-\uFFEF\u3130-\u318F\u0000-\u0009\u3300-\u33FF\u0400-\u04FF\u2070-\u209C\u2714]/gi, ""); 
        msg = msg.replace(/[\u200B\u3000\u3164\uDB40\u272A]/gi, "");
        msg = msg.replace(/[\u2027]/gi, " ");
        
		var tit = $("#tit").val();

		// 2019.01.31. 이수환 수정 추가  <?/* 2차발신 방법 */?>
		var temp_kind = "";	
		if ($("#smslms_kind").val()=="SMS") { temp_kind = "S"; }
		else if ($("#smslms_kind").val()=="LMS") { temp_kind = "L"; }
		else {temp_kind = "M";}
		
		// 2019.01.31. 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
		var mst_type1 = '';		// 1차 문자 type 공백
		var mst_type2 = '';
		// 2차 문자가 있으면(웹(A)LMS : wa, 웹(A)SMS : was, 웹(A) MMS : wam, 웹(B)LMS : wb, 웹(B)SMS : wbs, 웹(B) : wbm, 폰문자: phn
		var temp_mst_kind = $("#mem_2nd_send").val();
		if(temp_mst_kind == "GREEN_SHOT") {
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			} 
			mst_type2 = "wa" + temp_resend_type;
		} else if (temp_mst_kind == "NASELF"){
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			} 
			mst_type2 = "wb" + temp_resend_type;
		} else if (temp_mst_kind == "015") {
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			} 
			mst_type2 = "015" + temp_resend_type;
		} else if (temp_mst_kind == "PHONE") {
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			} 
			mst_type2 = "phn" + temp_resend_type;
		}
		// 2019.01.31. 이수환 추가 끝 ; mst_type1(1차 문자 = 카카오 관련 문자), mst_type2(2차 문자 = 카카오톡 외의 문자)
		var reserveDt = "00000000000000";
		
		var tel_number = new Array();
		$("span[name=test_input_phone_no]").each(function () {
			//var array = $(this).parent().parent().parent().parent().find("#tel_number").val().trim();
			var array = $(this).text().replace(/-/gi, "");
			tel_number.push(array);
		});
		var smslms_kind = $("#smslms_kind").val();
		var mem_2nd_send = $("#mem_2nd_send").val();
		//alert(smslms_kind + ", " + mem_2nd_send);
		$.ajaxSettings.traditional = true;
		$.ajax({
			url: "/biz/sender/lms/all",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"templi_cont": templi_cont, 
				"msg": msg, 
				"kind": kind, 
				"senderBox": senderBox, 
				"tit": tit,
				"pf_key": profile, 
				"img_url": "", 
				"img_link": "",
				"btn1": "", 
				"btn2": "", 
				"btn3": "", 
				"btn4": "", 
				"btn5": "",
				"smsOnly": smsOnly, 
				"tel_number[]": tel_number, 
				"reserveDt": reserveDt, 
				"smslms_kind":smslms_kind, 
				"mem_2nd_send":mem_2nd_send,
				"mms_id":mms_id,
				"fail_mst_id":"<? if($fail_mst_id) { echo $fail_mst_id; } else { echo ""; } ?>",
				"mst_type1" : mst_type1, // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
				"mst_type2" : mst_type2	// 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문자
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
					//$('#navigateURL').val("/biz/sender/history_dt");
					$(".content").html("발송 요청되었습니다.");
					$('#myModal').modal({backdrop: 'static'});
					//$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				} else {
					$(".content").html("발송 실패되었습니다.<br/>" + message);
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
	}
    
	function include_msg() {
		$("input[name='selMsg']:checked").each(function () {
			var checked = $(this).val();
			if(checked > 0) {
				set_lms_by_user_msg(checked, "U");
				return false;
			}
		});
		
		$("input[name='preselMsg']:checked").each(function () {
			var checked = $(this).val();
			if(checked > 0) {
				set_lms_by_user_msg(checked, "P");
				return false;
			}
		});		
	}

	function delete_msg() {
		var msgIds = [];
		$("input[name='selMsg']:checked").each(function () {
			var checked = $(this).val();
			msgIds.push(checked);
		});
		var result = confirm('선택한 메세지를 삭제 하시겠습니까?'); 
		if(result) { //yes 
			open_page_user_lms_msg(1, msgIds);
		} else { 
			//no 
		}

		
		
	}

	function set_lms_by_user_msg(msg_id, msg_type) {

		$("#myModalUserMSGList").modal('hide');
		$("#myModalUserMSGList .include_phns").unbind("click");
		
		$.ajax({
			url: "/biz/sender/lms/msg_load",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"msg_id": msg_id,
				"msg_type" : msg_type
				},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				var msg_id = json['msg_id'];
				
				//var msg = json['mst_lms_content'];
				var msg = json['mst_lms_content'];

				var kind = json['msg_kind'];
				if (msg_id>0) {
					$("#smslms_kind").val(kind).trigger('change');
					//$("#smslms_kind").
					//msgtypeChange($("#smslms_kind"));
					$("#lms").val(msg).trigger('keyup');
					onPreviewText();
					//resize_cont($("#lms"));
					chkword_lms();
					$(".uploader").css('display','none'); 
					$(".content").html("메시지 불러오기가 완료 되었습니다.");
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				} else {
					$(".content").html("메시지 불러오기가 실패되었습니다.<br/>" + message );
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				}
			},
			error: function (request,status,error){
				$(".content").html("메시지 불러오기가 실패되었습니다.(ER)"+status+","+error);
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
/*
 * 문자 내역 저장용
 */
 function open_page_user_lms_msg(page, delids ) {
		var searchMsg = $('#searchMsg').val() || '';
		var searchKind = $('#searchKind').val() || '';

		$('#myModalUserMSGList .content').html('').load(
			"/biz/sender/lms/msg_save_list",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_msg": searchMsg,
				//"search_kind": "FT",
				"search_kind": searchKind,
				"del_ids[]":delids,
				'page': page,
				'is_modal': true
			},
			function () {
				$('.uniform').uniform();
				$('select.select2').select2();				
			}
		);

 }

 function open_page_pre_lms_msg(page, delids ) {
		var searchMsg = $('#searchMsg').val() || '';
		var searchKind = $('#searchKind').val() || '';

		$('#myModalUserMSGList .content').html('').load(
			"/biz/sender/lms/pre_msg_list",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_msg": searchMsg,
				"search_kind": "FT",
				"del_ids[]":delids,
				'page': page,
				'is_modal': true
			},
			function () {
				$('.uniform').uniform();
				$('select.select2').select2();				
			}
		);

 }
    function search_msg(page) {
    	open_page_user_lms_msg('1');	
    }
    
	function open_lmsmsg_list() {

		$("#myModalUserMSGList").modal({backdrop: 'static'});
		$("#myModalUserMSGList").on('shown.bs.modal', function () {
			$('.uniform').uniform();
			$('select.select2').select2();
		});

		$('#myModalUserMSGList').unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 27) {
				$(".btn-default.dismiss").click();
			} else if (code == 13) {
				include_customer()
			}
		});

		$("#myModalUserMSGList .include_phns").click(function () {
			include_customer();
		});
		
		open_page_user_lms_msg('1');		
	}

		
	function msg_save(msg_type) {
		var msg = $('#lms').val();
		var msg_kind = $("#smslms_kind").val(); 

		//var second_flag = ($("input:checkbox[id='lms_select']").is(":checked") == true) ? "Y" : "N";
		//if (second_flag == "N") msg = "";
	
		$.ajax({
			url: "/biz/sender/lms/msg_save",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"msg": msg, "msg_type": msg_type, "msg_kind":msg_kind
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
					$(".content").html("저장이 완료 되었습니다.");
					$('#myModal').modal({backdrop: 'static'});
					$(document).unbind("keyup").keyup(function (e) {
						var code = e.which;
						if (code == 13) {
							$(".enter").click();
						}
					});
				} else {
					$(".content").html("저장이 실패되었습니다.<br/>" + message);
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
	}


	<?/*------------------------*
	  | 친구톡 내용 글자수 제한 |
	  *-------------------------*/?>
	function chkword_templi() {
		var msg_length = $("#templi_cont").val() && $("#templi_cont").val().length || 0;
		$("#type_num").html(msg_length);
		<?/* 이미지가 포함되면 400자 이내로 제한 */?>
		if ($("#img_url").val() != "" && $("#img_url").val() != undefined) {
			var limit_length = 400;
			var msg_length = $("#templi_cont").val().length;
			if (msg_length <= limit_length) {
				$("#type_num").html(msg_length);
			} else if (msg_length > limit_length) {
				$(".content").html("템플릿 내용을 400자 이내로 입력해주세요.");
				$("#myModal").modal({backdrop: 'static'});
				$('#myModal').on('hidden.bs.modal', function () {
					$("#templi_cont").focus();
				});
				var cont = $("#templi_cont").val();
				var cont_slice = cont.slice(0, 400);
				$("#templi_cont").val(cont_slice);
				$("#type_num").html(400);
				$("#text").val(cont_slice);
				return;
			} else {
				$("#type_num").html(msg_length);
			}
		} else if ($("#img_url").val() == undefined || $("#img_url").val() == "") {
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
			var cont_slice = cont.slice(0, 1000);
			$("#templi_cont").val(cont_slice);
			$("#type_num").html(1000);
			$("#text").val(cont_slice);
				return;
			} else {
				$("#type_num").html(msg_length);
			}
		}
	}

// 	function msgtypeChange(msgkind) {
// 		//$("#mms_att").hide();

// 		if(msgkind === undefined) {
// 			msgtype = $("#smslms_kind").val();
// 		} else {
// 			msgtype = msgkind;
// 		}
		
// 		if(msgtype=="SMS") {
// 			$("#max_msg_byte").val(90);
// 			$("#lms_character_limit").html("90 ");
// 		} else if(msgtype=="LMS") {
// 			$("#max_msg_byte").val(2000);
// 			$("#lms_character_limit").html("2000 ");
// 		} else if(msgtype=="MMS") {
// 			if($("#mem_2nd_send").val() == "NASELF") {
// 				$("#max_msg_byte").val(2000);
// 				$("#lms_character_limit").html("2000 ");
// 				$("#mms_att").show();
// 			} else {
// 				$('#smslms_kind').val(old_mem_send).trigger('change');
// 				msgtypeChange(old_mem_send);
// 			}
// 		}

// 		old_mem_send = msgtype;
// 	}

// 	function methodChange(mem2nd) {
// 		//$("#mms_att").hide();
// 		var phn = "";
// 		if( mem2nd.value=="015") { 
// 			phn = "080-1111-1111";
// 			$('#smslms_kind').val("LMS").trigger('change');
// 			msgtypeChange();
// 		} else if( mem2nd.value=="NASELF") { 
// 			phn = "080-855-5947";
// 			if($("#smslms_kind").val()=="MMS") {
// 				$("#mms_att").show();
// 			}
// 		} else if( mem2nd.value=="GREEN_SHOT") { 
// 			phn = "080-156-0186";
// 			$('#smslms_kind').val("LMS").trigger('change');
// 			msgtypeChange();
// 		} else if( mem2nd.value=="PHONE") { 
// 			phn = "080-889-8383";
// 			$('#smslms_kind').val("LMS").trigger('change');
// 			msgtypeChange();
// 		} 
		
// 		var content = $("#lms").val();
// 		$("#lms").val(content.replace(/무료.*/, "무료거부 : " +phn));		 
// 		onPreviewText();
// 		chkword_lms();
// 		resize_cont($("#lms"));
// 	}
	
	<?/*-------------------------*
	  | 2차발신 내용 글자수 제한 |
	  *--------------------------*/?>
	function chkword_lms() {
		//chkbyte($("#lms_header"), $("#lms"), $("#lms_footer"), $("#lms_num"), <?=(($mem->mem_2nd_send=='sms') ? '80' : '2000')?>);
		chkbyte($("#lms_header"), $("#lms"), $("#lms_footer"), $("#lms_num"));
	}

	function chkbyte($obj_header, $obj, $obj_footer, $num) {
		//var oriMaxByte = maxByte;
		var phn = '<?=$this->Biz_model->reject_phn?>';
		var phn_len = phn.length + 16;

		var lms_header_temp = $obj_header.html().replace("<BR>", "\n").replace("<br>", "\n");
		var lms_footer_temp = $obj_footer.html().replace("<BR>", "\n").replace("<br>", "\n");
		
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
// 			if (totalByte <= maxByte) {
// 				 len = i + 1;
// 			}
		}						
		$num.html(totalByte);

		//if (maxByte <= 80) {
		
		var mms_flag = false;
		$("div[name='mms-images']").each(function () {
			var filename = $(this).find(document.getElementsByName("fileInput")).val();
			if (filename != "") {
				mms_flag = true;
			}
		});
		if (!mms_flag) {
			if (totalByte <= 80) {
				//$("#lms_limit_str").html("SMS");
				$("#lms_limit_str").attr("class", "msg_type_txt sms");
				$(".msg_type").attr("class", "msg_type sms");
				$("#lms_character_limit").html("80");
				$("#smslms_kind").val("SMS");
			} else {
	    		//$("#lms_limit_str").html("LMS");
	    		$("#lms_limit_str").attr("class", "msg_type_txt lms");
	    		$(".msg_type").attr("class", "msg_type lms");
	    		$("#lms_character_limit").html("2000");
	    		$("#smslms_kind").val("LMS");
			}
		} else {
    		$("#lms_limit_str").attr("class", "msg_type_txt mms");
    		$(".msg_type").attr("class", "msg_type mms");
    		$("#lms_character_limit").html("2000");
    		$("#smslms_kind").val("MMS");
    	}
		


//		<?/* 넘어가는 글자는 자른다. */?>
// 		if (totalByte > maxByte) {
//			$(".content").html("<?=($mem->mem_2nd_send=='phn') ? '폰문자' : strtoupper($mem->mem_2nd_send)?> 내용을 " + oriMaxByte + "자(한글 " + parseInt(oriMaxByte / 2) + "자) 이내로<br/>입력해주세요.<br/>(실제내용 " + (maxByte - phn_len - 20) + "자 이내)");
// 			$("#myModal").modal({backdrop: 'static'});
// 			$('#myModal').on('hidden.bs.modal', function () {
// 				$("#lms").focus();
// 			});

// 			$obj.val(str2);
// 			$num.html(totalByte);
//          chkbyte($obj_header, $obj, $obj_footer, $num, oriMaxByte);
//		}
   }
	   
// 	function getMsgType() {
// 		if($('#mem_2nd_send').val()=="GREEN_SHOT") 
// 			return "WEB문자(A)";
// 		if($('#mem_2nd_send').val()=="NASELF") 
// 			return "WEB문자(B)";
// 		if($('#mem_2nd_send').val()=="015") 
// 			return "015문자";
// 		if($('#mem_2nd_send').val()=="PHONE") 
// 			return "폰문자";
// 		return "";
// 	}

	<?/*--------------------*
	  | 글자수(Byte)를 반환 |
	  *---------------------*/?>
	function getByteLength($obj)
	{
		var strValue = $obj.val();
		var strLen = strValue.length;
		var totalByte = 0;
		var oneChar = "";

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
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
	//글자수 제한 - 링크 이름
	function chkword_btn() {
		var limit_length = 30;
		var link_length = $("#btn_name").val().length;
		if (link_length <= limit_length) {
			$("#link_num").html(link_length);
		} else if (link_length > limit_length) {
			$(".content").html("링크 버튼 이름을 30자 이내로 입력해주세요.");
			$("#myModal").modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				$("#btn_name").focus();
			});
			var cont = $("#btn_name").val();
			var cont_slice = cont.slice(0, 30);
			$("#btn_name").val(cont_slice);
			$("#link_num").html(30);
			return;
		} else {
			$("#link_num").html(link_length);
		}
	}

	//글자수 제한 - 링크 url
	function chkword_url() {
		var limit_length = 250;
		var link_length = $("#btn_url").val().length;
		if (link_length > limit_length) {
			var cont = $("#btn_url").val();
			var cont_slice = cont.slice(0, 250);
			$("#btn_url").val(cont_slice);
			$("#btn_url").focus();
		}
	}

	// input enter 키 방지
	function captureReturnKey(e) {
		if(e.keyCode==13 && e.srcElement.type != 'textarea')
			return false;
	}

	//프로필 선택시
	function profile() {
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/biz/sender/send/profile3");
		form.submit();
	}


	// 프로필선택 버튼
	//function btnSelectProfile() {
	//	var count = '';
	//	console.log('count', count);
	//	if('' == "none") {
	//		$('#profile_danger').modal('show');
	//	} else {
	//		$("#profile_select").modal({backdrop: 'static'});
	//		$('#profile_select').unbind("keyup").keyup(function (e) {
	//			var code = e.which;
	//			if (code == 27) {
	//				console.log('code == 27');
	//				$("#profile_select").modal("hide");
	//			}
	//			else if ($("#search").is(":focus")) {
	//				if (code == 13) {
	//					search_enter(); // todo check
	//				}
	//			} else {
	//				if (code == 13) {
	//					select_profile();
	//				}
	//			}
	//		});
	//	}

	//	$('#profile_select .widget-content').html('').load(
	//		"/biz/sender/send/profile4",
	//		{
	//			<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
	//			"page": 1
	//		},
	//		function() {
				//$('#profile_select').css({"overflow-y": "scroll"});
	//		}
	//	);
	//}

	// copy from customer_list
	function open_page2(page) {
		var type = $('#searchType2').val() || 'all';
		var searchFor = $('#searchStr2').val() || '';

		$('#profile_select .widget-content').html('').load(
			"/biz/sender/send/profile5",
			{
				"search_type": type,
				"search_for": searchFor,
				"page": page
			},
			function() {
				//$('#profile_select').css({"overflow-y": "scroll"});
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

		imgSelectBox = window.open("/biz/sender/images", 'tst', 'width=' + sw + ',height=' + sh + ',top=' + mt + ',left=' + ml + ',location=no, resizable=no');
	}

	//function setImageValue(selctImgValue, selctImgLink) {
	//	$("#img_url").val(selctImgValue);
	//	readImage(selctImgValue);

	//	var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
	//	$("input[name=img_link]").val(selctImgLink);
	//}

	//미리보기 - 이미지
	function readImage(selctImgValue, img_no) {

		  if(img_no === undefined) {
			  img_no = 1;
		   }

		var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var msg_text = "";	

		if ($("#lms").val().replace(/\n/g, "").length > 0) {
			msg_text = msg_text + lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
		} else {
			msg_text = msg_text + lms_header_temp + "\n\n메세지를 입력해주세요.\n\n" + lms_footer_temp;
		}
		msg_text = msg_text.replace(/\n/g, "<br>");
				
		$("#image"+img_no).remove();
		var image = "<img id='image"+img_no+"' name='image' src='" + selctImgValue + "' style='width:100%; margin-bottom:5px;'/>";

		//var cont_length = $("#templi_cont").val().length;
		//var span = "<span id='type_num'>" + cont_length + "</span>";
		//document.getElementById('templi_length').innerHTML = span + "/400자";
		//$("#text").val($("#templi_cont").val());

		$('.uniform').uniform();
		$("#img_link").attr("readonly", false);
		if(img_no == 1) {
			if($("#image2").length) {
				$("#image2").before(image);
			} else if($("#image3").length) {
				$("#image3").before(image);
			} else {
				$("#text").html(image + msg_text);
			}
		}

		if(img_no == 2) {
			if($("#image1").length) {
				$("#image1").after(image);
			} else if($("#image3").length) {
				$("#image3").before(image);
			} else {
				$("#text").html(image + msg_text);
			}
		}

		if(img_no == 3) {
			if ($("#image2").length) {
				$("#image2").after(image);
			} else if ($("#image2").length) {
				$("#image1").after(image);
			} else {
				$("#text").html(image + msg_text);
			}
		}
		
		//$("#friendtalk_table tr").each(function () {
		//	if($(this).find("#no").text()) {
		//		var current_btn_num = $(this).find("#no").parent().attr("name");
		//		link_name(document.getElementById("btn_type"+current_btn_num),current_btn_num);
		//	}
		//});
		//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
    }

	//업로드 양식 다운로드
	function download() {
		document.location.href="/uploads/number_list.xlsx";
	}

	function img_delete(deleteNo, fileInputCount) {
		alert("deleteNo : " + deleteNo + "  fileInputCount : " + fileInputCount);
        var file_data = new FormData();
        file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
        file_data.append("mms_fileInput_no", deleteNo);
        file_data.append("mms_fileInput_cnt", fileInputCount);
        file_data.append("mms_id", mms_id);
        $.ajax({
            url: "/biz/sender/lms/mms_image_delete",
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
            	if (json['code'] == 'success') {
            		mms_id = json['mms_id'];
            	}
            },
            error: function (data, status, er) {
                $(".content").html("처리중 오류가 발생했습니다.<br>관리자에게 문의하십시오.");
                $("#myModal").modal('show');
            }
        });
	}
	
    function img_upload(userfile, fileinput) {
        var thumbext = $("#" + userfile).val();
        var file_data = new FormData();
        file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
        file_data.append(fileinput, $("#"+fileinput)[0].files[0]);
        file_data.append("mms_id", mms_id);

        //var mms_field = "";
		//if(userfile == "userfile1") {
		//	mms_field = "mms_file2";
		//}else if (userfile == "userfile2") {
		//	mms_field = "mms_file3";
		//}
        
        thumbext = thumbext.slice(thumbext.indexOf(".") + 1).toLowerCase();
        if (thumbext) {
                $.ajax({
                    url: "/biz/sender/lms/mms_upload",
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
                        $(".content").html("처리중 오류가 발생했습니다.<br>관리자에게 문의하십시오.");
                        $("#myModal").modal('show');
                    }
                });
                
                function showResult(json) {
   	              if (json['code'] == 'success') {
					mms_id = json['mms_id'];
					$("#urluserfile" + userfile.replace("userfile","")).val(json['imgurl']);
					readImage(json['imgurl'], userfile.replace("userfile",""));
					//$("#" + mms_field).css('display',''); 
					
                  } else {
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
                            messagekr = '관리자에게 문의하십시오';
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

	
    //업로드
   function readURL(input) {
		var file = document.getElementById('filecount').value;
		file = file.slice(file.indexOf(".") + 1).toLowerCase();
		//if (file.equals("xls") || file.equals("xlsx")) {
		if (file == "xls" || file == "xlsx" || file == "txt") {
			var formData = new FormData();
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			formData.append("file", $("input[name=filecount]")[0].files[0]);
			formData.append("ext", file);
			$.ajax({
				url: "/biz/sender/upload",
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
						$(".content").html("올바르지 않은 파일입니다.<br>"+msg);
						$('#myModal').modal({backdrop: 'static'});
						//$('#filecount').filestyle('clear');
					} else {
						var row_len = json['row_len'];

						var coin = json['coin'];
						var ft_count = json['ft_count'];
						var ft_img_count = json['ft_img_count'];
						var sms_count = 0; try { sms_count=json['sms_count']; }catch(e){}
						var lms_count = json['lms_count'];
						var mms_count = json['mms_count'];

<? /* upload 에서 반환하는 숫자의 lms는 sms(이미지가 있어도 90자 이하면 sms개수에 더해집니다.)와 mms가 포함된 갯수입니다. */
if($mem->mem_2nd_send=='mms') { /* 2차발신이 mms인 경우 이미지가 없는 발신은 lms 단가로 계산 : resend_price 적용 */ ?>
						var total_price = (Number(ft_count) * Number(send_price)) + (Number(ft_img_count) * Number(ft_img_price)) + (Number(mms_count) * Number(resend_price)) + ((Number(lms_count) - Number(mms_count)) * Number(lms_price));
<?} else { /* 2차발신이 mms가 아닌 경우 이미지 여부와 관계없이 금액 계산 : resend_price 적용 */ ?>
						var total_price = (Number(ft_count) * Number(send_price)) + (Number(ft_img_count) * Number(ft_img_price)) + ((Number(lms_count) - Number(sms_count)) * Number(resend_price)) + (Number(sms_count) * Number(sms_price));
<?}?>
						if (Number(coin) - Number(total_price) < 0) {
							$(".content").html("잔액이 부족합니다.<BR>잔액:"+coin+"<BR>예상금액:"+Number(total_price));
							$('#myModal').modal({backdrop: 'static'});
							//$('#filecount').filestyle('clear');
						} else if (row_len > 60000) {
							$(".content").html("최대 6만건까지 가능합니다.");
							$('#myModal').modal({backdrop: 'static'});
							//$('#filecount').filestyle('clear');
						} else {
							var bulk_send = json['row_len'];
							var tot_row = json['tot_row_len'];
							var ex_row = json['ex_row_len'];
							var coin = json['coin'];
							var limit_count;

							//if (ms != "" && $("#img_url").val() == "") { // 파일안에 이미지갯수.파악해서 mms가격도 고바기 해야됑..
							//	limit_count = Number(resend_price)*Number(bulk_send);
							//} else {
							//	limit_count = Number(send_price)*Number(bulk_send);
							//}
							//if(coin >= limit_count) {
								//$("#number_add").hide();
								//$("#number_del").hide();
								//$("#send_select").hide();
								//$("#tel").hide();
								//$("#save_temporal_msgs").hide();
/*
								//미리보기
								$("#text").val("파일이 업로드 되었습니다.");
								$("#text").css("height","1px").css("height",($("#text").prop("0px")));
								var height = $("#text").prop("scrollHeight");
								if( height < 408 ) {
									$("#text").css("height","1px").css("height",($("#text").prop("scrollHeight"))+"px");
								} else {
									$("#text").css("height", "1px").css("height", "460px");
								}
*/								
/*
								if ($("#img_url").val() != "") {
									$("#lms_ptag").html('<input type="checkbox" id="lms_select" class="uniform" onchange="select_lms(this)">LMS 발신 여부()');
									var lms = "<tr id='smslms'><th>LMS</th><td colspan='5'><textarea name='msg' id='lms' name='lms' cols='30' rows='5' class='form-control autosize' " +
									"placeholder='LMS 발신 체크를 선택해주세요.' style='resize:none;cursor:default;background-color:#EEEEEE;' " +
									"onkeyup='onPreviewText();resize_cont(this);chkword_lms();' disabled='disabled'></textarea>" +
									"<p class='help-block align-left' style='width:80% !important'><input type='checkbox' id='lms_kakaolink_select' " +
									"class='uniform' onclick='insert_kakao_link(this);' style='cursor: default;' disabled> 카카오 친구추가 링크 넣기</p>" +
									"<p class='help-block align-right'><span id='lms_num'>0</span>/<?=(($mem->mem_2nd_send=='sms') ? '90' : '2000')?>자</p></td><input type='hidden' name='tit' id='tit'></tr>";
									$("#templi").after(lms);
									$(".uniform").uniform();
									$("#mms").remove();
									$("#image").remove();
									$("#img_url").val("");
									$("#img_link").val("");
									$("#img_link").attr("readonly", true).css("cursor", "default");
								}
*/
/*
								$("#img_select").attr('disabled', 'disabled').css("cursor","default");
								$("#type_num").html(0);
								$("#templi_cont").val("").attr("readonly",true).css("cursor","default").css("background-color","#EEEEEE");
								$("#lms").val("").attr("disabled",true).css("cursor","default").css("background-color","#EEEEEE");
								$("#lms_num").html(0);

								$("#lms_select").attr('disabled','disabled').css("cursor","default");
								$("#lms_kakaolink_select").attr('disabled','disabled').css("cursor","default");
								$("#lms_kakaolink_select").uniform();

								$("#templi_cont").val("").attr("readonly", true).css("cursor", "default").css("background-color", "#EEEEEE");

								if($("input:checkbox[id='lms_select']").is(":checked") == true){
									$("input:checkbox[id='lms_select']").removeAttr('checked');
								}
								if($("input:checkbox[id='lms_kakaolink_select']").is(":checked") == true) {
									$("input:checkbox[id='lms_kakaolink_select']").removeAttr('checked');
								}

								//버튼 비활성화
								$("#friendtalk_table tr").each(function () {
									if($(this).find("#no").text()) {
										if ($(this).find("#no").text() != 1) {
											var name = $(this).find("#no").parent().attr("name");
											$("#btn_preview_div"+name).remove();
											$(this).next().remove();
											$(this).remove();
										} else {
											var current_btn_num = $(this).find("#no").parent().attr("name");
											$("#no").attr("hidden", true);
											$("#btn_type_td").attr("hidden", true);
											$("#n_1_"+current_btn_num).attr("hidden",true);
											$("#n_2_"+current_btn_num).attr("hidden",true);
											$("#wl_1_"+current_btn_num).attr("hidden",true);
											$("#wl_2_"+current_btn_num).attr("hidden",true);
											$("#wl_3_"+current_btn_num).attr("hidden",true);
											$("#al_1_"+current_btn_num).attr("hidden",true);
											$("#al_2_"+current_btn_num).attr("hidden",true);
											$("#al_3_"+current_btn_num).attr("hidden",true);
											$("#bk_1_"+current_btn_num).attr("hidden",true);
											$("#bk_2_"+current_btn_num).attr("hidden",true);
											$("#md_1_"+current_btn_num).attr("hidden",true);
											$("#md_2_"+current_btn_num).attr("hidden",true);
											$("#btn_del").attr("hidden", true);
											$("#btn_add_msg").attr("hidden", false);
											$("#btn_type"+current_btn_num).val("N");
											$("#btn_type"+current_btn_num).select2();
											//$("#btn_type"+current_btn_num+" option:eq(0)").prop("selected", true);
											//$("#btn_type"+current_btn_num).select2("val", "N");
											$("#friendtalk_table").find(document.getElementsByName("btn_name1")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_name2")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_name3")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_name4")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_name5")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_url21")).val("http://");
											$("#friendtalk_table").find(document.getElementsByName("btn_url22")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_url31")).val("");
											$("#friendtalk_table").find(document.getElementsByName("btn_url32")).val("");
										}
									}
								});
								$("#text").css("margin-bottom","0px");

								$("#btn_add").attr("hidden", true);

								document.getElementById("templi_cont").style.height = "103px";
								document.getElementById("lms").style.height = "103px";

*/
								//$(".tel_content").after('<div class="widget-content" id="upload_result"><p>업로드 결과 : ' + bulk_send + ' 명의 수신자가 지정되었습니다.(총: ' + tot_row + ' 제외: ' + ex_row + ')</p><input type="hidden" id="upload_send" value="' + bulk_send + '"></div>');
								//$(".uniform").uniform();
								$("#upload_tel_count").html(bulk_send);
							//} else {
							//	$(".content").html("충전 잔액이 부족합니다.");
							//	$('#myModal').modal({backdrop: 'static'});
							//	$('#filecount').filestyle('clear');
							//}
						}
					}
				}
			});
		} else {
			$(".content").html("xls,xlsx, txt 파일만 가능합니다.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').on('hidden.bs.modal', function () {
				//$('#filecount').filestyle('clear');
				//var table = document.getElementById("tel");
				//var row_index = table.rows.length;

				//for (var i = 1; i < row_index; i++) {
				//	var tr = $("." + i);
				//	tr.remove();
				//	var height = $("#tel_tbody").prop("scrollHeight");
				//	$("#tel_tbody").css("height", "0px");
				//}

				//var add = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
				//	'<input name="sel_del" id="' + 1 + '" class="uniform" value="' + 1 + '" type="checkbox"></td>'+
				//	'<td width="15%"><span id="ab_kind">&nbsp;</span></td><td width="15%"><span id="ab_name">&nbsp;</span></td>' +
				//	'<td width="40%"><input type="text" ' +
				//	'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
				//	'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
				//	'<i class="icon-trash"></i> 삭제</a></td></tr>';
				//$("#tel_tbody").html(add);
				//var height = $("#tel_tbody").prop("scrollHeight");
				//$("#tel_tbody").css("height", "47px");
				//$("#1").uniform();
			});
			$(document).unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 13) {
					$(".enter").click();
				}
			});
		}
	}

	<?/*----------------------------------------*
		| 업로드 버튼을 클릭하였을때 메시지 표시 |
		*----------------------------------------*/?>
	function upload() {
		/*if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
			$('#filecount').attr('disabled', 'disabled');
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
			$('#myModal').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27 || code == 13) {
					$(".enter").click();
				}
			});
			$(".enter").click(function () {
				$("#filecount").removeAttr("disabled");
			});
		} else*/
	    if ('0' != 0) {
			$('#filecount').attr('disabled', 'disabled');
		}else {
			$('#filecount').attr('disabled', 'disabled');
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


	<?/*------------------------------------------------------------------*
		| 업로드메시지 확인 클릭시 처리 : 이미지가 등록된 경우 이미지 제거 |
		*------------------------------------------------------------------*/?>
	function check(obj) {
		if ($('#filecount').is('[disabled=disabled]') == true || obj) {
			$("#number_add").show();
			$("#number_del").show();
			$("#send_select").show();
			$("#tel").show();
			$("#save_temporal_msgs").show();
			$("#upload_result").remove();

			if (!obj) {
				$("#filecount").removeAttr("disabled");
			}
			var content = "(광고) "+$("#pf_ynm").val()+"\r\r\r\r수신거부 : 홈>친구차단";

			$("#templi_cont").val(content);
			$("#templi_cont").attr("readonly", false).css("background-color", "white");
			$("#lms_select").removeAttr("disabled");
			$("#img_select").attr('disabled', false).css("cursor","pointer");
			if ($("#img_url").val() != "") {
/*
				$("#lms_ptag").html('<input type="checkbox" id="lms_select" class="uniform" onchange="select_lms(this)">LMS 발신 여부()');
				var lms = "<tr id='smslms'><th>LMS</th><td colspan='5'><textarea name='msg' id='lms' name='lms' cols='30' rows='5' class='form-control autosize' " +
					"placeholder='LMS 발신 체크를 선택해주세요.' style='resize:none;cursor:default;background-color:#EEEEEE;' " +
					"onkeyup='onPreviewText();resize_cont(this);chkword_lms();' disabled='disabled'></textarea>" +
					"<p class='help-block align-left' style='width:80% !important'><input type='checkbox' id='lms_kakaolink_select' " +
					"class='uniform' onclick='insert_kakao_link(this);' style='cursor: default;' disabled> 카카오 친구추가 링크 넣기</p>" +
					"<p class='help-block align-right'><span id='lms_num'>0</span>/<?=(($mem->mem_2nd_send=='sms') ? '90' : '2000')?>자</p></td><input type='hidden' name='tit' id='tit'></tr>";
				$("#templi").after(lms);
				$(".uniform").uniform();
				$("#mms").remove();
*/
				$("#image").remove();
				$("#img_url").val("");
				$("#img_link").val("");
				$("#img_link").attr("readonly", true).css("cursor", "default");
			}
			$("#type_num").html(0);

			if (obj !== 'customer_select') {
				$("#lms").val("").attr("disabled",true).css("cursor","default").css("background-color","#EEEEEE");
			}
			$("#lms_num").html(0);

			$("#lms_kakaolink_select").attr('disabled','disabled').css("cursor","default");
			$("#lms_kakaolink_select").uniform();

			if($("input:checkbox[id='lms_select']").is(":checked") == true){
				$("input:checkbox[id='lms_select']").removeAttr('checked');
			}
			if($("input:checkbox[id='lms_kakaolink_select']").is(":checked") == true){
				$("input:checkbox[id='lms_kakaolink_select']").removeAttr('checked');
			}

			$("#btn_add").attr("hidden", false);
			//$("#text").css("margin-bottom","0px");

			$("#btn_load_customer").removeAttr("disabled");
			$("#number_add").removeAttr("disabled");

			$("#friendtalk_table tr").each(function () {
				if($(this).find("#no").text()) {
					if ($(this).find("#no").text() != 1) {
							var name = $(this).find("#no").parent().attr("name");
							$("#btn_preview_div"+name).remove();
							$(this).next().remove();
							$(this).remove();
					} else if ($(this).find("#no").text() == 1) {
						var current_btn_num = $(this).find("#no").parent().attr("name");
						$("#btn_preview_div"+current_btn_num).remove();
						$("#no").attr("hidden", false);
						$("#btn_type_td").attr("hidden", false);
						$("#n_1_"+current_btn_num).attr("hidden",true);
						$("#n_2_"+current_btn_num).attr("hidden",true);
						$("#wl_1_"+current_btn_num).attr("hidden",false);
						$("#wl_2_"+current_btn_num).attr("hidden",false);
						$("#wl_3_"+current_btn_num).attr("hidden",false);
						$("#al_1_"+current_btn_num).attr("hidden",true);
						$("#al_2_"+current_btn_num).attr("hidden",true);
						$("#al_3_"+current_btn_num).attr("hidden",true);
						$("#bk_1_"+current_btn_num).attr("hidden",true);
						$("#bk_2_"+current_btn_num).attr("hidden",true);
						$("#md_1_"+current_btn_num).attr("hidden",true);
						$("#md_2_"+current_btn_num).attr("hidden",true);
						$("#btn_del").attr("hidden", false);
						$("#btn_add_msg").attr("hidden", true);
						$("#btn_type"+current_btn_num).val("WL");
						$("#btn_type"+current_btn_num).select2();
						//$("#btn_type"+current_btn_num+" option:eq(1)").prop("selected", true);
						//$("#btn_type"+current_btn_num).select2().select2("val", "WL");

						var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
						var pf_ynm = $("#pf_ynm").val();
						$("#btn_name2_"+current_btn_num).val("세일전단지");//pf_ynm);
						$("input[name=btn_url21]").val("http://plus-talk.kakao.com/plus/home/" + pf_yid);
						link_name(document.getElementById("btn_type"+current_btn_num),current_btn_num);
					}
				}
			});
			$('#text').val(content);
			//$("#text").css("height", "1px").css("height", ($("#text").prop("0px")));
			//var height = $("#text").prop("scrollHeight");
			//if (height < 408) {
			//	$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
			//} else {
			//	$("#text").css("height", "1px").css("height", "460px");
			//}

			//if ($('#filecount').val() != "") {
			//	$("#number_add").click(function () {
			//		number_add();
			//	});
			//}
			//$('#filecount').filestyle('clear');
			//var table = document.getElementById("tel");
			//var row_index = table.rows.length;
			//for (var i = 1; i < row_index; i++) {
			//	var tr = $("." + i);
			//	tr.remove();
			//	var height = $("#tel_tbody").prop("scrollHeight");
			//	$("#tel_tbody").css("height", "0px");
			//	var tr = '<tr class="' + 1 + '" id="tel_tbody_tr"><td class="checkbox-column" style="width:10% !important">' +
			//		'<input name="sel_del" id="' + 1 + '" class="uniform" value="' + 1 + '" type="checkbox"></td>'+
			//		'<td width="15%"><span id="ab_kind">&nbsp;</span></td><td width="15%"><span id="ab_name">&nbsp;</span></td>' +
			//		'<td width="40%"><input type="text" ' +
			//		'class="form-control input-width-medium inline tel_number" id ="tel_number" name="tel_number" placeholder="전화번호 입력" tabindex="1" onkeyup="SetNum(this);"></td>' +
			//		'<td width="20%"><a href="javascript:tel_remove(' + 1 + ');" id="tel_remove" class="btn  btn-sm" title="삭제">' +
			//		'<i class="icon-trash"></i> 삭제</a></td></tr>';
			//	$("#tel_tbody").html(tr);
			//	var height = $("#tel_tbody").prop("scrollHeight");
			//	$("#tel_tbody").css("height", "47px");
			//	$("#1").uniform();
			//}
			$("#myModalUpload").modal('hide');

			if (!obj) {
				//$("#filecount").removeAttr("disabled");
				$("#filecount").attr('onclick', '');
				$("#filecount").click();
				$(".up").unbind("click");
				return $("#filecount").attr('onchange', 'readURL()');
			}

		}
	}

	<?/*---------------------------------*
		| 고객정보 체크 선택하여 불러오기 |
		*---------------------------------*/?>
	function load_customer_select() {
		/*if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
		$(".content").html("프로필을 먼저 선택해주세요.");
		$('#myModal').modal({backdrop: 'static'});
		} else*/
		//{
			if ($("#upload_result").val() != undefined) {
				$("#upload_result").remove();
				check('customer_select');
			}

			$("#myModalLoadCustomers").modal({backdrop: 'static'});
			$("#myModalLoadCustomers").on('shown.bs.modal', function () {
				$('.uniform').uniform();
				$('select.select2').select2();
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
		//}
	}

	<?/*------------------------*
		| 고객정보 구분 불러오기 2019.01.18 이수환 추가|
		*------------------------*/?>
	function load_customer_gubun() {
		load_customer_all($('#searchGroup2').val());
	}
		
	<?/*------------------------*
		| 고객정보 전체 불러오기 |
		*------------------------*/?>
	function load_customer_all(filter) {
		filter = ((typeof(filter)=='undefined' || filter==null) ? '' : filter);
		/*if ($("#pf_key").val() == null || $("#pf_key").val()=='') {
			$(".content").html("프로필을 먼저 선택해주세요.");
			$('#myModal').modal({backdrop: 'static'});
		} else {*/
			if (document.getElementById('filecount').value != '') {
				check('customer_all');
			}
			$.ajax({
				url: "/biz/sender/lms/load_customer",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", 'filter': filter },
				type: "POST",
				success: function (json) {
					var customer_count = json['customer_count'];
					if (customer_count > 60000) {
						$(".content").html("최대 60,000명까지 가능합니다.");
						$('#myModal').modal({backdrop: 'static'});
					} else if (customer_count > 0) {
						$("#number_add").hide();
						$("#number_del").hide();
						$("#send_select").hide();
						$("#tel").hide();
						$("#upload_result").remove();
						// 2019.01.17. 이수환 고객구분없음 추가 및 수정
						var filterText = (filter == 'NONE') ?  '고객구분없음' : filter;
						$(".tel_content").after('<div class="widget-content" id="upload_result"><p>'+((filter=='') ? '전체' : filterText)+' 고객 : ' + customer_count + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="customer_all_send" value="' + customer_count + '"><input type="hidden" id="customer_filter" value="' + filter + '"></div>');
						//$(".tel_content").after('<div class="widget-content" id="upload_result"><p>'+((filter=='') ? '전체' : filter)+' 고객 : ' + customer_count + ' 명의 수신자가 지정되었습니다.</p><input type="hidden" id="customer_all_send" value="' + customer_count + '"><input type="hidden" id="customer_filter" value="' + filter + '"></div>');
					} else {
						$(".content").html("고객정보가 없습니다.");
						$('#myModal').modal({backdrop: 'static'});
					}
				},
				error: function (data, status, er) {
					$(".content").html("처리중 오류가 발생하였습니다.");
					$("#myModal").modal('show');
				}
			});
			$("#myModalFilterAll").modal('hide');
		//}
	}

	<?/*----------------------------------------*
		| 그룹으로 선택한 고객정보 전체 불러오기 |
		*----------------------------------------*/?>
	function include_customer(group ) {
		if(($("#sel_all").prop('checked') && $('#searchGroup').val()!='') || group == 'GS') {
			// 2019.01.17 이수환 고객구분없음 추가로 추가및 수정
			var searchGroupText = $('#searchGroup').val();
			if(searchGroupText == "NONE") {
				searchGroupText = "고객구분없음";
			}
			//$(".content").html("선택하신 " + $('#searchGroup').val() + " 고객 전체를 추가 하시겠습니까?");
			$(".content").html("선택하신 " + searchGroupText + " 고객 전체를 추가 하시겠습니까?");
			
			$('#myModalFilterAll').modal({backdrop: 'static'});
			$('#myModalFilterAll').unbind("keyup").keyup(function (e) {
				var code = e.which;
				if (code == 27) {
					$(".btn-default").click();
				} else if (code == 13) {
					load_customer_all($('#searchGroup').val());
					return;
				}
			});
			$(".filter_all").click(function () {
				load_customer_all($('#searchGroup').val());
				return;
			});
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

		console.log('open_page', page);

		$('#myModalLoadCustomers .widget-content').html('').load(
			"/biz/customer/inc_lists",
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

	// profileKey 설정시
// 	function checkTemporalMessages() {
// 		var pfKey = $("#pf_key").val();
// 		if (pfKey && pfKey != '') {
// 			$(".content").html("임시저장 메세지가 있습니다.<br/>사용 하시겠습니까?");
// 			$("#myModalCheck").modal({backdrop: 'static'});
// 			$("#myModalCheck").unbind("keyup").keyup(function (e) {
// 				var code = e.which;
// 				if (code == 13) {
// 					setTemporalMessages();
// 				}
// 			});
// 			$(".submit").unbind("click").click(function () {
// 				setTemporalMessages();
// 				$(".submit").unbind("click");
// 			});
// 		}
// 	}

// 	function setTemporalMessages() {
// 		$("#myModalCheck").modal("hide");

// 		var pfKey = $("#pf_key").val();
// 		console.log('setTemporalMessages', pfKey);
// 		$.ajax({
// 			url: "/biz/sender/get_temp_msg",
// 			type: "POST",
// 			data: {'key': pfKey},
// 			success: function(resp){
// 				var json = JSON.parse(resp);
// 				var fields = json[0].fields;
// 				$('#lms').val(fields.lms);
// 				$('#templi_cont').val(fields.templi);
// 				if(fields.lms && fields.lms != "") {
// 					$("#text").val(fields.lms);
// 				} else {
// 					$("#text").val(fields.templi);
// 				}
// 				if(fields.buttons && fields.buttons != "") {
// 					$("#btn_content_1").remove();
// 					$("#btn_content_1_1").remove();
// 					$("#btn_preview_div1").remove();
// 					var btn_content = JSON.parse(fields.buttons);

// 					for(var i=0;i<=btn_content.length;i++) {
// 						var html = "";
// 						var btn_type = btn_content[i]["linkType"];
// 						var btn_name = "";
// 						if (btn_content[i]["name"] != undefined) {
// 							 btn_name = btn_content[i]["name"];
// 						}
// 						var linkMo = btn_content[i]["linkMo"];
// 						var linkPc = btn_content[i]["linkPc"];
// 						var linkAnd = btn_content[i]["linkAnd"];
// 						var linkIos = btn_content[i]["linkIos"];

// 						var counter = i + 1;

// 						//공통
// 						html += "<tr id='btn_content_" + counter + "_1'>";
// 						html += "<td id='no' align='center' rowspan='2'>" + counter + "</td>";
// 						html += "<td id='btn_type_td' name='btn_type_td' align='center' rowspan='2'>";
// 						html += "<select class='select2 input-width-small' id='btn_type"+counter+"' name='btn_type' onchange='modify_btn_type("+counter+");link_name(this,"+counter+");'>";
// 						html += "<option value='N'>선택</option>";
// 						html += "<option value='WL'>웹링크</option>";
// 						html += "<option value='AL'>앱링크</option>";
// 						html += "<option value='BK'>봇키워드</option>";
// 						html += "<option value='MD'>메시지전달</option>";
// 						html += "</select>";
// 						html += "</td>";
// 						html += "<td id='btn_add_msg' align='center' rowspan='2' colspan='5' hidden>버튼을 추가할 수 있습니다.</td>";
// 						//N
// 						html += "<td id='n_1_" + counter+ "' align='center' rowspan='2' hidden></td>";
// 						html += "<td id='n_2_" + counter+ "' align='center' width='67px' hidden></td>";
// 						//봇키워드
// 						html += "<td id='bk_1_" + counter+ "' align='center' rowspan='2' hidden><input name='btn_name4' id='btn_name4_" + counter+ "' maxlength='10' onkeyup='link_name(this," + counter+ ");scroll_prevent();btn_name_chk(this," + counter+ ");' type='text' class='form-control input-width-small inline'></td>";
// 						html += "<td id='bk_2_" + counter+ "' align='center' hidden></td>";
// 						//메시지전달
// 						html += "<td id='md_1_" + counter+ "' align='center' rowspan='2' hidden><input name='btn_name5' id='btn_name5_" + counter+ "' maxlength='10' onkeyup='link_name(this," + counter+ ");scroll_prevent();btn_name_chk(this," + counter+ ");' type='text' class='form-control input-width-small inline'></td>";
// 						html += "<td id='md_2_" + counter+ "' align='center' hidden></td>";
// 						//웹링크
// 						html += "<td id='wl_1_" + counter+ "' align='center' rowspan='2' hidden><input name='btn_name2' id='btn_name2_" + counter+ "' maxlength='10' onkeyup='link_name(this," + counter+ ");scroll_prevent();btn_name_chk(this," + counter+ ");' type='text' class='form-control input-width-small inline'></td>";
// 						html += "<td id='wl_2_" + counter+ "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>Mobile</label><input style='margin-left: 10px;' name='btn_url21' id='wl_2_btn_url_" + counter+ "' maxlength='254' type='text' class='form-control input-width-medium inline'></td>";
// 						//앱링크
// 						html += "<td id='al_1_" + counter+ "' align='center' rowspan='2' hidden><input name='btn_name3' id='btn_name3_" + counter+ "' maxlength='10' onkeyup='link_name(this," + counter+ ");scroll_prevent();btn_name_chk(this," + counter+ ");' type='text' class='form-control input-width-small inline'></td>";
// 						html += "<td id='al_2_" + counter+ "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>Android</label><input style='margin-left: 10px;' name='btn_url31' id='al_2_btn_url_" + counter+ "' maxlength='254' type='text' class='form-control input-width-medium inline'></td>";
// 						html += "<td id='btn_del' align='center' rowspan='2'><a onclick='btn_del(" + counter + ");' style='cursor:pointer'>제거</a></td>";
// 						html += "</tr>";
// 						//웹&앱링크&공통
// 						html += "<tr id='btn_content_" + counter + "' name='" + counter + "'>";
// 						html += "<td id='wl_3_" + counter+ "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>PC(선택)</label><input style='margin-left: 10px;' name='btn_url22' id='wl_3_btn_url_" + counter+ "' maxlength='254' type='text' class='form-control input-width-medium inline'></td>";
// 						html += "<td id='al_3_" + counter+ "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>iOS</label><input style='margin-left: 10px;' name='btn_url32' id='al_3_btn_url_" + counter+ "' maxlength='254' type='text' class='form-control input-width-medium inline'></td>";
// 						html += "</tr>";

// 						$("#btn_content_" + i).after(html);

// 						$("#btn_type"+counter).val(btn_type);
// 						if (btn_content[i]["linkType"] == "WL") {
// 							$("#wl_1_"+counter).attr("hidden",false);
// 							$("#wl_2_"+counter).attr("hidden",false);
// 							$("#wl_3_"+counter).attr("hidden",false);
// 							$("#btn_name2_"+counter).val(btn_name);
// 							$("#wl_2_btn_url_"+counter).val(linkMo);
// 							$("#wl_3_btn_url_"+counter).val(linkPc);
// 						} else if (btn_content[i]["linkType"] == "AL") {
// 							$("#al_1_"+counter).attr("hidden",false);
// 							$("#al_2_"+counter).attr("hidden",false);
// 							$("#al_3_"+counter).attr("hidden",false);
// 							$("#btn_name3_"+counter).val(btn_name);
// 							$("#al_2_btn_url_"+counter).val(linkAnd);
// 							$("#al_3_btn_url_"+counter).val(linkIos);
// 							$("#wl_2_btn_url_"+counter).val("http://");
// 						} else if (btn_content[i]["linkType"] == "BK") {
// 							$("#bk_1_"+counter).attr("hidden",false);
// 							$("#bk_2_"+counter).attr("hidden",false);
// 							$("#btn_name4_"+counter).val(btn_name);
// 							$("#wl_2_btn_url_"+counter).val("http://");
// 						} else if (btn_content[i]["linkType"] == "MD") {
// 							$("#md_1_"+counter).attr("hidden",false);
// 							$("#md_2_"+counter).attr("hidden",false);
// 							$("#btn_name5_"+counter).val(btn_name);
// 							$("#wl_2_btn_url_"+counter).val("http://");
// 						}
// 						$('select.select2').select2();
// 						//$("#text").css("margin-bottom","10px");
// 						if(btn_name.replace(/ /gi, "") == ""){
// 							btn_name = '버튼명을 입력해주세요.';
// 						}
// 						var html = '<div id="btn_preview_div' + counter + '" class="' + counter + '" style="height: 200px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">' +
// 							'<p data-always-visible="1" id="btn_preview' + counter + '" data-rail-visible="0" cols="20" readonly="readonly" ' +
// 							'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"' +
// 							'>' + btn_name + '</p></div>';
// 						if (counter == 1) {
// 							$("#text").after(html);
// 						} else {
// 							var no = counter-1;
// 							$("#btn_preview_div" + no).after(html);
// 						}
// 					}
// 				}
// 				if(fields.lms && fields.lms != "") {
// 					$("#lms_select").prop("checked",true);
// 					$('.uniform').uniform();
// 					$("#lms_kakaolink_select").attr("disabled",false);
// 					$("#lms_kakaolink_select").uniform();
// 					$("#lms").attr("disabled",false);
// 					$("#lms").val(fields.lms);
// 					chkword_lms();
// 				}
// 				if(fields.img_link && fields.img_link != "") {
// 					$('#img_link').val(fields.img_link);
// 				}
// 				select_lms(document.getElementById('lms_select'));
// 				$('.uniform').uniform();
// 			},
// 			error: function (data, status, er) {
// 				$("#myModalCheck").modal("hide");
// 				$(".content").html("처리중 오류가 발생하였습니다.");
// 				$("#myModal").modal('show');
// 			}
// 		});
// 	}

	// 메시지 임시저장
// 	function saveTemporalMsgs() {
// 		var msgs = {};

// 		var pfKey = $("#pf_key").val();
// 		var lms = $('#lms').val();
// 		var templi = $('#templi_cont').val();

// 		var buttons = new Array();
// 		var btnInfo = "";
// 		var ordering = 1;
// 		$("#friendtalk_table tr").each(function () {
// 			var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
// 			if (btn_type != undefined) {
// 				if (btn_type == "WL") {
// 					btnInfo = new Object();
// 					btnInfo.ordering = ordering;
// 					btnInfo.linkType = btn_type;
// 					btnInfo.name = $(this).find(document.getElementsByName("btn_name2")).val().trim();
// 					btnInfo.linkMo = $(this).find(document.getElementsByName("btn_url21")).val().trim();
// 					btnInfo.linkPc = $(this).next('tr').find(document.getElementsByName("btn_url22")).val().trim();
// 					buttons.push(btnInfo);
// 					ordering++;
// 				} else if (btn_type == "AL") {
// 					btnInfo = new Object();
// 					btnInfo.ordering = ordering;
// 					btnInfo.linkType = btn_type;
// 					btnInfo.name = $(this).find(document.getElementsByName("btn_name3")).val().trim();
// 					btnInfo.linkAnd = $(this).find(document.getElementsByName("btn_url31")).val().trim();
// 					btnInfo.linkIos = $(this).next('tr').find(document.getElementsByName("btn_url32")).val().trim();
// 					buttons.push(btnInfo);
// 					ordering++;
// 				} else if (btn_type == "BK") {
// 					btnInfo = new Object();
// 					btnInfo.ordering = ordering;
// 					btnInfo.linkType = btn_type;
// 					btnInfo.name = $(this).find(document.getElementsByName("btn_name4")).val().trim();
// 					buttons.push(btnInfo);
// 					ordering++;
// 				} else if (btn_type == "MD") {
// 					btnInfo = new Object();
// 					btnInfo.ordering = ordering;
// 					btnInfo.linkType = btn_type;
// 					btnInfo.name = $(this).find(document.getElementsByName("btn_name5")).val().trim();
// 					buttons.push(btnInfo);
// 					ordering++;
// 				}
// 			}
// 		});
//       buttons = JSON.stringify(buttons);

// 		var img_url = $('#img_url').val();
// 		var img_link = $('#img_link').val();

// 		//if (pfKey && pfKey != '') {
// 		//	msgs['key'] = pfKey;
// 		//} else {
// 		//	$(".content").html("프로필을 먼저 선택해주세요.");
// 		//	$('#myModal').modal({backdrop: 'static'});
// 		//	return;
// 		//}

// 		if (lms && lms != '') {
// 			msgs['lms'] = lms;
// 		}
// 		if (templi && templi != '') {
// 			msgs['templi'] = templi;
// 		}
// 		if (buttons && buttons != '') {
// 			msgs['buttons'] = buttons;
// 		}
// 		if (img_url && img_url != '') {
// 			msgs['img_url'] = img_url;
// 		}
// 		if (img_link && img_link != '') {
// 			msgs['img_link'] = img_link;
// 		}

// 		$.ajax({
// 			url: "/biz/sender/save_temp_msg",
// 			type: "POST",
// 			data: msgs,
// 			success: function(){
// 				$(".content").html("메세지가 저장되었습니다.");
// 				$("#myModal").modal('show');
// 			},
// 			error: function (data, status, er) {
// 				$(".content").html("처리중 오류가 발생하였습니다.");
// 				$("#myModal").modal('show');
// 			}
// 		});
// 	}
	</script>
