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

		$post_userid = ($this->input->post('userid') != "") ? $this->input->post('userid') : "ALL";
		//echo "param_userid : ". $view['param']['userid'] ."<br>";
		//echo "post_userid : ". $post_userid ."<br>";
        
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
        if(($view['param']['userid'] == 'dhn' || $view['param']['userid'] == 'dhnadmin') && $view['param']['companyName'] == '')
        {
            $skin_type = "ALL";
            //log_message("ERROR", "query 1 ========================");
            $sql = "
                SELECT A.*, B.mem_username as vendor, B.mem_userid as mem_userid, B.mem_level
                FROM (
                   SELECT
                        period_name,
                        -- manager_mem_id,
                        CASE WHEN id_level2 = 0 THEN manager_mem_id ELSE id_level2 END manager_mem_id,
                        -- (select mem_username from cb_member cm where cm.mem_id = manager_mem_id) as vendor,
                        -- (select mem_userid from cb_member cm where cm.mem_id = manager_mem_id) as mem_userid,
                       -- end_mem_id,
                           ( select sum(dep_deposit)
                            from cb_deposit cd inner join
                                    cb_member cm on cd.mem_id = cm.mem_id
                    	  where exists ( select 1 from cb_member_register cmr
                                            where cmr.mrg_recommend_mem_id = CASE WHEN id_level2 = 0 THEN manager_mem_id ELSE id_level2 END -- manager_mem_id
                                              and cmr.mem_id = cm.mem_id)
                              and DATE_FORMAT(cd.dep_deposit_datetime, '%Y-%m')  = period_name) as deposit,
                		sum(cwma.mad_price_full_care) as full_care,
                        sum(at) as at,
                        sum(at * dhn_margin_at) as at_amt,
                        sum(at_amount) as at_amount,
                        sum(ft) as ft,
                        sum(ft * dhn_margin_ft) as ft_amt,
                        sum(ft_amount) as ft_amount,
                        sum(ft_img) as ft_img,
                        sum(ft_img * dhn_margin_ft_img) as ft_img_amt,
                        sum(ft_img_amount) as ft_img_amount,
                        sum(ft_w_img) as ft_w_img,
                        sum(ft_w_img * dhn_margin_ft_img) as ft_w_img_amt,
                        sum(ft_w_img_amount) as ft_w_img_amount,
                        -- sum(sms) as sms,
                        -- sum(sms * sms_price) as sms_amt,
                        -- sum(lms) as lms,
                        -- sum(lms * lms_price) as lms_amt,
                        -- sum(phn) as phn,
                        -- sum(phn * phn_price) as phn_amt,
                        -- sum(phn_amount) as phn_amount,
                        sum(grs) as grs,
                        sum(grs * dhn_margin_grs) as grs_amt,
                        sum(grs_amount) as grs_amount,
                        sum(grs_biz) as grs_biz,
                        sum(grs_biz * dhn_margin_grs_biz) as grs_biz_amt,
                        sum(grs_biz_amount) as grs_biz_amount,
                        sum(grs_sms) as grs_sms,
                        sum(grs_sms * dhn_margin_grs_sms) as grs_sms_amt,
                        sum(grs_sms_amount) as grs_sms_amount,
                        sum(grs_mms) as grs_mms,
                        sum(grs_mms * dhn_margin_grs_mms) as grs_mms_amt,
                        sum(grs_mms_amount) as grs_mms_amount,
                        sum(nas) as nas,
                        sum(nas * dhn_margin_nas) as nas_amt,
                        sum(nas_amount) as nas_amount,
                        sum(nas_sms) as nas_sms,
                        sum(nas_sms * dhn_margin_nas_sms) as nas_sms_amt,
                        sum(nas_sms_amount) as nas_sms_amount,
                        sum(nas_mms) as nas_mms,
                        sum(nas_sms * dhn_margin_nas_mms) as nas_mms_amt,
                        sum(nas_sms_amount) as nas_mms_amount,
                        sum(smt) as smt,
                        sum(smt * dhn_margin_smt) as smt_amt,
                        sum(smt_amount) as smt_amount,
                        sum(smt_sms) as smt_sms,
                        sum(smt_sms * dhn_margin_smt_sms) as smt_sms_amt,
                        sum(smt_sms_amount) as smt_sms_amount,
                        sum(smt_mms) as smt_mms,
                        sum(smt_sms * dhn_margin_smt_mms) as smt_mms_amt,
                        sum(smt_sms_amount) as smt_mms_amount,
                        -- sum(f_015) as f_015,
                        -- sum(f_015 * f_015_price) as f_015_amt,
                        -- sum(f_015_amount) as f_015_amount,
                        base_up_ft,
                        base_up_ft_img,
                        base_up_ft_w_img,
                        base_up_at,
                        base_up_grs,
                        base_up_grs_sms,
                        base_up_nas,
                        base_up_nas_sms,
                        base_up_phn,
                        base_up_015,
                        base_up_grs_biz,
                        base_up_smt,
                        base_up_smt_sms,
                        base_up_smt_mms,
                        id_level1,
                        id_level2,
						monthly_fee
                    FROM
                        cb_monthly_report cmr left JOIN
                        cb_wt_member_addon cwma on cmr.end_mem_id = cwma.mad_mem_id
                    WHERE
                        period_name = '".$view['param']['startDate']."'
                    group by -- manager_mem_id ,
                    	id_level1,
                    	id_level2,
                        base_up_ft,
                        base_up_ft_img,
                        base_up_ft_w_img,
                        base_up_at,
                        base_up_grs,
                        base_up_grs_sms,
                        base_up_nas,
                        base_up_nas_sms,
                        base_up_phn,
                        base_up_015,
                        base_up_grs_biz,
                        base_up_smt,
                        base_up_smt_sms,
                        base_up_smt_mms
                    ORDER BY period_name , manager_mem_id , end_mem_id
                )  A Left JOIN cb_member B ON A.manager_mem_id = B.mem_id
                    ";
            //log_message("ERROR", "/biz/manager/settlement > sql : ". $sql);
			$list = $this->db->query($sql)->result();
            $view['list'] = $list;
        } else
        /* 중간 관리자가 조회 조건일 경우 하위 업체 정산 내역 표시 */
        {
            $skin_type = "END";
            $post_userid = $this->input->post('userid');
			//echo "post_userid : ". $post_userid .", userid : ". $view['param']['userid'] .", startDate : ". $view['param']['startDate'] ."<br>";
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
                        (c_cwma.mad_price_at - cwma.mad_price_at) as at_margin,
                        at * (c_cwma.mad_price_at - cwma.mad_price_at) as at_amt,
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
                    
                }
                
            }

/*             $sql = "
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
                        at,
                        at_price,
                        at * at_price as at_amt,
                        ft,
                        ft_price,
                        ft*ft_price as ft_amt,
                        ft_img,
                        ft_img_price,
                        ft_img * ft_img_price as ft_img_amt,
                        sms,
                        sms_price,
                        sms * sms_price as sms_amt,
                        lms,
                        lms_price,
                        lms * lms_price as lms_amt,
                        phn,
                        phn_price,
                        phn * phn_price as phn_amt,
                        grs,
                        grs_price,
                        grs * grs_price as grs_amt,
                        grs_sms,
                        grs_sms_price,
                        grs_sms * grs_sms_price as grs_sms_amt,
                        nas,
                        nas_price,
                        nas * nas_price as nas_amt,
                        nas_sms,
                        nas_sms_price,
                        nas_sms * nas_sms_price as nas_sms_amt,
                        f_015,
                        f_015_price,
                        f_015 * f_015_price as f_015_amt
                    FROM
                        cb_monthly_report cmr inner join
                        cb_member cm on cm.mem_id = cmr.manager_mem_id
                    WHERE
                        period_name = '".$view['param']['startDate']."'
                        and cm.mem_userid = '".$this->input->post('userid')."'
                    ORDER BY period_name , manager_mem_id , end_mem_id
                    "; */
            //log_message("ERROR", $sql);
            $list = $this->db->query($sql)->result();
            $view['list'] = $list;
        }
        
		$view['param']['userid'] = $post_userid;
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

        $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
        $cur_worksheet->setCellValue("B".$row_count, "친구톡(와이드)");
        $cur_worksheet->setCellValue("C".$row_count, $r->ft_w_img);
        $cur_worksheet->setCellValue("D".$row_count, $r->ft_w_img_price);
        $cur_worksheet->setCellValue("E".$row_count, $r->s_ft_w_img_amount);
        $row_count += 1;
        
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
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자C)");
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
                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자C)");
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
                    $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                    $cur_worksheet->setCellValue("B".$row_count, "2차 발신(문자C)");
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
            	REPLACE(REPLACE(b.mem_username, '_', ' '), '/', ' ') as mem_username,
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
            	(a.ft + a.ft_img + a.ft_w_img + a.at + a.grs + a.grs_biz + a.grs_sms + a.grs_mms + a.nas + a.nas_sms + a.nas_mms + a.smt + a.smt_sms + a.smt_mms) as tot_send,
                ((a.ft * a.ft_price) + (a.ft_img * a.ft_img_price) + (a.ft_w_img * a.ft_w_img_price) + (a.at * a.at_price) + ((a.grs + a.grs_biz) * a.grs_price) + (a.grs_sms * a.grs_sms_price) + (a.grs_mms * a.grs_mms_price) + (a.nas * a.nas_price) + (a.nas_sms * a.nas_sms_price) + (a.nas_mms * a.nas_mms_price) + (a.smt * a.smt_price) + (a.smt_sms * a.smt_sms_price) + (a.smt_mms * a.smt_mms_price)) as send_amount,
            	((a.ft * a.ft_price) + (a.ft_img * a.ft_img_price) + (a.ft_w_img * a.ft_w_img_price) + (a.at * a.at_price) + ((a.grs + a.grs_biz) * a.grs_price) + (a.grs_sms * a.grs_sms_price) + (a.grs_mms * a.grs_mms_price) + (a.nas * a.nas_price) + (a.nas_sms * a.nas_sms_price) + (a.nas_mms * a.nas_mms_price) + (a.smt * a.smt_price) + (a.smt_sms * a.smt_sms_price) + (a.smt_mms * a.smt_mms_price) + c.mad_price_full_care) as tot_amount
            from cb_monthly_report a left join 
                 cb_member b on a.end_mem_id = b.mem_id left join
                 cb_wt_member_addon c on a.end_mem_id = c.mad_mem_id
            where a.period_name='".$value->period_name."' and a.end_mem_id in (".$value->mem_id.")
            order by b.mem_id;
        ";
        
        //log_message("ERROR", $value->mem_id);        
        //log_message("ERROR", $sql);
        $result_query = $this->db->query($sql)->result();
        $sheet_count = 0;
        $start_date = $value->period_name.'-01';
        $end_date = $value->period_name.'-'.date('t', $start_date);
        
        $spreadsheet = new Spreadsheet();
        if ($result_query) {

            foreach ($result_query as $r) {
                if ($sheet_count == 0) {
                    $cur_worksheet = $spreadsheet->getActiveSheet();
                } else {
                    $cur_worksheet = $spreadsheet->createSheet();
                }
                //$next_row_count = $this->excelTopTable($r, $cur_worksheet, $start_date, $end_date, 6, $value->period_name);
                $next_row_count = $this->excelTopTable($r, $cur_worksheet, $start_date, $end_date, $value->period_name);
                $cur_worksheet->getStyle('A1');
                
                
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
            	REPLACE(REPLACE(b.mem_username, '_', ' '), '/', ' ') as mem_username, 
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
            	(a.ft + a.ft_img + a.at + a.grs + a.grs_biz + a.grs_sms + a.grs_mms + a.nas + a.nas_sms + a.nas_mms + a.smt + a.smt_sms + a.smt_mms) as tot_send,
                ((a.ft * a.ft_price) + (a.ft_img * a.ft_img_price) + (a.at * a.at_price) + ((a.grs + a.grs_biz) * a.grs_price) + (a.grs_sms * a.grs_sms_price) + (a.grs_mms * a.grs_mms_price) + (a.nas * a.nas_price) + (a.nas_sms * a.nas_sms_price) + (a.nas_mms * a.nas_mms_price) + (a.smt * a.smt_price) + (a.smt_sms * a.smt_sms_price) + (a.smt_mms * a.smt_mms_price)) as send_amount,
            	((a.ft * a.ft_price) + (a.ft_img * a.ft_img_price) + (a.at * a.at_price) + ((a.grs + a.grs_biz) * a.grs_price) + (a.grs_sms * a.grs_sms_price) + (a.grs_mms * a.grs_mms_price) + (a.nas * a.nas_price) + (a.nas_sms * a.nas_sms_price) + (a.nas_mms * a.nas_mms_price) + (a.smt * a.smt_price) + (a.smt_sms * a.smt_sms_price) + (a.smt_mms * a.smt_mms_price) + c.mad_price_full_care) as tot_amount
            from cb_monthly_report a left join 
                 cb_member b on a.end_mem_id = b.mem_id left join
                 cb_wt_member_addon c on a.end_mem_id = c.mad_mem_id
            where a.period_name='".$value->period_name."' and a.end_mem_id in (".$value->mem_id.")
            order by b.mem_id;
        ";
        
        //log_message("ERROR", $value->mem_id);
        //log_message("ERROR", $sql);
        $result_query = $this->db->query($sql)->result();
        $sheet_count = 0;
        $start_date = $value->period_name.'-01';
        $end_date = $value->period_name.'-'.date('t', $start_date);
        $SD = $start_date;
        $ED = $end_date;
        $start_date .= ' 00:00:00';
        $end_date .= ' 23:59:59';
        
        $spreadsheet = new Spreadsheet();
        if ($result_query) {
            
            foreach ($result_query as $r) {
                if ($sheet_count == 0) {
                    $cur_worksheet = $spreadsheet->getActiveSheet();
                } else {
                    $cur_worksheet = $spreadsheet->createSheet();
                }
                
                //$next_row_count = $this->excelTopTable($r, $cur_worksheet, $SD, $ED, 6, $value->period_name);
                $next_row_count = $this->excelTopTable($r, $cur_worksheet, $SD, $ED, $value->period_name);
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

                $sql_day = "
                select
                	mst_datetime,
                	sum(mst_qty) mst_qty,
                 	(sum(mst_ft) + sum(mst_ft_img) + sum(mst_ft_w_img) + sum(mst_at) + sum(mst_grs) + sum(mst_grs_sms) + sum(mst_grs_mms) + sum(mst_nas) + sum(mst_nas_sms) + sum(mst_nas_mms) + sum(mst_smt) + sum(mst_smt_sms) + sum(mst_smt_mms)) success_qty,
                	(sum(mst_err_ft) + sum(mst_err_ft_img) + sum(mst_err_ft_w_img) + sum(mst_err_at) + sum(mst_err_grs) + sum(mst_err_grs_sms) + sum(mst_err_grs_mms) + sum(mst_err_nas) + sum(mst_err_nas_sms) + sum(mst_err_nas_mms) + sum(mst_err_smt) + sum(mst_err_smt_sms) + sum(mst_err_smt_mms)) err_qty,
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
                    sum(mst_err_015) qty_015,
                	sum(mst_err_phn) qty_phn
                from (select substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 10) mst_datetime,
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
					case when (mst_type2 = 'wc') then mst_smt else 0 end as mst_smt,
					case when (mst_type2 = 'wcs') then mst_smt else 0 end as mst_smt_sms,
					case when (mst_type2 = 'wcm') then mst_smt else 0 end as mst_smt_mms,
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
					case when (mst_type2 = 'wcm') then mst_err_smt else 0 end as mst_err_smt_mms
				from cb_wt_msg_sent where mst_mem_id = ".$r->mem_id."     and (
                        	 ( mst_reserved_dt <> '00000000000000' AND 	
                              (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$start_date."' AND '".$end_date."')
                             ) OR 
                             ( mst_reserved_dt = '00000000000000' AND 	
                              (mst_datetime BETWEEN '".$start_date."' AND '".$end_date."')
                             )
                            )) AAA 
               group by mst_datetime order by 1 desc
                ";
                
                $sel_col = explode(',', $value->col_part_name);
                //$sel_col = split($value->col_part_name, ',');
                $sel_col_count = count($sel_col);
                
                $cells = array(
                    '', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
                );
                
                $next_row_count += 1;
                
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
                if(strpos($value->col_part_name, 'ft_w_img') !== false) {
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

                if(strpos($value->col_part_name, 'smt_lms') !== false) {
                    $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                    $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "2차문자(C)");
                    $col_index += 1;
                }
                if(strpos($value->col_part_name, 'smt_sms') !== false) {
                    $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                    $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "2차문자(C) SMS");
                    $col_index += 1;
                }
                if(strpos($value->col_part_name, 'smt_mms') !== false) {
                    $cur_worksheet->getColumnDimension($cells[$col_index])->setWidth(16.00 + 0.63);
                    $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, "2차문자(C) MMS");
                    $col_index += 1;
                }
                
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
                    
                    $cur_worksheet->setCellValue('B'.$next_row_count, $dey_r->mst_datetime);
                    $cur_worksheet->getStyle('B'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->setCellValue('C'.$next_row_count, $dey_r->mst_qty);
                    $cur_worksheet->getStyle('C'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->setCellValue('D'.$next_row_count, $dey_r->success_qty);
                    $cur_worksheet->getStyle('D'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->setCellValue('E'.$next_row_count, $dey_r->err_qty);
                    $cur_worksheet->getStyle('E'.$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    
                    $col_index = 6;
                    if(strpos($value->col_part_name, 'at') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_at);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'ft_txt') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_ft);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'ft_img') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_ft_img);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'ft_w_img') !== false) {
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
                    }
                    if(strpos($value->col_part_name, 'smt_lms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_smt);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'smt_sms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_smt_sms);
                        $col_index += 1;
                    }
                    if(strpos($value->col_part_name, 'smt_mms') !== false) {
                        $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $dey_r->mst_smt_mms);
                        $col_index += 1;
                    }
                    
                    $next_row_count += 1;
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
                if(strpos($value->col_part_name, 'ft_w_img') !== false) {
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
                }
                if(strpos($value->col_part_name, 'smt_lms') !== false) {
                    $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_smt);
                    $col_index += 1;
                }
                if(strpos($value->col_part_name, 'smt_sms') !== false) {
                    $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_smt_sms);
                    $col_index += 1;
                }
                if(strpos($value->col_part_name, 'smt_mms') !== false) {
                    $cur_worksheet->setCellValue($cells[$col_index].$next_row_count, $sum_mst_smt_mms);
                    $col_index += 1;
                }
                
                $cur_worksheet->getStyle("B".$next_row_count.":".$cells[$view_col_index].$next_row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle("B".$next_row_count.":".$cells[$view_col_index].$next_row_count)->getFill()->getStartColor()->setARGB('00ACB9CA');
                //$cur_worksheet->getStyle('D4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle('B'.$list_start_row.':B'.$next_row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle('B'.$list_start_row.':B'.$next_row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('C'.$list_start_row.':'.$cells[$view_col_index].$next_row_count)->getNumberFormat()->setFormatCode("#,##0_-");
                //$list_start_row
                
                $cur_worksheet->getStyle('B'.$start_table_row.':'.$cells[$view_col_index].$next_row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                
                $cur_worksheet->getStyle('A1');
                
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

        $sql_list = "
        SELECT *,
            (at_amt + ft_amt + ft_img_amt + ft_w_img_amt + grs_amt + grs_sms_amt + grs_mms_amt + nas_amt + nas_sms_amt + nas_mms_amt + grs_biz_amt + smt_amt + smt_sms_amt + smt_mms_amt) tot_amt
        FROM (
            SELECT
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
            	(cmr.at_price - cwma.mad_price_at) as at_margin,
            	at * (cmr.at_price - cwma.mad_price_at) as at_amt,
            	ft,
            	(cmr.ft_price - cwma.mad_price_ft) as ft_margin,
            	ft*(cmr.ft_price - cwma.mad_price_ft) as ft_amt,
            	ft_img,
            	(cmr.ft_img_price - cwma.mad_price_ft_img) as ft_img_margin,
            	ft_img * (cmr.ft_img_price - cwma.mad_price_ft_img) as ft_img_amt,
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
            WHERE
                cmr.period_name = '".$value->period_name."'
            ORDER BY period_name , mem_level DESC, manager_mem_id , end_mem_id
        ) ABC 
        ";
        
        //log_message("ERROR", $sql);
        $result_query = $this->db->query($sql)->result();

        //log_message("ERROR", $sql_list);
        $result_list_query = $this->db->query($sql_list)->result();
        
        
        $start_date = $value->period_name.'-01';
        $end_date = $value->period_name.'-'.date('t', $start_date);
        
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
            $cur_worksheet->getColumnDimension('Y')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('Z')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('AA')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('AB')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('AC')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('AD')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('AE')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('AF')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('AG')->setWidth(10.63 + 0.63);

            $cur_worksheet->getColumnDimension('AH')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('AI')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('AJ')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('AK')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('AL')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('AM')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('AN')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('AO')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('AP')->setWidth(10.63 + 0.63);
            $cur_worksheet->getColumnDimension('AQ')->setWidth(9.00 + 0.63);
            $cur_worksheet->getColumnDimension('AR')->setWidth(6.00 + 0.63);
            $cur_worksheet->getColumnDimension('AS')->setWidth(10.63 + 0.63);
            
            $cur_worksheet->getColumnDimension('AT')->setWidth(17.50 + 0.63);
            
            foreach ($result_query as $r) {
                $cur_worksheet->getRowDimension(2)->setRowHeight(25);
                
                $cur_worksheet->setTitle($r->top_vendor.'_'.$period_name)
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

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "친구톡(와이드)");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_ft_w_img);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_ft_w_img);
                $row_count += 1;
                
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "Web(A) KT, LG");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_grs);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_grs);
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "Web(A) SKT");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_grs_biz);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_grs_biz);
                $row_count += 1;
                
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "Web(A) SMS");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_grs_sms);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_grs_sms);
                $row_count += 1;
                
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "Web(A) MMS");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_grs_mms);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_grs_mms);
                $row_count += 1;
                
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "Web(B)");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_nas);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_nas);
                $row_count += 1;
                
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "Web(B) SMS");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_nas_sms);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_nas_sms);
                $row_count += 1;
                
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "Web(B) MMS");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_nas_mms);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_nas_mms);
                $row_count += 1;

                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "Web(C)");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_smt);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_smt);
                $row_count += 1;
                
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "Web(C) SMS");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_smt_sms);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_smt_sms);
                $row_count += 1;
                
                $cur_worksheet->getRowDimension($row_count)->setRowHeight(21.75);
                $cur_worksheet->mergeCells("C".$row_count.":E".$row_count);
                $cur_worksheet->mergeCells("F".$row_count.":H".$row_count);
                $cur_worksheet->setCellValue("B".$row_count, "Web(C) MMS");
                $cur_worksheet->setCellValue("C".$row_count, $r->t_smt_mms);
                $cur_worksheet->setCellValue("F".$row_count, $r->mad_price_smt_mms);
                $row_count += 1;
                
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

                 $cur_worksheet->mergeCells('B'.$row_count.':AT'.$row_count)->setCellValue('B'.$row_count, "발 송 내 역  정 산");
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
                $cur_worksheet->mergeCells('M'.$row_count.':O'.$row_count)->setCellValue('M'.$row_count, "친구톡(와이드)");
                $cur_worksheet->mergeCells('P'.$row_count.':R'.$row_count)->setCellValue('P'.$row_count, "WEB(A) KT, LG");
                $cur_worksheet->mergeCells('S'.$row_count.':U'.$row_count)->setCellValue('S'.$row_count, "WEB(A) SKT");
                $cur_worksheet->mergeCells('V'.$row_count.':X'.$row_count)->setCellValue('V'.$row_count, "WEB(A) SMS");
                $cur_worksheet->mergeCells('Y'.$row_count.':AA'.$row_count)->setCellValue('Y'.$row_count, "WEB(A) MMS");
                $cur_worksheet->mergeCells('AB'.$row_count.':AD'.$row_count)->setCellValue('AB'.$row_count, "WEB(B)");
                $cur_worksheet->mergeCells('AE'.$row_count.':AG'.$row_count)->setCellValue('AE'.$row_count, "WEB(B) SMS");
                $cur_worksheet->mergeCells('AH'.$row_count.':AJ'.$row_count)->setCellValue('AH'.$row_count, "WEB(B) MMS");
                $cur_worksheet->mergeCells('AK'.$row_count.':AM'.$row_count)->setCellValue('AK'.$row_count, "WEB(C)");
                $cur_worksheet->mergeCells('AN'.$row_count.':AN'.$row_count)->setCellValue('AN'.$row_count, "WEB(C) SMS");
                $cur_worksheet->mergeCells('AQ'.$row_count.':AS'.$row_count)->setCellValue('AQ'.$row_count, "WEB(C) MMS");
                $cur_worksheet->mergeCells('AT'.$row_count.':AT'.($row_count + 1))->setCellValue('AT'.$row_count, "총수익");
                
                $cur_worksheet->getStyle('B'.$row_count.':AT'.($row_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count.':AT'.($row_count + 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count.':AT'.($row_count + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle('B'.$row_count.':AT'.($row_count + 1))->getFill()->getStartColor()->setARGB('00AEAAAA');
                $cur_worksheet->getStyle('B'.$row_count.':AT'.($row_count + 1))->getFont()->setSize(10);
                $cur_worksheet->getStyle('B'.$row_count.':AT'.($row_count + 1))->getFont()->setBold(true);
                $row_count += 1;
                
                $cur_worksheet->setCellValue('D'.$row_count, "건수")->setCellValue('E'.$row_count, "마진")->setCellValue('F'.$row_count, "금액");
                $cur_worksheet->setCellValue('G'.$row_count, "건수")->setCellValue('H'.$row_count, "마진")->setCellValue('I'.$row_count, "금액");
                $cur_worksheet->setCellValue('J'.$row_count, "건수")->setCellValue('K'.$row_count, "마진")->setCellValue('L'.$row_count, "금액");
                $cur_worksheet->setCellValue('M'.$row_count, "건수")->setCellValue('N'.$row_count, "마진")->setCellValue('O'.$row_count, "금액");
                $cur_worksheet->setCellValue('P'.$row_count, "건수")->setCellValue('Q'.$row_count, "마진")->setCellValue('R'.$row_count, "금액");
                $cur_worksheet->setCellValue('S'.$row_count, "건수")->setCellValue('T'.$row_count, "마진")->setCellValue('U'.$row_count, "금액");
                $cur_worksheet->setCellValue('V'.$row_count, "건수")->setCellValue('W'.$row_count, "마진")->setCellValue('X'.$row_count, "금액");
                $cur_worksheet->setCellValue('Y'.$row_count, "건수")->setCellValue('Z'.$row_count, "마진")->setCellValue('AA'.$row_count, "금액");
                $cur_worksheet->setCellValue('AB'.$row_count, "건수")->setCellValue('AC'.$row_count, "마진")->setCellValue('AD'.$row_count, "금액");
                $cur_worksheet->setCellValue('AE'.$row_count, "건수")->setCellValue('AF'.$row_count, "마진")->setCellValue('AG'.$row_count, "금액");
                $cur_worksheet->setCellValue('AH'.$row_count, "건수")->setCellValue('AI'.$row_count, "마진")->setCellValue('AJ'.$row_count, "금액");
                $cur_worksheet->setCellValue('AK'.$row_count, "건수")->setCellValue('AL'.$row_count, "마진")->setCellValue('AM'.$row_count, "금액");
                $cur_worksheet->setCellValue('AN'.$row_count, "건수")->setCellValue('AO'.$row_count, "마진")->setCellValue('AP'.$row_count, "금액");
                $cur_worksheet->setCellValue('AQ'.$row_count, "건수")->setCellValue('AR'.$row_count, "마진")->setCellValue('AS'.$row_count, "금액");
                $cur_worksheet->getStyle("B".$start_table_row.":AT".$row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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
                $sum_tot_amt = 0;
                $sum_smt = 0;
                $sum_smt_amt = 0;
                $sum_smt_sms = 0;
                $sum_smt_sms_amt = 0;
                $sum_smt_mms = 0;
                $sum_smt_mms_amt = 0;
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
                    $sum_tot_amt += $r->tot_amt;
                    
                    $cur_worksheet->mergeCells('B'.$row_count.':C'.$row_count)->mergeCells('B'.($row_count + 1).':C'.($row_count + 1));
                    $cur_worksheet->mergeCells('D'.$row_count.':D'.($row_count + 1))->mergeCells('E'.$row_count.':E'.($row_count + 1))->mergeCells('F'.$row_count.':F'.($row_count + 1));
                    $cur_worksheet->mergeCells('G'.$row_count.':G'.($row_count + 1))->mergeCells('H'.$row_count.':H'.($row_count + 1))->mergeCells('I'.$row_count.':I'.($row_count + 1));
                    $cur_worksheet->mergeCells('J'.$row_count.':J'.($row_count + 1))->mergeCells('K'.$row_count.':K'.($row_count + 1))->mergeCells('L'.$row_count.':L'.($row_count + 1));
                    $cur_worksheet->mergeCells('M'.$row_count.':M'.($row_count + 1))->mergeCells('N'.$row_count.':N'.($row_count + 1))->mergeCells('O'.$row_count.':O'.($row_count + 1));
                    $cur_worksheet->mergeCells('P'.$row_count.':P'.($row_count + 1))->mergeCells('Q'.$row_count.':Q'.($row_count + 1))->mergeCells('R'.$row_count.':R'.($row_count + 1));
                    $cur_worksheet->mergeCells('S'.$row_count.':S'.($row_count + 1))->mergeCells('T'.$row_count.':T'.($row_count + 1))->mergeCells('U'.$row_count.':U'.($row_count + 1));
                    $cur_worksheet->mergeCells('V'.$row_count.':V'.($row_count + 1))->mergeCells('W'.$row_count.':W'.($row_count + 1))->mergeCells('X'.$row_count.':X'.($row_count + 1));
                    $cur_worksheet->mergeCells('Y'.$row_count.':Y'.($row_count + 1))->mergeCells('Z'.$row_count.':Z'.($row_count + 1))->mergeCells('AA'.$row_count.':AA'.($row_count + 1));
                    $cur_worksheet->mergeCells('AB'.$row_count.':AB'.($row_count + 1))->mergeCells('AC'.$row_count.':AC'.($row_count + 1))->mergeCells('AD'.$row_count.':AD'.($row_count + 1));
                    $cur_worksheet->mergeCells('AE'.$row_count.':AE'.($row_count + 1))->mergeCells('AF'.$row_count.':AF'.($row_count + 1))->mergeCells('AG'.$row_count.':AG'.($row_count + 1));
                    $cur_worksheet->mergeCells('AH'.$row_count.':AH'.($row_count + 1))->mergeCells('AI'.$row_count.':AI'.($row_count + 1))->mergeCells('AJ'.$row_count.':AJ'.($row_count + 1));
                    $cur_worksheet->mergeCells('AK'.$row_count.':AK'.($row_count + 1))->mergeCells('AL'.$row_count.':AL'.($row_count + 1))->mergeCells('AM'.$row_count.':AM'.($row_count + 1));
                    $cur_worksheet->mergeCells('AN'.$row_count.':AN'.($row_count + 1))->mergeCells('AO'.$row_count.':AO'.($row_count + 1))->mergeCells('AP'.$row_count.':AP'.($row_count + 1));
                    $cur_worksheet->mergeCells('AQ'.$row_count.':AQ'.($row_count + 1))->mergeCells('AR'.$row_count.':AR'.($row_count + 1))->mergeCells('AS'.$row_count.':AS'.($row_count + 1));
                    $cur_worksheet->mergeCells('AT'.$row_count.':AT'.($row_count + 1));
                    $cur_worksheet->setCellValue('B'.$row_count, $r->vendor)->setCellValue('B'.($row_count + 1), $r->pvendor);
                    $cur_worksheet->setCellValue('D'.$row_count, $r->at)->setCellValue('E'.$row_count, $r->at_margin)->setCellValue('F'.$row_count, $r->at_amt);
                    $cur_worksheet->setCellValue('G'.$row_count, $r->ft)->setCellValue('H'.$row_count, $r->ft_margin)->setCellValue('I'.$row_count, $r->ft_amt);
                    $cur_worksheet->setCellValue('J'.$row_count, $r->ft_img)->setCellValue('K'.$row_count, $r->ft_img_margin)->setCellValue('L'.$row_count, $r->ft_img_amt);
                    $cur_worksheet->setCellValue('M'.$row_count, $r->ft_w_img)->setCellValue('N'.$row_count, $r->ft_w_img_margin)->setCellValue('O'.$row_count, $r->ft_w_img_amt);
                    $cur_worksheet->setCellValue('P'.$row_count, $r->grs)->setCellValue('Q'.$row_count, $r->grs_margin)->setCellValue('R'.$row_count, $r->grs_amt);
                    $cur_worksheet->setCellValue('S'.$row_count, $r->grs_biz)->setCellValue('T'.$row_count, $r->grs_biz_margin)->setCellValue('U'.$row_count, $r->grs_biz_amt);
                    $cur_worksheet->setCellValue('V'.$row_count, $r->grs_sms)->setCellValue('W'.$row_count, $r->grs_sms_margin)->setCellValue('X'.$row_count, $r->grs_sms_amt);
                    $cur_worksheet->setCellValue('Y'.$row_count, $r->grs_mms)->setCellValue('Z'.$row_count, $r->grs_mms_margin)->setCellValue('AA'.$row_count, $r->grs_mms_amt);
                    $cur_worksheet->setCellValue('AB'.$row_count, $r->nas)->setCellValue('AC'.$row_count, $r->nas_margin)->setCellValue('AD'.$row_count, $r->nas_amt);
                    $cur_worksheet->setCellValue('AE'.$row_count, $r->nas_sms)->setCellValue('AF'.$row_count, $r->nas_sms_margin)->setCellValue('AG'.$row_count, $r->nas_sms_amt);
                    $cur_worksheet->setCellValue('AH'.$row_count, $r->nas_mms)->setCellValue('AI'.$row_count, $r->nas_mms_margin)->setCellValue('AJ'.$row_count, $r->nas_mms_amt);
                    $cur_worksheet->setCellValue('AK'.$row_count, $r->smt)->setCellValue('AL'.$row_count, $r->smt_margin)->setCellValue('AM'.$row_count, $r->smt_amt);
                    $cur_worksheet->setCellValue('AN'.$row_count, $r->smt_sms)->setCellValue('AO'.$row_count, $r->smt_sms_margin)->setCellValue('AP'.$row_count, $r->smt_sms_amt);
                    $cur_worksheet->setCellValue('AQ'.$row_count, $r->smt_mms)->setCellValue('AR'.$row_count, $r->smt_mms_margin)->setCellValue('AS'.$row_count, $r->smt_mms_amt);
                    $cur_worksheet->setCellValue('AT'.$row_count, $r->tot_amt);
                    
                    $cur_worksheet->getStyle("B".$row_count.":C".$row_count)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("B".$row_count.":C".$row_count)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("B".$row_count.":C".$row_count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("B".($row_count + 1).":C".($row_count + 1))->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("B".($row_count + 1).":C".($row_count + 1))->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("B".($row_count + 1).":C".($row_count + 1))->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $cur_worksheet->getStyle("D".$row_count.":AT".($row_count + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    $cur_worksheet->getStyle('B'.$row_count.":B".($row_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $cur_worksheet->getStyle('B'.$row_count.":B".($row_count + 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('D'.$row_count.':AT'.($row_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $cur_worksheet->getStyle('D'.$row_count.':AT'.($row_count + 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $cur_worksheet->getStyle('B'.$row_count.':AT'.($row_count + 1))->getFont()->setSize(10);
                   
                    $row_count += 2;
                }
                
                $cur_worksheet->mergeCells('B'.$row_count.':C'.$row_count)->setCellValue('B'.$row_count, "합 계");
                $cur_worksheet->setCellValue('D'.$row_count, $sum_at);
                $cur_worksheet->mergeCells('E'.$row_count.':F'.$row_count)->setCellValue('E'.$row_count, $sum_at_amt);
                $cur_worksheet->setCellValue('G'.$row_count, $sum_ft);
                $cur_worksheet->mergeCells('H'.$row_count.':I'.$row_count)->setCellValue('H'.$row_count, $sum_ft_amt);
                $cur_worksheet->setCellValue('J'.$row_count, $sum_ft_img);
                $cur_worksheet->mergeCells('K'.$row_count.':L'.$row_count)->setCellValue('K'.$row_count, $sum_ft_img_amt);
                $cur_worksheet->setCellValue('M'.$row_count, $sum_ft_w_img);
                $cur_worksheet->mergeCells('N'.$row_count.':O'.$row_count)->setCellValue('N'.$row_count, $sum_ft_w_img_amt);
                $cur_worksheet->setCellValue('P'.$row_count, $sum_grs);
                $cur_worksheet->mergeCells('Q'.$row_count.':R'.$row_count)->setCellValue('Q'.$row_count, $sum_grs_amt);
                $cur_worksheet->setCellValue('S'.$row_count, $sum_grs_biz);
                $cur_worksheet->mergeCells('T'.$row_count.':U'.$row_count)->setCellValue('T'.$row_count, $sum_grs_biz_amt);
                $cur_worksheet->setCellValue('V'.$row_count, $sum_grs_sms);
                $cur_worksheet->mergeCells('W'.$row_count.':X'.$row_count)->setCellValue('W'.$row_count, $sum_grs_sms_amt);
                $cur_worksheet->setCellValue('Y'.$row_count, $sum_grs_mms);
                $cur_worksheet->mergeCells('Z'.$row_count.':AA'.$row_count)->setCellValue('Z'.$row_count, $sum_grs_mms_amt);
                $cur_worksheet->setCellValue('AB'.$row_count, $sum_nas);
                $cur_worksheet->mergeCells('AC'.$row_count.':AD'.$row_count)->setCellValue('AC'.$row_count, $sum_nas_amt);
                $cur_worksheet->setCellValue('AE'.$row_count, $sum_nas_sms);
                $cur_worksheet->mergeCells('AF'.$row_count.':AG'.$row_count)->setCellValue('AF'.$row_count, $sum_nas_sms_amt);
                $cur_worksheet->setCellValue('AH'.$row_count, $sum_nas_mms);
                $cur_worksheet->mergeCells('AI'.$row_count.':AJ'.$row_count)->setCellValue('AI'.$row_count, $sum_nas_mms_amt);
                $cur_worksheet->setCellValue('AK'.$row_count, $sum_smt);
                $cur_worksheet->mergeCells('AL'.$row_count.':AM'.$row_count)->setCellValue('AL'.$row_count, $sum_smt_amt);
                $cur_worksheet->setCellValue('AN'.$row_count, $sum_smt_sms);
                $cur_worksheet->mergeCells('AO'.$row_count.':AP'.$row_count)->setCellValue('AO'.$row_count, $sum_smt_sms_amt);
                $cur_worksheet->setCellValue('AQ'.$row_count, $sum_smt_mms);
                $cur_worksheet->mergeCells('AR'.$row_count.':AS'.$row_count)->setCellValue('AR'.$row_count, $sum_smt_mms_amt);
                $cur_worksheet->mergeCells('AT'.$row_count.':AT'.$row_count)->setCellValue('AT'.$row_count, $sum_tot_amt);
                
                $cur_worksheet->getStyle('B'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('D'.$row_count.':AT'.$row_count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $cur_worksheet->getStyle('D'.$row_count.':AT'.$row_count)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $cur_worksheet->getStyle('B'.$row_count.':AT'.$row_count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $cur_worksheet->getStyle('B'.$row_count.':AT'.$row_count)->getFill()->getStartColor()->setARGB('00AEAAAA');
                $cur_worksheet->getStyle('B'.$row_count.':AT'.$row_count)->getFont()->setSize(10);
                $cur_worksheet->getStyle("B".$row_count.":AT".$row_count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                
                $cur_worksheet->getStyle('D'.($start_table_row + 2).':AT'.$row_count)->getNumberFormat()->setFormatCode("#,##0_-");
                $cur_worksheet->getStyle('E'.($start_table_row + 2).':E'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('H'.($start_table_row + 2).':H'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('K'.($start_table_row + 2).':K'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('N'.($start_table_row + 2).':N'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('Q'.($start_table_row + 2).':Q'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('T'.($start_table_row + 2).':T'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('W'.($start_table_row + 2).':W'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('Z'.($start_table_row + 2).':Z'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('AC'.($start_table_row + 2).':AC'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('AF'.($start_table_row + 2).':AF'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('AI'.($start_table_row + 2).':AI'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('AL'.($start_table_row + 2).':AL'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('AO'.($start_table_row + 2).':AO'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
                $cur_worksheet->getStyle('AR'.($start_table_row + 2).':AR'.($row_count - 1))->getNumberFormat()->setFormatCode("#,##0.00_-");
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
            ),	'A1:T1');
            
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A3:T3');
            
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
            
            $this->excel->getActiveSheet()->mergeCells('A1:S1');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "발 송 내 역  정 산");
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '업체명');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '월사용료');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '충전');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '알림톡');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '친구톡(텍스트)');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, '친구톡(이미지)');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 3, '친구톡(와이드)');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 3, 'WEB(A) KT,LG');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, 'WEB(A) SKT');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 3, 'WEB(A) SMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, 3, 'WEB(A) MMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 3, 'WEB(B)');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, 3, 'WEB(B) SMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, 3, 'WEB(B) MMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 3, 'WEB(C)');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, 3, 'WEB(C) SMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, 3, 'WEB(C) MMS');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, 3, '발송합계');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, 3, '전송매출');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, 3, '수익금액');
            
            $row = 4;
            foreach($value as $val) {
                // 데이터를 읽어서 순차로 기록한다.
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->vendor);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->monthly_fee);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val->deposit);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->at);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val->ft);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val->ft_img);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val->ft_w_img);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val->grs );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val->grs_biz );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $val->grs_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $val->grs_mms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->nas);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->nas_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->nas_mms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $val->smt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $val->smt_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $val->smt_mms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $val->row_sum );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $val->row_amount );
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $val->row_margin );
                $row++;
            }

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, "합 계");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $total[0]->monthly_fee);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $total[0]->deposit);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $total[0]->at);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $total[0]->ft);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $total[0]->ft_img);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $total[0]->ft_w_img);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $total[0]->grs );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $total[0]->grs_biz );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $total[0]->grs_sms);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $total[0]->grs_mms );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $total[0]->nas);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $total[0]->nas_sms );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $total[0]->nas_mms );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $total[0]->smt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $total[0]->smt_sms );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $total[0]->smt_mms );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $total[0]->row_sum );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $total[0]->row_amount );
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $total[0]->row_margin );

            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A'.$row.':T'.$row);
            
            $row++;
            
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A4:A'.$row);
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'B4:T'.$row);


        } else 
        {
            // 필드명을 기록한다.
            // 글꼴 및 정렬
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A1:AS1');
            
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A3:AS4');
            
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AE')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AF')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('AG')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AH')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AI')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AK')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('AM')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AN')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AO')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('AP')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AQ')->setWidth(6);
            $this->excel->getActiveSheet()->getColumnDimension('AR')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('AS')->setWidth(15);
            
            $this->excel->getActiveSheet()->mergeCells('A1:AP1');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "발 송 내 역  정 산");
            
            $this->excel->getActiveSheet()->mergeCells('A3:A4');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '업체명');
            
            $this->excel->getActiveSheet()->mergeCells('B3:B4');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Fullcare');
            
            $this->excel->getActiveSheet()->mergeCells('C3:E3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '알림톡');
            
            $this->excel->getActiveSheet()->mergeCells('F3:H3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 3, '친구톡(텍스트)');
            
            $this->excel->getActiveSheet()->mergeCells('I3:K3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 3, '친구톡(이미지)');
            $this->excel->getActiveSheet()->mergeCells('L3:N3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, 3, '친구톡(와이드)');
            $this->excel->getActiveSheet()->mergeCells('O3:Q3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, 3, 'WEB(A) KT,LG');
            $this->excel->getActiveSheet()->mergeCells('R3:T3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, 3, 'WEB(A) SKT');
            $this->excel->getActiveSheet()->mergeCells('U3:W3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, 3, 'WEB(A) SMS');
            $this->excel->getActiveSheet()->mergeCells('X3:Z3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, 3, 'WEB(A) MMS');
            $this->excel->getActiveSheet()->mergeCells('AA3:AC3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, 3, 'WEB(B)');
            $this->excel->getActiveSheet()->mergeCells('AD3:AF3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(29, 3, 'WEB(B) SMS');
            $this->excel->getActiveSheet()->mergeCells('AG3:AI3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(32, 3, 'WEB(B) MMS');
            $this->excel->getActiveSheet()->mergeCells('AJ3:AL3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(35, 3, 'WEB(C)');
            $this->excel->getActiveSheet()->mergeCells('AM3:AO3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(38, 3, 'WEB(C) SMS');
            $this->excel->getActiveSheet()->mergeCells('AP3:AR3');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(41, 3, 'WEB(C) MMS');
            $this->excel->getActiveSheet()->mergeCells('AS3:AS4');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(44, 3, '총수익');

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
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(24, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(25, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(28, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(29, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(30, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(31, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(32, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(33, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(34, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(35, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(36, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(37, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(38, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(39, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(40, 4, '금액');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(41, 4, '건수');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(42, 4, '마진');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(43, 4, '금액');
            
            
            $row = 5;
            foreach($value as $val) {
                // 데이터를 읽어서 순차로 기록한다.
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
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $val->ft_w_img);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $val->ft_w_img_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val->ft_w_img_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $val->grs);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $val->grs_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $val->grs_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $val->grs_biz);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $val->grs_biz_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $val->grs_biz_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $val->grs_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $val->grs_sms_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $val->grs_sms_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, $val->grs_mms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(24, $row, $val->grs_mms_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(25, $row, $val->grs_mms_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $val->nas);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $val->nas_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(28, $row, $val->nas_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(29, $row, $val->nas_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(30, $row, $val->nas_sms_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(31, $row, $val->nas_sms_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(32, $row, $val->nas_mms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(33, $row, $val->nas_mms_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(34, $row, $val->nas_mms_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(35, $row, $val->smt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(36, $row, $val->smt_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(37, $row, $val->smt_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(38, $row, $val->smt_sms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(39, $row, $val->smt_sms_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(40, $row, $val->smt_sms_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(41, $row, $val->smt_mms);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(42, $row, $val->smt_mms_margin);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(43, $row, $val->smt_mms_amt);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(44, $row, $val->row_margin);
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
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $total[0]->ft_w_img);
            $this->excel->getActiveSheet()->mergeCells('M'.$row.':N'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $total[0]->ft_w_img_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $total[0]->grs);
            $this->excel->getActiveSheet()->mergeCells('P'.$row.':Q'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $total[0]->grs_amt);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, $total[0]->grs_biz);
            $this->excel->getActiveSheet()->mergeCells('S'.$row.':T'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, $total[0]->grs_biz_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $total[0]->grs_sms);
            $this->excel->getActiveSheet()->mergeCells('V'.$row.':W'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $total[0]->grs_sms_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, $total[0]->grs_mms);
            $this->excel->getActiveSheet()->mergeCells('Y'.$row.':Z'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(24, $row, $total[0]->grs_mms_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $total[0]->nas);
            $this->excel->getActiveSheet()->mergeCells('AB'.$row.':AC'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $total[0]->nas_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(29, $row, $total[0]->nas_sms);
            $this->excel->getActiveSheet()->mergeCells('AE'.$row.':AF'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(30, $row, $total[0]->nas_sms_amt);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(32, $row, $total[0]->nas_mms);
            $this->excel->getActiveSheet()->mergeCells('AH'.$row.':AI'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(33, $row, $total[0]->nas_mms_amt);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(35, $row, $total[0]->smt);
            $this->excel->getActiveSheet()->mergeCells('AK'.$row.':AL'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(36, $row, $total[0]->smt_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(38, $row, $total[0]->smt_sms);
            $this->excel->getActiveSheet()->mergeCells('AN'.$row.':AO'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(39, $row, $total[0]->smt_sms_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(41, $row, $total[0]->smt_mms);
            $this->excel->getActiveSheet()->mergeCells('AQ'.$row.':AR'.$row);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(42, $row, $total[0]->smt_mms_amt);
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(44, $row, $total[0]->row_sum_margin );

            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A'.$row.':AS'.$row);
            
            $row++;
            
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'A5:A'.$row);
            $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
                'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
            ),	'B5:AS'.$row);
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
    
}
?>