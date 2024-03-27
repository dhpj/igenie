<?php
class Sendprofile extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Post_file', 'Biz', 'Biz_dhn');

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
         //ib플래그
        $this->load->library(array('querystring', 'dhnkakao', 'dhnkakao_ib'));
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
        //소속 현황
		$view['users'] = $this->Biz_dhn_model->get_sub_manager($this->member->item('mem_id'));
		//발신프로필 전체수
        $view['total_rows'] = $this->Biz_dhn_model->get_partner_profile_count($uid, $where, $param);

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        //발신프로필 현황
		$view['list'] = $this->Biz_dhn_model->get_partner_profile_list($uid, $view['param']['page'] - 1, $view['perpage'], $where, $param);
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
            'path' => 'biz/dhnsendprofile',
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
        //if($mode=="sendprofile_save") { $this->sendprofile_save(); return; }

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        //소속 리스트
		$view['users'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));

		// 관리자의 스윗트렉커 아이디를 가져온다.
        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['member'] = $this->Biz_dhn_model->get_child_member($this->member->item('mem_id'));
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $view['category'] = $this->dhnkakao_ib->category_all();
        }else{
            $view['category'] = $this->dhnkakao->category_all();
        }

        $layoutconfig = array(
            'path' => 'biz/dhnsendprofile',
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

    //발신프로필 등록 > 등록 버튼 클릭시
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
        $mem_id = $this->input->post("mem_id");
        $mem_username = $this->input->post("mem_username");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > id : ". $id .", tel : ". $tel .", category : ". $category .", licenseNumber : ". $licenseNumber .", token : ". $token .", token_code : ". $token_code .", api_code : ". $api_code .", api_data : ". $api_data .", pf_key : ". $pf_key .", mem_id : ". $mem_id .", mem_username : ". $mem_username);

		if(empty($mem_id)) {
		    $mem_id = $this->member->item('mem_id');
		}
		if(empty($mem_username)) {
		    $mem_username = $this->member->item('mem_username');
		}

        if(!$id || !$tel || !$token || !isset($_FILES)) { echo '<script type="text/javascript">alert("입력항목 누락!");history.back();</script>'; exit; }
        // id 중복검사
        //ib플래그
        $cnt = $this->Biz_dhn_model->get_table_count(config_item('ib_profile'), "spf_friend=? and spf_use='Y'", array($id));
        if($cnt > 0) { echo '<script type="text/javascript">alert("이미 등록된 검색아이디 입니다!");history.back();</script>'; exit; }

        $pf_type = "None";
        // 기 등록된 플러스 친구인 경우 프로필 테이블에 없는 값이면 success로 처리
        if($api_data->message=="이미 사용중인 플러스친구 입니다.") {
            //ib플래그
            $old_key = $this->db->query("select spf_key, spf_friend, spf_use from ".config_item('ib_profile')." where spf_friend=?", array($id))->row();
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
        //$this->load->library('wideshot');
        //$result = $this->wideshot->sender_create($this->wideshot->getUserId(), str_replace("@", "\@", $id), $tel, $token);
        //if(!$result || $result->code!="success") {
        //	echo '<script type="text/javascript">alert("'.$result->message.'");history.back();</script>';
        //	return;
        //}
        //$this->load->library('dhnkakao');
        if($cnt > 0) {
            //ib플래그
            $this->db->query("update ".config_item('ib_profile')." set spf_use='Y', spf_biz_sheet=? where spf_friend=?", array($uploadfiledata['pfi_filename'], $id));
        } else {
            $this->spf_friend = $id;
            //$this->spf_mem_id = $this->member->item('mem_id');
            //$this->spf_company = $this->member->item('mem_username');
            $this->spf_mem_id = $mem_id;
            $this->spf_company = $mem_username;
            $this->spf_manager_hp = $tel;
            $this->spf_category = $category;
            $this->spf_licenseno = $licenseNumber;
            $this->spf_biz_sheet = $uploadfiledata['pfi_filename'];
            $this->spf_key = $pf_key;
            $this->spf_key_type = $pf_type;
            $this->spf_datetime = cdate('Y-m-d H:i:s');
            //ib플래그
            if(config_item('ib_flag')=="Y"){
                $this->spf_appr_id = $this->dhnkakao_ib->userId;
            }else{
                $this->spf_appr_id = $this->dhnkakao->userId;
            }
            $this->spf_appr_datetime = cdate('Y-m-d H:i:s');
            //ib플래그
            $this->db->insert(config_item('ib_profile'), $this);
        }
        if ($this->db->error()['code'] > 0) {
            echo '<script type="text/javascript">alert("저장 오류입니다.");history.back();</script>';
        } else {
            header("Location: /biz/dhnsendprofile/lists");
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
        $view['member'] = $this->Biz_dhn_model->get_child_member($this->member->item('mem_id'));
        // 관리자의 스윗트렉커 아이디를 가져온다.
        //$this->load->library('dhnkakao');
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $view['admin_sw_id'] = $this->dhnkakao_ib->userId;
        }else{
            $view['admin_sw_id'] = $this->dhnkakao->userId;
        }

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        //$view['list'] = $this->dhnkakao->sender('');
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $view['category'] = $this->dhnkakao_ib->category_all();
        }else{
            $view['category'] = $this->dhnkakao->category_all();
        }

        $layoutconfig = array(
            'path' => 'biz/dhnsendprofile',
            'layout' => 'layout',
            'skin' => 'take',
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
       // $this->load->library('dhnkakao');
        $senderKey = array();

        if(is_array($key) || is_object($key)) {
            foreach($key as $k) {
                array_push($senderKey, array("senderKey"=>$k));
            }
        } else {
            array_push($senderKey, array("senderKey"=>$key));
        }
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $result = $this->dhnkakao_ib->sender_delete($senderKey);
        }else{
            $result = $this->dhnkakao->sender_delete($senderKey);
        }
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
            //ib플래그
            $this->db->query("update ".config_item('ib_profile')." set spf_use='N' where spf_key=?".(($this->member->item('mem_level')>=100) ? "" : " and spf_mem_id=?"), $param);
        }
        if ($this->db->error()['code'] > 0) {
            echo '<script type="text/javascript">alert("삭제 오류입니다.");history.back();</script>';
        } else {
            echo '<script type="text/javascript">alert("발신프로필 삭제\n\n성공 : '.$ok.'건, 강제삭제: '.$err.'건, 오류: '.$fail.'건");document.location.href="/biz/sendprofile/lists";</script>';
        }
    }

    public function modify()
    {
        $pf_mem_id = $this->input->post('pf_mem_id'); //회원번호 2020-12-09
        $pf_friend = $this->input->post('pf_friend'); //플러스친구 2020-12-09
        $pf_key = $this->input->post('pf_key');
        $sms_sender = $this->input->post('sms_sender');
        if(!$pf_mem_id or !$pf_key) {
            echo '{"success": false}';
            exit;
        }
        $this->spf_sms_callback = $sms_sender;
        if($this->member->item('mem_level') >= 100){ //최고관리자만 2020-12-09
			$this->spf_friend = $pf_friend; //플러스친구
			$this->spf_key = $pf_key; //발신 프로필 키
		}
        //$this->db->update("wt_send_profile_dhn", $this, array("spf_key"=>$pf_key));
        //ib플래그
        $this->db->update(config_item('ib_profile'), $this, array("spf_mem_id"=>$pf_mem_id));
        header('Content-Type: application/json');
        if ($this->db->error()['code'] < 1) {
            echo '{"success": true}';
        } else {
            echo '{"success": false}';
        }
    }

    public function sender_smart() {

        $pf_mem_id = $this->input->post("pf_mem_id");
        $pf_friend = $this->input->post("pf_friend");
        $pf_public = $this->input->post("pf_public");
        $result = array();
        if(!empty($pf_mem_id)&&!empty($pf_friend)){
            $db = $this->load->database("218g", TRUE);


            $sql = "select count(1) as cnt from cb_shop_member_data where smd_mem_id = '".$pf_mem_id."' and smd_from = '133g'";
            $shopcnt = $db->query($sql)->row()->cnt;

            if($shopcnt == 0){
                $result["code"] = "1";
            }else{
                $sql = "select smd_spf_friend, smd_spf_public_id from cb_shop_member_data where smd_mem_id = '".$pf_mem_id."' and smd_from = '133g' limit 1";
                $smd_spf = $db->query($sql)->row();
                $smd_spf_friend= $smd_spf->smd_spf_friend;
                $smd_spf_public_id= $smd_spf->smd_spf_public_id;
                if($smd_spf_friend==""||$smd_spf_friend!=$pf_friend||$smd_spf_public_id!=$pf_public){
                    $mem_data = array();
                    $mem_data['smd_spf_friend'] = $pf_friend;
                    $mem_data['smd_spf_public_id'] = $pf_public;
                    $db->update("cb_shop_member_data", $mem_data, array("smd_mem_id"=>$pf_mem_id, "smd_from"=>"133g"));
                    $result["code"] = "0";
                }else{
                    $result["code"] = "2";
                }
            }
        }else{
            $result["code"] = "3";
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
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

        $param = array();
        //ib플래그
        $sql = "SELECT cwspgd.spg_mem_id, cwspgd.spg_name, cwspgd.spg_pf_key
                from ".config_item('ib_profile_group')." cwspgd where cwspgd.spg_mem_id = 0 and cwspgd.spg_p_mem_id is null";
        $view['grouplist'] = $this->db->query($sql, $param)->result();

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/dhnsendprofile',
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

    public function group_in_profile() {

        $groupMemId = $this->input->post('group_mem_id');
        $groupProfile = $this->input->post('group_profile');
        //log_message("ERROR", "group_in_profile groupMemId : ".$groupMemId);
        //log_message("ERROR", "group_in_profile groupProfile : ".$groupProfile);
        //ib플래그
        $sql = "select cwspd.spf_mem_id, cwspd.spf_friend, cwspd.spf_company, cwspd.spf_key
                from ".config_item('ib_profile_group')." cwspgn
                    left join ".config_item('ib_profile')." cwspd on cwspgn.spg_mem_id = cwspd.spf_mem_id and cwspgn.spg_pf_key = cwspd.spf_key
                where cwspgn.spg_p_mem_id=".$groupMemId." and cwspgn.spg_p_pf_key ='".$groupProfile."'";

        //log_message("ERROR", "group_in_profile sql : ".$sql);

        $result = $this->db->query($sql)->result();

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        $json = str_replace('null', '""', $json);
        //log_message("ERROR", "group_in_profile json : ".$json);
        header('Content-Type: application/json');
        echo $json;
    }

    public function group_out_profile() {
        $groupMemId = $this->input->post('group_mem_id');
        $groupProfile = $this->input->post('group_profile');
        $groupOutProfileSearch = $this->input->post('search_profile');
        $whereSearch = "";

        if (!empty($groupOutProfileSearch)) {
            $whereSearch = " and cwspd.spf_company like '%".$groupOutProfileSearch."%'";
        }

        //log_message("ERROR", "group_in_profile groupMemId : ".$groupMemId);
        //log_message("ERROR", "group_in_profile groupProfile : ".$groupProfile);
        //log_message("ERROR", "group_in_profile groupOutProfileSearch : ".$groupOutProfileSearch);
        //ib플래그
        $sql = "select cwsp.spf_mem_id, cwsp.spf_friend, cwsp.spf_company, cwsp.spf_key
                from (  select *
                        from ".config_item('ib_profile')." cwspd
                            left join ".config_item('ib_profile_group')." cwspgn on cwspd.spf_mem_id = cwspgn.spg_mem_id and cwspd.spf_key = cwspgn.spg_pf_key and cwspgn.spg_p_mem_id=".$groupMemId." and cwspgn.spg_p_pf_key='".$groupProfile."'
                        where cwspd.spf_mem_id <> 0 and cwspd.spf_use = 'Y'and cwspd.spf_status ='A'".$whereSearch.") cwsp
                where cwsp.spg_pf_key is null";

        //log_message("ERROR", "group_in_profile sql : ".$sql);

        $result = $this->db->query($sql)->result();

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        $json = str_replace('null', '""', $json);
        header('Content-Type: application/json');
        echo $json;
    }

    public function group_profile_add()
    {
        $groupMemId = $this->input->post('group_mem_id');
        $groupProfile = $this->input->post('group_profile');
        $profileMemId = $this->input->post('profile_mem_id');
        $profileName = $this->input->post('profile_name');
        $profileKey = $this->input->post('profile_key');

        //log_message("ERROR", "group_profile_add groupMemId : ".$groupMemId);
        //log_message("ERROR", "group_profile_add groupProfile : ".$groupProfile);
        //log_message("ERROR", "group_profile_add profileMemId : ".$profileMemId);
        //log_message("ERROR", "group_profile_add profileName : ".$profileName);
        //log_message("ERROR", "group_profile_add profileKey : ".$profileKey);

        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $json = $this->dhnkakao_ib->sender_group_add($groupProfile, $profileKey);
        }else{
            $json = $this->dhnkakao->sender_group_add($groupProfile, $profileKey);
        }
        //log_message("ERROR", "group_profile_add result->code : ".$json->code);
        //$json = json_decode($result);

        if($json->code == "200") {
            //ib플래그
            $orderMaxSql = "select IFNULL(max(spg_p_order), 0) + 1 maxorder from ".config_item('ib_profile_group')." where spg_pf_key ='".$profileKey."'";
            $orderMax = $this->db->query($orderMaxSql)->row()->maxorder;

            $ins = array();
            $ins["spg_mem_id"] = $profileMemId;
            $ins["spg_name"] = $profileName;
            $ins["spg_datetime"] = cdate('Y-m-d H:i:s');
            $ins["spg_pf_key"] = $profileKey;
            $ins["spg_p_mem_id"] = $groupMemId;
            $ins["spg_p_pf_key"] = $groupProfile;
            $ins["spg_p_order"] = $orderMax;
            //ib플래그
            $this->db->replace(config_item('ib_profile_group'), $ins);

            $result =  '{"code":"200","message":"그룹에 추가 되었습니다."}';
        } else {
            $result =  '{"code":"2001","message":"그룹에 추가하지 못했습니다."}';
        }
        //log_message("ERROR", "group_profile_add json : ".$json);
        //log_message("ERROR", "group_profile_add result : ".$result);

        header('Content-Type: application/json');
        echo $result;
    }

    public function group_profile_remove()
    {
        $groupMemId = $this->input->post('group_mem_id');
        $groupProfile = $this->input->post('group_profile');
        $profileMemId = $this->input->post('profile_mem_id');
        $profileName = $this->input->post('profile_name');
        $profileKey = $this->input->post('profile_key');

        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $json = $this->dhnkakao_ib->sender_group_remove($groupProfile, $profileKey);
        }else{
            $json = $this->dhnkakao->sender_group_remove($groupProfile, $profileKey);
        }
        //$json = json_decode($result);
        //log_message("ERROR", "group_profile_remove result->code : ".$json->code);

        if($json->code == "200") {
            //ib플래그
            $deleteSql = "delete from ".config_item('ib_profile_group')." where spg_pf_key='".$profileKey."' AND spg_p_pf_key='".$groupProfile."';";
            $this->db->query($deleteSql);

            //$result =  '{"code":"200","message":"그룹에서 삭제되었습니다."}';

            if ($this->db->error()['code'] < 1) {
                $result =  '{"code":"200","message":"그룹에서 삭제되었습니다."}';
            } else {
                $result =  '{"code":"2001","message":"그룹에서 삭제시 오류가 발생하였습니다."}';
            }
        } else {
            $result =  '{"code":"2002","message":"그룹에서 삭제할수 없습니다."}';
        }

        header('Content-Type: application/json');
        echo $result;
    }

    public function takegroup() {
        $mode = $this->input->post("actions");
        if($mode=="sendprofile_save") { $this->sendprofile_save(); return; }

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['member'] = $this->Biz_dhn_model->get_child_member($this->member->item('mem_id'));
        // 관리자의 스윗트렉커 아이디를 가져온다.
        //$this->load->library('dhnkakao');
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $view['admin_sw_id'] = $this->dhnkakao_ib->userId;
        }else{
            $view['admin_sw_id'] = $this->dhnkakao->userId;
        }

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        //$view['list'] = $this->dhnkakao->sender('');
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $view['category'] = $this->dhnkakao_ib->category_all();
        }else{
            $view['category'] = $this->dhnkakao->category_all();
        }

        $layoutconfig = array(
            'path' => 'biz/dhnsendprofile',
            'layout' => 'layout',
            'skin' => 'takegroup',
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
            'path' => 'biz/dhnsendprofile',
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

    //발신프로필 등록 > 괄리자 휴대전화번호 > 인증번호 전송
	public function sendToken()
    {
        $pf_yid = $this->input->post('pf_yid');
        $phoneNumber = $this->input->post('phoneNumber');
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > pf_yid : ". $pf_yid .", phoneNumber : ". $phoneNumber);
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $result = $this->dhnkakao_ib->sender_token($pf_yid, $phoneNumber);
        }else{
            $result = $this->dhnkakao->sender_token($pf_yid, $phoneNumber);
        }
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ". $result);
        header('Content-Type: application/json');
        echo $result;
    }

    //발신프로필 등록 > 프로필 생성
	public function senderCreate()
    {
        $yellowId = $this->input->post('yellowId');
        $phoneNumber = $this->input->post('phoneNumber');
        $token = $this->input->post('token');
        $categoryCode = $this->input->post('categoryCode');
        $mem_id = $this->input->post("mem_id");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > yellowId : ". $yellowId .", phoneNumber : ". $phoneNumber .", token : ". $token .", categoryCode : ". $categoryCode .", mem_id : ". $mem_id);
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $result = $this->dhnkakao_ib->sender_create($yellowId, $phoneNumber,$categoryCode,$token);
        }else{
            $result = $this->dhnkakao->sender_create($yellowId, $phoneNumber,$categoryCode,$token);
        }
        $json = json_decode($result);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > json->code : ". $json->code);
        if($json->code == "200" && !empty($json->data)) {
           if(empty($mem_id)) {
                $mem_id = $this->member->item('mem_id');
            }
            $sql = "select * from cb_member cm where cm.mem_id = '".$mem_id."'";
            $m = $this->db->query($sql)->row();
            $ins = array();
            $ins["spf_mem_id"] = $m->mem_id;
            $ins["spf_friend"] = $json->data->uuid;
            $ins["spf_company"] = $m->mem_username;
            $ins["spf_manager_hp"] = $m->mem_phone;
            $ins["spf_category"] = $json->data->categoryCode;
            $ins["spf_licenseno"] = $json->data->licenseNumber;
            $ins["spf_biz_sheet"] = null;
            $ins["spf_key"] = $json->data->senderKey;
            $ins["spf_key_type"] = $json->data->senderKeyType;
            $ins["spf_datetime"] = $json->data->createdAt;
            $ins["spf_sms_callback"] = $m->mem_phone;
            //ib플래그
            if(config_item('ib_flag')=="Y"){
                $ins["spf_appr_id"] = $this->dhnkakao_ib->userId;
            }else{
                $ins["spf_appr_id"] = $this->dhnkakao->userId;
            }
            $ins["spf_appr_datetime"] = cdate('Y-m-d H:i:s');
            //ib플래그
            $this->db->insert(config_item('ib_profile'), $ins);
            $result =  '{"code":"200","message":"등록이 완료 되었습니다."}';
        }
        header('Content-Type: application/json');
        echo $result;
    }

	public function refresh()
	{

	    //$this->load->library('dhnkakao');
		//log_message("error", "refresh()");

		$userId = $this->input->post('appr_id');
		$senderKey = $this->input->post('senderKey');
		$mem_id = $this->input->post('mem_id');
        //ib플래그
        if(config_item('ib_flag')=="Y"){
    		$result = $this->dhnkakao_ib->sender($senderKey);
        }else{
            $result = $this->dhnkakao->sender($senderKey);
        }

		//log_message("ERROR", $result);
		$json = json_decode($result);

		if($json->code == "200" &&!empty($json->data)) {

		    if(empty($mem_id)) {
		        $mem_id = $this->member->item('mem_id');
		    }

		    $sql = "select * from cb_member cm where cm.mem_id = '".$mem_id."'";
		    //log_message("ERROR", $sql);
		    $m = $this->db->query($sql)->row();

		    $ins = array();
		    $ins["spf_mem_id"] = $m->mem_id;
		    $ins["spf_friend"] = $json->data->uuid;
		    $ins["spf_company"] = $m->mem_username;
		    $ins["spf_manager_hp"] = $m->mem_phone;
		    $ins["spf_category"] = $json->data->categoryCode;
		    $ins["spf_licenseno"] = $json->data->licenseNumber;
		    $ins["spf_biz_sheet"] = null;
		    $ins["spf_key"] = $json->data->senderKey;
		    $ins["spf_key_type"] = $json->data->senderKeyType;
		    $ins["spf_datetime"] = $json->data->createdAt;
		    $ins["spf_sms_callback"] = $m->mem_phone;
            //ib플래그
            if(config_item('ib_flag')=="Y"){
    		    $ins["spf_appr_id"] = $this->dhnkakao_ib->userId;
            }else{
                $ins["spf_appr_id"] = $this->dhnkakao->userId;
            }
		    $ins["spf_appr_datetime"] = cdate('Y-m-d H:i:s');
            //ib플래그
		    $this->db->replace(config_item('ib_profile'), $ins);
		    $result =  '{"code":"200","message":"등록이 완료 되었습니다."}';
		}

    	header('Content-Type: application/json');
		echo '{"code": true}';
	}

    function grouplists(){
      $cache_limiter = session_cache_limiter();
      //log_message('ERROR', 'List 시작'.$cache_limiter);

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

      $param = array();
      $sql = "SELECT cwspgd.spg_mem_id, cwspgd.spg_name, cwspgd.spg_pf_key
              from cb_wt_send_profile_group_ib cwspgd where cwspgd.spg_p_mem_id is NULL";
      $view['grouplist'] = $this->db->query($sql, $param)->result();
      $view['total_rows'] = count($view['grouplist']);

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
          'skin' => 'grouplists',
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

  public function group_profile_add2()
    {
        $groupMemId = $this->input->post('group_mem_id');
        $groupProfile = $this->input->post('group_profile');
        $profileMemId = $this->input->post('profile_mem_id');
        $profileName = $this->input->post('profile_name');
        $profileKey = $this->input->post('profile_key');

        // log_message("ERROR", "group_profile_add groupMemId : ".$groupMemId);
        // log_message("ERROR", "group_profile_add groupProfile : ".$groupProfile);
        // log_message("ERROR", "group_profile_add profileMemId : ".$profileMemId);
        // log_message("ERROR", "group_profile_add profileName : ".$profileName);
        // log_message("ERROR", "group_profile_add profileKey : ".$profileKey);


        $json = $this->dhnkakao_ib->sender_group_add($groupProfile, $profileKey);
        log_message("ERROR", "group_profile_add result->code : ".$json->code);
        //$json = json_decode($result);

        if($json->code == "200") {
            $orderMaxSql = "select IFNULL(max(spg_p_order), 0) + 1 maxorder from cb_wt_send_profile_group_ib where spg_pf_key ='".$profileKey."'";
            $orderMax = $this->db->query($orderMaxSql)->row()->maxorder;

            $ins = array();
            $ins["spg_mem_id"] = $profileMemId;
            $ins["spg_name"] = $profileName;
            $ins["spg_datetime"] = cdate('Y-m-d H:i:s');
            $ins["spg_pf_key"] = $profileKey;
            $ins["spg_p_mem_id"] = $groupMemId;
            $ins["spg_p_pf_key"] = $groupProfile;
            $ins["spg_p_order"] = $orderMax;
            $this->db->replace("cb_wt_send_profile_group_ib", $ins);

            $result =  '{"code":"200","message":"그룹에 추가 되었습니다."}';
        } else {
            $flag = false;
            $json = $this->dhnkakao_ib->group($groupProfile);
            foreach($json->data as $group2){
                if ($group2->senderKey == $profileKey){
                    $orderMaxSql = "select IFNULL(max(spg_p_order), 0) + 1 maxorder from cb_wt_send_profile_group_ib where spg_pf_key ='".$profileKey."'";
                    $orderMax = $this->db->query($orderMaxSql)->row()->maxorder;

                    $ins = array();
                    $ins["spg_mem_id"] = $profileMemId;
                    $ins["spg_name"] = $profileName;
                    $ins["spg_datetime"] = cdate('Y-m-d H:i:s');
                    $ins["spg_pf_key"] = $profileKey;
                    $ins["spg_p_mem_id"] = $groupMemId;
                    $ins["spg_p_pf_key"] = $groupProfile;
                    $ins["spg_p_order"] = $orderMax;
                    $this->db->replace("cb_wt_send_profile_group_ib", $ins);

                    $result =  '{"code":"200","message":"그룹에 추가 되었습니다."}';
                    $flag = true;
                }
            }
            if(!$flag){
                $result =  '{"code":"2001","message":"그룹에 추가하지 못했습니다."}';
            }
        }
        //log_message("ERROR", "group_profile_add json : ".$json);
        //log_message("ERROR", "group_profile_add result : ".$result);

        header('Content-Type: application/json');
        echo $result;
    }

    public function group_profile_remove2()
    {
        $groupMemId = $this->input->post('group_mem_id');
        $groupProfile = $this->input->post('group_profile');
        $profileMemId = $this->input->post('profile_mem_id');
        $profileName = $this->input->post('profile_name');
        $profileKey = $this->input->post('profile_key');

        // $json = $this->dhnkakao_ib->sender_group_remove("4e546c8c5b7a904a1c7926e8a8487f4c18cc377b", "026f2151bbf5f3d99fe7df1f2830536084de8cfc");
        // return;
        $json = $this->dhnkakao_ib->sender_group_remove($groupProfile, $profileKey);
        // $json = $this->dhnkakao->sender_group_remove("01341cd3aee55b35e8d078049dfd2da31bd9c294", "8afef0aaf66ce5f0ce399a6ced2910e895e28b9b");
        // return;
        //$json = json_decode($result);
        //log_message("ERROR", "group_profile_remove result->code : ".$json->code);

        if($json->code == "200") {

            $deleteSql = "delete from cb_wt_send_profile_group_ib where spg_pf_key='".$profileKey."' AND spg_p_pf_key='".$groupProfile."';";
            $this->db->query($deleteSql);

            //$result =  '{"code":"200","message":"그룹에서 삭제되었습니다."}';

            if ($this->db->error()['code'] < 1) {
                $result =  '{"code":"200","message":"그룹에서 삭제되었습니다."}';
            } else {
                $result =  '{"code":"2001","message":"그룹에서 삭제시 오류가 발생하였습니다."}';
            }
        } else {
            $result =  '{"code":"2002","message":"그룹에서 삭제할수 없습니다."}';
        }

        header('Content-Type: application/json');
        echo $result;
    }

  public function view_insert_group() {
      $mode = $this->input->post("actions");
      if($mode=="sendprofile_save") { $this->sendprofile_save(); return; }

      // 이벤트 라이브러리를 로딩합니다
      $eventname = 'event_main_index';
      $this->load->event($eventname);

      $view = array();
      $view['view'] = array();

      // 이벤트가 존재하면 실행합니다
      $view['view']['event']['before'] = Events::trigger('before', $eventname);
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
          'skin' => 'view_insert_group',
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

  public function save_group_profile(){
      $group_name = $this->input->post('groupname');
      $group_profile_key = $this->input->post('senderKey');

      $orderMaxSql = "select IFNULL(max(spg_p_order), 0) + 1 maxorder from cb_wt_send_profile_group_ib where spg_pf_key ='".$group_profile_key."'";
      $orderMax = $this->db->query($orderMaxSql)->row()->maxorder;




      if ($this->db->error()['code'] < 1) {
          $result =  '{"code":"200","message":"그룹에서 삭제되었습니다."}';
      } else {
          $result =  '{"code":"2001","message":"그룹에서 삭제시 오류가 발생하였습니다."}';
      }

      header('Content-Type: application/json');
      echo $result;
  }

  public function group_out_profile2() {
      $groupMemId = $this->input->post('group_mem_id');
      $groupProfile = $this->input->post('group_profile');
      $groupOutProfileSearch = $this->input->post('search_profile');
      $whereSearch = "";

      if (!empty($groupOutProfileSearch)) {
          $whereSearch = " and cwspd.spf_company like '%".$groupOutProfileSearch."%'";
      }

      //log_message("ERROR", "group_in_profile groupMemId : ".$groupMemId);
      //log_message("ERROR", "group_in_profile groupProfile : ".$groupProfile);
      //log_message("ERROR", "group_in_profile groupOutProfileSearch : ".$groupOutProfileSearch);

      $sql = "select cwsp.spf_mem_id, cwsp.spf_friend, cwsp.spf_company, cwsp.spf_key
              from (  select *
                      from cb_wt_send_profile_ib cwspd
                          left join cb_wt_send_profile_group_ib cwspgn on cwspd.spf_mem_id = cwspgn.spg_mem_id and cwspd.spf_key = cwspgn.spg_pf_key and cwspgn.spg_p_mem_id=".$groupMemId." and cwspgn.spg_p_pf_key='".$groupProfile."'
                      where cwspd.spf_mem_id <> 0 and cwspd.spf_use = 'Y'and cwspd.spf_status ='A'".$whereSearch.") cwsp
              where cwsp.spg_pf_key is null";

      //log_message("ERROR", "group_in_profile sql : ".$sql);

      $result = $this->db->query($sql)->result();

      $json = json_encode($result,JSON_UNESCAPED_UNICODE);
      $json = str_replace('null', '""', $json);
      header('Content-Type: application/json');
      echo $json;
  }
  public function group_in_profile2() {

        $groupMemId = $this->input->post('group_mem_id');
        $groupProfile = $this->input->post('group_profile');
        //log_message("ERROR", "group_in_profile groupMemId : ".$groupMemId);
        //log_message("ERROR", "group_in_profile groupProfile : ".$groupProfile);

        $sql = "select cwspd.spf_mem_id, cwspd.spf_friend, cwspd.spf_company, cwspd.spf_key
                from cb_wt_send_profile_group_ib cwspgn
                    left join cb_wt_send_profile_ib cwspd on cwspgn.spg_mem_id = cwspd.spf_mem_id and cwspgn.spg_pf_key = cwspd.spf_key
                where cwspgn.spg_p_mem_id=".$groupMemId." and cwspgn.spg_p_pf_key ='".$groupProfile."'";

        //log_message("ERROR", "group_in_profile sql : ".$sql);

        $result = $this->db->query($sql)->result();

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        $json = str_replace('null', '""', $json);
        //log_message("ERROR", "group_in_profile json : ".$json);
        header('Content-Type: application/json');
        echo $json;
    }

  public function group_refresh()
  {
      log_message("ERROR", "group_refresh : Start");
      $groupMemId = $this->input->post('group_mem_id');
      $groupProfileName = trim($this->input->post('group_profile_name'));
      $groupProfileKey = trim($this->input->post('group_profile_key'));
      $isGroup = false;
      $result = 503;
      $message = "이미 그룹이 등록되어 있습니다.";

      $groupCntSql = "select count(*) group_cnt from cb_wt_send_profile_group_ib cwspgd where spg_mem_id=".$groupMemId." and spg_pf_key='".$groupProfileKey."'";
      $profileCntSql = "select count(*) profile_cnt from cb_wt_send_profile_ib cwspd where spf_mem_id=".$groupMemId." and spf_key='".$groupProfileKey."'";

      $groupCount = $this->db->query($groupCntSql)->row()->group_cnt;
      $profileCount = $this->db->query($profileCntSql)->row()->profile_cnt;

      log_message("ERROR", "group_refresh groupCount : ".$groupCount);
      log_message("ERROR", "group_refresh profileCount : ".$profileCount);

      if ($groupCount == 0 && $profileCount == 0) {
          $result = 508;
          $message = "그룹이 존재하지 않습니다.";
          $json = $this->dhnkakao_ib->group_list();

          log_message("ERROR", "group_refresh json : ".count($json->data));
          log_message("ERROR", "group_refresh json groupKey : ".$json->data[0]->groupKey);
          log_message("ERROR", "group_refresh json name : ".$json->data[0]->name);
          //$json = json_decode($result);

          foreach($json->data as $group) {
              $item_groupKey = $group->groupKey;
              $item_groupName = $group->name;
              $item_createdAt = $group->createdAt;

              if ($groupProfileName == $item_groupName && $groupProfileKey == $item_groupKey) {
                  $groupins = array();
                  $profileins = array();

                  $groupins["spg_mem_id"] = $groupMemId;
                  $groupins["spg_name"] = "[그룹] ".$groupProfileName;
                  $groupins["spg_datetime"] = $item_createdAt;
                  $groupins["spg_pf_key"] = $groupProfileKey;
                  $groupins["spg_p_mem_id"] = null;
                  $groupins["spg_p_pf_key"] = "";
                  $groupins["spg_p_order"] = "1";
                  $this->db->insert("cb_wt_send_profile_group_ib", $groupins);

                  $profileins["spf_mem_id"] = $groupMemId;
                  $profileins["spf_friend"] = $item_groupName;
                  $profileins["spf_company"] = "[그룹] ".$groupProfileName;
                  $profileins["spf_manager_hp"] = "01076694556";
                  $profileins["spf_category"] = null;
                  $profileins["spf_licenseno"] = null;
                  $profileins["spf_biz_sheet"] = "";
                  $profileins["spf_key"] = $groupProfileKey;
                  $profileins["spf_key_type"] = "G";
                  $profileins["spf_datetime"] = $item_createdAt;
                  $profileins["spf_sms_callback"] = "";
                  $profileins["spf_appr_id"] = $this->dhnkakao_ib->userId;
                  $profileins["spf_appr_datetime"] = cdate('Y-m-d H:i:s');
                  $this->db->insert("wt_send_profile_ib", $profileins);

                  $isGroup = true;
                  $result = 200;
                  $message = "그룹 가져오기를 성공하였습니다.";
                  break;
              }
          }
      }
      log_message("ERROR", "group_refresh : End");
      header('Content-Type: application/json');
      echo '{"code": "'.$result.'", "message": "'.$message.'"}';
  }

    public function search_group_list()
    {
        $json = $this->dhnkakao_ib->group_list();
        log_message("error", $json->data);
        if (!empty($json->data)){
            foreach($json->data as $group) {
                // log_message("error", $group->groupKey);
              // log_message("error", $group->name);
                // log_message("error", $group->createdAt);
                log_message("error", "group : " . $group->groupKey);
                $json2 = $this->dhnkakao_ib->group($group->groupKey);
                foreach($json2->data as $group2){
                    log_message("error", $group2->name . "  /  " . $group2->senderKey);
                }
            }
        }

        $result =  '{"code":"200","message":"그룹에 추가 되었습니다."}';

        header('Content-Type: application/json');
        echo $result;
    }
}
?>
