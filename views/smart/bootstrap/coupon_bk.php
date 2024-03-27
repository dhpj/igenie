<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<div class="wrap_smart">
  <div id="coupon_capture">
    <img id="pre_img_preview" src="<?=$data->pcd_viewpath?>">
  </div>
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
  <div class="smart_home3" <?=(!empty($this->input->get("home")))? "onClick='location.href=\"/smart/shome/".$this->input->get("home")."\";'" : "style='display:none;'" ?>><img src="/dhn/images/home_icon2.png" /></div>
 <? include_once($_SERVER['DOCUMENT_ROOT'].'/views/smart/bootstrap/_inc_smart_footer.php'); ?>
</div>
