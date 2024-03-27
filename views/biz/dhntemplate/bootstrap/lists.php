<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
			<div class="modal-body">
				<div class="content"></div>
				<button type="button" class="btn md btn-primary" data-dismiss="modal">확인</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
			<div class="modal-body">
				<div class="content2"></div>
				<button type="button" id="close_btn" class="btn md btn-default" data-dismiss="modal">취소</button>
				<button type="button" id="confirm_btn" class="btn md btn-primary" data-dismiss="modal">확인</button>
			</div>
		</div>
	</div>
</div>
<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu3.php');
?>
<!-- //3차 메뉴 -->
<!-- 컨텐츠 전체 영역 -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<? if($public == "Y") { ?>
				<h3>공용 템플릿 (전체 <b style="color: red" id="total_rows"><?=number_format($total_rows)?></b>개)</h3>
				<!--<button class="btn_tr" onclick="location.href='/dhnbiz/template/write/Y'">공용템플릿 등록하기</button>-->
			<? } else {?>
			   <h3>템플릿 (전체 <b style="color: red" id="total_rows"><?=number_format($total_rows)?></b>개)</h3>
				<!--<button class="btn_tr" onclick="location.href='/dhnbiz/template/write/'">템플릿 등록하기</button>-->
			<? } ?>
		</div>
<?
   $uri= $_SERVER['REQUEST_URI'];
   if (strpos($uri, "public_lists") == true) {
  ?>
  <form action="/dhnbiz/template/public_lists" method="post" id="mainForm">
  <? } else {?>
   <form action="/dhnbiz/template/lists" method="post" id="mainForm">
  <? } ?>
  <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
	<div class="white_box">
        <select class="input-width-large" id="pf_g" name="pf_g" onChange="search_template(0);">
			<option value="ALL">그룹명</option>
            <option value="c03b09ba2a86fc2984b940d462265dd4dbddb105" <?=($param['pf_g']=="c03b09ba2a86fc2984b940d462265dd4dbddb105") ? 'selected' : ''?>>[그룹] 마트친구</option>
			<option value="0681d073d38f4124a5a34b2eab602f8a1e0509e9" <?=($param['pf_g']=="0681d073d38f4124a5a34b2eab602f8a1e0509e9") ? 'selected' : ''?>>[그룹] DHN공용</option>
		</select>
		<select style="width:300px;" class="input-width-large" id="pf_ynm" name="pf_ynm" onChange="search_template(0);">
			<option value="ALL">업체명</option>
			<?foreach($profile as $r) {?>
			<option value="<?=$r->spf_key?>" <?=($param['pf_ynm']==$r->spf_key) ? 'selected' : ''?>><?=$r->spf_company?>(<?=$r->spf_friend?>)</option>
			<?}?>
		</select>
		<select id="advyn" name="advyn" onChange="search_template(0);" style="display:<?=($this->member->item('mem_level') >= 100) ? "" : "none"?>;">
			<option value="ALL">알림톡 사용여부</option>
			<option value="Y" <?=($param['advyn']=="Y") ? 'selected' : ''?>>사용</option>
			<option value="N" <?=($param['advyn']=="N") ? 'selected' : ''?>>사용안함</option>
		</select>
		<select id="inspect_status" name="inspect_status" onChange="search_template(0);">
			<option value="ALL">검수상태</option>
			<option value="REG" <?=($param['inspect_status']=="REG") ? 'selected' : ''?>>등록</option>
			<option value="REQ" <?=($param['inspect_status']=="REQ") ? 'selected' : ''?>>검수요청</option>
			<option value="APR" <?=($param['inspect_status']=="APR") ? 'selected' : ''?>>승인</option>
			<option value="REJ" <?=($param['inspect_status']=="REJ") ? 'selected' : ''?>>반려</option>
		</select>
		<select id="tmpl_status" name="tmpl_status" onChange="search_template(0);">
			<option value="ALL">템플릿 상태</option>
			<option value="R" <?=($param['tmpl_status']=="R") ? 'selected' : ''?>>대기(발송전)</option>
			<option value="A" <?=($param['tmpl_status']=="A") ? 'selected' : ''?>>정상</option>
			<option value="S" <?=($param['tmpl_status']=="S") ? 'selected' : ''?>>차단</option>
		</select>
		<select id="comment_status" name="comment_status" onChange="search_template(0);">
			<option value="ALL">문의 상태</option>
			<option value="INQ" <?=($param['comment_status']=="INQ") ? 'selected' : ''?>>문의</option>
			<option value="REP" <?=($param['comment_status']=="REP") ? 'selected' : ''?>>답변</option>
		</select>
		<select id="tmpl_search" name="tmpl_search">
			<!--<option value="ALL">검색항목</option>-->
			<option value="company" <?=($param['tmpl_search']=="company") ? 'selected' : ''?>>업체명</option>
			<option value="code" <?=($param['tmpl_search']=="" or $param['tmpl_search']=="code") ? 'selected' : ''?>>템플릿코드</option>
			<option value="name" <?=($param['tmpl_search']=="name") ? 'selected' : ''?>>템플릿명</option>
			<option value="contents" <?=($param['tmpl_search']=="contents") ? 'selected' : ''?>>메세지 내용</option>
		</select>
		<input type="text" id="searchStr" placeholder="검색어를 입력해주세요." name="searchStr" value="<?=$param['searchStr']?>" onKeypress="if(event.keyCode==13){ search_template(0); }">
		<button class="btn_search1" onclick="search_template(0);">조회</button>
	</div>
	<div class="white_box mg_t10">
		<div class="search_box">
			<button class="btn md blackline fr mg_l10" onclick="req_all_inspect_template()"><i class="fas fa-tasks"></i> 일괄 검수</button>
			<button class="btn md blackline fr" onclick="sync_template_status()"><i class="fas fa-clipboard-check"></i> 전체 검수결과 확인</button>
			<button class="btn blackline md" onclick="req_inspect_template()"><i class="fas fa-file-export"></i> 선택 템플릿 검수요청</button>
			<button class="btn md delete mg_l10" onclick="delete_templates()"><i class="far fa-trash-alt"></i> 선택 템플릿 삭제</button>
			<button class="btn md mg_l10" onclick="download_template()"><i class="icon-download-alt"></i> 선택 템플릿 다운로드</button>
		</div>
		<div class="table_top">
		<p class="notice">템플릿 등록후 꼭! 검수요청 버튼을 클릭하셔야 정상적으로 요청이 완료됩니다.</p>
		</div>
		<div class="table_list">
			<table>
				<colgroup>
					<col width="60px">
					<col width="180px"><?//업체명?>
					<col width="140px"><?//템플릿코드?>
					<col width="180px"><?//템플릿명?>
					<col width="260px"><?//템플릿 내용?>
					<col width="180px"><?//버튼정보?>
					<col width="85px"><?//검수상태?>
					<col width="85px"><?//템플릿상태?>
					<col width="100px"><?//버튼1타입?>
					<col width="80px"><?//확인?>
				</colgroup>
				<thead>
				<tr>
					<th>
						<label class="checkbox_container">
						  <input type="checkbox" id="check_all" onclick="checkAll();">
						  <span class="checkmark"></span>
						</label>
					</th>
					<th>업체명</th>
					<th>템플릿코드</th>
					<th>템플릿명</th>
					<th>템플릿 내용</th>
					<th>버튼정보</th>
					<th>검수상태</th>
					<th>템플릿상태</th>
					<th><? if($this->member->item('mem_level') >= 100){ ?>버튼1타입<? }else{ ?>문의상태<? } ?></th>
					<th>확인</th>
				</tr>
				</thead>
				<tbody>
						<!-- 템플릿이슈 수정 시작 -->
						<?$num = 0;
						foreach($list as $r) { $num++;?>
						<tr>
							<!-- 템플릿이슈 수정 끝 -->
							<td onclick="event.cancelBubble=true;">
								<?if($r->tpl_mem_id==$this->member->item('mem_id') || ( $r->tpl_mem_id == '0' && $this->member->item('mem_id') == '3' ) ) {?>
									<input type="checkbox" id="get_tmp_id" name="chk_tmplate_id[]" value="<?=$r->tpl_id?>" style="display:none" onclick="javascript:check_template('chk_tmplate_id[]')">
									<input type="checkbox" id="get_pf_key" name="chk_pf_key[]" value="<?=$r->tpl_profile_key?>" style="display:none" onclick="javascript:check_template('chk_pf_key[]')">
									<input type="checkbox" id="get_pf_type" name="chk_pf_type[]" value="<?=$r->spf_key_type?>" style="display:none" onclick="javascript:check_template('chk_pf_type[]')">
									<input type="checkbox" id="get_tmp_code" name="chk_tmplate[]" value="<?=$r->tpl_code?>" onclick="javascript:check_template('chk_tmplate[]')">
									<input type="checkbox" name="chk_inspect_status[]" value="<?=$r->tpl_inspect_status?>" style="display:none" onclick="javascript:check_template('chk_inspect_status[]')">
								<?} else { echo '-'; }?>
								<? if($this->member->item('mem_level') >= 100){ ?>
								<br><font color="red">(<?=$r->tpl_id?>)</font>
								<? } ?>
							</td>
							<td style="cursor:pointer;" onclick="javascript:clickTrEvent(<?=$r->tpl_id?>)">
								<?=$r->tpl_company?>(<?=$r->spf_friend?>)<?//업체명?>
							</td>
							<td style="word-break:break-all !important;">
								<?=$r->tpl_code?><?//템플릿코드?>
								<? if($this->member->item('mem_level') >= 100){ ?>
									<? if($r->tpl_adv_yn == "Y"){ //광고성 알림톡 사용의 경우 ?>
										<button class="btn_tem1_on" type="button" onclick="js_adv_yn('<?=$r->tpl_id?>', 'N');">알림톡사용_ON</button>
										<? if($r->tpl_adv_main_yn == "Y"){ //광고성 알림톡 메인 사용의 경우 ?>
											<button class="btn_tem2_on" type="button" style="margin-top:3px;cursor:default;">메인사용_ON</button>
										<? }else{ ?>
											<button class="btn_tem2_off" type="button" onclick="js_adv_main_yn('<?=$r->tpl_id?>', 'Y');">메인 사용_OFF</button>
										<? } ?>
										<? if($r->tpl_premium_yn == "Y"){ //프리미엄 사용의 경우 ?>
											<button class="btn_tem3_on" type="button" onclick="js_premium_yn('<?=$r->tpl_id?>', 'N');">프리미엄_ON</button>
										<? }else{ ?>
											<button class="btn_tem3_off" type="button" onclick="js_premium_yn('<?=$r->tpl_id?>', 'Y');">프리미엄_OFF</button>
										<? } ?>
									<? }else{ ?>
										<button class="btn_tem1_off" type="button" onclick="js_adv_yn('<?=$r->tpl_id?>', 'Y');">알림톡사용_OFF</button>
									<? } ?>
								<? } ?>
							</td>
							<td style="cursor:pointer; text-align:left;" onclick="javascript:clickTrEvent(<?=$r->tpl_id?>)">
                                <?=($r->tpl_emphasizetype=="IMAGE")? "<span class='template_imgtype'>이미지알림톡</span>" : "<span class='template_txttype'>텍스트알림톡</span>" ?>

								<ul class="tem_cate_info">
                                    <? if(!empty($r->tc_description)){ ?>
									<li><?=$r->tc_description?></li>
									<li><?
                                    if($r->tci_btn_cnt > 0){
                                        echo $r->tci_btn_cnt."버튼";
                                    }else{
                                        echo "버튼없음";
                                    }
                                    ?></li>
                                    <? } ?>
								</ul>

								<?=$r->tpl_name?><?//템플릿명?>
								<input id="<?=$num?>" type="hidden" value="<?=str_replace("\n", "<br />", str_replace('"', '', $r->tpl_contents))?>">
							</td>
							<td style="cursor:pointer;text-align:left;vertical-align:middle !important;" width="190" onclick="javascript:clickTrEvent(<?=$r->tpl_id?>)">
								<span rel="<?=$r->tpl_id?>"
										  class="align-left center-block bs-tooltip"
										  data-container="body" id="tmpl_cont"
										  data-placement="right" data-html="true">
									<script>
										var counter = '<?=$num?>';
										var cont = $("#" + counter).val();
										var str = /#{+[^#{]+}+/g;
										var cont_crt = cont.replace(str, '<span style="background-color:yellow;">$&</span>');
										$("[rel=<?=$r->tpl_id?>]").attr('data-original-title', cont_crt);
									</script>
									<?=str_replace("\n", "<br />", cut_str($r->tpl_contents, 50))?><?//템플릿 내용?>
								</span>
							</td>
							<?if($r->tpl_button) {?>
							<td>
								<?
									$tpl_button = "";
									$tpl_button_arr = json_decode($r->tpl_button);
									$tpl_button_cnt = 0;
									if(!empty($tpl_button_arr)){
										foreach ($tpl_button_arr as $arr) {
											$tpl_button_cnt++;
											echo "<div class='tem_btn1'>". $arr->name ."</div>";
										}
									}
								?><?//버튼정보?>
							</td>
							<?} else {?>
							<td><span class="label">-</span></td>
							<?}?>
							<td><?=$this->funn->setColorLabel($r->tpl_inspect_status)?></td>
							<td><?=$this->funn->setColorLabel($r->tpl_status)?></td>
							<td>
								<? if($this->member->item('mem_level') >= 100){ ?>
									<? if($r->tpl_adv_yn == "Y" and $tpl_button_cnt > 0){ //광고성 알림톡 사용의 경우 & 버튼이 있는 경우 ?>
									<div class="checks list_al_left">
										<ul>
										  <li><input type="radio" id="tem_btn_choice_<?=$r->tpl_id?>_L" value="L" onclick="js_btn1_type('<?=$r->tpl_id?>', 'L');"<? if($r->tpl_btn1_type == "L"){ ?> checked<? } ?>><label for="tem_btn_choice_<?=$r->tpl_id?>_L">스마트전단</label></li>
										  <li><input type="radio" id="tem_btn_choice_<?=$r->tpl_id?>_C" value="C" onclick="js_btn1_type('<?=$r->tpl_id?>', 'C');"<? if($r->tpl_btn1_type == "C"){ ?> checked<? } ?>><label for="tem_btn_choice_<?=$r->tpl_id?>_C">스마트쿠폰</label></li>
										  <li><input type="radio" id="tem_btn_choice_<?=$r->tpl_id?>_E" value="E" onclick="js_btn1_type('<?=$r->tpl_id?>', 'E');"<? if($r->tpl_btn1_type == "E"){ ?> checked<? } ?>><label for="tem_btn_choice_<?=$r->tpl_id?>_E">에디터사용</label></li>
										  <li><input type="radio" id="tem_btn_choice_<?=$r->tpl_id?>_S" value="S" onclick="js_btn1_type('<?=$r->tpl_id?>', 'S');"<? if($r->tpl_btn1_type == "S"){ ?> checked<? } ?>><label for="tem_btn_choice_<?=$r->tpl_id?>_S">직접입력</label></li>
									  </ul>
									</div><?//버튼1타입?>
									<? } ?>
								<? }else{ ?>
								<?=$this->funn->setColorLabel($r->tpl_comment_status)?><?//문의상태?>
								<? } ?>
							</td>
							<td><button class="btn sm blue" type="button" onclick="sync_one_template_status(<?=$r->tpl_id?>)"><i class="fas fa-clipboard-check"></i> 확인</button></td>
						</tr>
						<?}?>
				</tbody>
			</table>
		</div>
		<div class="clearfix">
			<button type="button" class="btn md blackline" onclick="req_inspect_template()"><i class="fas fa-file-export"></i> 선택 템플릿 검수요청</button>
			<button type="button" class="btn md mg_l10" onclick="<?=($public=='Y')?"delete_public_temp()":"delete_templates()"?>"><i class="icon-trash"></i> 선택 템플릿 삭제</button>
			<button type="button" class="btn md mg_l10" onclick="download_template()"><i class="icon-download-alt"></i> 선택 템플릿 다운로드</button>
			<button type="button" class="btn md fr blackline" onclick="req_all_inspect_template()"><i class="fas fa-tasks"></i> 일괄 검수</button>
			</div>
			<div class="page_cen mg_b50"><?echo $page_html?></div>
		</div>
	</div>
</div>
</form>

<div class="modal select fade" id="btn_detail_cont" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog select-dialog" id="modal">
		<div class="modal-content">
			<i class="material-icons modal_close" data-dismiss="modal">close</i>
			<div class="modal_title"><h2>템플릿 버튼 정보</h2></div>
			<div class="modal-body select-body">
				<div class="select-content">
					<div class="widget-content" id="btn_list">
						<div class="table_list">
							<table>
								<thead>
								<tr>
									<th class="text-center" width="20">no</th>
									<th class="text-center" width="40">버튼타입</th>
									<th class="text-center" width="50">버튼명</th>
									<th class="text-center" width="100">버튼링크</th>
								</tr>
								</thead>
								<tbody class="table-content" style="cursor: default;" id="btn_tbody">
							</table>
						</div>
						<script type="text/javascript">
							$(document).ready(function() {
								window.onpageshow = function(event) {
									//if ( event.persisted || (window.performance && window.performance.navigation.type == 2)) {
									//	alert("뒤로가기 했네");
									//}

								}
							});
						</script>
					</div>
				</div>
				<div class="modal_bottom">
					<button type="button" class="btn md btn-primary enter" id="code" name="code" data-dismiss="modal">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#nav li.nav30").addClass("current open");

	$('.searchBox input').unbind("keyup").keyup(function (e) {
		var code = e.which;
		if (code == 13) {
			search_template(0);
		}
	});

			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +1).attr('data-original-title', text);
			}

			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +2).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +3).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +4).attr('data-original-title', text);
			}



			var buttons = '[{&quot;ordering&quot;:1,&quot;name&quot;:&quot;자격증 발급신청&quot;,&quot;linkType&quot;:&quot;WL&quot;,&quot;linkTypeName&quot;:&quot;웹링크&quot;,&quot;linkMo&quot;:&quot;http://www.q-net.or.kr/isr001.do?id\u003disr00101\u0026gSite\u003dQ\u0026gId\u003d&quot;}]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +5).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +6).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +7).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +8).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +9).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +10).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +11).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +12).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +13).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +14).attr('data-original-title', text);
			}



			var buttons = '[]';

			var btn = buttons.replace(/&quot;/gi, "\"");
			var btn_content = JSON.parse(btn);

			var text = '';
			for (var i = 0; i < btn_content.length; i++) {
				var btn_type = btn_content[i]["linkType"];
				if (i > 0) {
					text += '<br/><br/>';
				}
				if (btn_type == "WL") {
					if (btn_content[i]["linkPc"] != null) {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"] + '<br/>' + btn_content[i]["linkPc"];
					} else {
						text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkMo"];
					}
				} else if (btn_type == "AL") {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"] + '<br/>' + btn_content[i]["linkAnd"] + '<br/>' + btn_content[i]["linkIos"];
				} else {
					text += btn_content[i]["linkTypeName"] + '<br/>' + btn_content[i]["name"];
				}
				$("#btn_content" +15).attr('data-original-title', text);
			}



	//전체 선택
	function checkAll() {
		if (document.getElementById("check_all").checked) {
			$("input[type=checkbox]").prop("checked", true);
		} else {
			$("input[type=checkbox]").prop("checked", false);
		}
	}

	<!-- 템플릿이슈 수정 시작 -->
	//상세 페이지 이동
	function clickTrEvent(tmpl_id) {
		var url = '/dhnbiz/template/view?0';
		url = url.replace('0', tmpl_id);
		window.document.location = url;
	}
	<!-- 템플릿이슈 수정 끝 -->

	function btn_detail(tmpl_id) {
		$("#btn_detail_cont").modal({backdrop: 'static'});
		$('#btn_detail_cont').unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 27) {
				$("#btn_detail_cont").modal("hide");
			} else {
				if (code == 13) {
					$("#btn_detail_cont").modal("hide");
				}
			}
		});

		$('#btn_detail_cont .widget-content').html('').load(
			"/dhnbiz/template/btn",
			{
						<?=$this->security->get_csrf_token_name()?>: '<?=$this->security->get_csrf_hash()?>',
				"tmpl_id": tmpl_id
			},
			function () {
				$('#btn_detail_cont').css({"overflow-y": "scroll"});
			}
		);
	}

	function sync_template_status() {
		var form = document.createElement("form");
		document.body.appendChild(form);
		form.setAttribute("method", "post");
		form.setAttribute("action", "/dhnbiz/template/sync_template_status");
		var scrfField = document.createElement("input");
		scrfField.setAttribute("type", "hidden");
		scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		form.appendChild(scrfField);

						<? if($public == "Y") { ?>
		var public_flag = document.createElement("input");
		public_flag.setAttribute("type", "hidden");
		public_flag.setAttribute("name", "public_flag");
		public_flag.setAttribute("value", "Y");
		form.appendChild(public_flag);
		<? } ?>

		form.submit();
	}

	function sync_one_template_status(tpl_id_) {
		var form = document.getElementById("mainForm");
		//document.body.appendChild(form);
		//form.setAttribute("method", "post");
		form.setAttribute("action", "/dhnbiz/template/sync_template_status");
		var scrfField = document.createElement("input");
		scrfField.setAttribute("type", "hidden");
		scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
		scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
		form.appendChild(scrfField);
		var tpl_id = document.createElement("input");
		tpl_id.setAttribute("type", "hidden");
		tpl_id.setAttribute("name", "tpl_id");
		tpl_id.setAttribute("value", tpl_id_);
		form.appendChild(tpl_id);
		<? if($public == "Y") { ?>
		var public_flag = document.createElement("input");
		public_flag.setAttribute("type", "hidden");
		public_flag.setAttribute("name", "public_flag");
		public_flag.setAttribute("value", "Y");
		form.appendChild(public_flag);
		<? } ?>
		form.submit();
	}

	//검색
	function search_template(page) {
		if (page == 0) {
			page = 1;
		}

		var searchStr = $("#searchStr").val();
		if(searchStr != ""){
			$("#searchStr").val(searchStr.trim());
		}

		var form = document.getElementById('mainForm');
		var pageField = document.createElement("input");
		pageField.setAttribute("type", "hidden");
		pageField.setAttribute("name", "page");
		pageField.setAttribute("value", page);
		form.appendChild(pageField);
		form.submit();
	}

	//템플릿 삭제
	function delete_templates() {
		var csrftoken = '<?=$this->security->get_csrf_hash()?>';
		var tmp_code = check_template("chk_tmplate[]");
		var inspect_status = check_template("chk_inspect_status[]");
		var obj_tmp_code = [];
		var obj_pf_key = [];
		var obj_pf_type = [];

		var pf_key = check_template("chk_pf_key[]");
		var pf_type = check_template("chk_pf_type[]");
		var count_REG = 0;

		for (var i = 0; i < check_template("chk_tmplate[]").length; i++) {
			if (inspect_status[i] == "REG" || inspect_status[i] == "REJ") {
				obj_tmp_code.push(tmp_code[i]);
				obj_pf_key.push(pf_key[i]);
				obj_pf_type.push(pf_type[i]);
				count_REG++;
			}
		}

		if (check_template("chk_tmplate[]").length > 0) {

			if (count_REG == 0) {
				$(".content").html("등록상태의 템플릿을 선택해주세요.");
				$("#myModal").modal('show');
			}

			else {
				$(".content2").html(check_template("chk_tmplate[]").length + "건의 템플릿을 삭제 하시겠습니까?");
				$("#myModal2").modal('show');

				$("#confirm_btn").click(function () {
					$.ajax({
						url: "/dhnbiz/template/check_delete/",
						type: "POST",
						data: {
							<?=$this->security->get_csrf_token_name()?>: csrftoken,
							pf_key: JSON.stringify({pf_key: obj_pf_key}),
							pf_type: JSON.stringify({pf_type: obj_pf_type}),
							tmp_code: JSON.stringify({tmp_code: obj_tmp_code}),
							count: count_REG
						},
						beforeSend: function () {
							$('#overlay').fadeIn();
						},
						complete: function () {
							$('#overlay').fadeOut();
						},
						success: function (json) {
							var text = '선택하신 템플릿을 삭제 요청하였습니다. (등록상태만 삭제가능)' + '\n' + '[요청결과] 성공 : ' + json['success'] + '건, ' + '실패 :' + json['fail'] + '건';
							$(".content").html(text.replace(/\n/g, "<br/>"));
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								location.reload();
							});
						},
						error: function (data, status, er) {
							$(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
							$("#myModal").modal('show');
						}
					});
				});
			}
		}
		else {
			$(".content").html("삭제할 템플릿을 선택해주세요.");
			$("#myModal").modal('show');
		}
	}

	//템플릿 삭제
	function delete_public_temp() {
		var csrftoken = '<?=$this->security->get_csrf_hash()?>';
		var tmp_code = check_template("chk_tmplate[]");
		var inspect_status = check_template("chk_inspect_status[]");
		var obj_tmp_code = [];
		var obj_pf_key = [];
		var obj_pf_type = [];

		var pf_key = check_template("chk_pf_key[]");
		var pf_type = check_template("chk_pf_type[]");
		var count_REG = 0;

		for (var i = 0; i < check_template("chk_tmplate[]").length; i++) {
				obj_tmp_code.push(tmp_code[i]);
				obj_pf_key.push(pf_key[i]);
				obj_pf_type.push(pf_type[i]);
				count_REG++;
		}

		if (check_template("chk_tmplate[]").length > 0) {



				$(".content2").html(check_template("chk_tmplate[]").length + "건의 템플릿을 삭제 하시겠습니까?");
				$("#myModal2").modal('show');

				$("#confirm_btn").click(function () {
					$.ajax({
						url: "/dhnbiz/template/check_delete_public/",
						type: "POST",
						data: {
							<?=$this->security->get_csrf_token_name()?>: csrftoken,
							pf_key: JSON.stringify({pf_key: obj_pf_key}),
							pf_type: JSON.stringify({pf_type: obj_pf_type}),
							tmp_code: JSON.stringify({tmp_code: obj_tmp_code}),
							count: count_REG
						},
						beforeSend: function () {
							$('#overlay').fadeIn();
						},
						complete: function () {
							$('#overlay').fadeOut();
						},
						success: function (json) {
							var text = '선택하신 공용 템플릿을 삭제 하였습니다. ';
							$(".content").html(text.replace(/\n/g, "<br/>"));
							$("#myModal").modal('show');
							$('#myModal').on('hidden.bs.modal', function () {
								location.reload();
							});
						},
						error: function (data, status, er) {
							$(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
							$("#myModal").modal('show');
						}
					});
				});

		}
		else {
			$(".content").html("삭제할 템플릿을 선택해주세요.");
			$("#myModal").modal('show');
		}
	}

	//선택된 템플릿 확인
	function check_template(obj) {
		var sum = 0, tag = [];
		var chk = document.getElementsByName(obj);
		var chk_id = document.getElementsByName("chk_tmplate_id[]");
		var chk_key = document.getElementsByName("chk_pf_key[]");
		var chk_key_type = document.getElementsByName("chk_pf_type[]");
		var chk_inspect_status = document.getElementsByName("chk_inspect_status[]");
		var tot = chk.length;

		for (var i = 0; i < tot; i++) {
			if (chk[i].checked == true) {
				chk_id[i].checked = true;
				chk_key[i].checked = true;
				chk_key_type[i].checked = true;
				chk_inspect_status[i].checked = true;
				tag[sum] = chk[i].value;
				sum++;
			}
		}
		return tag;
	}

	//템플릿 검수요청
	function req_inspect_template() {
		var csrftoken = '<?=$this->security->get_csrf_hash()?>';
		var tmp_code = check_template("chk_tmplate[]");
		var obj_tmp_code = [];
		var obj_pf_key = [];
		var obj_pf_type = [];
		var pf_key = check_template("chk_pf_key[]");
		var pf_type = check_template("chk_pf_type[]");
		var inspect_status = check_template("chk_inspect_status[]");
		var count_REG = 0;

		for (var i = 0; i < check_template("chk_tmplate[]").length; i++) {
			if (inspect_status[i] == "REG") {
				obj_tmp_code.push(tmp_code[i]);
				obj_pf_key.push(pf_key[i]);
				obj_pf_type.push(pf_type[i]);
				count_REG++;
			}
		}

		if (check_template("chk_tmplate[]").length > 0) {
			if (count_REG == 0) {
				$(".content").html("등록상태의 템플릿이 없습니다.");
				$("#myModal").modal('show');

			} else {
				$.ajax({
					url: "/dhnbiz/template/check_inspect/",
					type: "POST",
					data: {
						<?=$this->security->get_csrf_token_name()?>: csrftoken,
						pf_key: JSON.stringify({pf_key: obj_pf_key}),
						pf_type: JSON.stringify({pf_type: obj_pf_type}),
						tmp_code: JSON.stringify({tmp_code: obj_tmp_code}),
						count: check_template("chk_tmplate[]").length
					},
					beforeSend: function () {
						$('#overlay').fadeIn();
					},
					complete: function () {
						$('#overlay').fadeOut();
					},
					success: function (json) {
						var text = '선택하신 템플릿을 검수 요청하였습니다.' + '\n' + '[요청결과] 성공 : ' + json['success'] + '건, ' + '실패 :' + json['fail'] + '건';
						$(".content").html(text.replace(/\n/g, "<br/>"));
						$("#myModal").modal('show');
						$('#myModal').on('hidden.bs.modal', function () {
							location.reload();
						});
					},
					error: function (data, status, er) {
						$(".content").html("처리중 오류가 발생하였습니다.<br/>관리자에게 문의해주시기 바랍니다.");
						$("#myModal").modal('show');
					}
				});
			}
		} else {
			$(".content").html("검수요청할 템플릿을 선택해주세요.");
			$("#myModal").modal('show');
		}
	}

	//일괄 검수 요청
	function req_all_inspect_template() {
			if($("input[type = checkbox]").length < 2) {
				//전체 체크 박스가 하나 있어요!
			  $(".content").html("검수요청이 가능한 템플릿이 없습니다.");
			  $("#myModal").modal('show');
			  return;
			}
		$("input[type = checkbox]").prop("checked", true);

		var csrftoken = '<?=$this->security->get_csrf_hash()?>';
		var tmp_code = check_template("chk_tmplate[]");
		var obj_tmp_code = [];
		var obj_pf_key = [];
		var obj_pf_type = [];
		var pf_key = check_template("chk_pf_key[]");
		var pf_type = check_template("chk_pf_type[]");
		var inspect_status = check_template("chk_inspect_status[]");
		var count_REG = 0;
		for (var i = 0; i < check_template("chk_tmplate[]").length; i++) {

			if (inspect_status[i] == "REG") {
				obj_tmp_code.push(tmp_code[i]);
				obj_pf_key.push(pf_key[i]);
				obj_pf_type.push(pf_type[i]);
				count_REG++;
			}
		}

		if (check_template("chk_tmplate[]").length > 0) {

			if (count_REG == 0) {
				$(".content").html("등록상태의 템플릿이 없습니다.");
				$("#myModal").modal('show');
			}

			else {

				$.ajax({
					url: "/dhnbiz/template/check_inspect/",
					type: "POST",
					data: {
						<?=$this->security->get_csrf_token_name()?>: csrftoken,
						pf_key: JSON.stringify({pf_key: obj_pf_key}),
						pf_type: JSON.stringify({pf_type: obj_pf_type}),
						tmp_code: JSON.stringify({tmp_code: obj_tmp_code}),
						count: count_REG
					},
					beforeSend: function () {
						$('#overlay').fadeIn();
					},
					complete: function () {
						$('#overlay').fadeOut();
					},
					success: function (json) {
						var text = '현재 페이지의 등록상태 템플릿을 모두 검수 요청하였습니다.' + '\n' + '[요청결과] 성공 : ' + json['success'] + '건, ' + '실패 :' + json['fail'] + '건';
						$(".content").html(text.replace(/\n/g, "<br/>"));
						$("#myModal").modal('show');
						$('#myModal').on('hidden.bs.modal', function () {
							location.reload();
						});
					},
					error: function (data, status, er) {
						var text = '검수요청 가능한 상태의 템플릿이 없습니다!' + '\n' + '(등록상태의 템플릿만 검수요청 가능)';
						$(".content").html(text.replace(/\n/g, "<br/>"));
						$("#myModal").modal('show');
					}
				});
			}
		}
	}

	//CSV 파일 다운로드
	function download_template() {
		var csrftoken = '<?=$this->security->get_csrf_hash()?>';
		//계속 같은 템플릿 아이디 가져오는 현상 수정

		var obj_tmp_id = [];
		$("input:checkbox[id=get_tmp_code]:checked").each(function () {
			obj_tmp_id.push($(this).parent().parent().parent().find("#get_tmp_id").val());
		});

		if (check_template("chk_tmplate[]").length > 0) {
			$.download('/dhnbiz/template/download/', '<?=$this->security->get_csrf_token_name()?>=' + csrftoken + '&tmp_id=' + obj_tmp_id);
		}

		if (check_template("chk_tmplate[]").length == 0) {
			$(".content").html("템플릿을 선택해주세요.");
			$("#myModal").modal('show');
		}
	}

	$('#mainForm').submit(function () {
		$('#overlay').fadeIn();
		return true;
	});

	jQuery.download = function (url, data, method) {
		//url and data options required
		if (url && data) {
			//data can be string of parameters or array/object
			data = typeof data == 'string' ? data : jQuery.param(data);
			//split params into form inputs
			var inputs = '';
			jQuery.each(data.split('&'), function () {
				var pair = this.split('=');
				inputs += '<input type="hidden" name="' + pair[0] + '" value="' + pair[1] + '" />';
			});
			//send request
			jQuery('<form action="' + url + '" method="' + (method || 'post') + '">' + inputs + '</form>')
				.appendTo('body').submit().remove();
		}
	};

	//광고성 알림톡 여부 업데이트
	function js_adv_yn(tpl_id, tpl_adv_yn){
		//alert("tpl_id : "+ tpl_id +", tpl_adv_yn : "+ tpl_adv_yn);
		$.ajax({
			url: "/dhnbiz/template/adv_yn",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?> : "<?=$this->security->get_csrf_hash()?>",
				tpl_id : tpl_id,
				tpl_adv_yn : tpl_adv_yn
			},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				alert("업데이트 되었습니다.");
				location.reload();
			},
			error: function (data, status, er) {
				alert("처리중 오류가 발생하였습니다.");
				return;
			}
		});
	}

	//광고성 알림톡 메인 여부 업데이트
	function js_adv_main_yn(tpl_id, tpl_adv_main_yn){
		//alert("tpl_id : "+ tpl_id +", tpl_adv_main_yn : "+ tpl_adv_main_yn);
		$.ajax({
			url: "/dhnbiz/template/adv_main_yn",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?> : "<?=$this->security->get_csrf_hash()?>",
				tpl_id : tpl_id,
				tpl_adv_main_yn : tpl_adv_main_yn
			},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				alert("업데이트 되었습니다.");
				location.reload();
			},
			error: function (data, status, er) {
				alert("처리중 오류가 발생하였습니다.");
				return;
			}
		});
	}

	//프리미엄 여부 업데이트
	function js_premium_yn(tpl_id, tpl_premium_yn){
		//alert("tpl_id : "+ tpl_id +", tpl_premium_yn : "+ tpl_premium_yn);
		$.ajax({
			url: "/dhnbiz/template/premium_yn",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?> : "<?=$this->security->get_csrf_hash()?>",
				tpl_id : tpl_id,
				tpl_premium_yn : tpl_premium_yn
			},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				alert("업데이트 되었습니다.");
				location.reload();
			},
			error: function (data, status, er) {
				alert("처리중 오류가 발생하였습니다.");
				return;
			}
		});
	}

	//버튼1타입 업데이트
	function js_btn1_type(tpl_id, tpl_btn1_type){
		//alert("tpl_id : "+ tpl_id +", tpl_btn1_type : "+ tpl_btn1_type);
		$.ajax({
			url: "/dhnbiz/template/btn1_type",
			type: "POST",
			data: {
				<?=$this->security->get_csrf_token_name()?> : "<?=$this->security->get_csrf_hash()?>",
				tpl_id : tpl_id,
				tpl_btn1_type : tpl_btn1_type
			},
			beforeSend: function () {
				$('#overlay').fadeIn();
			},
			complete: function () {
				$('#overlay').fadeOut();
			},
			success: function (json) {
				alert("업데이트 되었습니다.");
				location.reload();
			},
			error: function (data, status, er) {
				alert("처리중 오류가 발생하였습니다.");
				return;
			}
		});
	}
</script>
