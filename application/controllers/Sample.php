<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sample extends CB_Controller {
	/**
	* ���� �ε��մϴ�
	*/
	protected $models = array('Board', 'Homepage');

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

	/**
	* 메인 페이지
	*/
	public function index()
	{
        redirect("/");
		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'sample',
			'layout' => 'layout_home',
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

	/**
	* 메인 페이지
	*/
	public function main()
	{
		if($this->member->is_member()) {
			// �̺�Ʈ ���̺귯���� �ε��մϴ�

		    $this->session->set_userdata('isNotice', 'Y');
		    // 2019.02.25 디자인 수정으로 로그인 후 경로 변경
		    //redirect('/biz/sender/send/friend');
		    //redirect('/dhnbiz/sender/send/message');
		    redirect('/home');

			$eventname = 'event_main_index';
			$this->load->event($eventname);

			$view = array();
			$view['view'] = array();

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
				'path' => 'biz',
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
		else
		{
			$CI =& get_instance();
			$ls_actions = $this->input->get_post("actions");
			$ls_referer = $this->input->get_post("referer");
			$data['base_url'] = $CI->config->item('base_url');
			$data['referer'] = $ls_referer;
			$data['mode'] = $ls_actions;

			//get_post_list($limit = '', $offset = '', $where = '', $category_id = '', $orderby = '', $sfield = '', $skeyword = '', $sop = 'OR')
			$this->load->model('Post_model');
			$data['notice_post'] = $this->Post_model->get_post_list(5, '', array('brd_id' => $this->board->item_key('brd_id', 'notice_01'), 'post_del' => '0'), '', 'post_id', 'desc', '', '');
			if (isset($data['notice_post']['list']) && is_array($data['notice_post']['list'])) {
				foreach ($data['notice_post']['list'] as $key => $val) {
					 $brd_key = $this->board->item_id('brd_key', element('brd_id', $val));
					 $data['notice_post']['list'][$key]['post_url'] = post_url($brd_key, element('post_id', $val));
					 $data['notice_post']['list'][$key]['display_name'] = display_username(
						  element('post_userid', $val),
						  element('post_nickname', $val)
					 );
				}
			}

			$data['pds_post'] = $this->Post_model->get_post_list(5, '', array('brd_id' => $this->board->item_key('brd_id', 'data'), 'post_del' => '0'), '', 'post_id', 'desc', '', '');
			if (isset($data['pds_post']['list']) && is_array($data['pds_post']['list'])) {
				foreach ($data['pds_post']['list'] as $key => $val) {
					 $brd_key = $this->board->item_id('brd_key', element('brd_id', $val));
					 $data['pds_post']['list'][$key]['post_url'] = post_url($brd_key, element('post_id', $val));
					 $data['pds_post']['list'][$key]['display_name'] = display_username(
						  element('post_userid', $val),
						  element('post_nickname', $val)
					 );
				}
			}
			//$this->load->view('homepage/intro',$data);
			//log_message("ERROR", "/homepage/index");
			redirect('login');
		}
	}

	public function alimtalk()
	{
		$CI =& get_instance();
		$ls_actions = $this->input->get_post("actions");
		$ls_referer = $this->input->get_post("referer");
		$data['base_url'] = $CI->config->item('base_url');
		$data['referer'] = $ls_referer;
		$data['mode'] = $ls_actions;

		$this->load->view('homepage/alimtalk',$data);
	}

	public function friendtalk()
	{
		$CI =& get_instance();
		$ls_actions = $this->input->get_post("actions");
		$ls_referer = $this->input->get_post("referer");
		$data['base_url'] = $CI->config->item('base_url');
		$data['referer'] = $ls_referer;
		$data['mode'] = $ls_actions;

		$this->load->view('homepage/friendtalk',$data);
	}

	public function sangdamtalk()
	{
		$CI =& get_instance();
		$ls_actions = $this->input->get_post("actions");
		$ls_referer = $this->input->get_post("referer");
		$data['base_url'] = $CI->config->item('base_url');
		$data['referer'] = $ls_referer;
		$data['mode'] = $ls_actions;

		$this->load->view('homepage/sangdamtalk',$data);
	}

	public function sms()
	{
		$CI =& get_instance();
		$ls_actions = $this->input->get_post("actions");
		$ls_referer = $this->input->get_post("referer");
		$data['base_url'] = $CI->config->item('base_url');
		$data['referer'] = $ls_referer;
		$data['mode'] = $ls_actions;

		$this->load->view('homepage/sms',$data);
	}

	public function company()
	{
		$CI =& get_instance();
		$ls_actions = $this->input->get_post("actions");
		$ls_referer = $this->input->get_post("referer");
		$data['base_url'] = $CI->config->item('base_url');
		$data['referer'] = $ls_referer;
		$data['mode'] = $ls_actions;

		$this->load->view('homepage/company',$data);
	}

	public function faq()
	{
		$CI =& get_instance();
		$ls_actions = $this->input->get_post("actions");
		$ls_referer = $this->input->get_post("referer");
		$data['base_url'] = $CI->config->item('base_url');
		$data['referer'] = $ls_referer;
		$data['mode'] = $ls_actions;

		$this->load->view('homepage/faq',$data);
	}

	public function marttalk()
	{
		$CI =& get_instance();
		$ls_actions = $this->input->get_post("actions");
		$ls_referer = $this->input->get_post("referer");
		$data['base_url'] = $CI->config->item('base_url');
		$data['referer'] = $ls_referer;
		$data['mode'] = $ls_actions;

		$this->load->view('homepage/marttalk',$data);
	}

	public function mainpage()
	{

	}

	public function cs()
	{
		$CI =& get_instance();
		$ls_actions = $this->input->get_post("actions");
		$ls_referer = $this->input->get_post("referer");
		$data['base_url'] = $CI->config->item('base_url');
		$data['referer'] = $ls_referer;
		$data['mode'] = $ls_actions;

		$this->load->view('homepage/cs',$data);
	}

	//실시간 계약현황 조회
	public function contract_list(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 실시간 계약현황 조회 (랜덤 50건)");
		$sql = "
		SELECT
			  mem_id /* 회원번호 */
			, REPLACE(mem_username, RIGHT(mem_username,2), '**') AS mem_username /* 업체명 (끝 2자리 * 처리) */
			, mem_full_care_yn /* 풀케어 사용여부 */
		FROM cb_member_sample
		WHERE mem_useyn = 'Y' /* 사용여부 */
		AND mem_id NOT IN (2, 3, 15, 93, 107, 108, 110, 406, 413, 464, 542, 645, 651, 654, 655, 694) /* 테스트 계정 제외 */
		ORDER BY RAND() /* 랜덤조회 */
		LIMIT 50 /* 조회건수 */ ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 실시간 계약현황 조회 (랜덤 50건) SQL : ". $sql);
		$result = $this->db->query($sql)->result();
		$json = json_encode($result, JSON_UNESCAPED_UNICODE);
		$json = str_replace('null', '""', $json);
		header('Content-Type: application/json');
		echo $json;
	}

}
?>
