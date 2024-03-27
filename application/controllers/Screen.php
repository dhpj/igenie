<?php
class Screen extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	//protected $models = array('Board', 'Biz');

	/**
	* 헬퍼를 로딩합니다
	*/
	protected $helpers = array('form', 'array');

	/*
	* 파일 디렉토리 설정 및 사이즈 조절
	*/
	private $upload_path; //스마트전단 업로드 경로
	private $template_self_path; //템플릿 직접입력 업로드 경로
	private $upload_max_size; //파일 제한 사이지
	private $member_images_max_size; //이미지보관함 제한 사이지
	private $member_images_max_cnt; //이미지보관함 제한 등록수
	private $member_images_path; //이미지보관함  업로드 경로

	function __construct(){
		parent::__construct();

		/**
		* 라이브러리를 로딩합니다
		*/
		$this->load->library(array('querystring', 'funn'));

		/* 이미지 등록될 기본 디렉토리 설정 */
		$this->upload_path = config_item('uploads_dir') .'/goods/'; //스마트전단 업로드 경로

		/* 업로드할 용량지정 byte 단위 */
		$this->upload_max_size = 5 * 1024 * 1024; //파일 제한 사이지(byte 단위) (5MB)

		/* 기본 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->upload_path);

		/* 년 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->upload_path . "Y". date("Y") ."/");

		/* 월 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->upload_path . "Y". date("Y") ."/". date("m") ."/");

		$this->upload_path = $this->upload_path . "Y". date("Y") ."/". date("m") ."/";
		//echo $_SERVER['REQUEST_URI'] ." > upload_path : ". $this->upload_path ."<br>";

		/* 이미지 등록될 기본 디렉토리 설정 */
		$this->template_self_path =  config_item('uploads_dir') .'/design_templet_self/'; //스마트전단 템플릿 직접입력 업로드 경로

		/* 기본 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->template_self_path);

		/* 년 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->template_self_path . date("Y") ."/");

		$this->template_self_path = $this->template_self_path . date("Y") ."/";
		//echo $_SERVER['REQUEST_URI'] ." > template_self_path : ". $this->template_self_path ."<br>";

		$this->member_images_max_size = 10 * 1024 * 1024; //이미지보관함  제한 사이지(byte 단위) (10MB)
		//echo $_SERVER['REQUEST_URI'] ." > member_images_max_size : ". $this->member_images_max_size ."<br>";

		$this->member_images_max_cnt = 100; //이미지보관함 제한 등록수
		//echo $_SERVER['REQUEST_URI'] ." > member_images_max_cnt : ". $this->member_images_max_cnt ."<br>";

		/* 이미지 등록될 기본 디렉토리 설정 */
		$this->member_images_path =  config_item('uploads_dir') .'/member_images/'; //이미지보관함  업로드 경로

		/* 기본 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->member_images_path);

		/* 년 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->member_images_path . date("Y") ."/");

		/* 월 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->member_images_path . date("Y") ."/". date("m") ."/");

		$this->member_images_path = $this->member_images_path . date("Y") ."/". date("m") ."/";
		//echo $_SERVER['REQUEST_URI'] ." > member_images_path : ". $this->member_images_path ."<br>";
	}

	//스마트전단 > 스마트전단 목록
	public function index(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$view = array();
		$skin = "main";
		$add = $this->input->get("add");
		$view['add'] = $add;
		if($add != "") $skin .= $add;
		$view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
		$view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
		$where = "WHERE psd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND psd_type = 'S' /* 타입(S.전단, C.쿠폰, T.임시저장) */ ";
		$where .= "AND psd_useyn = 'Y' ";
		if($this->member->item("mem_level") >= 100) {
		  $where .= "AND psd_mng_yn = 'Y' /*전단관리자 목록 */ ";
		}

		//스마트전단 전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_pop_screen_data a
		". $where ." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//스마트전단 리스트
		$sql = "
		SELECT
			  psd_id /* 전단번호 */
			, psd_code /* 코드 */
			, psd_title /* 제목 */
			, psd_date /* 행사기간 */
			, DATE_FORMAT(psd_cre_date, '%Y.%m') AS psd_cre_ym /* 등록년월 */
			, DATE_FORMAT(psd_cre_date, '%d') AS psd_cre_dd /* 등록일 */
			, psd_order_yn /* 주문하기 사용여부 */
			, psd_order_sdt /* 주문하기 시작일자 */
			, psd_order_edt /* 주문하기 종료일자 */
		FROM cb_pop_screen_data a
		". $where ."
		ORDER BY psd_seq DESC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['data_list'] = $this->db->query($sql)->result();

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

		$layoutconfig = array(
			'path' => 'spop/screen',
			'layout' => 'layout',
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

	//스마트전단 > 스마트전단 만들기
	public function write(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$view = array();
		$skin = "write";
		$add = $this->input->get("add"); //추가스킨
		$view['add'] = $add;
		if($add != "") $skin .= $add;
		$view['upload_max_size'] = $this->upload_max_size; //파일 제한 사이지
		$view['upload_path'] = $this->upload_path; //스마트전단 업로드 경로
		$view['template_self_path'] = $this->template_self_path; //템플릿 직접입력 업로드 경로
		$view['md'] = $this->input->get("md"); //페이지모드
		$psd_id = $this->input->get("psd_id"); //전단번호
		$temp_id = $this->input->get("temp_id"); //임시번호

		$view['searchCate1'] = $this->input->get("searchCate1"); //대분류
		$view['searchCate2'] = $this->input->get("searchCate2"); //소분류

		if(empty($view['searchCate1'])) {
		    $sql = "select mem_img_lib_category1, mem_img_lib_category2 from cb_member cm where cm.mem_id = '".$this->member->item("mem_id")."'";
		    $img_lib_category = $this->db->query($sql)->row();

		    $view['searchCate1'] = $img_lib_category->mem_img_lib_category1; //대분류
		    $view['searchCate2'] = $img_lib_category->mem_img_lib_category2; //소분류

		}

		if($temp_id  != ""){
			$view['org_id'] = $psd_id;
			$psd_id = $temp_id;
		}

		//대분류 리스트
		$sql = "SELECT id, code, description FROM cb_images_category WHERE useyn = 'Y' AND parent_id IS NULL ORDER BY id ASC";
		//echo $sql ."<br>";
        $view['category_list'] = $this->db->query($sql)->result();

		//소분류 리스트
		if($view['searchCate1'] != ''){ //대분류가 있는 경우
			$sql = "SELECT id, code, description FROM cb_images_category WHERE parent_id = '". $view['searchCate1'] ."' and useyn = 'Y' ORDER BY id ASC";
			//echo $sql ."<br>";
			$view['category_sub'] = $this->db->query($sql)->result();
		}else{
			$view['category_sub'] = null;
		}

		//스마트전단 데이타
		$sql = "
		SELECT
			  a. *
			, b.tem_imgpath /* 템플릿 이미지 경로 */
			, b.tem_bgcolor /* 템플릿 배경색 */
			, b.tem_useyn /* 템플릿 사용여부(Y.사용, N.사용안함, S.직접입력) */
		FROM cb_pop_screen_data a
		LEFT JOIN cb_design_templet b ON a.psd_tem_id = b.tem_id
		WHERE psd_mem_id = '". $this->member->item("mem_id") ."'
		AND psd_id = '". $psd_id ."'
		AND psd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		//if($add == "_test") echo $sql ."<br>";
		$view['screen_data'] = $this->db->query($sql)->row();
		$psd_ver_no = $view['screen_data']->psd_ver_no; //버전번호

		//할인&대표상품 등록 조회
		$sql = "
		SELECT a.*
			, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_badge LIMIT 1) AS badge_imgpath /* 뱃지 이미지 */
		FROM cb_pop_screen_goods a
		WHERE psg_mem_id = '". $this->member->item("mem_id") ."' /* 회원번호 */
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
			, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_badge LIMIT 1) AS badge_imgpath /* 뱃지 이미지 */
		FROM cb_pop_screen_goods a
		WHERE psg_mem_id = '". $this->member->item("mem_id") ."' /* 회원번호 */
		AND psg_psd_id = '". $psd_id ."' /* 전단번호 */
		AND psg_step > 1 /* 스텝 */
		ORDER BY ". $sort ." ";
		//echo $sql ."<br>";
		$view['screen_box'] = $this->db->query($sql)->result();

		//디자인 이미지형 타이틀 조회
		$sql = "
		SELECT
			  tit_id /* 타이틀번호 */
			, tit_imgpath /* 이미지경로 */
		FROM cb_design_title dt
		WHERE  dt.tit_type = 'I'
		AND dt.tit_useyn = 'Y'
		ORDER BY dt.tit_seq ASC ";
		//echo $sql ."<br>";
		$view['design_title_img_list'] = $this->db->query($sql)->result();

		//디자인 텍스트형 타이틀 조회
		$sql = "
		SELECT
			  tit_id /* 타이틀번호 */
			, tit_imgpath /* 이미지경로 */
		FROM cb_design_title dt
		WHERE dt.tit_type = 'T'
		AND dt.tit_useyn = 'Y'
		ORDER BY dt.tit_seq ASC ";
		//echo $sql ."<br>";
		$view['design_title_text_list'] = $this->db->query($sql)->result();

		$layoutconfig = array(
			'path' => 'spop/screen',
			'layout' => 'layout',
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

	//이미지 탬플릿선택 모달창 > 탬플릿 리스트
	public function ajax_templet(){
		$perpage = ($this->input->post("per_page")) ? $this->input->post("per_page") : 8;
		$page = ($this->input->post("page")) ? $this->input->post("page") : 1;
		$tag_id = ($this->input->post("tag_id")) ? $this->input->post("tag_id") : "";
		$where = " WHERE dt.tem_useyn = 'Y' ";
		if($tag_id != "" && $tag_id != "all"){
			$where .= " and dt.tem_tag_id = '". $tag_id ."' ";
		}
		//echo $where ."<br>";

		//디자인 템플릿 전체 수
		$sql = "SELECT count(1) as cnt
		FROM cb_design_templet dt
		". $where;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tag_id : ". $tag_id . ", sql : ".$sql);
		$total_rows = $this->db->query($sql)->row()->cnt;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > total_rows : ".$total_rows);

		//디자인 템플릿 조회
		$sql = "SELECT dt.*
		FROM cb_design_templet dt
		". $where ."
		ORDER BY dt.tem_seq DESC
		LIMIT ".(($page - 1) * $perpage).", ".$perpage." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
		$data_list = $this->db->query($sql)->result();
		$html = "";
		foreach ($data_list as $r) {
			$html .= '<li>';
			$html .= '	<p class="tem_img" style="cursor:pointer;"><img src="'. $r->tem_imgpath .'" onClick="templet_choice(\''. $r->tem_id .'\', \''. $r->tem_bgcolor .'\',\''. $r->tem_imgpath .'\', \'base\');"></p>';
			$html .= '	<p class="tem_text">';
			$html .= '	<span>type'. $this->funn->fnZeroAdd($r->tem_id) .'</span>';
			$html .= '		<button type="button" onClick="templet_choice(\''. $r->tem_id .'\', \''. $r->tem_bgcolor .'\',\''. $r->tem_imgpath .'\', \'base\');">탬플릿선택</button>';
			$html .= '	</p>';
			$html .= '</li>';
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > html : ".$html);

		$result = array();
		$result['page'] = $page;
		$result['total_rows'] = $total_rows;
		$result['html'] = $html;

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'templet_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $total_rows;
		$page_cfg['per_page'] = $perpage;
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($page);
		$result['page_html'] = $this->pagination->create_links();
		$result['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='templet_page($1)'><a herf='#' ",$result['page_html']);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result['page_html'] : ".$result['page_html']);

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//이미지 탬플릿선택 모달창 > 탬플릿 태그 리스트
	public function ajax_templet_tag(){
		$tag_id = ($this->input->post("tag_id")) ? $this->input->post("tag_id") : "all";
		$sql = "select * from cb_design_templet_tag order by tag_seq asc ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
		$data_list = $this->db->query($sql)->result();
		$html = '<li style="cursor:pointer;" onClick="tag_choice(\'all\', \'1\');"><input type="radio" name="tag_list" id="tag_all" class="tag_list" style="cursor:pointer;" value="all" '. (("all" == $tag_id) ? "checked" : "") .'><label for="tag_all" style="cursor:pointer;">전체</label></li>';
		foreach ($data_list as $r) {
			$html .= '<li style="cursor:pointer;" onClick="tag_choice(\''. $r->tag_id .'\', \'1\');"><input type="radio" name="tag_list" id="tag_'. $r->tag_id .'" class="tag_list" style="cursor:pointer;" value="'. $r->tag_id .'" '. (($r->tag_id == $tag_id) ? "checked" : "") .'><label for="tag_'. $r->tag_id .'" style="cursor:pointer;">'. $r->tag_name .'</label></li>';
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > html : ".$html);

		$result = array();
		$result['html_tag'] = $html;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result['html_tag'] : ".$result['html_tag']);
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 삭제
	public function screen_del(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$data_id = $this->input->post("data_id"); //전단번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .",data_id : ". $data_id);

		//스마트전단 상품 삭제
		$sql = "delete from cb_pop_screen_goods where psg_mem_id = '". $mem_id ."' and psg_psd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품 삭제 : ". $sql);
		$this->db->query($sql);

		//스마트전단 데이타 삭제
		$sql = "delete from cb_pop_screen_data where psd_mem_id = '". $mem_id ."' and psd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 삭제 : ". $sql);
		$this->db->query($sql);

		$result = array();
		$result['code'] = '0';
		$result['msg'] = $data_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 Sample 설정
	public function setsample(){
	    $mem_id = $this->member->item("mem_id"); //회원번호
	    $data_id = $this->input->post("data_id"); //전단번호

	    //스마트전단 Sample 설정
	    $sql = "update cb_pop_screen_data set psd_sample = 'Y' where psd_mem_id = '". $mem_id ."' and psd_id = '". $data_id ."' ";
	    $this->db->query($sql);

	    $result = array();
	    $result['code'] = '0';
	    $result['msg'] = "OK";
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}


	//스마트전단 사용안함 처리
	public function screen_remove(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$data_id = $this->input->post("data_id"); //전단번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .",data_id : ". $data_id);

		//스마트전단 사용안함 처리
		$sql = "update cb_pop_screen_data set psd_useyn='N' where psd_mem_id = '". $mem_id ."' and psd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 사용안함 처리 : ". $sql);
		$this->db->query($sql);

		$result = array();
		$result['code'] = '0';
		$result['msg'] = $data_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 데이타 저장
	public function screen_data_save(){
		$flag = $this->input->post("flag"); //구분
		$data_id = $this->input->post("data_id"); //일련번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id);
		$data = array();
		$data["psd_tem_id"] = ($this->input->post("tem_id") != "") ? $this->input->post("tem_id") : "0"; //템플릿번호
		$data["psd_title"] = $this->input->post("psd_title"); //행사제목
		$data["psd_date"] = $this->input->post("psd_date"); //행사기간
		$data["psd_step1_yn"] = $this->input->post("step1_yn"); //할인&대표상품 사용여부
		$data["psd_step2_yn"] = $this->input->post("step2_yn"); //이미지형 상품 사용여부
		$data["psd_step3_yn"] = $this->input->post("step3_yn"); //텍스트형 상품 사용여부
		$data["psd_type"] = ($this->input->post("psd_type") != "") ? $this->input->post("psd_type") : "S"; //타입(S.전단, C.쿠폰, T.임시저장)
		$data["psd_order_yn"] = ($this->input->post("psd_order_yn") != "") ? $this->input->post("psd_order_yn") : "N"; //주문하기 사용여부 2021-03-05
		$data["psd_order_sdt"] = ($this->input->post("psd_order_sdt") != "") ? $this->input->post("psd_order_sdt") : null; //주문하기 시작일자 2021-03-05
		$data["psd_order_edt"] = ($this->input->post("psd_order_edt") != "") ? $this->input->post("psd_order_edt") : null; //주문하기 종료일자 2021-03-05
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id .", psd_tem_id = ". $data["psd_tem_id"] .", psd_title = ". $data["psd_title"] .", psd_date = ". $data["psd_date"] .", psd_step1_yn = ". $data["psd_step1_yn"] .", psd_step2_yn = ". $data["psd_step2_yn"] .", psd_step3_yn = ". $data["psd_step3_yn"]);
		if($data["psd_type"] == "T"){ //임시저장의 경우
			//임시저장 스마트전단 상품 삭제
			$sql = "
			DELETE FROM cb_pop_screen_goods
			WHERE psg_psd_id IN (
				SELECT psd_id
				FROM cb_pop_screen_data
				WHERE psd_mem_id = '". $this->member->item("mem_id") ."'
				AND psd_type = 'T'
			) ";
			$this->db->query($sql);

			//임시저장 스마트전단 데이타
			$where = array();
			$where["psd_type"] = "T"; //타입(S.전단, C.쿠폰, T.임시저장)
			$where["psd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$this->db->delete("cb_pop_screen_data", $where); //데이타 수정
		}
		if(!empty($data_id)) { //수정의 경우
			if($this->input->post("psd_ver_no") != ""){
				$data["psd_ver_no"] = $this->input->post("psd_ver_no");
			}
			$where = array();
			$where["psd_id"] = $data_id; //일련번호
			$where["psd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$rtn = $this->db->update("cb_pop_screen_data", $data, $where); //데이타 수정

			//코드 조회
			$sql = "select psd_code as code from cb_pop_screen_data where psd_id = '". $data_id ."' ";
			$code = $this->db->query($sql)->row()->code;
		}else{ //등록
			//신규 일련번호
			$sql = "select ifnull(max(psd_id),0)+1 as psd_id from cb_pop_screen_data ";
			$data_id = $this->db->query($sql)->row()->psd_id;

			//스마트전단 데이타 신규 정렬순서
			$sql = "select ifnull(max(psd_seq),0)+1 as psd_seq from cb_pop_screen_data where psd_mem_id = '". $this->member->item("mem_id") ."' ";
			$psd_seq = $this->db->query($sql)->row()->psd_seq;

			//전단코드 생성
			$this->load->library('base62');
			$surl_id = cdate('YmdHis');
			$code = $this->base62->encode($surl_id);

			//스마트전단 데이타 추가
			$data["psd_id"] = $data_id; //일련번호
			$data["psd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$data["psd_code"] = $code; //전단코드
			$data["psd_seq"] = $psd_seq; //정렬순서
			$data["psd_viewyn"] = ($this->input->post("psd_viewyn") != "") ? $this->input->post("psd_viewyn") : "Y"; //스마트전단 샘플 뷰 (Y/N)
			$data["psd_ver_no"] = ($this->input->post("psd_ver_no") != "") ? $this->input->post("psd_ver_no") : "1"; //버전번호
			$rtn = $this->db->replace("cb_pop_screen_data", $data); //데이타 추가
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id .", rtn = ". $rtn);

		$result = array();
		$result['code'] = '0';
		$result['id'] = $data_id;
		$result['psd_code'] = $code;
		$result['flag'] = $flag;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 복사
	public function screen_copy(){
		$data_id = $this->input->post("data_id"); //일련번호
		$mem_id = $this->member->item("mem_id"); //회원번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id);

		//신규 일련번호
		$sql = "select ifnull(max(psd_id),0)+1 as psd_id from cb_pop_screen_data ";
		$psd_id = $this->db->query($sql)->row()->psd_id;

		//스마트전단 데이타 신규 정렬순서
		$sql = "select ifnull(max(psd_seq),0)+1 as psd_seq from cb_pop_screen_data where psd_mem_id = '". $mem_id ."' ";
		$psd_seq = $this->db->query($sql)->row()->psd_seq;

		//전단코드 생성
		$this->load->library('base62');
		$surl_id = cdate('YmdHis');
		$code = $this->base62->encode($surl_id);

		//스마트전단 데이타 복사
		$sql = "
		INSERT INTO cb_pop_screen_data (
			  psd_id /* 전단번호 */
			, psd_mem_id /* 회원번호 */
			, psd_tem_id /* 템플릿번호 */
			, psd_code /* 코드 */
			, psd_title /* 행사제목 */
			, psd_date /* 행사기간 */
			, psd_step1_yn /* STEP1 사용여부 */
			, psd_step2_yn /* STEP2 사용여부 */
			, psd_step3_yn /* STEP3 사용여부 */
			, psd_useyn /* 사용여부 (Y/N) */
			, psd_seq /* 정렬순서 */
			, psd_type /* 타입(S.전단, C.쿠폰, T.임시저장) */
			, psd_ver_no /* 버전번호 */
		)
		SELECT
			  ". $psd_id ." AS psd_id /* 전단번호 */
			, ". $mem_id ." AS psd_mem_id /* 회원번호 */
			, psd_tem_id /* 템플릿번호 */
			, '". $code ."' AS psd_code /* 코드 */
			, CONCAT(psd_title, ' (복사)') AS psd_title /* 행사제목 */
			, psd_date /* 행사기간 */
			, psd_step1_yn /* STEP1 사용여부 */
			, psd_step2_yn /* STEP2 사용여부 */
			, psd_step3_yn /* STEP3 사용여부 */
			, psd_useyn /* 사용여부 (Y/N) */
			, ". $psd_seq ." AS psd_seq /* 정렬순서 */
			, psd_type /* 타입(S.전단, C.쿠폰, T.임시저장) */
			, psd_ver_no /* 버전번호 */
		FROM cb_pop_screen_data
		WHERE psd_id = '". $data_id ."' ";
		$this->db->query($sql);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 복사 ". $sql);

		//스마트전단 상품 복사
		// 2021-06-01 상품코드 추가
		$sql = "
		INSERT INTO cb_pop_screen_goods (
			  psg_mem_id /* 회원번호 */
			, psg_psd_id /* 전단번호 */
			, psg_tit_id /* 타이틀번호 */
			, psg_step /* 스텝 */
			, psg_step_no /* 스텝순번 */
			, psg_imgpath /* 이미지경로 */
			, psg_name /* 상품명 */
			, psg_option /* 옵션명 */
			, psg_price /* 정상가 */
			, psg_dcprice /* 할인가 */
			, psg_seq /* 정렬순서 */
			, psg_box_no /* 박스별 순번 */
			, psg_option2 /* 추가란 */
            , psg_code /* 상품코드 */
						, psg_stock /* 재고유무 */
		)
		SELECT
			  ". $mem_id ." AS psg_mem_id /* 회원번호 */
			, ". $psd_id ." AS psg_psd_id /* 전단번호 */
			, psg_tit_id /* 타이틀번호 */
			, psg_step /* 스텝 */
			, psg_step_no /* 스텝순번 */
			, psg_imgpath /* 이미지경로 */
			, psg_name /* 상품명 */
			, psg_option /* 옵션명 */
			, psg_price /* 정상가 */
			, psg_dcprice /* 할인가 */
			, psg_seq /* 정렬순서 */
			, psg_box_no /* 박스별 순번 */
			, psg_option2 /* 추가란 */
            , psg_code /* 상품코드 */
						, psg_stock /* 재고유무 */
		FROM cb_pop_screen_goods
		WHERE psg_psd_id = '". $data_id ."'
		ORDER BY psg_step ASC, psg_step_no ASC, psg_seq ASC ";
		$this->db->query($sql);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품 복사 ". $sql);

		$result = array();
		$result['code'] = '0';
		$result['id'] = $psd_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 복사
	public function screen_mng_copy(){
	    $data_id = $this->input->post("data_id"); //일련번호
	    $mem_id = $this->member->item("mem_id"); //회원번호
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id);

	    //신규 일련번호
	    $sql = "select ifnull(max(psd_id),0)+1 as psd_id from cb_pop_screen_data ";
	    $psd_id = $this->db->query($sql)->row()->psd_id;

	    //스마트전단 데이타 신규 정렬순서
	    $sql = "select ifnull(max(psd_seq),0)+1 as psd_seq from cb_pop_screen_data where psd_mem_id = '". $mem_id ."' ";
	    $psd_seq = $this->db->query($sql)->row()->psd_seq;

	    //전단코드 생성
	    $this->load->library('base62');
	    $surl_id = cdate('YmdHis');
	    $code = $this->base62->encode($surl_id);

	    //스마트전단 데이타 복사
	    $sql = "
		INSERT INTO cb_pop_screen_data (
			  psd_id /* 전단번호 */
			, psd_mem_id /* 회원번호 */
			, psd_tem_id /* 템플릿번호 */
			, psd_code /* 코드 */
			, psd_title /* 행사제목 */
			, psd_date /* 행사기간 */
			, psd_step1_yn /* STEP1 사용여부 */
			, psd_step2_yn /* STEP2 사용여부 */
			, psd_step3_yn /* STEP3 사용여부 */
			, psd_useyn /* 사용여부 (Y/N) */
			, psd_seq /* 정렬순서 */
			, psd_type /* 타입(S.전단, C.쿠폰, T.임시저장) */
			, psd_ver_no /* 버전번호 */
            , psd_mng_yn /* 전단관리자 목록 */
		)
		SELECT
			  ". $psd_id ." AS psd_id /* 전단번호 */
			, ". $mem_id ." AS psd_mem_id /* 회원번호 */
			, psd_tem_id /* 템플릿번호 */
			, '". $code ."' AS psd_code /* 코드 */
			, CONCAT(psd_title, ' (복사)') AS psd_title /* 행사제목 */
			, psd_date /* 행사기간 */
			, psd_step1_yn /* STEP1 사용여부 */
			, psd_step2_yn /* STEP2 사용여부 */
			, psd_step3_yn /* STEP3 사용여부 */
			, psd_useyn /* 사용여부 (Y/N) */
			, ". $psd_seq ." AS psd_seq /* 정렬순서 */
			, psd_type /* 타입(S.전단, C.쿠폰, T.임시저장) */
			, psd_ver_no /* 버전번호 */
            , 'Y'
		FROM cb_pop_screen_data
		WHERE psd_id = '". $data_id ."' ";
	    $this->db->query($sql);
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 복사 ". $sql);

	    //스마트전단 상품 복사
	    $sql = "
		INSERT INTO cb_pop_screen_goods (
			  psg_mem_id /* 회원번호 */
			, psg_psd_id /* 전단번호 */
			, psg_tit_id /* 타이틀번호 */
			, psg_step /* 스텝 */
			, psg_step_no /* 스텝순번 */
			, psg_imgpath /* 이미지경로 */
			, psg_name /* 상품명 */
			, psg_option /* 옵션명 */
			, psg_price /* 정상가 */
			, psg_dcprice /* 할인가 */
			, psg_seq /* 정렬순서 */
			, psg_box_no /* 박스별 순번 */
			, psg_option2 /* 추가란 */
		)
		SELECT
			  ". $mem_id ." AS psg_mem_id /* 회원번호 */
			, ". $psd_id ." AS psg_psd_id /* 전단번호 */
			, psg_tit_id /* 타이틀번호 */
			, psg_step /* 스텝 */
			, psg_step_no /* 스텝순번 */
			, psg_imgpath /* 이미지경로 */
			, psg_name /* 상품명 */
			, psg_option /* 옵션명 */
			, psg_price /* 정상가 */
			, psg_dcprice /* 할인가 */
			, psg_seq /* 정렬순서 */
			, psg_box_no /* 박스별 순번 */
			, psg_option2 /* 추가란 */
		FROM cb_pop_screen_goods
		WHERE psg_psd_id = '". $data_id ."'
		ORDER BY psg_step ASC, psg_step_no ASC, psg_seq ASC ";
	    $this->db->query($sql);
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품 복사 ". $sql);

	    $result = array();
	    $result['code'] = '0';
	    $result['id'] = $psd_id;
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

	//스마트전단 상품 삭제
	public function screen_goods_del(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$data_id = $this->input->post("data_id"); //전단번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .",data_id : ". $data_id);

		//스마트전단 상품 삭제
		$sql = "delete from cb_pop_screen_goods where psg_mem_id = '". $mem_id ."' and psg_psd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품 삭제 : ". $sql);
		$this->db->query($sql);

		$result = array();
		$result['code'] = '0';
		$result['msg'] = $data_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 상품 저장
	public function screen_goods_save(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$data_id = $this->input->post("data_id"); //전단번호
		$goods_imgpath = $this->input->post("goods_imgpath"); //이미지경로
		$goods_badge = $this->input->post("goods_badge"); //할인뱃지
		if($goods_badge == "" OR empty($goods_badge)) $goods_badge = "0";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", data_id : ". $data_id .", goods_imgpath : ". $goods_imgpath);

		//스마트전단 상품 추가
		$data = array();
		$data["psg_mem_id"] = $mem_id; //회원번호
		$data["psg_psd_id"] = $data_id; //전단번호
		$data["psg_tit_id"] = $this->input->post("tit_id"); //타이틀번호
		$data["psg_step"] = $this->input->post("goods_step"); //스텝
		$data["psg_step_no"] = $this->input->post("goods_step_no"); //스텝순번
		$data["psg_imgpath"] = $goods_imgpath; //이미지경로
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > _FILES[imgfile] : ". $_FILES['imgfile']['name']);
		if(empty($_FILES['imgfile']['name']) == false){ //이미지 업로드
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_max_size : ". $this->upload_max_size);
			$img_data = $this->img_upload($this->upload_path, 'imgfile', $this->upload_max_size, "200");
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_data : ". $img_data);
            if( is_array($img_data) && !empty($img_data) ){
				$data["psg_imgpath"] = '/'.$this->upload_path.$img_data['file_name']; //이미지 경로
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > psg_imgpath : ". $goods["psg_imgpath"]);
			}
		}
		$data["psg_name"] = $this->input->post("goods_name"); //상품명
		$data["psg_option"] = $this->input->post("goods_option"); //옵션명
		$data["psg_price"] = $this->input->post("goods_price"); //정상가
		$data["psg_dcprice"] = $this->input->post("goods_dcprice"); //할인가
		$data["psg_seq"] = $this->input->post("goods_seq"); //정렬순서
		$data["psg_badge"] = $goods_badge; //할인뱃지
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", data_id : ". $data_id .", psg_tit_id = ". $data["psg_tit_id"] .", psg_step = ". $data["psg_step"] .", psg_step_no = ". $data["psg_step_no"] .", psg_imgpath = ". $data["psg_imgpath"] .", psg_name = ". $data["psg_name"] .", psg_option = ". $data["psg_option"] .", psg_price = ". $data["psg_price"] .", psg_dcprice = ". $data["psg_dcprice"] .", psg_seq = ". $data["psg_seq"]);
		$rtn = $this->db->replace("cb_pop_screen_goods", $data); //데이타 추가
		$goods_id = $this->db->insert_id();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > goods_id : ". $goods_id .", rtn = ". $rtn);

		//이미지 라이브러리 추가
		//이미지 경로가 있는 경우
		if($goods_imgpath != "" && 1 == 2){ //2020-10-07 기능 막음
			//썸네일 파일이 존재하는지 확인
			$filepath = $goods_imgpath;
			$filepath_exp = explode(".", $filepath);
			$file_name = $filepath_exp[0]; //파일명 (경로포함)
			$file_ext = $filepath_exp[1]; //파일 확장자
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filepath : ". $filepath .", file_name : ". $file_name .", file_ext : ". $file_ext);
			$filepath_thumb = $file_name ."_thumb.". $file_ext;
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filepath_thumb : ". $filepath_thumb);

			//이미지 라이브러리 확인
			$img_name = trim(trim($data["psg_name"]) ." ". trim($data["psg_option"]));
			$sql = "SELECT COUNT(*) AS cnt FROM cb_images where img_name = '". $img_name ."' ";
			$img_cnt = $this->db->query($sql)->row()->cnt;
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_cnt : ". $img_cnt);

			//이미지 라이브러리 저장
			$data = array();
			$data["img_category1"] = 0; //카테고리번호
			$data["img_category2"] = 0; //하위카테고리번호
			$data["img_path"] = $goods_imgpath; //이미지경로
			if(file_exists($_SERVER["DOCUMENT_ROOT"] . $filepath_thumb)) {
				$data["img_path_thumb"] = $filepath_thumb; //썸네일 이미지경로
			}
			if($img_cnt == 0){ //신규등록
				$data["img_name"] = $img_name; //이미지명
				$data["img_cre_id"] = $mem_id; //회원번호
				$rtn = $this->db->replace("cb_images", $data); //이미지 라이브러리 추가
			}else{ //수정
				$where = array();
				$where["img_name"] = $img_name; //이미지명
				$data["img_upd_id"] = $mem_id; //회원번호
				$rtn = $this->db->update("cb_images", $data, $where); //이미지 라이브러리 수정
			}
		}

		$result = array();
		$result['code'] = '0';
		$result['id'] = $goods_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 상품 저장 (배열)
	public function screen_goods_arr_save(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$updata = $this->input->post('updata'); //배열 데이타
	    $datas = json_decode( $updata );
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", datas : ". $datas);
		$cnt = 0;
		foreach($datas as $r) {
			$data_id = $r->data_id; //전단번호
			$tit_id = $r->tit_id; //타이틀번호
			if($tit_id == "" OR empty($tit_id)) $tit_id = "0"; //타이틀번호
			$goods_imgpath = $r->goods_imgpath; //이미지경로
			$goods_badge = $r->goods_badge; //할인뱃지
			if($goods_badge == "" OR empty($goods_badge)) $goods_badge = "0"; //할인뱃지
			$box_no = $r->box_no; //박스별 순번
			if($box_no == "" OR empty($box_no)) $box_no = "0"; //박스별 순번
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > [". $cnt ."] mem_id : ". $mem_id .", data_id : ". $data_id .", goods_imgpath : ". $goods_imgpath);

			//스마트전단 상품 추가
			$data = array();
			$data["psg_mem_id"] = $mem_id; //회원번호
			$data["psg_psd_id"] = $data_id; //전단번호
			$data["psg_tit_id"] = $tit_id; //타이틀번호
			$data["psg_step"] = $r->goods_step; //스텝
			$data["psg_step_no"] = $r->goods_step_no; //스텝순번
			$data["psg_imgpath"] = $goods_imgpath; //이미지경로
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > _FILES[imgfile] : ". $_FILES['imgfile']['name']);
			/*if(empty($_FILES['imgfile']['name']) == false){ //이미지 업로드
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_max_size : ". $this->upload_max_size);
				$img_data = $this->img_upload($this->upload_path, 'imgfile', $this->upload_max_size, "200");
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_data : ". $img_data);
				if( is_array($img_data) && !empty($img_data) ){
					$data["psg_imgpath"] = '/'.$this->upload_path.$img_data['file_name']; //이미지 경로
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > psg_imgpath : ". $goods["psg_imgpath"]);
				}
			}*/
			$data["psg_name"] = $r->goods_name; //상품명
			$data["psg_option"] = $r->goods_option; //옵션명
			$data["psg_price"] = $r->goods_price; //정상가
			$data["psg_dcprice"] = $r->goods_dcprice; //할인가
			$data["psg_seq"] = $r->goods_seq; //정렬순서
			$data["psg_badge"] = $goods_badge; //할인뱃지
			$data["psg_box_no"] = $box_no; //박스별 순번
			$data["psg_option2"] = $r->goods_option2; //추가란
			$data["psg_code"] = $r->goods_barcode; //바코드  2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
			$data["psg_stock"] = $r->goods_stock; //품절여부 2021-06-17 추가
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", data_id : ". $data_id .", psg_tit_id = ". $data["psg_tit_id"] .", psg_step = ". $data["psg_step"] .", psg_step_no = ". $data["psg_step_no"] .", psg_imgpath = ". $data["psg_imgpath"] .", psg_name = ". $data["psg_name"] .", psg_option = ". $data["psg_option"] .", psg_price = ". $data["psg_price"] .", psg_dcprice = ". $data["psg_dcprice"] .", psg_seq = ". $data["psg_seq"]);
			$rtn = $this->db->replace("cb_pop_screen_goods", $data); //데이타 추가
			$goods_id = $this->db->insert_id();
			$cnt++;
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > goods_id : ". $goods_id .", rtn = ". $rtn);
		}
		$result = array();
		$result['code'] = '0';
		$result['cnt'] = $cnt;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//이미지 저장
	public function imgfile_save(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > _FILES[imgfile] : ". $_FILES['imgfile']['name']);
		$imgpath = "";
		if(empty($_FILES['imgfile']['name']) == false){ //이미지 업로드
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_max_size : ". $this->upload_max_size);
			$img_data = $this->img_upload($this->upload_path, 'imgfile', $this->upload_max_size, "200");
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_data : ". $img_data);
            if( is_array($img_data) && !empty($img_data) ){
				$imgpath = '/'.$this->upload_path.$img_data['file_name']; //이미지 경로
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > imgpath : ". $imgpath);
			}
		}
		$result = array();
		$result['code'] = '0';
		$result['imgpath'] = $imgpath;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//이미지 업로드 함수
	public function img_upload($upload_img_path = null, $field_name = null, $size = null, $thumb = ""){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_upload Start");
		if( is_dir($upload_img_path)  == false ){
			//alert('해당 디렉도리가 존재 하지 않음'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 해당 디렉도리가 존재 하지 않음");
		}
		if ( is_null($field_name) ){
			//alert('필드값을 기입하지 않았습니다.'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 필드값을 기입하지 않았습니다.");
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
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 업로드 성공 > filedata : ". $filedata .", thumb : ". $thumb);
				if($thumb != ""){ //썸네일 추가
					$this->load->library('image_lib');
					$this->image_lib->clear();
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > full_path : ". $filedata['full_path']);
					$simgconfig = array();
					$simgconfig['image_library'] = 'gd2';
					$simgconfig['source_image']	= $filedata['full_path'];
					$simgconfig['maintain_ratio'] = TRUE;
					$simgconfig['width']	= $thumb; //썸네일 가로 사이즈
					$simgconfig['height']= $thumb; //썸네일 가로 사이즈
					$simgconfig['new_image'] = $filedata['file_path'].$filedata['raw_name'].'_thumb'.$filedata['file_ext'];
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > new_image : ". $simgconfig['new_image']);
					$this->image_lib->initialize($simgconfig);
					if(!$this->image_lib->resize()) {
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 썸네일 이미지 저장 실패 : ". $this->upload->display_errors());
					}
				}
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filedata : ". $filedata);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > full_path : ". $filedata['full_path']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file_name : ". $filedata['file_name']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > orig_name : ". $filedata['orig_name']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file_size : ". $filedata['file_size']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file_ext : ". $filedata['file_ext']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > image_width : ". $filedata['image_width']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > image_height : ". $filedata['image_height']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > image_type : ". $filedata['image_type']);
				//$this->resize_image($filedata, 300, 300); //이미지를 원하는 크기(원본비율 유지)로 리사이즈
				//$image = new Image($filedata['full_path']);
				//list($filedata['image_width'], $filedata['image_height']) = getimagesize($filedata['full_path']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > resize_image OK");
				return $filedata;
			}else{
				//업로드 실패
				$error = $this->upload->display_errors();
				//alert($error, '/maker'); exit('');
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 업로드 실패");
			}
		} else {
			//alert('파일이 업로드 되지 않았습니다.'); exit('');
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 파일이 업로드 되지 않았습니다.");
		}
	}

	//이미지 라이브러리 조회
	public function search_library(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Start");
		$result = array();
		$result['perpage'] = ($this->input->post("perpage")) ? $this->input->post("perpage") : 15; //개시물수
		$result['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1; //현재페이지
		$result['searchCate1'] = $this->input->post("searchCate1"); //대분류
		$result['searchCate2'] = $this->input->post("searchCate2"); //소분류
		$result['sn'] = ($this->input->post("searchnm")) ? $this->input->post("searchnm") : "name"; //검색이름
		$result['sf'] = $this->input->post("searchstr"); //검색내용
		$result['library_type'] = $this->input->post("library_type"); //라이브러리 타입
		$result['page_yn'] = ($this->input->post("page_yn")) ? $this->input->post("page_yn") : "N"; //페이징 처리여부
		$html = "";
		$where = "";
		if($result['library_type'] == "goods"){ //최근상품 조회
			//---------------------------------------------------------------------------------------------------------------------
			// 최근상품 조회
			//---------------------------------------------------------------------------------------------------------------------
			$tbl = "cb_pop_screen_goods";
			$where .= "AND psg_mem_id = '". $this->member->item("mem_id") ."' ";
			if(!empty($result['sf'])) {
				$orstr = explode(' ', $result['sf']);
				$orcnt = count($orstr);
				if($orcnt > 1) {
					$where .= "AND ( ";
					$prefix = '';
					for($i=0; $i<$orcnt; $i++){
						$where .= $prefix." psg_name LIKE '%". addslashes($orstr[$i]) ."%' ";
						$prefix = "AND ";
					}
					$where .= " ) ";
				} else {
					$where .= " AND psg_name LIKE '%". addslashes($orstr[0]) ."%' ";
				}
			}else{
				$where .= "AND psg_name != '' ";
			}
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > where : ". $where);

			//상품 전체수
			$sql = "
			SELECT
				COUNT(*) AS cnt
			FROM ". $tbl ."
			WHERE psg_id IN (
				SELECT MAX(psg_id)
				FROM ". $tbl ." a
				WHERE psg_imgpath != ''
				AND psg_dcprice != ''
				". $where ."
				GROUP BY psg_name
			) ";
			$result['total'] = $this->db->query($sql)->row()->cnt;

			//상품 현황
			$sql = "
			SELECT
				  psg_id AS img_id /* 전단상품번호 */
				, psg_name AS img_name /* 상품명 */
				, psg_price /* 정상가 */
				, psg_dcprice /* 할인가 */
				, psg_imgpath AS img_path /* 사진경로 */
			FROM ". $tbl ."
			WHERE psg_id IN (
				SELECT MAX(psg_id)
				FROM ". $tbl ." a
				WHERE psg_imgpath != ''
				AND psg_dcprice != ''
				". $where ."
				GROUP BY psg_name
			)
			ORDER BY psg_name ASC
			LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'] ;
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$list = $this->db->query($sql)->result();
			if(!empty($list)){
				foreach ($list as $r){
					$img_name = $r->img_name;
					if(!empty($result['sf'])) {
						//$img_name = str_replace($result['sf'], "<font color='red'>". addslashes($result['sf']) ."</font>", $img_name);
						$orstr = explode(' ', $result['sf']);
						$orcnt = count($orstr);
						for($i=0; $i<$orcnt; $i++){
							$img_name = str_replace($orstr[$i], "<font color='red'>". addslashes($orstr[$i]) ."</font>", $img_name);
						}
					}
					$html .= "
					<li class=\"img_select\" title=\"".$r->img_name."\">
						<a onClick=\"set_goods_library('". $r->img_path ."', '". addslashes($r->img_name) ."', '". addslashes($r->psg_price) ."', '". addslashes($r->psg_dcprice) ."')\">
							<input type=\"radio\" name=\"chk_image\" id=\"chk". $r->img_id ."\" value=\"". $r->img_id ."\" title=\"". $r->img_name ."\"><label for=\"chk". $r->img_id ."\"></label>
							<div class=\"thumb_img\" style=\"background-image: url('". $r->img_path ."');background-size: contain;\"><input type=\"hidden\" id=\"img_". $r->id ."\" value=\"". $r->img_path ."\"></div>
						</a>
						<div class=\"goods_name\">". $img_name ."</div>
						<div class=\"goods_price\" style=\"display:". (($r->psg_price != "") ? "" : "none") ."\">". $r->psg_price ."</div>
						<div class=\"goods_dcprice\" style=\"display:". (($r->psg_dcprice != "") ? "" : "none") ."\">". $r->psg_dcprice ."</div>
					</li>";
				}
			}else{
				$html .= "<li style=\"width:100%;\">no data.</li>";
			}
		}else if($result['library_type'] == "keep"){ //이미지보관함 조회
			//---------------------------------------------------------------------------------------------------------------------
			// 이미지보관함 조회
			//---------------------------------------------------------------------------------------------------------------------
			$where = " WHERE img_useyn = 'Y' ";
			$where .= "AND img_mem_id = '". $this->member->item("mem_id") ."' ";
			if(!empty($result['sf'])) {
				$orstr = explode(' ', $result['sf']);
				$orcnt = count($orstr);
				if($orcnt > 1) {
					$where .= "AND ( ";
					$prefix = '';
					for($i=0; $i<$orcnt; $i++){
						$where .= $prefix." img_name LIKE '%". addslashes($orstr[$i]) ."%' ";
						$prefix = "AND ";
					}
					$where .= " ) ";
				} else {
					$where .= " AND img_name LIKE '%". addslashes($orstr[0]) ."%' ";
				}
			}

			//이미지 전체수
			$sql = "SELECT COUNT(*) AS cnt FROM cb_member_images ". $where;
			$result['total'] = $this->db->query($sql)->row()->cnt;

			//이미지 현황
			$sql = "
			SELECT
				  img_id /* 이미지번호 */
				, img_name /* 이미지 이름 */
				, img_path /* 이미지 경로 */
				, img_path AS img_path_thumb /* 썸네일 이미지 경로 */
			FROM cb_member_images
			". $where ."
			ORDER BY img_id DESC
			LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$list = $this->db->query($sql)->result();
			if(!empty($list)){
				foreach ($list as $r){
					$img_name = $this->funn->StringCut($r->img_name, 20, "..");
					if(!empty($result['sf'])) {
						//$img_name = str_replace($result['sf'], "<font color='red'>". addslashes($result['sf']) ."</font>", $img_name);
						$orstr = explode(' ', $result['sf']);
						$orcnt = count($orstr);
						for($i=0; $i<$orcnt; $i++){
							$img_name = str_replace($orstr[$i], "<font color='red'>". addslashes($orstr[$i]) ."</font>", $img_name);
						}
					}
					$html .= "
					<li class=\"img_select\" title=\"".$r->img_name."\" >
						<a onClick=\"set_img_library('". $r->img_path ."')\">
							<input type=\"radio\" name=\"chk_image\" id=\"chk". $r->img_id ."\" value=\"". $r->img_id ."\" title=\"". $r->img_name ."\"><label for=\"chk". $r->img_id ."\"></label>
							<div class=\"thumb_img\" style=\"background-image: url('". $r->img_path_thumb ."');background-size: contain;\"><input type=\"hidden\" id=\"img_". $r->id ."\" value=\"". $r->img_path ."\"></div>
						</a>
						<div class=\"file_name\">". $img_name ."</div>
					</li>";
				}
			}else{
				$html .= "<li style=\"width:100%;\">no data.</li>";
			}
		}else{ //이미지 라이브러리 조회
			//---------------------------------------------------------------------------------------------------------------------
			// 이미지 라이브러리 조회
			//---------------------------------------------------------------------------------------------------------------------
			$where = " WHERE i.img_useyn = 'Y' ";
			if($result['searchCate1'] != ''){ //대분류
				$where .= " AND img_category1 = '". $result['searchCate1'] ."' ";
			}
			if($result['searchCate2'] != ''){ //소분류
				$where .= " AND img_category2 = '". $result['searchCate2'] ."' ";
			}
			if(!empty($result['sf'])) {
				$orstr = explode(' ', $result['sf']);
				$orcnt = count($orstr);
				if($orcnt > 1) {
					$where .= " AND ( ";
					$prefix = '';
					for($i=0; $i<$orcnt; $i++){
						$where .= $prefix." i.img_". $result['sn'] ." LIKE '%". addslashes($orstr[$i]) ."%' ";
						$prefix = " AND ";
					}
					$where .= " ) ";
				} else {
					$where .= " AND i.img_". $result['sn'] ." LIKE '%". addslashes($orstr[0]) ."%' ";
				}
			}

			//이미지 전체수
			$sql = "SELECT COUNT(*) AS cnt FROM cb_images i ". $where;
			$result['total'] = $this->db->query($sql)->row()->cnt;

			//이미지 현황
			$sql = "
			SELECT
				  img_id /* 이미지번호 */
				, img_name /* 이미지 이름 */
				, img_path /* 이미지 경로 */
				, (CASE when img_path_thumb != '' THEN img_path_thumb ELSE img_path END) AS img_path_thumb /* 썸네일 이미지 경로 */
			FROM cb_images i
			". $where ."
			ORDER BY i.img_name ASC
			LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$list = $this->db->query($sql)->result();
			if(!empty($list)){
				foreach ($list as $r){
					$img_name = $r->img_name;
					if(!empty($result['sf'])) {
						//$img_name = str_replace($result['sf'], "<font color='red'>". addslashes($result['sf']) ."</font>", $img_name);
						$orstr = explode(' ', $result['sf']);
						$orcnt = count($orstr);
						for($i=0; $i<$orcnt; $i++){
							$img_name = str_replace($orstr[$i], "<font color='red'>". addslashes($orstr[$i]) ."</font>", $img_name);
						}
					}
					$html .= "
					<li class=\"img_select\" title=\"".$r->img_name."\" >
						<a onClick=\"set_img_library('". $r->img_path ."')\">
							<input type=\"radio\" name=\"chk_image\" id=\"chk". $r->img_id ."\" value=\"". $r->img_id ."\" title=\"". $r->img_name ."\"><label for=\"chk". $r->img_id ."\"></label>
							<div class=\"thumb_img\" style=\"background-image: url('". $r->img_path_thumb ."');background-size: contain;\"><input type=\"hidden\" id=\"img_". $r->id ."\" value=\"". $r->img_path ."\"></div>
						</a>
						<div class=\"file_name\">". $img_name ."</div>
					</li>";
				}
			}else{
				$html .= "<li style=\"width:100%;\">no data.</li>";
			}
		}

		//페일징 처리
		if($result['page_yn'] == "Y"){
			$this->load->library('pagination');
			$page_cfg['link_mode'] = 'searchLibraryPage';
			$page_cfg['base_url'] = '';
			$page_cfg['total_rows'] = $result['total'];
			$page_cfg['per_page'] = $result['perpage'];
			$this->pagination->initialize($page_cfg);
			$this->pagination->cur_page = intval($result['page']);
			$result['page_html'] = $this->pagination->create_links();
			$result['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='searchLibraryPage($1)'><a herf='#' ",$result['page_html']);
		}else{
			$result['page_html'] = "";
		}

		$result['html'] = $html;
		$result['page']++;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//포스 상품조회
	public function ajax_pos_goods(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Start");
		$result = array();
		$result['perpage'] = ($this->input->post("perpage")) ? $this->input->post("perpage") : 15; //개시물수
		$result['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1; //현재페이지
		$result['pos_sc'] = ($this->input->post("pos_sc")) ? $this->input->post("pos_sc") : "name"; //검색구분
		$result['pos_sv'] = $this->input->post("pos_sv"); //검색내용
		$result['pos_sort'] = ($this->input->post("pos_sort")) ? $this->input->post("pos_sort") : "id"; //정렬
		$html = "";
		$where = "";
		$where .= "AND psg_mem_id = '". $this->member->item("mem_id") ."' ";
		if(!empty($result['pos_sv'])) {
			$orstr = explode(' ', $result['pos_sv']);
			$orcnt = count($orstr);
			if($orcnt > 1) {
				$where .= "AND ( ";
				$prefix = '';
				for($i=0; $i<$orcnt; $i++){
					$where .= $prefix." psg_". $result['pos_sc'] ." LIKE '%". addslashes($orstr[$i]) ."%' ";
					$prefix = "AND ";
				}
				$where .= " ) ";
			} else {
				$where .= " AND psg_". $result['pos_sc'] ." LIKE '%". addslashes($orstr[0]) ."%' ";
			}
		}else{
			$where .= "AND psg_name != '' ";
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > where : ". $where);

		//상품 전체수
		$sql = "
		SELECT
			COUNT(*) AS cnt
		FROM cb_pop_screen_goods_pos
		WHERE 1=1
		". $where;
		$result['total'] = $this->db->query($sql)->row()->cnt;

		//상품 현황
		$sql = "
		SELECT
			  psg_id AS img_id /* 일련번호 */
			, psg_code /* 상품코드 */
			, psg_name AS img_name /* 상품명 */
			, psg_option /* 규격 */
			, psg_price /* 정상가 */
			, psg_dcprice /* 할인가 */
			, (SELECT img_path FROM cb_images b WHERE b.img_code = a.psg_code LIMIT 1) AS img_path /* 이미지경로 */
		FROM cb_pop_screen_goods_pos a
		WHERE 1=1
		". $where ."
		ORDER BY psg_". $result['pos_sort'] ." ASC
		LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'] ;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		$list = $this->db->query($sql)->result();
		if(!empty($list)){
			foreach ($list as $r){
				$img_name = $r->img_name; //상품명
				$psg_option = $r->psg_option; //규격
				if($psg_option != "") $img_name .= " ". $psg_option; //상품명 규격
				$img_path = $r->img_path; //이미지경로
				if($img_path == ""){
					//스마트전단 전체수
					$sql = "
					SELECT psg_imgpath
					FROM cb_pop_screen_goods a
					WHERE psg_mem_id = '". $this->member->item("mem_id") ."'
					AND psg_imgpath != ''
					AND psg_name = '". addslashes($img_name) ."'
					ORDER BY psg_id DESC
					LIMIT 1 ";
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
					$img_path = $this->db->query($sql)->row()->psg_imgpath;
				}
				// 2021-06-01 pos_goods_barcode 부분 추가 - 이수환
				$html .= "
				<li class=\"img_select\" title=\"".$img_name."\">
				    <label class=\"check_con\" for=\"chk". $r->img_id ."\">
				      <input type=\"checkbox\" name=\"pos_goods_chk\" id=\"chk". $r->img_id ."\" value=\"". $r->img_id ."\" title=\"". $img_name ."\">
				      <span class=\"checkmark\"></span>
				    </label>
					<div class=\"thumb_img\" style=\"background-image: url('". $img_path ."');background-size: contain;\"><input type=\"hidden\" id=\"img_". $r->id ."\" value=\"". $img_path ."\"></div>
					<div class=\"goods_name\">". $img_name ."</div>
					<div id=\"pos_goods_price". $r->img_id ."\" class=\"goods_price\" style=\"display:". (($r->psg_price != "") ? "" : "none") ."\">". $r->psg_price ."</div>
					<div id=\"pos_goods_dcprice". $r->img_id ."\" class=\"goods_dcprice\" style=\"display:". (($r->psg_dcprice != "") ? "" : "none") ."\">". $r->psg_dcprice ."</div>
					<div id=\"pos_goods_name". $r->img_id ."\" style=\"display:none;\">". addslashes($r->img_name) ."</div>
					<div id=\"pos_goods_option". $r->img_id ."\" style=\"display:none;\">". addslashes($r->psg_option) ."</div>
					<div id=\"pos_goods_imgpath". $r->img_id ."\" style=\"display:none;\">". $img_path ."</div>
                    <div id=\"pos_goods_barcode". $r->img_id ."\" style=\"display:none;\">". $r->psg_code ."</div>
				</li>";
			}
		}else{
			$html .= "<li style=\"width:100%;\">no data.</li>";
		}
		$result['html'] = $html;
		$result['page']++;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//상품 엑셀 업로드
	public function excel_upload_ok(){
	    $result = array();
		$rtn_data = $this->input->post('rtn_data'); //리턴값
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file : ". $_FILES['file']['tmp_name']);
		//업로드된 엑셀 파일을 처리함
	    if(file_exists($_FILES['file']['tmp_name'])) {
			$this->load->library("excel");
			$inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > inputFileType : ". $inputFileType);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly( true );
			$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
			$sheetsCount = $objPHPExcel->getSheetCount();

			// 쉬트별로 읽기
			for($i = 0; $i < $sheetsCount; $i++){
				$sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
				$sheet = $objPHPExcel->getActiveSheet();
				$highestRow = $sheet->getHighestRow();
				$highestColumn = $sheet->getHighestColumn();
				//log_message("ERROR", "Rows : ".$highestRow);
				// 한줄읽기
				for ($row = 2; $row <= $highestRow; $row++){ //2번줄부터 읽기
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name : ". $rowData[0][0] .", price : ". $rowData[0][1] .", dcprice : ". $rowData[0][2]);
					//$code = ""; //상품코드
					//$name = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품명
					//$option = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //옵션명
					//$price = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //정상가
					//$dcprice = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //할인가
					$code = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품코드
					$name = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //상품명
					$option = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //옵션명
					$price = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //정상가
					$dcprice = (empty($rowData[0][4])) ? "" : $rowData[0][4]; //할인가
					$option2 = (empty($rowData[0][5])) ? "" : $rowData[0][5]; //추가란 2021-01-22
					$sch_name = $name;
					if($option != "") $sch_name .= " ". $option;
					$result[$row-2]['code'] = $code; //상품코드
					$result[$row-2]['name'] = $name; //상품명
					$result[$row-2]['option'] = $option; //옵션명
					$result[$row-2]['price'] = $price; //정상가
					$result[$row-2]['dcprice'] = $dcprice; //할인가
					$result[$row-2]['option2'] = $option2; //추가란 2021-01-22
					$result[$row-2]['img_path'] = $this->img_path($code, $sch_name, $name, $option); //이미지 경로
					$result[$row-2]['seq'] = ($row-2); //정렬순서
					$result[$row-2]['rtn_data'] = $rtn_data; //리턴값
				}
			}
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ". $result);
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > json : ". $json);
		echo $json;
	}

	//이미지 경로 조회
	public function img_path($code, $sch_name, $name, $option){
		$img_path = "";
		if($code != ""){ //이미지 라이브러리 > 상품코드로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT img_path
			FROM cb_images
			WHERE img_useyn = 'Y'
			AND img_code = '". addslashes($code) ."'
			LIMIT 1 ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}
		if($img_path == "" and $name != ""){ //최근상품 > 이미지명으로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT psg_imgpath AS img_path
			FROM cb_pop_screen_goods
			WHERE psg_id = (
				SELECT MAX(psg_id)
				FROM cb_pop_screen_goods
				WHERE psg_imgpath != ''
				AND psg_dcprice != ''
				AND psg_mem_id = '3'
				AND psg_name = '". addslashes($name) ."'
				AND psg_option = '". addslashes($option) ."'
				GROUP BY psg_name
			)
			LIMIT 1 ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}
		if($img_path == "" and $sch_name != ""){ //이미지 라이브러리 > 이미지명으로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT img_path
			FROM cb_images
			WHERE img_useyn = 'Y'
			AND img_name = '". addslashes($sch_name) ."'
			LIMIT 1 ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_path : ". $img_path);
		return $img_path;
	}

	//스마트전단 데이타 조회
	public function smart_data(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 조회");
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		$result = array();
		$view = array();
		$view['view'] = array();
		$view['perpage'] = ($this->input->post("per_page")) ? $this->input->post("per_page") : 8;
		$view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
		$view['psdtype'] = ($this->input->post("psdtype")) ? $this->input->post("psdtype") : "S"; //타입(S.전단, C.쿠폰, T.임시저장)

		//검색조건
		$where = "WHERE psd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND psd_type = '". $view['psdtype'] ."' /* 타입(S.전단, C.쿠폰, T.임시저장) */ ";
		$where .= "AND psd_useyn = 'Y' ";

		//스마트전단 전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_pop_screen_data a
		". $where ." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//스마트전단 리스트
		$sql = "
		SELECT
			  psd_id /* 전단번호 */
			, psd_code /* 전단코드 */
			, psd_title /* 제목 */
			, psd_date /* 행사기간 */
			, (SELECT tem_imgpath FROM cb_design_templet b WHERE b.tem_id = a.psd_tem_id LIMIT 1) AS tem_imgpath /* 템플릿 이미지 */
			, DATE_FORMAT(psd_cre_date, '%y.%m.%d') AS psd_credt /* 등록일자 */
		FROM cb_pop_screen_data a
		". $where ."
		ORDER BY psd_seq DESC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$result = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'smart_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='smart_page($1)'><a herf='#' ",$view['page_html']);
		$result['page_html'] = $view['page_html'];
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result[page_html] : ".$result['page_html']);

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		$json = str_replace('null', '""', $json);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 링크 URL 만들기
	public function smart_dhnlurl(){
		$this->load->library('dhnlurl');
		$code = $this->input->post("code");
		$result = array();
		$result['dhnlurl'] = 'http://'. $this->dhnlurl->get_short('http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $code);
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 상품정보 가져오기
	public function smart_goods_call(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 조회");
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		$result = array();
		$psd_code = $this->input->post("psd_code"); //전단코드

		//스마트전단 데이타
		$sql = "
		SELECT
			a. *
		FROM cb_pop_screen_data a
		WHERE psd_mem_id = '". $this->member->item("mem_id") ."'
		AND psd_code = '". $psd_code ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		//if($add == "_test") echo $sql ."<br>";
		$screen_data = $this->db->query($sql)->row();
		$psd_id = $screen_data->psd_id; //전단번호
		$psd_ver_no = $screen_data->psd_ver_no; //버전번호

		if($psd_ver_no == 1){ //버전 1의 경우
			$sort = "psg_step ASC,  psg_step_no ASC, psg_seq ASC";
		}else{
			$sort = "psg_step_no ASC, psg_seq ASC";
		}

		//코너별 상품등록 조회
		$sql = "
		SELECT a.*
		FROM cb_pop_screen_goods a
		WHERE psg_mem_id = '". $this->member->item("mem_id") ."' /* 회원번호 */
		AND psg_psd_id = '". $psd_id ."' /* 전단번호 */
		ORDER BY ". $sort ." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$result = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//템플릿 직접입력 저장
	public function template_self_save(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$imgpath = $this->input->post('imgpath'); //템플릿 직접입력 이미지 경로

		$data = array();
		$sql = "select ifnull(max(tem_id),0)+1 as tem_id from cb_design_templet ";
		$tem_id = $this->db->query($sql)->row()->tem_id;
		$data["tem_id"] = $tem_id; //템플릿번호
		$data["tem_mem_id"] = $this->member->item("mem_id"); //회원번호
		$data["tem_tag_id"] = $this->input->post("tag_id"); //템플릿 태그번호
		$data["tem_imgpath"] = $this->input->post("imgpath"); //템플릿 이미지 경로
		$data["tem_bgcolor"] = $this->input->post("bgcolor"); //템플릿 배경색상
		$data["tem_useyn"] = $this->input->post("useyn"); //템플릿 사용여부
		$data["tem_seq"] = $tem_id; //정렬순서
		$rtn = $this->db->replace("cb_design_templet", $data);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > goods_id : ". $goods_id .", rtn = ". $rtn);

		$result = array();
		$result['code'] = '0';
		$result['tem_id'] = $tem_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//뱃지 리스트 조회
	public function ajax_badge_list(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 조회");
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		$result = array();

		//뱃지이미지 리스트
		$sql = "
		SELECT *
		FROM cb_design_title a
		WHERE tit_type = 'B' /* 구분(I.이미지형, T.텍스트형, B.뱃지이미지) */
		AND tit_useyn = 'Y' /* 사용여부 (Y/N) */
		ORDER BY tit_seq ASC ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$result = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 > 이미지보관함 > 목록 2021-02-04
	public function images(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		//echo $_SERVER['REQUEST_URI'] ."<br>";
		$view = array();
		$view['view'] = array();
		$view['upload_max_size'] = $this->member_images_max_size; //파일 제한 사이지
		$view['member_images_max_cnt'] = $this->member_images_max_cnt; //파일 제한 등록수
		$view['member_images_path'] = $this->member_images_path; //이미지 경로
		$view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 20; //리스트수
		$view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1; //현재페이지
		$view['sort'] = ($this->input->get("sort")) ? $this->input->get("sort") : "1"; //정렬 (1.등록일순, 2.제품명순)
		$view['searchType'] = $this->input->get("searchType"); //검색타입
		$view['searchStr'] = $this->input->get("searchStr"); //검색내용
		$where = "WHERE i.img_mem_id = '". $this->member->item("mem_id") ."' "; //회원번호
		$where .= "AND i.img_useyn = 'Y' "; //사용여부
		if(!empty($view['searchStr'])) {
			if($view['searchType'] == "1"){ //이미지명
				$orstr = explode(' ', $view['searchStr']);
				$orcnt = count($orstr);
				if($orcnt > 1) {
					$where .= " AND ( ";
					$prefix = '';
					for($i=0; $i<$orcnt; $i++){
						$where .= $prefix." i.img_name LIKE '%". addslashes($orstr[$i]) ."%' ";
						$prefix = " AND ";
					}
					$where .= " ) ";
				} else {
					$where .= " AND i.img_name LIKE '%". addslashes($orstr[0]) ."%' ";
				}
			} else if($view['searchType'] == "2"){ //상품코드
				//$where .= " AND img_code LIKE '%". addslashes($view['searchStr']) ."%'  ";
			}
		}
		$sort = "";
		if($view['sort'] == "2"){ //이미지명순
			$sort = "i.img_name ASC";
		}else{ //등록일순
			$sort = "i.img_id DESC";
		}

		//이미지 전체수
		$sql = "SELECT COUNT(*) AS cnt FROM cb_member_images i ". $where;
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//이미지 현황
		$sql = "
		SELECT
			  img_id /* 이미지번호 */
			, img_name /* 이미지 이름 */
			, img_path /* 이미지 경로 */
		FROM cb_member_images i
		". $where ."
		ORDER BY ". $sort ."
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		$view['data_list'] = $this->db->query($sql)->result();

		$where = "WHERE i.img_useyn = 'Y' ";
		if($view['searchCate1'] != ''){ //대분류
			$where .= " AND img_category1 = '". $view['searchCate1'] ."' ";
		}
		if($view['searchCate2'] != ''){ //소분류
			$where .= " AND img_category2 = '". $view['searchCate2'] ."' ";
		}
		if(!empty($view['searchStr'])) {
			if($view['searchType'] == "1"){ //제품명
				$orstr = explode(' ', $view['searchStr']);
				$orcnt = count($orstr);
				if($orcnt > 1) {
					$where .= " AND ( ";
					$prefix = '';
					for($i=0; $i<$orcnt; $i++){
						$where .= $prefix." i.img_name LIKE '%". addslashes($orstr[$i]) ."%' ";
						$prefix = " AND ";
					}
					$where .= " ) ";
				} else {
					$where .= " AND i.img_name LIKE '%". addslashes($orstr[0]) ."%' ";
				}
			} else if($view['searchType'] == "2"){ //상품코드
				$where .= " AND img_code LIKE '%". addslashes($view['searchStr']) ."%'  ";
			}
		}
		$sort = "";
		if($view['sort'] == "2"){ //제품명순
			$sort = "i.img_name ASC";
		}else{ //등록일순
			$sort = "i.img_id DESC";
		}

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

		$layoutconfig = array(
			'path' => 'spop/screen',
			'layout' => 'layout',
			'skin' => 'images',
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

	//스마트전단 > 이미지보관함 > 이미지 저장 2021-02-04
	public function images_save(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Start");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > _FILES[imgfile] : ". $_FILES['imgfile']['name']);
		$imgpath = "";
		$upload_img_path = $this->input->post("upload_path"); //업로드 경로
		$field_name = $this->input->post("upload_id"); //업로드 ID
		$max_size = $this->input->post("upload_max_size"); //파일 제한 사이지(byte 단위)
		$thumb_size = $this->input->post("upload_img_size"); //이미지 제한 크기(px 단위)(넓이 또는 높이 최대값)
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_img_path : ". $upload_img_path);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > field_name : ". $field_name);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > max_size : ". $max_size);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > thumb_size : ". $thumb_size);
		if ( is_null($field_name) || $field_name == '' ){
			//alert('필드값을 기입하지 않았습니다.'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 필드값을 기입하지 않았습니다.");
			$field_name = "imgfile";
		}
		if(empty($_FILES[$field_name]['name']) == false){ //이미지 업로드
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_max_size : ". $this->upload_max_size);
			$img_data = $this->img_upload_resize($upload_img_path, $field_name, $max_size,  $thumb_size);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_data : ". $img_data);
            if( is_array($img_data) && !empty($img_data) ){
				$imgpath = '/'. $upload_img_path . $img_data['file_name']; //이미지 경로
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > imgpath : ". $imgpath);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > orig_name : ". $img_data['orig_name']);

				//이미지보관함 추가
				$data = array();
				$data["img_mem_id"] = $this->member->item("mem_id"); //회원번호
				$data["img_path"] = $imgpath; //이미지 경로
				$data["img_name"] = $img_data['orig_name']; //이미지 이름
				$data["img_size"] = $img_data['file_size']; //파일크기
				$rtn = $this->db->replace("cb_member_images", $data);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지보관함 추가 rtn : ". $rtn);
			}
		}
		$result = array();
		$result['code'] = '0';
		$result['imgpath'] = $imgpath;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 > 이미지보관함 > 이미지 저장 2021-02-04
	public function images_save_arr(){
		$upload_cnt = $this->input->post("upload_cnt"); //업로드 경로
		$upload_id = $this->input->post("upload_id"); //업로드 ID
		$upload_img_path = $this->input->post("upload_path"); //업로드 경로
		$max_size = $this->input->post("upload_max_size"); //파일 제한 사이지(byte 단위)
		$thumb_size = $this->input->post("upload_img_size"); //이미지 제한 크기(px 단위)(넓이 또는 높이 최대값)
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_cnt : ". $upload_cnt);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_id > ". $upload_id);
		for($i = 0; $i < $upload_cnt; $i++){
			$field_name = $upload_id . $i; //업로드 ID
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > field_name > ". $field_name);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > _FILES[$field_name]['name'] > ". $_FILES[$field_name]['name']);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > _FILES[$field_name]['tmp_name'] > ". $_FILES[$field_name]['tmp_name']);
			if(empty($_FILES[$field_name]['name']) == false){ //이미지 업로드
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 업로드[". $i ."] start");
				$img_data = $this->img_upload_resize($upload_img_path, $field_name, $max_size,  $thumb_size); //이미지 크기변경 업로드
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_data[". $i ."] : ". $img_data);
				if( is_array($img_data) && !empty($img_data) ){
					$imgpath = '/'. $upload_img_path . $img_data['file_name']; //이미지 경로
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > imgpath[". $i ."] : ". $imgpath);
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > orig_name : ". $img_data['orig_name']);

					//이미지보관함 추가
					$data = array();
					$data["img_mem_id"] = $this->member->item("mem_id"); //회원번호
					$data["img_path"] = $imgpath; //이미지 경로
					$data["img_name"] = $img_data['orig_name']; //이미지 이름
					$data["img_size"] = $img_data['file_size']; //파일크기
					$rtn = $this->db->replace("cb_member_images", $data);
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지보관함 추가 rtn : ". $rtn);
				}
			}else{
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 업로드[". $i ."] 오류");
			}
		}
		$result = array();
		$result['code'] = '0';
		$result['imgpath'] = $imgpath;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 > 이미지보관함 > 이미지 크기변경 업로드 함수 2021-02-04
	public function img_upload_resize($upload_img_path, $field_name, $max_size,  $thumb_size){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Start");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_img_path : ". $upload_img_path);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > field_name : ". $field_name);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > max_size : ". $max_size);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > thumb_size : ". $thumb_size);
		if( is_dir($upload_img_path)  == false ){
			//alert('해당 디렉도리가 존재 하지 않음'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 해당 디렉도리가 존재 하지 않음");
		}
		if ( is_null($field_name) || $field_name == '' ){
			//alert('필드값을 기입하지 않았습니다.'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 필드값을 기입하지 않았습니다.");
			$field_name = "imgfile";
		}
		if( is_null($max_size) || is_numeric($max_size) == false || $max_size == ''){
			$max_size = 10 * 1024 * 1024; //파일 제한 사이지(byte 단위) (10MB)
		}
		if( is_null($thumb_size) || is_numeric($thumb_size) == false || $thumb_size == ''){
			$thumb_size = "400"; // //이미지 제한 크기(px 단위)(넓이 또는 높이 최대값)
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > max_size : ". $max_size);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > thumb_size : ". $thumb_size);
		if(is_array($_FILES) && empty($_FILES[$field_name]['name']) == false){ //이미지 업로드
			$upload_img_config = '';
			$upload_img_config['upload_path'] = $upload_img_path;
			$upload_img_config['allowed_types'] = 'gif|jpg|png|jpeg';
			$upload_img_config['encrypt_name'] = true;
			$upload_img_config['max_size'] = $max_size;
			//$upload_img_config['max_width'] = 1200;
			//$upload_img_config['max_height'] = 1200;
			$this->load->library('upload',$upload_img_config);
			$this->upload->initialize($upload_img_config);
			if ($this->upload->do_upload($field_name)) {
				//업로드 성공
				$filedata = $this->upload->data();
				if($filedata['image_width'] >= $filedata['image_height'] and $filedata['image_height'] >= $thumb_size){
					$resize_type = "height";
				}else if($filedata['image_width'] >= $thumb_size){
					$resize_type = "width";
				}else{
					$resize_type = "";
				}
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filedata : ". $filedata);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > full_path : ". $filedata['full_path']); //업로드 파일명 (경로포함)
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file_name : ". $filedata['file_name']); //업로드 파일명
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > orig_name : ". $filedata['orig_name']); //원본 파일명
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file_size : ". $filedata['file_size']); //파일크기
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file_ext : ". $filedata['file_ext']); //파일 확장자
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > image_width : ". $filedata['image_width']); //이미지 넓이
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > image_height : ". $filedata['image_height']); //이미지 높이
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > client_name : ". $filedata['client_name']); //원본 파일명
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > raw_name : ". $filedata['raw_name']); //업로드 파일명 (확장자 없음)
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > resize_type : ". $resize_type);
				if($resize_type != ""){ //썸네일 추가
					$this->load->library('image_lib');
					$this->image_lib->clear();
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > full_path : ". $filedata['full_path']);
					$simgconfig = array();
					$simgconfig['image_library'] = 'gd2';
					$simgconfig['source_image']	= $filedata['full_path'];
					$simgconfig['maintain_ratio'] = TRUE;
					if($resize_type == "width"){
						$simgconfig['width']	= $thumb_size; //썸네일 가로 사이즈
					}else{
						$simgconfig['height']= $thumb_size; //썸네일 세로 사이즈
					}
					$simgconfig['new_image'] = $filedata['file_path'].$filedata['raw_name'].'_thumb'.$filedata['file_ext'];
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > new_image : ". $simgconfig['new_image']);
					$this->image_lib->initialize($simgconfig);
					if(!$this->image_lib->resize()) {
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 썸네일 이미지 저장 실패 : ". $this->upload->display_errors());
					}
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 오지니널 이미지명 : ". $filedata['file_name']);
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 오지니널 file_size : ". $filedata['file_size']);
					$orgpath = $_SERVER["DOCUMENT_ROOT"] ."/". $upload_img_path . $filedata['file_name']; //오지니널 이미지 경로
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 오지니널 이미지 경로 : ". $orgpath);
					unlink($orgpath); //오지니널 이미지 삭제
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 오지니널 이미지 삭제 : ". $orgpath);
					$filedata['file_name'] = $filedata['raw_name'].'_thumb'.$filedata['file_ext']; //썸네일 이미지명
					//$filedata['file_size'] = filesize($simgconfig['new_image']); //byte 단위
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 썸네일 file_size : ". $filedata['file_size']);
					//$filedata['file_size'] = number_format(filesize($simgconfig['new_image']) * 0.001024, 2); //KB 단위 (소수점 2자리 반올림)
					$filedata['file_size'] = number_format(filesize($simgconfig['new_image']) / 1024, 2); //KB 단위 (소수점 2자리 반올림)
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 썸네일 file_size : ". $filedata['file_size']);
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 썸네일 이미지명 : ". $filedata['file_name']);
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 썸네일 이미지 경로 : ". $simgconfig['new_image']);
				}else{
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 오지니널 file_size : ". $filedata['file_size']);
				}
				return $filedata;
			}else{
				//업로드 실패
				$error = $this->upload->display_errors();
				//alert($error, '/maker'); exit('');
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 업로드 실패");
			}
		} else {
			//alert('파일이 업로드 되지 않았습니다.'); exit('');
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 파일이 업로드 되지 않았습니다.");
		}
	}

	//스마트전단 > 이미지보관함 > 선택삭제 2021-02-04
	public function images_chk_del(){
		$chkIds = $this->input->post("chkIds"); //이미지번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > count(chkIds) : ". count($chkIds));
		if(count($chkIds) > 0) { //선택 삭제
			foreach($chkIds as $chkId) {
				//이미지 경로 조회
				//$sql = "SELECT img_path FROM cb_member_images WHERE img_mem_id = '". $this->member->item("mem_id") ."' and img_id = '". $chkId ."' ";
				////log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
				//$img_path = $this->db->query($sql)->row()->img_path;
				//$del_path = $_SERVER["DOCUMENT_ROOT"] . $img_path;
				//unlink($del_path); //이미지 삭제
				////log_message("ERROR", $_SERVER['REQUEST_URI'] ." > del_path[". $chkId ."] : ". $del_path);
				//$this->db->delete("cb_member_images", array("img_id"=>$chkId)); //이미지보관함 삭제
				////log_message("ERROR", $_SERVER['REQUEST_URI'] ." > chkId : ". $chkId);
				$this->db->update("cb_member_images", array("img_useyn"=>"N"), array("img_id"=>$chkId)); //이미지보관함 사용안함 처리
			}
		}
		$result = array();
		$result['code'] = '0';
		$result['msg'] = "OK";
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 > 이미지보관함 > 이미지 회전 2021-02-04
	public function images_rotation(){
		$chkIds = $this->input->post("chkIds"); //이미지번호
		$direction = $this->input->post("direction"); //회전방향(L.왼쪽, R.오른쪽)
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > count(chkIds) : ". count($chkIds));
		if(count($chkIds) > 0) { //선택 삭제
			foreach($chkIds as $chkId) {
				//이미지 경로 조회
				$sql = "SELECT img_path FROM cb_member_images WHERE img_mem_id = '". $this->member->item("mem_id") ."' and img_id = '". $chkId ."' ";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
				$img_path = $this->db->query($sql)->row()->img_path;
				$source_file = $_SERVER["DOCUMENT_ROOT"] . $img_path;
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > source_file[". $chkId ."] : ". $source_file);
				$imgsize = getimagesize($source_file);
				$mime = $imgsize['mime']; //image/jpeg
				if($mime == "image/gif"){
					$source = imagecreatefromgif($source_file);
				}else if($mime == "image/png"){
					$source = imagecreatefrompng($source_file);
				}else{
					$source = imagecreatefromjpeg($source_file);
				}
				if($direction == "R"){
					$rotate = imagerotate($source, -90, 0); //오른쪽으로 회전
				}else{
					$rotate = imagerotate($source, 90, 0); //왼쪽으로 회전
				}
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > rotate[". $chkId ."] : ". $rotate);
				if($mime == "image/gif"){
					imagegif($rotate, $source_file);
				}else if($mime == "image/png"){
					imagepng($rotate, $source_file);
				}else{
					imagejpeg($rotate, $source_file);
				}
			}
		}
	}

	//이미지보관함 이미지 다운로드
    public function images_down(){
        if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Start");
		$id = $this->input->get('key');
		if( is_numeric($id) == false || empty($id)  || is_null($id)){
			return false;
		}else{
			$sql = "
			SELECT
				  img_path as path /* 이미지 경로 */
				, img_name as name /* 이미지명 */
			FROM cb_member_images
			WHERE img_mem_id = '". $this->member->item("mem_id") ."'
			AND img_id = '". $id ."' ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$query = $this->db->query($sql);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > num_rows : ". $query->num_rows());
			if($query->num_rows() > 0){
				$result = $query->row();
				if(!empty($result->path)){
					$this->load->helper('download');
					$file = FCPATH . $result->path;
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file : ". $file);
					if (is_file($file)){ //파일이 존재
						//echo '파일이 존재';
						$data = file_get_contents ( $file );
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result->name : ". $result->name);
						if(!empty($result->name) ){
							force_download ( $result->name, $data );
						}
					}else{ //파일이 존재하지않음
						alert("파일이 존재하지 않습니다."); return;
					}
				}else{
						alert("데이터값이 존재하지 않습니다."); return;
				}
			}else{
				alert("데이터베이스가 존재하지 않습니다."); return;
			}
		}
    }

}
?>
