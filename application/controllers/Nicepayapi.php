<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Nicepayapi extends CB_Controller
{

    function __construct()
    {
        parent::__construct();
        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->helper(array('string'));
        $this->load->library(array('nicepay'));
    }

    function get_nicepay(){
        $subdate = strtotime("-1 days");
        $result_list = $this->db
            ->select('mid, login_id, merchantKey')
            ->from('cb_wt_nicepay_result AS a')
            ->where(array(
                'create_datetime >=' => (date("Y-m-d ", strtotime("-1 months")) . "00:00:00")
              , 'create_datetime <=' => (date("Y-m-d ") . "23:59:59")
              , 'cancel_flag !=' => 'Y'
            ))
            ->where_in('resultcode', config_item('success_code'))
            ->group_by(array('mid', 'login_id', 'merchantKey'))
            ->get()
            ->result();

        if (!empty($result_list)){
            foreach($result_list as $a){
                $usrId = $a->login_id;
                $merchantKey = $a->merchantKey;
                $searchID = $a->mid;
                $reqData = array(
                        'usrId' => $usrId
                      , 'merchantKey' => $merchantKey
                      , 'searchID' => $searchID
                      , 'svcCd' => "01"
                      , 'dt' => date("Ymd", $subdate)
                );
                $this->nicepay->request_settle($reqData);
            }
        } else {
            log_message("error", "--------------------------- 정산할 결과 데이터가 없음 --------------------------- " . date("Y-m-d H:i:s"));
        }
    }

    function get_cardconfirm(){
        $settlmntDt = date("Ymd", strtotime("-1 days"));
        $result_list = $this->db
            ->select('
                b.login_id
              , b.merchantKey
              , a.tid
            ')
            ->from('cb_wt_nicepay_settle AS a')
            ->join('cb_wt_nicepay_result AS b', 'a.tid = b.tid', 'left')
            ->where(array(
                'settlmntDt' => $settlmntDt
            ))
            ->get()
            ->result();

        if (!empty($result_list)){
            foreach($result_list as $a){
                $usrId = $a->login_id;
                $merchantKey = $a->merchantKey;
                $reqData = array(
                        'usrId' => $usrId
                      , 'merchantKey' => $merchantKey
                      , 'tid' => $a->tid
                );
                $this->nicepay->request_cardconfirm($reqData);
            }
        } else {
            log_message("error", "--------------------------- 정산할 결과 데이터가 없음 --------------------------- " . date("Y-m-d H:i:s"));
        }
    }

}
