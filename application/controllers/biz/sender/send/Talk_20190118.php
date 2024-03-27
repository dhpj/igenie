<?php
class Talk extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz');
    
    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');
    
    public $nft = "";
    
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
        $this->Biz_model->make_msg_log_table($this->member->item('mem_userid'));
        $this->Biz_model->make_customer_book($this->member->item('mem_userid'));
        $this->Biz_model->make_user_image_table($this->member->item('mem_userid'));
        $this->Biz_model->make_user_deposit_table($this->member->item('mem_userid'));
        $this->Biz_model->make_send_cache_table($this->member->item('mem_userid'));
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['param']['tmp_code'] = $this->input->post('tmp_code');
        $view['param']['tmp_profile'] = $this->input->post('tmp_profile');
        $view['param']['iscoupon'] = $this->input->post('iscoupon');
        if(!$this->nft) {
            $this->nft = $this->input->post('nft');
        }
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $tmp_code = $view['param']['tmp_code'];
        $tmp_profile = $view['param']['tmp_profile'];
        //$view['view']['iscoupon'] = $this->input->post('iscoupon');
        
        
        if($tmp_profile && $tmp_code  ) {
            // log_message("ERROR", "Coupon : ".$view['view']['iscoupon']);
            $view['tpl'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
            //log_message("ERROR", "row count ".$this->db->affected_rows());
        }
        
        $view['mem'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);
        $view['view']['canonical'] = site_url();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        $sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid');
        $view['kind'] = $this->db->query($sql)->result();
        $view['nft'] = $this->nft;
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/sender/send',
            'layout' => 'layout',
            'skin' => 'talk',
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
    
    public function nft_talk() {
        $this->nft = "NFT";
        $this->index();
    }
    
    public function load_customer()
    {
        $cnt = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'");
        header('Content-Type: application/json');
        echo '{"customer_count":'.$cnt.'}';
        exit;
    }
}