<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Main class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 메인 페이지를 담당하는 controller 입니다.
 */
class Home extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Board', 'Biz_dhn');

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
        if($this->member->item('mem_id') == '2'){
            $this->load->library(array('querystring', 'funn', 'kakaolib'));
        } else {
            $this->load->library(array('querystring', 'funn'));
        }
	}

	//파트너센터 메인 페이지입니다
	public function index(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

        if($this->member->item('mem_id') == '2'){
            $code = $this->input->get('code');
            if (!empty($code)){
                $view['token_data'] = $this->kakaolib->get_token($code);
                if( empty($view['token_data'])){
                    setcookie('key', '', time() - 3600, '/');
                }
                if( !empty($view['token_data']->error) || empty($view['token_data']->access_token) ){
                    setcookie('key', '', time() - 3600, '/');
                }
                setcookie('authorize-access-token', true, time() + (12*3600));
            }
        }

		$this->Biz_dhn_model->make_msg_log_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_customer_book($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_user_image_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_user_deposit_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_send_cache_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_user_group_table($this->member->item('mem_userid')); //고객별 그룹정보 테이블 확인

		$mem_id = $this->member->item("mem_id"); //회원번호
		$mem_userid = $this->member->item("mem_userid"); //회원아이디

		//공지사항
		$sql = "
		SELECT
			post_id /* 일련번호*/
			, post_title /* 제목 */
			, DATE_FORMAT(post_datetime, '%Y.%m.%d') AS post_date /* 등록일자 */
		FROM cb_post
		WHERE brd_id = '1' /* 공지사항 */
		ORDER BY post_id DESC
		LIMIT 1 ";
		//echo $sql ."<br>";
		$view['notice'] = $this->db->query($sql)->row();

		//나의 건수
		$sql = "
		SELECT
			  (SELECT COUNT(1) FROM cb_ab_". $mem_userid ." WHERE 1=1) AS cus_cnt /* 나의 고객수 */
			, (SELECT COUNT(1) FROM cb_pop_screen_data WHERE psd_mem_id = '". $mem_id ."' AND psd_useyn = 'Y') AS psd_cnt /* 스마트 전단 행사수 */
			, (
				SELECT IFNULL(SUM(mst_qty),0) AS mst_qty
				from cb_wt_msg_sent
				where mst_mem_id = '". $mem_id ."'
				AND (
					(mst_reserved_dt = '00000000000000' AND DATE(mst_datetime) = CURDATE())
					OR
					(mst_reserved_dt != '00000000000000' AND LEFT(mst_reserved_dt, 8) = DATE_FORMAT(now(), '%Y%m%d') AND mst_reserved_dt < DATE_FORMAT(now(), '%Y%m%d%H%i%s'))
				)
			) AS mst_qty /* 오늘의 발송현황 건수 */ ";
		//echo $sql ."<br>";
		$view['mycnt'] = $this->db->query($sql)->row();

        $sql = "SELECT mrg_recommend_mem_id FROM cb_member_register WHERE mem_id = '".$mem_id."'";
        $view['gyn'] = $this->db->query($sql)->row()->mrg_recommend_mem_id;

		//포스연동 일별 매출/방문자 조회
		$chart = array();
		$chart_title = ""; //타이틀
		$chart_type1 = ""; //타입1
		$chart_type2 = ""; //타입1
		$chart_sdt; //차트 표기 시작일
		$chart_edt; //차트 표기 종료일
		$mem_pos_yn = $this->member->item("mem_pos_yn"); //포스연동 사용여부
		$mem_ip = $this->member->item("mem_ip"); //포스연동 IP
		$mem_pos_port = $this->member->item("mem_pos_port"); //포스연동 포트
		$view['pos_yn'] = "N"; //포스연동 사용
		//echo "mem_id : ". $mem_id .", mem_userid : ". $mem_userid .", mem_ip : ". $mem_ip .", mem_pos_port : ". $mem_pos_port ."<br>";
		if($mem_pos_yn == "Y" and $mem_ip != "" and $mem_pos_port != ""){
			$view['pos_yn'] = "Y"; //포스연동 사용
			$search_dt = ($this->input->get("search_dt")) ? $this->input->get("search_dt") : date("Y-m-d"); //검색일자
			$data_type = ($this->input->get("data_type")) ? $this->input->get("data_type") : "ALL"; //데이타 구분(ALL.전체, VISIT.방문정보, SALES.매출정보)
			$date_type = ($this->input->get("data_type")) ? $this->input->get("date_type") : "WEEK"; //날짜 구분(DAY.일, WEEK.주, MONTH.월)
			//$date_type = "MONTH"; //날짜 구분(DAY.일, WEEK.주, MONTH.월)
			if($mem_userid == "dhn"){
				//$search_dt = date("2018-01-15"); //검색일자
				//$search_dt = date("2019-12-29"); //검색일자
				//$search_dt = date("2020-01-01"); //검색일자
				$search_dt = date("2019-12-15"); //검색일자
			}
			//echo "mem_pos_id : ". $mem_pos_id .", search_dt : ". $search_dt .", data_type : ". $data_type .", date_type : ". $date_type ."<br>";

			$pram = array();
			$pram['mem_ip'] = $mem_ip; //포스연동 IP
			$pram['mem_pos_port'] = $mem_pos_port; //포스연동 포트
			$pram['data_type'] = $data_type; //데이타 구분(ALL.전체, VISIT.방문정보, SALES.매출정보)
			$pram['date_type'] = $date_type; //날짜 구분(DAY.일, WEEK.주, MONTH.월)
			$pram['scday'] = date("Ymd", strtotime($search_dt)); //검색일
			$pram['yesterday_dt'] = date("Ymd", strtotime($search_dt ." -1 day")); //어제
			$pram['today_dt'] = date("Ymd", strtotime($search_dt)); //오늘

			//지난주 ~ 이번주 날짜
			$scday = date("Y-m-d", strtotime($search_dt ." -7 day")); //검색일자
			$week = date("w", strtotime($scday)); //요일 출력 ex) 수요일 = 3
			$pram['week_prev_sdt'] = date("Ymd", strtotime($scday ." -". $week ." day")); //주별 지난주 시작일
			$pram['week_prev_edt'] = date("Ymd", strtotime($pram['week_prev_sdt'] ." +6 day")); //주별 지난주 종료일
			$scday = date("Y-m-d", strtotime($search_dt)); //검색일자
			$week = date("w", strtotime($scday)); //요일 출력 ex) 수요일 = 3
			$pram['week_this_sdt'] = date("Ymd", strtotime($scday ." -". $week ." day")); //주별 이번주 시작일
			$pram['week_this_edt'] = date("Ymd", strtotime($pram['week_this_sdt'] ." +6 day")); //주별 이번주 종료일
			$week_title = date("y.m.d", strtotime($pram['week_this_sdt'])) ." ~ ". date("m.d", strtotime($pram['week_this_edt'])); //주의 차트 타이틀
			//echo "search_dt : ". $search_dt .", pram['week_prev_sdt'] : ". $pram['week_prev_sdt'] .", pram['week_prev_edt'] : ". $pram['week_prev_edt'] .", pram['week_this_sdt'] : ". $pram['week_this_sdt'] .", pram['week_this_edt'] : ". $pram['week_this_edt'] .", week_title : ". $week_title ."<br>";

			//지난달 ~ 이번달 날짜
			$scday = date("Y-m-", strtotime($search_dt)); //검색일자
			$scday = date("Y-m-d", strtotime($scday ."01 -1 month")); //검색일자
			$year = date("Y", strtotime($scday)); //년도
			$month = date("n", strtotime($scday)); //월
			$pram['month_prev_sdt'] = date("Ymd", mktime(0, 0, 0, $month , 1, $year)); //월별 지난달 시작일
			$pram['month_prev_edt'] = date("Ymd", mktime(0, 0, 0, $month+1 , 0, $year)); //월별 지난달 종료일
			$scday = date("Y-m-d", strtotime($search_dt)); //검색일자
			$year = date("Y", strtotime($scday)); //년도
			$month = date("n", strtotime($scday)); //월
			$pram['month_this_sdt'] = date("Ymd", mktime(0, 0, 0, $month , 1, $year)); //월별 이번달 시작일
			$pram['month_this_edt'] = date("Ymd", mktime(0, 0, 0, $month+1 , 0, $year)); //월별 이번달 종료일
			$month_title = date("y.m.d", strtotime($pram['month_this_sdt'])) ." ~ ". date("m.d", strtotime($pram['month_this_edt'])); //월의 차트 타이틀
			//echo "search_dt : ". $search_dt .", pram['month_prev_sdt'] : ". $pram['month_prev_sdt'] .", pram['month_prev_edt'] : ". $pram['month_prev_edt'] .", pram['month_this_sdt'] : ". $pram['month_this_sdt'] .", pram['month_this_edt'] : ". $pram['month_this_edt'] .", month_title : ". $month_title ."<br>";

			//포스 검색일자
			$start = $pram['month_prev_sdt']; //시작일
			$end = $pram['month_this_edt']; //종료일
			if($pram['week_this_edt'] > $pram['month_this_edt']){
				$end = $pram['week_this_edt']; //종료일
			}
			//echo "search_dt : ". $search_dt .", start : ". $start .", end : ". $end ."<br>";
			$pram['sdate'] = $start; //포스검색 시작일
			$pram['edate'] = $end; //포스검색 종료일

			//curl 통신 확인
			$chkUrl = "http://". $mem_ip .":". $mem_pos_port;
			$chkRtn = $this->funn->curl_alive($chkUrl, 10); //curl 통신 확인
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > chkRtn : ". $chkRtn);
			if($chkRtn == 1){
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 포스연동 > 상품정보 조회 (포스검색)");
				$chart = $this->pos_daily_data($pram); //포스연동 > 판매정보 및 방문정보 조회 (데이타)
				$chart['msg'] = "success";
			}else{
				log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 포스연동 > 통신오류");
				$chart['msg'] = "error";
			}
		}

        $sql = "SELECT count(1) as cnt, kvd_proc_flag from cb_kvoucher_deposit where kvd_mem_id = ".$this->member->item('mem_id');
        $able_voucher = $this->db->query($sql)->row();

        $view['voucher']="";

        if($able_voucher->cnt>0&&$able_voucher->kvd_proc_flag=="Y"){
            $sql = "
                SELECT
                    *
                FROM
                    cb_wt_voucher_addon cva
                LEFT JOIN
                    cb_member cm
                ON
                    cva.vad_mem_id = cm.mem_id
                LEFT JOIN (
                        SELECT
                            mst_mem_id
                          , sum(mst_qty) AS sum_qty
                        FROM
                            cb_wt_msg_sent
                        WHERE (
                            (mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN DATE_SUB(NOW(), INTERVAL 20 DAY) AND NOW()))
                            OR
                            (mst_reserved_dt = '00000000000000' AND (mst_datetime BETWEEN DATE_SUB(NOW(), INTERVAL 20 DAY) AND NOW()))
                        )
                        GROUP BY mst_mem_id
                    ) AS tmp on cm.mem_id = tmp.mst_mem_id
                WHERE
                    cm.mem_level = 1
                    AND
                    cm.mem_register_datetime < DATE_SUB(NOW(), INTERVAL 20 DAY)
                    AND
                    (tmp.sum_qty <= 0 OR tmp.sum_qty IS NULL)
                    AND
                    cva.vad_edate > NOW()
                    AND
                    cm.mem_voucher_yn = 'Y'
                    AND
                    cm.mem_id = " . $mem_id . "
            ";
            $view['voucher'] = $this->db->query($sql)->row();
        }



        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql);

		$chart['scday'] = $search_dt; //검색일
		$chart['data_type'] = $data_type; //데이타 구분(ALL.전체, VISIT.방문정보, SALES.매출정보)
		$chart['date_type'] = $date_type; //날짜 구분(DAY.일, WEEK.주, MONTH.월)
		$chart['week_title'] = $week_title; //주별 타이틀
		$chart['month_title'] = $month_title; //월별 타이틀
		//echo "scday : ". $chart['scday'] .", TODAY_VISIT_TOT : ". $chart['TODAY_VISIT_TOT'] .", TODAY_SALES_TOT : ". $chart['TODAY_SALES_TOT'] .", YESTERDAY_VISIT_TOT : ". $chart['YESTERDAY_VISIT_TOT'] .", YESTERDAY_SALES_TOT : ". $chart['YESTERDAY_SALES_TOT'] ."<br>WEEK_PREV_VISIT_TOT : ". $chart['WEEK_PREV_VISIT_TOT'] .", WEEK_PREV_SALES_TOT : ". $chart['WEEK_PREV_SALES_TOT'] ."<br>WEEK_VISIT_CAT : ". $chart['WEEK_VISIT_CAT'] ."<br>WEEK_VISIT_CNT : ". $chart['WEEK_VISIT_CNT'] ."<br>WEEK_SALES_CAT : ". $chart['WEEK_SALES_CAT'] ."<br>WEEK_SALES_AMT : ". $chart['WEEK_SALES_AMT'] ."<br>MONTH_PREV_VISIT_TOT : ". $chart['MONTH_PREV_VISIT_TOT'] .", MONTH_PREV_SALES_TOT : ". $chart['MONTH_PREV_SALES_TOT'] ."<br>MONTH_VISIT_CAT : ". $chart['MONTH_VISIT_CAT'] ."<br>MONTH_VISIT_CNT : ". $chart['MONTH_VISIT_CNT'] ."<br>MONTH_SALES_CAT : ". $chart['MONTH_SALES_CAT'] ."<br>MONTH_SALES_AMT : ". $chart['MONTH_SALES_AMT'] ."<br>";
		$view['chart'] = $chart;

        //이벤트 가능업체 여부
        $where_mem1 = "";
        $view['eve_cnt'] = 0;
        if(!empty(config_item('eve1_member'))){
            foreach(config_item('eve1_member') as $r){
                if($where_mem1!=""){
                    $where_mem1.=",";
                }
                $where_mem1.=$r;
            }
            if(!empty($where_mem1)){
                $sql = "select count(1) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE  cmr.mrg_recommend_mem_id in ( ".$where_mem1." ) and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql." / mem_id > ".$mem_id);
                $view['eve_cnt'] = $this->db->query($sql)->row()->cnt;
            }
        }
        // //이벤트 가능업체 여부(티지)
        $where_mem2 = "";
        $view['eve_cnt2'] = 0;
        if(!empty(config_item('eve2_member'))){
            foreach(config_item('eve2_member') as $r){
                if($where_mem2!=""){
                    $where_mem2.=",";
                }
                $where_mem2.=$r;
            }
            if(!empty($where_mem2)){
                $sql = "select count(1) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE  cmr.mrg_recommend_mem_id in ( ".$where_mem2." ) and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
                // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql." / mem_id > ".$mem_id);
                $view['eve_cnt2'] = $this->db->query($sql)->row()->cnt;
            }
        }


        // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE ( cmr.mrg_recommend_mem_id = 3 or cmr.mrg_recommend_mem_id = 1260) and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
		// $view['eve_cnt'] = $this->db->query($sql)->row()->cnt;
        //

        // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE cmr.mrg_recommend_mem_id = 962 and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
        // $view['eve_cnt2'] = $this->db->query($sql)->row()->cnt;


		$my_id = $this->member->item("mem_id");
		$view['unread_announces'] = $this->db->query("
			SELECT * FROM cb_announce
			WHERE an_id IN (
				SELECT ans_an_id
				FROM cb_announce_state
				WHERE ans_discarded = FALSE
				 	AND ans_mem_id = ".$my_id."
					AND ans_read = FALSE
			)
		")->result();

        //레이아웃을 정의합니다
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');
		$layoutconfig = array(
			'path' => 'home',
			'layout' => 'layout',
			'skin' => 'main',
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

	//파트너센터 테스트 페이지입니다
	public function test(){
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		$mem_id = $this->member->item("mem_id"); //회원번호
		$mem_userid = $this->member->item("mem_userid"); //회원아이디

		//공지사항
		$sql = "
		SELECT
			post_id /* 일련번호*/
			, post_title /* 제목 */
			, DATE_FORMAT(post_datetime, '%Y.%m.%d') AS post_date /* 등록일자 */
		FROM cb_post
		WHERE brd_id = '1' /* 공지사항 */
		ORDER BY post_id DESC
		LIMIT 1 ";
		//echo $sql ."<br>";
		$view['notice'] = $this->db->query($sql)->row();

		//나의 건수
		$sql = "
		SELECT
			  (SELECT COUNT(*) FROM cb_ab_". $mem_userid ." WHERE 1=1) AS cus_cnt /* 나의 고객수 */
			, (SELECT COUNT(*) FROM cb_pop_screen_data WHERE psd_mem_id = '". $mem_id ."' AND psd_useyn = 'Y') AS psd_cnt /* 스마트 전단 행사수 */
			, (
				SELECT IFNULL(SUM(mst_qty),0) AS mst_qty
				from cb_wt_msg_sent
				where mst_mem_id = '". $mem_id ."'
				AND (
					(mst_reserved_dt = '00000000000000' AND DATE(mst_datetime) = CURDATE())
					OR
					(mst_reserved_dt != '00000000000000' AND LEFT(mst_reserved_dt, 8) = DATE_FORMAT(now(), '%Y%m%d') AND mst_reserved_dt < DATE_FORMAT(now(), '%Y%m%d%H%i%s'))
				)
			) AS mst_qty /* 오늘의 발송현황 건수 */ ";
		//echo $sql ."<br>";
		$view['mycnt'] = $this->db->query($sql)->row();

		//포스연동 일별 매출/방문자 조회
		$chart = array();
		$chart_title = ""; //타이틀
		$chart_type1 = ""; //타입1
		$chart_type2 = ""; //타입1
		$chart_sdt; //차트 표기 시작일
		$chart_edt; //차트 표기 종료일
		$mem_pos_id = $this->member->item("mem_pos_id"); //포스연동 ID
		//$mem_pos_id = "";
		$view['mem_pos_id'] = $mem_pos_id;
		//echo "mem_id : ". $mem_id .", mem_userid : ". $mem_userid .", mem_pos_id : ". $mem_pos_id ."<br>";
		if($mem_pos_id != ""){
			$search_dt = ($this->input->get("search_dt")) ? $this->input->get("search_dt") : date("Y-m-d"); //검색일자
			$data_type = ($this->input->get("data_type")) ? $this->input->get("data_type") : "ALL"; //데이타 구분(ALL.전체, VISIT.방문정보, SALES.매출정보)
			$date_type = ($this->input->get("data_type")) ? $this->input->get("date_type") : "WEEK"; //날짜 구분(DAY.일, WEEK.주, MONTH.월)
			//$date_type = "MONTH"; //날짜 구분(DAY.일, WEEK.주, MONTH.월)
			if($mem_pos_id == "dhn"){
				//$search_dt = date("2018-01-15"); //검색일자
				//$search_dt = date("2019-12-29"); //검색일자
				$search_dt = date("2020-01-01"); //검색일자
				//$search_dt = date("2019-12-01"); //검색일자
			}
			//echo "mem_pos_id : ". $mem_pos_id .", search_dt : ". $search_dt .", data_type : ". $data_type .", date_type : ". $date_type ."<br>";

			$pram = array();
			$pram['name'] = $mem_pos_id; //포스연동 ID
			$pram['data_type'] = $data_type; //데이타 구분(ALL.전체, VISIT.방문정보, SALES.매출정보)
			$pram['date_type'] = $date_type; //날짜 구분(DAY.일, WEEK.주, MONTH.월)
			$pram['scday'] = date("Ymd", strtotime($search_dt)); //검색일
			$pram['yesterday_dt'] = date("Ymd", strtotime($search_dt ." -1 day")); //어제
			$pram['today_dt'] = date("Ymd", strtotime($search_dt)); //오늘

			//지난주 ~ 이번주 날짜
			$scday = date("Y-m-d", strtotime($search_dt ." -7 day")); //검색일자
			$week = date("w", strtotime($scday)); //요일 출력 ex) 수요일 = 3
			$pram['week_prev_sdt'] = date("Ymd", strtotime($scday ." -". $week ." day")); //주별 지난주 시작일
			$pram['week_prev_edt'] = date("Ymd", strtotime($pram['week_prev_sdt'] ." +6 day")); //주별 지난주 종료일
			$scday = date("Y-m-d", strtotime($search_dt)); //검색일자
			$week = date("w", strtotime($scday)); //요일 출력 ex) 수요일 = 3
			$pram['week_this_sdt'] = date("Ymd", strtotime($scday ." -". $week ." day")); //주별 이번주 시작일
			$pram['week_this_edt'] = date("Ymd", strtotime($pram['week_this_sdt'] ." +6 day")); //주별 이번주 종료일
			$week_title = date("y.m.d", strtotime($pram['week_this_sdt'])) ." ~ ". date("m.d", strtotime($pram['week_this_edt'])); //주의 차트 타이틀
			//echo "search_dt : ". $search_dt .", pram['week_prev_sdt'] : ". $pram['week_prev_sdt'] .", pram['week_prev_edt'] : ". $pram['week_prev_edt'] .", pram['week_this_sdt'] : ". $pram['week_this_sdt'] .", pram['week_this_edt'] : ". $pram['week_this_edt'] .", week_title : ". $week_title ."<br>";

			//지난달 ~ 이번달 날짜
			$scday = date("Y-m-", strtotime($search_dt)); //검색일자
			$scday = date("Y-m-d", strtotime($scday ."01 -1 month")); //검색일자
			$year = date("Y", strtotime($scday)); //년도
			$month = date("n", strtotime($scday)); //월
			$pram['month_prev_sdt'] = date("Ymd", mktime(0, 0, 0, $month , 1, $year)); //월별 지난달 시작일
			$pram['month_prev_edt'] = date("Ymd", mktime(0, 0, 0, $month+1 , 0, $year)); //월별 지난달 종료일
			$scday = date("Y-m-d", strtotime($search_dt)); //검색일자
			$year = date("Y", strtotime($scday)); //년도
			$month = date("n", strtotime($scday)); //월
			$pram['month_this_sdt'] = date("Ymd", mktime(0, 0, 0, $month , 1, $year)); //월별 이번달 시작일
			$pram['month_this_edt'] = date("Ymd", mktime(0, 0, 0, $month+1 , 0, $year)); //월별 이번달 종료일
			$month_title = date("y.m.d", strtotime($pram['month_this_sdt'])) ." ~ ". date("m.d", strtotime($pram['month_this_edt'])); //월의 차트 타이틀
			//echo "search_dt : ". $search_dt .", pram['month_prev_sdt'] : ". $pram['month_prev_sdt'] .", pram['month_prev_edt'] : ". $pram['month_prev_edt'] .", pram['month_this_sdt'] : ". $pram['month_this_sdt'] .", pram['month_this_edt'] : ". $pram['month_this_edt'] .", month_title : ". $month_title ."<br>";

			//포스 검색일자
			$start = $pram['month_prev_sdt']; //시작일
			$end = $pram['month_this_edt']; //종료일
			if($pram['week_this_edt'] > $pram['month_this_edt']){
				$end = $pram['week_this_edt']; //종료일
			}
			//echo "search_dt : ". $search_dt .", start : ". $start .", end : ". $end ."<br>";
			$pram['sdate'] = $start; //포스검색 시작일
			$pram['edate'] = $end; //포스검색 종료일
			$chart = $this->pos_daily_data($pram); //포스연동 > 판매정보 및 방문정보 조회 (데이타)
		}
		$chart['scday'] = $search_dt; //검색일
		$chart['data_type'] = $data_type; //데이타 구분(ALL.전체, VISIT.방문정보, SALES.매출정보)
		$chart['date_type'] = $date_type; //날짜 구분(DAY.일, WEEK.주, MONTH.월)
		$chart['week_title'] = $week_title; //주별 타이틀
		$chart['month_title'] = $month_title; //월별 타이틀
		//echo "scday : ". $chart['scday'] .", TODAY_VISIT_TOT : ". $chart['TODAY_VISIT_TOT'] .", TODAY_SALES_TOT : ". $chart['TODAY_SALES_TOT'] .", YESTERDAY_VISIT_TOT : ". $chart['YESTERDAY_VISIT_TOT'] .", YESTERDAY_SALES_TOT : ". $chart['YESTERDAY_SALES_TOT'] ."<br>WEEK_PREV_VISIT_TOT : ". $chart['WEEK_PREV_VISIT_TOT'] .", WEEK_PREV_SALES_TOT : ". $chart['WEEK_PREV_SALES_TOT'] ."<br>WEEK_VISIT_CAT : ". $chart['WEEK_VISIT_CAT'] ."<br>WEEK_VISIT_CNT : ". $chart['WEEK_VISIT_CNT'] ."<br>WEEK_SALES_CAT : ". $chart['WEEK_SALES_CAT'] ."<br>WEEK_SALES_AMT : ". $chart['WEEK_SALES_AMT'] ."<br>MONTH_PREV_VISIT_TOT : ". $chart['MONTH_PREV_VISIT_TOT'] .", MONTH_PREV_SALES_TOT : ". $chart['MONTH_PREV_SALES_TOT'] ."<br>MONTH_VISIT_CAT : ". $chart['MONTH_VISIT_CAT'] ."<br>MONTH_VISIT_CNT : ". $chart['MONTH_VISIT_CNT'] ."<br>MONTH_SALES_CAT : ". $chart['MONTH_SALES_CAT'] ."<br>MONTH_SALES_AMT : ". $chart['MONTH_SALES_AMT'] ."<br>";
		$view['chart'] = $chart;

        //레이아웃을 정의합니다
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');
		$layoutconfig = array(
			'path' => 'home',
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

	//포스연동 > 판매정보 및 방문정보 조회 (데이타)
	public function pos_daily_data($pram){
		//포스연동
		$data = array();
		//echo "/home/pos_daily_data > name : ". $pram['name'] .", scday : ". $pram['scday'] .", today_dt : ". $pram['today_dt'] .", yesterday_dt : ". $pram['yesterday_dt'] .", week_this_sdt : ". $pram['week_this_sdt'] .", week_this_edt : ". $pram['week_this_edt'] .", sdate : ". $pram['sdate'] .", edate : ". $pram['edate'] .", data_type : ". $pram['data_type'] .", date_type : ". $pram['date_type'] ."<br>";
		//log_message("ERROR", "/home/pos_daily_data > name : ". $pram['name'] .", scday : ". $pram['scday'] .", sdate : ". $pram['sdate'] .", edate : ". $pram['edate'] .", data_type : ". $pram['data_type'] .", date_type : ". $pram['date_type']);
		if($pram['mem_ip'] != "" && $pram['mem_pos_port'] != "" && $pram['sdate'] != "" && $pram['edate'] != ""){
			$pos_data = $this->pos_daily_search($pram); //포스연동 > 판매정보 및 방문정보 조회
			$no = 0;
			$wno = 0;
			$mno = 0;
			if($pram['date_type'] == "DAY"){ //날짜 구분(DAY.일, WEEK.주, MONTH.월)
				if(!empty($pos_data)){
					foreach ($pos_data as $key => $value){
						if($no == 0){
							$data['VISIT_CAT'] = $value['sell_time']; //방문정보 카테고리
							$data['VISIT_CNT'] = $value['sell_cnt']; //방문정보 방문수
							$data['VISIT_TOT'] = $value['sell_cnt']; //방문정보 전체방문수
							$data['SALES_CAT'] = $value['sell_time']; //매출정보 카테고리
							$data['SALES_AMT'] = $value['sell_amt']; //매출정보 매출액
							$data['SALES_TOT'] = $value['sell_amt']; //매출정보 전체매출액
						}else{
							$data['VISIT_CAT'] .= ", ". $value['sell_time']; //방문정보 카테고리
							$data['VISIT_CNT'] .= ", ". $value['sell_cnt']; //방문정보 방문수
							$data['VISIT_TOT'] += $value['sell_cnt']; //방문정보 전체방문수
							$data['SALES_CAT'] .= ", ". $value['sell_time']; //매출정보 카테고리
							$data['SALES_AMT'] .= ", ". $value['sell_amt']; //매출정보 매출액
							$data['SALES_TOT'] += $value['sell_amt']; //매출정보 전체매출액
						}
						//echo "VISIT_CAT : ". $data['VISIT_CAT'] ."<br>VISIT_CNT : ". $data['VISIT_CNT'] ."<br>VISIT_TOT : ". $data['VISIT_TOT'] ."<br>SALES_CAT : ". $data['SALES_CAT'] ."<br>SALES_AMT : ". $data['SALES_AMT'] ."<br>SALES_TOT : ". $data['SALES_TOT'] ."<br>";
						$no++;
					} //foreach ($pos_data as $key => $value){
				}
			}else{ //if($pram['date_type'] == "DAY"){ //날짜 구분(DAY.일, WEEK.주, MONTH.월)
				if(!empty($pos_data)){
					foreach ($pos_data as $key => $value){
						//echo "dr_yymmdd : ". $value['dr_yymmdd'] ."<br>dd : ". date("j", strtotime($value['dr_yymmdd'])) ."<br>total_sell : ". $value['total_sell'] ."<br>dr_sellcount : ". $value['dr_sellcount'] ."<br>dr_memsellcount : ". $value['dr_memsellcount'] ."<br>dr_memnew : ". $value['dr_memnew'] ."<br>tw_exestart : ". $value['tw_exestart'] ."<br><br>";
						$dd = date("n.j", strtotime($value['dr_yymmdd'])); //날짜
						//오늘/어제 매출/방문자 정보
						if($pram['today_dt'] == $value['dr_yymmdd']){ //오늘
							$data['TODAY_VISIT_TOT'] = $value['dr_memsellcount']; //오늘 방문정보 전체방문수
							$data['TODAY_SALES_TOT'] = $value['total_sell']; //오늘 매출정보 전체매출액
						}else if($pram['yesterday_dt'] == $value['dr_yymmdd']){ //어제
							$data['YESTERDAY_VISIT_TOT'] = $value['dr_memsellcount']; //어제 방문정보 전체방문수
							$data['YESTERDAY_SALES_TOT'] = $value['total_sell']; //어제 매출정보 전체매출액
						}
						//echo "chart_sdt : ". $pram['chart_sdt'] .", chart_edt : ". $pram['chart_edt'] .", dr_yymmdd : ". $value['dr_yymmdd'] ."<br>";
						//log_message("ERROR", "/home/pos_daily_data > chart_sdt : ". $pram['chart_sdt'] .", chart_edt : ". $pram['chart_edt'] .", dr_yymmdd : ". $value['dr_yymmdd']);
						//주별 데이타
						if($pram['week_this_sdt'] <= $value['dr_yymmdd'] and $pram['week_this_edt'] >= $value['dr_yymmdd']){ //이번주
							//echo "현재 데이타 통계 [". $cno ."] dr_yymmdd : ". $value['dr_yymmdd'] .", VISIT_CNT : ". $value['dr_memsellcount'] .", SALES_AMT : ". $value['total_sell'] ."<br>";
							if($wno == 0){
								$data['WEEK_VISIT_CAT'] = $dd; //방문정보 카테고리
								$data['WEEK_VISIT_CNT'] = $value['dr_memsellcount']; //방문정보 방문수
								$data['WEEK_VISIT_TOT'] = $value['dr_memsellcount']; //방문정보 전체방문수
								$data['WEEK_SALES_CAT'] = $dd; //매출정보 카테고리
								$data['WEEK_SALES_AMT'] = $value['total_sell']; //매출정보 매출액
								$data['WEEK_SALES_TOT'] = $value['total_sell']; //매출정보 전체매출액
							}else{
								$data['WEEK_VISIT_CAT'] .= ", ". $dd; //방문정보 카테고리
								$data['WEEK_VISIT_CNT'] .= ", ". $value['dr_memsellcount']; //방문정보 방문수
								$data['WEEK_VISIT_TOT'] += $value['dr_memsellcount']; //방문정보 전체방문수
								$data['WEEK_SALES_CAT'] .= ", ". $dd; //매출정보 카테고리
								$data['WEEK_SALES_AMT'] .= ", ". $value['total_sell']; //매출정보 매출액
								$data['WEEK_SALES_TOT'] += $value['total_sell']; //매출정보 전체매출액
							}
							$wno++;
						}else if($pram['week_prev_sdt'] <= $value['dr_yymmdd'] and $pram['week_prev_edt'] >= $value['dr_yymmdd']){ //지난주
							//echo "지난 데이타 통계 [". $no ."] dr_yymmdd : ". $value['dr_yymmdd'] .", VISIT_CNT : ". $value['dr_memsellcount'] .", SALES_AMT : ". $value['total_sell'] ."<br>";
							$data['WEEK_PREV_VISIT_TOT'] += $value['dr_memsellcount']; //지난 방문정보 전체방문수 (지난주/이난달)
							$data['WEEK_PREV_SALES_TOT'] += $value['total_sell']; //지난 매출정보 전체매출액 (지난주/이난달)
						}
						//월별 데이타
						if($pram['month_this_sdt'] <= $value['dr_yymmdd'] and $pram['month_this_edt'] >= $value['dr_yymmdd']){ //차트 표시일자
							//echo "현재 데이타 통계 [". $cno ."] dr_yymmdd : ". $value['dr_yymmdd'] .", VISIT_CNT : ". $value['dr_memsellcount'] .", SALES_AMT : ". $value['total_sell'] ."<br>";
							if($mno == 0){
								$data['MONTH_VISIT_CAT'] = $dd; //방문정보 카테고리
								$data['MONTH_VISIT_CNT'] = $value['dr_memsellcount']; //방문정보 방문수
								$data['MONTH_VISIT_TOT'] = $value['dr_memsellcount']; //방문정보 전체방문수
								$data['MONTH_SALES_CAT'] = $dd; //매출정보 카테고리
								$data['MONTH_SALES_AMT'] = $value['total_sell']; //매출정보 매출액
								$data['MONTH_SALES_TOT'] = $value['total_sell']; //매출정보 전체매출액
							}else{
								$data['MONTH_VISIT_CAT'] .= ", ". $dd; //방문정보 카테고리
								$data['MONTH_VISIT_CNT'] .= ", ". $value['dr_memsellcount']; //방문정보 방문수
								$data['MONTH_VISIT_TOT'] += $value['dr_memsellcount']; //방문정보 전체방문수
								$data['MONTH_SALES_CAT'] .= ", ". $dd; //매출정보 카테고리
								$data['MONTH_SALES_AMT'] .= ", ". $value['total_sell']; //매출정보 매출액
								$data['MONTH_SALES_TOT'] += $value['total_sell']; //매출정보 전체매출액
							}
							$mno++;
						}else if($pram['month_prev_sdt'] <= $value['dr_yymmdd'] and $pram['month_prev_edt'] >= $value['dr_yymmdd']){ //지난 데이타 통계
							//echo "지난 데이타 통계 [". $no ."] dr_yymmdd : ". $value['dr_yymmdd'] .", VISIT_CNT : ". $value['dr_memsellcount'] .", SALES_AMT : ". $value['total_sell'] ."<br>";
							$data['MONTH_PREV_VISIT_TOT'] += $value['dr_memsellcount']; //지난 방문정보 전체방문수 (지난주/이난달)
							$data['MONTH_PREV_SALES_TOT'] += $value['total_sell']; //지난 매출정보 전체매출액 (지난주/이난달)
						}
						//echo "VISIT_CAT : ". $data['VISIT_CAT'] .", VISIT_CNT : ". $data['VISIT_CNT'] .", VISIT_TOT : ". $data['VISIT_TOT'] .", SALES_CAT : ". $data['SALES_CAT'] .", SALES_AMT : ". $data['SALES_AMT'] .", SALES_TOT : ". $data['SALES_TOT'] .", PREV_VISIT_TOT : ". $data['PREV_VISIT_TOT'] .", PREV_SALES_TOT : ". $data['PREV_SALES_TOT'] ."<br>";
						$no++;
					} //foreach ($pos_data as $key => $value){
				}
			} //if($pram['date_type'] == "DAY"){ //날짜 구분(DAY.일, WEEK.주, MONTH.월)
			//echo "VISIT_CAT : ". $data['VISIT_CAT'] ."<br>VISIT_CNT : ". $data['VISIT_CNT'] ."<br>VISIT_TOT : ". $data['VISIT_TOT'] ."<br>SALES_CAT : ". $data['SALES_CAT'] ."<br>SALES_AMT : ". $data['SALES_AMT'] ."<br>SALES_TOT : ". $data['SALES_TOT'] ."<br>PREV_VISIT_TOT : ". $data['PREV_VISIT_TOT'] ."<br>PREV_SALES_TOT : ". $data['PREV_SALES_TOT'] ."<br>";
		}
		return $data;
	}

	//포스연동 > 판매정보 및 방문정보 조회 (포스검색)
	public function pos_daily_search($pram){
		//echo "/home/pos_daily_search > name : ". $pram['name'] .", sdate : ". $pram['sdate'] .", edate : ". $pram['edate'] .", data_type : ". $pram['data_type'] .", date_type : ". $pram['date_type'] ."<br>";
		//'log_message("ERROR", "/home/pos_daily_search > name : ". $pram['name'] .", sdate : ". $pram['sdate'] .", edate : ". $pram['edate']);
		$pos_search = array();
		//$pram['mem_ip'] = $mem_ip; //포스연동 IP
		if($pram['mem_ip'] != "" && $pram['mem_pos_port'] != "" && $pram['sdate'] != "" && $pram['edate'] != ""){
			//$pos_url = "http://222.122.203.68:7676/daily?name=". $pram['name'] ."&sdate=". $pram['sdate'] ."&edate=". $pram['edate'];
			//$pos_url = "http://182.208.215.189:9092/api/getDailySellList/20191001/20191002";
			$pos_url = "http://". $pram['mem_ip'] .":". $pram['mem_pos_port'] ."/api/getDailySellList/". $pram['sdate'] ."/". $pram['edate'];
			//echo "pos_url : ". $pos_url ."<br>";
			//log_message("ERROR", "/home/pos_daily_data > pos_url : ". $pos_url);
			$ch = curl_init();
			$options = array(
				CURLOPT_URL => $pos_url, //연결 URL
				CURLOPT_HEADER => false, //TRUE로 설정 시 헤더의 내용을 출력
				CURLOPT_HTTPHEADER => array("Content-Type:application/json"),
				CURLOPT_RETURNTRANSFER => true //TRUE로 설정 시 curl_exec()의 반환 값을 문자열로 반환
			);
			curl_setopt_array($ch, $options);
			$buffer = curl_exec ($ch);
			$cinfo = curl_getinfo($ch);
			curl_close($ch);
			//header('Content-Type: application/json');
			//echo $buffer;

			$pos_search = json_decode($buffer, true);
			//JSon 데이터 조회
			//foreach ($pos_search as $key => $value){
			//	echo "dr_yymmdd : ". $value['dr_yymmdd'] ."<br>total_sell : ". $value['total_sell'] ."<br>dr_sellcount : ". $value['dr_sellcount'] ."<br>dr_memsellcount : ". $value['dr_memsellcount'] ."<br>dr_memnew : ". $value['dr_memnew'] ."<br>tw_exestart : ". $value['tw_exestart'] ."<br><br>";
			//}
		}
		return $pos_search;
	}

}
