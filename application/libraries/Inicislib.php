<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inicislib {

    public function curl_inicis_cancel($mem_id, $order_id, $can_amt = "", $con_amt = ""){
        $url = "http://smart.dhn.kr/inicis/inicis_refund_api";

        $post   = array(
            "server_name" => "133g"
          , "mem_id" => $mem_id
          , "order_id" => $order_id
          , "can_amt" => $can_amt
          , "con_amt" => $con_amt
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data",
            ),
        ));
        $buffer = curl_exec($curl);
        $cinfo = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($cinfo['http_code'] != 200){
            log_message("ERROR", "cinfo : ".$cinfo);
        }
        $response = json_decode($buffer, true);
        if ($cinfo['http_code'] != 200) {
            return ($buffer) ? $response : '';
        } else {
            return $response;
        }
    }

    public function curl_inicis_refund_check($mem_id, $order_id){
        $url = "http://smart.dhn.kr/inicis/inicis_refund_check_api";

        $post   = array(
            "server_name" => "133g"
          , "mem_id" => $mem_id
          , "order_id" => $order_id
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data",
            ),
        ));
        $buffer = curl_exec($curl);
        $cinfo = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($cinfo['http_code'] != 200){
            log_message("ERROR", "cinfo : ".$cinfo);
        }
        log_message("error", "SONG : " . $buffer);
        $response = json_decode($buffer, true);
        if ($cinfo['http_code'] != 200) {
            return ($buffer) ? $response : '';
        } else {
            return $response;
        }
    }
}
