<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Korpay {
    public function kor_cancel($oid){
        $CI =& get_instance();
        $k_data = $CI->db
                ->select("*")
                ->from("cb_wt_korpay")
                ->where("k_oid", $oid)
                ->get()->row();

            $headers = array(
                "Content-Type:application/json",
            );
            $data = array(
                "mid" => $k_data->k_mid,
                "ordNo" => $oid,
                "canNm" => $k_data->k_buyername,
                "canMsg" => "구매자 취소",
                "canAmt" => $k_data->k_amt,
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, "https://ndevpgapi.korpay.com/api/cancel");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);					//connection timeout 15
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));	//POST data
            curl_setopt($ch, CURLOPT_POST, true);
            $buffer = curl_exec ($ch);
            curl_close($ch);
            $resdata = json_decode($buffer);

            $CI->db
                ->set("kcl_k_id", $k_data->k_id)
                ->set("kcl_cancel_rescode", $resdata->res_code)
                ->set("kcl_cancel_resmsg", $resdata->res_msg)
                ->set("kcl_cancel_resdate", $resdata->cancel_date)
                ->set("kcl_cancel_restime", $resdata->cancel_time)
                ->insert("cb_wt_korpay_cancel_log");

            if ($resdata->res_code == "0000"){
                $CI->db
                    ->set("k_cancel_flag", "1")
                    ->set("k_cancel_name", "구매자")
                    ->set("k_cancel_msg", "구매자 취소")
                    ->set("k_cancel_rescode", $resdata->res_code)
                    ->set("k_cancel_resmsg", $resdata->res_msg)
                    ->set("k_cancel_resdate", $resdata->cancel_date)
                    ->set("k_cancel_restime", $resdata->cancel_time)
                    ->where("k_oid", $oid)
                    ->update("cb_wt_korpay");

                $CI->db
                    ->set("status", "4")
                    ->set("cal_person", "c")
                    ->where("id", $oid)
                    ->update("cb_orders");

            }

            return $resdata->res_code;
    }
}
