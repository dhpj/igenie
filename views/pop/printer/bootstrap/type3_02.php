<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/pop/printer/bootstrap/inc/smartpop_t.php'); ?>
<style>
@media only print{
	.genie_container .contents_wrap{background: url(/dhn/images/leaflets/pop/pop_bg11.jpg) no-repeat bottom center; background-size: 100%;}
	.pop_ntype3_02{background:none;}
}
</style>
<div class="pop_ntype3_02" id="screen" onClick="modal_open(1);">
  <p class="goods_tit">
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
