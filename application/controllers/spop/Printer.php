<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Group class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Printer extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'dhtml_editor');

	/*
     * 파일 디렉토리 설정 및 사이즈 조절
     */
    private $upload_path; //스마트POP 업로드 경로
    private $upload_max_size; //파일 제한 사이지

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring', 'funn'));

		/* 이미지 등록될 기본 디렉토리 설정 */
        $this->upload_path =  config_item('uploads_dir') .'/pop_printer/'; //스마트POP 업로드 경로

        /* 업로드할 용량지정 byte 단위 */
        $this->upload_max_size = 5 * 1024 * 1024; //파일 제한 사이지(byte 단위) (5MB)

        /* 폴더가 없다면 생성을 위해 실행 */
        $this->funn->create_upload_dir($this->upload_path);

		/* 년 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->upload_path . date("Y") ."/");

		/* 월 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->upload_path . date("Y") ."/". date("m") ."/");

		$this->upload_path = $this->upload_path . date("Y") ."/". date("m") ."/";

		//echo "PHP_VERSION_ID : ". PHP_VERSION_ID ."<br>";
		//echo "CI_VERSION : ". CI_VERSION ."<br>";
		//print_r($_COOKIE);
		//echo "_COOKIE['ci_session'] : ". $_COOKIE['ci_session'] ."<br>";

		/*
		header('Set-Cookie: same-site-cookie=foo; SameSite=Lax');
		//header('Set-Cookie: cross-site-cookie=bar; SameSite=None; Secure');
		header('Set-Cookie: cross-site-cookie=bar; SameSite=None;');
		setcookie('ci_session', $_COOKIE['ci_session'], 0, '/; samesite=strict');
		setcookie('csrf_cookie_name', $_COOKIE['csrf_cookie_name'], 0, '/; samesite=strict');
		setcookie('user_ip', $_COOKIE['user_ip'], 0, '/; samesite=strict');
		echo "_COOKIE['ci_session'] : ". $_COOKIE['ci_session'] ."<br>";
		echo "_COOKIE['csrf_cookie_name'] : ". $_COOKIE['csrf_cookie_name'] ."<br>";
		echo "_COOKIE['user_ip'] : ". $_COOKIE['user_ip'] ."<br>";
		*/

		//header('Set-Cookie: same-site-cookie=foo; SameSite=Lax');
		//header('Set-Cookie: cross-site-cookie=bar; SameSite=None');
		//setcookie('ci_session', '1', 0, '/; samesite=strict');

		//echo "ci_session : ". $_COOKIE['ci_session'] ."<br>";
		//header('Set-Cookie: same-site-cookie=foo; SameSite=Lax');
		//header('Set-Cookie: cross-site-cookie=bar; SameSite=None');
		//setcookie('ci_session', $_COOKIE['ci_session'], 0, '/; samesite=strict');
		//setcookie('csrf_cookie_name', $_COOKIE['csrf_cookie_name'], 0, '/; samesite=strict');

		//header('Set-Cookie: same-site-cookie=foo; SameSite=Lax');
		//header('Set-Cookie: cross-site-cookie=bar; SameSite=None; Secure');
		//setcookie('samesite-test', '1', 0, '/; samesite=strict');

	}

	//스마트POP 메인
	public function index(){
	    if($this->member->is_member() == false) { //로그인이 안된 경우
	        redirect('login');
	    }
	    $view = array();
	    $view['title'] = $this->db->query("select * from cb_spop_title where mem_id ='".$this->member->item('mem_id')."'")->row();
		$view['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
		$view['type'] = ($this->input->get("type")) ? $this->input->get("type") : '';
		$view['spop'] = ($this->input->get("spop")) ? $this->input->get("spop") : '';
		$view['upload_max_size'] = $this->upload_max_size; //파일 제한 사이즈

		$sql = "SELECT * FROM cb_pop_printer_logo WHERE ppl_mem_id = ".$this->member->item("mem_id")." LIMIT 1";
		$view['logos'] = $this->db->query($sql)->row();

		$sql = "SELECT * FROM cb_pop_printer_fav WHERE ppf_mem_id = ".$this->member->item("mem_id");
		$view['fav_list'] = $this->db->query($sql)->result();

        $sql = "SELECT count(*) as cnt FROM cb_pop_printer_title WHERE mem_id = ".$this->member->item("mem_id");
		$view['title_cnt'] = $this->db->query($sql)->row()->cnt;

        $sql = "SELECT type, title FROM cb_pop_printer_title WHERE mem_id = ".$this->member->item("mem_id");
		$view['title_list'] = $this->db->query($sql)->result();


	    $layoutconfig = array(
			'path' => 'spop/printer',
			'layout' => 'layout',
			'skin' => 'main',
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

	//스마트POP 테스트
	public function type_test(){
	    if($this->member->is_member() == false) { //로그인이 안된 경우
	        redirect('login');
	    }
	    $view = array();
	    $view['title'] = $this->db->query("select * from cb_spop_title where mem_id ='".$this->member->item('mem_id')."'")->row();
	    $layoutconfig = array(
			'path' => 'spop/printer',
			'layout' => 'layout',
			'skin' => 'type_test',
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

	//스마트POP 만들기
	public function type($type){
	    if($this->member->is_member() == false) { //로그인이 안된 경우
	        redirect('login');
	    }
		$view = array();
		$view['type'] = $type;
		$view['upload_path'] = $this->upload_path; //스마트POP 업로드 경로
		$view['upload_max_size'] = $this->upload_max_size; //파일 제한 사이지
		$view['data'] = null;
		$view['goods'] = array();
		$view['title'] = $this->db->query("select * from cb_spop_title where mem_id ='".$this->member->item('mem_id')."'")->row();
		$mem_id = $this->member->item("mem_id"); //회원번호
		$ppd_id = $this->input->get("ppd_id"); //POP번호
		$tmp_imgpath = addslashes(trim($this->input->post("tmp_imgpath"))); //이미지경로
		$tmp_name = addslashes(trim($this->input->post("tmp_name"))); //상품명
        $tmp_option = addslashes(trim($this->input->post("tmp_option"))); //옵션명
        $tmp_org_price = addslashes(trim($this->input->post("tmp_org_price"))); //정상가
		$tmp_price = addslashes(trim($this->input->post("tmp_price"))); //할인가
		$view['style'] = ($this->input->get("style")) ? $this->input->get("style") : '';
		$view['spop'] = ($this->input->get("spop")) ? $this->input->get("spop") : '';
        $view['blankdata']='N';

        if(!empty($view['spop'])||!empty($ppd_id)){
            $view['blankdata']='Y';
        }
		$view['mem_name'] = $this->member->item("mem_username"); //업체명
		//echo "tmp_name : ". $tmp_name .", tmp_price : ". $tmp_price .", tmp_imgpath : ". $tmp_imgpath ."<br>";
		if($ppd_id != ""){ //수정의 경우
			//스마트POP 기준정보
			$sql = "
			SELECT *
			FROM cb_pop_printer_data
			WHERE ppd_mem_id = '". $mem_id ."' /* 회원번호 */
			AND ppd_id = '". $ppd_id ."' /* POP번호 */ ";
			//echo $_SERVER['REQUEST_URI'] ." > sql : ". $sql ."<br>";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$view['data'] = $this->db->query($sql)->row();

			//스마트POP 상품정보 목록
			$sql = "
			SELECT *
			FROM cb_pop_printer_goods
			WHERE ppg_mem_id = '". $mem_id ."' /* 회원번호 */
			AND ppg_ppd_id = '". $ppd_id ."' /* POP번호 */
			ORDER BY ppg_id ASC ";
			//echo $_SERVER['REQUEST_URI'] ." > sql : ". $sql ."<br>";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$view['goods'] = $this->db->query($sql)->result();
			$sql = "
			SELECT count(*) as cnt
			FROM cb_pop_printer_goods
			WHERE ppg_mem_id = '". $mem_id ."' /* 회원번호 */
			AND ppg_ppd_id = '". $ppd_id ."' /* POP번호 */ ";
			//echo $_SERVER['REQUEST_URI'] ." > sql : ". $sql ."<br>";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$view['goods_cnt'] = $this->db->query($sql)->row()->cnt;

			// $view['goods_cnt'] = '';
		}else if($tmp_name != ""){ //스마트전단 > POP 인쇄의 경우
			$tmp_imgpath_exp = explode("§§", $tmp_imgpath);
			$tmp_name_exp = explode("§§", $tmp_name);
            $tmp_option_exp = explode("§§", $tmp_option);
            $tmp_org_price_exp = explode("§§", $tmp_org_price);
			$tmp_price_exp = explode("§§", $tmp_price);
			$view['goods_cnt'] = count($tmp_name_exp);
			if(!empty($tmp_name_exp)){
				for($ii = 0; $ii < count($tmp_name_exp); $ii++){
					if($ii > 0) $sql .= "	  UNION ALL ";
					$sql .= "SELECT ";
					$sql .= "	  '". addslashes(trim($tmp_imgpath_exp[$ii])) ."' AS ppg_imgpath /* 이미지경로 */ ";
					$sql .= "	, '". addslashes(trim($tmp_name_exp[$ii])) ."' AS ppg_name /* 상품명 */ ";
                    $sql .= "	, '". addslashes(trim($tmp_option_exp[$ii])) ."' AS ppg_option /* 옵션명 */ ";
                    $sql .= "	, '". addslashes(trim($tmp_org_price_exp[$ii])) ."' AS ppg_org_price /* 정상가 */ ";
					$sql .= "	, '". addslashes(trim($tmp_price_exp[$ii])) ."' AS ppg_price /* 할인가 */ ";
				}
				//echo $_SERVER['REQUEST_URI'] ." > sql : ". $sql ."<br>";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
				$view['goods'] = $this->db->query($sql)->result();
			}
		}

		$sql = "SELECT * FROM cb_pop_printer_logo WHERE ppl_mem_id = '".$this->member->item("mem_id")."' LIMIT 1";
		$view['mylogo'] = $this->db->query($sql)->row();

        $sql = "SELECT type, title FROM cb_pop_printer_title WHERE mem_id = ".$this->member->item("mem_id");
        $view['title_list'] = $this->db->query($sql)->result();

	    $layoutconfig = array(
	        'path' => 'spop/printer',
	        'layout' => 'layout',
	        'skin' => 'type'. $type,
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

	//스마트POP 저장
	public function data_save(){
		$data_id = $this->input->post("data_id"); //일련번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id);
		$data = array();
		$goods = array();
		if(empty($_FILES['imgfile']['name']) == false){
            $img_data = $this->img_upload($this->upload_path,'imgfile');
            if( is_array($img_data) && !empty($img_data) ){
				$goods["ppg_imgpath"] = '/'.$this->upload_path.$img_data['file_name']; //이미지 경로
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ppg_imgpath : ". $goods["ppg_imgpath"]);
			}
		}
		$data["ppd_type"] = $this->input->post("data_type"); //타입
		$data["ppd_title"] = $this->input->post("goods_tit"); //타이틀
		if(!empty($this->input->post("ppd_useyn"))) {
			$data["ppd_useyn"] = $this->input->post("ppd_useyn"); //사용여부
		}
		$goods["ppg_name"] = $this->input->post("goods_name"); //상품명
		$goods["ppg_price"] = $this->input->post("goods_price"); //가격
		if(!empty($data_id)) { //수정의 경우
			$where = array();
			$where["ppd_id"] = $data_id; //POP번호
			$where["ppd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$rtn = $this->db->update("cb_pop_printer_data", $data, $where); //스마트POP 데이타 수정

			$where = array();
			$where["ppg_ppd_id"] = $data_id; //POP번호
			$where["ppg_mem_id"] = $this->member->item("mem_id"); //회원번호
			$rtn = $this->db->update("cb_pop_printer_goods", $goods, $where); //스마트POP 상품 수정
		}else{ //등록
			//스마트POP 데이타 신규 POP번호
			$sql = "select ifnull(max(ppd_id),0)+1 as ppd_id from cb_pop_printer_data ";
			$ppd_id = $this->db->query($sql)->row()->ppd_id;

			//스마트POP 데이타 신규 정렬순서
			$sql = "select ifnull(max(ppd_seq),0)+1 as ppd_seq from cb_pop_printer_data where ppd_mem_id = '". $this->member->item("mem_id") ."' and ppd_type = '". $data["ppd_type"] ."' ";
			$ppd_seq = $this->db->query($sql)->row()->ppd_seq;

			//스마트POP 데이타 추가
			$data["ppd_id"] = $ppd_id; //POP번호
			$data["ppd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$data["ppd_seq"] = $ppd_seq; //정렬순서
			$rtn = $this->db->replace("cb_pop_printer_data", $data); //스마트POP 데이타 추가

			//스마트POP 상품 신규 정렬순서
			$ppg_seq = 1;

			//스마트POP 상품 추가
			$goods["ppg_ppd_id"] = $ppd_id; //POP번호
			$goods["ppg_mem_id"] = $this->member->item("mem_id"); //회원번호
			$goods["ppg_seq"] = $ppg_seq; //정렬순서
			$rtn = $this->db->replace("cb_pop_printer_goods", $goods); //스마트POP 상품 추가
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ppd_id : ". $ppd_id .", rtn = ". $rtn);

		$result = array();
		$result['code'] = '0';
		$result['msg'] = $ppd_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//로고 저장
	public function logo_save(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
				redirect('login');
		}
		$mem_id  = $this->member->item("mem_id");
		$sql = "SELECT COUNT(*) AS cnt FROM cb_pop_printer_logo WHERE ppl_mem_id = '".$mem_id."'";
		$mem_cnt = $this->db->query($sql)->row()->cnt;
		$hdn_use_img_yn = $this->input->post("hdn_use_img_yn"); //로고삭제변수
		log_message("ERROR", "logo_save > mem_cnt : ". $mem_cnt .", mem_id = ". $mem_id);
		$data = array();
		$data["ppl_mem_id"] = $mem_id; //회원번호
		$data["ppl_name"] = $this->input->post("com_name"); //업체명
		if(!empty($this->input->post("imgpath"))){

				$data["ppl_imgpath"] = $this->input->post("imgpath"); //이미지경로

		}else{
			if($hdn_use_img_yn=='N'){
				$data["ppl_imgpath"] = '';
			}
		}





		$data["ppl_useyn"] = $this->input->post("use_yn"); //사용여부

		$result = array();

		if($mem_cnt>0){ // 수정
			$where = array();
			$where["ppl_mem_id"] = $mem_id; //POP번호
			$this->db->update("cb_pop_printer_logo", $data, $where);
			$result['code'] = '0';
			$result['msg'] = '수정되었습니다';
		}else{ // 등록
			$this->db->insert("cb_pop_printer_logo", $data);
			$result['code'] = '0';
			$result['msg'] = '등록되었습니다';
		}

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//즐겨찾기 추가 / 삭제
	public function fav_on_off(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
				redirect('login');
		}
		$mem_id  = $this->member->item("mem_id");
		$favid  = $this->input->post("favid");
		$sql = "SELECT COUNT(*) AS cnt FROM cb_pop_printer_fav WHERE ppf_mem_id = '".$mem_id."' AND ppf_type = '".$favid."'";
		$fav_cnt = $this->db->query($sql)->row()->cnt;
		$result = array();
		if($fav_cnt > 0){ //삭제
			$sql = "SELECT ppf_id FROM cb_pop_printer_fav WHERE ppf_mem_id = '".$mem_id."' AND ppf_type = '".$favid."'";
			$ppf_id = $this->db->query($sql)->row()->ppf_id;
            log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ppf_id : ". $ppf_id );
			$sql = "DELETE FROM cb_pop_printer_fav WHERE ppf_id = '".$ppf_id."'";
			$this->db->query($sql);
			$result['code'] = '1';
			$result['msg'] = '즐겨찾기 삭제되었습니다';
		}else{ //등록
			$data = array();
			$data["ppf_mem_id"] = $mem_id; //회원번호
			$data["ppf_type"] = $favid; //타입아이디
			$this->db->insert("cb_pop_printer_fav", $data);
			$result['code'] = '0';
			$result['msg'] = '즐겨찾기 추가되었습니다';
		}

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트POP 배열 저장
	public function data_save_arr(){
		$data_id = $this->input->post("data_id"); //일련번호
		$imgfile = $this->input->post("imgfile"); //HTML to Image
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > imgfile : ". $imgfile);
		$imgpath = "/". $this->upload_path . "file". date("YmdHis") .".png";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > imgpath : ". $imgpath);
		list($type, $imgfile) = explode(';', $imgfile);
		list(, $imgfile) = explode(',', $imgfile);
		$imgfile = base64_decode($imgfile);
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . $imgpath, $imgfile);

		$uiStr = $this->input->post("updata"); //상품정보
		$updata = json_decode( $uiStr );
		$fontsize = $this->input->post("fontsize"); //상품정보
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id .", updata : ". $updata);
		$data = array();
		$data["ppd_type"] = $this->input->post("data_type"); //타입
		$data["ppd_title"] = $this->input->post("goods_tit"); //타이틀
		$data["ppd_imgpath"] = $imgpath; //HTML to Image
		$data["ppd_date"] = $this->input->post("goods_date"); //행사기간
		$data["ppd_font_size"] = $fontsize; //폰트크기
		$data["ppd_style"] =  $this->input->post("style"); //css
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id .", ppd_title : ". $data["ppd_title"] .", ppd_imgpath : ". $data["ppd_imgpath"] .", ppd_date : ". $data["ppd_date"]);
		if(!empty($this->input->post("ppd_useyn"))) {
			$data["ppd_useyn"] = $this->input->post("ppd_useyn"); //사용여부
		}
		if($data_id != "") { //수정의 경우
			$where = array();
			$where["ppd_id"] = $data_id; //POP번호
			$where["ppd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$rtn = $this->db->update("cb_pop_printer_data", $data, $where); //스마트POP 데이타 수정

			//기존 스마트POP 상품 삭제
			$where = array();
			$where["ppg_ppd_id"] = $data_id; //POP번호
			$where["ppg_mem_id"] = $this->member->item("mem_id"); //회원번호
			$this->db->delete("cb_pop_printer_goods", $where); //기존 스마트POP 상품 삭제
		}else{ //등록
			//스마트POP 데이타 신규 POP번호
			$sql = "select ifnull(max(ppd_id),0)+1 as ppd_id from cb_pop_printer_data ";
			$data_id = $this->db->query($sql)->row()->ppd_id;

			//스마트POP 데이타 신규 정렬순서
			$sql = "select ifnull(max(ppd_seq),0)+1 as ppd_seq from cb_pop_printer_data where ppd_mem_id = '". $this->member->item("mem_id") ."' and ppd_type = '". $data["ppd_type"] ."' ";
			$ppd_seq = $this->db->query($sql)->row()->ppd_seq;

			//스마트POP 데이타 추가
			$data["ppd_id"] = $data_id; //POP번호
			$data["ppd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$data["ppd_seq"] = $ppd_seq; //정렬순서
			$rtn = $this->db->insert("cb_pop_printer_data", $data); //스마트POP 데이타 추가
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id .", rtn = ". $rtn);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ppd_type : ". $data["ppd_type"] .", ppd_title = ". $data["ppd_title"]);

		//스마트POP 상품 추가
		$ppg_seq = 1; //스마트POP 상품 신규 정렬순서
		foreach($updata as $r) {
			//스마트POP 상품 추가
			$goods = array();
			$goods["ppg_mem_id"] = $this->member->item("mem_id"); //회원번호
			$goods["ppg_ppd_id"] = $data_id; //POP번호
			$goods["ppg_imgpath"] = $r->imgpath; //이미지경로
			$goods["ppg_name"] = $r->goods_name; //상품명
			$goods["ppg_option"] = $r->goods_option; //옵션명
			$goods["ppg_org_price"] = $r->goods_org_price; //정상가
			$goods["ppg_price"] = $r->goods_price; //가격
			$goods["ppg_seq"] = $ppg_seq; //정렬순서
			$goods["ppg_fontsize"] = $r->fontsize; //글자크기
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ppg_seq : ". $ppg_seq .", ppg_name = ". $goods["ppg_name"] .", ppg_price = ". $goods["ppg_price"] .", ppg_imgpath = ". $goods["ppg_imgpath"]);
            // log_message('error', $r->fontsize);
			$rtn = $this->db->insert("cb_pop_printer_goods", $goods); //스마트POP 상품 추가
			$ppg_seq++;
		}
		$result = array();
		$result['code'] = '0';
		$result['data_id'] = $data_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트POP 삭제
	public function data_del(){
		$data_id = $this->input->post("data_id"); //일련번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id);

		//스마트POP 데이타 삭제
		$sql = "delete from cb_pop_printer_data where ppd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트POP 데이타 삭제 : ". $sql);
		$this->db->query($sql);

		//스마트POP 상품 삭제
		$sql = "delete from cb_pop_printer_goods where ppg_ppd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트POP 상품 삭제 삭제 : ". $sql);
		$this->db->query($sql);

		$result = array();
		$result['code'] = '0';
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트POP 타이틀 저장
	public function title_save(){
        if($this->member->is_member() == false) { //로그인이 안된 경우
	        redirect('login');
	    }
	    $mem_id = $this->member->item('mem_id');
        $title_type = $this->input->post('type');
        $title_content = $this->input->post('title');

        $ex_title_type = explode("§§", $title_type);
        $ex_title_content = explode("§§", $title_content);

        $result = array();

        if(!empty($ex_title_content)){
            $sql = "SELECT count(*) as cnt FROM cb_pop_printer_title WHERE mem_id = '".$mem_id."'";
            $precnt = $this->db->query($sql)->row()->cnt;

            if($precnt>0){
                $sql = "DELETE FROM cb_pop_printer_title WHERE mem_id = '".$mem_id."'";
                $this->db->query($sql);
            }

            for($ii = 0; $ii < count($ex_title_content); $ii++){
                $title = array();
                $title['mem_id']=$mem_id;
                $title['type']=$ex_title_type[$ii];
                $title['title']=$ex_title_content[$ii];
                $this->db->insert("cb_pop_printer_title", $title);
            }
            //echo $_SERVER['REQUEST_URI'] ." > sql : ". $sql ."<br>";
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
            $result['code'] = '0';
            $result['msg'] = '저장 되었습니다';
        }else{
            $result['code'] = '1';
            $result['msg'] = '저장에 오류가 있습니다';
        }

	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

	//이미지 저장
	public function imgfile_save(){
		$imgpath = "";
		$uppath = $this->input->post("uppath"); //업로드경로
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > _FILES[imgfile] : ". $_FILES['imgfile']['name']);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > uppath : ". $uppath);
        $this->load->library('image_api');
		if($uppath == "") $uppath = $this->upload_path;
		if(empty($_FILES['imgfile']['name']) == false){ //이미지 업로드
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_max_size : ". $this->upload_max_size);
			$img_data = $this->img_upload($uppath, 'imgfile', $this->upload_max_size, "");
			// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_data : ". $img_data);
			if( is_array($img_data) && !empty($img_data) ){
				$imgpath = '/'. $uppath . $img_data['file_name']; //이미지 경로
				// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > imgpath : ". $imgpath);
                $this->image_api->api_image($imgpath);
			}
		}
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > imgpath : ". $imgpath);
		$result = array();
		$result['code'] = '0';
		$result['imgpath'] = $imgpath;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//이미지 업로드 함수
	public function img_upload($upload_img_path = null, $field_name = null, $size = null, $thumb = "" ){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Start");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_name : ". $_FILES[$field_name]['tmp_name']);
		if( is_dir($upload_img_path)  == false ){
			//alert('해당 디렉도리가 존재 하지 않음'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 해당 디렉도리가 존재 하지 않음");
		}
		if ( is_null($field_name) ){
			//alert('필드값을 기입하지 않았습니다.'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 필드값을 기입하지 않았습니다.");
		}
		if( is_null($size) || is_numeric($size) == false || $size == ''){
			$size = 5 * 1024 * 1024; //파일 제한 사이지(byte 단위) (5MB)
		}
		if(is_array($_FILES) && empty($_FILES[$field_name]['name']) == false){
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filename : ". $_FILES[$field_name]['name']);
			$img_filesize = filesize($_FILES[$field_name]['tmp_name']); //이미지 사이즈
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > max_size : ". $size);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filesize : ". $img_filesize);
			if( $size < $img_filesize or $img_filesize == ""){
				//alert('이미지 최대 업로드 사이즈는 '. ($size/(1024*1024)) .'MB 입니다.'); exit();
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 최대 업로드 사이즈는 ". ($size/(1024*1024)) ."MB 입니다.");
				$msg = "이미지 최대 업로드 사이즈는 ". $this->funn->fnFileSize($size, 0) ." 입니다.";
				if($img_filesize != ""){
					$msg .= "\n\n현재 파일 사이즈는 ". $this->funn->fnFileSize($img_filesize, 0) ." 입니다.";
				}
				$result = array();
				$result['code'] = '9';
				$result['msg'] = $msg;
				$json = json_encode($result,JSON_UNESCAPED_UNICODE);
				header('Content-Type: application/json');
				echo $json;
				exit();
			}
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_img_config [S] ---------- ");
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
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_img_config [E] ---------- ");
			if ($this->upload->do_upload($field_name)) {
				//업로드 성공
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 업로드 성공");
				$filedata = $this->upload->data();
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filedata : ". $filedata .", thumb : ". $thumb);
				if($thumb != ""){ //썸네일 추가
					$this->load->library('image_lib');
					$this->image_lib->clear();
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
					}else{
                        $str_path_thumb = str_replace("/var/www/igenie", "", $simgconfig['new_image']);
                        $this->image_api->api_image($str_path_thumb);
                    }
				}
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filedata : ". $filedata);
                $str_path = str_replace("/var/www/igenie", "", $filedata['full_path']);
                $this->image_api->api_image($str_path);
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

	//스마트POP 라이브러리 데이타 조회
	public function search_library(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Start");
		$result = array();
		$mem_id = $this->member->item("mem_id"); //회원번호
		$result['perpage'] = ($this->input->post("perpage")) ? $this->input->post("perpage") : 8; //개시물수
		$result['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1; //현재페이지

		$sch = "";
		$sch .= "AND ppd_mem_id = '". $mem_id ."' /* 회원번호 */ ";
		$sch .= "AND ppd_useyn = 'Y' /* 사용여부 (Y/N) */ ";

		//전체수
		$sql = "
		SELECT
			COUNT(*) AS cnt
		FROM cb_pop_printer_data
		WHERE 1=1 ". $sch ." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		$result['total_rows'] = $this->db->query($sql)->row()->cnt;

		//목록
		$sql = "
		SELECT
			  ppd_id /* POP번호 */
			, ppd_type /* 타입 */
			, ppd_style /* css */
			, ppd_title /* POP제목 */
			, ppd_imgpath /* 본문 이미지경로 */
		FROM cb_pop_printer_data
		WHERE 1=1 ". $sch ."
		ORDER BY ppd_id DESC
		LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		$result['list'] = $this->db->query($sql)->result();

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'printer_library_data';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $result['total_rows'];
		$page_cfg['per_page'] = $result['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($result['page']);
		$result['page_html'] = $this->pagination->create_links();
		$result['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$result['page_html']);

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 > 스마트POP 레이아웃 선택 페이지
	public function spop_layout(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$view = array();


		$layoutconfig = array(
			'path' => 'spop/printer',
			'layout' => 'layout',
			'skin' => 'spop_layout',
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

	//스마트전단 > 스마트POP
	public function spop(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$view = array();
		$skin = "spop";
        // $in_ip = $_SERVER['REMOTE_ADDR'];
		// if($in_ip=="61.75.230.209"){
		// 	$skin = "spop_test";
		// }
		$view['upload_max_size'] = $this->upload_max_size; //파일 제한 사이지
		$view['upload_path'] = $this->upload_path; //스마트전단 업로드 경로
		$view['template_self_path'] = $this->template_self_path; //템플릿 직접입력 업로드 경로
		$view['titimg_self_path'] = $this->titimg_self_path; //타이틀 직접입력 업로드 경로
		$psd_id = $this->input->get("spop"); //전단번호
		$view['type'] = $this->input->get("type"); //pop유형
		$view['style'] = ($this->input->get("style")) ? $this->input->get("style") : '';
		$view['spop'] = ($this->input->get("spop")) ? $this->input->get("spop") : '';

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
		FROM cb_pop_screen_goods a
		WHERE psg_mem_id = '". $this->member->item("mem_id") ."' /* 회원번호 */
		and psg_psd_id = '". $psd_id ."' /* 전단번호 */
		and psg_step = '1' /* 스텝 */ and psg_box_no = '0'
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
			, (SELECT COUNT(*) FROM cb_pop_screen_goods b WHERE b.psg_psd_id = a.psg_psd_id AND b.psg_step = a.psg_step AND b.psg_step_no = a.psg_step_no) AS rownum /* 코너별 등록수 */
			, IFNULL((SELECT tit_text_info FROM cb_design_title dt WHERE dt.tit_id = a.psg_tit_id LIMIT 1),'코너명 미지정') AS tit_text_info /* 타이틀직접입력 */
			, (SELECT pst_tit_text_info FROM cb_pop_screen_text b WHERE  a.psg_psd_id =b.pst_psd_id  AND a.psg_step_no= b.pst_step_no LIMIT 1) AS pst_tit_text_info /*바로가기텍스트정보*/
		FROM cb_pop_screen_goods a
		WHERE psg_mem_id = '". $this->member->item("mem_id") ."' /* 회원번호 */
		AND psg_psd_id = '". $psd_id ."' /* 전단번호 */
		AND psg_box_no > 0  and psg_step != 9 /* 스텝 */
		ORDER BY ". $sort ." ";
		//echo $sql ."<br>";
		$view['screen_box'] = $this->db->query($sql)->result();
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);




		$layoutconfig = array(
			'path' => 'spop/printer',
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

    public function print_log()
    {
        if($this->member->item('mem_level') < 100) { //로그인이 안된 경우
			redirect('home');
		}

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();

        $view['perpage'] = 30;
        $view['param']['page'] = (!empty($this->input->get("page")))? $this->input->get("page"): 1;

        if(!empty($this->input->get("search_type"))){
            if($this->input->get("search_type")=="1"){ //업체명
                $view['search_type'] = 1;
            }
        }else{
            $view['param']['search_type'] = ""; //검색조건
        }

        $view['param']['search_for'] = $this->input->get("search_for"); //검색내용
        if(!empty($this->input->get("search_for"))){
            $view["search_for"] = $this->input->get("search_for");
        }

        $sd = date('Y-m-d 00:00:00', strtotime('-3 months'));
        $ed = date('Y-m-d 23:59:59');
        $where = " where 1=1 and a.sl_create_datetime >= '" . $sd . "' and a.sl_create_datetime <= '" . $ed . "'";

        if($view["search_for"] && $view['search_type']){
            if( $view['search_type'] == "1"){
                $where_key = " b.mem_username ";
            }
            $where .= "and ".$where_key." like '%".$view['search_for']."%' ";
        }

        $sql = "
            SELECT
                *
            FROM
                cb_spop_log a
            LEFT JOIN
                cb_member b ON a.mem_id = b.mem_id
            ".$where."
            ORDER BY
                a.sl_create_datetime DESC
            LIMIT
                ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['lists'] = $this->db->query($sql)->result();
        // log_message("error", $sql);
        $sql = "
            SELECT
                COUNT(1) AS cnt
            FROM
                cb_spop_log a
            LEFT JOIN
                cb_member b ON a.mem_id = b.mem_id
            ".$where;

        $view['total_rows'] = $this->db->query($sql)->row()->cnt;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'spop/printer',
            'layout' => 'layout',
            'skin' => 'log_list',
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

    public function insert_print_log(){
        $data = array();
        $data['mem_id'] = $this->member->item('mem_id');
        $data['sl_type'] = (int)$this->input->post("type");
        $data['sl_spop_type'] = $this->input->post("spop_type");
        if (!empty($this->input->post("style")) && $this->input->post("style") != ""){
            $data['sl_spop_style'] = $this->input->post("style");
        }
        $this->db->insert("cb_spop_log", $data);
    }

}
?>
