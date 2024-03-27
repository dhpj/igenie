<?php
class Survey extends CB_Controller {
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
        if($key) { $this->view($key); return; }

        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['duration'] = ($this->input->post("duration")) ? $this->input->post("duration") : "week";
        
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $where = " mem_id = '".$this->member->item("mem_id")."' ";
        $param = array();
        if($view['param']['search_type'] && $view['param']['search_type']!="all" && $view['param']['search_for']) {
            $where .= " and title like ? "; 
            array_push($param, "%".$view['param']['search_for']."%");
        }

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        $sql = "select * from cb_survey_m sm where ".$where." order by svm_id desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        
        $view['list'] = $this->db->query($sql, $param)->result();
        
        $view['view']['canonical'] = site_url();
        
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        
        /*
         * 2019.08.14 LSH
         * 관리자 DHN 으로 들어 왔을경우 "실패재전송" 메뉴를 보이게 하기 위해
         * session 변수를 이용함.
         */
        if($this->member->item('mem_id') != '3') {
            if($this->session->userdata('login_stack')) {
                $login_stack = $this->session->userdata('login_stack');
                log_message("ERROR",'Login Stack : '. $login_stack[0]);
                if($login_stack[0] == '3') {
                    $view['isManager'] = 'Y';
                }
            }
            
        } else {
            $view['isManager'] = 'Y';
        }
        
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'biz/survey',
            'layout' => 'layout',
            'skin' => 'list',
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
     
    public function result($survey_id) {
        $sql = "select * from cb_survey_m where short_url = '".$survey_id."'";
        
        $view = array();
        $view['info'] = $this->db->query($sql)->row();
        
        $sql_q = "select q.*
                        ,(SELECT COUNT(1) FROM cb_survey_detail d WHERE d.que_id = q.que_id) AS t_cnt
                   from cb_survey_q q where q.svm_id = '".$view['info']->svm_id."'";
        $view['question'] = $this->db->query($sql_q)->result();
        
        $sql_t = "select max(cnt) AS t_cnt FROM (SELECT que_id, COUNT(1) AS cnt FROM cb_survey_detail WHERE svm_id = '".$view['info']->svm_id."' GROUP BY que_id) a";
        $view['total_cnt'] = $this->db->query($sql_t)->row()->t_cnt;
        
        foreach($view['question'] as $q) {
            $sql_a = "SELECT a.*
                            ,(SELECT COUNT(1) FROM cb_survey_detail d WHERE d.que_id = a.que_id AND d.nvalue = a.seq) AS t_a_cnt 
                        FROM cb_survey_a a where que_id = '".$q->que_id."'";
            $view['answer'][$q->que_id] = $this->db->query($sql_a)->result();
            
            $sql_a = "SELECT a.*                            
                        FROM cb_survey_detail a where que_id = '".$q->que_id."' and type in (3) and tvalue is not null";
            $view['answer'][$q->que_id]['tvalue'] = $this->db->query($sql_a)->result();
        }
        
        $layoutconfig = array(
            'path' => 'biz/survey',
            'layout' => 'layout',
            'skin' => 'result',
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
    
    public function download()
    {
        $survey_id = $this->input->post('svm_id');
        $sql = "select * from cb_survey_m where short_url = '".$survey_id."'";
        
        $info = $this->db->query($sql)->row();
        
        $sql_q = "select q.*
                        ,(SELECT COUNT(1) FROM cb_survey_detail d WHERE d.que_id = q.que_id) AS t_cnt
                   from cb_survey_q q where q.svm_id = '".$info->svm_id."'";
        $question = $this->db->query($sql_q)->result();
        
        $sql_t = "select max(cnt) AS t_cnt FROM (SELECT que_id, COUNT(1) AS cnt FROM cb_survey_detail WHERE svm_id = '".$info->svm_id."' GROUP BY que_id) a";
        $total_cnt = $this->db->query($sql_t)->row()->t_cnt;
        
        foreach($question as $q) {
            $sql_a = "SELECT a.*
                            ,(SELECT COUNT(1) FROM cb_survey_detail d WHERE d.que_id = a.que_id AND d.nvalue = a.seq) AS t_a_cnt
                        FROM cb_survey_a a where que_id = '".$q->que_id."'";
            $answer[$q->que_id] = $this->db->query($sql_a)->result();
            
            $sql_a = "SELECT a.*
                        FROM cb_survey_detail a where que_id = '".$q->que_id."' and type in (3) and tvalue is not null";
            $answer[$q->que_id]['tvalue'] = $this->db->query($sql_a)->result();
        } 
        
        // 라이브러리를 로드한다.
        $this->load->library('excel');
        
        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');
        
        // 필드명을 기록한다.
        // 글꼴 및 정렬
//         $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
//             'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
//             'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
//         ),	'A1:G1');
//         $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
//             'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF')),
//             'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'height'=>12 )
//         ),	'A2:G2');
        
//         $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
//             'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
//             'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
//         ),	'A4:G4');
        
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '기간 ');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, $info->start."~".$info->end);
        
        $row = 2;
        foreach($question as $q ) {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Q '.$q->seq.'. '.$q->title);
            $row ++ ;
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, '응답');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $q->t_cnt);
            $row ++ ;
            foreach($answer[$q->que_id] as $a) { 
                switch($a->type) {
                    case 1:
                    case 2:
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $a->title);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $a->t_a_cnt);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, round(($a->t_a_cnt/$q->t_cnt)*100).' %');
                        $row++;
                        break;
                    case 3:
                        foreach($answer[$q->que_id]['tvalue'] as $t) {
                            if(!empty($t->tvalue)) {
                                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $t->tvalue);
                                $row++;
                            }
                        }
                }
            }
            
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
    
    public function write() {
        //	    $sql = "select * from cb_survey_m where short_url = '".$survey_id."'";
        
        $view = array();
        // 	    $view['info'] = $this->db->query($sql)->row();
        
        // 	    $sql_q = "select q.*
        //                         ,(SELECT COUNT(1) FROM cb_survey_detail d WHERE d.que_id = q.que_id) AS t_cnt
        //                    from cb_survey_q q where q.svm_id = '".$view['info']->svm_id."'";
        // 	    $view['question'] = $this->db->query($sql_q)->result();
        
        // 	    $sql_t = "select max(cnt) AS t_cnt FROM (SELECT que_id, COUNT(1) AS cnt FROM cb_survey_detail WHERE svm_id = '".$view['info']->svm_id."' GROUP BY que_id) a";
        // 	    $view['total_cnt'] = $this->db->query($sql_t)->row()->t_cnt;
        
        // 	    foreach($view['question'] as $q) {
        // 	        $sql_a = "SELECT a.*
        //                             ,(SELECT COUNT(1) FROM cb_survey_detail d WHERE d.que_id = a.que_id AND d.nvalue = a.seq) AS t_a_cnt
        //                         FROM cb_survey_a a where que_id = '".$q->que_id."'";
        // 	        $view['answer'][$q->que_id] = $this->db->query($sql_a)->result();
        
        // 	        $sql_a = "SELECT a.*
        //                         FROM cb_survey_detail a where que_id = '".$q->que_id."' and type in (3) and tvalue is not null";
        // 	        $view['answer'][$q->que_id]['tvalue'] = $this->db->query($sql_a)->result();
        // 	    }
        
        $layoutconfig = array(
            'path' => 'biz/survey',
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
    
    public function save() {
        //log_message("ERROR", "Title : ".$this->input->post('survey_json'));
        $survey_id = $this->input->post("surveyid");
        if($survey_id < 1) {
            $survey_m = array();
            $survey_m['mem_id'] = $this->member->item('mem_id');
            $survey_m['title'] = $this->input->post('title');
            $survey_m['description'] = $this->input->post('description');
            $survey_m['start'] = $this->input->post('start');
            $survey_m['end'] = $this->input->post('end');
            $this->db->insert('cb_survey_m', $survey_m);
            $survey_id = $this->db->insert_id();
            
            $str = date("mdhi").sprintf("%03d", ($id % 100) );
            
            $this->load->library('Base62');
            $short_url = $this->base62->encode($str);
            
            $long_url = "http://bizalimtalk.kr/survey/".$short_url;
            
            $this->load->library('Dhnlurl');
            $call_url = $this->dhnlurl->get_short($long_url);
            
            log_message("ERROR", $short_url.'/'.$call_url);
            
            $sql = "update cb_survey_m m set m.short_url = '".$short_url."', m.call_url = '".$call_url."' where svm_id = '".$survey_id."'";
            $this->db->query($sql);
            
        } else {
            $title = $this->input->post('title');
            $desc = $this->input->post('description');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            
            $sql = "update cb_survey_m m
                       set m.title = '".$title."'
                         , m.description = '".$desc."'
                         , m.start = '".$start."'
                         , m.end = '".$end."'
                  where svm_id = '".$survey_id."'";
            $this->db->query($sql);
        }
        
        $survey=json_decode($this->input->post('survey_json'));
        
        foreach($survey->survey as $r) {
            
            $q_id = $this->db->query("select que_id from cb_survey_q where svm_id = '".$survey_id."' and seq = '".$r->seq."'")->row()->que_id;
            
            if(!empty($q_id)) {
                $qsql = "update cb_survey_q q
                        set q.title = '".$r->title."'
                           ,q.type = '".$r->type."'
                      where q.que_id '".$q_id."'";
                $this->db->query($qsql);
                
            } else {
                $q = array();
                $q['svm_id']  = $survey_id;
                $q['mem_id']  = $this->member->item('mem_id');;
                $q['seq']  = $r->seq;
                $q['title']  = $r->title;
                $q['type']  =  $r->type;
                
                $this->db->insert('cb_survey_q', $q);
                $q_id = $this->db->insert_id();
            }
            
            
            foreach($r->answer as $a) {
                
                $aid = $this->db->query("select ans_id from cb_survey_a where que_id = '".$q_id."' and seq = '".$a->seq."'")->row()->ans_id;
                
                if(!empty($aid)) {
                    $asql = "update cb_survey_a a
                            set a.title = '".$a->title."'
                               ,a.type = '".$a->type."'
                          where a.que_id = '".$q_id."' and seq = '".$a->seq."'";
                    $this->db->query($asql);
                } else {
                    $ans = array();
                    $ans['que_id']  = $q_id;
                    $ans['seq']  = $a->seq;
                    $ans['title']  = $a->title;
                    $ans['type']  =  $a->type;
                    
                    $this->db->insert('cb_survey_a', $ans);
                }
            }
        }
        
        header('Content-Type: application/json');
        echo '{"code":"OK", "surver_id":"'.$survey_id.'"}';
    }
    
}