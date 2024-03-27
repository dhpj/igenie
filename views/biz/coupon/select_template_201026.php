<?if($rs->tpl_profile_key) {?>
<div class="input_content_wrap">
	<label class="input_tit">쿠폰명</label>
	<div class="input_content">
		<input class="" id="id_cc_title" name="cc_title" size = "60" value="<?=$rs->cc_title?>" <? echo ($rs->cc_status=='P')?"readonly":""; ?>>
	</div>
	<div class="mobile_preview">
		<div class="preview_circle"></div>
		<div class="preview_round"></div>
		<div class="preview_msg_wrap">
			<ul class="preview_tab">
				<li class="btn_tab active" id="msgpage">응모페이지</li>
				<li class="btn_tab" id="resultpage">당첨페이지</li>
			</ul>
			<div class="preview_msg_window">
				<div class="entry" style="display:">
					<div class="preview_box_profile">
						<span class="profile_thumb">
							<img src="/img/icon/icon_profile.jpg">
						</span>
						<span class="profile_text"><?=$rs->spf_friend?></span>
					</div>
					<div class="preview_box_msg" id="text"></div>
				</div>
				<div class="draw" style="display:none;">
					<div class="preview_img"></div>
					<div class="preview_text_box">
						<h3 class="preview_title"><?=$rs->cc_title?></h3>
						<p class="preview_date">사용기간 : <?=$rs->cc_start_date?> ~ <?=$rs->cc_end_date?></p>
						<div class="preview_text"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="preview_home"></div>
	</div><!-- mobile_preview END -->
</div>
<div class="input_content_wrap">
	<label class="input_tit">템플릿 코드</label>
	<div class="input_content">
		<?=$rs->tpl_code?>
		 <input type="hidden" name="pf_key" id="pf_key" value="<?=$rs->tpl_profile_key?>">
		 <input type="hidden" name="pf_yid" id="pf_yid" value="<?=$rs->spf_friend?>">
		 <input type="hidden" name="templi_code" id="templi_code" value="<?=$rs->tpl_code?>">
		 <input type="hidden" name="sbid" id="sbid" value="<?=$param['sb_id']?>">
		 <input type="hidden" name="kind" id="kind" value="L">
		 <input type="hidden" name="btn_name">
		 <input type="hidden" name="btn_url">
		 <input type="hidden" name="cc_tpl_code" value="<?=$rs->tpl_code?>">
		 <input type="hidden" name="cc_tpl_id" value="<?=$rs->tpl_id?>">
		 <input type="hidden" name="cc_type" value="AT">
	</div>
</div>	
<div class="input_content_wrap">
	<label class="input_tit">템플릿 명</label>
	<div class="input_content">
		<?=$rs->tpl_name?>
	</div>
</div>
<div class="input_content_wrap">
	<label class="input_tit">템플릿 내용</label>
	<div class="input_content">
		<!--ul class="btn_adv_wrap">
			<li class="btn_adv on">타입1</li>
			<li class="btn_adv">타입2</li>
		</ul-->
		<div class="msg_box">
			<textarea name="cc_msg" id="templi_cont" cols="20" rows="10" class="form-control autosize" placeholder="템플릿 내용을 입력해주세요." style="resize:none;cursor:default;display:none" onkeyup=" templi_preview(this); scroll_prevent(); templi_chk();" value="<?=$rs->tpl_contents?>" readonly><?=$rs->tpl_contents?></textarea>
			<div class="input_msg" style="height: auto;">
				<?
				$tmp_str = nl2br($rs->tpl_contents);

				$pattern = '/\#{[^}]*}/';
				$m_cnt = preg_match_all($pattern, $tmp_str, $match );
				$idx = 0;
				$m_idx = 0;
 
				while($m_cnt >= 1) {
				    $var_value = "";
				    if(!empty($varvalue)) {
				        $var_value=$varvalue[$idx]->var_text;
				    } 
				    $pla =  $match[0][$idx];
				     
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
			<span class="txt_byte"><p class="info_text" style="float: left;">"{변수}" 부분의 내용만 수정이 가능합니다.</p><span id="type_num">0</span>/1000자</span>
			<input type="hidden" id="hidden_cont"  value="<?=$rs->tpl_contents?>" />
		</div>
	</div>
</div>
<div class="input_content_wrap">
	<label class="input_tit">기간</label>
	<div class="input_content">
		 <input type="text" class="calendar" readonly placeholder="시작날짜 선택"  id="id_cc_start_date" name="cc_start_date" value="<?=$rs->cc_start_date?>" <? echo ($rs->cc_status=='P')?"readonly":""; ?>> <span style="line-height: 38px;">~</span>  <input type="text" class="calendar" readonly placeholder="종료날짜 선택" required id="id_cc_end_date" name="cc_end_date" value="<?=$rs->cc_end_date?>" <? echo ($rs->cc_status=='P')?"readonly":""; ?>>
	</div>
</div>
<div class="input_content_wrap">
	<label class="input_tit">쿠폰 번호 생성</label>
	<div class="input_content">
						<div class="checks">
						<? if($rs->cc_status!='P') { ?>
						<input type="radio" id="auto" name="id_coupon_create" style="display:none" onclick="javascript:$('#id_upload_div').css('display', 'none'); $('#id_input_div').css('display', '')" value="A" <? echo ($rs->cc_method=='A' || empty($rs->cc_method)) ? 'checked':''; ?> ><label for="auto" style="display:none">자동 생성</label>
						<input type="radio" id="upload" name="id_coupon_create" style="display:none" onclick="javascript:$('#id_input_div').css('display', 'none'); $('#id_upload_div').css('display', '')" value="M" <? echo ($rs->cc_method=='M') ? 'checked':''; ?>><label for="upload" style="display:none">직접 올리기</label>
						<? } else { ?>
						    <input type="hidden" name="id_coupon_create" value="<?=$rs->cc_method?>">
						<?}?>
						</div>
						<div id="id_input_div" style="<? echo ($rs->cc_method=='A'|| empty($rs->cc_method)) ? '':'display:none;'; ?>">
							<input class=" " id="id_cc_coupon_qty" name="cc_coupon_qty" style="text-align:right" type="number" value="<?=$rs->cc_coupon_qty?>" max="10000" <? echo ($rs->cc_status=='P')?"readonly":""; ?> > <span style="line-height: 38px;">개</span>
						</div>
						<div id="id_upload_div" style="<? echo ($rs->cc_method=='M') ? '':'display:none;'; ?>">
                            <div class="widget-content">
                                <div class="row">
                                    <div class="col-xs-11 file">
                                        <input type="file" name="userfile" id="userfile" multiple="multiple" style="cursor: default; padding: 20px 20px 20px 20px !important;">
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    $('#userfile').filestyle({
                                        input: true,
                                        buttonName: '',
                                        iconName: 'icon-folder-open',
                                        buttonText: '파일업로드'
                                    });
                                </script>
                                <style>
                                    .file input{
                                        cursor: default !important;
                                    }
                                    .file button{
                                        position: absolute;
                                        z-index: -1;
                                    }
                                </style>
                                <a herf="#" >업로드파일 양식 다운로드</a>
                                
                            </div>
						</div>  
	</div>
</div>
<div class="input_content_wrap">
	<label class="input_tit">당첨확률</label>
	<div class="input_content">
					<div class="checks">
					<? if($rs->cc_status!='P') { ?>
						<input type="radio" id="rate_01" name="cc_rate" onclick="javascript:$('#rate_id').css('display', 'none')" value="100" <? echo ($rs->cc_rate == '100') ? "checked" : ""; ?> <? echo ($rs->cc_status=='P')?"disabled":""; ?>><label for="rate_01">100%(선착순)</label>
						
						<input type="radio" id="rate_03" name="cc_rate" onclick="javascript:$('#rate_id').css('display', 'none')" value="50" <? echo ($rs->cc_rate == '50') ? "checked" : ""; ?> <? echo ($rs->cc_status=='P')?"disabled":""; ?>><label for="rate_03">50%</label>
					  	<input type="radio" id="rate_04" name="cc_rate" onclick="javascript:$('#rate_id').css('display', 'none')" value="25" <? echo ($rs->cc_rate == '25') ? "checked" : ""; ?> <? echo ($rs->cc_status=='P')?"disabled":""; ?>><label for="rate_04">25%</label>
					  	<input type="radio" id="rate_05" name="cc_rate" onclick="javascript:$('#rate_id').css('display', '')" value="0" <? echo ($rs->cc_rate == '0') ? "checked" : ""; ?> <? echo ($rs->cc_status=='P')?"disabled":""; ?>><label for="rate_05">직접입력 </label>
						</div>
					  	<div id="rate_id" style="<? echo ($rs->cc_rate == '0') ? "" : "display: none"; ?>">
						  	<input type="number" max="100" min="1" id="rate" name="cc_rate_txt" style="height: 28px;border-color: #ff9a00" value="<? if($rs->cc_rate_txt<1 || $rs->cc_rate_txt>100) { echo 1; } else {echo $rs->cc_rate_txt;} ?>" <? echo ($rs->cc_status=='P')?"readonly":""; ?>> <span style="line-height: 28px;">%</span>
					  	</div>  
					 <? } else {?>
					     <input type="number" max="100" min="1" class="form-control input-width-small " id="rate" name="cc_rate"  value="<?=$rs->cc_rate ?>" style="<? echo ($rs->cc_rate == '0') ? "display: none":""; ?>" readonly> %
					     <input type="number" max="100" min="1" class="" id="rate_txt" name="cc_rate_txt"  value="<?=$rs->cc_rate_txt ?>" style="<? echo ($rs->cc_rate == '0') ? "" : "display: none"; ?>" readonly>
					 <? } ?>
					</div>
	</div>
</div>

<div class="input_content_wrap">
	<label class="input_tit">당첨페이지</label>
	<div class="input_content">
		<? if($rs->cc_status!='P') { ?>
		<button class="btn md fr" type="button" onclick="imageSelect()" id="img_select"><i class="icon-folder-open"></i>이미지 선택</button> 
		<? } ?>
		<input name="img_link" id="img_link" value="<?=$rs->cc_img_link?>" size="50" style="width: 300px" readonly>
		<textarea name="cc_memo" id="id_cc_memo" cols="20" rows="10" style="width: 100%; resize: none; margin-top: 10px; font-size: 15px;" placeholder="당첨 페이지 텍스트를 입력 하세요" value=""><?=$rs->cc_memo?></textarea>
	</div>
</div>
<div class="input_content_wrap" style="display: none">
	<label class="input_tit">사용자 발급번호</label>
	<div class="input_content">
		<input class="" id="id_cc_userbarcode" name="cc_user_barcode" size = "30" value="<?=$rs->cc_user_barcode?>" <? echo ($rs->cc_status=='P')?"readonly":""; ?>>
	</div>
</div>
<div class="input_content_wrap" id="btn_0" style="display:none">
	<label class="input_tit">링크</label>
	<div class="input_content">

	</div>
</div>

  <!--table class="table table-bordered table-highlight-head table-checkable mt10" id="alimtalk_table">
		<tbody>		 		 
  				  <tr id="btn_0" style="display:none">
						<th rowspan="20">링크</th>
						<th style="text-align: center;">no</th>
						<th style="text-align: center;">버튼타입</th>
						<th style="text-align: center;">버튼명</th>
						<th style="text-align: center;">버튼링크</th>
				  </tr>

					<script type="text/javascript">
						var buttons = '<?=$rs->tpl_button?>';
						var btn = buttons.replace(/&quot;/gi, "\"");
						var btn_content = JSON.parse(btn);

						$(document).ready(function() {
							$('#templi_cont').click(function() { edit_control = "templi_cont"; });
							templi_preview(document.getElementById("templi_cont")); scroll_prevent();  templi_chk();
							
						});

						for(var i=0;i<btn_content.length;i++) {
							 var html = '';
							 var no = i + 1;
							 var ordering= (typeof(btn_content[i]["ordering"])=='undefined') ? no : btn_content[i]["ordering"];
							 var btn_type = btn_content[i]["linkType"];
							 var type = '';
							 var btn_name = btn_content[i]["name"];
							 if(btn_type=="DS") { type="<?=$this->Biz_model->buttonName['DS']?>"; }
							 else if(btn_type=="BK") { type="<?=$this->Biz_model->buttonName['BK']?>"; }
							 else if(btn_type=="MD") { type="<?=$this->Biz_model->buttonName['MD']?>"; }
							 else if(btn_type=="AL") { type="<?=$this->Biz_model->buttonName['AL']?>"; }
							 else if(btn_type=="WL") { type="<?=$this->Biz_model->buttonName['WL']?>"; }

							 var url_name1 = '';
							 var url_name2 = '';
							 var url_link1 = '';
							 var url_link2 = '';
							 if (btn_type == "DS" || btn_type == "BK" || btn_type == "MD") {
								  html += "<tr id='btn_" + no + "' style='display:none'>";
								  html += "<td id='no' align='center'>" + ordering + "</td>";
								  html += "<td align='center'>" + type + "<input id='btn_type' name='btn_type' type='hidden' value='" + btn_type + "'></td>";
								  html += "<td align='center'>" + btn_name + "<input id='btn_name' name='btn_name' type='hidden' value='" + btn_name + "'></td>";
								  html += "<td align='center'></td>";
								  html += "</tr>";
							 } else if (btn_type == "WL" || btn_type == "AL") {
								  html += "<tr id='btn_"+no+"_1' style='display:none'>";
								  html += "<td id='no' align='center' rowspan='2'>" + ordering + "</td>";
								  html += "<td align='center' rowspan='2'>" + type + "<input id='btn_type' name='btn_type' type='hidden' value='" + btn_type + "'></td>";
								  html += "<td align='center' rowspan='2'>" + btn_name + "<input id='btn_name' name='btn_name' type='hidden' value='" + btn_name + "'></td>";
								  if(btn_type == "WL") {
										url_name1 = "Mobile";
										//url_name2 = "PC(선택)";
										url_link1 = btn_content[i]["linkMo"];
										//url_link2 = btn_content[i]["linkPc"];
										html += "<td><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>" + url_name1 + "</label><br><input style='margin-left: 10px;' id='btn_url' name='cc_button1' maxlength='255' type='text' value='" +  url_link1  + "' placeholder='링크 주소를 입력해주세요.' class='form-control input-width-medium inline'><input type='hidden' id='hidden_url' value='" + url_link1 + "'></td>";
										html += "</tr>";
										html += "<tr id='btn_" + no + "'>";
										
										html += "</tr>";
								  } else if(btn_type == "AL") {
										url_name1 = "Android";
										url_name2 = "iOS";
										url_link1 = btn_content[i]["linkAnd"];
										url_link2 = btn_content[i]["linkIos"];
										html += "<td><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>" + url_name1 + "</label><br><input style='margin-left: 10px;' id='btn_url' name='cc_button1' maxlength='255' type='text' value='" +  url_link1  + "' placeholder='링크 주소를 입력해주세요.' class='form-control input-width-medium inline'><input type='hidden' id='hidden_url' value='" + url_link1 + "'></td>";
										html += "</tr>";
										 
								  }
							 }
							 $("#btn_" + i).after(html);
						}

				  </script>
		</tbody>
  </table-->	

  <script type="text/javascript">
	$(document).ready(function() {
 
		$("#id_cc_start_date, #id_cc_end_date").datepicker({
			format:'yyyy.mm.dd',
			todayBtn:"linked",
			language:"kr",
			autoclose:true,
			todayHighlight:true
		});		
	});

	$("#id_cc_memo").on('change keyup paste', function() {
		show_pre_draw();
	});
	
 	$("#msgpage").click(function() {
 	 	$(".entry").show();
 	 	$("#msgpage").addClass("active");
 	 	
 	 	$(".draw").hide();
 	 	$("#resultpage").removeClass("active");
 	});

 	$("#resultpage").click(function() {
 	 	$(".entry").hide();
 	 	$("#msgpage").removeClass("active");
 	 	
 	 	show_pre_draw();
 	 	
 	 	$(".draw").show();
 	 	$("#resultpage").addClass("active");
 	}); 	

 	function show_pre_draw() {
		var imglink = $("#img_link").val();
 	 	$(".preview_img").html("<img src='" + imglink + "'>");
 	 	var ccmemo = $("#id_cc_memo").val();
 	 	$(".preview_text").html(ccmemo.replace(/(\n|\r\n)/g, '<br>'));
 	}
</script>

<?} else {?>
<div class="nodata">
	<i class="material-icons icon_error">error_outline</i>
	<p>선택된 템플릿이 없습니다. 템플릿을 선택해 주세요.</p>
	<button class="btn md yellow plus" type="button" onclick="btnSelectTemplate();" id="modalBtn">템플릿 선택하기</button>
</div>
<?}?>