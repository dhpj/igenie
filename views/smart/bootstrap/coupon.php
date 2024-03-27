<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<script src="/js/jquery-barcode.js"></script>
<script src="/js/jquery-barcode.min.js"></script>
<div class="wrap_smart">
  <!-- <div id="coupon_capture">
    <img id="pre_img_preview" src="<?=$data->pcd_viewpath?>">
  </div> -->

  <div class="smart_coupon_preview">
      <div id="coupon_img_append_div">
      <?
          $seq = 0;
          foreach($data as $a){
      ?>
          <div id="coupon_img_<?=$seq?>">
              <div class="wl_r_preview pop_view" id="pre_coupon_type1_<?=$seq?>"  style="display:<? if($a->pcdd_type != "1"){ ?>none<? } ?>"><?//무료증정 쿠폰 미리보기?>
                  <div class="pre_box_wrap1 <?=(!empty($a->barcode_txt)||!empty($a->barcode_img_path))? " coupon_wrap" : ""?>">
                      <!--템플릿 이미지가 들어갈 공간-->
                      <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('/images/smart_cou_bg<?=(!empty($a->barcode_txt)||!empty($a->barcode_img_path))? "3" : "1"?>.jpg') no-repeat top center;background-color:#47b5e5"></div>
                      <!--//템플릿 이미지가 들어갈 공간-->
                      <div class="pre_box1">
                          <p id="pre_pcd_date_type1_<?=$seq?>" class="pre_date speech"<? if($a->pcdd_date_size != "" and $a->pcdd_date_size != "0"){ ?> style="font-size:<?=$a->pcdd_date_size?>px;"<? } ?>><?=($a->pcdd_date == "") ? "2020.11.11. 단하루" : $a->pcdd_date?></p>
                          <p id="pre_pcd_title_type1_<?=$seq?>" class="pre_tit"<? if($a->pcdd_title_size != "" and $a->pcdd_title_size != "0"){ ?> style="font-size:<?=$a->pcdd_title_size?>px;"<? } ?>><?=($a->pcdd_title == "") ? "카톡친구 무료증정" : $a->pcdd_title?></p>
                      </div>
                      <div class="pre_box2" id="pre_goods_list_step1">
                          <div id="pre_div_preview_<?=$seq?>" class="templet_img_in3" style="background-image : url('<?=($a->pcdd_imgpath == "") ? $sample_img : $a->pcdd_imgpath?>');">
                              <img id="pre_img_preview_<?=$seq?>" style="display:none;">
                          </div>
                          <div class="barcode_box">
                              <div id="barcode_type1_<?=$seq?>"></div>
                              <img id="barcode_pre_img_type1_<?=$seq?>" src="<?=empty($a->barcode_img_path) ? "" : $a->barcode_img_path ?>"></img>
                          </div>
                          <p id="pre_pcd_option1_type1_<?=$seq?>" class="pre_goodsinfo"<? if($a->pcdd_option1_size != "" and $a->pcdd_option1_size != "0"){ ?> style="font-size:<?=$a->pcdd_option1_size?>px;"<? } ?>><?=($a->pcdd_option1 == "") ? "크리넥스 안심 키친타올" : $a->pcdd_option1?></p>
                          <p id="pre_pcd_option2_type1_<?=$seq?>" class="pre_couoption"<? if($a->pcdd_option2_size != "" and $a->pcdd_option2_size != "0"){ ?> style="font-size:<?=$a->pcdd_option2_size?>px;"<? } ?>><?=($a->pcdd_option2 == "") ? "선착순 100개 한정수량" : $a->pcdd_option2?></p>
                      </div><!--//pre_box2-->
                  </div><!--//pre_box_wrap-->
              </div><!--//wl_r_preview-->
              <div class="wl_r_preview" id="pre_coupon_type2_<?=$seq?>" style="display:<? if($a->pcdd_type != "2"){ ?>none<? } ?>"><?//가격할인 쿠폰 미리보기?>
                  <div class="pre_box_wrap2">
                      <!--템플릿 이미지가 들어갈 공간-->
                      <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('/images/smart_cou_bg2.jpg') no-repeat top center;background-color:#47b5e5"></div>
                      <!--//템플릿 이미지가 들어갈 공간-->
                      <div class="pre_box1">
                          <p id="pre_pcd_date_type2_<?=$seq?>" class="pre_date"<? if($a->pcdd_date_size != "" and $a->pcdd_date_size != "0"){ ?> style="font-size:<?=$a->pcdd_date_size?>px;"<? } ?>><?=($a->pcdd_date == "") ? "2020.11.11. 단하루" : $a->pcdd_date?></p>
                          <p id="pre_pcd_title_type2_<?=$seq?>" class="pre_tit"<? if($a->pcdd_title_size != "" and $a->pcdd_title_size != "0"){ ?> style="font-size:<?=$a->pcdd_title_size?>px;"<? } ?>><?=($a->pcdd_title == "") ? "카톡친구 무료증정" : $a->pcdd_title?></p>
                      </div>
                      <div class="pre_box2" id="pre_goods_list_step1">
                          <p id="pre_pcd_option1_type2_<?=$seq?>" class="pre_couoption1"<? if($a->pcdd_option1_size != "" and $a->pcdd_option1_size != "0"){ ?> style="font-size:<?=$a->pcdd_option1_size?>px;"<? } ?>><?=($a->pcdd_option1 == "") ? "3만원이상 구매시(1인1매)" : $a->pcdd_option1?></p>
                          <p id="pre_pcd_price_type2_<?=$seq?>" class="pre_saleprice"<? if($a->pcdd_price_size != "" and $a->pcdd_price_size != "0"){ ?> style="font-size:<?=$a->pcdd_price_size?>px;"<? } ?>><?=($a->pcdd_price == "") ? "3,000원" : $a->pcdd_price?></p>
                          <div class="barcode_box2">
                              <div id="barcode_type2_<?=$seq?>"></div>
                              <img id="barcode_pre_img_type2_<?=$seq?>" src="<?=empty($a->barcode_img_path) ? "" : $a->barcode_img_path ?>"></img>
                          </div>
                          <p id="pre_pcd_option2_type2_<?=$seq?>" class="pre_couoption2<?=$a->barcode_txt_length_flag != 0 ? " exist_barcode" : "" ?>"<? if($a->pcdd_option2_size != "" and $a->pcdd_option2_size != "0"){ ?> style="font-size:<?=$a->pcdd_option2_size?>px;"<? } ?>><?=($a->pcdd_option2 == "") ? "선착순 100명 현장할인" : $a->pcdd_option2?></p>
                      </div><!--//pre_box2-->
                  </div><!--//pre_box_wrap-->
              </div><!--//wl_r_preview-->
              <div class="maker_box_c" id="pre_coupon_type3_<?=$seq?>" style="background:<?=$a->pcdd_type == '3' ? $a->pcdd_backcolor : 'rgb(169, 212, 79)'?>;display:<? if($a->pcdd_type != "3"){ ?>none<? } ?>">
                  <p class="coupon_txt1" id='pre_pcd_message_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_message_size . 'px' : '20px'?>">
                      <?=$a->pcdd_type == '3' ? $a->pcdd_message : $this->member->item('mem_username')?>
                  </p>
                  <p class="coupon_txt2" id='pre_pcd_title_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_title_size . 'px' : '70px'?>">
                      <?=$a->pcdd_type == '3' ? $a->pcdd_title : '할인쿠폰'?>
                  </p>
                  <div class="coupon_goods_box">
                      <div class="cp_goods_top" id='pre_pcd_date_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_date_size . 'px' : '18px'?>">
                           <?=$a->pcdd_type == '3' ? $a->pcdd_date : '쿠폰사용기간 23.08.07(월)~08.08(화)'?>
                      </div>
                      <div class="cp_goods_img_box">
                          <div id="pre_div_preview3_<?=$seq?>" style="background : url('<?=$a->pcdd_type == '3' ? $a->pcdd_imgpath : '/dhn/images/leaflets/sample_img.jpg'?>');">
                              <img src="" />
                          </div>
                      </div>
                      <div class="cp_goods_info">
                          <div class="cp_gi_op" id='pre_pcd_option2_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_option2_size . 'px' : '20px'?>">
                              <?=$a->pcdd_type == '3' ? $a->pcdd_option2 : '5만원이상 구매시'?>
                          </div>
                          <div class="cp_gi_na" id='pre_pcd_option1_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_option1_size . 'px' : '40px'?>">
                              <?=$a->pcdd_type == '3' ? $a->pcdd_option1 : '신라면'?>
                          </div>
                          <div class="cp_gi_op2" id='pre_pcd_stan_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_stan_size . 'px' : '20px'?>">
                              <?=$a->pcdd_type == '3' ? $a->pcdd_stan : '5입'?>
                          </div>
                          <div class="cp_gi_dp" id='pre_pcd_dcprice_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_dcprice_size . 'px' : '40px'?>">
                              <?=$a->pcdd_type == '3' ? $a->pcdd_dcprice : '3,990원'?>
                          </div>
                          <div class="bprice">
                              <div class="cp_gi_fp" id='pre_pcd_price_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_price_size . 'px' : '25px'?>">
                                   <?=$a->pcdd_type == '3' ? $a->pcdd_price : '4,990원'?>
                              </div>
                          </div>
                      </div>
                      <div class="cp_goods_date" id='pre_pcd_ucomment_type3_<?=$seq?>' style="font-size:<?=$a->pcdd_type == '3' ? $a->pcdd_ucomment_size . 'px' : '20px'?>">
                          <?=$a->pcdd_type == '3' ? $a->pcdd_ucomment : '쿠폰이 발행되었습니다!'?>
                      </div>
                      <div id='mother_barcode_box_type3_<?=$seq?>' class="cp_barcode_img" style="padding-left:0px;display:<?=$a->barcode_txt_length_flag != '0' ? '' : 'none'?>;">
                          <div class="" style="padding:0px;overflow:auto;">
                              <div class="barcode_box3">
                                  <div id="barcode_type3_<?=$seq?>"></div>
                                  <img id="barcode_pre_img_type3_<?=$seq?>" src="<?=$a->pcdd_type == '3' ? (empty($a->barcode_img_path) ? "" : $a->barcode_img_path) : ''?>"></img>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      <?
              $seq++;
          }
      ?>
      </div>
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
      html2canvas(document.querySelector("#coupon_img_append_div"), {scale:1}).then(function(canvas){
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

<script>
$(document).ready(function(){
        if ("<?=count($data)?>" > 0){
            <?
            $seq = 0;
            foreach($data as $a){
            ?>
                $("#barcode_<?=$a->pcdd_type == '1' ? "type1" : ($a->pcdd_type == '2' ? "type2" : 'type3') ?>_<?=$seq?>").barcode(String(<?=$a->barcode_txt?>), "ean<?=$a->barcode_txt_length_flag?>",{barWidth:2, barHeight:60, fontSize:20});
            <?
                $seq++;
            }
            ?>
        }
    });
</script>
