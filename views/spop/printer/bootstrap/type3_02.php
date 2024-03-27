<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_t.php'); ?>

<style>
@media only print{
	.genie_container .contents_wrap{background: url(/dhn/images/leaflets/pop/pop_bg11.jpg) no-repeat bottom center; background-size: 100%;}
	.pop_ntype3_02{background:none;}
}
</style>
<div id="print_div">
  <? $title_str = explode('|', $ppd_title);?>
  <? $number = 0;
  for($ii = 1; $ii <= $goods_cnt; $ii++){
    $number++;?>
<div class="pop_ntype3_02 <?=$style?>" id="screen<?=$number?>" onClick="modal_open(<?=$ii?>);">
  <p class="goods_tit">
    <span id="view_goods_tit<?=$ii?>" <?=empty($goods_data[$ii][fontsize]->goods_tit)?"":'style="font-size:'.$goods_data[$ii][fontsize]->goods_tit.'"'?>><?=($title_str[$ii] != "") ? $title_str[$ii] : $base_goods_tit?></span>
  </p>

  <input type="hidden" id="data_imgpath<?=$ii?>" value="<?=$goods_data[$ii]["imgpath"]?>">
  <input type="hidden" id="data_goods_name<?=$ii?>" value="<?=$goods_data[$ii]["name"]?>">
  <input type="hidden" id="data_goods_price<?=$ii?>" value="<?=$goods_data[$ii]["price"]?>">
  <p id="view_goods_name<?=$ii?>" class="goods_name" <?=empty($goods_data[$ii][fontsize]->goods_name)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_name.'"'?>>
    <?=($goods_data[$ii]["name"] != "") ? $goods_data[$ii]["name"] : $base_goods_name?>
  </p>
  <p class="goods_price" >
    <font id="view_goods_price<?=$ii?>" <?=empty($goods_data[$ii][fontsize]->goods_price)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_price.'"'?>><?=($goods_data[$ii]["price"] != "") ? $goods_data[$ii]["price"] : $base_goods_price?></font><span>원</span>
  </p>

</div>
<? } ?>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_b.php'); ?>
