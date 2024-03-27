<?php
class Main extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board');
    protected $gAgent = array("prcom"=>"1", "naself"=>"2");
    protected $phn_agent = array("prcom"=>"P", "naself"=>"");
    protected $refund_agent = array("prcom"=>"3", "naself"=>"4");
    protected $sms_agent = array("prcom"=>"", "naself"=>"S");
    protected $lms_agent = array("prcom"=>"", "naself"=>"L");
    protected $mms_agent = array("prcom"=>"", "naself"=>"M");
    
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
        if($this->member->item('mem_level') >= 10) {
            $this_mem_id = $this->member->item('mem_id');
            $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
            if($key) {
                $mem = $this->db->query("select mem_id from cb_member where mem_id=?", array($key))->row();
                if($mem && $mem->mem_id==$key) {
                    $_SESSION["mem_id"] = $key;
                    if(!$this->session->userdata('login_stack')) {
                        $login_stack = array();
                        array_push($login_stack, $this_mem_id);
                        $this->session->set_userdata('login_stack', $login_stack);
                    } else {
                        $login_stack = $this->session->userdata('login_stack');
                        array_push($login_stack, $this_mem_id);
                        $this->session->set_userdata('login_stack', $login_stack);
                    }
                    Header("Location: /");
                    exit;
                }
            }
        }
        $this->load->model("Biz_model");
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['view']['canonical'] = site_url();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz',
            'layout' => 'layout',
            'skin' => 'main',
            'layout_dir' => $this->cbconfig->item('layout_main'),
            'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_main'),
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => $this->cbconfig->item('skin_main'),
            'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_main'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }
    
    public function check_msg_to_phn_agent($div)
    {
        $phn = 0; $sms=0;$p015=0;$grs=0;$nas=0;
        $this->load->model("Biz_free_model");
        $this->DB2 = new mysqli("125.128.249.42", "bizsms", "!@nanum0915", "bizsms");
        
        // 기본 단가 수집
        $base_price = $this->Biz_free_model->getBasePrice();	//$this->db->query($sql)->row_array();
        $adm_price = $this->Biz_free_model->getMemberPrice(3);	//- 관리자단가
        
        // 메시지 전송 결과 처리
        $ok = 0; $err = 0; $before_id = "";
        $sql = "select a.*
                      ,b.mem_userid
                      ,b.mem_level
                      ,b.mem_phn_agent
                      ,b.mem_sms_agent
                      ,b.mem_2nd_send
                 from TBL_REQUEST_RESULT a inner join cb_member b on SPLIT(a.MSGID, '_', 1)=b.mem_id
                where substr(a.msgid, -1) = '".$div."'
                  and( a.reserve_dt < '".cdate('YmdHis')."'
                   or a.reserve_dt = '00000000000000')"  ;
        $list = $this->db->query($sql)->result_array();
        $msgtype = "LMS";
        //sleep(10);
        // 나노 아이티 동보전송을 위한 임시 테이블 비우기
        // $this->db->query("delete from cb_nanoit_msg");
        // if ($this->db->error()['code'] > 0) { //log_message("Error", "delete from cb_nanoit_msg ERROR!!"); }
        
        foreach($list as $r)
        {
            $userid = $r['mem_userid'];
            if($r["SMS_KIND"] == "S" && !is_null($r["SMS_KIND"]) ) {
                $msgtype = "SMS";
            } else {
                $msgtype = "LMS";
            }
            $msg_id = $r['MSGID'];
            
            if(strpos($r['MSGID'], "_")!==false) {
                $mem_lv = $r['mem_level'];
                $mem_phn = $r['mem_phn_agent'];
                $mem_sms = $r['mem_sms_agent'];
                $mem_p_invoice = $r['P_INVOICE'];
                $mem_resend = $r['mem_2nd_send'];
                $msg_sms = $r['MSG_SMS'];
                
                //log_message("ERROR", "2차 발송 업체 : ".$mem_p_invoice." / ".$mem_resend." / ".$msgtype );
                
                if($mem_p_invoice && $r['MESSAGE_TYPE']=="ph" )
                    $mem_resend = $mem_p_invoice;
                    
                    unset($r['mem_level']);
                    unset($r['mem_phn_agent']);
                    unset($r['mem_sms_agent']);
                    unset($r['mem_2nd_send']);
                    unset($r['MSG']);
                    unset($r['MSG_SMS']);
                    $ids = explode("_", $r['MSGID']);
                    if($before_id=="" || $before_id != $ids[0]) {
                        // 사용자별 단가 수집 : id가 변하는 경우만 처리
                        $price = $this->Biz_free_model->getParentPrice($ids[0]);	//$this->db->query($sql, array($ids[0]))->row_array();
                        if($price['mad_mem_id']!=$ids[0]) {
                            $price = $base_price;
                        }
                        $mem_price = $this->Biz_free_model->getMemberPrice($ids[0]);
                        $before_id = $ids[0];
                    }
                    
                    $this->db->insert("cb_msg_".$userid, $r);
                    if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error());continue; }
                    $this->db->query("delete from TBL_REQUEST_RESULT where MSGID=?", array($r['MSGID']));
                    if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error());continue; }
                    // 성공했으면 금액 차감, MASTER테이블에 카운터 저장
                    $sent_key = $r['REMARK4'];	// cb_wt_msg_sent 키
                    if($r['RESULT']=="Y") {
                        $amt = $mem_price['mad_price_ft'];
                        $amt_admin = $adm_price['mad_price_ft'];
                        $memo = "";
                        $kind = "";
                        if($r['MESSAGE_TYPE']=="ft") {
                            $sql = "update cb_wt_msg_sent set ".(($r['IMAGE_URL']==null || $r['IMAGE_URL']=="") ? "mst_ft=ifnull(mst_ft,0)+1" : "mst_ft_img=ifnull(mst_ft_img,0)+1")." where mst_id=?";
                            $this->db->query($sql, $sent_key);
                            $amt = (($r['IMAGE_URL']==null || $r['IMAGE_URL']=="") ? $mem_price['mad_price_ft'] : $mem_price['mad_price_ft_img']);
                            $amt_admin = (($r['IMAGE_URL']==null || $r['IMAGE_URL']=="") ? $adm_price['mad_price_ft'] : $adm_price['mad_price_ft_img']);
                            $memo = (($r['IMAGE_URL']==null || $r['IMAGE_URL']=="") ? "친구톡(텍스트)" : "친구톡(이미지)");
                            $kind = (($r['IMAGE_URL']==null || $r['IMAGE_URL']=="") ? "F" : "I");
                            
                            $friend_data = array(
                                'mem_id' => $ids[0],
                                'phn' => "0".substr($r["PHN"], 2),
                                'last_send_date' =>cdate('Y-m-d H:i:s')
                            );
                            
                            $this->db->replace('friend_list', $friend_data);
                            
                        } else if($r['MESSAGE_TYPE']=="at") {
                            $sql = "update cb_wt_msg_sent set mst_at=ifnull(mst_at,0)+1 where mst_id=?";
                            $this->db->query($sql, $sent_key);
                            $amt = $mem_price['mad_price_at'];
                            $amt_admin = $adm_price['mad_price_at'];
                            $memo = "알림톡발송";
                            $kind = "A";
                        }
                        $data = array();
                        $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                        $data['amt_kind'] = $kind;
                        $data['amt_amount'] = $amt;
                        $data['amt_memo'] = $memo;
                        $data['amt_reason'] = $r['MSGID'];
                        /* 상위자와의 단가차이를 적용 */
                        $data['amt_payback'] = ($mem_lv>=50) ? 0 : (($kind=="A") ? $price['payback_at'] : (($kind=="F") ? $price['payback_ft'] : $price['payback_ft_img']));
                        $data['amt_admin'] = $amt_admin;
                        
                        $this->db->insert("cb_amt_".$userid, $data);
                        if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                        unset($data);
                    } else if($r['RESULT']=="N")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                        
                        /* 친구 톡 실패시 친구 목록 삭제 */
                        if($r['MESSAGE_TYPE']=="ft") {
                            $friend_sql = "delete from cb_friend_list where mem_id = '".$ids[0]."' and phn = '"."0".substr($r["PHN"], 2)."'";
                            $this->db->query($friend_sql);
                        }
                        
                        if($mem_resend && $r['MESSAGE']!="InvalidPhoneNumber" && $r["SMS_SENDER"] && !empty($msg_sms) ) {		// substr($r['CODE'], 0, 1)!="E"
                            // 오류코드가 E코드가 아닌 경우 폰문자 재발송 K101-NotAvailableSendMessage, 999 : 시스템오류
                            //log_message("ERROR", $mem_resend.", ".$msgtype);
                            if($mem_resend != "NONE" && !is_null($mem_resend)) {
                                /* 폰문자로 들어가면 무조건 성공으로 간주 */
                                
                              /*  if($ids[0] == "255" && $mem_resend == "GREEN_SHOT" && $msgtype == "LMS")
                                {
                                    $mem_resend = "BKG";
                                }
                                */
                                if($mem_resend == "015") {
                                    /*
                                     $web015 = array();
                                     $web015['rcv_phone'] = "0".substr($r["PHN"], 2);
                                     $web015['subject'] = $r["SMS_LMS_TIT"];
                                     $web015['text'] = $r["MSG_SMS"];
                                     $web015['msg_st'] = 0;
                                     $web015['ins_dttm'] = cdate('Y-m-d H:i:s');
                                     $web015['req_dttm'] = cdate('Y-m-d H:i:s');
                                     $web015['cb_msg_id'] = $r["MSGID"];
                                     $web015['remark4'] = $sent_key;
                                     
                                     $this->db->insert("pms_msg", $web015);
                                     
                                     if ($this->db->error()['code'] < 1) { $p015++; }
                                     
                                     $sql = "update cb_wt_msg_sent set mst_015=ifnull(mst_015,0)+1 where mst_id=?";
                                     $this->db->query($sql, $sent_key);
                                     if ($this->db->error()['code'] > 0) { continue; }
                                     
                                     $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='15',CODE='015', MESSAGE = '결과 수신대기' where MSGID=?";
                                     $this->db->query($sql, $r["MSGID"]);
                                     if ($this->db->error()['code'] > 0) { continue; }
                                     
                                     $amt = $mem_price['mad_price_015'];
                                     $amt_admin = $adm_price['mad_price_015'];
                                     if(is_null($amt))
                                     $amt = $amt_admin;
                                     $memo = "";
                                     $kind = "";
                                     
                                     
                                     $_015amt = array();
                                     $_015amt['amt_datetime'] = cdate('Y-m-d H:i:s');
                                     $_015amt['amt_kind'] = 'P';
                                     $_015amt['amt_amount'] = $amt;
                                     $_015amt['amt_memo'] = '015저가문자';
                                     $_015amt['amt_reason'] = $r['MSGID'];
                                     
                                     $_015amt['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_015'];
                                     $_015amt['amt_admin'] = $amt_admin;
                                     
                                     $this->db->insert("cb_amt_".$userid, $_015amt);
                                     if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                                     
                                     unset($_015amt);
                                     unset($web015);
                                     */
                                     $nanoit_015 = array();
                                     $nanoit_015['msg_type'] = "015";
                                     $nanoit_015['remark4'] = $sent_key;
                                     $nanoit_015['phn'] = "0".substr($r["PHN"], 2);
                                     
                                     $this->db->insert("nanoit_msg", $nanoit_015);
                                     if ($this->db->error()['code'] > 0) { continue; }
                                     
                                     unset($nanoit_015);
                                } else if($mem_resend == "PHONE") {
                                    /*
                                     $phone = array();
                                     $phone['rcv_phone'] = "0".substr($r["PHN"], 2);
                                     $phone['subject'] = $r["SMS_LMS_TIT"];
                                     $phone['text'] = $r["MSG_SMS"];
                                     $phone['msg_st'] = 0;
                                     $phone['ins_dttm'] = cdate('Y-m-d H:i:s');
                                     $phone['req_dttm'] = cdate('Y-m-d H:i:s');
                                     $phone['cb_msg_id'] = $r["MSGID"];
                                     $phone['remark4'] = $sent_key;
                                     
                                     $this->db->insert("pms_msg", $phone);
                                     
                                     if ($this->db->error()['code'] < 1) { $phn++; }
                                     
                                     $sql = "update cb_wt_msg_sent set mst_phn=ifnull(mst_phn,0)+1 where mst_id=?";
                                     $this->db->query($sql, $sent_key);
                                     if ($this->db->error()['code'] > 0) { continue; }
                                     
                                     $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='ph',CODE='PHN', MESSAGE = '결과 수신대기' where MSGID=?";
                                     $this->db->query($sql, $r["MSGID"]);
                                     if ($this->db->error()['code'] > 0) { continue; }
                                     
                                     $amt = $mem_price['mad_price_phn'];
                                     $amt_admin = $adm_price['mad_price_phn'];
                                     if(is_null($amt))
                                     $amt = $amt_admin;
                                     $memo = "";
                                     $kind = "";
                                     
                                     
                                     $phnamt = array();
                                     $phnamt['amt_datetime'] = cdate('Y-m-d H:i:s');
                                     $phnamt['amt_kind'] = 'P';
                                     $phnamt['amt_amount'] = $amt;
                                     $phnamt['amt_memo'] = '폰문자';
                                     $phnamt['amt_reason'] = $r['MSGID'];
                                     
                                     $phnamt['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_phn'];
                                     $phnamt['amt_admin'] = $amt_admin;
                                     
                                     $this->db->insert("cb_amt_".$userid, $phnamt);
                                     if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                                     
                                     unset($phnamt);
                                     unset($phone);
                                     */
                                     $nanoit_phone = array();
                                     $nanoit_phone['msg_type'] = "PHONE";
                                     $nanoit_phone['remark4'] = $sent_key;
                                     $nanoit_phone['phn'] = "0".substr($r["PHN"], 2);
                                     
                                     $this->db->insert("nanoit_msg", $nanoit_phone);
                                     if ($this->db->error()['code'] > 0) { continue; }
                                     
                                     unset($nanoit_phone);
                                     
                                     
                                } else if($mem_resend == "BKG") {
                                    //log_message('error', 'BKG 처리 시작');
                                    $bkg = array();
                                    $bkg['SUBJECT'] = str_replace(array("\r\n", "\r", "\n"),'',$r["SMS_LMS_TIT"]);
                                    $bkg['PHONE'] = "0".substr($r["PHN"], 2);
                                    $bkg['CALLBACK'] = $r["SMS_SENDER"];
                                    $bkg['STATUS'] = '0';
                                    $bkg['MSG'] = $msg_sms;
                                    $bkg['BILL_ID'] = $r["SMS_SENDER"];
                                    //								$bkg['FILE_CNT'] = ;
                                    //								$bkg['FILE_PATH1'] = ;
                                    $bkg['TYPE'] = '0';
                                    $bkg['ETC1'] =  $r["MSGID"];
                                    $bkg['ETC2'] = $sent_key;
                                    $bkg['ETC4'] = $ids[0];
                                    
                                    if($r["RESERVE_DT"]!="00000000000000" ) {
                                        $bkg['REQDATE'] = $r["RESERVE_DT"];
                                    } else {
                                        $bkg['REQDATE'] = cdate('Y-m-d H:i:s');
                                    }
                                    
                                    $this->db->insert("mms_msg", $bkg);
                                    
                                    if($this->db->error()['code'] < 1) { $bkg++; }
                                    else {
                                        //log_message('error', 'DB ERROR = '.$this->db->_error_message());
                                    }
                                    
                                    $sql = "update cb_wt_msg_sent set mst_grs=ifnull(mst_grs,0)+1 where mst_id=?";
                                    $this->db->query($sql, $sent_key);
                                    if ($this->db->error()['code'] > 0) { continue; }
                                    
                                    $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='gs',CODE='GRS', MESSAGE = '결과 수신대기' where MSGID=?";
                                    $this->db->query($sql, $r["MSGID"]);
                                    if ($this->db->error()['code'] > 0) { continue; }
                                    
                                    
                                    $amt = $mem_price['mad_price_grs'];
                                    $amt_admin = $adm_price['mad_price_grs'];
                                    if(is_null($amt))
                                        $amt = $amt_admin;
                                
                                    $memo = "";
                                    $kind = "";
                                    
                                    
                                    $bkgamt = array();
                                    $bkgamt['amt_datetime'] = cdate('Y-m-d H:i:s');
                                    $bkgamt['amt_kind'] = 'P';
                                    $bkgamt['amt_amount'] = $amt;
                                    $bkgamt['amt_memo'] = '웹(A)';
                                    $bkgamt['amt_reason'] = $r['MSGID'];
                                    
                                    $bkgamt['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_phn'];
                                    $bkgamt['amt_admin'] = $amt_admin;
                                    
                                    $this->db->insert("cb_amt_".$userid, $bkgamt);
                                    if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                                    
                                    unset($bkg);
                                    unset($$bkgamt);
                                        
                                } else if($mem_resend == "GREEN_SHOT" && $msgtype=="SMS") {
                                    /*
                                     * 2차 발송이 웹(A) 이고 메세지가 SMS 이면
                                     * BKG 문자로 전송함.
                                     *
                                     */
                                    
                                    $funsms = array();
                                    $funsms['TR_PHONE'] = "0".substr($r["PHN"], 2);
                                    $funsms['TR_CALLBACK'] = $r["SMS_SENDER"];
                                    $funsms['TR_ORG_CALLBACK'] = $r["SMS_SENDER"];
                                    $funsms['TR_SENDSTAT'] = '0';
                                    $funsms['TR_MSG'] = $msg_sms;
                                    //$funsms['BILL_ID'] = 'DevDepartment';
                                    //								$bkg['FILE_CNT'] = ;
                                    //								$bkg['FILE_PATH1'] = ;
                                    $funsms['TR_MSGTYPE'] = '0';
                                    $funsms['TR_ETC1'] =  $r["MSGID"];
                                    $funsms['TR_ETC2'] = $sent_key;
                                    $funsms['TR_ETC4'] = $ids[0];
                                    
                                    if($r["RESERVE_DT"]!="00000000000000" ) {
                                        $funsms['TR_SENDDATE'] = $r["RESERVE_DT"];
                                    } else {
                                        $funsms['TR_SENDDATE'] = cdate('Y-m-d H:i:s');
                                    }
                                    
                                    $this->db->insert("sms_msg", $funsms);
                                    
                                    if($this->db->error()['code'] < 1) { $bkg++; }
                                    else {
                                        //log_message('error', 'DB ERROR = '.$this->db->_error_message());
                                    }
                                    
                                    $sql = "update cb_wt_msg_sent set mst_grs_sms=ifnull(mst_grs_sms,0)+1 where mst_id=?";
                                    $this->db->query($sql, $sent_key);
                                    if ($this->db->error()['code'] > 0) { continue; }
                                    
                                    $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='gs',CODE='GRS', MESSAGE = '결과 수신대기', SMS_KIND = 'S' where MSGID=?";
                                    $this->db->query($sql, $r["MSGID"]);
                                    if ($this->db->error()['code'] > 0) { continue; }
                                    
                                    
                                    $amt = $mem_price['mad_price_grs_sms'];
                                    $amt_admin = $adm_price['mad_price_grs_sms'];
                                    
                                    if(is_null($amt)) {
                                        $amt = $amt_admin;
                                    }
                                    
                                    $memo = "";
                                    $kind = "";
                                    
                                    
                                    $bkgamt = array();
                                    $bkgamt['amt_datetime'] = cdate('Y-m-d H:i:s');
                                    $bkgamt['amt_kind'] = 'P';
                                    $bkgamt['amt_amount'] = $amt;
                                    $bkgamt['amt_memo'] = '웹(A)SMS';
                                    $bkgamt['amt_reason'] = $r['MSGID'];
                                    
                                    $bkgamt['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_phn'];
                                    $bkgamt['amt_admin'] = $amt_admin;
                                    
                                    $this->db->insert("cb_amt_".$userid, $bkgamt);
                                    if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                                    
                                    unset($bkg);
                                    unset($$bkgamt);
                                    /*
                                    $nanoit_bkg = array();
                                    $nanoit_bkg['msg_type'] = "BKG";
                                    $nanoit_bkg['remark4'] = $sent_key;
                                    $nanoit_bkg['phn'] = "0".substr($r["PHN"], 2);
                                    
                                    $this->db->insert("nanoit_msg", $nanoit_bkg);
                                    if ($this->db->error()['code'] > 0) { continue; }
                                    
                                    unset($nanoit_bkg);
                                    */
                                    
                                } else if($mem_resend == "GREEN_SHOT" && $msgtype == "LMS") {
                                    /*
                                     * 2차 발송이 웹(A) 이고 메세지가 SMS 아니면
                                     * Green Shot 으로 전송 함.
                                     *
                                     * green shot 은 LMS 발송만 됨.                                 *
                                     */
                                    //log_message("ERROR", "GRS : ".$msgtype.", Insert 시작");
                                    /*
                                     $greenshot = array();
                                     $greenshot['msg_gb'] = "LMS";
                                     $greenshot['msg_st'] = "0";
                                     $greenshot['msg_snd_phn'] = $r["SMS_SENDER"];
                                     $greenshot['msg_rcv_phn'] = "0".substr($r["PHN"], 2);
                                     $greenshot['subject'] = str_replace(array("\r\n", "\r", "\n"),'',$r["SMS_LMS_TIT"]);
                                     $greenshot['text'] = str_replace("\xC2\xA0", ' ', $r["MSG_SMS"]);
                                     $greenshot['cb_msg_id']  = $r["MSGID"];
                                     $greenshot['remark4'] = $sent_key;
                                     
                                     // 예약 시간이 있으면 예약 문자로 발송 함.
                                     if($r["RESERVE_DT"]!="00000000000000") {
                                     $greenshot['msg_req_dttm'] = $r["RESERVE_DT"];
                                     } else {
                                     $greenshot['msg_req_dttm'] = cdate('Y-m-d H:i:s');
                                     }
                                     
                                     $this->db->insert("grs_msg", $greenshot);
                                     if($this->db->error()['code'] < 1) { $grs++; }
                                     
                                     $sql = "update cb_wt_msg_sent set mst_grs=ifnull(mst_grs,0)+1 where mst_id=?";
                                     $this->db->query($sql, $sent_key);
                                     if ($this->db->error()['code'] > 0) { continue; }
                                     
                                     $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='gs',CODE='GRS', MESSAGE = '결과 수신대기', SMS_KIND = 'L' where MSGID=?";
                                     $this->db->query($sql, $r["MSGID"]);
                                     if ($this->db->error()['code'] > 0) { continue; }
                                     
                                     $amt = $mem_price['mad_price_grs'];
                                     $amt_admin = $adm_price['mad_price_grs'];
                                     
                                     if(is_null($amt)) {
                                     $amt = $amt_admin;
                                     }
                                     
                                     $memo = "";
                                     $kind = "";
                                     
                                     $gsamt = array();
                                     $gsamt['amt_datetime'] = cdate('Y-m-d H:i:s');
                                     $gsamt['amt_kind'] = 'P';
                                     $gsamt['amt_amount'] = $amt;
                                     $gsamt['amt_memo'] = 'Green Shot';
                                     $gsamt['amt_reason'] = $r['MSGID'];
                                     
                                     $gsamt['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_grs'];
                                     $gsamt['amt_admin'] = $amt_admin;
                                     
                                     $this->db->insert("cb_amt_".$userid, $gsamt);
                                     if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                                     
                                     unset($greenshot);
                                     unset($gsamt);
                                     */
                                    
                                    /*
                                     * 그린샷 복원시 해제 해 줘야 함.
                                     * 2018.09.17
                                     * 
                                    */
                                    
                                    $nanoit_grs = array();
                                    $nanoit_grs['msg_type'] = "GRS";
                                    $nanoit_grs['remark4'] = $sent_key;
                                    $nanoit_grs['phn'] = "0".substr($r["PHN"], 2);
                                    
                                    $this->db->insert("nanoit_msg", $nanoit_grs);
                                    if ($this->db->error()['code'] > 0) { continue; }
                                    
                                    unset($nanoit_grs);
                                     
                                    // 그린샷 오류로 당분간 나셀프로 발송함.
                                    // 단가는 그린샷 단가를 적용 해야 함.
                                    /*
                                    if ($this->DB2->connect_errno) die("Connect failed: ".$db->connect_error);
                                    
                                    $block_res = $this->DB2->query("select * from sdk_block_hp where hp = '"."0".substr($r["PHN"], 2)."'");
                                    $rowCount = $block_res->num_rows;
                                    // //log_message("ERROR", "NASELF : " . $rowCount);
                                    
                                    if($rowCount > 0) {
                                        $sql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='gs',CODE='GRS', MESSAGE = '수신거부' where MSGID=?";
                                        $this->db->query($sql, $r["MSGID"]);
                                        
                                        $sql = "update cb_wt_msg_sent set mst_err_grs=ifnull(mst_err_grs,0)+1   where mst_id=?";
                                        $this->db->query($sql, $sent_key);
                                    } else {
                                        
                                        $newItem = array();
                                        $newItem['subject'] = $r["SMS_LMS_TIT"];
                                        $newItem['phone'] = "0".substr($r["PHN"], 2);
                                        $newItem['callback'] = $r["SMS_SENDER"];
                                        $newItem['reqdate'] = cdate('Y-m-d H:i:s');
                                        $newItem['msg'] = $msg_sms;
                                        if($msgtype=="SMS") {
                                            $newItem['TYPE'] = "1";
                                        }else {
                                            $newItem['TYPE'] = "2";
                                        }
                                        $newItem['cb_msg_id'] = $r["MSGID"];
                                        $newItem['remark4'] = $sent_key;
                                        $newItem['ETC3'] = 'GRS';
                                        
                                        $this->db->insert("neo_msg", $newItem);
                                        
                                        if ($this->db->error()['code'] < 1) { $nas++; }
                                        
                                        if($msgtype=="SMS") {
                                            $sql = "update cb_wt_msg_sent set mst_grs_sms=ifnull(mst_grs_sms,0)+1 where mst_id=?";
                                        }else {
                                            $sql = "update cb_wt_msg_sent set mst_grs=ifnull(mst_grs,0)+1 where mst_id=?";
                                        }
                                        $this->db->query($sql, $sent_key);
                                        if ($this->db->error()['code'] > 0) { continue; }
                                        
                                        if($msgtype=="SMS") {
                                            $amt = $mem_price['mad_price_grs_sms'];
                                            $amt_admin = $adm_price['mad_price_grs_sms'];
                                        }else {
                                            $amt = $mem_price['mad_price_grs'];
                                            $amt_admin = $adm_price['mad_price_grs'];
                                        }
                                        
                                        if(is_null($amt)) {
                                            $amt = $amt_admin;
                                        }
                                        
                                        //$amt = $mem_price['mad_price_nas'];
                                        //$amt_admin = $adm_price['mad_price_nas'];
                                        $memo = "";
                                        $kind = "";
                                        
                                        
                                        $nasdata = array();
                                        $nasdata['amt_datetime'] = cdate('Y-m-d H:i:s');
                                        $nasdata['amt_kind'] = 'P';
                                        $nasdata['amt_amount'] = $amt;
                                        $nasdata['amt_memo'] = 'Green Shot';
                                        $nasdata['amt_reason'] = $r['MSGID'];
                                        
                                        $nasdata['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_grs'];
                                        $nasdata['amt_admin'] = $amt_admin;
                                        
                                        $this->db->insert("cb_amt_".$userid, $nasdata);
                                        if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                                        
                                        unset($nasdata);
                                        
                                        $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='gs',CODE='GRS', MESSAGE = '결과 수신대기' where MSGID=?";
                                        $this->db->query($sql, $r["MSGID"]);
                                        if ($this->db->error()['code'] > 0) { continue; }
                                        
                                        unset($newItem);
                                        
                                    }
                                    */
                                } else if($mem_resend == "DOOIT") {
                                     $dooit = array();
                                     $dooit['msg_type'] = $msgtype;
                                     $dooit['remark4'] = $sent_key;
                                     $dooit['phn'] = "0".substr($r["PHN"], 2);
                                     $dooit['msg_t_id'] = $msg_id;
                                     
                                     $this->db->insert("dooit_msg", $dooit);
                                     if ($this->db->error()['code'] > 0) { continue; }
                                     
                                     $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='ph',CODE='PHN', MESSAGE = '결과 수신대기' where MSGID=?";
                                     $this->db->query($sql, $r["MSGID"]);
                                     
                                     unset($dooit);
                                } else if($mem_resend == "NASELF_A") {
                                    // 20180523 hsjin
                                    // 2차 발신(폰문자, SMS, LMS, MMS 등등) 모두 나셀프 에이전트가 처리
                                    if ($this->DB2->connect_errno) die("Connect failed: ".$db->connect_error);
                                    
                                    $block_res = $this->DB2->query("select * from sdk_block_hp where hp = '"."0".substr($r["PHN"], 2)."'");
                                    $rowCount = $block_res->num_rows;
                                    // //log_message("ERROR", "NASELF : " . $rowCount);
                                    
                                    if($rowCount > 0) {
                                        $sql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='ns',CODE='NAS', MESSAGE = '수신거부' where MSGID=?";
                                        $this->db->query($sql, $r["MSGID"]);
                                        
                                        $sql = "update cb_wt_msg_sent set mst_err_nas=ifnull(mst_err_nas,0)+1   where mst_id=?";
                                        $this->db->query($sql, $sent_key);
                                    } else {
                                        
                                        $newItem = array();
                                        $newItem['subject'] = $r["SMS_LMS_TIT"];
                                        $newItem['phone'] = "0".substr($r["PHN"], 2);
                                        $newItem['callback'] = $r["SMS_SENDER"];
                                        $newItem['reqdate'] = cdate('Y-m-d H:i:s');
                                        $newItem['msg'] = $msg_sms;
                                        if($msgtype=="SMS") {
                                            $newItem['TYPE'] = "1";
                                        }else {
                                            $newItem['TYPE'] = "2";
                                        }
                                        $newItem['cb_msg_id'] = $r["MSGID"];
                                        $newItem['remark4'] = $sent_key;
                                        
                                        $this->db->insert("neo_msg", $newItem);
                                        
                                        if ($this->db->error()['code'] < 1) { $nas++; }
                                        
                                        if($msgtype=="SMS") {
                                            $sql = "update cb_wt_msg_sent set mst_nas_sms=ifnull(mst_nas_sms,0)+1 where mst_id=?";
                                        }else {
                                            $sql = "update cb_wt_msg_sent set mst_nas=ifnull(mst_nas,0)+1 where mst_id=?";
                                        }
                                        $this->db->query($sql, $sent_key);
                                        if ($this->db->error()['code'] > 0) { continue; }
                                        
                                        if($msgtype=="SMS") {
                                            $amt = $mem_price['mad_price_nas_sms'];
                                            $amt_admin = $adm_price['mad_price_nas_sms'];
                                        }else {
                                            $amt = $mem_price['mad_price_nas'];
                                            $amt_admin = $adm_price['mad_price_nas'];
                                        }
                                        
                                        if(is_null($amt)) {
                                            $amt = $amt_admin;
                                        }
                                        
                                        //$amt = $mem_price['mad_price_nas'];
                                        //$amt_admin = $adm_price['mad_price_nas'];
                                        $memo = "";
                                        $kind = "";
                                        
                                        
                                        $nasdata = array();
                                        $nasdata['amt_datetime'] = cdate('Y-m-d H:i:s');
                                        $nasdata['amt_kind'] = 'P';
                                        $nasdata['amt_amount'] = $amt;
                                        $nasdata['amt_memo'] = 'Naself';
                                        $nasdata['amt_reason'] = $r['MSGID'];
                                        
                                        $nasdata['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_nas'];
                                        $nasdata['amt_admin'] = $amt_admin;
                                        
                                        $this->db->insert("cb_amt_".$userid, $nasdata);
                                        if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                                        
                                        unset($nasdata);
                                        
                                        $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='ns',CODE='NAS', MESSAGE = '결과 수신대기' where MSGID=?";
                                        $this->db->query($sql, $r["MSGID"]);
                                        if ($this->db->error()['code'] > 0) { continue; }
                                        
                                        unset($newItem);
                                        
                                    }
                                } else if($mem_resend == "NASELF") {
                                    // 20180523 hsjin
                                    // 2차 발신(폰문자, SMS, LMS, MMS 등등) 모두 나셀프 에이전트가 처리
                                    if ($this->DB2->connect_errno) die("Connect failed: ".$db->connect_error);
                                    
                                    $block_res = $this->DB2->query("select * from sdk_block_hp where hp = '"."0".substr($r["PHN"], 2)."'");
                                    $rowCount = $block_res->num_rows;
                                    // //log_message("ERROR", "NASELF : " . $rowCount);
                                    
                                    if($rowCount > 0) {
                                        $sql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='ns',CODE='NAS', MESSAGE = '수신거부' where MSGID=?";
                                        $this->db->query($sql, $r["MSGID"]);
                                        
                                        $sql = "update cb_wt_msg_sent set mst_err_nas=ifnull(mst_err_nas,0)+1   where mst_id=?";
                                        $this->db->query($sql, $sent_key);
                                    } else {
                                        if($msgtype=="SMS") {
                                            
                                            $nassms = array();
                                            $nassms['TR_PHONE'] = "0".substr($r["PHN"], 2);
                                            $nassms['TR_CALLBACK'] = $r["SMS_SENDER"];
                                            $nassms['TR_ORG_CALLBACK'] = $r["SMS_SENDER"];
                                            $nassms['TR_SENDSTAT'] = '0';
                                            $nassms['TR_MSG'] = $msg_sms;
                                            //$funsms['BILL_ID'] = 'DevDepartment';
                                            //								$bkg['FILE_CNT'] = ;
                                            //								$bkg['FILE_PATH1'] = ;
                                            $nassms['TR_MSGTYPE'] = '0';
                                            $nassms['TR_ETC1'] =  $r["MSGID"];
                                            $nassms['TR_ETC2'] = $sent_key;
                                            $nassms['TR_ETC4'] = $ids[0];
                                            
                                            if($r["RESERVE_DT"]!="00000000000000" ) {
                                                $nassms['TR_SENDDATE'] = $r["RESERVE_DT"];
                                            } else {
                                                $nassms['TR_SENDDATE'] = cdate('Y-m-d H:i:s');
                                            }
                                            
                                            $this->db->insert("cb_nas_sms_msg", $nassms);
                                            
                                            if($this->db->error()['code'] < 1) { $nas_s++; }
                                            else {
                                                //log_message('error', 'DB ERROR = '.$this->db->_error_message());
                                            }
                                            
                                            $sql = "update cb_wt_msg_sent set mst_nas_sms=ifnull(mst_nas_sms,0)+1 where mst_id=?";
                                            $this->db->query($sql, $sent_key);
                                            if ($this->db->error()['code'] > 0) { continue; }
                                            
                                            $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='ns',CODE='NAS', MESSAGE = '결과 수신대기', SMS_KIND = 'S' where MSGID=?";
                                            $this->db->query($sql, $r["MSGID"]);
                                            if ($this->db->error()['code'] > 0) { continue; }
                                            unset($nassms);
                                        }else {
                                            $nasmms = array();
                                            $nasmms['SUBJECT'] = str_replace(array("\r\n", "\r", "\n"),'',$r["SMS_LMS_TIT"]);
                                            $nasmms['PHONE'] = "0".substr($r["PHN"], 2);
                                            $nasmms['CALLBACK'] = $r["SMS_SENDER"];
                                            $nasmms['STATUS'] = '0';
                                            $nasmms['MSG'] = $msg_sms;
                                            $nasmms['BILL_ID'] = $r["SMS_SENDER"];
                                            //								$bkg['FILE_CNT'] = ;
                                            //								$bkg['FILE_PATH1'] = ;
                                            $nasmms['TYPE'] = '0';
                                            $nasmms['ETC1'] =  $r["MSGID"];
                                            $nasmms['ETC2'] = $sent_key;
                                            $nasmms['ETC4'] = $ids[0];
                                            
                                            if($r["RESERVE_DT"]!="00000000000000" ) {
                                                $nasmms['REQDATE'] = $r["RESERVE_DT"];
                                            } else {
                                                $nasmms['REQDATE'] = cdate('Y-m-d H:i:s');
                                            }
                                            
                                            $this->db->insert("cb_nas_mms_msg", $nasmms);
                                            
                                            if($this->db->error()['code'] < 1) { $nas_s++; }
                                            else {
                                                //log_message('error', 'DB ERROR = '.$this->db->_error_message());
                                            }
                                            
                                            $sql = "update cb_wt_msg_sent set mst_nas=ifnull(mst_nas,0)+1 where mst_id=?";
                                            $this->db->query($sql, $sent_key);
                                            if ($this->db->error()['code'] > 0) { continue; }
                                            
                                            $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='ns',CODE='NAS', MESSAGE = '결과 수신대기' where MSGID=?";
                                            $this->db->query($sql, $r["MSGID"]);
                                            if ($this->db->error()['code'] > 0) { continue; }
                                            unset($nasmms);
                                        }
                                        
                                 
                                        $this->db->query($sql, $sent_key);
                                        if ($this->db->error()['code'] > 0) { continue; }
                                        
                                        if($msgtype=="SMS") {
                                            $amt = $mem_price['mad_price_nas_sms'];
                                            $amt_admin = $adm_price['mad_price_nas_sms'];
                                        }else {
                                            $amt = $mem_price['mad_price_nas'];
                                            $amt_admin = $adm_price['mad_price_nas'];
                                        }
                                        
                                        if(is_null($amt)) {
                                            $amt = $amt_admin;
                                        }
                                        
                                        //$amt = $mem_price['mad_price_nas'];
                                        //$amt_admin = $adm_price['mad_price_nas'];
                                        $memo = "";
                                        $kind = "";
                                        
                                        
                                        $nasdata = array();
                                        $nasdata['amt_datetime'] = cdate('Y-m-d H:i:s');
                                        $nasdata['amt_kind'] = 'P';
                                        $nasdata['amt_amount'] = $amt;
                                        $nasdata['amt_memo'] = 'Naself';
                                        $nasdata['amt_reason'] = $r['MSGID'];
                                        
                                        $nasdata['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_nas'];
                                        $nasdata['amt_admin'] = $amt_admin;
                                        
                                        $this->db->insert("cb_amt_".$userid, $nasdata);
                                        if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                                        
                                        unset($nasdata);
                                        
                                        $sql = "update cb_msg_".$userid." set MESSAGE_TYPE='ns',CODE='NAS', MESSAGE = '결과 수신대기' where MSGID=?";
                                        $this->db->query($sql, $r["MSGID"]);
                                        if ($this->db->error()['code'] > 0) { continue; }
                                        
                                    }
                                } else if($mem_phn=="prcom" ) {
                                    
                                    $data = array();
                                    $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                                    $data['amt_kind'] = ($this->phn_agent[$mem_phn]) ? $this->phn_agent[$mem_phn] : "P";
                                    $data['amt_amount'] = $mem_price['mad_price_phn'];
                                    $data['amt_memo'] = "LMS발송(PRCOM)";
                                    $data['amt_reason'] = $r['MSGID'];
                                    /* 상위자와의 단가차이를 적용 */
                                    $data['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_phn'];
                                    $data['amt_admin'] = $adm_price['phn'.$this->gAgent[$mem_phn]];
                                    
                                    $this->db->insert("cb_amt_".$userid, $data);
                                    if ($this->db->error()['code'] > 0) { continue; }
                                    unset($data);
                                    /* 로그를 폰문자 발송으로 변경 : 성공처리 */
                                    $sql = "update cb_msg_".$userid." set RESULT='Y', MESSAGE_TYPE='pn' where MSGID=?";
                                    
                                    $this->db->query($sql, $r["MSGID"]);
                                    //if ($this->db->error()['code'] > 0) { continue; }
                                    /* 폰문자 발송 테이블에 기록 */
                                    
                                    $pData = array();
                                    $pData['PR_SENDDATE'] = cdate('Y-m-d H:i:s');
                                    $pData['PR_ID'] = $r["MSGID"];
                                    $pData['PR_STATUS'] = "0";
                                    $pData['PR_PHONE'] = "0".substr($r["PHN"], 2);
                                    $pData['PR_CALLBACK'] = $r["SMS_SENDER"];
                                    $pData['PR_MSG'] = $msg_sms;
                                    $pData['PR_SUBJECT'] = $r["SMS_LMS_TIT"];
                                    $this->db->insert("cb_pr_tran", $pData);
                                    
                                    $sql = "update cb_wt_msg_sent set mst_phn=ifnull(mst_phn,0)+1 where mst_id=?";
                                    $this->db->query($sql, $sent_key);
                                    if ($this->db->error()['code'] > 0) { continue; }
                                    
                                    if ($this->db->error()['code'] < 1) { $phn++; }
                                    unset($pData);
                                    
                                    /*
                                     // 20180523 hsjin
                                     // 2차 발신(폰문자, SMS, LMS, MMS 등등) 모두 나셀프 에이전트가 처리
                                     $newItem = array();
                                     $newItem['subject'] = $r["SMS_LMS_TIT"];
                                     $newItem['phone'] = "0".substr($r["PHN"], 2);
                                     $newItem['callback'] = $r["SMS_SENDER"];
                                     $newItem['reqdate'] = cdate('Y-m-d H:i:s');
                                     $newItem['msg'] = $r["MSG_SMS"];
                                     $newItem['TYPE'] = "2";
                                     $this->db->insert("neo_msg", $newItem);
                                     if ($this->db->error()['code'] < 1) { $phn++; }
                                     unset($newItem);
                                     */
                                }
                            } else {
                                /* 2차발신이 없는 경우 카카오 실패 건수 처리 */
                                if($r['MESSAGE_TYPE']=="ft") {
                                    $sql = "update cb_wt_msg_sent set ".(($r['IMAGE_URL']==null || $r['IMAGE_URL']=="") ? "mst_err_ft=ifnull(mst_err_ft,0)+1" : "mst_err_ft_img=ifnull(mst_err_ft_img,0)+1")." where mst_id=?";
                                    $this->db->query($sql, $sent_key);
                                } else if($r['MESSAGE_TYPE']=="at") {
                                    $sql = "update cb_wt_msg_sent set mst_err_at=ifnull(mst_err_at,0)+1 where mst_id=?";
                                    $this->db->query($sql, $sent_key);
                                }
                            }
                        } else {
                            /* 2차발신이 없는 경우 카카오 실패 건수 처리 */
                            if($r['MESSAGE_TYPE']=="ft") {
                                $sql = "update cb_wt_msg_sent set ".(($r['IMAGE_URL']==null || $r['IMAGE_URL']=="") ? "mst_err_ft=ifnull(mst_err_ft,0)+1" : "mst_err_ft_img=ifnull(mst_err_ft_img,0)+1")." where mst_id=?";
                                $this->db->query($sql, $sent_key);
                            } else if($r['MESSAGE_TYPE']=="at") {
                                $sql = "update cb_wt_msg_sent set mst_err_at=ifnull(mst_err_at,0)+1 where mst_id=?";
                                $this->db->query($sql, $sent_key);
                            }
                        }
                    }
            }
        }
        echo '{"success":'.$ok.', "fail":'.$err.', "phn":'.$phn.', "sms":'.$sms.', "datetime":"'.cdate('Y-m-d H:i:s').'"}';
        
        /*
         * 동보 전송 처리시 필요한 구문...
         *
         * select  b.*, group_concat(phn),max(a.msg_type), count(1) from cb_nanoit_msg a , cb_wt_msg_sent_v b
         where a.remark4 = b.mst_id
         */
    }
    /*
     * 나노 아이티 동보 전송 만들기...
     *
     */
    public function nanoit_summary_send() {
        //return;
        $this->load->model("Biz_free_model");
        $base_price = $this->Biz_free_model->getBasePrice();
        $adm_price = $this->Biz_free_model->getMemberPrice(3);
        
        $sql = "select cnm.remark4
                      ,(sn div 50) as part
                      ,group_concat(cnm.phn) as PHN
                      ,group_concat(concat('82', right(phn, length(phn)-1))) as msg_phn
                      ,group_concat(cnm.sn) as sn
                      ,wms.mst_lms_content as MSG_SMS
                      ,wms.mst_sms_callback as SMS_SENDER
                      ,cnm.msg_type
                      ,cm.mem_userid as user_id
                      ,cm.mem_id
                      ,cm.mem_level
                      ,wms.mst_reserved_dt as RESERVE_DT
                      ,max(sn) as max_sn
                  from cb_nanoit_msg cnm
                 inner join cb_wt_msg_sent wms
                    on cnm.remark4 = wms.mst_id
                 inner join cb_member cm
                    on wms.mst_mem_id = cm.mem_id
                 group by cnm.remark4
                         ,(sn div 50)
                         ,wms.mst_lms_content
                      ,wms.mst_sms_callback
                      ,cnm.msg_type
                 order by remark4
                         ,sn
                ";
        
        $list = $this->db->query($sql)->result_array();
        
        foreach($list as $r) {
            
            $price = $this->Biz_free_model->getParentPrice($r['mem_id']);
            $mem_price = $this->Biz_free_model->getMemberPrice($r['mem_id']);
            $sent_key = $r['remark4'];
            $ins_cnt = substr_count($r["PHN"], ",") + 1;
            $userid = $r["user_id"];
            $mem_lv = $r["mem_level"];
            $mem_resend = $r['msg_type'];
            
            if($mem_resend == "015") {

                
                $del = "delete from cb_nanoit_msg where sn in (".$r['sn'].")";
                $this->db->query($del);
                
                $web015 = array();
                
                $web015['rcv_phone'] = $r["PHN"];
                $web015['subject'] = substr(str_replace(array("\r\n", "\r", "\n"),'',$r["MSG_SMS"]), 0, 40);
                $web015['text'] = str_replace("\xC2\xA0", ' ', $r["MSG_SMS"]);
                $web015['msg_st'] = 0;
                $web015['ins_dttm'] = cdate('Y-m-d H:i:s');
                $web015['req_dttm'] = cdate('Y-m-d H:i:s');
                $web015['cb_msg_id'] = $r['mem_id'];
                $web015['remark4'] = $r['remark4'];
                $web015['max_sn'] = $r['max_sn'];
                
                $this->db->insert("pms_msg", $web015);
                
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "NANOIT 015 Insert error : ".$this->db->error()['code']);
                    continue;
                }
                
                $sql = "update cb_wt_msg_sent
                           set mst_015=ifnull(mst_015,0)+".$ins_cnt."
                         where mst_id='".$sent_key."'";
                $this->db->query($sql );
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "NANOIT 015 WT Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $sql = "update cb_msg_".$userid."
                           set MESSAGE_TYPE='15'
                              ,CODE='015'
                              ,MESSAGE = '결과 수신대기'
                         where remark4='".$sent_key."' and phn in (".$r["msg_phn"].")";
                $this->db->query($sql );
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "NANOIT 015 MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $amt = $mem_price['mad_price_015'];
                $amt_admin = $adm_price['mad_price_015'];
                
                if(is_null($amt) || $amt == 0) {
                    $amt = $amt_admin;
                }
                
                $_015amt = array();
                $_015amt['amt_datetime'] = cdate('Y-m-d H:i:s');
                $_015amt['amt_kind'] = 'P';
                $_015amt['amt_amount'] = $amt * $ins_cnt;
                $_015amt['amt_memo'] = '015저가문자';
                $_015amt['amt_reason'] = $sent_key."_".$r['max_sn'];
                
                $_015amt['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_015'] * $ins_cnt;
                $_015amt['amt_admin'] = $amt_admin* $ins_cnt;
                
                $this->db->insert("cb_amt_".$userid, $_015amt);
                if ($this->db->error()['code'] > 0 ) {
                    //log_message("ERROR", "NANOIT 015 AMT Insert error : ".$this->db->error()['code']);
                    continue;
                }
                
                unset($_015amt);
                unset($web015);

            } else if($mem_resend == "PHONE") {

                $del = "delete from cb_nanoit_msg where sn in (".$r['sn'].")";
                $this->db->query($del);
                
                $phone = array();
                $phone['rcv_phone'] = $r["PHN"];
                $phone['subject'] = substr(str_replace(array("\r\n", "\r", "\n"),'',$r["MSG_SMS"]), 0, 40);
                $phone['text'] = str_replace("\xC2\xA0", ' ', $r["MSG_SMS"]);
                $phone['msg_st'] = 0;
                $phone['ins_dttm'] = cdate('Y-m-d H:i:s');
                $phone['req_dttm'] = cdate('Y-m-d H:i:s');
                $phone['cb_msg_id'] = $r['mem_id'];
                $phone['remark4'] = $sent_key;
                $phone['max_sn'] = $r['max_sn'];
                
                $this->db->insert("pms_msg", $phone);
                
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "NANOIT PHN Insert error : ".$this->db->error()['code']);
                    continue;
                }
                
                $sql = "update cb_wt_msg_sent
                           set mst_phn=ifnull(mst_phn,0)+".$ins_cnt."
                         where mst_id='".$sent_key."'";
                $this->db->query($sql );
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "NANOIT PHN WT Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $sql = "update cb_msg_".$userid."
                           set MESSAGE_TYPE='ph'
                              ,CODE='PHN'
                              ,MESSAGE = '결과 수신대기'
                         where remark4='".$sent_key."' and phn in (".$r["msg_phn"].")";
                $this->db->query($sql );
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "NANOIT PHN MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $amt = $mem_price['mad_price_phn'];
                $amt_admin = $adm_price['mad_price_phn'];
                if(is_null($amt) || $amt==0) {
                    $amt = $amt_admin;
                }
                
                $phnamt = array();
                $phnamt['amt_datetime'] = cdate('Y-m-d H:i:s');
                $phnamt['amt_kind'] = 'P';
                $phnamt['amt_amount'] = $amt * $ins_cnt;
                $phnamt['amt_memo'] = '폰문자';
                $phnamt['amt_reason'] = $sent_key."_".$r['max_sn'];
                
                $phnamt['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_phn'] * $ins_cnt;
                $phnamt['amt_admin'] = $amt_admin* $ins_cnt;
                
                $this->db->insert("cb_amt_".$userid, $phnamt);
                if ($this->db->error()['code'] > 0 ) {
                    //log_message("ERROR", "NANOIT PHN AMT Insert error : ".$this->db->error()['code']);
                    continue;
                }
                
                unset($phnamt);
                unset($phone);
                
            } else if($mem_resend == "GRS") {

                $del = "delete from cb_nanoit_msg where sn in (".$r['sn'].")";
                $this->db->query($del);
                
                $greenshot = array();
                $greenshot['msg_gb'] = "LMS";
                $greenshot['msg_st'] = "0";
                $greenshot['msg_snd_phn'] = $r["SMS_SENDER"];
                $greenshot['msg_rcv_phn'] =  $r["PHN"] ;
                $greenshot['subject'] = substr(str_replace(array("\r\n", "\r", "\n"),'',$r["MSG_SMS"]), 0, 40);
                $greenshot['text'] = str_replace("\xC2\xA0", ' ', $r["MSG_SMS"]);
                $greenshot['cb_msg_id']  = $r['mem_id'];
                $greenshot['remark4'] = $sent_key;
                $greenshot['max_sn'] = $r['max_sn'];
                
                // 예약 시간이 있으면 예약 문자로 발송 함.
                if($r["RESERVE_DT"]!="00000000000000") {
                    $greenshot['msg_req_dttm'] = $r["RESERVE_DT"];
                } else {
                    $greenshot['msg_req_dttm'] = cdate('Y-m-d H:i:s');
                }
                
                $this->db->insert("grs_msg", $greenshot);
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "NANOIT GRS Insert error : ".$this->db->error()['code']);
                    continue;
                }
                
                $sql = "update cb_wt_msg_sent
                           set mst_grs=ifnull(mst_grs,0)+".$ins_cnt."
                         where mst_id='".$sent_key."'";
                $this->db->query($sql );
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "NANOIT GRS WT Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $sql = "update cb_msg_".$userid."
                           set MESSAGE_TYPE='gs'
                              ,CODE='GRS'
                              ,MESSAGE = '결과 수신대기'
                         where remark4='".$sent_key."' and phn in (".$r["msg_phn"].")";
                $this->db->query($sql );
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "NANOIT GRS MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $amt = $mem_price['mad_price_grs'];
                $amt_admin = $adm_price['mad_price_grs'];
                
                if(is_null($amt)) {
                    $amt = $amt_admin;
                }
                
                $gsamt = array();
                $gsamt['amt_datetime'] = cdate('Y-m-d H:i:s');
                $gsamt['amt_kind'] = 'P';
                $gsamt['amt_amount'] = $amt * $ins_cnt;
                $gsamt['amt_memo'] = 'Green Shot';
                $gsamt['amt_reason'] = $sent_key."_".$r['max_sn'];
                
                $gsamt['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_grs'] * $ins_cnt;
                $gsamt['amt_admin'] = $amt_admin * $ins_cnt;
                
                $this->db->insert("cb_amt_".$userid, $gsamt);
                if ($this->db->error()['code'] > 0 ) {
                    //log_message("ERROR", "NANOIT GRS AMT Insert error : ".$this->db->error()['code']);
                    continue;
                }
                
                unset($greenshot);
                unset($gsamt);
                

            }
            
        }
    }

    public function dooit_summary_send() {
        //return;
        $this->load->model("Biz_free_model");
        $base_price = $this->Biz_free_model->getBasePrice();
        $adm_price = $this->Biz_free_model->getMemberPrice(3);
        
        $sql = "select cdm.remark4
                      ,(sn div 50) as part
                      ,group_concat(cdm.phn) as PHN
                      ,group_concat(concat('82', right(phn, length(phn)-1))) as msg_phn
                      ,group_concat(concat('''',cdm.msg_t_id,'''')) as msg_id
                      ,group_concat(cdm.sn) as sn
                      ,wms.mst_lms_content as MSG_SMS
                      ,wms.mst_sms_callback as SMS_SENDER
                      ,cdm.msg_type
                      ,cm.mem_userid as user_id
                      ,cm.mem_id
                      ,cm.mem_level
                      ,wms.mst_reserved_dt as RESERVE_DT
                      ,max(sn) as max_sn
                  from cb_dooit_msg cdm
                 inner join cb_wt_msg_sent wms
                    on cdm.remark4 = wms.mst_id
                 inner join cb_member cm
                    on wms.mst_mem_id = cm.mem_id
                 group by cdm.remark4
                         ,(sn div 50)
                         ,wms.mst_lms_content
                      ,wms.mst_sms_callback
                      ,cdm.msg_type
                 order by remark4
                         ,sn
                ";
        
        $list = $this->db->query($sql)->result_array();
        
        foreach($list as $r) {
            $del = "delete from cb_dooit_msg where sn in (".$r['sn'].")";
            $this->db->query($del);
            
            $price = $this->Biz_free_model->getParentPrice($r['mem_id']);
            $mem_price = $this->Biz_free_model->getMemberPrice($r['mem_id']);
            $sent_key = $r['remark4'];
            $ins_cnt = substr_count($r["PHN"], ",") + 1;
            $userid = $r["user_id"];
            $mem_lv = $r["mem_level"];
            $mem_resend = $r['msg_type'];

            $sms_url = "http://www.martapp.co.kr/ezcom/smssend_phone.php";		// 변경X
            //$returnurl  = "http://";			// 반환 URL
            $sms['transtype'] = '1';					// base64_encode 사용시 1 미사용시 0 ※추가된 항목
            $sms['sendtype'] = base64_encode($mem_resend);						// 전송 Mode : SMS:1 LMS:2
            //$sms['testflag'] = base64_encode("Y");						// 실제 전송 없이 TEST = Y/ 실제 전송시 생략
            $sms['uid'] = base64_encode("dhnet");					// 사용자 ID
            $sms['passwd'] = base64_encode("01*378");					// 사용자 PWD
            $sms['title'] = base64_encode(substr(str_replace(array("\r\n", "\r", "\n"),'',$r["MSG_SMS"]), 0, 40));				// MMS 전송 제목(SMS 전송시 생략) (※전송 방법[POST/GET]에 관계 없이 무조건 Encodeing 해줍니다)
            $sms['msg'] = base64_encode(str_replace("\xC2\xA0", ' ', $r["MSG_SMS"]));					// 전송 메시지 본문 (※전송 방법[POST/GET]에 관계 없이 무조건 Encodeing 해줍니다)
            $sms['sphone'] = base64_encode($r["SMS_SENDER"]);			// 발진자 번호
            $sms['rphone'] = base64_encode($r["PHN"]);			// 수신자 번호 ( ","(콤마)를 구분자로 다중 전송 지원
            if($r["RESERVE_DT"] !="00000000000000"  ) {
                $sms['rdate'] = base64_encode(substr($r["RESERVE_DT"], 0, 8));				// 예약 전송 날짜YYYYMMDD(즉시 전송시 생략)
                $sms['rtime'] = base64_encode(substr($r["RESERVE_DT"], -6));				// 예약 전송 시간HHMMSS(즉시 전송시 생략)
            } else {
                $sms['rdate'] = base64_encode('');				// 예약 전송 날짜YYYYMMDD(즉시 전송시 생략)
                $sms['rtime'] = base64_encode('');				// 예약 전송 시간HHMMSS(즉시 전송시 생략)
            }
            //$nointeractive = base64_encode($_POST['nointeractive']);	// 사용할 경우 : 1, 성공시 대화상자(alert)생략
            
            $host_info = explode("/", $sms_url);
            $host = $host_info[2];
            $path = $host_info[3]."/".$host_info[4];
            
            $boundary = '';
            $data = '';
            
            srand((double)microtime()*1000000);
            $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
            //print_r($sms);
            
            
            // 헤더 생성
            $header = "POST /".$path ." HTTP/1.0\r\n";
            $header .= "Host: ".$host."\r\n";
            $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";
            
            // 본문 생성
            foreach($sms AS $index => $value){
                $data .="--$boundary\r\n";
                $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
                $data .= "\r\n".$value."\r\n";
                $data .="--$boundary\r\n";
            }
            $header .= "Content-length: " . strlen($data) . "\r\n\r\n";
            
            $fp = fsockopen($host, 80);
            
            $code = "";
            $result_msg = "";
            if ($fp) {
                fputs($fp, $header.$data);
                $rsp = '';
                while(!feof($fp)) {
                    $rsp .= fgets($fp,8192);
                }
                fclose($fp);
                $msg =explode("\r\n\r\n", trim($rsp));
                $rMsg = explode(",", $msg[1]);
                $Result = $rMsg[0];	// 전송결과 : 전송 결과는 데이터 시트 참조
                
                // 발송 결과 알림
                
                if(0 == $Result || '0' == $Result){
                    $code = "SUCCESS";
                    
                    $usql = "update cb_msg_".$userid." set RESULT='Y', MESSAGE_TYPE='ph', MESSAGE ='폰문자 성공' where MSGID in (".$r['msg_id'].")";
                    $this->db->query($usql);
                    
                    $sql = "update cb_wt_msg_sent
                           set mst_phn=ifnull(mst_phn,0)+".$ins_cnt."
                         where mst_id='".$sent_key."'";
                    $this->db->query($sql );
                    
                    $amt = $mem_price['mad_price_dooit'];
                    $amt_admin = $adm_price['mad_price_dooit'];
                    
                    if(is_null($amt) || $amt == 0) {
                        $amt = $amt_admin;
                    }
                    
                    $_dooitamt = array();
                    $_dooitamt['amt_datetime'] = cdate('Y-m-d H:i:s');
                    $_dooitamt['amt_kind'] = 'P';
                    $_dooitamt['amt_amount'] = $amt * $ins_cnt;
                    $_dooitamt['amt_memo'] = '폰문자';
                    $_dooitamt['amt_reason'] = $sent_key."_".$r['max_sn'];
                    
                    $_dooitamt['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_dooit'] * $ins_cnt;
                    $_dooitamt['amt_admin'] = $amt_admin* $ins_cnt;
                    
                    $this->db->insert("cb_amt_".$userid, $_dooitamt);
                    if ($this->db->error()['code'] > 0 ) {
                        //log_message("ERROR", "NANOIT 015 AMT Insert error : ".$this->db->error()['code']);
                        continue;
                    }
                    
                    unset($_dooitamt);
                } else {
                    $code = "ERROR";
                    
                    if('100' == $Result || 100 == $Result) {
                        $result_msg = "Disabled Account";
                    } else if('200' == $Result|| 200 == $Result) {
                        $result_msg = "No exist ID or wrong Password";
                    } else if('300' == $Result || 300 == $Result) {
                        $result_msg = "Send type not matched";
                    } else if('301' == $Result || 301 == $Result) {
                        $result_msg = "'GET' Method Message length can not over 512 character";
                    } else if('302' == $Result || 302 == $Result) {
                        $result_msg = "Parameter value has incorrent";
                    } else if('303' == $Result || 303 == $Result) {
                        $result_msg = "Cybercash not enough";
                    } else if('304' == $Result || 304 == $Result) {
                        $result_msg = 'Sender Phone number required';
                    } else if('305' == $Result || 305 == $Result) {
                        $result_msg = "Reciever Phone number required";
                    } else if('900' == $Result || 900 == $Result) {
                        $result_msg = "busy try again later";
                    } else {
                        $result_msg = "$Result";
                    }

                }
            } else {
                $result_msg = "Error : Connection Failed";
                $code = "ERROR";
            }
           // //log_message("ERROR", "Code : ".$code." / MSG : ".$msg);
            if($code == "ERROR") {
                $usql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='ph', MESSAGE ='$result_msg' where MSGID in (".$r['msg_id'].")";
                $this->db->query($usql);
                
                $sql = "update cb_wt_msg_sent set mst_err_phn=ifnull(mst_err_phn,0)+1, mst_phn=mst_phn-1 where mst_id=?";
                $this->db->query($sql, $sent_key);
            }
        }
    }
    
    public function check_sms_agent() {
        /*
         * 월 변경시 Log 테이블 생성
         */
        if(date("d") == "01") {
            $this->create_log_table();
        }
        
        /* NANOIT 결과 처리 프로세스 */
        /* 폰 문자 확인 */
        $phn = 0; $sms=0;
        $this->load->model("Biz_free_model");
        
        // 기본 단가 수집
        $base_price = $this->Biz_free_model->getBasePrice();	//$this->db->query($sql)->row_array();
        $adm_price = $this->Biz_free_model->getMemberPrice(3);	//- 관리자단가
        
        // NASELF 처리
        $ok = 0; $err = 0; $before_id = "";
        $sql = "select cnm.cb_msg_id as MSGID
                      ,cnm.MSGKEY
                      ,cnl.RSLT
                      ,cnm.REMARK4
                      ,cm.mem_userid
                      ,cm.mem_level
                      ,cm.mem_phn_agent
                      ,cm.mem_sms_agent
                      ,cm.mem_2nd_send
                      ,cm.mem_id
                      ,cnm.TYPE
                      ,cnm.ETC3
                  from cb_neo_msg_bk cnm
                 inner join cb_neo_log_".date("Ym")." cnl
                    on cnm.msgkey = cnl.msgkey
                 inner join cb_member cm
                    on cm.mem_id = split(cnm.cb_msg_id, '_', 1)
                   and cnl. status = '3'
                   and ( cnl.sentdate is not null
                   or cnl.terminateddate is not null )";
        $list = $this->db->query($sql)->result_array();
        //log_message('ERROR', 'NASELF 시작 : '.count($list)." / ".$this->db->error()['code']);
        //log_message('ERROR', 'NASELF Query : '.$sql);
        foreach($list as $r)
        {
            $userid = $r['mem_userid'];
            if(strpos($r['MSGID'], "_")!==false) {
                $mem_lv = $r['mem_level'];
                $mem_phn = $r['mem_phn_agent'];
                $mem_sms = $r['mem_sms_agent'];
                $mem_resend = $r['mem_2nd_send'];
                unset($r['mem_level']);
                unset($r['mem_phn_agent']);
                unset($r['mem_sms_agent']);
                unset($r['mem_2nd_send']);
                
                $ids = explode("_", $r['MSGID']);
                if($before_id=="" || $before_id != $ids[0]) {
                    // 사용자별 단가 수집 : id가 변하는 경우만 처리
                    $price = $this->Biz_free_model->getParentPrice($ids[0]);	//$this->db->query($sql, array($ids[0]))->row_array();
                    if($price['mad_mem_id']!=$ids[0]) {
                        $price = $base_price;
                    }
                    $mem_price = $this->Biz_free_model->getMemberPrice($ids[0]);
                    $before_id = $ids[0];
                }
                
                // 성공했으면 금액 차감, MASTER테이블에 카운터 저장
                $sent_key = $r['REMARK4'];	// cb_wt_msg_sent 키
                //log_message('ERROR', 'rslt text = '.$r['rslt']);
                if($r['RSLT']=="100") {
                    
                    $this->db->query("delete from cb_neo_msg_bk where MSGKEY=?", array($r['MSGKEY']));
                    if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error());continue; }
                    if(empty($r['ETC3'])) {
                        $sql = "update cb_msg_".$userid." set RESULT='Y', MESSAGE_TYPE='ns', MESSAGE ='NS 성공' where MSGID=?";
                    } else {
                        $sql = "update cb_msg_".$userid." set RESULT='Y', MESSAGE_TYPE='gs', MESSAGE ='GRS 성공' where MSGID=?";
                    }
                    
                    $this->db->query($sql, $r["MSGID"]);
                    unset($data);
                } else if($r['RSLT']!="100")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                    $this->db->query("delete from cb_neo_msg_bk where MSGKEY=?", array($r['MSGKEY']));
                    if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error());continue; }
                    
                    if(empty($r['ETC3'])) {
                        $sql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='ns',CODE='NAS', MESSAGE = '".$r["RSLT"]."' where MSGID=?";
                    } else {
                        $sql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='gs',CODE='GRS', MESSAGE = '".$r["RSLT"]."' where MSGID=?";
                    }
                    
                    $this->db->query($sql, $r["MSGID"]);
                    
                    if($r['TYPE']=="1") {
                        if(empty($r['ETC3'])) {
                            $sql = "update cb_wt_msg_sent set mst_err_nas_sms=ifnull(mst_err_nas_sms,0)+1, mst_nas_sms=mst_nas_sms-1  where mst_id=?";
                        } else {
                            $sql = "update cb_wt_msg_sent set mst_err_grs_sms=ifnull(mst_err_grs_sms,0)+1, mst_grs_sms=mst_grs_sms-1  where mst_id=?";
                        }
                    } else {
                        if(empty($r['ETC3'])) {
                            $sql = "update cb_wt_msg_sent set mst_err_nas=ifnull(mst_err_nas,0)+1, mst_nas=mst_nas-1  where mst_id=?";
                        } else {
                            $sql = "update cb_wt_msg_sent set mst_err_grs=ifnull(mst_err_grs,0)+1, mst_grs=mst_grs-1  where mst_id=?";
                        }
                    }
                    $this->db->query($sql, $sent_key);
                    
                    /*
                     * 예치금 환급
                     */
                    $nasrefund = "select * from cb_amt_".$userid. " where amt_reason='".$r['MSGID']."' and amt_kind='P'";
                    $refundlist = $this->db->query($nasrefund)->result_array();
                    foreach($refundlist as $rf)
                    {
                        $data = array();
                        $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                        $data['amt_kind'] = '3';
                        $data['amt_amount'] =$rf["amt_amount"];
                        if(empty($r['ETC3'])) {
                            $data['amt_memo'] = 'WEB(B)발송실패:환불';
                        } else {
                            $data['amt_memo'] = 'WEB(A)발송실패:환불';
                        }
                        $data['amt_reason'] = $r['MSGID'];
                        
                        $data['amt_payback'] = $rf["amt_payback"] * -1;
                        $data['amt_admin'] = $rf["amt_admin"] * -1;
                        
                        $this->db->insert("cb_amt_".$userid, $data);
                        if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                    }
                }
            }
        }
        
        unset($list);
        
        // SMS 처리
        $mmscheck = "select 1 from cb_nas_sms_msg_log_".date("Ym");
        $mms_exists = $this->db->query($mmscheck);
        if($mms_exists != FALSE)
        {
            $ok = 0; $err = 0; $before_id = "";
            $sql = "SELECT  cml.TR_ETC1 AS MSGID,
                            cml.tr_num as MSGKEY,
                            cml.tr_rsltstat as RSLT,
                            cml.TR_ETC2 AS REMARK4,
                            cm.mem_userid,
                            cm.mem_level,
                            cm.mem_phn_agent,
                            cm.mem_sms_agent,
                            cm.mem_2nd_send,
                            cm.mem_id
                       FROM cb_nas_sms_msg_log_".date("Ym")." cml,
                            cb_member cm
                      WHERE cml.tr_etc4 = cm.mem_id
                        AND cml.tr_sendstat= '2'
                    order by tr_senddate
                        limit 0, 100
                    ";
            $list = $this->db->query($sql)->result_array();
            
            foreach($list as $r)
            {
                $userid = $r['mem_userid'];
                if(strpos($r['MSGID'], "_")!==false) {
                    
                    $ids = explode("_", $r['MSGID']);
                    
                    // 성공했으면 금액 차감, MASTER테이블에 카운터 저장
                    $sent_key = $r['REMARK4'];	// cb_wt_msg_sent 키
                    //log_message('ERROR', 'rslt text = '.$r['BC_RSLT_TEXT']);
                    if($r['RSLT']=="0") {
                        
                        $sql = "update cb_msg_".$userid." set RESULT='Y', MESSAGE_TYPE='ns', MESSAGE ='NS 성공' where MSGID=?";
                        
                        $this->db->query($sql, $r["MSGID"]);
                        unset($data);
                    } else if($r['RSLT']!="0")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                        
                        $sql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='ns',CODE='NAS', MESSAGE = '".$r["RSLT"]."' where MSGID=?";
                        $this->db->query($sql, $r["MSGID"]);
                        
                        $sql = "update cb_wt_msg_sent set mst_err_nas_sms=ifnull(mst_err_nas_sms,0)+1, mst_nas_sms=mst_nas_sms-1  where mst_id=?";
                        $this->db->query($sql, $sent_key);
                        
                        $nasrefund = "select * from cb_amt_".$userid. " where amt_reason='".$r['MSGID']."' and amt_kind='P'";
                        $refundlist = $this->db->query($nasrefund)->result_array();
                        foreach($refundlist as $rf)
                        {
                            $data = array();
                            $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                            $data['amt_kind'] = '3';
                            $data['amt_amount'] =$rf["amt_amount"];
                            $data['amt_memo'] = 'WEB(B)발송실패:환불';
                            $data['amt_reason'] = $r['MSGID'];
                            
                            $data['amt_payback'] = $rf["amt_payback"] * -1;
                            $data['amt_admin'] = $rf["amt_admin"] * -1;
                            
                            $this->db->insert("cb_amt_".$userid, $data);
                            if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                        }
                        
                    }
                    
                    $sql = "update cb_nas_sms_msg_log_".date("Ym")." set tr_sendstat = '4' where tr_num =?";
                    $this->db->query($sql,  $r['MSGKEY']);
                    
                }
            }
            
            unset($list);
        }
        
        // LMS / MMS 처리
        $mmscheck = "select 1 from cb_nas_mms_msg_log_".date("Ym");
        $mms_exists = $this->db->query($mmscheck);
        if($mms_exists != FALSE)
        {
            $ok = 0; $err = 0; $before_id = "";
            $sql = "SELECT  cml.ETC1 AS MSGID,
                            cml.MSGKEY as MSGKEY,
                            cml.RSLT as RSLT,
                            cml.ETC2 AS REMARK4,
                            cm.mem_userid,
                            cm.mem_level,
                            cm.mem_phn_agent,
                            cm.mem_sms_agent,
                            cm.mem_2nd_send,
                            cm.mem_id
                       FROM cb_nas_mms_msg_log_".date("Ym")." cml,
                            cb_member cm
                      WHERE cml.etc4 = cm.mem_id
                        AND cml.status= '3'
                    order by msgkey
                        limit 0, 1000
                    ";
            $list = $this->db->query($sql)->result_array();
            
            foreach($list as $r)
            {
                $userid = $r['mem_userid'];
                if(strpos($r['MSGID'], "_")!==false) {
                    
                    $ids = explode("_", $r['MSGID']);
                    
                    // 성공했으면 금액 차감, MASTER테이블에 카운터 저장
                    $sent_key = $r['REMARK4'];	// cb_wt_msg_sent 키
                    //log_message('ERROR', 'rslt text = '.$r['BC_RSLT_TEXT']);
                    if($r['RSLT']=="0") {
                        
                        $sql = "update cb_msg_".$userid." set RESULT='Y', MESSAGE_TYPE='ns', MESSAGE ='NAS 성공' where MSGID=?";
                        
                        $this->db->query($sql, $r["MSGID"]);
                        unset($data);
                    } else if($r['RSLT']!="0")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                        
                        $sql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='ns',CODE='NAS', MESSAGE = '".$r["RSLT"]."' where MSGID=?";
                        $this->db->query($sql, $r["MSGID"]);
                        
                        $sql = "update cb_wt_msg_sent set mst_err_nas=ifnull(mst_err_nas,0)+1, mst_nas=mst_nas-1  where mst_id=?";
                        $this->db->query($sql, $sent_key);
                        
                        $nasrefund = "select * from cb_amt_".$userid. " where amt_reason='".$r['MSGID']."' and amt_kind='P'";
                        $refundlist = $this->db->query($nasrefund)->result_array();
                        foreach($refundlist as $rf)
                        {
                            $data = array();
                            $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                            $data['amt_kind'] = '3';
                            $data['amt_amount'] =$rf["amt_amount"];
                            $data['amt_memo'] = 'WEB(B)발송실패:환불';
                            $data['amt_reason'] = $r['MSGID'];
                            
                            $data['amt_payback'] = $rf["amt_payback"] * -1;
                            $data['amt_admin'] = $rf["amt_admin"] * -1;
                            
                            $this->db->insert("cb_amt_".$userid, $data);
                            if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                        }
                        
                    }
                    
                    $sql = "update cb_nas_mms_msg_log_".date("Ym")." set STATUS = '4' where msgkey =?";
                    $this->db->query($sql,  $r['MSGKEY']);
                    
                }
            }
            
            unset($list);
        }
        //log_message("ERROR", "Table 없음.");
        
        echo '{"success":'.$ok.', "fail":'.$err.', "phn":'.$phn.', "sms":'.$sms.', "datetime":"'.cdate('Y-m-d H:i:s').'"}';
        
        echo '{"success":'.$ok.', "fail":'.$err.', "phn":'.$phn.', "sms":'.$sms.', "datetime":"'.cdate('Y-m-d H:i:s').'"}';
        
    }
    
    public function check_nanoit_agent() {
        /*
         * 월 변경시 Log 테이블 생성
         */
        $this->load->model("Biz_free_model");
        $base_price = $this->Biz_free_model->getBasePrice();
        $adm_price = $this->Biz_free_model->getMemberPrice(3);
        
        if(date("d") == "01") {
            $this->create_log_table();
        }
        //$this->create_log_table();
        $phn = 0; $sms=0;
        $this->load->model("Biz_free_model");
        
        // 폰문자 처리
        
        $sql = "select cpm.cb_msg_id as msgid
                      ,'M103' as code
                      ,'pn' as message_type
                      ,cpm.REMARK4
                      ,cpm.max_sn
                      ,cpm.text as msg
                      ,cpm.text as msg_sms
                      ,cpl.BC_RCV_PHONE
                      ,concat('82', right(cpl.bc_rcv_phone, length(cpl.bc_rcv_phone) - 1)) as PHN
                      ,cpm.req_dttm as reg_date
                      ,cpm.MSG_ID
                      ,cpl.BC_RSLT_NO 
                      ,cpl.BC_RSLT_TEXT
                      ,cpl.req_snd_dttm
                      ,cm.mem_userid
                      ,cm.mem_level
                      ,cm.mem_phn_agent
                      ,cm.mem_sms_agent
                      ,cm.mem_2nd_send
                      ,cm.mem_id
                  from cb_pms_msg_bk cpm
                 inner join CB_PMS_BROADCAST_LOG_".date("Ym")." cpl
                    on cpm.msg_id = cpl.msg_id
                   and cpm.rcv_phone like concat('%', cpl.bc_rcv_phone, '%')
                 inner join cb_member cm
                    on cm.mem_id = split(cpm.cb_msg_id, '_', 1)
                 where cpm.msg_st = '1'
                   and cpl.BC_SND_ST in (3, 4)
                 order by cpm.msg_id limit 0
                         ,1000";
        $list = $this->db->query($sql)->result_array();
        
        foreach($list as $r)
        {
            $userid = $r['mem_userid'];
            $sent_key = $r['REMARK4'];
            $price = $this->Biz_free_model->getParentPrice($r['mem_id']);
            $mem_price = $this->Biz_free_model->getMemberPrice($r['mem_id']);
            $mem_lv = $r["mem_level"];
            if($r['BC_RSLT_NO']=="0") {
                $sql = "update cb_msg_".$userid."
                           set RESULT='Y'
                              ,MESSAGE_TYPE='ph'
                              ,MESSAGE ='폰문자 성공'
                         where remark4 ='".$sent_key."'
                           and phn = '".$r['PHN']."'";
                
                $this->db->query($sql);
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 성공 - NANOIT PMS MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
            } else if($r['BC_RSLT_NO']!="0")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                $sql = "update cb_msg_".$userid."
                           set RESULT='N'
                              ,MESSAGE_TYPE='ph'
                              ,MESSAGE ='".$r["BC_RSLT_NO"]."'
                         where remark4 ='".$sent_key."'
                           and phn = '".$r['PHN']."'";
                
                $this->db->query($sql);
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 실패 - NANOIT PMS MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $sql = "update cb_wt_msg_sent set mst_err_phn=ifnull(mst_err_phn,0)+1, mst_phn=mst_phn-1 where mst_id=?";
                $this->db->query($sql, $sent_key);
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 실패 - NANOIT PMS WT Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $amt = $mem_price['mad_price_phn'];
                $amt_admin = $adm_price['mad_price_phn'];
                
                if(is_null($amt) || $amt == 0) {
                    $amt = $amt_admin;
                }
                //$grsrefund = "select * from cb_amt_".$userid. " where amt_reason='".$sent_key."_".$r['max_sn']."' and amt_kind = 'P'";
                //$refundlist = $this->db->query($grsrefund)->result_array();
                //foreach($refundlist as $rf)
                {
                    $data = array();
                    $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                    $data['amt_kind'] = '3';
                    $data['amt_amount'] = $amt;
                    $data['amt_memo'] = '폰문자 발송실패:환불';
                    $data['amt_reason'] = $sent_key."_".$r['max_sn']."_".$r['PHN'];
                    
                    $data['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_phn'] * -1;
                    $data['amt_admin'] =  $amt_admin * -1;
                    
                    $this->db->insert("cb_amt_".$userid, $data);
                    if ($this->db->error()['code'] > 0) {
                        //log_message("ERROR", "결과 실패 - NANOIT PMS 환불처리 error : ".$this->db->error()['code']);
                        continue;
                    }
                }
            }
            $sql = "update cb_pms_msg_bk set rcv_phone = replace(rcv_phone,'".$r['BC_RCV_PHONE']."','-1') where msg_id = '".$r['MSG_ID']."' and max_sn = '".$r['max_sn']."'" ;
            $this->db->query($sql );
            if ($this->db->error()['code'] > 0) {
                //log_message("ERROR", "결과 완료 - NANOIT GRS MSG Update error : ".$this->db->error()['code']);
                continue;
            }
        }
        unset($list);
        
        // GREEN_SHOT 처리
        
        $sql = "select cgm.cb_msg_id as msgid
                      ,cgm.msg_id
                      ,cgm.msg_st
                      ,cgm.max_sn
                      ,cgb.BC_RSLT_NO
                      ,(case
                         when cgb.bc_snd_st = '2' and cgb.bc_rslt_no = '0' and
                              addtime(cgb.bc_snd_dttm, '00:10:00') < now() then
                          '성공'
                         else
                          cgb.bc_rslt_text
                       end) as BC_RSLT_TEXT
                	  ,cgb.bc_rcv_phn
                      ,concat('82', right(cgb.bc_rcv_phn, length(cgb.bc_rcv_phn)-1)) as PHN
                      ,cgm.REMARK4
                      ,cm.mem_userid
                      ,cm.mem_level
                      ,cm.mem_phn_agent
                      ,cm.mem_sms_agent
                      ,cm.mem_2nd_send
                      ,cm.mem_id
                  from cb_grs_msg_bk cgm
                 inner join cb_grs_broadcast_".date("Ym")." cgb
                    on cgm.msg_id = cgb.msg_id
                   and cgm.msg_rcv_phn like concat('%', cgb.bc_rcv_phn , '%')
                 inner join cb_member cm
                    on cm.mem_id = cgm.cb_msg_id
                 where cgm.msg_st in ('1', '0')
                   and cgb.bc_snd_st in( '3', '4') 
                 order by cgm.msg_id limit 0
                         ,1000";
        $list = $this->db->query($sql)->result_array();
        
        foreach($list as $r)
        {
            
            $userid = $r['mem_userid'];
            $sent_key = $r['REMARK4'];
            $price = $this->Biz_free_model->getParentPrice($r['mem_id']);
            $mem_price = $this->Biz_free_model->getMemberPrice($r['mem_id']);
            $mem_lv = $r["mem_level"];
            
            if($r['BC_RSLT_NO']=="0" && $r['BC_RSLT_TEXT']=="성공") {
                
                $sql = "update cb_msg_".$userid."
                           set RESULT='Y'
                              ,MESSAGE_TYPE='gs'
                              ,MESSAGE = 'GS 성공'
                         where remark4 ='".$sent_key."'
                           and phn = '".$r['PHN']."'";
                $this->db->query($sql );
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 성공 - NANOIT GRS MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
                
            } else if($r['BC_RSLT_NO']!="0")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                
                $sql = "update cb_msg_".$userid."
                           set RESULT='N'
                              ,MESSAGE_TYPE='gs'
                              ,CODE='GRS'
                              ,MESSAGE = '".$r["BC_RSLT_NO"]."'
                         where remark4 ='".$sent_key."'
                           and phn = '".$r['PHN']."'";
                $this->db->query($sql );
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 실패 - NANOIT GRS MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $sql = "update cb_wt_msg_sent set mst_err_grs=ifnull(mst_err_grs,0)+1, mst_grs=mst_grs-1  where mst_id=?";
                $this->db->query($sql, $sent_key);
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 실패 - NANOIT GRS WT Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                //$grsrefund = "select * from cb_amt_".$userid. " where amt_reason='".$sent_key."_".$r['max_sn']."' and amt_kind = 'P'";
                //$refundlist = $this->db->query($grsrefund)->result_array();
                //foreach($refundlist as $rf)
                $amt = $mem_price['mad_price_grs'];
                $amt_admin = $adm_price['mad_price_grs'];
                
                if(is_null($amt) || $amt == 0) {
                    $amt = $amt_admin;
                }
                
                {
                    $data = array();
                    $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                    $data['amt_kind'] = '3';
                    $data['amt_amount'] =$amt;
                    $data['amt_memo'] = 'Green Shot 발송실패:환불';
                    $data['amt_reason'] =  $sent_key."_".$r['max_sn']."_".$r['PHN'];
                    
                    $data['amt_payback'] = ($mem_lv>=50) ? 0 : $price['payback_grs'] * -1;
                    $data['amt_admin'] = $amt_admin * -1;
                    
                    $this->db->insert("cb_amt_".$userid, $data);
                    if ($this->db->error()['code'] > 0) {
                        //log_message("ERROR", "결과 실패 - NANOIT GRS 환불처리 error : ".$this->db->error()['code']);
                        continue;
                    }
                }
            }
            
            $sql = "update cb_grs_msg_bk set msg_rcv_phn = replace(msg_rcv_phn,'".$r['bc_rcv_phn']."','-1') where msg_id = '".$r['msg_id']."' and max_sn = '".$r['max_sn']."'" ;
            $this->db->query($sql );
            if ($this->db->error()['code'] > 0) {
                //log_message("ERROR", "결과 완료 - NANOIT GRS MSG Update error : ".$this->db->error()['code']);
                continue;
            }
        }
        
        
        unset($list);
        
        // FUN 문자 SMS 처리
        $mmscheck = "select 1 from cb_sms_log_".date("Ym");
        $mms_exists = $this->db->query($mmscheck);
        if($mms_exists != FALSE)
        {
            $ok = 0; $err = 0; $before_id = "";
            $sql = "SELECT  cml.TR_ETC1 AS MSGID,
                            cml.tr_num as MSGKEY,
                            cml.tr_rsltstat as RSLT,
                            cml.TR_ETC2 AS REMARK4,
                            cm.mem_userid,
                            cm.mem_level,
                            cm.mem_phn_agent,
                            cm.mem_sms_agent,
                            cm.mem_2nd_send,
                            cm.mem_id
                       FROM cb_sms_log_".date("Ym")." cml,
                            cb_member cm
                      WHERE cml.tr_etc4 = cm.mem_id
                        AND cml.tr_sendstat= '2'
                    order by tr_senddate
                        limit 0, 100
                    ";
            $list = $this->db->query($sql)->result_array();
            
            foreach($list as $r)
            {
                $userid = $r['mem_userid'];
                if(strpos($r['MSGID'], "_")!==false) {
                    
                    $ids = explode("_", $r['MSGID']);
                    
                    // 성공했으면 금액 차감, MASTER테이블에 카운터 저장
                    $sent_key = $r['REMARK4'];	// cb_wt_msg_sent 키
                    //log_message('ERROR', 'rslt text = '.$r['BC_RSLT_TEXT']);
                    if($r['RSLT']=="0") {
                        
                        $sql = "update cb_msg_".$userid." set RESULT='Y', MESSAGE_TYPE='gs', MESSAGE ='GS 성공' where MSGID=?";
                        
                        $this->db->query($sql, $r["MSGID"]);
                        unset($data);
                    } else if($r['RSLT']!="0")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                        
                        $sql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='gs',CODE='GRS', MESSAGE = '".$r["RSLT"]."' where MSGID=?";
                        $this->db->query($sql, $r["MSGID"]);
                        
                        $sql = "update cb_wt_msg_sent set mst_err_grs_sms=ifnull(mst_err_grs_sms,0)+1, mst_grs_sms=mst_grs_sms-1  where mst_id=?";
                        $this->db->query($sql, $sent_key);
                        
                        $bkrefund = "select * from cb_amt_".$userid. " where amt_reason='".$r['MSGID']."' and amt_kind = 'P'";
                        $refundlist = $this->db->query($bkrefund)->result_array();
                        foreach($refundlist as $rf)
                        {
                            $data = array();
                            $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                            $data['amt_kind'] = '3';
                            $data['amt_amount'] =$rf["amt_amount"];
                            $data['amt_memo'] = 'Green Shot SMS 발송실패:환불';
                            $data['amt_reason'] = $r['MSGID'];
                            
                            $data['amt_payback'] = $rf["amt_payback"] * -1;
                            $data['amt_admin'] = $rf["amt_admin"] * -1;
                            
                            $this->db->insert("cb_amt_".$userid, $data);
                            if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                        }
                        
                    }
                    
                    $sql = "update cb_sms_log_".date("Ym")." set tr_sendstat = '4' where tr_num =?";
                    $this->db->query($sql,  $r['MSGKEY']);
                    
                }
            }
            
            unset($list);
        }
        
        // FUN 문자 LMS / MMS 처리
        $mmscheck = "select 1 from cb_mms_log_".date("Ym");
        $mms_exists = $this->db->query($mmscheck);
        if($mms_exists != FALSE)
        {
            $ok = 0; $err = 0; $before_id = "";
            $sql = "SELECT  cml.ETC1 AS MSGID,
                            cml.MSGKEY as MSGKEY,
                            cml.RSLT as RSLT,
                            cml.ETC2 AS REMARK4,
                            cm.mem_userid,
                            cm.mem_level,
                            cm.mem_phn_agent,
                            cm.mem_sms_agent,
                            cm.mem_2nd_send,
                            cm.mem_id
                       FROM cb_mms_log_".date("Ym")." cml,
                            cb_member cm
                      WHERE cml.etc4 = cm.mem_id
                        AND cml.status= '3'
                    order by msgkey
                        limit 0, 1000
                    ";
            $list = $this->db->query($sql)->result_array();
            
            foreach($list as $r)
            {
                $userid = $r['mem_userid'];
                if(strpos($r['MSGID'], "_")!==false) {
                    
                    $ids = explode("_", $r['MSGID']);
                    
                    // 성공했으면 금액 차감, MASTER테이블에 카운터 저장
                    $sent_key = $r['REMARK4'];	// cb_wt_msg_sent 키
                    //log_message('ERROR', 'rslt text = '.$r['BC_RSLT_TEXT']);
                    if($r['RSLT']=="0") {
                        
                        $sql = "update cb_msg_".$userid." set RESULT='Y', MESSAGE_TYPE='gs', MESSAGE ='GS 성공' where MSGID=?";
                        
                        $this->db->query($sql, $r["MSGID"]);
                        unset($data);
                    } else if($r['RSLT']!="0")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                        
                        $sql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='gs',CODE='GRS', MESSAGE = '".$r["RSLT"]."' where MSGID=?";
                        $this->db->query($sql, $r["MSGID"]);
                        
                        $sql = "update cb_wt_msg_sent set mst_err_grs=ifnull(mst_err_grs,0)+1, mst_grs=mst_grs-1  where mst_id=?";
                        $this->db->query($sql, $sent_key);
                        
                        $bkrefund = "select * from cb_amt_".$userid. " where amt_reason='".$r['MSGID']."' and amt_kind = 'P'";
                        $refundlist = $this->db->query($bkrefund)->result_array();
                        foreach($refundlist as $rf)
                        {
                            $data = array();
                            $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                            $data['amt_kind'] = '3';
                            $data['amt_amount'] =$rf["amt_amount"];
                            $data['amt_memo'] = 'FUN 발송실패:환불';
                            $data['amt_reason'] = $r['MSGID'];
                            
                            $data['amt_payback'] = $rf["amt_payback"] * -1;
                            $data['amt_admin'] = $rf["amt_admin"] * -1;
                            
                            $this->db->insert("cb_amt_".$userid, $data);
                            if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                        }
                        
                    }
                    
                    $sql = "update cb_mms_log_".date("Ym")." set STATUS = '4' where msgkey =?";
                    $this->db->query($sql,  $r['MSGKEY']);
                    
                }
            }
            
            unset($list);
        }
        //log_message("ERROR", "Table 없음.");
        
        echo '{"success":'.$ok.', "fail":'.$err.', "phn":'.$phn.', "sms":'.$sms.', "datetime":"'.cdate('Y-m-d H:i:s').'"}';
        
    }

    public function check_pre_nanoit_agent() {
        /*
         * 월 변경시 Log 테이블 생성
         */
        if(date("d") == "01") {
            $this->create_log_table();
        }
        
        //$this->create_log_table();
        $phn = 0; $sms=0;
        $this->load->model("Biz_free_model");
        
        // 폰문자 처리
        
        $sql = "select cpm.cb_msg_id as msgid
                      ,'M103' as code
                      ,'pn' as message_type
                      ,cpm.REMARK4
                      ,cpm.max_sn
                      ,cpm.text as msg
                      ,cpm.text as msg_sms
                      ,cpl.BC_RCV_PHONE
                      ,concat('82', right(cpl.bc_rcv_phone, length(cpl.bc_rcv_phone) - 1)) as PHN
                      ,cpm.req_dttm as reg_date
                      ,cpm.MSG_ID
                      ,cpl.BC_RSLT_NO
                      ,cpl.BC_RSLT_TEXT
                      ,cpl.req_snd_dttm
                      ,cm.mem_userid
                      ,cm.mem_level
                      ,cm.mem_phn_agent
                      ,cm.mem_sms_agent
                      ,cm.mem_2nd_send
                      ,cm.mem_id
                  from cb_pms_msg_bk cpm
                 inner join CB_PMS_BROADCAST_LOG_".date("Ym", strtotime('-1 months'))." cpl
                    on cpm.msg_id = cpl.msg_id
                   and cpm.rcv_phone like concat('%', cpl.bc_rcv_phone, '%')
                 inner join cb_member cm
                    on cm.mem_id = split(cpm.cb_msg_id, '_', 1)
                 where cpm.msg_st = '1'
                   and cpl.BC_SND_ST in (3, 4)
                 order by cpm.msg_id limit 0
                         ,1000";
        //log_message("ERROR", "PRE MONTHS : ".$sql);
        $list = $this->db->query($sql)->result_array();
        
        foreach($list as $r)
        {
            $userid = $r['mem_userid'];
            $sent_key = $r['REMARK4'];
            
            if($r['BC_RSLT_NO']=="0") {
                $sql = "update cb_msg_".$userid."
                           set RESULT='Y'
                              ,MESSAGE_TYPE='ph'
                              ,MESSAGE ='폰문자 성공'
                         where remark4 ='".$sent_key."'
                           and phn = '".$r['PHN']."'";
                
                $this->db->query($sql);
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 성공 - NANOIT PMS MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
            } else if($r['BC_RSLT_NO']!="0")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                $sql = "update cb_msg_".$userid."
                           set RESULT='N'
                              ,MESSAGE_TYPE='ph'
                              ,MESSAGE ='".$r["BC_RSLT_NO"]."'
                         where remark4 ='".$sent_key."'
                           and phn = '".$r['PHN']."'";
                
                $this->db->query($sql);
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 실패 - NANOIT PMS MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $sql = "update cb_wt_msg_sent set mst_err_phn=ifnull(mst_err_phn,0)+1, mst_phn=mst_phn-1 where mst_id=?";
                $this->db->query($sql, $sent_key);
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 실패 - NANOIT PMS WT Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $grsrefund = "select * from cb_amt_".$userid. " where amt_reason='".$sent_key."_".$r['max_sn']."' and amt_kind = 'P'";
                $refundlist = $this->db->query($grsrefund)->result_array();
                foreach($refundlist as $rf)
                {
                    $data = array();
                    $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                    $data['amt_kind'] = '3';
                    $data['amt_amount'] =$rf["amt_amount"];
                    $data['amt_memo'] = '폰문자 발송실패:환불';
                    $data['amt_reason'] = $sent_key."_".$r['max_sn'];
                    
                    $data['amt_payback'] = $rf["amt_payback"] * -1;
                    $data['amt_admin'] = $rf["amt_admin"] * -1;
                    
                    $this->db->insert("cb_amt_".$userid, $data);
                    if ($this->db->error()['code'] > 0) {
                        //log_message("ERROR", "결과 실패 - NANOIT PMS 환불처리 error : ".$this->db->error()['code']);
                        continue;
                    }
                }
            }
            $sql = "update cb_pms_msg_bk set rcv_phone = replace(rcv_phone,'".$r['BC_RCV_PHONE']."','-1') where msg_id = '".$r['MSG_ID']."' and max_sn = '".$r['max_sn']."'" ;
            $this->db->query($sql );
            if ($this->db->error()['code'] > 0) {
                //log_message("ERROR", "결과 완료 - NANOIT GRS MSG Update error : ".$this->db->error()['code']);
                continue;
            }
        }
        unset($list);
        
        // GREEN_SHOT 처리
        
        $sql = "select cgm.cb_msg_id as msgid
                      ,cgm.msg_id
                      ,cgm.msg_st
                      ,cgm.max_sn
                      ,cgb.BC_RSLT_NO
                      ,(case
                         when cgb.bc_snd_st = '2' and cgb.bc_rslt_no = '0' and
                              addtime(cgb.bc_snd_dttm, '00:10:00') < now() then
                          '성공'
                         else
                          cgb.bc_rslt_text
                       end) as BC_RSLT_TEXT
                	  ,cgb.bc_rcv_phn
                      ,concat('82', right(cgb.bc_rcv_phn, length(cgb.bc_rcv_phn)-1)) as PHN
                      ,cgm.REMARK4
                      ,cm.mem_userid
                      ,cm.mem_level
                      ,cm.mem_phn_agent
                      ,cm.mem_sms_agent
                      ,cm.mem_2nd_send
                      ,cm.mem_id
                  from cb_grs_msg_bk cgm
                 inner join cb_grs_broadcast_".date("Ym", strtotime('-1 months'))." cgb
                    on cgm.msg_id = cgb.msg_id
                   and cgm.msg_rcv_phn like concat('%', cgb.bc_rcv_phn , '%')
                 inner join cb_member cm
                    on cm.mem_id = cgm.cb_msg_id
                 where cgm.msg_st in ('1', '0')
                   and cgb.bc_snd_st in( '3', '4')
                 order by cgm.msg_id limit 0
                         ,1000";
        $list = $this->db->query($sql)->result_array();
        
        foreach($list as $r)
        {
            
            $userid = $r['mem_userid'];
            $sent_key = $r['REMARK4'];
            
            if($r['BC_RSLT_NO']=="0" && $r['BC_RSLT_TEXT']=="성공") {
                
                $sql = "update cb_msg_".$userid."
                           set RESULT='Y'
                              ,MESSAGE_TYPE='gs'
                              ,MESSAGE = 'GS 성공'
                         where remark4 ='".$sent_key."'
                           and phn = '".$r['PHN']."'";
                $this->db->query($sql );
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 성공 - NANOIT GRS MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
                
            } else if($r['BC_RSLT_NO']!="0")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                
                $sql = "update cb_msg_".$userid."
                           set RESULT='N'
                              ,MESSAGE_TYPE='gs'
                              ,CODE='GRS'
                              ,MESSAGE = '".$r["BC_RSLT_NO"]."'
                         where remark4 ='".$sent_key."'
                           and phn = '".$r['PHN']."'";
                $this->db->query($sql );
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 실패 - NANOIT GRS MSG Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $sql = "update cb_wt_msg_sent set mst_err_grs=ifnull(mst_err_grs,0)+1, mst_grs=mst_grs-1  where mst_id=?";
                $this->db->query($sql, $sent_key);
                if ($this->db->error()['code'] > 0) {
                    //log_message("ERROR", "결과 실패 - NANOIT GRS WT Update error : ".$this->db->error()['code']);
                    continue;
                }
                
                $grsrefund = "select * from cb_amt_".$userid. " where amt_reason='".$sent_key."_".$r['max_sn']."' and amt_kind = 'P'";
                $refundlist = $this->db->query($grsrefund)->result_array();
                foreach($refundlist as $rf)
                {
                    $data = array();
                    $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                    $data['amt_kind'] = '3';
                    $data['amt_amount'] =$rf["amt_amount"];
                    $data['amt_memo'] = 'Green Shot 발송실패:환불';
                    $data['amt_reason'] =  $sent_key."_".$r['max_sn'];
                    
                    $data['amt_payback'] = $rf["amt_payback"] * -1;
                    $data['amt_admin'] = $rf["amt_admin"] * -1;
                    
                    $this->db->insert("cb_amt_".$userid, $data);
                    if ($this->db->error()['code'] > 0) {
                        //log_message("ERROR", "결과 실패 - NANOIT GRS 환불처리 error : ".$this->db->error()['code']);
                        continue;
                    }
                }
            }
            
            $sql = "update cb_grs_msg_bk set msg_rcv_phn = replace(msg_rcv_phn,'".$r['bc_rcv_phn']."','-1') where msg_id = '".$r['msg_id']."' and max_sn = '".$r['max_sn']."'" ;
            $this->db->query($sql );
            if ($this->db->error()['code'] > 0) {
                //log_message("ERROR", "결과 완료 - NANOIT GRS MSG Update error : ".$this->db->error()['code']);
                continue;
            }
        }
        
        
        unset($list);
        
        // FUN 문자 SMS 처리
        $mmscheck = "select 1 from cb_sms_log_".date("Ym");
        $mms_exists = $this->db->query($mmscheck);
        if($mms_exists != FALSE)
        {
            $ok = 0; $err = 0; $before_id = "";
            $sql = "SELECT  cml.TR_ETC1 AS MSGID,
                            cml.tr_num as MSGKEY,
                            cml.tr_rsltstat as RSLT,
                            cml.TR_ETC2 AS REMARK4,
                            cm.mem_userid,
                            cm.mem_level,
                            cm.mem_phn_agent,
                            cm.mem_sms_agent,
                            cm.mem_2nd_send,
                            cm.mem_id
                       FROM cb_sms_log_".date("Ym")." cml,
                            cb_member cm
                      WHERE cml.tr_etc4 = cm.mem_id
                        AND cml.tr_sendstat= '2'
                    order by tr_senddate
                        limit 0, 100
                    ";
            $list = $this->db->query($sql)->result_array();
            
            foreach($list as $r)
            {
                $userid = $r['mem_userid'];
                if(strpos($r['MSGID'], "_")!==false) {
                    
                    $ids = explode("_", $r['MSGID']);
                    
                    // 성공했으면 금액 차감, MASTER테이블에 카운터 저장
                    $sent_key = $r['REMARK4'];	// cb_wt_msg_sent 키
                    //log_message('ERROR', 'rslt text = '.$r['BC_RSLT_TEXT']);
                    if($r['RSLT']=="0") {
                        
                        $sql = "update cb_msg_".$userid." set RESULT='Y', MESSAGE_TYPE='gs', MESSAGE ='GS 성공' where MSGID=?";
                        
                        $this->db->query($sql, $r["MSGID"]);
                        unset($data);
                    } else if($r['RSLT']!="0")  {	/* Agent가 결과를 넣고 결과값을 넣는 시간사이의 갭이 있을수 있음 */
                        
                        $sql = "update cb_msg_".$userid." set RESULT='N', MESSAGE_TYPE='gs',CODE='GRS', MESSAGE = '".$r["RSLT"]."' where MSGID=?";
                        $this->db->query($sql, $r["MSGID"]);
                        
                        $sql = "update cb_wt_msg_sent set mst_err_grs_sms=ifnull(mst_err_grs_sms,0)+1, mst_grs_sms=mst_grs_sms-1  where mst_id=?";
                        $this->db->query($sql, $sent_key);
                        
                        $bkrefund = "select * from cb_amt_".$userid. " where amt_reason='".$r['MSGID']."' and amt_kind = 'P'";
                        $refundlist = $this->db->query($bkrefund)->result_array();
                        foreach($refundlist as $rf)
                        {
                            $data = array();
                            $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                            $data['amt_kind'] = '3';
                            $data['amt_amount'] =$rf["amt_amount"];
                            $data['amt_memo'] = 'Green Shot SMS 발송실패:환불';
                            $data['amt_reason'] = $r['MSGID'];
                            
                            $data['amt_payback'] = $rf["amt_payback"] * -1;
                            $data['amt_admin'] = $rf["amt_admin"] * -1;
                            
                            $this->db->insert("cb_amt_".$userid, $data);
                            if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                        }
                        
                    }
                    
                    $sql = "update cb_sms_log_".date("Ym")." set tr_sendstat = '4' where tr_num =?";
                    $this->db->query($sql,  $r['MSGKEY']);
                    
                }
            }
            
            unset($list);
        }
        //log_message("ERROR", "Table 없음.");
        
        echo '{"success":'.$ok.', "fail":'.$err.', "phn":'.$phn.', "sms":'.$sms.', "datetime":"'.cdate('Y-m-d H:i:s').'"}';
        
    }
    
    public function check_phn_agent()
    {
        /* PR컴퍼니 결과 처리 프로세스 */
        $phn = 0;
        $this->load->model("Biz_free_model");
        
        //- PR컴퍼니의 발송로그를 월별로 분리하여 처리 ------------------------------------------------------
        $this->db->query("use prcom");
        $exist = $this->db->query("show tables like 'cb_pr_tran_".date('Ym')."'")->result_array();
        if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        //- 월별 테이블이 없으면 새로 생성
        if(count($exist) < 1) {
            $sql = "
				CREATE TABLE `cb_pr_tran_".date('Ym')."` (
				  `PR_NUM` bigint(20) NOT NULL,
				  `PR_SENDDATE` datetime DEFAULT NULL,
				  `PR_ID` varchar(20) NOT NULL DEFAULT '',
				  `PR_STATUS` varchar(1) NOT NULL DEFAULT '0',
				  `PR_RSLT` varchar(4) DEFAULT '0000',
				  `PR_PHONE` varchar(20) NOT NULL,
				  `PR_CALLBACK` varchar(20) DEFAULT NULL,
				  `PR_RSLTDATE` datetime DEFAULT NULL,
				  `PR_MODIFIED` datetime DEFAULT NULL,
				  `PR_MSG` varchar(2000) DEFAULT NULL,
				  `PR_SUBJECT` varchar(160) DEFAULT NULL,
				  `PR_REALSENDDATE` datetime DEFAULT NULL,
				  `PR_FINISHDATE` datetime DEFAULT NULL,
				  `PR_ETC1` varchar(160) DEFAULT NULL,
				  `PR_ETC2` varchar(160) DEFAULT NULL,
				  `PR_ETC3` varchar(160) DEFAULT NULL,
				  `PR_ETC4` varchar(160) DEFAULT NULL,
				  `PR_ETC5` varchar(160) DEFAULT NULL,
				  `mem_userid` varchar(50) DEFAULT '',
				  PRIMARY KEY (`PR_ID`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
            $this->db->query($sql);
            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        }
        $this->db->query("use dhn");
        if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        //---------------------------------------------------------------------------------------------------
        
        // 기본 단가 수집
        $base_price = $this->Biz_free_model->getBasePrice();
        $adm_price = $this->Biz_free_model->getMemberPrice(3);	//- 관리자단가
        
        // 메시지 전송 결과 처리
        $ok = 0; $err = 0; $before_id = "";
        $sql = "select a.*, b.mem_userid, b.mem_level, b.mem_phn_agent, b.mem_sms_agent from cb_pr_tran a inner join cb_member b on SPLIT(a.PR_ID, '_', 1)=b.mem_id where PR_STATUS='2' and PR_RSLT<>'0000'";
        $list = $this->db->query($sql)->result_array();
        
        foreach($list as $r)
        {
            $userid = $r['mem_userid'];
            if(strpos($r['PR_ID'], "_")!==false) {
                $mem_lv = $r['mem_level'];
                $mem_phn = $r['mem_phn_agent'];
                $mem_sms = $r['mem_sms_agent'];
                unset($r['mem_level']);
                unset($r['mem_phn_agent']);
                unset($r['mem_sms_agent']);
                
                $ids = explode("_", $r['PR_ID']);
                if($before_id=="" || $before_id != $ids[0]) {
                    // 사용자별 단가 수집 : id가 변하는 경우만 처리
                    $price = $this->Biz_free_model->getParentPrice($ids[0]);	//$this->db->query($sql, array($ids[0]))->row_array();
                    if($price['mad_mem_id']!=$ids[0]) {
                        $price = $base_price;
                    }
                    $before_id = $ids[0];
                }
                
                //- 발송결과를 발신연월로 구분하여 다른테이블에 저장함
                $this->db->insert("prcom.cb_pr_tran_".str_replace("-", "", substr($r['PR_SENDDATE'], 0, 7)), $r);
                if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error());continue; }
                $this->db->query("delete from cb_pr_tran where PR_NUM=?", array($r['PR_NUM']));
                if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error());continue; }
                // 발송 성공인 경우 Skip
                if ($r['PR_RSLT']=="06") { continue; }
                
                // 발송 실패인 경우 환불
                $rMsg = $this->db->query("select MSGID, REMARK4 from cb_msg_".$r['mem_userid']." where MSGID=?", array($r["PR_ID"]))->row();	// cb_wt_msg_sent 키
                if(!$rMsg || $rMsg->MSGID!=$r["PR_ID"]) { continue; }	//- 발송테이블에서 찾지못하면 skip
                // 발송테이블의 상태를 실패로 변경
                $this->db->query("update cb_msg_".$r['mem_userid']." set RESULT='N', CODE='M103' where MSGID=?", array($r["PR_ID"]));
                if ($this->db->error()['code'] > 0) { continue; }
                // 발송 마스터 테이블 건수 변경
                $sent_key = $rMsg->REMARK4;	//- 발송마스터 키(cb_wt_msg_sent)
                $amt = $price['mad_price_phn'];
                $sql = "update cb_wt_msg_sent set mst_phn =ifnull(mst_phn,0)-1, mst_err_phn =ifnull(mst_err_phn,0)+1 where mst_id=?";
                $this->db->query($sql, $sent_key);
                // 환불정보 입력
                $data = array();
                $data['amt_datetime'] = cdate('Y-m-d H:i:s');
                $data['amt_kind'] = ($this->refund_agent[$mem_phn]) ? $this->refund_agent[$mem_phn] : "3";	//- 환불
                $data['amt_amount'] = $amt;
                $data['amt_memo'] = "환불:발송실패(폰문자)";
                $data['amt_reason'] = $rMsg->MSGID;
                /* 상위자와의 단가차이를 적용 */
                $data['amt_payback'] = ($mem_lv>=50) ? 0 : ($price['payback_phn'] * -1);	//- 페이백 차감
                $data['amt_admin'] = ($adm_price['phn'.$this->gAgent[$mem_phn]] * -1);
                
                $this->db->insert("cb_amt_".$userid, $data);
                if ($this->db->error()['code'] < 1) { $ok++; } else { $err++; }
                unset($data);
            }
        }
        echo '{"success":'.$ok.', "fail":'.$err.', "datetime":"'.cdate('Y-m-d H:i:s').'"}';
    }
    
    public function get_stat()
    {
        
        $this->load->model("Biz_model");
        /*$stats_weekly = array();
        $stats_today = array();
        */
        $tmpl_info = array();
        /*
        $tmpl_info["apr_cnt"] = $this->Biz_model->get_table_count("cb_wt_template", "tpl_mem_id=? and tpl_inspect_status='APR'", array($this->member->item('mem_id')));
        $tmpl_info["req_cnt"] = $this->Biz_model->get_table_count("cb_wt_template", "tpl_mem_id=? and tpl_inspect_status='REQ'", array($this->member->item('mem_id')));
        $tmpl_info["rej_cnt"] = $this->Biz_model->get_table_count("cb_wt_template", "tpl_mem_id=? and tpl_inspect_status='REJ'", array($this->member->item('mem_id')));
        */
        $tmpl_info["msg_cnt"] = $this->Biz_model->get_table_count("cb_message_receiver", "to_mem_id=? and is_alam = 'Y'", array($this->member->item('mem_id')));
        /*
        $stats_today["cnt_succ_kakao_at"] = $this->Biz_model->get_table_count("cb_msg_".$this->member->item('mem_userid'), "MESSAGE_TYPE='at' and RESULT='Y' and REG_DT between ? and ?", array(date('Y-m-d')." 00:00:00", date('Y-m-d H:i:s')));
        $stats_today["cnt_succ_kakao_ft"] = $this->Biz_model->get_table_count("cb_msg_".$this->member->item('mem_userid'), "MESSAGE_TYPE='ft' and RESULT='Y' and REG_DT between ? and ?", array(date('Y-m-d')." 00:00:00", date('Y-m-d H:i:s')));
        $stats_today["cnt_succ_phn"] = $this->Biz_model->get_table_count("cb_msg_".$this->member->item('mem_userid'), "MESSAGE_TYPE='pn' and RESULT='Y' and REG_DT between ? and ?", array(date('Y-m-d')." 00:00:00", date('Y-m-d H:i:s')));
        
        for($n=7;$n>=0;$n--) {
            $day = ($n==0) ? date('Y-m-d') : date('Y-m-d', strtotime(date('Y-m-d')."-".$n." day"));
            $sdate = $day." 00:00:00";
            $edate = $day." 23:59:59";
            
            $daysum = array();
            $daysum['cnt_fail_total'] = $this->Biz_model->get_table_count("cb_msg_".$this->member->item('mem_userid'), "RESULT<>'Y' and REG_DT between ? and ?", array($sdate, $edate));
            $daysum['cnt_succ_total'] = $this->Biz_model->get_table_count("cb_msg_".$this->member->item('mem_userid'), "RESULT='Y' and REG_DT between ? and ?", array($sdate, $edate));
            $daysum['time'] = $day;
            array_push($stats_weekly, $daysum);
            unset($daysum);
        }
        */
        $this->session->set_userdata('isNotice', 'N');
        
        header("Content-Type: application/json");
        echo json_encode(array("tmpl_info"=>array($tmpl_info)));
        
    }
    
    public function create_log_table() {
        
        $exist = $this->db->query("show tables like 'CB_PMS_BROADCAST_LOG_".date("Ym")."'")->result_array();
        if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        //- 월별 테이블이 없으면 새로 생성
        if(count($exist) < 1) {
            
            
            $sql = "CREATE TABLE IF NOT EXISTS `CB_PMS_BROADCAST_LOG_".date("Ym")."` (
								`MSG_ID` INT(11) NULL DEFAULT NULL,
								`MSG_WRT_CNT` INT(11) NULL DEFAULT NULL,
								`BC_MSG_ID` BIGINT(20) UNSIGNED NOT NULL,
								`BC_RCV_PHONE` VARCHAR(20) NULL DEFAULT NULL,
								`BC_SND_ST` INT(11) NULL DEFAULT NULL,
								`BC_RSLT_NO` VARCHAR(5) NULL DEFAULT NULL,
								`BC_RSLT_TEXT` VARCHAR(100) NULL DEFAULT NULL,
								`REQ_SND_DTTM` DATETIME NULL DEFAULT NULL,
								`REQ_RCV_DTTM` DATETIME NULL DEFAULT NULL,
								PRIMARY KEY (`BC_MSG_ID`),
                                INDEX `idx_CB_PMS_BROADCAST_".date("Ym")."_MSG_ID` (`MSG_ID`)
							)
							COLLATE='utf8_general_ci'
							ENGINE=InnoDB
					";
            
            $this->db->query($sql);
            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        }
        
        $exist = $this->db->query("show tables like 'cb_grs_broadcast_".date("Ym")."'")->result_array();
        if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        //- 월별 테이블이 없으면 새로 생성
        if(count($exist) < 1) {
            
            $sql = "CREATE TABLE IF NOT EXISTS `cb_grs_broadcast_".date("Ym")."` (
								`MSG_ID` INT(11) NOT NULL,
								`MSG_GB` VARCHAR(5) NOT NULL,
								`BC_MSG_ID` BIGINT(20) UNSIGNED NOT NULL,
								`BC_SND_ST` CHAR(1) NULL DEFAULT NULL,
								`BC_SND_PHN` VARCHAR(20) NOT NULL,
								`BC_RCV_PHN` VARCHAR(20) NOT NULL,
								`BC_RSLT_NO` VARCHAR(5) NULL DEFAULT NULL,
								`BC_RSLT_TEXT` VARCHAR(255) NULL DEFAULT NULL,
								`BC_SND_DTTM` DATETIME NULL DEFAULT NULL,
								`BC_RCV_DTTM` DATETIME NULL DEFAULT NULL,
								PRIMARY KEY (`BC_MSG_ID`),
                                INDEX `idx_cb_grs_broadcast_".date("Ym")."_MSG_ID` (`MSG_ID`)
							)
							COLLATE='utf8_general_ci'
							ENGINE=InnoDB
					";
            $this->db->query($sql);
            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        }
        
        $exist = $this->db->query("show tables like 'cb_neo_log_".date("Ym")."'")->result_array();
        if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        //- 월별 테이블이 없으면 새로 생성
        if(count($exist) < 1) {
            $sql = "CREATE TABLE IF NOT EXISTS `cb_neo_log_".date("Ym")."` (
								`MSGKEY` INT(11) NOT NULL,
								`SUBJECT` VARCHAR(120) NULL DEFAULT NULL,
								`PHONE` VARCHAR(15) NOT NULL,
								`CALLBACK` VARCHAR(15) NULL DEFAULT NULL,
								`STATUS` VARCHAR(2) NOT NULL DEFAULT '0',
								`WRTDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
								`REQDATE` DATETIME NOT NULL,
								`MSG` TEXT NULL,
								`FILE_CNT` INT(11) NOT NULL DEFAULT '0',
								`FILE_CNT_REAL` INT(11) NOT NULL DEFAULT '0',
								`FILE_PATH1` TEXT NULL,
								`FILE_PATH1_SIZ` INT(11) NULL DEFAULT NULL,
								`FILE_PATH2` TEXT NULL,
								`FILE_PATH2_SIZ` INT(11) NULL DEFAULT NULL,
								`FILE_PATH3` TEXT NULL,
								`FILE_PATH3_SIZ` INT(11) NULL DEFAULT NULL,
								`FILE_PATH4` TEXT NULL,
								`FILE_PATH4_SIZ` INT(11) NULL DEFAULT NULL,
								`FILE_PATH5` TEXT NULL,
								`FILE_PATH5_SIZ` INT(11) NULL DEFAULT NULL,
								`SENTDATE` DATETIME NULL DEFAULT NULL,
								`RSLTDATE` DATETIME NULL DEFAULT NULL,
								`REPORTDATE` DATETIME NULL DEFAULT NULL,
								`TERMINATEDDATE` DATETIME NULL DEFAULT NULL,
								`RSLT` VARCHAR(4) NULL DEFAULT NULL,
								`TYPE` VARCHAR(2) NOT NULL DEFAULT '0',
								`TELCOINFO` VARCHAR(10) NULL DEFAULT NULL,
								`ID` VARCHAR(20) NULL DEFAULT NULL,
								`ETC1` VARCHAR(64) NULL DEFAULT NULL,
								`ETC2` VARCHAR(32) NULL DEFAULT NULL,
								`ETC3` VARCHAR(16) NULL DEFAULT NULL,
								`ETC4` INT(11) NULL DEFAULT NULL,
								INDEX `cb_neo_log_".date("Ym")."_IDX1` (`REQDATE`),
								INDEX `cb_neo_log_".date("Ym")."_IDX2` (`REPORTDATE`),
								INDEX `cb_neo_log_".date("Ym")."_IDX4` (`SENTDATE`),
								INDEX `cb_neo_log_".date("Ym")."_IDX5` (`TERMINATEDDATE`),
								INDEX `cb_neo_log_".date("Ym")."_IDX6` (`MSGKEY`)
							)
							COLLATE='utf8_general_ci'
							ENGINE=InnoDB
					";
            $this->db->query($sql);
            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        }
        
        $exist = $this->db->query("show tables like 'cb_sms_log_".date("Ym")."'")->result_array();
        if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        //- 월별 테이블이 없으면 새로 생성
        if(count($exist) < 1) {
            $sql = "CREATE TABLE IF NOT EXISTS `cb_sms_log_".date("Ym")."` (
								`TR_NUM` BIGINT(20) NOT NULL,
								`TR_SENDDATE` DATETIME NULL DEFAULT NULL,
								`TR_SERIALNUM` INT(10) NULL DEFAULT NULL,
								`TR_ID` VARCHAR(16) NULL DEFAULT NULL,
								`TR_SENDSTAT` VARCHAR(1) NOT NULL DEFAULT '0',
								`TR_RSLTSTAT` VARCHAR(10) NULL DEFAULT '00',
								`TR_MSGTYPE` VARCHAR(1) NOT NULL DEFAULT '0',
								`TR_PHONE` VARCHAR(20) NOT NULL DEFAULT '',
								`TR_CALLBACK` VARCHAR(20) NULL DEFAULT NULL,
								`TR_ORG_CALLBACK` VARCHAR(20) NULL DEFAULT '',
								`TR_BILL_ID` VARCHAR(20) NULL DEFAULT '',
								`TR_RSLTDATE` DATETIME NULL DEFAULT NULL,
								`TR_MODIFIED` DATETIME NULL DEFAULT NULL,
								`TR_MSG` VARCHAR(160) NULL DEFAULT NULL,
								`TR_NET` VARCHAR(4) NULL DEFAULT NULL,
								`TR_ETC1` VARCHAR(34) NULL DEFAULT NULL,
								`TR_ETC2` VARCHAR(34) NULL DEFAULT NULL,
								`TR_ETC3` VARCHAR(34) NULL DEFAULT NULL,
								`TR_ETC4` VARCHAR(34) NULL DEFAULT NULL,
								`TR_ETC5` VARCHAR(34) NULL DEFAULT NULL,
								`TR_ETC6` VARCHAR(34) NULL DEFAULT NULL,
								`TR_ETC7` VARCHAR(34) NULL DEFAULT NULL,
								`TR_ETC8` VARCHAR(34) NULL DEFAULT NULL,
								`TR_ETC9` VARCHAR(34) NULL DEFAULT NULL,
								`TR_ETC10` VARCHAR(34) NULL DEFAULT NULL,
								`TR_REALSENDDATE` DATETIME NULL DEFAULT NULL,
								INDEX `TR_SENDDATE_".date("Ym")."` (`TR_SENDDATE`),
								INDEX `TR_SENDSTAT_".date("Ym")."` (`TR_SENDSTAT`),
								INDEX `TR_PHONE` (`TR_PHONE`),
								INDEX `cb_sms_log_".date("Ym")."_IDX1` (`TR_SENDDATE`),
								INDEX `cb_sms_log_".date("Ym")."_IDX2` (`TR_SENDSTAT`),
								INDEX `cb_sms_log_".date("Ym")."_IDX3` (`TR_PHONE`)
							)
							COLLATE='utf8_general_ci'
							ENGINE=InnoDB
					";
            
            $this->db->query($sql);
            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        }
        
        $exist = $this->db->query("show tables like 'cb_mms_log_".date("Ym")."'")->result_array();
        if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        //- 월별 테이블이 없으면 새로 생성
        if(count($exist) < 1) {
            $sql = "CREATE TABLE `cb_mms_log_".date("Ym")."` (
                                `MSGKEY` INT(11) NOT NULL,
                                `SUBJECT` VARCHAR(120) NULL DEFAULT NULL,
                                `PHONE` VARCHAR(15) NOT NULL,
                                `CALLBACK` VARCHAR(15) NOT NULL,
                                `ORG_CALLBACK` VARCHAR(20) NULL DEFAULT '',
                                `BILL_ID` VARCHAR(20) NULL DEFAULT '',
                                `STATUS` VARCHAR(2) NOT NULL DEFAULT '0',
                                `REQDATE` DATETIME NOT NULL,
                                `MSG` VARCHAR(4000) NULL DEFAULT NULL,
                                `FILE_CNT` INT(10) NULL DEFAULT '0',
                                `FILE_CNT_REAL` INT(10) NULL DEFAULT '0',
                                `FILE_PATH1` VARCHAR(128) NULL DEFAULT NULL,
                                `FILE_PATH1_SIZ` INT(10) NULL DEFAULT NULL,
                                `FILE_PATH2` VARCHAR(128) NULL DEFAULT NULL,
                                `FILE_PATH2_SIZ` INT(10) NULL DEFAULT NULL,
                                `FILE_PATH3` VARCHAR(128) NULL DEFAULT NULL,
                                `FILE_PATH3_SIZ` INT(10) NULL DEFAULT NULL,
                                `FILE_PATH4` VARCHAR(128) NULL DEFAULT NULL,
                                `FILE_PATH4_SIZ` INT(10) NULL DEFAULT NULL,
                                `FILE_PATH5` VARCHAR(128) NULL DEFAULT NULL,
                                `FILE_PATH5_SIZ` INT(10) NULL DEFAULT NULL,
                                `EXPIRETIME` VARCHAR(10) NULL DEFAULT NULL,
                                `SENTDATE` DATETIME NULL DEFAULT NULL,
                                `RSLTDATE` DATETIME NULL DEFAULT NULL,
                                `REPORTDATE` DATETIME NULL DEFAULT NULL,
                                `TERMINATEDDATE` DATETIME NULL DEFAULT NULL,
                                `RSLT` VARCHAR(10) NULL DEFAULT NULL,
                                `REPCNT` INT(10) NULL DEFAULT NULL,
                                `TYPE` VARCHAR(2) NOT NULL,
                                `TELCOINFO` VARCHAR(12) NULL DEFAULT NULL,
                                `ID` VARCHAR(22) NULL DEFAULT NULL,
                                `POST` VARCHAR(22) NULL DEFAULT NULL,
                                `ETC1` VARCHAR(68) NULL DEFAULT NULL,
                                `ETC2` VARCHAR(34) NULL DEFAULT NULL,
                                `ETC3` VARCHAR(34) NULL DEFAULT NULL,
                                `ETC4` INT(10) NULL DEFAULT NULL,
                                `ETC5` VARCHAR(34) NULL DEFAULT NULL,
                                `ETC6` VARCHAR(34) NULL DEFAULT NULL,
                                `ETC7` VARCHAR(34) NULL DEFAULT NULL,
                                `ETC8` VARCHAR(34) NULL DEFAULT NULL,
                                `ETC9` VARCHAR(34) NULL DEFAULT NULL,
                                `ETC10` VARCHAR(34) NULL DEFAULT NULL,
                                INDEX `cb_mms_log_".date("Ym")."_IDX1` (`REQDATE`),
                                INDEX `cb_mms_log_".date("Ym")."_IDX2` (`REPORTDATE`),
                                INDEX `cb_mms_log_".date("Ym")."_IDX3` (`EXPIRETIME`),
                                INDEX `cb_mms_log_".date("Ym")."_IDX4` (`SENTDATE`),
                                INDEX `cb_mms_log_".date("Ym")."_IDX5` (`TERMINATEDDATE`)
                                )
                                COLLATE='utf8_general_ci'
                                ENGINE=InnoDB
					";
            
            $this->db->query($sql);
            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        }

        $exist = $this->db->query("show tables like 'cb_nas_mms_msg_log_".date("Ym")."'")->result_array();
        if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        //- 월별 테이블이 없으면 새로 생성
        if(count($exist) < 1) {
            $sql = "CREATE TABLE `cb_nas_mms_msg_log_".date("Ym")."` (
            `MSGKEY` INT(11) NOT NULL,
            `SUBJECT` VARCHAR(120) NULL DEFAULT NULL,
            `PHONE` VARCHAR(15) NOT NULL,
            `CALLBACK` VARCHAR(15) NOT NULL,
            `ORG_CALLBACK` VARCHAR(20) NULL DEFAULT '',
            `BILL_ID` VARCHAR(20) NULL DEFAULT '',
            `STATUS` VARCHAR(2) NOT NULL DEFAULT '0',
            `REQDATE` DATETIME NOT NULL,
            `MSG` VARCHAR(4000) NULL DEFAULT NULL,
            `FILE_CNT` INT(10) NULL DEFAULT '0',
            `FILE_CNT_REAL` INT(10) NULL DEFAULT '0',
            `FILE_PATH1` VARCHAR(128) NULL DEFAULT NULL,
            `FILE_PATH1_SIZ` INT(10) NULL DEFAULT NULL,
            `FILE_PATH2` VARCHAR(128) NULL DEFAULT NULL,
            `FILE_PATH2_SIZ` INT(10) NULL DEFAULT NULL,
            `FILE_PATH3` VARCHAR(128) NULL DEFAULT NULL,
            `FILE_PATH3_SIZ` INT(10) NULL DEFAULT NULL,
            `FILE_PATH4` VARCHAR(128) NULL DEFAULT NULL,
            `FILE_PATH4_SIZ` INT(10) NULL DEFAULT NULL,
            `FILE_PATH5` VARCHAR(128) NULL DEFAULT NULL,
            `FILE_PATH5_SIZ` INT(10) NULL DEFAULT NULL,
            `EXPIRETIME` VARCHAR(10) NULL DEFAULT NULL,
            `SENTDATE` DATETIME NULL DEFAULT NULL,
            `RSLTDATE` DATETIME NULL DEFAULT NULL,
            `REPORTDATE` DATETIME NULL DEFAULT NULL,
            `TERMINATEDDATE` DATETIME NULL DEFAULT NULL,
            `RSLT` VARCHAR(10) NULL DEFAULT NULL,
            `REPCNT` INT(10) NULL DEFAULT NULL,
            `TYPE` VARCHAR(2) NOT NULL,
            `TELCOINFO` VARCHAR(12) NULL DEFAULT NULL,
            `ID` VARCHAR(22) NULL DEFAULT NULL,
            `POST` VARCHAR(22) NULL DEFAULT NULL,
            `ETC1` VARCHAR(68) NULL DEFAULT NULL,
            `ETC2` VARCHAR(34) NULL DEFAULT NULL,
            `ETC3` VARCHAR(34) NULL DEFAULT NULL,
            `ETC4` INT(10) NULL DEFAULT NULL,
            `ETC5` VARCHAR(34) NULL DEFAULT NULL,
            `ETC6` VARCHAR(34) NULL DEFAULT NULL,
            `ETC7` VARCHAR(34) NULL DEFAULT NULL,
            `ETC8` VARCHAR(34) NULL DEFAULT NULL,
            `ETC9` VARCHAR(34) NULL DEFAULT NULL,
            `ETC10` VARCHAR(34) NULL DEFAULT NULL,
            INDEX `cb_nas_mms_msg_log_".date("Ym")."_IDX1` (`REQDATE`),
            INDEX `cb_nas_mms_msg_log_".date("Ym")."_IDX2` (`REPORTDATE`),
            INDEX `cb_nas_mms_msg_log_".date("Ym")."_IDX3` (`EXPIRETIME`),
            INDEX `cb_nas_mms_msg_log_".date("Ym")."_IDX4` (`SENTDATE`),
            INDEX `cb_nas_mms_msg_log_".date("Ym")."_IDX5` (`TERMINATEDDATE`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
					";
            
            $this->db->query($sql);
            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        }
        
        $exist = $this->db->query("show tables like 'cb_nas_sms_msg_log_".date("Ym")."'")->result_array();
        if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        //- 월별 테이블이 없으면 새로 생성
        if(count($exist) < 1) {
            $sql = "CREATE TABLE `cb_nas_sms_msg_log_".date("Ym")."` (
	`TR_NUM` BIGINT(20) NOT NULL,
	`TR_SENDDATE` DATETIME NULL DEFAULT NULL,
	`TR_SERIALNUM` INT(10) NULL DEFAULT NULL,
	`TR_ID` VARCHAR(16) NULL DEFAULT NULL,
	`TR_SENDSTAT` VARCHAR(1) NOT NULL DEFAULT '0',
	`TR_RSLTSTAT` VARCHAR(10) NULL DEFAULT '00',
	`TR_MSGTYPE` VARCHAR(1) NOT NULL DEFAULT '0',
	`TR_PHONE` VARCHAR(20) NOT NULL DEFAULT '',
	`TR_CALLBACK` VARCHAR(20) NULL DEFAULT NULL,
	`TR_ORG_CALLBACK` VARCHAR(20) NULL DEFAULT '',
	`TR_BILL_ID` VARCHAR(20) NULL DEFAULT '',
	`TR_RSLTDATE` DATETIME NULL DEFAULT NULL,
	`TR_MODIFIED` DATETIME NULL DEFAULT NULL,
	`TR_MSG` VARCHAR(160) NULL DEFAULT NULL,
	`TR_NET` VARCHAR(4) NULL DEFAULT NULL,
	`TR_ETC1` VARCHAR(34) NULL DEFAULT NULL,
	`TR_ETC2` VARCHAR(34) NULL DEFAULT NULL,
	`TR_ETC3` VARCHAR(34) NULL DEFAULT NULL,
	`TR_ETC4` VARCHAR(34) NULL DEFAULT NULL,
	`TR_ETC5` VARCHAR(34) NULL DEFAULT NULL,
	`TR_ETC6` VARCHAR(34) NULL DEFAULT NULL,
	`TR_ETC7` VARCHAR(34) NULL DEFAULT NULL,
	`TR_ETC8` VARCHAR(34) NULL DEFAULT NULL,
	`TR_ETC9` VARCHAR(34) NULL DEFAULT NULL,
	`TR_ETC10` VARCHAR(34) NULL DEFAULT NULL,
	`TR_REALSENDDATE` DATETIME NULL DEFAULT NULL,
	INDEX `TR_SENDDATE` (`TR_SENDDATE`),
	INDEX `TR_SENDSTAT` (`TR_SENDSTAT`),
	INDEX `TR_PHONE` (`TR_PHONE`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
					";
            
            $this->db->query($sql);
            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        }
    }
}
?>
