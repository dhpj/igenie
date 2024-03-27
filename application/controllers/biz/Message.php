<?php
class Message extends CB_Controller {

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz');
    public $partner_list = array();

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
    }

    public function lists()
    {
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['perpage'] = 15;
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;

        $view['users'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));
        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        $sql = "SELECT  cm.*,
                        (SELECT
                                COUNT(1)
                            FROM
                                cb_message_receiver cmr
                            WHERE
                                cmr.msg_id = cm.msg_id) AS rec_cnt,
                    	(select
                               mem_username
                    		from cb_message_receiver cmr inner join cb_member cmm
                               on cmm.mem_id = cmr.to_mem_id
                    	 where cmr.msg_id = cm.msg_id
                             limit 0,1) as rec_user
                    FROM
                        cb_message cm
                   where cm.from_mem_id = '".$this->member->item('mem_id')."'
                      order by msg_id desc
                       limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];

        $list = $this->db->query($sql)->result();
        $view['list'] = $list;

        //log_message("ERROR", count($list));

        $view['total_rows'] = $this->Biz_model->get_table_count("cb_message", "from_mem_id = '".$this->member->item('mem_id')."'", array());

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/message',
            'layout' => 'layout',
            'skin' => 'lists',
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

    public function view_lists()
    {
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['perpage'] = 1;
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;

        $view['users'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));
        $view['view']['canonical'] = site_url();

        $isAlam = $this->input->post("isalam");
        if(empty($isAlam) || $isAlam == 'Y') {
            $isAlam = "and is_alam = 'Y'";
        } else {
            $isAlam = "";
        }
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        $sql = "SELECT
                        *
                    FROM cb_message cm inner join cb_message_receiver cmr
                           on cm.msg_id = cmr.msg_id
                    where cmr.to_mem_id = '".$this->member->item('mem_id')."' ".$isAlam."
                    order by (case when cmr.is_alam = 'Y' then 1 else 2 end), cmr.msg_id desc
               limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        // log_message("ERROR", $sql);

        $list = $this->db->query($sql)->result();
        $view['list'] = $list;
        $view['currentpage']= $view['param']['page'];
        foreach($list as $r) {
            $update_sql = "update cb_message_receiver set is_alam = 'N' where msg_id = '".$r->msg_id."'";
            $this->db->query($update_sql);
        }

        $view['total_rows'] = $this->Biz_model->get_table_count("cb_message_receiver", "to_mem_id = '".$this->member->item('mem_id')."' ".$isAlam, array());
        if($view['currentpage'] > $view['total_rows'])
            $view['currentpage'] = $view['total_rows'];

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page_user_msg';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $this->load->view('biz/message/view_lists',$view);
    }

    public function view($msg_id)
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        //$view['offer'] = $this->Biz_model->get_offer();

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        $sql = "select * from cb_message where msg_id = '".$msg_id."'";
        $view['rs'] = $this->db->query($sql)->row();

        $sql = "SELECT m.mem_username
            	  FROM cb_message_receiver cmr inner join cb_member m on cmr.to_mem_id = m.mem_id
                 where cmr.msg_id = '".$msg_id."'";
        $view['partner'] = $this->db->query($sql)->result();

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/message',
            'layout' => 'layout',
            'skin' => 'view',
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

    public function write()
    {
        $mode = $this->input->post("actions");
        if($mode=="msg_save") { $this->msg_save(true); return; }

        unset($this->partner_list);
        $this->partner_list = [];

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['member'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);
        $view['offer'] = $this->Biz_model->get_child_partner_info($this->member->item('mem_id'), "a.mem_level>=10");	//$this->db->query("select mem_id, mem_username from cb_member where mem_level>=10 order by mem_level, mem_username")->result();
        //$view['offer'] = $this->Biz_model->get_offer();

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        $this->end_user($this->member->item('mem_id'));
        $view['partner'] = $this->partner_list;

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/message',
            'layout' => 'layout',
            'skin' => 'write',
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

    public function end_user($parent_mem_id)
    {
        $sql = "SELECT
                cm.mem_id,
                cm.mem_username,
                (SELECT
                    COUNT(1)
                    FROM
                    cb_member_register cmr1
                    WHERE cmr1.mrg_recommend_mem_id = cm.mem_id
                      and cmr1.mrg_recommend_mem_id <> cmr.mrg_recommend_mem_id) AS cnt
                    FROM
                    cb_member cm
                    INNER JOIN
                    cb_member_register cmr ON cm.mem_id = cmr.mem_id
                    WHERE
                    cm.mem_useyn = 'Y'
                        AND cmr.mrg_recommend_mem_id = '".$parent_mem_id."'";

        $sub_list = $this->db->query($sql)->result();
        foreach ($sub_list as $r) {
            if($r->cnt > 0) {
                $this->end_user($r->mem_id);

            } else {
                if(!isset($this->partner_list[$r->mem_id]) && $r->mem_id <> $this->member->item('mem_id')) {
                    $this->partner_list += [$r->mem_id => $r->mem_username];
                }
            }
        }

    }

    public function msg_save($new = false) {
        $message = array();

        $receiver = $this->input->post("receiver_list");
        $message['title'] = $this->input->post("title");
        $message['msg'] =$this->input->post("message");
        $message['expiration_date'] = $this->input->post("expiration_date");
        $message['send_date'] = $this->input->post("send_date");
        $message['from_mem_id'] = $this->member->item('mem_id');

        $this->db->insert("message", $message);
        $msg_id = $this->db->insert_id();

        foreach ($receiver as $a){
            $msg_receiver = array();
            $msg_receiver['msg_id'] = $msg_id;
            $msg_receiver['to_mem_id'] = $a;
            $msg_receiver['is_alam'] = 'Y';

            $this->db->insert("message_receiver", $msg_receiver);
        }

        $this->lists();
    }
}
?>
