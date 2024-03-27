<?php
require_once APPPATH.'/third_party/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Settlement extends CB_Controller {
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
        //log_message("ERROR", "Settlement 1.===============================");
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        $view['param']['userid'] = (!$this->input->post('userid') || $this->input->post('userid')=="ALL") ? ($this->member->item('mem_level')>100) ? "dhn" : $this->member->item('mem_userid') : $this->input->post('userid');
        $view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m'); //현재달
        //$view['param']['startDate'] = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m', strtotime("-1 months")); //전달
        $view['param']['companyName'] = ($this->input->post('companyName')) ? $this->input->post('companyName') : '';

		$search_userid = ($this->input->post('userid') != "") ? $this->input->post('userid') : "ALL"; //중간관리자
		$view['param']['full_care_yn'] = ($this->input->post('full_care_yn') != "") ? $this->input->post('full_care_yn') : "ALL"; //월사용료 존재여부
		$view['param']['month_deposit_yn'] = ($this->input->post('month_deposit_yn') != "") ? $this->input->post('month_deposit_yn') : "ALL"; //충전금액 존재여부
        $view['param']['voucher_yn'] = ($this->input->post('voucher_yn') != "") ? $this->input->post('voucher_yn') : "ALL"; //바우처 업체 여부
        $view['param']['pay_type_yn'] = ($this->input->post('pay_type_yn') != "") ? $this->input->post('pay_type_yn') : "ALL"; //바우처 업체 여부
        $view['param']['kvd_voucher_yn'] = ($this->input->post('kvd_voucher_yn') != "") ? $this->input->post('kvd_voucher_yn') : "ALL"; //바우처 소진 여부

		//echo "param_userid : ". $view['param']['userid'] ."<br>";
		//echo "search_userid : ". $search_userid ."<br>";
		//echo "full_care_yn : ". $view['param']['full_care_yn'] .", month_deposit_yn : ". $view['param']['month_deposit_yn'] ."<br>";

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

		//정산 프로시저 실행
		//$sql = "CALL cb_monthly_settlement()";
		//$this->db->query($sql);

        $sql = "select * from cb_wt_adm_price";
        $price = $this->db->query($sql)->result();
        $view['adm_phn'] = array();
        $view['adm_sms'] = array();
        $view['adm_lms'] = array();
        $view['adm_mms'] = array();
        foreach($price as $p) {
            $view['adm_phn'][$p->adm_agent] = $p->adm_price_phn;
            $view['adm_sms'][$p->adm_agent] = $p->adm_price_sms;
            $view['adm_lms'][$p->adm_agent] = $p->adm_price_lms;
            $view['adm_mms'][$p->adm_agent] = $p->adm_price_mms;
        }

        $view['users'] = $this->Biz_model->get_child_member($this->member->item('mem_id'), 10);

        $mem = $this->Biz_model->get_member($view['param']['userid'], true);
        //echo '<pre> :: ';print_r($mem); exit;
        if(!$mem || $mem->mem_userid!=$view['param']['userid']) { show_404(); exit; }
        $view['member'] = $mem;

        $param = array($view['param']['startDate'].'-01 00:00:00', $view['param']['startDate'].'-31 23:59:59');
        /* 전체 조회 일 경우 중간 관리자 정산 내역 표시
         * 최 상위 관리자 조회 일때..
         * */
        //log_message("ERROR", "END USEDR / ".$this->input->post('userid'));
        //log_message("ERROR", "param USEDR / ".$view['param']['userid']);
        $skin_type = "";
        //if(($view['param']['userid'] == 'dhn' || $view['param']['userid'] == 'dhnadmin') && $view['param']['companyName'] == ''){
        if(1 == 1){ //2021-02-10
            //echo $_SERVER['REQUEST_URI'] ." > 최 상위 관리자 조회 조건 > userid : ". $view['param']['userid'] .", startDate : ". $view['param']['startDate'] ."<br>";
			$skin_type = "ALL";
            $sch = " WHERE 1=1 ";
            //log_message("ERROR", "query 1 ========================");
			if($view['param']['full_care_yn'] == "Y" and $view['param']['month_deposit_yn'] == "Y"){ //월사용료 = Y and 충전금액 = Y
				$sch .= "and full_care > 0 AND month_deposit > 0 ";
			}else if($view['param']['full_care_yn'] == "Y" and $view['param']['month_deposit_yn'] == "N"){ //월사용료 = Y and 충전금액 = N
				$sch .= "and full_care > 0 AND month_deposit = 0 ";
			}else if($view['param']['full_care_yn'] == "Y" and $view['param']['month_deposit_yn'] == "ALL"){ //월사용료 = Y and 충전금액 = ALL
				$sch .= "and full_care > 0 ";
			}else if($view['param']['full_care_yn'] == "N" and $view['param']['month_deposit_yn'] == "ALL"){ //월사용료 = N and 충전금액 = ALL
				$sch .= "and full_care = 0 ";
			}else if($view['param']['full_care_yn'] == "N" and $view['param']['month_deposit_yn'] == "Y"){ //월사용료 = N and 충전금액 = Y
				$sch .= "and full_care = 0 AND month_deposit > 0 ";
			}else if($view['param']['full_care_yn'] == "N" and $view['param']['month_deposit_yn'] == "N"){ //월사용료 = N and 충전금액 = N
				$sch .= "and full_care = 0 AND month_deposit = 0 ";
			}else if($view['param']['full_care_yn'] == "ALL" and $view['param']['month_deposit_yn'] == "Y"){ //월사용료 = ALL and 충전금액 = Y
				$sch .= "and month_deposit > 0 ";
			}else if($view['param']['full_care_yn'] == "ALL" and $view['param']['month_deposit_yn'] == "N"){ //월사용료 = ALL and 충전금액 = N
				$sch .= "and month_deposit = 0 ";
			}else{
				$sch .= "and (smt_tot > 0 OR full_care > 0 OR month_deposit > 0) ";
			}
            if($view['param']['voucher_yn'] == "Y"){
                $sch .= " AND (tt.mem_voucher_yn='Y' || tt.voucher_flag ='V') ";
            }else if($view['param']['voucher_yn'] == "N"){
                $sch .= " AND (tt.mem_voucher_yn='N' && tt.voucher_flag ='N') ";
            }

            if($view['param']['kvd_voucher_yn'] == "Y"){
                $sch .= " AND tt.kvd_proc_flag='Y'  ";
            }else if($view['param']['kvd_voucher_yn'] == "N"){
                $sch .= " AND tt.kvd_proc_flag='N' ";
            }

            if($view['param']['pay_type_yn'] == "B"){
                $sch .= " AND tt.mem_pay_type = 'B' ";
            }else if($view['param']['pay_type_yn'] == "A"){
                $sch .= " AND tt.mem_pay_type = 'A' ";
            }
            if(!empty($view['param']['companyName'])){
                $sch .= " AND tt.vendor like '%".$view['param']['companyName']."%' ";
            }

			$sch .= "AND mem_register_datetime < '". date("Y-m-d", strtotime($view['param']['startDate'] ."-01  +1 month")) ."' ";
			$sql = "			SELECT *, (at_amt+ft_amt+ft_img_amt+ft_w_img_amt+smt_amt+smt_sms_amt+smt_mms_amt+rcs_amt+rcs_sms_amt+rcs_mms_amt+rcs_tem_amt) as total_amt
            , (at + ft + ft_img + ft_w_img + smt + smt_sms + smt_mms + rcs + rcs_sms + rcs_mms + rcs_tem) as total_sum
            -- , (at_amount + ft_amount +ft_img_amount + ft_w_img_amount + smt_amount + smt_sms_amount + smt_mms_amount) as total_amount
            , (select count(1) as cnt from cb_monthly_report vv where vv.end_mem_id = tt.mem_id and  tt.period_name = vv.period_name and (vv.at <> '0' or vv.ft <> '0' or vv.smt <> '0' or vv.smt_sms <> '0' or vv.smt_mms <> '0' or vv.rcs <> '0' or vv.rcs_sms <> '0' or vv.rcs_mms <> '0' or vv.rcs_tem <> '0')) as cnt
			from (
				select
                cmr.voucher_flag,
                kvd.kvd_proc_flag,
                ifnull(period_name, '".$view['param']['startDate']."') as period_name,
                cmt.mem_userid,
                cmt.mem_id,
                cmt.mem_pay_type,
                cmt.mem_username as vendor,
                cmt.mem_deposit as deposit,
                cmt.mad_price_full_care as full_care,
                cmt.mem_voucher_yn,
                cmr.at_price as ori_at,
                cmr.ft_price as ori_ft,
                cmr.ft_img_price as ori_ft_img,
                cmr.smt_price as ori_smt,
                cmr.smt_sms_price as ori_smt_sms,
                ifnull(at, 0) as at,
                (ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_at + (fcwva.vad_price_at - fcwma.mad_price_at)  else cwma.mad_price_at end, 0) - ifnull(cmr.base_up_at, 0)) as at_margin,
                ifnull(at, 0) *
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_at + (fcwva.vad_price_at - fcwma.mad_price_at) else cwma.mad_price_at end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_at > 0 then fcwva.vad_price_at else fcwma.mad_price_at end) as at_amt,
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_at + (fcwva.vad_price_at - fcwma.mad_price_at) else cwma.mad_price_at end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_at > 0 then fcwva.vad_price_at else fcwma.mad_price_at end) as price_at,
                ifnull(ft, 0) as ft,
                (ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_ft + (fcwva.vad_price_ft - fcwma.mad_price_ft) else cwma.mad_price_ft end, 0) - ifnull(cmr.base_up_ft, 0)) as ft_margin,
                ifnull(ft, 0) *
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_ft + (fcwva.vad_price_ft - fcwma.mad_price_ft) else cwma.mad_price_ft end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_ft > 0 then fcwva.vad_price_ft else fcwma.mad_price_ft end) as ft_amt,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_ft > 0 then cwva.vad_price_ft else cwma.mad_price_ft end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_ft > 0 then fcwva.vad_price_ft else fcwma.mad_price_ft end) as ft_amt,
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_ft + (fcwva.vad_price_ft - fcwma.mad_price_ft) else cwma.mad_price_ft end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_ft > 0 then fcwva.vad_price_ft else fcwma.mad_price_ft end) as price_ft,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_ft > 0 then cwva.vad_price_ft else cwma.mad_price_ft end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_ft > 0 then fcwva.vad_price_ft else fcwma.mad_price_ft end) as price_ft,
                ifnull(ft_img, 0) as ft_img,
                (ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_ft_img + (fcwva.vad_price_ft_img - fcwma.mad_price_ft_img) else cwma.mad_price_ft_img end, 0) - ifnull(cmr.base_up_ft_img, 0)) as ft_img_margin,
                ifnull(ft_img, 0) *
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_ft_img + (fcwva.vad_price_ft_img - fcwma.mad_price_ft_img) else cwma.mad_price_ft_img end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_ft_img > 0 then fcwva.vad_price_ft_img else fcwma.mad_price_ft_img end) as ft_img_amt,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_ft_img > 0 then cwva.vad_price_ft_img else cwma.mad_price_ft_img end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_ft_img > 0 then fcwva.vad_price_ft_img else fcwma.mad_price_ft_img end) as ft_img_amt,
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_ft_img + (fcwva.vad_price_ft_img - fcwma.mad_price_ft_img) else cwma.mad_price_ft_img end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_ft_img > 0 then fcwva.vad_price_ft_img else fcwma.mad_price_ft_img end) as price_ft_img,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_ft_img > 0 then cwva.vad_price_ft_img else cwma.mad_price_ft_img end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_ft_img > 0 then fcwva.vad_price_ft_img else fcwma.mad_price_ft_img end) as price_ft_img,
                ifnull(ft_w_img, 0) as ft_w_img,
                (ifnull(cwma.mad_price_ft_w_img,0) - ifnull(cmr.base_up_ft_w_img,0)) as ft_w_img_margin,
                -- ifnull(ft_w_img, 0) * (ifnull(cwma.mad_price_ft_w_img,0) - ifnull(cmr.base_up_ft_w_img,0)) as ft_w_img_amt,
                ifnull(ft_w_img, 0) * ifnull(cwma.mad_price_ft_w_img, fcwma.mad_price_ft_w_img) as ft_w_img_amt,
                ifnull(cwma.mad_price_ft_w_img, fcwma.mad_price_ft_w_img) as price_ft_w_img,
                ifnull(grs, 0) as grs,
                (ifnull(cwma.mad_price_grs, 0) - ifnull(cmr.base_up_grs, 0)) as grs_margin,
                -- ifnull(grs, 0) * (ifnull(cwma.mad_price_grs, 0) - ifnull(cmr.base_up_grs, 0)) as grs_amt,
                ifnull(grs, 0) * ifnull(cwma.mad_price_grs, fcwma.mad_price_grs) as grs_amt,
                ifnull(cwma.mad_price_grs, fcwma.mad_price_grs) as price_grs,
                ifnull(grs_biz,0) as grs_biz,
                case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = 'dhn' then (ifnull(cwma.mad_price_grs_biz,0) - ifnull(cwma.mad_price_grs_biz,0)) else (ifnull(cmr.grs_price, 0) - ifnull(cwma.mad_price_grs_biz, 0)) end as grs_biz_margin,
                -- case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = 'dhn' then ifnull(grs_biz,0) * (ifnull(cwma.mad_price_grs_biz,0) - ifnull(cwma.mad_price_grs_biz,0)) else ifnull(grs_biz,0) * (ifnull(cmr.grs_price, 0) - ifnull(cwma.mad_price_grs_biz, 0)) end as grs_biz_amt,
                case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = 'dhn' then ifnull(grs_biz,0) * ifnull(cwma.mad_price_grs_biz, fcwma.mad_price_grs_biz) else ifnull(grs_biz,0) * ifnull(cwma.mad_price_grs_biz, fcwma.mad_price_grs_biz) end as grs_biz_amt,
                ifnull(cwma.mad_price_grs_biz, fcwma.mad_price_grs_biz) as price_grs_biz,
                ifnull(grs_sms, 0) as grs_sms,
                (ifnull(cwma.mad_price_grs_sms,0) - ifnull(cmr.base_up_grs_sms, 0)) as grs_sms_margin,
                -- ifnull(grs_sms, 0) * (ifnull(cwma.mad_price_grs_sms,0) - ifnull(cmr.base_up_grs_sms, 0)) as grs_sms_amt,
                ifnull(grs_sms, 0) * ifnull(cwma.mad_price_grs_sms, fcwma.mad_price_grs_sms) as grs_sms_amt,
                ifnull(cwma.mad_price_grs_sms, fcwma.mad_price_grs_sms) as price_grs_sms,
                ifnull(grs_mms, 0) as grs_mms,
                (ifnull(cwma.mad_price_grs_mms, 0) - ifnull(cmr.base_up_grs_mms, 0)) as grs_mms_margin,
                -- ifnull(grs_mms, 0) * (ifnull(cwma.mad_price_grs_mms, 0) - ifnull(cmr.base_up_grs_mms, 0)) as grs_mms_amt,
                ifnull(grs_mms, 0) * ifnull(cwma.mad_price_grs_mms, fcwma.mad_price_grs_mms) as grs_mms_amt,
                ifnull(cwma.mad_price_grs_mms, fcwma.mad_price_grs_mms) as price_grs_mms,
                ifnull(nas, 0) as nas,
                (ifnull(cwma.mad_price_nas, 0) - ifnull(cmr.base_up_nas,0)) as nas_margin,
                -- ifnull(nas, 0) * (ifnull(cwma.mad_price_nas, 0) - ifnull(cmr.base_up_nas,0)) as nas_amt,
                ifnull(nas, 0) * ifnull(cwma.mad_price_nas, fcwma.mad_price_nas) as nas_amt,
                ifnull(cwma.mad_price_nas, fcwma.mad_price_nas) as price_nas,
                ifnull(nas_sms,0) as nas_sms,
                (ifnull(cwma.mad_price_nas_sms, 0) - ifnull(cmr.base_up_nas_sms,0)) as nas_sms_margin,
                -- ifnull(nas_sms,0) * (ifnull(cwma.mad_price_nas_sms, 0) - ifnull(cmr.base_up_nas_sms,0)) as nas_sms_amt,
                ifnull(nas_sms,0) * ifnull(cwma.mad_price_nas_sms, fcwma.mad_price_nas_sms) as nas_sms_amt,
                ifnull(cwma.mad_price_nas_sms, fcwma.mad_price_nas_sms) as price_nas_sms,
                ifnull(nas_mms, 0) as nas_mms,
                (ifnull(cwma.mad_price_nas_mms,0) - ifnull(cmr.base_up_nas_mms, 0)) as nas_mms_margin,
                -- ifnull(nas_mms, 0) * (ifnull(cwma.mad_price_nas_mms,0) - ifnull(cmr.base_up_nas_mms, 0)) as nas_mms_amt,
                ifnull(nas_mms, 0) * ifnull(cwma.mad_price_nas_mms, fcwma.mad_price_nas_mms) as nas_mms_amt,
                ifnull(cwma.mad_price_nas_mms, fcwma.mad_price_nas_mms) as price_nas_mms,
                ifnull(smt, 0) as smt,
                (ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_smt + (fcwva.vad_price_smt - fcwma.mad_price_smt) else cwma.mad_price_smt end, 0) - ifnull(cwma.mad_price_smt, 0)) as smt_margin,
                ifnull(smt, 0) *
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_smt + (fcwva.vad_price_smt - fcwma.mad_price_smt) else cwma.mad_price_smt end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_smt > 0 then fcwva.vad_price_smt else fcwma.mad_price_smt end) as smt_amt,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_smt > 0 then cwva.vad_price_smt else cwma.mad_price_smt end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_smt > 0 then fcwva.vad_price_smt else fcwma.mad_price_smt end) as smt_amt,
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_smt + (fcwva.vad_price_smt - fcwma.mad_price_smt) else cwma.mad_price_smt end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_smt > 0 then fcwva.vad_price_smt else fcwma.mad_price_smt end) as price_smt,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_smt > 0 then cwva.vad_price_smt else cwma.mad_price_smt end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_smt > 0 then fcwva.vad_price_smt else fcwma.mad_price_smt end) as price_smt,
                ifnull(smt_sms,0) as smt_sms,
                (ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_smt_sms + (fcwva.vad_price_smt_sms - fcwma.mad_price_smt_sms) else cwma.mad_price_smt_sms end,0) - ifnull(cwma.mad_price_smt_sms, 0)) as smt_sms_margin,
                ifnull(smt_sms,0) *
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_smt_sms + (fcwva.vad_price_smt_sms - fcwma.mad_price_smt_sms) else cwma.mad_price_smt_sms end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_smt_sms > 0 then fcwva.vad_price_smt_sms else fcwma.mad_price_smt_sms end) as smt_sms_amt,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_smt_sms > 0 then cwva.vad_price_smt_sms else cwma.mad_price_smt_sms end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_smt_sms > 0 then fcwva.vad_price_smt_sms else fcwma.mad_price_smt_sms end) as smt_sms_amt,
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_smt_sms + (fcwva.vad_price_smt_sms - fcwma.mad_price_smt_sms) else cwma.mad_price_smt_sms end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_smt_sms > 0 then fcwva.vad_price_smt_sms else fcwma.mad_price_smt_sms end) as price_smt_sms,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_smt_sms > 0 then cwva.vad_price_smt_sms else cwma.mad_price_smt_sms end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_smt_sms > 0 then fcwva.vad_price_smt_sms else fcwma.mad_price_smt_sms end) as price_smt_sms,
                ifnull(smt_mms, 0) as smt_mms,
                (ifnull(cmr.smt_mms_price,0) - ifnull(cwma.mad_price_smt_mms,0)) as smt_mms_margin,
                ifnull(smt_mms, 0) * ifnull(cwma.mad_price_smt_mms, fcwma.mad_price_smt_mms) as smt_mms_amt,
                ifnull(cwma.mad_price_smt_mms, fcwma.mad_price_smt_mms) as price_smt_mms,

                ifnull(rcs, 0) as rcs,
                (ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_rcs + (fcwva.vad_price_rcs - fcwma.mad_price_rcs) else cwma.mad_price_rcs end, 0) - ifnull(cwma.mad_price_rcs, 0)) as rcs_margin,
                ifnull(rcs, 0) *
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_rcs + (fcwva.vad_price_rcs - fcwma.mad_price_rcs) else cwma.mad_price_rcs end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_rcs > 0 then fcwva.vad_price_rcs else fcwma.mad_price_rcs end) as rcs_amt,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_rcs > 0 then cwva.vad_price_rcs else cwma.mad_price_rcs end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_rcs > 0 then fcwva.vad_price_rcs else fcwma.mad_price_rcs end) as rcs_amt,
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_rcs + (fcwva.vad_price_rcs - fcwma.mad_price_rcs) else cwma.mad_price_rcs end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_rcs > 0 then fcwva.vad_price_rcs else fcwma.mad_price_rcs end) as price_rcs,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_rcs > 0 then cwva.vad_price_rcs else cwma.mad_price_rcs end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_rcs > 0 then fcwva.vad_price_rcs else fcwma.mad_price_rcs end) as price_rcs,
                ifnull(rcs_sms,0) as rcs_sms,
                (ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_rcs_sms + (fcwva.vad_price_rcs_sms - fcwma.mad_price_rcs_sms) else cwma.mad_price_rcs_sms end,0) - ifnull(cwma.mad_price_rcs_sms, 0)) as rcs_sms_margin,
                ifnull(rcs_sms,0) *
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_rcs_sms + (fcwva.vad_price_rcs_sms - fcwma.mad_price_rcs_sms) else cwma.mad_price_rcs_sms end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_rcs_sms > 0 then fcwva.vad_price_rcs_sms else fcwma.mad_price_rcs_sms end) as rcs_sms_amt,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_rcs_sms > 0 then cwva.vad_price_rcs_sms else cwma.mad_price_rcs_sms end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_rcs_sms > 0 then fcwva.vad_price_rcs_sms else fcwma.mad_price_rcs_sms end) as rcs_sms_amt,
                ifnull(case when cmr.voucher_flag = 'V' then cwma.mad_price_rcs_sms + (fcwva.vad_price_rcs_sms - fcwma.mad_price_rcs_sms) else cwma.mad_price_rcs_sms end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_rcs_sms > 0 then fcwva.vad_price_rcs_sms else fcwma.mad_price_rcs_sms end) as price_rcs_sms,
                -- ifnull(case when cmr.voucher_flag = 'V' and cwva.vad_price_rcs_sms > 0 then cwva.vad_price_rcs_sms else cwma.mad_price_rcs_sms end, case when cmr.voucher_flag = 'V' and fcwva.vad_price_rcs_sms > 0 then fcwva.vad_price_rcs_sms else fcwma.mad_price_rcs_sms end) as price_rcs_sms,
                ifnull(rcs_mms, 0) as rcs_mms,
                (ifnull(cmr.rcs_mms_price,0) - ifnull(cwma.mad_price_rcs_mms,0)) as rcs_mms_margin,
                ifnull(rcs_mms, 0) * ifnull(cwma.mad_price_rcs_mms, fcwma.mad_price_rcs_mms) as rcs_mms_amt,
                ifnull(cwma.mad_price_rcs_mms, fcwma.mad_price_rcs_mms) as price_rcs_mms,

				ifnull(rcs_tem, 0) as rcs_tem,
                (ifnull(cmr.rcs_tem_price,0) - ifnull(cwma.mad_price_rcs_tem,0)) as rcs_tem_margin,
                ifnull(rcs_tem, 0) * ifnull(cwma.mad_price_rcs_tem, fcwma.mad_price_rcs_tem) as rcs_tem_amt,
                ifnull(cwma.mad_price_rcs_tem, fcwma.mad_price_rcs_tem) as price_rcs_tem,

                IFNULL((at + ft + ft_img + ft_w_img + grs + grs_biz + grs_sms + nas + nas_sms + nas_mms + grs_mms + grs_biz + smt + smt_sms + smt_mms + rcs + rcs_sms + rcs_mms + rcs_tem),0) as smt_tot,
                IFNULL(depo.month_deposit,0) AS month_deposit,
                cmt.mem_register_datetime
				from (select cm.mem_id, cm.mem_userid, cm.mem_username, cm.mem_deposit, cm.mem_pay_type, cm.mem_cont_from_date, cm.mem_voucher_yn, date_format(cm.mem_register_datetime, '%Y-%m-%d') as mem_register_datetime, cwma.*, cwva.*
					  from  cb_member cm
						   left join cb_wt_member_addon cwma on cm.mem_id = cwma.mad_mem_id
                           left join cb_wt_voucher_addon cwva on cm.mem_id = cwva.vad_mem_id
					 where (cm.mem_cont_cancel_yn = 'N' OR (cm.mem_cont_cancel_yn = 'Y' AND cm.mem_cont_cancel_date >= '".$view['param']['startDate']."-01')) and cm.mem_useyn = 'Y'
                     and cm.mem_id in (    WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                            FROM cb_member_register cmr
                                                 JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                            WHERE cm.mem_userid = '".$view['param']['userid']."' and cmr.mem_id <> (select mem_id from cb_member where mem_userid = '".$view['param']['userid']."')
                                            UNION ALL
                                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                            FROM cb_member_register cmr
                                                 JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                                 JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id )
                                        SELECT distinct mem_id
                                        FROM cmem
                                        union all
                                        select mem_id
                                        from cb_member cms
                                        where cms.mem_userid = '".$view['param']['userid']."'
                              )

                 ) cmt
					 left join (
						SELECT cbd.mem_id, ifnull(sum(case when cbd.dep_pay_type <> 'voucher' then cbd.dep_deposit end),0) as month_deposit
                        , ifnull(sum(case when cbd.dep_pay_type = 'voucher' then cbd.dep_deposit end),0) as month_v_deposit
						FROM cb_deposit cbd
						WHERE dep_pay_type IN ('bank', 'vbank', 'voucher')
						AND dep_status = '1'
						AND DATE_FORMAT(dep_datetime, '%Y-%m') = '".$view['param']['startDate']."'
						GROUP BY cbd.mem_id
					) as depo ON cmt.mem_id = depo.mem_id
					 left join cb_monthly_report cmr  on cmt.mem_id = cmr.end_mem_id and cmr.period_name = '".$view['param']['startDate']."'
					 left join cb_wt_member_addon cwma on CASE WHEN LEFT('".$view['param']['userid']."',4) = 'dhn_' THEN CASE WHEN cmr.id_level1 = 2 THEN cwma.mad_mem_id = cmr.id_level4 ELSE cwma.mad_mem_id = cmr.id_level3 END ELSE CASE WHEN cmr.id_level1 = 2 THEN cwma.mad_mem_id = cmr.id_level3 ELSE cwma.mad_mem_id = cmr.id_level2 END END
					 left join cb_wt_member_addon fcwma on cmt.mem_id = fcwma.mad_mem_id
                     left join cb_wt_voucher_addon cwva on CASE WHEN LEFT('".$view['param']['userid']."',4) = 'dhn_' THEN CASE WHEN cmr.id_level1 = 2 THEN cwma.mad_mem_id = cmr.id_level4 ELSE cwva.vad_mem_id = cmr.id_level3 END ELSE CASE WHEN cmr.id_level1 = 2 THEN cwva.vad_mem_id = cmr.id_level3 ELSE cwva.vad_mem_id = cmr.id_level2 END END
					 left join cb_wt_voucher_addon fcwva on cmt.mem_id = fcwva.vad_mem_id
                     left join cb_kvoucher_deposit kvd on cmt.mem_id = kvd.kvd_mem_id
			) tt
			 ". $sch ."
			 ORDER BY mem_id, voucher_flag ASC  ";
			if($this->member->item('mem_level') >= 100){
				//echo $sql ."<br>";
				log_message("ERROR", "/biz/manager/settlement > sql : ". $sql);
			}
			$list = $this->db->query($sql)->result();
            $view['list'] = $list;
        } else { //중간 관리자가 조회 조건일 경우 하위 업체 정산 내역 표시
            $skin_type = "END";
            $post_userid = $this->input->post('userid');
			//echo $_SERVER['REQUEST_URI'] ." > 중간 관리자가 조회 조건 > post_userid : ". $post_userid .", userid : ". $view['param']['userid'] .", startDate : ". $view['param']['startDate'] ."<br>";
			if ($this->member->item('mem_userid') == 'dhn' || $this->member->item('mem_userid') == 'dhnadmin') {
                $view['param']['userid'] = $post_userid;
                //log_message("ERROR", "query 2 ========================");
                $sql = "
                    SELECT
                        period_name,
                        manager_mem_id,
                        cm.mem_userid,
                        end_mem_id,
                        (select mem_username from cb_member cm where cm.mem_id = end_mem_id) as vendor,
                        ( select sum(dep_deposit)
                            from cb_deposit cd
                    	  where cd.mem_id = end_mem_id
                              and DATE_FORMAT(cd.dep_deposit_datetime, '%Y-%m')  = period_name) as deposit,
                        fcwma.mad_price_full_care as full_care,
                        at,
                        (cwma.mad_price_at - cmr.base_up_at) as at_margin,
                        at * (cwma.mad_price_at - cmr.base_up_at) as at_amt,
                        ft,
                        (cwma.mad_price_ft - cmr.base_up_ft) as ft_margin,
                        ft*(cwma.mad_price_ft - cmr.base_up_ft) as ft_amt,
                        ft_img,
                        (cwma.mad_price_ft_img - cmr.base_up_ft_img) as ft_img_margin,
                        ft_img * (cwma.mad_price_ft_img - cmr.base_up_ft_img) as ft_img_amt,
                        ft_w_img,
                        (cwma.mad_price_ft_w_img - cmr.base_up_ft_w_img) as ft_w_img_margin,
                        ft_w_img * (cwma.mad_price_ft_w_img - cmr.base_up_ft_w_img) as ft_w_img_amt,
                        -- sms,
                        -- (cwma.mad_price_grs - cmr.base_up_grs) as sms_margin,
                        -- sms * sms_price as sms_amt,
                        -- lms,
                        -- lms_price,
                        -- lms * lms_price as lms_amt,
                        -- phn,
                        -- phn_price,
                        -- phn * phn_price as phn_amt,
                        grs,
                        (cwma.mad_price_grs - cmr.base_up_grs) as grs_margin,
                        grs * (cwma.mad_price_grs - cmr.base_up_grs) as grs_amt,
		            	grs_biz,
		            	case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = 'tgtech' then (cwma.mad_price_grs_biz - cwma.mad_price_grs_biz) else (cmr.grs_price - cwma.mad_price_grs_biz) end as grs_biz_margin,
		            	case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = 'tgtech' then grs_biz * (cwma.mad_price_grs_biz - cwma.mad_price_grs_biz) else grs_biz * (cmr.grs_price - cwma.mad_price_grs_biz) end as grs_biz_amt,
		            	-- case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = '".$post_userid."' then (cwma.mad_price_grs_biz - cwma.mad_price_grs_biz) else (cmr.grs_price - cwma.mad_price_grs_biz) end as grs_biz_margin,
		            	-- case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = '".$post_userid."' then grs_biz * (cwma.mad_price_grs_biz - cwma.mad_price_grs_biz) else grs_biz * (cmr.grs_price - cwma.mad_price_grs_biz) end as grs_biz_amt,
                        grs_sms,
                        (cwma.mad_price_grs_sms - cmr.base_up_grs_sms) as grs_sms_margin,
                        grs_sms * (cwma.mad_price_grs_sms - cmr.base_up_grs_sms) as grs_sms_amt,
                        grs_mms,
                        (cwma.mad_price_grs_mms - cmr.base_up_grs_mms) as grs_mms_margin,
                        grs_mms * (cwma.mad_price_grs_mms - cmr.base_up_grs_mms) as grs_mms_amt,
                        nas,
                        (cwma.mad_price_nas - cmr.base_up_nas) as nas_margin,
                        nas * (cwma.mad_price_nas - cmr.base_up_nas) as nas_amt,
                        nas_sms,
                        (cwma.mad_price_nas_sms - cmr.base_up_nas_sms) as nas_sms_margin,
                        nas_sms * (cwma.mad_price_nas_sms - cmr.base_up_nas_sms) as nas_sms_amt,
                        nas_mms,
                        (cwma.mad_price_nas_mms - cmr.base_up_nas_mms) as nas_mms_margin,
                        nas_mms * (cwma.mad_price_nas_mms - cmr.base_up_nas_mms) as nas_mms_amt,
		            	smt,
		            	(cmr.smt_price - cwma.mad_price_smt) as smt_margin,
		            	smt * (cmr.smt_price - cwma.mad_price_smt) as smt_amt,
		            	smt_sms,
		            	(cmr.smt_sms_price - cwma.mad_price_smt_sms) as smt_sms_margin,
		            	smt_sms * (cmr.smt_sms_price - cwma.mad_price_smt_sms) as smt_sms_amt,
		            	smt_mms,
		            	(cmr.smt_mms_price - cwma.mad_price_smt_mms) as smt_mms_margin,
		            	smt_mms * (cmr.smt_mms_price - cwma.mad_price_smt_mms) as smt_mms_amt
                        -- f_015,
                        -- f_015_price,
                        -- f_015 * f_015_price as f_015_amt
                    FROM
                        cb_monthly_report cmr inner join
                        (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                	FROM cb_member_register cmr
                                	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                	WHERE cm.mem_userid = '".$view['param']['userid']."' and cmr.mem_id <> (select mem_id from cb_member where mem_userid = '".$view['param']['userid']."')
                                	UNION ALL
                                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                	FROM cb_member_register cmr
                                	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                                )
                                SELECT distinct mem_id
                                FROM cmem
                                union all
                                select mem_id
                                from cb_member cm
                                where cm.mem_userid = '".$view['param']['userid']."'
                                )  cms on cmr.end_mem_id = cms.mem_id inner join
                        cb_member cm on cm.mem_id = cmr.manager_mem_id left join
                        cb_wt_member_addon cwma on CASE WHEN LEFT('".$view['param']['userid']."',4) = 'dhn_' THEN cwma.mad_mem_id = cmr.id_level3 ELSE cwma.mad_mem_id = cmr.id_level2 END left join
                        cb_wt_member_addon fcwma on cmr.end_mem_id = fcwma.mad_mem_id
                    WHERE
                        cmr.period_name = '".$view['param']['startDate']."'
                    ORDER BY period_name , mem_level DESC, manager_mem_id , end_mem_id
                ";
            } else {
                if ($post_userid == "ALL") {
                    //log_message("ERROR", "query 3 ========================");
                    //$post_userid = $view['param']['userid'];
                    $post_userid = $this->member->item('mem_userid');
                    $sql = "
                    SELECT
                        period_name,
                        manager_mem_id,
                        cm.mem_userid,
                        end_mem_id,
                        (select mem_username from cb_member cm where cm.mem_id = end_mem_id) as vendor,
                        ( select sum(dep_deposit)
                            from cb_deposit cd
                    	  where cd.mem_id = end_mem_id
                              and DATE_FORMAT(cd.dep_deposit_datetime, '%Y-%m')  = period_name) as deposit,
                        fcwma.mad_price_full_care as full_care,
                        at,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN (cmr.at_price - cwma.mad_price_at)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN (cmr.at_price - cwma.mad_price_at)
                             ELSE (mcwma.mad_price_at - cwma.mad_price_at) END as at_margin,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN at * (cmr.at_price - cwma.mad_price_at)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN at * (cmr.at_price - cwma.mad_price_at)
                             ELSE at * (mcwma.mad_price_at - cwma.mad_price_at) END as at_amt,
                        ft,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN (cmr.ft_price - cwma.mad_price_ft)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN (cmr.ft_price - cwma.mad_price_ft)
                             ELSE (mcwma.mad_price_ft - cwma.mad_price_ft) END as ft_margin,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN ft * (cmr.ft_price - cwma.mad_price_ft)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN ft * (cmr.ft_price - cwma.mad_price_ft)
                             ELSE ft * (mcwma.mad_price_ft - cwma.mad_price_ft) END as ft_amt,
                        ft_img,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN (cmr.ft_img_price - cwma.mad_price_ft_img)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN (cmr.ft_img_price - cwma.mad_price_ft_img)
                             ELSE (mcwma.mad_price_ft_img - cwma.mad_price_ft_img) END as ft_img_margin,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN ft_img * (cmr.ft_img_price - cwma.mad_price_ft_img)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN ft_img * (cmr.ft_img_price - cwma.mad_price_ft_img)
                             ELSE ft_img * (mcwma.mad_price_ft_img - cwma.mad_price_ft_img) END as ft_img_amt,
                        ft_w_img,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN (cmr.ft_w_img_price - cwma.mad_price_ft_w_img)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN (cmr.ft_w_img_price - cwma.mad_price_ft_w_img)
                             ELSE (mcwma.mad_price_ft_w_img - cwma.mad_price_ft_w_img) END as ft_w_img_margin,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN ft_w_img * (cmr.ft_w_img_price - cwma.mad_price_ft_w_img)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN ft_w_img * (cmr.ft_w_img_price - cwma.mad_price_ft_w_img)
                             ELSE ft_img * (mcwma.mad_price_ft_w_img - cwma.mad_price_ft_w_img) END as ft_w_img_amt,
                        -- sms,
                        -- (cwma.mad_price_grs - cmr.base_up_grs) as sms_margin,
                        -- sms * sms_price as sms_amt,
                        -- lms,
                        -- lms_price,
                        -- lms * lms_price as lms_amt,
                        -- phn,
                        -- phn_price,
                        -- phn * phn_price as phn_amt,
                        grs,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN (cmr.grs_price - cwma.mad_price_grs)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN (cmr.grs_price - cwma.mad_price_grs)
                             ELSE (mcwma.mad_price_grs - cwma.mad_price_grs) END as grs_margin,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN grs * (cmr.grs_price - cwma.mad_price_grs)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN grs * (cmr.grs_price - cwma.mad_price_grs)
                             ELSE grs * (mcwma.mad_price_grs - cwma.mad_price_grs) END as grs_amt,
                        grs_sms,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN (cmr.grs_sms_price - cwma.mad_price_grs_sms)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN (cmr.grs_sms_price - cwma.mad_price_grs_sms)
                             ELSE (mcwma.mad_price_grs_sms - cwma.mad_price_grs_sms) END as grs_sms_margin,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN grs_sms * (cmr.grs_sms_price - cwma.mad_price_grs_sms)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN grs_sms * (cmr.grs_sms_price - cwma.mad_price_grs_sms)
                             ELSE grs_sms * (mcwma.mad_price_grs_sms - cwma.mad_price_grs_sms) END as grs_sms_amt,
                        grs_mms,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN (cmr.grs_mms_price - cwma.mad_price_grs_mms)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN (cmr.grs_mms_price - cwma.mad_price_grs_mms)
                             ELSE (mcwma.mad_price_grs_mms - cwma.mad_price_grs_mms) END as grs_mms_margin,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN grs_mms * (cmr.grs_mms_price - cwma.mad_price_grs_mms)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN grs_mms * (cmr.grs_mms_price - cwma.mad_price_grs_mms)
                             ELSE grs_mms * (mcwma.mad_price_grs_mms - cwma.mad_price_grs_mms) END as grs_mms_amt,
                        nas,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN (cmr.nas_price - cwma.mad_price_nas)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN (cmr.nas_price - cwma.mad_price_nas)
                             ELSE (mcwma.mad_price_nas - cwma.mad_price_nas) END as nas_margin,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN nas * (cmr.nas_price - cwma.mad_price_nas)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN nas * (cmr.nas_price - cwma.mad_price_nas)
                             ELSE nas * (mcwma.mad_price_nas - cwma.mad_price_nas) END as nas_amt,
                        nas_sms,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN (cmr.nas_sms_price - cwma.mad_price_nas_sms)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN (cmr.nas_sms_price - cwma.mad_price_nas_sms)
                             ELSE (mcwma.mad_price_nas_sms - cwma.mad_price_nas_sms) END as nas_sms_margin,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN nas_sms * (cmr.nas_sms_price - cwma.mad_price_nas_sms)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN nas_sms * (cmr.nas_sms_price - cwma.mad_price_nas_sms)
                             ELSE nas_sms * (mcwma.mad_price_nas_sms - cwma.mad_price_nas_sms) END as nas_sms_amt,
                        nas_mms,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN (cmr.nas_mms_price - cwma.mad_price_nas_mms)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN (cmr.nas_mms_price - cwma.mad_price_nas_mms)
                             ELSE (mcwma.mad_price_nas_mms - cwma.mad_price_nas_mms) END as nas_mms_margin,
                        CASE WHEN (cmr.manager_mem_id <> cwma.mad_mem_id AND cmr.end_mem_id = cwma.mad_mem_id) THEN nas_sms * (cmr.nas_mms_price - cwma.mad_price_nas_mms)
                             WHEN (cmr.manager_mem_id = cwma.mad_mem_id AND cmr.end_mem_id <> cwma.mad_mem_id) THEN nas_sms * (cmr.nas_mms_price - cwma.mad_price_nas_mms)
                             ELSE nas_mms * (mcwma.mad_price_nas_mms - cwma.mad_price_nas_mms) END as nas_mms_amt
                        -- f_015,
                        -- f_015_price,
                        -- f_015 * f_015_price as f_015_amt
                    FROM
                        cb_monthly_report cmr inner join
                        (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                	FROM cb_member_register cmr
                                	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                	WHERE cm.mem_userid = '".$post_userid."' and cmr.mem_id <> (select mem_id from cb_member where mem_userid = '".$post_userid."')
                                	UNION ALL
                                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                	FROM cb_member_register cmr
                                	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                                )
                                SELECT distinct mem_id
                                FROM cmem
                                union all
                                select mem_id
                                from cb_member cm
                                where cm.mem_userid = '".$post_userid."'
                                )  cms on cmr.end_mem_id = cms.mem_id inner join
                        cb_member cm on cm.mem_id = cmr.manager_mem_id left join
                        cb_wt_member_addon cwma on CASE WHEN ".$this->member->item('mem_level')." = 50 THEN cwma.mad_mem_id = cmr.id_level2
                        								WHEN ".$this->member->item('mem_level')." = 30 THEN cwma.mad_mem_id = cmr.id_level3
                        								WHEN ".$this->member->item('mem_level')." = 10 THEN cwma.mad_mem_id = cmr.id_level4 END left join
                        cb_wt_member_addon mcwma on cmr.manager_mem_id = mcwma.mad_mem_id left join
                        cb_wt_member_addon fcwma on cmr.end_mem_id = fcwma.mad_mem_id
                    WHERE
                        cmr.period_name = '".$view['param']['startDate']."'
                    ORDER BY period_name , mem_level DESC, manager_mem_id , end_mem_id
                    ";
                    if($this->member->item('mem_level') >= 100){
                        log_message("ERROR", "/biz/manager/settlement2 > sql : ". $sql);
                    }
                } else {
                    //log_message("ERROR", "query 4 ========================");
                    $sql = "
                    SELECT
                        period_name,
                        manager_mem_id,
                        cm.mem_userid,
                        end_mem_id,
                        (select mem_username from cb_member cm where cm.mem_id = end_mem_id) as vendor,
                        ( select sum(dep_deposit)
                            from cb_deposit cd
                    	  where cd.mem_id = end_mem_id
                              and DATE_FORMAT(cd.dep_deposit_datetime, '%Y-%m')  = period_name) as deposit,
                        fcwma.mad_price_full_care as full_care,
                        at,
                        (case when cmr.voucher_flag = 'V' and ccccc.vad_price_at > 0 then ccccc.vad_price_at - cwma.mad_price_at else c_cwma.mad_price_at - cwma.mad_price_at end) as at_margin,
                        at * (case when cmr.voucher_flag = 'V' and ccccc.vad_price_at > 0 then ccccc.vad_price_at - cwma.mad_price_at else c_cwma.mad_price_at - cwma.mad_price_at end) as at_amt,
                        ft,
                        (c_cwma.mad_price_ft - cwma.mad_price_ft) as ft_margin,
                        ft*(c_cwma.mad_price_ft - cwma.mad_price_ft) as ft_amt,
                        ft_img,
                        (c_cwma.mad_price_ft_img - cwma.mad_price_ft_img) as ft_img_margin,
                        ft_img * (c_cwma.mad_price_ft_img - cwma.mad_price_ft_img) as ft_img_amt,
                        ft_w_img,
                        (c_cwma.mad_price_ft_w_img - cwma.mad_price_ft_w_img) as ft_w_img_margin,
                        ft_w_img * (c_cwma.mad_price_ft_w_img - cwma.mad_price_ft_w_img) as ft_w_img_amt,
                        -- sms,
                        -- (cwma.mad_price_grs - cmr.base_up_grs) as sms_margin,
                        -- sms * sms_price as sms_amt,
                        -- lms,
                        -- lms_price,
                        -- lms * lms_price as lms_amt,
                        -- phn,
                        -- phn_price,
                        -- phn * phn_price as phn_amt,
                        grs,
                        (c_cwma.mad_price_grs - cwma.mad_price_grs) as grs_margin,
                        grs * (c_cwma.mad_price_grs - cwma.mad_price_grs) as grs_amt,
                        grs_sms,
                        (c_cwma.mad_price_grs_sms - cwma.mad_price_grs_sms) as grs_sms_margin,
                        grs_sms * (c_cwma.mad_price_grs_sms - cwma.mad_price_grs_sms) as grs_sms_amt,
                        grs_mms,
                        (c_cwma.mad_price_grs_mms - cwma.mad_price_grs_mms) as grs_mms_margin,
                        grs_mms * (c_cwma.mad_price_grs_mms - cwma.mad_price_grs_mms) as grs_mms_amt,
                        nas,
                        (c_cwma.mad_price_nas - cwma.mad_price_nas) as nas_margin,
                        nas * (c_cwma.mad_price_nas - cwma.mad_price_nas) as nas_amt,
                        nas_sms,
                        (c_cwma.mad_price_nas_sms - cwma.mad_price_nas_sms) as nas_sms_margin,
                        nas_sms * (c_cwma.mad_price_nas_sms - cwma.mad_price_nas_sms) as nas_sms_amt,
                        nas_mms,
                        (c_cwma.mad_price_nas_mms - cwma.mad_price_nas_mms) as nas_mms_margin,
                        nas_mms * (c_cwma.mad_price_nas_mms - cwma.mad_price_nas_mms) as nas_mms_amt
                        -- f_015,
                        -- f_015_price,
                        -- f_015 * f_015_price as f_015_amt
                    FROM
                        cb_monthly_report cmr inner join
                        (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                	FROM cb_member_register cmr
                                	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                	WHERE cm.mem_userid = '".$view['param']['userid']."' and cmr.mem_id <> (select mem_id from cb_member where mem_userid = '".$view['param']['userid']."')
                                	UNION ALL
                                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                	FROM cb_member_register cmr
                                	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                                )
                                SELECT distinct mem_id
                                FROM cmem
                                union all
                                select mem_id
                                from cb_member cm
                                where cm.mem_userid = '".$view['param']['userid']."'
                                )  cms on cmr.end_mem_id = cms.mem_id inner join
                        cb_member cm on cm.mem_id = cmr.manager_mem_id left join
                        cb_wt_member_addon cwma on CASE WHEN ".$this->member->item('mem_level')." = 50 THEN cwma.mad_mem_id = cmr.id_level2
                    									WHEN ".$this->member->item('mem_level')." = 30 THEN cwma.mad_mem_id = cmr.id_level3
                    									WHEN ".$this->member->item('mem_level')." = 10 THEN cwma.mad_mem_id = cmr.id_level4 END left join
                        cb_wt_voucher_addon ccccc on CASE WHEN ".$this->member->item('mem_level')." = 50 THEN ccccc.vad_mem_id = cmr.id_level2
                    									WHEN ".$this->member->item('mem_level')." = 30 THEN ccccc.vad_mem_id = cmr.id_level3
                    									WHEN ".$this->member->item('mem_level')." = 10 THEN ccccc.vad_mem_id = cmr.id_level4 END left join
                        (select a.mem_userid, a.mem_level, b.*
                    	 from cb_member a left join
                    	 	cb_wt_member_addon b on a.mem_id = b.mad_mem_id
                    	 where mem_userid = '".$view['param']['userid']."') c_cwma on CASE WHEN c_cwma.mem_level = 30 THEN c_cwma.mad_mem_id = cmr.id_level3
                    	 											WHEN c_cwma.mem_level = 10 THEN c_cwma.mad_mem_id = cmr.id_level4 END left join
                    	cb_wt_member_addon fcwma on cmr.end_mem_id = fcwma.mad_mem_id
                    WHERE
                        cmr.period_name = '".$view['param']['startDate']."'
                    ORDER BY cmr.period_name , cm.mem_level DESC, cmr.manager_mem_id , cmr.end_mem_id
                    ";
                    if($this->member->item('mem_level') >= 100){
                        log_message("ERROR", "/biz/manager/settlement3 > sql : ". $sql);
                    }
                }
            }
            log_message("ERROR", $sql);
            $list = $this->db->query($sql)->result();
            $view['list'] = $list;
        }

		//echo "search_userid : ". $search_userid ."<br>";
		$view['param']['userid'] = $search_userid;
		if($view['param']['userid'] == "ALL"){
			$skin = "admin";
		}else{
			$skin = "offer";
		}

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
            'path' => 'biz/manager/settlement',
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

    /*
     * 월정산, 일정산 엑셀파일 내려받기의 상단 Total
     */
    private function excelTopTable($r, $cur_worksheet, $start_date, $end_date, $period_name) {
        $cur_worksheet->getColumnDimension('A')->setWidth(1.38 + 0.63);
        $cur_worksheet->getColumnDimension('B')->setWidth(14.63 + 0.63);
        $cur_worksheet->getColumnDimension('C')->setWidth(19.75 + 0.63);
        $cur_worksheet->getColumnDimension('D')->setWidth(17.50 + 0.63);
        $cur_worksheet->getColumnDimension('E')->setWidth(17.50 + 0.63);
        $cur_worksheet->getRowDimension(2)->setRowHeight(25);

        $cur_worksheet->setTitle($r->mem_username.'_'.$period_name)
        ->mergeCells("B2:E2")
        ->setCellValue("B2", str_replace('_',' ',$r->mem_username).'('.$r->mem_email.')');
        $cur_worksheet->getStyle("B2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle("B2")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle("B2")->getFont()->setSize(13);

        $cur_worksheet->getRowDimension(4)->setRowHeight(18);
        $cur_worksheet->mergeCells("D4:E4")->setCellValue("D4", "기간 : ".$start_date." ~ ".$end_date);
        $cur_worksheet->getStyle("D4")->getFont()->setBold(true);
        $cur_worksheet->getStyle("D4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle("D4")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('D4:E4')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $cur_worksheet->getStyle('D4:E4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $cur_worksheet->getStyle('D4:E4')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $cur_worksheet->getStyle('D4:E4')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $cur_worksheet->getStyle('D4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $cur_worksheet->getStyle("D4")->getFill()->getStartColor()->setARGB('00F2F2F2');

        $start_table_row = 5;
        $cur_worksheet->getRowDimension(5)->setRowHeight(15);
        $cur_worksheet->getRowDimension(6)->setRowHeight(15);
        $cur_worksheet->mergeCells("B5:B6");
        $cur_worksheet->mergeCells("C5:C6");
        $cur_worksheet->mergeCells("D5:D6");
        $cur_worksheet->mergeCells("E5:E6");
        $cur_worksheet->getStyle("B5:E6")->getFont()->getColor()->setARGB('007D604F');
        $cur_worksheet->getStyle("B5:E6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle("B5:E6")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('B5:E6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $cur_worksheet->getStyle("B5:E6")->getFill()->getStartColor()->setARGB('00D9D9D9');
        $cur_worksheet->setCellValue("B5", "항목");
        $cur_worksheet->setCellValue("C5", "발송건수");
        $cur_worksheet->setCellValue("D5", "단가(VAT포함)");
        $cur_worksheet->setCellValue("E5", "금액(VAT포함)");

        $row_count = 7;
        $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        $cur_worksheet->setCellValue("B".$row_count, "알림톡");
        $cur_worksheet->setCellValue("C".$row_count, $r->at);
        $cur_worksheet->setCellValue("D".$row_count, $r->at_price);
        $cur_worksheet->setCellValue("E".$row_count, $r->s_at_amount);
        $row_count += 1;

        $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        $cur_worksheet->setCellValue("B".$row_count, "친구톡(텍스트)");
        $cur_worksheet->setCellValue("C".$row_count, $r->ft);
        $cur_worksheet->setCellValue("D".$row_count, $r->ft_price);
        $cur_worksheet->setCellValue("E".$row_count, $r->s_ft_amount);
        $row_count += 1;

        $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        $cur_worksheet->setCellValue("B".$row_count, "친구톡(이미지)");
        $cur_worksheet->setCellValue("C".$row_count, $r->ft_img);
        $cur_worksheet->setCellValue("D".$row_count, $r->ft_img_price);
        $cur_worksheet->setCellValue("E".$row_count, $r->s_ft_img_amount);
        $row_count += 1;

        // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        // $cur_worksheet->setCellValue("B".$row_count, "친구톡(와이드)");
        // $cur_worksheet->setCellValue("C".$row_count, $r->ft_w_img);
        // $cur_worksheet->setCellValue("D".$row_count, $r->ft_w_img_price);
        // $cur_worksheet->setCellValue("E".$row_count, $r->s_ft_w_img_amount);
        // $row_count += 1;

        if ($r->grs_price == $r->nas_price && $r->grs_price == $r->smt_price) {
            $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
            $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자)");
            $cur_worksheet->setCellValue("C".$row_count, $r->grs + $r->nas + $r->smt);
            $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
            $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount + $r->s_nas_amount + $r->s_smt_amount);
            $row_count += 1;
        } else {
            if ($r->nas == 0 && $r->smt == 0) {
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자)");
                $cur_worksheet->setCellValue("C".$row_count, $r->grs);
                $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
                $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount);
                $row_count += 1;
            } else if ($r->smt == 0) {
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
                $cur_worksheet->setCellValue("C".$row_count, $r->grs);
                $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
                $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount);
                $row_count += 1;
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
                $cur_worksheet->setCellValue("C".$row_count, $r->nas);
                $cur_worksheet->setCellValue("D".$row_count, $r->nas_price);
                $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_amount);
                $row_count += 1;
            } else {
                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
                // $cur_worksheet->setCellValue("C".$row_count, $r->grs);
                // $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
                // $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount);
                // $row_count += 1;
                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
                // $cur_worksheet->setCellValue("C".$row_count, $r->nas);
                // $cur_worksheet->setCellValue("D".$row_count, $r->nas_price);
                // $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_amount);
                // $row_count += 1;
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자 LMS)");
                $cur_worksheet->setCellValue("C".$row_count, $r->smt);
                $cur_worksheet->setCellValue("D".$row_count, $r->smt_price);
                $cur_worksheet->setCellValue("E".$row_count, $r->s_smt_amount);
                $row_count += 1;
            }
        }

        if (($r->grs_sms + $r->nas_sms + $r->smt_sms) > 0) {
            if($r->grs_sms_price == $r->nas_sms_price && $r->grs_sms_price == $r->smt_sms_price) {
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, "문자(SMS)");
                $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms + $r->nas_sms + $r->smt_sms);
                $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
                $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount + $r->s_nas_sms_amount + $r->s_smt_sms_amount);
                $row_count += 1;
            } else {
                if ($r->nas_sms == 0 && $r->smt_sms == 0) {
                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자)");
                    $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
                    $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
                    $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
                    $row_count += 1;
                } else if ($r->smt_sms == 0) {
                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
                    $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
                    $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
                    $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
                    $row_count += 1;
                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
                    $cur_worksheet->setCellValue("C".$row_count, $r->nas_sms);
                    $cur_worksheet->setCellValue("D".$row_count, $r->nas_sms_price);
                    $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_sms_amount);
                    $row_count += 1;
                } else {
                    // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
                    // $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
                    // $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
                    // $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
                    // $row_count += 1;
                    // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
                    // $cur_worksheet->setCellValue("C".$row_count, $r->nas_sms);
                    // $cur_worksheet->setCellValue("D".$row_count, $r->nas_sms_price);
                    // $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_sms_amount);
                    // $row_count += 1;
                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자 SMS)");
                    $cur_worksheet->setCellValue("C".$row_count, $r->smt_sms);
                    $cur_worksheet->setCellValue("D".$row_count, $r->smt_sms_price);
                    $cur_worksheet->setCellValue("E".$row_count, $r->s_smt_sms_amount);
                    $row_count += 1;
                }
//                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
//                 $cur_worksheet->setCellValue("B".$row_count, "문자A(SMS)");
//                 $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
//                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
//                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
//                 $row_count += 1;
//                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
//                 $cur_worksheet->setCellValue("B".$row_count, "문자B(SMS)");
//                 $cur_worksheet->setCellValue("C".$row_count, $r->nas_sms);
//                 $cur_worksheet->setCellValue("D".$row_count, $r->nas_sms_price);
//                 $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_sms_amount);
//                 $row_count += 1;
            }
        }

        if (($r->grs_mms + $r->nas_mms + $r->smt_mms) > 0) {
            if($r->grs_mms_price == $r->nas_mms_price && $r->grs_mms_price == $r->smt_mms_price) {
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, "문자(MMS)");
                $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms + $r->nas_mms + $r->smt_mms);
                $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
                $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount + $r->s_nas_mms_amount + $r->s_smt_mms_amount);
                $row_count += 1;
            } else {
                if ($r->nas_mms == 0 && $r->smt_mms == 0) {
                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자)");
                    $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
                    $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
                    $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
                    $row_count += 1;
                } else if ($r->smt_mms == 0) {
                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
                    $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
                    $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
                    $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
                    $row_count += 1;
                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
                    $cur_worksheet->setCellValue("C".$row_count, $r->nas_mms);
                    $cur_worksheet->setCellValue("D".$row_count, $r->nas_mms_price);
                    $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_mms_amount);
                    $row_count += 1;
                } else {
                    // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
                    // $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
                    // $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
                    // $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
                    // $row_count += 1;
                    // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
                    // $cur_worksheet->setCellValue("C".$row_count, $r->nas_mms);
                    // $cur_worksheet->setCellValue("D".$row_count, $r->nas_mms_price);
                    // $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_mms_amount);
                    // $row_count += 1;
                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자 MMS)");
                    $cur_worksheet->setCellValue("C".$row_count, $r->smt_mms);
                    $cur_worksheet->setCellValue("D".$row_count, $r->smt_mms_price);
                    $cur_worksheet->setCellValue("E".$row_count, $r->s_smt_mms_amount);
                    $row_count += 1;
                }
//                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
//                 $cur_worksheet->setCellValue("B".$row_count, "문자A(MMS)");
//                 $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
//                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
//                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
//                 $row_count += 1;
//                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
//                 $cur_worksheet->setCellValue("B".$row_count, "문자B(MMS)");
//                 $cur_worksheet->setCellValue("C".$row_count, $r->nas_mms);
//                 $cur_worksheet->setCellValue("D".$row_count, $r->nas_mms_price);
//                 $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_mms_amount);
//                 $row_count += 1;
            }
        }


        $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->getStartColor()->setARGB('00D9D9D9');
        $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->setCellValue("B".$row_count, "발송 합계");
        $cur_worksheet->setCellValue("C".$row_count, $r->tot_send);
        $cur_worksheet->setCellValue("D".$row_count, "-");
        $cur_worksheet->setCellValue("E".$row_count, $r->send_amount);
        $cur_worksheet->getStyle('D'.$row_count.':'.'E'.$row_count)->getFont()->setBold(true);
        $row_count += 1;

        if($r->full_care > 0) {
            $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
            //$cur_worksheet->mergeCells("D".$row_count.":E".$row_count);
            $cur_worksheet->setCellValue("B".$row_count, "Fullcare");
            $cur_worksheet->setCellValue("C".$row_count, '-');
            $cur_worksheet->setCellValue("D".$row_count, '-');
            $cur_worksheet->setCellValue("E".$row_count, $r->full_care);
            $cur_worksheet->getStyle('E'.$row_count.':'.'E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            //$cur_worksheet->getStyle('E'.$row_count.':'.'E'.$row_count)->getFill()->getStartColor()->setARGB('00D9D9D9');
            $cur_worksheet->getStyle('E'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");
            $row_count += 1;
        }

        $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->getStartColor()->setARGB('00FFFF00');
        $cur_worksheet->getStyle('B'.$row_count.':B'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle('B'.$row_count.':B'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('C'.$row_count.':E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $cur_worksheet->getStyle('C'.$row_count.':E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->setCellValue("B".$row_count, "청구 금액");
        $cur_worksheet->setCellValue("C".$row_count, "-");
        $cur_worksheet->setCellValue("D".$row_count, "-");
        $cur_worksheet->setCellValue("E".$row_count, $r->tot_amount);
        $cur_worksheet->getStyle('D'.$row_count.':'.'E'.$row_count)->getFont()->setBold(true);

        $cur_worksheet->getStyle('B'.($start_table_row + 2).':B'.($row_count-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle('B'.($start_table_row + 2).':B'.($row_count-1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('C'.($start_table_row + 2).':E'.($row_count-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $cur_worksheet->getStyle('C'.($start_table_row + 2).':E'.($row_count-1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('C'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $cur_worksheet->getStyle('C'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $cur_worksheet->getStyle('E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $cur_worksheet->getStyle('C'.($start_table_row + 2).':C'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");
        $cur_worksheet->getStyle('D'.($start_table_row + 2).':D'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
        $cur_worksheet->getStyle('E'.($start_table_row + 2).':E'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");

        $cur_worksheet->getStyle('B'.$start_table_row.':E'.$row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return ($row_count + 1);

    }

    public function download_monthly_settlement() {
        $value = json_decode($this->input->post('value'));
        //log_message("ERROR", "---------------");
        //log_message("ERROR", "mem_id : ".$value->mem_id);
        //log_message("ERROR", "period_name : ".$value->period_name);
        //log_message("ERROR", "---------------");

        $sql = "
            select
            	a.period_name,
                a.end_mem_id as mem_id,
                a.voucher_flag,
                (select count(*) as cnt from cb_monthly_report vv where vv.end_mem_id = a.end_mem_id and  a.period_name = vv.period_name) as cnt,
            	REPLACE(REPLACE(b.mem_username, '_', ' '), '/', ' ') as mem_username,
                b.mem_userid,
            	b.mem_email,
            	c.mad_price_full_care as full_care,
            	a.ft,
            	a.ft_price,
            	(a.ft * a.ft_price) as s_ft_amount,
            	a.ft_img,
            	a.ft_img_price,
            	(a.ft_img * a.ft_img_price) as s_ft_img_amount,
            	a.ft_w_img,
            	a.ft_w_img_price,
            	(a.ft_w_img * a.ft_w_img_price) as s_ft_w_img_amount,
            	a.at,
            	a.at_price,
            	(a.at * a.at_price) as s_at_amount,
            	(a.grs + a.grs_biz) grs,
            	a.grs_price,
            	((a.grs + a.grs_biz) * a.grs_price) as s_grs_amount,
            	a.grs_sms,
            	a.grs_sms_price,
            	(a.grs_sms * a.grs_sms_price) as s_grs_sms_amount,
            	a.grs_mms,
            	a.grs_mms_price,
            	(a.grs_mms * a.grs_mms_price) as s_grs_mms_amount,
            	a.nas,
            	a.nas_price,
            	(a.nas * a.nas_price) as s_nas_amount,
            	a.nas_sms,
            	a.nas_sms_price,
            	(a.nas_sms * a.nas_sms_price) as s_nas_sms_amount,
            	a.nas_mms,
            	a.nas_mms_price,
            	(a.nas_mms * a.nas_mms_price) as s_nas_mms_amount,
            	a.smt,
            	a.smt_price,
            	(a.smt * a.smt_price) as s_smt_amount,
            	a.smt_sms,
            	a.smt_sms_price,
            	(a.smt_sms * a.smt_sms_price) as s_smt_sms_amount,
            	a.smt_mms,
            	a.smt_mms_price,
            	(a.smt_mms * a.smt_mms_price) as s_smt_mms_amount,

                a.rcs,
            	a.rcs_price,
            	(a.rcs * a.rcs_price) as s_rcs_amount,
            	a.rcs_sms,
            	a.rcs_sms_price,
            	(a.rcs_sms * a.rcs_sms_price) as s_rcs_sms_amount,
            	a.rcs_mms,
            	a.rcs_mms_price,
            	(a.rcs_mms * a.rcs_mms_price) as s_rcs_mms_amount,
                a.rcs_tem,
            	a.rcs_tem_price,
            	(a.rcs_tem * a.rcs_tem_price) as s_rcs_tem_amount,

            	(a.ft + a.ft_img + a.ft_w_img + a.at + a.grs + a.grs_biz + a.grs_sms + a.grs_mms + a.nas + a.nas_sms + a.nas_mms + a.smt + a.smt_sms + a.smt_mms + a.rcs + a.rcs_sms + a.rcs_mms + a.rcs_tem) as tot_send,
                ((a.ft * a.ft_price) + (a.ft_img * a.ft_img_price) + (a.ft_w_img * a.ft_w_img_price) + (a.at * a.at_price) + ((a.grs + a.grs_biz) * a.grs_price) + (a.grs_sms * a.grs_sms_price) + (a.grs_mms * a.grs_mms_price) + (a.nas * a.nas_price) + (a.nas_sms * a.nas_sms_price) + (a.nas_mms * a.nas_mms_price) + (a.smt * a.smt_price) + (a.smt_sms * a.smt_sms_price) + (a.smt_mms * a.smt_mms_price) + (a.rcs * a.rcs_price) + (a.rcs_sms * a.rcs_sms_price) + (a.rcs_mms * a.rcs_mms_price) + (a.rcs_tem * a.rcs_tem_price)) as send_amount,
            	((a.ft * a.ft_price) + (a.ft_img * a.ft_img_price) + (a.ft_w_img * a.ft_w_img_price) + (a.at * a.at_price) + ((a.grs + a.grs_biz) * a.grs_price) + (a.grs_sms * a.grs_sms_price) + (a.grs_mms * a.grs_mms_price) + (a.nas * a.nas_price) + (a.nas_sms * a.nas_sms_price) + (a.nas_mms * a.nas_mms_price) + (a.smt * a.smt_price) + (a.smt_sms * a.smt_sms_price) + (a.smt_mms * a.smt_mms_price)+ (a.rcs * a.rcs_price) + (a.rcs_sms * a.rcs_sms_price) + (a.rcs_mms * a.rcs_mms_price) + (a.rcs_tem * a.rcs_tem_price) + c.mad_price_full_care) as tot_amount
            from cb_monthly_report a left join
                 cb_member b on a.end_mem_id = b.mem_id left join
                 cb_wt_member_addon c on a.end_mem_id = c.mad_mem_id
            where a.period_name='".$value->period_name."' and a.end_mem_id in (".$value->mem_id.")
            order by b.mem_id;
        ";

        //log_message("ERROR", $value->mem_id);
        log_message("ERROR", $sql);
        $result_query = $this->db->query($sql)->result();
        $sheet_count = 0;
        $start_date = $value->period_name.'-01';
        $end_date = $value->period_name.'-'.date('t', strtotime($start_date));

        $spreadsheet = new Spreadsheet();

        if ($result_query) {

            $pre_mem_id ="";
            $new_ii="N";

            $sum_tot_send = 0;
            $sum_send_amount = 0;

            foreach ($result_query as $r) {

                $voucher_text = "";
                if ($sheet_count == 0){
                    $cur_worksheet = $spreadsheet->getActiveSheet();
                    $new_ii="N";
                }else{
                    if($pre_mem_id!=$r->mem_id){
                        $new_ii="N";
                        $cur_worksheet = $spreadsheet->createSheet();
                    }else{
                        if($r->cnt<2){
                            $new_ii="N";
                            $cur_worksheet = $spreadsheet->createSheet();
                        }else{
                            $new_ii="M";
                            $cur_worksheet = $spreadsheet->getActiveSheet();
                        }
                    }
                }

                $sql = "select count(1) as cnt from cb_kvoucher_deposit ckd where ckd.kvd_mem_id =  ".$r->mem_id." and ckd.kvd_proc_flag = 'Y' and ckd.kvd_minus_date BETWEEN '".$start_date."' AND '".$end_date."'";
                $voucher_blank_cnt = $this->db->query($sql)->row()->cnt;

                if($voucher_blank_cnt>0){
                    $sql = "select
                    count(1) as cnt_all,
                    sum(amt_amount) as sum_all,
                   sum(case when amt_kind = 'A' THEN 1 else 0 end) as cnt_a,
                   sum(case when amt_kind = 'F' THEN 1 else 0 end) as cnt_f,
                   sum(case when amt_kind = 'I' THEN 1 else 0 end) as cnt_i,
                   sum(case when (amt_kind = 'P' and amt_memo='웹(C) SMS,조정') THEN 1 else 0 end) as cnt_sms,
                   sum(case when (amt_kind = 'P' and amt_memo='웹(C) LMS,조정') THEN 1 else 0 end) as cnt_lms,

                   sum(case when (amt_kind = 'R' and amt_memo='RCS SMS,조정') THEN 1 else 0 end) as cnt_rcs_sms,
                   sum(case when (amt_kind = 'R' and amt_memo='RCS LMS,조정') THEN 1 else 0 end) as cnt_rcs_lms,

                   sum(case when amt_kind = 'A' THEN amt_amount else 0 end) as sum_a,
                   sum(case when amt_kind = 'F' THEN amt_amount else 0 end) as sum_f,
                   sum(case when amt_kind = 'I' THEN amt_amount else 0 end) as sum_i,
                   sum(case when (amt_kind = 'P' and amt_memo='웹(C) SMS,조정') THEN amt_amount else 0 end) as sum_sms,
                   sum(case when (amt_kind = 'P' and amt_memo='웹(C) LMS,조정') THEN amt_amount else 0 end) as sum_lms,

                   sum(case when (amt_kind = 'R' and amt_memo='RCS SMS,조정') THEN amt_amount else 0 end) as sum_rcs_sms,
                   sum(case when (amt_kind = 'R' and amt_memo='RCS LMS,조정') THEN amt_amount else 0 end) as sum_rcs_lms
                   from cb_amt_".$r->mem_userid." ca where  FIND_IN_SET('조정', amt_memo) ";
                   $adjust_voucher = $this->db->query($sql)->row();
                }

                //합계처리
                if($new_ii == "M"){
                    $sum_tot_send = $sum_tot_send + $r->tot_send;
                    $sum_send_amount = $sum_send_amount + $r->send_amount;
                }else{
                    $sum_tot_send = $r->tot_send;
                    $sum_send_amount = $r->send_amount;
                }
                //바우처 여부 처리
                if($r->voucher_flag=="V"){
                    $voucher_text = "[바우처]";
                }

                if(($new_ii=="N"&&$r->cnt<2)||$sheet_count == 0){
                    $cur_worksheet->getColumnDimension('A')->setWidth(1.38 + 0.63);
                    $cur_worksheet->getColumnDimension('B')->setWidth(25);
                    $cur_worksheet->getColumnDimension('C')->setWidth(19.75 + 0.63);
                    $cur_worksheet->getColumnDimension('D')->setWidth(17.50 + 0.63);
                    $cur_worksheet->getColumnDimension('E')->setWidth(17.50 + 0.63);
                    $cur_worksheet->getRowDimension(2)->setRowHeight(25);

                    $cur_worksheet->setTitle($r->mem_username.'_'.$value->period_name)
                    ->mergeCells("B2:E2")
                    ->setCellValue("B2", str_replace('_',' ',$r->mem_username).'('.$r->mem_email.')');
                    $cur_worksheet->getStyle("B2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle("B2")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle("B2")->getFont()->setSize(13);

                    $cur_worksheet->getRowDimension(4)->setRowHeight(18);
                    $cur_worksheet->mergeCells("D4:E4")->setCellValue("D4", "기간 : ".$start_date." ~ ".$end_date);
                    $cur_worksheet->getStyle("D4")->getFont()->setBold(true);
                    $cur_worksheet->getStyle("D4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle("D4")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('D4:E4')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle('D4:E4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle('D4:E4')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle('D4:E4')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle('D4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle("D4")->getFill()->getStartColor()->setARGB('00F2F2F2');

                    $start_table_row = 5;
                    $cur_worksheet->getRowDimension(5)->setRowHeight(15);
                    $cur_worksheet->getRowDimension(6)->setRowHeight(15);
                    $cur_worksheet->mergeCells("B5:B6");
                    $cur_worksheet->mergeCells("C5:C6");
                    $cur_worksheet->mergeCells("D5:D6");
                    $cur_worksheet->mergeCells("E5:E6");
                    $cur_worksheet->getStyle("B5:E6")->getFont()->getColor()->setARGB('007D604F');
                    $cur_worksheet->getStyle("B5:E6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle("B5:E6")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('B5:E6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle("B5:E6")->getFill()->getStartColor()->setARGB('00D9D9D9');
                    $cur_worksheet->setCellValue("B5", "항목");
                    $cur_worksheet->setCellValue("C5", "발송건수");
                    $cur_worksheet->setCellValue("D5", "단가(VAT포함)");
                    $cur_worksheet->setCellValue("E5", "금액(VAT포함)");

                    $row_count = 7;
                }

                if($voucher_blank_cnt>0&&$r->cnt=="1"){
                    $sql = "select mad_price_at, mad_price_ft, mad_price_ft_img, mad_price_smt_sms, mad_price_smt from cb_wt_member_addon where mad_mem_id = '".$r->mem_id."'";
                    $m_addon = $this->db->query($sql)->row();

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "알림톡");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_a);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_at);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_a);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "친구톡(텍스트)");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_f);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_ft);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_f);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "친구톡(이미지)");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_i);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_ft_img);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_i);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "문자 SMS");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_sms);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_smt_sms);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_sms);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "문자 LMS");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_lms);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_smt);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_lms);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "RCS SMS");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_rcs_sms);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_rcs_sms);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_rcs_sms);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "RCS LMS");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_rcs_lms);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_rcs);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_rcs_lms);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->getStartColor()->setARGB('00D9D9D9');
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->setCellValue("B".$row_count, "발송 합계");
                    $cur_worksheet->setCellValue("C".$row_count,  $adjust_voucher->cnt_all );
                    $cur_worksheet->setCellValue("D".$row_count, "-");
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_all);
                    $cur_worksheet->getStyle('D'.$row_count.':'.'E'.$row_count)->getFont()->setBold(true);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->getStartColor()->setARGB('00FFFF00');

                    $cur_worksheet->setCellValue("B".$row_count, "청구 금액");
                    $cur_worksheet->setCellValue("C".$row_count, "-");
                    $cur_worksheet->setCellValue("D".$row_count, "-");
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_all);
                    $cur_worksheet->getStyle('D'.$row_count.':'.'E'.$row_count)->getFont()->setBold(true);
                    $row_count += 1;

                }

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."알림톡");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->at - $adjust_voucher->cnt_a : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->at + $adjust_voucher->cnt_a : $r->at));
                $cur_worksheet->setCellValue("D".$row_count, $r->at_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_at_amount - $adjust_voucher->sum_a : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_at_amount + $adjust_voucher->sum_a : $r->s_at_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."친구톡(텍스트)");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->ft - $adjust_voucher->cnt_f : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->ft + $adjust_voucher->cnt_f : $r->ft));
                $cur_worksheet->setCellValue("D".$row_count, $r->ft_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_ft_amount - $adjust_voucher->sum_f : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_ft_amount + $adjust_voucher->sum_f : $r->s_ft_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."친구톡(이미지)");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->ft_img - $adjust_voucher->cnt_i : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->ft_img + $adjust_voucher->cnt_i : $r->ft_img));
                $cur_worksheet->setCellValue("D".$row_count, $r->ft_img_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_ft_img_amount - $adjust_voucher->sum_i : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_ft_img_amount + $adjust_voucher->sum_i : $r->s_ft_img_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."문자 SMS");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->smt_sms - $adjust_voucher->cnt_sms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->smt_sms + $adjust_voucher->cnt_sms : $r->smt_sms));
                $cur_worksheet->setCellValue("D".$row_count, $r->smt_sms_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_smt_sms_amount - $adjust_voucher->sum_sms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_smt_sms_amount + $adjust_voucher->sum_sms : $r->s_smt_sms_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."문자 LMS");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->smt - $adjust_voucher->cnt_lms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->smt + $adjust_voucher->cnt_lms : $r->smt));
                $cur_worksheet->setCellValue("D".$row_count, $r->smt_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_smt_amount - $adjust_voucher->sum_lms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_smt_amount + $adjust_voucher->sum_lms : $r->s_smt_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                  $cur_worksheet->setCellValue("B".$row_count, $voucher_text."RCS SMS");
                  $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->rcs_sms - $adjust_voucher->cnt_rcs_sms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->rcs_sms + $adjust_voucher->cnt_rcs_sms : $r->rcs_sms));
                  $cur_worksheet->setCellValue("D".$row_count, $r->rcs_sms_price);
                  $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_rcs_sms_amount - $adjust_voucher->sum_rcs_sms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_rcs_sms_amount + $adjust_voucher->sum_rcs_sms : $r->s_rcs_sms_amount));
                  $row_count += 1;

                  $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                  $cur_worksheet->setCellValue("B".$row_count, $voucher_text."RCS LMS");
                  $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->rcs - $adjust_voucher->cnt_rcs_lms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->rcs + $adjust_voucher->cnt_rcs_lms : $r->rcs));
                  $cur_worksheet->setCellValue("D".$row_count, $r->rcs_price);
                  $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_rcs_amount - $adjust_voucher->sum_rcs_lms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_rcs_amount + $adjust_voucher->sum_rcs_lms : $r->s_rcs_amount));
                  $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->getStartColor()->setARGB('00D9D9D9');
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."발송 합계");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $sum_tot_send - $adjust_voucher->cnt_all : $sum_tot_send );
                $cur_worksheet->setCellValue("D".$row_count, "-");
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $sum_send_amount - $adjust_voucher->sum_all : $sum_send_amount);
                $cur_worksheet->getStyle('D'.$row_count.':'.'E'.$row_count)->getFont()->setBold(true);
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->getStartColor()->setARGB('00FFFF00');

                $cur_worksheet->setCellValue("B".$row_count, (!empty($voucher_text))? "바우처 소진" : "청구 금액");
                $cur_worksheet->setCellValue("C".$row_count, "-");
                $cur_worksheet->setCellValue("D".$row_count, "-");
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $sum_send_amount - $adjust_voucher->sum_all : $sum_send_amount);
                $cur_worksheet->getStyle('D'.$row_count.':'.'E'.$row_count)->getFont()->setBold(true);
                $row_count += 1;
        //         $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //         $cur_worksheet->setCellValue("B".$row_count, $voucher_text."알림톡");
        //         $cur_worksheet->setCellValue("C".$row_count, $r->at);
        //         $cur_worksheet->setCellValue("D".$row_count, $r->at_price);
        //         $cur_worksheet->setCellValue("E".$row_count, $r->s_at_amount);
        //         $row_count += 1;
        //
        //         $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //         $cur_worksheet->setCellValue("B".$row_count, $voucher_text."친구톡(텍스트)");
        //         $cur_worksheet->setCellValue("C".$row_count, $r->ft);
        //         $cur_worksheet->setCellValue("D".$row_count, $r->ft_price);
        //         $cur_worksheet->setCellValue("E".$row_count, $r->s_ft_amount);
        //         $row_count += 1;
        //
        //         $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //         $cur_worksheet->setCellValue("B".$row_count, $voucher_text."친구톡(이미지)");
        //         $cur_worksheet->setCellValue("C".$row_count, $r->ft_img);
        //         $cur_worksheet->setCellValue("D".$row_count, $r->ft_img_price);
        //         $cur_worksheet->setCellValue("E".$row_count, $r->s_ft_img_amount);
        //         $row_count += 1;
        //
        //         // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //         // $cur_worksheet->setCellValue("B".$row_count, "친구톡(와이드)");
        //         // $cur_worksheet->setCellValue("C".$row_count, $r->ft_w_img);
        //         // $cur_worksheet->setCellValue("D".$row_count, $r->ft_w_img_price);
        //         // $cur_worksheet->setCellValue("E".$row_count, $r->s_ft_w_img_amount);
        //         // $row_count += 1;
        //
        //         if ($r->grs_price == $r->nas_price && $r->grs_price == $r->smt_price) {
        //             $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //             $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자)");
        //             $cur_worksheet->setCellValue("C".$row_count, $r->grs + $r->nas + $r->smt);
        //             $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
        //             $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount + $r->s_nas_amount + $r->s_smt_amount);
        //             $row_count += 1;
        //         } else {
        //             if ($r->nas == 0 && $r->smt == 0) {
        //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                 $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자)");
        //                 $cur_worksheet->setCellValue("C".$row_count, $r->grs);
        //                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
        //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount);
        //                 $row_count += 1;
        //             } else if ($r->smt == 0) {
        //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                 $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자A)");
        //                 $cur_worksheet->setCellValue("C".$row_count, $r->grs);
        //                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
        //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount);
        //                 $row_count += 1;
        //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                 $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자B)");
        //                 $cur_worksheet->setCellValue("C".$row_count, $r->nas);
        //                 $cur_worksheet->setCellValue("D".$row_count, $r->nas_price);
        //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_amount);
        //                 $row_count += 1;
        //             } else {
        //                 // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                 // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
        //                 // $cur_worksheet->setCellValue("C".$row_count, $r->grs);
        //                 // $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
        //                 // $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount);
        //                 // $row_count += 1;
        //                 // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                 // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
        //                 // $cur_worksheet->setCellValue("C".$row_count, $r->nas);
        //                 // $cur_worksheet->setCellValue("D".$row_count, $r->nas_price);
        //                 // $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_amount);
        //                 // $row_count += 1;
        //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                 $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자 LMS)");
        //                 $cur_worksheet->setCellValue("C".$row_count, $r->smt);
        //                 $cur_worksheet->setCellValue("D".$row_count, $r->smt_price);
        //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_smt_amount);
        //                 $row_count += 1;
        //             }
        //         }
        //
        //         if (($r->grs_sms + $r->nas_sms + $r->smt_sms) > 0) {
        //             if($r->grs_sms_price == $r->nas_sms_price && $r->grs_sms_price == $r->smt_sms_price) {
        //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                 $cur_worksheet->setCellValue("B".$row_count, $voucher_text."문자(SMS)");
        //                 $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms + $r->nas_sms + $r->smt_sms);
        //                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
        //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount + $r->s_nas_sms_amount + $r->s_smt_sms_amount);
        //                 $row_count += 1;
        //             } else {
        //                 if ($r->nas_sms == 0 && $r->smt_sms == 0) {
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
        //                     $row_count += 1;
        //                 } else if ($r->smt_sms == 0) {
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자A)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
        //                     $row_count += 1;
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자B)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->nas_sms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->nas_sms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_sms_amount);
        //                     $row_count += 1;
        //                 } else {
        //                     // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
        //                     // $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
        //                     // $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
        //                     // $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
        //                     // $row_count += 1;
        //                     // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
        //                     // $cur_worksheet->setCellValue("C".$row_count, $r->nas_sms);
        //                     // $cur_worksheet->setCellValue("D".$row_count, $r->nas_sms_price);
        //                     // $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_sms_amount);
        //                     // $row_count += 1;
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자 SMS)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->smt_sms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->smt_sms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_smt_sms_amount);
        //                     $row_count += 1;
        //                 }
        // //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        // //                 $cur_worksheet->setCellValue("B".$row_count, "문자A(SMS)");
        // //                 $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
        // //                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
        // //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
        // //                 $row_count += 1;
        // //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        // //                 $cur_worksheet->setCellValue("B".$row_count, "문자B(SMS)");
        // //                 $cur_worksheet->setCellValue("C".$row_count, $r->nas_sms);
        // //                 $cur_worksheet->setCellValue("D".$row_count, $r->nas_sms_price);
        // //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_sms_amount);
        // //                 $row_count += 1;
        //             }
        //         }
        //
        //         if (($r->grs_mms + $r->nas_mms + $r->smt_mms) > 0) {
        //             if($r->grs_mms_price == $r->nas_mms_price && $r->grs_mms_price == $r->smt_mms_price) {
        //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                 $cur_worksheet->setCellValue("B".$row_count, $voucher_text."문자(MMS)");
        //                 $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms + $r->nas_mms + $r->smt_mms);
        //                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
        //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount + $r->s_nas_mms_amount + $r->s_smt_mms_amount);
        //                 $row_count += 1;
        //             } else {
        //                 if ($r->nas_mms == 0 && $r->smt_mms == 0) {
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
        //                     $row_count += 1;
        //                 } else if ($r->smt_mms == 0) {
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자A)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
        //                     $row_count += 1;
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자B)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->nas_mms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->nas_mms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_mms_amount);
        //                     $row_count += 1;
        //                 } else {
        //                     // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
        //                     // $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
        //                     // $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
        //                     // $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
        //                     // $row_count += 1;
        //                     // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
        //                     // $cur_worksheet->setCellValue("C".$row_count, $r->nas_mms);
        //                     // $cur_worksheet->setCellValue("D".$row_count, $r->nas_mms_price);
        //                     // $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_mms_amount);
        //                     // $row_count += 1;
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자 MMS)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->smt_mms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->smt_mms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_smt_mms_amount);
        //                     $row_count += 1;
        //                 }
        // //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        // //                 $cur_worksheet->setCellValue("B".$row_count, "문자A(MMS)");
        // //                 $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
        // //                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
        // //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
        // //                 $row_count += 1;
        // //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        // //                 $cur_worksheet->setCellValue("B".$row_count, "문자B(MMS)");
        // //                 $cur_worksheet->setCellValue("C".$row_count, $r->nas_mms);
        // //                 $cur_worksheet->setCellValue("D".$row_count, $r->nas_mms_price);
        // //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_mms_amount);
        // //                 $row_count += 1;
        //             }
        //         }

                if(($new_ii=="M"&&$r->cnt>1)||($new_ii=="N"&&$r->cnt<2)){
                    $sql = "SELECT
                        SUM(amt_amount * amt_deduct) amt
                         FROM cb_amt_".$r->mem_userid." WHERE (FIND_IN_SET('바우처', amt_memo) OR amt_kind = '9') and amt_datetime < '".$end_date." 23:59:59'";
                    $voucher_deposit = $this->db->query($sql)->row()->amt;



                    if($r->full_care > 0) {
                        $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                        //$cur_worksheet->mergeCells("D".$row_count.":E".$row_count);
                        $cur_worksheet->setCellValue("B".$row_count, "Fullcare");
                        $cur_worksheet->setCellValue("C".$row_count, '-');
                        $cur_worksheet->setCellValue("D".$row_count, '-');
                        $cur_worksheet->setCellValue("E".$row_count, $r->full_care);
                        $cur_worksheet->getStyle('E'.$row_count.':'.'E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                        //$cur_worksheet->getStyle('E'.$row_count.':'.'E'.$row_count)->getFill()->getStartColor()->setARGB('00D9D9D9');
                        $cur_worksheet->getStyle('E'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");
                        $row_count += 1;
                    }



                    if($r->voucher_flag=="V"){
                        // $row_count += 1;
                        $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                        $cur_worksheet->setCellValue("B".$row_count, "바우처 잔액");
                        $cur_worksheet->setCellValue("C".$row_count, "-");
                        $cur_worksheet->setCellValue("D".$row_count, "-");
                        $cur_worksheet->setCellValue("E".$row_count, $voucher_deposit);

                    }


                    $cur_worksheet->getStyle('B'.$row_count.':B'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.$row_count.':B'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('C'.$row_count.':E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $cur_worksheet->getStyle('C'.$row_count.':E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                    $cur_worksheet->getStyle('B'.($start_table_row + 2).':B'.($row_count-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.($start_table_row + 2).':B'.($row_count-1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('C'.($start_table_row + 2).':E'.($row_count-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $cur_worksheet->getStyle('C'.($start_table_row + 2).':E'.($row_count-1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('C'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $cur_worksheet->getStyle('C'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $cur_worksheet->getStyle('E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                    $cur_worksheet->getStyle('C'.($start_table_row + 2).':C'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");
                    $cur_worksheet->getStyle('D'.($start_table_row + 2).':D'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                    $cur_worksheet->getStyle('E'.($start_table_row + 2).':E'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");

                    $cur_worksheet->getStyle('B'.$start_table_row.':E'.$row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                }

                $next_row_count = $row_count;
                $pre_mem_id = $r->mem_id;
                // $next_row_count = $this->excelTopTable($r, $cur_worksheet, $start_date, $end_date, $value->period_name);
                if(($new_ii=="M"&&$r->cnt>1)||($new_ii=="N"&&$r->cnt<2)){
                    $cur_worksheet->getStyle('A1');
                }


                $sheet_count += 1;
            }
        }
        $spreadsheet->setActiveSheetIndex(0);

        $filename = '월정산_'.$value->period_name;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');

        // $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function download_day_settlement() {
        $value = json_decode($this->input->post('value'));
        //log_message("ERROR", "---------------");
        //log_message("ERROR", "mem_id : ".$value->mem_id);
        //log_message("ERROR", "period_name : ".$value->period_name);
        //log_message("ERROR", "---------------");

        $sql = "
            select
            	a.period_name,
                a.end_mem_id as mem_id,
                a.voucher_flag,
                (select count(*) as cnt from cb_monthly_report vv where vv.end_mem_id = a.end_mem_id and  a.period_name = vv.period_name) as cnt,
            	REPLACE(REPLACE(b.mem_username, '_', ' '), '/', ' ') as mem_username,
                b.mem_userid,
            	b.mem_email,
            	c.mad_price_full_care as full_care,
            	a.ft,
            	a.ft_price,
            	(a.ft * a.ft_price) as s_ft_amount,
            	a.ft_img,
            	a.ft_img_price,
            	(a.ft_img * a.ft_img_price) as s_ft_img_amount,
            	a.ft_w_img,
            	a.ft_w_img_price,
            	(a.ft_w_img * a.ft_w_img_price) as s_ft_w_img_amount,
            	a.at,
            	a.at_price,
            	(a.at * a.at_price) as s_at_amount,
            	(a.grs + a.grs_biz) grs,
            	a.grs_price,
            	((a.grs + a.grs_biz) * a.grs_price) as s_grs_amount,
            	a.grs_sms,
            	a.grs_sms_price,
            	(a.grs_sms * a.grs_sms_price) as s_grs_sms_amount,
            	a.grs_mms,
            	a.grs_mms_price,
            	(a.grs_mms * a.grs_mms_price) as s_grs_mms_amount,
            	a.nas,
            	a.nas_price,
            	(a.nas * a.nas_price) as s_nas_amount,
            	a.nas_sms,
            	a.nas_sms_price,
            	(a.nas_sms * a.nas_sms_price) as s_nas_sms_amount,
            	a.nas_mms,
            	a.nas_mms_price,
            	(a.nas_mms * a.nas_mms_price) as s_nas_mms_amount,
            	a.smt,
            	a.smt_price,
            	(a.smt * a.smt_price) as s_smt_amount,
            	a.smt_sms,
            	a.smt_sms_price,
            	(a.smt_sms * a.smt_sms_price) as s_smt_sms_amount,
            	a.smt_mms,
            	a.smt_mms_price,
            	(a.smt_mms * a.smt_mms_price) as s_smt_mms_amount,

                a.rcs,
            	a.rcs_price,
            	(a.rcs * a.rcs_price) as s_rcs_amount,
            	a.rcs_sms,
            	a.rcs_sms_price,
            	(a.rcs_sms * a.rcs_sms_price) as s_rcs_sms_amount,
            	a.rcs_mms,
            	a.rcs_mms_price,
            	(a.rcs_mms * a.rcs_mms_price) as s_rcs_mms_amount,
                a.rcs_tem,
            	a.rcs_tem_price,
            	(a.rcs_tem * a.rcs_tem_price) as s_rcs_tem_amount,

            	(a.ft + a.ft_img + a.at + a.grs + a.grs_biz + a.grs_sms + a.grs_mms + a.nas + a.nas_sms + a.nas_mms + a.smt + a.smt_sms + a.smt_mms + a.rcs + a.rcs_sms + a.rcs_mms + a.rcs_tem) as tot_send,
                ((a.ft * a.ft_price) + (a.ft_img * a.ft_img_price) + (a.at * a.at_price) + ((a.grs + a.grs_biz) * a.grs_price) + (a.grs_sms * a.grs_sms_price) + (a.grs_mms * a.grs_mms_price) + (a.nas * a.nas_price) + (a.nas_sms * a.nas_sms_price) + (a.nas_mms * a.nas_mms_price) + (a.smt * a.smt_price) + (a.smt_sms * a.smt_sms_price) + (a.smt_mms * a.smt_mms_price) + (a.rcs * a.rcs_price) + (a.rcs_sms * a.rcs_sms_price) + (a.rcs_mms * a.rcs_mms_price) + (a.rcs_tem * a.rcs_tem_price)) as send_amount,
            	((a.ft * a.ft_price) + (a.ft_img * a.ft_img_price) + (a.at * a.at_price) + ((a.grs + a.grs_biz) * a.grs_price) + (a.grs_sms * a.grs_sms_price) + (a.grs_mms * a.grs_mms_price) + (a.nas * a.nas_price) + (a.nas_sms * a.nas_sms_price) + (a.nas_mms * a.nas_mms_price) + (a.smt * a.smt_price) + (a.smt_sms * a.smt_sms_price) + (a.smt_mms * a.smt_mms_price) + (a.rcs * a.rcs_price) + (a.rcs_sms * a.rcs_sms_price) + (a.rcs_mms * a.rcs_mms_price) + (a.rcs_tem * a.rcs_tem_price) + c.mad_price_full_care) as tot_amount
            from cb_monthly_report a left join
                 cb_member b on a.end_mem_id = b.mem_id left join
                 cb_wt_member_addon c on a.end_mem_id = c.mad_mem_id
            where a.period_name='".$value->period_name."' and a.end_mem_id in (".$value->mem_id.")
            order by b.mem_id, voucher_flag;
        ";

        //log_message("ERROR", $value->mem_id);
        log_message("ERROR", $sql);
        $result_query = $this->db->query($sql)->result();
        $sheet_count = 0;
        $start_date = $value->period_name.'-01';
        $end_date = $value->period_name.'-'.date('t', strtotime($start_date));
        $SD = $start_date;
        $ED = $end_date;
        $start_date .= ' 00:00:00';
        $end_date .= ' 23:59:59';

        $spreadsheet = new Spreadsheet();
        if ($result_query) {
            $pre_mem_id ="";
            $new_ii="N";

            $sum_tot_send = 0;
            $sum_send_amount = 0;
            foreach ($result_query as $r) {
                // if ($sheet_count == 0) {
                //     $cur_worksheet = $spreadsheet->getActiveSheet();
                // } else {
                //     $cur_worksheet = $spreadsheet->createSheet();
                // }
                $voucher_text = "";
                if ($sheet_count == 0){
                    $cur_worksheet = $spreadsheet->getActiveSheet();
                    $new_ii="N";
                }else{
                    if($pre_mem_id!=$r->mem_id){
                        $new_ii="N";
                        $cur_worksheet = $spreadsheet->createSheet();
                    }else{
                        if($r->cnt<2){
                            $new_ii="N";
                            $cur_worksheet = $spreadsheet->createSheet();
                        }else{
                            $new_ii="M";
                            $cur_worksheet = $spreadsheet->getActiveSheet();
                        }
                    }
                }

                $sql = "select count(1) as cnt from cb_kvoucher_deposit ckd where ckd.kvd_mem_id =  ".$r->mem_id." and ckd.kvd_proc_flag = 'Y' and ckd.kvd_minus_date BETWEEN '".$start_date."' AND '".$end_date."'";
                $voucher_blank_cnt = $this->db->query($sql)->row()->cnt;

                if($voucher_blank_cnt>0){
                    $sql = "select
                    count(1) as cnt_all,
                    sum(amt_amount) as sum_all,
                   sum(case when amt_kind = 'A' THEN 1 else 0 end) as cnt_a,
                   sum(case when amt_kind = 'F' THEN 1 else 0 end) as cnt_f,
                   sum(case when amt_kind = 'I' THEN 1 else 0 end) as cnt_i,
                   sum(case when (amt_kind = 'P' and amt_memo='웹(C) SMS,조정') THEN 1 else 0 end) as cnt_sms,
                   sum(case when (amt_kind = 'P' and amt_memo='웹(C) LMS,조정') THEN 1 else 0 end) as cnt_lms,

                  sum(case when (amt_kind = 'R' and amt_memo='RCS SMS,조정') THEN 1 else 0 end) as cnt_rcs_sms,
                  sum(case when (amt_kind = 'R' and amt_memo='RCS LMS,조정') THEN 1 else 0 end) as cnt_rcs_lms,

                   sum(case when amt_kind = 'A' THEN amt_amount else 0 end) as sum_a,
                   sum(case when amt_kind = 'F' THEN amt_amount else 0 end) as sum_f,
                   sum(case when amt_kind = 'I' THEN amt_amount else 0 end) as sum_i,
                   sum(case when (amt_kind = 'P' and amt_memo='웹(C) SMS,조정') THEN amt_amount else 0 end) as sum_sms,
                   sum(case when (amt_kind = 'P' and amt_memo='웹(C) LMS,조정') THEN amt_amount else 0 end) as sum_lms,

                   sum(case when (amt_kind = 'R' and amt_memo='RCS SMS,조정') THEN amt_amount else 0 end) as sum_rcs_sms,
                   sum(case when (amt_kind = 'R' and amt_memo='RCS LMS,조정') THEN amt_amount else 0 end) as sum_rcs_lms
                   from cb_amt_".$r->mem_userid." ca where  FIND_IN_SET('조정', amt_memo) ";
                   $adjust_voucher = $this->db->query($sql)->row();
                }

                //합계처리
                // if($new_ii == "M"){
                //     $sum_tot_send = $sum_tot_send + $r->tot_send;
                //     $sum_send_amount = $sum_send_amount + $r->send_amount;
                // }else{
                //     $sum_tot_send = $r->tot_send;
                //     $sum_send_amount = $r->send_amount;
                // }

                $sum_tot_send = $r->tot_send;
                $sum_send_amount = $r->send_amount;
                //바우처 여부 처리
                if($r->voucher_flag=="V"){
                    $voucher_text = "[바우처]";
                }

                if(($new_ii=="N"&&$r->cnt<2)||$sheet_count == 0){
                    $cur_worksheet->getColumnDimension('A')->setWidth(1.38 + 0.63);
                    $cur_worksheet->getColumnDimension('B')->setWidth(25);
                    $cur_worksheet->getColumnDimension('C')->setWidth(19.75 + 0.63);
                    $cur_worksheet->getColumnDimension('D')->setWidth(17.50 + 0.63);
                    $cur_worksheet->getColumnDimension('E')->setWidth(17.50 + 0.63);
                    $cur_worksheet->getRowDimension(2)->setRowHeight(25);

                    $cur_worksheet->setTitle($r->mem_username.'_'.$value->period_name)
                    ->mergeCells("B2:E2")
                    ->setCellValue("B2", str_replace('_',' ',$r->mem_username).'('.$r->mem_email.')');
                    $cur_worksheet->getStyle("B2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle("B2")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle("B2")->getFont()->setSize(13);

                    $cur_worksheet->getRowDimension(4)->setRowHeight(18);
                    $cur_worksheet->mergeCells("D4:E4")->setCellValue("D4", "기간 : ".$SD." ~ ".$ED);
                    $cur_worksheet->getStyle("D4")->getFont()->setBold(true);
                    $cur_worksheet->getStyle("D4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle("D4")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('D4:E4')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle('D4:E4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle('D4:E4')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle('D4:E4')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle('D4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle("D4")->getFill()->getStartColor()->setARGB('00F2F2F2');

                    $start_table_row = 5;
                    $cur_worksheet->getRowDimension(5)->setRowHeight(15);
                    $cur_worksheet->getRowDimension(6)->setRowHeight(15);
                    $cur_worksheet->mergeCells("B5:B6");
                    $cur_worksheet->mergeCells("C5:C6");
                    $cur_worksheet->mergeCells("D5:D6");
                    $cur_worksheet->mergeCells("E5:E6");
                    $cur_worksheet->getStyle("B5:E6")->getFont()->getColor()->setARGB('007D604F');
                    $cur_worksheet->getStyle("B5:E6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle("B5:E6")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('B5:E6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle("B5:E6")->getFill()->getStartColor()->setARGB('00D9D9D9');
                    $cur_worksheet->setCellValue("B5", "항목");
                    $cur_worksheet->setCellValue("C5", "발송건수");
                    $cur_worksheet->setCellValue("D5", "단가(VAT포함)");
                    $cur_worksheet->setCellValue("E5", "금액(VAT포함)");

                    $row_count = 7;
                }
                if($voucher_blank_cnt>0&&$r->cnt=="1"){
                    $sql = "select mad_price_at, mad_price_ft, mad_price_ft_img, mad_price_smt_sms, mad_price_smt from cb_wt_member_addon where mad_mem_id = '".$r->mem_id."'";
                    $m_addon = $this->db->query($sql)->row();

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "알림톡");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_a);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_at);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_a);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "친구톡(텍스트)");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_f);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_ft);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_f);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "친구톡(이미지)");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_i);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_ft_img);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_i);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "문자 SMS");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_sms);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_smt_sms);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_sms);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "문자 LMS");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_lms);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_smt);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_lms);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "RCS SMS");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_rcs_sms);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_rcs_sms);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_rcs_sms);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "RCS LMS");
                    $cur_worksheet->setCellValue("C".$row_count, $adjust_voucher->cnt_rcs_lms);
                    $cur_worksheet->setCellValue("D".$row_count, $m_addon->mad_price_rcs);
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_rcs_lms);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->getStartColor()->setARGB('00D9D9D9');
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->setCellValue("B".$row_count, "발송 합계");
                    $cur_worksheet->setCellValue("C".$row_count,  $adjust_voucher->cnt_all );
                    $cur_worksheet->setCellValue("D".$row_count, "-");
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_all);
                    $cur_worksheet->getStyle('D'.$row_count.':'.'E'.$row_count)->getFont()->setBold(true);
                    $row_count += 1;

                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->getStartColor()->setARGB('00FFFF00');

                    $cur_worksheet->setCellValue("B".$row_count, "청구 금액");
                    $cur_worksheet->setCellValue("C".$row_count, "-");
                    $cur_worksheet->setCellValue("D".$row_count, "-");
                    $cur_worksheet->setCellValue("E".$row_count, $adjust_voucher->sum_all);
                    $cur_worksheet->getStyle('D'.$row_count.':'.'E'.$row_count)->getFont()->setBold(true);
                    $row_count += 1;

                }


                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."알림톡");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->at - $adjust_voucher->cnt_a : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->at + $adjust_voucher->cnt_a : $r->at));
                $cur_worksheet->setCellValue("D".$row_count, $r->at_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_at_amount - $adjust_voucher->sum_a : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_at_amount + $adjust_voucher->sum_a : $r->s_at_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."친구톡(텍스트)");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->ft - $adjust_voucher->cnt_f : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->ft + $adjust_voucher->cnt_f : $r->ft));
                $cur_worksheet->setCellValue("D".$row_count, $r->ft_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_ft_amount - $adjust_voucher->sum_f : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_ft_amount + $adjust_voucher->sum_f : $r->s_ft_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."친구톡(이미지)");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->ft_img - $adjust_voucher->cnt_i : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->ft_img + $adjust_voucher->cnt_i : $r->ft_img));
                $cur_worksheet->setCellValue("D".$row_count, $r->ft_img_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_ft_img_amount - $adjust_voucher->sum_i : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_ft_img_amount + $adjust_voucher->sum_i : $r->s_ft_img_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."문자 SMS");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->smt_sms - $adjust_voucher->cnt_sms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->smt_sms + $adjust_voucher->cnt_sms : $r->smt_sms));
                $cur_worksheet->setCellValue("D".$row_count, $r->smt_sms_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_smt_sms_amount - $adjust_voucher->sum_sms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_smt_sms_amount + $adjust_voucher->sum_sms : $r->s_smt_sms_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."문자 LMS");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->smt - $adjust_voucher->cnt_lms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->smt + $adjust_voucher->cnt_lms : $r->smt));
                $cur_worksheet->setCellValue("D".$row_count, $r->smt_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_smt_amount - $adjust_voucher->sum_lms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_smt_amount + $adjust_voucher->sum_lms : $r->s_smt_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."RCS SMS");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->rcs_sms - $adjust_voucher->cnt_rcs_sms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->rcs_sms + $adjust_voucher->cnt_rcs_sms : $r->rcs_sms));
                $cur_worksheet->setCellValue("D".$row_count, $r->rcs_sms_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_rcs_sms_amount - $adjust_voucher->sum_rcs_sms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_rcs_sms_amount + $adjust_voucher->sum_rcs_sms : $r->s_rcs_sms_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."RCS LMS");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->rcs - $adjust_voucher->cnt_rcs_lms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->rcs + $adjust_voucher->cnt_rcs_lms : $r->rcs));
                $cur_worksheet->setCellValue("D".$row_count, $r->rcs_price);
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $r->s_rcs_amount - $adjust_voucher->sum_rcs_lms : ((empty($voucher_text)&&$voucher_blank_cnt>0&&$r->cnt>1)? $r->s_rcs_amount + $adjust_voucher->sum_rcs_lms : $r->s_rcs_amount));
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->getStartColor()->setARGB('00D9D9D9');
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->setCellValue("B".$row_count, $voucher_text."발송 합계");
                $cur_worksheet->setCellValue("C".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $sum_tot_send - $adjust_voucher->cnt_all : $sum_tot_send );
                $cur_worksheet->setCellValue("D".$row_count, "-");
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $sum_send_amount - $adjust_voucher->sum_all : $sum_send_amount);
                $cur_worksheet->getStyle('D'.$row_count.':'.'E'.$row_count)->getFont()->setBold(true);
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getFill()->getStartColor()->setARGB('00FFFF00');

                $cur_worksheet->setCellValue("B".$row_count, (!empty($voucher_text))? "바우처 소진" : "청구 금액");
                $cur_worksheet->setCellValue("C".$row_count, "-");
                $cur_worksheet->setCellValue("D".$row_count, "-");
                $cur_worksheet->setCellValue("E".$row_count, (!empty($voucher_text)&&$voucher_blank_cnt>0)? $sum_send_amount - $adjust_voucher->sum_all : $sum_send_amount);
                $cur_worksheet->getStyle('D'.$row_count.':'.'E'.$row_count)->getFont()->setBold(true);
                $row_count += 1;


                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->setCellValue("B".$row_count, "친구톡(와이드)");
                // $cur_worksheet->setCellValue("C".$row_count, $r->ft_w_img);
                // $cur_worksheet->setCellValue("D".$row_count, $r->ft_w_img_price);
                // $cur_worksheet->setCellValue("E".$row_count, $r->s_ft_w_img_amount);
                // $row_count += 1;

                // if ($r->grs_price == $r->nas_price && $r->grs_price == $r->smt_price) {
                //     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                //     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자)");
                //     $cur_worksheet->setCellValue("C".$row_count, $r->grs + $r->nas + $r->smt);
                //     $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
                //     $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount + $r->s_nas_amount + $r->s_smt_amount);
                //     $row_count += 1;
                // } else {
                //     if ($r->nas == 0 && $r->smt == 0) {
                //         $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                //         $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자)");
                //         $cur_worksheet->setCellValue("C".$row_count, $r->grs);
                //         $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
                //         $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount);
                //         $row_count += 1;
                //     } else if ($r->smt == 0) {
                //         $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                //         $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자A)");
                //         $cur_worksheet->setCellValue("C".$row_count, $r->grs);
                //         $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
                //         $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount);
                //         $row_count += 1;
                //         $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                //         $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자B)");
                //         $cur_worksheet->setCellValue("C".$row_count, $r->nas);
                //         $cur_worksheet->setCellValue("D".$row_count, $r->nas_price);
                //         $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_amount);
                //         $row_count += 1;
                //     } else {
                //         // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                //         // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
                //         // $cur_worksheet->setCellValue("C".$row_count, $r->grs);
                //         // $cur_worksheet->setCellValue("D".$row_count, $r->grs_price);
                //         // $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_amount);
                //         // $row_count += 1;
                //         // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                //         // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
                //         // $cur_worksheet->setCellValue("C".$row_count, $r->nas);
                //         // $cur_worksheet->setCellValue("D".$row_count, $r->nas_price);
                //         // $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_amount);
                //         // $row_count += 1;
                //         $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                //         $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자 LMS)");
                //         $cur_worksheet->setCellValue("C".$row_count, $r->smt);
                //         $cur_worksheet->setCellValue("D".$row_count, $r->smt_price);
                //         $cur_worksheet->setCellValue("E".$row_count, $r->s_smt_amount);
                //         $row_count += 1;
                //     }
                // }

        //         if (($r->grs_sms + $r->nas_sms + $r->smt_sms) > 0) {
        //             if($r->grs_sms_price == $r->nas_sms_price && $r->grs_sms_price == $r->smt_sms_price) {
        //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                 $cur_worksheet->setCellValue("B".$row_count, $voucher_text."문자(SMS)");
        //                 $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms + $r->nas_sms + $r->smt_sms);
        //                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
        //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount + $r->s_nas_sms_amount + $r->s_smt_sms_amount);
        //                 $row_count += 1;
        //             } else {
        //                 if ($r->nas_sms == 0 && $r->smt_sms == 0) {
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
        //                     $row_count += 1;
        //                 } else if ($r->smt_sms == 0) {
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자A)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
        //                     $row_count += 1;
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자B)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->nas_sms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->nas_sms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_sms_amount);
        //                     $row_count += 1;
        //                 } else {
        //                     // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
        //                     // $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
        //                     // $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
        //                     // $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
        //                     // $row_count += 1;
        //                     // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
        //                     // $cur_worksheet->setCellValue("C".$row_count, $r->nas_sms);
        //                     // $cur_worksheet->setCellValue("D".$row_count, $r->nas_sms_price);
        //                     // $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_sms_amount);
        //                     // $row_count += 1;
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자 SMS)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->smt_sms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->smt_sms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_smt_sms_amount);
        //                     $row_count += 1;
        //                 }
        // //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        // //                 $cur_worksheet->setCellValue("B".$row_count, "문자A(SMS)");
        // //                 $cur_worksheet->setCellValue("C".$row_count, $r->grs_sms);
        // //                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_sms_price);
        // //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_sms_amount);
        // //                 $row_count += 1;
        // //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        // //                 $cur_worksheet->setCellValue("B".$row_count, "문자B(SMS)");
        // //                 $cur_worksheet->setCellValue("C".$row_count, $r->nas_sms);
        // //                 $cur_worksheet->setCellValue("D".$row_count, $r->nas_sms_price);
        // //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_sms_amount);
        // //                 $row_count += 1;
        //             }
        //         }

        //         if (($r->grs_mms + $r->nas_mms + $r->smt_mms) > 0) {
        //             if($r->grs_mms_price == $r->nas_mms_price && $r->grs_mms_price == $r->smt_mms_price) {
        //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                 $cur_worksheet->setCellValue("B".$row_count, $voucher_text."문자(MMS)");
        //                 $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms + $r->nas_mms + $r->smt_mms);
        //                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
        //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount + $r->s_nas_mms_amount + $r->s_smt_mms_amount);
        //                 $row_count += 1;
        //             } else {
        //                 if ($r->nas_mms == 0 && $r->smt_mms == 0) {
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
        //                     $row_count += 1;
        //                 } else if ($r->smt_mms == 0) {
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자A)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
        //                     $row_count += 1;
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자B)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->nas_mms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->nas_mms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_mms_amount);
        //                     $row_count += 1;
        //                 } else {
        //                     // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자A)");
        //                     // $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
        //                     // $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
        //                     // $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
        //                     // $row_count += 1;
        //                     // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     // $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자B)");
        //                     // $cur_worksheet->setCellValue("C".$row_count, $r->nas_mms);
        //                     // $cur_worksheet->setCellValue("D".$row_count, $r->nas_mms_price);
        //                     // $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_mms_amount);
        //                     // $row_count += 1;
        //                     $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        //                     $cur_worksheet->setCellValue("B".$row_count, $voucher_text."2차 발신(문자 MMS)");
        //                     $cur_worksheet->setCellValue("C".$row_count, $r->smt_mms);
        //                     $cur_worksheet->setCellValue("D".$row_count, $r->smt_mms_price);
        //                     $cur_worksheet->setCellValue("E".$row_count, $r->s_smt_mms_amount);
        //                     $row_count += 1;
        //                 }
        // //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        // //                 $cur_worksheet->setCellValue("B".$row_count, "문자A(MMS)");
        // //                 $cur_worksheet->setCellValue("C".$row_count, $r->grs_mms);
        // //                 $cur_worksheet->setCellValue("D".$row_count, $r->grs_mms_price);
        // //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_grs_mms_amount);
        // //                 $row_count += 1;
        // //                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        // //                 $cur_worksheet->setCellValue("B".$row_count, "문자B(MMS)");
        // //                 $cur_worksheet->setCellValue("C".$row_count, $r->nas_mms);
        // //                 $cur_worksheet->setCellValue("D".$row_count, $r->nas_mms_price);
        // //                 $cur_worksheet->setCellValue("E".$row_count, $r->s_nas_mms_amount);
        // //                 $row_count += 1;
        //             }
        //         }

                if(($new_ii=="M"&&$r->cnt>1)||($new_ii=="N"&&$r->cnt<2)){
                    $sql = "SELECT
            			SUM(amt_amount * amt_deduct) amt
            			 FROM cb_amt_".$r->mem_userid." WHERE (FIND_IN_SET('바우처', amt_memo) OR amt_kind = '9') and amt_datetime < '".$end_date."'";
                    $voucher_deposit = $this->db->query($sql)->row()->amt;



                    if($r->full_care > 0) {
                        $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                        //$cur_worksheet->mergeCells("D".$row_count.":E".$row_count);
                        $cur_worksheet->setCellValue("B".$row_count, "Fullcare");
                        $cur_worksheet->setCellValue("C".$row_count, '-');
                        $cur_worksheet->setCellValue("D".$row_count, '-');
                        $cur_worksheet->setCellValue("E".$row_count, $r->full_care);
                        $cur_worksheet->getStyle('E'.$row_count.':'.'E'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                        //$cur_worksheet->getStyle('E'.$row_count.':'.'E'.$row_count)->getFill()->getStartColor()->setARGB('00D9D9D9');
                        $cur_worksheet->getStyle('E'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");
                        $row_count += 1;
                    }



                    if($r->voucher_flag=="V"){
                        // $row_count += 1;
                        $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                        $cur_worksheet->setCellValue("B".$row_count, "바우처 잔액");
                        $cur_worksheet->setCellValue("C".$row_count, "-");
                        $cur_worksheet->setCellValue("D".$row_count, "-");
                        $cur_worksheet->setCellValue("E".$row_count, $voucher_deposit);

                    }


                    $cur_worksheet->getStyle('B'.$row_count.':B'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.$row_count.':B'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('C'.$row_count.':E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $cur_worksheet->getStyle('C'.$row_count.':E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                    $cur_worksheet->getStyle('B'.($start_table_row + 2).':B'.($row_count-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.($start_table_row + 2).':B'.($row_count-1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('C'.($start_table_row + 2).':E'.($row_count-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $cur_worksheet->getStyle('C'.($start_table_row + 2).':E'.($row_count-1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('C'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $cur_worksheet->getStyle('C'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $cur_worksheet->getStyle('E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                    $cur_worksheet->getStyle('C'.($start_table_row + 2).':C'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");
                    $cur_worksheet->getStyle('D'.($start_table_row + 2).':D'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                    $cur_worksheet->getStyle('E'.($start_table_row + 2).':E'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");

                    $cur_worksheet->getStyle('B'.$start_table_row.':E'.$row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                }
                $next_row_count = $row_count;
                $pre_mem_id = $r->mem_id;
                // $next_row_count = $this->excelTopTable($r, $cur_worksheet, $start_date, $end_date, $value->period_name);
                if(($new_ii=="M"&&$r->cnt>1)||($new_ii=="N"&&$r->cnt<2)){
                    $cur_worksheet->getStyle('A1');
                }
                //$next_row_count = $this->excelTopTable($r, $cur_worksheet, $SD, $ED, 6, $value->period_name);
                // $next_row_count = $this->excelTopTable($r, $cur_worksheet, $SD, $ED, $value->period_name);
//                 $sql_day = "
//                 select
//                 	substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 10) mst_datetime,
//                 	sum(mst_qty) mst_qty,
//                  	(sum(mst_ft) + sum(mst_ft_img) + sum(mst_at) + sum(mst_grs) + sum(mst_grs_sms) + sum(mst_grs_mms) + sum(mst_nas) + sum(mst_nas_sms) + sum(mst_nas_mms)) success_qty,
//                 	(sum(mst_err_ft) + sum(mst_err_ft_img) + sum(mst_err_at) + sum(mst_err_grs) + sum(mst_err_grs_sms) + sum(mst_err_grs_mms) + sum(mst_err_nas) + sum(mst_err_nas_sms) + sum(mst_err_nas_mms)) err_qty,
//                	    sum(mst_ft) mst_ft,
//                 	sum(mst_ft_img) mst_ft_img,
//                 	sum(mst_at) mst_at,
//                 	sum(mst_sms) mst_sms,
//                 	sum(mst_lms) mst_lms,
//                 	sum(mst_mms) mst_mms,
//                     sum(mst_grs) mst_grs,
//                     sum(mst_grs_sms) mst_grs_sms,
//                     sum(mst_grs_mms) mst_grs_mms,
//                     sum(mst_nas) mst_nas,
//                     sum(mst_nas_sms) mst_nas_sms,
//                     sum(mst_nas_mms) mst_nas_mms,
//                     sum(mst_015) mst_015,
//                 	sum(mst_phn) mst_phn,
//                 	sum(mst_err_at) err_at,
//                 	sum(mst_err_ft) err_ft,
//                 	sum(mst_err_ft_img) err_ft_img,
//                 	sum(mst_err_sms) qty_sms,
//                 	sum(mst_err_lms) qty_lms,
//                 	sum(mst_err_mms) qty_mms,
//                     sum(mst_err_grs) qty_grs,
//                     sum(mst_err_grs_sms) qty_grs_sms,
//                     sum(mst_err_grs_mms) qty_grs_mms,
//                     sum(mst_err_nas) qty_nas,
//                     sum(mst_err_nas_sms) qty_nas_sms,
//                     sum(mst_err_nas_mms) qty_nas_mms,
//                     sum(mst_err_015) qty_015,
//                 	sum(mst_err_phn) qty_phn
//                 from cb_wt_msg_sent where mst_mem_id = ".$r->mem_id."     and (
//                         	 ( mst_reserved_dt <> '00000000000000' AND
//                               (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$start_date."' AND '$end_date')
//                              ) OR
//                              ( mst_reserved_dt = '00000000000000' AND
//                               (mst_datetime BETWEEN '".$start_date."' AND '$end_date')
//                              )
//                             ) group by substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 10) order by 1 desc
//                 ";
                if(($new_ii=="M"&&$r->cnt>1)||($new_ii=="N"&&$r->cnt<2)){
                    $normal_blank_cnt = 0;
                    if($voucher_blank_cnt>0){
                        $sql = "select substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 10) mst_datetime
                   from cb_wt_msg_sent where mst_mem_id = ".$r->mem_id."     and (
                                ( mst_reserved_dt <> '00000000000000' AND
                                 (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$start_date."' AND '".$end_date."')
                                ) OR
                                ( mst_reserved_dt = '00000000000000' AND
                                 (mst_datetime BETWEEN '".$start_date."' AND '".$end_date."') )) and mst_sent_voucher = 'V' order by mst_id desc limit 1";

                        $voucher_blank_date = $this->db->query($sql)->row()->mst_datetime;

                        $sql = "select count(1) as cnt
                   from cb_wt_msg_sent where mst_mem_id = ".$r->mem_id."     and (
                                ( mst_reserved_dt <> '00000000000000' AND
                                 (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$$voucher_blank_date." 00:00:00' AND '".$$voucher_blank_date." 23:59:59')
                                ) OR
                                ( mst_reserved_dt = '00000000000000' AND
                                 (mst_datetime BETWEEN '".$voucher_blank_date." 00:00:00' AND '".$voucher_blank_date." 23:59:59') )) and mst_sent_voucher = 'N' order by mst_id desc limit 1";
                        $normal_blank_cnt = $this->db->query($sql)->row()->cnt;
                    }


                    $sql_day = "
                    select
                        mst_sent_voucher,
                    	mst_datetime,
                    	sum(mst_qty) mst_qty,
                     	(sum(mst_ft) + sum(mst_ft_img) + sum(mst_ft_w_img) + sum(mst_at) + sum(mst_grs) + sum(mst_grs_sms) + sum(mst_grs_mms) + sum(mst_nas) + sum(mst_nas_sms) + sum(mst_nas_mms) + sum(mst_smt) + sum(mst_smt_sms) + sum(mst_smt_mms) + sum(mst_rcs) + sum(mst_rcs_sms) + sum(mst_rcs_mms) + sum(mst_rcs_tem)) success_qty,
                    	(sum(mst_err_ft) + sum(mst_err_ft_img) + sum(mst_err_ft_w_img) + sum(mst_err_at) + sum(mst_err_grs) + sum(mst_err_grs_sms) + sum(mst_err_grs_mms) + sum(mst_err_nas) + sum(mst_err_nas_sms) + sum(mst_err_nas_mms) + sum(mst_err_smt) + sum(mst_err_smt_sms) + sum(mst_err_smt_mms) + sum(mst_err_rcs) + sum(mst_err_rcs_sms) + sum(mst_err_rcs_mms) + sum(mst_err_rcs_tem)) err_qty,
                   	    sum(mst_ft) mst_ft,
                    	sum(mst_ft_img) mst_ft_img,
                        sum(mst_ft_w_img) mst_ft_w_img,
                    	sum(mst_at) mst_at,
                    	sum(mst_sms) mst_sms,
                    	sum(mst_lms) mst_lms,
                    	sum(mst_mms) mst_mms,
                        sum(mst_grs) mst_grs,
                        sum(mst_grs_sms) mst_grs_sms,
                        sum(mst_grs_mms) mst_grs_mms,
                        sum(mst_grs_biz_qty) mst_grs_biz,
                        sum(mst_nas) mst_nas,
                        sum(mst_nas_sms) mst_nas_sms,
                        sum(mst_nas_mms) mst_nas_mms,
                        sum(mst_smt) mst_smt,
                        sum(mst_smt_sms) mst_smt_sms,
                        sum(mst_smt_mms) mst_smt_mms,

                        sum(mst_rcs) mst_rcs,
                        sum(mst_rcs_sms) mst_rcs_sms,
                        sum(mst_rcs_mms) mst_rcs_mms,
                        sum(mst_rcs_tem) mst_rcs_tem,

                        sum(mst_015) mst_015,
                    	sum(mst_phn) mst_phn,
                    	sum(mst_err_at) err_at,
                    	sum(mst_err_ft) err_ft,
                    	sum(mst_err_ft_img) err_ft_img,
                        sum(mst_err_ft_w_img) err_ft_w_img,
                    	sum(mst_err_sms) qty_sms,
                    	sum(mst_err_lms) qty_lms,
                    	sum(mst_err_mms) qty_mms,
                        sum(mst_err_grs) qty_grs,
                        sum(mst_err_grs_sms) qty_grs_sms,
                        sum(mst_err_grs_mms) qty_grs_mms,
                        sum(mst_err_nas) qty_nas,
                        sum(mst_err_nas_sms) qty_nas_sms,
                        sum(mst_err_nas_mms) qty_nas_mms,
                        sum(mst_err_smt) qty_smt,
                        sum(mst_err_smt_sms) qty_smt_sms,
                        sum(mst_err_smt_mms) qty_smt_mms,

                        sum(mst_err_rcs) qty_rcs,
                        sum(mst_err_rcs_sms) qty_rcs_sms,
                        sum(mst_err_rcs_mms) qty_rcs_mms,
                        sum(mst_err_rcs_tem) qty_rcs_tem,

                        sum(mst_err_015) qty_015,
                    	sum(mst_err_phn) qty_phn
                    from (select substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 10) mst_datetime,
                        mst_sent_voucher,
                        mst_qty,
    					mst_ft,
    					case when (mst_type1 = 'fti') then mst_ft_img else 0 end as mst_ft_img,
                        case when (mst_type1 = 'ftw') then mst_ft_img else 0 end as mst_ft_w_img,
    					mst_at,
    					mst_sms,
    					mst_lms,
    					mst_mms,
    					case when (mst_type2 = 'wa') then mst_grs else 0 end as mst_grs,
    					mst_grs_sms,
                        mst_grs_biz_qty,
    					case when (mst_type2 = 'wb') then mst_nas else 0 end as mst_nas,
    					mst_nas_sms,
    					mst_015,
    					mst_phn,
    					case when (mst_type2 = 'wam') then mst_grs else 0 end as mst_grs_mms,
    					case when (mst_type2 = 'wbm') then mst_nas else 0 end as mst_nas_mms,
    					case when (mst_type2 = 'wc' or mst_type3 = 'wc') then mst_smt else 0 end as mst_smt,
    					case when (mst_type2 = 'wcs' or mst_type3 = 'wcs') then mst_smt else 0 end as mst_smt_sms,
    					case when (mst_type2 = 'wcm' or mst_type3 = 'wcm') then mst_smt else 0 end as mst_smt_mms,

                        case when (mst_type2 = 'rc') then mst_rcs else 0 end as mst_rcs,
    					case when (mst_type2 = 'rcs') then mst_rcs else 0 end as mst_rcs_sms,
    					case when (mst_type2 = 'rcm') then mst_rcs else 0 end as mst_rcs_mms,
                        case when (mst_type2 = 'rct') then mst_rcs else 0 end as mst_rcs_tem,

    					mst_err_at,
    					mst_err_ft,
    					case when (mst_type1 = 'fti') then mst_err_ft_img else 0 end as mst_err_ft_img,
                        case when (mst_type1 = 'ftw') then mst_err_ft_img else 0 end as mst_err_ft_w_img,
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
    					case when (mst_type2 = 'wc') then mst_err_smt else 0 end as mst_err_smt,
    					case when (mst_type2 = 'wcs') then mst_err_smt else 0 end as mst_err_smt_sms,
    					case when (mst_type2 = 'wcm') then mst_err_smt else 0 end as mst_err_smt_mms,

                        case when (mst_type2 = 'wc') then mst_err_rcs else 0 end as mst_err_rcs,
    					case when (mst_type2 = 'wcs') then mst_err_rcs else 0 end as mst_err_rcs_sms,
    					case when (mst_type2 = 'wcm') then mst_err_rcs else 0 end as mst_err_rcs_mms,
                        case when (mst_type2 = 'wct') then mst_err_rcs else 0 end as mst_err_rcs_tem

    				from cb_wt_msg_sent where mst_mem_id = ".$r->mem_id."     and (
                            	 ( mst_reserved_dt <> '00000000000000' AND
                                  (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$start_date."' AND '".$end_date."')
                                 ) OR
                                 ( mst_reserved_dt = '00000000000000' AND
                                  (mst_datetime BETWEEN '".$start_date."' AND '".$end_date."')
                                 )
                                )) AAA
                   group by mst_datetime, mst_sent_voucher order by mst_datetime ASC, mst_sent_voucher DESC
                    ";
                    log_message("ERROR", $sql_day);
                    $sel_col = explode(',', $value->col_part_name);
                    //$sel_col = split($value->col_part_name, ',');
                    $sel_col_count = count($sel_col);

                    $cells = array(
                        '', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
                    );

                    $next_row_count += 2;

                    $view_col_index = $sel_col_count + 5;

                    $cur_worksheet->mergeCells('B'.$next_row_count.':'.$cells[$view_col_index].$next_row_count)->setCellValue('B'.$next_row_count, "발 송 통 계");
                    $cur_worksheet->getStyle('B'.$next_row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.$next_row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('B'.$next_row_count)->getFont()->setSize(13);
                    $cur_worksheet->getRowDimension($next_row_count)->setRowHeight(25);
                    $cur_worksheet->getStyle('B'.$next_row_count)->getFont()->setBold(true);

                    $next_row_count += 2;
                    $start_table_row = $next_row_count;

                    // Table Header 부분 처리
                    $cur_worksheet->setCellValue('B'.$next_row_count, "발송일");
                    $cur_worksheet->setCellValue('C'.$next_row_count, "총 발송요청");
                    $cur_worksheet->setCellValue('D'.$next_row_count, "발송성공");
                    $cur_worksheet->setCellValue('E'.$next_row_count, "발송실패");
                    $cur_worksheet->mergeCells('F'.$next_row_count.':'.$cells[$view_col_index].$next_row_count)->setCellValue('F'.$next_row_count, "발 송 성 공");
                    $cur_worksheet->getStyle("B".$next_row_count.":".$cells[$view_col_index].($next_row_count + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle("B".$next_row_count.":".$cells[$view_col_index].($next_row_count + 1))->getFill()->getStartColor()->setARGB('00ACB9CA');

                    $next_row_count += 1;

                    $col_index = 6;
                    if(strpos($value->col_part_name, 'at') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(13.75 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "알림톡");
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'ft_txt') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "친구톡(텍스트)");
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'ft_img') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "친구톡(이미지)");
                        $col_index += 1;
                    }
                    /*if(strpos($value->col_part_name, 'ft_w_img') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "친구톡(와이드)");
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'grs_lms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "2차문자(A)");
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'grs_sms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "2차문자(A) SMS");
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'grs_mms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "2차문자(A) MMS");
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'nas_lms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "2차문자(B)");
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'nas_sms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "2차문자(B) SMS");
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'nas_mms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "2차문자(B) MMS");
                        $col_index += 1;
                    }
                    */
                    if(strpos($value->col_part_name, 'smt_sms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "문자 SMS");
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'smt_lms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "문자 LMS");
                        $col_index += 1;
                    }

                    if(strpos($value->col_part_name, 'rcs_sms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "RCS SMS");
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'rcs_lms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "RCS LMS");
                        $col_index += 1;
                    }

                    /*
                    if(strpos($value->col_part_name, 'smt_mms') !== false) {
                        $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "2차문자(C) MMS");
                        $col_index += 1;
                    }*/

                    $cur_worksheet->mergeCells('B'.($next_row_count - 1).':B'.$next_row_count);
                    $cur_worksheet->mergeCells('C'.($next_row_count - 1).':C'.$next_row_count);
                    $cur_worksheet->mergeCells('D'.($next_row_count - 1).':D'.$next_row_count);
                    $cur_worksheet->mergeCells('E'.($next_row_count - 1).':E'.$next_row_count);

                    $cur_worksheet->getStyle('B'.($next_row_count - 1).':'.$cells[$view_col_index].$next_row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.($next_row_count - 1).':'.$cells[$view_col_index].$next_row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $next_row_count += 1;

                    // 리스트 부분 처리
                    $day_result_query = $this->db->query($sql_day)->result();
                    $list_start_row = $next_row_count;
                    $sum_mst_qty = 0;
                    $sum_success_qty = 0;
                    $sum_err_qty = 0;
                    $sum_mst_at = 0;
                    $sum_mst_ft = 0;
                    $sum_mst_ft_img = 0;
                    $sum_mst_ft_w_img = 0;
                    $sum_mst_grs = 0;
                    $sum_mst_grs_sms = 0;
                    $sum_mst_grs_mms = 0;
                    $sum_mst_nas = 0;
                    $sum_mst_nas_sms = 0;
                    $sum_mst_nas_mms = 0;
                    $sum_mst_smt = 0;
                    $sum_mst_smt_sms = 0;
                    $sum_mst_smt_mms = 0;

                    foreach ($day_result_query as $dey_r) {
                        $send_voucher="";
                        if($dey_r->mst_sent_voucher=="V"){
                            $send_voucher="[바우처]";
                        }
                        $sum_mst_qty += $dey_r->mst_qty;
                        $sum_success_qty += $dey_r->success_qty;
                        $sum_err_qty += $dey_r->err_qty;
                        $sum_mst_at += $dey_r->mst_at;
                        $sum_mst_ft += $dey_r->mst_ft;
                        $sum_mst_ft_img += $dey_r->mst_ft_img;
                        $sum_mst_ft_w_img += $dey_r->mst_ft_w_img;
                        $sum_mst_grs += $dey_r->mst_grs;
                        $sum_mst_grs_sms += $dey_r->mst_grs_sms;
                        $sum_mst_grs_mms += $dey_r->mst_grs_mms;
                        $sum_mst_nas += $dey_r->mst_nas;
                        $sum_mst_nas_sms += $dey_r->mst_nas_sms;
                        $sum_mst_nas_mms += $dey_r->mst_nas_mms;
                        $sum_mst_smt += $dey_r->mst_smt;
                        $sum_mst_smt_sms += $dey_r->mst_smt_sms;
                        $sum_mst_smt_mms += $dey_r->mst_smt_mms;

                        $cur_worksheet->setCellValue('B'.$next_row_count, $dey_r->mst_datetime.$send_voucher);
                        $cur_worksheet->getStyle('B'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $cur_worksheet->setCellValue('C'.$next_row_count, ($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0&&$dey_r->mst_sent_voucher=="V")? $dey_r->mst_qty - $adjust_voucher->cnt_all : (($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0)? $dey_r->mst_qty + $adjust_voucher->cnt_all : $dey_r->mst_qty));
                        $cur_worksheet->getStyle('C'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $cur_worksheet->setCellValue('D'.$next_row_count, ($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0&&$dey_r->mst_sent_voucher=="V")? $dey_r->success_qty - $adjust_voucher->cnt_all : (($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0)? $dey_r->success_qty + $adjust_voucher->cnt_all : $dey_r->success_qty));
                        $cur_worksheet->getStyle('D'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $cur_worksheet->setCellValue('E'.$next_row_count, $dey_r->err_qty);
                        $cur_worksheet->getStyle('E'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                        $col_index = 6;
                        if(strpos($value->col_part_name, 'at') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, ($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0&&$dey_r->mst_sent_voucher=="V")? $dey_r->mst_at - $adjust_voucher->cnt_a : (($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0)? $dey_r->mst_at + $adjust_voucher->cnt_a : $dey_r->mst_at));
                            $col_index += 1;
                        }
                        if(strpos($value->col_part_name, 'ft_txt') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, ($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0&&$dey_r->mst_sent_voucher=="V")? $dey_r->mst_ft - $adjust_voucher->cnt_f : (($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0)? $dey_r->mst_ft + $adjust_voucher->cnt_f : $dey_r->mst_ft));
                            $col_index += 1;
                        }
                        if(strpos($value->col_part_name, 'ft_img') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, ($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0&&$dey_r->mst_sent_voucher=="V")? $dey_r->mst_ft_img - $adjust_voucher->cnt_i : (($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0)? $dey_r->mst_ft_img + $adjust_voucher->cnt_i : $dey_r->mst_ft_img));
                            $col_index += 1;
                        }
                        /*if(strpos($value->col_part_name, 'ft_w_img') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_ft_w_img);
                            $col_index += 1;
                        }
                        if(strpos($value->col_part_name, 'grs_lms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_grs);
                            $col_index += 1;
                        }
                        if(strpos($value->col_part_name, 'grs_sms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_grs_sms);
                            $col_index += 1;
                        }
                        if(strpos($value->col_part_name, 'grs_mms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_grs_mms);
                            $col_index += 1;
                        }
                        if(strpos($value->col_part_name, 'nas_lms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_nas);
                            $col_index += 1;
                        }
                        if(strpos($value->col_part_name, 'nas_sms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_nas_sms);
                            $col_index += 1;
                        }
                        if(strpos($value->col_part_name, 'nas_mms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_nas_mms);
                            $col_index += 1;
                        }*/
                        if(strpos($value->col_part_name, 'smt_sms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, ($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0&&$dey_r->mst_sent_voucher=="V")? $dey_r->mst_smt_sms - $adjust_voucher->cnt_sms : (($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0)? $dey_r->mst_smt_sms + $adjust_voucher->cnt_sms : $dey_r->mst_smt_sms));
                            $col_index += 1;
                        }
                        if(strpos($value->col_part_name, 'smt_lms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, ($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0&&$dey_r->mst_sent_voucher=="V")? $dey_r->mst_smt - $adjust_voucher->cnt_lms : (($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0)? $dey_r->mst_smt + $adjust_voucher->cnt_lms : $dey_r->mst_smt));
                            $col_index += 1;
                        }

                        if(strpos($value->col_part_name, 'rcs_sms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, ($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0&&$dey_r->mst_sent_voucher=="V")? $dey_r->mst_rcs_sms - $adjust_voucher->cnt_sms : (($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0)? $dey_r->mst_rcs_sms + $adjust_voucher->cnt_sms : $dey_r->mst_rcs_sms));
                            $col_index += 1;
                        }
                        if(strpos($value->col_part_name, 'rcs_lms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, ($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0&&$dey_r->mst_sent_voucher=="V")? $dey_r->mst_rcs - $adjust_voucher->cnt_lms : (($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0)? $dey_r->mst_rcs + $adjust_voucher->cnt_lms : $dey_r->mst_rcs));
                            $col_index += 1;
                        }

                        /*if(strpos($value->col_part_name, 'smt_mms') !== false) {
                            $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_smt_mms);
                            $col_index += 1;
                        }*/

                        $next_row_count += 1;

                        if($dey_r->mst_datetime==$voucher_blank_date&&$voucher_blank_cnt>0&&$dey_r->mst_sent_voucher=="V"&&$normal_blank_cnt==0){
                            $cur_worksheet->setCellValue('B'.$next_row_count, $dey_r->mst_datetime);
                            $cur_worksheet->getStyle('B'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                            $cur_worksheet->setCellValue('C'.$next_row_count, $adjust_voucher->cnt_all);
                            $cur_worksheet->getStyle('C'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                            $cur_worksheet->setCellValue('D'.$next_row_count, $adjust_voucher->cnt_all);
                            $cur_worksheet->getStyle('D'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                            $cur_worksheet->setCellValue('E'.$next_row_count, 0);
                            $cur_worksheet->getStyle('E'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                            $col_index = 6;
                            if(strpos($value->col_part_name, 'at') !== false) {
                                $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $adjust_voucher->cnt_a);
                                $col_index += 1;
                            }
                            if(strpos($value->col_part_name, 'ft_txt') !== false) {
                                $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $adjust_voucher->cnt_f);
                                $col_index += 1;
                            }
                            if(strpos($value->col_part_name, 'ft_img') !== false) {
                                $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $adjust_voucher->cnt_i);
                                $col_index += 1;
                            }
                            if(strpos($value->col_part_name, 'smt_sms') !== false) {
                                $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $adjust_voucher->cnt_sms);
                                $col_index += 1;
                            }
                            if(strpos($value->col_part_name, 'smt_lms') !== false) {
                                $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $adjust_voucher->cnt_lms);
                                $col_index += 1;
                            }

                            if(strpos($value->col_part_name, 'rcs_sms') !== false) {
                                $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $adjust_voucher->cnt_rcs_sms);
                                $col_index += 1;
                            }
                            if(strpos($value->col_part_name, 'rcs_lms') !== false) {
                                $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $adjust_voucher->cnt_rcs_lms);
                                $col_index += 1;
                            }


                            $next_row_count += 1;
                        }


                    }


                    // 합계 부분 처리
                    $cur_worksheet->setCellValue('B'.$next_row_count, "합 계");
                    $cur_worksheet->setCellValue('C'.$next_row_count, $sum_mst_qty);
                    $cur_worksheet->setCellValue('D'.$next_row_count, $sum_success_qty);
                    $cur_worksheet->setCellValue('E'.$next_row_count, $sum_err_qty);

                    $col_index = 6;
                    if(strpos($value->col_part_name, 'at') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_at);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'ft_txt') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_ft);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'ft_img') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_ft_img);
                        $col_index += 1;
                    }
                    /*if(strpos($value->col_part_name, 'ft_w_img') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_ft_w_img);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'grs_lms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_grs);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'grs_sms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_grs_sms);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'grs_mms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_grs_mms);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'nas_lms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_nas);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'nas_sms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_nas_sms);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'nas_mms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_nas_mms);
                        $col_index += 1;
                    }*/
                    if(strpos($value->col_part_name, 'smt_sms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_smt_sms);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'smt_lms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_smt);
                        $col_index += 1;
                    }

                    if(strpos($value->col_part_name, 'rcs_sms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_rcs_sms);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'rcs_lms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_rcs_smt);
                        $col_index += 1;
                    }

                    /*if(strpos($value->col_part_name, 'smt_mms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_smt_mms);
                        $col_index += 1;
                    }*/

                    $cur_worksheet->getStyle("B".$next_row_count.":".$cells[$view_col_index].$next_row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle("B".$next_row_count.":".$cells[$view_col_index].$next_row_count)->getFill()->getStartColor()->setARGB('00ACB9CA');
                    //$cur_worksheet->getStyle('D4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $cur_worksheet->getStyle('B'.$list_start_row.':B'.$next_row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.$list_start_row.':B'.$next_row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('C'.$list_start_row.':'.$cells[$view_col_index].$next_row_count)->getNumberFormat()->setFormatCode("#,##0_-");
                    //$list_start_row

                    $cur_worksheet->getStyle('B'.$start_table_row.':'.$cells[$view_col_index].$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    $cur_worksheet->getStyle('A1');
                }
                $sheet_count += 1;
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        $filename = '일정산_'.$value->period_name;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');

        // $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function download_monthly_settlement_parent() {
        $value = json_decode($this->input->post('value'));
        //log_message("ERROR", "---------------");
        //log_message("ERROR", "mem_id : ".$value->mem_id);
        //log_message("ERROR", "period_name : ".$value->period_name);
        //log_message("ERROR", "---------------");

        /*
        $sql = "
        SELECT
        	period_name,
        	tmcm.mem_username as top_vendor,
        	SUM(cmr.at) as t_at,
        	cwma.mad_price_at,
        	SUM(cmr.ft) as t_ft,
        	cwma.mad_price_ft,
        	SUM(cmr.ft_img) as t_ft_img,
        	cwma.mad_price_ft_img,
        	SUM(cmr.ft_w_img) as t_ft_w_img,
        	cwma.mad_price_ft_w_img,
        	SUM(cmr.grs) as t_grs,
        	cwma.mad_price_grs,
        	SUM(cmr.grs_biz) as t_grs_biz,
        	case when cwma.mad_price_grs_biz = 0 then cwma.mad_price_grs else cwma.mad_price_grs_biz end mad_price_grs_biz,
        	SUM(cmr.grs_sms) as t_grs_sms,
        	cwma.mad_price_grs_sms,
        	SUM(cmr.grs_mms) as t_grs_mms,
        	cwma.mad_price_grs_mms,
        	SUM(cmr.nas) as t_nas,
        	cwma.mad_price_nas,
        	SUM(cmr.nas_sms) as t_nas_sms,
        	cwma.mad_price_nas_sms,
        	SUM(cmr.nas_mms) as t_nas_mms,
        	cwma.mad_price_nas_mms,
        	SUM(cmr.smt) as t_smt,
        	case when cwma.mad_price_smt = 0 then cwma.mad_price_nas else cwma.mad_price_smt end mad_price_smt,
        	SUM(cmr.smt_sms) as t_smt_sms,
        	case when cwma.mad_price_smt_sms = 0 then cwma.mad_price_nas_sms else cwma.mad_price_smt_sms end mad_price_smt_sms,
        	SUM(cmr.smt_mms) as t_smt_mms,
        	case when cwma.mad_price_smt_mms = 0 then cwma.mad_price_nas_mms else cwma.mad_price_smt_mms end mad_price_smt_mms,
        	(SUM(cmr.at) + SUM(cmr.ft) + SUM(cmr.ft_img) + SUM(cmr.ft_w_img) + SUM(cmr.grs) + SUM(cmr.grs_biz) + SUM(cmr.grs_sms) + SUM(cmr.grs_mms) + SUM(cmr.nas) + SUM(cmr.nas_sms) + SUM(cmr.nas_mms) + SUM(cmr.smt) + SUM(cmr.smt_sms) + SUM(cmr.smt_mms)) as tot_send
        FROM
            cb_monthly_report cmr inner join
            (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username, mem_level) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username, cm.mem_level
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_userid = '".$value->mem_userid."' and cmr.mem_id <> (select mem_id from cb_member where mem_userid = '".$value->mem_userid."')
                	UNION ALL
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username, cm.mem_level
                	FROM cb_member_register cmr
                	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                )
                SELECT distinct mem_id, mem_level, mem_username
                FROM cmem
                union all
                select mem_id, mem_level, mem_username
                from cb_member cm
                where cm.mem_userid = '".$value->mem_userid."'
                    )  cms on cmr.end_mem_id = cms.mem_id inner join
            cb_member cm on cm.mem_id = cmr.manager_mem_id left join
            cb_wt_member_addon cwma on CASE WHEN (select mem_level from cb_member where mem_userid = '".$value->mem_userid."') = 50 THEN cwma.mad_mem_id = cmr.id_level2
            									WHEN (select mem_level from cb_member where mem_userid = '".$value->mem_userid."') = 30 THEN cwma.mad_mem_id = cmr.id_level3
            									WHEN (select mem_level from cb_member where mem_userid = '".$value->mem_userid."') = 10 THEN cwma.mad_mem_id = cmr.id_level4 END left join
            cb_member tmcm on CASE WHEN (select mem_level from cb_member where mem_userid = '".$value->mem_userid."') = 50 THEN tmcm.mem_id = cmr.id_level2
        							WHEN (select mem_level from cb_member where mem_userid = '".$value->mem_userid."') = 30 THEN tmcm.mem_id = cmr.id_level3
        							WHEN (select mem_level from cb_member where mem_userid = '".$value->mem_userid."') = 10 THEN tmcm.mem_id = cmr.id_level4 END
        WHERE
            cmr.period_name = '".$value->period_name."'
        ";
        */

        $sql = "
           SELECT
        	IFNULL(period_name, '".$value->period_name."') AS period_name,
            (select mem_username from cb_member where mem_userid = '".$value->mem_userid."')  as top_vendor,
        	SUM(cmr.at) as t_at,
        	cwma.mad_price_at,
        	SUM(cmr.ft) as t_ft,
        	cwma.mad_price_ft,
        	SUM(cmr.ft_img) as t_ft_img,
        	cwma.mad_price_ft_img,
        	SUM(cmr.ft_w_img) as t_ft_w_img,
        	cwma.mad_price_ft_w_img,
        	SUM(cmr.grs) as t_grs,
        	cwma.mad_price_grs,
        	SUM(cmr.grs_biz) as t_grs_biz,
        	case when cwma.mad_price_grs_biz = 0 then cwma.mad_price_grs else cwma.mad_price_grs_biz end mad_price_grs_biz,
        	SUM(cmr.grs_sms) as t_grs_sms,
        	cwma.mad_price_grs_sms,
        	SUM(cmr.grs_mms) as t_grs_mms,
        	cwma.mad_price_grs_mms,
        	SUM(cmr.nas) as t_nas,
        	cwma.mad_price_nas,
        	SUM(cmr.nas_sms) as t_nas_sms,
        	cwma.mad_price_nas_sms,
        	SUM(cmr.nas_mms) as t_nas_mms,
        	cwma.mad_price_nas_mms,
        	SUM(cmr.smt) as t_smt,
        	case when cwma.mad_price_smt = 0 then cwma.mad_price_nas else cwma.mad_price_smt end mad_price_smt,
        	SUM(cmr.smt_sms) as t_smt_sms,
        	case when cwma.mad_price_smt_sms = 0 then cwma.mad_price_nas_sms else cwma.mad_price_smt_sms end mad_price_smt_sms,
        	SUM(cmr.smt_mms) as t_smt_mms,
        	case when cwma.mad_price_smt_mms = 0 then cwma.mad_price_nas_mms else cwma.mad_price_smt_mms end mad_price_smt_mms,

            SUM(cmr.rcs) as t_rcs,
        	cwma.mad_price_rcs,
        	SUM(cmr.rcs_sms) as t_rcs_sms,
        	cwma.mad_price_rcs_sms,
        	-- SUM(cmr.rcs_mms) as t_rcs_mms,
        	-- cwma.mad_price_rcs_mms,
            -- SUM(cmr.rcs_tem) as t_rcs_tem,
        	-- cwma.mad_price_rcs_mms,
        	(SUM(cmr.at) + SUM(cmr.ft) + SUM(cmr.ft_img) + SUM(cmr.ft_w_img) + SUM(cmr.grs) + SUM(cmr.grs_biz) + SUM(cmr.grs_sms) + SUM(cmr.grs_mms) + SUM(cmr.nas) + SUM(cmr.nas_sms) + SUM(cmr.nas_mms) + SUM(cmr.smt) + SUM(cmr.smt_sms) + SUM(cmr.smt_mms) + SUM(cmr.rcs) + SUM(cmr.rcs_sms) + SUM(cmr.rcs_mms) + SUM(cmr.rcs_tem)) as tot_send
        FROM
                (select cm.mem_id, cm.mem_userid, cm.mem_username, cm.mem_deposit, cm.mem_pay_type, cm.mem_cont_from_date, cm.mem_level, cwmas.*
                  from (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                                         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                         FROM cb_member_register cmr
                                              JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                         WHERE cm.mem_userid = '".$value->mem_userid."' and cmr.mem_id <> (select mem_id from cb_member where mem_userid = '".$value->mem_userid."')
                                         UNION ALL
                                         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                         FROM cb_member_register cmr
                                              JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                              JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id )
                                     SELECT distinct mem_id
                                     FROM cmem
                                     union all
                                     select mem_id
                                     from cb_member cms
                                     where cms.mem_userid = '".$value->mem_userid."') cmar
                       left join cb_member cm on cmar.mem_id = cm.mem_id
                       left join cb_wt_member_addon cwmas on cm.mem_id = cwmas.mad_mem_id
                   where cm.mem_useyn = 'Y' and cm.mem_cont_cancel_yn = 'N' OR (cm.mem_cont_cancel_yn = 'Y' AND cm.mem_cont_cancel_date >= '".$value->period_name."-01')) cmt
			    left join cb_monthly_report cmr  on cmt.mem_id = cmr.end_mem_id and cmr.period_name = '".$value->period_name."'
				left join cb_wt_member_addon cwma on cwma.mad_mem_id = (select mem_id from cb_member where mem_userid = '".$value->mem_userid."')

        ";

        /*
        $sql_list = "
        SELECT *,
            (at_amt + ft_amt + ft_img_amt + ft_w_img_amt + grs_amt + grs_sms_amt + grs_mms_amt + nas_amt + nas_sms_amt + nas_mms_amt + grs_biz_amt + smt_amt + smt_sms_amt + smt_mms_amt) tot_amt
        FROM (
            SELECT
                cmr.voucher_flag,
                period_name,
                manager_mem_id,
                cm.mem_userid,
                end_mem_id,
                (select mem_username from cb_member cm where cm.mem_id = end_mem_id) as vendor,
                (select mem_username from cb_member cm where cm.mem_id = manager_mem_id) as pvendor,
                ( select sum(dep_deposit)
                    from cb_deposit cd
                  where cd.mem_id = end_mem_id
                      and DATE_FORMAT(cd.dep_deposit_datetime, '%Y-%m')  = period_name) as deposit,
                at,
                (case when cmr.voucher_flag = 'V' and cwva.vad_price_at is not null then (cmr.at_price - cwma.mad_price_at)  - (cwva.vad_price_at - cwmaa.mad_price_at) else (cmr.at_price - cwma.mad_price_at) end) as at_margin,
                at *
                (case when cmr.voucher_flag = 'V' and cwva.vad_price_at is not null then (cmr.at_price - cwma.mad_price_at)  - (cwva.vad_price_at - cwmaa.mad_price_at) else (cmr.at_price - cwma.mad_price_at) end) as at_amt,
                case when cmr.voucher_flag = 'V' and cwva.vad_price_at is not null then (cwva.vad_price_at - cwmaa.mad_price_at) else 0 end as at_dhn,
                ft,
                (case when cmr.voucher_flag = 'V' and cwva.vad_price_ft is not null then (cmr.ft_price - cwma.mad_price_ft)  - (cwva.vad_price_ft - cwmaa.mad_price_ft) else (cmr.ft_price - cwma.mad_price_ft) end) as ft_margin,
                case when cmr.voucher_flag = 'V' and cwva.vad_price_ft is not null then (cwva.vad_price_ft - cwmaa.mad_price_ft) else 0 end as ft_dhn,
                ft *
                (case when cmr.voucher_flag = 'V' and cwva.vad_price_ft is not null then (cmr.ft_price - cwma.mad_price_ft)  - (cwva.vad_price_ft - cwmaa.mad_price_ft) else (cmr.ft_price - cwma.mad_price_ft) end) as ft_amt,
                ft_img,
                (case when cmr.voucher_flag = 'V' and cwva.vad_price_ft_img is not null then (cmr.ft_img_price - cwma.mad_price_ft_img)  - (cwva.vad_price_ft_img - cwmaa.mad_price_ft_img) else (cmr.ft_img_price - cwma.mad_price_ft_img) end) as ft_img_margin,
                case when cmr.voucher_flag = 'V' and cwva.vad_price_ft_img is not null then (cwva.vad_price_ft_img - cwmaa.mad_price_ft_img) else 0 end as ft_img_dhn,
                ft_img *
                (case when cmr.voucher_flag = 'V' and cwva.vad_price_ft_img is not null then (cmr.ft_img_price - cwma.mad_price_ft_img)  - (cwva.vad_price_ft_img - cwmaa.mad_price_ft_img) else (cmr.ft_img_price - cwma.mad_price_ft_img) end) as ft_img_amt,
                ft_w_img,
                (cmr.ft_w_img_price - cwma.mad_price_ft_w_img) as ft_w_img_margin,
                ft_w_img * (cmr.ft_w_img_price - cwma.mad_price_ft_w_img) as ft_w_img_amt,
                -- sms,
                -- (cwma.mad_price_grs - cmr.base_up_grs) as sms_margin,
                -- sms * sms_price as sms_amt,
                -- lms,
                -- lms_price,
                -- lms * lms_price as lms_amt,
                -- phn,
                -- phn_price,
                -- phn * phn_price as phn_amt,
                grs,
                (cmr.grs_price - cwma.mad_price_grs) as grs_margin,
                grs * (cmr.grs_price - cwma.mad_price_grs) as grs_amt,
                grs_sms,
                (cmr.grs_sms_price - cwma.mad_price_grs_sms) as grs_sms_margin,
                grs_sms * (cmr.grs_sms_price - cwma.mad_price_grs_sms) as grs_sms_amt,
                grs_mms,
                (cmr.grs_mms_price - cwma.mad_price_grs_mms) as grs_mms_margin,
                grs_mms * (cmr.grs_mms_price - cwma.mad_price_grs_mms) as grs_mms_amt,
                grs_biz,
            	case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = '".$value->mem_userid."' then (cwma.mad_price_grs_biz - cwma.mad_price_grs_biz) else (cmr.grs_price - cwma.mad_price_grs_biz) end as grs_biz_margin,
            	case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = '".$value->mem_userid."' then grs_biz * (cwma.mad_price_grs_biz - cwma.mad_price_grs_biz) else grs_biz * (cmr.grs_price - cwma.mad_price_grs_biz) end as grs_biz_amt,
                nas,
            	(cmr.nas_price - cwma.mad_price_nas) as nas_margin,
            	nas * (cmr.nas_price - cwma.mad_price_nas) as nas_amt,
            	nas_sms,
            	(cmr.nas_sms_price - cwma.mad_price_nas_sms) as nas_sms_margin,
            	nas_sms * (cmr.nas_sms_price - cwma.mad_price_nas_sms) as nas_sms_amt,
            	nas_mms,
            	(cmr.nas_mms_price - cwma.mad_price_nas_mms) as nas_mms_margin,
            	nas_mms * (cmr.nas_mms_price - cwma.mad_price_nas_mms) as nas_mms_amt,
            	smt,
            	(case when cmr.voucher_flag = 'V' and cwva.vad_price_smt is not null then (cmr.smt_price - cwma.mad_price_smt)  - (cwva.vad_price_smt - cwmaa.mad_price_smt) else (cmr.smt_price - cwma.mad_price_smt) end) as smt_margin,
            	case when cmr.voucher_flag = 'V' and cwva.vad_price_smt is not null then (cwva.vad_price_smt - cwmaa.mad_price_smt) else 0 end as smt_dhn,
            	smt *
            	(case when cmr.voucher_flag = 'V' and cwva.vad_price_smt is not null then (cmr.smt_price - cwma.mad_price_smt)  - (cwva.vad_price_smt - cwmaa.mad_price_smt) else (cmr.smt_price - cwma.mad_price_smt) end) as smt_amt,
            	smt_sms,
            	(case when cmr.voucher_flag = 'V' and cwva.vad_price_smt_sms is not null then (cmr.smt_sms_price - cwma.mad_price_smt_sms)  - (cwva.vad_price_smt_sms - cwmaa.mad_price_smt_sms) else (cmr.smt_sms_price - cwma.mad_price_smt_sms) end) as smt_sms_margin,
            	case when cmr.voucher_flag = 'V' and cwva.vad_price_smt_sms is not null then (cwva.vad_price_smt_sms - cwmaa.mad_price_smt_sms) else 0 end as smt_sms_dhn,
            	smt_sms *
            	(case when cmr.voucher_flag = 'V' and cwva.vad_price_smt_sms is not null then (cmr.smt_sms_price - cwma.mad_price_smt_sms)  - (cwva.vad_price_smt_sms - cwmaa.mad_price_smt_sms) else (cmr.smt_sms_price - cwma.mad_price_smt_sms) end) as smt_sms_amt,
            	smt_mms,
            	(cmr.smt_mms_price - cwma.mad_price_smt_mms) as smt_mms_margin,
            	smt_mms * (cmr.smt_mms_price - cwma.mad_price_smt_mms) as smt_mms_amt
            	-- f_015,
            	-- f_015_price,
            	-- f_015 * f_015_price as f_015_amt
            FROM
                cb_monthly_report cmr inner join
                (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                        	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                        	FROM cb_member_register cmr
                        	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                        	WHERE cm.mem_userid = '".$value->mem_userid."' and cmr.mem_id <> (select mem_id from cb_member where mem_userid = '".$value->mem_userid."')
                    	UNION ALL
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                    )
                    SELECT distinct mem_id
                    FROM cmem
                    union all
                    select mem_id
                    from cb_member cm
                    where cm.mem_userid = '".$value->mem_userid."'
                        )  cms on cmr.end_mem_id = cms.mem_id inner join
                cb_member cm on cm.mem_id = cmr.manager_mem_id left join
                cb_wt_member_addon cwma on CASE WHEN (select mem_level from cb_member where mem_userid = '".$value->mem_userid."') = 50 THEN cwma.mad_mem_id = cmr.id_level2
                									WHEN (select mem_level from cb_member where mem_userid = '".$value->mem_userid."') = 30 THEN cwma.mad_mem_id = cmr.id_level3
                									WHEN (select mem_level from cb_member where mem_userid = '".$value->mem_userid."') = 10 THEN cwma.mad_mem_id = cmr.id_level4 END
                left join
                cb_wt_voucher_addon cwva on cwva.vad_mem_id = cmr.end_mem_id
                left join
                cb_wt_member_addon cwmaa on cwmaa.mad_mem_id = cmr.end_mem_id
            WHERE
                cmr.period_name = '".$value->period_name."'
            ORDER BY period_name , mem_level DESC, manager_mem_id , end_mem_id
        ) ABC
        ";
        */

        $sql_list = "
        SELECT *,
            (at_amt + ft_amt + ft_img_amt + ft_w_img_amt + grs_amt + grs_sms_amt + grs_mms_amt + nas_amt + nas_sms_amt + nas_mms_amt + grs_biz_amt + smt_amt + smt_sms_amt + smt_mms_amt + rcs_amt + rcs_sms_amt + rcs_mms_amt + rcs_tem_amt) tot_amt
        FROM (
            SELECT
                cmr.voucher_flag,
                period_name,
                manager_mem_id,
                -- cm.mem_userid,
                end_mem_id,
                (select mem_username from cb_member cm where cm.mem_id = end_mem_id) as vendor,
                (select mem_username from cb_member cm where cm.mem_id = manager_mem_id) as pvendor,
                ( select sum(dep_deposit)
                    from cb_deposit cd
                  where cd.mem_id = end_mem_id
                      and DATE_FORMAT(cd.dep_deposit_datetime, '%Y-%m')  = period_name) as deposit,
                at,
                (cmr.at_price - cwma.mad_price_at) as at_margin,
                at *
                (cmr.at_price - cwma.mad_price_at) as at_amt,
                case when cmr.voucher_flag = 'V' and cwva.vad_price_at is not null then (cwva.vad_price_at - cwmaa.mad_price_at) else 0 end as at_dhn,
                ft,
                (cmr.ft_price - cwma.mad_price_ft) as ft_margin,
                case when cmr.voucher_flag = 'V' and cwva.vad_price_ft is not null then (cwva.vad_price_ft - cwmaa.mad_price_ft) else 0 end as ft_dhn,
                ft *
                (cmr.ft_price - cwma.mad_price_ft) as ft_amt,
                ft_img,
                (cmr.ft_img_price - cwma.mad_price_ft_img) as ft_img_margin,
                case when cmr.voucher_flag = 'V' and cwva.vad_price_ft_img is not null then (cwva.vad_price_ft_img - cwmaa.mad_price_ft_img) else 0 end as ft_img_dhn,
                ft_img *
                (cmr.ft_img_price - cwma.mad_price_ft_img) as ft_img_amt,
                ft_w_img,
                (cmr.ft_w_img_price - cwma.mad_price_ft_w_img) as ft_w_img_margin,
                ft_w_img * (cmr.ft_w_img_price - cwma.mad_price_ft_w_img) as ft_w_img_amt,
                -- sms,
                -- (cwma.mad_price_grs - cmr.base_up_grs) as sms_margin,
                -- sms * sms_price as sms_amt,
                -- lms,
                -- lms_price,
                -- lms * lms_price as lms_amt,
                -- phn,
                -- phn_price,
                -- phn * phn_price as phn_amt,
                grs,
                (cmr.grs_price - cwma.mad_price_grs) as grs_margin,
                grs * (cmr.grs_price - cwma.mad_price_grs) as grs_amt,
                grs_sms,
                (cmr.grs_sms_price - cwma.mad_price_grs_sms) as grs_sms_margin,
                grs_sms * (cmr.grs_sms_price - cwma.mad_price_grs_sms) as grs_sms_amt,
                grs_mms,
                (cmr.grs_mms_price - cwma.mad_price_grs_mms) as grs_mms_margin,
                grs_mms * (cmr.grs_mms_price - cwma.mad_price_grs_mms) as grs_mms_amt,
                grs_biz,
            	case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = '".$value->mem_userid."' then (cwma.mad_price_grs_biz - cwma.mad_price_grs_biz) else (cmr.grs_price - cwma.mad_price_grs_biz) end as grs_biz_margin,
            	case when (select mem_userid from cb_member cm where cm.mem_id = end_mem_id) = '".$value->mem_userid."' then grs_biz * (cwma.mad_price_grs_biz - cwma.mad_price_grs_biz) else grs_biz * (cmr.grs_price - cwma.mad_price_grs_biz) end as grs_biz_amt,
                nas,
            	(cmr.nas_price - cwma.mad_price_nas) as nas_margin,
            	nas * (cmr.nas_price - cwma.mad_price_nas) as nas_amt,
            	nas_sms,
            	(cmr.nas_sms_price - cwma.mad_price_nas_sms) as nas_sms_margin,
            	nas_sms * (cmr.nas_sms_price - cwma.mad_price_nas_sms) as nas_sms_amt,
            	nas_mms,
            	(cmr.nas_mms_price - cwma.mad_price_nas_mms) as nas_mms_margin,
            	nas_mms * (cmr.nas_mms_price - cwma.mad_price_nas_mms) as nas_mms_amt,
            	smt,
            	(cmr.smt_price - cwma.mad_price_smt) as smt_margin,
            	case when cmr.voucher_flag = 'V' and cwva.vad_price_smt is not null then (cwva.vad_price_smt - cwmaa.mad_price_smt) else 0 end as smt_dhn,
            	smt *
            	(cmr.smt_price - cwma.mad_price_smt) as smt_amt,
            	smt_sms,
            	(cmr.smt_sms_price - cwma.mad_price_smt_sms) as smt_sms_margin,
            	case when cmr.voucher_flag = 'V' and cwva.vad_price_smt_sms is not null then (cwva.vad_price_smt_sms - cwmaa.mad_price_smt_sms) else 0 end as smt_sms_dhn,
            	smt_sms *
            	(cmr.smt_sms_price - cwma.mad_price_smt_sms) as smt_sms_amt,
            	smt_mms,
            	(cmr.smt_mms_price - cwma.mad_price_smt_mms) as smt_mms_margin,
            	smt_mms * (cmr.smt_mms_price - cwma.mad_price_smt_mms) as smt_mms_amt,

                rcs,
                (case when cmr.rcs_price > 0 then (cmr.rcs_price - cwma.mad_price_rcs) else 0 end) as rcs_margin,

            	case when cmr.voucher_flag = 'V' and cwva.vad_price_rcs is not null then (cwva.vad_price_rcs - cwmaa.mad_price_rcs) else 0 end as rcs_dhn,
            	rcs *
            	(case when cmr.rcs_price > 0 then (cmr.rcs_price - cwma.mad_price_rcs) else 0 end) as rcs_amt,
            	rcs_sms,
            	(case when cmr.rcs_sms_price > 0 then (cmr.rcs_sms_price - cwma.mad_price_rcs_sms) else 0 end) as rcs_sms_margin,
            	case when cmr.voucher_flag = 'V' and cwva.vad_price_rcs_sms is not null then (cwva.vad_price_rcs_sms - cwmaa.mad_price_rcs_sms) else 0 end as rcs_sms_dhn,
            	rcs_sms *
            	(case when cmr.rcs_sms_price > 0 then (cmr.rcs_sms_price - cwma.mad_price_rcs_sms) else 0 end) as rcs_sms_amt,
            	rcs_mms,
            	(case when cmr.rcs_mms_price > 0 then (cmr.rcs_mms_price - cwma.mad_price_rcs_mms) else 0 end) as rcs_mms_margin,
            	rcs_mms *
                (case when cmr.rcs_mms_price > 0 then (cmr.rcs_mms_price - cwma.mad_price_rcs_mms) else 0 end) as rcs_mms_amt,
				rcs_tem,
            	(cmr.rcs_tem_price - cwma.mad_price_rcs_tem) as rcs_tem_margin,
            	rcs_tem * (cmr.rcs_tem_price - cwma.mad_price_rcs_tem) as rcs_tem_amt
            	-- f_015,
            	-- f_015_price,
            	-- f_015 * f_015_price as f_015_amt
            FROM
                (select cm.mem_id, cm.mem_userid, cm.mem_username, cm.mem_deposit, cm.mem_pay_type, cm.mem_cont_from_date, cm.mem_level, cmrs.mrg_recommend_mem_id, cwmas.*
                  from (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                                         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                         FROM cb_member_register cmr
                                              JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                         WHERE cm.mem_userid = '".$value->mem_userid."' and cmr.mem_id <> (select mem_id from cb_member where mem_userid = '".$value->mem_userid."')
                                         UNION ALL
                                         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                                         FROM cb_member_register cmr
                                              JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                                              JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id )
                                     SELECT distinct mem_id
                                     FROM cmem
                                     union all
                                     select mem_id
                                     from cb_member cms
                                     where cms.mem_userid = '".$value->mem_userid."') cmar
                       left join cb_member cm on cmar.mem_id = cm.mem_id
                       left join cb_wt_member_addon cwmas on cm.mem_id = cwmas.mad_mem_id
                       left join cb_member_register cmrs on cm.mem_id = cmrs.mem_id
                   where cm.mem_useyn = 'Y' and cm.mem_cont_cancel_yn = 'N' OR (cm.mem_cont_cancel_yn = 'Y' AND cm.mem_cont_cancel_date >= '".$value->period_name."-01')) cmt
			    left join cb_monthly_report cmr  on cmt.mem_id = cmr.end_mem_id and cmr.period_name = '".$value->period_name."'
				left join cb_wt_member_addon cwma on cwma.mad_mem_id = (select mem_id from cb_member where mem_userid = '".$value->mem_userid."')
                left join
                cb_wt_voucher_addon cwva on cwva.vad_mem_id = cmr.end_mem_id
                left join
                -- cb_wt_member_addon cwmaa on cwmaa.mad_mem_id = cmr.end_mem_id
                cb_wt_member_addon cwmaa on cwmaa.mad_mem_id = cmr.end_mem_id
            WHERE
                cmr.period_name = '".$value->period_name."'
            ORDER BY period_name , mem_level DESC, manager_mem_id , end_mem_id
        ) ABC
        ";


        log_message("ERROR", $sql);
        $result_query = $this->db->query($sql)->result();

        log_message("ERROR", $sql_list);
        $result_list_query = $this->db->query($sql_list)->result();


        $start_date = $value->period_name.'-01';
        $end_date = $value->period_name.'-'.date('t', strtotime($start_date));

        if ($result_query) {
            $spreadsheet = new Spreadsheet();
            $cur_worksheet = $spreadsheet->getActiveSheet();

            $cur_worksheet->getColumnDimension('A')->setWidth(1.38 + 0.63);
            $cur_worksheet->getColumnDimension('B')->setWidth(18.63 + 0.63);
            $cur_worksheet->getColumnDimension('C')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('D')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('E')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('F')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('G')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('H')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('I')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('J')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('K')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('L')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('M')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('N')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('O')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('P')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('Q')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('R')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('S')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('T')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('U')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('V')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('W')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('X')->setWidth(10.63 + 0.63);
            // $cur_worksheet->getColumnDimension('Y')->setWidth(9.00 + 0.63);
            // $cur_worksheet->getColumnDimension('Z')->setWidth(6.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AA')->setWidth(10.63 + 0.63);
            // $cur_worksheet->getColumnDimension('AB')->setWidth(9.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AC')->setWidth(6.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AD')->setWidth(10.63 + 0.63);
            // $cur_worksheet->getColumnDimension('AE')->setWidth(9.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AF')->setWidth(6.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AG')->setWidth(10.63 + 0.63);
            //
            // $cur_worksheet->getColumnDimension('AH')->setWidth(9.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AI')->setWidth(6.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AJ')->setWidth(10.63 + 0.63);
            // $cur_worksheet->getColumnDimension('AK')->setWidth(9.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AL')->setWidth(6.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AM')->setWidth(10.63 + 0.63);
            // $cur_worksheet->getColumnDimension('AN')->setWidth(9.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AO')->setWidth(6.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AP')->setWidth(10.63 + 0.63);
            // $cur_worksheet->getColumnDimension('AQ')->setWidth(9.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AR')->setWidth(6.00 + 0.63);
            // $cur_worksheet->getColumnDimension('AS')->setWidth(10.63 + 0.63);

            $cur_worksheet->getColumnDimension('Y')->setWidth(17.50 + 0.63);



            foreach ($result_query as $r) {

                $cur_worksheet->getRowDimension(2)->setRowHeight(25);

                $cur_worksheet->setTitle($r->top_vendor.'_'.$value->period_name)
                ->mergeCells("B2:H2")
                ->setCellValue("B2", str_replace('_',' ',$r->top_vendor));
                $cur_worksheet->getStyle("B2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle("B2")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle("B2")->getFont()->setSize(13);

                $cur_worksheet->getRowDimension(4)->setRowHeight(18);
                $cur_worksheet->mergeCells("E4:H4")->setCellValue("E4", "기간 : ".$start_date." ~ ".$end_date);
                $cur_worksheet->getStyle("E4")->getFont()->setBold(true);
                $cur_worksheet->getStyle("E4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle("E4")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('E4:H4')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $cur_worksheet->getStyle('E4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle("E4")->getFill()->getStartColor()->setARGB('00F2F2F2');

                $start_table_row = 5;
                $cur_worksheet->getRowDimension(5)->setRowHeight(15);
                $cur_worksheet->getRowDimension(6)->setRowHeight(15);
                $cur_worksheet->mergeCells("B5:B6");
                $cur_worksheet->mergeCells("C5:E6");
                $cur_worksheet->mergeCells("F5:H6");
                $cur_worksheet->getStyle("B5:H6")->getFont()->getColor()->setARGB('007D604F');
                $cur_worksheet->getStyle("B5:H6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle("B5:H6")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('B5:H6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle("B5:H6")->getFill()->getStartColor()->setARGB('00D9D9D9');
                $cur_worksheet->setCellValue("B5", "항목");
                $cur_worksheet->setCellValue("C5", "발송건수");
                $cur_worksheet->setCellValue("F5", "단가(VAT포함)");


                $row_count = 7;
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "알림톡");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_at);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_at);
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "친구톡(텍스트)");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_ft);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_ft);
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "친구톡(이미지)");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_ft_img);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_ft_img);
                $row_count += 1;

                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                // $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                // $cur_worksheet->setCellValue("B".$row_count, "친구톡(와이드)");
                // $cur_worksheet->setCellValue("C".$row_count, $r->t_ft_w_img);
                // $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_ft_w_img);
                // $row_count += 1;
                //
                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                // $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                // $cur_worksheet->setCellValue("B".$row_count, "Web(A) KT, LG");
                // $cur_worksheet->setCellValue("C".$row_count, $r->t_grs);
                // $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_grs);
                // $row_count += 1;
                //
                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                // $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                // $cur_worksheet->setCellValue("B".$row_count, "Web(A) SKT");
                // $cur_worksheet->setCellValue("C".$row_count, $r->t_grs_biz);
                // $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_grs_biz);
                // $row_count += 1;
                //
                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                // $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                // $cur_worksheet->setCellValue("B".$row_count, "Web(A) SMS");
                // $cur_worksheet->setCellValue("C".$row_count, $r->t_grs_sms);
                // $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_grs_sms);
                // $row_count += 1;
                //
                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                // $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                // $cur_worksheet->setCellValue("B".$row_count, "Web(A) MMS");
                // $cur_worksheet->setCellValue("C".$row_count, $r->t_grs_mms);
                // $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_grs_mms);
                // $row_count += 1;
                //
                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                // $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                // $cur_worksheet->setCellValue("B".$row_count, "Web(B)");
                // $cur_worksheet->setCellValue("C".$row_count, $r->t_nas);
                // $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_nas);
                // $row_count += 1;
                //
                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                // $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                // $cur_worksheet->setCellValue("B".$row_count, "Web(B) SMS");
                // $cur_worksheet->setCellValue("C".$row_count, $r->t_nas_sms);
                // $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_nas_sms);
                // $row_count += 1;
                //
                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                // $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                // $cur_worksheet->setCellValue("B".$row_count, "Web(B) MMS");
                // $cur_worksheet->setCellValue("C".$row_count, $r->t_nas_mms);
                // $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_nas_mms);
                // $row_count += 1;
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "문자 SMS");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_smt_sms);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_smt_sms);
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "문자 LMS");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_smt);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_smt);
                $row_count += 1;


                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "RCS SMS");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_rcs_sms);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_rcs_sms);
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "RCS LMS");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_rcs);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_rcs);
                $row_count += 1;

                // $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                // $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                // $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                // $cur_worksheet->setCellValue("B".$row_count, "문자 MMS");
                // $cur_worksheet->setCellValue("C".$row_count, $r->t_smt_mms);
                // $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_smt_mms);
                // $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->getStyle('B'.$row_count.':H'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle('B'.$row_count.':H'.$row_count)->getFill()->getStartColor()->setARGB('00D9D9D9');
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count.':E'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->setCellValue("B".$row_count, "사용건수합계");
                $cur_worksheet->setCellValue("C".$row_count, $r->tot_send);
                $cur_worksheet->setCellValue("F".$row_count, "");

                $cur_worksheet->getStyle('B'.($start_table_row + 2).':B'.($row_count-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle('B'.($start_table_row + 2).':B'.($row_count-1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('C'.($start_table_row + 2).':H'.($row_count-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $cur_worksheet->getStyle('C'.($start_table_row + 2).':H'.($row_count-1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('C'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $cur_worksheet->getStyle('C'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $cur_worksheet->getStyle('C'.($start_table_row + 2).':C'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");
                $cur_worksheet->getStyle('F'.($start_table_row + 2).':F'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");

                $cur_worksheet->getStyle("B".$start_table_row.":H".$row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $row_count += 1;
            }

            if ($result_list_query) {
                 $row_count += 1;

                 $cur_worksheet->mergeCells('B'.$row_count.':S'.$row_count)->setCellValue('B'.$row_count, "발 송 내 역  정 산");
                 $cur_worksheet->getStyle('B'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                 $cur_worksheet->getStyle('B'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                 $cur_worksheet->getStyle('B'.$row_count)->getFont()->setSize(13);
                 $cur_worksheet->getRowDimension($row_count)->setRowHeight(25);
                 $cur_worksheet->getStyle('B'.$row_count)->getFont()->setBold(true);

                $row_count += 2;
                $start_table_row = $row_count;
                // Table Header 부분 처리

                $cur_worksheet->mergeCells('B'.$row_count.':C'.($row_count + 1))->setCellValue('B'.$row_count, "업 체 명");
                $cur_worksheet->mergeCells('D'.$row_count.':F'.$row_count)->setCellValue('D'.$row_count, "알림톡");
                $cur_worksheet->mergeCells('G'.$row_count.':I'.$row_count)->setCellValue('G'.$row_count, "친구톡(텍스트)");
                $cur_worksheet->mergeCells('J'.$row_count.':L'.$row_count)->setCellValue('J'.$row_count, "친구톡(이미지)");
                // $cur_worksheet->mergeCells('M'.$row_count.':O'.$row_count)->setCellValue('M'.$row_count, "친구톡(와이드)");
                // $cur_worksheet->mergeCells('P'.$row_count.':R'.$row_count)->setCellValue('P'.$row_count, "WEB(A) KT, LG");
                // $cur_worksheet->mergeCells('S'.$row_count.':U'.$row_count)->setCellValue('S'.$row_count, "WEB(A) SKT");
                // $cur_worksheet->mergeCells('V'.$row_count.':X'.$row_count)->setCellValue('V'.$row_count, "WEB(A) SMS");
                // $cur_worksheet->mergeCells('Y'.$row_count.':AA'.$row_count)->setCellValue('Y'.$row_count, "WEB(A) MMS");
                // $cur_worksheet->mergeCells('AB'.$row_count.':AD'.$row_count)->setCellValue('AB'.$row_count, "WEB(B)");
                // $cur_worksheet->mergeCells('AE'.$row_count.':AG'.$row_count)->setCellValue('AE'.$row_count, "WEB(B) SMS");
                // $cur_worksheet->mergeCells('AH'.$row_count.':AJ'.$row_count)->setCellValue('AH'.$row_count, "WEB(B) MMS");
                $cur_worksheet->mergeCells('M'.$row_count.':O'.$row_count)->setCellValue('M'.$row_count, "문자 SMS");
                $cur_worksheet->mergeCells('P'.$row_count.':R'.$row_count)->setCellValue('P'.$row_count, "문자 LMS");

                $cur_worksheet->mergeCells('S'.$row_count.':U'.$row_count)->setCellValue('S'.$row_count, "RCS SMS");
                $cur_worksheet->mergeCells('V'.$row_count.':X'.$row_count)->setCellValue('V'.$row_count, "RCS LMS");
                // $cur_worksheet->mergeCells('S'.$row_count.':U'.$row_count)->setCellValue('S'.$row_count, "문자 MMS");
                $cur_worksheet->mergeCells('Y'.$row_count.':Y'.($row_count + 1))->setCellValue('Y'.$row_count, "총수익");

                $cur_worksheet->getStyle('B'.$row_count.':Y'.($row_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count.':Y'.($row_count + 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count.':Y'.($row_count + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle('B'.$row_count.':Y'.($row_count + 1))->getFill()->getStartColor()->setARGB('00AEAAAA');
                $cur_worksheet->getStyle('B'.$row_count.':Y'.($row_count + 1))->getFont()->setSize(10);
                $cur_worksheet->getStyle('B'.$row_count.':Y'.($row_count + 1))->getFont()->setBold(true);
                $row_count += 1;

                $cur_worksheet->setCellValue('D'.$row_count, "건수")->setCellValue('E'.$row_count, "마진")->setCellValue('F'.$row_count, "금액");
                $cur_worksheet->setCellValue('G'.$row_count, "건수")->setCellValue('H'.$row_count, "마진")->setCellValue('I'.$row_count, "금액");
                $cur_worksheet->setCellValue('J'.$row_count, "건수")->setCellValue('K'.$row_count, "마진")->setCellValue('L'.$row_count, "금액");
                $cur_worksheet->setCellValue('M'.$row_count, "건수")->setCellValue('N'.$row_count, "마진")->setCellValue('O'.$row_count, "금액");
                $cur_worksheet->setCellValue('P'.$row_count, "건수")->setCellValue('Q'.$row_count, "마진")->setCellValue('R'.$row_count, "금액");
                $cur_worksheet->setCellValue('S'.$row_count, "건수")->setCellValue('T'.$row_count, "마진")->setCellValue('U'.$row_count, "금액");
                $cur_worksheet->setCellValue('V'.$row_count, "건수")->setCellValue('W'.$row_count, "마진")->setCellValue('X'.$row_count, "금액");
                // $cur_worksheet->setCellValue('Y'.$row_count, "건수")->setCellValue('Z'.$row_count, "마진")->setCellValue('AA'.$row_count, "금액");
                // $cur_worksheet->setCellValue('AB'.$row_count, "건수")->setCellValue('AC'.$row_count, "마진")->setCellValue('AD'.$row_count, "금액");
                // $cur_worksheet->setCellValue('AE'.$row_count, "건수")->setCellValue('AF'.$row_count, "마진")->setCellValue('AG'.$row_count, "금액");
                // $cur_worksheet->setCellValue('AH'.$row_count, "건수")->setCellValue('AI'.$row_count, "마진")->setCellValue('AJ'.$row_count, "금액");
                // $cur_worksheet->setCellValue('AK'.$row_count, "건수")->setCellValue('AL'.$row_count, "마진")->setCellValue('AM'.$row_count, "금액");
                // $cur_worksheet->setCellValue('AN'.$row_count, "건수")->setCellValue('AO'.$row_count, "마진")->setCellValue('AP'.$row_count, "금액");
                // $cur_worksheet->setCellValue('AQ'.$row_count, "건수")->setCellValue('AR'.$row_count, "마진")->setCellValue('AS'.$row_count, "금액");
                $cur_worksheet->getStyle("B".$start_table_row.":Y".$row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $row_count += 1;

                $sum_at = 0;
                $sum_at_amt = 0;
                $sum_ft = 0;
                $sum_ft_amt = 0;
                $sum_ft_img = 0;
                $sum_ft_img_amt = 0;
                $sum_ft_w_img = 0;
                $sum_ft_w_img_amt = 0;
                $sum_grs = 0;
                $sum_grs_amt = 0;
                $sum_grs_sms = 0;
                $sum_grs_sms_amt = 0;
                $sum_grs_mms = 0;
                $sum_grs_mms_amt = 0;
                $sum_grs_biz = 0;
                $sum_grs_biz_amt = 0;
                $sum_nas = 0;
                $sum_nas_amt = 0;
                $sum_nas_sms = 0;
                $sum_nas_sms_amt = 0;
                $sum_nas_mms = 0;
                $sum_nas_mms_amt = 0;
                // $sum_tot_amt = 0;
                $sum_smt = 0;
                $sum_smt_amt = 0;
                $sum_smt_sms = 0;
                $sum_smt_sms_amt = 0;
                $sum_smt_mms = 0;
                $sum_smt_mms_amt = 0;

                $sum_rcs = 0;
                $sum_rcs_amt = 0;
                $sum_rcs_sms = 0;
                $sum_rcs_sms_amt = 0;
                $sum_rcs_mms = 0;
                $sum_rcs_mms_amt = 0;
                $sum_rcs_tem = 0;
                $sum_rcs_tem_amt = 0;

                $sum_tot_amt = 0;

                foreach ($result_list_query as $r) {
                    $sum_at += $r->at;
                    $sum_at_amt += $r->at_amt;
                    $sum_ft += $r->ft;
                    $sum_ft_amt += $r->ft_amt;
                    $sum_ft_img += $r->ft_img;
                    $sum_ft_img_amt += $r->ft_img_amt;
                    $sum_ft_w_img += $r->ft_w_img;
                    $sum_ft_w_img_amt += $r->ft_w_img_amt;
                    $sum_grs += $r->grs;
                    $sum_grs_amt += $r->grs_amt;
                    $sum_grs_sms += $r->grs_sms;
                    $sum_grs_sms_amt += $r->grs_sms_amt;
                    $sum_grs_mms += $r->grs_mms;
                    $sum_grs_mms_amt += $r->grs_mms_amt;
                    $sum_grs_biz += $r->grs_biz;
                    $sum_grs_biz_amt += $r->grs_biz_amt;
                    $sum_nas += $r->nas;
                    $sum_nas_amt += $r->nas_amt;
                    $sum_nas_sms += $r->nas_sms;
                    $sum_nas_sms_amt += $r->nas_sms_amt;
                    $sum_nas_mms += $r->nas_mms;
                    $sum_nas_mms_amt += $r->nas_mms_amt;
                    $sum_smt += $r->smt;
                    $sum_smt_amt += $r->smt_amt;
                    $sum_smt_sms += $r->smt_sms;
                    $sum_smt_sms_amt += $r->smt_sms_amt;
                    $sum_smt_mms += $r->smt_mms;
                    $sum_smt_mms_amt += $r->smt_mms_amt;

                    $sum_rcs += $r->rcs;
                    $sum_rcs_amt += $r->rcs_amt;
                    $sum_rcs_sms += $r->rcs_sms;
                    $sum_rcs_sms_amt += $r->rcs_sms_amt;
                    $sum_rcs_mms += $r->rcs_mms;
                    $sum_rcs_mms_amt += $r->rcs_mms_amt;

                    $sum_tot_amt += $r->tot_amt;

                    $voucher_text = "";

                    if($r->voucher_flag=="V"){
                        $voucher_text = "[바우처]";
                    }

                    $cur_worksheet->mergeCells('B'.$row_count.':C'.$row_count)->mergeCells('B'.($row_count + 1).':C'.($row_count + 1));
                    $cur_worksheet->mergeCells('D'.$row_count.':D'.($row_count + 1))->mergeCells('E'.$row_count.':E'.($row_count + 1))->mergeCells('F'.$row_count.':F'.($row_count + 1));
                    $cur_worksheet->mergeCells('G'.$row_count.':G'.($row_count + 1))->mergeCells('H'.$row_count.':H'.($row_count + 1))->mergeCells('I'.$row_count.':I'.($row_count + 1));
                    $cur_worksheet->mergeCells('J'.$row_count.':J'.($row_count + 1))->mergeCells('K'.$row_count.':K'.($row_count + 1))->mergeCells('L'.$row_count.':L'.($row_count + 1));
                    $cur_worksheet->mergeCells('M'.$row_count.':M'.($row_count + 1))->mergeCells('N'.$row_count.':N'.($row_count + 1))->mergeCells('O'.$row_count.':O'.($row_count + 1));
                    $cur_worksheet->mergeCells('P'.$row_count.':P'.($row_count + 1))->mergeCells('Q'.$row_count.':Q'.($row_count + 1))->mergeCells('R'.$row_count.':R'.($row_count + 1));
                    $cur_worksheet->mergeCells('S'.$row_count.':S'.($row_count + 1))->mergeCells('T'.$row_count.':T'.($row_count + 1))->mergeCells('U'.$row_count.':U'.($row_count + 1));
                    $cur_worksheet->mergeCells('V'.$row_count.':V'.($row_count + 1))->mergeCells('W'.$row_count.':W'.($row_count + 1))->mergeCells('X'.$row_count.':X'.($row_count + 1));
                    // $cur_worksheet->mergeCells('Y'.$row_count.':Y'.($row_count + 1))->mergeCells('Z'.$row_count.':Z'.($row_count + 1))->mergeCells('AA'.$row_count.':AA'.($row_count + 1));
                    // $cur_worksheet->mergeCells('AB'.$row_count.':AB'.($row_count + 1))->mergeCells('AC'.$row_count.':AC'.($row_count + 1))->mergeCells('AD'.$row_count.':AD'.($row_count + 1));
                    // $cur_worksheet->mergeCells('AE'.$row_count.':AE'.($row_count + 1))->mergeCells('AF'.$row_count.':AF'.($row_count + 1))->mergeCells('AG'.$row_count.':AG'.($row_count + 1));
                    // $cur_worksheet->mergeCells('AH'.$row_count.':AH'.($row_count + 1))->mergeCells('AI'.$row_count.':AI'.($row_count + 1))->mergeCells('AJ'.$row_count.':AJ'.($row_count + 1));
                    // $cur_worksheet->mergeCells('AK'.$row_count.':AK'.($row_count + 1))->mergeCells('AL'.$row_count.':AL'.($row_count + 1))->mergeCells('AM'.$row_count.':AM'.($row_count + 1));
                    // $cur_worksheet->mergeCells('AN'.$row_count.':AN'.($row_count + 1))->mergeCells('AO'.$row_count.':AO'.($row_count + 1))->mergeCells('AP'.$row_count.':AP'.($row_count + 1));
                    // $cur_worksheet->mergeCells('AQ'.$row_count.':AQ'.($row_count + 1))->mergeCells('AR'.$row_count.':AR'.($row_count + 1))->mergeCells('AS'.$row_count.':AS'.($row_count + 1));
                    $cur_worksheet->mergeCells('Y'.$row_count.':Y'.($row_count + 1));
                    $cur_worksheet->setCellValue('B'.$row_count, $r->vendor.$voucher_text)->setCellValue('B'.($row_count + 1), $r->pvendor);
                    $cur_worksheet->setCellValue('D'.$row_count, $r->at)->setCellValue('E'.$row_count, $r->at_margin)->setCellValue('F'.$row_count, $r->at_amt);
                    $cur_worksheet->setCellValue('G'.$row_count, $r->ft)->setCellValue('H'.$row_count, $r->ft_margin)->setCellValue('I'.$row_count, $r->ft_amt);
                    $cur_worksheet->setCellValue('J'.$row_count, $r->ft_img)->setCellValue('K'.$row_count, $r->ft_img_margin)->setCellValue('L'.$row_count, $r->ft_img_amt);
                    // $cur_worksheet->setCellValue('M'.$row_count, $r->ft_w_img)->setCellValue('N'.$row_count, $r->ft_w_img_margin)->setCellValue('O'.$row_count, $r->ft_w_img_amt);
                    // $cur_worksheet->setCellValue('P'.$row_count, $r->grs)->setCellValue('Q'.$row_count, $r->grs_margin)->setCellValue('R'.$row_count, $r->grs_amt);
                    // $cur_worksheet->setCellValue('S'.$row_count, $r->grs_biz)->setCellValue('T'.$row_count, $r->grs_biz_margin)->setCellValue('U'.$row_count, $r->grs_biz_amt);
                    // $cur_worksheet->setCellValue('V'.$row_count, $r->grs_sms)->setCellValue('W'.$row_count, $r->grs_sms_margin)->setCellValue('X'.$row_count, $r->grs_sms_amt);
                    // $cur_worksheet->setCellValue('Y'.$row_count, $r->grs_mms)->setCellValue('Z'.$row_count, $r->grs_mms_margin)->setCellValue('AA'.$row_count, $r->grs_mms_amt);
                    // $cur_worksheet->setCellValue('AB'.$row_count, $r->nas)->setCellValue('AC'.$row_count, $r->nas_margin)->setCellValue('AD'.$row_count, $r->nas_amt);
                    // $cur_worksheet->setCellValue('AE'.$row_count, $r->nas_sms)->setCellValue('AF'.$row_count, $r->nas_sms_margin)->setCellValue('AG'.$row_count, $r->nas_sms_amt);
                    // $cur_worksheet->setCellValue('AH'.$row_count, $r->nas_mms)->setCellValue('AI'.$row_count, $r->nas_mms_margin)->setCellValue('AJ'.$row_count, $r->nas_mms_amt);
                    $cur_worksheet->setCellValue('M'.$row_count, $r->smt_sms)->setCellValue('N'.$row_count, $r->smt_sms_margin)->setCellValue('O'.$row_count, $r->smt_sms_amt);
                    $cur_worksheet->setCellValue('P'.$row_count, $r->smt)->setCellValue('Q'.$row_count, $r->smt_margin)->setCellValue('R'.$row_count, $r->smt_amt);
                    $cur_worksheet->setCellValue('S'.$row_count, $r->rcs_sms)->setCellValue('T'.$row_count, $r->rcs_sms_margin)->setCellValue('U'.$row_count, $r->rcs_sms_amt);
                    $cur_worksheet->setCellValue('V'.$row_count, $r->rcs)->setCellValue('W'.$row_count, $r->rcs_margin)->setCellValue('X'.$row_count, $r->rcs_amt);
                    $cur_worksheet->setCellValue('Y'.$row_count, round($r->tot_amt));

                    $cur_worksheet->getStyle("B".$row_count.":C".$row_count)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("B".$row_count.":C".$row_count)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("B".$row_count.":C".$row_count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("B".($row_count + 1).":C".($row_count + 1))->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("B".($row_count + 1).":C".($row_count + 1))->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("B".($row_count + 1).":C".($row_count + 1))->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("D".$row_count.":Y".($row_count + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    $cur_worksheet->getStyle('B'.$row_count.":B".($row_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.$row_count.":B".($row_count + 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('D'.$row_count.':Y'.($row_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $cur_worksheet->getStyle('D'.$row_count.':Y'.($row_count + 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('B'.$row_count.':Y'.($row_count + 1))->getFont()->setSize(10);

                    $row_count += 2;
                }

                $cur_worksheet->mergeCells('B'.$row_count.':C'.$row_count)->setCellValue('B'.$row_count, "합 계");
                $cur_worksheet->setCellValue('D'.$row_count, $sum_at);
                $cur_worksheet->mergeCells('E'.$row_count.':F'.$row_count)->setCellValue('E'.$row_count, $sum_at_amt);
                $cur_worksheet->setCellValue('G'.$row_count, $sum_ft);
                $cur_worksheet->mergeCells('H'.$row_count.':I'.$row_count)->setCellValue('H'.$row_count, $sum_ft_amt);
                $cur_worksheet->setCellValue('J'.$row_count, $sum_ft_img);
                $cur_worksheet->mergeCells('K'.$row_count.':L'.$row_count)->setCellValue('K'.$row_count, $sum_ft_img_amt);
                // $cur_worksheet->setCellValue('M'.$row_count, $sum_ft_w_img);
                // $cur_worksheet->mergeCells('N'.$row_count.':O'.$row_count)->setCellValue('N'.$row_count, $sum_ft_w_img_amt);
                // $cur_worksheet->setCellValue('P'.$row_count, $sum_grs);
                // $cur_worksheet->mergeCells('Q'.$row_count.':R'.$row_count)->setCellValue('Q'.$row_count, $sum_grs_amt);
                // $cur_worksheet->setCellValue('S'.$row_count, $sum_grs_biz);
                // $cur_worksheet->mergeCells('T'.$row_count.':U'.$row_count)->setCellValue('T'.$row_count, $sum_grs_biz_amt);
                // $cur_worksheet->setCellValue('V'.$row_count, $sum_grs_sms);
                // $cur_worksheet->mergeCells('W'.$row_count.':X'.$row_count)->setCellValue('W'.$row_count, $sum_grs_sms_amt);
                // $cur_worksheet->setCellValue('Y'.$row_count, $sum_grs_mms);
                // $cur_worksheet->mergeCells('Z'.$row_count.':AA'.$row_count)->setCellValue('Z'.$row_count, $sum_grs_mms_amt);
                // $cur_worksheet->setCellValue('AB'.$row_count, $sum_nas);
                // $cur_worksheet->mergeCells('AC'.$row_count.':AD'.$row_count)->setCellValue('AC'.$row_count, $sum_nas_amt);
                // $cur_worksheet->setCellValue('AE'.$row_count, $sum_nas_sms);
                // $cur_worksheet->mergeCells('AF'.$row_count.':AG'.$row_count)->setCellValue('AF'.$row_count, $sum_nas_sms_amt);
                // $cur_worksheet->setCellValue('AH'.$row_count, $sum_nas_mms);
                // $cur_worksheet->mergeCells('AI'.$row_count.':AJ'.$row_count)->setCellValue('AI'.$row_count, $sum_nas_mms_amt);
                $cur_worksheet->setCellValue('M'.$row_count, $sum_smt_sms);
                $cur_worksheet->mergeCells('N'.$row_count.':O'.$row_count)->setCellValue('N'.$row_count, $sum_smt_sms_amt);
                $cur_worksheet->setCellValue('P'.$row_count, $sum_smt);
                $cur_worksheet->mergeCells('Q'.$row_count.':R'.$row_count)->setCellValue('Q'.$row_count, $sum_smt_amt);

                $cur_worksheet->setCellValue('S'.$row_count, $sum_rcs_sms);
                $cur_worksheet->mergeCells('T'.$row_count.':U'.$row_count)->setCellValue('T'.$row_count, $sum_rcs_sms_amt);
                $cur_worksheet->setCellValue('V'.$row_count, $sum_rcs);
                $cur_worksheet->mergeCells('W'.$row_count.':X'.$row_count)->setCellValue('W'.$row_count, $sum_rcs_amt);

                // $cur_worksheet->setCellValue('S'.$row_count, $sum_smt_mms);
                // $cur_worksheet->mergeCells('T'.$row_count.':U'.$row_count)->setCellValue('T'.$row_count, $sum_smt_mms_amt);
                $cur_worksheet->mergeCells('Y'.$row_count.':Y'.$row_count)->setCellValue('Y'.$row_count, round($sum_tot_amt));

                $cur_worksheet->getStyle('B'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('D'.$row_count.':Y'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $cur_worksheet->getStyle('D'.$row_count.':Y'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count.':Y'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle('B'.$row_count.':Y'.$row_count)->getFill()->getStartColor()->setARGB('00AEAAAA');
                $cur_worksheet->getStyle('B'.$row_count.':Y'.$row_count)->getFont()->setSize(10);
                $cur_worksheet->getStyle("B".$row_count.":Y".$row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $cur_worksheet->getStyle('D'.($start_table_row + 2).':S'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");
                $cur_worksheet->getStyle('E'.($start_table_row + 2).':E'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('H'.($start_table_row + 2).':H'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('K'.($start_table_row + 2).':K'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('N'.($start_table_row + 2).':N'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('Q'.($start_table_row + 2).':Q'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('T'.($start_table_row + 2).':T'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('W'.($start_table_row + 2).':W'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                // $cur_worksheet->getStyle('Z'.($start_table_row + 2).':Z'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                // $cur_worksheet->getStyle('AC'.($start_table_row + 2).':AC'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                // $cur_worksheet->getStyle('AF'.($start_table_row + 2).':AF'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                // $cur_worksheet->getStyle('AI'.($start_table_row + 2).':AI'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                // $cur_worksheet->getStyle('AL'.($start_table_row + 2).':AL'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                // $cur_worksheet->getStyle('AO'.($start_table_row + 2).':AO'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                // $cur_worksheet->getStyle('AR'.($start_table_row + 2).':AR'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('A1');
            }

            $spreadsheet->setActiveSheetIndex(0);
            $filename = $r->top_vendor.'(월정산_상위관리자)_'.$value->period_name;

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');

            // $writer = new Xlsx($spreadsheet);
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }
    }

    public function download($dl_type)
    {
        $value = json_decode($this->input->post('value'));
        $total = json_decode($this->input->post('total'));

        // 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        //log_message("ERROR", "DL TYPE : ".$dl_type);

        if ($dl_type == "admin")
        {
            // 필드명을 기록한다.
            // 글꼴 및 정렬
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A1:N1');

            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A3:N3');

            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(15);

            $this->excel->getActiveSheet()->mergeCells('A1:N1');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "발 송 내 역  정 산");

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '업체명');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '월사용료');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '충전금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '알림톡');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '친구톡(텍스트)');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, '친구톡(이미지)');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, '친구톡(와이드)');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'WEB(A) KT,LG');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, 'WEB(A) SKT');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 3, 'WEB(A) SMS');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 3, 'WEB(A) MMS');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 3, 'WEB(B)');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, 'WEB(B) SMS');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 3, 'WEB(B) MMS');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, '문자 SMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, '문자 LMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, '문자 MMS');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 3, 'RCS SMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 3, 'RCS LMS');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 3, '발송합계');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, '전송매출');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 3, '수익금액');

            $row = 4;
            foreach($value as $val) {
                // 데이터를 읽어서 순차로 기록한다.
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->vendor);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->full_care);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val->deposit);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->at);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val->ft);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val->ft_img);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val->ft_w_img);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val->grs );
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val->grs_biz );
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $val->grs_sms);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $val->grs_mms);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->nas);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->nas_sms);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->nas_mms);

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val->smt_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val->smt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val->smt_mms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $val->rcs_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $val->rcs);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->row_sum );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->row_amount );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->row_margin );
                $row++;
            }

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "합 계");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $total[0]->full_care);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $total[0]->deposit);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $total[0]->at);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $total[0]->ft);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $total[0]->ft_img);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $total[0]->ft_w_img);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $total[0]->grs );
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $total[0]->grs_biz );
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $total[0]->grs_sms);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $total[0]->grs_mms );
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $total[0]->nas);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $total[0]->nas_sms );
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $total[0]->nas_mms );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $total[0]->smt_sms );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $total[0]->smt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $total[0]->smt_mms );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $total[0]->rcs_sms );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $total[0]->rcs);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $total[0]->row_sum );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $total[0]->row_amount );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $total[0]->row_margin );

            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A'.$row.':N'.$row);

            $row++;

            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A4:A'.$row);
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'B4:N'.$row);


        } else
        {
            // 필드명을 기록한다.
            // 글꼴 및 정렬
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A1:V1');

            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A3:V4');

            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30); //업체명
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(8); //월사용료
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(9); //알림톡 건수
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(6); //알림톡 마진
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(11); //알림톡 금액
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(11);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(9);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(11);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(9);
            // $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(6);
            // $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(11);
            // $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(9);
            // $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(6);
            // $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(11);
            // $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(9);
            // $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(6);
            // $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(11);
            // $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(9);
            // $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(6);
            // $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(11);
            // $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(9);
            // $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(6);
            // $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(11);
            // $this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(9);
            // $this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(6);
            // $this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(11);
            // $this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(9);
            // $this->excel->getActiveSheet()->getColumnDimension('AE')->setWidth(6);
            // $this->excel->getActiveSheet()->getColumnDimension('AF')->setWidth(11);
            // $this->excel->getActiveSheet()->getColumnDimension('AG')->setWidth(9);
            // $this->excel->getActiveSheet()->getColumnDimension('AH')->setWidth(6);
            // $this->excel->getActiveSheet()->getColumnDimension('AI')->setWidth(11);
            // $this->excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(9);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(11);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(9);
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(11);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(9);
            $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(11);
            $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(11);
            $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(13);

            $this->excel->getActiveSheet()->mergeCells('A1:V1');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "발 송 내 역  정 산");

            $this->excel->getActiveSheet()->mergeCells('A3:A4');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '업체명');

            $this->excel->getActiveSheet()->mergeCells('B3:B4');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '월사용료');

            $this->excel->getActiveSheet()->mergeCells('C3:E3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '알림톡');

            $this->excel->getActiveSheet()->mergeCells('F3:H3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, '친구톡(텍스트)');

            $this->excel->getActiveSheet()->mergeCells('I3:K3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, '친구톡(이미지)');
            // $this->excel->getActiveSheet()->mergeCells('L3:N3');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 3, '친구톡(와이드)');
            // $this->excel->getActiveSheet()->mergeCells('O3:Q3');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 3, 'WEB(A) KT,LG');
            // $this->excel->getActiveSheet()->mergeCells('R3:T3');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, 3, 'WEB(A) SKT');
            // $this->excel->getActiveSheet()->mergeCells('U3:W3');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, 3, 'WEB(A) SMS');
            // $this->excel->getActiveSheet()->mergeCells('X3:Z3');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, 3, 'WEB(A) MMS');
            // $this->excel->getActiveSheet()->mergeCells('AA3:AC3');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, 3, 'WEB(B)');
            // $this->excel->getActiveSheet()->mergeCells('AD3:AF3');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(29, 3, 'WEB(B) SMS');
            // $this->excel->getActiveSheet()->mergeCells('AG3:AI3');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(32, 3, 'WEB(B) MMS');
            $this->excel->getActiveSheet()->mergeCells('L3:N3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 3, '문자 SMS');
            $this->excel->getActiveSheet()->mergeCells('O3:Q3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 3, '문자 LMS');
            $this->excel->getActiveSheet()->mergeCells('R3:T3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, 3, '문자 MMS');
            // $this->excel->getActiveSheet()->mergeCells('U3:V4');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, 3, '충전금액');
            // $this->excel->getActiveSheet()->mergeCells('W3:X4');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, 3, '총수익');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, 4, '금액');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, 4, '건수');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, 4, '마진');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, 4, '금액');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, 4, '건수');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(24, 4, '마진');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(25, 4, '금액');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, 4, '건수');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, 4, '마진');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(28, 4, '금액');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(29, 4, '건수');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(30, 4, '마진');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(31, 4, '금액');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(32, 4, '건수');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(33, 4, '마진');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(34, 4, '금액');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(35, 4, '건수');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(36, 4, '마진');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(37, 4, '금액');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(38, 4, '건수');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(39, 4, '마진');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(40, 4, '금액');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(41, 4, '건수');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(42, 4, '마진');
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(43, 4, '금액');


            $row = 5;
            $voucher_flag = "";
            foreach($value as $val) {

                // 데이터를 읽어서 순차로 기록한다.
                if($val->voucher_cnt>1&&$val->vendor != $voucher_flag){
                    $rowss = $row+1;
                    $this->excel->getActiveSheet()->mergeCells('A'.$row.':A'.$rowss);
                    $this->excel->getActiveSheet()->mergeCells('B'.$row.':B'.$rowss);

                }

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->vendor);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->full_care);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val->at);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->at_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val->at_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val->ft);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val->ft_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val->ft_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val->ft_img);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $val->ft_img_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $val->ft_img_amt);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->ft_w_img);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->ft_w_img_margin);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->ft_w_img_amt);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $val->grs);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $val->grs_margin);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $val->grs_amt);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $val->grs_biz);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $val->grs_biz_margin);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $val->grs_biz_amt);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $val->grs_sms);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $val->grs_sms_margin);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $val->grs_sms_amt);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, $val->grs_mms);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(24, $row, $val->grs_mms_margin);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(25, $row, $val->grs_mms_amt);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $val->nas);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $val->nas_margin);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(28, $row, $val->nas_amt);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(29, $row, $val->nas_sms);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(30, $row, $val->nas_sms_margin);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(31, $row, $val->nas_sms_amt);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(32, $row, $val->nas_mms);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(33, $row, $val->nas_mms_margin);
                // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(34, $row, $val->nas_mms_amt);

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->smt_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->smt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->smt_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $val->smt_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $val->smt_sms_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $val->smt_sms_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $val->smt_mms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $val->smt_mms_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $val->smt_mms_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $val->month_deposit);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $val->row_margin);
                $voucher_flag = $val->vendor;
                $row++;
            }

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "합 계");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $total[0]->full_care);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $total[0]->at);
            $this->excel->getActiveSheet()->mergeCells('D'.$row.':E'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $total[0]->at_amt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $total[0]->ft);
            $this->excel->getActiveSheet()->mergeCells('G'.$row.':H'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $total[0]->ft_amt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $total[0]->ft_img);
            $this->excel->getActiveSheet()->mergeCells('J'.$row.':K'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $total[0]->ft_img_amt);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $total[0]->ft_w_img);
            // $this->excel->getActiveSheet()->mergeCells('M'.$row.':N'.$row);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $total[0]->ft_w_img_amt);
            //
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $total[0]->grs);
            // $this->excel->getActiveSheet()->mergeCells('P'.$row.':Q'.$row);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $total[0]->grs_amt);
            //
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $total[0]->grs_biz);
            // $this->excel->getActiveSheet()->mergeCells('S'.$row.':T'.$row);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $total[0]->grs_biz_amt);
            //
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $total[0]->grs_sms);
            // $this->excel->getActiveSheet()->mergeCells('V'.$row.':W'.$row);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $total[0]->grs_sms_amt);
            //
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, $total[0]->grs_mms);
            // $this->excel->getActiveSheet()->mergeCells('Y'.$row.':Z'.$row);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(24, $row, $total[0]->grs_mms_amt);
            //
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $total[0]->nas);
            // $this->excel->getActiveSheet()->mergeCells('AB'.$row.':AC'.$row);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $total[0]->nas_amt);
            //
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(29, $row, $total[0]->nas_sms);
            // $this->excel->getActiveSheet()->mergeCells('AE'.$row.':AF'.$row);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(30, $row, $total[0]->nas_sms_amt);
            //
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(32, $row, $total[0]->nas_mms);
            // $this->excel->getActiveSheet()->mergeCells('AH'.$row.':AI'.$row);
            // $this->excel->getActiveSheet()->setCellValueByColumnAndRow(33, $row, $total[0]->nas_mms_amt);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $total[0]->smt_sms);
            $this->excel->getActiveSheet()->mergeCells('M'.$row.':N'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $total[0]->smt_sms_amt);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $total[0]->smt);
            $this->excel->getActiveSheet()->mergeCells('P'.$row.':Q'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $total[0]->smt_amt);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $total[0]->smt_mms);
            $this->excel->getActiveSheet()->mergeCells('S'.$row.':T'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $total[0]->smt_mms_amt);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $total[0]->month_deposit );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $total[0]->row_sum_margin );

            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A'.$row.':V'.$row);

            $row++;

            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A5:A'.$row);
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'B5:V'.$row);
        }
        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="summary.xls"');
        header('Cache-Control: max-age=0');

        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
    }

    public function download_email() {
        $value = json_decode($this->input->post('value'));

        $sql = "
            select
            	a.period_name,
                trim(b.mem_username) as mem_username, trim(b.mem_email) as mem_email
            from cb_monthly_report a left join
                 cb_member b on a.end_mem_id = b.mem_id
            where a.period_name='".$value->period_name."' and a.end_mem_id in (".$value->mem_id.")
            order by b.mem_id;
        ";

        //log_message("ERROR", $value->mem_id);
        //log_message("ERROR", $sql);
        $result_query = $this->db->query($sql)->result();


        $spreadsheet = new Spreadsheet();
        $cur_worksheet = $spreadsheet->getActiveSheet();

        $next_row_count = 1;

        $cur_worksheet->setCellValue('A1', "업체명");
        $cur_worksheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('A1')->getFont()->setSize(13);
        $cur_worksheet->getRowDimension(1)->setRowHeight(25);
        $cur_worksheet->getStyle('A1')->getFont()->setBold(true);
        $cur_worksheet->setCellValue('B1', "e-mail");
        $cur_worksheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle('B1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('B1')->getFont()->setSize(13);
        $cur_worksheet->getStyle('B1')->getFont()->setBold(true);
        $cur_worksheet->setCellValue('C1', "제목");
        $cur_worksheet->getStyle('C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle('C1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('C1')->getFont()->setSize(13);
        $cur_worksheet->getStyle('C1')->getFont()->setBold(true);
        $cur_worksheet->setCellValue('D1', "내용");
        $cur_worksheet->getStyle('D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle('D1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('D1')->getFont()->setSize(13);
        $cur_worksheet->getStyle('D1')->getFont()->setBold(true);
        $cur_worksheet->setCellValue('E1', "첨부파일명");
        $cur_worksheet->getStyle('E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $cur_worksheet->getStyle('E1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $cur_worksheet->getStyle('E1')->getFont()->setSize(13);
        $cur_worksheet->getStyle('E1')->getFont()->setBold(true);

        if ($result_query) {
            foreach ($result_query as $r) {
                $dateStr = explode("-",$r->period_name);

                $companyName = $r->mem_username;
                $email = $r->mem_email;
                $title = "(주)대형네트웍스 ".$dateStr[1]."월 마트톡 정산내역서";
                $bady = "안녕하세요.
(주)대형네트웍스 경영지원팀 입니다.

".$dateStr[1]."월 정산내역서 송부 하오니  확인 부탁드립니다.
(세금계산서 별도 발행)


감사합니다.";
                $attachment = $r->mem_username."_".$r->period_name.".xlsx";

                $next_row_count++;

                $cur_worksheet->setCellValue('A'.$next_row_count, $companyName);
                $cur_worksheet->getStyle('A'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $cur_worksheet->setCellValue('B'.$next_row_count, $email);
                $cur_worksheet->getStyle('B'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $cur_worksheet->setCellValue('C'.$next_row_count, $title);
                $cur_worksheet->getStyle('C'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $cur_worksheet->setCellValue('D'.$next_row_count, $bady);
                $cur_worksheet->getStyle('D'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $cur_worksheet->setCellValue('E'.$next_row_count, $attachment);
                $cur_worksheet->getStyle('E'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
        }

        $filename = 'Email발송_'.$value->period_name;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');

        // $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    //정산 세금계산서 엑셀 다운로드 2021-02-15
	public function download_taxbill() {
        $value = json_decode($this->input->post('value'));
        $sql = "
            SELECT
				DISTINCT
				tax.cur_date,
				tax.cur_day,
				tax.period_name,
				tax.mem_id,
				tax.mem_username,
				tax.mem_email,
				tax.mem_biz_reg_no,
				tax.mem_biz_reg_name,
				tax.mem_biz_reg_rep_name,
				tax.mem_biz_reg_add1,
				tax.mem_biz_reg_add2,
				tax.mem_biz_reg_add3,
				tax.mem_biz_reg_biz,
				tax.mem_biz_reg_sector,
				tax.mem_biz_reg_email1,
				tax.mem_biz_reg_email2,
				tax.mem_biz_reg_bigo,
				-- tax.tot_amount,
				-- CAST(ROUND(tax.tot_amount / 1.1) AS signed integer) AS tot_amount,
				-- ROUND((tax.tot_amount / 1.1) * 0.1) AS tot_amount_tex,
				-- ROUND((tax.tot_amount / 1.1) + ((tax.tot_amount / 1.1) * 0.1)) AS tot_amount_c,
				-- tax.tot_amount AS tot_amount,
				-- tax.send_amount,
				-- CAST(ROUND(tax.send_amount / 1.1) AS signed integer) AS send_amount,
				-- ROUND((tax.send_amount / 1.1) * 0.1) AS send_amount_tex,
				-- ROUND((tax.send_amount / 1.1) + ((tax.send_amount / 1.1) * 0.1)) AS send_amount_c,
				-- tax.send_amount AS send_amount,
				-- tax.full_care,
				-- CAST(ROUND(tax.full_care / 1.1) AS signed integer) AS full_care,
				-- ROUND((tax.full_care / 1.1) * 0.1) AS full_care_tex,
				-- ROUND((tax.full_care / 1.1) + ((tax.full_care / 1.1) * 0.1)) AS full_care_c,
				(tax.full_care + tax.month_deposit) AS tot_amount,
				tax.full_care,
				tax.month_deposit,
				tax.mem_tex_io_date
            FROM (
            	SELECT
            		DATE_FORMAT(CURDATE(), '%Y%m%d') AS cur_date,
                    DATE_FORMAT(CURDATE(), '%d') AS cur_day,
            		IFNULL(a.period_name, '".$value->period_name."') as period_name,
            	    IFNULL(a.end_mem_id, b.mem_id) AS mem_id,
            		REPLACE(REPLACE(b.mem_username, '_', ' '), '/', ' ') AS mem_username,
            		b.mem_email,
            		b.mem_biz_reg_no,
            		REPLACE(REPLACE(b.mem_biz_reg_name, '_', ' '), '/', ' ') AS mem_biz_reg_name,
                    b.mem_biz_reg_rep_name,
            		b.mem_biz_reg_add1,
            		b.mem_biz_reg_add2,
            		b.mem_biz_reg_add3,
            		b.mem_biz_reg_biz,
            		b.mem_biz_reg_sector,
            		b.mem_biz_reg_email1,
            		b.mem_biz_reg_email2,
                    b.mem_biz_reg_bigo,
            		((IFNULL(a.ft, 0) * IFNULL(a.ft_price, c.mad_price_ft)) + (IFNULL(a.ft_img, 0) * IFNULL(a.ft_img_price, c.mad_price_ft_img)) + (IFNULL(a.ft_w_img, 0) * IFNULL(a.ft_w_img_price,c.mad_price_ft_w_img)) + (IFNULL(a.at, 0) * IFNULL(a.at_price,c.mad_price_at)) + ((IFNULL(a.grs, 0) + IFNULL(a.grs_biz, 0)) * IFNULL(a.grs_price, c.mad_price_grs)) + (IFNULL(a.grs_sms, 0) * IFNULL(a.grs_sms_price, c.mad_price_grs_sms)) + (IFNULL(a.grs_mms, 0) * IFNULL(a.grs_mms_price, c.mad_price_grs_mms)) + (IFNULL(a.nas, 0) * IFNULL(a.nas_price, c.mad_price_nas)) + (IFNULL(a.nas_sms, 0) * IFNULL(a.nas_sms_price, c.mad_price_nas_sms)) + (IFNULL(a.nas_mms, 0) * IFNULL(a.nas_mms_price,c.mad_price_nas_mms)) + (IFNULL(a.smt, 0) * IFNULL(a.smt_price,c.mad_price_smt)) + (IFNULL(a.smt_sms,0) * IFNULL(a.smt_sms_price,c.mad_price_smt_sms)) + (IFNULL(a.smt_mms, 0) * IFNULL(a.smt_mms_price,c.mad_price_smt_mms)) + c.mad_price_full_care) AS tot_amount,
            		((IFNULL(a.ft, 0) * IFNULL(a.ft_price, c.mad_price_ft)) + (IFNULL(a.ft_img, 0) * IFNULL(a.ft_img_price, c.mad_price_ft_img)) + (IFNULL(a.ft_w_img, 0) * IFNULL(a.ft_w_img_price,c.mad_price_ft_w_img)) + (IFNULL(a.at, 0) * IFNULL(a.at_price,c.mad_price_at)) + ((IFNULL(a.grs, 0) + IFNULL(a.grs_biz, 0)) * IFNULL(a.grs_price, c.mad_price_grs)) + (IFNULL(a.grs_sms, 0) * IFNULL(a.grs_sms_price, c.mad_price_grs_sms)) + (IFNULL(a.grs_mms, 0) * IFNULL(a.grs_mms_price, c.mad_price_grs_mms)) + (IFNULL(a.nas, 0) * IFNULL(a.nas_price, c.mad_price_nas)) + (IFNULL(a.nas_sms, 0) * IFNULL(a.nas_sms_price, c.mad_price_nas_sms)) + (IFNULL(a.nas_mms, 0) * IFNULL(a.nas_mms_price,c.mad_price_nas_mms)) + (IFNULL(a.smt, 0) * IFNULL(a.smt_price,c.mad_price_smt)) + (IFNULL(a.smt_sms,0) * IFNULL(a.smt_sms_price,c.mad_price_smt_sms)) + (IFNULL(a.smt_mms, 0) * IFNULL(a.smt_mms_price,c.mad_price_smt_mms)) ) AS send_amount,
            		c.mad_price_full_care AS full_care,
					IFNULL(month_deposit,0) AS month_deposit,
					b.mem_tex_io_date
            	FROM cb_member b
				LEFT JOIN cb_monthly_report a ON b.mem_id = a.end_mem_id and a.period_name='".$value->period_name."'
				LEFT JOIN cb_wt_member_addon c ON b.mem_id = c.mad_mem_id
				LEFT JOIN (
					SELECT cbd.mem_id, ifnull(sum(cbd.dep_deposit),0) as month_deposit
					FROM cb_deposit cbd
					WHERE dep_pay_type IN ('bank', 'vbank')
					AND dep_status = '1'
					AND DATE_FORMAT(dep_datetime, '%Y-%m') = '".$value->period_name."'
					GROUP BY cbd.mem_id
				) as depo ON b.mem_id = depo.mem_id
            	WHERE b.mem_id IN (".$value->mem_id.")
				-- AND b.mem_useyn='Y'
			) tax
			WHERE (full_care > 0 OR month_deposit > 0)
            ORDER By tax.mem_id ASC
        ";
        //log_message("ERROR", $value->mem_id);
        //log_message("ERROR", $sql);
        $result_query = $this->db->query($sql)->result();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('돋움');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        $cur_worksheet = $spreadsheet->getActiveSheet();
        $cur_worksheet->getRowDimension(2)->setRowHeight(25);

        $cur_worksheet->setTitle('엑셀업로드양식');
        $cur_worksheet->setCellValue("A6", "전자(세금)계산서 종류
(01:일반, 02:영세율)")->getStyle("A6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("B6", "작성일자")->getStyle("B6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("C6", "공급자 등록번호
            (\"-\" 없이 입력)")->getStyle("C6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("D6", "공급자
 종사업장번호")->getStyle("D6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("E6", "공급자 상호")->getStyle("E6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("F6", "공급자 성명")->getStyle("F6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("G6", "공급자 사업장주소")->getStyle("G6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("H6", "공급자 업태")->getStyle("H6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("I6", "공급자 종목")->getStyle("I6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("J6", "공급자 이메일")->getStyle("J6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("K6", "공급받는자 등록번호
(\"-\" 없이 입력)")->getStyle("K6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("L6", "공급받는자
종사업장번호")->getStyle("L6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("M6", "공급받는자 상호")->getStyle("M6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("N6", "공급받는자 성명")->getStyle("N6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("O6", "공급받는자 사업장주소")->getStyle("O6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("P6", "공급받는자 업태")->getStyle("P6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("Q6", "공급받는자 종목")->getStyle("Q6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("R6", "공급받는자 이메일1")->getStyle("R6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("S6", "공급받는자 이메일2")->getStyle("S6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("T6", "공급가액")->getStyle("T6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("U6", "세액")->getStyle("U6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("V6", "비고")->getStyle("V6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("W6", "일자1
(2자리, 작성년월 제외)")->getStyle("W6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("X6", "품목1")->getStyle("X6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("Y6", "규격1")->getStyle("Y6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("Z6", "수량1")->getStyle("Z6")->getAlignment()->setWrapText(true);

        $cur_worksheet->setCellValue("AA6", "단가1")->getStyle("AA6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AB6", "공급가액1")->getStyle("AB6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AC6", "세액1")->getStyle("AC6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AD6", "품목비고1")->getStyle("AD6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AE6", "일자2
(2자리, 작성년월 제외)")->getStyle("AE6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AF6", "품목2")->getStyle("AF6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AG6", "규격2")->getStyle("AG6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AH6", "수량2")->getStyle("AH6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AI6", "단가2")->getStyle("AI6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AJ6", "공급가액2")->getStyle("AJ6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AK6", "세액2")->getStyle("AK6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AL6", "품목비고2")->getStyle("AL6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AM6", "일자3
(2자리, 작성년월 제외)")->getStyle("AM6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AN6", "품목3")->getStyle("AN6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AO6", "규격3")->getStyle("AO6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AP6", "수량3")->getStyle("AP6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AQ6", "단가3")->getStyle("AQ6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AR6", "공급가액3")->getStyle("AR6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AS6", "세액3")->getStyle("AS6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AT6", "품목비고3")->getStyle("AT6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AU6", "일자4
(2자리, 작성년월 제외)")->getStyle("AU6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AV6", "품목4")->getStyle("AV6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AW6", "규격4")->getStyle("AW6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AX6", "수량4")->getStyle("AX6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AY6", "단가4")->getStyle("AY6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("AZ6", "공급가액4")->getStyle("AZ6")->getAlignment()->setWrapText(true);

        $cur_worksheet->setCellValue("BA6", "세액4")->getStyle("BA6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("BB6", "품목비고4")->getStyle("BB6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("BC6", "현금")->getStyle("BC6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("BD6", "수표")->getStyle("BD6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("BE6", "어음")->getStyle("BE6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("BF6", "외상미수금")->getStyle("BF6")->getAlignment()->setWrapText(true);
        $cur_worksheet->setCellValue("BG6", "영수(01),
청구(02)")->getStyle("BG6")->getAlignment()->setWrapText(true);

        $next_row_count = 6;

        if ($result_query) {
            foreach ($result_query as $r) {
                $next_row_count++;
                $cur_worksheet->setCellValueExplicit('A'.$next_row_count, "01", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('B'.$next_row_count, $r->cur_date, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('C'.$next_row_count, "3648800974", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                $cur_worksheet->setCellValueExplicit('E'.$next_row_count, "주식회사대형네트웍스", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('F'.$next_row_count, "송종근", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('G'.$next_row_count, "경상남도 창원시 의창구 차룡로48번길 54, 302호(팔용동,경남창원산학융합본부 기업연구관)", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('H'.$next_row_count, "정보서비스", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('J'.$next_row_count, "finance@dhncorp.co.kr", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('K'.$next_row_count, $r->mem_biz_reg_no, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                $cur_worksheet->setCellValueExplicit('M'.$next_row_count, $r->mem_biz_reg_name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('N'.$next_row_count, $r->mem_biz_reg_rep_name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                $biz_reg_add = $r->mem_biz_reg_add1;
                if ($r->mem_biz_reg_add2 != "") {
                    $biz_reg_add .= " ".$r->mem_biz_reg_add2;
                }
                if ($r->mem_biz_reg_add3 != "") {
                    $biz_reg_add .= " ".$r->mem_biz_reg_add3;
                }

                $cur_worksheet->setCellValueExplicit('O'.$next_row_count, $biz_reg_add, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('P'.$next_row_count, $r->mem_biz_reg_biz, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('Q'.$next_row_count, $r->mem_biz_reg_sector, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('R'.$next_row_count, $r->mem_email, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); //담당자 이메일
                $cur_worksheet->setCellValueExplicit('S'.$next_row_count, $r->mem_biz_reg_email2, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                $tot_amount = round($r->tot_amount / 1.1); //공급가액
                // $tot_amount_tax = round($tot_amount * 0.1); //공급가 세액
                $tot_amount_tax = $r->tot_amount - $tot_amount; //공급가 세액
                $cur_worksheet->setCellValueExplicit('T'.$next_row_count, $tot_amount, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('U'.$next_row_count, $tot_amount_tax, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                //$cur_worksheet->setCellValueExplicit('T'.$next_row_count, $r->tot_amount, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                //$cur_worksheet->setCellValueExplicit('U'.$next_row_count, $r->tot_amount_tex, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $cur_worksheet->setCellValueExplicit('V'.$next_row_count, $r->mem_biz_reg_bigo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                //품목1
				if($r->full_care > 0 and $r->month_deposit > 0){ //월이용료, 충전금액이 모두 있는 경우
					$name1 = "월이용료";
					$name2 = "충전금액";
					$data1 = round($r->full_care / 1.1); //월이용료
					$data1_tax = round($data1 * 0.1); //월이용료 세액
					$data2 = round($r->month_deposit / 1.1); //충전금액
					$data2_tax = round($data2 * 0.1); //충전금액 세액
				}else if($r->full_care > 0 and $r->month_deposit == 0){ //월이용료만 있는 경우
					$name1 = "월이용료";
					$name2 = "";
					$data1 = round($r->full_care / 1.1); //월이용료
					$data1_tax = round($data1 * 0.1); //월이용료 세액
					$data2 = "";
					$data2_tax = "";
				}else if($r->full_care == 0 and $r->month_deposit > 0){ //충전금액만 있는 경우
					$name1 = "충전금액";
					$name2 = "";
					$data1 = round($r->month_deposit / 1.1); //충전금액
					$data1_tax = round($data1 * 0.1); //충전금액 세액
					$data2 = "";
					$data2_tax = "";
				}else{
					$name1 = "";
					$name2 = "";
					$data1 = "";
					$data1_tax = "";
					$data2 = "";
					$data2_tax = "";
				}

				$cur_worksheet->setCellValueExplicit('W'.$next_row_count, $r->mem_tex_io_date, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); //발행일1
                $cur_worksheet->setCellValueExplicit('X'.$next_row_count, $name1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); //품목1
                $cur_worksheet->setCellValueExplicit('AB'.$next_row_count, $data1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); //공급가액1
                $cur_worksheet->setCellValueExplicit('AC'.$next_row_count, $data1_tax, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); //세액1

                $cur_worksheet->setCellValueExplicit('AE'.$next_row_count, $r->mem_tex_io_date, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); //발행일2
                $cur_worksheet->setCellValueExplicit('AF'.$next_row_count, $name2, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); //품목2
                $cur_worksheet->setCellValueExplicit('AJ'.$next_row_count, $data2, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); //공급가액2
                $cur_worksheet->setCellValueExplicit('AK'.$next_row_count, $data2_tax, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); //세액2

                $cur_worksheet->setCellValueExplicit('BG'.$next_row_count, "02", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            }
        }

        $filename = '세금계산서_'.$value->period_name;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');

        // $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

}
?>
