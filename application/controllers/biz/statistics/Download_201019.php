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
		
		$author = $this->input->post('author');
		
		//log_message("ERROR", "Download author : ".$author);

		// 라이브러리를 로드한다.
		$this->load->library('excel');

		// 시트를 지정한다.
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Sheet1');

		// 필드명을 기록한다.
		// 글꼴 및 정렬
		$head_location = '';
		$title_location = '';
		if (empty($daily)) {
		    if ($author == 'admin') {
		        $head_location = 'A1:P1';
		        $title_location = 'A3:P4';
		    } else {
		        $head_location = 'A1:O1';
		        $title_location = 'A3:O4';
		    }
		} else {
		    if ($author == 'admin') {
		        $head_location = 'A1:O1';
		        $title_location = 'A3:O4';
		    } else {
		        $head_location = 'A1:N1';
		        $title_location = 'A3:N4';
		    }
		}
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	$head_location);

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	$title_location);
        
		if(empty($daily)) {
            $this->excel->getActiveSheet()->freezePaneByColumnAndRow(7, 5);
		} else {
		    $this->excel->getActiveSheet()->freezePaneByColumnAndRow(6, 5);
		}
		
		if(empty($daily)) {
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
			if ($author == 'admin') {
				$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
			}
		} else {
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
			if ($author == 'admin') {
				$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
			}
		}
		
		$this->excel->getActiveSheet()->mergeCells($head_location);
		if(empty($daily)) {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '발 송 통 계');
		} else {
		    if($daily == '1') {
		        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '일 일 발 송 통 계');
		    } else {
		        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '월 별 발 송 통 계');
		    }
		}
		
		$author_sub = 1;
		$daily_sub = 1;
		
		if ($author == 'admin') {
		    $author_sub = 0;
		}
		if(empty($daily)) {
		  $this->excel->getActiveSheet()->mergeCells('A3:A4');
		  $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '발송일');
		  $this->excel->getActiveSheet()->mergeCells('B3:B4');
		  $this->excel->getActiveSheet()->mergeCells('C3:C4');
		  $this->excel->getActiveSheet()->mergeCells('D3:D4');
		  $this->excel->getActiveSheet()->mergeCells('E3:E4');
		  $this->excel->getActiveSheet()->mergeCells('F3:F4');
		  $this->excel->getActiveSheet()->mergeCells('G3:G4');
		  $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:H4');
		  if ($author == 'admin') {
		      $this->excel->getActiveSheet()->mergeCells('H3:L3');
		      $this->excel->getActiveSheet()->mergeCells('M3:P3');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'I4:J4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'K4:L4');
		  } else {
		      $this->excel->getActiveSheet()->mergeCells('H3:T3');
		      $this->excel->getActiveSheet()->mergeCells('U3:AE3');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'I4:I4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'J4:K4');
		  }
		  $daily_sub = 0;
		} else {
		    $this->excel->getActiveSheet()->mergeCells('A3:A4');
		    $this->excel->getActiveSheet()->mergeCells('B3:B4');
		    $this->excel->getActiveSheet()->mergeCells('C3:C4');
		    $this->excel->getActiveSheet()->mergeCells('D3:D4');
		    $this->excel->getActiveSheet()->mergeCells('E3:E4');
		    $this->excel->getActiveSheet()->mergeCells('F3:F4');
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G4:G4');
		    if ($author == 'admin') {
		        $this->excel->getActiveSheet()->mergeCells('G3:K3');
		        $this->excel->getActiveSheet()->mergeCells('L3:O3');
				$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'H4:I4');
				$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'J4:K4');
		    } else {
		        $this->excel->getActiveSheet()->mergeCells('G3:J3');
		        $this->excel->getActiveSheet()->mergeCells('K3:N3');
				$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'H4:H4');
				$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'I4:J4');
		    }
		}

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((1-$daily_sub), 3, '업체명');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((2-$daily_sub), 3, '총 발송요청');
		if($total[0]->get_user_flag == "A") {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow((3-$daily_sub), 3, '발송성공(대기포함)');
		} else {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow((3-$daily_sub), 3, '발송성공');
		}
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((4-$daily_sub), 3, '발송예약');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((5-$daily_sub), 3, '발송실패');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((6-$daily_sub), 3, '발송대기');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub), 3, '발 송 성 공');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub-$author_sub), 3, '발 송 실 패');

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub), 4, '알림톡');
		if ($author == 'admin') {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub), 4, 'WEB(A) KT,LG');
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub), 4, 'WEB(A) SKT');
		} else {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub), 4, 'WEB(A)');
		}
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub-$author_sub), 4, 'WEB(C)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub-$author_sub), 4, 'WEB(C) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub-$author_sub), 4, '알림톡');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$author_sub), 4, 'WEB(A)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$author_sub), 4, 'WEB(C)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$author_sub), 4, 'WEB(C) SMS');

		$row = 5;
		foreach($value as $val) {
			// 데이터를 읽어서 순차로 기록한다.
		    if(empty($daily)) {
			   $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->time);
		    }
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((1-$daily_sub), $row, $val->pf_ynm);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((2-$daily_sub), $row, $val->cnt_total);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((3-$daily_sub), $row, $val->cnt_succ_total);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((4-$daily_sub), $row, $val->cnt_reserved_total);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((5-$daily_sub), $row, $val->cnt_invalid_total);
		    if($total[0]->get_user_flag == "A") {
		      $this->excel->getActiveSheet()->setCellValueByColumnAndRow((6-$daily_sub), $row, $val->cnt_wait_total);
		    }
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub), $row, $val->cnt_succ_kakao);
		    if ($author == 'admin') {
		        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub), $row, $val->cnt_succ_grs);
		        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub), $row, $val->cnt_succ_grs_biz);
		    } else {
		        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub), $row, $val->cnt_succ_grs);
		    }
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub-$author_sub), $row, $val->cnt_succ_smt);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub-$author_sub), $row, $val->cnt_succ_smt_sms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub-$author_sub), $row, $val->cnt_fail_kakao);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$author_sub), $row, $val->cnt_fail_grs);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$author_sub), $row, $val->cnt_fail_smt);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$author_sub), $row, $val->cnt_fail_smt_sms);
			$row++;
		}
		if(empty($daily)) {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '합 계');
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '-');
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H'.$row.':H'.$row);
		    if ($author == 'admin') {
				$this->excel->getActiveSheet()->mergeCells('H3:L3');
				$this->excel->getActiveSheet()->mergeCells('M3:P3');
				$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'I'.$row.':J'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'K'.$row.':L'.$row);
		    } else {
				$this->excel->getActiveSheet()->mergeCells('H3:K3');
				$this->excel->getActiveSheet()->mergeCells('L3:O3');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'I'.$row.':I'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'J'.$row.':K'.$row);
		    }
		} else {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((1-$daily_sub), $row, '합 계');
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G'.$row.':G'.$row);
		    if ($author == 'admin') {
				$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'H'.$row.':I'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'J'.$row.':K'.$row);
		    } else {
				$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'H'.$row.':H'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'I'.$row.':K'.$row);
		    }
		}
		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((2-$daily_sub), $row, $total[0]->get_statistics_cnt_total);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((3-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_total);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((4-$daily_sub), $row, $total[0]->get_statistics_cnt_reserved);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((5-$daily_sub), $row, $total[0]->get_statistics_cnt_invalid_total);
		if($total[0]->get_user_flag == "A") {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((6-$daily_sub), $row, $total[0]->get_statistics_cnt_wait_total);
		}
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_kakao);
		
		if ($author == 'admin') {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_grs);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_grs_biz);
		} else {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_grs);
		}
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt_sms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_kakao);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_grs);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt_sms);
		
		if (empty($daily)) {
		    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
		        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		    ),	'A5:B'.$row);
		    if ($author == 'admin') {
		        $this->excel->getActiveSheet()->getStyle('A5:P'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:P'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    } else {
		        $this->excel->getActiveSheet()->getStyle('A5:O'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:O'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    }
		} else {
		    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
		        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		    ),	'A5:A'.$row);
		    if ($author == 'admin') {
		        $this->excel->getActiveSheet()->getStyle('A5:O'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:O'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    } else {
		        $this->excel->getActiveSheet()->getStyle('A5:N'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:N'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    }
		}
//		$this->excel->getActiveSheet()->getStyle('A3:W'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//		$this->excel->getActiveSheet()->getStyle('A3:W'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//		$this->excel->getActiveSheet()->getStyle('A3:W'.$row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		


// 		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
// 			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
// 			),	'C5:M'.$row);

		$row++;
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