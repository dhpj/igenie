<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Nicepay {
    function reqPost($data, $url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);					//connection timeout 15
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));	//POST data
        curl_setopt($ch, CURLOPT_POST, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    function reqPost2($header, $data, $url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);					//connection timeout 15
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	//POST data
        curl_setopt($ch, CURLOPT_POST, true);
        $response = curl_exec($ch);
        log_message("error", $response);
        curl_close($ch);
        $this->set_nicepay($response);
        return $response;
    }
    function reqPost3($header, $data, $url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);					//connection timeout 15
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	//POST data
        curl_setopt($ch, CURLOPT_POST, true);
        $response = curl_exec($ch);
        log_message("error", $response);
        curl_close($ch);
        $this->set_confirm($response);
        return $response;
    }
    public function payment_cancel($oid, $reason, $cancelAmt = 0){
        $CI =& get_instance();
        function jsonRespDump($resp, $aid, $rid){
        	$respArr = json_decode($resp);
            log_message("error", "------------------------취소로그-----------------------start    auth_id = " . $aid . "       res_id = " . $rid);
        	foreach ( $respArr as $key => $value ){
        		if($key == "Data"){
        			log_message("error", decryptDump ($value, $merchantKey)."<br />");
        		}else{
        			log_message("error", $key . "=" . $value);
        		}
        	}
            log_message("error", "------------------------취소로그-----------------------end");
        }

        $CI->db
            ->select("
                a.mem_id
              , a.merchantKey
              , b.id AS res_id
              , b.tid
              , b.mid
              , b.moid
              , b.amt
              , a.id AS auth_id
            ")
            ->from("cb_wt_nicepay_auth AS a")
            ->join("cb_wt_nicepay_result AS b", "a.id = b.auth_id", "left")
            ->where("a.oid", $oid);
        $nice_rs = $CI->db->get()->row();

        $CI->db
            ->select("*")
            ->from("cb_wt_nicepay_cancel")
            ->where("res_id = " . $nice_rs->res_id . " AND (resultcode = '2001' OR resultcode = '2211')")
            ->order_by("id", "DESC")
            ->limit(1);
        $nice_cc = $CI->db->get()->row();

        $partFlag = 0;
        if (empty($nice_cc) && $cancelAmt == "0"){
            $ccAmt = $nice_rs->amt;
        } else if (empty($nice_cc) && $cancelAmt > 0){
            $ccAmt = $cancelAmt;
            $partFlag = 1;
        } else if (!empty($nice_cc) && $cancelAmt > 0) {
            $ccAmt = $cancelAmt;
            $partFlag = 1;
        } else {
            $ccAmt = $nice_cc->remainamt;
            $partFlag = 1;
        }

        $result = array();
        $mem_id = $nice_rs->mem_id;
        $res_id = $nice_rs->res_id;
        $merchantKey = $nice_rs->merchantKey;
        $tid = $nice_rs->tid;
        $mid = $nice_rs->mid;
        $moid = $nice_rs->moid;
        $ccAmt = $ccAmt;
        $cancelMsg = !empty($reason) ? $reason : "판매자취소";
        $partialCancelCode = $partFlag;
        $ediDate = date("YmdHis");
        $signData = bin2hex(hash('sha256', $mid . $ccAmt . $ediDate . $merchantKey, true));
        $charset = "utf-8";
        $mallreserved = "";
        $aid = $nice_rs->auth_id;

        $CI->db
            ->set("mem_id", $mem_id)
            ->set("res_id", $res_id)
            ->set("mid", $mid)
            ->set("moid", $moid)
            ->set("cancelamt", $ccAmt)
            ->set("cancelmsg", $cancelMsg)
            ->set("partialcancelcode", $partialCancelCode)
            ->set("edidate", $ediDate)
            ->set("signdata", $signData)
            ->set("charset", $charset)
            ->set("mallreserved1", $mallreserved)
            ->insert("cb_wt_nicepay_cancel");
        $ins_id = $CI->db->insert_id();

        try{
        	$data = Array(
        		'TID' => $tid,
        		'MID' => $mid,
        		'Moid' => $moid,
        		'CancelAmt' => $ccAmt,
        		'CancelMsg' => iconv("UTF-8", "EUC-KR", $cancelMsg),
        		'PartialCancelCode' => $partialCancelCode,
        		'EdiDate' => $ediDate,
        		'SignData' => $signData,
        		'CharSet' => $charset
        	);
            $res = $this->reqPost($data, "https://webapi.nicepay.co.kr/webapi/cancel_process.jsp");
        }catch(Exception $e){
        	$e->getMessage();
        	$result["code"] = "9999";
        	$result["msg"] = "통신실패";
            $CI->db
                ->set("resultcode", $result["code"])
                ->set("resultmsg", $result["msg"])
                ->where("id", $ins_id)
                ->update("cb_wt_nicepay_cancel");
        }
        if (empty($result["code"])){
            jsonRespDump($res, $aid, $rid);
            $res = json_decode($res);

            $result["code"] = $res->ResultCode;
            $result["msg"] = $res->ResultMsg;

            $CI->db
                ->set("resultcode", $res->ResultCode)
                ->set("resultmsg", $res->ResultMsg)
                ->set("paymethod", $res->PayMethod)
                ->set("tid", $res->TID)
                ->set("canceldate", $res->CancelDate)
                ->set("canceltime", $res->CancelTime)
                ->set("cancelnum", $res->CancelNum)
                ->set("remainamt", $res->RemainAmt)
                ->set("mallreserved2", $res->MallReserved)
                ->where("id", $ins_id)
                ->update("cb_wt_nicepay_cancel");

            if (in_array($res->ResultCode, config_item("refund_code"))){
                if (empty($nice_cc) && $cancelAmt == "0"){
                    $CI->db
                        ->set("cancel_flag", "Y")
                        ->where("id", $res_id)
                        ->update("cb_wt_nicepay_result");
                } else if (empty($nice_cc) && $cancelAmt > 0){
                    $CI->db
                        ->set("cancel_flag", "P")
                        ->where("id", $res_id)
                        ->update("cb_wt_nicepay_result");
                } else {
                    $CI->db
                        ->set("cancel_flag", "Y")
                        ->where("id", $res_id)
                        ->update("cb_wt_nicepay_result");
                }
            }
        }
        return $result["code"];
    }

    //정산대사 조회
    // $reqData = array(
    //     'usrId' => ""
    //   , 'merchantKey' => ""상점키
    //   , 'searchID' => ""로그인아이디
    //   , 'svcCd' => "" 결제수단
    //   , 'dt' => ""
    // );
    // dt 조회일자 (YYYYMMDD)
    function request_settle($reqData, $dtDiv = "0"){
        log_message("error", "--------------------------- 정산대사 조회 펑션 시작 --------------------------- " . date("Y-m-d H:i:s") . " / dt : " . $reqData['dt']);
        $sid = "0301003";
        try{
            $trDtm = date("YmdHis");
            $signData = bin2hex(hash('sha256', $sid . $reqData['usrId'] . $trDtm . $reqData['merchantKey'], true));
            $header = array("content-type: application/json; charset=UTF-8");
            $data = array(
                "header" => array(
                    'sid' => $sid,
            		'trDtm' => $trDtm,
            		'gubun' => "S",
            		'resCode' => "",
            		'resMsg' => "",
                )
              , "body" => array(
              		'usrId' => $reqData['usrId'],
              		'encKey' => $signData,
              		'svcCd' => $reqData['svcCd'],
              		'dtDiv' => $dtDiv,
              		'dt' => $reqData['dt'],
              		'idCl' => "2",
              		'searchID' => $reqData['searchID'],
              	)
            );

            // $header = array(
            //     "content-type: application/json; charset=UTF-8",
            //     'sid' => $sid,
            //     'trDtm' => $trDtm,
            //     'gubun' => "S",
            //     'resCode' => "",
            //     'resMsg' => "",
            // );
            // $data = array(
            //   		'usrId' => $reqData['usrId'],
            //   		'encKey' => $signData,
            //   		'svcCd' => $reqData['svcCd'],
            //   		'dtDiv' => $dtDiv,
            //   		'dt' => $reqData['dt'],
            //   		'idCl' => "2",
            //   		'searchID' => ($reqData['usrId'] . "m")
            //     );


            $this->reqPost2($header, json_encode($data), "https://data.nicepay.co.kr/recon/api");
        }catch(Exception $e){
            log_message("error", "--------------------------- 정산대사 조회 펑션 에러 --------------------------- " . date("Y-m-d H:i:s"));
            return;
        }
        log_message("error", "--------------------------- 정산대사 조회 펑션 끝 --------------------------- " . date("Y-m-d H:i:s"));
    }

    function set_nicepay($resp){
        $data = json_decode($resp);
        log_message("error", $data->header->resCode);
        if (!empty($data->header)){
            log_message("error", "--------------------------- 나이스페이 조회 응답 시작 --------------------------- " . date("Y-m-d H:i:s"));
            if ($data->header->resCode == "0000"){
                log_message("error", "--------------------------- 나이스페이 조회 응답 성공 --------------------------- " . date("Y-m-d H:i:s"));
                if ($data->header->sid == "0301003") $this->set_settle($data);
            } else {
                log_message("error", "--------------------------- 나이스페이 조회 응답 실패 (코드 : " . $data->header->resCode . " / " . $data->header->resMsg . ") --------------------------- " . date("Y-m-d H:i:s"));
            }
        }
    }

    function set_confirm($resp){
        $data = json_decode($resp);
        log_message("error", $data->header->resCode);
        if (!empty($data->header)){
            log_message("error", "--------------------------- 나이스페이 카드승인코드 응답 시작 --------------------------- " . date("Y-m-d H:i:s"));
            if ($data->header->resCode == "0000"){
                log_message("error", "--------------------------- 나이스페이 카드승인코드 응답 성공 --------------------------- " . date("Y-m-d H:i:s"));
                if ($data->header->sid == "0401001") $this->set_cardconfirm($data);
            } else {
                log_message("error", "--------------------------- 나이스페이 카드승인코드 응답 실패 (코드 : " . $data->header->resCode . " / " . $data->header->resMsg . ") --------------------------- " . date("Y-m-d H:i:s"));
            }
        }
    }

    function set_cardconfirm($data){
        $CI =& get_instance();
        log_message("error", "--------------------------- 카드승인코드 삽입 시작 --------------------------- " . date("Y-m-d H:i:s"));
        foreach($data->body->data as $key => $a){
            $CI->db
                ->set('appNo', $a->appNo)
                ->where('tid', $a->tid)
                ->update('cb_wt_nicepay_settle');
        }
        log_message("error", "--------------------------- 카드승인코드 삽입 끝 --------------------------- " . date("Y-m-d H:i:s"));
    }

    function set_settle($data){
        $CI =& get_instance();
        log_message("error", "--------------------------- 정산대사 조회 및 삽입 시작 --------------------------- " . date("Y-m-d H:i:s"));
        foreach($data->body->data as $key => $a){
            if ($key == "0"){
                $CI->db
                    ->where(array(
                        "settlmntDt" => $data->body->reqDt
                      , "mid" => $a->mid
                ))
                ->delete('cb_wt_nicepay_settle');
            }
            $CI->db
                ->set('trDt', $a->trDt)
                ->set('tid', $a->tid)
                ->set('mid', $a->mid)
                ->set('serviceId', $a->serviceId)
                ->set('subId', $a->subId)
                ->set('settlmntDt', $a->settlmntDt)
                ->set('svcCd', $a->svcCd)
                ->set('stateCd', $a->stateCd)
                ->set('transType', $a->transType)
                ->set('trAmt', $a->trAmt)
                ->set('depositAmt', $a->depositAmt)
                ->set('fee', $a->fee)
                ->set('vat', $a->vat)
                ->set('instmntMon', $a->instmntMon)
                ->set('ninstFee', $a->ninstFee)
                ->set('fnCd', $a->fnCd)
                ->set('partSvcCd', $a->partSvcCd)
                ->insert('cb_wt_nicepay_settle');

            if ($a->stateCd == '0'){
                $CI->db
                    ->select("
                        a.*
                      , c.mem_id
                    ")
                    ->from("cb_wt_nicepay_result AS a")
                    ->join("cb_wt_nicepay_auth AS c", "a.auth_id = c.id", "left")
                    ->where("a.tid", $a->tid);
                $row_data = $CI->db->get()->row();
                $mem_id = 0;
                $same_flag = "N";
                if (!empty($row_data)){
                    $mem_id = $row_data->mem_id;
                    if ($row_data->amt == $a->trAmt){
                        $same_flag = "Y";
                    } else {
                        $same_flag = "N";
                    }
                } else {
                    log_message("error", "자사 데이터 누락 tid : " . $a->tid);
                }

                $CI->db
                    ->set('mem_id', $mem_id)
                    ->set('same_flag', $same_flag)
                    ->where('tid', $a->tid)
                    ->update('cb_wt_nicepay_settle');
            } else if ($a->stateCd == '2') {
                $CI->db
                    ->select("
                        a.*
                    ")
                    ->from("cb_wt_nicepay_cancel AS a")
                    ->where("a.tid", $a->tid);
                $row_data = $CI->db->get()->row();
                $mem_id = 0;
                $same_flag = "N";
                if (!empty($row_data)){
                    $mem_id = $row_data->mem_id;
                    if (((-1) * $row_data->cancelamt) == $a->trAmt){
                        $same_flag = "Y";
                    } else {
                        $same_flag = "N";
                    }
                } else {
                    log_message("error", "자사 데이터 누락 tid : " . $a->tid);
                }

                $CI->db
                    ->set('mem_id', $mem_id)
                    ->set('same_flag', $same_flag)
                    ->where('tid', $a->tid)
                    ->update('cb_wt_nicepay_settle');
            }
        }
        log_message("error", "--------------------------- 정산대사 조회 및 삽입 끝 --------------------------- " . date("Y-m-d H:i:s"));
    }

    function request_cardconfirm($reqData, $dtDiv = "0"){
        log_message("error", "--------------------------- 카드승인코드 조회 펑션 시작 --------------------------- " . date("Y-m-d H:i:s") . " / dt : " . $reqData['dt']);
        $sid = "0401001";
        try{
            $trDtm = date("YmdHis");
            $signData = bin2hex(hash('sha256', $sid . $reqData['usrId'] . $trDtm . $reqData['merchantKey'], true));
            $header = array("content-type: application/json; charset=UTF-8");
            $data = array(
                "header" => array(
                    'sid' => $sid,
            		'trDtm' => $trDtm,
            		'gubun' => "S",
            		'resCode' => "",
            		'resMsg' => "",
                )
              , "body" => array(
              		'usrId' => $reqData['usrId'],
              		'encKey' => $signData,
              		'tid' => $reqData['tid'],
              		'type' => '0',
              	)
            );


            $this->reqPost3($header, json_encode($data), "https://data.nicepay.co.kr/issue/api");
        }catch(Exception $e){
            log_message("error", "--------------------------- 카드승인코드 조회 펑션 에러 --------------------------- " . date("Y-m-d H:i:s"));
            return;
        }
        log_message("error", "--------------------------- 카드승인코드 조회 펑션 끝 --------------------------- " . date("Y-m-d H:i:s"));
    }

}
