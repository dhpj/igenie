<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_t.php'); ?>


<div id="print_div">
  <? $number = 0;
  for($ii = 1; $ii <= $goods_cnt; $ii++){
    $number++;?>
<div class="pop_ntype10 <?=$style?>" id="screen<?=$number?>" onClick="modal_open(<?=$ii?>);">
  <p class="goods_tit">
    <span id="view_goods_tit<?=$ii?>" <?=empty($ppd_font_size->goods_tit)?"":'style="font-size:'.$ppd_font_size->goods_tit.'"'?>><?=($ppd_title != "") ? $ppd_title : $base_goods_tit?></span>
  </p>


  <input type="hidden" id="data_imgpath<?=$ii?>" value="<?=$goods_data[$ii]["imgpath"]?>">
  <input type="hidden" id="data_goods_name<?=$ii?>" value="<?=$goods_data[$ii]["name"]?>">
  <input type="hidden" id="data_goods_price<?=$ii?>" value="<?=$goods_data[$ii]["price"]?>">
  <p class="goods_img">
    <img id="view_imgpath<?=$ii?>" src="<?=($goods_data[$ii]["imgpath"] != "") ? ((strpos($goods_data[$ii]["imgpath"], "uploads/goods/") == true)? config_item('igenie_path').$goods_data[$ii]["imgpath"] : $goods_data[$ii]["imgpath"]) : "/dhn/images/leaflets/sample_img2.jpg"?>" alt=""/>
  </p>
  <div class="goods_tbox">
  <p id="view_goods_date<?=$ii?>" class="goods_date"><?=$ppd_date?></p><?//행사기간?>
  <p id="view_goods_name<?=$ii?>" class="goods_name" <?=empty($goods_data[$ii][fontsize]->goods_name)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_name.'"'?>>
    <?=($goods_data[$ii]["name"] != "") ? $goods_data[$ii]["name"] : $base_goods_name?>
  </p>
  <p id="view_goods_price<?=$ii?>" class="goods_price" <?=empty($goods_data[$ii][fontsize]->goods_price)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_price.'"'?>>
    <?=($goods_data[$ii]["price"] != "") ? $goods_data[$ii]["price"] : $base_goods_price?>
  </p>
  </div>
  <?=$mylogotext?>
</div>
<? } ?>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_b.php'); ?>
