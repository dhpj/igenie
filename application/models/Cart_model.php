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
	//��ٱ��� �� �ֹ��ݾ�
	public function cartamt(){
	    $amt = 0;
	    for($i =0; $i < count($_SESSION["user_cart"]); $i++){
	        $dcprice = preg_replace("/[^0-9]*/s", "", $_SESSION["user_cart"][$i]['dcprice']); //���ΰ� ���� ����
			$amt = $amt + $dcprice * $_SESSION["user_cart"][$i]['qty']; //�ֹ��ݾ� = ���ΰ� * �ֹ��Ǽ�
	    }
	    if(empty($amt)){
			$amt = 0;
		}
	    return number_format($amt);
	}

	//��ٱ��� �� �ֹ��Ǽ�
	public function cartqty(){
	    $qty = 0;
	    for($i =0; $i < count($_SESSION["user_cart"]); $i++){
	        $qty = $qty + $_SESSION["user_cart"][$i]['qty']; //�ֹ��Ǽ�
	        //log_message("ERROR", $i.':'.$qty.' / '.$_SESSION["user_cart"][$i]['qty']);
	    }
	    if(empty($qty)){
			$qty = 0;
		}
	    return $qty;
	}

}
