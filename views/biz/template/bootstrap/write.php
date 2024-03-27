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
						<div class="inner_content">
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
					                    <li style="font-weight: bold;"><a href="/uploads/kakao_template_guide.pdf" target="_blank" style="color:red; font-weight: bold;">템플릿 검수 가이드 문서</a> 보러가기 (필독)</li>
				                    </ul>
			                	</div>
			                	<form class="form-horizontal row-border" action="#" name="baseForm" id="baseForm">
		                            <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
		                            <input type='hidden' name='actions' value='template_save' />
                        		</form>
		                        <p class="align-center mt30">
		                            <input type="hidden" name="vi_count_num" id="vi_count_num" value="0">
		                            <a href="javascript:void(0);" onclick="addForm();" class="btn"> <i class="icon-plus"></i> 입력란 추가</a>&nbsp;
		                            <a href="javascript:void(0);" onclick="standard_del();" class="btn"> <i class="icon-minus"></i> 입력란 삭제</a>&nbsp;
		                            <!-- button class="btn" type="button" id="cancel" name="cancel" onclick="location.href='/template/add/'">취소</button-->&nbsp;
		                            <button class="btn btn-primary" type="button" onclick="javascript:register_template()">등록</button>&nbsp;
		                        </p>
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
            col_html += "			<table id='addedFormDiv_|col_no|'>";
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
            col_html += "						<select name='input_pf_ynm' id='input_pf_ynm|col_no|' class=''>";
            col_html += "                               " ;
			col_html += "							<option value='none'>업체명 선택</option>";
			<?	foreach($profile as $r) {?>
				col_html += "							        <option value='<?=$r->spf_key?>|<?=$r->spf_key_type?>'><?=$r->spf_company?>(<?=$r->spf_friend?>)</option>";
				<?}
			} else if($public_flag == 'Y' ) { ?>
            col_html += "						<select name='input_pf_ynm' id='input_pf_ynm|col_no|' class='' onchange='public_part_lists(|col_no|)'>";
            col_html += "                               " ;
			col_html += "							<option value='none'>그룹명 선택</option>";
			<?	foreach($profile_group as $r) {
				?>
				col_html += "							        <option value='<?=$r->spg_pf_key?>|None'><?=$r->spg_name?></option>";
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
            col_html += "						<input name='input_part_id' id='input_part_id|col_no|' value='none'>";
            col_html += "					</td>";
            col_html += "				</tr>";
            <? } else if($public_flag == 'Y' ) { ?>
            col_html += "				<tr>";
            col_html += "					<th>분류명</th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "						<select name='input_part_id' id='input_part_id|col_no|' class=''>";
			col_html += "							<option value='none'>분류명 선택</option>";            
            col_html += "                               " ;
            col_html += "						</select>";
            col_html += "					</td>";
            col_html += "				</tr>";
            <? } ?>
            col_html += "				<tr>";
            col_html += "					<th>템플릿 코드<span class='required'>*</span></th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "						<input id='input_tmp_code|col_no|'  maxlength='30'  class='form-control required input-width-large' type='text' style='ime-mode:disabled;' onkeyup='onOnlyAlphaNumber(this);'>";
            col_html += "					</td>";
            col_html += "				</tr>";
            col_html += "				<tr>";
            col_html += "					<th>템플릿 명<span class='required'>*</span></th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "						<input id='input_tmp_name|col_no|' maxlength='30' class='form-control required input-width-large' type='text'>";
            col_html += "					</td>";
            col_html += "				</tr>";
            col_html += "				<tr>";
            col_html += "					<th>템플릿 내용<span class=\"required\">*</span></th>";
            col_html += "					<td colspan='5' style='text-align:left'>";
            col_html += "                       <textarea name='input_tmp_cont' onkeyup='check_word_count(1000);' id='input_tmp_cont|col_no|' cols='30' rows='5' class='form-control required' placeholder='내용을 입력해주세요.'></textarea>";
            col_html += "						<DIV class=remaining style='color: #8c8c8c'><SPAN class='count'>0</SPAN>/1000</DIV>";
            col_html += "					</td>";
            col_html += "				</tr>";

            col_html += "               <tr id='btn_content_|col_no|'>";
            col_html += "					<th>링크 버튼<br><button class='btn sm' type='button' onclick='javascript:btn_add(|col_no|);'><i class='icon-plus'></i> 추가</button></th>";
            col_html += "					<th align='center' width='10%'>no</th>";
            col_html += "					<th align='center' width='20%'>버튼타입</th>";
            col_html += "					<th align='center' width='20%'>버튼명</th>";
            col_html += "					<th align='center' width='30%'>버튼링크</th>";
            col_html += "					<th align='center' width='10%'></th>";
            col_html += "               </tr>";
            //공통
            col_html += "               <tr id='btn_content_|col_no|_1_1'>";
            col_html += "					<th rowspan='2'></th>";
            col_html += "                   <td id='no|col_no|' align='center' rowspan='2' hidden>1</td>";
            col_html += "                   <td id='btn_type_td|col_no|' name='btn_type_td' align='center' rowspan='2' hidden>";
            col_html += "                       <select class='select2 input-width-medium none-search' id='btn_type|col_no|_1' name='btn_type' onchange=\"btn_type_select('|col_no|',1)\">";
            col_html += "                           <option value='N'>선택</option>";
            col_html += "                           <option value='DS'>배송조회</option>";
            col_html += "                           <option value='WL'>웹링크</option>";
            col_html += "                           <option value='AL'>앱링크</option>";
            col_html += "                           <option value='BK'>봇키워드</option>";
            col_html += "                           <option value='MD'>메시지전달</option>";
            col_html += "                       </select>";
            col_html += "                   </td>";
            col_html += "                   <td id='btn_add_msg|col_no|' align='center' rowspan='2' colspan='5'>버튼을 추가할 수 있습니다.</td>";
            //N
            col_html += "                   <td id='n_1_|col_no|_1' name='n_1_|col_no|' align='center' rowspan='2' hidden></td>";
            col_html += "                   <td id='n_2_|col_no|_1' name='n_1_|col_no|' align='center' width='67px' hidden></td>";
            //DS
            col_html += "                   <td id='ds_1_|col_no|_1' align='center' rowspan='2' hidden><input name='btn_name1' id='btn_name' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
            col_html += "                   <td id='ds_2_|col_no|_1' width='67px' hidden>* 알림톡 메시지 파싱을 통해 배송조회 카카오검색 페이지 링크가 자동 생성 됩니다.<br>* 파싱 지원 택배사 : KG로지스 우체국택배 로젠택배 CJ대한통운 일양로지스 롯데택배 GTX로지스 FedEx 한진택배 경동택배 합동택배</td>";
            //BK
            col_html += "                   <td id='bk_1_|col_no|_1' align='center' rowspan='2' hidden><input name='btn_name4' id='btn_name' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
            col_html += "                   <td id='bk_2_|col_no|_1' align='center' width='67px' hidden></td>";
            //MD
            col_html += "                   <td id='md_1_|col_no|_1' align='center' rowspan='2' hidden><input name='btn_name5' id='btn_name' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
            col_html += "                   <td id='md_2_|col_no|_1' align='center' width='67px' hidden></td>";
            //WL
            col_html += "                   <td id='wl_1_|col_no|_1' align='center' rowspan='2' hidden><input name='btn_name2' id='btn_name' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
            col_html += "                   <td id='wl_2_|col_no|_1' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 30px;'>Mobile</label><input style='margin-left: 30px;' maxlength='255' type='text' value='http://' name='btn_url21' id='wl_2_btn_url_|col_no|_1' class='form-control input-width-medium inline'></td>";
            //AL
            col_html += "                   <td id='al_1_|col_no|_1' align='center' rowspan='2' hidden><input name='btn_name3' id='btn_name' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
            col_html += "                   <td id='al_2_|col_no|_1' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 22px;'>Android</label><input style='margin-left: 30px;' maxlength='255' type='text' name='btn_url31' id='al_2_btn_url_|col_no|_1' class='form-control input-width-medium inline'></td>";
            //공통
            col_html += "                   <td id='btn_del|col_no|' align='center' rowspan='2' hidden><button class='btn btn-primary' type='button' style='margin-top: 5px; padding: 3px 5px;' onclick='javascript:btn_del(|col_no|,1);'><i class='icon-trash'></i> 제거</button></td>";
            col_html += "               </tr>";
            col_html += "               <tr id='btn_content_|col_no|_1' class='1'>";
            col_html += "                   <td id='wl_3_|col_no|_1' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 26px;'>PC(선택)</label><input style='margin-left: 29px;' maxlength='255' type='text' name='btn_url22' id='wl_3_btn_url_|col_no|_1' class='form-control input-width-medium inline'></td>";
            col_html += "                   <td id='al_3_|col_no|_1' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 50px;'>iOS</label><input style='margin-left: 30px;' maxlength='255' type='text' name='btn_url32' id='al_3_btn_url_|col_no|_1' class='form-control input-width-medium inline'></td>";
            col_html += "               </tr>";

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

        function btn_type_select(cno,obj) {
            var btn_type=$("#btn_type"+cno + "_" + obj).val();

            $("#n_1_" + cno + "_" + obj).attr("hidden", true);
            $("#n_2_" + cno + "_" + obj).attr("hidden", true);
            $("#ds_1_"+cno + "_" + obj).attr("hidden",true);
            $("#ds_2_"+cno + "_" + obj).attr("hidden",true);
            $("#wl_1_"+cno + "_" + obj).attr("hidden",true);
            $("#wl_2_"+cno + "_" + obj).attr("hidden",true);
            $("#wl_3_"+cno + "_" + obj).attr("hidden",true);
            $("#al_1_"+cno + "_" + obj).attr("hidden",true);
            $("#al_2_"+cno + "_" + obj).attr("hidden",true);
            $("#al_3_"+cno + "_" + obj).attr("hidden",true);
            $("#bk_1_"+cno + "_" + obj).attr("hidden",true);
            $("#bk_2_"+cno + "_" + obj).attr("hidden",true);
            $("#md_1_"+cno + "_" + obj).attr("hidden",true);
            $("#md_2_"+cno + "_" + obj).attr("hidden",true);

            if (btn_type == "N") {
                $("#n_1_" + cno + "_" + obj).attr("hidden", false);
                $("#n_2_" + cno + "_" + obj).attr("hidden", false);
            } else if (btn_type == "DS") {
                $("#ds_1_" + cno + "_" + obj).attr("hidden", false);
                $("#ds_2_" + cno + "_" + obj).attr("hidden", false);
            } else if (btn_type == "WL") {
                $("#wl_1_" + cno + "_" + obj).attr("hidden", false);
                $("#wl_2_" + cno + "_" + obj).attr("hidden", false);
                $("#wl_3_" + cno + "_" + obj).attr("hidden", false);
                $(".content").html("현재 카카오 정책상 알림톡 메시지는 PC에서 메시지내용이 숨김 처리되어 PC버전으로 버튼이 보이지 않습니다.<br><br>향후 카카오 정책 변경으로 인한(미정) 템플릿 수정의 번거로움을 덜기 위하여 <b>PC 버튼링크</b>를 선택적으로 입력이 가능합니다.");
                $("#myModal").modal('show');
            } else if (btn_type == "AL") {
                $("#al_1_" + cno + "_" + obj).attr("hidden", false);
                $("#al_2_" + cno + "_" + obj).attr("hidden", false);
                $("#al_3_" + cno + "_" + obj).attr("hidden", false);
            } else if (btn_type == "BK") {
                $("#bk_1_" + cno + "_" + obj).attr("hidden", false);
                $("#bk_2_" + cno + "_" + obj).attr("hidden", false);
            } else if (btn_type == "MD") {
                $("#md_1_" + cno + "_" + obj).attr("hidden", false);
                $("#md_2_" + cno + "_" + obj).attr("hidden", false);
            }
        }

        function btn_add(obj) {
            var lastItemNo = $("#addedFormDiv_"+obj+" tr:last").attr("class");
            var tr_length = $("#addedFormDiv_"+obj+" tr").length;
            var current_btn_num = lastItemNo;
            var add_btn_num = Number(current_btn_num)+1;

            if(tr_length == 8 && $("#no" + obj).is(":hidden") == true) {
                $("#no" + obj).attr("hidden", false);
                $("#btn_type_td" + obj).attr("hidden", false);
                $("#btn_del" + obj).attr("hidden", false);
                $("#btn_add_msg" + obj).attr("hidden", true);
                $("td[name=n_1_"+obj+"]").attr("hidden", false);
                $("td[name=n_2_"+obj+"]").attr("hidden", false);
            } else if (tr_length < 16) { // 업체명,템플릿코드,템플릿명,템플릿내용,버튼5개 = 9
                var btn_tr = tr_length-4;
                var no = btn_tr/2;
                var col_html = "";
                //공통
                col_html += "               <tr id='btn_content_" + obj + "_" + add_btn_num + "_1'>";
                col_html += "					<th rowspan='2'></th>";
                col_html += "                   <td id='no" + obj + "' align='center' rowspan='2'>"+no+"</td>";
                col_html += "                   <td id='btn_type_td" + obj + "' name='btn_type_td' align='center' rowspan='2'>";
                col_html += "                       <select class='select2 none-search input-width-medium' id='btn_type" + obj + "_" + add_btn_num +"' name='btn_type' onchange=\"btn_type_select(" + obj + "," + add_btn_num + ")\">";
                col_html += "                           <option value='N'>선택</option>";
                col_html += "                           <option value='DS'>배송조회</option>";
                col_html += "                           <option value='WL'>웹링크</option>";
                col_html += "                           <option value='AL'>앱링크</option>";
                col_html += "                           <option value='BK'>봇키워드</option>";
                col_html += "                           <option value='MD'>메시지전달</option>";
                col_html += "                       </select>";
                col_html += "                   </td>";
                col_html += "                   <td id='btn_add_msg" + obj + "' align='center' rowspan='2' colspan='5' hidden>버튼을 추가할 수 있습니다.</td>";
                //N
                col_html += "                   <td id='n_1_" + obj + "_" + add_btn_num +"' name='n_1_" + obj + "' align='center' rowspan='2'></td>";
                col_html += "                   <td id='n_2_" + obj + "_" + add_btn_num +"' name='n_1_" + obj + "' align='center' width='67px'></td>";
                //DS
                col_html += "                   <td id='ds_1_" + obj + "_" + add_btn_num +"' align='center' rowspan='2' hidden><input name='btn_name1' id='ds_1_btn_name_" + obj + "_" + add_btn_num +"' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='ds_2_" + obj + "_" + add_btn_num +"' width='67px' hidden>* 알림톡 메시지 파싱을 통해 배송조회 카카오검색 페이지 링크가 자동 생성 됩니다.<br>* 파싱 지원 택배사 : KG로지스 우체국택배 로젠택배 CJ대한통운 일양로지스 롯데택배 GTX로지스 FedEx 한진택배 경동택배 합동택배</td>";
                //BK
                col_html += "                   <td id='bk_1_" + obj + "_" + add_btn_num +"' align='center' rowspan='2' hidden><input name='btn_name4' id='bk_1_btn_name_" + obj + "_" + add_btn_num +"' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='bk_2_" + obj + "_" + add_btn_num +"' align='center' width='67px' hidden></td>";
                //MD
                col_html += "                   <td id='md_1_" + obj + "_" + add_btn_num +"' align='center' rowspan='2' hidden><input name='btn_name5' id='md_1_btn_name_" + obj + "_" + add_btn_num +"' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='md_2_" + obj + "_" + add_btn_num +"' align='center' width='67px' hidden></td>";
                //WL
                col_html += "                   <td id='wl_1_" + obj + "_" + add_btn_num + "' align='center' rowspan='2' hidden><input name='btn_name2' id='wl_1_btn_name_" + obj + "_" + add_btn_num +"' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='wl_2_" + obj + "_" + add_btn_num + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 30px;'>Mobile</label><input style='margin-left: 30px;' maxlength='255' type='text' value='http://' name='btn_url21' id='wl_2_btn_url_" + obj + "_" + add_btn_num +"' class='form-control input-width-medium inline'></td>";
                //AL
                col_html += "                   <td id='al_1_" + obj + "_" + add_btn_num + "' align='center' rowspan='2' hidden><input name='btn_name3' id='al_1_btn_name_" + obj + "_" + add_btn_num +"' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='al_2_" + obj + "_" + add_btn_num + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 22px;'>Android</label><input style='margin-left: 30px;' maxlength='255' type='text' name='btn_url31' id='al_2_btn_url_" + obj + "_" + add_btn_num +"' class='form-control input-width-medium inline'></td>";
                //공통
                col_html += "                   <td id='btn_del" + obj + "' align='center' rowspan='2'><button class='btn btn-primary' type='button' style='margin-top: 5px; padding: 3px 5px;' onclick='javascript:btn_del(" + obj + "," + add_btn_num + ");'><i class='icon-trash'></i> 제거</button></td>";
                col_html += "               </tr>";
                col_html += "               <tr id='btn_content_" + obj + "_" + add_btn_num + "' class='" + add_btn_num + "'>";
                col_html += "                   <td id='wl_3_" + obj + "_" + add_btn_num + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 26px;'>PC(선택)</label><input style='margin-left: 29px;' maxlength='255' type='text' name='btn_url22' id='wl_3_btn_url_" + obj + "_" + add_btn_num + "' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='al_3_" + obj + "_" + add_btn_num + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 50px;'>iOS</label><input style='margin-left: 30px;' maxlength='255' type='text' name='btn_url32' id='al_3_btn_url_" + obj + "_" + add_btn_num + "' class='form-control input-width-medium inline'></td>";
                col_html += "               </tr>";

                $("#btn_content_" + obj + "_" + current_btn_num).after(col_html);
                $('td').css('border-left','1px solid #dedede').css('border-right','1px solid #dedede').css('border-top','1px solid #dedede');
                $('select.none-search').select2({
                    minimumResultsForSearch: Infinity
                });
            }
        }

        function btn_del(obj1,obj2){
            var tr_length = $("#addedFormDiv_"+obj1+" tr").length;
            if(tr_length < 9) {
                $("#n_1_" + obj1 + "_" + obj2).attr("hidden", true);
                $("#n_2_" + obj1 + "_" + obj2).attr("hidden", true);
                $("#ds_1_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#ds_2_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#wl_1_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#wl_2_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#wl_3_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#al_1_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#al_2_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#al_3_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#bk_1_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#bk_2_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#md_1_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#md_2_"+ obj1 + "_" + obj2).attr("hidden", true);
                $("#btn_type" + obj1 + "_" + obj2 + " option:eq(0)").prop("selected", true);
                $("#btn_type" + obj1 + "_" + obj2).select2("val", "N");

                $("#addedFormDiv_"+obj1).find(document.getElementsByName("btn_name1")).val("");
                $("#addedFormDiv_"+obj1).find(document.getElementsByName("btn_name2")).val("");
                $("#addedFormDiv_"+obj1).find(document.getElementsByName("btn_name3")).val("");
                $("#addedFormDiv_"+obj1).find(document.getElementsByName("btn_name4")).val("");
                $("#addedFormDiv_"+obj1).find(document.getElementsByName("btn_name5")).val("");
                $("#addedFormDiv_"+obj1).find(document.getElementsByName("btn_url21")).val("http://");
                $("#addedFormDiv_"+obj1).find(document.getElementsByName("btn_url22")).val("");
                $("#addedFormDiv_"+obj1).find(document.getElementsByName("btn_url31")).val("");
                $("#addedFormDiv_"+obj1).find(document.getElementsByName("btn_url32")).val("");

                $("#no" + obj1).attr("hidden", true);
                $("#btn_type_td" + obj1).attr("hidden", true);
                $("#btn_del" + obj1).attr("hidden", true);
                $("#btn_add_msg" + obj1).attr("hidden", false);
            } else {
                $("#btn_content_" + obj1 + "_" + obj2).remove();
                $("#btn_content_" + obj1 + "_" + obj2 + "_1").remove();
                var no = 1;
                $("#addedFormDiv_" + obj1 + " tr").find("#no" + obj1).each(function () {
                    $(this).text(no);
                    no++;
                });
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
            var jsonObj = [];
            var count = ($("#vi_count_num").val()*1);
            var csrftoken = '<?=$this->security->get_csrf_hash()?>';
            var isValid = true;

            for (var i = 1; i <= count; i++) {
                var cont = $('#input_tmp_cont' + i).val();
                if ($('#input_pf_ynm'+i).val() == "none") {
                    isValid = false;
                    <? if($public_flag == 'N' ) { ?>
                    $(".content").html("업체명을 선택해주세요.");
                    <? } else if($public_flag == 'Y' ) {?>
                    $(".content").html("그룹명을 선택해주세요.");
                    <? } ?>
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#input_pf_ynm'+i).focus();
                    });
                } else if (!$('#input_tmp_code'+i).val()) {
                    isValid = false;
                    $(".content").html("템플릿 코드를 입력해주세요.");
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#input_tmp_code'+i).focus();
                    });
                } else if (!$('#input_tmp_name'+i).val()) {
                    isValid = false;
                    $(".content").html("템플릿 명을 입력해주세요.");
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#input_tmp_name'+i).focus();
                    });
                } else if (!$('#input_tmp_cont'+i).val()) {
                    isValid = false;
                    $(".content").html("템플릿 내용을 입력해주세요.");
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#input_tmp_cont' + i).focus();
                    });
                } else {
                    $("#addedFormDiv_"+i+" tr").each(function () {
                        var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
                        if (btn_type != undefined) {
                            var focus;
                            if (btn_type == "N" && $(this).find(document.getElementsByName("btn_type")).is(":hidden") == false) {
                                isValid = false;
                                $(".content").html("버튼타입을 선택해주세요.");
                                $("#myModal").modal('show');
                            } else if (btn_type == "DS" && $(this).find(document.getElementsByName("btn_name1")).val().trim() == "") {
                                isValid = false;
                                focus = $(this).find(document.getElementsByName("btn_name1"));
                                $(".content").html("배송조회 버튼의 버튼명을 입력해주세요.");
                                $("#myModal").modal('show');
                                $('#myModal').on('hidden.bs.modal', function () {
                                    focus.focus();
                                });
                            } else if (btn_type == "WL") {
                                if($(this).find(document.getElementsByName("btn_name2")).val().trim() == "") {
                                    isValid = false;
                                    focus = $(this).find(document.getElementsByName("btn_name2"));
                                    $(".content").html("웹링크 버튼의 버튼명을 입력해주세요.");
                                    $("#myModal").modal('show');
                                    $('#myModal').on('hidden.bs.modal', function () {
                                        focus.focus();
                                    });
                                } else if ($(this).find(document.getElementsByName("btn_url21")).val().trim() == "" || $(this).find(document.getElementsByName("btn_url21")).val().trim() == "http://") {
                                    isValid = false;
                                    focus = $(this).find(document.getElementsByName("btn_url21"));
                                    $(".content").html("웹링크 버튼의 Mobile 버튼링크를 입력해주세요.");
                                    $("#myModal").modal('show');
                                    $('#myModal').on('hidden.bs.modal', function () {
                                        focus.focus();
                                    });
                                }
                            } else if (btn_type == "AL") {
                                if($(this).find(document.getElementsByName("btn_name3")).val().replace(/ /gi, "") == "") {
                                    isValid = false;
                                    focus = $(this).find(document.getElementsByName("btn_name3"));
                                    $(".content").html("앱링크 버튼의 버튼명을 입력해주세요.");
                                    $("#myModal").modal('show');
                                    $('#myModal').on('hidden.bs.modal', function () {
                                        focus.focus();
                                    });
                                } else if ($(this).find(document.getElementsByName("btn_url31")).val().replace(/ /gi, "") == "") {
                                    isValid = false;
                                    focus = $(this).find(document.getElementsByName("btn_url31"));
                                    $(".content").html("앱링크 버튼의 Android 버튼링크를 입력해주세요.");
                                    $("#myModal").modal('show');
                                    $('#myModal').on('hidden.bs.modal', function () {
                                        focus.focus();
                                    });
                                } else if ($(this).next('tr').find(document.getElementsByName("btn_url32")).val().replace(/ /gi, "") == "") {
                                    isValid = false;
                                    focus = $(this).parent().find(document.getElementsByName("btn_url32"));
                                    $(".content").html("앱링크 버튼의 iOS 버튼링크를 입력해주세요.");
                                    $("#myModal").modal('show');
                                    $('#myModal').on('hidden.bs.modal', function () {
                                        focus.focus();
                                    });
                                }
                            } else if (btn_type == "BK" && $(this).find(document.getElementsByName("btn_name4")).val().replace(/ /gi, "") == "") {
                                    isValid = false;
                                    focus = $(this).find(document.getElementsByName("btn_name4"));
                                    $(".content").html("봇키워드 링크 버튼의 버튼명을 입력해주세요.");
                                    $("#myModal").modal('show');
                                    $('#myModal').on('hidden.bs.modal', function () {
                                        focus.focus();
                                    });
                            } else if (btn_type == "MD" && $(this).find(document.getElementsByName("btn_name5")).val().replace(/ /gi, "") == "") {
                                    isValid = false;
                                    focus = $(this).find(document.getElementsByName("btn_name5"));
                                    $(".content").html("메시지전달 링크 버튼의 버튼명을 입력해주세요.");
                                    $("#myModal").modal('show');
                                    $('#myModal').on('hidden.bs.modal', function () {
                                        focus.focus();
                                    });
                            }
                        }
                    });
                    var pf_info = $('#input_pf_ynm' + i).val().split('|');
                    var pf_key = pf_info[0];
                    var pf_type = pf_info[1];
                    if (pf_type == '' || pf_type == null) {
                        pf_type = 'S'
                    }
					var pt_part_id = $('#input_part_id' + i).val();
                    
                    var tmp_code = $('#input_tmp_code' + i).val();
                    var tmp_name = $('#input_tmp_name' + i).val();
                    var tmp_cont = $('#input_tmp_cont' + i).val();
                    var sms_cont = $('#input_sms_cont' + i).val();
                    var btn_type = $('#btn_type' + i).val();

                    var btn_content = new Array();
                    var btnInfo = "";
                    var ordering = 1;
                    $("#addedFormDiv_"+i+" tr").each(function () {
                        var btn_type = $(this).find(document.getElementsByName("btn_type")).val();
                        if (btn_type != undefined) {
                            if (btn_type == "DS") {
                                btnInfo = new Object();
                                btnInfo.ordering = ordering;
                                btnInfo.linkType = btn_type;
                                btnInfo.name = $(this).find(document.getElementsByName("btn_name1")).val();
                                btn_content.push(btnInfo);
                                ordering++;
                            } else if (btn_type == "WL") {
                                btnInfo = new Object();
                                btnInfo.ordering = ordering;
                                btnInfo.linkType = btn_type;
                                btnInfo.name = $(this).find(document.getElementsByName("btn_name2")).val();
                                btnInfo.linkMo = $(this).find(document.getElementsByName("btn_url21")).val();
                                btnInfo.linkPc = $(this).next('tr').find(document.getElementsByName("btn_url22")).val();
                                btn_content.push(btnInfo);
                                ordering++;
                            } else if (btn_type == "AL") {
                                btnInfo = new Object();
                                btnInfo.ordering = ordering;
                                btnInfo.linkType = btn_type;
                                btnInfo.name = $(this).find(document.getElementsByName("btn_name3")).val();
                                btnInfo.linkAnd = $(this).find(document.getElementsByName("btn_url31")).val();
                                btnInfo.linkIos = $(this).next('tr').find(document.getElementsByName("btn_url32")).val();
                                btn_content.push(btnInfo);
                                ordering++;
                            } else if (btn_type == "BK") {
                                btnInfo = new Object();
                                btnInfo.ordering = ordering;
                                btnInfo.linkType = btn_type;
                                btnInfo.name = $(this).find(document.getElementsByName("btn_name4")).val();
                                btn_content.push(btnInfo);
                                ordering++;
                            } else if (btn_type == "MD") {
                                btnInfo = new Object();
                                btnInfo.ordering = ordering;
                                btnInfo.linkType = btn_type;
                                btnInfo.name = $(this).find(document.getElementsByName("btn_name5")).val();
                                btn_content.push(btnInfo);
                                ordering++;
                            }
                        }
                    });
                }

                //btn_content = JSON.stringify(btn_content);

                var item = {
                    "senderKey": pf_key,
                    "senderKeyType": pf_type,
                    "publicTemplatePart": pt_part_id,
                    "templateCode": tmp_code,
                    "templateName": tmp_name,
                    "templateContent": tmp_cont,
                    "buttons": btn_content,
                    "smsContent": sms_cont
                };
                jsonObj.push(item);
            }

            if (!isValid) {
                return;
            }

            var jsonString = JSON.stringify(jsonObj);
            $.ajax({
                url: '/biz/template/add_process/',
                type: "POST",
                data: {
                    <?=$this->security->get_csrf_token_name()?>: csrftoken,
                    jsonString: jsonString,
                },
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
                    show_response(str, success, fail);
                },
                //실패시
                error: function () {
                    $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
                    $("#myModal").modal('show');
                }
            });

            function show_response(str, success, fail) {
                if (jsonObj.length > 1 || str == null) {
                    if (success > 0) {
                        (function (i, s, o, g, r, a, m) {
                            i['GoogleAnalyticsObject'] = r;
                            i[r] = i[r] || function () {
                                        (i[r].q = i[r].q || []).push(arguments)
                                    }, i[r].l = 1 * new Date();
                            a = s.createElement(o),
                                    m = s.getElementsByTagName(o)[0];
                            a.async = 1;
                            a.src = g;
                            m.parentNode.insertBefore(a, m)
                        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

                        ga('create', 'UA-29375674-2', 'auto');
                        ga('send', 'event', '템플릿 등록', '<?=$this->member->item('mem_username')?>', '(not set)', success);
                    }

                    text = '템플릿 등록을 요청하였습니다.' + '\n' + '[요청결과] 성공 : ' + success + '건, ' + '실패 :' + fail + '건' +
                            '\n\n' + '템플릿 목록에서 등록 상태인 템플릿은 검수요청을 진행해주세요.';
                    $(".content").html(text.replace(/\n/g, "<br/>"));
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                        window.location.href = "/biz/template/write";
                    });
                }
                else if (jsonObj.length == 1 || str != null) {
                    text = str;
                    $(".content").html(text.replace(/\n/g, "<br/>"));
                    $("#myModal").modal('show');
                }
            }
        }

        function public_part_lists(col_no) {
            
            var pf_info = $('#input_pf_ynm' + col_no).val().split('|');
            var input_pf_ynm = pf_info[0];
            
            $("#input_part_id"+col_no+" option").remove();
            $("#input_part_id"+col_no).append("<option value='none'>분류명 선택</option>");

            if (input_pf_ynm != "none") {
         		$.ajax({
        			url: "/biz/template/public_part_lists",
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
        
    </script>
