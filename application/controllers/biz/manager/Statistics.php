<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends CB_Controller {
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
		$this->load->library(array('querystring', 'statisticslib', 'funn'));
	}

	public function index()
	{
        if($this->member->item('mem_level') < 150){
            redirect('/home');
        }

        $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/manager/statistics',
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

    public function companylist()
	{
        if($this->member->item('mem_level') < 150){
            redirect('/home');
        }

        $view['searchStr'] = $this->input->get('searchStr');
        $where = '';
        if (!empty($view['searchStr'])){
            $where = ' and a.mem_username like \'%' . $view['searchStr'] . '%\' ';
        }

        $para = $this->set_date('', '');

        $sql = "
            select
                *
            from
                cb_member a
            where
                a.mem_id in (
                    select
                        mem_id
                    from
                        cb_orders b
                    where
                        creation_date between '" . $para['sdate'] . "' and '" . $para['edate'] . "'
                    group by
                        b.mem_id
                ) $where
            order by
                a.mem_username
        ";
        $view['list'] = $this->db->query($sql)->result();
        $view['total_cnt'] = count($view['list']);

        $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/manager/statistics',
			'layout' => 'layout',
			'skin' => 'companylist',
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

    public function detail($mem_id)
	{
        if($this->member->item('mem_level') < 150){
            redirect('/home');
        }

        $view['mem_id'] = $mem_id;

        $view['mem_info'] = $this->db
            ->select('*')
            ->from('cb_member a')
            ->where('mem_id', $mem_id)
            ->get()->row();

        $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/manager/statistics',
			'layout' => 'layout',
			'skin' => 'detail',
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

    public function get_method(){
        $result = array();
        $para = array();
        $para = $this->set_date($this->input->post('edate'), $this->input->post('sdate'));

        $result['edate'] = substr($para['edate'], 0, 7);
        $result['sdate'] = substr($para['sdate'], 0, 7);

        if (!empty($this->input->post('mem_id'))){
            $para['mem_id'] = $this->input->post('mem_id');
        }

        $para['dtype'] = $this->input->post('dtype');

        $list = $this->db->query($this->statisticslib->payment_graph($para))->result();

        $result['mdate'] = array();
        $result['spot'] = array();
        $result['lcl'] = array();
        $result['acc'] = array();
        $result['elec'] = array();
        $result['money'] = array();
        $result['m_spot'] = array();
        $result['m_lcl'] = array();
        $result['m_acc'] = array();
        $result['m_elec'] = array();

        foreach($list as $a){
            array_push($result['mdate'], $a->a_mdate);
            array_push($result['spot'], $a->spot);
            array_push($result['lcl'], $a->lcl);
            array_push($result['acc'], $a->acc);
            array_push($result['elec'], $a->elec);
            array_push($result['money'], $a->money);
            array_push($result['m_spot'], $a->m_spot);
            array_push($result['m_lcl'], $a->m_lcl);
            array_push($result['m_acc'], $a->m_acc);
            array_push($result['m_elec'], $a->m_elec);
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
    }

    public function get_method2(){
        $result = array();
        $para = array();
        $para = $this->set_date($this->input->post('edate'), $this->input->post('sdate'));

        $result['edate'] = substr($para['edate'], 0, 7) . " 23:59:59";
        $result['sdate'] = substr($para['sdate'], 0, 7) . " 00:00:00";

        if (!empty($this->input->post('mem_id'))){
            $para['mem_id'] = $this->input->post('mem_id');
        }

        $list = $this->db->query($this->statisticslib->payment_graph2($para))->result();

        $result['mdate'] = array();
        $result['spot'] = array();
        $result['lcl'] = array();
        $result['acc'] = array();
        $result['elec'] = array();
        $result['money'] = array();
        $result['m_spot'] = array();
        $result['m_lcl'] = array();
        $result['m_acc'] = array();
        $result['m_elec'] = array();

        foreach($list as $a){
            array_push($result['mdate'], $a->a_mdate . '째');
            array_push($result['spot'], $a->spot);
            array_push($result['lcl'], $a->lcl);
            array_push($result['acc'], $a->acc);
            array_push($result['elec'], $a->elec);
            array_push($result['money'], $a->money);
            array_push($result['m_spot'], $a->m_spot);
            array_push($result['m_lcl'], $a->m_lcl);
            array_push($result['m_acc'], $a->m_acc);
            array_push($result['m_elec'], $a->m_elec);
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
    }

    public function get_cycle(){
        $result = array();
        $para = array();

        $para = $this->set_date($this->input->post('edate'), $this->input->post('sdate'));

        if (!empty($this->input->post('mem_id'))){
            $para['mem_id'] = $this->input->post('mem_id');
        }

        $list = $this->db->query($this->statisticslib->cycle_graph($para))->result();

        $result['edate'] = substr($para['edate'], 0, 7);
        $result['sdate'] = substr($para['sdate'], 0, 7);

        $result['ticks'] = array();
        $result['cnt'] = array();

        foreach($list as $key => $a){
            array_push($result['ticks'], round($a->ticks) . ' ~ ' . round(($a->ticks + 9)) . '건');
            array_push($result['cnt'], $a->cnt);
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
    }

    public function get_amount(){
        $result = array();
        $para = array();

        $para = $this->set_date($this->input->post('edate'), $this->input->post('sdate'));

        if (!empty($this->input->post('mem_id'))){
            $para['mem_id'] = $this->input->post('mem_id');
        }

        $list = $this->db->query($this->statisticslib->amount_graph($para))->result();

        $result['amt'] = array();
        $result['cnt'] = array();

        foreach($list as $key => $a){
            if (round($a->amt) > 0){
                $plbl = number_format($a->amt/10000);
                $flbl = number_format($a->amt/10000 + 1) . '만';
            } else {
                $plbl = 0;
                $flbl = '1만';
            }
            array_push($result['amt'], $plbl . ' ~ ' . $flbl);
            array_push($result['cnt'], $a->cnt);
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
    }

    private function set_date($edate, $sdate){
        $para = array();
        if (!empty($edate) && !empty($sdate)){
            $para['edate'] = $edate . '-31 23:59:59';
            $para['sdate'] = $sdate . '-01 00:00:00';
        } else {
            $para['edate'] = date('Y-m-d H:i:s');
            $para['sdate'] = date('Y-m-d H:i:s', strtotime('-2 years'));
        }
        return $para;
    }

    public function get_marker(){
        $result = array();

        $para = $this->set_date('', '');

        $where = array();
        $where['creation_date >='] = $para['sdate'];
        $where['creation_date <='] = $para['edate'];
        $where['trim(postcode) !='] = '';
        $result['list'] = $this->db
            ->select('
                left(addr1, 2) as addr
              , COUNT(*) AS cnt
            ')
            ->from('cb_orders')
            ->where($where)
            ->where_in('status', array(0,1,2,3))
            ->group_by('left(addr1,2)')
            ->having('cnt >= 10')
            ->order_by('addr1', 'ASC')
            ->get()->result();

        $result['cnt'] = count($result['list']);
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
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
}
?>
