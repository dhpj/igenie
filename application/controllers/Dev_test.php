<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dev_test extends CB_Controller
{
    private $partner_key = "51d3ab9bc25dceeee44c6f472fd16691c8b49d65";
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board');

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


    /**
     * 전체 메인 페이지입니다
     */
    public function index(){

		$layoutconfig = array(
			'path' => 'devtest',
			'layout' => 'layout_home',
			'skin' => 'world',
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

    public function status_templet(){
        $url = "https://bzm-center.kakao.com/api/v2/" . $this->partner_key . "/alimtalk/template";
        $param = "?senderKey=c03b09ba2a86fc2984b940d462265dd4dbddb105&templateCode=210901-4";

        $ch = curl_init();
        log_message('error', $url.$param);
        $options = array(
            CURLOPT_URL => $url.$param,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPGET => TRUE,
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.0)",
            CURLOPT_REFERER => "",
            CURLOPT_TIMEOUT => 30
        );
        curl_setopt_array($ch, $options);
        $buffer = curl_exec ($ch);
        $cinfo = curl_getinfo($ch);
        curl_close($ch);
        log_message('error', $buffer);
        $category = json_decode( $buffer , true );

        if ($cinfo['http_code'] == 200)
        {
            return $category;
        }
        return "";
    }

}
