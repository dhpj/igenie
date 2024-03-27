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
			if($arr->action->displayText != ""){
				$tpl_button_cnt++;
				$tpl_button .= "<p>". $arr->action->displayText ."</p>";
				$str_button .= "<p class=\"tem_btn\">". $arr->action->displayText ."</p>";
				// if($tpl_button_cnt == 2){
				// 	$rep_button_nm = str_replace(" ", "", $arr->action->displayText); //버튼명 공백제거
				// 	$pos = strpos($rep_button_nm, "전단보기"); //문자열 포함 체크
				// 	//echo "알림톡 버튼[". $tpl_button_cnt ."] : ". $rep_button_nm ."<br>";
				// 	//echo "전단보기 포함여부[". $tpl_button_cnt ."] : ". $pos ."<br>";
				// 	if($pos){
				// 		$tpl_button2_leaflet_yn = "Y"; //알림톡 버튼2 전단보기 여부
				// 		//echo "알림톡 버튼2 전단보기 여부[". $tpl_button_cnt ."] : ". $tpl_button2_leaflet_yn ."<br>";
				// 	}
				// }
			}
		}
		$str_button .= "</div>";
	}
	//echo "알림톡 버튼 : ". $str_button ."<br>";
	//echo "알림톡 버튼수 : ". $tpl_button_cnt ."<br>";
	//echo "알림톡 버튼2 전단보기 여부 : ". $tpl_button2_leaflet_yn ."<br>";
?>
<?if($rs->tpl_brand) {?>
	<div class="input_content_wrap" style="display:none;">
		<label class="input_tit">템플릿 코드</label>
		<div class="input_content">
			<?=$rs->tpl_messagebaseId?>
		</div>
		<input type="hidden" id="pf_ynm" value="<?=$mem1->mem_username?>"/>
		<input type="hidden" id="sms_sender" value="<?=($sendtelauth>0)?$this->member->item("mem_phone"):''?>"/>
		<!-- 20190107 이수환 추가 작업중인 아디의 링크버튼 명을 입력-->
		<input type="hidden" name="find_linkbtn_name" id="find_linkbtn_name" value="<?=$rs->mem_linkbtn_name?>">
		<input type="hidden" name="pf_key" id="pf_key" value="<?=$rs->tpl_brand?>">
		 <!-- <input type="hidden" name="pf_yid" id="pf_yid" value="<?=$rs->spf_friend?>"> -->
		 <input type="hidden" name="templi_code" id="templi_code" value="<?=$rs->tpl_messagebaseId?>">
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
         <input type="hidden" name="hdn_rcs_type" id="hdn_rcs_type"  value="<?=$rcs_type?>" style="width:160px;">
         <input type="hidden" name="hdn_rcs_tmpl" id="hdn_rcs_tmpl"  value="<?=$rcs_tmpl?>" style="width:160px;">
          <input type="hidden" name="hdn_rcs_mms_url" id="hdn_rcs_mms_url"  style="width:160px;">
         <input type="file" name="image_file" id="image_file" accept="image/*" value="upload" style="display: none;" class="upload-hidden" accept="image/jpeg, image/png" onchange="img_size();">
	</div>
	<div class="input_content_wrap" style="display:none;">
		<label class="input_tit">템플릿명</label>
		<div class="input_content">
			<?=$rs->tpl_name?>
		</div>
	</div>
    <script type="text/javascript">
    function add_info(x) {
            // 원본 찾아서 pre_set으로 저장.
            var pre_set = document.getElementById('pre_set');
            // last-id 속성에서 필드ID르 쓸값 찾고
            var fieldid = parseInt(pre_set.getAttribute('last-id'));
            var rcs_select = $('input[name="rcs_select"]:checked').val();
            var maxno = 0;
            if(rcs_select=="S"){
                maxno = 1;
            }else if(rcs_select=="L"){
                maxno = 3;
            }else if(rcs_select=="M"){
                maxno = 2;
            }


            if ( fieldid < maxno) {	// 1개, 3개만 생성
                // 다음에 필드ID가 중복되지 않도록 1 증가.
                pre_set.setAttribute('last-id', fieldid + 1 );

                // 복사할 div 엘리먼트 생성
                var div = document.createElement('div');

                var pre_set_innerHTML = pre_set.innerHTML;
                pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_type0\"", ("\"btn_type" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_url_\"", ("\"btn_url" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"link_type00\"", ("\"link_type" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"link_type_option0\"", ("\"link_type_option" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("onchange=\"modify_btn_type(0);link_name(this,0);\"", "onchange=\"modify_btn_type(" + (fieldid + 1) + ");link_name(this," + (fieldid + 1) + ");\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("onchange=\"chg_link_type(0);\"", "onchange=\"chg_link_type('" + (fieldid + 1) + "');\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("onclick=\"linkview(0)\"", "onclick=\"linkview('" + (fieldid + 1) + "')\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_web_like_\"", ("\"btn_web_like_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_name2_\"", ("\"btn_name2_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_app_like_\"", ("\"btn_app_like_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"psd_like_\"", ("\"psd_like_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"div_smart_box\"", ("\"div_smart_box" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"div_coupon_box\"", ("\"div_coupon_box" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"div_blank_box\"", ("\"div_blank_box" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"div_smart_goods_call\"", ("\"div_smart_goods_call" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_name3_\"", ("\"btn_name3_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_bots_like_\"", ("\"btn_bots_like_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_name4_\"", ("\"btn_name4_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_msg_like_\"", ("\"btn_msg_like_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"btn_name5_\"", ("\"btn_name5_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"pc_web_like_\"", ("\"pc_web_like_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"web_like_\"", ("\"web_like_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"app_like_android_\"", ("\"app_like_android_" + (fieldid + 1)) + "\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("\"app_like_ios_\"", ("\"app_like_ios_" + (fieldid + 1)) + "\"");
                // pre_set_innerHTML = pre_set_innerHTML.replace(/onkeyup=\"link_name\(this,0\);scroll_prevent\(\);\"/g, "onkeyup=\"link_name(this," + (fieldid + 1) + ");scroll_prevent();\"");
                pre_set_innerHTML = pre_set_innerHTML.replace("onkeyup=\"link_name(this,0);\"", "onkeyup=\"link_name(this," + (fieldid + 1) + ");\"");

                // 내용 복사
                //div.innerHTML = pre_set.innerHTML;
                div.innerHTML = pre_set_innerHTML;
                // 복사된 엘리먼트의 id를 'field-data-XX'가 되도록 지정.
                div.id = 'field-data-' + fieldid;
                div.setAttribute('name', "field-data");
                // selection_content 영역에 내용 변경.
                var temp = div.getElementsByClassName('selection_content')[0];
                temp.innerText = x;

                // delete_box에 삭제할 fieldid 정보 건네기
                var deleteBox = div.getElementsByClassName('delete_box')[0];
                // target이라는 속성에 삭제할 div id 저장
                deleteBox.setAttribute('target',div.id);
                // #field에 복사한 div 추가.
                document.getElementById('field').appendChild(div);

                // var pf_yid = $("#pf_yid").val().replace(/[ ]*$/g, '');

                // $("#btn_url"+(fieldid + 1)).val("http://plus-talk.kakao.com/plus/home/" + pf_yid);
                var link_type_cnt = $("[name='link_type0']").length - 1;

                if(fieldid == 0){
                    $("#link_type1").val("self").prop("selected", false);
                    $("#link_type1").val("smart").prop("selected", true);
                    chg_link_type('1');
                    // $("#btn_url"+(fieldid + 1)).val("");
                    // // buttonArr.push("smart");
                    // $("#link_type_option"+(fieldid + 1)).val("smart");
                    console.log(buttonArr);
                }else{
                    buttonArr.push($("#link_type"+(fieldid + 1)).val());
                    console.log(buttonArr);
                }
                link_name(document.getElementById("btn_type" + (fieldid + 1)),(fieldid + 1));
                buttonArr = [];
                for(var i=0;i<link_type_cnt;i++){
                    buttonArr.push($("#link_type"+(i+1)).val());
                    console.log("#link_type"+(i+1)+" : "+$("#link_type"+(i+1)).val());
                }
            }else{
                if(rcs_select!="T"){
                    var rcs_type_sl = "SMS";
                    if(rcs_select =="L"){
                        rcs_type_sl = "LMS";
                    }else if(rcs_select =="M"){
                        rcs_type_sl = "MMS";
                    }
                    showSnackbar("선택하신 RCS "+rcs_type_sl+"는 총 "+maxno+"개의 버튼을 사용하실 수 있습니다.", 1500);
                }
            }
        }

        function delete_info(obj) {
        var pre_set = document.getElementById('pre_set');
        var fieldid = parseInt(pre_set.getAttribute('last-id'));

        // 삭제할 ID 정보 찾기
        var target = obj.parentNode.getAttribute('target');
        //alert(target);
        // 삭제할 element 찾기
        var field = document.getElementById(target);
        // filedId 검색 및 마지막 숫자만 분리
        var filedId = field.id;
        var fieldIdNo = parseInt(filedId.substring(filedId.lastIndexOf("-") + 1));


        // #field 에서 삭제할 element 제거하기
        document.getElementById('field').removeChild(field);
        $("#btn_preview_div" + (fieldIdNo + 1)).remove();
        buttonArr.splice(fieldIdNo);

        for (var i = (fieldIdNo + 1); i < fieldid; i++) {
            var rowTargetId = "field-data-" + i;
            var rowTarget = document.getElementById(rowTargetId);

            modFieldIdNo = i - 1;
            rowTarget.id = "field-data-" + modFieldIdNo;

            //var preViewTargetId = "btn_preview_div" + (i + 1);
            var preViewTarget = document.getElementById("btn_preview_div" + (i + 1));
            preViewTarget.id = "btn_preview_div" + i;

            var btnRreViewTarget = document.getElementById("btn_preview" + (i + 1));
            btnRreViewTarget.id = "btn_preview" + i;


            // delete_box에 삭제할 fieldid 정보 건네기
            var deleteBox = rowTarget.getElementsByClassName('delete_box')[0];
            // target이라는 속성에 삭제할 div id 저장
            deleteBox.setAttribute('target',rowTarget.id);

            var rowTargetHtml = rowTarget.innerHTML;
            //alert(rowTargetHtml);
            rowTargetHtml = rowTargetHtml.replace("\"btn_type" + (i + 1) + "\"", ("\"btn_type" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"btn_url" + (i + 1) + "\"", ("\"btn_url" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"link_type" + (i + 1) + "\"", ("\"link_type" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"link_type_option" + (i + 1) + "\"", ("\"link_type_option" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"btn_web_like_" + (i + 1) + "\"", ("\"btn_web_like_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"btn_name2_" + (i + 1) + "\"", ("\"btn_name2_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"btn_app_like_" + (i + 1) + "\"", ("\"btn_app_like_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"psd_like_" + (i + 1) + "\"", ("\"psd_like_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"div_smart_box" + (i + 1) + "\"", ("\"div_smart_box" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"div_coupon_box" + (i + 1) + "\"", ("\"div_coupon_box" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"div_blank_box" + (i + 1) + "\"", ("\"div_blank_box" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"div_smart_goods_call" + (i + 1) + "\"", ("\"div_smart_goods_call" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"btn_name3_" + (i + 1) + "\"", ("\"btn_name3_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"btn_bots_like_" + (i + 1) + "\"", ("\"btn_bots_like_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"btn_name4_" + (i + 1) + "\"", ("\"btn_name4_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"btn_msg_like_" + (i + 1) + "\"", ("\"btn_msg_like_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"btn_name5_" + (i + 1) + "\"", ("\"btn_name5_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"pc_web_like_" + (i + 1) + "\"", ("\"pc_web_like_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"web_like_" + (i + 1) + "\"", ("\"web_like_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"app_like_android_" + (i + 1) + "\"", ("\"app_like_android_" + i) + "\"");
            rowTargetHtml = rowTargetHtml.replace("\"app_like_ios_" + (i + 1) + "\"", ("\"app_like_ios_" + i) + "\"");

            //alert(rowTargetHtml);
            rowTarget.innerHTML = rowTargetHtml;
        }

        fieldid -= 1;
        pre_set.setAttribute('last-id', fieldid);
        }
    </script>
	<div class="input_content_wrap">
		<div class="mobile_preview">
            <div class="rcs_msg_type tem"></div>
			<div class="preview_circle"></div>
			<div class="preview_round"></div>
			<div class="preview_msg_wrap">
				<div class="preview_msg_window rcs">
                    <div class="rcs_top_com" style="margin-top:15px;padding-left:15px;">
                        <span class="material-icons" style="font-size:12px;">arrow_back_ios</span>
                        <span style="font-size:12px !important;margin-left:-3px;"><?=$this->member->item('mem_username')?></span>
                    </div>
					<div class="rcs_top_date">
                        <?
                        $idate = date("Y년 m월 d일");
                        $daily = array('일','월','화','수','목','금','토');
                        $weekday = $daily[date('w')];
                        $idate = $idate."(".$weekday.")";
                        echo $idate;
                        ?>
					</div>
                    <img id="rcs_mms_img" style="width:300px;" />
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
			<p>발송타입 선택</p>
		</label>
		<div class="input_content">
            <div class="checks">
                <input type="radio" name="rcs_select" value="T" id="rcs_T" <?=($rcs_type=="T")? " checked" : "" ?>><label for="rcs_T" <?=($rcs_type!="T"&&($intemplate->cnt==0||$intemplate->cnt==1))? "style='display:none;'" : "" ?>>RCS 템플릿</label>
                <input type="radio" name="rcs_select" value="S" id="rcs_S" <?=($rcs_type=="S")? " checked" : "" ?>><label for="rcs_S">RCS SMS</label>
                <input type="radio" name="rcs_select" value="L" id="rcs_L" <?=($rcs_type=="L")? " checked" : "" ?>><label for="rcs_L">RCS LMS</label>
                <input type="radio" name="rcs_select" value="M" id="rcs_M" <?=($rcs_type=="M")? " checked" : "" ?>><label for="rcs_M">RCS MMS</label>
            </div>
            <div id="mmsbtn"  <?=($rcs_type!="M")? "style='display:none;'" : "style='margin-top:10px;'" ?>>
                <button class="btn_add btn_myModal" onClick="image_list_open(1);">RCS MMS 이미지 관리</button>
                <p class="noted">* RCS MMS 이미지를 등록, 삭제하실 수 있습니다.</p>
            </div>
            <!-- The Modal -->
            <div id="image_list_modal" class="dh_modal">
              <!-- Modal content -->
              <div class="modal-content md_width970">
                  <span class="dh_close" onClick="image_list_close();">&times;</span>
                  <p class="modal-tit t_al_left">RCS MMS 이미지 관리</p>
                    <button class="btn_friimg" type="button" onclick="javascript:click_bntimage()"><i class="material-icons">insert_photo</i> 이미지추가</button>
                    <p class="modal_info">
                        * 이미지 권장 사이즈 : <span>720px X 720px</span> / 지원 파일형식 및 크기 : <span>jpg, png</span> / 최대용량 : <span>500KB</span>
                        <br  />* 이미지 제한 사이즈 : 가로 500px 미만, 가로:세로 비율이 2:1 미만, 3:4 초과시 업로드 불가
                        <br  />* 이미지 편집이 어려우실 경우 <span>[알씨]를 설치</span>(<a href="https://www.altools.co.kr" target="_blank">https://www.altools.co.kr</a>)하거나 <span>이미지 무료편집사이트</span>(<a href="https://www.iloveimg.com/ko" target="_blank">https://www.iloveimg.com/ko</a>)를 이용해보세요~
                    </p>
                    <ul class="frided_imglist" id="image_list_content"><? //친구톡 이미지 리스트 ?>
                    </ul>
                    <div class="page_cen" id="image_list_pagination" style="margin-top:10px;"><? //친구톡 이미지 페이징 ?>
                    </div>
                  </div>
            </div>

            <div id="rcs_list_modal" class="dh_modal">
              <!-- Modal content -->
              <div class="modal-content md_width970">
                  <span class="dh_close" onClick="rcs_list_close();">&times;</span>
                  <p class="modal-tit t_al_left">이미지 선택</p>

                    <ul class="frided_imglist" id="rcs_list_content"><? //친구톡 이미지 리스트 ?>
                    </ul>
                    <div class="page_cen" id="rcs_list_pagination" style="margin-top:10px;"><? //친구톡 이미지 페이징 ?>
                    </div>
                  </div>
            </div>
            <script type="text/javascript">

                $("[name=rcs_select]").click(function(){
                    //alert("this.value");
                    if($(this).prop("checked")){ //포스고객
                        // alert("this.value : "+ this.value);
                        var chk_data = this.value;
                        // if(chk_data == "T"){
                        //     $("#rcs_btn").show();
                        //     $("#rcs_btn_note").show();
                        // }else{
                        //     $("#rcs_btn").hide();
                        //     $("#rcs_btn_note").hide();
                        // }

                        if($("#hdn_rcs_type").val()!=chk_data){
                            selected_rcs_type(chk_data);
                        }

                    }
                });

                $(document).ready(function() {
                    <? //if($rcs_type=="T"){//유효 템플릿이 없을때
                        //if($intemplate->cnt==0||($intemplate->cnt==1&&$intemplate->tpl_cardType=="free")){
                            ?>
                        //selected_rcs_type("S");
                        // console.log("여기여기");
                        <? //} }?>
                });
            </script>
            <br/>
			<button id="rcs_btn" class="btn_add_gr btn_myModal" onclick="open_templet()" <?=($rcs_type=="T")? "" : " style='display:none;'" ?>><span class="material-icons">link</span> RCS템플릿 변경하기</button>
			<p id="rcs_btn_note" class="noted" <?=($rcs_type=="T")? "" : " style='display:none;'" ?>>* RCS버튼을 변경하시려면 위 버튼을 클릭하세요.<?=($this->member->item("mem_level") >= 100)? " - [".$rs->tpl_messagebaseId."]" : '' ?></p>
		</div>
		</div>
		<div class="input_content_wrap">
		<label class="input_tit" id="id_STEP2">
			<p class="txt_st_eng">STEP 2.</p>
			<p>발송내용 입력</p>
		</label>
		<div class="input_content">
            <button class="btn_add btn_myModal" onClick="rcs_list_open(1);" <?=($rcs_type!="M")? "style='display:none;'" : "style='margin-bottom:10px;'" ?>>RCS MMS 이미지 선택</button>

			<div class="msg_box">

				<!-- div class="txt_ad" id="cont_header">(광고) 대형네트웍스</div -->
				<textarea style="display:none;" name="dumy_templi_cont" id="dumy_templi_cont" cols="30" rows="5" class="form-control autosize" placeholder="템플릿 내용을 입력해주세요." style="resize:none;cursor:default;"
						onkeyup="javascript: var file = document.getElementById('filecount').value;
								 if (file==''){templi_preview(this); scroll_prevent(); templi_chk();}"
						value="<?=(!empty($rs->tpl_description)&&$rs->tpl_cardType=="free")? $rs->tpl_description : "{{free}}" ?>"><?=(!empty($rs->tpl_description)&&$rs->tpl_cardType=="free")? $rs->tpl_description : "{{free}}"?></textarea>
                <? if($rcs_type=="T"){ ?>
				<div class="input_msg" style="height: auto;" id="templi_cont">
                <? }else{ ?>
                    <div class="txt_ad" id="rcs_header">
						<span id="span_adv2">(광고)</span>
						<input type="text" class="input_option" style="width: 320px;" id="companyName2" onkeyup="onPreviewText(); chkword_templi();">
					</div>
                    <textarea class="input_msg" id="templi_cont" placeholder="메세지를 입력해주세요." onkeyup="onPreviewText();chkword_templi();" maxlength="<?=($rcs_type=="T")? "90" : (($rcs_type=="S")? "100" : "1300" ) ?>"></textarea>
                    <div class="txt_ad" id="rcs_footer">
						<span id="span_unsubscribe2">무료수신거부 : </span><input type="text" class="input_option" id="unsubscribe_num2" onkeyup="onPreviewText(); chkword_templi();">
					</div>
			        <!-- <span class="txt_byte" id="templi_length"><span id="type_num">0</span>/1,000 자</span> -->
                <? } ?>

				<?
				//$tmp_str = nl2br($rs->tpl_description);
				$tmp_str = $rs->tpl_description;
                if(empty($tmp_str)&&$rs->tpl_cardType=="free"){
                    $tmp_str = "{{free}}";
                    //  $tmp_str = '<textarea name="var_name" class="var multi" onkeyup="return view_preview();" placeholder="내용을 작성하세요"></textarea>';
                    //  $tmp_str = str_replace("\n\n", "<p>&nbsp;</p>", $tmp_str);
     				// $tmp_str = nl2br($tmp_str);

                }
                //else{
                if($rcs_type=="T"){
                    $tmp_str = str_replace("\n\n", "<p>&nbsp;</p>", $tmp_str);
    				$tmp_str = nl2br($tmp_str);
    				$pattern = '/\{+{[^}]*}\}/'; // $pattern = '/\#{[^}]*}/';
    				$m_cnt = preg_match_all($pattern, $tmp_str, $match );
    				$idx = 0;
    				$m_idx = 0;
    				while($m_cnt >= 1) {
    				    $var_value = "";
    				    if(!empty($varvalue)) {
    				        $var_value = $varvalue[$idx]->var_text;
    						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > var_value[". $idx ."] : ". $var_value);
    				    }
    				    log_message("ERROR", "match : ".$match[0][$idx]);
    				    $pla =  $match[0][$idx];
                        log_message("ERROR", "pla : ".$pla);
    				    $variable = '/\\'.$match[0][$idx].'/';
    				    if($pla == '{{변수내용}}' || $pla == '{{회원정보 등록일}}') {
    				        $var_str = '<textarea name="var_name" class="var multi" onkeyup="return view_preview();" placeholder="행사 정보">'.$var_value.'</textarea>';
                            // alert($pla);
    				    }else if($pla == "{{free}}") {
                            // log_message("ERROR", "pla 같음? ");
    				        $var_str = '<textarea name="var_name" class="var multi" style="height:300px" onkeyup="return view_preview();" placeholder="내용을 작성하세요"></textarea>';
    				    }else if($pla == '{{업체명}}') {
    				        if(empty($var_value)) {
    				            $var_value = $this->member->item('mem_username');
    				        }
    				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="업체명">'.$var_value.'</textarea>';
    				    } else if($pla == '{{업체전화번호}}' || $pla == '{{전화번호}}') {
    						if(empty($var_value)) {
    				            //$var_value = $this->Biz_dhn_model->add_hyphen($this->member->item('mem_phone'));
    				            $var_value = $this->funn->format_phone($this->member->item('mem_phone'), "-");
    				        }
    				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="전화번호">'.$var_value.'</textarea>';
                        } else if($pla == '{{날짜}}'|| $pla == '{{변수}}'){
    				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="행사 날짜">'.$var_value.'</textarea>';
                        } else if($pla == '{{등급}}'){
    				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="등급">'.$var_value.'</textarea>';
                        } else if($pla == '{{00}}'){
                            $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="00" style="width:35px;">'.$var_value.'</textarea>';
                        } else {
    				        $var_str = '<textarea name="var_name" class="var single" onkeyup="return view_preview();" onkeypress="if(event.keyCode==13) { event.preventDefault(); }" placeholder="'.str_replace("#", "", $pla).'">'.$var_value.'</textarea>';
    				    }
    				    //log_message("ERROR", $pla."/".$var_str);
    			        $tmp_str= preg_replace($variable,$var_str,$tmp_str, 1);
    				    $m_cnt = $m_cnt - 1;
    				    $idx = $idx + 1;

    				}
                    log_message("ERROR", $tmp_str);
                //}
                echo $tmp_str;

            }
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." tmp_str  > ". $tmp_str);


				?>
				</div>
				<span class="txt_byte"><span id="type_num">0</span>/<?=($rcs_type=="T")? "90" : (($rcs_type=="S")? "100" : "1,300" ) ?> 자</span>
				<!-- div class="txt_ad">수신거부 : 홈>친구차단</div-->
			</div>
			<p class="noted">*노란 박스 영역(변수값)의 내용만 입력/수정이 가능합니다.</p>
			<!--p class="info_text">"#{변수}" 부분의 내용만 수정이 가능합니다.</p-->
    		<? // 2021-05-04 템플릿 추가정보형 추가(카카오톡 API버전 변경)?>

		</div>
	</div>
	<div class="input_content_wrap" style="display:<?=($tpl_button_cnt == 0&&$rcs_type=="T") ? "none" : ""?>;">
		<label class="input_tit" id="id_STEP3">
			<p class="txt_st_eng">STEP 3.</p>
			<p>버튼내용 선택</p>
		</label>
		<div class="input_content">
			<!-- 스마트전단, 에디터사용 공통 -->
			<? $leaflet_link_type = $rcs_type; //전단지 타입(at.알림톡, lms.문자)
            log_message("ERROR", $_SERVER['REQUEST_URI'] ." leaflet_link_type > ". $leaflet_link_type);
            ?>
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/dhnsender/send/bootstrap/_inc_leaflet_rcs_v2.php'); ?>

		</div>
		<script type="text/javascript">
			var buttons = '<?=$rs->tpl_button?>';
			var btn = buttons.replace(/&quot;/gi, "\"");

            // console.log(btn_content);
			var url_name1 = '';
			var url_name2 = '';
			var url_link1 = '';
			var url_link2 = '';
			// var link_type = "<?=$link_type_option?>";
			// var btn1_type = "<?=$rs->tpl_btn1_type?>"; //버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
			// var premium_yn = "<?=$rs->tpl_premium_yn?>"; //광고성 알림톡 프리미엄 여부
            var mem_contract_type = "<?=$this->member->item("mem_contract_type")?>"; //계약종류(P:프리미엄, S:스텐다드)
			var alim_btn_url1 = "<?=$this->member->item("mem_alim_btn_url1")?>"; //알림톡 '행사보기' 버튼 URL 있는 경우
			var alim_btn_url2 = "<?=$this->member->item("mem_alim_btn_url2")?>"; //알림톡 '전단보기' 버튼 URL 있는 경우
			var alim_btn_url3 = "<?=$this->member->item("mem_alim_btn_url3")?>"; //알림톡 '주문하기' 버튼 URL 있는 경우
            var selecttype = "";
            var wholename = "";
            <? if($rcs_type=="T"){ ?>
            if(btn&&buttons!="null"){
                var btn_content = JSON.parse(btn);
                for(var i=0;i<btn_content.length;i++){
                    wholename += btn_content[i]["action"]["displayText"].replace(/ /g,"");
                    // buttonNameArr.push(btn_content[i]["name"].replace(/ /g,""));
                }

    			//alert("link_type : "+ link_type);

    			for(var i=0;i<btn_content.length;i++) {
    				var html = '';
                    var html2 = '';
                    var html3 = '';
    				var btn_count = i + 1;
    				var ordering= (typeof(btn_content[i]["ordering"])=='undefined') ? no : btn_content[i]["ordering"];
    				var btn_type = '';
                    if(btn_content[i]["action"]["urlAction"]!=undefined){
                        btn_type = "urlAction";
                    }else if(btn_content[i]["action"]["dialerAction"]!=undefined){
                        btn_type = "dialerAction";
                    }else if(btn_content[i]["action"]["clipboardAction"]!=undefined){
                        btn_type = "clipboardAction";
                    }

    				var btn_name = btn_content[i]["action"]["displayText"];
    				//if(btn_type=="DS") { type="<?=$this->Biz_dhn_model->buttonName['DS']?>"; }
    				//else if(btn_type=="BK") { type="<?=$this->Biz_dhn_model->buttonName['BK']?>"; }
    				//else if(btn_type=="MD") { type="<?=$this->Biz_dhn_model->buttonName['MD']?>"; }
    				//else if(btn_type=="AL") { type="<?=$this->Biz_dhn_model->buttonName['AL']?>"; }
    				//else if(btn_type=="WL") { type="<?=$this->Biz_dhn_model->buttonName['WL']?>"; }

    				console.log(btn_type);
    				if (btn_type == "dialerAction" || btn_type == "clipboardAction") {
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
    				} else if (btn_type == "urlAction") {
    					if (btn_name != "") {
    						html += "<div id=\"field-data-" + i + "\" name=\"field-data\">";
    						html += "<div class=\"input_link_wrap\">";
    						html += "<input id=\"btn_type\" name=\"btn_type\" type=\"hidden\" value=\"" + btn_type + "\"></td>";
    						html += "<span style=\"display:none;\">";
    						html += "<select style=\"width: 120px;\" class=\"fl\"  name=\"btn_type1\" id=\"btn_type\">";
    						html += "<option disabled=\"disabled\" value=\"urlAction\"";
    						if(btn_type == "urlAction") { html += " selected" }
    						html += ">웹링크</option>";
    						html += "<option disabled=\"disabled\" value=\"dialerAction\">전화걸기</option>";
    						html += "<option disabled=\"disabled\" value=\"clipboardAction\">복사하기</option>";
    						html += "</select>";
    						html += "<div style=\"overflow: hidden;\"><input type=\"text\" name=\"btn_name\" id=\"btn_name\" class=\"btn_name_loop_"+i+"\"  style=\"width:100%; margin-left: -1px;\" placeholder=\"버튼명을 입력해주세요\" readonly value=\"" + btn_name +"\">";
    						html += "</div>";
    						html += "</span>";
    						if(btn_type == "urlAction") { //웹링크
                                url_link1 = btn_content[i]["url_mobile"];
    							url_link2 = btn_content[i]["url_pc"];
                                // url_link1 = btn_content[i]["url_mobile"];
    							// url_link2 = btn_content[i]["url_pc"];
    							//alert('btn_name.replace(/ /g,"").indexOf("주문하기") : '+ btn_name.replace(/ /g,"").indexOf("주문하기"));
    							//alert('btn_name.replace(/ /g,"") : '+ btn_name.replace(/ /g,""));
    							//alert('alim_btn_url1 : '+ alim_btn_url1);

    							html += "<div class=\"input_link_wrap\">";

                                html += "<div id=\"selectIn_"+i+"\"></div>";

    							if(alim_btn_url1 != "" && btn_name.replace(/ /g,"").indexOf("행사보기") > -1){ //알림톡 '행사보기' 버튼 URL 고정 사용의 경우
                                    selecttype = "self";//self
    								// $("#link_type").val("self"); //링크타입 직접입력으로 변경
    								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>";
    								html += "  <div style=\"overflow: hidden;\">";
    								html += "    <input type=\"url\" style=\"\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url21\" placeholder=\"링크주소를 입력해주세요.\" value=\""+ alim_btn_url1 +"\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
    								html += "  </div>";
    							}else if(alim_btn_url2 != "" && btn_name.replace(/ /g,"").indexOf("전단보기") > -1){ //알림톡 '전단보기' 버튼 URL 고정 사용의 경우
                                    selecttype = "editor";//editor
    								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>";
    								html += "  <div style=\"overflow: hidden;\">";
    								html += "    <input type=\"url\" style=\"\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url21\" placeholder=\"링크주소를 입력해주세요.\" value=\""+ alim_btn_url2 +"\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
    								html += "  </div>";
    							}else if(alim_btn_url3 != "" && btn_name.replace(/ /g,"").indexOf("주문하기") > -1){ //알림톡 '주문하기' 버튼 URL 고정 사용의 경우
                                    selecttype = "self";//self
    								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>";
    								html += "  <div style=\"overflow: hidden;\">";
    								html += "    <input type=\"url\" style=\"\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url21\" placeholder=\"링크주소를 입력해주세요.\" value=\""+ alim_btn_url3 +"\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
    								html += "  </div>";
    							}else if(btn_count != 1 && btn1_type != "C" && btn_name.replace(/ /g,"").indexOf("쿠폰받기") > -1){ //첫번째 버튼 & 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
                                    selecttype = "coupon";//coupon
    								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //첫번째 버튼
    								html += "  <div style=\"overflow: hidden;\">";
    								html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url21\" placeholder=\"쿠폰불러오기 버튼 클릭\" value=\"<?=$pcd_url?>\" readonly>";
    								html += "  </div>";
    							}else if(btn_count == 1 && btn1_type == "E"){ //첫번째 버튼 & 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
                                    selecttype = "editor";//editor
    								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //첫번째 버튼
    								html += "  <div style=\"overflow: hidden;\">";
    								html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url21\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?=$dhnl_url?>\" readonly>";
    								html += "  </div>";
    							}else if(btn_count == 1 && btn1_type == "L"){ //첫번째 버튼 & 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
                                    selecttype = "smart";//smart
    								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //첫번째 버튼
    								html += "  <div style=\"overflow: hidden;\">";
    								html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url21\" placeholder=\"전단불러오기 버튼 클릭\" value=\"<?=$psd_url?>\" readonly>";
    								html += "  </div>";
    							}else if(btn_count == 1 && btn1_type == "C"){ //첫번째 버튼 & 버튼1타입(S.직접입력, E.에디터사용, L.스마트전단, C.스마트쿠폰)
                                    selecttype = "coupon";//coupon
    								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //첫번째 버튼
    								html += "  <div style=\"overflow: hidden;\">";
    								html += "    <input type=\"url\" style=\"background:#f2f2f2;\" id=\"btn_url\" class=\"btn_loop_"+i+"\" name=\"btn_url21\" placeholder=\"쿠폰불러오기 버튼 클릭\" value=\"<?=$pcd_url?>\" readonly>";
    								html += "  </div>";
    							}else if(premium_yn == "Y" && mem_contract_type == "P" && btn_name.replace(/ /g,"").indexOf("주문하기") > -1){ //광고성 알림톡 프리미엄 & 주문하기
                                    selecttype = "self";//self
    								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //두번째 버튼
    								html += "  <div style=\"overflow: hidden;\">";
    								html += "    <input type=\"url\" style=\"\" id=\"btn_url\" name=\"btn_url21\" class=\"btn_loop_"+i+"\" placeholder=\"링크주소를 입력해주세요.\" value=\"http://<?=$this->member->item('mem_mall_id')?>.pfmall.co.kr\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
    								html += "  </div>";
    							}else{
                                    selecttype = "self";//self
    								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">"+ btn_name +"</button>"; //두번째~ 버튼
    								html += "  <div style=\"overflow: hidden;\">";
    								html += "    <input type=\"url\" style=\"\" id=\"btn_url\" name=\"btn_url21\" class=\"btn_loop_"+i+"\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?//=$psd_url?>\" onkeyup=\"btnUrlCopy(this, '"+ btn_count +"');\">";
    								html += "  </div>";
    							}
    							html += "</div>";

    							if(url_link2 != null && url_link2 != "") { //PC 버전
    								html += "<span style=\"display:none;\">";
    								html += "<div><label>PC</label></div>";
    								html += "<div class=\"input_link_wrap\">";
    								html += "  <button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"linkview("+i+");\">링크확인</button>";
    								html += "  <div style=\"overflow: hidden;\">";
    								html += "    <input type=\"url\" style=\"\" id=\"btn_url\" name=\"btn_url22\" class=\"btn_loop_"+i+"\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?//=$psd_url?>\">";
    								html += "  </div>";
    								html += "</div>";
    								html += "</span>";
    							}
                                buttonArr.push(selecttype);

                                html3 += "<div style='display:"+"<?=($lms_purigo_yn == 'Y') ? 'none' : ''?>"+";'>";


                                html3 += "<input type='hidden' name='link_type_option"+ i +"' id='link_type_option"+ i +"' value='"+selecttype+"'>";
                            html3 += "<select style='' class='send_select' name='link_type' id='link_type"+ i +"' onChange='chg_link_type(\""+i+"\");'>";

                            // html3 +=  "<option value='smart'"+"<?=($link_type_option == 'smart') ? ' selected' : ''?>"+">스마트전단</option>";
                            html3 +=  "<option value='smart'";
                            if(selecttype=="smart"){
                                html3 += " selected";
                            }
                            html3 += ">스마트전단</option>";
                            if(wholename.indexOf("쿠폰")>-1){
                                html3 +=  "<option value='coupon'";
                                if(selecttype=="coupon"){
                                    html3 += " selected";
                                }
                                html3 += ">스마트쿠폰</option>";
                            }

                            // html3 +=  "<option value='coupon'"+"<?=($link_type_option == 'coupon') ? ' selected' : ''?>"+">스마트쿠폰</option>";

                            html3 +=  "<option value='editor'";
                            if(selecttype=="editor"){
                                html3 += " selected";
                            }
                            html3 += ">에디터사용</option>";

                            html3 +=  "<option value='self'";
                            if(selecttype=="self"){
                                html3 += " selected";
                            }
                            html3 += ">직접입력</option>";


                            html3 +=  "</select>";

                            html3 +=  "</div>";


                            html2 +=  "<div id='div_blank_box"+ i +"' style='display:";
                            if(selecttype=="self"){
                                html2 += "none";
                            }
                            html2 += ";margin: 0px 0px 10px 0px;'>";
                            html2 +=  "</div>";
                            html2 +=  "<div id='div_smart_box"+ i +"' style='display:";
                            if(selecttype!="smart"){
                                html2 += "none";
                            }
                            html2 += ";'>";

                            html2 +=  "<button class='btn_add btn_myModal' onClick='smart_page(\"1\");'>전단불러오기</button>";

                            html2 +=  "<div id='div_smart_goods_call"+ i +"' style='display:none;'>";


                            html2 +=  "		<button class='btn_con_copy btn_myModal' onClick='smart_goods_call(\"at\");'>행사정보 가져오기</button>";
                            html2 +=  "</div>";
                            html2 +=  "<div class='editor_box' style='margin:15px 0px; display:none;'>";
                            html2 +=  "<div id='div_smart_url"+ i +"' class='url_info' style='margin: 10px 0px 0px 0px;'><?=$psd_url?></div>";
                            html2 +=  "<button class='btn_link2' onClick='linkview("+i+");'>링크확인</button><?//스마트전단 링크확인?>";
                            html2 +=  "</div>";
                            html2 +=  "</div>";

                            html2 +=  "<div id='div_coupon_box"+ i +"' style='display:";
                            if(selecttype!="coupon"){
                                html2 += "none";
                            }
                            html2 += ";'>";
                            html2 +=  "<button class='btn_add btn_myModal' onClick='coupon_page(1);'>쿠폰불러오기</button>";
                            html2 +=  "</div>";

                            html2 +=  "<div class='editor_box' style='margin:15px 0px; display:none;";
                            if(selecttype!="editor"){
                                html2 += "none";
                            }
                            html2 += ";'>";
                            html2 +=  "<div id='div_coupon_url"+ i +"' class='url_info' style='margin: 10px 0px 0px 0px;'>"+"<?=$pcd_url?>"+"</div>";
                            html2 +=  "<button class='btn_link2' onClick='linkview("+i+");'>링크확인</button><?//스마트쿠폰 링크확인?>";
                            html2 +=  "</div>";
                            html2 +=  "</div>";
                            // console.log(html2);
                            // alert("확인");



    						} else if(btn_type == "AL") {
                                url_link1 = btn_content[i]["scheme_android"];
    							url_link2 = btn_content[i]["scheme_ios"];
    							// url_link1 = btn_content[i]["scheme_android"];
    							// url_link2 = btn_content[i]["scheme_ios"];
    							html += "<div><label>Android</label></div>";
    							html += "<div class=\"input_link_wrap\">";
    							html += "<button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
    							html += "<div style=\"overflow: hidden;\">";
    							html += "<input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url21\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?//=$psd_url?>\">";
    							html += "</div>";
    							html += "</div>";
    							html += '<div <?if($rs->cc_idx) { echo "style=\"display:none\"";} ?>><label>iOS</label></div>';
    							html += '<div class=\"input_link_wrap\" <?if($rs->cc_idx) { echo "style=\"display:none\"";} ?>>';
    							html += "<button class=\"btn md fr\" style=\"margin-left: -1px;\" id=\"find_url\" onclick=\"urlConfirm(this);\">링크확인</button>";
    							html += "<div style=\"overflow: hidden;\">";
    							html += "<input type=\"url\" style=\"width:100%;\" id=\"btn_url\" name=\"btn_url22\" placeholder=\"링크주소를 입력해주세요.\" value=\"<?//=$psd_url?>\">";
    							html += "</div>";
    							html += "</div>";
    						}
    						html += "</div>";
    						html += "</div>";

    					}
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
            }

			if ($("#field").html().trim() == "") {
				$("#field").html("버튼 링크가 없습니다.");
			}
            <? } ?>
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
            <button class="fr mg_l5 btn_yellow" onclick="getRcsCont();"><span class="material-icons">notifications_active</span> RCS내용 가져오기</button>
			<button class="btn md fr" onclick="getLinkCont();">링크 가져오기</button>
			<span class="noted">* RCS 실패건을 문자(SMS/LMS)로 재발송합니다.</span>
			<?
			} else {
				if ($mem1->mem_2nd_send=='NONE' || $mem1->mem_2nd_send=='') {
			?>
			<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>
            <button class="fr mg_l5 btn_yellow" onclick="getRcsCont();" style="display:none;"><span class="material-icons">notifications_active</span> RCS내용 가져오기</button>
			<button class="btn md fr" onclick="getLinkCont();" style="display:none;">링크 가져오기</button>
			<span class="info_text2">*2차 발신이 설정되지 않았습니다. 관리자에게 문의 하세요.</span>
			<?
				} else if ($sendtelauth == 0) { ?>
			<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>
            <button class="fr mg_l5 btn_yellow" onclick="getRcsCont();" style="display:none;"><span class="material-icons">notifications_active</span> RCS내용 가져오기</button>
			<button class="btn md fr" onclick="getLinkCont();" style="display:none;">링크 가져오기</button>
			<span class="info_text2">*발신번호가 등록되지 않아 2차 발신은 할 수 없습니다.</span>
			<?
				} else { ?>
			<label class="form-switch"><input type="checkbox" data-trigger="hidden_fields_sms" class="trigger" id="lms_select" disabled><i></i></label>
            <button class="fr mg_l5 btn_yellow" onclick="getRcsCont();" style="display:none;"><span class="material-icons">notifications_active</span> RCS내용 가져오기</button>
			<button class="btn md fr" onclick="getLinkCont();" style="display:none;">링크 가져오기</button>
			<span class="info_text2">*발신번호가 등록 및 2차 발신이 설정되지 않았습니다.</span>
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
					<textarea class="input_msg" id="lms" name="lms" placeholder="'입력' 또는 'RCS 내용 가져오기' 버튼을 클릭하세요." onkeyup="onPreviewLms();chkword_lms();"><?=$lms_base_msg?></textarea>
					<span class="txt_byte"><span class="fl" style="color: #ff9a00;">발신번호 : <?=$this->funn->format_phone($rs1->spf_sms_callback,"-")?></span><span class="msg_type_txt sms" id="lms_limit_str"></span><span id="lms_num">0</span>/<span id="lms_character_limit">2000</span> bytes</span>
					<!--div class="txt_ad" id="lms_footer"></div-->
					<div class="txt_ad" id="lms_footer">
						<p id="kakotalk_link_text"></p>
						<span id="span_unsubscribe">무료수신거부 : </span><input type="text" class="input_option" id="unsubscribe_num" onkeyup="onPreviewLms(); chkword_lms();">
					</div>
				</div>
				<!--<input type="checkbox" id="lms_kakaolink_select"  class="input_cb" onclick="insert_kakao_link(this);" style="cursor: default;"><label for="lms_kakaolink_select">카카오 친구추가</label>-->

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
		  RCS 템플릿 목록
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
					$tmp_str = $rs->tpl_description;
                    if($tmp_str!=""){
                        log_message("ERROR", "tpl_description : ".$rs->tpl_description);
    					$tmp_str = str_replace("\n\n", "<p>&nbsp;</p>", $tmp_str);
                        log_message("ERROR", "tmp_str1 : ".$tmp_str);
    					$tmp_str = nl2br($tmp_str);
                        log_message("ERROR", "tmp_str2 : ".$tmp_str);
                        $pattern = '/\{+{[^}]*}\}/';
    					// $pattern = '/\#{[^}]*}/';
    					$m_cnt = preg_match_all($pattern, $tmp_str, $match );
    					$idx = 0;
    					$m_idx = 0;
    					while($m_cnt >= 1) {
    						$pla =  $match[0][$idx];
    						$variable = '/\\'.$match[0][$idx].'/';

                            if($pla == '{{업체명}}') {
    							$var_str = $this->member->item('mem_username');
    						} else if($pla == '{{업체전화번호}}' || $pla == '{{전화번호}}'){
    							$var_str = $this->funn->format_phone($this->member->item('mem_phone'), "-");
    						} else if($pla == '{{변수내용}}' || $pla == '{{회원정보 등록일}}'){
    						    $var_str = "행사 기간";
    						} else if($pla == '{{날짜}}'||$pla == '{{변수}}'){
    						    $var_str = "행사 날짜";
                            } else if($pla == '{{등급}}'){
    						    $var_str = "등급";
                            } else if($pla == '{{00}}'){
    						    $var_str = "00";
    						}else{
    							$var_str = $pla;
    						}
    						//log_message("ERROR", $pla."/".$var_str);
    						$tmp_str= preg_replace($variable,$var_str,$tmp_str, 1);
                            log_message("ERROR", "tmp_str3 : ".$tmp_str);
                            if(strpos($tmp_str,'00')==0){
                                $tmp_str = substr_replace($tmp_str, "", 0,2);
                            }
                            log_message("ERROR", "tmp_str3.5 : ".$tmp_str);
    						$m_cnt = $m_cnt - 1;
    						$idx = $idx + 1;
    						//log_message("ERROR", $tmp_str);
    					}
    					// 부가정보 추가
    					// if ($rs->tpl_extra != "" && $rs->tpl_extra != "NONE") {
    					//     $tmp_str.= "<br><br>".$rs->tpl_extra;
    					// }
                        log_message("ERROR", "tmp_str4 : ".$tmp_str);

    					echo $tmp_str;
                    }else{
                        if($rs->tpl_cardType=="free"){
                            echo "내용을 작성하세요";
                        }
                    }
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
			<p><i class="xi-info-o"></i> RCS템플릿을 선택하세요.</p>
		</div>
		</div>
		<div class="al_talk_list">
			<!-- <p class="tit">
				버튼타입
			</p> -->
            <div class="al_talk_cate">
				<input type='hidden' name='category_cnt' id='category_cnt' value='<?=$category_cnt?>' />
				<input type='hidden' name='max_btn_cnt' id='max_btn_cnt' value='<?=$max_btn_cnt?>' />
				<input type='hidden' name='category_ids' id='category_ids' value='ALL' />
				<input type='hidden' name='btn_cnt_ids' id='btn_cnt_ids' value='ALL' />

			</div>
			<div class="list">
				<ul class="al_talk_tembox" id="modal_templet_list"><?//알림톡 샘플 리스트 영역?>
				</ul>
			</div>
		</div>
	  </div>
	</div>
	<script>
    function rcs_list_open(page){
    	//RCS 이미지 리스트 조회
    	$.ajax({
    		url: "/dhnbiz/sender/send/rcs_v2/ajax_image_list_rcs",
    		type: "POST",
    		data: {"per_page" : "8", "page" : page, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
    		success: function (json){
    			var html = "";
    			//alert("json.page_html : "+ json.page_html);
    			$.each(json, function(key, value){
    				var img_id = value.rmi_id; //이미지 일련번호
    				var img_url = "http://<?=$_SERVER['HTTP_HOST']?>/uploads/rcs_images/"+ value.rmi_filename; //이미지 파일명
    				// var img_url = value.img_url; //카카오 URL
    				var del_url = encodeURI(value.rmi_filename +"|"+ img_id);
    				if(img_id != undefined && img_url != undefined){
    					html += "<li onClick=\"image_rcs_choose('"+ img_url +"', '"+ value.rmi_url +"');\">";
    					html += "	<p class=\"tem_img\" style=\"background-image: url('"+ img_url +"');border: solid 1px #ddd;\" ></p>";
    					html += "	<p class=\"tem_text\">";
    					// html += "	 <button type=\"button\" onClick=\"image_rcs_choose('"+ img_url +"');\"><i class=\"material-icons\">add</i>이미지선택</button>";
    					// html += "	 <button type=\"button\" onClick=\"delete_image('"+ del_url +"');\"><i class=\"material-icons\">remove</i>이미지삭제</button>";
    					html += "	</p>";
    					html += "</li>";
    				}
    			});
    			$("#rcs_list_content").html(html);
    			$("#rcs_list_pagination").html(json.page_html);
    			//$("#image_list_pagination").html("<ul class=\"pagination\"><li class=\"active\"><a>1</a></li><li><a href=\"javascript:void(image_list_open('2'));\">2</a></li><li><a href=\"javascript:void(image_list_open('2'));\">&gt;</a></li></ul>");
    		}
    	});
    	document.getElementById("rcs_list_modal").style.display = "block";
    }

    //카카오톡 이미지 모달창 닫기
    function rcs_list_close(){
    	document.getElementById("rcs_list_modal").style.display = "none";
    }

    //이미지 선택
    function image_rcs_choose(url, inurl) {
    	$("#rcs_mms_img").prop("src", url);
        $("#hdn_rcs_mms_url").val(inurl);
        rcs_list_close();
    }

    function image_list_open(page){
    	//RCS 이미지 리스트 조회
    	$.ajax({
    		url: "/dhnbiz/sender/send/rcs_v2/ajax_image_list",
    		type: "POST",
    		data: {"per_page" : "8", "page" : page, "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"},
    		success: function (json){
    			var html = "";
    			//alert("json.page_html : "+ json.page_html);
    			$.each(json, function(key, value){
    				var img_id = value.rmi_id; //이미지 일련번호
    				var img_url = "http://<?=$_SERVER['HTTP_HOST']?>/uploads/rcs_images/"+ value.rmi_filename; //이미지 파일명
    				// var img_url = value.img_url; //카카오 URL
    				var del_url = encodeURI(value.rmi_filename +"|"+ img_id);
    				if(img_id != undefined && img_url != undefined){
    					html += "<li>";
    					html += "	<p class=\"tem_img\" style=\"background-image: url('"+ img_url +"');border: solid 1px #ddd;\" ></p>";
    					html += "	<p class=\"tem_text\">";
    					// html += "	 <button type=\"button\" onClick=\"image_choose('"+ img_url +"', '"+ img_link +"');\"><i class=\"material-icons\">add</i>이미지선택</button>";
    					// html += "	 <button type=\"button\" onClick=\"delete_image('"+ del_url +"');\"><i class=\"material-icons\">remove</i>이미지삭제</button>";
    					html += "	</p>";
    					html += "</li>";
    				}
    			});
    			$("#image_list_content").html(html);
    			$("#image_list_pagination").html(json.page_html);
    			//$("#image_list_pagination").html("<ul class=\"pagination\"><li class=\"active\"><a>1</a></li><li><a href=\"javascript:void(image_list_open('2'));\">2</a></li><li><a href=\"javascript:void(image_list_open('2'));\">&gt;</a></li></ul>");
    		}
    	});
    	document.getElementById("image_list_modal").style.display = "block";
    }

    //카카오톡 이미지 모달창 닫기
    function image_list_close(){
    	document.getElementById("image_list_modal").style.display = "none";
    }

    //이미지 업로드 버튼 클릭
    function click_bntimage() {
    	$("#image_file").trigger("click");
    }

    //카카오톡 이미지 추가시 체크
    function img_size(){
    	var img_file = $("#image_file")[0].files[0];
    	var _URL = window.URL || window.webkitURL;
    	var img = new Image();
    	img.src = _URL.createObjectURL(img_file);
    	img.onload = function() {
    		var img_width = img.width;
    		var img_height = img.height;
    		var img_check = true;
    		// if (img_width/img_height < 0.75 && img_width/img_height > 2) { //이미지 사이즈 체크
    		// 	img_check = false;
    		// }
    		upload(img_check);
    	};
    }

    //파일 용량 및 확장자 확인 ->500KB제한
    function upload(check) {
    	var thumbext = document.getElementById('image_file').value;
    	var file_data = new FormData();
    	file_data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
    	file_data.append("image_file", $("input[name=image_file]")[0].files[0]);
    	// file_data.append("img_wide", "N"); //이미지 구분(N.친구톡, W.와이드친구톡, C.쿠폰)
    	thumbext = thumbext.slice(thumbext.indexOf(".") + 1).toLowerCase();
    	if (thumbext) {
    		showSnackbar("처리중입니다. 잠시만 기다려 주세요.", 1500); //1.5초
    		$.ajax({
    			url: "/biz/sender/image_upload_rcs",
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
    				//$(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오..");
    				//$("#myModal").modal('show');
    				alert("처리중 오류가 발생했습니다.");return;
    			}
    		});
    		function showResult(json) {
    			//alert("code : "+ json['code']);
    			if (json.code == 'success') {
    				//window.location.href = '/biz/sender/image_w_list';
    				image_list_open(1);
    			} else {

    				var message = json.message;


                    messagekr = message;
    				var text = '이미지 업로드에 실패하였습니다' + '\n\n' + messagekr;

    				alert(text);return;
    			}
    		}
    	}
    }
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
			// var first_tpl_premium_yn = "";
			var first_btn_name = "";
			var contract_type = "<?=$this->member->item("mem_contract_type")?>";
			// var category_ids = $('#category_ids').val();
			// var btn_cnt_ids = $('#btn_cnt_ids').val();
			$("#modal_templet_list").html(""); //알림톡 샘플 리스트 영역 초기화
			//알림톡버튼 데이타 조회
			$.ajax({
				url: "/dhnbiz/sender/send/rcs_v2/ajax_adv_template_data",
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
						var tpl_description = value.tpl_description; //템플릿 내용
						tpl_description = tpl_description.replace(/\n/gi, "<br>"); //<br>처리
						tpl_description = tpl_description.replace(/{{업체명}}/gi, "<?=$this->member->item('mem_username')?>"); //업체명 적용
						tpl_description = tpl_description.replace(/{{전화번호}}/gi, mem_phone); //연락처 적용
						tpl_description = tpl_description.replace(/{{업체전화번호}}/gi, mem_phone); //연락처 적용
						var tpl_button = value.tpl_button; //템플릿 버튼
						// var tpl_premium_yn = value.tpl_premium_yn; //프리미엄 여부
						var badge = "<span class=\"badge_sta\">스탠<br>다드</span>";
						// if(tpl_premium_yn == "Y") badge = "<span class=\"badge_pre\">프리<br>미엄</span>";
						var btn = tpl_button.replace(/&quot;/gi, "\"");

						var btn_cnt = 0;
						var btn_name = "";
						var rtn_name = "";
                        if(tpl_button&&tpl_button!="null"){
                            var btn_content = JSON.parse(btn);
    						for(var i=0;i<btn_content.length;i++){
    							btn_name += "  <p>"+ btn_content[i]["action"]["displayText"] +"</p>";
    							btn_cnt++;
    						}
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
							html += "    <button onClick=\"view_templet('"+ tpl_id +"', '"+ btn_name +"');\"><i class=\"xi-plus-circle-o\"></i> 미리보기</button>";
							html += "    <button onClick=\""+ rtn_link +"\"><i class=\"xi-check-circle-o\"></i> 선택하기</button>";
							html += "    <textarea id='tpl_contents_"+ tpl_id +"' style='display:none;'>"+ tpl_description +"</textarea>";
							html += "  </div>";
							html += "</li>";
							if(modal_tpl_no == 1){
								first_tpl_id = tpl_id;
								// first_tpl_premium_yn = tpl_premium_yn;
								first_btn_name = btn_name;
							}
						}
					});
					$("#modal_templet_list").append(html);
				}
			});

		}
		//알림톡버튼 데이타 미리보기
		function view_templet(tpl_id, btn_name){
			//alert("tpl_id : "+ tpl_id +", tpl_premium_yn : "+ tpl_premium_yn +"\n"+"btn_name : "+ btn_name);
			var tpl_description = $("#tpl_description_"+ tpl_id).val(); //템플릿 내용
			// var tpl_extra = $("#tpl_extra_"+ tpl_id).val();
			// if (tpl_extra && tpl_extra!='NONE') {
			// 	tpl_extra = "<br><br>"+tpl_extra;
			// }else{
            //     tpl_extra ='';
            // }
            <? if($rs->tpl_cardType=="free"){ ?>
                $("#modal_templet_msg").html("내용을 작성하세요"); //템플릿 내용
            <?  }else{ ?>
            tpl_description = tpl_description.replace(/{{변수내용}}/gi, '행사 정보');
			tpl_description = tpl_description.replace(/{{회원정보 등록일}}/gi, '행사 정보');
			tpl_description = tpl_description.replace(/{{업체명}}/gi, '업체명');
			tpl_description = tpl_description.replace(/{{업체전화번호}}/gi, '전화번호');
			tpl_description = tpl_description.replace(/{{전화번호}}/gi, '전화번호');
			tpl_description = tpl_description.replace(/{{날짜}}/gi, '행사 날짜');
            tpl_description = tpl_description.replace(/{{등급}}/gi, '등급');
            tpl_description = tpl_description.replace(/{{변수}}/gi, '행사 날짜');
            tpl_description = tpl_description.replace('{{00}}', '00');
            $("#modal_templet_msg").html(tpl_description); //템플릿 내용
			$("#modal_templet_btn").html(btn_name); //템플릿 버튼
            <? } ?>

		}

		//알림톡버튼 모달
		var modal_templet = document.getElementById("modal_templet");
		function open_templet(){
			// msg_save('N');//알림톡 내용 임시저장
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
				url: "/dhnbiz/sender/send/rcs_v2/ajax_adv_template_data",
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
						var tpl_description = value.tpl_description; //템플릿 내용
                        if(tpl_description){
                            tpl_description = tpl_description.replace(/\n/gi, "<br>"); //<br>처리
    						tpl_description = tpl_description.replace(/{{업체명}}/gi, "<?=$this->member->item('mem_username')?>"); //업체명 적용
    						tpl_description = tpl_description.replace(/{{전화번호}}/gi, mem_phone); //연락처 적용
    						tpl_description = tpl_description.replace(/{{업체전화번호}}/gi, mem_phone); //연락처 적용
                        }

						var tpl_button = value.tpl_button; //템플릿 버튼
						// var tpl_premium_yn = value.tpl_premium_yn; //프리미엄 여부
						var badge = "<span class=\"badge_sta\">스탠<br>다드</span>";
						// if(tpl_premium_yn == "Y") badge = "<span class=\"badge_pre\">프리<br>미엄</span>";


						var btn_cnt = 0;
						var btn_name = "";
						var rtn_name = "";
                        if(tpl_button&&tpl_button!="null"){
                            var btn = tpl_button.replace(/&quot;/gi, "\"");
                            var btn_content = JSON.parse(btn);
    						for(var i=0;i<btn_content.length;i++){
    							btn_name += "  <p>"+ btn_content[i]["name"] +"</p>";
    							btn_cnt++;
    						}
                        }
						// var tpl_extra = value.tpl_extra;
						// if (tpl_extra == null) {
						// 	tpl_extra = "";
						// }
						if(btn_cnt == 0){
							rtn_name = "<p>버튼없음</p>";
						}else{
							rtn_name = btn_name;
						}
						var rtn_link = "selected_template('"+ tpl_id +"', 'temp');";
                        // 프리미엄 서버스 기능 제거 하단 한줄 주석처리 2021-08-03
						// if(tpl_premium_yn == "Y" && contract_type != "P") rtn_link = "alert('프리미엄 서비스입니다.');";
						//btn_name += "  <p>"+ tpl_id +"</p>";
						if(tpl_id != "" && tpl_id != undefined){
							html += "<li id='"+ tpl_id +"' class='li_templet_list'>";
                            // 프리미엄 서버스 기능 제거 하단 한줄 주석처리 2021-08-03
							// html += "  "+ badge; //뱃지
							html += "  <div class=\"li_templet_list_box\" onClick=\""+ rtn_link +"\">";
							html += "    <div>"+ rtn_name +"</div>"; //버튼
							html += "  </div>";
							html += "  <div class=\"type_btn\">";
							html += "    <button onClick=\"view_templet('"+ tpl_id +"', '"+ btn_name +"');\"><i class=\"xi-plus-circle-o\"></i> 미리보기</button>";
							html += "    <button onClick=\""+ rtn_link +"\"><i class=\"xi-check-circle-o\"></i> 선택하기</button>";
							html += "    <textarea id='tpl_description_"+ tpl_id +"' style='display:none;'>"+ tpl_description +"</textarea>";
							// html += "    <input type='hidden' id='tpl_extra_"+ tpl_id +"' value='"+ tpl_extra +"' />";
							html += "  </div>";
							html += "</li>";
							if(modal_tpl_no == 1){
								first_tpl_id = tpl_id;
								// first_tpl_premium_yn = tpl_premium_yn;
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
					url: "/dhnbiz/sender/send/rcs_v2/ajax_adv_template_sort",
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
        if(buttons&&buttons!="null"){
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
