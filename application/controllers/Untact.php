<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Group class
*
* Copyright (c) CIBoard <www.ciboard.co.kr>
*
* @author CIBoard (develop@ciboard.co.kr)
*/

class Untact extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board', 'Cart', 'Post', 'Post_link', 'Post_file', 'Post_extra_vars', 'Post_meta');

	/**
	* 헬퍼를 로딩합니다
	*/
	protected $helpers = array('form', 'array', 'dhtml_editor');

	function __construct()
	{
		parent::__construct();

		/**
		* 라이브러리를 로딩합니다
		*/
		$this->load->library(array('querystring', 'funn', 'alimtalk', 'accesslevel', 'email', 'notelib', 'point', 'imagelib'));
	}

	//발신목록 > 월별 발송건수
	public function calendar(){
		$key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();
		$view['param']['year'] = $year;
		$view['param']['month'] = $month;

		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['canonical'] = site_url();

		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		$sql = "
		SELECT
		*
		FROM
		cb_voucher_schedule
		WHERE
		mem_id = '".$this->member->item("mem_id")."'
		AND
		vs_useyn = 'Y'
		ORDER BY
		vs_id
		";
		$view["schedule_list"] = $this->db->query($sql)->result();

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'untact',
			'layout' => 'layout',
			'skin' => 'calendar',
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

	public function insert_schedule(){
		$mem_id = $this->member->item("mem_id");
		$calendarId = $this->input->post("calendarId");
		$end = $this->input->post("end");
		$isAllDay = $this->input->post("isAllDay") == "false" ? 0 : 1;
		$isPrivate = $this->input->post("isPrivate") == "false" ? 0 : 1;
		$location = $this->input->post("location");
		$start = $this->input->post("start");
		$state = $this->input->post("state");
		$title = $this->input->post("title");
		$useCreationPopup = $this->input->post("useCreationPopup") == "false" ? 0 : 1;
		$color = $this->input->post("color");
		$bgColor = $this->input->post("bgColor");
		$dragBgColor = $this->input->post("dragBgColor");
		$borderColor = $this->input->post("borderColor");

		$data = array();
		$data["mem_id"] = $mem_id;
		$data["vs_calendar_id"] = $calendarId;
		$data["vs_end"] = $end;
		$data["vs_is_all_day"] = $isAllDay;
		$data["vs_is_private"] = $isPrivate;
		$data["vs_location"] = $location;
		$data["vs_start"] = $start;
		$data["vs_state"] = $state;
		$data["vs_title"] = $title;
		$data["vs_use_creation_popup"] = $useCreationPopup;
		$data["vs_color"] = $color;
		$data["vs_bgcolor"] = $bgColor;
		$data["vs_drag_bgcolor"] = $dragBgColor;
		$data["vs_border_color"] = $borderColor;

		$this->db->insert("cb_voucher_schedule", $data);
		$id = $this->db->insert_id();

		header('Content-Type: application/json');
		echo '{"id": "'.$id.'"}';

	}

	public function update_schedule(){
		$id = $this->input->post("id");
		$mem_id = $this->member->item("mem_id");
		if (!empty($this->input->post("calendarId")) && $this->input->post("calendarId") != ""){
			$data["vs_calendar_id"] = $this->input->post("calendarId");
			$data["vs_color"] = $this->input->post("color");
			$data["vs_bgcolor"] = $this->input->post("bgColor");
			$data["vs_drag_bgcolor"] = $this->input->post("dragBgColor");
			$data["vs_border_color"] = $this->input->post("borderColor");
		}
		if (!empty($this->input->post("end")) && $this->input->post("end") != ""){
			$data["vs_end"] = $this->input->post("end");
		}
		if (!empty($this->input->post("isAllDay")) && $this->input->post("isAllDay")  != ""){
			$data["vs_is_all_day"] = $this->input->post("isAllDay") == "false" ? 0 : 1;
		}
		if (!empty($this->input->post("start")) && $this->input->post("start")  != ""){
			$data["vs_start"] = $this->input->post("start");
		}
		if (!empty($this->input->post("title")) && $this->input->post("title")  != ""){
			$data["vs_title"] = $this->input->post("title");
		}



		$where = array();
		$where["vs_id"] = $id;
		$where["mem_id"] = $mem_id;

		$this->db->update("cb_voucher_schedule", $data, $where);

	}

	public function delete_schedule(){
		$id = $this->input->post("id");
		$mem_id = $this->member->item("mem_id");

		$where = array();
		$where["vs_id"] = $id;
		$where["mem_id"] = $mem_id;

		$data = array();
		$data["vs_useyn"] = "N";

		$this->db->update("cb_voucher_schedule", $data, $where);

	}

    // 바우처 요금안내 페이지
	public function price(){
		/**
		* 레이아웃을 정의합니다
		*/
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'untact',
			'layout' => 'layout_home',
			'skin' => 'price',
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

	// 바우처 소개 페이지 및 신청서 작성
	public function voucher(){
		/**
		* 레이아웃을 정의합니다
		*/
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'untact',
			'layout' => 'layout_home',
			'skin' => 'voucher',
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


    // 바우처 소개 페이지 및 신청서 작성
	public function eventpage(){
		/**
		* 레이아웃을 정의합니다
		*/
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'untact',
			'layout' => 'layout_home',
			'skin' => 'eventpage',
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






	// 바우처 신청 목록
	public function lists(){
		//log_message("error", "들옴 ?");
		$key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['perpage'] = 20;
		//$view['param']['idx'] = $this->input->get("idx"); //소속 ID
		if(!empty($this->input->get("search_type"))){
			if($this->input->get("search_type")=="1"){ //업체명
				$view['search_type'] = "company_name";
			}else if($this->input->get("search_type")=="2"){ //신청자 이름
				$view['search_type'] = "user_name";
			}
		}else{
			$view['search_type'] = ""; //검색조건
		}
		$view['param']['search_for'] = $this->input->get("search_for"); //검색내용
		if(!empty($this->input->get("search_for"))){
			$view["search_for"] = $this->input->get("search_for");
		}
		$view['param']['page'] = (!empty($this->input->get("page")))? $this->input->get("page"): 1;

		$where = " where del='N' ";
		if(!empty($this->input->get("reg_date"))){
			$view["reg_date"] = $this->input->get("reg_date");
			$where .= "and reg_date >= '".$view['reg_date']." 00:00:00'";
		} else {
            $view["reg_date"] = date('Y-m-d', strtotime('-3 months'));
        }

        if(!empty($this->input->get("reg_date2"))){
			$view["reg_date2"] = $this->input->get("reg_date2");
			$where .= "and reg_date <='".$view['reg_date2']." 23:59:59'";
		} else {
            $view["reg_date2"] = date('Y-m-d');
        }

		$view['sectors'] = $this->input->get("sectors");
		if(!empty($view['sectors']) && $view['sectors']!="ALL") {
			$where .= "and sector = '".$view['sectors']."' ";
		}
		$view['state'] = $this->input->get("state");
		if(!empty($view['state']) && $view['state']!="ALL") {
			$where .= "and state = '".$view['state']."' ";
		}
		if($view['param']['search_for'] && $view['search_type']){
			$where .= "and ".$view['search_type']." like '%".$view['param']['search_for']."%' ";
		}

        $view['type'] = $this->input->get("type");
        if(!empty($view['type']) && $view['type']!="ALL") {
			if ($view['type'] == 'v'){
                $sql = "
                    SELECT *, 'v' as type
                    FROM cb_voucher " . $where . "
                    ORDER BY reg_date desc limit
                    ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
            } else if ($view['type'] == 's'){
                $sql = "
                    SELECT *, 's' as type
                    FROM cb_smartshop " . $where . "
                    ORDER BY reg_date desc limit
                    ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
            }
		} else {
            $sql = "
                SELECT *, 'v' as type
                FROM cb_voucher " . $where . "
                union all
                SELECT *, 's' as type
                FROM cb_smartshop " . $where . "
                ORDER BY reg_date desc limit
                ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        }

		$view['row'] = $this->db->query($sql)->result();


		$sql = "SELECT count(*) as cnt FROM cb_voucher ".$where;
		//log_message("ERROR", "sql : ".$sql);
		//echo $sql; exit;
		$view['total_rows'] = $this->db->query($sql)->row()->cnt; //총 count row

		$view['view']['canonical'] = site_url();
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		$this->load->library('pagination'); //페이징 라이브러리
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();


		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'untact',
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

	// 바우처 신청 정보 상세
	public function view($idx){
		//$key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

        $view['type'] = substr($idx, 0, 1);
        if ($view['type'] == 'v'){
            $type = 'voucher';
        } else if ($view['type'] == 's'){
            $type = 'smartshop';
        }
		$view['idx'] = substr($idx, 1);
		//$sql = "SELECT a.*, (SELECT COUNT(idx) FROM cb_voucher_comment WHERE board_idx=$idx AND cmt_delete = 'N') as cnt FROM cb_voucher WHERE idx='$idx'";
		$sql = "SELECT *, (SELECT COUNT(idx) FROM cb_" . $type . "_comment WHERE board_idx='" . $view['idx'] . "' AND cmt_delete = 'N') as cnt FROM cb_" . $type . " WHERE idx='" . $view['idx'] . "'";
		$view['row'] = $this->db->query($sql)->row(); //게시글 데이터 쿼리

		$sql="SELECT a.idx,	b.* FROM cb_" . $type . " a LEFT JOIN cb_" . $type . "_comment b ON a.idx = b.board_idx WHERE a.idx = " . $view['idx'] . " AND cmt_delete = 'N'";
		$view['row_cmt'] = $this->db->query($sql)->result(); // 댓글 데이터 쿼리

		$view['view']['event']['before'] = Events::trigger('before', $eventname);		$view['view']['canonical'] = site_url();
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);


		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'untact',
			'layout' => 'layout',
			'skin' => 'view',
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



	public function state_update(){ //진행상태 업데이트
		$state = $this->input->post("state");
		$board_idx = $this->input->post("board_idx");
        $type = $this->input->post("type");

        if ($type == 'v'){
            $tb = 'voucher';
        } else if ($type == 's'){
            $tb = 'smartshop';
        }

		$data = array( 'state' => $state );
		$where = array( 'idx' => $board_idx );

		$this->db->update("cb_".$tb, $data, $where);

		header('Content-Type: application/json');
		if ($this->db->error()['code'] < 1) {
				echo '{"success": true}';
		} else {
				echo '{"success": false}';
		}

	}


	public function comment_save(){ //댓글 저장

		$data = array();
		$data['board_idx'] = $this->input->post('board_idx'); //게시글 번호
		$data['reg_name'] = $this->input->post('reg_name'); //작성자명
		$data['cmt_content'] = $this->input->post('cmt_content'); //내용
		$data['cmt_delete'] = "N"; //삭제값
		$data['cmt_reg_date'] = cdate('Y-m-d H:i:s'); //등록일
        $type = $this->input->post("type");

        if ($type == 'v'){
            $tb = 'voucher';
        } else if ($type == 's'){
            $tb = 'smartshop';
        }

		$board_idx = $data['board_idx'];

		$this->db->insert("cb_" . $tb . "_comment", $data);
		$cmt_idx = $this->db->insert_id(); //방금 insert된 정보의 PK 값

		$sql = "SELECT COUNT(idx) as cmt_row FROM cb_" . $tb . "_comment WHERE cmt_delete='N' AND board_idx = '$board_idx'";
		$result = $this->db->query($sql)->row()->cmt_row;


		$data_array = ['board_idx'=>$data['board_idx'], 'reg_name'=>$data['reg_name'], 'cmt_content'=>$data['cmt_content'], 'cmt_delete'=>$data['cmt_delete'], 'cmt_reg_date'=>$data['cmt_reg_date'], 'cmt_idx'=>$cmt_idx, 'cmt_cnt'=>$result];
		echo json_encode($data_array);
	}


	public function delete_cmt(){ //댓글 삭제(delete 값 Y로 변경)

		$board_idx = $this->input->post('board_idx'); // 게시글 idx
		$idx = $this->input->post('idx'); //댓글 idx
        $type = $this->input->post("type");

        if ($type == 'v'){
            $tb = 'voucher';
        } else if ($type == 's'){
            $tb = 'smartshop';
        }

		// $data = array( 'cmt_delete' => 'Y');
		// $where = array( 'idx' => $board_idx, 'board_idx' => $board_idx );

		$sql = "UPDATE cb_" . $tb . "_comment SET cmt_delete = 'Y' WHERE board_idx = $board_idx AND idx = $idx";
		$this->db->query($sql);

		$sql = "SELECT COUNT(idx) as cmt_row FROM cb_" . $tb . "_comment WHERE cmt_delete='N' AND board_idx = $board_idx";
		$result = $this->db->query($sql)->row()->cmt_row;

		echo $result;
	}

	// 바우처 신청 정보 상세
	public function write(){
		$key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();
		$view['param']['year'] = $year;
		$view['param']['month'] = $month;

		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['canonical'] = site_url();

		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'untact',
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

	// 바우처 신청 저장
	public function save(){

		//alert($this->input->post('company_name')); exit;

        $cv = $this->input->post('choice_add_v');
        $cs = $this->input->post('choice_add_s');

		$data = array();
		$data['user_name'] = $this->input->post('user_name'); //신청자 이름
		$data['tel'] = $this->input->post('tel'); //연락처
		$data['email'] = $this->input->post('email'); //이메일
		$data['company_name'] = $this->input->post('company_name'); //회사 또는 단체명
		//$data['employees_num'] = $this->input->post('employees_num'); //도입 인원
		// $data['sector'] = $this->input->post('sector'); //고객 유형
		$data['content'] = $this->input->post('content'); //도입 목적 및 문의 내용
		$data['state'] = $this->input->post('state'); //진행 상태
		$data['reg_date'] = cdate('Y-m-d H:i:s'); //작성일

        // if ($cv == 'on'){
            $this->db->insert("cb_voucher", $data);
        // }


        $data['rec_name'] = $this->input->post('rec_name'); //추천인
        if ($cs == 'on'){
            $this->db->insert("cb_smartshop", $data);
        }
		if ($this->db->error()['code'] > 0) {
			echo "<script type='text/javascript'>alert('저장 오류입니다.');history.back();</script>";
		} else {
			echo "<script type='text/javascript'>alert('신청되었습니다.');document.location.href='/untact/voucher';</script>";
		}

	}

    // 스마트상점 신청 저장
	public function ss_save(){

		//alert($this->input->post('company_name')); exit;

        $cv = $this->input->post('choice_add_v');
        $cs = $this->input->post('choice_add_s');

		$data = array();
        $data['user_name'] = $this->input->post('user_name'); //신청자 이름
		$data['tel'] = $this->input->post('tel'); //연락처
		$data['email'] = $this->input->post('email'); //이메일
		$data['company_name'] = $this->input->post('company_name'); //회사 또는 단체명
		//$data['employees_num'] = $this->input->post('employees_num'); //도입 인원
		$data['sector'] = $this->input->post('sector'); //고객 유형
		$data['content'] = $this->input->post('content'); //도입 목적 및 문의 내용
		$data['state'] = $this->input->post('state'); //진행 상태
		$data['reg_date'] = cdate('Y-m-d H:i:s'); //작성일

        if ($cv == 'on'){
            $this->db->insert("cb_voucher", $data);
        }
        $data['rec_name'] = $this->input->post('rec_name'); //추천인
        if ($cs == 'on'){
            $this->db->insert("cb_smartshop", $data);
        }
		if ($this->db->error()['code'] > 0) {
			echo "<script type='text/javascript'>alert('저장 오류입니다.');history.back();</script>";
		} else {
			echo "<script type='text/javascript'>alert('신청되었습니다.');document.location.href='/untact/voucher?type=ss';</script>";
		}

	}
}
?>
