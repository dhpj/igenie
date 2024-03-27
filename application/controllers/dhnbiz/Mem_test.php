<?php
class Mem_test extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board');

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
		$this->load->model("Biz_free_model");
		exit;

		//echo $this->Biz_free_model->adm_send_message("");
		//echo $this->Biz_free_model->deposit_appr("","");

		//echo '<pre> :: ';print_r($this->Biz_free_model->getBasePrice());
		//echo '<pre> :: ';print_r($this->Biz_free_model->getMemberPrice(3));
		//echo '<pre> :: ';print_r($this->Biz_free_model->getMemberPrice(5));
		//echo '<pre> :: ';print_r($this->Biz_free_model->getParentPrice(28));
	}

	/*
	*  기본 단가 수집
	*/
	function getBasePrice()
	{
		$sql = "
			select wst_mem_id mad_mem_id, '' mad_free_hp,
				wst_price_ft mad_price_ft, wst_price_ft_img mad_price_ft_img, wst_price_at mad_price_at, wst_price_sms mad_price_sms,
				wst_price_lms mad_price_lms, wst_price_mms mad_price_mms, wst_price_phn mad_price_phn,
				0 payback_at, 0 payback_ft, 0 payback_ft_img, 0 payback_phn, 0 payback_sms, 0 payback_lms, 0 payback_mms
			from cb_wt_setting limit 1
		";
		$result =  $this->db->query($sql)->result_array();
		if(count($result) > 0) return $result[0];
		return null;
	}
	/*
	*  자신의 단가와 상위 관리자와의 단가 차액을 반환 : array
	*	lv100 미만인 최고상위자의 단가만 반환 : 하위의 단가에 대한 마진이나 단가처리는 lv50이 알아서 처리함
	*/
	function getParentPrice($mem_id)
	{
		$sql = "
			select b.mem_userid, b.mem_level, i.*
				,(i.mad_price_at - ifnull(a.mad_price_at, i.mad_price_at)) payback_at
				,(i.mad_price_ft - ifnull(a.mad_price_ft, i.mad_price_ft)) payback_ft
				,(i.mad_price_ft_img - ifnull(a.mad_price_ft_img, i.mad_price_ft_img)) payback_ft_img
				,(i.mad_price_phn - ifnull(a.mad_price_phn, i.mad_price_phn)) payback_phn
				,(i.mad_price_sms - ifnull(a.mad_price_sms, i.mad_price_sms)) payback_sms
				,(i.mad_price_lms - ifnull(a.mad_price_lms, i.mad_price_lms)) payback_lms
				,(i.mad_price_mms - ifnull(a.mad_price_mms, i.mad_price_mms)) payback_mms
			from
				cb_wt_member_addon i left join
				cb_wt_member_addon a on 1=1 inner join
				cb_member b on a.mad_mem_id=b.mem_id inner join
				(
					SELECT  @r AS _id, (SELECT  @r := mrg_recommend_mem_id FROM cb_member_register WHERE mem_id = _id ) AS mrg_recommend_mem_id
					FROM
						(SELECT  @r := ".$mem_id.", @cl := 0) vars,	cb_member_register h
					WHERE    @r <> 0
				) c on a.mad_mem_id=c.mrg_recommend_mem_id
			where i.mad_mem_id=".$mem_id." and b.mem_level < 100
			order by b.mem_level desc
		";

		$result =  $this->db->query($sql)->result_array();
		if(count($result) > 0) return $result[0];
		return null;
	}
	/*
	*  지정된 mem_id의 단가를 반환 : array
	*  주로 대형(lv100)의 단가를 구하기 위해 사용함
	*  제3의 에이전트가 있을수 있음 : 온라인발송 agent 제공시 추가소지가 있음
	*/
	function getMemberPrice($mem_id)
	{
		$sql = "
			select b.mem_userid, b.mem_level, a.*,
				a.mad_price_phn phn1, a.mad_price_sms sms1, a.mad_price_lms lms1, a.mad_price_mms mms1,
				a.mad_price_phn phn2, a.mad_price_sms sms2, a.mad_price_lms lms2, a.mad_price_mms mms2,
				a.mad_price_phn phn3, a.mad_price_sms sms3, a.mad_price_lms lms3, a.mad_price_mms mms3
			from
				cb_wt_member_addon a inner join
				cb_member b on a.mad_mem_id=b.mem_id
			where a.mad_mem_id=".$mem_id;

		$result =  $this->db->query($sql)->result_array();
		if(count($result) > 0) {
			if($result[0]["mem_level"] >= 100) {
				/* 각 에이전트별 대형의 단가를 추가 */
				$agent = array("prcom"=>"1", "dooit"=>"2");
				$sql = "select * from cb_wt_adm_price";
				$query = $this->db->query($sql);
				if($query) {
					$price = $query->result();
					foreach($price as $p) {
						$result[0]["phn".$agent[$p->adm_agent]] = $p->adm_price_phn;
						$result[0]["sms".$agent[$p->adm_agent]] = $p->adm_price_sms;
						$result[0]["lms".$agent[$p->adm_agent]] = $p->adm_price_lms;
						$result[0]["mms".$agent[$p->adm_agent]] = $p->adm_price_mms;
					}
				}
			}
			return $result[0];
		}
		return null;
	}

}
?>