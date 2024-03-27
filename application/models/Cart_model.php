<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cart_model extends CB_Model
{
	//장바구니 총 주문금액
	public function cartamt(){
	    $amt = 0;
	    for($i =0; $i < count($_SESSION["user_cart"]); $i++){
	        $dcprice = preg_replace("/[^0-9]*/s", "", $_SESSION["user_cart"][$i]['dcprice']); //할인가 숫자 제거
			$amt = $amt + $dcprice * $_SESSION["user_cart"][$i]['qty']; //주문금액 = 할인가 * 주문건수
	    }
	    if(empty($amt)){
			$amt = 0;
		}
	    return number_format($amt);
	}

	//장바구니 총 주문건수
	public function cartqty(){
	    $qty = 0;
	    for($i =0; $i < count($_SESSION["user_cart"]); $i++){
	        $qty = $qty + $_SESSION["user_cart"][$i]['qty']; //주문건수
	        //log_message("ERROR", $i.':'.$qty.' / '.$_SESSION["user_cart"][$i]['qty']);
	    }
	    if(empty($qty)){
			$qty = 0;
		}
	    return $qty;
	}

}
