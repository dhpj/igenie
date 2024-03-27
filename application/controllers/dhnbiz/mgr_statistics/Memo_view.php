<?php
class Memo_view extends CB_Controller {
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
        log_message("ERROR", "Memo_view index Start");
        // 이벤트 라이브러리를 로딩합니다
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 5;
               
        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['mam_id'] =  $this->input->post('mem_id');
        $view['param']['memo_kind'] =  $this->input->post('memo_kind');

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $sql = "select count(1) cnt from cb_service_history where sh_mem_id = ".$view['param']['mam_id']." and sh_type='".$view['param']['memo_kind']."'";
        log_message("ERROR", "sql : ".$sql);
        $page_cfg['total_rows'] = $this->db->query($sql)->row()->cnt;
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        
       
        $sql = "select * from cb_service_history where sh_mem_id = ".$view['param']['mam_id']." and sh_type='".$view['param']['memo_kind']."' order by sh_reg_date DESC limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        log_message("ERROR", "sql : ".$sql);
        $view['pop_mome_list'] = $this->db->query($sql)->result();
        $view['view']['canonical'] = site_url();
        $this->load->view('biz/mgr_statistics/memo_view', $view);
        
    }
}
?>