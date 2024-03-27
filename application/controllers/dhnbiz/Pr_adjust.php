<?php
class Pr_adjust extends CB_Controller {
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
		exit;
		$ok = 0; $err = 0;

		$err_rs = $this->db->query("select a.PR_ID, a.PR_RSLT, a.mem_userid, b.PR_RSLT RSLT from prcom.cb_pr_tran_sent_err a inner join dhn.cb_pr_tran_sent b on a.PR_ID=b.PR_ID order by PR_ID")->result();
		foreach($err_rs as $ers) {
//			if($ers->RSLT!="000") { echo '<pre> :: ';print_r($ers); continue; }
			$msg_row = $this->db->query("select REMARK4 from cb_msg_".$ers->mem_userid." where MSGID=?", array($ers->PR_ID))->row();

			if($msg_row && $msg_row->REMARK4) {
			

				if($ers->PR_RSLT=="06") {
					$sql = "update cb_msg_".$ers->mem_userid." set RESULT='Y' where MSGID=?";
					$this->db->query($sql, array($ers->PR_ID));
					if($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($sql.$ers->PR_ID);echo '<pre> :: ';print_r($this->db->error());break; }
				} else { continue; }

/*			

				
				//- 1. 발송카운터 변경 : 성공인 경우 1 누적
				if($ers->PR_RSLT=="06") {
					$sql = "update dhn.cb_wt_msg_sent set mst_phn = mst_phn + 1 where mst_id=?";
					$this->db->query($sql, array($msg_row->REMARK4));
					if($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($sql.$msg_row->REMARK4);echo '<pre> :: ';print_r($this->db->error());break; }
				} else { continue; }

				
				//- 2. 성공인 경우 amt에서 삭제
				$sql = "delete from cb_amt_".$ers->mem_userid." where amt_kind='3' and amt_reason=?";
				$this->db->query($sql, array($ers->PR_ID));
				if($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($sql.$ers->PR_ID);echo '<pre> :: ';print_r($this->db->error());break; }

			
				//- 3. 발송결과 업데이트
				$sql = "update dhn.cb_pr_tran_sent set PR_RSLT='06' where PR_ID=?";
				$this->db->query($sql, array($ers->PR_ID));
				if($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($sql.$ers->PR_ID);echo '<pre> :: ';print_r($this->db->error());break; }
*/
				$ok++;

			} else {
				echo '<pre> :: ';print_r($ers);
			}
		}

		echo '<pre> :: ';print_r("OK : ".$ok);
	}
}?>