<?php
class Test extends CB_Controller {
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
	    // 		$key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
	    // 		if(empty($key)) {
	    // 		    $key = $this->input->post("tmpl_id");
	    // 		}
	    // 이벤트 라이브러리를 로딩합니다
	    $eventname = 'event_main_index';
	    $this->load->event($eventname);
	    
	    $view = array();
	    $view['view'] = array();
	    
	    // 이벤트가 존재하면 실행합니다
	    $view['view']['event']['before'] = Events::trigger('before', $eventname);
	    
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
	    
	    log_message("ERROR", "Test index()");
	    
	    $layoutconfig = array(
	        'path' => 'biz/test',
	        'layout' => 'layout',
	        'skin' => 'index',
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
// 		$key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
// 		if(empty($key)) {
// 		    $key = $this->input->post("tmpl_id");
// 		}
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$query = $this->db->query("SHOW TABLE STATUS FROM dhn WHERE engine='MyISAM' AND (Name LIKE 'cb_sc_%' OR Name LIKE 'cb_msg_%' OR Name LIKE 'cb_ab_%' OR Name LIKE 'cb_img_%' OR Name LIKE 'cb_amt_%')");
		$message = "";
		$i = 0;
		foreach ($query->result() as $row) {
		    $i += 1;
		    $table_name = $row->Name;
		    $engine_name = $row->Engine;
		    
		    //$message .= $i." ".$table_name." ".$engine_name."<br>";
		    
		    $query2 = $this->db->query("ALTER TABLE ".$table_name." ENGINE=InnoDB");
		    
		    if($query2) {
		        $message .= $i." ".$table_name." ".$engine_name."InnoDB_ 변경완료<br>";
		    } else {
		        $message .= "에러";
		        break;        
		    }
		}
//		log_message("ERROR", $message);
// 		$i=0;
// 		while ($row[$i] = $this->db->row("SHOW TABLE STATUS FROM dhn")) {
// 		    $table_name = $row[$i][0];
// 		    $message += $table_name."<br>";
// 		}
	
// 		$query1 = "select T.* from (
//             select a.*, 
//                    (a.mst_ft + a.mst_ft_img + mst_at + mst_sms + mst_lms + mst_mms + mst_phn + mst_015 + 
//                     a.mst_grs + a.mst_nas + a.mst_bkg + a.mst_grs_sms + a.mst_nas_sms) AS s_qty,
//                    (a.mst_err_ft + a.mst_err_ft_img + a.mst_err_at + a.mst_err_sms + a.mst_err_lms + a.mst_err_mms + a.mst_err_phn + a.mst_err_015 + 
//                     a.mst_err_grs + a.mst_err_nas + a.mst_err_bkg + a.mst_err_grs_sms + a.mst_err_nas_sms + a.mst_wait) AS e_qty,
//                     d.*, b.mem_username 
//                         from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
//             (	SELECT distinct a.mem_id
//                         FROM
//                             (SELECT
//                                 GET_PARENT_EQ_PRIOR_ID(mem_id) AS _id, @level AS level
//                             FROM
//                                 (SELECT @start_with:=3, @id:=@start_with) vars, cb_member_register
//                             WHERE
//                                 @id IS NOT NULL) ho
//                                 LEFT JOIN
//                             cb_member_register c ON c.mem_id = ho._id
//                                 INNER JOIN
//                             cb_member a ON (c.mem_id = a.mem_id or a.mem_id = 3 )
//                                 AND a.mem_useyn = 'Y'
//             )  c on a.mst_mem_id = c.mem_id 
//             				left join cb_wt_send_profile d on a.mst_profile=d.spf_key 
//             where 1=1 and a.mst_reserved_dt = '00000000000000' and a.mst_datetime between '2018-12-09 00:00:00' and NOW()
//                   and DATE_ADD(a.mst_datetime, INTERVAL 15 MINUTE) < NOW() and (a.mst_ft + a.mst_ft_img + mst_at + mst_sms + mst_lms + mst_mms + mst_phn + mst_015 + 
//                     a.mst_grs + a.mst_nas + a.mst_bkg + a.mst_grs_sms + a.mst_nas_sms + a.mst_err_ft + a.mst_err_ft_img + a.mst_err_at + a.mst_err_sms + a.mst_err_lms + a.mst_err_mms + a.mst_err_phn + a.mst_err_015 + 
//                     a.mst_err_grs + a.mst_err_nas + a.mst_err_bkg + a.mst_err_grs_sms + a.mst_err_nas_sms + a.mst_wait) < a.mst_qty
//             union all
//             select a.*, 
//                    (a.mst_ft + a.mst_ft_img + mst_at + mst_sms + mst_lms + mst_mms + mst_phn + mst_015 + 
//                     a.mst_grs + a.mst_nas + a.mst_bkg + a.mst_grs_sms + a.mst_nas_sms) AS s_qty,
//                    (a.mst_err_ft + a.mst_err_ft_img + a.mst_err_at + a.mst_err_sms + a.mst_err_lms + a.mst_err_mms + a.mst_err_phn + a.mst_err_015 + 
//                     a.mst_err_grs + a.mst_err_nas + a.mst_err_bkg + a.mst_err_grs_sms + a.mst_err_nas_sms + a.mst_wait) AS e_qty,
//                     d.*, b.mem_username 
//             			from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
//             (	SELECT distinct a.mem_id
//                         FROM
//                             (SELECT
//                                 GET_PARENT_EQ_PRIOR_ID(mem_id) AS _id, @level AS level
//                             FROM
//                                 (SELECT @start_with:=3, @id:=@start_with) vars, cb_member_register
//                             WHERE
//                                 @id IS NOT NULL) ho
//                                 LEFT JOIN
//                             cb_member_register c ON c.mem_id = ho._id
//                                 INNER JOIN
//                             cb_member a ON (c.mem_id = a.mem_id or a.mem_id = 3 )
//                                 AND a.mem_useyn = 'Y'
//             )  c on a.mst_mem_id = c.mem_id 
//             				left join cb_wt_send_profile d on a.mst_profile=d.spf_key 
//                where 1=1 and a.mst_reserved_dt <> '00000000000000' and STR_TO_DATE(a.mst_reserved_dt,'%Y%m%d%H%i%S') between '2018-12-09 00:00:00' and NOW()
//                          and DATE_ADD(STR_TO_DATE(a.mst_reserved_dt,'%Y%m%d%H%i%S'), INTERVAL 15 MINUTE) < NOW() and (a.mst_ft + a.mst_ft_img + mst_at + mst_sms + mst_lms + mst_mms + mst_phn + mst_015 + 
//                     a.mst_grs + a.mst_nas + a.mst_bkg + a.mst_grs_sms + a.mst_nas_sms + a.mst_err_ft + a.mst_err_ft_img + a.mst_err_at + a.mst_err_sms + a.mst_err_lms + a.mst_err_mms + a.mst_err_phn + a.mst_err_015 + 
//                     a.mst_err_grs + a.mst_err_nas + a.mst_err_bkg + a.mst_err_grs_sms + a.mst_err_nas_sms + a.mst_wait) < a.mst_qty
//              ) as T";
// 		$query2 = "select T.* from (
//             select a.*, 
//             	   (CAST(a.mst_qty as SIGNED) - (a.mst_ft + a.mst_ft_img + mst_at + a.mst_err_ft + a.mst_err_ft_img + a.mst_err_at)) as talk_count,
//                    (a.mst_ft + a.mst_ft_img + mst_at + mst_sms + mst_lms + mst_mms + mst_phn + mst_015 + 
//                     a.mst_grs + a.mst_nas + a.mst_bkg + a.mst_grs_sms + a.mst_nas_sms) AS s_qty,
//                    (a.mst_err_ft + a.mst_err_ft_img + a.mst_err_at + a.mst_err_sms + a.mst_err_lms + a.mst_err_mms + a.mst_err_phn + a.mst_err_015 + 
//                     a.mst_err_grs + a.mst_err_nas + a.mst_err_bkg + a.mst_err_grs_sms + a.mst_err_nas_sms + a.mst_wait) AS e_qty,
//                    d.*, b.mem_username 
//                         from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
//             (	SELECT distinct a.mem_id
//                         FROM
//                             (SELECT
//                                 GET_PARENT_EQ_PRIOR_ID(mem_id) AS _id, @level AS level
//                             FROM
//                                 (SELECT @start_with:=3, @id:=@start_with) vars, cb_member_register
//                             WHERE
//                                 @id IS NOT NULL) ho
//                                 LEFT JOIN
//                             cb_member_register c ON c.mem_id = ho._id
//                                 INNER JOIN
//                             cb_member a ON (c.mem_id = a.mem_id or a.mem_id = 3 )
//                                 AND a.mem_useyn = 'Y'
//             )  c on a.mst_mem_id = c.mem_id 
//             				left join cb_wt_send_profile d on a.mst_profile=d.spf_key 
//             where 1=1 and a.mst_reserved_dt = '00000000000000' and a.mst_datetime between DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00') and NOW()
//                   and DATE_ADD(a.mst_datetime, INTERVAL 15 MINUTE) < NOW() and a.mst_wait > (CAST(a.mst_qty as SIGNED) - (a.mst_ft + a.mst_ft_img + mst_at + a.mst_err_ft + a.mst_err_ft_img + a.mst_err_at)) * 0.2
//             union all
//             select a.*, 
//             	   (CAST(a.mst_qty as SIGNED) - (a.mst_ft + a.mst_ft_img + mst_at + a.mst_err_ft + a.mst_err_ft_img + a.mst_err_at)) as talk_count,
//                    (a.mst_ft + a.mst_ft_img + mst_at + mst_sms + mst_lms + mst_mms + mst_phn + mst_015 + 
//                     a.mst_grs + a.mst_nas + a.mst_bkg + a.mst_grs_sms + a.mst_nas_sms) AS s_qty,
//                    (a.mst_err_ft + a.mst_err_ft_img + a.mst_err_at + a.mst_err_sms + a.mst_err_lms + a.mst_err_mms + a.mst_err_phn + a.mst_err_015 + 
//                     a.mst_err_grs + a.mst_err_nas + a.mst_err_bkg + a.mst_err_grs_sms + a.mst_err_nas_sms + a.mst_wait) AS e_qty,
//                    d.*, b.mem_username 
//             			from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id inner join
//             (	SELECT distinct a.mem_id
//                         FROM
//                             (SELECT
//                                 GET_PARENT_EQ_PRIOR_ID(mem_id) AS _id, @level AS level
//                             FROM
//                                 (SELECT @start_with:=3, @id:=@start_with) vars, cb_member_register
//                             WHERE
//                                 @id IS NOT NULL) ho
//                                 LEFT JOIN
//                             cb_member_register c ON c.mem_id = ho._id
//                                 INNER JOIN
//                             cb_member a ON (c.mem_id = a.mem_id or a.mem_id = 3 )
//                                 AND a.mem_useyn = 'Y'
//             )  c on a.mst_mem_id = c.mem_id 
//             				left join cb_wt_send_profile d on a.mst_profile=d.spf_key 
//                where 1=1 and a.mst_reserved_dt <> '00000000000000' and STR_TO_DATE(a.mst_reserved_dt,'%Y%m%d%H%i%S') between DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00') and NOW()
//                          and DATE_ADD(STR_TO_DATE(a.mst_reserved_dt,'%Y%m%d%H%i%S'), INTERVAL 15 MINUTE) < NOW() and a.mst_wait > (CAST(a.mst_qty as SIGNED) - (a.mst_ft + a.mst_ft_img + mst_at + a.mst_err_ft + a.mst_err_ft_img + a.mst_err_at)) * 0.2
//              ) as T";
		
// 		log_message("ERROR", "Test view()");
		
		$view = array();
		$view['view'] = array();
// 		$view['rs1'] = $this->db->query($query1)->result();
// 		$view['rs2'] = $this->db->query($query2)->result();
	
		$view['message'] = $message;
		$this->load->view('biz/test/view',$view);
	}
}
?>