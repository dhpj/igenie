<?if($rs->tpl_profile_key) {?>
	 <table class="table table-bordered table-highlight-head table-checkable mt10" id="friendtalk_table">
        <colgroup>
            <col stype="width:10px!important;">
            <col width="*">
            <col width="*">
            <col width="*">
            <col width="*">
            <col width="*">
        </colgroup>
        <tbody>
            <tr>
                <th>업체명</th>

                <td value="<?=$rs->spf_company?>" colspan="5">
                    <div class="input-group" style="width: 100%;">
                        <?=$rs->tpl_company."(".$rs->spf_friend.")"?>
                        <input type="hidden" id="pf_ynm" value="<?=$rs->tpl_company?>"/>
                        <input type="hidden" id="pf_yid" value="<?=$rs->spf_friend?>"/>
                        <input type="hidden" id="sms_sender" value="<?=$rs->spf_sms_callback?>"/>
                        <span class="input-group-btn" style="display: flex; float: right; margin-right: 95px;">
                            <button class="btn btn-sm btn-custom align-right" type="button"
                                    onclick="btnSelectTemplate()" id="modalBtn">
                                <i class="icon-ok"></i>템플릿 선택
                            </button>
                        </span>
                    </div>
                </td>
                <input type="hidden" name="pf_key" id="pf_key" value="<?=$rs->tpl_profile_key?>">
                <input type="hidden" name="kind" id="kind" value="<?=$rs->spf_key_type?>">
            </tr>
            <tr id="templi">
                <th>알림톡</th>
                <td colspan="5">
                    <script>var content = "<?=str_replace("\n", "<br>", $rs->tpl_contents)?>"; $("#templi_cont").val(content);</script>
                    <textarea name="templi_cont" id="templi_cont" cols="30" rows="5" class="form-control autosize" style="resize:none;"
                              placeholder="메세지를 입력해주세요."
                              onkeyup="onPreviewText();resize_cont(this);chkword_templi();"><?=$rs->tpl_contents?></textarea>
                    <p class="help-block align-left" style="width:80% !important" id="lms_ptag"><input type="checkbox" id="lms_select" class="uniform" onchange="select_lms(this)">LMS 발신 여부 ()</p>
                    <p class="help-block align-right" id="templi_length"><span id="type_num">0</span>/1000자</p>
                </td>
            </tr>
            <!-- 무조건 LMS 활성 -->
            <tr id="smslms">
                <th>LMS</th>
                <td colspan="5">
                    <textarea name="msg" id="lms" name="lms" cols="30" rows="5" class="form-control autosize" placeholder="LMS 발신 체크를 선택해주세요." style="resize:none; cursor: default;"
                              onkeyup="onPreviewText();resize_cont(this);chkword_lms();" disabled="disabled"></textarea>
                    <p class="help-block align-left" style="width:80% !important"><input type="checkbox" id="lms_kakaolink_select" class="uniform" onclick="insert_kakao_link(this);" style="cursor: default;" disabled> 카카오 친구추가 링크 넣기</p>
                    <p class="help-block align-right"><span id="lms_num">0</span>/1000자</p>
                </td>
                <input type="hidden" name="tit" id="tit">
            </tr>
            <tr id="btn_content_0">
                <th rowspan="20" width='15%'>링크 <a onclick='btn_add();' id="btn_add" style='cursor:pointer'>(추가)</a></th>
                <th style="text-align: center;" width='3%'>no</th>
                <th style="text-align: center;" width='18%'>버튼타입</th>
                <th style="text-align: center;" width='20%'>버튼명</th>
                <th style="text-align: center;" width='30%'>버튼링크</th>
                <th style="text-align: center;" width='10%'></th>
            </tr>
            <tr id='btn_content_1_1' name='1'>
                <td id='no' align='center' rowspan='2' hidden>1</td>
                <td id='btn_type_td' name='btn_type_td' align='center' rowspan='2' hidden>
                <select class='select2 input-width-small' id='btn_type1' name='btn_type' onchange='modify_btn_type(1);link_name(this,1);' style='display:none!important;'>
                    <option value='N'>선택</option>
                    <option value='WL'>웹링크</option>
                    <option value='AL'>앱링크</option>
                    <option value='BK'>봇키워드</option>
                    <option value='MD'>메시지전달</option>
                </select>
                </td>
                <td id='btn_add_msg' align='center' rowspan='2' colspan='5'>버튼을 추가할 수 있습니다.</td>
                <td id='n_1_1' align='center' rowspan='2' hidden></td>
                <td id='n_2_1' align='center' width='67px' hidden></td>
                <td id='bk_1_1' align='center' rowspan='2' hidden><input name='btn_name4' id='btn_name4_1' maxlength='10' onkeyup='link_name(this,1);scroll_prevent();btn_name_chk(this,1);' type='text' class='form-control input-width-small inline'></td>
                <td id='bk_2_1' align='center' width='67px' hidden></td>
                <td id='md_1_1' align='center' rowspan='2' hidden><input name='btn_name5' id='btn_name5_1' maxlength='10' onkeyup='link_name(this,1);scroll_prevent();btn_name_chk(this,1);' type='text' class='form-control input-width-small inline'></td>
                <td id='md_2_1' align='center' width='67px' hidden></td>
                <td id='wl_1_1' align='center' rowspan='2' hidden><input name='btn_name2' id='btn_name2_1' maxlength='10' onkeyup='link_name(this,1);scroll_prevent();btn_name_chk(this,1);' type='text' class='form-control input-width-small inline'></td>
                <td id='wl_2_1' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>Mobile</label>
                    <input style='margin-left: 10px;' type='text' value='http://' name='btn_url21' maxlength='254' class='form-control input-width-medium inline'></td>
                <td id='al_1_1' align='center' rowspan='2' hidden><input name='btn_name3' id='btn_name3_1' maxlength='10' onkeyup='link_name(this,1);scroll_prevent();btn_name_chk(this,1);' type='text' class='form-control input-width-small inline'></td>
                <td id='al_2_1' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>Android</label>
                    <input style='margin-left: 10px;' type='text' name='btn_url31' maxlength='254' class='form-control input-width-medium inline'></td>
                <td id='btn_del' align='center' rowspan='2' hidden><a onclick='btn_del(1);' id='btn_del' style='cursor:pointer'>제거</a></td>
            </tr>
            <tr id='btn_content_1' name='1'>
                <td id='wl_3_1' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>PC(선택)</label>
                    <input style='margin-left: 10px;' type='text' name='btn_url22' maxlength='254' class='form-control input-width-medium inline'></td>
                <td id='al_3_1' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 10px;'>iOS</label>
                    <input style='margin-left: 10px;' type='text' name='btn_url32' maxlength='254' class='form-control input-width-medium inline'></td>
            </tr>
        </tbody>
    </table>
<?} else {?>
    <div class="content_wrap">
        <div class="col-xs-8">
			 <div class="widget-header">
				  <h4>템플릿 선택</h4>
			 </div>
			 <div class="widget-content">
				  
				  <div class="alert alert-danger align-center">선택된 템플릿이 없습니다. 하단 버튼을 눌러 선택해주세요.</div>
				  
				  

				  <div class="align-center mt10">
						<button class="btn btn-sm btn-custom" type="button"
								  onclick="btnSelectTemplate();" id="modalBtn">
							 <i class="icon-ok"></i> 템플릿 선택
						</button>
				  </div>
			 </div>
    </div>
<?}?>