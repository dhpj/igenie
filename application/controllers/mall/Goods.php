<?php
class Goods extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	//protected $models = array('Board', 'Biz');

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

	//쇼핑몰관리 > 상품관리 메인
	public function index()
	{
	    if($this->member->is_member() == false) { //로그인이 안된 경우
            redirect('login');
        }
        
        $layoutconfig = array(
			'path' => 'mall/goods',
			'layout' => 'layout',
			'skin' => 'main',
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