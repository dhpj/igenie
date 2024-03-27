<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_t.php'); ?>

<div id="print_div">
  <? $title_str = explode('|', $ppd_title);?>
<? $number = 0;
for($ii = 1; $ii <= $goods_cnt; $ii++){
  $number++;?>
<div class="pop_type1_11 <?=$style?>" id="screen<?=$number?>" onClick="modal_open(<?=$ii?>);">
    <p class="goods_sale">
      <span id="view_goods_tit<?=$ii?>" <?=empty($goods_data[$ii][fontsize]->goods_tit)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_tit.'"'?>><?=($title_str[$ii] != "") ? $title_str[$ii] : $base_goods_tit?></span>
      <span class="goods_sale_s">%</span>
    </p>
    <p id="view_goods_date<?=$ii?>" class="goods_date"><?=($ppd_date != "") ? $ppd_date : $base_goods_date?></p><?//행사기간?>

  <input type="hidden" id="data_imgpath<?=$ii?>" value="<?=$goods_data[$ii]["imgpath"]?>">
  <input type="hidden" id="data_goods_name<?=$ii?>" value="<?=$goods_data[$ii]["name"]?>">
  <input type="hidden" id="data_goods_price<?=$ii?>" value="<?=$goods_data[$ii]["price"]?>">
  <input type="hidden" id="data_goods_org_price<?=$ii?>" value="<?=$goods_data[$ii]["org_price"]?>">
  <p id="view_goods_name<?=$ii?>" class="goods_name" <?=empty($goods_data[$ii][fontsize]->goods_name)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_name.'"'?>>
    <?=($goods_data[$ii]["name"] != "") ? $goods_data[$ii]["name"] : $base_goods_name?>
  </p>
  <p class="goods_baseprice" <?=($goods_data[$ii]["org_price"] == ""&&$blankdata=='Y') ? "style='display:none;'" : ''?>>
    <span class="base_num1" id="view_goods_org_price<?=$ii?>" <?=empty($goods_data[$ii][fontsize]->goods_org_price)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_org_price.'"'?>><?=($goods_data[$ii]["org_price"] != "") ? $goods_data[$ii]["org_price"] : $base_goods_org_price?></span> <span class="base_num2">원</span>
  </p>
  <p class="goods_price" >
    <?
    $price_change = '';
    if(!empty($goods_data[$ii]["price"])){
       $price1_0 = $goods_data[$ii]["price"];
      $lengthOfString = strlen($price1_0);
      $lastCharPosition = $lengthOfString-1;
      $lastChar = $price1_0[$lastCharPosition];
      if($lastChar=='원'){
        $price_change = substr($price1_0, 0, -1);
      }else{
        $price_change = $price1_0;
      }
    }
     ?>
    <font id="view_goods_price<?=$ii?>" <?=empty($goods_data[$ii][fontsize]->goods_price)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_price.'"'?>><?=($goods_data[$ii]["price"] != "") ? $price_change : $base_goods_price?></font><span>원</span>
  </p>
  <?=$mylogotext?>
</div>
<? } ?>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_b.php'); ?>
