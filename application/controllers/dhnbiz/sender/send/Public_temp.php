<?php
class Public_temp extends CB_Controller {
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
        $view['perpage'] = 6;
        
        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        // 		$view['param']['pf_ynm'] = $this->input->post('pf_ynm');							//한국산업인력공단
        // 		$view['param']['inspect_status'] = $this->input->post('inspect_status');	//REG
        // 		$view['param']['tmpl_status'] = $this->input->post('tmpl_status');			//R
        // 		$view['param']['comment_status'] = $this->input->post('comment_status');	//INQ
        // 		$view['param']['tmpl_search'] = $this->input->post('tmpl_search');			//pf_ynm
        // 		$view['param']['searchStr'] = $this->input->post('searchStr');					//한국
        // 		$param = array($this->member->item('mem_id'));
        $view['param']['group_key'] = $this->input->post('group_key');
        $view['param']['part_id'] = $this->input->post('part_id');
        
        $param = array();
        
        $groupSql = "select count(a.spg_p_pf_key) cnt
            from cb_wt_send_profile_group_dhn a
            left join cb_wt_send_profile_dhn b on a.spg_p_pf_key = b.spf_key
            where a.spg_mem_id = ".$this->member->item('mem_id');
        //log_message("ERROR", "Pulbic Template groupSql cnt sql : ".$groupSql);
        $view['group_cnt'] = $this->db->query($groupSql)->row()->cnt;
        //log_message("ERROR", "Pulbic Template groupSql cnt : ".$view['group_cnt'] );
        
        $groupSql = "select a.spg_p_pf_key, b.spf_friend
		  from cb_wt_send_profile_group_dhn a
		  left join cb_wt_send_profile_dhn b on a.spg_p_pf_key = b.spf_key
		  where a.spg_mem_id = ".$this->member->item('mem_id')."
		  order by a.spg_p_order ASC";
        //log_message("ERROR", "Pulbic Template groupSql : ".$groupSql);
        $view['group_list'] = $this->db->query($groupSql)->result();
        
        if(empty($view['param']['group_key'])) {
            $temp = $view['group_list'][0];
            $groupKey = $temp->spg_p_pf_key;
            $view['param']['group_key'] = $groupKey;
        }
        
        $partSql = "select count(tp_part_id) cnt
            from cb_wt_template_part_dhn
            where tp_profile_key = '".$view['param']['group_key']."' and tp_used='Y'
        ";
        //log_message("ERROR", "Pulbic Template partSql cnt : ".$partSql);
        $view['part_cnt'] = $this->db->query($partSql)->row()->cnt;
        //log_message("ERROR", "Pulbic Template partSql cnt : ".$view['part_cnt']);
        $partSql = "select tp_part_id, tp_part_name
            from dhn.cb_wt_template_part_dhn
            where tp_profile_key = '".$view['param']['group_key']."' and tp_used='Y'
            order by tp_order, tp_part_id
        ";
        //log_message("ERROR", "Pulbic Template partSql : ".$partSql);
        $view['part_list'] = $this->db->query($partSql)->result();
        
        if (empty($view['param']['part_id']) || $this->input->post('groupChange') == "Y") {
            $view['param']['part_id'] = "ALL";
            // 		    $temp = $view['part_list'][0];
            // 		    $partId = $temp->tp_part_id;
            // 		    $view['param']['part_id'] = $partId;
            //log_message("ERROR", "firstMem_id : ".$firstMem_id);
        }
        
        // 		$where = " tpl_use='Y' and exists (select 1 from cb_wt_send_profile_group where spg_pf_key = tpl_profile_key)  and tpl_inspect_status='APR'  ";
        $where = " a.tpl_use='Y' and exists (select 1 from cb_wt_send_profile_group_dhn where spg_pf_key = a.tpl_profile_key)   ";
        $where .= " and a.tpl_profile_key='".$view['param']['group_key']."' ";
        if ($view['param']['part_id'] != "ALL") {
            $where .= " and a.tpl_type_group='".$view['param']['part_id']."' ";
        }
        $where .= "  and tpl_inspect_status='APR' and a.tpl_status <> 'S' ";
        
        // 		if($view['param']['pf_ynm']!="ALL" && $view['param']['pf_ynm']) { $where.=" and tpl_profile_key=? "; array_push($param, $view['param']['pf_ynm']); }
        // 		if($view['param']['inspect_status']!="ALL" && $view['param']['inspect_status']) { $where.=" and tpl_inspect_status=? "; array_push($param, $view['param']['inspect_status']); }
        // 		if($view['param']['tmpl_status']!="ALL" && $view['param']['tmpl_status']) { $where.=" and tpl_status=? "; array_push($param, $view['param']['tmpl_status']); }
        // 		if($view['param']['comment_status']!="ALL" && $view['param']['comment_status']) { $where.=" and tpl_comment_status=? "; array_push($param, $view['param']['comment_status']); }
        // 		if($view['param']['tmpl_search']!="ALL" && $view['param']['tmpl_search'] && $view['param']['searchStr']) { $where.=" and tpl_".$view['param']['tmpl_search']." like ? "; array_push($param, '%'.$view['param']['searchStr'].'%'); }
        
        $sql = "
			select count(*) cnt
			from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
			where ".$where;
        //log_message("ERROR", "Pulbic Template cnt : ".$sql);
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $this->db->query($sql)->row()->cnt;
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        //log_message("ERROR", "Pulbic Template cnt : ".$page_cfg['total_rows']);
        $view['total_count'] = $page_cfg['total_rows'];
        $sql = "
			select a.*, b.spf_friend, b.spf_key_type
			from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
			where ".$where." order by tpl_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //echo $sql ."<br>";
        //log_message("ERROR", "Pulbic Template : ".$sql);
        $view['list'] = $this->db->query($sql)->result();
        $view['profile'] = $this->Biz_dhn_model->get_partner_profile();
        $view['view']['canonical'] = site_url();
        
        $this->load->view('biz/dhnsender/send/public_temp', $view);
    }
    
    public function index_d()
    {
        // 이벤트 라이브러리를 로딩합니다
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 6;
        
        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['pf_ynm'] = $this->input->post('pf_ynm');							//한국산업인력공단
        $view['param']['inspect_status'] = $this->input->post('inspect_status');	//REG
        $view['param']['tmpl_status'] = $this->input->post('tmpl_status');			//R
        $view['param']['comment_status'] = $this->input->post('comment_status');	//INQ
        $view['param']['tmpl_search'] = $this->input->post('tmpl_search');			//pf_ynm
        $view['param']['searchStr'] = $this->input->post('searchStr');					//한국
        $param = array($this->member->item('mem_id'));
        $where = " tpl_use='Y' and exists (select 1 from cb_wt_send_profile_group_dhn where spg_pf_key = tpl_profile_key)  and tpl_inspect_status='APR'  ";
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
			from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
			where ".$where." order by tpl_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql, $param)->result();
        $view['profile'] = $this->Biz_dhn_model->get_partner_profile();
        $view['view']['canonical'] = site_url();
        
        $this->load->view('biz/dhnsender/send/public_temp_d', $view);
    }
}
?>