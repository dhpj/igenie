<?php
class Agreeview extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
 
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
        $this->load->library(array('querystring', 'google_url_api'));
    }
    
	//알림톡 발송내용 > 자세히 보러가기
	public function index(){
		$view = array();
		$view['idx'] = $this->uri->segment(3); //개인정보동의번호
		$view['phn'] = $this->uri->segment(4); //휴대전화번호
		//echo $_SERVER['REQUEST_URI'] ." > idx : ". $view['idx'] .", phn : ". $view['phn'] ."<br>";

		//수신동의 발송정보 조회
		$sql = "
		SELECT
			  a.agi_idx /* 개인정보동의번호 */
			, a.agi_mem_id /* 회원번호 */
			, a.agi_imgpath /* 자세히 보러가기 이미지 */
			, a.agi_cre_date /* 등록일자 */
			, b.mst_id /* 발송번호 */
		FROM cb_agree_info a
		LEFT OUTER JOIN cb_wt_msg_sent b ON a.agi_idx = b.mst_agreeid
		WHERE a.agi_idx = '". $view['idx'] ."' ";
		//echo $_SERVER['REQUEST_URI'] ." > sql : ". $sql ."<br>";
		$view['rs'] = $this->db->query($sql)->row();

		//수신동의 여부 확인
		$sql = "
		SELECT COUNT(1) AS cnt /* 등록수 */
		FROM cb_agree_data
		WHERE agd_agi_idx = '". $view['rs']->agi_idx ."' /* 개인정보동의번호 */
		AND agd_mem_id = '". $view['rs']->agi_mem_id ."' /* 회원번호 */
		AND agd_phn = '". $view['phn'] ."' /* 휴대전화번호 */
		AND agd_state = '0' /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */ ";
		$cnt = $this->db->query($sql)->row()->cnt;
		//echo $_SERVER['REQUEST_URI'] ." > cnt : ". $cnt ."<br>";

		if($cnt > 0){ //발송 상태의 경우
			//개인정보동의 확인 처리
			$where = array();
			$where["agd_agi_idx"] = $view['rs']->agi_idx; //개인정보동의번호
			$where["agd_mem_id"] = $view['rs']->agi_mem_id; //회원번호
			$where["agd_mst_id"] = $view['rs']->mst_id; //발송번호
			$where["agd_phn"] = $view['phn']; //연락처
			$data = array();
			$data["agd_state"] = "1"; //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함)
			$data["agd_view_date"] = date("Y-m-d H:i:s"); //확인일자
			$rtn = $this->db->update("cb_agree_data", $data, $where); //데이타 수정
		}

		//수신동의 데이타
		$sql = "
		SELECT *
		FROM cb_agree_data
		WHERE agd_agi_idx = '". $view['rs']->agi_idx ."' /* 개인정보동의번호 */
		AND agd_mem_id = '". $view['rs']->agi_mem_id ."' /* 회원번호 */
		AND agd_phn = '". $view['phn'] ."' /* 휴대전화번호 */ ";
		//echo $_SERVER['REQUEST_URI'] ." > sql : ". $sql ."<br>";
		$view['data'] = $this->db->query($sql)->row();
		//echo $_SERVER['REQUEST_URI'] ." > view['cnt'] : ". $view['cnt'] ."<br>";

		$this->load->view('biz/agree/agreeview',$view);
	}
    
    //동의하기
	public function approval(){
		$idx = $this->input->POST("idx");
		$phn = $this->input->POST("phn");
		$state = $this->input->POST("state");
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > idx : ". $idx .", phn : ". $phn .", state : ". $state);
		$msg = "";

		//수신동의 여부 확인
		$sql = "
		SELECT COUNT(1) AS cnt
		FROM cb_agree_data
		WHERE agd_agi_idx = '". $idx ."' /* 개인정보동의번호 */
		AND agd_phn = '". $phn ."' /* 휴대전화번호 */
		AND agd_state IN  ('0', '1') /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */ ";
		$cnt = $this->db->query($sql)->row()->cnt;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > cnt : ". $cnt);

		if($cnt > 0) { //발송/확인 상태
			$sql = "
			UPDATE cb_agree_data SET
				  agd_state = '". $state ."' /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */
				, agd_agree_date = sysdate() /* 수신동의일자 */
			WHERE agd_agi_idx = '". $idx ."' /* 개인정보동의번호 */
			AND agd_phn = '". $phn ."' /* 휴대전화번호 */ ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : ". $sql);
			$this->db->query($sql);
			$msg = "정상적으로 동의 되었습니다.";
		} else {
			$msg = "이미 동의하셨습니다.";
		}

		header('Content-Type: application/json');
		echo '{"result": "'. $msg .'"}';
    }

	//결과조회
	public function agree_result(){
		$view = array();
		$view['perpage'] = ($this->input->post("perpage")) ? $this->input->post("perpage") : 10; //개시물수
		$view['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1; //현재페이지
		$view['agreeid'] = $this->input->POST("agreeid"); //개인정보동의번호
		$view['search_phn'] = $this->input->POST("search_phn"); //연락처 검색
		$view['search_state'] = $this->input->POST("search_state"); //상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) 검색

		//검색조건
		$where = "WHERE agd_mem_id = '". $this->member->item("mem_id") ."' ";
		$where .= "AND agd_agi_idx = '". $view['agreeid'] ."' /* 개인정보동의번호 */ ";
		if($view['search_state'] != ""){
			$where .= "AND agd_state = '". $view['search_state'] ."' /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */ ";
		}
		if($view['search_phn'] != ""){
			//$search_phn = preg_replace("/[^0-9]/", "", $view['search_phn']); //숫자 이외 제거
			$search_phn = str_replace("-", "", $view['search_phn']); //- 제거
			$where .= "AND agd_phn LIKE '%". $search_phn ."%' /* 휴대전화번호 */ ";
		}

		//전체수
		$sql = "
		SELECT count(1) as cnt
		FROM cb_agree_data a
		". $where ." ";
		//echo $sql ."<br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//리스트
		$sql = "
		SELECT
			  agd_idx /* 일련번호 */
			, agd_phn /* 연락처 */
			, agd_state /* 상태(0.발송, 1.확인, 2.수신동의, 3.동의안함) */
			, DATE_FORMAT(agd_cre_date, '%Y-%m-%d %H:%i') AS agd_cre_date /* 발송일자 */
			, DATE_FORMAT(agd_view_date, '%Y-%m-%d %H:%i') AS agd_view_date /* 확인일자 */
			, DATE_FORMAT(agd_agree_date, '%Y-%m-%d %H:%i') AS agd_agree_date /* 수신동의일자 */
		FROM cb_agree_data a
		". $where ."
		ORDER BY agd_idx DESC
		LIMIT ".(($view['page'] - 1) * $view['perpage']).", ".$view['perpage']." ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$view['list'] = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view : ".$view);

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_agree_result';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_agree_result($1)'><a herf='#' ",$view['page_html']);
		$view['page_html'] = $view['page_html'];
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > view[page_html] : ".$view['page_html']);

		 /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        
        $this->load->view('biz/agree/agree_result',$view);
	}

}
?>