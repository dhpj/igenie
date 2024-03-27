<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu16.php');
?>
<div id="mArticle" class="card_wrap cardsublist">
  <div class="form_section">
    <div class="inner_tit">
      <h3>카드결제업체 (<?=number_format($total_rows)?>건)</h3>
    </div>
    <div class="white_box mg_t20">
      <div class="row_wrap">
        <div class="row_label">상세검색</div>
        <div class="row_contents">
          <select id="type" onchange='open_page(1)'>
            <option value="1" <? if($type == '1') { echo 'selected'; }?>>-전체-</option>
            <option value="2" <? if($type == '2') { echo 'selected'; }?>>현장결제</option>
            <option value="3" <? if($type == '3') { echo 'selected'; }?>>카드결제</option>
            <option value="4" <? if($type == '4') { echo 'selected'; }?>>지역화폐</option>
            <option value="5" <? if($type == '5') { echo 'selected'; }?>>계좌이체</option>
          </select>
          <select id="sc">
            <option value="1" <? if($sc == '1') { echo 'selected'; }?>>업체명</option>
          </select>
          <input type="text" id="sv" value="<?=$sv?>" style="margin-left:5px;" placeholder="검색내용" onkeypress="javascript:if(event.keyCode==13) { open_page(1); }">
          <button class="btn md dark" style="margin-left:5px;cursor:pointer;" onclick="open_page(1)">검색</button>
        </div>
      </div>
    </div>
    <div class="white_box mg_t20">
      <div class="widget-content" id="append_list">
        <div class="table_top">
        </div>
        <table class="order">
          <colgroup>
              <!-- <col width="6%"><?//?> -->
              <col width="*"><?//주문번호?>
              <col width="12%"><?//구매자명?>
              <col width="12%"><?//구매자명?>
              <col width="12%"><?//결제일?>
              <col width="12%"><?//결제일?>
              <col width="12%"><?//결제일?>
          </colgroup>
          <thead>
            <input type="hidden" id="idGoodsViewFl" value="CLOSE">
            <tr>
                <th>업체명</th>
                <th>현장결제</th>
                <th>카드결제</th>
                <th>지역화폐</th>
                <th>계좌이체</th>
                <th>매출통계</th>
            </tr>
          </thead>
          <tbody class="tbody">
  <?
        if (!empty($list)){
            foreach($list as $key => $a){
  ?>
                <tr>
                    <td><?=$a->mem_username?></td>
                    <td><?=$a->mem_shop_pay_offline_flag == 'Y' ? 'O' : 'X'?></td>
                    <td><?=$a->mem_shop_pay_inicis_flag == 'Y' ? 'O' : 'X'?></td>
                    <td><?=$a->mem_is_pay == 'Y' ? 'O' : 'X'?></td>
                    <td><?=$a->mem_is_account == 'Y' ? 'O' : 'X'?></td>
                    <td>
                        <button onclick='location.href="/biz/manager/statistics/detail/<?=$a->mem_id?>"' class="btn sm">바로가기</button>
                    </td>
                <tr>
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

	$(function() {
		$(".onchange_search").change(function(){
			open_page(1);
		});
	});

	//검색
	function open_page(page){
		var url = "?page="+ page
		url += "&sc="+ $("#sc").val() + "&sv="+ $("#sv").val() + "&type="+ $("#type").val(); //검색내용
		location.href = url;
    }
</script>
