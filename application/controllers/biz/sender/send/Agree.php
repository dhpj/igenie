<?php
class Agree extends CB_Controller {
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
    
    //개인정보동의 작성하기
	public function index()
    {
        $this->Biz_dhn_model->make_msg_log_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_customer_book($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_user_image_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_user_deposit_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_send_cache_table($this->member->item('mem_userid'));
        
        //echo $_SERVER['REQUEST_URI'] ."<br>";
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();

		$view['add'] = $this->input->get("add");
		if($view['add'] == "") $view['add'] = $this->input->post("add");
		$skin = "agree";
		$add = $view['add'];
		if($add != "") $skin .= $add;

        $view['param']['tmp_code'] = $this->input->post('tmp_code');
        $view['param']['tmp_profile'] = $this->input->post('tmp_profile');
        $view['param']['agreeid'] = $this->input->post('agreeid');

		if($view['param']['tmp_code'] == ""){
			$view['param']['tmp_code'] = "108"; //개인정보동의 2021010501
		}

		if($view['param']['tmp_profile'] == ""){
			$view['param']['tmp_profile'] = "c03b09ba2a86fc2984b940d462265dd4dbddb105"; //프로필키
		}

		if($view['param']['agreeid'] == ""){
			//$view['param']['agreeid'] = date("ymdHis"); //개인정보동의ID
		}

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $tmp_code = $view['param']['tmp_code'];
        $tmp_profile = $view['param']['tmp_profile'];
        
        $view['spf'] = $this->db->query("select * from cb_wt_send_profile_dhn where spf_mem_id=".$this->member->item('mem_id')." and spf_use = 'Y'")->row();
        
        //log_message("ERROR","Coupon : ". $tmp_profile ."/". $tmp_code);
        if($tmp_profile && $tmp_code  ) {
            $view['tpl'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
        }
        
        $view['mem'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        $view['view']['canonical'] = site_url();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        // 2019.01.17. 이수환 고객구분 select box값 조회(NULL값을 공백처리해서 길이가 0초가한 값만)
        //$sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid');
        //$sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid')." where length( ifnull(ab_kind,'') ) > 0 ";
        $sql = "select ab_kind, count(ab_kind) ab_kind_cnt from (select IFNULL(ab_kind, '') as ab_kind, ab_tel from cb_ab_".$this->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8) a group by ab_kind";
        $view['kind'] = $this->db->query($sql)->result();
        $view['nft'] = $this->nft;
        
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
        
        /*
         * 2019.01.24 SSG
         * 관리자 DHN 으로 들어 왔을경우 "특정 기능 및 항목"을 보이게 하기 위해
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

		//고객 전체수
		$sql = "SELECT COUNT(*) AS cnt FROM cb_ab_". $this->member->item('mem_userid') ." WHERE ab_status = '1' AND length(ab_tel) >=8 ";
		//echo $sql ."<br>";
		$view['customer_cnt'] = $this->db->query($sql)->row()->cnt;

		//고객그룹 조회
		$sql = "
		SELECT 
			  cg_id /* 그룹번호 */
			, cg_parent_id /* 부모번호 */
			, cg_level /* 레벨 */
			, cg_gname /* 그룹명 */
			, (CASE WHEN cg_level = 1 THEN lv1_cnt ELSE lv2_cnt END) AS cg_cnt /* 고객수 */
			, rowspan /* 라인수 */
		FROM (
			SELECT
				  a.*
				, (SELECT COUNT(*) 
				FROM cb_ab_". $this->member->item('mem_userid') ." u
				LEFT JOIN cb_ab_". $this->member->item('mem_userid') ."_group b ON u.ab_id = b.aug_ab_id AND u.ab_status = '1' AND length(u.ab_tel) >=8
				WHERE b.aug_group_id IN (
					SELECT cg_id FROM cb_customer_group c WHERE c.cg_parent_id = a.cg_id AND c.cg_use_yn = 'Y'
				)) AS lv1_cnt /* 그룹1차 고객수 */
				, (SELECT COUNT(*) 
				FROM cb_ab_". $this->member->item('mem_userid') ." u
				LEFT JOIN cb_ab_". $this->member->item('mem_userid') ."_group b ON u.ab_id = b.aug_ab_id AND u.ab_status = '1' AND length(u.ab_tel) >=8
				WHERE a.cg_id = b.aug_group_id
				) AS lv2_cnt /* 그룹2차 고객수 */
				, (SELECT COUNT(*) FROM cb_customer_group c WHERE c.cg_parent_id = a.cg_parent_id AND c.cg_use_yn = 'Y') AS rowspan /* 라인수 */
			FROM cb_customer_group a
			WHERE cg_mem_id = '". $this->member->item('mem_id') ."' 
			AND cg_use_yn = 'Y'
		) t
		WHERE (CASE WHEN cg_level = 1 THEN lv1_cnt ELSE lv2_cnt END) > 0 /* 등록된 고객이 있는 경우 */
		ORDER BY cg_sort ASC ";
		//echo $sql ."<br>";
		$view['customer_group'] = $this->db->query($sql)->result();

		//포스고객 전체수 2021-02-02
		$sql = "SELECT COUNT(*) AS cnt FROM cb_tel_pos WHERE ab_mem_id = '". $this->member->item('mem_id') ."' AND length(ab_tel) >=8 ";
		//echo $sql ."<br>";
		$view['pos_user_cnt'] = $this->db->query($sql)->row()->cnt;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        //$link_talk = ($this->nft == 'NFT') ? 'nft_talk' : 'talk';
        //$link_talk = 'coupon';
        
        $layoutconfig = array(
            'path' => 'biz/sender/send',
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

	//개인정보동의 목록
	public function lists()
    {
		$view = array();
        $view['view'] = array();
		$view['add'] = $this->input->get("add");
		if($view['add'] == "") $view['add'] = $this->input->post("add");
		$skin = "agree_lists";
		$add = $view['add'];
		if($add != "") $skin .= $add;

		$view['perpage'] = ($this->input->get("perpage")) ? $this->input->get("perpage") : 20;
        $view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
        
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $where = "1=1 ";
        $where .= "and mst_mem_id = '". $this->member->item("mem_id") ."' "; //회원번호
        $where .= "and mst_agreeid != '' "; //개인정보동의ID
        
        //$view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id", $where, $param);
		$sql = "
		SELECT count(1) as cnt
		from cb_wt_msg_sent a 
		inner join cb_member b on a.mst_mem_id=b.mem_id
		left join cb_wt_send_profile_dhn d on a.mst_profile=d.spf_key
		where ".$where." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;
        
        $sql = "
		select a.*, d.*, b.mem_username
			, (select agi_imgpath from cb_agree_info ai where ai.agi_idx = a.mst_agreeid) AS agi_imgpath /* 메시지확인수 */
			, (select COUNT(*) from cb_agree_data ad where ad.agd_agi_idx = a.mst_agreeid AND agd_state IN ('1', '2') /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */) AS view_cnt /* 메시지확인수 */
			, (select COUNT(*) from cb_agree_data ad where ad.agd_agi_idx = a.mst_agreeid AND agd_state = '2' /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */) AS agree_cnt /* 수신동의수 */
		from cb_wt_msg_sent a 
		inner join cb_member b on a.mst_mem_id=b.mem_id
		left join cb_wt_send_profile_dhn d on a.mst_profile=d.spf_key
		where ".$where." 
		order by (case when a.mst_reserved_dt <> '00000000000000' then a.mst_reserved_dt else DATE_FORMAT(a.mst_datetime, '%Y%m%d%H%i%s')  end) desc, mst_id desc 
		limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
        $view['list'] = $this->db->query($sql, $param)->result();

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);
		$view['page_html'] = $view['page_html'];
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view[page_html] : ".$view['page_html']);

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

	//개인정보동의 상세내용
	public function view($agreeid = 0)
    {
		$view = array();
        $view['view'] = array();
		$view['add'] = $this->input->get("add");
		if($view['add'] == "") $view['add'] = $this->input->post("add");
		$skin = "agree_view";
		$add = $view['add'];
		if($add != "") $skin .= $add;

		$view['perpage'] = ($this->input->get("perpage")) ? $this->input->get("perpage") : 20; //개시물수
		$view['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1; //현재페이지
		$view['agreeid'] = $agreeid; //개인정보동의번호
		$view['search_phn'] = $this->input->get("search_phn"); //연락처 검색
		$view['search_state'] = $this->input->get("search_state"); //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색

		//검색조건
		$where = "WHERE agd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND agd_agi_idx = '". $view['agreeid'] ."' /* 개인정보동의번호 */ ";
		if($view['search_state'] != ""){
			$where .= "AND agd_state = '". $view['search_state'] ."' /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */ ";
		}
		if($view['search_phn'] != ""){
			//$search_phn = preg_replace("/[^0-9]/", "", $view['search_phn']); //숫자 이외 제거
			$search_phn = str_replace("-", "", $view['search_phn']); //- 제거
			$where .= "AND agd_phn LIKE '%". $search_phn ."%' /* 휴대전화번호 */ ";
		}

		//전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_agree_data a
		". $where ." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//리스트
		$sql = "
		SELECT
			  agd_idx /* 일련번호 */
			, agd_phn /* 연락처 */
			, agd_state /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */
			, DATE_FORMAT(agd_cre_date, '%Y-%m-%d %H:%i') AS agd_cre_date /* 발송일자 */
			, DATE_FORMAT(agd_view_date, '%Y-%m-%d %H:%i') AS agd_view_date /* 확인일자 */
			, DATE_FORMAT(agd_agree_date, '%Y-%m-%d %H:%i') AS agd_agree_date /* 수신동의일자 */
		FROM cb_agree_data a
		". $where ."
		ORDER BY agd_idx DESC
		LIMIT ".(($view['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['list'] = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view : ".$view);

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);
		$view['page_html'] = $view['page_html'];
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view[page_html] : ".$view['page_html']);

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

	//개인정보동의 엑셀 다운로드
	public function download()
    {
		$view = array();
        $view['view'] = array();
		$view['agreeid'] = $this->input->get("agreeid"); //개인정보동의번호
		$view['search_phn'] = $this->input->get("search_phn"); //연락처 검색
		$view['search_state'] = $this->input->get("search_state"); //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색

		//검색조건
		$where = "WHERE agd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND agd_agi_idx = '". $view['agreeid'] ."' /* 개인정보동의번호 */ ";
		if($view['search_state'] != ""){
			$where .= "AND agd_state = '". $view['search_state'] ."' /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */ ";
		}
		if($view['search_phn'] != ""){
			//$search_phn = preg_replace("/[^0-9]/", "", $view['search_phn']); //숫자 이외 제거
			$search_phn = str_replace("-", "", $view['search_phn']); //- 제거
			$where .= "AND agd_phn LIKE '%". $search_phn ."%' /* 휴대전화번호 */ ";
		}

		//전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_agree_data a
		". $where ." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//리스트
		$sql = "
		SELECT
			  agd_idx /* 일련번호 */
			, agd_phn /* 연락처 */
			, agd_state /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */
			, DATE_FORMAT(agd_cre_date, '%Y-%m-%d %H:%i') AS agd_cre_date /* 발송일자 */
			, DATE_FORMAT(agd_view_date, '%Y-%m-%d %H:%i') AS agd_view_date /* 확인일자 */
			, DATE_FORMAT(agd_agree_date, '%Y-%m-%d %H:%i') AS agd_agree_date /* 수신동의일자 */
		FROM cb_agree_data a
		". $where ."
		ORDER BY agd_idx DESC ";
		//echo $sql ."<br>";
		log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$list = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view : ".$view);

		// 라이브러리를 로드한다.
        $this->load->library('excel');
        
        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');
        
        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:F1');
        
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A3:F3');
        
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10); //No
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); //전화번호
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); //상태
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); //발송일자
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20); //확인일자
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20); //수신동의일자
        
        $this->excel->getActiveSheet()->mergeCells('A1:F1');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '개인정보동의 상세내역');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'No');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '전화번호');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '상태');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '발송일자');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '확인일자');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, '수신동의일자');
        
        $seq = 0;
		$row = 4;
        foreach($list as $r) {
			$num = $view['total_rows'] - $seq; //No
			$agd_phn = $this->funn->format_phone($r->agd_phn, "-"); //전화번호
			$agd_state = $r->agd_state; //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함)
			$agd_cre_date = $r->agd_cre_date; //발송일자
			$agd_view_date = ""; //확인일자
			$agd_agree_date = ""; //수신동의일자
			if($agd_state == "0"){
				$agd_state = "발송완료";
			}else{
				$agd_view_date = $r->agd_view_date; //확인일자
				if($agd_state == "1"){
					$agd_state = "확인완료";
				}else if($agd_state == "2"){
					$agd_state = "수신동의";
					$agd_agree_date = $r->agd_agree_date; //수신동의일자
				}
			}
			//가운데 정렬
			$this->excel->getActiveSheet()->duplicateStyleArray( array( 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER )),	'A'.$row.':F'.$row); 
			
			// 데이터를 읽어서 순차로 기록한다.
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $num); //No
            //$this->excel->getActiveSheet()->setCellValueExplicit('A'.$row, $num, PHPExcel_Cell_DataType::TYPE_STRING); //문자형으로 변환
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $agd_phn); //전화번호
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $agd_state); //상태
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $agd_cre_date); //발송일자
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $agd_view_date); //확인일자
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $agd_agree_date); //수신동의일자
            $row++;
            $seq++; //순번
        }
        
        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="agree_list.xls"');
        header('Cache-Control: max-age=0');
        
        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
	}

}