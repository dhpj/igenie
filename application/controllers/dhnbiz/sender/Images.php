<?php
class Images extends CB_Controller {
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
		// �̺�Ʈ ���̺귯���� �ε��մϴ�
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();
		$CI =& get_instance();
		$view['base_url'] = $CI->config->item('base_url');

		$view['perpage'] = 8;
		$view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $this->Biz_model->get_table_count("cb_img_".$this->member->item('mem_userid'), "1=1 and img_wide <> 'Y'", "");
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg); 
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();

		$sql = "
			select *
			from cb_img_".$this->member->item('mem_userid')."
            where img_wide <> 'Y' 
			order by img_id desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		$view['list'] = $this->db->query($sql)->result();
		$view['view']['canonical'] = site_url();

		$this->load->view('biz/sender/images', $view);
	}
}
?>