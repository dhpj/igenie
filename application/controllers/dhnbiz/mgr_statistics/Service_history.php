<?php
require_once APPPATH.'/third_party/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Service_history extends CB_Controller {
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
		$view['perpage'] = 20;

		// �̺�Ʈ�� �����ϸ� �����մϴ�
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['param']['page'] = ($this->input->get('page')) ? $this->input->get('page') : 1;
		$view['param']['startDate'] = ($this->input->get('startDate')) ? $this->input->get('startDate') : date('Y-m-d', strtotime(date('Y-m-d')."-2week"));
		$view['param']['endDate'] = ($this->input->get('endDate')) ? $this->input->get('endDate') : date('Y-m-d');
		$view['param']['serviceType'] = ($this->input->get('serviceType')) ? $this->input->get('serviceType') : 'ALL';
		$view['param']['mem_id'] = ($this->input->get('mem_id')) ? $this->input->get('mem_id') : 'ALL';

		$where = '';
		if ($view['param']['mem_id'] != 'ALL') {
		    $where = "a.sh_mem_id = ".$view['param']['mem_id']." AND ";
		}
		
		if ($view['param']['serviceType'] != 'ALL') {
		    $where = "a.sh_type = '".$view['param']['serviceType']."' AND ";
		}
		
		
		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$sql = "SELECT count(*) cnt
            FROM cb_service_history a 
            	LEFT JOIN cb_member b on a.sh_mem_id = b.mem_id
            WHERE ".$where." a.sh_reg_date BETWEEN '".$view['param']['startDate']." 00:00:00' AND '".$view['param']['endDate']." 23:59:59'
            ORDER BY a.sh_reg_date DESC, b.mem_username ASC";
		log_message("ERROR", "sql : ".$sql);
		$page_cfg['total_rows'] = $this->db->query($sql)->row()->cnt;
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		
		$view['member'] = $this->Biz_model->get_partners($this->member->item("mem_id"));
		
		$sql = "
            SELECT a.sh_mem_id, a.sh_reg_date, b.mem_username, a.sh_text, a.sh_type 
            FROM cb_service_history a 
            	LEFT JOIN cb_member b on a.sh_mem_id = b.mem_id
            WHERE ".$where." a.sh_reg_date BETWEEN '".$view['param']['startDate']." 00:00:00' AND '".$view['param']['endDate']." 23:59:59'
            ORDER BY a.sh_reg_date DESC, b.mem_username ASC limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
		//log_message("ERROR", "sql : ".$sql);
		
		
		$view['list'] = $this->db->query($sql)->result();
	
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
			'skin' => 'service_history',
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
	    //$mode = $this->input->post("actions");
	    // if($mode=="partner_save") { $this->partner_save(true); return; }
	    
	    // 이벤트 라이브러리를 로딩합니다
	    $eventname = 'event_main_index';
	    $this->load->event($eventname);
	    
	    $view = array();
	    $view['view'] = array();
	    
	    // 이벤트가 존재하면 실행합니다
	    $view['view']['event']['before'] = Events::trigger('before', $eventname);
	    
	    $view['member'] = $this->Biz_model->get_partners($this->member->item("mem_id"));
	    //$view['offer'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id')); //get_child_partner_info($this->member->item('mem_id'), "a.mem_level>=10");	//$this->db->query("select mem_id, mem_username from cb_member where mem_level>=10 order by mem_level, mem_username")->result();
	    //$view['offer'] = $this->Biz_model->get_offer();
	    
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
	        'path' => 'biz/mgr_statistics',
	        'layout' => 'layout',
	        'skin' => 'sh_write',
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
	
	public function service_history_save() {
	   
	    log_message("ERROR", "Service_history service_history_save start");
	    $memo = $this->input->post('memo');
	    $mem_id = $this->input->post('mem_id');
	    $memo_kind = $this->input->post('serviceType');
	    
	    $data = array();
	    $data['sh_mem_id'] = $mem_id;
	    $data['sh_reg_date'] = cdate('Y-m-d H:i:s');
	    $data['sh_text'] = $memo;
	    $data['sh_type'] =  $memo_kind;
	    
	    $this->db->insert("service_history", $data);
	    
	    header('Content-Type: application/json');
	    echo '{"message":"success", "code":"success"}';
	    
//	    if ($this->db->error()['code'] > 0) {
//	        echo '<script type="text/javascript">alert("저장 오류입니다.");history.back();</script>';
//	    } else {
//	        header("Location: /biz/mgr_statistics/service_history");
//	    }
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