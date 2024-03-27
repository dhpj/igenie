<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_t.php'); ?>

<div id="print_div">
  <? $title_str = explode('|', $ppd_title);?>
  <? $number = 0;
  for($ii = 1; $ii <= $goods_cnt; $ii++){?>
    <? if($ii % 3 == 1){
      $number++;?>
<div class="pop_ntype3_10 <?=$style?>" id="screen<?=$number?>">
  <? } ?>
  <input type="hidden" id="data_imgpath<?=$ii?>" value="<?=$goods_data[$ii]["imgpath"]?>">
  <input type="hidden" id="data_goods_name<?=$ii?>" value="<?=$goods_data[$ii]["name"]?>">
  <input type="hidden" id="data_goods_price<?=$ii?>" value="<?=$goods_data[$ii]["price"]?>">
  <input type="hidden" id="data_goods_option<?=$ii?>" value="<?=$goods_data[$ii]["option"]?>">
  <input type="hidden" id="data_goods_org_price<?=$ii?>" value="<?=$goods_data[$ii]["org_price"]?>">
  <div class="goods_box1" onClick="modal_open(<?=$ii?>);">
    <p id="view_goods_date" class="goods_date"<?=empty($ppd_font_size->goods_date)?"":'style="font-size:'.$ppd_font_size->goods_date.'"'?>><?=$ppd_date?></p><?//행사기간?>
  <p id="view_goods_name<?=$ii?>" class="goods_name" <?=empty($goods_data[$ii][fontsize]->goods_name)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_name.'"'?>>
    <?=($goods_data[$ii]["name"] != "") ? $goods_data[$ii]["name"] : $base_goods_name?>
  </p>
  <p  id="view_goods_option<?=$ii?>"class="goods_option" <?=empty($goods_data[$ii][fontsize]->goods_option)?"":'style="font-size:'.$goods_data[$ii][fontsize]->goods_option.'"'?>>
    <?=($blankdata=='Y') ? $goods_data[$ii]["option"] : $base_goods_option?>
  </p>
  <?=$goods_data[$ii][fontsize]->goods_org_price?>
  <p class="goods_baseprice" <?=($goods_data[$ii]["org_price"] == ""&&$blankdata=='Y') ? "style='display:none;'" : ''?>>
    <span class="base_num1" id="view_goods_org_price<?=$ii?>" <?=empty($goods_data[$ii][fontsize]->goods_org_price)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_org_price.'"'?>><?=($goods_data[$ii]["org_price"] != "") ? $goods_data[$ii]["org_price"] : $base_goods_org_price?></span> <span class="base_num2">원</span>
  </p>
  <p class="goods_price">
    <font id="view_goods_price<?=$ii?>" <?=empty($goods_data[$ii][fontsize]->goods_price)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_price.'"'?>><?=($goods_data[$ii]["price"] != "") ? $goods_data[$ii]["price"] : $base_goods_price?></font><span>원</span>
  </p>
  </div>
  <? if($ii % 3 == 0 || $ii == $goods_cnt){ ?>
</div>
  <? } } ?>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_b.php'); ?>
