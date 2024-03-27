<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/pop/printer/bootstrap/inc/smartpop_t.php'); ?>
<style>
@media only print{
	.genie_container .contents_wrap{background: url(/dhn/images/leaflets/pop/pop_bg08.jpg) no-repeat bottom left; background-size:auto 100%;}
	.pop_ntype08_bg{display: none;}
}
</style>
<div class="pop_ntype08" id="screen">
  <div class="pop_ntype08_bg">
    <img src="/dhn/images/leaflets/pop/pop_bg08.jpg" alt="" />
  </div>
  <div class="goods_box_wrap">
    <? for($ii = 1; $ii <= $goods_cnt; $ii++){ ?>
    <input type="hidden" id="data_imgpath<?=$ii?>" value="<?=$goods_data[$ii]["imgpath"]?>">
    <input type="hidden" id="data_goods_name<?=$ii?>" value="<?=$goods_data[$ii]["name"]?>">
    <input type="hidden" id="data_goods_price<?=$ii?>" value="<?=$goods_data[$ii]["price"]?>">
	<div class="goods_box<?=($ii == 2) ? "2" : "1"?>" onClick="modal_open(<?=$ii?>);">
      <p class="goods_img">
        <img id="view_imgpath<?=$ii?>" src="<?=($goods_data[$ii]["imgpath"] != "") ? $goods_data[$ii]["imgpath"] : "/dhn/images/leaflets/sample_img2.jpg"?>" alt=""/>
      </p>
      <div class="goods_box<?=($ii == 2) ? "2" : "1"?>_txt">
        <p id="view_goods_name<?=$ii?>" class="goods_name">
          <?=($goods_data[$ii]["name"] != "") ? $goods_data[$ii]["name"] : $base_goods_name?>
        </p>
        <p id="view_goods_price<?=$ii?>" class="goods_price">
          <?=($goods_data[$ii]["price"] != "") ? $goods_data[$ii]["price"] : $base_goods_price?>
        </p>
      </div>
    </div>
	<? } ?>
  </div>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/views/pop/printer/bootstrap/inc/smartpop_b.php'); ?>
