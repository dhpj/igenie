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
        
        // 2019.01.17. 이수환 고객구분 select box값 조회(NULL값을 공백처리해서 길이가 0초가한 값만) 
        //$sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid');
        $sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid')." where length( ifnull(ab_kind,'') ) > 0 ";
        
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
            if(!empty($del_ids[0]) && $del_ids[0]=='all') {
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
        
        $where = " 1=1 and ab_group_id is null "; $param = array();
        if($view['param']['search_type']!="all") { $where .= " and ab_status = ? "; array_push($param, ($view['param']['search_type']=="reject") ? "0" : "1"); }
        if($view['param']['search_for']) { $where .= " and ab_tel like ? "; array_push($param, '%'.$view['param']['search_for'].'%'); }
        
        // 2019.01.17. 이수환 고객구분없음과 전체를 구분하기 위해 수정
//         if($view['param']['search_group']) {
//             if($view['param']['search_group']=='FT') {
//                 $where .= " and exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
//             } else if($view['param']['search_group']=='NFT') {
//                 $where .= " and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
//             } else {
//                 $where .= " and ab_kind = ? "; array_push($param, $view['param']['search_group']);
//             }
//         }
        if($view['param']['search_group']) {
            if($view['param']['search_group']=='FT') {
                $where .= " and exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
            } else if($view['param']['search_group']=='NFT') {
                $where .= " and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
            } else if($view['param']['search_group']=='NONE') {
                $where .= " and ab_kind = ? "; array_push($param, '');
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
        
        
        $where = " 1=1 and ab_group_id is null "; $param = array();
        if($view['param']['search_type']!="all") { $where .= " and ab_status = ? "; array_push($param, ($view['param']['search_type']=="reject") ? "0" : "1"); }
        if($view['param']['search_for']) { $where .= " and ab_tel like ? "; array_push($param, '%'.$view['param']['search_for'].'%'); }
        // 2019.01.17 이수환 고객구분없음 추가로 수정
        //if($view['param']['search_group']) { $where .= " and ab_kind like ? "; array_push($param, '%'.$view['param']['search_group'].'%'); }
        if($view['param']['search_group']) { 
            if($view['param']['search_group'] == 'NONE') {
                $where .= " and ifNull(ab_kind, '') = ? "; array_push($param, ''); 
            } else {
                $where .= " and ifNull(ab_kind, '') like ? "; array_push($param, '%'.$view['param']['search_group'].'%');
            }
        }
        
        if($view['param']['search_name']) { $where .= " and ab_name like ? "; array_push($param, '%'.$view['param']['search_name'].'%'); }
        
        $sql = "
			select a.*
			from cb_ab_".$this->member->item('mem_userid')." a
			where ".$where." order by (case when length(a.ab_kind) > 0 then concat('0',a.ab_kind) else '9' end) ,  a.ab_datetime desc" ;
        log_Message("ERROR", "SQL : ".$sql);
        $all_data = $this->db->query($sql, $param)->result();
        echo json_encode($all_data);
    }

    public function tree_lists()
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
        
        // 2019.01.17. 이수환 고객구분 select box값 조회(NULL값을 공백처리해서 길이가 0초가한 값만)
        //$sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid');
        
        $add_group = $this->input->Post("add_group");
        
        if(!empty($add_group)) {
            $cntsql = "select count(1) as cnt from cb_custom_group where mem_id = '".$this->member->item('mem_id')."' and group_name = '".$add_group."'";
            $cnt = $this->db->query($cntsql)->row()->cnt;
            if($cnt == 0) {
                $sql = "insert into cb_custom_group(mem_id, mem_userid, group_name) values('".$this->member->item('mem_id')."','".$this->member->item('mem_userid')."','".$add_group."')";
                $this->db->query($sql);
            }
        }
        
        $del_groups = $this->input->post("del_groups");
        
        if(!empty($del_groups)) {
            $ab_del = "delete from cb_ab_".$this->member->item('mem_userid')." where ab_kind = '".$del_groups."'";
            $this->db->query($ab_del);
            
            $cg_del = "delete from cb_custom_group where mem_id= '".$this->member->item('mem_id')."' and group_name = '".$del_groups."'";
            $this->db->query($cg_del);
            
        }
        
        $sql = "SELECT id,
                       group_name,
                       (SELECT COUNT(1)
                          FROM cb_ab_".$this->member->item('mem_userid')." a
                         WHERE a.ab_kind = group_name
                           and a.ab_status = '1' ) AS group_cnt
                  FROM cb_custom_group
                 WHERE mem_id = '".$this->member->item('mem_id')."'
                 order by group_name";
        
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
            'skin' => 'treeview',
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
    
    
    public function treeload() {
        $mem_id = $this->input->post('mem_id');
        $parent = $this->input->post('parent_id');
        
        if(empty($parent)) {
            $parent = 0;
        }
        
        $sql = "SELECT grOup_name AS title
                      ,(case when (SELECT COUNT(1) FROM cb_custom_group b WHERE b.mem_id = a.mem_id AND b.parent_id = a.id) > 0 then TRUE ELSE FALSE END) AS folder
                      ,(case when (SELECT COUNT(1) FROM cb_custom_group b WHERE b.mem_id = a.mem_id AND b.parent_id = a.id) > 0 then TRUE ELSE FALSE END) AS lazy
                      ,(SELECT COUNT(1) FROM cb_ab_".$this->member->item('mem_userid')." b WHERE b.ab_group_id = a.id) as tel_cnt
                      , a.* FROM cb_custom_group a WHERE a.mem_id = '$mem_id' and parent_id = '$parent'";
        $rs = $this->db->query($sql)->result();
        
        $json = json_encode($rs);
        
        header('Content-Type: application/json');
        log_message("ERROR", $json);
        echo $json;
    }
    
    public function treeMove() {
        $mem_id = $this->input->post('mem_id');
        $parent = $this->input->post('parent_id');
        $thisnode = $this->input->post('node_id');
        
        $sql = "update cb_custom_group set parent_id = '$parent' where id = '$thisnode'";
        $this->db->query($sql);
        
    }
    
    public function treerename() {
        $mem_id = $this->input->post('mem_id');
        $title = $this->input->post('title');
        $thisnode = $this->input->post('node_id');
        
        $sql = "update cb_custom_group set group_name = '$title' where id = '$thisnode'";
        
        $this->db->query($sql);
        
    }
    
    public function treeplus() {
        $mem_id = $this->input->post('mem_id');
        $parent_id = $this->input->post('parent_id');
        $thisnode = $this->input->post('node_id');
        
        $node = array();
        $node['mem_id'] = $mem_id;
        $node['group_name'] = '그룹명입력';
        $node['parent_id'] =  $parent_id;
        
        $this->db->insert('cb_custom_group', $node);
        $id = $this->db->insert_id();
        
        $node['id'] = $id;
        
        $json = json_encode($node);
        
        header('Content-Type: application/json');
        echo $json;
        
    }
    
    public function treeremove() {
        $mem_id = $this->input->post('mem_id');
        $this_id = $this->input->post('this_id');
        $mem_userid = $this->input->post('mem_userid');
        $this->node_remove($this_id, $mem_userid);
        
        $result = array();
        $result['code'] = 'OK';
        $json = json_encode($result);
        
        header('Content-Type: application/json');
        echo $json;
        
    }
    
    public function node_remove($id, $mem_userid) {
        $sql = "select * from cb_custom_group ccg where ccg.parent_id = '$id'";
        
        $rs = $this->db->query($sql)->result();
        
        foreach($rs as $r) {
            $this->node_remove($r->id, $mem_userid);
        }
        
        $del = "delete from cb_custom_group where id = '$id'";
        $this->db->query($del);
        
        $del = "delete from cb_ab_".$mem_userid." where ab_group_id = '$id'";
        $this->db->query($del);
        
    }
    
    public function telsremove() {
        $mem_id = $this->input->post('mem_id');
        $this_id = $this->input->post('this_id');
        $mem_userid = $this->input->post('mem_userid');
        $ids = $this->input->post('ids');
        
        
        foreach($ids as $did) {
            $this->db->delete("cb_ab_".$mem_userid, array("ab_group_id"=>$this_id, "ab_id"=>$did));
        }
        
    }
    
    public function teladd() {
        $cb_ab = array();
        $cb_ab['ab_group_id'] =  $this->input->post('this_id');
        $cb_ab['ab_name'] =  $this->input->post('input_name');
        $cb_ab['ab_tel'] =str_replace("-", "",   $this->input->post('input_tel'));
        $cb_ab['ab_memo'] =  $this->input->post('input_memo');
        $cb_ab['ab_status'] =  '1';
        $cb_ab['ab_datetime'] =  cdate('Y-m-d H:i:s');
        $cb_ab['ab_last_datetime'] =  cdate('Y-m-d H:i:s');
        
        $sql = "delete from cb_ab_".$this->input->post('mem_userid')." where ab_group_id = ". $cb_ab['ab_group_id']. " and ab_tel = '".$cb_ab['ab_tel']."' and ( ab_kind = '' or ab_kind is null)";
        $this->db->query($sql);
        
        $this->db->insert('cb_ab_'.$this->input->post('mem_userid'), $cb_ab);
    }
    
    function uploadbytree()
    {
        //업로드된 엑셀 파일을 처리함
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "col_value": "", "nrow_len": 0 }';
            exit;
        }
        
        $ext = $this->input->post("ext");
        if(strtolower($ext) == "txt") {
            $this->upload_txt();
            exit;
        }

        $this->load->library("excel");
        $inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
        
        log_message("ERROR",$inputFileType );
        
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly( true );
        $objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
        $sheetsCount = $objPHPExcel->getSheetCount();
        
        // 쉬트별로 읽기
        $ok = 0;
        $duplicated = array();
        $data = array();
        $del_qty = 0;
        $dup_qty = 0;
        if($this->input->post('real')) {
            $tmpFile = '/var/www/html/uploads/'.cdate('YmdHis').'.txt';
            $fp = fopen($tmpFile, 'w');
        }
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
                        if(!is_numeric(preg_replace("/[^0-9]*/s", "", $rowData[0][$p]))) {//; str_replace(" ","",str_replace("-", "", $rowData[0][$p])))) {
                            $err = true;
                            break;
                        } else {
                            $data[$eng_field[$col]] = preg_replace("/[^0-9]*/s", "", $rowData[0][$p]); //str_replace(" ", "", str_replace("-", "", $rowData[0][$p]));
                        }
                    } else {
                        //$data[$eng_field[$col]] = $rowData[0][$p];
                        $data[$eng_field[$col]] = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $rowData[0][$p]);
                    }
                    $col++;
                }
                if($err) continue;
                $data["ab_datetime"] = cdate('Y-m-d H:i:s');
                
                if($this->input->post('real')) {
                    //3,01098790716,고객명1,1222,,2019-02-20 13:24:00.505
                    $outstr = $this->input->post('mem_id').','.$data['ab_tel'].','.$data['ab_name'].','.$data['ab_kind'].','.$data['del'].','.$data['ab_datetime'].chr(13).chr(10);
                    fwrite($fp,$outstr );
                } else {
                    $ok++;
                }
            }
        }
        
        if($fp) {
            flock($fp, LOCK_UN);
            fclose($fp);
            log_message("ERROR", 'File Lock 해제 및 닫기 완료');
            
            $uptempdel = "delete FROM cb_ab_upload_temp WHERE ab_id = '".$this->input->post('mem_id')."'";
            $this->db->query($uptempdel);
            
            $loadstr = "LOAD DATA LOCAL INFILE '".$tmpFile."'".
                "INTO TABLE cb_ab_upload_temp
                        CHARACTER SET utf8mb4
                        FIELDS
                        TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
                        LINES
                        TERMINATED BY '\n'
                 ";
            //IGNORE 1 LINES
            
            $this->db->query($loadstr);
            if($this->db->error()['code'] >0)  {
                log_message('error', 'DB ERROR = '.$this->db->_error_message());
            } else {
                unlink($tmpFile);
            }
            
            $delstr = "select count(1) as cnt FROM cb_ab_".$this->input->post('mem_userid')." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = '' and t.ab_id = '".$this->input->post('mem_id')."') and a.ab_group_id = '".$this->input->post('this_id')."'";
            $dup_qty = $this->db->query($delstr)->row()->cnt;
            //$dup_qty= $this->db->affected_rows();
            
            $delstr = "delete a FROM cb_ab_".$this->input->post('mem_userid')." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = 'D' and t.ab_id = '".$this->input->post('mem_id')."') and a.ab_group_id = '".$this->input->post('this_id')."'";
            $this->db->query($delstr);
            
            $insstr = "INSERT INTO cb_ab_".$this->input->post('mem_userid')."(ab_name, ab_tel, ab_kind, ab_datetime, ab_group_id)
                        SELECT DISTINCT ab_name, ab_tel, ab_kind, ab_datetime, '".$this->input->post('this_id')."'
                        from cb_ab_upload_temp t
                        WHERE ab_status = ''
                          and ab_id ='".$this->input->post('mem_id')."'
                          and not exists ( select 1 from cb_ab_".$this->input->post('mem_userid')." a where t.ab_tel = a.ab_tel and a.ab_group_id = '".$this->input->post('this_id')."' )";
            $this->db->query($insstr);
            $ok = $this->db->affected_rows();
        }
        
        header('Content-Type: application/json');
        if($this->input->post('real')) {
            echo '{"nrow_len": '.$ok.', "dup_list": '.json_encode($duplicated).', "status": "success", "duplicated": '.$dup_qty.', "deleted":'.$del_qty.'}';
        } else {
            echo '{"status": "success", "col_value": [["'.$data["ab_tel"].'", "'.$data["ab_name"].'"]], "nrow_len": '.$ok.' }';
        }
    }

    public function loadcustomer() {
        $mem_id = $this->input->post('mem_id');
        $mem_userid = $this->input->post('mem_userid');
        $group_id = $this->input->post('group_id');
        
        $search_tel = $this->input->post('search_tel');
        $search_name = $this->input->post('search_name');
        
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['page'] = $this->input->post("page");
        $param = array();
        
        $where = " and ab_status ='1' ";
        
        if(!empty($search_tel)) {
            $where = $where." and ab_tel like '%$search_tel%' ";
        }
        
        if(!empty($search_name)) {
            $where = $where." and ab_name like '%$search_name%' ";
        }
        
        $sql = "select * from cb_ab_".$mem_userid." where ab_group_id = '$group_id' $where limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['rs'] = $this->db->query($sql)->result();
        
        $sql = "select count(1) as cnt from cb_ab_".$mem_userid." where ab_group_id = '$group_id' and ab_status = '1'";
        $total_rows = $this->db->query($sql)->row()->cnt;
        
        $view['view']['canonical'] = site_url();
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = $view['param']['page'];
        $page_cfg['total_rows'] = $total_rows;
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        
        $view['page_html'] = $this->pagination->create_links();
        
        $this->load->view('biz/customer/tree_lists',$view);
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

    public function upload_txt()
    {
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "ft_count": 0, "mms_count": 0, "lms_count": 0, "ft_img_count": 0, "coin": "0", "col_len": 0, "row_len": 0 }';
            exit;
        }

        $ok = 0;
        $duplicated = array();
        $data = array();
        $del_qty = 0;
        $dup_qty = 0;

        if($this->input->post('real')) {
            $tmpFile = '/var/www/html/uploads/'.cdate('YmdHis').'.txt';
            $txtfp = fopen($tmpFile, 'w');
        }

        $fp = fopen($_FILES['file']['tmp_name'],"r");
        while( !feof($fp) )
        {
            $doc_data = fgets($fp);
            $doc_data = preg_replace("/[^0-9]*/s", "",preg_replace('/\r\n|\r|\n/','',$doc_data));
            
            $data = array();
            
            $err = false;
            $cnt = 0;
            
            //��ȭ��ȣ�� ���ڰ� �ƴϸ� skip
            if(!is_numeric(str_replace("-", "", $doc_data))) {
                continue;
            }
            
            $tot_row_len++;
            
            if(!preg_match("/^01[0-9]/", $doc_data)) {
                $ex_row_len++;
                continue;
            }
            
            $data['ab_tel'] = $doc_data;
            $data['ab_datetime'] = cdate('Y-m-d H:i:s');
            $row_len++;
            
            if($this->input->post('real')) {
                //3,01098790716,고객명1,1222,,2019-02-20 13:24:00.505
                $outstr = $this->member->item('mem_id').','.$data['ab_tel'].','.$data['ab_name'].','.$data['ab_kind'].','.$data['del'].','.$data['ab_datetime'].chr(13).chr(10);
                //log_message("ERROR", $outstr)
                fwrite($txtfp,$outstr );
            } else {
                $ok++;
            }

            //$this->db->insert("cb_wt_send_cache", $data);
            //if ($this->db->error()['code'] < 1) { $ok++; } else { echo '<pre> :: ';print_r($this->db->error()); }
        }
        
        fclose($fp);
                
        if($txtfp) {
            flock($txtfp, LOCK_UN);
            fclose($txtfp);
            log_message("ERROR", 'File Lock 해제 및 닫기 완료');
            
            $uptempdel = "delete FROM cb_ab_upload_temp WHERE ab_id = '".$this->member->item('mem_id')."'";
            $this->db->query($uptempdel);
            
            $loadstr = "LOAD DATA LOCAL INFILE '".$tmpFile."'".
                       "INTO TABLE cb_ab_upload_temp
                        CHARACTER SET utf8mb4
                        FIELDS
                        TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
                        LINES
                        TERMINATED BY '\n'
                 ";
            //IGNORE 1 LINES
            
            $this->db->query($loadstr);
            if($this->db->error()['code'] >0)  {
                log_message('error', 'DB ERROR = '.$this->db->_error_message());
            } else {
                unlink($tmpFile);
            }
           
            $delstr = "select count(1) as cnt FROM cb_ab_".$this->member->item('mem_userid')." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = '' and t.ab_id = '".$this->member->item('mem_id')."')";
            $dup_qty = $this->db->query($delstr)->row()->cnt;
            //$dup_qty= $this->db->affected_rows();
            
            $delstr = "delete a FROM cb_ab_".$this->member->item('mem_userid')." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = 'D' and t.ab_id = '".$this->member->item('mem_id')."')";
            $this->db->query($delstr);
            
            if(empty($this->input->post('this_id'))) {
                $insstr = "INSERT INTO cb_ab_".$this->member->item('mem_userid')."(ab_name, ab_tel, ab_kind, ab_datetime)
                SELECT DISTINCT ab_name, ab_tel, ab_kind, ab_datetime
                from cb_ab_upload_temp t
                WHERE ab_status = ''
                  and ab_id ='".$this->member->item('mem_id')."' 
                  and not exists ( select 1 from cb_ab_".$this->member->item('mem_userid')." a where t.ab_tel = a.ab_tel )";

            } else {
                $insstr = "INSERT INTO cb_ab_".$this->input->post('mem_userid')."(ab_name, ab_tel, ab_kind, ab_datetime, ab_group_id)
                SELECT DISTINCT ab_name, ab_tel, ab_kind, ab_datetime, '".$this->input->post('this_id')."'
                from cb_ab_upload_temp t
                WHERE ab_status = ''
                  and ab_id ='".$this->input->post('mem_id')."'
                  and not exists ( select 1 from cb_ab_".$this->input->post('mem_userid')." a where t.ab_tel = a.ab_tel and a.ab_group_id = '".$this->input->post('this_id')."' )";
            }
                                      
            $this->db->query($insstr);
            $ok = $this->db->affected_rows();
        }
        
        header('Content-Type: application/json');
        if($this->input->post('real')) {
            echo '{"uploaded": '.$ok.',"nrow_len":'.$ok.', "dup_list": '.json_encode($duplicated).', "status": "success","success": "success", "duplicated": '.$dup_qty.', "deleted":'.$del_qty.'}';
        } else {
            echo '{"status": "success", "col_value": [["'.$data["ab_tel"].'", "'.$data["ab_name"].'"]], "nrow_len": '.$ok.' }';
        }
         
    }

    function upload()
    {
        //업로드된 엑셀 파일을 처리함
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "col_value": "", "nrow_len": 0 }';
            exit;
        }
        $ext = $this->input->post("ext");
        if(strtolower($ext) == "txt") {
            $this->upload_txt();
            exit;
        }

        log_message("ERROR", "Excel upload 시작  ");

        $this->load->library("excel");
        $inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
        
        log_message("ERROR",$inputFileType );
        
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly( true );
        $objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
        $sheetsCount = $objPHPExcel->getSheetCount();
        
        // 쉬트별로 읽기
        $ok = 0;
        $error = 0;
        $duplicated = array();
        $data = array();
        $del_qty = 0;
        $dup_qty = 0;
        if($this->input->post('real')) {
            $tmpFile = '/var/www/html/uploads/'.cdate('YmdHis').'.txt';
            $fp = fopen($tmpFile, 'w');
        }
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
                        if(!is_numeric(preg_replace("/[^0-9]*/s", "", $rowData[0][$p]))) {//; str_replace(" ","",str_replace("-", "", $rowData[0][$p])))) {
                            $err = true;
                            break;
                        } else {
                            $tempPhone = preg_replace("/[^0-9]*/s", "", $rowData[0][$p]);
                            $tempPhone = trim($tempPhone);
                            $tempPhoneLen = mb_strlen($tempPhone);
                            if ($tempPhoneLen == 11) {
                                $data[$eng_field[$col]] = preg_replace("/[^0-9]*/s", "", $rowData[0][$p]); //str_replace(" ", "", str_replace("-", "", $rowData[0][$p]));
                            } else if ($tempPhoneLen == 10 && substr($tempPhone,0,2) == "01") {
                                $data[$eng_field[$col]] = preg_replace("/[^0-9]*/s", "", $rowData[0][$p]); //str_replace(" ", "", str_replace("-", "", $rowData[0][$p]));
                            } else {
                                $err = true;
                                break;
                            }
                        }
                    } else {
                        //$data[$eng_field[$col]] = $rowData[0][$p];
                        $data[$eng_field[$col]] = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $rowData[0][$p]);
                    }
                    $col++;
                }
                if($err) {
                    $error++;
                    continue;
                }
                $data["ab_datetime"] = cdate('Y-m-d H:i:s');
                
                if($this->input->post('real')) {
                    //3,01098790716,고객명1,1222,,2019-02-20 13:24:00.505
                    $outstr = $this->member->item('mem_id').','.$data['ab_tel'].','.$data['ab_name'].','.$data['ab_kind'].','.$data['del'].','.$data['ab_datetime'].chr(13).chr(10);
                    fwrite($fp,$outstr );
                } else {
                    $ok++;
                }
            }
        }
        
        if($fp) {
            flock($fp, LOCK_UN);
            fclose($fp);
            log_message("ERROR", 'File Lock 해제 및 닫기 완료');
            
            $uptempdel = "delete FROM cb_ab_upload_temp WHERE ab_id = '".$this->member->item('mem_id')."'";
            $this->db->query($uptempdel);
            
            $loadstr = "LOAD DATA LOCAL INFILE '".$tmpFile."'".
                       "INTO TABLE cb_ab_upload_temp
                        CHARACTER SET utf8mb4
                        FIELDS
                        TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
                        LINES
                        TERMINATED BY '\n'
                 ";
            //IGNORE 1 LINES
            
            $this->db->query($loadstr);
            if($this->db->error()['code'] >0)  {
                log_message('error', 'DB ERROR = '.$this->db->_error_message());
            } else {
                unlink($tmpFile);
            }
           
            $delstr = "select count(1) as cnt FROM cb_ab_".$this->member->item('mem_userid')." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = '' and t.ab_id = '".$this->member->item('mem_id')."')";
            $dup_qty = $this->db->query($delstr)->row()->cnt;
            //$dup_qty= $this->db->affected_rows();
            
            $delstr = "delete a FROM cb_ab_".$this->member->item('mem_userid')." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = 'D' and t.ab_id = '".$this->member->item('mem_id')."')";
            $this->db->query($delstr);
            
            $insstr = "INSERT INTO cb_ab_".$this->member->item('mem_userid')."(ab_name, ab_tel, ab_kind, ab_datetime)
                        SELECT DISTINCT ab_name, ab_tel, ab_kind, ab_datetime
                        from cb_ab_upload_temp t
                        WHERE ab_status = ''
                          and ab_id ='".$this->member->item('mem_id')."' 
                          and not exists ( select 1 from cb_ab_".$this->member->item('mem_userid')." a where t.ab_tel = a.ab_tel )";
            $this->db->query($insstr);
            $ok = $this->db->affected_rows();
        }
        
        header('Content-Type: application/json');
        if($this->input->post('real')) {
            echo '{"uploaded": '.$ok.', "dup_list": '.json_encode($duplicated).', "success": "success", "duplicated": '.$dup_qty.', "deleted":'.$del_qty.'}';
        } else {
            echo '{"status": "success", "col_value": [["'.$data["ab_tel"].'", "'.$data["ab_name"].'"]], "nrow_len": '.$ok.', "tel_err_cnt" : '.$error.' }';
        }
    }

    function uploadbylist()
    {
        //업로드된 엑셀 파일을 처리함
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "col_value": "", "nrow_len": 0 }';
            exit;
        }
        log_message("ERROR", "Excel upload 시작  ");

        $this->load->library("excel");
        $inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
        
        log_message("ERROR",$inputFileType );
        
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly( true );
        $objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);	//$this->input->server("DOCUMENT_ROOT").'/system/temp/data.xls');	// 파일경로
        $sheetsCount = $objPHPExcel->getSheetCount();
        
        // 쉬트별로 읽기
        $ok = 0;
        $duplicated = array();
        $data = array();
        $del_qty = 0;
        $dup_qty = 0;
        if($this->input->post('real')) {
            $tmpFile = '/var/www/html/uploads/'.cdate('YmdHis').'.txt';
            $fp = fopen($tmpFile, 'w');
        }
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
                        if(!is_numeric(preg_replace("/[^0-9]*/s", "", $rowData[0][$p]))) {//; str_replace(" ","",str_replace("-", "", $rowData[0][$p])))) {
                            $err = true;
                            break;
                        } else {
                            $data[$eng_field[$col]] = preg_replace("/[^0-9]*/s", "", $rowData[0][$p]); //str_replace(" ", "", str_replace("-", "", $rowData[0][$p]));
                        }
                    } else {
                        //$data[$eng_field[$col]] = $rowData[0][$p];
                        $data[$eng_field[$col]] = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $rowData[0][$p]);
                    }
                    $col++;
                }
                if($err) continue;
                $data["ab_datetime"] = cdate('Y-m-d H:i:s');
                
                if($this->input->post('real')) {
                    //3,01098790716,고객명1,1222,,2019-02-20 13:24:00.505
                    $outstr = $this->input->post('mem_id').','.$data['ab_tel'].','.$data['ab_name'].','.$data['ab_kind'].','.$data['del'].','.$data['ab_datetime'].chr(13).chr(10);
                    fwrite($fp,$outstr );
                } else {
                    $ok++;
                }
            }
        }
        
        if($fp) {
            flock($fp, LOCK_UN);
            fclose($fp);
            log_message("ERROR", 'File Lock 해제 및 닫기 완료');
            
            $uptempdel = "delete FROM cb_ab_upload_temp WHERE ab_id = '".$this->input->post('mem_id')."'";
            $this->db->query($uptempdel);
            
            $loadstr = "LOAD DATA LOCAL INFILE '".$tmpFile."'".
                       "INTO TABLE cb_ab_upload_temp
                        CHARACTER SET utf8mb4
                        FIELDS
                        TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
                        LINES
                        TERMINATED BY '\n'
                 ";
            //IGNORE 1 LINES
            
            $this->db->query($loadstr);
            if($this->db->error()['code'] >0)  {
                log_message('error', 'DB ERROR = '.$this->db->_error_message());
            } else {
                unlink($tmpFile);
            }
           
            $delstr = "select count(1) as cnt FROM cb_ab_".$this->input->post('mem_userid')." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = '' and t.ab_id = '".$this->input->post('mem_id')."')";
            $dup_qty = $this->db->query($delstr)->row()->cnt;
            //$dup_qty= $this->db->affected_rows();
            
            $delstr = "delete a FROM cb_ab_".$this->input->post('mem_userid')." a
                               WHERE EXISTS ( SELECT 1 FROM cb_ab_upload_temp t WHERE t.ab_tel = a.ab_tel and t.ab_status = 'D' and t.ab_id = '".$this->input->post('mem_id')."')";
            $this->db->query($delstr);
            
            $insstr = "INSERT INTO cb_ab_".$this->input->post('mem_userid')."(ab_name, ab_tel, ab_kind, ab_datetime)
                        SELECT DISTINCT ab_name, ab_tel, ab_kind, ab_datetime
                        from cb_ab_upload_temp t
                        WHERE ab_status = ''
                          and ab_id ='".$this->input->post('mem_id')."' 
                          and not exists ( select 1 from cb_ab_".$this->input->post('mem_userid')." a where t.ab_tel = a.ab_tel )";
            $this->db->query($insstr);
            $ok = $this->db->affected_rows();
            
            $memUpdate = "UPDATE cb_member 
                            SET mem_customer_up_filename='".$this->input->post('file_name')."' 
                          WHERE mem_id=".$this->input->post('mem_id')." and mem_userid='".$this->input->post('mem_userid')."'";
            $this->db->query($memUpdate);
        }
        
        header('Content-Type: application/json');
        if($this->input->post('real')) {
            echo '{"nrow_len": '.$ok.', "dup_list": '.json_encode($duplicated).', "status": "success", "duplicated": '.$dup_qty.', "deleted":'.$del_qty.'}';
        } else {
            echo '{"status": "success", "col_value": [["'.$data["ab_tel"].'", "'.$data["ab_name"].'"]], "nrow_len": '.$ok.' }';
        }
    }
    
    function deletebylist()
    {
        $mem_id = $this->input->post('mem_id');
        $mem_userid = $this->input->post('mem_userid');

        $sql = "delete from cb_ab_".$mem_userid;
        $this->db->query($sql);
        
        $memUpdate = "UPDATE cb_member
                            SET mem_customer_up_filename=''
                          WHERE mem_id=".$this->input->post('mem_id')." and mem_userid='".$this->input->post('mem_userid')."'";
        $this->db->query($memUpdate);
    }
    
    function upload__()
    {
        //업로드된 엑셀 파일을 처리함
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "col_value": "", "nrow_len": 0 }';
            exit;
        }
        log_message("ERROR", "Excel upload 시작  ");
        /*
         업로드 시 자료 초기화 방지 .
         if($this->input->post('real')) {
         
         $sql = "DROP TABLE IF EXISTS `cb_ab_".$this->member->item('mem_userid')."`";
         $this->db->query($sql);
         $this->Biz_model->make_customer_book($this->member->item('mem_userid'));
         }
         */
        
        $this->load->library("excel");
        $inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
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
        log_message("ERROR", "Excel upload 끝 ( ".$highestRow." )");
        header('Content-Type: application/json');
        if($this->input->post('real')) {
            echo '{"uploaded": '.$ok.', "dup_list": '.json_encode($duplicated).', "success": "success", "duplicated": '.count($duplicated).', "deleted":'.$del_qty.'}';
        } else {
            echo '{"status": "success", "col_value": [["'.$data["ab_tel"].'", "'.$data["ab_name"].'"]], "nrow_len": '.$ok.' }';
        }
    }
    
    public function upload_() {
        
        
        //업로드된 엑셀 파일을 처리함
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "col_value": "", "nrow_len": 0 }';
            exit;
        }
        
        $this->load->library("ExcelReader");
       
        $xlsx = new XlsxReader();
        
        $inputFileName = $_FILES['file']['tmp_name'];

        log_message("ERROR", "Excel upload 시작  ");
        
        $xlsx->init($inputFileName);
        
        $xlsx->load_sheet(1);

        $ok = 0;
        $duplicated = array();
        $data = array();
        $del_qty = 0;
        $dup_qty = 0;

        $highestRow = $xlsx->rowsize;
        $highestColumn = $xlsx->colsize;
            
        $han_field = array("전화번호", "이름", "고객구분", "삭제");
        $eng_field = array("ab_tel", "ab_name", "ab_kind", "del" );
        $base_pos = array(0, 1, 2, 3);
        $pos = array();
            
        // 한줄읽기
        for ($row = 1; $row <= $highestRow; $row++)
        {

            unset($data);
            
            $data = array();
            
            $err = false;
            $col = 0;
            
            $data['ab_tel'] = $xlsx->val($row, 0);
            $data['ab_name'] = $xlsx->val($row, 1);
            $data['ab_kind'] = $xlsx->val($row, 2);
            $data['del'] = $xlsx->val($row, 3);
            $data["ab_datetime"] = cdate('Y-m-d H:i:s');
            
            if($this->input->post('real')) {
                /* 전화번호가 기존에 존재 하는지 확인 */
                //$dp = $this->Biz_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_tel=? " /* and ifnull(ab_kind,'')=ifnull(?, '')"*/, array($data['ab_tel']/*, $data['ab_kind']*/));
                $dp = 0;
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

        $xlsx->close();
        
        log_message("ERROR", "Excel upload 끝 ( ".$highestRow." )");
        
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
        
        log_message("ERROR", "EXCEL 다운로드 >> ".$this->input->post("search_group"));
        
        $where = " 1=1 ";
        $param = array();
        if($post['search_type']!="all") { $where .= " and ab_status = ? "; array_push($param, ($post['search_type']=="reject") ? "0" : "1"); }
        if($post['search_for']) { $where .= " and ab_tel like ? "; array_push($param, '%'.$post['search_for'].'%'); }
        //         if($post['search_group']) { $where .= " and ab_kind like ? "; array_push($param, '%'.$post['search_group'].'%'); }
        //         if($post['search_name']) { $where .= " and ab_name like ? "; array_push($param, '%'.$post['search_name'].'%'); }
        
        if($post['search_group']) {
            if($post['search_group']=='FT') {
                $where .= " and exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
            } else if($post['search_group']=='NFT') {
                $where .= " and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
            } else if($post['search_group']=='NONE') {
                $where .= " and ab_kind = ? "; array_push($param, '');
            } else {
                $where .= " and ab_kind = ? "; array_push($param, $view['param']['search_group']);
            }
        }
        
        if($view['param']['search_name']) { $where .= " and ab_name like ? "; array_push($param, '%'.$view['param']['search_name'].'%'); }
        
        //         $sql = "
        // 			select a.*
        // 			from cb_ab_".$this->member->item('mem_userid')." a
        // 			where ".$where." order by a.ab_datetime desc";
        
        $sql = "
            select a.*
            from cb_ab_".$this->member->item('mem_userid')." a
            where ".$where." order by (case when length(a.ab_kind) > 0 then concat('0',a.ab_kind) else '9' end) ,  a.ab_datetime desc ";
        
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
            // 수신자 전화번호 국번 별표 처리
            $ab_tel_ori = $val->ab_tel;
            $ab_tel = "";
            $ab_name_ori = $val->ab_name;
            $ab_name = "";
            
            if (strlen($ab_tel_ori) > 10) {
                $ab_tel = substr($ab_tel_ori, 0, 3)."****".substr($ab_tel_ori, 7);
            } else {
                $ab_tel = substr($ab_tel_ori, 0, 3)."***".substr($ab_tel_ori, 6);
            }
            
            $ab_name_count = mb_strlen("$ab_name_ori", "UTF-8");
            
            if ($ab_name_count > 3) {
                //$ab_name = substr($ab_name_ori, 0, 1)."**".substr($ab_name_ori, strlen($ab_name_ori) - 1);
                $tempStr = "";
                for($strCount = 1; $strCount <= $ab_name_count - 2; $strCount++) {
                    $tempStr .= "*";
                }
                
                $ab_name = iconv_substr($ab_name_ori, 0, 1, "utf-8").$tempStr.iconv_substr($ab_name_ori, $ab_name_count - 1, 1, "utf-8");
            } else if ($ab_name_count == 3) {
                //$ab_name = substr($ab_name_ori, 0, 1)."*".substr($ab_name_ori, 2);
                $ab_name = iconv_substr($ab_name_ori, 0, 1, "utf-8")."*".iconv_substr($ab_name_ori, 2, 1, "utf-8");
            } else if ($ab_name_count == 2) {
                $ab_name = iconv_substr($ab_name_ori, 0, 1, "utf-8")."*";
            } else if ($ab_name_count == 1){
                $ab_name = "*";
            }
            
            if($this->member->item('mem_id') != '3' && $this->member->item('mem_id') != '6' && $this->member->item('mem_id') != '2') {
                $login_stack = $this->session->userdata('login_stack');
                if($this->session->userdata('login_stack')) {
                    $login_stack = $this->session->userdata('login_stack');
                    if($login_stack[0] == '3' || $login_stack[0] == '6' || $login_stack[0] == '2') {
                        $ab_tel = $ab_tel_ori;
                        $ab_name = $ab_name_ori;
                    }
                }
            } else {
                $ab_tel = $ab_tel_ori;
                $ab_name = $ab_name_ori;
            }
            
            // 데이터를 읽어서 순차로 기록한다.
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->ab_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->ab_kind);
            //$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val->ab_name);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $ab_name);
            //$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->ab_tel);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $ab_tel);
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