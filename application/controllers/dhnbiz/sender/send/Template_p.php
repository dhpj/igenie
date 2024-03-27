<?php
class Template_p extends CB_Controller {
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
        $page = $this->input->post('page');
        $view['type'] = $this->input->post('type');

        $where = array(
            'pcr_mem_id' => $this->member->item('mem_id')
          , 'pcr_useyn' => 'Y'
          , 'pcr_para <>' => 'NULL'
        );

        if (!empty($type)) $where['pcr_type'] = $view['type'];

        $view['tr'] = $this->db
            ->select('count(1) as cnt')
            ->from('cb_personal_creative')
            ->where($where)
            ->get()->row()->cnt;

        $view['list'] = $this->db
            ->select("
                *
              , CASE
                    WHEN pcr_type = 'bt' THEN '기본텍스트'
                    WHEN pcr_type = 'wm' THEN '와이드이미지'
                    WHEN pcr_type = 'wl' THEN '와이드리스트'
                    WHEN pcr_type = 'cc' THEN '캐러셀커머스'
                    WHEN pcr_type = 'cf' THEN '캐러셀피드'
                    ELSE ''
              END as type
            ")
            ->from('cb_personal_creative')
            ->where($where)
            ->order_by('pcr_create_datetime', 'DESC')
            ->limit($view['perpage'], ($page - 1) * $view['perpage'])
            ->get()->result();

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['tr'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($page);
        $view['page_html'] = $this->pagination->create_links();

        $view['view']['canonical'] = site_url();
        $this->load->view('biz/dhnsender/send/template_p', $view);
    }
}
?>
