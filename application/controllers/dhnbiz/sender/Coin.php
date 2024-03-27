<?php
class Coin extends CB_Controller {
	/**
	* ���� �ε��մϴ�
	*/
	protected $models = array('Board', 'Biz_dhn');

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
        if($this->member->item('mem_voucher_yn')=="Y"){
            $mCoin = $this->Biz_dhn_model->getAbleVCoin($this->member->item('mem_id'), $this->member->item('mem_userid')) + $this->Biz_dhn_model->getAbleCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
        }else{
            $mCoin = $this->Biz_dhn_model->getAbleCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
            $bCoin = $this->Biz_dhn_model->getAbleBCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
            $bCoin = !empty($bCoin) ? $bCoin : '0';
        }

		$mSent = $this->Biz_dhn_model->getTodaySent($this->member->item('mem_id'));
		//log_message("ERROR", "Coin : ".$mCoin);
		$m2ndPrice = 0;
		switch($this->member->item('mem_2nd_send')) {
		    case "GREEN_SHOT" : $m2ndPrice= $this->Biz_dhn_model->price_grs;
		                        break;
		    case "NASELF" : $m2ndPrice= $this->Biz_dhn_model->price_nas;
		                    break;
		    case "SMART" : $m2ndPrice= $this->Biz_dhn_model->price_smt;
		    break;
		    default :$m2ndPrice = 0;
		    break;
		}

		header('Content-Type: application/json');
		echo '{"coin": "'.$mCoin.'", "sent":"'.$mSent.'", "limit":"'.$this->member->item('mem_day_limit').'", "2ndprice":"'.$m2ndPrice.'", "bcoin":"'.$bCoin.'"}';
		//log_message("ERROR",'{"coin": "'.$mCoin.'", "sent":"'.$mSent.'", "limit":"'.$this->member->item('mem_day_limit').'", "2ndprice":"'.$m2ndPrice.'"}' );
	}
}
?>
