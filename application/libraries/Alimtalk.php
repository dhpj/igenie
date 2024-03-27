<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * board table 을 주로 관리하는 class 입니다.
 */
class Alimtalk extends CI_Controller
{

	private $CI;

	function __construct()
	{
		$this->CI = & get_instance();
	}

    public function send($param, $method = "alimtalk_test") {
        $headers = array(
            "Content-Type:application/json;charset=utf-8",
            "Authorization: ".$param['Auth']
        );

        $parameters = array(
            "tmp_number"   => $param['temp'],
            "kakao_sender" => $param['sender'],
            "kakao_name"   => $param['name'],
            "kakao_phone"  => $param['phn'],
            "kakao_add1"   => $param['add1'],
            "kakao_add2"   => $param['add2'],
            "kakao_add3"   => $param['add3'],
            "kakao_add4"   => $param['add4'],
            "kakao_add5"   => $param['add5'],
            "kakao_add6"   => $param['add6'],
            "kakao_add7"   => $param['add7'],
            "kakao_add8"   => $param['add8'],
            "kakao_add9"   => $param['add9'],
            "kakao_add10"  => $param['add10'],
            "kakao_2nd"    => $param['2nd'],
            "kakao_url1_1" => $param['m1'],
            "kakao_url1_2" => $param['pc1'],
            "kakao_url2_1" => $param['m2'],
            "kakao_url2_2" => $param['pc2'],
            "kakao_url3_1" => $param['m3'],
            "kakao_url3_2" => $param['pc3'],
            "kakao_url4_1" => $param['m4'],
            "kakao_url4_2" => $param['pc4'],
            "kakao_url5_1" => $param['m5'],
            "kakao_url5_2" => $param['pc5'],
            "mid" => $param['mid'],
        );
        //log_message("error",$param['Auth']);
        $curl = curl_init();
        //$api_url = 'http://'. $_SERVER['HTTP_HOST'] .'/bizmsgapi/'.$method; //지니 알림톡 발송 API
        $api_url = 'http://igenie.co.kr/bizmsgapi/'.$method; //지니 알림톡 발송 API
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($parameters, JSON_UNESCAPED_UNICODE));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_NOSIGNAL, true);
        curl_setopt($curl, CURLOPT_VERBOSE, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $buffer = curl_exec ($curl);
        $cinfo = curl_getinfo($curl);
        curl_close($curl);
        //log_message("ERROR", "http code : ".$cinfo['http_code']);

        //ob_start();
        //var_dump($buffer);
        //var_dump($cinfo);
        //$result = ob_get_contents();
        //ob_end_clean();
        //log_message("ERROR", "buffer : ".$result);

        if ($cinfo['http_code'] != 200) { return ($buffer) ? json_decode($buffer) : ''; } else { return json_decode($buffer); }
    }
}
