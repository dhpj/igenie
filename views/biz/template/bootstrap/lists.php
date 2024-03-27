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
	<?
	   $uri= $_SERVER['REQUEST_URI'];
	   if (strpos($uri, "public_lists") == true) {
    ?>
    <form action="/biz/template/public_lists" method="post" id="mainForm">
    <? } else {?>
     <form action="/biz/template/lists" method="post" id="mainForm">
    <? } ?>
    <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
				<div id="mArticle">
					<div class="form_section">
						<div class="inner_content">
								<select class="input-width-large" id="pf_ynm" name="pf_ynm">
                                    <option value="ALL">업체명</option>
                                    <?foreach($profile as $r) {?>
                                    <option value="<?=$r->spf_key?>" <?=($param['pf_ynm']==$r->spf_key) ? 'selected' : ''?>><?=$r->spf_company?>(<?=$r->spf_friend?>)</option>
                                    <?}?>
                                </select>

                                <select id="inspect_status"
                                        name="inspect_status">
                                    <option value="ALL">검수상태</option>
                                    <option value="REG" <?=($param['inspect_status']=="REG") ? 'selected' : ''?>>등록</option>
                                    <option value="REQ" <?=($param['inspect_status']=="REQ") ? 'selected' : ''?>>검수요청</option>
                                    <option value="APR" <?=($param['inspect_status']=="APR") ? 'selected' : ''?>>승인</option>
                                    <option value="REJ" <?=($param['inspect_status']=="REJ") ? 'selected' : ''?>>반려</option>
                                </select>

                                <select id="tmpl_status"
                                        name="tmpl_status">
                                    <option value="ALL">템플릿 상태</option>
                                    <option value="R" <?=($param['tmpl_status']=="R") ? 'selected' : ''?>>대기(발송전)</option>
                                    <option value="A" <?=($param['tmpl_status']=="A") ? 'selected' : ''?>>정상</option>
                                    <option value="S" <?=($param['tmpl_status']=="S") ? 'selected' : ''?>>차단</option>
                                </select>


                                <select id="comment_status"
                                        name="comment_status">
                                    <option value="ALL">문의 상태</option>
                                    <option value="INQ" <?=($param['comment_status']=="INQ") ? 'selected' : ''?>>문의</option>
                                    <option value="REP" <?=($param['comment_status']=="REP") ? 'selected' : ''?>>답변</option>
                                </select>

                                <select id="tmpl_search"
                                        name="tmpl_search">
                                    <option value="ALL">검색항목</option>
                                    <option value="company" <?=($param['tmpl_search']=="company") ? 'selected' : ''?>>업체명</option>
                                    <option value="code" <?=($param['tmpl_search']=="code") ? 'selected' : ''?>>템플릿코드</option>
                                    <option value="name" <?=($param['tmpl_search']=="name") ? 'selected' : ''?>>템플릿명</option>
                                    <option value="contents" <?=($param['tmpl_search']=="contents") ? 'selected' : ''?>>메세지 내용</option>
                                </select>
                                <input type="text" id="searchStr" placeholder="검색어를 입력해주세요." name="searchStr" value="<?=$param['searchStr']?>">
                                <button class="btn md yellow btn-default"  onclick="search_template(0)">조회</button>
						</div>
					</div>
					<div class="form_section">
						<div class="inner_tit">
							<? if($public == "Y") { ?>
					            <h3>공용 템플릿</h3>
					        <? } else {?>
					           <h3>나의 템플릿</h3>
					        <? } ?>
						</div>
						<div class="inner_content">
							<div class="search_box">
								<button class="btn md blue fr" style="margin-left: 10px;" onclick="req_all_inspect_template()"><i class="fas fa-tasks"></i> 일괄 검수</button>
	                            <button class="btn md blue fr" onclick="sync_template_status()"><i class="fas fa-clipboard-check"></i> 전체 검수결과 확인</button>
	                            <button class="btn md blue" onclick="req_inspect_template()"><i class="fas fa-file-export"></i> 선택 템플릿 검수요청</button>
								<button class="btn md delete" style="margin-left: 10px;" onclick="delete_templates()"><i class="far fa-trash-alt"></i> 선택 템플릿 삭제</button>
								<button class="btn md" style="margin-left: 10px;" onclick="download_template()"><i class="fas fa-file-download"></i>선택 템플릿 다운로드</button>
							</div>
							<div class="table_top">
							<p class="notice">템플릿 등록후 꼭! 검수요청 버튼을 클릭하셔야 정상적으로 요청이 완료됩니다.</p>
							</div>
							<div class="table_list">
								<table>
	                                <colgroup>
	                                    <col width="30px">
	                                    <col width="180px">
	                                    <col width="100px">
	                                    <col width="100px">
	                                    <col width="200">
	                                    <col width="100px">
	                                    <col width="80px">
	                                    <col width="100px">
	                                    <col width="80px">
	                                    <col width="80px">
	                                </colgroup>
	                                <thead>
	                                <tr>
	                                    <th><input type="checkbox" id="check_all" onclick="checkAll();"></th>
	                                    <th>업체명</th>
	                                    <th>템플릿코드</th>
	                                    <th>템플릿명</th>
	                                    <th>템플릿 내용</th>
	                                    <th>버튼정보</th>
	                                    <th>검수상태</th>
	                                    <th>템플릿상태</th>
	                                    <th>문의상태</th>
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
													<?if($r->tpl_mem_id==$this->member->item('mem_id') || ( $r->tpl_mem_id == '1' && $this->member->item('mem_id') == '3' ) ) {?>
	                                                <input type="checkbox" id="get_tmp_id" name="chk_tmplate_id[]" value="<?=$r->tpl_id?>" style="display:none" onclick="javascript:check_template('chk_tmplate_id[]')">
	                                                <input type="checkbox" id="get_pf_key" name="chk_pf_key[]" value="<?=$r->tpl_profile_key?>" style="display:none" onclick="javascript:check_template('chk_pf_key[]')">
	                                                <input type="checkbox" id="get_pf_type" name="chk_pf_type[]" value="<?=$r->spf_key_type?>" style="display:none" onclick="javascript:check_template('chk_pf_type[]')">
	                                                <input type="checkbox" id="get_tmp_code" name="chk_tmplate[]" value="<?=$r->tpl_code?>" onclick="javascript:check_template('chk_tmplate[]')">
	                                                <input type="checkbox" name="chk_inspect_status[]" value="<?=$r->tpl_inspect_status?>" style="display:none" onclick="javascript:check_template('chk_inspect_status[]')">
													<?} else { echo '-'; }?>
	                                            </td>
	                                            <td onclick="javascript:clickTrEvent(<?=$r->tpl_id?>)">
	                                                <?=$r->tpl_company?>(<?=$r->spf_friend?>)
	                                            </td>
	                                            <td style="word-break:break-all !important;" onclick="javascript:clickTrEvent(<?=$r->tpl_id?>)">
	                                                <?=$r->tpl_code?>
	                                            </td>
	                                            <td onclick="javascript:clickTrEvent(<?=$r->tpl_id?>)">
	                                                <?=$r->tpl_name?>
	                                                <input id="<?=$num?>" type="hidden"
	                                                       value="<?=str_replace("\n", "<br />", str_replace('"', '', $r->tpl_contents))?>">
	                                            </td>
	                                            <td style="vertical-align:middle !important;" width="190" onclick="javascript:clickTrEvent(<?=$r->tpl_id?>)">
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
	                                                        <?=str_replace("\n", "<br />", cut_str($r->tpl_contents, 47))?>
																		 </span>

	                                            </td>
												<?if($r->tpl_button) {?>
                                                <td onclick="javascript:btn_detail(<?=$r->tpl_id?>)">
                                                    <span id="btn_content<?=$num?>"
                                                          class="align-center center-block bs-tooltip"
                                                          data-placement="right" data-html="true"
                                                          data-original-title="">상세보기
                                                    </span>
                                                </td>
												<?} else {?>
                                                <td><span class="label">-</span></td>
												<?}?>
                                                <td><?=$this->funn->setColorLabel($r->tpl_inspect_status)?></td>
                                                <td><?=$this->funn->setColorLabel($r->tpl_status)?></td>
                                                <td><?=$this->funn->setColorLabel($r->tpl_comment_status)?></td>
                                                <td><button class="btn" type="button" onclick="sync_one_template_status(<?=$r->tpl_id?>)"><i class="icon-download-alt"></i>확인</button></td>
	                                        </tr>
											<?}?>
	                                </tbody>
	                            </table>
							</div>
							<div class="clearfix">
	                            <button class="btn md" style="margin-right: 10px;" onclick="delete_templates()"><i class="icon-trash"></i>선택 템플릿 삭제</button>
	                            <button class="btn md" onclick="download_template()"><i class="icon-download-alt"></i>선택 템플릿 다운로드</button>
	                            <button class="btn md fr" onclick="req_inspect_template()"><i class="icon-arrow-right"></i>선택 템플릿 검수요청</button>
	                            <button class="btn md fr" style="margin-right: 10px;" onclick="req_all_inspect_template()"><i class="icon-arrow-down"></i>일괄 검수</button>
							</div>
							<div class="align-center mt30"><?echo $page_html?></div>
						</div>
					</div>
				</div>
</form>

    <div class="modal select fade" id="btn_detail_cont" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true"
         style="overflow-y: scroll; padding-right:200px; height: 700px;">
        <div class="modal-dialog modal-lg select-dialog" id="modal">
            <div class="modal-content" style="width: 600px; height: auto;">

                <br/>
                <h4 class="modal-title" align="center">템플릿 버튼 정보</h4>
                <div class="modal-body select-body">
                    <div class="select-content">
                        <div class="widget-content" id="btn_list">
                            <div align="left" style="padding:-180px; height: auto; width: 100% !important;">
    <table class="table table-bordered table-highlight-head scrolltbody" style="width:100% !important; table-layout:fixed;">
        <thead>
        <tr style="cursor:default;">
            <th class="text-center" width="20">no</th>
            <th class="text-center" width="40">버튼타입</th>
            <th class="text-center" width="50">버튼명</th>
            <th class="text-center" width="100">버튼링크</th>
        </tr>
        </thead>
        <tbody class="table-content" style="cursor: default;" id="btn_tbody">
    </table>
</div>
<style>
    #btn_tbody td {
        word-break:break-all;
    }
</style>
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
                    <div align="center">
                        <br/>
                        <button type="button" class="btn btn-primary" id="code" name="code" data-dismiss="modal">확인
                        </button>
                        <br/><br/>
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
            var url = '/biz/template/view?0';
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
                "/biz/template/btn",
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
            form.setAttribute("action", "/biz/template/sync_template_status");
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
            form.setAttribute("action", "/biz/template/sync_template_status");
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
                            url: "/biz/template/check_delete/",
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
                            url: "/biz/template/check_delete_public/",
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
                        url: "/biz/template/check_inspect/",
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
                        url: "/biz/template/check_inspect/",
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
                $.download('/biz/template/download/', '<?=$this->security->get_csrf_token_name()?>=' + csrftoken + '&tmp_id=' + obj_tmp_id);
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
    </script>
