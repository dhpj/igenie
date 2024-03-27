<?php
class Coupon extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz_dhn');
    
    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

	private $set_tmp_code; //템플릿 코드
	private $set_tmp_profile; //프로필키
    
    function __construct()
    {
        parent::__construct();
        
        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring'));

		$this->set_tmp_code = '2020070701'; //템플릿 코드
		$this->set_tmp_profile = 'c03b09ba2a86fc2984b940d462265dd4dbddb105'; //프로필키
    }
    
    //전용쿠폰 알림톡 보내기 > 쿠폰 목록
    public function index()
    {
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        if($key) { $this->view($key); return; }
        
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();

		$view['add'] = $this->input->get("add");
		if($view['add'] == "") $view['add'] = $this->input->post("add");
		$skin = "list";
		$add = $view['add'];
		if($add != "") $skin .= $add;

        $view['perpage'] = 5;
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['duration'] = ($this->input->post("duration")) ? $this->input->post("duration") : "week";
        
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $where = " cc_mem_id = '".$this->member->item("mem_id")."' and cc_used = 'Y' ";
        $param = array();
        if($view['param']['search_type'] && $view['param']['search_type']!="all" && $view['param']['search_for']) {
            $where .= " and ".$view['param']['search_type']." like ? "; array_push($param, "%".$view['param']['search_for']."%");
        }
        
        if($view['param']['duration']) {
            if($view['param']['duration']=="today") {
                $where .= " and cc_creation_date between ? and ? "; array_push($param, date('Y-m-d').' 00:00:00'); array_push($param, date('Y-m-d H:i:s'));
            } else if($view['param']['duration']=="week") {
                $where .= " and cc_creation_date between ? and ? "; array_push($param, date('Y-m-d', strtotime("-1week"))); array_push($param, date('Y-m-d H:i:s'));
            } else if($view['param']['duration']=="1month") {
                $where .= " and cc_creation_date between ? and ? "; array_push($param, date('Y-m-d', strtotime("-1month"))); array_push($param, date('Y-m-d H:i:s'));
            } else if($view['param']['duration']=="3month") {
                $where .= " and cc_creation_date between ? and ? "; array_push($param, date('Y-m-d', strtotime("-3month"))); array_push($param, date('Y-m-d H:i:s'));
            } else if($view['param']['duration']=="6month") {
                $where .= " and cc_creation_date between ? and ? "; array_push($param, date('Y-m-d', strtotime("-6month"))); array_push($param, date('Y-m-d H:i:s'));
            }
        }
        
        $view['total_rows'] = $this->Biz_dhn_model->get_table_count("cb_coupon", $where, $param);
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        
        $sql = "select cc.*,
                       (select count(1) as cnt from cb_coupon_list ccl where ccl.cl_cc_id = cc.cc_idx and ccl.cl_phn is not null) as used_cnt,
                       ( SELECT tpl_profile_key FROM cb_wt_template_dhn WHERE tpl_id = cc.cc_tpl_id) as profile_key
                from cb_coupon cc
                where ".$where." order by cc_idx desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        
        $view['list'] = $this->db->query($sql, $param)->result();
        
        $view['view']['canonical'] = site_url();
        
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/coupon',
            'layout' => 'layout',
            'skin' => $skin,
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
    
    //전용쿠폰 알림톡 보내기 > 쿠폰 등록
	public function write()
    {
        
        $mode = $this->input->post("actions");
        // log_message("ERROR", $mode);
        if($mode=="coupon_save") { $this->coupon_save(); return; }
        if($mode=="coupon_proc") { $this->coupon_proc(); return; }
        if($mode=="coupon_close") { $this->index(); return; }
        
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
        //$view['param']['tmp_code'] = '2020070701'; //$this->input->post('tmp_code');
        //$view['param']['tmp_profile'] = 'c03b09ba2a86fc2984b940d462265dd4dbddb105'; //$this->input->post('tmp_profile');
        $view['param']['tmp_code'] = $this->set_tmp_code; //템플릿 코드
        $view['param']['tmp_profile'] = $this->set_tmp_profile; //프로필키
        //echo "tmp_code : ". $view['param']['tmp_code'] .", tmp_profile : ". $view['param']['tmp_profile'] ."<br>";
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $tmp_code = $view['param']['tmp_code'];
        $tmp_profile = $view['param']['tmp_profile'];
        //log_message("ERROR", $tmp_profile.", ".$tmp_code);
        if($tmp_profile && $tmp_code) {
            //$view['tpl'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_code=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
			$sql = "
			select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback 
			from cb_wt_template_dhn a 
			inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' 
			where 1=1
			/*and tpl_mem_id=? */
			and tpl_code = '". $tmp_code ."'
			and tpl_profile_key = '". $tmp_profile ."'
			order by tpl_id 
			desc limit 1";
			$view['tpl'] = $this->db->query($sql)->row();
			//echo $sql ."<br>";
            //log_message("ERROR", "row count ".$this->db->affected_rows());
        }
        
        $view['mem'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        $view['view']['canonical'] = site_url();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/coupon',
            'layout' => 'layout',
            'skin' => 'coupon',
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
    
    public function writeft()
    {
        
        $mode = $this->input->post("actions");
        // log_message("ERROR", $mode);
        if($mode=="coupon_save") { $this->coupon_saveft(); return; }
        if($mode=="coupon_proc") { $this->coupon_procft(); return; }
        if($mode=="coupon_close") { $this->index(); return; }
        
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
        $view['param']['tmp_code'] = $this->input->post('tmp_code');
        $view['param']['tmp_profile'] = $this->input->post('tmp_profile');
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $tmp_code = $view['param']['tmp_code'];
        $tmp_profile = $view['param']['tmp_profile'];
        //log_message("ERROR", $tmp_profile.", ".$tmp_code);
        if($tmp_profile && $tmp_code) {
            $view['tpl'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
            //log_message("ERROR", "row count ".$this->db->affected_rows());
        }
        
        $view['mem'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        $view['view']['canonical'] = site_url();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/coupon',
            'layout' => 'layout',
            'skin' => 'couponft',
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
    
    public function view($cc_idx)
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
        $view['param']['tmp_code'] = $this->input->post('tmp_code');
        $view['param']['tmp_profile'] = $this->input->post('tmp_profile');
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $tmp_code = $view['param']['tmp_code'];
        $tmp_profile = $view['param']['tmp_profile'];
        
        $tpl = "select a.*
                     , b.spf_friend
                     , b.spf_key_type
                     , b.spf_sms_callback
                     , cc.cc_idx
                     , cc.cc_tpl_code
                     , cc.cc_tpl_id
                     , cc.cc_rate
                     , cc.cc_rate_txt
                     ,cc.cc_status
                  from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
                                        inner join cb_coupon cc on a.tpl_id = cc.cc_tpl_id
                 where cc.cc_idx = '".$cc_idx."'";
        
        $view['tpl'] = $this->db->query($tpl)->row();
        
        $view['mem'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        $view['view']['canonical'] = site_url();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        $sql = "select cc.cc_idx
                    ,cc.cc_mem_id
                    ,cc.cc_tpl_id
                    ,cc.cc_tpl_code
                    ,cc.cc_msg as tpl_contents
                    ,cc.cc_button1
                    ,cc.cc_title
                    ,cc.cc_coupon_qty
                    ,cc.cc_start_date
                    ,cc.cc_end_date
                    ,cc.cc_img_url1  as cc_img_link
                    ,cc.cc_memo
                    ,cwt.tpl_profile_key
                    ,cwt.tpl_name
                    ,cwt.tpl_button
                    ,cc.cc_rate
                    ,cc.cc_rate_txt
                    ,cc.cc_status
                    , ( SELECT tpl_profile_key FROM cb_wt_template_dhn WHERE tpl_id = cc.cc_tpl_id) as profile_key
               from cb_coupon cc inner join cb_wt_template_dhn cwt on cc.cc_tpl_id = cwt.tpl_id
              where cc.cc_idx = '".$cc_idx."'";
        //log_message("ERROR", $sql);
        $view['rs'] = $this->db->query($sql)->row();
        
        $listsql = "SELECT COUNT(1) AS total, SUM((case when cl_phn IS NOT NULL then 0 ELSE 1 END)) AS remain  FROM cb_coupon_list a WHERE a.cl_cc_id = '".$cc_idx."'";
        $view['resultcnt'] = $this->db->query($listsql)->row();
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        
        
        $layoutconfig = array(
            'path' => 'biz/coupon',
            'layout' => 'layout',
            'skin' => 'coupon',
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
    
    public function viewft($cc_idx)
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
        $view['param']['tmp_code'] = $this->input->post('tmp_code');
        $view['param']['tmp_profile'] = $this->input->post('tmp_profile');
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $tmp_code = $view['param']['tmp_code'];
        $tmp_profile = $view['param']['tmp_profile'];
        
        $tpl = "select a.*
                     , b.spf_friend
                     , b.spf_key_type
                     , b.spf_sms_callback
                     , cc.cc_idx
                     , cc.cc_tpl_code
                     , cc.cc_tpl_id
                     , cc.cc_rate
                     , cc.cc_rate_txt
                     ,cc.cc_status
                  from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
                                        inner join cb_coupon cc on a.tpl_id = cc.cc_tpl_id
                 where cc.cc_idx = '".$cc_idx."'";
        
        $view['tpl'] = $this->db->query($tpl)->row();
        
        $view['mem'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        $view['view']['canonical'] = site_url();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        $sql = "select cc.cc_idx
                    ,cc.cc_mem_id
                    ,cc.cc_tpl_id
                    ,cc.cc_tpl_code
                    ,cc.cc_msg as tpl_contents
                    ,cc.cc_button1
                    ,cc.cc_title
                    ,cc.cc_coupon_qty
                    ,cc.cc_start_date
                    ,cc.cc_end_date
                    ,cc.cc_img_url1  as cc_img_link
                    ,cc.cc_memo
                    ,wsp.spf_key
                    ,wsp.spf_friend
                    ,cc.cc_rate
                    ,cc.cc_rate_txt
                    ,cc.cc_status
               from cb_coupon cc inner join cb_wt_send_profile_dhn wsp on cc.cc_mem_id = wsp.spf_mem_id
              where wsp.spf_use = 'Y' and cc.cc_idx = '".$cc_idx."'";
        //log_message("ERROR", $sql);
        $view['ftrs'] = $this->db->query($sql)->row();
        
        $listsql = "SELECT COUNT(1) AS total, SUM((case when cl_phn IS NOT NULL then 0 ELSE 1 END)) AS remain  FROM cb_coupon_list a WHERE a.cl_cc_id = '".$cc_idx."'";
        $view['resultcnt'] = $this->db->query($listsql)->row();
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        
        
        $layoutconfig = array(
            'path' => 'biz/coupon',
            'layout' => 'layout',
            'skin' => 'couponft',
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
    
    
    public function load_customer()
    {
        $cnt = $this->Biz_dhn_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'");
        header('Content-Type: application/json');
        echo '{"customer_count":'.$cnt.'}';
        exit;
    }
    
    public function select_template($cc_idx = 0)
    {
        if($cc_idx > 0) {
            $view = array();
            $view['view'] = array();
            
            $sql = "select cc.cc_idx
                    ,cc.cc_mem_id
                    ,cc.cc_tpl_id
                    ,cc.cc_tpl_code
                    ,cc.cc_msg as tpl_contents
                    ,cc.cc_button1
                    ,cc.cc_title
                    ,cc.cc_coupon_qty
                    ,cc.cc_start_date
                    ,cc.cc_end_date
                    ,cc.cc_img_url1 as cc_img_link
                    ,cc.cc_memo
                    ,cwt.tpl_profile_key
                    ,cwt.tpl_name
                    ,cwt.tpl_button
                    ,cwt.tpl_id
                    ,cwt.tpl_code
                    ,cc.cc_rate
                    ,cc.cc_rate_txt
                    ,cc.cc_status
                    ,cc.cc_user_barcode
               from cb_coupon cc inner join cb_wt_template_dhn cwt on cc.cc_tpl_id = cwt.tpl_id
              where cc.cc_idx = '".$cc_idx."'";
            
            $view['rs'] = $this->db->query($sql)->row();
            
            $varstr = "select * from cb_coupon_var where cc_idx = '".$cc_idx."' order by var_idx";
            $view['varvalue'] = $this->db->query($varstr)->result();
            
        } else {
            $tmp_code = $this->input->post('tmp_code');
            $tmp_profile = $this->input->post('tmp_profile');
            
            $view = array();
            $view['view'] = array();
            if($tmp_profile && $tmp_code) {
                $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_code=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
            }
            $view['varvalue'] = "";
        }
        $this->load->view('biz/coupon/select_template',$view);
    }
    
    public function select_profile()
    {
        $profileKey = $this->input->post('profileKey');
        $cc_idx = $this->input->post('cc_idx');
        
        $view = array();
        $view['view'] = array();
        // 20190107 이수환 수정 mem_linkbtn_name 조회구문에 추가
        /* 		if(!$profileKey) {
         $view['rs'] = $this->db->query("select * from cb_wt_send_profile_dhn where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1", array($this->member->item('mem_id')))->row();
         } else {
         $view['rs'] = $this->db->query("select * from cb_wt_send_profile_dhn where spf_key=? and spf_appr_id<>'' and spf_use='Y'  and spf_status='A'", array($profileKey))->row();
         }  */
        if(!$profileKey) {
            $view['rs'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from cb_wt_send_profile_dhn where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();
        } else {
            $view['rs'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from cb_wt_send_profile_dhn where spf_key=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A') as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($profileKey))->row();
        }
        $sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$view['rs']->spf_sms_callback."' and use_flag = 'Y' and auth_flag = 'O'";
        //log_message("ERROR", "0000000000000000000000".$sql);
        $view['sendtelauth'] = $this->db->query($sql)->row()->cnt;
        
        $view['mem'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        
        // 20190315 이수환 추가 Profile들 조회 발신 아이디 Selectbox값.
        $sqlProfile = "
		select *
		from cb_wt_send_profile_dhn
		where spf_key<>'' and spf_use='Y' and spf_mem_id=".$this->member->item('mem_id')." order by spf_datetime desc;";
        //log_message("ERROR", "0000000000000000000000".$sqlProfile);
        $view['profile'] = $this->db->query($sqlProfile)->result();
        
        $sql = "select cc.cc_idx
                    ,cc.cc_mem_id
                    ,cc.cc_tpl_id
                    ,cc.cc_tpl_code
                    ,cc.cc_msg as tpl_contents
                    ,cc.cc_button1
                    ,cc.cc_title
                    ,cc.cc_coupon_qty
                    ,cc.cc_start_date
                    ,cc.cc_end_date
                    ,cc.cc_img_url1  as cc_img_link
                    ,cc.cc_memo
                    ,wsp.spf_key
                    ,wsp.spf_friend
                    ,cc.cc_rate
                    ,cc.cc_rate_txt
                    ,cc.cc_status
               from cb_coupon cc inner join cb_wt_send_profile_dhn wsp on cc.cc_mem_id = wsp.spf_mem_id
              where wsp.spf_use = 'Y' and cc.cc_idx = '".$cc_idx."'";
        //log_message("ERROR", $sql);
        $view['ftrs'] = $this->db->query($sql)->row();
        
        $this->load->view('biz/coupon/select_profile',$view);
    }
    
    public function coupon_result()
    {
        $view = array();
        $view['view'] = array();
        $coupon = $this->input->post('coupon_id');
        $search_phn = $this->input->post('search_phn');
        $view['perpage'] = 10;
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
        
        if($search_phn) {
            $search_phn = " and phn like '%".$search_phn."%'";
        } else {
            $search_phn = "";
        }
        //log_message("ERROR", $search_phn);
        $sql = "select (case
                         when coupon_no is null then
                          'X'
                         else
                          'O'
                       end) as iswin
                      ,phn
                      ,coupon_no
                      ,reg_date
                      ,(case
                         when app_date is not null and coupon_no is not null then
                          'O'
                         when app_date is null and coupon_no is not null then
                          'X'
                         else
                          null
                       end) as isend
                      ,(case
                         when app_date is not null and coupon_no is not null then
                          app_date
                         else
                          null
                       end) as end_date
                  from cb_coupon_result a
                 where a.coupon_id = '".$coupon."' ".$search_phn."
                 limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        
        $view['rs'] = $this->db->query($sql)->result();
        
        $sqlcnt = "select count(1) as cnt
                  from cb_coupon_result a
                 where a.coupon_id = '".$coupon."'".$search_phn;
        
        $view['total_rows'] = $this->db->query($sqlcnt)->row()->cnt;
        
        $view['coupon_id'] = $coupon;
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page_result';
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
        
        
        $this->load->view('biz/coupon/coupon_result',$view);
    }
    
    public function template()
    {
        // 이벤트 라이브러리를 로딩합니다
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 5;
        
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
        
        $sql = "
			select a.*, b.spf_friend, b.spf_key_type
			from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
			where ".$where." order by tpl_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql, $param)->result();
        $view['profile'] = $this->Biz_dhn_model->get_partner_profile();
        $view['view']['canonical'] = site_url();
        
        $this->load->view('biz/sender/send/template', $view);
    }
    
    public function img_list()
    {
        $CI =& get_instance();
        
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $this->Biz_dhn_model->make_user_image_table($this->member->item('mem_userid'));
        
        $view = array();
        $view['view'] = array();
        $view['base_url'] = $CI->config->item('base_url');
        $view['perpage'] = 6;
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
        
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'img_open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $this->Biz_dhn_model->get_table_count("cb_img_".$this->member->item('mem_userid'),"","");
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
        
        
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $this->load->view('biz/coupon/img_list', $view);
    }
    
	//전용쿠폰 알림톡 보내기 > 임시저장
    function coupon_save($new=false)
    {
        $status = "N";
        $errMsg = "";
        $rtn = array();
        
        if($this->input->post("actions")=="coupon_save") {
            $status = "S";
        }
        
        if($this->input->post("actions")=="coupon_proc") {
            $status = "P";
        }
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 전용쿠폰 알림톡 보내기 > 임시저장 > status : ". $status);
		//echo $_SERVER['REQUEST_URI'] ." > 전용쿠폰 알림톡 보내기 > 임시저장 > status : ". $status ."<br>";
        
        $rtn['coupon_type'] = $this->input->post("id_coupon_create");
        // 쿠폰 번호를 사용자가 직접 Upload 하면 파일 처리 필요
        if($this->input->post("id_coupon_create") == 'M' && $status=='S') {
            
            if(empty($_FILES)) {
                $errMsg = "파일이 없습니다.";
            }
            
            $this->load->library('upload');
            $config = "";
            $config['upload_path']          = config_item('uploads_dir') .'/coupon/';
            $config['allowed_types']        = 'txt';
            $config['encrypt_name']        = true;
            
            $this->upload->initialize($config);
            
            if ( ! $this->upload->do_upload())
            {
                $error = array('error' => $this->upload->display_errors());
                $errMsg = $this->upload->display_errors('','');
            }
            else
            {
                $rtn['filedata'] =  $this->upload->data();
                
            }
        }
        
        if(empty($errMsg)) {
            $var = $this->input->post("var_name");
			$cc_type = $this->input->post("cc_type");
            if(empty($cc_type)) $cc_type = 'AT';
            
            if(!$this->input->post("cc_idx")) {
                $coupon = array();
                
                $coupon['cc_title'] = $this->input->post("cc_title");
                $coupon['cc_mem_id'] = $this->member->item('mem_id');
                $coupon['cc_type'] = $this->input->post("cc_type");
                $coupon['cc_tpl_id'] = $this->input->post("cc_tpl_id");
                $coupon['cc_tpl_code'] = $this->input->post("cc_tpl_code");
                $coupon['cc_msg'] = $this->input->post("cc_msg");
                $coupon['cc_button1'] = $this->input->post("cc_button1");
                $coupon['cc_start_date'] = $this->input->post("cc_start_date");
                $coupon['cc_end_date'] = $this->input->post("cc_end_date");
                $coupon['cc_img_url1'] = $this->input->post("img_link");
                $coupon['cc_memo'] = $this->input->post("cc_memo");
                $coupon['cc_creation_date'] = cdate('Y-m-d H:i:s');
                $coupon['cc_coupon_qty'] = $this->input->post("cc_coupon_qty");
                $coupon['cc_status'] = $status;
                $coupon['cc_rate'] = $this->input->post("cc_rate");
                $coupon['cc_rate_txt'] = $this->input->post("cc_rate_txt");
                $coupon['cc_user_barcode'] = $this->input->post("cc_user_barcode");
                
                $this->db->insert("coupon", $coupon);
                $rtn['cc_idx'] = $this->db->insert_id();
                
                $i = 1;
                foreach($var as $v) {
                    $varname = array();
                    $varname['cc_idx'] = $rtn['cc_idx'];
                    $varname['var_idx'] = $i;
                    $varname['var_text'] = $v;
                    $this->db->insert('coupon_var', $varname);
                    $i = $i + 1;
                }
                
            } else {
                $var = $this->input->post("var_name");
                
                $coupon['cc_idx'] = $this->input->post("cc_idx");
                $coupon['cc_title'] = $this->input->post("cc_title");
                $coupon['cc_mem_id'] = $this->member->item('mem_id');
                $coupon['cc_type'] = $this->input->post("cc_type");
                $coupon['cc_tpl_id'] = $this->input->post("ud_cc_tpl_id");
                $coupon['cc_tpl_code'] = $this->input->post("ud_cc_tpl_code");
                $coupon['cc_msg'] = $this->input->post("cc_msg");
                $coupon['cc_button1'] = $this->input->post("cc_button1");
                $coupon['cc_start_date'] = $this->input->post("cc_start_date");
                $coupon['cc_end_date'] = $this->input->post("cc_end_date");
                $coupon['cc_img_url1'] = $this->input->post("img_link");
                $coupon['cc_memo'] = $this->input->post("cc_memo");
                $coupon['cc_creation_date'] = cdate('Y-m-d H:i:s');
                $coupon['cc_coupon_qty'] = $this->input->post("cc_coupon_qty");
                $coupon['cc_status'] = $status;
                $coupon['cc_rate'] = $this->input->post("cc_rate");
                $coupon['cc_rate_txt'] = $this->input->post("cc_rate_txt");
                $coupon['cc_user_barcode'] = $this->input->post("cc_user_barcode");
                
                $this->db->replace("coupon", $coupon);
                $rtn['cc_idx'] = $this->input->post("cc_idx");
                
                $this->db->query("delete from cb_coupon_var where cc_idx = '".$rtn['cc_idx']."'");
                
                $i = 1;
				foreach($var as $v) {
                    $varname = array();
                    $varname['cc_idx'] = $rtn['cc_idx'];
                    $varname['var_idx'] = $i;
                    $varname['var_text'] = $v;
                    $this->db->insert('coupon_var', $varname);
                    $i = $i + 1;
                }
            }
        }
        
        if ($this->db->error()['code'] > 0) {
            $errMsg = "저장 오류 !!".$this->db->error()['message'];
        }
        
        if(empty($errMsg)) {
            
            if($status != 'P') {
                redirect("/biz/coupon");
            }
        } else {
            echo '<script type="text/javascript">alert("'.$errMsg.'");history.back();</script>';
            $rtn['error'] = $errMsg;
        }
        return $rtn;
    }
    
    function coupon_saveft($new=false)
    {
        $status = "N";
        $errMsg = "";
        $rtn = array();
        
        if($this->input->post("actions")=="coupon_save") {
            $status = "S";
        }
        
        if($this->input->post("actions")=="coupon_proc") {
            $status = "P";
        }
        
        $rtn['coupon_type'] = $this->input->post("id_coupon_create");
        // 쿠폰 번호를 사용자가 직접 Upload 하면 파일 처리 필요
        if($this->input->post("id_coupon_create") == 'M' && $status=='S') {
            
            if(empty($_FILES)) {
                $errMsg = "파일이 없습니다.";
            }
            
            $this->load->library('upload');
            $config = "";
            $config['upload_path']          = config_item('uploads_dir') .'/coupon/';
            $config['allowed_types']        = 'txt';
            $config['encrypt_name']        = true;
            
            $this->upload->initialize($config);
            
            if ( ! $this->upload->do_upload())
            {
                $error = array('error' => $this->upload->display_errors());
                $errMsg = $this->upload->display_errors('','');
            }
            else
            {
                $rtn['filedata'] =  $this->upload->data();
                
            }
        }
        
        if(empty($errMsg)) {
            
            $cc_type = $this->input->post("cc_type");
            if(empty($cc_type))
                $cc_type = 'AT';
                
                if(!$this->input->post("cc_idx")) {
                    $coupon = array();
                    
                    $coupon['cc_title'] = $this->input->post("cc_title");
                    $coupon['cc_mem_id'] = $this->member->item('mem_id');
                    $coupon['cc_tpl_id'] = $this->input->post("cc_tpl_id");
                    $coupon['cc_tpl_code'] = $this->input->post("cc_tpl_code");
                    $coupon['cc_type'] = $cc_type;
                    $coupon['cc_msg'] = $this->input->post("cc_msg");
                    $coupon['cc_button1'] = $this->input->post("cc_button1");
                    $coupon['cc_start_date'] = $this->input->post("cc_start_date");
                    $coupon['cc_end_date'] = $this->input->post("cc_end_date");
                    $coupon['cc_img_url1'] = $this->input->post("img_link");
                    $coupon['cc_memo'] = $this->input->post("cc_memo");
                    $coupon['cc_creation_date'] = cdate('Y-m-d H:i:s');
                    $coupon['cc_coupon_qty'] = $this->input->post("cc_coupon_qty");
                    $coupon['cc_status'] = $status;
                    $coupon['cc_rate'] = $this->input->post("cc_rate");
                    $coupon['cc_rate_txt'] = $this->input->post("cc_rate_txt");
                    $coupon['cc_user_barcode'] = $this->input->post("cc_user_barcode");
                    
                    $this->db->insert("coupon", $coupon);
                    $rtn['cc_idx'] = $this->db->insert_id();
                } else {
                    $coupon['cc_idx'] = $this->input->post("cc_idx");
                    $coupon['cc_title'] = $this->input->post("cc_title");
                    $coupon['cc_mem_id'] = $this->member->item('mem_id');
                    $coupon['cc_tpl_id'] = $this->input->post("ud_cc_tpl_id");
                    $coupon['cc_tpl_code'] = $this->input->post("ud_cc_tpl_code");
                    $coupon['cc_type'] = $cc_type;
                    $coupon['cc_msg'] = $this->input->post("cc_msg");
                    $coupon['cc_button1'] = $this->input->post("cc_button1");
                    $coupon['cc_start_date'] = $this->input->post("cc_start_date");
                    $coupon['cc_end_date'] = $this->input->post("cc_end_date");
                    $coupon['cc_img_url1'] = $this->input->post("img_link");
                    $coupon['cc_memo'] = $this->input->post("cc_memo");
                    $coupon['cc_creation_date'] = cdate('Y-m-d H:i:s');
                    $coupon['cc_coupon_qty'] = $this->input->post("cc_coupon_qty");
                    $coupon['cc_status'] = $status;
                    $coupon['cc_rate'] = $this->input->post("cc_rate");
                    $coupon['cc_rate_txt'] = $this->input->post("cc_rate_txt");
                    $coupon['cc_user_barcode'] = $this->input->post("cc_user_barcode");
                    
                    $this->db->replace("coupon", $coupon);
                    $rtn['cc_idx'] = $this->input->post("cc_idx");
                }
        }
        
        if ($this->db->error()['code'] > 0) {
            $errMsg = "저장 오류 !!".$this->db->error()['message'];
        }
        
        if(empty($errMsg)) {
            
            if($status != 'P') {
                redirect("/biz/coupon");
            }
        } else {
            echo '<script type="text/javascript">alert("'.$errMsg.'");history.back();</script>';
            $rtn['error'] = $errMsg;
        }
        return $rtn;
    }
    
    
    
    function coupon_close() {
        $this->index();
    }
    
    //전용쿠폰 알림톡 보내기 > 쿠폰 저장
	function coupon_proc($new=false)
    {
        $rtn = $this->coupon_save();
        $errMsg = "";
        
        if(empty($rtn['error'])) {
            
            // 쿠폰 생성 방법이 자동이면 crc32 함수를 쿠폰 갯수 만큼 이용하여 자동 생성
            $sql = "select count(1) as cnt from cb_coupon_list ccl where ccl.cl_cc_id = '".$rtn['cc_idx']."'";
            
            $cnt = $this->db->query($sql)->row()->cnt;
            
            if($cnt == 0) {
                if($rtn['coupon_type'] == 'A') {
                    $qty = $this->input->post("cc_coupon_qty");
                    
                    while($qty>0) {
                        
                        $ccl = array();
                        $ccl['cl_cc_id'] = $rtn['cc_idx'];
                        $ccl['cl_creation_date'] = cdate('Y-m-d H:i:s');
                        $this->db->insert('coupon_list', $ccl);
                        $ccl_id = $this->db->insert_id();
                        
                        $ccl_no = str_pad(crc32(str_pad($this->input->post("cc_idx"),"8","0",STR_PAD_LEFT).str_pad($ccl_id,"8","0",STR_PAD_LEFT)), "10", "Z",STR_PAD_LEFT) ;
                        $sql = "update cb_coupon_list set cl_cc_no='$ccl_no' where cl_idx = '$ccl_id'";
                        //log_message("ERROR", "id : ".$ccl_id. "/ ".$ccl_no."/".$sql);
                        $this->db->query($sql);
                        
                        $qty = $qty - 1;
                    }
                    
                } else {
                    if(file_exists($rtn['filedata']['full_path']))
                    {
                        $fp = fopen($rtn['filedata']['full_path'],"r");
                        $ins_qty = 0;
                        while( !feof($fp) )  {
                            $doc_data = fgets($fp);
                            if(!empty($doc_data)) {
                                $ccl = array();
                                $ccl['cl_cc_id'] = $rtn['cc_idx'];
                                $ccl['cl_creation_date'] = cdate('Y-m-d H:i:s');
                                $ccl['cl_cc_no'] = $doc_data;
                                $this->db->insert('coupon_list', $ccl);
                                $ins_qty = $ins_qty + 1;
                            }
                        }
                        
                        fclose($fp);
                        
                        if($ins_qty > 0) {
                            $sql = 'update cb_coupon set cc_coupon_qty = '.$ins_qty.' where cc_idx = '.$rtn['cc_idx'];
                            $this->db->query($sql);
                        } else {
                            $errMsg = "쿠폰 발급 수량이 없습니다.\n업로드 파일을 확인 하세요.";
                        }
                    } else {
                        $errMsg = "파일이 없습니다.\n업로드 파일을 확인 하세요.";
                    }
                    
                }
            }
            
            if(empty($errMsg)) {
                header("Location: /biz/coupon");
            } else {
                echo '<script type="text/javascript">alert("'.$errMsg.'");history.back();</script>';
            }
        } else {
            echo '<script type="text/javascript">alert("'.$rtn['error'].'");history.back();</script>';
        }
    }
    
    function coupon_procft($new=false)
    {
        $rtn = $this->coupon_saveft();
        //log_message("ERROR", "rtn coupon type : ".$rtn['coupon_type']);
        $errMsg = "";
        //log_message("ERROR", "coupon rtn : ".$rtn['error']);
        if(empty($rtn['error'])) {
            
            // 쿠폰 생성 방법이 자동이면 crc32 함수를 쿠폰 갯수 만큼 이용하여 자동 생성
            $sql = "select count(1) as cnt from cb_coupon_list ccl where ccl.cl_cc_id = '".$rtn['cc_idx']."'";
            $cnt = $this->db->query($sql)->row()->cnt;
            //log_message("ERROR", "coupon sql : ".$sql);
            if($cnt == 0) {
                if($rtn['coupon_type'] == 'A') {
                    $qty = $this->input->post("cc_coupon_qty");
                    
                    while($qty>0) {
                        
                        $ccl = array();
                        $ccl['cl_cc_id'] = $rtn['cc_idx'];
                        $ccl['cl_creation_date'] = cdate('Y-m-d H:i:s');
                        $this->db->insert('coupon_list', $ccl);
                        $ccl_id = $this->db->insert_id();
                        
                        $ccl_no = str_pad(crc32(str_pad($this->input->post("cc_idx"),"8","0",STR_PAD_LEFT).str_pad($ccl_id,"8","0",STR_PAD_LEFT)), "10", "Z",STR_PAD_LEFT) ;
                        $sql = "update cb_coupon_list set cl_cc_no='$ccl_no' where cl_idx = '$ccl_id'";
                        //log_message("ERROR", "id : ".$ccl_id. "/ ".$ccl_no."/".$sql);
                        $this->db->query($sql);
                        
                        $qty = $qty - 1;
                    }
                    
                } else {
                    if(file_exists($rtn['filedata']['full_path']))
                    {
                        $fp = fopen($rtn['filedata']['full_path'],"r");
                        $ins_qty = 0;
                        while( !feof($fp) )  {
                            $doc_data = fgets($fp);
                            if(!empty($doc_data)) {
                                $ccl = array();
                                $ccl['cl_cc_id'] = $rtn['cc_idx'];
                                $ccl['cl_creation_date'] = cdate('Y-m-d H:i:s');
                                $ccl['cl_cc_no'] = $doc_data;
                                $this->db->insert('coupon_list', $ccl);
                                $ins_qty = $ins_qty + 1;
                            }
                        }
                        
                        fclose($fp);
                        
                        if($ins_qty > 0) {
                            $sql = 'update cb_coupon set cc_coupon_qty = '.$ins_qty.' where cc_idx = '.$rtn['cc_idx'];
                            $this->db->query($sql);
                        } else {
                            $errMsg = "쿠폰 발급 수량이 없습니다.\n업로드 파일을 확인 하세요.";
                        }
                    } else {
                        $errMsg = "파일이 없습니다.\n업로드 파일을 확인 하세요.";
                    }
                    
                }
            }
            
            if(empty($errMsg)) {
                header("Location: /biz/coupon");
            } else {
                echo '<script type="text/javascript">alert("'.$errMsg.'");history.back();</script>';
            }
        } else {
            echo '<script type="text/javascript">alert("'.$rtn['error'].'");history.back();</script>';
        }
    }
    
    
    function coupon_delete($coupon_id)
    {
        $sql = "update cb_coupon set cc_used = 'N' where cc_idx = ".$coupon_id;
        
        $this->db->query($sql);
        
        if ($this->db->error()['code'] > 0) {
            echo '<script type="text/javascript">alert("삭제 오류");history.back();</script>';
        } else {
            header("Location: /biz/coupon");
        }
    }
    
}