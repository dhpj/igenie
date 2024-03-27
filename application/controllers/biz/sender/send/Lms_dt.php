<?php
class Lms_dt extends CB_Controller {
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
        $receivers = $this->input->post('receivers');
        $view['receivers'] = ($receivers) ? $this->Biz_model->get_customer_from_id($this->member->item('mem_userid'), $receivers) : '';
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['mem'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);
        $view['view']['canonical'] = site_url();
        
        $view['rs'] = $this->db->query("select * from cb_wt_send_profile where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' order by spf_appr_datetime desc limit 1", $this->member->item('mem_id'))->row();
        
        $view['po'] = $this->db->query("select * from cb_member where mem_id=?", $this->member->item('mem_id'))->row();
        
        $smscallback = "";
        
        if($view['rs']->spf_sms_callback) {
            $smscallback = $view['rs']->spf_sms_callback;
        } else {
            $smscallback = $view['po']->mem_phone;
        }
        $sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$smscallback."' and use_flag = 'Y' and auth_flag = 'O'";
        $view['sendtelauth'] = $this->db->query($sql)->row()->cnt;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        // 2019.01.17. 이수환 고객구분 select box값 조회(NULL값을 공백처리해서 길이가 0초가한 값만)
        //$sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid');
        $sql = "select ab_kind, count(ab_kind) ab_kind_cnt from cb_ab_".$this->member->item('mem_userid')." group by ab_kind";
        log_message("ERROR", $sql);
        $view['kind'] = $this->db->query($sql)->result();
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
            'skin' => 'lms_dt',
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
    
    /*
     * 2019.01.23 ssg
     * 실패 재 전송 처리를 위한 Function 추가
     *
     */
    public function resend($fail_mst_id = -1)
    {
        
        $str = "select mst_mem_id, mem_userid from cb_wt_msg_sent_v where mst_id = '".$fail_mst_id."'";
        $rs = $this->db->query($str)->row();
        
        $mem_userid = $rs->mem_userid;
        $mem_id = $rs->mst_mem_id;
        
        $this->Biz_model->make_msg_log_table($mem_userid);
        $this->Biz_model->make_customer_book($mem_userid);
        $this->Biz_model->make_user_image_table($mem_userid);
        $this->Biz_model->make_user_deposit_table($mem_userid);
        $this->Biz_model->make_send_cache_table($mem_userid);
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $receivers = $this->input->post('receivers');
        $view['receivers'] = ($receivers) ? $this->Biz_model->get_customer_from_id($mem_userid, $receivers) : '';
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['mem'] = $this->Biz_model->get_member($mem_userid, true);
        $view['view']['canonical'] = site_url();
        
        $view['rs'] = $this->db->query("select * from cb_wt_send_profile where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' order by spf_appr_datetime desc limit 1", $mem_id)->row();
        
        $view['po'] = $this->db->query("select * from cb_member where mem_id=?", $mem_id)->row();
        
        //$this->load->library('message');
        $str_query = "SELECT count(1) as cnt
                        FROM cb_msg_".$mem_userid." cmd INNER JOIN cb_wt_msg_sent cwm ON cmd.remark4 = cwm.mst_id
                       WHERE cmd.remark4 = '".$fail_mst_id."' and cmd.RESULT = 'N' and cmd.MESSAGE not like '%대기%'";
        $view['fail_qty'] = $this->db->query($str_query)->row()->cnt;
        
        $view['fail_mst_id'] = $fail_mst_id;
        
        $smscallback = "";
        
        if($view['rs']->spf_sms_callback) {
            $smscallback = $view['rs']->spf_sms_callback;
        } else {
            $smscallback = $view['po']->mem_phone;
        }
        $sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$mem_id."' and send_tel_no = '".$smscallback."' and use_flag = 'Y' and auth_flag = 'O'";
        $view['sendtelauth'] = $this->db->query($sql)->row()->cnt;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        // 2019.01.17. 이수환 고객구분 select box값 조회(NULL값을 공백처리해서 길이가 0초가한 값만)
        //$sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid');
        $sql = "select ab_kind, count(ab_kind) ab_kind_cnt from cb_ab_".$this->member->item('mem_userid')." group by ab_kind";
        log_message("ERROR", $sql);
        $view['kind'] = $this->db->query($sql)->result();

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
            'skin' => 'lms_dt',
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