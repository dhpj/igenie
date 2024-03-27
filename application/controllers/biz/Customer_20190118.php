<?php
class Customer extends CB_Controller {
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
    
    public function lists()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['view']['canonical'] = site_url();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        $sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid');
        $view['kind'] = $this->db->query($sql)->result();
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/customer',
            'layout' => 'layout',
            'skin' => 'lists',
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
    
    public function inc_lists() {
        $del_ids = $this->input->post('del_ids');
        if($del_ids && count($del_ids) > 0 && $del_ids[0]) {
            if($del_ids[0]=='all') {
                $this->db->query("delete from cb_ab_".$this->member->item('mem_userid'));
            } else {
                foreach($del_ids as $did) {
                    $this->db->delete("ab_".$this->member->item('mem_userid'), array("ab_id"=>$did));
                }
            }
        }
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['search_group'] = $this->input->post("search_group");
        $view['param']['search_name'] = $this->input->post("search_name");
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
        
        $where = " 1=1 "; $param = array();
        if($view['param']['search_type']!="all") { $where .= " and ab_status = ? "; array_push($param, ($view['param']['search_type']=="reject") ? "0" : "1"); }
        if($view['param']['search_for']) { $where .= " and ab_tel like ? "; array_push($param, '%'.$view['param']['search_for'].'%'); }
        
        if($view['param']['search_group']) { 
            if($view['param']['search_group']=='FT') {
                $where .= " and exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
            } else if($view['param']['search_group']=='NFT') {
                $where .= " and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
            } else {
            $where .= " and ab_kind = ? "; array_push($param, $view['param']['search_group']); 
            }
        }
        
        if($view['param']['search_name']) { $where .= " and ab_name like ? "; array_push($param, '%'.$view['param']['search_name'].'%'); }
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), $where, $param);
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        
        $sql = "
			select a.*
			from cb_ab_".$this->member->item('mem_userid')." a
			where ".$where." order by (case when length(a.ab_kind) > 0 then concat('0',a.ab_kind) else '9' end) ,  a.ab_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        
        $view['list'] = $this->db->query($sql, $param)->result();
        $view['view']['canonical'] = site_url();
        
        $this->load->view('biz/customer/inc_lists',$view);
    }
    
    
    public function inc_lists_all() {
        
        
        $where = " 1=1 "; $param = array();
        if($view['param']['search_type']!="all") { $where .= " and ab_status = ? "; array_push($param, ($view['param']['search_type']=="reject") ? "0" : "1"); }
        if($view['param']['search_for']) { $where .= " and ab_tel like ? "; array_push($param, '%'.$view['param']['search_for'].'%'); }
        if($view['param']['search_group']) { $where .= " and ab_kind like ? "; array_push($param, '%'.$view['param']['search_group'].'%'); }
        if($view['param']['search_name']) { $where .= " and ab_name like ? "; array_push($param, '%'.$view['param']['search_name'].'%'); }
        
        $sql = "
			select a.*
			from cb_ab_".$this->member->item('mem_userid')." a
			where ".$where." order by (case when length(a.ab_kind) > 0 then concat('0',a.ab_kind) else '9' end) ,  a.ab_datetime desc" ;
        $all_data = $this->db->query($sql, $param)->result();
        echo json_encode($all_data);
    }
    
    public function modify()
    {
        $customer_id = $this->input->post('customer_id');
        $status_box = $this->input->post('status_box');	//:none
        $kind = urldecode($this->input->post('kind'));	//:1111
        $memo = urldecode($this->input->post('memo'));	//:1111
        if(!$customer_id) {
            echo '{"success": false}';
            exit;
        }
        $this->ab_status = ($status_box=='reject') ? '0' : '1';
        $this->ab_kind = $kind;
        $this->ab_memo = $memo;
        $this->db->update("ab_".$this->member->item('mem_userid'), $this, array("ab_id"=>$customer_id));
        header('Content-Type: application/json');
        if ($this->db->error()['code'] < 1) {
            echo '{"success": true}';
        } else {
            echo '{"success": false}';
        }
    }
    
    public function write()
    {
        $tels = $this->input->post('tels');
        $names = $this->input->post('names');
        $kinds = $this->input->post('kinds');
        if($tels && count($tels) > 0) {
            $this->save_customer($tels, $names, $kinds);
            return;
        }
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
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
            'path' => 'biz/customer',
            'layout' => 'layout',
            'skin' => 'write',
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
    
    function save_customer($tels, $names, $kinds)
    {
        $ok = 0;
        $duplicated = array();
        $data = array();
        $this->Biz_model->make_customer_book($this->member->item('mem_userid'));
        for($n=0;$n<count($tels);$n++) {
            if(!is_numeric(str_replace("-", "", $tels[$n]))) {
                continue;
            }
            $data['ab_tel'] = str_replace("-", "", $tels[$n]);
            $data['ab_name'] = ($names[$n]) ? $names[$n] : '';
            $data['ab_kind'] = ($kinds[$n]) ? $kinds[$n] : '';
            $data["ab_datetime"] = cdate('Y-m-d H:i:s');
            $dp = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_tel=?", array($data['ab_tel']));
            if($dp > 0) {
                array_push($duplicated, array($data['ab_tel'], $data['ab_name']));
            } else {
                $this->db->insert("ab_".$this->member->item('mem_userid'), $data);
                if ($this->db->error()['code'] < 1) { $ok++; }
            }
        }
        header('Content-Type: application/json');
        echo '{"uploaded": '.$ok.', "dup_list": '.json_encode($duplicated).', "success": "success", "duplicated": '.count($duplicated).'}';
    }
    
    function upload()
    {
        //업로드된 엑셀 파일을 처리함
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "col_value": "", "nrow_len": 0 }';
            exit;
        }
        /*
         업로드 시 자료 초기화 방지 .
         if($this->input->post('real')) {
         
         $sql = "DROP TABLE IF EXISTS `cb_ab_".$this->member->item('mem_userid')."`";
         $this->db->query($sql);
         $this->Biz_model->make_customer_book($this->member->item('mem_userid'));
         }
         */
        
        $this->load->library("excel");
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objReader->setReadDataOnly( true );
        $objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
        $sheetsCount = $objPHPExcel->getSheetCount();
        
        // 쉬트별로 읽기
        $ok = 0;
        $duplicated = array();
        $data = array();
        $del_qty = 0;
        $dup_qty = 0;
        for($i = 0; $i < $sheetsCount; $i++)
        {
            $sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            
            $han_field = array("전화번호", "이름", "고객구분", "삭제");
            $eng_field = array("ab_tel", "ab_name", "ab_kind", "del" );
            $base_pos = array(0, 1, 2, 3);
            $pos = array();
            
            // 한줄읽기
            for ($row = 1; $row <= $highestRow; $row++)
            {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                // 첫줄에 필드내용
                if($row==1) {
                    foreach($han_field as $f) {
                        for($n=0;$n<count($rowData[0]);$n++) {
                            if($f==$rowData[0][$n]) {
                                array_push($pos, $n);
                                break;
                            }
                        }
                    }
                    // 찾지못한경우 기본값으로 설정, 찾은경우 다음행으로 이동
                    if(count($han_field) != count($pos)) { $pos = $base_pos; } else { continue; }
                }
                unset($data);
                $data = array();
                // $rowData가 한줄의 데이터를 셀별로 배열처리 됩니다.
                // 엑셀의 날짜가 간혹 41852 이렇게 보일때가 있습니다. 이것은 엑셀서식이기에 우리가 흔히 쓰는 형식으로 변경을 해줘야 할때가 있습니다
                //$date = PHPExcel_Style_NumberFormat::toFormattedString($날짜변수, PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $err = false;
                $col = 0;
                foreach($pos as $p) {
                    if($col==0) {
                        if(!is_numeric(str_replace("-", "", $rowData[0][$p]))) {
                            $err = true;
                            break;
                        } else {
                            $data[$eng_field[$col]] = str_replace("-", "", $rowData[0][$p]);
                        }
                    } else {
                        $data[$eng_field[$col]] = $rowData[0][$p];
                    }
                    $col++;
                }
                if($err) continue;
                $data["ab_datetime"] = cdate('Y-m-d H:i:s');
                
                if($this->input->post('real')) {
                    /* 전화번호가 기존에 존재 하는지 확인 */
                    $dp = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_tel=? " /* and ifnull(ab_kind,'')=ifnull(?, '')"*/, array($data['ab_tel']/*, $data['ab_kind']*/));
                    
                    /*
                     * 삭제 필드가 값이 있다면 전화 번호 삭제 시도
                     */
                    if($data['del'] == 'D') {
                        if($dp > 0) {
                            $this->db->query("delete from cb_ab_".$this->member->item('mem_userid')." where ab_tel = '".$data['ab_tel']."'");
                            $del_qty ++;
                        }
                    } else {
                        unset($data['del']);
                        if($dp > 0) {
                            array_push($duplicated, array($data['ab_tel'], $data['ab_name']));
                        } else {
                            $this->db->insert("ab_".$this->member->item('mem_userid'), $data);
                            if ($this->db->error()['code'] < 1) { $ok++; }
                        }
                    }
                } else {
                    $ok++;
                }
            }
        }
        header('Content-Type: application/json');
        if($this->input->post('real')) {
            echo '{"uploaded": '.$ok.', "dup_list": '.json_encode($duplicated).', "success": "success", "duplicated": '.count($duplicated).', "deleted":'.$del_qty.'}';
        } else {
            echo '{"status": "success", "col_value": [["'.$data["ab_tel"].'", "'.$data["ab_name"].'"]], "nrow_len": '.$ok.' }';
        }
    }
    
    public function download()
    {
        log_message("ERROR", "EXCEL 다운로드 시작");
        $post = array();
        $post['search_type'] = $this->input->post("search_type");
        $post['search_for'] = $this->input->post("search_for");
        $post['search_group'] = $this->input->post("search_group");
        $post['search_name'] = $this->input->post("search_name");
        
        $where = " 1=1 ";
        $param = array();
        if($post['search_type']!="all") { $where .= " and ab_status = ? "; array_push($param, ($post['search_type']=="reject") ? "0" : "1"); }
        if($post['search_for']) { $where .= " and ab_tel like ? "; array_push($param, '%'.$post['search_for'].'%'); }
        if($post['search_group']) { $where .= " and ab_kind like ? "; array_push($param, '%'.$post['search_group'].'%'); }
        if($post['search_name']) { $where .= " and ab_name like ? "; array_push($param, '%'.$post['search_name'].'%'); }
        
        $sql = "
			select a.*
			from cb_ab_".$this->member->item('mem_userid')." a
			where ".$where." order by a.ab_datetime desc";
        
        log_message("ERROR", "Query : ".$sql);
        
        $list = $this->db->query($sql, $param)->result();
        
        // 라이브러리를 로드한다.
        $this->load->library('excel');
        
        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');
        
        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:F1');
        
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A3:F3');
        
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
        
        $this->excel->getActiveSheet()->mergeCells('A1:F1');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '고객목록');
        
        $this->excel->getActiveSheet()->mergeCells('A3:A3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '등록일');
        $this->excel->getActiveSheet()->mergeCells('B3:B3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '고객구분');
        $this->excel->getActiveSheet()->mergeCells('C3:C3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '고객명');
        $this->excel->getActiveSheet()->mergeCells('D3:D3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '전화번호');
        $this->excel->getActiveSheet()->mergeCells('E3:E3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '상태');
        $this->excel->getActiveSheet()->mergeCells('F3:F3');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, '메모');
        
        
        $row = 4;
        foreach($list as $val) {
            // 데이터를 읽어서 순차로 기록한다.
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->ab_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->ab_kind);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val->ab_name);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->ab_tel);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ($val->ab_status=='1') ? '정상' :'수신거부');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val->ab_memo);
            $row++;
        }
        
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