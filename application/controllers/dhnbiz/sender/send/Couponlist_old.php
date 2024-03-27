<?php
class Couponlist extends CB_Controller {
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
		// 이벤트 라이브러리를 로딩합니다
		$view = array();
		$view['view'] = array();
		$view['perpage'] = 5;

		$view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;

		$where = " tpl_use='Y' and date_add(cc_end_date, interval +1 day) > now() and cc_mem_id = '".$this->member->item('mem_id')."'";
		$param = array( );
		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $this->Biz_model->get_table_count("cb_wt_template_dhn a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
			                                                                          inner join cb_coupon cc on cc.cc_tpl_id = a.tpl_id and cc.cc_status='P' and cc.cc_used = 'Y' and exists(select 1 from cb_coupon_list ccl where ccl.cl_cc_id = cc.cc_idx and ccl.cl_phn is null)", $where, $param);
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg); 
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();

		$sql = "
			select a.*, b.spf_friend, b.spf_key_type, cc.*
			from cb_wt_template_dhn a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
			                      inner join cb_coupon cc on cc.cc_tpl_id = a.tpl_id and cc.cc_status='P' and cc.cc_used = 'Y' and exists(select 1 from cb_coupon_list ccl where ccl.cl_cc_id = cc.cc_idx and ccl.cl_phn is null)
			where ".$where." order by cc.cc_start_date desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		$view['list'] = $this->db->query($sql, $param)->result();
		$view['profile'] = $this->Biz_model->get_partner_profile();
		$view['view']['canonical'] = site_url();

		$this->load->view('biz/sender/send/couponlist', $view);
		log_message("ERROR", "aa");
	}
}
?>