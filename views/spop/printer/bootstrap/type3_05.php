<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_t.php'); ?>

<div id="print_div">
  <? $number = 0;
  for($ii = 1; $ii <= $goods_cnt; $ii++){
    $number++;?>
<div class="pop_ntype3_04_n <?=$style?>" id="screen<?=$number?>" onClick="modal_open(<?=$ii?>);">
    <p class="goods_tit">
      <span id="view_goods_tit<?=$ii?>" <?=empty($ppd_font_size->goods_tit)?"":'style="font-size:'.$ppd_font_size->goods_tit.'"'?>><?=($title_str[$ii] != "") ? $title_str[$ii] : $base_goods_tit?></span><?//타이틀?>
    </p>
  <p id="view_goods_date<?=$ii?>" class="goods_date"<?=empty($ppd_font_size->goods_date)?"":'style="font-size:'.$ppd_font_size->goods_date.'"'?>><?=$ppd_date?></p><?//행사기간?>

  <input type="hidden" id="data_imgpath<?=$ii?>" value="<?=$goods_data[$ii]["imgpath"]?>">
  <input type="hidden" id="data_goods_name<?=$ii?>" value="<?=$goods_data[$ii]["name"]?>">
  <input type="hidden" id="data_goods_price<?=$ii?>" value="<?=$goods_data[$ii]["price"]?>">
  <input type="hidden" id="data_goods_option<?=$ii?>" value="<?=$goods_data[$ii]["option"]?>">
  <input type="hidden" id="data_goods_org_price<?=$ii?>" value="<?=$goods_data[$ii]["org_price"]?>">
  <p id="view_goods_name<?=$ii?>" class="goods_name" <?=empty($goods_data[$ii][fontsize]->goods_name)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_name.'"'?>>
    <?=($goods_data[$ii]["name"] != "") ? $goods_data[$ii]["name"] : $base_goods_name?>
  </p>
  <p  id="view_goods_option<?=$ii?>"class="goods_option" <?=empty($goods_data[$ii][fontsize]->goods_option)?"":'style="font-size:'.$goods_data[$ii][fontsize]->goods_option.'"'?>>
    <?=($blankdata=='Y') ? $goods_data[$ii]["option"] : $base_goods_option?>

  </p>
  <p class="goods_price">
    <font id="view_goods_price<?=$ii?>" <?=empty($goods_data[$ii][fontsize]->goods_price)?"":'style="font-size:'.$goods_data[$ii][fontsize]->goods_price.'"'?>><?=($goods_data[$ii]["price"] != "") ? $goods_data[$ii]["price"] : $base_goods_price?></font><span>원</span>
  </p>
  <?=$mylogotext?>
</div>
  <? } ?>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_b.php'); ?>