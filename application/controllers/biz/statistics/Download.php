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
	        // 일별, 월별 엑셀
	        if ($total[0]->get_user_flag == "A") {
// 	            $head_location = 'A1:W1';
// 	            $title_location = 'A3:W4';
	            $head_location = 'A1:AE1';
	            $title_location = 'A3:AE4';
	        } else {
// 	            $head_location = 'A1:V1';
// 	            $title_location = 'A3:V4';
	            $head_location = 'A1:AD1';
	            $title_location = 'A3:AD4';
	        }
	    } else {
	        // 일일통계, 월별통계 엑셀
// 	        if ($total[0]->get_user_flag == "A") {
// 	            $head_location = 'A1:Z1';
// 	            $title_location = 'A3:Z4';
// 	        } else {
// 	            $head_location = 'A1:W1';
// 	            $title_location = 'A3:W4';
// 	        }
	        if($daily == '2') { // 월별통계
	            if ($total[0]->get_user_flag == "A") {
	                $head_location = 'A1:AK1';
	                $title_location = 'A3:AK4';
	            } else {
	                $head_location = 'A1:AH1';
	                $title_location = 'A3:AH4';
	            }
	        } else {   //일일통계
    	        if ($total[0]->get_user_flag == "A") {
    	            $head_location = 'A1:AJ1';
    	            $title_location = 'A3:AJ4';
    	        } else {
    	            $head_location = 'A1:AG1';
    	            $title_location = 'A3:AG4';
    	        }
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
	        // 일별, 월별 엑셀
	        if($total[0]->get_user_flag == "A") {
	            $this->excel->getActiveSheet()->freezePaneByColumnAndRow(7, 5);
	        } else {
	            $this->excel->getActiveSheet()->freezePaneByColumnAndRow(6, 5);
	        }
	    } else {
	        // 일일통계, 월별통계 엑셀
	        if($total[0]->get_user_flag == "A") {
	            $this->excel->getActiveSheet()->freezePaneByColumnAndRow(10, 5);
	        } else {
	            $this->excel->getActiveSheet()->freezePaneByColumnAndRow(7, 5);
	        }
	    }

	    if(empty($daily)) {
	        // 일별, 월별 엑셀
	        if($total[0]->get_user_flag == "A") {
	            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15); // 발송일
	            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); // 업체명
	            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); // 총 발송요청
	            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // 발송성공(대기포함)
	            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); // 발송예약
	            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); // 발송실패
	            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15); // 발송대기
	            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	        } else {
	            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15); // 발송일
	            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); // 업체명
	            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); // 총 발송요청
	            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); // 발송성공
	            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); // 발송예약
	            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); // 발송실패
	            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15); // 발송성공-알림톡
	            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	        }
	    } else {
	        // 일일통계, 월별통계 엑셀
	        if($total[0]->get_user_flag == "A") {
	            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10); // 순번
	            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); // 소속
	            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20); // 업체명
	            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // 영업자
	            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20); // 지점
	            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); // 총 발송요청
	            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20); // 발송성공(대기포함)
	            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); // 발송예약
	            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15); // 발송실패
	            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15); // 발송대기
	        } else {
	            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10); // 순번
	            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20); // 소속
	            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20); // 업체명
	            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); // 총 발송요청
	            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); // 발송성공
	            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); // 발송예약
	            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15); // 발송실패
	            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); // 발송성공-알림톡
	            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15); // 발송성공-이미지 알림톡
	            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15); // 발송성공-이미지 알림톡
	        }
	    }
// 	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
// 	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
// 	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
// 	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
// 	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
// 	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
// 	    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
//	    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
//	    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
//	    $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);

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

	    $this->excel->getActiveSheet()->getColumnDimension('AI')->setWidth(15);
	    $this->excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(15);
	    $this->excel->getActiveSheet()->getColumnDimension('AK')->setWidth(15);
	    $this->excel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
	    $this->excel->getActiveSheet()->getColumnDimension('AM')->setWidth(15);
	    $this->excel->getActiveSheet()->getColumnDimension('AN')->setWidth(15);
	    
	    $this->excel->getActiveSheet()->mergeCells($head_location);
	    if(empty($daily)) {
	        // 일별, 월별 엑셀
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '발 송 통 계');
	    } else {
	        // 일일통계, 월별통계 엑셀
	        if($daily == '1') {
	            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '일 일 발 송 통 계('.$total[0]->get_title.')');
	        } else {
	            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '월 별 발 송 통 계('.$total[0]->get_title.')');
	        }
	    }

	    $daily_sub = 1;    // 일자 포함유무
	    $no_sub = 1;       // 순번
	    $belong_sub = 1;   // 소속
	    $salesperson_sub = 1;  // 영업자
	    $sales_sub = 1;  // 지점
	    $author_sub = 1;   // 대기

	    if ($total[0]->get_user_flag == "A") {
	        $author_sub = 0;
	        if(!empty($daily)) {
	            $salesperson_sub = 0;
	            $sales_sub = 0;
	        }
	    }
	    if(!empty($daily)) {
	        // 일일통계, 월별통계 엑셀
	        $belong_sub = 0;
	        $no_sub = 0;
	    }

	    if(empty($daily)) {
	        // 일별, 월별 엑셀
	        $this->excel->getActiveSheet()->mergeCells('A3:A4');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '발송일');
	        $this->excel->getActiveSheet()->mergeCells('B3:B4');
	        $this->excel->getActiveSheet()->mergeCells('C3:C4');
	        $this->excel->getActiveSheet()->mergeCells('D3:D4');
	        $this->excel->getActiveSheet()->mergeCells('E3:E4');
	        $this->excel->getActiveSheet()->mergeCells('F3:F4');

	        if ($total[0]->get_user_flag == "A") {
	            $this->excel->getActiveSheet()->mergeCells('G3:G4');
// 	            $this->excel->getActiveSheet()->mergeCells('H3:O3');
	            $this->excel->getActiveSheet()->mergeCells('H3:S3');
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:I4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:H4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'I4:I4');
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J4:L4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J4:J4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K4:K4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'L4:L4');
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M4:O4');
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'P4:S4');
// 	            $this->excel->getActiveSheet()->mergeCells('P3:W3');
	            $this->excel->getActiveSheet()->mergeCells('T3:AE3');
	        } else {
	            $this->excel->getActiveSheet()->mergeCells('G3:N3');
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G4:H4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G4:G4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:H4');
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'I4:K4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'I4:I4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J4:J4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K4:K4');
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'L4:N4');
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'O4:R4');
// 	            $this->excel->getActiveSheet()->mergeCells('O3:V3');
	            $this->excel->getActiveSheet()->mergeCells('S3:AD3');
	        }

	        $daily_sub = 0;
	    } else {
	        // 일일통계, 월별통계 엑셀
	        $this->excel->getActiveSheet()->mergeCells('A3:A4');   // 순번
	        $this->excel->getActiveSheet()->mergeCells('B3:B4');   // 소속
	        $this->excel->getActiveSheet()->mergeCells('C3:C4');   // 업체명
	        $this->excel->getActiveSheet()->mergeCells('D3:D4');   // 영업자(A) or 총발송요청(U)
	        $this->excel->getActiveSheet()->mergeCells('E3:E4');   // 지점(A) or 발송성공(U)
	        $this->excel->getActiveSheet()->mergeCells('F3:F4');   // 총발송요청(A) or 발송예약(U)
	        $this->excel->getActiveSheet()->mergeCells('G3:G4');   // 발송성공(대기포함)(A) or 발송실패(U)

// 	        if ($total[0]->get_user_flag == "A") {
// 	            $this->excel->getActiveSheet()->mergeCells('H3:H4');   // 발송예약(A)
// 	            $this->excel->getActiveSheet()->mergeCells('I3:I4');   // 발송실패(A)
// 	            $this->excel->getActiveSheet()->mergeCells('J3:J4');   // 발송대기(A)
// 	            $this->excel->getActiveSheet()->mergeCells('K3:R3');   // 발송성공 전체 타이틀(A)
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'K4:K4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'L4:L4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'M4:M4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'N4:N4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'O4:O4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'P4:R4');
// 	            $this->excel->getActiveSheet()->mergeCells('T3:Z3');
// 	        } else {
// 	            $this->excel->getActiveSheet()->mergeCells('H3:O3');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:H4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'I4:I4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J4:J4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K4:K4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'L4:L4');
// 	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M4:O4');
// 	            $this->excel->getActiveSheet()->mergeCells('P3:W3');
// 	        }
	        
	        if($daily == '2') { // 월별통계
	            if ($total[0]->get_user_flag == "A") {
	                $this->excel->getActiveSheet()->mergeCells('H3:H4');   // 발송예약(A)
	                $this->excel->getActiveSheet()->mergeCells('I3:I4');   // 발송실패(A)
	                $this->excel->getActiveSheet()->mergeCells('J3:J4');   // 발송대기(A)
	                $this->excel->getActiveSheet()->mergeCells('K3:V3');   // 발송성공 전체 타이틀(A)
	                $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'K4:L4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'K4:K4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'L4:L4');
	                $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'M4:O4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'M4:M4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'N4:N4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'O4:O4');
	                $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'P4:R4');
	                $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'S4:V4');
	                $this->excel->getActiveSheet()->mergeCells('W3:AH3');
	                $this->excel->getActiveSheet()->mergeCells('AI3:AI4');
	                $this->excel->getActiveSheet()->mergeCells('AJ3:AJ4');
	            } else {
	                $this->excel->getActiveSheet()->mergeCells('H3:T3');
	                $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:I4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:H4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'I4:I4');
	                $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J4:L4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J4:J4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K4:K4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'L4:L4');
	                $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M4:O4');
	                $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'P4:S4');
	                $this->excel->getActiveSheet()->mergeCells('T3:AE3');
	                $this->excel->getActiveSheet()->mergeCells('AF3:AF4');
	            }
	        } else {   // 일일통계
    	        if ($total[0]->get_user_flag == "A") {
    	            $this->excel->getActiveSheet()->mergeCells('H3:H4');   // 발송예약(A)
    	            $this->excel->getActiveSheet()->mergeCells('I3:I4');   // 발송실패(A)
    	            $this->excel->getActiveSheet()->mergeCells('J3:J4');   // 발송대기(A)
    	            $this->excel->getActiveSheet()->mergeCells('K3:V3');   // 발송성공 전체 타이틀(A)
    	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'K4:L4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'K4:K4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'L4:L4');
    	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'M4:O4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'M4:M4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'N4:N4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'O4:O4');
    	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'P4:R4');
    	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'S4:V4');
    	            $this->excel->getActiveSheet()->mergeCells('W3:AH3');
    	        } else {
    	            $this->excel->getActiveSheet()->mergeCells('H3:S3');
    	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:I4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:H4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'I4:I4');
    	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J4:L4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J4:J4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K4:K4');
//     	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'L4:L4');
    	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M4:O4');
    	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'P4:S4');
    	            $this->excel->getActiveSheet()->mergeCells('T3:AE3');
    	        }
	        }
	    }

// 	    $daily_sub = 1;    // 일자 포함유무
// 	    $no_sub = 1;       // 순번
// 	    $belong_sub = 1;   // 소속
// 	    $salesperson_sub = 1;  // 영업자
// 	    $sales_sub = 1;  // 지점
// 	    $author_sub = 1;   // 대기



	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((1-$daily_sub), 3, '순번');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((2-$daily_sub-$no_sub), 3, '소속');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((3-$daily_sub-$no_sub-$belong_sub), 3, '업체명');
	    //if()
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((4-$daily_sub-$no_sub-$belong_sub), 3, '영업자');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((5-$daily_sub-$no_sub-$belong_sub-$salesperson_sub), 3, '지점');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((6-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), 3, '총 발송요청');
	    if($total[0]->get_user_flag == "A") {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), 3, '발송성공(대기포함)');
	    } else {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), 3, '발송성공');
	    }
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), 3, '발송예약');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), 3, '발송실패');

	    if ($total[0]->get_user_flag == "A") {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), 3, '발송대기');
	    }

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 3, '발 송 성 공');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((23-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 3, '발 송 실 패');
	    
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '알림톡');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '이미지 알림톡');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '친구톡(텍스트)');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '친구톡(이미지)');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '친구톡(와이드)');
	    if ($total[0]->get_user_flag == "A") {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((16-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'WEB(C) SMS');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((17-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'WEB(C) LMS');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((18-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'WEB(C) MMS');
	    } else {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((16-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '문자(SMS)');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((17-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '문자(LMS)');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((18-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '문자(MMS)');
	    }
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((19-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'RCS SMS');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((20-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'RCS LMS');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((21-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'RCS MMS');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((22-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'RCS 템플릿');
	    

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((23-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '알림톡');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((24-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '이미지 알림톡');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((25-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '친구톡(텍스트)');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((26-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '친구톡(이미지)');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((27-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '친구톡(와이드)');
	    if ($total[0]->get_user_flag == "A") {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((28-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'WEB(C) SMS');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((29-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'WEB(C) LMS');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((30-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'WEB(C) MMS');
	    } else {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((28-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '문자(SMS)');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((29-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '문자(LMS)');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((30-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, '문자(MMS)');
	    }
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((31-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'RCS SMS');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((32-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'RCS LMS');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((33-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'RCS MMS');
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((34-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 4, 'RCS 템플릿');
	    
        if($daily=="2"){
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow((35-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 3, '담당자 연락처');
            if ($total[0]->get_user_flag == "A") {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow((36-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), 3, '인센티브');
            }
        }

	    //$this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, 4, '015저가문자');
	    //$this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, 4, '폰문자');

	    $row = 5;
	    $data_row = 0;
	    $data_block_row = 0;
	    $last_col_alp = "";
	    $last_col_alp = "";
	    if (empty($daily)) {
	        // 일별, 월별 엑셀
// 	        ($total[0]->get_user_flag == "A") ? $last_col_alp = "W" : $last_col_alp = "V";
	        ($total[0]->get_user_flag == "A") ? $last_col_alp = "AE" : $last_col_alp = "AD";
	    } else {
	        // 일일통계, 월별통계 엑셀
// 	        ($total[0]->get_user_flag == "A") ? $last_col_alp = "Z" : $last_col_alp = "W";
	        if($daily=="2"){
	            ($total[0]->get_user_flag == "A") ? $last_col_alp = "AJ" : $last_col_alp = "AG";
	        } else {
	            ($total[0]->get_user_flag == "A") ? $last_col_alp = "AI" : $last_col_alp = "AF";
	        }
	    }

	    foreach($value as $val) {
	        $data_row++;

	        // 데이터를 읽어서 순차로 기록한다.
	        if(empty($daily)) {
	            // 일별, 월별 엑셀
	            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->time);       // 일자
	        }

	        // 	    $daily_sub = 1;    // 일자 포함유무
	        // 	    $no_sub = 1;       // 순번
	        // 	    $belong_sub = 1;   // 소속
	        // 	    $salesperson_sub = 1;  // 영업자
	        // 	    $sales_sub = 1;  // 지점
	        // 	    $author_sub = 1;   // 대기


	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((1-$daily_sub), $row, $val->row_no);    // 순번
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((2-$daily_sub-$no_sub), $row, $val->pf_pnm);    // 소속
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((3-$daily_sub-$no_sub-$belong_sub), $row, $val->pf_ynm);    // 업체
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((4-$daily_sub-$no_sub-$belong_sub), $row, $val->salesperson);   // 영업자
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((5-$daily_sub-$no_sub-$belong_sub-$salesperson_sub), $row, $val->sales);     // 지점
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((6-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), $row, $val->cnt_total);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), $row, $val->cnt_succ_total);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), $row, $val->cnt_reserved_total);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), $row, $val->cnt_invalid_total);
	        if($total[0]->get_user_flag == "A") {
	            $this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), $row, $val->cnt_wait_total);
	        }
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_kakao);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_kakao_img);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_friend);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_friend_img);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_friend_wide_img);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((16-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_smt_sms);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((17-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_smt);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((18-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_smt_mms);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((19-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_rcs_sms);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((20-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_rcs);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((21-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_rcs_mms);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((22-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_succ_rcs_tpl);
	        
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((23-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_kakao);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((24-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_kakao_img);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((25-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_friend);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((26-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_friend_img);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((27-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_friend_wide_img);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((28-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_smt_sms);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((29-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_smt);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((30-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_smt_mms);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((31-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_rcs_sms);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((32-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_rcs);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((33-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_rcs_mms);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((34-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_fail_rcs_tpl);
	        if($daily=="2"){
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow((35-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->pf_phone);
                if ($total[0]->get_user_flag == "A") {
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((36-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $val->cnt_incentive);
                }
	        }
	        if ($data_row % 2 == 0) { // 데이터 짝수 row
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'edf0f5'))),'A'.$row.':'.$last_col_alp.$row);
	        }
	        
// 	        if(empty($daily)) {
// 	            if ($data_row % 2 == 0) { // 데이터 짝수 row
// 	                $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'edf0f5'))),'A'.$row.':'.$last_col_alp.$row);
// 	            }
// 	        } else {
// 	            if ($data_row % 2 == 0){
// 	                $data_block_row++;
// 	                if ($data_block_row % 2 == 0 && $data_block_row > 0) {
// 	                    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'edf0f5'))),'A'.($row-1).':'.$last_col_alp.$row);
// 	                }

// 	                if($total[0]->get_user_flag == "A") {  //
// 	                    $this->excel->getActiveSheet()->mergeCells('C'.($row-1).':C'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('D'.($row-1).':D'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('E'.($row-1).':E'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('F'.($row-1).':F'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('G'.($row-1).':G'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('H'.($row-1).':H'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('I'.($row-1).':I'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('J'.($row-1).':J'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('K'.($row-1).':K'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('L'.($row-1).':L'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('M'.($row-1).':M'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('N'.($row-1).':N'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('O'.($row-1).':O'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('P'.($row-1).':P'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('Q'.($row-1).':Q'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('R'.($row-1).':R'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('S'.($row-1).':S'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('T'.($row-1).':T'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('U'.($row-1).':U'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('V'.($row-1).':V'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('W'.($row-1).':W'.$row);
// 	                } else {
// 	                    $this->excel->getActiveSheet()->mergeCells('B'.($row-1).':B'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('C'.($row-1).':C'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('D'.($row-1).':D'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('E'.($row-1).':E'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('F'.($row-1).':F'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('G'.($row-1).':G'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('H'.($row-1).':H'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('I'.($row-1).':I'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('J'.($row-1).':J'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('K'.($row-1).':K'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('L'.($row-1).':L'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('M'.($row-1).':M'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('N'.($row-1).':N'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('O'.($row-1).':O'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('P'.($row-1).':P'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('Q'.($row-1).':Q'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('R'.($row-1).':R'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('S'.($row-1).':S'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('T'.($row-1).':T'.$row);
// 	                    $this->excel->getActiveSheet()->mergeCells('U'.($row-1).':U'.$row);
// 	                }
// 	            }
// 	        }

	        $row++;
	    }

	    if(empty($daily)) {
	        // 일별, 월별 엑셀
	        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD'))),'A'.$row.':'.$last_col_alp.$row);

	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '합 계');
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '-');

	        if ($total[0]->get_user_flag == "A") {
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H'.$row.':H'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'I'.$row.':I'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J'.$row.':J'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K'.$row.':K'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'L'.$row.':L'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M'.$row.':O'.$row);
	        } else {
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G'.$row.':G'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H'.$row.':H'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'I'.$row.':I'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J'.$row.':J'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K'.$row.':K'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'L'.$row.':M'.$row);
	        }
	    } else {
	        // 일일통계, 월별통계 엑셀
	        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD'))),'A'.$row.':'.$last_col_alp.$row);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((1-$daily_sub), $row, '합 계');

	        if ($total[0]->get_user_flag == "A") {
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'K'.$row.':K'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'L'.$row.':L'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'M'.$row.':M'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'N'.$row.':N'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'O'.$row.':O'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'P'.$row.':R'.$row);
	        } else {
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H'.$row.':H'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'I'.$row.':I'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J'.$row.':J'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K'.$row.':K'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'L'.$row.':L'.$row);
	            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M'.$row.':O'.$row);
	        }
	    }
	    // 	    $daily_sub = 1;    // 일자 포함유무
	    // 	    $no_sub = 1;       // 순번
	    // 	    $belong_sub = 1;   // 소속
	    // 	    $salesperson_sub = 1;  // 영업자
	    // 	    $sales_sub = 1;  // 지점
	    // 	    $author_sub = 1;   // 대기

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((2-$daily_sub), $row, '-'); //
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((3-$daily_sub-$no_sub), $row, '-'); //
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((4-$daily_sub-$no_sub-$belong_sub), $row, '-'); //
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((5-$daily_sub-$no_sub-$belong_sub-$salesperson_sub), $row, '-'); //
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((6-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), $row, $total[0]->get_statistics_cnt_total);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), $row, $total[0]->get_statistics_cnt_succ_total);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), $row, $total[0]->get_statistics_cnt_reserved);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), $row, $total[0]->get_statistics_cnt_invalid_total);
	    if($total[0]->get_user_flag == "A") {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub), $row, $total[0]->get_statistics_cnt_wait_total);
	    }
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_kakao);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_kakao_img);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_friend);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_friend_img);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_friend_wide_img);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((16-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt_sms);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((17-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((18-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt_mms);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((19-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_rcs_sms);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((20-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_rcs);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((21-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_rcs_mms);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((22-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_rcs_tpl);
	    
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((23-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_kakao);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((24-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_kakao_img);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((25-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_friend);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((26-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_friend_img);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((27-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_friend_wide_img);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((28-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt_sms);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((29-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((30-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt_mms);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((31-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_rcs_sms);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((32-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_rcs);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((33-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_rcs_mms);
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((34-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_rcs_tpl);
	    
	    if (empty($daily)) {
	        // 일별, 월별 엑셀
	        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
	            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
	        ),	'A5:B'.$row);
	        if ($total[0]->get_user_flag == "A") {
	            $this->excel->getActiveSheet()->getStyle('A5:AE'.$row)->getNumberFormat()->setFormatCode('#,##0');
	            $this->excel->getActiveSheet()->getStyle('A3:AE'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	        } else {
	            $this->excel->getActiveSheet()->getStyle('A5:AD'.$row)->getNumberFormat()->setFormatCode('#,##0');
	            $this->excel->getActiveSheet()->getStyle('A3:AD'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	        }
	    } else {
	        // 일일통계, 월별통계 엑셀
	        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
	            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
	        ),	'A5:A'.$row);
	        if ($total[0]->get_user_flag == "A") {
	            $this->excel->getActiveSheet()->getStyle('A5:AH'.$row)->getNumberFormat()->setFormatCode('#,##0');
	            $this->excel->getActiveSheet()->getStyle('A3:AH'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	            if ($daily == '2') {
	                $this->excel->getActiveSheet()->setCellValueByColumnAndRow((36-$daily_sub-$no_sub-$belong_sub-$salesperson_sub-$sales_sub-$author_sub), $row, $total[0]->get_statistics_cnt_incentive);
	                $this->excel->getActiveSheet()->getStyle('AJ5:AJ'.$row)->getNumberFormat()->setFormatCode('#,##0.0');
	                $this->excel->getActiveSheet()->getStyle('AJ3:AJ'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	                
	            }
	        } else {
	            $this->excel->getActiveSheet()->getStyle('A5:AF'.$row)->getNumberFormat()->setFormatCode('#,##0');
	            $this->excel->getActiveSheet()->getStyle('A3:AF'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	        }
	    }

	    $row++;

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="statistics_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
	    // $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');
	}

	private function index_20210909__($daily='')
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
		    if ($total[0]->get_user_flag == "A") {
		        $head_location = 'A1:W1';
		        $title_location = 'A3:W4';
		    } else {
		        $head_location = 'A1:V1';
		        $title_location = 'A3:V4';
		    }
		} else {
		    if ($total[0]->get_user_flag == "A") {
		        $head_location = 'A1:V1';
		        $title_location = 'A3:V4';
		    } else {
		        $head_location = 'A1:U1';
		        $title_location = 'A3:U4';
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
		    if($total[0]->get_user_flag == "A") {
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(7, 5);
		    } else {
		        $this->excel->getActiveSheet()->freezePaneByColumnAndRow(6, 5);
		    }
		} else {
		    if($total[0]->get_user_flag == "A") {
                $this->excel->getActiveSheet()->freezePaneByColumnAndRow(6, 5);
		    } else {
		        $this->excel->getActiveSheet()->freezePaneByColumnAndRow(5, 5);
		    }
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

		if ($total[0]->get_user_flag == "A") {
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

		  if ($total[0]->get_user_flag == "A") {
    		  $this->excel->getActiveSheet()->mergeCells('G3:G4');
    		  $this->excel->getActiveSheet()->mergeCells('H3:O3');
    		  $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:H4');
    		  $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'I4:I4');
    		  $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J4:J4');
    		  $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K4:K4');
    	      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'L4:L4');
    	      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M4:O4');
    	      $this->excel->getActiveSheet()->mergeCells('P3:W3');
		  } else {
		      $this->excel->getActiveSheet()->mergeCells('G3:N3');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G4:G4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:H4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'I4:I4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J4:J4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K4:K4');
		      $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'L4:M4');
		      $this->excel->getActiveSheet()->mergeCells('O3:V3');
		  }

		  $daily_sub = 0;
		} else {
		    $this->excel->getActiveSheet()->mergeCells('A3:A4');
		    $this->excel->getActiveSheet()->mergeCells('B3:B4');
		    $this->excel->getActiveSheet()->mergeCells('C3:C4');
		    $this->excel->getActiveSheet()->mergeCells('D3:D4');
		    $this->excel->getActiveSheet()->mergeCells('E3:E4');
		    if ($total[0]->get_user_flag == "A") {
    		    $this->excel->getActiveSheet()->mergeCells('F3:F4');
    		    $this->excel->getActiveSheet()->mergeCells('G3:N3');
    		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G4:G4');
    		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H4:H4');
    		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'I4:I4');
    		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J4:J4');
    		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K4:K4');
    		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'L4:N4');
    		    $this->excel->getActiveSheet()->mergeCells('O3:V3');
		    } else {
		        $this->excel->getActiveSheet()->mergeCells('F3:M3');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'F4:F4');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G4:G4');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'H4:H4');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'I4:I4');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J4:J4');
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'K4:M4');
		        $this->excel->getActiveSheet()->mergeCells('N3:U3');
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

		if ($total[0]->get_user_flag == "A") {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow((6-$daily_sub), 3, '발송대기');
		}

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub-$author_sub), 3, '발 송 성 공');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$author_sub), 3, '발 송 실 패');

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub-$author_sub), 4, '알림톡');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub-$author_sub), 4, '이미지 알림톡');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub-$author_sub), 4, '친구톡(텍스트)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub-$author_sub), 4, '친구톡(이미지)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub-$author_sub), 4, '친구톡(와이드)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub-$author_sub), 4, 'WEB(C)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$author_sub), 4, 'WEB(C) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$author_sub), 4, 'WEB(C) MMS');

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$author_sub), 4, '알림톡');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((16-$daily_sub-$author_sub), 4, '이미지 알림톡');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((17-$daily_sub-$author_sub), 4, '친구톡(텍스트)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((18-$daily_sub-$author_sub), 4, '친구톡(이미지)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((19-$daily_sub-$author_sub), 4, '친구톡(와이드)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((20-$daily_sub-$author_sub), 4, 'WEB(C)');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((21-$daily_sub-$author_sub), 4, 'WEB(C) SMS');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((22-$daily_sub-$author_sub), 4, 'WEB(C) MMS');
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, 4, '015저가문자');
		//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, 4, '폰문자');

		$row = 5;
		$data_row = 0;
		$data_block_row = 0;
		$last_col_alp = "";
		$last_col_alp = "";
		if (empty($daily)) {
		    ($total[0]->get_user_flag == "A") ? $last_col_alp = "W" : $last_col_alp = "V";
		} else {
		    ($total[0]->get_user_flag == "A") ? $last_col_alp = "V" : $last_col_alp = "U";
		}

		foreach($value as $val) {
		    $data_row++;

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
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub-$author_sub), $row, $val->cnt_succ_kakao);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub-$author_sub), $row, $val->cnt_succ_kakao_img);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub-$author_sub), $row, $val->cnt_succ_friend);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub-$author_sub), $row, $val->cnt_succ_friend_img);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub-$author_sub), $row, $val->cnt_succ_friend_wide_img);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub-$author_sub), $row, $val->cnt_succ_smt);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$author_sub), $row, $val->cnt_succ_smt_sms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$author_sub), $row, $val->cnt_succ_smt_mms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$author_sub), $row, $val->cnt_fail_kakao);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((16-$daily_sub-$author_sub), $row, $val->cnt_fail_kakao_img);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((17-$daily_sub-$author_sub), $row, $val->cnt_fail_friend);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((18-$daily_sub-$author_sub), $row, $val->cnt_fail_friend_img);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((19-$daily_sub-$author_sub), $row, $val->cnt_fail_friend_wide_img);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((20-$daily_sub-$author_sub), $row, $val->cnt_fail_smt);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((21-$daily_sub-$author_sub), $row, $val->cnt_fail_smt_sms);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((22-$daily_sub-$author_sub), $row, $val->cnt_fail_smt_mms);

		    if(empty($daily)) {
		        if ($data_row % 2 == 0) { // 데이터 짝수 row
		            $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'edf0f5'))),'A'.$row.':'.$last_col_alp.$row);
		        }
		    } else {
		        if ($data_row % 2 == 0){
		            $data_block_row++;
		            if ($data_block_row % 2 == 0 && $data_block_row > 0) {
		                $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'edf0f5'))),'A'.($row-1).':'.$last_col_alp.$row);
		            }
		        }

		    }

			$row++;
		}

		if(empty($daily)) {
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD'))),'A'.$row.':'.$last_col_alp.$row);

		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '합 계');
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, '-');

		    if ($total[0]->get_user_flag == "A") {
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H'.$row.':H'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'I'.$row.':I'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J'.$row.':J'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K'.$row.':K'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'L'.$row.':L'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'M'.$row.':O'.$row);
		    } else {
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G'.$row.':G'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H'.$row.':H'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'I'.$row.':I'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J'.$row.':J'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K'.$row.':K'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'L'.$row.':M'.$row);
		    }
		} else {
		    $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD'))),'A'.$row.':'.$last_col_alp.$row);
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((1-$daily_sub), $row, '합 계');

		    if ($total[0]->get_user_flag == "A") {
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G'.$row.':G'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'H'.$row.':H'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'I'.$row.':I'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J'.$row.':J'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'K'.$row.':K'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'L'.$row.':N'.$row);
		    } else {
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'F'.$row.':F'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'G'.$row.':G'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'H'.$row.':H'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'I'.$row.':I'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faecc5'))),'J'.$row.':J'.$row);
		        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'dad9ff'))),'K'.$row.':M'.$row);
		    }
		}

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((2-$daily_sub), $row, $total[0]->get_statistics_cnt_total);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((3-$daily_sub), $row, $total[0]->get_statistics_cnt_succ_total);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((4-$daily_sub), $row, $total[0]->get_statistics_cnt_reserved);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((5-$daily_sub), $row, $total[0]->get_statistics_cnt_invalid_total);
		if($total[0]->get_user_flag == "A") {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow((6-$daily_sub), $row, $total[0]->get_statistics_cnt_wait_total);
		}
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((7-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_kakao);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((8-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_kakao_img);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((9-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_friend);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((10-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_friend_img);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((11-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_friend_wide_img);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((12-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((13-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt_sms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((14-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_succ_smt_mms);

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((15-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_kakao);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((16-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_kakao_img);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((17-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_friend);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((18-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_friend_img);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((19-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_friend_wide_img);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((20-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((21-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt_sms);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow((22-$daily_sub-$author_sub), $row, $total[0]->get_statistics_cnt_fail_smt_mms);

		if (empty($daily)) {
		    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
		        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		    ),	'A5:B'.$row);
		    if ($total[0]->get_user_flag == "A") {
		        $this->excel->getActiveSheet()->getStyle('A5:W'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:W'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    } else {
		        $this->excel->getActiveSheet()->getStyle('A5:V'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:V'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    }
		} else {
		    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
		        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		    ),	'A5:A'.$row);
		    if ($total[0]->get_user_flag == "A") {
		        $this->excel->getActiveSheet()->getStyle('A5:V'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:V'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    } else {
		        $this->excel->getActiveSheet()->getStyle('A5:U'.$row)->getNumberFormat()->setFormatCode('#,##0');
		        $this->excel->getActiveSheet()->getStyle('A3:U'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    }
		}

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

	private function index__($daily='') {
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
	        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'fae0d4'))),'I4:I4');
	        $this->excel->getActiveSheet()->duplicateStyleArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'faf4c0'))),'J4:J4');
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
