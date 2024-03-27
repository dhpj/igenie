<?php
class Template extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz_dhn');

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
        // 이벤트 라이브러리를 로딩합니다
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 10;

        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['pf_ynm'] = $this->input->post('pf_ynm');							//한국산업인력공단
        $view['param']['inspect_status'] = $this->input->post('inspect_status');	//REG
        $view['param']['tmpl_status'] = $this->input->post('tmpl_status');			//R
        $view['param']['comment_status'] = $this->input->post('comment_status');	//INQ
        $view['param']['tmpl_search'] = $this->input->post('tmpl_search');			//pf_ynm
        $view['param']['searchStr'] = $this->input->post('searchStr');					//한국
        $param = array($this->member->item('mem_id'));
        $where = " tpl_use='Y' and tpl_mem_id=? and tpl_inspect_status='APR' ";
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

        // log_message("ERROR", "total_rows : ".$page_cfg['total_rows']);

        $view['total_count'] = $page_cfg['total_rows'];
        // log_message("ERROR", "total_count : ".$view['view']['total_count']);

        $sql = "
			select a.*, b.spf_friend, b.spf_key_type
			from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
			where ".$where." order by tpl_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        // log_message("ERROR", "sql : ".$this->member->item('mem_id'));
        // log_message("ERROR", "sql : ".$sql);

        $view['list'] = $this->db->query($sql, $param)->result();
        $view['profile'] = $this->Biz_dhn_model->get_partner_profile();
        $view['view']['canonical'] = site_url();



        $this->load->view('biz/dhnsender/send/template', $view);
    }
}
?>
