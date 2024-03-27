<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CB_Controller {
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
		$mode = $this->input->post('actions');
		if($mode=="save") { $this->save(); exit; }

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$sql = "select * from cb_wt_adm_price";
		$view['price'] = $this->db->query($sql)->result();

		$sql = "select * from cb_wt_setting limit 1";
		$view['rs'] = $this->db->query($sql)->row();

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
			'path' => 'biz/manager/setting',
			'layout' => 'layout',
			'skin' => 'index',
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

	function save()
	{
		$data = array();
		$data['wst_price_at'] = str_replace(",", "", $this->input->post('price_at'));
		$data['wst_price_ft'] = str_replace(",", "", $this->input->post('price_ft'));
		$data['wst_price_ft_img'] = str_replace(",", "", $this->input->post('price_ft_img'));
		$data['wst_price_grs'] = str_replace(",", "", $this->input->post('price_grs'));
		$data['wst_price_grs_sms'] = str_replace(",", "", $this->input->post('price_grs_sms'));
		$data['wst_price_nas'] = str_replace(",", "", $this->input->post('price_nas'));
		$data['wst_price_nas_sms'] = str_replace(",", "", $this->input->post('price_nas_sms'));
		//$data['wst_price_015'] = str_replace(",", "", $this->input->post('price_015'));
		//$data['wst_price_phn'] = str_replace(",", "", $this->input->post('price_phn'));
		//$data['wst_price_dooit'] = str_replace(",", "", $this->input->post('price_dooit'));
		//$data['wst_price_sms'] = str_replace(",", "", $this->input->post('price_sms'));
		//$data['wst_price_lms'] = str_replace(",", "", $this->input->post('price_lms'));
		//$data['wst_price_mms'] = str_replace(",", "", $this->input->post('price_mms'));
		$data['wst_weight1'] = str_replace(",", "", $this->input->post('rate1'));
		$data['wst_weight2'] = str_replace(",", "", $this->input->post('rate2'));
		$data['wst_weight3'] = str_replace(",", "", $this->input->post('rate3'));
		$data['wst_weight4'] = str_replace(",", "", $this->input->post('rate4'));
		$data['wst_weight5'] = str_replace(",", "", $this->input->post('rate5'));
		$data['wst_weight6'] = str_replace(",", "", $this->input->post('rate6'));
		$data['wst_weight7'] = str_replace(",", "", $this->input->post('rate7'));
		$data['wst_price_grs_mms'] = str_replace(",", "", $this->input->post('price_grs_mms'));
		$data['wst_price_nas_mms'] = str_replace(",", "", $this->input->post('price_nas_mms'));
		$data['wst_price_grs_biz'] = str_replace(",", "", $this->input->post('price_grs_biz'));
		$data['wst_price_smt'] = str_replace(",", "", $this->input->post('price_smt'));
		$data['wst_price_smt_sms'] = str_replace(",", "", $this->input->post('price_smt_sms'));
		$data['wst_price_smt_mms'] = str_replace(",", "", $this->input->post('price_smt_mms'));
		
		$this->db->update("cb_wt_setting", $data);
		if($this->db->error()['code'] > 0) {
			echo "<script type='text/javascript'>alert('저장 오류 입니다.');history.back(-1);</script>";
		} else {
			echo "<script type='text/javascript'>alert('기본 설정 정보가 저장되었습니다.');document.location.href='/biz/manager/setting';</script>";
		}
	}

	public function adm_price()
	{
		$agent = $this->input->post('agent');
		$data = array();
		$data['adm_company'] = $this->input->post('company');
		$data['adm_price_phn'] = str_replace(",", "", $this->input->post('adm_phn'));
		$data['adm_price_sms'] = str_replace(",", "", $this->input->post('adm_sms'));
		$data['adm_price_lms'] = str_replace(",", "", $this->input->post('adm_lms'));
		$data['adm_price_mms'] = str_replace(",", "", $this->input->post('adm_mms'));
		$this->db->update("cb_wt_adm_price", $data, array("adm_agent"=>$agent));
		header('Content-Type: application/json');
		if($this->db->error()['code'] > 0) {
			echo '{"success": false}';
		} else {
			echo '{"success": true}';
		}
	}
}
?>