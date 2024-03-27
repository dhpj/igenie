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
	<label class="input_tit">발송내용</label>
	<div class="input_content">
		<div class="msg_box">
			<!-- div class="txt_ad" id="cont_header">(광고) 대형네트웍스</div -->
			<textarea style="display:none" name="dumy_templi_cont" id="dumy_templi_cont" cols="30" rows="5" class="form-control autosize" placeholder="템플릿 내용을 입력해주세요." style="resize:none;cursor:default;"
					onkeyup="javascript: var file = document.getElementById('filecount').value;
							 if (file==''){templi_preview(this); scroll_prevent(); templi_chk();}"
					value="<?=$rs->tpl_contents?>"><?=$rs->tpl_contents?></textarea>
			<div class="input_msg" id="templi_cont" style="height: auto;">
			<?
				/*$tmp_str = nl2br($rs->tpl_contents);
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
				echo $tmp_str;*/
			?>
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
				        $var_value = $varvalue[$idx]->var_text;
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > var_value[". $idx ."] : ". $var_value);
				    }
				    //log_message("ERROR", "cnt : ".sizeof($varvalue)."/ value : ".$var_value);
				    $pla =  $match[0][$idx];
				    $variable = '/\\'.$match[0][$idx].'/';
				    if($pla == '#{개인정보동의내용}') {
				        $var_str = '<textarea name="var_name" class="var multi" onkeyup="return view_preview();" placeholder="'.str_replace("#", "", $pla).'">'.$var_value.'</textarea>';
				    } else if($pla == '#{업체명}') {
				        if(empty($var_value)) {
				            $var_value = $this->member->item('mem_username');
				        }
				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="'.str_replace("#", "", $pla).'">'.$var_value.'</textarea>';
				    } else if($pla == '#{업체전화번호}' || $pla == '#{전화번호}') {
						if(empty($var_value)) {
				            //$var_value = $this->Biz_dhn_model->add_hyphen($this->member->item('mem_phone'));
				            $var_value = $this->funn->format_phone($this->member->item('mem_phone'), "-");
				        }
				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="'.str_replace("#", "", $pla).'">'.$var_value.'</textarea>';
				    } else {
				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="'.str_replace("#", "", $pla).'">'.$var_value.'</textarea>';
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
			<span class="txt_byte"><p class="info_text fl">"노란 박스 영역(변수값)의 내용만 입력/수정이 가능합니다.</p><span id="type_num">0</span>/1,000 자</span>
			<!-- div class="txt_ad">수신거부 : 홈>친구차단</div-->
		</div>
	</div>
	 <div class="mobile_preview">
		<div class="preview_circle"></div>
		<div class="preview_round"></div>
		<div class="preview_msg_wrap">
			<ul class="preview_tab">
				<li class="btn_tab active" id="msg_save_temp" onclick="view_preview();">미리보기</li>
				<li class="btn_tab" id="open_msg_list_" onclick="view_result();">자세히 보러가기</li>
			</ul>
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
				<div class="draw" style="display:none; background-color:#fff;"><?//자세히 보러가기 미리보기 영력?>
					<div class="preview_img_box" id="draw_text"></div><?//자세히 보러가기 이미지 영력?>
				</div>
			</div>
		</div>
		<div class="preview_home"></div>
	</div><!-- mobile_preview END -->
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
						url_link1 = url_link2 = "<?= $agree_url ?>";
						//url_link2 = btn_content[i]["linkPc"];
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
<div class="input_content_wrap" id="div_imgfile">
	<label class="input_tit">개인정보동의 이미지</label>
	<div class="input_content">
    <label for="imgfile">
      <input id="imgfile_link" value="" style="width:300px;" readonly>
	  <label class="btn md fr" for="imgfile" style="cursor:pointer; line-height:34px;"><i class="icon-folder-open"></i>이미지 선택</label>
    </label>
    <input type="file" id="imgfile" onchange="chg_imgfile(this);" accept=".jpg, .jepg, .png, .gif" style="display:none;">
	</div>
</div>
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
		<label class="input_tit">템플릿 선택</label>
		<div class="input_content">
			<div class="nodata">
			<i class="material-icons icon_error">error_outline</i>
			<p>템플릿을 선택해 주세요.</p>
			<button class="btn md yellow plus"	onclick="btnSelectTemplate();" id="modalBtn">템플릿 선택하기</button>
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

	// 미리보기 - 버튼링크
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
			  $("#draw_text").html(previewText + returnTempli_cont);
		  } else {
			  //$("#btn_preview" + i).after(p);
		  }
		}
		<?}?>
	}

	//자세히 보러가기
	function re_templi_preview(imgpath){
		<?if($rs) {?>
		var returnTempli_cont = "";
		var title = "";
		//alert("re_templi_preview > imgpath : "+ imgpath);
		if(imgpath != ""){
			title = title +	"<img src='"+ imgpath +"'>";
		}
		title = title + '<div id="btn_preview" class="btn_staff"><p style="margin:20px 0;" data-always-visible="1" data-rail-visible="0" cols="20" readonly="readonly">동의하기</p></div>';
		$("#draw_text").html(title + returnTempli_cont);
		<?}?>
	}

	//파일 사이즈 반환 (파일사이즈, 소수점)
	function jsFileSize(fileSize, decimal){
		var size = "";
		if(fileSize > (1024*1024*1024)){
			size = (fileSize / (1024*1024*1024)).toFixed(decimal) +"GB";
		}else if(fileSize > (1024*1024)){
			size = (fileSize / (1024*1024)).toFixed(decimal) +"MB";
		}else if(fileSize > 1024){
			size = (fileSize / 1024).toFixed(decimal) +"KB";
		}else{
			size = (fileSize / 0.1024) +"byte";
		}
		return size;
	}

	//파일 초기화
	function jsFileRemove(input){
		var ieyn = jsIeyn(); //ie 여부
		if (ieyn == "Y"){ //ie 일때
			$(input).replaceWith( $(input).clone(true) ); //파일 초기화
		}else{
			$(input).val(""); //파일 초기화
		}
	}

	//개인정보동의 이미지 클릭시
	function chg_imgfile(input){
		if(input.value.length > 0) {
			$("#agreeid").val(""); //개인정보수신동의번호
			//alert("input.value : "+ input.value); return;
  			var ext = input.value.split('.').pop().toLowerCase(); //파일 확장자
			//alert("ext : "+ ext); return;
  			if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
				jsFileRemove(input); //파일 초기화
				alert("해당 파일은 이미지 파일이 아닙니다.\n(이미지 파일 : jpg, jpeg, png, gif)");
				return;
			}else{
				if (input.files && input.files[0]){
					var fileSize = input.files[0].size; //파일사이즈
					var maxSize = "<?=$upload_max_size?>"; //파일 제한 사이지(byte)
					//alert("fileSize : "+ fileSize +", maxSize : "+ maxSize);
					if(maxSize < fileSize){
						jsFileRemove(input); //파일 초기화
						//$("#"+ imgid).attr("src", "");
						//$("#pre_"+ imgid).attr("src", "");
						alert("첨부파일 사이즈는 "+ jsFileSize(maxSize,0) +" 이내로 등록 가능합니다.\n\n현재파일 사이즈는 "+ jsFileSize(fileSize,1) +" 입니다.");
						return;
					}
					//alert("첨부파일 사이즈 체크 완료"); return;

					var formData = new FormData();
					formData.append("imgfile", input.files[0]); //이미지
					formData.append("uppath", "<?=$upload_path?>"); //업로드경로
					formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
					$.ajax({
						url: "/biz/sender/send/select_agree/imgfile_save",
						type: "POST",
						data: formData,
						processData: false,
						contentType: false,
						success: function (json) {
							//alert("json.code : "+ json.code);
							if(json.agreeid != "" && json.imgpath != "") { //성공
								//jsFileRemove(input); //파일 초기화
								var agreeid = json.agreeid; //개인정보수신동의번호
								$("#agreeid").val(agreeid); //개인정보수신동의번호
								var imgpath = json.imgpath; //이미지 경로
								$("#agreepath").val(imgpath); //이미지 경로
								$("#imgfile_link").val("http://<?=$_SERVER["HTTP_HOST"]?>"+ imgpath); //이미지 경로 표기
								view_result(); //개인정보동의 자세히 보러가기
								return;
							}else{
								jsFileRemove(input); //파일 초기화
								alert("처리중 오류가 발생하였습니다.");
								return;
							}
						}
					});
				}
			}
		}
	}
</script>
