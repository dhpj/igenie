<?php
class Partner extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz', 'Biz_dhn', 'Unique_id', 'Deposit');

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
        $this->load->library(array('querystring', 'funn'));
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
        $view['perpage'] = 20;
        // $view['userid'] = $this->input->get("uid");
        $view['userid'] = empty($this->input->get("uid")) ? "ALL" : $this->input->get("uid"); //소속 ID
        $view['contract_type'] = empty($this->input->get("contract_type")) ? "" : $this->input->get("contract_type"); //계약형태 (S.스텐다드, P.프리미엄)
        $view['monthly_fee_yn'] = empty($this->input->get("monthly_fee_yn")) ? "ALL" : $this->input->get("monthly_fee_yn"); //월사용료 2021-02-09 추가
        $view['voucher_yn'] = empty($this->input->get("voucher_yn")) ? "" : $this->input->get("voucher_yn"); //바우처신청여부 2022-03-22 윤재박
        $view['dormancy_yn'] = empty($this->input->get("dormancy_yn")) ? "" : $this->input->get("dormancy_yn"); //휴면상태
        $view['search_type'] = empty($this->input->get("search_type")) ? "" : $this->input->get("search_type"); //검색조건
        $view['search_for'] = empty($this->input->get("search_for")) ? "" : $this->input->get("search_for"); //검색내용
        $view['page'] = empty($this->input->get("page")) ? 1 : $this->input->get("page");

		// if($this->member->item('mem_id') == "2") $view['userid'] = "3";

        $param = array();

        if($view['userid'] && is_numeric($view['userid'])) {
            array_push($param, $view['userid']);
        } else {
            array_push($param, $this->member->item('mem_id'));
        }

        $where = " mem_useyn='Y' ";
        if($view['contract_type'] && $view['contract_type'] != "ALL"){ //계약형태 (S.스텐다드, P.프리미엄)
            $where .= " and a.mem_contract_type = '". $view['contract_type'] ."' ";
        }
        if($view['monthly_fee_yn'] == "Y"){ //월사용료 있음 2021-02-09 추가
            $where .= " and a.mem_monthly_fee > 0 ";
        }else if($view['monthly_fee_yn'] == "N"){ //월사용료 없음 2021-02-09 추가
            $where .= " and a.mem_monthly_fee = 0 ";
        }
        if($view['search_for'] && $view['search_type']){
            $where .= "and replace(a.mem_".$view['search_type'].", ' ','') like replace(?, ' ','') ";
            array_push($param, "%".$view['search_for']."%");
        }
        if($view['voucher_yn'] == "Y"){ //바우처신청여부 2022.03.22 추가 윤재박
            $where .= " and a.mem_voucher_yn = 'Y' ";
        }else if($view['voucher_yn'] == "N"){
            $where .= "  and a.mem_voucher_yn = 'N' " ;
		}

        if($view['dormancy_yn'] == "Y"){ //휴면계정 유무 2021.12.30 추가
            $where .= " and d.mdd_dormant_flag = 1 ";
        }else if($view['dormancy_yn'] == "N"){
            $where .= " and (ISNULL(d.mdd_dormant_flag) OR d.mdd_dormant_flag = 0) " ;
		    }


        $sales_cnt=0;
        if($this->member->item('mem_level')==17){
            $sql = "select count(1) as cnt from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
            $sales_cnt = $this->db->query($sql)->row()->cnt;
        }

        if($sales_cnt>0){
            $sql = "select * from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
            $sales_id = $this->db->query($sql)->row()->ds_id;
            $where .= " AND
                    a.mem_id <> '".$this->member->item('mem_id')."' AND ( a.mem_management_mng_id = '".$sales_id."' OR  a.mem_sales_mng_id = '".$sales_id."'
            ) ";
            if($view['userid'] && is_numeric($view['userid'])) {
                $where .= " AND c.mrg_recommend_mem_id = '".$view['userid']."'";
            }
            $rs = $this->Biz_model->get_sales_partner_list($param, $where, $view['page'], $view['perpage']);
        }else{
            $rs = $this->Biz_model->get_partner_list($param, $where, $view['page'], $view['perpage'], true);
        }


        $view['rs'] = $rs->lists;
        $view['total_rows'] = $rs->total_rows;
        $view['view']['canonical'] = site_url();

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = $view['page'];
        $page_cfg['total_rows'] = $rs->total_rows;
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['page']);

        $view['page_html'] = $this->pagination->create_links();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        if($sales_cnt>0){
            //소속 리스트
    		$view['users'] = $this->Biz_model->get_sales_sub_manager($this->member->item('mem_id'), $sales_id);
        }else{
            //소속 리스트
    		$view['users'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));
        }


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
            'path' => 'biz/partner',
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


    public function no_lists()
    {
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['perpage'] = 20;
        // $view['userid'] = $this->input->get("uid");
        $view['userid'] = empty($this->input->get("uid")) ? "ALL" : $this->input->get("uid"); //소속 ID
        // $view['contract_type'] = empty($this->input->get("contract_type")) ? "" : $this->input->get("contract_type"); //계약형태 (S.스텐다드, P.프리미엄)
        // $view['monthly_fee_yn'] = empty($this->input->get("monthly_fee_yn")) ? "ALL" : $this->input->get("monthly_fee_yn"); //월사용료 2021-02-09 추가
        // $view['voucher_yn'] = empty($this->input->get("voucher_yn")) ? "" : $this->input->get("voucher_yn"); //바우처신청여부 2022-03-22 윤재박
        // $view['dormancy_yn'] = empty($this->input->get("dormancy_yn")) ? "" : $this->input->get("dormancy_yn"); //휴면상태
        $view['search_type'] = empty($this->input->get("search_type")) ? "" : $this->input->get("search_type"); //검색조건
        $view['search_for'] = empty($this->input->get("search_for")) ? "" : $this->input->get("search_for"); //검색내용
        $view['page'] = empty($this->input->get("page")) ? 1 : $this->input->get("page");

		// if($this->member->item('mem_id') == "2") $view['userid'] = "3";

        $param = array();

        if($view['userid'] && is_numeric($view['userid'])) {
            array_push($param, $view['userid']);
        } else {
            array_push($param, $this->member->item('mem_id'));
        }

        $where = " mem_useyn='N' ";
        // if($view['contract_type'] && $view['contract_type'] != "ALL"){ //계약형태 (S.스텐다드, P.프리미엄)
        //     $where .= " and a.mem_contract_type = '". $view['contract_type'] ."' ";
        // }
        // if($view['monthly_fee_yn'] == "Y"){ //월사용료 있음 2021-02-09 추가
        //     $where .= " and a.mem_monthly_fee > 0 ";
        // }else if($view['monthly_fee_yn'] == "N"){ //월사용료 없음 2021-02-09 추가
        //     $where .= " and a.mem_monthly_fee = 0 ";
        // }
        if($view['search_for'] && $view['search_type']){
            $where .= "and replace(a.mem_".$view['search_type'].", ' ','') like replace(?, ' ','') ";
            array_push($param, "%".$view['search_for']."%");
        }
        // if($view['voucher_yn'] == "Y"){ //바우처신청여부 2022.03.22 추가 윤재박
        //     $where .= " and a.mem_voucher_yn = 'Y' ";
        // }else if($view['voucher_yn'] == "N"){
        //     $where .= "  and a.mem_voucher_yn = 'N' " ;
		// }
        //
        // if($view['dormancy_yn'] == "Y"){ //휴면계정 유무 2021.12.30 추가
        //     $where .= " and d.mdd_dormant_flag = 1 ";
        // }else if($view['dormancy_yn'] == "N"){
        //     $where .= " and (ISNULL(d.mdd_dormant_flag) OR d.mdd_dormant_flag = 0) " ;
		//     }


        // $sales_cnt=0;
        // if($this->member->item('mem_level')==17){
        //     $sql = "select count(1) as cnt from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
        //     $sales_cnt = $this->db->query($sql)->row()->cnt;
        // }

        // if($sales_cnt>0){
        //     $sql = "select * from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
        //     $sales_id = $this->db->query($sql)->row()->ds_id;
        //     $where .= " AND
        //             a.mem_id <> '".$this->member->item('mem_id')."' AND ( a.mem_management_mng_id = '".$sales_id."' OR  a.mem_sales_mng_id = '".$sales_id."'
        //     ) ";
        //     if($view['userid'] && is_numeric($view['userid'])) {
        //         $where .= " AND c.mrg_recommend_mem_id = '".$view['userid']."'";
        //     }
        //     $rs = $this->Biz_model->get_sales_partner_list($param, $where, $view['page'], $view['perpage']);
        // }else{
            $rs = $this->Biz_model->get_no_partner_list($param, $where, $view['page'], $view['perpage']);
        // }


        // log_message("error", count($rs->lists));
        $view['rs'] = $rs->lists;
        $view['total_rows'] = $rs->total_rows;
        $view['view']['canonical'] = site_url();

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = $view['page'];
        $page_cfg['total_rows'] = $rs->total_rows;
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['page']);

        $view['page_html'] = $this->pagination->create_links();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        // if($sales_cnt>0){
        //     //소속 리스트
    	// 	$view['users'] = $this->Biz_model->get_sales_sub_manager($this->member->item('mem_id'), $sales_id);
        // }else{
            //소속 리스트
    		$view['users'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));
        // }


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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'no_lists',
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

    //파트너 목록
	public function inc_lists() {
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['userid'] = $this->input->post("uid"); //소속 ID
        $view['param']['contract_type'] = $this->input->post("contract_type"); //계약형태 (S.스텐다드, P.프리미엄)
        $view['param']['monthly_fee_yn'] = $this->input->post("monthly_fee_yn"); //월사용료 2021-02-09 추가
        $view['param']['search_type'] = $this->input->post("search_type"); //검색조건
        $view['param']['search_for'] = $this->input->post("search_for"); //검색내용
        $view['param']['dormancy_yn'] = $this->input->post("dormancy_yn"); //휴면상태
        $view['param']['page'] = $this->input->post("page");
		if($this->member->item('mem_id') == "2") $view['param']['userid'] = "3";
		//echo $this->member->item('mem_id');
        $param = array();
        if($view['param']['userid'] && $view['param']['userid']!="ALL" && is_numeric($view['param']['userid'])) { array_push($param, $view['param']['userid']); } else { array_push($param, $this->member->item('mem_id')); }

        $where = " mem_useyn='Y' ";
        if($view['param']['contract_type']){ //계약형태 (S.스텐다드, P.프리미엄)
            $where .= " and a.mem_contract_type = '". $view['param']['contract_type'] ."' ";
        }
        if($view['param']['monthly_fee_yn'] == "Y"){ //월사용료 있음 2021-02-09 추가
            $where .= " and a.mem_monthly_fee > 0 ";
        }else if($view['param']['monthly_fee_yn'] == "N"){ //월사용료 없음 2021-02-09 추가
            $where .= " and a.mem_monthly_fee = 0 ";
          }
        if($view['param']['search_for'] && $view['param']['search_type']){
            $where .= "and replace(a.mem_".$view['param']['search_type'].", ' ','') like replace(?, ' ','') ";
            array_push($param, "%".$view['param']['search_for']."%");
        }

        if($view['param']['dormancy_yn'] == "Y"){ //휴면계정 유무 2021.12.30 추가
            $where .= " and d.mdd_dormant_flag = 1 ";
        }else if($view['param']['dormancy_yn'] == "N"){
            $where .= " and (ISNULL(d.mdd_dormant_flag) OR d.mdd_dormant_flag = 0) " ;
		    }

        $rs = $this->Biz_model->get_partner_list($param, $where, $view['param']['page'], $view['perpage']);
        $view['rs'] = $rs->lists;
        $view['total_rows'] = $rs->total_rows;
        $view['view']['canonical'] = site_url();

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = $view['param']['page'];
        $page_cfg['total_rows'] = $rs->total_rows;
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);

        $view['page_html'] = $this->pagination->create_links();

        $this->load->view('biz/partner/inc_lists',$view);
    }

    public function login_log_lists()
    {
        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        //소속 리스트
		$view['users'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));

        $view['perpage'] = 30;
        $view['param']['userid'] = $this->input->get("uid"); //소속 ID


        if(!empty($this->input->get("search_type"))){
            if($this->input->get("search_type")=="1"){ //업체명
                $view['param']['search_type'] = "b.mem_username";
            }else if($this->input->get("search_type")=="2"){ //로그내용
                $view['param']['search_type'] = "a.mll_reason";
            }else if($this->input->get("search_type")=="3"){ //아이피
                $view['param']['search_type'] = "a.mll_ip";
            }
            $view['search_type']=$this->input->get("search_type");
        }else{
            $view['param']['search_type'] = ""; //검색조건
        }

        $view['param']['search_for'] = $this->input->get("search_for"); //검색내용
        if(!empty($this->input->get("search_for"))){
            $view["search_for"] = $this->input->get("search_for");
        }
        $view['param']['page'] = (!empty($this->input->get("page")))? $this->input->get("page"): 1;
		// if($this->member->item('mem_id') == "2") $view['param']['userid'] = "3";
		//echo $this->member->item('mem_id');
    //echo $this->input->post("contract_type")."<br>";
        // $param = array();
        // if($view['param']['userid'] && $view['param']['userid']!="ALL" && is_numeric($view['param']['userid'])) { array_push($param, $view['param']['userid']); } else { array_push($param, $this->member->item('mem_id')); }


        $where = " where 1=1 and a.mll_reason NOT LIKE '로그아웃%' ";
        if($view['param']['userid'] && $view['param']['userid']!="ALL" && is_numeric($view['param']['userid'])) {
            $where .= "and c.mrg_recommend_mem_id = '".$view['param']['userid']."' ";
            // array_push($param, $view['param']['userid']); } else { array_push($param, $this->member->item('mem_id'));
            }
        // if($view['param']['contract_type']){ //계약형태 (S.스텐다드, P.프리미엄)
        //     $where .= " and a.mem_contract_type = '". $view['param']['contract_type'] ."' ";
        // }
        // if($view['param']['monthly_fee_yn'] == "Y"){ //월사용료 있음 2021-02-09 추가
        //     $where .= " and a.mem_monthly_fee > 0 ";
        // }else if($view['param']['monthly_fee_yn'] == "N"){ //월사용료 없음 2021-02-09 추가
        //     $where .= " and a.mem_monthly_fee = 0 ";
        // }

        // if(!empty($this->input->get("selectdate"))){
        //     $view["selectdate"] = $this->input->get("selectdate");
        //     $where .= "and a.mll_datetime between '".$view['selectdate']." 00:00:00' and '".$view['selectdate']." 23:59:59'  ";
        // }
        // $now_date = date("Y-m-d",strtotime('first day of -2 month'));

        $view['startDate'] = ($this->input->get("startDate"))? $this->input->get("startDate") : date("Y-m-d", mktime(0, 0, 0, intval(date('m')), 1, intval(date('Y'))  ));
        $view['endDate'] = ($this->input->get("endDate")) ? $this->input->get("endDate") : date("Y-m-d", mktime(0, 0, 0, intval(date('m'))+1, 0, intval(date('Y'))  ));



        $where .= "  AND a.mll_datetime BETWEEN '".$view['startDate'] ." 00:00:00' AND '". $view['endDate'] ." 23:59:59' ";


        if($view['param']['search_for'] && $view['param']['search_type']){
            $where .= "and ".$view['param']['search_type']." like '%".$view['param']['search_for']."%' ";
            // array_push($param, "%".$view['param']['search_for']."%");
        }
        $view['dormancy'] = $this->input->get("dormancy");
        if(!empty($view['dormancy'])&&$view['dormancy']!="ALL"){ //휴면계정 유무 2021.12.30 추가

            if($view['dormancy']=="Y"){ //휴면
                $where .= " and e.mdd_dormant_flag = 1 ";
            }else{ //정상
                $where .= " and (ISNULL(e.mdd_dormant_flag) OR e.mdd_dormant_flag = 0) " ;
            }
        }

        $view['voucher'] = $this->input->get("voucher");
        if(!empty($view['voucher'])&&$view['voucher']!="ALL"){ //바우처 여부 추가 2022-06-09 윤재박

            if($view['voucher']=="Y"){ //바우처
                $where .= " and b.mem_voucher_yn = 'Y' ";
            }else{ //일반
                $where .= " and b.mem_voucher_yn = 'N' " ;
            }
        }
        //print_r($view['param']);
		    //echo "where : ". $where ."<br>";

        $sql = "SELECT a.*, b.*, d.mem_username as adminname, e.mem_id as dormant_mem_id, e.mdd_dormant_flag,
                    (select f.mll_datetime from cb_member_login_log f where f.mll_reason like '로그아웃%' and b.mem_id = f.mem_id and  f.mll_datetime > a.mll_datetime order by f.mll_datetime desc limit 1) as logout_log
        FROM cb_member_login_log a LEFT JOIN cb_member b ON a.mem_id = b.mem_id LEFT JOIN cb_member_register c ON a.mem_id = c.mem_id LEFT JOIN cb_member d ON c.mrg_recommend_mem_id = d.mem_id LEFT JOIN cb_member_dormant_dhn e ON a.mem_id = e.mem_id
        ".$where."
        order by a.mll_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        // log_message("ERROR", "sql : ".$sql);
        // $rs = $this->Biz_model->get_partner_list($param, $where, $view['param']['page'], $view['perpage']);
        // $view['rs'] = $rs->lists;
        $view['rs'] = $this->db->query($sql)->result();

        // for($n=0;$n<count($view['rs']);$n++) {
        //     $sql = "select count(*) as cnt, mll_datetime from cb_member_login_log where mll_reason like '%".$view['rs'][$n]->mll_id."' LIMIT 1";
        //     // log_message("ERROR", "deposit > serviceplus : ". $sql);
        //     $logoutplus = $this->db->query($sql)->row();
        //     if(!empty($logoutplus->cnt)) {
        //         $view['rs'][$n]->logout_log = $logoutplus->mll_datetime;
        //     }
        // }

        $sql = "SELECT count(*) as cnt FROM cb_member_login_log a LEFT JOIN cb_member b ON a.mem_id = b.mem_id LEFT JOIN cb_member_register c ON a.mem_id = c.mem_id  LEFT JOIN cb_member_dormant_dhn e ON a.mem_id = e.mem_id ".$where;
        // log_message("ERROR", "sql : ".$sql);
        // $view['total_rows'] = $rs->total_rows;
        $view['total_rows'] = $this->db->query($sql)->row()->cnt;

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'login_log_lists',
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

    public function due_download() {
	    $view = array();
        //
        if(!empty($this->input->post("type"))){
            if($this->input->post("type")=="1"){ //업체명
                $view['param']['type'] = "b.mem_username";
            }else if($this->input->post("type")=="2"){ //로그내용
                $view['param']['type'] = "a.mll_reason";
            }else if($this->input->post("type")=="3"){ //아이피
                $view['param']['type'] = "a.mll_ip";
            }
            $view['type']=$this->input->post("type");
        }else{
            $view['param']['type'] = ""; //검색조건
        }

        $view['param']['search_for'] = $this->input->post("searchFor"); //검색내용
        if(!empty($this->input->post("searchFor"))){
            $view["search_for"] = $this->input->post("searchFor");
        }
        $view['param']['page'] = (!empty($this->input->post("page")))? $this->input->post("page"): 1;

        $where = " where 1=1 and mll_reason NOT LIKE '로그아웃%' ";
        if($view['param']['userid'] && $view['param']['userid']!="ALL" && is_numeric($view['param']['userid'])) {
            $where .= "and c.mrg_recommend_mem_id = '".$view['param']['userid']."' ";
        }

        // $now_date = date("Y-m-d",strtotime('first day of -2 month'));

        $view['startDate'] = ($this->input->post("startDate"))? $this->input->post("startDate") : date("Y-m-d", mktime(0, 0, 0, intval(date('m')), 1, intval(date('Y'))  ));
        $view['endDate'] = ($this->input->post("endDate")) ? $this->input->post("endDate") : date("Y-m-d", mktime(0, 0, 0, intval(date('m'))+1, 0, intval(date('Y'))  ));


        $where .= "  AND a.mll_datetime BETWEEN '".$view['startDate'] ." 00:00:00' AND '". $view['endDate'] ." 23:59:59' ";


        if($view['param']['search_for'] && $view['param']['type']){
            $where .= "and ".$view['param']['type']." like '%".$view['param']['search_for']."%' ";
            // array_push($param, "%".$view['param']['search_for']."%");
        }
        $view['dormancy'] = $this->input->post("dormancy");
        if(!empty($view['dormancy'])&&$view['dormancy']!="ALL"){ //휴면계정 유무 2021.12.30 추가

            if($view['dormancy']=="Y"){ //휴면
                $where .= " and e.mdd_dormant_flag = 1 ";
            }else{ //정상
                $where .= " and (ISNULL(e.mdd_dormant_flag) OR e.mdd_dormant_flag = 0) " ;
            }
        }

        $view['voucher'] = $this->input->post("voucher");
        if(!empty($view['voucher'])&&$view['voucher']!="ALL"){ //바우처 여부 추가 2022-06-09 윤재박

            if($view['voucher']=="Y"){ //바우처
                $where .= " and b.mem_voucher_yn = 'Y' ";
            }else{ //일반
                $where .= " and b.mem_voucher_yn = 'N' " ;
            }
        }



        $sql = "SELECT a.*, b.*, d.mem_username as adminname, e.mem_id as dormant_mem_id, e.mdd_dormant_flag,
            (select f.mll_datetime from cb_member_login_log f where f.mll_reason like '로그아웃%' and b.mem_id = f.mem_id and  f.mll_datetime > a.mll_datetime order by f.mll_datetime desc limit 1) as logout_log
        FROM cb_member_login_log a LEFT JOIN cb_member b ON a.mem_id = b.mem_id LEFT JOIN cb_member_register c ON a.mem_id = c.mem_id LEFT JOIN cb_member d ON c.mrg_recommend_mem_id = d.mem_id LEFT JOIN cb_member_dormant_dhn e ON a.mem_id = e.mem_id
        ".$where."
        order by a.mll_datetime desc ";
        // log_message("ERROR", "sql : ".$sql);

        $list = $this->db->query($sql)->result();


        // for($n=0;$n<count($list);$n++) {
        //     $sql = "select count(*) as cnt, mll_datetime from cb_member_login_log where mll_reason like '%".$list[$n]->mll_id."' LIMIT 1";
        //     // log_message("ERROR", "deposit > serviceplus : ". $sql);
        //     $logoutplus = $this->db->query($sql)->row();
        //     if(!empty($logoutplus->cnt)) {
        //         $list[$n]->logout_log = $logoutplus->mll_datetime;
        //     }
        // }


	    $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

	    $tmp_id = $this->input->post('tmp_id');
	    $ids = explode(',', $tmp_id);

	    // 라이브러리를 로드한다.
	    $this->load->library('excel');

	    // 시트를 지정한다.
	    $this->excel->setActiveSheetIndex(0);
	    $this->excel->getActiveSheet()->setTitle('Sheet1');

	    // 필드명을 기록한다.
	    // 글꼴 및 정렬
	    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
	        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
	        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
	    ),	'A1:K1');

	    $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
	    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "접속시간");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "상품");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "소속");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "계정(ID)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "사업자번호");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "아이피");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "접속경로/이전페이지");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "로그내용");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "로그아웃");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "휴면상태");

	    $cnt = 1;
	    $row = 2;

	    foreach($list as $r) {
	        $str2n = "";
            $mll_url_plus = "";
            if(!empty($r->mll_referer)){
                $mll_url_plus="(".$r->mll_referer.")";
            }
             $turm_string="";
             if(!empty($r->logout_log)){
                 $turm_time = strtotime(date($r->logout_log)." GMT") - strtotime(date($r->mll_datetime)." GMT");
                 if($trum_time > 3600){
                     $turm_string = "(".date('H시 i분 s초', $turm_time).")";
                 }else{
                     $turm_string = "(".date('i분 s초', $turm_time).")";
                 }

              }
              $success = "";
              if($r->mll_success=='1'){
                  $success ="[성공]";
              }else{
                  $success ="[실패]";
              }

              $dormant="정상";
              if(!empty($r->dormant_mem_id)) {
                if($r->mdd_dormant_flag == 1){
                    $dormant="휴면";
                }
              }

              $biz_text = "";
              if(!empty($r->mem_biz_reg_no)){
                  if(strlen($r->mem_biz_reg_no)==10){
                      $biz_text = substr($r->mem_biz_reg_no, 0, 3)."-".substr($r->mem_biz_reg_no, 3, 2)."-".substr($r->mem_biz_reg_no, 5, 5);
                  }
              }

	        // switch($r->mem_2nd_send) { case 'GREEN_SHOT' : $str2n = '웹(A)'; break; case 'NASELF': $str2n = '웹(B)'; break; case 'SMART':$str2n = '웹(C)'; break; default:$str2n = '';}
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->mll_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '지니');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->adminname);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->mem_userid);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->mem_username);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $biz_text);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->mll_ip);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->mll_url.$mll_url_plus);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $success.$r->mll_reason.$turm_string);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $r->logout_log);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $dormant);
	        $row++;
	        $cnt++;
	    }







	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="login_log_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');

	}

    public function download() {
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['userid'] = $this->input->post("uid");
        $view['param']['contract_type'] = $this->input->post("contract_type");
        $view['param']['monthly_fee_yn'] = $this->input->post("monthly_fee_yn");
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['dormancy_yn'] = $this->input->post("dormancy_yn");
        $view['param']['voucher_yn'] = $this->input->post("voucher_yn");
        $view['param']['page'] = $this->input->post("page");
        $param = array();
        if($view['param']['userid'] && $view['param']['userid']!="ALL" && is_numeric($view['param']['userid'])) { array_push($param, $view['param']['userid']); } else { array_push($param, $this->member->item('mem_id')); }

        $where = " mem_useyn='Y' ";

        if($view['param']['contract_type'] != 'ALL'){ //계약형태 (S.스텐다드, P.프리미엄)
            $where .= " and a.mem_contract_type = '". $view['param']['contract_type'] ."' ";
        }
        if($view['param']['monthly_fee_yn'] == "Y"){ //월사용료 있음 2021-02-09 추가
            $where .= " and a.mem_monthly_fee > 0 ";
        }else if($view['param']['monthly_fee_yn'] == "N"){ //월사용료 없음 2021-02-09 추가
            $where .= " and a.mem_monthly_fee = 0 ";
		    }
        if($view['param']['dormancy_yn'] == "Y"){ //휴면계정 유무 2021.12.30 추가
            $where .= " and d.mdd_dormant_flag = 1 ";
        }else if($view['param']['dormancy_yn'] == "N"){
            $where .= " and (ISNULL(d.mdd_dormant_flag) OR d.mdd_dormant_flag = 0) " ;
		    }
        if($view['param']['voucher_yn'] == "Y"){ //바우처신청여부 2022.03.22 추가 윤재박
            $where .= " and e.vad_mem_id is not null ";
        }else if($view['param']['voucher_yn'] == "N"){
            $where .= "  and e.vad_mem_id is null " ;
		}

        if($view['param']['search_for'] && $view['param']['search_type']) {
            $where .= " and a.mem_".$view['param']['search_type']." like ?";
            array_push($param, "%".$view['param']['search_for']."%");
        }

        // $sql = "
		// SELECT  a.*,
        //         a.mem_deposit total_deposit,
        //         c.mrg_recommend_mem_id,
        //         IFNULL((SELECT
        //                         mem_username
        //                     FROM
        //                         cb_member
        //                     WHERE
        //                         mem_id = c.mrg_recommend_mem_id),
        //                 '') mrg_recommend_mem_username, d.mdd_dormant_flag
        //     FROM
        //         (SELECT
        //             GET_PARENT_EQ_PRIOR_ID(mem_id) AS _id, @level AS level
        //         FROM
        //             (SELECT @start_with:=?, @id:=@start_with) vars, cb_member_register
        //         WHERE
        //             @id IS NOT NULL) ho
        //             JOIN
        //         cb_member_register c ON c.mem_id = ho._id
        //             INNER JOIN
        //         cb_member a ON (c.mem_id = a.mem_id)
        //             AND a.mem_useyn = 'Y'
        //             LEFT JOIN
        //         cb_member_dormant_dhn d on a.mem_id = d.mem_id
        //             LEFT JOIN
        //          cb_wt_voucher_addon e on a.mem_id = e.vad_mem_id
		// 	where ".$where."
		// 	order by c.mrg_recommend_mem_id, a.mem_level desc, a.mem_username ";

        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                  FROM cb_member_register cmr
                  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                 WHERE cm.mem_id = ? AND cmr.mem_id <> cm.mem_id
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                  FROM cb_member_register cmr
                  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                  JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                )
            SELECT a.*,
                   a.mem_deposit total_deposit,
                   c.parent_id AS mrg_recommend_mem_id,
                   e.vad_mem_id,
                     IFNULL(
                       (SELECT mem_username
                           FROM cb_member
                          WHERE mem_id = c.parent_id),
                         '') mrg_recommend_mem_username, d.mdd_dormant_flag,
                   e.*,
                   f.*,
                   g.max_date
            FROM cmem c
            INNER JOIN cb_member a on a.mem_id = c.mem_id
                                   and a.mem_useyn = 'Y'
            LEFT JOIN cb_member_dormant_dhn d on a.mem_id = d.mem_id
            LEFT JOIN cb_wt_voucher_addon e on a.mem_id = e.vad_mem_id
            LEFT JOIN cb_wt_member_addon f on a.mem_id = f.mad_mem_id
            LEFT JOIN (
                SELECT
                    mst_mem_id,
                    MAX(
                    	IF(
                    		mst_reserved_dt = '00000000000000'
                    	  , mst_datetime
                    	  , IF(CONCAT(SUBSTR(mst_reserved_dt,1,4), '-', SUBSTR(mst_reserved_dt,5,2), '-', SUBSTR(mst_reserved_dt,7,2), ' ', SUBSTR(mst_reserved_dt,9,2), ':', SUBSTR(mst_reserved_dt,11,2), ':', SUBSTR(mst_reserved_dt,13,2)) <= NOW(), CONCAT(SUBSTR(mst_reserved_dt,1,4), '-', SUBSTR(mst_reserved_dt,5,2), '-', SUBSTR(mst_reserved_dt,7,2), ' ', SUBSTR(mst_reserved_dt,9,2), ':', SUBSTR(mst_reserved_dt,11,2), ':', SUBSTR(mst_reserved_dt,13,2)), NULL)
                    	  )
                    ) AS max_date
                FROM
                    cb_wt_msg_sent
                WHERE
                        mst_qty >= 10
                GROUP BY
                    mst_mem_id
            ) g ON a.mem_id = g.mst_mem_id
            where ".$where."
            order by c.parent_id, a.mem_level desc, a.mem_username
            ";


        $list = $this->db->query($sql, $param)->result();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

        $tmp_id = $this->input->post('tmp_id');
        $ids = explode(',', $tmp_id);

        // 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:U1');

        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(20);

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "소속");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "가입일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "계정");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "사업자번호");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "잔액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "담당자이름");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "담당자연락처");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "담당자이메일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "휴면상태");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "바우처잔액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "최종발송일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "알림톡");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "친구톡");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, "친구톡(이미지)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, 1, "단문문자(SMS)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, 1, "장문문자(LMS)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, 1, "RCS(SMS)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, 1, "RCS(LMS)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, 1, "RCS(MMS)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, 1, "RCS(TEM)");

        $cnt = 1;
        $row = 2;

        foreach($list as $r) {
            $biz_text = "";
            if(!empty($r->mem_biz_reg_no)){
                if(strlen($r->mem_biz_reg_no)==10){
                    $biz_text = substr($r->mem_biz_reg_no, 0, 3)."-".substr($r->mem_biz_reg_no, 3, 2)."-".substr($r->mem_biz_reg_no, 5, 5);
                }
            }
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->mrg_recommend_mem_username);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->mem_register_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->mem_userid);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->mem_username);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $biz_text);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $this->Biz_model->getTotalDeposit($r->mem_userid));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->mem_nickname);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->mem_emp_phone);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $r->mem_email);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row,(!empty($r->mdd_dormant_flag) ? $r->mdd_dormant_flag == 1 ? "휴면" : "정상" : "정상")); //2021.12.30 조지영
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $this->Biz_model->getVoucherDeposit($r->mem_userid));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $r->max_date);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $r->mad_price_at);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $r->mad_price_ft);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $r->mad_price_ft_img);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $r->mad_price_smt_sms);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $r->mad_price_smt);
            if ($row->mem_rcs_yn == "Y"){
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $r->mad_price_rcs_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $r->mad_price_rcs);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $r->mad_price_rcs_mms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $r->mad_price_rcs_tem);
            }

            $row++;
            $cnt++;
        }

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="result_list.xls"');
        header('Cache-Control: max-age=0');



        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');

    }
    public function getMemberPriceJSON() {
        $this->load->model("Biz_free_model");
        $mem_id = $this->input->post("parent_mem_id");
        $paremt_amt = $this->Biz_free_model->getMemberPriceJSON($mem_id);

        header('Content-Type: application/json');
        echo $paremt_amt;
    }

	//파트너 관리 > 수정
    public function edit()
    {
        $this->load->model("Biz_free_model");
        $mode = $this->input->post("actions");
        if($mode=="partner_save") { $this->partner_save(false); return; }

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";

		$skin = "write";
		$add = $this->input->get("add");
		if($add != "") $skin .= $add;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        //회원정보
		$view['rs'] = $this->Biz_model->get_member($view['param']['key'], true);
        //log_message("ERROR", "LSH 000001 WEB SKT : ".$view['rs']->mad_price_grs_biz);

		//주문설정 정보 2021-03-25
		$sql = "select * from cb_orders_setting where mem_id = '". $view['rs']->mem_id ."' ";
		//echo $_SERVER['REQUEST_URI'] ." > sql<br>". $sql ."<br>";
		$view['os'] = $this->db->query($sql)->row();

        $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));
        /* 관리자가 아닌경우 직속 하위의 파트너만 수정가능 */
        if($this->member->item("mem_level")<100 && !in_array($view['rs']->mem_id, $child)) { show_404();	exit; }
        if(!$view['rs'] || count($view['rs']) < 1) { show_404();	exit; }
        if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }

        //ib플래그 + 그룹추가
        $sql = "select b.mtg_useyn as mtg_useyn1, c.mtg_useyn as mtg_useyn2 from cb_member a left join cb_member_template_group b ON a.mem_id = b.mtg_mem_id and b.mtg_profile = 'A' LEFT JOIN cb_member_template_group c ON a.mem_id = c.mtg_mem_id and c.mtg_profile = 'B'  where a.mem_id = '".$view['rs']->mem_id."' ";
        $view['member_temp'] = $this->db->query($sql)->row();

        //바우처 2022-03-16 윤재박
        $sql = "select count(*) as cnt, a.* from cb_wt_voucher_addon a where a.vad_mem_id = '".$view['rs']->mem_id."' ";
        $view['member_voucher'] = $this->db->query($sql)->row();

        $view['member'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);
        $view['offer'] = $this->Biz_model->get_child_partner_info($this->member->item('mem_id'), "a.mem_level>=10 and a.mem_id >= 3");	//$this->db->query("select mem_id, mem_username from cb_member where mem_level>=10 order by mem_level, mem_username")->result();
        $view['parent_amt'] = $this->Biz_free_model->getMemberPrice($view['rs']->mrg_recommend_mem_id);

        if( $this->member->item('mem_id') == '962'){ //(주)지니 TG 계정 하위에서 등록한 영업자만 나오도록.
            $where = " AND a.ds_dbo_id = '6'";
        }
        $sql = "select * from dhn_branch_office where dbo_part='BO' or (dbo_part='DE' and dbo_genie_userid<>'')";
        $view['branch'] = $this->db->query($sql)->result();
        $sql = "select a.ds_id, a.ds_dbo_id, a.ds_name,
                    CASE a.ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장'
                        WHEN 'MA' THEN '과장' WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END ds_position, b.dbo_name
                from dhn_salesperson a left join dhn_branch_office b on a.ds_dbo_id=b.dbo_id
                where ds_task_state<>'L'".$where." order by ds_name";
        $view['salesperson'] = $this->db->query($sql)->result();

        $sql = "
            SELECT
                *
            FROM
                cb_member_dormant_dhn
            WHERE mem_id = '".$view['rs']->mem_id."'
        ";
        $view['dormancy'] = $this->db->query($sql)->row();


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
            'path' => 'biz/partner',
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

    //파트너 관리 > 신규 등록
	public function write()
    {
        $mode = $this->input->post("actions");
        if($mode=="partner_save") { $this->partner_save(true); return; }

		$skin = "write";
		$add = $this->input->get("add");
		if($add != "") $skin .= $add;

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['member'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);
        //$view['offer'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));
		$view['offer'] = $this->Biz_model->get_child_partner_info($this->member->item('mem_id'), "a.mem_level>=10 and a.mem_id >= 3");
		//$this->db->query("select mem_id, mem_username from cb_member where mem_level>=10 order by mem_level, mem_username")->result();
        //$view['offer'] = $this->Biz_model->get_offer();

        if( $this->member->item('mem_id') == '962'){ //(주)지니 TG 계정 하위에서 등록한 영업자만 나오도록.
            $where = " AND a.ds_dbo_id = '6'";
        }
		$sql = "select * from dhn_branch_office where dbo_part='BO' or (dbo_part='DE' and dbo_genie_userid<>'')";
		$view['branch'] = $this->db->query($sql)->result();
		$sql = "select a.ds_id, a.ds_dbo_id, a.ds_name,
                    CASE a.ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장'
                        WHEN 'MA' THEN '과장' WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END ds_position, b.dbo_name
                from dhn_salesperson a left join dhn_branch_office b on a.ds_dbo_id=b.dbo_id where ds_task_state<>'L'".$where." order by ds_name";
		$view['salesperson'] = $this->db->query($sql)->result();

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
            'path' => 'biz/partner',
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

    //파트너 관리 > 저장
	function partner_save($new=false)
    {
        $id = $this->input->post("userid");
        $pwd = $this->input->post("password");
        $username = $this->input->post("company_name");
        $nickname = $this->input->post("username");
        $bank_user = $this->input->post("bank_user");
        $phn = $this->input->post("phone");
        $emp_phn = $this->input->post("emp_phn");
        $email = $this->input->post("email");
        $phn_free = $this->input->post("phn_free");
        $price_at = $this->input->post("price_at");
        $price_ft = $this->input->post("price_ft");
        $price_ft_img = $this->input->post("price_ft_img");
        $price_ft_w_img = $this->input->post("price_ft_w_img");
        $price_grs = $this->input->post("price_grs");
        $price_grs_sms = $this->input->post("price_grs_sms");
        $price_nas = $this->input->post("price_nas");
        $price_nas_sms = $this->input->post("price_nas_sms");
        $price_015 = $this->input->post("price_015");
        $price_phn = $this->input->post("price_phn");
        $price_dooit = $this->input->post("price_dooit");
        $price_sms = $this->input->post("price_sms");
        $price_lms = $this->input->post("price_lms");
        $price_mms = $this->input->post("price_mms");
        $price_grs_mms = $this->input->post("price_grs_mms");
        $price_nas_mms = $this->input->post("price_nas_mms");
        $price_smt = $this->input->post("price_smt");
        $price_rcs_tem = $this->input->post("price_rcs_tem");
        $price_rcs_sms = $this->input->post("price_rcs_sms");
        $price_rcs = $this->input->post("price_rcs");
        $price_rcs_mms = $this->input->post("price_rcs_mms");
        $rate1 = $this->input->post("rate1");
        $rate2 = $this->input->post("rate2");
        $rate3 = $this->input->post("rate3");
        $rate4 = $this->input->post("rate4");
        $rate5 = $this->input->post("rate5");
        $rate6 = $this->input->post("rate6");
        $rate7 = $this->input->post("rate7");
        $rate8 = $this->input->post("rate8");
        $rate9 = $this->input->post("rate9");
        $rate10 = $this->input->post("rate10");
        $day_limit = intval(preg_replace("/[^0-9\.]*/s", "", $this->input->post('day_limit')));
        $mem_pf_manager = $this->input->post("mem_pf_manager");

        //20190107 이수환 추가(1line)
        $linkbtn_name = $this->input->post('linkbtn_name');
        //2019.02.18 Full Care비용 추가

        //2019-10-09 이수환 추가
        $mem_pf_cnt = $this->input->post("mem_pf_cnt");
        $mem_full_care_type = $this->input->post("mem_full_care_type");
        $mem_sales_name = $this->input->post("mem_sales_name");

        //2021-08-04 영업지점추가 김대경
        $mem_sales_point = $this->input->post("mem_sales_point");

        $mem_sale_point_id = $this->input->post("mem_sale_point_id");  // 지점 ID
        $mem_sales_mng_id = $this->input->post("mem_sales_mng_id");     // 영업자 담당자 ID

        $mem_management_point_id = $this->input->post("mem_management_point_id");  // 지점 ID
        $mem_management_mng_id = $this->input->post("mem_management_mng_id");     // 관리 담당자 ID

        $price_full_care = intval(preg_replace("/[^0-9\.]*/s", "", $this->input->post('price_full_care')));
        $price_grs_biz = $this->input->post("price_grs_biz") ? $this->input->post("price_grs_biz") : 0.00;
        $price_smt_sms = $this->input->post("price_smt_sms");
        $price_smt_mms = $this->input->post("price_smt_mms");
        $old_photo = $this->input->post("old_photo");
        $mem_level = $this->input->post("mem_level");
        $parent_id = $this->input->post("recommend_mem_id");
        $phn_agent = $this->input->post("phn_agent");
        $sms_agent = $this->input->post("sms_agent");
        if (!empty($this->input->post('incentive'))){
            $incentive = $this->input->post('incentive');

        $fiexd_url1 = $this->input->post("fiexd_url1");
        $fiexd_url2 = $this->input->post("fiexd_url2");}
        $useyn = $this->input->post("useyn");

        //- 2018.03.06 2차발신 서비스 지정
        $mem_2nd_send = $this->input->post('mem_2nd_send');
        $mem_payment_desc = $this->input->post('mem_payment_desc');
        $mem_payment_bank = $this->input->post('mem_payment_bank');
        $mem_payment_card = $this->input->post('mem_payment_card');
        $mem_payment_realtime = $this->input->post('mem_payment_realtime');
        $mem_payment_vbank = $this->input->post('mem_payment_vbank');
        $mem_payment_phone = $this->input->post('mem_payment_phone');
        $mem_pay_type = $this->input->post('mem_pay_type');
        $mem_min_charge_amt = $this->input->post('mem_min_charge_amt');
        $mem_fixed_from_date = $this->input->post('mem_fixed_from_date');
        $mem_fixed_to_date = $this->input->post('mem_fixed_to_date');

		 //결재방법 변경 2020-12-18
        $mem_payment_type = $this->input->post('mem_payment_type'); //결재방법
		if($mem_payment_type == "bank"){
			$mem_payment_bank = "Y"; //계좌이체(레드뱅킹) 결재 사용여부
			$mem_payment_card = "N"; //신용카드 결재 사용여부
			$mem_payment_realtime = "N"; //실시간계좌이체 결재 사용여부
			$mem_payment_vbank = "N"; //가상계좌(이니시스) 결재 사용여부
			$mem_payment_phone = "N"; //핸드폰 결재 사용여부
		}elseif($mem_payment_type == "vbank"){
			$mem_payment_bank = "N"; //계좌이체(레드뱅킹) 결재 사용여부
			$mem_payment_card = "N"; //신용카드 결재 사용여부
			$mem_payment_realtime = "N"; //실시간계좌이체 결재 사용여부
			$mem_payment_vbank = "Y"; //가상계좌(이니시스) 결재 사용여부
			$mem_payment_phone = "N"; //핸드폰 결재 사용여부
		}

        $korpay_flag = $this->input->post("chk_korpay_flag");
        $korpay_id = $this->input->post("korpay_id");
        $korpay_key = $this->input->post("korpay_key");

        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > id : ". $id .", username : ". $username .", nickname : ". $nickname .", phn : ". $phn .", email : ". $email);
		if(!$id || !$username || !$nickname || !$phn || !$email) { echo '<script type="text/javascript">alert("입력항목 누락!");history.back();</script>'; exit; }

        $key = 0;

        /* 자신이 관리하는 파트너인지 확인 : 최고관리자 제외 */
        $sql = "select a.mem_id, b.mrg_recommend_mem_id, ifnull(c.mad_mem_id, 0) mad_mem_id
			from cb_member a
				inner join cb_member_register b on a.mem_id=b.mem_id
				left join cb_wt_member_addon c on a.mem_id=c.mad_mem_id
			where a.mem_userid=?";
        //$mem = $this->Biz_model->get_member($id, true);	//$this->db->query($sql, array($id))->row();
        $mem = $this->Biz_model->get_member_ori($id, true);

        if(!$new && (!$mem || $mem->mem_userid!=$id)) { show_404();	exit; }

        if($new) {
            /* new 인데 mem에 존재하면 */
            if($mem && $mem->mem_id > 1) { show_404(); exit; }
            $parent = $this->member->item('mem_id');
        } else {
            if($mem && $mem->mem_id > 0) { //최고관리자검사
                $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));

                /* 관리자가 아닌경우 직속 하위의 파트너만 수정가능 */
                if($this->member->item("mem_level")<100 && !in_array($mem->mem_id, $child)) { show_404();	exit; }
                if($this->member->item('mem_id')!=$mem->mem_id && $this->member->is_admin()!="super" && !in_array($mem->mem_id, $child)) { show_404(); exit; }

                //if($this->member->is_admin()!="super" && $mem->mrg_recommend_mem_id!=$this->member->item('mem_id')) { show_404(); exit; }
                $key = $mem->mem_id;
                $parent = $mem->mrg_recommend_mem_id;
            } else {	show_404();	exit;	}
        }

        //ib플래그 + 그룹추가
        $sql = "select count(*) as cnt from cb_member_template_group where mtg_mem_id = '".$mem->mem_id."' and mtg_profile = 'A'";
        $temp_cnt_a = $this->db->query($sql)->row()->cnt;

        $sql = "select count(*) as cnt from cb_member_template_group where mtg_mem_id = '".$mem->mem_id."' and mtg_profile = 'B'";
        $temp_cnt_b = $this->db->query($sql)->row()->cnt;

        if(!empty($this->input->post("chk_temp_a"))){

            if($temp_cnt_a==0){
                $sql = "INSERT INTO cb_member_template_group
                        (mtg_mem_id, mtg_profile, mtg_useyn)
                        VALUES('".$mem->mem_id."', 'A', 'Y')";
                $this->db->query($sql);
            }
        }else{
            if($temp_cnt_a>0){
                $sql = "DELETE FROM cb_member_template_group WHERE mtg_mem_id = ".$mem->mem_id." AND mtg_profile ='A'";
                $this->db->query($sql);
            }
        }

        if(!empty($this->input->post("chk_temp_b"))){

            if($temp_cnt_b==0){
                $sql = "INSERT INTO cb_member_template_group
                        (mtg_mem_id, mtg_profile, mtg_useyn)
                        VALUES('".$mem->mem_id."', 'B', 'Y')";
                $this->db->query($sql);
            }
        }else{
            if($temp_cnt_b>0){
                $sql = "DELETE FROM cb_member_template_group WHERE mtg_mem_id = ".$mem->mem_id." AND mtg_profile ='B'";
                $this->db->query($sql);
            }
        }

        $file_error = '';
        $uploadfiledata = '';
        $this->load->library('upload');
        if (isset($_FILES) && isset($_FILES['biz_license']) && isset($_FILES['biz_license']['name']) && isset($_FILES['biz_license']['name'])) {
            $filecount = count($_FILES['biz_license']['name']);
            $upload_path = config_item('uploads_dir') . '/member_photo/';
            if (is_dir($upload_path) === false) {
                mkdir($upload_path, 0707);
                $file = $upload_path . 'index.php';
                $f = @fopen($file, 'w');
                @fwrite($f, '');
                @fclose($f);
                @chmod($file, 0644);
            }
            $upload_path .= cdate('Y') . '/';
            if (is_dir($upload_path) === false) {
                mkdir($upload_path, 0707);
                $file = $upload_path . 'index.php';
                $f = @fopen($file, 'w');
                @fwrite($f, '');
                @fclose($f);
                @chmod($file, 0644);
            }
            $upload_path .= cdate('m') . '/';
            if (is_dir($upload_path) === false) {
                mkdir($upload_path, 0707);
                $file = $upload_path . 'index.php';
                $f = @fopen($file, 'w');
                @fwrite($f, '');
                @fclose($f);
                @chmod($file, 0644);
            }

            $uploadconfig = '';
            $uploadconfig['upload_path'] = $upload_path;
            $uploadconfig['allowed_types'] = element('upload_file_extension', $board) ? element('upload_file_extension', $board) : '*';
            $uploadconfig['max_size'] = element('upload_file_max_size', $board) * 1024;
            $uploadconfig['encrypt_name'] = true;

            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $_FILES['biz_license']['name'];
            $_FILES['userfile']['type'] = $_FILES['biz_license']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['biz_license']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['biz_license']['error'];
            $_FILES['userfile']['size'] = $_FILES['biz_license']['size'];
            if ($this->upload->do_upload()) {
                $filedata = $this->upload->data();

                $uploadfiledata['pfi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
                $uploadfiledata['pfi_originname'] = element('orig_name', $filedata);
                $uploadfiledata['pfi_filesize'] = intval(element('file_size', $filedata) * 1024);
                $uploadfiledata['pfi_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
                $uploadfiledata['pfi_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
                $uploadfiledata['pfi_type'] = str_replace('.', '', element('file_ext', $filedata));
                $uploadfiledata['is_image'] = element('is_image', $filedata) ? 1 : 0;

                $this->mem_photo = $uploadfiledata['pfi_filename'];
                if($old_photo && file_exists(config_item('uploads_dir').'/member_photo/'.$old_photo)) {
                    unlink(config_item('uploads_dir').'/member_photo/'.$old_photo);
                }
            } else {
                $file_error = $this->upload->display_errors();
            }
        }

		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > biz_license : ". $_FILES['biz_license']['name']);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > contract_file : ". $_FILES['contract_file']['name']);

		//계약서 파일
		if (isset($_FILES) && isset($_FILES['contract_file']) && isset($_FILES['contract_file']['name']) && isset($_FILES['contract_file']['name'])) {
            $filecount = count($_FILES['contract_file']['name']);
            $upload_path = config_item('uploads_dir') . '/member_contract/';
            if (is_dir($upload_path) === false) {
                mkdir($upload_path, 0707);
                $file = $upload_path . 'index.php';
                $f = @fopen($file, 'w');
                @fwrite($f, '');
                @fclose($f);
                @chmod($file, 0644);
            }
            $upload_path .= cdate('Y') . '/';
            if (is_dir($upload_path) === false) {
                mkdir($upload_path, 0707);
                $file = $upload_path . 'index.php';
                $f = @fopen($file, 'w');
                @fwrite($f, '');
                @fclose($f);
                @chmod($file, 0644);
            }
            $upload_path .= cdate('m') . '/';
            if (is_dir($upload_path) === false) {
                mkdir($upload_path, 0707);
                $file = $upload_path . 'index.php';
                $f = @fopen($file, 'w');
                @fwrite($f, '');
                @fclose($f);
                @chmod($file, 0644);
            }

            $uploadconfig = '';
            $uploadconfig['upload_path'] = $upload_path;
            $uploadconfig['allowed_types'] = element('upload_file_extension', $board) ? element('upload_file_extension', $board) : '*';
            $uploadconfig['max_size'] = element('upload_file_max_size', $board) * 1024;
            $uploadconfig['encrypt_name'] = true;

            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $_FILES['contract_file']['name'];
            $_FILES['userfile']['type'] = $_FILES['contract_file']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['contract_file']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['contract_file']['error'];
            $_FILES['userfile']['size'] = $_FILES['contract_file']['size'];

            if ($this->upload->do_upload()) {
                $filedata = $this->upload->data();

                $uploadpdffiledata['pfi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
                $uploadpdffiledata['pfi_originname'] = element('orig_name', $filedata);
                $uploadpdffiledata['pfi_filesize'] = intval(element('file_size', $filedata) * 1024);
                $uploadpdffiledata['pfi_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
                $uploadpdffiledata['pfi_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
                $uploadpdffiledata['pfi_type'] = str_replace('.', '', element('file_ext', $filedata));
                $uploadpdffiledata['is_image'] = element('is_image', $filedata) ? 1 : 0;

                $this->mem_contract_filename = $_FILES['contract_file']['name'];
                $this->mem_contract_filepath = $uploadpdffiledata['pfi_filename'];
                $id_old_contract_file = $this->input->post("id_old_contract_file");
				if($id_old_contract_file && file_exists(config_item('uploads_dir').'/member_contract/'.$id_old_contract_file)) {
                    unlink(config_item('uploads_dir').'/member_contract/'.$id_old_contract_file);
                }
            } else {
                $pdf_file_error = $this->upload->display_errors();
                log_Message("ERROR", "pdf file_error : ".$pdf_file_error);
            }
        }

        $this->mem_username = $username;
        $this->mem_nickname = $nickname;
        $this->mem_bank_user = $bank_user;
        $this->mem_phone = $phn;
        $this->mem_emp_phone = $emp_phn;
        $this->mem_email = $email;
        $this->mem_level = ($mem_level) ? (int)$mem_level : 1;
        $this->mem_day_limit = $day_limit;
        //20190107 이수환 추가(1line)
        $this->mem_linkbtn_name = $linkbtn_name;
        //if($_SERVER['REMOTE_ADDR']==$this->config->config['developer_ip'] || $this->config->config['use_multi_agent'])
        $this->mem_2nd_send = $mem_2nd_send;
        $this->mem_payment_desc = $mem_payment_desc;
        $this->mem_payment_bank = $mem_payment_bank;
        $this->mem_payment_card = $mem_payment_card;
        $this->mem_payment_realtime = $mem_payment_realtime;
        $this->mem_payment_vbank = $mem_payment_vbank;
        $this->mem_payment_phone = $mem_payment_phone;
        $this->mem_pay_type = (empty($mem_pay_type) ? 'B' : $mem_pay_type);
        $this->mem_min_charge_amt = $mem_min_charge_amt;
        if($mem_pay_type=='T') { //충전방식 (B.선불, A.후불, T.정액제)
            $this->mem_fixed_from_date = $mem_fixed_from_date; //정액제 시작일
            $this->mem_fixed_to_date = $mem_fixed_to_date; //정액제 종료일
        }else{
			$this->mem_fixed_from_date = null; //정액제 시작일
            $this->mem_fixed_to_date = null; //정액제 종료일
		}
        $this->mem_pf_manager = $mem_pf_manager;

        //2019-10-09 이수환 추가
        $this->mem_pf_cnt = $mem_pf_cnt;
        $this->mem_sales_name = $mem_sales_name;

        //2021-08-04 영업지점추가 김대경
        $this->mem_sales_point = $mem_sales_point;

        $this->mem_sale_point_id = $mem_sale_point_id;  // 지점 ID
        $this->mem_sales_mng_id = $mem_sales_mng_id;  // 영업 담당자 ID

        $this->mem_management_point_id = $mem_management_point_id;
        $this->mem_management_mng_id = $mem_management_mng_id;

        $this->mem_full_care_yn = $this->input->post("mem_full_care_yn"); //풀케어 사용유무
        $this->mem_full_care_type = $this->input->post("mem_full_care_type"); //플케어 Type (a.풀케어 A, b.풀케어 B, c.풀케어 C)
		$this->mem_full_care_max = $this->input->post("mem_full_care_max"); //플케어 이미지 제작 최대 횟수
        $this->mem_full_care_from_date = $this->input->post("mem_full_care_from_date"); //풀케어 시작일
        $this->mem_full_care_to_date = $this->input->post("mem_full_care_to_date"); //풀케어 종료일
		if($this->mem_full_care_yn == 'on' or $this->mem_full_care_yn == 'Y') {
            $this->mem_full_care_yn = 'Y';
        } else { //풀케어 서비스 : 설정안함
            $this->mem_full_care_yn = 'N';
            $this->mem_full_care_type = '';
            $this->mem_full_care_max = '0';
            $this->mem_full_care_from_date = '';
            $this->mem_full_care_to_date = '';
			//$price_full_care = 0; //풀케어 서비스 요금
        }

        $this->mem_cont_from_date = $this->input->post("mem_cont_from_date"); //계약시작일
        $this->mem_cont_to_date = $this->input->post("mem_cont_to_date"); //계약종료일
        $this->mem_cont_cancel_yn = $this->input->post("cont_cancel_yn"); //계약해지 상태
        $this->mem_cont_cancel_date = $this->input->post("mem_cont_cancel_date"); //계약해지시 정산처리일자
		if($this->mem_cont_cancel_yn == 'on' or $this->mem_cont_cancel_yn == 'Y') {
            $this->mem_cont_cancel_yn = 'Y';
        } else {
            $this->mem_cont_cancel_yn = 'N';
            $this->mem_cont_cancel_date = '';
        }

		$this->mem_biz_reg_name = $this->input->post('biz_reg_name'); //사업자등록증 상호
		$this->mem_biz_reg_no = $this->input->post('biz_reg_no1') . $this->input->post('biz_reg_no2') . $this->input->post('biz_reg_no3'); //사업자 등록번호
		$this->mem_biz_reg_rep_name = $this->input->post('biz_reg_rep_name'); //사업자등록증 대표자 이름
		$this->mem_biz_reg_zip_code = $this->input->post('biz_reg_zip_code'); //사업자등록증 우편번호
		$this->mem_biz_reg_add1 = $this->input->post('biz_reg_add1'); //사업자등록증 주소
		$this->mem_biz_reg_add2 = $this->input->post('biz_reg_add2'); //사업자등록증 상세주소
		$this->mem_biz_reg_add3 = $this->input->post('biz_reg_add3'); //사업자등록증 참고항목
		$this->mem_biz_reg_div = $this->input->post('biz_reg_div'); //사업자 구분 (p.개인사업자, c.법인사업자)
		$this->mem_biz_reg_biz = $this->input->post('biz_reg_biz'); //사업자등록증 업태
		$this->mem_biz_reg_sector = $this->input->post('biz_reg_sector'); //사업자등록증 종목
		$this->mem_pos_yn = $this->input->post("mem_pos_yn"); //포스연동 사용여부
		$this->mem_pos_id = $this->input->post("mem_pos_id"); //포스연동 ID
		$this->mem_ip = $this->input->post("mem_ip"); //포스연동 IP
		$this->mem_pos_port = $this->input->post("mem_pos_port"); //포스연동 포트
		$this->mem_contract_type = $this->input->post("mem_contract_type"); //계약형태 (S.스텐다드, P.프리미엄)
		if($this->mem_contract_type == "P"){ //계약형태 (S.스텐다드, P.프리미엄)
			$this->mem_mall_id = $this->input->post("mem_mall_id"); //플친몰 ID
			$this->mem_mall_key = $this->input->post("mem_mall_key"); //플친몰 사이트연결 Key
		}else{
			$this->mem_mall_id = ""; //플친몰 ID
			$this->mem_mall_key = ""; //플친몰 사이트연결 Key
		}
		$this->mem_purigo_yn = $this->input->post("mem_purigo_yn"); //Purigo 회원여부
		$this->mem_monthly_fee = $this->funn->numbers_only($this->input->post("mem_monthly_fee")); //월사용료 (숫자만 입력)
		$this->mem_talk_time_clear_yn = $this->input->post("mem_talk_time_clear_yn"); //알림톡 발송시간 해제 2020-12-30
        $this->mem_sms_time_clear_yn = $this->input->post("mem_sms_time_clear_yn"); //알림톡 발송시간 해제 2020-12-30
		$this->mem_bigpos_yn = $this->input->post("mem_bigpos_yn"); //빅포스연동 여부 2021-02-01
		$this->mem_stmall_yn = ($this->input->post("mem_stmall_yn") == "") ? "N" : $this->input->post("mem_stmall_yn"); //스마트전단 주문하기 사용여부 2021-02-25
		$this->mem_stmall_alim_phn = $this->input->post("mem_stmall_alim_phn"); //주문 알림 수신 번호(구분자 콤마) 2021-02-25
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > this->mem_stmall_alim_phn : ". $this->mem_stmall_alim_phn);
        $this->mem_voucher_yn = $this->input->post("mem_voucher_yn");

        $this->mem_rcs_yn = $this->input->post("mem_rcs_yn");

        $this->mem_send_smart_yn = $this->input->post("mem_send_smart_yn");

        $this->mem_mpop_yn = $this->input->post("mem_mpop_yn");
        $this->mem_mpop_cont_start = $this->input->post("mem_mpop_cont_start");
        $this->mem_mpop_cont_end = $this->input->post("mem_mpop_cont_end");

        $this->mem_eve_yn = $this->input->post("mem_eve_yn");
        if($this->mem_eve_yn=="Y"){
            $this->mem_eve_id = $this->input->post("mem_eve_id");
            $this->mem_eve_key = $this->input->post("mem_eve_key");
        }

        $this->mem_fixed_url1_flag = $fiexd_url1;
        $this->mem_fixed_url2_flag = $fiexd_url2;

		//주문 배달 가능금액 설정 2021-03-25
		if($this->mem_stmall_yn == "Y"){ //스마트전단 주문하기 사용
			// $sql = "select count(*) as cnt from cb_orders_setting where mem_id = '". $mem->mem_id ."' ";
			// $os_cnt = $this->db->query($sql)->row()->cnt;
			$setting = array();
			$setting["min_amt"] = $this->input->post("min_amt"); //주문 최소금액
			$setting["delivery_amt"] = $this->input->post("delivery_amt"); //배달비
			$setting["free_delivery_amt"] = $this->input->post("free_delivery_amt"); //무료배달 최소금액
			// if($os_cnt == 0){ //등록
			// 	$setting["mem_id"] = $mem->mem_id; //회원번호
			// 	$rtn = $this->db->insert("cb_orders_setting", $setting); //데이타 등록
			// }else{ //수정
			// 	$where = array();
			// 	$where["mem_id"] = $mem->mem_id; //회원번호
			// 	$rtn = $this->db->update("cb_orders_setting", $setting, $where); //데이타 수정
			// }
            if ($this->input->post("chk_division_a") == 'Y' && $this->input->post("chk_division_b") == 'Y'){
                $this->mem_shop_division = 3;
            } else if ($this->input->post("chk_division_a") == 'Y'){
                $this->mem_shop_division = 1;
            } else if ($this->input->post("chk_division_b") == 'Y'){
                $this->mem_shop_division = 2;
            }
            $this->mem_shop_pay_offline_flag = $this->input->post("chk_payment_a") == "Y" ? "Y" : "N";
            $this->mem_is_spot = $this->input->post("chk_payment_c") == "Y" ? "Y" : "N";
            $this->mem_shop_pay_inicis_flag = $this->input->post("chk_payment_b") == "Y" ? "Y" : "N";
            $this->mem_shop_pay_inicis_id = $this->input->post("inicis_id");
            $this->mem_shop_pay_inicis_key = $this->input->post("inicis_key");
            $this->mem_shop_pay_inicis_login_id = $this->input->post("inicis_login_id");
            $this->mem_shop_pay_inicis_fee = $this->input->post("inicis_fee");
            $this->mem_shop_pay_inicis_account = $this->input->post("inicis_account");
            $this->mem_shop_pay_inicis_bank = $this->input->post("inicis_bank");
            $this->mem_shop_pay_inicis_per = $this->input->post("chk_per_pay") == "Y" ? "Y" : "N";
            // log_message("error", "song : ". $this->mem_shop_pay_inicis_per);
		}

        $this->mem_korpay_flag = $korpay_flag;
        $this->mem_shop_pay_korpay_id = $korpay_id;
        $this->mem_shop_pay_korpay_key = $korpay_key;

        $db = $this->load->database("218g", TRUE);

		//ALTER TABLE cb_member ADD mem_stmall_yn CHAR(1) NULL DEFAULT 'N' COMMENT '스마트전단 주문하기 사용여부' COLLATE 'utf8_general_ci';
		//select * from cb_member limit 10;

        if($pwd) {
            if ( ! function_exists('password_hash')) { $this->load->helper('password'); }
            $this->mem_password = password_hash($pwd, PASSWORD_BCRYPT);
            $this->mem_shop_code = $this->funn->encrypt($pwd); //2020-09-15
        }
        if($new) { $this->mem_userid = $id; }
        if($new) {
            $this->mem_email_cert = 1;
            $this->mem_register_datetime = cdate('Y-m-d H:i:s');
            $this->mem_register_ip = $this->input->ip_address();
            $this->mem_deposit = 0.0;
            $this->mem_point = $this->cbconfig->item('point_register');		/* 회원가입시 지급 포인트 */

            $this->db->insert("member", $this);
            $key = $this->db->insert_id();

            $sql = "select count(1) as cnt from cb_shop_member_data where smd_mem_id = '".$key."' and smd_from = '133g'";
            $shopcnt = $db->query($sql)->row()->cnt;

            if($shopcnt == 0){
                $mem_data = array();
                $mem_data["smd_mem_id"] = $key;
                $mem_data["smd_mem_userid"] = $this->mem_userid;
                $mem_data["smd_mem_username"] = $this->mem_username;
                $mem_data["smd_mem_nickname"] = $this->mem_nickname;
                $mem_data["smd_mem_stmall_yn"] = $this->mem_stmall_yn;
                $mem_data["smd_mem_stmall_alim_phn"] = $this->mem_stmall_alim_phn;
                $mem_data["smd_mem_shop_pay_offline_flag"] = $this->mem_shop_pay_offline_flag;
                $mem_data["smd_mem_shop_pay_inicis_flag"] = $this->mem_shop_pay_inicis_flag;
                $mem_data["smd_mem_shop_pay_inicis_id"] = $this->mem_shop_pay_inicis_id;
                $mem_data["smd_mem_shop_pay_inicis_key"] = $this->mem_shop_pay_inicis_key;
                $mem_data["smd_mem_shop_pay_inicis_login_id"] = $this->mem_shop_pay_inicis_login_id;
                $mem_data["smd_mem_shop_pay_inicis_fee"] = $this->mem_shop_pay_inicis_fee;
                $mem_data["smd_mem_shop_pay_inicis_account"] = $this->mem_shop_pay_inicis_account;
                $mem_data["smd_mem_shop_pay_inicis_bank"] = $this->mem_shop_pay_inicis_bank;
                $mem_data["smd_mem_shop_pay_inicis_per"] = $this->mem_shop_pay_inicis_per;
                $mem_data["smd_mem_shop_division"] = $this->mem_shop_division;
                $mem_data["smd_from"] = "133g";
                $db->insert("cb_shop_member_data", $mem_data); //데이타 추가
            }

            /* 회원 등록정보 저장 */
            $register = array();
            $register['mem_id'] = $key;
            $register['mrg_ip'] = $this->input->ip_address();
            $register['mrg_datetime'] = cdate('Y-m-d H:i:s');
            $register['mrg_recommend_mem_id'] = ($parent_id) ? $parent_id : $parent;
            $register['mrg_useragent'] = $this->agent->agent_string();
            $register['mrg_referer'] = $this->session->userdata('site_referer');
            $this->db->insert("member_register", $register);

            //2022-07-19 윤재박 - 스마트전단 설정 저장 오류 수정
    		if($this->mem_stmall_yn == "Y"){ //스마트전단 주문하기 사용
    			$sql = "select count(*) as cnt from cb_orders_setting where mem_id = '". $key ."' ";
    			$os_cnt = $this->db->query($sql)->row()->cnt;

    			if($os_cnt == 0){ //등록
    				$setting["mem_id"] = $key; //회원번호
    				$rtn = $this->db->insert("cb_orders_setting", $setting); //데이타 등록
                    $setting["os_from"] = "133g";
                    $rtn2 = $db->insert("cb_orders_setting", $setting); //데이타 추가
    			}else{ //수정
    				$where = array();
    				$where["mem_id"] = $key; //회원번호
    				$rtn = $this->db->update("cb_orders_setting", $setting, $where); //데이타 수정

                    $sql = "select count(1) as cnt from cb_orders_setting where mem_id = '".$key."' and os_from = '133g'";
                    $setcnt = $db->query($sql)->row()->cnt;

                    if($setcnt==0){
                        $sql = "select * from cb_orders_setting where mem_id = '".$key."' limit 1 ";
                        $osd = $this->db->query($sql)->row();

                        $os_data = array();
                        $os_data["mem_id"] = $key;
                        $os_data["min_amt"] = $osd->min_amt;
                        $os_data["delivery_amt"] = $osd->delivery_amt;
                        $os_data["free_delivery_amt"] = $osd->free_delivery_amt;
                        $os_data["os_from"] = "133g";
                        $db->insert("cb_orders_setting", $os_data); //데이타 추가
                    }else{
                        $where["os_from"] = "133g";
                        $rtn2 = $db->update("cb_orders_setting", $setting, $where); //데이타 수정
                    }
    			}
    		}
        } else {
            if($this->member->item('mem_level')>=10) {
                $register = array();
                $register['mrg_recommend_mem_id'] = ($parent_id) ? $parent_id : $parent;
                $this->db->update("member_register", $register, array("mem_id"=>$key));
                /* 문자 발송 업체 지정, 사용여부 추가 : 2017.12.15 */
                if($phn_agent) { $this->mem_phn_agent = $phn_agent; }
                if($sms_agent) { $this->mem_sms_agent = $sms_agent; }
                if($useyn) { $this->mem_useyn = $useyn; }
            }
            $this->db->update("member", $this, array("mem_userid"=>$id));

            $sql = "select count(1) as cnt from cb_shop_member_data where smd_mem_userid = '".$id."' and smd_from = '133g'";
            $shopcnt = $db->query($sql)->row()->cnt;
            $sql = "select spf_friend from cb_wt_send_profile_dhn where spf_mem_id = '".$key."' limit 1";
            $spf_friend = $this->db->query($sql)->row()->spf_friend;
            if($shopcnt == 0){

                $mem_data = array();
                $mem_data["smd_mem_id"] = $key;
                $mem_data["smd_mem_userid"] = $id;
                $mem_data["smd_mem_username"] = $this->mem_username;
                $mem_data["smd_mem_nickname"] = $this->mem_nickname;
                $mem_data["smd_spf_friend"] = $spf_friend;
                $mem_data["smd_mem_stmall_yn"] = $this->mem_stmall_yn;
                $mem_data["smd_mem_stmall_alim_phn"] = $this->mem_stmall_alim_phn;
                $mem_data["smd_mem_shop_pay_offline_flag"] = $this->mem_shop_pay_offline_flag;
                $mem_data["smd_mem_shop_pay_inicis_flag"] = $this->mem_shop_pay_inicis_flag;
                $mem_data["smd_mem_shop_pay_inicis_id"] = $this->mem_shop_pay_inicis_id;
                $mem_data["smd_mem_shop_pay_inicis_key"] = $this->mem_shop_pay_inicis_key;
                $mem_data["smd_mem_shop_pay_inicis_login_id"] = $this->mem_shop_pay_inicis_login_id;
                $mem_data["smd_mem_shop_pay_inicis_fee"] = $this->mem_shop_pay_inicis_fee;
                $mem_data["smd_mem_shop_pay_inicis_account"] = $this->mem_shop_pay_inicis_account;
                $mem_data["smd_mem_shop_pay_inicis_bank"] = $this->mem_shop_pay_inicis_bank;
                $mem_data["smd_mem_shop_pay_inicis_per"] = $this->mem_shop_pay_inicis_per;
                $mem_data["smd_mem_shop_division"] = $this->mem_shop_division;
                $mem_data["smd_from"] = "133g";
                $db->insert("cb_shop_member_data", $mem_data); //데이타 추가
            }else{
                $mem_data = array();
                $mem_data["smd_mem_username"] = $this->mem_username;
                $mem_data["smd_mem_nickname"] = $this->mem_nickname;
                $mem_data["smd_spf_friend"] = $spf_friend;
                $mem_data["smd_mem_stmall_yn"] = $this->mem_stmall_yn;
                $mem_data["smd_mem_stmall_alim_phn"] = $this->mem_stmall_alim_phn;
                $mem_data["smd_mem_shop_pay_offline_flag"] = $this->mem_shop_pay_offline_flag;
                $mem_data["smd_mem_shop_pay_inicis_flag"] = $this->mem_shop_pay_inicis_flag;
                $mem_data["smd_mem_shop_pay_inicis_id"] = $this->mem_shop_pay_inicis_id;
                $mem_data["smd_mem_shop_pay_inicis_key"] = $this->mem_shop_pay_inicis_key;
                $mem_data["smd_mem_shop_pay_inicis_login_id"] = $this->mem_shop_pay_inicis_login_id;
                $mem_data["smd_mem_shop_pay_inicis_fee"] = $this->mem_shop_pay_inicis_fee;
                $mem_data["smd_mem_shop_pay_inicis_account"] = $this->mem_shop_pay_inicis_account;
                $mem_data["smd_mem_shop_pay_inicis_bank"] = $this->mem_shop_pay_inicis_bank;
                $mem_data["smd_mem_shop_pay_inicis_per"] = $this->mem_shop_pay_inicis_per;
                $mem_data["smd_mem_shop_division"] = $this->mem_shop_division;
                $where = array();
                $where["smd_mem_id"] = $key; //회원번호
                $where["smd_from"] = "133g";
                $db->update("cb_shop_member_data", $mem_data, $where);
            }

            //2022-07-19 윤재박 - 스마트전단 설정 저장 오류 수정
            if($this->mem_stmall_yn == "Y"){ //스마트전단 주문하기 사용
    			$sql = "select count(*) as cnt from cb_orders_setting where mem_id = '". $mem->mem_id ."' ";
    			$os_cnt = $this->db->query($sql)->row()->cnt;
    			if($os_cnt == 0){ //등록
    				$setting["mem_id"] = $mem->mem_id; //회원번호
    				$rtn = $this->db->insert("cb_orders_setting", $setting); //데이타 등록
                    $setting["os_from"] = "133g";
                    $rtn2 = $db->insert("cb_orders_setting", $setting); //데이타 추가
    			}else{ //수정
    				$where = array();
    				$where["mem_id"] = $mem->mem_id; //회원번호
    				$rtn = $this->db->update("cb_orders_setting", $setting, $where); //데이타 수정

                    $sql = "select count(1) as cnt from cb_orders_setting where mem_id = '".$mem->mem_id."' and os_from = '133g'";
                    $setcnt = $db->query($sql)->row()->cnt;

                    if($setcnt==0){
                        $sql = "select * from cb_orders_setting where mem_id = '".$mem->mem_id."' limit 1 ";
                        $osd = $this->db->query($sql)->row();

                        $os_data = array();
                        $os_data["mem_id"] = $mem->mem_id;
                        $os_data["min_amt"] = $osd->min_amt;
                        $os_data["delivery_amt"] = $osd->delivery_amt;
                        $os_data["free_delivery_amt"] = $osd->free_delivery_amt;
                        $os_data["os_from"] = "133g";
                        $db->insert("cb_orders_setting", $os_data); //데이타 추가
                    }else{
                        $where["os_from"] = "133g";
                        $rtn2 = $db->update("cb_orders_setting", $setting, $where); //데이타 수정
                    }
    			}
    		}
        }

        if($this->input->post("mem_voucher_yn")=="Y"){
            $price_v_at = $this->input->post('price_v_at');
            $price_v_ft = $this->input->post('price_v_ft');
            $price_v_ft_img = $this->input->post('price_v_ft_img');
            $price_v_smt_sms = $this->input->post('price_v_smt_sms');
            $price_v_smt = $this->input->post('price_v_smt');
            $price_v_smt_mms = $this->input->post('price_v_smt_mms');

            $price_v_rcs_tem = $this->input->post('price_v_rcs_tem');
            $price_v_rcs_sms = $this->input->post('price_v_rcs_sms');
            $price_v_rcs = $this->input->post('price_v_rcs');
            $price_v_rcs_mms = $this->input->post('price_v_rcs_mms');
            // $price_v_smt_ums = $this->input->post('price_v_smt_ums');
            // $price_v_imc = $this->input->post('price_v_imc');
            $sdate = $this->input->post("voucher_from_date"); //시작일
            $edate = $this->input->post("voucher_to_date"); //종료일
            $vaddon = array();
            $vaddon['vad_price_at'] = str_replace(",", "", $price_v_at);
            $vaddon['vad_price_ft'] = str_replace(",", "", $price_v_ft);
            $vaddon['vad_price_ft_img'] = str_replace(",", "", $price_v_ft_img);
            $vaddon['vad_price_smt_sms'] = str_replace(",", "", $price_v_smt_sms);
            $vaddon['vad_price_smt'] = str_replace(",", "", $price_v_smt);
            $vaddon['vad_price_smt_mms'] = str_replace(",", "", $price_v_smt_mms);
            if($this->input->post("mem_rcs_yn")=="Y"){
                $vaddon['vad_price_rcs_tem'] = str_replace(",", "", $price_v_rcs_tem);
                $vaddon['vad_price_rcs_sms'] = str_replace(",", "", $price_v_rcs_sms);
                $vaddon['vad_price_rcs'] = str_replace(",", "", $price_v_rcs);
                $vaddon['vad_price_rcs_mms'] = str_replace(",", "", $price_v_rcs_mms);
            }
            // $vaddon['vad_price_smt_ums'] = str_replace(",", "", $price_v_smt_ums);
            // $vaddon['vad_price_imc'] = str_replace(",", "", $price_v_imc);
            $vaddon['vad_sdate'] = $sdate;
            $vaddon['vad_edate'] = $edate;
            if($new || empty($mem->vad_mem_id)) {
                $vaddon['vad_mem_id'] = $key;
                $this->db->insert("wt_voucher_addon", $vaddon);
            } else {
                $this->db->update("wt_voucher_addon", $vaddon, array("vad_mem_id"=>$key));
            }
        }

        /* 회원 추가정보 저장 */
        $addon['mad_free_hp'] = $phn_free;
        $addon['mad_price_at'] = str_replace(",", "", $price_at);
        $addon['mad_price_ft'] = str_replace(",", "", $price_ft);
        $addon['mad_price_ft_img'] = str_replace(",", "", $price_ft_img);
        $addon['mad_price_ft_w_img'] = str_replace(",", "", $price_ft_w_img);
        $addon['mad_price_grs'] = str_replace(",", "", $price_grs);
        $addon['mad_price_grs_sms'] = str_replace(",", "", $price_grs_sms);
        $addon['mad_price_nas'] = str_replace(",", "", $price_nas);
        $addon['mad_price_nas_sms'] = str_replace(",", "", $price_nas_sms);
        $addon['mad_price_015'] = str_replace(",", "", $price_015);
        $addon['mad_price_phn'] = str_replace(",", "", $price_phn);
        $addon['mad_price_dooit'] = str_replace(",", "", $price_dooit);
        $addon['mad_price_sms'] = str_replace(",", "", $price_sms);
        $addon['mad_price_lms'] = str_replace(",", "", $price_lms);
        $addon['mad_price_mms'] = str_replace(",", "", $price_mms);
        $addon['mad_price_grs_mms'] = str_replace(",", "", $price_grs_mms);
        $addon['mad_price_nas_mms'] = str_replace(",", "", $price_nas_mms);
        $addon['mad_price_smt'] = str_replace(",", "", $price_smt);
        $addon['mad_weight1'] = str_replace(",", "", $rate1);
        $addon['mad_weight2'] = str_replace(",", "", $rate2);
        $addon['mad_weight3'] = str_replace(",", "", $rate3);
        $addon['mad_weight4'] = str_replace(",", "", $rate4);
        $addon['mad_weight5'] = str_replace(",", "", $rate5);
        $addon['mad_weight6'] = str_replace(",", "", $rate6);
        $addon['mad_weight7'] = str_replace(",", "", $rate7);
        $addon['mad_weight8'] = str_replace(",", "", $rate8);
        $addon['mad_weight9'] = str_replace(",", "", $rate9);
        $addon['mad_weight10'] = str_replace(",", "", $rate10);

        //2019.02.18 Full Care비용 추가
        $addon['mad_price_full_care'] = str_replace(",", "", $price_full_care);
        $addon['mad_price_grs_biz'] = str_replace(",", "", $price_grs_biz);
        $addon['mad_price_smt_sms'] = str_replace(",", "", $price_smt_sms);
        $addon['mad_price_smt_mms'] = str_replace(",", "", $price_smt_mms);

        if (!empty($this->input->post('incentive'))){
            $addon['mad_incentive'] = $incentive;
        }

        if($this->input->post("mem_rcs_yn")=="Y"){
            $addon['mad_price_rcs_tem'] = str_replace(",", "", $price_rcs_tem);
            $addon['mad_price_rcs_sms'] = str_replace(",", "", $price_rcs_sms);
            $addon['mad_price_rcs'] = str_replace(",", "", $price_rcs);
            $addon['mad_price_rcs_mms'] = str_replace(",", "", $price_rcs_mms);
        }

        if($new || $mem->mad_mem_id < 1) {
            $addon['mad_mem_id'] = $key;
            $this->db->insert("wt_member_addon", $addon);
        } else {
            $this->db->update("wt_member_addon", $addon, array("mad_mem_id"=>$key));
        }
        /* member_userid 등록 */
        $uData = array();
        $uData['mem_userid'] = $id;
        $uData['mem_status'] = '0';
        if($new || $mem->mad_mem_id < 1) {
            $uData['mem_id'] = $key;
            $this->db->insert("cb_member_userid", $uData);
        } else {
            $this->db->update("cb_member_userid", $uData, array("mem_id"=>$key));
        }
        /* member_nickname 등록 */
        $nData = array();
        $nData['mni_nickname'] = $nickname;
        $nData['mni_start_datetime'] = cdate('Y-m-d H:i:s');
        if($new || $mem->mad_mem_id < 1) {
            $nData['mem_id'] = $key;
            $this->db->insert("cb_member_nickname", $nData);
        } else {
            $this->db->update("cb_member_nickname", $nData, array("mem_id"=>$key));
        }
        /* 신규등록인 경우 필요 테이블 생성 */
        if($new) {
            $this->Biz_model->make_msg_log_table($id);
            $this->Biz_model->make_customer_book($id);
            $this->Biz_model->make_user_image_table($id);
            $this->Biz_model->make_user_deposit_table($id);
            $this->Biz_model->make_send_cache_table($id);
        }

        $sql = "
            SELECT
                *
            FROM
                cb_member_dormant_dhn
            WHERE
                mem_id = '".$key."';
        ";

        $dormancy_data = $this->db->query($sql)->row();
        $set_dormant_flag = $this->input->post("dormant_yn");

        if (!empty($dormancy_data->mem_id)){
            if ($dormancy_data->mdd_dormant_flag != $this->input->post("dormant_yn")){
                if ($set_dormant_flag == 0){
                    $sql = "
                        UPDATE
                            cb_member_dormant_dhn
                        SET
                            mdd_dormant_flag = ".$set_dormant_flag."
                          , mdd_update_datetime = '".date("Y-m-d H:i:s")."'
                        WHERE
                            mem_id = '".$key."'
                    ";
                } else if ($set_dormant_flag == 1){
                    $sql = "
                        UPDATE
                            cb_member_dormant_dhn
                        SET
                            mdd_dormant_flag = ".$set_dormant_flag."
                          , mdd_update_datetime = NULL
                        WHERE
                            mem_id = '".$key."'
                    ";
                }
                $this->db->query($sql);
            }
        } else if (empty($dormancy_data->mem_id)){
            $sql = "
                INSERT INTO cb_member_dormant_dhn
                VALUES ('".$key."', '".$id."', '".$set_dormant_flag."', NULL)
            ";
            $this->db->query($sql);
        }

        if ($this->db->error()['code'] > 0) {
            echo '<script type="text/javascript">alert("저장 오류입니다.");history.back();</script>';
        } else {
            //header("Location: /biz/partner/lists");
            header("Location: /biz/partner/view?".$id);
        }
    }

    public function partner_charge()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        $view['perpage'] = 20;
		$view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['rs'] = $this->Biz_model->get_member($view['param']['key'], true);

        /* 관리자가 아닌경우 직속 하위의 파트너만 열람가능 */
        if($this->member->item("mem_level")<100 && $view['rs']->mrg_recommend_mem_id!=$this->member->item('mem_id')) { show_404();	exit; }

        if(!$view['rs'] || $view['rs']->mem_userid!=$view['param']['key']) { show_404(); exit; }
        $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));
        if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }

        $this->Biz_model->make_user_deposit_table($view['rs']->mem_userid);

        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['set_date'] = $this->input->post('set_date');
        $term = $this->Biz_model->set_Term($view['param']['set_date']);
        $view['param']['endDate'] = $term->endDate;
        $view['param']['startDate'] = $term->startDate;
        $param = array($view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59");

		//$view['total_rows'] = $this->Biz_model->get_table_count("cb_amt_".$view['rs']->mem_userid, "amt_kind in ('1', '2', '3') and amt_datetime between ? and ?", $param);

        $sql = "
			select *
			from cb_amt_".$view['rs']->mem_userid."
			where amt_kind in ('1', '2', '3') and amt_datetime between ? and ? order by amt_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //$view['list'] = $this->db->query($sql, $param)->result();

		//검색조건
		$sch = "";
		$sch .= "AND amt_kind IN ('1', '2', '3') ";
		$sch .= "AND amt_datetime BETWEEN '". $view['param']['startDate'] ." 00:00:00' AND '". $view['param']['endDate'] ." 23:59:59' ";

		//전체건수
		$sql = "
		SELECT SUM(cnt) AS cnt
		FROM (
			SELECT
				1 AS cnt
			FROM cb_amt_".$view['rs']->mem_userid."
			WHERE 1=1 ". $sch ."
			GROUP BY amt_kind, DATE_FORMAT(amt_datetime, '%Y-%m-%d'), amt_memo
		) t ";
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//현황
		$sql = "
		SELECT
			  DATE_FORMAT(amt_datetime, '%Y-%m-%d') AS amt_datetime /* 충전 일시 */
			, amt_kind /* 구분(1.충전 금액, 3.환불 금액) */
			, amt_memo /* 비고 */
			, sum(amt_amount) AS amt_amount /* 금액 */
		FROM cb_amt_".$view['rs']->mem_userid."
		WHERE 1=1 ". $sch ."
		GROUP BY amt_kind, DATE_FORMAT(amt_datetime, '%Y-%m-%d'), amt_memo
		ORDER BY amt_datetime DESC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		$view['list'] = $this->db->query($sql, $param)->result();

		$this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'partner_charge',
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

    public function partner_recipient()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['rs'] = $this->Biz_model->get_member($view['param']['key'], true);

        /* 관리자가 아닌경우 직속 하위의 파트너만 열람가능 */
        if($this->member->item("mem_level")<100 && $view['rs']->mrg_recommend_mem_id!=$this->member->item('mem_id')) { show_404();	exit; }

        if(!$view['rs'] || count($view['rs']) < 1) { show_404(); exit; }
        $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));
        if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }

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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'partner_recipient',
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

    public function inc_recipient_lists() {
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['page'] = $this->input->post("page");
        $view['total_rows'] = $this->Biz_model->get_table_count("cb_ab_".$view['param']['key'], "", array());

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        $sql = "
			select a.*
			from cb_ab_".$view['param']['key']." a
			/*		inner join template b	*/
			where 1=1 order by a.ab_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql, $param)->result();
        $view['view']['canonical'] = site_url();

        $this->load->view('biz/partner/inc_recipient_lists',$view);
    }

    public function partner_sent()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        $view['perpage'] = 20;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['rs'] = $this->Biz_model->get_member($view['param']['key'], true);

        /* 관리자가 아닌경우 직속 하위의 파트너만 열람가능 */
        if($this->member->item("mem_level")<100 && $view['rs']->mrg_recommend_mem_id!=$this->member->item('mem_id')) { show_404();	exit; }

        if(!$view['rs'] || count($view['rs']) < 1) { show_404(); exit; }
        $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));
        if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }

        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['set_date'] = $this->input->post('set_date');
        $term = $this->Biz_model->set_Term($view['param']['set_date']);
        $view['param']['endDate'] = $term->endDate;
        $view['param']['startDate'] = $term->startDate;
        $param = array($view['rs']->mem_id, $view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59", $view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59");
        $where = "mst_mem_id=? and (CASE WHEN mst_reserved_dt = '00000000000000' THEN mst_datetime BETWEEN ? AND ? ELSE STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN ? AND ? END )";
        $view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_msg_sent", $where, $param);
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        //log_message("ERROR", "view['param']['startDate'] : ".$view['param']['startDate']);
        //log_message("ERROR", "view['param']['endDate'] : ".$view['param']['endDate']);

        $sql = "
			select a.*, STR_TO_DATE(a.mst_reserved_dt,'%Y%m%d%H%i%s') mst_reserved_date
			from cb_wt_msg_sent a
			where a.mst_mem_id=?
                and (CASE WHEN a.mst_reserved_dt = '00000000000000'
                    THEN a.mst_datetime BETWEEN ? AND ?
                    ELSE STR_TO_DATE(a.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN ? AND ? END )
            order by a.mst_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //log_message("ERROR", "partner_sent() sql : ".$sql);
//         $sql = "
// 			select a.*
// 			from cb_wt_msg_sent a
// 			/*		inner join template b	*/
// 			where a.mst_mem_id=? and a.mst_datetime between ? and ? order by a.mst_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];

        $view['list'] = $this->db->query($sql, $param)->result();
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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'partner_sent',
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

    public function view()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['rs'] = $this->Biz_model->get_member($view['param']['key'], true);
        $sql = "select spf_biz_sheet from cb_wt_send_profile where spf_mem_id=? and spf_biz_sheet is not null and spf_biz_sheet<>''";
        $view['biz_sheet'] = $this->db->query($sql, array($view['rs']->mem_id))->result_array();

		/*
		$long_url = "http://igenie.dhndev.co.kr/biz/partner/view?dhn";
		$short_url = "";
        echo "long_url : ". $long_url ."<br>";
        $i = 0;

        while($i < 10) {
            $surl = array();
            $surl['url'] = $long_url;
			$id = 50715;

			if($id >= 999999) {
			  $str = '2'.sprintf("%06d", ($id % 999999) );
			} else {
			  $str = '2'.sprintf("%06d", $id);
			}

			$this->load->library('Base62');
			$short_url = $this->base62->encode($str);
			$i = 11;
        }
        echo "short_url : ". $short_url ."<br>";
		*/

        //$child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));

        /* 관리자가 아닌경우 직속 하위의 파트너만 열람가능 */
        //if($this->member->item("mem_level")<100 && !in_array($view['rs']->mem_id, $child)) { show_404();	exit; }
        //if(!$view['rs'] || count($view['rs']) < 1) { show_404(); exit; }
       // if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }

		$mem_id = $view['rs']->mem_id; //파트너 회원번호
		$mem_userid = $view['rs']->mem_userid; //파트너 회원ID
		$mem_site_key = $view['rs']->mem_site_key; //파트너 사이트연결 Key
		//echo "mem_id : ". $mem_id .", mem_userid : ". $mem_userid .", mem_site_key : ". $mem_site_key ."<br>";
        $sql = "select b.mtg_useyn as mtg_useyn1, c.mtg_useyn as mtg_useyn2 from cb_member a left join cb_member_template_group b ON a.mem_id = b.mtg_mem_id and b.mtg_profile = 'A' LEFT JOIN cb_member_template_group c ON a.mem_id = c.mtg_mem_id and c.mtg_profile = 'B'  where a.mem_id = '".$mem_id."' ";
        $view['member_temp'] = $this->db->query($sql)->row();

		//사이트연결 Key가 없는 경우
		if($mem_site_key == ""){ //사이트연결 Key가 없는 경우
			$site_key = $mem_id . $mem_userid . cdate('YmdHis');
			$mem_site_key = $this->funn->encrypt($site_key);
			$view['rs']->mem_site_key = $mem_site_key;
			//echo "site_key : ". $site_key .", mem_site_key : ". $mem_site_key ."<br>";
			//사이트연결 Key 업데이트
			$sql = "
			UPDATE cb_member SET
				mem_site_key = '". $mem_site_key ."'
			WHERE mem_id = '". $mem_id ."'
			AND mem_userid = '". $mem_userid ."' ";
			$this->db->query($sql);
		}

		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view['rs']->mem_sale_point_id : ". $view['rs']->mem_sale_point_id .", view['rs']->mem_sales_mng_id : ".$view['rs']->mem_sales_mng_id);
		if ($view['rs']->mem_sale_point_id > 0) {
		    $sql = "select * from dhn_branch_office where (dbo_part='BO' or (dbo_part='DE' and dbo_genie_userid<>'')) and dbo_id = ".$view['rs']->mem_sale_point_id;
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
		    $tpm_result = $this->db->query($sql)->row();
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpm_result->dbo_name : ".$tpm_result->dbo_name);
		    $view['branch'] = $tpm_result->dbo_name;

		} else {
		    $view['branch'] = "미지정";
		}

		if ($view['rs']->mem_sale_point_id > 0) {
		    $sql = "select a.ds_id, a.ds_dbo_id, a.ds_name,
                        CASE a.ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장'
                            WHEN 'MA' THEN '과장' WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END ds_position, b.dbo_name
                    from dhn_salesperson a left join dhn_branch_office b on a.ds_dbo_id=b.dbo_id where a.ds_id  = ".$view['rs']->mem_sales_mng_id;
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
		    $tpm_result = $this->db->query($sql)->row();
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpm_result->ds_name : ".$tpm_result->ds_name. ", tpm_result->ds_position : ".$tpm_result->ds_position);
		    $view['salesperson'] = $tpm_result->ds_name." ".$tpm_result->ds_position;
		} else {
		    $view['salesperson'] = "미지정";
		}

        if ($view['rs']->mem_management_point_id > 0) {
		    $sql = "select * from dhn_branch_office where (dbo_part='BO' or (dbo_part='DE' and dbo_genie_userid<>'')) and dbo_id = ".$view['rs']->mem_management_point_id;
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
		    $tpm_result = $this->db->query($sql)->row();
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpm_result->dbo_name : ".$tpm_result->dbo_name);
		    $view['branch_for_manage'] = $tpm_result->dbo_name;

		} else {
		    $view['branch_for_manage'] = "미지정";
		}

		if ($view['rs']->mem_management_mng_id > 0) {
		    $sql = "select a.ds_id, a.ds_dbo_id, a.ds_name,
                        CASE a.ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장'
                            WHEN 'MA' THEN '과장' WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END ds_position, b.dbo_name
                    from dhn_salesperson a left join dhn_branch_office b on a.ds_dbo_id=b.dbo_id where a.ds_id  = ".$view['rs']->mem_management_mng_id;
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
		    $tpm_result = $this->db->query($sql)->row();
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpm_result->ds_name : ".$tpm_result->ds_name. ", tpm_result->ds_position : ".$tpm_result->ds_position);
		    $view['salesperson_for_manage'] = $tpm_result->ds_name." ".$tpm_result->ds_position;
		} else {
		    $view['salesperson_for_manage'] = "미지정";
		}

        $sql = "
            SELECT
                *
            FROM
                cb_member_dormant_dhn
            WHERE mem_id = '".$view['rs']->mem_id."'
        ";
        $view['dormancy'] = $this->db->query($sql)->row();

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
            'path' => 'biz/partner',
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

    public function no_view()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['rs'] = $this->Biz_model->get_no_member($view['param']['key'], true);
        $sql = "select spf_biz_sheet from cb_wt_send_profile where spf_mem_id=? and spf_biz_sheet is not null and spf_biz_sheet<>''";
        $view['biz_sheet'] = $this->db->query($sql, array($view['rs']->mem_id))->result_array();

		/*
		$long_url = "http://igenie.dhndev.co.kr/biz/partner/view?dhn";
		$short_url = "";
        echo "long_url : ". $long_url ."<br>";
        $i = 0;

        while($i < 10) {
            $surl = array();
            $surl['url'] = $long_url;
			$id = 50715;

			if($id >= 999999) {
			  $str = '2'.sprintf("%06d", ($id % 999999) );
			} else {
			  $str = '2'.sprintf("%06d", $id);
			}

			$this->load->library('Base62');
			$short_url = $this->base62->encode($str);
			$i = 11;
        }
        echo "short_url : ". $short_url ."<br>";
		*/

        //$child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));

        /* 관리자가 아닌경우 직속 하위의 파트너만 열람가능 */
        //if($this->member->item("mem_level")<100 && !in_array($view['rs']->mem_id, $child)) { show_404();	exit; }
        //if(!$view['rs'] || count($view['rs']) < 1) { show_404(); exit; }
       // if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }

		$mem_id = $view['rs']->mem_id; //파트너 회원번호
		$mem_userid = $view['rs']->mem_userid; //파트너 회원ID
		$mem_site_key = $view['rs']->mem_site_key; //파트너 사이트연결 Key
		//echo "mem_id : ". $mem_id .", mem_userid : ". $mem_userid .", mem_site_key : ". $mem_site_key ."<br>";
        $sql = "select b.mtg_useyn as mtg_useyn1, c.mtg_useyn as mtg_useyn2 from cb_member a left join cb_member_template_group b ON a.mem_id = b.mtg_mem_id and b.mtg_profile = 'A' LEFT JOIN cb_member_template_group c ON a.mem_id = c.mtg_mem_id and c.mtg_profile = 'B'  where a.mem_id = '".$mem_id."' ";
        $view['member_temp'] = $this->db->query($sql)->row();

		//사이트연결 Key가 없는 경우
		if($mem_site_key == ""){ //사이트연결 Key가 없는 경우
			$site_key = $mem_id . $mem_userid . cdate('YmdHis');
			$mem_site_key = $this->funn->encrypt($site_key);
			$view['rs']->mem_site_key = $mem_site_key;
			//echo "site_key : ". $site_key .", mem_site_key : ". $mem_site_key ."<br>";
			//사이트연결 Key 업데이트
			$sql = "
			UPDATE cb_member SET
				mem_site_key = '". $mem_site_key ."'
			WHERE mem_id = '". $mem_id ."'
			AND mem_userid = '". $mem_userid ."' ";
			$this->db->query($sql);
		}

		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view['rs']->mem_sale_point_id : ". $view['rs']->mem_sale_point_id .", view['rs']->mem_sales_mng_id : ".$view['rs']->mem_sales_mng_id);
		if ($view['rs']->mem_sale_point_id > 0) {
		    $sql = "select * from dhn_branch_office where (dbo_part='BO' or (dbo_part='DE' and dbo_genie_userid<>'')) and dbo_id = ".$view['rs']->mem_sale_point_id;
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
		    $tpm_result = $this->db->query($sql)->row();
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpm_result->dbo_name : ".$tpm_result->dbo_name);
		    $view['branch'] = $tpm_result->dbo_name;

		} else {
		    $view['branch'] = "미지정";
		}

		if ($view['rs']->mem_sale_point_id > 0) {
		    $sql = "select a.ds_id, a.ds_dbo_id, a.ds_name,
                        CASE a.ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장'
                            WHEN 'MA' THEN '과장' WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END ds_position, b.dbo_name
                    from dhn_salesperson a left join dhn_branch_office b on a.ds_dbo_id=b.dbo_id where a.ds_id  = ".$view['rs']->mem_sales_mng_id;
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
		    $tpm_result = $this->db->query($sql)->row();
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpm_result->ds_name : ".$tpm_result->ds_name. ", tpm_result->ds_position : ".$tpm_result->ds_position);
		    $view['salesperson'] = $tpm_result->ds_name." ".$tpm_result->ds_position;
		} else {
		    $view['salesperson'] = "미지정";
		}

        if ($view['rs']->mem_management_point_id > 0) {
		    $sql = "select * from dhn_branch_office where (dbo_part='BO' or (dbo_part='DE' and dbo_genie_userid<>'')) and dbo_id = ".$view['rs']->mem_management_point_id;
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
		    $tpm_result = $this->db->query($sql)->row();
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpm_result->dbo_name : ".$tpm_result->dbo_name);
		    $view['branch_for_manage'] = $tpm_result->dbo_name;

		} else {
		    $view['branch_for_manage'] = "미지정";
		}

		if ($view['rs']->mem_management_mng_id > 0) {
		    $sql = "select a.ds_id, a.ds_dbo_id, a.ds_name,
                        CASE a.ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장'
                            WHEN 'MA' THEN '과장' WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END ds_position, b.dbo_name
                    from dhn_salesperson a left join dhn_branch_office b on a.ds_dbo_id=b.dbo_id where a.ds_id  = ".$view['rs']->mem_management_mng_id;
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ".$sql);
		    $tpm_result = $this->db->query($sql)->row();
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpm_result->ds_name : ".$tpm_result->ds_name. ", tpm_result->ds_position : ".$tpm_result->ds_position);
		    $view['salesperson_for_manage'] = $tpm_result->ds_name." ".$tpm_result->ds_position;
		} else {
		    $view['salesperson_for_manage'] = "미지정";
		}

        $sql = "
            SELECT
                *
            FROM
                cb_member_dormant_dhn
            WHERE mem_id = '".$view['rs']->mem_id."'
        ";
        $view['dormancy'] = $this->db->query($sql)->row();

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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'no_view',
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

	//파트너 관리 > 비밀번호 조회
	public function pass_visible(){
		$mem_id = $this->input->post("mid"); //회원 일련번호
		$mem_userid = $this->input->post("userid"); //회원 아이디
		if($this->member->item('mem_level') >= 100){
			//비밀번호 조회
			$sql = "select mem_shop_code from cb_member where mem_id = '". $mem_id ."' and mem_userid = '". $mem_userid ."' ";
			$pass = $this->db->query($sql)->row()->mem_shop_code;
			if($pass !=""){
				$pass = $this->funn->decrypt($pass); //비밀번호 복호화
			}
		}
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > pass : ". $pass);
		$result = array();
		$result['pass'] = $pass;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//계약서보기
	public function view_contract_pdf() {
		$user_id = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
		$sql = "select mem_contract_filepath, mem_contract_filename from cb_member where mem_userid='". $user_id ."' ";
		//log_Message("ERROR", $_SERVER['REQUEST_URI'] ." > view_contract_pdf sql : ".$sql);
		$result = $this->db->query($sql)->row();

		$contract_file = base_url().config_item('uploads_dir').'/member_contract/'.$result->mem_contract_filepath;
		//log_Message("ERROR", $_SERVER['REQUEST_URI'] ." > view_contract_file : ".$contract_file);
		$contract_filename = $result->mem_contract_filename;
		// log_Message("ERROR", $_SERVER['REQUEST_URI'] ." > view_contract_filename : ".$contract_filename);

		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="'.$contract_filename.'"');
		header('Content-Transfer-Encoding: binary');
		// header('Content-Length: '.filesize($contract_file));
		header('Accept-Ranges: bytes');
		@readfile($contract_file);
	}

    public function get_marttalk_partner(){
        $result = array();
        $db = $this->load->database("58m", TRUE);
        $sql = "
            SELECT
                a.mem_id
              , a.mem_userid
              , a.mem_nickname
              , TRIM(a.mem_username) AS mem_username2
            FROM
                cb_member a
            LEFT JOIN
                cb_member_dormant_dhn b
                ON
                a.mem_id = b.mem_id
            WHERE
                a.mem_useyn = 'Y'
                AND
                a.mem_cont_cancel_yn = 'N'
                AND
                a.mem_pay_type = 'B'
                AND
                (b.mdd_dormant_flag = 0 OR b.mdd_dormant_flag IS NULL)
            ORDER BY
                mem_username2
        ";
        $list = $db->query($sql)->result();
        $modal_html = "<select id = 'marttalk_list'>";
        foreach($list as $key => $a){
            $modal_html .= "<option value='" . $a->mem_id . "_" . $a->mem_userid . "_" . $a->mem_nickname . "'>" . $a->mem_username2 . "</option>";
        }
        $modal_html .= "</select><button id='btn_transfer' click='start_transfer();'>적용</button>";
        $result["modal_html"] = $modal_html;
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

    public function modify_deposit(){

        $result = array();
        $sql ="		select sum(cnt_ft) cnt_ft,
            sum(cnt_ft_lms) cnt_ft_lms,
            sum(cnt_ft_sms) cnt_ft_sms,
            sum(cnt_ft_pms) cnt_ft_pms,
            sum(cnt_fti_pms) cnt_fti_pms,
            sum(cnt_ft_img) cnt_ft_img,
            sum(cnt_at) cnt_at,
            sum(cnt_at_lms) cnt_at_lms,
            sum(cnt_at_sms) cnt_at_sms,
            sum(cnt_ai) cnt_ai,
            sum(cnt_ai_lms) cnt_ai_lms,
            sum(cnt_ai_sms) cnt_ai_sms,
            -- sum(cnt_phn) cnt_phn,
            sum(cnt_pms) cnt_pms,
            sum(cnt_sms) cnt_sms,
            sum(cnt_lms) cnt_lms,
            sum(cnt_mms) cnt_mms
        from (
            select
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and ifnull(a.MSG_SMS, '')='' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ft,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ft_lms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ft_sms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wp2' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ft_pms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')<>'' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wp2' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_fti_pms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')<>'' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ft_img,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')='' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_at,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_at_lms,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_at_sms,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')='' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N'  then 1 else 0 end) cnt_ai,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ai_lms,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ai_sms,
                -- sum(case when a.MESSAGE_TYPE='pn' then 1 else 0 end) cnt_phn,
                sum(case when a.MESSAGE_TYPE='pm' then 1 else 0 end) cnt_pms,
                sum(case when a.MESSAGE_TYPE='sm' then 1 else 0 end) cnt_sms,
                sum(case when a.MESSAGE_TYPE='lm' then 1 else 0 end) cnt_lms,
                sum(case when a.MESSAGE_TYPE='mm' then 1 else 0 end) cnt_mms
            from DHN_REQUEST a
            where a.REMARK2 = ? and a.RESERVE_DT <> '00000000000000'
            union all
            select
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and ifnull(a.MSG_SMS, '')='' then 1 else 0 end) cnt_ft,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 then 1 else 0 end) cnt_ft_lms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 then 1 else 0 end) cnt_ft_sms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wp2' then 1 else 0 end) cnt_ft_pms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')<>'' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wp2' then 1 else 0 end) cnt_fti_pms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')<>'' then 1 else 0 end) cnt_ft_img,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')='' then 1 else 0 end) cnt_at,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 then 1 else 0 end) cnt_at_lms,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 then 1 else 0 end) cnt_at_sms,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')='' then 1 else 0 end) cnt_ai,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 then 1 else 0 end) cnt_ai_lms,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 then 1 else 0 end) cnt_ai_sms,
                -- sum(case when a.MESSAGE_TYPE='ph' then 1 else 0 end) cnt_phn,
                sum(case when a.MESSAGE_TYPE='ph' and P_INVOICE='SMART' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wp2' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_pms,
                sum(case when a.MESSAGE_TYPE='ph' and P_INVOICE='SMART' and a.sms_kind ='S' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wcs' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_sms,
                sum(case when a.MESSAGE_TYPE='ph' and P_INVOICE='SMART' and a.sms_kind ='L' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wc' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_lms,
                sum(case when a.MESSAGE_TYPE='ph' and P_INVOICE='SMART' and a.sms_kind ='M' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_mms
            from DHN_REQUEST_RESULT a
            where a.REMARK2 = ? and a.RESERVE_DT <> '00000000000000'
            ) as b";
        $query = $this->db->query($sql, array($this->input->post("genie_mem_id"), $this->input->post("genie_mem_id")));
        $flag = false;
        if($query) {	/* 발송대기중인 메시지의 요금을 차감한다. : lms 기본 발송 -> phn */
            $wait = $query->row();
            if($wait->cnt_ft) { $flag = true; }
            else if($wait->cnt_ft_lms) { $flag = true; }
            else if($wait->cnt_ft_pms) { $flag = true; }
            else if($wait->cnt_fti_pms) { $flag = true; }
            else if($wait->cnt_ft_sms) { $flag = true; }
            else if($wait->cnt_ft_img) { $flag = true; }
            else if($wait->cnt_at) { $flag = true; }
            else if($wait->cnt_at_lms) { $flag = true; }
            else if($wait->cnt_at_sms) { $flag = true; }
            else if($wait->cnt_ai) { $flag = true; }
            else if($wait->cnt_ai_lms) { $flag = true; }
            else if($wait->cnt_ai_sms) { $flag = true; }
            //if($wait->cnt_phn) { $mCoin -= ($wait->cnt_phn * $this->price_phn); }
            else if($wait->cnt_pms) { $flag = true; }
            else if($wait->cnt_sms) { $flag = true; }
            else if($wait->cnt_lms) { $flag = true; }
            else if($wait->cnt_mms) { $flag = true; }
        }
        if($flag) {
            $result["code"] = "NG";
        } else {
            $result["code"] = "OK";
            // return;

            // TODO: 지니 차감 start
            $dep_id = $this->Unique_id_model->get_id($this->input->ip_address());
            $sql = "
                SELECT
                    *
                FROM
                    cb_member
                WHERE
                    mem_id = " . $this->input->post('genie_mem_id') . "
            ";
            $nick = $this->db->query($sql)->row()->mem_nickname;
            $dep_from_type = 'deposit';
            $dep_to_type = 'removededuct';
            $dep_pay_type = 'deduction';
            $igenieCoin = $this->funn->getCoin($this->input->post("genie_mem_id"), $this->input->post("genie_mem_userid"));
            $f_igenieCoin = floor($igenieCoin['coin']);
            $result["genie_before"] = $f_igenieCoin;
            $result["genie_after"] = 0;
            $dep_deposit = $f_igenieCoin * (-1);
            $sum = $this->Deposit_model->get_deposit_sum($this->input->post("genie_mem_id"));
            $deposit_sum = $sum + $dep_deposit;

            $insertdata = array(
                'dep_id' => $dep_id,
                'mem_id' => $this->input->post("genie_mem_id"),
                'mem_nickname' => $nick,
                'dep_from_type' => $dep_from_type,
                'dep_to_type' => $dep_to_type,
                'dep_pay_type' => $dep_pay_type,
                'dep_deposit_request' => $dep_deposit,
                'dep_deposit' => $dep_deposit,
                'dep_deposit_sum' => $deposit_sum,
                'dep_content' => "지니1(" . $this->input->post("genie_mem_userid") . ") -> 마트톡(" . $this->input->post("marttalk_mem_userid") . ") 이관 차감",
                'dep_admin_memo' => "작업 아이피 : " . $_SERVER["REMOTE_ADDR"],
                'dep_datetime' => cdate('Y-m-d H:i:s'),
                'dep_deposit_datetime' => cdate('Y-m-d H:i:s'),
                'dep_ip' => $this->input->ip_address(),
                'dep_useragent' => $this->agent->agent_string(),
                'dep_status' => 1,
            );
            $this->db->insert("cb_deposit", $insertdata);
            //
            $this->Biz_model->deposit_appr($this->input->post("genie_mem_id"), $dep_id, 7);
            // TODO: 지니 차감 end

            // TODO: 마트톡 충전 start
            $db = $this->load->database("58m", TRUE);

            $dep_from_type = 'removecash';
            $dep_to_type = 'deposit';
            $dep_pay_type = 'removecash';
            $dep_deposit = $dep_deposit * (-1);
            $m_coin = $this->get_marttalk_coin($this->input->post("marttalk_mem_id"), $this->input->post("marttalk_mem_userid"));
            $result["marttalk_before"] = floor($m_coin["coin"]);
            $deposit_coin = $m_coin["coin"] + $dep_deposit;
            $result["marttalk_after"] = $deposit_coin;
            $deposit_sum = $m_coin["sum_coin"] + $dep_deposit;

            $insertdata2 = array(
            'dep_id' => $dep_id,
            'mem_id' => $this->input->post("marttalk_mem_id"),
            'mem_nickname' => $this->input->post("marttalk_mem_nickname"),
            'dep_from_type' => $dep_from_type,
            'dep_to_type' => $dep_to_type,
            'dep_pay_type' => $dep_pay_type,
            'dep_deposit_request' => $dep_deposit,
            'dep_deposit' => $dep_deposit,
            'dep_deposit_sum' => $deposit_sum,
            'dep_content' => "지니1(" . $this->input->post("genie_mem_userid") . ") -> 마트톡(" . $this->input->post("marttalk_mem_userid") . ") 이관 충전",
            'dep_admin_memo' => "작업 아이피 : " . $_SERVER["REMOTE_ADDR"],
            'dep_datetime' => cdate('Y-m-d H:i:s'),
            'dep_deposit_datetime' => cdate('Y-m-d H:i:s'),
            'dep_ip' => $this->input->ip_address(),
            'dep_useragent' => $this->agent->agent_string(),
            'dep_status' => 1,
            );
            $db->insert("cb_deposit", $insertdata2);

            $insertdata3 = array();
            $insertdata3['amt_datetime'] = cdate('Y-m-d H:i:s');
            $insertdata3['amt_kind'] = "6";
            $insertdata3['amt_amount'] = abs($dep_deposit);
            $insertdata3['amt_point'] = 0;
            $insertdata3['amt_memo'] = "지니1(" . $this->input->post("genie_mem_userid") . ") -> 마트톡(" . $this->input->post("marttalk_mem_userid") . ") 이관 충전";
            $insertdata3['amt_reason'] = $dep_id;
            $db->insert("cb_amt_" . $this->input->post("marttalk_mem_userid"), $insertdata3);
            // TODO: 마트톡 충전 end
        }
        $json = json_encode($result, JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;


    }

    function get_marttalk_coin($m_mem_id, $m_mem_userid){
        $url = "http://kakaomarttalk.com/api/get_mem_coin";

        $post   = array(
            "mem_id" => $m_mem_id
          , "mem_userid" => $m_mem_userid
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data",
            ),
        ));
        $buffer = curl_exec($curl);
        $cinfo = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($cinfo['http_code'] != 200){
            log_message("ERROR", "cinfo : ".$cinfo);
        }
        $response = json_decode($buffer, true);
        return $response;
    }

    public function company_lists()
    {
        $view = array();
        $view['perpage'] = 20;
        $view['userid'] = empty($this->input->get("uid")) ? "ALL" : $this->input->get("uid"); //소속 ID
        $view['dboid'] = empty($this->input->get("dboid")) ? "ALL" : $this->input->get("dboid"); //지점
        $view['dsid'] = empty($this->input->get("dsid")) ? "ALL" : $this->input->get("dsid"); //영업담당자
        $view['ls'] = empty($this->input->get("ls")) && $this->input->get("ls") != "0" ? "ALL" : $this->input->get("ls"); //마지막발송
        $view['dormancy_yn'] = empty($this->input->get("dormancy_yn")) ? "" : $this->input->get("dormancy_yn"); //휴면상태
        $view['search_type'] = empty($this->input->get("search_type")) ? "" : $this->input->get("search_type"); //검색조건
        $view['search_for'] = empty($this->input->get("search_for")) ? "" : $this->input->get("search_for"); //검색내용
        $view['page'] = empty($this->input->get("page")) ? 1 : $this->input->get("page");

        // log_message("error", "SONG : " . $view['userid']);
        if($this->member->item('mem_id') == "2") $view['userid'] = "3";

        //소속 리스트
		$view['users'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));

        //지점
        $sql = "
            SELECT
                *
            FROM
                dhn_branch_office2
        ";
        $view["branch"] = $this->db->query($sql)->result();

        //영업담당자
        $sql = "
            SELECT
                *
            FROM
                dhn_salesperson2
            WHERE
                TRIM(ds_name) <> ''
        ";
        $view["salesperson"] = $this->db->query($sql)->result();

        $where = " mem_useyn='Y' ";

        if ($view['dboid'] != "ALL"){
            $where .= " AND a.mem_management_point_id = " . $view['dboid'] . " ";
        }

        if ($view['dsid'] != "ALL"){
            $where .= " AND a.mem_management_mng_id = " . $view['dsid'] . " ";
        }

        if($view['dormancy_yn'] == "Y"){ //휴면계정 유무 2021.12.30 추가
            $where .= " and d.mdd_dormant_flag = 1 ";
        }else if($view['dormancy_yn'] == "N"){
            $where .= " and (ISNULL(d.mdd_dormant_flag) OR d.mdd_dormant_flag = 0) " ;
	    }

        if ($view['ls'] != "ALL"){
            if ($view['ls'] != "0"){
                if ($view['ls'] == "1"){
                    $timestamp1 = strtotime("-1 months");
                    $timestamp2 = strtotime("-2 months");
                    $where .= " AND DATE(e.max_date) BETWEEN '" . date("Y-m-d H:i:s", $timestamp2) . "' AND '" . date("Y-m-d H:i:s", $timestamp1) . "' ";
                } else if ($view['ls'] == "2"){
                    $timestamp1 = strtotime("-2 months");
                    $timestamp2 = strtotime("-3 months");
                    $where .= " AND DATE(e.max_date) BETWEEN '" . date("Y-m-d H:i:s", $timestamp2) . "' AND '" . date("Y-m-d H:i:s", $timestamp1) . "' ";
                } else if ($view['ls'] == "3"){
                    $timestamp1 = strtotime("-3 months");
                    $timestamp2 = strtotime("-6 months");
                    $where .= " AND DATE(e.max_date) BETWEEN '" . date("Y-m-d H:i:s", $timestamp2) . "' AND '" . date("Y-m-d H:i:s", $timestamp1) . "' ";
                } else if ($view['ls'] == "6"){
                    $timestamp1 = strtotime("-6 months");
                    $where .= " AND e.max_date < '" . date("Y-m-d H:i:s", $timestamp1) . "' ";
                }
            } else {
                $where .= " AND e.max_date IS NULL ";
            }
        }

        $param = array();

        ($view['userid'] && is_numeric($view['userid'])) ? array_push($param, $view['userid']) : array_push($param, $this->member->item('mem_id'));

        if($view['search_for'] && $view['search_type']){
            $where .= " and replace(a.mem_".$view['search_type'].", ' ','') like replace(" . "'%".$view['search_for']."%'" . ", ' ','') ";
        }

        $rs = $this->Biz_model->get_partner_list_for_management($param, $where, $view['page'], $view['perpage']);
        $view['rs'] = $rs->lists;
        $view['total_rows'] = $rs->total_rows;

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = $view['page'];
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['page']);
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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'company_lists',
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

    function apply_note(){
        $mem_id = $this->input->post("mem_id");
        $note = $this->input->post("note");
        // $note = str_replace("\n", "<br>", $this->input->post("note"));

        $this->db
            ->set("mem_note", $note)
            ->where("mem_id", $mem_id)
            ->update("cb_member");
        $this->db
            ->set("mn_text", $note)
            ->set("mn_mem_id", $mem_id)
            ->insert("cb_wt_mem_note");
        $result = array();
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

    public function download_for_company() {
        $view = array();
        $view['perpage'] = 20;
        $view['userid'] = empty($this->input->post("uid")) ? "ALL" : $this->input->post("uid"); //소속 ID
        $view['dboid'] = empty($this->input->post("dboid")) ? "ALL" : $this->input->post("dboid"); //지점
        $view['dsid'] = empty($this->input->post("dsid")) ? "ALL" : $this->input->post("dsid"); //영업담당자
        $view['ls'] = empty($this->input->post("ls")) && $this->input->post("ls") != "0" ? "ALL" : $this->input->post("ls"); //마지막발송
        $view['dormancy_yn'] = empty($this->input->post("dormancy_yn")) ? "" : $this->input->post("dormancy_yn"); //휴면상태
        $view['search_type'] = empty($this->input->post("search_type")) ? "" : $this->input->post("search_type"); //검색조건
        $view['search_for'] = empty($this->input->post("search_for")) ? "" : $this->input->post("search_for"); //검색내용

        //지점
        $sql = "
            SELECT
                *
            FROM
                dhn_branch_office
        ";
        $branch = $this->db->query($sql)->result();

        $branch_arr = array();
        foreach ($branch as $a){
            $branch_arr[$a->dbo_id] = $a->dbo_name;
        }

        //영업담당자
        $sql = "
            SELECT
                *
            FROM
                dhn_salesperson
            WHERE
                TRIM(ds_name) <> ''
        ";
        $salesperson = $this->db->query($sql)->result();

        $salesperson_arr = array();
        foreach ($salesperson as $a){
            $salesperson_arr[$a->ds_id] = $a->ds_name;
        }

        $where = " mem_useyn='Y' ";

        if ($view['dboid'] != "ALL"){
            $where .= " AND a.mem_management_point_id = " . $view['dboid'] . " ";
        }

        if ($view['dsid'] != "ALL"){
            $where .= " AND a.mem_management_mng_id = " . $view['dsid'] . " ";
        }

        if($view['dormancy_yn'] == "Y"){ //휴면계정 유무 2021.12.30 추가
            $where .= " and d.mdd_dormant_flag = 1 ";
        }else if($view['dormancy_yn'] == "N"){
            $where .= " and (ISNULL(d.mdd_dormant_flag) OR d.mdd_dormant_flag = 0) " ;
	    }

        if ($view['ls'] != "ALL"){
            if ($view['ls'] != "0"){
                if ($view['ls'] == "1"){
                    $timestamp1 = strtotime("-1 months");
                    $timestamp2 = strtotime("-2 months");
                    $where .= " AND DATE(e.max_date) BETWEEN '" . date("Y-m-d H:i:s", $timestamp2) . "' AND '" . date("Y-m-d H:i:s", $timestamp1) . "' ";
                } else if ($view['ls'] == "2"){
                    $timestamp1 = strtotime("-2 months");
                    $timestamp2 = strtotime("-3 months");
                    $where .= " AND DATE(e.max_date) BETWEEN '" . date("Y-m-d H:i:s", $timestamp2) . "' AND '" . date("Y-m-d H:i:s", $timestamp1) . "' ";
                } else if ($view['ls'] == "3"){
                    $timestamp1 = strtotime("-3 months");
                    $timestamp2 = strtotime("-6 months");
                    $where .= " AND DATE(e.max_date) BETWEEN '" . date("Y-m-d H:i:s", $timestamp2) . "' AND '" . date("Y-m-d H:i:s", $timestamp1) . "' ";
                } else if ($view['ls'] == "6"){
                    $timestamp1 = strtotime("-6 months");
                    $where .= " AND e.max_date < '" . date("Y-m-d H:i:s", $timestamp1) . "' ";
                }
            } else {
                $where .= " AND e.max_date IS NULL ";
            }
        }

        $param = array();

        ($view['userid'] && is_numeric($view['userid'])) ? array_push($param, $view['userid']) : array_push($param, $this->member->item('mem_id'));

        if($view['search_for'] && $view['search_type']){
            $where .= " and replace(a.mem_".$view['search_type'].", ' ','') like replace(" . "'%".$view['search_for']."%'" . ", ' ','') ";
        }


        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            WHERE cm.mem_id = 3 AND cmr.mem_id <> cm.mem_id
            UNION ALL
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            SELECT a.*,
            a.mem_deposit total_deposit,
            a.mem_id AS memid,
            c.parent_id AS mrg_recommend_mem_id,
            IFNULL(
              (SELECT mem_username
                  FROM cb_member
                 WHERE mem_id = c.parent_id),
                '') mrg_recommend_mem_username,
            d.*,
            e.max_date,
            (select max(mn_create_datetime) from cb_wt_mem_note f where a.mem_id = f.mn_mem_id) as note_date
            FROM cmem c
            INNER JOIN cb_member a on a.mem_id = c.mem_id
                           and a.mem_useyn = 'Y'
            LEFT JOIN cb_member_dormant_dhn d on a.mem_id = d.mem_id
            LEFT JOIN (
            SELECT
            mst_mem_id,
            MAX(mst_datetime) AS max_date
            FROM
            cb_wt_msg_sent
            WHERE
            (mst_reserved_dt = '00000000000000' OR DATE_FORMAT(mst_reserved_dt,'%Y-%m-%d %H:%i:%s') < NOW())
            AND
                mst_qty >= 10
                AND
                mst_sent_platform = 'S'
            GROUP BY
            mst_mem_id
            ) e ON a.mem_id = e.mst_mem_id
			where ".$where."
            order by c.parent_id, a.mem_level desc, a.mem_id desc, a.mem_username
            ";

        // log_message('error', $sql);

        $list = $this->db->query($sql, $param)->result();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

        $tmp_id = $this->input->post('tmp_id');
        $ids = explode(',', $tmp_id);

        // 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:O1');

        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "소속");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "가입일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "계정");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "사업자번호");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "담당자이름");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "담당자연락처");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "계약 영업 지점");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "계약 영업 담당자");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "관리 지점");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, "관리 담당자");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, "휴면상태");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, "최종발송일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "비고");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, "비고작성일");

        $cnt = 1;
        $row = 2;

        foreach($list as $r) {
            $biz_text = "";
            if(!empty($r->mem_biz_reg_no)){
                if(strlen($r->mem_biz_reg_no)==10){
                    $biz_text = substr($r->mem_biz_reg_no, 0, 3)."-".substr($r->mem_biz_reg_no, 3, 2)."-".substr($r->mem_biz_reg_no, 5, 5);
                }
            }
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->mrg_recommend_mem_username);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->mem_register_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->mem_userid);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->mem_username);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $biz_text);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->mem_nickname);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->mem_emp_phone);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, (($r->mem_sale_point_id == "0") ? "미지정" : $branch_arr[$r->mem_sale_point_id]));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, (($r->mem_sales_mng_id == "0") ? "미지정" : $salesperson_arr[$r->mem_sales_mng_id]));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, (($r->mem_management_point_id == "0") ? "미지정" : $branch_arr[$r->mem_management_point_id]));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, (($r->mem_management_mng_id == "0") ? "미지정" : $salesperson_arr[$r->mem_management_mng_id]));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row,(!empty($r->mdd_dormant_flag) ? $r->mdd_dormant_flag == 1 ? "휴면" : "정상" : "정상")); //2021.12.30 조지영
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, (!empty($r->max_date) ? $r->max_date : "미발송"));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, str_replace("\n", "    ", $r->mem_note));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $r->note_date);

            $row++;
            $cnt++;
        }

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="result_list.xls"');
        header('Cache-Control: max-age=0');



        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');

    }

    function get_note(){
        $result = array();
        $mem_id = $this->input->post("mem_id");

        $this->db
            ->select("*")
            ->from("cb_wt_mem_note")
            ->where("mn_mem_id", $mem_id)
            ->order_by("mn_create_datetime", "DESC");
        $list = $this->db->get()->result();
        $cnt = count($list);

        if ($cnt > 0){
            $html = "<div id='exists' class='exists_box'>";
            $html .= "<table>";
            $html .= "<thead><tr><th>내용</th><th>작성일</th><th>상세</th></tr></thead>";
            $html .= "<tbody>";
            foreach($list as $a){
                $html .="<tr>";
                $html .= "<td>" . str_replace(" ", "\n", $a->mn_text) . "</td>";
                $html .= "<td>" . $a->mn_create_datetime . "</td>";
                $html .= "<td><button id='open_detail' data-value=" . $a->mn_id . ">상세</button></td>";
                $html .="</tr>";
            }
            $html .= "</tbody>";
            $html .= "</table>";
            $html .= "</div>";
        } else {
            $html = "<div id='not_exists'>";
            $html .= "이력이 없습니다.";
            $html .= "</div>";
        }
        $result["msg"] = $html;

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

    function get_detail(){
        $result = array();
        $id = $this->input->post("id");

        $this->db
            ->select("*")
            ->from("cb_wt_mem_note")
            ->where("mn_id", $id);
        $detail = $this->db->get()->row();
        $html = "";
        $html .= "<div id='detail_txt'>" . str_replace("\n", "<br>", $detail->mn_text) . "</div>";

        $result["msg"] = $html;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

    function get_incentive(){
        $result = array();
        $price = $this->input->post("price");

        $this->db
            ->select("incentive")
            ->from("cb_wt_incentive")
            ->where("s_price <=", $price)
            ->where("e_price >", $price);
        $result['incentive'] = $this->db->get()->row()->incentive;

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

    function get_price(){
        $result = array();
        $mem_id = $this->input->post("mid");

        $this->db
            ->select("*")
            ->from("cb_wt_member_addon")
            ->where("mad_mem_id", $mem_id);
        $result['price'] = $this->db->get()->row();

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }
}
?>
