<?php
class Imglibrary extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	//protected $models = array('Board', 'Biz');

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
		$this->load->library(array('querystring'));
	}

	//이미지 경로 조회
	public function img_path(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Start");
		$result = array();
		$img_name = $this->input->post("img_name"); //이미지명
		$result['rtn_data'] = $this->input->post("rtn_data"); //리턴 값

		//이미지 경로
		$sql = "SELECT img_path
		FROM cb_images i
		WHERE i.img_useyn = 'Y'
		AND i.img_name = '". addslashes($img_name) ."'
		LIMIT 1 ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
		$result['img_path'] = $this->db->query($sql)->row()->img_path;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_path : ". $result['img_path']);
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

}
?>
