<?php
class Friend_v3 extends CB_Controller {
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
        //log_message("ERROR", "filter2 : ".$where);
        $cnt = $this->Biz_dhn_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'".$where, $param);
        header('Content-Type: application/json');
        echo '{"customer_count":'.$cnt.'}';
        exit;
    }


    public function load_customerft()
    {
        $filter = $this->input->post('filter');
        $where = ""; $param = array();
        $where = " and exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
        $cnt = $this->Biz_dhn_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'".$where, $param);
        header('Content-Type: application/json');
        echo '{"customer_count":'.$cnt.'}';
        exit;
    }

    public function load_customernft()
    {
        $filter = $this->input->post('filter');
        $where = ""; $param = array();
        $where = " and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
        $cnt = $this->Biz_dhn_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'".$where, $param);
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
        //log_message("ERROR", "first_mem_id : ".$first_mem_id."    first_mem_userid : ".$first_mem_userid);

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
        $mst_type3  = $this->input->post('mst_type3');
		//2021-01-05 2차 알림톡 추가
		$alim_code = $this->input->post('alim_code'); //2차 알림톡 템플릿코드
		$alim_cont = $this->input->post('alim_cont'); //2차 알림톡 내용
        $btn1_alim = $this->input->post('btn1_alim');
        $btn2_alim = $this->input->post('btn2_alim');
        $btn3_alim = $this->input->post('btn3_alim');
        $btn4_alim = $this->input->post('btn4_alim');
        $btn5_alim = $this->input->post('btn5_alim');

        if(!$this->is_valid_callback($senderBox) && !empty($lms_msg)) {
            header('Content-Type: application/json');
            echo '{"message": "발신번호 사전등록 오류", "code": "fail"}';
            return;
        }
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $this->load->library('dhnkakao_ib');
        }else{
            $this->load->library('dhnkakao');
        }
        //log_message("ERROR", "upload count : ".$templi_cont);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_count : ". $upload_count);
        if($upload_count) {
            /* 전체 발송인 경우 */
            //    if($_FILES) {
            $msg = $this->Biz_dhn_model->checkSendAbleMessage("ft", $this->member->item('mem_id'), $this->member->item('mem_userid'));
            if($msg!="") {
                header('Content-Type: application/json');
                echo '{"message":"'.$msg.'", "code": "fail"}';
                return;
            }
            //- 일 발신 한도 검사
            $sendCount = 9999999;
            //폰문자
            // if($mst_type2=="wp1" || $mst_type2=="wp2"){
            //     if($this->input->post('reserveDt')!="00000000000000"){
            //         $resdate = substr($this->input->post('reserveDt'), 0, 8);
            //         if(date("Ymd")!=date('Ymd', strtotime($resdate))){
            //             if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getDateSent($this->member->item('mem_id'),date('Y-m-d', strtotime($resdate)));
            //         }else{
            //             if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
            //         }
            //     }else{
            //         if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
            //     }
            // }

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
            $data['mst_img_link_url'] = !empty($this->input->post('img_link')) ? $this->input->post('img_link') : "";
            $data['mst_button'] = json_encode(array($btn1, $btn2, $btn3, $btn4, $btn5), JSON_UNESCAPED_UNICODE);
            $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
            $data['mst_sms_callback'] =  $senderBox;
            $data['mst_status'] = '1';
            $data['mst_qty'] =  ($customer_all_count) ? (($customer_all_count > $sendCount) ? $sendCount : $customer_all_count) : ((count($tel_number) > $sendCount) ? $sendCount : count($tel_number));
            $data['mst_amount'] = 0;	// ($this->Biz_dhn_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));

            $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
            $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문
            $data['mst_type3'] =  $mst_type3;
            //2022-03-21 바우처 추가 윤재박
            $data['mst_sent_voucher'] = (!empty($this->input->post('voucher')))? $this->input->post('voucher') : "N";

            if($data['mst_sent_voucher']=="N" || $data['mst_sent_voucher']=="B"){
                $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
                $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
                $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
                $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
                $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
                $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            }else{
                $data['mst_price_ft'] = $this->Biz_dhn_model->price_v_ft;             // 친구톡단가
                $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_v_ft_img;     // 친구톡이미지단가
                $data['mst_price_at'] = $this->Biz_dhn_model->price_v_at;             // 알림톡단가
                $data['mst_smt_price'] = $this->Biz_dhn_model->price_v_smt;           // 그린샷 웹(C) lms 단가
                $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_v_smt_sms;   // 그린샷 웹(C) sms 단가
                $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_v_smt_mms;   // 그린샷 웹(C) mms 단가
            }
            // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
            // $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
            // $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
            // $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
            $data['mst_price_grs'] = $this->Biz_dhn_model->price_grs;           // 그린샷 웹(A) lms 단가
            $data['mst_price_grs_sms'] = $this->Biz_dhn_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
            $data['mst_price_grs_mms'] = $this->Biz_dhn_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
            $data['mst_price_nas'] = $this->Biz_dhn_model->price_nas;           // 그린샷 웹(B) lms 단가
            $data['mst_price_nas_sms'] = $this->Biz_dhn_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
            $data['mst_price_nas_mms'] = $this->Biz_dhn_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
//             $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
//             $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
//             $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            // $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
            // $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            // $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;;   // 그린샷 웹(C) mms 단가
			$data['mst_price_ft_w_img'] = $this->Biz_dhn_model->price_ft_w_img;   // 친구톡 와이드이미지 단가

            $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
            $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)

			//2021-01-05 2차 알림톡 추가
			$data['mst_2nd_alim_code'] = $alim_code; //2차 알림톡 템플릿코드
			$data['mst_2nd_alim_cont'] = $alim_cont; //2차 알림톡 내용
			$data['mst_2nd_alim_btn'] = json_encode(array($btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim), JSON_UNESCAPED_UNICODE); //2차 알림톡 버튼
			$data['mst_2nd_alim'] = ($mst_type2 == "ai") ? "1" : "0"; //2차 알림톡 구분(0.알림톡 발송안함, 1.대형 카카오 발송, 2.스윗트래커 카카오 발송)



            //echo '<pre> :: ';print_r($data);
            //exit;

            $this->db->insert("cb_wt_msg_sent", $data);
            $gid = $this->db->insert_id();
            $link_type  = $this->input->post('link_type');
            if($link_type == "smartEditor" or $link_type == "couponEditor" or $link_type == "smartcouponEditor" or $link_type == "smart" or $link_type == "coupon" or $link_type == "editor" or $link_type == "home"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가
				$this->ums_save($gid, $data['mst_qty']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 수정 gid : ". $gid);
			}
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > gid : ". $gid .", mst_type2 : ". $mst_type2);
            //ib플래그
            if(config_item('ib_flag')=="Y"){
                $ok = $this->dhnkakao_ib->NewUploadToSendCache("ft");
                if($ok > 0) { $ok = $this->dhnkakao_ib->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }
            }else{
                $ok = $this->dhnkakao->NewUploadToSendCache("ft");
                if($ok > 0) { $ok = $this->dhnkakao->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }
                if($ok > 0 and $gid != "0" and $data['mst_qty']== 0 and $link_type == "home"){
                    $sql = "select mst_qty from cb_wt_msg_sent where mst_id = '".$gid."'";
                    $new_qty = $this->db->query($sql)->row()->mst_qty;
                    $this->ums_save($gid, $new_qty);
                }
            }

			//2021-01-05 2차 알림톡 추가
			if($mst_type2 == "ai"){
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > gid : ". $gid .", alim_code : ". $alim_code .", alim_cont : ". $alim_cont .", btn1_alim : ". $btn1_alim);
                //ib플래그
                if(config_item('ib_flag')=="Y"){
    				$this->dhnkakao_ib->sendAgentSendCache2nd($gid, $alim_code, $alim_cont, $btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim);
                }else{
                    $this->dhnkakao->sendAgentSendCache2nd($gid, $alim_code, $alim_cont, $btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim, $mst_type2);
                }
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sendAgentSendCache2nd OK");
			}
			// $link_type  = $this->input->post('link_type'); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가
			$bizurl = $this->input->post('bizurl'); //에디터 URL
			// if($mst_type2 == "at" || $mst_type2 == "ai" and ($link_type == "smart" or $link_type == "coupon" or $link_type == "editor")){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가

            // else if($bizurl != ""){
			// 	//에디터 사용이 아닌 경우 내용 삭제
			// 	$mem_id = $this->member->item('mem_id');
			// 	$sql = "DELETE FROM cb_alimtalk_ums WHERE short_url = '". $bizurl ."' and mem_id = '". $mem_id ."' ";
			// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 내용 삭제 : ". $sql);
			// 	$this->db->query($sql);
			// }

            header('Content-Type: application/json');
            echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
            return;
            //}
        }

        //- 일 발신 한도 검사
        $sendCount = 9999999;
        // if($mst_type2=="wp1" || $mst_type2=="wp2"){
        //     if($this->input->post('reserveDt')!="00000000000000"){
        //         $resdate = substr($this->input->post('reserveDt'), 0, 8);
        //         if(date("Ymd")!=date('Ymd', strtotime($resdate))){
        //             if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getDateSent($this->member->item('mem_id'),date('Y-m-d', strtotime($resdate)));
        //         }else{
        //             if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
        //         }
        //     }else{
        //         if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
        //     }
        // }
        if($sendCount < 1) {
            header('Content-Type: application/json');
            echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
            return;
        }

        //- 먼저 발송캐시에 등록함
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            if($customer_all_count) {
                //log_message("ERROR", "customer_filter : ".$customer_filter." sendCount : ".$sendCount);
                $ok = $this->dhnkakao_ib->phoneBookToSendCache("ft", $customer_filter, $sendCount);
            } else {
                $ok = $this->dhnkakao_ib->sendAgent("ft");
            }
        }else{
            if($customer_all_count) {
                //log_message("ERROR", "customer_filter : ".$customer_filter." sendCount : ".$sendCount);
                $ok = $this->dhnkakao->phoneBookToSendCache("ft", $customer_filter, $sendCount);
            } else {
                $ok = $this->dhnkakao->sendAgent("ft");
            }
        }
        $msg = $this->Biz_dhn_model->checkSendAbleMessage("ft", $this->member->item('mem_id'), $this->member->item('mem_userid'));
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
        $data['mst_img_link_url'] = !empty($this->input->post('img_link')) ? $this->input->post('img_link') : "";
        $data['mst_button'] = json_encode(array($btn1, $btn2, $btn3, $btn4, $btn5), JSON_UNESCAPED_UNICODE);
        $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
        $data['mst_sms_callback'] =  $senderBox;
        $data['mst_status'] = '1';
        $data['mst_qty'] =  ($customer_all_count) ? (($customer_all_count > $sendCount) ? $sendCount : $customer_all_count) : ((count($tel_number) > $sendCount) ? $sendCount : count($tel_number));
        $data['mst_amount'] = 0;	// ($this->Biz_dhn_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));

        $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
        $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문자)
        $data['mst_type3'] =  $mst_type3;
        //2022-03-21 바우처 추가 윤재박
        $data['mst_sent_voucher'] = (!empty($this->input->post('voucher')))? $this->input->post('voucher') : "N";

        if($data['mst_sent_voucher']=="N" || $data['mst_sent_voucher']=="B"){
            $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
            $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
            $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
            $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
            $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
        }else{
            $data['mst_price_ft'] = $this->Biz_dhn_model->price_v_ft;             // 친구톡단가
            $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_v_ft_img;     // 친구톡이미지단가
            $data['mst_price_at'] = $this->Biz_dhn_model->price_v_at;             // 알림톡단가
            $data['mst_smt_price'] = $this->Biz_dhn_model->price_v_smt;           // 그린샷 웹(C) lms 단가
            $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_v_smt_sms;   // 그린샷 웹(C) sms 단가
            $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_v_smt_mms;   // 그린샷 웹(C) mms 단가
        }
        // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
        // $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
        // $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
        // $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
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
        // $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;;   // 그린샷 웹(C) mms 단가
		$data['mst_price_ft_w_img'] = $this->Biz_dhn_model->price_ft_w_img;   // 친구톡 와이드이미지 단가

        $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
        $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)

		//2021-01-05 2차 알림톡 추가
		$data['mst_2nd_alim_code'] = ($mst_type2 == "ai") ? $alim_code : ""; //2차 알림톡 템플릿코드
		$data['mst_2nd_alim_cont'] = $alim_cont; //2차 알림톡 내용
		$data['mst_2nd_alim_btn'] = json_encode(array($btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim), JSON_UNESCAPED_UNICODE); //2차 알림톡 버튼
		$data['mst_2nd_alim'] = ($mst_type2 == "ai") ? "1" : "0"; //2차 알림톡 구분(0.알림톡 발송안함, 1.대형 카카오 발송, 2.스윗트래커 카카오 발송)



        //echo '<pre> :: ';print_r($data);
        //exit;

        $this->db->insert("cb_wt_msg_sent", $data);
        $gid = $this->db->insert_id();
        $link_type  = $this->input->post('link_type');
        if($link_type == "smartEditor" or $link_type == "couponEditor" or $link_type == "smartcouponEditor" or $link_type == "smart" or $link_type == "coupon" or $link_type == "editor" or $link_type == "home"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가
			$this->ums_save($gid, $data['mst_qty']);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 수정 gid : ". $gid);
		}

        //ib플래그
        if(config_item('ib_flag')=="Y"){
            if($ok > 0) { $ok = $this->dhnkakao_ib->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }

    		//2021-01-05 2차 알림톡 추가
    		if($mst_type2 == "ai"){
    			$this->dhnkakao_ib->sendAgentSendCache2nd($gid, $alim_code, $alim_cont, $btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim);
    		}
        }else{
            if($ok > 0) { $ok = $this->dhnkakao->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }
            if($ok > 0 and $gid != "0" and $data['mst_qty']== 0 and $link_type == "home"){
                $sql = "select mst_qty from cb_wt_msg_sent where mst_id = '".$gid."'";
                $new_qty = $this->db->query($sql)->row()->mst_qty;
                $this->ums_save($gid, $new_qty);
            }
    		if($mst_type2 == "ai"){
    			$this->dhnkakao->sendAgentSendCache2nd($gid, $alim_code, $alim_cont, $btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim, $mst_type2);
    		}
        }
		 //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가
		$bizurl = $this->input->post('bizurl'); //에디터 URL
		// if($mst_type2 == "at" || $mst_type2 == "ai" and ($link_type == "smart" or $link_type == "coupon" or $link_type == "editor")){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가

        // else if($bizurl != ""){
		// 	//에디터 사용이 아닌 경우 내용 삭제
		// 	$mem_id = $this->member->item('mem_id');
		// 	$sql = "DELETE FROM cb_alimtalk_ums WHERE short_url = '". $bizurl ."' and mem_id = '". $mem_id ."' ";
		// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 내용 삭제 : ". $sql);
		// 	$this->db->query($sql);
		// }

        header('Content-Type: application/json');
        echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
        //log_message("ERROR", "끝");
    }

	//UMS 내용 저장
	public function ums_save($gid = 0, $qty = 0){
		$link_type  = $this->input->post('link_type'); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2020-09-16 추가
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
        $reserveDt = $this->input->post('reserveDt');
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", bizurl : ". $bizurl .", gid : ". $gid);

		$html = array();
		$html['short_url'] = $bizurl; //에디터코드
        $html['mem_id'] = $mem_id; //회원번호
        $html['mst_id'] = $gid; //발송번호
        $html['server_id'] = '133'; //서버IP
        $html['link_type'] = "home"; //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력)

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
        }else if($link_type == "home"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력, smartEditor.스마트전단+에디터)
			$html['psd_code'] = $this->input->post('psd_code'); //스마트전단 코드
			$html['psd_url'] = $this->input->post('psd_url'); //스마트전단 링크 URL
			$html['html'] = $this->input->post('html'); //에디터내용
			$html['url'] = $this->input->post('dhnlurl'); //에디터 링크 URL
            $html['pcd_code'] = $this->input->post('pcd_code'); //스마트쿠폰 코드
            $html['pcd_type'] = $this->input->post('pcd_type'); //스마트쿠폰 타입
			$html['pcd_url'] = $this->input->post('pcd_url'); //스마트쿠폰 링크 URL
            $html['home_code'] = $this->input->post('home_code'); //스마트홈 코드
            $html['home_url'] = $this->input->post('home_url'); //스마트홈 링크 URL
            $html['order_url'] = $this->input->post('order_url'); //주문하기 링크 URL
            $html['order_btn'] = (!empty($this->input->post('order_btn')))? $this->input->post('order_btn') : "" ;
            if($gid>0){

               if($qty>0){
                   $html['mst_qty'] = $qty;
               }

            if($reserveDt=="00000000000000"){
               $html['sent_date'] = cdate('Y-m-d H:i:s');
            }else{
                // log_message("ERROR", "reserveDt : ".$reserveDt);
                $html['sent_date'] = date('Y-m-d H:i:s', strtotime($reserveDt));

            }
            // log_message("ERROR", "gid : ".$gid." / mst_qty : ".$qty);
           }
		}else{
			$html['html'] = $this->input->post('html'); //에디터내용
			$html['url'] = $this->input->post('dhnlurl'); //에디터 링크 URL
		}

        $db = $this->load->database("218g", TRUE);


        if($link_type == "home"){
            $sql = "SELECT count(1) AS cnt FROM cb_alimtalk_ums dt WHERE home_code = '". $html['home_code'] ."' and mem_id = '". $mem_id ."' ";
    		// log_message("ERROR", "Query : ".$sql);
    		$cnt = $this->db->query($sql)->row()->cnt;
    		if($cnt == 0){ //신규등록
    			$this->db->insert("cb_alimtalk_ums", $html);
    		}else{
    			$where = array();
    			$where['home_code'] = $html['home_code'];
    			$where['mem_id'] = $mem_id;
    			$this->db->update("cb_alimtalk_ums", $html, $where);
    		}

            $cnt_smart = $db->query($sql)->row()->cnt;
            if($cnt_smart == 0){ //신규등록
    			$db->insert("cb_alimtalk_ums", $html);
    		}else{
    			$where = array();
    			$where['home_code'] = $html['home_code'];
    			$where['mem_id'] = $mem_id;
    			$db->update("cb_alimtalk_ums", $html, $where);
    		}
        }
        // }else{
        //     $this->db->insert("cb_alimtalk_ums", $html);
        //     $db->insert("cb_alimtalk_ums", $html);
        // }

		// $sql = "SELECT count(1) AS cnt, idx FROM cb_alimtalk_ums dt WHERE short_url = '". $bizurl ."' and mem_id = '". $mem_id ."' ";
		// // log_message("ERROR", "Query : ".$sql);
		// $cnt = $this->db->query($sql)->row();
        // if($cnt->cnt == 0){ //신규등록
		// 	$this->db->insert("cb_alimtalk_ums", $html);
		// }else{
		// 	$where = array();
		// 	$where['short_url'] = $bizurl;
		// 	$where['mem_id'] = $mem_id;
        //     $where['idx'] = $cnt->idx;
		// 	$this->db->update("cb_alimtalk_ums", $html, $where);
		// }
		// $this->db->insert("cb_alimtalk_ums", $html);
	}

    public function all_fail()
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
        //log_message("ERROR", "first_mem_id : ".$first_mem_id."    first_mem_userid : ".$first_mem_userid);

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
		//2021-01-05 2차 알림톡 추가
		$alim_code = $this->input->post('alim_code'); //2차 알림톡 템플릿코드
		$alim_cont = $this->input->post('alim_cont'); //2차 알림톡 내용
        $btn1_alim = $this->input->post('btn1_alim');
        $btn2_alim = $this->input->post('btn2_alim');
        $btn3_alim = $this->input->post('btn3_alim');
        $btn4_alim = $this->input->post('btn4_alim');
        $btn5_alim = $this->input->post('btn5_alim');

        if(!$this->is_valid_callback($senderBox) && !empty($lms_msg)) {
            header('Content-Type: application/json');
            echo '{"message": "발신번호 사전등록 오류", "code": "fail"}';
            return;
        }
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $this->load->library('dhnkakao_ib');
        }else{
            $this->load->library('dhnkakao');
        }
        //log_message("ERROR", "upload count : ".$templi_cont);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_count : ". $upload_count);
        if($upload_count) {
            /* 전체 발송인 경우 */
            //    if($_FILES) {
            $msg = $this->Biz_dhn_model->checkSendAbleMessage("ft", $this->member->item('mem_id'), $this->member->item('mem_userid'));
            if($msg!="") {
                header('Content-Type: application/json');
                echo '{"message":"'.$msg.'", "code": "fail"}';
                return;
            }
            //- 일 발신 한도 검사
            $sendCount = 9999999;
            if($mst_type2=="wp1" || $mst_type2=="wp2"){
                if($this->input->post('reserveDt')!="00000000000000"){
                    $resdate = substr($this->input->post('reserveDt'), 0, 8);
                    if(date("Ymd")!=date('Ymd', strtotime($resdate))){
                        if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getDateSent($this->member->item('mem_id'),date('Y-m-d', strtotime($resdate)));
                    }else{
                        if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
                    }
                }else{
                    if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
                }
            }

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
            $data['mst_amount'] = 0;	// ($this->Biz_dhn_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));

            $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
            $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문

            //2022-03-21 바우처 추가 윤재박
            $data['mst_sent_voucher'] = (!empty($this->input->post('voucher')))? $this->input->post('voucher') : "N";

            if($data['mst_sent_voucher']=="N" || $data['mst_sent_voucher']=="B"){
                $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
                $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
                $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
                $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
                $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
                $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            }else{
                $data['mst_price_ft'] = $this->Biz_dhn_model->price_v_ft;             // 친구톡단가
                $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_v_ft_img;     // 친구톡이미지단가
                $data['mst_price_at'] = $this->Biz_dhn_model->price_v_at;             // 알림톡단가
                $data['mst_smt_price'] = $this->Biz_dhn_model->price_v_smt;           // 그린샷 웹(C) lms 단가
                $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_v_smt_sms;   // 그린샷 웹(C) sms 단가
                $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_v_smt_mms;   // 그린샷 웹(C) mms 단가
            }
            // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
            // $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
            // $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
            // $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
            $data['mst_price_grs'] = $this->Biz_dhn_model->price_grs;           // 그린샷 웹(A) lms 단가
            $data['mst_price_grs_sms'] = $this->Biz_dhn_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
            $data['mst_price_grs_mms'] = $this->Biz_dhn_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
            $data['mst_price_nas'] = $this->Biz_dhn_model->price_nas;           // 그린샷 웹(B) lms 단가
            $data['mst_price_nas_sms'] = $this->Biz_dhn_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
            $data['mst_price_nas_mms'] = $this->Biz_dhn_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
//             $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
//             $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
//             $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
            // $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
            // $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            // $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;;   // 그린샷 웹(C) mms 단가
			$data['mst_price_ft_w_img'] = $this->Biz_dhn_model->price_ft_w_img;   // 친구톡 와이드이미지 단가

            $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
            $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)

			//2021-01-05 2차 알림톡 추가
			$data['mst_2nd_alim_code'] = $alim_code; //2차 알림톡 템플릿코드
			$data['mst_2nd_alim_cont'] = $alim_cont; //2차 알림톡 내용
			$data['mst_2nd_alim_btn'] = json_encode(array($btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim), JSON_UNESCAPED_UNICODE); //2차 알림톡 버튼
			$data['mst_2nd_alim'] = ($mst_type2 == "at" || $mst_type2 == "ai") ? "1" : "0"; //2차 알림톡 구분(0.알림톡 발송안함, 1.대형 카카오 발송, 2.스윗트래커 카카오 발송)



            //echo '<pre> :: ';print_r($data);
            //exit;

            $this->db->insert("cb_wt_msg_sent", $data);
            $gid = $this->db->insert_id();
            $link_type  = $this->input->post('link_type');
            if($link_type == "smartEditor" or $link_type == "couponEditor" or $link_type == "smartcouponEditor" or $link_type == "smart" or $link_type == "coupon" or $link_type == "editor"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가
				$this->ums_save($gid, $data['mst_qty']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 수정 gid : ". $gid);
			}
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > gid : ". $gid .", mst_type2 : ". $mst_type2);
            //ib플래그
            if(config_item('ib_flag')=="Y"){
                $ok = $this->dhnkakao_ib->NewUploadToSendCache("ft");
                if($ok > 0) { $ok = $this->dhnkakao_ib->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }
            }else{
                $ok = $this->dhnkakao->NewUploadToSendCache("ft");
                if($ok > 0) { $ok = $this->dhnkakao->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }
                if($ok > 0 and $gid != "0" and $data['mst_qty']== 0 and $link_type == "home"){
                    $sql = "select mst_qty from cb_wt_msg_sent where mst_id = '".$gid."'";
                    $new_qty = $this->db->query($sql)->row()->mst_qty;
                    $this->ums_save($gid, $new_qty);
                }
            }

			//2021-01-05 2차 알림톡 추가
			if($mst_type2 == "at" || $mst_type2 == "ai"){
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > gid : ". $gid .", alim_code : ". $alim_code .", alim_cont : ". $alim_cont .", btn1_alim : ". $btn1_alim);
                //ib플래그
                if(config_item('ib_flag')=="Y"){
    				$this->dhnkakao_ib->sendAgentSendCache2nd($gid, $alim_code, $alim_cont, $btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim);
                }else{
                    $this->dhnkakao->sendAgentSendCache2nd($gid, $alim_code, $alim_cont, $btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim, $mst_type2);
                }
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sendAgentSendCache2nd OK");
			}
			// $link_type  = $this->input->post('link_type'); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가
			$bizurl = $this->input->post('bizurl'); //에디터 URL
			// if($mst_type2 == "at" || $mst_type2 == "ai" and ($link_type == "smart" or $link_type == "coupon" or $link_type == "editor")){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가
            // else if($bizurl != ""){
			// 	//에디터 사용이 아닌 경우 내용 삭제
			// 	$mem_id = $this->member->item('mem_id');
			// 	$sql = "DELETE FROM cb_alimtalk_ums WHERE short_url = '". $bizurl ."' and mem_id = '". $mem_id ."' ";
			// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 내용 삭제 : ". $sql);
			// 	$this->db->query($sql);
			// }

            header('Content-Type: application/json');
            echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
            return;
            //}
        }

        //- 일 발신 한도 검사
        $sendCount = 9999999;
        if($mst_type2=="wp1" || $mst_type2=="wp2"){
            if($this->input->post('reserveDt')!="00000000000000"){
                $resdate = substr($this->input->post('reserveDt'), 0, 8);
                if(date("Ymd")!=date('Ymd', strtotime($resdate))){
                    if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getDateSent($this->member->item('mem_id'),date('Y-m-d', strtotime($resdate)));
                }else{
                    if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
                }
            }else{
                if($this->member->item('mem_day_limit') > 0) $sendCount = $this->member->item('mem_day_limit') - $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
            }
        }
        if($sendCount < 1) {
            header('Content-Type: application/json');
            echo '{"message": "금일 발송 가능 건수를 모두 사용하였습니다.", "code": "fail"}';
            return;
        }

        //- 먼저 발송캐시에 등록함
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            if($customer_all_count) {
                //log_message("ERROR", "customer_filter : ".$customer_filter." sendCount : ".$sendCount);
                $ok = $this->dhnkakao_ib->phoneBookToSendCache("ft", $customer_filter, $sendCount);
            } else {
                $ok = $this->dhnkakao_ib->sendAgent("ft");
            }
        }else{
            if($customer_all_count) {
                //log_message("ERROR", "customer_filter : ".$customer_filter." sendCount : ".$sendCount);
                $ok = $this->dhnkakao->phoneBookToSendCache("ft", $customer_filter, $sendCount);
            } else {
                $ok = $this->dhnkakao->sendAgent_fail("ft");
            }
        }
        $msg = $this->Biz_dhn_model->checkSendAbleMessage("ft", $this->member->item('mem_id'), $this->member->item('mem_userid'));
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
        $data['mst_amount'] = 0;	// ($this->Biz_dhn_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));

        $data['mst_type1'] =  $mst_type1;    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)
        $data['mst_type2'] =  $mst_type2;    // 2019.01.31 이수환 추가 ; mst_type2(2차 문자 = 카카오톡 외의 문자)

        //2022-03-21 바우처 추가 윤재박
        $data['mst_sent_voucher'] = (!empty($this->input->post('voucher')))? $this->input->post('voucher') : "N";

        if($data['mst_sent_voucher']=="N" || $data['mst_sent_voucher']=="B"){
            $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
            $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
            $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
            $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
            $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
            $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
        }else{
            $data['mst_price_ft'] = $this->Biz_dhn_model->price_v_ft;             // 친구톡단가
            $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_v_ft_img;     // 친구톡이미지단가
            $data['mst_price_at'] = $this->Biz_dhn_model->price_v_at;             // 알림톡단가
            $data['mst_smt_price'] = $this->Biz_dhn_model->price_v_smt;           // 그린샷 웹(C) lms 단가
            $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_v_smt_sms;   // 그린샷 웹(C) sms 단가
            $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_v_smt_mms;   // 그린샷 웹(C) mms 단가
        }
        // 2019.02.25 이수환 ; 발송시 현재 단가를 추가
        // $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
        // $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
        // $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
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
        // $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;;   // 그린샷 웹(C) mms 단가
		$data['mst_price_ft_w_img'] = $this->Biz_dhn_model->price_ft_w_img;   // 친구톡 와이드이미지 단가

        $data['send_mem_id'] = $first_mem_id;   // 발송자 mem_id(풀케어)
        $data['send_mem_user_id'] = $first_mem_userid;   // 발송자 mem_userid(풀케어)

		//2021-01-05 2차 알림톡 추가
		$data['mst_2nd_alim_code'] = ($mst_type2 == "at" || $mst_type2 == "ai") ? $alim_code : ""; //2차 알림톡 템플릿코드
		$data['mst_2nd_alim_cont'] = $alim_cont; //2차 알림톡 내용
		$data['mst_2nd_alim_btn'] = json_encode(array($btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim), JSON_UNESCAPED_UNICODE); //2차 알림톡 버튼
		$data['mst_2nd_alim'] = ($mst_type2 == "at" || $mst_type2 == "ai") ? "1" : "0"; //2차 알림톡 구분(0.알림톡 발송안함, 1.대형 카카오 발송, 2.스윗트래커 카카오 발송)



        //echo '<pre> :: ';print_r($data);
        //exit;

        $this->db->insert("cb_wt_msg_sent", $data);
        $gid = $this->db->insert_id();
        $link_type  = $this->input->post('link_type');
        if($link_type == "smartEditor" or $link_type == "couponEditor" or $link_type == "smartcouponEditor" or $link_type == "smart" or $link_type == "coupon" or $link_type == "editor"){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가
			$this->ums_save($gid, $data['mst_qty']);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 수정 gid : ". $gid);
		}
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            if($ok > 0) { $ok = $this->dhnkakao_ib->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }

    		//2021-01-05 2차 알림톡 추가
    		if($mst_type2 == "at" || $mst_type2 == "ai"){
    			$this->dhnkakao_ib->sendAgentSendCache2nd($gid, $alim_code, $alim_cont, $btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim);
    		}
        }else{
            if($ok > 0) { $ok = $this->dhnkakao->sendAgentSendCache("ft", $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $this->input->post('pf_key'), $this->input->post('reserveDt'), $sendCount); }
            if($ok > 0 and $gid != "0" and $data['mst_qty']== 0 and $link_type == "home"){
                $sql = "select mst_qty from cb_wt_msg_sent where mst_id = '".$gid."'";
                $new_qty = $this->db->query($sql)->row()->mst_qty;
                $this->ums_save($gid, $new_qty);
            }
    		if($mst_type2 == "at" || $mst_type2 == "ai"){
    			$this->dhnkakao->sendAgentSendCache2nd($gid, $alim_code, $alim_cont, $btn1_alim, $btn2_alim, $btn3_alim, $btn4_alim, $btn5_alim, $mst_type2);
    		}
        }
		// $link_type  = $this->input->post('link_type'); //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가
		$bizurl = $this->input->post('bizurl'); //에디터 URL
		// if($mst_type2 == "at" || $mst_type2 == "ai" and ($link_type == "smart" or $link_type == "coupon" or $link_type == "editor")){ //전단종류(smart.스마트전단, coupon.스마트쿠폰, editor.에디터 사용, self.직접입력) 2021-01-05 추가

        // else if($bizurl != ""){
		// 	//에디터 사용이 아닌 경우 내용 삭제
		// 	$mem_id = $this->member->item('mem_id');
		// 	$sql = "DELETE FROM cb_alimtalk_ums WHERE short_url = '". $bizurl ."' and mem_id = '". $mem_id ."' ";
		// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 발송 > 에디터 내용 삭제 : ". $sql);
		// 	$this->db->query($sql);
		// }

        header('Content-Type: application/json');
        echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
        //log_message("ERROR", "끝");
    }

}
?>
