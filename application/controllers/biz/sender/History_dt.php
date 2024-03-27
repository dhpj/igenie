<?php
class History_dt extends CB_Controller {
    /*
    * 占쏙옙占쏙옙 占싸듸옙占쌌니댐옙
    */
    protected $models = array('Board', 'Biz');
    
    /**
     * 占쏙옙占쌜몌옙 占싸듸옙占쌌니댐옙
     */
    protected $helpers = array('form', 'array');
    
    function __construct()
    {
        parent::__construct();
        
        /**
         * 占쏙옙占싱브러占쏙옙占쏙옙 占싸듸옙占쌌니댐옙
         */
        $this->load->library(array('querystring'));
    }
    
    public function index()
    {
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        // 	    $key = $this->input->post("mst_id");
        //if($key) { $this->view($key); return; }
        // 		// 占싱븝옙트 占쏙옙占싱브러占쏙옙占쏙옙 占싸듸옙占쌌니댐옙
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $view['param']['search_type'] = $this->input->get("search_type");
        $view['param']['search_for'] = $this->input->get("search_for");
        $view['param']['duration'] = ($this->input->get("duration")) ? $this->input->get("duration") : "week";
        
        // 占싱븝옙트占쏙옙 占쏙옙占쏙옙占싹몌옙 占쏙옙占쏙옙占쌌니댐옙
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $where = " 1=1 ";
        $param = array();
        if($view['param']['search_type'] && $view['param']['search_type']!="all" && $view['param']['search_for']) {
            $where .= " and ".$view['param']['search_type']." like ? "; array_push($param, "%".$view['param']['search_for']."%");
        }
        if($view['param']['duration']) {
            if($view['param']['duration']=="today") {
                $where .= " and a.mst_datetime between ? and ? "; array_push($param, date('Y-m-d').' 00:00:00'); array_push($param, date('Y-m-d H:i:s'));
            } else if($view['param']['duration']=="week") {
                $where .= " and a.mst_datetime between ? and ? "; array_push($param, date('Y-m-d', strtotime("-1week"))); array_push($param, date('Y-m-d H:i:s'));
            } else if($view['param']['duration']=="1month") {
                $where .= " and a.mst_datetime between ? and ? "; array_push($param, date('Y-m-d', strtotime("-1month"))); array_push($param, date('Y-m-d H:i:s'));
            } else if($view['param']['duration']=="3month") {
                $where .= " and a.mst_datetime between ? and ? "; array_push($param, date('Y-m-d', strtotime("-3month"))); array_push($param, date('Y-m-d H:i:s'));
            } else if($view['param']['duration']=="6month") {
                $where .= " and a.mst_datetime between ? and ? "; array_push($param, date('Y-m-d', strtotime("-6month"))); array_push($param, date('Y-m-d H:i:s'));
            }
        }
        
        $view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
				(	SELECT distinct a.mem_id
            FROM
                (SELECT
                    GET_PARENT_EQ_PRIOR_ID(mem_id) AS _id, @level AS level
                FROM
                    (SELECT @start_with:=".$this->member->item('mem_id').", @id:=@start_with) vars, cb_member_register
                WHERE
                    @id IS NOT NULL) ho
                    LEFT JOIN
                cb_member_register c ON c.mem_id = ho._id
                    INNER JOIN
                cb_member a ON (c.mem_id = a.mem_id or a.mem_id =".$this->member->item('mem_id')." )
                    AND a.mem_useyn = 'Y'
) c on a.mst_mem_id = c.mem_id", $where, $param);
        /*
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ".$this->member->item('mem_id').") initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         union select ".$this->member->item('mem_id')." mem_id, ".$this->member->item('mem_id')." mrg_recommend_mem_id
         ) c on a.mst_mem_id = c.mem_id", $where, $param);
         */
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        /*
         $sql = "
         select a.*, d.*
         from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ".$this->member->item('mem_id').") initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         union select ".$this->member->item('mem_id')." mem_id, ".$this->member->item('mem_id')." mrg_recommend_mem_id
         ) c on a.mst_mem_id = c.mem_id
         inner join cb_wt_send_profile d on a.mst_profile=d.spf_key
         where ".$where." order by a.mst_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
         */
        
        $sql = "
			select a.*, d.*, b.mem_username
			from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
(	SELECT distinct a.mem_id
            FROM
                (SELECT
                    GET_PARENT_EQ_PRIOR_ID(mem_id) AS _id, @level AS level
                FROM
                    (SELECT @start_with:=".$this->member->item('mem_id').", @id:=@start_with) vars, cb_member_register
                WHERE
                    @id IS NOT NULL) ho
                    LEFT JOIN
                cb_member_register c ON c.mem_id = ho._id
                    INNER JOIN
                cb_member a ON (c.mem_id = a.mem_id or a.mem_id =".$this->member->item('mem_id')." )
                    AND a.mem_useyn = 'Y'
)  c on a.mst_mem_id = c.mem_id
				left join cb_wt_send_profile d on a.mst_profile=d.spf_key
			where ".$where." order by (case when a.mst_reserved_dt <> '00000000000000' then a.mst_reserved_dt else DATE_FORMAT(a.mst_datetime, '%Y%m%d%H%i%s')  end) desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        
        
        //log_message("ERROR", "LSH 000001 SQL : ".$sql);
        //log_message("ERROR", "LSH 000001 param[0] : ".$param[0]);
        //log_message("ERROR", "LSH 000001 param[1] : ".$param[1]);
        
        $view['list'] = $this->db->query($sql, $param)->result();
        
        $view['view']['canonical'] = site_url();
        
        // 占싱븝옙트占쏙옙 占쏙옙占쏙옙占싹몌옙 占쏙옙占쏙옙占쌌니댐옙
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        /**
         * 占쏙옙占싱아울옙占쏙옙 占쏙옙占쏙옙占쌌니댐옙
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/sender',
            'layout' => 'layout',
            'skin' => 'history_dt',
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
    
    public function view($key)
    {
        $this->Biz_model->make_msg_log_table($this->member->item('mem_userid'));
        
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['key'] = $key;
        $view['perpage'] = 20;
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_result'] = $this->input->post("search_result");
        $view['param']['search_for'] = $this->input->post("search_for");
        
        // 占싱븝옙트占쏙옙 占쏙옙占쏙옙占싹몌옙 占쏙옙占쏙옙占쌌니댐옙
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $param = array(strval($key));
        $view['rs'] = $this->db->query("select a.*, b.mem_userid,b.mem_username, c.* from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id left join cb_wt_send_profile c on a.mst_profile=c.spf_key where a.mst_id=?", $param)->row();
        
        $where = "";
        if($view['param']['search_type'] && $view['param']['search_type']!="all") { $where .= " and MESSAGE_TYPE=? "; array_push($param, $view['param']['search_type']); }
        
        // 2019.01.22 이수환 대기항목 삭제, 성공 = (중간 관리자, 일반 사용자일때), 상위관리자는 현행으로
        //if($view['param']['search_result'] && $view['param']['search_result']!="all") { $where .= " and ifnull((case when RESULT = 'Y' then
        //                   'Y'
        //                 when RESULT = 'N' and MESSAGE like '%수신대기%' then
        //                   'W'
        //                 else
        //                   RESULT
        //            end), 'N')=? "; array_push($param, $view['param']['search_result']); }
        if($view['param']['search_result'] && $view['param']['search_result']!="all") {
            if ($view['param']['search_result']!="YW") {
                $where .= " and ifnull((case when RESULT = 'Y' then
                               'Y'
                             when RESULT = 'N' and MESSAGE like '%수신대기%' then
                               'W'
                             else
                               RESULT
                        end), 'N')=? ";
            } else {
                $where .= " and ifnull((case when RESULT = 'Y' then
                               'YW'
                             when RESULT = 'N' and MESSAGE like '%수신대기%' then
                               'YW'
                             else
                               RESULT
                        end), 'N')=? ";
            }
            array_push($param, $view['param']['search_result']);
        }
        
        if($view['param']['search_for']) { $where .= " and PHN like ? "; array_push($param, "%".$view['param']['search_for']."%"); }
        
        $view['total_rows'] = $this->Biz_model->get_table_count("cb_msg_".$view['rs']->mem_userid, "REMARK4=?".$where, $param);
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        //log_message("ERROR", "start!!");
        $sql = "
			select a.*,
                   (case when a.RESULT = 'Y' then
                           'Y'
                         when a.RESULT = 'N' and a.MESSAGE like  '%수신대기%' then
                           'W'
                         else
                           a.RESULT
                    end) as RESULT_MSG
			from cb_msg_".$view['rs']->mem_userid." a
			where REMARK4=? ".$where."   limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        
        //log_message("ERROR", "========>".$sql);
        $view['list'] = $this->db->query($sql, $param)->result();
        //log_message("ERROR", "end!!  - ".$sql);
        
        $view['view']['canonical'] = site_url();
        
        // 占싱븝옙트占쏙옙 占쏙옙占쏙옙占싹몌옙 占쏙옙占쏙옙占쌌니댐옙
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['isManager'] = 'N';
        
        /*
         * 2019.01.24 SSG
         * 관리자 DHN 으로 들어 왔을경우 "실패재전송" 메뉴를 보이게 하기 위해
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
         * 占쏙옙占싱아울옙占쏙옙 占쏙옙占쏙옙占쌌니댐옙
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/sender',
            'layout' => 'layout',
            'skin' => 'history_view_dt',
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
    
    public function reserve_cnacel() {
        $mst_id = $this->input->post("mst_id");
        $sql_TBL = "delete from TBL_REQUEST where remark4 = '".$mst_id."'";
        $this->db->query($sql_TBL);
        
        $sql_TBL_RES = "delete from TBL_REQUEST_RESULT where remark4 = '".$mst_id."'";
        $this->db->query($sql_TBL_RES);
        
        $sql_wt = "delete from cb_wt_msg_sent where mst_id = '".$mst_id."'";
        $this->db->query($sql_wt);
        
        $this->index();
    }
}
?>