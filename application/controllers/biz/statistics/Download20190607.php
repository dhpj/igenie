<?php
class Download extends CB_Controller {
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

	public function index($daily='')
	{
		$value = json_decode($this->input->post('value'));
		$total = json_decode($this->input->post('total'));

		// 라이브러리를 로드한다.
		$this->load->library('excel');

		// 시트를 지정한다.
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Sheet1');

		// 필드명을 기록한다.
		// 글꼴 및 정렬
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A1:W1');

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A3:W4');

		$this->excel->getActiveSheet()->freezePaneByColumnAndRow(5, 5);
		
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
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
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
		
		$this->excel->getActiveSheet()->mergeCells('A1:W1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '발 송 통 계');

		if(empty($daily)) {
		  $this->excel->getActiveSheet()->mergeCells('A3:A4');
		  $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '발송일');
		}
		$this->excel->getActiveSheet()->mergeCells('B3:B4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '업체명');
		$this->excel->getActiveSheet()->mergeCells('C3:C4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '총 발송요청');
		$this->excel->getActiveSheet()->mergeCells('D3:D4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '발송성공');
		$this->excel->getActiveSheet()->mergeCells('E3:E4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '발송실패');
		$this->excel->getActiveSheet()->mergeCells('F3:F4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, '발송대기');
		$this->excel->getActiveSheet()->mergeCells('G3:M3');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, '발 송 성 공');
		$this->excel->getActiveSheet()->mergeCells('N3:T3');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 3, '발 송 실 패');

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, '알림톡');
		$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G4:G4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, '친구톡(텍스트)');
		$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'H4:H4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, '친구톡(이미지)');
		$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'I4:I4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, 'WEB(A)');
		$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'J4:K4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 4, 'WEB(A) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 4, 'WEB(B)');
		$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd4f4fa'))),'L4:M4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 4, 'WEB(B) SMS');
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 4, '015저가문자');
		//$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M4:M4');
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 4, '폰문자');
		//$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e8d9ff'))),'N4:N4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 4, '알림톡');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 4, '친구톡(텍스트)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, 4, '친구톡(이미지)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, 4, 'WEB(A)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, 4, 'WEB(A) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, 4, 'WEB(B)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, 4, 'WEB(B) SMS');
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, 4, '015저가문자');
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, 4, '폰문자');

		$row = 5;
		foreach($value as $val) {
			// 데이터를 읽어서 순차로 기록한다.
		    if(empty($daily)) {
			   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->time);
		    }
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->pf_ynm);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val->cnt_total);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->cnt_succ_total);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val->cnt_invalid_total);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val->cnt_wait_total);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val->cnt_succ_kakao);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val->cnt_succ_friend);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val->cnt_succ_friend_img);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $val->cnt_succ_grs);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $val->cnt_succ_grs_sms);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->cnt_succ_nas);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->cnt_succ_nas_sms);
			//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->cnt_succ_015);
			//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->cnt_succ_phn);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->cnt_fail_kakao);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $val->cnt_fail_friend);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $val->cnt_fail_friend_img);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $val->cnt_fail_grs);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $val->cnt_fail_grs_sms);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $val->cnt_fail_nas);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $val->cnt_fail_nas_sms);
			//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $val->cnt_fail_015);
			//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $val->cnt_fail_phn);
			$row++;
		}
		if(!empty($daily)) {
    	   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '합 계');
		} else {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '합 계');
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '-');
		}
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $total[0]->get_statistics_cnt_total);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $total[0]->get_statistics_cnt_succ_total);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $total[0]->get_statistics_cnt_invalid_total);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $total[0]->get_statistics_cnt_wait_total);
		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $total[0]->get_statistics_cnt_succ_kakao);
		$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G'.$row.':G'.$row);
		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $total[0]->get_statistics_cnt_succ_friend);
		$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'H'.$row.':H'.$row);
		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $total[0]->get_statistics_cnt_succ_friend_img);
		$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'I'.$row.':I'.$row);
		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $total[0]->get_statistics_cnt_succ_grs);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $total[0]->get_statistics_cnt_succ_grs_sms);
		$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'J'.$row.':K'.$row);
		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $total[0]->get_statistics_cnt_succ_nas);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $total[0]->get_statistics_cnt_succ_nas_sms);
		$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd4f4fa'))),'L'.$row.':M'.$row);
		
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $total[0]->get_statistics_cnt_succ_015);
		//$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M'.$row.':M'.$row);
		
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $total[0]->get_statistics_cnt_succ_phn);
		//$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e8d9ff'))),'N'.$row.':N'.$row);
		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $total[0]->get_statistics_cnt_fail_kakao);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $total[0]->get_statistics_cnt_fail_friend);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $total[0]->get_statistics_cnt_fail_friend_img);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $total[0]->get_statistics_cnt_fail_grs);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $total[0]->get_statistics_cnt_fail_grs_sms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $total[0]->get_statistics_cnt_fail_nas);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $total[0]->get_statistics_cnt_fail_nas_sms);
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $total[0]->get_statistics_cnt_fail_015);
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $total[0]->get_statistics_cnt_fail_phn);
		$this->excel->getActiveSheet()->getStyle('A5:W'.$row)->getNumberFormat()->setFormatCode('#,##0');
		$this->excel->getActiveSheet()->getStyle('A3:W'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//		$this->excel->getActiveSheet()->getStyle('A3:W'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);/
//		$this->excel->getActiveSheet()->getStyle('A3:W'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//		$this->excel->getActiveSheet()->getStyle('A3:W'.$row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$row++;

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A5:B'.$row);
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'C5:M'.$row);

		// 파일로 내보낸다. 파일명은 'filename.xls' 이다.
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="statistics_list.xls"');
		header('Cache-Control: max-age=0');

 

		// Excel5 포맷(excel 2003 .XLS file)으로 저장한다. 
		// 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		// 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
		$objWriter->save('php://output');
	}
}
?>