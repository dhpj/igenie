<?php
class Report extends CB_Controller {
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
	}

	public function sale_report()
	{
		$key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();
		$view['param']['userid'] = (!$this->input->post('userid') || $this->input->post('userid')=="ALL") ? ($this->member->item('mem_level')>=100) ? "dhn" : $this->member->item('mem_userid') : $this->input->post('userid');
		$view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);
		
		$view['users'] = $this->Biz_model->get_child_member($this->member->item('mem_id'), 10);

		$mem = $this->Biz_model->get_member($view['param']['userid'], true);
		//echo '<pre> :: ';print_r($mem); exit;
		if(!$mem || $mem->mem_userid!=$view['param']['userid']) { show_404(); exit; }
		$view['member'] = $mem;

		$view['param']['beforeDate'] = date('Y-m', strtotime("-1 month", strtotime($view['param']['startDate']."-01")));

		$sql = "
			select t2.mrg_recommend_mem_id parent, t3.mem_username parent_name, t1.*
			from 
				(
				select b.mem_id, b.mem_userid, b.mem_username, b.mem_level,
					ifnull((select sum(dep_deposit) from cb_deposit where mem_id=a.mst_mem_id and dep_status='1' and substr(dep_deposit_datetime, 1, 7)='".$view['param']['beforeDate']."'), 0) mst_charge1,
					ifnull((select sum(ref_amount) from cb_wt_refund where ref_mem_id=a.mst_mem_id and ref_stat='Y' and substr(ref_datetime, 1, 7)='".$view['param']['beforeDate']."'), 0) mst_refund1,
					sum(case when substr(a.mst_datetime, 1, 7)='".$view['param']['beforeDate']."' then a.mst_amount else 0 end) mst_amount1,
					sum(case when substr(a.mst_datetime, 1, 7)='".$view['param']['beforeDate']."' then a.mst_qty else 0 end) mst_qty1,
					sum(case when substr(a.mst_datetime, 1, 7)='".$view['param']['beforeDate']."' then a.mst_at + a.mst_ft + a.mst_ft_img else 0 end) mst_kakao1,
					sum(case when substr(a.mst_datetime, 1, 7)='".$view['param']['beforeDate']."' then a.mst_sms + a.mst_lms + a.mst_mms + a.mst_phn else 0 end) mst_resend1,
					ifnull((select sum(dep_deposit) from cb_deposit where mem_id=a.mst_mem_id and dep_status='1' and substr(dep_deposit_datetime, 1, 7)='".$view['param']['startDate']."'), 0) mst_charge2,
					ifnull((select sum(ref_amount) from cb_wt_refund where ref_mem_id=a.mst_mem_id and ref_stat='Y' and substr(ref_datetime, 1, 7)='".$view['param']['startDate']."'), 0) mst_refund2,
					sum(case when substr(a.mst_datetime, 1, 7)='".$view['param']['startDate']."' then a.mst_amount else 0 end) mst_amount2,
					sum(case when substr(a.mst_datetime, 1, 7)='".$view['param']['startDate']."' then a.mst_qty else 0 end) mst_qty2,
					sum(case when substr(a.mst_datetime, 1, 7)='".$view['param']['startDate']."' then a.mst_at + a.mst_ft + a.mst_ft_img else 0 end) mst_kakao2,
					sum(case when substr(a.mst_datetime, 1, 7)='".$view['param']['startDate']."' then a.mst_sms + a.mst_lms + a.mst_mms + a.mst_phn else 0 end) mst_resend2,
					b.mem_deposit,
					b.mem_point
				from cb_wt_msg_sent a
					inner join cb_member b on a.mst_mem_id=b.mem_id
				group by a.mst_mem_id
				) t1 inner join
				(select  mem_id, mrg_recommend_mem_id from cb_member_register
					where 
						FIND_IN_SET(mem_id, 
							(SELECT GROUP_CONCAT(memid SEPARATOR ',') memid FROM (
								SELECT @pv:=(SELECT GROUP_CONCAT(mem_id SEPARATOR ',') FROM cb_member_register 
								WHERE FIND_IN_SET(mrg_recommend_mem_id, @pv)) AS memid FROM cb_member_register 
								JOIN (SELECT @pv:=".$mem->mem_id.") tmp
							) a)
						) > 0 or mem_id=".$mem->mem_id."
				  ) t2 on t1.mem_id=t2.mem_id inner join
				cb_member t3 on t2.mrg_recommend_mem_id=t3.mem_id
			order by mem_level desc, mem_id asc
		";

		$view['list'] = $this->db->query($sql)->result();
		
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
			'path' => 'biz/manager/report',
			'layout' => 'layout',
			'skin' => "sale_report",
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

	//매출현황
	public function sale_report_download()
	{
		log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 매출현황");
		$value = json_decode($this->input->post('value'));
		$vendor = $this->input->post('vendor');
		$startDate = $this->input->post('startDate');

		// 라이브러리를 로드한다.
		$this->load->library('excel');

		// 시트를 지정한다.
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Sheet1');

		// 필드명을 기록한다.
		// 글꼴 및 정렬
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A1:M1');

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A3:M4');

		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

		$this->excel->getActiveSheet()->mergeCells('A1:M1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $startDate." ".$vendor." 매 출 현 황");

		$this->excel->getActiveSheet()->mergeCells('A3:A4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '소속');
		$this->excel->getActiveSheet()->mergeCells('B3:B4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '업체명');
		$this->excel->getActiveSheet()->mergeCells('C3:G3');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '전월실적');
		$this->excel->getActiveSheet()->mergeCells('H3:L3');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, '금월실적');
		$this->excel->getActiveSheet()->mergeCells('M3:M4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, '예치금잔액');

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, '충전금액');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, '카카오');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, '재발신');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, '실패');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, '매출합계');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, '충전금액');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, '카카오');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, '재발신');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 4, '실패');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 4, '매출합계');

		$row = 5;
		foreach($value as $val) {
			// 데이터를 읽어서 순차로 기록한다.
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->parent);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->company);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, ($val->charge1 - $val->refund1));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->kakao1);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val->resend1);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, ($val->qty1 - ($val->kakao1 + $val->resend1)));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val->amount1);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, ($val->charge2 - $val->refund2));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val->kakao2);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $val->resend2);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, ($val->qty2 - ($val->kakao2 + $val->resend2)));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->amount2);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, ($val->deposit + $val->point));
			$row++;
		}

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A5:B'.($row - 1));
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'C5:M'.($row - 1));

		// 파일로 내보낸다. 파일명은 'filename.xls' 이다.
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="sale_report.xls"');
		header('Cache-Control: max-age=0');

		// Excel5 포맷(excel 2003 .XLS file)으로 저장한다. 
		// 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		// 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
		$objWriter->save('php://output');
	}
}
?>