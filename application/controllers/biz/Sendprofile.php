<?php
class Sendprofile extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Post_file', 'Biz');
    
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
        /* 스윗트렉커에서 전달된 프로필과 저장된 프로필을 비교하여 수정처리하는 부분이 필요함 */
    }
    
    public function lists()
    {
        $cache_limiter = session_cache_limiter();
        //log_message('ERROR', 'List 시작'.$cache_limiter);
            
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['param']['userid'] = $this->input->post("uid");
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        
        
        $uid = $this->member->item('mem_id');
        if($view['param']['userid'] && $view['param']['userid']!="ALL" && is_numeric($view['param']['userid'])) { $uid = $view['param']['userid']; }
        
        $where = "";
        $param = array();
        if($view['param']['search_for'] && $view['param']['search_type']) {
            $where .= " and a.spf_".$view['param']['search_type']." like ?";
            array_push($param, "%".$view['param']['search_for']."%");
        }
        
        $view['users'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));
        $view['total_rows'] = $this->Biz_model->get_partner_profile_count($uid, $where, $param);
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        
        $view['list'] = $this->Biz_model->get_partner_profile_list($uid, $view['param']['page'] - 1, $view['perpage'], $where, $param);
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
            'path' => 'biz/sendprofile',
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
        if($mode=="sendprofile_save") { $this->sendprofile_save(); return; }
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        // 관리자의 스윗트렉커 아이디를 가져온다.
        $this->load->library('sweettracker');
        $view['admin_sw_id'] = $this->sweettracker->userId;
        
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
        
        $view['category'] = $this->sweettracker->get_category();
        
        $layoutconfig = array(
            'path' => 'biz/sendprofile',
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
    
    function sendprofile_save()
    {
        $pf_key = "";
        
        $id = $this->input->post("pf_yid");
        $tel = $this->input->post("phoneNumber");
        $category = $this->input->post("category");
        $licenseNumber = $this->input->post("licenseNumber");
        $token = $this->input->post("token");
        $token_code = $this->input->post("token_code");
        $api_code = $this->input->post("api_code");
        $api_data = json_decode($this->input->post("api_data"));
        $pf_key = $this->input->post("pf_key");
        
        if(!$id || !$tel || !$token || !isset($_FILES)) { echo '<script type="text/javascript">alert("입력항목 누락!");history.back();</script>'; exit; }
        // id 중복검사
        $cnt = $this->Biz_model->get_table_count("cb_wt_send_profile", "spf_friend=? and spf_use='Y'", array($id));
        if($cnt > 0) { echo '<script type="text/javascript">alert("이미 등록된 검색아이디 입니다!");history.back();</script>'; exit; }
        
        $pf_type = "None";
        // 기 등록된 플러스 친구인 경우 프로필 테이블에 없는 값이면 success로 처리
        if($api_data->message=="이미 사용중인 플러스친구 입니다.") {
            $old_key = $this->db->query("select spf_key, spf_friend, spf_use from cb_wt_send_profile where spf_friend=?", array($id))->row();
            if($old_key && $old_key->spf_friend==$id) { $api_data->data = $old_key->spf_key; $cnt = 1; }
            $api_code="success";
            $api_data->data = $pf_key;
        }
        if($api_code=="fail") {
            if($api_data->data && $api_data->data->senderKey) {
                $pf_key = $api_data->data->senderKey;
                $pf_type = $api_data->data->senderKeyType;
            }
        } else if($api_code=="success") {
            $pf_key = $api_data->data;
        }
        if(!$pf_key) {
            echo '<script type="text/javascript">alert("'.$api_data->message.'('.$pf_key.')");history.back();</script>';
            return;
        }
        
        $file_error = '';
        $uploadfiledata = '';
        $this->load->library('upload');
        if (isset($_FILES) && isset($_FILES['biz_license']) && isset($_FILES['biz_license']['name']) && isset($_FILES['biz_license']['name'])) {
            $filecount = count($_FILES['biz_license']['name']);
            $upload_path = config_item('uploads_dir') . '/biz_license/';
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
            $_FILES['userfile']['name'] = $_FILES['biz_license']['name'];
            $_FILES['userfile']['type'] = $_FILES['biz_license']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['biz_license']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['biz_license']['error'];
            $_FILES['userfile']['size'] = $_FILES['biz_license']['size'];
            if ($this->upload->do_upload()) {
                $filedata = $this->upload->data();
                
                $uploadfiledata['pfi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
                $uploadfiledata['pfi_originname'] = element('orig_name', $filedata);
                $uploadfiledata['pfi_filesize'] = intval(element('file_size', $filedata) * 1024);
                $uploadfiledata['pfi_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
                $uploadfiledata['pfi_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
                $uploadfiledata['pfi_type'] = str_replace('.', '', element('file_ext', $filedata));
                $uploadfiledata['is_image'] = element('is_image', $filedata) ? 1 : 0;
            } else {
                $file_error = $this->upload->display_errors();
            }
        }
        
        /* 스윗트렉커로 전송 */
        //$this->load->library('sweettracker');
        //$result = $this->sweettracker->sender_create($this->sweettracker->getUserId(), str_replace("@", "\@", $id), $tel, $token);
        //if(!$result || $result->code!="success") {
        //	echo '<script type="text/javascript">alert("'.$result->message.'");history.back();</script>';
        //	return;
        //}
        $this->load->library('sweettracker');
        if($cnt > 0) {
            $this->db->query("update cb_wt_send_profile set spf_use='Y', spf_biz_sheet=? where spf_friend=?", array($uploadfiledata['pfi_filename'], $id));
        } else {
            $this->spf_mem_id = $this->member->item('mem_id');
            $this->spf_friend = $id;
            $this->spf_company = $this->member->item('mem_username');
            $this->spf_manager_hp = $tel;
            $this->spf_category = $category;
            $this->spf_licenseno = $licenseNumber;
            $this->spf_biz_sheet = $uploadfiledata['pfi_filename'];
            $this->spf_key = $pf_key;
            $this->spf_key_type = $pf_type;
            $this->spf_datetime = cdate('Y-m-d H:i:s');
            $this->spf_appr_id = $this->sweettracker->userId;
            $this->spf_appr_datetime = cdate('Y-m-d H:i:s');
            $this->db->insert("wt_send_profile", $this);
        }
        if ($this->db->error()['code'] > 0) {
            echo '<script type="text/javascript">alert("저장 오류입니다.");history.back();</script>';
        } else {
            header("Location: /biz/sendprofile/lists");
        }
    }
    
    
    public function take() {
        $mode = $this->input->post("actions");
        if($mode=="sendprofile_save") { $this->sendprofile_save(); return; }
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        // 관리자의 스윗트렉커 아이디를 가져온다.
        $this->load->library('sweettracker');
        $view['admin_sw_id'] = $this->sweettracker->userId;
        
        $view['view']['canonical'] = site_url();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        $view['list'] = $this->sweettracker->sender( $this->sweettracker->userId);
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $view['category'] = $this->sweettracker->get_category();
        
        $layoutconfig = array(
            'path' => 'biz/sendprofile',
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
    
    function remove() {
        $ok = 0; $err = 0; $fail = 0;
        $key = $this->input->post('pf_key');

        /* 스윗트렉커로 전송 */
        $this->load->library('sweettracker');
        $senderKey = array();
        
        if(is_array($key) || is_object($key)) {
            foreach($key as $k) {
                array_push($senderKey, array("senderKey"=>$k));
            }
        } else {
            array_push($senderKey, array("senderKey"=>$key));
        }
        
        $result = $this->sweettracker->sender_delete($senderKey);
        echo '<script type="text/javascript">alert("'.$result[0]->code.'");</script>';
        
        if($result[0]->code!="success") {
            if($result[0]->message=="처리 가능한 상태가 아닙니다." || $result[0]->message=="요청한 데이터가 존재하지 않습니다.") {
                $err++;
                //echo '<script type="text/javascript">alert("'.$result[0]->message.'\n\n프로필을 강제 삭제 합니다.");</script>';
            } else {
                $fail++;
                //echo '<script type="text/javascript">alert("'.$result[0]->message.'");history.back();</script>';
                //return;
            }
        } else {
            $ok++;
        }
        
        if($ok < 1 && $err < 1) {
            echo '<script type="text/javascript">alert("발신프로필 삭제 오류입니다. (오류: '.$fail.'건)");document.location.href="/biz/sendprofile/lists";</script>';
            exit;
        }
        
        foreach($key as $k) {
            if($this->member->item('mem_level')>=100) { $param = array($k); } else { $param = array($k, $this->member->item('mem_id')); }
            $this->db->query("update cb_wt_send_profile set spf_use='N' where spf_key=?".(($this->member->item('mem_level')>=100) ? "" : " and spf_mem_id=?"), $param);
        }
        if ($this->db->error()['code'] > 0) {
            echo '<script type="text/javascript">alert("삭제 오류입니다.");history.back();</script>';
        } else {
            echo '<script type="text/javascript">alert("발신프로필 삭제\n\n성공 : '.$ok.'건, 강제삭제: '.$err.'건, 오류: '.$fail.'건");document.location.href="/biz/sendprofile/lists";</script>';
        }
    }
    
    public function modify()
    {
        $pf_key = $this->input->post('pf_key');
        $sms_sender = $this->input->post('sms_sender');
        if(!$pf_key) {
            echo '{"success": false}';
            exit;
        }
        $this->spf_sms_callback = $sms_sender;
        $this->db->update("wt_send_profile", $this, array("spf_key"=>$pf_key));
        header('Content-Type: application/json');
        if ($this->db->error()['code'] < 1) {
            echo '{"success": true}';
        } else {
            echo '{"success": false}';
        }
    }
    
    public function group()
    {
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
            'path' => 'biz/sendprofile',
            'layout' => 'layout',
            'skin' => 'group',
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
    
    public function plusfriend()
    {
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
            'path' => 'biz/sendprofile',
            'layout' => 'layout',
            'skin' => 'plusfriend',
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
    
    public function sendToken()
    {
        $pf_yid = $this->input->post('pf_yid');
        $phoneNumber = $this->input->post('phoneNumber');
        
        $this->load->library('sweettracker');
        $result = $this->sweettracker->sender_token($pf_yid, $phoneNumber);
        header('Content-Type: application/json');
        echo $result;
    }
	
	public function refresh() 
	{
	    
	    $this->load->library('sweettracker');
		
		$userId = $this->input->post('appr_id');
		$senderKey = $this->input->post('spf_key');

		$result = $this->sweettracker->sender($userId,$senderKey);

		$cnt = $this->Biz_model->get_table_count("cb_wt_send_profile", "spf_key=? ", $result->{'data'}->{'senderKey'});
		 
		if($cnt = 0) {
			$ins = array();
			$ins->spf_mem_id = $this->member->item('mem_id');
			$ins->spf_friend = $result->{'data'}->{'uuid'};
			$ins->spf_company = $this->member->item('mem_username');
			$ins->spf_manager_hp = $this->member->item('mem_phone');
			$ins->spf_category = $result->{'data'}->{'categoryCode'};
			$ins->spf_licenseno = $result->{'data'}->{'licenseNumber'};
			$ins->spf_biz_sheet = null;
			$ins->spf_key = $result->{'data'}->{'senderKey'};
			$ins->spf_key_type = $result->{'data'}->{'senderKeyType'};
			$ins->spf_datetime = $result->{'data'}->{'createdAt'};
			$ins->spf_appr_id = $this->sweettracker->userId;
			$ins->spf_appr_datetime = cdate('Y-m-d H:i:s');
			$this->db->insert("wt_send_profile", $ins);
			$ins_cnt = $this->db->affected_rows();
		} else {
		    //log_message("ERROR", "user id : ".$userId. " / key : ".$senderKey."/".$result->{'data'}->{'status'});
            $this->db->query("update cb_wt_send_profile set spf_status = '".$result->{'data'}->{'status'}."' where spf_key=?", $senderKey);
		}
		header('Content-Type: application/json');
		echo '{"success": true}';
	}
	
}
?>