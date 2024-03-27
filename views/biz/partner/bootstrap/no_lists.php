<!-- 타이틀 영역 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu15.php');
?>
<!-- <div class="tit_wrap">
	파트너 관리
</div> -->
<!-- 타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>사용안함 파트너 목록 (전체 <b style="color: red" id="total_rows">0</b>개)</h3>
			<button class="btn_tr" onclick="location.href='/biz/partner/write'" <?=($this->member->item('mem_level')=='17')? "style='display:none;'" : "" ?>>파트너 등록하기</button>
		</div>
		<div class="white_box" id="search_box">
            <div class="search_box">
				<label for="userid">소속</label>
				<select name="userid" id="userid" <?//class="selectpicker"?> data-live-search="true" onChange="search_question(1);">
					<option value="ALL">- ALL -</option>
				<?foreach($users as $r) {?>
					<option value="<?=$r->mem_id?>" <?=($userid==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
				<?}?>
				</select>
				<!-- <span style="margin-left:10px;<?=($this->member->item('mem_level')==17)? "display:none;" : ""?>">계약형태</span>
				<select class="select2 input-width-medium" id="contract_type" onChange="search_question(1);" style="<?=($this->member->item('mem_level')==17)? "display:none;" : ""?>">
					<option value="ALL">- ALL -</option>
					<option value="S" <?=$contract_type=='S' ? 'selected' : ''?>>스텐다드</option>
					<option value="P" <?=$contract_type=='P' ? 'selected' : ''?>>프리미엄</option>
				</select>
				<span style="margin-left:10px;">월사용료</span><? //2021-02-09 추가 ?>
				<select class="select2 input-width-medium" id="monthly_fee_yn" onChange="search_question(1);">
					<option value="ALL">- ALL -</option>
					<option value="Y" <?=$monthly_fee_yn=='Y' ? 'selected' : ''?>>있음</option>
					<option value="N" <?=$monthly_fee_yn=='N' ? 'selected' : ''?>>없음</option>
				</select>
                <span style="margin-left:10px;">바우처</span><? //2022-03-22 추가 ?>
                <select class="select2 input-width-medium" id="voucher_yn" onChange="search_question(1);">
					<option value="ALL">- ALL -</option>
					<option value="Y" <?=$voucher_yn=='Y' ? 'selected' : ''?>>바우처</option>
					<option value="N" <?=$voucher_yn=='N' ? 'selected' : ''?>>일반</option>
				</select>
				<span style="margin-left:10px;">휴면상태</span>
				<select class="select2 input-width-medium" id="dormancy_yn" onChange="search_question(1);">
					<option value="ALL">- ALL -</option>
					<option value="N" <?=$dormancy_yn=='N' ? 'selected' : ''?>>정상</option>
					<option value="Y" <?=$dormancy_yn=='Y' ? 'selected' : ''?>>휴면</option>
				</select> -->
				<select class="select2 input-width-medium" id="searchType" style="margin-left:10px;">
					<option value="username" <?=$search_type=='username' ? 'selected' : ''?>>업체명</option>
					<option value="biz_reg_no" <?=$search_type=='biz_reg_no' ? 'selected' : ''?>>사업자번호</option>
					<option value="biz_reg_name" <?=$search_type=='biz_reg_name' ? 'selected' : ''?>>상호</option>
					<option value="phone" <?=$search_type=='phone' ? 'selected' : ''?>>전화번호</option>
					<option value="nickname" <?=$search_type=='nickname' ? 'selected' : ''?>>담당자 이름</option>
					<option value="biz_reg_rep_name" <?=$search_type=='biz_reg_rep_name' ? 'selected' : ''?>>대표자 이름</option>
				</select>
				<input type="text" class="" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$search_for?>" onKeypress="if(event.keyCode==13){ search_question(1); }">
				<input type="button" class="btn md" id="check" value="조회" onclick="search_question(1)"/>
				<? if($this->member->item('mem_id')=='3') {?>
					<button type="button" class="btn md excel fr" onclick="download_();">
					<i class="icon-arrow-down"></i> 엑셀 다운로드</button>
				<? } ?>
			</div>
            <div class="table_list">
                <table>
                	<colgroup>
                		<col width="*"><?//소속?>
                		<col width="6%"><?//가입일?>
                		<col width="13%"><?//계정관리?>
                		<col width="8%"><?//계정?>
                		<col width="14%"><?//업체명?>
                		<col width="10%"><?//잔액?>
                		<col width="8%"><?//담당자 이름?>
                		<col width="10%"><?//담당자 연락처?>
                        <col width="%"><?//영업자 이름?>
                		<col width="4%"><?//2nd?>
                		<? if($this->member->item('mem_level') >= '50') { ?>
                			<col width="155px"><?//고객등록?>
                		<? } ?>
                        <col width="4%"><?//2nd?>
                        <? if($this->member->item('mem_id') == '3') { ?>
                        <col width="3%"><?//2nd?>
                        <? } ?>
                	</colgroup>
                	<thead>
                	<tr>
                		<th>소속</th>
                		<th>가입일</th>
                		<th>계정관리</th>
                		<th>계정</th>
                		<th>업체명</th>
                		<th>잔액</th>
                		<th>담당자 이름</th>
                		<th>담당자 연락처</th>
                        <th>영업자 이름</th>
                		<th>2nd</th>
                		<? if($this->member->item('mem_level') >= '50') { ?>
                			<th>고객등록</th>
                		<? } ?>
                		<th>휴면상태</th> <!-- 2021.12.30 조지영 휴면계정 노출 작업 -->
                        <? if($this->member->item('mem_id') == '3') { ?>
                        <th>이관</th>
                        <? } ?>
                	</tr>
                	</thead>
                	<tbody>
                		<?
                			$offer = "";
                			foreach($rs as $row) {
                		?>
                		<tr>
                			<td><?if($offer==$row->mrg_recommend_mem_id) { echo '&nbsp;'; } else { echo $row->mrg_recommend_mem_username; $offer=$row->mrg_recommend_mem_id; }?></td><?//소속?>
                			<td><?=substr($row->mem_register_datetime, 0, 10)?></td><?//가입일?>
                			<td>
                				<input type="button" style="height:25px;" class="btn_userinsert" value="계정보기" onclick="document.location.href='/biz/partner/no_view?<?=$row->mem_userid?>'"/>
                				<?if($this->member->item("mem_level")>=10) {?>
                				<input type="button" style="height:25px;margin-left:5px;" class="btn_userchange" value="계정전환" onclick="document.location.href='/biz/main?<?=$row->mem_id?>'"/><?//계정전환?>
                				<?}?>
                			</td><?//계정관리?>
                			<td><?=$row->mem_userid?></td><?//계정?>
                			<td>
                                <div class="gn_tooltip"><?=$row->mem_username?>
								<span class="gn_tooltiptext">
                                    <?
                                        echo "발송단가";
                                        if ($row->mad_price_at > 0){
                                            echo "<br>알림톡 : " . $row->mad_price_at;
                                        }
                                        if ($row->mad_price_ft > 0){
                                            echo "<br>친구톡 : " . $row->mad_price_ft;
                                        }
                                        if ($row->mad_price_ft_img > 0){
                                            echo "<br>친구톡(이미지) : " . $row->mad_price_ft_img;
                                        }
                                        if ($row->mad_price_smt_sms > 0){
                                            echo "<br>단문문자(SMS) : " . $row->mad_price_smt_sms;
                                        }
                                        if ($row->mad_price_smt > 0){
                                            echo "<br>장문문자(LMS) : " . $row->mad_price_smt;
                                        }
                                        if ($row->mem_rcs_yn == "Y"){
                                            if ($row->mad_price_rcs_sms > 0){
                                                echo "<br>RCS(SMS) : " . $row->mad_price_rcs_sms;
                                            }
                                            if ($row->mad_price_rcs > 0){
                                                echo "<br>RCS(LMS) : " . $row->mad_price_rcs;
                                            }
                                            if ($row->mad_price_rcs_mms > 0){
                                                echo "<br>RCS(MMS) : " . $row->mad_price_rcs_mms;
                                            }
                                            if ($row->mad_price_rcs_tem > 0){
                                                echo "<br>RCS(TEM) : " . $row->mad_price_rcs_tem;
                                            }
                                        }
                                        if ($this->member->item("mem_id") == "3" && $row->mem_voucher_yn =="Y"){
                                            echo "<br><br>바우처단가";
                                            if ($row->vad_price_at > 0){
                                                echo "<br>알림톡 : " . $row->vad_price_at;
                                            }
                                            if ($row->vad_price_ft > 0){
                                                echo "<br>친구톡 : " . $row->vad_price_ft;
                                            }
                                            if ($row->vad_price_ft_img > 0){
                                                echo "<br>친구톡(이미지) : " . $row->vad_price_ft_img;
                                            }
                                            if ($row->vad_price_smt_sms > 0){
                                                echo "<br>단문문자(SMS) : " . $row->vad_price_smt_sms;
                                            }
                                            if ($row->vad_price_smt > 0){
                                                echo "<br>장문문자(LMS) : " . $row->vad_price_smt;
                                            }
                                            if ($row->mem_rcs_yn == "Y"){
                                                if ($row->vad_price_rcs_sms > 0){
                                                    echo "<br>RCS(SMS) : " . $row->vad_price_rcs_sms;
                                                }
                                                if ($row->vad_price_rcs > 0){
                                                    echo "<br>RCS(LMS) : " . $row->vad_price_rcs;
                                                }
                                                if ($row->vad_price_rcs_mms > 0){
                                                    echo "<br>RCS(MMS) : " . $row->vad_price_rcs_mms;
                                                }
                                                if ($row->vad_price_rcs_tem > 0){
                                                    echo "<br>RCS(TEM) : " . $row->vad_price_rcs_tem;
                                                }
                                            }
                                        }
                                        if (!empty($row->max_date)){
                                            echo "<br><div class='gn_lastsend'>최종발송일 : " . $row->max_date . "</div>";
                                        }
                                    ?>
								</span>
                                </div>
                            </td><?//업체명?>
                			<td class="text-right">
                                <? if ($row->mem_pay_type == 'A') { ?>후불제<? } else { ?><?=number_format($this->Biz_model->getTotalDeposit($row->mem_userid))?>원
                                    <?=(!empty($row->vad_mem_id)&&$row->mem_voucher_yn=='Y')? "<br/>바우처(".number_format($this->Biz_model->getVoucherDeposit($row->mem_userid))."원)" : "" ?><? } ?>
                                    <?if (!empty($row->pre_cash)){?>
                                        <?if ($row->pre_cash > 0){?>
                                            <br/>선충전(<?=number_format($row->pre_cash)?>원)
                                        <?}?>
                                    <?}?>
                            </td><?//잔액?>
                			<td><?=$row->mem_nickname?></td><?//담당자 이름?>
                			<td><?//=$row->mem_phone?><?=$this->funn->format_phone($row->mem_emp_phone,"-")?></td><?//담당자 연락처?>
                            <td><?=$row->ds_name?></td>
                			<td><? switch($row->mem_2nd_send) { case 'GREEN_SHOT' : echo '웹(A)'; break; case 'NASELF':echo '웹(B)'; break; case 'SMART':echo '웹(C)'; break; default:echo '';} ?></td><?//2nd?>
                			<? if($this->member->item('mem_level') >= '50') { ?>

                				<td>
                					<button type="button" class="btn_st_small" id="btn_upload<?=$row->mem_id?>" onclick="event.cancelBubble=true; btn_upload_click('<?=$row->mem_id?>', '<?=$row->mem_userid?>');" value="0">고객등록</button>
                					<button type="button" class="btn_st_small" id="btn_upload<?=$row->mem_id?>" onclick="event.cancelBubble=true; btn_delete_click('<?=$row->mem_id?>', '<?=$row->mem_userid?>');" value="0" style="margin-left:5px;">고객삭제</button>
                					<input type="hidden" id="real<?=$row->mem_id?>" value="0">
                				</td>
                			<? } ?>

                			<td><?=!empty($row->mem_id) ? ($row->mdd_dormant_flag == 1 ? "휴면" : "정상" ): "정상" ?></td>
                        <? if($this->member->item('mem_id') == '3') { ?>
                            <td>
                                <? if ($row->mem_pay_type == 'B' && floor($this->Biz_model->getTotalDeposit($row->mem_userid)) > 0 && $row->mdd_dormant_flag != 1) {?>
                                <button type="button" class="btn_st_small" id="btn_transfer<?=$row->mem_id?>" onclick="event.cancelBubble=true; btn_transfer_click('<?=$row->mem_id?>', '<?=$row->mem_userid?>');" value="0" style="margin-left:5px;">이관</button>
                                <? } ?>
                            </td>
                        <? } ?>
                		</tr>
                		<?
                			}
                		?>
                	</tbody>
                </table>
                <input type="file" name="filecount" id="filecount" multiple onchange="readURL();" style="cursor: default; padding: 20px; width: 100px;display:none">
            </div>
            <div class="page_cen"><?echo $page_html?></div>
		</div>
	</div>
</div>

<!--모달-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content identify">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">
                            확인
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalCustomer" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content identify">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-primary customer_save" data-dismiss="modal" onclick="customer_save();">
                            확인
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalCheck" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default dismiss" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary submit">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalAll" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary all" onclick="all_move();">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalDel" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary del">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalSelDel" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary selDel">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalTemp" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary temp">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalUpload" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary up">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalDownload" tabindex="-1" role="dialog"
     aria-labelledby="myModalCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalCheck">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary down">확인</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal">
        <div class="modal-content">
            <br/>
            <div class="modal-body">
                <div class="content identify">
                </div>
                <div>
                    <p align="right">
                        <br/><br/>
                        <button type="button" class="btn btn-primary enter" data-dismiss="modal" id="identify">
                            취소
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--모달-->

<script>
    var genie_mem_id = "";
    var genie_mem_userid = "";
    $(document).ready(function() {
        $("#total_rows").html("<?=number_format($total_rows)?>");
    });

    $(document).on("click", "#btn_transfer", function() {
        start_transfer();
    });

    $(document).on("change", "#marttalk_list", function(e) {
        $("#marttalk_list").val($(this).val());
    });

    $('.searchBox input').unbind("keyup").keyup(function (e) {
        var code = e.which;
        if (code == 13) {
            open_page(1);
        }
    });

    $("#nav li.nav60").addClass("current open");

    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
    });

    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });

    //검색 조회
    function search_question(page) {
        open_page(page);
    }

    //검색
    function open_page(page) {
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/biz/partner/no_lists");

        var cfrsField = document.createElement("input");
        cfrsField.setAttribute("type", "hidden");
        cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(cfrsField);

        var pageField = document.createElement("input");
        pageField.setAttribute("type", "hidden");
        pageField.setAttribute("name", "page");
        pageField.setAttribute("value", page);
        form.appendChild(pageField); //현재 페이지

        var uidField = document.createElement("input");
        uidField.setAttribute("type", "hidden");
        uidField.setAttribute("name", "uid");
        uidField.setAttribute("value", $('#userid').val()); //소속
        form.appendChild(uidField);

        // var contractTypeField = document.createElement("input");
        // contractTypeField.setAttribute("type", "hidden");
        // contractTypeField.setAttribute("name", "contract_type");
        // contractTypeField.setAttribute("value", $('#contract_type').val()); //계약형태
        // form.appendChild(contractTypeField);
        //
        // var monthlyFeeYnField = document.createElement("input");
        // monthlyFeeYnField.setAttribute("type", "hidden");
        // monthlyFeeYnField.setAttribute("name", "monthly_fee_yn");
        // monthlyFeeYnField.setAttribute("value", $('#monthly_fee_yn').val()); //월사용료
        // form.appendChild(monthlyFeeYnField);
        //
        // var dormancyYnField = document.createElement("input");
        // dormancyYnField.setAttribute("type", "hidden");
        // dormancyYnField.setAttribute("name", "dormancy_yn");
        // dormancyYnField.setAttribute("value", $('#dormancy_yn').val()); //휴면
        // form.appendChild(dormancyYnField);

        // var voucherYnField = document.createElement("input");
        // voucherYnField.setAttribute("type", "hidden");
        // voucherYnField.setAttribute("name", "voucher_yn");
        // voucherYnField.setAttribute("value", $('#voucher_yn').val()); //바우처
        // form.appendChild(voucherYnField);

        var searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "search_type");
        searchTypeField.setAttribute("value", $('#searchType').val()); //검색조건
        form.appendChild(searchTypeField);

        var searchStrField = document.createElement("input");
        searchStrField.setAttribute("type", "hidden");
        searchStrField.setAttribute("name", "search_for");
        searchStrField.setAttribute("value", $('#searchStr').val()); //검색내용
        form.appendChild(searchStrField);

        form.submit();
    }

    //엑셀 다운로드
    function download_() {
        var uid = $('#userid').val();
        var contract_type = $('#contract_type').val();
        var type = $('#searchType').val();
        var searchFor = $('#searchStr').val();
        var dormancy_yn = $('#dormancy_yn').val();
        var voucher_yn = $('#voucher_yn').val();
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/biz/partner/download");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "uid");
        field.setAttribute("value", uid);
        form.appendChild(field);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "contract_type");
        field.setAttribute("value", contract_type);
        form.appendChild(field);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "search_type");
        field.setAttribute("value", type);
        form.appendChild(field);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "dormancy_yn");
        field.setAttribute("value", dormancy_yn);
        form.appendChild(field);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "voucher_yn");
        field.setAttribute("value", voucher_yn);
        form.appendChild(field);

        var resultField = document.createElement("input");
        resultField.setAttribute("type", "hidden");
        resultField.setAttribute("name", "search_for");
        resultField.setAttribute("value", searchFor);
        form.appendChild(resultField);

        form.submit();

    }

    //업로드
    var up_mem_id = -1;
    var up_mem_userid = "";

    function btn_upload_click(_mem_id, _mem_userid) {
        up_mem_id = _mem_id;
        up_mem_userid  = _mem_userid;
        var real = $("#real" + up_mem_id).val();
        if(real == 0) {
            $("#filecount").click();
        } else {
            readURL();
        }
    }

    //고객삭제
	function btn_delete_click(_mem_id, _mem_userid) {
    	 if(confirm("고객 리스트 전체를 삭제 하시겠습니까?")){
	        var formData = new FormData();
	    	formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
		    formData.append("mem_id",_mem_id);
	        formData.append("mem_userid",_mem_userid);
	        $.ajax({
	            url: "/biz/customer/deletebylist",
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
					alert("정상적으로 삭제 되었습니다.");
				}
	        });
    	 }

    }

    function readURL() {
        var file = document.getElementById('filecount').value;
        file = file.slice(file.indexOf(".") + 1).toLowerCase();
        var real = $("#real" + up_mem_id).val();

        if (file == "xls" || file == "xlsx" || file == "csv") {
			//var formUrl = "/biz/customer/uploadbylist";
			var formUrl = "/biz/customer/upload";
			var formData = new FormData();
            formData.append("file", $("input[name=filecount]")[0].files[0]);
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			formData.append("real", real);
			formData.append("mem_id",up_mem_id);
			formData.append("mem_userid",up_mem_userid);
			//alert("formUrl : "+ formUrl); return;
            $.ajax({
                url: formUrl,
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
                    var status = json['status'];
                    if (status == 'error') {
                        var msg = json['msg'];
                        $(".content").html("올바르지 않은 파일입니다.<br>"+msg);
                        $('#myModal').modal({backdrop: 'static'});
                        $('#filecount').filestyle('clear');
                    } else if (status == 'success') {
                        //var upload_count = json['nrow_len'];
                        var upload_count = json['uploaded'];
                        if(real == 0) {
                            //$("#number_add").hide();
                            //$("#number_del").hide();
                            //$("#tel").hide();
                            //$("#upload_result").remove();
                            $("#btn_upload" + up_mem_id).html(upload_count + "건 등록");
                            $("#real" + up_mem_id).val("1");
                        } else {
                            alert("고객등록 완료 되었습니다.");
                            $("#btn_upload" + up_mem_id).html("고객등록");
                            $("#real" + up_mem_id).val("0");
                        }
                        //$(".tel_content").after('<div class="widget-content" style="margin-top:-10px;" id="upload_result"><p>업로드 결과 : ' + upload_count + ' 명의 고객이 업로드 되었습니다.</p><input type="hidden" id="upload_all_send" value="' + upload_count + '"></div>');
                    }
                }
            });
        } else {
            $(".content").html("xls,xlsx 파일만 가능합니다.");
            $('#myModal').modal({backdrop: 'static'});
            $('#myModal').on('hidden.bs.modal', function () {
                $('#filecount').filestyle('clear');
            });
            $(document).unbind("keyup").keyup(function (e) {
                var code = e.which;
                if (code == 13) {
                    $(".enter").click();
                }
            });
        }
    }

    function btn_transfer_click(mem_id, mem_userid){
        $.ajax({
            url: "/biz/partner/get_marttalk_partner",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
            },
            // processData: false,
            // contentType: false,
            success: function (json) {
                genie_mem_id = mem_id;
                genie_mem_userid = mem_userid;
                $('#overlay').fadeIn();
                $(".content").html(json.modal_html);
                $('#transferModal').modal({backdrop: 'static'});
                $('#overlay').fadeOut();
            },
        });
    }

    function start_transfer(){
        if (confirm("업체를 한번 더 확인해주세요.")){
            var split_marttalk = document.getElementById("marttalk_list").value.split("_");
            $.ajax({
                url: "/biz/partner/modify_deposit",
                type: "POST",
                data: {
                    "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
                    , genie_mem_id : genie_mem_id
                    , genie_mem_userid : genie_mem_userid
                    , marttalk_mem_id : split_marttalk[0]
                    , marttalk_mem_userid : split_marttalk[1]
                    , marttalk_mem_nickname : split_marttalk[2]
                },
                // processData: false,
                // contentType: false,
                success: function (json) {
                    if (json.code == "OK"){
                        alert("이관되었습니다.\r지니 -> 마트톡\r지니 : " + json.genie_before + " -> " + json.genie_after +"\r마트톡 : " + json.marttalk_before + " -> " + json.marttalk_after);
                        location.reload();
                    } else {
                        alert("예약 및 발송 처리가 끝이나면 다시 시도해주세요.");
                    }
                },
            });
        }
    }
</script>

<style>
    .text-center {
        vertical-align: middle !important;
    }

    .text-left {
        vertical-align: middle !important;
    }
</style>
