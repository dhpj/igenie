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
	    
	    $layoutconfig = array(
			'path' => 'pop/printer',
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
	
	//스마트POP 만들기
	public function type($type){
	    if($this->member->is_member() == false) { //로그인이 안된 경우
	        redirect('login');
	    }

		$view = array();
		$view['type'] = $type;
		$view['upload_path'] = $this->upload_path; //스마트POP 업로드 경로
		$view['upload_max_size'] = $this->upload_max_size; //파일 제한 사이지

		$mem_id = $this->member->item("mem_id"); //회원번호
		$ppd_id = $this->input->get("ppd_id"); //POP번호

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
	    
	    $layoutconfig = array(
	        'path' => 'pop/printer',
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
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > data_id : ". $data_id .", updata : ". $updata);
		$data = array();
		$data["ppd_type"] = $this->input->post("data_type"); //타입
		$data["ppd_title"] = $this->input->post("goods_tit"); //타이틀
		$data["ppd_imgpath"] = $imgpath; //HTML to Image
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
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ppg_seq : ". $ppg_seq .", ppg_name = ". $goods["ppg_name"] .", ppg_price = ". $goods["ppg_price"] .", ppg_imgpath = ". $goods["ppg_imgpath"]);
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

	//이미지 저장
	public function imgfile_save(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > _FILES[imgfile] : ". $_FILES['imgfile']['name']);
		$imgpath = "";
		$uppath = $this->input->post("uppath"); //업로드경로
		if($uppath == "") $uppath = $this->upload_path;
		if(empty($_FILES['imgfile']['name']) == false){ //이미지 업로드
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_max_size : ". $this->upload_max_size);
			$img_data = $this->img_upload($uppath, 'imgfile', $this->upload_max_size, "");
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_data : ". $img_data);
            if( is_array($img_data) && !empty($img_data) ){
				$imgpath = '/'. $uppath . $img_data['file_name']; //이미지 경로
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
					}
				}
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filedata : ". $filedata);
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
	
}
?>