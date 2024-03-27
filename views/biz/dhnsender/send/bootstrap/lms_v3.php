<?
	$mem_purigo_yn = $this->member->item("mem_purigo_yn"); //Purigo 회원여부
	$dhnl_url = str_replace("http://","",$dhnl_url); //에이터 URL
	$base_lms = ""; //문자 발송내용 기본 메시지
	if($mem_purigo_yn == "Y"){
		$base_lms = "\n\n세일문자 클릭 -> ". $dhnl_url;
	}
?>
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<script type="text/javascript" src="/js/plugins/moment-with-locales.js"></script>
<script type="text/javascript" src="/js/plugins/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
<!--
	var edit_control = "templi_cont";
    var lmsflag = "sms";

    var sendFlag = false;

    function eaSendBtn(){
        $('.btn_send').prop('disabled', false);
        console.log($('.btn_send').prop('disabled'));
    }
//-->
</script>
<input type="hidden" id="navigateURL" value="" />
<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu1.php');
?>
<!--form action="/dhnbiz/sender/Lms/all" method="post" id="sendForm" name="sendForm"-->
<!-- 컨텐츠 전체 영역 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>문자메시지 작성하기</h3>
			<span class="fr btn_guide"><a href="/manual/lms"><span class="material-icons">pageview</span> 동영상 가이드 바로가기</a></span>
		</div>
		<div class="inner_content preview_info">
			<input type="hidden" id="pf_ynm" value="<? if ($rs->spf_company) { echo $rs->spf_company; } else { echo $po->mem_username; } ?>"/>
			<input type="hidden" id="pf_yid" value="<?=$rs->spf_friend?>"/>
			<input type="hidden" id="sms_sender" value="<? if(empty($rs->spf_sms_callback)) {echo $po->mem_phone;} else {echo $rs->spf_sms_callback;} ?>"/>
			<input type="hidden" id="max_msg_byte" value="2000"/>
			<input type="hidden" id="smslms_kind_r" value="N"/>
			<input type="hidden" name="pf_key" id="pf_key" value="<?=$rs->spf_key?>">
			<input type="hidden" name="kind" id="kind" value="<?=$rs->spf_key_type?>">
			<input type="hidden" name="biz_url" id="biz_url"  value="<?=$short_url?>" style="width:100px;">
			<input type="hidden" name="dhnl_url" id="dhnl_url"  value="<?=$dhnl_url?>" style="width:160px;">
			<input type="hidden" name="psd_code" id="psd_code"  value="<?=$psd_code?>" style="width:100px;">
			<input type="hidden" name="psd_url" id="psd_url"  value="<?=$psd_url?>" style="width:160px;">
			<input type="hidden" name="pcd_code" id="pcd_code"  value="<?=$pcd_code?>" style="width:100px;">
			<input type="hidden" name="pcd_type" id="pcd_type"  value="<?=$pcd_type?>" style="width:100px;">
			<input type="hidden" name="pcd_url" id="pcd_url"  value="<?=$pcd_url?>" style="width:160px;">
            <input type="hidden" name="hdn_isdhn" id="hdn_isdhn"  value="<?=$isdhn?>" style="width:160px;">
			<div class="input_content_wrap">
				<label class="input_tit">업체명</label>
				<div class="input_content">
					<?//=($rs->spf_key) ? $rs->spf_company."(".$rs->spf_friend.")" : $po->mem_username ?>
					<?=($rs->spf_key) ? $rs->spf_company."(".$this->funn->format_phone($rs->spf_sms_callback,"-").")" : $po->mem_username."(".$this->funn->format_phone($po->mem_phone,"-").")" ?>
					<!-- 메시지 미리보기 -->
					<div class="sms_preview">
						<div class="msg_type <?=($isdhn=="N")? "sms" : ""?>"></div>
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
				<label class="input_tit" id="id_STEP1">
					<p class="txt_st_eng">STEP 1.</p>
					<p>발송내용 입력</p>
				</label>
				<div class="input_content">
					<div class="msg_box">
						<?php // 문자 내용 헤드, 풋 수정 2019-07-25 ?>
						<!--div class="txt_ad" id="lms_header"></div-->
						<div class="txt_ad" id="lms_header">
							<span id="span_adv">(광고)</span><input type="text" class="input_option" style="width: 320px;padding-left: 0px !important;" id="companyName" onkeyup="onPreviewText(); chkword_lms();" value="<? if ($rs->spf_company) { echo $rs->spf_company; } else { echo $po->mem_username; } ?>"<?=($mem_purigo_yn == "Y") ? " Readonly" : ""?>>
						</div>
						<textarea class="input_msg" id="lms" name="lms" placeholder="메세지를 입력해주세요." onkeyup="onPreviewText();chkword_lms();"<?=($mem_purigo_yn == "Y") ? " Readonly" : ""?>><?=$base_lms?></textarea>
						<span class="txt_byte"><span id="info_desc"><span class="info_desc">90bytes가 넘어가면 장문 문자로 변경됩니다.</span></span><span class="msg_type_txt sms" id="lms_limit_str"></span><span id="lms_num">0</span>/<span id="lms_character_limit">90</span> bytes</span>
						<!--div class="txt_ad" id="lms_footer"></div-->
						<div class="txt_ad" id="lms_footer">
							<p id="kakotalk_link_text"></p><span id="span_unsubscribe"><?=($mem_purigo_yn == "Y") ? "무료거부" : "무료수신거부"?> : </span><input type="text" class="input_option" style="padding-left: 0px !important;" id="unsubscribe_num" onkeyup="onPreviewText(); chkword_lms();"<?=($mem_purigo_yn == "Y") ? " Readonly" : ""?>>
						</div>
					</div>
					<div style="display:<?=($mem_purigo_yn == "Y") ? "none" : ""?>;">
						<input type="checkbox" id="advertising_add" class="input_cb"  onclick="insert_advertising(this);" checked><label for="advertising_add">(광고) 삽입</label>&nbsp;&nbsp;
						<!--<input type="checkbox" id="lms_kakaolink_select" class="lms_kakaolink_select" onclick="insert_kakao_link(this);"><label for="link_add">친구추가 링크 삽입</label>-->
						<button class="btn md fr btn_emoji">특수문자</button>
					</div>
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
			<div class="input_content_wrap" id="id_STEP2">
				<label class="input_tit">
			      <p class="txt_st_eng">STEP 2.</p>
			      <p><?=($mem_purigo_yn != "Y") ? "버튼내용 선택" : "URL 메시지 편집"?></p>
				</label>
			<div class="input_content">
				<!-- 스마트전단, 에디터사용 공통 -->
				<? $leaflet_link_type = "lms"; //전단지 타입(at.알림톡, lms.문자) ?>
				<? if($add == "_test"){ ?>
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/dhnsender/send/bootstrap/_inc_leaflet_test.php'); ?>
				<? }else{ ?>
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/dhnsender/send/bootstrap/_inc_leaflet.php'); ?>
				<? } ?>
			</div>
			</div>
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
						//$('#myModal').modal({backdrop: 'static'});
						modal_open('myModal'); return false;
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
			<input type="hidden" name="mem_2nd_send" id="mem_2nd_send" value="<?=$mem->mem_2nd_send ?>" />
			<input type="hidden" name="smslms_kind" id="smslms_kind" />
			<div class="input_content_wrap" style="display: <? if($isManager != 'Y*') {?>none<? } ?>">
				<label class="input_tit">발송방법</label>
				<div class="input_content">
					<select>
						<option disabled="disabled" <?=($mem->mem_2nd_send=="GREEN_SHOT") ? "selected" : ""?>>인터넷문자 WEB(A)</option>
						<option disabled="disabled" <?=($mem->mem_2nd_send=="NASELF") ? "selected" : ""?>>일반문자 WEB(B)</option>
						<option disabled="disabled" <?=($mem->mem_2nd_send=="SMART") ? "selected" : ""?>>일반문자 WEB(C)</option>
					</select>
				</div>
			</div><!-- input_content_wrap END -->
			<? if(!$fail_mst_id) { echo $fail_mst_id; ?>
			<div class="input_content_wrap">
				<label class="input_tit">
					<p class="txt_st_eng">STEP 3.</p>
					<p>수신고객 선택</p>
				</label>
				<div id="reception_list_parent" class="input_content">
					<div id="reception_list" class="checks">
						<?php // 2019-07-09 친구여부 추가및 명칭변경 ?>
						<!--input type="radio" name="customer" value="upload" id="switch_upload" checked /><label for="switch_upload">파일업로드로 전송</label>
						<input type="radio" name="customer" value="db" id="switch_select" /><label for="switch_select" style="margin-left: -1px;">그룹별 선택</label>
						<input type="radio" name="customer" value="private" id="switch_customer" /><label for="switch_customer" style="margin-left: -1px;">연락처 직접 입력</label>
						<input type="radio" name="customer" value="db" id="switch_select" checked /><label for="switch_select">그룹별 선택</label>-->
						<? $mem_bigpos_yn = $this->member->item("mem_bigpos_yn"); //빅포스 연동 2021-02-03 ?>
						<? //$mem_bigpos_yn = "N"; //빅포스 연동 ?>
                    <? if (empty($fail_list)){?>
						<? if($mem_bigpos_yn == "Y"){ //빅포스 연동 2021-02-03 ?>
							<input type="radio" name="customer" value="pos" id="switch_pos" checked><label for="switch_pos">포스고객</label>
							<input type="radio" name="customer" value="db" id="switch_select"><label for="switch_select">그룹선택</label>
						<? }else{ ?>
							<input type="radio" name="customer" value="db" id="switch_select" checked><label for="switch_select">그룹별 선택</label>
						<? } ?>
						<input type="radio" name="customer" value="upload" id="switch_upload" /><label for="switch_upload">파일업로드</label>
                    <? } else {
                        $fail_flag = true;
                        echo "<input type='hidden' id='fail_flag' value='" . $mid . "'>";
                        echo "<input type='hidden' id='fail_mtype' value='" . $mtype . "'>";
                        echo "<input type='hidden' id='fail_uid' value='" . $uid . "'>";
                        }
                    ?>
						<input type="radio" name="customer" value="private" id="switch_customer" /><label for="switch_customer">연락처 입력</label>
						<!--<input type="radio" name="customer" value="fr" id="switch_friend" /><label for="switch_friend">플친여부</label>-->
						<?php // 친구여부 추가및 명칭변경 끝 ?>
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
									총 발송 <span class="num" id="upload_tel_count">0</span>건
								</div>
							</div>
						</div>
						<div id="db">
							<div class="msg_box form_check">
								<div class="send_list h200" style="display:none;">
									<ul>
									<li class="check_all"><input type="checkbox" name="checkAll" id="all"><label for="all">전체 선택</label></li>
									<?
										$kindCount = 1;
										foreach($kind as $r) {
											//log_message("ERROR", "r->ab_kind : ".$r->ab_kind." r->ab_kind_cnt : ".$r->ab_kind_cnt);
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

								// $("#groupSendTotal").text(groupSendTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")); //총 발송 건수 표기 (콤마 추가)
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
							function oneCheckFunc( obj ){
								var allObj = $("[name=checkAll]");
								var objName = $(obj).attr("name");
								if( $(obj).prop("checked") ){
									checkBoxLength = $("[name="+ objName +"]").length;
									checkedLength = $("[name="+ objName +"]:checked").length;
									if( checkBoxLength == checkedLength ) {
										allObj.prop("checked", true);
									} else {
										allObj.prop("checked", false);
									}
								}else	{
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
										<li><input type="radio" name="friend_yn" value="FT" id="switch_FT" / ><label for="switch_FT" style="margin-left: -1px;">플러스 친구<span class="send_amount">(<?=$ftCount ?>명)</span></label></li>
										<li><input type="radio" name="friend_yn" value="NFT" id="switch_NFT" checked /><label for="switch_NFT" style="margin-left: -1px;">플러스 친구 아님<span class="send_amount">(<?=$nftCount ?>명)</span></label></li>
									</ul>
								</div>
								<div class="bottom tr">
									총 발송 <span class="num" id="friend_yn_tel_count"><?=$nftCount ?></span>건
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
								<div class='input-group date fl' id='datetimepicker' style="width:135px;">
									<input type='text' class="form-control" id="reserve">
									<span class="input-group-addon"><span class="material-icons" style="font-size:18px;cursor:pointer;">date_range</span></span>
								</div>
								<select style="width: 75px; margin-left:5px;" id="reserve_hours">
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
								<select style="width: 75px;" id="reserve_minutes">
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
									<input type="text" id="test_add-todo-input" name="phoneNum" maxlength="13" style="width:100%; margin-right: -2px;" placeholder="연락처를 입력해 주세요">
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
					<p class="noted">* 전화번호 입력 후 번호추가 버튼을 클릭하셔야 합니다.</p>
				</div>
			</div>
		</div><!-- inner_content END -->
	</div><!-- form_section END -->
	<div class="btn_send_cen">
		<!-- button class="btn lg">취소</button-->
		<button class="btn_send" onclick="all_send();">메시지발송</button>
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

	<div class="dh_modal" id="sendbox_select">
		<div class="modal-dialog" id="modal">
			<div class="modal-content">
				<span id="close_sendbox_select" class="dh_close">&times;</span>
				<h4 class="modal-title">내 문자함 (친구톡)</h4>
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
							 </select>&nbsp;
							 <input type="text" class="form-control input-width-medium inline"
									  id="searchFor_sendbox" name="search" placeholder="검색어 입력" value=""/>&nbsp;
							 <input type="button" class="btn btn-default" id="check" value="조회" onclick="location.href='javascript:search_sendbox();'"/>
						 </div>
					</div>
					<div class="widget-content" id="sendbox_list">
					</div>
					<div class="modal_bottom">
						<button type="button" class="btn md cancel btn-default" onclick="modal_close('dh_modal');">취소</button>
						<button type="button" class="btn md modal_confirm btn-primary" id="code" name="code" onclick="select_sendbox();">확인</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--프로필 선택-->
    <div class="dh_modal" id="profile_danger">
        <div class="modal-dialog" id="modal">
            <div class="modal-content">
	            <span id="close_profile_danger" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content">
                        <div class="row align-center" id="danger">
                            <p class="alert alert-danger">프로필이 없습니다. 프로필을 등록해 주세요.</p>
                        </div>
                    </div>
                    <div class="modal_bottom">
						<button type="button" class="btn md cancel btn-default">닫기</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="profile_select">
        <div class="modal-dialog" id="modal">
            <div class="modal-content">
	            <span id="close_profile_select" class="dh_close">&times;</span>
                <h4 class="modal-title">발신프로필 선택하기</h4>
                <div class="modal-new">
                    <div class="mb20 mt10 clear">
                        <select class="select2 input-width-medium" id="searchType2" onchange="getSelectValue(this.form);">
                            <option value="all">검색항목</option>
                            <option value="pf_ynm">업체명</option>
                            <option value="pf_key">프로필 키</option>
                        </select>
                        <input type="text" class="form-control input-width-medium inline"
                               id="searchStr2" name="search" placeholder="검색어 입력" value=""/>
                        <input type="button" class="btn btn-default" id="check" value="조회" onclick="location.href='javascript:search_profile();'"/>
                    </div>
                    <div class="widget-content" id="profile_list"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md cancel btn-default" onclick="modal_close('profile_select');">취소</button>
                        <button type="button" class="btn md modal_confirm btn-primary" id="code" name="code" onclick="select_profile();">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModal">
        <div class="modal-dialog" id="modal">
            <div class="modal-content">
	            <span id="close_myModal" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content identify"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-primary enter" onClick="modal_close('myModal');">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModalCheck">
        <div class="modal-dialog" id="modalCheck">
            <div class="modal-content">
	            <span id="close_myModalCheck" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-default dismiss" onclick="modal_close('myModalCheck');">취소</button>
                        <button type="button" class="btn md btn-primary submit" onClick="modal_close('myModal');">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModalSelect">
        <div class="modal-dialog" id="modalCheck">
            <div class="modal-content">
	            <span id="close_myModalSelect" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-default" onclick="modal_close('myModalSelect');">취소</button>
                        <button type="button" class="btn md btn-primary select-send" onclick="modal_close('myModalSelect');select_move();">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModalAll">
        <div class="modal-dialog" id="modalCheck">
            <div class="modal-content">
	            <span id="close_myModalAll" class="dh_close">&times;</span>
				<div class="modal-tit">발송</div>
                <div class="modal-new">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md cancel" onclick="modal_close('myModalAll');eaSendBtn();">취소</button>
                        <button type="button" class="btn md all" onclick="modal_close('myModalAll');all_move();">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModalTest"><?//테스트 발송 모달?>
        <div class="modal-dialog" id="modalCheck">
            <div class="modal-content">
	            <span id="close_myModalTest" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md cancel" onclick="modal_close('myModalTest');">취소</button>
                        <button type="button" class="btn md btn-primary test-send" onclick="modal_close('myModalTest');test_move();">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModalDel">
        <div class="modal-dialog" id="modalCheck">
            <div class="modal-content">
	            <span id="close_myModalDel" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-default" onclick="modal_close('myModalDel');">취소</button>
                        <button type="button" class="btn md btn-primary del">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModalSelDel">
        <div class="modal-dialog" id="modalCheck">
            <div class="modal-content">
	            <span id="close_myModalSelDel" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-default" onclick="modal_close('myModalSelDel');">취소</button>
                        <button type="button" class="btn md btn-primary selDel">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModalTemp">
        <div class="modal-dialog" id="modalCheck">
            <div class="modal-content">
	            <span id="close_myModalTemp" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-default" onclick="modal_close('myModalTemp');">취소</button>
                        <button type="button" class="btn md btn-primary temp">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModalUpload">
        <div class="modal-dialog" id="modalCheck">
            <div class="modal-content">
	            <span id="close_myModalUpload" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-default" onclick="modal_close('myModalUpload');">취소</button>
                        <button type="button" class="btn md btn-primary up">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModalDownload">
        <div class="modal-dialog" id="modalCheck">
            <div class="modal-content">
	            <span id="close_myModalDownload" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-default" onclick="modal_close('myModalDownload');">취소</button>
                        <button type="button" class="btn md btn-primary down">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dh_modal" id="myModalFilterAll">
        <div class="modal-dialog modal-lg" id="modalCheck">
            <div class="modal-content">
	            <span id="close_myModalFilterAll" class="dh_close">&times;</span>
                <div class="modal-new">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-default">선택추가</button>
                        <button type="button" class="btn md btn-primary filter_all">전체추가</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dh_modal" id="myModalUserMSGList">
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
                        <button type="button" class="btn md btn-primary include_phns modal_del" name="code" onclick="modal_close('myModalUserMSGList');delete_msg()">선택 삭제</button>
                        <button type="button" class="btn md btn-default cancel dismiss" onclick="modal_close('myModalUserMSGList');">취소</button>
                        <button type="button" class="btn md btn-primary include_phns" name="code" onclick="modal_close('myModalUserMSGList');include_msg()">선택 추가</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dh_modal" id="myModalLoadCustomers">
        <div class="modal-dialog modal-lg select-dialog">
            <div class="modal-content">
                <h4 class="modal-title" align="center">고객정보 가져오기</h4>
                <div class="modal-new select-body" style="height: 600px;">
                    <div class="mb20 mt10">
                        <div style="padding-bottom:20px;">
                            <select class="select2 input-width-medium" id="searchType">
                                <option value="all">전체</option>
                                <option value="normal">정상</option>
                                <option value="reject">수신거부</option>
                            </select>&nbsp;
                            <input type="text" class="form-control input-width-medium inline" id="searchStr" name="searchStr" placeholder="전화번호 입력" value=""/>고객구분
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
										<?if($fail_mst_id) {?>
											set_lms_by_user_msg(<?=$fail_mst_id?>, "P");
										<? } ?>

										<? if($sendtelauth == 0) { ?>
										$(".content").html("문자 발송을 위해 발신번호(<? if(empty($rs->spf_sms_callback)) {echo $po->mem_phone;} else {echo $rs->spf_sms_callback;} ?>)를 <BR>사전에 등록 하세요.");
										//$('#myModal').modal({backdrop: 'static'});
										modal_open('myModal'); return false;
										<? } ?>
									});
									</script>
                        </div>
                    </div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-default dismiss" onclick="modal_close('myModalLoadCustomers');">취소</button>
                        <button type="button" class="btn md btn-primary include_phns" name="code" onclick="modal_close('myModalLoadCustomers');include_customer()">선택 추가</button>
                        <button type="button" class="btn md btn-primary include_phns" name="code" onclick="modal_close('myModalLoadCustomers');include_customer('GS')">구분 전체추가</button>
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
	.scrolltbody th {
		text-align: center
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

	$("#myModal").unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			$(".enter").click();
		}
	});

	$(document).ready(function() {

		var phn = "080-888-7985";
		<?if( $mem->mem_2nd_send=='015') { ?>
			phn = '080-888-7985'; //'080-1111-1111';
		<?} else if( $mem->mem_2nd_send=='NASELF') { ?>
			phn = '080-888-7985'; //'080-855-5947';
	    <?} else if($mem->mem_2nd_send=='GREEN_SHOT') { ?>
			phn = '080-888-7985'; //'080-156-0186';
		<?} else if($mem->mem_2nd_send=='SMART') { ?>
			phn = '080-888-7985';
		<?} else if($mem->mem_2nd_send=='PHONE' || $mem->mem_2nd_send=='SMT_PHN') { ?>
			phn = '080-888-7985'; //'080-889-8383';
		<?}
		if($this->member->item("mem_id") == 228 || $this->member->item("mem_id") == 93 || $this->member->item("mem_id") == 519
		    || $this->member->item("mem_id") == 445 || $this->member->item("mem_id") == 446 || $this->member->item("mem_id") == 447
		    || $this->member->item("mem_id") == 448 || $this->member->item("mem_id") == 484 ||$this->member->item("mem_id") == 572) {

        ?>
		phn = '080-053-7590';
		<? }?>
		<? if($mem_purigo_yn == "Y"){ //Purigo 회원 ?>
		phn = phn.replace(/[^0-9]/g,""); //숫자만 추출
		<? } ?>

        $("#unsubscribe_num").val(phn);

		onPreviewText();
		chkword_lms();

        <?if((date('H') < '08' || date('H') > '21') && $mem->mem_sms_time_clear_yn == "N") {?>
		//$(".content").html("저녁8시~아침8시 까지는 문자 발송이 차단<?=($mem->mem_2nd_send) ? '되어  일반문자 로만 전송됩니다.' : ' 됩니다.'?>");
		$(".content").html("저녁9시 ~ 아침8시 까지는 문자 발송이 차단 됩니다.");
		//$('#myModal').modal({backdrop: 'static'});
		//$('#myModal').on('hidden.bs.modal', function () {
		//	location.href='/dhnbiz/sender/send/message';
		//});
		modal_open('myModal'); return false;
        <?}?>
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

	$("#linkView").click(function() {
		$(this).css("box-shadow","none");
	});
	$(function() {
		$("#templi_cont.autosize").keyup(function () {
		});
	});

    function click_bntimage(no) {
        $("#image_file"+no).trigger("click");

    }

	// 미리보기 2차발신 우선
	function onPreviewText() {
		<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
		//var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		var lms_header_temp = $("#span_adv").text() + $("#companyName").val();
		var lms_footer_temp = "";
		if($("#kakotalk_link_text").text() == "") {
			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		} else {
			lms_footer_temp = $("#kakotalk_link_text").text() +"\n"+ $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		}
		//var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

		var link_type = $("#link_type").val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
		var dhnl_url = $("#dhnl_url").val(); //에디터 URL
		var psd_url = $("#psd_url").val(); //스마트전단 URL
		var pcd_url = $("#pcd_url").val(); //스마트쿠폰 URL
		//alert("link_type : "+ link_type +"\n"+ "dhnl_url : "+ dhnl_url +"\n"+ "psd_url : "+ psd_url);
		if(link_type == "editor" && dhnl_url != ""){ //에디터 & 에디터 URL 있는 경우
			<? if($mem_purigo_yn == "Y"){ //Purigo 회원인 경우 ?>
				lms_footer_temp = lms_footer_temp;
			<? }else{ ?>
				lms_footer_temp = "<?=$leaflet_link_name?>\n"+ dhnl_url +"\n\n"+ lms_footer_temp;
			<? } ?>
		}else if(link_type == "smart" && psd_url != ""){ //스마트전단 & 스마트전단 URL 있는 경우
			lms_footer_temp = "<?=$leaflet_link_name?>\n"+ psd_url +"\n\n"+ lms_footer_temp;
		}else if(link_type == "coupon" && pcd_url != ""){ //스마트쿠폰 & 스마트쿠폰 URL 있는 경우
			lms_footer_temp = "<?=$coupon_link_name?>\n"+ pcd_url +"\n\n"+ lms_footer_temp;
		}

		var msg_text = "";
		if ($("#lms").val().replace(/\n/g, "").length > 0) {
			msg_text = msg_text + lms_header_temp + "\n" + $("#lms").val() + "\n\n" + lms_footer_temp;
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
	}

	<?/*---------------------------*
		| 카카오 친구추가 링크 넣기 |
		*---------------------------*/?>
	function insert_kakao_link(check){
		var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');
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
				<? // 문자 풋부분 카카오톡 링크 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
				if (check.checked) {
					$("#kakotalk_link_text").html(short_url);
				} else {
					$("#kakotalk_link_text").html("");
				}
				chkword_lms();
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
		<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
		if (check.checked) {
			$("#span_adv").text("(광고)");
		} else {
			$("#span_adv").text("");
		}
		chkword_lms();
		onPreviewText();
	}

	//발신번호 숫자만 입력 가능
	function SetNum(obj) {
		var val = obj.value;
		var re = /[^0-9]/gi;
		obj.value = val.replace(re, "");
	}

	//모달 검색
	function search() {
		var search = $("#search").val();
		var selectBox = $("#selectBox option:selected").val();
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/dhnbiz/sender/send/profile6");
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
			"/dhnbiz/sender/send/profile1",
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
		form.setAttribute("action", "/dhnbiz/sender/send/profile2");
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
		//$('#myModal').modal({backdrop: 'static'});
		//$('#filecount').filestyle('clear');
		modal_open('myModal'); return false;
	}


<?/*----------------------------------*
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	| 이부분에 단가를 넣어줘야 합니다. |
	*----------------------------------*/?>
	var ms = '<?=($mem->mem_2nd_send) ? substr(strtoupper($mem->mem_2nd_send), 0, 1) : ""?>';
	var ft_price = '<?=$this->Biz_dhn_model->price_ft?>';
	var ft_img_price = '<?=$this->Biz_dhn_model->price_ft_img?>';
	var sms_price = '<?=$this->Biz_dhn_model->price_sms?>';
	var lms_price = '<?=$this->Biz_dhn_model->price_lms?>';
	var mms_price = '<?=$this->Biz_dhn_model->price_mms?>';
	var phn_price = '<?=$this->Biz_dhn_model->price_phn?>';
    <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
        ft_price = '<?=$this->Biz_dhn_model->price_v_ft ?>';
        ft_img_price = '<?=$this->Biz_dhn_model->price_v_ft_img ?>';
    	sms_price = '<?=$this->Biz_dhn_model->price_v_smt_sms?>';
    	lms_price = '<?=$this->Biz_dhn_model->price_v_smt?>';
    	mms_price = '<?=$this->Biz_dhn_model->price_v_smt_mms?>';
    <? } ?>
<?if($mem->mem_2nd_send=='phn') { echo "var resend_price = '".$this->Biz_dhn_model->price_phn."';"; }
	else if($mem->mem_2nd_send=='sms') { echo "var resend_price = '".$this->Biz_dhn_model->price_sms."';"; }
	else if($mem->mem_2nd_send=='lms') { echo "var resend_price = '".$this->Biz_dhn_model->price_lms."';"; }
	else if($mem->mem_2nd_send=='mms') { echo "var resend_price = '".$this->Biz_dhn_model->price_mms."';"; }
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
		var userfile = ($('#userfile1').val() == undefined || $('#userfile1').val() == "") ? "" : $('#userfile1').val();
		var smslmskind = $('#smslms_kind').val();
		$('#smslms_kind_r').val(smslmskind);
		send_price = 0;

		//alert("send_method : " + send_method);
		//alert("userfile : " + userfile);
		//alert("smslmskind : " + smslmskind);

		switch(send_method) {
		case '015' :
			send_price = <? if($this->Biz_dhn_model->price_015 > 0) { echo $this->Biz_dhn_model->price_015 ; } else { echo 0; } ?>;
			break;
		case 'PHONE' :
		case 'SMT_PHN' :
			send_price = <? if($this->Biz_dhn_model->price_phn > 0) { echo $this->Biz_dhn_model->price_phn ; } else { echo 0; } ?>;
			break;
		case 'GREEN_SHOT' :
			if($('#smslms_kind').val() == 'SMS') {
				send_price = <? if($this->Biz_dhn_model->price_grs_sms > 0) { echo $this->Biz_dhn_model->price_grs_sms ; } else { echo 0; } ?>;
			} else {
				if(userfile != "") {
					send_price = <? if($this->Biz_dhn_model->price_grs_mms > 0) { echo $this->Biz_dhn_model->price_grs_mms ; } else { echo 0; } ?>;
					$('#smslms_kind_r').val("MMS");
				} else {
					send_price = <? if($this->Biz_dhn_model->price_grs > 0) { echo $this->Biz_dhn_model->price_grs ; } else { echo 0; } ?>;
				}
			}
			break;
		case 'NASELF' :
			if($('#smslms_kind').val() == 'SMS') {
				send_price = <? if($this->Biz_dhn_model->price_nas_sms > 0) { echo $this->Biz_dhn_model->price_nas_sms ; } else { echo 0; } ?>;
			} else if($('#smslms_kind').val() == 'LMS') {
				if(userfile != "") {
					send_price = <? if($this->Biz_dhn_model->price_nas_mms > 0) { echo $this->Biz_dhn_model->price_nas_mms ; } else { echo 0; } ?>;
					$('#smslms_kind_r').val("MMS");
				} else {
					send_price = <? if($this->Biz_dhn_model->price_nas > 0) { echo $this->Biz_dhn_model->price_nas ; } else { echo 0; } ?>;
				}
			} else if($('#smslms_kind').val() == 'MMS') {
				send_price = <? if($this->Biz_dhn_model->price_nas_mms > 0) { echo $this->Biz_dhn_model->price_nas_mms ; } else { echo 0; } ?>;
			}
			break;
    	case 'SMART' :
            <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
                if($('#smslms_kind').val() == 'SMS') {
                    send_price = <? if($this->Biz_dhn_model->price_v_smt_sms > 0) { echo $this->Biz_dhn_model->price_v_smt_sms ; } else { echo 0; } ?>;
                } else if($('#smslms_kind').val() == 'LMS') {
                    if(userfile != "") {
                        send_price = <? if($this->Biz_dhn_model->price_v_smt_mms > 0) { echo $this->Biz_dhn_model->price_v_smt_mms ; } else { echo 0; } ?>;
                        $('#smslms_kind_r').val("MMS");
                    } else {
                        send_price = <? if($this->Biz_dhn_model->price_v_smt > 0) { echo $this->Biz_dhn_model->price_v_smt ; } else { echo 0; } ?>;
                    }
                } else if($('#smslms_kind').val() == 'MMS') {
                    send_price = <? if($this->Biz_dhn_model->price_v_smt_mms > 0) { echo $this->Biz_dhn_model->price_v_smt_mms ; } else { echo 0; } ?>;
                }
            <? }else{ ?>
                if($('#smslms_kind').val() == 'SMS') {
                    send_price = <? if($this->Biz_dhn_model->price_smt_sms > 0) { echo $this->Biz_dhn_model->price_smt_sms ; } else { echo 0; } ?>;
                } else if($('#smslms_kind').val() == 'LMS') {
                    if(userfile != "") {
                        send_price = <? if($this->Biz_dhn_model->price_smt_mms > 0) { echo $this->Biz_dhn_model->price_smt_mms ; } else { echo 0; } ?>;
                        $('#smslms_kind_r').val("MMS");
                    } else {
                        send_price = <? if($this->Biz_dhn_model->price_smt > 0) { echo $this->Biz_dhn_model->price_smt ; } else { echo 0; } ?>;
                    }
                } else if($('#smslms_kind').val() == 'MMS') {
                    send_price = <? if($this->Biz_dhn_model->price_smt_mms > 0) { echo $this->Biz_dhn_model->price_smt_mms ; } else { echo 0; } ?>;
                }
            <? } ?>

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

	<?/*----------------------------------------------------------------------------------------------------------------*
	   | 전체고객정보 불러오기 하여 전체 발송버튼 클릭 [업로드 발송 포함] 또는 수신목록이 표시된 상태에서 전체발송 클릭 |
	   *----------------------------------------------------------------------------------------------------------------*/?>
	function all_send() {
		chkbyte($("#lms_header"), $("#lms"), $("#lms_footer"), $("#lms_num"));
		var link_type = $("#link_type").val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
		var dhnl_url = $("#dhnl_url").val(); //에디터 URL
		var psd_url = $("#psd_url").val(); //스마트전단 URL
		var pcd_url = $("#pcd_url").val(); //스마트쿠폰 URL
		var summ = $('#summernote').summernote('code'); //에디터내용
		//alert("getByte : "+ getByteLength($("#lms"))); return;
        <? if($sendtelauth == 0) { ?>
        $(".content").html("문자 발송을 위해 발신번호(<? if(empty($rs->spf_sms_callback)) {echo $po->mem_phone;} else {echo $rs->spf_sms_callback;} ?>)를 <BR>사전에 등록 하세요.");
        //$('#myModal').modal({backdrop: 'static'});
        modal_open('myModal'); return false;
        <? } ?>
		if ($('#lms').val().replace(/ /gi,"")==""){
			alert("문자 내용을 입력해주세요.");
			$("#lms").focus();
			return;
		}
		if (getByteLength($("#lms")) > 2000) {
			alert("문자 내용은 최대 2,000bytes (한글 1,000자) 이내로\n입력하여야 합니다.");
			$("#lms").focus();
			return;
		}
		if (link_type == "editor" && (summ == '' || summ == '<p><br></p>' || summ == '<p><span style="font-size: 18px;">﻿</span><br></p>')) { //에디터의 경우
			$('#summernote').summernote('code', '');
			alert("<?=($mem_purigo_yn != "Y") ? "에디터" : "URL 메시지 편집"?> 내용을 입력하세요.");
			window.scroll(0, getOffsetTop(document.getElementById("id_STEP2"))); //STEP 2 영역으로 이동
			return;
		}
		if (link_type == "smart" && psd_url == "") {
			alert("스마트전단을 선택하세요.");
			smart_page('1'); //스마트전단 불러오기 모달창 열기
			return;
		}else if (link_type == "coupon" && pcd_url == "") {
			alert("스마트쿠폰을 선택하세요.");
			coupon_page('1'); //스마트쿠폰 불러오기 모달창 열기
			return;
		}
		<? if(!$fail_mst_id) { ?>
		if ($("#switch_upload").is(":checked") && parseInt($("#upload_tel_count").text()) == 0) { // upload 발송
			alert("수신 전화번호 엑셀파일 업로드하세요."); return;
		}
		if ($("#switch_select").is(":checked")) { // 고객 DB에서 그룹 선택
			//alert("groupId : "+ $("#groupId").val());
			var groupSendTotal = $("#groupSendTotal").text().replace(",", "");
			//alert("groupSendTotal : "+ groupSendTotal); return;
			if (groupSendTotal == "" || groupSendTotal == "0") {
				alert("고객 그룹을 선택하세요."); return;
    		}
    	}
		if ($("#switch_customer").is(":checked") && $("span[name=input_phone_no]").length == 0) {	// 연락처 직접 입력
    		if ($("span[name=input_phone_no]").length == 0) {
				alert("연락처를 입력 후 [전화번호 추가] 버튼을 클릭하세요.");
				$("#add-todo-input").focus();
				return;
    		}
    	}
		if ($("#switch_friend").is(":checked") && $("input[name=friend_yn]:checked").length == 0) {	// 플러스 친구 여부 확인 2019-07-09 추가
			if ($("input[name=friend_yn]").length == 0) {
				alert("플러스 친구 여부를 선택하세요."); return;
			}
		}
		<? } else {?>
		if (parseInt($("#not_friend_list").text()) == 0) {	// 연락처 직접 입력
			alert("발송 실패 고객이 없습니다."); return;
    	}
		<? } ?>
		if (link_type == "editor") { //에디터의 경우
			var bizurl =  document.getElementById("biz_url").value;
			//에디터 전단내용 저장
			$.ajax({
				url: "/dhnbiz/sender/talk/ums_save",
				type: "POST",
				data: {"bizurl" : bizurl, "html" : summ, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {}
			});
		}
		if(1 == 1){
			//alert("Start"); return;
			var check = true;

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

			var lms_msg = "";
			var link_type = $("#link_type").val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
			var dhnl_url = $("#dhnl_url").val(); //에디터 URL
			var psd_url = $("#psd_url").val(); //스마트전단 URL
			var pcd_url = $("#pcd_url").val(); //스마트쿠폰 URL
			//alert("link_type : "+ link_type +"\n"+ "dhnl_url : "+ dhnl_url +"\n"+ "psd_url : "+ psd_url);
			if(link_type == "editor" && dhnl_url != ""){ //에디터 & 에디터 URL 있는 경우
				<? if($mem_purigo_yn == "Y"){ //Purigo 회원 ?>
					lms_msg = lms_header_temp + "\n" + $("#lms").val() + "\n\n" + lms_footer_temp;
				<? }else{ ?>
					lms_msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$leaflet_link_name?>\n"+ dhnl_url +"\n\n"+ lms_footer_temp;
				<? } ?>
			}else if(link_type == "smart" && psd_url != ""){ //스마트전단 & 스마트전단 URL 있는 경우
				lms_msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$leaflet_link_name?>\n"+ psd_url +"\n\n"+ lms_footer_temp;
			}else if(link_type == "coupon" && pcd_url != ""){ //스마트쿠폰 & 스마트쿠폰 URL 있는 경우
				lms_msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$coupon_link_name?>\n"+ pcd_url +"\n\n"+ lms_footer_temp;
			}else{
				lms_msg = lms_header_temp + "\n" + $("#lms").val() + "\n\n" + lms_footer_temp;
			}

			$.ajax({
				url: "/dhnbiz/sender/check_special_char",
				type: "POST",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					   "lmsText" : lms_msg, "lmsTitle": ""},
				success: function (json) {
					var checkMode = json['checkMode'];
					var tatalBytes = json['tatalBytes'];
					var checkMsg = json['checkMsg'];

					if (checkMode == "0") {
	    				$(".content").html(checkMsg);
	    				//$('#myModal').modal({backdrop: 'static'});
	    				check = false;
						modal_open('myModal'); return false;
					}

					if(check == true) {
    					if($("#reserve_check").is(":checked") == true) {
    						if($("#reserve").val().trim() == ""){
    							check = false;
								alert("예약 발송 일자를 선택해주세요.");
								return;
    						} else if ($("#reserve_hours").val().trim() == "") {
    							$(".content").html("예약 발송 시간를 선택해주세요.");
    							//$('#myModal').modal({backdrop: 'static'});
    							check = false;
								modal_open('myModal'); return false;
    						} else if ($("#reserve_minutes").val().trim() == "") {
    							$(".content").html("예약 발송 분를 선택해주세요.");
    							//$('#myModal').modal({backdrop: 'static'});
    							check = false;
								modal_open('myModal'); return false;
    						} else {
    							if(($("#reserve_hours").val() < 8 || $("#reserve_hours").val() > 21) && "<?=$mem->mem_sms_time_clear_yn?>" == "N") {
    								check = false;
									alert("예약시간을 8:00 ~ 20:50분 사이로 입력해주세요.");
									return;
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
    		    						check = false;
										alert("예약발송은 최소 10분 이후로 입력해주세요.");
										return;
    		    					}
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
							//alert("date : "+ date +"\n"+"hour : "+ hour +"\n"+"minute : "+ minute +"\n"+"time : "+ time);
							<? if($this->member->item('mem_level') < 100){ ?>
							if((time < "0800" || time > "2100") && "<?=$mem->mem_sms_time_clear_yn?>" == "N") {
								check = false;
								alert("발송시간은 8:00 ~ 21:00시 사이로 발송할 수 있습니다.");
								return;
							}
							<? } ?>
    					}
					}
					if (check == true) {
						set_price();
						if(send_price <= 0)
						{
							$(".content").html("적용 단가 오류 - 확인이 필요 합니다.");
							//$('#myModal').modal({backdrop: 'static'});
							check = false;
							modal_open('myModal'); return false;
						}
					}

					if (check == true) {
						$.ajax({
							url: "/dhnbiz/sender/coin",
							type: "POST",
							data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"},
							success: function (json) {
								coin = json['coin'];
								var limit = Number(json['limit']);
								var sent = Number(json['sent']);
                                var bcoin = json.bcoin;
								var limit_msg = "";
								if(limit > 0 && sent >= limit) { showLimitOver(); return; }
								if(limit > 0) { limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>"; }

								//alert("coin : "  + coin);
								//alert("send_price : " + send_price);
								//check_resend_method();
								// 재발신 가능 건수를 산정합니다.
								var resends = (send_price > 0) ? Math.floor((Number(coin) + Number(bcoin)) / Number(send_price)) : 0;
								var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');

								if (!$("#switch_upload").is(":checked")) {
									var row_index = 0;
                                    var content_msg = '';
                                    <?if($mem->mrg_recommend_mem_id != "962" && $this->member->item('mem_id') != '962'){?>
									content_msg += "<div class='modal_info_row'><label>발송 가능 건수</label>" + resend_cnt + "건</div>";
                                    <?}?>

									<? if(!$fail_mst_id) { ?>
									if ($("#switch_select").is(":checked")) {	// 고객 DB에서 그룹 선택
										row_index = parseInt($("#groupSendTotal").text());
									} else if ($("#switch_customer").is(":checked")) {	// 연락처 직접 입력
										row_index = parseInt($("#input_phone_count").text());
									} else if ($("#switch_friend").is(":checked")) {	// 플러스 친구 여부 선택 2019-07-09 추가
										row_index = parseInt($("#friend_yn_tel_count").text());
									}
									<? } else { ?>
									var row_index = <?=$fail_qty?>;
									<? } ?>
									if ($("#switch_select").is(":checked")) {	// 고객 DB에서 그룹 선택
										row_index = parseInt($("#groupSendTotal").text().replace(/,/gi, ""));
									} else if ($("#switch_customer").is(":checked")) {	// 연락처 직접 입력
										row_index = parseInt($("#input_phone_count").text().replace(/,/gi, ""));
									} else if ($("#switch_friend").is(":checked")) {	// 플러스 친구 여부 선택 2019-07-09 추가
										row_index = parseInt($("#friend_yn_tel_count").text().replace(/,/gi, ""));
									} else if ($("#switch_pos").is(":checked")) {	//포스고객 선택 2021-02-03 추가
										row_index = parseInt($("#posSendTotal").text().replace(/,/gi, ""));
									}

									//alert("row_index : "+ row_index);
									if(row_index == "0"){
										alert("수신고객이 없습니다.");
										return false;
									}

									<? if( $this->member->item("mem_userid") == "015227985"){ //DHN애드톡 ?>
									//alert("coin : "+ coin +", send_price : "+ send_price +", row_index : "+ row_index);
									<? } ?>

									if ((Number(coin) + Number(bcoin)) - (Number(send_price) * Number(row_index)) < 0) {
                                        var html_text = "잔액이 부족합니다.<br>잔액 : "+coin+"<br>예상금액 : "+(Number(send_price) * Number(row_index));
                                        <?if($mem->mrg_recommend_mem_id == "962"){?>
                                            html_text = "잔액이 부족합니다.<br>잔액 : "+coin;
                                        <?}?>
                                        $(".content").html(html_text);
										//$('#myModal').modal({backdrop: 'static'});
										//$(document).unbind("keyup").keyup(function (e) {
										//	var code = e.which;
										//	if (code == 13) {
										//		$(".enter").click();
										//	}
										//});
										modal_open('myModal'); return false;
									} else {
                                        <?if($mem->mrg_recommend_mem_id != "962" && $this->member->item('mem_id') != '962'){?>
										if ($("#smslms_kind").val() == "SMS") {
											content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(send_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + send_price + ")</div>";
										} else if ($("#smslms_kind").val() == "LMS") {
											content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(send_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + send_price + ")</div>";
										} else if ($("#smslms_kind").val() == "MMS") {
											content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(send_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + send_price + ")</div>";
										} else {
											content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(charge_type) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + charge_type + ")</div>";
										}
                                        <?}?>
										$(".content").html(limit_msg + content_msg + "<br>전체 발송하시겠습니까?<br/> (발송할 건수(" + $("#smslms_kind_r").val() +") : " + row_index + "건)");
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
									// 재발신 가능 건수를 산정합니다.
									//var resends = (send_price > 0) ? Math.floor(Number(coin) / Number(send_price)) : 0;
									//var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
									<?/* 업로드 된 건수 */?>
									var upload_send = $("#upload_tel_count").text();
									if ((Number(coin) + Number(bcoin)) - (Number(send_price) * Number(upload_send)) < 0) {
                                        var html_text = "잔액이 부족합니다.<br>잔액 : "+coin+"<br>예상금액 : "+(Number(send_price) * Number(upload_send));
                                        <?if($mem->mrg_recommend_mem_id == "962"){?>
                                            html_text = "잔액이 부족합니다.<br>잔액 : "+coin;
                                        <?}?>
                                        $(".content").html(html_text);
										//$('#myModal').modal({backdrop: 'static'});
										//$(document).unbind("keyup").keyup(function (e) {
										//	var code = e.which;
										//	if (code == 13) {
										//		$(".enter").click();
										//	}
										//});
										modal_open('myModal'); return false;
									} else {
    									<?/* 발송가능 건수 표시 : 업로드 된 자료의 경우 업로드 내용으로만 판단할 수 있으므로 잔액이 부족하다는 메시지를 보여줄 수 없습니다. */?>
                                        var content_msg = '';
                                        <?if($mem->mrg_recommend_mem_id != "962" && $this->member->item('mem_id') != '962'){?>
    									content_msg += "<div class='modal_info_row'><label>발송 가능 건수</label>" + resend_cnt + "건</div>";
    									if ($("#smslms_kind").val() == "SMS") {
    										content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(send_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + send_price + ")</div>";
    									} else if ($("#smslms_kind").val() == "LMS") {
    										content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(send_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + send_price + ")</div>";
    									} else if ($("#smslms_kind").val() == "MMS") {
    										content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(send_price) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + send_price + ")</div>";
    									} else {
    										content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(charge_type) * Number(upload_send)).toFixed(2)  + " 원(" + upload_send + " X " + charge_type + ")</div>";
    									}
                                        <?}?>

    									$(".content").html(limit_msg + content_msg + "<br>업로드 파일을 전체 발송하시겠습니까?<br/> 발송할 건수 : " + upload_send.replace(/,/gi, "") + " 건" );
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

        if(sendFlag==false){
            sendFlag = true;
            $('.cancel').focus();
            // $('.cancel').trigger('click');
            // $('#myModalAll').remove();
    		var nextNavigateURL = "/dhnbiz/sender/history";
    		var senderBox = $("#sms_sender").val();
    		var smsOnly='';
    		var templi_cont = $("#templi_cont").val();
    		var profile = document.getElementById("pf_key").value;
    		var kind = ms;
    		//var msg = $('#lms').val();
    		var lms_header_temp = "";
    		var lms_footer_temp = "";
    		var msg = "";
    		<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
    		//var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
    		lms_header_temp = $("#span_adv").text() + $("#companyName").val();
    		if($("#kakotalk_link_text").text() == "") {
    			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
    		} else {
    			lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
    		}
    		//var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

    		//msg = lms_header_temp + "\n" + $("#lms").val() + "\n\n" + lms_footer_temp;

    		var link_type = $("#link_type").val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
    		var bizurl = $("#biz_url").val(); //에디터 코드
    		var summ = $('#summernote').summernote('code'); //에디터내용
    		var dhnl_url = $("#dhnl_url").val(); //에디터 링크 URL
    		var psd_code = $("#psd_code").val(); //스마트전단 코드
    		var psd_url = $("#psd_url").val(); //스마트전단 링크 URL
    		var pcd_type = $("#pcd_type").val(); //스마트쿠폰 타입
    		var pcd_code = $("#pcd_code").val(); //스마트쿠폰 코드
    		var pcd_url = $("#pcd_url").val(); //스마트쿠폰 URL
    		//alert("link_type : "+ link_type +"\n"+ "dhnl_url : "+ dhnl_url +"\n"+ "psd_url : "+ psd_url);
    		if(link_type == "editor" && dhnl_url != ""){ //에디터 & 에디터 URL 있는 경우
    			<? if($mem_purigo_yn == "Y"){ //Purigo 회원 ?>
    				msg = lms_header_temp +"\n"+ $("#lms").val() +"\n\n"+ lms_footer_temp;
    			<? }else{ ?>
    				msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$leaflet_link_name?>\n"+ dhnl_url +"\n\n"+ lms_footer_temp;
    			<? } ?>
    		}else if(link_type == "smart" && psd_url != ""){ //스마트전단 & 스마트전단 URL 있는 경우
    			msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$leaflet_link_name?>\n"+ psd_url +"\n\n"+ lms_footer_temp;
    		}else if(link_type == "coupon" && pcd_url != ""){ //스마트쿠폰 & 스마트쿠폰 URL 있는 경우
    			msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$coupon_link_name?>\n"+ pcd_url +"\n\n"+ lms_footer_temp;
    		}else{
    			msg = lms_header_temp +"\n"+ $("#lms").val() +"\n\n"+ lms_footer_temp;
    		}

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
    				if (temp_kind == "S" || temp_kind == "M") {
    					mst_type2 = "wc" + temp_resend_type;
    				} else {
    					mst_type2 = "wa" + temp_resend_type;
    				}
    			} else {
    				mst_type2 = "wa" + temp_resend_type;
    			}
    		} else if (temp_mst_kind == "NASELF"){
    			var temp_resend_type = "";
    			if (temp_kind != "L") {
    				temp_resend_type = temp_kind.toLowerCase();
    			}
    			mst_type2 = "wb" + temp_resend_type;
    		} else if (temp_mst_kind == "SMART"){
    			var temp_resend_type = "";
    			if (temp_kind != "L") {
    				temp_resend_type = temp_kind.toLowerCase();
    			}
    			mst_type2 = "wc" + temp_resend_type;
    		} else if (temp_mst_kind == "015") {
    			var temp_resend_type = "";
    			if (temp_kind != "L") {
    				temp_resend_type = temp_kind.toLowerCase();
    			}
    			mst_type2 = "015" + temp_resend_type;
    		} else if (temp_mst_kind == "PHONE" || temp_mst_kind == "SMT_PHN") {
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
    			file_data.append("link_type", link_type); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
    			file_data.append("bizurl", bizurl); //에디터 코드
    			file_data.append("html", summ); //에디터내용
    			file_data.append("dhnl_url", dhnl_url); //에디터 링크 URL
    			file_data.append("psd_code", psd_code); //스마트전단 코드
    			file_data.append("psd_url", psd_url); //스마트전단 링크 URL
                <? if($mem->voucher_deposit>0&&$mem->mem_voucher_yn=='Y'){ ?>
                file_data.append("voucher", "V");
                <? } else if ($mem->bonus_deposit > 0){?>
                file_data.append("voucher", "B");
                <? } ?>

    			$.ajaxSettings.traditional = true;
    			$.ajax({
    				url: "/dhnbiz/sender/lms/all",
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
    						modal_open('myModal'); return false;
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
    					modal_open('myModal'); return false;
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

    			var smslms_kind = $("#smslms_kind").val();
    			var mem_2nd_send = $("#mem_2nd_send").val();
    			//alert(customer_filter);

    			$.ajax({
    				url: "/dhnbiz/sender/lms/all",
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
    					,"link_type" : link_type //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
    					,"bizurl" : bizurl //에디터코드
    					,"html" : summ //에디터내용
    					,"dhnl_url" : dhnl_url //에디터 링크 URL
    					,"psd_code" : psd_code //스마트전단 코드
    					,"psd_url" : psd_url //스마트전단 링크 URL
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
    						modal_open('myModal'); return false;
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
    					modal_open('myModal'); return false;
    				}
    			});
    		} else if ($("#switch_customer").is(":checked") && $("span[name=input_phone_no]").length > 0) {	// 연락처 직접 입력
                if ($("#fail_flag").length > 0){
                    var tel_number = new Array();
                    var smslms_kind = $("#smslms_kind").val();
                    var mem_2nd_send = $("#mem_2nd_send").val();
                    //alert(smslms_kind + ", " + mem_2nd_send);
                    $.ajaxSettings.traditional = true;
                    $.ajax({
                        url: "/dhnbiz/sender/lms/all_fail",
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
                                ,"link_type" : link_type //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
                                ,"bizurl" : bizurl //에디터코드
                                ,"html" : summ //에디터내용
                                ,"dhnl_url" : dhnl_url //에디터 링크 URL
                                ,"psd_code" : psd_code //스마트전단 코드
                                ,"psd_url" : psd_url //스마트전단 링크 URL
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
                                    if (code == "success") {
                                        //$('#navigateURL').val(document.location.href);
                                        $('#navigateURL').val(nextNavigateURL);
                                        $(".content").html("발송 요청되었습니다.");
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
                                        modal_open('myModal'); return false;
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
                                    modal_open('myModal'); return false;
                                }
                            });
                } else {
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
                        url: "/dhnbiz/sender/lms/all",
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
                                ,"link_type" : link_type //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
                                ,"bizurl" : bizurl //에디터코드
                                ,"html" : summ //에디터내용
                                ,"dhnl_url" : dhnl_url //에디터 링크 URL
                                ,"psd_code" : psd_code //스마트전단 코드
                                ,"psd_url" : psd_url //스마트전단 링크 URL
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
                                        $(".content").html("발송 요청되었습니다.");
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
                                        modal_open('myModal'); return false;
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
                                    modal_open('myModal'); return false;
                                }
                            });

                }
    		} else if ($("#switch_friend").is(":checked") && $("input[name=friend_yn]:checked").length > 0) {	// 플러스 친구 여부 선택  2019-07-09 추가
    			var customer_all_count = parseInt($("#friend_yn_tel_count").text());
    			var customer_filter = "";
    			var customer_filter_count = 0;
    			$("input[name=friend_yn]:checked").each(function() {
    				customer_filter += $(this).val();
    			});
    			var smslms_kind = $("#smslms_kind").val();
    			var mem_2nd_send = $("#mem_2nd_send").val();

    			//alert(customer_filter);

    			$.ajax({
    				url: "/dhnbiz/sender/lms/all",
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
    					,"link_type" : link_type //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
    					,"bizurl" : bizurl //에디터코드
    					,"html" : summ //에디터내용
    					,"dhnl_url" : dhnl_url //에디터 링크 URL
    					,"psd_code" : psd_code //스마트전단 코드
    					,"psd_url" : psd_url //스마트전단 링크 URL
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
    						$(".content").html("발송 요청되었습니다.");
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
    						modal_open('myModal'); return false;
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
    					modal_open('myModal'); return false;
    				}
    			});
    		} <? if($fail_mst_id) { ?> else {
    			var customer_all_count = parseInt($("#groupSendTotal").text());
    			var customer_filter = "";
    			var smslms_kind = $("#smslms_kind").val();
    			var mem_2nd_send = $("#mem_2nd_send").val();

    			$.ajax({
    				url: "/dhnbiz/sender/lms/all",
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
    					,"link_type" : link_type //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
    					,"bizurl" : bizurl //에디터코드
    					,"html" : summ //에디터내용
    					,"dhnl_url" : dhnl_url //에디터 링크 URL
    					,"psd_code" : psd_code //스마트전단 코드
    					,"psd_url" : psd_url //스마트전단 링크 URL
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
    						$(".content").html("발송 요청되었습니다.");
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
    						modal_open('myModal'); return false;
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
    					modal_open('myModal'); return false;
    				}
    			});
    		}
    		<? } ?>
        }


	}

	<?/*--------------------------------*
	   | 테스트 발송시 값 체크하는 함수 |
	   *--------------------------------*/?>
	function test_send() {
        <? if($sendtelauth == 0) { ?>
        $(".content").html("문자 발송을 위해 발신번호(<? if(empty($rs->spf_sms_callback)) {echo $po->mem_phone;} else {echo $rs->spf_sms_callback;} ?>)를 <BR>사전에 등록 하세요.");
        //$('#myModal').modal({backdrop: 'static'});
        modal_open('myModal'); return false;
        <? } ?>
		if ($('#lms').val().replace(/ /gi,"")==""){
			alert("문자 내용을 입력해주세요.");
			$("#lms").focus();
		} else if (getByteLength($("#lms")) > 2000) {
			alert("문자 내용은 최대 2,000bytes (한글 1,000자) 이내로 입력하여야 합니다.");
			$("#lms").focus();
		} else if ($("span[name=test_input_phone_no]").length == 0) {	// 연락처 직접 입력
			alert("테스트 발송 연락처를 입력 후 [전화번호 추가] 버튼을 클릭하세요.");
			$("#test_add-todo-input").focus();
		} else {
			var check = true;

			var st = sTime();
		    var date = new Date(st);
			var hour = parseInt(date.getHours());

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
			//var lms_msg = lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;

			var lms_msg = "";
			var link_type = $("#link_type").val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
			var dhnl_url = $("#dhnl_url").val(); //에디터 URL
			var psd_url = $("#psd_url").val(); //스마트전단 URL
			var pcd_type = $("#pcd_type").val(); //스마트쿠폰 타입
			var pcd_code = $("#pcd_code").val(); //스마트쿠폰 코드
			var pcd_url = $("#pcd_url").val(); //스마트쿠폰 URL
			var summ = $('#summernote').summernote('code'); //에디터내용
			//alert("link_type : "+ link_type +"\n"+ "dhnl_url : "+ dhnl_url +"\n"+ "psd_url : "+ psd_url);
			if(link_type == "editor" && dhnl_url != ""){ //에디터 & 에디터 URL 있는 경우
				<? if($mem_purigo_yn == "Y"){ //Purigo 회원 ?>
					lms_msg = lms_header_temp +"\n"+ $("#lms").val() +"\n\n"+ lms_footer_temp;
				<? }else{ ?>
					lms_msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$leaflet_link_name?>\n"+ dhnl_url +"\n\n"+ lms_footer_temp;
				<? } ?>
			}else if(link_type == "smart" && psd_url != ""){ //스마트전단 & 스마트전단 URL 있는 경우
				lms_msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$leaflet_link_name?>\n"+ psd_url +"\n\n"+ lms_footer_temp;
			}else if(link_type == "coupon" && pcd_url != ""){ //스마트쿠폰 & 스마트쿠폰 URL 있는 경우
				lms_msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$coupon_link_name?>\n"+ pcd_url +"\n\n"+ lms_footer_temp;
			}else{
				lms_msg = lms_header_temp +"\n"+ $("#lms").val() +"\n\n"+ lms_footer_temp;
			}

			if (link_type == "editor" && (summ == '' || summ == '<p><br></p>' || summ == '<p><span style="font-size: 18px;">﻿</span><br></p>')) { //에디터의 경우
				$('#summernote').summernote('code', '');
				alert("<?=($mem_purigo_yn != "Y") ? "에디터" : "URL 메시지 편집"?> 내용을 입력하세요.");
				window.scroll(0, getOffsetTop(document.getElementById("id_STEP2"))); //STEP 2 영역으로 이동
				return;
			}
			if (link_type == "smart" && psd_url == "") {
				alert("스마트전단을 선택하세요.");
				smart_page('1'); //스마트전단 불러오기 모달창 열기
				return;
			}else if (link_type == "coupon" && pcd_url == "") {
				alert("스마트쿠폰을 선택하세요.");
				coupon_page('1'); //스마트쿠폰 불러오기 모달창 열기
				return;
			}
			//alert("lms_msg : "+ lms_msg);
			//alert("lms_msg : "+ lms_msg); return;

			$.ajax({
				url: "/dhnbiz/sender/check_special_char",
				type: "POST",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
					   "lmsText" : lms_msg, "lmsTitle": ""},
				success: function (json) {
					var checkMode = json['checkMode'];
					var tatalBytes = json['tatalBytes'];
					var checkMsg = json['checkMsg'];

					if (checkMode == "0") {
	    				$(".content").html(checkMsg);
	    				//$('#myModal').modal({backdrop: 'static'});
	    				check = false;
						modal_open('myModal'); return false;
					}
					//alert("1111111111111");
					if (check == true) {
						<? if($this->member->item('mem_level') < 100){ ?>
		    			//if(hour < 8 || hour > 21) {
		    			//	$(".content").html("발송시간을 8:00 부터 21:00사이로 발송할 수 있습니다.");
		    			//	$('#myModal').modal({backdrop: 'static'});
		    			//	check = false;
		    			//}
						<? } ?>
					}
					//alert("2222222");
					if (check == true) {
						set_price();
						if(send_price <= 0)
						{
							$(".content").html("적용 단가 오류 - 확인이 필요 합니다.");
							//$('#myModal').modal({backdrop: 'static'});
							check = false;
							modal_open('myModal'); return false;
						}
					}
					//alert("33333333");
					if (check == true) {
						$.ajax({
							url: "/dhnbiz/sender/coin",
							type: "POST",
							data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"},
							success: function (json) {
								coin = json['coin'];
								var limit = Number(json['limit']);
								var sent = Number(json['sent']);
                                var bcoin = json.bcoin;
								var limit_msg = "";
								if(limit > 0 && sent >= limit) { showLimitOver(); return; }
								if(limit > 0) { limit_msg = "<font color='blue'>금일 발송가능 : " + (limit - sent).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0') + "건</font><br><br>"; }

								//alert("coin : "  + coin);
								//alert("send_price : " + send_price);
								//check_resend_method();
								// 재발신 가능 건수를 산정합니다.
								var resends = (send_price > 0) ? Math.floor((Number(coin) + Number(bcoin)) / Number(send_price)) : 0;
								var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');

								// 재발신 가능 건수를 산정합니다.
								//var resends = (send_price > 0) ? Math.floor(Number(coin) / Number(send_price)) : 0;
								//var resend_cnt = resends.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/NaN/,'0');
								<?/* 업로드 된 건수 */?>
								var row_index = $("span[name=test_input_phone_no]").length;
								//alert("row_index : "+ row_index);
								//var upload_send = $("#upload_tel_count").text();
								if ((Number(coin) + Number(bcoin)) - (Number(send_price) * Number(row_index)) < 0) {
                                                var html_text = "잔액이 부족합니다.<BR>잔액:"+coin+"<BR>예상금액:"+(Number(send_price) * Number(row_index));
                                                <?if($mem->mrg_recommend_mem_id == "962"){?>
                                                    html_text = "잔액이 부족합니다.<BR>잔액:"+coin;
                                                <?}?>
                                                $(".content").html(html_text);
												modal_open('myModal');
												/*$('#myModal').modal({backdrop: 'static'});
												$(document).unbind("keyup").keyup(function (e) {
													var code = e.which;
													if (code == 13) {
														$(".enter").click();
													}
												});*/
											} else {
												<?/* 발송가능 건수 표시 : 업로드 된 자료의 경우 업로드 내용으로만 판단할 수 있으므로 잔액이 부족하다는 메시지를 보여줄 수 없습니다. */?>
                                                var content_msg = '';
                                                <?if($mem->mrg_recommend_mem_id != "962" && $this->member->item('mem_id') != '962'){?>
												content_msg += "<div class='modal_info_row'><label>발송 가능 건수</label>" + resend_cnt + "건</div>";
												//alert("content_msg 1 : "+ content_msg);
												if ($("#smslms_kind").val() == "SMS") {
													content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(send_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + send_price + ")</div>";
												} else if ($("#smslms_kind").val() == "LMS") {
													content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(send_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + send_price + ")</div>";
												} else if ($("#smslms_kind").val() == "MMS") {
													content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(send_price) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + send_price + ")</div>";
												} else {
													content_msg += "<div class='modal_info_row'><label>발송 예상 금액</label>"  + (Number(charge_type) * Number(row_index)).toFixed(2)  + " 원(" + row_index + " X " + charge_type + ")</div>";
												}
                                                <?}?>
												//alert("content_msg 2 : "+ content_msg);
												//$(".content").html(limit_msg + content_msg + "<br/><br/>업로드 파일을 전체 발송하시겠습니까?<br/> 발송할 건수 : " + row_index + " 건" );
												$(".content").html(limit_msg + content_msg + "<br>테스트 발송하시겠습니까?<br/> 발송할 건수 : " + row_index + " 건" );
												//$("#myModalTest").modal({backdrop: 'static'});
												//$(document).unbind("keyup").keyup(function (e) {
												//	var code = e.which;
												//	if (code == 13) {
												//		$(".test-send").click();
												//	}
												//});
												//$(".test-send").click(function () {
												//	$("#myModalTest").modal("hide");
												//});
												modal_open('myModalTest');
											}

							}
						});
					}
				}
			});
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
		<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
		//var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
		lms_header_temp = $("#span_adv").text() + $("#companyName").val();
		if($("#kakotalk_link_text").text() == "") {
			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		} else {
			lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		}
		//var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

		//msg = lms_header_temp + "\n" + $("#lms").val() + "\n" + lms_footer_temp;
		var link_type = $("#link_type").val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
		var bizurl = $("#biz_url").val(); //에디터 코드
		var summ = $('#summernote').summernote('code'); //에디터내용
		var dhnl_url = $("#dhnl_url").val(); //에디터 링크 URL
		var psd_code = $("#psd_code").val(); //스마트전단 코드
		var psd_url = $("#psd_url").val(); //스마트전단 링크 URL
		var pcd_type = $("#pcd_type").val(); //스마트쿠폰 타입
		var pcd_code = $("#pcd_code").val(); //스마트쿠폰 코드
		var pcd_url = $("#pcd_url").val(); //스마트쿠폰 URL
		//alert("link_type : "+ link_type +"\n"+ "dhnl_url : "+ dhnl_url +"\n"+ "psd_url : "+ psd_url +"\n"+ "bizurl : "+ bizurl +"\n"+ "summ : "+ summ); return;
		if(link_type == "editor" && dhnl_url != ""){ //에디터 & 에디터 URL 있는 경우
			<? if($mem_purigo_yn == "Y"){ //Purigo 회원 ?>
				msg = lms_header_temp +"\n"+ $("#lms").val() +"\n\n"+ lms_footer_temp;
			<? }else{ ?>
				msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$leaflet_link_name?>\n"+ dhnl_url +"\n\n"+ lms_footer_temp;
			<? } ?>
		}else if(link_type == "smart" && psd_url != ""){ //스마트전단 & 스마트전단 URL 있는 경우
			msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$leaflet_link_name?>\n"+ psd_url +"\n\n"+ lms_footer_temp;
		}else if(link_type == "coupon" && pcd_url != ""){ //스마트쿠폰 & 스마트쿠폰 URL 있는 경우
			msg = lms_header_temp +"\n"+ $("#lms").val() + "\n\n<?=$coupon_link_name?>\n"+ pcd_url +"\n\n"+ lms_footer_temp;
		}else{
			msg = lms_header_temp +"\n"+ $("#lms").val() +"\n\n"+ lms_footer_temp;
		}

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
			//var temp_resend_type = "";
			//if (temp_kind != "L") {
			//	temp_resend_type = temp_kind.toLowerCase();
			//}
			//mst_type2 = "wa" + temp_resend_type;
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
				if (temp_kind == "S" || temp_kind == "M") {
					mst_type2 = "wc" + temp_resend_type;
				} else {
					mst_type2 = "wa" + temp_resend_type;
				}
			} else {
				mst_type2 = "wa" + temp_resend_type;
			}
		} else if (temp_mst_kind == "NASELF"){
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			}
			mst_type2 = "wb" + temp_resend_type;
		} else if (temp_mst_kind == "SMART"){
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			}
			mst_type2 = "wc" + temp_resend_type;
		} else if (temp_mst_kind == "015") {
			var temp_resend_type = "";
			if (temp_kind != "L") {
				temp_resend_type = temp_kind.toLowerCase();
			}
			mst_type2 = "015" + temp_resend_type;
		} else if (temp_mst_kind == "PHONE" || temp_mst_kind == "SMT_PHN") {
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
			url: "/dhnbiz/sender/lms/all",
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
				,"link_type" : link_type //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
				,"bizurl" : bizurl //에디터코드
				,"html" : summ //에디터내용
				,"dhnl_url" : dhnl_url //에디터 링크 URL
				,"psd_code" : psd_code //스마트전단 코드
				,"psd_url" : psd_url //스마트전단 링크 URL
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
				var gid = json['gid'];
				if (code == "success") {
					//$('#navigateURL').val(document.location.href);
					//$('#navigateURL').val("/dhnbiz/sender/history");
					//$(".content").html("발송 요청되었습니다.");
					//$('#myModal').modal({backdrop: 'static'});
					////$('#myModal .enter').unbind("click").click(function() { document.location.href=$('#navigateURL').val(); });
					//$(document).unbind("keyup").keyup(function (e) {
					//	var code = e.which;
					//	if (code == 13) {
					//		$(".enter").click();
					//	}
					//});
					$(".content").html("테스트 발송 완료되었습니다.");
					modal_open('myModal');
				} else {
					$(".content").html("발송 실패되었습니다.<br/>" + message);
					//$('#myModal').modal({backdrop: 'static'});
					//$(document).unbind("keyup").keyup(function (e) {
					//	var code = e.which;
					//	if (code == 13) {
					//		$(".enter").click();
					//	}
					//});
					modal_open('myModal'); return false;
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
				modal_open('myModal'); return false;
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

	//문자메시지 불러오기 모달에서 선택하기
	function set_lms_by_user_msg(msg_id, msg_type) {
		//alert("msg_id : "+ msg_id +", msg_type : "+ msg_type); return;
		//$("#myModalUserMSGList").modal('hide');
		modal_close("myModalUserMSGList");
		$("#myModalUserMSGList .include_phns").unbind("click");
		$.ajax({
			url: "/dhnbiz/sender/lms/msg_load",
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

				var msg = "";
				if (msg_type == "U") {
				//var msg = json['mst_lms_content'];
					msg = json['msg'];
				} else {
					msg = json['mst_lms_content'];
				}
				//var msg = json['mst_lms_content'];
				//var msg2 = json['msg'];
				var kind = json['msg_kind'];

				if (msg_id>0) {
					$("#smslms_kind").val(kind).trigger('change');
					//$("#smslms_kind").
					//msgtypeChange($("#smslms_kind"));
					$("#lms").val(msg).trigger('keyup');
					//if (msg_type == "U") {
					//	$("#lms").val(msg2).trigger('keyup');
					//} else {
					//	$("#lms").val(msg).trigger('keyup');
					//}
					onPreviewText();
					//resize_cont($("#lms"));
					chkword_lms();
					$(".uploader").css('display','none');
				} else {
					$(".content").html("메시지 불러오기가 실패되었습니다.<br/>" + message );
					//$('#myModal').modal({backdrop: 'static'});
					//$(document).unbind("keyup").keyup(function (e) {
					//	var code = e.which;
					//	if (code == 13) {
					//		$(".enter").click();
					//	}
					//});
					modal_open('myModal'); return false;
				}
			},
			error: function (request,status,error){
				$(".content").html("메시지 불러오기가 실패되었습니다.(ER)"+status+","+error);
				//$('#myModal').modal({backdrop: 'static'});
				//$(document).unbind("keyup").keyup(function (e) {
				//	var code = e.which;
				//	if (code == 13) {
				//		$(".enter").click();
				//	}
				//});
				modal_open('myModal'); return false;
			}
		});
	}

	//문자메시지 불러오기 모달에서 삭제하기
	function sel_delete_msg(msgIds, msg_type){
		var result = confirm('선택한 메세지를 삭제 하시겠습니까?');
		if(result) { //yes
			if(msg_type == "P"){
				open_page_pre_lms_msg(1, msgIds); //발송 메시지 불러오기
			}else{
				open_page_user_lms_msg(1, msgIds); //저장 메시지 불러오기
			}
		}
	}

	//발송 메시지 불러오기
	function open_page_pre_lms_msg(page, delids ) {
		var searchMsg = $('#searchMsg').val() || '';
		var searchKind = $('#searchKind').val() || '';
		$('#myModalUserMSGList .content').html('').load(
			"/dhnbiz/sender/lms/pre_msg_list",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_msg": searchMsg,
				//"search_kind": searchKind,
				"search_kind": "MSG",
				"del_ids[]":delids,
				'page': page,
				'is_modal': true
			},
			function () {
				//$('.uniform').uniform();
				$('select.select2').select2();
			}
		);
	}

    //검색하기
	function search_msg(msg_type) {
    	//alert("msg_type : "+ msg_type);
		if(msg_type == "P"){
			open_page_pre_lms_msg('1'); //발송 메시지 검색하기
		}else{
			open_page_user_lms_msg('1'); //저장 메시지 검색하기
		}
    }

	//문자메시지 내용저장
	function msg_save(msg_type) {
		var msg = $('#lms').val();
		var msg_kind = $("#smslms_kind").val();
		//alert("msg : "+ msg +", msg_kind : "+ msg_kind); return;
		if(msg == ""){
			alert("입력된 문자내용이 없습니다.");
			$('#lms').focus();
			return;
		}
		//var second_flag = ($("input:checkbox[id='lms_select']").is(":checked") == true) ? "Y" : "N";
		//if (second_flag == "N") msg = "";
		$.ajax({
			url: "/dhnbiz/sender/lms/msg_save",
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
					//$('#myModal').modal({backdrop: 'static'});
					//$(document).unbind("keyup").keyup(function (e) {
					//	var code = e.which;
					//	if (code == 13) {
					//		$(".enter").click();
					//	}
					//});
					modal_open('myModal'); return false;
				} else {
					$(".content").html("저장이 실패되었습니다.<br/>" + message);
					//$('#myModal').modal({backdrop: 'static'});
					//$(document).unbind("keyup").keyup(function (e) {
					//	var code = e.which;
					//	if (code == 13) {
					//		$(".enter").click();
					//	}
					//});
					modal_open('myModal'); return false;
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
				modal_open('myModal'); return false;
			}
		});
	}

	//문자메시지 불러오기
	function open_lmsmsg_list() {
		//$("#myModalUserMSGList").modal({backdrop: 'static'});
		//$("#myModalUserMSGList").on('shown.bs.modal', function () {
		//	$('.uniform').uniform();
		//	$('select.select2').select2();
		//});
		//$('#myModalUserMSGList').unbind("keyup").keyup(function (e) {
		//	var code = e.which;
		//	if (code == 27) {
		//		$(".btn-default.dismiss").click();
		//	} else if (code == 13) {
		//		include_customer()
		//	}
		//});
		//$("#myModalUserMSGList .include_phns").click(function () {
		//	include_customer();
		//});
		open_page_user_lms_msg('1');
		modal_open('myModalUserMSGList');
	}

	//문자메시지 불러오기 내용조회
	function open_page_user_lms_msg(page, delids ) {
		var searchMsg = $('#searchMsg').val() || '';
		var searchKind = $('#searchKind').val() || '';
		$('#myModalUserMSGList .content').html('').load(
			"/dhnbiz/sender/lms/msg_save_list",
			{
				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
				"search_msg": searchMsg,
				//"search_kind": searchKind,
				"search_kind": "MSG",
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
				//$(".content").html("템플릿 내용을 400자 이내로 입력해주세요.");
				//$("#myModal").modal({backdrop: 'static'});
				//$('#myModal').on('hidden.bs.modal', function () {
				//	$("#templi_cont").focus();
				//});
				var cont = $("#templi_cont").val();
				var cont_slice = cont.slice(0, 400);
				$("#templi_cont").val(cont_slice);
				$("#type_num").html(400);
				$("#text").val(cont_slice);
				alert('템플릿 내용을 400자 이내로 입력해주세요.');
				$("#templi_cont").focus();
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
				//$(".content").html("템플릿 내용을 1000자 이내로 입력해주세요.");
				//$("#myModal").modal({backdrop: 'static'});
				//$('#myModal').on('hidden.bs.modal', function () {
				//	$("#templi_cont").focus();
				//});
				var cont = $("#templi_cont").val();
				var cont_slice = cont.slice(0, 1000);
				$("#templi_cont").val(cont_slice);
				$("#type_num").html(1000);
				$("#text").val(cont_slice);
				alert('템플릿 내용을 1000자 이내로 입력해주세요.');
				$("#templi_cont").focus();
				return;
			} else {
				$("#type_num").html(msg_length);
			}
		}
	}

	<?/*-------------------------*
	  | 2차발신 내용 글자수 제한 |
	  *--------------------------*/?>
	function chkword_lms() {
		//chkbyte($("#lms_header"), $("#lms"), $("#lms_footer"), $("#lms_num"), <?=(($mem->mem_2nd_send=='sms') ? '80' : '2000')?>);
		chkbyte($("#lms_header"), $("#lms"), $("#lms_footer"), $("#lms_num"));
	}

	function chkbyte($obj_header, $obj, $obj_footer, $num) {
		//var oriMaxByte = maxByte;
		var phn = '<?=$this->Biz_dhn_model->reject_phn?>';
		var phn_len = phn.length + 16;
		//alert("phn_len : "+ phn_len);

		<? // 문자 헤드부분 회사명 변경가능하도록 수정 문자길이체크 처리 2019-07-25 ?>
		var lms_header_temp = $obj_header.find("#span_adv").text() + $obj_header.find("#companyName").val();
		//var lms_header_temp = $obj_header.html().replace("<BR>", "\n").replace("<br>", "\n");
		var lms_footer_temp = "";
		if($("#kakotalk_link_text").text() == "") {
			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		} else {
			lms_footer_temp = $("#kakotalk_link_text").text() +"\n"+ $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		}
		//var lms_footer_temp = $obj_footer.html().replace("<BR>", "\n").replace("<br>", "\n");

		var strValue = "";
		var link_type = $("#link_type").val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
		var dhnl_url = $("#dhnl_url").val(); //에디터 URL
		var psd_url = $("#psd_url").val(); //스마트전단 URL
		var pcd_type = $("#pcd_type").val(); //스마트쿠폰 타입
		var pcd_code = $("#pcd_code").val(); //스마트쿠폰 코드
		var pcd_url = $("#pcd_url").val(); //스마트쿠폰 URL
		//alert("link_type : "+ link_type +"\n"+ "dhnl_url : "+ dhnl_url +"\n"+ "psd_url : "+ psd_url);
		if(link_type == "editor" && dhnl_url != ""){ //에디터 & 에디터 URL 있는 경우
			<? if($mem_purigo_yn == "Y"){ //Purigo 회원 ?>
				strValue = lms_header_temp +"\n"+ $obj.val() + "\n\n"+ lms_footer_temp;
			<? }else{ ?>
				strValue = lms_header_temp +"\n"+ $obj.val() + "\n\n<?=$leaflet_link_name?>\n"+ dhnl_url +"\n\n"+ lms_footer_temp;
			<? } ?>
		}else if(link_type == "smart" && psd_url != ""){ //스마트전단 & 스마트전단 URL 있는 경우
			strValue = lms_header_temp +"\n"+ $obj.val() + "\n\n<?=$leaflet_link_name?>\n"+ psd_url +"\n\n"+ lms_footer_temp;
		}else if(link_type == "coupon" && pcd_url != ""){ //스마트쿠폰 & 스마트쿠폰 URL 있는 경우
			strValue = lms_header_temp +"\n"+ $obj.val() + "\n\n<?=$coupon_link_name?>\n"+ pcd_url +"\n\n"+ lms_footer_temp;
		}else{
			strValue = lms_header_temp +"\n"+ $obj.val() +"\n\n"+ lms_footer_temp;
		}
		var strLen = strValue.length || 0;
		//alert("strValue : "+ strValue);

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
		if (totalByte > 2000) {
			$("#info_desc").html('<span class="noted_red">※ 2000 byte가 넘어 발송할 수 없습니다.</span>');
		}else{
			$("#info_desc").html('<span class="info_desc">90bytes가 넘어가면 장문 문자로 변경됩니다.</span>');
		}
		$num.html(totalByte);

		//if (maxByte <= 80) {
        var isdhn = $("#hdn_isdhn").val();
		var mms_flag = false;
		$("div[name='mms-images']").each(function () {
			var filename = $(this).find(document.getElementsByName("fileInput")).val();
			if (filename != "") {
				mms_flag = true;
			}
		});
		if (!mms_flag) {
            if (totalByte <= 90) {
				$("#lms_limit_str").html("SMS");
				// $("#lms_limit_str").attr("class", "msg_type_txt sms");
                if(isdhn=="N"){
                    $(".msg_type").attr("class", "msg_type sms");
                }
				$("#lms_character_limit").html("90");
				$("#smslms_kind").val("SMS");

    			$(".msg_type_txt").attr("class", "msg_type_txt sms");
                lmsflag="sms";
			} else {
                if(lmsflag=="sms"){
                    showSnackbar("90Byte가 초과되어 LMS로 전환됩니다.", 1500);
                }
	    		$("#lms_limit_str").html("LMS");
	    		// $("#lms_limit_str").attr("class", "msg_type_txt lms");
                if(isdhn=="N"){
                    $(".msg_type").attr("class", "msg_type lms");
                }
	    		$("#lms_character_limit").html("2000");
	    		$("#smslms_kind").val("LMS");

    			$(".msg_type_txt").attr("class", "msg_type_txt lms");
                lmsflag="lms";
			}
		} else {
    		$("#lms_limit_str").attr("class", "msg_type_txt mms");
            if(isdhn=="N"){
                $(".msg_type").attr("class", "msg_type mms");
            }
    		$("#lms_character_limit").html("2000");
    		$("#smslms_kind").val("MMS");
    	}
   }

	<?/*--------------------*
	  | 글자수(Byte)를 반환 |
	  *---------------------*/?>
	function getByteLength($obj)
	{
		<? // 문자 헤드부분 회사명 변경가능하도록 수정  2019-07-25 ?>
		//var lms_header_temp = $("#lms_header").html().replace("<BR>", "\n").replace("<br>", "\n");
		var lms_header_temp = $("#span_adv").text() + $("#companyName").val();
		var lms_footer_temp = "";
		if($("#kakotalk_link_text").text() == "") {
			lms_footer_temp = $("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		} else {
			lms_footer_temp = $("#kakotalk_link_text").text() + "\n" +$("#span_unsubscribe").text() + $("#unsubscribe_num").val();
		}
		//var lms_footer_temp = $("#lms_footer").html().replace("<BR>", "\n").replace("<br>", "\n");

		var link_type = $("#link_type").val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
		var dhnl_url = $("#dhnl_url").val(); //에디터 URL
		var psd_url = $("#psd_url").val(); //스마트전단 URL
		var pcd_type = $("#pcd_type").val(); //스마트쿠폰 타입
		var pcd_code = $("#pcd_code").val(); //스마트쿠폰 코드
		var pcd_url = $("#pcd_url").val(); //스마트쿠폰 URL
		//alert("link_type : "+ link_type +"\n"+ "dhnl_url : "+ dhnl_url +"\n"+ "psd_url : "+ psd_url);
		if(link_type == "editor" && dhnl_url != ""){ //에디터 & 에디터 URL 있는 경우
			<? if($mem_purigo_yn == "Y"){ //Purigo 회원 ?>
				lms_footer_temp = "\n\n"+ lms_footer_temp;
			<? }else{ ?>
				lms_footer_temp = "\n<?=$leaflet_link_name?>\n"+ dhnl_url +"\n\n"+ lms_footer_temp;
			<? } ?>
		}else if(link_type == "smart" && psd_url != ""){ //스마트전단 & 스마트전단 URL 있는 경우
			lms_footer_temp = "\n<?=$leaflet_link_name?>\n"+ psd_url +"\n\n"+ lms_footer_temp;
		}else if(link_type == "coupon" && pcd_url != ""){ //스마트쿠폰 & 스마트쿠폰 URL 있는 경우
			lms_footer_temp = "\n<?=$coupon_link_name?>\n"+ pcd_url +"\n\n"+ lms_footer_temp;
		}

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
			//$(".content").html("링크 버튼 이름을 30자 이내로 입력해주세요.");
			//$("#myModal").modal({backdrop: 'static'});
			//$('#myModal').on('hidden.bs.modal', function () {
			//	$("#btn_name").focus();
			//});
			var cont = $("#btn_name").val();
			var cont_slice = cont.slice(0, 30);
			$("#btn_name").val(cont_slice);
			$("#link_num").html(30);
			alert("링크 버튼 이름을 30자 이내로 입력해주세요.");
			$("#btn_name").focus();
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
		form.setAttribute("action", "/dhnbiz/sender/send/profile3");
		form.submit();
	}

	// copy from customer_list
	function open_page2(page) {
		var type = $('#searchType2').val() || 'all';
		var searchFor = $('#searchStr2').val() || '';

		$('#profile_select .widget-content').html('').load(
			"/dhnbiz/sender/send/profile5",
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

		imgSelectBox = window.open("/dhnbiz/sender/images", 'tst', 'width=' + sw + ',height=' + sh + ',top=' + mt + ',left=' + ml + ',location=no, resizable=no');
	}

	//미리보기 - 이미지
	function readImage(selctImgValue, img_no) {

		  if(img_no === undefined) {
			  img_no = 1;
		   }

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
    }

	//업로드 양식 다운로드
	function download() {
		document.location.href="/uploads/number_list.xlsx";
	}

	function img_delete(deleteNo, fileInputCount) {
		//alert("deleteNo : " + deleteNo + "  fileInputCount : " + fileInputCount);
        var file_data = new FormData();
        file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
        file_data.append("mms_fileInput_no", deleteNo);
        file_data.append("mms_fileInput_cnt", fileInputCount);
        file_data.append("mms_id", mms_id);
        $.ajax({
            url: "/dhnbiz/sender/lms/mms_image_delete",
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
                //$("#myModal").modal('show');
				modal_open('myModal'); return false;
            }
        });
	}

    function img_upload(userfile, fileinput) {
        var thumbext = $("#" + userfile).val();
        var file_data = new FormData();
        file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
        file_data.append(fileinput, $("#"+fileinput)[0].files[0]);
        file_data.append("mms_id", mms_id);

        thumbext = thumbext.slice(thumbext.indexOf(".") + 1).toLowerCase();
        if (thumbext) {
                $.ajax({
                    url: "/dhnbiz/sender/lms/mms_upload",
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
                        //$("#myModal").modal('show');
						modal_open('myModal'); return false;
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
                        //$("#myModal").modal('show');
                        //$(document).unbind("keyup").keyup(function (e) {
                        //    var code = e.which;
                        //    if (code == 13) {
                        //        $(".btn-primary").click();
                        //    }
                        //});
						modal_open('myModal'); return false;
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
						$(".content").html("올바르지 않은 파일입니다.<br>"+msg);
						//$('#myModal').modal({backdrop: 'static'});
						//$('#filecount').filestyle('clear');
						modal_open('myModal'); return false;
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
                            var html_text = "잔액이 부족합니다.<BR>잔액:"+coin+"<BR>예상금액:"+Number(total_price);
                            <?if($mem->mrg_recommend_mem_id == "962"){?>
                                html_text = "잔액이 부족합니다.<BR>잔액:"+coin;
                            <?}?>
                            $(".content").html(html_text);
							//$('#myModal').modal({backdrop: 'static'});
							//$('#filecount').filestyle('clear');
							modal_open('myModal'); return false;
						} else if (row_len > 60000) {
							$(".content").html("최대 6만건까지 가능합니다.");
							//$('#myModal').modal({backdrop: 'static'});
							//$('#filecount').filestyle('clear');
							modal_open('myModal'); return false;
						} else {
							var bulk_send = json['row_len'];
							var tot_row = json['tot_row_len'];
							var ex_row = json['ex_row_len'];
							var coin = json['coin'];
							var limit_count;
							$("#upload_tel_count").html(bulk_send);
						}
					}
				}
			});
		} else {
			$(".content").html("xls,xlsx, txt 파일만 가능합니다.");
			//$('#myModal').modal({backdrop: 'static'});
			//$('#myModal').on('hidden.bs.modal', function () {
			//});
			//$(document).unbind("keyup").keyup(function (e) {
			//	var code = e.which;
			//	if (code == 13) {
			//		$(".enter").click();
			//	}
			//});
			modal_open('myModal'); return false;
		}
	}

	<?/*----------------------------------------*
		| 업로드 버튼을 클릭하였을때 메시지 표시 |
		*----------------------------------------*/?>
	function upload() {
	    if ('0' != 0) {
			$('#filecount').attr('disabled', 'disabled');
		}else {
			$('#filecount').attr('disabled', 'disabled');
			//$(".content").html("입력하신 수신 번호가 초기화됩니다.<br/>업로드 하시면 업로드 파일의 내용으로 발송됩니다.<br/>업로드 하시겠습니까?");
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
			if(confirm("입력하신 수신 번호가 초기화됩니다.\n업로드 하시면 업로드 파일의 내용으로 발송됩니다.\n업로드 하시겠습니까?")){
				check();
			}
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
			var content = "(광고)"+$("#pf_ynm").val()+"\r\r\r\r수신거부 : 홈>친구차단";

			$("#templi_cont").val(content);
			$("#templi_cont").attr("readonly", false).css("background-color", "white");
			$("#lms_select").removeAttr("disabled");
			$("#img_select").attr('disabled', false).css("cursor","pointer");
			if ($("#img_url").val() != "") {
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
			//$("#myModalUpload").modal('hide');

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
				url: "/dhnbiz/sender/lms/load_customer",
				data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>", 'filter': filter },
				type: "POST",
				success: function (json) {
					var customer_count = json['customer_count'];
					if (customer_count > 60000) {
						$(".content").html("최대 60,000명까지 가능합니다.");
						//$('#myModal').modal({backdrop: 'static'});
						modal_open('myModal'); return false;
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
						//$('#myModal').modal({backdrop: 'static'});
						modal_open('myModal'); return false;
					}
				},
				error: function (data, status, er) {
					$(".content").html("처리중 오류가 발생하였습니다.");
					//$("#myModal").modal('show');
					modal_open('myModal'); return false;
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

	function cancelClick() {
		$(document).unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 13) {
				$(".enter").click();
			}
		});
	}

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

	//링크 타입 변경
	function chg_link_type(){
		var link_type = document.getElementById("link_type").value; //URL 타입
		var btn_rul = "";
		if(link_type == "none"){ //사용안함
			document.getElementById("div_smart_box").style.display = "none"; //스마트전단 영역 닫기
			document.getElementById("div_coupon_box").style.display = "none"; //스마트쿠폰 영역 닫기
			document.getElementById("div_editor_box").style.display = "none"; //에디터 영역 닫기
			document.getElementById("div_blank_box").style.display = ""; //사용안함 영역 열기
			document.getElementById("div_smart_goods_call").style.display = "none"; //행사정보 가져오기 영역 열기
		}else if(link_type == "editor"){ //에디터
			btn_rul = document.getElementById("dhnl_url").value; //에디터 링크 URL
			document.getElementById("div_smart_box").style.display = "none"; //스마트전단 영역 닫기
			document.getElementById("div_coupon_box").style.display = "none"; //스마트쿠폰 영역 닫기
			document.getElementById("div_editor_box").style.display = ""; //에디터 영역 열기
			document.getElementById("div_blank_box").style.display = "none"; //사용안함 영역 닫기
			document.getElementById("div_smart_goods_call").style.display = "none"; //행사정보 가져오기 영역 열기
		}else if(link_type == "coupon"){ //스마트쿠폰
			btn_rul = document.getElementById("pcd_url").value; //스마트쿠폰 링크 URL
			document.getElementById("div_smart_box").style.display = "none"; //스마트전단 영역 열기
			document.getElementById("div_coupon_box").style.display = "inline"; //스마트쿠폰 영역 닫기
			document.getElementById("div_editor_box").style.display = "none"; //에디터 영역 닫기
			document.getElementById("div_blank_box").style.display = "none"; //사용안함 영역 닫기
			document.getElementById("div_smart_goods_call").style.display = ""; //행사정보 가져오기 영역 열기
		}else{ //스마트전단
			btn_rul = document.getElementById("psd_url").value; //스마트전단 링크 URL
			document.getElementById("div_smart_box").style.display = "inline"; //스마트전단 영역 열기
			document.getElementById("div_coupon_box").style.display = "none"; //스마트쿠폰 영역 닫기
			document.getElementById("div_editor_box").style.display = "none"; //에디터 영역 닫기
			document.getElementById("div_blank_box").style.display = "none"; //사용안함 영역 닫기
			document.getElementById("div_smart_goods_call").style.display = ""; //행사정보 가져오기 영역 열기
		}
		//문자내용반영
		onPreviewText();
		chkword_lms();
	}

	//스마트전단 선택
	function smart_choice(code){
		//alert("code : "+ code); return;

		//스마트전단 링크 URL 만들기
		$.ajax({
			url: "/spop/screen/smart_dhnlurl",
			type: "POST",
			data: {"code": code, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function(json){
				var btn_rul = json.dhnlurl;
				//alert("btn_rul : "+ btn_rul); return;
				if(btn_rul != ""){
					document.getElementById("psd_code").value = code; //스마트전단 코드
					document.getElementById("psd_url").value = btn_rul; //스마트전단 URL
					$("#div_smart_url").html(btn_rul); //스마트전단 링크주조 표기 영역
					//alert("psd_url : "+ document.getElementById("psd_url").value);
					//문자내용반영
					onPreviewText();
					chkword_lms();
				}
			}
		});

		//스마트전단 불러오기 Modal 닫기
		modal_smart.style.display = "none";
	}

	//스마트쿠폰 선택
	function coupon_choice(code, type){
		//alert("code : "+ code); return;
		//스마트쿠폰 링크 URL 만들기
		$.ajax({
			url: "/spop/coupon/coupon_dhnlurl",
			type: "POST",
			data: {"code": code, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function(json){
				var btn_url = json.dhnlurl;
				//alert("btn_url : "+ btn_url); return;
				if(btn_url != ""){
					document.getElementById("pcd_code").value = code; //스마트쿠폰 코드
					document.getElementById("pcd_type").value = type; //스마트쿠폰 타입
					document.getElementById("pcd_url").value = btn_url; //스마트전단 링크 URL
					$("#div_coupon_url").html(btn_url); //스마트전단 링크주조 표기 영역
					//alert("pcd_url : "+ document.getElementById("pcd_url").value);
					//문자내용반영
					onPreviewText();
					chkword_lms();
				}
			}
		});

		//스마트전단 불러오기 Modal 닫기
		modal_coupon.style.display = "none";
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
