<?php
class At extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */

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
        $this->load->library(array('querystring', 'funn'));
    }

    public function _remap($short_url) {
        $this->index($short_url);
    }

    public function index($short_url = "") {
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > short_url : ". $short_url);
		//echo "short_url : ". $short_url ."<br>";
		$view = array();

		$add = $this->input->get("add"); //스킨 추가
		$skin = "atview";
		if($add != "") $skin .= $add;

		//UMS 정보
		$sql = "
		SELECT *
		FROM cb_alimtalk_ums
		WHERE idx = (
			SELECT IFNULL(MAX(idx),0) from cb_alimtalk_ums where short_url = '". $short_url ."'
		) ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > index : sql => ". $sql);
		$result = $this->db->query($sql)->row();

		//매장정보
		$memsql = "select * from cb_member cm where cm.mem_id = '".$result->mem_id."' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > index : memsql => ". $memsql);
		$userinfo = $this->db->query($memsql)->row();

		$view['html'] = $result; //UMS 정보
		$view['shop'] = $userinfo; //매장정보
		$this->load->view($skin,$view);
    }

}
?>
