<?php
class Offline extends CB_Controller {

    private $upload_path;
    private $goods_upload_path;
    private $title_upload_path;

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');
    private $tem_type;

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring', 'funn', 'mpoplib'));
        $this->tem_type = $this->mpoplib->get_type();
        $this->upload_path = config_item('uploads_dir') .'/mpop/';
		$this->funn->create_upload_dir($this->upload_path);
		$this->funn->create_upload_dir($this->upload_path . "Y". date("Y") ."/");
		$this->funn->create_upload_dir($this->upload_path . "Y". date("Y") ."/". date("m") ."/");
		$this->upload_path = $this->upload_path . "Y". date("Y") ."/". date("m") ."/";
        $this->goods_upload_path =  $this->upload_path . 'goods/';
        $this->funn->create_upload_dir($this->goods_upload_path);
        $this->title_upload_path =  $this->upload_path . 'title/';
        $this->funn->create_upload_dir($this->title_upload_path);
    }

    public function index() {
        if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
        $view['perpage'] = $this->input->get("per_page") ? $this->input->get("per_page") : 10;
		$view['page'] = $this->input->get("page") ? $this->input->get("page") : 1;

        $where = array(
            'pmd_mem_id' => $this->member->item('mem_id')
          , 'pmd_useyn' => 'Y'
        );

        $view['tr'] = $this->db
            ->select('count(1) as cnt')
            ->from('cb_pop_mobile_data')
            ->where($where)
            ->get()->row()->cnt;

        $view['list'] = $this->db
            ->select('*')
            ->from('cb_pop_mobile_data')
            ->where($where)
            ->order_by('pmd_id', 'desc')
            ->limit($view['perpage'], ($view['page'] - 1) * $view['perpage'])
            ->get()->result();

        $this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['tr'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['page']);
		$view['page_html'] = $this->pagination->create_links();
		// $view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

        $layoutconfig = array(
			'path' => 'mpop/offline',
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

    public function backup_excel(){
        $pmd_id = $this->input->post('id');
        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

	    // 라이브러리를 로드한다.
	    $this->load->library('excel');

	    // 시트를 지정한다.
	    $this->excel->setActiveSheetIndex(0);
	    $this->excel->getActiveSheet()->setTitle('Sheet1');

	    // 필드명을 기록한다.
	    // 글꼴 및 정렬
	    $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
	        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'd8e4bc')),
	        'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
	    ),	'A1:G1');

	    $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	    // $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        // $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "상품코드 (선택)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "상품명 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "규격 (선택)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "정상가 (선택)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "할인가 (필수)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "옵션 (선택)");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "이미지 (선택)");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "총결제해야할 금액");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "결제금액");
	    // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "미수금액");

	    $cnt = 1;
	    $row = 2;


	    //number_format(($r->dep_datetime, 1)
	    foreach($list as $r) {
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->goods_barcode." ");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->goods_name);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->goods_option);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->goods_price);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->goods_dcprice);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->goods_option2);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->goods_imgpath);
	        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->dep_deposit_datetime);
	        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->dep_cash_request);
	        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $r->dep_cash);
	        // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format($r->dep_cash_request - abs($r->dep_cash)));
	        $row++;
	        $cnt++;
	    }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="'.$data_id.'_goods_conner_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');
    }

    public function write() {
        $id = $this->input->get('id');
        if (!empty($id)){
            $view['id'] = $this->input->get('id');
            $view['type'] = json_decode($this->get_pm_data($view['id'])->pmd_data)[0]->type;
        } else {
            $view['id'] = 0;
            $view['type'] = 'mpop_type_a';
        }

        $layoutconfig = array(
			'path' => 'mpop/offline',
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

    public function write_body() {
        $view = array();
        $view['id'] = $this->input->post('id');
        $view['type'] = $this->input->post('type');
        $td = $this->tem_type[$view['type']];
        $view['type_data'] = json_encode($td, JSON_UNESCAPED_UNICODE);

        $view['data'] = $this->get_pm_data($view['id']);

        $this->load->view('mpop/offline/bootstrap/write_body',$view);
    }

    public function write_body_test() {
        $view = array();
        $this->load->view('mpop/offline/bootstrap/write_body_test',$view);
    }

    public function write_grid() {
        $view = array();
        $view['id'] = $this->input->post('id');
        $view['type'] = $this->input->post('type');
        $td = $this->tem_type[$view['type']];
        $view['type_data'] = json_encode($td, JSON_UNESCAPED_UNICODE);

        $view['data'] = $this->get_pm_data($view['id']);

        if (!empty($view['data']->pmd_data)){
            $detail = json_decode($view['data']->pmd_data);
        }

        //handsontable colHeader 제작
        $view['col_h'] = "['상품 이미지', '상품 이름', '상품 가격', '상품 할인가격'";
        $view['max_option'] = max($td['block']['goods_option_cnt']);
        $view['goods_cnt'] = array_sum($td['block']['goods_cnt']);
        for($a=0;$a<$view['max_option'];$a++) $view['col_h'] .= ", '옵션" . ($a + 1) . "'";
        $view['col_h'] .= $td['block']['goods_badge'] ? ", '뱃지 이미지'" : '';
        $view['col_h'] .= "]";

        $view['row_h'] = "[";
        $view['grid_data'] = "[";

        foreach($td['block']['goods_cnt'] as $key => $a){
            //handsontable rowHeader 제작
            for($b=0;$b<$a;$b++){
                $view['row_h'] .= ($b == 0 ? ($key != 0 ? ", '" . (($key + 1) . '번째 블록') . "'" : "'" . (($key + 1) . '번째\\r\\n블록') . "'") : ", ''");
                if(!empty($detail)){
                    if (!empty($detail[0]->block[$key]->goods[$b])){
                        $c = $detail[0]->block[$key]->goods[$b]->data;
                        $view['grid_data'] .= ($key ==0 && $b == 0 ? "" : ",") . "";
                        $view['grid_data'] .= "['" . $c->image_path . "', '" . $c->name . "', '" . $c->price . "', '" . $c->dcprice . "'";
                        for($d=0;$d<$view['max_option'];$d++) $view['grid_data'] .= ", '" . $c->option[$d] . "'";
                        $view['grid_data'] .= $td['block']['goods_badge'] ? ", '" . $c->badge_path . "'" : '';
                        $view['grid_data'] .= "]";
                    } else {
                        $view['grid_data'] .= $key ==0 && $b == 0 ? "" : ",";
                        $view['grid_data'] .= "['', '', '', '', ''" . str_repeat(", ''", $view['max_option']) . "]";
                    }
                } else {
                    $view['grid_data'] .= $key ==0 && $b == 0 ? "" : ",";
                    $view['grid_data'] .= "['', '', '', '', ''" . str_repeat(", ''", $view['max_option']) . "]";
                }
            }

        }

        $view['row_h'] .= "]";
        $view['grid_data'] .= "]";

        //대분류 리스트
		$sql = "SELECT id, code, description FROM cb_images_category WHERE useyn = 'Y' AND parent_id IS NULL ORDER BY id ASC";
        $view['category_list'] = $this->db->query($sql)->result();

		//소분류 리스트
		if($view['searchCate1'] != ''){ //대분류가 있는 경우
			$sql = "SELECT id, code, description FROM cb_images_category WHERE parent_id = '". $view['searchCate1'] ."' and useyn = 'Y' ORDER BY id ASC";
			$view['category_sub'] = $this->db->query($sql)->result();
		}else{
			$view['category_sub'] = null;
		}

        $this->load->view('mpop/offline/bootstrap/write_grid',$view);
    }

    private function get_pm_data($id){
        return $this->db
            ->select('*')
            ->from('cb_pop_mobile_data')
            ->where('pmd_id', $id)
            ->get()->row();
    }

    //이미지 저장
	public function imgfile_save(){
		$imgpath = "";
        $flag = $this->input->post('flag');
		if(empty($_FILES['imgfile']['name']) == false){ //이미지 업로드
            if ($flag == '1'){
                $path = $this->goods_upload_path;
            } else if ($flag == '0'){
                $path = $this->title_upload_path;
            }
			$img_data = $this->img_upload($path, 'imgfile');
            if( is_array($img_data) && !empty($img_data) ){
				$imgpath = '/' . $path . $img_data['file_name']; //이미지 경로
			}
		}
		$result = array();
		$result['code'] = '0';
		$result['imgpath'] = $imgpath;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//이미지 업로드 함수
    public function img_upload($upload_img_path = null, $field_name = null, $size = null, $thumb = ""){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_upload Start");
		if( is_dir($upload_img_path)  == false ){
			//alert('해당 디렉도리가 존재 하지 않음'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 해당 디렉도리가 존재 하지 않음");
		}
		if ( is_null($field_name) ){
			//alert('필드값을 기입하지 않았습니다.'); exit();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 필드값을 기입하지 않았습니다.");
		}
		if( is_null($size) || is_numeric($size) == false || $size == ''){
			$size = 10 * 1024 * 1024; //파일 제한 사이지(byte 단위) (10MB)
		}
		if(is_array($_FILES) && empty($_FILES[$field_name]['name']) == false){
			$upload_img_config = '';
			$upload_img_config['upload_path'] = $upload_img_path;
			$upload_img_config['allowed_types'] = 'gif|jpg|png|jpeg';
			$upload_img_config['encrypt_name'] = true;
			$upload_img_config['max_size'] = $size;
			$this->load->library('upload',$upload_img_config);
            $this->load->library('image_api');
			$this->upload->initialize($upload_img_config);
			if ($this->upload->do_upload($field_name)) {
				//업로드 성공
				$filedata = $this->upload->data();
				if($thumb != ""){ //썸네일 추가
					$this->load->library('image_lib');
					$this->image_lib->clear();
					$simgconfig = array();
					$simgconfig['image_library'] = 'gd2';
					$simgconfig['source_image']	= $filedata['full_path'];
					$simgconfig['maintain_ratio'] = TRUE;
					$simgconfig['width']	= $thumb; //썸네일 가로 사이즈
					$simgconfig['height']= $thumb; //썸네일 가로 사이즈
					$simgconfig['new_image'] = $filedata['file_path'].$filedata['raw_name'].'_thumb'.$filedata['file_ext'];

					$this->image_lib->initialize($simgconfig);
					if(!$this->image_lib->resize()) {
					}else{
                        $str_path_thumb = str_replace("/var/www/igenie", "", $simgconfig['new_image']);
                        $this->image_api->api_image($str_path_thumb);
                    }

				}
                $str_path = str_replace("/var/www/igenie", "", $filedata['full_path']);
                $this->image_api->api_image($str_path);
				return $filedata;
			}else{
				//업로드 실패
				$error = $this->upload->display_errors();
				//alert($error, '/maker'); exit('');
			}
		} else {
			// alert('파일이 업로드 되지 않았습니다.'); exit('');
		}
	}

    public function save_mpop(){
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $arr_data = $this->input->post('arr_data');

        if ($id > 0){
            $this->db
                ->set('pmd_data', $arr_data)
                ->set('pmd_title', $title)
                ->where('pmd_id', $id)
                ->update('cb_pop_mobile_data');
        } else {
            $this->db
                ->set('pmd_mem_id', $this->member->item('mem_id'))
                ->set('pmd_title', $title)
                ->set('pmd_data', $arr_data)
                ->insert('cb_pop_mobile_data');
        }
        $result = array();
		$result['code'] = 1;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

    public function print_a4(){
        $view['id'] = $this->input->get('id');
        $view['data'] = $this->get_pm_data($view['id']);
        $view['type'] = json_decode($view['data']->pmd_data)[0]->type;
        $td = $this->tem_type[$view['type']];
        $view['type_data'] = json_encode($td, JSON_UNESCAPED_UNICODE);

        $layoutconfig = array(
			'path' => 'mpop/offline',
			'layout' => 'empty',
			'skin' => 'print_a4',
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

    //상품 엑셀 업로드
	public function excel_upload(){
	    $result = array();
        $pos = $this->input->post('pos');
        $goods_cnt = $this->input->post('goods_cnt');

	    if(file_exists($_FILES['file']['tmp_name'])) {
			$this->load->library("excel");
			$inputFileType = PHPExcel_IOFactory::identify($_FILES['file']['tmp_name']);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly( true );
			$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);
			$sheetsCount = $objPHPExcel->getSheetCount();

			// 쉬트별로 읽기
			for($i = 0; $i < $sheetsCount; $i++){
				$sheetIndex = $objPHPExcel->setActiveSheetIndex($i);
				$sheet = $objPHPExcel->getActiveSheet();
				$highestRow = $sheet->getHighestRow();
				$highestColumn = $sheet->getHighestColumn();
                $pos_row = 1;
                if($pos == 'together'){
                    $pos_row = 2;
                }
				for ($row = $pos_row; $row <= $highestRow; $row++){ //1번 줄부터 읽기
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
					if($rowData[0][0] != ""){
                        $result[$row-$pos_row][1] = $rowData[0][2];
                        $result[$row-$pos_row][2] = $rowData[0][6];
                        $result[$row-$pos_row][3] = $rowData[0][9];
					}
                    if ($row == $goods_cnt + 1){
                        break;
                    }
				}
			}
		}

	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

    public function get_type_list(){
        $html = '';
        foreach($this->tem_type as $key => $a){
            $html .= '<dl>';
            $html .= '    <dt>';
            $html .= '        <p class="hidetitle">상품개수 <span>총 ' . array_sum($a['block']['goods_cnt']) . ' 개</span> 세로</p>';
            $html .= '    </dt>';
            $html .= '    <dd onclick="change_type(\'' . $key . '\')">';
            $html .= '        <img src="' . $a['type']['src'] . '" alt="' . $a['type']['name'] . '">';
            $html .= '    </dd>';
            $html .= '</dl>';
        }

		$result = array();
        $result['html'] = $html;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

    //이미지 라이브러리 조회
	public function search_library(){
		$result = array();
		$result['perpage'] = ($this->input->post("perpage")) ? $this->input->post("perpage") : 28; //개시물수
		$result['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1; //현재페이지
		$result['searchCate1'] = $this->input->post("searchCate1"); //대분류
		$result['searchCate2'] = $this->input->post("searchCate2"); //소분류
		$result['sn'] = ($this->input->post("searchnm")) ? $this->input->post("searchnm") : "name"; //검색이름
		$result['sf'] = $this->input->post("searchstr"); //검색내용
		$result['library_type'] = $this->input->post("library_type"); //라이브러리 타입
		$result['page_yn'] = ($this->input->post("page_yn")) ? $this->input->post("page_yn") : "N"; //페이징 처리여부
        $sel_img_flag = $this->input->post("sel_img_flag");
		$html = "";
		$where = "";
		if($result['library_type'] == "goods"){ //최근상품 조회
			//---------------------------------------------------------------------------------------------------------------------
			// 최근상품 조회
			//---------------------------------------------------------------------------------------------------------------------
			$tbl = "cb_pop_screen_goods";
			$where .= "AND psg_mem_id = '". $this->member->item("mem_id") ."' ";
			if(!empty($result['sf'])) {
				$orstr = explode(' ', $result['sf']);
				$orcnt = count($orstr);
				if($orcnt > 1) {
					$where .= "AND ( ";
					$prefix = '';
					for($i=0; $i<$orcnt; $i++){
						$where .= $prefix." psg_name LIKE '%". addslashes($orstr[$i]) ."%' ";
						$prefix = "AND ";
					}
					$where .= " ) ";
				} else {
					$where .= " AND psg_name LIKE '%". addslashes($orstr[0]) ."%' ";
				}
			}else{
				$where .= "AND psg_name != '' ";
			}
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > where : ". $where);

			//상품 전체수
			$sql = "
			SELECT
				COUNT(*) AS cnt
			FROM ". $tbl ."
			WHERE psg_id IN (
				SELECT MAX(psg_id)
				FROM ". $tbl ." a
				WHERE psg_imgpath != ''
				AND psg_dcprice != ''
				". $where ."
				GROUP BY psg_name
			) ";
			$result['total'] = $this->db->query($sql)->row()->cnt;

			//상품 현황
			$sql = "
			SELECT
				  psg_id AS img_id /* 전단상품번호 */
				, psg_name AS img_name /* 상품명 */
				, psg_price /* 정상가 */
				, psg_dcprice /* 할인가 */
				, psg_imgpath AS img_path /* 사진경로 */
			FROM ". $tbl ."
			WHERE psg_id IN (
				SELECT MAX(psg_id)
				FROM ". $tbl ." a
				WHERE psg_imgpath != ''
				AND psg_dcprice != ''
				". $where ."
				GROUP BY psg_name
			)
			ORDER BY psg_name ASC
			LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'] ;
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$list = $this->db->query($sql)->result();
			if(!empty($list)){
				foreach ($list as $r){
					$img_name = $r->img_name;
					if(!empty($result['sf'])) {
						//$img_name = str_replace($result['sf'], "<font color='red'>". addslashes($result['sf']) ."</font>", $img_name);
						$orstr = explode(' ', $result['sf']);
						$orcnt = count($orstr);
						for($i=0; $i<$orcnt; $i++){
							$img_name = str_replace($orstr[$i], "<font color='red'>". addslashes($orstr[$i]) ."</font>", $img_name);
						}
					}
                    $last_dot = strripos($r->img_path, '.');
                    $file_path = substr($r->img_path, 0, $last_dot);
                    $file_ext = substr($r->img_path, $last_dot + 1, count($r->img_path));
                    $thumb_path = $file_path . '_thumb' . $file_ext;
					$html .= "
					<li class=\"img_select\" title=\"".$r->img_name."\">
						<a onClick=\"set_goods_library('". $thumb_path ."', '". addslashes($r->img_name) ."', '". addslashes($r->psg_price) ."', '". addslashes($r->psg_dcprice) ."')\">
							<input type=\"radio\" name=\"chk_image\" id=\"chk". $r->img_id ."\" value=\"". $r->img_id ."\" title=\"". $r->img_name ."\"><label for=\"chk". $r->img_id ."\"></label>
							<div class=\"thumb_img\" style=\"background-image: url('". $thumb_path ."');background-size: contain;\"><input type=\"hidden\" id=\"img_". $r->id ."\" value=\"". $thumb_path ."\"></div>
						</a>
						<div class=\"goods_name\">". $img_name ."</div>
						<div class=\"goods_price\" style=\"display:". (($r->psg_price != "") ? "" : "none") ."\">". $r->psg_price ."</div>
						<div class=\"goods_dcprice\" style=\"display:". (($r->psg_dcprice != "") ? "" : "none") ."\">". $r->psg_dcprice ."</div>
					</li>";
				}
			}else{
				$html .= "<li style=\"width:100%;\">no data.</li>";
			}
		}else if($result['library_type'] == "keep"){ //이미지보관함 조회
			//---------------------------------------------------------------------------------------------------------------------
			// 이미지보관함 조회
			//---------------------------------------------------------------------------------------------------------------------
			$where = " WHERE img_useyn = 'Y' ";
			$where .= "AND img_mem_id = '". $this->member->item("mem_id") ."' ";
			if(!empty($result['sf'])) {
				$orstr = explode(' ', $result['sf']);
				$orcnt = count($orstr);
				if($orcnt > 1) {
					$where .= "AND ( ";
					$prefix = '';
					for($i=0; $i<$orcnt; $i++){
						$where .= $prefix." img_name LIKE '%". addslashes($orstr[$i]) ."%' ";
						$prefix = "AND ";
					}
					$where .= " ) ";
				} else {
					$where .= " AND img_name LIKE '%". addslashes($orstr[0]) ."%' ";
				}
			}

			//이미지 전체수
			$sql = "select count(1) as cnt FROM cb_member_images ". $where;
			$result['total'] = $this->db->query($sql)->row()->cnt;

			//이미지 현황
			$sql = "
			SELECT
				  img_id /* 이미지번호 */
				, img_name /* 이미지 이름 */
				, img_path /* 이미지 경로 */
				, img_path AS img_path_thumb /* 썸네일 이미지 경로 */
			FROM cb_member_images
			". $where ."
			ORDER BY img_id DESC
			LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$list = $this->db->query($sql)->result();
			if(!empty($list)){
				foreach ($list as $r){
					$img_name = $this->funn->StringCut($r->img_name, 20, "..");
					if(!empty($result['sf'])) {
						//$img_name = str_replace($result['sf'], "<font color='red'>". addslashes($result['sf']) ."</font>", $img_name);
						$orstr = explode(' ', $result['sf']);
						$orcnt = count($orstr);
						for($i=0; $i<$orcnt; $i++){
							$img_name = str_replace($orstr[$i], "<font color='red'>". addslashes($orstr[$i]) ."</font>", $img_name);
						}
					}
					$html .= "
					<li class=\"img_select\" title=\"".$r->img_name."\" >
						<a onClick=\"set_img_library('". $r->img_path ."')\">
							<input type=\"radio\" name=\"chk_image\" id=\"chk". $r->img_id ."\" value=\"". $r->img_id ."\" title=\"". $r->img_name ."\"><label for=\"chk". $r->img_id ."\"></label>
							<div class=\"thumb_img\" style=\"background-image: url('". $r->img_path_thumb ."');background-size: contain;\"><input type=\"hidden\" id=\"img_". $r->id ."\" value=\"". $r->img_path ."\"></div>
						</a>
						<div class=\"file_name\">". $img_name ."</div>
					</li>";
				}
			}else{
				$html .= "<li style=\"width:100%;\">no data.</li>";
			}
		}else{ //이미지 라이브러리 조회
			//---------------------------------------------------------------------------------------------------------------------
			// 이미지 라이브러리 조회
			//---------------------------------------------------------------------------------------------------------------------
			$where = " WHERE i.img_useyn = 'Y' ";
			if($result['searchCate1'] != ''){ //대분류
				$where .= " AND img_category1 = '". $result['searchCate1'] ."' ";
			}
			if($result['searchCate2'] != ''){ //소분류
				$where .= " AND img_category2 = '". $result['searchCate2'] ."' ";
			}
			if(!empty($result['sf'])) {
				$orstr = explode(' ', $result['sf']);
				$orcnt = count($orstr);
				if($orcnt > 1) {
					$where .= " AND ( ";
					$prefix = '';
					for($i=0; $i<$orcnt; $i++){
						$where .= $prefix." i.img_". $result['sn'] ." LIKE '%". addslashes($orstr[$i]) ."%' ";
						$prefix = " AND ";
					}
					$where .= " ) ";
				} else {
					$where .= " AND i.img_". $result['sn'] ." LIKE '%". addslashes($orstr[0]) ."%' ";
				}
			}

			//이미지 전체수
			$sql = "select count(1) as cnt FROM cb_images i ". $where;
			$result['total'] = $this->db->query($sql)->row()->cnt;

            $sql = "
            SELECT
                 img_id /* 이미지번호 */
               , img_name /* 이미지 이름 */
               , img_path /* 이미지 경로 */
               , (CASE when img_path_thumb != '' THEN img_path_thumb ELSE img_path END) AS img_path_thumb /* 썸네일 이미지 경로 */
               , cpsg.psg_id
           FROM cb_images i left join (
           select
				*
			from
				(
				(select
					psg_imgpath,
					max(psg_id) as psg_id
				from
					cb_pop_screen_goods
				where
					psg_imgpath <> ''
					and psg_mem_id = '". $this->member->item("mem_id") ."'
				group by
					psg_imgpath order by psg_id desc
				limit 10) as tmp
				)
           ) cpsg on i.img_path = cpsg.psg_imgpath
			". $where ."
			ORDER BY cpsg.psg_id desc, i.img_name ASC
			LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];
			$list = $this->db->query($sql)->result();
			if(!empty($list)){
				foreach ($list as $r){
					$img_name = $r->img_name;
					if(!empty($result['sf'])) {
						$orstr = explode(' ', $result['sf']);
						$orcnt = count($orstr);
						for($i=0; $i<$orcnt; $i++){
							$img_name = str_replace($orstr[$i], "<font color='red'>". addslashes($orstr[$i]) ."</font>", $img_name);
						}
					}
                    $last_dot = strripos($r->img_path, '.');
                    $file_path = substr($r->img_path, 0, $last_dot);
                    $file_ext = substr($r->img_path, $last_dot);
                    $thumb_path = $file_path . '_thumb' . $file_ext;
                    if ($sel_img_flag == '1'){
                        $html .= "
    					<li class=\"img_select\" title=\"".$r->img_name."\" >
    						<a onClick=\"set_img_library('". $thumb_path ."')\">
    							<input type=\"radio\" name=\"chk_image\" id=\"chk". $r->img_id ."\" value=\"". $r->img_id ."\" title=\"". $r->img_name ."\"><label for=\"chk". $r->img_id ."\"></label>
    							<div class=\"thumb_img\" style=\"background-image: url('". $thumb_path ."');background-size: contain;\"><input type=\"hidden\" id=\"img_". $r->id ."\" value=\"". $thumb_path ."\"></div>
    						</a>
    						<div class=\"file_name\">". $img_name ."</div>
    					</li>";
                    } else if ($sel_img_flag == '2'){
                        $html .= "
    					<li class=\"img_select\" title=\"".$r->img_name."\" >
    						<a onClick=\"set_img_library2('". $thumb_path ."')\">
    							<div class=\"thumb_img\" style=\"background-image: url('". $thumb_path ."');background-size: contain;\"><input type=\"hidden\" id=\"img_". $r->id ."\" value=\"". $thumb_path ."\"></div>
    						</a>
    						<div class=\"file_name\">". $img_name ."</div>
    					</li>";
                    }

				}
			}else{
				$html .= "<li style=\"width:100%;\">no data.</li>";
			}
		}

		//페일징 처리
		if($result['page_yn'] == "Y"){
			$this->load->library('pagination');
			$page_cfg['link_mode'] = 'searchLibraryPage';
			$page_cfg['base_url'] = '';
			$page_cfg['total_rows'] = $result['total'];
			$page_cfg['per_page'] = $result['perpage'];
			$this->pagination->initialize($page_cfg);
			$this->pagination->cur_page = intval($result['page']);
			$result['page_html'] = $this->pagination->create_links();
			$result['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='searchLibraryPage($1)'><a herf='#' ",$result['page_html']);
		}else{
			$result['page_html'] = "";
		}

		$result['html'] = $html;
		$result['page']++;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

    public function copy(){
        $result = array();
        $id = $this->input->post('id');
        $sql = "
            insert into cb_pop_mobile_data(pmd_mem_id, pmd_title, pmd_data)
            select pmd_mem_id, concat(pmd_title, date_format(now(), '(복사본)_%Y%m%d%H%i%s')), pmd_data
            from cb_pop_mobile_data
            where pmd_id = " . $id . "
        ";
        $this->db->query($sql);

	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

    public function del(){
        $result = array();
        $id = $this->input->post('id');
        $sql = "
            update cb_pop_mobile_data
            set pmd_useyn = 'N'
            where pmd_id = " . $id . "
        ";
        $this->db->query($sql);

	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

    public function test() {

        $layoutconfig = array(
			'path' => 'mpop/offline',
			'layout' => 'layout',
			'skin' => 'test',
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

    public function set_print_log(){
        $page = $this->input->post('page');
        $type = $this->input->post('type');
        $print = $this->input->post('print');
        $this->db
            ->set('ml_mem_id', $this->member->item('mem_id'))
            ->set('ml_ip', $_SERVER["REMOTE_ADDR"])
            ->set('ml_page', $page)
            ->set('ml_type', $type)
            ->set('ml_print_type', $print)
            ->insert('cb_mpop_log');

        $result = array();
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }
}
?>
