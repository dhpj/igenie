<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<div class="wrap_smart">
  <? if($ScreenShotYn == "Y"){ //스마트전단 목록 미리보기의 경우 ?>
  <button type="button" class="btnScreenShot" id="btnScreenShot"><span class="material-icons">save_alt</span> 이미지 다운로드</button>
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2014-11-29/FileSaver.min.js"></script>-->
  <script src="/assets/js/FileSaver.min.js"></script>
  <script src="/assets/js/html2canvas1.0.0rc0/html2canvas.min.js"></script>
  <script id="rendered-js" src="/assets/js/canvas-toBlob.js"></script>
  <script id="rendered-js" src="/assets/js/dist/xlsx.full.min.js"></script>
  <script>
    $("#btnScreenShot").on("click", function(){ //스크린샷 클릭시
      html2canvas(document.querySelector("#smart_capture"), {scale:1}).then(function(canvas){
        canvas.toBlob(function(blob){
          saveAs(blob, "smart_capture.png"); //파일 저장
        });
      });
    ﻿});
  </script>
  <? } ?>
  <div class="smart_header">
    <span class="logo"><img src="/dhn/images/logo_v2.png" alt="지니"></span>
    <span class="header_txt">AI 스마트 매장관리시스템</span>
  </div>
  <div class="smart_top" style="background: url(<? if($shop->mem_shop_img != ""){ ?><?=$shop->mem_shop_img?><? }else{ ?>/dhn/images/mi_pick.jpg<? } ?>)no-repeat; background-size: cover;">
    <p class="mart_name"><?=$shop->mem_username?></p><?//매장 이름 ?>
  </div>
  <div class="smart_info"><?=nl2br($shop->mem_shop_intro)?></div><?//매장 소개글 ?>
  <div class="smart_con" id="smart_capture">
    <div class="wl_rbox">
      <div class="wl_r_preview">
        <div class="pre_box_wrap">
          <!--템플릿 이미지가 들어갈 공간-->
          <div id="pre_templet_bg" class="wl_r_preview_bg" style="background:url('<?=($screen_data->tem_imgpath == "") ? "/dhn/images/leaflets/tem/tem01.jpg" : $screen_data->tem_imgpath?>') no-repeat top center;background-color:<?=$screen_data->tem_bgcolor?>;"></div>
          <!--//템플릿 이미지가 들어갈 공간-->
          <div class="pre_box1">
            <p id="pre_wl_tit" class="pre_tit"><?=($screen_data->psd_title == "") ? "행사제목" : $screen_data->psd_title?></p>
            <p id="pre_wl_date" class="pre_date"><?=($screen_data->psd_date == "") ? "행사기간" : $screen_data->psd_date?></p>
          </div>
          <?
			//할인&대표상품 등록 조회 [S] ------------------------------------------------------------------------------------------------
			if($screen_data->psd_step1_yn != "N"){
		  ?>
		  <div class="pre_box2" id="pre_goods_list_step1">
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
								$psg_dcprice = mb_substr($r->psg_dcprice, 0 , -1, "UTF-8") . "<span class='sm_small'>원</span>";
							}
						}
		      ?>
		      <dl id="pre_step1_<?=$ii?>">
                <dt>
				  <div class="sale_badge" style="display:<? if($r->psg_badge == "0"){ ?>none<? } ?>;"><?=$badge_rate?><span>%</span></div>
				  <div id="pre_div_preview_step1_<?=$ii?>" class="templet_img_in3" style="background-image : url('<?=($r->psg_imgpath == "") ? "/dhn/images/leaflets/sample_img.jpg" : $r->psg_imgpath?>');">
				    <img id="pre_img_preview_step1_<?=$ii?>" style="display:none;">
				  </div>
                </dt>
			    <dd>
                  <ul>
                    <li class="price1" id="pre_goods_price_step1_<?=$ii?>"><?=$r->psg_price?></li><?//STEP1 정상가?>
                    <li class="price2" id="pre_goods_dcprice_step1_<?=$ii?>"><?=$psg_dcprice?></li><?//STEP1 할인가?>
                    <li class="name">
					  <span id="pre_goods_name_step1_<?=$ii?>"><?=$r->psg_name?></span><?//STEP1 상품명?>
					  <span id="pre_goods_option_step1_<?=$ii?>"><?=$r->psg_option?></span><?//STEP1 옵션명?>
					</li>
				    <li id="pre_goods_imgpath_step1_<?=$ii?>" style="display:none;"><?=($r->psg_imgpath == "") ? "/dhn/images/leaflets/sample_img.jpg" : $r->psg_imgpath?></li><?//STEP1 이미지경로?>
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
					$psg_step = $r->psg_step; //스텝(1.할인&대표상품, 2.이미지형, 3.텍스트형)
					if($psg_step == "2"){ //이미지형
						if((($psg_step*10) + $r->psg_step_no) != $chk_step_no){
							$ii = 0;
							$seq++;
        ?>
			<div class="pre_box3"><?//이미지형?>
			  <? if($r->tit_imgpath != ""){ ?>
			  <p class="pre_tit" id="pre_tit_id_step2-<?=$seq?>">
			    <img src="<?=$r->tit_imgpath?>"><?//STEP2 타이틀 이미지 ?>
  			  </p>
			  <? } ?>
              <div class="pop_list_wrap" id="pre_area_goods_step2-<?=$seq?>">
                <!--상품추가시 looping-->
                <?
						} //if((($psg_step*10) + $r->psg_step_no) != $chk_step_no){
                ?>
				<div class="pop_list01" id="pre_step2-<?=$seq?>_<?=$ii?>">
				  <div id="pre_div_preview_step2-<?=$seq?>_<?=$ii?>" class="templet_img_in3" style="background-image : url('<?=($r->psg_imgpath == "") ? "/dhn/images/leaflets/sample_img.jpg" : $r->psg_imgpath?>');">
				    <img id="pre_img_preview_step2-<?=$seq?>_<?=$ii?>" style="display:none;">
				  </div>
				  <div class="pop_price">
				    <p class="price1" id="pre_goods_price_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_price?></p><?//STEP2 정상가?>
				    <p class="price2" id="pre_goods_dcprice_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_dcprice?></p><?//STEP2 할인가?>
				  </div>
				  <div class="pop_name">
                    <span id="pre_goods_name_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_name?></span><?//STEP2 상품명?>
                    <span id="pre_goods_option_step2-<?=$seq?>_<?=$ii?>"><?=$r->psg_option?></span><?//STEP2 옵션명?>
					<div id="pre_goods_imgpath_step2-<?=$seq?>_<?=$ii?>" style="display:none;"><?=($r->psg_imgpath == "") ? "/dhn/images/leaflets/sample_img.jpg" : $r->psg_imgpath?></div><?//STEP2 이미지경로?>
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
            <div class="pre_box4"><?//텍스트형?>
			  <? if($r->tit_imgpath != ""){ ?>
              <p class="pre_tit" id="pre_tit_id_step3-<?=$seq?>">
                <img src="<?=$r->tit_imgpath?>"><?//텍스트형 타이틀 이미지 ?>
              </p>
			  <? } ?>
              <div class="pop_list_wrap" id="pre_area_goods_step3-<?=$seq?>">
                <!--상품추가시 looping-->
                <?
						} //if((($psg_step*10) + $r->psg_step_no) != $chk_step_no){
                ?>
                <div class="pop_list02" id="pre_step3-<?=$seq?>_<?=$ii?>">
                  <div class="name">
					<span id="pre_goods_name_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_name?></span><?//텍스트형 상품명?>
                    <span id="pre_goods_option_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_option?></span><?//텍스트형 옵션명?>
                  </div>
				  <span class="price1" id="pre_goods_price_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_price?></span><?//텍스트형 정상가?>
                  <span class="price2" id="pre_goods_dcprice_step3-<?=$seq?>_<?=$ii?>"><?=$r->psg_dcprice?></span><?//텍스트형 할인가?>
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
                <img src="<?=$r->psg_imgpath?>"><?//행사이미지 ?>
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
  <div class="smart_bot">
	  <ul>
		<li>
		 <span class="material-icons">history</span> 운영시간 : <?=$shop->mem_shop_time?>
		</li>
		<li>
		  <span class="material-icons">history_toggle_off</span> 휴무일 : <?=$shop->mem_shop_holiday?>
		</li>
		<li>
		  <span class="material-icons">phone_in_talk</span> 전화번호 : <?=$shop->mem_shop_phone?>
		</li>
		<li>
		  <span class="material-icons">place</span> 주소 : <?=$shop->mem_shop_addr?>
		</li>
	  </ul>
  </div><!--//smart_bot-->
  <div class="smart_map" id="smart_map" style="width:100%;height:250px"><!-- 카카오맵 - 지도 -->
  </div>
  <div class="smart_footer">
     <p>Copyright © DHN Corp. All rights reserved.</p>
  </div>
</div><!--//wrap_smart-->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=5eb6ce655a9dd1e9948326da08bfcbe2&libraries=services"></script>
<script>
	//카카오맵 - 지도
	var mapContainer = document.getElementById('smart_map'), // 지도를 표시할 div
	mapOption = {
		center: new kakao.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
		level: 3 // 지도의 확대 레벨
	};

	// 지도를 생성합니다
	var map = new kakao.maps.Map(mapContainer, mapOption);

	// 주소-좌표 변환 객체를 생성합니다
	var geocoder = new kakao.maps.services.Geocoder();

	// 주소로 좌표를 검색합니다
	geocoder.addressSearch('<?=$shop->mem_shop_addr?>', function(result, status) {
		// 정상적으로 검색이 완료됐으면
		 if (status === kakao.maps.services.Status.OK) {
			var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
			// 결과값으로 받은 위치를 마커로 표시합니다
			var marker = new kakao.maps.Marker({
				map: map,
				position: coords
			});
			// 인포윈도우로 장소에 대한 설명을 표시합니다
			var infowindow = new kakao.maps.InfoWindow({
				content: '<div style="width:200px;text-align:center;padding:3px 0; font-size:14px;"><?=$shop->mem_username?></div>'
			});
			infowindow.open(map, marker);
			// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
			map.setCenter(coords);
		}
	});
</script>
