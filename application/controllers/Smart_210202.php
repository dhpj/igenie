<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Group class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Smart extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board');

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
        $this->load->library(array('querystring', 'funn'));
	}

	//스마트전단 보기
	public function view($code = ""){
	    $add = $this->input->get("add"); //스킨 추가
		$skin = "main";
		if($add != "") $skin .= $add;

		$view = array();
		$view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부
		
		//스마트전단 데이타
		$sql = "
		SELECT
			  a. * 	
			, b.tem_imgpath /* 텝플릿 이미지 경로 */
			, b.tem_bgcolor /* 텝플릿 배경색 */
		FROM cb_pop_screen_data a
		LEFT JOIN cb_design_templet b ON a.psd_tem_id = b.tem_id
		WHERE psd_code = '". $code ."'
		AND psd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['screen_data'] = $this->db->query($sql)->row();
		$psd_id = $view['screen_data']->psd_id; //전단번호
		$mem_id = $view['screen_data']->psd_mem_id; //회원번호
		$psd_ver_no = $view['screen_data']->psd_ver_no; //버전번호
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ." .", psd_ver_no : ". $psd_ver_no ."<br>";

		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();

		//할인&대표상품 등록 조회
		$sql = "
		SELECT *
		FROM cb_pop_screen_goods
		WHERE psg_mem_id = '". $mem_id ."' /* 회원번호 */
		and psg_psd_id = '". $psd_id ."' /* 전단번호 */
		and psg_step = '1' /* 스텝 */
		ORDER BY psg_seq ASC ";
		//echo $sql ."<br>";
		$view['screen_step1'] = $this->db->query($sql)->result();

		if($psd_ver_no == 1){ //버전 1의 경우
			$sort = "psg_step ASC,  psg_step_no ASC, psg_seq ASC";
		}else{
			$sort = "psg_step_no ASC, psg_seq ASC";
		}

		//코너별 상품등록 조회
		$sql = "
		SELECT a.*
			, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_tit_id LIMIT 1) AS tit_imgpath /* 코너별 이미지 */
			, (SELECT COUNT(*) FROM cb_pop_screen_goods b WHERE b.psg_psd_id = a.psg_psd_id AND b.psg_step = a.psg_step AND b.psg_step_no = a.psg_step_no) AS rownum /* 코너별 등록수 */
			, (SELECT COUNT(*) FROM cb_pop_screen_goods b WHERE b.psg_psd_id = a.psg_psd_id AND b.psg_step = a.psg_step AND psg_seq = 0) AS gronum /* 코너수 */
		FROM cb_pop_screen_goods a
		WHERE psg_mem_id = '". $mem_id ."' /* 회원번호 */
		AND psg_psd_id = '". $psd_id ."' /* 전단번호 */
		AND psg_step > 1 /* 스텝 */
		ORDER BY ". $sort ." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		$view['screen_box'] = $this->db->query($sql)->result();
		
		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
			'skin' => $skin,
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

	//스마트쿠폰 보기
	public function coupon($code = ""){
	    $add = $this->input->get("add"); //스킨 추가
	    //$code = $this->input->get("code"); //쿠폰코드
		$skin = "coupon";
		if($add != "") $skin .= $add;

		$view = array();
		$view['ScreenShotYn'] = ($this->input->get("ScreenShotYn")) ? $this->input->get("ScreenShotYn") : "N"; //스크린샷 허용여부
		
		//스마트전단 데이타
		$sql = "
		SELECT
			  a. * 	
		FROM cb_pop_coupon_data a
		WHERE pcd_code = '". $code ."'
		AND pcd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['data'] = $this->db->query($sql)->row();
		$psd_id = $view['data']->psd_id; //전단번호
		$mem_id = $view['data']->psd_mem_id; //회원번호
		$psd_ver_no = $view['data']->psd_ver_no; //버전번호
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ." .", psd_ver_no : ". $psd_ver_no ."<br>";

		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();
		
		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout_popup',
			'skin' => $skin,
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

	//고객분포
	public function map(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$mem_id = $this->member->item('mem_id'); //회원번호
		$mem_userid = $this->member->item('mem_userid'); //회원아이디
		
		//매장정보
		$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['shop'] = $this->db->query($sql)->row();

		//고객 주소 리스트
		$sql = "
		SELECT *
		FROM cb_ab_". $mem_userid ."
		WHERE ab_addr != '' /* 주소가 있는 것만 가져 온다. */
		ORDER BY ab_id ASC ";
		//echo $sql ."<br>";
		$view['addr_list'] = $this->db->query($sql)->result();
		
		$layoutconfig = array(
			'path' => 'smart',
			'layout' => 'layout',
			'skin' => 'map',
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
	
}
?>