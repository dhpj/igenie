<?php $coin = $this->Biz_dhn_model->getAbleCoin($this->member->item('mem_id'), $this->member->item('mem_userid')); ?>
<?php $v_coin = $this->Biz_dhn_model->getAbleVCoin($this->member->item('mem_id'), $this->member->item('mem_userid')); ?>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal">
            <div class="modal-content">
                <br/>
                <div class="modal-body">
                    <div class="content">
                    </div>
                        <p class="btn_al_cen mg_b20">
                            <button type="button" class="btn_st1" data-dismiss="modal">확인</button>
                        </p>
                </div>
            </div>
        </div>
    </div>
    <!-- 3차 메뉴 -->
    <?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu4.php');
    ?>
    <!-- //3차 메뉴 -->
    <script>
    $(document).ready(function() {
    $("input[name='smarthome_type']:radio").change(function () {

            var types = this.value;
            if(types == 'A'||types == 'B'){
                $("#smarthome_visual").show();
            }else{
                $("#smarthome_visual").hide();
            }

    });
});

    </script>
<div id="mArticle">
	<div class="form_section">
    <div class="inner_tit">
			<h3>매장 정보<span class="s_txt">* 스마트전단 발송시 상단과 하단에 보여질 매장안내 이미지와 매장정보를 입력하세요~</span></h3>
		</div>
    <div class="white_box">
      <form id="join_shop" action="/biz/myinfo/shop_modify" method="post" autocomplete="off">
	  <input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
	    <div class="list_myinfo">
			<div class="mi_lbox">
                <div class="smarthome_type">
                    <dl>
                        <dt>
                          <input type="radio" name="smarthome_type" id="smarthome_type1" value="A" <?=($rs->mem_shop_template == 'A')? "checked" :""?>>
                          <label for="smarthome_type1">일반마트</label>
                        </dt>
                        <dd>
                          <img src="/images/smarthome_type1.jpg" />
                        </dd>
                    </dl>
                    <dl>
                        <dt>
                          <input type="radio" name="smarthome_type" id="smarthome_type2" value="B" <?=($rs->mem_shop_template == 'B')? "checked" :""?>>
                          <label for="smarthome_type2">농협전용</label>
                        </dt>
                        <dd>
                          <img src="/images/smarthome_type2.jpg" />
                        </dd>
                    </dl>
                    <dl>
                        <dt>
                          <input type="radio" name="smarthome_type" id="smarthome_type3" value="C" <?=($rs->mem_shop_template == 'C')? "checked" :""?>>
                          <label for="smarthome_type3">주문전용</label>
                        </dt>
                        <dd>
                          <img src="/images/smarthome_type3.jpg" />
                        </dd>
                    </dl>
                    <p class="smarthome_type_txt">
                        <span>step1</span> 스마트홈 타입을 선택해 주세요.
                    </p>

                </div>
                <div class="smarthome_visual" id="smarthome_visual" <?=($rs->mem_shop_template == 'C')? "style='display:none;'" : "" ?>>
			    <div class="text">
                    <label for="img_file_shop" class="templet_img_box" style="cursor:pointer;">
                        <div id="div_preview_shop"><img src="<?=$rs->mem_shop_img?>" id="img_preview_shop" style="display:block;width:100%"></div>
                    </label>
                </div>
 			    <input type="hidden" name="imgpath_shop" id="imgpath_shop" value="<?=$rs->mem_shop_img?>" style="width:100%; text-align:right;">
 			    <input type="file" title="이미지 파일" name="img_file_shop" id="img_file_shop" onChange="imgChange(this, '_shop');" class="upload-hidden" accept=".jpg, .png, .gif" style="display:none;">
			    <div class="name"><?=$rs->mem_username?></div><?//매장 사진 아래 업체명?>
                <p class="smarthome_type_txt">
                    <span>step2</span> 업체 전경 사진 또는 로고를 등록해 주세요.
                </p>
                <p class="mg_t10">
                  * <span class="fc_red">행사 전단 이미지는 등록하실 수 없습니다.</span>(카카오 광고제재)
                </p>
                <p class="mg_t10">
                  * 매장정보 이미지는 <span class="fc_red">4:3 비율</span>에 최적화 되어 있으며 권장사이즈는 <span class="fc_red">800x600px,</span> <br /><span class="mg_l10">최소사이즈는 400x300px입니다.</span>
                </p>
                <p class="mg_t10">
                  * 이미지 편집이 어려우실 경우 <span class="fc_red">[알씨]를 설치</span>(<a href="https://www.altools.co.kr" target="_blank">https://www.altools.co.kr</a>)하거나 <span class="fc_red">이미지 무료편집사이트</span>(<a href="https://www.iloveimg.com/ko" target="_blank">https://www.iloveimg.com/ko</a>)를 이용해보세요~
                </p>
                </div>
			</div>
			<div class="mi_rbox">
				<dl>
					<dt>
						매장소개글
					</dt>
					<dd>
						<textarea id="mem_shop_intro" name="mem_shop_intro" cols="100" rows="6" placeholder="지니할인마트를 찾아주셔서 감사합니다. 포인트 1000점 이상 사용가능합니다. 3만원이상 배달가능"><?=$rs->mem_shop_intro?></textarea>
					</dd>
				</dl>
                <dl>
					<dt>
						공지사항
					</dt>
					<dd>
                        <input type="text" id="mem_notice" name="mem_notice" placeholder="한줄 공지입니다. 글자 수 제한에 유의하세요." value="<?=$rs->mem_notice?>" maxlength="30" class="int100_l">
					</dd>
				</dl>
				<dl>
					<dt>
						운영시간
					</dt>
					<dd>
						<input type="text" id="mem_shop_time" name="mem_shop_time" placeholder="09:00~23:00" value="<?=$rs->mem_shop_time?>" class="">
					</dd>
				</dl>
				<dl>
					<dt>
						휴무일
					</dt>
					<dd>
						<input type="text" id="mem_shop_holiday" name="mem_shop_holiday" placeholder="연중무휴" value="<?=$rs->mem_shop_holiday?>" class="int100_l">
					</dd>
				</dl>
				<dl>
					<dt>
						전화번호
					</dt>
					<dd>
						<input type="text" id="mem_shop_phone" name="mem_shop_phone" placeholder="070-1234-5678" value="<?=$rs->mem_shop_phone?>" class="int100_l">
					</dd>
				</dl>
				<dl>
					<dt>
						주소
					</dt>
					<dd>
						<input type="text" id="mem_shop_addr" name="mem_shop_addr" placeholder="부산 연제구 거제시장로14번길 43" value="<?=$rs->mem_shop_addr?>" class="int100_l">
					</dd>
				</dl>
                <dl>
					<dt>
						장바구니<br/>하단정보
					</dt>
					<dd>
						<textarea id="mem_cart_info" name="mem_cart_info" cols="100" rows="6" placeholder="장바구니 하단 정보"><?=$rs->mem_cart_info?></textarea>
					</dd>
				</dl>
                <dl>
					<dt>
						스마트홈<br/>공지사항
					</dt>
					<dd>
                        <input type="hidden" id="mem_shop_notice" name="mem_shop_notice"/>
                        <script src="/summernote/summernote-lite.js"></script>
                		<script src="/summernote/lang/summernote-ko-KR.js"></script>
                		<link rel="stylesheet" href="/summernote/summernote-lite.css">
                		<div id="summernote"></div>
                		<script>
                			$(document).ready(function() {
                				$('#summernote').summernote({
                					height: 340,                 // 에디터 높이
                					minHeight: null,             // 최소 높이
                					maxHeight: null,             // 최대 높이
                					focus: false,                  // 에디터 로딩후 포커스를 맞출지 여부
                					lang: "ko-KR",					// 한글 설정
                					placeholder: '에디터 내용을 입력하세요.',	//placeholder 설정
                					toolbar: [
                						['fontsize', ['fontsize']],
                						['style', ['bold', 'italic', 'underline','strikethrough']],
                						['color', ['forecolor','color']],
                						['insert',['picture']]
                					],
                					callbacks: {
                						onImageUpload: function(image) {
                                            for(var i = 0; i < image.length; i++){
                                                uploadImage(image[i]);
                                            }
                						}
                					},
                				});

                				$('#summernote').summernote('fontSize', 18);
                                // $('.note-editable').css('font-size','18px');
                				<? if(!empty($rs->mem_shop_notice)) { ?>
                				$('#summernote').summernote('code', '<?=$rs->mem_shop_notice?>');
                				<? } ?>
                				var html = $('#summernote').summernote('code'); //에디터 내용
                				//alert("html : "+ html);
                				//alert("html : "+ html); return;
                				if(html == '' || html == '<p><br></p>' || html == '<p><span style="font-size: 18px;">﻿</span><br></p>'){ //에디터의 경우
                					$('#summernote').summernote('code', '');
                				}
                				// $('.form-group note-group-image-url').hide();
                			});

                			function uploadImage(image) {
                				var data = new FormData();
                				data.append("image_file", image);
                				data.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
                				$.ajax({
                					url: 'http://<?=$_SERVER['HTTP_HOST']?>/dhnbiz/sender/image_upload/talkadvimg',
                					cache: false,
                					contentType: false,
                					processData: false,
                                    async: false,
                					data: data,
                					type: "post",
                					success: function(json) {
                						console.log(json);
                						if(json['code']=='S') {
                							var image = $('<img>').attr('src', json['imgurl']);
                							$('#summernote').summernote("insertNode", image[0]);
                						}
                					},
                					error: function(data) {
                						console.log(data);
                					}
                				});
                			}
                		</script>
					</dd>
				</dl>
				<dl style="display:none;">
					<dt>
						다음지도
					</dt>
					<dd>
						<input type="text" id="mem_map_node" name="mem_map_node" placeholder="다음지도 노드" title="다음지도 노드" value="<?=$rs->mem_map_node?>" class="">
						&nbsp;
						<input type="text" id="mem_map_key" name="mem_map_key" placeholder="다음지도 Key" title="다음지도 Key" value="<?=$rs->mem_map_key?>" class="">
					</dd>
				</dl>
		</div>
      </form>
	</div>
    <div class="btn_al_cen mg_b50">
      <a class="btn_st3" href="/home">취소</a>&nbsp;
      <a class="btn_st3" type="button" onclick="complete_shop();">수정</a>
    </div>
    </div>
		<div class="inner_tit mg_t20">
			<h3>계정 정보</h3>
		</div>
		<div class="white_box">
			<form id="join" action="/biz/myinfo/modify" method="post" enctype="multipart/form-data" autocomplete="off">
			<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
			<div class="myinfo_list">
			  <dl>
				<dt>계정</dt>
				<dd><input class="form-control input-width-large" type="text" name="readonly" readonly value="<?=$rs->mem_userid?>"></dd>
			  </dl>
			  <dl>
				<dt>예치금</dt>
				<? if ($rs->mem_pay_type == 'A') { ?>
				<dd>후불제</dd>
			  <? } else {?>
				  <dd><font color='blue' style="letter-spacing:1px;"><strong>
                      <?=number_format($coin)?></strong>원<?=($v_coin>0&&$this->member->item("mem_voucher_yn")=="Y")? " + 바우처(<strong>".number_format($v_coin)."</strong>원)" : "" ?></font> &nbsp;
    				  (사용가능: <font color='red' style="letter-spacing:1px;"><strong><?php echo number_format($coin+(($v_coin>0&&$this->member->item("mem_voucher_yn")=="Y")? $v_coin : 0)); ?></strong>원</font>)</dd>
				  <? } ?>
			  </dl>
              <?if(!in_array($this->member->item('mem_id'), config_item('price_display'))){?>
			  <dl>
				<dt>발송가능 건수</dt>
				<? if ($rs->mem_pay_type == 'A') { ?>

				<? } else {?>
				  <dd>
		<!--
		알림톡: <?=number_format(floor($coin / $this->Biz_model->price_at))?>건
		, &nbsp;친구톡: <?=number_format(floor($coin / $this->Biz_model->price_ft))?>건
		, &nbsp;친구톡이미지: <?=number_format(floor($coin / $this->Biz_model->price_ft_img))?>건
<?if($rs->mem_2nd_send=="lms") { if( $coin > 0 && $this->Biz_model->price_sms>0) {  echo ', LMS('.number_format(floor($coin / $this->Biz_model->price_sms)).'건)'; } }
else if($rs->mem_2nd_send=="GREEN_SHOT") { if( $coin > 0 && $this->Biz_model->price_grs>0) {  echo ', WEB(A)('.number_format(floor($coin / $this->Biz_model->price_grs)).'건)'; } }
else if($rs->mem_2nd_send=="NASELF") { if( $coin > 0 && $this->Biz_model->price_nas>0) {  echo ', WEB(B)('.number_format(floor($coin / $this->Biz_model->price_nas)).'건)'; } }
else if($rs->mem_2nd_send=="015") { if( $coin > 0 && $this->Biz_model->price_015>0) {  echo ', 015저가문자('.number_format(floor($coin / $this->Biz_model->price_015)).'건)'; } }
else if($this->member->item('mem_2nd_send')=="NASELF") {if( $my['coin']>0 && $my['price_nas']>0) { echo ', WEB(B)('.number_format(floor($my['coin'] / $my['price_nas'])).'건)'; } }
else if($this->member->item('mem_2nd_send')=="015") {if( $my['coin']>0 && $my['price_015']>0) { echo ', 015저가문자('.number_format(floor($my['coin'] / $my['price_015'])).'건)'; } }
else  { ?>
		, &nbsp;문자: <?//=number_format(floor($coin / $this->Biz_model->price_phn))?>건
		<?php }?>-->
<?
      $my = $this->funn->getCoin_new($this->member->item('mem_id'), $this->member->item('mem_userid'));
      //log_message("ERROR","예치금 잔액 : ".$my['coin']."/".$my['price_phn']);
      $memPayType = $this->member->item('mem_pay_type');               // 선후불제 타입(선불제: B, 후불제:A, 정액제: T)
      $totalCoin = number_format($my['coin'], 0);           // 충전금액 or 사용금액(-값)
      $arlimTackNumber = '';               // 알림톡 발송건수
      $arlimTackUnitPrice = '';           // 알림톡 단가(값 예; 건당 1.55원)
      $frinedTackNumber = '';              // 친구톡 발송건수
      $frinedTackUnitPrice = '';          // 친구톡 단가
      $frinedTackImageNumber = '';         // 친구톡 이미지 발송건수
      $frinedTackImageUnitPrice = '';     // 친구톡 이미지 단가
      $messageNumber = '';              // 문자 발송건수
      $messageUnitPrice = '';          // 문자 단가
      // 2019.09.20 변수 추가
      $messageSmsNumber = '';          // sms 발송 건수
      $messageSmsUnitPrice = '';      // sms 단가
      $messageMmsNumber = '';          // Mms 발송 건수
      $messageMmsUnitPrice = '';      // Mms 단가

      //log_message("ERROR", "mem_pay_type : ".$memPayType);
      //log_message("ERROR", "mem_pay_type = B : ".($memPayType === 'B'));
      //log_message("ERROR", "empty(paty_type) = B : ".empty($memPayType));
      //$memPayType = 'A';
  if($memPayType == 'B' || empty($memPayType)) {
          //log_message("ERROR", "mem_pay_type1 : B");
          if( $my['coin'] > 0 && $my['price_at'] > 0 ) {      // 알림톡 건수 및 단가
              $arlimTackNumber = number_format(floor($my['coin'] / $my['price_at']));
              $arlimTackUnitPrice = '단가 : '.$my['price_at'].'원/건';
          } else {
              $arlimTackNumber = 0;
              $arlimTackUnitPrice = '단가 : '.$my['price_at'].'원/건';
          }
          if( $my['coin'] > 0 && $my['price_ft'] > 0) {       // 친구톡 건수 및 단가
              $frinedTackNumber = number_format(floor($my['coin'] / $my['price_ft']));
              $frinedTackUnitPrice = '단가 : '.$my['price_ft'].'원/건';
          } else {
              $frinedTackNumber = 0;
              $frinedTackUnitPrice = '단가 : '.$my['price_ft'].'원/건';
          }
          if( $my['coin'] > 0 && $my['price_ft_img'] > 0 ) {  // 친구톡 이미지 건수 및 단가
              $frinedTackImageNumber = number_format(floor($my['coin'] / $my['price_ft_img']));
              $frinedTackImageUnitPrice = '단가 : '.$my['price_ft_img'].'원/건';
          } else {
              $frinedTackImageNumber = 0;
              $frinedTackImageUnitPrice = '단가 : '.$my['price_ft_img'].'원/건';
          }
          if($this->member->item('mem_2nd_send')=="sms") {
              if( $my['coin']>0 && $my['price_sms']>0) {
                  $messageNumber = number_format(floor($my['coin'] / $my['price_sms']));
                  $messageUnitPrice = '단가 : '.$my['price_sms'].'원/건';
              } else {
                  $messageNumber = 0;
                  $messageUnitPrice = '단가 : '.$my['price_sms'].'원/건';
              }
          } else if($this->member->item('mem_2nd_send')=="lms") {
              if( $my['coin']>0 && $my['price_lms']>0) {
                  $messageNumber = number_format(floor($my['coin'] / $my['price_lms']));
                  $messageUnitPrice = '단가 : '.$my['price_lms'].'원/건';
              } else {
                  $messageNumber = 0;
                  $messageUnitPrice = '단가 : '.$my['price_lms'].'원/건';
              }
          } else if($this->member->item('mem_2nd_send')=="mms") {
              if( $my['coin']>0 && $my['price_mms']>0) {
                  $messageNumber = number_format(floor($my['coin'] / $my['price_mms']));
                  $messageUnitPrice = '단가 : '.$my['price_mms'].'원/건';
              } else {
                  $messageNumber = 0;
                  $messageUnitPrice = '단가 : '.$my['price_mms'].'원/건';
              }
          } else if($this->member->item('mem_2nd_send')=="phn") {
              if( $my['coin']>0 && $my['price_phn']>0) {
                  $messageNumber = number_format(floor($my['coin'] / $my['price_phn']));
                  $messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
              } else {
                  $messageNumber = 0;
                  $messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
              }
          } else if($this->member->item('mem_2nd_send')=="GREEN_SHOT") {
              if( $my['coin']>0 && $my['price_grs']>0) {
                  $messageNumber = number_format(floor($my['coin'] / $my['price_grs']));
                  $messageUnitPrice = '단가 : '.$my['price_grs'].'원/건';
              } else {
                  $messageNumber = 0;
                  $messageUnitPrice = '단가 : '.$my['price_grs'].'원/건';
              }
              // 2019.09.20 추가
              if( $my['coin']>0 && $my['price_grs_sms']>0) {
                  $messageSmsNumber = number_format(floor($my['coin'] / $my['price_grs_sms']));
                  $messageSmsUnitPrice = '단가 : '.$my['price_grs_sms'].'원/건';
              } else {
                  $messageSmsNumber = 0;
                  $messageSmsUnitPrice = '단가 : '.$my['price_grs_sms'].'원/건';
              }
              if( $my['coin']>0 && $my['price_grs_mms']>0) {
                  $messageMmsNumber = number_format(floor($my['coin'] / $my['price_grs_mms']));
                  $messageMmsUnitPrice = '단가 : '.$my['price_grs_mms'].'원/건';
              } else {
                  $messageMmsNumber = 0;
                  $messageMmsUnitPrice = '단가 : '.$my['price_grs_mms'].'원/건';
              }
          } else if($this->member->item('mem_2nd_send')=="NASELF") {
              if( $my['coin']>0 && $my['price_nas']>0) {
                  $messageNumber = number_format(floor($my['coin'] / $my['price_nas']));
                  $messageUnitPrice = '단가 : '.$my['price_nas'].'원/건';
              } else {
                  $messageNumber = 0;
                  $messageUnitPrice = '단가 : '.$my['price_nas'].'원/건';
              }
              // 2019.09.20 추가
              if( $my['coin']>0 && $my['price_nas_sms']>0) {
                  $messageSmsNumber = number_format(floor($my['coin'] / $my['price_nas_sms']));
                  $messageSmsUnitPrice = '단가 : '.$my['price_nas_sms'].'원/건';
              } else {
                  $messageSmsNumber = 0;
                  $messageSmsUnitPrice = '단가 : '.$my['price_nas_sms'].'원/건';
              }
              if( $my['coin']>0 && $my['price_nas_mms']>0) {
                  $messageMmsNumber = number_format(floor($my['coin'] / $my['price_nas_mms']));
                  $messageMmsUnitPrice = '단가 : '.$my['price_nas_mms'].'원/건';
              } else {
                  $messageMmsNumber = 0;
                  $messageMmsUnitPrice = '단가 : '.$my['price_nas_mms'].'원/건';
              }
          } else if($this->member->item('mem_2nd_send')=="SMART") {
              if( $my['coin']>0 && $my['price_smt']>0) {
                  $messageNumber = number_format(floor($my['coin'] / $my['price_smt']));
                  $messageUnitPrice = '단가 : '.$my['price_smt'].'원/건';
              } else {
                  $messageNumber = 0;
                  $messageUnitPrice = '단가 : '.$my['price_smt'].'원/건';
              }
              // 2019.09.20 추가
              if( $my['coin']>0 && $my['price_smt']>0) {
                  $messageSmsNumber = number_format(floor($my['coin'] / $my['price_smt_sms']));
                  $messageSmsUnitPrice = '단가 : '.$my['price_smt_sms'].'원/건';
              } else {
                  $messageSmsNumber = 0;
                  $messageSmsUnitPrice = '단가 : '.$my['price_smt_sms'].'원/건';
              }
              if( $my['coin']>0 && $my['price_smt']>0) {
                  $messageMmsNumber = number_format(floor($my['coin'] / $my['price_smt_mms']));
                  $messageMmsUnitPrice = '단가 : '.$my['price_smt_mms'].'원/건';
              } else {
                  $messageMmsNumber = 0;
                  $messageMmsUnitPrice = '단가 : '.$my['price_smt_mms'].'원/건';
              }
          } else if($this->member->item('mem_2nd_send')=="015") {
              if( $my['coin']>0 && $my['price_015']>0) {
                  $messageNumber = number_format(floor($my['coin'] / $my['price_015']));
                  $messageUnitPrice = '단가 : '.$my['price_015'].'원/건';
              } else {
                  $messageNumber = 0;
                  $messageUnitPrice = '단가 : '.$my['price_015'].'원/건';
              }
          } else {
              if( $my['coin']>0 && $my['price_phn']>0) {
                  $messageNumber = number_format(floor($my['coin'] / $my['price_phn']));
                  $messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
              } else {
                  $messageNumber = 0;
                  $messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
              }
          }

      } else if ($memPayType == 'A'){
          //log_message("ERROR", "mem_pay_type1 : A");
          $arlimTackUnitPrice = $my['price_at'];
          $frinedTackUnitPrice = $my['price_ft'];
          $frinedTackImageUnitPrice = $my['price_ft_img'];
          if($this->member->item('mem_2nd_send')=="sms") {
              $messageUnitPrice = $my['price_sms'];
          } else if($this->member->item('mem_2nd_send')=="lms") {
              $messageUnitPrice = $my['price_lms'];
          } else if($this->member->item('mem_2nd_send')=="mms") {
              $messageUnitPrice = $my['price_mms'];
          } else if($this->member->item('mem_2nd_send')=="phn") {
              $messageUnitPrice = $my['price_phn'];
          } else if($this->member->item('mem_2nd_send')=="GREEN_SHOT") {
              $messageUnitPrice = $my['price_grs'];
              // 2019.09.20 추가
              $messageSmsUnitPrice = $my['price_grs_sms'];
              $messageMmsUnitPrice = $my['price_grs_mms'];
          } else if($this->member->item('mem_2nd_send')=="NASELF") {
              // 2019.09.20 추가
              $messageSmsUnitPrice = $my['price_nas_sms'];
              $messageMmsUnitPrice = $my['price_nas_mms'];
              $messageUnitPrice = $my['price_nas'];
          } else if($this->member->item('mem_2nd_send')=="SMART") {
              $messageUnitPrice = $my['price_smt'];
              // 2019.09.20 추가
              $messageSmsUnitPrice = $my['price_smt_sms'];
              $messageMmsUnitPrice = $my['price_smt_mms'];
          } else if($this->member->item('mem_2nd_send')=="015") {
              $messageUnitPrice = $my['price_015'];
          } else {
              $messageUnitPrice = $my['price_phn'];
          }
      }
	  if($messageSmsNumber == "") $messageSmsNumber = "0";
?>
		알림톡: <?= $arlimTackNumber ?>건
		, &nbsp;문자: <?= $messageSmsNumber ?>건
				</dd>
				<? } ?>
			  </dl>
              <?}?>
			  <dl>
				<dt>일일 발신량 제한</dt>
				<dd><?=($rs->mem_day_limit==0) ? "제한없음" : number_format($rs->mem_day_limit)."건"?></dd>
			  </dl>
			  <dl>
				<dt>현재 비밀번호<span class="required">*</span></dt>
				<dd>
				  <input type="password" name="mb_passwd" id="mb_passwd"
							   class="form-control required input-width-large" maxlength='25'
							   onblur="check_password(); check_empty('mb_passwd');"
							   onchange="noSpaceForm(this);">
					<input type="button" value="확인" onClick="check_password();">
					<input type="hidden" name="hidden_mb_passwd" id="hidden_mb_passwd"
						   value="pbkdf2_sha256$24000$b5yo1HAJbDj8$r4TFfxoWJgM4i9KrpBt+z6dsOoLLCsakTwIxTqbAlzQ=">
					<text id="mb_passwd_result"
						  style="display:none; color:#942a25; font-weight: bold; "> 비밀번호를 입력해주세요.
					</text>
					<text id="mb_passwd_rule"
						  style="display:none; color:#942a25; font-weight: bold; "></text>
				</dd>
			  </dl>
			  <dl>
				<dt>새로운 비밀번호</dt>
				<dd>
				  <input type="password" name="new_mb_passwd" id="new_mb_passwd"
							   class="form-control input-width-large" maxlength='25'
							   onblur="check_password_rule(); check_empty('new_mb_passwd'); "
							   onchange="noSpaceForm(this);">
						<text id="new_mb_passwd_result"
							  style="display:none; color:#942a25; font-weight: bold; "> 새로운 비밀번호를
							입력해주세요.
						</text>
						<text id="new_mb_passwd_rule"
							  style="display:none; color:#942a25; font-weight: bold; "></text>
						<span class="help-block">변경하실 새로운 비밀번호를 입력해주세요.</span>
				</dd>
			  </dl>
			  <dl>
				<dt>비밀번호 재입력</dt>
				<dd>
				  <input type="password" name="new_mb_passwd2" id="new_mb_passwd2"
							   class="form-control input-width-large" maxlength='25'
							   onblur="check_password_new(); check_empty('new_mb_passwd2'); "
							   onchange="noSpaceForm(this);">
						<text id="new_mb_passwd2_rule"
							  style="display:none; color:#942a25; font-weight: bold; "></text>
						<text id="new_mb_passwd2_result"
							  style="display:none; color:#942a25; font-weight: bold; "> 비밀번호를 다시 한번
							입력해주세요.
						</text>
				</dd>
			  </dl>
			  <dl>
				<dt>업체명</dt>
				<dd><input class="form-control input-width-large" type="text" name="readonly" readonly value="<?=$rs->mem_username?>"></dd>
			  </dl>
			  <dl>
				<dt>사업자등록증</dt>
				<dd>
				  <input type="file" name="bisness_file" id="bisness_file" class="required"
							   accept="image/*"
							   onblur="check_empty('bisness_file');" value="upload"
							   onchange="check_size();">
						<input type="hidden" name="bisness_file_result_" id="bisness_file_result"
							   value="">
						<span class="help-block"> &nbsp;jpg, png 파일만 업로드 됩니다. 최대 500KB </span>
						<text id="bisness_file_result"
							  style="display:none; color:#942a25; font-weight: bold; "> 사업자등록증을 등록해주세요.
						</text>
						<text id="bisness_file_rule"
							  style="display:none; color:#942a25; font-weight: bold; "></text>
						<img src="/mem_sheet/<?=$rs->mem_photo?>" style="height:100px;" />
				</dd>
			  </dl>
			  <dl style="display:none;">
				<dt>전화번호<span class="required">*</span></dt>
				<dd>
				  <input class="form-control input-width-large required" id="admin_tel" maxlength="25" name="admin_tel" placeholder="- 없이 입력해주세요" value="<?=$rs->mem_phone?>" type="text" />
						<text id="admin_tel_result"
							  style="display:none; color:#942a25; font-weight: bold; "> 담당자 연락처를 입력해주세요.
						</text>
						<text id="admin_tel_rule"
							  style="display:none; color:#942a25; font-weight: bold; "></text>
				</dd>
			  </dl>
			  <dl>
				<dt>담당자 이름<span class="required">*</span></dt>
				<dd>
				  <input type="text" name="admin_name" id="admin_name"
								   class="form-control required input-width-large"
								   value="<?=$rs->mem_nickname?>" onblur="check_empty('admin_name');"
								   onchange="noSpaceForm(this);" maxlength='25'>

						<text id="admin_name_result"
							  style="display:none; color:#942a25; font-weight: bold; "> 담당자 이름을 입력해주세요.
						</text>
						<text id="admin_name_rule"
							  style="display:none; color:#942a25; font-weight: bold; "></text>
				</dd>
			  </dl>
			  <dl>
				<dt>담당자 연락처<span class="required">*</span></dt>
				<dd>
				  <input class="form-control input-width-large required" id="id_emp_tel" maxlength="25" name="emp_tel" placeholder="- 없이 입력해주세요" value="<?=$rs->mem_emp_phone?>" type="text" />
						<text id="admin_tel_result"
							  style="display:none; color:#942a25; font-weight: bold; "> 담당자 연락처를 입력해주세요.
						</text>
						<text id="admin_tel_rule"
							  style="display:none; color:#942a25; font-weight: bold; "></text>
				</dd>
			  </dl>
			  <dl>
				<dt>담당자 이메일 <span class="required">*</span></dt>
				<dd>
				  <input type="email" name="admin_email" maxlength='25' id="admin_email"
						 class="form-control required input-width-large"
						 value="<?=$rs->mem_email?>"
						 onblur="email_check_result(); check_empty('admin_email');"
						 onchange="noSpaceForm(this);">
				 <text id="admin_email_rule" style="display:none; color:#942a25; font-weight: bold; "></text>
				</dd>
			  </dl>
			  <dl>
			    <dt>매장URL(카페,블로그)</dt>
			    <dd><input class="form-control input-width-large" style="width:600px;" id="id_phn_free" maxlength="128" name="phn_free" placeholder="http://를 꼭 포함해서 입력하세요." value="<?=$rs->mad_free_hp?>" type="text" /></dd>
			  </dl>
			  <dl style="display:none;">
			    <dt>알림톡 '행사보기'<br>버튼 URL 고정</dt>
			    <dd>
				  <input type="radio" name="alim_btn_yn1" id="alim_btn_yn1_N" value="N" onClick="$('#id_alim_btn_url1').hide();$('#chk_alim_btn_url1').hide();$('#id_alim_btn_url1').val('');"<?=($rs->mem_alim_btn_url1 == "") ? " checked" : ""?>>&nbsp;<label for="alim_btn_yn1_N">사용안함</label>
				  <input type="radio" name="alim_btn_yn1" id="alim_btn_yn1_Y" value="Y" onClick="$('#id_alim_btn_url1').show();$('#chk_alim_btn_url1').show();$('#id_alim_btn_url1').focus();"<?=($rs->mem_alim_btn_url1 != "") ? " checked" : ""?> style="margin-left:10px;">&nbsp;<label for="alim_btn_yn1_Y">사용</label>
				  <input class="form-control input-width-large" style="width:600px;margin-left:10px;display:<?=($rs->mem_alim_btn_url1 != "") ? "" : "none"?>;" id="id_alim_btn_url1" maxlength="128" name="mem_alim_btn_url1" placeholder="http://를 꼭 포함해서 입력하세요." value="<?=$rs->mem_alim_btn_url1?>" type="text" />
				  <input type="button" id="chk_alim_btn_url1" value="링크확인" style="cursor: pointer;margin-left:5px;display:<?=($rs->mem_alim_btn_url1 != "") ? "" : "none"?>;" onClick="urlLink($('#id_alim_btn_url1'));">
			    </dd>
			  </dl>
			  <dl style="display:none;">
			    <dt>알림톡 '전단보기'<br>버튼 URL 고정</dt>
			    <dd>
				  <input type="radio" name="alim_btn_yn2" id="alim_btn_yn2_N" value="N" onClick="$('#id_alim_btn_url2').hide();$('#chk_alim_btn_url2').hide();$('#id_alim_btn_url2').val('');"<?=($rs->mem_alim_btn_url2 == "") ? " checked" : ""?>>&nbsp;<label for="alim_btn_yn2_N">사용안함</label>
				  <input type="radio" name="alim_btn_yn2" id="alim_btn_yn2_Y" value="Y" onClick="$('#id_alim_btn_url2').show();$('#chk_alim_btn_url2').show();$('#id_alim_btn_url2').focus();"<?=($rs->mem_alim_btn_url2 != "") ? " checked" : ""?> style="margin-left:10px;">&nbsp;<label for="alim_btn_yn2_Y">사용</label>
				  <input class="form-control input-width-large" style="width:600px;margin-left:10px;display:<?=($rs->mem_alim_btn_url2 != "") ? "" : "none"?>;" id="id_alim_btn_url2" maxlength="128" name="mem_alim_btn_url2" placeholder="http://를 꼭 포함해서 입력하세요." value="<?=$rs->mem_alim_btn_url2?>" type="text" />
				  <input type="button" id="chk_alim_btn_url2" value="링크확인" style="cursor: pointer;margin-left:5px;display:<?=($rs->mem_alim_btn_url2 != "") ? "" : "none"?>;" onClick="urlLink($('#id_alim_btn_url2'));">
			    </dd>
			  </dl>
			  <dl>
			    <dt>알림톡 '주문하기'<br>버튼 URL 고정</dt>
			    <dd>
				  <input type="radio" name="alim_btn_yn3" id="alim_btn_yn3_N" value="N" onClick="$('#id_alim_btn_url3').hide();$('#chk_alim_btn_url3').hide();$('#id_alim_btn_url3').val('');"<?=($rs->mem_alim_btn_url3 == "") ? " checked" : ""?>>&nbsp;<label for="alim_btn_yn3_N">사용안함</label>
				  <input type="radio" name="alim_btn_yn3" id="alim_btn_yn3_Y" value="Y" onClick="$('#id_alim_btn_url3').show();$('#chk_alim_btn_url3').show();$('#id_alim_btn_url3').focus();"<?=($rs->mem_alim_btn_url3 != "") ? " checked" : ""?> style="margin-left:10px;">&nbsp;<label for="alim_btn_yn3_Y">사용</label>
				  <input class="form-control input-width-large" style="width:600px;margin-left:10px;display:<?=($rs->mem_alim_btn_url3 != "") ? "" : "none"?>;" id="id_alim_btn_url3" maxlength="128" name="mem_alim_btn_url3" placeholder="http://를 꼭 포함해서 입력하세요." value="<?=$rs->mem_alim_btn_url3?>" type="text" />
				  <input type="button" id="chk_alim_btn_url3" value="링크확인" style="cursor: pointer;margin-left:5px;display:<?=($rs->mem_alim_btn_url3 != "") ? "" : "none"?>;" onClick="urlLink($('#id_alim_btn_url3'));">
			    </dd>
			  </dl>
        <dl>
			    <dt>이미지 라이브러리 <br />카테고리 고정 </dt>
			    <dd>
            <select id="mem_img_lib_category1" name="mem_img_lib_category1" style="" onchange="$('#mem_img_lib_category2').val('');img_lib_category2('');">
				        <option value="">- 대분류 -</option>
            				<? foreach($category_list as $r) { ?>
            				<option value="<?=$r->id?>"<?=($rs->mem_img_lib_category1 == $r->id) ? " Selected" : ""?>><?=$r->description?></option>
            				<? } ?>
						</select>
            <select id="mem_img_lib_category2" name="mem_img_lib_category2" style="width: 220px; margin-left: 5px;" >
				        <option value="">- 소분류 -</option>
			      </select>
          </dd>
        </dl>
        <dl>
          <dt>이미지 라이브러리 <br />규격검색 사용유무</dt>
          <dd>
            <input type="hidden" name="hdn_is_stan" value="<?=$rs->mem_is_stan?>" >
            <input type="radio" name="stan_ch" id="stan_ch1" value="N" onClick="" <?=($rs->mem_is_stan == "N") ? " checked" : ""?>>&nbsp;<label for="stan_ch1">사용안함</label>
            <input type="radio" name="stan_ch" id="stan_ch2" value="Y" onClick="" style="margin-left:10px;" <?=($rs->mem_is_stan == "Y") ? " checked" : ""?>>&nbsp;<label for="stan_ch2">사용</label>
          </dd>
        </dl>
			  <dl style="display:<?=($rs->mem_stmall_yn=="Y") ? "" : "none"?>;">
				<dt>스마트전단 주문<br>알림 전화번호 <span class="required">*</span></dt>
				<dd>
					<div class="info_contents">
						<input type="hidden" id="mem_stmall_alim_phn" name="mem_stmall_alim_phn" value="<?=$rs->mem_stmall_alim_phn?>">
						<?
							$mem_stmall_alim_phn = $rs->mem_stmall_alim_phn; //주문 알림 수신 번호
							$orstr = explode(',', $mem_stmall_alim_phn);
							for($i=0; $i<count($orstr); $i++){
								$stmall_alim_phn = $orstr[$i];
								if($stmall_alim_phn == "" and $i == 0) $stmall_alim_phn = $rs->mem_emp_phone; //담당자 연락처
								$stmall_alim_phn = $this->funn->format_phone($stmall_alim_phn, "-");
								if($i > 0){
									echo "<span class='class_stmall_alim_phn' style='margin-left:10px;'>";
								}
						?>
						<input type="text" id="id_stmall_alim_phn" style="width:120px;" value="<?=$stmall_alim_phn?>" maxlength="13" onKeyup="phone_chk(this);">
						<?
								if($i > 0){
									echo "  <input type='button' style='width:30px;margin-left:2px;cursor:pointer;' onclick='item_remove(this)' value='-'>";
									echo "</span>";
								}
							}
						?>
						<input type="button" id="btn_stmall_alim_phn" style="width:30px;margin-left:5px;cursor:pointer;" onclick="stmall_alim_phn_append()" value="+">
					</div>
				</dd>
			 </dl>
			  <dl style="display:<?=($rs->mem_stmall_yn=="Y") ? "" : "none"?>;">
				<dt>스마트전단 주문<br>배달 가능금액 설정 <span class="required">*</span></dt>
				<dd>
					<div class="info_contents">
						<span>주문 최소금액</span>
						<input type="text" name="min_amt" id="min_amt" style="width:100px;" value="<?=number_format(($os->min_amt != "") ? $os->min_amt : "30000")?>" maxlength="10" onkeyup="numberWithCommas(this, this.value)">
						<span style="margin-left:20px;">배달비</span>
						<input type="text" name="delivery_amt" id="delivery_amt" style="width:100px;" value="<?=number_format(($os->delivery_amt != "") ? $os->delivery_amt : "3000")?>" maxlength="10" onkeyup="numberWithCommas(this, this.value)">
						<span style="margin-left:20px;">무료배달 최소금액</span>
						<input type="text" name="free_delivery_amt" id="free_delivery_amt" style="width:100px;" value="<?=number_format(($os->free_delivery_amt != "") ? $os->free_delivery_amt : "50000")?>" maxlength="10" onkeyup="numberWithCommas(this, this.value)">
					</div>
				</dd>
			 </dl>
       <dl style="display:<?=($rs->mem_stmall_yn=="Y") ? "" : "none"?>;">
         <dt>현장결제(지역화폐) 사용유무</dt>
         <dd>
           <input type="hidden" name="hdn_is_pay" value="<?=$rs->mem_is_pay?>" >
           <input type="radio" name="order_ch" id="order_ch1" value="N" onClick="" <?=($rs->mem_is_pay == "N") ? " checked" : ""?>>&nbsp;<label for="order_ch1">사용안함</label>
           <input type="radio" name="order_ch" id="order_ch2" value="Y" onClick="" style="margin-left:10px;" <?=($rs->mem_is_pay == "Y") ? " checked" : ""?>>&nbsp;<label for="order_ch2">사용</label>
         </dd>
       </dl>
       <dl style="display:<?=($rs->mem_stmall_yn=="Y") ? "" : "none"?>;">
         <dt>계좌이체 사용유무</dt>
         <dd>
           <input type="hidden" name="hdn_is_account" value="<?=$rs->mem_is_account?>" >
           <input type="radio" name="account_ch" id="account_ch1" value="N" onClick="" <?=($rs->mem_is_account == "N") ? " checked" : ""?>>&nbsp;<label for="account_ch1">사용안함</label>
           <input type="radio" name="account_ch" id="account_ch2" value="Y" onClick="" style="margin-left:10px;" <?=($rs->mem_is_account == "Y") ? " checked" : ""?>>&nbsp;<label for="account_ch2">사용</label>
           <input type="hidden" name="hdn_is_account_info" value="" >
           <input type="text" id="account_info" style="width:260px;height:28px;margin-left:10px;display:<?=($rs->mem_is_account=='Y' or empty($rs->mem_is_account)) ? '':'none'?>" placeholder="이체 받을 계좌정보를 입력하세요" value="<?=$rs->account_info?>">
         </dd>
       </dl>
			</div>
			<div class="btn_al_cen mg_b50">
			  <a class="btn_st3" href="/home">취소</a>&nbsp;
			  <a class="btn_st3" type="button" onclick="complete();">수정</a>
			</div>
		</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#biusnessNum').filestyle({
		buttonName: 'btn',
		buttonText: ' 파일찾기',
		icon: false
	});
</script>

<script type="text/javascript">
	var check_password_ = false;
	var check_password_rule_ = false;
	var check_password_new_ = false;
	var email_result_ = false;
	var file_check_ = 0;

	//소분류 조회
	function img_lib_category2(category2){
		var category1 = $("#mem_img_lib_category1").val(); //대분류
		$("#mem_img_lib_category2").empty(); //소분류 초기화
		var option = $("<option value=''>- 소분류 -</option>");
		$("#mem_img_lib_category2").append(option);
		//alert("category1 : "+ category1 +", category2 : "+ category2);
		//alert("category1 : "+ category1); return;
		//소분류 조회
		$.ajax({
			url: "/mng/design/search_category2",
			type: "POST",
			data: {"category1" : category1, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				$.each(json, function(key, value){
					//if(key == 0) alert("["+ (key+1) +"] value.id : "+ value.id +", value.description : "+ value.description);
					if(category2 != "" && category2 == value.id){
						option = $("<option value='"+ value.id +"' Selected>"+ value.description +"</option>");
					}else{
						option = $("<option value='"+ value.id +"'>"+ value.description +"</option>");
					}
					$("#mem_img_lib_category2").append(option);
				});
			}
		});
	}

	$(window).ready(function() {
		img_lib_category2("<?=$rs->mem_img_lib_category2?>");
	});

	//확인창 확인버튼
	function click_btn_custom() {
		$(document).unbind("keyup").keyup(function (e) {
			var code = e.which;
			if (code == 13) {
				$(".btn-custom").click();
			}
		});
	}

	// 공백 제거
	function noSpaceForm(obj) {
		var str_space = /\s/;  // 공백체크
		if (str_space.exec(obj.value)) { //공백 체크
			obj.focus();
			obj.value = obj.value.replace(/(\s*)/g, ''); // 공백제거
			return false;
		}
	}

	//빈칸 여부 확인
	function check_empty(id) {
		var get_id = '#' + id;
		if ($(get_id).val().trim().length > 0) {
			document.getElementById(id + '_result').style.display = 'none';
		}
		if (!id || $('#mb_passwd').val().trim().length == 0) {
			document.getElementById('mb_passwd_rule').style.display = 'none';
		}
		if (!id || $('#new_mb_passwd').val().trim().length == 0) {
			document.getElementById('new_mb_passwd_rule').style.display = 'none';
		}
	}

	//이메일 유효성 검사
	function email_check(email) {
		var regex = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		return (email != '' && email != 'undefined' && regex.test(email) === true);
	}

	function email_check_result() {
		var admin_email = $('#admin_email').val();
		if (!email_check(admin_email) || !admin_email) {
			document.getElementById('admin_email_rule').innerHTML = '올바른 형식의 이메일을 입력해주세요.';
			document.getElementById('admin_email_rule').style.color = '#942a25';
			document.getElementById('admin_email_rule').style.display = 'block';
			email_result_ = false;
		}
		else if (admin_email && email_check(admin_email)) {
			document.getElementById('admin_email_rule').innerHTML = '올바른 형식의 이메일 입니다.';
			document.getElementById('admin_email_rule').style.color = '#2a6496';
			document.getElementById('admin_email_rule').style.display = 'block';
			email_result_ = true;
		}
	}

	//패스워드 확인
	function check_password_rule() {

		var password = $('#new_mb_passwd').val();
		var checkNumber = password.search(/[0-9]/g);
		var checkEnglish = password.search(/[a-z]/ig);

		if (password) {
			if (password && $('#new_mb_passwd').val().trim().length > 0) {
				document.getElementById('new_mb_passwd_result').style.display = 'none';

				if (!/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[azA-Z0-9])/.test(password) || checkNumber < 0 || checkEnglish < 0) {
					if (!/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[azA-Z0-9])/.test(password) || password.length<6 || password.length>15) {
						document.getElementById('new_mb_passwd_rule').innerHTML = '숫자와 영문자, 특수문자 조합으로 6~15자리를 사용해야 합니다.';
						document.getElementById('new_mb_passwd_rule').style.color = '#942a25';
						document.getElementById('new_mb_passwd_rule').style.display = 'block';
						check_password_rule_ = false;
					}
					if (checkNumber < 0 || checkEnglish < 0) {
						document.getElementById('new_mb_passwd_rule').innerHTML = '숫자와 영문자, 특수문자를 혼용하여야 합니다.';
						document.getElementById('new_mb_passwd_rule').style.color = '#942a25';
						document.getElementById('new_mb_passwd_rule').style.display = 'block';
						check_password_rule_ = false;
					}
				}
				else {
					document.getElementById('new_mb_passwd_rule').innerHTML = '사용가능한 비밀번호 입니다.';
					document.getElementById('new_mb_passwd_rule').style.color = '#2a6496';
					document.getElementById('new_mb_passwd_rule').style.display = 'block';
					check_password_rule_ = true;
				}
			} else {
				document.getElementById('new_mb_passwd_rule').innerHTML = '공백문자를 사용할 수 없습니다. ';
				document.getElementById('new_mb_passwd_rule').style.color = '#942a25';
				document.getElementById('new_mb_passwd_rule').style.display = 'block';
				check_password_rule_ = false;
			}
		} else {
			document.getElementById('new_mb_passwd_result').style.display = 'none';
			check_password_rule_ = false;
		}
	}

	//비밀번호 일치 확인 (현재 비밀번호)
	function check_password() {
		var mb_passwd = $('#mb_passwd').val();

		if ($('#mb_passwd').val().trim().length == 0) { //공백문자 일 경우(mb_passwd)
			document.getElementById('mb_passwd_result').style.display = 'block';
			check_password_ = false;
		}
		else if (!mb_passwd) {
			document.getElementById('mb_passwd_result').style.display = 'block';
			check_password_ = false;
		}
		else {
			document.getElementById('mb_passwd_result').style.display = 'none';
			document.getElementById('mb_passwd_rule').style.display = 'none';

			$.ajax({
				url: '/biz/myinfo/password',
				type: "POST",
				data: {
					<?=$this->security->get_csrf_token_name()?>: '<?=$this->security->get_csrf_hash()?>',
					mb_passwd: mb_passwd
				},
				success: function (json) {
					get_result(json);
				},
				error: function (data, status, er) {
					document.getElementById('mb_passwd_rule').innerHTML = '처리중 오류가 발생하였습니다. 관리자에게 문의하세요';
					document.getElementById('mb_passwd_rule').style.color = '#942a25';
					document.getElementById('mb_passwd_rule').style.display = 'block';
					check_password_ = false;
				}
			});
			function get_result(json) {
				document.getElementById('mb_passwd_result').style.display = 'none';
				if (json['result'] == true) {
					document.getElementById('mb_passwd_rule').innerHTML = '비밀번호가 일치 합니다.';
					document.getElementById('mb_passwd_rule').style.color = '#2a6496';
					document.getElementById('mb_passwd_rule').style.display = 'block';
					check_password_ = true;
				}
				else {
					document.getElementById('mb_passwd_rule').innerHTML = '비밀번호가 일치하지 않습니다.';
					document.getElementById('mb_passwd_rule').style.color = '#942a25';
					document.getElementById('mb_passwd_rule').style.display = 'block';
					check_password_ = false;
				}
			}
		}
	}

	//비밀번호 일치 확인 (새로운 비밀번호)
	function check_password_new() {
		var new_mb_passwd = $('#new_mb_passwd').val();
		var new_mb_passwd2 = $('#new_mb_passwd2').val();

		if ($('#new_mb_passwd').val().trim().length == 0 || $('#new_mb_passwd2').val().trim().length == 0) {
			if ($('#new_mb_passwd').val().trim().length == 0) { //공백문자 일 경우(mb_passwd)
				document.getElementById('mb_passwd_result').style.display = 'block';
				check_password_new_ = false;
			}
			if ($('#new_mb_passwd2').val().trim().length == 0) { //공백문자 일 경우(mb_passwd2)
				document.getElementById('mb_passwd2_result').style.display = 'block';
				check_password_new_ = false;
			}
		}
		else if (!new_mb_passwd || !new_mb_passwd2) {

			if (!new_mb_passwd) {
				document.getElementById('mb_passwd_result').style.display = 'block';
				check_password_new_ = false;
			}
			if (!new_mb_passwd2) {
				document.getElementById('mb_passwd2_result').style.display = 'block';
				check_password_new_ = false;
			}

		}
		else {
			document.getElementById('new_mb_passwd_result').style.display = 'none';
			if (new_mb_passwd != new_mb_passwd2) {
				document.getElementById('new_mb_passwd2_rule').innerHTML = '비밀번호가 일치하지 않습니다.';
				document.getElementById('new_mb_passwd2_rule').style.color = '#942a25';
				document.getElementById('new_mb_passwd2_rule').style.display = 'block';
				check_password_new_ = false;
			} else {
				document.getElementById('new_mb_passwd2_rule').innerHTML = '비밀번호가 일치 합니다.';
				document.getElementById('new_mb_passwd2_rule').style.color = '#2a6496';
				document.getElementById('new_mb_passwd2_rule').style.display = 'block';
				check_password_new_ = true;
			}
		}
	}

	//전송 버튼 후 30초 비활성화
	$(function () {
		$("#check_admin_tel").click(function () {
			$("#check_admin_tel").attr("disabled", "disabled");
			setTimeout(function () {
				$("#check_admin_tel").removeAttr("disabled");
			}, 30000);
		});
	});

	//파일 용량 및 확장자 확인 ->500KB제한
	function check_size() {
		var thumbext = document.getElementById('bisness_file').value;

		thumbext = thumbext.slice(thumbext.indexOf(".") + 1).toLowerCase();

		if (thumbext) {
			document.getElementById('bisness_file_result').style.display = 'none';
			if (thumbext != "jpg" && thumbext != "png" && thumbext != "jpeg") { //확장자를 확인
				document.getElementById('bisness_file_rule').innerHTML = 'jpg, png 파일이 아닙니다. 다시 선택해 주세요.';
				document.getElementById('bisness_file_rule').style.color = '#942a25';
				document.getElementById('bisness_file_rule').style.display = 'block';
				file_check_ = 2;
			}
			else {
				if ($("#bisness_file")[0].files[0].size > 500000) //파일 용량 체크 (500KB 제한)
				{
					document.getElementById('bisness_file_rule').innerHTML = '500KB를 초과하였습니다. 다시 선택해 주세요.';
					document.getElementById('bisness_file_rule').style.color = '#942a25';
					document.getElementById('bisness_file_rule').style.display = 'block';
					file_check_ = 2;
				} else {
					document.getElementById('bisness_file_rule').innerHTML = '업로드 가능한 파일입니다.';
					document.getElementById('bisness_file_rule').style.color = '#2a6496';
					document.getElementById('bisness_file_rule').style.display = 'block';
					file_set = true;
					file_check_ = 1;
				}
			}
		} else {
			document.getElementById('bisness_file_rule').style.display = 'none';
			file_check_ = 0;
		}
	}

	//매장 정보 수정
	function complete_shop() {
        var html = $('#summernote').summernote('code');
        $("#mem_shop_notice").val(html);
		$(".content").html("매장 정보를 변경하였습니다.");
		$("#myModal").modal('show');
		click_btn_custom();
		$('#myModal').on('hidden.bs.modal', function () {
			document.getElementById("join_shop").submit();
		});
	}

	//링크확인
	function urlLink(id){
		url = id.val();
		//alert("val : "+ val);
		if(url == ""){
			alert("URL을 입력하세요.");
			id.focus();
			return;
		}
		if(url.substring(0,7) != "http://"){
			alert("URL 내용은 http:// 로 시작 되어야 합니다.");
			id.focus();
			return;
		}
		window.open(url, 'urlLink', '');
	}

	//계정 정보 수정
	function complete() {
		var mb_passwd = $('#mb_passwd').val();
		var admin_email = $('#admin_email').val();
		var admin_tel = $('#admin_tel').val();
		var admin_name = $('#admin_name').val();
		var new_mb_passwd = $('#new_mb_passwd').val();
		var new_mb_passwd2 = $('#new_mb_passwd2').val();
		var check_new_password_result = true;
		var check_file_result = true;

		check_password();
		email_check_result();

		if (new_mb_passwd) {
			if (!new_mb_passwd2 || check_password_rule_ == false || check_password_new_ == false) {
				if (!new_mb_passwd2) {
					$('#mb_passwd2').focus();
					check_new_password_result = false;
					document.getElementById('new_mb_passwd2_result').style.display = 'block';
				}
				else if (check_password_rule_ == false) {
					$('#new_mb_passwd').focus();
					check_new_password_result = false;
					document.getElementById('new_mb_passwd_rule').style.display = 'none';
				}
				else if (check_password_new_ == false) {
					$('#new_mb_passwd2').focus();
					check_new_password_result = false;
					document.getElementById('new_mb_passwd_rule').style.display = 'none';
				}
			}
			else {
				check_new_password_result = true;
			}
		}

		if (file_check_ == 2) {
			check_file_result = false;
			check_size();
		} else {
			check_file_result = true;
		}



		var alim_btn_yn1 = value_check("alim_btn_yn1"); //알림톡 '행사보기' 버튼 URL 고정 사용여부
		if(alim_btn_yn1 == "Y"){ //알림톡 '행사보기' 버튼 URL 고정 사용
			$('#id_alim_btn_url1').val($('#id_alim_btn_url1').val().trim());
			var id_alim_btn_url1 = $('#id_alim_btn_url1').val();
			if(id_alim_btn_url1 == ""){
				alert("알림톡 '행사보기' 버튼 URL을 입력하세요.");
				$('#id_alim_btn_url1').focus();
				return;
			}
			if(id_alim_btn_url1.substring(0,7) != "http://"){
				alert("알림톡 '행사보기' 버튼 URL 내용은 http:// 로 시작 되어야 합니다.");
				$('#id_alim_btn_url1').focus();
				return;
			}
		}
		//alert("id_alim_btn_url1.substring(0,7) : "+ id_alim_btn_url1.substring(0,7)); return;
		var alim_btn_yn2 = value_check("alim_btn_yn2"); //알림톡 '전단보기' 버튼 URL 고정 사용여부
		if(alim_btn_yn2 == "Y"){ //알림톡 '전단보기' 버튼 URL 고정 사용
			$('#id_alim_btn_url2').val($('#id_alim_btn_url2').val().trim());
			var id_alim_btn_url2 = $('#id_alim_btn_url2').val();
			if(id_alim_btn_url2 == ""){
				alert("알림톡 '전단보기' 버튼 URL을 입력하세요.");
				$('#id_alim_btn_url2').focus();
				return;
			}
			if(id_alim_btn_url2.substring(0,7) != "http://"){
				alert("알림톡 '전단보기' 버튼 URL 내용은 http:// 로 시작 되어야 합니다.");
				$('#id_alim_btn_url2').focus();
				return;
			}
		}
		var alim_btn_yn3 = value_check("alim_btn_yn3"); //알림톡 '주문하기' 버튼 URL 고정 사용여부
		if(alim_btn_yn3 == "Y"){ //알림톡 '주문하기' 버튼 URL 고정 사용
			$('#id_alim_btn_url3').val($('#id_alim_btn_url3').val().trim());
			var id_alim_btn_url3 = $('#id_alim_btn_url3').val();
			if(id_alim_btn_url3 == ""){
				alert("알림톡 '주문하기' 버튼 URL을 입력하세요.");
				$('#id_alim_btn_url3').focus();
				return;
			}
			if(id_alim_btn_url3.substring(0,7) != "http://"){
				alert("알림톡 '주문하기' 버튼 URL 내용은 http:// 로 시작 되어야 합니다.");
				$('#id_alim_btn_url3').focus();
				return;
			}
		}

    var order_ch = value_check("order_ch"); //지역화폐 사용유무
    if(order_ch){
      $('input[name=hdn_is_pay]').attr('value',order_ch);
    }

    var account_ch = value_check("account_ch"); //계좌이체 사용유무
    if(account_ch){
      $('input[name=hdn_is_account]').attr('value',account_ch);
    }
    if(account_ch == 'Y'){//계좌정보 리셋
      $('input[name=hdn_is_account_info]').val($("#account_info").val());
    }else{
      $('input[name=hdn_is_account_info]').empty();
    }

    var stan_ch = value_check("stan_ch"); //규격검색 사용유무
    if(stan_ch){
      $('input[name=hdn_is_stan]').attr('value',stan_ch);
    }




		var ii = 0;
		var errNo = 0;
		var mem_stmall_yn = "<?=$rs->mem_stmall_yn?>"; //스마트전단 주문하기 사용유무
		//alert("mem_stmall_yn : "+ mem_stmall_yn); return false;
		var mem_stmall_alim_phn = "";
		$(".info_contents #id_stmall_alim_phn").each(function (){ //주문 알림 수신 번호
			//alert("mem_stmall_alim_phn["+ ii +"] : "+ $(this).val());;
			var phn = rtn_number($(this).val());
			if(phn.length !=10 && phn.length !=11 && mem_stmall_yn == "Y"){
				errNo = (ii+1);
				alert(errNo +"번째 주문 알림 수신 번호가\n입력되지 않았거나 올바르지 않습니다.");
				$(this).focus();
				return false;
			}
			if(mem_stmall_alim_phn == ""){
				mem_stmall_alim_phn = phn;
			}else{
				mem_stmall_alim_phn += ","+ phn;
			}
			ii++;
		});
		//alert("mem_stmall_alim_phn : "+ mem_stmall_alim_phn); return false;
		$("#mem_stmall_alim_phn").val(mem_stmall_alim_phn); //주문 알림 수신 번호
		//alert("#mem_stmall_alim_phn : "+ $("#mem_stmall_alim_phn").val()); return false;
		//alert("errNo : "+ errNo); return false;
		if(errNo > 0) return false; //주문 알림 수신 번호 오류의 경우
		//alert("OK"); return false;

		if(mem_stmall_yn == "Y"){
			if($("#min_amt").val() == "") {
				alert("주문 최소금액을 입력해 주세요.");
				$("#min_amt").focus();
				return false;
			}
			if($("#delivery_amt").val() == "") {
				alert("배달비를 입력해 주세요.");
				$("#delivery_amt").focus();
				return false;
			}
			if($("#free_delivery_amt").val() == "") {
				alert("무료배달 최소금액을 입력해 주세요.");
				$("#free_delivery_amt").focus();
				return false;
			}
		}
		$("#min_amt").val(rtn_number($("#min_amt").val())); //주문 최소금액
		$("#delivery_amt").val(rtn_number($("#delivery_amt").val())); //배달비
		$("#free_delivery_amt").val(rtn_number($("#free_delivery_amt").val())); //무료배달 최소금액
		//alert("OK"); return;

		if (check_new_password_result == true && check_password_ == true && admin_name && admin_email && email_result_ == true && check_file_result == true) {

			$(".content").html("계정 정보를 변경하였습니다.");
			$("#myModal").modal('show');
			click_btn_custom();
			$('#myModal').on('hidden.bs.modal', function () {
				document.getElementById("join").submit();
			});

		} else {
			if (email_result_ == false) {
				$('#admin_email').focus();
			}
			if (!admin_tel) {
				document.getElementById('admin_tel_result').style.display = 'block';
				$('#admin_tel').focus();
			}
			if (!admin_name) {
				document.getElementById('admin_name_result').style.display = 'block';
				$('#admin_name').focus();
			}

			if (check_file_result == false) {
				check_size();
			}
			if (check_new_password_result == false) {
				$('#new_mb_passwd').focus();
				check_password_rule();
				check_password_new();
			}

			if (check_password_ == false) {
				document.getElementById('mb_passwd_result').style.display = 'block';
				$('#mb_passwd').focus();
			}
		}
	}

	//배경 이미지 초기화
	function remove_img(rowid){
		$("#img_preview"+ rowid).attr("src", "");
	}

	//이미지 선택 클릭시
	function imgChange(obj, id){
		//alert("id : "+ id +", obj.value : "+ obj.value); return;
		if(obj.value.length > 0) {
			//alert("obj.value : "+ obj.value);
			if (obj.files && obj.files[0]) {
				remove_img(id);
				readURL(obj, id);
			}
		}
	}

	//이미지 경로 세팅
	var set_imgpath_id = "";
	function readURL(input, id) {
		//alert("readURL(input, divid) > input.value : "+ input.value +", id : "+ id); return;
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$("#img_preview"+ id).attr("src", e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
			set_imgpath_id = "imgpath"+ id;
			//alert("set_imgpath_id : "+ set_imgpath_id);

			//이미지 추가
			request = new XMLHttpRequest();
			var formData = new FormData();
			formData.append("imgfile", input.files[0]); //이미지
			formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
			request.onreadystatechange = imgCallback;
			request.open("POST", "/biz/myinfo/imgfile_save");
			request.send(formData);
		}
	}

	function imgCallback(){
		//console.log(request.responseText);
		if(request.readyState  == 4) {
			var obj = JSON.parse(request.responseText);
			if(obj.code == "0") { //성공
				$("#"+ set_imgpath_id).val(obj.imgpath);
			} else { //오류
			}
		}
	}

	//주문 알림 수신 번호 추가
	function stmall_alim_phn_append(){
		//alert("stmall_alim_phn_append"); return;
		var html = "";
		html += "<span class='class_stmall_alim_phn' style='margin-left:10px;'>";
		html += "  <input type='text' id='id_stmall_alim_phn' style='width:120px;' value='' maxlength='13' onKeyup='phone_chk(this);'>";
		html += "  <input type='button' style='width:30px;margin-left:2px;cursor:pointer;' onclick='item_remove(this)' value='-'>";
		html += "</span>";
		//alert(html); return;
		$("#btn_stmall_alim_phn").before(html);
	}

	//항목 삭제
	function item_remove(obj){
		$(obj).parents(".class_stmall_alim_phn").remove();
	}

	// 숫자만 입력받고, ","값 입력하기
	function numberWithCommas(obj, value) {
		value = value.replace(/[^0-9]/g, '');		// 입력값이 숫자가 아니면 공백
		value = value.replace(/,/g, '');			// ,값 공백처리
		$(obj).val(value.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
	}
  //계좌정보 숨김 이벤트
  $(function(){
    $("input[name='account_ch']:radio").change(function(){
      var account_ch = this.value;
      if(account_ch == 'Y'){
        $("#account_info").css("display", "");
      }else{
        $("#account_info").css("display", "none");
      }
    });
  });
</script>
