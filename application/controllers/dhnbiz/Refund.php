<?php
class Refund extends CB_Controller {
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
	}

	public function lists()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();
		$view['perpage'] = 20;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
		$view['param']['search_company_nm'] = $this->input->post('search_company_nm');
		$view['param']['search_stat'] = $this->input->post('search_stat');
		$param = array();
		$where = " 1=1 ";
		if($view['param']['search_stat']!="all" && $view['param']['search_stat']) { $where.=" and ref_stat=? "; array_push($param, $view['param']['search_stat']); }
		if($view['param']['search_company_nm']) { $where.=" and mem_userid like ? "; array_push($param, '%'.$view['param']['search_company_nm'].'%'); }
		$view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_refund a inner join cb_member b on a.ref_mem_id=b.mem_id", $where, $param);

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg); 
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();

		$sql = "
			select a.*, b.mem_username
			from cb_wt_refund a inner join cb_member b on a.ref_mem_id=b.mem_id
			where ".$where." order by ref_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		$view['list'] = $this->db->query($sql, $param)->result();

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
			'path' => 'biz/refund',
			'layout' => 'layout',
			'skin' => 'lists',
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

	public function remove()
	{
		$selRefund = $this->input->post('selRefund');
		if(count($selRefund) > 0) {
			foreach($selRefund as $id) {
				$rs = $this->db->query("select a.*, b.mem_userid from cb_wt_refund a inner join cb_member b on a.ref_mem_id=b.mem_id where a.ref_id=?", array($id))->row();
				if($rs && $rs->ref_stat=="-") {
					$this->db->query("update cb_wt_refund set ref_stat='N', ref_appr_id=?, ref_appr_datetime=?, ref_end_datetime=? where ref_id=?", array($this->member->item('mem_userid'), cdate('Y-m-d H:i:s'), cdate('Y-m-d H:i:s'), $id));
					if ($this->db->error()['code'] < 1) {
						$data = array();
						$data['amt_datetime'] = cdate('Y-m-d H:i:s');
						$data['amt_kind'] = "1";
						$data['amt_amount'] = $rs->ref_amount;
						$data['amt_memo'] = "환불취소";
						$data['amt_reason'] = $id;
						$this->db->insert("cb_amt_".$rs->mem_userid, $data);
					}
				}
			}
		}
		Header("Location: /biz/refund/lists");
	}

	public function appr()
	{
		$selRefund = $this->input->post('selRefund');
		if(count($selRefund) > 0) {
			foreach($selRefund as $id) {
				$rs = $this->db->query("select a.*, b.mem_userid from cb_wt_refund a inner join cb_member b on a.ref_mem_id=b.mem_id where a.ref_id=?", array($id))->row();
				if($rs && $rs->ref_stat=="-") {
					$this->db->query("update cb_wt_refund set ref_stat='Y', ref_appr_id=?, ref_appr_datetime=?, ref_end_datetime=? where ref_id=?", array($this->member->item('mem_userid'), cdate('Y-m-d H:i:s'), cdate('Y-m-d H:i:s'), $id));
				}
			}
		}
		Header("Location: /biz/refund/lists");
	}
}
?>