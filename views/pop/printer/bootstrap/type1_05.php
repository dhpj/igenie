<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/pop/printer/bootstrap/inc/smartpop_t.php'); ?>
<div class="pop_ntype05" id="screen">
  <p class="goods_tit">
    <span id="view_goods_tit"><?=($ppd_title != "") ? $ppd_title : $base_goods_tit?></span>
  </p>
  <? for($ii = 1; $ii <= $goods_cnt; $ii++){ ?>
  <input type="hidden" id="data_imgpath<?=$ii?>" value="<?=$goods_data[$ii]["imgpath"]?>">
  <input type="hidden" id="data_goods_name<?=$ii?>" value="<?=$goods_data[$ii]["name"]?>">
  <input type="hidden" id="data_goods_price<?=$ii?>" value="<?=$goods_data[$ii]["price"]?>">
  <div class="goods_box<?=($ii % 2 == 0) ? "2" : "1"?>" onClick="modal_open(<?=$ii?>);">
    <p class="goods_img goods_img_d">
      <img id="view_imgpath<?=$ii?>" src="<?=($goods_data[$ii]["imgpath"] != "") ? $goods_data[$ii]["imgpath"] : "/dhn/images/leaflets/sample_img2.jpg"?>" alt=""/>
    </p>
    <div class="goods_box<?=($ii % 2 == 0) ? "2" : "1"?>_txt">
      <p id="view_goods_price<?=$ii?>" class="goods_price">
        <?=($goods_data[$ii]["price"] != "") ? $goods_data[$ii]["price"] : $base_goods_price?>
      </p>
      <p id="view_goods_name<?=$ii?>" class="goods_name">
        <?=($goods_data[$ii]["name"] != "") ? $goods_data[$ii]["name"] : $base_goods_name?>
      </p>
    </div>
  </div>
  <? } ?>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/pop/printer/bootstrap/inc/smartpop_b.php'); ?>