<?php
/*

모든 유저의 cb_amt_?? 테이블에 관리자 단가필드를 저장

*/

class Table_adjust extends CB_Controller {
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
		$sql = "select mem_id, mem_userid from cb_member order by mem_id";
		$mems = $this->db->query($sql)->result();

		//- 모든 유저의 cb_amt_?? 테이블에 관리자 단가필드를 저장

		
		/*

		foreach($mems as $mem) {
			$sql = "SHOW COLUMNS FROM `cb_amt_".$mem->mem_userid."` LIKE 'amt_admin'";
			$result = $this->db->query($sql);
			if(!$result) continue;
			$row = $result->row();
			if($row && $row->Field=="amt_admin") continue;

			$sql = "alter table `cb_amt_".$mem->mem_userid."` add column `amt_admin`		decimal(14,2)			default '0.0'	comment '관리자단가'";
			$this->db->query($sql);
			if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }
		}

		echo '<pre> :: ';print_r("처리완료");
		*/

		$adm = $this->funn->getMemberPrice(3);	//- 대형의 단가

		/* // 단가정리, payback정리
		foreach($mems as $mem) {
			$price = $this->funn->getParentPrice($mem->mem_id);
			if($price == null) {
				$sql = "update `cb_amt_".$mem->mem_userid."` set amt_payback=0, amt_admin=? where amt_kind='P'";
				$this->db->query($sql, array($adm['mad_price_phn']));
				if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }

				$sql = "update `cb_amt_".$mem->mem_userid."` set amt_payback=0, amt_admin=? where amt_kind='3'";
				$this->db->query($sql, array($adm['mad_price_phn'] * -1));
				if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }
			} else {
				$sql = "update `cb_amt_".$mem->mem_userid."` set amt_payback=amt_amount - ?, amt_admin=? where amt_kind='P'";
				$this->db->query($sql, array($price['mad_price_phn'], $adm['mad_price_phn']));
				if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }

				$sql = "update `cb_amt_".$mem->mem_userid."` set amt_payback=(amt_amount - ?) * -1, amt_admin=? where amt_kind='3'";
				$this->db->query($sql, array($price['mad_price_phn'], $adm['mad_price_phn'] * -1));
				if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }
			}
		}
		*/

		//- admin 발신단가 정리

		foreach($mems as $mem) {
			$sql = "update `cb_amt_".$mem->mem_userid."` set amt_admin=? where amt_kind='A'";
			$this->db->query($sql, array($adm['mad_price_at']));
			if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }

			$sql = "update `cb_amt_".$mem->mem_userid."` set amt_admin=? where amt_kind='F'";
			$this->db->query($sql, array($adm['mad_price_ft']));
			if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }

			$sql = "update `cb_amt_".$mem->mem_userid."` set amt_admin=? where amt_kind='I'";
			$this->db->query($sql, array($adm['mad_price_ft_img']));
			if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }

			$sql = "update `cb_amt_".$mem->mem_userid."` set amt_admin=? where amt_kind='P'";
			$this->db->query($sql, array($adm['mad_price_phn']));
			if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }




			$sql = "update `cb_amt_".$mem->mem_userid."` set amt_admin=? where amt_kind='3'";
			$this->db->query($sql, array($adm['mad_price_phn'] * -1));
			if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }

			$sql = "update `cb_amt_".$mem->mem_userid."` set amt_admin=? where amt_kind='4'";
			$this->db->query($sql, array($adm['mad_price_phn'] * -1));
			if($this->db->error['code']>0) { echo '<pre> :: ';print_r($this->db->error()); }
		}
		
		
		echo '<pre> :: ';print_r("처리완료");
	}
}?>