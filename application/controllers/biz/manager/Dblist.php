<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dblist extends CB_Controller {
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
        if($this->member->item('mem_level') < 150){
            redirect('/home');
        }

        $view['searchStr'] = $this->input->get('searchStr');
        $view['duration'] = $this->input->get('duration');
        $where = array(
            'mem_useyn' => 'Y'
          , 'mem_username LIKE ' => '%' . $view['searchStr'] . '%'
        );
        if ($view['duration'] == 'open'){
            $where['mem_parent_tel_mark_flag'] = 'Y';
        } else if ($view['duration'] == 'close'){
            $where['mem_parent_tel_mark_flag'] = 'N';
        }
        $view['perpage'] = 20;
        $view['page'] = !empty($this->input->get("page"))? $this->input->get("page"): 1;

        $view['list'] = $this->db
            ->select('
                mem_id
              , mem_username
              , mem_parent_tel_mark_flag
            ')
            ->from('cb_member')
            ->where($where)
            ->order_by('mem_username', 'ASC')
            ->limit($view['perpage'], ($view['page'] - 1) * $view['perpage'])->get()->result();

        $view['total_cnt'] = $this->db
            ->select('
                COUNT(*) AS cnt
            ')
            ->from('cb_member')
            ->where($where)->get()->row()->cnt;

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_cnt'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['page']);
        $view['page_html'] = $this->pagination->create_links();

        $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/manager/dblist',
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

	public function check_pwd()
	{
        $result = array();
        $result['code'] = 0;
        if ($this->input->post('pwd') == config_item('db_pwd')){
            $result['code'] = 1;
        } else {
            $result['msg'] = '비밀번호가 틀렸습니다.';
        }
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
	}

    public function set_dbstatus()
	{
        $result = array();

        $mem_id = $this->input->post('mem_id');
        $status = $this->input->post('status');
        $manager = $this->input->post('manager');
        $reason = $this->input->post('reason');

        $this->db
            ->set('mem_parent_tel_mark_flag', $status)
            ->where('mem_id', $mem_id)
            ->update('cb_member');

        $this->db
            ->set('tml_mem_id', $mem_id)
            ->set('tml_manager', $manager)
            ->set('tml_reason', $reason)
            ->set('tml_flag', $status)
            ->insert('cb_tel_mark_log');

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
	}

    public function get_addr(){
        $headers = array(
            "Content-Type:application/x-www-form-urlencoded;charset=utf-8",
        );

        $parameters = array(
            "currentPage"   => '1',
            "countPerPage" => '10',
            "resultType"   => 'json',
            "confmKey"  => 'U01TX0FVVEgyMDIxMDgxMTA5NDMyNjExMTUxMDI=',
            "keyword"   => '충북 청주시 청원구 오창읍 중심상업1로 8-34',
        );

        $curl = curl_init();
        $api_url = 'https://www.juso.go.kr/addrlink/addrLinkApi.do';
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $buffer = curl_exec ($curl);
        $cinfo = curl_getinfo($curl);
        curl_close($curl);

        $res = json_decode($buffer, true);
        log_message('error', $buffer);
    }
}
?>
