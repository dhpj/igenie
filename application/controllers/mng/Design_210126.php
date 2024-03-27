<?php
class Design extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	//protected $models = array('Board', 'Biz');

	/**
	* 헬퍼를 로딩합니다
	*/
	protected $helpers = array('form', 'array');

	function __construct(){
		parent::__construct();

		/**
		* 라이브러리를 로딩합니다
		*/
		$this->load->library(array('querystring', 'funn'));
	}

	//디자인관리 > 템플릿관리
	public function templet(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$view = array();
		$view['view'] = array();
		$view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 15; //리스트수
		$view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1; //현재페이지
		$view['tagid'] = ($this->input->get("tagid")) ? $this->input->get("tagid") : "all"; //태그번호
		$where = "WHERE dt.tem_useyn = 'Y' ";
		if($view['tagid'] != "all"){
			$where .= "AND dt.tem_tag_id = '". $view['tagid'] ."' ";
		}
		//echo $where ."<br>";

		$sql = "select * from cb_design_templet_tag order by tag_seq asc ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 디자인관리 > 템플릿분류 > 데이타 조회 : ". $sql);
		$view['templet_tag'] = $this->db->query($sql)->result();

		//디자인 템플릿 전체 수
		$sql = "SELECT count(1) as cnt
		FROM cb_design_templet dt
		". $where;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//디자인 템플릿 조회
		$sql = "
		SELECT dt.*
		FROM cb_design_templet dt
		". $where ."
		ORDER BY dt.tem_seq DESC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
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
			'path' => 'mng/design',
			'layout' => 'layout',
			'skin' => 'templet',
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

	//디자인관리 > 템플릿관리 저장
	public function templet_save(){
		$tem_id = $this->input->post("tem_id"); //템플릿번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tem_id : ". $tem_id);
		$data = array();
		if (isset($_FILES) && isset($_FILES['imgfile']) && isset($_FILES['imgfile']['name'])) { //이미지 업로드
			$this->load->library('upload');
			$upload_path = config_item('uploads_dir') .'/design_templet/';
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
			$uploadconfig['allowed_types'] = 'gif|jpg|png';
			$uploadconfig['max_size'] = 1024 * 1024;
			$uploadconfig['encrypt_name'] = true;
			$this->upload->initialize($uploadconfig);
			$_FILES['userfile']['name'] = $_FILES['imgfile']['name'];
			$_FILES['userfile']['type'] = $_FILES['imgfile']['type'];
			$_FILES['userfile']['tmp_name'] = $_FILES['imgfile']['tmp_name'];
			$_FILES['userfile']['error'] = $_FILES['imgfile']['error'];
			$_FILES['userfile']['size'] = $_FILES['imgfile']['size'];
			if ($this->upload->do_upload()) {
				$this->load->library('image_lib');
				$this->image_lib->clear();
				$imgconfig = array();
				$filedata = $this->upload->data();
				$imgconfig['image_library'] = 'gd2';
				$imgconfig['source_image']	= $filedata['full_path'];
				//$imgconfig['create_thumb'] = TRUE;
				$imgconfig['maintain_ratio'] = TRUE;
				$imgconfig['new_image'] = $filedata['full_path'];
				$this->image_lib->initialize($imgconfig);
				if($this->image_lib->resize()) {
					$data["tem_imgpath"] = str_replace($_SERVER["DOCUMENT_ROOT"],'',$imgconfig['new_image']); //이미지경로
				} else {
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Thumb 이미지 저장 실패 : ". $this->upload->display_errors());
				}
			} else {
				$file_error = $this->upload->display_errors();
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 Upload 실패 : ".$this->upload->display_errors().'( '. $upload_path.' )');
			}
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 Upload OK");
		}
		$data["tem_mem_id"] = $this->member->item("mem_id"); //회원번호
		$data["tem_tag_id"] = $this->input->post("tem_tag_id"); //태그번호
		$data["tem_bgcolor"] = $this->input->post("tem_bgcolor"); //배경색상
		if(!empty($this->input->post("tem_useyn"))) {
			$data["tem_useyn"] = $this->input->post("tem_useyn") ; //사용여부
		}
		if(!empty($tem_id)) { //수정의 경우
			$where = array();
			$where["tem_id"] = $tem_id; //템플릿번호
			$rtn = $this->db->update("cb_design_templet", $data, $where);
		}else{ //등록
			$sql = "select ifnull(max(tem_id),0)+1 as tem_id from cb_design_templet ";
			$tem_id = $this->db->query($sql)->row()->tem_id;
			$data["tem_id"] = $tem_id; //템플릿번호
			$data["tem_seq"] = $tem_id; //정렬순서
			$rtn = $this->db->replace("cb_design_templet", $data);
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tem_id : ". $tem_id .", rtn = ". $rtn);
		$result = array();
		$result['code'] = '0';
		$result['msg'] = $tem_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 템플릿관리 삭제
	public function templet_del(){
		$tem_id = $this->input->post("tem_id"); //템플릿번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tem_id : ". $tem_id);
		$sql = "delete from cb_design_templet where tem_id = '". $tem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 디자인관리 > 템플릿관리 삭제 : ". $sql);
		$this->db->query($sql);
		$result = array();
		$result['code'] = '0';
		$result['msg'] = $tem_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 템플릿관리 > 데이타 조회
	public function templet_data(){
		$tem_id = $this->input->post("tem_id"); //템플릿번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tem_id : ". $tem_id);
		$sql = "select * from cb_design_templet where tem_id = '". $tem_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 디자인관리 > 템플릿관리 > 데이타 조회 : ". $sql);
		$result = $this->db->query($sql)->result();
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		$json = str_replace('null', '""', $json);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 템플릿분류 저장
	public function templet_tag_save(){
		$tag_id = $this->input->post("tag_id"); //템플릿번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tag_id : ". $tag_id);
		$data = array();
		$data["tag_mem_id"] = $this->member->item("mem_id"); //회원번호
		$data["tag_name"] = $this->input->post("tag_name"); //태그
		if(!empty($tag_id)) { //수정의 경우
			$where = array();
			$where["tag_id"] = $tag_id; //템플릿번호
			$rtn = $this->db->update("cb_design_templet_tag", $data, $where);
		}else{ //등록
			$sql = "select ifnull(max(tag_id),0)+1 as tag_id from cb_design_templet_tag ";
			$tag_id = $this->db->query($sql)->row()->tag_id;
			$data["tag_id"] = $tag_id; //템플릿번호
			$data["tag_seq"] = $tag_id; //정렬순서
			$rtn = $this->db->replace("cb_design_templet_tag", $data);
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tag_id : ". $tag_id .", rtn = ". $rtn);
		$result = array();
		$result['tag_id'] = $tag_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 템플릿분류 수정
	public function templet_tag_mod(){
		$tag_id = $this->input->post("tag_id"); //템플릿번호
		$data = array();
		$data["tag_name"] = $this->input->post("tag_name"); //태그
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tag_id : ". $tag_id);
		$where = array();
		$where["tag_id"] = $tag_id; //템플릿번호
		$rtn = $this->db->update("cb_design_templet_tag", $data, $where);
		$result = array();
		$result['code'] = '0';
		$result['msg'] = $tag_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 템플릿분류 삭제
	public function templet_tag_del(){
		$tag_id = $this->input->post("tag_id"); //템플릿번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tag_id : ". $tag_id);
		$sql = "delete from cb_design_templet_tag where tag_id = '". $tag_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 디자인관리 > 템플릿분류 삭제 : ". $sql);
		$this->db->query($sql);
		$result = array();
		$result['code'] = '0';
		$result['msg'] = $tag_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 템플릿분류 > 데이타 조회
	public function templet_tag_data(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 템플릿분류 > 데이타 조회");
		$sql = "select * from cb_design_templet_tag order by tag_seq asc ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 디자인관리 > 템플릿분류 > 데이타 조회 : ". $sql);
		$result = $this->db->query($sql)->result();
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		$json = str_replace('null', '""', $json);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 템플릿분류 > 순서변경
	public function templet_tag_sort(){
		$seq = $this->input->post("seq"); //순번
		for($row = 0; $row < count($seq); $row ++) {
			$sql = "update cb_design_templet_tag set tag_seq='" .$row ."' where tag_id = '". $seq[$row] ."' ";
			$this->db->query($sql);
		}
	}

	//디자인관리 > 템플릿분류 > 중복체크
	public function templet_tag_chk(){
		$tag_name = $this->input->post("tag_name"); //태그
		$sql = "select count(*) as cnt from cb_design_templet_tag where tag_name = '". addslashes($tag_name) ."' ";
		$cnt = $this->db->query($sql)->row()->cnt;
		$result = array();
		$result['cnt'] = $cnt;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 타이틀관리
	public function title(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		$view = array();
		$view['view'] = array();

		//디자인 타이틀 이미지형 조회
		$sql = "SELECT *
		FROM cb_design_title
		WHERE tit_type = 'I' /* 구분(I.이미지형, T.텍스트형) */
		AND tit_useyn = 'Y' /* 사용여부 (Y/N) */
		ORDER BY tit_seq ASC ";
		//echo $sql ."<br>";
		$view['data_img_list'] = $this->db->query($sql)->result();

		//디자인 타이틀 텍스트형 조회
		$sql = "SELECT *
		FROM cb_design_title
		WHERE tit_type = 'T' /* 구분(I.이미지형, T.텍스트형) */
		AND tit_useyn = 'Y' /* 사용여부 (Y/N) */
		ORDER BY tit_seq ASC ";
		//echo $sql ."<br>";
		$view['data_text_list'] = $this->db->query($sql)->result();

		$layoutconfig = array(
			'path' => 'mng/design',
			'layout' => 'layout',
			'skin' => 'title',
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
	
	//디자인관리 > 타이틀관리 저장
	public function title_save(){
		$tit_id = $this->input->post("tit_id"); //타이틀번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tit_id : ". $tit_id);
		$data = array();
		if (isset($_FILES) && isset($_FILES['imgfile']) && isset($_FILES['imgfile']['name'])) { //이미지 업로드
			$this->load->library('upload');
			$upload_path = config_item('uploads_dir') .'/design_title/';
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
			$uploadconfig['allowed_types'] = 'gif|jpg|png';
			$uploadconfig['max_size'] = 1024 * 1024;
			$uploadconfig['encrypt_name'] = true;
			$this->upload->initialize($uploadconfig);
			$_FILES['userfile']['name'] = $_FILES['imgfile']['name'];
			$_FILES['userfile']['type'] = $_FILES['imgfile']['type'];
			$_FILES['userfile']['tmp_name'] = $_FILES['imgfile']['tmp_name'];
			$_FILES['userfile']['error'] = $_FILES['imgfile']['error'];
			$_FILES['userfile']['size'] = $_FILES['imgfile']['size'];
			if ($this->upload->do_upload()) {
				$this->load->library('image_lib');
				$this->image_lib->clear();
				$imgconfig = array();
				$filedata = $this->upload->data();
				$imgconfig['image_library'] = 'gd2';
				$imgconfig['source_image']	= $filedata['full_path'];
				//$imgconfig['create_thumb'] = TRUE;
				$imgconfig['maintain_ratio'] = TRUE;
				$imgconfig['new_image'] = $filedata['full_path'];
				$this->image_lib->initialize($imgconfig);
				if($this->image_lib->resize()) {
					$data["tit_imgpath"] = str_replace($_SERVER["DOCUMENT_ROOT"],'',$imgconfig['new_image']); //이미지경로
				} else {
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Thumb 이미지 저장 실패 : ". $this->upload->display_errors());
				}
			} else {
				$file_error = $this->upload->display_errors();
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 Upload 실패 : ".$this->upload->display_errors().'( '. $upload_path.' )');
			}
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 Upload OK");
		}

		$data["tit_mem_id"] = $this->member->item("mem_id"); //회원번호
		$data["tit_type"] = $this->input->post("tit_type") ; //구분(I.이미지형, T.텍스트형)
		if(!empty($this->input->post("tit_useyn"))) {
			$data["tit_useyn"] = $this->input->post("tit_useyn") ; //사용여부
		}
		if(!empty($tit_id)) { //수정의 경우
			$where = array();
			$where["tit_id"] = $tit_id; //타이틀번호
			$rtn = $this->db->update("cb_design_title", $data, $where);
		}else{ //등록
			$sql = "select ifnull(max(tit_id),0)+1 as tit_id from cb_design_title ";
			$tit_id = $this->db->query($sql)->row()->tit_id;
			$data["tit_id"] = $tit_id; //타이틀번호
			$data["tit_seq"] = $tit_id; //정렬순서
			$rtn = $this->db->replace("cb_design_title", $data);
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tit_id : ". $tit_id .", rtn = ". $rtn);
		$result = array();
		$result['code'] = '0';
		$result['msg'] = $tit_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 타이틀관리 삭제
	public function title_del(){
		$tit_id = $this->input->post("tit_id"); //타이틀번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tit_id : ". $tit_id);
		$sql = "delete from cb_design_title where tit_id = '". $tit_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 디자인관리 > 타이틀관리 삭제 : ". $sql);
		$this->db->query($sql);
		$result = array();
		$result['code'] = '0';
		$result['msg'] = $tit_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 타이틀관리 순서변경
	public function title_sort(){
		$seq = $this->input->post("seq"); //순번
		for($row = 0; $row < count($seq); $row ++) {
			$sql = "update cb_design_title set tit_seq='" .$row ."' where tit_id = '". $seq[$row] ."' ";
			$this->db->query($sql);
		}
	}

	//디자인관리 > 이미지관리
	public function images(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$view = array();
		$view['view'] = array();
		$view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 15; //리스트수
		$view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1; //현재페이지
		$view['sort'] = ($this->input->get("sort")) ? $this->input->get("sort") : "1"; //정렬 (1.등록일순, 2.제품명순)
		$view['searchType'] = $this->input->get("searchType"); //검색타입
		$view['searchStr'] = $this->input->get("searchStr"); //검색내용
		$view['imgcode'] = $this->input->get("imgcode"); //상품코드
		$where = "WHERE i.img_useyn = 'Y' ";
		if(!empty($view['searchStr'])) {
			if($view['searchType'] == "1"){ //제품명
				$orstr = explode(' ', $view['searchStr']);
				$orcnt = count($orstr);
				if($orcnt > 1) {
					$where = $where." AND ( ";
					$prefix = '';
					for($i=0; $i<$orcnt; $i++){
						$where = $where.$prefix." i.img_name LIKE '%". addslashes($orstr[$i]) ."%' ";
						$prefix = " AND ";
					}
					$where = $where." ) ";
				} else {
					$where = $where." AND i.img_name LIKE '%". addslashes($orstr[0]) ."%' ";
				}
			} else if($view['searchType'] == "2"){ //상품코드
				$where = $where." AND img_code LIKE '%". addslashes($view['searchStr']) ."%'  ";
			}
		}
		if(!empty($view['imgcode'])) { //상품코드
			if($view['imgcode'] == "Y") {
				$where = $where." AND img_code != '' ";
			} else if($view['imgcode'] == "N") {
				$where = $where." AND ifnull(img_code,'N') = 'N' ";
			}
		}
		$sort = "";
		if($view['sort'] == "2"){ //제품명순
			$sort = "i.img_name ASC";
		}else{ //등록일순
			$sort = "i.img_id DESC";
		}

		//이미지 전체수
		$sql = "SELECT COUNT(*) AS cnt FROM cb_images i ". $where;
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//이미지 현황
		$sql = "
		SELECT
			  img_id /* 이미지번호 */
			, img_name /* 이미지 이름 */
			, img_path /* 이미지 경로 */
			, img_path_thumb /* 썸네일 이미지 경로 */
		FROM cb_images i
		". $where ."
		ORDER BY ". $sort ."
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
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
			'path' => 'mng/design',
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

	//디자인관리 > 이미지관리 저장
	public function images_save(){
		$img_id = $this->input->post("img_id"); //이미지번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_id : ". $img_id);
		$data = array();
		$data["img_category1"] = 0; //카테고리번호
		$data["img_category2"] = 0; //하위카테고리번호
		$data["img_name"] = $this->input->post("img_name"); //제품명
		$data["img_code"] = $this->input->post("img_code"); //상품코드
		if(!empty($this->input->post("img_useyn"))) {
			$data["img_useyn"] = $this->input->post("img_useyn") ; //사용여부
		}
		if (isset($_FILES) && isset($_FILES['imgfile']) && isset($_FILES['imgfile']['name'])) { //이미지 업로드
			$this->load->library('upload');

			// 업로드 기본 폴더
			$upload_path = config_item('uploads_dir') .'/products/';
			//echo "upload_path : ". $upload_path ."<br>";
			
			// 기본 폴더가 없다면 생성을 위해 실행
			$this->funn->create_upload_dir($upload_path);

			// 년 폴더가 없다면 생성을 위해 실행
			$this->funn->create_upload_dir($upload_path . date("Y") ."/");

			// 월 폴더가 없다면 생성을 위해 실행
			$this->funn->create_upload_dir($upload_path . date("Y") ."/". date("m") ."/");

			// 최종 업로드 경로
			$upload_path = $upload_path . date("Y") ."/". date("m") ."/";
			//echo "upload_path : ". $upload_path ."<br>";

			$uploadconfig = '';
			$uploadconfig['upload_path'] = $upload_path;
			$uploadconfig['allowed_types'] = 'gif|jpg|png';
			$uploadconfig['max_size'] = 1024 * 1024;
			$uploadconfig['encrypt_name'] = true;
			$this->upload->initialize($uploadconfig);
			$_FILES['userfile']['name'] = $_FILES['imgfile']['name'];
			$_FILES['userfile']['type'] = $_FILES['imgfile']['type'];
			$_FILES['userfile']['tmp_name'] = $_FILES['imgfile']['tmp_name'];
			$_FILES['userfile']['error'] = $_FILES['imgfile']['error'];
			$_FILES['userfile']['size'] = $_FILES['imgfile']['size'];
			if ($this->upload->do_upload()) {
				$this->load->library('image_lib');
				$this->image_lib->clear();
				$imgconfig = array();
				$filedata = $this->upload->data();
				$imgconfig['image_library'] = 'gd2';
				$imgconfig['source_image']	= $filedata['full_path'];
				//$imgconfig['create_thumb'] = TRUE;
				$imgconfig['maintain_ratio'] = TRUE;
				$imgconfig['new_image'] = $filedata['full_path'];
				$this->image_lib->initialize($imgconfig);
				if($this->image_lib->resize()) {
					$data["img_path"] = str_replace($_SERVER["DOCUMENT_ROOT"],'',$imgconfig['new_image']); //이미지경로
				} else {
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Thumb 이미지 저장 실패 : ". $this->upload->display_errors());
				}
				$thumb = "200"; //썸네일 사이즈
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
					if($this->image_lib->resize()) {
						$data["img_path_thumb"] = str_replace($_SERVER["DOCUMENT_ROOT"],'',$simgconfig['new_image']); //이미지경로
					} else {
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Thumb 이미지 저장 실패 : ". $this->upload->display_errors());
					}
				}
			} else {
				$file_error = $this->upload->display_errors();
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 Upload 실패 : ".$this->upload->display_errors().'( '. $upload_path.' )');
			}
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 Upload OK");
		}
		if(!empty($img_id)) { //수정의 경우
			$where = array();
			$where["img_id"] = $img_id; //이미지명
			$data["img_upd_id"] = $this->member->item("mem_id"); //수정자 회원번호
			$rtn = $this->db->update("cb_images", $data, $where); //이미지 라이브러리 수정
		}else{ //등록
			$data["img_cre_id"] = $this->member->item("mem_id"); //등록자 회원번호
			$data["img_upd_id"] = $this->member->item("mem_id"); //수정자 회원번호
			$rtn = $this->db->replace("cb_images", $data); //이미지 라이브러리 추가
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_id : ". $img_id .", rtn = ". $rtn);
		$result = array();
		$result['code'] = '0';
		$result['msg'] = $img_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 이미지관리 삭제
	public function images_del(){
		$img_id = $this->input->post("img_id"); //이미지번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_id : ". $img_id);
		//$sql = "delete from cb_images where img_id = '". $img_id ."' ";
		$sql = "update cb_images set img_useyn='N' where img_id = '". $img_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 디자인관리 > 이미지관리 삭제 : ". $sql);
		$this->db->query($sql);
		$result = array();
		$result['code'] = '0';
		$result['msg'] = $img_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 이미지관리 > 데이타 조회
	public function images_data(){
		$img_id = $this->input->post("img_id"); //이미지번호
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지관리 > 데이타 조회");
		$sql = "select * from cb_images where img_id = '". $img_id ."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 디자인관리 > 이미지관리 > 데이타 조회 : ". $sql);
		$result = $this->db->query($sql)->row();
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		$json = str_replace('null', '""', $json);
		header('Content-Type: application/json');
		echo $json;
	}

	//디자인관리 > 이미지관리 > FTP Import
	public function images_import(){
		
		//회원번호
		$mem_id = $this->member->item('mem_id'); //회원번호
		
		// 폴더명 지정
		$org_dir = $_SERVER["DOCUMENT_ROOT"] ."/uploads/temp_images"; //원본 디렉토리
		$new_dir = $_SERVER["DOCUMENT_ROOT"] ."/uploads/products_import"; //복사 디렉토리
		//$new_dir = $_SERVER["DOCUMENT_ROOT"] ."/uploads/products"; //복사 디렉토리
		$this->funn->create_upload_dir($new_dir ."/"); //기본 폴더가 없다면 생성을 위해 실행
		$this->funn->create_upload_dir($new_dir ."/". date("Y") ."/"); //년 폴더가 없다면 생성을 위해 실행
		$this->funn->create_upload_dir($new_dir ."/". date("Y") ."/". date("m") ."/"); //월 폴더가 없다면 생성을 위해 실행
		$new_dir = $new_dir ."/". date("Y") ."/". date("m"); //최종 복사 디렉토리
		//echo "org_dir : ". $org_dir ."<br>new_dir : ". $new_dir ."<br>";
		 
		// 핸들 획득
		$handle  = opendir($org_dir);

		$files = array();

		// 디렉터리에 포함된 파일을 저장한다.
		while (false !== ($filename = readdir($handle))){
			if($filename == "." || $filename == ".."){
				continue;
			}

			// 파일인 경우만 목록에 추가한다.
			if(is_file($org_dir . "/" . $filename)){
				$files[] = $filename;
			}
		}

		// 핸들 해제 
		closedir($handle);

		// 정렬, 역순으로 정렬하려면 rsort 사용
		//sort($files);

		// 파일명을 출력한다.
		$no = 0;
		foreach ($files as $fn) {
			//echo $fn ."<br>";
			//$fn : 파일명-촬영년월_상품코드.확장자
			$no++;
			//$file1_arr = substr(strrchr($fn, '.'), 1);
			$file_ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $fn); //확장자
			$file_full = basename($fn, '.'. $file_ext); //파일명-촬영년월_상품코드
			$file2_arr = explode("-", $fn); //파일명-촬영년월_상품코드 배열처리
			$file3_arr = explode("_", $file_full); //파일명-촬영년월_상품코드 배열처리
			$file_name = trim($file2_arr[0]); //파일명
			$file_code = trim($file3_arr[1]); //상품코드
			$new_name = sprintf('%03d', $no) . $this->funn->RenStr(22) .".". $file_ext;
			//echo "[". sprintf('%03d', $no) ."] fn : ". $fn .", file_full : ". $file_full .", file_ext : ". $file_ext ."<br>";
			//echo "[". sprintf('%03d', $no) ."] file_full : ". $file_full .", file_name : ". $file_name .", file_code : ". $file_code .", new_name : ". $new_name ."<br>";
			
			// 실제 존재하는 파일인지 체크...
			$old_filepath = $org_dir . "/" . $fn; //원본파일
			$new_filepath = $new_dir . "/" . $new_name; //복사파일
			//echo "[". sprintf('%03d', $no) ."] old_filepath : ". $old_filepath .", new_filepath : ". $new_filepath ."<br>";
			if(file_exists($old_filepath) && 1 == 1){
				if(!copy($old_filepath, $new_filepath)) {
					//echo "<font color='red'>파일 복사 실패 [". sprintf('%03d', $no) ."] old_filepath : ". $old_filepath .", new_filepath : ". $new_filepath ."</font><br>";
					echo "<font color='red'>파일 복사 실패 [". sprintf('%03d', $no) ."] ". $fn ."</font><br>";
				} else if(file_exists($new_filepath)) {
					//테이블명
					$tbl_nm = "cb_images";

					//이미지 라이브러리 확인
					$sql = "SELECT COUNT(*) AS cnt FROM ". $tbl_nm ." where img_name = '". $file_name ."' ";
					$img_cnt = $this->db->query($sql)->row()->cnt;
					//echo "[". sprintf('%03d', $no) ."] img_cnt : ". $img_cnt .", sql : ". $sql ."<br>";
					//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_cnt : ". $img_cnt);
						
					//이미지 라이브러리 저장
					$data = array();
					$data["img_category1"] = 0; //카테고리번호
					$data["img_category2"] = 0; //하위카테고리번호
					$data["img_path"] = str_replace($_SERVER["DOCUMENT_ROOT"], "", $new_filepath); //이미지경로
					$data["img_code"] = $file_code; //상품코드
					$file_mode = "";
					if($img_cnt == 0){ //신규등록
						$data["img_name"] = $file_name ; //이미지명
						$data["img_cre_id"] = $mem_id; //회원번호
						$rtn = $this->db->replace($tbl_nm, $data); //이미지 라이브러리 추가
						$file_mode = "등록";
						//echo "[". sprintf('%03d', $no) ."] 등록 : ". $file_name ."<br>";
						echo "<font color='blue'>파일 ". $file_mode ." 성공 [". sprintf('%03d', $no) ."] ". $fn ."</font><br>";
					}else{ //수정
						$where = array();
						$where["img_name"] = $file_name; //이미지명
						$data["img_upd_id"] = $mem_id; //회원번호
						$rtn = $this->db->update($tbl_nm, $data, $where); //이미지 라이브러리 수정
						$file_mode = "수정";
						//echo "[". sprintf('%03d', $no) ."] 수정 : ". $file_name ."<br>";
						echo "<font color='green'>파일 ". $file_mode ." 성공 [". sprintf('%03d', $no) ."] ". $fn ."</font><br>";
					}
					//echo "<font color='blue'>파일 복사 성공 [". sprintf('%03d', $no) ."] old_filepath : ". $old_filepath .", new_filepath : ". $new_filepath ."</font><br>";
					//echo "<font color='blue'>파일 ". $file_mode ." 성공 [". sprintf('%03d', $no) ."] ". $fn ."</font><br>";
				}
			}
		}
	}

	//디자인관리 > 이미지관리 > 상품코드 엑셀 업데이트
	public function images_code_upload(){
		$cnt = 0;
		$upd = 0;
		$result = array();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file : ". $_FILES['file']['tmp_name']);
		//업로드된 엑셀 파일을 처리함
	    if(file_exists($_FILES['file']['tmp_name'])) {
			$this->load->library("excel");
			$inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > inputFileType : ". $inputFileType);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly( true );
			$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']); // 파일경로
			$sheetsCount = $objPHPExcel->getSheetCount();

			// 쉬트별로 읽기
			for($i = 0; $i < $sheetsCount; $i++){
				$sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
				$sheet = $objPHPExcel->getActiveSheet();
				$highestRow = $sheet->getHighestRow();
				$highestColumn = $sheet->getHighestColumn();
				//log_message("ERROR", "Rows : ".$highestRow);
				// 한줄읽기
				for ($row = 1; $row <= $highestRow; $row++){ //1번줄부터 읽기
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
					//롯데네슬레코리아 네스카페크리마아메리카노텀블러증정 100스틱 -촬영201908_8801055704354.png
					$full_name = (empty($rowData[0][0])) ? "" : $rowData[0][0]; //상품명-촬영일_상품코드.확장자
					if($full_name != ""){
						$file_ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $full_name); //확장자
						$file_full = basename($full_name, '.'. $file_ext); //파일명-촬영년월_상품코드
						$file2_arr = explode("-", $full_name); //파일명-촬영년월_상품코드 배열처리
						$file3_arr = explode("_", $file_full); //파일명-촬영년월_상품코드 배열처리
						$file_name = trim($file2_arr[0]); //파일명
						$file_code = trim($file3_arr[1]); //상품코드
						if($file_name != "" and $file_code != ""){
							//테이블명
							$tbl_nm = "cb_images";

							//이미지 라이브러리 확인
							$sql = "SELECT COUNT(*) AS cnt FROM ". $tbl_nm ." WHERE img_name = '". $file_name ."' and img_useyn = 'Y' and ifnull(img_code,'N') = 'N' ";
							$img_cnt = $this->db->query($sql)->row()->cnt;

							//상품명이 일치하고 상품코드가 없는 경우
							if($img_cnt > 0){
								$upd++;
								$where = array();
								$where["img_name"] = $file_name; //이미지명
								$where["img_useyn"] = "Y"; //사용여부
								$data = array();
								$data["img_code"] = $file_code; //상품코드
								$rtn = $this->db->update($tbl_nm, $data, $where); //이미지 라이브러리 수정
								if($rtn == 1){
									$upd++;
								}
								//$sql = "UPDATE ". $tbl_nm ." SET img_code = '". $file_code ."' WHERE img_name = '". $file_name ."' AND img_useyn = 'Y' AND ifnull(img_code,'N') = 'N' ";
								//$this->db->query($sql);
								//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 이미지 라이브러리 수정[". $upd ."] : ". $sql);
							}
						}
						$cnt++;
						//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > file[". $cnt ."] file_code : ". $file_code .", file_name : ". $file_name .", full_name : ". $full_name);
					}
				}
			}
		}
		$result = array();
		$result['cnt'] = $cnt;
		$result['upd'] = $upd;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result['cnt'] : ". $result['cnt']);
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

}
?>
