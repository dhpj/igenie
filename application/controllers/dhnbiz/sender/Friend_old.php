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
        log_Message("ERROR", "filter2 : ".$where);
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
    
    // 2019.11.15 함수 추가 이수환 계정변경시 오발송 처리
    public function equal_send_mem_id()
    {
        $sender_mem_id = $this->input->post('senderMem_id');
        $returnEqual = "T";

        if ($this->member->item('mem_id') != $sender_mem_id) {
            $returnEqual = "F";
        }
        
        header('Content-Type: application/json');
        echo '{"senderCheck": "'.$returnEqual.'"}';
    }
    
    public function all()
    {
        //log_message("ERROR", "friend All 시작");
        $first_mem_id = $this->member->item('mem_id');
        $first_mem_userid = $this->member->item('mem_userid');
        if($this->session->userdata('login_stack')) {
            $login_stack = $this->session->userdata('login_stack');
            $first_mem_id = $login_stack[0];
            $first_mem = $this->db->query("select mem_userid from cb_member where mem_id=?", array($first_mem_id))->row();
            $first_mem_userid = $first_mem->mem_userid;
        }
        //log_Message("ERROR", "first_mem_id : ".$first_mem_id."    first_mem_userid : ".$first_mem_userid);
        
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
        // 2019.01.31. 이수환 추가 ; $mst_type1(1차 문자 = 카카오 관련 문자), $mst_type2(2차 문자 = 카카오톡 외의 문자)
        $mst_type1  = $this->input->post('mst_type1');
        $mst_type2  = $this->input->post('mst_type2');
       
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
            
            $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
            $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문

            // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
            $data['mst_price_ft'] = $this->Biz_model->price_ft;             // 친구톡단가
            $data['mst_price_ft_img'] = $this->Biz_model->price_ft_img;     // 친구톡이미지단가
            $data['mst_price_at'] = $this->Biz_model->price_at;             // 알림톡단가
            $data['mst_price_grs'] = $this->Biz_model->price_grs;           // 그린샷 웹(A) lms 단가
            $data['mst_price_grs_sms'] = $this->Biz_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
            $data['mst_price_grs_mms'] = $this->Biz_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
            $data['mst_price_nas'] = $this->Biz_model->price_nas;           // 그린샷 웹(B) lms 단가
            $data['mst_price_nas_sms'] = $this->Biz_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
            $data['mst_price_nas_mms'] = $this->Biz_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
            //             $data['mst_smt_price'] = $this->Biz_model->price_smt;           // 그린샷 웹(C) lms 단가
            //             $data['mst_price_smt_sms'] = $this->Biz_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            //             $data['mst_price_smt_mms'] = $this->Biz_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            $data['mst_smt_price'] = $this->Biz_model->price_smt == 0 ? $this->Biz_model->price_nas : $this->Biz_model->price_smt;           // 그린샷 웹(C) lms 단가
            $data['mst_price_smt_sms'] = $this->Biz_model->price_smt_sms == 0 ? $this->Biz_model->price_nas_sms : $this->Biz_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            $data['mst_price_smt_mms'] = $this->Biz_model->price_smt_mms == 0 ? $this->Biz_model->price_nas_mms : $this->Biz_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            $data['mst_price_ft_w_img'] = $this->Biz_model->price_ft_w_img;   // 친구톡 와이드이미지 단가
            
            $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
            $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)
            //echo '<pre> :: ';print_r($data);
            //exit;
            
            $this->db->insert("cb_wt_msg_sent", $data);
            $gid = $this->db->insert_id();
            
            $this->load->library('sweettracker');
            $ok = $this->sweettracker->NewUploadToSendCache("ft");
            if($ok > 0) { $ok = $this->sweettracker->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }
            
            header('Content-Type: application/json');
            echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
            return;
            //}
        }

        //- 일 발신 한도 검사
        $sendCount = 9999999;
        if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_model->getTodaySent($this->member->item('mem_id'));
        if($sendCount < 1) {
            header('Content-Type: application/json');
            echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
            return;
        }
        
        $this->load->library('sweettracker');
        //- 먼저 발송캐시에 등록함
        if($customer_all_count) {
            log_Message("ERROR", "customer_filter : ".$customer_filter." sendCount : ".$sendCount);
            $ok = $this->sweettracker->phoneBookToSendCache("ft", $customer_filter, $sendCount);
        } else {
            $ok = $this->sweettracker->sendAgent("ft");
        }
        $msg = $this->Biz_model->checkSendAbleMessage("ft", $this->member->item('mem_id'), $this->member->item('mem_userid'));
        if($msg!="") {
            header('Content-Type: application/json');
            echo '{"message": "'.$msg.'", "code": "fail"}';
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

        $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
        $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문자)

        // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
        $data['mst_price_ft'] = $this->Biz_model->price_ft;             // 친구톡단가
        $data['mst_price_ft_img'] = $this->Biz_model->price_ft_img;     // 친구톡이미지단가
        $data['mst_price_at'] = $this->Biz_model->price_at;             // 알림톡단가
        $data['mst_price_grs'] = $this->Biz_model->price_grs;           // 그린샷 웹(A) lms 단가
        $data['mst_price_grs_sms'] = $this->Biz_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
        $data['mst_price_grs_mms'] = $this->Biz_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
        $data['mst_price_nas'] = $this->Biz_model->price_nas;           // 그린샷 웹(B) lms 단가
        $data['mst_price_nas_sms'] = $this->Biz_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
        $data['mst_price_nas_mms'] = $this->Biz_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
        //         $data['mst_smt_price'] = $this->Biz_model->price_smt;           // 그린샷 웹(C) lms 단가
        //         $data['mst_price_smt_sms'] = $this->Biz_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
        //         $data['mst_price_smt_mms'] = $this->Biz_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
        $data['mst_smt_price'] = $this->Biz_model->price_smt == 0 ? $this->Biz_model->price_nas : $this->Biz_model->price_smt;           // 그린샷 웹(C) lms 단가
        $data['mst_price_smt_sms'] = $this->Biz_model->price_smt_sms == 0 ? $this->Biz_model->price_nas_sms : $this->Biz_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
        $data['mst_price_smt_mms'] = $this->Biz_model->price_smt_mms == 0 ? $this->Biz_model->price_nas_mms : $this->Biz_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
        $data['mst_price_ft_w_img'] = $this->Biz_model->price_ft_w_img;   // 친구톡 와이드이미지 단가
        
        $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
        $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)
        //echo '<pre> :: ';print_r($data);
        //exit;
        
        $this->db->insert("cb_wt_msg_sent", $data);
        $gid = $this->db->insert_id();
        
        if($ok > 0) { $ok = $this->sweettracker->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }
        
        header('Content-Type: application/json');
        echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
        //log_message("ERROR", "끝");
    }
    
    public function wide_all()
    {
        //log_message("ERROR", "friend All 시작");
        $first_mem_id = $this->member->item('mem_id');
        $first_mem_userid = $this->member->item('mem_userid');
        if($this->session->userdata('login_stack')) {
            $login_stack = $this->session->userdata('login_stack');
            $first_mem_id = $login_stack[0];
            $first_mem = $this->db->query("select mem_userid from cb_member where mem_id=?", array($first_mem_id))->row();
            $first_mem_userid = $first_mem->mem_userid;
        }
        //log_Message("ERROR", "first_mem_id : ".$first_mem_id."    first_mem_userid : ".$first_mem_userid);
        
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
        // 2019.01.31. 이수환 추가 ; $mst_type1(1차 문자 = 카카오 관련 문자), $mst_type2(2차 문자 = 카카오톡 외의 문자)
        $mst_type1  = $this->input->post('mst_type1');
        $mst_type2  = $this->input->post('mst_type2');
        
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
            
            $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
            $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문
            
            // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
            $data['mst_price_ft'] = $this->Biz_model->price_ft;             // 친구톡단가
            $data['mst_price_ft_img'] = $this->Biz_model->price_ft_img;     // 친구톡이미지단가
            $data['mst_price_at'] = $this->Biz_model->price_at;             // 알림톡단가
            $data['mst_price_grs'] = $this->Biz_model->price_grs;           // 그린샷 웹(A) lms 단가
            $data['mst_price_grs_sms'] = $this->Biz_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
            $data['mst_price_grs_mms'] = $this->Biz_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
            $data['mst_price_nas'] = $this->Biz_model->price_nas;           // 그린샷 웹(B) lms 단가
            $data['mst_price_nas_sms'] = $this->Biz_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
            $data['mst_price_nas_mms'] = $this->Biz_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
            //             $data['mst_smt_price'] = $this->Biz_model->price_smt;           // 그린샷 웹(C) lms 단가
            //             $data['mst_price_smt_sms'] = $this->Biz_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            //             $data['mst_price_smt_mms'] = $this->Biz_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            $data['mst_smt_price'] = $this->Biz_model->price_smt == 0 ? $this->Biz_model->price_nas : $this->Biz_model->price_smt;           // 그린샷 웹(C) lms 단가
            $data['mst_price_smt_sms'] = $this->Biz_model->price_smt_sms == 0 ? $this->Biz_model->price_nas_sms : $this->Biz_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            $data['mst_price_smt_mms'] = $this->Biz_model->price_smt_mms == 0 ? $this->Biz_model->price_nas_mms : $this->Biz_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            $data['mst_price_ft_w_img'] = $this->Biz_model->price_ft_w_img;   // 친구톡 와이드이미지 단가
            
            $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
            $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)
            //echo '<pre> :: ';print_r($data);
            //exit;
            
            $this->db->insert("cb_wt_msg_sent", $data);
            $gid = $this->db->insert_id();
            
            $this->load->library('sweettracker');
            $ok = $this->sweettracker->NewUploadToSendCache("ft");
            if($ok > 0) { $ok = $this->sweettracker->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount, 'Y'); }
            
            header('Content-Type: application/json');
            echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
            return;
            //}
        }
        
        //- 일 발신 한도 검사
        $sendCount = 9999999;
        if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_model->getTodaySent($this->member->item('mem_id'));
        if($sendCount < 1) {
            header('Content-Type: application/json');
            echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
            return;
        }
        
        $this->load->library('sweettracker');
        //- 먼저 발송캐시에 등록함
        if($customer_all_count) {
            log_Message("ERROR", "customer_filter : ".$customer_filter." sendCount : ".$sendCount);
            $ok = $this->sweettracker->phoneBookToSendCache("ft", $customer_filter, $sendCount);
        } else {
            $ok = $this->sweettracker->sendAgent("ft");
        }
        $msg = $this->Biz_model->checkSendAbleMessage("ft", $this->member->item('mem_id'), $this->member->item('mem_userid'));
        if($msg!="") {
            header('Content-Type: application/json');
            echo '{"message": "'.$msg.'", "code": "fail"}';
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
        
        $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
        $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문자)
        
        // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
        $data['mst_price_ft'] = $this->Biz_model->price_ft;             // 친구톡단가
        $data['mst_price_ft_img'] = $this->Biz_model->price_ft_img;     // 친구톡이미지단가
        $data['mst_price_at'] = $this->Biz_model->price_at;             // 알림톡단가
        $data['mst_price_grs'] = $this->Biz_model->price_grs;           // 그린샷 웹(A) lms 단가
        $data['mst_price_grs_sms'] = $this->Biz_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
        $data['mst_price_grs_mms'] = $this->Biz_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
        $data['mst_price_nas'] = $this->Biz_model->price_nas;           // 그린샷 웹(B) lms 단가
        $data['mst_price_nas_sms'] = $this->Biz_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
        $data['mst_price_nas_mms'] = $this->Biz_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
        //         $data['mst_smt_price'] = $this->Biz_model->price_smt;           // 그린샷 웹(C) lms 단가
        //         $data['mst_price_smt_sms'] = $this->Biz_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
        //         $data['mst_price_smt_mms'] = $this->Biz_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
        $data['mst_smt_price'] = $this->Biz_model->price_smt == 0 ? $this->Biz_model->price_nas : $this->Biz_model->price_smt;           // 그린샷 웹(C) lms 단가
        $data['mst_price_smt_sms'] = $this->Biz_model->price_smt_sms == 0 ? $this->Biz_model->price_nas_sms : $this->Biz_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
        $data['mst_price_smt_mms'] = $this->Biz_model->price_smt_mms == 0 ? $this->Biz_model->price_nas_mms : $this->Biz_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
        $data['mst_price_ft_w_img'] = $this->Biz_model->price_ft_w_img;   // 친구톡 와이드이미지 단가
        
        $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
        $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)
        //echo '<pre> :: ';print_r($data);
        //exit;
        
        $this->db->insert("cb_wt_msg_sent", $data);
        $gid = $this->db->insert_id();
        
        if($ok > 0) { $ok = $this->sweettracker->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount, 'Y'); }
        
        header('Content-Type: application/json');
        echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
        //log_message("ERROR", "끝");
    }
}
?>