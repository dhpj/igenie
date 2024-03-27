<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu16.php');
?>
<div id="mArticle" class="card_wrap cardorder">
  <div class="form_section">
    <div class="inner_tit">
      <h3>카드결제현황 (<?=number_format($total_rows)?>건)</h3>
    </div>
    <div class="white_box mg_t20">
      <div class="mall_box_top">
        <div class="row_label">기간</div>
        <div class="row_contents">
          <input type="text" id="start_date" class="calendar hasDatepicker" value="<?=$start_date?>" placeholder="검색 시작일" style="width:110px;"> ~
          <input type="text" id="end_date" class="calendar hasDatepicker" value="<?=$end_date?>" placeholder="검색 종료일" style="width:110px;">
        </div>
      </div>
      <div class="row_wrap">
        <div class="row_label">상세검색</div>
        <div class="row_contents">
          <select id="sc">
            <option value="1" <? if($sc == '1') { echo 'selected'; }?>>업체명</option>
          </select>
          <input type="text" id="sv" value="<?=$sv?>" style="margin-left:5px;" placeholder="검색내용" onkeypress="javascript:if(event.keyCode==13) { open_page(1); }">
          <button class="btn md dark" style="margin-left:5px;cursor:pointer;" onclick="open_page(1)">검색</button>
        </div>
        <div class="fr">
            <div class='all_amt'>합계 : <i><?=number_format($sum) . '원</i> (주문취소는 뺀 금액)'?></div>
            <button class="btn md excel" onclick="download()">엑셀 다운로드</button>
        </div>
      </div>
    </div>
    <div class="white_box mg_t20">
      <div class="widget-content" id="append_list">
        <table class="order">
          <colgroup>
            <col style="width: 20%;">
            <col style="width: 20%;">
            <col style="width: 20%;">
            <col style="width: 20%;">
            <col style="width: ;">
          </colgroup>
          <thead>
            <tr>
              <th>업체명</th>
              <th>주문번호</th>
              <th>결제금액</th>
              <th>주문일시</th>
              <th>진행상태</th>
            </tr>
          </thead>
          <tbody class="tbody">
        <?
            if(!empty($list)){
                foreach($list as $a){
        ?>
            <tr>
                <td><?=$a->mem_username?></td>
                <td><?=$a->id?></td>
                <td><?=number_format($a->amt) . '원'?></td>
                <td><?=$a->creation_date?></td>
                <td><?=$this->funn->user_order_status($a->status)?></td>
            </tr>
        <?
                }
            }
        ?>
          </tbody>
        </table>
        <div class="page_cen"><?=$page_html?></div>
      </div>
    </div>
  </div>
</div>
<script>
	//기간 시작일자 클리시
	$("#start_date").datepicker({
		format: "yyyy-mm-dd", //날짜형식
		todayHighlight: true, //오늘자 색상변경
		language: "kr", //표기 언어
		//startDate: "-0d",
		//endDate: "+1m",
		autoclose: true
	});

	//기간 종료일자 클리시
	$("#end_date").datepicker({
		format: "yyyy-mm-dd", //날짜형식
		todayHighlight: true, //오늘자 색상변경
		language: "kr", //표기 언어
		//startDate: "-0d",
		endDate: "-0d",
		autoclose: true
	});

    //검색
	function open_page(page){
		var url = "?page="+ page; //현재 페이지
		if($("#per_page").val() != "") url += "&per_page="+ 20; //목록수
		if($("#start_date").val() != "") url += "&start_date="+ $("#start_date").val(); //시작일
		if($("#end_date").val() != "") url += "&end_date="+ $("#end_date").val(); //종료일
		if($("#sc").val() != "" && $("#sv").val() != "") url += "&sc="+ $("#sc").val() + "&sv="+ $("#sv").val(); //검색내용
		location.href = url;
    }

    //엑셀 다운로드
	function download(){
        var form = document.createElement("form");
        document.body.appendChild(form);
        form.setAttribute("method", "get");
        form.setAttribute("action", "/card/orderexcel");

        var scrfField = document.createElement("input");
        scrfField.setAttribute("type", "hidden");
        scrfField.setAttribute("name", "<?=$this->security->get_csrf_token_name()?>");
        scrfField.setAttribute("value", "<?=$this->security->get_csrf_hash()?>");
        form.appendChild(scrfField);

        var field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "start_date");
        field.setAttribute("value", $("#start_date").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "end_date");
        field.setAttribute("value", $("#end_date").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "sc");
        field.setAttribute("value", $("#sc").val());
        form.appendChild(field);

        field = document.createElement("input");
        field.setAttribute("type", "hidden");
        field.setAttribute("name", "sv");
        field.setAttribute("value", $("#sv").val());
        form.appendChild(field);

        form.submit();
    }
</script>
