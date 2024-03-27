<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu1.php');
?>
<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
      <h3>개인정보동의 상세내역 (전체 <b style="color: red"><?=number_format($total_rows)?></b>건)</h3>
      <button class="btn_tr" onclick="location.href='/biz/sender/send/agree/lists';">목록으로</button>
	  <button class="btn_tr2" onclick="download();" style="margin-right:10px;">엑셀 다운로드</button>
    </div>
    <div class="white_box">
      <div class="search_wrap fr">
        <select id="search_state" onchange="open_page(1);">
          <option value="">- 상태전체 -</option>
          <option value="0"<?=($search_state == "0") ? " Selected" : ""?>>발송완료</option>
          <option value="1"<?=($search_state == "1") ? " Selected" : ""?>>확인완료</option>
          <option value="2"<?=($search_state == "2") ? " Selected" : ""?>>수신동의</option>
        </select>
        <input type="text" id="search_phn" value="<?=$search_phn?>" style="margin-left:5px;" placeholder="전화번호를 입력해 주세요" onkeypress="if(event.keyCode==13){ open_page(1); }">
        <button type="button" class="btn md" style="margin-left:5px;" onclick="open_page(1)">검색</button>
      </div>
      <div class="table_list">
        <table cellpadding="0" cellspacing="0" border="0">
          <colgroup>
            <col width="8%">
            <col width="*">
            <col width="15%">
            <col width="20%">
            <col width="20%">
            <col width="20%">
          </colgroup>
          <thead>
            <tr>
              <th>No</th>
              <th>전화번호</th>
              <th>상태</th>
              <th>발송일자</th>
              <th>확인일자</th>
              <th>수신동의일자</th>
            </tr>
          </thead>
          <tbody>
			<?
				$i = 0;
				if(!empty($list)){
					foreach($list as $r){
						$num = $total_rows-($perpage*($page-1))-$i; //순번
						$agd_phn = $this->funn->format_phone($r->agd_phn, "-"); //전화번호
						$agd_state = $r->agd_state; //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함)
						$agd_cre_date = $r->agd_cre_date; //발송일자
						$agd_view_date = ""; //확인일자
						$agd_agree_date = ""; //수신동의일자
						if($agd_state == "0"){
							$agd_state = "발송완료";
						}else{
							$agd_view_date = $r->agd_view_date; //확인일자
							if($agd_state == "1"){
								$agd_state = "확인완료";
							}else if($agd_state == "2"){
								$agd_state = "수신동의";
								$agd_agree_date = $r->agd_agree_date; //수신동의일자
							}
						}
			?>
			<tr>
				<td><?=$num //No?></td>
				<td><?=$agd_phn //전화번호?></td>
				<td><?=$agd_state //상태?></td>
				<td><?=$agd_cre_date //확인일자?></td>
				<td><?=$agd_view_date //확인일자?></td>
				<td><?=$agd_agree_date //수신동의일자?></td>
			</tr>
			<?
						$i++;
					}
				}else{
			?>
			<tr>
				<td colspan="6">no data.</td>
			</tr>
			<?
				}
			?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="page_cen"><?=$page_html?></div>
</div>
<script>
	//검색
	function open_page(page) {
		var url = "?page="+ page;
		var search_phn = $("#search_phn").val(); //연락처 검색
		if(search_phn != "") url += "&search_phn="+ search_phn;
		var search_state = $("#search_state").val(); //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색
		if(search_state != "") url += "&search_state="+ search_state;
		location.href = url;
	}

	//엑셀 다운로드
	function download() {
		var url = "/biz/sender/send/agree/download?agreeid=<?=$agreeid?>";
		var search_phn = $("#search_phn").val(); //연락처 검색
		if(search_phn != "") url += "&search_phn="+ search_phn;
		var search_state = $("#search_state").val(); //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색
		if(search_state != "") url += "&search_state="+ search_state;
		location.href = url;
	}
</script>
