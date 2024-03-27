<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_t.php'); ?>


<div id="print_div">
  <? $title_str = explode('|', $ppd_title);?>
  <? $number = 0;
  for($ii = 1; $ii <= $goods_cnt; $ii++){ ?>
    <? if($ii % 3 == 1){
      $number++;?>
<div class="pop_ctype02 <?=$style?>" id="screen<?=$number?>">
<? } ?>

    <input type="hidden" id="data_imgpath<?=$ii?>" value="<?=$goods_data[$ii]["imgpath"]?>">
    <input type="hidden" id="data_goods_name<?=$ii?>" value="<?=$goods_data[$ii]["name"]?>">
    <input type="hidden" id="data_goods_price<?=$ii?>" value="<?=$goods_data[$ii]["price"]?>">
    <div class="goods_box1" onClick="modal_open(<?=$ii?>);">
      <p class="goods_img goods_img_d">
        <img id="view_imgpath<?=$ii?>" src="<?=($goods_data[$ii]["imgpath"] != "") ? ((strpos($goods_data[$ii]["imgpath"], "uploads/goods/") == true)? config_item('igenie_path').$goods_data[$ii]["imgpath"] : $goods_data[$ii]["imgpath"]) : "/dhn/images/leaflets/sample_img2.jpg"?>" alt=""/>
      </p>
      <div class="goods_box1_txt">
        <p id="view_goods_price<?=$ii?>" class="goods_price" <?=empty($goods_data[$ii][fontsize]->goods_price)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_price.'"'?>>
          <?=($goods_data[$ii]["price"] != "") ? $goods_data[$ii]["price"] : $base_goods_price?>
        </p>
        <p id="view_goods_name<?=$ii?>" class="goods_name" <?=empty($goods_data[$ii][fontsize]->goods_name)?'':'style="font-size:'.$goods_data[$ii][fontsize]->goods_name.'"'?>>
          <?=($goods_data[$ii]["name"] != "") ? $goods_data[$ii]["name"] : $base_goods_name?>
        </p>
      </div>
      <?=$mylogotext?>
    </div>

  <p id="view_goods_date" class="goods_date"><?=$ppd_date?></p><?//행사기간?>
  <? if($ii % 3 == 0 || $ii == $goods_cnt){ ?>
</div>
<? } } ?>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/spop/printer/bootstrap/inc/smartpop_b.php'); ?>
