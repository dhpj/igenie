<?
	//버튼정보
	$tpl_button = "";
	$str_button = "";
	$tpl_button_arr = json_decode($rs->tpl_button);
	$tpl_button_cnt = 0;
	if(!empty($tpl_button_arr)){
		$str_button .= "<div style=\"margin-top: 4px;\">";
		foreach ($tpl_button_arr as $arr) {
			if($arr->name != ""){
				$tpl_button_cnt++;
				$tpl_button .= "<p>". $arr->name ."</p>";
				$str_button .= "<p class=\"tem_btn\">". $arr->name ."</p>";
			}
		}
		$str_button .= "</div>";
	}
	//echo "str_button : ". $str_button ."<br>";
?>
<?if($rs->tpl_profile_key) {?>
	<div class="input_content_wrap" style="display:none;">
		<div class="input_content2">
			<?=$rs->tpl_code?><?//템플릿 코드?>
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
		 <input type="hidden" name="tpl_button_cnt" id="tpl_button_cnt"  value="<?=$tpl_button_cnt?>" style="width:160px;">
		 <input type="hidden" name="pcd_code" id="pcd_code"  value="<?=$pcd_code?>" style="width:100px;">
		 <input type="hidden" name="pcd_type" id="pcd_type"  value="<?=$pcd_type?>" style="width:100px;">
		 <input type="hidden" name="pcd_url" id="pcd_url"  value="<?=$pcd_url?>" style="width:160px;">
	</div>
	<div class="input_content_wrap" style="display:none;">
		<div class="input_content2">
			<?=$rs->tpl_name?><?//템플릿명?>
		</div>
	</div>
	<div class="input_content_wrap" style="display:none;">
		<div class="input_content2">
			<button class="btn_add_gr btn_myModal" onclick="open_templet()"><span class="material-icons">link</span> 알림톡버튼 변경하기</button>
			<p class="noted">* 알림톡버튼을 변경하시려면 위 버튼을 클릭하세요.</p>
		</div>
	</div>
	<div class="input_content_wrap" style="padding:0;border-bottom:0px;">
		<div class="input_content2"><?//발송내용 입력?>
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
				        $var_value = $varvalue[$idx]->var_text;
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > var_value[". $idx ."] : ". $var_value);
				    }
				    //log_message("ERROR", "cnt : ".sizeof($varvalue)."/ value : ".$var_value);
				    $pla =  $match[0][$idx];
				    $variable = '/\\'.$match[0][$idx].'/';
				    if($pla == '#{변수내용}') {
						$var_str = '<textarea name="var_name" class="var multi" onkeyup="return view_preview();" placeholder="행사 정보">'.$var_value.'</textarea>';
				    } else if($pla == '#{업체명}') {
				        if(empty($var_value)) {
				            $var_value = $this->member->item('mem_username');
				        }
				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="업체명">'.$var_value.'</textarea>';
				    } else if($pla == '#{업체전화번호}' || $pla == '#{전화번호}') {
						if(empty($var_value)) {
				            //$var_value = $this->Biz_dhn_model->add_hyphen($this->member->item('mem_phone'));
				            $var_value = $this->funn->format_phone($this->member->item('mem_phone'), "-");
				        }
				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="전화번호">'.$var_value.'</textarea>';
				    } else if($pla == '#{날짜}'){
				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="행사 날짜">'.$var_value.'</textarea>';
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
				<span class="txt_byte"><span id="type_num_second">0</span>/1,000 자</span>
				<!-- div class="txt_ad">수신거부 : 홈>친구차단</div-->
			</div>
			<p class="noted">*노란 박스 영역(변수값)의 내용만 입력/수정이 가능합니다.</p>
			<!--p class="info_text">"#{변수}" 부분의 내용만 수정이 가능합니다.</p-->
		</div>
		<div class="sms_preview toggle"><?//mobile_preview?>
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
					<div class="preview_box_msg" id="text_second"></div>
				</div>
				<?if($rs->cc_idx) { // 2019.08.08. 쿠폰 발송 관련 시작?>
				<div class="preview_option flex">
					<button class="btn_preview_option" id="msg_save_temp" onclick="view_preview();">응모페이지</button>
					<button class="btn_preview_option" id="open_msg_list_" onclick="view_result();">당첨페이지</button>
				</div>
				<? } // 2019.08.08. 쿠폰 발송 관련 끝 ?>
				<!--
				<div class="preview_option flex">
					<button class="btn_preview_option" onClick="msg_save('Y');">내용저장</button>
					<button class="btn_preview_option" onClick="open_msg_lms_list();">불러오기</button>
				</div>
				-->
			</div>
			<div class="preview_home"></div>
		</div><!-- mobile_preview END -->
	</div>
	<div class="input_content_wrap" style="padding: 15px 0px 0px 0px;border-bottom:0px;display:<?=($tpl_button_cnt == 0) ? "none" : ""?>;">
		<div class="input_content2"><?//버튼내용 선택?>
			<!-- 스마트전단, 에디터사용 공통 -->
			<? $leaflet_link_type = "at"; //전단지 타입(at.알림톡, lms.문자) ?>
			<? if($add == "_test"){ ?>
				<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/dhnsender/send/bootstrap/_inc_leaflet_test.php'); ?>
			<? }else{ ?>
				<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/dhnsender/send/bootstrap/_inc_leaflet.php'); ?>
			<? } ?>
			<div id="field_second"><?//버튼내용 표시 영역?>
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
			var btn1_type = "<?=$rs->tpl_btn1_type?>"; //버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
			var premium_yn = "<?=$rs->tpl_premium_yn?>"; //광고성 알림톡 프리미엄 여부
			//alert("link_type : "+ link_type);
			//alert("btn_content.length : "+ btn_content.length);
			for(var i=0;i<btn_content.length;i++) {
				var html = '';
				var btn_count = i + 1;
				var ordering= (typeof(btn_content[i]["ordering"])=='undefined') ? no : btn_content[i]["ordering"];
				var btn_type = btn_content[i]["linkType"];
				//var type = '';
				var btn_name = btn_content[i]["name"];
				//alert("["+ i +"] btn_name : "+ btn_name);
				//if(btn_type=="DS") { type="<?=$this->Biz_dhn_model->buttonName['DS']?>"; }
				//else if(btn_type=="BK") { type="<?=$this->Biz_dhn_model->buttonName['BK']?>"; }
				//else if(btn_type=="MD") { type="<?=$this->Biz_dhn_model->buttonName['MD']?>"; }
				//else if(btn_type=="AL") { type="<?=$this->Biz_dhn_model->buttonName['AL']?>"; }
				//else if(btn_type=="WL") { type="<?=$this->Biz_dhn_model->buttonName['WL']?>"; }

				if (btn_type == "DS" || btn_type == "BK" || btn_type == "MD") {
					html += "<div id=\"field_second-data-" + i + "\" name=\"field_second-data\">";
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
						html += "<div id=\"field_second-data-" + i + "\" name=\"field_second-data\">";
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
							//alert('btn_name.replace(/ /g,"").indexOf("주문하기") : '+ btn_name.replace(/ /g,"").indexOf("주문하기"));
							html += "<div class=\"input_link_wrap\">";
							if(btn_count == 1 && btn1_type == "E"){ //첫번째 버튼 & 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview();\">"+ btn_name +"</button>"; //첫번째 버튼
								html += "  <div style=\"overflow: hidden;\">";
								html += "    <input type=\"url\" style=\"width:100%;background:#f2f2f2;\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?=$dhnl_url?>\" readonly>";
								html += "  </div>";
							}else if(btn_count == 1 && (btn1_type == "L" || btn1_type == "C")){ //첫번째 버튼 & 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview();\">"+ btn_name +"</button>"; //첫번째 버튼
								html += "  <div style=\"overflow: hidden;\">";
								html += "    <input type=\"url\" style=\"width:100%;background:#f2f2f2;\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"스마트전단 불러오기 버튼을 클릭하세요.\" value=\"<?=$psd_url?>\" readonly>";
								html += "  </div>";
							}else if(premium_yn == "Y" && btn_name.replace(/ /g,"").indexOf("주문하기") > -1){ //광고성 알림톡 프리미엄 & 주문하기
								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this, '"+ btn_count +"');\">"+ btn_name +"</button>"; //두번째 버튼
								html += "  <div style=\"overflow: hidden;\">";
								html += "    <input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"http://<?=$this->member->item('mem_userid')?>.pfmall.co.kr\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
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
				//alert("["+ i +"] html : "+ html);
				if ($("#field_second").html().trim() == "") {
					$("#field_second").html(html);
				} else {
					$("#field_second-data-" + (i - 1)).after(html);
				}
			}
			if ($("#field_second").html().trim() == "") {
				$("#field_second").html("버튼 링크가 없습니다.");
			}
		</script>
	</div>
	<div class="input_content_wrap planB" style="display:none;">
		<div class="input_content form_check"><?//2차발송 선택?>
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
						$lms_base_msg = $savemsg->lms_msg; //저장된 문자 내용
						if($tpl_button_cnt > 0){ //링크버튼형
							//tpl_btn1_type : 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
							if($rs->tpl_btn1_type == "E" and $dhnl_url != ""){
								$lms_add_msg = "행사보기\n". str_replace('http://', '',$dhnl_url);
							}else if($rs->tpl_btn1_type == "L" and $psd_url != ""){
								$lms_add_msg = "행사보기\n". str_replace('http://', '',$psd_url);
							}
							if($lms_add_msg != ""){
								$lms_base_msg .= "\n\n". $lms_add_msg;
							}
						}
					?>
					<textarea class="input_msg" id="lms_second" name="lms_second" placeholder="'입력' 또는 '알림톡 내용 가져오기' 버튼을 클릭하세요." onkeyup="onPreviewLms();chkword_lms();"><?=$lms_base_msg?></textarea>
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
	<!-- 알림톡버튼 변경하기 Modal -->
	<div id="modal_templet" class="dh_modal">
	  <!-- Modal content -->
	  <div class="modal-content md_width970"> <span id="close_templet" class="dh_close">&times;</span>
		<p class="modal-tit t_al_left">
		  알림톡버튼 목록
		</p>
		<!--<ul id="id_templet_list"><?//알림톡 샘플 리스트 영역?>
		</ul>-->
		<div class="al_talk_box">
		<div class="talk_box">
			<div class="talk_box_top">
				미리보기
			</div>
			<div class="talk_box_con" id="modal_templet_msg">
				<?
					//Modal 템플릿 내용
					$match = "";
					$tmp_str = $rs->tpl_contents;
					$tmp_str = str_replace("\n\n", "<p>&nbsp;</p>", $tmp_str);
					$tmp_str = nl2br($tmp_str);
					$pattern = '/\#{[^}]*}/';
					$m_cnt = preg_match_all($pattern, $tmp_str, $match );
					$idx = 0;
					$m_idx = 0;
					while($m_cnt >= 1) {
						$pla =  $match[0][$idx];
						$variable = '/\\'.$match[0][$idx].'/';
						if($pla == '#{업체명}') {
							$var_str = $this->member->item('mem_username');
						} else if($pla == '#{업체전화번호}' || $pla == '#{전화번호}'){
							$var_str = $this->funn->format_phone($this->member->item('mem_phone'), "-");
						}else{
							$var_str = $pla;
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
			<div class="talk_box_btn" id="modal_templet_btn">
				<?
					//Modal 템플릿 버튼
					echo $tpl_button;
				?>
			</div>
		</div>
		<div class="talk_info">
			<p><i class="xi-info-o"></i> 알림톡버튼을 선택하세요.</p>
		</div>
		</div>
		<div class="al_talk_list">
			<p class="tit">
				버튼타입
			</p>
			<div class="list">
				<ul id="modal_templet_list"><?//알림톡 샘플 리스트 영역?>
				</ul>
			</div>
		</div>
	  </div>
	</div>
	<script>
		//알림톡버튼 데이타 미리보기
		function view_templet(tpl_id, tpl_premium_yn, btn_name){
			//alert("tpl_id : "+ tpl_id +", tpl_premium_yn : "+ tpl_premium_yn +"\n"+"btn_name : "+ btn_name);
			var tpl_contents = $("#tpl_contents_"+ tpl_id).val(); //템플릿 내용
			$("#modal_templet_msg").html(tpl_contents); //템플릿 내용
			$("#modal_templet_btn").html(btn_name); //템플릿 버튼
		}

		//알림톡버튼 모달
		var modal_templet = document.getElementById("modal_templet");
		function open_templet(){
			//msg_save('N');//알림톡 내용 임시저장
			var modal_tpl_no = 0;
			var first_tpl_id = "";
			var first_tpl_premium_yn = "";
			var first_btn_name = "";
			var contract_type = "<?=$this->member->item("mem_contract_type")?>";
			$("#modal_templet_list").html(""); //알림톡 샘플 리스트 영역 초기화
			//알림톡버튼 데이타 조회
			$.ajax({
				url: "/dhnbiz/sender/send/talk_adv/ajax_adv_template_data",
				type: "POST",
				data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					modal_tpl_no++;
					var html = "";
					var mem_phone = "<?=$this->funn->format_phone($this->member->item('mem_phone'), "-")?>";
					//alert("json : "+ json);
					$.each(json, function(key, value){
						var tpl_id = value.tpl_id; //템플릿번호
						//alert("tpl_id : "+ tpl_id);
						var tpl_contents = value.tpl_contents; //템플릿 내용
						tpl_contents = tpl_contents.replace(/\n/gi, "<br>"); //<br>처리
						tpl_contents = tpl_contents.replace(/#{업체명}/gi, "<?=$this->member->item('mem_username')?>"); //업체명 적용
						tpl_contents = tpl_contents.replace(/#{전화번호}/gi, mem_phone); //연락처 적용
						tpl_contents = tpl_contents.replace(/#{업체전화번호}/gi, mem_phone); //연락처 적용
						var tpl_button = value.tpl_button; //템플릿 버튼
						var tpl_premium_yn = value.tpl_premium_yn; //프리미엄 여부
						var badge = "<span class=\"badge_sta\">스탠<br>다드</span>";
						if(tpl_premium_yn == "Y") badge = "<span class=\"badge_pre\">프리<br>미엄</span>";
						var btn = tpl_button.replace(/&quot;/gi, "\"");
						var btn_content = JSON.parse(btn);
						var btn_cnt = 0;
						var btn_name = "";
						var rtn_name = "";
						for(var i=0;i<btn_content.length;i++){
							btn_name += "  <p>"+ btn_content[i]["name"] +"</p>";
							btn_cnt++;
						}
						if(btn_cnt == 0){
							rtn_name = "<p>버튼없음</p>";
						}else{
							rtn_name = btn_name;
						}
						var rtn_link = "selected_template('"+ tpl_id +"', 'temp');";
						if(tpl_premium_yn == "Y" && contract_type != "P") rtn_link = "alert('프리미엄 서비스입니다.');";
						//btn_name += "  <p>"+ tpl_id +"</p>";
						if(tpl_id != "" && tpl_id != undefined){
							html += "<li id='"+ tpl_id +"' class='li_templet_list'>";
							html += "  "+ badge; //뱃지
							html += "  <div class=\"li_templet_list_box\" onClick=\""+ rtn_link +"\">";
							html += "    <div>"+ rtn_name +"</div>"; //버튼
							html += "  </div>";
							html += "  <div class=\"type_btn\">";
							html += "    <button onClick=\"view_templet('"+ tpl_id +"', '"+ tpl_premium_yn +"', '"+ btn_name +"');\"><i class=\"xi-plus-circle-o\"></i> 미리보기</button>";
							html += "    <button onClick=\""+ rtn_link +"\"><i class=\"xi-check-circle-o\"></i> 선택하기</button>";
							html += "    <textarea id='tpl_contents_"+ tpl_id +"' style='display:none;'>"+ tpl_contents +"</textarea>";
							html += "  </div>";
							html += "</li>";
							if(modal_tpl_no == 1){
								first_tpl_id = tpl_id;
								first_tpl_premium_yn = tpl_premium_yn;
								first_btn_name = btn_name;
							}
						}
					});
					$("#modal_templet_list").append(html);
				}
			});

			//open the modal
			modal_templet.style.display = "block";

			//close the modal
			var close = document.getElementById("close_templet");
			close.onclick = function() {
				modal_templet.style.display = "none";
			}

			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
				if (event.target == modal_templet) {
					modal_templet.style.display = "none";
				}
			}

			//alert("first_tpl_id : "+ first_tpl_id +", first_tpl_premium_yn : "+ first_tpl_premium_yn);
			//view_templet(first_tpl_id, first_tpl_premium_yn, first_btn_name);
		}

		<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 ?>
		//알림톡버튼 데이타 마우스 이동
		$("#modal_templet_list").sortable({
			stop: function(event, ui) {
				var seq = [];
				var i = 0;
				$("#modal_templet_list > li.li_templet_list").each(function(t) {
					seq.push($(this).attr("id"));
				});
				//console.log(seq);
				//alert("알림톡버튼 데이타 마우스 이동"); return;
				//alert("seq : "+ seq); return;
				$.ajax({
					url: "/dhnbiz/sender/send/talk_adv/ajax_adv_template_sort",
					type: "POST",
					data:{"seq[]":seq, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
					success:function(json) {
						//console.log('완료');
						showSnackbar("정렬순서가 변경 되었습니다.", 1500);
					}
				});
			}
		});
		$("#modal_templet_list").disableSelection();
		<? } //if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 ?>
	</script>
<?} else {?>
	<div class="input_content_wrap">
			<label class="input_tit">템플릿 선택</label>
			<div class="input_content2">
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
		<?}?>
	}

	//모바일 미리보기 세팅
	function mobile_preview(){
		var previewText = $("#text_second").html() + '<?=$str_button?>';
		$("#text_second").html(previewText);
	}

	view_preview(); //알림톡 미리보기  함수들

    //템플릿내용 선택
	/*function selected_template(bottonId, tmp_flag) {
        iscoupon = $("#couponwrite").val();
		nft_value = $("#NFTVALUE").val();
		var psd_code = $("#psd_code").val();
		//alert("psd_code : "+ psd_code);
        var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/dhnbiz/sender/send/friend");

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

		form.submit();
    }*/

	//링크 타입 변경
	function chg_link_type(){
		var link_type_option = document.getElementById("link_type_option").value;
		var link_type = document.getElementById("link_type").value; //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
		var tpl_id = "<?=$tmp_code?>";
		var btn_url = "";
		//alert("link_type : "+ link_type +", link_type_option : "+ link_type_option +", tpl_id : "+ tpl_id);
		if(link_type == "self"){ //직접입력
			document.getElementById("div_smart_box").style.display = "none"; //스마트전단 영역 닫기
			document.getElementById("div_coupon_box").style.display = "none"; //스마트쿠폰 영역 닫기
			document.getElementById("div_editor_box").style.display = "none"; //에디터 영역 닫기
			document.getElementById("div_blank_box").style.display = ""; //사용안함 영역 열기
			document.getElementById("div_smart_goods_call").style.display = "none"; //행사정보 가져오기 영역 열기
		}else if(link_type == "editor"){ //에디터url 리턴값
			btn_url = document.getElementById("dhnl_url").value; //에디터 링크 URL
			document.getElementById("div_smart_box").style.display = "none"; //스마트전단 영역 닫기
			document.getElementById("div_coupon_box").style.display = "none"; //스마트쿠폰 영역 닫기
			document.getElementById("div_editor_box").style.display = ""; //에디터 영역 열기
			document.getElementById("div_blank_box").style.display = "none"; //사용안함 영역 열기
			document.getElementById("div_smart_goods_call").style.display = "none"; //행사정보 가져오기 영역 열기
		}else if(link_type == "coupon"){ //스마트쿠폰
			if(link_type_option != "coupon"){
				//msg_save('N');//알림톡 내용 임시저장
				selected_template('<?=$param['coupon_tmp_code']?>', 'temp'); //스마트쿠폰 템플릿 이동
				return;
			}
			btn_url = document.getElementById("pcd_url").value; //스마트쿠폰 링크 URL
			document.getElementById("div_smart_box").style.display = "none"; //스마트전단 영역 닫기
			document.getElementById("div_coupon_box").style.display = "inline"; //스마트쿠폰 영역 닫기
			document.getElementById("div_editor_box").style.display = "none"; //에디터 영역 열기
			document.getElementById("div_blank_box").style.display = "none"; //사용안함 영역 열기
			document.getElementById("div_smart_goods_call").style.display = "none"; //행사정보 가져오기 영역 열기
		}else{ //스마트전단
			if(link_type_option == "coupon"){
				//msg_save('N');//알림톡 내용 임시저장
				selected_template('<?=$param['base_tmp_code']?>', 'temp'); //스마트전단 템플릿 이동
				return;
			}
			btn_url = document.getElementById("psd_url").value; //스마트전단 링크 URL
			document.getElementById("div_smart_box").style.display = "inline"; //스마트전단 영역 열기
			document.getElementById("div_coupon_box").style.display = "none"; //스마트쿠폰 영역 닫기
			document.getElementById("div_editor_box").style.display = "none"; //에디터 영역 닫기
			document.getElementById("div_blank_box").style.display = "none"; //사용안함 영역 열기
			document.getElementById("div_smart_goods_call").style.display = ""; //행사정보 가져오기 영역 열기
		}
		//알림톡 버튼 URL 변경
		var btn_cnt = 0;
		$("div[name='field_second-data']").each(function () {
			btn_cnt++;
			var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
			//alert("btn_type : "+ btn_type);
			if (btn_type != undefined && btn_cnt == 1) { //첫번째 버튼만 링크 추가
				$(this).find(document.getElementsByName("btn_url1")).val(btn_url);
				$(this).find(document.getElementsByName("btn_url2")).val(btn_url);
				if(link_type == "smart" || link_type == "coupon" || link_type == "editor"){ //스마트전단 or 스마트쿠폰 or 에디터
					$(this).find(document.getElementsByName("btn_url1")).attr("readonly",true); //링크 readonly true
					$(this).find(document.getElementsByName("btn_url1")).css("background-color", "#f2f2f2"); //링크 배경색 변경
					if(link_type == "smart"){ //스마트전단
						$(this).find(document.getElementsByName("btn_url1")).attr("placeholder", "스마트전단 불러오기 버튼을 클릭하세요.").placeholder(); //링크 placeholder 변경
					}else if(link_type == "coupon"){ //스마트쿠폰
						$(this).find(document.getElementsByName("btn_url1")).attr("placeholder", "스마트쿠폰 불러오기 버튼을 클릭하세요.").placeholder(); //링크 placeholder 변경
					}else{
						$(this).find(document.getElementsByName("btn_url1")).attr("placeholder", "링크주소를 입력해주세요.").placeholder(); //링크 placeholder 변경
					}
				}else{
					$(this).find(document.getElementsByName("btn_url1")).attr("readonly",false); //링크 readonly false
					$(this).find(document.getElementsByName("btn_url1")).css("background-color", "#ffffff"); //링크 배경색 변경
					$(this).find(document.getElementsByName("btn_url1")).attr("placeholder", "링크주소를 입력해주세요.").placeholder(); //링크 placeholder 변경
				}
			}
		});
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
				var btn_url = json.dhnlurl;
				//alert("btn_url : "+ btn_url); return;
				if(btn_url != ""){
					document.getElementById("psd_code").value = code; //스마트전단 코드
					document.getElementById("psd_url").value = btn_url; //스마트전단 링크 URL
					$("#div_smart_url").html(btn_url); //스마트전단 링크주조 표기 영역
					//alert("psd_url : "+ document.getElementById("psd_url").value); return;
					//알림톡 버튼 URL 변경
					var btn_cnt = 0;
					$("div[name='field_second-data']").each(function () {
						btn_cnt++;
						var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
						//alert("btn_type : "+ btn_type);
						if (btn_type != undefined && btn_cnt == 1) { //첫번째 버튼만 링크 추가
							$(this).find(document.getElementsByName("btn_url1")).val(btn_url);
							$(this).find(document.getElementsByName("btn_url2")).val(btn_url);
						}
					});
					//2차발송 세팅
					document.getElementById("lms").value = "행사보기\n"+ btn_url; //2차발송 내용
					//문자내용반영
					onPreviewLms();
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
					//alert("psd_url : "+ document.getElementById("psd_url").value); return;
					//알림톡 버튼 URL 변경
					var btn_cnt = 0;
					$("div[name='field_second-data']").each(function () {
						btn_cnt++;
						var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
						//alert("btn_type : "+ btn_type);
						if (btn_type != undefined && btn_cnt == 1) { //첫번째 버튼만 링크 추가
							$(this).find(document.getElementsByName("btn_url1")).val(btn_url);
							$(this).find(document.getElementsByName("btn_url2")).val(btn_url);
						}
					});
					//2차발송 세팅
					document.getElementById("lms").value = "쿠폰확인\n"+ btn_url; //2차발송 내용
					//문자내용반영
					onPreviewLms();
					chkword_lms();
				}
			}
		});

		//스마트전단 불러오기 Modal 닫기
		modal_coupon.style.display = "none";
	}
</script>