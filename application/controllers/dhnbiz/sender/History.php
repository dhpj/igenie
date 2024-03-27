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
        // $view['isManager'] = 'N';
        // if($this->member->item('mem_id') != '3') {
        //     $login_stack = $this->session->userdata('login_stack');
        //     if($this->session->userdata('login_stack')) {
        //         $login_stack = $this->session->userdata('login_stack');
        //         if($login_stack[0] == '3') {
        //             $view['isManager'] = 'Y';
        //         }
        //     }
        // } else {
        //     $view['isManager'] = 'Y';
        // }
        // if($this->member->item('mem_id') == '3'){
        //     $view['isManager'] = 'Y';
        // }
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

        $view['param']['platform_type'] = ($this->input->get("platform_type")) ? $this->input->get("platform_type") : 'S';

        // if ($view['isManager'] == 'Y') {
        //     $view['param']['platform_type'] = ($this->input->get("platform_type")) ? $this->input->get("platform_type") : 'S';
        // }else{
        //     $view['param']['platform_type'] = ($this->input->get("platform_type")) ? $this->input->get("platform_type") : 'all';
        // }

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
        // 2021-10-14 API, 사이트발송 구분
        if ($view['param']['platform_type'] != 'all') {
            $where .= " and mst_sent_platform = '".$view['param']['platform_type']."'";
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
        $sales_cnt=0;
        if($this->member->item('mem_level')==17){
            $sql = "select count(1) as cnt from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
            $sales_cnt = $this->db->query($sql)->row()->cnt;
        }

        if($sales_cnt>0){
            $sql = "select * from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
            $sales_id = $this->db->query($sql)->row()->ds_id;
            $where .= " AND a.mst_mem_id in (
                    select mem_id
                    from cb_member cm
                    where cm.mem_id = '".$this->member->item('mem_id')."' OR cm.mem_management_mng_id = '".$sales_id."' OR  cm.mem_sales_mng_id = '".$sales_id."'
            )";
        }else{
            // 2021-11-03 조회 속도개선 query문 where에 recursive 추가
            $where .= " AND a.mst_mem_id in (
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
            )";
        }





        // 2021-11-03 조회 속도개선 query문 수정
        //         $view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
        // 				(	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
        //                     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //                     	FROM cb_member_register cmr
        //                     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //                     	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
        //                     	UNION ALL
        //                     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //                     	FROM cb_member_register cmr
        //                     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //                     	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
        //                     )
        //                     SELECT distinct mem_id
        //                     FROM cmem
        //                     union all
        //                     select mem_id
        //                     from cb_member cm
        //                     where cm.mem_id = '".$this->member->item('mem_id')."'
        //                     ) c on a.mst_mem_id = c.mem_id", $where, $param);
        // log_message("error", "cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id" . $where);
        $view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id", $where, $param);

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        // 2021-11-03 조회 속도개선 query문 수정
        //         $sql = "
        // 			select a.*, d.*, b.mem_username
        // 				, (select link_type from cb_alimtalk_ums where mst_id = a.mst_id) AS link_type /* 버튼링크 (editor.에디터, smart.스마트전단, 기타) */
        // 				, (case when b.mem_stmall_yn = 'Y' then (SELECT psd_order_yn FROM cb_pop_screen_data WHERE psd_code = (select psd_code from cb_alimtalk_ums where mst_id = a.mst_id LIMIT 1) LIMIT 1) ELSE 'N' END) AS stmall_yn /* 주문하기 사용여부 */
        // 			from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
        //             (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
        //             	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //             	FROM cb_member_register cmr
        //             	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //             	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
        //             	UNION ALL
        //             	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //             	FROM cb_member_register cmr
        //             	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //             	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
        //             )
        //             SELECT distinct mem_id
        //             FROM cmem
        //             union all
        //             select mem_id
        //             from cb_member cm
        //             where cm.mem_id = '".$this->member->item('mem_id')."'
        //             )  c on a.mst_mem_id = c.mem_id
        // 				left join cb_wt_send_profile_dhn d on a.mst_profile=d.spf_key and a.mst_mem_id = d.spf_mem_id
        // 			where ".$where."
        // 			order by (case when a.mst_reserved_dt <> '00000000000000' then a.mst_reserved_dt else DATE_FORMAT(a.mst_datetime, '%Y%m%d%H%i%s')  end) desc, mst_id desc
        // 			limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //ib플래그
        $sql = "
			select a.*, d.*, b.mem_username,  wmr.*
            /*, (select link_type from cb_alimtalk_ums where mst_id = a.mst_id) AS link_type*/ /* 버튼링크 (editor.에디터, smart.스마트전단, 기타) */
            , e.link_type /* 버튼링크 (editor.에디터, smart.스마트전단, 기타) */
            , e.html
            , e.url
            , e.psd_code
            , e.psd_url
            , e.pcd_code
            , e.order_url
				, (case when b.mem_stmall_yn = 'Y' then (SELECT psd_order_yn FROM cb_pop_screen_data WHERE psd_code = (select psd_code from cb_alimtalk_ums where mst_id = a.mst_id LIMIT 1) LIMIT 1) ELSE 'N' END) AS stmall_yn /* 주문하기 사용여부 */
			from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id
				left join ".config_item('ib_profile')." d on a.mst_profile=d.spf_key and a.mst_mem_id = d.spf_mem_id
                left join cb_alimtalk_ums e on a.mst_id = e.mst_id
                left join cb_wt_msg_rcs wmr on a.mst_id=wmr.msr_mst_id
			where ".$where."
			order by (case when a.mst_reserved_dt <> '00000000000000' then a.mst_reserved_dt else DATE_FORMAT(a.mst_datetime, '%Y%m%d%H%i%s')  end) desc, mst_id desc
			limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //echo "where : ". $where .", param : ". $param ."<br>";
		//echo $_SERVER['REQUEST_URI'] ." > Query : <br>". $sql ."<br>";
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);
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

        //ib플래그
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
			left join ".config_item('ib_profile')." d on a.mst_profile=d.spf_key and a.mst_mem_id = d.spf_mem_id
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
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['key'] = $key;
        $view['perpage'] = 20;
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_result'] = $this->input->post("search_result");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['search_yn'] = $this->input->post("search_yn");
		//echo "search_type : ". $view['param']['search_type'] ."<br>";

        $param = array(strval($key));
        //ib플래그
        $view['rs'] = $this->db->query("select a.*, b.mem_userid,b.mem_username, b.mem_rcs_yn, b.mem_id as detail_mem_id, c.* from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id left join ".config_item('ib_profile')." c on a.mst_profile=c.spf_key and a.mst_mem_id = c.spf_mem_id where a.mst_id=?", $param)->row();
        $sql = "select * from cb_wt_msg_rcs where msr_mst_id = '".$view['rs']->mst_id."' limit 1 ";
        $view['rcsd'] = $this->db->query($sql)->row();
        $sql = "select * from cb_wt_brand where brd_brand = '".$view['rcsd']->msr_brand."' limit 1 ";
        $view['rcsbrand'] = $this->db->query($sql)->row();
        $view['mms_img'] = $this->db->query("select * from cb_mms_images where mms_id = '".$view['rs']->mst_mms_content."'")->row();
        $where = "";
        if($view['param']['search_type'] && $view['param']['search_type']!="all") {
			if($view['param']['search_type'] == "sms"){ //문자
				//gs.WEB(A), ns.WEB(B), sm.WEB(C)
				$where .= " and MESSAGE_TYPE IN ('gs', 'ns', 'sm') ";
			}else if($view['param']['search_type'] == "at"){ //문자
				$where .= " and MESSAGE_TYPE IN ('at', 'ai') ";
			}else if($view['param']['search_type'] == "ft"){ //문자
				$where .= " and MESSAGE_TYPE = 'ft' ";
            }else if($view['param']['search_type'] == "rc"){ //RCS
				$where .= " and MESSAGE_TYPE IN ('rc', 'RC') ";
			}else{
				$where .= " and MESSAGE_TYPE=? "; array_push($param, $view['param']['search_type']);
			}
		}

		// 2021-05-25 카카오톡 API 버전 업으로 추가 시작
		$mst_template = "";
		// log_message("ERROR", "mst_type1 : ".$view['rs']->mst_type1);
		// log_message("ERROR", "mst_type2 : ".$view['rs']->mst_type2);
		if ($view['rs']->mst_type1 == "at" || $view['rs']->mst_type1 == "ai") {
		    $mst_template = $view['rs']->mst_template;
		} else if ($view['rs']->mst_type2 == "at" || $view['rs']->mst_type2 == "ai") {
		    $mst_template = $view['rs']->mst_2nd_alim_code;
		    // log_message("ERROR", "mst_type2 : ".$view['rs']->mst_2nd_alim_code);
		}

		//$mst_template_2nd = $view['rs']->mst_2nd_alim_code;
        //ib플래그
		$sql = "select * from ".config_item('ib_template')." where tpl_code = '".$mst_template."'";
		// log_message("ERROR", "sql : ".$sql);
		$view['tmp_rs'] = $this->db->query($sql)->row();
		// 2021-05-25 카카오톡 API 버전 업으로 추가 끝


        if($view['param']['search_result'] && $view['param']['search_result']!="all") {
            if ($view['param']['search_result']!="YW") {
                $where .= " and ifnull((case when RESULT = 'Y' then
                               'Y'
                             when RESULT <> 'Y' and MESSAGE like '%수신대기%' then
                               'W'
                               when RESULT = '' and MESSAGE like  '%수신거부%' then
                                'N'
                             else
                               RESULT
                        end), 'N')=? ";
            } else {
                $where .= " and ifnull((case when RESULT = 'Y' then
                               'YW'
                             when RESULT <> 'Y' and MESSAGE like '%수신대기%' then
                               'YW'
                             when RESULT = '' and MESSAGE like  '%수신거부%' then
                              'N'
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
                     when a.RESULT = '' and a.MESSAGE like  '%수신거부%' then
                       'N'
					 else
					   a.RESULT
				end) as RESULT_MSG
		from cb_msg_".$view['rs']->mem_userid." a
		where REMARK4=? ".$where."   limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //echo $sql ."<br>";
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

        if ($view['rs']->mst_type1 == "ft" || $view['rs']->mst_type1 == "at" || $view['rs']->mst_type1 == "ai" || $view['rs']->mst_type1 == "fti" || $view['rs']->mst_type1 == "ftw") {
            $this->db
                ->select("COUNT(*) AS cnt")
                ->from("cb_msg_" . $view['rs']->mem_userid)
                ->where(array("CODE !=" => "0000", "REMARK4" => $key));
        } else if ($view['rs']->mst_type2=="wc"||$view['rs']->mst_type2=="wcs"||$view['rs']->mst_type2=="wcm"){
            $this->db
                ->select("COUNT(*) AS cnt")
                ->from("cb_msg_" . $view['rs']->mem_userid)
                ->where(array("MESSAGE !=" => "웹(C) 성공", "REMARK4" => $key));
        } else if ($view['rs']->mst_type2=="rc"||$view['rs']->mst_type2=="rcs"||$view['rs']->mst_type2=="rcm"||$view['rs']->mst_type2=="rct"){
            $this->db
                ->select("COUNT(*) AS cnt")
                ->from("cb_msg_" . $view['rs']->mem_userid)
                ->where(array("MESSAGE !=" => "RCS 성공", "REMARK4" => $key));
        }
        $cnt = $this->db->get()->row()->cnt;
        $view["fail_send_flag"] = ($cnt > 0) ? true : false;

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
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 예약삭제 mst_id : ".$mst_id);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 예약삭제자 mem_id : ".$this->member->item("mem_id"));

        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $sql_TBL = "delete from DHN_REQUEST_IB where remark4 = '".$mst_id."'";
        }else{
            $sql_TBL = "delete from DHN_REQUEST where remark4 = '".$mst_id."'";
        }
        $del_flag = $this->db->query($sql_TBL);

        if(!empty($del_flag)){
            //2021-01-05 친구톡 2차 알림톡 내용 삭제
            //ib플래그
            if(config_item('ib_flag')=="Y"){
                $sql_TBL = "delete from DHN_REQUEST_IB_2ND where remark4 = '".$mst_id."'";
                $this->db->query($sql_TBL);

                $sql_TBL_RES = "delete from DHN_REQUEST_RESULT_IB where remark4 = '".$mst_id."'";
                $this->db->query($sql_TBL_RES);
            }else{
                $sql_TBL = "delete from DHN_REQUEST_2ND where remark4 = '".$mst_id."'";
                $this->db->query($sql_TBL);

                $sql_TBL_RES = "delete from DHN_REQUEST_RESULT where remark4 = '".$mst_id."'";
                $this->db->query($sql_TBL_RES);
            }

            $sql_wt = "delete from cb_wt_msg_sent where mst_id = '".$mst_id."'";
            $this->db->query($sql_wt);
            $sql_RCS = "delete from cb_wt_msg_rcs where msr_mst_id = '".$mst_id."'";
            $this->db->query($sql_RCS);
            log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 예약삭제 mst_id : ".$mst_id." > 정상처리");
        }else{
            log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 예약삭제 mst_id : ".$mst_id." > 에러");
            echo "<script>";
			echo "  alert('예약삭제 에러가 발생하였습니다. 잠시 후 다시 시도해주세요.');";
			//echo "  opener.location.reload();";
            echo " location.replace('/dhnbiz/sender/history')";
			// echo "  window.close();";
			echo "</script>";
            return false;
        }


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
        $db = $this->load->database("218g", TRUE);

		$mst_id = $this->input->post('mst_id');
		$short_url = $this->input->post('short_url');
		$sch = "";
		$data = array();
		$where = array();
        $wheres = "";
		if($short_url != ""){
			$where["short_url"] = $short_url;
            $wheres = " short_url = '".$short_url."' ";
		}else{
			$where["mst_id"] = $mst_id;
            $wheres = " mst_id = '".$mst_id."' ";
		}
		$data["html"] = $this->input->post('html');
		$this->db->update("cb_alimtalk_ums", $data, $where);


        $sql = "SELECT count(1) AS cnt FROM cb_alimtalk_ums dt WHERE ".$wheres;
        $cnt_smart = $db->query($sql)->row()->cnt;
        if($cnt_smart == 0){ //신규등록
            $sql = "SELECT * FROM cb_alimtalk_ums dt WHERE ".$wheres." limit 1";
            $alums = $this->db->query($sql)->row();
            $html = array();
            $html["mem_id"] = $alums->mem_id;
            $html["mst_id"] = $alums->mst_id;
            $html["short_url"] = $alums->short_url;
            $html["html"] = $alums->html;
            $html["url"] = $alums->url;
            $html["server_id"] = $alums->server_id;
            $html["link_type"] = $alums->link_type;
            $html["psd_code"] = $alums->psd_code;
            $html["psd_url"] = $alums->psd_url;
            $html["pcd_code"] = $alums->pcd_code;
            $html["pcd_url"] = $alums->pcd_url;
            $html["home_code"] = $alums->home_code;
            $html["home_url"] = $alums->home_url;
            $html["order_url"] = $alums->order_url;

			$db->insert("cb_alimtalk_ums", $html);
		}else{

			$db->update("cb_alimtalk_ums", $data, $where);
		}
	}

    function set_fail_phn(){
        $code = "OK";
        $msg = "";
        $mid = $this->input->post("mid");
        $uid = $this->input->post("uid");
        $type = $this->input->post("type");


        if ($type == "친구톡"  || $type == "알림톡" || $type == "알림톡(이미지)" || $type == "친구톡(이미지)" || $type == "친구톡(와이드)"){
            $this->db
                ->where(array("userid" => $uid))
                ->delete("cb_fail_phn");
            $this->db
                ->select(array("mem_userid AS userid", "PHN AS phnno"))
                ->from("cb_msg_" . $uid)
                ->where(array("CODE !=" => "0000", "REMARK4" => $mid));
            $list = $this->db->get()->result_array();
            $msg = $this->db->insert_batch("cb_fail_phn", $list);
            if ($msg <= 0){
                $code = "NG";
                $msg = "실패목록이 없습니다.";
            }
        } else if ($type == "웹(C) LMS"  || $type == "웹(C) SMS"  || $type == "웹(C) MMS" || $type == "RCS LMS" || $type == "RCS SMS" || $type == "RCS 템플릿" || $type == "RCS MMS"){
            $message = "";
            if ($type == "웹(C) LMS"  || $type == "웹(C) SMS"  || $type == "웹(C) MMS") $message = "웹(C) 성공";
            if ($type == "RCS LMS" || $type == "RCS SMS" || $type == "RCS 템플릿" || $type == "RCS MMS") $message = "RCS 성공";
            $this->db
                ->where(array("userid" => $uid))
                ->delete("cb_fail_phn");
            $this->db
                ->select(array("mem_userid AS userid", "PHN AS phnno"))
                ->from("cb_msg_" . $uid)
                ->where(array("MESSAGE !=" => $message, "REMARK4" => $mid));
            $list = $this->db->get()->result_array();
            $msg = $this->db->insert_batch("cb_fail_phn", $list);
            if ($msg <= 0){
                $code = "NG";
                $msg = "실패목록이 없습니다.";
            }
        }

        header('Content-Type: application/json');
        echo '{"code":"' . $code . '", "msg":"' . $msg . '"}';
    }
}
?>
