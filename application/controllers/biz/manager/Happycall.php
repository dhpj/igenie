<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Happycall extends CB_Controller {
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

	public function index()
	{
        if($this->member->item('mem_level') < 150){
            redirect('/home');
        }
        $view['perpage'] = 20;
        $view['page'] = !empty($this->input->get("page"))? $this->input->get("page"): 1;
        $view['mid'] = !empty($this->input->get('mid')) ? $this->input->get('mid') : '0';
        $view['start_date'] = !empty($this->input->get("start_date"))? $this->input->get("start_date"): date('Y-m-d', strtotime('-7 days'));
        $view['end_date'] = !empty($this->input->get("end_date"))? $this->input->get("end_date"): date('Y-m-d');
        $view['search_type'] = !empty($this->input->get("search_type"))? $this->input->get("search_type"): 1;
        $view['search_str'] = !empty($this->input->get("search_str"))? $this->input->get("search_str"): '';

        $view['modal_list'] = $this->db
            ->select('mem_id, mem_username')
            ->from('cb_member')
            // ->where('mem_useyn', 'Y')
            ->order_by('mem_username')
            ->get()->result();

        $sql = '
            select
                a.mem_id
              , a.mem_username
            from
                cb_member a
            inner join(
                select
                    hc_mem_id
                from
                    cb_happycall
                group by
                    hc_mem_id
            ) b on a.mem_id = b.hc_mem_id
        ';

        $view['mem_list'] = $this->db->query($sql)->result();

        $where = array(
            '1' => 1
          , 'a.hc_useyn' => 'Y'
          , 'a.hc_create_datetime >=' => $view['start_date'] . ' 00:00:00'
          , 'a.hc_create_datetime <=' => $view['end_date'] . ' 23:59:59'
        );

        if (!empty($view['mid'])){
            $where['a.hc_mem_id'] = $view['mid'];
        }

        if (!empty($view['search_str'])){
            if ($view['search_type'] == '1'){
                $where['b.mem_username like'] = '%' . $view['search_str'] . '%';
            } else if ($view['search_type'] == '2'){
                $where['a.hc_writer like'] = '%' . $view['search_str'] . '%';
            }
        }

        $view['list'] = $this->db
            ->select('
                *
            ')
            ->from('cb_happycall a')
            ->join('cb_member b', 'a.hc_mem_id = b.mem_id', 'left')
            ->where($where)
            ->order_by('hc_id', 'desc')
            ->limit($view['perpage'], ($view['page'] - 1) * $view['perpage'])->get()->result();

        $view['total_cnt'] = $this->db
            ->select('
                COUNT(*) AS cnt
            ')
            ->from('cb_happycall a')
            ->join('cb_member b', 'a.hc_mem_id = b.mem_id', 'left')
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
			'path' => 'biz/manager/happycall',
			'layout' => 'layout',
			'skin' => 'list',
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

    public function del_history()
	{
        $result = array();

        $id = $this->input->post('id');
        $this->db
            ->set('hc_useyn', 'N')
            ->where('hc_id', $id)
            ->update('cb_happycall');

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
	}

	public function update_fix()
	{
	    $result = array();

	    $id = $this->input->post('id');
	    $fix = $this->input->post('flag');
	    $this->db
	    ->set('hc_fix', $fix)
	    ->set('hc_fix_date', $fix == 'N' ? null : date('Y-m-d H:i:s'))
	    ->where('hc_id', $id)
	    ->update('cb_happycall');

	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

    public function ins_history(){
        $result = array();

        $mid = $this->input->post('mid');
        $category = $this->input->post('category');
        $writer = $this->input->post('writer');
        $contents = $this->input->post('contents');

        $this->db
            ->set('hc_wt_mem_id', $this->member->item('mem_id'))
            ->set('hc_mem_id', $mid)
            ->set('hc_category', $category)
            ->set('hc_writer', $writer)
            ->set('hc_contents', $contents)
            ->insert('cb_happycall');

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
    }

    public function get_history(){
        $result = array();

        $mid = $this->input->post('mid');
        $flag = $this->input->post('flag');
        if (!$flag){
            $result['list'] = $this->db
                ->select('*')
                ->from('cb_happycall')
                ->where(array(
                    'hc_mem_id' => $mid
                  , 'hc_useyn' => 'Y'
                ))
                ->order_by('hc_id', 'desc')
                ->get()->result();
        } else{
            $result['list'] = $this->db
            ->select('*')
            ->from('cb_happycall')
            ->where(array(
                'hc_mem_id' => $mid
                , 'hc_useyn' => 'Y'
            ))
            ->order_by('hc_fix', 'asc')
            ->order_by('hc_fix_date', 'desc')
            ->order_by('hc_create_datetime', 'desc')
            ->get()->result();
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
    }
}
?>
