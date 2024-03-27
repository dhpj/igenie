<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu18.php');
?>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=9cfe8ac17b7e37cb80885155998da2cc&libraries=services"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>업체 목록 (전체 <b style="color: red" id="total_rows"><?=$total_cnt?></b>개)</h3>
		</div>
		<div class="white_box" id="search_box">
            <div class="search_box">
				<label for="userid">업체명</label>
				<input type="text" class="" id="searchStr" name="searchStr" placeholder="검색어 입력" value="" onKeypress="if(event.keyCode==13){ open_page(); }">
				<input type="button" class="btn md" id="check" value="조회" onclick="open_page()"/>
			</div>
            <div class="table_list">
                <table>
                	<colgroup>
                		<col width="*">
                		<col width="30%">
                	</colgroup>
                	<thead>
                	<tr>
                		<th>업체명</th>
                		<th>상세보기</th>
                	</tr>
                	</thead>
                	<tbody>
                    <?
                        if (!empty($list)){
                            foreach($list as $a){
                    ?>
                		<tr>
                            <td><?=$a->mem_username?></td>
                            <td><button onclick="location.href='/biz/manager/statistics/detail/<?=$a->mem_id?>'" class="btn sm">상세보기</button></td>
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
<script>
    $(document).ready(function(){
        $('#searchStr').val('<?=$searchStr?>');
        $('#searchStr').focus();
    });

    function open_page() {
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/biz/manager/statistics/companylist");

        var cfrsField = document.createElement("input");
        cfrsField.setAttribute("type", "hidden");
        cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(cfrsField);

        var searchStrField = document.createElement("input");
        searchStrField.setAttribute("type", "hidden");
        searchStrField.setAttribute("name", "searchStr");
        searchStrField.setAttribute("value", $('#searchStr').val()); //검색내용
        form.appendChild(searchStrField);

        form.submit();
    }
</script>
