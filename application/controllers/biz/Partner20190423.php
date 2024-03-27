<?php
class Partner extends CB_Controller {
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
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['users'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id'));
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
            'path' => 'biz/partner',
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
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['userid'] = $this->input->post("uid");
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['page'] = $this->input->post("page");
        $param = array();
        if($view['param']['userid'] && $view['param']['userid']!="ALL" && is_numeric($view['param']['userid'])) { array_push($param, $view['param']['userid']); } else { array_push($param, $this->member->item('mem_id')); }
        
        $where = " mem_useyn='Y' ";
        if($view['param']['search_for'] && $view['param']['search_type']) {
            $where .= " and a.mem_".$view['param']['search_type']." like ?";
            array_push($param, "%".$view['param']['search_for']."%");
        }
        $rs = $this->Biz_model->get_partner_list($param, $where, $view['param']['page'], $view['perpage']);
        $view['rs'] = $rs->lists;
        $view['view']['canonical'] = site_url();
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = $view['param']['page'];
        $page_cfg['total_rows'] = $rs->total_rows;
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        
        $view['page_html'] = $this->pagination->create_links();
        
        $this->load->view('biz/partner/inc_lists',$view);
    }
    
    public function download() {
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['userid'] = $this->input->post("uid");
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['page'] = $this->input->post("page");
        $param = array();
        if($view['param']['userid'] && $view['param']['userid']!="ALL" && is_numeric($view['param']['userid'])) { array_push($param, $view['param']['userid']); } else { array_push($param, $this->member->item('mem_id')); }
        
        $where = " mem_useyn='Y' ";
        if($view['param']['search_for'] && $view['param']['search_type']) {
            $where .= " and a.mem_".$view['param']['search_type']." like ?";
            array_push($param, "%".$view['param']['search_for']."%");
        }
        
        $sql = "
		SELECT  a.*,
                a.mem_deposit total_deposit,
                c.mrg_recommend_mem_id,
                IFNULL((SELECT
                                mem_username
                            FROM
                                cb_member
                            WHERE
                                mem_id = c.mrg_recommend_mem_id),
                        '') mrg_recommend_mem_username
            FROM
                (SELECT
                    GET_PARENT_EQ_PRIOR_ID(mem_id) AS _id, @level AS level
                FROM
                    (SELECT @start_with:=?, @id:=@start_with) vars, cb_member_register
                WHERE
                    @id IS NOT NULL) ho
                    JOIN
                cb_member_register c ON c.mem_id = ho._id
                    INNER JOIN
                cb_member a ON (c.mem_id = a.mem_id)
                    AND a.mem_useyn = 'Y'
			where ".$where."
			order by c.mrg_recommend_mem_id, a.mem_level desc, a.mem_username ";
        
        $list = $this->db->query($sql, $param)->result();
        
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
        ),	'A1:H1');
        
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "소속");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "가입일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "계정");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "잔액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "담당자이름");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "담당자연락처");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "담당자이메일");
        
        $cnt = 1;
        $row = 2;
        
        foreach($list as $r) {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->mrg_recommend_mem_username);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->mem_register_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->mem_userid);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->mem_username);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, number_format($r->total_deposit, 1));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->mem_nickname);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->mem_phone);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->mem_email);
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
    public function getMemberPriceJSON() {
        $this->load->model("Biz_free_model");
        $mem_id = $this->input->post("parent_mem_id");
        $paremt_amt = $this->Biz_free_model->getMemberPriceJSON($mem_id);
        
        header('Content-Type: application/json');
        echo $paremt_amt;
    }
    
    public function edit()
    {
        $this->load->model("Biz_free_model");
        $mode = $this->input->post("actions");
        if($mode=="partner_save") { $this->partner_save(false); return; }
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['rs'] = $this->Biz_model->get_member($view['param']['key'], true);
        
        $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));
        /* 관리자가 아닌경우 직속 하위의 파트너만 수정가능 */
        if($this->member->item("mem_level")<100 && !in_array($view['rs']->mem_id, $child)) { show_404();	exit; }
        if(!$view['rs'] || count($view['rs']) < 1) { show_404();	exit; }
        if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }
        
        $view['member'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);
        $view['offer'] = $this->Biz_model->get_child_partner_info($this->member->item('mem_id'), "a.mem_level>=10");	//$this->db->query("select mem_id, mem_username from cb_member where mem_level>=10 order by mem_level, mem_username")->result();
        $view['parent_amt'] = $this->Biz_free_model->getMemberPrice($view['rs']->mrg_recommend_mem_id);
        
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
            'path' => 'biz/partner',
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
    
    public function write()
    {
        $mode = $this->input->post("actions");
        if($mode=="partner_save") { $this->partner_save(true); return; }
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['member'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);
        $view['offer'] = $this->Biz_model->get_sub_manager($this->member->item('mem_id')); //get_child_partner_info($this->member->item('mem_id'), "a.mem_level>=10");	//$this->db->query("select mem_id, mem_username from cb_member where mem_level>=10 order by mem_level, mem_username")->result();
        //$view['offer'] = $this->Biz_model->get_offer();
        
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
            'path' => 'biz/partner',
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
    
    function partner_save($new=false)
    {
        $id = $this->input->post("userid");
        $pwd = $this->input->post("password");
        $username = $this->input->post("company_name");
        $nickname = $this->input->post("username");
        $bank_user = $this->input->post("bank_user");
        $phn = $this->input->post("phone");
        $emp_phn = $this->input->post("emp_phn");
        $email = $this->input->post("email");
        $phn_free = $this->input->post("phn_free");
        $price_at = $this->input->post("price_at");
        $price_ft = $this->input->post("price_ft");
        $price_ft_img = $this->input->post("price_ft_img");
        $price_grs = $this->input->post("price_grs");
        $price_grs_sms = $this->input->post("price_grs_sms");
        $price_nas = $this->input->post("price_nas");
        $price_nas_sms = $this->input->post("price_nas_sms");
        $price_015 = $this->input->post("price_015");
        $price_phn = $this->input->post("price_phn");
        $price_dooit = $this->input->post("price_dooit");
        $price_sms = $this->input->post("price_sms");
        $price_lms = $this->input->post("price_lms");
        $price_mms = $this->input->post("price_mms");
        $price_grs_mms = $this->input->post("price_grs_mms");
        $price_nas_mms = $this->input->post("price_nas_mms");
        $rate1 = $this->input->post("rate1");
        $rate2 = $this->input->post("rate2");
        $rate3 = $this->input->post("rate3");
        $rate4 = $this->input->post("rate4");
        $rate5 = $this->input->post("rate5");
        $rate6 = $this->input->post("rate6");
        $rate7 = $this->input->post("rate7");
        $rate8 = $this->input->post("rate8");
        $rate9 = $this->input->post("rate9");
        $rate10 = $this->input->post("rate10");
        $day_limit = intval(preg_replace("/[^0-9\.]*/s", "", $this->input->post('day_limit')));
        
        //20190107 이수환 추가(1line)
        $linkbtn_name = $this->input->post('linkbtn_name');
        //2019.02.18 Full Care비용 추가
        $price_full_care = intval(preg_replace("/[^0-9\.]*/s", "", $this->input->post('price_full_care')));
        
        $old_photo = $this->input->post("old_photo");
        
        $mem_level = $this->input->post("mem_level");
        $parent_id = $this->input->post("recommend_mem_id");
        $phn_agent = $this->input->post("phn_agent");
        $sms_agent = $this->input->post("sms_agent");
        $useyn = $this->input->post("useyn");
        
        //- 2018.03.06 2차발신 서비스 지정
        
        $mem_2nd_send = $this->input->post('mem_2nd_send');
        $mem_payment_desc = $this->input->post('mem_payment_desc');
        $mem_payment_bank = $this->input->post('mem_payment_bank');
        $mem_payment_card = $this->input->post('mem_payment_card');
        $mem_payment_realtime = $this->input->post('mem_payment_realtime');
        $mem_payment_vbank = $this->input->post('mem_payment_vbank');
        $mem_payment_phone = $this->input->post('mem_payment_phone');
        $mem_pay_type = $this->input->post('mem_pay_type');
        $mem_min_charge_amt = $this->input->post('mem_min_charge_amt');
        $mem_fixed_from_date = $this->input->post('mem_fixed_from_date');
        $mem_fixed_to_date = $this->input->post('mem_fixed_to_date');
        
        if(!$id || !$username || !$nickname || !$phn || !$email) { echo '<script type="text/javascript">alert("입력항목 누락!");history.back();</script>'; exit; }
        
        $key = 0;
        
        /* 자신이 관리하는 파트너인지 확인 : 최고관리자 제외 */
        $sql = "select a.mem_id, b.mrg_recommend_mem_id, ifnull(c.mad_mem_id, 0) mad_mem_id
			from cb_member a
				inner join cb_member_register b on a.mem_id=b.mem_id
				left join cb_wt_member_addon c on a.mem_id=c.mad_mem_id
			where a.mem_userid=?";
        $mem = $this->Biz_model->get_member($id, true);	//$this->db->query($sql, array($id))->row();
        
        if(!$new && (!$mem || $mem->mem_userid!=$id)) { show_404();	exit; }
        
        if($new) {
            /* new 인데 mem에 존재하면 */
            if($mem && $mem->mem_id > 1) { show_404(); exit; }
            $parent = $this->member->item('mem_id');
        } else {
            if($mem && $mem->mem_id > 0) { //최고관리자검사
                $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));
                
                /* 관리자가 아닌경우 직속 하위의 파트너만 수정가능 */
                if($this->member->item("mem_level")<100 && !in_array($mem->mem_id, $child)) { show_404();	exit; }
                if($this->member->item('mem_id')!=$mem->mem_id && $this->member->is_admin()!="super" && !in_array($mem->mem_id, $child)) { show_404(); exit; }
                
                //if($this->member->is_admin()!="super" && $mem->mrg_recommend_mem_id!=$this->member->item('mem_id')) { show_404(); exit; }
                $key = $mem->mem_id;
                $parent = $mem->mrg_recommend_mem_id;
            } else {	show_404();	exit;	}
        }
        
        $file_error = '';
        $uploadfiledata = '';
        $this->load->library('upload');
        if (isset($_FILES) && isset($_FILES['biz_license']) && isset($_FILES['biz_license']['name']) && isset($_FILES['biz_license']['name'])) {
            $filecount = count($_FILES['biz_license']['name']);
            $upload_path = config_item('uploads_dir') . '/member_photo/';
            if (is_dir($upload_path) === false) {
                mkdir($upload_path, 0707);
                $file = $upload_path . 'index.php';
                $f = @fopen($file, 'w');
                @fwrite($f, '');
                @fclose($f);
                @chmod($file, 0644);
            }
            $upload_path .= cdate('Y') . '/';
            if (is_dir($upload_path) === false) {
                mkdir($upload_path, 0707);
                $file = $upload_path . 'index.php';
                $f = @fopen($file, 'w');
                @fwrite($f, '');
                @fclose($f);
                @chmod($file, 0644);
            }
            $upload_path .= cdate('m') . '/';
            if (is_dir($upload_path) === false) {
                mkdir($upload_path, 0707);
                $file = $upload_path . 'index.php';
                $f = @fopen($file, 'w');
                @fwrite($f, '');
                @fclose($f);
                @chmod($file, 0644);
            }
            
            $uploadconfig = '';
            $uploadconfig['upload_path'] = $upload_path;
            $uploadconfig['allowed_types'] = element('upload_file_extension', $board) ? element('upload_file_extension', $board) : '*';
            $uploadconfig['max_size'] = element('upload_file_max_size', $board) * 1024;
            $uploadconfig['encrypt_name'] = true;
            
            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $_FILES['biz_license']['name'];
            $_FILES['userfile']['type'] = $_FILES['biz_license']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['biz_license']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['biz_license']['error'];
            $_FILES['userfile']['size'] = $_FILES['biz_license']['size'];
            if ($this->upload->do_upload()) {
                $filedata = $this->upload->data();
                
                $uploadfiledata['pfi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
                $uploadfiledata['pfi_originname'] = element('orig_name', $filedata);
                $uploadfiledata['pfi_filesize'] = intval(element('file_size', $filedata) * 1024);
                $uploadfiledata['pfi_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
                $uploadfiledata['pfi_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
                $uploadfiledata['pfi_type'] = str_replace('.', '', element('file_ext', $filedata));
                $uploadfiledata['is_image'] = element('is_image', $filedata) ? 1 : 0;
                
                $this->mem_photo = $uploadfiledata['pfi_filename'];
                if($old_photo && file_exists(config_item('uploads_dir').'/member_photo/'.$old_photo)) {
                    unlink(config_item('uploads_dir').'/member_photo/'.$old_photo);
                }
            } else {
                $file_error = $this->upload->display_errors();
            }
        }
        
        $this->mem_username = $username;
        $this->mem_nickname = $nickname;
        $this->mem_bank_user = $bank_user;
        $this->mem_phone = $phn;
        $this->mem_emp_phone = $emp_phn;
        $this->mem_email = $email;
        $this->mem_level = ($mem_level) ? (int)$mem_level : 1;
        $this->mem_day_limit = $day_limit;
        //20190107 이수환 추가(1line)
        $this->mem_linkbtn_name = $linkbtn_name;
        //if($_SERVER['REMOTE_ADDR']==$this->config->config['developer_ip'] || $this->config->config['use_multi_agent'])
        $this->mem_2nd_send = $mem_2nd_send;
        $this->mem_payment_desc = $mem_payment_desc;
        $this->mem_payment_bank = $mem_payment_bank;
        $this->mem_payment_card = $mem_payment_card;
        $this->mem_payment_realtime = $mem_payment_realtime;
        $this->mem_payment_vbank = $mem_payment_vbank;
        $this->mem_payment_phone = $mem_payment_phone;
        $this->mem_pay_type = (empty($mem_pay_type) ? 'B' : $mem_pay_type);
        $this->mem_min_charge_amt = $mem_min_charge_amt;
        if($mem_pay_type=='T') {
            $this->mem_fixed_from_date = $mem_fixed_from_date;
            $this->mem_fixed_to_date = $mem_fixed_to_date;
        }
        
        if($pwd) {
            if ( ! function_exists('password_hash')) { $this->load->helper('password'); }
            $this->mem_password = password_hash($pwd, PASSWORD_BCRYPT);
        }
        if($new) { $this->mem_userid = $id; }
        if($new) {
            $this->mem_email_cert = 1;
            $this->mem_register_datetime = cdate('Y-m-d H:i:s');
            $this->mem_register_ip = $this->input->ip_address();
            $this->mem_deposit = 0.0;
            $this->mem_point = $this->cbconfig->item('point_register');		/* 회원가입시 지급 포인트 */
            
            $this->db->insert("member", $this);
            $key = $this->db->insert_id();
            /* 회원 등록정보 저장 */
            $register = array();
            $register['mem_id'] = $key;
            $register['mrg_ip'] = $this->input->ip_address();
            $register['mrg_datetime'] = cdate('Y-m-d H:i:s');
            $register['mrg_recommend_mem_id'] = ($parent_id) ? $parent_id : $parent;
            $register['mrg_useragent'] = $this->agent->agent_string();
            $register['mrg_referer'] = $this->session->userdata('site_referer');
            $this->db->insert("member_register", $register);
        } else {
            if($this->member->item('mem_level')>=10) {
                $register = array();
                $register['mrg_recommend_mem_id'] = ($parent_id) ? $parent_id : $parent;
                $this->db->update("member_register", $register, array("mem_id"=>$key));
                /* 문자 발송 업체 지정, 사용여부 추가 : 2017.12.15 */
                if($phn_agent) { $this->mem_phn_agent = $phn_agent; }
                if($sms_agent) { $this->mem_sms_agent = $sms_agent; }
                if($useyn) { $this->mem_useyn = $useyn; }
            }
            $this->db->update("member", $this, array("mem_userid"=>$id));
        }
        
        /* 회원 추가정보 저장 */
        $addon['mad_free_hp'] = $phn_free;
        $addon['mad_price_at'] = str_replace(",", "", $price_at);
        $addon['mad_price_ft'] = str_replace(",", "", $price_ft);
        $addon['mad_price_ft_img'] = str_replace(",", "", $price_ft_img);
        $addon['mad_price_grs'] = str_replace(",", "", $price_grs);
        $addon['mad_price_grs_sms'] = str_replace(",", "", $price_grs_sms);
        $addon['mad_price_nas'] = str_replace(",", "", $price_nas);
        $addon['mad_price_nas_sms'] = str_replace(",", "", $price_nas_sms);
        $addon['mad_price_015'] = str_replace(",", "", $price_015);
        $addon['mad_price_phn'] = str_replace(",", "", $price_phn);
        $addon['mad_price_dooit'] = str_replace(",", "", $price_dooit);
        $addon['mad_price_sms'] = str_replace(",", "", $price_sms);
        $addon['mad_price_lms'] = str_replace(",", "", $price_lms);
        $addon['mad_price_mms'] = str_replace(",", "", $price_mms);
        $addon['mad_price_grs_mms'] = str_replace(",", "", $price_grs_mms);
        $addon['mad_price_nas_mms'] = str_replace(",", "", $price_nas_mms);
        $addon['mad_weight1'] = str_replace(",", "", $rate1);
        $addon['mad_weight2'] = str_replace(",", "", $rate2);
        $addon['mad_weight3'] = str_replace(",", "", $rate3);
        $addon['mad_weight4'] = str_replace(",", "", $rate4);
        $addon['mad_weight5'] = str_replace(",", "", $rate5);
        $addon['mad_weight6'] = str_replace(",", "", $rate6);
        $addon['mad_weight7'] = str_replace(",", "", $rate7);
        $addon['mad_weight8'] = str_replace(",", "", $rate8);
        $addon['mad_weight9'] = str_replace(",", "", $rate9);
        $addon['mad_weight10'] = str_replace(",", "", $rate10);
        
        //2019.02.18 Full Care비용 추가
        $addon['mad_price_full_care'] = str_replace(",", "", $price_full_care);
        
        if($new || $mem->mad_mem_id < 1) {
            $addon['mad_mem_id'] = $key;
            $this->db->insert("wt_member_addon", $addon);
        } else {
            $this->db->update("wt_member_addon", $addon, array("mad_mem_id"=>$key));
        }
        /* member_userid 등록 */
        $uData = array();
        $uData['mem_userid'] = $id;
        $uData['mem_status'] = '0';
        if($new || $mem->mad_mem_id < 1) {
            $uData['mem_id'] = $key;
            $this->db->insert("cb_member_userid", $uData);
        } else {
            $this->db->update("cb_member_userid", $uData, array("mem_id"=>$key));
        }
        /* member_nickname 등록 */
        $nData = array();
        $nData['mni_nickname'] = $nickname;
        $nData['mni_start_datetime'] = cdate('Y-m-d H:i:s');
        if($new || $mem->mad_mem_id < 1) {
            $nData['mem_id'] = $key;
            $this->db->insert("cb_member_nickname", $nData);
        } else {
            $this->db->update("cb_member_nickname", $nData, array("mem_id"=>$key));
        }
        /* 신규등록인 경우 필요 테이블 생성 */
        if($new) {
            $this->Biz_model->make_msg_log_table($id);
            $this->Biz_model->make_customer_book($id);
            $this->Biz_model->make_user_image_table($id);
            $this->Biz_model->make_user_deposit_table($id);
            $this->Biz_model->make_send_cache_table($id);
        }
        
        if ($this->db->error()['code'] > 0) {
            echo '<script type="text/javascript">alert("저장 오류입니다.");history.back();</script>';
        } else {
            header("Location: /biz/partner/lists");
        }
    }
    
    public function partner_charge()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        $view['perpage'] = 20;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['rs'] = $this->Biz_model->get_member($view['param']['key'], true);
        
        /* 관리자가 아닌경우 직속 하위의 파트너만 열람가능 */
        if($this->member->item("mem_level")<100 && $view['rs']->mrg_recommend_mem_id!=$this->member->item('mem_id')) { show_404();	exit; }
        
        if(!$view['rs'] || $view['rs']->mem_userid!=$view['param']['key']) { show_404(); exit; }
        $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));
        if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }
        
        $this->Biz_model->make_user_deposit_table($view['rs']->mem_userid);
        
        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['set_date'] = $this->input->post('set_date');
        $term = $this->Biz_model->set_Term($view['param']['set_date']);
        $view['param']['endDate'] = $term->endDate;
        $view['param']['startDate'] = $term->startDate;
        $param = array($view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59");
        $view['total_rows'] = $this->Biz_model->get_table_count("cb_amt_".$view['rs']->mem_userid, "amt_kind in ('1', '2', '3') and amt_datetime between ? and ?", $param);
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        
        $sql = "
			select *
			from cb_amt_".$view['rs']->mem_userid."
			where amt_kind in ('1', '2', '3') and amt_datetime between ? and ? order by amt_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql, $param)->result();
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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'partner_charge',
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
    
    public function partner_recipient()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['rs'] = $this->Biz_model->get_member($view['param']['key'], true);
        
        /* 관리자가 아닌경우 직속 하위의 파트너만 열람가능 */
        if($this->member->item("mem_level")<100 && $view['rs']->mrg_recommend_mem_id!=$this->member->item('mem_id')) { show_404();	exit; }
        
        if(!$view['rs'] || count($view['rs']) < 1) { show_404(); exit; }
        $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));
        if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }
        
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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'partner_recipient',
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
    
    public function inc_recipient_lists() {
        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        $view['param']['search_type'] = $this->input->post("search_type");
        $view['param']['search_for'] = $this->input->post("search_for");
        $view['param']['page'] = $this->input->post("page");
        $view['total_rows'] = $this->Biz_model->get_table_count("cb_ab_".$view['param']['key'], "", array());
        
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        
        $sql = "
			select a.*
			from cb_ab_".$view['param']['key']." a
			/*		inner join template b	*/
			where 1=1 order by a.ab_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql, $param)->result();
        $view['view']['canonical'] = site_url();
        
        $this->load->view('biz/partner/inc_recipient_lists',$view);
    }
    
    public function partner_sent()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        $view['perpage'] = 20;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['rs'] = $this->Biz_model->get_member($view['param']['key'], true);
        
        /* 관리자가 아닌경우 직속 하위의 파트너만 열람가능 */
        if($this->member->item("mem_level")<100 && $view['rs']->mrg_recommend_mem_id!=$this->member->item('mem_id')) { show_404();	exit; }
        
        if(!$view['rs'] || count($view['rs']) < 1) { show_404(); exit; }
        $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));
        if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }
        
        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['set_date'] = $this->input->post('set_date');
        $term = $this->Biz_model->set_Term($view['param']['set_date']);
        $view['param']['endDate'] = $term->endDate;
        $view['param']['startDate'] = $term->startDate;
        $param = array($view['rs']->mem_id, $view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59");
        $view['total_rows'] = $this->Biz_model->get_table_count("cb_wt_msg_sent", "mst_mem_id=? and mst_datetime between ? and ?", $param);
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        
        $sql = "
			select a.*
			from cb_wt_msg_sent a
			/*		inner join template b	*/
			where a.mst_mem_id=? and a.mst_datetime between ? and ? order by a.mst_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql, $param)->result();
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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'partner_sent',
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
    
    public function view()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();
        $view['param']['key'] = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        
        $view['rs'] = $this->Biz_model->get_member($view['param']['key'], true);
        $sql = "select spf_biz_sheet from cb_wt_send_profile where spf_mem_id=? and spf_biz_sheet is not null and spf_biz_sheet<>''";
        $view['biz_sheet'] = $this->db->query($sql, array($view['rs']->mem_id))->result_array();
        
        $child = $this->Biz_model->get_member_child_id($this->member->item('mem_id'));
        
        /* 관리자가 아닌경우 직속 하위의 파트너만 열람가능 */
        if($this->member->item("mem_level")<100 && !in_array($view['rs']->mem_id, $child)) { show_404();	exit; }
        if(!$view['rs'] || count($view['rs']) < 1) { show_404(); exit; }
        if($this->member->item('mem_id')!=$view['rs']->mem_id && $this->member->is_admin()!="super" && !in_array($view['rs']->mem_id, $child)) { show_404(); exit; }
        
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
            'path' => 'biz/partner',
            'layout' => 'layout',
            'skin' => 'view',
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
}
?>