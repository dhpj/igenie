<?php
class Checkmsg extends CB_Controller {
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
		$this->load->library(array('querystring', 'simple_html_dom'));
	}

	public function index()
	{
		if($_SERVER['REMOTE_ADDR']!='182.208.91.234') {
			exit;
		}
		exit;

		$tbls = $this->db->query("show tables like 'cb_msg_%'")->result_array();
		foreach($tbls AS $row) {
			$result = $this->db->query("select count(*) cnt from ".$row['Tables_in_dhn (cb_msg_%)']." where MESSAGE='DuplicatedMsgId'")->row();
			if($result) {
				if($result->cnt > 0) {
					echo '<pre> :: ';print_r($row['Tables_in_dhn (cb_msg_%)']);
					echo '<pre> :: ';print_r($result);
				}
			}
		}
	}
}?>