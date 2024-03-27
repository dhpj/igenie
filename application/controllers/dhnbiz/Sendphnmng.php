<?php
class Sendphnmng extends CB_Controller {
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
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['userid'] = $this->input->post("uid");
        
        $uid = $this->member->item('mem_id');
        
        $where = " use_flag in ( 'Y', 'N') ";
        
        if($view['param']['search_for']) {
            $where .= " and send_tel_no like '%".$view['param']['search_for']."%' ";
        }

        if($view['param']['search_type'] && $view['param']['search_type'] != 'X') {
            $where .= " and auth_flag = '".$view['param']['search_type']."' ";
        }
        
        if($view['param']['userid'] && $view['param']['userid'] != "ALL" ) {
            $where .= " and cm.mem_username like '%".$view['param']['userid']."%' ";
        }
        
        
        $sql = "select count(1) as cnt
        	    from cb_send_tel_no_list a inner join
        	    (SELECT distinct  a.mem_id
        	        FROM
        	        (SELECT
        	            GET_PARENT_EQ_PRIOR_ID(cmr.mem_id) AS _id, @level AS level
        	            FROM
        	            (SELECT @start_with:=".$uid.", @id:=@start_with) vars, cb_member_register cmr
        	            WHERE
        	            @id IS NOT NULL) ho
        	        LEFT JOIN
        	        cb_member_register c ON c.mem_id = ho._id
        	        INNER JOIN
        	        cb_member a ON (c.mem_id = a.mem_id or a.mem_id = ".$uid.")
        	        AND a.mem_useyn = 'Y'
        	        ) b on a.mem_id = b.mem_id
                    inner join cb_member cm on cm.mem_id = a.mem_id
        	        where ".$where;

        $view['total_rows']  = $this->db->query($sql)->row()->cnt;
        //log_message("ERROR", $sql);
        
        $sql = "select a.*, cm.mem_username, cm.mem_userid
        	    from cb_send_tel_no_list a inner join
        	    (SELECT distinct  a.mem_id
        	        FROM
        	        (SELECT
        	            GET_PARENT_EQ_PRIOR_ID(cmr.mem_id) AS _id, @level AS level
        	            FROM
        	            (SELECT @start_with:=".$uid.", @id:=@start_with) vars, cb_member_register cmr
        	            WHERE
        	            @id IS NOT NULL) ho
        	        LEFT JOIN
        	        cb_member_register c ON c.mem_id = ho._id
        	        INNER JOIN
        	        cb_member a ON (c.mem_id = a.mem_id or a.mem_id = ".$uid.")
        	        AND a.mem_useyn = 'Y'
        	        ) b on a.mem_id = b.mem_id
                    inner join cb_member cm on cm.mem_id = a.mem_id
        	        where ".$where. "
			order by cm.mem_username, a.reg_date limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        
        $view['list'] = $this->db->query($sql)->result();
        //$view['users'] = $this->Biz_model->get_partners($this->member->item('mem_id'));
        
        $this->load->library('pagination');
        $page_cfg = array();
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        
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
            'path' => 'biz/sendphnmng',
            'layout' => 'layout',
            'skin' => 'lists',
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
    
    
    public function write()
    {
        $mode = $this->input->post("actions");
         
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['view']['canonical'] = site_url();
        
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        $view['phncnt'] = $this->db->query("select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and use_flag in ('Y', 'N')")->row()->cnt;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/sendphnmng',
            'layout' => 'layout',
            'skin' => 'write',
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
     
    public function check_duplicated_tel() {
        $aut_tel = $this->input->post("auth_num");
        
        $cnt = $this->db->query("select count(1) as cnt from cb_sendnumber_auth a where a.aut_mem_id = '".$this->member->item("mem_id")."' and aut_snd_no = '".$aut_tel."' and aut_result = 'Y'")->row()->cnt;
        
        header('Content-Type: application/json');
        echo '{"result": '.$cnt.'}';
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
    
    public function send_auth_number() {
        $agent = 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.0)';
        $aut_no = mt_rand(100000, 999999);
        $aut_tel = $this->input->post("auth_num");
        $aut_loc = $this->input->post("location");
        
        if($this->is_valid_callback($aut_tel)) {
            $cnt = $this->db->query("select count(1) as cnt from cb_sendnumber_auth a where a.aut_mem_id = '".$this->member->item("mem_id")."' and aut_snd_no = '".$aut_tel."' and aut_result = 'N'")->row()->cnt;
            
            if($cnt > 0) {
                $this->db->query("delete from cb_sendnumber_auth where aut_mem_id = '".$this->member->item("mem_id")."' and aut_snd_no = '".$aut_tel."' and aut_result = 'N'");
            }
            
            $cb_auth = array();
            $cb_auth["aut_mem_id"] = $this->member->item("mem_id");
            $cb_auth["aut_snd_no"] = $aut_tel;
            $cb_auth["aut_req_date"] = cdate('Y-m-d H:i:s');
            $cb_auth["aut_result"] = 'N';
            $cb_auth["aut_aut_no"] = $aut_no;
            $cb_auth["aut_exp_date"] = cdate('Y-m-d H:i:s',strtotime('+5 minutes'));
            
            $this->db->insert("sendnumber_auth", $cb_auth);
            
            if($aut_loc == 'auth_mob') {
                // sms 인증이면...
                $url = "http://authvoice.caller.co.kr/auth_proc_standard.php?DATA=authvoice|dhn7985|1|".$aut_tel."|".$aut_no; 
                $ch = curl_init();
                $options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => false,
                    CURLOPT_HTTPGET => TRUE,
                    CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_USERAGENT => $agent,
                    CURLOPT_REFERER => "",
                    CURLOPT_TIMEOUT => 3
                ); 
                curl_setopt_array($ch, $options);
                $buffer = curl_exec ($ch);
                $cinfo = curl_getinfo($ch);
                //log_message("ERROR",$url." : ".$cinfo['http_code']." : ".$buffer);
                curl_close($ch);
                //if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return $buffer; }
                
            } else if($aut_loc == 'auth_ars') {
                // ARS 인증이면...
                // sms 인증이면...
                $url = "http://authvoice.caller.co.kr/auth_proc_standard.php?DATA=authvoice|dhn7985|0|".$aut_tel."|".$aut_no;
                $ch = curl_init();
                $options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => false,
                    CURLOPT_HTTPGET => TRUE,
                    CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_USERAGENT => $agent,
                    CURLOPT_REFERER => "",
                    CURLOPT_TIMEOUT => 3
                );
                curl_setopt_array($ch, $options);
                $buffer = curl_exec ($ch);
                $cinfo = curl_getinfo($ch);
                //log_message("ERROR",$url." : ".$cinfo['http_code']." : ".$buffer);
                curl_close($ch);
                //if ($cinfo['http_code'] != 200) { return ($buffer) ? $buffer : ''; } else { return $buffer; }
            }
            header('Content-Type: application/json');
            echo '{"result": "success"}';
        } else {
            header('Content-Type: application/json');
            echo '{"result": "not_valid_no"}';
        }
        
    }
    
    public function check_auth_number() {
        
        $aut_tel = $this->input->post("mobile");
        $aut_no = $this->input->post("auth_code");
        $result  = '';
        
        $aut_rs = $this->db->query("select *  from cb_sendnumber_auth a where a.aut_mem_id = '".$this->member->item("mem_id")."' and aut_snd_no = '".$aut_tel."' and aut_result = 'N'")->row();
        
        if(!$aut_tel) {
            $result = '{"result": "NoMatchCode"}';
        }
        
        if(!$aut_no) {
            $result = '{"result": "EmptyCode"}';
        }
        
        if($aut_rs->aut_exp_date < cdate('Y-m-d H:i:s')) {
            $result = '{"result": "TimeOut"}';
        } else if($aut_rs->aut_aut_no <> $aut_no) {
            $result = '{"result": "NoMatchCode"}';
        } else {
            $upsql = "update cb_sendnumber_auth 
                         set aut_result = 'Y'
                            ,aut_aut_date = '".cdate('Y-m-d H:i:s')."'
                       where aut_mem_id = '".$this->member->item("mem_id")."' and aut_snd_no = '".$aut_tel."'";
            $this->db->query($upsql);    
            
            $result = '{"result": "Success"}';
        }
                
        header('Content-Type: application/json');
        echo $result;
    }
    
    public function callback_add() {

        $sendmng = array();
        $file_error = '';
        $uploadfiledata = '';
        $this->load->library('upload');
        
        if (isset($_FILES) && isset($_FILES['certificate_file']) && isset($_FILES['certificate_file']['name'])) {
            //log_message("ERROR", "File ".$_FILES['certificate_file']['name']);
            $filecount = count($_FILES['certificate_file']['name']);
            $upload_path = config_item('uploads_dir') . '/member_certi/';
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
            
            $uploadconfig = '';
            $uploadconfig['upload_path'] = $upload_path;
            $uploadconfig['allowed_types'] = element('upload_file_extension', $board) ? element('upload_file_extension', $board) : '*';
            $uploadconfig['max_size'] = element('upload_file_max_size', $board) * 1024;
            $uploadconfig['encrypt_name'] = true;
            
            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $_FILES['certificate_file']['name'];
            $_FILES['userfile']['type'] = $_FILES['certificate_file']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['certificate_file']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['certificate_file']['error'];
            $_FILES['userfile']['size'] = $_FILES['certificate_file']['size'];
            if ($this->upload->do_upload()) {
                $filedata = $this->upload->data();
                
                $uploadfiledata['pfi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
                $uploadfiledata['pfi_originname'] = element('orig_name', $filedata);
                $uploadfiledata['pfi_filesize'] = intval(element('file_size', $filedata) * 1024);
                $uploadfiledata['pfi_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
                $uploadfiledata['pfi_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
                $uploadfiledata['pfi_type'] = str_replace('.', '', element('file_ext', $filedata));
                $uploadfiledata['is_image'] = element('is_image', $filedata) ? 1 : 0;
                
                $sendmng["att_url"] = config_item('uploads_dir').'/member_certi/'.$uploadfiledata['pfi_filename'];
                if($old_photo && file_exists(config_item('uploads_dir').'/member_certi/'.$old_photo)) {
                    unlink(config_item('uploads_dir').'/member_certi/'.$old_photo);
                }
            } else {
                $file_error = $this->upload->display_errors();
            }
        }

        $sendmng["mem_id"] = $this->member->item("mem_id");
        $sendmng["send_tel_no"] = $this->input->post("auth_num");
        $sendmng["auth_type"] = $this->input->post("auth_method");
        
        $auth_flag = "A";
        if($this->input->post("auth_method") == "mobile" || $this->input->post("auth_method") == "ars") {
            $auth_flag = "O";
        }
        $sendmng["auth_flag"] = $auth_flag;
        $sendmng["reg_date"] = cdate('Y-m-d H:i:s');
        $sendmng["memo"] =  $this->input->post("comments");
        $sendmng["use_flag"] = "Y";
        
        $this->db->insert("send_tel_no_list", $sendmng);
        header('Content-Type: application/json');
        if ($this->db->error()['code'] > 0) {
            echo '{"result": "'.$this->db->error()['message'].'"}';
        } else {
            echo '{"result": null}';
        }
    }
    
    
    function remove() {
        $ok = 0; $err = 0; $fail = 0;
        $key = $this->input->post('stn_ids');
        //log_message("ERROR", "key ".$key);
        
        
        foreach($key as $k) {
            $this->db->query("update cb_send_tel_no_list set use_flag='D' where idx='".$k."'");
            if ($this->db->error()['code'] > 0) {
                $err = $err + 1;                
            } else {
                $ok = $ok + 1;
            }
        }
        
        $this->index();
    }
    
    function usephnno() {
        $ok = 0; $err = 0; $fail = 0;
        $key = $this->input->post('stn_ids');
        
        //log_message("ERROR", "key ".$key);
        
        foreach($key as $k) {
            //log_message("ERROR", "k ".$k);
            
            $mMem_id = $this->db->query("select mem_id from cb_send_tel_no_list where idx = '".$k."'")->row()->mem_id;
            $mSendPhone = $this->db->query("select send_tel_no from cb_send_tel_no_list where idx = '".$k."'")->row()->send_tel_no;
            $this->db->query("update cb_send_tel_no_list set use_flag='N' where mem_id='".$mMem_id."' and use_flag in ('Y', 'N')");
            $this->db->query("update cb_send_tel_no_list set use_flag='Y' where idx='".$k."'");
            $this->db->query("update cb_wt_send_profile_dhn set spf_sms_callback='".$mSendPhone."' where spf_mem_id='".$mMem_id."' and spf_use='Y' and spf_status='A'");
            $this->db->query("update cb_member set mem_phone='".$mSendPhone."' where mem_id='".$mMem_id."' and mem_useyn='Y'");
            if ($this->db->error()['code'] > 0) {
                $err = $err + 1;
            } else {
                $ok = $ok + 1;
            }
            break;
        }
        
        $this->index();
    }
    
    function approval() {
        $ok = 0; $err = 0; $fail = 0;
        $key = $this->input->post('stn_ids');
        
        foreach($key as $k) {
            $this->db->query("update cb_send_tel_no_list set auth_flag='O' where idx='".$k."'");
            if ($this->db->error()['code'] > 0) {
                $err = $err + 1;
            } else {
                $ok = $ok + 1;
            }
        }
        
        $this->index();
    }

    function reject() {
        $ok = 0; $err = 0; $fail = 0;
        $key = $this->input->post('stn_ids');
        
        foreach($key as $k) {
            $this->db->query("update cb_send_tel_no_list set auth_flag='R' where idx='".$k."'");
            if ($this->db->error()['code'] > 0) {
                $err = $err + 1;
            } else {
                $ok = $ok + 1;
            }
        }
        
        $this->index();
        
    }
    
}