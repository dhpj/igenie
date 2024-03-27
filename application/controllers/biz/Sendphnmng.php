<?php
class Sendphnmng extends CB_Controller {
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
        $this->load->library(array('querystring', 'funn'));
    }

	//발신번호 관리
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
            $where .= " and d.mem_username like '%".$view['param']['userid']."%' ";
        }
        //발신번호 전체수
        $sql = "
                WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    FROM cb_member_register cmr
                    JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    WHERE cm.mem_id = " . $uid . "
                    AND cmr.mem_id <> cm.mem_id
                    UNION ALL
                    SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    FROM cb_member_register cmr
                    JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                )
                select count(1) as cnt
        	    from cb_send_tel_no_list a
                left join cmem c on a.mem_id = c.mem_id
                inner join cb_member d on c.mem_id = d.mem_id and d.mem_useyn = 'y'
        	        where ".$where;
        $view['total_rows']  = $this->db->query($sql)->row()->cnt;
        // log_message("ERROR", $sql);
        //발신번호 현황
		$sql = "
                WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                  FROM cb_member_register cmr
                  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                 WHERE cm.mem_id = " . $uid . "
                 AND cmr.mem_id <> cm.mem_id
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                  FROM cb_member_register cmr
                  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                  JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                )
                select a.*, d.mem_username, d.mem_userid, (select count(1) as cnt from cb_send_tel_no_list iii where iii.mem_id = a.mem_id and use_flag = 'Y') as cnt
                	, (select max(reg_date) from cb_send_tel_no_list cstnl where cstnl.mem_id = a.mem_id) as latest_date
                	,  (select count(1) as cnt from cb_send_tel_no_list iii where iii.mem_id = a.mem_id and use_flag in ('Y', 'N')) as row_cnt
                from cb_send_tel_no_list a
                left join cmem c on a.mem_id = c.mem_id
                inner join cb_member d on c.mem_id = d.mem_id and d.mem_useyn = 'y'
        	        where ".$where. "
                  order by latest_date DESC, a.reg_date DESC limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
                  //order by cm.mem_username, a.reg_date limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']
        //echo $sql ."<br>";
        // log_message("ERROR", $sql);
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

    //발신번호 등록
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

		//소속 현황
		$view['users'] = $this->Biz_dhn_model->get_sub_manager($this->member->item('mem_id'));

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

    //발신번호 중복확인
	public function check_duplicated_tel() {
        $mem_id = $this->input->post("mem_id");
		if($mem_id == ""){
			$mem_id = $this->member->item("mem_id");
		}
		$aut_tel = $this->input->post("auth_num");
        $cnt = $this->db->query("select count(1) as cnt from cb_sendnumber_auth a where a.aut_mem_id = '".$mem_id."' and aut_snd_no = '".$aut_tel."' and aut_result = 'Y'")->row()->cnt;
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
        $mem_id = $this->input->post("mem_id");
		if($mem_id == ""){
			$mem_id = $this->member->item("mem_id");
		}
		$agent = 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.0)';
        $aut_no = mt_rand(100000, 999999);
        $aut_tel = $this->input->post("auth_num");
        $aut_loc = $this->input->post("location");

        if($this->is_valid_callback($aut_tel)) {
            $cnt = $this->db->query("select count(1) as cnt from cb_sendnumber_auth a where a.aut_mem_id = '".$mem_id."' and aut_snd_no = '".$aut_tel."' and aut_result = 'N'")->row()->cnt;

            if($cnt > 0) {
                $this->db->query("delete from cb_sendnumber_auth where aut_mem_id = '".$mem_id."' and aut_snd_no = '".$aut_tel."' and aut_result = 'N'");
            }

            $cb_auth = array();
            $cb_auth["aut_mem_id"] = $mem_id;
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
                log_message("ERROR",$url." : ".$cinfo['http_code']." : ".$buffer);
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
                log_message("ERROR",$url." : ".$cinfo['http_code']." : ".$buffer);
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
        $mem_id = $this->input->post("mem_id");
		if($mem_id == ""){
			$mem_id = $this->member->item("mem_id");
		}
        $aut_tel = $this->input->post("mobile");
        $aut_no = $this->input->post("auth_code");
        $result  = '';

        $aut_rs = $this->db->query("select *  from cb_sendnumber_auth a where a.aut_mem_id = '".$mem_id."' and aut_snd_no = '".$aut_tel."' and aut_result = 'N'")->row();

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
                       where aut_mem_id = '".$mem_id."' and aut_snd_no = '".$aut_tel."'";
            $this->db->query($upsql);

            $result = '{"result": "Success"}';
        }

        header('Content-Type: application/json');
        echo $result;
    }

    public function callback_add() {
		$mem_id = $this->input->post("mem_id");
		if($mem_id == ""){
			$mem_id = $this->member->item("mem_id");
		}
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

        $sendmng["mem_id"] = $mem_id;
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

    //발신번호 선택사용
	function usephnno() {
        $ok = 0; $err = 0; $fail = 0;
        $key = $this->input->post('stn_ids');
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > key ".$key);
        foreach($key as $k) {
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > k : ".$k);
            $mMem_id = $this->db->query("select mem_id from cb_send_tel_no_list where idx = '".$k."'")->row()->mem_id;
            $mSendPhone = $this->db->query("select send_tel_no from cb_send_tel_no_list where idx = '".$k."'")->row()->send_tel_no;
            $this->db->query("update cb_send_tel_no_list set use_flag='N' where mem_id='".$mMem_id."' and use_flag in ('Y', 'N')");
            $this->db->query("update cb_send_tel_no_list set use_flag='Y' where idx='".$k."'");
            $this->db->query("update cb_wt_send_profile set spf_sms_callback='".$mSendPhone."' where spf_mem_id='".$mMem_id."' and spf_use='Y' and spf_status='A'");
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

    public function chguse(){
		$idx = $this->input->post("id");
        $mem_id = $this->input->post("uid");
		$result = array();
        if(!empty($idx)&&!empty($mem_id)){
            $sql = "select * from cb_send_tel_no_list where idx='".$idx."' and mem_id = '".$mem_id."'";
            $chgdata = $this->db->query($sql)->row();
            if($chgdata->use_flag != 'Y'){
                $sql = "update cb_send_tel_no_list SET use_flag = 'Y' where idx='".$idx."' and mem_id = '".$mem_id."'";
                $this->db->query($sql);
            }
            $sql = "update cb_send_tel_no_list SET use_flag = 'N' where idx <> '".$idx."' and use_flag = 'Y' and mem_id = '".$mem_id."'";
            $this->db->query($sql);

            $sql = "select count(1) as cnt from cb_wt_send_profile_dhn where spf_mem_id = '".$mem_id."'";
            $profile_cnt = $this->db->query($sql)->row()->cnt;
            if($profile_cnt==1){
                $sql = "update cb_wt_send_profile_dhn SET spf_sms_callback = '".$chgdata->send_tel_no."' where spf_mem_id = '".$mem_id."'";
                $this->db->query($sql);
                $result["msg"] = "발신번호가 변경되었습니다.";
                $result["code"] = "0";
            }else{
                if($profile_cnt==0){
                    $result["msg"] = "발신번호는 변경되었으나 발신프로필이 없습니다.";
                    $result["code"] = "0";
                }else{
                    $result["msg"] = "발신번호는 변경되었으나 발신프로필 문자발신번호를 변경하는데 문제가 있습니다.";
                    $result["code"] = "1";
                }
            }

            $sql = "update cb_member SET mem_phone = '".$chgdata->send_tel_no."' where mem_id = '".$mem_id."'";
            $this->db->query($sql);

        }else{
            $result["code"] = "1";
            $result["msg"] = "발신번호 변경에 문제가 있습니다.";
        }
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//파트너 검색
	public function partner_list() {
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 파트너 검색");
		$result = array();
		$perpage = ($this->input->post("perpage")) ? $this->input->post("perpage") : 15; //개시물수
		$userid = $this->input->post("uid"); //소속
		$search_type = $this->input->post("search_type"); //검색타입
		$search_for = $this->input->post("search_for"); //검색내용
		$page = ($this->input->post("page")) ? $this->input->post("page") : 1; //현재페이지
		if($userid && $userid!="ALL" && is_numeric($userid)) {
			$mem_id = $userid;
		} else {
			$mem_id = $this->member->item('mem_id');
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > perpage : ". $perpage .", userid : ". $userid .", search_type : ". $search_type  .", search_for : ". $search_for .", page : ". $page);

		$where = " mem_useyn='Y' ";
		if($search_type && $search_for){
			$where .= " and a.mem_". $search_type ." like '%". addslashes($search_for) ."%' ";
		}

		//이미지 라이브러리 전체수
		$sql = "
		SELECT
			  count(1) as cnt
		FROM cb_images i
		where 1=1 ";

		//이미지 라이브러리 현황
		$sql = "
		SELECT
			  img_id as mem_id
			, img_name as mem_username
			, '소속' as mrg_recommend_mem_username
			, img_path_thumb /* 썸네일 이미지 경로 */
		FROM cb_images i
		where 1=1
		ORDER BY i.img_name ASC
		limit ". (($page - 1) * $perpage) .", ". $perpage;

		//업체 전체수
		$sql = "
		WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
			SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
			FROM cb_member_register cmr
			JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
			WHERE cm.mem_id = '". $mem_id ."' AND cmr.mem_id <> cm.mem_id
			UNION ALL
			SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
			FROM cb_member_register cmr
			JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
			JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
		)
		SELECT count(1) as cnt
		FROM cmem c
		INNER JOIN cb_member a on a.mem_id = c.mem_id and a.mem_useyn = 'Y'
		where ". $where;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		$result['total'] = $this->db->query($sql)->row()->cnt;

		//업체 현황
		$sql = "
		WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
			SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
			FROM cb_member_register cmr
			JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
			WHERE cm.mem_id = '". $mem_id ."' AND cmr.mem_id <> cm.mem_id
			UNION ALL
			SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
			FROM cb_member_register cmr
			JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
			JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
		)
		SELECT a.*,
			a.mem_deposit total_deposit,
			c.parent_id AS mrg_recommend_mem_id,
			IFNULL((SELECT mem_username FROM cb_member WHERE mem_id = c.parent_id),'') AS mrg_recommend_mem_username
		FROM cmem c
		INNER JOIN cb_member a on a.mem_id = c.mem_id
		and a.mem_useyn = 'Y'
		where ". $where ."
		order by c.parent_id, a.mem_level desc, a.mem_username
		limit ". (($page - 1) * $perpage) .", ". $perpage;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);

		$result['list']= $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > page 1 : ". $page);
		$result['page'] = $page++; //다음페이지
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > page 2 : ". $page);

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		$json = str_replace('null', '""', $json);
		header('Content-Type: application/json');
		echo $json;
	}

  //이미지 저장
	public function imgfile_save(){
		$upload_path = config_item('uploads_dir') . '/member_certi/'.dirname($this->input->post("oldpath"))."/";
    $upload_max_size = 5 * 1024 * 1024; //파일 제한 사이지(byte 단위) (5MB)
		$imgpath = "";
		if(empty($_FILES['imgfile']['name']) == false){ //이미지 업로드

			$img_data = $this->img_upload($upload_path, 'imgfile', $upload_max_size);

      //if(!empty($img_data) ){
      if( is_array($img_data) && !empty($img_data) ){
				$imgpath = '/'.$upload_path.$img_data['file_name']; //이미지 경로
        //alert($imgpath); exit('');
        $sql = "update cb_send_tel_no_list set att_url = '".$upload_path.$img_data['file_name']."' where idx = '". $this->input->post("idx") ."'  ";
  	    $this->db->query($sql);
        //alert("update 실행"); exit('');
        //기존파일 삭제 코드 사용안함
        /*$old_photo = basename($this->input->post("oldpath"));
        if(file_exists($upload_path.$old_photo)) { //기존파일 삭제
            unlink($upload_path.$old_photo);
        }*/
			}
		}
		$result = array();
		$result['code'] = '0';
		$result['imgpath'] = $imgpath;
    $result['idx'] = $this->input->post("idx");
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

  //이미지 업로드 함수
  	public function img_upload($upload_img_path = null, $field_name = null, $size = null){

  		if( is_dir($upload_img_path)  == false ){
        mkdir($upload_img_path, 0707);
  			//alert('해당 디렉도리가 존재 하지 않음'); exit();
  		}
  		if ( is_null($field_name) ){
  			//alert('필드값을 기입하지 않았습니다.'); exit();

  		}
  		if( is_null($size) || is_numeric($size) == false || $size == ''){
  			$size = 10 * 1024 * 1024; //파일 제한 사이지(byte 단위) (10MB)
  		}
  		if(is_array($_FILES) && empty($_FILES[$field_name]['name']) == false){
  			$upload_img_config = '';
  			$upload_img_config['upload_path'] = $upload_img_path;
  			$upload_img_config['allowed_types'] = 'gif|jpg|png|jpeg';
  			$upload_img_config['encrypt_name'] = true;
  			$upload_img_config['max_size'] = $size;
  			//$upload_img_config['max_width'] = 1200;
  			//$upload_img_config['max_height'] = 1200;
  			$this->load->library('upload',$upload_img_config);
  			$this->upload->initialize($upload_img_config);
  			if ($this->upload->do_upload($field_name)) {
  				//업로드 성공
  				$filedata = $this->upload->data();
          //alert('파일이 업로드 되었습니다.'); exit('');


  				//$this->resize_image($filedata, 300, 300); //이미지를 원하는 크기(원본비율 유지)로 리사이즈
  				//$image = new Image($filedata['full_path']);
  				//list($filedata['image_width'], $filedata['image_height']) = getimagesize($filedata['full_path']);
  				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > resize_image OK");
  				return $filedata;
  			}else{
  				//업로드 실패
  				$error = $this->upload->display_errors();
  				alert($error, '/maker'); exit('');
  				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 업로드 실패");
  			}
  		} else {
  			//alert('파일이 업로드 되지 않았습니다.'); exit('');
  			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 파일이 업로드 되지 않았습니다.");
  		}
  	}



}
