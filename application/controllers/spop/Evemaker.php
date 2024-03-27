<?php
class Evemaker extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board', 'Biz');

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
	private $titimg_self_path; //타이틀 직접입력 업로드 경로

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

		/* 이미지 등록될 기본 디렉토리 설정 */
		$this->titimg_self_path =  config_item('uploads_dir') .'/design_title_self/'; //스마트전단 타이틀 직접입력 업로드 경로

		/* 기본 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->titimg_self_path);

		/* 년 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->titimg_self_path . date("Y") ."/");

		$this->titimg_self_path = $this->titimg_self_path . date("Y") ."/";
	}

	//스마트전단 > 스마트전단 목록
	public function index(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$view = array();
		$skin = "main";
		$add = $this->input->get("add");
        $pladd = $this->input->get("pladd");
		$view['add'] = $add;
		if($add != "") $skin .= $add;
        // $in_ip = $_SERVER['REMOTE_ADDR'];
        // if($in_ip=="61.75.230.209"){
            // $skin = "main_type_new";
            // if($pladd=="test"){
            //     $skin = "main_type";
            // }
        // }
        //
        // if($in_ip=="182.208.215.189"){
        //     if($pladd=="test"){
        //         $skin = "main_type";
        //     }
        // }
		$view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
		$view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
		$where = "WHERE emd_mem_id = '". $this->member->item("mem_id") ."' ";
		// $where .= "AND emd_type = 'R' /* 타입(S.전단, C.쿠폰, T.임시저장) */ ";
		$where .= "AND emd_useyn = 'Y' ";
		// if($this->member->item("mem_level") >= 100) {
		//   $where .= "AND psd_mng_yn = 'Y' /*전단관리자 목록 */ ";
		// }

		//스마트전단 전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_evemaker_data a
		". $where ." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//스마트전단 리스트
		$sql = "
		SELECT
			  emd_id /* 전단번호 */
			, emd_code /* 코드 */
			, emd_title /* 제목 */
			, emd_sub_info /* 행사기간 */
            , emd_sub_info2 /* 행사기간 */
			, DATE_FORMAT(emd_cre_date, '%Y.%m') AS emd_cre_ym /* 등록년월 */
			, DATE_FORMAT(emd_cre_date, '%d') AS emd_cre_dd /* 등록일 */

			, emd_open_sdt /* 주문하기 시작일자 */
			, emd_open_edt /* 주문하기 종료일자 */
            , emd_open_yn /* 공개여부 */
		FROM cb_evemaker_data a
		". $where ."
		ORDER BY emd_id DESC
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
			'path' => 'spop/evemaker',
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

    // //스마트전단 > 스마트전단 목록
	// public function custom(){
	// 	if($this->member->is_member() == false) { //로그인이 안된 경우
	// 		redirect('login');
	// 	}
	// 	$view = array();
	// 	$skin = "custom";
	// 	$add = $this->input->get("add");
	// 	$view['add'] = $add;
    //     $mode = $this->input->get("mode");
	// 	$view['mode'] = $mode;
	// 	if($add != "") $skin .= $add;
	// 	$view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 12;
	// 	$view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
	// 	$where = "WHERE mem_id = '". $this->member->item("mem_id") ."' ";
    //     $where .= "AND mst_id > 0 ";
    //     $where .= "AND sent_date is not null ";
    //
    //     $parent_id = $this->funn->getParent($this->member->item('mem_id'));
    //
    //     if($this->member->item('mem_level')>=100){
    //         $where .= "AND mst_qty > 0 ";
    //     }else{
    //         $where .= "AND mst_qty > 20 ";
    //     }
    //
    //     $where .= "AND sent_date < '".date("Y-m-d H:i:s")."' ";
    //     // date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',time())."-1 month"))
    //     // date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',time())."-1 day"));
    //     // $where .= "AND sent_date > '".date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',time())."-3 day"))."' ";
    //     $where .= "AND sent_date > '".date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',time())."-1 month"))."' ";
    //     if(!empty($mode)&&$mode=="edit"){
    //         $where .= "AND a.html <> '' AND a.url <> ''  ";
    //     }else{
    //         $where .= "AND a.psd_code <> ''  ";
    //     }
    //
    //     $db = $this->load->database("218g", TRUE);
    //
    //
    //
	// 	//스마트전단 전체수
	// 	$sql = "
	// 	SELECT count(1) as cnt
	// 	FROM cb_alimtalk_ums a
	// 	". $where ." ";
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
	// 	//echo $sql ."<br>";
	// 	$view['total_rows'] = $this->db->query($sql)->row()->cnt;
    //
    //     if(!empty($mode)&&$mode=="edit"){
    //         $sql = "
    // 		SELECT
    //               a.short_url
    //               , a.html
    // 			  , a.sent_date
    // 		FROM cb_alimtalk_ums a
    // 		". $where . "
    // 		ORDER BY sent_date DESC
    // 		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
    //     }else{
	// 	//스마트전단 리스트
	// 	$sql = "
	// 	SELECT
	// 		  b.psd_id /* 전단번호 */
	// 		, b.psd_code /* 코드 */
	// 		, b.psd_title /* 제목 */
	// 		, b.psd_date /* 행사기간 */
    //         , b.psd_open_yn /* 공개여부 */
    //         , a.sent_date
    //         , c.tem_imgpath /* 템플릿 이미지 경로 */
	// 	FROM cb_alimtalk_ums a left join cb_pop_screen_data b on a.psd_code = b.psd_code
    //     LEFT JOIN cb_design_templet c ON b.psd_tem_id = c.tem_id
	// 	". $where . " and b.psd_open_yn = 'Y'
	// 	ORDER BY sent_date DESC
	// 	LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
	// 	//echo $sql ."<br>";
    //     }
	// 	$view['data_list'] = $this->db->query($sql)->result();
    //
    //
    //     $sql = "select count(1) as cnt from cb_alimtalk_custom_link where mem_id = '".$this->member->item("mem_id")."' and cau_useyn='Y' and cau_from = '91m'";
    //     $view['custom_cnt'] = $db->query($sql)->row()->cnt;
    //
    //     log_message('error', 'test : ' . $view['custom_cnt']);
    //
    //     $view['custom_row'] = "";
    //     if($view['custom_cnt']>0){
    //         $sql = "select * from cb_alimtalk_custom_link where mem_id = '". $this->member->item("mem_id") ."' and cau_useyn='Y' and cau_from = '91m' limit 1";
    //         $view['custom_row'] = $db->query($sql)->row();
    //         $view['custom_select']="";
    //         if(!empty($view['custom_row']->psd_code)){
    //             $sql = "select psd_title, psd_date from cb_pop_screen_data where psd_mem_id = '". $this->member->item("mem_id") ."' and psd_code = '".$view['custom_row']->psd_code."' limit 1";
    //             $view['custom_select'] = $this->db->query($sql)->row();
    //         }
    //
    //     }
    //
    //
	// 	$this->load->library('pagination');
	// 	$page_cfg['link_mode'] = 'open_page';
	// 	$page_cfg['base_url'] = '';
	// 	$page_cfg['total_rows'] = $view['total_rows'];
	// 	$page_cfg['per_page'] = $view['perpage'];
	// 	$this->pagination->initialize($page_cfg);
	// 	$this->pagination->cur_page = intval($view['param']['page']);
	// 	$view['page_html'] = $this->pagination->create_links();
	// 	$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);
    //
	// 	$layoutconfig = array(
	// 		'path' => 'spop/evemaker',
	// 		'layout' => 'layout',
	// 		'skin' => $skin,
	// 		'layout_dir' => $this->cbconfig->item('layout_main'),
	// 		'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_main'),
	// 		'use_sidebar' => $this->cbconfig->item('sidebar_main'),
	// 		'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
	// 		'skin_dir' => $this->cbconfig->item('skin_main'),
	// 		'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_main'),
	// 		'page_title' => $page_title,
	// 		'meta_description' => $meta_description,
	// 		'meta_keywords' => $meta_keywords,
	// 		'meta_author' => $meta_author,
	// 		'page_name' => $page_name,
	// 	);
    //
	// 	$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
	// 	$this->data = $view;
	// 	$this->layout = element('layout_skin_file', element('layout', $view));
	// 	$this->view = element('view_skin_file', element('layout', $view));
	// }

    // //커스텀메뉴2 연결
	// public function custom_choice(){
	// 	if($this->member->is_member() == false) { //로그인이 안된 경우
	// 		redirect('login');
	// 	}
	// 	$code = $this->input->post("code");
    //     $flag = $this->input->post("flag");
    //
    //     $db = $this->load->database("218g", TRUE);
    //
    //     $result = array();
    //
    //     if(!empty($code)&&!empty($flag)){
    //         $sql = "select count(1) as cnt from cb_alimtalk_custom_link where mem_id = '".$this->member->item("mem_id")."' and cau_from = '91m'";
    //         $custom_cnt = $db->query($sql)->row()->cnt;
    //
    //         $data = array();
    //         $data["cau_useyn"] = 'Y';
    //         if($flag=="smart"){
    //             $data["psd_code"] = $code;
    //             $data["short_url"] = '';
    //         }else{
    //             $data["psd_code"] = '';
    //             $data["short_url"] = $code;
    //         }
    //
    //         if($custom_cnt>0){
    //
    //             $where["mem_id"] = $this->member->item("mem_id");
    //             $where["cau_from"] = "91m";
    //             $db->update("cb_alimtalk_custom_link", $data, $where); //데이타 수정
    //
    //         }else{
    //             $data["mem_id"] = $this->member->item("mem_id");
    //             $data["cau_from"] = "91m";
    //             $db->insert("cb_alimtalk_custom_link", $data); //데이타 추가
    //         }
    //         $result['code'] = "0";
    //
    //         if($flag=="smart"){
    //             $sql = "select psd_title, psd_date from cb_pop_screen_data where psd_mem_id = '".$this->member->item("mem_id")."' and psd_code = '".$code."' limit 1";
    //             $smart_row = $this->db->query($sql)->row();
    //             $result['remsg'] = "설정된 스마트전단 : <em>".$smart_row->psd_title."</em> ".$smart_row->psd_date;
    //         }else{
    //             $sql = "select sent_date from cb_alimtalk_ums where mem_id = '".$this->member->item("mem_id")."' and short_url = '".$code."' limit 1";
    //             $smart_row = $this->db->query($sql)->row();
    //             $result['remsg'] = "설정된 에디터 : <em>".$smart_row->sent_date."</em> 발송";
    //         }
    //
    //     }else{
    //         $result['code'] = "1";
    //         $result['remsg'] = "";
    //     }
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result['html_tag'] : ".$result['html_tag']);
	// 	$json = json_encode($result,JSON_UNESCAPED_UNICODE);
	// 	header('Content-Type: application/json');
	// 	echo $json;
	// }
    //
    // //커스텀메뉴2 연결
	// public function custom_reset(){
	// 	if($this->member->is_member() == false) { //로그인이 안된 경우
	// 		redirect('login');
	// 	}
    //
    //     $db = $this->load->database("218g", TRUE);
    //
    //     $result = array();
    //
    //     $sql = "select count(1) as cnt from cb_alimtalk_custom_link where mem_id = '".$this->member->item("mem_id")."' and cau_from = '91m'";
    //     $custom_cnt = $db->query($sql)->row()->cnt;
    //
    //     $data = array();
    //     $data["cau_useyn"] = 'N';
    //
    //     if($custom_cnt>0){
    //
    //         $where["mem_id"] = $this->member->item("mem_id");
    //         $where["cau_from"] = "91m";
    //         $db->update("cb_alimtalk_custom_link", $data, $where); //데이타 수정
    //         $result['code'] = "0";
    //     }else{
    //         $result['code'] = "1";
    //     }
    //
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result['html_tag'] : ".$result['html_tag']);
	// 	$json = json_encode($result,JSON_UNESCAPED_UNICODE);
	// 	header('Content-Type: application/json');
	// 	echo $json;
	// }

	//스마트전단 > 스마트전단 만들기
	public function write(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		$view = array();
		$skin = "write";
        // $in_ip = $_SERVER['REMOTE_ADDR'];
        // if($in_ip=="61.75.230.209"){
        //     $skin = "write_type";
        // }
		// $add = $this->input->get("add"); //추가스킨
		// $view['add'] = $add;
		// if($add != "") $skin .= $add;
		$view['upload_max_size'] = $this->upload_max_size; //파일 제한 사이지
		$view['upload_path'] = $this->upload_path; //스마트전단 업로드 경로
		$view['template_self_path'] = $this->template_self_path; //템플릿 직접입력 업로드 경로
		$view['titimg_self_path'] = $this->titimg_self_path; //타이틀 직접입력 업로드 경로
		$view['md'] = $this->input->get("md"); //페이지모드
		$view['emd_id'] = $this->input->get("emd_id"); //전단번호
		$emd_id = $this->input->get("emd_id"); //전단번호
		// $temp_id = $this->input->get("temp_id"); //임시번호

		$view['searchCate1'] = $this->input->get("searchCate1"); //대분류
		$view['searchCate2'] = $this->input->get("searchCate2"); //소분류

		if(empty($view['searchCate1'])) {
		    $sql = "select mem_img_lib_category1, mem_img_lib_category2 from cb_member cm where cm.mem_id = '".$this->member->item("mem_id")."'";
		    $img_lib_category = $this->db->query($sql)->row();

		    $view['searchCate1'] = $img_lib_category->mem_img_lib_category1; //대분류
		    $view['searchCate2'] = $img_lib_category->mem_img_lib_category2; //소분류

		}
        // $view['psd_mode'] = "N";
		if(!empty($emd_id)){
			$sql = "select count(1) as cnt from cb_evemaker_goods WHERE emg_emd_id = '".$emd_id."'";
			$view["emd_cnt"] = $this->db->query($sql)->row()->cnt;
            log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
			// $sql = "select count(1) as cnt from cb_pop_screen_goods_temp WHERE psg_psd_id = '".$psd_id."'";
			// $view["temp_psd_cnt"] = $this->db->query($sql)->row()->cnt;
            // $view['psd_v'] = "O"; //버전
            // if($view['psd_cnt']>1000){
            //     $view['psd_mode'] = "L";
            // }
		}

		// if($temp_id  != ""){
		// 	$view['org_id'] = $psd_id;
		// 	$psd_id = $temp_id;
		// }

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
			  *
		FROM cb_evemaker_data
		WHERE emd_mem_id = '". $this->member->item("mem_id") ."'
		AND emd_id = '". $emd_id ."'
		AND emd_useyn = 'Y' ";
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		//if($add == "_test") echo $sql ."<br>";
		$view['evemaker_data'] = $this->db->query($sql)->row();
		// $psd_ver_no = $view['screen_data']->psd_ver_no; //버전번호
        $view['emd_goods_cnt'] = $view['evemaker_data']->emd_goods_cnt;


		//코너별 상품등록 조회
		$sql = "
		SELECT *
		FROM cb_evemaker_goods a
		WHERE emg_mem_id = '". $this->member->item("mem_id") ."' /* 회원번호 */
		AND emg_emd_id = '". $emd_id ."' /* 전단번호 */  and emg_name <> ''
        AND emg_useyn = 'Y'
		ORDER BY emg_seq ";
		//echo $sql ."<br>";
		$view['evemaker_box'] = $this->db->query($sql)->result();
		log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);









        //디자인 텍스트형 타이틀 조회
		// $sql = "
		// SELECT
		// 	  tit_id /* 타이틀번호 */
		// 	, tit_imgpath /* 이미지경로 */
		// 	, tit_type_self /* 타이틀 직접입력 확인*/
		// 	, tit_text_info /* 타이틀 텍스트정보 */
        //     , tit_bgcolor
        //     , tit_align
        //     , tit_txcolor
		// FROM cb_design_title dt
		// WHERE dt.tit_type = 'C'
		// AND dt.tit_useyn = 'Y'
		// ORDER BY dt.tit_seq ASC ";
		// // echo $sql ."<br>";
		// $view['design_title_custom_list'] = $this->db->query($sql)->result();






		$layoutconfig = array(
			'path' => 'spop/evemaker',
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

	// //스마트전단 상품 임시테이블데이터 복구
	// public function smart_temp_save(){
	// 	if($this->member->is_member() == false) { //로그인이 안된 경우
	// 		redirect('login');
	// 	}
	// 	$psd_id = $this->input->post("psd_id");
	// 	$sql = "INSERT INTO cb_pop_screen_goods(psg_mem_id, psg_psd_id, psg_tit_id, psg_step, psg_step_no, psg_imgpath, psg_name, psg_option, psg_price, psg_dcprice, psg_badge, psg_seq, psg_box_no, psg_option2, psg_mng_yn, psg_code, psg_stock) SELECT psg_mem_id, psg_psd_id, psg_tit_id, psg_step, psg_step_no, psg_imgpath, psg_name, psg_option, psg_price, psg_dcprice, psg_badge, psg_seq, psg_box_no, psg_option2, psg_mng_yn, psg_code, psg_stock FROM cb_pop_screen_goods_temp WHERE psg_psd_id = '".$psd_id."'";
	// 	$this->db->query($sql);
    //
	// 	$result = array();
	// 	$sql = "select count(1) as cnt from cb_pop_screen_goods WHERE psg_psd_id = '".$psd_id."'";
	// 	$psd_cnt = $this->db->query($sql)->row()->cnt;
	// 	if($psd_cnt>0){
	// 		$result['code'] = "0";
	// 		$result['msg'] = "복구가 완료되었습니다.";
	// 	}else{
	// 		$result['code'] = "1";
	// 		$result['msg'] = "복구작업에 오류가 있습니다.";
	// 	}
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result['html_tag'] : ".$result['html_tag']);
	// 	$json = json_encode($result,JSON_UNESCAPED_UNICODE);
	// 	header('Content-Type: application/json');
	// 	echo $json;
	// }
    //
	// //스마트전단 상품 임시테이블데이터 복구
	// public function outofpage(){
	// 	if($this->member->is_member() == false) { //로그인이 안된 경우
	// 		redirect('login');
	// 	}
	// 	$result = array();
	// 	$isChange = $this->input->post("isChange");
	// 	if($isChange=='true'){
	// 		$result['code'] = "0";
	// 		$result['msg'] = "수정된 내용이 있습니다. 저장하시겠습니까?";
	// 	}else{
	// 		$result['code'] = "1";
	// 		$result['msg'] = "수정된 내용이 없습니다";
	// 	}
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result['html_tag'] : ".$result['html_tag']);
	// 	$json = json_encode($result,JSON_UNESCAPED_UNICODE);
	// 	header('Content-Type: application/json');
	// 	echo $json;
	// }
    //
	// //이미지 탬플릿선택 모달창 > 탬플릿 리스트
	// public function ajax_templet(){
	// 	$perpage = ($this->input->post("per_page")) ? $this->input->post("per_page") : 8;
	// 	$page = ($this->input->post("page")) ? $this->input->post("page") : 1;
	// 	$tag_id = ($this->input->post("tag_id")) ? $this->input->post("tag_id") : "";
	// 	$where = " WHERE dt.tem_useyn = 'Y' ";
	// 	if($tag_id != "" && $tag_id != "all"){
	// 		$where .= " and dt.tem_tag_id = '". $tag_id ."' ";
	// 	}
	// 	//echo $where ."<br>";
    //
	// 	//디자인 템플릿 전체 수
	// 	$sql = "SELECT count(1) as cnt
	// 	FROM cb_design_templet dt
	// 	". $where;
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tag_id : ". $tag_id . ", sql : ".$sql);
	// 	$total_rows = $this->db->query($sql)->row()->cnt;
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > total_rows : ".$total_rows);
    //
	// 	//디자인 템플릿 조회
	// 	$sql = "SELECT dt.*
	// 	FROM cb_design_templet dt
	// 	". $where ."
	// 	ORDER BY dt.tem_seq DESC
	// 	LIMIT ".(($page - 1) * $perpage).", ".$perpage." ";
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
	// 	$data_list = $this->db->query($sql)->result();
	// 	$html = "";
	// 	foreach ($data_list as $r) {
	// 		$html .= '<li>';
	// 		$html .= '	<p class="tem_img" style="cursor:pointer;"><img src="'. $r->tem_imgpath .'" onClick="templet_choice(\''. $r->tem_id .'\', \''. $r->tem_bgcolor .'\',\''. $r->tem_imgpath .'\', \'base\');"></p>';
	// 		$html .= '	<p class="tem_text">';
	// 		$html .= '	<span>type'. $this->funn->fnZeroAdd($r->tem_id) .'</span>';
	// 		$html .= '		<button type="button" onClick="templet_choice(\''. $r->tem_id .'\', \''. $r->tem_bgcolor .'\',\''. $r->tem_imgpath .'\', \'base\');">탬플릿선택</button>';
	// 		$html .= '	</p>';
	// 		$html .= '</li>';
	// 	}
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > html : ".$html);
    //
	// 	$result = array();
	// 	$result['page'] = $page;
	// 	$result['total_rows'] = $total_rows;
	// 	$result['html'] = $html;
    //
	// 	$this->load->library('pagination');
	// 	$page_cfg['link_mode'] = 'templet_page';
	// 	$page_cfg['base_url'] = '';
	// 	$page_cfg['total_rows'] = $total_rows;
	// 	$page_cfg['per_page'] = $perpage;
	// 	$this->pagination->initialize($page_cfg);
	// 	$this->pagination->cur_page = intval($page);
	// 	$result['page_html'] = $this->pagination->create_links();
	// 	$result['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='templet_page($1)'><a herf='#' ",$result['page_html']);
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result['page_html'] : ".$result['page_html']);
    //
	// 	$json = json_encode($result,JSON_UNESCAPED_UNICODE);
	// 	header('Content-Type: application/json');
	// 	echo $json;
	// }
    //
	// //이미지 탬플릿선택 모달창 > 탬플릿 태그 리스트
	// public function ajax_templet_tag(){
	// 	$tag_id = ($this->input->post("tag_id")) ? $this->input->post("tag_id") : "all";
	// 	$sql = "select * from cb_design_templet_tag order by tag_seq asc ";
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
	// 	$data_list = $this->db->query($sql)->result();
	// 	$html = '<li style="cursor:pointer;" onClick="tag_choice(\'all\', \'1\');"><input type="radio" name="tag_list" id="tag_all" class="tag_list" style="cursor:pointer;" value="all" '. (("all" == $tag_id) ? "checked" : "") .'><label for="tag_all" style="cursor:pointer;">전체</label></li>';
	// 	foreach ($data_list as $r) {
	// 		$html .= '<li style="cursor:pointer;" onClick="tag_choice(\''. $r->tag_id .'\', \'1\');"><input type="radio" name="tag_list" id="tag_'. $r->tag_id .'" class="tag_list" style="cursor:pointer;" value="'. $r->tag_id .'" '. (($r->tag_id == $tag_id) ? "checked" : "") .'><label for="tag_'. $r->tag_id .'" style="cursor:pointer;">'. $r->tag_name .'</label></li>';
	// 	}
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > html : ".$html);
    //
	// 	$result = array();
	// 	$result['html_tag'] = $html;
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result['html_tag'] : ".$result['html_tag']);
	// 	$json = json_encode($result,JSON_UNESCAPED_UNICODE);
	// 	header('Content-Type: application/json');
	// 	echo $json;
	// }
    //
	// //스마트전단 삭제
	// public function screen_del(){
	// 	$mem_id = $this->member->item("mem_id"); //회원번호
	// 	$data_id = $this->input->post("data_id"); //전단번호
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .",data_id : ". $data_id);
    //
	// 	//스마트전단 상품 삭제
	// 	$sql = "delete from cb_pop_screen_goods where psg_mem_id = '". $mem_id ."' and psg_psd_id = '". $data_id ."' ";
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품 삭제 : ". $sql);
	// 	$this->db->query($sql);
    //
	// 	//스마트전단 데이타 삭제
	// 	$sql = "delete from cb_pop_screen_data where psd_mem_id = '". $mem_id ."' and psd_id = '". $data_id ."' ";
	// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 삭제 : ". $sql);
	// 	$this->db->query($sql);
    //
	// 	$result = array();
	// 	$result['code'] = '0';
	// 	$result['msg'] = $data_id;
	// 	$json = json_encode($result,JSON_UNESCAPED_UNICODE);
	// 	header('Content-Type: application/json');
	// 	echo $json;
	// }
    //
	// //스마트전단 Sample 설정
	// public function setsample(){
	//     $mem_id = $this->member->item("mem_id"); //회원번호
	//     $data_id = $this->input->post("data_id"); //전단번호
    //
	//     //스마트전단 Sample 설정
	//     $sql = "update cb_pop_screen_data set psd_sample = 'Y' where psd_mem_id = '". $mem_id ."' and psd_id = '". $data_id ."' ";
	//     $this->db->query($sql);
    //
	//     $result = array();
	//     $result['code'] = '0';
	//     $result['msg'] = "OK";
	//     $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	//     header('Content-Type: application/json');
	//     echo $json;
	// }

    //스마트전단 사용안함 처리
	public function evemaker_onoff(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$data_id = $this->input->post("data_id"); //전단번호
        $open_yn = $this->input->post("flag"); //공개여부
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .",data_id : ". $data_id);

		//스마트전단 사용안함 처리
		$sql = "update cb_evemaker_data set emd_open_yn='".$open_yn."' where emd_mem_id = '". $mem_id ."' and emd_id = '". $data_id ."' ";
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 공개여부 처리 : ". $sql);
		$this->db->query($sql);

        // $db = $this->load->database("218g", TRUE);
        // $sql = "select count(1) as cnt from cb_pop_screen_data_mt where psd_id = '".$data_id."' ";
        // $smartcnt = $db->query($sql)->row()->cnt;
        //
        // if($smartcnt>0){
        //     $sql = "update cb_pop_screen_data_mt set psd_open_yn='".$open_yn."' where psd_mem_id = '". $mem_id ."' and psd_id = '". $data_id ."' ";
        //     $db->query($sql);
        // }

		$result = array();
		$result['code'] = '0';
		$result['msg'] = $data_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 사용안함 처리
	public function evemaker_remove(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$data_id = $this->input->post("data_id"); //전단번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .",data_id : ". $data_id);

		//스마트전단 사용안함 처리
		$sql = "update cb_evemaker_data set emd_useyn='N' where emd_mem_id = '". $mem_id ."' and emd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 사용안함 처리 : ". $sql);
		$this->db->query($sql);

		$result = array();
		$result['code'] = '0';
		$result['msg'] = $data_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

    public function check_shop(){
        if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
        $db = $this->load->database("218g", TRUE);
        // $code = $this->input->post("code"); //전단코드
        $sql = "select count(1) as cnt from cb_shop_member_data where smd_mem_id = '".$this->member->item("mem_id")."' and smd_from = '91m'";
        $shopcnt = $db->query($sql)->row()->cnt;

        if($shopcnt == 0){
            $sql = "select spf_friend from cb_wt_send_profile_dhn where spf_mem_id = '".$this->member->item("mem_id")."' limit 1";
            $spf_friend = $this->db->query($sql)->row()->spf_friend;
            $mem_data = array();
            $mem_data["smd_mem_id"] = $this->member->item("mem_id");
            $mem_data["smd_mem_userid"] = $this->member->item("mem_userid");
            $mem_data["smd_mem_username"] = $this->member->item("mem_username");
            $mem_data["smd_mem_shop_img"] = $this->member->item("mem_shop_img");
            $mem_data["smd_mem_shop_intro"] = $this->member->item("mem_shop_intro");
            $mem_data["smd_mem_notice"] = $this->member->item("mem_notice");
            $mem_data["smd_mem_shop_time"] = $this->member->item("mem_shop_time");
            $mem_data["smd_mem_shop_holiday"] = $this->member->item("mem_shop_holiday");
            $mem_data["smd_mem_shop_phone"] = $this->member->item("mem_shop_phone");
            $mem_data["smd_mem_shop_addr"] = $this->member->item("mem_shop_addr");
            $mem_data["smd_mem_shop_notice"] = $this->member->item("mem_shop_notice");
            $mem_data["smd_mem_shop_template"] = $this->member->item("mem_shop_template");
            $mem_data["smd_spf_friend"] = $spf_friend;
            $mem_data["smd_mem_stmall_yn"] = $this->member->item("mem_stmall_yn");
            $mem_data["smd_mem_stmall_alim_phn"] = $this->member->item("mem_stmall_alim_phn");
            $mem_data["smd_mem_cart_info"] = $this->member->item("mem_cart_info");
            $mem_data["smd_from"] = "91m";
            $db->insert("cb_shop_member_data", $mem_data); //데이타 추가
        }

        $sql = "select count(1) as cnt from cb_orders_setting where mem_id = '".$this->member->item("mem_id")."' ";
        $os_cnt = $this->db->query($sql)->row()->cnt;

        if($os_cnt > 0){
            $sql = "select count(1) as cnt from cb_orders_setting where mem_id = '".$this->member->item("mem_id")."' and os_from = '91m'";
            $setcnt = $db->query($sql)->row()->cnt;

            if($setcnt==0){
                $sql = "select * from cb_orders_setting where mem_id = '".$this->member->item("mem_id")."' limit 1 ";
                $osd = $this->db->query($sql)->row();

                $os_data = array();
                $os_data["mem_id"] = $this->member->item("mem_id");
                $os_data["min_amt"] = $osd->min_amt;
                $os_data["delivery_amt"] = $osd->delivery_amt;
                $os_data["free_delivery_amt"] = $osd->free_delivery_amt;
                $os_data["os_from"] = "91m";
                $db->insert("cb_orders_setting", $os_data); //데이타 추가
            }
        }
    }

    //광고성 알림톡 조회
	public function check_psd(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 광고성 알림톡 조회");
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
        $result = array();
        $db = $this->load->database("218g", TRUE);
        $code = $this->input->post("code"); //전단코드

        $this->check_shop();

        $sql = "select count(1) as cnt from cb_pop_screen_data_mt where psd_code = '".$code."' and psd_from = '91m'";
        $smartcnt = $db->query($sql)->row()->cnt;

        if($smartcnt==0){
            $sql = "select * from cb_pop_screen_data where psd_code = '". $code."' ";
            $oridata = $this->db->query($sql)->row();

            $data = array();
            $data["psd_id"] = $oridata->psd_id;
            $data["psd_mem_id"] = $oridata->psd_mem_id; //회원번호
            $data["psd_tem_id"] = $oridata->psd_tem_id;
            $data["psd_code"] = $code; //전단코드
            $data["psd_title"] = $oridata->psd_title;
            $data["psd_date"] = $oridata->psd_date;
            $data["psd_step1_yn"] = $oridata->psd_step1_yn;
            $data["psd_step2_yn"] = $oridata->psd_step2_yn;
            $data["psd_step3_yn"] = $oridata->psd_step3_yn;
            $data["psd_useyn"] = $oridata->psd_useyn;
            $data["psd_seq"] = $oridata->psd_seq; //정렬순서
            $data["psd_viewyn"] = $oridata->psd_viewyn; //스마트전단 샘플 뷰 (Y/N)
            $data["psd_ver_no"] = $oridata->psd_ver_no; //버전번호
            $data["psd_type"] = $oridata->psd_type;
            $data["psd_order_yn"] = $oridata->psd_order_yn;
            $data["psd_order_sdt"] = $oridata->psd_order_sdt;
            $data["psd_order_edt"] = $oridata->psd_order_edt;
            $data["psd_order_st"] = $oridata->psd_order_st;
            $data["psd_order_et"] = $oridata->psd_order_et;
            $data["psd_mng_yn"] = $oridata->psd_mng_yn;
            $data["psd_goods"] = $oridata->psd_goods;
            $data["psd_goods_cnt"] = $oridata->psd_goods_cnt;
            $data["psd_from"] = '91m';
            $db->replace("cb_pop_screen_data_mt", $data); //데이타 추가

            // $mem_id = $this->member->item("mem_id"); //회원번호
            $sql = "select count(1) as cnt from cb_pop_screen_goods_mt where psg_psd_id = '". $oridata->psd_id."' ";
            $goods_cnt = $db->query($sql)->row();

            if($goods_cnt > 0){
                $sql = "DELETE FROM cb_pop_screen_goods_mt
    			WHERE psg_psd_id = '".$oridata->psd_id."'";
                $db->query($sql);
            }

            $sql = "select * from cb_pop_screen_goods where psg_psd_id = '". $oridata->psd_id."' ";
    		$datas = $this->db->query($sql)->result();


            // $db = $this->load->database("218g", TRUE);

    		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", datas : ". $datas);
    		$cnt = 0;
    		foreach($datas as $r) {

    			//스마트전단 상품 추가
    			$data = array();
    			$data["psg_mem_id"] = $r->psg_mem_id; //회원번호
    			$data["psg_psd_id"] = $r->psg_psd_id; //전단번호
    			$data["psg_tit_id"] = $r->psg_tit_id; //타이틀번호
    			$data["psg_step"] = $r->psg_step; //스텝
    			$data["psg_step_no"] = $r->psg_step_no; //스텝순번
    			$data["psg_imgpath"] = $r->psg_imgpath; //이미지경로

    			$data["psg_name"] = $r->psg_name; //상품명
    			$data["psg_option"] = $r->psg_option; //옵션명
    			$data["psg_price"] = $r->psg_price; //정상가
                $data["psg_evprice"] = $r->psg_evprice; //정상가
    			$data["psg_dcprice"] = $r->psg_dcprice; //할인가
    			$data["psg_seq"] = $r->psg_seq; //정렬순서
    			$data["psg_badge"] = $r->psg_badge; //할인뱃지
    			$data["psg_box_no"] = $r->psg_box_no; //박스별 순번
    			$data["psg_option2"] = $r->psg_option2; //추가란
                $data["psg_option3"] = $r->psg_option3; //추가란
    			$data["psg_code"] = $r->psg_code; //바코드  2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
    			$data["psg_stock"] = $r->psg_stock; //품절여부 2021-06-17 추가
    			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", data_id : ". $data_id .", psg_tit_id = ". $data["psg_tit_id"] .", psg_step = ". $data["psg_step"] .", psg_step_no = ". $data["psg_step_no"] .", psg_imgpath = ". $data["psg_imgpath"] .", psg_name = ". $data["psg_name"] .", psg_option = ". $data["psg_option"] .", psg_price = ". $data["psg_price"] .", psg_dcprice = ". $data["psg_dcprice"] .", psg_seq = ". $data["psg_seq"]);
    			$db->replace("cb_pop_screen_goods_mt", $data); //데이타 추가
    			$cnt++;


    		}

            $sql = "select count(1) as cnt from cb_pop_screen_text_mt where pst_psd_id = '". $oridata->psd_id."' ";
            $tit_cnt = $db->query($sql)->row();

            if($tit_cnt > 0){
                $sql = "DELETE FROM cb_pop_screen_text_mt
    			WHERE pst_psd_id = '".$oridata->psd_id."'";
                $db->query($sql);
            }

            $sql = "select * from cb_pop_screen_text where pst_psd_id = '". $oridata->psd_id."' ";
    		$tdatas = $this->db->query($sql)->result();

            foreach($tdatas as $r) {
                //바로가기 텍스트 저장
                $data_text = array();
                $data_text["pst_mem_id"] = $r->pst_mem_id; //회원번호
                $data_text["pst_psd_id"] = $r->pst_psd_id; //전단번호
                $data_text["pst_step"] = $r->pst_step; //스텝
                $data_text["pst_step_no"] = $r->pst_step_no; //스텝순번
                $data_text["pst_seq"] = $r->pst_seq; //정렬순서
                $data_text["pst_box_no"] = $r->pst_box_no; //박스별 순번
                $data_text["pst_tit_text_info"] =$r->pst_tit_text_info; //바로가기 텍스트정보
                $data_text["pst_from"] ="91m"; //원본서버
                $db->replace("cb_pop_screen_text_mt", $data_text);
            }

            $result["code"] = 0;

        }else{
            $result["code"] = 1;
        }


		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 데이타 저장
	public function evemaker_data_save(){
		// $flag = $this->input->post("flag"); //구분
		$data_id = $this->input->post("data_id"); //일련번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id);
		$data = array();
		// $data["psd_tem_id"] = ($this->input->post("tem_id") != "") ? $this->input->post("tem_id") : "0"; //템플릿번호
		$data["emd_title"] = $this->input->post("emd_title"); //행사제목
		$data["emd_sub_info"] = $this->input->post("emd_sub_info"); //행사기간


		// $data["psd_open_yn"] = ($this->input->post("psd_order_yn") != "") ? $this->input->post("psd_order_yn") : "N"; //주문하기 사용여부 2021-03-05
		$data["emd_open_sdt"] = ($this->input->post("emd_open_sdt") != "") ? $this->input->post("emd_open_sdt") : null; //주문하기 시작일자 2021-03-05
		$data["emd_open_edt"] = ($this->input->post("emd_open_edt") != "") ? $this->input->post("emd_open_edt") : null; //주문하기 종료일자 2021-03-05

        $data["emd_tem_type"] = ($this->input->post("emd_tem_type") != "") ? $this->input->post("emd_tem_type") : "A";
        // $data["psd_order_st"] = $data["psd_order_yn"] == "Y" ? $this->input->post("psd_order_sh") . $this->input->post("psd_order_sm") : null; //주문하기 시작시간분 0710
        // $data["psd_order_et"] = $data["psd_order_yn"] == "Y" ? $this->input->post("psd_order_eh") . $this->input->post("psd_order_em") : null; //주문하기 시작시간분 1930
        // $data["psd_font_LN"] = ($this->input->post("psd_font_LN") != "") ? $this->input->post("psd_font_LN") : "L"; //글꼴크기 2023-02-01
        // $data["psd_tem_type"] = ($this->input->post("psd_tem_type") != "") ? $this->input->post("psd_tem_type") : "A";
        // $data['psd_order_alltime'] = $this->input->post("psd_order_alltime");
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id .", psd_tem_id = ". $data["psd_tem_id"] .", psd_title = ". $data["psd_title"] .", psd_date = ". $data["psd_date"] .", psd_step1_yn = ". $data["psd_step1_yn"] .", psd_step2_yn = ". $data["psd_step2_yn"] .", psd_step3_yn = ". $data["psd_step3_yn"]);

        // $db = $this->load->database("218g", TRUE);

        // $sql = "select count(1) as cnt from cb_pop_screen_data_mt where psd_id = '".$data_id."' and psd_from = '91m'";
        // $smartcnt = $db->query($sql)->row()->cnt;



		if(!empty($data_id)) { //수정의 경우

			$where = array();
			$where["emd_id"] = $data_id; //일련번호
			$where["emd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$rtn = $this->db->update("cb_evemaker_data", $data, $where); //데이타 수정

			//코드 조회
			$sql = "select emd_code as code from cb_evemaker_data where emd_id = '". $data_id ."' ";
			$code = $this->db->query($sql)->row()->code;

            // if($smartcnt==0){
            //     // $this->check_shop();
            //     $sql = "select psd_code, psd_seq, psd_viewyn, psd_ver_no, psd_open_yn from cb_pop_screen_data where psd_id = '". $data_id ."' ";
    		// 	$oridata = $this->db->query($sql)->row();
            //
            //
            //     $data["psd_id"] = $data_id; //일련번호
    		// 	$data["psd_mem_id"] = $this->member->item("mem_id"); //회원번호
    		// 	$data["psd_code"] = $oridata->psd_code; //전단코드
    		// 	$data["psd_seq"] = $oridata->psd_seq; //정렬순서
    		// 	$data["psd_viewyn"] = $oridata->psd_viewyn; //스마트전단 샘플 뷰 (Y/N)
    		// 	$data["psd_ver_no"] = $oridata->psd_ver_no; //버전번호
            //     $data["psd_open_yn"] = $oridata->psd_open_yn;
    		// 	if($this->member->item("mem_level") >= 100) {
    		// 	  $data["psd_mng_yn"] = 'Y';
    		// 	}
            //     $data["psd_from"] = '91m';
            //     $rtn2 = $db->replace("cb_pop_screen_data_mt", $data); //데이타 추가
            // }else{
                // $where["psd_from"] = "91m";
                // $rtn2 = $db->update("cb_pop_screen_data_mt", $data, $where); //데이타 수정
            // }

		}else{ //등록
			//신규 일련번호
			// $sql = "select ifnull(max(psd_id),0)+1 as psd_id from cb_pop_screen_data ";
			// $data_id = $this->db->query($sql)->row()->psd_id;
            //
			// //스마트전단 데이타 신규 정렬순서
			// $sql = "select ifnull(max(psd_seq),0)+1 as psd_seq from cb_pop_screen_data where psd_mem_id = '". $this->member->item("mem_id") ."' ";
			// $psd_seq = $this->db->query($sql)->row()->psd_seq;

			//전단코드 생성
			$this->load->library('base62');
			$surl_id = $this->member->item("mem_id").cdate('YmdHis');
			$code = $this->base62->encode($surl_id);

			//스마트전단 데이타 추가
			// $data["psd_id"] = $data_id; //일련번호
			$data["emd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$data["emd_code"] = $code; //전단코드


			$rtn = $this->db->insert("cb_evemaker_data", $data); //데이타 추가
            $data_id = $this->db->insert_id();


		}


		$result = array();
		$result['code'] = '0';
		$result['id'] = $data_id;
		$result['emd_code'] = $code;
		// $result['flag'] = $flag;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 복사
	public function evemaker_copy(){
		$data_id = $this->input->post("data_id"); //일련번호
		$mem_id = $this->member->item("mem_id"); //회원번호
		log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id);

		// //신규 일련번호
		// $sql = "select ifnull(max(psd_id),0)+1 as psd_id from cb_pop_screen_data ";
		// $psd_id = $this->db->query($sql)->row()->psd_id;
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > psd_id : ". $psd_id);

		// //스마트전단 데이타 신규 정렬순서
		// $sql = "select ifnull(max(psd_seq),0)+1 as psd_seq from cb_pop_screen_data where psd_mem_id = '". $mem_id ."' ";
		// $psd_seq = $this->db->query($sql)->row()->psd_seq;
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > psd_seq : ". $psd_seq);

		//전단코드 생성
		$this->load->library('base62');
		$surl_id = $mem_id.cdate('YmdHis');
		$code = $this->base62->encode($surl_id);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > code : ". $code);

		//스마트전단 데이타 복사
		$sql = "
		INSERT INTO cb_evemaker_data (
            emd_mem_id
            , emd_code
            , emd_type
            , emd_open_yn
            , emd_title
            , emd_sub_info
            , emd_useyn
            , emd_goods_cnt
		)
		SELECT
            ". $mem_id ." AS emd_mem_id
            , '". $code ."' AS emd_code
            , emd_type
            , emd_open_yn
            , CONCAT(emd_title, '_복사') AS emd_title
            , emd_sub_info
            , emd_useyn
            , 0
		FROM cb_evemaker_data
		WHERE emd_id = '". $data_id ."' ";
		$this->db->query($sql);
        $emd_id = $this->db->insert_id();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 복사 ". $sql);



    		//스마트전단 상품 복사
    		// 2021-06-01 상품코드 추가
    		$sql = "
    		INSERT INTO cb_evemaker_goods (
                emg_id
                , emg_mem_id
                , emg_emd_id
                , emg_imgpath
                , emg_type
                , emg_name
                , emg_sub_name
                , emg_deposite
                , emg_seq
                , emg_cnt
    		)
    		SELECT
                CONCAT(emg_id, '_00') AS emg_id
                , ". $mem_id ." AS emg_mem_id
                ,  ". $emd_id ." AS emg_emd_id
                , emg_imgpath
                , emg_type
                , emg_name
                , emg_sub_name
                , emg_deposite
                , emg_seq
                , emg_cnt
    		FROM cb_evemaker_goods
    		WHERE emg_emd_id = '". $data_id ."' AND emg_useyn = 'Y'
    		";
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
	    $surl_id = $mem_id.cdate('YmdHis');
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
            , psd_goods
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
            , psd_goods
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
            , psg_evprice /* 정상가 */
			, psg_dcprice /* 할인가 */
			, psg_seq /* 정렬순서 */
			, psg_box_no /* 박스별 순번 */
			, psg_option2 /* 추가란 */
            , psg_option3 /* 추가란 */
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
            , psg_evprice /* 정상가 */
			, psg_dcprice /* 할인가 */
			, psg_seq /* 정렬순서 */
			, psg_box_no /* 박스별 순번 */
			, psg_option2 /* 추가란 */
            , psg_option3 /* 추가란 */
		FROM cb_pop_screen_goods
		WHERE psg_psd_id = '". $data_id ."'
		ORDER BY psg_step ASC, psg_step_no ASC, psg_seq ASC ";
	    $this->db->query($sql);
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품 복사 ". $sql);

			//스마트전단 바로가기텍스트 복사
			$sql = "
			INSERT INTO cb_pop_screen_text (
					pst_mem_id /* 회원번호 */
				, pst_psd_id /* 전단번호 */
				, pst_step /* 스텝 */
				, pst_step_no /* 스텝순번 */
				, pst_seq /* 정렬순서 */
				, pst_box_no /* 박스별 순번 */
				, pst_tit_text_info /* 타이틀 텍스트 정보 */
			)
			SELECT
					". $mem_id ." AS pst_mem_id /* 회원번호 */
				, ". $psd_id ." AS pst_psd_id /* 전단번호 */
				, pst_step /* 스텝 */
				, pst_step_no /* 스텝순번 */
				, pst_seq /* 정렬순서 */
				, pst_box_no /* 박스별 순번 */
				, pst_tit_text_info /* 타이틀 텍스트 정보 */
			FROM cb_pop_screen_text
			WHERE pst_psd_id = '". $data_id ."'
			ORDER BY pst_step ASC, pst_step_no ASC, pst_seq ASC ";
			$this->db->query($sql);
			log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 바로가기텍스 복사 ". $sql);

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
		$sql = "select count(1) as cnt FROM cb_pop_screen_goods WHERE psg_psd_id = '".$data_id."'";
		$pre_cnt = $this->db->query($sql)->row()->cnt;
		if($pre_cnt>0){
            $sql = "DELETE FROM cb_pop_screen_goods_temp WHERE psg_psd_id = '".$data_id."'";
            $this->db->query($sql);
			$sql = "INSERT INTO cb_pop_screen_goods_temp(psg_mem_id, psg_psd_id, psg_tit_id, psg_step, psg_step_no, psg_imgpath, psg_name, psg_option, psg_price, psg_dcprice, psg_badge, psg_seq, psg_box_no, psg_option2, psg_mng_yn, psg_code, psg_stock) SELECT psg_mem_id, psg_psd_id, psg_tit_id, psg_step, psg_step_no, psg_imgpath, psg_name, psg_option, psg_price, psg_dcprice, psg_badge, psg_seq, psg_box_no, psg_option2, psg_mng_yn, psg_code, psg_stock FROM cb_pop_screen_goods WHERE psg_psd_id = '".$data_id."'";
			$this->db->query($sql);
		}

		//스마트전단 상품 삭제
		$sql = "delete from cb_pop_screen_goods where psg_mem_id = '". $mem_id ."' and psg_psd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품 삭제 : ". $sql);
		$this->db->query($sql);

		//바로가기텍스트 삭제
		$sql = "delete from cb_pop_screen_text where pst_mem_id = '". $mem_id ."' and pst_psd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품 삭제 : ". $sql);
		$this->db->query($sql);

        $db = $this->load->database("218g", TRUE);

        $sql = "select count(1) as cnt from cb_pop_screen_data_mt where psd_id = '".$data_id."' and psd_from = '91m'";

        $smartcnt = $db->query($sql)->row()->cnt;

        if($smartcnt>0){
            //스마트전단 상품 삭제
    		$sql = "delete from cb_pop_screen_goods_mt where psg_mem_id = '". $mem_id ."' and psg_psd_id = '". $data_id ."' ";
    		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품 삭제 : ". $sql);
    		$db->query($sql);

    		//바로가기텍스트 삭제
    		$sql = "delete from cb_pop_screen_text_mt where pst_mem_id = '". $mem_id ."' and pst_psd_id = '". $data_id ."' ";
    		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 상품 삭제 : ". $sql);
    		$db->query($sql);
        }


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
			$sql = "select count(1) as cnt FROM cb_images where img_name = '". $img_name ."' ";
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



    //스마트전단 코너 상품 저장 (배열)
    public function goods_conner_arr_save(){
        $mem_id = $this->member->item("mem_id"); //회원번호
		$updata = $this->input->post('updata'); //배열 데이타
        $data_id = $this->input->post('data_id');
        $box_no = $this->input->post('box_no');
	    $datas = json_decode( $updata );

        $result = array();

        // $db = $this->load->database("218g", TRUE);

		log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", datas : ". $datas);
		$cnt = 0;
        $datas_cnt = count($datas);
        if($datas_cnt > 0){
            $sql = "select count(1) as cnt from cb_pop_screen_goods where psg_psd_id='".$data_id."' and psg_box_no = '".$box_no."' and psg_mem_id = '".$mem_id."'";
            $pre_cnt = $this->db->query($sql)->row()->cnt;
            if($pre_cnt>0){
                $sql = "delete from cb_pop_screen_goods where psg_psd_id='".$data_id."' and psg_box_no = '".$box_no."' and psg_mem_id = '".$mem_id."'";
                $this->db->query($sql);

                $sql = "delete from cb_pop_screen_text where pst_psd_id='".$data_id."' and pst_box_no = '".$box_no."' and pst_mem_id = '".$mem_id."'";
                $this->db->query($sql);
            }

            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > [". $cnt ."] mem_id : ". $mem_id .", data_id : ". $data_id .", goods_imgpath : ". $goods_imgpath);

			//스마트전단 상품 추가
			// $data = array();
            $data_text = array();

            // log_message("ERROR", "___length : ".count($datas));

            foreach($datas as $r) {
                $data_id = $r->data_id; //전단번호
                $tit_id = $r->tit_id; //타이틀번호
    			if($tit_id == "" OR empty($tit_id)) $tit_id = "0"; //타이틀번호
    			$goods_imgpath = $r->goods_imgpath; //이미지경로
    			$goods_badge = $r->goods_badge; //할인뱃지
    			if($goods_badge == "" OR empty($goods_badge)) $goods_badge = "0"; //할인뱃지
    			$box_no = $r->box_no; //박스별 순번
    			if($box_no == "" OR empty($box_no)) $box_no = "0"; //박스별 순번

                $data[] = array(
                      "psg_mem_id" => $mem_id  //회원번호
                    , "psg_psd_id" => $data_id //전단번호
                    , "psg_tit_id" => $tit_id //타이틀번호
                    , "psg_step" => $r->goods_step //스텝
                    , "psg_step_no" => $box_no //스텝순번
                    , "psg_imgpath" => $goods_imgpath //이미지경로
                    , "psg_name" => $r->goods_name //상품명
                    , "psg_option" => $r->goods_option //옵션명
                    , "psg_price" => $r->goods_price //정상가
                    , "psg_dcprice" => $r->goods_dcprice //할인가
                    , "psg_seq" => $r->goods_seq //정렬순서
                    , "psg_badge" => $goods_badge //할인뱃지
                    , "psg_box_no" => $box_no //박스별 순번
                    , "psg_option2" => $r->goods_option2 //추가란
                    , "psg_code" => $r->goods_barcode //바코드  2021-06-01  goods_bar_ 추가 Pos에서 제품 추가시 - 이수환
                    , "psg_stock" => $r->goods_stock //품절여부 2021-06-17 추가
                );

                //바로가기 텍스트 저장
                if($cnt==0){
                    $data_text["pst_mem_id"] = $mem_id; //회원번호
        			$data_text["pst_psd_id"] = $data_id; //전단번호
        			$data_text["pst_step"] = $r->goods_step; //스텝
        			$data_text["pst_step_no"] = $r->goods_step_no; //스텝순번
        			$data_text["pst_seq"] = $r->goods_seq; //정렬순서
        			$data_text["pst_box_no"] = $box_no; //박스별 순번
        			$data_text["pst_tit_text_info"] =$r->tit_text_info; //바로가기 텍스트정보
                }

    			$cnt++;
                $divide = $cnt%10;
                if(($cnt!=1&&$divide == 1)||$datas_cnt==$cnt){
                    if(!empty($data)){
                        $this->db->insert_batch("cb_pop_screen_goods", $data); //데이타 추가
            			// $this->db->insert_batch("cb_pop_screen_text", $data_text);
                        unset( $data );
                        // unset( $data_text );
                    }

                }




            }
            // log_message("ERROR", "vardump data: ".var_dump($data));
            // log_message("ERROR", "vardump data: ".var_dump($data_text));
            // $this->db->insert_batch("cb_pop_screen_goods", $data); //데이타 추가
			// $this->db->insert_batch("cb_pop_screen_text", $data_text);
            if(!empty($data_text)){
                $rtn = $this->db->replace("cb_pop_screen_text", $data_text);
            }
            $result['code'] = '0';

        }else{
            $result['code'] = '1';

        }




            // $data_text["pst_from"] ="91m"; //원본서버
            // $rtn2 = $db->replace("cb_pop_screen_text", $data_text);



        $result['cnt'] = $cnt;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

	//스마트전단 상품 저장 (배열)
	public function evemaker_goods_arr_save(){
        $mem_id = $this->member->item("mem_id"); //회원번호
		$updata = $this->input->post('updata'); //배열 데이타
        $emd_all_cnt = $this->input->post('emd_all_cnt'); //배열 데이타
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > psd_all_cnt : ". $psd_all_cnt);
        if(empty($emd_all_cnt)){
            $emd_all_cnt = 0;
        }
	    $datas = json_decode( $updata );


        // $db = $this->load->database("218g", TRUE);

		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .", datas : ". $datas);
        $cnt = 0;
        $datas_cnt = count($datas);
        $datedata = cdate('mdHi');
        $i_box_no = 1;

        $dataid = "";
        //디자인 타이틀 뱃지이미지 조회
		// $sql = "SELECT *
		// FROM cb_design_title
		// WHERE tit_type = 'B' /* 구분(I.이미지형, T.텍스트형, B.뱃지이미지) */
		// AND tit_useyn = 'Y' /* 사용여부 (Y/N) */
        // AND tit_mem_id in ('3', '".$mem_id."')
		// ORDER BY tit_seq ASC ";
		// //echo $sql ."<br>";
		// $data_badge_list = $this->db->query($sql)->result();

        // $sql = "SELECT *
		// FROM cb_design_title
		// WHERE tit_type <> 'B' /* 구분(I.이미지형, T.텍스트형, B.뱃지이미지) */
		// AND tit_useyn = 'Y' /* 사용여부 (Y/N) */
        // AND tit_mem_id in ('3', '".$mem_id."')
		// ORDER BY tit_seq ASC ";
		// //echo $sql ."<br>";
		// $data_tit_list = $this->db->query($sql)->result();

			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > [". $cnt ."] mem_id : ". $mem_id .", data_id : ". $data_id .", goods_imgpath : ". $goods_imgpath);

			//스마트전단 상품 추가
			// $data = array();
            // $data_text = array();

            // log_message("ERROR", "___length : ".count($datas));
            $nogoods = "";
            foreach($datas as $r) {
                $data_id = $r->data_id; //전단번호
                // $dataid = $data_id;

    			$goods_imgpath = $r->goods_imgpath; //이미지경로


                if(!empty($r->goods_id)&&$emd_all_cnt>0){
                    $goods_id = $r->goods_id;
                }else{
                    $goods_id = $mem_id."_".$data_id."_".$datedata."_".$cnt;
                }
                // $data[] = array(
                //       "emg_id" => $goods_id  //상품번호
                //     , "emg_mem_id" => $mem_id  //회원번호
                //     , "emg_emd_id" => $data_id //전단번호
                //     , "emg_imgpath" => $goods_imgpath //이미지경로
                //     , "emg_name" => $r->goods_name //상품명
                //     , "emg_sub_name" => $r->goods_info //옵션명
                //     , "emg_cnt" => $r->goods_cnt //정상가
                //     , "emg_deposit" => $r->goods_deposit //행사가
                // );
                // if(!empty($goods_imgpath)){
                //     $libdata = array();
                //     $libdata["psl_psg_id"] = $goods_id; //상품번호
        		// 	$libdata["psl_mem_id"] = $mem_id; //회원번호
        		// 	$libdata["psl_psd_id"] = $data_id; //전단번호
                //
        		// 	$libdata["psl_imgpath"] = $goods_imgpath; //이미지경로
                //
        		// 	$libdata["psl_name"] = $r->goods_name; //상품명
        		// 	$libdata["psl_option"] = $r->goods_option; //옵션명
                //
        		// 	$this->db->replace("cb_evemaker_lib", $libdata); //데이타 추가
                // }

                if(!empty($r->goods_name)){
                    $gdata = array();
                    $gdata["emg_id"] = $goods_id; //상품번호
        			$gdata["emg_mem_id"] = $mem_id; //회원번호
        			$gdata["emg_emd_id"] = $data_id; //전단번호
                    $gdata["emg_type"] = $r->goods_type;
        			$gdata["emg_imgpath"] = $goods_imgpath; //이미지경로

        			$gdata["emg_name"] = $r->goods_name; //상품명
        			$gdata["emg_sub_name"] = $r->goods_info; //옵션명
                    $gdata["emg_cnt"] = $r->goods_cnt;
                    $gdata["emg_deposite"] = $r->goods_deposit;
                    $gdata["emg_seq"] = $cnt;
                    // if($r->goods_type=="N"){
                    $gdata["emg_barcode"] = $r->goods_barcode;
                    $gdata["emg_barcode_type"] = $r->goods_barcode_type;
                    // }
        			$this->db->replace("cb_evemaker_goods", $gdata); //데이타 추가
                    if(!empty($nogoods)){
                        $nogoods .= ","."'".$goods_id."'";
                    }else{
                        $nogoods .= "'".$goods_id."'";
                    }
                    $cnt++;
                }

            }



            if($datas_cnt>0){

                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > nogoods : ". $nogoods);

                $sql = "update cb_evemaker_data set emd_goods_cnt = '".$cnt."' where emd_mem_id = '". $mem_id ."' and emd_id = '". $data_id ."' ";
        		$this->db->query($sql);

                if(!empty($nogoods)){
                    $sql = "update cb_evemaker_goods set emg_useyn = 'N' WHERE emg_mem_id='".$mem_id."' AND emg_emd_id='". $data_id ."' AND emg_id not in (". $nogoods .")";
                   $this->db->query($sql);
                }

                // $db = $this->load->database("218g", TRUE);
                // $sql = "select count(1) as cnt from cb_pop_screen_data_mt where psd_id = '".$dataid."' and psd_from = '91m'";
                // $smartcnt = $db->query($sql)->row()->cnt;
                // $data = array();
                // if($smartcnt==0){
                //     $this->check_shop();
                //     $sql = "select psd_code, psd_seq, psd_viewyn, psd_ver_no, psd_open_yn from cb_pop_screen_data where psd_id = '". $dataid ."' ";
        		// 	$oridata = $this->db->query($sql)->row();
                //
                //
                //     $data["psd_id"] = $dataid; //일련번호
        		// 	$data["psd_mem_id"] = $this->member->item("mem_id"); //회원번호
        		// 	$data["psd_code"] = $oridata->psd_code; //전단코드
        		// 	$data["psd_seq"] = $oridata->psd_seq; //정렬순서
        		// 	$data["psd_viewyn"] = $oridata->psd_viewyn; //스마트전단 샘플 뷰 (Y/N)
        		// 	$data["psd_ver_no"] = $oridata->psd_ver_no; //버전번호
                //     $data["psd_open_yn"] = $oridata->psd_open_yn;
                //     $data["psd_goods"] = $jgoods;
                //     $data["psd_goods_cnt"] = $cnt;
        		// 	if($this->member->item("mem_level") >= 100) {
        		// 	  $data["psd_mng_yn"] = 'Y';
        		// 	}
                //     $data["psd_from"] = '91m';
                //     $rtn2 = $db->replace("cb_pop_screen_data_mt", $data); //데이타 추가
                // }else{

                    // $data["psd_goods"] = $jgoods;
                    // $data["psd_goods_cnt"] = $cnt;
                    //
                    // $where = array();
        			// $where["psd_id"] = $dataid; //일련번호
        			// $where["psd_mem_id"] = $this->member->item("mem_id"); //회원번호
                    // $where["psd_from"] = "91m";
                    // $rtn2 = $db->update("cb_pop_screen_data_mt", $data, $where); //데이타 수정
                // }

            }
            // log_message("ERROR", "vardump data: ".var_dump($data));
            // log_message("ERROR", "vardump data: ".var_dump($data_text));
            // $this->db->insert_batch("cb_pop_screen_goods", $data); //데이타 추가
			// $this->db->insert_batch("cb_pop_screen_text", $data_text);

            // $data_text["pst_from"] ="91m"; //원본서버
            // $rtn2 = $db->replace("cb_pop_screen_text", $data_text);

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
            $this->load->library('image_api');
			$this->upload->initialize($upload_img_config);
			if ($this->upload->do_upload($field_name)) {
				//업로드 성공
				$filedata = $this->upload->data();
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 업로드 성공 > filedata : ". $filedata .", thumb : ". $thumb);
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
                    $str_path_thumb = str_replace("/var/www/html", "", $simgconfig['new_image']);
					log_message("ERROR", $_SERVER['REQUEST_URI'] ." > new_image : ". $simgconfig['new_image']);
                    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > str_path_thumb : ". $str_path_thumb);
					$this->image_lib->initialize($simgconfig);
					if(!$this->image_lib->resize()) {
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 썸네일 이미지 저장 실패 : ". $this->upload->display_errors());
					}else{
                        $str_path_thumb = str_replace("/var/www/html", "", $simgconfig['new_image']);
                        $this->image_api->excute_image_api($str_path_thumb);
                    }
				}
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filedata : ". $filedata);
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > full_path : ". $filedata['full_path']);
                $str_path = str_replace("/var/www/html", "", $filedata['full_path']);
                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > str_path : ". $str_path);

				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file_name : ". $filedata['file_name']);
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > orig_name : ". $filedata['orig_name']);
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file_size : ". $filedata['file_size']);
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file_ext : ". $filedata['file_ext']);
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > image_width : ". $filedata['image_width']);
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > image_height : ". $filedata['image_height']);
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > image_type : ". $filedata['image_type']);
				//$this->resize_image($filedata, 300, 300); //이미지를 원하는 크기(원본비율 유지)로 리사이즈
				//$image = new Image($filedata['full_path']);
				//list($filedata['image_width'], $filedata['image_height']) = getimagesize($filedata['full_path']);
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > resize_image OK");
                $str_path = str_replace("/var/www/html", "", $filedata['full_path']);
                $this->image_api->excute_image_api($str_path);
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
			$tbl = "cb_pop_screen_lib";
			$where .= "AND psl_mem_id = '". $this->member->item("mem_id") ."' ";
			if(!empty($result['sf'])) {
				$orstr = explode(' ', $result['sf']);
				$orcnt = count($orstr);
				if($orcnt > 1) {
					$where .= "AND ( ";
					$prefix = '';
					for($i=0; $i<$orcnt; $i++){
						$where .= $prefix." psl_name LIKE '%". addslashes($orstr[$i]) ."%' ";
						$prefix = "AND ";
					}
					$where .= " ) ";
				} else {
					$where .= " AND psl_name LIKE '%". addslashes($orstr[0]) ."%' ";
				}
			}else{
				$where .= "AND psl_name != '' ";
			}
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > where : ". $where);

			//상품 전체수
			$sql = "
			SELECT
				COUNT(*) AS cnt
			FROM ". $tbl ."
			WHERE psl_psg_id IN (
				SELECT MAX(psl_psg_id)
				FROM ". $tbl ." a
				WHERE psl_imgpath != ''
				". $where ."
				GROUP BY psl_name
			) ";
			$result['total'] = $this->db->query($sql)->row()->cnt;

			//상품 현황
			$sql = "
			SELECT
				  psl_psg_id AS img_id /* 전단상품번호 */
				, psl_name AS img_name /* 상품명 */
				, psl_imgpath AS img_path /* 사진경로 */
			FROM ". $tbl ."
			WHERE psl_psg_id IN (
				SELECT MAX(psl_psg_id)
				FROM ". $tbl ." a
				WHERE psl_imgpath != ''
				". $where ."
				GROUP BY psl_name
			)
			ORDER BY psl_name ASC
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
			$sql = "select count(1) as cnt FROM cb_member_images ". $where;
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
			$sql = "select count(1) as cnt FROM cb_images i ". $where;
			$result['total'] = $this->db->query($sql)->row()->cnt;

			//이미지 현황
			// $sql = "
			// SELECT
			// 	  img_id /* 이미지번호 */
			// 	, img_name /* 이미지 이름 */
			// 	, img_path /* 이미지 경로 */
			// 	, (CASE when img_path_thumb != '' THEN img_path_thumb ELSE img_path END) AS img_path_thumb /* 썸네일 이미지 경로 */
			// FROM cb_images i
			// ". $where ."
			// ORDER BY i.img_name ASC
			// LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];

            $sql = "
            SELECT
                 img_id /* 이미지번호 */
               , img_name /* 이미지 이름 */
               , img_path /* 이미지 경로 */
               , (CASE when img_path_thumb != '' THEN img_path_thumb ELSE img_path END) AS img_path_thumb /* 썸네일 이미지 경로 */
               , cpsg.psg_id
           FROM cb_images i left join (
           select
				*
			from
				(
				(select
					psg_imgpath,
					max(psg_id) as psg_id
				from
					cb_pop_screen_goods
				where
					psg_imgpath <> ''
					and psg_mem_id = '". $this->member->item("mem_id") ."'
				group by
					psg_imgpath order by psg_id desc
				limit 10) as tmp
				)
           ) cpsg on i.img_path = cpsg.psg_imgpath
			". $where ."
			ORDER BY cpsg.psg_id desc, i.img_name ASC
			LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];
			log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
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
        $step = $this->input->post('step'); //step
        $tit_flag = "I";
        if($step=="3"){
            $tit_flag = "T";
        }
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
                $conner = "";
                $titidid = "";
				for ($row = 2; $row <= $highestRow; $row++){ //2번줄부터 읽기
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name : ". $rowData[0][0] .", price : ". $rowData[0][1] .", dcprice : ". $rowData[0][2]);
					//$code = ""; //상품코드
					//$name = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품명
					//$option = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //옵션명
					//$price = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //정상가
					//$dcprice = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //할인가
					$conner = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품코드

					$name = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //상품명
					$option = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //옵션명
					$price = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //정상가
                    $evprice = (empty($rowData[0][4])) ? "" : $rowData[0][4]; //행사가
					$dcprice = (empty($rowData[0][5])) ? "" : $rowData[0][5]; //할인가
					$option2 = (empty($rowData[0][6])) ? "" : $rowData[0][6]; //추가란 2021-01-22
                    $option3 = (empty($rowData[0][7])) ? "" : $rowData[0][7]; //추가란 2021-01-22
                    $ipath = (empty($rowData[0][8])) ? "" : $rowData[0][8]; //이미지 경로 2022-10-21
                    if($row==2&&!empty($conner)){

                        $sql = "select tit_id, tit_mem_id from cb_design_title where tit_useyn = 'Y' and tit_type = '".$tit_flag."' and tit_text_info='".$conner."' and (tit_mem_id = 3 or tit_mem_id = '".$this->member->item('mem_id')."') order by tit_id desc limit 1 ";
                        $tit = $this->db->query($sql)->row();
                        $tit_search_flag = "A";
                        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
                        if(!empty($tit->tit_id)){
                            $titid = $tit->tit_id;
                            $titidid = $titid;
                        }else{

                            $sql = "select tit_id, tit_mem_id from cb_design_title where tit_useyn = 'Y' and tit_type = '".$tit_flag."' and tit_text_info like '%".$conner."%' and (tit_mem_id = 3 or tit_mem_id = '".$this->member->item('mem_id')."') order by tit_id desc limit 1 ";
                            $tit = $this->db->query($sql)->row();
                            $tit_search_flag = "B";
                            if(!empty($tit->tit_id)){
                                $titid = $tit->tit_id;
                                $titidid = $titid;
                            }
                        }
                        if($tit->tit_mem_id!="3"){

                            if($tit_search_flag == "A"){
                                $sql = "select tit_id from cb_design_title where tit_useyn = 'Y' and tit_type = '".$tit_flag."' and tit_text_info='".$conner."' and tit_mem_id = 3 order by tit_id desc limit 1 ";
                            }else{
                                $sql = "select tit_id from cb_design_title where tit_useyn = 'Y' and tit_type = '".$tit_flag."' and tit_text_info like '%".$conner."%' and tit_mem_id = 3 order by tit_id desc limit 1 ";
                            }

                            $titit = $this->db->query($sql)->row()->tit_id;
                            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
                            if(!empty($titit)){
                                $titidid = $titit;
                            }
                        }
                    }
					$sch_name = $name;
					if($option != "") $sch_name .= " ". $option;
					// $result[$row-2]['code'] = trim($code); //상품코드
                    $result[$row-2]['conner'] = trim($conner); //상품코드
					$result[$row-2]['name'] = $name; //상품명
					$result[$row-2]['option'] = $option; //옵션명
					$result[$row-2]['price'] = $price; //정상가
                    $result[$row-2]['evprice'] = $evprice; //행사가
					$result[$row-2]['dcprice'] = $dcprice; //할인가
					$result[$row-2]['option2'] = $option2; //추가란 2021-01-22
                    $result[$row-2]['option3'] = $option3; //추가란 2021-01-22
					$result[$row-2]['img_path'] = $this->img_path("", $sch_name, $name, $option, $ipath, $titidid); //이미지 경로
					$result[$row-2]['seq'] = ($row-2); //정렬순서
					$result[$row-2]['rtn_data'] = $rtn_data; //리턴값
                    $result[$row-2]['tit_id'] = $titidid; //titid
				}
			}
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ". $result);
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > json : ". $json);
		echo $json;
	}


    //상품 엑셀 업로드
	public function excel_upload_ok_admin(){
	    $result = array();
		$rtn_data = $this->input->post('rtn_data'); //리턴값
        $step = $this->input->post('step'); //step
        $tit_flag = "I";
        if($step=="3"){
            $tit_flag = "T";
        }
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
                $conner = "";
                $titidid = "";
                $ii_num = 0;
				for ($row = 2; $row <= $highestRow; $row++){ //2번줄부터 읽기
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name : ". $rowData[0][0] .", price : ". $rowData[0][1] .", dcprice : ". $rowData[0][2]);
					//$code = ""; //상품코드
					//$name = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품명
					//$option = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //옵션명
					//$price = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //정상가
					//$dcprice = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //할인가
					$conner = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품코드

					$name = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //상품명
					$option = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //옵션명
					$price = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //정상가
                    $evprice = (empty($rowData[0][4])) ? "" : $rowData[0][4]; //행사가
					$dcprice = (empty($rowData[0][5])) ? "" : $rowData[0][5]; //할인가
					$option2 = (empty($rowData[0][6])) ? "" : $rowData[0][6]; //추가란 2021-01-22
                    $option3 = (empty($rowData[0][7])) ? "" : $rowData[0][7]; //추가란 2021-01-22
                    $ipath = (empty($rowData[0][8])) ? "" : $rowData[0][8]; //이미지 경로 2022-10-21
                    if($row==2&&!empty($conner)){

                        $sql = "select tit_id, tit_mem_id from cb_design_title where tit_useyn = 'Y' and tit_type = '".$tit_flag."' and tit_text_info='".$conner."' and (tit_mem_id = 3 or tit_mem_id = '".$this->member->item('mem_id')."') order by tit_id desc limit 1 ";
                        $tit = $this->db->query($sql)->row();
                        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
                        if(!empty($tit->tit_id)){
                            $titid = $tit->tit_id;
                            $titidid = $titid;
                        }
                        if($tit->tit_mem_id!="3"){
                            $sql = "select tit_id from cb_design_title where tit_useyn = 'Y' and tit_type = '".$tit_flag."' and tit_text_info='".$conner."' and tit_mem_id = 3 order by tit_id desc limit 1 ";
                            $titit = $this->db->query($sql)->row()->tit_id;
                            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
                            if(!empty($titit)){
                                $titidid = $titit;
                            }
                        }
                    }
					$sch_name = $name;
					if($option != "") $sch_name .= " ". $option;
					// $result[$row-2]['code'] = trim($code); //상품코드
                    if(!empty($name)){
                        $result[$ii_num]['conner'] = trim($conner); //상품코드
    					$result[$ii_num]['name'] = $name; //상품명
    					$result[$ii_num]['option'] = $option; //옵션명
    					$result[$ii_num]['price'] = $price; //정상가
                        $result[$ii_num]['evprice'] = $evprice; //행사가
    					$result[$ii_num]['dcprice'] = $dcprice; //할인가
    					$result[$ii_num]['option2'] = $option2; //추가란 2021-01-22
                        $result[$ii_num]['option3'] = $option3; //추가란 2021-01-22
    					$result[$ii_num]['img_path'] = $this->img_path("", $sch_name, $name, $option, $ipath, $titidid); //이미지 경로
    					$result[$ii_num]['seq'] = ($ii_num); //정렬순서
    					$result[$ii_num]['rtn_data'] = $rtn_data; //리턴값
                        $result[$ii_num]['tit_id'] = $titidid; //titid
                        $ii_num++;
                    }

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
	public function img_path($code, $sch_name, $name, $option, $ipath, $titid=NULL){
		$img_path = "";
        $where = "";
        $where2 = "";
        $where_ori1 = "";
        $where_ori2 = "";


        if($ipath != ""){
            $sql="select count(1) as cnt from cb_images where img_path = '".$ipath."'";
            $img_cnt = $this->db->query($sql)->row()->cnt;
            if($img_cnt>0){
                $img_path = $ipath;
            }
        }



        if($img_path == "" and $name != ""){ //최근상품 > 이미지명으로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT psl_imgpath AS img_path
			FROM cb_pop_screen_lib
			where psl_imgpath != ''
				AND psl_mem_id = '".$this->member->item("mem_id")."'
				AND psl_name = '". addslashes($name) ."'
				AND psl_option = '". addslashes($option) ."'
			order by psl_date desc
			LIMIT 1 ";
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}

        if($img_path == "" and $name != ""){ //최근상품 > 이미지명으로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT psl_imgpath AS img_path
			FROM cb_pop_screen_lib
			where psl_imgpath != ''
				AND psl_name = '". addslashes($name) ."'
				AND psl_option = '". addslashes($option) ."'
			order by psl_date desc
			LIMIT 1 ";
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}

        if($img_path == "" and $name != ""){ //최근상품 > 이미지명으로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT psl_imgpath AS img_path
			FROM cb_pop_screen_lib
			where psl_imgpath != ''
				AND psl_mem_id = '".$this->member->item("mem_id")."'
				AND psl_name = '". addslashes($name) ."'
			order by psl_date desc
			LIMIT 1 ";
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}

        if($img_path == "" and $name != ""){ //최근상품 > 이미지명으로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT psl_imgpath AS img_path
			FROM cb_pop_screen_lib
			where psl_imgpath != ''
				AND psl_name = '". addslashes($name) ."'
			order by psl_date desc
			LIMIT 1 ";
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}

		// if($img_path == "" and $name != ""){ //최근상품 > 이미지명으로 이미지경로 조회
		// 	//이미지 경로
		// 	$sql = "
		// 	SELECT psg_imgpath AS img_path
		// 	FROM cb_pop_screen_goods
		// 	WHERE psg_id = (
		// 		SELECT MAX(psg_id)
		// 		FROM cb_pop_screen_goods
		// 		WHERE psg_imgpath != ''
		// 		AND psg_dcprice != ''
		// 		AND psg_mem_id = '".$this->member->item("mem_id")."'
		// 		AND psg_name = '". addslashes($name) ."'
		// 		AND psg_option = '". addslashes($option) ."'
		// 		GROUP BY psg_name
		// 	)
		// 	LIMIT 1 ";
		// 	log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		// 	$img_path = $this->db->query($sql)->row()->img_path;
		// }
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_path1 : ". $img_path);
        // $code = trim($code);
        // if($img_path == "" and $code != ""){ //이미지 라이브러리 > 상품코드로 이미지경로 조회
		// 	//이미지 경로
		// 	$sql = "
		// 	SELECT img_path
		// 	FROM cb_images
		// 	WHERE img_useyn = 'Y'
		// 	AND img_code = '". addslashes($code) ."'
		// 	LIMIT 1 ";
		// 	log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		// 	$img_path = $this->db->query($sql)->row()->img_path;
		// }



        if($img_path == "" and $name != ""){ //이미지 라이브러리 > 상품코드로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT img_path
			FROM cb_images
			WHERE img_useyn = 'Y'
			AND img_name = '". addslashes($name) ."' OR img_name = '". addslashes($sch_name) ."' order by cnt desc
			LIMIT 1 ";
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_path2 : ". $img_path);
        //
        if($img_path == "" and !empty($name)) {
            $wherewhere = "";
            $in_ip = $_SERVER['REMOTE_ADDR'];
            // if($in_ip=="61.75.230.209"){

                $name_t = preg_replace("/\(|\)|\[|\]|\/|\"|\,|\_|\-|\+|\*/", ' ', $name);


                $name_t = str_replace('암소', '', $name_t );
                $name_t = str_replace('암돼지', '', $name_t );
                $name_t = str_replace('암퇘지', '', $name_t );
                $name_t = str_replace('1등급이상', '', $name_t );
                $name_t = str_replace('냉장', '', $name_t );
                $name_t = str_replace('프리미엄', '', $name_t );
                $name_t = str_replace('미국산', '', $name_t );
                $name_t = str_replace('국내산', '', $name_t );
                $name_t = str_replace('호주산', '', $name_t );
                $name_t = str_replace('산지직송', '', $name_t );

                $name_t = str_replace('기획', '', $name_t );

                $name_t = str_replace('멀티', '', $name_t );

                $name_t = str_replace('신라면', ' 신라면 ', $name_t );

                $name_t = str_replace('비비고', '비비고 ', $name_t );

                $name_t = str_replace('동원', '동원 ', $name_t );

                $name_t = str_replace('팔도', '팔도 ', $name_t );

                $name_t = str_replace('백설', '백설 ', $name_t );
                $name_t = str_replace('해태', '해태 ', $name_t );
                $name_t = str_replace('농심', '농심 ', $name_t );

                $name_t = str_replace('송학', '송학 ', $name_t );
                $name_t = str_replace('CJ', 'CJ ', $name_t );
                $name_t = str_replace('풀무원', '풀무원 ', $name_t );

                $name_t = str_replace('롯데', '롯데 ', $name_t );

                $name_t = str_replace('백설', '백설 ', $name_t );
                $name_t = str_replace('청우', '청우 ', $name_t );
                $name_t = str_replace('큐원', '큐원 ', $name_t );
                $name_t = str_replace('맥심', '맥심 ', $name_t );
                $name_t = str_replace('대림', '대림 ', $name_t );
                $name_t = str_replace('삼호', '삼호 ', $name_t );

                $name_t = str_replace('오리온', '오리온 ', $name_t );
                $name_t = str_replace('오뚜기', '오뚜기 ', $name_t );
                $name_t = str_replace('엘지', '엘지 ', $name_t );

                $name_t = str_replace('테크', '테크 ', $name_t );
                $name_t = str_replace('리엔', '리엔 ', $name_t );
                $name_t = str_replace('해피바스', '해피바스 ', $name_t );
                $name_t = str_replace('피죤', '피죤 ', $name_t );
                $name_t = str_replace('다우니', '다우니 ', $name_t );
                $name_t = str_replace('메디안', '메디안 ', $name_t );
                $name_t = str_replace('잘풀리는집', '잘풀리는집 ', $name_t );

                $name_t = str_replace('뽀로로', '뽀로로 ', $name_t );
                $name_t = str_replace('쁘띠첼', '쁘띠첼 ', $name_t );
                $name_t = str_replace('고메', '고메 ', $name_t );
                $name_t = str_replace('비요뜨', '비요뜨 ', $name_t );
                $name_t = str_replace('샤프란', '샤프란 ', $name_t );
                $name_t = str_replace('내몸에', '내몸에 ', $name_t );
                $name_t = str_replace('파워에이드', '파워에이드 ', $name_t );
                $name_t = str_replace('게토레이', '게토레이 ', $name_t );
                $name_t = str_replace('스프라이트', '스프라이트 ', $name_t );

                $name_t = str_replace('맛술', '맛술 ', $name_t );

                $name_t = str_replace('청정원', '청정원 ', $name_t );

                $name_t = str_replace('햇반', '햇반 ', $name_t );

                $name_t = str_replace('그래놀라', '그래놀라 ', $name_t );
                $name_t = str_replace('몽쉘', '몽쉘 ', $name_t );
                $name_t = str_replace('에너지바', '에너지바 ', $name_t );
                $name_t = str_replace('단백질바', '단백질바 ', $name_t );

                $name_t = str_replace('크라운', '크라운 ', $name_t );

                $name_t = str_replace('닥터', '닥터 ', $name_t );

                $name_t = str_replace('카스타드', '카스타드 ', $name_t );

                $name_t = str_replace('썬칩', '썬칩 ', $name_t );

                $name_t = str_replace('빙그레', '빙그레 ', $name_t );

                $name_t = str_replace('햄스빌', '햄스빌 ', $name_t );

                $name_t = str_replace('하임', '하임 ', $name_t );
                $name_t = str_replace('연세', '연세 ', $name_t );


                $name_t = str_replace('푸르밀', '푸르밀 ', $name_t );
                $name_t = str_replace('매일', '매일 ', $name_t );
                $name_t = str_replace('상하', '상하 ', $name_t );
                $name_t = str_replace('미닛', '미닛 ', $name_t );
                $name_t = str_replace('웅진', '웅진 ', $name_t );
                $name_t = str_replace('산타페', '산타페 ', $name_t );
                $name_t = str_replace('코카콜라', '코카콜라 ', $name_t );
                $name_t = str_replace('배홍동', '배홍동 ', $name_t );
                $name_t = str_replace('라면', '라면 ', $name_t );
                $name_t = str_replace('왕뚜껑', '왕뚜껑 ', $name_t );
                $name_t = str_replace('엘라스틴', '엘라스틴 ', $name_t );
                $name_t = str_replace('리스테린', '리스테린 ', $name_t );
                $name_t = str_replace('콘트라', '콘트라 ', $name_t );

                $name_t = str_replace('대란', ' 대란 계란 ', $name_t );
                $name_t = str_replace('키친타올', ' 키친타올 ', $name_t );
                $name_t = str_replace('각티슈', ' 각티슈 ', $name_t );
                $name_t = str_replace('락스', ' 락스 ', $name_t );
                $name_t = str_replace('주물럭', ' 주물럭 ', $name_t );
                $name_t = str_replace('파스타', ' 파스타 ', $name_t );
                $name_t = str_replace('스파게티', ' 스파게티 ', $name_t );
                $name_t = str_replace('오렌지', ' 오렌지 ', $name_t );
                $name_t = str_replace('피자', ' 피자 ', $name_t );
                $name_t = str_replace('떡볶이', ' 떡볶이 ', $name_t );
                $name_t = str_replace('쌀국수', ' 쌀국수 ', $name_t );
                if(strpos($name_t,'순두부')==true){
                    $name_t = str_replace('순두부', ' 순두부 ', $name_t );
                }else{
                    $name_t = str_replace('두부', ' 두부 ', $name_t );
                }
                $name_t = str_replace('우유', ' 우유 ', $name_t );
                $name_t = str_replace('두유', ' 두유 ', $name_t );
                $name_t = str_replace('식혜', ' 식혜 ', $name_t );
                $name_t = str_replace('김치', ' 김치 ', $name_t );
                $name_t = str_replace('참외', ' 참외 ', $name_t );

                if(strpos($name_t,'벌집삼겹살')==false){
                    $name_t = str_replace('삼겹살', ' 삼겹살 ', $name_t );
                }else{
                    $name_t = str_replace('벌집삼겹살', ' 벌집삼겹살 ', $name_t );
                }
                // $name_t = str_replace('냉면', ' 냉면 ', $name_t );
                $name_t = str_replace('한우', ' 한우 ', $name_t );
                $name_t = str_replace('한돈', ' 한돈 ', $name_t );

                $name_t = str_replace('곰탕', ' 곰탕 ', $name_t );

                $name_t = str_replace('불고기', ' 불고기 ', $name_t );
                $name_t = str_replace('양념', ' 양념 ', $name_t );
                if(strpos($name_t,'진간장')==true){
                    $name_t = str_replace('진간장', ' 진간장 ', $name_t );
                }else{
                    $name_t = str_replace('간장', ' 간장 ', $name_t );
                }
                $name_t = str_replace('고추장', ' 고추장 ', $name_t );
                $name_t = str_replace('샴푸', ' 샴푸 ', $name_t );
                $name_t = str_replace('컨디셔너', ' 컨디셔너 ', $name_t );
                $name_t = str_replace('물티슈', ' 물티슈 ', $name_t );
                $name_t = str_replace('바디워시', ' 바디워시 ', $name_t );
                $name_t = str_replace('복숭아', ' 복숭아 ', $name_t );
                $name_t = str_replace('딸기', ' 딸기 ', $name_t );
                $name_t = str_replace('사과', ' 사과 ', $name_t );
                if(strpos($name_t,'청포도')==false){
                    $name_t = str_replace('포도', ' 포도 ', $name_t );
                }else{
                    $name_t = str_replace('청포도', ' 청포도 ', $name_t );
                }

                $name_t = str_replace('장아찌', ' 장아찌 ', $name_t );

                if(strpos($name_t,'고기만두')==true){
                    $name_t = str_replace('고기만두', ' 고기만두 ', $name_t );
                }else if(strpos($name_t,'김치만두')==true){
                    $name_t = str_replace('김치만두', ' 김치만두 ', $name_t );
                }else{
                    $name_t = str_replace('만두', ' 만두 ', $name_t );
                }
                // $name_t = str_replace('포도', ' 포도 ', $name_t );
                $name_t = str_replace('블루베리', ' 블루베리 ', $name_t );
                $name_t = str_replace('파인애플', ' 파인애플 ', $name_t );
                $name_t = str_replace('돈가스', ' 돈가스 ', $name_t );
                $name_t = str_replace('돈까스', ' 돈까스 ', $name_t );
                $name_t = str_replace('쏘피', ' 쏘피 ', $name_t );
                $name_t = str_replace('액체세제', ' 액체세제 ', $name_t );
                $name_t = str_replace('후랑크', ' 후랑크 ', $name_t );
                $name_t = str_replace('단무지', ' 단무지 ', $name_t );
                $name_t = str_replace('어묵', ' 어묵 ', $name_t );
                $name_t = str_replace('베이컨', ' 베이컨 ', $name_t );
                $name_t = str_replace('깁밥햄', ' 김밥햄 ', $name_t );
                $name_t = str_replace('비엔나', ' 비엔나 ', $name_t );

                $name_t = str_replace('둥지', ' 둥지 ', $name_t );


                $name_t = str_replace('kg', '', $name_t );
                $name_t = str_replace('ml', '', $name_t );

                $name_t = preg_replace('/[0-9]/', ' ', $name_t);

                $name_t = preg_replace('/[ ]{2,}/', ' ', $name_t);

                if(strpos($name_t,'주스')){
                    $wherewhere .= " and img_name LIKE '%주스%' ";
                }

                if(strpos($name_t,'에이드')){
                    $wherewhere .= " and img_name LIKE '%에이드%' ";
                }

                if(strpos($name_t,'우유')){
                    $wherewhere .= " and img_name LIKE '%우유%' ";
                }

                if(strpos($name_t,'두유')){
                    $wherewhere .= " and img_name LIKE '%두유%' ";
                }

                if(strpos($name_t,'샘표')){
                    $wherewhere .= " and img_name LIKE '%샘표%' ";
                }

                if(strpos($name_t,'간장')){
                    $wherewhere .= " and img_name LIKE '%간장%' ";
                }

                if(strpos($name_t,'치즈')){
                    $wherewhere .= " and img_name LIKE '%치즈%' ";
                }

                if(strpos($name_t,'라면')){
                    $wherewhere .= " and img_name LIKE '%라면%' ";
                }

                if(strpos($name_t,'신라면')){
                    $wherewhere .= " and img_name LIKE '%신라면%' ";
                }

                if(strpos($name_t,'진라면')){
                    $wherewhere .= " and img_name LIKE '%진라면%' ";
                }

                if(strpos($name_t,'둥지')){
                    $wherewhere .= " and img_name LIKE '%둥지%' ";
                }

                if(strpos($name_t,'카레')){
                    $wherewhere .= " and img_name LIKE '%카레%' ";
                }

                if(strpos($name_t,'백세카레')){
                    $wherewhere .= " and img_name LIKE '%백세카레%' ";
                }

                if(strpos($name_t,'햇반')){
                    $wherewhere .= " and img_name LIKE '%햇반%' ";
                }

                if(strpos($name_t,'컵반')){
                    $wherewhere .= " and img_name LIKE '%컵반%' ";
                }


                // $orstr = preg_split("/\(|\)|\[|\]|\//", $name);
                 log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name_t : ". $name_t);
                $orstr = explode(' ', trim($name_t));
                // $orcnt0 = count($orstr);
                // if($orcnt0>0){
                //     if($orcnt0>1){
                //
                //     }else{
                //         // $orstr = explode(' ', $name);
                //         $orstr = preg_split("/\(|\)|\[|\]|\/|\s{0,}/", $name);
                //     }
                //
                //     array_merge()
                // }
            // }else{
            //     $orstr = explode(' ', $name);
            //     $name_t = $name;
            // }



            $orcnt = count($orstr);
            if($orcnt > 1) {
                $where .= " and ( ";
                $where2 .= " and ( ";
                $prefix = '';
                $prefix2 = '';
                for($i=0; $i<$orcnt; $i++){
                    if((preg_match("/[\xA1-\xFE][\xA1-\xFE]/", $orstr[$i])!=true)|| (preg_match('/[0-9]/', $orstr[$i]) && preg_match('/[a-z]/i', $orstr[$i]))){

                    }else{
                        if($i==0){
                            if($orstr[$i]=="매"){
                                $orstr[$i] = "매일";
                            }else if($orstr[$i]=="빙"){
                                $orstr[$i] = "빙그레";
                            }else if($orstr[$i]=="풀"){
                                $orstr[$i] = "풀무원";
                            }else if($orstr[$i]=="오"){
                                $orstr[$i] = "오리온";
                            }else if($orstr[$i]=="청"){
                                $orstr[$i] = "청정원";
                            }else if($orstr[$i]=="농"){
                                $orstr[$i] = "농심";
                            }else if($orstr[$i]=="동"){
                                $orstr[$i] = "동서";
                            }else if($orstr[$i]=="롯"){
                                $orstr[$i] = "롯데";
                            }else if($orstr[$i]=="동"){
                                $orstr[$i] = "동원";
                            }else if($orstr[$i]=="샘"){
                                $orstr[$i] = "샘표";
                            }

                        }
                        if($orstr[$i]!=""&&mb_strlen(trim($orstr[$i]))!=1){
                            $where .= $prefix." img_name LIKE '%". addslashes(trim($orstr[$i])) ."%' ";
                            $prefix = " and ";

                            $where2 .= $prefix2." img_name LIKE '%". addslashes(trim($orstr[$i])) ."%' ";
                            $prefix2 = " or ";
                        }

                    }
                }
                if($where==" and ( "){
                    $where .= " img_name LIKE '%". addslashes(trim($orstr[0])) ."%' ";
                    $where2 .= " img_name LIKE '%". addslashes(trim($orstr[0])) ."%' ";
                }
                $where .= " ) ";
                $where2 .= " ) ";
            }else{
                $where .= " and img_name LIKE '%". addslashes(trim($name_t)) ."%' ";
                $where2 .= " and img_name LIKE '%". addslashes(trim($name_t)) ."%' ";
            }
            if($name=="농심 둥지냉면 동치미물냉면농심 둥지냉면 비빔냉면"){
                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name_t where : ". $where);
                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name_t where2 : ". $where2);

                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name_t wherewhere : ". $wherewhere);
            }
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name_t where : ". $where);
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name_t where2 : ". $where2);
            $where_ori1 = $where;
            $where_ori2 = $where2;

            if(!empty($titid)) {
                $catenum = $this->funn->get_img_cate($titid);
                if($catenum["cate1"]!="0"||$catenum["cate2"]!="0"||$catenum["cate3"]!="0"||$catenum["cate4"]!="0"){

                    if($catenum["cate4"]!="0"){
                        $where .= " and img_category1 <> '".$catenum["cate4"]."' ";
                        $where2 .= " and img_category1 <> '".$catenum["cate4"]."' ";
                    }else if($catenum["cate3"]!="0"){
                        $where .= " and img_category1 = '".$catenum["cate1"]."' and ( img_category2 = '".$catenum["cate2"]."' or img_category2 = '".$catenum["cate3"]."') ";
                        $where2 .= " and img_category1 = '".$catenum["cate1"]."' and ( img_category2 = '".$catenum["cate2"]."' or img_category2 = '".$catenum["cate3"]."') ";
                    }else{
                        // $where .= " and img_category1 = '".$catenum["cate1"]."' and  img_category2 = '".$catenum["cate2"]."' ";
                        $where .= " and img_category1 = '".$catenum["cate1"]."' ";
                        $where2 .= " and img_category1 = '".$catenum["cate1"]."' ";
                        if($catenum["cate2"]!="0"){
                            $where .= " and  img_category2 = '".$catenum["cate2"]."' ";
                            $where2 .= " and  img_category2 = '".$catenum["cate2"]."' ";
                        }

                        // $where2 .= " and img_category1 = '".$catenum["cate1"]."' and  img_category2 = '".$catenum["cate2"]."' ";
                    }
                }
            }
        }
        //
		if($img_path == "" and $name != ""){ //이미지 라이브러리 > 이미지명으로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT img_path
			FROM cb_images
			WHERE img_useyn = 'Y'
			".$where.$wherewhere." and img_category1 <> '0' and img_category2 <> '0' order by cnt desc, img_category1 asc, img_id desc
			LIMIT 1 ";

            if($name=="농심 둥지냉면 동치미물냉면농심 둥지냉면 비빔냉면"){
                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
            }
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_path3 : ". $img_path);
        //
        if($img_path == "" and $name != ""){ //이미지 라이브러리 > 이미지명으로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT img_path
			FROM cb_images
			WHERE img_useyn = 'Y'
			".$where2.$wherewhere." and img_category1 <> '0' and img_category2 <> '0' order by cnt desc, img_category1 asc, img_id desc
			LIMIT 1 ";

            if($name=="농심 둥지냉면 동치미물냉면농심 둥지냉면 비빔냉면"){
                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
            }
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}

        if($img_path == "" and $name != ""){ //이미지 라이브러리 > 이미지명으로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT img_path
			FROM cb_images
			WHERE img_useyn = 'Y'
			".$where_ori1.$wherewhere." and img_category1 <> '0' and img_category2 <> '0' order by cnt desc, img_category1 asc, img_id desc
			LIMIT 1 ";

            if($name=="농심 둥지냉면 동치미물냉면농심 둥지냉면 비빔냉면"){
                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
            }
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}

        if($img_path == "" and $name != ""){ //이미지 라이브러리 > 이미지명으로 이미지경로 조회
			//이미지 경로
			$sql = "
			SELECT img_path
			FROM cb_images
			WHERE img_useyn = 'Y'
			".$where_ori2.$wherewhere." and img_category1 <> '0' and img_category2 <> '0' order by cnt desc, img_category1 asc, img_id desc
			LIMIT 1 ";

            if($name=="농심 둥지냉면 동치미물냉면농심 둥지냉면 비빔냉면"){
                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
            }
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$img_path = $this->db->query($sql)->row()->img_path;
		}

        // if($img_path == "" and $name != ""){
		// 	//이미지 경로
		// 	$sql = "
        //     SELECT img_path
		// 	FROM cb_images
		// 	WHERE img_useyn = 'Y'
		// 	AND img_name LIKE '". addslashes(trim($name)) ."%'
		// 	ORDER BY cnt DESC
		// 	LIMIT 1 ";
		// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		// 	$imgpath = $this->db->query($sql)->row()->imgpath;
		// }
        //
		// if($img_path == "" and $name != ""){
		// 	//이미지 경로
		// 	$sql = "
        //     SELECT img_path
		// 	FROM cb_images
		// 	WHERE img_useyn = 'Y'
		// 	AND img_name LIKE '%". addslashes(trim($name)) ."%'
		// 	ORDER BY cnt DESC
		// 	LIMIT 1 ";
		// 	//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		// 	$imgpath = $this->db->query($sql)->row()->imgpath;
		// }
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_path4 : ". $img_path);


        // if($img_path == "" and $name != ""){ //최근상품 > 이미지명으로 이미지경로 조회
		// 	//이미지 경로
		// 	$sql = "
		// 	SELECT psg_imgpath AS img_path
		// 	FROM cb_pop_screen_goods
		// 	WHERE psg_id = (
		// 		SELECT MAX(psg_id)
		// 		FROM cb_pop_screen_goods
		// 		WHERE psg_imgpath != ''
		// 		AND psg_dcprice != ''
		// 		AND psg_mem_id = '3'
		// 		AND psg_name = '". addslashes($name) ."'
		// 		AND psg_option = '". addslashes($option) ."'
		// 		GROUP BY psg_name
		// 	)
		// 	LIMIT 1 ";
		// 	// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		// 	$img_path = $this->db->query($sql)->row()->img_path;
		// }
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_path4 : ". $img_path);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_path : ". $img_path);


		return $img_path;
	}

	//스마트전단 데이타 조회
	public function eve_data(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 조회");
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		$result = array();
		$view = array();
		$view['view'] = array();
		$view['perpage'] = ($this->input->post("per_page")) ? $this->input->post("per_page") : 8;
		$view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
		$view['emdtype'] = ($this->input->post("emdtype")) ? $this->input->post("emdtype") : "R"; //타입

		//검색조건
		$where = "WHERE emd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND emd_type = '". $view['emdtype'] ."' /* 타입 */ ";
		$where .= "AND emd_useyn = 'Y' ";

		//스마트전단 전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_evemaker_data a
		". $where ." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//스마트전단 리스트
		$sql = "
		SELECT
			  emd_id /* 전단번호 */
			, emd_code /* 전단코드 */
			, emd_title /* 제목 */
            , emd_sub_info
			, DATE_FORMAT(emd_open_sdt, '%y.%m.%d') AS emd_sdate
            , DATE_FORMAT(emd_open_edt, '%y.%m.%d') AS emd_edate
			, DATE_FORMAT(emd_cre_date, '%y.%m.%d') AS emd_credt /* 등록일자 */
            , emd_img_url
		FROM cb_evemaker_data a
		". $where ."
		ORDER BY emd_id DESC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
		log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$result = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'eve_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='eve_page($1)'><a herf='#' ",$view['page_html']);
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

    //스마트전단 상품정보 가져오기
	public function smart_goods_call_new(){
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

		// if($psd_ver_no == 1){ //버전 1의 경우
		// 	$sort = "psg_step ASC,  psg_step_no ASC, psg_seq ASC";
		// }else{
		// 	$sort = "psg_step_no ASC, psg_seq ASC";
		// }
        //
		// //코너별 상품등록 조회
		// $sql = "
		// SELECT a.*
		// FROM cb_pop_screen_goods a
		// WHERE psg_mem_id = '". $this->member->item("mem_id") ."' /* 회원번호 */
		// AND psg_psd_id = '". $psd_id ."' /* 전단번호 */
		// ORDER BY ". $sort ." ";
		// //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		// $result = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);
        $psd_goods = json_decode($screen_data->psd_goods);
        // $result = array();

        $result['pdata'] = $psd_goods->pdata;
        $result['data'] = $psd_goods->data;
        $result['cnt0'] = $psd_goods->cnt0;
        $result['cnt1'] = $psd_goods->cnt1;

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
        $db = $this->load->database("218g", TRUE);

        $db->replace("cb_design_templet_mt", $data);

		$result = array();
		$result['code'] = '0';
		$result['tem_id'] = $tem_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}
	//템플릿 직접입력 저장
	public function title_self_save(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$imgpath = $this->input->post('imgpath'); //템플릿 직접입력 이미지 경로

		$data = array();
		$sql = "select ifnull(max(tit_id),0)+1 as tit_id from cb_design_title ";
		$tit_id = $this->db->query($sql)->row()->tit_id;
		$data["tit_id"] = $tit_id; //템플릿번호
		$data["tit_mem_id"] = $this->member->item("mem_id"); //회원번호
		$data["tit_imgpath"] = $this->input->post("imgpath"); //템플릿 이미지 경로
		$data["tit_type"] = $this->input->post("tit_type"); //타이틀 타입
		$data["tit_type_self"] = $this->input->post("tit_type_self"); //타이틀직접입력 여부
		$data["tit_seq"] = $tit_id; //정렬순서
		$rtn = $this->db->replace("cb_design_title", $data);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > goods_id : ". $goods_id .", rtn = ". $rtn);

		$result = array();
		$result['code'] = '0';
		$result['tit_id'] = $tit_id;
		$result['tit_imgpath'] = $imgpath;
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
		$sql = "select count(1) as cnt FROM cb_member_images i ". $where;
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
			'path' => 'spop/evemaker',
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
        $this->load->library('image_api');
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
                    $this->image_api->excute_image_api($imgpath);
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
			$thumb_size = "200"; // //이미지 제한 크기(px 단위)(넓이 또는 높이 최대값)
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
                    if($filedata['image_width'] <= $filedata['image_height']){
                        $resize_type = "width";
                    }else{
                        $resize_type = "height";
                    }
					// $resize_type = "";
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

    public function conner_download_new() {

        $data_id = $this->input->post('data_id'); //배열 데이타
		$updata = $this->input->post('updata'); //배열 데이타
	    $datas = json_decode( $updata );

	    $view = array();

	    // $view['param']['search_type'] = $this->input->post("search_type");
	    // $view['param']['start_date'] = $this->input->post("start_date");
	    // $view['param']['end_date'] = $this->input->post("end_date");

        // $view['param']['psg_psd_id'] = $this->input->post("psd_id");
	    // $view['param']['psg_box_no'] = $this->input->post("box_no");
        //
        //
        //
	    // $param = array();
        //
	    // $sql = "
        //     select *
		// 	from cb_pop_screen_goods
        //     where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psg_psd_id']."' and psg_box_no = '".$view['param']['psg_box_no']."'
        //     order by psg_seq asc
        // ";

	    $list = $datas;

	    $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

	    $tmp_id = $this->input->post('tmp_id');
	    $ids = explode(',', $tmp_id);

	    // 라이브러리를 로드한다.
	    $this->load->library('excel');

	    // 시트를 지정한다.
	    $this->excel->setActiveSheetIndex(0);
	    $this->excel->getActiveSheet()->setTitle('Sheet1');

	    // 필드명을 기록한다.
	    // 글꼴 및 정렬
	    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
	        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
	        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
	    ),	'A1:J1');

	    $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "코너구분 (선택)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "상품명 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "규격 (선택)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "정상가 (선택)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "행사가 (선택)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "할인가 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "옵션1 (선택)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "옵션2 (선택)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "코너유형 (선택)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "이미지 (선택)");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "총결제해야할 금액");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "결제금액");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "미수금액");

	    $cnt = 1;
	    $row = 2;


	    //number_format(($r->dep_datetime, 1)
	    foreach($list as $r) {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, (($row==2)? $r->tit_text_info : ""));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->goods_name);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->goods_option);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->goods_price);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->goods_evprice);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->goods_dcprice);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->goods_option2);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->goods_option3);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, "");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $r->goods_imgpath);
	        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->dep_deposit_datetime);
	        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->dep_cash_request);
	        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $r->dep_cash);
	        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format($r->dep_cash_request - abs($r->dep_cash)));
	        $row++;
	        $cnt++;
	    }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="'.$data_id.'_goods_conner_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');

	}





    //전단전체 엑셀 다운로드
    public function psd_download() {
	    $view = array();

	    // $view['param']['search_type'] = $this->input->post("search_type");
	    // $view['param']['start_date'] = $this->input->post("start_date");
	    // $view['param']['end_date'] = $this->input->post("end_date");

        $view['param']['psd_id'] = $this->input->post("data_id");
	    // $view['param']['psg_box_no'] = $this->input->post("box_no");



	    $param = array();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

	    // $tmp_id = $this->input->post('tmp_id');
	    // $ids = explode(',', $tmp_id);

	    // 라이브러리를 로드한다.
	    $this->load->library('excel');

	    // 시트를 지정한다.
	    $this->excel->setActiveSheetIndex(0);
	    $this->excel->getActiveSheet()->setTitle('Sheet1');

        $sql = "
            select *
           from cb_pop_screen_data
            where psd_mem_id = '".$this->member->item("mem_id")."' and psd_id = '".$view['param']['psd_id']."'
        ";

        $psd_data = $this->db->query($sql)->row();

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
	        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
	        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
	    ),	'A1:F1');

	    $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        // $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "템플릿ID");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "전단명 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "행사일정 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "할인&대표");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "이미지형");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "텍스트형");
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "주문하기 (선택)");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "총결제해야할 금액");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "결제금액");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "미수금액");

        $row=2;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $psd_data->psd_tem_id);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $psd_data->psd_title);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $psd_data->psd_date);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $psd_data->psd_step1_yn);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $psd_data->psd_step2_yn);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $psd_data->psd_step3_yn);
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->psg_imgpath);


	    $sql = "
            select *
			from cb_pop_screen_goods a left join cb_pop_screen_text b on a.psg_psd_id = b.pst_psd_id and a.psg_step_no = b.pst_step_no and a.psg_seq = b.pst_seq
            where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."'
            order by psg_box_no asc, psg_seq asc
        ";

	    $list = $this->db->query($sql)->result();



	    // 필드명을 기록한다.
	    // 글꼴 및 정렬
	    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
	        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
	        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
	    ),	'A3:N3');
        // $this->excel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

	    $this->excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
	    $this->excel->getActiveSheet()->getRowDimension('4')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
	    // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
	    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);


	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, "상품코드");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, "상품명 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, "규격");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, "정상가");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, "할인가(필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, "옵션");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, "이미지");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, "타이틀ID");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, "타이틀");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 3, "뱃지");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 3, "형태");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 3, "단락번호");
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, "순서");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, "재고");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 3, "이미지 자동매칭(Y)");

	    $cnt = 1;
	    $row = 4;
	    //number_format(($r->dep_datetime, 1)
	    foreach($list as $r) {
            // $this->excel->getActiveSheet()->getStyle("A".$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->psg_code." ");//->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->psg_name);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->psg_option);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->psg_price);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->psg_dcprice);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->psg_option2);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->psg_imgpath);
	        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->dep_deposit_datetime);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->psg_tit_id);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $r->pst_tit_text_info);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $r->psg_badge);
            $psg_step = "";
            switch ($r->psg_step) {
                case '1':
                    $psg_step = "1단";
                    break;

                case '2':
                    $psg_step = "2단";
                    break;

                case '3':
                    $psg_step = "텍스트";
                    break;

                case '4':
                    $psg_step = "3단";
                    break;
                case '5':
                    $psg_step = "4단";
                    break;
                case '9':
                    $psg_step = "이미지";
                    break;
            }
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $psg_step);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_box_no);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $r->psg_seq);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $r->psg_stock);
	        $row++;
	        $cnt++;
	    }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="'.$view['param']['psd_id'].'_smart_data.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');

	}


    public function download()
    {
		$view = array();
        $view['view'] = array();
		$view['esi_id'] = $this->input->get("eveid"); //개인정보동의번호
		$view['search_phn'] = $this->input->get("search_phn"); //연락처 검색
		$view['search_state'] = $this->input->get("search_state"); //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색

		//검색조건
		$where = "WHERE esd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND esd_esi_idx = '". $view['esi_id'] ."'  ";
		if($view['search_state'] != ""){
			$where .= "AND esd_state = '". $view['search_state'] ."' /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */ ";
		}
		if($view['search_phn'] != ""){
			//$search_phn = preg_replace("/[^0-9]/", "", $view['search_phn']); //숫자 이외 제거
			$search_phn = str_replace("-", "", $view['search_phn']); //- 제거
			$where .= "AND esd_phn LIKE '%". $search_phn ."%' /* 휴대전화번호 */ ";
		}

		//전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_evemaker_send_data a
		". $where ." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//리스트
		$sql = "
		SELECT
			  esd_idx /* 일련번호 */
			, esd_phn /* 연락처 */
			, esd_state /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */
			, DATE_FORMAT(esd_cre_date, '%Y-%m-%d %H:%i') AS esd_cre_date /* 발송일자 */
			, DATE_FORMAT(esd_view_date, '%Y-%m-%d %H:%i') AS esd_view_date /* 확인일자 */
			, DATE_FORMAT(esd_eve_date, '%Y-%m-%d %H:%i') AS esd_eve_date /* 수신동의일자 */
            , DATE_FORMAT(esd_goods_date, '%Y-%m-%d %H:%i') AS esd_goods_date
            , esd_emg_name
		FROM cb_evemaker_send_data a
		". $where ."
		ORDER BY esd_idx DESC ";
		//echo $sql ."<br>";
		log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$list = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view : ".$view);

		// 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:H1');

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A3:H3');

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10); //No
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); //전화번호
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); //상태
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); //발송일자
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20); //확인일자
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20); //수신동의일자
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20); //수신동의일자
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20); //수신동의일자

        $this->excel->getActiveSheet()->mergeCells('A1:H1');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '이벤트참여 상세내역');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'No');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '전화번호');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '상태');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '발송일자');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '이벤트 확인');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, '이벤트 참여');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, '상품수령');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, '당첨상품');

        $seq = 0;
		$row = 4;
        foreach($list as $r) {
			$num = $view['total_rows'] - $seq; //No
            $esd_phn = $this->funn->format_phone($r->esd_phn, "-"); //전화번호
            $esd_state = $r->esd_state; //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함)
            $esd_cre_date = $r->esd_cre_date; //발송일자
            $esd_view_date = ""; //확인일자
            $esd_eve_date = ""; //수령일자
            $esd_goods_date = ""; //수령일자

            if($esd_state == "0"){
                $esd_state = "발송완료";
            }else{
                if(!empty($r->esd_view_date)){
                    $esd_view_date = $r->esd_view_date; //확인일자
                }
                if(!empty($r->esd_eve_date)){
                    $esd_eve_date = $r->esd_eve_date;
                }
                if(!empty($r->esd_goods_date)){
                    $esd_goods_date = $r->esd_goods_date;
                }
                if($esd_state == "1"){
                    $esd_state = "이벤트 확인";

                }else if($esd_state == "2"){
                    $esd_state = "이벤트 참여";

                }else if($esd_state == "3"){
                    $esd_state = "상품수령";

                }
            }
			//가운데 정렬
			$this->excel->getActiveSheet()->duplicateStyleArray( array( 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER )),	'A'.$row.':H'.$row);

			// 데이터를 읽어서 순차로 기록한다.
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $num); //No
            //$this->excel->getActiveSheet()->setCellValueExplicit('A'.$row, $num, PHPExcel_Cell_DataType::TYPE_STRING); //문자형으로 변환
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $esd_phn); //전화번호
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $esd_state); //상태
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $esd_cre_date); //발송일자
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $esd_view_date); //확인일자
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $esd_eve_date); //수신동의일자
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $esd_goods_date);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->esd_emg_name);
            $row++;
            $seq++; //순번
        }

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="agree_list.xls"');
        header('Cache-Control: max-age=0');

        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
	}

    //스마트전단 > 상품수 체크
    public function psd_chk_goods(){
        $data_id = $this->input->post("data_id"); //전단번호
        $sql = "SELECT psd_goods_cnt FROM cb_pop_screen_data WHERE psd_mem_id = '". $this->member->item("mem_id") ."' and psd_id = '". $data_id ."' ";
        $cnt = $this->db->query($sql)->row()->psd_goods_cnt;
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > count(chkIds) : ". count($chkIds));
        $result = array();
        if($cnt > 0) { //선택 삭제
            $result['mode'] = 'N';
            if($cnt>1000){
                $result['code'] = '1';
            }else{
                $result['code'] = '0';
            }
        }else{
            $result['mode'] = 'O';
            $sql = "SELECT count(1) as cnt FROM cb_pop_screen_goods WHERE psg_mem_id = '". $this->member->item("mem_id") ."' and psg_psd_id = '". $data_id ."' ";
            $cnt = $this->db->query($sql)->row()->cnt;
            if($cnt>1000){
                $result['code'] = '1';
            }else{
                $result['code'] = '0';
            }
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
    }


    //전단전체 엑셀 다운로드 탭별 테스트
    public function psd_download_tab_old() {
	    $view = array();

	    // $view['param']['search_type'] = $this->input->post("search_type");
	    // $view['param']['start_date'] = $this->input->post("start_date");
	    // $view['param']['end_date'] = $this->input->post("end_date");

        $view['param']['psd_id'] = $this->input->post("data_id");
	    // $view['param']['psg_box_no'] = $this->input->post("box_no");



	    $param = array();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

	    // $tmp_id = $this->input->post('tmp_id');
	    // $ids = explode(',', $tmp_id);

	    // 라이브러리를 로드한다.
	    $this->load->library('excel');

	    // 시트를 지정한다.
	    $this->excel->setActiveSheetIndex(0);
	    $this->excel->getActiveSheet()->setTitle('전단정보');

        $sql = "
            select *
           from cb_pop_screen_data
            where psd_mem_id = '".$this->member->item("mem_id")."' and psd_id = '".$view['param']['psd_id']."'
        ";

        $psd_data = $this->db->query($sql)->row();

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
	        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
	        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
	    ),	'A1:D1');

	    $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	    // $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	    // $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        // $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "템플릿ID");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "전단명 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "행사일정 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "할인&대표");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "이미지형");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "텍스트형");
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "주문하기 (선택)");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "총결제해야할 금액");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "결제금액");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "미수금액");

        $row=2;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $psd_data->psd_tem_id);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $psd_data->psd_title);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $psd_data->psd_date);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $psd_data->psd_step1_yn);
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $psd_data->psd_step2_yn);
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $psd_data->psd_step3_yn);
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->psg_imgpath);

        $sql = "select count(1) as cnt from (
            select count(1) as cnt
			from cb_pop_screen_goods
            where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."'
            group by psg_box_no) t
        ";

        $boxcnt = $this->db->query($sql)->row()->cnt;

        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > boxcnt : ". $boxcnt);

        $sql = "
            select psg_box_no, psg_step
			from cb_pop_screen_goods
            where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."'
            group by psg_box_no
        ";
        $boxlist = $this->db->query($sql)->result();
        $sheetnum = 1;
        foreach($boxlist as $r) {
            $this->excel->createSheet();
            $this->excel->setActiveSheetIndex($sheetnum);
            $psg_step = "";
            switch ($r->psg_step) {
                case '1':
                    $psg_step = "1단";
                    break;

                case '2':
                    $psg_step = "2단";
                    break;

                case '3':
                    $psg_step = "텍스트";
                    break;

                case '4':
                    $psg_step = "3단";
                    break;
                case '5':
                    $psg_step = "4단";
                    break;
                case '9':
                    $psg_step = "이미지";
                    break;
            }
            if($psd_data->psd_step1_yn=="Y"&&$sheetnum==1){
                $this->excel->getActiveSheet()->setTitle("대표할인상품");
            }else{
                $this->excel->getActiveSheet()->setTitle($psg_step);
            }



            $sql = "
                select *
    			from cb_pop_screen_goods a left join cb_pop_screen_text b on a.psg_psd_id = b.pst_psd_id and a.psg_step_no = b.pst_step_no and a.psg_seq = b.pst_seq
                where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."' and psg_box_no = '".$r->psg_box_no."'
                order by  psg_seq asc
            ";

    	    $list = $this->db->query($sql)->result();



    	    // 필드명을 기록한다.
    	    // 글꼴 및 정렬
    	    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
    	        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
    	        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
    	    ),	'A1:M1');
            // $this->excel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    	    $this->excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
    	    $this->excel->getActiveSheet()->getRowDimension('4')->setRowHeight(20);

    	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    	    // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    	    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    	    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            // $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);


    	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "상품코드");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "상품명 (필수)");
    	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "규격");
    	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "정상가");
    	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "할인가(필수)");
    	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "옵션");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "이미지");
    	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
    	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "타이틀ID");
    	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "타이틀");
    	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "뱃지");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "형태");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "단락번호");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, "순서");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "재고");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "이미지 자동매칭(Y)");

    	    $cnt = 1;
    	    $row = 2;
    	    //number_format(($r->dep_datetime, 1)
    	    foreach($list as $r) {
                // $this->excel->getActiveSheet()->getStyle("A".$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
    	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->psg_code." ");//->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->psg_name);
    	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->psg_option);
    	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->psg_price);
    	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->psg_dcprice);
    	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->psg_option2);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->psg_imgpath);
    	        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->dep_deposit_datetime);
    	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->psg_tit_id);
    	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $r->pst_tit_text_info);
    	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $r->psg_badge);

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $psg_step);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_box_no);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $r->psg_seq);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_stock);
    	        $row++;
    	        $cnt++;
    	    }




            $sheetnum++;
        }



	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="'.$view['param']['psd_id'].'_smart_data.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');

	}

    //전단전체 엑셀 다운로드 탭별 테스트
    public function psd_download_tab() {
	    $view = array();


        $view['param']['psd_id'] = $this->input->post("data_id");

	    $param = array();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");


	    // 라이브러리를 로드한다.
	    $this->load->library('excel');

	    // 시트를 지정한다.
	    $this->excel->setActiveSheetIndex(0);
	    $this->excel->getActiveSheet()->setTitle('전단정보');

        $sql = "
            select *
           from cb_pop_screen_data
            where psd_mem_id = '".$this->member->item("mem_id")."' and psd_id = '".$view['param']['psd_id']."'
        ";

        $psd_data = $this->db->query($sql)->row();

        $psd_goods = json_decode($psd_data->psd_goods);

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
	        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
	        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
	    ),	'A1:D1');

	    $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	    // $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	    // $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        // $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "템플릿ID");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "전단명 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "행사일정 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "할인&대표");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "이미지형");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "텍스트형");
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "주문하기 (선택)");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "총결제해야할 금액");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "결제금액");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "미수금액");

        $row=2;
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $psd_data->psd_tem_id);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $psd_data->psd_title);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $psd_data->psd_date);
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $psd_data->psd_step1_yn);
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $psd_data->psd_step2_yn);
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $psd_data->psd_step3_yn);
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->psg_imgpath);

        // $sql = "select count(1) as cnt from (
        //     select count(1) as cnt
		// 	from cb_pop_screen_goods
        //     where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."'
        //     group by psg_box_no) t
        // ";
        //
        // $boxcnt = $this->db->query($sql)->row()->cnt;
        //
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > boxcnt : ". $boxcnt);

        // $sql = "
        //     select psg_box_no, psg_step
		// 	from cb_pop_screen_goods
        //     where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."'
        //     group by psg_box_no
        // ";
        $boxlist = $psd_goods->tdata;
        $list = $psd_goods->data;
        $plist = $psd_goods->pdata;
        $tlist = $psd_goods->tdata;
        $titlenum = 0;
        $sheetnum = 1;
        if($psd_data->psd_step1_yn=="Y"){
            // foreach($boxlist as $r) {
                $this->excel->createSheet();
                $this->excel->setActiveSheetIndex($sheetnum);

                $psg_step = "1단";

        	    $this->excel->getActiveSheet()->setTitle("대표할인상품");

                // $pst_step_no = $r->pst_step_no;
                // $sql = "
                //     select *
        		// 	from cb_pop_screen_goods a left join cb_pop_screen_text b on a.psg_psd_id = b.pst_psd_id and a.psg_step_no = b.pst_step_no and a.psg_seq = b.pst_seq
                //     where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."' and psg_box_no = '".$r->psg_box_no."'
                //     order by  psg_seq asc
                // ";

        	    // $list = $this->db->query($sql)->result();



        	    // 필드명을 기록한다.
        	    // 글꼴 및 정렬
        	    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
        	        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
        	        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        	    ),	'A1:M1');
                // $this->excel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        	    $this->excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
        	    $this->excel->getActiveSheet()->getRowDimension('4')->setRowHeight(20);

        	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        	    // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        	    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        	    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
                // $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);


        	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "상품코드");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "상품명 (필수)");
        	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "규격");
        	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "정상가");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "정상가");
        	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "할인가(필수)");
        	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "옵션1");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "옵션2");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "이미지");
        	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
        	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "타이틀ID");
        	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "타이틀");
        	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "뱃지");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "형태");
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "단락번호");
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, "순서");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "재고");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, "이미지 자동매칭(Y)");

        	    $cnt = 1;
        	    $row = 2;
        	    //number_format(($r->dep_datetime, 1)
        	    foreach($plist as $r) {

                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->psg_code." ");//->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);

                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->psg_name);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->psg_option);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->psg_price);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->psg_evprice);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->psg_dcprice);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->psg_option2);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->psg_option3);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $r->psg_imgpath);
                       // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->dep_deposit_datetime);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, '0');
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, '');
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_badge);

                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $psg_step);
                        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_box_no);
                        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $r->psg_seq);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $r->psg_stock);
                       $row++;
                       $cnt++;

                    // $this->excel->getActiveSheet()->getStyle("A".$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );

        	    }




                $sheetnum++;
            // }
        }
        // $boxlist = $this->db->query($sql)->result();

        foreach($boxlist as $r) {
            $this->excel->createSheet();
            $this->excel->setActiveSheetIndex($sheetnum);
            $pst_step = "";
            switch ($r->pst_step) {
                case '1':
                    $psg_step = "1단";
                    break;

                case '2':
                    $psg_step = "2단";
                    break;

                case '3':
                    $psg_step = "텍스트";
                    break;

                case '4':
                    $psg_step = "3단";
                    break;
                case '5':
                    $psg_step = "4단";
                    break;
                case '9':
                    $psg_step = "이미지";
                    break;
            }
    	    $this->excel->getActiveSheet()->setTitle($psg_step);

            $pst_step_no = $r->pst_step_no;
            // $sql = "
            //     select *
    		// 	from cb_pop_screen_goods a left join cb_pop_screen_text b on a.psg_psd_id = b.pst_psd_id and a.psg_step_no = b.pst_step_no and a.psg_seq = b.pst_seq
            //     where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."' and psg_box_no = '".$r->psg_box_no."'
            //     order by  psg_seq asc
            // ";

    	    // $list = $this->db->query($sql)->result();



    	    // 필드명을 기록한다.
    	    // 글꼴 및 정렬
    	    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
    	        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
    	        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
    	    ),	'A1:M1');
            // $this->excel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    	    $this->excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
    	    $this->excel->getActiveSheet()->getRowDimension('4')->setRowHeight(20);

            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
            // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
            // $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);


            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "상품코드");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "상품명 (필수)");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "규격");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "정상가");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "행사가");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "할인가(필수)");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "옵션1");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "옵션2");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "이미지");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "타이틀ID");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "타이틀");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "뱃지");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "형태");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "단락번호");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, "순서");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "재고");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, "이미지 자동매칭(Y)");

    	    $cnt = 1;
    	    $row = 2;
    	    //number_format(($r->dep_datetime, 1)
    	    foreach($list as $r) {
                if($r->psg_step_no==$pst_step_no){
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->psg_code." ");//->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->psg_name);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->psg_option);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->psg_price);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->psg_evprice);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->psg_dcprice);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->psg_option2);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->psg_option3);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $r->psg_imgpath);
                   // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->dep_deposit_datetime);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $r->psg_tit_id);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $tlist[$titlenum]->pst_tit_text_info);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_badge);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $psg_step);
                    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_box_no);
                    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $r->psg_seq);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $r->psg_stock);
                   $row++;
                   $cnt++;
                }
                if($r->psg_step_no > $pst_step_no){
                    break;
                }
                // $this->excel->getActiveSheet()->getStyle("A".$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );

    	    }




            $sheetnum++;
            $titlenum++;
        }



	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="'.$view['param']['psd_id'].'_smart_data.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');

	}


    //전단전체 엑셀 다운로드 탭별 테스트
    public function psd_download_all() {
	    $view = array();


        $view['param']['psd_id'] = $this->input->post("data_id");

	    $param = array();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");


	    // 라이브러리를 로드한다.
	    $this->load->library('excel');

	    // 시트를 지정한다.
	    $this->excel->setActiveSheetIndex(0);
	    $this->excel->getActiveSheet()->setTitle("상품리스트");

        $sql = "
            select *
           from cb_pop_screen_data
            where psd_mem_id = '".$this->member->item("mem_id")."' and psd_id = '".$view['param']['psd_id']."'
        ";

        $psd_data = $this->db->query($sql)->row();

        $psd_goods = json_decode($psd_data->psd_goods);

        // $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
	    //     'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
	    //     'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
	    // ),	'A1:D1');
        //
	    // $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    // $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        //
	    // $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	    // $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	    // // $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	    // // $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        // // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // // $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	    // // $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        // // $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        //
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "템플릿ID");
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "전단명 (필수)");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "행사일정 (필수)");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "할인&대표");
	    // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "이미지형");
	    // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "텍스트형");
        // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "주문하기 (선택)");
	    // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
	    // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "총결제해야할 금액");
	    // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "결제금액");
	    // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "미수금액");
        //
        // $row=2;
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $psd_data->psd_tem_id);
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $psd_data->psd_title);
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $psd_data->psd_date);
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $psd_data->psd_step1_yn);
        // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $psd_data->psd_step2_yn);
        // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $psd_data->psd_step3_yn);
        // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->psg_imgpath);

        // $sql = "select count(1) as cnt from (
        //     select count(1) as cnt
		// 	from cb_pop_screen_goods
        //     where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."'
        //     group by psg_box_no) t
        // ";
        //
        // $boxcnt = $this->db->query($sql)->row()->cnt;
        //
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > boxcnt : ". $boxcnt);

        // $sql = "
        //     select psg_box_no, psg_step
		// 	from cb_pop_screen_goods
        //     where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."'
        //     group by psg_box_no
        // ";
        $boxlist = $psd_goods->tdata;
        $list = $psd_goods->data;
        $plist = $psd_goods->pdata;
        $tlist = $psd_goods->tdata;
        $titlenum = 0;
        // $sheetnum = 1;


        // $this->excel->createSheet();
        // $this->excel->setActiveSheetIndex($sheetnum);





        $psg_step = "대표";

        // $pst_step_no = $r->pst_step_no;
        // $sql = "
        //     select *
        // 	from cb_pop_screen_goods a left join cb_pop_screen_text b on a.psg_psd_id = b.pst_psd_id and a.psg_step_no = b.pst_step_no and a.psg_seq = b.pst_seq
        //     where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."' and psg_box_no = '".$r->psg_box_no."'
        //     order by  psg_seq asc
        // ";

        // $list = $this->db->query($sql)->result();



        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:M1');
        // $this->excel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        $this->excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
        $this->excel->getActiveSheet()->getRowDimension('4')->setRowHeight(20);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
        // $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
        // $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        // $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);


        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "코너구분(선택)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "상품명 (필수)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "규격");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "정상가");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "정상가");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "할인가(필수)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "옵션1");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "옵션2");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "코너유형 (필수)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "이미지");
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "타이틀ID");

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "뱃지");

        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "단락번호");
        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, "순서");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "재고");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "이미지 자동매칭(Y)");

        $cnt = 1;
        $row = 2;
        if($psd_data->psd_step1_yn=="Y"){
        	    foreach($plist as $r) {

                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, " ");//->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);

                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->psg_name);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->psg_option);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->psg_price);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->psg_evprice);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->psg_dcprice);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->psg_option2);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->psg_option3);
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, (($r->psg_seq=="0")? $psg_step : ""));
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $r->psg_imgpath);
                       // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->dep_deposit_datetime);
                       // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, '0');
                       // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, '');
                       $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $r->psg_badge);


                        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_box_no);
                        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $r->psg_seq);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_stock);
                       $row++;
                       $cnt++;

        	    }

                // $sheetnum++;
            // }
        }
        // $boxlist = $this->db->query($sql)->result();

        foreach($boxlist as $r) {
            // $this->excel->createSheet();
            // $this->excel->setActiveSheetIndex($sheetnum);
            $pst_step = "";
            switch ($r->pst_step) {
                case '1':
                    $psg_step = "1단";
                    break;

                case '2':
                    $psg_step = "2단";
                    break;

                case '3':
                    $psg_step = "텍스트";
                    break;

                case '4':
                    $psg_step = "3단";
                    break;
                case '5':
                    $psg_step = "4단";
                    break;
                case '9':
                    $psg_step = "이미지";
                    break;
            }
    	    // $this->excel->getActiveSheet()->setTitle($psg_step);

            $pst_step_no = $r->pst_step_no;
            // $sql = "
            //     select *
    		// 	from cb_pop_screen_goods a left join cb_pop_screen_text b on a.psg_psd_id = b.pst_psd_id and a.psg_step_no = b.pst_step_no and a.psg_seq = b.pst_seq
            //     where psg_mem_id = '".$this->member->item("mem_id")."' and psg_psd_id = '".$view['param']['psd_id']."' and psg_box_no = '".$r->psg_box_no."'
            //     order by  psg_seq asc
            // ";

    	    // $list = $this->db->query($sql)->result();



    	    // 필드명을 기록한다.
    	    // 글꼴 및 정렬
    	    // $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
    	    //     'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
    	    //     'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
    	    // ),	'A1:M1');
            // // $this->excel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            //
    	    // $this->excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
    	    // $this->excel->getActiveSheet()->getRowDimension('4')->setRowHeight(20);
            //
            // $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            // $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            // $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            // $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            // $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            // $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
            // // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            // $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
            // $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            // $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
            // $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
            // $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
            // $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
            // $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
            // $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
            // // $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            //
            //
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "상품코드");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "상품명 (필수)");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "규격");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "정상가");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "행사가");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "할인가(필수)");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "옵션1");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "옵션2");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "이미지");
            // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "타이틀ID");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "타이틀");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "뱃지");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "형태");
            // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "단락번호");
            // // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, "순서");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "재고");
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, "이미지 자동매칭(Y)");
            //
    	    // $cnt = 1;
    	    // $row = 2;
    	    //number_format(($r->dep_datetime, 1)
    	    foreach($list as $r) {
                if($r->psg_step_no==$pst_step_no){
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, (($r->psg_seq=="0")? $tlist[$titlenum]->pst_tit_text_info : ""));//->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->psg_name);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->psg_option);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->psg_price);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->psg_evprice);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->psg_dcprice);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->psg_option2);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->psg_option3);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, (($r->psg_seq=="0")? $psg_step : ""));
                   // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, (($r->psg_seq=="0")? $r->psg_tit_id : ""));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $r->psg_imgpath);
                   // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->dep_deposit_datetime);

                   // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $tlist[$titlenum]->pst_tit_text_info);
                   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $r->psg_badge);


                    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_box_no);
                    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $r->psg_seq);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->psg_stock);

                   $row++;
                   $cnt++;
                }
                if($r->psg_step_no > $pst_step_no){
                    break;
                }
                // $this->excel->getActiveSheet()->getStyle("A".$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );

    	    }




            // $sheetnum++;
            $titlenum++;
        }



	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="'.$view['param']['psd_id'].'_smart_data.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');

	}

    //전단 엑셀 업로드
	public function excel_psd_upload_ok(){
	    $result = array();
		// $rtn_data = $this->input->post('rtn_data'); //리턴값
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file : ". $_FILES['file']['tmp_name']);
		//업로드된 엑셀 파일을 처리함
        $result["code"]="1";
	    if(file_exists($_FILES['file']['tmp_name'])) {
			$this->load->library("excel");
			$inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > inputFileType : ". $inputFileType);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly( true );
			$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
			$sheetsCount = $objPHPExcel->getSheetCount();
            $mem_id = $this->member->item("mem_id");
			// 쉬트별로 읽기
			for($i = 0; $i < $sheetsCount; $i++){
				$sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
				$sheet = $objPHPExcel->getActiveSheet();
				$highestRow = $sheet->getHighestRow();
				$highestColumn = $sheet->getHighestColumn();
				//log_message("ERROR", "Rows : ".$highestRow);
                //전단코드 생성
    			$this->load->library('base62');
    			$surl_id = $this->member->item("mem_id").cdate('YmdHis');
    			$psdcode = $this->base62->encode($surl_id);

                $rowData0 = $sheet->rangeToArray('A2:' . $highestColumn . '2', NULL, TRUE, FALSE);
                $inPsd = array();
                $inPsd["psd_tem_id"] = (empty($rowData0[0][0])) ? "" : $rowData0[0][0];
                $inPsd["psd_title"] = (empty($rowData0[0][1])) ? "" : $rowData0[0][1]."(엑셀)";
                $inPsd["psd_date"] = (empty($rowData0[0][2])) ? "" : $rowData0[0][2];
                $inPsd["psd_step1_yn"] = (empty($rowData0[0][3])) ? "" : $rowData0[0][3];
                $inPsd["psd_step2_yn"] = (empty($rowData0[0][4])) ? "" : $rowData0[0][4];
                $inPsd["psd_step3_yn"] = (empty($rowData0[0][5])) ? "" : $rowData0[0][5];
                $inPsd["psd_useyn"] = "Y";
                $inPsd["psd_code"] = $psdcode;
                $inPsd["psd_mem_id"] = $mem_id;
                $inPsd["psd_viewyn"] = "Y";
                $inPsd["psd_type"] = "S";
                $inPsd["psd_ver_no"] = "2";
                $inPsd["psd_order_yn"] = "N";
                if($this->member->item("mem_level") >= 100) {
                $inPsd["psd_mng_yn"] = "Y";
                }
                //신규 일련번호
        		$sql = "select ifnull(max(psd_id),0)+1 as psd_id from cb_pop_screen_data ";
        		$psd_id = $this->db->query($sql)->row()->psd_id;

                //스마트전단 데이타 신규 정렬순서
        	    $sql = "select ifnull(max(psd_seq),0)+1 as psd_seq from cb_pop_screen_data where psd_mem_id = '". $mem_id ."' ";
        	    $psd_seq = $this->db->query($sql)->row()->psd_seq;

                $inPsd["psd_id"] = $psd_id;
                $inPsd["psd_seq"] = $psd_seq;


                $this->db->insert("cb_pop_screen_data", $inPsd); //데이타 추가
    			// $psd_id = $this->db->insert_id();

                $psg_box_no = -1;
                $psg_seq = 0;
				// 한줄읽기
				for ($row = 4; $row <= $highestRow; $row++){ //2번줄부터 읽기

					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    if($psg_box_no!=$rowData[0][11]){
                        $psg_box_no=$rowData[0][11];
                        $psg_seq=0;
                    }
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name : ". $rowData[0][0] .", price : ". $rowData[0][1] .", dcprice : ". $rowData[0][2]);
					//$code = ""; //상품코드
					//$name = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품명
					//$option = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //옵션명
					//$price = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //정상가
					//$dcprice = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //할인가
                    $psgData = array();

					$code = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품코드
					$name = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //상품명
					$option = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //옵션명
					$price = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //정상가
                    $evprice = (empty($rowData[0][4])) ? "" : $rowData[0][4]; //할인가
					$dcprice = (empty($rowData[0][5])) ? "" : $rowData[0][5]; //할인가
					$option2 = (empty($rowData[0][6])) ? "" : $rowData[0][6]; //추가란 2021-01-22
                    $option3 = (empty($rowData[0][7])) ? "" : $rowData[0][7]; //추가란 2021-01-22
                    $ipath = (empty($rowData[0][8])) ? "" : $rowData[0][8]; //이미지 경로 2022-10-21
                    $titid = (empty($rowData[0][9])) ? "" : $rowData[0][9];
                    $tittext = (empty($rowData[0][10])) ? "" : $rowData[0][10];
                    $badge = (empty($rowData[0][11])) ? "" : $rowData[0][11];
                    $step = (empty($rowData[0][12])) ? "" : $rowData[0][12];
                    $psg_step = "";
                    switch ($step) {
                        case '1단':
                            $psg_step = "1";
                            break;

                        case '2단':
                            $psg_step = "2";
                            break;

                        case '텍스트':
                            $psg_step = "3";
                            break;

                        case '3단':
                            $psg_step = "4";
                            break;
                        case '4단':
                            $psg_step = "5";
                            break;
                        case '이미지':
                            $psg_step = "9";
                            break;
                    }
                    $stock = (empty($rowData[0][12])) ? "" : $rowData[0][12];
                    $autoImg = (empty($rowData[0][13])) ? "" : $rowData[0][13];
                    if($psg_step!="9"&&$psg_step!="3"&&empty($ipath)&&$autoImg=="Y"){
                        $sch_name = $name;
    					if($option != "") $sch_name .= " ". $option;
                        $ipath = $this->img_path($code, $sch_name, $name, $option, "");
                    }

                    $psgData["psg_psd_id"] = $psd_id;
                    $psgData["psg_mem_id"] = $mem_id;

                    $psgData["psg_code"] = trim($code);
                    $psgData["psg_name"] = $name;
                    $psgData["psg_option"] = $option;
                    $psgData["psg_price"] = $price;
                    $psgData["psg_evprice"] = $evprice;
                    $psgData["psg_dcprice"] = $dcprice;
                    $psgData["psg_option2"] = $option2;
                    $psgData["psg_option3"] = $option3;
                    $psgData["psg_imgpath"] = $ipath;
                    $psgData["psg_tit_id"] = $titid;
                    $psgData["psg_badge"] = $badge;
                    $psgData["psg_step"] = $psg_step;
                    $psgData["psg_stock"] = $stock;
                    // if($this->member->item("mem_level") >= 100) {
                    // $psgData["psg_mng_yn"] = "Y";
                    // }else{
                    // $psgData["psg_mng_yn"] = "N";
                    // }

                    $psgData["psg_mng_yn"] = "Y";
                    $psgData["psg_box_no"] = $psg_box_no;
                    $psgData["psg_step_no"] = $psg_box_no;
                    $psgData["psg_seq"] = $psg_seq;
					// $sch_name = $name;
					// if($option != "") $sch_name .= " ". $option;
					// $result[$row-4]['code'] = $code; //상품코드
					// $result[$row-4]['name'] = $name; //상품명
					// $result[$row-4]['option'] = $option; //옵션명
					// $result[$row-4]['price'] = $price; //정상가
					// $result[$row-4]['dcprice'] = $dcprice; //할인가
					// $result[$row-4]['option2'] = $option2; //추가란 2021-01-22
					// $result[$row-2]['img_path'] = $this->img_path($code, $sch_name, $name, $option, $ipath); //이미지 경로
					// $result[$row-2]['seq'] = ($row-2); //정렬순서
					// $result[$row-2]['rtn_data'] = $rtn_data; //리턴값

                    $this->db->insert("cb_pop_screen_goods", $psgData); //데이타 추가

                    $pstData = array();
                    $pstData["pst_psd_id"] = $psd_id;
                    $pstData["pst_mem_id"] = $mem_id;
                    $pstData["pst_step"] = $psg_step;
                    $pstData["pst_step_no"] = $psg_box_no;
                    $pstData["pst_box_no"] = $psg_box_no;
                    $pstData["pst_seq"] = $psg_seq;
                    $pstData["pst_tit_text_info"] = $tittext;

                    $this->db->insert("cb_pop_screen_text", $pstData); //데이타 추가

                    $psg_seq++;
				}
                $result["code"]="0";
			}

		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ". $result);
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > json : ". $json);
		echo $json;
	}

    //전단 엑셀 업로드
	public function excel_psd_upload_ok_mt(){
	    $result = array();
		// $rtn_data = $this->input->post('rtn_data'); //리턴값
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file : ". $_FILES['file']['tmp_name']);
		//업로드된 엑셀 파일을 처리함
        $result["code"]="1";
	    if(file_exists($_FILES['file']['tmp_name'])) {
			$this->load->library("excel");
			$inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > inputFileType : ". $inputFileType);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly( true );
			$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
			$sheetsCount = $objPHPExcel->getSheetCount();
            $mem_id = $this->member->item("mem_id");
            $ii = 0;
            $step1_yn = "N";
            $tem_id = "2118";
			// 쉬트별로 읽기
			for($i = 0; $i < $sheetsCount; $i++){
				$sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
				$sheet = $objPHPExcel->getActiveSheet();
				$highestRow = $sheet->getHighestRow();
				$highestColumn = $sheet->getHighestColumn();
				//log_message("ERROR", "Rows : ".$highestRow);
                if($i==0){
                    //전단코드 생성
        			$this->load->library('base62');
        			$surl_id = $this->member->item("mem_id").cdate('YmdHis');
        			$psdcode = $this->base62->encode($surl_id);

                    // $rowData0 = $sheet->rangeToArray('A2:' . $highestColumn . '2', NULL, TRUE, FALSE);
                    $inPsd = array();
                    $inPsd["psd_tem_id"] = $tem_id;
                    $inPsd["psd_title"] = "엑셀생성전단";
                    $inPsd["psd_date"] =  "행사기간";
                    $inPsd["psd_step1_yn"] = 'N';
                    $inPsd["psd_step2_yn"] = 'N';
                    $inPsd["psd_step3_yn"] = 'N';
                    $inPsd["psd_useyn"] = "Y";
                    $inPsd["psd_code"] = $psdcode;
                    $inPsd["psd_mem_id"] = $mem_id;
                    $inPsd["psd_viewyn"] = "Y";
                    $inPsd["psd_type"] = "S";
                    $inPsd["psd_ver_no"] = "2";
                    $inPsd["psd_order_yn"] = "N";
                    if($this->member->item("mem_level") >= 100) {
                    $inPsd["psd_mng_yn"] = "Y";
                    }
                    //신규 일련번호
            		$sql = "select ifnull(max(psd_id),0)+1 as psd_id from cb_pop_screen_data ";
            		$psd_id = $this->db->query($sql)->row()->psd_id;

                    //스마트전단 데이타 신규 정렬순서
            	    $sql = "select ifnull(max(psd_seq),0)+1 as psd_seq from cb_pop_screen_data where psd_mem_id = '". $mem_id ."' ";
            	    $psd_seq = $this->db->query($sql)->row()->psd_seq;

                    $inPsd["psd_id"] = $psd_id;
                    $inPsd["psd_seq"] = $psd_seq;


                    $this->db->insert("cb_pop_screen_data", $inPsd); //데이타 추가

                    // if($inPsd["psd_step1_yn"]!="Y"){
                    //     $ii++;
                    // }
        			// $psd_id = $this->db->insert_id();

                    $psg_seq = 0;
                    $prconner = "";
                    $prtitle = "";
                    $prstep = "";
                    $psg_step = "";
                    $titid = 0;


    				// 한줄읽기
    				for ($row = 2; $row <= $highestRow; $row++){ //2번줄부터 읽기

    					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

    					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name : ". $rowData[0][0] .", price : ". $rowData[0][1] .", dcprice : ". $rowData[0][2]);
    					//$code = ""; //상품코드
    					//$name = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품명
    					//$option = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //옵션명
    					//$price = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //정상가
    					//$dcprice = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //할인가

                        $psgData = array();

    					$conner = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품코드
    					$name = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //상품명
    					$option = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //옵션명
    					$price = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //정상가
                        $evprice = (empty($rowData[0][4])) ? "" : $rowData[0][4]; //할인가
    					$dcprice = (empty($rowData[0][5])) ? "" : $rowData[0][5]; //할인가
    					$option2 = (empty($rowData[0][6])) ? "" : $rowData[0][6]; //추가란 2021-01-22
                        $option3 = (empty($rowData[0][7])) ? "" : $rowData[0][7]; //추가란 2021-01-22
                        $step = (empty($rowData[0][8])) ? "" : $rowData[0][8];
                        $ipath = (empty($rowData[0][9])) ? "" : $rowData[0][9]; //이미지 경로 2022-10-21

                        $badge = (empty($rowData[0][10])) ? "" : $rowData[0][10];
                        if($row == 2||!empty($step)){
                            if($row > 2){
                                $ii++;
                            }else{
                                if($step!="대표"){
                                    $ii++;
                                }
                            }
                            if($row == 2){
                                if(!empty($rowData[0][13])){
                                    $tem_id = $rowData[0][13];
                                }
                            }
                            // $titid = (empty($rowData[0][9])) ? "" : $rowData[0][9];
                            // $tittext = (empty($rowData[0][10])) ? "" : $rowData[0][10];
                            // $step = (empty($rowData[0][12])) ? "" : $rowData[0][12];
                            $prconner = $conner;
                            $prstep = $step;

                            $psg_seq = 0;
                            $tit_flag = "I";
                            $psg_step = "";
                            switch ($step) {
                                case '대표':
                                    $psg_step = "1";
                                    $step1_yn = "Y";
                                    break;

                                case '1단':
                                    $psg_step = "1";
                                    break;

                                case '2단':
                                    $psg_step = "2";
                                    break;

                                case '텍스트':
                                    $psg_step = "3";
                                    $tit_flag = "T";
                                    break;

                                case '3단':
                                    $psg_step = "4";
                                    break;
                                case '4단':
                                    $psg_step = "5";
                                    break;
                                case '이미지':
                                    $psg_step = "9";
                                    break;
                            }


                            $titidid = 0;
                            if(!empty($conner)){
                                $sql = "select tit_id, tit_mem_id from cb_design_title where tit_useyn = 'Y' and tit_type = '".$tit_flag."' and tit_text_info='".$conner."' and (tit_mem_id = 3 or tit_mem_id = '".$mem_id."') order by tit_id desc limit 1 ";
                                $tit = $this->db->query($sql)->row();
                                $tit_search_flag = "A";
                                if(!empty($tit->tit_id)){
                                    $titid = $tit->tit_id;
                                    $titidid = $titid;
                                }else{

                                    $sql = "select tit_id, tit_mem_id from cb_design_title where tit_useyn = 'Y' and tit_type = '".$tit_flag."' and tit_text_info like '%".$conner."%' and (tit_mem_id = 3 or tit_mem_id = '".$mem_id."') order by tit_id desc limit 1 ";
                                    $tit = $this->db->query($sql)->row();
                                    $tit_search_flag = "B";
                                    if(!empty($tit->tit_id)){
                                        $titid = $tit->tit_id;
                                        $titidid = $titid;
                                    }
                                }
                                if($tit->tit_mem_id!="3"){
                                    if($tit_search_flag == "A"){
                                        $sql = "select tit_id from cb_design_title where tit_useyn = 'Y' and tit_type = '".$tit_flag."' and tit_text_info='".$conner."' and tit_mem_id = 3 order by tit_id desc limit 1 ";
                                    }else{
                                        $sql = "select tit_id from cb_design_title where tit_useyn = 'Y' and tit_type = '".$tit_flag."' and tit_text_info like '%".$conner."%' and tit_mem_id = 3 order by tit_id desc limit 1 ";
                                    }

                                    $titit = $this->db->query($sql)->row()->tit_id;
                                    if(!empty($titit)){
                                        $titidid = $titit;
                                    }
                                }
                            }
                        }
                        $stock = (empty($rowData[0][11])) ? "" : $rowData[0][11];
                        $autoImg = (empty($rowData[0][12])) ? "" : $rowData[0][12];
                        if($psg_step!="9"&&$psg_step!="3"&&empty($ipath)&&(empty($autoImg)||$autoImg!="N")){
                            $sch_name = $name;
        					if($option != "") $sch_name .= " ". $option;
                            $ipath = $this->img_path($code, $sch_name, $name, $option, "", $titidid);
                        }else{
                            if($psg_step!="9"&&$psg_step!="3"&&!empty($ipath)){
                                $ipath = (empty($rowData[0][9])) ? "" : $rowData[0][9];
                            }else{
                                $ipath="";
                            }


                        }

                        if(($psg_step!="9"&&!empty($name))||$psg_step=="9"){
                            $psgData["psg_psd_id"] = $psd_id;
                            $psgData["psg_mem_id"] = $mem_id;

                            $psgData["psg_code"] = trim($code);
                            $psgData["psg_name"] = $name;
                            $psgData["psg_option"] = $option;
                            $psgData["psg_price"] = $price;
                            $psgData["psg_evprice"] = $evprice;
                            $psgData["psg_dcprice"] = $dcprice;
                            $psgData["psg_option2"] = $option2;
                            $psgData["psg_option3"] = $option3;
                            $psgData["psg_imgpath"] = $ipath;
                            $psgData["psg_tit_id"] = $titid;
                            if($psg_step!="3"&&$psg_step>5){
                                $badge="";
                            }
                            $psgData["psg_badge"] = $badge;
                            $psgData["psg_step"] = $psg_step;
                            $psgData["psg_stock"] = $stock;
                            // if($this->member->item("mem_level") >= 100) {
                            // $psgData["psg_mng_yn"] = "Y";
                            // }else{
                            // $psgData["psg_mng_yn"] = "N";
                            // }

                            $psgData["psg_mng_yn"] = "Y";
                            $psgData["psg_box_no"] = $ii;
                            $psgData["psg_step_no"] = $ii;
                            $psgData["psg_seq"] = $psg_seq;
        					// $sch_name = $name;
        					// if($option != "") $sch_name .= " ". $option;
        					// $result[$row-4]['code'] = $code; //상품코드
        					// $result[$row-4]['name'] = $name; //상품명
        					// $result[$row-4]['option'] = $option; //옵션명
        					// $result[$row-4]['price'] = $price; //정상가
        					// $result[$row-4]['dcprice'] = $dcprice; //할인가
        					// $result[$row-4]['option2'] = $option2; //추가란 2021-01-22
        					// $result[$row-2]['img_path'] = $this->img_path($code, $sch_name, $name, $option, $ipath); //이미지 경로
        					// $result[$row-2]['seq'] = ($row-2); //정렬순서
        					// $result[$row-2]['rtn_data'] = $rtn_data; //리턴값

                            $this->db->insert("cb_pop_screen_goods", $psgData); //데이타 추가

                            if($psg_seq==0){
                                $pstData = array();
                                $pstData["pst_psd_id"] = $psd_id;
                                $pstData["pst_mem_id"] = $mem_id;
                                $pstData["pst_step"] = $psg_step;
                                $pstData["pst_step_no"] = $ii;
                                $pstData["pst_box_no"] = $ii;
                                $pstData["pst_seq"] = '0';
                                $pstData["pst_tit_text_info"] = $prconner;

                                $this->db->insert("cb_pop_screen_text", $pstData); //데이타 추가
                            }
                            $psg_seq++;
                        }


    				}
                    // $ii++;
                }

                $result["code"]="0";
			}
            if($step1_yn == "Y"&&!empty($psd_id)){
                $sql = "update cb_pop_screen_data set psd_step1_yn = 'Y' where psd_mem_id = '". $mem_id ."' and psd_id = '". $psd_id ."' ";
        	    $this->db->query($sql);
            }

            if($tem_id!="2118"&&!empty($psd_id)){
                $sql = "update cb_pop_screen_data set psd_tem_id = '".$tem_id."' where psd_mem_id = '". $mem_id ."' and psd_id = '". $psd_id ."' ";
        	    $this->db->query($sql);
            }

		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ". $result);
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > json : ". $json);
		echo $json;
	}

    //전단 엑셀 업로드
	public function excel_psd_upload_ok_tab(){
	    $result = array();
		// $rtn_data = $this->input->post('rtn_data'); //리턴값
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file : ". $_FILES['file']['tmp_name']);
		//업로드된 엑셀 파일을 처리함
        $result["code"]="1";
	    if(file_exists($_FILES['file']['tmp_name'])) {
			$this->load->library("excel");
			$inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > inputFileType : ". $inputFileType);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly( true );
			$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
			$sheetsCount = $objPHPExcel->getSheetCount();
            $mem_id = $this->member->item("mem_id");
            $ii = 0;
			// 쉬트별로 읽기
			for($i = 0; $i < $sheetsCount; $i++){
				$sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
				$sheet = $objPHPExcel->getActiveSheet();
				$highestRow = $sheet->getHighestRow();
				$highestColumn = $sheet->getHighestColumn();
				//log_message("ERROR", "Rows : ".$highestRow);
                if($i==0){
                    //전단코드 생성
        			$this->load->library('base62');
        			$surl_id = $this->member->item("mem_id").cdate('YmdHis');
        			$psdcode = $this->base62->encode($surl_id);

                    $rowData0 = $sheet->rangeToArray('A2:' . $highestColumn . '2', NULL, TRUE, FALSE);
                    $inPsd = array();
                    $inPsd["psd_tem_id"] = (empty($rowData0[0][0])) ? "" : $rowData0[0][0];
                    $inPsd["psd_title"] = (empty($rowData0[0][1])) ? "" : $rowData0[0][1]."(엑셀)";
                    $inPsd["psd_date"] = (empty($rowData0[0][2])) ? "" : $rowData0[0][2];
                    $inPsd["psd_step1_yn"] = (empty($rowData0[0][3])) ? "" : $rowData0[0][3];
                    $inPsd["psd_step2_yn"] = 'N';
                    $inPsd["psd_step3_yn"] = 'N';
                    $inPsd["psd_useyn"] = "Y";
                    $inPsd["psd_code"] = $psdcode;
                    $inPsd["psd_mem_id"] = $mem_id;
                    $inPsd["psd_viewyn"] = "Y";
                    $inPsd["psd_type"] = "S";
                    $inPsd["psd_ver_no"] = "2";
                    $inPsd["psd_order_yn"] = "N";
                    if($this->member->item("mem_level") >= 100) {
                    $inPsd["psd_mng_yn"] = "Y";
                    }
                    //신규 일련번호
            		$sql = "select ifnull(max(psd_id),0)+1 as psd_id from cb_pop_screen_data ";
            		$psd_id = $this->db->query($sql)->row()->psd_id;

                    //스마트전단 데이타 신규 정렬순서
            	    $sql = "select ifnull(max(psd_seq),0)+1 as psd_seq from cb_pop_screen_data where psd_mem_id = '". $mem_id ."' ";
            	    $psd_seq = $this->db->query($sql)->row()->psd_seq;

                    $inPsd["psd_id"] = $psd_id;
                    $inPsd["psd_seq"] = $psd_seq;


                    $this->db->insert("cb_pop_screen_data", $inPsd); //데이타 추가

                    if($inPsd["psd_step1_yn"]!="Y"){
                        $ii++;
                    }
        			// $psd_id = $this->db->insert_id();
                }else{
                    $psg_seq = 0;
    				// 한줄읽기
    				for ($row = 2; $row <= $highestRow; $row++){ //2번줄부터 읽기

    					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

    					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > name : ". $rowData[0][0] .", price : ". $rowData[0][1] .", dcprice : ". $rowData[0][2]);
    					//$code = ""; //상품코드
    					//$name = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품명
    					//$option = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //옵션명
    					//$price = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //정상가
    					//$dcprice = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //할인가

                        $psgData = array();

    					$code = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품코드
    					$name = (empty($rowData[0][1])) ? "" : $rowData[0][1]; //상품명
    					$option = (empty($rowData[0][2])) ? "" : $rowData[0][2]; //옵션명
    					$price = (empty($rowData[0][3])) ? "" : $rowData[0][3]; //정상가
                        $evprice = (empty($rowData[0][4])) ? "" : $rowData[0][4]; //할인가
    					$dcprice = (empty($rowData[0][5])) ? "" : $rowData[0][5]; //할인가
    					$option2 = (empty($rowData[0][6])) ? "" : $rowData[0][6]; //추가란 2021-01-22
                        $option3 = (empty($rowData[0][7])) ? "" : $rowData[0][7]; //추가란 2021-01-22
                        $ipath = (empty($rowData[0][8])) ? "" : $rowData[0][8]; //이미지 경로 2022-10-21
                        $badge = (empty($rowData[0][11])) ? "" : $rowData[0][11];
                        if($row == 2){
                            $titid = (empty($rowData[0][9])) ? "" : $rowData[0][9];
                            $tittext = (empty($rowData[0][10])) ? "" : $rowData[0][10];
                            $step = (empty($rowData[0][12])) ? "" : $rowData[0][12];

                            $psg_step = "";
                            $psg_step = "";
                            switch ($step) {
                                case '1단':
                                    $psg_step = "1";
                                    break;

                                case '2단':
                                    $psg_step = "2";
                                    break;

                                case '텍스트':
                                    $psg_step = "3";
                                    break;

                                case '3단':
                                    $psg_step = "4";
                                    break;
                                case '4단':
                                    $psg_step = "5";
                                    break;
                                case '이미지':
                                    $psg_step = "9";
                                    break;
                            }
                        }
                        $stock = (empty($rowData[0][13])) ? "" : $rowData[0][13];
                        $autoImg = (empty($rowData[0][14])) ? "" : $rowData[0][14];
                        if($psg_step!="9"&&$psg_step!="3"&&empty($ipath)&&$autoImg=="Y"){
                            $sch_name = $name;
        					if($option != "") $sch_name .= " ". $option;
                            $ipath = $this->img_path($code, $sch_name, $name, $option, "", $titid);
                        }

                        $psgData["psg_psd_id"] = $psd_id;
                        $psgData["psg_mem_id"] = $mem_id;

                        $psgData["psg_code"] = trim($code);
                        $psgData["psg_name"] = $name;
                        $psgData["psg_option"] = $option;
                        $psgData["psg_price"] = $price;
                        $psgData["psg_evprice"] = $evprice;
                        $psgData["psg_dcprice"] = $dcprice;
                        $psgData["psg_option2"] = $option2;
                        $psgData["psg_option3"] = $option3;
                        $psgData["psg_imgpath"] = $ipath;
                        $psgData["psg_tit_id"] = $titid;
                        if($psg_step!="3"&&$psg_step<5){
                            $badge="";
                        }
                        $psgData["psg_badge"] = $badge;
                        $psgData["psg_step"] = $psg_step;
                        $psgData["psg_stock"] = $stock;
                        // if($this->member->item("mem_level") >= 100) {
                        // $psgData["psg_mng_yn"] = "Y";
                        // }else{
                        // $psgData["psg_mng_yn"] = "N";
                        // }

                        $psgData["psg_mng_yn"] = "Y";
                        $psgData["psg_box_no"] = $ii;
                        $psgData["psg_step_no"] = $ii;
                        $psgData["psg_seq"] = $psg_seq;
    					// $sch_name = $name;
    					// if($option != "") $sch_name .= " ". $option;
    					// $result[$row-4]['code'] = $code; //상품코드
    					// $result[$row-4]['name'] = $name; //상품명
    					// $result[$row-4]['option'] = $option; //옵션명
    					// $result[$row-4]['price'] = $price; //정상가
    					// $result[$row-4]['dcprice'] = $dcprice; //할인가
    					// $result[$row-4]['option2'] = $option2; //추가란 2021-01-22
    					// $result[$row-2]['img_path'] = $this->img_path($code, $sch_name, $name, $option, $ipath); //이미지 경로
    					// $result[$row-2]['seq'] = ($row-2); //정렬순서
    					// $result[$row-2]['rtn_data'] = $rtn_data; //리턴값

                        $this->db->insert("cb_pop_screen_goods", $psgData); //데이타 추가

                        if($psg_seq==0){
                            $pstData = array();
                            $pstData["pst_psd_id"] = $psd_id;
                            $pstData["pst_mem_id"] = $mem_id;
                            $pstData["pst_step"] = $psg_step;
                            $pstData["pst_step_no"] = $ii;
                            $pstData["pst_box_no"] = $ii;
                            $pstData["pst_seq"] = '0';
                            $pstData["pst_tit_text_info"] = $tittext;

                            $this->db->insert("cb_pop_screen_text", $pstData); //데이타 추가
                        }
                        $psg_seq++;
    				}
                    $ii++;
                }

                $result["code"]="0";
			}

		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ". $result);
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > json : ". $json);
		echo $json;
	}

    //이미지 업로드 함수
	public function thumb_upload(){


        $data = array();
		$data_id = $this->input->post('id'); //일련번호
        $image = $this->input->post('img_file'); //이미지
        // $theDate = date("YmdHis");
        // $name = $this->member->item("mem_id").$theDate;
        // $sql = "SELECT emd_img_url FROM cb_evemaker_data WHERE emd_id = '".$data_id."'";
        // $ori_url = $this->db->query($sql)->row()->emd_img_url;
        // if(!empty($ori_url)){
        //     // $orgpath = $_SERVER["DOCUMENT_ROOT"] ."/". $this->title_img_path . $img_data['file_name']; //오지니널 이미지 경로
        //     $orgpath = $_SERVER["DOCUMENT_ROOT"] . $ori_url; //오지니널 이미지 경로
        //     log_message("ERROR", $_SERVER['REQUEST_URI'] ." > orgpath : ". $orgpath. ",  thumb_img_path".$this->upload_path);
        //     unlink($orgpath); //오지니널 이미지 삭제
        // }

        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $decoded = base64_decode($image);
        file_put_contents($this->upload_path . "/eve_" . $data_id . ".jpg", $decoded, FILE_APPEND);
        $data['emd_img_url'] = '/'.$this->upload_path. "eve_" . $data_id .".jpg";

		log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id);




		$where = array();
		$where['emd_id'] = $data_id; //일련번호
		$rtn = $this->db->update("cb_evemaker_data", $data, $where); //데이타 수정


		$result = array();
		$result['code'] = '0';
		$result['data_id'] = $data_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}


    //개인정보동의 목록
	public function lists()
    {
		$view = array();
        $view['view'] = array();
		// $view['add'] = $this->input->get("add");
		// if($view['add'] == "") $view['add'] = $this->input->post("add");
		$skin = "send_lists";
		// $add = $view['add'];
		// if($add != "") $skin .= $add;

		$view['perpage'] = ($this->input->get("perpage")) ? $this->input->get("perpage") : 20;
        $view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;

        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $where = "1=1 ";
        $where .= "and esi_mem_id = '". $this->member->item("mem_id") ."' "; //회원번호
        $where .= "and esi_send_cnt != '0' ";

        //$view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id", $where, $param);
		$sql = "
		SELECT count(1) as cnt
		from cb_evemaker_send_info a
		inner join cb_member b on a.esi_mem_id=b.mem_id
		left join cb_evemaker_send_data d on a.esi_idx=d.esd_esi_idx
		where ".$where." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;
		//echo "total_rows : ". $view['total_rows'] ."<br>";

        $sql = "
		select a.*, d.*, b.mem_username, e.emd_title, e.emd_sub_info, e.emd_img_url
			, (select count(1) from cb_evemaker_send_data esd where esd.esd_esi_idx = a.esi_idx and esd.esd_state in ('0')) AS esd0cnt /* 메시지확인수 */
            , (select count(1) from cb_evemaker_send_data esd where esd.esd_esi_idx = a.esi_idx and esd.esd_state in ('1')) AS esd1cnt /* 메시지확인수 */
            , (select count(1) from cb_evemaker_send_data esd where esd.esd_esi_idx = a.esi_idx and esd.esd_state in ('2')) AS esd2cnt /* 메시지확인수 */
			, (select count(1) from cb_evemaker_send_data esd where esd.esd_esi_idx = a.esi_idx and esd.esd_state in ('3')) AS esd3cnt /* 메시지확인수 */
        from cb_evemaker_send_info a
    	inner join cb_member b on a.esi_mem_id=b.mem_id
    	left join cb_evemaker_send_data d on a.esi_idx=d.esd_esi_idx
        left join cb_evemaker_data e on a.esi_emd_code=e.emd_code
		where ".$where."
		order by esi_idx desc
		limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
        $view['list'] = $this->db->query($sql)->result();

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);
		$view['page_html'] = $view['page_html'];
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view[page_html] : ".$view['page_html']);

		//레이아웃을 정의합니다
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'spop/evemaker',
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

	//개인정보동의 상세내용
	public function view($id = 0)
    {
		$view = array();
        $view['view'] = array();
		// $view['add'] = $this->input->get("add");
		// if($view['add'] == "") $view['add'] = $this->input->post("add");
		$skin = "send_view";
		$add = $view['add'];
		if($add != "") $skin .= $add;
        $view['eveid'] = $id;
		$view['perpage'] = ($this->input->get("perpage")) ? $this->input->get("perpage") : 20; //개시물수
		$view['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1; //현재페이지
		$view['sendid'] = $id; //개인정보동의번호
		$view['search_phn'] = $this->input->get("search_phn"); //연락처 검색
		$view['search_state'] = $this->input->get("search_state"); //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색

		//검색조건
		$where = "WHERE esd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND esd_esi_idx = '". $view['sendid'] ."' /* 개인정보동의번호 */ ";
		if($view['search_state'] != ""){
			$where .= "AND esd_state = '". $view['search_state'] ."' /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */ ";
		}
		if($view['search_phn'] != ""){
			//$search_phn = preg_replace("/[^0-9]/", "", $view['search_phn']); //숫자 이외 제거
			$search_phn = str_replace("-", "", $view['search_phn']); //- 제거
			$where .= "AND esd_phn LIKE '%". $search_phn ."%' /* 휴대전화번호 */ ";
		}

		//전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_evemaker_send_data a
		". $where ." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//리스트
		$sql = "
		SELECT
			  esd_idx /* 일련번호 */
			, esd_phn /* 연락처 */
			, esd_state /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */
			, DATE_FORMAT(esd_cre_date, '%Y-%m-%d %H:%i') AS esd_cre_date /* 발송일자 */
			, DATE_FORMAT(esd_view_date, '%Y-%m-%d %H:%i') AS esd_view_date /* 확인일자 */
			, DATE_FORMAT(esd_eve_date, '%Y-%m-%d %H:%i') AS esd_eve_date /* 수신동의일자 */
            , DATE_FORMAT(esd_goods_date, '%Y-%m-%d %H:%i') AS esd_goods_date /* 수신동의일자 */
            , esd_emg_id
            , esd_emg_name
            , esd_code
		FROM cb_evemaker_send_data a
		". $where ."
		ORDER BY esd_idx DESC
		LIMIT ".(($view['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['list'] = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view : ".$view);

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);
		$view['page_html'] = $view['page_html'];
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view[page_html] : ".$view['page_html']);

		/**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'spop/evemaker',
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

}
?>
