<!-- 타이틀 영역 END -->
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>미발송업체 목록 (전체 <b style="color: red" id="total_rows"><?=$total_rows?></b>개)</h3>
		</div>
		<div class="white_box" id="search_box">
			<div class="search_box">
				<label for="userid">소속</label>
				<select name="userid" id="userid" data-live-search="true" onchange="open_page(1);">
					<option value="ALL" <?=$userid == "ALL" ? "selected" : ""?>>- ALL -</option>
        <?
            if(!empty($select)){
                foreach($select as $a){
        ?>
					<option value="<?=$a->spf_mem_id?>" <?=$userid == $a->spf_mem_id ? "selected" : ""?>><?=$a->mem_username?></option>
        <?
                }
            }
        ?>
                </select>
				<span style="margin-left:10px;">휴면상태</span>
				<select class="select2 input-width-medium" id="dormancy_yn" onchange="open_page(1);">
					<option value="ALL" <?=$dormancy_yn == "ALL" ? "selected" : ""?>>- ALL -</option>
					<option value="N" <?=$dormancy_yn == "N" ? "selected" : ""?>>정상</option>
					<option value="Y" <?=$dormancy_yn == "Y" ? "selected" : ""?>>휴면</option>
				</select>
				<select class="select2 input-width-medium" id="searchType" style="margin-left:10px;">
					<option value="userid" <?=$searchType == "userid" ? "selected" : ""?>>계정</option>
					<option value="mem_username" <?=$searchType == "mem_username" ? "selected" : ""?>>업체명</option>
					<option value="mem_nickname" <?=$searchType == "mem_nickname" ? "selected" : ""?>>담당자</option>
                    <option value="salesoffice" <?=$searchType == "salesoffice" ? "selected" : ""?>>지점</option>
					<option value="salesperson" <?=$searchType == "salesperson" ? "selected" : ""?>>영업자</option>
				</select>
				<input type="text" class="" id="searchStr" name="searchStr" placeholder="검색어 입력" value="<?=$searchStr?>" onkeypress="if(event.keyCode==13){ open_page(1); }">
				<input type="button" class="btn md" id="check" value="조회" onclick="open_page(1)">
				<button type="button" class="btn md excel fr" onclick="download();">
				<i class="icon-arrow-down"></i> 엑셀 다운로드</button>
			</div>
            <div class="table_list">
                <table>
                	<colgroup>
                		<col width="15%">
                        <col width="8%">
                        <col width="7%">
                        <col width="8%">
                        <col width="14%">
                        <col width="5%">
                        <col width="8%">
                        <col width="5%">
                        <col width="5%">
                        <col width="5%">
                        <col width="*">
                    </colgroup>
                	<thead>
                	<tr>
                		<th>소속</th>
                		<th>가입일</th>
                		<th>계정관리</th>
                		<th>계정(ID)</th>
                		<th>업체명</th>
                		<th>담당자</th>
                		<th>담당자 연락처</th>
                        <th>지점</th>
                		<th>영업자</th>
                		<th>휴면상태</th>
                        <th>비고</th>
                	</tr>
                	</thead>
                	<tbody>
                <?
                    if (!empty($list)){
                        foreach($list as $a){
                ?>
                		<tr>
                			<td><?=$a->adminname?></td>
                            <td><?=$a->mem_register_datetime?></td>
                            <td>
                				<input type="button" style="height:25px;" class="btn_userinsert" value="계정수정" onclick="document.location.href='/biz/partner/view?<?=$a->mem_userid?>'">
                            </td>
                            <td><?=$a->mem_userid?></td>
                            <td><?=$a->mem_username?></td>
                            <td><?=$a->mem_nickname?></td>
                            <td><?=$a->mem_emp_phone?></td>
                            <td><?=$a->dbo_name?></td>
                            <td><?=$a->ds_name?></td>
                			<td><?=$a->mdd_dormant_flag == "1" ? "휴면" : "정상"?></td>
                            <td>
                                <input type="text" class="note" id="<?=$a->mem_userid?>" value="<?=$a->mem_note?>" style="width:100%"/>
                            </td>
                		</tr>
                <?
                        }
                    }
                ?>
                	</tbody>
                </table>
                <input type="file" name="filecount" id="filecount" multiple="" onchange="readURL();" style="cursor: default; padding: 20px; width: 100px;display:none">
            </div>
            <div class="page_cen"><?echo $page_html?></div>
		</div>
	</div>
</div>
<script>
    $(function() {
        //추가정보 업데이트
        $(".note").change(function(){
            //console.log($(this).val());
            var id = $(this).attr("id");
            var message = $(this).val();
            $.ajax({
                url: "/dhnbiz/nosend/change_note",
                type: "POST",
                data: {"<?=$this->security->get_csrf_token_name()?>":"<?=$this->security->get_csrf_hash()?>", "mem_userid":id, "message":message},
                success: function (json) {
                    alert();
                }
            });
        });
    });

    //검색
    function open_page(page) {
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/dhnbiz/Nosend");

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
        uidField.setAttribute("name", "userid");
        uidField.setAttribute("value", $('#userid').val()); //소속
        form.appendChild(uidField);

        var dormancyYnField = document.createElement("input");
        dormancyYnField.setAttribute("type", "hidden");
        dormancyYnField.setAttribute("name", "dormancy_yn");
        dormancyYnField.setAttribute("value", $('#dormancy_yn').val()); //휴면
        form.appendChild(dormancyYnField);

        var searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "searchType");
        searchTypeField.setAttribute("value", $('#searchType').val()); //검색조건
        form.appendChild(searchTypeField);

        var searchStrField = document.createElement("input");
        searchStrField.setAttribute("type", "hidden");
        searchStrField.setAttribute("name", "searchStr");
        searchStrField.setAttribute("value", $('#searchStr').val()); //검색내용
        form.appendChild(searchStrField);

        form.submit();
    }

    //엑셀 다운로드
    function download() {
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/dhnbiz/Nosend/download_excel");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var uidField = document.createElement("input");
        uidField.setAttribute("type", "hidden");
        uidField.setAttribute("name", "uid");
        uidField.setAttribute("value", $('#userid').val()); //소속
        form.appendChild(uidField);

        var dormancyYnField = document.createElement("input");
        dormancyYnField.setAttribute("type", "hidden");
        dormancyYnField.setAttribute("name", "dormancy_yn");
        dormancyYnField.setAttribute("value", $('#dormancy_yn').val()); //휴면
        form.appendChild(dormancyYnField);

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
</script>
