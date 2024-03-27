<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Deposit class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 예치금 페이지를 보여주는 controller 입니다.
 */
class Deposit extends CB_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Deposit', 'Biz', 'Biz_dhn', 'Unique_id');

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'cmall');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'Deposit_model';

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('pagination', 'querystring', 'depositlib'));

        if ( ! $this->depositconfig->item('use_deposit')) {
            alert('이 웹사이트는 ' . html_escape($this->depositconfig->item('deposit_name')) . ' 기능을 사용하지 않습니다');
            return;
        }

		//echo "PHP 버전 : ". PHP_VERSION_ID ."<br>";
		//echo "코드이그나이터 버전 : ". CI_VERSION ."<br>";

		//크롬 브라우저에서 결제 후, 메인 화면으로 튕기는 현상 대응 2020-12-02
		/*
		if(!function_exists('session_start_samesite')) {
			function session_start_samesite($options = array()){
				$res = @session_start($options);
				// IE 브라우저 또는 엣지브라우저 일때는 secure; SameSite=None 을 설정하지 않습니다.
				if( preg_match('/Edge/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~Trident/7.0(; Touch)?; rv:11.0~',$_SERVER['HTTP_USER_AGENT']) ){
					return $res;
				}
				$headers = headers_list();
				krsort($headers);
				foreach ($headers as $header) {
					if (!preg_match('~^Set-Cookie: PHPSESSID=~', $header)) continue;
					$header = preg_replace('~; secure(; HttpOnly)?$~', '', $header) . '; sameSite=none'; //sameSite=none; Secure로 설정되어 있기 때문에, 로컬에 https 설정이 안 되어 있으면 ​Secure를 빼야합니다.
					header($header, false);
					break;
				}
				return $res;
			}
		}
		session_start_samesite();
		*/
		/*
		header('Set-Cookie: same-site-cookie=foo; SameSite=Lax');
		//header('Set-Cookie: cross-site-cookie=bar; SameSite=None; Secure');
		header('Set-Cookie: cross-site-cookie=bar; SameSite=None;');
		setcookie('ci_session', $_COOKIE['ci_session'], 0, '/; samesite=strict');
		setcookie('csrf_cookie_name', $_COOKIE['csrf_cookie_name'], 0, '/; samesite=strict');
		setcookie('user_ip', $_COOKIE['user_ip'], 0, '/; samesite=strict');
		//echo "_COOKIE['ci_session'] : ". $_COOKIE['ci_session'] ."<br>";
		//echo "_COOKIE['csrf_cookie_name'] : ". $_COOKIE['csrf_cookie_name'] ."<br>";
		//echo "_COOKIE['user_ip'] : ". $_COOKIE['user_ip'] ."<br>";
		*/
    }

    /**
     * 예치금 페이지를 보여주는 함수입니다
     */
    public function index()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_deposit_index';
        $this->load->event($eventname);

		//log_message("ERROR", "/deposit > index");

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

		$mem_payment_bank = $this->member->item("mem_payment_bank"); //계좌이체(레드뱅킹) 결재 사용여부
		$mem_payment_vbank = $this->member->item("mem_payment_vbank"); //무통장입금(가상계좌) 결재 사용여부
		//echo "bank : ". $mem_payment_bank .", vbank : ". $mem_payment_vbank ."<br>";

		if($mem_payment_bank == "Y"){ //계좌이체(레드뱅킹) 결재의 경우
			redirect('deposit/redbank'); //계좌이체(레드뱅킹) 안내 페이지로 이동
		}

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $cashtodep = array();
        $cashtodeposit = $this->depositconfig->item('deposit_cash_to_deposit_unit');
        $cashtodeposit = preg_replace("/[\r|\n|\r\n]+/", ',', $cashtodeposit);
        $cashtodeposit = preg_replace("/\s+/", '', $cashtodeposit);
        if ($cashtodeposit) {
            $cashtodeposit = explode(',', trim($cashtodeposit, ','));
            $cashtodeposit = array_unique($cashtodeposit);
            if ($cashtodeposit) {
                foreach ($cashtodeposit as $key => $val) {
                    $cashtodep[] = explode(':', $val);
                }
            }
        }
        if (empty($cashtodep)) {
            alert('충전 목록이 존재하지 않습니다');
            return;
        }
        $view['view']['cashtodep'] = $cashtodep;

        $this->load->model('Unique_id_model');
        $unique_id = $this->Unique_id_model->get_id($this->input->ip_address());
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ip_address : ". $this->input->ip_address() .", unique_id : ". $unique_id);
		//echo $_SERVER['REQUEST_URI'] ." > ip_address : ". $this->input->ip_address() .", unique_id : ". $unique_id ."<br>";
        $view['view']['unique_id'] = $unique_id;
        $view['view']['good_name'] = $this->depositconfig->item('deposit_name') . ' 충전';
        $this->session->set_userdata(
            'unique_id',
            $unique_id
        );
		//echo $_SERVER['REQUEST_URI'] ." > unique_id : ". $this->session->userdata['unique_id'] ."<br>";

        $view['view']['use_pg'] = $use_pg = false;
        if ($this->cbconfig->item('use_payment_card')
            OR $this->cbconfig->item('use_payment_realtime')
            OR $this->cbconfig->item('use_payment_vbank')
            OR $this->cbconfig->item('use_payment_phone')) {
            $view['view']['use_pg'] = $use_pg = true;
        }

        if ($this->cbconfig->item('use_payment_pg') === 'kcp' && $use_pg) {
            $this->load->library('paymentlib');
            $view['view']['pg'] = $this->paymentlib->kcp_init();
            /*      //삭제예정
            if ($this->cbconfig->get_device_type() !== 'mobile') {
                $view['view']['body_script'] = 'onLoad="CheckPayplusInstall();"';
            }
            */
        }

        if ($this->cbconfig->item('use_payment_pg') === 'lg' && $use_pg) {
            $this->load->library('paymentlib');
            $view['view']['pg'] = $this->paymentlib->lg_init();
            /*      //삭제예정
            if ($this->cbconfig->get_device_type() !== 'mobile') {
                $view['view']['body_script'] = 'onload="isActiveXOK();"';
            }
            */
        }

        if ($this->cbconfig->item('use_payment_pg') === 'inicis' && $use_pg) {
            $this->load->library('paymentlib');
            $view['view']['pg'] = $this->paymentlib->inicis_init('deposit');
            /*      //삭제예정
            if ($this->cbconfig->get_device_type() !== 'mobile') {
                $view['view']['body_script'] = 'onload="enable_click();"';
            }
            */
        }

        $view['view']['ptype'] = 'deposit';

        $view['view']['form1name'] = ($this->cbconfig->get_device_type() === 'mobile')
            ? 'mform_1' : 'form_1';
        $view['view']['form2name'] = ($this->cbconfig->get_device_type() === 'mobile')
            ? 'mform_2' : 'form_2';
        $view['view']['form3name'] = ($this->cbconfig->get_device_type() === 'mobile')
            ? 'mform_3' : 'form_3';
        $view['view']['form4name'] = ($this->cbconfig->get_device_type() === 'mobile')
            ? 'mform_4' : 'form_4';

        $where = array(
            'mem_id' => $this->member->item('mem_id'),
            'dep_status' => 1,
        );
        $this->load->model('Deposit_model');
        $view['view']['list'] = $this->Deposit_model
            ->get('', '', $where, '7', 0, 'dep_id', 'DESC');
        $view['view']['count'] = $this->Deposit_model->count_by($where);

        $view['view']['canonical'] = site_url('deposit');

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->depositconfig->item('site_meta_title_deposit');
        $meta_description = $this->depositconfig->item('site_meta_description_deposit');
        $meta_keywords = $this->depositconfig->item('site_meta_keywords_deposit');
        $meta_author = $this->depositconfig->item('site_meta_author_deposit');
        $page_name = $this->depositconfig->item('site_page_name_deposit');

        $layoutconfig = array(
            'path' => 'deposit',
            'layout' => 'layout',
            'skin' => 'deposit',
            'layout_dir' => $this->depositconfig->item('layout_deposit'),
            'mobile_layout_dir' => $this->depositconfig->item('mobile_layout_deposit'),
            'use_sidebar' => $this->depositconfig->item('sidebar_deposit'),
            'use_mobile_sidebar' => $this->depositconfig->item('mobile_sidebar_deposit'),
            'skin_dir' => $this->depositconfig->item('skin_deposit'),
            'mobile_skin_dir' => $this->depositconfig->item('mobile_skin_deposit'),
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



    /**
     * 레드뱅킹 안내 페이지를 보여주는 함수입니다
     */
    public function redbank()
    {
		/**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $view = array();
        $view['view'] = array();

        // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE ( cmr.mrg_recommend_mem_id = 3 or cmr.mrg_recommend_mem_id = 1260) and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
		// $view['eve_cnt'] = $this->db->query($sql)->row()->cnt;
        //
        // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE cmr.mrg_recommend_mem_id = 962 and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
		// $view['eve_cnt2'] = $this->db->query($sql)->row()->cnt;

        $where_mem1 = "";
        $view["eve_cnt"] = 0;
        if(!empty(config_item('eve1_member'))){
            foreach(config_item('eve1_member') as $r){
                if($where_mem1!=""){
                    $where_mem1.=",";
                }
                $where_mem1.=$r;
            }
            if(!empty($where_mem1)){
                $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE  cmr.mrg_recommend_mem_id in ( ".$where_mem1." ) and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql." / mem_id > ".$mem_id);
                $view["eve_cnt"] = $this->db->query($sql)->row()->cnt;
            }
        }
        $where_mem2 = "";
        $view["eve_cnt2"] = 0;
        if(!empty(config_item('eve2_member'))){
            foreach(config_item('eve2_member') as $r){
                if($where_mem2!=""){
                    $where_mem2.=",";
                }
                $where_mem2.=$r;
            }
            if(!empty($where_mem2)){
                $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE  cmr.mrg_recommend_mem_id in ( ".$where_mem2." ) and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql." / mem_id > ".$mem_id);
                $view["eve_cnt2"] = $this->db->query($sql)->row()->cnt;
            }
        }

		/**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->depositconfig->item('site_meta_title_deposit');
        $meta_description = $this->depositconfig->item('site_meta_description_deposit');
        $meta_keywords = $this->depositconfig->item('site_meta_keywords_deposit');
        $meta_author = $this->depositconfig->item('site_meta_author_deposit');
        $page_name = $this->depositconfig->item('site_page_name_deposit');

        $layoutconfig = array(
            'path' => 'deposit',
            'layout' => 'layout',
            'skin' => 'redbank',
            'layout_dir' => $this->depositconfig->item('layout_deposit'),
            'mobile_layout_dir' => $this->depositconfig->item('mobile_layout_deposit'),
            'use_sidebar' => $this->depositconfig->item('sidebar_deposit'),
            'use_mobile_sidebar' => $this->depositconfig->item('mobile_sidebar_deposit'),
            'skin_dir' => $this->depositconfig->item('skin_deposit'),
            'mobile_skin_dir' => $this->depositconfig->item('mobile_skin_deposit'),
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


    //이벤트 광고페이지 추가  2021-10-06 윤재박
    public function eventpage()
    {
		/**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();



        $view = array();
        $view['view'] = array();


        //이벤트 가능업체 여부
        $where_mem1 = "";
        $view['eve_cnt'] = 0;
        if(!empty(config_item('eve1_member'))){
            foreach(config_item('eve1_member') as $r){
                if($where_mem1!=""){
                    $where_mem1.=",";
                }
                $where_mem1.=$r;
            }
            if(!empty($where_mem1)){
                $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE  cmr.mrg_recommend_mem_id in ( ".$where_mem1." ) and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql." / mem_id > ".$mem_id);
                $view['eve_cnt'] = $this->db->query($sql)->row()->cnt;
            }
        }

        $eve1_flag="N";
        if(!empty(config_item('eve1_member'))){
            foreach(config_item('eve1_member') as $r){
                if($r==$this->member->item("mem_id")){
                    $eve1_flag="Y";
                    $view['eve_cnt'] = 1;
                    break;
                }
            }
        }

        // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE ( cmr.mrg_recommend_mem_id = 3 or cmr.mrg_recommend_mem_id = 1260 or cmr.mrg_recommend_mem_id = 962) and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
		// $view['eve_cnt'] = $this->db->query($sql)->row()->cnt;
        // log_message('ERROR', 'eve_cnt > '.$eve_cnt);
        $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE cmr.mrg_recommend_mem_id = 962 and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
		$view['eve_cnt2'] = $this->db->query($sql)->row()->cnt;


        // log_message('ERROR', 'eve_cnt > '.$eve_cnt);
        // if($view['eve_cnt']==0&&($this->member->item("mem_id")!='2'&&$this->member->item("mem_id")!='3'&&$this->member->item("mem_id")!='1260'&&$this->member->item("mem_id")!='962')){
        if($view['eve_cnt']==0&&$eve1_flag=="N"){
            // if($this->member->item("mem_id")!='3'){
                redirect($_SERVER['HTTP_REFERER']);
            // }


        }else{
            $nowTime = date("Y-m-d H:i:s"); // 현재 시간
            // if($view['eve_cnt'] > 0||$this->member->item("mem_id")=='2'||$this->member->item("mem_id")=='3'||$this->member->item("mem_id")=='1260'){ //dhn
            if($view['eve_cnt'] > 0||$eve1_flag=="Y"){ //dhn
                $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime1'))); // 시작 시간
                // $startTime = date("Y-m-d H:i:s", strtotime('2022-11-09 00:00:00')); // 시작 시간
                $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime1'))); // 종료 시간
            }else{ //티지
                // $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime2'))); // 시작 시간
                $startTime = date("Y-m-d H:i:s", strtotime('2022-08-05 00:00:00')); // 시작 시간
                $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime2'))); // 종료 시간
            }

            if($nowTime < $expTime && $nowTime >= $startTime){

            }else{
                $in_ip = $_SERVER['REMOTE_ADDR'];
                if($in_ip!="61.75.230.209"){
                    redirect($_SERVER['HTTP_REFERER']);
                }
                // }else{
                //     $view['eve_cnt'] = 1;
                // }
                // if($this->member->item("mem_id")!='3'){

                // }
            }
        }

		/**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->depositconfig->item('site_meta_title_deposit');
        $meta_description = $this->depositconfig->item('site_meta_description_deposit');
        $meta_keywords = $this->depositconfig->item('site_meta_keywords_deposit');
        $meta_author = $this->depositconfig->item('site_meta_author_deposit');
        $page_name = $this->depositconfig->item('site_page_name_deposit');

        $layoutconfig = array(
            'path' => 'deposit',
            'layout' => 'layout',
            'skin' => 'eventpage',
            'layout_dir' => $this->depositconfig->item('layout_deposit'),
            'mobile_layout_dir' => $this->depositconfig->item('mobile_layout_deposit'),
            'use_sidebar' => $this->depositconfig->item('sidebar_deposit'),
            'use_mobile_sidebar' => $this->depositconfig->item('mobile_sidebar_deposit'),
            'skin_dir' => $this->depositconfig->item('skin_deposit'),
            'mobile_skin_dir' => $this->depositconfig->item('mobile_skin_deposit'),
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


    //이벤트 광고페이지 추가  2023-08-30 윤재박
    public function event2page()
    {
		/**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();



        $view = array();
        $view['view'] = array();

        //이벤트 가능업체 여부
        $where_mem1 = "";
        $view['eve_cnt'] = 0;
        if(!empty(config_item('eve1_member'))){
            foreach(config_item('eve1_member') as $r){
                if($where_mem1!=""){
                    $where_mem1.=",";
                }
                $where_mem1.=$r;
            }
            if(!empty($where_mem1)){
                $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE  cmr.mrg_recommend_mem_id in ( ".$where_mem1." ) and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql." / mem_id > ".$mem_id);
                $view['eve_cnt'] = $this->db->query($sql)->row()->cnt;
            }
        }
        // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE ( cmr.mrg_recommend_mem_id = 3 or cmr.mrg_recommend_mem_id = 1260  or cmr.mrg_recommend_mem_id = 962) and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
		// $view['eve_cnt'] = $this->db->query($sql)->row()->cnt;

        $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE cmr.mrg_recommend_mem_id = 962 and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
		$view['eve_cnt2'] = $this->db->query($sql)->row()->cnt;

        // log_message('ERROR', 'eve_cnt > '.$eve_cnt);
        if($view['eve_cnt']==0&&($this->member->item("mem_id")!='2'&&$this->member->item("mem_id")!='3'&&$this->member->item("mem_id")!='1260'&&$this->member->item("mem_id")!='962')){
            redirect($_SERVER['HTTP_REFERER']);

        }else{
            $nowTime = date("Y-m-d H:i:s"); // 현재 시간
            if($view['eve_cnt'] > 0||$this->member->item("mem_id")=='2'||$this->member->item("mem_id")=='3'||$this->member->item("mem_id")=='1260'){ //dhn
                $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime1'))); // 시작 시간
                // $startTime = date("Y-m-d H:i:s", strtotime('2022-02-17 00:00:00')); // 시작 시간
                $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime1'))); // 종료 시간
            }else{ //티지
                // $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime2'))); // 시작 시간
                $startTime = date("Y-m-d H:i:s", strtotime('2022-02-17 00:00:00')); // 시작 시간
                $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime2'))); // 종료 시간
            }

            if($nowTime < $expTime && $nowTime >= $startTime){

            }else{
                // $in_ip = $_SERVER['REMOTE_ADDR'];
                // if($in_ip!="61.75.230.209"){
                    redirect($_SERVER['HTTP_REFERER']);
                // }else{
                //     $view['eve_cnt'] = 1;
                // }
            }
        }


		/**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->depositconfig->item('site_meta_title_deposit');
        $meta_description = $this->depositconfig->item('site_meta_description_deposit');
        $meta_keywords = $this->depositconfig->item('site_meta_keywords_deposit');
        $meta_author = $this->depositconfig->item('site_meta_author_deposit');
        $page_name = $this->depositconfig->item('site_page_name_deposit');

        $layoutconfig = array(
            'path' => 'deposit',
            'layout' => 'layout',
            'skin' => 'event2page',
            'layout_dir' => $this->depositconfig->item('layout_deposit'),
            'mobile_layout_dir' => $this->depositconfig->item('mobile_layout_deposit'),
            'use_sidebar' => $this->depositconfig->item('sidebar_deposit'),
            'use_mobile_sidebar' => $this->depositconfig->item('mobile_sidebar_deposit'),
            'skin_dir' => $this->depositconfig->item('skin_deposit'),
            'mobile_skin_dir' => $this->depositconfig->item('mobile_skin_deposit'),
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


    /**
     * 예치금 나의 사용 내역을 보여주는 함수입니다
     */
    public function mylist()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_deposit_mylist';
        $this->load->event($eventname);

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $this->load->model('Deposit_model');

        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;
        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
        $findex = $this->Deposit_model->primary_key;
        $forder = 'desc';

        $per_page = $this->cbconfig->item('list_count') ? (int) $this->cbconfig->item('list_count') : 20;
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $where = array(
            'mem_id' => $this->member->item('mem_id'),
            'dep_status' => 1,
        );
        $result = $this->Deposit_model
            ->get_list($per_page, $offset, $where, '', $findex, $forder);
        $list_num = $result['total_rows'] - ($page - 1) * $per_page;
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                $result['list'][$key]['num'] = $list_num--;
            }
        }
        $view['view']['data'] = $result;

        /**
         * 페이지네이션을 생성합니다
         */
        $config['base_url'] = site_url('deposit/mylist') . '?' . $param->replace('page');
        $config['total_rows'] = $result['total_rows'];
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $view['view']['paging'] = $this->pagination->create_links();
        $view['view']['page'] = $page;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->depositconfig->item('site_meta_title_deposit_mylist');
        $meta_description = $this->depositconfig->item('site_meta_description_deposit_mylist');
        $meta_keywords = $this->depositconfig->item('site_meta_keywords_deposit_mylist');
        $meta_author = $this->depositconfig->item('site_meta_author_deposit_mylist');
        $page_name = $this->depositconfig->item('site_page_name_deposit_mylist');

        $layoutconfig = array(
            'path' => 'deposit',
            'layout' => 'layout',
            'skin' => 'mylist',
            'layout_dir' => $this->depositconfig->item('layout_deposit'),
            'mobile_layout_dir' => $this->depositconfig->item('mobile_layout_deposit'),
            'use_sidebar' => $this->depositconfig->item('sidebar_deposit'),
            'use_mobile_sidebar' => $this->depositconfig->item('mobile_sidebar_deposit'),
            'skin_dir' => $this->depositconfig->item('skin_deposit'),
            'mobile_skin_dir' => $this->depositconfig->item('mobile_skin_deposit'),
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

    public function inicisweb(){
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_deposit_inicis_pc_pay';
        $this->load->event($eventname);

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        $this->load->library(array('paymentlib'));
        $init = $this->paymentlib->inicis_init('deposit');

        if( 'inicis' !== $this->cbconfig->item('use_payment_pg') ){
            die(json_encode(array('error'=>'올바른 방법으로 이용해 주십시오.')));
        }

        $request_mid = $this->input->post('mid', null, '');
        $session_order_num = $this->session->userdata('unique_id');
		$orderNumber = $this->input->post('orderNumber', true, 0);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > unique_id : ". $this->session->userdata['unique_id'] .", mem_id : ". $this->member->item("mem_id"));

		//2020-11-04 추가
		if($session_order_num == ""){
			$session_order_num = $orderNumber;
			$this->session->set_userdata(
				'unique_id',
				$orderNumber
			);
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > unique_id : ". $this->session->userdata['unique_id'] .", orderNumber : ". $orderNumber);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > request_mid : ". $request_mid .", pg_inicis_mid : ". element('pg_inicis_mid', $init) .", session_order_num : ". $session_order_num);

        if( ($request_mid != element('pg_inicis_mid', $init)) || ! $session_order_num ){
            alert("잘못된 요청입니다.");
            //alert("잘못된 요청입니다. request_mid : ". $request_mid .", pg_inicis_mid : ". element('pg_inicis_mid', $init) .", session_order_num : ". $session_order_num);
        }
        if( !$orderNumber ){
            alert("주문번호가 없습니다.");
        }

        $this->load->model('Payment_order_data_model');
        $row = $this->Payment_order_data_model->get_one($orderNumber);
        $params = array();
        $data = cmall_tmp_replace_data($row['pod_data']);

        if( !$data ){
            alert("임시 주문 정보가 저장되지 않았습니다. \\n 다시 실행해 주세요.");
        }

        foreach($data as $key=>$value) {
            if(is_array($value)) {
                foreach($value as $k=>$v) {
                    $_POST[$key][$k] = $params[$key][$k] = $v;
                }
            } else {
                $_POST[$key] = $params[$key] = $value;
            }
        }

        // 이벤트가 존재하면 실행합니다
        Events::trigger('after', $eventname);

        $this->update();
    }

    /**
     * 결제 업데이트 함수입니다
     */
    public function update($agent_type='')
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_deposit_update';
        $this->load->event($eventname);

        $pay_type = $this->input->post('pay_type', null, '');

		//2020-11-04 추가
		$ss_mem_id = $this->session->userdata['mem_id'];
		$unique_mem_id = $this->input->post('unique_mem_id', null, '');
		//log_message("ERROR", "/deposit > update > unique_id : ". $this->session->userdata['unique_id'] .", pay_type : ". $pay_type .", ss_mem_id : ". $ss_mem_id .", unique_mem_id : ". $unique_mem_id);
		if($ss_mem_id == "" and $unique_mem_id != ""){
			//log_message("ERROR", "/deposit > update > this->session->set_userdata : ". $unique_mem_id);
			$this->session->set_userdata(
				'mem_id',
				$unique_mem_id
			);
		}

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        if ( ! $this->depositconfig->item('use_deposit_cash_to_deposit')) {
            alert('충전이 불가능합니다. 관리자에게 문의하여 주십시오');
        }

        if ( 'bank' != $pay_type && $this->cbconfig->item('use_payment_pg') === 'lg'
            && ! $this->input->post('LGD_PAYKEY')) {
            alert('결제등록 요청 후 주문해 주십시오');
        }

        if ( ! $this->session->userdata('unique_id') OR ! $this->input->post('unique_id')
            OR $this->session->userdata('unique_id') !== $this->input->post('unique_id')) {
            alert('잘못된 접근입니다');
        }

        $this->load->library('paymentlib');

        $moneyreal = (int) $this->input->post('money_value');
        $depositreal = (int) $this->input->post('deposit_real');
        $mem_realname = $this->input->post('mem_realname', null, '');

		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > moneyreal : ". $moneyreal .", depositreal : ". $depositreal .", mem_realname : ". $mem_realname);

        if($moneyreal == -1) {
            $moneyreal = $depositreal;
        } else {
            $cashtodep = array();
            $reallist = false;
            $cashtodeposit = $this->depositconfig->item('deposit_cash_to_deposit_unit');
            $cashtodeposit = preg_replace("/[\r|\n|\r\n]+/", ',', $cashtodeposit);
            $cashtodeposit = preg_replace("/\s+/", '', $cashtodeposit);
            if ($cashtodeposit) {
                $cashtodeposit = explode(',', trim($cashtodeposit, ','));
                $cashtodeposit = array_unique($cashtodeposit);
                if ($cashtodeposit) {
                    foreach ($cashtodeposit as $key => $val) {
                        $exp = explode(':', $val);
                        if ($moneyreal == $exp[0] && $depositreal == $exp[1]) {
                            $reallist = true;
                        }
                    }
                }
            }
            if ($reallist === false) {
                alert('충전 목록이 존재하지 않습니다');
            }
        }
        $insertdata = array();
        $result = '';

        if ($pay_type === 'bank') { //레드뱅킹
            $insertdata['dep_datetime'] = date('Y-m-d H:i:s');
            $insertdata['dep_deposit_datetime'] = null;
            $insertdata['dep_deposit_request'] = $depositreal;
            $insertdata['dep_deposit'] = 0;
            $insertdata['mem_realname'] = $mem_realname;
            $insertdata['dep_cash_request'] = $moneyreal;
            $insertdata['dep_cash'] = 0;
            $insertdata['dep_status'] = 0;
            $insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 적립 (무통장입금)';

        } elseif ($pay_type === 'realtime') {
            if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
                $result = $this->paymentlib->kcp_pp_ax_hub();
            } elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
                $result = $this->paymentlib->xpay_result();
            } elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
                $result = $this->paymentlib->inipay_result($agent_type);
            }

            $insertdata['dep_tno'] = element('tno', $result);
            $insertdata['dep_datetime'] = date('Y-m-d H:i:s');
            $insertdata['dep_deposit_datetime'] = preg_replace(
                "/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
                "\\1-\\2-\\3 \\4:\\5:\\6",
                element('app_time', $result)
            );
            $insertdata['dep_deposit_request'] = $depositreal;
            $insertdata['dep_deposit'] = $depositreal;
            $insertdata['dep_cash_request'] = element('amount', $result);
            $insertdata['dep_cash'] = element('amount', $result);
            $insertdata['dep_status'] = 1;
            $insertdata['mem_realname'] = $mem_realname;
            $insertdata['dep_pg'] = $this->cbconfig->item('use_payment_pg');
            $insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 적립 (실시간계좌이체)';

         } elseif ($pay_type === 'vbank') { //가상계좌입금

            if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
                $result = $this->paymentlib->kcp_pp_ax_hub();
            } elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
                $result = $this->paymentlib->xpay_result();
            } elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
                $result = $this->paymentlib->inipay_result($agent_type);
            }

            $insertdata['dep_tno'] = element('tno', $result);
            $insertdata['dep_datetime'] = date('Y-m-d H:i:s');
            $insertdata['dep_deposit_request'] = $depositreal;
            $insertdata['dep_deposit'] = 0;
            $insertdata['dep_cash_request'] = element('amount', $result);
            $insertdata['dep_cash'] = 0;
            $insertdata['dep_status'] = 0;
            $insertdata['mem_realname'] = element('depositor', $result);
            $insertdata['dep_vbank_expire'] = element('cor_vbank_expire', $result) ? date("Y-m-d", strtotime(element('cor_vbank_expire', $result))) : '0000-00-00 00:00:00';
            $insertdata['dep_bank_info'] = element('bankname', $result) . ' ' . element('account', $result);
            $insertdata['dep_pg'] = $this->cbconfig->item('use_payment_pg');
            $insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 적립 (가상계좌)';

        } elseif ($pay_type === 'phone') {

            if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
                $result = $this->paymentlib->kcp_pp_ax_hub();
            } elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
                $result = $this->paymentlib->xpay_result();
            } elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
                $result = $this->paymentlib->inipay_result($agent_type);
            }

            $insertdata['dep_tno'] = element('tno', $result);
            $insertdata['dep_app_no'] = element('commid', $result) . ' ' . element('mobile_no', $result);
            $insertdata['dep_datetime'] = date('Y-m-d H:i:s');
            $insertdata['dep_deposit_datetime'] = preg_replace(
                "/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
                "\\1-\\2-\\3 \\4:\\5:\\6",
                element('app_time', $result)
            );
            $insertdata['dep_deposit_request'] = $depositreal;
            $insertdata['dep_deposit'] = $depositreal;
            $insertdata['dep_cash_request'] = element('amount', $result);
            $insertdata['dep_cash'] = element('amount', $result);
            $insertdata['dep_status'] = 1;
            $insertdata['mem_realname'] = $mem_realname;
            $insertdata['dep_pg'] = $this->cbconfig->item('use_payment_pg');
            $insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 적립 (핸드폰결제)';

        } elseif ($pay_type === 'card') {

            if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
                $result = $this->paymentlib->kcp_pp_ax_hub();
            } elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
                $result = $this->paymentlib->xpay_result();
            } elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
                $result = $this->paymentlib->inipay_result($agent_type);
            }

            $insertdata['dep_tno'] = element('tno', $result);
            $insertdata['dep_app_no'] = element('app_no', $result);
            $insertdata['dep_datetime'] = date('Y-m-d H:i:s');
            $insertdata['dep_deposit_datetime'] = preg_replace(
                "/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
                "\\1-\\2-\\3 \\4:\\5:\\6",
                element('app_time', $result)
            );
            $insertdata['dep_deposit_request'] = $depositreal;
            $insertdata['dep_deposit'] = $depositreal;
            $insertdata['dep_cash_request'] = element('amount', $result);
            $insertdata['dep_cash'] = element('amount', $result);
            $insertdata['dep_bank_info'] = element('card_name', $result);
            $insertdata['dep_status'] = 1;
            $insertdata['mem_realname'] = $mem_realname;
            $insertdata['dep_pg'] = $this->cbconfig->item('use_payment_pg');
            $insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 적립 (신용카드결제)';

        } else {
            alert('결제 수단이 잘못 입력되었습니다');
        }

        // 주문금액과 결제금액이 일치하는지 체크
        if (element('tno', $result) && (int) element('amount', $result) !== $moneyreal) {
            if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
                $this->paymentlib->kcp_pp_ax_hub_cancel($result);
            } elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
                $this->paymentlib->xpay_cancel($result);
            } elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
                $this->paymentlib->inipay_cancel($result);
            }
            alert('결제가 완료되지 않았습니다. 다시 시도해주십시오', site_url('deposit'));
        }

        // 정보 입력
        $dep_id = $this->session->userdata('unique_id');
        $insertdata['dep_id'] = $dep_id;
        $insertdata['mem_id'] = $this->member->item('mem_id');
        $insertdata['mem_nickname'] = $this->member->item('mem_nickname');
        $insertdata['mem_email'] = $this->input->post('mem_email', null, '');
        $insertdata['mem_phone'] = $this->input->post('mem_phone', null, '');
        $insertdata['dep_from_type'] = 'cash';
        $insertdata['dep_to_type'] = 'deposit';
        $insertdata['dep_pay_type'] = $pay_type;
        $insertdata['dep_ip'] = $this->input->ip_address();
        $insertdata['dep_useragent'] = $this->agent->agent_string();
        $insertdata['is_test'] = $this->cbconfig->item('use_pg_test');

        $this->load->model('Deposit_model');
        $res = $this->Deposit_model->insert($insertdata);
        if ( ! $res) {
            if ($pay_type !== 'bank') {
                if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
                    $this->paymentlib->kcp_pp_ax_hub_cancel($result);
                } elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
                    $this->paymentlib->xpay_cancel($result);
                } elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
                    $this->paymentlib->inipay_cancel($result);
                }
            }
            alert('결제가 완료되지 않았습니다. 다시 시도해주십시오', site_url('deposit'));
        }

        if ($pay_type === 'bank') {
            $this->depositlib->alarm('bank_to_deposit', $dep_id);
        } elseif ($pay_type !== 'bank') {
            $this->depositlib->alarm('cash_to_deposit', $dep_id);
        }

        //회원의 예치금 업데이트 합니다.
        $this->depositlib->update_member_deposit( $this->member->item('mem_id') );
		  /* 2017.11.27 회원 예치금로그 */
		  $this->load->model("Biz_dhn_model");
		  $this->Biz_dhn_model->deposit_appr($this->member->item('mem_id'), $dep_id);
		  /*----------------------------*/

        // 이벤트가 존재하면 실행합니다
        Events::trigger('after', $eventname);

        $this->session->set_userdata('unique_id', '');

        redirect('deposit/result/' . $dep_id);
    }

    //나의 지갑
    public function history()
    {
        $this->Biz_model->make_user_deposit_table($this->member->item('mem_userid'));
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['set_date'] = $this->input->post('set_date');
        $term = $this->Biz_model->set_Term($view['param']['set_date']);
        $view['param']['endDate'] = (!empty($this->input->post('set_date'))) ? $term->endDate : $this->input->post('endDate');
        $view['param']['startDate'] = (!empty($this->input->post('set_date'))) ? $term->startDate : $this->input->post('startDate');

        if($view['param']['startDate'] == $view['param']['endDate']){
            // $iday = $view['param']['startDate'];
            // $view['param']['startDate'] = date("Y-m-d", strtotime($iday." -1 day"));
            if(empty($view['param']['startDate'])){
                $view['param']['startDate'] = date("Y-m-d");
            }
            if(empty($view['param']['endDate'])){
                $view['param']['endDate'] = date("Y-m-d");
            }
        }

        $param = array($view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59");
        $whereplus = '';
        $whereplusplus ='';
         if($this->member->item("mem_pay_type")=="T"){
             $whereplusplus = " AND aa.amt_kind!='2' ";
         }

        if($this->input->post('selectinout')!='0'){
          if($this->input->post('selectinout')=='1'){
            $whereplus = " AND aa.amt_kind!='1' AND aa.amt_kind!='3' AND aa.amt_kind!='4' AND aa.amt_kind!='9' ";
          }else if($this->input->post('selectinout')=='2'){
            $whereplus = " AND (aa.amt_kind='1' OR aa.amt_kind='3' OR aa.amt_kind='4' OR aa.amt_kind='9') ".$whereplusplus;
          }
        }else{
            if($this->member->item("mem_pay_type")=="T"){
                $whereplus = $whereplusplus;
            }
        }
        $view['selectinouts'] = $this->input->post('selectinout');
        $sql = "
		SELECT count(1) as cnt
		FROM cb_wt_voucher_addon where vad_mem_id = '".$this->member->item("mem_id")."' ";
        $view['voucher_on'] = $this->db->query($sql)->row()->cnt;

        // $sql = "
        //     select count(1) as cnt
        //     from cb_amt_".$this->member->item('mem_userid')."
        //     where FIND_IN_SET('보너스', amt_memo) AND amt_kind != '9'
        // ";
        // $view['bonus_on'] = $this->db->query($sql)->row()->cnt;

        //검색조건
        $sch = "";
        $sch .= $whereplus." AND aa.amt_kind != '' AND aa.idate BETWEEN '". $view['param']['startDate'] ." 00:00:00' AND '". $view['param']['endDate'] ." 23:59:59' ";
        $sch2 .= $whereplus." AND aa.amt_kind != '' AND aa.amt_datetime BETWEEN '". $view['param']['startDate'] ." 00:00:00' AND '". $view['param']['endDate'] ." 23:59:59' ";

        //전체건수
        $sql = "
		SELECT SUM(cnt) AS cnt
		FROM (
			SELECT
				1 AS cnt
			FROM cb_amt_".$this->member->item('mem_userid')." aa
			WHERE 1=1 ". $sch2 ."
			GROUP BY aa.amt_kind, DATE_FORMAT(aa.amt_datetime, '%Y%m%d%I'), aa.amt_memo order by null
		) t ";
        $view['total_rows'] = $this->db->query($sql)->row()->cnt;
        // log_message("ERROR", "/deposit > history >".$sql);


        //현황
        $sql = "SELECT *
		FROM
		(
		SELECT
    t.idate
		, t.amt_datetime
		, t.amt_kind
		, t.amt_memo
		, t.amt_deduct
		, t.amt_cnt
		, t.amt_amount
    -- , t.dep_admin_memo
        , (@subtotal := @subtotal + (CASE
        WHEN FIND_IN_SET('바우처', t.amt_memo)=0 AND t.amt_kind != '9' AND FIND_IN_SET('보너스', t.amt_memo)=0
        THEN t.amt_amount*t.amt_deduct
        ELSE 0
    END)) AS tamt
        , (@vtotal := @vtotal + (CASE
        WHEN FIND_IN_SET('바우처', t.amt_memo) OR t.amt_kind = '9'
        THEN t.amt_amount*t.amt_deduct
        ELSE 0
    END)) AS vamt
    , (@btotal := @btotal + (CASE
        WHEN FIND_IN_SET('보너스', t.amt_memo) AND t.amt_kind != '9'
        THEN t.amt_amount*t.amt_deduct
        ELSE 0
    END)) AS bamt

        FROM ( SELECT @subtotal := 0, @vtotal := 0, @btotal := 0) i
  		JOIN
  		(SELECT
  			 a.amt_datetime as idate
			 ,  DATE_FORMAT(a.amt_datetime, '%Y-%m-%d') AS amt_datetime /* 충전 일시 */
			, a.amt_kind /* 구분(A.알림톡, P.문자, I.친구톡(이미지)) */
			, a.amt_memo /* 사용내역 */
			, a.amt_amount as oriamount /* 금액 */
			, SUM(a.amt_amount) AS amt_amount
      		, a.amt_deduct
      		, COUNT(1) AS amt_cnt
          -- , aaa.dep_admin_memo
		FROM cb_amt_".$this->member->item('mem_userid')." a /*LEFT JOIN cb_deposit aaa ON (a.amt_kind = '1' OR a.amt_kind = '2') AND a.amt_reason = aaa.dep_id*/

    -- GROUP BY a.amt_kind , DATE_FORMAT(a.amt_datetime, '%Y%m%d%I'), a.amt_memo  ORDER by idate, amt_memo DESC) t  ) aa WHERE 1=1 ".$sch."
    GROUP BY a.amt_kind , DATE_FORMAT(a.amt_datetime, '%Y%m%d%I'), a.amt_memo  ORDER by null) t  ) aa WHERE 1=1 ".$sch."
		ORDER BY aa.idate DESC, aa.amt_memo ASC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql, $param)->result();
        // log_message('error', $sql);


    //     $sql = "SELECT *
	// 	FROM
	// 	(
	// 	SELECT
    // t.idate
	// 	, t.amt_datetime
	// 	, t.amt_kind
	// 	, t.amt_memo
	// 	, t.amt_deduct
	// 	, t.amt_cnt
	// 	, t.amt_amount
    // -- , t.dep_admin_memo
    //     , (@subtotal := @subtotal + (CASE
    //     WHEN FIND_IN_SET('바우처', t.amt_memo)=0 AND FIND_IN_SET('보너스', t.amt_memo)=0 AND t.amt_kind != '9'
    //     THEN t.amt_amount*t.amt_deduct
    //     ELSE 0
    // END)) AS tamt
    //     , (@vtotal := @vtotal + (CASE
    //     WHEN FIND_IN_SET('바우처', t.amt_memo) OR t.amt_kind = '9'
    //     THEN t.amt_amount*t.amt_deduct
    //     ELSE 0
    // END)) AS vamt
    // , (@btotal := @btotal + (CASE
    //     WHEN FIND_IN_SET('보너스', t.amt_memo) AND t.amt_kind != '9'
    //     THEN t.amt_amount*t.amt_deduct
    //     ELSE 0
    // END)) AS bamt
    //
    //     FROM ( SELECT @subtotal := 0, @vtotal := 0, @btotal := 0) i
  	// 	JOIN
  	// 	(SELECT
  	// 		 a.amt_datetime as idate
	// 		 ,  DATE_FORMAT(a.amt_datetime, '%Y-%m-%d') AS amt_datetime /* 충전 일시 */
	// 		, a.amt_kind /* 구분(A.알림톡, P.문자, I.친구톡(이미지)) */
	// 		, a.amt_memo /* 사용내역 */
	// 		, a.amt_amount as oriamount /* 금액 */
	// 		, SUM(a.amt_amount) AS amt_amount
    //   		, a.amt_deduct
    //   		, COUNT(1) AS amt_cnt
    //       -- , aaa.dep_admin_memo
	// 	FROM cb_amt_".$this->member->item('mem_userid')." a /*LEFT JOIN cb_deposit aaa ON (a.amt_kind = '1' OR a.amt_kind = '2') AND a.amt_reason = aaa.dep_id*/
    //
    // -- GROUP BY a.amt_kind , DATE_FORMAT(a.amt_datetime, '%Y%m%d%I'), a.amt_memo  ORDER by idate, amt_memo DESC) t  ) aa WHERE 1=1 ".$sch."
    // GROUP BY a.amt_kind , DATE_FORMAT(a.amt_datetime, '%Y%m%d%I'), a.amt_memo  ORDER by null) t  ) aa WHERE 1=1 ".$sch."
	// 	ORDER BY aa.idate DESC, aa.amt_memo ASC";
    //     $view['download_list'] = $this->db->query($sql, $param)->result();
        // log_message("ERROR", "/deposit > history >".$sql);
        // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE ( cmr.mrg_recommend_mem_id = 3 or cmr.mrg_recommend_mem_id = 1260) and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
		// $view['eve_cnt'] = $this->db->query($sql)->row()->cnt;
        //
        // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE cmr.mrg_recommend_mem_id = 962 and cm.mem_level = 1 and cm.mem_id = '".$this->member->item('mem_id')."'";
		// $view['eve_cnt2'] = $this->db->query($sql)->row()->cnt;

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        $layoutconfig = array(
            'path' => 'deposit',
            'layout' => 'layout',
            'skin' => 'history',
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

    /**
     * 결제 후 결과 페이지입니다
     */
    public function result($dep_id = 0)
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_deposit_result';
        $this->load->event($eventname);

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        if (empty($dep_id) OR $dep_id < 1) {
            alert('잘못된 접근입니다');
        }

        $this->load->model('Deposit_model');

        $deposit = $this->Deposit_model->get_one($dep_id);

        if ( ! element('dep_id', $deposit)) {
            alert('잘못된 접근입니다');
        }

        if ((int) element('mem_id', $deposit) !== (int) $this->member->item('mem_id')) {
            alert('잘못된 접근입니다');
        }

        $view['view']['data'] = $deposit;


        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->depositconfig->item('site_meta_title_deposit_result');
        $meta_description = $this->depositconfig->item('site_meta_description_deposit_result');
        $meta_keywords = $this->depositconfig->item('site_meta_keywords_deposit_result');
        $meta_author = $this->depositconfig->item('site_meta_author_deposit_result');
        $page_name = $this->depositconfig->item('site_page_name_deposit_result');

        $layoutconfig = array(
            'path' => 'deposit',
            'layout' => 'layout',
            'skin' => 'deposit_result',
            'layout_dir' => $this->depositconfig->item('layout_deposit'),
            'mobile_layout_dir' => $this->depositconfig->item('mobile_layout_deposit'),
            'use_sidebar' => $this->depositconfig->item('sidebar_deposit'),
            'use_mobile_sidebar' => $this->depositconfig->item('mobile_sidebar_deposit'),
            'skin_dir' => $this->depositconfig->item('skin_deposit'),
            'mobile_skin_dir' => $this->depositconfig->item('mobile_skin_deposit'),
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


    /**
     * 포인트 -> 예치금 전환 페이지
     */
    public function point_to_deposit()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_deposit_point_to_deposit';
        $this->load->event($eventname);

        if ( ! $this->depositconfig->item('use_deposit_point_to_deposit')) {
            alert_close('포인트를 이용한 ' . $this->depositconfig->item('deposit_name') . ' 구매 기능을 지원하지 않습니다');
            return;
        }
        if ($this->member->is_member() === false) {
            alert_close('접근 권한이 없습니다');
            return;
        }
        if ($this->depositconfig->item('deposit_point_min') && $this->depositconfig->item('deposit_point_min') > $this->member->item('mem_point')) {
            alert_close('최소 ' . $this->depositconfig->item('deposit_point_min') . ' 포인트 이상 이용 가능합니다');
            return;
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'point',
                'label' => '포인트',
                'rules' => 'trim|required|is_natural_no_zero|callback__point_to_deposit_check',
            ),
        );
        $this->form_validation->set_rules($config);

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = '포인트를 ' . $this->depositconfig->item('deposit_name') . '(으)로 전환';
            $layoutconfig = array(
                'path' => 'deposit',
                'layout' => 'layout_popup',
                'skin' => 'point_to_deposit',
                'layout_dir' => $this->depositconfig->item('layout_deposit'),
                'mobile_layout_dir' => $this->depositconfig->item('mobile_layout_deposit'),
                'skin_dir' => $this->depositconfig->item('skin_deposit'),
                'mobile_skin_dir' => $this->depositconfig->item('mobile_skin_deposit'),
                'page_title' => $page_title,
            );
            $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));

        } else {

            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            $deposit = floor($this->input->post('point') / $this->depositconfig->item('deposit_point'));
            $content = '[포인트 -> ' . $this->depositconfig->item('deposit_name') . ' 전환] 포인트 '
                . number_format($this->input->post('point')) . ' -> '
                . $this->depositconfig->item('deposit_name') . ' ' . $deposit . ' '
                . $this->depositconfig->item('deposit_unit');
            $return = $this->depositlib->do_point_to_deposit(
                $this->member->item('mem_id'),
                $this->input->post('point'),
                $pay_type = 'point',
                $content,
                $admin_memo = ''
            );
            $result = json_decode($return, true);
            if (element('result', $result) === 'fail') {
                alert_close(html_escape(element('reason', $result)));
            }
            $this->depositlib->alarm('point_to_deposit', element('dep_id', $result));
            alert_refresh_close('전환이 완료되었습니다.');
        }
    }


    /**
     * 예치금 -> 포인트 전환 페이지
     */
    public function deposit_to_point()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_deposit_deposit_to_point';
        $this->load->event($eventname);

        if ( ! $this->depositconfig->item('use_deposit_deposit_to_point')) {
            alert_close($this->depositconfig->item('deposit_name') . '을(를) 포인트로 전환하는 기능을 지원하지 않습니다');
            return;
        }
        if ($this->member->is_member() === false) {
            alert_close('접근 권한이 없습니다');
            return;
        }
        if ($this->depositconfig->item('deposit_refund_point_min')
            && $this->depositconfig->item('deposit_refund_point_min') > $this->member->item('total_deposit')) {
            alert_close('최소 ' . $this->depositconfig->item('deposit_refund_point_min')
                . $this->depositconfig->item('deposit_unit') . ' 이상 이용 가능합니다');
            return;
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'deposit',
                'label' => $this->depositconfig->item('deposit_name'),
                'rules' => 'trim|required|is_natural_no_zero|callback__deposit_to_point_check',
            ),
        );
        $this->form_validation->set_rules($config);
        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->depositconfig->item('deposit_name') . '을(를) 포인트로 전환';
            $layoutconfig = array(
                'path' => 'deposit',
                'layout' => 'layout_popup',
                'skin' => 'deposit_to_point',
                'layout_dir' => $this->depositconfig->item('layout_deposit'),
                'mobile_layout_dir' => $this->depositconfig->item('mobile_layout_deposit'),
                'skin_dir' => $this->depositconfig->item('skin_deposit'),
                'mobile_skin_dir' => $this->depositconfig->item('mobile_skin_deposit'),
                'page_title' => $page_title,
            );
            $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));

        } else {

            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            $point = $this->input->post('deposit') * $this->depositconfig->item('deposit_refund_point');

            $content = '[' . $this->depositconfig->item('deposit_name') . ' -> 포인트 전환] '
                . $this->depositconfig->item('deposit_name') . ' ' . $this->input->post('deposit')
                . $this->depositconfig->item('deposit_unit') . ' -> 포인트 ' . number_format($point) . '점';

            $return = $this->depositlib->do_deposit_to_point(
                $this->member->item('mem_id'),
                $this->input->post('deposit'),
                $pay_type = '',
                $content,
                $admin_memo = ''
            );

            $result = json_decode($return, true);

            if (element('result', $result) === 'fail') {
                alert_close(html_escape(element('reason', $result)));
            }
            $this->depositlib->alarm('deposit_to_point', element('dep_id', $result));
            alert_refresh_close('전환이 완료되었습니다.');
        }
    }


    public function _deposit_to_point_check($deposit)
    {
        if ($deposit > $this->member->item('total_deposit')) {
            $this->form_validation->set_message(
                '_deposit_to_point_check',
                '입력된 값이 회원님이 보유하신 값보다 큽니다'
            );
            return false;
        }
        return true;
    }


    public function _point_to_deposit_check($point)
    {
        if ($point > $this->member->item('mem_point')) {
            $this->form_validation->set_message(
                '_point_to_deposit_check',
                '입력된 값이 회원님이 보유하신 값보다 큽니다'
            );
            return false;
        }
        return true;
    }

	//충전하기 > 가상계좌입금내역
	public function vbank_20201218(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		// 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_vbank_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;
        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		//echo "page : ". $page ."<br>";

        $view['view']['sort'] = array(
            'dep_id' => $param->sort('dep_id', 'asc'),
            'dep_pay_type' => $param->sort('dep_pay_type', 'asc'),
        );
        $findex = $this->input->get('findex') ? $this->input->get('findex') : $this->{$this->modelname}->primary_key;
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, ''); //검색타입
        $skeyword = $this->input->get('skeyword', null, ''); //검색내용

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;
		//echo "per_page : ". $per_page ."<br>";

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        //$this->{$this->modelname}->allow_search_field = array('deposit.mem_id', 'dep_deposit_request', 'dep_cash_request', 'dep_point', 'dep_pay_type', 'dep_content', 'dep_admin_memo', 'dep_ip', 'deposit.mem_nickname', 'member.mem_userid'); // 검색이 가능한 필드
        $this->{$this->modelname}->allow_search_field = array('deposit.mem_id', 'dep_deposit_request', 'dep_cash_request', 'dep_point', 'dep_pay_type', 'dep_content', 'dep_admin_memo', 'dep_ip', 'deposit.mem_nickname', 'member.mem_userid', 'member.mem_username', 'mem_realname'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('deposit.mem_id', 'dep_deposit_request', 'dep_cash_request', 'dep_point', 'dep_pay_type'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('dep_id', 'deposit.mem_id', 'dep_pay_type'); // 정렬이 가능한 필드

        $where = array(
            'dep_from_type' => 'cash',
            'dep_pay_type' => 'vbank',
            'deposit.mem_id' => $this->member->item("mem_id"),
        );
        if ($this->input->get('dep_status') === 'Y') {
            $where['dep_status'] = 1;
        }
        if ($this->input->get('dep_status') === 'N') {
            $where['dep_status'] = 0;
        }

        $result = $this->{$this->modelname}
            ->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                $result['list'][$key]['display_name'] = display_username(
                    element('mem_userid', $val),
                    element('mem_nickname', $val)
                );
            }
        }
        $view['view']['data'] = $result;

        //primary key 정보를 저장합니다
        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $result['total_rows'];
		$page_cfg['per_page'] = $per_page;
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($page);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

		$view['total_rows'] = $result['total_rows']; //전체수
		$view['per_page'] = $per_page; //리스트수
		$view['page'] = $page; //현재페이지

		$layoutconfig = array(
			'path' => 'deposit',
			'layout' => 'layout',
			'skin' => 'vbank',
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

	//충전하기 > 가상계좌입금내역
	public function vbank(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		// 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_vbank_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;
        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		//echo "page : ". $page ."<br>";

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;
		//echo "per_page : ". $per_page ."<br>";

        $sch = "";
        $sch .= "and dep_from_type = 'cash' ";
        $sch .= "and dep_pay_type in ('bank', 'vbank') ";
        $sch .= "and a.mem_id = '". $this->member->item("mem_id") ."' ";
        if ($this->input->get('dep_status') === 'Y') {
            $sch .= "and dep_status = 1 ";
        }
        if ($this->input->get('dep_status') === 'N') {
            $sch .= "and dep_status = 0 ";
        }

		$sql = "
		select count(1) as cnt
		from cb_deposit a
		left outer join cb_member b ON a.mem_id = b.mem_id
		where 1=1 ". $sch ." ";
		$result['total_rows'] = $this->db->query($sql)->row()->cnt;

		$sql = "
		select
			  b.mem_username /* 회원명 */
			, a.mem_realname /* 입금자 */
			, dep_bank_info /* 입금계좌 */
			, dep_datetime /* 요청일시 */
			, dep_cash /* 결제금액 */
			, dep_cash_request /* 미수금액 */
			, dep_cash_request /* 미수금액 */
			, a.dep_pay_type /* 입금구분(bank.무통장입금(레드뱅킹), vbank.가상계좌(이니시즈)) */
		from cb_deposit a
		left outer join cb_member b ON a.mem_id = b.mem_id
		where 1=1 ". $sch ."
		ORDER BY dep_id DESC
		LIMIT ".$offset.", ".$per_page." ";
		//echo $sql ."<br>";
        $view['list'] = $this->db->query($sql)->result();

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $result['total_rows'];
		$page_cfg['per_page'] = $per_page;
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($page);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);
		$view['total_rows'] = $result['total_rows']; //전체수
		$view['per_page'] = $per_page; //리스트수
		$view['page'] = $page; //현재페이지

		$layoutconfig = array(
			'path' => 'deposit',
			'layout' => 'layout',
			'skin' => 'vbank',
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

    public function write($pid = 0)
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_depositlist_write';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['deptype'] = $deptype = $this->depositlib->deptype;
        $view['view']['paymethodtype'] = $paymethodtype = $this->depositlib->paymethodtype;
        $rs = $this->Biz_model->get_partner_list(array($this->member->item('mem_id')), '1=1', 1, 1000);
        $view['rs'] = $rs->lists;

        $this->db
            ->select("SUM(pdt_amount) AS total_amt")
            ->from("cb_pre_deposit_tg");

        $view["total_amt"] = $this->db->get()->row()->total_amt;

        /**
         * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
         */
        if ($pid) {
            $pid = (int) $pid;
            if (empty($pid) OR $pid < 1) {
                show_404();
            }
        }
        $primary_key = $this->{$this->modelname}->primary_key;
        /**
         * 수정 페이지일 경우 기존 데이터를 가져옵니다
         */
        $getdata = array();
        if ($pid) {
            $getdata = $this->{$this->modelname}->get_one($pid);
            $getdata['member'] = $member = $this->Member_model->get_one(element('mem_id', $getdata));
            /* 결제정보 추가 입력 */
            $getdata['amt_deposit'] = "0"; $getdata['amt_point'] = "0";
            $amt_rs = $this->db->query("select * from cb_amt_".$getdata['member']['mem_userid']." where amt_kind='1' and amt_reason=?", array($getdata['dep_id']));
            if($amt_rs) {
                $amt = $amt_rs->row();
                $getdata['amt_deposit'] = $amt->amt_deposit;
                $getdata['amt_point'] = $amt->amt_point;
            }
            /*--------------------*/
        }

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');


        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'dep_content',
                'label' => '내용',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'dep_admin_memo',
                'label' => '관리자 메모',
                'rules' => 'trim',
            ),
        );
        if ($this->input->post($primary_key)) {

        } else {
            $config[] = array(
                'field' => 'mem_userid',
                'label' => '회원아이디',
                'rules' => 'trim|required',
            );
            $config[] = array(
                'field' => 'findname',
                'label' => '아이디확인',
                'rules' => 'trim|required',
            );
            $config[] = array(
                'field' => 'dep_deposit',
                'label' => '예치금',
                'rules' => 'trim|numeric|callback__deposit_sum_check',
            );
            $config[] = array(
                'field' => 'dep_kind',
                'label' => '선택항목',
                'rules' => 'trim|required',
            );
        }
        $this->form_validation->set_rules($config);


        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            $view['view']['data'] = $getdata;

            /**
             * primary key 정보를 저장합니다
             */
            $view['view']['primary_key'] = $primary_key;

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

            /**
             * 어드민 레이아웃을 정의합니다
             */
            $writeskin = $pid ? 'modify' : 'write';
            $layoutconfig = array(
                'path' => 'deposit',
                'layout' => 'layout',
                'skin' => $writeskin,
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
            $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());//$this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));

        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            /**
             * 게시물을 수정하는 경우입니다
             */
            if ($this->input->post($primary_key)) {
                $updatedata = array(
                    'dep_content' => $this->input->post('dep_content', null, ''),
                    'dep_admin_memo' => $this->input->post('dep_admin_memo', null, ''),
                );
                $this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);
                $this->session->set_flashdata(
                    'message',
                    '정상적으로 수정되었습니다'
                    );
            } else {
                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > dep_deposit : ". $this->input->post('dep_deposit') .", dep_kind : ". $this->input->post('dep_kind'));
                /**
                 * 게시물을 새로 입력하는 경우입니다
                 */
                $mb = $this->Member_model->get_by_userid($this->input->post('mem_userid'), 'mem_id, mem_nickname');
                $mem_id = element('mem_id', $mb);

                $sum = $this->Deposit_model->get_deposit_sum($mem_id);
                $deposit_pm = 0;
                if($this->input->post('dep_kind')=='1'||$this->input->post('dep_kind')=='4'||$this->input->post('dep_kind')=='5'||$this->input->post('dep_kind')=='9'){
                    $deposit_sum = $sum + $this->input->post('dep_deposit', null, 0);
                }else if($this->input->post('dep_kind')=='2'||$this->input->post('dep_kind')=='40'){ //40은 선충전차감
                    $deposit_sum = $sum - $this->input->post('dep_deposit', null, 0);
                }

                $sql = " SELECT count(*) AS cnt FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$mem_id;
                $kvd_cnt = $this->db->query($sql)->row()->cnt;
                // log_message("ERROR", $_SERVER['REQUEST_URI']."cb_kvoucher_deposit > sql >".$sql);
                if($kvd_cnt>0&&$this->input->post('dep_kind')=='9'){

                    echo "<script>";
                    echo "  alert('이미 바우처충전된 업체입니다');";

                    echo "  history.back();";
                    echo "</script>";
                    return;
                }


                $dep_id = $this->Unique_id_model->get_id($this->input->ip_address());

                // if ($this->input->post('dep_deposit') > 0) {
                //     $dep_from_type = 'service';
                //     $dep_to_type = 'deposit';
                //     $dep_pay_type = 'service';
                // } else {
                //     $dep_from_type = 'deposit';
                //     $dep_to_type = 'service';
                //     $dep_pay_type = '';
                // }
                $dep_deposit = $this->input->post('dep_deposit') ? $this->input->post('dep_deposit') : 0;

                if($this->input->post('dep_kind')=='1'||$this->input->post('dep_kind')=='4'){
                    $dep_from_type = 'service';
                    $dep_to_type = 'deposit';
                    if($this->input->post('dep_kind')=='1'){
                        $dep_pay_type = 'service';
                    }else if($this->input->post('dep_kind')=='4'){
                        $dep_pay_type = 'pservice';
                    }
                }else if($this->input->post('dep_kind')=='40'){ //40은 선충전차감
                    $dep_from_type = 'deposit';
                    $dep_to_type = 'pservice';
                    $dep_pay_type = 'pservice';
                    if($dep_deposit>0){
                        $dep_deposit = $dep_deposit*-1;
                    }
                }else if($this->input->post('dep_kind')=='2'){
                    $dep_from_type = 'deposit';
                    $dep_to_type = 'service';
                    $dep_pay_type = '';
                    if($dep_deposit>0){
                        $dep_deposit = $dep_deposit*-1;
                    }
                }else if($this->input->post('dep_kind')=='5'){
                    $dep_from_type = 'service';
                    $dep_to_type = 'deposit';
                    $dep_pay_type = 'service';
                }else if($this->input->post('dep_kind')=='9'){
                    $dep_from_type = 'voucher';
                    $dep_to_type = 'vcash';
                    $dep_pay_type = 'voucher';
                }



                $insertdata = array(
                    'dep_id' => $dep_id,
                    'mem_id' => $mem_id,
                    'mem_nickname' => element('mem_nickname', $mb),
                    'dep_from_type' => $dep_from_type,
                    'dep_to_type' => $dep_to_type,
                    'dep_pay_type' => $dep_pay_type,
                    'dep_deposit_request' => $dep_deposit,
                    'dep_deposit' => $dep_deposit,
                    'dep_deposit_sum' => $deposit_sum,
                    'dep_content' => $this->input->post('dep_content', null, ''),
                    'dep_admin_memo' => $this->input->post('dep_admin_memo', null, ''),
                    'dep_datetime' => cdate('Y-m-d H:i:s'),
                    'dep_deposit_datetime' => cdate('Y-m-d H:i:s'),
                    'dep_ip' => $this->input->ip_address(),
                    'dep_useragent' => $this->agent->agent_string(),
                    'dep_status' => 1,
                    'dep_insert_mem' =>2,
                );
                $this->{$this->modelname}->insert($insertdata);

                $insertdata2 = array(
                    'pdt_mem_id' => $mem_id,
                    'pdt_amount' => $dep_deposit,
                    'pdt_dep_id' => $dep_id,
                    'pdt_process_object' => "tg"
                );
                $this->db->insert("cb_pre_deposit_tg", $insertdata2);


                if($this->input->post('dep_kind')=='4' || $this->input->post('dep_kind')=='40'){ //선충전
                    $sql = " SELECT count(*) AS cnt FROM cb_pre_deposit WHERE mem_id = ".$mem_id;
                    $pre_cnt = $this->db->query($sql)->row()->cnt;
                    if($pre_cnt>0){
                        $sql = " SELECT pre_cash FROM cb_pre_deposit WHERE mem_id = ".$mem_id;
                        $pre_cash = $this->db->query($sql)->row()->pre_cash;
                        $updatedeposit = 0;
                        if($pre_cash>0){
                            $updatedeposit = $pre_cash + $dep_deposit;
                        }else{
                            $updatedeposit = $dep_deposit;
                        }
                        $insertD = array();
                        $insertD['pre_cash'] = $updatedeposit;
                        $this->db->update("cb_pre_deposit", $insertD, array("mem_id"=>$mem_id));

                    }else{
                        $insertD = array();
                        $insertD['mem_id'] = $mem_id;
                        if($this->input->post('dep_kind')=='4'){
                            $insertD['pre_cash'] = $dep_deposit;
                        }else if($this->input->post('dep_kind')=='40'){
                            $insertD['pre_cash'] = $dep_deposit*-1;
                        }
                        $this->db->insert("cb_pre_deposit", $insertD);
                    }
                }

                if($this->input->post('dep_kind')=='9'){

                    // if($kvd_cnt>0){
                        // $sql = " SELECT kvd_cash FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$mem_id;
                        // $kvd_cash = $this->db->query($sql)->row()->kvd_cash;
                        // $updatedeposit = 0;
                        // if($kvd_cash>0){
                        //     $updatedeposit = $kvd_cash + $dep_deposit;
                        // }else{
                        //      $updatedeposit = $dep_deposit;
                        //  }
                        // $insertD = array();
                        // $insertD['kvd_cash'] = $updatedeposit;
                        // $insertD['kvd_sdate'] = $this->input->post("startDate", null, '');
                        // $insertD['kvd_edate'] = $this->input->post("endDate", null, '');
                        // $this->db->update("cb_kvoucher_deposit", $insertD, array("kvd_mem_id"=>$mem_id));

                    // }else{
                        $insertD = array();
                        $insertD['kvd_mem_id'] = $mem_id;
                        $insertD['kvd_cash'] = $dep_deposit;
                        $insertD['kvd_sdate'] = $this->input->post("startDate", null, '');
                        $insertD['kvd_edate'] = $this->input->post("endDate", null, '');

                        $this->db->insert("cb_kvoucher_deposit", $insertD);
                    // }
                }
                /*

                이 부분에 예치금 저장 프로세스를 넣어야 함 : 현재는 추가 버튼을 막아 skip

                */
                $this->Biz_model->deposit_appr($mem_id, $dep_id, $this->input->post('dep_kind'));

                $this->session->set_flashdata(
                    'message',
                    '정상적으로 수정되었습니다'
                    );
            }

            $this->depositlib->update_member_deposit($mem_id);

            // 이벤트가 존재하면 실행합니다
            Events::trigger('after', $eventname);

            /**
             * 게시물의 신규입력 또는 수정작업이 끝난 후 목록 페이지로 이동합니다
             */
            $param =& $this->querystring;
            $redirecturl = $this->pagedir . '?' . $param->output();

            redirect("/deposit/prelist");
            // redirect($redirecturl);
        }
    }

    function check_amount(){
        $kind = $this->input->post("kind");
        $dep_deposit = $this->input->post("dep_deposit");
        $userid = $this->input->post("userid");

        $sql = "
            SELECT
                SUM(pdt_amount) AS sum
            FROM
                cb_pre_deposit_tg
        ";

        $sum = $this->db->query($sql)->row()->sum;

        $sql = "
            SELECT
                pda_amount
            FROM
                cb_pre_deposit_amt
            WHERE
                pda_mem_id = 962
        ";

        $pda_amount = $this->db->query($sql)->row()->pda_amount;

        $result = array();
        $result["code"] = "OK";
        $result["msg"] = "";
        $result["sum"] = $sum;

        if ($sum == "0" && $kind == "40"){
            $result["code"] = "NG";
            $result["msg"] = "차감할 금액이 없습니다.";
        } else if($sum + $dep_deposit > $pda_amount && $kind == "4") {
            $result["code"] = "NG";
            $result["msg"] = "선충전 한도금액을 초과합니다.";
        } else {
            $sql="
                SELECT
                    IFNULL(SUM(a.pdt_amount), 0) AS sum
                FROM
                    cb_pre_deposit_tg a
                INNER JOIN(
                    SELECT
                        mem_id
                    FROM
                        cb_member
                    WHERE
                        mem_userid = '" . $userid . "'
                ) b ON a.pdt_mem_id = b.mem_id
            ";
            $sum2 = $this->db->query($sql)->row()->sum;
            if ($kind == "40" && $sum2 == "0"){
                $result["code"] = "NG";
                $result["msg"] = "선택하신 유저는 선충전 금액이 없습니다.";
                $result["sum"] = "0";
            } else if ($kind == "40" && $sum2 > 0 && $sum2 < $dep_deposit){
                $result["code"] = "NG";
                $result["msg"] = "입력한 금액보다 선충전이 되어 있는 금액이 작습니다.";
                $result["sum"] = $sum2;
            }
        }

        // $json = json_encode($result, JSON_UNESCAPED_UNICODE);
        // $json = str_replace('null', '""', $json);
        header('Content-Type: application/json');
        echo '{"code":"' . $result["code"] . '", "msg":"' . $result["msg"] . '", "sum":"' . $result["sum"] . '" }';
    }

    /**
     * 금액이 올바르게 입력되었는지를 체크합니다
     */
    public function _deposit_sum_check($deposit)
    {
        is_numeric($deposit) OR $deposit = 0;
        $deposit = (int) $deposit;

        $mb = $this->Member_model->get_by_userid($this->input->post('mem_userid'), 'mem_id, mem_denied');
        if ( ! element('mem_id', $mb)) {
            $this->form_validation->set_message(
                '_deposit_sum_check',
                $this->input->post('mem_userid') . ' 은 존재하지 않는 회원아이디입니다'
                );
            return false;
        }
        if (element('mem_denied', $mb)) {
            $this->form_validation->set_message(
                '_deposit_sum_check',
                $this->input->post('mem_userid') . ' 은 탈퇴, 차단된 회원아이디입니다'
                );
            return false;
        }

        if ($deposit === 0) {
            $this->form_validation->set_message(
                '_deposit_sum_check',
                '예치금 변동 금액을 양수 또는 음수로 입력하여 주십시오'
                );
            return false;
        }

        $sum = $this->Deposit_model->get_deposit_sum(element('mem_id', $mb));
        $deposit_sum = $sum + $deposit;

        if ($deposit_sum < 0) {
            $this->form_validation->set_message(
                '_deposit_sum_check',
                ' 회원님의 총 잔액이 0 보다 작아지므로 진행할 수 없습니다'
                );
            return false;
        }

        return true;
    }

    /**
     * 목록을 가져오는 메소드입니다
     */
    public function prelist()
    {
        $view = array();
        $per_page = 15;
        $page = !empty($this->input->get("page")) ? $this->input->get("page") : 1;
        $view["txt"] = !empty($this->input->get("txt")) ? $this->input->get("txt") : "";
        $view["type"] = !empty($this->input->get("type")) ? $this->input->get("type") : "";

        $where = " WHERE 1=1 ";
        if (!empty($view["txt"])){
            if ($view["type"] == "username"){
                $where .= " AND b.mem_username LIKE '%" . $view["txt"] . "%' ";
            } else if ($view["type"] == "realname") {
                $where .= " AND c.mem_realname LIKE '%" . $view["txt"] . "%' ";
            }
        }
        $sql = "
            SELECT
                COUNT(*) AS cnt
            FROM
                cb_pre_deposit_tg a
            LEFT JOIN
                cb_member b ON a.pdt_mem_id = b.mem_id
            LEFT JOIN
                cb_deposit c ON a.pdt_dep_id = c.dep_id
            " . $where . "
        ";
        $total_row = $this->db->query($sql)->row()->cnt;
        $view["total_row"] = $total_row;

        $sql = "
            SELECT
                *
            FROM
                cb_pre_deposit_tg a
            LEFT JOIN
                cb_member b ON a.pdt_mem_id = b.mem_id
            LEFT JOIN
                cb_deposit c ON a.pdt_dep_id = c.dep_id
            " . $where . "
            ORDER BY
                a.pdt_id DESC
            LIMIT " . (($page - 1) * $per_page) . ", " . $per_page . "
        ";
        $view["list"] = $this->db->query($sql)->result();

        $this->db
            ->select("SUM(pdt_amount) AS total_amt")
            ->from("cb_pre_deposit_tg");
        $view["total_amt"] = $this->db->get()->row()->total_amt;

        $this->db
            ->select("*")
            ->from("cb_pre_deposit_amt")
            ->where(array("pda_mem_id" => "962"));
        $view["limit_amt"] = $this->db->get()->row()->pda_amount;

        $view["able_amt"] = $view["limit_amt"] - $view["total_amt"];

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $total_row;
		$page_cfg['per_page'] = $per_page;
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($page);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array(
            'path' => 'deposit',
            'layout' => 'layout',
            'skin' => 'prelist',
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
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());//$this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }
}
