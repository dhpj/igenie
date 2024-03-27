<?php
require_once APPPATH.'/third_party/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Customer_status extends CB_Controller {
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

		$view['param']['startDate'] = ($this->input->get('startDate')) ? $this->input->get('startDate') : date('Y-m-d', strtotime(date('Y-m-d')."-2week"));
		$view['param']['endDate'] = ($this->input->get('endDate')) ? $this->input->get('endDate') : date('Y-m-d');
		$view['param']['order_by_name'] = ($this->input->get('order_by_name')) ? $this->input->get('order_by_name') : 'mst_qty';
		$view['param']['order_by'] = ($this->input->get('order_by')) ? $this->input->get('order_by') : 'DESC';
		// 		$view['param']['startDate'] = date('Y-m', strtotime(date('Y-m-d')."-11month"));
// 		$view['param']['endDate'] = date('Y-m');
		$view['param']['group_id'] = $this->input->get('group_id');
//		$view['param']['pf_key'] = $this->input->get('pf_key');
		
		log_message("ERROR", "this->input->get('group_id') : ".$this->input->get('group_id'));
		log_message("ERROR", "view['param']['group_id'] : ".$view['param']['group_id']);
		if (empty($view['param']['group_id'])) {
		    $view['param']['group_id'] = 3;
		}
		
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
		//$view['profile'] = $this->Biz_model->get_partners($this->member->item("mem_id"));
		//$view['profile'] = $this->Biz_model->get_Children($view['param']['group_id']);
		
		//if (empty($view['param']['pf_key']) || $this->input->get('groupChange') == "Y") {
		//    $temp = $view['profile'][0];
		//    $firstMem_id = $temp->mem_id;
		//    $view['param']['pf_key'] = $firstMem_id;
		    //log_message("ERROR", "firstMem_id : ".$firstMem_id);
		//} 
		
		//log_message("ERROR", "view['param']['pf_key'] : ".$view['param']['pf_key']);
		
// 		$sql = "
//             select a.mem_id, a.mem_nickname, a.mem_phone, a.mem_emp_phone, a.mem_sales_name, DATE_FORMAT(a.mem_register_datetime, '%Y-%m-%d') as mem_register_datetime, 
//                 a.mem_full_care_type, a.mem_pf_cnt, a.mem_sales_name, a.mem_userid, b.mrg_recommend_mem_id as parent_id
//             from cb_member a left join cb_member_register b on a.mem_id = b.mem_id 
//             where a.mem_id = ".$view['param']['pf_key'];
// 		log_message("ERROR", "sql : ".$sql);
// 		$view['memberinfo'] = $this->db->query($sql)->row();
		
// 		$temp = $view['memberinfo'];
// 		$memUserId = $temp->mem_userid;
// 		$view['register_dt'] = $temp->mem_register_datetime;
// 		$memSalesName = $temp->mem_sales_name;
// 		$parentId = $temp->parent_id;
		
// 		if (empty($memSalesName)) {
// 		    $sql = "            
//                 select a.mem_id, a.mem_nickname, a.mem_userid
//                 from cb_member a
//                 where a.mem_id = ".$parentId;
		    
// 		    //log_message("ERROR", "sql : ".$sql);
// 		    $memSalesName = $this->db->query($sql)->row()->mem_nickname;
// 		}
		
// 		$view['mem_sales_name'] = $memSalesName;
// 		log_message("ERROR", "memSalesName : ".$memSalesName);
		
// 		$sql = "
//             select count(*) ftcnt 
//             from cb_ab_".$memUserId." 
//             where ab_status='1' and length(ab_tel) >=8 and exists ( select 1 from cb_friend_list b where b.mem_id = ".$view['param']['pf_key']." and b.phn = ab_tel)    
//         ";
// 		//log_message("ERROR", "sql : ".$sql);
// 		$view['ftCount'] = $this->db->query($sql)->row()->ftcnt;
		
		$sql = "
            SELECT *
            FROM (
                SELECT a.mem_id, a.mem_username, 
                	CASE WHEN (a.mem_level = 1) THEN '마트(업체)' ELSE '중간관리자' END AS mem_level,
                	IFNULL(c.mst_qty, 0) mst_qty,
                	IFNULL(c.mst_at, 0) mst_at,
                	IFNULL(c.mst_ft, 0) mst_ft,
                	IFNULL(c.mst_ft_img, 0) mst_ft_img,
                	IFNULL(c.mst_ft_w_img, 0) mst_ft_w_img,
                	(IFNULL(c.mst_ft, 0) + IFNULL(c.mst_ft_img, 0) + IFNULL(c.mst_ft_w_img, 0)) as mst_ft_total, 
                	IFNULL(c.mst_2nd, 0) mst_2nd,
                    IFNULL(c.mst_err, 0) mst_err,
                	ROUND(((IFNULL(c.mst_at, 0) + IFNULL(c.mst_ft, 0) + IFNULL(c.mst_ft_img, 0) + IFNULL(c.mst_ft_w_img, 0) + IFNULL(c.mst_2nd, 0)) / IFNULL(c.mst_qty, 100)) * 100) as succ_rate
                FROM cb_member a 
                	INNER JOIN (
                		WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                			SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username  
                			FROM cb_member_register cmr
                				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                			WHERE cm.mem_id = ".$view['param']['group_id']." AND cmr.mem_id <> ".$view['param']['group_id']."
                			UNION ALL
                			SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                			FROM cb_member_register cmr 
                				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                		)
                		SELECT DISTINCT mem_id
                		FROM cmem
                	) b ON a.mem_id = b.mem_id and a.mem_useyn = 'Y'
                	LEFT JOIN (
                		SELECT ca.mst_mem_id,
                			SUM(ca.mst_qty) as mst_qty,
                			SUM(ca.mst_at) as mst_at,
                			SUM(ca.mst_ft) as mst_ft,
                			SUM(ca.mst_ft_img) as mst_ft_img,
                			SUM(ca.mst_ft_w_img) as mst_ft_w_img,
                			SUM(ca.mst_2nd) as mst_2nd,
    			             SUM(ca.mst_err) as mst_err
                		FROM (
                			SELECT caa.mst_mem_id,
                                caa.mst_qty, caa.mst_at, caa.mst_ft, 
                                CASE WHEN caa.mst_type1 = 'fti' THEN caa.mst_ft_img ELSE 0 END AS mst_ft_img,
                                CASE WHEN caa.mst_type1 = 'ftw' THEN caa.mst_ft_img ELSE 0 END AS mst_ft_w_img,
                                (caa.mst_grs + caa.mst_grs_sms + caa.mst_nas + caa.mst_nas_sms + caa.mst_smt) AS mst_2nd,
                                (caa.mst_err_ft + caa.mst_err_ft_img + caa.mst_err_at + caa.mst_err_grs + caa.mst_err_nas + caa.mst_err_grs_sms + caa.mst_err_nas_sms + caa.mst_err_smt) as mst_err
                			FROM cb_wt_msg_sent caa
                			WHERE (CASE WHEN caa.mst_reserved_dt = '00000000000000' THEN caa.mst_datetime BETWEEN '".$view['param']['startDate']." 00:00:00' AND '".$view['param']['endDate']." 23:59:59' ELSE STR_TO_DATE(caa.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$view['param']['startDate']." 00:00:00' AND '".$view['param']['endDate']." 23:59:59' END )
                		) ca
                		GROUP BY ca.mst_mem_id
                	) c on a.mem_id = c.mst_mem_id
            ) d
            ORDER BY d.".$view['param']['order_by_name']." ".$view['param']['order_by'].", d.mem_username ASC
        ";
		
// 		$sql = "
//             select b.yyyymm,
//                 IFNULL(c.mst_qty, 0) mst_qty,
//                 IFNULL(c.mst_at, 0) mst_at,
//                 IFNULL(c.mst_ft, 0) mst_ft,
// 	            IFNULL(c.mst_ft_img, 0) mst_ft_img,
// 	            IFNULL(c.mst_ft_w_img, 0) mst_ft_w_img,
// 	           (IFNULL(c.mst_ft, 0) + IFNULL(c.mst_ft_img, 0) + IFNULL(c.mst_ft_w_img, 0)) as mst_ft_total, 
// 	           IFNULL(c.mst_2nd, 0) mst_2nd
//             FROM (SELECT DISTINCT substr(DATE_FORMAT(DATE_ADD('20181101', INTERVAL seq-1 DAY), '%Y-%m'), 1, 7) as yyyymm
//                 FROM seq_1_to_365) b 
//                     LEFT JOIN (
//                         SELECT d.yyyymm,
//                             sum(d.mst_qty) as mst_qty,
//                             sum(d.mst_at) as mst_at,
//                             sum(d.mst_ft) as mst_ft,
//                             sum(d.mst_ft_img) as mst_ft_img,
//                             sum(d.mst_ft_w_img) as mst_ft_w_img,
//                             sum(d.mst_2nd) as mst_2nd
//                         FROM (
//                             SELECT substr((case when a.mst_reserved_dt <> '00000000000000' then STR_TO_DATE(a.mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 7) as yyyymm,
//                                 a.mst_qty, a.mst_at, a.mst_ft, 
//                                 case when mst_type1 = 'fti' then a.mst_ft_img else 0 end as mst_ft_img,
//                                 case WHEN mst_type1 = 'ftw' then a.mst_ft_img else 0 end as mst_ft_w_img,
//                                 (a.mst_grs + a.mst_grs_sms + a.mst_nas + a.mst_nas_sms + a.mst_smt) as mst_2nd
//                             from cb_wt_msg_sent a
//                             where (CASE WHEN a.mst_reserved_dt = '00000000000000' THEN a.mst_datetime BETWEEN '".$view['param']['startDate']." 00:00:00' AND '".$view['param']['endDate']." 23:59:59' ELSE STR_TO_DATE(a.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$view['param']['startDate']." 00:00:00' AND '".$view['param']['endDate']." 23:59:59' END )
//                         ) d
//                         GROUP by d.yyyymm
//             ) c on b.yyyymm = c.yyyymm";
		
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
			'skin' => 'customer_status',
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

	public function download_customer_status() {
	    $value = json_decode($this->input->post('value'));
	    //log_message("ERROR", "---------------");
	    //log_message("ERROR", "mem_id : ".$value->mem_id);
	    //log_message("ERROR", "period_name : ".$value->period_name);
	    //log_message("ERROR", "---------------");
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
                SELECT a.mem_id, a.mem_username, 
                	CASE WHEN (a.mem_level = 1) THEN '마트(업체)' ELSE '중간관리자' END AS mem_level,
                	IFNULL(c.mst_qty, 0) mst_qty,
                	IFNULL(c.mst_at, 0) mst_at,
                	IFNULL(c.mst_ft, 0) mst_ft,
                	IFNULL(c.mst_ft_img, 0) mst_ft_img,
                	IFNULL(c.mst_ft_w_img, 0) mst_ft_w_img,
                	(IFNULL(c.mst_ft, 0) + IFNULL(c.mst_ft_img, 0) + IFNULL(c.mst_ft_w_img, 0)) as mst_ft_total, 
                	IFNULL(c.mst_2nd, 0) mst_2nd,
                    IFNULL(c.mst_err, 0) mst_err,
                	ROUND(((IFNULL(c.mst_at, 0) + IFNULL(c.mst_ft, 0) + IFNULL(c.mst_ft_img, 0) + IFNULL(c.mst_ft_w_img, 0) + IFNULL(c.mst_2nd, 0)) / IFNULL(c.mst_qty, 100)) * 100) as succ_rate
                FROM cb_member a 
                	INNER JOIN (
                		WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                			SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username  
                			FROM cb_member_register cmr
                				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                			WHERE cm.mem_id = ".$value->group_id." AND cmr.mem_id <> ".$value->group_id."
                			UNION ALL
                			SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                			FROM cb_member_register cmr 
                				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                		)
                		SELECT DISTINCT mem_id
                		FROM cmem
                	) b ON a.mem_id = b.mem_id and a.mem_useyn = 'Y'
                	LEFT JOIN (
                		SELECT ca.mst_mem_id,
                			SUM(ca.mst_qty) as mst_qty,
                			SUM(ca.mst_at) as mst_at,
                			SUM(ca.mst_ft) as mst_ft,
                			SUM(ca.mst_ft_img) as mst_ft_img,
                			SUM(ca.mst_ft_w_img) as mst_ft_w_img,
                			SUM(ca.mst_2nd) as mst_2nd,
    			             SUM(ca.mst_err) as mst_err
                		FROM (
                			SELECT caa.mst_mem_id,
                                caa.mst_qty, caa.mst_at, caa.mst_ft, 
                                CASE WHEN caa.mst_type1 = 'fti' THEN caa.mst_ft_img ELSE 0 END AS mst_ft_img,
                                CASE WHEN caa.mst_type1 = 'ftw' THEN caa.mst_ft_img ELSE 0 END AS mst_ft_w_img,
                                (caa.mst_grs + caa.mst_grs_sms + caa.mst_nas + caa.mst_nas_sms + caa.mst_smt) AS mst_2nd,
                                (caa.mst_err_ft + caa.mst_err_ft_img + caa.mst_err_at + caa.mst_err_grs + caa.mst_err_nas + caa.mst_err_grs_sms + caa.mst_err_nas_sms + caa.mst_err_smt) as mst_err
                			FROM cb_wt_msg_sent caa
                			WHERE (CASE WHEN caa.mst_reserved_dt = '00000000000000' THEN caa.mst_datetime BETWEEN '".$value->startDate." 00:00:00' AND '".$value->endDate." 23:59:59' ELSE STR_TO_DATE(caa.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$value->startDate." 00:00:00' AND '".$value->endDate." 23:59:59' END )
                		) ca
                		GROUP BY ca.mst_mem_id
                	) c on a.mem_id = c.mst_mem_id
            ) d
            ORDER BY d.".$value->order_by_name." ".$value->order_by.", d.mem_username ASC
        ";
	    
	    //log_message("ERROR", $sql);
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
	        $cur_worksheet->getColumnDimension('B')->setWidth(5.29 + 0.63);
	        $cur_worksheet->getColumnDimension('C')->setWidth(56.00 + 0.63);
	        $cur_worksheet->getColumnDimension('D')->setWidth(12.00 + 0.63);
	        $cur_worksheet->getColumnDimension('E')->setWidth(16.00 + 0.63);
	        $cur_worksheet->getColumnDimension('F')->setWidth(16.00 + 0.63);
	        $cur_worksheet->getColumnDimension('G')->setWidth(16.00 + 0.63);
	        $cur_worksheet->getColumnDimension('H')->setWidth(16.00 + 0.63);
	        $cur_worksheet->getColumnDimension('I')->setWidth(16.00 + 0.63);
	        $cur_worksheet->getColumnDimension('J')->setWidth(16.00 + 0.63);
	        $cur_worksheet->getColumnDimension('K')->setWidth(16.00 + 0.63);
	        $cur_worksheet->getColumnDimension('L')->setWidth(8.00 + 0.63);
	        $cur_worksheet->getColumnDimension('M')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('N')->setWidth(8.43 + 0.63);
	        $cur_worksheet->getColumnDimension('O')->setWidth(8.43 + 0.63);
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
	        
	        $cur_worksheet->setTitle("파트너별 발송 현황(".$group_username.")");
	        //$view_col_index = $sel_col_count + 5;
	        $title = "파트너별 발송 현황(".$group_username." ; ".$value->startDate." ~ ".$value->endDate.")";
	        $cur_worksheet->mergeCells('B'.$next_row_count.':'.$cells[12].$next_row_count)->setCellValue('B'.$next_row_count, $title);
	        $cur_worksheet->getStyle('B'.$next_row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	        $cur_worksheet->getStyle('B'.$next_row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	        $cur_worksheet->getStyle('B'.$next_row_count)->getFont()->setSize(13);
	        $cur_worksheet->getRowDimension($next_row_count)->setRowHeight(25);
	        $cur_worksheet->getStyle('B'.$next_row_count)->getFont()->setBold(true);
	        
	        $next_row_count += 2;
	        $start_table_row = $next_row_count;
	        // Table Header 부분 처리
	        $cur_worksheet->setCellValue('B'.$next_row_count, "순번");
	        $cur_worksheet->setCellValue('C'.$next_row_count, "업체명");
	        $cur_worksheet->setCellValue('D'.$next_row_count, "등급");
	        $cur_worksheet->setCellValue('E'.$next_row_count, "발송요청");
	        $cur_worksheet->setCellValue('F'.$next_row_count, "발송실패");
	        $cur_worksheet->mergeCells('G'.$next_row_count.':'.$cells[11].$next_row_count)->setCellValue('G'.$next_row_count, "발송성공");
	        $cur_worksheet->setCellValue('L'.$next_row_count, "성공율");
	        $cur_worksheet->getStyle("B".$next_row_count.":".$cells[12].($next_row_count + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	        $cur_worksheet->getStyle("B".$next_row_count.":".$cells[12].($next_row_count + 1))->getFill()->getStartColor()->setARGB('00ACB9CA');
	        
	        $next_row_count += 1;
	        $cur_worksheet->setCellValue($cells[7].$next_row_count, "알림톡");
	        $cur_worksheet->setCellValue($cells[8].$next_row_count, "친구톡(텍스트)");
	        $cur_worksheet->setCellValue($cells[9].$next_row_count, "친구톡(이미지)");
	        $cur_worksheet->setCellValue($cells[10].$next_row_count, "친구톡(와이드)");
	        $cur_worksheet->setCellValue($cells[11].$next_row_count, "2차문자");
	        
	        $cur_worksheet->mergeCells('B'.($next_row_count - 1).':B'.$next_row_count);
	        $cur_worksheet->mergeCells('C'.($next_row_count - 1).':C'.$next_row_count);
	        $cur_worksheet->mergeCells('D'.($next_row_count - 1).':D'.$next_row_count);
	        $cur_worksheet->mergeCells('E'.($next_row_count - 1).':E'.$next_row_count);
	        $cur_worksheet->mergeCells('F'.($next_row_count - 1).':F'.$next_row_count);
	        $cur_worksheet->mergeCells('L'.($next_row_count - 1).':L'.$next_row_count);
	        
	        $cur_worksheet->getStyle('B'.($next_row_count - 1).':'.$cells[12].$next_row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	        $cur_worksheet->getStyle('B'.($next_row_count - 1).':'.$cells[12].$next_row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	        
	        $next_row_count += 1;
	        
	        // 리스트 부분 처리
	        $list_start_row = $next_row_count;
	        
	        foreach ($result_query as $r) {
	            $row_index += 1;
	            $cur_worksheet->setCellValue('B'.$next_row_count, $row_index);
	            $cur_worksheet->getStyle('B'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('C'.$next_row_count, $r->mem_username);
	            $cur_worksheet->getStyle('C'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('D'.$next_row_count, $r->mem_level);
	            $cur_worksheet->getStyle('D'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('E'.$next_row_count, $r->mst_qty);
	            $cur_worksheet->getStyle('E'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('F'.$next_row_count, $r->mst_err);
	            $cur_worksheet->getStyle('F'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('G'.$next_row_count, $r->mst_at);
	            $cur_worksheet->getStyle('G'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('H'.$next_row_count, $r->mst_ft);
	            $cur_worksheet->getStyle('H'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('I'.$next_row_count, $r->mst_ft_img);
	            $cur_worksheet->getStyle('I'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('J'.$next_row_count, $r->mst_ft_w_img);
	            $cur_worksheet->getStyle('J'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('K'.$next_row_count, $r->mst_2nd);
	            $cur_worksheet->getStyle('K'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            $cur_worksheet->setCellValue('L'.$next_row_count, $r->succ_rate."%");
	            $cur_worksheet->getStyle('L'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	            
	            $next_row_count += 1;
	        }
	        
	        $cur_worksheet->getStyle('B'.$list_start_row.':B'.($next_row_count - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	        $cur_worksheet->getStyle('B'.$list_start_row.':B'.($next_row_count - 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	        $cur_worksheet->getStyle('C'.$list_start_row.':'.$cells[12].($next_row_count - 1))->getNumberFormat()->setFormatCode("#,##0_-");
	        //$list_start_row
	        
	        $cur_worksheet->getStyle('B'.$start_table_row.':'.$cells[12].($next_row_count - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	        
	        $cur_worksheet->getStyle('A1');
	        
	    }
	    
	    $spreadsheet->setActiveSheetIndex(0);
	    $filename = "파트너별 발송 현황(".$group_username.")";
	    
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
	    
	    // $writer = new Xlsx($spreadsheet);
	    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
	    $writer->save('php://output');
	}
	
	
	
	
}
?>