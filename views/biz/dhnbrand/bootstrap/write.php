    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="modal">
            <div class="modal-content">
	            <i class="material-icons modal_close" data-dismiss="modal">close</i>
                <div class="modal-body icon_alarm">
                    <div class="content"></div>
                    <div class="modal_bottom">
                        <button type="button" class="btn md btn-custom" data-dismiss="modal">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- 타이틀 영역 -->
	<div class="tit_wrap">
		템플릿 관리
	</div>
<!-- 타이틀 영역 END -->
<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<h3>템플릿 등록</h3>
						</div>
						<div class="white_box">
				                <div class="guide_box" style="margin-bottom: 15px;">
					                <h3>템플릿 등록 가이드</h3>
				                    <ul>
					                    <li>입력란은 총 10개까지 생성가능합니다.</li>
					                    <li>발신프로필 그룹 등록 시, 그룹간 동일한 템플릿 코드와 템플릿명을 중복해서 등록할 수 없습니다.</li>
					                    <li>템플릿 동시 등록은 가능하나, 수정 및 검수 요청은 템플릿 별로 가능합니다.</li>
					                    <li>발신 업체명(발송할 플러스친구)을 선택해 주세요. 템플릿별 하나의 플러스친구만 선택 가능합니다.</li>
					                    <li>템플릿명은 메시지 내용에 포함되지 않습니다.</li>
					                    <li>한/영구분없이 URL 포함 1,000자 입력 가능합니다. 변수에 들어갈 내용의 최대 길이를 감안하여 작성해 주세요.</li>
					                    <li>변수를 <span style="background-color: yellow;">#{변수}</span> 형태로 넣어주세요. 특수문자(<b>#{}</b>)가 다를 경우 변수로 사용할 수 없습니다. <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(예 : <span style="background-color: yellow;">#{홍길동}</span>님의 택배가 금일 (<span style="background-color: yellow;">#{09:50}</span>)에 배달될 예정입니다.)</li>
					                    <li>하나의 발신프로필에 동일한 템플릿코드와 템플릿명을 중복해서 등록할 수 없습니다.</li>
					                    <li style="font-weight: bold;"><a href="/uploads/kakao_template_guide_202106.pdf" target="_blank" style="color:red; font-weight: bold;">템플릿 검수 가이드 문서</a> 보러가기 (필독)</li>
				                    </ul>
			                	</div>
			                	<form class="form-horizontal row-border" action="#" name="baseForm" id="baseForm">
		                            <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
		                            <input type='hidden' name='actions' value='template_save' />
		                            <input type='hidden' id='senderKeyType' value='<?=($public_flag=="N")?"S":"G"?>' />
                        		</form>
		                        <!--
		                        <p class="align-center mt30">
		                            <input type="hidden" name="vi_count_num" id="vi_count_num" value="0">
		                            <a href="javascript:void(0);" onclick="addForm();" class="btn"> <i class="icon-plus"></i> 입력란 추가</a>&nbsp;
		                            <a href="javascript:void(0);" onclick="standard_del();" class="btn"> <i class="icon-minus"></i> 입력란 삭제</a>&nbsp;
		                        </p>
		                         -->
                            <div class="btn_al_cen mg_b50">
                              <!-- button class="btn" type="button" id="cancel" name="cancel" onclick="location.href='/template/add/'">취소</button-->&nbsp;
                              <button class="btn_st3" type="button" onclick="javascript:register_template()">템플릿 등록</button>
                            </div>
						</div>
					</div>
</div>
    <style>
        textarea {
            resize: none;
        }
    </style>
    <script type="text/javascript">
        addForm();
        function addForm() {
            var col_add_no=($("#vi_count_num").val()*1)+1;
            var col_html="";

            col_html += "			<div class='table_list'>";
            col_html += "			<table id='addedFormDiv'>";
            col_html += "				<colgroup>";
            col_html += "					<col width='13%'>";
            col_html += "					<col width='*'>";
            col_html += "				</colgroup>";
            col_html += "				<tr>";
            <? if($public_flag == 'N' ) { ?>
            col_html += "					<th>업체명</th>";
            <? } else if($public_flag == 'Y' ) { ?>
            col_html += "					<th>그룹명<span class='required'>*</span></th>";
            <? } ?>
			col_html += "					<td colspan='5' style='text-align:left'>";
			<? if($public_flag == 'N' ) { ?>
            col_html += "						<select name='input_pf_ynm' id='input_pf_ynm' class=''>";
            col_html += "                               " ;
			col_html += "							<option value='none'>업체명 선택</option>";
			<?	foreach($profile as $r) {?>
				col_html += "							        <option value='<?=$r->spf_key?>'><?=$r->spf_company?>(<?=$r->spf_friend?>)</option>";
				<?}
			} else if($public_flag == 'Y' ) { ?>
            //col_html += "						<select name='input_pf_ynm' id='input_pf_ynm' class='' onchange='public_part_lists(this)'>";
            col_html += "						<select name='input_pf_ynm' id='input_pf_ynm' class=''>";
            col_html += "                               " ;
			col_html += "							<option value='none'>그룹명 선택</option>";
			<?	foreach($profile_group as $r) {
				?>
				//col_html += "							        <option value='<?=$r->spg_pf_key?>'><?=$r->spg_name?></option>";
        col_html += "							         <option value='<?=$r->spf_key?>'><?=$r->spf_company?>(<?=$r->spf_friend?>)</option>";
				<?
				}
			}
				?>

            col_html += "                               " ;
            col_html += "						</select>";
            col_html += "					</td>";
            col_html += "				</tr>";
            <? if($public_flag == 'N' ) { ?>
            col_html += "				<tr style='display:none'>";
            col_html += "					<th>분류명</th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "						<input name='input_part_id' id='input_part_id' value='none'>";
            col_html += "					</td>";
            col_html += "				</tr>";
            <? } else if($public_flag == 'Y' ) { ?>
            col_html += "				<tr style='display:none'>";
            col_html += "					<th>분류명</th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "						<select name='input_part_id' id='input_part_id' class=''>";
			      col_html += "							<option value='none'>분류명 선택</option>";
            col_html += "                               " ;
            col_html += "						</select>";
            col_html += "					</td>";
            col_html += "				</tr>";
            <? } ?>
            col_html += "				<tr>";
            col_html += "					<th>템플릿 코드<span class='required'>*</span></th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "						<input id='input_tmp_code' placeholder='최대 30자'  maxlength='30'  class='form-control required input-width-large' type='text' style='ime-mode:disabled;' onkeyup='onOnlyAlphaNumber(this);'>";
            col_html += "					</td>";
            col_html += "				</tr>";
            col_html += "				<tr>";
            col_html += "					<th>보안 템플릿 설정</th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "						<input type='checkbox' id='securityFlag' name='securityFlag'><label for='securityFlag' class='mg_l10 pd_t10'> 보안 템플릿 체크 시, 메인 디바이스 모바일 외 모든 서브 디바이스에서는 메시지 내용이 노출되지 않습니다.</label>";
            col_html += "					</td>";
            col_html += "				</tr>";
            col_html += "				<tr>";
            col_html += "					<th>카테고리<span class='required'>*</span></th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "                       <select style='width:300px' class='select2 input-width-medium none-search' id='categoryCode' name='categoryCode'>";
            col_html += "                           <option value=''>카테고리선택</option>";
			<?
			foreach($categoryAll as $r) {
			?>
            col_html += "                           <option value='<?=$r->code?>'><?=$r->groupName?> - <?=$r->name?></option>";
            <?
			}
            ?>
            col_html += "                       </select>";
            col_html += "					</td>";
            col_html += "				</tr>";
            col_html += "				<tr>";
            col_html += "					<th>템플릿명<span class='required'>*</span></th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "						<input id='input_tmp_name' placeholder='최대 200자' maxlength='30' class='form-control required input-width-large' type='text'>";
            col_html += "					</td>";
            col_html += "				</tr>";

            col_html += "				<tr>";
            col_html += "					<th>메시지 유형</th>";
            col_html += "					<td colspan='2' style='text-align:left'>";
            col_html += "						<select name='msg_type_id' id='msg_type_id' class='' onchange='msgtypeChange(this)'>";
            col_html += "							<option value='BA'>기본형</option>";
            col_html += "							<option value='EX'>부가 정보형</option>";
            //col_html += "							<option value='AD'>광고 추가형</option>";
            //col_html += "							<option value='MI'>복합형</option>";
            col_html += "                               " ;
            col_html += "						</select>";
            col_html += "					</td>";
            col_html += "					<th>강조 유형</th>";
            col_html += "					<td colspan='2' style='text-align:left'>";
            col_html += "						<select name='emphasis_type_id' id='emphasis_type_id' class='' onchange='emphasisChange(this)'>";
            col_html += "							<option value='NONE'>선택안함</option>";
            col_html += "							<option value='IMAGE'>이미지형</option>";
            col_html += "							<option value='TEXT'>강조표기형</option>";
            col_html += "                               " ;
            col_html += "						</select>";
            col_html += "					</td>";
            col_html += "				</tr>";

            col_html += "				<tr id='emphasis_image_type' style='display:none'>";
            col_html += "					<th>이미지</th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "						<input id='alimimg_preview' class='fl bor_none' disabled>";
            col_html += "						<input name='alimimg' id='alimimg' class='fl bor_none upload_hidden' type='file'  onChange='fileChange(this, \"alimimg_preview\");' fileread='data.templateImage'>";
            col_html += "         <label for='alimimg' class='btn_upload'>파일선택</label>";
		    col_html += "                         <button class='btn md' type='button' onclick='image_upload()'>업로드</button>";
            col_html += "						  <span id='image_url_span'></span>";
            col_html += "						  <input id='image_url' type='hidden'  >";            
            col_html += "					</td>";
            col_html += "				</tr>";

            col_html += "				<tr id='emphasis_text_type' style='display:none'>";
            col_html += "					<th>강조 표기 타이틀</th>";
            col_html += "					<td colspan='2' style='text-align:left'>";
            col_html += "						<input id='emphasis_title_id' name='emphasis_title_id' placeholder='텍스트를 입력해주세요. (최대 50자)' maxlength='50'  class='form-control required' type='text' style='ime-mode:disabled;'>";
            col_html += "					</td>";
            col_html += "					<th>강조 표기 보조 문구</th>";
            col_html += "					<td colspan='2' style='text-align:left'>";
            col_html += "						<input id='emphasis_subtitle_id' name='emphasis_subtitle_id' placeholder='텍스트를 입력해주세요. (최대 50자)' maxlength='50'  class='form-control required' type='text' style='ime-mode:disabled;'>";
            col_html += "					</td>";
            col_html += "				</tr>";

            col_html += "				<tr>";
            col_html += "					<th>템플릿 내용<span class=\"required\">*</span></th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "                       <textarea name='input_tmp_cont' onkeyup='check_word_count(1000);' id='input_tmp_cont' cols='30' rows='5' class='form-control required' placeholder='내용을 입력해주세요.'></textarea>";
            col_html += "						<DIV class=remaining style='color: #8c8c8c'><SPAN class='count'>0</SPAN>/1000</DIV>";
            col_html += "					</td>";
            col_html += "				</tr>";
            col_html += "				<tr id='extended_info' style='display:none'>";
            col_html += "					<th>부가 정보</th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "                       <textarea name='input_tmp_extra' onkeyup='check_word_count(500);' id='input_tmp_extra' cols='30' rows='5' class='form-control required' placeholder='내용을 입력해주세요.'></textarea>";
            col_html += "						<DIV class=remaining style='color: #8c8c8c'><SPAN class='count'>0</SPAN>/500</DIV>";
            col_html += "					</td>";
            col_html += "				</tr>";

            // 링크 버튼 추가
            col_html += "       <tr id='btn_content'>";
            col_html += "					<th>링크 버튼<br><button class='btn sm' type='button' onclick='javascript:btn_add();'><i class='icon-plus'></i> 추가</button></th>";
            col_html += "                   <td style='padding:0px' colspan='5'>";
            col_html += "                   <table><tr>";
            col_html += "					    <th align='center' width='10%'>no</th>";
            col_html += "					    <th align='center' width='20%'>버튼 타입</th>";
            col_html += "					    <th align='center' width='20%'>버튼명</th>";
            col_html += "					    <th align='center' width='30%'>버튼 링크</th>";
            col_html += "					    <th align='center' width='10%'></th>";
            col_html += "                   </tr>";
            col_html += "                   <tr class='btninspoint'><td colspan='5'>버튼을 추가할 수 있습니다.</td></tr>";
            col_html += "                   </table>";
            col_html += "        </td></tr>";
            //공통

            //바로연결 버튼 추가
            col_html += "       <tr id='link_btn_content'>";
            col_html += "					<th>바로연결 링크 버튼<br><button class='btn sm' type='button' onclick='javascript:lbtn_add();'><i class='icon-plus'></i> 추가</button></th>";
            col_html += "                   <td style='padding:0px' colspan='5'>";
            col_html += "                   <table><tr>";
            col_html += "					    <th align='center' width='10%'>no</th>";
            col_html += "					    <th align='center' width='20%'>바로연결 타입</th>";
            col_html += "					    <th align='center' width='20%'>바로연결명</th>";
            col_html += "					    <th align='center' width='30%'>바로연결 링크</th>";
            col_html += "					    <th align='center' width='10%'></th>";
            col_html += "                   </tr>";
            col_html += "                   <tr class='linkinspoint'><td colspan='5'>바로연결을 추가할 수 있습니다.</td></tr>";
            col_html += "                   </table>";
            col_html += "        </td></tr>";
            //공통

            col_html += "			</table>";
            col_html += "			</div>";

            var print_add_to_html="";

            var print_add_html = col_html.replace(/\|col_no\|/gi, col_add_no);
            print_add_to_html += print_add_html;

            if (col_add_no>10){
                $(".content").html("한번에 최대 10개까지만 등록 가능합니다.");
                $("#myModal").modal('show');
            } else {
                $("#vi_count_num").val(col_add_no);
                col_add_no++;

                $("#baseForm").append(print_add_to_html);
                $('td').css('border-left','1px solid #dedede').css('border-right','1px solid #dedede');
//                 $('select.search-static').select2().on('select2-open', function () {
//                     $('.select2-drop-mask').height($(document).height());
//                 });;
//                 $('select.none-search').select2({
//                     minimumResultsForSearch: Infinity
//                 }).on('select2-open', function () {
//                     $('.select2-drop-mask').height($(document).height());
//                 });

            }
        }

        function emphasisChange(obj) {
			$(obj).parents("#addedFormDiv").find("#emphasis_image_type").css("display","none");
			$(obj).parents("#addedFormDiv").find("#emphasis_text_type").css("display","none");

			if($(obj).val() == "IMAGE") {
				$(obj).parents("#addedFormDiv").find("#emphasis_image_type").css("display","");
			} else if($(obj).val() == "TEXT") {
				$(obj).parents("#addedFormDiv").find("#emphasis_text_type").css("display","");
			}
        }

        function msgtypeChange(obj) {
			$(obj).parents("#addedFormDiv").find("#extended_info").css("display","none");

			if($(obj).val() == "EX") {
				$(obj).parents("#addedFormDiv").find("#extended_info").css("display","");
			}
        }

        function btn_type_select(sel, cno) {
            //var btn_type=$("#btn_type"+cno + "_" + obj).val();
//console.log(.attr("id"));
            var btn_type = $(sel).val();
			var hs = "";
            if (btn_type == "DS") {
                hs = hs + "* 알림톡 메시지 파싱을 통해 배송조회 카카오검색 페이지 링크가 자동 생성됩니다.<br>* 배송조회 링크가 정상적으로 생성되는지 필히 사전 테스트 해보시기 바랍니다.";
            } else if (btn_type == "WL") {
                hs = hs + "<table style='border: 0px;'>"
                hs = hs + "<tr><td style='border: 0px;'>Mobile</td><td style='border: 0px;'><input id='mlink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
                hs = hs + "<tr><td style='border: 0px;'>PC(선택)</td><td style='border: 0px;'><input id='plink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
            	hs = hs + "</table>";
            } else if (btn_type == "AL") {
                hs = hs + "<table style='border: 0px;'>"
                hs = hs + "<tr><td style='border: 0px;'>Android</td><td style='border: 0px;'><input id='androidlink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
                hs = hs + "<tr><td style='border: 0px;'>iOS</td><td style='border: 0px;'><input id='ioslink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
                hs = hs + "<tr><td style='border: 0px;'>Mobile</td><td style='border: 0px;'><input id='mlink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
                hs = hs + "<tr><td style='border: 0px;'>PC(선택)</td><td style='border: 0px;'><input id='plink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
            	hs = hs + "</table>";
            	hs = hs + "<b>Mobile, Android, iOS 중 2가지 이상 입력 필수</b>";
            }
            $(sel).parents(".alimbutton").children("#btnlink").html("");
            $(sel).parents(".alimbutton").children("#btnlink").append(hs);

        }

        function btn_add() {

            var button_length = $(".alimbutton").length;

            if(button_length == 0) {
                $(".btninspoint").css("display","none");
            }

            if (button_length < 5) { // 업체명,템플릿코드,템플릿명,템플릿내용,버튼5개 = 9

                var col_html = "";
                //공통
                col_html += "               <tr class='alimbutton' id='alimbtn"+(button_length +1)+"'>";
                col_html += "                   <td id='no' align='center' ><span id='btnno'>"+ (button_length +1) + "</span></td>";
                col_html += "                   <td id='btn_type_td' name='btn_type_td' align='center' >";
                col_html += "                       <select class='select2 none-search input-width-medium' id='btn_type' name='btn_type' onchange='btn_type_select(this)'>";
                col_html += "                           <option value='N'>선택</option>";
                col_html += "                           <option value='DS'>배송조회</option>";
                col_html += "                           <option value='WL'>웹링크</option>";
                col_html += "                           <option value='AL'>앱링크</option>";
                col_html += "                           <option value='BK'>봇키워드</option>";
                col_html += "                           <option value='MD'>메시지전달</option>";
                col_html += "                           <option value='BC'>상담톡전환</option>";
                col_html += "                           <option value='BT'>봇전환</option>";
                col_html += "                       </select>";
                col_html += "                   </td>";
                col_html += "                   <td>";
                col_html += "                     <input name='btn_name' id='btn_name' placeholder='14자 이내' maxlength='28' type='text' class='form-control input-width-medium inline'>";
                col_html += "                   </td>";
                col_html += "                   <td id='btnlink'></td>";
                col_html += "                   <td><button class='btn' type='button' onclick='btnremove(this)'>삭제</button></td>";
                col_html += "               </tr>";

                $(".btninspoint").before(col_html);

            }
        }


        function btnremove(rm) {
        	var no = $(rm).parents(".alimbutton").attr("id");
        	no = Number(no.replace("alimbtn", ""));

        	var button_length = $(".alimbutton").length + 1;

			for(let i=(no+1); i<button_length; i++) {
				//console.log($("#alimbtn" + i).children("#no").children("#btnno").html());
				$("#alimbtn" + i).children("#no").children("#btnno").html(i-1);
				$("#alimbtn" + i).attr("id", "alimbtn" + (i-1));
			}
			$(rm).parents(".alimbutton").remove();

			button_length = $(".alimbutton").length;

            if(button_length == 0) {
                $(".btninspoint").css("display","none");
            }
        }

        function link_type_select(sel) {

            var btn_type = $(sel).val();
			var hs = "";
            if (btn_type == "WL") {
                hs = hs + "<table style='border: 0px;'>"
                hs = hs + "<tr><td style='border: 0px;'>Mobile</td><td style='border: 0px;'><input id='mlink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
                hs = hs + "<tr><td style='border: 0px;'>PC(선택)</td><td style='border: 0px;'><input id='plink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
            	hs = hs + "</table>";
            } else if (btn_type == "AL") {
                hs = hs + "<table style='border: 0px;'>"
                hs = hs + "<tr><td style='border: 0px;'>Android</td><td style='border: 0px;'><input id='androidlink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
                hs = hs + "<tr><td style='border: 0px;'>iOS</td><td style='border: 0px;'><input id='ioslink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
                hs = hs + "<tr><td style='border: 0px;'>Mobile</td><td style='border: 0px;'><input id='mlink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
                hs = hs + "<tr><td style='border: 0px;'>PC(선택)</td><td style='border: 0px;'><input id='plink' placeholder='http://'  class='form-control required input-width-large' type='text'></td></tr>";
            	hs = hs + "</table>";
            	hs = hs + "<b>Mobile, Android, iOS 중 2가지 이상 입력 필수</b>";
            }
            $(sel).parents(".alimlinkbtn").children("#btnlink").html("");
            $(sel).parents(".alimlinkbtn").children("#btnlink").append(hs);

        }

        function lbtn_add() {

            var button_length = $(".alimlinkbtn").length;

            if(button_length == 0) {
                $(".linkinspoint").css("display","none");
            }

            if (button_length < 10) { // 업체명,템플릿코드,템플릿명,템플릿내용,버튼5개 = 9

                var col_html = "";
                //공통
                col_html += "               <tr class='alimlinkbtn' id='alimlinkbtn"+(button_length +1)+"'>";
                col_html += "                   <td id='no' align='center' ><span id='btnno'>"+ (button_length +1) + "</span></td>";
                col_html += "                   <td id='btn_type_td' name='btn_type_td' align='center' >";
                col_html += "                       <select class='select2 none-search input-width-medium' id='btn_type' name='btn_type' onchange='link_type_select(this)'>";
                col_html += "                           <option value='N'>선택</option>";
                col_html += "                           <option value='WL'>웹링크</option>";
                col_html += "                           <option value='AL'>앱링크</option>";
                col_html += "                           <option value='BK'>봇키워드</option>";
                col_html += "                           <option value='MD'>메시지전달</option>";
                col_html += "                           <option value='BC'>상담톡전환</option>";
                col_html += "                           <option value='BT'>봇전환</option>";
                col_html += "                       </select>";
                col_html += "                   </td>";
                col_html += "                   <td>";
                col_html += "                     <input name='btn_name' id='btn_name' placeholder='14자 이내' maxlength='28' type='text' class='form-control input-width-medium inline'>";
                col_html += "                   </td>";
                col_html += "                   <td id='btnlink'></td>";
                col_html += "                   <td><button class='btn' type='button' onclick='lbtnremove(this)'>삭제</button></td>";
                col_html += "               </tr>";

                $(".linkinspoint").before(col_html);

            }
        }

        function lbtnremove(rm) {
        	var no = $(rm).parents(".alimlinkbtn").attr("id");
        	no = Number(no.replace("alimlinkbtn", ""));

        	var button_length = $(".alimlinkbtn").length + 1;

			for(let i=(no+1); i<button_length; i++) {
				//console.log($("#alimbtn" + i).children("#no").children("#btnno").html());
				$("#alimlinkbtn" + i).children("#no").children("#btnno").html(i-1);
				$("#alimlinkbtn" + i).attr("id", "alimlinkbtn" + (i-1));
			}
			$(rm).parents(".alimlinkbtn").remove();

			button_length = $(".alimlinkbtn").length;

            if(button_length == 0) {
                $(".linkinspoint").css("display","none");
            }
        }

        function standard_del(){
            var cno=$("#vi_count_num").val()*1;
            if (cno < 2){
                $(".content").html("삭제할 정보가 없습니다.");
                $("#myModal").modal('show');
            } else {
                $("#addedFormDiv_"+cno).remove();
                cno--;
                $("#vi_count_num").val(cno);
            }
        }
        
        function image_upload() {
    		var formData = new FormData();
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			formData.append("image", $("input[name=alimimg]")[0].files[0]);
    		//console.log(formData);
    		$.ajax({
    			url: "/dhnbiz/template/atimgupload",
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
 					console.log(json);
 					if(json['code']=='0000') {
 						$("#image_url_span" ).text(json['image']);
 						$("#image_url").val(json['image']);
 					} else {
 	 					$("#image_url_span").text(json['message']);
 	 					$("#image_url" ).val("");
 					}
    			}
    		});            
			
        }        
    </script>

    <script type="text/javascript">
        $("#nav li.nav30").addClass("current open");

        $(document).unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                $(".btn-custom").click();
            }
        });

        //CSRF token획득
        function getCookie(name) {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }


        //입력 글자수 확인
        function check_word_count(maxlength) {
            $('.remaining').each(function () {
                var $count = $('.count', this);
                var $input = $(this).prev();

                var update = function () {
                    var before = $count.text() * 1;
                    var now = $input.val().length;

                    if (now > maxlength) {
                        var str = $input.val();
                        $input.val(str.substr(0, maxlength));
                        now = 0;
                    }

                    if (before != now) {
                        $count.text(now);
                    }
                };

                $input.bind('input keyup paste', function () {
                    setTimeout(update, 0)
                });
                update();
            });
        }

        function check_word_count2(maxlength) {
            $('.remaining2').each(function () {

                var $count = $('.count2', this);
                var $input = $(this).prev();

                var update = function () {
                    var before = $count.text() * 1;
                    var now = $input.val().length;
                    if (now > maxlength) {
                        var str = $input.val();
                        $input.val(str.substr(0, maxlength));
                        now = 0;
                    }

                    if (before != now) {
                        $count.text(now);
                    }
                };

                $input.bind('input keyup paste', function () {
                    setTimeout(update, 0)
                });
                update();
            });
        }

        //템플릿 코드 한글 입력 제한
        function onOnlyAlphaNumber(obj) {
            var str = obj.value;
            var len = str.length;
            var ch = str.charAt(0);
            for (var i = 0; i < len; i++) {
                ch = str.charAt(i);
                if ((ch >= '0' && ch <= '9') || (ch >= 'a' && ch <= 'z') || (ch >= 'A' && ch <= 'Z') ||
                        (ch == '!') || (ch == '@') || (ch == '#') || (ch == '$') || (ch == '%') || (ch == '^') ||
                        (ch == '&') || (ch == '*') || (ch == '(') || (ch == ')') || (ch == '_') || (ch == '-') ||
                        (ch == '+') || (ch == '=') || (ch == '?') || (ch == '/')) {
                    continue;
                } else {
                    //alert("영문, 숫자 또는 특수문자만 입력이 가능합니다.");

                    $(".content").html("영문, 숫자 또는 특수문자만 입력이 가능합니다.");
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                        obj.value = obj.value.replace(/[\ㄱ-ㅎㅏ-ㅣ가-힣]/g, '');
                        obj.focus();
                    });

                    return true;
                }
            }
        }

        //템플릿 등록
        function register_template() {

            var filesOjb = [];
            var count = 1; //($("#vi_count_num").val()*1);
            var csrftoken = '<?=$this->security->get_csrf_hash()?>';
            var isValid = true;

			$("#addedFormDiv").each(function() {

	            var jsonObj = [];
	            var btnObj = [];
	            var linkObj = [];

                if ($(this).find('#input_pf_ynm').val() == "none") {
                    isValid = false;
                    <? if($public_flag == 'N' ) { ?>
                    $(".content").html("업체명을 선택해주세요.");
                    <? } else if($public_flag == 'Y' ) {?>
                    $(".content").html("그룹명을 선택해주세요.");
                    <? } ?>
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                    	$(this).find('#input_pf_ynm').focus();
                    });
                } else if (!$(this).find('#input_tmp_code').val()) {
                    isValid = false;
                    $(".content").html("템플릿 코드를 입력해주세요.");
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                    	$(this).find('#input_tmp_code').focus();
                    });
                } else if (!$(this).find('#categoryCode').val()) {
                    isValid = false;
                    $(".content").html("카테고리를 선택해주세요.");
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                    	$(this).find('#categoryCode').focus();
                    });
                } else if (!$(this).find('#input_tmp_name').val()) {
                    isValid = false;
                    $(".content").html("템플릿 명을 입력해주세요.");
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                    	$(this).find('#input_tmp_name').focus();
                    });
                } else if (!$(this).find('#input_tmp_cont').val()) {
                    isValid = false;
                    $(".content").html("템플릿 내용을 입력해주세요.");
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                    	$(this).find('#input_tmp_cont').focus();
                    });
                } else {
                    var btnorder = 1;
					$(this).find(".alimbutton").each(function() {
						if($(this).find("#btn_type").val() == "N") {
							   isValid = false;
			                    $(".content").html("버튼 타입을 선택 하세요.");
			                    $("#myModal").modal('show');
			                    $('#myModal').on('hidden.bs.modal', function () {
			                    	$(this).find("#btn_type").focus();
			                    });
						} else if(!$(this).find("#btn_name").val()) {
							   isValid = false;
			                    $(".content").html("버튼명을 입력 하세요.");
			                    $("#myModal").modal('show');
			                    $('#myModal').on('hidden.bs.modal', function () {
			                    	$(this).find("#btn_name").focus();
			                    });
						}
						var btn = {
							"ordering":String(btnorder),
							"type":$(this).find("#btn_type").val(),
							"name":$(this).find("#btn_name").val(),
							"url_mobile":nvl($(this).find("#mlink").val(), ""),
							"url_pc":nvl($(this).find("#plink").val(),""),
							"scheme_ios":nvl($(this).find("#ioslink").val(),""),
							"scheme_android":nvl($(this).find("#androidlink").val(), ""),
							"pluginId":""
						};

						btnorder ++;

						btnObj.push(btn);

					});

					//console.log(btnObj);

					$(this).find(".alimlinkbtn").each(function() {
						if($(this).find("#btn_type").val() == "N") {
							   isValid = false;
			                    $(".content").html("바로연결 링크 버튼 타입을 선택 하세요.");
			                    $("#myModal").modal('show');
			                    $('#myModal').on('hidden.bs.modal', function () {
			                    	$(this).find("#btn_type").focus();
			                    });
						} else if(!$(this).find("#btn_name").val()) {
							   isValid = false;
			                    $(".content").html("바로연결 링크 버튼명을 입력 하세요.");
			                    $("#myModal").modal('show');
			                    $('#myModal').on('hidden.bs.modal', function () {
			                    	$(this).find("#btn_name").focus();
			                    });
						}

						var link = {
								"type":$(this).find("#btn_type").val(),
								"name":$(this).find("#btn_name").val(),
								"url_mobile":nvl($(this).find("#mlink").val(), ""),
								"url_pc":nvl($(this).find("#plink").val(),""),
								"scheme_ios":nvl($(this).find("#ioslink").val(),""),
								"scheme_android":nvl($(this).find("#androidlink").val(), "")
							};

						linkObj.push(link);
					});
                }

                //btn_content = JSON.stringify(btn_content);
				$securityFlag = false;

				if($(this).find("input:checkbox[name=securityFlag]").is(":checked") == true) {
					$securityFlag = true;
				}

                var img_temp = $('#image'+i).val().split("\\");
                var img_name = img_temp[img_temp.length-1];
                var img_url = $('#image_url'+i).val();
                
                 var item = {
                    "senderKey": $(this).find('#input_pf_ynm').val(),
                    "templateCode": $(this).find('#input_tmp_code').val(),
                    "templateName": $(this).find('#input_tmp_name').val(),
                    "templateMessageType":$(this).find('#msg_type_id').val(),
                    "templateEmphasizeType":$(this).find('#emphasis_type_id').val(),
                    "templateContent": $(this).find('#input_tmp_cont').val(),
                    "templateExtra": nvl($(this).find('#input_tmp_extra').val(),""),
                    "templateAd": nvl($(this).find('#templateAd').val(),""),
                    "templateImageName":nvl(img_name, ""),
                    "templateImageUrl":nvl(img_url,""),                    
                    "templateTitle": nvl($(this).find('#emphasis_title_id').val(),""),
                    "templateSubtitle": nvl($(this).find('#emphasis_subtitle_id').val(),""),
                    "senderKeyType": $("#senderKeyType").val(),
                    "categoryCode":$(this).find('#categoryCode').val(),
                    "securityFlag":$securityFlag,
                    "buttons": btnObj,
                    "quickReplies":linkObj,
                };
                jsonObj.push(item);

                if (!isValid) {
                    return;
                }

                var jsonString = JSON.stringify(jsonObj);

                var file_data = new FormData();
                file_data.append("<?=$this->security->get_csrf_token_name()?>", csrftoken);
                file_data.append("image_file", $(this).find('#alimimg')[0].files[0]);
                file_data.append("jsonString", jsonString);

                $.ajax({
                    url: '/dhnbiz/template/add_process/',
                    type: "POST",
                    data: file_data,
                    processData: false,
                    contentType: false,
                    beforeSend:function(){
                        $('#overlay').fadeIn();
                    },
                    complete:function(){
                        $('#overlay').fadeOut();
                    },
                    //성공시
                    success: function (json) {
                        var str = json['message'];
                        var success = json['success'];
                        var fail = json['fail'];
                        //console.log(json);
                        show_response(str, success, fail);
                        setTimeout(function(){
                        	location.href="/dhnbiz/template/public_lists";
                        },1000);
                    },
                    //실패시
                    error: function () {
                        $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
                        $("#myModal").modal('show');
                    }
                });


            });

            function show_response(str, success, fail) {
                    text = str;
                    $(".content").html(text );
                    $("#myModal").modal('show');
            }
        }

        function nvl(str, defaultStr){

            if(typeof str == "undefined" || str == null || str == "")
                str = defaultStr ;

            return str ;
        }

        function public_part_lists(col_no) {

            var pf_info = $('#input_pf_ynm' + col_no).val().split('|');
            var input_pf_ynm = pf_info[0];

            $("#input_part_id"+col_no+" option").remove();
            $("#input_part_id"+col_no).append("<option value='none'>분류명 선택</option>");

            if (input_pf_ynm != "none") {
         		$.ajax({
        			url: "/dhnbiz/template/public_part_lists",
        			type: "POST",
        			data: {
        				<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
        				"group_pf_key": input_pf_ynm
        				},
        			beforeSend: function () {
        				$('#overlay').fadeIn();
        			},
        			complete: function () {
        				$('#overlay').fadeOut();
        			},
        			success: function (json) {
        				var code = json['code'];
        				var message = json['message'];
        				var part_total_count = json['part_count'];
        				var part_list = json['part_list'];

        				var tableHTML = "";

    					if (code == "success") {
    						console.log("code :" + code);
    						console.log("part_total_count :" + part_total_count);
    						console.log("part_list :" + part_list);

    						if(part_list.length > 0) {
    							for(var pi = 0; pi < part_list.length; pi++) {
    								$("#input_part_id"+col_no).append("<option value='"+part_list[pi]["tp_part_id"]+"'> "+part_list[pi]["tp_part_name"]+"</option>");
    							}
    						}
    					}
        			},
        			error: function () {
        				$(".content").html("시스템 오류로 처리되지 않았습니다.");
        				$('#myModal').modal({backdrop: 'static'});
        				$(document).unbind("keyup").keyup(function (e) {
        					var code = e.which;
        					if (code == 13) {
        						$(".enter").click();
        					}
        				});
        			}
        		});
            }
        }

        //이미지 등록 선택 클릭시
	function fileChange(input, id){

			if(input.value.length > 0) {

				if(input.files && input.files[0]){

					$("#"+ id).val(""); //input 초기화
					readSharingURL(input, id);
				}
			}

	}

	//이미지 경로 세팅
	function readSharingURL(input, id){
		var fileValue = input.value.split("\\");
		var fileName = fileValue[fileValue.length-1];
		$("#"+ id).val(fileName);
	}

    </script>
