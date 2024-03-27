<?php
class Friend extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz');
    
    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');
    
    function __construct()
    {
        parent::__construct();
        
        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring'));
    }
    
    public function index()
    {
    }
    
    public function load_customer()
    {
        $filter = $this->input->post('filter');
        $where = ""; $param = array();
        
        // 2019.01.17 이수환 고객없음을 NONE로 변경
        //         if($filter) {
        //             if($filter == 'FT') {
        //                 $this->load_customerft();
        //             } else if($filter == 'NFT') {
        //                 $this->load_customernft();
        //             } else {
        //                 $where = " and ab_kind like ? ";
        //                 array_push($param, "%".$filter."%");
        //             }
        //         }
        if($filter) {
            if($filter == 'FT') {
                $this->load_customerft();
            } else if($filter == 'NFT') {
                $this->load_customernft();
            } else if($filter == 'NONE') {
                $where = " and ifnull(ab_kind, '') = ? ";
                array_push($param, '');
            } else {
                // 2019.01.18 이수환 차후 고객구분 멀티 선택시 처리를 위해 변경(LIKE => IN)
                //$where = " and ifnull(ab_kind, '') like ? ";
                //array_push($param, "%".$filter."%");
                $where = " and ifnull(ab_kind, '') in (?) ";
                array_push($param, $filter);
            }
        }
        
        log_Message("ERROR", "where : ".$where. "filter : ".$filter );
        $cnt = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'".$where, $param);
        header('Content-Type: application/json');
        echo '{"customer_count":'.$cnt.'}';
        exit;
    }

    
    public function load_customerft()
    {
        $filter = $this->input->post('filter');
        $where = ""; $param = array();
        $where = " and exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
        $cnt = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'".$where, $param);
        header('Content-Type: application/json');
        echo '{"customer_count":'.$cnt.'}';
        exit;
    }
    
    public function load_customernft()
    {
        $filter = $this->input->post('filter');
        $where = ""; $param = array();
        $where = " and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
        $cnt = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'".$where, $param);
        header('Content-Type: application/json');
        echo '{"customer_count":'.$cnt.'}';
        exit;
    }
    
    
    public function is_valid_callback($callback)
    {
        $_callback = preg_replace('/[^0-9]/', '', $callback);
        if (!
            preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080|007)\-?\d{3,4}\-?\d{4,5}$/",
                $_callback) && ! preg_match("/^(15|16|18)\d{2}\-?\d{4,5}$/", $_callback)) {
                    $_result = false;
                }
                if (preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080)\-?0{3,4}\-?\d{4}$/",
                    $_callback)) {
                        $_result = false;
                    }
                    return true;
    }
    
    
    public function all()
    {
        $customer_all_count = $this->input->post('customer_all_count');
        $customer_filter = $this->input->post('customer_filter');
        $upload_count = $this->input->post('upload_count');
        $templateCode = $this->input->post('templateCode');
        $pf_key = $this->input->post('pf_key');
        $senderBox = $this->input->post('senderBox');
        $kind = $this->input->post('kind');
        $reserveDt = $this->input->post('reserveDt');
        $templi_cont = $this->input->post('templi_cont');
        $lms_msg = $this->input->post('msg');
        $img_url = $this->input->post('img_url');
        $btn1 = $this->input->post('btn1');
        $btn2 = $this->input->post('btn2');
        $btn3 = $this->input->post('btn3');
        $btn4 = $this->input->post('btn4');
        $btn5 = $this->input->post('btn5');
        $tel_number = $this->input->post('tel_number');

        if(!$this->is_valid_callback($senderBox) && !empty($lms_msg)) {
            header('Content-Type: application/json');
            echo '{"message": "발신번호 사전등록 오류", "code": "fail"}';
            return;
        }
        
        //log_message("ERROR", "upload count : ".$templi_cont);
        if($upload_count) {
            /* 전체 발송인 경우 */
            //    if($_FILES) {
            $msg = $this->Biz_model->checkSendAbleMessage("ft", $this->member->item('mem_id'), $this->member->item('mem_userid'));
            if($msg!="") {
                header('Content-Type: application/json');
                echo '{"message":"'.$msg.'", "code": "fail"}';
                return;
            }
            //- 일 발신 한도 검사
            $sendCount = 9999999;
            if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_model->getTodaySent($this->member->item('mem_id'));
            if($sendCount < 1) {
                header('Content-Type: application/json');
                echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
                return;
            }
            /* 업로드된 엑셀을 사용하여 발송하는 경우 */
            $cache = $this->db->query("select * from cb_wt_send_cache where sc_mem_id=? limit 1", array($this->member->item('mem_id')))->row();
            
            $data = array();
            $data['mst_mem_id'] = $this->member->item('mem_id');
            $data['mst_template'] = '';
            $data['mst_profile'] = $pf_key;
            $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
            $data['mst_kind'] =  "ft";
            $data['mst_content'] =  $templi_cont;
            $data['mst_sms_send'] =  ($kind=="S") ? $kind : "";
            $data['mst_lms_send'] =  ($kind=="L" || $kind=="P") ? $kind : "";
            $data['mst_mms_send'] =  ($kind=="M") ? $kind : "";
            $data['mst_sms_content'] =  '';
            $data['mst_lms_content'] =  $this->input->post('msg');
            $data['mst_mms_content'] =  '';
            $data['mst_img_content'] =  $this->input->post('img_url');
            $data['mst_button'] = json_encode(array($btn1, $btn2, $btn3, $btn4, $btn5), JSON_UNESCAPED_UNICODE);
            $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
            $data['mst_sms_callback'] =  $senderBox;
            $data['mst_status'] = '1';
            $data['mst_qty'] =  ($customer_all_count) ? (($customer_all_count > $sendCount) ? $sendCount : $customer_all_count) : ((count($tel_number) > $sendCount) ? $sendCount : count($tel_number));
            $data['mst_amount'] = 0;	// ($this->Biz_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));
            
            //echo '<pre> :: ';print_r($data);
            //exit;
            
            $this->db->insert("cb_wt_msg_sent", $data);
            $gid = $this->db->insert_id();
            
            $this->load->library('sweettracker');
            $ok = $this->sweettracker->NewUploadToSendCache("ft" );
            if($ok > 0) { $ok = $this->sweettracker->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }
            
            header('Content-Type: application/json');
            echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
            return;
            //}
        }
        
        $this->load->library('sweettracker');
        //- 먼저 발송캐시에 등록함
        if($customer_all_count) {
            $ok = $this->sweettracker->phoneBookToSendCache("ft", $customer_filter);
        } else {
            $ok = $this->sweettracker->sendAgent("ft");
        }
        $msg = $this->Biz_model->checkSendAbleMessage("ft", $this->member->item('mem_id'), $this->member->item('mem_userid'));
        if($msg!="") {
            header('Content-Type: application/json');
            echo '{"message": "'.$msg.'", "code": "fail"}';
            return;
        }
        //- 일 발신 한도 검사
        $sendCount = 9999999;
        if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_model->getTodaySent($this->member->item('mem_id'));
        if($sendCount < 1) {
            header('Content-Type: application/json');
            echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
            return;
        }
        //- 발송내역에 기록
        $data = array();
        $data['mst_mem_id'] = $this->member->item('mem_id');
        $data['mst_template'] = '';
        $data['mst_profile'] = $pf_key;
        $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
        $data['mst_kind'] =  "ft";
        $data['mst_content'] =  $templi_cont;
        $data['mst_sms_send'] =  ($kind=="S") ? $kind : "";
        $data['mst_lms_send'] =  ($kind=="L" || $kind=="P") ? $kind : "";
        $data['mst_mms_send'] =  ($kind=="M") ? $kind : "";
        $data['mst_sms_content'] =  '';
        $data['mst_lms_content'] =  $this->input->post('msg');
        $data['mst_mms_content'] =  '';
        $data['mst_img_content'] =  $this->input->post('img_url');
        $data['mst_button'] = json_encode(array($btn1, $btn2, $btn3, $btn4, $btn5), JSON_UNESCAPED_UNICODE);
        $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
        $data['mst_sms_callback'] =  $senderBox;
        $data['mst_status'] = '1';
        $data['mst_qty'] =  ($customer_all_count) ? (($customer_all_count > $sendCount) ? $sendCount : $customer_all_count) : ((count($tel_number) > $sendCount) ? $sendCount : count($tel_number));
        $data['mst_amount'] = 0;	// ($this->Biz_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));
        
        //echo '<pre> :: ';print_r($data);
        //exit;
        
        $this->db->insert("cb_wt_msg_sent", $data);
        $gid = $this->db->insert_id();
        
        if($ok > 0) { $ok = $this->sweettracker->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }
        
        header('Content-Type: application/json');
        echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
        
    }
}
?>