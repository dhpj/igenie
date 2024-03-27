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
		        $head_location = 'A1:AH1';
		        $title_location = 'A3:AH4';
		    } else {
		        $head_location = 'A1:AG1';
		        $title_location = 'A3:AG4';
		    }
		} else {
		    if ($author == 'admin') {
		        $head_location = 'A1:AG1';
		        $title_location = 'A3:AG4';
		    } else {
		        $head_location = 'A1:AF1';
		        $title_location = 'A3:AF4';
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
		$this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AE')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AF')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AG')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AH')->setWidth(15);
		
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
		  $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'I4:I4');
		  $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J4:J4');
		  $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K4:K4');
		  if ($author == 'admin') {
		      $this->excel->getActiveSheet()->mergeCells('H3:U3');
		      $this->excel->getActiveSheet()->mergeCells('V3:AH3');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'L4:O4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd4f4fa'))),'P4:R4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'S4:U4');
		  } else {
		      $this->excel->getActiveSheet()->mergeCells('H3:T3');
		      $this->excel->getActiveSheet()->mergeCells('U3:AE3');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'L4:N4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd4f4fa'))),'M4:Q4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'R4:T4');
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
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'H4:H4');
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'I4:I4');
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J4:J4');
		    if ($author == 'admin') {
		        $this->excel->getActiveSheet()->mergeCells('G3:T3');
		        $this->excel->getActiveSheet()->mergeCells('U3:AG3');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'K4:N4');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd4f4fa'))),'O4:Q4');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'R4:T4');
		    } else {
		        $this->excel->getActiveSheet()->mergeCells('G3:S3');
		        $this->excel->getActiveSheet()->mergeCells('S3:AF3');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'K4:M4');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd4f4fa'))),'N4:P4');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'Q4:S4');
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
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((21-$daily_sub-$author_sub), 3, '발 송 실 패');

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub), 4, '알림톡');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub), 4, '친구톡(텍스트)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub), 4, '친구톡(이미지)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub), 4, '친구톡(와이드)');
		if ($author == 'admin') {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub), 4, 'WEB(A) KT,LG');
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub), 4, 'WEB(A) SKT');
		} else {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub), 4, 'WEB(A)');
		}
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$author_sub), 4, 'WEB(A) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$author_sub), 4, 'WEB(A) MMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$author_sub), 4, 'WEB(B)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((16-$daily_sub-$author_sub), 4, 'WEB(B) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((17-$daily_sub-$author_sub), 4, 'WEB(B) MMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((18-$daily_sub-$author_sub), 4, 'WEB(C)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((19-$daily_sub-$author_sub), 4, 'WEB(C) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((20-$daily_sub-$author_sub), 4, 'WEB(C) MMS');
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 4, '015저가문자');
		//$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M4:M4');
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 4, '폰문자');
		//$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e8d9ff'))),'N4:N4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((21-$daily_sub-$author_sub), 4, '알림톡');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((22-$daily_sub-$author_sub), 4, '친구톡(텍스트)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((23-$daily_sub-$author_sub), 4, '친구톡(이미지)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((24-$daily_sub-$author_sub), 4, '친구톡(와이드)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((25-$daily_sub-$author_sub), 4, 'WEB(A)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((26-$daily_sub-$author_sub), 4, 'WEB(A) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((27-$daily_sub-$author_sub), 4, 'WEB(A) MMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((28-$daily_sub-$author_sub), 4, 'WEB(B)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((29-$daily_sub-$author_sub), 4, 'WEB(B) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((30-$daily_sub-$author_sub), 4, 'WEB(B) MMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((31-$daily_sub-$author_sub), 4, 'WEB(C)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((32-$daily_sub-$author_sub), 4, 'WEB(C) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((33-$daily_sub-$author_sub), 4, 'WEB(C) MMS');
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, 4, '015저가문자');
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, 4, '폰문자');

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
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub), $row, $val->cnt_succ_friend);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub), $row, $val->cnt_succ_friend_img);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub), $row, $val->cnt_succ_friend_wide_img);
		    if ($author == 'admin') {
		        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub), $row, $val->cnt_succ_grs);
		        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub), $row, $val->cnt_succ_grs_biz);
		    } else {
		        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub), $row, $val->cnt_succ_grs);
		    }
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$author_sub), $row, $val->cnt_succ_grs_sms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$author_sub), $row, $val->cnt_succ_grs_mms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$author_sub), $row, $val->cnt_succ_nas);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((16-$daily_sub-$author_sub), $row, $val->cnt_succ_nas_sms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((17-$daily_sub-$author_sub), $row, $val->cnt_succ_nas_mms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((18-$daily_sub-$author_sub), $row, $val->cnt_succ_smt);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((19-$daily_sub-$author_sub), $row, $val->cnt_succ_smt_sms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((20-$daily_sub-$author_sub), $row, $val->cnt_succ_smt_mms);
		    //$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->cnt_succ_015);
			//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->cnt_succ_phn);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((21-$daily_sub-$author_sub), $row, $val->cnt_fail_kakao);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((22-$daily_sub-$author_sub), $row, $val->cnt_fail_friend);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((23-$daily_sub-$author_sub), $row, $val->cnt_fail_friend_img);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((24-$daily_sub-$author_sub), $row, $val->cnt_fail_friend_img);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((25-$daily_sub-$author_sub), $row, $val->cnt_fail_grs);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((26-$daily_sub-$author_sub), $row, $val->cnt_fail_grs_sms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((27-$daily_sub-$author_sub), $row, $val->cnt_fail_grs_mms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((28-$daily_sub-$author_sub), $row, $val->cnt_fail_nas);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((29-$daily_sub-$author_sub), $row, $val->cnt_fail_nas_sms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((30-$daily_sub-$author_sub), $row, $val->cnt_fail_nas_mms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((31-$daily_sub-$author_sub), $row, $val->cnt_fail_smt);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((32-$daily_sub-$author_sub), $row, $val->cnt_fail_smt_sms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((33-$daily_sub-$author_sub), $row, $val->cnt_fail_smt_mms);
		    //$this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $val->cnt_fail_015);
			//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $val->cnt_fail_phn);
			$row++;
		}
		if(empty($daily)) {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '합 계');
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '-');
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H'.$row.':H'.$row);
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'I'.$row.':I'.$row);
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J'.$row.':J'.$row);
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K'.$row.':K'.$row);
		    if ($author == 'admin') {
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'L'.$row.':O'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd4f4fa'))),'P'.$row.':R'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'S'.$row.':U'.$row);
		    } else {
		        //$this->excel->getActiveSheet()->mergeCells('H3:S3');
		        //$this->excel->getActiveSheet()->mergeCells('S3:AD3');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'L'.$row.':N'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd4f4fa'))),'O'.$row.':Q'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'R'.$row.':T'.$row);
		    }
		} else {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((1-$daily_sub), $row, '합 계');
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G'.$row.':G'.$row);
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'H'.$row.':H'.$row);
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'I'.$row.':I'.$row);
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J'.$row.':J'.$row);
		    if ($author == 'admin') {
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'K'.$row.':N'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd4f4fa'))),'O'.$row.':Q'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'R'.$row.':T'.$row);
		    } else {
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e4f7ba'))),'K'.$row.':M'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd4f4fa'))),'N'.$row.':P'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'Q'.$row.':S'.$row);
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
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_friend);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_friend_img);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_friend_wide_img);
		
		if ($author == 'admin') {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_grs);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_grs_biz);
		} else {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_grs);
		}
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_grs_sms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_grs_mms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_nas);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((16-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_nas_sms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((17-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_nas_mms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((18-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((19-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt_sms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((20-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt_mms);
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $total[0]->get_statistics_cnt_succ_015);
		//$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M'.$row.':M'.$row);
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $total[0]->get_statistics_cnt_succ_phn);
		//$this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'e8d9ff'))),'N'.$row.':N'.$row);
		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((21-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_kakao);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((22-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_friend);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((23-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_friend_img);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((24-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_friend_wide_img);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((25-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_grs);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((26-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_grs_sms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((27-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_grs_mms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((28-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_nas);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((29-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_nas_sms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((30-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_nas_mms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((31-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((32-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt_sms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((33-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt_mms);
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $total[0]->get_statistics_cnt_fail_015);
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $total[0]->get_statistics_cnt_fail_phn);
		
		if (empty($daily)) {
		    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
		        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		    ),	'A5:B'.$row);
		    if ($author == 'admin') {
		        $this->excel->getActiveSheet()->getStyle('A5:AF'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:AF'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    } else {
		        $this->excel->getActiveSheet()->getStyle('A5:AE'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:AE'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    }
		} else {
		    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
		        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		    ),	'A5:A'.$row);
		    if ($author == 'admin') {
		        $this->excel->getActiveSheet()->getStyle('A5:AE'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:AE'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    } else {
		        $this->excel->getActiveSheet()->getStyle('A5:AD'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:AD'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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