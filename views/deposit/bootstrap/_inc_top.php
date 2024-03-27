<?
$my = $this->funn->getCoin_new($this->member->item('mem_id'), $this->member->item('mem_userid'));
$pre_coin = $this->funn->getPreCoin($this->member->item('mem_id')); //선충전 잔액
// $v_coin = $this->funn->getVCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
$v_coin["coin"] = $my["vcoin"];
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

$v_arlimTackNumber = '';               // 알림톡 발송건수
$v_arlimTackUnitPrice = '';           // 알림톡 단가(값 예; 건당 1.55원)
$v_frinedTackNumber = '';              // 친구톡 발송건수
$v_frinedTackUnitPrice = '';          // 친구톡 단가
$v_frinedTackImageNumber = '';         // 친구톡 이미지 발송건수
$v_frinedTackImageUnitPrice = '';     // 친구톡 이미지 단가
$v_messageNumber = '';              // 문자 발송건수
$v_messageUnitPrice = '';          // 문자 단가
$v_messageSmsNumber = '';          // sms 발송 건수
$v_messageSmsUnitPrice = '';      // sms 단가
$v_messageMmsNumber = '';          // Mms 발송 건수
$v_messageMmsUnitPrice = '';      // Mms 단가

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
        if( $my['coin']>0 && $my['price_grs']>0) {
            $messageMmsNumber = number_format(floor($my['coin'] / $my['price_grs']));
            $messageMmsUnitPrice = '단가 : '.$my['price_grs'].'원/건';
        } else {
            $messageMmsNumber = 0;
            $messageMmsUnitPrice = '단가 : '.$my['price_grs'].'원/건';
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
        if( $my['coin']>0 && $my['price_nas']>0) {
            $messageMmsNumber = number_format(floor($my['coin'] / $my['price_nas']));
            $messageMmsUnitPrice = '단가 : '.$my['price_nas'].'원/건';
        } else {
            $messageMmsNumber = 0;
            $messageMmsUnitPrice = '단가 : '.$my['price_nas'].'원/건';
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
            $messageMmsNumber = number_format(floor($my['coin'] / $my['price_smt']));
            $messageMmsUnitPrice = '단가 : '.$my['price_smt'].'원/건';
        } else {
            $messageMmsNumber = 0;
            $messageMmsUnitPrice = '단가 : '.$my['price_smt'].'원/건';
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
    if($messageSmsNumber == "") $messageSmsNumber = "0"; //단문문자 발송가능건수
    if($messageMmsNumber == "") $messageMmsNumber = "0"; //장문문자 발송가능건수
    if($arlimTackNumber == "") $arlimTackNumber = "0"; //알림톡 발송가능건수
    $messageSmsNumberStr = "<p class='d_number'> ". $messageSmsNumber ."<span>건</span>"; //단문문자 발송가능건수
    $messageMmsNumberStr = "<p class='d_number'> ". $messageMmsNumber ."<span>건</span>"; //장문문자 발송가능건수
    $arlimTackNumberStr = "<p class='d_number'> ". $arlimTackNumber ."<span>건</span>"; //알림톡 발송가능건수

    //바우처추가 2022-03-22 윤재박
    if( $v_coin['coin'] > 0 && $my['price_v_at'] > 0 ) {      // 알림톡 건수 및 단가
        $v_arlimTackNumber = number_format(floor($v_coin['coin'] / $my['price_v_at']));
        $v_arlimTackUnitPrice = '단가 : '.$my['price_v_at'].'원/건';
    } else {
        $v_arlimTackNumber = 0;
        $v_arlimTackUnitPrice = '단가 : '.$my['price_v_at'].'원/건';
    }
    if( $v_coin['coin'] > 0 && $my['price_v_ft'] > 0) {       // 친구톡 건수 및 단가
        $v_frinedTackNumber = number_format(floor($v_coin['coin'] / $my['price_v_ft']));
        $v_frinedTackUnitPrice = '단가 : '.$my['price_v_ft'].'원/건';
    } else {
        $v_frinedTackNumber = 0;
        $v_frinedTackUnitPrice = '단가 : '.$my['price_v_ft'].'원/건';
    }
    if( $v_coin['coin'] > 0 && $my['price_v_ft_img'] > 0 ) {  // 친구톡 이미지 건수 및 단가
        $v_frinedTackImageNumber = number_format(floor($v_coin['coin'] / $my['price_v_ft_img']));
        $v_frinedTackImageUnitPrice = '단가 : '.$my['price_v_ft_img'].'원/건';
    } else {
        $v_frinedTackImageNumber = 0;
        $v_frinedTackImageUnitPrice = '단가 : '.$my['price_v_ft_img'].'원/건';
    }
    if($this->member->item('mem_2nd_send')=="sms") {
        if( $v_coin['coin']>0 && $my['price_sms']>0) {
            $v_messageNumber = number_format(floor($v_coin['coin'] / $my['price_sms']));
            $v_messageUnitPrice = '단가 : '.$my['price_sms'].'원/건';
        } else {
            $v_messageNumber = 0;
            $v_messageUnitPrice = '단가 : '.$my['price_sms'].'원/건';
        }
    } else if($this->member->item('mem_2nd_send')=="lms") {
        if( $v_coin['coin']>0 && $my['price_lms']>0) {
            $v_messageNumber = number_format(floor($v_coin['coin'] / $my['price_lms']));
            $v_messageUnitPrice = '단가 : '.$my['price_lms'].'원/건';
        } else {
            $v_messageNumber = 0;
            $v_messageUnitPrice = '단가 : '.$my['price_lms'].'원/건';
        }
    } else if($this->member->item('mem_2nd_send')=="mms") {
        if( $v_coin['coin']>0 && $my['price_mms']>0) {
            $v_messageNumber = number_format(floor($v_coin['coin'] / $my['price_mms']));
            $v_messageUnitPrice = '단가 : '.$my['price_mms'].'원/건';
        } else {
            $v_messageNumber = 0;
            $v_messageUnitPrice = '단가 : '.$my['price_mms'].'원/건';
        }
    } else if($this->member->item('mem_2nd_send')=="phn") {
        if( $v_coin['coin']>0 && $my['price_phn']>0) {
            $v_messageNumber = number_format(floor($v_coin['coin'] / $my['price_phn']));
            $v_messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
        } else {
            $v_messageNumber = 0;
            $v_messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
        }
    } else if($this->member->item('mem_2nd_send')=="GREEN_SHOT") {
        if( $v_coin['coin']>0 && $my['price_grs']>0) {
            $v_messageNumber = number_format(floor($v_coin['coin'] / $my['price_grs']));
            $v_messageUnitPrice = '단가 : '.$my['price_grs'].'원/건';
        } else {
            $v_messageNumber = 0;
            $v_messageUnitPrice = '단가 : '.$my['price_grs'].'원/건';
        }
        // 2019.09.20 추가
        if( $v_coin['coin']>0 && $my['price_grs_sms']>0) {
            $v_messageSmsNumber = number_format(floor($v_coin['coin'] / $my['price_grs_sms']));
            $v_messageSmsUnitPrice = '단가 : '.$my['price_grs_sms'].'원/건';
        } else {
            $v_messageSmsNumber = 0;
            $v_messageSmsUnitPrice = '단가 : '.$my['price_grs_sms'].'원/건';
        }
        if( $v_coin['coin']>0 && $my['price_grs']>0) {
            $v_messageMmsNumber = number_format(floor($v_coin['coin'] / $my['price_grs']));
            $v_messageMmsUnitPrice = '단가 : '.$my['price_grs'].'원/건';
        } else {
            $v_messageMmsNumber = 0;
            $v_messageMmsUnitPrice = '단가 : '.$my['price_grs'].'원/건';
        }
    } else if($this->member->item('mem_2nd_send')=="NASELF") {
        if( $v_coin['coin']>0 && $my['price_nas']>0) {
            $v_messageNumber = number_format(floor($v_coin['coin'] / $my['price_nas']));
            $v_messageUnitPrice = '단가 : '.$my['price_nas'].'원/건';
        } else {
            $v_messageNumber = 0;
            $v_messageUnitPrice = '단가 : '.$my['price_nas'].'원/건';
        }
        // 2019.09.20 추가
        if( $v_coin['coin']>0 && $my['price_nas_sms']>0) {
            $v_messageSmsNumber = number_format(floor($v_coin['coin'] / $my['price_nas_sms']));
            $v_messageSmsUnitPrice = '단가 : '.$my['price_nas_sms'].'원/건';
        } else {
            $v_messageSmsNumber = 0;
            $v_messageSmsUnitPrice = '단가 : '.$my['price_nas_sms'].'원/건';
        }
        if( $v_coin['coin']>0 && $my['price_nas']>0) {
            $v_messageMmsNumber = number_format(floor($v_coin['coin'] / $my['price_nas']));
            $v_messageMmsUnitPrice = '단가 : '.$my['price_nas'].'원/건';
        } else {
            $v_messageMmsNumber = 0;
            $v_messageMmsUnitPrice = '단가 : '.$my['price_nas'].'원/건';
        }
    } else if($this->member->item('mem_2nd_send')=="SMART") {
        if( $v_coin['coin']>0 && $my['price_v_smt']>0) {
            $v_messageNumber = number_format(floor($v_coin['coin'] / $my['price_v_smt']));
            $v_messageUnitPrice = '단가 : '.$my['price_v_smt'].'원/건';
        } else {
            $v_messageNumber = 0;
            $v_messageUnitPrice = '단가 : '.$my['price_v_smt'].'원/건';
        }
        // 2019.09.20 추가
        if( $v_coin['coin']>0 && $my['price_v_smt']>0) {
            $v_messageSmsNumber = number_format(floor($v_coin['coin'] / $my['price_v_smt_sms']));
            $v_messageSmsUnitPrice = '단가 : '.$my['price_v_smt_sms'].'원/건';
        } else {
            $v_messageSmsNumber = 0;
            $v_messageSmsUnitPrice = '단가 : '.$my['price_v_smt_sms'].'원/건';
        }
        if( $v_coin['coin']>0 && $my['price_v_smt']>0) {
            $v_messageMmsNumber = number_format(floor($v_coin['coin'] / $my['price_v_smt']));
            $v_messageMmsUnitPrice = '단가 : '.$my['price_v_smt'].'원/건';
        } else {
            $v_messageMmsNumber = 0;
            $v_messageMmsUnitPrice = '단가 : '.$my['price_v_smt'].'원/건';
        }
    } else if($this->member->item('mem_2nd_send')=="015") {
        if( $v_coin['coin']>0 && $my['price_015']>0) {
            $v_messageNumber = number_format(floor($v_coin['coin'] / $my['price_015']));
            $v_messageUnitPrice = '단가 : '.$my['price_015'].'원/건';
        } else {
            $v_messageNumber = 0;
            $v_messageUnitPrice = '단가 : '.$my['price_015'].'원/건';
        }
    } else {
        if( $v_coin['coin']>0 && $my['price_phn']>0) {
            $v_messageNumber = number_format(floor($v_coin['coin'] / $my['price_phn']));
            $v_messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
        } else {
            $v_messageNumber = 0;
            $v_messageUnitPrice = '단가 : '.$my['price_phn'].'원/건';
        }
    }
    if($v_messageSmsNumber == "") $v_messageSmsNumber = "0"; //단문문자 발송가능건수
    if($v_messageMmsNumber == "") $v_messageMmsNumber = "0"; //장문문자 발송가능건수
    if($v_arlimTackNumber == "") $v_arlimTackNumber = "0"; //알림톡 발송가능건수
    $v_messageSmsNumberStr = "<p class='d_number'> ". $v_messageSmsNumber ."<span>건</span>"; //단문문자 발송가능건수
    $v_messageMmsNumberStr = "<p class='d_number'> ". $v_messageMmsNumber ."<span>건</span>"; //장문문자 발송가능건수
    $v_arlimTackNumberStr = "<p class='d_number'> ". $v_arlimTackNumber ."<span>건</span>"; //알림톡 발송가능건수

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
    $messageSmsNumberStr = "<p class='d_number'> 제한없음</span>"; //단문문자 발송가능건수
    $messageMmsNumberStr = "<p class='d_number'> 제한없음</span>"; //장문문자 발송가능건수
    $arlimTackNumberStr = "<p class='d_number'> 제한없음</span>"; //알림톡 발송가능건수
}
if($messageSmsNumber == "") $messageSmsNumber = "0"; //단문문자 발송가능건수
if($messageMmsNumber == "") $messageMmsNumber = "0"; //장문문자 발송가능건수
if($arlimTackNumber == "") $arlimTackNumber = "0"; //알림톡 발송가능건수
?>
	<div class="form_section">
		<div class="inner_tit">
			<h3>예치금 잔액</h3>
		</div>
		<div class="deposit_wrap">
			<div class="deposit_box">
        <i class="xi-credit-card"></i> 예치금 잔액
        <p class="d_number"><?php echo number_format($my['coin'],0); ?><span><?php echo $this->depositconfig->item('deposit_unit'); ?></span></p>
        <? if($pre_coin>0){ ?>
          <p class="precharge">
           (선충전 -<?=number_format($pre_coin)?><span>원</span>)
          </p>
        <? } ?>
      </div>
			<div class="deposit_box"><i class="xi-comment-o"></i> 단문문자 발송가능건수<?= $messageSmsNumberStr?></p></div>
			<div class="deposit_box"><i class="xi-mail-o"></i> 장문문자 발송가능건수<?=$messageMmsNumberStr?></p></div>
			<div class="deposit_box"><i class="xi-message-o"></i> 알림톡 발송가능건수<?= $arlimTackNumberStr ?></p></div>
		</div>
	</div>
    <? if($v_coin["coin"]>0&&$this->member->item("mem_voucher_yn")=="Y"){ ?>
    <div class="form_section">
		<div class="inner_tit mg_t30">
			<h3>바우처 잔액</h3>
		</div>
		<div class="deposit_wrap">
			<div class="deposit_box">
            <i class="xi-credit-card"></i> 바우처 잔액
            <p class="d_number"><?php echo number_format($v_coin['coin'],0); ?><span><?php echo $this->depositconfig->item('deposit_unit'); ?></span></p>
            </div>
			<div class="deposit_box"><i class="xi-comment-o"></i> 단문문자 발송가능건수<?= $v_messageSmsNumberStr?></p></div>
			<div class="deposit_box"><i class="xi-mail-o"></i> 장문문자 발송가능건수<?=$v_messageMmsNumberStr?></p></div>
			<div class="deposit_box"><i class="xi-message-o"></i> 알림톡 발송가능건수<?= $v_arlimTackNumberStr ?></p></div>
		</div>
	</div>
    <? } ?>
