<?php 
@set_time_limit(0);
require_once '/var/www/igenie/application/third_party/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// 전단지 주문 Inicis 결제 통계 및 정산 관련 모듈 포함

class Inicis extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz');
    
    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

    /**
     * 전단지 Server DB(Inicis DB) 연결 설정.
     */
    protected $inicisDB = $this->load->database("218g", TRUE); // 전단지 서버 DB
    
    /**
     * Inicis 생성자
     */
    function __construct()
    {
        parent::__construct();
        
        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring'));
    }
    
    /**
     * Inicis 결재 목록
     */
    public function payment_list()
    {
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m-d', strtotime(date('Y-m-d')."-2week"));   // 검색 시작일
        $view['param']['endDate'] = ($this->input->post('endDate')) ? $this->input->post('endDate') : date('Y-m-d');                                            // 검색 종료일
        $view['param']['mem_id'] = ($this->input->post('mem_id')) ? $this->input->post('mem_id') : 'ALL';                                                       // 검색 파트너
        $view['param']['payment_type'] = ($this->input->post('payment_type')) ? $this->input->post('payment_type') : 'ALL'; // 검색 결재타입(ALL : 전체, PC: 부분취소, AC: 전체 취소, C: 취소전체(부분취소, 전체취소 모두))
        $view['param']['settlement'] = ($this->input->post('settlement')) ? $this->input->post('settlement') : 'ALL';   // 정산 상태 (ALL: 전체, SN: 미정산, SA: 정산)

        $paymentSql = "
SELECT coi.mem_id, 			-- 마트 내부ID 
	csmd.smd_mem_username, 	-- 마트명
	coi.server_alias, 		-- 발송 서버
	coi.order_id, 			-- 주문 ID
	coi.orderno, 			-- 주문 번호
	coi.inicis_id, 			-- inicis 결재 ID
	coi.tid,				-- 결재 TID
	coi.code, 				-- 전단지 코드
	coi.p_amt, 				-- 결재 금액
	coi.create_datetime, 	-- 결재 일자
	coir.flag, 				-- 결재 취소 A : 전체취소  P : 부분취소
	coir.tid, 				-- 결재 취소 (부분취소)부부취소TID
	coir.prtcTid,			-- 결재 취소 (전체취소, 부분취소)원거래TID 
	coir.resultCode, 		-- 결재 취소 결과코드[00:성공, 그외 실패]
	coir.resultMsg, 		-- 결재 취소 결과메세지
	coir.prtcPrice, 		-- 결재 취소 (부분취소)부분취소금액
	coir.prtcRemains, 		-- 결재 취소 (부분취소)부분취소 후 남은금액
	coir.prtcCnt, 			-- 결재 취소 (부분취소)부분취소 요청 횟수
	coir.prtcType, 			-- 결재 취소 (부분취소)부분취소 구분 [0:재승인방식, 1:부분취소]
	coir.create_datetime,	-- 결재 취소 일자
	CASE WHEN coir.flag IS NULL THEN coi.p_amt ELSE coir.prtcRemains END AS final_price -- 부분 취소 전체 취소 후 남은 금액.
FROM smart.cb_order_inicis coi 
	LEFT JOIN smart.cb_order_inicis_refund coir 
		ON coi.mem_id=coir.mem_id 
		AND coi.order_id = coir.order_id 
		AND coir.resultCode='00' 
	LEFT JOIN smart.cb_shop_member_data csmd 
		ON coi.mem_id = csmd.smd_mem_id 
		AND coi.server_alias = csmd.smd_from
WHERE coi.create_datetime BETWEEN '".$view['param']['startDate']." 00:00:00' AND '".$view['param']['endDate']." 23:59:59'
	AND coi.inicis_id = 'dhncorp002'
	AND coi.server_alias = '133g' ";
        
        if ($view['param']['mem_id'] != 'ALL') {
            $paymentSql .= "
    AND coi.mem_id = ".$view['param']['mem_id'];
        }
        
        $paymentSql .= "
ORDER BY coi.create_datetime DESC, coir.create_datetime DESC;";
        
        
//         log_message("ERROR","Inicis > payment_list() >  paymentSql: ". $paymentSql);
//         $view['$payment_list'] = $sw_db->query($paymentSql)->result();
        
//         $view['view']['canonical'] = site_url();
        
//         $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
//         $page_title = $this->cbconfig->item('site_meta_title_main');
//         $meta_description = $this->cbconfig->item('site_meta_description_main');
//         $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
//         $meta_author = $this->cbconfig->item('site_meta_author_main');
//         $page_name = $this->cbconfig->item('site_page_name_main');
        
//         $layoutconfig = array(
//             'path' => 'inicis',
//             'layout' => 'layout',
//             'skin' => 'payment_list',
//             'layout_dir' => $this->cbconfig->item('layout_main'),
//             'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_main'),
//             'use_sidebar' => $this->cbconfig->item('sidebar_main'),
//             'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
//             'skin_dir' => $this->cbconfig->item('skin_main'),
//             'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_main'),
//             'page_title' => $page_title,
//             'meta_description' => $meta_description,
//             'meta_keywords' => $meta_keywords,
//             'meta_author' => $meta_author,
//             'page_name' => $page_name,
//         );
        
//         $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
//         $this->data = $view;
//         $this->layout = element('layout_skin_file', element('layout', $view));
//         $this->view = element('view_skin_file', element('layout', $view));
        
        
        
    }
}
?>