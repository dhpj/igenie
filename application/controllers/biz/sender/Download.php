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

	function getMessageTypeName($kind, $img)
	{
		if($kind=="ft" && ($img==null || empty($img))) { return "친구톡(텍스트)"; }
		if($kind=="ft" && $img!=null && !empty($img)) { return "친구톡(이미지)"; }
		if($kind=="at") { return "알림톡"; }
		if($kind=="pn") { return "폰문자"; }
		if($kind=="sm") { return "SMS"; }
		if($kind=="lm") { return "LMS"; }
		if($kind=="mm") { return "MMS"; }
		return $kind;
	}

	public function index()
	{
		$view = array();
		$view['view'] = array();
		$view['param']['search_key'] = $this->input->post("searchKey");
		$view['param']['search_type'] = $this->input->post("searchType");
		$view['param']['search_result'] = $this->input->post("searchResult");
		$view['param']['search_for'] = $this->input->post("searchStr");
		log_message("ERROR", "searchKey : ".$this->input->post("searchKey"));
		log_message("ERROR", "searchKey2 : ".$view['param']['search_key']);
		$param = array($view['param']['search_key']);
		$rs = $this->db->query("select a.*, b.mem_userid, b.mem_username, c.* from cb_wt_msg_sent a inner join cb_member b on a.mst_mem_id=b.mem_id left join cb_wt_send_profile c on a.mst_profile=c.spf_key where a.mst_id=?", $param)->row();

		$where = "";
		if($view['param']['search_type'] && $view['param']['search_type']!="all") { $where .= " and MESSAGE_TYPE=? "; array_push($param, $view['param']['search_type']); }
		
		// 2019.01.22 이수환 대기항목 삭제, 성공 = (중간 관리자, 일반 사용자일때), 상위관리자는 현행으로
		//if($view['param']['search_result'] && $view['param']['search_result']!="all") { $where .= " and ifnull((case when RESULT = 'Y' then
        //                   'Y'
        //                 when RESULT = 'N' and MESSAGE like '%수신대기%' then
        //                   'W'
        //                 else
        //                   RESULT
        //            end), 'N')=? "; array_push($param, $view['param']['search_result']); }
		if($view['param']['search_result'] && $view['param']['search_result']!="all") { 
		    if ($view['param']['search_result']!="YW") {
		        $where .= " and ifnull((case when RESULT = 'Y' then
                           'Y'
                         when RESULT = 'N' and MESSAGE like '%수신대기%' then
                           'W'
                         else
                           RESULT
                    end), 'N')=? "; 
		    } else {
		        $where .= " and ifnull((case when RESULT = 'Y' then
                           'YW'
                         when RESULT = 'N' and MESSAGE like '%수신대기%' then
                           'YW'
                         else
                           RESULT
                    end), 'N')=? "; 
		    }

		    array_push($param, $view['param']['search_result']);
		}
		
		if($view['param']['search_for']) { $where .= " and PHN like ? "; array_push($param, "%".$view['param']['search_for']."%"); }

		$sql = "
			select a.*,
                   (case when a.RESULT = 'Y' then
                           'Y'
                         when a.RESULT = 'N' and a.MESSAGE like  '%수신대기%' then
                           'W'
                         else
                           a.RESULT
                    end) as RESULT_MSG
			from cb_msg_".$rs->mem_userid." a
			where REMARK4=? ".$where." order by MSGID desc" ;

		$list = $this->db->query($sql, $param)->result();
		
		//log_message('ERROR', 'SQL : '.$sql);
		/*--------------------------------------
		 엑셀 저장
		----------------------------------------*/

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
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A1:G1');
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'height'=>12 )
			),	'A2:G2');

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
			),	'A4:G4');

		$this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
		$this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(60);
		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "일련번호");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "발신/예약일자");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "업체명");
		$this->excel->getActiveSheet()->mergeCells('D1:F1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "메시지내용");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "발송개수");

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, ($rs->mst_id * 1));
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, $rs->mst_datetime.(($rs->mst_reserved_dt!="00000000000000") ? "\n".$this->funn->format_date($rs->mst_reserved_dt, '-', 14) : ""));
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 2, ($rs->spf_company) ? $rs->spf_company."(".$rs->spf_friend.")" : $rs->mem_username);
		$this->excel->getActiveSheet()->mergeCells('D2:F2');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 2, $rs->mst_content);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 2, $rs->mst_qty);

		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, "순번");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, "발신번호");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, "수신번호");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, "발신방법");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, "메세지유형");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, "발송결과");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, "오류메세지");
		//$this->excel->getActiveSheet()->freezePane('A5');
		$cnt = 1;
		$row = 5;
	
		foreach($list as $r) {
		    
		    $type = "";
		    if($r->MESSAGE_TYPE=="at") {
		        $type = "AT";
		    } else if($r->MESSAGE_TYPE=="ft") {
		        $type = "FT";
		    } else if ($r->SMS_KIND=="S" && !is_null(($r->SMS_KIND))) {
		        $type = "SMS";
		    } else {
		        $type = "LMS";
		    }
		    
		    $result_msg = "";
		    // 2019.01.22 이수환 대기항목 삭제, 성공 = 대기 + 성공(중간 관리자, 일반 사용자일때), 상위관리자는 현행으로 $error_msg 추가 및 수정
		    //if($r->RESULT_MSG=='Y') {
		    //    $result_msg = "성공";
		    //} else if($r->RESULT_MSG=='W') {
		    //    $result_msg = "대기";
		    //} else {
		    //    $result_msg = "실패";
		    //}
		    $error_msg = "";
		    if($r->RESULT_MSG=='Y') {
		        $result_msg = "성공";
		        $error_msg = "";
		    } else if($r->RESULT_MSG=='W') {
		        // 2019.01.22 이수환 대기항목 삭제, 성공 = 대기 + 성공(중간 관리자, 일반 사용자일때), 상위관리자는 현행으로
		        //$result_msg = "대기";
		        if ($this->member->item("mem_level") >= 100) {
		            $result_msg = "대기";
		            $error_msg = $this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE);
		        } else {
		            $result_msg = "성공";
		            $error_msg = "";
		        }
		        
		    } else {
		        $result_msg = "실패";
		        $error_msg = $this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE);
		    }

		    // 수신자 전화번호 국번 별표 처리
		    $phoneNoOri = "0".substr($r->PHN, 2);
		    $phoneNo = "";
		    
		    if (strlen($phoneNoOri) > 10) {
		        $phoneNo = substr($phoneNoOri, 0, 3)."****".substr($phoneNoOri, 7);
		    } else {
		        $phoneNo = substr($phoneNoOri, 0, 3)."***".substr($phoneNoOri, 6);
		    }
		    
		    
		    if($this->member->item('mem_id') != '3' && $this->member->item('mem_id') != '6' && $this->member->item('mem_id') != '2') {
		        $login_stack = $this->session->userdata('login_stack');
		        if($this->session->userdata('login_stack')) {
		            $login_stack = $this->session->userdata('login_stack');
		            if($login_stack[0] == '3' || $login_stack[0] == '6' || $login_stack[0] == '2') {
		                $phoneNo = $phoneNoOri;
		            }
		        }
		    } else {
		        $phoneNo = $phoneNoOri;
		    }
		    
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $cnt);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->SMS_SENDER);
			//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, "0".substr($r->PHN, 2));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $phoneNo);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $this->funn->get_msg_tbl_kind($r->MESSAGE_TYPE));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $type);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result_msg);
			// 2019.01.22 이수환 대기항목 삭제, 성공 = 대기 + 성공(중간 관리자, 일반 사용자일때), 상위관리자는 현행으로
			//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, ($r->RESULT=="Y") ? "" : $this->funn->get_phone_result_error($r->MESSAGE_TYPE, $r->MESSAGE));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $error_msg);
			
			$row++;
			$cnt++;
		}

		// 파일로 내보낸다. 파일명은 'filename.xls' 이다.
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="result_list.xls"');
		header('Cache-Control: max-age=0');

 

		// Excel5 포맷(excel 2003 .XLS file)으로 저장한다. 
		// 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		// 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
		$objWriter->save('php://output');

	}
}
?>