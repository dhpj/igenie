<?php
class Image_w_list extends CB_Controller {
    /**
     * ���� �ε��մϴ�
     */
    protected $models = array('Board', 'Biz');
    
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
    
    public function index()
    {
        $CI =& get_instance();
        
        // �̺�Ʈ ���̺귯���� �ε��մϴ�
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $this->Biz_model->make_user_image_table($this->member->item('mem_userid'));
        
        $view = array();
        $view['view'] = array();
        $view['base_url'] = $CI->config->item('base_url');
        $view['perpage'] = 12;
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
        
        // �̺�Ʈ�� �����ϸ� �����մϴ�
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $this->Biz_model->get_table_count("cb_img_".$this->member->item('mem_userid'),"","");
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        
        $sql = "
			select *
			from cb_img_".$this->member->item('mem_userid')."
			order by img_id desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql)->result();
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
            'path' => 'biz/sender',
            'layout' => 'layout',
            'skin' => 'image_w_list',
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
}
?>