<?php
class Monthly extends CB_Controller {
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
	    $this->load->model("Biz_free_model");
	    $view = array();
	    $view['view'] = array();

	    // �̺�Ʈ�� �����ϸ� �����մϴ�
	    $view['view']['event']['before'] = Events::trigger('before', $eventname);

	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > this->input->post('branch') : ". $this->input->post('branch'));
	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > this->input->post('salesperson') : ". $this->input->post('salesperson'));


	    $view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m');
	    $view['param']['pf_key'] = $this->input->post('pf_key');
	    $view['param']['branch'] = ($this->input->post('branch')) ? $this->input->post('branch') : 'ALL';
	    $view['param']['salesperson'] = ($this->input->post('salesperson')) ? $this->input->post('salesperson') : 'ALL';
	    $view['param']['orderby'] = ($this->input->post('orderby')) ? $this->input->post('orderby') : '0';
	    
	    $view['param']['managerType'] = $this->input->post('managerType') ? $this->input->post('managerType') : "S";
	    
	    if ($this->member->item('mem_level')==17 || $this->member->item('mem_level')>=50) {
	        $view['param']['zero_include'] = ($this->input->post('zero_include')) ? $this->input->post('zero_include') : 'YES';
	    } else {
	        $view['param']['zero_include'] = ($this->input->post('zero_include')) ? $this->input->post('zero_include') : 'NO';
	    }

	    $sql = "select a.mrg_recommend_mem_id as parent_id, (select mem_username from cb_member where mem_id = a.mrg_recommend_mem_id) as parent_name
                from cb_member_register a where a.mem_id = ".$this->member->item("mem_id");
	    $parent = $this->db->query($sql)->row();
	    $view['param']['parent_id'] = $parent->parent_id;         // 로그인 계정 상위 계정 mem_id
	    $view['param']['parent_name'] = $parent->parent_name;     // 로그인 계정 상위 계정 mem_username

	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view['param']['startDate'] : ". $view['param']['startDate']);
	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view['param']['pf_key'] : ". $view['param']['pf_key']);
	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view['param']['branch'] : ". $view['param']['branch']);
	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view['param']['salesperson'] : ". $view['param']['salesperson']);
	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view['param']['zero_include'] : ". $view['param']['zero_include']);
	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view['param']['parent_id'] : ". $view['param']['parent_id']);
	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view['param']['parent_name'] : ". $view['param']['parent_name']);
	    log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view['param']['orderby'] : ". $view['param']['orderby']);

	    // $view['list'] = $this->Biz_model->get_monthly_report( $view['param']);
	    if($this->member->item('mem_level')==17) {
	        
	        $sql = "select count(1) as cnt from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
	        $sales_cnt = $this->db->query($sql)->row()->cnt;
	        
	        if ($sales_cnt > 0) {
	            $sql = "select * from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
	            //$sales_id = $this->db->query($sql)->row()->ds_id;
	            $view['param']['sales_id'] = $this->db->query($sql)->row()->ds_id;
	            //$view['list'] = $this->Biz_model->get_daily_report_new( $view['param']);
	            // $view['list'] = $this->Biz_model->get_send_report('day', $view['param']['startDate'], $view['param']['endDate'], $view['param']['pf_key']);
	            $view['list'] = $this->Biz_model->get_sales_monthly_report_new($view['param']);
	        } else {
	            $view['list'] = $this->Biz_model->get_monthly_report_new( $view['param']);
	        }
	        
	    } else {
	        $view['list'] = $this->Biz_model->get_monthly_report_new( $view['param']);
	    }
	    // $view['list'] = $this->Biz_model->get_monthly_report_new( $view['param']);
	    
	    $view['list_cnt'] = count($view['list']);
	    //log_message("ERROR", "Daily list Count : ".count($view['list']));
	    
	    $view['company'] = $this->Biz_model->get_member_from_profile_key($view['param']['pf_key']);
	    //$view['profile'] = $this->Biz_model->get_partner_profile();
	    if($this->member->item('mem_level')==17){
	        // $view['profile'] = $this->Biz_model->get_partners($this->member->item("mem_id"));
	        $view['profile'] = $this->Biz_model->get_sales_partners($this->member->item("mem_id"), $view['param']['managerType']);
	    } else {
	        $view['profile'] = $this->Biz_model->get_partners($this->member->item("mem_id"));
	    }
	    // $view['profile'] = $this->Biz_model->get_partners($this->member->item("mem_id"));

        if($this->member->item('mem_id') == '962'){
            $where = " AND a.ds_dbo_id = '6' ";
            $sql = "select * from dhn_branch_office where dbo_id='6'";
        }else{
		    $sql = "select * from dhn_branch_office where dbo_part='BO'";
        }
	    $view['branch'] = $this->db->query($sql)->result();
	    //$view['branch'] = $this->Biz_model->test();
	    $sql = "select a.ds_id, a.ds_dbo_id, a.ds_name,
                    CASE a.ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장'
                        WHEN 'MA' THEN '과장' WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END ds_position, b.dbo_id, b.dbo_name
                from dhn_salesperson a left join dhn_branch_office b on a.ds_dbo_id=b.dbo_id where ds_task_state<>'L'".$where." order by b.dbo_id, a.ds_id, a.ds_name";
	    $view['salesperson'] = $this->db->query($sql)->result();

	    //$view['test'] = $this->Biz_model->test();

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
	        'skin' => 'monthly',
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

	private function index__()
	{
		// �̺�Ʈ ���̺귯���� �ε��մϴ�
		$eventname = 'event_main_index';
		$this->load->event($eventname);
		$this->load->model("Biz_free_model");
		$view = array();
		$view['view'] = array();

		// �̺�Ʈ�� �����ϸ� �����մϴ�
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m');
		$view['param']['pf_key'] = $this->input->post('pf_key');
		$view['list'] = $this->Biz_model->get_monthly_report( $view['param']);

		$view['company'] = $this->Biz_model->get_member_from_profile_key($view['param']['pf_key']);
		//$view['profile'] = $this->Biz_model->get_partner_profile();
		$view['profile'] = $this->Biz_model->get_partners($this->member->item("mem_id"));

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
			'skin' => 'monthly',
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
