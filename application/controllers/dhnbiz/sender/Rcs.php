<?php
class Rcs extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz_dhn');

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
        $cnt = $this->Biz_dhn_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'");
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
        //log_message("ERROR", "friend equal_send_mem_id 시작");
        $sender_mem_id = $this->input->post('senderMem_id');
        $returnEqual = "T";
        //log_message("ERROR", "start returnEqual : ".$returnEqual);
        //log_message("ERROR", "sender_mem_id : ".$sender_mem_id);
        //log_message("ERROR", "this->member->item('mem_id') : ".$this->member->item('mem_id'));
        if ($this->member->item('mem_id') != $sender_mem_id) {
            $returnEqual = "F";
            //log_message("ERROR", "returnEqual : ".$returnEqual);
        }

        header('Content-Type: application/json');
        echo '{"senderCheck": "'.$returnEqual.'"}';
    }

//     public function send()
//     {
//         $first_mem_id = $this->member->item('mem_id');
//         $first_mem_userid = $this->member->item('mem_userid');
//         if($this->session->userdata('login_stack')) {
//             $login_stack = $this->session->userdata('login_stack');
//             $first_mem_id = $login_stack[0];
//             $first_mem = $this->db->query("select mem_userid from cb_member where mem_id=?", array($first_mem_id))->row();
//             $first_mem_userid = $first_mem->mem_userid;
//         }
//         //log_message("ERROR", "first_mem_id : ".$first_mem_id."    first_mem_userid : ".$first_mem_userid);
//
//         $customer_all_count = $this->input->post('customer_all_count');
//         $customer_filter = $this->input->post('customer_filter');
//         $friend_list = $this->input->post('friend_list');
//         $reserveDt = $this->input->post('reserveDt');
//         $upload_count = $this->input->post('upload_count');
//         $tel_number = $this->input->post('tel_number');
//
//         $senderBox = $this->input->post('senderBox');
//         $lms_msg = $this->input->post('msg');
//
//         // 2019.01.31. 이수환 추가 ; $mst_type1(1차 문자 = 카카오 관련 문자), $mst_type2(2차 문자 = 카카오톡 외의 문자)
//         $mst_type1  = $this->input->post('mst_type1');
//         $mst_type2  = $this->input->post('mst_type2');
//         $mst_type3  = $this->input->post('mst_type3');
//
//         // if($mst_type2 == "wc") {
//         //     $mst_type2 = "rc";
//         // } else if ($mst_type2 == "wcs") {
//         //     $mst_type2 = "rcs";
//         // }
//         //log_message("ERROR", "friend_list : ".$friend_list);
//
//         if(!$this->is_valid_callback($senderBox) && !empty($lms_msg)) {
//             header('Content-Type: application/json');
//             echo '{"message": "발신번호 사전등록 오류", "code": "fail"}';
//             return;
//         }
//         //log_message("ERROR", "RESERVE : ".$reserveDt);
//         $user = $this->Biz_dhn_model->get_member_profile_key($this->member->item('mem_id'));
//
//         if(/*$_FILES &&*/ $upload_count) {
//             /* 전체 발송인 경우 */
//             //log_message("ERROR", "_FILES1 : ".count($_FILES));
//             //if($_FILES) { // 2019/04.18 이수환 주석 처리
//                 //log_message("ERROR", "_FILES2 : ".count($_FILES));
//                 $msg = $this->Biz_dhn_model->checkSendAbleMessage("at", $this->member->item('mem_id'), $this->member->item('mem_userid'));
//                 if($msg!="") {
//                     header('Content-Type: application/json');
//                     echo '{"message": "'.$msg.'", "code": "fail"}';
//                     return;
//                 }
//                 //- 일 발신 한도 검사
//                 $sendCount = 9999999;
//                 if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
//                 if($sendCount < 1) {
//                     header('Content-Type: application/json');
//                     echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
//                     return;
//                 }
//                 /* 업로드된 엑셀을 사용하여 발송하는 경우 */
//                 $cache = $this->db->query("select * from cb_wt_send_cache where sc_mem_id=? limit 1", array($this->member->item('mem_id')))->row();
//
//                 /*
//                  if(!$cache || $this->input->post('templi_code')!=$cache->sc_template) {
//                  header('Content-Type: application/json');
//                  echo '{"message": "템플릿 정보가 일치하지 않습니다.", "code": "fail"}'; exit;
//                  }
//                  */
//                 $data = array();
//                 $data['mst_mem_id'] = $this->member->item('mem_id');
//                 $data['mst_template'] = $this->input->post('templi_code');
//                 $data['mst_profile'] = $user->spf_key;//$this->input->post('pf_key'); //$user->spf_key;
//                 $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
//                 $data['mst_kind'] =  "rc";
//                 $data['mst_content'] =  $this->input->post('templi_cont');
//                 $data['mst_sms_send'] =  'N';	//$this->input->post('kind');
//                 $data['mst_lms_send'] =  $this->input->post('kind');
//                 $data['mst_mms_send'] =  'N';	//$this->input->post('kind');
//                 $data['mst_sms_content'] =  '';
//                 $data['mst_lms_content'] =  $this->input->post('msg');
//                 $data['mst_mms_content'] =  '';
//                 $data['mst_img_content'] =  $this->input->post('img_url');
//                 $data['mst_button'] = json_encode(array($this->input->post('btn1'), $this->input->post('btn2'), $this->input->post('btn3'), $this->input->post('btn4'), $this->input->post('btn5')));
//                 $data['mst_sms_callback'] =  $this->input->post('senderBox');
//                 $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
//                 $data['mst_status'] = '1';
//                 $data['mst_qty'] =  ($upload_count > $sendCount) ? $sendCount : $upload_count;
//                 $data['mst_amount'] = 0;	// ($this->Biz_dhn_model->price_at * $upload_count);
//
//                 $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
//                 $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문
//
//                 // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
//                 $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
//                 $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
//                 $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
//                 $data['mst_price_grs'] = $this->Biz_dhn_model->price_grs;           // 그린샷 웹(A) lms 단가
//                 $data['mst_price_grs_sms'] = $this->Biz_dhn_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
//                 $data['mst_price_grs_mms'] = $this->Biz_dhn_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
//                 $data['mst_price_nas'] = $this->Biz_dhn_model->price_nas;           // 그린샷 웹(B) lms 단가
//                 $data['mst_price_nas_sms'] = $this->Biz_dhn_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
//                 $data['mst_price_nas_mms'] = $this->Biz_dhn_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
// //                 $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
// //                 $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
// //                 $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
//                 $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
//                 $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
//                 $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;;   // 그린샷 웹(C) mms 단가
// 				$data['mst_price_ft_w_img'] = $this->Biz_dhn_model->price_ft_w_img;   // 친구톡 와이드이미지 단가
//
//                 $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
//                 $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)
//
// 				//2021-01-21 개인정보동의ID 추가
// 				$data['mst_agreeid'] = $this->input->post('agreeid'); //개인정보동의ID
//
//                 $this->db->insert("cb_wt_msg_sent", $data);
//                 $gid = $this->db->insert_id();
//
//                 $this->load->library('KTrcs');
//                 $ok = $this->ktrcs->NewUploadToSendCache("rc");
//                 if($ok > 0) { $ok = $this->ktrcs->sendAgentSendCache("rc", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $user->spf_key, $this->input->post('reserveDt'), $sendCount); }
//
// 				//2021-01-21 개인정보동의 데이타 추가
// 				// if($ok > 0 and $data['mst_agreeid'] != "" and $gid != "") { $ok = $this->dhnkakao->sendAgentSendCacheAgree($data['mst_agreeid'], $this->member->item('mem_id'), $gid, $this->member->item('mem_userid')); }
//
//                 header('Content-Type: application/json');
//                 //echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
//                 echo '{"message": "", "code": "'.(($ok>0) ? 'success' : 'fail').'"}';
//                 return;
//             //}  // 2019.04.18 이수환 주석 처리
//         }
//
//         $this->load->library('KTrcs');
//         //- 먼저 발송캐시에 등록함
//         if($customer_all_count) {
//             if($friend_list) {
//                 //log_message("ERROR", "CALL phoneBookToSendCacheNFT");
//                 $ok = $this->ktrcs->phoneBookToSendCacheNFT("rc");
//             } else {
//                 //log_message("ERROR", "CALL phoneBookToSendCache");
//                 $ok = $this->ktrcs->phoneBookToSendCache("rc", $customer_filter);
//             }
//         } else {
//             $ok = $this->ktrcs->sendAgent("rc");
//         }
//         $msg = $this->Biz_dhn_model->checkSendAbleMessage("rc", $this->member->item('mem_id'), $this->member->item('mem_userid'));
//         if($msg!="") {
//             header('Content-Type: application/json');
//             echo '{"message": "'.$msg.'", "code": "fail"}';
//             return;
//         }
//         //- 일 발신 한도 검사
//         $sendCount = 9999999;
//         if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
//         if($sendCount < 1) {
//             header('Content-Type: application/json');
//             echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
//             return;
//         }
//         //- 발송내역에 기록
//         $data = array();
//         $data['mst_mem_id'] = $this->member->item('mem_id');
//         $data['mst_template'] = $this->input->post('templi_code');
//         $data['mst_profile'] = $user->spf_key;//$this->input->post('pf_key'); //$user->spf_key;
//         $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
//         $data['mst_kind'] =  "rc";
//         $data['mst_content'] =  $this->input->post('templi_cont');
//         $data['mst_sms_send'] =  'N';	//$this->input->post('kind');
//         $data['mst_lms_send'] =  $this->input->post('kind');
//         $data['mst_mms_send'] =  'N';	//$this->input->post('kind');
//         $data['mst_sms_content'] =  '';
//         $data['mst_lms_content'] =  $this->input->post('msg');
//         $data['mst_mms_content'] =  '';
//         $data['mst_img_content'] =  $this->input->post('img_url');
//         $data['mst_button'] = json_encode(array($this->input->post('btn1'), $this->input->post('btn2'), $this->input->post('btn3'), $this->input->post('btn4'), $this->input->post('btn5')));
//         $data['mst_sms_callback'] =  $this->input->post('senderBox');
//         $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
//
//         $data['mst_status'] = '1';
//         $data['mst_qty'] =  (count($this->input->post('tel_number')) > $sendCount) ? $sendCount : count($this->input->post('tel_number'));
//         $data['mst_amount'] = 0;	// ($this->Biz_dhn_model->price_ft * count($this->input->post('tel_number')));
//
//         $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
//         $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문
//         $data['mst_type3'] =  $mst_type3;
//         // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
//         $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
//         $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
//         $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
//         $data['mst_price_grs'] = $this->Biz_dhn_model->price_grs;           // 그린샷 웹(A) lms 단가
//         $data['mst_price_grs_sms'] = $this->Biz_dhn_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
//         $data['mst_price_grs_mms'] = $this->Biz_dhn_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
//         $data['mst_price_nas'] = $this->Biz_dhn_model->price_nas;           // 그린샷 웹(B) lms 단가
//         $data['mst_price_nas_sms'] = $this->Biz_dhn_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
//         $data['mst_price_nas_mms'] = $this->Biz_dhn_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
// //         $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
// //         $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
// //         $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
//         $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
//         $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
//         $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;;   // 그린샷 웹(C) mms 단가
// 		$data['mst_price_ft_w_img'] = $this->Biz_dhn_model->price_ft_w_img;   // 친구톡 와이드이미지 단가
//
//         $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
//         $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)
//
// 		//2021-01-21 개인정보동의ID 추가
// 		$data['mst_agreeid'] = $this->input->post('agreeid'); //개인정보동의ID
// 		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mst_agreeid : ". $data['mst_agreeid']);
//
//         $this->db->insert("cb_wt_msg_sent", $data);
//         $gid = $this->db->insert_id();
//
//         if($ok >0) { $this->dhnkakao->sendAgentSendCache("at", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $user->spf_key, $this->input->post('reserveDt'), $sendCount); }
//
// 		//2021-01-21 개인정보동의 데이타 추가
// 		if($ok > 0 and $data['mst_agreeid'] != "" and $gid != "") { $ok = $this->dhnkakao->sendAgentSendCacheAgree($data['mst_agreeid'], $this->member->item('mem_id'), $gid, $this->member->item('mem_userid')); }
//
//         header('Content-Type: application/json');
//         if($_FILES) {
//             echo '{"message": "", "code": "success"}';
//         } else {
//             $num = count($this->input->post('tel_number'));
//             $err = $num - $ok;
//             echo '{"success":'.$ok.',"fail":'.$err.',"num":'.$num.',"success_type":"at","fail_type":"at","message": "","code": "success"}';
//         }
//     }

    //광고성 rcs 발송
	public function adv_send()
    {
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
        $friend_list = $this->input->post('friend_list');
        $reserveDt = $this->input->post('reserveDt');
        $upload_count = $this->input->post('upload_count');
        $tel_number = $this->input->post('tel_number');
        $senderBox = $this->input->post('senderBox');
        $lms_msg = $this->input->post('msg');

        // 2019.01.31. 이수환 추가 ; $mst_type1(1차 문자 = 카카오 관련 문자), $mst_type2(2차 문자 = 카카오톡 외의 문자)
        $mst_type1  = $this->input->post('mst_type1');
        $mst_type2  = $this->input->post('mst_type2');
        $mst_type3  = $this->input->post('mst_type3');

        $rcs_type = "";
        $rcr_kind = "";
        if($mst_type2 == "rc") {
            $rcs_type = "L";
            $rcr_kind = "RCSLMS";
        } else if ($mst_type2 == "rcs") {
            $rcs_type = "S";
            $rcr_kind = "RCSSMS";
        } else if ($mst_type2 == "rct") {
            $rcs_type = "T";
            $rcr_kind = "RCSTMPL";
        } else if ($mst_type2 == "rcm") {
            $rcs_type = "M";
            $rcr_kind = "RCSMMS";
        }

        $groupId  = $this->input->post('groupId'); //그룹번호
        $link_type  = $this->input->post('link_type'); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터) 2020-09-16 추가

        if(!$this->is_valid_callback($senderBox) && !empty($lms_msg)) {
            header('Content-Type: application/json');
            echo '{"message": "발신번호 사전등록 오류", "code": "fail"}';
            return;
        }
        //log_message("ERROR", "RESERVE : ".$reserveDt);
        $user = $this->Biz_dhn_model->get_member_brand($this->member->item('mem_id'));
        $nowdatetime = cdate('Y-m-d H:i:s');
        if(/*$_FILES &&*/ $upload_count) {
            /* 전체 발송인 경우 */
            //log_message("ERROR", "_FILES1 : ".count($_FILES));
            //if($_FILES) { // 2019/04.18 이수환 주석 처리
            //log_message("ERROR", "_FILES2 : ".count($_FILES));
            // $msg = $this->Biz_dhn_model->checkSendAbleMessage("at", $this->member->item('mem_id'), $this->member->item('mem_userid'));
            // if($msg!="") {
            //     header('Content-Type: application/json');
            //     echo '{"message": "'.$msg.'", "code": "fail"}';
            //     return;
            // }
            //- 일 발신 한도 검사
            $sendCount = 9999999;
            // if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
            // if($sendCount < 1) {
            //     header('Content-Type: application/json');
            //     echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
            //     return;
            // }
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
            // $data['mst_template'] = $this->input->post('templi_code');
            // $data['mst_profile'] = $user->brd_brand;//$this->input->post('pf_key'); //$user->spf_key;

            $data['mst_datetime'] = $nowdatetime;
            $data['mst_kind'] =  "rc";
            // $data['mst_content'] =  $this->input->post('templi_cont');
            $data['mst_sms_send'] =  'N';	//$this->input->post('kind');
            $data['mst_lms_send'] =  $this->input->post('kind');
            $data['mst_mms_send'] =  'N';	//$this->input->post('kind');
            $data['mst_sms_content'] =  '';
            $data['mst_lms_content'] =  $this->input->post('msg');
            $data['mst_mms_content'] =  '';
            // $data['mst_img_content'] =  $this->input->post('img_url');
            $data['mst_button'] = json_encode(array($this->input->post('btn1'), $this->input->post('btn2'), $this->input->post('btn3'), $this->input->post('btn4'), $this->input->post('btn5')));
            $data['mst_sms_callback'] =  $this->input->post('senderBox');
            $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
            $data['mst_status'] = '1';
            $data['mst_qty'] =  ($upload_count > $sendCount) ? $sendCount : $upload_count;
            $data['mst_amount'] = 0;	// ($this->Biz_dhn_model->price_at * $upload_count);

            $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
            $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문
            $data['mst_type3'] =  $mst_type3;

            //2022-03-21 바우처 추가 윤재박
            $data['mst_sent_voucher'] = (!empty($this->input->post('voucher')))? $this->input->post('voucher') : "N";

            // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
            if($data['mst_sent_voucher']=="N" || $data['mst_sent_voucher']=="B"){
                $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
                $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
                $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
                $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
                $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
                $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
                $data['mst_price_rcs'] = $this->Biz_dhn_model->price_rcs;
                $data['mst_price_rcs_sms'] = $this->Biz_dhn_model->price_rcs_sms;
                $data['mst_price_rcs_mms'] = $this->Biz_dhn_model->price_rcs_mms;
                $data['mst_price_rcs_tem'] = $this->Biz_dhn_model->price_rcs_tem;
            }else{
                $data['mst_price_ft'] = $this->Biz_dhn_model->price_v_ft;             // 친구톡단가
                $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_v_ft_img;     // 친구톡이미지단가
                $data['mst_price_at'] = $this->Biz_dhn_model->price_v_at;             // 알림톡단가
                $data['mst_smt_price'] = $this->Biz_dhn_model->price_v_smt;           // 그린샷 웹(C) lms 단가
                $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_v_smt_sms;   // 그린샷 웹(C) sms 단가
                $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_v_smt_mms;   // 그린샷 웹(C) mms 단가
                $data['mst_price_rcs'] = $this->Biz_dhn_model->price_v_rcs;
                $data['mst_price_rcs_sms'] = $this->Biz_dhn_model->price_v_rcs_sms;
                $data['mst_price_rcs_mms'] = $this->Biz_dhn_model->price_v_rcs_mms;
                $data['mst_price_rcs_tem'] = $this->Biz_dhn_model->price_v_rcs_tem;
            }
            $data['mst_price_grs'] = $this->Biz_dhn_model->price_grs;           // 그린샷 웹(A) lms 단가
            $data['mst_price_grs_sms'] = $this->Biz_dhn_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
            $data['mst_price_grs_mms'] = $this->Biz_dhn_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
            $data['mst_price_nas'] = $this->Biz_dhn_model->price_nas;           // 그린샷 웹(B) lms 단가
            $data['mst_price_nas_sms'] = $this->Biz_dhn_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
            $data['mst_price_nas_mms'] = $this->Biz_dhn_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
            //                 $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
            //                 $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            //                 $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            // $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
            // $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            // $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            $data['mst_price_ft_w_img'] = $this->Biz_dhn_model->price_ft_w_img;   // 친구톡 와이드이미지 단가



            $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
            $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)

			//2021-01-21 개인정보동의ID 추가
			$data['mst_agreeid'] = $this->input->post('agreeid'); //개인정보동의ID

            $this->db->insert("cb_wt_msg_sent", $data);
            $gid = $this->db->insert_id();

            $rdata = array();
            $rdata['msr_mst_id'] = $gid;
            $rdata['msr_template'] = $this->input->post('templi_code');
            $rdata['msr_brand'] = $user->brd_brand;
            $rdata['msr_brandkey'] = $user->brd_brand_key;
            $rdata['msr_datetime'] = $nowdatetime;
            $rdata['msr_kind'] =  $rcr_kind;
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > post templi_cont : ". $this->input->post('templi_cont')."length".strlen($this->input->post('templi_cont')));

            // $rdata['msr_content'] =  $this->input->post('templi_cont'); preg_replace('/\r\n/','\n',$text)

            $rdata['msr_content'] = str_replace("\r\n" , "\n", $this->input->post('templi_cont'));
            if($this->input->post('reserveDt')!="00000000000000"){
                $itime = date($this->input->post('reserveDt'));
            }else{
                $itime = date("Y-m-d H:i:s");
            }

            $rdata['msr_exptime'] = date("Y-m-d H:i:s", strtotime("+30 minutes", strtotime($itime)));
            $rdata['msr_body'] = str_replace("\r\n" , "\n", $this->input->post('mbody'));
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > rdata msr_content : ". $rdata['msr_content']."length".strlen($rdata['msr_content']));
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 수정 gid : ". $gid);
            $rdata['msr_button1'] =  $this->input->post('btn1');
            $rdata['msr_button2'] =  $this->input->post('btn2');
            $rdata['msr_button3'] =  $this->input->post('btn3');
            $rdata['msr_button'] =  $this->input->post('btnall');
            $rdata['msr_chatbotid'] =  $user->brd_sms_callback;

            $this->db->insert("cb_wt_msg_rcs", $rdata);

			$link_type  = $this->input->post('link_type'); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터) 2020-09-16 추가
			if($link_type == "smart" or $link_type == "coupon" or $link_type == "editor" or $link_type == "smartEditor"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터) 2020-09-16 추가
				$this->ums_save($gid);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 수정 gid : ". $gid);
			}else{
				//에디터 사용이 아닌 경우 내용 삭제
				$bizurl = $this->input->post('bizurl');
				$mem_id = $this->member->item('mem_id');
				$sql = "DELETE FROM cb_alimtalk_ums WHERE short_url = '". $bizurl ."' and mem_id = '". $mem_id ."' ";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 내용 삭제 : ". $sql);
				$this->db->query($sql);
			}


            $this->load->library('KTrcs');
            $ok = $this->ktrcs->NewUploadToSendCache("rc");
            if($ok > 0) { $ok = $this->ktrcs->sendAgentSendCache($rcs_type, $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $user->spf_key, $this->input->post('reserveDt'), $sendCount); }

			//2021-01-21 개인정보동의 데이타 추가
			// if($ok > 0 and $data['mst_agreeid'] != "" and $gid != "") { $ok = $this->ktrcs->sendAgentSendCacheAgree($data['mst_agreeid'], $this->member->item('mem_id'), $gid, $this->member->item('mem_userid')); }

            header('Content-Type: application/json');
            //echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
            echo '{"message": "", "code": "'.(($ok>0) ? 'success' : 'fail').'"}';
            return;
            //}  // 2019.04.18 이수환 주석 처리
        }

        $this->load->library('KTrcs');
        //- 먼저 발송캐시에 등록함
		log_message("ERROR", $_SERVER['REQUEST_URI'] ." > customer_all_count : ". $customer_all_count);
        if($customer_all_count) {
            if($friend_list) {
                $ok = $this->ktrcs->phoneBookToSendCacheNFT("rc");
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > phoneBookToSendCacheNFT");
            } else {
                $ok = $this->ktrcs->phoneBookToSendCache("rc", $customer_filter);
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > phoneBookToSendCache");
            }
        } else {
            $ok = $this->ktrcs->sendAgent("rc");
            log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sendAgent");
        }
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ktrcs End");
        // $msg = $this->Biz_dhn_model->checkSendAbleMessage("at", $this->member->item('mem_id'), $this->member->item('mem_userid'));
        // if($msg!="") {
        //     header('Content-Type: application/json');
        //     echo '{"message": "'.$msg.'", "code": "fail"}';
        //     return;
        // }
        //- 일 발신 한도 검사
        $sendCount = 9999999;
        // if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
        // if($sendCount < 1) {
        //     header('Content-Type: application/json');
        //     echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
        //     return;
        // }
        //- 발송내역에 기록
        $data = array();
        $data['mst_mem_id'] = $this->member->item('mem_id');
        // $data['mst_template'] = $this->input->post('templi_code');
        // $data['mst_profile'] = $user->spf_key;//$this->input->post('pf_key'); //$user->spf_key;
        $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
        $data['mst_kind'] =  "rc";
        // $data['mst_content'] =  $this->input->post('templi_cont');
        $data['mst_sms_send'] =  'N';	//$this->input->post('kind');
        $data['mst_lms_send'] =  $this->input->post('kind');
        $data['mst_mms_send'] =  'N';	//$this->input->post('kind');
        $data['mst_sms_content'] =  '';
        $data['mst_lms_content'] =  $this->input->post('msg');
        $data['mst_mms_content'] =  '';
        $data['mst_img_content'] =  $this->input->post('img_url');
        // $data['mst_button'] = json_encode(array($this->input->post('btn1'), $this->input->post('btn2'), $this->input->post('btn3'), $this->input->post('btn4'), $this->input->post('btn5')));
        $data['mst_sms_callback'] =  $this->input->post('senderBox');
        $data['mst_reserved_dt'] =  $this->input->post('reserveDt');

        $data['mst_status'] = '1';
        $data['mst_qty'] =  (count($this->input->post('tel_number')) > $sendCount) ? $sendCount : count($this->input->post('tel_number'));
        $data['mst_amount'] = 0;	// ($this->Biz_dhn_model->price_ft * count($this->input->post('tel_number')));

        $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
        $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문
        $data['mst_type3'] =  $mst_type3;
        //2022-03-21 바우처 추가 윤재박
        $data['mst_sent_voucher'] = (!empty($this->input->post('voucher')))? $this->input->post('voucher') : "N";
        // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
        if($data['mst_sent_voucher']=="N" || $data['mst_sent_voucher']=="B"){
            $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
            $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
            $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
            $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
            $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            $data['mst_price_rcs'] = $this->Biz_dhn_model->price_rcs;
            $data['mst_price_rcs_sms'] = $this->Biz_dhn_model->price_rcs_sms;
            $data['mst_price_rcs_mms'] = $this->Biz_dhn_model->price_rcs_mms;
            $data['mst_price_rcs_tem'] = $this->Biz_dhn_model->price_rcs_tem;
        }else{
            $data['mst_price_ft'] = $this->Biz_dhn_model->price_v_ft;             // 친구톡단가
            $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_v_ft_img;     // 친구톡이미지단가
            $data['mst_price_at'] = $this->Biz_dhn_model->price_v_at;             // 알림톡단가
            $data['mst_smt_price'] = $this->Biz_dhn_model->price_v_smt;           // 그린샷 웹(C) lms 단가
            $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_v_smt_sms;   // 그린샷 웹(C) sms 단가
            $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_v_smt_mms;   // 그린샷 웹(C) mms 단가
            $data['mst_price_rcs'] = $this->Biz_dhn_model->price_v_rcs;
            $data['mst_price_rcs_sms'] = $this->Biz_dhn_model->price_v_rcs_sms;
            $data['mst_price_rcs_mms'] = $this->Biz_dhn_model->price_v_rcs_mms;
            $data['mst_price_rcs_tem'] = $this->Biz_dhn_model->price_v_rcs_tem;
        }
        $data['mst_price_grs'] = $this->Biz_dhn_model->price_grs;           // 그린샷 웹(A) lms 단가
        $data['mst_price_grs_sms'] = $this->Biz_dhn_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
        $data['mst_price_grs_mms'] = $this->Biz_dhn_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
        $data['mst_price_nas'] = $this->Biz_dhn_model->price_nas;           // 그린샷 웹(B) lms 단가
        $data['mst_price_nas_sms'] = $this->Biz_dhn_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
        $data['mst_price_nas_mms'] = $this->Biz_dhn_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
        //         $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
        //         $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
        //         $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
        // $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
        // $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
        // $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
        $data['mst_price_ft_w_img'] = $this->Biz_dhn_model->price_ft_w_img;   // 친구톡 와이드이미지 단가


        $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
        $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)

		//2021-01-21 개인정보동의ID 추가
		$data['mst_agreeid'] = $this->input->post('agreeid'); //개인정보동의ID

        $this->db->insert("cb_wt_msg_sent", $data);
        $gid = $this->db->insert_id();

        $rdata = array();
        $rdata['msr_mst_id'] = $gid;
        $rdata['msr_template'] = $this->input->post('templi_code');
        $rdata['msr_brand'] = $user->brd_brand;
        $rdata['msr_brandkey'] = $user->brd_brand_key;
        $rdata['msr_datetime'] = $nowdatetime;
        $rdata['msr_kind'] =  $rcr_kind;
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > post templi_cont 2 : ". $this->input->post('templi_cont')."length".strlen($this->input->post('templi_cont')));
        if($this->input->post('reserveDt')!="00000000000000"){
            $itime = date($this->input->post('reserveDt'));
        }else{
            $itime = date("Y-m-d H:i:s");
        }
        $rdata['msr_exptime'] = date("Y-m-d H:i:s", strtotime("+30 minutes", strtotime($itime)));
        $rdata['msr_content'] =  $this->input->post('templi_cont');
        $rdata['msr_body'] = $this->input->post('mbody');
        $rdata['msr_button1'] =  $this->input->post('btn1');
        $rdata['msr_button2'] =  $this->input->post('btn2');
        $rdata['msr_button3'] =  $this->input->post('btn3');
        $rdata['msr_button'] =  $this->input->post('btnall');
        $rdata['msr_chatbotid'] =  $user->brd_sms_callback;

        $this->db->insert("cb_wt_msg_rcs", $rdata);

        if($ok >0) { $this->ktrcs->sendAgentSendCache($rcs_type, $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $user->brd_brand, $this->input->post('reserveDt'), $sendCount); }

		//2021-01-21 개인정보동의 데이타 추가
		// if($ok > 0 and $data['mst_agreeid'] != "" and $gid != "") { $ok = $this->ktrcs->sendAgentSendCacheAgree($data['mst_agreeid'], $this->member->item('mem_id'), $gid, $this->member->item('mem_userid')); }

        /*
		$html = array();
        $html['short_url'] = $this->input->post('bizurl');
        $html['html'] = $this->input->post('html');
        $html['mem_id'] = $this->member->item('mem_id');
        $html['mst_id'] = $gid;
        $html['server_id'] = '58';
        $this->db->insert("cb_alimtalk_ums", $html);
		*/

		$link_type  = $this->input->post('link_type'); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터) 2020-09-16 추가
		if($link_type == "smart" or $link_type == "coupon" or $link_type == "editor" or $link_type == "smartEditor"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터) 2020-09-16 추가
			$this->ums_save($gid);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 수정 gid : ". $gid);
		}else{
			//에디터 사용이 아닌 경우 내용 삭제
			$bizurl = $this->input->post('bizurl');
			$mem_id = $this->member->item('mem_id');
			$sql = "DELETE FROM cb_alimtalk_ums WHERE short_url = '". $bizurl ."' and mem_id = '". $mem_id ."' ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 내용 삭제 : ". $sql);
			$this->db->query($sql);
		}



        header('Content-Type: application/json');
        if($_FILES) {
            echo '{"message": "", "code": "success"}';
        } else {
            $num = count($this->input->post('tel_number'));
            $err = $num - $ok;
            echo '{"success":'.$ok.',"fail":'.$err.',"num":'.$num.',"success_type":"at","fail_type":"at","message": "","code": "success"}';
        }
    }

	//UMS 내용 저장
	public function ums_save($gid = 0){
		$link_type  = $this->input->post('link_type'); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터) 2020-09-16 추가
		$bizurl = $this->input->post('bizurl'); //에디터코드
		//$mem_id = $this->member->item('mem_id');
		$mem_id = $this->input->post("mem_id"); //회원번호
		if(empty($mem_id)) {
		    $mem_id = $this->member->item('mem_id'); //회원번호
		}
		if(empty($gid) || $gid == 0) {
		    //$gid = $this->member->item('gid');
		    $gid = $this->input->post('gid');
		}
		if(empty($gid) || $gid == "") $gid = 0;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", bizurl : ". $bizurl .", gid : ". $gid);
		$html = array();
		$html['short_url'] = $bizurl; //에디터코드
        $html['mem_id'] = $mem_id; //회원번호
        $html['mst_id'] = $gid; //발송번호
        $html['server_id'] = '54'; //서버IP
        $html['link_type'] = $link_type; //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터)
		if($link_type == "smart"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터)
			$html['psd_code'] = $this->input->post('psd_code'); //스마트전단 코드
			$html['psd_url'] = $this->input->post('psd_url'); //스마트전단 링크 URL
		}else if($link_type == "coupon"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터)
			$html['pcd_code'] = $this->input->post('pcd_code'); //스마트쿠폰 코드
			$html['pcd_type'] = $this->input->post('pcd_type'); //스마트쿠폰 타입
			$html['pcd_url'] = $this->input->post('pcd_url'); //스마트쿠폰 링크 URL
		}else if($link_type == "smartEditor"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터)
			$html['psd_code'] = $this->input->post('psd_code'); //스마트전단 코드
			$html['psd_url'] = $this->input->post('psd_url'); //스마트전단 링크 URL
			$html['html'] = $this->input->post('html'); //에디터내용
			$html['url'] = $this->input->post('dhnlurl'); //에디터 링크 URL
		}else{
			$html['html'] = $this->input->post('html'); //에디터내용
			$html['url'] = $this->input->post('dhnlurl'); //에디터 링크 URL
		}

		//$sql = "SELECT count(1) AS cnt FROM cb_alimtalk_ums dt WHERE short_url = '". $bizurl ."' and mem_id = '". $mem_id ."' ";
		//log_message("ERROR", "Query : ".$sql);
		//$cnt = $this->db->query($sql)->row()->cnt;
		//if($cnt == 0){ //신규등록
		//	$this->db->insert("cb_alimtalk_ums", $html);
		//}else{
		//	$where = array();
		//	$where['short_url'] = $bizurl;
		//	$where['mem_id'] = $mem_id;
		//	$this->db->update("cb_alimtalk_ums", $html, $where);
		//}
		$this->db->insert("cb_alimtalk_ums", $html);
	}

    public function adv_send_fail()
    {
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
        $friend_list = $this->input->post('friend_list');
        $reserveDt = $this->input->post('reserveDt');
        $upload_count = $this->input->post('upload_count');
        $tel_number = $this->input->post('tel_number');
        $senderBox = $this->input->post('senderBox');
        $lms_msg = $this->input->post('msg');

        // 2019.01.31. 이수환 추가 ; $mst_type1(1차 문자 = 카카오 관련 문자), $mst_type2(2차 문자 = 카카오톡 외의 문자)
        $mst_type1  = $this->input->post('mst_type1');
        $mst_type2  = $this->input->post('mst_type2');
        $mst_type3  = $this->input->post('mst_type3');

        $rcs_type = "";
        $rcr_kind = "";
        if($mst_type2 == "rc") {
            $rcs_type = "L";
            $rcr_kind = "RCSLMS";
        } else if ($mst_type2 == "rcs") {
            $rcs_type = "S";
            $rcr_kind = "RCSSMS";
        } else if ($mst_type2 == "rct") {
            $rcs_type = "T";
            $rcr_kind = "RCSTMPL";
        } else if ($mst_type2 == "rcm") {
            $rcs_type = "M";
            $rcr_kind = "RCSMMS";
        }

        $groupId  = $this->input->post('groupId'); //그룹번호
        $link_type  = $this->input->post('link_type'); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터) 2020-09-16 추가

        if(!$this->is_valid_callback($senderBox) && !empty($lms_msg)) {
            header('Content-Type: application/json');
            echo '{"message": "발신번호 사전등록 오류", "code": "fail"}';
            return;
        }
        //log_message("ERROR", "RESERVE : ".$reserveDt);
        $user = $this->Biz_dhn_model->get_member_brand($this->member->item('mem_id'));
        $nowdatetime = cdate('Y-m-d H:i:s');
            /* 전체 발송인 경우 */
            //log_message("ERROR", "_FILES1 : ".count($_FILES));
            //if($_FILES) { // 2019/04.18 이수환 주석 처리
            //log_message("ERROR", "_FILES2 : ".count($_FILES));
            // $msg = $this->Biz_dhn_model->checkSendAbleMessage("at", $this->member->item('mem_id'), $this->member->item('mem_userid'));
            // if($msg!="") {
            //     header('Content-Type: application/json');
            //     echo '{"message": "'.$msg.'", "code": "fail"}';
            //     return;
            // }
            //- 일 발신 한도 검사
            $sendCount = 9999999;
            // if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
            // if($sendCount < 1) {
            //     header('Content-Type: application/json');
            //     echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
            //     return;
            // }
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
            // $data['mst_template'] = $this->input->post('templi_code');
            // $data['mst_profile'] = $user->brd_brand;//$this->input->post('pf_key'); //$user->spf_key;

            $data['mst_datetime'] = $nowdatetime;
            $data['mst_kind'] =  "rc";
            // $data['mst_content'] =  $this->input->post('templi_cont');
            $data['mst_sms_send'] =  'N';	//$this->input->post('kind');
            $data['mst_lms_send'] =  $this->input->post('kind');
            $data['mst_mms_send'] =  'N';	//$this->input->post('kind');
            $data['mst_sms_content'] =  '';
            $data['mst_lms_content'] =  $this->input->post('msg');
            $data['mst_mms_content'] =  '';
            // $data['mst_img_content'] =  $this->input->post('img_url');
            $data['mst_button'] = json_encode(array($this->input->post('btn1'), $this->input->post('btn2'), $this->input->post('btn3'), $this->input->post('btn4'), $this->input->post('btn5')));
            $data['mst_sms_callback'] =  $this->input->post('senderBox');
            $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
            $data['mst_status'] = '1';
            $data['mst_qty'] =  ($upload_count > $sendCount) ? $sendCount : $upload_count;
            $data['mst_amount'] = 0;	// ($this->Biz_dhn_model->price_at * $upload_count);

            $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
            $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문
            $data['mst_type3'] =  $mst_type3;

            //2022-03-21 바우처 추가 윤재박
            $data['mst_sent_voucher'] = (!empty($this->input->post('voucher')))? $this->input->post('voucher') : "N";

            // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
            if($data['mst_sent_voucher']=="N" || $data['mst_sent_voucher']=="B"){
                $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
                $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
                $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
                $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
                $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
                $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
                $data['mst_price_rcs'] = $this->Biz_dhn_model->price_rcs;
                $data['mst_price_rcs_sms'] = $this->Biz_dhn_model->price_rcs_sms;
                $data['mst_price_rcs_mms'] = $this->Biz_dhn_model->price_rcs_mms;
                $data['mst_price_rcs_tem'] = $this->Biz_dhn_model->price_rcs_tem;
            }else{
                $data['mst_price_ft'] = $this->Biz_dhn_model->price_v_ft;             // 친구톡단가
                $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_v_ft_img;     // 친구톡이미지단가
                $data['mst_price_at'] = $this->Biz_dhn_model->price_v_at;             // 알림톡단가
                $data['mst_smt_price'] = $this->Biz_dhn_model->price_v_smt;           // 그린샷 웹(C) lms 단가
                $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_v_smt_sms;   // 그린샷 웹(C) sms 단가
                $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_v_smt_mms;   // 그린샷 웹(C) mms 단가
                $data['mst_price_rcs'] = $this->Biz_dhn_model->price_v_rcs;
                $data['mst_price_rcs_sms'] = $this->Biz_dhn_model->price_v_rcs_sms;
                $data['mst_price_rcs_mms'] = $this->Biz_dhn_model->price_v_rcs_mms;
                $data['mst_price_rcs_tem'] = $this->Biz_dhn_model->price_v_rcs_tem;
            }
            $data['mst_price_grs'] = $this->Biz_dhn_model->price_grs;           // 그린샷 웹(A) lms 단가
            $data['mst_price_grs_sms'] = $this->Biz_dhn_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
            $data['mst_price_grs_mms'] = $this->Biz_dhn_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
            $data['mst_price_nas'] = $this->Biz_dhn_model->price_nas;           // 그린샷 웹(B) lms 단가
            $data['mst_price_nas_sms'] = $this->Biz_dhn_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
            $data['mst_price_nas_mms'] = $this->Biz_dhn_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
            //                 $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
            //                 $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            //                 $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            // $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
            // $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            // $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            $data['mst_price_ft_w_img'] = $this->Biz_dhn_model->price_ft_w_img;   // 친구톡 와이드이미지 단가


            $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
            $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)

			//2021-01-21 개인정보동의ID 추가
			$data['mst_agreeid'] = $this->input->post('agreeid'); //개인정보동의ID

            $this->db->insert("cb_wt_msg_sent", $data);
            $gid = $this->db->insert_id();

            $rdata = array();
            $rdata['msr_mst_id'] = $gid;
            $rdata['msr_template'] = $this->input->post('templi_code');
            $rdata['msr_brand'] = $user->brd_brand;
            $rdata['msr_brandkey'] = $user->brd_brand_key;
            $rdata['msr_datetime'] = $nowdatetime;
            $rdata['msr_kind'] =  $rcr_kind;
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > post templi_cont : ". $this->input->post('templi_cont')."length".strlen($this->input->post('templi_cont')));

            // $rdata['msr_content'] =  $this->input->post('templi_cont'); preg_replace('/\r\n/','\n',$text)
            $rdata['msr_content'] = str_replace("\r\n" , "\n", $this->input->post('templi_cont'));
            $itime = date("Y-m-d H:i:s");
            $rdata['msr_exptime'] = date("Y-m-d H:i:s", strtotime("+30 minutes", strtotime($itime)));
            $rdata['msr_body'] = str_replace("\r\n" , "\n", $this->input->post('mbody'));
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > rdata msr_content : ". $rdata['msr_content']."length".strlen($rdata['msr_content']));
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 수정 gid : ". $gid);
            $rdata['msr_button1'] =  $this->input->post('btn1');
            $rdata['msr_button2'] =  $this->input->post('btn2');
            $rdata['msr_button3'] =  $this->input->post('btn3');
            $rdata['msr_button'] =  $this->input->post('btnall');
            $rdata['msr_chatbotid'] =  $user->brd_sms_callback;

            $this->db->insert("cb_wt_msg_rcs", $rdata);

			$link_type  = $this->input->post('link_type'); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터) 2020-09-16 추가
			if($link_type == "smart" or $link_type == "coupon" or $link_type == "editor" or $link_type == "smartEditor"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터) 2020-09-16 추가
				$this->ums_save($gid);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 수정 gid : ". $gid);
			}else{
				//에디터 사용이 아닌 경우 내용 삭제
				$bizurl = $this->input->post('bizurl');
				$mem_id = $this->member->item('mem_id');
				$sql = "DELETE FROM cb_alimtalk_ums WHERE short_url = '". $bizurl ."' and mem_id = '". $mem_id ."' ";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 내용 삭제 : ". $sql);
				$this->db->query($sql);
			}


            $this->load->library('KTrcs');
            $ok = $this->ktrcs->NewUploadToSendCache_fail("rc");
            if($ok > 0) { $ok = $this->ktrcs->sendAgentSendCache($rcs_type, $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $user->spf_key, $this->input->post('reserveDt'), $sendCount); }

			//2021-01-21 개인정보동의 데이타 추가
			// if($ok > 0 and $data['mst_agreeid'] != "" and $gid != "") { $ok = $this->ktrcs->sendAgentSendCacheAgree($data['mst_agreeid'], $this->member->item('mem_id'), $gid, $this->member->item('mem_userid')); }

            header('Content-Type: application/json');
            //echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
            echo '{"message": "", "code": "'.(($ok>0) ? 'success' : 'fail').'"}';
            return;
            //}  // 2019.04.18 이수환 주석 처리
    }

}
?>
