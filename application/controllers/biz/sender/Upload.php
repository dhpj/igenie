<?php
class Upload extends CB_Controller {
    /**
     * ���� �ε��մϴ�
     */
    protected $models = array('Board', 'Biz');
    
    /**
     * ���۸� �ε��մϴ�
     */
    protected $helpers = array('form', 'array');
    
    function __construct()
    {
        parent::__construct();
        
        /**
         * ���̺귯���� �ε��մϴ�
         */
        $this->load->library(array('querystring'));
    }
    
    //     $fp = fopen("document.txt","r"); while( !feof($fp) ) $doc_data = fgets($fp); fclose($fp); echo $doc_data;
    
    //     출처: http://winplz.tistory.com/entry/PHP파일-읽기 [윈플]
    public function index()
    {
        // ft, at ���ε忡�� ���� ó����
        $upload_type = "ft";
        $templ_id = $this->input->post('templ_id');
        $templ_profile = $this->input->post('templ_profile');
        $templ_code = $this->input->post('templ_code');
        $ext = $this->input->post('ext');
        $tmpl = "";
        if($templ_profile) {
            $upload_type = "at";
            $tmpl = $this->db->query("select * from cb_wt_template where tpl_inspect_status='APR' and tpl_use='Y' and tpl_id=? and tpl_profile_key=? and tpl_code=?", array($templ_id, $templ_profile, $templ_code))->row();
            /* �˸����� ��� ���ø��� ������ ó������ ���� */
            if(!$tmpl) { echo '{"status": "fail", "ft_count": 0, "mms_count": 0, "lms_count": 0, "ft_img_count": 0, "coin": 0, "col_len": 0, "row_len": 0 }'; exit; }
        }
        
        /*
        
        btn1:[{"type":"WL","name":"����ġ����","url_mobile":"http://plus-talk.kakao.com/plus/home/@����ġ����","url_pc":"22"}]
        btn2:[{"type":"AL","name":"a","scheme_android":"33","scheme_ios":"44"}]
        btn3:[{"type":"BK","name":"b"}]
        btn4:[{"type":"MD","name":"m"}]
        */
        //{"status": "success", "ft_count": 4, "mms_count": 1, "lms_count": 1, "ft_img_count": 0, "coin": "4640.00", "col_len": 25, "row_len": 6}
        //���ε�� ���� ������ ó����
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "ft_count": 0, "mms_count": 0, "lms_count": 0, "ft_img_count": 0, "coin": "0", "col_len": 0, "row_len": 0 }';
            exit;
        }
        
        $fileinfo = pathinfo($_FILES['file']['tmp_name']);
        //$ext = $fileinfo['extension'];
        log_message("ERROR", "EXT : ".$ext);
        if(strtolower($ext) == "txt" ) {
            $this->upload_txt();
            exit;
        }
        
        $mem = $this->Biz_model->get_member($this->member->item('mem_userid'));
        
        /* ���ε��ϸ� �ڷ� �ʱ�ȭ �� */
        $this->db->delete("cb_wt_send_cache", array("sc_mem_id"=>$this->member->item('mem_id')));
        $this->db->delete("cb_tel_uploads", array("mem_id"=>$this->member->item('mem_id')));
        
        $this->load->library("excel");
        $objPHPExcel = PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
        //$objPHPExcel->setReadDataOnly(true); 
        $sheetsCount = $objPHPExcel->getSheetCount();
        
        // ��Ʈ���� �б�
        $ok = 0;
        $ft_count = 0;			//ģ�������??
        $mms_count = 0;		//MMS����(�ߺ�����)
        $lms_count = 0;		//LMS����(�ߺ�����)
        $sms_count = 0;		//SMS����(�ߺ�����)
        $ft_img_count = 0;	//ģ���� �̹��� ����(�ߺ�����)
        $coin = $this->Biz_model->getAbleCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
        $col_len = 0;			//Į������
        $row_len = 0;			//�హ��(��ȭ��ȣ����:�ߺ�����)
        $ex_row_len = 0;
        $tot_row_len = 0;
        $img_link = array();
        $data = array();
        for($i = 0; $i < $sheetsCount; $i++)
        {
            $sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            
            for ($row = 1; $row <= $highestRow; $row++)
            {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                if($col_len < count($rowData[0])) { $col_len = count($rowData[0]); }	//Į����
                unset($data);
                $data = array();
                
                $err = false;
                $cnt = 0;
                
                //��ȭ��ȣ�� ���ڰ� �ƴϸ� skip
				$phn = preg_replace("/[^0-9]*/s", "", $rowData[0][0]);
                if(!is_numeric($phn)) {
                    continue;
                }

                $tot_row_len++;
                
                if(!preg_match("/^01[0-9]/", $phn)) {
                    $ex_row_len++;
                    continue;
                }
                
                $row_len++;
                $data['sc_mem_id'] = $this->member->item('mem_id');
                $data['sc_tel'] = $phn;
                $data['sc_tel'] = ($data['sc_tel'] && substr($data['sc_tel'], 0, 1)=="1" && strlen($data['sc_tel'])<=10) ? "0".$data['sc_tel'] : $data['sc_tel'];
                $data['sc_datetime'] = cdate('Y-m-d H:i:s');
                $data['sc_content'] = $rowData[0][1];
                
                $attry = array();
                $attr['var1'] = $rowData[0][3];
                $attr['var2'] = $rowData[0][4];
                $attr['var3'] = $rowData[0][5];
                $attr['var4'] = $rowData[0][6];
                $attr['var5'] = $rowData[0][7];
                //if($upload_type=="ft") {
                /* ģ������ ��� */
                //- ������ ������ skip : 2018.03.06 ������ ������ �߼۵��� �ʰ� �����־� �̹߼۰Ǽ� ���� ���� �߻�
                /*
                 * 2018.10.22 업로드시 전화번호만 업로드 하도록 변경함.
                 
                 if($data['sc_content']!="") { $ft_count++; } else { continue; }
                 $btn_odr = 0;
                 for($b=0;$b<5;$b++) {
                 if($rowData[0][($b*4)+2]!=null && $rowData[0][($b*4)+2]!="") {
                 $btn_odr++;
                 $btn = array();
                 //$btn["ordering"] = $btn_odr;
                 //$btn["linkTypeName"] = $rowData[0][($b*4)+2];
                 $btn["type"] = $this->Biz_model->buttonType[$rowData[0][($b*4)+2]];
                 $btn["name"] = $rowData[0][($b*4)+3];
                 if($btn["type"]=="WL") {
                 $btn["url_mobile"] = $rowData[0][($b*4)+4];
                 $btn["url_pc"] = $rowData[0][($b*4)+5];
                 } else if($btn["type"]=="AL") {
                 $btn["scheme_android"] = $rowData[0][($b*4)+4];
                 $btn["scheme_ios"] = $rowData[0][($b*4)+5];
                 } else {
                 //$btn["link1"] = $rowData[0][($b*4)+4];
                 //$btn["link2"] = $rowData[0][($b*4)+5];
                 }
                 $data['sc_button'.$btn_odr] = json_encode($btn, JSON_UNESCAPED_UNICODE);
                 }
                 }
                 $data['sc_lms_content'] = trim($rowData[0][22]);
                 if($data['sc_lms_content']!="") {
                 $lms_count++;
                 if(mb_strlen($data['sc_lms_content'], 'euc-kr') <= 80) { $sms_count++; }
                 }
                 $data['sc_img_url'] = trim($rowData[0][23]);
                 if($data['sc_img_url']!="") { $mms_count++; }
                 $data['sc_img_link'] = trim($rowData[0][24]);
                 if($data['sc_img_url'] && $data['sc_img_link']) {
                 if(!in_array($data['sc_img_link'], $img_link)) { array_push($img_link, $data['sc_img_link']); $ft_img_count++; }
                 }
                 */
                if(!empty($data['sc_tel'])) {
                    $tel_upload = array();
                    $tel_upload['mem_id'] = $this->member->item('mem_id');
                    $tel_upload['tel_no'] =$data['sc_tel'];
                    
                    $tel_upload['var1']=$attr['var1'];
                    $tel_upload['var2']=$attr['var2'];
                    $tel_upload['var3']=$attr['var3'];
                    $tel_upload['var4']=$attr['var4'];
                    $tel_upload['var5']=$attr['var5'];
                    //log_message("ERROR", "TEL NO : ".$data['sc_tel']);
                    $this->db->insert('cb_tel_uploads', $tel_upload);
                }
                /*} else {
                 /* �˸����� ��� */
                /*
                 $tpl_button = json_decode($tmpl->tpl_button);
                 
                 //- ������ ������ skip : 2018.03.06 ������ ������ �߼۵��� �ʰ� �����־� �̹߼۰Ǽ� ���� ���� �߻�
                 if($data['sc_content']!="") { $ft_count++; } else { continue; }
                 $col = 2;
                 $btn_odr = 1;
                 foreach($tpl_button as $btn) {
                 if($btn->linkType=="DS") {
                 $data['sc_p_com'] = $rowData[0][$col++];
                 $data['sc_p_invoice'] = $rowData[0][$col++];
                 $data['sc_button'.$btn_odr] = str_replace("linkType", "type", json_encode($btn, JSON_UNESCAPED_UNICODE));
                 $btn_odr++;
                 continue;
                 }
                 if($btn->linkType=="WL") {
                 if($btn->linkMo) { $btn->linkMo = $rowData[0][$col++]; } else { unset($btn->linkMo); }
                 if($btn->linkPc) { $btn->linkPc = $rowData[0][$col++]; } else { unset($btn->linkPc); }
                 $data['sc_button'.$btn_odr] = str_replace("linkPc", "url_pc", str_replace("linkMo", "url_mobile", str_replace("linkType", "type", json_encode($btn, JSON_UNESCAPED_UNICODE))));
                 $btn_odr++;
                 continue;
                 }
                 if($btn->linkType=="AL") {
                 if($btn->linkAnd) { $btn->linkAnd = $rowData[0][$col++]; } else { unset($btn->linkAnd); }
                 if($btn->linkIos) { $btn->linkIos = $rowData[0][$col++]; } else { unset($btn->linkIos); }
                 $data['sc_button'.$btn_odr] = str_replace("linkAnd", "scheme_android", str_replace("linkIos", "scheme_ios", str_replace("linkType", "type", json_encode($btn, JSON_UNESCAPED_UNICODE))));
                 $btn_odr++;
                 continue;
                 }
                 if($btn->linkType=="BK") {
                 $btn->name = $rowData[0][$col++];
                 $data['sc_button'.$btn_odr] = str_replace("linkType", "type", json_encode($btn, JSON_UNESCAPED_UNICODE));
                 $btn_odr++;
                 continue;
                 }
                 if($btn->linkType=="MD") {
                 $data['sc_s_code'] = $rowData[0][$col++];
                 $data['sc_button'.$btn_odr] = str_replace("linkType", "type", json_encode($btn, JSON_UNESCAPED_UNICODE));
                 $btn_odr++;
                 continue;
                 }
                 }
                 */
                //	$data['sc_lms_content'] = $rowData[0][$col++];
                //	$data['sc_sms_callback'] = preg_replace("/[^0-9]*/s", "", $rowData[0][$col]);
                //	$data['sc_sms_callback'] = ($data['sc_sms_callback'] && substr($data['sc_sms_callback'], 0, 1)=="1" && strlen($data['sc_sms_callback'])<=10) ? "0".$data['sc_sms_callback'] : $data['sc_sms_callback'];
                //	$data['sc_template'] = $templ_code;
                //	if($data['sc_lms_content']!="") {
                //		$lms_count++;
                //		if(mb_strlen($data['sc_lms_content'], 'euc-kr') <= 80) { $sms_count++; }
                //	}
                //}
                
                $this->db->insert("cb_wt_send_cache", $data);
                if ($this->db->error()['code'] < 1) { $ok++; } else { echo '<pre> :: ';print_r($this->db->error()); }
            }
        }
        header('Content-Type: application/json');
        if($upload_type=="ft") {
            //$mms_count = 0; /* mms�� �߼����� ���� */
            echo '{"status": "success", "ft_count": '.$ft_count.', "mms_count": '.$mms_count.', "lms_count": '.$lms_count.', "sms_count": '.$sms_count.',
			       "ft_img_count": '.$ft_img_count.', "coin": "'.$coin.'", "col_len": '.$col_len.', "tot_row_len": '.$tot_row_len.', "ex_row_len": '.$ex_row_len.', "row_len": '.$ok.'}';
        } else {
            echo '{"status": "success", "at_count": '.$ft_count.', "lms_count": '.$lms_count.', "sms_count": '.$sms_count.', "coin": "'.$coin.'", "col_len": '.$col_len.', "nrow_len": '.$ok.'}';
        }
    }
    
    public function upload_txt()
    {
        
        $upload_type = "ft";
        $templ_id = $this->input->post('templ_id');
        $templ_profile = $this->input->post('templ_profile');
        $templ_code = $this->input->post('templ_code');
        $tmpl = "";
        if($templ_profile) {
            $upload_type = "at";
            $tmpl = $this->db->query("select * from cb_wt_template where tpl_inspect_status='APR' and tpl_use='Y' and tpl_id=? and tpl_profile_key=? and tpl_code=?", array($templ_id, $templ_profile, $templ_code))->row();
            
            if(!$tmpl) { echo '{"status": "fail", "ft_count": 0, "mms_count": 0, "lms_count": 0, "ft_img_count": 0, "coin": 0, "col_len": 0, "row_len": 0 }'; exit; }
        }
        
        
        if(!file_exists($_FILES['file']['tmp_name'])) {
            header('Content-Type: application/json');
            echo '{"status": "error", "ft_count": 0, "mms_count": 0, "lms_count": 0, "ft_img_count": 0, "coin": "0", "col_len": 0, "row_len": 0 }';
            exit;
        }
        $mem = $this->Biz_model->get_member($this->member->item('mem_userid'));
        
        
        $this->db->delete("cb_wt_send_cache", array("sc_mem_id"=>$this->member->item('mem_id')));
        $this->db->delete("cb_tel_uploads", array("mem_id"=>$this->member->item('mem_id')));
        
        $ok = 0;
        $ft_count = 0;			//ģ�������??
        $mms_count = 0;		//MMS����(�ߺ�����)
        $lms_count = 0;		//LMS����(�ߺ�����)
        $sms_count = 0;		//SMS����(�ߺ�����)
        $ft_img_count = 0;	//ģ���� �̹��� ����(�ߺ�����)
        $coin = $this->Biz_model->getAbleCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
        $col_len = 0;			//Į������
        $row_len = 0;			//�హ��(��ȭ��ȣ����:�ߺ�����)
        $ex_row_len = 0;
        $tot_row_len = 0;
        $img_link = array();
        $data = array();
        
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
            
            $row_len++;
            $data['sc_mem_id'] = $this->member->item('mem_id');
            $data['sc_tel'] = preg_replace("/[^0-9]*/s", "", $doc_data);
            $data['sc_tel'] = ($data['sc_tel'] && substr($data['sc_tel'], 0, 1)=="1" && strlen($data['sc_tel'])<=10) ? "0".$data['sc_tel'] : $data['sc_tel'];
            $data['sc_datetime'] = cdate('Y-m-d H:i:s');
            
            if(!empty($data['sc_tel'])) {
                $tel_upload = array();
                $tel_upload['mem_id'] = $this->member->item('mem_id');
                $tel_upload['tel_no'] =$data['sc_tel'];
                $this->db->insert('cb_tel_uploads', $tel_upload);
            }
            
            //if($tot_row_len % 10 == 0) {
               // log_message("ERROR", " Upload : ".$tot_row_len." / ".$data['sc_tel']);
           // }
            
            $this->db->insert("cb_wt_send_cache", $data);
            if ($this->db->error()['code'] < 1) { $ok++; } else { echo '<pre> :: ';print_r($this->db->error()); }
        }
        
        fclose($fp);
        
        header('Content-Type: application/json');
        if($upload_type=="ft") {
            //$mms_count = 0; /* mms�� �߼����� ���� */
            echo '{"status": "success", "ft_count": '.$ft_count.', "mms_count": '.$mms_count.', "lms_count": '.$lms_count.', "sms_count": '.$sms_count.',
			       "ft_img_count": '.$ft_img_count.', "coin": "'.$coin.'", "col_len": '.$col_len.', "tot_row_len": '.$tot_row_len.', "ex_row_len": '.$ex_row_len.', "row_len": '.$ok.'}';
        } else {
            echo '{"status": "success", "at_count": '.$ft_count.', "lms_count": '.$lms_count.', "sms_count": '.$sms_count.', "coin": "'.$coin.'", "col_len": '.$col_len.', "nrow_len": '.$ok.'}';
        }
         
    }
}
?>
