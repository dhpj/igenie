<?
	$goods_qty = 20; //주문 가능 갯수
	$back_url = "/smart/view/". $code; //이전페이지
    if($ScreenShotYn != ""){
        $back_url .= "?ScreenShotYn=". $ScreenShotYn;
        if($this->input->get("home") != "") $back_url .= "&home=". $this->input->get("home");
    }else{
        if($this->input->get("home") != "") $back_url .= "?home=". $this->input->get("home");
    }


?>
<link rel="stylesheet" type="text/css" href="/views/smart/bootstrap/css/style.css?v=<?=date("ymdHis")?>" />
<div class="wrap_smart">
  <div class="smart_header">
    <span class="logo"><a href="<?=$back_url?>"><img src="/dhn/images/logo_v2.png" alt="<?=config_item('site_name')?>"></a></span>
    <span class="header_txt"><?=config_item('site_full_name')?></span>
  </div>
  <div class="smart_menu">
    <a href="<?=$back_url?>"><i class="material-icons">arrow_back</i></a>
    <span class="smart_title">장바구니</span>
  </div>
  <? if(!empty($_SESSION['user_cart'])){ ?>
  <div class="smart_order">
    <?
		$amount = 0;
		$total_amt = 0;
		foreach($_SESSION["user_cart"] as $keys => $r){
			$dcprice = preg_replace("/[^0-9]*/s", "", $r['dcprice']); //할인가 숫자 제거
			$amount = $dcprice * $r['qty']; //판매가 = 할인가 * 주문갯수
			$total_amt += $amount; //총주문금액

			if(!empty($r['imgurl'])&&$r['imgurl'] != ""){
			    $file_ext = substr(strrchr($r['imgurl'], "."), 1);
			    $fileNameWithoutExt = substr($r['imgurl'], 0, strrpos($r['imgurl'], "."));
			    $findthumb = strripos($fileNameWithoutExt, '_thumb');
			    //echo $fileNameWithoutExt."---".$file_ext;
			    $plustext = "_thumb.";
			    if($findthumb>0){
			        $plustext = ".";
			    }
			    $imgnamepext = $fileNameWithoutExt.$plustext.$file_ext;
			}
	?>
    <dl class="cart_goods">
      <dt>
        <img src="<?=($r['imgurl'] !="") ? $imgnamepext : config_item('no_img_path')//이미지?>">
      </dt>
      <dd>
        <ul>
          <li><strong><?=$r['name']//상품명?></strong> <?=$r['option']//규격?></li>
          <li>
            <!-- <span class="amount">
              <select id="<?=$r['goodsid']?>_qty" name="<?=$r['goodsid']?>_quantity1" value="1" onchange="changeqty('<?=$r['goodsid']?>','<?=$r['qty']?>')">
                <? for($ii = 1; $ii <= $goods_qty; $ii++){ ?>
                <option value="<?=$ii?>"<? if($r['qty'] == $ii) { echo ' selected'; } ?>><?=$ii?></option>
                <? } ?>
                <? if($r['qty'] > 20) { ?>
                <option value="<?=$r['qty']?>" selected><?=$r['qty']?></option>
                <? } ?>
              </select>
            </span> -->
            <div class="amount">
				<input type="button" value="-" class="qty_num minus" onclick="setprice(<?=$r['goodsid']?>, 'M')">
                <input type="number" name="<?=$r['goodsid']?>_quantity1" id="<?=$r['goodsid']?>_qty" value="<?=$r['qty']?>" class="qty" min="1" max="999" maxlength='3' onkeypress="return isNumberKey(event)" numberOnly onchange="changeqty('<?=$r['goodsid']?>','<?=$r['qty']?>')">
                <input type="button" value="+" class="qty_num plus" onclick="setprice(<?=$r['goodsid']?>, 'P')">
			</div>
            <div class="base_price"><?=number_format($dcprice)?><span>원</span></div>
          </li>
          <li>총상품금액<div class="good_price"><?=number_format($amount)?><span>원</span></div></li>
        </ul>
        <div class="material-icons btn_del" style="cursor:pointer;" onclick="delcartitem('<?=$r['goodsid']?>')"><i class="material-icons">clear</i></div>
      </dd>
    </dl>
    <?
		} //foreach($_SESSION["user_cart"] as $keys => $r){

		//배달비 세팅
		$min_amt = ($os->min_amt != "") ? $os->min_amt : 30000; //주문 최소금액
		$delivery_amt = ($os->delivery_amt != "") ? $os->delivery_amt : 3000; //배달비
		$free_delivery_amt = ($os->free_delivery_amt != "") ? $os->free_delivery_amt : 50000; //무료배달 최소금액
		if($total_amt > $free_delivery_amt){
			$delivery_amt = 0;
		}
    ?>
    <div class="delivery_price">
      배달비<div class="good_price"><?=number_format($delivery_amt)?><span>원</span></div>
    </div>
    <? if(!empty($shop->mem_cart_info)){ ?>
    <div class="mem_cart_info">
        <?=nl2br($shop->mem_cart_info)?>
    </div>
    <? } ?>
    <div class="total_price">
      총결제금액<div class="good_price"><?=number_format($total_amt+$delivery_amt)?><span>원</span></div>
    </div>
    <? if($total_amt < $min_amt){ ?>
    <button class="btn_order_x"><?=number_format($min_amt)?>원 이상 주문가능</button>
    <? }else{ ?>
    <button class="btn_order" onclick="order();">주문하기</button>
    <? } ?>
  </div>
  <? }else{ //if(!empty($_SESSION['user_cart'])){ ?>
  <div class="smart_order"><dl class="cart_goods" style="width:100%;text-align:center;padding:50px;">장바구니에 담긴 상품이 없습니다.</dl></div>
  <? } //if(!empty($_SESSION['user_cart'])){ ?>
  <!-- <div class="smart_footer">
     <p>Copyright © DHN Corp. All rights reserved.</p>
  </div> -->
  <? include_once($_SERVER['DOCUMENT_ROOT'].'/views/smart/bootstrap/_inc_smart_footer.php'); ?>
</div>
<script>
	//장바구니 상품삭제
	function delcartitem(goodsid){
		//if(confirm("장바구니에서 제거 하시겠습니까?"))
		{
			$.ajax({
				url: "/smart/delitem",
				type: "POST",
				data: {"goodsid" : goodsid, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
				success: function (json) {
					if(json.code == "0") {
						//alert("정상적으로 처리 되었습니다.");
						location.reload();
					} else {
						showSnackbar("취소 하지 못했습니다.<br>" + json.message, 1500);
					}
	 			}
			});
		}
	}

	//장바구니 수량 변경
	function changeqty(goodsid, org_qty){
		var qty = $("#"+ goodsid +"_qty").val();
        if(Number(qty)>9999){
            $("#"+ goodsid +"_qty").val('9999');
            qty=9999;
        }
        if(Number(qty)<1){
            $("#"+ goodsid +"_qty").val('1');
            qty=1;
        }

		$.ajax({
			url: "/smart/changeqty",
			type: "POST",
			data: {"goodsid" : goodsid, "qty" : qty, "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"},
			success: function (json) {
				//alert("json.code : "+ json.code);
				if(json.code == "0"){
					//alert("정상적으로 처리 되었습니다.");
					location.reload();
				}else{
					$("#"+ goodsid +"_qty").val(org_qty);
					showSnackbar("장바구니에 담지 못했습니다.<br>"+ json.message, 1500);
				}
			}
		});
	}

    function setprice(goodsid, flag){
        var qty = $("#"+ goodsid +"_qty").val();

        if(flag=='P'){
            qty = Number(qty)+1;
            $("#"+ goodsid +"_qty").val(qty);
            changeqty(goodsid, qty);
        }else{
            if(Number(qty)==1){
                // showSnackbar(""+ json.message, 1000);
                return false;
            }else{
                qty = Number(qty)-1;
                $("#"+ goodsid +"_qty").val(qty);
                changeqty(goodsid, qty);
            }
        }
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        // Textbox value
        var _value = event.srcElement.value;

        // 소수점(.)이 두번 이상 나오지 못하게
        var _pattern0 = /^\d*[.]\d*$/; // 현재 value값에 소수점(.) 이 있으면 . 입력불가
        if (_pattern0.test(_value)) {
            if (charCode == 46) {
                return false;
            }
        }

        // 10000 이하의 숫자만 입력가능
        var _pattern1 = /^\d{4}$/; // 현재 value값이 3자리 숫자이면 . 만 입력가능
        if (_pattern1.test(_value)) {
            if (charCode != 46) {
                // alert("1000 이하의 숫자만 입력가능합니다");
                return false;
            }
        }

        return true;
    }


	//주문하기
	function order(){
        $.ajax({
            url: "/smart/check_purchase_datetime",
            type: "POST",
            data: {
                "<?=$this->security->get_csrf_token_name()?>" : "<?=$this->security->get_csrf_hash()?>"
              , "psd_id" : "<?=$screen_data->psd_id?>"
            },
            success: function (json){
                if (json.code == "0"){
                    var url = "/smart/orders?code=<?=$code?>";
            		var ScreenShotYn = "<?=$ScreenShotYn?>";
                    var Home = "<?=$this->input->get("home")?>";
                    if(Home != "") url += "&home="+ Home;
            		if(ScreenShotYn != "") url += "&ScreenShotYn="+ ScreenShotYn;
            		location.href = url;
                }else if(json.code == "1") {
                    showSnackbar("구매할 수 없는 시간입니다.", 1500);
                }
            }
        });
	}
</script>
