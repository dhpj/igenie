<? if($screen_data->psd_open_yn=="N"){?>
    <div style="background:#eee;height:100%;text-align:center;">
    <div style="position: absolute;
    top: 250px;
    left: 50%;
    width: 200px;
    height: 200px;
    text-align: center;font-size:18px;line-height: 23px;
    transform: translate(-50%, -50%);"><span class="material-icons" style="color:#e83143;font-size:50px;">warning</span><br/>요청하신 스마트전단은<br/> <span style="color:#e83143"><b>비공개 상태입니다.</b></span></div>
    </div>
<? }else{ ?>
<?
	$mem_stmall_yn = $shop->mem_stmall_yn; //스마트전단 주문하기 사용여부
	$psd_order_yn = $screen_data->psd_order_yn; //주문하기 사용여부
	$psd_order_sdt = $screen_data->psd_order_sdt; //주문하기 시작일자
	$psd_order_edt = $screen_data->psd_order_edt; //주문하기 종료일자
    $psd_order_st = $screen_data->psd_order_st; //주문하기 시작일자
	$psd_order_et = $screen_data->psd_order_et; //주문하기 종료일자
    $psd_order_alltime = $screen_data->psd_order_alltime;
	$stmall_yn = $this->funn->get_stmall_yn($mem_stmall_yn, $psd_order_yn, $psd_order_sdt, $psd_order_edt, $psd_order_st, $psd_order_et, $psd_order_alltime); //주문하기 사용여부
	//echo "stmall_yn : ". $stmall_yn ."<br>";
?>

<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/common.css?v=<?=date("ymdHis")?>" />
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style_A<?=($screen_data->psd_font_LN=="N")? "_n" : "" ?>.css?v=<?=date("ymdHis")?>" />
<div class="wrap_smart">
  <script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script><!-- <script src="/assets/js/kakao.min.js"></script> -->
  <? if($ScreenShotYn == "Y"){ //스마트전단 목록 미리보기의 경우 ?>
  <button type="button" class="btnScreenShot" id="btnScreenShot" style="bottom:93px;"><span class="material-icons">save_alt</span> 이미지 다운로드</button>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2014-11-29/FileSaver.min.js"></script>
  <script src="/assets/js/FileSaver.min.js"></script>
  <script src="/assets/js/html2canvas1.0.0rc0/html2canvas.min.js"></script>
  <script id="rendered-js" src="/assets/js/canvas-toBlob.js"></script>
  <script id="rendered-js" src="/assets/js/dist/xlsx.full.min.js"></script>

  <script>
    $("#btnScreenShot").on("click", function(){ //스크린샷 클릭시
        var smart_capture_html = $("#smart_capture").html();
         var smart_capture_replace = smart_capture_html.replace(/14.43.241.107/g , "igenie.co.kr");
         $("#smart_capture").html('');
         $("#smart_capture").html(smart_capture_replace);
         setTimeout(function(){
             html2canvas(document.querySelector("#smart_capture"), {scale:1}).then(function(canvas){
               canvas.toBlob(function(blob){
                 saveAs(blob, "smart_capture.png"); //파일 저장
               });
             });
       },500);
    ﻿});
    $(document).ready(function() {
            // ID를 alpreah_input로 가지는 곳에서 키를 누를 경우
            $("#search_input").keydown(function(key) {
                if (key.keyCode == 13) {
                    search_goods();
                }
            });
        });
    // $(window).load(function(){
    //     $("#search_input").on("keyup",function(key){
    //          if(key.keyCode==13) {
    //             search_goods();
    //          }
    //      });
    // });



  </script>
  <? } ?>

  <div class="smart_header">
      <!--스마트홈 사용없이 보내는 친구톡, 문자의 경우 홈아이콘 비활성-->
     <!-- <div class="smart_home" <?=($this->input->get("home"))? "onClick='location.href=\"/smart/shome/".$this->input->get("home")."\"'" : "style='display:none;'" ?>><img src="/dhn/images/home_icon1.png" /></div> -->
     <!-- <div id="link_menu" class="icon_share2"><span class="material-icons">share</span></div> -->
     <span class="logo2" onclick="search_back();"><img src="/dhn/images/logo_v3.png" alt="<?=config_item('site_name')?>"></span>
    <!--<span class="header_txt"><?=config_item('site_full_name')?></span>-->
		<div class="smart_tmenu" onclick="openNav()">
			<!-- <span>행사메뉴<span class="material-icons">arrow_forward</span></span>--> &#9776;
		</div>
        <div class="smart_search" onclick="search_box_onoff();"><span class="material-icons">search</span></div>
		<div id="mySidenav" class="sidenav">
			<span class="sidenav_txt"><span class="material-icons">store_mall_directory</span> 행사바로가기</span>
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
			<? if($screen_data->psd_step1_yn != "N"){ ?>
					<a href="javascript:void(0)" onclick="tit_text_info_click('t');"><span class="material-icons">arrow_forward</span> 할인&대표상품</a>
			<?	}
				 if(!empty($tit_text_info)){ //타이틀 텍스트 정보 체크
						foreach($tit_text_info as $r) {
							if($r->pst_tit_text_info !=''){?>

								<a href="javascript:void(0)" id ="tit_text_info_<?=$r->pst_box_no?>" onclick="tit_text_info_click(<?=$r->pst_box_no?>)"><span class="material-icons">arrow_forward</span> <?=$r->pst_tit_text_info?></a>
						<?	}
				 		}
					} ?>
		</div>
	<script>
	function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
  }
  function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  }
  var igenie_path = "<?=config_item('igenie_path')?>";
  </script>

  </div>
  <div class="smart_menu_s" style="display:none;">
    <span onclick="search_back();"><i class="material-icons">arrow_back</i></span>
    <span class="smart_title">검색</span>
  </div>
	<div class="smart_search_box" style="display:none;">
		<input type="search" value="" id="search_input" autocomplete="off" onkeypress="" onkeyup="" placeholder="검색어를 입력하세요">
		<button id="id_searchBtn" onclick="search_goods();">
				<i class="material-icons">search</i>
		</button>
	</div>
	<div class="smart_search_result" style="display:none;">
		<p class="smart_search_tit">
			<span id="search_keyword">"오뚜기"</span>에 대한 검색결과
		</p>
        <div id="search_item">
    		<dl>
    			<dt>
    				<img src="http://kakaobrandtalk.com/uploads/goods/78a834fe621dbcb2278f583e5ad31e51.jpg" />
    			</dt>
    			<dd>
    				<ul>
    					<li>오뚜기진라면 순한맛/매운맛</li>
    					<li>5,900원</li>
    					<li>5,900원</li>
    				</ul>
    			</dd>
    			<button class="icon_add_cart" onclick="addcart('2077565')">장바구니</button>
    		</dl>
        </div>
	</div>

    <div id="link_modal" class="modal">
    <div id="modal_contents" class="modal-content w80">
			<p class="share_tit">
				공유하기
			</p>
			<ul class="share_icon">
				<li>
					<a href="javascript:;" id="kakao-link-btn" onclick="close_pop();">
        	            <!-- 버튼이 생기는 부분, id는 맘대로 쓰시되 아래 js 코드도 동일하게 적용해주셔야 합니다. -->
        	            <!-- <img width="60" height="60" src="//developers.kakao.com/assets/img/about/logos/kakaolink/kakaolink_btn_medium.png" />--> <!-- 톡 이미지 부분이고, 전 kakaolink_btn_small.png로 불러왔습니다.   -->
        	  <p class="share_icon_img"><img src="/img/sp_spi_icons_kakao.png" alt=""/></p>
						<p class="share_icon_txt">
							카카오톡
						</p>
					</a>
				</li>
                <li><a href="javascript:toSNS('facebook','<?=$description;?>','<?="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>')" title="페이스북으로 가져가기"><p class="share_icon_img"><img src="/img/sp_spi_icons_face.png"></p><p class="share_icon_txt">페이스북</p></a></li>
                <li><a href="javascript:toSNS('twitter','<?=$description;?>','<?="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>')" title="트위터로 가져가기"><p class="share_icon_img"><img src="/img/sp_spi_icons_tw.png"></p><p class="share_icon_txt">트위터</p></a></li>
                <li><a href="javascript:toSNS('line','<?=$description;?>','<?="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>')" title="라인으로 가져가기"><p class="share_icon_img"><img src="/img/sp_spi_icons_line.png"></p><p class="share_icon_txt">라인</p></a></li>
                <!-- <li><a href="javascript:toSNS('pholar','공유제목!','http://단축URL')" title="폴라로 가져가기"><img src="/img/pholar.jpg"></a></li> -->
                <!-- <li><a href="javascript:toSNS('pinterest','공유제목!','http://단축URL')" title="핀터레스트로 가져가기"><img src="/img/pinterest.jpg"></a></li> -->
                <li><a href="javascript:toSNS('band','<?=$description;?>','<?="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>')" title="밴드로 가져가기"><p class="share_icon_img"><img src="/img/sp_spi_icons_band.png"></p><p class="share_icon_txt">밴드</p></a></li>
                <!-- <li><a href="javascript:toSNS('google','공유제목!','http://http://단축URL')" title="구글플러스로 가져가기"><img src="/img/google.jpg"></a></li> -->
                <!-- <li><a href="javascript:toSNS('blog','공유제목!','http://http://단축URL')" title="네이버블로그로 가져가기"><img src="/img/blog.jpg"></a></li> -->
				<li>
					<a id="clipboard_copy">
	                    <p class="share_icon_img"><img src="/img/icon_urlcopy_36.svg" alt=""/></p>
						<p class="share_icon_txt">
							URL복사
						</p>
	                </a>
				</li>
			</ul>
        <div class="share_close" onClick="close_pop();">
           <span class="pop_bt material-icons">close</span>
        </div>
    </div>
</div>
<?
     if(!empty($tit_text_info)){ //타이틀 텍스트 정보 체크
         ?>
         <div class="event_gnb">
         <?
            foreach($tit_text_info as $r) {
                if($r->pst_tit_text_info !=''){?>
                    <div  onclick="tit_text_info_click(<?=$r->pst_box_no?>)" class=""><?=$r->pst_tit_text_info?></div>
            <?	}
            }
            ?>
            </div>
            <?
    } ?>

	<div class="smart_box" style="<?=(!empty($tit_text_info))? "margin-top:105px;" : "margin-top:55px;"?> padding-bottom:70px;">
  <!-- <div class="smart_top" style="background: url(<? if($shop->mem_shop_img != ""){ ?><?=config_item('igenie_path').$shop->mem_shop_img?><? }else{ ?>/dhn/images/mi_pick.jpg<? } ?>)no-repeat; background-size: cover;">
    <a class="icon_call" href="tel:<?=$shop->mem_shop_phone?>"><img src="/images/icon_call.png"/></a>
		<p class="mart_name"><?=$shop->mem_username?></p><?//매장 이름 ?>
  </div> -->

  <div class="smart_con" id="smart_capture" style="border-top:0px;">
    <div class="wl_rbox">
      <div class="wl_r_preview">
        <div class="pre_box_wrap">
          <!--템플릿 이미지가 들어갈 공간-->
          <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('<?=($screen_data->tem_imgpath == "") ? $this->config->item('screen_tem_img') : config_item('igenie_path').$screen_data->tem_imgpath?>') no-repeat top center;background-color:<?=($screen_data->tem_bgcolor == "") ? $this->config->item('screen_tem_bgcolor') : $screen_data->tem_bgcolor?>;"></div>
          <!--//템플릿 이미지가 들어갈 공간-->
          <div class="pre_box1">
            <p id="pre_wl_tit" class="pre_tit"><?=($screen_data->psd_title == "") ? "행사제목" : $screen_data->psd_title?></p>
            <p id="pre_wl_date" class="pre_date"><?=($screen_data->psd_date == "") ? "행사기간" : $screen_data->psd_date?></p>
          </div>
          <p id="tem_self_img" class="img100" style="display:<?=($screen_data->tem_useyn == "S") ? "" : "none"?>;">
            <img src="<?=config_item('igenie_path').$screen_data->tem_imgpath?>">
          </p>
          <?
			//할인&대표상품 등록 조회 [S] ------------------------------------------------------------------------------------------------
			if($screen_data->psd_step1_yn != "N"){
		  ?>
		  <div class="pre_box2<?=($screen_data->tem_useyn == "S") ? " mg_t0" : ""?>" id="pre_goods_list_step1">
            <!--상품추가시 looping-->
            <section id="pre_area_goods_step1">
		      <?
				$ii = 0;
				if(!empty($screen_step1)){
					foreach($screen_step1 as $r) {
						$badge_rate = "";
						if($r->psg_price != "" && $r->psg_dcprice != ""){
							$goods_price = preg_replace("/[^0-9]*/s", "", $r->psg_price); //정상가 숫자만 출력
							$goods_dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
							if($goods_price != "" && $goods_dcprice != ""){
								$badge_rate = 100 - round( ( ($goods_dcprice / $goods_price) * 100) ); //할인율
							}
						}
						$psg_dcprice = $r->psg_dcprice; //STEP1 할인가
						if($psg_dcprice != ""){
							$psg_dcprice_last = mb_substr($r->psg_dcprice, -1, NULL, "UTF-8"); //할인가 마지막 단어
							if($psg_dcprice_last == "원"){ //할인가 마지막 단어가 원인 경우
								$psg_dcprice = mb_substr($r->psg_dcprice, 0 , -1, "UTF-8") . "<span class='sm_small'>원</span>"; //할인가 원에 class 주기
							}
							$dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
							if($stmall_yn == "Y" and $dcprice != "" and $dcprice != "0"){
								$cart_yn = "Y";
							}else{
								$cart_yn = "N";
							}
						}
		      ?>
		      <dl>
                <? if($cart_yn == "Y"&&$r->psg_stock != "N"){ //스마트전단 주문하기 사용의 경우 ?><button class="icon_add_cart" onclick="addcart('<?=$r->psg_id?>')">장바구니</button><? } ?>
                <dt>
                    <? if(!empty($r->psg_imgpath)&&$r->psg_imgpath != ""){
                        $file_ext = substr(strrchr($r->psg_imgpath, "."), 1);
                        $fileNameWithoutExt = substr($r->psg_imgpath, 0, strrpos($r->psg_imgpath, "."));
                        $findthumb = strripos($fileNameWithoutExt, '_thumb');
                        //echo $fileNameWithoutExt."---".$file_ext;
                        $plustext = "_thumb.";
                        if($findthumb>0){
                            $plustext = ".";
                        }
                        $imgnamepext = $fileNameWithoutExt.$plustext.$file_ext;
                    }  ?>
				  <div <? if($r->psg_badge == "0"){ ?>style="display:none;"<? }else if($r->psg_badge == "1"){ ?>class="sale_badge demo" data-effectJs="flip-left"<? }else{ ?>class="design_badge demo" data-effectJs="flip-left"<? } ?>><? if($r->psg_badge == "1"){ ?><?=$badge_rate?><span>%</span><? }else if($r->psg_badge > 1){ ?><img src="<?=config_item('igenie_path').$r->badge_imgpath?>"><? } ?></div>
				  <div id="pre_div_preview_step1_<?=$ii?>" class="templet_img_in3<?=($r->psg_stock == "N") ? " soldout" : ""?>" style="background-image : url('<?=($r->psg_imgpath == "") ? config_item('no_img_path') : config_item('igenie_path').$imgnamepext?>');">
				    <img id="pre_img_preview_step1_<?=$ii?>" style="display:none;">
				  </div>
                </dt>
			    <dd>
                  <ul>
                      <? $price_font_size="";
                    if(strlen($r->psg_dcprice)>5&&$screen_data->psd_font_LN!="N"){
                        if((50-((strlen($r->psg_dcprice)-5)*2))>=40){
                            $price_font_size="style='font-size:".(50-((strlen($r->psg_dcprice)-5)*2))."px;'";
                        }else{
                            $price_font_size="style='font-size:40px;'";
                        }

                    } ?>
                    <li class="price1" id="pre_goods_price_step1_<?=$ii?>"><?=$r->psg_price?></li><?//STEP1 정상가?>
                    <li class="price2" id="pre_goods_dcprice_step1_<?=$ii?>"  <?=$price_font_size?>><?=$psg_dcprice?></li><?//STEP1 할인가?>
                    <li><span class="pop_option" style="display:<?=($r->psg_option2 != "") ? "" : "none"?>;"><?=$r->psg_option2?></span></li><?//이미지형 옵션?>
                    <li class="name" <?=(strlen($r->psg_name.$r->psg_option)>30&&$screen_data->psd_font_LN!="N")? "style='font-size:18px'" : "" ?>>
					  <span id="pre_goods_name_step1_<?=$ii?>"><?=$r->psg_name?></span><?//STEP1 상품명?>
					  <span id="pre_goods_option_step1_<?=$ii?>"><?=$r->psg_option?></span><?//STEP1 규격?>
					</li>
				    <li id="pre_goods_imgpath_step1_<?=$ii?>" style="display:none;"><?=($r->psg_imgpath == "") ? config_item('no_img_path') : config_item('igenie_path').$r->psg_imgpath?></li><?//STEP1 이미지경로?>
                  </ul>
                </dd>
              </dl>
		      <?
						$ii++;
					} //foreach($screen_step1 as $r) {
				} //if(!empty($screen_step1)){
		      ?>
		    </section>
            <!--//상품추가시 looping-->
          </div><!--//pre_box2-->
		  <?
			} //if($screen_data->psd_step1_yn != "N"){
			//할인&대표상품 등록 조회 [E] ------------------------------------------------------------------------------------------------
		  ?>
        </div><!--//pre_box_wrap-->
        <div class="pre_box_wrap">
        <?
			//등록된 행사코너별 리스트 [S] ------------------------------------------------------------------------------------------------
			$no = 0; //순번
			$ii = 0; //코너내 순번
			$seq = 0; //코너번호
			$chk_step_no = -1; //코너별 고유번호
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > screen_box : ". count($screen_box));
			if(!empty($screen_box)){
				foreach($screen_box as $r) {
					$no++;
					$psg_step = $r->psg_step; //스텝(1.할인&대표상품, 2.2단 이미지형, 3.1단 텍스트형, 4.3단 이미지형, 5.4단 이미지형)
					$badge_rate = "";
					if($r->psg_price != "" && $r->psg_dcprice != ""){
						$goods_price = preg_replace("/[^0-9]*/s", "", $r->psg_price); //정상가 숫자만 출력
						$goods_dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
						if($goods_price != "" && $goods_dcprice){
							$badge_rate = 100 - round( ( ($goods_dcprice / $goods_price) * 100) ); //할인율
						}
					}
					$dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
					if($stmall_yn == "Y" and $dcprice != "" and $dcprice != "0"){
						$cart_yn = "Y";
					}else{
						$cart_yn = "N";
					}
                    $price_font_size = "";
					if($psg_step == "2" or $psg_step == "4" or $psg_step == "5"){ //이미지형
                        if($psg_step == "2"){ //2.2단 이미지형
							$pop_list_class = "pop_list01";
                            if(strlen($r->psg_dcprice)>5&&$screen_data->psd_font_LN!="N"){
                                $minfont = 42-((strlen($r->psg_dcprice)-5)*2);
                                if($minfont<30){
                                    $minfont = 30;
                                }
                                $price_font_size="style='font-size:".$minfont."px;'";
                            }
						}else if($psg_step == "4"){ //4.3단 이미지형
							$pop_list_class = "pop_list03";
                            if(strlen($r->psg_dcprice)>5&&$screen_data->psd_font_LN!="N"){
                                $minfont = 32-((strlen($r->psg_dcprice)-5)*1.3);
                                if($minfont<22){
                                    $minfont = 22;
                                }
                                $price_font_size="style='font-size:".$minfont."px;'";
                                // $price_font_size="style='font-size:".(32-((strlen($r->psg_dcprice)-5)*1))."px;'";


                            }
						}else if($psg_step == "5"){ //5.4단 이미지형
							$pop_list_class = "pop_list04";
                            if(strlen($r->psg_dcprice)>5&&$screen_data->psd_font_LN!="N"){
                                $minfont = 26-((strlen($r->psg_dcprice)-5)*1.3);
                                if($minfont<17){
                                    $minfont = 17;
                                }
                                $price_font_size="style='font-size:".$minfont."px;'";
                                // $price_font_size="style='font-size:".(26-((strlen($r->psg_dcprice)-5)*1))."px;'";
                            }
						}
						if((($psg_step*10) + $r->psg_step_no) != $chk_step_no){
							$ii = 0;
							$seq++;
        ?>

				<div id="pre_box_n_<?=$r->psg_box_no?>" class="pre_box3"><?//이미지형?>
					<? if($r->tit_imgpath != ""){ ?>
					<p class="pre_tit demo" data-effectJs="zoom-in-up">
						<img src="<?=config_item('igenie_path').$r->tit_imgpath?>"><?//STEP2 타이틀 이미지 ?>
					</p>
			  <? } ?>
              <div class="pop_list_wrap" >
                <!--상품추가시 looping-->
                <?
						} //if((($psg_step*10) + $r->psg_step_no) != $chk_step_no){
                ?>
                <? if(!empty($r->psg_imgpath)&&$r->psg_imgpath != ""){
                    $file_ext = substr(strrchr($r->psg_imgpath, "."), 1);
                    $fileNameWithoutExt = substr($r->psg_imgpath, 0, strrpos($r->psg_imgpath, "."));
                    $findthumb = strripos($fileNameWithoutExt, '_thumb');
                    //echo $fileNameWithoutExt."---".$file_ext;
                    $plustext = "_thumb.";
                    if($findthumb>0){
                        $plustext = ".";
                    }
                    $imgnamepext = $fileNameWithoutExt.$plustext.$file_ext;
                }  ?>

				<div class="<?=$pop_list_class?>">
				  <? if($cart_yn == "Y"&&$r->psg_stock!='N'){ //스마트전단 주문하기 사용의 경우 ?><button class="icon_add_cart" onclick="addcart('<?=$r->psg_id?>')">장바구니</button><? } ?>
				  <div <? if($r->psg_badge == "0"){ ?>style="display:none;"<? }else if($r->psg_badge == "1"){ ?>class="sale_badge demo" data-effectJs="flip-left"<? }else{ ?>class="design_badge demo" data-effectJs="flip-left"<? } ?>"><? if($r->psg_badge == "1"){ ?><?=$badge_rate?><span>%</span><? }else if($r->psg_badge > 1){ ?><img src="<?=config_item('igenie_path').$r->badge_imgpath?>"><? } ?></div>
				  <div class="templet_img_in3<?=($r->psg_stock == "N") ? " soldout" : ""?>" style="background-image : url('<?=($r->psg_imgpath == "") ? config_item('no_img_path') : config_item('igenie_path').$imgnamepext?>');">
				    <img style="display:none;">
				  </div>
				  <div class="pop_price">
				    <p class="price1"><?=$r->psg_price?></p><?//정상가?>
				    <p class="price2" <?=$price_font_size?>><?=$r->psg_dcprice?></p><?//할인가?>
				  </div>
				  <div><span class="pop_option" style="display:<?=($r->psg_option2 != ""  && $r->psg_step < 3) ? "" : "none"?>;"><?=$r->psg_option2?></span></div><?//이미지형 옵션?>
				  <div class="pop_name">
                    <span><?=$r->psg_name?></span><?//상품명?>
                    <span><?=$r->psg_option?></span><?//규격?>
                  </div>
                </div>
                <?
						$ii++; //코너별 등록수
						if($ii >= $r->rownum){
				?>
                <!--상품추가시 looping-->
  			  </div><!--//pop_list_wrap-->
            </div><!--//pre_box3-->
        <?
						} //if($ii >= $r->rownum){
						$chk_step_no = (($psg_step*10) + $r->psg_step_no);
					}else if($psg_step == "3"){ //텍스트형
						if((($psg_step*10) + $r->psg_step_no) != $chk_step_no){
							$ii = 0;
							$seq++;
		?>
						<div id="pre_box_n_<?=$r->psg_box_no?>" class="pre_box4"><?//텍스트형?>
					<? if($r->tit_imgpath != ""){ ?>
								<p class="pre_tit demo" data-effectJs="zoom-in-up" id="pre_tit_id_step3-<?=$seq?>">
									<img src="<?=config_item('igenie_path').$r->tit_imgpath?>"><?//텍스트형 타이틀 이미지 ?>
								</p>
					<? } ?>
              <div class="pop_list_wrap" id="pre_area_goods_step3-<?=$seq?>">
                <!--상품추가시 looping-->
                <?
						} //if((($psg_step*10) + $r->psg_step_no) != $chk_step_no){
						$dcprice = preg_replace("/[^0-9]*/s", "", $r->psg_dcprice); //할인가 숫자만 출력
						if($stmall_yn == "Y" and $dcprice != "" and $dcprice != "0"){
							$cart_yn = "Y";
						}else{
							$cart_yn = "N";
						}
                ?>
                <div class="pop_list02<? if($stmall_yn == "Y"){ ?> cartplus_t<? }else{?> cartplus <?} ?><?=($r->psg_stock == "N") ? " soldout" : ""?>" id="pre_step3-<?=$seq?>_<?=$ii?>">
                  <div class="name">
					<span id="pre_goods_name_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_name?></span><?//텍스트형 상품명?>
                    <span id="pre_goods_option_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_option?></span><?//텍스트형 규격?>
                  </div>
				  <span class="price1" id="pre_goods_price_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_price?></span><?//텍스트형 정상가?>
                  <span class="price2" id="pre_goods_dcprice_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_dcprice?></span><?//텍스트형 할인가?>
                  <? if($cart_yn == "Y"&&$r->psg_stock != "N"){ //스마트전단 주문하기 사용의 경우 ?><button class="icon_add_cart" onclick="addcart('<?=$r->psg_id?>')">장바구니</button><? } ?>
                </div>
                <?
						$ii++; //코너별 등록수
						if($ii >= $r->rownum){
				?>
                <!--상품추가시 looping-->
              </div>
            </div>
		<?
						} //if($ii >= $r->rownum){
						$chk_step_no = (($psg_step*10) + $r->psg_step_no);
					}else if($psg_step == "9"){ //행사이미지
						$ii = 0;
						$seq++;
		?>
            <div class="pre_box4"><?//행사이미지?>
			  <? if($r->psg_imgpath != ""){ ?>
              <p class="pre_tit">
                <img src="<?=config_item('igenie_path').$r->psg_imgpath?>"><?//행사이미지 ?>
              </p>
			  <? } ?>
            </div>
		<?
					} //}else if($psg_step == "9"){ //행사이미지
				} //foreach($screen_box as $r) {
			} //if(!empty($screen_box)){
			//등록된 행사코너별 리스트 [E] ------------------------------------------------------------------------------------------------
        ?>
        </div><!--//pre_box_wrap-->
      </div><!--//wl_r_preview-->
    </div><!--//wl_rbox-->
  </div>

  <div class="smart_info">
    <?=nl2br($shop->mem_shop_intro)?>
  </div><?//매장 소개글 ?>
  <? if($stmall_yn == "Y"){ //스마트전단 주문하기 사용의 경우 ?>
  <div class="smart_order_info">
    <p>
      <span class="material-icons">chevron_right</span><span class="fc_color_red"><?=number_format(($os->min_amt != "") ? $os->min_amt : "30000")?></span>원 이상 <strong>배달가능</strong>
	  (배달비 <?=($os->delivery_amt == "0") ? "무료" : number_format(($os->delivery_amt != "") ? $os->delivery_amt : "3000")."원"?>)
    </p>
    <? if($os->delivery_amt != "0"){ //배달비가 0이 아닌 경우 ?>
        <? if($os->free_delivery_amt < 999999){ //배달비 무료사용안 ?>
    <p>
      <span class="material-icons">chevron_right</span><span class="fc_color_red"><?=number_format(($os->free_delivery_amt != "") ? $os->free_delivery_amt : "50000")?></span>원 이상 <strong>배달비 무료</strong>
    </p>
        <? } ?>
    <? } ?>
  </div>
  <? } ?>
  <? include_once($_SERVER['DOCUMENT_ROOT'].'/views/smart/bootstrap/_inc_smart_footer.php'); ?>
  <!-- <div class="smart_bot">
	  <ul>
		<li>
		 <span class="material-icons">history</span> 운영시간 : <?=$shop->mem_shop_time?>
		</li>
		<li>
		  <span class="material-icons">history_toggle_off</span> 휴무일 : <?=$shop->mem_shop_holiday?>
		</li>
		<li>
		  <span class="material-icons">phone_in_talk</span> 전화번호 : <a href="tel:<?=$shop->mem_shop_phone?>"><?=$shop->mem_shop_phone?></a>
		</li>
		<li>
		  <span class="material-icons">place</span> 주소 : <?=$shop->mem_shop_addr?>
		</li>
	  </ul>
  </div>-->
  <!--<div class="smart_map" id="smart_map" style="width:100%;height:250px">
  </div>
  <div class="smart_footer">
     <p>Copyright © DHN Corp. All rights reserved.</p>
  </div> -->

  <div class="page_but" <?=($screen_data->psd_mem_id == "1789")? "" : "style='display:none;'" ?>> <span class="material-icons page_but_prev" onclick="pre_page('<?=$page?>');">chevron_left</span> <div class="page"><?=$page?> / <?=$total?></div> <span class="material-icons page_but_next" onclick="nex_page('<?=$page?>');">chevron_right</span> </div>

  <div class="fix_bar fix_bar_up">
	<div <?=($this->input->get("home"))? "onClick='location.href=\"/smart/shome/".$this->input->get("home")."\"'" : "style='display:none;'" ?>><i class="material-icons"><img  src="/images/smart_icon20.png" style="width:36px;height:36px;"/></i><p>홈으로</p></div>
	<div onclick="search_box_onoff();"><i class="material-icons"><img src="/images/smart_icon19.png" style="width:36px;height:36px;"/></i><p>검색</p></div>
	<div  id="link_menu"><i class="material-icons"><img src="/images/smart_icon18.png" style="width:36px;height:36px;"/></i><p>공유</p></div>
	<div><a href="tel:<?=$shop->mem_shop_phone?>"><i class="material-icons"><img src="/images/smart_icon17.png" style="width:36px;height:36px;"/></i><p>문의</p></a></div>
    <? if($stmall_yn == "Y"){ //스마트전단 주문하기 사용의 경우 ?>
	<div class="cart" onClick="cart();">
		<i class="material-icons"><img src="/images/smart_icon23.png" style="width:36px;height:36px;"/></i><p>장바구니</p>
		<em class="count" id="cart_count"><? if(!empty($_SESSION['user_cart'])){ echo $this->Cart_model->cartqty(); }else{ echo 0; } ?></em>
	</div>
    <? } ?>
</div>
	</div><!--//smart_box-->
</div><!--//wrap_smart-->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=config_item('kakao_map_appkey')?>&libraries=services"></script>
<script>
	//카카오맵 - 지도
	// var mapContainer = document.getElementById('smart_map'), // 지도를 표시할 div
	// mapOption = {
	// 	center: new kakao.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
	// 	level: 3 // 지도의 확대 레벨
	// };
    //
	// // 지도를 생성합니다
	// var map = new kakao.maps.Map(mapContainer, mapOption);
    //
	// // 주소-좌표 변환 객체를 생성합니다
	// var geocoder = new kakao.maps.services.Geocoder();
    //
	// // 주소로 좌표를 검색합니다
	// geocoder.addressSearch('<?=$shop->mem_shop_addr?>', function(result, status) {
	// 	// 정상적으로 검색이 완료됐으면
	// 	 if (status === kakao.maps.services.Status.OK) {
	// 		var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
	// 		// 결과값으로 받은 위치를 마커로 표시합니다
	// 		var marker = new kakao.maps.Marker({
	// 			map: map,
	// 			position: coords
	// 		});
	// 		// 인포윈도우로 장소에 대한 설명을 표시합니다
	// 		var infowindow = new kakao.maps.InfoWindow({
	// 			content: '<div style="width:200px;text-align:center;padding:3px 0; font-size:14px;"><?=$shop->mem_username?></div>'
	// 		});
	// 		infowindow.open(map, marker);
	// 		// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
	// 		map.setCenter(coords);
	// 	}
	// });

	//장바구니 추가
	function addcart(goodsid){
		//alert("addcart(goodsid) > goodsid : "+ goodsid); return;
		var qty = 1; //주문갯수
		$.ajax({
			url: "/smart/addcart",
			type: "POST",
			data: {"shop_mem_id" : "<?=$shop->mem_id?>", "psdid" : "<?=$screen_data->psd_id?>", "goodsid" : goodsid, "qty" : qty, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json){
				if(json.code == "0"){
					$("#cart_count").html(json.cartcnt); //주문건수 표기
					showSnackbar("장바구니에 추가되었습니다.", 1000);
				} else {
					showSnackbar("장바구니에 담지 못했습니다.<br>"+ json.message, 2000);
				}
			}
		});
	}

    function pre_page(ipage){
        if(ipage>1){
            var ipage = Number(ipage)-1;
            open_page(ipage);
        }
    }

    function nex_page(ipage){
        var whole_page = "<?=$total?>";
        if(ipage<whole_page){
            var ipage = Number(ipage)+1;
            open_page(ipage);
        }
    }

    function open_page(page) {
        var home_code = "<?=$this->input->get("home")?>";
        var pages = "";
        var url = "/smart/view/<?=$code?>";
        var Home = "<?=$this->input->get("home")?>";
        if(Home != "") {
            url += "?home="+ Home+"&page="+ page;
        }else{
            url += "?page="+ page;
        }
		location.href = url;
	}

	//장바구니 이동
	function cart(){
		var url = "/smart/cart?code=<?=$code?>";
		var ScreenShotYn = "<?=$ScreenShotYn?>";
        var Home = "<?=$this->input->get("home")?>";
        if(Home != "") url += "&home="+ Home;
		if(ScreenShotYn != "") url += "&ScreenShotYn="+ ScreenShotYn;
		location.href = url;
	}

	//바로가기 클릭이벤트
	function tit_text_info_click(num){
		if(num=="t"){
			var target =	document.getElementById("pre_templet_bg");
		}else{
			var target =	document.getElementById("pre_box_n_"+num);
		}
		//바로가기위치 상대좌표 위치
		var clientRect = target.getBoundingClientRect();
		var relativeTop = clientRect.top;
		//바로가기위치 절대좌표 위치
		var scrolledTopLength = window.pageYOffset;
		var absoluteTop = scrolledTopLength + relativeTop;
		//바로가기위치 고정바 제외
		var menuRelativeTop = absoluteTop - 103;
		window.scrollTo(0,menuRelativeTop);
		closeNav();
	}

    function search_box_onoff(){

        if($(".smart_menu_s").is(':visible') == false){
            $(".smart_menu_s").show();
            $(".smart_search_box").show();
            if($("#search_input").val()!=""){
                $(".smart_search_result").show();
                $(".smart_box").hide();
            }
            $(".smart_top").css("margin-top", "161px");
        }else{
            $(".smart_menu_s").hide();
            $(".smart_search_box").hide();
            $(".smart_search_result").hide();
            $(".smart_top").css("margin-top", "");
            $(".smart_box").show();
        }

    }

    function search_goods(){
		//alert("addcart(goodsid) > goodsid : "+ goodsid); return;
		var word = $("#search_input").val(); //검색어
        word = word.trim();
        if(word==""){
            showSnackbar("검색어를 입력하세요.", 1000);
            // alert("검색어를 입력하세요");
            $("#search_input").focus();
            return;
        }
        var html ="";
		$.ajax({
			url: "/smart/search_goods",
			type: "POST",
			data: {"word" : word, "psdid" : "<?=$screen_data->psd_id?>", "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json){
				if(json.code == "0"){
					// $("#cart_count").html(json.cartcnt); //주문건수 표기
					// showSnackbar(".", 1000);
                    $.each(json.search, function(index, item) { // 데이터 =item
                        html += "<dl>";
                        html += "<dt><img src='"
                        if(item.psg_imgpath){
                            var file_ext = item.psg_imgpath.substring(item.psg_imgpath.lastIndexOf('.'));
                            var file_name = item.psg_imgpath.substring(0, item.psg_imgpath.lastIndexOf('.'));

                            var temp_psg_imgpath = "";
							if (item.psg_imgpath.lastIndexOf('_thumb') > 0) {
								temp_psg_imgpath = item.psg_imgpath;
							} else {
								temp_psg_imgpath = file_name + '_thumb' + file_ext;
							}

                            // html += item.psg_imgpath;
                            html += igenie_path + temp_psg_imgpath;
                        }else{
                            html += "/dhn/images/leaflets/sample_img.jpg";
                        }
                        html += "' /></dt>";
                        html += "<dd><ul>";
                        html += "<li>"+item.psg_name+ " " + item.psg_option + "</li>";
                        html += "<li>"+item.psg_price+"</li>";
                        html += "<li>"+item.psg_dcprice+"</li>";
                        html += "</ul></dd>";
                        <? if($cart_yn == "Y"&&$r->psg_stock != "N"){ //스마트전단 주문하기 사용의 경우 ?>
                        if(item.psg_stock=="Y"){
                            html += "<button class='icon_add_cart' onclick='addcart(\""+item.psg_id+"\")'>장바구니</button>";
                        }
                        <? } ?>
                        html += "</dl>";
    					 // index가 끝날때까지
    					// $("#demo").append(item.name + " ");
    					// $("#demo").append(item.age + " ");
    					// $("#demo").append(item.address + " ");
    					// $("#demo").append(item.phone + "<br>");
    				});
                    if(html){
                        $("#search_item").html(html);
                    }else{
                        $("#search_item").html("검색결과가 없습니다.");
                    }
                    $("#search_keyword").text("\""+word+"\"");
                    $(".smart_search_result").show();
                    $(".smart_box").hide();

				} else {
					showSnackbar("검색 결과가 없습니다.", 2000);
				}
			}
		});
	}

    function search_back(){
        $(".smart_menu_s").hide();
        $(".smart_search_box").hide();
        $(".smart_search_result").hide();
        $(".smart_top").css("margin-top", "");
        $("#search_item").html("");
        $("#search_input").val("");
        $(".smart_box").show();
    }



    // $(document).on("click", function(e){
    //     if (e.target.id != "modal_contents" && e.target.id != "link_menu"){
    //         $('#link_modal').hide();
    //     }
    // });

    function copyText() {
      const temp = document.createElement('textarea');

      document.body.appendChild(temp);

      temp.value = '<?=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>';
      temp.select();

      document.execCommand('copy');
      document.body.removeChild(temp);

      showSnackbar("클립보드에 복사되었습니다.", 1500);
      $('#link_modal').hide();
    }



    $( document ).ready(function() {
        const button = document.querySelector('#clipboard_copy');

        button.addEventListener('click', copyText);
        $('#link_menu').on('click', function() {
            $('#link_modal').show();
        });
    });
    function close_pop() {
        $('#link_modal').hide();
    };

     Kakao.init('<?=config_item('kakao_map_appkey')?>');
     // // 카카오링크 버튼을 생성합니다. 처음 한번만 호출하면 됩니다.
     Kakao.Link.createDefaultButton({
       container: '#kakao-link-btn',  // 컨테이너는 아까 위에 버튼이 쓰여진 부분 id
       objectType: 'feed',
       content: {  // 여기부터 실제 내용이 들어갑니다.
         title: '<?=$screen_data->psd_title?>', // 본문 제목
         description: '<?=$shop->mem_username?> 스마트전단',  // 본문 바로 아래 들어가는 영역?
         imageUrl: '<? if($shop->mem_shop_img != ""){ ?><?=config_item("base_url")?><?=$shop->mem_shop_img?><? }else{ ?>/dhn/images/mi_pick.jpg<? } ?>', // 이미지
         link: {
           mobileWebUrl: '<?=current_url()?>',
           webUrl: '<?=current_url()?>'
         }
       },
       buttons: [
         {
           title: '바로가기',
           link: {
             mobileWebUrl: '<?=current_url()?>',
             webUrl: '<?=current_url()?>'
           }
         },
       ]
     });


     function toSNS(sns, strTitle, strURL) {
         var snsArray = new Array();
         var strMsg = strTitle + " " + strURL;
         // var image = "이미지경로";

         snsArray['twitter'] = "http://twitter.com/home?status=" + encodeURIComponent(strTitle) + ' ' + encodeURIComponent(strURL);
         snsArray['facebook'] = "http://www.facebook.com/share.php?u=" + encodeURIComponent(strURL);
         // snsArray['pinterest'] = "http://www.pinterest.com/pin/create/button/?url=" + encodeURIComponent(strURL) + "&media=" + image + "&description=" + encodeURIComponent(strTitle);
         snsArray['band'] = "http://band.us/plugin/share?body=" + encodeURIComponent(strTitle) + "  " + encodeURIComponent(strURL) + "&route=" + encodeURIComponent(strURL);
         // snsArray['blog'] = "http://blog.naver.com/openapi/share?url=" + encodeURIComponent(strURL) + "&title=" + encodeURIComponent(strTitle);
         snsArray['line'] = "http://line.me/R/msg/text/?" + encodeURIComponent(strTitle) + " " + encodeURIComponent(strURL);
         // snsArray['pholar'] = "http://www.pholar.co/spi/rephol?url=" + encodeURIComponent(strURL) + "&title=" + encodeURIComponent(strTitle);
         // snsArray['google'] = "https://plus.google.com/share?url=" + encodeURIComponent(strURL) + "&t=" + encodeURIComponent(strTitle);
         window.open(snsArray[sns]);
     }

</script><? //$_SESSION['user_cart'] = ""; ?>
 <? if($ScreenShotYn == "Y"){ //스마트전단 목록 미리보기의 경우 ?>
<link rel="stylesheet" href="/views/smart/bootstrap/css/effect-js.min.css" />
<script src="/views/smart/bootstrap/css/effect-js.min.js"></script>
<? } ?>
<? } ?>
