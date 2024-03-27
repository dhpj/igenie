<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu17.php');
?>
<div id="mArticle">
	<div class="form_section">
		<div class="inner_tit">
			<h3>상세정산 목록 (전체 <b style="color: red" id="total_rows"></b>개)</h3>
		</div>
		<div class="white_box" id="search_box">
            <div class="search_box">
                <label>기간</label>
                <span class="dateBox" style="margin-right: 10px;">
					<input type="text" class="datepicker" name="startDate" id="startDate" value="<?=$startDate;?>" readonly="readonly"> ~
					<input type="text" class="datepicker" name="endDate" id="endDate" value="<?=$endDate;?>" readonly="readonly">
			    </span>
				<input type="hidden" id="set_date" name="set_date">
				<input type="button" class="btn md" id="check" value="조회" onclick="search_question(1)"/>
				<button type="button" class="btn md excel fr" onclick="download_();">
				<i class="icon-arrow-down"></i> 엑셀 다운로드</button>
                <span class='settle_sum md fr'>합계 : <span id='settle_sum'></span></span>
			</div>
            <div class="table_list">
                <table>
                	<colgroup>
                		<col width="6%"><?//주문번호?>
                		<col width="6%"><?//구매자명?>
                        <col width="6%"><?//상품명?>
                        <col width="6%"><?//결제일?>
                        <col width="6%"><?//정산일?>
                        <col width="6%"><?//결제금액?>
                        <col width="6%"><?//수수료?>
                        <!-- <col width="6%"><?//부가가치세?> -->
                        <col width="6%"><?//할부개월?>
                        <col width="6%"><?//무이자수수료?>
                        <col width="6%"><?//정산금액?>
                        <col width="6%"><?//카드사?>
                        <col width="6%"><?//카드번호?>
                        <col width="6%"><?//승인번호?>
                        <col width="6%"><?//카드구분?>
                        <col width="6%"><?//카드형태?>
                	</colgroup>
                	<thead>
                	<tr>
                        <th>주문번호</th>
                		<th>구매자명</th>
                        <th>상품명</th>
                		<th>결제일</th>
                		<th>정산일</th>
                		<th>결제금액</th>
                        <th>수수료</th>
                		<!-- <th>부가가치세</th> -->
                		<th>할부개월</th>
                		<th>무이자수수료</th>
                        <th>정산금액</th>
                		<th>카드사</th>
                        <th>카드번호</th>
                        <th>승인번호</th>
                		<th>카드구분</th>
                		<th>카드형태</th>
                	</tr>
                	</thead>
                	<tbody>
                		<?
                            $settle_sum = 0;
                            $total_row = 0;
                            if (!empty($list)){
        			            foreach($list as $a) {
                                    if ($a->trAmt == '0' || empty($a->trAmt)) continue;
                                    $total_row++;
                                    $settle_sum += $a->payamt;
                		?>
                		<tr>
                            <td><?=$a->id?></td>
                            <td><?=$a->buyername?></td>
                            <td><?=$a->goodsname?></td>
                            <td><?=$a->create_datetime?></td>
                            <td><?=$a->settlmntDt?></td>
                            <td><?=!empty($a->trAmt) ? number_format($a->trAmt) . '원' : ''?></td>
                            <td><?=!empty($a->fee) ? number_format($a->fee + $a->vat) . '원' : ''?></td>
                            <!-- <td><?=!empty($a->vat) ? number_format($a->vat) . '원' : ''?></td> -->
                            <td><?=$a->instmntMon?></td>
                            <td><?=!empty($a->ninstFee) ? number_format($a->ninstFee) . '원' : ''?></td>
                            <td><?=!empty($a->payamt) ? number_format($a->payamt) . '원' : ''?></td>
                            <td><?=$a->cardname?></td>
                            <td><?=$a->cardno?></td>
                            <td><?=$a->appNo?></td>
                            <td><?=$a->cardcl?></td>
                            <td><?=$a->cardtype?></td>
                        </tr>
                		<?
                                }
                                $total_row = number_format($total_row);
                			} else {
                		?>
                        <tr>
                            <td colspan='15'>자료가 없습니다.</td>
                        </tr>
                        <?
                            }
                        ?>
                	</tbody>
                </table>
                <input type="file" name="filecount" id="filecount" multiple onchange="readURL();" style="cursor: default; padding: 20px; width: 100px;display:none">
            </div>
		</div>
	</div>
</div>
<script>
    $('#startDate').datepicker({
       format: "yyyy-mm-dd",
       todayHighlight: true,
       language: "kr",
       autoclose: true,
       startDate: '-36m',
       endDate: '+0d'
   }).on('changeDate', function (selected) {
       var startDate = new Date(selected.date.valueOf());
       $('#endDate').datepicker('setStartDate', startDate);
   });

    var start = $("#startDate").val();

    $('#endDate').datepicker({
       format: "yyyy-mm-dd",
       todayHighlight: true,
       language: "kr",
       autoclose: true,
       startDate: start,
       endDate: '+0d'
    }).on('changeDate', function (selected) {
       var endDate = new Date(selected.date.valueOf());
       $('#startDate').datepicker('setEndDate', endDate);
    });

    var end = $("#endDate").val();

    //검색 조회
    function search_question(page) {
        open_page(page);
    }

    //검색
    function open_page(page) {
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/card/cdetail");

        var cfrsField = document.createElement("input");
        cfrsField.setAttribute("type", "hidden");
        cfrsField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        cfrsField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(cfrsField);

        var searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "startDate");
        searchTypeField.setAttribute("value", $('#startDate').val()); //검색조건
        form.appendChild(searchTypeField);

        var searchStrField = document.createElement("input");
        searchStrField.setAttribute("type", "hidden");
        searchStrField.setAttribute("name", "endDate");
        searchStrField.setAttribute("value", $('#endDate').val()); //검색내용
        form.appendChild(searchStrField);

        form.submit();
    }

    //엑셀 다운로드
    function download_() {
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "post");
        form.setAttribute("action", "/card/cdownload");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var searchTypeField = document.createElement("input");
        searchTypeField.setAttribute("type", "hidden");
        searchTypeField.setAttribute("name", "startDate");
        searchTypeField.setAttribute("value", $('#startDate').val()); //검색조건
        form.appendChild(searchTypeField);

        var searchStrField = document.createElement("input");
        searchStrField.setAttribute("type", "hidden");
        searchStrField.setAttribute("name", "endDate");
        searchStrField.setAttribute("value", $('#endDate').val()); //검색내용
        form.appendChild(searchStrField);

        form.submit();

    }
    $(document).ready(function(){
        $('#total_rows').text('<?=$total_row?>');
        $('#settle_sum').text('<?=number_format($settle_sum) . '원'?>');
    });
</script>
