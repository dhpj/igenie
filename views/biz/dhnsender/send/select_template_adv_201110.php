<?
	//같이 수정해야 됨 /views/biz/dhnsender/send/bootstrap/talk_adv.php
	$bt1_tpl_id = "77"; //버튼타입 1 템플릿번호
	$bt2_tpl_id = "78"; //버튼타입 2 템플릿번호
	$bt3_tpl_id = "80"; //버튼타입 3 템플릿번호

	//버튼타입 1 템플릿번호 변경시
	//같이 수정해야 됨 controllers/dhnbiz/sender/send/Talk_adv.php > index() > tmp_code 변경
?>
<?if($rs->tpl_profile_key) {?>
	<div class="input_content_wrap" style="display:none;">
		<label class="input_tit">템플릿 코드</label>
		<div class="input_content">
			<?=$rs->tpl_code?>
		</div>
		<input type="hidden" id="pf_ynm" value="<?=$mem1->mem_username?>"/>
		<input type="hidden" id="sms_sender" value="<?=$rs1->spf_sms_callback?>"/>
		<!-- 20190107 이수환 추가 작업중인 아디의 링크버튼 명을 입력-->
		<input type="hidden" name="find_linkbtn_name" id="find_linkbtn_name" value="<?=$rs->mem_linkbtn_name?>">
		<input type="hidden" name="pf_key" id="pf_key" value="<?=$rs->tpl_profile_key?>">
		 <input type="hidden" name="pf_yid" id="pf_yid" value="<?=$rs->spf_friend?>">
		 <input type="hidden" name="templi_code" id="templi_code" value="<?=$rs->tpl_code?>">
		 <input type="hidden" name="templi_id" id="templi_id" value="<?=$rs->tpl_id?>">
		 <input type="hidden" name="sbid" id="sbid" value="<?=$param['sb_id']?>">
		 <input type="hidden" name="kind" id="kind" value="L">
		 <input type="hidden" name="btn_name">
		 <input type="hidden" name="btn_url">
		 <input type="hidden" name="couponid" id="couponid"  value="<?=$rs->cc_idx?>">
		 <input type="hidden" name="biz_url" id="biz_url"  value="<?=$short_url?>" style="width:100px;">
		 <input type="hidden" name="dhnl_url" id="dhnl_url"  value="<?=$dhnl_url?>" style="width:160px;">
		 <input type="hidden" name="psd_code" id="psd_code"  value="<?=$psd_code?>" style="width:100px;">
		 <input type="hidden" name="psd_url" id="psd_url"  value="<?=$psd_url?>" style="width:160px;">
	</div>
	<div class="input_content_wrap" style="display:none;">
		<label class="input_tit">템플릿명</label>
		<div class="input_content">
			<?=$rs->tpl_name?>
		</div>
	</div>
	<div class="input_content_wrap">
		<div class="mobile_preview">
			<div class="preview_circle"></div>
			<div class="preview_round"></div>
			<div class="preview_msg_wrap">
				<div class="preview_msg_window">
					<div class="preview_box_profile">
						<span class="profile_thumb">
							<img src="/img/icon/icon_profile.jpg">
						</span>
						<span class="profile_text"><?=$mem1->mem_username?></span>
					</div>
					<div class="preview_box_msg" id="text"></div>
				</div>
				<?if($rs->cc_idx) { // 2019.08.08. 쿠폰 발송 관련 시작?>
				<div class="preview_option flex">
					<button class="btn_preview_option" id="msg_save_temp" onclick="view_preview();">응모페이지</button>
					<button class="btn_preview_option" id="open_msg_list_" onclick="view_result();">당첨페이지</button>
				</div>
				<? } // 2019.08.08. 쿠폰 발송 관련 끝 ?>
				<div class="preview_option flex">
					<button class="btn_preview_option" onClick="msg_save();">내용저장</button>
					<button class="btn_preview_option" onClick="open_msg_lms_list();">불러오기</button>
				</div>
			</div>
			<div class="preview_home"></div>
		</div><!-- mobile_preview END -->
		<label class="input_tit">
			<p class="txt_st_eng">STEP 1.</p>
			<p>버튼타입 선택</p>
		</label>
		<div class="input_content">
			<div class="btn_adv_wrap">
				<button class="btn_adv<?=($rs->tpl_id == $bt1_tpl_id) ? " on" : ""?>" name="selected_temp" id="selected_temp2" onclick="selected_template(this.id)"><span class="tem_btn1">★행 사 보 기★</span></button>
				<? if($this->member->item('mem_contract_type') == "P"){ //계약형태 (S.스텐다드, P.프리미엄) ?>
					<button class="btn_adv<?=($rs->tpl_id == $bt2_tpl_id) ? " on" : ""?>" name="selected_temp" id="selected_temp3" onclick="selected_template(this.id)"><span class="tem_btn1">★행 사 보 기★</span><span class="tem_btn2">★주 문 하 기★</span></button>
				<? } ?>
				<button class="btn_adv<?=($rs->tpl_id == $bt3_tpl_id) ? " on" : ""?>" name="selected_temp" id="selected_temp1" onclick="selected_template(this.id)"><span class="tem_btn1">버튼없음</span></button>
        <p class="noted">* 템플릿 버튼타입을 먼저 선택하세요.</p>
			</div>
		</div>
		</div>
		<div class="input_content_wrap">
		<label class="input_tit">
			<p class="txt_st_eng">STEP 2.</p>
			<p>발송내용 입력</p>
		</label>
		<div class="input_content">
			<div class="msg_box">
				<!-- div class="txt_ad" id="cont_header">(광고) 대형네트웍스</div -->
				<textarea style="display:none;" name="dumy_templi_cont" id="dumy_templi_cont" cols="30" rows="5" class="form-control autosize" placeholder="템플릿 내용을 입력해주세요." style="resize:none;cursor:default;"
						onkeyup="javascript: var file = document.getElementById('filecount').value;
								 if (file==''){templi_preview(this); scroll_prevent(); templi_chk();}"
						value="<?=$rs->tpl_contents?>"><?=$rs->tpl_contents?></textarea>
				<div class="input_msg" style="height: auto;" id="templi_cont">
				<?

				//$tmp_str = nl2br($rs->tpl_contents);
				$tmp_str = $rs->tpl_contents;
				$tmp_str = str_replace("\n\n", "<p>&nbsp;</p>", $tmp_str);
				$tmp_str = nl2br($tmp_str);

				$pattern = '/\#{[^}]*}/';
				$m_cnt = preg_match_all($pattern, $tmp_str, $match );
				$idx = 0;
				$m_idx = 0;

				while($m_cnt >= 1) {
				    $var_value = "";
				    if(!empty($varvalue)) {
				        $var_value=$varvalue[$idx]->var_text;
				    }
				    //log_message("ERROR", "cnt : ".sizeof($varvalue)."/ value : ".$var_value);
				    $pla =  $match[0][$idx];

				    $variable = '/\\'.$match[0][$idx].'/';
				    if($pla == '#{변수내용}') {
				        $var_str = '<textarea name="var_name[]" class="var multi" onkeyup="return view_preview();" placeholder="'.str_replace("#", "", $pla).'">'.$var_value.'</textarea>';
				    } else if($pla == '#{업체명}') {
				        if(empty($var_value)) {
				            $var_value = $this->member->item('mem_username');
				        }
				        $var_str = '<textarea name="var_name[]" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="'.str_replace("#", "", $pla).'">'.$var_value.'</textarea>';
				    } else if($pla == '#{업체전화번호}' || $pla == '#{전화번호}') {
						if(empty($var_value)) {
				            //$var_value = $this->Biz_dhn_model->add_hyphen($this->member->item('mem_phone'));
				            $var_value = $this->funn->format_phone($this->member->item('mem_phone'), "-");
				        }
				        $var_str = '<textarea name="var_name[]" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="'.str_replace("#", "", $pla).'">'.$var_value.'</textarea>';
				    } else {
				        $var_str = '<textarea name="var_name[]" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="'.str_replace("#", "", $pla).'">'.$var_value.'</textarea>';
				    }
				    //log_message("ERROR", $pla."/".$var_str);
			        $tmp_str= preg_replace($variable,$var_str,$tmp_str, 1);
				    $m_cnt = $m_cnt - 1;
				    $idx = $idx + 1;
				    //log_message("ERROR", $tmp_str);
				}
				echo $tmp_str;

				?>
				</div>
				<span class="txt_byte"><span id="type_num">0</span>/1,000 자</span>
				<!-- div class="txt_ad">수신거부 : 홈>친구차단</div-->
			</div>
			<p class="noted">*노란 박스 영역(변수값)의 내용만 입력/수정이 가능합니다.</p>
			<!--p class="info_text">"#{변수}" 부분의 내용만 수정이 가능합니다.</p-->
		</div>
	</div>
	<div class="input_content_wrap" style="display:<?=($rs->tpl_id == $bt3_tpl_id) ? "none" : ""?>;">
		<label class="input_tit">
			<p class="txt_st_eng">STEP 3.</p>
			<p>버튼내용 선택</p>
		</label>
		<div class="input_content">
			<!-- 스마트전단, 에디터사용 공통 -->
			<? $leaflet_link_type = "at"; //전단지 타입(at.알림톡, lms.문자) ?>
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/dhnsender/send/bootstrap/_inc_leaflet.php'); ?>
			<div id="field">
			</div>
		</div>
		<script type="text/javascript">
			var buttons = '<?=$rs->tpl_button?>';
			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);
			var url_name1 = '';
			var url_name2 = '';
			var url_link1 = '';
			var url_link2 = '';
			var link_type = document.getElementById("link_type").value;
			//alert("link_type : "+ link_type);

			for(var i=0;i<btn_content.length;i++) {
				var html = '';
				var btn_count = i + 1;
				var ordering= (typeof(btn_content[i]["ordering"])=='undefined') ? no : btn_content[i]["ordering"];
				var btn_type = btn_content[i]["linkType"];
				//var type = '';
				var btn_name = btn_content[i]["name"];
				//if(btn_type=="DS") { type="<?=$this->Biz_dhn_model->buttonName['DS']?>"; }
				//else if(btn_type=="BK") { type="<?=$this->Biz_dhn_model->buttonName['BK']?>"; }
				//else if(btn_type=="MD") { type="<?=$this->Biz_dhn_model->buttonName['MD']?>"; }
				//else if(btn_type=="AL") { type="<?=$this->Biz_dhn_model->buttonName['AL']?>"; }
				//else if(btn_type=="WL") { type="<?=$this->Biz_dhn_model->buttonName['WL']?>"; }

				if (btn_type == "DS" || btn_type == "BK" || btn_type == "MD") {
					html += "<div id=\"field-data-" + i + "\" name=\"field-data\">";
					html += "<div class=\"input_link_wrap\">";
					html += "<select style=\"width: 120px;\" class=\"fl\"  name=\"btn_type\" id=\"btn_type\">"
					html += "<option disabled=\"disabled\" value=\"WL\">웹링크</option>";
					html += "<option disabled=\"disabled\" value=\"AL\">앱링크</option>";
					html += "<option disabled=\"disabled\" value=\"BK\"";
					if(btn_type == "BK") { html += " selected" }
					html += ">봇키워드</option>";
					html += "<option disabled=\"disabled\" value=\"MD\"";
					if(btn_type == "MD") { html += " selected" }
					html += ">메시지전달</option>";
					html += "</select>";
					html += "<div style=\"overflow: hidden;\"><input type=\"text\" name=\"btn_name\" id=\"btn_name\" style=\"width:100%; margin-left: -1px;\" placeholder=\"버튼명을 입력해주세요\" readonly value=\"" + btn_name;
					html += "\"></div>";
					html += "</div>";
					html += "</div>";
				} else if (btn_type == "WL" || btn_type == "AL") {
					if (btn_name != "") {
						html += "<div id=\"field-data-" + i + "\" name=\"field-data\">";
						html += "<div class=\"input_link_wrap\">";
						html += "<input id=\"btn_type\" name=\"btn_type\" type=\"hidden\" value=\"" + btn_type + "\"></td>";
						html += "<span style=\"display:none;\">";
						html += "<select style=\"width: 120px;\" class=\"fl\"  name=\"btn_type1\" id=\"btn_type\">";
						html += "<option disabled=\"disabled\" value=\"WL\"";
						if(btn_type == "WL") { html += " selected" }
						html += ">웹링크</option>";
						html += "<option disabled=\"disabled\" value=\"AL\"";
						if(btn_type == "AL") { html += " selected" }
						html += ">앱링크</option>";
						html += "<option disabled=\"disabled\" value=\"BK\">봇키워드</option>";
						html += "<option disabled=\"disabled\" value=\"MD\">메시지전달</option>";
						html += "</select>";
						html += "<div style=\"overflow: hidden;\"><input type=\"text\" name=\"btn_name\" id=\"btn_name\" style=\"width:100%; margin-left: -1px;\" placeholder=\"버튼명을 입력해주세요\" readonly value=\"" + btn_name +"\">";
						html += "</div>";
						html += "</span>";
						if(btn_type == "WL") { //웹링크
							url_link1 = btn_content[i]["linkMo"];
							url_link2 = btn_content[i]["linkPc"];
							//html += "<div style=\"margin-top:10px;\"><label>Mobile</label></div>";
							//html += "<div class=\"input_link_wrap\">";
							//html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
							//html += "  <div style=\"overflow: hidden;\">";
							//html += "    <input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?=$psd_url?>\">";
							//html += "  </div>";
							//html += "</div>";
							html += "<div class=\"input_link_wrap\">";
							if(btn_count == 1){ //첫번째 버튼
								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview();\">"+ btn_name +"</button>"; //첫번째 버튼
								html += "  <div style=\"overflow: hidden;\">";
								html += "    <input type=\"url\" style=\"width:100%;background:#f2f2f2;\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"스마트전단 불러오기 버튼을 클릭하세요.\" value=\"<?=$psd_url?>\" readonly>";
								html += "  </div>";
							}else{
								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this, '"+ btn_count +"');\">"+ btn_name +"</button>"; //두번째~ 버튼
								html += "  <div style=\"overflow: hidden;\">";
								html += "    <input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?=$psd_url?>\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
								html += "  </div>";
							}
							html += "</div>";
							if(url_link2 != null && url_link2 != "") { //PC 버전
								html += "<span style=\"display:none;\">";
								html += "<div><label>PC</label></div>";
								html += "<div class=\"input_link_wrap\">";
								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
								html += "  <div style=\"overflow: hidden;\">";
								html += "    <input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url2\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?=$psd_url?>\">";
								html += "  </div>";
								html += "</div>";
								html += "</span>";
							}
						} else if(btn_type == "AL") {
							url_link1 = btn_content[i]["linkAnd"];
							url_link2 = btn_content[i]["linkIos"];
							html += "<div><label>Android</label></div>";
							html += "<div class=\"input_link_wrap\">";
							html += "<button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
							html += "<div style=\"overflow: hidden;\">";
							html += "<input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?=$psd_url?>\">";
							html += "</div>";
							html += "</div>";
							html += '<div <?if($rs->cc_idx) { echo "style=\"display:none\"";} ?>><label>iOS</label></div>';
							html += '<div class=\"input_link_wrap\" <?if($rs->cc_idx) { echo "style=\"display:none\"";} ?>>';
							html += "<button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
							html += "<div style=\"overflow: hidden;\">";
							html += "<input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url2\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?=$psd_url?>\">";
							html += "</div>";
							html += "</div>";
						}
						html += "</div>";
						html += "</div>";

					}
				}

				if ($("#field").html().trim() == "") {
					$("#field").html(html);
				} else {
					$("#field-data-" + (i - 1)).after(html);
				}
			}
			if ($("#field").html().trim() == "") {
				$("#field").html("버튼 링크가 없습니다.");
			}
		</script>
	</div>

	<div class="input_content_wrap planB">
				<label class="input_tit">
					2차발송 선택
				</label>
				<div class="input_content form_check">
					<!-- label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger"><i></i></label -->
					<?
					if($sendtelauth > 0) { ?>
					<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select"><i></i></label>
					<span class="noted">* 알림톡 실패건을 문자(SMS/LMS)로 재발송합니다.</span>
					<?
					} else {
						if ($mem1->mem_2nd_send=='NONE' || $mem1->mem_2nd_send=='') {
					?>
					<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>
					<span class="info_text">*2차 발신이 설정되지 않았습니다. 관리자에게 문의 하세요.</span>
					<?
						} else if ($sendtelauth == 0) { ?>
					<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>
					<span class="info_text">*발신번호가 등록되지 않아 2차 발신은 할 수 없습니다.</span>
					<?
						} else { ?>
					<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>
					<span class="info_text">*발신번호가 등록 및 2차 발신이 설정되지 않았습니다.</span>
					<?
						}
					} ?>
					<div class="switch_content" id="hidden_fields_sms" style="display: none;">
						<div class="sms_preview toggle">
							<div class="msg_type sms"></div>
							<div class="preview_circle"></div>
							<div class="preview_round"></div>
							<div class="preview_msg_wrap">
								<div class="preview_msg_window lms" id="lms_preview"></div>
							</div>
							<div class="preview_home"></div>
						</div>
						<div class="msg_box">
							<?php // 문자 내용 헤드, 풋 수정 2019-07-25 ?>
							<!-- div class="txt_ad" id="lms_header"></div-->
							<div class="txt_ad" id="lms_header">
								<span id="span_adv">(광고)</span>
								<input type="text" class="input_option" style="width: 320px;" id="companyName" onkeyup="onPreviewLms(); chkword_lms();">
							</div>
							<?
								$lms_base_msg = "";
								if($rs->tpl_id == $bt1_tpl_id or $tpl->tpl_id == $bt2_tpl_id){ //링크버튼형
									$lms_base_msg = "행사보기\n". str_replace('http://', '',$psd_url);
								}
							?>
							<textarea class="input_msg" id="lms" name="lms" placeholder="'입력' 또는 '알림톡 내용 가져오기' 버튼을 클릭하세요." onkeyup="onPreviewLms();chkword_lms();"><?=$lms_base_msg?></textarea>
							<span class="txt_byte"><span class="msg_type_txt sms" id="lms_limit_str"></span><span id="lms_num">0</span>/<span id="lms_character_limit">2000</span> bytes</span>
							<!--div class="txt_ad" id="lms_footer"></div-->
							<div class="txt_ad" id="lms_footer">
								<p id="kakotalk_link_text"></p>
								<span id="span_unsubscribe">무료수신거부 : </span><input type="text" class="input_option" id="unsubscribe_num" onkeyup="onPreviewLms(); chkword_lms();">
							</div>
						</div>
						<!--<input type="checkbox" id="lms_kakaolink_select"  class="input_cb" onclick="insert_kakao_link(this);" style="cursor: default;"><label for="lms_kakaolink_select">카카오 친구추가</label>-->
						<button class="btn md fr" onclick="getArimtalkCont();">알림톡내용 가져오기</button>
						<button class="btn md fr btn_emoji" style="margin-right:5px;">특수문자</button>
						<div class="toggle_layer_wrap toggle_layer_emoji">
							<h3>특수문자<i class="material-icons icon_close fr" id="icon_close">close</i></h3>
							<div class="layer_content emoticon_wrap clearfix">
								<ul>
									<li onclick="insert_char('☆', 'lms')">☆</li>
									<li onclick="insert_char('★', 'lms')">★</li>
									<li onclick="insert_char('○', 'lms')">○</li>
									<li onclick="insert_char('●', 'lms')">●</li>
									<li onclick="insert_char('◎', 'lms')">◎</li>
									<li onclick="insert_char('⊙', 'lms')">⊙</li>
									<li onclick="insert_char('◇', 'lms')">◇</li>
									<li onclick="insert_char('◆', 'lms')">◆</li>
									<li onclick="insert_char('□', 'lms')">□</li>
									<li onclick="insert_char('■', 'lms')">■</li>
									<li onclick="insert_char('▣', 'lms')">▣</li>
									<li onclick="insert_char('△', 'lms')">△</li>
									<li onclick="insert_char('▲', 'lms')">▲</li>
									<li onclick="insert_char('▽', 'lms')">▽</li>
									<li onclick="insert_char('▼', 'lms')">▼</li>
									<li onclick="insert_char('◁', 'lms')">◁</li>
									<li onclick="insert_char('◀', 'lms')">◀</li>
									<li onclick="insert_char('▷', 'lms')">▷</li>
									<li onclick="insert_char('▶', 'lms')">▶</li>
									<li onclick="insert_char('♡', 'lms')">♡</li>
									<li onclick="insert_char('♥', 'lms')">♥</li>
									<li onclick="insert_char('♧', 'lms')">♧</li>
									<li onclick="insert_char('♣', 'lms')">♣</li>
									<li onclick="insert_char('◐', 'lms')">◐</li>
									<li onclick="insert_char('◑', 'lms')">◑</li>
									<li onclick="insert_char('▦', 'lms')">▦</li>
									<li onclick="insert_char('☎', 'lms')">☎</li>
									<li onclick="insert_char('♪', 'lms')">♪</li>
									<li onclick="insert_char('♬', 'lms')">♬</li>
									<li onclick="insert_char('※', 'lms')">※</li>
									<li onclick="insert_char('＃', 'lms')">＃</li>
									<li onclick="insert_char('＠', 'lms')">＠</li>
									<li onclick="insert_char('＆', 'lms')">＆</li>
									<li onclick="insert_char('㉿', 'lms')">㉿</li>
									<li onclick="insert_char('™', 'lms')">™</li>
									<li onclick="insert_char('℡', 'lms')">℡</li>
									<li onclick="insert_char('Σ', 'lms')">Σ</li>
									<li onclick="insert_char('Δ', 'lms')">Δ</li>
									<li onclick="insert_char('♂', 'lms')">♂</li>
									<li onclick="insert_char('♀', 'lms')">♀</li>
								</ul>
							</div>
							<div class="layer_content emoticon_wrap_multi clearfix">
								<ul>
									<li onclick="insert_char('^0^', 'lms')">^0^</li>
									<li onclick="insert_char('☆(~.^)/', 'lms')">☆(~.^)/</li>
									<li onclick="insert_char('づ^0^)づ', 'lms')">づ^0^)づ</li>
									<li onclick="insert_char('(*^.^)♂', 'lms')">(*^.^)♂</li>
									<li onclick="insert_char('(o^^)o', 'lms')">(o^^)o</li>
									<li onclick="insert_char('o(^^o)', 'lms')">o(^^o)</li>
									<li onclick="insert_char('=◑.◐=', 'lms')">=◑.◐=</li>
									<li onclick="insert_char('_(≥∇≤)ノ', 'lms')">_(≥∇≤)ノ</li>
									<li onclick="insert_char('q⊙.⊙p', 'lms')">q⊙.⊙p</li>
									<li onclick="insert_char('^.^', 'lms')">^.^</li>
									<li onclick="insert_char('(^.^)V', 'lms')">(^.^)V</li>
									<li onclick="insert_char('*^^*', 'lms')">*^^*</li>
									<li onclick="insert_char('^o^~~♬', 'lms')">^o^~~♬</li>
									<li onclick="insert_char('^.~', 'lms')">^.~</li>
									<li onclick="insert_char('S(*^___^*)S', 'lms')">S(*^___^*)S</li>
									<li onclick="insert_char('^Δ^', 'lms')">^Δ^</li>
									<li onclick="insert_char('＼(*^▽^*)ノ', 'lms')">＼(*^▽^*)ノ</li>
									<li onclick="insert_char('^L^', 'lms')">^L^</li>
									<li onclick="insert_char('^ε^', 'lms')">^ε^</li>
									<li onclick="insert_char('^_^', 'lms')">^_^</li>
									<li onclick="insert_char('(ノ^O^)ノ', 'lms')">(ノ^O^)ノ</li>
								</ul>
							</div>
						</div>

						<div class="clearfix"></div>
					</div>
				</div>
	</div>
	<script >
		$(".btn_emoji").click(function () {
			$(".toggle_layer_emoji").slideToggle("fast");
		});

		$(".btn_emoticon").click(function () {
		$(".toggle_layer_emoticon").slideToggle("fast");
		});

		$(".icon_close").click(function () {
			$(this).parent().parent(".toggle_layer_wrap").slideToggle("fast")
		});
	</script>
<?} else {?>
	<div class="input_content_wrap">
			<label class="input_tit">템플릿 선택</label>
			<div class="input_content">
				<div class="nodata">
				<i class="material-icons icon_error">error_outline</i>
				<p>선택된 템플릿이 없습니다. 템플릿을 선택해 주세요.</p>
				<button class="btn md yellow plus"	onclick="btnSelectTemplate();" id="modalBtn">템플릿 선택하기</button>
				</div>
			</div>
	</div>
<?}?>
<script>
	//쿠폰 미리보기 - 이미지 2019.08.08 쿠폰 관련
	function readImage(selctImgValue) {
		$("#image").remove();
		var image = "<img id='image' name='image' src='" + selctImgValue + "' style='width:100%; margin-bottom:5px;'/>";
		var text_temp = $("#text").html();

		$("#text").html(image + text_temp);


	// 	$("#image").remove();
	// 	var image = "<img id='image' name='image' src='" + selctImgValue + "' style='width:100%; margin-bottom:5px;'/>";

	// 	var cont_length = $("#templi_cont").val().length;
	// 	var span = "<span id='type_num'>" + cont_length + "</span>";
	// 	//document.getElementById('templi_length').innerHTML = span + "/400자";
	// 	$("#text").val($("#templi_cont").val());

	// 	$('.uniform').uniform();
	// 	$("#img_link").attr("readonly", false);
	// 	$("#text").before(image);
	// 	$("#friendtalk_table tr").each(function () {
	// 		if($(this).find("#no").text()) {
	// 			var current_btn_num = $(this).find("#no").parent().attr("name");
	// 			link_name(document.getElementById("btn_type"+current_btn_num),current_btn_num);
	// 		}
	// 	});
		//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
	}

	// 쿠폰 미리보기 - 버튼링크
	function re_link_preview(){
		<?if($rs) {?>
		var buttons = '<?=$rs->tpl_button?>';
		var btn = buttons.replace(/&quot;/gi, "\"");
		var btn_content = JSON.parse(btn);
		for (var i = 0; i < btn_content.length; i++) {
		  var no = i + 1;
		  var p = '<div id="btn_preview' + no + '" style="height: 200px;padding-bottom: 10px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">' +
					 '<p data-always-visible="1" data-rail-visible="0" cols="20" readonly="readonly" ' +
					 'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"' +
					 '>직원확인</p></div>';
		  if (i == 0) {
			  var previewText = $("#text").html() + "<br><br>" + p;
			  $("#text").html(previewText);
		  } else {
			  $("#btn_preview" + i).after(p);
		  }
		}

	// 	$("#text").css("margin-bottom", "10px");

	//	var buttons = '<?=$rs->tpl_button?>';
	// 	var btn = buttons.replace(/&quot;/gi, "\"");
	// 	var btn_content = JSON.parse(btn);

	// 	var title = "<div id='pre_title' style='padding-bottom: 10px;'><strong style='margin-top: 1px;display: block; padding: 2px 9px; border: 1px solid transparent; font-weight: normal; font-size: 16px; color: rgb(136,136,136);'>";
	// 	    title = title + $("#id_cc_title").val() + "</strong>";
	// 	    title = title +	"<li style='padding-bottom: 10px;display: block;padding: 0 10px;color: rgb(46,172,188);font-size: 12px;'>사용기간 : ";
	// 	    title = title +	$("#id_cc_end_date").val() + "까지</li></div>";

	// 	$("#text").before(title);

	// 	for (var i = 0; i < btn_content.length; i++) {
	// 	  var no = i + 1;
	// 	  var p = '<div id="btn_preview' + no + '" style="height: 200px;padding-bottom: 10px; border:1px solid !important; border-color: #e8e8e8 !important; height:40px; margin-top:-1px !important;">' +
	// 				 '<p data-always-visible="1" data-rail-visible="0" cols="20" readonly="readonly" ' +
	// 				 'style="text-align: center !important; padding-top:10px !important; color: #5bc0de; overflow:hidden;border:0;background-color:white;resize:none;cursor:default;"' +
	// 				 '>직원확인</p></div>';

	// 	  if (i == 0) {
	// 			$("#text").before(p);
	// 	  } else {
	// 			$("#btn_preview" + i).after(p);
	// 	  }
	// 	}
		<?}?>
	}

	//쿠폰 미리보기 - 템플릿내용
	function re_templi_preview(obj) {
		<?if($rs) {?>
		var returnTempli_cont = obj.value;
		returnTempli_cont = replaceKakaoEmoticon(returnTempli_cont, 20);

		var title = "<div id='pre_title' style='padding-bottom: 10px;'><strong style='margin-top: 1px;display: block; padding: 2px 9px; border: 1px solid transparent; font-weight: normal; font-size: 16px; color: rgb(136,136,136);'>";
		title = title + $("#id_cc_title").val() + "</strong>";
		title = title +	"<li style='padding-bottom: 10px;display: block;padding: 0 10px;color: rgb(46,172,188);font-size: 12px;'>사용기간 : ";
		title = title +	$("#id_cc_end_date").val() + "까지</li></div>";

		$("#text").html(title + returnTempli_cont);

		//alert(obj.value);
	// 	$("#text").val(obj.value);
	// 	var height = $("#text").prop("scrollHeight");
	// 	if (height < 468) {
	// 		$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
	// 	} else {
	// 		var message = $("#text").val();
	// 		var height = $("#text").prop("scrollHeight");
	// 		if (message == "") {
	// 			$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
	// 		} else {
	// 			$("#text").css("height", "1px").css("height", ($("#text").prop("scrollHeight")) + "px");
	// 			$("#templi_cont").keyup(function() {
	// 				//$(".scroller").scrollTop($(".scroller")[0].scrollHeight);
	// 			});
	// 		}
	// 	}
		<?}?>
	}

	document.getElementById("body_top").scrollIntoView(); //TOP 위치로 스크롤 이동
</script>
