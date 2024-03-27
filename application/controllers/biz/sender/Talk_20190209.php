<?php
class Talk extends CB_Controller {
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
        $cnt = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'");
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
    
    
    public function send()
    {
        $customer_all_count = $this->input->post('customer_all_count');
        $customer_filter = $this->input->post('customer_filter');
        $friend_list = $this->input->post('friend_list');
        $reserveDt = $this->input->post('reserveDt');
        $upload_count = $this->input->post('upload_count');
        $tel_number = $this->input->post('tel_number');
        
        $senderBox = $this->input->post('senderBox');
        $lms_msg = $this->input->post('msg');
        
        if(!$this->is_valid_callback($senderBox) && !empty($lms_msg)) {
            header('Content-Type: application/json');
            echo '{"message": "발신번호 사전등록 오류", "code": "fail"}';
            return;
        }
        log_message("ERROR", "RESERVE : ".$reserveDt);
        $user = $this->Biz_model->get_member_profile_key($this->member->item('mem_id'));
        
        if(/*$_FILES &&*/ $upload_count) {
            /* 전체 발송인 경우 */
            if($_FILES) {
                $msg = $this->Biz_model->checkSendAbleMessage("at", $this->member->item('mem_id'), $this->member->item('mem_userid'));
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
                /* 업로드된 엑셀을 사용하여 발송하는 경우 */
                $cache = $this->db->query("select * from cb_wt_send_cache where sc_mem_id=? limit 1", array($this->member->item('mem_id')))->row();
                
                /*
                 if(!$cache || $this->input->post('templi_code')!=$cache->sc_template) {
                 header('Content-Type: application/json');
                 echo '{"message": "템플릿 정보가 일치하지 않습니다.", "code": "fail"}'; exit;
                 }
                 */
                $data = array();
                $data['mst_mem_id'] = $this->member->item('mem_id');
                $data['mst_template'] = $this->input->post('templi_code');
                $data['mst_profile'] = $user->spf_key;//$this->input->post('pf_key'); //$user->spf_key;
                $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
                $data['mst_kind'] =  "at";
                $data['mst_content'] =  $this->input->post('templi_cont');
                $data['mst_sms_send'] =  'N';	//$this->input->post('kind');
                $data['mst_lms_send'] =  $this->input->post('kind');
                $data['mst_mms_send'] =  'N';	//$this->input->post('kind');
                $data['mst_sms_content'] =  '';
                $data['mst_lms_content'] =  $this->input->post('msg');
                $data['mst_mms_content'] =  '';
                $data['mst_img_content'] =  $this->input->post('img_url');
                $data['mst_button'] = json_encode(array($this->input->post('btn1'), $this->input->post('btn2'), $this->input->post('btn3'), $this->input->post('btn4'), $this->input->post('btn5')));
                $data['mst_sms_callback'] =  $this->input->post('senderBox');
                $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
                $data['mst_status'] = '1';
                $data['mst_qty'] =  ($upload_count > $sendCount) ? $sendCount : $upload_count;
                $data['mst_amount'] = 0;	// ($this->Biz_model->price_at * $upload_count);
                $this->db->insert("cb_wt_msg_sent", $data);
                $gid = $this->db->insert_id();
                
                $this->load->library('sweettracker');
                $ok = $this->sweettracker->NewUploadToSendCache("at" );
                if($ok > 0) { $ok = $this->sweettracker->sendAgentSendCache("at", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $user->spf_key, $this->input->post('reserveDt'), $sendCount); }
                
                header('Content-Type: application/json');
                //echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
                echo '{"message": "", "code": "'.(($ok>0) ? 'success' : 'fail').'"}';
                return;
            }
        }
        
        $this->load->library('sweettracker');
        //- 먼저 발송캐시에 등록함
        if($customer_all_count) {
            if($friend_list) {
                $ok = $this->sweettracker->phoneBookToSendCacheNFT("at");
            } else {
                $ok = $this->sweettracker->phoneBookToSendCache("at", $customer_filter);
            }
        } else {
            $ok = $this->sweettracker->sendAgent("at");
        }
        $msg = $this->Biz_model->checkSendAbleMessage("at", $this->member->item('mem_id'), $this->member->item('mem_userid'));
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
        $data['mst_template'] = $this->input->post('templi_code');
        $data['mst_profile'] = $user->spf_key;//$this->input->post('pf_key'); //$user->spf_key;
        $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
        $data['mst_kind'] =  "at";
        $data['mst_content'] =  $this->input->post('templi_cont');
        $data['mst_sms_send'] =  'N';	//$this->input->post('kind');
        $data['mst_lms_send'] =  $this->input->post('kind');
        $data['mst_mms_send'] =  'N';	//$this->input->post('kind');
        $data['mst_sms_content'] =  '';
        $data['mst_lms_content'] =  $this->input->post('msg');
        $data['mst_mms_content'] =  '';
        $data['mst_img_content'] =  $this->input->post('img_url');
        $data['mst_button'] = json_encode(array($this->input->post('btn1'), $this->input->post('btn2'), $this->input->post('btn3'), $this->input->post('btn4'), $this->input->post('btn5')));
        $data['mst_sms_callback'] =  $this->input->post('senderBox');
        $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
        
        $data['mst_status'] = '1';
        $data['mst_qty'] =  (count($this->input->post('tel_number')) > $sendCount) ? $sendCount : count($this->input->post('tel_number'));
        $data['mst_amount'] = 0;	// ($this->Biz_model->price_ft * count($this->input->post('tel_number')));
        $this->db->insert("cb_wt_msg_sent", $data);
        $gid = $this->db->insert_id();
        
        if($ok >0) { $this->sweettracker->sendAgentSendCache("at", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $user->spf_key, $this->input->post('reserveDt'), $sendCount); }
        
        header('Content-Type: application/json');
        if($_FILES) {
            echo '{"message": "", "code": "success"}';
        } else {
            $num = count($this->input->post('tel_number'));
            $err = $num - $ok;
            echo '{"success":'.$ok.',"fail":'.$err.',"num":'.$num.',"success_type":"at","fail_type":"at","message": "","code": "success"}';
        }
    }
}
?>