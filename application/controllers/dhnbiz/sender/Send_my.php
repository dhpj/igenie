<?php
class Send extends CB_Controller {
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
	}

	public function friend()
	{
		// �̺�Ʈ ���̺귯���� �ε��մϴ�
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();
		$receivers = $this->input->post('receivers');
		$view['receivers'] = ($receivers) ? $this->Biz_dhn_model->get_customer_from_id($this->member->item('mem_userid'), $receivers) : '';

		// �̺�Ʈ�� �����ϸ� �����մϴ�
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

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
			'path' => 'biz/dhnsender/send',
			'layout' => 'layout',
			'skin' => 'friend',
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

	public function profile()
	{
		$view = array();
		$view['view'] = array();
		$view['perpage'] = 20;

		$view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
		$param = array($this->member->item('mem_id'), $this->member->item('mem_id'), $this->member->item('mem_id'));
		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $this->Biz_dhn_model->get_table_count("cb_wt_send_profile a inner join 
				(select  mem_id, mrg_recommend_mem_id
				from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
					(select @pv := ?) initialisation
				where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
				union select ? mem_id, ? mrg_recommend_mem_id
				) b on a.spf_mem_id = b.mem_id", "a.spf_key<>'' and a.spf_use='Y'", $param);
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg); 
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();

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
		$view['view']['canonical'] = site_url();

		$this->load->view('biz/dhnsender/send/profile',$view);
	}

	public function select_profile()
	{
		$profileKey = $this->input->post('profileKey');
		$view = array();
		$view['view'] = array();
		if(!$profileKey) {
			$view['rs'] = $this->db->query("select * from cb_wt_send_profile where spf_mem_id=? and spf_appr_id<>'' order by spf_appr_datetime desc limit 1", array($this->member->item('mem_id')))->row();
		} else {
			$view['rs'] = $this->db->query("select * from cb_wt_send_profile where spf_key=? and spf_appr_id<>''", array($profileKey))->row();
		}
		$this->load->view('biz/dhnsender/send/select_profile',$view);
	}

	public function template()
	{
		// �̺�Ʈ ���̺귯���� �ε��մϴ�
		$view = array();
		$view['view'] = array();
		$view['perpage'] = 10;

		$view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
		$view['param']['pf_ynm'] = $this->input->post('pf_ynm');							//�ѱ�����η°���
		$view['param']['inspect_status'] = $this->input->post('inspect_status');	//REG
		$view['param']['tmpl_status'] = $this->input->post('tmpl_status');			//R
		$view['param']['comment_status'] = $this->input->post('comment_status');	//INQ
		$view['param']['tmpl_search'] = $this->input->post('tmpl_search');			//pf_ynm
		$view['param']['searchStr'] = $this->input->post('searchStr');					//�ѱ�
		$param = array();
		$where = " tpl_use='Y' ";
		if($view['param']['pf_ynm']!="ALL" && $view['param']['pf_ynm']) { $where.=" and tpl_profile_key=? "; array_push($param, $view['param']['pf_ynm']); }
		if($view['param']['inspect_status']!="ALL" && $view['param']['inspect_status']) { $where.=" and tpl_inspect_status=? "; array_push($param, $view['param']['inspect_status']); }
		if($view['param']['tmpl_status']!="ALL" && $view['param']['tmpl_status']) { $where.=" and tpl_status=? "; array_push($param, $view['param']['tmpl_status']); }
		if($view['param']['comment_status']!="ALL" && $view['param']['comment_status']) { $where.=" and tpl_comment_status=? "; array_push($param, $view['param']['comment_status']); }
		if($view['param']['tmpl_search']!="ALL" && $view['param']['tmpl_search'] && $view['param']['searchStr']) { $where.=" and tpl_".$view['param']['tmpl_search']." like ? "; array_push($param, '%'.$view['param']['searchStr'].'%'); }

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $this->Biz_dhn_model->get_table_count("cb_wt_template_dhn", $where, $param);
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg); 
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();

		$sql = "
			select a.*, b.spf_friend, b.spf_key_type
			from cb_wt_template_dhn a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
			where ".$where." order by tpl_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		$view['list'] = $this->db->query($sql, $param)->result();
		$view['profile'] = $this->Biz_dhn_model->get_partner_profile();
		$view['view']['canonical'] = site_url();

		$this->load->view('biz/dhnsender/send/template', $view);
	}

	public function select_template()
	{
		$tmp_code = $this->input->post('tmp_code');
		$tmp_profile = $this->input->post('tmp_profile');
		$view = array();
		$view['view'] = array();
		if($tmp_profile && $tmp_code) {
			$view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template_dhn a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where tpl_mem_id=? and tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
		} else {
			$view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template_dhn a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where tpl_mem_id=? order by tpl_id desc limit 1", array($this->member->item('mem_id')))->row();
		}
		$this->load->view('biz/dhnsender/send/select_template',$view);
	}
}
?>