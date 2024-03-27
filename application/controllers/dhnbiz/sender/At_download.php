<?php
class At_download extends CB_Controller {
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
		$tmpli_cont = $this->input->post('tmpli_cont');
		$buttons = json_decode($this->input->post('buttons'));
		$policy_sms = $this->input->post('policy_sms');

		$Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

		$tmp_id = $this->input->post('tmp_id');
		$ids = explode(',', $tmp_id);

		// 라이브러리를 로드한다.
		$this->load->library('excel');

		// 시트를 지정한다.
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Sheet1');

		// 필드명을 기록한다.
		// 글꼴 및 정렬
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FF0000')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A1:B1');
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'height'=>12 )
			),	'A2:B2');

		$this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
		$this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(200);
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);

		$this->excel->getActiveSheet()->mergeCells('A1:B1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "'* 필독 가이드 (최대 6만건까지 가능)");

		$this->excel->getActiveSheet()->mergeCells('A2:B2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, "'* 전화번호란에는 '-'없이 숫자만 입력\r\n* 템플릿 내용은 변수를 변경하여 완전한 템플릿 메시지 내용으로 모두 입력\r\n* 버튼의 경우 해당 템플릿 버튼 중 WL(웹링크)/AL(앱링크) 링크 부분만 변수 변경하여 입력(최대 255자)\r\n\r\n- WL(웹링크) : 모바일 링크 입력, pc 링크는 등록시에 입력한 경우에만 입력 가능\r\n- AL(앱링크) : android 스킴, ios 스킴 입력\r\n\r\n*** 엑셀 서식을 변경하여 업로드할 경우 데이터가 다르게 보내질 수 있습니다. 서식 그대로 발송해주시기 바랍니다.");
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
		$this->excel->getActiveSheet()->mergeCells('A3:A4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '전화번호');
		$this->excel->getActiveSheet()->mergeCells('B3:B4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '템플릿내용');
		$hcol = 2;
		$col = 2;
		$bcnt = 1;

	
		foreach($buttons as $btn) {
			if($btn->linkType=="WL") {
				if($btn->linkMo && $btn->linkPc) {
					$this->excel->getActiveSheet()->mergeCells($Acol[$hcol++].'3:'.$Acol[$hcol++].'3');
				} else { $hcol++; }
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 3, '버튼'.$bcnt);
				if($btn->linkMo) {
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 4, '모바일링크');
				}
				if($btn->linkPc) {
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 4, 'PC링크');
				}
				$bcnt++;
				continue;
			}
			if($btn->linkType=="AL") {
				if($btn->linkIos && $btn->linkAnd) {
					$this->excel->getActiveSheet()->mergeCells($Acol[$hcol++].'3:'.$Acol[$hcol++].'3');
				} else { $hcol++; }
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 3, '버튼'.$bcnt);
				if($btn->linkAnd) {
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 4, '안드로이드링크');
				}
				if($btn->linkIos) {
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 4, 'IOS링크');
				}
				$bcnt++;
				continue;
			}
			if($btn->linkType=="DS") {
				$this->excel->getActiveSheet()->mergeCells($Acol[$hcol++].'3:'.$Acol[$hcol++].'3');
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 3, '버튼'.$bcnt);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 4, '택배사코드');
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 4, '운송장번호');
				$bcnt++;
				continue;
			}
			if($btn->linkType=="BK") {
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 3, '버튼'.$bcnt);
				$hcol++;
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 4, '버튼명');
				$bcnt++;
				continue;
			}
			if($btn->linkType=="MD") {
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 3, '버튼'.$bcnt);
				$hcol++;
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 4, '쇼핑몰코드');
				$bcnt++;
				continue;
			}
		}
		$this->excel->getActiveSheet()->mergeCells($Acol[$hcol].'3:'.$Acol[$hcol].'4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 3, 'LMS내용');
		$hcol++;
		$this->excel->getActiveSheet()->mergeCells($Acol[$hcol].'3:'.$Acol[$hcol].'4');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col+1, 3, 'LMS회신번호');

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A3:'.$Acol[$hcol].'4');

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 5, '01012345678');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, $tmpli_cont);
		$col = 2;
		foreach($buttons as $btn) {
			if($btn->linkType=="WL") {
				if($btn->linkMo) {
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 5, $btn->linkMo);
				}
				if($btn->linkPc) {
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 5, $btn->linkPc);
				}
				continue;
			}
			if($btn->linkType=="AL") {
				if($btn->linkAnd) {
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 5, $btn->linkAnd);
				}
				if($btn->linkIos) {
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 5, $btn->linkIos);
				}
				continue;
			}
			if($btn->linkType=="DS") {
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 5, '택배사코드');
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 5, '운송장번호');
				continue;
			}
			if($btn->linkType=="BK") {
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 5, '버튼명');
				continue;
			}
			if($btn->linkType=="MD") {
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, 5, '쇼핑몰코드');
				continue;
			}
		}

		// 파일로 내보낸다. 파일명은 'filename.xls' 이다.
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="number_list.xls"');
		header('Cache-Control: max-age=0');

 

		// Excel5 포맷(excel 2003 .XLS file)으로 저장한다. 
		// 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		// 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
		$objWriter->save('php://output');

	}
}
?>