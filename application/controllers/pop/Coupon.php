<?php
class Coupon extends CB_Controller {
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
	private $upload_path; //업로드 경로
	private $upload_max_size; //파일 제한 사이지

	function __construct(){
		parent::__construct();

		/**
		* 라이브러리를 로딩합니다
		*/
		$this->load->library(array('querystring', 'funn'));

		/* 이미지 등록될 기본 디렉토리 설정 */
		$this->upload_path =  config_item('uploads_dir') .'/pop_coupon/'; //스마트쿠폰 업로드 경로

		/* 업로드할 용량지정 byte 단위 */
		$this->upload_max_size = 5 * 1024 * 1024; //파일 제한 사이지(byte 단위) (5MB)

		/* 기본 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->upload_path);

		/* 년 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->upload_path . date("Y") ."/");

		/* 월 폴더가 없다면 생성을 위해 실행 */
		$this->funn->create_upload_dir($this->upload_path . date("Y") ."/". date("m") ."/");

		$this->upload_path = $this->upload_path . date("Y") ."/". date("m") ."/";
		//echo "upload_path : ". $this->upload_path;
	}

	//스마트쿠폰 > 스마트쿠폰 목록
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

		$where = "WHERE pcd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND pcd_useyn = 'Y' ";

		//스마트쿠폰 전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_pop_coupon_data a
		". $where ." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//스마트쿠폰 리스트
		$sql = "
		SELECT
			  pcd_id /* 쿠폰번호 */
			, pcd_code /* 코드 */
			, pcd_type /* 쿠폰타입(1.무료증정, 2.가격할인) */
			, pcd_title /* 제목 */
			, pcd_date /* 행사기간 */
			, DATE_FORMAT(pcd_cre_date, '%Y.%m') AS pcd_cre_ym /* 등록년월 */
			, DATE_FORMAT(pcd_cre_date, '%d') AS pcd_cre_dd /* 등록일 */
		FROM cb_pop_coupon_data a
		". $where ."
		ORDER BY pcd_id DESC
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
			'path' => 'pop/coupon',
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
	
	//스마트쿠폰 > 스마트쿠폰 만들기
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
		$view['upload_path'] = $this->upload_path; //업로드 경로

		$pcd_id = $this->input->get("pcd_id"); //쿠폰번호
		//echo "dep_id : ". $dep_id ."<br>";
		
		//스마트쿠폰 데이타
		$sql = "
		SELECT
			a. *
		FROM cb_pop_coupon_data a
		WHERE pcd_mem_id = '". $this->member->item("mem_id") ."' 
		AND pcd_id = '". $pcd_id ."'
		AND pcd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['data'] = $this->db->query($sql)->row();

		$layoutconfig = array(
			'path' => 'pop/coupon',
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

	//스마트쿠폰 저장
	public function coupon_save(){
		$imgfile = $this->input->post("imgfile"); //HTML to Image
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > imgfile : ". $imgfile);
		$viewpath = "/". $this->upload_path . "file". date("YmdHis") .".png";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > viewpath : ". $viewpath);
		list($type, $imgfile) = explode(';', $imgfile);
		list(, $imgfile) = explode(',', $imgfile);
		$imgfile = base64_decode($imgfile);
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . $viewpath, $imgfile);

		$flag = $this->input->post("flag"); //구분
		$data_id = $this->input->post("data_id"); //일련번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id);
		$data = array();
		$data["pcd_type"] = $this->input->post("pcd_type"); //쿠폰타입(1.무료증정, 2.가격할인)
		$data["pcd_date"] = $this->input->post("pcd_date"); //행사기간
		$data["pcd_title"] = $this->input->post("pcd_title"); //쿠폰제목
		$data["pcd_option1"] = $this->input->post("pcd_option1"); //상품정보/쿠폰옵션1
		$data["pcd_option2"] = $this->input->post("pcd_option2"); //쿠폰옵션/쿠폰옵션2
		$data["pcd_price"] = $this->input->post("pcd_price"); //할인금액
		$data["pcd_imgpath"] = $this->input->post("pcd_imgpath"); //이미지경로
		$data["pcd_viewpath"] = $viewpath; //미리보기경로
		$data["pcd_date_size"] = $this->input->post("pcd_date_size"); //행사기간 폰트크기
		$data["pcd_title_size"] = $this->input->post("pcd_title_size"); //쿠폰제목 폰트크기
		$data["pcd_option1_size"] = $this->input->post("pcd_option1_size"); //상품정보/쿠폰옵션1 폰트크기
		$data["pcd_option2_size"] = $this->input->post("pcd_option2_size"); //쿠폰옵션/쿠폰옵션2 폰트크기
		$data["pcd_price_size"] = $this->input->post("pcd_price_size"); //할인금액 폰트크기
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id .", pcd_tem_id = ". $data["pcd_tem_id"] .", pcd_title = ". $data["pcd_title"] .", pcd_date = ". $data["pcd_date"] .", pcd_step1_yn = ". $data["pcd_step1_yn"] .", pcd_step2_yn = ". $data["pcd_step2_yn"] .", pcd_step3_yn = ". $data["pcd_step3_yn"]);
		if(!empty($data_id)) { //수정의 경우
			$where = array();
			$where["pcd_id"] = $data_id; //일련번호
			$where["pcd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$rtn = $this->db->update("cb_pop_coupon_data", $data, $where); //데이타 수정
			if($rtn){ //성공
				$code = "0";
				$msg = "OK";
			}else{ //오류
				$code = "1";
				$msg = "데이타 처리중 오류가 발생하였습니다.";
			}

			//코드 조회
			$sql = "select pcd_code from cb_pop_coupon_data where pcd_id = '". $data_id ."' ";
			$pcd_code = $this->db->query($sql)->row()->pcd_code;
		}else{ //등록
			//쿠폰코드 생성
			$this->load->library('base62');
			$surl_id = cdate('YmdHis');
			$pcd_code = $this->base62->encode($surl_id);
			
			//스마트쿠폰 데이타 추가
			$data["pcd_mem_id"] = $this->member->item("mem_id"); //회원번호
			$data["pcd_code"] = $pcd_code; //쿠폰코드
			$rtn = $this->db->replace("cb_pop_coupon_data", $data); //데이타 추가
			if($rtn){ //성공
				$data_id = $this->db->insert_id();
				$code = "0";
				$msg = "OK";
			}else{ //오류
				$code = "1";
				$msg = "데이타 처리중 오류가 발생하였습니다.";
			}
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id .", rtn = ". $rtn);

		$result = array();
		$result['code'] = $code;
		$result['msg'] = $msg;
		$result['id'] = $data_id;
		$result['pcd_code'] = $pcd_code;
		$result['flag'] = $flag;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트쿠폰 복사
	public function coupon_copy(){
		$data_id = $this->input->post("data_id"); //일련번호
		$mem_id = $this->member->item("mem_id"); //회원번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id);

		//쿠폰코드 생성
		$this->load->library('base62');
		$surl_id = cdate('YmdHis');
		$pcd_code = $this->base62->encode($surl_id);

		//스마트쿠폰 데이타 복사
		$sql = "
		INSERT INTO cb_pop_coupon_data (
			  pcd_mem_id /* 회원번호 */
			, pcd_code /* 쿠폰코드 */
			, pcd_type /* 쿠폰타입(1.무료증정, 2.가격할인) */
			, pcd_date /* 행사기간 */
			, pcd_title /* 쿠폰제목 */
			, pcd_option1 /* 상품정보/쿠폰옵션1 */
			, pcd_option2 /* 쿠폰옵션/쿠폰옵션2 */
			, pcd_price /* 할인금액 */
			, pcd_imgpath /* 이미지경로 */
			, pcd_viewpath /* 미리보기경로 */
			, pcd_date_size /* 행사기간 폰트크기 */
			, pcd_title_size /* 쿠폰제목 폰트크기 */
			, pcd_option1_size /* 상품정보/쿠폰옵션1 폰트크기 */
			, pcd_option2_size /* 쿠폰옵션/쿠폰옵션2 폰트크기 */
			, pcd_price_size /* 할인금액 폰트크기 */
			, pcd_useyn /* 사용여부 (Y/N) */
		)
		SELECT
			  ". $mem_id ." AS pcd_mem_id /* 회원번호 */
			, '". $pcd_code ."' AS pcd_code /* 쿠폰코드 */
			, pcd_type /* 쿠폰타입(1.무료증정, 2.가격할인) */
			, pcd_date /* 행사기간 */
			, CONCAT(pcd_title, ' (복사)') AS pcd_title /* 쿠폰제목 */
			, pcd_option1 /* 상품정보/쿠폰옵션1 */
			, pcd_option2 /* 쿠폰옵션/쿠폰옵션2 */
			, pcd_price /* 할인금액 */
			, pcd_imgpath /* 이미지경로 */
			, pcd_viewpath /* 미리보기경로 */
			, pcd_date_size /* 행사기간 폰트크기 */
			, pcd_title_size /* 쿠폰제목 폰트크기 */
			, pcd_option1_size /* 상품정보/쿠폰옵션1 폰트크기 */
			, pcd_option2_size /* 쿠폰옵션/쿠폰옵션2 폰트크기 */
			, pcd_price_size /* 할인금액 폰트크기 */
			, pcd_useyn /* 사용여부 (Y/N) */
		FROM cb_pop_coupon_data
		WHERE pcd_id = '". $data_id ."' ";
		$rtn = $this->db->query($sql);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트쿠폰 데이타 복사 ". $sql);
		if($rtn){ //성공
			$new_id = $this->db->insert_id();
			$code = "0";
			$msg = "OK";
		}else{ //오류
			$code = "1";
			$msg = "데이타 처리중 오류가 발생하였습니다.";
		}

		$result = array();
		$result['code'] = $code;
		$result['msg'] = $msg;
		$result['new_id'] = $new_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트쿠폰 삭제
	public function coupon_del(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$data_id = $this->input->post("data_id"); //쿠폰번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .",data_id : ". $data_id);

		//스마트쿠폰 데이타 삭제
		$sql = "delete from cb_pop_coupon_data where pcd_mem_id = '". $mem_id ."' and pcd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트쿠폰 데이타 삭제 : ". $sql);
		$this->db->query($sql);

		$result = array();
		$result['code'] = '0';
		$result['msg'] = $data_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트쿠폰 사용안함 처리
	public function coupon_remove(){
		$mem_id = $this->member->item("mem_id"); //회원번호
		$data_id = $this->input->post("data_id"); //쿠폰번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ". $mem_id .",data_id : ". $data_id);
		
		//스마트쿠폰 사용안함 처리
		$sql = "update cb_pop_coupon_data set pcd_useyn='N' where pcd_mem_id = '". $mem_id ."' and pcd_id = '". $data_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트쿠폰 사용안함 처리 : ". $sql);
		$this->db->query($sql);

		$result = array();
		$result['code'] = '0';
		$result['msg'] = $data_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트쿠폰 데이타 조회
	public function coupon_data(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트쿠폰 데이타 조회");
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		$result = array();
		$view = array();
		$view['view'] = array();
		$view['perpage'] = ($this->input->post("per_page")) ? $this->input->post("per_page") : 8;
		$view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;

		//검색조건
		$where = "WHERE pcd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND pcd_useyn = 'Y' ";

		//스마트쿠폰 전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_pop_coupon_data a
		". $where ." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//스마트쿠폰 리스트
		$sql = "
		SELECT
			  pcd_id /* 쿠폰번호 */
			, pcd_code /* 쿠폰코드 */
			, pcd_type /* 쿠폰타입(1.무료증정, 2.가격할인) */
			, pcd_viewpath /* 미리보기경로 */
			, pcd_title /* 쿠폰제목 */
			, pcd_date /* 행사기간 */
			, DATE_FORMAT(pcd_cre_date, '%y.%m.%d') AS pcd_credt /* 등록일자 */
		FROM cb_pop_coupon_data a
		". $where ."
		ORDER BY pcd_id DESC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$result = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'coupon_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='coupon_page($1)'><a herf='#' ",$view['page_html']);
		$result['page_html'] = $view['page_html'];
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result[page_html] : ".$result['page_html']);

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		$json = str_replace('null', '""', $json);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트쿠폰 링크 URL 만들기
	public function coupon_dhnlurl(){
		$this->load->library('dhnlurl');
		$code = $this->input->post("code");
		$result = array();
		$result['dhnlurl'] = 'http://'. $this->dhnlurl->get_short('http://'. $_SERVER['HTTP_HOST'] ."/smart/coupon/". $code);
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}
	
}
?>