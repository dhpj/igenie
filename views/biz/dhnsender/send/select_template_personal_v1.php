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
<?if($pcr_id) {?>
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
	<div class="input_content_wrap personal_msg">
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
				<!-- <div class="preview_option flex">
					<button class="btn_preview_option" id="msg_save_temp" onclick="view_preview();">응모페이지</button>
					<button class="btn_preview_option" id="open_msg_list_" onclick="view_result();">당첨페이지</button>
				</div> -->
				<? } // 2019.08.08. 쿠폰 발송 관련 끝 ?>
				<!-- <div class="preview_option flex" style="display:none;">
					<button class="btn_preview_option" onClick="msg_save('Y');">내용저장</button>
					<button class="btn_preview_option" onClick="open_msg_lms_list();">불러오기</button>
				</div> -->
			</div>
			<div class="preview_home"></div>
		</div><!-- mobile_preview END -->
		<label class="input_tit" id="id_STEP1">
			<p class="txt_st_eng">STEP 1.</p>
			<p>소재 선택</p>
		</label>
		<div class="input_content">
			<button class="btn_add_gr btn_myModal" onclick="btnSelectTemplate()"><span class="material-icons">link</span>소재 변경하기</button>
			<p class="noted">* 소재를 변경하시려면 위 버튼을 클릭하세요.</p>
			<p class="add_file_view_tit">등록된 이미지</p>
            <dl id="aaaaaa" class="add_file_view">
                <dt>
                    <img id="aaaaa" src="/uploads/creative/2/2023/10/5b1edd1c4aa41666e0653da8621698db.jpg" alt="소재등록 이미지">
                </dt>
                <dd>
                    <span id="aaaaaa" class="add_file_name">aaaaaaaa</span>
                </dd>
            </dl>
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
				<input type="hidden" id="tpl_emphasizetype_h" value="<?=$rs->tpl_emphasizetype?>" />
				<input type="hidden" id="tpl_imageurl" value="<?=$rs->tpl_imageurl?>" />
				<div class="input_msg" style="height: auto;" id="templi_cont">
				<?
                $para = json_decode($crs->pcr_para);
				$tmp_str = $para->title;
				$tmp_str = str_replace("\n\n", "<p>&nbsp;</p>", $tmp_str);
				$tmp_str = nl2br($tmp_str);
				$pattern = '/(?=\$\{)(.*?)(\})/';
				$m_cnt = preg_match_all($pattern, $tmp_str, $match );
				$idx = 0;
				$m_idx = 0;
				while($m_cnt >= 1) {
				    $var_value = "";
				    if(!empty($varvalue)) {
				        $var_value = $varvalue[$idx]->var_text;
				    }
				    $pla =  '';
                    foreach($this->kakaolib->parameters as $key1 => $a){
                        foreach($a as $key2 => $b){
                            if ($match[0][$idx] == $b){
                                $pla = $this->kakaolib->parameters[$key1][0] . '[' . $key2 . ']';
                                break;
                            }
                        }
                    }
				    $variable = '/\\'.$match[0][$idx].'/';
				    $var_str = '<textarea data-key ="' . $match[0][$idx] . '" name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="'.$pla.'">'.$var_value.'</textarea>';
			        $tmp_str= preg_replace($variable,$var_str,$tmp_str, 1);
				    $m_cnt = $m_cnt - 1;
				    $idx = $idx + 1;
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
	<div class="input_content_wrap" style="display:<?=!empty($para->button) ? "" : "none"?>;">
		<label class="input_tit" id="id_STEP3">
			<p class="txt_st_eng">STEP 3.</p>
			<p>버튼내용 선택</p>
		</label>
        <div class="input_content">
        <?foreach($para->button as $key => $a){?>
    		<div id="pre_set_<?=$key?>" class='pre_set' last-id="<?=$key?>" style="display: ;">
    			<div class="link_info_wrap">
    				<div class="input_link_wrap">
                        <div id='div_blank_box<?=$key?>' style='margin: 0px 0px 10px 0px;'></div>
                        <div id='div_smart_box<?=$key?>' style="display:none;">
                            <button class='btn_add' id="psd_like_" onClick="smart_page('1', <?=$key?>);">전단불러오기</button>
                        </div>
                        <div id='div_coupon_box<?=$key?>' style="display:none;">
                            <button class='btn_add' onClick='coupon_page(1, <?=$key?>);'>쿠폰불러오기</button>
                        </div>
                        <input type="hidden"  name="btn_type" id="btn_type<?=$key?>" value="WL"/>
                        <input type="hidden" name="link_type_option" id="link_type_option<?=$key?>" value="self"/>
                        <select style="width: 120px;" class="fl"  name="link_type2" id="link_type<?=$key?>" onchange='chg_link_type(<?=$key?>, this);'>
                            <option value='self'>직접입력</option>
                            <option value='smart'>스마트전단</option>
                            <option value='editor'>에디터</option>
                            <option value='coupon'>스마트쿠폰</option>
    						<!-- <option value='AL'>앱링크</option>
    						<option value='BK'>봇키워드</option>
    						<option value='MD'>메시지전달</option> -->
    					</select>
    					<div style="overflow: hidden;" id="btn_web_like_"><input type="text" name="btn_name2" id="btn_name2_<?=$key?>" style="width:100%; margin-left: -1px;" maxlength="26" value='<?=$a->title?>' readonly></div>
    					<div style="overflow: hidden; display: none" id="btn_app_like_"><input type="text" name="btn_name3"  id="btn_name3_<?=$key?>" style="width:100%; margin-left: -1px;" value='<?=$a->title?>' readonly></div>
    					<div style="overflow: hidden; display: none" id="btn_bots_like_"><input type="text" name="btn_name4" id="btn_name4_<?=$key?>" style="width:100%; margin-left: -1px;" value='<?=$a->title?>' readonly></div>
    					<div style="overflow: hidden; display: none" id="btn_msg_like_"><input type="text" name="btn_name5" id="btn_name5_<?=$key?>" style="width:100%; margin-left: -1px;" value='<?=$a->title?>' readonly></div>

    				</div>
    				<div class="input_link_wrap" id="web_like_<?=$key?>">
    					<button class="btn md fr" style="margin-left: -1px;" id="find_url" onclick="linkview(<?=$key?>)">링크확인</button>
    					<div style="overflow: hidden;">
    					<input type="url" name="btn_url21" id="btn_url_<?=$key?>" style="width:100%;" placeholder="링크주소를 입력해주세요." onkeyup= "">
    					</div>
    				</div>
    				<div class="input_link_wrap" id="pc_web_like_<?=$key?>" style="display: none">
    					<button class="btn md fr" style="margin-left: -1px;" id="pc_find_url" onclick="urlConfirm(this)">링크확인</button>
    					<div style="overflow: hidden;">
    					<input type="url" name="btn_url22" style="width:100%;" placeholder="링크주소를 입력해주세요.">
    					</div>
    				</div>
    				<div class="input_link_wrap" id="app_like_android_<?=$key?>" style="display: none">
    					<button class="btn md fr" style="margin-left: -1px;" id="android_find_url" onclick="urlConfirm(this)">링크확인</button>
    					<div style="overflow: hidden;">
    					<input type="url" name="btn_url31" style="width:100%;" placeholder="Android App 링크주소를 입력해주세요.">
    					</div>
    				</div>
    				<div class="input_link_wrap" id="app_like_ios_<?=$key?>" style="display: none">
    					<button class="btn md fr" style="margin-left: -1px;" id="iso_find_url" onclick="urlConfirm(this)">링크확인</button>
    					<div style="overflow: hidden;">
    					<input type="url" name="btn_url32" style="width:100%;" placeholder="iOS App 링크주소를 입력해주세요.">
    					</div>
    				</div>
    			</div>
    			<div class="selection_content"></div>
    		</div>
            <?}?>
    		<div id="field"></div>
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/dhnsender/send/bootstrap/_inc_leaflet_personal_v1.php'); ?>
    	</div>
	</div>
	<div class="input_content_wrap planB">
		<label class="input_tit">
			<p class="txt_st_eng">STEP <?=!empty($para->button) ? "4" : "3"?>.</p>
			<p>2차발송 선택</p>
		</label>
        <div class="input_content">
    		<?

    		if($sendtelauth > 0) { ?>
    		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_talk" class="trigger" id="lms_select"><i></i></label>
            <? if($sendtelauth > 0 && $mem1->mem_2nd_send!='NONE') { ?>
            <!-- <button id="getFriend_btn" class="fr mg_l5 btn_yellow" onclick="getFriendtalkCont();"><span class="material-icons">notifications_active</span> 친구톡내용 가져오기</button> -->
            <button id="getLink_btn" class="btn md fr" onclick="getLinkCont('#lms');">링크 가져오기</button>
    		<span class="noted" id="note2nd">*친구톡 실패건을 문자(SMS/LMS)로 재발송합니다.</span>
            <? } ?>
    		<div id="div_second_type" class="checks">
    			<input type="radio" name="second_type" value="ms" id="second_type_ms" onClick="chg_second_type();" checked><label for="second_type_ms" style="margin-left: -6px;">문자발송</label>
                <input type="radio" name="second_type" value="at" id="second_type_at" onClick="chg_second_type();"><label for="second_type_at">알림톡발송</label>
    		</div>
    		<?
    		} else {
    			if ($mem->mem_2nd_send=='NONE' || $mem->mem_2nd_send=='') {
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
    			<!-- 메시지 미리보기 -->
    			<div class="sms_preview toggle">
    				<div class="msg_type <?=($isdhn=="N")? "lms" : ""?>" id="preview_msg_type"></div>
    				<div class="preview_circle"></div>
    				<div class="preview_round"></div>
    				<div class="preview_msg_wrap">
    					<div class="preview_msg_window lms" id="lms_preview"></div>
    				</div>
    				<div class="preview_home"></div>
    			</div><!-- mobile_preview END -->
    			<div class="msg_box">
    				<?php // 문자 내용 헤드, 풋 수정 2019-07-25 ?>
    				<!-- div class="txt_ad" id="lms_header"></div-->
    				<div class="txt_ad" id="lms_header">
    					<span id="span_adv">(광고)</span>
    					<input type="text" class="input_option" style="width: 320px;" id="companyName" onkeyup="onPreviewLms(); chkword_lms(); keyup_lms_header()" value="<?=$rs->spf_company?>">
    				</div>
    				<textarea class="input_msg" id="lms" name="lms" placeholder="'입력' 또는 '친구톡 내용 가져오기' 버튼을 클릭하세요." onkeyup="onPreviewLms();chkword_lms();"></textarea>
    				<span class="txt_byte"><span class="fl" style="color: #ff9a00;">발신번호 : <?=$this->funn->format_phone($rs->spf_sms_callback,"-")?></span><span class="msg_type_txt lms" id="lms_limit_str">SMS</span><span id="lms_num">0</span>/<span id="lms_character_limit">80</span> bytes</span>
    				<!-- div class="txt_ad" id="lms_footer"></div-->
    				<div class="txt_ad" id="lms_footer">
    					<p id="kakotalk_link_text"></p>
    					<span id="span_unsubscribe">무료수신거부 : </span>
    					<input type="text" class="input_option" id="unsubscribe_num" onkeyup="onPreviewLms(); chkword_lms();">
    				</div>
    				<input type="hidden" name="tit" id="tit">
    			</div>
    			<input type="checkbox" id="lms_kakaolink_select" onclick="insert_kakao_link(this);" style="cursor: default;"><label for="lms_kakaolink_select" id="lms_kakaolink_select_text">카카오 친구추가 주소 삽입</label>
    			<!-- <button class="btn md fr" style="margin-left:10px;" onclick="getKakaoCont();">친구톡내용 가져오기</button> -->
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
    		</div>
    		<div class="switch_content" id="hidden_fields_talk" style="display: none;">알림톡 템플릿 영역<?//알림톡 템플릿 영역?>
    		</div>
    	</div>
	</div>
    <div class="input_content_wrap" id="jump_3rd" style="display:none ;">
    	<label class="input_tit">3차발송</label>
    	<div class="input_content">

    		<?
    		if($sendtelauth > 0) { ?>
    		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_talk" class="trigger" id="lms_select2"><i></i></label>
            <? if($sendtelauth > 0 && $mem1->mem_2nd_send!='NONE') { ?>
            <!-- <button id="getFriend_btn2" class="fr mg_l5 btn_yellow" onclick="getFriendtalkCont2();"><span class="material-icons">notifications_active</span> 친구톡내용 가져오기</button> -->
            <button id="getLink_btn2" class="btn md fr" onclick="getLinkCont('#lms3');">링크 가져오기</button>
    		<span class="noted" id="note2nd">*2차 알림톡 실패건을 문자(SMS/LMS)로 재발송합니다.</span>
            <? } ?>
    		<!-- <span class="info_text pen"><- 2차 알림톡 <em class="marker">발송실패 고객</em>에게 <em class="marker">문자</em>로 <em class="marker">재발송</em>합니다.</span> -->
    												<?
    		} else {
    			if ($mem->mem_2nd_send=='NONE' || $mem->mem_2nd_send=='') {;
    		?>
            <label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select2" disabled><i></i></label>
    		<span class="info_text">*2차 발신이 설정되지 않았습니다. 관리자에게 문의 하세요.</span>
    		<?
    			} else if ($sendtelauth == 0) { ?>
    		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select2" disabled><i></i></label>
    		<span class="info_text">*발신번호가 등록되지 않아 2차 발신은 할 수 없습니다.</span>
    		<?
    			} else { ?>
    		<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select2" disabled><i></i></label>
    		<span class="info_text">*발신번호가 등록 및 2차 발신이 설정되지 않았습니다.</span>
    		<?
    														}
    		} ?>
    		<div class="switch_content" id="hidden_fields_sms2" style="display: none;">
    			<!-- 메시지 미리보기 -->
    			<div class="sms_preview toggle">
    				<div class="msg_type2  <?=($isdhn=="N")? "lms" : ""?>" id="preview_msg_type3"></div>
    				<div class="preview_circle"></div>
    				<div class="preview_round"></div>
    				<div class="preview_msg_wrap">
    					<div class="preview_msg_window lms" id="lms_preview3"></div>
    				</div>
    				<div class="preview_home"></div>
    			</div><!-- mobile_preview END -->

    			<div class="msg_box">
    				<?php // 문자 내용 헤드, 풋 수정 2019-07-25 ?>
    				<!-- div class="txt_ad" id="lms_header"></div-->
    				<div class="txt_ad" id="lms_header2">
    					<span id="span_adv2">(광고)</span>
    					<input type="text" class="input_option" style="width: 320px;" id="companyName2" onkeyup="onPreviewLms2(); chkword_lms2(); keyup_lms_header2()" value="<?=$rs->spf_company?>">
    				</div>
    				<textarea class="input_msg" id="lms3" name="lms3" placeholder="'입력' 또는 '친구톡 내용 가져오기' 버튼을 클릭하세요." onkeyup="onPreviewLms2();chkword_lms2();"></textarea>
    				<span class="txt_byte"><span class="msg_type_txt2 sms" id="lms_limit_str2"></span><span id="lms_num2">0</span>/<span id="lms_character_limit2">90</span> bytes</span>
    				<!-- div class="txt_ad" id="lms_footer"></div-->
    				<div class="txt_ad" id="lms_footer2">
    					<p id="kakotalk_link_text2"></p>
    					<span id="span_unsubscribe2">무료수신거부 : </span>
    					<input type="text" class="input_option" id="unsubscribe_num2" onkeyup="onPreviewLms2(); chkword_lms2();">
    				</div>
    				<input type="hidden" name="tit" id="tit">
    			</div>
    			<input type="checkbox" id="lms_kakaolink_select2" onclick="insert_kakao_link(this);" style="cursor: default;"><label for="lms_kakaolink_select2">카카오 친구추가 삽입</label>
    			<!-- <button class="btn md fr" style="margin-left:10px;" onclick="getKakaoCont('2');">친구톡내용 가져오기</button> -->
    			<button class="btn md btn_emoji2 fr">특수문자</button>
    			<div class="clearfix"></div>

    			<div class="toggle_layer_wrap toggle_layer_emoji2" id="btn_emoji2">
    					<h3>특수문자<i class="material-icons icon_close fr" id="icon_close">close</i></h3>
    					<div class="layer_content emoticon_wrap clearfix">
    						<ul>
    											<li onclick="insert_char('☆', 'lms3')">☆</li>
    											<li onclick="insert_char('★', 'lms3')">★</li>
    											<li onclick="insert_char('○', 'lms3')">○</li>
    											<li onclick="insert_char('●', 'lms3')">●</li>
    											<li onclick="insert_char('◎', 'lms3')">◎</li>
    											<li onclick="insert_char('⊙', 'lms3')">⊙</li>
    											<li onclick="insert_char('◇', 'lms3')">◇</li>
    											<li onclick="insert_char('◆', 'lms3')">◆</li>
    											<li onclick="insert_char('□', 'lms3')">□</li>
    											<li onclick="insert_char('■', 'lms3')">■</li>
    											<li onclick="insert_char('▣', 'lms3')">▣</li>
    											<li onclick="insert_char('△', 'lms3')">△</li>
    											<li onclick="insert_char('▲', 'lms3')">▲</li>
    											<li onclick="insert_char('▽', 'lms3')">▽</li>
    											<li onclick="insert_char('▼', 'lms3')">▼</li>
    											<li onclick="insert_char('◁', 'lms3')">◁</li>
    											<li onclick="insert_char('◀', 'lms3')">◀</li>
    											<li onclick="insert_char('▷', 'lms3')">▷</li>
    											<li onclick="insert_char('▶', 'lms3')">▶</li>
    											<li onclick="insert_char('♡', 'lms3')">♡</li>
    											<li onclick="insert_char('♥', 'lms3')">♥</li>
    											<li onclick="insert_char('♧', 'lms3')">♧</li>
    											<li onclick="insert_char('♣', 'lms3')">♣</li>
    											<li onclick="insert_char('◐', 'lms3')">◐</li>
    											<li onclick="insert_char('◑', 'lms3')">◑</li>
    											<li onclick="insert_char('▦', 'lms3')">▦</li>
    											<li onclick="insert_char('☎', 'lms3')">☎</li>
    											<li onclick="insert_char('♪', 'lms3')">♪</li>
    											<li onclick="insert_char('♬', 'lms3')">♬</li>
    											<li onclick="insert_char('※', 'lms3')">※</li>
    											<li onclick="insert_char('＃', 'lms3')">＃</li>
    											<li onclick="insert_char('＠', 'lms3')">＠</li>
    											<li onclick="insert_char('＆', 'lms3')">＆</li>
    											<li onclick="insert_char('㉿', 'lms3')">㉿</li>
    											<li onclick="insert_char('™', 'lms3')">™</li>
    											<li onclick="insert_char('℡', 'lms3')">℡</li>
    											<li onclick="insert_char('Σ', 'lms3')">Σ</li>
    											<li onclick="insert_char('Δ', 'lms3')">Δ</li>
    											<li onclick="insert_char('♂', 'lms3')">♂</li>
    											<li onclick="insert_char('♀', 'lms3')">♀</li>
    						</ul>
    					</div>
    					<div class="layer_content emoticon_wrap_multi clearfix">
    						<ul>
    											<li onclick="insert_char('^0^', 'lms3')">^0^</li>
    											<li onclick="insert_char('☆(~.^)/', 'lms3')">☆(~.^)/</li>
    											<li onclick="insert_char('づ^0^)づ', 'lms3')">づ^0^)づ</li>
    											<li onclick="insert_char('(*^.^)♂', 'lms3')">(*^.^)♂</li>
    											<li onclick="insert_char('(o^^)o', 'lms3')">(o^^)o</li>
    											<li onclick="insert_char('o(^^o)', 'lms3')">o(^^o)</li>
    											<li onclick="insert_char('=◑.◐=', 'lms3')">=◑.◐=</li>
    											<li onclick="insert_char('_(≥∇≤)ノ', 'lms3')">_(≥∇≤)ノ</li>
    											<li onclick="insert_char('q⊙.⊙p', 'lms3')">q⊙.⊙p</li>
    											<li onclick="insert_char('^.^', 'lms3')">^.^</li>
    											<li onclick="insert_char('(^.^)V', 'lms3')">(^.^)V</li>
    											<li onclick="insert_char('*^^*', 'lms3')">*^^*</li>
    											<li onclick="insert_char('^o^~~♬', 'lms3')">^o^~~♬</li>
    											<li onclick="insert_char('^.~', 'lms3')">^.~</li>
    											<li onclick="insert_char('S(*^___^*)S', 'lms3')">S(*^___^*)S</li>
    											<li onclick="insert_char('^Δ^', 'lms3')">^Δ^</li>
    											<li onclick="insert_char('＼(*^▽^*)ノ', 'lms3')">＼(*^▽^*)ノ</li>
    											<li onclick="insert_char('^L^', 'lms3')">^L^</li>
    											<li onclick="insert_char('^ε^', 'lms3')">^ε^</li>
    											<li onclick="insert_char('^_^', 'lms3')">^_^</li>
    											<li onclick="insert_char('(ノ^O^)ノ', 'lms3')">(ノ^O^)ノ</li>
    						</ul>
    					</div>
    				</div>
    		</div>
    	</div>
    </div>
	<script >
        //버튼링크 가져오기
        function getLinkCont(target) {
            var dumyTempli_cont = '';
            // if(get_btn_cnt > 0){ //버튼이 있는 경우

                var btn_url = "";
                var newline = "";
                var maxnum = $('.pre_set').length;
                if(maxnum>0){
                    for(i=0;i<maxnum;i++){
                        if($('#btn_url_'+i).val() !=''){
                            if(btn_url!=''){
                                newline = "\n";
                            }
                            var btn_name_temp = $('#btn_name2_'+i).val();
                            btn_url += newline + btn_name_temp.replace(/\s/gi, "") + "\n" + $('#btn_url_'+i).val();
                        }
                    }

                    if(btn_url != ""){
                        dumyTempli_cont = btn_url;
                    }
                }


            $(target).val(dumyTempli_cont);
            chkword_lms();
            onPreviewLms();
        }

        $("#lms_select").change(function() {
            if($(this).is(":checked")) {
                $("#getLink_btn").show();
                $("#getFriend_btn").show();
                $("#div_second_type").css("display", "block"); //2차발송 > 2차발송 구분 선택 영역 열기
                $("#hidden_fields_talk").css("display", "none"); //2차발송 > 알림톡발송 영역 닫기
                chg_second_type(); //2차발송 구분(ms.문자, at.알림톡) 선택시
                $("#hidden_fields_sms").css("display", "block"); //2차발송 > 문자발송 영역 닫기
                $("#hidden_fields_talk").css("display", "none"); //2차발송 > 알림톡발송 영역 닫기
            } else {
                $("#getLink_btn").hide();
                $("#getFriend_btn").hide();
                $("#div_second_type").css("display", "none"); //2차발송 > 구분 선택 영역 닫기
                $("#hidden_fields_sms").css("display", "none"); //2차발송 > 문자발송 영역 닫기
                $("#hidden_fields_talk").css("display", "none"); //2차발송 > 알림톡발송 영역 닫기
            }
        });

        $("#lms_select2").change(function() {
            if($(this).is(":checked")) {
                $("#hidden_fields_sms2").css("display", "block"); //3차발송 > 문자발송 영역 열기
                onPreviewLms2();
                chkword_lms2();
            } else {
                $("#hidden_fields_sms2").css("display", "none"); //3차발송 > 문자발송 영역 열기
                onPreviewLms2();
                chkword_lms2();
            }
        });

        function onPreviewLms2() {
    		<? // 문자 헤드부분 회사명 변경가능하도록 수정 미리보기 처리 2019-07-25 ?>
    		//var lms_header_temp = $("#lms_header").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");
    		var lms_header_temp = $("#span_adv2").text() + $("#companyName2").val();
    		var lms_footer_temp = "";
    		if($("#kakotalk_link_text2").text() == "") {
    			lms_footer_temp = $("#span_unsubscribe2").text() + $("#unsubscribe_num2").val();
    		} else {
    			lms_footer_temp = $("#kakotalk_link_text2").text() + "\n" +$("#span_unsubscribe2").text() + $("#unsubscribe_num2").val();
    		}
    		//var lms_footer_temp = $("#lms_footer").html().replace(/<BR>/g, "\n").replace(/<br>/g, "\n");

    		var msg_text = "";

    		if ($("#lms3").val().replace(/\n/g, "").length > 0) {
    			msg_text = msg_text + lms_header_temp + "\n" + $("#lms3").val() + "\n" + lms_footer_temp;
    		} else {
    			msg_text = msg_text + lms_header_temp + "\n\n메세지를 입력해주세요.\n\n" + lms_footer_temp;
    		}

            // 2019.01.21 이수환 특수문자 제거 테스트
            //msg_text = msg_text.replace(/[\u2100-\u2120\u2123-\u214F\u2190-\u21FF\u0080-\u008F\uFE50-\uFE6F\u2460-\u24FF\u2300-\u259F\u25A2\u25A4-\u25A5\u25A7-\u25B1\u25B4-\u25B5\u25B8-\u25BB\u25BE-\u25BF\u25C2-\u25C5\u25C8-\u25CA\u25CC-\u25CD\u25D2-\u2604\u2607-\u260D\u260F-\u261D\u261F-\u263F\u2641\u2643-\u2660\u2662\u2664\u2666\u2668-\u2669\u266B\u266D-\u303F\u3100-\u32FF\u2150-\u2189\u2200-\u2298\u229A-\u22FF\u0080-\u00FF\u2000-\u203A\u203C-\u206F\uFF00-\uFF02\uFF04-\uFF05\uFF07-\uFF1F\uFF21-\uFFEF\u3130-\u318F\u0000-\u0009\u3300-\u33FF\u0400-\u04FF\u2070-\u209C\u2714]/gi, "");
            //msg_text = msg_text.replace(/[\u200B\u3000\u3164\uDB40\u272A]/gi, "");
            //msg_text = msg_text.replace(/[\u2027]/gi, " ");

    		msg_text = msg_text.replace(/\n/g, "<br>");
    		//2021-01-20 2차 MMS 발송 추가.
    		//$("#lms_preview").html(msg_text);
    		preview_text = $("#lms_preview3").html();
    		if(preview_text.lastIndexOf("\">") < 0) {
    			preview_text = "";
    		} else {
    			preview_text = preview_text.substring(0, preview_text.lastIndexOf("\">") + 2);
    		}
    		$("#lms_preview3").html(preview_text + msg_text);
    	}

        //2차발송 구분(ms.문자, at.알림톡) 선택시
    	function chg_second_type(){
    		var second_type = value_check("second_type"); //2차발송 구분(ms.문자, at.알림톡) 선택값
    		//alert("second_type : "+ second_type);
    		if(second_type == "at"){ //구분(ms.문자, at.알림톡)
    			$("#hidden_fields_sms").css("display", "none"); //2차발송 > 문자발송 영역 닫기
    			$("#hidden_fields_talk").css("display", "block"); //2차발송 > 알림톡발송 영역 열기
                $("#getLink_btn").hide();
                $("#getFriend_btn").hide();
    			$("#hidden_fields_talk").html("").load(
    				"/dhnbiz/sender/send/select_template_second_v4",
    				{ <?=$this->security->get_csrf_token_name() ?>: "<?=$this->security->get_csrf_hash() ?>", "add" : "<?=$add?>" },
    				function() {
                        chg_al_btn();
    				}
    			);
                $("#jump_3rd").show();
                // $("#lms_select2").prop("checked", true);
    		    // $("#hidden_fields_sms2").css("display", "block");
                // onPreviewLms2();
                // chkword_lms2();

    		}else{
    			$("#hidden_fields_sms").css("display", "block"); //2차발송 > 문자발송 영역 열기
    			$("#hidden_fields_talk").css("display", "none"); //2차발송 > 알림톡발송 영역 닫기
                $("#getLink_btn").show();
                $("#getFriend_btn").show();
                $("#jump_3rd").hide();
                // $("#lms_select2").prop("checked", false);
    		    // $("#hidden_fields_sms2").css("display", "none");
    		}
    	}

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
					if ($rs->tpl_imageurl != "") {
					    $tmp_str = "<img src='".$rs->tpl_imageurl."' style='width:100%' /><br>".$tmp_str;
					}
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
						} else if($pla == '#{변수내용}' || $pla == '#{회원정보 등록일}'){
						    $var_str = "행사 기간";
						} else if($pla == '#{날짜}'){
						    $var_str = "행사 날짜";
                        } else if($pla == '#{00}'){
						    $var_str = "00";
						}else{
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
				<div class="al_talk_cate_box1">
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
			<div class="list" style="height:410px;">
				<ul class="al_talk_tembox" id="modal_templet_list"><?//알림톡 샘플 리스트 영역?>
				</ul>
			</div>
		</div>
	  </div>
	</div>
<?} else {?>
	<div class="input_content_wrap">
			<label class="input_tit">소재 선택</label>
			<div class="input_content">
				<div class="nodata">
				<i class="material-icons icon_error">error_outline</i>
				<p>선택된 소재가 없습니다. 소재를 선택해 주세요.</p>
				<button class="btn md yellow plus"	onclick="btnSelectTemplate();" id="modalBtn">소재 선택하기</button>
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

    var presel = '';
    $(document).on('click', "[name='link_type2']", function(){
        presel = $(this).val();
    });

    //링크 타입 변경
	function chg_link_type(id, t){
		var link_type_option = document.getElementById("link_type_option"+id).value;
		var link_type = $("#link_type"+id).val(); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)
		var btn_url = "";
        var stop_flag = false;

        if (link_type == 'editor'){
            $.each($("[name='link_type2']"), function(idx, val){
                if($(t).attr('id') != $(this).attr('id')) {
                    if ($(this).val() == 'editor') {
                        stop_flag = true;
                        return false;
                    }
                }
            });
            if (stop_flag){
                showSnackbar('에디터는 최대 1개만 활성화 가능합니다.', 1500);
                $(t).val(presel).attr('checked', true);
                $("#btn_url"+id).attr("readonly", false);
                $("#btn_url"+id).css("background-color", "#ffffff");
                return false;
            } else {
                // $("#btn_url"+id).val(document.getElementById("dhnl_url").value);
            }
        }

        var edi_flag = false;
        $.each($("[name='link_type2']"), function(idx, val){
            if ($(this).val() == 'editor') edi_flag = true;
        });

        $('#btn_url_' + id).val('');
		if(link_type == "self"){ //직접입력
            $('#btn_url_' + id).attr('readonly', false);
			document.getElementById("div_smart_box"+id).style.display = "none"; //스마트전단 영역 닫기
			document.getElementById("div_coupon_box"+id).style.display = "none"; //스마트쿠폰 영역 닫기
            if(!edi_flag){
                document.getElementById("div_editor_box").style.display = "none"; //에디터 영역 닫기
            }
			document.getElementById("div_blank_box"+id).style.display = "none"; //사용안함 영역 열기
		}else if(link_type == "editor"){ //스마트전단+에디터
            $('#btn_url_' + id).attr('readonly', true);
            $('#btn_url_' + id).val('http://smart.dhn.kr/at/' + $('#biz_url').val());
			btn_url = document.getElementById("dhnl_url").value; //에디터 링크 URL
			document.getElementById("div_smart_box"+id).style.display = "none"; //스마트전단 영역 닫기
			document.getElementById("div_coupon_box"+id).style.display = "none"; //스마트쿠폰 영역 닫기
			document.getElementById("div_editor_box").style.display = ""; //에디터 영역 열기
			document.getElementById("div_blank_box"+id).style.display = "none"; //사용안함 영역 닫기
		}else if(link_type == "coupon"){ //스마트쿠폰
            $('#btn_url_' + id).attr('readonly', true);
			btn_url = document.getElementById("pcd_url").value; //스마트쿠폰 링크 URL
			document.getElementById("div_smart_box"+id).style.display = "none"; //스마트전단 영역 닫기
			document.getElementById("div_coupon_box"+id).style.display = "inline-block"; //스마트쿠폰 영역 열기
            if(!edi_flag){
                document.getElementById("div_editor_box").style.display = "none"; //에디터 영역 닫기
            }
			document.getElementById("div_blank_box"+id).style.display = "none"; //사용안함 영역 닫기
		}else{ //스마트전단
            $('#btn_url_' + id).attr('readonly', true);
			btn_url = document.getElementById("psd_url").value; //스마트전단 링크 URL
			document.getElementById("div_smart_box"+id).style.display = "inline-block"; //스마트전단 영역 열기
			document.getElementById("div_coupon_box"+id).style.display = "none"; //스마트쿠폰 영역 닫기
            if(!edi_flag){
                document.getElementById("div_editor_box").style.display = "none"; //에디터 영역 닫기
            }
			document.getElementById("div_blank_box"+id).style.display = "none"; //사용안함 영역 닫기
		}

        chg_al_btn();

	}
</script>
