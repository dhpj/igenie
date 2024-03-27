<?
	//버튼정보
	$tpl_button = "";
	$str_button = "";
	$tpl_button_arr = json_decode($rs->tpl_button);
	$tpl_button_cnt = 0;
	$tpl_button2_leaflet_yn = "N"; //알림톡 버튼2 전단보기 여부
	if(!empty($tpl_button_arr)){
		$str_button .= "<div style=\"margin-top: 4px;\">";
		foreach ($tpl_button_arr as $arr) {
			if($arr->name != ""){
				$tpl_button_cnt++;
				$tpl_button .= "<p>". $arr->name ."</p>";
				$str_button .= "<p class=\"tem_btn\">". $arr->name ."</p>";
				if($tpl_button_cnt == 2){
					$rep_button_nm = str_replace(" ", "", $arr->name); //버튼명 공백제거
					$pos = strpos($rep_button_nm, "전단보기"); //문자열 포함 체크
					//echo "알림톡 버튼[". $tpl_button_cnt ."] : ". $rep_button_nm ."<br>";
					//echo "전단보기 포함여부[". $tpl_button_cnt ."] : ". $pos ."<br>";
					if($pos){
						$tpl_button2_leaflet_yn = "Y"; //알림톡 버튼2 전단보기 여부
						//echo "알림톡 버튼2 전단보기 여부[". $tpl_button_cnt ."] : ". $tpl_button2_leaflet_yn ."<br>";
					}
				}
			}
		}
		$str_button .= "</div>";
	}
	//echo "알림톡 버튼 : ". $str_button ."<br>";
	//echo "알림톡 버튼수 : ". $tpl_button_cnt ."<br>";
	//echo "알림톡 버튼2 전단보기 여부 : ". $tpl_button2_leaflet_yn ."<br>";
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
		 <input type="hidden" name="pcd_code" id="pcd_code"  value="<?=$pcd_code?>" style="width:100px;">
		 <input type="hidden" name="pcd_type" id="pcd_type"  value="<?=$pcd_type?>" style="width:100px;">
		 <input type="hidden" name="pcd_url" id="pcd_url"  value="<?=$pcd_url?>" style="width:160px;">
         <input type="hidden" name="home_code" id="home_code"  value="<?=$home_code?>" style="width:100px;">
		 <input type="hidden" name="home_url" id="home_url"  value="<?=$home_url?>" style="width:160px;">
         <input type="hidden" name="hdn_isdhn" id="hdn_isdhn"  value="<?=$isdhn?>" style="width:160px;">
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
				<div class="preview_option flex" style="display:none;">
					<button class="btn_preview_option" onClick="msg_save('Y');">내용저장</button>
					<button class="btn_preview_option" onClick="open_msg_lms_list();">불러오기</button>
				</div>
			</div>
			<div class="preview_home"></div>
		</div><!-- mobile_preview END -->
		<label class="input_tit" id="id_STEP1">
			<p class="txt_st_eng">STEP 1.</p>
			<p>버튼타입 선택</p>
		</label>
		<div class="input_content">
			<button class="btn_add_gr btn_myModal" onclick="open_templet()"><span class="material-icons">link</span> 알림톡버튼 변경하기</button>
			<p class="noted">* 알림톡버튼을 변경하시려면 위 버튼을 클릭하세요.<?=($this->member->item("mem_level") >= 100)? " - [".$rs->tpl_code."]" : '' ?></p>
		</div>
		</div>
		<div class="input_content_wrap">
		<label class="input_tit" id="id_STEP2">
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
				        $var_value = $varvalue[$idx]->var_text;
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > var_value[". $idx ."] : ". $var_value);
				    }
				    //log_message("ERROR", "cnt : ".sizeof($varvalue)."/ value : ".$var_value);
				    $pla =  $match[0][$idx];
				    $variable = '/\\'.$match[0][$idx].'/';
				    if($pla == '#{변수내용}' || $pla == '#{회원정보 등록일}') {
				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" placeholder="행사 기간 : 00.00.00.">'.$var_value.'</textarea>';
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
				<span class="txt_byte"><span id="type_num">0</span>/1,000 자</span>
				<!-- div class="txt_ad">수신거부 : 홈>친구차단</div-->
			</div>
			<p class="noted">*노란 박스 영역(변수값)의 내용만 입력/수정이 가능합니다.</p>
			<!--p class="info_text">"#{변수}" 부분의 내용만 수정이 가능합니다.</p-->
            <? // 2021-05-04 템플릿 추가정보형 추가(카카오톡 API버전 변경)?>
            <div class="input_content_wrap" <? if($rs->tpl_messagetype != "EX" && $rs->tpl_messagetype != "MI") {echo "style='display:none'";}?>>
            	<label class="input_tit">부가정보</label>
            	<div class="input_content">
            		<input type="hidden" id="tpl_extra" name="tpl_extra"  value="<?=$rs->tpl_extra?>" />
            		<?=$rs->tpl_extra?>
            	</div>
            </div>
            <div class="input_content_wrap" <? if($rs->tpl_messagetype != "AD" && $rs->tpl_messagetype != "MI") {echo "style='display:none'";}?>>
            	<label class="input_tit">광고형 내용</label>
            	<div class="input_content">
            		<input type="hidden" id="tpl_ad" name="tpl_ad"  value="<?=$rs->tpl_ad ?>" />
            		<?=$rs->tpl_ad ?>
            	</div>
            </div>
		</div>
	</div>
	<div class="input_content_wrap" style="display:<?=($tpl_button_cnt == 0) ? "none" : ""?>;">
		<label class="input_tit" id="id_STEP3">
			<p class="txt_st_eng">STEP 3.</p>
			<p>버튼내용 선택</p>
		</label>
		<div class="input_content">
			<!-- 스마트전단, 에디터사용 공통 -->
			<? $leaflet_link_type = "at"; //전단지 타입(at.알림톡, lms.문자) ?>
			<? if($add == "_test"){ ?>
				<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/dhnsender/send/bootstrap/_inc_leaflet_test.php'); ?>
			<? }else{ ?>
				<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/dhnsender/send/bootstrap/_inc_leaflet_new_v3.php'); ?>
			<? } ?>

		</div>
		<script type="text/javascript">
			var buttons = '<?=$rs->tpl_button?>';
			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);
			var url_name1 = '';
			var url_name2 = '';
			var url_link1 = '';
			var url_link2 = '';
			var link_type = "<?=$link_type_option?>";
			var btn1_type = "<?=$rs->tpl_btn1_type?>"; //버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
            // var btn2_type = "";
            // var btn3_type = "";
			var premium_yn = "<?=$rs->tpl_premium_yn?>"; //광고성 알림톡 프리미엄 여부
			var mem_contract_type = "<?=$this->member->item("mem_contract_type")?>"; //계약종류(P:프리미엄, S:스텐다드)
			var alim_btn_url1 = "<?=$this->member->item("mem_alim_btn_url1")?>"; //알림톡 '행사보기' 버튼 URL 있는 경우
			var alim_btn_url2 = "<?=$this->member->item("mem_alim_btn_url2")?>"; //알림톡 '전단보기' 버튼 URL 있는 경우
			var alim_btn_url3 = "<?=$this->member->item("mem_alim_btn_url3")?>"; //알림톡 '주문하기' 버튼 URL 있는 경우
            var selecttype = "";
            var wholename = "";
            for(var i=0;i<btn_content.length;i++){
                wholename += btn_content[i]["name"].replace(/ /g,"");
                // buttonNameArr.push(btn_content[i]["name"].replace(/ /g,""));
            }


			//alert("link_type : "+ link_type);
			for(var i=0;i<btn_content.length;i++) {
				var html = '';
                var html2 = '';
                var html3 = '';
				var btn_count = i + 1;
				var ordering= (typeof(btn_content[i]["ordering"])=='undefined') ? no : btn_content[i]["ordering"];
                //ib플래그
                <? if(config_item('ib_flag')=="Y"){ ?>
				    var btn_type = btn_content[i]["type"];
                <? }else{ ?>
                    var btn_type = btn_content[i]["linkType"];
                <? } ?>
				var btn_name = btn_content[i]["name"];
				//if(btn_type=="DS") { type="<?=$this->Biz_dhn_model->buttonName['DS']?>"; }
				//else if(btn_type=="BK") { type="<?=$this->Biz_dhn_model->buttonName['BK']?>"; }
				//else if(btn_type=="MD") { type="<?=$this->Biz_dhn_model->buttonName['MD']?>"; }
				//else if(btn_type=="AL") { type="<?=$this->Biz_dhn_model->buttonName['AL']?>"; }
				//else if(btn_type=="WL") { type="<?=$this->Biz_dhn_model->buttonName['WL']?>"; }
				if (btn_type == "DS" || btn_type == "BK" || btn_type == "MD") {
					html += "<div id=\"field-data-" + i + "\" name=\"field-data\">";
					html += "<div class=\"input_link_wrap\">";
					html += "<select style=\"width: 120px;\" class=\"fl\"  name=\"btn_type\" id=\"btn_type\">";
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
						html += "<div style=\"overflow: hidden;\"><input type=\"text\" name=\"btn_name\" id=\"btn_name\" class=\"btn_name_loop_"+i+"\"  style=\"width:100%; margin-left: -1px;\" placeholder=\"버튼명을 입력해주세요\" readonly value=\"" + btn_name +"\">";
						html += "</div>";
						html += "</span>";
						if(btn_type == "WL") { //웹링크
                            //ib플래그
                            <? if(config_item('ib_flag')=="Y"){ ?>
                                url_link1 = btn_content[i]["url_mobile"];
							    url_link2 = btn_content[i]["url_pc"];
                            <? }else{ ?>
                                url_link1 = btn_content[i]["linkMo"];
    							url_link2 = btn_content[i]["linkPc"];
                            <? } ?>
							//alert('btn_name.replace(/ /g,"").indexOf("주문하기") : '+ btn_name.replace(/ /g,"").indexOf("주문하기"));
							//alert('btn_name.replace(/ /g,"") : '+ btn_name.replace(/ /g,""));
							//alert('alim_btn_url1 : '+ alim_btn_url1);

							html += "<div class=\"input_link_wrap\">";

                            html += "<div id=\"selectIn_"+i+"\"></div>";
                            if(i == 0){
                                selecttype = "home";
                                html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview_home("+i+");\">"+ btn_name +"</button>";
                                html += "  <div style=\"overflow: hidden;\">";
                                html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"스마트홈\" value=\"<?=$home_url?>\" readonly>";
                                html += "  </div>";
                            }else{
                                selecttype = "self";
                                html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview_home("+i+");\">"+ btn_name +"</button>";
                                html += "  <div style=\"overflow: hidden;\">";
                                html += "    <input type=\"url\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"url을 입력하세요\" value=\"\">";
                                html += "  </div>";
                            }

							// if(link_type == "smartEditor" && btn_name.replace(/ /g,"").indexOf("전단보기") > -1 && i == 1){ //스마트전단+에디터 & 전단보기 & 2번째 버튼의 경우
                            //     if(btn1_type == "E"){
                            //         selecttype = "smart";//smart
    						// 		html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //첫번째 버튼
    						// 		html += "  <div style=\"overflow: hidden;\">";
    						// 		html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"전단불러오기 버튼 클릭\" value=\"<?=$psd_url?>\" readonly>";
    						// 		html += "  </div>";
                            //     }else{
                            //         selecttype = "editor"; //editor
    						// 		html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //첫번째 버튼
    						// 		html += "  <div style=\"overflow: hidden;\">";
    						// 		html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?=$dhnl_url?>\" readonly>";
    						// 		html += "  </div>";
                            //     }
                            //
							// }else if(alim_btn_url1 != "" && btn_name.replace(/ /g,"").indexOf("행사보기") > -1){ //알림톡 '행사보기' 버튼 URL 고정 사용의 경우
                            //     selecttype = "self";//self
							// 	// $("#link_type").val("self"); //링크타입 직접입력으로 변경
							// 	html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>";
							// 	html += "  <div style=\"overflow: hidden;\">";
							// 	html += "    <input type=\"url\" style=\"\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\""+ alim_btn_url1 +"\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
							// 	html += "  </div>";
							// }else if(alim_btn_url2 != "" && btn_name.replace(/ /g,"").indexOf("전단보기") > -1){ //알림톡 '전단보기' 버튼 URL 고정 사용의 경우
                            //     selecttype = "editor";//editor
							// 	html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>";
							// 	html += "  <div style=\"overflow: hidden;\">";
							// 	html += "    <input type=\"url\" style=\"\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\""+ alim_btn_url2 +"\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
							// 	html += "  </div>";
							// }else if(alim_btn_url3 != "" && btn_name.replace(/ /g,"").indexOf("주문하기") > -1){ //알림톡 '주문하기' 버튼 URL 고정 사용의 경우
                            //     selecttype = "self";//self
							// 	html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>";
							// 	html += "  <div style=\"overflow: hidden;\">";
							// 	html += "    <input type=\"url\" style=\"\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\""+ alim_btn_url3 +"\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
							// 	html += "  </div>";
							// }else if(btn_count != 1 && btn1_type != "C" && btn_name.replace(/ /g,"").indexOf("쿠폰받기") > -1){ //첫번째 버튼 & 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
                            //     selecttype = "coupon";//coupon
							// 	html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //첫번째 버튼
							// 	html += "  <div style=\"overflow: hidden;\">";
							// 	html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"쿠폰불러오기 버튼 클릭\" value=\"<?=$pcd_url?>\" readonly>";
							// 	html += "  </div>";
							// }else if(btn_count == 1 && btn1_type == "E"){ //첫번째 버튼 & 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
                            //     selecttype = "editor";//editor
							// 	html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //첫번째 버튼
							// 	html += "  <div style=\"overflow: hidden;\">";
							// 	html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?=$dhnl_url?>\" readonly>";
							// 	html += "  </div>";
							// }else if(btn_count == 1 && btn1_type == "L"){ //첫번째 버튼 & 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
                            //     selecttype = "smart";//smart
							// 	html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //첫번째 버튼
							// 	html += "  <div style=\"overflow: hidden;\">";
							// 	html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"전단불러오기 버튼 클릭\" value=\"<?=$psd_url?>\" readonly>";
							// 	html += "  </div>";
							// }else if(btn_count == 1 && btn1_type == "C"){ //첫번째 버튼 & 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
                            //     selecttype = "coupon";//coupon
							// 	html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //첫번째 버튼
							// 	html += "  <div style=\"overflow: hidden;\">";
							// 	html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url1\" placeholder=\"쿠폰불러오기 버튼 클릭\" value=\"<?=$pcd_url?>\" readonly>";
							// 	html += "  </div>";
							// }else if(premium_yn == "Y" && mem_contract_type == "P" && btn_name.replace(/ /g,"").indexOf("주문하기") > -1){ //광고성 알림톡 프리미엄 & 주문하기
                            //     selecttype = "self";//self
							// 	html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //두번째 버튼
							// 	html += "  <div style=\"overflow: hidden;\">";
							// 	html += "    <input type=\"url\" style=\"\" id=\"btn_url\" name=\"btn_url1\" class=\"btn_loop_"+i+"\" placeholder=\"링크주소를 입력해주세요.\" value=\"http://<?=$this->member->item('mem_mall_id')?>.pfmall.co.kr\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
							// 	html += "  </div>";
							// }else{
                            //     selecttype = "self";//self
							// 	html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //두번째~ 버튼
							// 	html += "  <div style=\"overflow: hidden;\">";
							// 	html += "    <input type=\"url\" style=\"\" id=\"btn_url\" name=\"btn_url1\" class=\"btn_loop_"+i+"\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?//=$psd_url?>\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
							// 	html += "  </div>";
							// }
							html += "</div>";

							if(url_link2 != null && url_link2 != "") { //PC 버전
								html += "<span style=\"display:none;\">";
								html += "<div><label>PC</label></div>";
								html += "<div class=\"input_link_wrap\">";
								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">링크확인</button>";
								html += "  <div style=\"overflow: hidden;\">";
								html += "    <input type=\"url\" style=\"\" id=\"btn_url\" name=\"btn_url2\" class=\"btn_loop_"+i+"\" placeholder=\"링크주소를 입력해주세요.\" value=\"\">";
								html += "  </div>";
								html += "</div>";
								html += "</span>";
							}
                            buttonArr.push(selecttype);

                            html3 += "<div style='display:"+"<?=($lms_purigo_yn == 'Y') ? 'none' : ''?>"+";'>";


                            html3 += "<input type='hidden' name='link_type_option"+ i +"' id='link_type_option"+ i +"' value='"+selecttype+"'>";
                        html3 += "<select style='' class='send_select' name='link_type' id='link_type"+ i +"' onChange='chg_link_type(\""+i+"\");'>";

                        // html3 +=  "<option value='smart'"+"<?=($link_type_option == 'smart') ? ' selected' : ''?>"+">스마트전단</option>";
                        if(i == 0){ //첫번째 버튼의 경우
                            html3 +=  "<option value='home'";
                            if(selecttype=="home"){
                                html3 += " selected";
                            }
                            html3 += ">스마트홈</option>";
                        }
                        // if(wholename.indexOf("쿠폰")>-1){
                        //     html3 +=  "<option value='coupon'";
                        //     if(selecttype=="coupon"){
                        //         html3 += " selected";
                        //     }
                        //     html3 += ">스마트쿠폰</option>";
                        // }

                        // html3 +=  "<option value='coupon'"+"<?=($link_type_option == 'coupon') ? ' selected' : ''?>"+">스마트쿠폰</option>";

                        // html3 +=  "<option value='editor'";
                        // if(selecttype=="editor"){
                        //     html3 += " selected";
                        // }
                        // html3 += ">에디터사용</option>";
                        <? if($isdhn=="Y"){ ?>
                        html3 +=  "<option value='self'";
                        if(selecttype=="self"){
                            html3 += " selected";
                        }
                        html3 += ">직접입력</option>";
                        <? } ?>


                        html3 +=  "</select>";

                        html3 +=  "</div>";


                        html2 +=  "<div id='div_blank_box"+ i +"' style='display:";
                        if(selecttype=="self"){
                            html2 += "none";
                        }
                        html2 += ";margin: 0px 0px 10px 0px;'>";
                        html2 +=  "</div>";
                        // html2 +=  "<div id='div_smart_box"+ i +"' style='display:";
                        // if(selecttype!="smart"){
                        //     html2 += "none";
                        // }
                        // html2 += ";'>";
                        //
                        // html2 +=  "<button class='btn_add btn_myModal' onClick='smart_page(\"1\");'>전단불러오기</button>";
                        //
                        // html2 +=  "<div id='div_smart_goods_call"+ i +"' style='display:";
                        // if(selecttype!="smart"){
                        //     html2 += "none";
                        // }
                        // html2 += ";'>";
                        //
                        // html2 +=  "		<button class='btn_con_copy btn_myModal' onClick='smart_goods_call(\"at\");'>행사정보 가져오기</button>";
                        // html2 +=  "</div>";
                        // html2 +=  "<div class='editor_box' style='margin:15px 0px; display:none;'>";
                        // html2 +=  "<div id='div_smart_url"+ i +"' class='url_info' style='margin: 10px 0px 0px 0px;'><?=$psd_url?></div>";
                        // html2 +=  "<button class='btn_link2' onClick='linkview("+i+");'>링크확인</button><?//스마트전단 링크확인?>";
                        // html2 +=  "</div>";
                        // html2 +=  "</div>";

                        // html2 +=  "<div id='div_coupon_box"+ i +"' style='display:";
                        // if(selecttype!="coupon"){
                        //     html2 += "none";
                        // }
                        // html2 += ";'>";
                        // html2 +=  "<button class='btn_add btn_myModal' onClick='coupon_page(1);'>쿠폰불러오기</button>";
                        // html2 +=  "</div>";
                        //
                        // html2 +=  "<div class='editor_box' style='margin:15px 0px; display:none;";
                        // if(selecttype!="editor"){
                        //     html2 += "none";
                        // }
                        // html2 += ";'>";
                        // html2 +=  "<div id='div_coupon_url"+ i +"' class='url_info' style='margin: 10px 0px 0px 0px;'>"+"<?=$pcd_url?>"+"</div>";
                        // html2 +=  "<button class='btn_link2' onClick='linkview("+i+");'>링크확인</button><?//스마트쿠폰 링크확인?>";
                        // html2 +=  "</div>";
                        html2 +=  "</div>";
                        // console.log(html2);
                        // alert("확인");



						} else if(btn_type == "AL") {
                            //ib플래그
                            <? if(config_item('ib_flag')=="Y"){ ?>
                                url_link1 = btn_content[i]["scheme_android"];
    							url_link2 = btn_content[i]["scheme_ios"];
                            <? }else{ ?>
                                url_link1 = btn_content[i]["linkAnd"];
    							url_link2 = btn_content[i]["linkIos"];
                            <? } ?>

							html += "<div><label>Android</label></div>";
							html += "<div class=\"input_link_wrap\">";
							html += "<button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
							html += "<div style=\"overflow: hidden;\">";
							html += "<input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url1\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?//=$psd_url?>\">";
							html += "</div>";
							html += "</div>";
							html += '<div <?if($rs->cc_idx) { echo "style=\"display:none\"";} ?>><label>iOS</label></div>';
							html += '<div class=\"input_link_wrap\" <?if($rs->cc_idx) { echo "style=\"display:none\"";} ?>>';
							html += "<button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
							html += "<div style=\"overflow: hidden;\">";
							html += "<input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url2\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?//=$psd_url?>\">";
							html += "</div>";
							html += "</div>";
						}
						html += "</div>";
						html += "</div>";

					}
				}
                if(i==btn_content.length-1){
                    html += "<div id='div_home_box' class='input_link_wrap'>";
                    html += "    <div class='info_contents'>";
                    html += "        <input type=\"checkbox\" id=\"chk_a\" name=\"chk_btn\" onChange='smart_chk_change(this, \"<?=$psd_code?>\");' value=\"psd\" <?=((empty($psd_url)&&empty($dhnl_url)&&empty($pcd_url))||!empty($psd_url)) ? "checked" : "" ?> /><label for=\"chk_a\"> 스마트전단</label>&nbsp;&nbsp;&nbsp;";
                    html += "        <input type=\"checkbox\" id=\"chk_b\" name=\"chk_btn\" onChange='editor_choice(this, \"<?=$short_url?>\");' value=\"dhnl\" <?=(!empty($dhnl_url)) ? "checked" : "" ?> /><label for=\"chk_b\"> 에디터</label>&nbsp;&nbsp;&nbsp;";
                    html += "        <input type=\"checkbox\" id=\"chk_c\" name=\"chk_btn\" onChange='coupon_chk_change(this, \"<?=$pcd_code?>\");' value=\"pcd\" <?=(!empty($pcd_url)) ? "checked" : "" ?> /><label for=\"chk_c\"> 스마트쿠폰</label>&nbsp;&nbsp;&nbsp;";
                    html += "        <input type=\"checkbox\" id=\"chk_d\" name=\"chk_btn\" onChange='order_chk_change(this);' value=\"ord\" /><label for=\"chk_d\"> 직접입력</label>";
                    html += "   </div>";
                    html += "    <div id='div_smart' <?=((empty($psd_url)&&empty($dhnl_url)&&empty($pcd_url))||!empty($psd_url)) ? "" : "style='display:none;'" ?>>";
                    html += "       <span class='smart_home_btn'>스마트전단</span>";
                    html += "        <button class='btn_add btn_myModal' onClick='smart_page(1);'>전단불러오기</button>";
                    html += "        <input type='hidden' id='hdn_smart_url' value='<?=$psd_url?>'/>";
                    // html += "        <div id='div_smart_url' class='url_info' style='margin: 10px 0px 0px 0px;'><?=$psd_url?></div>";
                    html += "        <button class='btn_homelink' onClick='linkview_btn(0);'><span class='material-icons'>link</span> 행사보기</button>";
                    html += "    </div>";
                    html += "    <div id='div_editor' <?=(!empty($dhnl_url)) ? "" : "style='display:none;'" ?>>";
                    html += "        <span class='smart_home_btn'>에디터</span>";
                    html += "        <input type='hidden' id='hdn_editor_url' value='<?=$dhnl_url?>'/>";
                    // html += "        <div id='div_editor_url' class='url_info' style='margin: 10px 0px 0px 0px;'><?=$dhnl_url?></div>";
                    html += "        <button class='btn_homelink' onClick='linkview_btn(1);'><span class='material-icons'>link</span> 전단보기</button>";
                    html += "    </div>";
                    html += "    <div id='div_coupon' <?=(!empty($pcd_url)) ? "" : "style='display:none;'" ?>>";
                    html += "        <span class='smart_home_btn'>스마트쿠폰</span>";
                    html += "        <button class='btn_add btn_myModal' onClick='coupon_page(1);'>쿠폰불러오기</button>";
                    html += "        <input type='hidden' id='hdn_coupon_url' value='<?=$pcd_url?>'/>";
                    // html += "        <div id='div_coupon_url' class='url_info' style='margin: 10px 0px 0px 0px;'><?=$pcd_url?></div>";
                    html += "        <button class='btn_homelink' onClick='linkview_btn(2);'><span class='material-icons'>link</span> 쿠폰보기</button>";
                    html += "    </div>";
                    html += "    <div id='div_order' style='display:none;'>";
                    html += "        <span class='smart_home_btn'>직접입력</span>";
                    html += "        <input type='text' id='hdn_order_url' value='<?=$order_url?>'/>";
                    html += "        <button id='order_btn_review' class='btn_homelink' onClick='linkview_btn(3);' style=\"letter-spacing: -0.3px;\"><span class='material-icons'>link</span> 주문하기</button>";
                    html += "        <input type='text' id='hdn_order_btn' style=\"width: 103px; margin: 5px 0 0 274px;\" value='' placeholder=\"버튼이름 입력\" onkeyup=\"return view_btn_name();\" maxlength='6'/>";
                    html += "    </div>";
                    html += "</div>";
                }

				if ($("#field").html().trim() == "") {
					$("#field").html(html);
                    // alert(i);
                    $("#field-data-" + i).before(html2);
                    $("#selectIn_" + i).html(html3);
				} else {
                    // alert(i);

					$("#field-data-" + (i - 1)).after(html);
                    $("#field-data-" + i).before(html2);
                    $("#selectIn_" + i).html(html3);
				}
			}

			if ($("#field").html().trim() == "") {
				$("#field").html("버튼 링크가 없습니다.");
			}
		</script>

	</div>
	<div class="input_content_wrap planB">
		<label class="input_tit">
			<p class="txt_st_eng">STEP 4.</p>
			<p>2차발송 선택</p>
		</label>
		<div class="input_content form_check">
			<!-- label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger"><i></i></label -->
			<?
			if($sendtelauth > 0 && $mem1->mem_2nd_send!='NONE') { ?>
			<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select"><i></i></label>
			<!-- <?=$mem1->mem_2nd_send?> -->
			<button class="fr mg_l5 btn_yellow" onclick="getArimtalkCont();"><span class="material-icons">notifications_active</span> 알림톡내용 가져오기</button>
			<button class="btn md fr" onclick="getLinkCont();">링크 가져오기</button>
			<span class="noted">* 알림톡 실패건을 문자(SMS/LMS)로 재발송합니다.</span>
			<?
			} else {
				if ($mem1->mem_2nd_send=='NONE' || $mem1->mem_2nd_send=='') {
			?>
			<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>

			<button class="fr mg_l5 btn_yellow" onclick="getArimtalkCont();" style="display:none;"><span class="material-icons">notifications_active</span> 알림톡내용 가져오기</button>
			<button class="btn md fr" onclick="getLinkCont();" style="display:none;">링크 가져오기</button>
			<span class="info_text2">*2차 발신이 설정되지 않았습니다. 관리자에게 문의 하세요.</span>
			<?
				} else if ($sendtelauth == 0) { ?>
			<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>

			<button class="fr mg_l5 btn_yellow" onclick="getArimtalkCont();" style="display:none;"><span class="material-icons">notifications_active</span> 알림톡내용 가져오기</button>
			<button class="btn md fr" onclick="getLinkCont();" style="display:none;">링크 가져오기</button>
			<span class="info_text2">*발신번호가 등록되지 않아 2차 발신은 할 수 없습니다.</span>
			<?
				} else { ?>
			<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>

			<button class="fr mg_l5 btn_yellow" onclick="getArimtalkCont();" style="display:none;"><span class="material-icons">notifications_active</span> 알림톡내용 가져오기</button>
			<button class="btn md fr" onclick="getLinkCont();" style="display:none;">링크 가져오기</button>
			<span class="info_text2">*발신번호가 등록 및 2차 발신이 설정되지 않았습니다.</span>
			<?
				}
			} ?>
			<div class="switch_content" id="hidden_fields_sms" style="display: none;">
				<div class="sms_preview toggle">
					<div class="msg_type <?=($isdhn=="N")? "sms" : ""?>"></div>
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
						// if($tpl_button_cnt > 0){ //링크버튼형
						// 	//tpl_btn1_type : 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
						// 	if($rs->tpl_btn1_type == "E" and $dhnl_url != ""){
						// 		$lms_add_msg = "행사보기\n". str_replace('http://', '',$dhnl_url);
						// 	}else if($rs->tpl_btn1_type == "L" and $psd_url != ""){
						// 		$lms_add_msg = "행사보기\n". str_replace('http://', '',$psd_url);
						// 	}
						// 	if($lms_add_msg != ""){
						// 		$lms_base_msg .= "\n\n". $lms_add_msg;
						// 	}
						// }
					?>
					<textarea class="input_msg" id="lms" name="lms" placeholder="'입력' 또는 '알림톡 내용 가져오기' 버튼을 클릭하세요." onkeyup="onPreviewLms();chkword_lms();"><?=$lms_base_msg?></textarea>
					<span class="txt_byte"><span class="fl" style="color: #ff9a00;">발신번호 : <?=$this->funn->format_phone($rs1->spf_sms_callback,"-")?></span><span class="msg_type_txt sms" id="lms_limit_str"></span><span id="lms_num">0</span>/<span id="lms_character_limit">2000</span> bytes</span>
					<!--div class="txt_ad" id="lms_footer"></div-->
					<div class="txt_ad" id="lms_footer">
						<p id="kakotalk_link_text"></p>
						<span id="span_unsubscribe">무료수신거부 : </span><input type="text" class="input_option" id="unsubscribe_num" onkeyup="onPreviewLms(); chkword_lms();">
					</div>
				</div>
				<!--<input type="checkbox" id="lms_kakaolink_select"  class="input_cb" onclick="insert_kakao_link(this);" style="cursor: default;"><label for="lms_kakaolink_select">카카오 친구추가</label>-->
                <button class="btn md fr" onclick="smart_goods_call('lms')"><span class="material-icons" style="font-size: 14px;">login</span> 스마트전단 행사내용 불러오기</button>
				<button class="btn md fl btn_emoji" style="margin-right:5px;">특수문자</button>
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
						} else if($pla == '#{변수내용}' ||  $pla == '#{회원정보 등록일}'){
						    $var_str = "행사 기간";
						} else if($pla == '#{날짜}'){
						    $var_str = "행사 날짜";
                        } else if($pla == '#{00}'){
						    $var_str = "00";
						} else {
							$var_str = $pla;
						}
						//log_message("ERROR", $pla."/".$var_str);
						$tmp_str= preg_replace($variable,$var_str,$tmp_str, 1);
                        if(strpos($tmp_str,'00')==0){
                            $tmp_str = substr_replace($tmp_str, "", 0,2);
                        }
						$m_cnt = $m_cnt - 1;
						$idx = $idx + 1;
						//log_message("ERROR", $tmp_str);
					}
                    // 부가정보 추가
					if ($rs->tpl_extra != "" && $rs->tpl_extra != "NONE") {
					    $tmp_str.= "<br><br>".$rs->tpl_extra;
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
			<!--<p class="tit">
				버튼타입
			</p>-->
			<div class="al_talk_cate">
				<input type='hidden' name='category_cnt' id='category_cnt' value='<?=$category_cnt?>' />
				<input type='hidden' name='max_btn_cnt' id='max_btn_cnt' value='<?=$max_btn_cnt?>' />
				<input type='hidden' name='category_ids' id='category_ids' value='ALL' />
				<input type='hidden' name='btn_cnt_ids' id='btn_cnt_ids' value='ALL' />
       <div class="al_talk_cate_box1" style="display: none">
	       <span id="category_all" class="cate_all cate_on" onclick="category_click('ALL')"><i class="xi-check-circle"></i> 분류타입전체</span>
				<ul>
					<?
					foreach($category_list as $row) {
					?>
					<li><span id="category_name_<?=$row->tc_id?>" name="category_name" onclick="category_click('<?=$row->tc_id ?>')"><i class="xi-check-circle"></i> <?=$row->tc_description?></span></li>
					<?
					}
					?>
				</ul>
				</div>
				<div class="al_talk_cate_box2">
					<span id="btn_type_all" class="cate_all cate_on" onclick="btn_type_click('ALL')"><i class="xi-check-circle"></i> 버튼타입전체</span>
				<ul>
					<?
					foreach($max_btn_cnt_list as $row) {
					?>
					<li><span id="btn_type_name_<?=$row->tci_btn_cnt?>" name="btn_type_name" onclick="btn_type_click('<?=$row->tci_btn_cnt?>')"><i class="xi-check-circle"></i> 버튼<?=($row->tci_btn_cnt == 0) ? '없음' : $row->tci_btn_cnt ?></span></li>
					<?
					}
					?>
				</ul>
				</div>
			</div>
			<div class="list">
				<ul class="al_talk_tembox" id="modal_templet_list"><?//알림톡 샘플 리스트 영역?>
				</ul>
			</div>
		</div>
	  </div>
	</div>
	<script>
    	// 알림톡 카테고리 클릭 2021-09-19
    	function category_click(category_id) {
			<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 ?>
			var sortable_disabled = $("#modal_templet_list").sortable("option", "disabled");
			// alert(sortable_disabled);
			<? } ?>
    		if (category_id == 'ALL') {
    			$('#category_all').addClass('cate_on');
    			$('#category_ids').val('ALL');
    			$('span[name=category_name]').each(function() {
    				$(this).removeClass('cate_on');
    			});
				<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 ?>

				if (sortable_disabled == true) {
					if ($('#category_all').hasClass('cate_on') && $('#btn_type_all').hasClass('cate_on')) {
						$("#modal_templet_list").sortable("option", "disabled", false);
					}
				}
				<? } ?>
    		} else {
    			if ($('#category_all').hasClass('cate_on')) {
    				$('#category_all').removeClass('cate_on');
    				$('#category_ids').val('');
    			}

    			//var str_category_ids = $('#category_ids').val();
    			var str_category_ids = "";

    			if ($('#category_name_'+category_id).hasClass('cate_on')) {
    				$('#category_name_'+category_id).removeClass('cate_on');
    			} else {
    				$('#category_name_'+category_id).addClass('cate_on');
    			}

    			if ($('span[name=category_name].cate_on').length == $("#category_cnt").val() || $('span[name=category_name].cate_on').length == 0) {
    				$('#category_all').addClass('cate_on');
    				$('#category_ids').val('ALL');
     				$('span[name=category_name]').each(function() {
    					$(this).removeClass('cate_on');
    				});
     				<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 ?>

    				if (sortable_disabled == true) {
        				if ($('#category_all').hasClass('cate_on') && $('#btn_type_all').hasClass('cate_on')) {
    						$("#modal_templet_list").sortable("option", "disabled", false);
        				}
    				}
    				<? } ?>
    			} else {
    				<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 ?>
    				if (sortable_disabled == false) {
    					$("#modal_templet_list").sortable("option", "disabled", true);
    				}

    				<? } ?>
    				$('span[name=category_name]').each(function() {
    					if($(this).hasClass('cate_on')){
    						var id_temp = $(this).attr('id');
    						id_temp = id_temp.substring(id_temp.lastIndexOf('_') + 1);
    						// alert(id_temp);
    						if (str_category_ids.trim() == '') {
    							str_category_ids = id_temp;
    						} else {
    							str_category_ids += "," + id_temp;
    						}
    					}
            		});
    				$('#category_ids').val(str_category_ids);
    			}
    		}
    		//alert($('#category_ids').val());
    		adv_template_data();
    	}

    	// 알림톡 버튼타입 클릭 2021-09-19
    	function btn_type_click(btn_type_id) {
			<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 ?>
			var sortable_disabled = $("#modal_templet_list").sortable("option", "disabled");
			// alert(sortable_disabled);
			<? } ?>
    		if(btn_type_id == 'ALL') {
    			$('#btn_type_all').addClass('cate_on');
    			$('#btn_cnt_ids').val('ALL');
    			$('span[name=btn_type_name]').each(function() {
    				$(this).removeClass('cate_on');
    			});
				<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 ?>

				if (sortable_disabled == true) {
					if ($('#category_all').hasClass('cate_on') && $('#btn_type_all').hasClass('cate_on')) {
						$("#modal_templet_list").sortable("option", "disabled", false);
					}
				}
				<? } ?>
    		} else {
    			if ($('#btn_type_all').hasClass('cate_on')) {
    				$('#btn_type_all').removeClass('cate_on');
    			}

    			//var str_btn_cnt_ids = $('#btn_cnt_ids').val();
    			var str_btn_cnt_ids = "";

    			if ($('#btn_type_name_'+btn_type_id).hasClass('cate_on')) {
    				$('#btn_type_name_'+btn_type_id).removeClass('cate_on');
    			} else {
    				$('#btn_type_name_'+btn_type_id).addClass('cate_on');
    			}

    			if ($('span[name=btn_type_name].cate_on').length == $("#max_btn_cnt").val() || $('span[name=btn_type_name].cate_on').length == 0) {
    				$('#btn_type_all').addClass('cate_on');
       				$('#btn_cnt_ids').val('ALL');
    				$('span[name=btn_type_name]').each(function() {
    					$(this).removeClass('cate_on');
    				});
     				<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 ?>

    				if (sortable_disabled == true) {
        				if ($('#category_all').hasClass('cate_on') && $('#btn_type_all').hasClass('cate_on')) {
    						$("#modal_templet_list").sortable("option", "disabled", false);
        				}
    				}
    				<? } ?>
    			} else {
    				<? if($this->member->item('mem_level') >= 100){ //최고관리자 권한만 ?>
    				if (sortable_disabled == false) {
    					$("#modal_templet_list").sortable("option", "disabled", true);
    				}

    				<? } ?>
    				$('span[name=btn_type_name]').each(function() {
    					if($(this).hasClass('cate_on')){
    						var id_temp = $(this).attr('id');
    						id_temp = id_temp.substring(id_temp.lastIndexOf('_') + 1);
    						// alert(id_temp);
    						if (str_btn_cnt_ids.trim() == '') {
    							str_btn_cnt_ids = id_temp;
    						} else {
    							str_btn_cnt_ids += "," + id_temp;
    						}
    					}
            		});
    				$('#btn_cnt_ids').val(str_btn_cnt_ids);
    			}
    		}
    		//alert($('#btn_cnt_ids').val());
    		adv_template_data();

    	}

		// 템플릿 조회 함수 2021-09-19
		function adv_template_data() {
			var modal_tpl_no = 0;
			var first_tpl_id = "";
			var first_tpl_premium_yn = "";
			var first_btn_name = "";
			var contract_type = "<?=$this->member->item("mem_contract_type")?>";
			var category_ids = $('#category_ids').val();
			var btn_cnt_ids = $('#btn_cnt_ids').val();
			$("#modal_templet_list").html(""); //알림톡 샘플 리스트 영역 초기화
			//알림톡버튼 데이타 조회
			$.ajax({
				url: "/dhnbiz/sender/send/talk_adv_v3/ajax_adv_template_data",
				type: "POST",
				data: {"category_ids":category_ids, "tpl_btn_cnts":btn_cnt_ids, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
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
						// 프리미엄 서버스 기능 제거 하단 한줄 주석처리 2021-08-03
						//if(tpl_premium_yn == "Y" && contract_type != "P") rtn_link = "alert('프리미엄 서비스입니다.');";
						//btn_name += "  <p>"+ tpl_id +"</p>";
						if(tpl_id != "" && tpl_id != undefined){
							html += "<li id='"+ tpl_id +"' class='li_templet_list'>";
							// 프리미엄 서버스 기능 제거 하단 한줄 주석처리 2021-08-03
							//html += "  "+ badge; //뱃지
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

		}

		//알림톡버튼 데이타 미리보기
		function view_templet(tpl_id, tpl_premium_yn, btn_name){
			//alert("tpl_id : "+ tpl_id +", tpl_premium_yn : "+ tpl_premium_yn +"\n"+"btn_name : "+ btn_name);
			var tpl_contents = $("#tpl_contents_"+ tpl_id).val(); //템플릿 내용
            var tpl_extra = $("#tpl_extra_"+ tpl_id).val();
			if (tpl_extra && tpl_extra!='NONE') {
				tpl_extra = "<br><br>"+tpl_extra;
			}else{
                tpl_extra ='';
            }

			tpl_contents = tpl_contents.replace(/#{변수내용}/gi, '행사 기간');
            tpl_contents = tpl_contents.replace(/#{회원정보 등록일}/gi, '행사 기간');
			tpl_contents = tpl_contents.replace(/#{업체명}/gi, '업체명');
			tpl_contents = tpl_contents.replace(/#{업체전화번호}/gi, '전화번호');
			tpl_contents = tpl_contents.replace(/#{전화번호}/gi, '전화번호');
			tpl_contents = tpl_contents.replace(/#{날짜}/gi, '행사 날짜');

			$("#modal_templet_msg").html(tpl_contents + tpl_extra); //템플릿 내용
			$("#modal_templet_btn").html(btn_name); //템플릿 버튼
		}

		//알림톡버튼 모달
		var modal_templet = document.getElementById("modal_templet");
		function open_templet(){
			msg_save('N');//알림톡 내용 임시저장
			var modal_tpl_no = 0;
			var first_tpl_id = "";
			var first_tpl_premium_yn = "";
			var first_btn_name = "";
			var contract_type = "<?=$this->member->item("mem_contract_type")?>";
			var category_ids = $('#category_ids').val();
			var btn_cnt_ids = $('#btn_cnt_ids').val();
			$("#modal_templet_list").html(""); //알림톡 샘플 리스트 영역 초기화
			//알림톡버튼 데이타 조회
			$.ajax({
				url: "/dhnbiz/sender/send/talk_adv_v3/ajax_adv_template_data",
				type: "POST",
				data: {"category_ids":category_ids, "tpl_btn_cnts":btn_cnt_ids, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
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
                        var tpl_extra = value.tpl_extra;
						if (tpl_extra == null) {
							tpl_extra = "";
						}
						if(btn_cnt == 0){
							rtn_name = "<p>버튼없음</p>";
						}else{
							rtn_name = btn_name;
						}
						var rtn_link = "selected_template('"+ tpl_id +"', 'temp');";
						// 프리미엄 서버스 기능 제거 하단 한줄 주석처리 2021-08-03
						//if(tpl_premium_yn == "Y" && contract_type != "P") rtn_link = "alert('프리미엄 서비스입니다.');";
						//btn_name += "  <p>"+ tpl_id +"</p>";
						if(tpl_id != "" && tpl_id != undefined){
							html += "<li id='"+ tpl_id +"' class='li_templet_list'>";
							// 프리미엄 서버스 기능 제거 하단 한줄 주석처리 2021-08-03
							//html += "  "+ badge; //뱃지
							html += "  <div class=\"li_templet_list_box\" onClick=\""+ rtn_link +"\">";
							html += "    <div>"+ rtn_name +"</div>"; //버튼
							html += "  </div>";
							html += "  <div class=\"type_btn\">";
							html += "    <button onClick=\"view_templet('"+ tpl_id +"', '"+ tpl_premium_yn +"', '"+ btn_name +"');\"><i class=\"xi-plus-circle-o\"></i> 미리보기</button>";
							html += "    <button onClick=\""+ rtn_link +"\"><i class=\"xi-check-circle-o\"></i> 선택하기</button>";
							html += "    <textarea id='tpl_contents_"+ tpl_id +"' style='display:none;'>"+ tpl_contents +"</textarea>";
                            html += "    <input type='hidden' id='tpl_extra_"+ tpl_id +"' value='"+ tpl_extra +"' />";
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
					url: "/dhnbiz/sender/send/talk_adv_v3/ajax_adv_template_sort",
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
		var previewText = $("#text").html() + '<?=$str_button?>';
		$("#text").html(previewText);
	}

	document.getElementById("body_top").scrollIntoView(); //TOP 위치로 스크롤 이동
	//open_templet(); //알림톡버튼 변경하기 모달창 오픈
</script>
