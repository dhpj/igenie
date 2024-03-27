<?php
class Compare extends CB_Controller {
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

    //행사가격 비교 메인
    public function index(){
        if($this->member->is_member() == false) { //로그인이 안된 경우
            redirect('login');
        }else{
          if($this->member->item("mem_level") < 100) { //로그인이 안된 경우
              redirect('/spop/screen');
          }
        }
		$view = array();
		$view['view'] = array();

		$skin = "main";
		$add = $this->input->get("add");
		$view['add'] = $add;
		if($add != "") $skin .= $add;

		$view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
		$view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
		$view['psdtype'] = ($this->input->post("psdtype")) ? $this->input->post("psdtype") : "S"; //타입(S.전단, C.쿠폰)
        $view['search_type'] = $this->input->get("search_type");
        $view['search_txt'] = $this->input->get("search_txt");

		$where = "WHERE 1=1 ";
		//$where .= "AND psd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND psd_type = '". $view['psdtype'] ."' /* 타입(S.전단, C.쿠폰) */ ";
		$where .= "AND psd_useyn = 'Y' /* 사용여부 (Y/N) */ ";
		$where .= "AND psd_viewyn = 'Y' /* 스마트전단 샘플 뷰 (Y/N) */ ";
		//$where .= "AND ( psd_mng_yn is null or psd_mng_yn = 'N') /* 전단관리자 목록  (Y/N) */ ";

        if(!empty($view['search_txt'])){
            if ($view['search_type'] == '1'){
                $where .= "AND b.mem_username like '%" . $view['search_txt'] . "%' /* 검색 업체명 */ ";
            } else if ($view['search_type'] == '2'){
                $where .= "AND a.psd_title like '%" . $view['search_txt'] . "%' /* 검색 업체명 */ ";
            }
        }

		//스마트전단 dhn 계정 최근 등록건 조회
		$sql = "
		SELECT
			 psd_code /* 스마트전단 관리자 최근 등록건 코드 */
		FROM cb_pop_screen_data a
        LEFT JOIN cb_member b on a.psd_mem_id = b.mem_id
		". $where ."
		AND psd_mem_id = '3' /* dhn 계정 */
		ORDER BY psd_id DESC
		LIMIT 1";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['max_code'] = $this->db->query($sql)->row()->psd_code;

		//스마트전단 전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_pop_screen_data a
		LEFT JOIN cb_member b ON b.mem_id = a.psd_mem_id
		". $where ." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//스마트전단 리스트
		$sql = "
		SELECT
			  psd_id /* 전단번호 */
			, psd_code /* 코드 */
			, psd_title /* 제목 */
			, psd_date /* 행사기간 */
			, DATE_FORMAT(psd_cre_date, '%Y.%m') AS psd_cre_ym /* 등록년월 */
			, DATE_FORMAT(psd_cre_date, '%d') AS psd_cre_dd /* 등록일 */
			/* , (
				SELECT IFNULL(SUM(mst_qty),0) AS cnt
				FROM cb_wt_msg_sent
				WHERE mst_id IN (
					SELECT mst_id FROM cb_alimtalk_ums u WHERE u.psd_code = a.psd_code
				)
			) AS msq_cnt */ /* 발송건수 */
			, b.mem_userid /* 업체ID */
			, b.mem_username /* 업체명 */
			, b.mem_stmall_yn /* 파트너 스마트전단 주문하기 사용여부 */
			, psd_order_yn /* 주문하기 사용여부 */
			, psd_order_sdt /* 주문하기 시작일자 */
			, psd_order_edt /* 주문하기 종료일자 */
		FROM cb_pop_screen_data a
		LEFT JOIN cb_member b ON b.mem_id = a.psd_mem_id
		". $where ."
		ORDER BY psd_id DESC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		//echo $sql ."<br>";
		//echo $where ."<br>";
		$view['data_list'] = $this->db->query($sql)->result();

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		//$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

        $layoutconfig = array(
            'path' => 'mall/compare',
            'layout' => 'layout',
            'skin' => $skin,
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

    //스마트 전단 샘플 ( 고객용 )
    public function sample(){
        if($this->member->is_member() == false) { //로그인이 안된 경우
            redirect('login');
        }
        $view = array();
        $view['view'] = array();

        $skin = "sample";
        $add = $this->input->get("add");
        $view['search_type'] = $this->input->get("search_type");
        $view['search_txt'] = $this->input->get("search_txt");

        $view['add'] = $add;
        if($add != "") $skin .= $add;

        $view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $view['psdtype'] = ($this->input->post("psdtype")) ? $this->input->post("psdtype") : "S"; //타입(S.전단, C.쿠폰)

        $where = "WHERE 1=1 ";
        //$where .= "AND psd_mem_id = '". $this->member->item("mem_id") ."' ";
        $where .= "AND psd_type = '". $view['psdtype'] ."' /* 타입(S.전단, C.쿠폰) */ ";
        $where .= "AND psd_useyn = 'Y' /* 사용여부 (Y/N) */ ";
        $where .= "AND psd_viewyn = 'Y' /* 스마트전단 샘플 뷰 (Y/N) */ ";
        $where .= "AND psd_sample = 'Y' /* 스마트전단 샘플 뷰 (Y/N) */ ";
        $where .= "AND psd_mng_yn = 'Y' /* 전단관리자 목록  (Y/N) */ ";

        if(!empty($view['search_txt'])){
            if ($view['search_type'] == '1'){
                $where .= "AND b.mem_username like '%" . $view['search_txt'] . "%' /* 검색 업체명 */ ";
            } else if ($view['search_type'] == '2'){
                $where .= "AND a.psd_title like '%" . $view['search_txt'] . "%' /* 검색 업체명 */ ";
            }
        }

        //스마트전단 dhn 계정 최근 등록건 조회
        $sql = "
		SELECT
			 psd_code /* 스마트전단 관리자 최근 등록건 코드 */
		FROM cb_pop_screen_data a
        LEFT JOIN cb_member b on a.psd_mem_id = b.mem_id
		". $where ."
		AND psd_mem_id = '3' /* dhn 계정 */
		ORDER BY psd_id DESC
		LIMIT 1";
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
        //echo $sql ."<br>";
        $view['max_code'] = $this->db->query($sql)->row()->psd_code;

        //스마트전단 전체수
        $sql = "
		SELECT count(1) as cnt
		FROM cb_pop_screen_data a
		LEFT JOIN cb_member b ON b.mem_id = a.psd_mem_id
		". $where ." ";
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
        //echo $sql ."<br>";
        $view['total_rows'] = $this->db->query($sql)->row()->cnt;

        //스마트전단 리스트
        $sql = "
		SELECT
			  psd_id /* 전단번호 */
			, psd_code /* 코드 */
			, psd_title /* 제목 */
			, psd_date /* 행사기간 */
			, DATE_FORMAT(psd_cre_date, '%Y.%m') AS psd_cre_ym /* 등록년월 */
			, DATE_FORMAT(psd_cre_date, '%d') AS psd_cre_dd /* 등록일 */
			/* , (
				SELECT IFNULL(SUM(mst_qty),0) AS cnt
				FROM cb_wt_msg_sent
				WHERE mst_id IN (
					SELECT mst_id FROM cb_alimtalk_ums u WHERE u.psd_code = a.psd_code
				)
			) AS msq_cnt */ /* 발송건수 */
			, b.mem_userid /* 업체ID */
			, b.mem_username /* 업체명 */
			, b.mem_stmall_yn /* 파트너 스마트전단 주문하기 사용여부 */
			, psd_order_yn /* 주문하기 사용여부 */
			, psd_order_sdt /* 주문하기 시작일자 */
			, psd_order_edt /* 주문하기 종료일자 */
		FROM cb_pop_screen_data a
		LEFT JOIN cb_member b ON b.mem_id = a.psd_mem_id
		". $where ."
		ORDER BY psd_id DESC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
        //echo $sql ."<br>";
        //echo $where ."<br>";
        $view['data_list'] = $this->db->query($sql)->result();

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        //$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > page_html : ".  $view['page_html']);
        $layoutconfig = array(
            'path' => 'mall/compare',
            'layout' => 'layout',
            'skin' => $skin,
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

	//행사가격  미리보기
	public function preview(){
	    $add = $this->input->post("add");
	    $code = ($this->input->post("code")) ? $this->input->post("code") : ""; //스마트전단 코드
		$result = array();
		//스마트전단 데이타
		$sql = "
		SELECT
			  a. *
			, b.tem_imgpath /* 텝플릿 이미지 경로 */
			, b.tem_bgcolor /* 텝플릿 배경색 */
			, b.tem_useyn /* 템플릿 사용여부(Y.사용, N.사용안함, S.직접입력) */
			, c.mem_stmall_yn /* 파트너 스마트전단 주문하기 사용여부 */
		FROM cb_pop_screen_data a
		LEFT JOIN cb_design_templet b ON a.psd_tem_id = b.tem_id
		LEFT JOIN cb_member c ON a.psd_mem_id = c.mem_id
		WHERE psd_code = '". $code ."'
		AND psd_useyn = 'Y' ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 Query : ".$sql);
		$result['screen_data'] = $this->db->query($sql)->row();
		$psd_id = $result['screen_data']->psd_id; //전단번호
		$mem_id = $result['screen_data']->psd_mem_id; //회원번호
		$psd_ver_no = $result['screen_data']->psd_ver_no; //버전번호
		//echo "psd_id : ". $psd_id .", mem_id : ". $mem_id ."<br>";

		//STEP1. 대표상품 등록 조회
		$sql = "
		SELECT a.*
			, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_badge LIMIT 1) AS badge_imgpath /* 뱃지 이미지 */
		FROM cb_pop_screen_goods a
		WHERE psg_psd_id = '". $psd_id ."' /* 전단번호 */
		and psg_step = '1' /* 스텝 */
		ORDER BY psg_seq ASC ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > STEP1 Query : ".$sql);
		$result['screen_step1'] = $this->db->query($sql)->result();

		if($psd_ver_no == 1){ //버전 1의 경우
			$sort = "psg_step ASC,  psg_step_no ASC, psg_seq ASC";
		}else{
			$sort = "psg_step_no ASC, psg_seq ASC";
		}

		//코너별 상품등록 조회
		$sql = "
		SELECT a.*
			, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_tit_id LIMIT 1) AS tit_imgpath /* 코너별 이미지 */
			, (SELECT COUNT(*) FROM cb_pop_screen_goods b WHERE b.psg_psd_id = a.psg_psd_id AND b.psg_step = a.psg_step AND b.psg_step_no = a.psg_step_no) AS rownum /* 코너별 등록수 */
			, (SELECT COUNT(*) FROM cb_pop_screen_goods b WHERE b.psg_psd_id = a.psg_psd_id AND b.psg_step = a.psg_step AND psg_seq = 0) AS gronum /* 코너수 */
			, (SELECT tit_imgpath FROM cb_design_title dt WHERE dt.tit_id = a.psg_badge LIMIT 1) AS badge_imgpath /* 뱃지 이미지 */
		FROM cb_pop_screen_goods a
		WHERE psg_psd_id = '". $psd_id ."' /* 전단번호 */
		AND psg_step > 1 /* 스텝 */
		ORDER BY ". $sort ." ";
		//echo $sql ."<br>";
		$result['screen_box'] = $this->db->query($sql)->result();

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		$json = str_replace('null', '""', $json);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단 샘플 뷰 (N) 처리
	public function viewn(){
		$data_id = $this->input->post("data_id"); //전단번호
		$where = array();
		$where["psd_id"] = $data_id; //전단번호
		$data = array();
		$data["psd_viewyn"] = "N"; //마트전단 샘플 뷰 (Y/N)
		$rtn = $this->db->update("cb_pop_screen_data", $data, $where); //이미지 라이브러리 수정

		$result = array();
		$result['code'] = '0';
		$result['msg'] = $data_id;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//스마트전단을 관리자가 샘플로 지정
	public function samplen(){
	    $data_id = $this->input->post("data_id"); //전단번호
	    $where = array();
	    $where["psd_id"] = $data_id; //전단번호
	    $data = array();
	    $data["psd_sample"] = "N"; //마트전단 샘플 지정 유무
	    $rtn = $this->db->update("cb_pop_screen_data", $data, $where); //이미지 라이브러리 수정

	    $result = array();
	    $result['code'] = '0';
	    $result['msg'] = $data_id;
	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

}
?>
