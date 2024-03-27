<?php
class Profile extends CB_Controller {
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
		$view = array();
		$view['view'] = array();
		$view['perpage'] = 20;

		$view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
		$param = array($this->member->item('mem_id'), $this->member->item('mem_id'), $this->member->item('mem_id'));
		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $this->Biz_model->get_table_count("cb_wt_send_profile", "spf_key<>'' and spf_use='Y' and spf_mem_id=?", array($this->member->item('mem_id')));
		/*
		$page_cfg['total_rows'] = $this->Biz_model->get_table_count("cb_wt_send_profile a inner join 
				(select  mem_id, mrg_recommend_mem_id
				from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
					(select @pv := ?) initialisation
				where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
				union select ? mem_id, ? mrg_recommend_mem_id
				) b on a.spf_mem_id = b.mem_id", "a.spf_key<>'' and a.spf_use='Y'", $param);
		*/
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg); 
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();

		$sql = "
			select *
			from cb_wt_send_profile 
			where spf_key<>'' and spf_use='Y' and spf_mem_id=? order by spf_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		$view['list'] = $this->db->query($sql, array($this->member->item('mem_id')))->result();
		/*
		$sql = "
			select a.*
			from cb_wt_send_profile a inner join 
				(select  mem_id, mrg_recommend_mem_id
				from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
					(select @pv := ?) initialisation
				where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
				union select ? mem_id, ? mrg_recommend_mem_id
				) b on a.spf_mem_id = b.mem_id
			where a.spf_key<>'' and a.spf_use='Y' order by a.spf_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		$view['list'] = $this->db->query($sql, $param)->result();
		*/
		$view['view']['canonical'] = site_url();

		$this->load->view('biz/sender/send/profile',$view);
	}
}
?>