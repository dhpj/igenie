<?php
class Order extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	//protected $models = array('Board', 'Biz');

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
		$this->load->library(array('querystring', 'funn', 'alimtalk_v', "nicepay", "korpay"));
	}

	//메인 페이지
	public function index(){
		$this->main();
	}

	//주문관리 > 주문내역
	public function main(){
	    if($this->member->is_member() == false) { //로그인이 안된 경우
            redirect('login');
        }
        $view = array();
		$skin = "main";
		$add = $this->input->get("add");
		$view['add'] = $add;
		if($add != "") $skin .= $add;
		$view['perpage'] = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10; //목록수
		$view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1; //현재 페이지
		$view['param']['order_period'] = $this->input->get("order_period"); //주문기간
		$view['param']['start_date'] = ($this->input->get("start_date")) ? $this->input->get("start_date") : date("Y-m-d", strtotime("-1 week")); //시작일자
		$view['param']['end_date'] = ($this->input->get("start_date")) ? $this->input->get("end_date") : date("Y-m-d"); //종료일자
		$view['param']['status'] = $this->input->get("status"); //진행상태 (0:주문완료, 1:배송준비중, 2:배송시작, 3:배송완료, 4:주문취소, 9.주문대기)
		$view['param']['charge_type'] = $this->input->get("charge_type"); //결제방법
		$view['param']['sc'] = $this->input->get("sc"); //검색구분
		$view['param']['sv'] = $this->input->get("sv"); //검색내용
		//echo "['param']['status'] : ". $view['param']['status'] ."<br>";
		$where = "WHERE d.mem_id = '".$this->member->item('mem_id')."' ";
		if(!empty($view['param']['start_date'])){ //시작일자
			$where .= "AND d.creation_date >= '".$view['param']['start_date']."' ";
		}
		if(!empty($view['param']['end_date'])){ //종료일자
			$where .= "AND d.creation_date <= '".$view['param']['end_date']." 23:59:59' ";
		}
		if($view['param']['status'] != ""){ //진행상태
			if($view['param']['status'] !='10'){
				$where .= "AND d.status = '".$view['param']['status']."' ";
			}
		}else{
			$where .= "AND (d.status != '3' AND d.status != '4') ";
		}
		if($view['param']['charge_type'] != ""){ //결제방법
			$where .= "AND d.charge_type = '".$view['param']['charge_type']."' ";
		}
		if($view['param']['sc'] != "" and $view['param']['sv'] != "") { //검색내용
			$sv = trim($view['param']['sv']);
			if($view['param']['sc'] == "phnno"){ //전화번호 검색의 경우
				$sv = $this->funn->numbers_only($sv); //숫자 이외 제거
			}
			$where .= "AND d.". $view['param']['sc'] ." like '%". addslashes($sv) ."%'";
		}
		//echo "where : ". $where ."<br>";

		//전체주문 건수
		$sql = "SELECT COUNT(1) AS cnt from cb_orders d where mem_id = '".$this->member->item('mem_id')."' AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id) AND d.status <> 9";
		//echo $sql ."<br>";
		$view['order_cnt'] = $this->db->query($sql)->row()->cnt;

		//신규주문 건수
		$sql = "SELECT COUNT(1) AS cnt from cb_orders d where mem_id = '".$this->member->item('mem_id')."' and status = '0' AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id) GROUP BY status";
		$view['order_com'] = $this->db->query($sql)->row()->cnt;

		//상품준비중 건수
		$sql = "SELECT COUNT(1) AS cnt from cb_orders d where mem_id = '".$this->member->item('mem_id')."' and status = '1' AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id) GROUP BY status";
		$view['order_pre'] = $this->db->query($sql)->row()->cnt;

		//배송중 건수
		$sql = "SELECT COUNT(1) AS cnt from cb_orders d where mem_id = '".$this->member->item('mem_id')."' and status = '2' AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id) GROUP BY status";
		$view['order_dlv'] = $this->db->query($sql)->row()->cnt;

		//배송완료 건수
		$sql = "SELECT COUNT(1) AS cnt from cb_orders d where mem_id = '".$this->member->item('mem_id')."' and status = '3' AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id) GROUP BY status";
		$view['order_fin'] = $this->db->query($sql)->row()->cnt;

		//주문취소 건수
		$sql = "SELECT COUNT(1) AS cnt from cb_orders d where mem_id = '".$this->member->item('mem_id')."' and status = '4' AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id) GROUP BY status";
		$view['order_can'] = $this->db->query($sql)->row()->cnt;

		//회원정보
		$sql = "select * from cb_member where mem_id='".$this->member->item('mem_id')."' ";
		//$view['mem'] = $this->db->query($sql)->row();

		//전체수
		$sql = "SELECT count(1) as cnt FROM cb_orders d ". $where ." AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id) AND d.status <> 9";
		//echo $sql ."<br>";
		//log_message("ERROR", "Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//주문내역
		$sql = "
		SELECT
			 d.id as order_id
            ,d.orderno /* 주문번호 */
			,d.phnno /* 휴대폰번호 */
			,d.receiver /* 주문자 */
			,d.postcode /* 우편번호 */
			,d.addr1 /* 주소 */
			,d.addr2 /* 상세주소 */
			,d.charge_type /* 결재수단 */
			,d.point_save_no /* 마트적립번호 */
			,d.user_req /* 요청사항 */
			,d.mng_note /* 추가정보 */
			,d.deliv_amt /* 배달비 */
			,d.status /* 진행상태 */
			,d.creation_date /* 주문일시 */
			,d.c_reason /*부분취소사유(고객)*/
			,d.s_reason /*부분취소사유(판매자)*/
			,d.cc_reason /*전체취소사유(고객)*/
			,d.sc_reason /*전체취소사유(판매자)*/
			,d.cal_person as calp /*전체취소주체*/
			,d.price_change /* 결제금액조정 */
			,dod.cal_yn /* 부분취소여부 Y:취소 */
			,dod.cal_person /* 부분취소주체 C:고객 S:판매자 */
			,dod.id as gid /* 상품번호 */
			,dod.goodsid /* 상품번호 */
			,dod.name as goods_name /* 상품명 */
			,dod.option as goods_option /* 규격 */
			,dod.price /* 정상가 */
			,dod.dcprice /* 판매가 */
			,dod.qty /* 주문수 */
			,dod.amount /* 총상품금액 */
			,dod.barcode /* 상품바코드 */
			,dod.imgurl /* 상품이미지 */
			,(SELECT COUNT(1) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id) AS rowspan /* 주문 라인수 */
			,dod2.min_amt /* 최소주문금액 */
			,dod2.delivery_amt /* 배송비 */
			,dod2.free_delivery_amt /* 배송비 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'Y') AS can_amount /* 부분 취소 금액 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id) AS all_amount /* 전체 상품 금액 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') AS pre_amount /* 원주문 상품 금액 */
			,(SELECT COUNT(1) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') AS realrow /* 유효주문 라인수 */
            ,d.division
		FROM cb_orders d
		inner join cb_order_details dod ON d.id = dod.order_id
		inner join cb_orders_setting dod2 ON d.mem_id = dod2.mem_id
		inner join (
			select id from cb_orders d ". $where ."  AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id) AND d.status <> 9
			order by d.id desc
			limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage']."
		) dd on dd.id = d.id
		order by d.id desc ";
        // log_message("ERROR", "Query : ".$sql);
		$view['orderlist'] = $this->db->query($sql)->result();

		$sql = "
		SELECT count(1) AS cnt FROM cb_orders d ". $where . " AND d.status <> 9";
		// log_message("ERROR", "Query : ".$sql);
		$view['search_cnt'] = $this->db->query($sql)->row()->cnt;

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($view['param']['page']);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

        $layoutconfig = array(
			'path' => 'mall/order',
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

	//주문관리 > 주문내역 > 전체 주문내역 프린트
	public function print_list(){
	    if($this->member->is_member() == false) { //로그인이 안된 경우
            redirect('login');
        }
        $view = array();
		$skin = "print_list";
		$add = $this->input->get("add");
		$view['add'] = $add;
		if($add != "") $skin .= $add;
		$view['param']['start_date'] = ($this->input->get("start_date")) ? $this->input->get("start_date") : date("Y-m-d", strtotime("-1 week")); //시작일자
		$view['param']['end_date'] = ($this->input->get("start_date")) ? $this->input->get("end_date") : date("Y-m-d"); //종료일자
		$view['param']['status'] = $this->input->get("status"); //진행상태 (0:주문완료, 1:배송준비중, 2:배송시작, 3:배송완료, 4:주문취소, 9.주문대기)
		$view['param']['charge_type'] = $this->input->get("charge_type"); //결제방법
		$view['param']['sc'] = $this->input->get("sc"); //검색구분
		$view['param']['sv'] = $this->input->get("sv"); //검색내용
		//echo "['param']['status'] : ". $view['param']['status'] ."<br>";
		$where = "WHERE d.mem_id = '".$this->member->item('mem_id')."' ";
		if(!empty($view['param']['start_date'])){ //시작일자
			$where .= "AND d.creation_date >= '".$view['param']['start_date']."' ";
		}
		if(!empty($view['param']['end_date'])){ //종료일자
			$where .= "AND d.creation_date <= '".$view['param']['end_date']." 23:59:59' ";
		}
		if($view['param']['status'] != ""){ //진행상태
			$where .= "AND d.status = '".$view['param']['status']."' ";
		}
		if($view['param']['charge_type'] != ""){ //결제방법
			$where .= "AND d.charge_type = '".$view['param']['charge_type']."' ";
		}
		if($view['param']['sc'] != "" and $view['param']['sv'] != "") { //검색내용
			$sv = trim($view['param']['sv']);
			if($view['param']['sc'] == "phnno"){ //전화번호 검색의 경우
				$sv = $this->funn->numbers_only($sv); //숫자 이외 제거
			}
			$where .= "AND d.". $view['param']['sc'] ." like '%". addslashes($sv) ."%'";
		}
		//echo "where : ". $where ."<br>";

		//전체수
		$sql = "SELECT count(1) as cnt FROM cb_orders d ". $where ." AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id)";
		//echo $sql ."<br>";
		//log_message("ERROR", "Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//주문내역
		$sql = "
		SELECT
			 d.id as order_id /* 주문번호 */
            ,d.orderno /* 주문번호 */
			,d.phnno /* 휴대폰번호 */
			,d.receiver /* 주문자 */
			,d.postcode /* 우편번호 */
			,d.addr1 /* 주소 */
			,d.addr2 /* 상세주소 */
			,d.charge_type /* 결재수단 */
			,d.point_save_no /* 마트적립번호 */
			,d.user_req /* 요청사항 */
			,d.mng_note /* 추가정보 */
			,d.deliv_amt /* 배달비 */
			,d.status /* 진행상태 */
			,d.creation_date /* 주문일시 */
			,d.c_reason /*부분취소사유(고객)*/
			,d.s_reason /*부분취소사유(판매자)*/
			,d.cc_reason /*전체취소사유(고객)*/
			,d.sc_reason /*전체취소사유(판매자)*/
			,d.cal_person as calp /*전체취소주체*/
			,d.price_change /* 결제금액조정 */
			,dod.cal_yn /* 부분취소여부 Y:취소 */
			,dod.cal_person /* 부분취소주체 C:고객 S:판매자 */
			,dod.id as gid /* 상품번호 */
			,dod.goodsid /* 상품번호 */
			,dod.name as goods_name /* 상품명 */
			,dod.option as goods_option /* 규격 */
			,dod.price /* 정상가 */
			,dod.dcprice /* 판매가 */
			,dod.qty /* 주문수 */
			,dod.amount /* 총상품금액 */
			,dod.barcode /* 상품바코드 */
			,dod.imgurl /* 상품이미지 */
			,(SELECT COUNT(1) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id) AS rowspan /* 주문 라인수 */
			,dod2.min_amt /* 최소주문금액 */
			,dod2.delivery_amt /* 배송비 */
			,dod2.free_delivery_amt /* 배송비 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'Y') AS can_amount /* 부분 취소 금액 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id) AS all_amount /* 전체 상품 금액 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') AS pre_amount /* 원주문 상품 금액 */
			,(SELECT COUNT(1) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') AS realrow /* 유효주문 라인수 */
		FROM cb_orders d
		inner join cb_order_details dod ON d.id = dod.order_id
		inner join cb_orders_setting dod2 ON d.mem_id = dod2.mem_id
		inner join (
			select id from cb_orders d ". $where ."  AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id)
		) dd on dd.id = d.id
		order by d.id desc ";
		$view['orderlist'] = $this->db->query($sql)->result();

        $layoutconfig = array(
			'path' => 'mall/order',
			'layout' => 'layout_popup',
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

	//주문관리 > 주문내역 > 엑셀 다운로드
	public function excel_down(){
	    if($this->member->is_member() == false) { //로그인이 안된 경우
            redirect('login');
        }
        $view = array();
		$view['param']['start_date'] = ($this->input->get("start_date")) ? $this->input->get("start_date") : date("Y-m-d", strtotime("-1 week")); //시작일자
		$view['param']['end_date'] = ($this->input->get("start_date")) ? $this->input->get("end_date") : date("Y-m-d"); //종료일자
		$view['param']['status'] = $this->input->get("status"); //진행상태 (0:주문완료, 1:배송준비중, 2:배송시작, 3:배송완료, 4:주문취소, 9.주문대기)
		$view['param']['charge_type'] = $this->input->get("charge_type"); //결제방법
		$view['param']['sc'] = $this->input->get("sc"); //검색구분
		$view['param']['sv'] = $this->input->get("sv"); //검색내용
		//echo "['param']['status'] : ". $view['param']['status'] ."<br>";
		$where = "WHERE d.mem_id = '".$this->member->item('mem_id')."' ";
		if(!empty($view['param']['start_date'])){ //시작일자
			$where .= "AND d.creation_date >= '".$view['param']['start_date']."' ";
		}
		if(!empty($view['param']['end_date'])){ //종료일자
			$where .= "AND d.creation_date <= '".$view['param']['end_date']." 23:59:59' ";
		}
		if($view['param']['status'] != ""){ //진행상태
			$where .= "AND d.status = '".$view['param']['status']."' ";
		}
		if($view['param']['charge_type'] != ""){ //결제방법
			$where .= "AND d.charge_type = '".$view['param']['charge_type']."' ";
		}
		if($view['param']['sc'] != "" and $view['param']['sv'] != "") { //검색내용
			$sv = trim($view['param']['sv']);
			if($view['param']['sc'] == "phnno"){ //전화번호 검색의 경우
				$sv = $this->funn->numbers_only($sv); //숫자 이외 제거
			}
			$where .= "AND d.". $view['param']['sc'] ." like '%". addslashes($sv) ."%'";
		}
		//echo "where : ". $where ."<br>";

		//전체수
		$sql = "SELECT count(1) as cnt FROM cb_orders d ". $where ." AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id)";
		//echo $sql ."<br>";
		//log_message("ERROR", "Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//주문내역
		$sql = "
		SELECT
			 d.id as order_id /* 주문번호 */
            ,d.orderno /* 주문번호 */
			,d.phnno /* 휴대폰번호 */
			,d.receiver /* 주문자 */
			,d.postcode /* 우편번호 */
			,d.addr1 /* 주소 */
			,d.addr2 /* 상세주소 */
			,d.charge_type /* 결재수단 */
			,d.point_save_no /* 마트적립번호 */
			,d.user_req /* 요청사항 */
			,d.mng_note /* 추가정보 */
			,d.deliv_amt /* 배달비 */
			,d.status /* 진행상태 */
			,d.creation_date /* 주문일시 */
			,d.c_reason /*부분취소사유(고객)*/
			,d.s_reason /*부분취소사유(판매자)*/
			,d.cc_reason /*전체취소사유(고객)*/
			,d.sc_reason /*전체취소사유(판매자)*/
			,d.cal_person as calp /*전체취소주체*/
			,d.price_change /* 결제금액조정 */
			,dod.cal_yn /* 부분취소여부 Y:취소 */
			,dod.cal_person /* 부분취소주체 C:고객 S:판매자 */
			,dod.id as gid /* 상품번호 */
			,dod.goodsid /* 상품번호 */
			,dod.name as goods_name /* 상품명 */
			,dod.option as goods_option /* 규격 */
			,dod.price /* 정상가 */
			,dod.dcprice /* 판매가 */
			,dod.qty /* 주문수 */
			,dod.amount /* 총상품금액 */
			,dod.barcode /* 상품바코드 */
			,dod.imgurl /* 상품이미지 */
			,(SELECT COUNT(1) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id) AS rowspan /* 주문 라인수 */
			,dod2.min_amt /* 최소주문금액 */
			,dod2.delivery_amt /* 배송비 */
			,dod2.free_delivery_amt /* 배송비 */
			,IF ( (SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') < dod2.free_delivery_amt, dod2.delivery_amt, 0 ) AS deliv_amt_real
			,IF ( (SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') < dod2.free_delivery_amt, dod2.delivery_amt, 0 ) + (SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') AS calcul
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'Y') AS can_amount /* 부분 취소 금액 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id) AS all_amount /* 전체 상품 금액 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') AS pre_amount /* 원주문 상품 금액 */
			,(SELECT COUNT(1) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') AS realrow /* 유효주문 라인수 */
		FROM cb_orders d
		inner join cb_order_details dod ON d.id = dod.order_id
		inner join cb_orders_setting dod2 ON d.mem_id = dod2.mem_id
		inner join (
			select id from cb_orders d ". $where ."  AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id)
		) dd on dd.id = d.id
		order by d.id desc ";
		$list = $this->db->query($sql)->result();

		$this->load->library('excel');

		// 시트를 지정한다.
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Sheet1');

		// 필드명을 기록한다.
		// 글꼴 및 정렬
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'A1:T1');

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
			'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'A2:T2');

		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10); //주문번호 Width
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12); //주문일자 Width
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10); //주문시간 Width
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10); //주문자명 Width
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); //연락처 Width
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(45); //배달지 Width
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(40); //추가주문 및 요청사항 Width
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25); //부분취소사유 Width
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(25); //전체취소사유 Width
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(18); //결제정보 Width
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(30); //상품명 Width
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15); //규격 Width
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(8); //수량 Width
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(10); //상품금액 Width
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(10); //부분취소 Width
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(10); //배달비 Width
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(10); //부분취소금액 Width
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(10); //금액조정 Width
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(10); //결제금액 Width
		$this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(12); //진행상태 Width

		$this->excel->getActiveSheet()->mergeCells('A1:T1');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "주문내역");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, '주문번호');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, '주문일자');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 2, '주문시간');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 2, '주문자명');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 2, '연락처');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 2, '배달지');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 2, '추가주문 및 요청사항');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 2, '부분취소사유');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 2, '전체취소사유');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 2, '결제정보');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 2, '상품명');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 2, '규격');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 2, '수량');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 2, '상품금액');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 2, '부분취소');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, 2, '배달비');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, 2, '부분취소금액');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, 2, '금액조정');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, 2, '결제금액');
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, 2, '진행상태');

		$row = 3;
		$new_flag= true;
		$pre_flag = 0;
		$sub_row = 0;
		$subtotal = 0;
		$deliv_amt = 0;

		foreach($list as $val) {
			// 데이터를 읽어서 순차로 기록한다.


			if($pre_flag <> $val->order_id) {

				$new_flag = true;
				if($pre_flag > 0) {
					$this->excel->getActiveSheet()->mergeCells('A'.$sub_row.':A'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('B'.$sub_row.':B'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('C'.$sub_row.':C'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('D'.$sub_row.':D'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('E'.$sub_row.':E'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('F'.$sub_row.':F'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('G'.$sub_row.':G'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('H'.$sub_row.':H'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('I'.$sub_row.':I'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('J'.$sub_row.':J'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('P'.$sub_row.':P'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('Q'.$sub_row.':Q'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('R'.$sub_row.':R'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('S'.$sub_row.':S'.($row -1));
					$this->excel->getActiveSheet()->mergeCells('T'.$sub_row.':T'.($row -1));
					//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $sub_row, ($val->calcul + $val->price_change)); //결제금액
				}
				$pre_flag = $val->order_id;
				$sub_row = $row;
				$subtotal = 0;
			} else {
				$new_flag = false;
			}
			if($new_flag) {
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->order_id); //주문번호
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('Y-m-d', strtotime($val->creation_date))); //주문일자
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('H:i:s', strtotime($val->creation_date))); //주문시간
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->receiver); //주문자명
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $this->funn->format_phone($val->phnno, "-")); //연락처
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, trim($val->postcode ." ". $val->addr1 ." ". $val->addr2)); //배달지
				$this->excel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setWrapText(true); //요청사항 줄바꿈 허용
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row,$val->user_req); //요청사항
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row,$val->s_reason); //부분취소사유
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row,$val->sc_reason); //전체취소사유
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $this->funn->get_charge_type($val->charge_type)); //결제정보

				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $val->deliv_amt_real); //배달비
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $val->can_amount*-1); //부분취소 금액
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $val->price_change); //금액조정
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, ($val->calcul + $val->price_change)); //결제금액
				$calp = "";
				if($val->status == '4'){
					if($val->calp=='S'){
						$calp = "<br/>(판매자)";
					}else{
						$calp = "<br/>(구매자)";
					}
					$lfcr = chr(10);
					$calp = str_replace("<br/>",$lfcr,$calp);
				}
				$this->excel->getActiveSheet()->getStyle('T'.$row)->getAlignment()->setWrapText(true); //요청사항 줄바꿈 허용
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $this->funn->get_order_status($val->status).$calp); //진행상태
				$subtotal = 0;
			}
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $val->goods_name); //상품명
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->goods_option); //규격
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->qty); //수량
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->amount); //상품금액
			$cal = "";
			if($val->cal_yn=='Y'){
				$cal = "부분취소";
			}

			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $cal); //부분취소
			//if($val->cal_yn=='N'){
				$subtotal = $subtotal + $val->amount;
			//}

			$row++;

		}

		if($subtotal > 0 ){ //마지막 줄
			$this->excel->getActiveSheet()->mergeCells('A'.$sub_row.':A'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('B'.$sub_row.':B'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('C'.$sub_row.':C'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('D'.$sub_row.':D'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('E'.$sub_row.':E'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('F'.$sub_row.':F'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('G'.$sub_row.':G'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('H'.$sub_row.':H'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('I'.$sub_row.':I'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('J'.$sub_row.':J'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('P'.$sub_row.':P'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('Q'.$sub_row.':Q'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('R'.$sub_row.':R'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('S'.$sub_row.':S'.($row -1));
			$this->excel->getActiveSheet()->mergeCells('T'.$sub_row.':T'.($row -1));
			//$this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $sub_row, ($val->calcul + $val->price_change)); //결제금액
		}

		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'A3:A'.($row-1)); //주문번호
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'B3:B'.($row-1)); //주문일자
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'C3:C'.($row-1)); //주문시간
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'D3:D'.($row-1)); //주문자명
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'E3:E'.($row-1)); //연락처
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'F3:F'.($row-1)); //배달지
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'G3:G'.($row-1)); //요청사항
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'H3:H'.($row-1)); //부분취소사유
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'I3:I'.($row-1)); //전체취소사유
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'J3:J'.($row-1)); //결제정보
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'P3:P'.($row-1)); //배달비
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'Q3:Q'.($row-1)); //부분취소금액
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'R3:R'.($row-1)); //금액조정
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'S3:S'.($row-1)); //결제금액
		$this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
			'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
		),	'T3:T'.($row-1)); //진행상태

		// 파일로 내보낸다. 파일명은 'filename.xls' 이다.
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="order_list_'. date('Ymd') .'.xls"');
		header('Cache-Control: max-age=0');

		// Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
		// 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		// 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
		$objWriter->save('php://output');
	}

	//주문관리 > 주문내역 > 개별 프린트
	public function print_unit($id){
	    if($this->member->is_member() == false) { //로그인이 안된 경우
            redirect('login');
        }
        $view = array();
		$skin = "print_unit";
		$add = $this->input->get("add");
		$view['add'] = $add;
		if($add != "") $skin .= $add;

		$where = "WHERE d.id = '". $id ."' ";
		if($this->member->item("mem_level") < 100){
			$where .= "AND d.mem_id = '".$this->member->item('mem_id')."' ";
		}
		//echo "where : ". $where ."<br>";

		//전체수
		$sql = "SELECT count(1) as cnt FROM cb_orders d ". $where ." AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id)";
		//echo $sql ."<br>";
		//log_message("ERROR", "Query : ".$sql);
		$view['total_rows'] = $this->db->query($sql)->row()->cnt;

		//주문내역
		$sql = "
		SELECT
			 d.id as order_id /* 주문번호 */
            ,d.orderno /* 주문번호 */
			,d.phnno /* 휴대폰번호 */
			,d.receiver /* 주문자 */
			,d.postcode /* 우편번호 */
			,d.addr1 /* 주소 */
			,d.addr2 /* 상세주소 */
			,d.charge_type /* 결재수단 */
			,d.point_save_no /* 마트적립번호 */
			,d.user_req /* 요청사항 */
			,d.mng_note /* 추가정보 */
			,d.deliv_amt /* 배달비 */
			,d.status /* 진행상태 */
			,d.creation_date /* 주문일시 */
			,d.c_reason /*부분취소사유(고객)*/
			,d.s_reason /*부분취소사유(판매자)*/
			,d.cc_reason /*전체취소사유(고객)*/
			,d.sc_reason /*전체취소사유(판매자)*/
			,d.cal_person as calp /*전체취소주체*/
			,d.price_change /* 결제금액조정 */
			,dod.cal_yn /* 부분취소여부 Y:취소 */
			,dod.cal_person /* 부분취소주체 C:고객 S:판매자 */
			,dod.id as gid /* 상품번호 */
			,dod.goodsid /* 상품번호 */
			,dod.name as goods_name /* 상품명 */
			,dod.option as goods_option /* 규격 */
			,dod.price /* 정상가 */
			,dod.dcprice /* 판매가 */
			,dod.qty /* 주문수 */
			,dod.amount /* 총상품금액 */
			,dod.barcode /* 상품바코드 */
			,dod.imgurl /* 상품이미지 */
			,(SELECT COUNT(1) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id) AS rowspan /* 주문 라인수 */
			,dod2.min_amt /* 최소주문금액 */
			,dod2.delivery_amt /* 배송비 */
			,dod2.free_delivery_amt /* 배송비 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'Y') AS can_amount /* 부분 취소 금액 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id) AS all_amount /* 전체 상품 금액 */
			,(SELECT sum(dod1.amount) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') AS pre_amount /* 원주문 상품 금액 */
			,(SELECT COUNT(1) AS cnt FROM cb_order_details dod1 WHERE dod1.order_id = d.id AND dod1.cal_yn = 'N') AS realrow /* 유효주문 라인수 */
		FROM cb_orders d
		inner join cb_order_details dod ON d.id = dod.order_id
		inner join cb_orders_setting dod2 ON d.mem_id = dod2.mem_id
		inner join (
			select id from cb_orders d ". $where ."  AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id)
		) dd on dd.id = d.id
		order by d.id desc ";
		$view['orderlist'] = $this->db->query($sql)->result();

        $layoutconfig = array(
			'path' => 'mall/order',
			'layout' => 'layout_popup',
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

	//주문관리 > 주문내역 > 선택한 주문 상태 변경
	function changestatus() {
	    $oid = $this->input->post('oid'); //주문번호
	    $status = $this->input->post('status'); //변경할 진행상태
	    $org_status = $this->input->post('org_status'); //기존 진행상태
		$sc_reason = $this->input->post('sc_reason'); //전체주문취소사유
		$cal_person = $this->input->post('cal_person'); //전체취소주체
        $charge_type = $this->input->post("charge_type"); //결제방식
		// log_message("ERROR", "/mall/order/changestatus > oid : ". $oid .", status : ". $status .", org_status : ". $org_status);

        $o_data = $this->db
            ->select("*")
            ->from("cb_orders")
            ->where("id", $oid)
            ->get()->row();

        $processFlag = true;
        if ($status == "4" && ($charge_type == "5" || $charge_type == "6")){
            if ($o_data->pg_type == "NP"){
                $this->db
                    ->select("*")
                    ->from("cb_wt_nicepay_cancel")
                    ->where("moid = " . $oid . " AND (resultcode = '2001' OR resultcode = '2211')")
                    ->order_by("id", "DESC")
                    ->limit(1);
                $nice_cc = $this->db->get()->row();
                $ccAmt = 0;
                if (!empty($nice_cc)) $ccAmt = $nice_cc->remainamt;
                $payCode = $this->nicepay->payment_cancel($oid, $sc_reason, $ccAmt);
                if (!in_array($payCode, config_item("refund_code"))){
                    $processFlag = false;
                }
            } else if ($o_data->pg_type == "KP"){
                $processFlag = false;
                $retcode = $this->korpay->kor_cancel($oid);
                if ($retcode == "0000") $processFlag = true;
            }
        }

        if ($processFlag){
            $sql="";
            $cal_sql="";
            if($status=='4'){
                $cal_sql = ", cal_person ='".$cal_person."' ";
            }
            if(!empty($sc_reason)){
                $sql = "update cb_orders set status = '". $status ."', sc_reason='".$sc_reason."'".$cal_sql." where id = '". $oid ."' ";
            }else{
                $sql = "update cb_orders set status = '". $status ."'".$cal_sql." where id = '". $oid ."' ";
            }
            //log_message("ERROR", "/mall/order/changestatus > ". $sql);
            $this->db->query($sql);

            //알림톡 발송
            if($status > $org_status){
                log_message("ERROR", "/mall/order/changestatus > 알림톡 발송 : oid : ". $oid .", mid : ". $this->member->item('mem_id') .", status : ". $status);
                $this->sendat($oid, $this->member->item('mem_id'), $status); //알림톡 발송
            }
        }
	}

	//주문관리 > 주문내역 > 선택한 주문 상태 변경
	function changeprice() {
	    $id = $this->input->post('id'); //주문번호
	    $price = $this->input->post('price'); //변경할 진행상태
        $type = $this->input->post('type'); //변경할 진행상태
		$sql = "select price_change from cb_orders where mem_id = '".$this->member->item('mem_id')."' AND id = '".$id."'";
		$pre_price = $this->db->query($sql)->row()->price_change;

		if($pre_price==$price){
			$result["code"] = 0;
			$result["msg"] = "결제변경금액이 이전변경금액과 동일합니다";
		}else{
            $sql = "update cb_orders set price_change = ". $price ." where id = '". $id ."' ";
            $this->db->query($sql);
            $result = array();
            $result["code"] = 1;
            $result["msg"] = "결제금액이 변경되었습니다";
		}


		$json = json_encode($result,JSON_UNESCAPED_UNICODE);

		header('Content-Type: application/json');
		echo $json;

	}

	//주문관리 > 주문내역 > 주문 취소
	function ordercancel() {
		$ono = $this->input->post("orderno");
		$sql = "select * from cb_orders where id = '$ono' and status in (0, 1)";
		//log_message("ERROR", "/mall/order/ordercancel : SQL : ".$sql);
		$rs = $this->db->query($sql)->row();
		if(!empty($rs) ) {
			$sql = "update cb_orders set status = '4' where id = '". $ono ."'"; //주문정보 주문 취소 처리
			//log_message("ERROR", "/cart/ordercancel : SQL : ".$sql);
			$this->db->query($sql);

			//재고관리 상품정보 조회
			$sql =  "select d.goodsid, d.qty
						from cb_order_details d
						left join cb_goods g on d.goodsid = g.id
						where d.order_id = '". $ono ."'
						and g.stock_flag = 'Y'
						order by d.id asc";
			$list = $this->db->query($sql)->result();
			foreach($list as $val) {
				$sql = "update cb_goods set stock = (stock + ". $val->qty .") where id = '". $val->goodsid ."' and stock_flag = 'Y'"; //상품재고 수량 증가
				//log_message("ERROR", "/cart/ordercancel : SQL(상품재고 수량 증가) : ".$sql);
				$this->db->query($sql);
			}

			$sql = "select alimtalk2 from cb_alimtalk_setting where mem_id = '". $this->member->item('mem_id') ."'";
			//log_message("ERROR", "SQL : ".$sql);
			$alim = $this->db->query($sql)->row();

			if(!empty($alim->alimtalk2) and $alim->alimtalk2=='Y') {
			    $this->sendat('4', $ono);
			}
		}
		$json = json_encode($rs,JSON_UNESCAPED_UNICODE);

		header('Content-Type: application/json');
		echo $json;
	}

	//주문관리 > 주문내역 > 주문 취소
	function partcancel() {
        $orderid = $this->input->post("orderid");
		$s_reason = $this->input->post("s_reason");
		$canre = array();
        $canre['code'] = "0";
        $canre['msg'] = "부분취소되었습니다.";
		$delyn = 'N';
		$sql = "
            select
                *
            from
                cb_orders
            where
                id = '$orderid'
                and
                status = 0
        ";

		$rs = $this->db->query($sql)->row();
		if(!empty($rs) ) {
			$sql = "
                update
                    cb_orders
                set
                    s_reason = '".$s_reason."'
                where
                    id = '". $orderid ."'
            "; //부분 취소 사유 처리
			$this->db->query($sql);

			$gid = $this->input->post("gid");
			$gnameid = $gid[0];
			$good_cnt = count($gid);
			//log_message("ERROR", "/ordermng/part > gnameid". $gid[0]);

			//주문 상품정보
			$sql = "
    			SELECT
    				  CONCAT(d.name, ' ', d.option, ' ', d.qty, '개') AS good_name /* 상품명 */
    				, (SELECT COUNT(*) FROM cb_order_details WHERE order_id = d.order_id and cal_yn='N') AS good_cnt /* 주문수 */
    				, (SELECT SUM(amount) FROM cb_order_details WHERE order_id = d.order_id and cal_yn='N') AS good_amt /* 전체상품금액 */
    				, o.deliv_amt /* 배송비 */
    				, o.charge_type /* 결제유형 (1.현장결제(카드), 2.현장결제(현금), 3.계좌이체) */
    			FROM
                    cb_order_details d
    			LEFT JOIN
                    cb_orders o ON o.id = d.order_id
    			WHERE
                    d.order_id =  '". $orderid ."'
                    AND
                    d.id = ".$gnameid;
			//log_message("ERROR","/cart/sendat_M > sql : ". $sql);
			$part_good = $this->db->query($sql)->row();

			$good_name = trim($part_good->good_name); //주문 상품명
			//$good_cnt = count($gid); //주문 건수
			if($good_cnt > 1){ //주문 건수가 2건 이상인 경우
				$good_name = $good_name ." 외 ". ($good_cnt - 1) ."건";
			}
			$d_amt = $part_good->deliv_amt;

			$sql = "
                select
                    *
                from
                    cb_orders_setting
                where
                    mem_id = '".$this->member->item('mem_id')."'
            ";
			$ordset = $this->db->query($sql)->row();
			$min_amt = $ordset->min_amt;
			$delivery_amt = $ordset->delivery_amt;
			$free_delivery_amt = $ordset->free_delivery_amt;

			$can_price = 0;

			foreach($gid as $arr_partgoods) {
				if($arr_partgoods !== ""){

					$sql = "
                        select
                            *
                        from
                            cb_order_details
                        where
                            order_id= '".$orderid."'
                            and
                            id = '".$arr_partgoods."'
                            and
                            cal_yn = 'N'
                    ";
					$partdel = $this->db->query($sql)->row();

						$can_price = $can_price + $partdel->amount;

				}
			}

			$charge_type = $part_good->charge_type;
			$total_price = $part_good->good_amt;
			$iprice = $part_good->good_amt - $can_price;

			if($iprice < $free_delivery_amt){
				$d_amt = $delivery_amt;
			}else{
				$d_amt = 0;
			}
            $inicis_tot = 0;
            $inicis_del = 0;
            if ($part_good->good_amt < $free_delivery_amt && $iprice < $free_delivery_amt){
                $inicis_tot = $delivery_amt;
            }else if ($part_good->good_amt > $free_delivery_amt && $iprice < $free_delivery_amt){
                $inicis_tot = $delivery_amt;
                $inicis_del = $delivery_amt;
            }

            // log_message("error", "SONG : " . $total_price);
            // log_message("error", "SONG : " . $iprice);
            // log_message("error", "SONG : " . $d_amt);
            // log_message("error", "SONG : " . $part_good->good_amt);
            // log_message("error", "SONG : " . $can_price);

			$tot_amt = number_format($iprice + $d_amt)."원";
            $processFlag = true;
            if ($charge_type == "5"){
                if ($total_price <= $iprice + $d_amt){
                    $canre['msg'] = "카드결제의 경우 기존금액보다 취소금액이 같거나 클시 부분취소를 할 수 없습니다.(취소전금액 : " . number_format($total_price) . " / 취소후금액 : " . number_format($iprice + $d_amt) . ")";
                    $canre['code'] = "1";
                    $processFlag = false;
                } else {
                    $ccAmt = $iprice + $d_amt;
                    $ccAmt = $total_price - $ccAmt;
                    $payCode = $this->nicepay->payment_cancel($orderid, $s_reason, $ccAmt);
                    if (!in_array($payCode, config_item("refund_code"))){
                        $canre['msg'] = "내부문제로 문제가 생겨서 결제를 취소하지 못하였습니다. 관리자에게 문의주세요.";
                        $canre['code'] = "2";
                        $processFlag = false;
                    }
                }
            }
            $charge_type = $this->funn->get_charge_type($charge_type);

            if ($processFlag){
                foreach($gid as $arr_partgoods) {
    				if($arr_partgoods !== ""){

    					$sql = "
                            select
                                *
                            from
                                cb_order_details
                            where
                                order_id= '".$orderid."'
                                and
                                id = '".$arr_partgoods."'
                        ";
    					$partdel = $this->db->query($sql)->row();

    					if(!empty($partdel)){
    						$sql = "
                                update
                                    cb_order_details
                                set
                                    cal_yn = 'Y'
                                where
                                    id = '".$arr_partgoods."'
                            ";
    						$this->db->query($sql);
    					}
    				}
    			}


    			$this->sendat_part_cancel($orderid, $this->member->item('mem_id'), $good_name, $s_reason);
    			$this->sendat_part_cancel_M($orderid, $this->member->item('mem_id'), $good_name, $s_reason, $charge_type, $tot_amt);
            }

		}
		$json = json_encode($canre,JSON_UNESCAPED_UNICODE);

		header('Content-Type: application/json');
		echo $json;
	}

	//알림톡 발송 : 부분취소시 => 고객 자동발송
	function sendat_part_cancel($id, $mid, $good_name, $s_reason){
		//주문정보
	log_message("ERROR", "/order/sendat_part_cancel(id : ". $id .", mid : ". $mid .", good_name : ". $good_name .", s_reason : ". $s_reason .")");
	$sql = "select * from cb_orders d where d.id = ".$id;
		$odr = $this->db->query($sql)->row();

		//회원정보 (마트)
        //ib플래그
		$sql = "
		SELECT
			  a.*
			, b.spf_key
		FROM cb_member a
		LEFT JOIN ".config_item('ib_profile')." b ON a.mem_id = b.spf_mem_id AND b.spf_use = 'Y'
		WHERE a.mem_id = '". $mid ."' ";
	    $mem = $this->db->query($sql)->row();

        $voucher_flag = $this->voucher_flag($mem->mem_userid, $mid);

		//주문내역 확인  URL
		$url = 'http://'. $_SERVER['HTTP_HOST'] .'/smart/orderview?name='. urlencode($odr->receiver) .'&phn='. $odr->phnno .'&oid='. $id;
		// log_message("ERROR", "/order/sendat_part_cancel(url : ". $url .")");
	    $param = array();
		$param['Auth'] = $mem->spf_key; //프로필 키
		$param['alim_flag']   = '3'; //알림톡발송구분(1.구마트톡, 2.신마트톡, 3.지니) 2020-12-11
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            $param['temp'] = '20210928_1'; //템플릿 코드 : 부분취소시(고객)
        }else{
            $param['temp'] = '2021082404'; //템플릿 코드 : 부분취소시(고객)
        }
		//$param['temp'] = '2021030302'; //템플릿 코드 : 주문취소시(고객)
		$param['sender'] = $mem->mem_phone;
		$param['name']   = $odr->receiver;
		$param['phn']    = $odr->phnno;
		$param['add1']   = $id; //주문번호
		$param['add2']   = $good_name; //주문상품
		$param['add3']   = $s_reason; // 부분취소사유
		$param['add4']   = $this->funn->format_phone($mem->mem_phone, "-"); //마트전화번호
		$param['add5']   = '';
		$param['add6']   = '';
		$param['add7']   = '';
		$param['add8']   = '';
		$param['add9']   = '';
		$param['add10']  = '';
		$param['2nd']    = 'Y';
		$param['m1']    = $url; //주문내역 확인  URL
		$param['pc1']    = $url; //주문내역 확인  URL
        $param['mid'] = $mid; //mem_id
        $param['voucher'] = $voucher_flag; //바우처 발송여부
		$rtn = $this->alimtalk_v->send($param);
		//log_message("ERROR", "주문취소 Alimtalk 결과 : ".$rtn);
	}

	//알림톡 발송 : 부분취소시 => 관리자 자동발송
	function sendat_part_cancel_M($id, $mid, $good_name, $s_reason, $charge_type, $tot_amt){
		//주문정보
	//log_message("ERROR", "/ordermng/sendat_part_cancel(id : ". $id .")");
	$sql = "select * from cb_orders d where d.id = ".$id;
		$odr = $this->db->query($sql)->row();

		//회원정보 (마트)
        //ib플래그
		$sql = "
		SELECT
			  a.*
			, b.spf_key
		FROM cb_member a
		LEFT JOIN ".config_item('ib_profile')." b ON a.mem_id = b.spf_mem_id AND b.spf_use = 'Y'
		WHERE a.mem_id = '". $mid ."' ";
	    $mem = $this->db->query($sql)->row();

        $voucher_flag = $this->voucher_flag($mem->mem_userid, $mid);

			$mem_stmall_alim_phn = $mem->mem_stmall_alim_phn; //주문 알림 수신 번호
			$arr_stmall_alim_phn = explode(',', $mem_stmall_alim_phn);

		//주문내역 확인  URL
		$url = 'http://'. $_SERVER['HTTP_HOST'] .'/smart/orderview?name='. urlencode($odr->receiver) .'&phn='. $odr->phnno .'&oid='. $id;

		for($i=0; $i<count($arr_stmall_alim_phn); $i++){
			$param = array();
			$param['Auth'] = $mem->spf_key; //프로필 키
			$param['alim_flag']   = '3'; //알림톡발송구분(1.구마트톡, 2.신마트톡, 3.지니) 2020-12-11
            //ib플래그
            if(config_item('ib_flag')=="Y"){
                $param['temp'] = '20210928_2'; //템플릿 코드 : 부분취소시(고객)
            }else{
                $param['temp'] = '2021082403'; //템플릿 코드 : 부분취소시(고객)
            }
			//$param['temp'] = '2021030306';
			$param['sender'] = $mem->mem_phone; //발신자 연락처
			$param['name'] = $odr->receiver; //성명
			$param['phn'] = $arr_stmall_alim_phn[$i]; //수신자 연락처
			$param['add1'] = $id; //주문번호
			$param['add2'] = $good_name; // 취소상품 $odr->receiver; //주문자명
			$param['add3'] = $s_reason; // 취소사유 $this->funn->format_phone($odr->phnno, "-"); //휴대폰번호
			$param['add4'] = $odr->receiver; //주문자명 $charge_type; //결제정보
			$param['add5'] = $this->funn->format_phone($odr->phnno, "-"); //휴대폰번호 $tot_amt; //총결제금액
			$param['add6'] = $charge_type; //결제정보
			$param['add7'] = $tot_amt; //총결제금액
			$param['add8'] = '';
			$param['add9'] = '';
			$param['add10'] = '';
			$param['2nd'] = 'Y';
			$param['m1'] = $url ."&mng=Y"; //주문내역 확인 URL (모바일)
			$param['pc1'] = $url ."&mng=Y"; //주문내역 확인 URL (PC)
			$param['2nd_btn_add'] = 'Y'; //2차 발송 버튼 링크 추가 여부 2021-03-05
            $param['mid'] = $mid; //mem_id
            $param['voucher'] = $voucher_flag; //바우처 발송여부
			$rtn = $this->alimtalk_v->send($param);
		}

		//log_message("ERROR", "주문취소 Alimtalk 결과 : ".$rtn);
	}

	//주문관리 > 주문내역 > 선택삭제
	function order_remove() {
		$chkids = $this->input->post('chkids');
		$data = json_decode( $chkids );
		//log_message("ERROR", "/mall/order/order_remove > chkids : ". $chkids .", data : ". $data);
		foreach($data as $r) {
			$order_id = substr($r->id,0);
			//log_message("ERROR", "/mall/order/order_remove > order_id : ". $order_id);

			//주문 상품정보 삭제
			$sql = "delete from cb_order_details where order_id = '". $order_id ."'";
			//log_message("ERROR", "/mall/order/order_remove > 주문 상품정보 삭제 SQL ". $sql);
			//$this->db->query($sql);

			//주문 내역 삭제
			$sql = "delete from cb_orders where id = '". $order_id ."'";
			//log_message("ERROR", "/mall/order/order_remove > 주문 내역 삭제 SQL ". $sql);
			//$this->db->query($sql);

			//주문 내역 > 회원사 사용여부 변경
			$sql = "update cb_orders set mem_use_yn='N' where id = '". $order_id ."'";
			//log_message("ERROR", "/mall/order/order_remove > 주문 내역 > 회원사 사용여부 변경 SQL ". $sql);
			$this->db->query($sql);
		}
	}

	//주문관리 > 주문내역 > 추가정보 업데이트
	function changemngnote() {
		$oid = $this->input->post('oid'); //일련번호
		$mng_note = $this->input->post('mng_note'); //추가정보
		$mng_note = str_replace("‘", "'", $mng_note); //추가정보('콤마 특수문자 처리)
		$sql = "update cb_orders set mng_note = '". addslashes($mng_note) ."' where id = '". $oid ."' ";
		//log_message("ERROR", $sql);
		$this->db->query($sql);
	}

		//주문관리 > 배달분포
		public function map(){
		    if($this->member->is_member() == false) { //로그인이 안된 경우
	            redirect('login');
	        }
	        $view = array();
			$skin = "map";
			$add = $this->input->get("add");
			$view['add'] = $add;
			if($add != "") $skin .= $add;
			$mem_id = $this->member->item('mem_id'); //회원번호
			$mem_userid = $this->member->item('mem_userid'); //회원아이디
			$mem_mapadd_day = $this->member->item('mem_mapadd_day'); //배달분포 주소추가 지도표시일

			//매장정보
			$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
			$view['shop'] = $this->db->query($sql)->row();

			//주문자 주소 리스트
			$sql = "
			SELECT
				  0 AS flag /* 구분 */
				, a.ma_id AS id /* 일련번호 */
	            , a.ma_id AS orderno /* 주문번호 */
				, b.ab_name AS receiver /* 이름 */
				, '0' AS status
				, b.ab_addr AS addr /* 주소 */
				, b.ab_tel AS phnno /* 전화번호 */
			FROM cb_mapadd a
			LEFT JOIN cb_ab_". $mem_userid ." b ON b.ab_tel = a.ma_tel
			WHERE DATE(a.ma_cre_date) > DATE_SUB(curdate(), INTERVAL ". $mem_mapadd_day ." DAY)
			UNION ALL
			SELECT
				  1 as flag /* 구분 */
				, d.id /* 주문번호 */
	            ,d.orderno /* 주문번호 */
				, d.receiver /* 주문자명 */
				, d.status /* 진행상태 */
				, TRIM(CONCAT(d.addr1, ' ', d.addr2)) AS addr /* 주소 */
				, d.phnno /* 전화번호 */
			FROM cb_orders d
			WHERE d.mem_id = '". $mem_id ."'
			AND d.status IN ('0', '1', '2') /* 진행상태 (0.신규주문, 1.상품준비중, 2.배송중) */
			AND d.addr1 != '' /* 주소가 있는 것만 가져 온다. */
			AND EXISTS (SELECT 1 FROM cb_order_details dod WHERE dod.order_id = d.id)
			ORDER BY flag ASC, id DESC ";
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
			//echo $sql ."<br>";
			$view['addr_list'] = $this->db->query($sql)->result();

	        $layoutconfig = array(
				'path' => 'mall/order',
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

		//주문관리 > 배달분포 주소추가
		public function mapadd(){
		    if($this->member->is_member() == false) { //로그인이 안된 경우
	            redirect('login');
	        }
	        $view = array();
			$skin = "mapadd";
			$add = $this->input->get("add");
			$view['add'] = $add;
			if($add != "") $skin .= $add;
			$mem_id = $this->member->item('mem_id'); //회원번호
			$mem_userid = $this->member->item('mem_userid'); //회원아이디
			$mem_mapadd_day = $this->member->item('mem_mapadd_day'); //배달분포 주소추가 지도표시일

			//매장정보
			$sql = "select * from cb_member where mem_id='". $mem_id ."' ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
			$view['shop'] = $this->db->query($sql)->row();

			$where = "
			FROM cb_mapadd a
			LEFT JOIN cb_ab_". $mem_userid ." b ON b.ab_tel = a.ma_tel
			WHERE DATE(ma_cre_date) > DATE_SUB(curdate(), INTERVAL ". $mem_mapadd_day ." DAY) ";

			//스마트전단 전체수
			$sql = "
			SELECT count(1) as cnt
			". $where;
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
			//echo $sql ."<br>";
			$view['total_rows'] = $this->db->query($sql)->row()->cnt;

			//주소 조회
			$sql = "
			SELECT
				  a.ma_id /* 일련번호 */
				, b.ab_name /* 이름 */
				, b.ab_tel /* 전화번호 */
				, b.ab_addr /* 주소 */
			". $where ."
			ORDER BY a.ma_id DESC ";
			//echo $sql ."<br>";
			$view['data_list'] = $this->db->query($sql)->result();

	        $layoutconfig = array(
				'path' => 'mall/order',
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

		//주문관리 > 배달분포 주소추가 > 조회
		public function mapadd_search(){
			$mem_id = $this->member->item('mem_id'); //회원번호
			$mem_userid = $this->member->item('mem_userid'); //회원아이디
			$tel = $this->input->post("tel"); //전화번호

			//주소 조회
			$sql = "
			SELECT
				  ab_name /* 이름 */
				, ab_tel /* 전화번호 */
				, ab_addr /* 주소 */
			FROM cb_ab_". $mem_userid ."
			WHERE ab_tel LIKE '%". $tel ."%' /* 전화번호 */
			ORDER BY ab_tel ASC ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
			$result = $this->db->query($sql)->result();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);

			$json = json_encode($result,JSON_UNESCAPED_UNICODE);
			header('Content-Type: application/json');
			echo $json;
		}

		//주문관리 > 배달분포 주소추가 > 지도 등록
		public function mapadd_add(){
			$mem_id = $this->member->item('mem_id'); //회원번호
			$mem_userid = $this->member->item('mem_userid'); //회원아이디
			$mem_mapadd_day = $this->member->item('mem_mapadd_day'); //배달분포 주소추가 지도표시일
			$ma_tel = $this->input->post("tel"); //전화번호

			//기존 데이타 삭제
			$where = array();
			$where["ma_mem_id"] = $mem_id; //회원번호
			$where["ma_tel"] = $ma_tel; //전화번호
			$rtn = $this->db->delete("cb_mapadd", $where); //데이타 삭제

			//지도 등록
			$data = array();
			$data["ma_mem_id"] = $mem_id; //회원번호
			$data["ma_tel"] = $ma_tel; //전화번호
			$rtn = $this->db->insert("cb_mapadd", $data); //데이타 추가

			$where = "
			FROM cb_mapadd a
			LEFT JOIN cb_ab_". $mem_userid ." b ON b.ab_tel = a.ma_tel
			WHERE DATE(ma_cre_date) > DATE_SUB(curdate(), INTERVAL ". $mem_mapadd_day ." DAY) ";

			//주소 조회
			$sql = "
			SELECT
				  a.ma_id /* 일련번호 */
				, b.ab_name /* 이름 */
				, b.ab_tel /* 전화번호 */
				, b.ab_addr /* 주소 */
			". $where ."
			ORDER BY a.ma_id DESC ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
			$result = $this->db->query($sql)->result();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);

			$json = json_encode($result,JSON_UNESCAPED_UNICODE);
			header('Content-Type: application/json');
			echo $json;
		}

		//주문관리 > 배달분포 주소추가 > 지도 등록
		public function mapadd_input_add(){
		    $mem_id = $this->member->item('mem_id'); //회원번호
		    $ma_tel = $this->input->post("tel"); //전화번호
		    $ma_name = $this->input->post("name"); //전화번호
		    $ma_addr = $this->input->post("addr"); //전화번호
		    $mem_mapadd_day = $this->member->item('mem_mapadd_day');
		    $mem_userid = $this->member->item('mem_userid');

		    $del = array();
		    $del["ab_tel"] = $ma_tel; //전화번호
		    $this->db->delete("cb_ab_".$mem_userid, $del); //데이타 삭제

		    $ins = array();
		    $ins['ab_name'] = $ma_name;
		    $ins['ab_status'] = '1';
		    $ins['ab_group_id'] = '0';
		    $ins['ab_addr'] = $ma_addr;
		    $ins['ab_tel'] = $ma_tel;
		    $this->db->insert("cb_ab_".$mem_userid, $ins);

		    //기존 데이타 삭제
		    $where = array();
		    $where["ma_mem_id"] = $mem_id; //회원번호
		    $where["ma_tel"] = $ma_tel; //전화번호
		    $this->db->delete("cb_mapadd", $where); //데이타 삭제

		    //지도 등록
		    $data = array();
		    $data["ma_mem_id"] = $mem_id; //회원번호
		    $data["ma_tel"] = $ma_tel; //전화번호
		    $this->db->insert("cb_mapadd", $data); //데이타 추가

		    $where = "
			FROM cb_mapadd a
			LEFT JOIN cb_ab_". $mem_userid ." b ON b.ab_tel = a.ma_tel
			WHERE DATE(ma_cre_date) > DATE_SUB(curdate(), INTERVAL ". $mem_mapadd_day ." DAY) ";

		    //주소 조회
		    $sql = "
			SELECT
				  a.ma_id /* 일련번호 */
				, b.ab_name /* 이름 */
				, b.ab_tel /* 전화번호 */
				, b.ab_addr /* 주소 */
			". $where ."
			ORDER BY a.ma_id DESC ";
		    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		    $result = $this->db->query($sql)->result();
		    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);

		    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		    header('Content-Type: application/json');
		    echo $json;
		}
		//주문관리 > 배달분포 주소추가 > 지도 삭제
		public function mapadd_del(){
			$mem_id = $this->member->item('mem_id'); //회원번호
			$mem_userid = $this->member->item('mem_userid'); //회원아이디
			$id = $this->input->post("id"); //일련번호

			//기존 데이타 삭제
			$where = array();
			$where["ma_mem_id"] = $mem_id; //회원번호
			$where["ma_id"] = $id; //일련번호
			$rtn = $this->db->delete("cb_mapadd", $where); //데이타 삭제

			$result = array();
			$result['code'] = '0';
			$json = json_encode($result,JSON_UNESCAPED_UNICODE);
			header('Content-Type: application/json');
			echo $json;
		}

		//주문관리 > 배달분포 주소추가 > 지도표시기간 변경
		public function mapadd_day(){
			$mem_id = $this->member->item('mem_id'); //회원번호
			$mem_userid = $this->member->item('mem_userid'); //회원아이디
			$day = $this->input->post("day"); //지도표시기간

			//배달분포 주소추가 지도표시기간 변경
			$data = array();
			$data["mem_mapadd_day"] = $day; //배달분포 주소추가 지도표시일
			$where = array();
			$where["mem_id"] = $mem_id; //회원번호
			$rtn = $this->db->update("cb_member", $data, $where); //데이타 삭제

			$result = array();
			$result['code'] = '0';
			$json = json_encode($result,JSON_UNESCAPED_UNICODE);
			header('Content-Type: application/json');
			echo $json;
		}

	//주문관리 > 주문내역 > 알림톡 발송
	//controllers/Smart.php
	//mall/Order.php
	function sendat($oid, $mid, $gb){

	    //주문정보
		$sql = "select * from cb_orders d where d.id = '". $oid ."' ";
	    $odr = $this->db->query($sql)->row();

	    //회원정보 (마트)
        //ib플래그
		$sql = "
		SELECT
			  a.*
			, b.spf_key
		FROM cb_member a
		LEFT JOIN ".config_item('ib_profile')." b ON a.mem_id = b.spf_mem_id AND b.spf_use = 'Y'
		WHERE a.mem_id = '". $mid ."' ";
	    $mem = $this->db->query($sql)->row();

        $voucher_flag = $this->voucher_flag($mem->mem_userid, $mid);

		//주문 상품정보
		$sql = "
		SELECT
			  CONCAT(d.name, ' ', d.option) AS good_name /* 상품명 */
			, (SELECT COUNT(*) FROM cb_order_details WHERE order_id = d.order_id) AS good_cnt /* 주문수 */
			, (SELECT IFNULL(SUM(amount),0) FROM cb_order_details WHERE order_id = d.order_id) AS good_amt /* 전체상품금액 */
			, o.deliv_amt /* 배달비 */
			, o.charge_type /* 결재수단 */
			, o.sc_reason /* 결재수단 */
		FROM cb_order_details d
		LEFT JOIN cb_orders o ON o.id = d.order_id
		WHERE d.order_id =  '". $oid ."'
		AND d.id = (SELECT MIN(id) FROM cb_order_details WHERE order_id = d.order_id) ";
		$goods = $this->db->query($sql)->row();

		$good_name = trim($goods->good_name); //주문 상품명
		$good_cnt = $goods->good_cnt; //주문 건수
		if($good_cnt > 1){ //주문 건수가 2건 이상인 경우
			$good_name = $good_name ." 외 ". ($good_cnt - 1) ."건";
		}
		$good_amt = $goods->good_amt; //전체상품금액
		$deliv_amt = $goods->deliv_amt; //배달비
		$tot_amt = number_format($good_amt + $deliv_amt) ."원";//총결제금액 = 전체상품금액 + 배달비
		$charge_type = $this->funn->get_charge_type($goods->charge_type); //결재수단
		$mem_stmall_alim_phn = $mem->mem_stmall_alim_phn; //주문 알림 수신 번호
		$arr_stmall_alim_phn = explode(',', $mem_stmall_alim_phn);

	    //주문내역 확인  URL
		$url = 'http://'. $_SERVER['HTTP_HOST'] .'/smart/orderview?name='. urlencode($odr->receiver) .'&phn='. $odr->phnno .'&oid='. $oid;

		//주문완료시(고객) > 알림톡 발송
		$param = array();
	    $param['Auth'] = $mem->spf_key; //프로필 키
	    $param['alim_flag'] = '3'; //알림톡발송구분(1.구마트톡, 2.신마트톡, 3.지니)
        //ib플래그
        if(config_item('ib_flag')=="Y"){
            if($gb == "0"){ //주문완료
    			$param['temp'] = '20210928_3'; //템플릿 코드 : 주문완료시(고객)
    		}else if($gb == "2"){ //배송시작
    			$param['temp'] = '20210928_4'; //템플릿 코드 : 주문출발시(고객)
    		}else if($gb == "3"){ //배송완료
    			$param['temp'] = '20210928_5'; //템플릿 코드 : 배송완료시(고객)
    		}else if($gb == "4"){ //주문취소
    			$param['temp'] = '20210928_6'; //템플릿 코드 : 주문취소시(고객)
    		}
        }else{
            if($gb == "0"){ //주문완료
    			$param['temp'] = '2021030301'; //템플릿 코드 : 주문완료시(고객)
    		}else if($gb == "2"){ //배송시작
    			$param['temp'] = '2021030303'; //템플릿 코드 : 주문출발시(고객)
    		}else if($gb == "3"){ //배송완료
    			$param['temp'] = '2021030304'; //템플릿 코드 : 배송완료시(고객)
    		}else if($gb == "4"){ //주문취소
    			$param['temp'] = '2021082402'; //템플릿 코드 : 주문취소시(고객)
    		}
        }
	    $param['sender'] = $mem->mem_phone; //발신자 연락처
	    $param['name'] = $odr->receiver; //성명
	    $param['phn'] = $odr->phnno; //수신자 연락처
	    $param['add1'] = $oid; //주문번호
	    $param['add2'] = $good_name; //주문상품
			if($gb == "4"){
		    $param['add3'] = $goods->sc_reason; //취소사유
		    $param['add4'] = $this->funn->format_phone($mem->mem_phone, "-");//문의하기
			}else{
				$param['add3'] = $this->funn->format_phone($mem->mem_phone, "-"); //문의하기
		    $param['add4'] = '';
			}
	    $param['add5'] = '';
	    $param['add6'] = '';
	    $param['add7'] = '';
	    $param['add8'] = '';
	    $param['add9'] = '';
	    $param['add10'] = '';
	    $param['2nd'] = 'Y';
	    $param['m1'] = $url; //주문내역 확인 URL (모바일)
	    $param['pc1'] = $url; //주문내역 확인 URL (PC)
		$param['2nd_btn_add'] = 'Y'; //2차 발송 버튼 링크 추가 여부 2021-03-05
        $param['mid'] = $mid; //mem_id
        $param['voucher'] = $voucher_flag; //바우처 발송여부
	    $rtn = $this->alimtalk_v->send($param);

		//주문완료시(관리자) > 알림톡 발송
		for($i=0; $i<count($arr_stmall_alim_phn); $i++){
			$param = array();
			$param['Auth'] = $mem->spf_key; //프로필 키
			$param['alim_flag'] = '3'; //알림톡발송구분(1.구마트톡, 2.신마트톡, 3.지니)
            //ib플래그
            if(config_item('ib_flag')=="Y"){
                if($gb == "0"){ //주문완료
    				$param['temp'] = '20210928_7'; //템플릿 코드 : 주문완료시(관리자)
    			}else if($gb == "4"){ //주문취소
    				$param['temp'] = '20210928_8'; //템플릿 코드 : 주문취소시(관리자)
    			}
            }else{
                if($gb == "0"){ //주문완료
    				$param['temp'] = '2021030305'; //템플릿 코드 : 주문완료시(관리자)
    			}else if($gb == "4"){ //주문취소
    				$param['temp'] = '2021082401'; //템플릿 코드 : 주문취소시(관리자)
    			}
            }
			$param['sender'] = $mem->mem_phone; //발신자 연락처
			$param['name'] = $odr->receiver; //성명
			$param['phn'] = $arr_stmall_alim_phn[$i]; //수신자 연락처
			$param['add1'] = $oid; //주문번호
			if($gb == "4"){
				$param['add2'] = $good_name; //취소상품
				$param['add3'] = $goods->sc_reason; //취소사유
				$param['add4'] = $odr->receiver; //주문자명
				$param['add5'] = $this->funn->format_phone($odr->phnno, "-"); //휴대폰번호
				$param['add6'] = $charge_type; //결제정보
				$param['add7'] = $tot_amt; //총결제금액
			}else{
				$param['add2'] =
				$param['add3'] = $this->funn->format_phone($odr->phnno, "-"); //휴대폰번호
				$param['add4'] = $charge_type; //결제정보
				$param['add5'] = $tot_amt; //총결제금액
				$param['add6'] = '';
				$param['add7'] = '';
			}
			$param['add8'] = '';
			$param['add9'] = '';
			$param['add10'] = '';
			$param['2nd'] = 'Y';
			$param['m1'] = $url ."&mng=Y"; //주문내역 확인 URL (모바일)
			$param['pc1'] = $url ."&mng=Y"; //주문내역 확인 URL (PC)
			$param['2nd_btn_add'] = 'Y'; //2차 발송 버튼 링크 추가 여부 2021-03-05
            $param['mid'] = $mid; //mem_id
            $param['voucher'] = $voucher_flag; //바우처 발송여부
			$rtn = $this->alimtalk_v->send($param);
		}
	    //log_message("ERROR", "주문완료 Alimtalk 결과 : ".$rtn);
	}

    function voucher_flag($uid, $mid){
        $sql = "
            select a.mem_voucher_yn, ";
        $sql = $sql."
              (SELECT
                SUM(amt_amount * amt_deduct) amt
                 FROM cb_amt_".$uid." WHERE FIND_IN_SET('바우처', amt_memo) OR amt_kind = '9') as voucher_deposit ";
        $sql = $sql."
            from cb_member a
            where a.mem_useyn='Y' and a.mem_id='".$mid."'";

        $voucher = $this->db->query($sql)->row();

        $voucher_flag = "N";

        if($voucher->voucher_deposit>0&&$voucher->mem_voucher_yn=='Y'){
            $voucher_flag = "V";
        }

        return $voucher_flag;
    }

}
?>
