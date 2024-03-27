<? 
$member_id = $this->member->item('mem_id');
if ($rs->tpl_mem_id==1 && $member_id == 3) {
    $member_id = 1;
}
?>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                    <div>
                        <p align="right">
                            <br/><br/>
                            <button type="button" class="btn btn-primary modal-close" data-dismiss="modal">확인</button>
                        </p>
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
<!-- 컨텐츠 전체 영역 -->
<script type="text/javascript">
function public_temp() {
    var temp_id = $("#tmpl_id").val();
    //alert(temp_id);
    $.ajax({
        url: "/biz/template/public_temp/",
        type: "POST",
        data: {
            <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
            		'temp_id': temp_id 
        },
        //성공시
        success: function (json) {

            if (json['success'] > '0') {
                //var text = '템플릿을 수정하였습니다.'+'\n'+'[요청결과] 성공 : ' + json['success'] + '건, ' + '실패 :' + json['fail'] + '건';
                var text = '템플릿을 공용으로 설정 하였습니다.';
                $(".content").html(text.replace(/\n/g, "<br/>"));
                $("#myModal").modal('show');
            }
            else if (json['success'] == '0') {
                //var text = '템플릿 수정이 실패하였습니다.'+'\n'+'[요청결과] 성공 : ' + json['success'] + '건, ' + '실패 :' + json['fail'] + '건';
                var text = '공용으로 설정 하지 못했습니다.';
                $(".content").html(text.replace(/\n/g, "<br/>"));
                $("#myModal").modal('show');
                return;
            } 
        },
        //실패시
        error: function (xhr, errmsg, err) {
            $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
            $("#myModal").modal('show');
        }
    });            
}
</script> 
<form action="/biz/template/view/<?=$rs->tpl_id?>" method="get" id="mainForm">
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_tit">
							<div class="fr" id="duration">
                                <button class="btn md" id="_public_temp"  onclick="public_temp()" style="display:none">공용으로 설정</button>
                            </div>
							<h3>템플릿 상세정보</h3>
						</div>
						<div class="inner_content">
							<div class="input_content_wrap">
								<label class="input_tit">업체명</label>
								<div class="input_content">
									<?=$rs->tpl_company?>(<?=$rs->spf_friend?>)
								</div>
							</div>
							<input type="hidden" id="tmpl_id" value="<?=$rs->tpl_id?>">
                            <input type="hidden" id="pf_key" value="<?=$rs->tpl_profile_key?>">
                            <input type="hidden" id="pf_type" value="<?=$rs->spf_key_type?>">
							<div class="input_content_wrap">
								<label class="input_tit">템플릿 코드</label>
								<div class="input_content">
									<?if($rs->tpl_mem_id==$member_id && ($rs->tpl_inspect_status=="REG" || $rs->tpl_inspect_status=="REJ")) {?>
									<input id=input_tmp_code name=input_tmp_code type="text" class="form-control input-width-xlarge" maxlength="30" onkeyup="onOnlyAlphaNumber(this);" value="<?=$rs->tpl_code?>">
									<?} else {?>
									<input id="input_tmp_code" type="hidden" value="<?=$rs->tpl_code?>"><?=$rs->tpl_code?>
									<?}?>
									
								</div>
							</div>
							<div class="input_content_wrap" id="addedFormDiv">
								<label class="input_tit">템플릿 명</label>
								<div class="input_content">
									<?if($rs->tpl_mem_id==$member_id && ($rs->tpl_inspect_status=="REG" || $rs->tpl_inspect_status=="REJ")) {?>
									<input id=input_tmp_name name=input_tmp_code type="text" class="form-control input-width-xlarge" maxlength="30" value="<?=$rs->tpl_name?>">
									<?} else {?>
										<?=$rs->tpl_name?>
									<?}?>
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">등록일</label>
								<div class="input_content">
									<?=$rs->tpl_datetime?>
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">검수 요청일</label>
								<div class="input_content">
									<?=$rs->tpl_check_datetime?>
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">검수일</label>
								<div class="input_content">
									<?=$rs->tpl_appr_datetime?>
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">검수상태</label>
								<div class="input_content">
									<input type="hidden" id="inspect_status" value="REG">
									<?=$this->funn->setColorLabel($rs->tpl_inspect_status)?>
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">템플릿 상태</label>
								<div class="input_content">
									<input type="hidden" id="tmpl_status" value="R">
									<?=$this->funn->setColorLabel($rs->tpl_status)?>
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">문의상태</label>
								<div class="input_content">
									<?=$this->funn->setColorLabel($rs->tpl_comment_status)?>
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">탬플릿 내용</label>
								<div class="input_content">
									<?if($rs->tpl_mem_id==$member_id && ($rs->tpl_inspect_status=="REG" || $rs->tpl_inspect_status=="REJ")) {?> 
                                    <textarea id="input_tmp_cont" rows="6" cols="5" maxlength="1000"
                                              name="textarea" onkeyup="check_word_count(1000);"
                                              class="form-control"><?=$rs->tpl_contents?></textarea>
                                    <div class=remaining style="color: #8c8c8c"><span class="count">0</span>/1000</div>
									<?} else {?>
									<input id="input_tmp_cont" type="hidden"
										   value="<?=str_replace("\"", "'",str_replace("\n", "<br>", $rs->tpl_contents))?>">
									<script>
										var cont = $("#input_tmp_cont").val();
										
										var str = /#{+[^#{]+}+/g;
										var cont_crt = cont.replace(str, '<span style="background-color:yellow;">$&</span>');
										document.write(cont_crt);
									</script>
									<?}?>
								</div>
							</div>
							<div class="input_content_wrap">
								<label class="input_tit">버튼</label>
								<div class="input_content">
									<? if($rs->tpl_button=="" || $rs->tpl_button=="[]") { ?>
									-
									<? } else {?>
									<!-- div class="btn_link_box">
										<span>웹링크</span><span>vip쿠폰받기</span>
									</div>
									<div class="btn_link_box">
										<span>URL</span><span>http://#{url}</span>
									</div-->
                                    <table class="tpl_ver_form" width="100%" border="1">
                                        <colgroup>
                                            <col width="200">
                                            <col width="*">
                                        </colgroup>
                                        <tbody>
                                        
                                        <tr id="btn_content_0">
        										<?if($rs->tpl_mem_id==$member_id && ($rs->tpl_inspect_status=="REG" || $rs->tpl_inspect_status=="REJ")) {?>                                    
                                                <th style="vertical-align: middle !important;" rowspan="12">버튼 타입
                                                    <button class='btn btn-primary' type='button'
                                                            style='margin-top: 7px; padding: 3px 5px;' onclick='btn_add();'><i
                                                            class='icon-plus'></i> 추가
                                                    </button>
        										<?}?>      
                                                <th align="center" width='10%'>no</th>
                                                <th align="center" width='20%'>버튼타입</th>
                                                <th align="center" width='20%'>버튼명</th>
                                                <th align="center" width='30%'>버튼링크</th>
        										<?if($rs->tpl_mem_id==$member_id && ($rs->tpl_inspect_status=="REG" || $rs->tpl_inspect_status=="REJ")) {?>                                    
                                                <th align="center" width='10%'></th>
        										<?}?>     
                                        </tr>
                                        </tbody>
                                    </table>
                                    <? } ?>									
								</div>							</div>
							<div class="input_content_wrap">
								<label class="input_tit"></label>
								<div class="input_content">
								<p class="noted">승인된 템플릿은 수정/삭제가 불가합니다.</p>
								</div>
							</div>
							<div class="input_content_wrap">
								<div class="input_content">
                                    <p class="align-center mt20">
        							<? if(($rs->tpl_mem_id==$member_id) && ($rs->tpl_inspect_status=="REG" || $rs->tpl_inspect_status=="REJ")) {?>
                                            <button class="btn" type="button"
                                                    onclick=delete_template("<?=$rs->tpl_code?>")>삭제
                                            </button>&nbsp;
                                            <button class="btn btn-custom" type="button"
                                                    onclick=modify_template("<?=$rs->tpl_code?>")>수정
                                            </button>&nbsp;
        							<?}else if($this->member->item('mem_level')>=100) {?>
                                           <button class="btn" type="button"
                                                    onclick=hide_template("<?=$rs->tpl_code?>")>숨기기
                                            </button>&nbsp;
         							<?}?>
         							</p>
 								<!-- button class="btn md">숨기기</button-->
								</div>
							</div>
						</div>
					</div>
					
					<div class="form_section">
						<div class="inner_tit">
							<h3>검수결과</h3>
						</div>
						<div class="inner_content">
							<div class="widget-content">
		                        <table>
		                            <colgroup>
		                                <col width="10%">
		                                <col width="20%">
		                                <col width="5%">
		                                <col width="*">
		                            </colgroup>
		                            <thead>
		                            <tr>
		                                <th class="text-center" style="vertical-align:middle;">작성자</th>
		                                <th class="text-center" style="vertical-align:middle;">등록시간</th>
		                                <th class="text-center" style="vertical-align:middle;">상태</th>
		                                <th class="text-center" style="vertical-align:middle;">결과 및 요청사항</th>
		                            </tr>
		                            </thead>
		                            <tbody>
		<? if($tpl_status->data->comments) { foreach($tpl_status->data->comments as $stat) {?>                            
									<tr>
										<td class="text-center" style="vertical-align:middle;"><?=$stat->userName?></td>
										<td class="text-center" style="vertical-align:middle;"><?=$stat->createdAt?></td>
										<td class="align-center" style="vertical-align:middle;"><?=$this->funn->get_inspect_status_name($stat->status)?></td>
										<td><p><?=$stat->content?></p></td>
									</tr>                                
		<?} }?>
		                            </tbody>
		                        </table>
                    		</div>
						</div>
						<form action="#">
						<div class="inner_content">
							<div class="widget-content">
	                            <table class="tpl_ver_form" width="100%">
	                                <colgroup>
	                                    <col width="200">
	                                    <col width="*">
	                                </colgroup>
	                                <tbody>
	                                </tr>
	                                <th class="align-center" style="vertical-align: middle">결과 및 요청사항</th>
	                                <td>
	                                    <div class="flLeft" style="width:91%">
		                                    <textarea id=comment_content rows="2" cols="3" name="textarea" maxlength="1999" class="form-control" style="cursor: default;" <? if(($rs->tpl_inspect_status == 'REQ' || $rs->tpl_inspect_status == 'REJ' || $rs->tpl_inspect_status == 'APR') && $rs->tpl_status=="R" ) { } else { echo "disabled";} ?>></textarea>
	                                    </div>
	                                    <div class="flRight" style="width:8%"> &nbsp;<input class="btn btn-custom" type="button" value="등록" style="height:49px;" onclick="javascript:comment_add()">
	                                    </div>
	                                </td>
	                                </tr>
	                                </tbody>
	                            </table>
                        	</div>
						</div>
						</form>
					</div>					
				</div>

<!--
    <div class="content_wrap">
        <div class="row">
            <div class="col-xs-12">
                    <div class="widget">      
                        <div class="widget-content">
                            <table class="tpl_ver_form" width="100%" border="1">
                                <colgroup>
                                    <col width="200">
                                    <col width="*">
                                </colgroup>
                                <tbody>
                                
                                <tr id="btn_content_0">
                                <?if($rs->tpl_mem_id==$member_id && ($rs->tpl_inspect_status=="REG" || $rs->tpl_inspect_status=="REJ")) {?>                                    
                                        <th style="vertical-align: middle !important;" rowspan="12">버튼 타입
                                            <button class='btn btn-primary' type='button'
                                                    style='margin-top: 7px; padding: 3px 5px;' onclick='btn_add();'><i
                                                    class='icon-plus'></i> 추가
                                            </button>
											<?}?>      
                                        <th align="center" width='10%'>no</th>
                                        <th align="center" width='20%'>버튼타입</th>
                                        <th align="center" width='20%'>버튼명</th>
                                        <th align="center" width='30%'>버튼링크</th>
											<?if($rs->tpl_mem_id==$member_id && ($rs->tpl_inspect_status=="REG" || $rs->tpl_inspect_status=="REJ")) {?>                                    
                                        <th align="center" width='10%'></th>
<?}?>     
                                </tr>
                                </tbody>
                            </table>
                            <p class="align-center mt20">
<?if($rs->tpl_mem_id==$member_id && ($rs->tpl_inspect_status=="REG" || $rs->tpl_inspect_status=="REJ")) {?>
                                    <button class="btn" type="button"
                                            onclick=delete_template("<?=$rs->tpl_code?>")>삭제
                                    </button>&nbsp;
                                    <button class="btn btn-custom" type="button"
                                            onclick=modify_template("<?=$rs->tpl_code?>")>수정
                                    </button>&nbsp;
<?}else if($this->member->item('mem_level')>=100) {?>
                                   <button class="btn" type="button"
                                            onclick=hide_template("<?=$rs->tpl_code?>")>숨기기
                                    </button>&nbsp;
 <?}?>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        -->
</form>



    <script type="text/javascript">
        $("#nav li.nav30").addClass("current open");

        $(document).unbind("keyup").keyup(function (e) {
            var code = e.which;
            if (code == 13) {
                $(".modal-close").click();
            }
        });

        $(document).ready(function () {
            check_word_count(1000);
            check_word_count2(10);
            show_btn_name_url();
        });

        function show_btn_name_url() {
			<?if($rs->tpl_mem_id==$member_id && ($rs->tpl_inspect_status=="REG" || $rs->tpl_inspect_status=="REJ")) {
				if($rs->tpl_button=="" || $rs->tpl_button=="[]") {
		    ?>
				
                 //버튼이 없는경우
                    var counter = 1;
                    var html = "";
                    //공통
                    html += "<tr id='btn_content_" + counter + "_1'>";
                    html += "<td id='no' align='center' rowspan='2' hidden>" + counter + "</td>";
                    html += "<td id='btn_type_td' name='btn_type_td' align='center' rowspan='2' hidden>";
                    html += "<select class='select2 input-width-medium none-search' id='btn_type" + counter + "' name='btn_type' onchange='modify_btn_type(" + counter + ");'>";
                    html += "<option value='N'>선택</option>";
                    html += "<option value='DS'>배송조회</option>";
                    html += "<option value='WL'>웹링크</option>";
                    html += "<option value='AL'>앱링크</option>";
                    html += "<option value='BK'>봇키워드</option>";
                    html += "<option value='MD'>메시지전달</option>";
                    html += "</select>";
                    html += "</td>";
                    html += "<td id='btn_add_msg' align='center' rowspan='2' colspan='5'>버튼을 추가할 수 있습니다.</td>";
                    //N
                    html += "<td id='n_1_" + counter + "' align='center' rowspan='2' hidden></td>";
                    html += "<td id='n_2_" + counter + "' align='center' width='67px' hidden></td>";
                    //DS
                    html += "<td id='ds_1_" + counter + "' align='center' rowspan='2' hidden><input name='btn_name1' id='ds_1_btn_name_" + counter + "' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                    html += "<td id='ds_2_" + counter + "' width='67px' hidden>* 알림톡 메시지 파싱을 통해 배송조회 카카오검색 페이지 링크가 자동 생성 됩니다.<br>* 파싱 지원 택배사 : KG로지스 우체국택배 로젠택배 CJ대한통운 일양로지스 롯데택배 GTX로지스 FedEx 한진택배 경동택배 합동택배</td>";
                    //BK
                    html += "<td id='bk_1_" + counter + "' align='center' rowspan='2' hidden><input name='btn_name4' id='bk_1_btn_name_" + counter + "' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                    html += "<td id='bk_2_" + counter + "' align='center' hidden></td>";
                    //MD
                    html += "<td id='md_1_" + counter + "' align='center' rowspan='2' hidden><input name='btn_name5' id='md_1_btn_name_" + counter + "' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                    html += "<td id='md_2_" + counter + "' align='center' hidden></td>";
                    //WL
                    html += "<td id='wl_1_" + counter + "' align='center' rowspan='2' hidden><input name='btn_name2' id='wl_1_btn_name_" + counter + "' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                    html += "<td id='wl_2_" + counter + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 30px;'>Mobile</label><input style='margin-left: 30px;' name='btn_url21' maxlength='255' type='text' value='http://' id='wl_2_btn_url_" + counter + "' class='form-control input-width-medium inline'></td>";
                    //AL
                    html += "<td id='al_1_" + counter + "' align='center' rowspan='2' hidden><input name='btn_name3' id='al_1_btn_name_" + counter + "' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                    html += "<td id='al_2_" + counter + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 22px;'>Android</label><input style='margin-left: 30px;' name='btn_url31' maxlength='255' type='text' id='al_2_btn_url_" + counter + "' class='form-control input-width-medium inline'></td>";
                    html += "<td id='btn_del' align='center' rowspan='2' hidden><button class='btn btn-primary' type='button' style='margin-top: 5px; padding: 3px 5px;' onclick='btn_del(" + counter + ");'><i class='icon-trash'></i> 제거</button></td>";
                    html += "</tr>";
                    //웹&앱링크&공통
                    html += "<tr id='btn_content_" + counter + "' class='" + counter + "'>";
                    html += "<td id='wl_3_" + counter + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 26px;'>PC(선택)</label><input style='margin-left: 29px;' name='btn_url22' maxlength='255' type='text' id='wl_3_btn_url_" + counter + "' class='form-control input-width-medium inline'></td>";
                    html += "<td id='al_3_" + counter + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 50px;'>iOS</label><input style='margin-left: 30px;' name='btn_url32' maxlength='255' type='text' id='al_3_btn_url_" + counter + "' class='form-control input-width-medium inline'></td>";
                    html += "</tr>";

                    $("#btn_content_0").after(html);
                    $('td').css('border-left', '1px solid #dedede').css('border-right', '1px solid #dedede').css('border-top', '1px solid #dedede');
                    $('select.none-search').select2({
                        minimumResultsForSearch: Infinity
                    });
			<?	} else {
			    
			?>
					var buttons = '<?=$rs->tpl_button?>';
					var btn = buttons.replace(/&quot;/gi, "\"");
                    var btn_content = JSON.parse(btn);

                    for (var i = 0; i < btn_content.length; i++) {
                        var html = "";
                        var btn_type = btn_content[i]["linkType"];
                        var btn_name = "";
                        if (btn_content[i]["name"] != undefined) {
                            btn_name = btn_content[i]["name"];
                        }
                        var linkMo = btn_content[i]["linkMo"];
                        var linkPc = btn_content[i]["linkPc"];
                        var linkAnd = btn_content[i]["linkAnd"];
                        var linkIos = btn_content[i]["linkIos"];

                        var counter = i + 1;

                        //공통
                        html += "<tr id='btn_content_" + counter + "_1'>";
                        html += "<td id='no' align='center' rowspan='2'>" + counter + "</td>";
                        html += "<td id='btn_type_td' name='btn_type_td' align='center' rowspan='2'>";
                        html += "<select class='select2 input-width-medium none-search' id='btn_type" + counter + "' name='btn_type' onchange='modify_btn_type(" + counter + ");'>";
                        html += "<option value='N'>선택</option>";
                        html += "<option value='DS'>배송조회</option>";
                        html += "<option value='WL'>웹링크</option>";
                        html += "<option value='AL'>앱링크</option>";
                        html += "<option value='BK'>봇키워드</option>";
                        html += "<option value='MD'>메시지전달</option>";
                        html += "</select>";
                        html += "</td>";
                        html += "<td id='btn_add_msg' align='center' rowspan='2' colspan='5' hidden>버튼을 추가할 수 있습니다.</td>";
                        //N
                        html += "<td id='n_1_" + counter + "' align='center' rowspan='2' hidden></td>";
                        html += "<td id='n_2_" + counter + "' align='center' width='67px' hidden></td>";
                        //DS
                        html += "<td id='ds_1_" + counter + "' align='center' rowspan='2' hidden><input name='btn_name1' id='ds_1_btn_name_" + counter + "' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                        html += "<td id='ds_2_" + counter + "' width='67px' hidden>* 알림톡 메시지 파싱을 통해 배송조회 카카오검색 페이지 링크가 자동 생성 됩니다.<br>* 파싱 지원 택배사 : KG로지스 우체국택배 로젠택배 CJ대한통운 일양로지스 롯데택배 GTX로지스 FedEx 한진택배 경동택배 합동택배</td>";
                        //봇키워드
                        html += "<td id='bk_1_" + counter + "' align='center' rowspan='2' hidden><input name='btn_name4' id='bk_1_btn_name_" + counter + "' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                        html += "<td id='bk_2_" + counter + "' align='center' hidden></td>";
                        //메시지전달
                        html += "<td id='md_1_" + counter + "' align='center' rowspan='2' hidden><input name='btn_name5' id='md_1_btn_name_" + counter + "' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                        html += "<td id='md_2_" + counter + "' align='center' hidden></td>";
                        //웹링크
                        html += "<td id='wl_1_" + counter + "' align='center' rowspan='2' hidden><input name='btn_name2' id='wl_1_btn_name_" + counter + "' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                        html += "<td id='wl_2_" + counter + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 30px;'>Mobile</label><input style='margin-left: 30px;' name='btn_url21' maxlength='255' type='text' id='wl_2_btn_url_" + counter + "' class='form-control input-width-medium inline'></td>";
                        //앱링크
                        html += "<td id='al_1_" + counter + "' align='center' rowspan='2' hidden><input name='btn_name3' id='al_1_btn_name_" + counter + "' maxlength='28' type='text' class='form-control input-width-medium inline'></td>";
                        html += "<td id='al_2_" + counter + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 22px;'>Android</label><input style='margin-left: 30px;' name='btn_url31' maxlength='255' type='text' id='al_2_btn_url_" + counter + "' class='form-control input-width-medium inline'></td>";
                        html += "<td id='btn_del' align='center' rowspan='2'><button class='btn btn-primary' type='button' style='margin-top: 5px; padding: 3px 5px;' onclick='btn_del(" + counter + ");'><i class='icon-trash'></i> 제거</button></td>";
                        html += "</tr>";
                        //웹&앱링크&공통
                        html += "<tr id='btn_content_" + counter + "' class='" + counter + "'>";
                        html += "<td id='wl_3_" + counter + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 26px;'>PC(선택)</label><input style='margin-left: 29px;' name='btn_url22' maxlength='255' type='text' id='wl_3_btn_url_" + counter + "' class='form-control input-width-medium inline'></td>";
                        html += "<td id='al_3_" + counter + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 50px;'>iOS</label><input style='margin-left: 30px;' name='btn_url32' maxlength='255' type='text' id='al_3_btn_url_" + counter + "' class='form-control input-width-medium inline'></td>";
                        html += "</tr>";

                        $("#btn_content_" + i).after(html);

                        $("#btn_type" + counter).val(btn_type);
                        if (btn_content[i]["linkType"] == "DS") {
                            $("#ds_1_" + counter).attr("hidden", false);
                            $("#ds_2_" + counter).attr("hidden", false);
                            $("#ds_1_btn_name_" + counter).val(btn_name);
                            $("#wl_2_btn_url_" + counter).val("http://");
                        } else if (btn_content[i]["linkType"] == "WL") {
                            $("#wl_1_" + counter).attr("hidden", false);
                            $("#wl_2_" + counter).attr("hidden", false);
                            $("#wl_3_" + counter).attr("hidden", false);
                            $("#wl_1_btn_name_" + counter).val(btn_name);
                            $("#wl_2_btn_url_" + counter).val(linkMo);
                            $("#wl_3_btn_url_" + counter).val(linkPc);
                        } else if (btn_content[i]["linkType"] == "AL") {
                            $("#al_1_" + counter).attr("hidden", false);
                            $("#al_2_" + counter).attr("hidden", false);
                            $("#al_3_" + counter).attr("hidden", false);
                            $("#al_1_btn_name_" + counter).val(btn_name);
                            $("#al_2_btn_url_" + counter).val(linkAnd);
                            $("#al_3_btn_url_" + counter).val(linkIos);
                            $("#wl_2_btn_url_" + counter).val("http://");
                        } else if (btn_content[i]["linkType"] == "BK") {
                            $("#bk_1_" + counter).attr("hidden", false);
                            $("#bk_2_" + counter).attr("hidden", false);
                            $("#bk_1_btn_name_" + counter).val(btn_name);
                            $("#wl_2_btn_url_" + counter).val("http://");
                        } else if (btn_content[i]["linkType"] == "MD") {
                            $("#md_1_" + counter).attr("hidden", false);
                            $("#md_2_" + counter).attr("hidden", false);
                            $("#md_1_btn_name_" + counter).val(btn_name);
                            $("#wl_2_btn_url_" + counter).val("http://");
                        }
                        $('td').css('border-left', '1px solid #dedede').css('border-right', '1px solid #dedede').css('border-top', '1px solid #dedede');
                        $('select.none-search').select2({
                            minimumResultsForSearch: Infinity
                        });
                    }
            <?	}
			} else {
			    
			?>
					var buttons = '<?=$rs->tpl_button?>';
					if(buttons != "[]") {
                    var btn = buttons.replace(/&quot;/gi, "\"");
                    var btn_content = JSON.parse(btn);

                    for (var i = 0; i < btn_content.length; i++) {
                        var html = "";
                        var btn_name = btn_content[i]["name"];
                        var linkMo = btn_content[i]["linkMo"];
                        var linkPc = btn_content[i]["linkPc"];
                        var linkAnd = btn_content[i]["linkAnd"];
                        var linkIos = btn_content[i]["linkIos"];
						if(btn_content[i]["linkType"]=="DS") btn_content[i]["linkTypeName"] = "<?=$this->Biz_model->buttonName['DS']?>";
						else if(btn_content[i]["linkType"]=="WL") btn_content[i]["linkTypeName"] = "<?=$this->Biz_model->buttonName['WL']?>";
						else if(btn_content[i]["linkType"]=="AL") btn_content[i]["linkTypeName"] = "<?=$this->Biz_model->buttonName['AL']?>";
						else if(btn_content[i]["linkType"]=="BK") btn_content[i]["linkTypeName"] = "<?=$this->Biz_model->buttonName['BK']?>";
						else if(btn_content[i]["linkType"]=="MD") btn_content[i]["linkTypeName"] = "<?=$this->Biz_model->buttonName['MD']?>";

                        var counter = i + 1;
                        if (btn_content[i]["linkType"] == "DS" || btn_content[i]["linkType"] == "BK" || btn_content[i]["linkType"] == "MD") {
                            html += "<tr id='btn_content_" + counter + "'>";
                            html += "<td id='no' align='center'>" + counter + "</td>";
                            html += "<td align='center'>" + btn_content[i]["linkTypeName"] + "</td>";
                            html += "<td align='center'>" + btn_name + "</td>";
                            html += "<td align='center'></td>";
                            html += "</tr>";
                        } else {
                            if (btn_content[i]["linkType"] == "WL") {
                                if (btn_content[i]["linkPc"] == null) {
                                    linkPc = '&nbsp;';
                                }
                                html += "<tr>";
                                html += "<td id='no' align='center' rowspan='2' style='vertical-align: middle !important;'>" + counter + "</td>";
                                html += "<td align='center' rowspan='2' style='vertical-align: middle !important;'>" + btn_content[i]["linkTypeName"] + "</td>";
                                html += "<td align='center' rowspan='2' style='vertical-align: middle !important;'>" + btn_name + "</td>";
                                html += "<td><label style='font-weight: 100; vertical-align: middle !important; margin-right: 70px;'>Mobile</label><div style='margin-top : -23px; margin-left: 70px; word-break:break-all;'>" + linkMo + "</div></td>";
                                html += "</tr>";
                                html += "<tr id='btn_content_" + counter + "'>";
                                html += "<td><label style='font-weight: 100; vertical-align: middle !important; margin-right: 70px;'>PC(선택)</label><div style='margin-top : -23px; margin-left: 70px; word-break:break-all;'>" + linkPc + "</div></td>";
                                html += "</tr>";
                            } else if (btn_content[i]["linkType"] == "AL") {
                                html += "<tr>";
                                html += "<td id='no' align='center' rowspan='2' style='vertical-align: middle !important;'>" + counter + "</td>";
                                html += "<td align='center' rowspan='2' style='vertical-align: middle !important;'>" + btn_content[i]["linkTypeName"] + "</td>";
                                html += "<td align='center' rowspan='2' style='vertical-align: middle !important;'>" + btn_name + "</td>";
                                html += "<td><label style='font-weight: 100; vertical-align: middle !important; margin-right: 70px;'>Android</label><div style='margin-top : -23px; margin-left: 70px; word-break:break-all;'>" + linkAnd + "</div></td>";
                                html += "</tr>";
                                html += "<tr id='btn_content_" + counter + "' class='" + counter + "'>";
                                html += "<td><label style='font-weight: 100; vertical-align: middle !important; margin-right: 70px;'>iOS</label><div style='margin-top : -23px; margin-left: 70px; word-break:break-all;'>" + linkIos + "</div></td>";
                                html += "</tr>";
                            }
                        }
                        $("#btn_content_" + i).after(html);
                        $('td').css('border-left', '1px solid #dedede').css('border-right', '1px solid #dedede').css('border-top', '1px solid #dedede');
                        $('select.search-static').select2();
                        $('select.none-search').select2({
                            minimumResultsForSearch: Infinity
                        });
                    }
					}
			<?}?>
        }

        function modify_btn_type(counter) {
            var btn_type = $("#btn_type" + counter).val(); //바꾸고 싶은 타입 값

            $("#n_1_" + counter).attr("hidden", true);
            $("#n_2_" + counter).attr("hidden", true);
            $("#ds_1_" + counter).attr("hidden", true);
            $("#ds_2_" + counter).attr("hidden", true);
            $("#wl_1_" + counter).attr("hidden", true);
            $("#wl_2_" + counter).attr("hidden", true);
            $("#wl_3_" + counter).attr("hidden", true);
            $("#al_1_" + counter).attr("hidden", true);
            $("#al_2_" + counter).attr("hidden", true);
            $("#al_3_" + counter).attr("hidden", true);
            $("#bk_1_" + counter).attr("hidden", true);
            $("#bk_2_" + counter).attr("hidden", true);
            $("#md_1_" + counter).attr("hidden", true);
            $("#md_2_" + counter).attr("hidden", true);

            if (btn_type == "N") {
                $("#n_1_" + counter).attr("hidden", false);
                $("#n_2_" + counter).attr("hidden", false);
            } else if (btn_type == "DS") {
                $("#ds_1_" + counter).attr("hidden", false);
                $("#ds_2_" + counter).attr("hidden", false);
            } else if (btn_type == "WL") {
                $("#wl_1_" + counter).attr("hidden", false);
                $("#wl_2_" + counter).attr("hidden", false);
                $("#wl_3_" + counter).attr("hidden", false);
                $(".content").html("현재 카카오 정책상 알림톡 메시지는 PC에서 메시지내용이 숨김 처리되어 PC버전으로 버튼이 보이지 않습니다.<br><br>향후 카카오 정책 변경으로 인한(미정) 템플릿 수정의 번거로움을 덜기 위하여 <b>PC 버튼링크</b>를 선택적으로 입력이 가능합니다.");
                $("#myModal").modal('show');
            } else if (btn_type == "AL") {
                $("#al_1_" + counter).attr("hidden", false);
                $("#al_2_" + counter).attr("hidden", false);
                $("#al_3_" + counter).attr("hidden", false);
            } else if (btn_type == "BK") {
                $("#bk_1_" + counter).attr("hidden", false);
                $("#bk_2_" + counter).attr("hidden", false);
            } else if (btn_type == "MD") {
                $("#md_1_" + counter).attr("hidden", false);
                $("#md_2_" + counter).attr("hidden", false);
            }
        }

        function btn_add() {
            var lastItemNo = $("#addedFormDiv tr:last").attr("class");
            var tr_length = $("#addedFormDiv tr").length;
            var current_btn_num = lastItemNo;
            var obj = Number(current_btn_num) + 1;
            if (tr_length == 13 && $("#no").is(":hidden") == true) {
                $("#no").attr("hidden", false);
                $("#btn_type_td").attr("hidden", false);
                $("#n_1_" + current_btn_num).attr("hidden", false);
                $("#n_2_" + current_btn_num).attr("hidden", false);
                $("#btn_del").attr("hidden", false);
                $("#btn_add_msg").attr("hidden", true);
            } else if (tr_length < 21) { // 업체명,템플릿코드,템플릿명,등록일,검수요청일,검수일,검수상태,템플릿상태,문의상태,템플릿내용,버튼5개(최대10) = 20
                var btn_tr = tr_length - 9;
                var no = btn_tr / 2;
                var col_html = "";
                //공통
                col_html += "               <tr id='btn_content_" + obj + "_1'>";
                col_html += "                   <td id='no' align='center' rowspan='2'>" + no + "</td>";
                col_html += "                   <td id='btn_type_td' name='btn_type_td' align='center' rowspan='2'>";
                col_html += "                       <select class='select2 input-width-medium none-search' id='btn_type" + obj + "' name='btn_type' onchange=\"modify_btn_type(" + obj + ")\">";
                col_html += "                           <option value='N'>선택</option>";
                col_html += "                           <option value='DS'>배송조회</option>";
                col_html += "                           <option value='WL'>웹링크</option>";
                col_html += "                           <option value='AL'>앱링크</option>";
                col_html += "                           <option value='BK'>봇키워드</option>";
                col_html += "                           <option value='MD'>메시지전달</option>";
                col_html += "                       </select>";
                col_html += "                   </td>";
                col_html += "                   <td id='btn_add_msg' align='center' rowspan='2' colspan='5' hidden>버튼을 추가할 수 있습니다.</td>";
                //N
                col_html += "                   <td id='n_1_" + obj + "' align='center' rowspan='2'></td>";
                col_html += "                   <td id='n_2_" + obj + "' align='center' width='67px'></td>";
                //DS
                col_html += "                   <td id='ds_1_" + obj + "' align='center' rowspan='2' hidden><input name='btn_name1' type='text' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='ds_2_" + obj + "' width='67px' hidden>* 알림톡 메시지 파싱을 통해 배송조회 카카오검색 페이지 링크가 자동 생성 됩니다.<br>* 파싱 지원 택배사 : KG로지스 우체국택배 로젠택배 CJ대한통운 일양로지스 롯데택배 GTX로지스 FedEx 한진택배 경동택배 합동택배</td>";
                //BK
                col_html += "                   <td id='bk_1_" + obj + "' align='center' rowspan='2' hidden><input name='btn_name4' type='text' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='bk_2_" + obj + "' align='center' width='67px' hidden></td>";
                //MD
                col_html += "                   <td id='md_1_" + obj + "' align='center' rowspan='2' hidden><input name='btn_name5' type='text' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='md_2_" + obj + "' align='center' width='67px' hidden></td>";
                //WL
                col_html += "                   <td id='wl_1_" + obj + "' align='center' rowspan='2' hidden><input name='btn_name2' type='text' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='wl_2_" + obj + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 30px;'>Mobile</label><input style='margin-left: 30px;' type='text' value='http://' name='btn_url21' id='wl_2_btn_url_" + obj + "' class='form-control input-width-medium inline'></td>";
                //AL
                col_html += "                   <td id='al_1_" + obj + "' align='center' rowspan='2' hidden><input name='btn_name3' type='text' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='al_2_" + obj + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 22px;'>Android</label><input style='margin-left: 30px;' type='text' name='btn_url31' id='al_2_btn_url_" + obj + "' class='form-control input-width-medium inline'></td>";
                //공통
                col_html += "                   <td id='btn_del' align='center' rowspan='2'><button class='btn btn-primary' type='button' style='margin-top: 5px; padding: 3px 5px;' onclick='btn_del(" + obj + ");'><i class='icon-trash'></i> 제거</button></td>";
                col_html += "               </tr>";
                col_html += "               <tr id='btn_content_" + obj + "' class='" + obj + "'>";
                col_html += "                   <td id='wl_3_" + obj + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 26px;'>PC(선택)</label><input style='margin-left: 29px;' type='text' name='btn_url22' class='form-control input-width-medium inline'></td>";
                col_html += "                   <td id='al_3_" + obj + "' hidden><label style='font-weight: 100; padding-top: 5px; margin-left: 50px;'>iOS</label><input style='margin-left: 30px;' type='text' name='btn_url32' class='form-control input-width-medium inline'></td>";
                col_html += "               </tr>";

                $("#btn_content_" + lastItemNo).after(col_html);
                $('td').css('border-left', '1px solid #dedede').css('border-right', '1px solid #dedede').css('border-top', '1px solid #dedede');
                $('select.none-search').select2({
                    minimumResultsForSearch: Infinity
                });
            }
        }

        function btn_del(obj) {
            var tr_length = $("#addedFormDiv tr").length;
            if (tr_length < 14) {
                $("#n_1_" + obj).attr("hidden", true);
                $("#n_2_" + obj).attr("hidden", true);
                $("#ds_1_" + obj).attr("hidden", true);
                $("#ds_2_" + obj).attr("hidden", true);
                $("#wl_1_" + obj).attr("hidden", true);
                $("#wl_2_" + obj).attr("hidden", true);
                $("#wl_3_" + obj).attr("hidden", true);
                $("#al_1_" + obj).attr("hidden", true);
                $("#al_2_" + obj).attr("hidden", true);
                $("#al_3_" + obj).attr("hidden", true);
                $("#bk_1_" + obj).attr("hidden", true);
                $("#bk_2_" + obj).attr("hidden", true);
                $("#md_1_" + obj).attr("hidden", true);
                $("#md_2_" + obj).attr("hidden", true);
                $("#btn_type" + obj + " option:eq(0)").prop("selected", true);
                $("#btn_type" + obj).select2("val", "N");

                $("#addedFormDiv").find(document.getElementsByName("btn_name1")).val("");
                $("#addedFormDiv").find(document.getElementsByName("btn_name2")).val("");
                $("#addedFormDiv").find(document.getElementsByName("btn_name3")).val("");
                $("#addedFormDiv").find(document.getElementsByName("btn_name4")).val("");
                $("#addedFormDiv").find(document.getElementsByName("btn_name5")).val("");
                $("#addedFormDiv").find(document.getElementsByName("btn_url21")).val("http://");
                $("#addedFormDiv").find(document.getElementsByName("btn_url22")).val("");
                $("#addedFormDiv").find(document.getElementsByName("btn_url31")).val("");
                $("#addedFormDiv").find(document.getElementsByName("btn_url32")).val("");

                $("#no").attr("hidden", true);
                $("#btn_type_td").attr("hidden", true);
                $("#btn_del").attr("hidden", true);
                $("#btn_add_msg").attr("hidden", false);
            } else {
                $("#btn_content_" + obj).remove();
                $("#btn_content_" + obj + "_1").remove();
                var no = 1;
                $("#addedFormDiv tr").find("#no").each(function () {
                    $(this).text(no);
                    no++;
                });
            }
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
                    $(".content").html("영문, 숫자 또는 특수문자만 입력이 가능합니다.");
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                        obj.value = obj.value.replace(/[\ㄱ-ㅎㅏ-ㅣ가-힣]/g, '');
                        obj.focus();
                    });
                    return false;
                }
            }
            return true;
        }


        //검수결과 등록
        function comment_add() {
            var tmp_code = $('#input_tmp_code').val();
            var pf_key = $('#pf_key').val();
            var pf_type = $('#pf_type').val();
            var comment_content = document.getElementById("comment_content").value;

            if (comment_content) {
                if ($('#comment_content').val().trim().length == 0) { //공백문자 일 경우
                    $(".content").html("요청사항을 입력 해주세요.");
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                        $('#comment_content').focus();
                    });
                } else {
                    $.ajax({
                        url: "/biz/template/comment_add/",
                        type: "POST",
                        data: {
                            <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
                            pf_key: pf_key,
                            pf_type: pf_type,
                            tmp_code: tmp_code,
                            comment_content: comment_content
                        },
                        success: function (json) {
                            if (json['success'] > '0') {
                                $(".content").html("요청사항이 등록되었습니다.");
                                $("#myModal").modal('toggle');
                                return;
                            }
                            else if (json['success'] == '0') {
                                var text = json['message'];
                                $(".content").html(text.replace(/\n/g, "<br/>"));
                                $("#myModal").modal('show');
                                return;
                            }
                            $('#myModal').on('hidden.bs.modal', function () {
                                <!-- 템플릿이슈 수정 시작 -->
                                var tmpl_id = $("#tmpl_id").val();
                                var url = '/biz/template/view?0';
            					url = url.replace('0', tmpl_id);
            					window.document.location = url;
                                //var form = document.getElementById('mainForm');
                                //form.submit();
                                <!-- 템플릿이슈 수정 끝 -->
                            });
                        },
                        error: function (data, status, er) {
                            $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
                            $("#myModal").modal('show');
                        }
                    });
                }
            } else {
                $(".content").html("결과 및 요청사항을 작성해주세요.");
                $("#myModal").modal('show');
            }
        }

        //템플릿 삭제
        function delete_template() {
            var inspect_status = $('#inspect_status').val();
            var pf_key = $('#pf_key').val();
            var pf_type = $('#pf_type').val();
            var tmp_code = $('#input_tmp_code').val();
            var obj_tmp_code = [];
            var obj_pf_key = [];
            var obj_pf_type = [];

            obj_tmp_code.push(tmp_code);
            obj_pf_key.push(pf_key);
            obj_pf_type.push(pf_type);

            $.ajax({
                url: "/biz/template/check_delete/",
                type: "POST",
                data: {
                    <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
                    pf_key: JSON.stringify({pf_key: obj_pf_key}),
                    pf_type: JSON.stringify({pf_type: obj_pf_type}),
                    tmp_code: JSON.stringify({tmp_code: obj_tmp_code}),
                    count: 1
                },
                success: function (json) {
                    //var text = '템플릿을 삭제하였습니다.'+'\n'+'[요청결과] 성공 : ' + json['success'] + '건, ' + '실패 :' + json['fail'] + '건';
                    var text = '템플릿을 삭제하였습니다.';
                    $(".content").html(text.replace(/\n/g, "<br/>"));
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                        window.location.href = "/biz/template/lists/";
                    })
                },
                error: function (data, status, er) {
                    $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
                    $("#myModal").modal('show');
                }
            });
        }

        //템플릿 삭제
        function hide_template() {
            var inspect_status = $('#inspect_status').val();
            var pf_key = $('#pf_key').val();
            var pf_type = $('#pf_type').val();
            var tmp_code = $('#input_tmp_code').val();
            var obj_tmp_code = [];
            var obj_pf_key = [];
            var obj_pf_type = [];

            obj_tmp_code.push(tmp_code);
            obj_pf_key.push(pf_key);
            obj_pf_type.push(pf_type);

            $.ajax({
                url: "/biz/template/hide_proc/",
                type: "POST",
                data: {
                    <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
                    pf_key: JSON.stringify({pf_key: obj_pf_key}),
                    pf_type: JSON.stringify({pf_type: obj_pf_type}),
                    tmp_code: JSON.stringify({tmp_code: obj_tmp_code}),
                    count: 1
                },
                success: function (json) {
                    //var text = '템플릿을 삭제하였습니다.'+'\n'+'[요청결과] 성공 : ' + json['success'] + '건, ' + '실패 :' + json['fail'] + '건';
                    var text = '템플릿을 숨기기 하였습니다.';
                    $(".content").html(text.replace(/\n/g, "<br/>"));
                    $("#myModal").modal('show');
                    $('#myModal').on('hidden.bs.modal', function () {
                        window.location.href = "/biz/template/lists/";
                    })
                },
                error: function (data, status, er) {
                    $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
                    $("#myModal").modal('show');
                }
            });
        }
                
        //템플릿 수정
        function modify_template(tmp_code) {
            var pf_key = $('#pf_key').val();
            var pf_type = $('#pf_type').val();
            var inspect_status = $('#inspect_status').val();
            var tmpl_status = $('#tmpl_status').val();
            var new_tmp_code = $('#input_tmp_code').val();
            var new_tmp_name = $('#input_tmp_name').val();
            var new_tmp_cont = $('#input_tmp_cont').val();
            var new_sms_cont = ""; //미사용

            if ($('#input_tmp_code').val().trim().length == 0) { //공백문자 일 경우
                $(".content").html("템플릿 코드를 입력해주세요.");
                $("#myModal").modal('show');
                $('#myModal').on('hidden.bs.modal', function () {
                    $('#input_tmp_code').focus();
                });
            }
            else if ($('#input_tmp_name').val().trim().length == 0) { //공백문자 일 경우
                $(".content").html("템플릿 이름을 입력해주세요.");
                $("#myModal").modal('show');
                $('#myModal').on('hidden.bs.modal', function () {
                    $('#input_tmp_name').focus();
                });
            }
            else if ($('#input_tmp_cont').val().trim().length == 0) { //공백문자 일 경우
                $(".content").html("템플릿 내용을 입력해주세요.");
                $("#myModal").modal('show');
                $('#myModal').on('hidden.bs.modal', function () {
                    $('#input_tmp_cont').focus();
                });
            }
            else {
                if (inspect_status == "REG" || inspect_status == "REJ") {
                    var isValid = true;

                    $("#addedFormDiv tr").each(function () {
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
                                if ($(this).find(document.getElementsByName("btn_name2")).val().trim() == "") {
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
                                if ($(this).find(document.getElementsByName("btn_name3")).val().replace(/ /gi, "") == "") {
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

                    var btn_content = new Array();
                    var btnInfo = "";
                    var ordering = 1;
                    $("#addedFormDiv tr").each(function () {
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
                if (!isValid) {
                    return;
                }

                var new_btn_content = JSON.stringify(btn_content);
				//alert("pf_key : " + pf_key);
                //등록 또는 반려일 경우 수정가능
                $.ajax({
                    url: "/biz/template/modify_success/",
                    type: "POST",
                    data: {
                        <?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>",
                        pf_key: pf_key,
                        pf_type: pf_type,
                        tmp_code: tmp_code,
                        new_tmp_code: new_tmp_code,
                        new_tmp_name: new_tmp_name,
                        new_tmp_cont: new_tmp_cont,
                        new_sms_cont: new_sms_cont,
                        new_btn_content: new_btn_content
                    },
                    //성공시
                    success: function (json) {

                        if (json['success'] > '0') {
                            //var text = '템플릿을 수정하였습니다.'+'\n'+'[요청결과] 성공 : ' + json['success'] + '건, ' + '실패 :' + json['fail'] + '건';
                            var text = '템플릿을 수정하였습니다.';
                            $(".content").html(text.replace(/\n/g, "<br/>"));
                            $("#myModal").modal('show');
                        }
                        else if (json['success'] == '0') {
                            //var text = '템플릿 수정이 실패하였습니다.'+'\n'+'[요청결과] 성공 : ' + json['success'] + '건, ' + '실패 :' + json['fail'] + '건';
                            var text = json['message'];
                            $(".content").html(text.replace(/\n/g, "<br/>"));
                            $("#myModal").modal('show');
                            return;
                        }
                        $('#myModal').on('hidden.bs.modal', function () {
                            location.reload();
                        });
                    },
                    //실패시
                    error: function (xhr, errmsg, err) {
                        $(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
                        $("#myModal").modal('show');
                    }
                });
            }

            if (inspect_status == "APR" || inspect_status == "REQ") {
                var text = '수정가능한 상태가 아닙니다.' + '\n' + '검수요청 또는 승인 상태에서는 수정이 불가합니다.';
                $(".content").html(text.replace(/\n/g, "<br/>"));
                $("#myModal").modal('show');
            }
        }


    </script>

