<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<div class="wrap_smart">
  <? if($data->pcd_type == "1"){ //쿠폰타입(1.무료증정, 2.가격할인) ?>
  <div class="pre_box_wrap1" id="coupon_capture">
	  <div id="pre_templet_bg" class="preview_bg" style="background:url('/images/smart_cou_bg1.jpg') no-repeat top center;"></div>
	  <div class="pre_box1">
		<p id="pre_pcd_date_1" class="pre_date speech"<? if($data->pcd_date_size != "" and $data->pcd_date_size != "0"){ ?> style="font-size:<?=$data->pcd_date_size?>px;"<? } ?>><?=$data->pcd_date?></p><?//행사기간?>
		<p id="pre_pcd_title_1" class="pre_tit"<? if($data->pcd_title_size != "" and $data->pcd_title_size != "0"){ ?> style="font-size:<?=$data->pcd_title_size?>px;"<? } ?>><?=$data->pcd_title?></p><?//쿠폰제목?>
	  </div>
	  <div class="pre_box2" id="pre_goods_list_step1">
		<div id="pre_div_preview" class="templet_img_in3" style="background-image : url('<?=($data->pcd_imgpath == "") ? "/dhn/images/leaflets/sample_img.jpg" : $data->pcd_imgpath?>');">
		  <img id="pre_img_preview" style="display:none;">
		</div>
		<p id="pre_pcd_option1_1" class="pre_goodsinfo"<? if($data->pcd_option1_size != "" and $data->pcd_option1_size != "0"){ ?> style="font-size:<?=$data->pcd_option1_size?>px;"<? } ?>><?=$data->pcd_option1?></p><?//상품정보?>
		<p id="pre_pcd_option2_1" class="pre_couoption"<? if($data->pcd_option2_size != "" and $data->pcd_option2_size != "0"){ ?> style="font-size:<?=$data->pcd_option2_size?>px;"<? } ?>><?=$data->pcd_option2?></p><?//쿠폰옵션?>
	  </div>
  </div><!--//pre_box_wrap1-->
  <? }else if($data->pcd_type == "2"){ //쿠폰타입(1.무료증정, 2.가격할인) ?>
  <div class="pre_box_wrap2" id="coupon_capture">
	  <!--템플릿 이미지가 들어갈 공간-->
	  <div id="pre_templet_bg" class="preview_bg" style="background:url('/images/smart_cou_bg2.jpg') no-repeat top center;"></div>
	  <!--//템플릿 이미지가 들어갈 공간-->
	  <div class="pre_box1">
		<p id="pre_pcd_date_2" class="pre_date"<? if($data->pcd_date_size != "" and $data->pcd_date_size != "0"){ ?> style="font-size:<?=$data->pcd_date_size?>px;"<? } ?>><?=$data->pcd_date?></p><?//행사기간?>
		<p id="pre_pcd_title_2" class="pre_tit"<? if($data->pcd_title_size != "" and $data->pcd_title_size != "0"){ ?> style="font-size:<?=$data->pcd_title_size?>px;"<? } ?>><?=$data->pcd_title?></p><?//쿠폰제목?>
	  </div>
	  <div class="pre_box2" id="pre_goods_list_step1">
		<p id="pre_pcd_option1_2" class="pre_couoption1"<? if($data->pcd_option1_size != "" and $data->pcd_option1_size != "0"){ ?> style="font-size:<?=$data->pcd_option1_size?>px;"<? } ?>><?=$data->pcd_option1?></p><?//쿠폰옵션1?>
		<p id="pre_pcd_price_2" class="pre_saleprice"<? if($data->pcd_price_size != "" and $data->pcd_price_size != "0"){ ?> style="font-size:<?=$data->pcd_price_size?>px;"<? } ?>><?=$data->pcd_price?></p><?//할인금액?>
		<p id="pre_pcd_option2_2" class="pre_couoption2"<? if($data->pcd_option2_size != "" and $data->pcd_option2_size != "0"){ ?> style="font-size:<?=$data->pcd_option2_size?>px;"<? } ?>><?=$data->pcd_option2?></p><?//쿠폰옵션2?>
	  </div>
  </div><!--//pre_box_wrap2-->
  <? } ?>
  <? if($ScreenShotYn == "Y"){ //스마트쿠폰 목록 미리보기의 경우 ?>
  <button type="button" class="btnScreenShot2" id="btnScreenShot"><span class="material-icons">save_alt</span> 이미지 다운로드</button>
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2014-11-29/FileSaver.min.js"></script>-->
  <script src="/assets/js/FileSaver.min.js"></script>
  <script src="/assets/js/html2canvas1.0.0rc0/html2canvas.min.js"></script>
  <script id="rendered-js" src="/assets/js/canvas-toBlob.js"></script>
  <script id="rendered-js" src="/assets/js/dist/xlsx.full.min.js"></script>
  <script>
    $("#btnScreenShot").on("click", function(){ //스크린샷 클릭시
      html2canvas(document.querySelector("#coupon_capture"), {scale:1}).then(function(canvas){
        canvas.toBlob(function(blob){
          saveAs(blob, "coupon_capture.png"); //파일 저장
        });
      });
    ﻿});
  </script>
  <? } ?>
</div>