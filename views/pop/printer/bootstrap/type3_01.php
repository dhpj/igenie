<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/pop/printer/bootstrap/inc/smartpop_t.php'); ?>
<div class="pop_ntype3_01" id="screen" onClick="modal_open(1);">
  <p class="goods_tit">
    <img src="/dhn/images/leaflets/pop/pop_bg10.jpg" alt="" />
    <span id="view_goods_tit"><?=($ppd_title != "") ? $ppd_title : $base_goods_tit?></span>
  </p>
  <? for($ii = 1; $ii <= $goods_cnt; $ii++){ ?>
  <input type="hidden" id="data_imgpath<?=$ii?>" value="<?=$goods_data[$ii]["imgpath"]?>">
  <input type="hidden" id="data_goods_name<?=$ii?>" value="<?=$goods_data[$ii]["name"]?>">
  <input type="hidden" id="data_goods_price<?=$ii?>" value="<?=$goods_data[$ii]["price"]?>">
  <p id="view_goods_name<?=$ii?>" class="goods_name">
    <?=($goods_data[$ii]["name"] != "") ? $goods_data[$ii]["name"] : $base_goods_name?>
  </p>
  <p class="goods_price">
    <font id="view_goods_price<?=$ii?>"><?=($goods_data[$ii]["price"] != "") ? $goods_data[$ii]["price"] : $base_goods_price?></font><span>원</span>
  </p>
  <? } ?> 
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/pop/printer/bootstrap/inc/smartpop_b.php'); ?>