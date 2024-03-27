<?
	//$code = "";
	if($code != ""){
		$back_url = "/smart/view/". $code; //이전페이지
		if($ScreenShotYn != "") $back_url .= "?ScreenShotYn=". $ScreenShotYn;
	}else{
		$back_url = "javascript:history.back();"; //이전페이지
	}
?>
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<div class="wrap_smart">
  <div class="smart_header">
    <span class="logo"><? if($code != ""){ ?><a href="<?=$back_url?>"><? } ?><img src="/dhn/images/logo_v2.png" alt="<?=config_item('site_name')?>"><? if($code != ""){ ?></a><? } ?></span>
    <span class="header_txt"><?=config_item('site_full_name')?></span>
  </div>
  <div class="smart_menu">
    <? if($code != ""){ ?><a href="<?=$back_url?>"><i class="material-icons">arrow_back</i></a><? } ?>
    <span class="smart_title">주문내역확인</span>
  </div>
  <div class="smart_order">
    <p class="title">
      주문정보
    </p>
    <ul class="info_text">
      <li>
        <span class="text1">주문번호</span>
        <span class="text2"><?=$data->orderno?></span>
      </li>
      <li>
        <span class="text1">주문일자</span>
        <span class="text2"><?=$data->creation_date?></span>
      </li>
      <li>
        <span class="text1">고객명</span>
        <span class="text2"><?=$data->receiver?></span>
      </li>
      <li>
        <span class="text1">휴대폰번호</span>
        <span class="text2"><?=$this->funn->format_phone($data->phnno, "-")?></span>
      </li>
      <li>
        <span class="text1">배달주소</span>
        <span class="text2"><?=trim($data->postcode ." ". $data->addr1 ." ". $data->addr2)?></span>
      </li>
      <li>
        <span class="text1">결제방법</span>
        <span class="text2"><?=$this->funn->get_charge_type($data->charge_type)?>&nbsp;&nbsp;<?=($data->charge_type=='4')? "(".$data->account_info.")" : "" ?></span>
      </li>
      <li>
        <span class="text1">추가주문 <br />및 요청사항</span>
        <span class="text2"><?=nl2br($data->user_req)?></span>
      </li>
			<?
			 if(!empty($data->s_reason)){
			  ?>
			<li>
        <span class="text1">부분취소사유</span>
        <span class="text2"><?=nl2br($data->s_reason)?></span>
      </li>
			<? }  ?>
      <li>
        <span class="text1">주문상태</span>
        <span class="text2"><?=$this->funn->user_order_status($data->status)?><?=(!empty($data->sc_reason))? "(".$data->sc_reason.")" : "" ?></span>
      </li>
    </ul>
    <p class="title">
      주문내역
    </p>
    <?
		$total_amt = 0;
		if(!empty($list)){
			foreach($list as $keys => $r){
				if($r->cal_yn=='N'){
						$total_amt += $r->amount;
				}

    ?>
    <dl class="cart_goods">
      <dt>
        <img src="<?=($r->imgurl !="") ? $r->imgurl : config_item('no_img_path')//이미지?>" />
      </dt>
      <dd>
        <ul>
          <li><strong style="<?=($r->cal_yn=='Y')?'text-decoration:line-through':''?>"><?=$r->name?></strong> <?=$r->option?><?=($r->cal_yn=='Y')?'(부분취소)':''?></li>
          <li>판매가(수량 : <?=$r->qty?>)<div class="good_price"><?=number_format($r->amount)?><span>원</span></div></li>
          <!--<li class="gdod_cancel_box">
						<p>부분취소</p>
						<p>취소사유 : 해당상품 품절</p>
					</li>-->
				</ul>
      </dd>
    </dl>
    <?
			} //foreach($list as $keys => $r){
		} //if(!empty($list)){
		$deliv_amt = 0;
		$delivery_amt = $orderset->delivery_amt;
		$free_delivery_amt = $orderset->free_delivery_amt;
		if($total_amt < $free_delivery_amt){
			$deliv_amt=$delivery_amt;
		}
    ?>
    <div class="delivery_price">
      배달비<div class="good_price"><?=number_format($deliv_amt)?><span>원</span></div>
    </div>
    <div class="total_price">
      총결제금액<div class="good_price"><?=number_format($total_amt + $deliv_amt)?><span>원</span></div>
    </div>
    <? if($data->status == "0"){ //주문완료 상태의 경우만 ?>
    <button class="btn_order" onclick="ordercancel();" <?=($mng=='Y')? "style='display:none;'" : '' ?>>주문취소</button>
    <script>
		//주문취소
		function ordercancel(){
			if(!confirm("주문취소 하시겠습니까?")){
				return false;
			}
			$.ajax({
				url: "/smart/ordercancel",
				type: "POST",
				data: {
					"oid" : "<?=$data->id?>", //주문번호
					"phn" : "<?=$data->phnno?>", //휴대폰번호
					"<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
				},
				success: function (json){
					if(json.code == "0") {
						showSnackbar("주문이 취소 되었습니다.", 1500);
						setTimeout(function (){
							location.reload();
						}, 1500);
					}else{
						alert("처리 중 오류가 발생 했습니다.\n"+ json.message);
						return;
					}
	 			}
			});
		}
    </script>
    <? } ?>
  </div>
  <!-- <div class="smart_footer">
     <p>Copyright © DHN Corp. All rights reserved.</p>
  </div> -->
  <? include_once($_SERVER['DOCUMENT_ROOT'].'/views/smart/bootstrap/_inc_smart_footer.php'); ?>
</div>
