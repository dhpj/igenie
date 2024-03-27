<!-- 타이틀 영역 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu15.php');
?>
<!-- 타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>업체 목록 (전체 <b style="color: red" id="total_rows"><?=$total_rows?></b>개)</h3>
		</div>
		<div class="white_box" id="search_box">
            <div class="search_box search_box1234">
				<label for="userid" >소속</label>
				<select name="userid" id="userid" <?//class="selectpicker"?> data-live-search="true" onChange="search_question(1);">
					<option value="ALL">- ALL -</option>
				<?foreach($users as $r) {?>
					<option value="<?=$r->mem_id?>" <?=($userid==$r->mem_id) ? 'selected' : ''?>><?=$r->mem_username?>(<?=$r->mem_userid?>)</option>
				<?}?>
				</select>
				<span style="margin-left:10px;" class="search_position1">지점</span>
				<select class="select2 input-width-medium search_position1" id="dboid" onChange="search_question(1);">
					<option value="ALL">- ALL -</option>
                <?foreach ($branch as $a){?>
                    <option value="<?=$a->dbo_id?>" <?=$a->dbo_id == $dboid ? 'selected' : ''?>><?=$a->dbo_name?></option>
                <?}?>
				</select>
				<span style="margin-left:10px;" class="search_position1">영업담당자</span><? //2021-02-09 추가 ?>
				<select class="select2 input-width-medium search_position1" id="dsid" onChange="search_question(1);">
					<option value="ALL">- ALL -</option>
                <?foreach ($salesperson as $a){?>
                    <option value="<?=$a->ds_id?>" <?=$a->ds_id == $dsid ? 'selected' : ''?>><?=$a->ds_name?></option>
                <?}?>
				</select>
				<span style="margin-left:10px;" class="search_position1">휴면상태</span><!-- 2021.12.30 조지영 추가 -->
				<select class="select2 input-width-medium search_position1" id="dormancy_yn" onChange="search_question(1);">
					<option value="ALL">- ALL -</option>
					<option value="N" <?=$dormancy_yn=='N' ? 'selected' : ''?>>정상</option>
					<option value="Y" <?=$dormancy_yn=='Y' ? 'selected' : ''?>>휴면</option>
				</select>
                <span style="margin-left:10px;" class="search_position1">마지막발송</span><? //2021-02-09 추가 ?>
				<select class="select2 input-width-medium search_position1" id="ls" onChange="search_question(1);">
					<option value="ALL">- ALL -</option>
					<option value="1" <?=$ls == '1' ? 'selected' : ''?>>1개월</option>
					<option value="2" <?=$ls == '2' ? 'selected' : ''?>>2개월</option>
                    <option value="3" <?=$ls == '3' ? 'selected' : ''?>>3개월</option>
                    <option value="6" <?=$ls == '6' ? 'selected' : ''?>>6개월</option>
                    <option value="0" <?=$ls == '0' ? 'selected' : ''?>>없음</option>
				</select>
				<select class="select2 input-width-medium search_position2" id="searchType" style="margin-left:10px;">
					<option value="username" <?=$search_type=='username' ? 'selected' : ''?>>업체명</option>
					<option value="biz_reg_no" <?=$search_type=='biz_reg_no' ? 'selected' : ''?>>사업자번호</option>
					<option value="biz_reg_name" <?=$search_type=='biz_reg_name' ? 'selected' : ''?>>상호</option>
					<option value="phone" <?=$search_type=='phone' ? 'selected' : ''?>>전화번호</option>
					<option value="nickname" <?=$search_type=='nickname' ? 'selected' : ''?>>담당자 이름</option>
					<option value="biz_reg_rep_name" <?=$search_type=='biz_reg_rep_name' ? 'selected' : ''?>>대표자 이름</option>
				</select>
				<input type="text" class="search_position2" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$search_for?>" onKeypress="if(event.keyCode==13){ search_question(1); }">
				<input type="button" class="btn md search_position2" id="check" value="조회" onclick="search_question(1)"/>
				<? if($this->member->item('mem_id')=='3') {?>
					<button type="button" class="btn md excel fr search_position3" onclick="download_();">
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
                		<col width="20%"><?//업체명?>
                        <col width="*">
                		<col width="5%"><?//담당자 이름?>
                		<col width="8%"><?//담당자 연락처?>
                		<col width="5%"><?//?>
                		<col width="10%"><?//마지막 발송일?>
                        <col width="4%"><?//휴면?>
                        <col width="4%"><?//비고?>
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
                		<th>담당 영업자</th>
                		<th>마지막 발송일</th>
                		<th>휴면상태</th>
                        <th>비고</th>
                	</tr>
                	</thead>
                	<tbody>
                		<?
                			$offer = "";
                            if (!empty($rs)){
                			foreach($rs as $row) {
                		?>
                		<tr>
                			<td><?if($offer==$row->mrg_recommend_mem_id) { echo '&nbsp;'; } else { echo $row->mrg_recommend_mem_username; $offer=$row->mrg_recommend_mem_id; }?></td><?//소속?>
                			<td><?=substr($row->mem_register_datetime, 0, 10)?></td><?//가입일?>
                			<td>
                				<input type="button" style="height:25px;" class="btn_userinsert" value="계정수정" onclick="document.location.href='/biz/partner/view?<?=$row->mem_userid?>'"/>
                				<?if($this->member->item("mem_level")>=10) {?>
                				<input type="button" style="height:25px;margin-left:5px;" class="btn_userchange" value="계정전환" onclick="document.location.href='/biz/main?<?=$row->memid?>'"/><?//계정전환?>
                				<?}?>
                			</td><?//계정관리?>
                			<td><?=$row->userid?></td><?//계정?>
                			<td><?=$row->mem_username?></td><?//업체명?>
                            <td>
                                <?
                                    if ($row->mem_pay_type == 'A') {
                                ?>
                                    후불제
                                <?
                                    } else {
                                ?>
                                    <?=number_format($this->Biz_model->getTotalDeposit($row->userid))."원"?>
                                    <?=(!empty($row->vad_mem_id)&&$row->mem_voucher_yn=='Y')? "<br/>바우처(".number_format($this->Biz_model->getVoucherDeposit($row->mem_userid))."원)" : "" ?>
                                <?
                                    }
                                ?>
                                <?
                                    if (!empty($row->pre_cash)){
                                ?>
                                    <?
                                        if ($row->pre_cash > 0){
                                    ?>
                                            <br/>선충전(<?=number_format($row->pre_cash)?>원)
                                    <?
                                        }
                                    ?>
                                <?
                                    }
                                ?>
                            </td>
                			<td><?=$row->mem_nickname?></td><?//담당자 이름?>
                			<td><?=$this->funn->format_phone($row->mem_emp_phone,"-")?></td><?//담당자 연락처?>
                			<td>
                            <?
                                foreach($salesperson as $b){
                                    if ($row->mem_management_mng_id == $b->ds_id){
                                        echo $b->ds_name;
                                    }
                                }
                            ?>
                            </td>
                			<td><?=$row->max_date?></td>
                			<td><?=!empty($row->memid) ? ($row->mdd_dormant_flag == 1 ? "휴면" : "정상" ): "정상" ?></td>
                            <td>
                                <textarea id="note_<?=$row->memid?>" style="display:none;"><?=""//$row->mem_note?></textarea>
                                <button type="button" class="btn company_lists_btn" onclick="open_note(<?=$row->memid?>)">적기</button>
                            </td>
                		</tr>
                		<?
                			}
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

<div id="modal_note" class="dh_modal modal_position" memid = "" style="display:none;">
    <div class="modal-content">
        <p class="modal-tit note_model">
            비고
            <button onclick="open_history();" class="but13">이력</button>
        </p>
        <textarea id="modal_textarea" class="company_lists_textarea">
        </textarea>
        <button onclick="save()" class="btn_apply">적용</button>
        <button onclick="close_note()" class="btn_cancel">취소</button>
    </div>
</div>

<div id="modal_history" class="dh_modal modal_position" memid = "" style="display:none;">
    <div class="modal-content">
        <p id="history_header" class="modal-tit note_model">
            히스토리
        </p>
        <button onclick="close_history()" class="btn_cancel btn_cancel1">닫기</button>
    </div>
</div>

<div id="modal_detail" class="dh_modal modal_position" memid = "" style="display:none;">
    <div class="modal-content">
        <p id="detail_header" class="modal-tit note_model">
            상세
        </p>
        <button onclick="close_detail()" class="btn_cancel btn_cancel1">닫기</button>
    </div>
</div>

<script>

    //검색 조회
    function search_question(page) {
        open_page(page);
    }

    //검색
    function open_page(page) {
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/biz/partner/company_lists");

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

        var contractTypeField = document.createElement("input");
        contractTypeField.setAttribute("type", "hidden");
        contractTypeField.setAttribute("name", "dboid");
        contractTypeField.setAttribute("value", $('#dboid').val()); //지점
        form.appendChild(contractTypeField);

        var monthlyFeeYnField = document.createElement("input");
        monthlyFeeYnField.setAttribute("type", "hidden");
        monthlyFeeYnField.setAttribute("name", "dsid");
        monthlyFeeYnField.setAttribute("value", $('#dsid').val()); //영업담당자
        form.appendChild(monthlyFeeYnField);

        var dormancyYnField = document.createElement("input");
        dormancyYnField.setAttribute("type", "hidden");
        dormancyYnField.setAttribute("name", "dormancy_yn");
        dormancyYnField.setAttribute("value", $('#dormancy_yn').val()); //휴면
        form.appendChild(dormancyYnField);

        var voucherYnField = document.createElement("input");
        voucherYnField.setAttribute("type", "hidden");
        voucherYnField.setAttribute("name", "ls");
        voucherYnField.setAttribute("value", $('#ls').val()); //마지막발송
        form.appendChild(voucherYnField);

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
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/biz/partner/download_for_company");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "uid");
        field.setAttribute("value", $('#userid').val());
        form.appendChild(field);

        var contractTypeField = document.createElement("input");
        contractTypeField.setAttribute("type", "hidden");
        contractTypeField.setAttribute("name", "dboid");
        contractTypeField.setAttribute("value", $('#dboid').val()); //지점
        form.appendChild(contractTypeField);

        var monthlyFeeYnField = document.createElement("input");
        monthlyFeeYnField.setAttribute("type", "hidden");
        monthlyFeeYnField.setAttribute("name", "dsid");
        monthlyFeeYnField.setAttribute("value", $('#dsid').val()); //영업담당자
        form.appendChild(monthlyFeeYnField);

        var dormancyYnField = document.createElement("input");
        dormancyYnField.setAttribute("type", "hidden");
        dormancyYnField.setAttribute("name", "dormancy_yn");
        dormancyYnField.setAttribute("value", $('#dormancy_yn').val()); //휴면
        form.appendChild(dormancyYnField);

        var voucherYnField = document.createElement("input");
        voucherYnField.setAttribute("type", "hidden");
        voucherYnField.setAttribute("name", "ls");
        voucherYnField.setAttribute("value", $('#ls').val()); //마지막발송
        form.appendChild(voucherYnField);

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

    function open_note(mem_id){
        modal_note.style.display = "block";
        $("#modal_note").attr("memid", mem_id);
        modal_textarea.value = $("#note_" + mem_id).val();
    }

    function close_note(){
        modal_note.style.display = "none";
    }

    function open_history(){
        $.ajax({
			url: "/biz/partner/get_note",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , "mem_id" : $("#modal_note").attr("memid")
            },
			success: function (json) {
                modal_note.style.display = "none";
                $("#exists").remove();
                $("#not_exists").remove();
                $("#history_header").after(json.msg);
                modal_history.style.display = "block";
			}
		});
    }

    function close_history(){
        modal_note.style.display = "block";
        modal_history.style.display = "none";
    }

    function open_detail(id){
        $.ajax({
			url: "/biz/partner/get_detail",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , id : id
            },
			success: function (json) {
                modal_history.style.display = "none";
                $("#detail_txt").remove();
                $("#detail_header").after(json.msg);
                modal_detail.style.display = "block";
			}
		});
    }

    function close_detail(){
        modal_detail.style.display = "none";
        modal_history.style.display = "block";
    }

    function save(){
        if (modal_textarea.value.trim() == ""){
            alert("내용을 입력해주세요.");
            return;
        }
        $.ajax({
			url: "/biz/partner/apply_note",
			type: "POST",
			data: {
                "<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>"
              , "mem_id" : $("#modal_note").attr("memid")
              , "note" : modal_textarea.value
            },
			success: function (json) {
                $("#note_" + $("#modal_note").attr("memid")).val(modal_textarea.value);
				// modal_note.style.display = "none";
                modal_textarea.value = "";
                alert("저장되었습니다.");
			}
		});
    }

    $(document).on("click", "#open_detail", function(){
        open_detail($(this).data("value"));
    });
</script>
