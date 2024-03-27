<?php
/*

모든 유저의 cb_amt_?? 트리거를 재생성함!! -> 발송데이터가 없을때 처리하기!!

*/
exit;

class Trigger_adjust extends CB_Controller {
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
		$sql = "select mem_userid from cb_member order by mem_id";
		$mems = $this->db->query($sql)->result();

exit;
		foreach($mems as $mem) {
			//- 트리거 삭제 -> 오류시 skip
			$sql = "DROP TRIGGER `ins_cb_amt_".$mem->mem_userid."`";
			$this->db->query($sql);
			if ($this->db->error()['code'] > 0) {
				echo '<pre> :: ';print_r($this->db->error());
				continue;
			}
			//- 트리거 생성
			$sql = "
				CREATE TRIGGER `ins_cb_amt_".$mem->mem_userid."` BEFORE INSERT ON `cb_amt_".$mem->mem_userid."`
				  FOR EACH ROW
				BEGIN
					if NEW.amt_kind='1' then
						update cb_member set mem_deposit = mem_deposit + NEW.amt_amount where mem_userid='".$mem->mem_userid."';
						set NEW.amt_deposit := NEW.amt_amount;
					else
						select mem_deposit, mem_point into @nDeposit, @nPoint from cb_member where mem_userid='".$mem->mem_userid."';
						IF NEW.amt_kind>='A' and NEW.amt_kind<='Z' THEN
							if @nPoint > 0 then
								set @nPoint := @nPoint - NEW.amt_amount;
								if @nPoint < 0 then
									set NEW.amt_point := NEW.amt_amount - @nPoint;
									set NEW.amt_deposit := abs(@nPoint);
									set @nDeposit := @nDeposit + @nPoint;
									set @nPoint := 0;
								else
									set NEW.amt_point := NEW.amt_amount;
								end if;
							else
								set @nDeposit := @nDeposit - NEW.amt_amount;
								set NEW.amt_deposit := NEW.amt_amount;
							end if;
						else
							if NEW.amt_kind='2' then
								set @nDeposit := @nDeposit - NEW.amt_amount;
								set NEW.amt_deposit := NEW.amt_amount;
							else
								set @nPoint := @nPoint + NEW.amt_amount;
								set NEW.amt_point := NEW.amt_amount;
							end if;
						end if;
						update cb_member set mem_deposit=@nDeposit, mem_point=@nPoint where mem_userid='".$mem->mem_userid."';
					END IF;
				END;
			";			
			$this->db->query($sql);
			if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); }
		}
	}
}?>