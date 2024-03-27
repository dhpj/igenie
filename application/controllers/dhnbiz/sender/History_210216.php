<?php
class History extends CB_Controller {
    /**
	* 모델을 로딩합니다
	*/
    protected $models = array('Board', 'Biz');
    
	/**
	* 헬퍼를 로딩합니다
	*/
    protected $helpers = array('form', 'array');
    
    function __construct(){
        parent::__construct();
        
        /**
		* 라이브러리를 로딩합니다
		*/
        $this->load->library(array('querystring', 'funn'));
    }
    
    //발신목록 > 전체 발신목록
	public function index(){
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
        if ($this->member->item('mem_level') > 1) {
            $view['param']['search_type'] = ($this->input->get("search_type")) ? $this->input->get("search_type") : 'b.mem_username';
        } else {
            $view['param']['search_type'] = ($this->input->get("search_type")) ? $this->input->get("search_type") : 'a.mst_content';
        }
        $view['param']['search_for'] = $this->input->get("search_for");
        $view['param']['duration'] = ($this->input->get("duration")) ? $this->input->get("duration") : "week";
        $view['param']['startDate'] = ($this->input->get("startDate"))? $this->input->get("startDate") : date('Y-m-d');
        $view['param']['endDate'] = ($this->input->get("endDate")) ? $this->input->get("endDate") : date('Y-m-d');
        
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $where = " 1=1 ";
        $param = array();
        if($view['param']['search_type'] && $view['param']['search_type']!="all" && $view['param']['search_for']) {
            if ($view['param']['search_type'] == 'a.mst_id') {
                $where .= " and ".$view['param']['search_type']." = ? "; array_push($param, $view['param']['search_for']);
            } else {
                $where .= " and ".$view['param']['search_type']." like ? "; array_push($param, "%".$view['param']['search_for']."%");
            }
        }
        if($view['param']['duration']) {
            if($view['param']['duration']=="today") {
                $where .= " and (case when a.mst_reserved_dt <> '00000000000000' then  STR_TO_DATE(mst_reserved_dt, '%Y%m%d%H%i%s') else a.mst_datetime  end) >= ? "; array_push($param, date('Y-m-d').' 00:00:00');
            } else if($view['param']['duration']=="week") {
                $where .= " and (case when a.mst_reserved_dt <> '00000000000000' then  STR_TO_DATE(mst_reserved_dt, '%Y%m%d%H%i%s') else a.mst_datetime  end) >= ? "; array_push($param, date('Y-m-d', strtotime("-1week")));
            } else if($view['param']['duration']=="1month") {
                $where .= " and (case when a.mst_reserved_dt <> '00000000000000' then  STR_TO_DATE(mst_reserved_dt, '%Y%m%d%H%i%s') else a.mst_datetime  end) >= ? "; array_push($param, date('Y-m-d', strtotime("-1month")));
            } else if($view['param']['duration']=="3month") {
                $where .= " and (case when a.mst_reserved_dt <> '00000000000000' then  STR_TO_DATE(mst_reserved_dt, '%Y%m%d%H%i%s') else a.mst_datetime  end) >= ? "; array_push($param, date('Y-m-d', strtotime("-3month")));
            } else if($view['param']['duration']=="6month") {
                $where .= " and (case when a.mst_reserved_dt <> '00000000000000' then  STR_TO_DATE(mst_reserved_dt, '%Y%m%d%H%i%s') else a.mst_datetime  end) >= ? "; array_push($param, date('Y-m-d', strtotime("-6month")));
            } else if($view['param']['duration']=="custom") {
                $startDate = str_replace('-','',$view['param']['startDate']); $startDate .= '000000';
                $endDate = str_replace('-','',$view['param']['endDate']); $endDate .= '235959';
                $where .= ' AND  (
		          ( mst_reserved_dt <> "00000000000000" AND (STR_TO_DATE(mst_reserved_dt,"%Y%m%d%H%i%s") BETWEEN "'.$startDate.'" AND "'.$endDate.'") ) OR
                  ( mst_reserved_dt = "00000000000000" AND (mst_datetime BETWEEN "'.$startDate.'" AND "'.$endDate.'") )
                )';
            }
        }
        
        $view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
				(	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
                    	UNION ALL
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                    )
                    SELECT distinct mem_id
                    FROM cmem
                    union all
                    select mem_id
                    from cb_member cm
                    where cm.mem_id = '".$this->member->item('mem_id')."'
                    ) c on a.mst_mem_id = c.mem_id", $where, $param);
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        
        $sql = "
			select a.*, d.*, b.mem_username
				, (select link_type from cb_alimtalk_ums where mst_id = a.mst_id) AS link_type /* 버튼링크 (editor.에디터, smart.스마트전단, 기타) */
			from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
            (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            	FROM cb_member_register cmr
            	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
            	UNION ALL
            	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            	FROM cb_member_register cmr
            	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            SELECT distinct mem_id
            FROM cmem
            union all
            select mem_id
            from cb_member cm
            where cm.mem_id = '".$this->member->item('mem_id')."'
            )  c on a.mst_mem_id = c.mem_id
				left join cb_wt_send_profile_dhn d on a.mst_profile=d.spf_key
			where ".$where." 
			order by (case when a.mst_reserved_dt <> '00000000000000' then a.mst_reserved_dt else DATE_FORMAT(a.mst_datetime, '%Y%m%d%H%i%s')  end) desc, mst_id desc 
			limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //echo "where : ". $where .", param : ". $param ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);
        $view['list'] = $this->db->query($sql, $param)->result();
        
        $view['view']['canonical'] = site_url();
        
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/dhnsender',
            'layout' => 'layout',
            'skin' => 'history',
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
    
    //발신목록 > 월별 발송건수
	public function monthly(){
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $year = ($this->input->get("year"))? $this->input->get("year") : date('Y'); //검색 년도
        $month = ($this->input->get("month")) ? $this->input->get("month") : date('n'); //검색 월
		$view['param']['year'] = $year;
		$view['param']['month'] = $month;
		//echo "view['param']['year'] : ". $view['param']['year'] .", view['param']['month'] : ". $view['param']['month'] ."<br>";
        
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $where = " 1=1 ";
        $param = array();
		$startDate = $year . $this->funn->fnZeroAdd($month) . '01000000';
		$endDate = $year . $this->funn->fnZeroAdd($month) . '31235959';
		//echo "startDate : ". $startDate .", endDate : ". $endDate ."<br>";
		
		$where .= " AND  (
		  ( mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '". $startDate ."' AND '". $endDate ."') ) OR
		  ( mst_reserved_dt = '00000000000000' AND (mst_datetime BETWEEN '". $startDate ."' AND '". $endDate ."') )
		)";
        
        $view['total_rows'] = $this->Biz_model->get_table_count("
		cb_wt_msg_sent a 
		inner join cb_member b on a.mst_mem_id=b.mem_id 
		inner join (
			WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
				FROM cb_member_register cmr
				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
				WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
				UNION ALL
				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
				FROM cb_member_register cmr
				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
			)
			SELECT distinct mem_id
			FROM cmem
			union all
			select mem_id
			from cb_member cm
			where cm.mem_id = '".$this->member->item('mem_id')."'
		) c on a.mst_mem_id = c.mem_id", $where, $param);
		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
        
        $sql = "
		SELECT
			  mst_date /* 발송일자 */
			, sum(mst_qty) AS mst_qty /* 발송건수 */
			, sum(mst_at) AS mst_at /* 알림톡 성공 건수 */
			, sum(mst_err_at) AS mst_err_at /* 알림톡 실패 건수 */
			, sum(mst_ft + mst_ft_img + mst_ft_wide_img) AS mst_ft /* 친구톡 성공 건수 */
			, sum(mst_err_ft + mst_err_ft_img + mst_err_ft_wide_img) AS mst_err_ft /* 친구톡 실패 건수 */
			, sum(mst_grs + mst_grs_sms + mst_grs_mms + mst_smt + mst_smt_sms + mst_smt_mms) AS mst_sms /* 문자 성공 건수 */
			, sum(mst_err_grs + mst_err_grs_sms + mst_err_grs_mms + mst_err_smt + mst_err_smt_sms + mst_err_smt_mms) AS mst_err_sms /* 문자 실패 건수 */
		FROM (
			SELECT
				mst_qty,
				mst_ft,
				case when (mst_type1 = 'fti') then mst_ft_img else 0 end as mst_ft_img,
				case when (mst_type1 = 'ftw') then mst_ft_img else 0 end as mst_ft_wide_img,
				mst_at,
				mst_sms,
				mst_lms,
				mst_mms,
				case when (mst_type2 = 'wa') then (mst_grs + mst_wait) else 0 end as mst_grs,
				case when (mst_type2 = 'was') then (mst_grs_sms + mst_wait) else 0 end as mst_grs_sms,
				case when (mst_type2 = 'wam') then (mst_grs + mst_wait) else 0 end as mst_grs_mms,
				mst_grs_biz_qty,
				case when (mst_type2 = 'wb') then (mst_nas + mst_wait) else 0 end as mst_nas,
				case when (mst_type2 = 'wbs') then (mst_nas_sms + mst_wait) else 0 end as mst_nas_sms,
				case when (mst_type2 = 'wbm') then (mst_nas + mst_wait) else 0 end as mst_nas_mms,
				mst_015,
				mst_phn,
				case when (mst_type2 = 'wc') then (mst_smt + mst_wait) else 0 end as mst_smt,
				case when (mst_type2 = 'wcs') then (mst_smt + mst_wait) else 0 end as mst_smt_sms,
				case when (mst_type2 = 'wcm') then (mst_smt + mst_wait) else 0 end as mst_smt_mms,
				mst_err_at,
				mst_err_ft,
				case when (mst_type1 = 'fti') then mst_err_ft_img else 0 end as mst_err_ft_img,
				case when (mst_type1 = 'ftw') then mst_err_ft_img else 0 end as mst_err_ft_wide_img,
				mst_err_sms,
				mst_err_lms,
				mst_err_mms,
				case when (mst_type2 = 'wa') then mst_err_grs else 0 end as mst_err_grs,
				mst_err_grs_sms,
				case when (mst_type2 = 'wam') then mst_err_grs else 0 end as mst_err_grs_mms,
				case when (mst_type2 = 'wb') then mst_err_nas else 0 end as mst_err_nas,
				mst_err_nas_sms,
				case when (mst_type2 = 'wbm') then mst_err_nas else 0 end as mst_err_nas_mms,
				mst_err_015,
				mst_err_phn,
				case when (mst_type2 = 'wc') then mst_err_smt else 0 end as mst_err_smt,
				case when (mst_type2 = 'wcs') then mst_err_smt else 0 end as mst_err_smt_sms,
				case when (mst_type2 = 'wcm') then mst_err_smt else 0 end as mst_err_smt_mms,
				mst_wait,
				(case when a.mst_reserved_dt <> '00000000000000' then left(a.mst_reserved_dt,8) else DATE_FORMAT(a.mst_datetime, '%Y%m%d') END) AS mst_date
			from cb_wt_msg_sent a 
			inner join cb_member b on a.mst_mem_id=b.mem_id 
			inner join
				(	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
					SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
					FROM cb_member_register cmr
					JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
					WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
					UNION ALL
					SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
					FROM cb_member_register cmr
					JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
					JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
				)
				SELECT distinct mem_id
				FROM cmem
				union all
				select mem_id
				from cb_member cm
				where cm.mem_id = '".$this->member->item('mem_id')."'
				)  c on a.mst_mem_id = c.mem_id
			left join cb_wt_send_profile_dhn d on a.mst_profile=d.spf_key
			where ". $where ."
		) t
		GROUP BY mst_date /* 발송일자 */
		order by mst_date ASC ";
		//echo $where ."<br>";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 발신목록 > 월별 발송건수 >  SQL : ". $sql);
		$view['mst_list'] = $this->db->query($sql, $param)->result();

		$view['view']['canonical'] = site_url();

		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/dhnsender',
			'layout' => 'layout',
			'skin' => 'history_monthly',
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
    
    //메시지발송 > 발신목록 > 상세보기
	public function view($key){
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
        $view['param']['search_yn'] = $this->input->post("search_yn");
        
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $param = array(strval($key));
        $view['rs'] = $this->db->query("select a.*, b.mem_userid,b.mem_username, c.* from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id left join cb_wt_send_profile_dhn c on a.mst_profile=c.spf_key where a.mst_id=?", $param)->row();
        $view['mms_img'] = $this->db->query("select * from cb_mms_images where mms_id = '".$view['rs']->mst_mms_content."'")->row();
        $where = "";
        if($view['param']['search_type'] && $view['param']['search_type']!="all") { $where .= " and MESSAGE_TYPE=? "; array_push($param, $view['param']['search_type']); }
        
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
        
        //if($view['param']['search_for']) { $where .= " and PHN like ? "; array_push($param, "%".$view['param']['search_for']."%"); }
        if($view['param']['search_for'] != "") {
            $where .= " and PHN like ? ";
            $phoneNo = '';
            if (strlen($view['param']['search_for']) >= 9 && substr($view['param']['search_for'], 0, 1) == '0') {
                $phoneNo = substr($view['param']['search_for'], 1);
            } else {
                $phoneNo = $view['param']['search_for'];
            }
            array_push($param, "%".$phoneNo."%");
        }
        
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
                //log_message("ERROR",'Login Stack : '. $login_stack[0]);
                if($login_stack[0] == '3') {
                    $view['isManager'] = 'Y';
                }
            }
        } else {
            $view['isManager'] = 'Y';
        }

		//UMS 에디터 내용 조회 (알림톡)
		$sql = "select * from cb_alimtalk_ums where mst_id = '".$key."' ";
        //echo $_SERVER['REQUEST_URI'] ." > 발신내역 > 알림톡 UMS 에디터 내용 조회 sql : ". $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 발신내역 > UMS 에디터 내용 조회 sql : ". $sql);
        $view['ums'] = $this->db->query($sql)->row();
        
        /**
         * 占쏙옙占싱아울옙占쏙옙 占쏙옙占쏙옙占쌌니댐옙
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/dhnsender',
            'layout' => 'layout',
            'skin' => 'history_view',
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
        $sql_TBL = "delete from DHN_REQUEST where remark4 = '".$mst_id."'";
        $this->db->query($sql_TBL);
        
        $sql_TBL_RES = "delete from DHN_REQUEST_RESULT where remark4 = '".$mst_id."'";
        $this->db->query($sql_TBL_RES);
        
        $sql_wt = "delete from cb_wt_msg_sent where mst_id = '".$mst_id."'";
        $this->db->query($sql_wt);
        
        $this->index();
    }
    
    public function get_datetime() {
        $now_date = date('Y-m-d H:i:s');
        
        header('Content-Type: application/json');
        echo '{"message":"success", "code":"success", "now_date":"'.$now_date.'"}';
    }

	//UMS 내용 저장
	public function ums_save() {
		//$ums_str = "select * from cb_alimtalk_ums where mst_id = '".$this->input->post('mst_id')."'";
		//$data = $this->db->query($ums_str)->row();
		//if(!empty($data)) {
		//	$data->html = $this->input->post('html');
		//	$this->db->replace("cb_alimtalk_ums", $data);
		//}
		
		$mst_id = $this->input->post('mst_id');
		$short_url = $this->input->post('short_url');
		$sch = "";
		$data = array();
		$where = array();
		if($short_url != ""){
			$where["short_url"] = $short_url;
		}else{
			$where["mst_id"] = $mst_id;
		}
		$data["html"] = $this->input->post('html');
		$this->db->update("cb_alimtalk_ums", $data, $where);
	}

}
?>