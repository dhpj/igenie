<?php
class Stats extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board');
    protected $gAgent = array("prcom"=>"1", "naself"=>"2");
    protected $phn_agent = array("prcom"=>"P", "naself"=>"");
    protected $refund_agent = array("prcom"=>"3", "naself"=>"4");
    protected $sms_agent = array("prcom"=>"", "naself"=>"S");
    protected $lms_agent = array("prcom"=>"", "naself"=>"L");
    protected $mms_agent = array("prcom"=>"", "naself"=>"M");

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

    //메인 통계화면
	public function index(){
        if($this->member->item('mem_level') >= 10) {
            $this_mem_id = $this->member->item('mem_id');
            $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
            if($key) {
                $mem = $this->db->query("select mem_id from cb_member where mem_id=?", array($key))->row();
                if($mem && $mem->mem_id==$key) {
                    $_SESSION["mem_id"] = $key;
                    if(!$this->session->userdata('login_stack')) {
                        $login_stack = array();
                        array_push($login_stack, $this_mem_id);
                        $this->session->set_userdata('login_stack', $login_stack);
                    } else {
                        $login_stack = $this->session->userdata('login_stack');
                        array_push($login_stack, $this_mem_id);
                        $this->session->set_userdata('login_stack', $login_stack);
                    }
                    Header("Location: /");
                    exit;
                }
            }
        }
        $this->load->model("Biz_model");
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

		$mem_id = $this->member->item("mem_id"); //회원번호
		//$view['isManager'] = 'N';
		//$scDate = "2020-05-07"; //조회일자
		//$mem_id = "687"; //회원번호 (우리마트양산시농수산물종합유통센터) : 친구톡 + 2차문자
		//$mem_id = "296"; //회원번호 (남밀양농협하나로마트) : 친구톡 + 2차문자
		//$mem_id = "686"; //회원번호 (인동농협하나로마트 양포점) : 알림톡 + 친구톡이미지
		//$mem_id = "471"; //회원번호 (웅상농협하나로마트) : 일별통계
		//$mem_id = "47"; //회원번호 (프리마트_수택점) : 일별통계
		//$mem_id = "383"; //회원번호 (거창농업협동조합하나로마트) : 일별통계

		//관리자 DHN 으로 들어 왔을경우 "특정 기능 및 항목"을 보이게 하기 위해
		//mem_id => 2 : dhnadmin, 3 : dhn
		if($mem_id == '2' || $mem_id == '3') {
			$view['isManager'] = 'Y';
		} else {
			if($this->session->userdata('login_stack')) {
				$login_stack = $this->session->userdata('login_stack');
				//log_message("ERROR",'Login Stack : '. $login_stack[0]);
				if($login_stack[0] == '2' || $login_stack[0] == '3') {
					$view['isManager'] = 'Y';
				}
			}
		}
		//echo "mem_id : ". $this->member->item('mem_id') .", isManager : ". $view['isManager'] ."<br>";

 		$view['param']['isManager'] = $view['isManager'];
 		$view['param']['mem_id'] = $mem_id;
 		$view['param']['type'] = "D"; //구분(D.일, W.주, M.월)
 		$view['param']['sno'] = "0"; //시작시간
 		$view['param']['eno'] = "23"; //종료시간
 		$view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m-d');
 		$view['param']['endDate'] = ($this->input->post('endDate')) ? $this->input->post('endDate') : date('Y-m-d');

		//echo "param : ". $view['param'] ."<br><br>";

		$view['mst_data'] = $this->get_mst_data($view['param']); //발송건수 데이타
		$view['mst_graph'] = $this->get_mst_graph($view['param']); //발송건수 그래프

		//계약만료전 업체 조회
		$sql = "
		SELECT
			  mem_id /* 업체번호 */
			, mem_userid /* 업체 계정 */
			, mem_username /* 업체명 */
			, mem_cont_from_date /* 계약 시작일 */
			, mem_cont_to_date /* 계약 만료일 */
		FROM cb_member
		WHERE mem_cont_cancel_yn != 'Y' /* 해지여부 (Y.해지, N.정상) */
		AND mem_cont_to_date BETWEEN CURDATE() AND DATE_SUB(CURDATE(), INTERVAL -3 MONTH) /* 계약만료 3개월 조회 */
		ORDER BY mem_cont_to_date ASC
		LIMIT 50 ";
		//echo $sql ."<br>";
		$view['expire_list'] = $this->db->query($sql)->result();

		$sql = "
        select
        	(select count(1) from cb_member WHERE mem_useyn='Y' and mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
            				UNION ALL
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
            				SELECT DISTINCT mem_id
            				FROM cmem UNION ALL
            				SELECT ".$mem_id." mem_id)) as mem_cnt, /* 전체 고객 업체 */
        	(select count(1) from cb_member where mem_cont_cancel_yn  = 'Y' AND  mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
            				UNION ALL
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
            				SELECT DISTINCT mem_id
            				FROM cmem UNION ALL
            				SELECT ".$mem_id." mem_id)) as mem_cont_cnacel_cnt, /* 계정 해지 업체 */
        	(select count(1) from cb_member  a LEFT JOIN cb_member_dormant_dhn b ON a.mem_id = b.mem_id where b.mdd_dormant_flag = 1  and a.mem_useyn='Y' AND  a.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
            				UNION ALL
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
            				SELECT DISTINCT mem_id
            				FROM cmem UNION ALL
            				SELECT ".$mem_id." mem_id)) as mem_dormant_cnt /* 휴면 업체 */
		";
		// log_message("ERROR", "Stats.php sql : ".$sql);
		$view['compony_cnt'] = $this->db->query($sql)->row();

        $sql = "
            SELECT
                COUNT(*) AS cnt
            FROM
                cb_wt_voucher_addon cva
            LEFT JOIN
                cb_member cm
            ON
                cva.vad_mem_id = cm.mem_id
            WHERE
                cm.mem_voucher_yn = 'Y'
                and cm.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                SELECT DISTINCT mem_id
                FROM cmem UNION ALL
                SELECT ".$mem_id." mem_id)
        ";
        $view["v_all_cnt"] = $this->db->query($sql)->row()->cnt;

        $sql = "
            SELECT
                COUNT(*) AS cnt
            FROM
                cb_wt_voucher_addon cva
            LEFT JOIN
                cb_member cm
                ON
                cva.vad_mem_id = cm.mem_id
            LEFT JOIN(
                SELECT
                    mem_id
                  , MAX(mll_id) AS mll_id
                  , MAX(mll_datetime) AS mll_datetime
                FROM
                    cb_member_login_log
                WHERE
                    mll_datetime BETWEEN DATE_SUB(NOW(), INTERVAL 20 DAY) AND NOW()
                    AND
                    mll_success = 1
                GROUP BY
                    mem_id
            ) AS login ON cm.mem_id = login.mem_id
            WHERE
                cm.mem_voucher_yn = 'Y'
                AND
                login.mll_id IS NULL
                and cm.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                SELECT DISTINCT mem_id
                FROM cmem UNION ALL
                SELECT ".$mem_id." mem_id)
        ";
        $view["v_20day_cnt"] = $this->db->query($sql)->row()->cnt;

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
                and cm.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                SELECT DISTINCT mem_id
                FROM cmem UNION ALL
                SELECT ".$mem_id." mem_id)
        ";
        $view["v_not_send_cnt"] = count($this->db->query($sql)->result());


        $sql = "
        select
        	(select count(1) from cb_wt_send_profile_dhn b left join cb_member a ON b.spf_mem_id = a.mem_id WHERE a.mem_useyn = 'Y' and b.spf_mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
            				UNION ALL
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
            				SELECT DISTINCT mem_id
            				FROM cmem UNION ALL
            				SELECT ".$mem_id." mem_id)) as mem_cnt, /* 총 계약 업체 */
            (SELECT count(*) as cnt from (SELECT * from (SELECT b.spf_mem_id FROM
            				cb_wt_send_profile_dhn b left join cb_member a ON b.spf_mem_id = a.mem_id  WHERE a.mem_useyn = 'Y' and b.spf_mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
            				UNION ALL
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
            				SELECT DISTINCT mem_id
            				FROM cmem UNION ALL
            				SELECT ".$mem_id." mem_id) ) t  WHERE t.spf_mem_id not in (SELECT DISTINCT mst_mem_id FROM cb_wt_msg_sent)) tt ) as mem_cont_cnacel_cnt, /* 미발송 업체 */

            (select count(1) from cb_wt_send_profile_dhn b left join cb_member a ON b.spf_mem_id = a.mem_id WHERE a.mem_useyn = 'Y' and b.spf_mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
            				UNION ALL
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
            				SELECT DISTINCT mem_id
            				FROM cmem UNION ALL
            				SELECT ".$mem_id." mem_id) AND MONTH(spf_datetime) = MONTH(CURRENT_DATE()) AND YEAR(spf_datetime) = YEAR(CURRENT_DATE())) as mem_new_cnt, /* 당월 신규 계약 업체 */

            (SELECT count(*) as cnt from (SELECT * from (SELECT b.spf_mem_id FROM
                            cb_wt_send_profile_dhn b left join cb_member a ON b.spf_mem_id = a.mem_id WHERE a.mem_useyn = 'Y' and b.spf_mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                            UNION ALL
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                            SELECT DISTINCT mem_id
                            FROM cmem UNION ALL
                            SELECT ".$mem_id." mem_id) AND MONTH(spf_datetime) = MONTH(CURRENT_DATE()) AND YEAR(spf_datetime) = YEAR(CURRENT_DATE()) ) t  WHERE t.spf_mem_id not in (SELECT DISTINCT mst_mem_id FROM cb_wt_msg_sent)) tt ) as mem_new_not_cnt /* 당월 신규 미발송 업체 */

		";
		// log_message("ERROR", "Stats.php sql : ".$sql);
		$view['reg_cnt'] = $this->db->query($sql)->row();

		//echo "mst_data : ". $view['mst_data'] ."<br>";
		//echo "mst_data[send_total] : ". $view['mst_data']['send_total'] ."<br>";
		//echo "mst_data[succ] : ". $view['mst_data']['succ'] ."<br>";
		//echo "mst_data[fail] : ". $view['mst_data']['fail'] ."<br>";
		//echo "mst_data[rate] : ". $view['mst_data']['rate'] ."<br>";
		//----------------------------------------------------------------------------------------------------------------

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
            'path' => 'biz',
            'layout' => 'layout',
            'skin' => 'stats',
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

    //Test 메인 통계화면
	public function test(){
        if($this->member->item('mem_level') >= 10) {
            $this_mem_id = $this->member->item('mem_id');
            $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
            if($key) {
                $mem = $this->db->query("select mem_id from cb_member where mem_id=?", array($key))->row();
                if($mem && $mem->mem_id==$key) {
                    $_SESSION["mem_id"] = $key;
                    if(!$this->session->userdata('login_stack')) {
                        $login_stack = array();
                        array_push($login_stack, $this_mem_id);
                        $this->session->set_userdata('login_stack', $login_stack);
                    } else {
                        $login_stack = $this->session->userdata('login_stack');
                        array_push($login_stack, $this_mem_id);
                        $this->session->set_userdata('login_stack', $login_stack);
                    }
                    Header("Location: /");
                    exit;
                }
            }
        }
        $this->load->model("Biz_model");
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

		$mem_id = $this->member->item("mem_id"); //회원번호
		//$view['isManager'] = 'N';
		//$scDate = "2020-05-07"; //조회일자
		//$mem_id = "687"; //회원번호 (우리마트양산시농수산물종합유통센터) : 친구톡 + 2차문자
		//$mem_id = "296"; //회원번호 (남밀양농협하나로마트) : 친구톡 + 2차문자
		//$mem_id = "686"; //회원번호 (인동농협하나로마트 양포점) : 알림톡 + 친구톡이미지
		//$mem_id = "471"; //회원번호 (웅상농협하나로마트) : 일별통계
		//$mem_id = "47"; //회원번호 (프리마트_수택점) : 일별통계
		//$mem_id = "383"; //회원번호 (거창농업협동조합하나로마트) : 일별통계

		//관리자 DHN 으로 들어 왔을경우 "특정 기능 및 항목"을 보이게 하기 위해
		//mem_id => 2 : dhnadmin, 3 : dhn
		if($mem_id == '2' || $mem_id == '3') {
			$view['isManager'] = 'Y';
		} else {
			if($this->session->userdata('login_stack')) {
				$login_stack = $this->session->userdata('login_stack');
				//log_message("ERROR",'Login Stack : '. $login_stack[0]);
				if($login_stack[0] == '2' || $login_stack[0] == '3') {
					$view['isManager'] = 'Y';
				}
			}
		}
		//echo "mem_id : ". $this->member->item('mem_id') .", isManager : ". $view['isManager'] ."<br>";

		$view['param']['isManager'] = $view['isManager'];
		$view['param']['mem_id'] = $mem_id;
		$view['param']['type'] = "D"; //구분(D.일, W.주, M.월)
		$view['param']['sno'] = "0"; //시작시간
		$view['param']['eno'] = "23"; //종료시간
		$view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m-d');
		$view['param']['endDate'] = ($this->input->post('endDate')) ? $this->input->post('endDate') : date('Y-m-d');
		//echo "param : ". $view['param'] ."<br><br>";

		$view['mst_data'] = $this->get_mst_data($view['param']); //발송건수 데이타
		$view['mst_graph'] = $this->get_mst_graph($view['param']); //발송건수 그래프

		//echo "mst_data : ". $view['mst_data'] ."<br>";
		//echo "mst_data[send_total] : ". $view['mst_data']['send_total'] ."<br>";
		//echo "mst_data[succ] : ". $view['mst_data']['succ'] ."<br>";
		//echo "mst_data[fail] : ". $view['mst_data']['fail'] ."<br>";
		//echo "mst_data[rate] : ". $view['mst_data']['rate'] ."<br>";
		//----------------------------------------------------------------------------------------------------------------

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
            'path' => 'biz',
            'layout' => 'layout',
            'skin' => 'stats_test',
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

	//발송건수 데이타
	function get_mst_data($param){
		$where = "";
		if($param['isManager'] != "Y"){
			//$where .= " and mst_mem_id= '". $param['mem_id'] ."' "; //회원번호
		}
		$where .= " and (
		                	 ( mst_reserved_dt <> '00000000000000' AND
                              (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '". $param['startDate'] ." 00:00:00' AND '". $param['endDate'] ." 23:59:59')
                             ) OR
                             ( mst_reserved_dt = '00000000000000' AND
                              (mst_datetime BETWEEN '". $param['startDate'] ." 00:00:00' AND '". $param['endDate'] ." 23:59:59')
                             )
                            )";
		//echo "where : ". $where ."<br><br>";

		$sql = "
			select
					sum(mst_qty) mst_qty,
					sum(reserved_qty) reserved_qty,
					sum(mst_ft) mst_ft,
					sum(mst_ft_img) mst_ft_img,
					sum(mst_ft_wide_img) mst_ft_wide_img,
					sum(mst_at) mst_at,
					sum(mst_sms) mst_sms,
					sum(mst_lms) mst_lms,
					sum(mst_mms) mst_mms,
					sum(mst_grs) mst_grs,
					sum(mst_grs_sms) mst_grs_sms,
					sum(mst_grs_biz_qty) mst_grs_biz_qty,
					sum(mst_nas) mst_nas,
					sum(mst_nas_sms) mst_nas_sms,
					sum(mst_015) mst_015,
					sum(mst_phn) mst_phn,
					sum(mst_grs_mms) mst_grs_mms,
					sum(mst_nas_mms) mst_nas_mms,
					sum(mst_smt) mst_smt,
					sum(mst_smt_sms) mst_smt_sms,
					sum(mst_smt_mms) mst_smt_mms,
					sum(mst_err_at) err_at,
					sum(mst_err_ft) err_ft,
					sum(mst_err_ft_img) err_ft_img,
					sum(mst_err_ft_wide_img) err_ft_wide_img,
					sum(mst_err_sms) qty_sms,
					sum(mst_err_lms) qty_lms,
					sum(mst_err_mms) qty_mms,
					sum(mst_err_grs) qty_grs,
					sum(mst_err_grs_sms) qty_grs_sms,
					sum(mst_err_nas) qty_nas,
					sum(mst_err_nas_sms) qty_nas_sms,
					sum(mst_err_015) qty_015,
					sum(mst_err_phn) qty_phn,
					sum(mst_err_grs_mms) qty_grs_mms,
					sum(mst_err_nas_mms) qty_nas_mms,
					sum(mst_err_smt) qty_smt,
					sum(mst_err_smt_sms) qty_smt_sms,
					sum(mst_err_smt_mms) qty_smt_mms,
					sum(mst_wait) qty_wait,
                    sum(mst_rcs) mst_rcs,
					sum(mst_err_rcs) qty_rcs,
					mem_userid,
					group_concat(mst_id ) as mst_id,
					mst_mem_id,
					(select mrg_recommend_mem_id from cb_member_register where mem_id = mst_mem_id) as parent_mem_id
				from(
					select
                        mst_qty,
                        (case when mst_reserved_dt <> '00000000000000' AND mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') then mst_qty else  0 end) reserved_qty,
                        mst_ft,
                        case when (mst_type1 = 'fti') then mst_ft_img else 0 end as mst_ft_img,
                        case when (mst_type1 = 'ftw') then mst_ft_img else 0 end as mst_ft_wide_img,
                        mst_at,
                        mst_sms,
                        mst_lms,
                        mst_mms,
                        case when (mst_type2 = 'wa') then (mst_grs + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_grs,
                        case when (mst_type2 = 'was') then (mst_grs_sms + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_grs_sms,
                        mst_grs_biz_qty,
                        case when (mst_type2 = 'wb') then (mst_nas + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_nas,
                        case when (mst_type2 = 'wbs') then (mst_nas_sms + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_nas_sms,
                        mst_015,
                        mst_phn,
                        case when (mst_type2 = 'wam') then (mst_grs + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_grs_mms,
                        case when (mst_type2 = 'wbm') then (mst_nas + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_nas_mms,
                        case when (mst_type2 = 'wc' OR mst_type3 = 'wc') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt,
                        case when (mst_type2 = 'wcs' OR mst_type3 = 'wcs') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt_sms,
                        case when (mst_type2 = 'wcm' OR mst_type3 = 'wcm') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt_mms,
                        mst_err_at,
                        mst_err_ft,
                        case when (mst_type1 = 'fti') then mst_err_ft_img else 0 end as mst_err_ft_img,
                        case when (mst_type1 = 'ftw') then mst_err_ft_img else 0 end as mst_err_ft_wide_img,
                        mst_err_sms,
                        mst_err_lms,
                        mst_err_mms,
                        case when (mst_type2 = 'wa') then mst_err_grs else 0 end as mst_err_grs,
                        mst_err_grs_sms,
                        case when (mst_type2 = 'wb') then mst_err_nas else 0 end as mst_err_nas,
                        mst_err_nas_sms,
                        mst_err_015,
                        mst_err_phn,
                        case when (mst_type2 = 'wam') then mst_err_grs else 0 end as mst_err_grs_mms,
                        case when (mst_type2 = 'wbm') then mst_err_nas else 0 end as mst_err_nas_mms,
                        case when (mst_type2 = 'wc' OR mst_type3 = 'wc') then mst_err_smt else 0 end as mst_err_smt,
                        case when (mst_type2 = 'wcs' OR mst_type3 = 'wcs') then mst_err_smt else 0 end as mst_err_smt_sms,
                        case when (mst_type2 = 'wcm' OR mst_type3 = 'wcm') then mst_err_smt else 0 end as mst_err_smt_mms,
                        case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end mst_wait,
                        mst_rcs,
                        mst_err_rcs,
                        mem_userid,
                        mst_id,
                        mst_mem_id
					from cb_wt_msg_sent_v where mst_mem_id in (
										WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
								SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
								FROM cb_member_register cmr
								JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
								WHERE cm.mem_id = '". $param['mem_id'] ."' and cmr.mem_id <> '". $param['mem_id'] ."'
								UNION ALL
								SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
								FROM cb_member_register cmr
								JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
								JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
							)
							SELECT distinct mem_id
							FROM cmem
							UNION ALL
							SELECT ". $param['mem_id'] ." mem_id
					)
					".$where."
				) temp ";
		//echo $sql ."<br>";
		$mst_data = $this->db->query($sql)->row();

		$at_qty =  0; //알림톡 발송수
		$at_succ =  0; //알림톡 발송성공
		$at_fail = 0; //알림톡 발송실패
		$at_rate = ""; //알림톡 성공율

		$ft_qty =  0; //친구톡 발송수
		$ft_succ =  0; //친구톡 발송성공
		$ft_fail = 0; //친구톡 발송실패
		$ft_rate = ""; //친구톡 성공율

		$tx_qty =  0; //문자 발송수
		$tx_succ =  0; //문자 발송성공
		$tx_fail = 0; //문자 발송실패
		$tx_rate = ""; //문자 성공율

        $rc_qty =  0; //RCS 발송수
		$rc_succ =  0; //RCS 발송성공
		$rc_fail = 0; //RCS 발송실패
		$rc_rate = ""; //RCS 성공율

		$view['mst_data']['succ'] =  $mst_data->mst_at
			+$mst_data->mst_ft
			+$mst_data->mst_ft_img
			+$mst_data->mst_ft_wide_img
			+$mst_data->mst_phn
			+$mst_data->mst_sms
			+$mst_data->mst_lms
			+$mst_data->mst_mms
			+$mst_data->mst_015
			+$mst_data->mst_grs
			+$mst_data->mst_nas
			+$mst_data->mst_grs_sms
			+$mst_data->mst_nas_sms
			+$mst_data->mst_grs_mms
			+$mst_data->mst_nas_mms
			+$mst_data->mst_smt
			+$mst_data->mst_smt_sms
			+$mst_data->mst_smt_mms
            +$mst_data->mst_rcs; //전체 발송성공
		$view['mst_data']['fail'] = $mst_data->mst_qty - $view['mst_data']['succ'] - $mst_data->reserved_qty; //전체 발송실패
		if(!empty($mst_data->mst_qty) && !empty($view['mst_data']['succ'])) {
		  $rate = ( ($view['mst_data']['succ'] * 100) / $mst_data->mst_qty); //전체 성공율 = ( (성공수*100) / 전체수)
		} else {
		    $rate = 0;
		}
		$view['mst_data']['rate'] = round($rate, 0); //성공율 소수점 반올림
		$view['mst_data']['reserved_qty'] = $mst_data->reserved_qty; //예약수
		$view['mst_data']['send_total'] = $mst_data->mst_qty; //전체 발송수 (발송수 + 예약수)

		//echo "view['mst_data']['send_total'] : ". $view['mst_data']['send_total'] ."<br>";
		//echo "view['mst_data']['succ'] : ". $view['mst_data']['succ'] ."<br>";
		//echo "view['mst_data']['fail'] : ". $view['mst_data']['fail'] ."<br>";
		//echo "view['mst_data']['rate'] : ". $view['mst_data']['rate'] ."<br>";

		//알림톡
		$at_succ =  $mst_data->mst_at; //알림톡 발송성공
		$at_fail = $mst_data->err_at; //알림톡 발송실패
		$at_qty =  $at_succ + $at_fail; //알림톡 발송수
		if($at_qty > 0){
			$at_rate = round( ($at_succ * 100) / $at_qty) ."%"; //알림톡 성공율 = ( (성공수*100) / 전체수)
		}else{
			$at_rate = "-"; //알림톡 성공율
		}
		$view['mst_data']['at_succ'] = $at_succ; //알림톡 발송성공
		$view['mst_data']['at_fail'] = $at_fail; //알림톡 발송실패
		$view['mst_data']['at_qty'] = $at_qty; //알림톡 발송수
		$view['mst_data']['at_rate'] = $at_rate; //알림톡 성공율

		//친구톡
		$ft_succ =  $mst_data->mst_ft + $mst_data->mst_ft_img + $mst_data->mst_ft_wide_img; //친구톡 발송성공
		$ft_fail = $mst_data->err_ft + $mst_data->err_ft_img + $mst_data->err_ft_wide_img; //친구톡 발송실패
		$ft_qty =  $ft_succ + $ft_fail; //친구톡 발송수
		if($ft_qty > 0){
			$ft_rate = round( ($ft_succ * 100) / $ft_qty) ."%"; //친구톡 성공율 = ( (성공수*100) / 전체수)
		}else{
			$ft_rate = "-"; //친구톡 성공율
		}
		$view['mst_data']['ft_succ'] = $ft_succ; //친구톡 발송성공
		$view['mst_data']['ft_fail'] = $ft_fail; //친구톡 발송실패
		$view['mst_data']['ft_qty'] = $ft_qty; //친구톡 발송수
		$view['mst_data']['ft_rate'] = $ft_rate; //친구톡 성공율

		//문자
		$tx_succ =  $mst_data->mst_grs + $mst_data->mst_grs_sms + $mst_data->mst_grs_mms + $mst_data->mst_nas + $mst_data->mst_nas_sms + $mst_data->mst_nas_mms + $mst_data->mst_smt + $mst_data->mst_smt_sms + $mst_data->mst_smt_mms; //문자 발송성공
		$tx_fail = $mst_data->qty_grs + $mst_data->qty_grs_sms + $mst_data->qty_grs_mms + $mst_data->qty_nas + $mst_data->qty_nas_sms + $mst_data->qty_nas_mms + $mst_data->qty_smt + $mst_data->qty_smt_sms + $mst_data->qty_smt_mms; //문자 발송실패
		$tx_qty =  $tx_succ + $tx_fail; //문자 발송수
		if($tx_qty > 0){
			$tx_rate = round( ($tx_succ * 100) / $tx_qty) ."%"; //문자 성공율 = ( (성공수*100) / 전체수)
		}else{
			$tx_rate = "-"; //문자 성공율
		}
		$view['mst_data']['tx_succ'] = $tx_succ; //문자 발송성공
		$view['mst_data']['tx_fail'] = $tx_fail; //문자 발송실패
		$view['mst_data']['tx_qty'] = $tx_qty; //문자 발송수
		$view['mst_data']['tx_rate'] = $tx_rate; //문자 성공율

        //RCS
		$rc_succ =  $mst_data->mst_rcs; //RCS 발송성공
		$rc_fail = $mst_data->qty_rcs; //RCS 발송실패
		$rc_qty =  $rc_succ + $rc_fail; //RCS 발송수
		if($rc_qty > 0){
			$rc_rate = round( ($rc_succ * 100) / $rc_qty) ."%"; //RCS 성공율 = ( (성공수*100) / 전체수)
		}else{
			$rc_rate = "-"; //RCS 성공율
		}
		$view['mst_data']['rc_succ'] = $rc_succ; //RCS 발송성공
		$view['mst_data']['rc_fail'] = $rc_fail; //RCS 발송실패
		$view['mst_data']['rc_qty'] = $rc_qty; //RCS 발송수
		$view['mst_data']['rc_rate'] = $rc_rate; //RCS 성공율

		return $view['mst_data'];
	}

	//발송건수 그래프
	function get_mst_graph($param){
		$startDate = date('Ymd', strtotime($param['startDate'])); //조회 시작일
		$endDate = date('Ymd', strtotime($param['endDate'])); //조회 종료일
		//echo "scDate : ". $scDate ."<br>";
		$sch = "";
		if($param['isManager'] != "Y"){
			//$sch .= "AND mst_mem_id='". $param['mem_id'] ."' ";
		}

		$sql = "
			SELECT
				  GROUP_CONCAT(seq ORDER BY seq) AS seq,
				  -- GROUP_CONCAT(IFNULL(mst_qty,0) ORDER BY seq) AS a_cnt,
				  GROUP_CONCAT(IFNULL(cnt,0) ORDER BY seq) AS cnt,
				  GROUP_CONCAT(IFNULL(fail_cnt,0) ORDER BY seq) AS failcnt
			FROM seq_". $param['sno'] ."_to_". $param['eno'] ."  t
			LEFT JOIN (
				SELECT ";
		if($param['type'] == 'D') { //일
			$sql = $sql ."DATE_FORMAT(mst_datetime, '%k') AS h";
		}else{
			$sql = $sql ."DATE_FORMAT(mst_datetime, '%d') AS h";
		}
		$sql = $sql ."
					, sum(mst_qty) as mst_qty
					, SUM(sucess_cnt) AS cnt, SUM(fail_cnt) as fail_cnt
				FROM (
					SELECT
						mst_qty,
						(mst_ft + mst_ft_img + mst_at + mst_grs + mst_grs_sms + mst_nas + mst_nas_sms + mst_smt + mst_wait) as sucess_cnt,
						-- (mst_err_ft + mst_err_ft_img + mst_err_grs + mst_err_grs_sms + mst_err_nas + mst_err_nas_sms + mst_err_smt) as fail_cnt,
						(mst_err_at + mst_err_ft + mst_err_ft_img + mst_err_grs + mst_err_grs_sms + mst_err_sms+ mst_err_lms+ mst_err_mms + mst_err_nas + mst_err_nas_sms + mst_err_smt)  as fail_cnt,
						(CASE WHEN mst_reserved_dt <> '00000000000000' THEN mst_reserved_dt ELSE mst_datetime END) AS mst_datetime
					FROM cb_wt_msg_sent_v
					WHERE mst_mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
						SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
						FROM cb_member_register cmr
						JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
						WHERE cm.mem_id = '". $param['mem_id'] ."' AND cmr.mem_id <> '". $param['mem_id'] ."'
						UNION ALL
						SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
						FROM cb_member_register cmr
						JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
						JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
						SELECT DISTINCT mem_id
						FROM cmem UNION ALL
						SELECT ". $param['mem_id'] ." mem_id)
					AND (
						(mst_reserved_dt <> '00000000000000' AND mst_reserved_dt BETWEEN '". $startDate ."000000' AND '". $endDate ."235959')
						OR ";
		if($param['type'] == 'D') { //일
			$sql = $sql ."(mst_reserved_dt = '00000000000000' AND date_format(mst_datetime,'%Y%m%d') = '". $startDate ."')";
		}else{
			$sql = $sql ."(mst_reserved_dt = '00000000000000' AND date_format(mst_datetime,'%Y%m%d') BETWEEN '". $startDate ."' AND '". $endDate ."')";
		}
		$sql = $sql ."
					)
                    AND (CASE WHEN mst_reserved_dt <> '00000000000000' THEN DATE_FORMAT(mst_reserved_dt, '%Y%m%d%H%i%S') ELSE mst_datetime END) < DATE_FORMAT(now(),'%Y%m%d%H%i%S')
					". $sch ."
				) t ";
		if($param['type'] == 'D') { //일
			$sql = $sql ."GROUP BY DATE_FORMAT(mst_datetime, '%Y-%m-%d %H')";
		}else{
			$sql = $sql ."GROUP BY DATE_FORMAT(mst_datetime, '%Y-%m-%d')";
		}
		$sql = $sql ."
			) h ON t.seq = h.h";
		//echo $sql ."<br><br>";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > param['type'] : ". $param['type'] .", sql : ". $sql);
		$mst_cnt = $this->db->query($sql)->row();
		$view['mst_graph']['seq'] = $mst_cnt->seq; //기간
		$view['mst_graph']['cnt'] = $mst_cnt->cnt; //기간별 건수
		$view['mst_graph']['failcnt'] = $mst_cnt->failcnt;
		//echo "view['mst_data']['seq'] : ". $view['mst_data']['seq'] ."<br>";
		//echo "view['mst_data']['cnt'] : ". $view['mst_data']['cnt'] ."<br>";
		//----------------------------------------------------------------------------------------------------------------

		return $view['mst_graph'];
	}

	//발송건수 실시간 조회
	public function ajax_mst_cnt() {
		//log_message("ERROR", "/biz/stats/ajax_mst_cnt");

		$type = $this->input->post('type');
		//log_message("ERROR", "/biz/stats/ajax_mst_cnt > type : ". $type);

		$result = array();

		$mem_id = $this->member->item("mem_id"); //회원번호
		//$view['isManager'] = 'N';
		//$scDate = "2020-05-07"; //조회일자
		//$mem_id = "687"; //회원번호 (우리마트양산시농수산물종합유통센터) : 친구톡 + 2차문자
		//$mem_id = "296"; //회원번호 (남밀양농협하나로마트) : 친구톡 + 2차문자
		//$mem_id = "686"; //회원번호 (인동농협하나로마트 양포점) : 알림톡 + 친구톡이미지
		//$mem_id = "471"; //회원번호 (웅상농협하나로마트) : 일별통계
		//$mem_id = "47"; //회원번호 (프리마트_수택점) : 일별통계
		//$mem_id = "383"; //회원번호 (거창농업협동조합하나로마트) : 일별통계

		//echo "<script>alert('aaaa');</script>";

		//관리자 DHN 으로 들어 왔을경우 "특정 기능 및 항목"을 보이게 하기 위해
		//mem_id => 2 : dhnadmin, 3 : dhn
		if($mem_id == '2' || $mem_id == '3') {
			$view['isManager'] = 'Y';
		} else {
			if($this->session->userdata('login_stack')) {
				$login_stack = $this->session->userdata('login_stack');
				//log_message("ERROR",'Login Stack : '. $login_stack[0]);
				if($login_stack[0] == '2' || $login_stack[0] == '3') {
					$view['isManager'] = 'Y';
				}
			}
		}
		//echo "mem_id : ". $this->member->item('mem_id') .", isManager : ". $view['isManager'] ."<br>";

		$view['param']['isManager'] = $view['isManager'];
		$view['param']['mem_id'] = $mem_id;

		$year = date('Y');
		$month = date('n');
		$day = date('j');

		if($type == 'D') { //일

			$view['param']['sno'] = "0"; //시작시간
			$view['param']['eno'] = "23"; //종료시간
			$view['param']['type'] = $type; //구분(D.일, W.주, M.월)
			$view['param']['startDate'] = date('Y-m-d'); //검색 시작일자
			$view['param']['endDate'] = date('Y-m-d'); //검색 종료일자
			//echo "param : ". $view['param'] ."<br><br>";
			$view['mst_data'] = $this->get_mst_data($view['param']); //발송건수 데이타
			$view['mst_graph'] = $this->get_mst_graph($view['param']); //발송건수 그래프
			if(!empty($view['mst_graph'])) {
				$result['x'] = $view['mst_graph']['seq'];
				$result['v'] = $view['mst_graph']['cnt'];
				$result['fv'] = $view['mst_graph']['failcnt'];
				$result['syy'] = $year;
				$result['smm'] = $month;
				$result['sdd'] = $day;
				$result['eyy'] = "";
				$result['emm'] = "";
				$result['edd'] = "";
			}

		} else if($type == 'W') { //주

			$prev6day = mktime( 0, 0, 0, $month, $day-6, $year );
			$pred = date("j", $prev6day);

			$view['param']['sno'] = date("j", $prev6day); //시작일
			$view['param']['eno'] = $day; //종료일
			$view['param']['startDate'] = date("Y-m-d", strtotime(date('Y-m-d') ." -6 day")); //6일전
			$view['param']['endDate'] = date('Y-m-d');
			//echo "param : ". $view['param'] ."<br><br>";
			$view['mst_data'] = $this->get_mst_data($view['param']); //발송건수 데이타

			if($day < $pred) { //오늘날짜 보다 6일전 날짜가 큰 경우

				//오늘 -6일전 부터 ~ 말일까지
				$me = date("t", mktime( 0, 0, 0, $month, $day-6, $year ));
				$view['param']['sno'] = $pred; //시작일
				$view['param']['eno'] = date("t", mktime( 0, 0, 0, $month, $day-6, $year )); //종료일
				$view['param']['startDate'] = date("Y-m-d", strtotime(date('Y-m-d') ." -6 day")); //6일전
				$view['param']['endDate'] = date("Y-m-d", mktime( 0, 0, 0, date("m", $prev6day), $me, date("Y", $prev6day) )); //이전달 마지말 일자
				//echo "param : ". $view['param'] ."<br><br>";
				$view['mst_graph'] = $this->get_mst_graph($view['param']); //발송건수 그래프
				if(!empty($view['mst_graph'])) {
					$result['x'] = $view['mst_graph']['seq'];
					$result['v'] = $view['mst_graph']['cnt'];
					$result['fv'] = $view['mst_graph']['failcnt'];
				}

				//현재달 1일부터 오늘까지
				$view['param']['sno'] = "1"; //시작일
				$view['param']['eno'] = $day; //종료일
				$view['param']['startDate'] = date("Y-m-d", mktime( 0, 0, 0, $month, 1, $year )); //현재달 1일
				$view['param']['endDate'] = date('Y-m-d'); //오늘일짜
				//echo "param : ". $view['param'] ."<br><br>";
				$view['mst_graph'] = $this->get_mst_graph($view['param']); //발송건수 그래프
				if(!empty($view['mst_graph'])) {
					$result['x'] = $result['x'].','. $view['mst_graph']['seq'];
					$result['v'] = $result['v'].','. $view['mst_graph']['cnt'];
					$result['fv'] = $result['fv'].','. $view['mst_graph']['failcnt'];
					$result['syy'] = date("Y", $prev6day);
					$result['smm'] = date("n", $prev6day);
					$result['sdd'] = date("j", $prev6day);
					$result['eyy'] = $year;
					$result['emm'] = $month;
					$result['edd'] = $day;
				}

			}else{

				$view['param']['sno'] = date("j", $prev6day); //시작일
				$view['param']['eno'] = $day; //종료일
				$view['param']['startDate'] = date("Y-m-d", strtotime(date('Y-m-d') ." -6 day")); //6일전
				$view['param']['endDate'] = date('Y-m-d');
				//echo "param : ". $view['param'] ."<br><br>";
				$view['mst_graph'] = $this->get_mst_graph($view['param']); //발송건수 그래프
				if(!empty($view['mst_graph'])) {
					$result['x'] = $view['mst_graph']['seq'];
					$result['v'] = $view['mst_graph']['cnt'];
					$result['fv'] = $view['mst_graph']['failcnt'];
					$result['syy'] = date("Y", $prev6day);
					$result['smm'] = date("n", $prev6day);
					$result['sdd'] = date("j", $prev6day);
					$result['eyy'] = $year;
					$result['emm'] = $month;
					$result['edd'] = $day;
				}

			}

	    } else if($type == 'M') { //월

			$day = "1";
			$eno = date("t", mktime( 0, 0, 0, $month, $day, $year ));
	        $view['param']['sno'] = $day; //시작일
			$view['param']['eno'] = $eno; //종료일
			$view['param']['startDate'] = date("Y-m-d", mktime( 0, 0, 0, $month, $day, $year )); //1일
			$view['param']['endDate'] = date("Y-m-d", mktime( 0, 0, 0, $month, $eno, $year )); //마지막일
			//echo "param : ". $view['param'] ."<br><br>";
			$view['mst_data'] = $this->get_mst_data($view['param']); //발송건수 데이타
			$view['mst_graph'] = $this->get_mst_graph($view['param']); //발송건수 그래프
			if(!empty($view['mst_graph'])) {
				$result['x'] = $view['mst_graph']['seq'];
				$result['v'] = $view['mst_graph']['cnt'];
				$result['fv'] = $view['mst_graph']['failcnt'];
				//log_message("ERROR", "/biz/stats/ajax_mst_cnt > view['mst_graph']['fail_cnt'] : ". $view['mst_graph']['fail_cnt']);
				$result['syy'] = $year;
				$result['smm'] = $month;
				$result['sdd'] = "";
				$result['eyy'] = "";
				$result['emm'] = "";
				$result['edd'] = "";
			}

	    }

		//log_message("ERROR", "/biz/stats/ajax_mst_cnt > period : ". $result['period']);
		//log_message("ERROR", "/biz/stats/ajax_mst_cnt > result : ". $result);
		//log_message("ERROR", "/biz/stats/ajax_mst_cnt > result['x'] : ". $result['x'] .", result['v'] : ". $result['v']);

	    //$json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    //header('Content-Type: application/json');
	    //echo $json;

		header('Content-Type: application/json');
		echo '{"x": "'. $result['x'] .'", "v": "'. $result['v'] .'", "f_v": "'. $result['fv'] .'", "syy": "'. $result['syy'] .'", "smm": "'. $result['smm'] .'", "sdd": "'. $result['sdd'] .'", "eyy": "'. $result['eyy'] .'", "emm": "'. $result['emm'] .'", "edd": "'. $result['edd'] .'", "send_total": "'. $view['mst_data']['send_total'] .'", "reserved_qty": "'. $view['mst_data']['reserved_qty'] .'", "succ": "'. $view['mst_data']['succ'] .'", "fail": "'. $view['mst_data']['fail'] .'", "rate": "'. $view['mst_data']['rate'] .'", "at_succ": "'. $view['mst_data']['at_succ'] .'", "at_fail": "'. $view['mst_data']['at_fail'] .'", "at_qty": "'. $view['mst_data']['at_qty'] .'", "at_rate": "'. $view['mst_data']['at_rate'] .'", "ft_succ": "'. $view['mst_data']['ft_succ'] .'", "ft_fail": "'. $view['mst_data']['ft_fail'] .'", "ft_qty": "'. $view['mst_data']['ft_qty'] .'", "ft_rate": "'. $view['mst_data']['ft_rate'] .'", "tx_succ": "'. $view['mst_data']['tx_succ'] .'", "tx_fail": "'. $view['mst_data']['tx_fail'] .'", "tx_qty": "'. $view['mst_data']['tx_qty'] .'", "tx_rate": "'. $view['mst_data']['tx_rate'] .'", "rc_succ": "'. $view['mst_data']['rc_succ'] .'", "rc_fail": "'. $view['mst_data']['rc_fail'] .'", "rc_qty": "'. $view['mst_data']['rc_qty'] .'", "rc_rate": "'. $view['mst_data']['rc_rate'] .'"}';

	}

	function get_mst_3m_graph($param) {
	    //$startDate = date('Ymd', strtotime($param['startDate'])); //조회 시작일
	    //$endDate = date('Ymd', strtotime($param['endDate'])); //조회 종료일
	    $startDate = $param['startDate'];
	    $endDate = $param['endDate'];

	    //echo "scDate : ". $scDate ."<br>";
	    $sch = "";
	    if($param['isManager'] != "Y"){
	        //$sch .= "AND mst_mem_id='". $param['mem_id'] ."' ";
	    }

	    $sql = "
            SELECT datelist.*,
            	-- case when (datelist.dt <= DATE_FORMAT(now(),'%Y-%m-%d')) then IFNULL(datecount.cnt,0) else 'null' end as cnt,
            	case when (datelist.dt <= DATE_FORMAT(now(),'%Y-%m-%d')) then IFNULL(datecount.sucesscnt,0) else 'null' end as cnt
            FROM (
            	WITH RECURSIVE t as (
            	    select '".$startDate."' as dt
            	  UNION
            	    SELECT DATE_ADD(t.dt, INTERVAL 1 DAY) FROM t WHERE DATE_ADD(t.dt, INTERVAL 1 DAY) <= '".$endDate."'
            	)
            	SELECT dt, DATE_FORMAT(dt, '%Y-%m') dtym, DATE_FORMAT(dt, '%Y') dty, DATE_FORMAT(dt, '%c') dtm, DATE_FORMAT(dt, '%e') dtd  FROM t) as datelist
            	LEFT JOIN (
            		SELECT DATE_FORMAT(mst_datetime, '%Y-%m-%d') AS send_date,
            			SUM(mst_qty) AS cnt ,
            			-- SUM(mst_ft) as ftcnt,
            			-- SUM(mst_ft_img) as fticnt,
            			-- SUM(mst_ft_wide_img) as ftiwcnt,
            			-- SUM(mst_at) as atcnt,
            			-- SUM(mst_grs_sms) as wascnt,
            			-- SUM(mst_grs) as wacnt,
            			-- SUM(mst_grs_biz_qty) as wabizcnt,
            			-- SUM(mst_grs_mms) as wamcnt,
            			-- SUM(mst_nas_sms) as wbscnt,
            			-- SUM(mst_nas) as wbcnt,
            			-- SUM(mst_nas_mms) as wbmcnt,
            			-- SUM(mst_smt_sms) as wcscnt,
            			-- SUM(mst_smt) as wccnt,
            			-- SUM(mst_smt_mms) as wcmcnt,
            			SUM(mst_ft + mst_ft_img + mst_ft_wide_img + mst_at + mst_grs_sms + mst_grs + mst_grs_mms + mst_nas_sms + mst_nas + mst_smt_sms + mst_smt + mst_smt_mms + mst_rcs) as sucesscnt
            		FROM (
            			SELECT
            				(CASE WHEN mst_reserved_dt <> '00000000000000' THEN mst_reserved_dt ELSE mst_datetime END) AS mst_datetime,
            				mst_qty,
            				mst_ft,
            				case when (mst_type1 = 'fti') then mst_ft_img else 0 end as mst_ft_img,
            				case when (mst_type1 = 'ftw') then mst_ft_img else 0 end as mst_ft_wide_img,
            				mst_at,
                            case when (mst_rcs > 0) then mst_rcs else 0 end as mst_rcs,
            				case when (mst_type2 = 'wa') then (mst_grs + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_grs,
            				case when (mst_type2 = 'was') then (mst_grs_sms + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_grs_sms,
                            mst_grs_biz_qty,
            				case when (mst_type2 = 'wb') then (mst_nas + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_nas,
            				case when (mst_type2 = 'wbs') then (mst_nas_sms + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_nas_sms,
            				case when (mst_type2 = 'wam') then (mst_grs + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_grs_mms,
            				case when (mst_type2 = 'wbm') then (mst_nas + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_nas_mms,
            				case when (mst_type2 = 'wc' OR mst_type3 = 'wc') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt,
            				case when (mst_type2 = 'wcs' OR mst_type3 = 'wcs') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt_sms,
            				case when (mst_type2 = 'wcm' OR mst_type3 = 'wcm') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt_mms
            			FROM cb_wt_msg_sent_v
            			WHERE mst_mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				WHERE cm.mem_id = '". $param['mem_id'] ."' AND cmr.mem_id <> '". $param['mem_id'] ."'
            				UNION ALL
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
            				SELECT DISTINCT mem_id
            				FROM cmem UNION ALL
            				SELECT ". $param['mem_id'] ." mem_id)
            			AND (
            				(mst_reserved_dt <> '00000000000000' AND mst_reserved_dt BETWEEN '". str_replace('-','', $startDate) ."000000' AND '". str_replace('-','', $endDate) ."235959')
            				OR (mst_reserved_dt = '00000000000000' AND date_format(mst_datetime,'%Y%m%d') BETWEEN '". str_replace('-','', $startDate) ."' AND '". str_replace('-','', $endDate) ."')
            			)
            			AND (CASE WHEN mst_reserved_dt <> '00000000000000' THEN DATE_FORMAT(mst_reserved_dt, '%Y%m%d%H%i%S') ELSE mst_datetime END) < DATE_FORMAT(now(),'%Y%m%d%H%i%S')

            		) t GROUP BY DATE_FORMAT(mst_datetime, '%Y-%m-%d')
            	) datecount ON datelist.dt = datecount.send_date
        ";

	    //echo $sql ."<br><br>";
	    //log_message("ERROR", "/biz/stats/ajax_mst_sql > param['type'] : ". $param['type']);
	    //log_message("ERROR", "/biz/stats/ajax_mst_sql > sql : ". $sql);
	    //$mst_cnt = $this->db->query($sql)->row();
	    $mst_cnt_list = $this->db->query($sql)->result();
	    //$view['mst_graph']['seq'] = $mst_cnt->seq; //기간
	    //$view['mst_graph']['cnt'] = $mst_cnt->cnt; //기간별 건수
	    //echo "view['mst_data']['seq'] : ". $view['mst_data']['seq'] ."<br>";
	    //echo "view['mst_data']['cnt'] : ". $view['mst_data']['cnt'] ."<br>";
	    //----------------------------------------------------------------------------------------------------------------

	    //return $view['mst_graph'];
	    return $mst_cnt_list;
	}

	//발송건수 실시간 조회
	public function ajax_mst_3m_cnt() {
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt");
	    date_default_timezone_set('Asia/Seoul');

	    $type = $this->input->post('type');
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt > type : ". $type);

	    $result = array();

	    $mem_id = $this->member->item("mem_id"); //회원번호
	    //$view['isManager'] = 'N';
	    //$scDate = "2020-05-07"; //조회일자
	    //$mem_id = "687"; //회원번호 (우리마트양산시농수산물종합유통센터) : 친구톡 + 2차문자
	    //$mem_id = "296"; //회원번호 (남밀양농협하나로마트) : 친구톡 + 2차문자
	    //$mem_id = "686"; //회원번호 (인동농협하나로마트 양포점) : 알림톡 + 친구톡이미지
	    //$mem_id = "471"; //회원번호 (웅상농협하나로마트) : 일별통계
	    //$mem_id = "47"; //회원번호 (프리마트_수택점) : 일별통계
	    //$mem_id = "383"; //회원번호 (거창농업협동조합하나로마트) : 일별통계

	    //echo "<script>alert('aaaa');</script>";

	    //관리자 DHN 으로 들어 왔을경우 "특정 기능 및 항목"을 보이게 하기 위해
	    //mem_id => 2 : dhnadmin, 3 : dhn
	    if($mem_id == '2' || $mem_id == '3') {
	        $view['isManager'] = 'Y';
	    } else {
	        if($this->session->userdata('login_stack')) {
	            $login_stack = $this->session->userdata('login_stack');
	            //log_message("ERROR",'Login Stack : '. $login_stack[0]);
	            if($login_stack[0] == '2' || $login_stack[0] == '3') {
	                $view['isManager'] = 'Y';
	            }
	        }
	    }
	    //echo "mem_id : ". $this->member->item('mem_id') .", isManager : ". $view['isManager'] ."<br>";

	    $view['param']['isManager'] = $view['isManager'];
	    $view['param']['mem_id'] = $mem_id;



	    $startYear = date('Y', strtotime("-2 months"));
	    $startMonth = date('m', strtotime("-2 months"));
	    $endYear = date('Y');
	    $endMonth = date('m');

	    $view['param']['sno'] = 1;
	    $view['param']['eno'] = 31; //종료일
	    $view['param']['smonth'] = date('n', strtotime("-2 months"));
	    $view['param']['emonth'] = date('n');
	    $view['param']['startDate'] = $startYear."-".$startMonth."-01"; //6일전
	    $view['param']['endDate'] = $endYear."-".$endMonth."-31";
	    //echo "param : ". $view['param'] ."<br><br>";
	    //$view['mst_data'] = $this->get_mst_data($view['param']); //발송건수 데이타
	    //$view['mst_graph'] = $this->get_mst_3m_graph($view['param']); //발송건수 그래프
	    $resultList = $this->get_mst_3m_graph($view['param']); //발송건수 그래프
	    //if(!empty($view['mst_graph'])) {
	    if(!empty($resultList)) {
	        $series['name'] = "";
	        $series['value'] = "";

	        $sName = "";
	        $sValue = "";
	        $totalCnt = 0;
	        $cDay = 0;
	        $cMonth = -1;
	        foreach($resultList as $r) {
	            if($sName != $r->dtym) {
	                $cMonth++;
	                $totalCnt = 0;
	                $sName = $r->dtym;
	                $series['name'][$cMonth] = str_replace('-','년',$r->dtym)."월";
	                $cDay = date('t', strtotime($r->dtym."-01"));
	                //$sValue = "[";
	                $sValue= "";
	            }

	            if($r->dtd < $cDay) {
	                if($r->dtd > 1) {
	                    $sValue = $sValue.", ";
	                }

	                if($r->dtd == 1 && $r->cnt == 'null') {
	                    $totalCnt = $totalCnt + 0;
                        $sValue = $sValue."0";
	                } else {
	                    if ($r->cnt != 'null') {
                            $totalCnt = $totalCnt + (int)$r->cnt;
	                    }
                        if($type == '3M') {
                            $sValue = $sValue.$r->cnt;
                        } else if ($type == '3MACC') {
                            if ($r->cnt != 'null') {
                                $sValue = $sValue.$totalCnt;
                            } else {
                                $sValue = $sValue.$r->cnt;
                            }
                        }
	                }

	            } else if ($r->dtd == $cDay){
	                if ($r->cnt != 'null') {
	                   $totalCnt = $totalCnt + (int)$r->cnt;
	                }
	                $sValue = $sValue.", ";
	                if($type == '3M') {
	                    $sValue = $sValue.$r->cnt;
	                } else if ($type == '3MACC') {
	                    if ($r->cnt != 'null') {
	                        $sValue = $sValue.$totalCnt;
	                    } else {
	                        $sValue = $sValue.$r->cnt;
	                    }
	                }
	                //$sValue = $sValue.", ".$r->cnt;
	                if ($cDay == 28) {
	                    $sValue = $sValue.", null, null, null";
	                } else if ($cDay == 29) {
	                    $sValue = $sValue.", null, null";
	                } else if ($cDay == 30) {
	                    $sValue = $sValue.", null";
	                }
	                //$sValue = $sValue."]";
	                $series['value'][$cMonth] = $sValue;
	            }
	        }

	        $seriesValue = "";
	        for($i=0; $i<count($series['value']); $i++) {
	            if ($i > 0) {
	                $seriesValue = $seriesValue.", ";
	            }
	            $seriesValue = $seriesValue."{";
	            $seriesValue = $seriesValue."showInLegend: false, ";
	            $seriesValue = $seriesValue."name: '".$series['name'][$i]."', ";
	            $seriesValue = $seriesValue."data: ".$series['value'][$i]." ";
	            $seriesValue = $seriesValue."}";
	        }

	        //$result['x'] = $view['mst_graph']['seq'];
	        $result['x'] = "1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31";
	        //$result['v'] = $view['mst_graph']['cnt'];
	        $result['v'] = "[".$seriesValue."]";
	        $result['syy'] = $startYear;
	        $result['smm'] = $view['param']['smonth'];
	        $result['sdd'] = "";
	        $result['eyy'] = $endYear;
	        $result['emm'] = $view['param']['emonth'];
	        $result['edd'] = "";

	    }
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt > seriesValue : ". $seriesValue);
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt > period : ". $result['period']);
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt > result : ". $result);
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt > result['x'] : ". $result['x'] .", result['v'] : ". $result['v']);

	    //$json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    //header('Content-Type: application/json');
	    //echo $json;

	    header('Content-Type: application/json');
	    echo '{"x": "'. $result['x'] .'", "n1": "'. $series['name'][0] .'", "v1": "'. $series['value'][0] .'", "n2": "'. $series['name'][1] .'", "v2": "'. $series['value'][1] .'", "n3": "'. $series['name'][2] .'", "v3": "'. $series['value'][2] .'", "syy": "'. $result['syy'] .'", "smm": "'. $result['smm'] .'", "sdd": "'. $result['sdd'] .'", "eyy": "'. $result['eyy'] .'", "emm": "'. $result['emm'] .'", "edd": "'. $result['edd'] .'", "send_total": "'. $view['mst_data']['send_total'] .'", "reserved_qty": "'. $view['mst_data']['reserved_qty'] .'", "succ": "'. $view['mst_data']['succ'] .'", "fail": "'. $view['mst_data']['fail'] .'", "rate": "'. $view['mst_data']['rate'] .'", "at_succ": "'. $view['mst_data']['at_succ'] .'", "at_fail": "'. $view['mst_data']['at_fail'] .'", "at_qty": "'. $view['mst_data']['at_qty'] .'", "at_rate": "'. $view['mst_data']['at_rate'] .'", "ft_succ": "'. $view['mst_data']['ft_succ'] .'", "ft_fail": "'. $view['mst_data']['ft_fail'] .'", "ft_qty": "'. $view['mst_data']['ft_qty'] .'", "ft_rate": "'. $view['mst_data']['ft_rate'] .'", "tx_succ": "'. $view['mst_data']['tx_succ'] .'", "tx_fail": "'. $view['mst_data']['tx_fail'] .'", "tx_qty": "'. $view['mst_data']['tx_qty'] .'", "tx_rate": "'. $view['mst_data']['tx_rate'] .'"}';

	}

    function get_mst_3m_graph2($param) {
	    //$startDate = date('Ymd', strtotime($param['startDate'])); //조회 시작일
	    //$endDate = date('Ymd', strtotime($param['endDate'])); //조회 종료일
	    $startDate = $param['startDate'];
	    $endDate = $param['endDate'];

	    //echo "scDate : ". $scDate ."<br>";
	    $sch = "";
	    if($param['isManager'] != "Y"){
	        //$sch .= "AND mst_mem_id='". $param['mem_id'] ."' ";
	    }

	    $sql = "
            SELECT datelist.*,
            	case when (datelist.dt <= DATE_FORMAT(now(),'%Y-%m-%d')) then IFNULL(datecount.cnt,0) else 'null' end as cnt
            FROM (
            	WITH RECURSIVE t as (
            	    select '".$startDate."' as dt
            	  UNION
            	    SELECT DATE_ADD(t.dt, INTERVAL 1 DAY) FROM t WHERE DATE_ADD(t.dt, INTERVAL 1 DAY) <= '".$endDate."'
            	)
            	SELECT dt, DATE_FORMAT(dt, '%Y-%m') dtym, DATE_FORMAT(dt, '%Y') dty, DATE_FORMAT(dt, '%c') dtm, DATE_FORMAT(dt, '%e') dtd  FROM t) as datelist
            	LEFT JOIN (
                    SELECT DATE_FORMAT(spf_datetime, '%Y-%m-%d') AS send_date,
            			SUM(qty) AS cnt
            		FROM (
            			SELECT
            				spf_datetime,
            				count(*) as qty
            			FROM cb_wt_send_profile_dhn
            			WHERE spf_mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				WHERE cm.mem_id = '". $param['mem_id'] ."' AND cmr.mem_id <> '". $param['mem_id'] ."'
            				UNION ALL
            				SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            				FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
            				SELECT DISTINCT mem_id
            				FROM cmem UNION ALL
            				SELECT ". $param['mem_id'] ." mem_id)

            			AND DATE_FORMAT(spf_datetime, '%Y%m%d%H%i%S') < DATE_FORMAT(now(),'%Y%m%d%H%i%S') group by DATE_FORMAT(spf_datetime, '%Y-%m-%d')

            		) t GROUP BY DATE_FORMAT(spf_datetime, '%Y-%m-%d')
            	) datecount ON datelist.dt = datecount.send_date
        ";

	    //echo $sql ."<br><br>";
	    //log_message("ERROR", "/biz/stats/ajax_mst_sql > param['type'] : ". $param['type']);
	    // log_message("ERROR", "/biz/stats/ajax_mst_sql > sql : ". $sql);
	    //$mst_cnt = $this->db->query($sql)->row();
	    $mst_cnt_list = $this->db->query($sql)->result();
	    //$view['mst_graph']['seq'] = $mst_cnt->seq; //기간
	    //$view['mst_graph']['cnt'] = $mst_cnt->cnt; //기간별 건수
	    //echo "view['mst_data']['seq'] : ". $view['mst_data']['seq'] ."<br>";
	    //echo "view['mst_data']['cnt'] : ". $view['mst_data']['cnt'] ."<br>";
	    //----------------------------------------------------------------------------------------------------------------

	    //return $view['mst_graph'];
	    return $mst_cnt_list;
	}

    //계약건수 실시간 조회
	public function ajax_mst_3m_cnt2() {
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt");
	    date_default_timezone_set('Asia/Seoul');

	    $type = $this->input->post('type');
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt > type : ". $type);

	    $result = array();

	    $mem_id = $this->member->item("mem_id"); //회원번호
	    //$view['isManager'] = 'N';
	    //$scDate = "2020-05-07"; //조회일자
	    //$mem_id = "687"; //회원번호 (우리마트양산시농수산물종합유통센터) : 친구톡 + 2차문자
	    //$mem_id = "296"; //회원번호 (남밀양농협하나로마트) : 친구톡 + 2차문자
	    //$mem_id = "686"; //회원번호 (인동농협하나로마트 양포점) : 알림톡 + 친구톡이미지
	    //$mem_id = "471"; //회원번호 (웅상농협하나로마트) : 일별통계
	    //$mem_id = "47"; //회원번호 (프리마트_수택점) : 일별통계
	    //$mem_id = "383"; //회원번호 (거창농업협동조합하나로마트) : 일별통계

	    //echo "<script>alert('aaaa');</script>";

	    //관리자 DHN 으로 들어 왔을경우 "특정 기능 및 항목"을 보이게 하기 위해
	    //mem_id => 2 : dhnadmin, 3 : dhn
	    if($mem_id == '2' || $mem_id == '3') {
	        $view['isManager'] = 'Y';
	    } else {
	        if($this->session->userdata('login_stack')) {
	            $login_stack = $this->session->userdata('login_stack');
	            //log_message("ERROR",'Login Stack : '. $login_stack[0]);
	            if($login_stack[0] == '2' || $login_stack[0] == '3') {
	                $view['isManager'] = 'Y';
	            }
	        }
	    }
	    //echo "mem_id : ". $this->member->item('mem_id') .", isManager : ". $view['isManager'] ."<br>";

	    $view['param']['isManager'] = $view['isManager'];
	    $view['param']['mem_id'] = $mem_id;



	    $startYear = date('Y', strtotime("-2 months"));
	    $startMonth = date('m', strtotime("-2 months"));
	    $endYear = date('Y');
	    $endMonth = date('m');

	    $view['param']['sno'] = 1;
	    $view['param']['eno'] = 31; //종료일
	    $view['param']['smonth'] = date('n', strtotime("-2 months"));
	    $view['param']['emonth'] = date('n');
	    $view['param']['startDate'] = $startYear."-".$startMonth."-01"; //6일전
	    $view['param']['endDate'] = $endYear."-".$endMonth."-31";
	    //echo "param : ". $view['param'] ."<br><br>";
	    //$view['mst_data'] = $this->get_mst_data($view['param']); //발송건수 데이타
	    //$view['mst_graph'] = $this->get_mst_3m_graph($view['param']); //발송건수 그래프
	    $resultList = $this->get_mst_3m_graph2($view['param']); //발송건수 그래프
	    //if(!empty($view['mst_graph'])) {
	    if(!empty($resultList)) {
	        $series['name'] = "";
	        $series['value'] = "";

	        $sName = "";
	        $sValue = "";
	        $totalCnt = 0;
	        $cDay = 0;
	        $cMonth = -1;
	        foreach($resultList as $r) {
	            if($sName != $r->dtym) {
	                $cMonth++;
	                $totalCnt = 0;
	                $sName = $r->dtym;
	                $series['name'][$cMonth] = str_replace('-','년',$r->dtym)."월";
	                $cDay = date('t', strtotime($r->dtym."-01"));
	                //$sValue = "[";
	                $sValue= "";
	            }

	            if($r->dtd < $cDay) {
	                if($r->dtd > 1) {
	                    $sValue = $sValue.", ";
	                }

	                if($r->dtd == 1 && $r->cnt == 'null') {
	                    $totalCnt = $totalCnt + 0;
                        $sValue = $sValue."0";
	                } else {
	                    if ($r->cnt != 'null') {
                            $totalCnt = $totalCnt + (int)$r->cnt;
	                    }
                        if($type == '3M') {
                            $sValue = $sValue.$r->cnt;
                        } else if ($type == '3MACC2') {
                            if ($r->cnt != 'null') {
                                $sValue = $sValue.$totalCnt;
                            } else {
                                $sValue = $sValue.$r->cnt;
                            }
                        }
	                }

	            } else if ($r->dtd == $cDay){
	                if ($r->cnt != 'null') {
	                   $totalCnt = $totalCnt + (int)$r->cnt;
	                }
	                $sValue = $sValue.", ";
	                if($type == '3M') {
	                    $sValue = $sValue.$r->cnt;
	                } else if ($type == '3MACC2') {
	                    if ($r->cnt != 'null') {
	                        $sValue = $sValue.$totalCnt;
	                    } else {
	                        $sValue = $sValue.$r->cnt;
	                    }
	                }
	                //$sValue = $sValue.", ".$r->cnt;
	                if ($cDay == 28) {
	                    $sValue = $sValue.", null, null, null";
	                } else if ($cDay == 29) {
	                    $sValue = $sValue.", null, null";
	                } else if ($cDay == 30) {
	                    $sValue = $sValue.", null";
	                }
	                //$sValue = $sValue."]";
	                $series['value'][$cMonth] = $sValue;
	            }
	        }

	        $seriesValue = "";
	        for($i=0; $i<count($series['value']); $i++) {
	            if ($i > 0) {
	                $seriesValue = $seriesValue.", ";
	            }
	            $seriesValue = $seriesValue."{";
	            $seriesValue = $seriesValue."showInLegend: false, ";
	            $seriesValue = $seriesValue."name: '".$series['name'][$i]."', ";
	            $seriesValue = $seriesValue."data: ".$series['value'][$i]." ";
	            $seriesValue = $seriesValue."}";
	        }

	        //$result['x'] = $view['mst_graph']['seq'];
	        $result['x'] = "1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31";
	        //$result['v'] = $view['mst_graph']['cnt'];
	        $result['v'] = "[".$seriesValue."]";
	        $result['syy'] = $startYear;
	        $result['smm'] = $view['param']['smonth'];
	        $result['sdd'] = "";
	        $result['eyy'] = $endYear;
	        $result['emm'] = $view['param']['emonth'];
	        $result['edd'] = "";

	    }
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt > seriesValue : ". $seriesValue);
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt > period : ". $result['period']);
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt > result : ". $result);
	    //log_message("ERROR", "/biz/stats/ajax_mst_cnt > result['x'] : ". $result['x'] .", result['v'] : ". $result['v']);

	    //$json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    //header('Content-Type: application/json');
	    //echo $json;

	    header('Content-Type: application/json');
	    echo '{"x": "'. $result['x'] .'", "n1": "'. $series['name'][0] .'", "v1": "'. $series['value'][0] .'", "n2": "'. $series['name'][1] .'", "v2": "'. $series['value'][1] .'", "n3": "'. $series['name'][2] .'", "v3": "'. $series['value'][2] .'", "syy": "'. $result['syy'] .'", "smm": "'. $result['smm'] .'", "sdd": "'. $result['sdd'] .'", "eyy": "'. $result['eyy'] .'", "emm": "'. $result['emm'] .'", "edd": "'. $result['edd'] .'", "send_total": "'. $view['mst_data']['send_total'] .'", "reserved_qty": "'. $view['mst_data']['reserved_qty'] .'", "succ": "'. $view['mst_data']['succ'] .'", "fail": "'. $view['mst_data']['fail'] .'", "rate": "'. $view['mst_data']['rate'] .'", "at_succ": "'. $view['mst_data']['at_succ'] .'", "at_fail": "'. $view['mst_data']['at_fail'] .'", "at_qty": "'. $view['mst_data']['at_qty'] .'", "at_rate": "'. $view['mst_data']['at_rate'] .'", "ft_succ": "'. $view['mst_data']['ft_succ'] .'", "ft_fail": "'. $view['mst_data']['ft_fail'] .'", "ft_qty": "'. $view['mst_data']['ft_qty'] .'", "ft_rate": "'. $view['mst_data']['ft_rate'] .'", "tx_succ": "'. $view['mst_data']['tx_succ'] .'", "tx_fail": "'. $view['mst_data']['tx_fail'] .'", "tx_qty": "'. $view['mst_data']['tx_qty'] .'", "tx_rate": "'. $view['mst_data']['tx_rate'] .'"}';

	}

    //리스트 조회
	public function search_member() {
        $type = $this->input->post("type");
        $mem_id = $this->member->item("mem_id");

        $result = array();

        $result['page_yn'] = (!empty($this->input->post("page_yn")))? $this->input->post("page_yn") : "Y";

        $result['perpage'] = (!empty($this->input->post("perpage")))? $this->input->post("perpage") : "10";
        $result['page'] = (!empty($this->input->post("page")))? $this->input->post("page") : "1";


        if(!empty($type)){
            $view['list_cnt'] = 0;
            $view['list'] = "";
            $gubun = substr($type,0,1);
            if($gubun=="w"){
                $where = " WHERE 1=1 and  a.mem_useyn='Y' ";
                if($type=="w2"){
                    $where .= " and a.mem_cont_cancel_yn  = 'Y' "; //계정해지업체
                }else if($type=="w3"){
                    $where = " LEFT JOIN cb_member_dormant_dhn b ON a.mem_id = b.mem_id WHERE  a.mem_useyn='Y' and b.mdd_dormant_flag = 1 "; //휴면업체
                }
                if(!empty($this->input->post("searchstr"))){
                    if($this->input->post("searchnm") == '1'){
                        $where .= " and a.mem_username  like '%".$this->input->post("searchstr")."%' ";
                    }else if($this->input->post("searchnm") == '2'){
                        $where .= " and a.mem_userid  like '%".$this->input->post("searchstr")."%' ";
                    }
                }
                //전체수
                $sql = "select count(*) as cnt from cb_member a ".$where." and a.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                FROM cb_member_register cmr
                                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                                UNION ALL
                                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                FROM cb_member_register cmr
                                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                                SELECT DISTINCT mem_id
                                FROM cmem UNION ALL
                                SELECT ".$mem_id." mem_id) ";
                $view['list_cnt'] = $this->db->query($sql)->row()->cnt;
                // log_message("ERROR", "Stats.php sql 1 : ".$sql);

                //리스트
                $sql = "select a.mem_userid, a.mem_username, a.mem_nickname, a.mem_useyn, a.mem_cont_cancel_yn, a.mem_cont_cancel_date, a.mem_emp_phone, a.mem_sales_name, a.mem_lastlogin_datetime, a.mem_lastlogin_ip, a.mem_register_datetime, ddd.mem_username as adminname, (SELECT ds.ds_name FROM dhn_salesperson2 ds WHERE a.mem_sales_mng_id = ds.ds_id) AS salesperson from cb_member a LEFT JOIN cb_member_register ccc ON a.mem_id = ccc.mem_id LEFT JOIN cb_member ddd ON ccc.mrg_recommend_mem_id = ddd.mem_id ".$where." and a.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                FROM cb_member_register cmr
                                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                                UNION ALL
                                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                FROM cb_member_register cmr
                                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                                SELECT DISTINCT mem_id
                                FROM cmem UNION ALL
                                SELECT ".$mem_id." mem_id)
                                ORDER BY a.mem_username ASC
                                LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];
                $view['list'] = $this->db->query($sql)->result();
                // log_message("ERROR", "Stats.php sql 2 : ".$sql);

            }else if($gubun=="m"){
                $where = " ";
                $cwhere = " ";
                if(!empty($this->input->post("searchstr"))){
                    if($this->input->post("searchnm") == '1'){
                        $where .= " and a.mem_username  like '%".$this->input->post("searchstr")."%' ";
                        $cwhere .= " and t.mem_username  like '%".$this->input->post("searchstr")."%' ";
                    }else if($this->input->post("searchnm") == '2'){
                        $where .= " and a.mem_userid  like '%".$this->input->post("searchstr")."%' ";
                        $cwhere .= " and t.mem_userid  like '%".$this->input->post("searchstr")."%' ";
                    }
                }
                if($type=="m2"){ // 미발송업체
                    $where .= " and spf_mem_id not in (SELECT DISTINCT mst_mem_id FROM cb_wt_msg_sent) ";
                    $cwhere .= " and t.spf_mem_id not in (SELECT DISTINCT mst_mem_id FROM cb_wt_msg_sent) ";
                    // $selectall = "t.*, bbb.mem_userid, bbb.mem_username";
                }else if($type=="m3"){ // 당월신규계약업체
                    $where .= " and MONTH(b.spf_datetime) = MONTH(CURRENT_DATE()) AND YEAR(b.spf_datetime) = YEAR(CURRENT_DATE())";
                    $cwhere .= " and MONTH(t.spf_datetime) = MONTH(CURRENT_DATE()) AND YEAR(t.spf_datetime) = YEAR(CURRENT_DATE())";
                }else if($type=="m4"){ // 당월신규미발송업체
                    $where .= " and MONTH(b.spf_datetime) = MONTH(CURRENT_DATE()) AND YEAR(b.spf_datetime) = YEAR(CURRENT_DATE()) and b.spf_mem_id not in (SELECT DISTINCT mst_mem_id FROM cb_wt_msg_sent)";
                    $cwhere .= " and MONTH(t.spf_datetime) = MONTH(CURRENT_DATE()) AND YEAR(t.spf_datetime) = YEAR(CURRENT_DATE()) and t.spf_mem_id not in (SELECT DISTINCT mst_mem_id FROM cb_wt_msg_sent)";
                }else if($type=="m5"){ // 당월신규발송업체
                    $where .= " and MONTH(b.spf_datetime) = MONTH(CURRENT_DATE()) AND YEAR(b.spf_datetime) = YEAR(CURRENT_DATE()) and b.spf_mem_id in (SELECT DISTINCT mst_mem_id FROM cb_wt_msg_sent)";
                    $cwhere .= " and MONTH(t.spf_datetime) = MONTH(CURRENT_DATE()) AND YEAR(t.spf_datetime) = YEAR(CURRENT_DATE()) and t.spf_mem_id in (SELECT DISTINCT mst_mem_id FROM cb_wt_msg_sent)";
                }



                $sql = "select count(*) as cnt from (SELECT a.spf_mem_id, a.spf_datetime, b.mem_username FROM
                                cb_wt_send_profile_dhn a LEFT JOIN cb_member b ON a.spf_mem_id = b.mem_id WHERE b.mem_useyn = 'Y' and a.spf_mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                FROM cb_member_register cmr
                                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                                UNION ALL
                                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                FROM cb_member_register cmr
                                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                                SELECT DISTINCT mem_id
                                FROM cmem UNION ALL
                                SELECT ".$mem_id." mem_id) ) t where 1=1 ".$cwhere;
                $view['list_cnt'] = $this->db->query($sql)->row()->cnt;
                // log_message("ERROR", "Stats.php sql 3 : ".$sql);

                if ($type == "m1"){
                    $add_select = "
                    , (
                        SELECT
                            SUM(mst_qty)
                        FROM
                            cb_wt_msg_sent_v
                        WHERE
                            mst_mem_id = b.spf_mem_id
                            AND
                            mst_datetime BETWEEN CONCAT(DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m'), '-01 00:00:00') AND DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m-%d %H:%i:%s')
                        /* GROUP BY
  		                    mst_mem_id */
                        ) AS lm
                        , (
                        SELECT
                            SUM(mst_qty)
                        FROM
                            cb_wt_msg_sent_v
                        WHERE
                            mst_mem_id = b.spf_mem_id
                            AND
                            mst_datetime BETWEEN CONCAT(DATE_FORMAT(NOW(), '%Y-%m'), '-01 00:00:00') AND DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s')
                        /* GROUP BY
	                        mst_mem_id */
                        ) AS cm
                    ";
                }

                $sql = "select b.spf_mem_id, a.mem_userid, a.mem_username, a.mem_nickname, a.mem_useyn, a.mem_cont_cancel_yn, a.mem_cont_cancel_date, a.mem_emp_phone, a.mem_sales_name, a.mem_lastlogin_datetime, a.mem_lastlogin_ip, a.mem_register_datetime, ddd.mem_username as adminname, (SELECT ds.ds_name FROM dhn_salesperson2 ds WHERE a.mem_sales_mng_id = ds.ds_id) AS salesperson " . $add_select . " FROM
                                cb_wt_send_profile_dhn b LEFT JOIN cb_member a ON b.spf_mem_id = a.mem_id  LEFT JOIN cb_member_register ccc ON a.mem_id = ccc.mem_id LEFT JOIN cb_member ddd ON ccc.mrg_recommend_mem_id = ddd.mem_id WHERE a.mem_useyn = 'Y' and b.spf_mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                FROM cb_member_register cmr
                                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                                UNION ALL
                                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                FROM cb_member_register cmr
                                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                                SELECT DISTINCT mem_id
                                FROM cmem UNION ALL
                                SELECT ".$mem_id." mem_id)  ".$where."
                                ORDER BY a.mem_username ASC
                                LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];

                // log_message("ERROR", "Stats.php sql 4 : ".$sql);

                $view['list'] = $this->db->query($sql)->result();


            } else if ($gubun == "v") {
                $where = " ";
                if(!empty($this->input->post("searchstr"))){
                    if($this->input->post("searchnm") == '1'){
                        $where .= " and cm.mem_username  like '%".$this->input->post("searchstr")."%' ";
                    }else if($this->input->post("searchnm") == '2'){
                        $where .= " and cm.mem_userid  like '%".$this->input->post("searchstr")."%' ";
                    }
                }

                if ($type == "v1"){
                    $sql = "
                        SELECT
                            COUNT(*) AS cnt
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
                                cb_wt_msg_sent_v
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
                            and cm.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                            UNION ALL
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                            SELECT DISTINCT mem_id
                            FROM cmem UNION ALL
                            SELECT ".$mem_id." mem_id)
                            " . $where . "";
                    $view['list_cnt'] = $this->db->query($sql)->row()->cnt;
                    $sql = "
                        SELECT
                            *
                          , (SELECT cm3.mem_username FROM cb_member cm2 LEFT JOIN cb_member_register cmr2 ON cm2.mem_id = cmr2.mem_id LEFT JOIN cb_member cm3 ON cmr2.mrg_recommend_mem_id = cm3.mem_id WHERE cm2.mem_id = cm.mem_id) AS adminname
                          , (SELECT ds.ds_name FROM dhn_salesperson2 ds WHERE cm.mem_sales_mng_id = ds.ds_id) AS salesperson
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
                                cb_wt_msg_sent_v
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
                            and cm.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                            UNION ALL
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                            SELECT DISTINCT mem_id
                            FROM cmem UNION ALL
                            SELECT ".$mem_id." mem_id)
                            " . $where . "
                        ORDER BY
                            cm.mem_username ASC
                        LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];
                    $view['list'] = $this->db->query($sql)->result();
                } else if ($type == "v2"){
                    $sql = "
                        SELECT
                            COUNT(*) AS cnt
                        FROM
                            cb_wt_voucher_addon cva
                        LEFT JOIN
                            cb_member cm
                        ON
                            cva.vad_mem_id = cm.mem_id
                        WHERE
                            cm.mem_voucher_yn = 'Y'
                            and cm.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                            UNION ALL
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                            SELECT DISTINCT mem_id
                            FROM cmem UNION ALL
                            SELECT ".$mem_id." mem_id)
                            " . $where . "
                    ";
                    $view['list_cnt'] = $this->db->query($sql)->row()->cnt;
                    $sql = "
                        SELECT
                            *
                          , (SELECT cm3.mem_username FROM cb_member cm2 LEFT JOIN cb_member_register cmr2 ON cm2.mem_id = cmr2.mem_id LEFT JOIN cb_member cm3 ON cmr2.mrg_recommend_mem_id = cm3.mem_id WHERE cm2.mem_id = cm.mem_id) AS adminname
                          , (SELECT ds.ds_name FROM dhn_salesperson2 ds WHERE cm.mem_sales_mng_id = ds.ds_id) AS salesperson
                        FROM
                            cb_wt_voucher_addon cva
                        LEFT JOIN
                            cb_member cm
                        ON
                            cva.vad_mem_id = cm.mem_id
                        WHERE
                            cm.mem_voucher_yn = 'Y'
                            and cm.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                            UNION ALL
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                            SELECT DISTINCT mem_id
                            FROM cmem UNION ALL
                            SELECT ".$mem_id." mem_id)
                            " . $where . "
                        ORDER BY
                            cm.mem_username ASC
                        LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];
                    $view['list'] = $this->db->query($sql)->result();
                } else if ($type == "v3"){
                    $sql = "
                        SELECT
                            COUNT(*) AS cnt
                        FROM
                            cb_wt_voucher_addon cva
                        LEFT JOIN
                            cb_member cm
                            ON
                            cva.vad_mem_id = cm.mem_id
                        LEFT JOIN(
                            SELECT
                                mem_id
                              , MAX(mll_id) AS mll_id
                              , MAX(mll_datetime) AS mll_datetime
                            FROM
                                cb_member_login_log
                            WHERE
                                mll_datetime BETWEEN DATE_SUB(NOW(), INTERVAL 20 DAY) AND NOW()
                                AND
                                mll_success = 1
                            GROUP BY
                                mem_id
                        ) AS login ON cm.mem_id = login.mem_id
                        WHERE
                            cm.mem_voucher_yn = 'Y'
                            AND
                            login.mll_id IS NULL
                            and cm.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                            UNION ALL
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                            SELECT DISTINCT mem_id
                            FROM cmem UNION ALL
                            SELECT ".$mem_id." mem_id)
                            " . $where . "
                    ";
                    $view['list_cnt'] = $this->db->query($sql)->row()->cnt;
                    $sql = "
                        SELECT
                            *
                          , (SELECT cm3.mem_username FROM cb_member cm2 LEFT JOIN cb_member_register cmr2 ON cm2.mem_id = cmr2.mem_id LEFT JOIN cb_member cm3 ON cmr2.mrg_recommend_mem_id = cm3.mem_id WHERE cm2.mem_id = cm.mem_id) AS adminname
                          , (SELECT ds.ds_name FROM dhn_salesperson2 ds WHERE cm.mem_sales_mng_id = ds.ds_id) AS salesperson
                        FROM
                            cb_wt_voucher_addon cva
                        LEFT JOIN
                            cb_member cm
                            ON
                            cva.vad_mem_id = cm.mem_id
                        LEFT JOIN(
                            SELECT
                                mem_id
                              , MAX(mll_id) AS mll_id
                              , MAX(mll_datetime) AS mll_datetime
                            FROM
                                cb_member_login_log
                            WHERE
                                mll_datetime BETWEEN DATE_SUB(NOW(), INTERVAL 20 DAY) AND NOW()
                                AND
                                mll_success = 1
                            GROUP BY
                                mem_id
                        ) AS login ON cm.mem_id = login.mem_id
                        WHERE
                            cm.mem_voucher_yn = 'Y'
                            AND
                            login.mll_id IS NULL
                            and cm.mem_id in (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                            UNION ALL
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                            SELECT DISTINCT mem_id
                            FROM cmem UNION ALL
                            SELECT ".$mem_id." mem_id)
                            " . $where . "
                        ORDER BY
                            cm.mem_username ASC
                        LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];
                    // log_message("error", $sql);
                    $view['list'] = $this->db->query($sql)->result();
                }

            }

            $result['total'] = $view['list_cnt'];


            if(!empty($view['list'])){
                $html = "
                <colgroup>
                    <col width=\"15%\">
                    <col width=\"*\">
                    <col width=\"10%\">
                    <col width=\"15%\">
                    " . ($type=="m1" ? "<col width=\"*\"><col width=\"*\">" : "") . "
                    <col width=\"10%\">
                    <col width=\"10%\">
                </colgroup>
                <thead>
                <tr>
                    <th>소속</th>
                    <th>업체명</th>
                    <th>계정</th>
                    <th>가입일</th>
                    " . ($type=="m1" ? "<th>전월</th><th>금월</th>" : "") . "
                    <th>영업자</th>
                    <th>상세정보</th>
                </tr>
                </thead>
                <tbody>
                    ";
				foreach ($view['list'] as $r){

                    $html .= "
                        <tr>
                            <td>".$r->adminname."</td>
                            <td>".$r->mem_username."</td>
                            <td>".$r->mem_userid."</td>
                            <td>".substr($r->mem_register_datetime,0,10)."</td>
                            ";
                    if($type=="m1"){
                    $html .= "
                            <td>".number_format($r->lm)."</td>
                            <td>".number_format($r->cm)."</td>
                            ";
                        }

                    $html .= "
                            <td>".$r->salesperson."</td>
                            <td><a href='/biz/partner/view?".$r->mem_userid."' target='_blank'><span>상세보기</span></td>
                        </tr>";
				}
                $html .= "
                    </tbody>";
			}else{
				$html = "<colgroup>
                    <col width=\"15%\">
                    <col width=\"*\">
                    <col width=\"10%\">
                    <col width=\"15%\">
                    <col width=\"10%\">
                    <col width=\"10%\">
                </colgroup>
                <thead>
                <tr>
                    <th>소속</th>
                    <th>업체명</th>
                    <th>계정</th>
                    <th>가입일</th>
                    <th>영업자</th>
                    <th>상세정보</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan = '6'>데이터가 없습니다</td>
                </tr>
                </tbody>";
			}
        }


		//log_message("ERROR", "Stats.php sql : ".$sql);

        //페이징 처리
		if($result['page_yn'] == "Y"){
			$this->load->library('pagination');
			$page_cfg['link_mode'] = 'searchMemberPage';
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

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;

		// $result['pass'] = $pass;
		// $json = json_encode($result,JSON_UNESCAPED_UNICODE);
		// header('Content-Type: application/json');
		// echo $json;

    }
}
?>
