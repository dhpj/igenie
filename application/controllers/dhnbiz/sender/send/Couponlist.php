<?php
class Couponlist extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board', 'Biz_dhn');

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
		// 이벤트 라이브러리를 로딩합니다
		$view = array();
		$view['view'] = array();
		$view['perpage'] = 5;

		$view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;

		$where = "";
		$param = array( );
		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
        //ib플래그
		$sql = "SELECT COUNT(1) as cnt
		          FROM ( select cc.cc_title
						 		,b.spf_friend
								,b.spf_key as tpl_profile_key
								,cc.cc_tpl_code AS tpl_code
								,cc.cc_tpl_id AS tpl_id
								,'AT' as cc_type
								,cc.cc_msg AS tpl_contents
								,cc.cc_start_date
								,cc.cc_end_date
                                ,cc.cc_idx
                                ,cc.cc_creation_date
									from ".config_item('ib_template')." a inner join ".config_item('ib_profile')." b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
														inner join cb_coupon cc on cc.cc_tpl_id = a.tpl_id and cc.cc_status='P' and cc.cc_used = 'Y' and exists(select 1 from cb_coupon_list ccl where ccl.cl_cc_id = cc.cc_idx and ccl.cl_phn is null)
									where  tpl_use='Y' and date_add(cc_end_date, interval +1 day) > now() and cc_mem_id = '".$this->member->item('mem_id')."'
						UNION all
						SELECT cc.cc_title
								,b.spf_friend
								,b.spf_key as tpl_profile_key
								,cc.cc_tpl_code AS tpl_code
								,cc.cc_tpl_id AS tpl_id
								,'FT' as cc_type
								,cc.cc_msg AS tpl_contents
								,cc.cc_start_date
								,cc.cc_end_date
                                ,cc.cc_idx
                                ,cc.cc_creation_date
						FROM ".config_item('ib_profile')." b
						INNER JOIN cb_coupon cc ON cc.cc_mem_id = b.spf_mem_id AND cc.cc_status='P' AND cc.cc_used = 'Y' AND b.spf_use = 'Y' AND EXISTS(
						SELECT 1
						FROM cb_coupon_list ccl
						WHERE ccl.cl_cc_id = cc.cc_idx AND ccl.cl_phn IS NULL)
						WHERE DATE_ADD(cc_end_date, INTERVAL +1 DAY) > NOW() AND cc_mem_id = '".$this->member->item('mem_id')."'
						) a";
//log_message("ERROR", $slq);
		$page_cfg['total_rows'] = $this->db->query($sql)->row()->cnt;
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
        //ib플래그
		$sql = "SELECT *
		FROM ( select cc.cc_title
					   ,b.spf_friend
					  ,b.spf_key as tpl_profile_key
					  ,cc.cc_tpl_code AS tpl_code
					  ,cc.cc_tpl_id AS tpl_id
					  ,'AT' as cc_type
					  ,cc.cc_msg AS tpl_contents
					  ,cc.cc_start_date
					  ,cc.cc_end_date
                      ,cc.cc_idx
                      ,cc.cc_creation_date
						  from ".config_item('ib_template')." a inner join ".config_item('ib_profile')." b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
											  inner join cb_coupon cc on cc.cc_tpl_id = a.tpl_id and cc.cc_status='P' and cc.cc_used = 'Y' and exists(select 1 from cb_coupon_list ccl where ccl.cl_cc_id = cc.cc_idx and ccl.cl_phn is null)
						  where  tpl_use='Y' and date_add(cc_end_date, interval +1 day) > now() and cc_mem_id = '".$this->member->item('mem_id')."'
			  UNION all
			  SELECT cc.cc_title
					  ,b.spf_friend
					  ,b.spf_key as tpl_profile_key
					  ,cc.cc_tpl_code AS tpl_code
					  ,cc.cc_tpl_id AS tpl_id
					  ,'FT' as cc_type
					  ,cc.cc_msg AS tpl_contents
					  ,cc.cc_start_date
					  ,cc.cc_end_date
                      ,cc.cc_idx
                      ,cc.cc_creation_date
			  FROM ".config_item('ib_profile')." b
			  INNER JOIN cb_coupon cc ON cc.cc_mem_id = b.spf_mem_id AND cc.cc_status='P' AND cc.cc_used = 'Y' AND b.spf_use = 'Y' AND EXISTS(
			  SELECT 1
			  FROM cb_coupon_list ccl
			  WHERE ccl.cl_cc_id = cc.cc_idx AND ccl.cl_phn IS NULL)
			  WHERE DATE_ADD(cc_end_date, INTERVAL +1 DAY) > NOW() AND cc_mem_id = '".$this->member->item('mem_id')."'
			  ) a order by cc_start_date desc,cc_creation_date desc  limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		//log_message("ERROR", $sql);
		$view['list'] = $this->db->query($sql, $param)->result();
		$view['profile'] = $this->Biz_dhn_model->get_partner_profile();
		$view['view']['canonical'] = site_url();

		$this->load->view('biz/dhnsender/send/couponlist', $view);
		log_message("ERROR", "aa");
	}


}
?>
