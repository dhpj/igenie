<?php
class Lms extends CB_Controller {
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
        $this->Biz_dhn_model->make_msg_log_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_customer_book($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_user_image_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_user_deposit_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_send_cache_table($this->member->item('mem_userid'));
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $receivers = $this->input->post('receivers');
        $view['receivers'] = ($receivers) ? $this->Biz_dhn_model->get_customer_from_id($this->member->item('mem_userid'), $receivers) : '';
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['mem'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        $view['view']['canonical'] = site_url();
        
        $view['rs'] = $this->db->query("select * from cb_wt_send_profile_dhn where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' order by spf_appr_datetime desc limit 1", $this->member->item('mem_id'))->row();
        
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
        $sql = "select ab_kind, count(ab_kind) ab_kind_cnt from (select IFNULL(ab_kind, '') as ab_kind, ab_tel from cb_ab_".$this->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8) a group by ab_kind";
        //log_message("ERROR", $sql);
        $view['kind'] = $this->db->query($sql)->result();
        
        // 2019-07-09 친구여부 추가
        $ftCountSql = "select count(*) ftcnt from cb_ab_".$this->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 and exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
        $nftCountSql = "select count(*) nftcnt from cb_ab_".$this->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
        //log_message("ERROR", "ftCountSql : ".$ftCountSql);
        //log_message("ERROR", "nftCountSql : ".$nftCountSql);
        $ftCount = $this->db->query($ftCountSql)->row()->ftcnt;
        $nftCount = $this->db->query($nftCountSql)->row()->nftcnt;
        $view['ftCount'] = $ftCount;
        $view['nftCount'] = $nftCount;
        //log_message("ERROR", "ftcnt : ".$ftCount);
        //log_message("ERROR", "nftcnt : ".$ftCount);
        // 2019-07-09 친구여부 추가 끝
        
        $view['isManager'] = 'N';
        
        /*
         * 2019.01.24 SSG
         * 관리자 DHN 으로 들어 왔을경우 "특정 기능 및 항목"을 보이게 하기 위해
         * session 변수를 이용함.
         */
        if($this->member->item('mem_id') != '3') {
            if($this->session->userdata('login_stack')) {
                $login_stack = $this->session->userdata('login_stack');
                log_message("ERROR",'Login Stack : '. $login_stack[0]);
                if($login_stack[0] == '3') {
                    $view['isManager'] = 'Y';
                }
            }
            
        } else {
            $view['isManager'] = 'Y';
        }
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/dhnsender/send',
            'layout' => 'layout',
            'skin' => 'lms',
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
        
        $this->Biz_dhn_model->make_msg_log_table($mem_userid);
        $this->Biz_dhn_model->make_customer_book($mem_userid);
        $this->Biz_dhn_model->make_user_image_table($mem_userid);
        $this->Biz_dhn_model->make_user_deposit_table($mem_userid);
        $this->Biz_dhn_model->make_send_cache_table($mem_userid);
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $receivers = $this->input->post('receivers');
        $view['receivers'] = ($receivers) ? $this->Biz_dhn_model->get_customer_from_id($mem_userid, $receivers) : '';
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['mem'] = $this->Biz_dhn_model->get_member($mem_userid, true);
        $view['view']['canonical'] = site_url();
        
        $view['rs'] = $this->db->query("select * from cb_wt_send_profile_dhn where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' order by spf_appr_datetime desc limit 1", $mem_id)->row();
        
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
        //log_message("ERROR", $sql);
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
            'path' => 'biz/dhnsender/send',
            'layout' => 'layout',
            'skin' => 'lms',
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