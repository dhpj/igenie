<?php
class Lms extends CB_Controller {
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
        log_message("ERROR"," INDEX MSG Save List");
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
    
    public function load_customer()
    {
        $filter = $this->input->post('filter');
        $where = ""; $param = array();
        // 2019.01.17 이수환 고객없음 value값을 NONE처리로 변경
        //if($filter) { $where = " and ab_kind like ? "; array_push($param, "%".$filter."%"); }
        if($filter == 'NONE') { 
            $where = " and ifnull(ab_kind, '') = ? "; array_push($param, ""); 
        } else if ($filter != "") {
            // 2019.01.18 이수환 차후 고객구분 멀티 선택시 처리를 위해 변경(LIKE => IN)
            //$where = " and ifnull(ab_kind, '') like ? "; array_push($param, "%".$filter."%"); 
            $where = " and ifnull(ab_kind, '') in (?) "; array_push($param, $filter); 
        } 
        $cnt = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'".$where, $param);
        
        log_message("ERROR", "WHERE : ".$where);
        header('Content-Type: application/json');
        echo '{"customer_count":'.$cnt.'}';
        exit;
    }
    
    public function all()
    {
        $customer_all_count = $this->input->post('customer_all_count');
        $customer_filter = $this->input->post('customer_filter');
        $upload_count = $this->input->post('upload_count');
        $templateCode = $this->input->post('templateCode');
        $pf_key = $this->input->post('pf_key');
        $senderBox = $this->input->post('senderBox');
        $reserveDt = $this->input->post('reserveDt');
        $smslms_kind = $this->input->post('smslms_kind');
        $mem_2nd_send = $this->input->post('mem_2nd_send');
        $kind = substr($smslms_kind, 0,1);
        $mms_id = $this->input->post('mms_id');
        $fail_mst_id = $this->input->post('fail_mst_id');
        
        $templi_cont = $this->input->post('templi_cont');
        $lms_msg = $this->input->post('msg');
        $tel_number = $this->input->post('tel_number');
        
        if(!$this->is_valid_callback($senderBox)) {
            header('Content-Type: application/json');
            echo '{"message": "발신번호 사전등록 오류", "code": "fail"}';
            return;
        }
        
        if(!empty($mms_id)) {
            $sql = "select *
                      from cb_mms_images
                     where mem_id = '".$this->member->item("mem_id")."'
                       and mms_id = '".$mms_id."'";
            
            $mv_img = $this->db->query($sql)->row();
            
            copy($mv_img->origin1_path, "/root/neoagent/mmsfiles/".$mv_img->file1_path);
            copy($mv_img->origin2_path, "/root/neoagent/mmsfiles/".$mv_img->file2_path);
            copy($mv_img->origin3_path, "/root/neoagent/mmsfiles/".$mv_img->file3_path);
            
        }
        
        //log_message("ERROR", "문자 param : ".$reserveDt.",".$mem_2nd_send.",".$smslms_kind);
        if($upload_count) {
            /* 전체 발송인 경우 */
            if($_FILES) {
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
                /* 업로드된 엑셀을 사용하여 발송하는 경우 */
                $cache = $this->db->query("select * from cb_wt_send_cache where sc_mem_id=? limit 1", array($this->member->item('mem_id')))->row();
                
                $data = array();
                $data['mst_mem_id'] = $this->member->item('mem_id');
                $data['mst_template'] = '';
                $data['mst_profile'] = $pf_key;
                $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
                $data['mst_kind'] =  "MS";
                $data['mst_content'] =  $this->input->post('msg');
                $data['mst_sms_send'] =  ($kind=="S") ? $kind : "";
                $data['mst_lms_send'] =  ($kind=="L" || $kind=="P") ? $kind : "";
                $data['mst_mms_send'] =  ($kind=="M") ? $kind : "";
                $data['mst_sms_content'] =  '';
                $data['mst_lms_content'] =  $this->input->post('msg');
                $data['mst_mms_content'] =  $mms_id;
                $data['mst_img_content'] =  $this->input->post('img_url');
                $data['mst_button'] = json_encode(array($btn1, $btn2, $btn3, $btn4, $btn5), JSON_UNESCAPED_UNICODE);
                $data['mst_reserved_dt'] =  $this->input->post('reserveDt');
                $data['mst_sms_callback'] =  $senderBox;
                $data['mst_status'] = '1';
                $data['mst_qty'] =  ($upload_count > $sendCount) ? $sendCount : $upload_count;
                $data['mst_amount'] = 0;	// ($cache->sc_img_url) ? ($this->Biz_model->price_ft_img * $upload_count) : ($this->Biz_model->price_ft * $upload_count);
                
                //echo '<pre> :: ';print_r($data);
                //exit;
                
                $this->db->insert("cb_wt_msg_sent", $data);
                $gid = $this->db->insert_id();
                
                $this->load->library('message');
                $ok = $this->message->NewUploadToSendCache('MT');
                if($ok > 0) { $ok = $this->message->sendAgentSendCache($kind, $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $mem_2nd_send, $this->input->post('reserveDt'), $sendCount); }
                
                header('Content-Type: application/json');
                echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
                return;
            }
        }
        
        $templi_cont = $this->input->post('templi_cont');
        $lms_msg = $this->input->post('msg');
        $img_url = $this->input->post('img_url');
        $btn1 = $this->input->post('btn1');
        $btn2 = $this->input->post('btn2');
        $btn3 = $this->input->post('btn3');
        $btn4 = $this->input->post('btn4');
        $btn5 = $this->input->post('btn5');
        $tel_number = $this->input->post('tel_number');
        
        $this->load->library('message');
        //- 먼저 발송캐시에 등록함
        
        if($fail_mst_id ) {
            
            $ok = $this->message->FailmsgToSendCache("ft", $fail_mst_id);
            $mms_id = $this->db->query("select mst_mms_content from cb_wt_msg_sent where mst_id = '".$fail_mst_id."'")->row()->mst_mms_conent;
            log_message("ERROR", "AA Fail MST ID : ".$ok);
        } else if($customer_all_count) {
            $ok = $this->message->phoneBookToSendCache("ft", $customer_filter);
        } else {
            $ok = $this->message->sendAgent("ft");
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
        $data['mst_kind'] =  "MS";
        $data['mst_content'] =  $this->input->post('msg');
        $data['mst_sms_send'] =  ($kind=="S") ? $kind : "";
        $data['mst_lms_send'] =  ($kind=="L" || $kind=="P") ? $kind : "";
        $data['mst_mms_send'] =  ($kind=="M") ? $kind : "";
        $data['mst_sms_content'] =  '';
        $data['mst_lms_content'] =  $this->input->post('msg');
        $data['mst_mms_content'] =  $mms_id;
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
        
        if($ok > 0) { $ok = $this->message->sendAgentSendCache($kind, $gid, $this->member->item('mem_id'), $this->member->item('mem_userid'), $mem_2nd_send, $this->input->post('reserveDt'), $sendCount); }
        
        header('Content-Type: application/json');
        echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'", "customer_count":"'.$ok.'"}';
        
    }
    
    public function mms_upload() {
        $this->load->library('upload');
        
        $upload_path = config_item('uploads_dir') . '/mms_images/';
        if (is_dir($upload_path) === false) {
            mkdir($upload_path, 0707);
            $file = $upload_path . 'index.php';
            $f = @fopen($file, 'w');
            @fwrite($f, '');
            @fclose($f);
            @chmod($file, 0644);
        }
        $upload_path .= cdate('Y') . '/';
        if (is_dir($upload_path) === false) {
            mkdir($upload_path, 0707);
            $file = $upload_path . 'index.php';
            $f = @fopen($file, 'w');
            @fwrite($f, '');
            @fclose($f);
            @chmod($file, 0644);
        }
        $upload_path .= cdate('m') . '/';
        if (is_dir($upload_path) === false) {
            mkdir($upload_path, 0707);
            $file = $upload_path . 'index.php';
            $f = @fopen($file, 'w');
            @fwrite($f, '');
            @fclose($f);
            @chmod($file, 0644);
        }
        
        $mms_id = $this->input->post('mms_id');
        if(empty($mms_id)) {
            $mms_id = cdate('YmdHis');
            $mms_images = array();
            $mms_images['mem_id'] = $this->member->item("mem_id");
            $mms_images['mms_id'] = $mms_id;
            $this->db->insert("mms_images", $mms_images);
        }
        
        $files = array('fileInput1', 'fileInput2', 'fileInput3');
        $err_cnt = 0 ;
        $imgurl = "";
        for ($i = 0; $i < 3; $i++) {
            if (isset($_FILES) && isset($_FILES[$files[$i]]) && isset($_FILES[$files[$i]]['name']) && isset($_FILES[$files[$i]]['name'])) {
                
                $sql = "select * from cb_mms_images where mem_id = '".$this->member->item("mem_id")."' and mms_id='".$mms_id."'";
                $result = $this->db->query($sql)->row();
                if ($result) {
                    $file_path = "";
                    if($i == 0) {
                        $file_path = $result->origin1_path;
                    }
                    if($i == 1) {
                        $file_path = $result->origin2_path;
                    }
                    if($i == 2) {
                        $file_path = $result->origin3_path;
                    }
                    
                    if($file_path)
                        unlink($file_path);
                }
                
                $uploadconfig = '';
                $uploadconfig['upload_path'] = $upload_path;
                $uploadconfig['allowed_types'] = element('upload_file_extension', $board) ? element('upload_file_extension', $board) : '*';
                $uploadconfig['max_size'] = element('upload_file_max_size', $board) * 1024;
                $uploadconfig['encrypt_name'] = true;
                
                $this->upload->initialize($uploadconfig);
                $_FILES['userfile']['name'] = $_FILES[$files[$i]]['name'];
                $_FILES['userfile']['type'] = $_FILES[$files[$i]]['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES[$files[$i]]['tmp_name'];
                $_FILES['userfile']['error'] = $_FILES[$files[$i]]['error'];
                $_FILES['userfile']['size'] = $_FILES[$files[$i]]['size'];
                
                if ($this->upload->do_upload()) {
                    
                    $this->load->library('image_lib');
                    $this->image_lib->clear();
                    $imgconfig = "";
                    $filedata = $this->upload->data();
                    $imgconfig['image_library'] = 'gd2';
                    $imgconfig['source_image']	= $filedata['full_path'];
                    $imgconfig['maintain_ratio'] = TRUE;
                    $imgconfig['width']	= 320;
                    $imgconfig['height']= 320;
                    $imgconfig['new_image'] = $filedata['full_path'];
                    $imgurl = $filedata['full_path'];
                    $this->image_lib->initialize($imgconfig);
                    $this->image_lib->resize();
                    
                    $ud_sql = "update cb_mms_images
                                  set file".($i+1)."_path = '".$filedata['file_name']."'
                                     ,origin".($i+1)."_path = '".$filedata['full_path']."'
                                where mem_id = '".$this->member->item("mem_id")."' and mms_id='".$mms_id."'";
                    $this->db->query($ud_sql);
                    
                } else {
                    log_message("ERROR", "IMG ERROR : ".$this->upload->display_errors());
                    $err_cnt = $err_cnt + 1;
                }
            }
        }
        header('Content-Type: application/json');
        if($err_cnt == 0) {
            echo '{"code":"success","imgurl":"'.str_replace("/var/www/html","",$imgurl).'", "mms_id":"'.$mms_id.'"}';
        } else {
            echo '{"message":"'.$err_cnt.'", "code":"error"}';
        }
    }
    
    
    public function msg_save()
    {
        $msg = $this->input->post('msg');
        $msg_type = $this->input->post('msg_type');
        $msg_kind = $this->input->post('msg_kind');
        
        if($msg_type == "TEMP") {
            $sql = "update cb_user_msg set msg = '".$msg."', msg_kind='".$msg_kind."', reg_date='".cdate('Y-m-d H:i:s')."' where mem_id=".$this->member->item('mem_id')." and msg_type='TEMP'";
            
            $this->db->query($sql);
            $cnt = $this->db->affected_rows();
            
            if($cnt == 0) {
                $data = array();
                $data['mem_id'] = $this->member->item('mem_id');
                $data['msg'] = $msg;
                $data['msg_type'] =  $msg_type;
                $data['msg_kind'] =  $msg_kind;
                $data['reg_date'] =  cdate('Y-m-d H:i:s');
                
                $this->db->insert("user_msg", $data);
            }
        } else {
            //- 발송내역에 기록
            $data = array();
            $data['mem_id'] = $this->member->item('mem_id');
            $data['msg'] = $msg;
            $data['msg_type'] =  $msg_type;
            $data['msg_kind'] =  $msg_kind;
            $data['reg_date'] =  cdate('Y-m-d H:i:s');
            
            $this->db->insert("user_msg", $data);
            
        }
        header('Content-Type: application/json');
        echo '{"message":"success", "code":"success"}';
        
    }
    
    public function msg_load()
    {
        $msg_id = $this->input->post('msg_id');
        $msg_type = $this->input->post("msg_type");
        log_message("ERROR", "msg type : ".$msg_type);
        if($msg_type == "U") {
            $sql = "select * from cb_user_msg where msg_id = '".$msg_id."'";
        } else if($msg_type == "P") {
            $sql = "select mst_content as msg, mst_id as msg_id, 'LMS' as msg_kind, ifnull(mst_lms_content, mst_sms_content) as mst_lms_content from cb_wt_msg_sent where mst_id = '".$msg_id."'";
        } 
        
        $msg = $this->db->query($sql)->result_array();;
        $json = "";
        foreach($msg as $m)
        {
            $json = json_encode($m);
        }
        //log_message("ERROR", $json);
        header('Content-Type: application/json');
        echo $json;
        
    }
    
    public function friend_msg_load()
    {
        $msg_id = $this->input->post('msg_id');
        $msg_type = $this->input->post("msg_type");
        log_message("ERROR", "msg type : ".$msg_type);
        if($msg_type == "U") {
            $sql = "select * from cb_user_msg where msg_id = '".$msg_id."'";
        } else if($msg_type == "P") {
            //$sql = "select mst_content as msg, mst_id as msg_id, 'LMS' as msg_kind, mst_lms_content, mst_sms_content from cb_wt_msg_sent where mst_id = '".$msg_id."'";
            $sql = "select IFNULL(a.mst_content, '') as msg, a.mst_id as msg_id, IFNULL(a.mst_lms_content, '') as mst_lms_content, a.mst_kind as mst_kind, a.mst_img_content as mst_img_countent, TRIM(a.mst_button) as mst_button, b.img_filename as img_filename from cb_wt_msg_sent a left join cb_img_".$this->member->item('mem_userid')." b on a.mst_img_content = b.img_url where a.mst_id = '".$msg_id."'";
        }
        
        $msg = $this->db->query($sql)->result_array();;
        $json = "";
        foreach($msg as $m)
        {
            $json = json_encode($m);
        }
        //log_message("ERROR", $json);
        header('Content-Type: application/json');
        echo $json;
        
    }
    
    public function msg_save_list() {
        
        $del_ids = $this->input->post('del_ids');
        if($del_ids && count($del_ids) > 0 && $del_ids[0]) {
            foreach($del_ids as $did) {
                $this->db->delete("user_msg", array("msg_id"=>$did));
                log_message("ERROR", "delete : ".$did);
            }
        }
        
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 5;
        $view['param']['search_msg'] = $this->input->post("search_msg");
        $view['param']['search_kind'] = $this->input->post("search_kind");
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
        
        $where = " mem_id =".$this->member->item('mem_id')." ";
        $param = array();
        
        $msgonly = $this->input->post("msgonly");
        
        if($view['param']['search_msg']) {
            $where .= " and msg like ? ";
            array_push($param, '%'.$view['param']['search_msg'].'%');
        }
        
        if($view['param']['search_kind']!="all") {
            $where .= " and msg_kind = ? ";
            array_push($param, $view['param']['search_kind']);
        }
        
        if($msgonly=='Y') {
            $where .= " and msg_kind in ('SMS','LMS') ";
        }
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $this->Biz_model->get_table_count("cb_user_msg", $where, $param);
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = str_replace('open_page','open_page_user_msg',$this->pagination->create_links());
        
        $sql = "
			select a.*
			from cb_user_msg a
			where ".$where." order by (case when msg_type = 'TEMP' then 0 else 1 end), reg_date desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //log_message("ERROR", $sql);
        $view['list'] = $this->db->query($sql, $param)->result();
        $view['view']['canonical'] = site_url();
        
        $this->load->view('biz/sender/user_msg_list',$view);
    }

    public function pre_msg_list() {
        
        $del_ids = $this->input->post('del_ids');
        if($del_ids && count($del_ids) > 0 && $del_ids[0]) {
            foreach($del_ids as $did) {
                $this->db->delete("user_msg", array("msg_id"=>$did));
                log_message("ERROR", "delete : ".$did);
            }
        }
        
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 5;
        $view['param']['search_msg'] = $this->input->post("search_msg");
        $view['param']['search_kind'] = $this->input->post("search_kind");
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
        
        $where = " mst_kind <> 'at' and mst_mem_id =".$this->member->item('mem_id')." ";
        $param = array();
        
        $msgonly = $this->input->post("msgonly");
        
        if($view['param']['search_msg']) {
            $where .= " and mst_content like ? ";
            array_push($param, '%'.$view['param']['search_msg'].'%');
        }
        
        $cntsql = "select count(1) as cnt FROM cb_wt_msg_sent a WHERE ".$where."  AND a.mst_reserved_dt < '".cdate('YmdHis')."'";
        $cnt = $this->db->query($cntsql)->row()->cnt;
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $cnt;
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = str_replace('open_page','open_page_pre_msg',$this->pagination->create_links());
        
        $sql = "select a.*
			FROM cb_wt_msg_sent a 
			WHERE ".$where."
			  AND a.mst_reserved_dt < '".cdate('YmdHis')."'
			ORDER BY mst_id DESC limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql, $param)->result();
        $view['view']['canonical'] = site_url();
        
        $this->load->view('biz/sender/pre_msg_list',$view);
    }
}
?>