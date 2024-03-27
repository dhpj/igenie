<?php
class Common extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board', 'Biz');

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

	public function index()
	{
	}

	public function check_dup_id() {
		$id = $this->input->post("mb_id");
		$sql = "select count(*) cnt from cb_member where mem_userid=?";
		$r = $this->db->query($sql, array($id))->row();
		header('Content-Type: application/json');
		echo '{"result":'.(($r->cnt > 0) ? 'false' : 'true').'}';
	}

	public function check_id() {
		$id = $this->input->post("mb_id");
		$sql = "select mem_username, mem_nickname from cb_member where mem_userid=?";
		$r = $this->db->query($sql, array($id))->row();
		header('Content-Type: application/json');
		echo '{"result":'.(($r && $r->mem_username) ? 'true' : 'false').', "name":"'.$r->mem_username.'", "nick":"'.$r->mem_nickname.'"}';
	}
}
?>