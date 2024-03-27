<?php
require_once APPPATH.'/third_party/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Summary2 extends CB_Controller {
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
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['param']['userid'] = (!$this->input->post('userid') || $this->input->post('userid')=="ALL") ? ($this->member->item('mem_level')>=100) ? "dhn" : $this->member->item('mem_userid') : $this->input->post('userid');
        //log_message("ERROR", $view['param']['userid']);
        $view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m');
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $sql = "select * from cb_wt_adm_price";
        $price = $this->db->query($sql)->result();
        $view['adm_phn'] = array();
        $view['adm_sms'] = array();
        $view['adm_lms'] = array();
        $view['adm_mms'] = array();
        foreach($price as $p) {
            $view['adm_phn'][$p->adm_agent] = $p->adm_price_phn;
            $view['adm_sms'][$p->adm_agent] = $p->adm_price_sms;
            $view['adm_lms'][$p->adm_agent] = $p->adm_price_lms;
            $view['adm_mms'][$p->adm_agent] = $p->adm_price_mms;
        }
        
        $view['users'] = $this->Biz_model->get_child_member($this->member->item('mem_id'), 10);
        
        $mem = $this->Biz_model->get_member($view['param']['userid'], true);
        //echo '<pre> :: ';print_r($mem); exit;
        if(!$mem || $mem->mem_userid!=$view['param']['userid']) { show_404(); exit; }
        $view['member'] = $mem;
        
        $param = array($view['param']['startDate'].'-01 00:00:00', $view['param']['startDate'].'-31 23:59:59');
        /* 전체 조회 일 경우 중간 관리자 정산 내역 표시
         * 최 상위 관리자 조회 일때..
         * */
        //log_message("ERROR", "END USEDR / ".$this->input->post('userid'));
        
        $skin_type = "";
        if(($view['param']['userid'] == 'dhn' ))
        {
            $skin_type = "ALL";
            $sql = "
                    SELECT
                        period_name,
                        manager_mem_id,
                        (select mem_username from cb_member cm where cm.mem_id = manager_mem_id) as vendor,
                        (select mem_userid from cb_member cm where cm.mem_id = manager_mem_id) as mem_userid,
                       -- end_mem_id,
                           ( select sum(dep_deposit)
                            from cb_deposit cd inner join
                                    cb_member cm on cd.mem_id = cm.mem_id
                    	  where exists ( select 1 from cb_member_register cmr
                                            where cmr.mrg_recommend_mem_id = manager_mem_id
                                              and cmr.mem_id = cm.mem_id)
                              and DATE_FORMAT(cd.dep_deposit_datetime, '%Y-%m')  = period_name) as deposit,
                
                        sum(at) as at,
                        sum(at * at_price) as at_amt,
                        sum(at_amount) as at_amount,
                        sum(ft) as ft,
                        sum(ft*ft_price) as ft_amt,
                        sum(ft_amount) as ft_amount,
                        sum(ft_img) as ft_img,
                        sum(ft_img * ft_img_price) as ft_img_amt,
                        sum(ft_img_amount) as ft_img_amount,
                        sum(sms) as sms,
                        sum(sms * sms_price) as sms_amt,
                        sum(lms) as lms,
                        sum(lms * lms_price) as lms_amt,
                        sum(phn) as phn,
                        sum(phn * phn_price) as phn_amt,
                        sum(phn_amount) as phn_amount,
                        sum(grs) as grs,
                        sum(grs * grs_price) as grs_amt,
                        sum(grs_amount) as grs_amount,
                        sum(grs_sms) as grs_sms,
                        sum(grs_sms * grs_sms_price) as grs_sms_amt,
                        sum(grs_sms_amount) as grs_sms_amount,
                        sum(nas) as nas,
                        sum(nas * nas_price) as nas_amt,
                        sum(nas_amount) as nas_amount,
                        sum(nas_sms) as nas_sms,
                        sum(nas_sms * nas_sms_price) as nas_sms_amt,
                        sum(nas_sms_amount) as nas_sms_amount,
                        sum(f_015) as f_015,
                        sum(f_015 * f_015_price) as f_015_amt,
                        sum(f_015_amount) as f_015_amount,
                        base_up_ft,
                        base_up_ft_img,
                        base_up_at,
                        base_up_grs,
                        base_up_grs_sms,
                        base_up_nas,
                        base_up_nas_sms,
                        base_up_phn,
                        base_up_015
                    FROM
                        cb_monthly_report cmr
                    WHERE
                        period_name = '".$view['param']['startDate']."'
                    group by manager_mem_id ,
                        base_up_ft,
                        base_up_ft_img,
                        base_up_at,
                        base_up_grs,
                        base_up_grs_sms,
                        base_up_nas,
                        base_up_nas_sms,
                        base_up_phn,
                        base_up_015
                    ORDER BY period_name , manager_mem_id , end_mem_id
                    ";
            $list = $this->db->query($sql)->result();
            $view['list'] = $list;
        } else
        /* 중간 관리자가 조회 조건일 경우 하위 업체 정산 내역 표시 */
        {
            $skin_type = "END";
            //log_message("ERROR", "END USEDR / ".$this->input->post('userid'));
            $sql = "
                    SELECT
                        period_name,
                        manager_mem_id,
                        cm.mem_userid,
                        end_mem_id,
                        (select mem_username from cb_member cm where cm.mem_id = end_mem_id) as vendor,
                        ( select sum(dep_deposit)
                            from cb_deposit cd
                    	  where cd.mem_id = end_mem_id
                              and DATE_FORMAT(cd.dep_deposit_datetime, '%Y-%m')  = period_name) as deposit,
                        at,
                        at_price,
                        at * at_price as at_amt,
                        ft,
                        ft_price,
                        ft*ft_price as ft_amt,
                        ft_img,
                        ft_img_price,
                        ft_img * ft_img_price as ft_img_amt,
                        sms,
                        sms_price,
                        sms * sms_price as sms_amt,
                        lms,
                        lms_price,
                        lms * lms_price as lms_amt,
                        phn,
                        phn_price,
                        phn * phn_price as phn_amt,
                        grs,
                        grs_price,
                        grs * grs_price as grs_amt,
                        grs_sms,
                        grs_sms_price,
                        grs_sms * grs_sms_price as grs_sms_amt,
                        nas,
                        nas_price,
                        nas * nas_price as nas_amt,
                        nas_sms,
                        nas_sms_price,
                        nas_sms * nas_sms_price as nas_sms_amt,
                        f_015,
                        f_015_price,
                        f_015 * f_015_price as f_015_amt
                    FROM
                        cb_monthly_report cmr inner join
                        cb_member cm on cm.mem_id = cmr.manager_mem_id
                    WHERE
                        period_name = '".$view['param']['startDate']."'
                        and cm.mem_userid = '".$this->input->post('userid')."'
                    ORDER BY period_name , manager_mem_id , end_mem_id
                    ";
            $list = $this->db->query($sql)->result();
            $view['list'] = $list;
        }
        
        
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
            'path' => 'biz/manager/summary2',
            'layout' => 'layout',
            'skin' => (($skin_type == "ALL") ? 'admin' : "offer"),
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
    
    public function download2($dl_type) {
       
        log_message("ERROR", "1.===============================");
        $datas = array(
            array('name' => '김정호', 'tel' => '010-1234-1234', 'bank' => '국민은행'),
            array('name' => '홍길동', 'tel' => '010-5678-5678', 'bank' => '한국은행')
        );
        
        $cells = array(
            'A' => array(15, 'name', '신청자명'),
            'B' => array(20, 'tel',  '전화번호'),
            'C' => array(20, 'bank', '은행명')
        );
        

        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        foreach ($cells as $key => $val) {
            $cellName = $key.'1';
            
            $sheet->getColumnDimension($key)->setWidth($val[0]);
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->setCellValue($cellName, $val[2]);
            $sheet->getStyle($cellName)->getFont()->setBold(true);
            $sheet->getStyle($cellName)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cellName)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }
        
        for ($i = 2; $row = array_shift($datas); $i++) {
            foreach ($cells as $key => $val) {
                $sheet->setCellValue($key.$i, $row[$val[1]]);
            }
        }
        
        $filename = 'excel';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        log_message("ERROR", "2.===============================");
    }
    
    public function download($dl_type)
    {
        $value = json_decode($this->input->post('value'));
        $total = json_decode($this->input->post('total'));
        
        // 라이브러리를 로드한다.
        $this->load->library('excel');
        
        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');
        
        log_message("ERROR", "DL TYPE : ".$dl_type);
        
        if ($dl_type == "admin")
        {
            // 필드명을 기록한다.
            // 글꼴 및 정렬
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A1:N1');
            
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A3:N3');
            
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
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
            
            $this->excel->getActiveSheet()->mergeCells('A1:N1');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "발 송 내 역  정 산");
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '업체명');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '충전');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '알림톡');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '친구톡(텍스트)');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '친구톡(이미지)');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, 'WEB(A)');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, 'WEB(A) SMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'WEB(B)');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, 'WEB(B) SMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 3, '015 저가문자');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 3, '폰문자');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 3, '발송합계');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, '전송매출');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 3, '수익금액');
            
            $row = 4;
            foreach($value as $val) {
                // 데이터를 읽어서 순차로 기록한다.
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->vendor);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->deposit);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val->at);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->ft);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val->ft_img);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val->grs );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val->grs_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val->nas );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val->nas_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $val->f_015 );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $val->phn );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->row_sum );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->row_amount );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->row_margin );
                $row++;
            }

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "합 계");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $total[0]->deposit);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $total[0]->at);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $total[0]->ft);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $total[0]->ft_img);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $total[0]->grs );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $total[0]->grs_sms);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $total[0]->nas );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $total[0]->nas_sms);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $total[0]->f_015 );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $total[0]->phn );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $total[0]->row_sum );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $total[0]->row_amount );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $total[0]->row_margin );

            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A'.$row.':N'.$row);
            
            $row++;
            
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A4:A'.$row);
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'B4:N'.$row);


        } else 
        {
            // 필드명을 기록한다.
            // 글꼴 및 정렬
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A1:AC1');
            
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A3:AC4');
            
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
            
            $this->excel->getActiveSheet()->mergeCells('A1:AC1');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "발 송 내 역  정 산");
            
            $this->excel->getActiveSheet()->mergeCells('A3:A4');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '업체명');
            
            $this->excel->getActiveSheet()->mergeCells('B3:D3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '알림톡');
            
            $this->excel->getActiveSheet()->mergeCells('E3:G3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '친구톡(텍스트)');
            
            $this->excel->getActiveSheet()->mergeCells('H3:J3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, '친구톡(이미지)');
            $this->excel->getActiveSheet()->mergeCells('K3:M3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 3, 'WEB(A)');
            $this->excel->getActiveSheet()->mergeCells('N3:P3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 3, 'WEB(A) SMS');
            $this->excel->getActiveSheet()->mergeCells('Q3:S3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, 3, 'WEB(B)');
            $this->excel->getActiveSheet()->mergeCells('T3:V3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, 3, 'WEB(B) SMS');
            $this->excel->getActiveSheet()->mergeCells('W3:Y3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, 3, '015 저가문자');
            $this->excel->getActiveSheet()->mergeCells('Z3:AB3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(25, 3, '폰문자');
            $this->excel->getActiveSheet()->mergeCells('AC3:AC4');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(28, 3, '총수익');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(24, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(25, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, 4, '금액');
             
            $row = 5;
            foreach($value as $val) {
                // 데이터를 읽어서 순차로 기록한다.
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->vendor);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->at);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val->at_price);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->at_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val->ft);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val->ft_price);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val->ft_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val->ft_img);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val->ft_img_price);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $val->ft_img_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $val->grs);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->grs_price);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->grs_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->grs_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $val->grs_sms_price);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $val->grs_sms_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $val->nas);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $val->nas_price);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $val->nas_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $val->nas_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $val->nas_sms_price);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $val->nas_sms_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $val->f_015);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, $val->f_015_price);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(24, $row, $val->f_015_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(25, $row, $val->phn);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $val->phn_price);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $val->phn_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(28, $row, $val->row_margin);
                $row++;
            }
             
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "합 계");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $total[0]->at);
            $this->excel->getActiveSheet()->mergeCells('C'.$row.':D'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $total[0]->at_amt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $total[0]->ft);
            $this->excel->getActiveSheet()->mergeCells('F'.$row.':G'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $total[0]->ft_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $total[0]->ft_img);
            $this->excel->getActiveSheet()->mergeCells('I'.$row.':J'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $total[0]->ft_img_amt);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $total[0]->grs);
            $this->excel->getActiveSheet()->mergeCells('L'.$row.':M'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $total[0]->grs_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $total[0]->grs_sms);
            $this->excel->getActiveSheet()->mergeCells('O'.$row.':P'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $total[0]->grs_sms_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $total[0]->nas);
            $this->excel->getActiveSheet()->mergeCells('R'.$row.':S'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $total[0]->nas_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $total[0]->nas_sms);
            $this->excel->getActiveSheet()->mergeCells('U'.$row.':V'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $total[0]->nas_sms_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $total[0]->f_015);
            $this->excel->getActiveSheet()->mergeCells('X'.$row.':Y'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, $total[0]->f_015_amt);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(25, $row, $total[0]->phn);
            $this->excel->getActiveSheet()->mergeCells('AA'.$row.':AB'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $total[0]->phn_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(28, $row, $total[0]->row_sum_margin );

            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A'.$row.':AC'.$row);
            
            $row++;
            
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A5:A'.$row);
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'B5:AC'.$row);
        }
        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="summary.xls"');
        header('Cache-Control: max-age=0');
        
        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        
        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
    }
    
    public function month_summary($month) {
        
        $del_sql = "delete FROM cb_monthly_report where period_name = '".$month."'";
        $this->db->query($del_sql);
        
        $main_sql = "
                     select  mem_id
							,mad_price_ft
							,mad_price_ft_img
							,mad_price_at
							,mad_price_sms
							,mad_price_lms
							,mad_price_mms
							,mad_price_phn
							,mad_price_grs_sms
							,mad_price_nas_sms
							,mad_price_grs
							,mad_price_nas
							,mad_price_015
    	 	           from cb_member cm left join
    		                cb_wt_member_addon ma on cm.mem_id=ma.mad_mem_id
    	  	          where cm.mem_level = '50'
    		            and exists (select 1
                                      from cb_member_register cmr inner join
    					                   cb_wt_msg_sent wms on cmr.mem_id = wms.mst_mem_id
    								 where cmr.mrg_recommend_mem_id = cm.mem_id
    								   and DATE_FORMAT(wms.mst_datetime, '%Y-%m') = '".$month."'
    												 )
                     order by mem_id
                    ";
        
        //log_message("ERROR", "MAIN = ".$main_sql);
        $main_list = $this->db->query($main_sql)->result();
        foreach ($main_list as $r) {
            $this->end_user_summary($month, $r, $r->mem_id);
        }
        $this->index();
    }
    
    public function end_user_summary($month, $main_row, $mem_id) {
        $sub_sql = "
             select  mem_id
    				,mad_price_ft
    				,mad_price_ft_img
    				,mad_price_at
    				,mad_price_sms
    				,mad_price_lms
    				,mad_price_mms
    				,mad_price_phn
    				,mad_price_grs_sms
    				,mad_price_nas_sms
    				,mad_price_grs
    				,mad_price_nas
    				,mad_price_015
                from cb_member cm left join
                     cb_wt_member_addon ma on cm.mem_id=ma.mad_mem_id
               where exists (select 1
                               from cb_member_register cmr
        					  where cmr.mrg_recommend_mem_id = '".$mem_id."'
        					    and cmr.mem_id = cm.mem_id)
                 and not exists ( select 1
                                    from cb_monthly_report cmr
                                   where cmr.period_name = '".$month."'
                                     and cmr.end_mem_id =  cm.mem_id )
               order by mem_id
                    ";
        
        //log_message("ERROR", "SUB = ".$sub_sql);
        $sub_list = $this->db->query($sub_sql)->result();
        foreach ($sub_list as $r) {
            $sub_check = "
                        select count(1) as cnt
                          from cb_member_register cmr
                         where cmr.mrg_recommend_mem_id = '".$r->mem_id."'
                           and cmr.mem_id <> cmr.mrg_recommend_mem_id
                         ";
            //log_message("ERROR", "SQL = ".$sub_check);
            $row_cnt = $this->db->query($sub_check)->row();
            if($row_cnt->cnt > 0 ) {
                $this->end_user_summary($month, $main_row, $r->mem_id);
            }
            
            $send_data_sql = "
                            select sum(wms.mst_ft) as qty_ft
                    		      ,sum(wms.mst_ft_img) as qty_ft_img
                    		      ,sum(wms.mst_at) as qty_at
                    			  ,sum(wms.mst_sms) as qty_sms
                    			  ,sum(wms.mst_lms) as qty_lms
                    			  ,sum(wms.mst_phn) as qty_phn
                    			  ,sum(wms.mst_grs) as qty_grs
                    			  ,sum(wms.mst_grs_sms) as qty_grs_sms
                    			  ,sum(wms.mst_nas) as qty_nas
                    			  ,sum(wms.mst_nas_sms) as qty_nas_sms
                    			  ,sum(wms.mst_015) as qty_015
                    			  ,count(1) as cnt
                    		 from cb_wt_msg_sent wms
                    		where wms.mst_mem_id = '".$r->mem_id."'
                    		  and DATE_FORMAT(wms.mst_datetime, '%Y-%m') = '".$month."'
                            		 ";
            //log_message("ERROR", "send data = ".$send_data_sql);
            $send_data = $this->db->query($send_data_sql)->row();
            
            if($send_data->cnt > 0) {
                $report_Data = array();
                
                $report_Data['period_name'] = $month;
                $report_Data['manager_mem_id'] = $main_row->mem_id;
                $report_Data['end_mem_id'] = $r->mem_id;
                $report_Data['ft'] = $send_data->qty_ft;
                $report_Data['ft_price'] = ($r->mad_price_ft - $main_row->mad_price_ft);
                $report_Data['ft_amount'] = ($r->mad_price_ft * $send_data->qty_ft);
                $report_Data['ft_img'] = $send_data->qty_ft_img;
                $report_Data['ft_img_price'] = ($r->mad_price_ft_img - $main_row->mad_price_ft_img);
                $report_Data['ft_img_amount'] = ($r->mad_price_ft_img * $send_data->qty_ft_img);
                $report_Data['at'] = $send_data->qty_at;
                $report_Data['at_price'] = ($r->mad_price_at - $main_row->mad_price_at);
                $report_Data['at_amount'] = ($r->mad_price_at * $send_data->qty_at);
                $report_Data['sms'] = $send_data->qty_sms;
                $report_Data['sms_price'] = ($r->mad_price_sms - $main_row->mad_price_sms);
                $report_Data['lms'] = $send_data->qty_lms;
                $report_Data['lms_price'] = ($r->mad_price_lms - $main_row->mad_price_lms);
                $report_Data['phn'] = $send_data->qty_phn;
                $report_Data['phn_price'] = ($r->mad_price_phn - $main_row->mad_price_phn);
                $report_Data['phn_amount'] = ($r->mad_price_phn * $send_data->qty_phn);
                $report_Data['grs'] = $send_data->qty_grs;
                $report_Data['grs_price'] = ($r->mad_price_grs - $main_row->mad_price_grs);
                $report_Data['grs_amount'] = ($r->mad_price_grs * $send_data->qty_grs);
                $report_Data['grs_sms'] = $send_data->qty_grs_sms;
                $report_Data['grs_sms_price'] = ($r->mad_price_grs_sms - $main_row->mad_price_grs_sms);
                $report_Data['grs_sms_amount'] = ($r->mad_price_grs_sms * $send_data->qty_grs_sms);
                $report_Data['nas'] = $send_data->qty_nas;
                $report_Data['nas_price'] = ($r->mad_price_nas - $main_row->mad_price_nas);
                $report_Data['nas_amount'] = ($r->mad_price_nas * $send_data->qty_nas);
                $report_Data['nas_sms'] =$send_data->qty_nas_sms ;
                $report_Data['nas_sms_price'] = ($r->mad_price_nas_sms - $main_row->mad_price_nas_sms);
                $report_Data['nas_sms_amount'] = ($r->mad_price_nas_sms * $send_data->qty_nas_sms);
                $report_Data['f_015'] = $send_data->qty_015;
                $report_Data['f_015_price'] = ($r->mad_price_015 - $main_row->mad_price_015);
                $report_Data['f_015_amount'] = ($r->mad_price_015 * $send_data->qty_015);
                $report_Data['base_up_ft'] = $this->Biz_model->base_price_ft;
                $report_Data['base_up_ft_img'] = $this->Biz_model->base_price_ft_img;
                $report_Data['base_up_at'] = $this->Biz_model->base_price_at;
                $report_Data['base_up_grs'] = $this->Biz_model->base_price_grs;
                $report_Data['base_up_grs_sms'] = $this->Biz_model->base_price_grs_sms;
                $report_Data['base_up_nas'] = $this->Biz_model->base_price_nas;
                $report_Data['base_up_nas_sms'] = $this->Biz_model->base_price_nas_sms;
                $report_Data['base_up_015'] = $this->Biz_model->base_price_015;
                $report_Data['base_up_phn'] = $this->Biz_model->base_price_phn;
                
                $this->db->insert("cb_monthly_report", $report_Data);
                log_message("ERROR", $this->db->error()['message']);
                unset($report_Data);
            }
        }
    }
}
?>