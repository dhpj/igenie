<?php
class Coin extends CB_Controller {
	/**
	* ���� �ε��մϴ�
	*/
	protected $models = array('Board', 'Biz');

	/**
	* ���۸� �ε��մϴ�
	*/
	protected $helpers = array('form', 'array');

	function __construct()
	{
		parent::__construct();

		/**
		* ���̺귯���� �ε��մϴ�
		*/
		$this->load->library(array('querystring'));
	}

	public function index()
	{
		$mCoin = $this->Biz_model->getAbleCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
		$mSent = $this->Biz_model->getTodaySent($this->member->item('mem_id'));
		
		$m2ndPrice = 0;
		switch($this->member->item('mem_2nd_send')) {
		    case "GREEN_SHOT" : $m2ndPrice= $this->Biz_model->price_grs;
		                        break;
		    case "NASELF" : $m2ndPrice= $this->Biz_model->price_nas;
		                    break;
		    case "SMART" : $m2ndPrice= $this->Biz_model->price_smt;
		    break;
		    default :$m2ndPrice = 0;
		    break;
		}

		header('Content-Type: application/json');
		echo '{"coin": "'.$mCoin.'", "sent":"'.$mSent.'", "limit":"'.$this->member->item('mem_day_limit').'", "2ndprice":"'.$m2ndPrice.'"}';
	}
}
?>

