<?if($rs->tpl_profile_key) {?>
<input type="hidden" id="pf_ynm" value="<?=$mem1->mem_username?>"/>
<input type="hidden" id="sms_sender" value="<?=$rs1->spf_sms_callback?>"/>
<!-- 20190107 이수환 추가 작업중인 아디의 링크버튼 명을 입력-->
<input type="hidden" name="find_linkbtn_name" id="find_linkbtn_name" value="<?=$rs->mem_linkbtn_name?>">
<input type="hidden" name="pf_key" id="pf_key" value="<?=$rs->tpl_profile_key?>">
 <input type="hidden" name="pf_yid" id="pf_yid" value="<?=$rs->spf_friend?>">
 <input type="hidden" name="templi_code" id="templi_code" value="<?=$rs->tpl_code?>">
 <input type="hidden" name="sbid" id="sbid" value="<?=$param['sb_id']?>">
 <input type="hidden" name="kind" id="kind" value="L">
 <input type="hidden" name="btn_name">
 <input type="hidden" name="btn_url">
 <input type="hidden" name="couponid" id="couponid"  value="<?=$rs->cc_idx?>">
<div class="input_content_wrap" style="display:none;">
	<label class="input_tit">템플릿 코드</label>
	<div class="input_content">
		<?=$rs->tpl_code?>
	</div>
</div>
<div class="input_content_wrap" style="display:none;">
	<label class="input_tit">템플릿명</label>
	<div class="input_content">
		<?=$rs->tpl_name?>
	</div>
</div>
<div class="input_content_wrap">
	<label class="input_tit">쿠폰내용</label>
	<div class="input_content">
		<div class="msg_box">
			<!-- div class="txt_ad" id="cont_header">(광고) 대형네트웍스</div -->
			<textarea style="display:none" name="dumy_templi_cont" id="dumy_templi_cont" cols="30" rows="5" class="form-control autosize" placeholder="템플릿 내용을 입력해주세요." style="resize:none;cursor:default;"
					onkeyup="javascript: var file = document.getElementById('filecount').value;
							 if (file==''){templi_preview(this); scroll_prevent(); templi_chk();}"
					value="<?=$rs->tpl_contents?>"><?=$rs->tpl_contents?></textarea>
			<div class="input_msg" id="templi_cont" style="height: auto;">
			<?
				$tmp_str = nl2br($rs->tpl_contents);
				$pattern = '/\#{[^}]*}/';
				$m_cnt = preg_match_all($pattern, $tmp_str, $match );
				//echo "m_cnt : ". $m_cnt ."<br>";
				$idx = 0;
				$m_idx = 0;
				while($m_cnt >= 1) {
					$var_value = "";
					if(!empty($varvalue)) {
						$var_value = $varvalue[$idx]->var_text;
					}
					$pla = $match[0][$idx];
					$variable = '/\\'.$match[0][$idx].'/';
					$var_str = '<input name="var_name[]" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="'.str_replace("#", "", $pla).'" value="'.$var_value.'">';
					//log_message("ERROR", $pla."/".$var_str);
					$tmp_str= preg_replace($variable,$var_str,$tmp_str, 1);
					$m_cnt = $m_cnt - 1;
					$idx = $idx + 1;
					//log_message("ERROR", $tmp_str);
				}
				echo $tmp_str;
			?>
			</div>
			<span class="txt_byte"><p class="info_text fl">"#{변수}" 부분의 내용만 수정이 가능합니다.</p><span id="type_num">0</span>/1,000 자</span>
			<!-- div class="txt_ad">수신거부 : 홈>친구차단</div-->
		</div>
	</div>
	 <div class="mobile_preview">
		<div class="preview_circle"></div>
		<div class="preview_round"></div>
		<div class="preview_msg_wrap">
			<?if($rs->cc_idx) { // 2019.08.08. 쿠폰 발송 관련 시작?>
			<ul class="preview_tab">
				<li class="btn_tab active" id="msg_save_temp" onclick="view_preview();">응모페이지</li>
				<li class="btn_tab" id="open_msg_list_" onclick="view_result();">당첨페이지</li>
			</ul>
			<? } // 2019.08.08. 쿠폰 발송 관련 끝 ?>
			<div class="preview_msg_window" style="height: auto;!important;">
				<div class="entry" style="display:">
					<div class="preview_box_profile">
						<span class="profile_thumb">
							<img src="/img/icon/icon_profile.jpg">
						</span>
						<span class="profile_text"><?=$mem1->mem_username?></span>
					</div>
					<div class="preview_box_msg" id="text"></div>
				</div>
				<div class="draw" style="display:none;"><?//당첨페이지 미리보기 영력?>
					<div class="preview_img_coupon" id="draw_image"></div><?//이미지?>
					<div class="preview_text_box" id="draw_text"></div><?//당첨내용?>
				</div>
			</div>
		</div>
		<div class="preview_home"></div>
	</div><!-- mobile_preview END -->
	<div class="align-right mt10" <?if($rs->cc_idx) { echo "style='display:none'";} ?>>
	<button class="btn md btn-custom" type="button" onclick="btnSelectTemplate();" id="modalBtn"><i class="icon-ok"></i> 쿠폰변경</button>
	</div>
</div>
<div class="input_content_wrap" style="display:none;">
	<label class="input_tit">링크 버튼</label>
	<div id="field"></div>
	<script type="text/javascript">
		var buttons = '<?=$rs->tpl_button?>';
		var btn = buttons.replace(/&quot;/gi, "\"");
		var btn_content = JSON.parse(btn);
		var url_name1 = '';
		var url_name2 = '';
		var url_link1 = '';
		var url_link2 = '';

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
				html += "<div class=\"input_content\" id=\"field-data-" + i + "\" name=\"field-data\">";
				html += "<div class=\"input_link_wrap\" style=\"display:none;\">";
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
				html += "<input type=\"text\" name=\"btn_name\" id=\"btn_name\" style=\"flex:1; margin-left: -1px;\" placeholder=\"버튼명을 입력해주세요\" readonly value=\"" + btn_name;
				html += "\">";
				html += "</div>";
				html += "</div>";
			} else if (btn_type == "WL" || btn_type == "AL") {
				if (btn_name != "") {
					html += "<div class=\"input_content\" id=\"field-data-" + i + "\" name=\"field-data\">";
					html += "<div class=\"input_link_wrap\" style=\"display:none;\">";
					html += "<input id=\"btn_type\" name=\"btn_type\" type=\"hidden\" value=\"" + btn_type + "\">"
					html += "<select style=\"width: 120px;\" name=\"btn_type1\" id=\"btn_type\">"
					html += "<option disabled=\"disabled\" value=\"WL\"";
					if(btn_type == "WL") { html += " selected" }
					html += ">웹링크</option>";
					html += "<option disabled=\"disabled\" value=\"AL\"";
					if(btn_type == "AL") { html += " selected" }
					html += ">앱링크</option>";
					html += "<option disabled=\"disabled\" value=\"BK\">봇키워드</option>";
					html += "<option disabled=\"disabled\" value=\"MD\">메시지전달</option>";
					html += "</select>";
					html += "<input type=\"text\" name=\"btn_name\" id=\"btn_name\" style=\"flex:1; margin-left: -1px;\" placeholder=\"버튼명을 입력해주세요\" readonly value=\"" + btn_name;
					html += "\">";
					html += "</div>";
					if(btn_type == "WL") {
						<?if($rs->cc_idx) { // 2019.08.08. 쿠폰 발송 관련 시작?>
							//url_link1 = btn_content[i]["linkMo"];
							//url_link2 = btn_content[i]["linkPc"];
							url_link1 = url_link2 = "<?= $coupon_url ?>";

							html += "<div class=\"input_link_wrap\">";
							html += "<span class=\"btn_link_label\" id=\"find_url\" onclick=\"urlConfirm(this);\">" + btn_name + "</span>";
							html += "<input type=\"url\" style=\"flex:1\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"" + url_link1 + "\" readonly>";
							//html += "<button class=\"btn md linked\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\"></button>";
							html += "</div>";
							if(url_link2 != null && url_link2 != "") {
								html += "<div class=\"input_link_wrap\" style=\"display:none;\">";
								html += "<span class=\"btn_link_label\" id=\"find_url\" onclick=\"urlConfirm(this);\">" + btn_name + "(PC)</span>";
								html += "<input type=\"url\" style=\"flex:1;\" id=\"btn_url\" name=\"btn_url2\" placeholder=\"링크주소를 입력해주세요.\" value=\"" + url_link2 + "\" readonly>";
								//html += "<button class=\"btn md dark\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
								html += "</div>";
							}
						<? } else {?>
						url_link1 = btn_content[i]["linkMo"];
						url_link2 = btn_content[i]["linkPc"];
						html += "<div class=\"input_link_wrap\">";
						html += "<span class=\"btn_link_label\">Mobile</span>";
						html += "<input type=\"url\" class=\"\" style=\"flex:1;\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"" + url_link1 + "\">";
						html += "<button class=\"btn md dark\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
						html += "</div>";
						if(url_link2 != null && url_link2 != "") {
							html += "<div class=\"input_link_wrap\">";
							html += "<span class=\"btn_link_label\">PC</span>";
							html += "<input type=\"url\" class=\"fr\" style=\"width:85%;\" id=\"btn_url\" name=\"btn_url2\" placeholder=\"링크주소를 입력해주세요.\" value=\"" + url_link2 + "\">";
							html += "<button class=\"btn md dark\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
							html += "</div>";
						}
						<? } ?>
					} else if(btn_type == "AL") {
						url_link1 = btn_content[i]["linkAnd"];
						url_link2 = btn_content[i]["linkIos"];
						html += "<div class=\"input_link_wrap\">";
						html += "<span class=\"btn_link_label\">Android</span>";
						html += "<button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
						html += "<div style=\"overflow: hidden;\">";
						html += "<input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"" + url_link1 + "\">";
						html += "</div>";
						html += "</div>";
						html += '<div <?if($rs->cc_idx) { echo "style=\"display:none\"";} ?>></div>';
						html += '<div class=\"input_link_wrap\" <?if($rs->cc_idx) { echo "style=\"display:none\"";} ?>>';
						html += "<span class=\"btn_link_label\">iOS</span>";
						html += "<button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
						html += "<div style=\"overflow: hidden;\">";
						html += "<input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url2\" placeholder=\"링크주소를 입력해주세요.\" value=\"" + url_link2 + "\">";
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
		if ($("#field").html().trim() == "") { $("#field").html("버튼 링크가 없습니다."); };
	</script>
	</div>
</div>
<?if($rs->cc_idx) { // 2019.08.08. 쿠폰 발송 관련 시작?>
<div class="input_content_wrap">
	<label class="input_tit">쿠폰명</label>
	<div class="input_content">
		<?=$rs->cc_title?>
		<input class="form-control input-width-large required" style="cursor:default;display:none" id="id_cc_title" name="cc_title" size = "60" value="<?=$rs->cc_title?>" readonly>
	</div>
</div>
<div class="input_content_wrap">
	<label class="input_tit">발급개수</label>
	<div class="input_content">
		<?=$rs->cc_coupon_qty?> 개
		<!-- input class="form-control input-width-small required " id="id_cc_coupon_qty" name="cc_coupon_qty" type="number" value="<?=$rs->cc_coupon_qty?>" readonly><span> 개 </span-->
	</div>
</div>
<div class="input_content_wrap">
	<label class="input_tit">기간</label>
	<div class="input_content">
		<?=$rs->cc_start_date?><!--input style="display: none;" id="id_cc_start_date" name="cc_start_date" value="<?=$rs->cc_start_date?>" readonly--> ~ <?=$rs->cc_end_date?><!--input class="" style="display:none" id="id_cc_end_date" name="cc_end_date" value="<?=$rs->cc_end_date?>" readonly-->
	</div>
</div>
<!--div class="input_content_wrap">
	<label class="input_tit">당첨페이지 이미지</label>
	<div class="input_content">
		<?=$rs->cc_img_url1?>
		<input name="cp_img_link" id="cp_img_link" value="<?=$rs->cc_img_url1?>" class="form-control required" style="cursor:default;display:none" size="50" placeholder="이미지 링크 주소를 입력해주세요." readonly>
	</div>
</div-->
<div class="input_content_wrap" style="display:none;">
	<label class="input_tit">당첨페이지</label>
	<div class="input_content">
		<input name="cp_img_link" id="cp_img_link" value="<?=$rs->cc_img_url1?>" style="margin-bottom: 5px; width: 100%;" size="50" placeholder="이미지 링크 주소" readonly>
		<div class="msg_box">
			<textarea name="cc_memo" id="id_cc_memo" cols="20" rows="10" class="form-control autosize" placeholder="당첨 페이지 텍스트를 입력 하세요" style="cursor:default;display:none" readonly><?=$rs->cc_memo?></textarea>
			<div class="input_msg"><?=str_replace("\n", "<br>", $rs->cc_memo)?></div>
		</div>
	</div>
</div>
<? } // 2019.08.08. 쿠폰 발송 관련 끝 ?>
<div class="input_content_wrap planB" style="display:none">
	<label class="input_tit">2차발송</label>
	<div class="input_content">
		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger"><i></i></label>
		<?
		if($sendtelauth > 0) { ?>
		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select"><i></i></label>
		<span class="info_text">*문자(SMS/LMS)를 2차발송합니다.</span>
		<?
		} else {
			if ($mem1->mem_2nd_send=='NONE' || $mem1->mem_2nd_send=='') {
		?>
		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>
		<span class="info_text">*2차 발신이 설정되지 않았습니다. 관리자에게 문의 하세요.</span>
		<?
			} else if ($sendtelauth == 0) {   ?>
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
				<div class="msg_type lms"></div>
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
				<textarea class="input_msg" id="lms" name="lms" placeholder="'입력' 또는 '알림톡 내용 가져오기' 버튼을 클릭하세요." onkeyup="onPreviewLms();chkword_lms();"></textarea>
				<span class="txt_byte"><span class="msg_type_txt lms" id="lms_limit_str"></span><span id="lms_num">0</span>/<span id="lms_character_limit">2000</span> bytes</span>
				<!--div class="txt_ad" id="lms_footer"></div-->
				<div class="txt_ad" id="lms_footer">
					<p id="kakotalk_link_text"></p>
					<span id="span_unsubscribe">무료수신거부 : </span><input type="text" class="input_option" id="unsubscribe_num" onkeyup="onPreviewLms(); chkword_lms();">
				</div>
			</div>
			<input type="checkbox" id="lms_kakaolink_select"  class="input_cb" onclick="insert_kakao_link(this);" style="cursor: default;"><label for="lms_kakaolink_select">카카오 친구추가 주소 삽입</label>
			<button class="btn md fr" onclick="getArimtalkCont();">알림톡내용 가져오기</button>
			<button class="btn md fr btn_emoji">특수문자</button>

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
		<label class="input_tit">쿠폰 선택</label>
		<div class="input_content">
			<div class="nodata">
			<i class="material-icons icon_error">error_outline</i>
			<p>선택된 쿠폰이 없습니다. 쿠폰을 선택해 주세요.</p>
			<button class="btn md yellow plus"	onclick="btnSelectTemplate();" id="modalBtn">쿠폰 선택하기</button>
			</div>
		</div>
</div>
<?}?>

<script>

//쿠폰 미리보기 - 이미지 2019.08.08 쿠폰 관련
function readImage(selctImgValue) {
	//alert("selctImgValue : "+ selctImgValue);
	$("#image").remove();
	var image = "<img id='image' name='image' src='" + selctImgValue + "''/>";
	//var text_temp = $("#draw_text").html();
	//alert("text_temp : "+ text_temp);
	//$("#draw_text").html(image + text_temp);
	$("#draw_image").html(image);
}

// 쿠폰 미리보기 - 버튼링크
function re_link_preview() {
	<?if($rs) {?>
	var returnTempli_cont = $("#id_cc_memo").val().replace(/(\n|\r\n)/g, '<br>');
	var buttons = '<?=$rs->tpl_button?>';
	var btn = buttons.replace(/&quot;/gi, "\"");
	var btn_content = JSON.parse(btn);
	for (var i = 0; i < btn_content.length; i++) {
	  var no = i + 1;
	  var p = '<div id="btn_preview' + no + '"class="btn_staff">' +
				 '<p data-always-visible="1" data-rail-visible="0" cols="20" readonly="readonly">직원확인</p></div>';
	  if (i == 0) {
		  var previewText = $("#draw_text").html() + "" + p + "";
		  $("#draw_text").html(previewText + '<div class="preview_coupon">'+ returnTempli_cont +'</div>');
	  } else {
		  //$("#btn_preview" + i).after(p);
	  }
	}
	<?}?>
}

//쿠폰 미리보기 - 템플릿내용
function re_templi_preview(obj) {
	<?if($rs) {?>
	var returnTempli_cont = "";
	var title = "<p class='preview_title'>";
    title = title + $("#id_cc_title").val() + "</p>";
    title = title +	"<p class='preview_date'>사용기간 : <?=$rs->cc_end_date?> 까지</p>";
    title = title + '<div id="btn_preview" class="btn_staff_ch"><p data-always-visible="1" data-rail-visible="0" cols="20" readonly="readonly">채널 추가하기</p></div>';
	$("#draw_text").html(title + returnTempli_cont);
	<?}?>
}
</script>
