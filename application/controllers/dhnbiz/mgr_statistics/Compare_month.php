<?php
require_once APPPATH.'/third_party/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Compare_month extends CB_Controller {
	/**
	* ���� �ε��մϴ�
	*/
	protected $models = array('Board', 'Biz');

	/**
	* ���۸� �ε��մϴ�
	*/
	protected $helpers = array('form', 'array');

	function __construct()
	{
		parent::__construct();

		/**
		* ���̺귯���� �ε��մϴ�
		*/
		$this->load->library(array('querystring'));
	}

	public function index()
	{
		// �̺�Ʈ ���̺귯���� �ε��մϴ�
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// �̺�Ʈ�� �����ϸ� �����մϴ�
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['param']['endDate'] = ($this->input->get('endDate')) ? $this->input->get('endDate') : date('Y-m', strtotime(date('Y-m-d')."-1month"));
		$endDate = $view['param']['endDate']."-01";
		
		$view['param']['startDate'] = date('Y-m', strtotime($endDate."-1month"));
		$view['param']['order_by_name'] = ($this->input->get('order_by_name')) ? $this->input->get('order_by_name') : 'mst_total_sub';
		$view['param']['order_by'] = ($this->input->get('order_by')) ? $this->input->get('order_by') : 'ASC';
		// 		$view['param']['startDate'] = date('Y-m', strtotime(date('Y-m-d')."-11month"));
// 		$view['param']['endDate'] = date('Y-m');
		$view['param']['group_id'] = $this->input->get('group_id');
//		$view['param']['pf_key'] = $this->input->get('pf_key');
		
		log_message("ERROR", "this->input->get('group_id') : ".$this->input->get('group_id'));
		log_message("ERROR", "view['param']['group_id'] : ".$view['param']['group_id']);
		if (empty($view['param']['group_id'])) {
		    $view['param']['group_id'] = 3;
		}
		
		$startDateYM = str_replace('-', '', $view['param']['startDate']);
		$endDateYM = str_replace('-', '', $view['param']['endDate']);
		
		$view['param']['preDate'] = $view['param']['startDate'];
		$view['param']['curDate'] = $view['param']['endDate'];
		
		//$view['company'] = $this->Biz_model->get_member_from_profile_key($view['param']['pf_key']);
		//$view['profile'] = $this->Biz_model->get_partner_profile();
		$sql = "
            select * from (
            SELECT mem_id, mem_userid, mem_username, mem_level,
            	(select count(0) cnt from cb_member_register c inner join cb_member d on c.mem_id = d.mem_id where c.mrg_recommend_mem_id = a.mem_id) as cnt
            FROM cb_member a 
            WHERE mem_level >= 10 and mem_useyn = 'Y' ) b
            where b.cnt > 0
            order by b.mem_username
            ";
		log_message("ERROR", "sql : ".$sql);
		$view['group'] = $this->db->query($sql)->result();
		
		$sql = "
            SELECT *        
            FROM (
            	SELECT cmo.mem_id, cmo.mem_username, cmo.mem_sales_name, cmc.parent_id, 
                    CASE WHEN (cmo.mem_level = 1) THEN '마트(업체)' ELSE '중간관리자' END AS mem_level,
            		(SELECT i_cm.mem_username from cb_member AS i_cm WHERE i_cm.mem_id = cmc.parent_id) AS parent_name,
            		IFNULL(p_s_cwms.p_mst_total, 0) AS p_mst_total,
            		IFNULL(n_s_cwms.n_mst_total, 0) AS n_mst_total, 
            		IFNULL(n_s_cwms.n_mst_total, 0) - IFNULL(p_s_cwms.p_mst_total, 0) AS mst_total_sub,
            		IFNULL(p_s_cwms.p_mst_at, 0) AS p_mst_at, 
            		IFNULL(n_s_cwms.n_mst_at, 0) AS n_mst_at, 
            		IFNULL(n_s_cwms.n_mst_at, 0) - IFNULL(p_s_cwms.p_mst_at, 0) AS mst_at_sub, 
            		IFNULL(p_s_cwms.p_mst_ft, 0) AS p_mst_ft, 
            		IFNULL(n_s_cwms.n_mst_ft, 0) AS n_mst_ft, 
            		IFNULL(n_s_cwms.n_mst_ft, 0) - IFNULL(p_s_cwms.p_mst_ft, 0) AS mst_ft_sub,
            		IFNULL(p_s_cwms.p_mst_ms, 0) AS p_mst_ms,
            		IFNULL(n_s_cwms.n_mst_ms, 0) AS n_mst_ms, 
            		IFNULL(n_s_cwms.n_mst_ms, 0) - IFNULL(p_s_cwms.p_mst_ms, 0) AS mst_ms_sub,
            		IFNULL(p_s_cwms.p_mst_wait, 0) AS p_mst_wait,
            		IFNULL(n_s_cwms.n_mst_wait, 0) AS n_mst_wait
            	FROM cb_member AS cmo 
            		INNER JOIN (
            			WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            					JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				WHERE cm.mem_id = ".$view['param']['group_id']." and cmr.mem_id <> cm.mem_id
            				UNION ALL
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            					JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            					JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            			)
            			SELECT distinct mem_id, parent_id
            			FROM cmem
            		) AS cmc ON cmo.mem_id = cmc.mem_id and cmo.mem_level<=10
            		LEFT JOIN (
            			SELECT p_cwms.mst_mem_id AS p_mem_id,
            			    SUM(p_cwms.mst_at + p_cwms.mst_ft + p_cwms.mst_ft_img + p_cwms.mst_grs + p_cwms.mst_grs_sms + p_cwms.mst_grs_mms + p_cwms.mst_nas + p_cwms.mst_nas_sms + p_cwms.mst_nas_mms + p_cwms.mst_smt + p_cwms.mst_wait) AS p_mst_total, 
            				SUM(p_cwms.mst_at) AS p_mst_at, 
            				SUM(p_cwms.mst_ft + p_cwms.mst_ft_img) AS p_mst_ft, 
            				SUM(p_cwms.mst_grs + p_cwms.mst_grs_sms + p_cwms.mst_grs_mms + p_cwms.mst_nas + p_cwms.mst_nas_sms + p_cwms.mst_nas_mms + p_cwms.mst_smt + p_cwms.mst_wait) AS p_mst_ms,
            				SUM(p_cwms.mst_wait) AS p_mst_wait
            			FROM cb_wt_msg_sent AS p_cwms
            			WHERE ((p_cwms.mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(p_cwms.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$startDateYM."01000000' AND '".$startDateYM."31235959')) 
            					OR
            					(p_cwms.mst_reserved_dt = '00000000000000' AND (p_cwms.mst_datetime BETWEEN '".$startDateYM."01000000' AND '".$startDateYM."31235959')))
            			GROUP BY p_cwms.mst_mem_id	
            		) AS p_s_cwms ON cmo.mem_id = p_s_cwms.p_mem_id
            		LEFT JOIN (
            			SELECT n_cwms.mst_mem_id AS n_mem_id, 
            				SUM(n_cwms.mst_at + n_cwms.mst_ft + n_cwms.mst_ft_img + n_cwms.mst_grs + n_cwms.mst_grs_sms + n_cwms.mst_grs_mms + n_cwms.mst_nas + n_cwms.mst_nas_sms + n_cwms.mst_nas_mms + n_cwms.mst_smt + n_cwms.mst_wait) AS n_mst_total,
            				SUM(n_cwms.mst_at) AS n_mst_at, 
            				SUM(n_cwms.mst_ft + n_cwms.mst_ft_img) AS n_mst_ft, 
            				SUM(n_cwms.mst_grs + n_cwms.mst_grs_sms + n_cwms.mst_grs_mms + n_cwms.mst_nas + n_cwms.mst_nas_sms + n_cwms.mst_nas_mms + n_cwms.mst_smt + n_cwms.mst_wait) AS n_mst_ms,
            				SUM(n_cwms.mst_wait) AS n_mst_wait 
            			FROM cb_wt_msg_sent n_cwms
            			WHERE ((n_cwms.mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(n_cwms.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$endDateYM."01000000' AND '".$endDateYM."31235959')) 
            					OR
            					(n_cwms.mst_reserved_dt = '00000000000000' AND (n_cwms.mst_datetime BETWEEN '".$endDateYM."01000000' AND '".$endDateYM."31235959')))
            			GROUP BY n_cwms.mst_mem_id	
            		) AS n_s_cwms ON cmo.mem_id = n_s_cwms.n_mem_id
            ) v_cwms                    
            ORDER BY v_cwms.".$view['param']['order_by_name']."  ".$view['param']['order_by'].", v_cwms.mem_username ASC       
        ";
		
		log_message("ERROR", "sql : ".$sql);
		$view['list'] = $this->db->query($sql)->result();
		//$view['list'] = $this->Biz_model->get_send_report('month', $view['param']['startDate'], $view['param']['endDate'], $view['param']['pf_key']);
		
// 		$sql = "select count(1) cnt from cb_service_history where sh_mem_id = ".$view['param']['pf_key']." and sh_type='H'";
// 		$view['memo_total'] = $this->db->query($sql)->row()->cnt;
		
		$view['view']['canonical'] = site_url();

		// �̺�Ʈ�� �����ϸ� �����մϴ�
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		* ���̾ƿ��� �����մϴ�
		*/
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'biz/mgr_statistics',
			'layout' => 'layout',
			'skin' => 'compare_month',
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

	public function download_compare_month() {
	    $value = json_decode($this->input->post('value'));
	    //log_message("ERROR", "---------------");
	    //log_message("ERROR", "mem_id : ".$value->mem_id);
	    //log_message("ERROR", "period_name : ".$value->period_name);
	    //log_message("ERROR", "---------------");
	    
	    $value_endDate = ($value->endDate) ? $value->endDate : date('Y-m', strtotime(date('Y-m-d')."-1month"));
	    $endDate = $value_endDate."-01";
	    
	    $value_startDate = date('Y-m', strtotime($endDate."-1month"));

	    $title_startDate = str_replace('-', '년 ', $value_startDate)."월";
	    $title_endDate = str_replace('-', '년 ', $value_endDate)."월";
	    
	    $value_startDate = str_replace('-', '', $value_startDate);
	    $value_endDate = str_replace('-', '', $value_endDate);
	    
	    
	    $sql = "
            select mem_username
            from cb_member 
            where mem_id=".$value->group_id."
        ";
	    //log_message("ERROR", "sql : ".$sql);
	    $group_username = $this->db->query($sql)->row()->mem_username;

	    $sql = "
            SELECT *
            FROM (
            	SELECT cmo.mem_id, cmo.mem_username, cmo.mem_sales_name, cmc.parent_id,
                    CASE WHEN (cmo.mem_level = 1) THEN '마트(업체)' ELSE '중간관리자' END AS mem_level,
            		(SELECT i_cm.mem_username from cb_member AS i_cm WHERE i_cm.mem_id = cmc.parent_id) AS parent_name,
            		IFNULL(p_s_cwms.p_mst_total, 0) AS p_mst_total,
            		IFNULL(n_s_cwms.n_mst_total, 0) AS n_mst_total,
            		IFNULL(n_s_cwms.n_mst_total, 0) - IFNULL(p_s_cwms.p_mst_total, 0) AS mst_total_sub,
            		IFNULL(p_s_cwms.p_mst_at, 0) AS p_mst_at,
            		IFNULL(n_s_cwms.n_mst_at, 0) AS n_mst_at,
            		IFNULL(n_s_cwms.n_mst_at, 0) - IFNULL(p_s_cwms.p_mst_at, 0) AS mst_at_sub,
            		IFNULL(p_s_cwms.p_mst_ft, 0) AS p_mst_ft,
            		IFNULL(n_s_cwms.n_mst_ft, 0) AS n_mst_ft,
            		IFNULL(n_s_cwms.n_mst_ft, 0) - IFNULL(p_s_cwms.p_mst_ft, 0) AS mst_ft_sub,
            		IFNULL(p_s_cwms.p_mst_ms, 0) AS p_mst_ms,
            		IFNULL(n_s_cwms.n_mst_ms, 0) AS n_mst_ms,
            		IFNULL(n_s_cwms.n_mst_ms, 0) - IFNULL(p_s_cwms.p_mst_ms, 0) AS mst_ms_sub,
            		IFNULL(p_s_cwms.p_mst_wait, 0) AS p_mst_wait,
            		IFNULL(n_s_cwms.n_mst_wait, 0) AS n_mst_wait
            	FROM cb_member AS cmo
            		INNER JOIN (
            			WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            					JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				WHERE cm.mem_id = ".$value->group_id." and cmr.mem_id <> cm.mem_id
            				UNION ALL
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            					JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            					JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            			)
            			SELECT distinct mem_id, parent_id
            			FROM cmem
            		) AS cmc ON cmo.mem_id = cmc.mem_id and cmo.mem_level<=10
            		LEFT JOIN (
            			SELECT p_cwms.mst_mem_id AS p_mem_id,
            			    SUM(p_cwms.mst_at + p_cwms.mst_ft + p_cwms.mst_ft_img + p_cwms.mst_grs + p_cwms.mst_grs_sms + p_cwms.mst_grs_mms + p_cwms.mst_nas + p_cwms.mst_nas_sms + p_cwms.mst_nas_mms + p_cwms.mst_smt + p_cwms.mst_wait) AS p_mst_total,
            				SUM(p_cwms.mst_at) AS p_mst_at,
            				SUM(p_cwms.mst_ft + p_cwms.mst_ft_img) AS p_mst_ft,
            				SUM(p_cwms.mst_grs + p_cwms.mst_grs_sms + p_cwms.mst_grs_mms + p_cwms.mst_nas + p_cwms.mst_nas_sms + p_cwms.mst_nas_mms + p_cwms.mst_smt + p_cwms.mst_wait) AS p_mst_ms,
            				SUM(p_cwms.mst_wait) AS p_mst_wait
            			FROM cb_wt_msg_sent AS p_cwms
            			WHERE ((p_cwms.mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(p_cwms.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$value_startDate."01000000' AND '".$value_startDate."31235959'))
            					OR
            					(p_cwms.mst_reserved_dt = '00000000000000' AND (p_cwms.mst_datetime BETWEEN '".$value_startDate."01000000' AND '".$value_startDate."31235959')))
            			GROUP BY p_cwms.mst_mem_id
            		) AS p_s_cwms ON cmo.mem_id = p_s_cwms.p_mem_id
            		LEFT JOIN (
            			SELECT n_cwms.mst_mem_id AS n_mem_id,
            				SUM(n_cwms.mst_at + n_cwms.mst_ft + n_cwms.mst_ft_img + n_cwms.mst_grs + n_cwms.mst_grs_sms + n_cwms.mst_grs_mms + n_cwms.mst_nas + n_cwms.mst_nas_sms + n_cwms.mst_nas_mms + n_cwms.mst_smt + n_cwms.mst_wait) AS n_mst_total,
            				SUM(n_cwms.mst_at) AS n_mst_at,
            				SUM(n_cwms.mst_ft + n_cwms.mst_ft_img) AS n_mst_ft,
            				SUM(n_cwms.mst_grs + n_cwms.mst_grs_sms + n_cwms.mst_grs_mms + n_cwms.mst_nas + n_cwms.mst_nas_sms + n_cwms.mst_nas_mms + n_cwms.mst_smt + n_cwms.mst_wait) AS n_mst_ms,
            				SUM(n_cwms.mst_wait) AS n_mst_wait
            			FROM cb_wt_msg_sent n_cwms
            			WHERE ((n_cwms.mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(n_cwms.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$value_endDate."01000000' AND '".$value_endDate."31235959'))
            					OR
            					(n_cwms.mst_reserved_dt = '00000000000000' AND (n_cwms.mst_datetime BETWEEN '".$value_endDate."01000000' AND '".$value_endDate."31235959')))
            			GROUP BY n_cwms.mst_mem_id
            		) AS n_s_cwms ON cmo.mem_id = n_s_cwms.n_mem_id
            ) v_cwms
            ORDER BY v_cwms.".$value->order_by_name."  ".$value->order_by.", v_cwms.mem_username ASC
        ";
	    
	    log_message("ERROR", "sql : ".$sql);
	    
	    $result_query = $this->db->query($sql)->result();
	    
	    $next_row_count = 0;
	    $row_index = 0;
	    
	    if ($result_query) {
	        $spreadsheet = new Spreadsheet();
	        $cur_worksheet = $spreadsheet->getActiveSheet();
	        $cells = array(
	            '', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
	        );
	        
	        $cur_worksheet->getColumnDimension('A')->setWidth(1.38 + 0.63);
	        $cur_worksheet->getColumnDimension('B')->setWidth(56.00 + 0.63);
	        $cur_worksheet->getColumnDimension('C')->setWidth(30.00 + 0.63);
	        $cur_worksheet->getColumnDimension('D')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('E')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('F')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('G')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('H')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('I')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('J')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('K')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('L')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('M')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('N')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('O')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('P')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('Q')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('R')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('S')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('T')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('U')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('V')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('W')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('X')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('Y')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('Z')->setWidth(8.43 + 0.63);

	        $next_row_count += 1;
	        
	        $cur_worksheet->setTitle("전월 발송 성공량 비교(".$group_username.")");
	        //$view_col_index = $sel_col_count + 5;
	        $title = "전월 발송 성공량 비교(".$group_username." ; ".$title_startDate." : ".$title_endDate.")";
	        $cur_worksheet->mergeCells('B'.$next_row_count.':'.$cells[12].$next_row_count)->setCellValue('B'.$next_row_count, $title);
	        $cur_worksheet->getStyle('B'.$next_row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	        $cur_worksheet->getStyle('B'.$next_row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	        $cur_worksheet->getStyle('B'.$next_row_count)->getFont()->setSize(15);
	        $cur_worksheet->getRowDimension($next_row_count)->setRowHeight(25);
	        $cur_worksheet->getStyle('B'.$next_row_count)->getFont()->setBold(true);
	        
	        $next_row_count += 2;
	        $start_table_row = $next_row_count;
	        // Table Header 부분 처리
	        $cur_worksheet->setCellValue('B'.$next_row_count, "업체명");
	        $cur_worksheet->setCellValue('C'.$next_row_count, "담당자");
	        $cur_worksheet->mergeCells('D'.$next_row_count.':'.$cells[6].$next_row_count)->setCellValue('D'.$next_row_count, "발송전체성공");
	        $cur_worksheet->mergeCells('G'.$next_row_count.':'.$cells[9].$next_row_count)->setCellValue('G'.$next_row_count, "알림톡성공");
	        $cur_worksheet->mergeCells('J'.$next_row_count.':'.$cells[12].$next_row_count)->setCellValue('J'.$next_row_count, "친구톡성공");
	        $cur_worksheet->mergeCells('M'.$next_row_count.':'.$cells[15].$next_row_count)->setCellValue('M'.$next_row_count, "2차 문자 성공");
	        $cur_worksheet->getStyle("B".$next_row_count.":".$cells[15].($next_row_count + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	        $cur_worksheet->getStyle("B".$next_row_count.":".$cells[15].($next_row_count + 1))->getFill()->getStartColor()->setARGB('00ACB9CA');
	        $cur_worksheet->getStyle("D".$next_row_count.":".$cells[6].($next_row_count))->getFill()->getStartColor()->setARGB('00E4F7BA');
	        $cur_worksheet->getStyle("G".$next_row_count.":".$cells[9].($next_row_count))->getFill()->getStartColor()->setARGB('00FAE0D4');
	        $cur_worksheet->getStyle("J".$next_row_count.":".$cells[12].($next_row_count))->getFill()->getStartColor()->setARGB('00FAE0D4');
	        $cur_worksheet->getStyle("M".$next_row_count.":".$cells[15].($next_row_count))->getFill()->getStartColor()->setARGB('00FAECC5');
	        
	        
	        $next_row_count += 1;
	        $cur_worksheet->setCellValue($cells[4].$next_row_count, $title_startDate);
	        $cur_worksheet->setCellValue($cells[5].$next_row_count, $title_endDate);
	        $cur_worksheet->setCellValue($cells[6].$next_row_count, "차");
	        $cur_worksheet->setCellValue($cells[7].$next_row_count, $title_startDate);
	        $cur_worksheet->setCellValue($cells[8].$next_row_count, $title_endDate);
	        $cur_worksheet->setCellValue($cells[9].$next_row_count, "차");
	        $cur_worksheet->setCellValue($cells[10].$next_row_count, $title_startDate);
	        $cur_worksheet->setCellValue($cells[11].$next_row_count, $title_endDate);
	        $cur_worksheet->setCellValue($cells[12].$next_row_count, "차");
	        $cur_worksheet->setCellValue($cells[13].$next_row_count, $title_startDate);
	        $cur_worksheet->setCellValue($cells[14].$next_row_count, $title_endDate);
	        $cur_worksheet->setCellValue($cells[15].$next_row_count, "차");
	        
	        $cur_worksheet->mergeCells('B'.($next_row_count - 1).':B'.$next_row_count);
	        $cur_worksheet->mergeCells('C'.($next_row_count - 1).':C'.$next_row_count);
	        //$cur_worksheet->mergeCells('D'.($next_row_count - 1).':D'.$next_row_count);
	        //$cur_worksheet->mergeCells('E'.($next_row_count - 1).':E'.$next_row_count);
	        //$cur_worksheet->mergeCells('F'.($next_row_count - 1).':F'.$next_row_count);
	        //$cur_worksheet->mergeCells('L'.($next_row_count - 1).':L'.$next_row_count);
	        
	        $cur_worksheet->getStyle('B'.($next_row_count - 1).':'.$cells[15].$next_row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	        $cur_worksheet->getStyle('B'.($next_row_count - 1).':'.$cells[15].$next_row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	        
	        $next_row_count += 1;
	        
	        // 리스트 부분 처리
	        $list_start_row = $next_row_count;
	        
	        foreach ($result_query as $r) {
	            $cur_worksheet->setCellValue('B'.$next_row_count, $r->mem_username);
	            $cur_worksheet->getStyle('B'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            if ($r->mem_sales_name) {
    	            $cur_worksheet->setCellValue('C'.$next_row_count, $r->mem_username);
    	            $cur_worksheet->getStyle('C'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            } else {
	                $cur_worksheet->setCellValue('C'.$next_row_count, $r->parent_name);
	                $cur_worksheet->getStyle('C'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            }
	            $cur_worksheet->setCellValue('D'.$next_row_count, $r->p_mst_total);
	            $cur_worksheet->getStyle('D'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('E'.$next_row_count, $r->n_mst_total);
	            $cur_worksheet->getStyle('E'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('F'.$next_row_count, $r->mst_total_sub);
	            $cur_worksheet->getStyle('F'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('G'.$next_row_count, $r->p_mst_at);
	            $cur_worksheet->getStyle('G'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('H'.$next_row_count, $r->n_mst_at);
	            $cur_worksheet->getStyle('H'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('I'.$next_row_count, $r->mst_at_sub);
	            $cur_worksheet->getStyle('I'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('J'.$next_row_count, $r->p_mst_ft);
	            $cur_worksheet->getStyle('J'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('K'.$next_row_count, $r->n_mst_ft);
	            $cur_worksheet->getStyle('K'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('L'.$next_row_count, $r->mst_ft_sub);
	            $cur_worksheet->getStyle('L'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('M'.$next_row_count, $r->p_mst_ms);
	            $cur_worksheet->getStyle('M'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('N'.$next_row_count, $r->n_mst_ms);
	            $cur_worksheet->getStyle('N'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('O'.$next_row_count, $r->mst_ms_sub);
	            $cur_worksheet->getStyle('O'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            
	            $next_row_count += 1;
	        }
	        
	        $cur_worksheet->getStyle('B'.$list_start_row.':B'.($next_row_count - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	        $cur_worksheet->getStyle('B'.$list_start_row.':B'.($next_row_count - 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	        $cur_worksheet->getStyle('C'.$list_start_row.':'.$cells[15].($next_row_count - 1))->getNumberFormat()->setFormatCode("#,##0_-");
	        //$list_start_row
	        
	        $cur_worksheet->getStyle('B'.$start_table_row.':'.$cells[15].($next_row_count - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	        
	        $cur_worksheet->getStyle('A1');
	        
	    }
	    
	    $spreadsheet->setActiveSheetIndex(0);
	    $filename = "전월 발송 성공량 비교(".$group_username.")";
	    
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
	    
	    // $writer = new Xlsx($spreadsheet);
	    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
	    $writer->save('php://output');
	}
	
	
	
	
}
?>