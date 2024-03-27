<?php
class Summary extends CB_Controller {
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
		$key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();
		$view['param']['userid'] = (!$this->input->post('userid') || $this->input->post('userid')=="ALL") ? ($this->member->item('mem_level')>=100) ? "dhn" : $this->member->item('mem_userid') : $this->input->post('userid');
		$view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);
		
		$sql = "select * from cb_wt_adm_price";
		$price = $this->db->query($sql)->result();
		$view['adm_phn'] = array();
		$view['adm_sms'] = array();
		$view['adm_lms'] = array();
		$view['adm_mms'] = array();
		foreach($price as $p) {
			$view['adm_phn'][$p->adm_agent] = $p->adm_price_phn;
			$view['adm_sms'][$p->adm_agent] = $p->adm_price_sms;
			$view['adm_lms'][$p->adm_agent] = $p->adm_price_lms;
			$view['adm_mms'][$p->adm_agent] = $p->adm_price_mms;
		}

/*		$sql = "
			select a.mem_userid, a.mem_username from cb_member a inner join
			(	select mem_id, mrg_recommend_mem_id
				from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
					(select @pv := ".$this->member->item('mem_id').") initialisation
				where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)) b on a.mem_id=b.mem_id
			order by a.mem_username, a.mem_userid
		";*/
		$view['users'] = $this->Biz_model->get_child_member($this->member->item('mem_id'), 10);

		$mem = $this->Biz_model->get_member($view['param']['userid'], true);
		//echo '<pre> :: ';print_r($mem); exit;
		if(!$mem || $mem->mem_userid!=$view['param']['userid']) { show_404(); exit; }
		$view['member'] = $mem;

		$param = array($view['param']['startDate'].'-01 00:00:00', $view['param']['startDate'].'-31 23:59:59');
		if($mem->mem_level >= 100)			//-관리자인 경우 : 스윗트렉커로 지급해야할 금액을 표시
		{
			$summary = array();
			$sql = "
				select a.*, b.mem_userid, b.mem_username from cb_member_register a inner join cb_member b on a.mem_id=b.mem_id where a.mrg_recommend_mem_id='".$mem->mem_id."' order by a.mem_id
			";
			$child = $this->db->query($sql)->result();
			foreach($child as $ch) {
				$sql = "
					select '".$ch->mem_userid."' mem_offerid, '".$ch->mem_username."' mem_offername, a.mem_id, a.mem_userid, a.mem_username, a.mem_level, b.mrg_recommend_mem_id,
						0 cnt_at, 0 cnt_ft, 0 cnt_ft_img, 0 cnt_sms, 0 cnt_lms, 0 cnt_mms, 0 cnt_phn, 0 cnt_refund, 0 cnt_total,
						0 amt_at, 0 amt_ft, 0 amt_ft_img, 0 amt_sms, 0 amt_lms, 0 amt_mms, 0 amt_phn, 0 amt_amount, 0 amt_refund, 0 payback, 0 refund
					from cb_member a inner join
					 (select  mem_id, mrg_recommend_mem_id from cb_member_register
						where 
							FIND_IN_SET(mem_id, 
								(SELECT GROUP_CONCAT(memid SEPARATOR ',') memid FROM (
									SELECT @pv:=(SELECT GROUP_CONCAT(mem_id SEPARATOR ',') FROM cb_member_register 
									WHERE FIND_IN_SET(mrg_recommend_mem_id, @pv)) AS memid FROM cb_member_register 
									JOIN (SELECT @pv:=".$ch->mem_id.") tmp
								) a)
							) > 0 or mem_id=".$ch->mem_id."
					  ) b on a.mem_id=b.mem_id
					order by mem_level desc, mem_id asc
				";
/*				$sql = "
					select '".$ch->mem_userid."' mem_offerid, '".$ch->mem_username."' mem_offername, a.mem_id, a.mem_userid, a.mem_username, a.mem_level, b.mrg_recommend_mem_id,
						0 cnt_at, 0 cnt_ft, 0 cnt_ft_img, 0 cnt_sms, 0 cnt_lms, 0 cnt_mms, 0 cnt_phn, 0 cnt_refund, 0 cnt_total,
						0 amt_at, 0 amt_ft, 0 amt_ft_img, 0 amt_sms, 0 amt_lms, 0 amt_mms, 0 amt_phn, 0 amt_amount, 0 amt_refund, 0 payback, 0 refund
					from cb_member a inner join
					 (select  mem_id, mrg_recommend_mem_id
							  from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
									(select @pv := ".$ch->mem_id.") initialisation
							  where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
							union all select '".$ch->mem_id."' mem_id, '".$ch->mem_id."' mrg_recommend_mem_id
					  ) b on a.mem_id=b.mem_id
					order by mem_level desc, mem_id asc
				";*/
				$list = $this->db->query($sql)->result_array();
				for($n=0;$n<count($list);$n++) {
					$sql = "
						select 
							ifnull(sum(case when amt_kind='1' then amt_deposit else 0 end), 0) amt_charge,
							ifnull(sum(case when amt_kind='A' then 1 else 0 end), 0) cnt_at,
							ifnull(sum(case when amt_kind='F' then 1 else 0 end), 0) cnt_ft,
							ifnull(sum(case when amt_kind='I' then 1 else 0 end), 0) cnt_ft_img,
							ifnull(sum(case when amt_kind='S' then 1 else 0 end), 0) cnt_sms,
							ifnull(sum(case when amt_kind='L' then 1 else 0 end), 0) cnt_lms,
							ifnull(sum(case when amt_kind='M' then 1 else 0 end), 0) cnt_mms,
							ifnull(sum(case when amt_kind='P' then 1 else 0 end), 0) cnt_phn,
							ifnull(sum(case when amt_kind='D' then 1 else 0 end), 0) cnt_phn1,
							ifnull(sum(case when amt_kind='3' then 1 else 0 end), 0) cnt_refund,
							ifnull(sum(case when amt_kind='4' then 1 else 0 end), 0) cnt_refund1,
							ifnull(sum(case when amt_kind='A' then amt_amount else 0 end), 0) amt_at,
							ifnull(sum(case when amt_kind='F' then amt_amount else 0 end), 0) amt_ft,
							ifnull(sum(case when amt_kind='I' then amt_amount else 0 end), 0) amt_ft_img,
							ifnull(sum(case when amt_kind='S' then amt_amount else 0 end), 0) amt_sms,
							ifnull(sum(case when amt_kind='L' then amt_amount else 0 end), 0) amt_lms,
							ifnull(sum(case when amt_kind='M' then amt_amount else 0 end), 0) amt_mms,
							ifnull(sum(case when amt_kind='P' then amt_amount else 0 end), 0) amt_phn,
							ifnull(sum(case when amt_kind='D' then amt_admin else 0 end), 0) amt_phn1,
							ifnull(sum(case when amt_kind='3' then amt_amount else 0 end), 0) amt_refund,
							ifnull(sum(case when amt_kind='4' then amt_amount else 0 end), 0) amt_refund1,

							ifnull(sum(case when amt_kind='A' then amt_admin else 0 end), 0) adm_at,
							ifnull(sum(case when amt_kind='F' then amt_admin else 0 end), 0) adm_ft,
							ifnull(sum(case when amt_kind='I' then amt_admin else 0 end), 0) adm_ft_img,
							ifnull(sum(case when amt_kind='S' then amt_admin else 0 end), 0) adm_sms,
							ifnull(sum(case when amt_kind='L' then amt_admin else 0 end), 0) adm_lms,
							ifnull(sum(case when amt_kind='M' then amt_admin else 0 end), 0) adm_mms,
							ifnull(sum(case when amt_kind='P' then amt_admin else 0 end), 0) adm_phn,
							ifnull(sum(case when amt_kind='D' then amt_admin else 0 end), 0) adm_phn1,
							ifnull(sum(case when amt_kind='3' then amt_admin else 0 end), 0) adm_refund,
							ifnull(sum(case when amt_kind='4' then amt_admin else 0 end), 0) adm_refund1,

							ifnull(sum(case when amt_kind>='A' and amt_kind<='Z' then 1 else 0 end), 0) cnt_total,
							ifnull(sum(case when amt_kind>='A' and amt_kind<='Z' then amt_amount else 0 end), 0) amt_amount,
							ifnull(sum(case when amt_kind>='A' and amt_kind<='Z' then amt_payback else 0 end), 0) payback,
							ifnull(sum(case when amt_kind='3' then amt_payback else 0 end), 0) refund,
							ifnull(sum(case when amt_kind='4' then amt_payback else 0 end), 0) refund1
						from cb_amt_".$list[$n]['mem_userid']." where amt_datetime between ? and ?
					";
					$tr = $this->db->query($sql, $param)->row();
					if($tr) {
						$list[$n]['amt_charge'] = $tr->amt_charge;
						$list[$n]['cnt_at'] = $tr->cnt_at;
						$list[$n]['cnt_ft'] = $tr->cnt_ft;
						$list[$n]['cnt_ft_img'] = $tr->cnt_ft_img;
						$list[$n]['cnt_sms'] = $tr->cnt_sms;
						$list[$n]['cnt_lms'] = $tr->cnt_lms;
						$list[$n]['cnt_mms'] = $tr->cnt_mms;
						$list[$n]['cnt_phn'] = $tr->cnt_phn;
						$list[$n]['amt_at'] = $tr->amt_at;
						$list[$n]['amt_ft'] = $tr->amt_ft;
						$list[$n]['amt_ft_img'] = $tr->amt_ft_img;
						$list[$n]['amt_sms'] = $tr->amt_sms;
						$list[$n]['amt_lms'] = $tr->amt_lms;
						$list[$n]['amt_mms'] = $tr->amt_mms;
						$list[$n]['amt_phn'] = $tr->amt_phn;
						$list[$n]['amt_phn1'] = $tr->amt_phn1;
						$list[$n]['cnt_total'] = $tr->cnt_total;
						$list[$n]['amt_amount'] = $tr->amt_amount;
						$list[$n]['payback'] = $tr->payback;

						$list[$n]['amt_refund'] = $tr->amt_refund;
						$list[$n]['amt_refund1'] = $tr->amt_refund1;
						$list[$n]['cnt_refund'] = $tr->cnt_refund;
						$list[$n]['refund'] = $tr->refund;

						$list[$n]['adm_at'] = $tr->adm_at;
						$list[$n]['adm_ft'] = $tr->adm_ft;
						$list[$n]['adm_ft_img'] = $tr->adm_ft_img;
						$list[$n]['adm_sms'] = $tr->adm_sms;
						$list[$n]['adm_lms'] = $tr->adm_lms;
						$list[$n]['adm_mms'] = $tr->adm_mms;
						$list[$n]['adm_phn'] = $tr->adm_phn;
						$list[$n]['adm_phn1'] = $tr->adm_phn1;
						$list[$n]['adm_refund'] = $tr->adm_refund;
						$list[$n]['adm_refund1'] = $tr->adm_refund1;
					}
				}
				array_push($summary, $list);
			}
			$view['list'] = $summary;
		} else if($mem->mem_level >= 10)	//-중간관리자의 경우 : 엔드유저 발송에 대한 수당 금액 표시(메시지 종류도 구분)
		{
			$sql = "
				select a.mem_id, a.mem_userid, a.mem_username, a.mem_level, b.mrg_recommend_mem_id,
					0 cnt_at, 0 cnt_ft, 0 cnt_ft_img, 0 cnt_sms, 0 cnt_lms, 0 cnt_mms, 0 cnt_phn, 0 cnt_total, 0 cnt_refund,
					0 amt_at, 0 amt_ft, 0 amt_ft_img, 0 amt_sms, 0 amt_lms, 0 amt_mms, 0 amt_phn, 0 amt_amount, 0 amt_refund, 0 payback, 0 refund
				from cb_member a inner join
				 (select  mem_id, mrg_recommend_mem_id from cb_member_register
					where 
						FIND_IN_SET(mem_id, 
							(SELECT GROUP_CONCAT(memid SEPARATOR ',') memid FROM (
								SELECT @pv:=(SELECT GROUP_CONCAT(mem_id SEPARATOR ',') FROM cb_member_register 
								WHERE FIND_IN_SET(mrg_recommend_mem_id, @pv)) AS memid FROM cb_member_register 
								JOIN (SELECT @pv:=".$mem->mem_id.") tmp
							) a)
						) > 0 or mem_id=".$mem->mem_id."
				  ) b on a.mem_id=b.mem_id
				order by mem_level desc, mem_id asc
			";
/*			$sql = "
				select a.mem_id, a.mem_userid, a.mem_username, a.mem_level, b.mrg_recommend_mem_id,
					0 cnt_at, 0 cnt_ft, 0 cnt_ft_img, 0 cnt_sms, 0 cnt_lms, 0 cnt_mms, 0 cnt_phn, 0 cnt_total, 0 cnt_refund,
					0 amt_at, 0 amt_ft, 0 amt_ft_img, 0 amt_sms, 0 amt_lms, 0 amt_mms, 0 amt_phn, 0 amt_amount, 0 amt_refund, 0 payback, 0 refund
				from cb_member a inner join
				 (select  mem_id, mrg_recommend_mem_id
						  from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
								(select @pv := ".$mem->mem_id.") initialisation
						  where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
						union all select '".$mem->mem_id."' mem_id, '".$mem->mem_id."' mrg_recommend_mem_id
				  ) b on a.mem_id=b.mem_id
				order by mem_level desc, mem_id asc
			";*/
			$list = $this->db->query($sql)->result_array();
			for($n=0;$n<count($list);$n++) {
				$sql = "
						select 
							ifnull(sum(case when amt_kind='1' then amt_deposit else 0 end), 0) amt_charge,
							ifnull(sum(case when amt_kind='A' then 1 else 0 end), 0) cnt_at,
							ifnull(sum(case when amt_kind='F' then 1 else 0 end), 0) cnt_ft,
							ifnull(sum(case when amt_kind='I' then 1 else 0 end), 0) cnt_ft_img,
							ifnull(sum(case when amt_kind='S' then 1 else 0 end), 0) cnt_sms,
							ifnull(sum(case when amt_kind='L' then 1 else 0 end), 0) cnt_lms,
							ifnull(sum(case when amt_kind='M' then 1 else 0 end), 0) cnt_mms,
							ifnull(sum(case when amt_kind='P' then 1 else 0 end), 0) cnt_phn,
							ifnull(sum(case when amt_kind='D' then 1 else 0 end), 0) cnt_phn1,
							ifnull(sum(case when amt_kind='3' then 1 else 0 end), 0) cnt_refund,
							ifnull(sum(case when amt_kind='4' then 1 else 0 end), 0) cnt_refund1,
							ifnull(sum(case when amt_kind='A' then amt_amount else 0 end), 0) amt_at,
							ifnull(sum(case when amt_kind='F' then amt_amount else 0 end), 0) amt_ft,
							ifnull(sum(case when amt_kind='I' then amt_amount else 0 end), 0) amt_ft_img,
							ifnull(sum(case when amt_kind='S' then amt_amount else 0 end), 0) amt_sms,
							ifnull(sum(case when amt_kind='L' then amt_amount else 0 end), 0) amt_lms,
							ifnull(sum(case when amt_kind='M' then amt_amount else 0 end), 0) amt_mms,
							ifnull(sum(case when amt_kind='P' then amt_amount else 0 end), 0) amt_phn,
							ifnull(sum(case when amt_kind='D' then amt_amount else 0 end), 0) amt_phn1,
							ifnull(sum(case when amt_kind='3' then amt_amount else 0 end), 0) amt_refund,
							ifnull(sum(case when amt_kind='4' then amt_amount else 0 end), 0) amt_refund1,

							ifnull(sum(case when amt_kind='A' then amt_admin else 0 end), 0) adm_at,
							ifnull(sum(case when amt_kind='F' then amt_admin else 0 end), 0) adm_ft,
							ifnull(sum(case when amt_kind='I' then amt_admin else 0 end), 0) adm_ft_img,
							ifnull(sum(case when amt_kind='S' then amt_admin else 0 end), 0) adm_sms,
							ifnull(sum(case when amt_kind='L' then amt_admin else 0 end), 0) adm_lms,
							ifnull(sum(case when amt_kind='M' then amt_admin else 0 end), 0) adm_mms,
							ifnull(sum(case when amt_kind='P' then amt_admin else 0 end), 0) adm_phn,
							ifnull(sum(case when amt_kind='D' then amt_admin else 0 end), 0) adm_phn1,
							ifnull(sum(case when amt_kind='3' then amt_admin else 0 end), 0) adm_refund,
							ifnull(sum(case when amt_kind='4' then amt_admin else 0 end), 0) adm_refund1,

							ifnull(sum(case when amt_kind>='A' and amt_kind<='Z' then 1 else 0 end), 0) cnt_total,
							ifnull(sum(case when amt_kind>='A' and amt_kind<='Z' then amt_amount else 0 end), 0) amt_amount,
							ifnull(sum(case when amt_kind>='A' and amt_kind<='Z' then amt_payback else 0 end), 0) payback,
							ifnull(sum(case when amt_kind='3' then amt_payback else 0 end), 0) refund,
							ifnull(sum(case when amt_kind='4' then amt_payback else 0 end), 0) refund1
						from cb_amt_".$list[$n]['mem_userid']." where amt_datetime between ? and ?
				";
				$tr = $this->db->query($sql, $param)->row();
				if($tr) {
					$list[$n]['amt_charge'] = $tr->amt_charge;
					$list[$n]['cnt_at'] = $tr->cnt_at;
					$list[$n]['cnt_ft'] = $tr->cnt_ft;
					$list[$n]['cnt_ft_img'] = $tr->cnt_ft_img;
					$list[$n]['cnt_sms'] = $tr->cnt_sms;
					$list[$n]['cnt_lms'] = $tr->cnt_lms;
					$list[$n]['cnt_mms'] = $tr->cnt_mms;
					$list[$n]['cnt_phn'] = $tr->cnt_phn;
					$list[$n]['amt_at'] = $tr->amt_at;
					$list[$n]['amt_ft'] = $tr->amt_ft;
					$list[$n]['amt_ft_img'] = $tr->amt_ft_img;
					$list[$n]['amt_sms'] = $tr->amt_sms;
					$list[$n]['amt_lms'] = $tr->amt_lms;
					$list[$n]['amt_mms'] = $tr->amt_mms;
					$list[$n]['amt_phn'] = $tr->amt_phn;
					$list[$n]['amt_phn1'] = $tr->amt_phn1;
					$list[$n]['cnt_total'] = $tr->cnt_total;
					$list[$n]['amt_amount'] = $tr->amt_amount;
					$list[$n]['payback'] = $tr->payback;

					$list[$n]['amt_refund'] = $tr->amt_refund;
					$list[$n]['amt_refund1'] = $tr->amt_refund1;
					$list[$n]['cnt_refund'] = $tr->cnt_refund;
					$list[$n]['refund'] = $tr->refund;

					$list[$n]['adm_at'] = $tr->adm_at;
					$list[$n]['adm_ft'] = $tr->adm_ft;
					$list[$n]['adm_ft_img'] = $tr->adm_ft_img;
					$list[$n]['adm_sms'] = $tr->adm_sms;
					$list[$n]['adm_lms'] = $tr->adm_lms;
					$list[$n]['adm_mms'] = $tr->adm_mms;
					$list[$n]['adm_phn'] = $tr->adm_phn;
					$list[$n]['adm_phn1'] = $tr->adm_phn1;
					$list[$n]['adm_refund'] = $tr->adm_refund;
					$list[$n]['adm_refund1'] = $tr->adm_refund1;
				}
			}
			$view['list'] = $list;
		} else									//-엔드유저의 경우 : 발송량과 금액을 표시
		{
			$sql = "
				select  
					ifnull(sum(case when amt_kind='1' then 1 else 0 end), 0) cnt_charge,
					ifnull(sum(case when amt_kind='1' then amt_deposit else 0 end), 0) amt_charge,
					ifnull(sum(case when amt_kind='A' then 1 else 0 end), 0) cnt_at,
					ifnull(sum(case when amt_kind='F' then 1 else 0 end), 0) cnt_ft,
					ifnull(sum(case when amt_kind='I' then 1 else 0 end), 0) cnt_ft_img,
					ifnull(sum(case when amt_kind='S' then 1 else 0 end), 0) cnt_sms,
					ifnull(sum(case when amt_kind='L' then 1 else 0 end), 0) cnt_lms,
					ifnull(sum(case when amt_kind='M' then 1 else 0 end), 0) cnt_mms,
					ifnull(sum(case when amt_kind='P' then 1 else 0 end), 0) cnt_phn,
					ifnull(sum(case when amt_kind='D' then 1 else 0 end), 0) cnt_phn1,
					ifnull(sum(case when amt_kind='3' then 1 else 0 end), 0) cnt_refund,
					ifnull(sum(case when amt_kind='4' then 1 else 0 end), 0) cnt_refund1,
					ifnull(sum(case when amt_kind='A' then amt_amount else 0 end), 0) amt_at,
					ifnull(sum(case when amt_kind='F' then amt_amount else 0 end), 0) amt_ft,
					ifnull(sum(case when amt_kind='I' then amt_amount else 0 end), 0) amt_ft_img,
					ifnull(sum(case when amt_kind='S' then amt_amount else 0 end), 0) amt_sms,
					ifnull(sum(case when amt_kind='L' then amt_amount else 0 end), 0) amt_lms,
					ifnull(sum(case when amt_kind='M' then amt_amount else 0 end), 0) amt_mms,
					ifnull(sum(case when amt_kind='P' then amt_amount else 0 end), 0) amt_phn,
					ifnull(sum(case when amt_kind='D' then amt_amount else 0 end), 0) amt_phn1,
					ifnull(sum(case when amt_kind='3' then amt_amount else 0 end), 0) amt_refund,
					ifnull(sum(case when amt_kind='4' then amt_amount else 0 end), 0) amt_refund1,

					ifnull(sum(case when amt_kind='A' then amt_admin else 0 end), 0) adm_at,
					ifnull(sum(case when amt_kind='F' then amt_admin else 0 end), 0) adm_ft,
					ifnull(sum(case when amt_kind='I' then amt_admin else 0 end), 0) adm_ft_img,
					ifnull(sum(case when amt_kind='S' then amt_admin else 0 end), 0) adm_sms,
					ifnull(sum(case when amt_kind='L' then amt_admin else 0 end), 0) adm_lms,
					ifnull(sum(case when amt_kind='M' then amt_admin else 0 end), 0) adm_mms,
					ifnull(sum(case when amt_kind='P' then amt_admin else 0 end), 0) adm_phn,
					ifnull(sum(case when amt_kind='D' then amt_admin else 0 end), 0) adm_phn1,
					ifnull(sum(case when amt_kind='3' then amt_admin else 0 end), 0) adm_refund,
					ifnull(sum(case when amt_kind='4' then amt_admin else 0 end), 0) adm_refund1,

					ifnull(sum(case when amt_kind>='A' and amt_kind<='Z' then 1 else 0 end), 0) cnt_total,
					ifnull(sum(case when amt_kind>='A' and amt_kind<='Z' then amt_amount else 0 end), 0) amt_amount,
					ifnull(sum(case when amt_kind>='A' and amt_kind<='Z' then amt_payback else 0 end), 0) payback,
					ifnull(sum(case when amt_kind='3' then amt_payback else 0 end), 0) refund,
					ifnull(sum(case when amt_kind='4' then amt_payback else 0 end), 0) refund1
				from cb_amt_".$view['param']['userid']." where amt_datetime between ? and ?
			";
			$list = $this->db->query($sql, $param)->row();
			$view['list'] = $list;
			$view['coin'] = $this->Biz_model->getAbleCoin($mem->mem_id, $mem->mem_userid);
		}
		
		$view['view']['canonical'] = site_url();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		* 레이아웃을 정의합니다
		*/
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/manager/summary',
			'layout' => 'layout',
			'skin' => (($mem->mem_level >= 100) ? 'admin' : (($mem->mem_level >= 10) ? "offer" : "index")),
			'layout_dir' => $this->cbconfig->item('layout_main'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_main'),
			'use_sidebar' => $this->cbconfig->item('sidebar_main'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
			'skin_dir' => $this->cbconfig->item('skin_main'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_main'),
			'page_title' => $page_title,
			'meta_description' => $meta_description,
			'meta_keywords' => $meta_keywords,
			'meta_author' => $meta_author,
			'page_name' => $page_name,
		);

		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function download()
	{
		$value = json_decode($this->input->post('value'));

		// 라이브러리를 로드한다.
		$this->load->library('excel');

		// 시트를 지정한다.
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Sheet1');

		// 필드명을 기록한다.
		// 글꼴 및 정렬
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A1:J1');

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A3:J3');

		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);

		$this->excel->getActiveSheet()->mergeCells('A1:J1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "발 송 내 역  정 산");

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '업체명');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '충전');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '알림톡');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '친구톡(텍스트)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '친구톡(이미지)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, '폰문자');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, '환불건수');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, '발송합계');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, '사용금액');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 3, '페이백');

		$row = 4;
		foreach($value as $val) {
			// 데이터를 읽어서 순차로 기록한다.
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->vendor);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->charge);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val->at);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->ft);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val->ft_img);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val->phn);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val->refund);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val->total);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val->amount);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $val->back);
			$row++;
		}

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A4:A'.$row);
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'B4:J'.$row);

		// 파일로 내보낸다. 파일명은 'filename.xls' 이다.
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="summary.xls"');
		header('Cache-Control: max-age=0');

		// Excel5 포맷(excel 2003 .XLS file)으로 저장한다. 
		// 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		// 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
		$objWriter->save('php://output');
	}
}
?>