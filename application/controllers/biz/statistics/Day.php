<?php
class Day extends CB_Controller {
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

		// �̺�Ʈ�� �����ϸ� �����մϴ�
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m-d', strtotime(date('Y-m-d')."-2week"));
		$view['param']['endDate'] = ($this->input->post('endDate')) ? $this->input->post('endDate') : date('Y-m-d');
		$view['param']['pf_key'] = $this->input->post('pf_key');
		$view['param']['managerType'] = $this->input->post('managerType') ? $this->input->post('managerType') : "S";
		
		if($this->member->item('mem_level')==17) {
            
		    $sql = "select count(1) as cnt from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
            $sales_cnt = $this->db->query($sql)->row()->cnt;
            
            if ($sales_cnt > 0) {
                $sql = "select * from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
                $sales_id = $this->db->query($sql)->row()->ds_id;
                // $view['list'] = $this->Biz_model->get_send_report('day', $view['param']['startDate'], $view['param']['endDate'], $view['param']['pf_key']);
                $view['list'] = $this->Biz_model->get_sales_send_report('day', $view['param']['startDate'], $view['param']['endDate'], $view['param']['pf_key'], $sales_id, $view['param']['managerType']);
            } else {
                $view['list'] = $this->Biz_model->get_send_report('day', $view['param']['startDate'], $view['param']['endDate'], $view['param']['pf_key']);
            }
            
		} else {
            $view['list'] = $this->Biz_model->get_send_report('day', $view['param']['startDate'], $view['param']['endDate'], $view['param']['pf_key']);
		}
		
		$view['company'] = $this->Biz_model->get_member_profile_key_dhn($view['param']['pf_key']);
		
		if($this->member->item('mem_level')==17){
		    // $view['profile'] = $this->Biz_model->get_partners($this->member->item("mem_id"));
		    $view['profile'] = $this->Biz_model->get_sales_partners($this->member->item("mem_id"), $view['param']['managerType']);
		} else {
            $view['profile'] = $this->Biz_model->get_partners($this->member->item("mem_id"));
		}
		

		$view['view']['canonical'] = site_url();

		// �̺�Ʈ�� �����ϸ� �����մϴ�
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		* ���̾ƿ��� �����մϴ�
		*/
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/statistics',
			'layout' => 'layout',
			'skin' => 'day',
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
}
?>
