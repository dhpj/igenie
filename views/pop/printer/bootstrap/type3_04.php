<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/pop/printer/bootstrap/inc/smartpop_t.php'); ?>
<div class="pop_ntype3_04" id="screen" onClick="modal_open(1);">
  <? for($ii = 1; $ii <= $goods_cnt; $ii++){ ?>
  <input type="hidden" id="data_imgpath<?=$ii?>" value="<?=$goods_data[$ii]["imgpath"]?>">
  <input type="hidden" id="data_goods_name<?=$ii?>" value="<?=$goods_data[$ii]["name"]?>">
  <input type="hidden" id="data_goods_price<?=$ii?>" value="<?=$goods_data[$ii]["price"]?>">
  <input type="hidden" id="data_goods_option<?=$ii?>" value="<?=$goods_data[$ii]["option"]?>">
  <input type="hidden" id="data_goods_org_price<?=$ii?>" value="<?=$goods_data[$ii]["org_price"]?>">
  <p id="view_goods_name<?=$ii?>" class="goods_name">
    <?=($goods_data[$ii]["name"] != "") ? $goods_data[$ii]["name"] : $base_goods_name?>
  </p>
  <p  id="view_goods_option1"class="goods_option">
    <?=($goods_data[$ii]["option"] != "") ? $goods_data[$ii]["option"] : $base_goods_option?>
  </p>
  <p class="goods_baseprice">
    <span class="base_num1" id="view_goods_org_price1"><?=($goods_data[$ii]["org_price"] != "") ? $goods_data[$ii]["org_price"] : $base_goods_org_price?></span> <span class="base_num2">원</span>
  </p>
  <p class="goods_price">
    <font id="view_goods_price<?=$ii?>"><?=($goods_data[$ii]["price"] != "") ? $goods_data[$ii]["price"] : $base_goods_price?></font><span>원</span>
  </p>
  <? } ?>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/pop/printer/bootstrap/inc/smartpop_b.php'); ?>