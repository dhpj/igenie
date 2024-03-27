<?php
class Biz_model extends CB_Model {
    public $userid = "";
    public $mem_id = 0;
    public $mem_lv = 0;
    public $manager_lv = 10;	//- 중간관리자 등급 ~부터

    public $parent_id = 0;
    public $price_ft = 16.5;
    public $price_ft_img = 22.0;
    public $price_ft_w_img = 22.0;
    public $price_at = 9.9;
    public $price_sms = 16.5;
    public $price_lms = 16.5;
    public $price_mms = 300.0;
    public $price_phn = 16.5;
    public $price_grs = 30.0;
    public $price_nas = 30.0;

    public $price_grs_sms = 0;
    public $price_nas_sms = 0;
    public $price_grs_biz = 0;
    public $price_grs_mms = 60.5;
    public $price_nas_mms = 60.5;

    public $price_smt = 0;
    public $price_smt_sms = 0;
    public $price_smt_mms = 0;

    public $price_rcs = 0;
    public $price_rcs_sms = 0;
    public $price_rcs_mms = 0;
    public $price_rcs_tem = 0;

    public $base_price_ft = 16.5;
    public $base_price_ft_img = 22.0;
    public $base_price_ft_w_img = 27.5;
    public $base_price_at = 9.9;
    public $base_price_sms = 16.5;
    public $base_price_lms = 16.5;
    public $base_price_mms = 300.0;
    public $base_price_phn = 16.5;

    public $base_price_smt = 0;
    public $base_price_smt_sms = 0;
    public $base_price_smt_mms = 0;

    public $base_price_rcs = 0;
    public $base_price_rcs_sms = 0;
    public $base_price_rcs_mms = 0;
    public $base_price_rcs_tem = 0;

    public $up_price_ft = 16.5;
    public $up_price_ft_img = 22.0;
    public $up_price_at = 9.9;
    public $up_price_sms = 16.5;
    public $up_price_lms = 16.5;
    public $up_price_mms = 300.0;
    public $up_price_phn = 16.5;

    public $up_price_rcs_tem = 9.9;
    public $up_price_rcs_sms = 16.5;
    public $up_price_rcs = 16.5;
    public $up_price_rcs_mms = 300.0;

    public $charge = array(30000, 50000, 100000, 300000, 500000, 1000000, 1500000, 2000000, 2500000, 3000000);
    public $deposit = array("30000"=>0,"50000"=>1,"100000"=>2,"300000"=>3,"500000"=>4,"1000000"=>5,"1500000"=>6,"2000000"=>7,"2500000"=>8,"3000000"=>9);
    public $weight = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    public $reject_phn = "080-870-6789";

    public $buttonName = array("DS"=>"배송조회","WL"=>"웹링크","AL"=>"앱링크","BK"=>"봇키워드","MD"=>"메시지전달");
    public $buttonType = array("배송조회"=>"DS","웹링크"=>"WL","앱링크"=>"AL","봇키워드"=>"BK","메시지전달"=>"MD");

    function __construct()
    {
        // 모델 생성자 호출
        parent::__construct();

        if(!$this->member->is_member()) {
            header('Location: /');
            exit;
        }

        $CI = & get_instance();

        // 기본 단가 수집
        $price = $this->db->query("select * from cb_wt_setting limit 1")->row();
        $this->base_price_ft = $price->wst_price_ft;
        $this->base_price_ft_img = $price->wst_price_ft_img;
        $this->base_price_at = $price->wst_price_at;
        $this->base_price_sms = $price->wst_price_sms;
        $this->base_price_lms = $price->wst_price_lms;
        $this->base_price_mms = $price->wst_price_mms;
        $this->base_price_grs = $price->wst_price_grs;
        $this->base_price_nas = $price->wst_price_nas;
        $this->base_price_015 = $price->wst_price_015;
        $this->base_price_phn = $price->wst_price_phn;
        $this->base_price_grs_mms = $price->wst_price_grs_mms;
        $this->base_price_nas_mms = $price->wst_price_nas_mms;

        $this->base_price_smt = $price->wst_price_smt;
        $this->base_price_smt_sms = $price->wst_price_smt_sms;
        $this->base_price_smt_mms = $price->wst_price_smt_mms;

        $this->base_price_rcs = $price->wst_price_rcs;
        $this->base_price_rcs_sms = $price->wst_price_rcs_sms;
        $this->base_price_rcs_mms = $price->wst_price_rcs_mms;
        $this->base_price_rcs_tem = $price->wst_price_rcs_tem;

        $this->base_price_dooit = $price->wst_price_dooit;

        $this->weight[0] = $price->wst_weight1;
        $this->weight[1] = $price->wst_weight2;
        $this->weight[2] = $price->wst_weight3;
        $this->weight[3] = $price->wst_weight4;
        $this->weight[4] = $price->wst_weight5;
        $this->weight[5] = $price->wst_weight6;
        $this->weight[6] = $price->wst_weight7;
        $this->weight[7] = $price->wst_weight8;
        $this->weight[8] = $price->wst_weight9;
        $this->weight[9] = $price->wst_weight10;

        // 자신의 단가 수집
        $query = $this->db->query("select a.mem_id, a.mem_userid, a.mem_level, b.* from cb_member a left join cb_wt_member_addon b on a.mem_id=b.mad_mem_id where a.mem_id=? limit 1", array($CI->session->mem_id));
        if($query) {
            $my_price = $query->row();
            if($my_price->mad_mem_id == $CI->session->mem_id) {
                $this->price_ft = $my_price->mad_price_ft;
                $this->price_ft_img = $my_price->mad_price_ft_img;
                $this->price_ft_w_img = $my_price->mad_price_ft_w_img;
                $this->price_at = $my_price->mad_price_at;
                $this->price_sms = $my_price->mad_price_sms;
                $this->price_lms = $my_price->mad_price_lms;
                $this->price_mms = $my_price->mad_price_mms;
                $this->price_grs = $my_price->mad_price_grs;
                $this->price_nas = $my_price->mad_price_nas;
                $this->price_015 = $my_price->mad_price_015;
                $this->price_phn = $my_price->mad_price_phn;
                $this->price_dooit = $my_price->mad_price_dooit;
                $this->price_grs_sms = $my_price->mad_price_grs_sms;
                $this->price_nas_sms = $my_price->mad_price_nas_sms;
                $this->price_grs_mms = $my_price->mad_price_grs_mms;
                $this->price_nas_mms = $my_price->mad_price_nas_mms;
                $this->price_grs_biz = $my_price->mad_price_grs_biz;
                $this->price_smt = $my_price->mad_price_smt;
                $this->price_smt_sms = $my_price->mad_price_smt_sms;
                $this->price_smt_mms = $my_price->mad_price_smt_mms;

                $this->price_rcs = $my_price->mad_price_rcs;
                $this->price_rcs_sms = $my_price->mad_price_rcs_sms;
                $this->price_rcs_mms = $my_price->mad_price_rcs_mms;
                $this->price_rcs_tem = $my_price->mad_price_rcs_tem;

                $this->weight[0] = $my_price->mad_weight1;
                $this->weight[1] = $my_price->mad_weight2;
                $this->weight[2] = $my_price->mad_weight3;
                $this->weight[3] = $my_price->mad_weight4;
                $this->weight[4] = $my_price->mad_weight5;
                $this->weight[5] = $my_price->mad_weight6;
                $this->weight[6] = $my_price->mad_weight7;
                $this->weight[7] = $my_price->mad_weight8;
                $this->weight[8] = $my_price->mad_weight9;
                $this->weight[9] = $my_price->mad_weight10;
            }
            if($my_price->mem_id != $CI->session->mem_id) { header('Location: /'); exit; }
            $this->mem_id = $CI->session->mem_id;
            $this->userid = $my_price->mem_userid;
            $this->mem_lv = $my_price->mem_level;

            /* 페이지 권한검사 */
            $uri = (strpos($_SERVER['REQUEST_URI'], '?')===false) ? $_SERVER['REQUEST_URI'] : substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
            if($uri!="/") {
                $mnu = $this->db->query("select men_allow_lv, men_max_lv from cb_menu where men_link=? limit 1", array($uri))->row();
                if(!$mnu) {
                    $uri = substr($uri, 0, strrpos($uri, "/"));
                    if($uri!="/") {
                        $mnu = $this->db->query("select men_allow_lv, men_max_lv from cb_menu where men_link=? limit 1", array($uri))->row();
                        if(!$mnu) {
                            $uri = substr($uri, 0, strrpos($uri, "/"));
                            if($uri!="/") {
                                $mnu = $this->db->query("select men_allow_lv, men_max_lv from cb_menu where men_link=? limit 1", array($uri))->row();
                            }
                        }
                    }
                }
            }

            //		if($_SERVER["REMOTE_ADDR"]=="112.163.89.66") {
            //			echo '<pre> :: ';print_r($uri);
            //			echo '<pre> :: ';print_r($mnu);
            //			echo '<pre> :: ';print_r($this->mem_lv);
            //		}


            if($uri && $uri!="/" && $mnu && $this->mem_lv < 100) {
                if($this->mem_lv < $mnu->men_allow_lv || $this->mem_lv > $mnu->men_max_lv) { header('Location: /'); exit; }
            }
        }
        // 상위 딜러의 단가 수집
        if($this->mem_lv >= $this->manager_lv) // 중간관리자 이상이면 상위단가를 자신의 단가로 지정
        {
            $this->up_price_ft = $this->price_ft;
            $this->up_price_ft_img = $this->price_ft_img;
            $this->up_price_at = $this->price_at;
            $this->up_price_sms = $this->price_sms;
            $this->up_price_lms = $this->price_lms;
            $this->up_price_mms = $this->price_mms;

            $this->up_price_rcs_sms = $this->price_rcs_sms;
            $this->up_price_rcs = $this->price_rcs;
            $this->up_price_rcs_mms = $this->price_rcs_mms;
            $this->up_price_rcs_tem = $this->price_rcs_tem;

            $this->up_price_grs = $this->price_grs;
            $this->up_price_nas = $this->price_nas;
            $this->up_price_015 = $this->price_015;
            $this->up_price_phn = $this->price_phn;
            $this->up_price_dooit = $this->price_dooit;
            $this->up_price_grs_mms = $this->price_grs_mms;
            $this->up_price_nas_mms = $this->price_nas_mms;
        } else {
            $query = $this->db->query("select * from cb_wt_member_addon where mad_mem_id=(select mrg_recommend_mem_id from cb_member_register where mem_id=? limit 1) limit 1", array($CI->session->mem_id));
            $this->parant_id = 0;
            if($query) {
                $up_price = $query->row();
                if($up_price->mad_mem_id > 0) {
                    $this->parant_id = $up_price->mad_mem_id;
                    $this->up_price_ft = $up_price->mad_price_ft;
                    $this->up_price_ft_img = $up_price->mad_price_ft_img;
                    $this->up_price_at = $up_price->mad_price_at;
                    $this->up_price_sms = $up_price->mad_price_sms;
                    $this->up_price_lms = $up_price->mad_price_lms;
                    $this->up_price_mms = $up_price->mad_price_mms;

                    $this->up_price_rcs_sms = $up_price->mad_price_rcs_sms;
                    $this->up_price_rcs = $up_price->mad_price_rcs;
                    $this->up_price_rcs_mms = $up_price->mad_price_rcs_mms;
                    $this->up_price_rcs_tem = $up_price->mad_price_rcs_tem;

                    $this->up_price_grs = $up_price->mad_price_grs;
                    $this->up_price_nas = $up_price->mad_price_nas;
                    $this->up_price_015 = $up_price->mad_price_015;
                    $this->up_price_phn = $up_price->mad_price_phn;
                    $this->up_price_dooit = $up_price->mad_price_dooit;
                    $this->up_price_grs_mms = $up_price->mad_price_grs_mms;
                    $this->up_price_nas_mms = $up_price->mad_price_nas_mms;
                } else {
                    $this->up_price_ft = $this->price_ft;
                    $this->up_price_ft_img = $this->price_ft_img;
                    $this->up_price_at = $this->price_at;
                    $this->up_price_sms = $this->price_sms;
                    $this->up_price_lms = $this->price_lms;
                    $this->up_price_mms = $this->price_mms;

                    $this->up_price_rcs_sms = $this->price_rcs_sms;
                    $this->up_price_rcs = $this->price_rcs;
                    $this->up_price_rcs_mms = $this->price_rcs_mms;
                    $this->up_price_rcs_tem = $this->price_rcs_tem;

                    $this->up_price_grs = $this->price_grs;
                    $this->up_price_nas = $this->price_nas;
                    $this->up_price_015 = $this->price_015;
                    $this->up_price_phn = $this->price_phn;
                    $this->up_price_dooit = $this->price_dooit;
                    $this->up_price_grs_mms = $this->price_grs_mms;
                    $this->up_price_nas_mms = $this->price_nas_mms;
                }
            } else {
                $this->up_price_ft = $this->price_ft;
                $this->up_price_ft_img = $this->price_ft_img;
                $this->up_price_at = $this->price_at;
                $this->up_price_sms = $this->price_sms;
                $this->up_price_lms = $this->price_lms;
                $this->up_price_mms = $this->price_mms;

                $this->up_price_rcs_sms = $this->price_rcs_sms;
                $this->up_price_rcs = $this->price_rcs;
                $this->up_price_rcs_mms = $this->price_rcs_mms;
                $this->up_price_rcs_tem = $this->price_rcs_tem;

                $this->up_price_grs = $this->price_grs;
                $this->up_price_nas = $this->price_nas;
                $this->up_price_015 = $this->price_015;
                $this->up_price_phn = $this->price_phn;
                $this->up_price_dooit = $this->price_dooit;
                $this->up_price_grs_mms = $this->price_grs_mms;
                $this->up_price_nas_mms = $this->price_nas_mms;
            }
        }
    }

    function set_Term($set_date)
    {
        $this->endDate = date('Y-m-d');
        if($set_date=='today') {
            $this->startDate = date('Y-m-d');
        } else if($set_date=='week') {
            $this->startDate = date('Y-m-d', strtotime(date('Y-m-d')."-7day"));
        } else if($set_date=='1month') {
            $this->startDate = date('Y-m-d', strtotime(date('Y-m-d')."-1month"));
        } else if($set_date=='3month') {
            $this->startDate = date('Y-m-d', strtotime(date('Y-m-d')."-3month"));
        } else if($set_date=='6month') {
            $this->startDate = date('Y-m-d', strtotime(date('Y-m-d')."-6month"));
        } else {
            $this->startDate = ($this->input->post('startDate')) ? $this->input->post('startDate') : date('Y-m-d');
            $this->endDate = ($this->input->post('endDate')) ? $this->input->post('endDate') : date('Y-m-d');
        }
        return $this;
    }

    function get_table_count($tbl, $where, $param) {
        if(!$where) $where = " 1=1 ";
        $sql = "
			select count(*) as cnt
			from ".$tbl."
			where ".$where;
        //log_message("ERROR", "get_table_count() sql : ".$sql);
        $r = $this->db->query($sql, $param)->row();
        //log_message("ERROR", "LAST QUERY CNT : ".$this->db->last_query());
        return $r->cnt;
    }

    function get_table_next_id($tbl, $key_field, $where='', $param=array()) {
        $result = $this->db->query("select (ifnull(max(".$key_field."), 0) + 1) id from ".$tbl.(($where) ? " where ".$where : ""), $param)->row();
        return ($result) ? $result->id : 0;
    }

    function get_row($tbl, $where='', $param=array()) {
        return $this->db->query("select * from ".$tbl." ".(($where) ? "where ".$where : ""), $param)->row();
    }

    function getAbleCoin($mem_id, $mem_userid) {
        /* Agent Request 테이블에 저장된 발송 대기 메시지의 갯수를 구해서 현재 예치금에서 차감해 표시한다. */
        $sql = "
			select
				sum(case when MESSAGE_TYPE='ft' and ifnull(IMAGE_URL, '')='' and ifnull(MSG_SMS, '')='' then 1 else 0 end) cnt_ft,
				sum(case when MESSAGE_TYPE='ft' and ifnull(IMAGE_URL, '')='' and ifnull(MSG_SMS, '')<>'' then 1 else 0 end) cnt_ft_lms,
				sum(case when MESSAGE_TYPE='ft' and ifnull(IMAGE_URL, '')<>'' then 1 else 0 end) cnt_ft_img,
				sum(case when MESSAGE_TYPE='at' and ifnull(MSG_SMS, '')='' then 1 else 0 end) cnt_at,
				sum(case when MESSAGE_TYPE='at' and ifnull(MSG_SMS, '')<>'' then 1 else 0 end) cnt_at_lms,
				sum(case when MESSAGE_TYPE='pn' then 1 else 0 end) cnt_phn,
				sum(case when MESSAGE_TYPE='sm' then 1 else 0 end) cnt_sms,
				sum(case when MESSAGE_TYPE='lm' then 1 else 0 end) cnt_lms,
				sum(case when MESSAGE_TYPE='mm' then 1 else 0 end) cnt_mms
			from TBL_REQUEST
			where REMARK2 = ?;
		";
        $query = $this->db->query($sql, array($mem_id));
        $mem = $this->get_member($mem_userid);
        if($mem->mem_pay_type == 'A' || $mem->mem_pay_type == 'T') {
            $mCoin = 999999999;
        } else {
            $mCoin = /*$mem->mem_point +*/ $mem->total_deposit;			/* 포인트 + 예치금 */
        }

        if($query) {	/* 발송대기중인 메시지의 요금을 차감한다. : lms 기본 발송 -> phn */
            $wait = $query->row();
            if($wait->cnt_ft) { $mCoin -= ($wait->cnt_ft * $this->price_ft); }
            if($wait->cnt_ft_lms) { $mCoin -= ($wait->cnt_ft_lms * $this->price_phn); }
            if($wait->cnt_ft_img) { $mCoin -= ($wait->cnt_ft_img * $this->price_ft_img); }
            if($wait->cnt_at) { $mCoin -= ($wait->cnt_at * $this->price_at); }
            if($wait->cnt_at_lms) { $mCoin -= ($wait->cnt_at_lms * $this->price_phn); }
            if($wait->cnt_phn) { $mCoin -= ($wait->cnt_phn * $this->price_phn); }
            if($wait->cnt_sms) { $mCoin -= ($wait->cnt_sms * $this->price_sms); }
            if($wait->cnt_lms) { $mCoin -= ($wait->cnt_lms * $this->price_lms); }
            if($wait->cnt_mms) { $mCoin -= ($wait->cnt_mms * $this->price_mms); }
        }

        return $mCoin;
    }

    function getReservedCoin($mem_id) {
        $mCoin =0;
        /* Agent Request 테이블에 저장된 발송 대기 메시지의 갯수를 구해서 현재 예치금에서 차감해 표시한다. */
        $sql = "
			select
				sum(case when MESSAGE_TYPE='ft' and ifnull(IMAGE_URL, '')='' and ifnull(MSG_SMS, '')='' then 1 else 0 end) cnt_ft,
				sum(case when MESSAGE_TYPE='ft' and ifnull(IMAGE_URL, '')='' and ifnull(MSG_SMS, '')<>'' then 1 else 0 end) cnt_ft_lms,
				sum(case when MESSAGE_TYPE='ft' and ifnull(IMAGE_URL, '')<>'' then 1 else 0 end) cnt_ft_img,
				sum(case when MESSAGE_TYPE='at' and ifnull(MSG_SMS, '')='' then 1 else 0 end) cnt_at,
				sum(case when MESSAGE_TYPE='at' and ifnull(MSG_SMS, '')<>'' then 1 else 0 end) cnt_at_lms,
				sum(case when MESSAGE_TYPE='ai' and ifnull(MSG_SMS, '')='' then 1 else 0 end) cnt_ai,
				sum(case when MESSAGE_TYPE='ai' and ifnull(MSG_SMS, '')<>'' then 1 else 0 end) cnt_ai_lms,
				sum(case when MESSAGE_TYPE='pn' then 1 else 0 end) cnt_phn,
				sum(case when MESSAGE_TYPE='sm' then 1 else 0 end) cnt_sms,
				sum(case when MESSAGE_TYPE='lm' then 1 else 0 end) cnt_lms,
				sum(case when MESSAGE_TYPE='mm' then 1 else 0 end) cnt_mms
			from DHN_REQUEST
			where REMARK2 = ?;
		";
        $query = $this->db->query($sql, array($mem_id));
        //$mem = $this->get_member($mem_userid);
        //if($mem->mem_pay_type == 'A' || $mem->mem_pay_type == 'T') {
        //    $mCoin = 999999999;
        //} else {
        //$sql = "SELECT
        //		SUM(amt_amount * amt_deduct) amt
        //	FROM cb_amt_".$mem_userid;
        //$query = $this->db->query($sql);
        //$wait = $query->row();

        //$mCoin = $wait->amt;
        //    $mCoin = /*$mem->mem_point +*/ $mem->total_deposit;			/* 포인트 + 예치금 */
        //}

        if($query) {	/* 발송대기중인 메시지의 요금을 차감한다. : lms 기본 발송 -> phn */
            $wait = $query->row();
            if($wait->cnt_ft) { $mCoin += ($wait->cnt_ft * $this->price_ft); }
            if($wait->cnt_ft_lms) { $mCoin += ($wait->cnt_ft_lms * $this->price_phn); }
            if($wait->cnt_ft_img) { $mCoin += ($wait->cnt_ft_img * $this->price_ft_img); }
            if($wait->cnt_at) { $mCoin += ($wait->cnt_at * $this->price_at); }
            if($wait->cnt_at_lms) { $mCoin += ($wait->cnt_at_lms * $this->price_phn); }
            if($wait->cnt_ai) { $mCoin += ($wait->cnt_ai * $this->price_at); }
            if($wait->cnt_ai_lms) { $mCoin += ($wait->cnt_ai_lms * $this->price_phn); }
            if($wait->cnt_phn) { $mCoin += ($wait->cnt_phn * $this->price_phn); }
            if($wait->cnt_sms) { $mCoin += ($wait->cnt_sms * $this->price_sms); }
            if($wait->cnt_lms) { $mCoin += ($wait->cnt_lms * $this->price_lms); }
            if($wait->cnt_mms) { $mCoin += ($wait->cnt_mms * $this->price_mms); }
        }
        //log_message('error', 'Coid : '.$mCoin);
        return $mCoin;
    }

    function getSendCacheAmount($kind, $mem_userid) {
        /* 발송대기 메시지의 전체 금액을 산정한다. : lms 기본 발송 -> phn */
        $sql = "
			select
				sum(case when ifnull(sc_img_url, '')='' and ifnull(sc_lms_content, '')='' then 1 else 0 end) cnt_nr,
				sum(case when ifnull(sc_img_url, '')='' and ifnull(sc_lms_content, '')<>'' then 1 else 0 end) cnt_lms,
				sum(case when ifnull(sc_img_url, '')<>'' then 1 else 0 end) cnt_img
			from cb_sc_".$mem_userid."
		";
        $query = $this->db->query($sql);
        $mAmount = 0;
        if($query) {
            $sc = $query->row();
            if($sc->cnt_nr) { $mAmount += (($kind=="at") ? ($sc->cnt_nr * $this->price_at) : ($sc->cnt_nr * $this->price_ft)); }
            if($sc->cnt_lms) { $mAmount += ($sc->cnt_lms * $this->price_phn); }
            if($sc->cnt_img) { $mAmount += (($kind=="at") ? ($sc->cnt_img * $this->price_mms) : ($sc->cnt_img * $this->price_ft)); }	/* 알림톡은 이미지 없어요! */
        }
        return $mAmount;
    }

    function checkSendAbleMessage($kind, $mem_id, $mem_userid)
    {
        /* 예치금과 발송대상 금액을 확인하여 발송 가능여부를 알려준다. */
        /* vhsanswk eksrkrk 0인 경우 mst_wait는 검사하지 않는다. */
        $mCoin = $this->getAbleCoin($mem_id, $mem_userid);
        $mAmount = $this->getSendCacheAmount($kind, $mem_userid);
        $sql = "
			SELECT mst_qty, mst_datetime,
				(select count(*) from cb_msg_".$mem_userid." where REMARK4=cb_wt_msg_sent.mst_id) mst_sent,
				".(($this->price_phn > 0) ? "'0'" : "'0'")." mst_wait
			FROM cb_wt_msg_sent where mst_mem_id=? and mst_reserved_dt < ? order by mst_id desc limit 1
		";
        $sent = $this->db->query($sql, array($mem_id, date('YmdHis')))->row();
        if($this->config->item('base_url') == 'http://www.bizalimtalk.kr') {
            if($sent->mst_qty > $sent->mst_sent || $sent->mst_wait > 0) { return "발송중인 메시지가 있습니다. 잠시후 발송하여 주세요.(요청:".$sent->mst_qty.", 발송:".$sent->mst_sent.", 대기:".$sent->mst_wait.")"; }
        }
        /* 예약발송인 경우 예약후 다른 발송이 누적되면 ... */
        /* agent 한번에 4만건 처리, 순차작업, 처리중이던 작업 처리후 다음작업 처리, 결과테이블에 옮겨놓고 결과입력 */
        $sql = "
			SELECT mst_qty, mst_datetime,
				(select count(*) from cb_msg_".$mem_userid." where REMARK4=cb_wt_msg_sent.mst_id) mst_sent,
				".(($this->price_phn > 0) ? "(select count(*) from cb_msg_".$mem_userid." where REMARK4=cb_wt_msg_sent.mst_id and RESULT='N' and SUBSTR(CODE, 1, 1)='K' and MESSAGE<>'InvalidPhoneNumber' and MSG_SMS<>'' and SMS_SENDER<>'')" : "'0'")." mst_wait
			FROM cb_wt_msg_sent where mst_mem_id=? and mst_reserved_dt < ? and mst_reserved_dt >'00000000000000' order by mst_reserved_dt desc limit 1
		";
        $sent = $this->db->query($sql, array($mem_id, date('YmdHis')))->row();
        //if($sent->mst_qty > $sent->mst_sent || $sent->mst_wait > 0) { return "발송중인 메시지가 있습니다. 잠시후 발송하여 주세요..(요청:".$sent->mst_qty.", 발송:".$sent->mst_sent.", 대기:".$sent->mst_wait.")"; }

        if($mCoin >= $mAmount) { return ""; } else { return "예치금 잔액이 부족합니다."; }
    }

    function getTodaySent($mem_id)
    {
        $sql = "SELECT ifnull(sum(mst_qty), 0) as sent_qty FROM cb_wt_msg_sent
			where mst_mem_id=? and (substr(mst_reserved_dt, 1, 8)='".date('Ymd')."'
				or (substr(mst_datetime, 1, 10)='".date('Y-m-d')."' and mst_reserved_dt='00000000000000'))";
        $result = $this->db->query($sql, array($mem_id))->row();
        return $result->sent_qty;
    }

    function make_new_member_data($id) {
        /* 사이트 회원가입으로 신규 가입시 기본 정보 처리해야함 */
        $rData = array();
        $cnt = $this->db->query("select count(*) cnt from cb_member_register where mem_id=?", array($id))->row();
        if($cnt->cnt < 1) {
            $rData['mem_id'] = $id;
            $rData['mrg_ip'] = $this->input->ip_address();
        }
        $rData['mrg_recommend_mem_id'] = 3;		// 최초 상위딜러
        if($cnt->cnt < 1) {
            $this->db->insert("cb_member_register", $aData);
        } else {
            $this->db->update("cb_member_register", $aData, array("mem_id"=>$id));
        }

        $aData = array();
        $cnt = $this->db->query("select count(*) cnt from cb_wt_member_addon where mad_mem_id=?", array($id))->row();
        if($cnt->cnt < 1) {
            $aData['mad_mem_id'] = $id;
            $aData['mad_free_hp'] = "";
        }
        $aData['mad_price_ft'] = $this->price_ft;
        $aData['mad_price_ft_img'] = $this->price_ft_img;
        $aData['mad_price_at'] = $this->price_at;
        $aData['mad_price_sms'] = $this->price_sms;
        $aData['mad_price_lms'] = $this->price_lms;
        $aData['mad_price_mms'] = $this->price_mms;
        $aData['mad_price_phn'] = $this->price_phn;
        $aData['mad_price_grs'] = $this->price_grs;
        $aData['mad_price_grs_sms'] = $this->price_grs_sms;
        $aData['mad_price_grs_mms'] = $this->price_grs_mms;
        $aData['mad_price_nas'] = $this->price_nas;
        $aData['mad_price_nas_sms'] = $this->price_nas_sms;
        $aData['mad_price_nas_mms'] = $this->price_nas_mms;
        $aData['mad_weight1'] = $this->weight[0];
        $aData['mad_weight2'] = $this->weight[1];
        $aData['mad_weight3'] = $this->weight[2];
        $aData['mad_weight4'] = $this->weight[3];
        $aData['mad_weight5'] = $this->weight[4];
        $aData['mad_weight6'] = $this->weight[5];
        $aData['mad_weight7'] = $this->weight[6];
        $aData['mad_weight8'] = $this->weight[7];
        $aData['mad_weight9'] = $this->weight[8];
        $aData['mad_weight10'] = $this->weight[9];
        if($cnt->cnt < 1) {
            $this->db->insert("cb_wt_member_addon", $aData);
        } else {
            $this->db->update("cb_wt_member_addon", $aData, array("mad_mem_id"=>$id));
        }
    }

    function get_member_ori($userid, $withAddon=false)
    {
        if($withAddon) {
            $sql = "
				select a.*, b.mrg_recommend_mem_id,
					a.mem_deposit total_deposit,
					c.*, d.*
				from cb_member a
					inner join cb_member_register b on a.mem_id=b.mem_id
					left join cb_wt_member_addon c on a.mem_id=c.mad_mem_id
                    left join cb_wt_voucher_addon d on a.mem_id=d.vad_mem_id
				where a.mem_useyn='Y' and a.mem_userid=?";
        } else {
            $sql = "
				select a.*,
					a.mem_deposit total_deposit
				from cb_member a
				where a.mem_useyn='Y' and a.mem_userid=?";
        }
		//echo "userid : ". $userid ."<br>";
		//echo $sql ."<br>";
        return $this->db->query($sql, array($userid))->row();
    }
    // 2021.05.24. 카카오마트톡 과 동일하게 변경 함.

    function get_member($userid, $withAddon=false)
    {
        $sql_pay_type = "select mem_pay_type as pay_type from cb_member where mem_userid=?";
        $pay_type = $this->db->query($sql_pay_type, array($userid))->row()->pay_type;
        // log_message("ERROR", "get_member() pay_type : ".$pay_type);

        if($withAddon) {
            // log_message("ERROR", "get_member() withAddon : true");
            /*$sql = "
             select a.*, b.mrg_recommend_mem_id,
             -- a.mem_deposit total_deposit,
             (SELECT
             sum(case when amt_kind IN (1, 3) then amt_amount
             ELSE
             amt_amount * -1
             END) amt
             FROM cb_amt_".$userid.") as total_deposit,
             c.*
             from cb_member a
             inner join cb_member_register b on a.mem_id=b.mem_id
             left join cb_wt_member_addon c on a.mem_id=c.mad_mem_id
             where a.mem_useyn='Y' and a.mem_userid=?";*/
            $sql = "
				select a.*, b.mrg_recommend_mem_id, ";
            if ($pay_type == 'A' || $pay_type == 'T') {
                $sql = $sql." a.mem_deposit total_deposit, ";
            } else {
                // 2020-11-13 이수환 변경
                /*$sql = $sql."
                 (SELECT
                 sum(case when amt_kind IN (1, 3) then amt_amount
                 ELSE
                 amt_amount * -1
                 END) amt
                 FROM cb_amt_".$userid.") as total_deposit, ";*/
                 $sql = $sql."
                     (SELECT
             			SUM(amt_amount * amt_deduct) amt
             			 FROM cb_amt_".$userid." WHERE FIND_IN_SET('바우처', amt_memo)=0 AND FIND_IN_SET('보너스', amt_memo)=0 AND amt_kind != '9') as total_deposit, ";
                  $sql = $sql."
                      (SELECT
              			SUM(amt_amount * amt_deduct) amt
              			 FROM cb_amt_".$userid." WHERE FIND_IN_SET('바우처', amt_memo) OR amt_kind = '9') as voucher_deposit, ";

            }
            $sql = $sql."
					c.*, d.*
				from cb_member a
					inner join cb_member_register b on a.mem_id=b.mem_id
					left join cb_wt_member_addon c on a.mem_id=c.mad_mem_id
                    left join cb_wt_voucher_addon d on a.mem_id=d.vad_mem_id
				where a.mem_useyn='Y' and a.mem_userid=?";

                // log_message("ERROR", "get_member() withAddon : true"." / sql : ".$sql);
        } else {
            // log_message("ERROR", "get_member() withAddon : false");
            /*$sql = "
             select a.*,
             -- a.mem_deposit total_deposit
             (SELECT
             sum(case when amt_kind IN (1, 3) then amt_amount
             ELSE
             amt_amount * -1
             END) amt
             FROM cb_amt_".$userid.") as total_deposit
             from cb_member a
             where a.mem_useyn='Y' and a.mem_userid=?";*/
            $sql = "
				select a.*, ";
            if ($pay_type == 'A' || $pay_type == 'T') {
                $sql = $sql." a.mem_deposit total_deposit ";
            } else {
                // 2020-11-13 이수환 변경
                /*$sql = $sql."
                 (SELECT
                 sum(case when amt_kind IN (1, 3) then amt_amount
                 ELSE
                 amt_amount * -1
                 END) amt
                 FROM cb_amt_".$userid.") as total_deposit ";*/
                $sql = $sql."
                    (SELECT
            			SUM(amt_amount * amt_deduct) amt
            			 FROM cb_amt_".$userid.") as total_deposit ";
            }
            $sql = $sql."
				from cb_member a
				where a.mem_useyn='Y' and a.mem_userid=?";

        }
        // log_message("ERROR", "get_member() sql : ".$sql);
        return $this->db->query($sql, array($userid))->row();
    }


    function get_no_member($userid, $withAddon=false)
    {
        $sql_pay_type = "select mem_pay_type as pay_type from cb_member where mem_userid=?";
        $pay_type = $this->db->query($sql_pay_type, array($userid))->row()->pay_type;
        // log_message("ERROR", "get_member() pay_type : ".$pay_type);

        if($withAddon) {
            // log_message("ERROR", "get_member() withAddon : true");
            /*$sql = "
             select a.*, b.mrg_recommend_mem_id,
             -- a.mem_deposit total_deposit,
             (SELECT
             sum(case when amt_kind IN (1, 3) then amt_amount
             ELSE
             amt_amount * -1
             END) amt
             FROM cb_amt_".$userid.") as total_deposit,
             c.*
             from cb_member a
             inner join cb_member_register b on a.mem_id=b.mem_id
             left join cb_wt_member_addon c on a.mem_id=c.mad_mem_id
             where a.mem_useyn='Y' and a.mem_userid=?";*/
            $sql = "
				select a.*, b.mrg_recommend_mem_id, ";
            if ($pay_type == 'A' || $pay_type == 'T') {
                $sql = $sql." a.mem_deposit total_deposit, ";
            } else {
                // 2020-11-13 이수환 변경
                /*$sql = $sql."
                 (SELECT
                 sum(case when amt_kind IN (1, 3) then amt_amount
                 ELSE
                 amt_amount * -1
                 END) amt
                 FROM cb_amt_".$userid.") as total_deposit, ";*/
                 $sql = $sql."
                     (SELECT
             			SUM(amt_amount * amt_deduct) amt
             			 FROM cb_amt_".$userid." WHERE FIND_IN_SET('바우처', amt_memo)=0 AND FIND_IN_SET('보너스', amt_memo)=0 AND amt_kind != '9') as total_deposit, ";
                  $sql = $sql."
                      (SELECT
              			SUM(amt_amount * amt_deduct) amt
              			 FROM cb_amt_".$userid." WHERE FIND_IN_SET('바우처', amt_memo) OR amt_kind = '9') as voucher_deposit, ";

            }
            $sql = $sql."
					c.*, d.*
				from cb_member a
					inner join cb_member_register b on a.mem_id=b.mem_id
					left join cb_wt_member_addon c on a.mem_id=c.mad_mem_id
                    left join cb_wt_voucher_addon d on a.mem_id=d.vad_mem_id
				where a.mem_useyn='N' and a.mem_userid=?";

                // log_message("ERROR", "get_member() withAddon : true"." / sql : ".$sql);
        } else {
            // log_message("ERROR", "get_member() withAddon : false");
            /*$sql = "
             select a.*,
             -- a.mem_deposit total_deposit
             (SELECT
             sum(case when amt_kind IN (1, 3) then amt_amount
             ELSE
             amt_amount * -1
             END) amt
             FROM cb_amt_".$userid.") as total_deposit
             from cb_member a
             where a.mem_useyn='Y' and a.mem_userid=?";*/
            $sql = "
				select a.*, ";
            if ($pay_type == 'A' || $pay_type == 'T') {
                $sql = $sql." a.mem_deposit total_deposit ";
            } else {
                // 2020-11-13 이수환 변경
                /*$sql = $sql."
                 (SELECT
                 sum(case when amt_kind IN (1, 3) then amt_amount
                 ELSE
                 amt_amount * -1
                 END) amt
                 FROM cb_amt_".$userid.") as total_deposit ";*/
                $sql = $sql."
                    (SELECT
            			SUM(amt_amount * amt_deduct) amt
            			 FROM cb_amt_".$userid.") as total_deposit ";
            }
            $sql = $sql."
				from cb_member a
				where a.mem_useyn='N' and a.mem_userid=?";

        }
        // log_message("ERROR", "get_member() sql : ".$sql);
        return $this->db->query($sql, array($userid))->row();
    }

    /* 파트너 목록에 사용자 총 예치금 */
    public function getTotalDeposit($userid) {
        // 2020-11-13 이수환 변경
        /*$sql = "SELECT
         sum(case when amt_kind IN (1, 3) then amt_amount
         ELSE
         amt_amount * -1
         END) amt
         FROM cb_amt_".$userid;*/
        $sql = "SELECT
        			SUM(amt_amount * amt_deduct) amt
        		FROM cb_amt_".$userid." WHERE FIND_IN_SET('바우처', amt_memo)=0 AND FIND_IN_SET('보너스', amt_memo)=0 AND amt_kind != '9' ";
        // log_message("error", $sql);
        return $this->db->query($sql, $param )->row()->amt;
    }

    /* 파트너 목록에 사용자 바우처 예치금 */
    public function getVoucherDeposit($userid) {
        // =& get_instance();
        if(!$this->db) {
            $CI->load->model('db');
            //log_message("ERROR", "TEST111111111111111 ");
        }
        // 2020-11-13 이수환 변경
        /*$sql = "SELECT
         sum(case when amt_kind IN (1, 3) then amt_amount
         ELSE
         amt_amount * -1
         END) amt
         FROM cb_amt_".$userid;*/
        $sql = "SELECT
        			SUM(amt_amount * amt_deduct) amt
        		FROM cb_amt_".$userid." WHERE FIND_IN_SET('바우처', amt_memo) OR amt_kind = '9' ";

        return $this->db->query($sql, $param)->row()->amt;
    }

    function get_offer()
    {
        return $this->db->query("select mem_id, mem_username from cb_member where mem_useyn='Y' and mem_level>=".$this->manager_lv." order by mem_level, mem_username")->result();
    }

    function get_member_child_id($mem_id)
    {
        // $sql = "select  b.mem_id, b.mrg_recommend_mem_id
        // from (select * from cb_member_register
		// 		where
		// 			FIND_IN_SET(mem_id,
		// 				(SELECT GROUP_CONCAT(memid SEPARATOR ',') memid FROM (
		// 					SELECT @pv:=(SELECT GROUP_CONCAT(mem_id SEPARATOR ',') FROM cb_member_register
		// 					WHERE FIND_IN_SET(mrg_recommend_mem_id, @pv)) AS memid FROM cb_member_register
		// 					JOIN (SELECT @pv:=?) tmp
		// 				) a)
		// 			) > 0 or mem_id=?) b";
        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                    JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                    JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            SELECT DISTINCT mem_id, parent_id
            FROM cmem
            UNION ALL
            select mem_id, (select mrg_recommend_mem_id from cb_member_register where mem_id = '".$mem_id."') as parent_id
            from cb_member cm
            where cm.mem_id = '".$mem_id."'
        ";


        /*		$sql = "select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ?) initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         union select ? mem_id, ? mrg_recommend_mem_id";*/
        //$rs = $this->db->query($sql, array($mem_id, $mem_id, $mem_id))->result();
        // log_message("error", $sql);
        // log_message("error", $mem_id);
        $rs = $this->db->query($sql)->result();
        // $rs = $this->db->query($sql, array($mem_id, $mem_id))->result();
        $result = array();
        foreach($rs as $r) { array_push($result, $r->mem_id); }
        return $result;
    }

    function get_send_report($kind, $startDate, $endDate, $pf_key)
    {
        $param = array();
        $where = "";
        if($pf_key && $pf_key!="ALL") {
            $where .= " and mst_mem_id = ? ";
            array_push($param, $pf_key);
        }
        if($startDate && $endDate) {
            $where .= " and (
		                	 ( mst_reserved_dt <> '00000000000000' AND
                              (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN ? AND ?)
                             ) OR
                             ( mst_reserved_dt = '00000000000000' AND
                              (mst_datetime BETWEEN ? AND ?)
                             )
                            )";
            array_push($param, $startDate.(($kind=="day") ? " 00:00:00" : "-01 00:00:00"));
            array_push($param, $endDate.(($kind=="day") ? " 23:59:59" : "-31 23:59:59"));
            array_push($param, $startDate.(($kind=="day") ? " 00:00:00" : "-01 00:00:00"));
            array_push($param, $endDate.(($kind=="day") ? " 23:59:59" : "-31 23:59:59"));
        }
        if($pf_key && $pf_key!="ALL") {
            $sql = "select
					mst_datetime, ";
            if ($kind == 'month') {
                $sql .= "		(select mem_username from cb_member where mem_id = mst_mem_id) as company,";
            }
            $sql .= "		sum(mst_qty) mst_qty,
                    sum(reserved_qty) reserved_qty,
                    sum(mst_ft) mst_ft,
					sum(mst_ft_img) mst_ft_img,
                    sum(mst_ft_wide_img) mst_ft_wide_img,
					sum(mst_at) mst_at,
					sum(mst_ai) mst_ai,
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
                    sum(mst_rcs_sms) AS mst_rcs_sms,
                    sum(mst_rcs) AS mst_rcs,
                    sum(mst_rcs_mms) AS mst_rcs_mms,
                    sum(mst_rcs_tpl) AS mst_rcs_tpl,
					sum(mst_err_at) err_at,
					sum(mst_err_ai) err_ai,
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
                    sum(mst_err_rcs_sms) AS qty_rcs_sms,
                    sum(mst_err_rcs) AS qty_rcs,
                    sum(mst_err_rcs_mms) AS qty_rcs_mms,
                    sum(mst_err_rcs_tpl) AS qty_rcs_tpl,
                    sum(mst_wait) AS qty_wait,
                    sum(mst_rcs_wait) AS qty_rcs_wait
				from (
				select ";
            if ($kind == 'month') {
                $sql .= "		mst_mem_id,
                    substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 7) mst_datetime,";
            } else {
                $sql .= "		substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 10) mst_datetime,";
            }
            $sql .= "
                    (case when mst_reserved_dt <> '00000000000000' AND mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') then mst_qty else  0 end) reserved_qty,
                    mst_qty,
					mst_ft,
					case when (mst_type1 = 'fti') then mst_ft_img else 0 end as mst_ft_img,
                    case when (mst_type1 = 'ftw') then mst_ft_img else 0 end as mst_ft_wide_img,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_at else 0 end as mst_at,
                    case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_at else 0 end as mst_ai,
					mst_sms,
					mst_lms,
					mst_mms,
                    case when (mst_type2 = 'wa' OR mst_type3 = 'wa') then (CASE WHEN (mst_type3 = 'wa') THEN mst_grs ELSE (mst_grs + mst_wait) END) else 0 end as mst_grs,
                    case when (mst_type2 = 'was' OR mst_type3 = 'was') then (CASE WHEN (mst_type3 = 'was') THEN mst_grs_sms ELSE (mst_grs_sms + mst_wait) END) else 0 end as mst_grs_sms,
                    mst_grs_biz_qty,
                    case when (mst_type2 = 'wb' OR mst_type3 = 'wb') then (CASE WHEN (mst_type3 = 'wb') THEN mst_nas ELSE (mst_nas + mst_wait) END) else 0 end as mst_nas,
                    case when (mst_type2 = 'wbs' OR mst_type3 = 'wbs') then (CASE WHEN (mst_type3 = 'wbs') THEN mst_nas_sms ELSE (mst_nas_sms + mst_wait) END) else 0 end as mst_nas_sms,
					mst_015,
					mst_phn,
                    case when (mst_type2 = 'wam' OR mst_type3 = 'wam') then (CASE WHEN (mst_type3 = 'wam') THEN mst_grs ELSE (mst_grs + mst_wait) END) else 0 end as mst_grs_mms,
                    case when (mst_type2 = 'wbm' OR mst_type3 = 'wbm') then (CASE WHEN (mst_type3 = 'wbm') THEN mst_nas ELSE (mst_nas + mst_wait) END) else 0 end as mst_nas_mms,
                    case when (mst_type2 = 'wc' OR mst_type3 = 'wc') then (CASE WHEN (mst_type3 = 'wc') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt,
                    case when (mst_type2 = 'wcs' OR mst_type3 = 'wcs') then (CASE WHEN (mst_type3 = 'wcs') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt_sms,
                    case when (mst_type2 = 'wcm' OR mst_type3 = 'wcm') then (CASE WHEN (mst_type3 = 'wcm') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt_mms,
                    CASE WHEN (mst_type2 = 'rcs') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_sms,
                    CASE WHEN (mst_type2 = 'rc') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs,
                    CASE WHEN (mst_type2 = 'rcm') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_mms,
                    CASE WHEN (mst_type2 = 'rct') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_tpl,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_err_at else 0 end as mst_err_at,
					case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_err_at else 0 end as mst_err_ai,
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
					case when (mst_type2 = 'wc') then mst_err_smt else 0 end as mst_err_smt,
					case when (mst_type2 = 'wcs') then mst_err_smt else 0 end as mst_err_smt_sms,
					case when (mst_type2 = 'wcm') then mst_err_smt else 0 end as mst_err_smt_mms,
                    CASE WHEN (mst_type2 = 'rcs') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_sms,
                    CASE WHEN (mst_type2 = 'rc') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs,
                    CASE WHEN (mst_type2 = 'rcm') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_mms,
                    CASE WHEN (mst_type2 = 'rct') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_tpl,
                    CASE WHEN NOT (mst_type2 = 'rcs' OR mst_type2 = 'rc' OR mst_type2 = 'rcm' OR mst_type2 = 'rct')
                        THEN
                            mst_wait
                        ELSE
                            0  -- rsc + 문자 대기값을 모두 rcs 쪽으로 보내기.
                            -- mst_wait
                    END AS mst_wait,
                    CASE WHEN (mst_type2 = 'rcs' OR mst_type2 = 'rc' OR mst_type2 = 'rcm' OR mst_type2 = 'rct')
                        THEN
                            -- rsc + 문자 모두 대기값을 rcs 대기값으로 설정.
                            mst_wait
                            -- (CASE WHEN mst_type3 IS NULL THEN mst_wai ELSE 0 END)
                        ELSE
                            0
                    END AS mst_rcs_wait
				from cb_wt_msg_sent where 1=1 ".$where.") temp group by mst_datetime";
            if ($kind == 'month') {
                $sql .= ", mst_mem_id";
            }
            $sql .= " order by 1 desc";
            //             $sql = "select
            // 					substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, ".(($kind=="day") ? "10" : "7").") mst_datetime,
            // 					sum(mst_qty) mst_qty,
            // 					sum(mst_ft) mst_ft,
            // 					sum(mst_ft_img) mst_ft_img,
            // 					sum(mst_at) mst_at,
            // 					sum(mst_sms) mst_sms,
            // 					sum(mst_lms) mst_lms,
            // 					sum(mst_mms) mst_mms,
            //                     sum(mst_grs) mst_grs,
            //                     sum(mst_grs_sms) mst_grs_sms,
            //                     sum(mst_nas) mst_nas,
            //                     sum(mst_nas_sms) mst_nas_sms,
            //                     sum(mst_015) mst_015,
            // 					sum(mst_phn) mst_phn,
            // 					sum(mst_err_at) err_at,
            // 					sum(mst_err_ft) err_ft,
            // 					sum(mst_err_ft_img) err_ft_img,
            // 					sum(mst_err_sms) qty_sms,
            // 					sum(mst_err_lms) qty_lms,
            // 					sum(mst_err_mms) qty_mms,
            //                     sum(mst_err_grs) qty_grs,
            //                     sum(mst_err_grs_sms) qty_grs_sms,
            //                     sum(mst_err_nas) qty_nas,
            //                     sum(mst_err_nas_sms) qty_nas_sms,
            //                     sum(mst_err_015) qty_015,
            // 					sum(mst_err_phn) qty_phn
            // 				from cb_wt_msg_sent where 1=1 ".$where." group by substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, ".(($kind=="day") ? "10" : "7").") order by 1 desc";
        } else {
            $sql = "select
					mst_datetime,
                    sum(mst_qty) mst_qty,
                    sum(reserved_qty) reserved_qty,
					sum(mst_ft) mst_ft,
					sum(mst_ft_img) mst_ft_img,
                    sum(mst_ft_wide_img) mst_ft_wide_img,
					sum(mst_at) mst_at,
					sum(mst_ai) mst_ai,
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
                    sum(mst_rcs_sms) AS mst_rcs_sms,
                    sum(mst_rcs) AS mst_rcs,
                    sum(mst_rcs_mms) AS mst_rcs_mms,
                    sum(mst_rcs_tpl) AS mst_rcs_tpl,
					sum(mst_err_at) err_at,
					sum(mst_err_ai) err_ai,
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
                    sum(mst_err_rcs_sms) AS qty_rcs_sms,
                    sum(mst_err_rcs) AS qty_rcs,
                    sum(mst_err_rcs_mms) AS qty_rcs_mms,
                    sum(mst_err_rcs_tpl) AS qty_rcs_tpl,
                    sum(mst_wait) AS qty_wait,
                    sum(mst_rcs_wait) AS qty_rcs_wait
				from (
				select ";
            if ($kind == 'month') {
                $sql .= "		substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 7) mst_datetime,";
            } else {
                $sql .= "		substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 10) mst_datetime,";
            }
            $sql .= "
                    (case when mst_reserved_dt <> '00000000000000' AND mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') then mst_qty else  0 end) reserved_qty,
                    mst_qty,
					mst_ft,
					case when (mst_type1 = 'fti') then mst_ft_img else 0 end as mst_ft_img,
                    case when (mst_type1 = 'ftw') then mst_ft_img else 0 end as mst_ft_wide_img,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_at else 0 end as mst_at,
                    case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_at else 0 end as mst_ai,
					mst_sms,
					mst_lms,
					mst_mms,
                    case when (mst_type2 = 'wa' OR mst_type3 = 'wa') then (CASE WHEN (mst_type3 = 'wa') THEN mst_grs ELSE (mst_grs + mst_wait) END) else 0 end as mst_grs,
                    case when (mst_type2 = 'was' OR mst_type3 = 'was') then (CASE WHEN (mst_type3 = 'was') THEN mst_grs_sms ELSE (mst_grs_sms + mst_wait) END) else 0 end as mst_grs_sms,
                    mst_grs_biz_qty,
                    case when (mst_type2 = 'wb' OR mst_type3 = 'wb') then (CASE WHEN (mst_type3 = 'wb') THEN mst_nas ELSE (mst_nas + mst_wait) END) else 0 end as mst_nas,
                    case when (mst_type2 = 'wbs' OR mst_type3 = 'wbs') then (CASE WHEN (mst_type3 = 'wbs') THEN mst_nas_sms ELSE (mst_nas_sms + mst_wait) END) else 0 end as mst_nas_sms,
					mst_015,
					mst_phn,
                    case when (mst_type2 = 'wam' OR mst_type3 = 'wam') then (CASE WHEN (mst_type3 = 'wam') THEN mst_grs ELSE (mst_grs + mst_wait) END) else 0 end as mst_grs_mms,
                    case when (mst_type2 = 'wbm' OR mst_type3 = 'wbm') then (CASE WHEN (mst_type3 = 'wbm') THEN mst_nas ELSE (mst_nas + mst_wait) END) else 0 end as mst_nas_mms,
                    case when (mst_type2 = 'wc' OR mst_type3 = 'wc') then (CASE WHEN (mst_type3 = 'wc') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt,
                    case when (mst_type2 = 'wcs' OR mst_type3 = 'wcs') then (CASE WHEN (mst_type3 = 'wcs') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt_sms,
                    case when (mst_type2 = 'wcm' OR mst_type3 = 'wcm') then (CASE WHEN (mst_type3 = 'wcm') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt_mms,
                    CASE WHEN (mst_type2 = 'rcs') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_sms,
                    CASE WHEN (mst_type2 = 'rc') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs,
                    CASE WHEN (mst_type2 = 'rcm') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_mms,
                    CASE WHEN (mst_type2 = 'rct') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_tpl,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_err_at else 0 end as mst_err_at,
					case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_err_at else 0 end as mst_err_ai,
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
					case when (mst_type2 = 'wc') then mst_err_smt else 0 end as mst_err_smt,
					case when (mst_type2 = 'wcs') then mst_err_smt else 0 end as mst_err_smt_sms,
					case when (mst_type2 = 'wcm') then mst_err_smt else 0 end as mst_err_smt_mms,
                    CASE WHEN (mst_type2 = 'rcs') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_sms,
                    CASE WHEN (mst_type2 = 'rc') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs,
                    CASE WHEN (mst_type2 = 'rcm') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_mms,
                    CASE WHEN (mst_type2 = 'rct') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_tpl,
                    CASE WHEN NOT (mst_type2 = 'rcs' OR mst_type2 = 'rc' OR mst_type2 = 'rcm' OR mst_type2 = 'rct')
                        THEN
                            mst_wait
                        ELSE
                            0  -- rsc + 문자 대기값을 모두 rcs 쪽으로 보내기.
                            -- mst_wait
                    END AS mst_wait,
                    CASE WHEN (mst_type2 = 'rcs' OR mst_type2 = 'rc' OR mst_type2 = 'rcm' OR mst_type2 = 'rct')
                        THEN
                            -- rsc + 문자 모두 대기값을 rcs 대기값으로 설정.
                            mst_wait
                            -- (CASE WHEN mst_type3 IS NULL THEN mst_wai ELSE 0 END)
                        ELSE
                            0
                    END AS mst_rcs_wait
				from cb_wt_msg_sent where (mst_mem_id in (
                					WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
                    	UNION ALL
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                    )
                    SELECT distinct mem_id
                    FROM cmem
                    ) or mst_mem_id = ".$this->member->item("mem_id").")    ".$where.") temp group by mst_datetime order by 1 desc";

            //             $sql = "select
            // 					substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, ".(($kind=="day") ? "10" : "7").") mst_datetime,
            // 					sum(mst_qty) mst_qty,
            // 					sum(mst_ft) mst_ft,
            // 					sum(mst_ft_img) mst_ft_img,
            // 					sum(mst_at) mst_at,
            // 					sum(mst_sms) mst_sms,
            // 					sum(mst_lms) mst_lms,
            // 					sum(mst_mms) mst_mms,
            //                     sum(mst_grs) mst_grs,
            //                     sum(mst_grs_sms) mst_grs_sms,
            //                     sum(mst_nas) mst_nas,
            //                     sum(mst_nas_sms) mst_nas_sms,
            //                     sum(mst_015) mst_015,
            // 					sum(mst_phn) mst_phn,
            // 					sum(mst_err_at) err_at,
            // 					sum(mst_err_ft) err_ft,
            // 					sum(mst_err_ft_img) err_ft_img,
            // 					sum(mst_err_sms) qty_sms,
            // 					sum(mst_err_lms) qty_lms,
            // 					sum(mst_err_mms) qty_mms,
            //                     sum(mst_err_grs) qty_grs,
            //                     sum(mst_err_grs_sms) qty_grs_sms,
            //                     sum(mst_err_nas) qty_nas,
            //                     sum(mst_err_nas_sms) qty_nas_sms,
            //                     sum(mst_err_015) qty_015,
            // 					sum(mst_err_phn) qty_phn
            // 				from cb_wt_msg_sent where (mst_mem_id in (
            //                 					WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            //                     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            //                     	FROM cb_member_register cmr
            //                     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            //                     	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
            //                     	UNION ALL
            //                     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            //                     	FROM cb_member_register cmr
            //                     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            //                     	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            //                     )
            //                     SELECT distinct mem_id
            //                     FROM cmem
            //                     ) or mst_mem_id = ".$this->member->item("mem_id").")    ".$where." group by substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, ".(($kind=="day") ? "10" : "7").") order by 1 desc";
            //log_message('error', 'Query = '.$sql);
            /*			$sql = "select
             substr(mst_datetime, 1, ".(($kind=="day") ? "10" : "7").") mst_datetime,
             sum(mst_qty) mst_qty,
             sum(mst_ft) mst_ft,
             sum(mst_ft_img) mst_ft_img,
             sum(mst_at) mst_at,
             sum(mst_phn) mst_phn,
             sum(mst_sms) mst_sms,
             sum(mst_lms) mst_lms,
             sum(mst_mms) mst_mms
             from cb_wt_msg_sent where mst_profile in (
             select a.spf_key from cb_wt_send_profile a inner join
             (select  mem_id, mrg_recommend_mem_id
             from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
             (select @pv := ".$this->mem_id.") initialisation
             where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
             union select '".$this->mem_id."' mem_id, '".$this->mem_id."' mrg_recommend_mem_id
             ) b on a.spf_mem_id = b.mem_id
             where a.spf_use='Y' and a.spf_key<>''
             ) ".$where." group by substr(mst_datetime, 1, ".(($kind=="day") ? "10" : "7").") order by 1 desc";*/
        }
        // log_message('error', 'Query = '.$sql);
        return $this->db->query($sql, $param)->result();
    }

    // 2023-03-03 영업자 하위 partner가져오기.
    // $sales_id : 영업자 코드
    // $option S : 영업자 담당자, M : 관리 담당자, A : 전체, O : 관리 담당자만 있는 있는경우.
    function get_sales_send_report($kind, $startDate, $endDate, $pf_key, $sales_id, $option)
    {
        $param = array();
        $where = "";
        if($pf_key && $pf_key!="ALL") {
            $where .= " and mst_mem_id = ? ";
            array_push($param, $pf_key);
        }
        if($startDate && $endDate) {
            $where .= " and (
		                	 ( mst_reserved_dt <> '00000000000000' AND
                              (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN ? AND ?)
                             ) OR
                             ( mst_reserved_dt = '00000000000000' AND
                              (mst_datetime BETWEEN ? AND ?)
                             )
                            )";
            array_push($param, $startDate.(($kind=="day") ? " 00:00:00" : "-01 00:00:00"));
            array_push($param, $endDate.(($kind=="day") ? " 23:59:59" : "-31 23:59:59"));
            array_push($param, $startDate.(($kind=="day") ? " 00:00:00" : "-01 00:00:00"));
            array_push($param, $endDate.(($kind=="day") ? " 23:59:59" : "-31 23:59:59"));
        }
        if($pf_key && $pf_key!="ALL") {
            $sql = "select
					mst_datetime, ";
            if ($kind == 'month') {
                $sql .= "		(select mem_username from cb_member where mem_id = mst_mem_id) as company,";
            }
            $sql .= "		sum(mst_qty) mst_qty,
                    sum(reserved_qty) reserved_qty,
                    sum(mst_ft) mst_ft,
					sum(mst_ft_img) mst_ft_img,
                    sum(mst_ft_wide_img) mst_ft_wide_img,
					sum(mst_at) mst_at,
					sum(mst_ai) mst_ai,
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
                    sum(mst_rcs_sms) AS mst_rcs_sms,
                    sum(mst_rcs) AS mst_rcs,
                    sum(mst_rcs_mms) AS mst_rcs_mms,
                    sum(mst_rcs_tpl) AS mst_rcs_tpl,
					sum(mst_err_at) err_at,
					sum(mst_err_ai) err_ai,
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
                    sum(mst_err_rcs_sms) AS qty_rcs_sms,
                    sum(mst_err_rcs) AS qty_rcs,
                    sum(mst_err_rcs_mms) AS qty_rcs_mms,
                    sum(mst_err_rcs_tpl) AS qty_rcs_tpl,
                    sum(mst_wait) AS qty_wait,
                    sum(mst_rcs_wait) AS qty_rcs_wait
				from (
				select ";
            if ($kind == 'month') {
                $sql .= "		mst_mem_id,
                    substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 7) mst_datetime,";
            } else {
                $sql .= "		substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 10) mst_datetime,";
            }
            $sql .= "
                    (case when mst_reserved_dt <> '00000000000000' AND mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') then mst_qty else  0 end) reserved_qty,
                    mst_qty,
					mst_ft,
					case when (mst_type1 = 'fti') then mst_ft_img else 0 end as mst_ft_img,
                    case when (mst_type1 = 'ftw') then mst_ft_img else 0 end as mst_ft_wide_img,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_at else 0 end as mst_at,
                    case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_at else 0 end as mst_ai,
					mst_sms,
					mst_lms,
					mst_mms,
                    case when (mst_type2 = 'wa' OR mst_type3 = 'wa') then (CASE WHEN (mst_type3 = 'wa') THEN mst_grs ELSE (mst_grs + mst_wait) END) else 0 end as mst_grs,
                    case when (mst_type2 = 'was' OR mst_type3 = 'was') then (CASE WHEN (mst_type3 = 'was') THEN mst_grs_sms ELSE (mst_grs_sms + mst_wait) END) else 0 end as mst_grs_sms,
                    mst_grs_biz_qty,
                    case when (mst_type2 = 'wb' OR mst_type3 = 'wb') then (CASE WHEN (mst_type3 = 'wb') THEN mst_nas ELSE (mst_nas + mst_wait) END) else 0 end as mst_nas,
                    case when (mst_type2 = 'wbs' OR mst_type3 = 'wbs') then (CASE WHEN (mst_type3 = 'wbs') THEN mst_nas_sms ELSE (mst_nas_sms + mst_wait) END) else 0 end as mst_nas_sms,
					mst_015,
					mst_phn,
                    case when (mst_type2 = 'wam' OR mst_type3 = 'wam') then (CASE WHEN (mst_type3 = 'wam') THEN mst_grs ELSE (mst_grs + mst_wait) END) else 0 end as mst_grs_mms,
                    case when (mst_type2 = 'wbm' OR mst_type3 = 'wbm') then (CASE WHEN (mst_type3 = 'wbm') THEN mst_nas ELSE (mst_nas + mst_wait) END) else 0 end as mst_nas_mms,
                    case when (mst_type2 = 'wc' OR mst_type3 = 'wc') then (CASE WHEN (mst_type3 = 'wc') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt,
                    case when (mst_type2 = 'wcs' OR mst_type3 = 'wcs') then (CASE WHEN (mst_type3 = 'wcs') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt_sms,
                    case when (mst_type2 = 'wcm' OR mst_type3 = 'wcm') then (CASE WHEN (mst_type3 = 'wcm') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt_mms,
                    CASE WHEN (mst_type2 = 'rcs') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_sms,
                    CASE WHEN (mst_type2 = 'rc') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs,
                    CASE WHEN (mst_type2 = 'rcm') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_mms,
                    CASE WHEN (mst_type2 = 'rct') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_tpl,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_err_at else 0 end as mst_err_at,
					case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_err_at else 0 end as mst_err_ai,
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
					case when (mst_type2 = 'wc') then mst_err_smt else 0 end as mst_err_smt,
					case when (mst_type2 = 'wcs') then mst_err_smt else 0 end as mst_err_smt_sms,
					case when (mst_type2 = 'wcm') then mst_err_smt else 0 end as mst_err_smt_mms,
                    CASE WHEN (mst_type2 = 'rcs') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_sms,
                    CASE WHEN (mst_type2 = 'rc') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs,
                    CASE WHEN (mst_type2 = 'rcm') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_mms,
                    CASE WHEN (mst_type2 = 'rct') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_tpl,
                    CASE WHEN NOT (mst_type2 = 'rcs' OR mst_type2 = 'rc' OR mst_type2 = 'rcm' OR mst_type2 = 'rct')
                        THEN
                            mst_wait
                        ELSE
                            0  -- rsc + 문자 대기값을 모두 rcs 쪽으로 보내기.
                            -- mst_wait
                    END AS mst_wait,
                    CASE WHEN (mst_type2 = 'rcs' OR mst_type2 = 'rc' OR mst_type2 = 'rcm' OR mst_type2 = 'rct')
                        THEN
                            -- rsc + 문자 모두 대기값을 rcs 대기값으로 설정.
                            mst_wait
                            -- (CASE WHEN mst_type3 IS NULL THEN mst_wai ELSE 0 END)
                        ELSE
                            0
                    END AS mst_rcs_wait
				from cb_wt_msg_sent where 1=1 ".$where.") temp group by mst_datetime";
            if ($kind == 'month') {
                $sql .= ", mst_mem_id";
            }
            $sql .= " order by 1 desc";
            //             $sql = "select
            // 					substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, ".(($kind=="day") ? "10" : "7").") mst_datetime,
            // 					sum(mst_qty) mst_qty,
            // 					sum(mst_ft) mst_ft,
            // 					sum(mst_ft_img) mst_ft_img,
            // 					sum(mst_at) mst_at,
            // 					sum(mst_sms) mst_sms,
            // 					sum(mst_lms) mst_lms,
            // 					sum(mst_mms) mst_mms,
            //                     sum(mst_grs) mst_grs,
            //                     sum(mst_grs_sms) mst_grs_sms,
            //                     sum(mst_nas) mst_nas,
            //                     sum(mst_nas_sms) mst_nas_sms,
            //                     sum(mst_015) mst_015,
            // 					sum(mst_phn) mst_phn,
            // 					sum(mst_err_at) err_at,
            // 					sum(mst_err_ft) err_ft,
            // 					sum(mst_err_ft_img) err_ft_img,
            // 					sum(mst_err_sms) qty_sms,
            // 					sum(mst_err_lms) qty_lms,
            // 					sum(mst_err_mms) qty_mms,
            //                     sum(mst_err_grs) qty_grs,
            //                     sum(mst_err_grs_sms) qty_grs_sms,
            //                     sum(mst_err_nas) qty_nas,
            //                     sum(mst_err_nas_sms) qty_nas_sms,
            //                     sum(mst_err_015) qty_015,
            // 					sum(mst_err_phn) qty_phn
            // 				from cb_wt_msg_sent where 1=1 ".$where." group by substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, ".(($kind=="day") ? "10" : "7").") order by 1 desc";
        } else {
            $sql = "select
					mst_datetime,
                    sum(mst_qty) mst_qty,
                    sum(reserved_qty) reserved_qty,
					sum(mst_ft) mst_ft,
					sum(mst_ft_img) mst_ft_img,
                    sum(mst_ft_wide_img) mst_ft_wide_img,
					sum(mst_at) mst_at,
					sum(mst_ai) mst_ai,
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
                    sum(mst_rcs_sms) AS mst_rcs_sms,
                    sum(mst_rcs) AS mst_rcs,
                    sum(mst_rcs_mms) AS mst_rcs_mms,
                    sum(mst_rcs_tpl) AS mst_rcs_tpl,
					sum(mst_err_at) err_at,
					sum(mst_err_ai) err_ai,
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
                    sum(mst_err_rcs_sms) AS qty_rcs_sms,
                    sum(mst_err_rcs) AS qty_rcs,
                    sum(mst_err_rcs_mms) AS qty_rcs_mms,
                    sum(mst_err_rcs_tpl) AS qty_rcs_tpl,
                    sum(mst_wait) AS qty_wait,
                    sum(mst_rcs_wait) AS qty_rcs_wait
				from (
				select ";
            if ($kind == 'month') {
                $sql .= "		substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 7) mst_datetime,";
            } else {
                $sql .= "		substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, 10) mst_datetime,";
            }
            $sql .= "
                    (case when mst_reserved_dt <> '00000000000000' AND mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') then mst_qty else  0 end) reserved_qty,
                    mst_qty,
					mst_ft,
					case when (mst_type1 = 'fti') then mst_ft_img else 0 end as mst_ft_img,
                    case when (mst_type1 = 'ftw') then mst_ft_img else 0 end as mst_ft_wide_img,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_at else 0 end as mst_at,
                    case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_at else 0 end as mst_ai,
					mst_sms,
					mst_lms,
					mst_mms,
                    case when (mst_type2 = 'wa' OR mst_type3 = 'wa') then (CASE WHEN (mst_type3 = 'wa') THEN mst_grs ELSE (mst_grs + mst_wait) END) else 0 end as mst_grs,
                    case when (mst_type2 = 'was' OR mst_type3 = 'was') then (CASE WHEN (mst_type3 = 'was') THEN mst_grs_sms ELSE (mst_grs_sms + mst_wait) END) else 0 end as mst_grs_sms,
                    mst_grs_biz_qty,
                    case when (mst_type2 = 'wb' OR mst_type3 = 'wb') then (CASE WHEN (mst_type3 = 'wb') THEN mst_nas ELSE (mst_nas + mst_wait) END) else 0 end as mst_nas,
                    case when (mst_type2 = 'wbs' OR mst_type3 = 'wbs') then (CASE WHEN (mst_type3 = 'wbs') THEN mst_nas_sms ELSE (mst_nas_sms + mst_wait) END) else 0 end as mst_nas_sms,
					mst_015,
					mst_phn,
                    case when (mst_type2 = 'wam' OR mst_type3 = 'wam') then (CASE WHEN (mst_type3 = 'wam') THEN mst_grs ELSE (mst_grs + mst_wait) END) else 0 end as mst_grs_mms,
                    case when (mst_type2 = 'wbm' OR mst_type3 = 'wbm') then (CASE WHEN (mst_type3 = 'wbm') THEN mst_nas ELSE (mst_nas + mst_wait) END) else 0 end as mst_nas_mms,
                    case when (mst_type2 = 'wc' OR mst_type3 = 'wc') then (CASE WHEN (mst_type3 = 'wc') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt,
                    case when (mst_type2 = 'wcs' OR mst_type3 = 'wcs') then (CASE WHEN (mst_type3 = 'wcs') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt_sms,
                    case when (mst_type2 = 'wcm' OR mst_type3 = 'wcm') then (CASE WHEN (mst_type3 = 'wcm') THEN mst_smt ELSE (mst_smt + mst_wait) END) else 0 end as mst_smt_mms,
                    CASE WHEN (mst_type2 = 'rcs') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_sms,
                    CASE WHEN (mst_type2 = 'rc') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs,
                    CASE WHEN (mst_type2 = 'rcm') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_mms,
                    CASE WHEN (mst_type2 = 'rct') THEN (mst_rcs + mst_wait) ELSE 0 END AS mst_rcs_tpl,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_err_at else 0 end as mst_err_at,
					case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_err_at else 0 end as mst_err_ai,
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
					case when (mst_type2 = 'wc') then mst_err_smt else 0 end as mst_err_smt,
					case when (mst_type2 = 'wcs') then mst_err_smt else 0 end as mst_err_smt_sms,
					case when (mst_type2 = 'wcm') then mst_err_smt else 0 end as mst_err_smt_mms,
                    CASE WHEN (mst_type2 = 'rcs') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_sms,
                    CASE WHEN (mst_type2 = 'rc') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs,
                    CASE WHEN (mst_type2 = 'rcm') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_mms,
                    CASE WHEN (mst_type2 = 'rct') THEN mst_err_rcs ELSE 0 END AS mst_err_rcs_tpl,
                    CASE WHEN NOT (mst_type2 = 'rcs' OR mst_type2 = 'rc' OR mst_type2 = 'rcm' OR mst_type2 = 'rct')
                        THEN
                            mst_wait
                        ELSE
                            0  -- rsc + 문자 대기값을 모두 rcs 쪽으로 보내기.
                            -- mst_wait
                    END AS mst_wait,
                    CASE WHEN (mst_type2 = 'rcs' OR mst_type2 = 'rc' OR mst_type2 = 'rcm' OR mst_type2 = 'rct')
                        THEN
                            -- rsc + 문자 모두 대기값을 rcs 대기값으로 설정.
                            mst_wait
                            -- (CASE WHEN mst_type3 IS NULL THEN mst_wai ELSE 0 END)
                        ELSE
                            0
                    END AS mst_rcs_wait
				from cb_wt_msg_sent where mst_mem_id in (";

            if ($option == "S") {
                $sql .= " select mem_id from igenie.cb_member where mem_id = '".$this->member->item("mem_id")."' OR mem_sales_mng_id = ".$sales_id." ";
            } else if ($option == "M") {
                $sql .= " select mem_id from igenie.cb_member where mem_id = '".$this->member->item("mem_id")."' OR mem_management_mng_id = ".$sales_id." ";
            } else if ($option == "O") {
                $sql .= " select mem_id from igenie.cb_member where mem_id = '".$this->member->item("mem_id")."' OR (mem_management_mng_id = ".$sales_id." AND mem_sales_mng_id <> mem_management_mng_id) ";
            } else {
                $sql .= " select mem_id from igenie.cb_member where mem_id = '".$this->member->item("mem_id")."' OR mem_management_mng_id = ".$sales_id." OR mem_sales_mng_id = ".$sales_id." ";
            }

            $sql .= "
                    ) ".$where.") temp group by mst_datetime order by 1 desc";

            //             $sql = "select
            // 					substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, ".(($kind=="day") ? "10" : "7").") mst_datetime,
            // 					sum(mst_qty) mst_qty,
            // 					sum(mst_ft) mst_ft,
            // 					sum(mst_ft_img) mst_ft_img,
            // 					sum(mst_at) mst_at,
            // 					sum(mst_sms) mst_sms,
            // 					sum(mst_lms) mst_lms,
            // 					sum(mst_mms) mst_mms,
            //                     sum(mst_grs) mst_grs,
            //                     sum(mst_grs_sms) mst_grs_sms,
            //                     sum(mst_nas) mst_nas,
            //                     sum(mst_nas_sms) mst_nas_sms,
            //                     sum(mst_015) mst_015,
            // 					sum(mst_phn) mst_phn,
            // 					sum(mst_err_at) err_at,
            // 					sum(mst_err_ft) err_ft,
            // 					sum(mst_err_ft_img) err_ft_img,
            // 					sum(mst_err_sms) qty_sms,
            // 					sum(mst_err_lms) qty_lms,
            // 					sum(mst_err_mms) qty_mms,
            //                     sum(mst_err_grs) qty_grs,
            //                     sum(mst_err_grs_sms) qty_grs_sms,
            //                     sum(mst_err_nas) qty_nas,
            //                     sum(mst_err_nas_sms) qty_nas_sms,
            //                     sum(mst_err_015) qty_015,
            // 					sum(mst_err_phn) qty_phn
            // 				from cb_wt_msg_sent where (mst_mem_id in (
            //                 					WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            //                     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            //                     	FROM cb_member_register cmr
            //                     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            //                     	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
            //                     	UNION ALL
            //                     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            //                     	FROM cb_member_register cmr
            //                     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            //                     	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            //                     )
            //                     SELECT distinct mem_id
            //                     FROM cmem
            //                     ) or mst_mem_id = ".$this->member->item("mem_id").")    ".$where." group by substr((case when mst_reserved_dt <> '00000000000000' then STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') else  mst_datetime end), 1, ".(($kind=="day") ? "10" : "7").") order by 1 desc";
            //log_message('error', 'Query = '.$sql);
            /*			$sql = "select
             substr(mst_datetime, 1, ".(($kind=="day") ? "10" : "7").") mst_datetime,
             sum(mst_qty) mst_qty,
             sum(mst_ft) mst_ft,
             sum(mst_ft_img) mst_ft_img,
             sum(mst_at) mst_at,
             sum(mst_phn) mst_phn,
             sum(mst_sms) mst_sms,
             sum(mst_lms) mst_lms,
             sum(mst_mms) mst_mms
             from cb_wt_msg_sent where mst_profile in (
             select a.spf_key from cb_wt_send_profile a inner join
             (select  mem_id, mrg_recommend_mem_id
             from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
             (select @pv := ".$this->mem_id.") initialisation
             where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
             union select '".$this->mem_id."' mem_id, '".$this->mem_id."' mrg_recommend_mem_id
             ) b on a.spf_mem_id = b.mem_id
             where a.spf_use='Y' and a.spf_key<>''
             ) ".$where." group by substr(mst_datetime, 1, ".(($kind=="day") ? "10" : "7").") order by 1 desc";*/
        }
        // log_message('error', 'Query222 = '.$sql);
        return $this->db->query($sql, $param)->result();
    }

    function get_daily_report_new($Data)
    {
        // from 절 첫번째 sub query 조건
        $sub_where1 = "";
        // from 절 두번째 sub query 조건
        $sub_where2 = "";
        // from 절 join 형태 : LEFT JOIN -> 미발송 업체 포함, RIGHT JOIN -> 미발송 업체 미포함
        $join_str = "";
        // 정렬 문자열
        $orderby_str = "";

        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['startDate'] : ". $Data['startDate']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['endDate'] : ". $Data['endDate']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['pf_key'] : ". $Data['pf_key']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['branch'] : ". $Data['branch']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['salesperson'] : ". $Data['salesperson']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['zero_include'] : ". $Data['zero_include']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['parent_id'] : ". $Data['parent_id']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['parent_name'] : ". $Data['parent_name']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['orderby'] : ". $Data['orderby']);

        if($Data['orderby'] && $Data['orderby'] == "1") {   // 관리업체명, 업체명
            $orderby_str = "ORDER BY sa.parent_name, sa.mem_username";
        } else if ($Data['orderby'] && $Data['orderby'] == "2") {   // 지점, 영업자
            $orderby_str = "ORDER BY sa.dbo_name, sa.ds_name";
        } else if ($Data['orderby'] && $Data['orderby'] == "3") {   // 발송수량
            $orderby_str = "ORDER BY sb.mst_qty DESC";
        }

        if($Data['pf_key'] && $Data['pf_key'] != 'ALL') {
            //$where .=  " and mst_profile='".$Data['pf_key']."' ";
            $sub_where1 .= "        WHERE   acb.mem_id='".$Data['pf_key']."'
";
            $sub_where2 .= "                  WHERE b.mst_mem_id='".$Data['pf_key']."'
";
        } else {
            $sub_where2 .= "                  WHERE b.mst_mem_id in (
                            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            WHERE cm.mem_id = '".$this->member->item('mem_id')."' AND cmr.mem_id <> '".$this->member->item('mem_id')."'
                            UNION ALL
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                            SELECT distinct mem_id
                            FROM cmem
                            UNION ALL
                            SELECT ".$this->member->item('mem_id')." mem_id)
";
        }

        if($Data['branch'] && $Data['branch'] != 'ALL') {
            if ($sub_where1 == "") {
                $sub_where1 .= "        WHERE   acb.mem_sale_point_id in (".$Data['branch'].")
";
            } else {
                $sub_where1 .= "                AND acb.mem_sale_point_id in (".$Data['branch'].")
";
            }
        }

        if($Data['salesperson'] && $Data['salesperson'] != 'ALL') {
            if ($sub_where1 == "") {
                $sub_where1 .= "        WHERE   acb.mem_sales_mng_id in (".$Data['salesperson'].")
";
            } else {
                $sub_where1 .= "                AND acb.mem_sales_mng_id in (".$Data['salesperson'].")
";
            }
        }

        if($Data['startDate']) {
            $sub_where2 .= "                        AND (( b.mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(b.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$Data['startDate']." 00:00:00' AND '".$Data['endDate']." 23:59:59'))
                                OR (b.mst_reserved_dt = '00000000000000' AND (b.mst_datetime BETWEEN  '".$Data['startDate']." 00:00:00' AND '".$Data['endDate']." 23:59:59')))";
            //             $sub_where2 .= " AND (
            // 		                	 ( mst_reserved_dt <> '00000000000000' AND
            //                               (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$Data['startDate']." 00:00:00"."' AND '".$Data['startDate']." 23:59:59"."')
            //                              ) OR
            //                              ( mst_reserved_dt = '00000000000000' AND
            //                               (mst_datetime BETWEEN  '".$Data['startDate']." 00:00:00"."' AND '".$Data['startDate']." 23:59:59"."')
            //                              )
            //                             )";
        }

        if($Data['zero_include'] == "YES") { // 미발송업체 포함
            $join_str = "        LEFT JOIN -- 미발송 업체 포함
";
        } else { // 미발송업체 미포함
            // $join_str = "        RIGHT JOIN -- 미발송 업체 미포함";
            $join_str = "        INNER JOIN -- 미발송 업체 미포함
";
        }

        // query문 생성
        $sql = "SELECT  mem_id,
        mem_userid,
        mem_username,
        parent_mem_id,
        parent_name,
        dbo_name,
        ds_name,
        ds_position,
        mst_id,
        ifnull(mst_qty, 0) AS mst_qty,
        ifnull(reserved_qty, 0) AS reserved_qty,
        ifnull(mst_ft, 0) AS mst_ft,
        ifnull(mst_ft_img, 0) AS mst_ft_img,
        ifnull(mst_ft_wide_img, 0) AS mst_ft_wide_img,
        ifnull(mst_at, 0) AS mst_at,
        ifnull(mst_ai, 0) AS mst_ai,
        ifnull(mst_sms, 0) AS mst_sms,
        ifnull(mst_lms, 0) AS mst_lms,
        ifnull(mst_mms, 0) AS mst_mms,
        ifnull(mst_grs, 0) AS mst_grs,
        ifnull(mst_grs_sms, 0) AS mst_grs_sms,
        ifnull(mst_grs_biz_qty, 0) AS mst_grs_biz_qty,
        ifnull(mst_nas, 0) AS mst_nas,
        ifnull(mst_nas_sms, 0) AS mst_nas_sms,
        ifnull(mst_015, 0) AS mst_015,
        ifnull(mst_phn, 0) AS mst_phn,
        ifnull(mst_grs_mms, 0) AS mst_grs_mms,
        ifnull(mst_nas_mms, 0) AS mst_nas_mms,
        ifnull(mst_smt, 0) AS mst_smt,
        ifnull(mst_smt_sms, 0) AS mst_smt_sms,
        ifnull(mst_smt_mms, 0) AS mst_smt_mms,
        ifnull(mst_rcs_sms, 0) AS mst_rcs_sms,
        ifnull(mst_rcs, 0) AS mst_rcs,
        ifnull(mst_rcs_mms, 0) AS mst_rcs_mms,
        ifnull(mst_rcs_tpl, 0) AS mst_rcs_tpl,
        ifnull(err_at, 0) AS err_at,
        ifnull(err_ai, 0) AS err_ai,
        ifnull(err_ft, 0) AS err_ft,
        ifnull(err_ft_img, 0) AS err_ft_img,
        ifnull(err_ft_wide_img, 0) AS err_ft_wide_img,
        ifnull(qty_sms, 0) AS qty_sms,
        ifnull(qty_lms, 0) AS qty_lms,
        ifnull(qty_mms, 0) AS qty_mms,
        ifnull(qty_grs, 0) AS qty_grs,
        ifnull(qty_grs_sms, 0) AS qty_grs_sms,
        ifnull(qty_nas, 0) AS qty_nas,
        ifnull(qty_nas_sms, 0) AS qty_nas_sms,
        ifnull(qty_015, 0) AS qty_015,
        ifnull(qty_phn, 0) AS qty_phn,
        ifnull(qty_grs_mms, 0) AS qty_grs_mms,
        ifnull(qty_nas_mms, 0) AS qty_nas_mms,
        ifnull(qty_smt, 0) AS qty_smt,
        ifnull(qty_smt_sms, 0) AS qty_smt_sms,
        ifnull(qty_smt_mms, 0) AS qty_smt_mms,
        ifnull(qty_rcs_sms, 0) AS qty_rcs_sms,
        ifnull(qty_rcs, 0) AS qty_rcs,
        ifnull(qty_rcs_mms, 0) AS qty_rcs_mms,
        ifnull(qty_rcs_tpl, 0) AS qty_rcs_tpl,
        ifnull(qty_wait, 0) AS qty_wait,
        ifnull(qty_rcs_wait, 0) AS qty_rcs_wait
FROM    (SELECT acb.mem_id, acb.mem_userid, acb.mem_username,
                (SELECT dbo_name FROM dhn_branch_office2 WHERE dbo_id = acb.mem_sale_point_id AND dbo_part='BO') AS dbo_name,
                (SELECT ds_name FROM dhn_salesperson2 WHERE ds_id = acb.mem_sales_mng_id) AS ds_name,
                (SELECT CASE ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장' WHEN 'MA' THEN '과장'
                        WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END
                FROM    dhn_salesperson2 WHERE ds_id = acb.mem_sales_mng_id) AS ds_position,
                rec.parent_id AS parent_mem_id,
                rec.parent_name AS parent_name
        FROM    cb_member acb INNER JOIN
                (WITH RECURSIVE cmem(mem_id, parent_id, parent_name) AS (
                    SELECT  cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    FROM    cb_member_register cmr
                    JOIN    cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    WHERE cm.mem_id = '".$this->member->item('mem_id')."' AND cmr.mem_id <> '".$this->member->item('mem_id')."'
                    UNION ALL
                    SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    FROM cb_member_register cmr
                    JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                    SELECT distinct mem_id, parent_id, parent_name
                    FROM cmem
                    UNION ALL
                    SELECT ".$this->member->item('mem_id')." mem_id, ".$Data['parent_id']." parent_id, '".$Data['parent_name']."' parent_name) AS rec
                        ON rec.mem_id = acb.mem_id
";
        $sql .= $sub_where1."        ) AS sa
";
        $sql .= $join_str."        (SELECT mst_mem_id,
                group_concat(mst_id) AS mst_id,
                sum(mst_qty) AS mst_qty,
                sum(reserved_qty) AS reserved_qty,
                sum(mst_ft) AS mst_ft,
                sum(mst_ft_img) AS mst_ft_img,
                sum(mst_ft_wide_img) AS mst_ft_wide_img,
                sum(mst_at) AS mst_at,
                sum(mst_ai) AS mst_ai,
                sum(mst_sms) AS mst_sms,
                sum(mst_lms) AS mst_lms,
                sum(mst_mms) AS mst_mms,
                sum(mst_grs) AS mst_grs,
                sum(mst_grs_sms) AS mst_grs_sms,
                sum(mst_grs_biz_qty) AS mst_grs_biz_qty,
                sum(mst_nas) AS mst_nas,
                sum(mst_nas_sms) AS mst_nas_sms,
                sum(mst_015) AS mst_015,
                sum(mst_phn) AS mst_phn,
                sum(mst_grs_mms) AS mst_grs_mms,
                sum(mst_nas_mms) AS mst_nas_mms,
                sum(mst_smt) AS mst_smt,
                sum(mst_smt_sms) AS mst_smt_sms,
                sum(mst_smt_mms) AS mst_smt_mms,
                sum(mst_rcs_sms) AS mst_rcs_sms,
                sum(mst_rcs) AS mst_rcs,
                sum(mst_rcs_mms) AS mst_rcs_mms,
                sum(mst_rcs_tpl) AS mst_rcs_tpl,
                sum(mst_err_at) AS err_at,
                sum(mst_err_ai) AS err_ai,
                sum(mst_err_ft) AS err_ft,
                sum(mst_err_ft_img) AS err_ft_img,
                sum(mst_err_ft_wide_img) AS err_ft_wide_img,
                sum(mst_err_sms) AS qty_sms,
                sum(mst_err_lms) AS qty_lms,
                sum(mst_err_mms) AS qty_mms,
                sum(mst_err_grs) AS qty_grs,
                sum(mst_err_grs_sms) AS qty_grs_sms,
                sum(mst_err_nas) AS qty_nas,
                sum(mst_err_nas_sms) AS qty_nas_sms,
                sum(mst_err_015) AS qty_015,
                sum(mst_err_phn) AS qty_phn,
                sum(mst_err_grs_mms) AS qty_grs_mms,
                sum(mst_err_nas_mms) AS qty_nas_mms,
                sum(mst_err_smt) AS qty_smt,
                sum(mst_err_smt_sms) AS qty_smt_sms,
                sum(mst_err_smt_mms) AS qty_smt_mms,
                sum(mst_err_rcs_sms) AS qty_rcs_sms,
                sum(mst_err_rcs) AS qty_rcs,
                sum(mst_err_rcs_mms) AS qty_rcs_mms,
                sum(mst_err_rcs_tpl) AS qty_rcs_tpl,
                sum(mst_wait) AS qty_wait,
                sum(mst_rcs_wait) AS qty_rcs_wait
        FROM (SELECT
                    b.mst_id,
                    b.mst_mem_id,
                    b.mst_qty,
                    (CASE WHEN b.mst_reserved_dt <> '00000000000000' AND b.mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') THEN b.mst_qty ELSE  0 END) reserved_qty,
                    b.mst_ft,
                    CASE WHEN (b.mst_type1 = 'fti') THEN b.mst_ft_img ELSE 0 END AS mst_ft_img,
                    CASE WHEN (b.mst_type1 = 'ftw') THEN b.mst_ft_img ELSE 0 END AS mst_ft_wide_img,
                    CASE WHEN (b.mst_type1 = 'at' or b.mst_type2 = 'at') THEN mst_at ELSE 0 END AS mst_at,
                    CASE WHEN (b.mst_type1 = 'ai' or b.mst_type2 = 'ai') THEN mst_at ELSE 0 END AS mst_ai,
                    b.mst_sms,
                    b.mst_lms,
                    b.mst_mms,
                    CASE WHEN (b.mst_type2 = 'wa' OR b.mst_type3 = 'wa') THEN (CASE WHEN (b.mst_type3 = 'wa') THEN b.mst_grs ELSE (b.mst_grs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs,
                    CASE WHEN (b.mst_type2 = 'was' OR b.mst_type3 = 'was') THEN (CASE WHEN (b.mst_type3 = 'was') THEN b.mst_grs_sms ELSE (b.mst_grs_sms + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs_sms,
                    b.mst_grs_biz_qty,
                    CASE WHEN (b.mst_type2 = 'wb' OR b.mst_type3 = 'wb') THEN (CASE WHEN (b.mst_type3 = 'wb') THEN b.mst_nas ELSE (b.mst_nas + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas,
                    CASE WHEN (b.mst_type2 = 'wbs' OR b.mst_type3 = 'wbs') THEN (CASE WHEN (b.mst_type3 = 'wbs') THEN b.mst_nas_sms ELSE (b.mst_nas_sms + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas_sms,
                    b.mst_015,
                    b.mst_phn,
                    CASE WHEN (b.mst_type2 = 'wam' OR b.mst_type3 = 'wam') THEN (CASE WHEN (b.mst_type3 = 'wam') THEN b.mst_grs ELSE (b.mst_grs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs_mms,
                    CASE WHEN (b.mst_type2 = 'wbm' OR b.mst_type3 = 'wbm') THEN (CASE WHEN (b.mst_type3 = 'wbm') THEN b.mst_nas ELSE (b.mst_nas + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas_mms,
                    CASE WHEN (b.mst_type2 = 'wc' OR b.mst_type3 = 'wc') THEN (CASE WHEN (b.mst_type3 = 'wc') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt,
                    CASE WHEN (b.mst_type2 = 'wcs' OR b.mst_type3 = 'wcs') THEN (CASE WHEN (b.mst_type3 = 'wcs') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt_sms,
                    CASE WHEN (b.mst_type2 = 'wcm' OR b.mst_type3 = 'wcm') THEN (CASE WHEN (b.mst_type3 = 'wcm') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt_mms,
                    -- RCS
                    CASE WHEN (b.mst_type2 = 'rcs') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_sms,
                    CASE WHEN (b.mst_type2 = 'rc') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs,
                    CASE WHEN (b.mst_type2 = 'rcm') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_mms,
                    CASE WHEN (b.mst_type2 = 'rct') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_tpl,

                    CASE WHEN (b.mst_type1 = 'at' or b.mst_type2 = 'at') THEN b.mst_err_at ELSE 0 END AS mst_err_at,
                    CASE WHEN (b.mst_type1 = 'ai' or b.mst_type2 = 'ai') THEN b.mst_err_at ELSE 0 END AS mst_err_ai,
                    b.mst_err_ft,
                    CASE WHEN (b.mst_type1 = 'fti') THEN b.mst_err_ft_img ELSE 0 END AS mst_err_ft_img,
                    CASE WHEN (b.mst_type1 = 'ftw') THEN b.mst_err_ft_img ELSE 0 END AS mst_err_ft_wide_img,
                    b.mst_err_sms,
                    b.mst_err_lms,
                    b.mst_err_mms,
                    CASE WHEN (b.mst_type2 = 'wa') THEN b.mst_err_grs ELSE 0 END AS mst_err_grs,
                    b.mst_err_grs_sms,
                    CASE WHEN (b.mst_type2 = 'wb') THEN b.mst_err_nas ELSE 0 END AS mst_err_nas,
                    b.mst_err_nas_sms,
                    b.mst_err_015,
                    b.mst_err_phn,
                    CASE WHEN (b.mst_type2 = 'wam') THEN b.mst_err_grs ELSE 0 END AS mst_err_grs_mms,
                    CASE WHEN (b.mst_type2 = 'wbm') THEN b.mst_err_nas ELSE 0 END AS mst_err_nas_mms,
                    CASE WHEN (b.mst_type2 = 'wc') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt,
                    CASE WHEN (b.mst_type2 = 'wcs') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt_sms,
                    CASE WHEN (b.mst_type2 = 'wcm') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt_mms,
                    -- RCS
                    CASE WHEN (b.mst_type2 = 'rcs') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_sms,
                    CASE WHEN (b.mst_type2 = 'rc') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs,
                    CASE WHEN (b.mst_type2 = 'rcm') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_mms,
                    CASE WHEN (b.mst_type2 = 'rct') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_tpl,
                    -- CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END mst_wait,
                    CASE WHEN NOT (b.mst_type2 = 'rcs' OR b.mst_type2 = 'rc' OR b.mst_type2 = 'rcm' OR b.mst_type2 = 'rct')
                        THEN
                            (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END)
                        ELSE
                            0  -- rsc + 문자 대기값을 모두 rcs 쪽으로 보내기.
                            -- (CASE WHEN b.mst_type3 IS NOT NULL THEN (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END)
                    END AS mst_wait,
                    CASE WHEN (b.mst_type2 = 'rcs' OR b.mst_type2 = 'rc' OR b.mst_type2 = 'rcm' OR b.mst_type2 = 'rct')
                        THEN
                            -- rsc + 문자 모두 대기값을 rcs 대기값으로 설정.
                            (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END)
                            -- (CASE WHEN b.mst_type3 IS NULL THEN (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END)
                        ELSE
                            0
                    END AS mst_rcs_wait
            FROM (SELECT *
                  FROM cb_wt_msg_sent b
";
        $sql .= $sub_where2.") AS b ) AS total
        GROUP BY mst_mem_id) AS sb ON sa.mem_id = sb.mst_mem_id
";
        $sql .= $orderby_str;

        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ". $sql);
        // log_message("ERROR", "SQL : ".$sql);
        return $this->db->query($sql )->result();
    }

    function get_sales_daily_report_new($Data)
    {
        // from 절 첫번째 sub query 조건
        $sub_where1 = "";
        // from 절 두번째 sub query 조건
        $sub_where2 = "";
        // from 절 join 형태 : LEFT JOIN -> 미발송 업체 포함, RIGHT JOIN -> 미발송 업체 미포함
        $join_str = "";
        // 정렬 문자열
        $orderby_str = "";

        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['startDate'] : ". $Data['startDate']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['endDate'] : ". $Data['endDate']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['pf_key'] : ". $Data['pf_key']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['branch'] : ". $Data['branch']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['salesperson'] : ". $Data['salesperson']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['zero_include'] : ". $Data['zero_include']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['parent_id'] : ". $Data['parent_id']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['parent_name'] : ". $Data['parent_name']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['orderby'] : ". $Data['orderby']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['sales_id'] : ". $Data['sales_id']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['managerType'] : ". $Data['managerType']);   // option

        if($Data['orderby'] && $Data['orderby'] == "1") {   // 관리업체명, 업체명
            $orderby_str = "ORDER BY sa.parent_name, sa.mem_username";
        } else if ($Data['orderby'] && $Data['orderby'] == "2") {   // 지점, 영업자
            $orderby_str = "ORDER BY sa.dbo_name, sa.ds_name";
        } else if ($Data['orderby'] && $Data['orderby'] == "3") {   // 발송수량
            $orderby_str = "ORDER BY sb.mst_qty DESC";
        }

        if($Data['pf_key'] && $Data['pf_key'] != 'ALL') {
            //$where .=  " and mst_profile='".$Data['pf_key']."' ";
            $sub_where1 .= "        WHERE   acb.mem_id='".$Data['pf_key']."'
";
            $sub_where2 .= "                  WHERE b.mst_mem_id='".$Data['pf_key']."'
";
        } else {
            //             $sub_where2 .= "                  WHERE b.mst_mem_id in (
            //                             WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            //                             SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            //                             FROM cb_member_register cmr
            //                             JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            //                             WHERE cm.mem_id = '".$this->member->item('mem_id')."' AND cmr.mem_id <> '".$this->member->item('mem_id')."'
            //                             UNION ALL
            //                             SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            //                             FROM cb_member_register cmr
            //                             JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            //                             JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
            //                             SELECT distinct mem_id
            //                             FROM cmem
            //                             UNION ALL
            //                             SELECT ".$this->member->item('mem_id')." mem_id)
            // ";
            if ($Data['managerType'] == "S") {
                $sub_where2 .= "                  WHERE b.mst_mem_id in (
                                SELECT mem_id FROM cb_member WHERE mem_id = '".$this->member->item("mem_id")."' OR mem_sales_mng_id = ".$Data['sales_id'].") ";
            } else if ($Data['managerType'] == "M") {
                $sub_where2 .= "                  WHERE b.mst_mem_id in (
                                SELECT mem_id FROM cb_member WHERE mem_id = '".$this->member->item("mem_id")."' OR mem_management_mng_id = ".$Data['sales_id'].") ";
            } else if ($Data['managerType'] == "O") {
                $sub_where2 .= "                  WHERE b.mst_mem_id in (
                                SELECT mem_id FROM cb_member WHERE mem_id = '".$this->member->item("mem_id")."' OR (mem_management_mng_id = ".$Data['sales_id']." AND mem_sales_mng_id <> mem_management_mng_id)) ";
            } else {
                $sub_where2 .= "                  WHERE b.mst_mem_id in (
                                SELECT mem_id FROM cb_member WHERE mem_id = '".$this->member->item("mem_id")."' OR mem_management_mng_id = ".$Data['sales_id']." OR mem_sales_mng_id = ".$Data['sales_id'].") ";
            }
        }

        if($Data['branch'] && $Data['branch'] != 'ALL') {
            if ($sub_where1 == "") {
                $sub_where1 .= "        WHERE   acb.mem_sale_point_id in (".$Data['branch'].")
";
            } else {
                $sub_where1 .= "                AND acb.mem_sale_point_id in (".$Data['branch'].")
";
            }
        }

        if($Data['salesperson'] && $Data['salesperson'] != 'ALL') {
            if ($sub_where1 == "") {
                $sub_where1 .= "        WHERE   acb.mem_sales_mng_id in (".$Data['salesperson'].")
";
            } else {
                $sub_where1 .= "                AND acb.mem_sales_mng_id in (".$Data['salesperson'].")
";
            }
        }

        if($Data['startDate']) {
            $sub_where2 .= "                        AND (( b.mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(b.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$Data['startDate']." 00:00:00' AND '".$Data['endDate']." 23:59:59'))
                                OR (b.mst_reserved_dt = '00000000000000' AND (b.mst_datetime BETWEEN  '".$Data['startDate']." 00:00:00' AND '".$Data['endDate']." 23:59:59')))";
            //             $sub_where2 .= " AND (
            // 		                	 ( mst_reserved_dt <> '00000000000000' AND
            //                               (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$Data['startDate']." 00:00:00"."' AND '".$Data['startDate']." 23:59:59"."')
            //                              ) OR
            //                              ( mst_reserved_dt = '00000000000000' AND
            //                               (mst_datetime BETWEEN  '".$Data['startDate']." 00:00:00"."' AND '".$Data['startDate']." 23:59:59"."')
            //                              )
            //                             )";
        }

        if($Data['zero_include'] == "YES") { // 미발송업체 포함
            $join_str = "        LEFT JOIN -- 미발송 업체 포함
";
        } else { // 미발송업체 미포함
            // $join_str = "        RIGHT JOIN -- 미발송 업체 미포함";
            $join_str = "        INNER JOIN -- 미발송 업체 미포함
";
        }

        // query문 생성
        $sql = "select  mem_id,
        mem_userid,
        mem_username,
        parent_mem_id,
        parent_name,
        dbo_name,
        ds_name,
        ds_position,
        mst_id,
        ifnull(mst_qty, 0) AS mst_qty,
        ifnull(reserved_qty, 0) AS reserved_qty,
        ifnull(mst_ft, 0) AS mst_ft,
        ifnull(mst_ft_img, 0) AS mst_ft_img,
        ifnull(mst_ft_wide_img, 0) AS mst_ft_wide_img,
        ifnull(mst_at, 0) AS mst_at,
        ifnull(mst_ai, 0) AS mst_ai,
        ifnull(mst_sms, 0) AS mst_sms,
        ifnull(mst_lms, 0) AS mst_lms,
        ifnull(mst_mms, 0) AS mst_mms,
        ifnull(mst_grs, 0) AS mst_grs,
        ifnull(mst_grs_sms, 0) AS mst_grs_sms,
        ifnull(mst_grs_biz_qty, 0) AS mst_grs_biz_qty,
        ifnull(mst_nas, 0) AS mst_nas,
        ifnull(mst_nas_sms, 0) AS mst_nas_sms,
        ifnull(mst_015, 0) AS mst_015,
        ifnull(mst_phn, 0) AS mst_phn,
        ifnull(mst_grs_mms, 0) AS mst_grs_mms,
        ifnull(mst_nas_mms, 0) AS mst_nas_mms,
        ifnull(mst_smt, 0) AS mst_smt,
        ifnull(mst_smt_sms, 0) AS mst_smt_sms,
        ifnull(mst_smt_mms, 0) AS mst_smt_mms,
        ifnull(mst_rcs_sms, 0) AS mst_rcs_sms,
        ifnull(mst_rcs, 0) AS mst_rcs,
        ifnull(mst_rcs_mms, 0) AS mst_rcs_mms,
        ifnull(mst_rcs_tpl, 0) AS mst_rcs_tpl,
        ifnull(err_at, 0) AS err_at,
        ifnull(err_ai, 0) AS err_ai,
        ifnull(err_ft, 0) AS err_ft,
        ifnull(err_ft_img, 0) AS err_ft_img,
        ifnull(err_ft_wide_img, 0) AS err_ft_wide_img,
        ifnull(qty_sms, 0) AS qty_sms,
        ifnull(qty_lms, 0) AS qty_lms,
        ifnull(qty_mms, 0) AS qty_mms,
        ifnull(qty_grs, 0) AS qty_grs,
        ifnull(qty_grs_sms, 0) AS qty_grs_sms,
        ifnull(qty_nas, 0) AS qty_nas,
        ifnull(qty_nas_sms, 0) AS qty_nas_sms,
        ifnull(qty_015, 0) AS qty_015,
        ifnull(qty_phn, 0) AS qty_phn,
        ifnull(qty_grs_mms, 0) AS qty_grs_mms,
        ifnull(qty_nas_mms, 0) AS qty_nas_mms,
        ifnull(qty_smt, 0) AS qty_smt,
        ifnull(qty_smt_sms, 0) AS qty_smt_sms,
        ifnull(qty_smt_mms, 0) AS qty_smt_mms,
        ifnull(qty_rcs_sms, 0) AS qty_rcs_sms,
        ifnull(qty_rcs, 0) AS qty_rcs,
        ifnull(qty_rcs_mms, 0) AS qty_rcs_mms,
        ifnull(qty_rcs_tpl, 0) AS qty_rcs_tpl,
        ifnull(qty_wait, 0) AS qty_wait,
        ifnull(qty_rcs_wait, 0) AS qty_rcs_wait
FROM    (SELECT acb.mem_id, acb.mem_userid, acb.mem_username,
                (SELECT dbo_name FROM dhn_branch_office2 WHERE dbo_id = acb.mem_sale_point_id AND dbo_part='BO') AS dbo_name,
                (SELECT ds_name FROM dhn_salesperson2 WHERE ds_id = acb.mem_sales_mng_id) AS ds_name,
                (SELECT CASE ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장' WHEN 'MA' THEN '과장'
                        WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END
                FROM    dhn_salesperson2 WHERE ds_id = acb.mem_sales_mng_id) AS ds_position,
                rec.parent_id AS parent_mem_id,
                rec.parent_name AS parent_name
        FROM    cb_member acb INNER JOIN ";

        if ($Data['managerType'] == "S") {
            $sql .= "
                (SELECT mem_id, ".$this->member->item("mem_id")." parent_id, (SELECT mem_username FROM cb_member WHERE mem_id = ".$this->member->item("mem_id").") parent_name
                 FROM cb_member WHERE mem_sales_mng_id = ".$Data['sales_id'];
        } else if ($Data['managerType'] == "M") {
            $sql .= "
                (SELECT mem_id, ".$this->member->item("mem_id")." parent_id, (SELECT mem_username FROM cb_member WHERE mem_id = ".$this->member->item("mem_id").") parent_name
                 FROM cb_member WHERE mem_management_mng_id = ".$Data['sales_id'];
        } else if ($Data['managerType'] == "O") {
            $sql .= "
                (SELECT mem_id, ".$this->member->item("mem_id")." parent_id, (SELECT mem_username FROM cb_member WHERE mem_id = ".$this->member->item("mem_id").") parent_name
                 FROM cb_member WHERE mem_management_mng_id = ".$Data['sales_id']. " AND mem_sales_mng_id <> mem_management_mng_id ";
        } else {
            $sql .= "
                (SELECT mem_id, ".$this->member->item("mem_id")." parent_id, (SELECT mem_username FROM cb_member WHERE mem_id = ".$this->member->item("mem_id").") parent_name
                 FROM cb_member WHERE mem_management_mng_id = ".$Data['sales_id']." OR mem_sales_mng_id = ".$Data['sales_id'];
        }

        $sql .= "                 UNION ALL
                 SELECT ".$this->member->item("mem_id")." mem_id, ".$Data['parent_id']." parent_id, '".$Data['parent_name']."' parent_name) AS rec
                     ON rec.mem_id = acb.mem_id ";
        //         $sql .=
        //                 (WITH RECURSIVE cmem(mem_id, parent_id, parent_name) AS (
        //                     SELECT  cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //                     FROM    cb_member_register cmr
        //                     JOIN    cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //                     WHERE cm.mem_id = '".$this->member->item('mem_id')."' AND cmr.mem_id <> '".$this->member->item('mem_id')."'
        //                     UNION ALL
        //                     SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //                     FROM cb_member_register cmr
        //                     JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //                     JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
        //                     SELECT distinct mem_id, parent_id, parent_name
        //                     FROM cmem
        //                     UNION ALL
        //                     SELECT ".$this->member->item('mem_id')." mem_id, ".$Data['parent_id']." parent_id, '".$Data['parent_name']."' parent_name) AS rec
        //                         ON rec.mem_id = acb.mem_id
        // ";
        $sql .= $sub_where1."        ) AS sa
";
        $sql .= $join_str."        (SELECT mst_mem_id,
                group_concat(mst_id) AS mst_id,
                sum(mst_qty) AS mst_qty,
                sum(reserved_qty) AS reserved_qty,
                sum(mst_ft) AS mst_ft,
                sum(mst_ft_img) AS mst_ft_img,
                sum(mst_ft_wide_img) AS mst_ft_wide_img,
                sum(mst_at) AS mst_at,
                sum(mst_ai) AS mst_ai,
                sum(mst_sms) AS mst_sms,
                sum(mst_lms) AS mst_lms,
                sum(mst_mms) AS mst_mms,
                sum(mst_grs) AS mst_grs,
                sum(mst_grs_sms) AS mst_grs_sms,
                sum(mst_grs_biz_qty) AS mst_grs_biz_qty,
                sum(mst_nas) AS mst_nas,
                sum(mst_nas_sms) AS mst_nas_sms,
                sum(mst_015) AS mst_015,
                sum(mst_phn) AS mst_phn,
                sum(mst_grs_mms) AS mst_grs_mms,
                sum(mst_nas_mms) AS mst_nas_mms,
                sum(mst_smt) AS mst_smt,
                sum(mst_smt_sms) AS mst_smt_sms,
                sum(mst_smt_mms) AS mst_smt_mms,
                sum(mst_rcs_sms) AS mst_rcs_sms,
                sum(mst_rcs) AS mst_rcs,
                sum(mst_rcs_mms) AS mst_rcs_mms,
                sum(mst_rcs_tpl) AS mst_rcs_tpl,
                sum(mst_err_at) AS err_at,
                sum(mst_err_ai) AS err_ai,
                sum(mst_err_ft) AS err_ft,
                sum(mst_err_ft_img) AS err_ft_img,
                sum(mst_err_ft_wide_img) AS err_ft_wide_img,
                sum(mst_err_sms) AS qty_sms,
                sum(mst_err_lms) AS qty_lms,
                sum(mst_err_mms) AS qty_mms,
                sum(mst_err_grs) AS qty_grs,
                sum(mst_err_grs_sms) AS qty_grs_sms,
                sum(mst_err_nas) AS qty_nas,
                sum(mst_err_nas_sms) AS qty_nas_sms,
                sum(mst_err_015) AS qty_015,
                sum(mst_err_phn) AS qty_phn,
                sum(mst_err_grs_mms) AS qty_grs_mms,
                sum(mst_err_nas_mms) AS qty_nas_mms,
                sum(mst_err_smt) AS qty_smt,
                sum(mst_err_smt_sms) AS qty_smt_sms,
                sum(mst_err_smt_mms) AS qty_smt_mms,
                sum(mst_err_rcs_sms) AS qty_rcs_sms,
                sum(mst_err_rcs) AS qty_rcs,
                sum(mst_err_rcs_mms) AS qty_rcs_mms,
                sum(mst_err_rcs_tpl) AS qty_rcs_tpl,
                sum(mst_wait) AS qty_wait,
                sum(mst_rcs_wait) AS qty_rcs_wait
        FROM (SELECT
                    b.mst_id,
                    b.mst_mem_id,
                    b.mst_qty,
                    (CASE WHEN b.mst_reserved_dt <> '00000000000000' AND b.mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') THEN b.mst_qty ELSE  0 END) reserved_qty,
                    b.mst_ft,
                    CASE WHEN (b.mst_type1 = 'fti') THEN b.mst_ft_img ELSE 0 END AS mst_ft_img,
                    CASE WHEN (b.mst_type1 = 'ftw') THEN b.mst_ft_img ELSE 0 END AS mst_ft_wide_img,
                    CASE WHEN (b.mst_type1 = 'at' or b.mst_type2 = 'at') THEN mst_at ELSE 0 END AS mst_at,
                    CASE WHEN (b.mst_type1 = 'ai' or b.mst_type2 = 'ai') THEN mst_at ELSE 0 END AS mst_ai,
                    b.mst_sms,
                    b.mst_lms,
                    b.mst_mms,
                    CASE WHEN (b.mst_type2 = 'wa' OR b.mst_type3 = 'wa') THEN (CASE WHEN (b.mst_type3 = 'wa') THEN b.mst_grs ELSE (b.mst_grs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs,
                    CASE WHEN (b.mst_type2 = 'was' OR b.mst_type3 = 'was') THEN (CASE WHEN (b.mst_type3 = 'was') THEN b.mst_grs_sms ELSE (b.mst_grs_sms + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs_sms,
                    b.mst_grs_biz_qty,
                    CASE WHEN (b.mst_type2 = 'wb' OR b.mst_type3 = 'wb') THEN (CASE WHEN (b.mst_type3 = 'wb') THEN b.mst_nas ELSE (b.mst_nas + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas,
                    CASE WHEN (b.mst_type2 = 'wbs' OR b.mst_type3 = 'wbs') THEN (CASE WHEN (b.mst_type3 = 'wbs') THEN b.mst_nas_sms ELSE (b.mst_nas_sms + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas_sms,
                    b.mst_015,
                    b.mst_phn,
                    CASE WHEN (b.mst_type2 = 'wam' OR b.mst_type3 = 'wam') THEN (CASE WHEN (b.mst_type3 = 'wam') THEN b.mst_grs ELSE (b.mst_grs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs_mms,
                    CASE WHEN (b.mst_type2 = 'wbm' OR b.mst_type3 = 'wbm') THEN (CASE WHEN (b.mst_type3 = 'wbm') THEN b.mst_nas ELSE (b.mst_nas + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas_mms,
                    CASE WHEN (b.mst_type2 = 'wc' OR b.mst_type3 = 'wc') THEN (CASE WHEN (b.mst_type3 = 'wc') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt,
                    CASE WHEN (b.mst_type2 = 'wcs' OR b.mst_type3 = 'wcs') THEN (CASE WHEN (b.mst_type3 = 'wcs') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt_sms,
                    CASE WHEN (b.mst_type2 = 'wcm' OR b.mst_type3 = 'wcm') THEN (CASE WHEN (b.mst_type3 = 'wcm') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt_mms,
                    -- RCS
                    CASE WHEN (b.mst_type2 = 'rcs') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_sms,
                    CASE WHEN (b.mst_type2 = 'rc') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs,
                    CASE WHEN (b.mst_type2 = 'rcm') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_mms,
                    CASE WHEN (b.mst_type2 = 'rct') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_tpl,

                    CASE WHEN (b.mst_type1 = 'at' or b.mst_type2 = 'at') THEN b.mst_err_at ELSE 0 END AS mst_err_at,
                    CASE WHEN (b.mst_type1 = 'ai' or b.mst_type2 = 'ai') THEN b.mst_err_at ELSE 0 END AS mst_err_ai,
                    b.mst_err_ft,
                    CASE WHEN (b.mst_type1 = 'fti') THEN b.mst_err_ft_img ELSE 0 END AS mst_err_ft_img,
                    CASE WHEN (b.mst_type1 = 'ftw') THEN b.mst_err_ft_img ELSE 0 END AS mst_err_ft_wide_img,
                    b.mst_err_sms,
                    b.mst_err_lms,
                    b.mst_err_mms,
                    CASE WHEN (b.mst_type2 = 'wa') THEN b.mst_err_grs ELSE 0 END AS mst_err_grs,
                    b.mst_err_grs_sms,
                    CASE WHEN (b.mst_type2 = 'wb') THEN b.mst_err_nas ELSE 0 END AS mst_err_nas,
                    b.mst_err_nas_sms,
                    b.mst_err_015,
                    b.mst_err_phn,
                    CASE WHEN (b.mst_type2 = 'wam') THEN b.mst_err_grs ELSE 0 END AS mst_err_grs_mms,
                    CASE WHEN (b.mst_type2 = 'wbm') THEN b.mst_err_nas ELSE 0 END AS mst_err_nas_mms,
                    CASE WHEN (b.mst_type2 = 'wc') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt,
                    CASE WHEN (b.mst_type2 = 'wcs') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt_sms,
                    CASE WHEN (b.mst_type2 = 'wcm') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt_mms,
                    -- RCS
                    CASE WHEN (b.mst_type2 = 'rcs') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_sms,
                    CASE WHEN (b.mst_type2 = 'rc') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs,
                    CASE WHEN (b.mst_type2 = 'rcm') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_mms,
                    CASE WHEN (b.mst_type2 = 'rct') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_tpl,
                    -- CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END mst_wait,
                    CASE WHEN NOT (b.mst_type2 = 'rcs' OR b.mst_type2 = 'rc' OR b.mst_type2 = 'rcm' OR b.mst_type2 = 'rct')
                        THEN
                            (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END)
                        ELSE
                            0  -- rsc + 문자 대기값을 모두 rcs 쪽으로 보내기.
                            -- (CASE WHEN b.mst_type3 IS NOT NULL THEN (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END)
                    END AS mst_wait,
                    CASE WHEN (b.mst_type2 = 'rcs' OR b.mst_type2 = 'rc' OR b.mst_type2 = 'rcm' OR b.mst_type2 = 'rct')
                        THEN
                            -- rsc + 문자 모두 대기값을 rcs 대기값으로 설정.
                            (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END)
                            -- (CASE WHEN b.mst_type3 IS NULL THEN (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END)
                        ELSE
                            0
                    END AS mst_rcs_wait
            FROM (SELECT *
                  FROM cb_wt_msg_sent b
";
        $sql .= $sub_where2.") AS b ) AS total
        GROUP BY mst_mem_id) AS sb ON sa.mem_id = sb.mst_mem_id
";
        $sql .= $orderby_str;

        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ". $sql);
        // log_message("ERROR", "SQL : ".$sql);
        return $this->db->query($sql)->result();
    }


    function get_daily_report($Date)
    {
        $param = array();
        $where = "";

        if($Date['startDate']) {
            $where .= " and (
		                	 ( mst_reserved_dt <> '00000000000000' AND
                              (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$Date['startDate']." 00:00:00"."' AND '".$Date['startDate']." 23:59:59"."')
                             ) OR
                             ( mst_reserved_dt = '00000000000000' AND
                              (mst_datetime BETWEEN  '".$Date['startDate']." 00:00:00"."' AND '".$Date['startDate']." 23:59:59"."')
                             )
                            )";
            array_push($param, $Date['startDate']." 00:00:00" );
            array_push($param, $Date['startDate']." 23:59:59" );
            array_push($param, $Date['startDate']." 00:00:00" );
            array_push($param, $Date['startDate']." 23:59:59" );
        }

        if($Date['pf_key'] && $Date['pf_key'] != 'ALL') {
            //$where .=  " and mst_profile='".$Date['pf_key']."' ";
            $where .=  " and mst_mem_id='".$Date['pf_key']."' ";
        }

        $sql = "select
					mem_username,
                    (select cm.mem_username from cb_member cm inner join cb_member_register cmr on cm.mem_id = cmr.mrg_recommend_mem_id where cmr.mem_id = mst_mem_id) as parent_name,
					sum(mst_qty) mst_qty,
                    sum(reserved_qty) reserved_qty,
					sum(mst_ft) mst_ft,
					sum(mst_ft_img) mst_ft_img,
                    sum(mst_ft_wide_img) mst_ft_wide_img,
					sum(mst_at) mst_at,
					sum(mst_ai) mst_ai,
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
					sum(mst_err_ai) err_ai,
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
                    mem_userid,
                    group_concat(mst_id ) as mst_id,
                    mst_mem_id,
                    (select mrg_recommend_mem_id from cb_member_register where mem_id = mst_mem_id) as parent_mem_id
                from(
                select mem_username,
					mst_qty,
                    (case when mst_reserved_dt <> '00000000000000' AND mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') then mst_qty else  0 end) reserved_qty,
					mst_ft,
					case when (mst_type1 = 'fti') then mst_ft_img else 0 end as mst_ft_img,
                    case when (mst_type1 = 'ftw') then mst_ft_img else 0 end as mst_ft_wide_img,
                    case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_at else 0 end as mst_at,
                    case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_at else 0 end as mst_ai,
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
					case when (mst_type2 = 'wc') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt,
					case when (mst_type2 = 'wcs') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt_sms,
					case when (mst_type2 = 'wcm') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt_mms,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_err_at else 0 end as mst_err_at,
					case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_err_at else 0 end as mst_err_ai,
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
					case when (mst_type2 = 'wc') then mst_err_smt else 0 end as mst_err_smt,
					case when (mst_type2 = 'wcs') then mst_err_smt else 0 end as mst_err_smt_sms,
					case when (mst_type2 = 'wcm') then mst_err_smt else 0 end as mst_err_smt_mms,
					-- mst_wait,
                    case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end mst_wait,
					mem_userid,
                	mst_id,
                	mst_mem_id
				from cb_wt_msg_sent_v where mst_mem_id in (
                					WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
                    	UNION ALL
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                    )
                    SELECT distinct mem_id
                    FROM cmem
                    UNION ALL
                    SELECT ".$this->member->item('mem_id')." mem_id
                    )  ".$where.") temp group by mem_username, mst_mem_id order by 2, 1";

        //         $sql = "select
        // 					mem_username,
        //                     (select cm.mem_username from cb_member cm inner join cb_member_register cmr on cm.mem_id = cmr.mrg_recommend_mem_id where cmr.mem_id = mst_mem_id) as parent_name,
        // 					sum(mst_qty) mst_qty,
        // 					sum(mst_ft) mst_ft,
        // 					sum(mst_ft_img) mst_ft_img,
        // 					sum(mst_at) mst_at,
        // 					sum(mst_sms) mst_sms,
        // 					sum(mst_lms) mst_lms,
        // 					sum(mst_mms) mst_mms,
        //                     sum(mst_grs) mst_grs,
        //                     sum(mst_grs_sms) mst_grs_sms,
        //                     sum(mst_nas) mst_nas,
        //                     sum(mst_nas_sms) mst_nas_sms,
        //                     sum(mst_015) mst_015,
        // 					sum(mst_phn) mst_phn,
        // 					sum(mst_err_at) err_at,
        // 					sum(mst_err_ft) err_ft,
        // 					sum(mst_err_ft_img) err_ft_img,
        // 					sum(mst_err_sms) qty_sms,
        // 					sum(mst_err_lms) qty_lms,
        // 					sum(mst_err_mms) qty_mms,
        //                     sum(mst_err_grs) qty_grs,
        //                     sum(mst_err_grs_sms) qty_grs_sms,
        //                     sum(mst_err_nas) qty_nas,
        //                     sum(mst_err_nas_sms) qty_nas_sms,
        //                     sum(mst_err_015) qty_015,
        // 					sum(mst_err_phn) qty_phn,
        // 					sum(mst_wait) qty_wait,
        //                     mem_userid,
        //                     group_concat(mst_id ) as mst_id,
        //                     mst_mem_id,
        //                     (select mrg_recommend_mem_id from cb_member_register where mem_id = mst_mem_id) as parent_mem_id
        // 				from cb_wt_msg_sent_v where mst_mem_id in (
        //                 					WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
        //                     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //                     	FROM cb_member_register cmr
        //                     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //                     	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
        //                     	UNION ALL
        //                     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //                     	FROM cb_member_register cmr
        //                     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //                     	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
        //                     )
        //                     SELECT distinct mem_id
        //                     FROM cmem
        //                     ) ".$where." group by mem_username, mst_mem_id order by 2, 1";
        //log_message("ERROR", "SQL : ".$sql);
        return $this->db->query($sql )->result();
    }

    function get_monthly_report_new($Data)
    {
        // from 절 첫번째 sub query 조건
        $sub_where1 = "";
        // from 절 두번째 sub query 조건
        $sub_where2 = "";
        // from 절 join 형태 : LEFT JOIN -> 미발송 업체 포함, RIGHT JOIN -> 미발송 업체 미포함
        $join_str = "";
        // 정렬 문자열
        $orderby_str = "";

        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['startDate'] : ". $Data['startDate']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['pf_key'] : ". $Data['pf_key']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['branch'] : ". $Data['branch']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['salesperson'] : ". $Data['salesperson']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['zero_include'] : ". $Data['zero_include']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['parent_id'] : ". $Data['parent_id']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['parent_name'] : ". $Data['parent_name']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['orderby'] : ". $Data['orderby']);

        if($Data['orderby'] && $Data['orderby'] == "1") {   // 관리업체명, 업체명
            $orderby_str = "ORDER BY sa.parent_name, sa.mem_username";
        } else if ($Data['orderby'] && $Data['orderby'] == "2") {   // 지점, 영업자
            $orderby_str = "ORDER BY sa.dbo_name, sa.ds_name";
        } else if ($Data['orderby'] && $Data['orderby'] == "3") {   // 발송수량
            $orderby_str = "ORDER BY sb.mst_qty DESC";
        }

        if($Data['pf_key'] && $Data['pf_key'] != 'ALL') {
            //$where .=  " and mst_profile='".$Data['pf_key']."' ";
            $sub_where1 .= "        WHERE   acb.mem_id='".$Data['pf_key']."'
";
            $sub_where2 .= "                  WHERE b.mst_mem_id='".$Data['pf_key']."'
";
        } else {
            $sub_where2 .= "                  WHERE b.mst_mem_id in (
                            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            WHERE cm.mem_id = '".$this->member->item('mem_id')."' AND cmr.mem_id <> '".$this->member->item('mem_id')."'
                            UNION ALL
                            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                            FROM cb_member_register cmr
                            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                            SELECT distinct mem_id
                            FROM cmem
                            UNION ALL
                            SELECT ".$this->member->item('mem_id')." mem_id)
";
        }

        if($Data['branch'] && $Data['branch'] != 'ALL') {
            if ($sub_where1 == "") {
                $sub_where1 .= "        WHERE   acb.mem_sale_point_id in (".$Data['branch'].")
";
            } else {
                $sub_where1 .= "                AND acb.mem_sale_point_id in (".$Data['branch'].")
";
            }
        }

        if($Data['salesperson'] && $Data['salesperson'] != 'ALL') {
            if ($sub_where1 == "") {
                $sub_where1 .= "        WHERE   acb.mem_sales_mng_id in (".$Data['salesperson'].")
";
            } else {
                $sub_where1 .= "                AND acb.mem_sales_mng_id in (".$Data['salesperson'].")
";
            }
        }

        if($Data['startDate']) {
            $sub_where2 .= "                        AND (( b.mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(b.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$Data['startDate']."-01 00:00:00' AND '".$Data['startDate']."-31 23:59:59'))
                                OR (b.mst_reserved_dt = '00000000000000' AND (b.mst_datetime BETWEEN  '".$Data['startDate']."-01 00:00:00' AND '".$Data['startDate']."-31 23:59:59')))";
            //             $sub_where2 .= " AND (
            // 		                	 ( mst_reserved_dt <> '00000000000000' AND
            //                               (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$Data['startDate']."-01 00:00:00"."' AND '".$Data['startDate']."-31 23:59:59"."')
            //                              ) OR
            //                              ( mst_reserved_dt = '00000000000000' AND
            //                               (mst_datetime BETWEEN  '".$Data['startDate']."-01 00:00:00"."' AND '".$Data['startDate']."-31 23:59:59"."')
            //                              )
            //                             )";
        }

        if($Data['zero_include'] == "YES") { // 미발송업체 포함
            $join_str = "        LEFT JOIN -- 미발송 업체 포함
";
        } else { // 미발송업체 미포함
            // $join_str = "        RIGHT JOIN -- 미발송 업체 미포함";
            $join_str = "        INNER JOIN -- 미발송 업체 미포함
";
        }

        // query문 생성
        $sql = "SELECT  mem_id,
        mem_userid,
        mem_username,
        parent_mem_id,
        parent_name,
        dbo_name,
        ds_name,
        ds_position,
        mst_id,
        ifnull(mst_qty, 0) AS mst_qty,
        ifnull(reserved_qty, 0) AS reserved_qty,
        ifnull(mst_ft, 0) AS mst_ft,
        ifnull(mst_ft_img, 0) AS mst_ft_img,
        ifnull(mst_ft_wide_img, 0) AS mst_ft_wide_img,
        ifnull(mst_at, 0) AS mst_at,
        ifnull(mst_ai, 0) AS mst_ai,
        ifnull(mst_sms, 0) AS mst_sms,
        ifnull(mst_lms, 0) AS mst_lms,
        ifnull(mst_mms, 0) AS mst_mms,
        ifnull(mst_grs, 0) AS mst_grs,
        ifnull(mst_grs_sms, 0) AS mst_grs_sms,
        ifnull(mst_grs_biz_qty, 0) AS mst_grs_biz_qty,
        ifnull(mst_nas, 0) AS mst_nas,
        ifnull(mst_nas_sms, 0) AS mst_nas_sms,
        ifnull(mst_015, 0) AS mst_015,
        ifnull(mst_phn, 0) AS mst_phn,
        ifnull(mst_grs_mms, 0) AS mst_grs_mms,
        ifnull(mst_nas_mms, 0) AS mst_nas_mms,
        ifnull(mst_smt, 0) AS mst_smt,
        ifnull(mst_smt_sms, 0) AS mst_smt_sms,
        ifnull(mst_smt_mms, 0) AS mst_smt_mms,
        ifnull(mst_rcs_sms, 0) AS mst_rcs_sms,
        ifnull(mst_rcs, 0) AS mst_rcs,
        ifnull(mst_rcs_mms, 0) AS mst_rcs_mms,
        ifnull(mst_rcs_tpl, 0) AS mst_rcs_tpl,
        ifnull(err_at, 0) AS err_at,
        ifnull(err_ai, 0) AS err_ai,
        ifnull(err_ft, 0) AS err_ft,
        ifnull(err_ft_img, 0) AS err_ft_img,
        ifnull(err_ft_wide_img, 0) AS err_ft_wide_img,
        ifnull(qty_sms, 0) AS qty_sms,
        ifnull(qty_lms, 0) AS qty_lms,
        ifnull(qty_mms, 0) AS qty_mms,
        ifnull(qty_grs, 0) AS qty_grs,
        ifnull(qty_grs_sms, 0) AS qty_grs_sms,
        ifnull(qty_nas, 0) AS qty_nas,
        ifnull(qty_nas_sms, 0) AS qty_nas_sms,
        ifnull(qty_015, 0) AS qty_015,
        ifnull(qty_phn, 0) AS qty_phn,
        ifnull(qty_grs_mms, 0) AS qty_grs_mms,
        ifnull(qty_nas_mms, 0) AS qty_nas_mms,
        ifnull(qty_smt, 0) AS qty_smt,
        ifnull(qty_smt_sms, 0) AS qty_smt_sms,
        ifnull(qty_smt_mms, 0) AS qty_smt_mms,
        ifnull(qty_rcs_sms, 0) AS qty_rcs_sms,
        ifnull(qty_rcs, 0) AS qty_rcs,
        ifnull(qty_rcs_mms, 0) AS qty_rcs_mms,
        ifnull(qty_rcs_tpl, 0) AS qty_rcs_tpl,
        ifnull(qty_wait, 0) AS qty_wait,
        ifnull(qty_rcs_wait, 0) AS qty_rcs_wait,
        mem_emp_phone,
        (ifnull(mst_ft, 0) + ifnull(mst_ft_img, 0) + ifnull(mst_ft_wide_img, 0)
         + ifnull(mst_at, 0) + ifnull(mst_ai, 0) + ifnull(mst_smt, 0) + ifnull(mst_smt_sms, 0)
         + ifnull(mst_smt_mms, 0) + ifnull(mst_rcs, 0) + ifnull(mst_rcs_sms, 0) + ifnull(mst_rcs_mms, 0) + ifnull(mst_rcs_tpl, 0)) * cwma.mad_incentive AS incen,
        cwma.mad_incentive
FROM    (SELECT acb.mem_id, acb.mem_userid, acb.mem_username, acb.mem_emp_phone,
                (SELECT dbo_name FROM dhn_branch_office2 WHERE dbo_id = acb.mem_sale_point_id AND dbo_part='BO') AS dbo_name,
                (SELECT ds_name FROM dhn_salesperson2 WHERE ds_id = acb.mem_sales_mng_id) AS ds_name,
                (SELECT CASE ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장' WHEN 'MA' THEN '과장'
                        WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END
                FROM    dhn_salesperson2 WHERE ds_id = acb.mem_sales_mng_id) AS ds_position,
                rec.parent_id AS parent_mem_id,
                rec.parent_name AS parent_name
        FROM    cb_member acb INNER JOIN
                (WITH RECURSIVE cmem(mem_id, parent_id, parent_name) AS (
                    SELECT  cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    FROM    cb_member_register cmr
                    JOIN    cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    WHERE cm.mem_id = '".$this->member->item('mem_id')."' AND cmr.mem_id <> '".$this->member->item('mem_id')."'
                    UNION ALL
                    SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    FROM cb_member_register cmr
                    JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                    SELECT distinct mem_id, parent_id, parent_name
                    FROM cmem
                    UNION ALL
                    SELECT ".$this->member->item('mem_id')." mem_id, ".$Data['parent_id']." parent_id, '".$Data['parent_name']."' parent_name) AS rec
                        ON rec.mem_id = acb.mem_id
";
        $sql .= $sub_where1."        ) AS sa
        LEFT JOIN
        cb_wt_member_addon cwma ON sa.mem_id = cwma.mad_mem_id
";
        $sql .= $join_str."        (SELECT mst_mem_id,
                group_concat(mst_id) AS mst_id,
                sum(mst_qty) AS mst_qty,
                sum(reserved_qty) AS reserved_qty,
                sum(mst_ft) AS mst_ft,
                sum(mst_ft_img) AS mst_ft_img,
                sum(mst_ft_wide_img) AS mst_ft_wide_img,
                sum(mst_at) AS mst_at,
                sum(mst_ai) AS mst_ai,
                sum(mst_sms) AS mst_sms,
                sum(mst_lms) AS mst_lms,
                sum(mst_mms) AS mst_mms,
                sum(mst_grs) AS mst_grs,
                sum(mst_grs_sms) AS mst_grs_sms,
                sum(mst_grs_biz_qty) AS mst_grs_biz_qty,
                sum(mst_nas) AS mst_nas,
                sum(mst_nas_sms) AS mst_nas_sms,
                sum(mst_015) AS mst_015,
                sum(mst_phn) AS mst_phn,
                sum(mst_grs_mms) AS mst_grs_mms,
                sum(mst_nas_mms) AS mst_nas_mms,
                sum(mst_smt) AS mst_smt,
                sum(mst_smt_sms) AS mst_smt_sms,
                sum(mst_smt_mms) AS mst_smt_mms,
                sum(mst_rcs_sms) AS mst_rcs_sms,
                sum(mst_rcs) AS mst_rcs,
                sum(mst_rcs_mms) AS mst_rcs_mms,
                sum(mst_rcs_tpl) AS mst_rcs_tpl,
                sum(mst_err_at) AS err_at,
                sum(mst_err_ai) AS err_ai,
                sum(mst_err_ft) AS err_ft,
                sum(mst_err_ft_img) AS err_ft_img,
                sum(mst_err_ft_wide_img) AS err_ft_wide_img,
                sum(mst_err_sms) AS qty_sms,
                sum(mst_err_lms) AS qty_lms,
                sum(mst_err_mms) AS qty_mms,
                sum(mst_err_grs) AS qty_grs,
                sum(mst_err_grs_sms) AS qty_grs_sms,
                sum(mst_err_nas) AS qty_nas,
                sum(mst_err_nas_sms) AS qty_nas_sms,
                sum(mst_err_015) AS qty_015,
                sum(mst_err_phn) AS qty_phn,
                sum(mst_err_grs_mms) AS qty_grs_mms,
                sum(mst_err_nas_mms) AS qty_nas_mms,
                sum(mst_err_smt) AS qty_smt,
                sum(mst_err_smt_sms) AS qty_smt_sms,
                sum(mst_err_smt_mms) AS qty_smt_mms,
                sum(mst_err_rcs_sms) AS qty_rcs_sms,
                sum(mst_err_rcs) AS qty_rcs,
                sum(mst_err_rcs_mms) AS qty_rcs_mms,
                sum(mst_err_rcs_tpl) AS qty_rcs_tpl,
                sum(mst_wait) AS qty_wait,
                sum(mst_rcs_wait) AS qty_rcs_wait
        FROM (SELECT
                    b.mst_id,
                    b.mst_mem_id,
                    b.mst_qty,
                    (CASE WHEN b.mst_reserved_dt <> '00000000000000' AND b.mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') THEN b.mst_qty ELSE  0 END) reserved_qty,
                    b.mst_ft,
                    CASE WHEN (b.mst_type1 = 'fti') THEN b.mst_ft_img ELSE 0 END AS mst_ft_img,
                    CASE WHEN (b.mst_type1 = 'ftw') THEN b.mst_ft_img ELSE 0 END AS mst_ft_wide_img,
                    CASE WHEN (b.mst_type1 = 'at' or b.mst_type2 = 'at') THEN mst_at ELSE 0 END AS mst_at,
                    CASE WHEN (b.mst_type1 = 'ai' or b.mst_type2 = 'ai') THEN mst_at ELSE 0 END AS mst_ai,
                    b.mst_sms,
                    b.mst_lms,
                    b.mst_mms,
                    CASE WHEN (b.mst_type2 = 'wa' OR b.mst_type3 = 'wa') THEN (CASE WHEN (b.mst_type3 = 'wa') THEN b.mst_grs ELSE (b.mst_grs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs,
                    CASE WHEN (b.mst_type2 = 'was' OR b.mst_type3 = 'was') THEN (CASE WHEN (b.mst_type3 = 'was') THEN b.mst_grs_sms ELSE (b.mst_grs_sms + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs_sms,
                    b.mst_grs_biz_qty,
                    CASE WHEN (b.mst_type2 = 'wb' OR b.mst_type3 = 'wb') THEN (CASE WHEN (b.mst_type3 = 'wb') THEN b.mst_nas ELSE (b.mst_nas + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas,
                    CASE WHEN (b.mst_type2 = 'wbs' OR b.mst_type3 = 'wbs') THEN (CASE WHEN (b.mst_type3 = 'wbs') THEN b.mst_nas_sms ELSE (b.mst_nas_sms + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas_sms,
                    b.mst_015,
                    b.mst_phn,
                    CASE WHEN (b.mst_type2 = 'wam' OR b.mst_type3 = 'wam') THEN (CASE WHEN (b.mst_type3 = 'wam') THEN b.mst_grs ELSE (b.mst_grs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs_mms,
                    CASE WHEN (b.mst_type2 = 'wbm' OR b.mst_type3 = 'wbm') THEN (CASE WHEN (b.mst_type3 = 'wbm') THEN b.mst_nas ELSE (b.mst_nas + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas_mms,
                    CASE WHEN (b.mst_type2 = 'wc' OR b.mst_type3 = 'wc') THEN (CASE WHEN (b.mst_type3 = 'wc') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt,
                    CASE WHEN (b.mst_type2 = 'wcs' OR b.mst_type3 = 'wcs') THEN (CASE WHEN (b.mst_type3 = 'wcs') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt_sms,
                    CASE WHEN (b.mst_type2 = 'wcm' OR b.mst_type3 = 'wcm') THEN (CASE WHEN (b.mst_type3 = 'wcm') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt_mms,
                    CASE WHEN (b.mst_type2 = 'rcs') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_sms,
                    CASE WHEN (b.mst_type2 = 'rc') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs,
                    CASE WHEN (b.mst_type2 = 'rcm') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_mms,
                    CASE WHEN (b.mst_type2 = 'rct') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_tpl,
                    CASE WHEN (b.mst_type1 = 'at' or b.mst_type2 = 'at') THEN b.mst_err_at ELSE 0 END AS mst_err_at,
                    CASE WHEN (b.mst_type1 = 'ai' or b.mst_type2 = 'ai') THEN b.mst_err_at ELSE 0 END AS mst_err_ai,
                    b.mst_err_ft,
                    CASE WHEN (b.mst_type1 = 'fti') THEN b.mst_err_ft_img ELSE 0 END AS mst_err_ft_img,
                    CASE WHEN (b.mst_type1 = 'ftw') THEN b.mst_err_ft_img ELSE 0 END AS mst_err_ft_wide_img,
                    b.mst_err_sms,
                    b.mst_err_lms,
                    b.mst_err_mms,
                    CASE WHEN (b.mst_type2 = 'wa') THEN b.mst_err_grs ELSE 0 END AS mst_err_grs,
                    b.mst_err_grs_sms,
                    CASE WHEN (b.mst_type2 = 'wb') THEN b.mst_err_nas ELSE 0 END AS mst_err_nas,
                    b.mst_err_nas_sms,
                    b.mst_err_015,
                    b.mst_err_phn,
                    CASE WHEN (b.mst_type2 = 'wam') THEN b.mst_err_grs ELSE 0 END AS mst_err_grs_mms,
                    CASE WHEN (b.mst_type2 = 'wbm') THEN b.mst_err_nas ELSE 0 END AS mst_err_nas_mms,
                    CASE WHEN (b.mst_type2 = 'wc') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt,
                    CASE WHEN (b.mst_type2 = 'wcs') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt_sms,
                    CASE WHEN (b.mst_type2 = 'wcm') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt_mms,
                    CASE WHEN (b.mst_type2 = 'rcs') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_sms,
                    CASE WHEN (b.mst_type2 = 'rc') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs,
                    CASE WHEN (b.mst_type2 = 'rcm') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_mms,
                    CASE WHEN (b.mst_type2 = 'rct') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_tpl,
                    -- CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END mst_wait,
                    CASE WHEN NOT (b.mst_type2 = 'rcs' OR b.mst_type2 = 'rc' OR b.mst_type2 = 'rcm' OR b.mst_type2 = 'rct')
                        THEN
                            (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END)
                        ELSE
                            0  -- rsc + 문자 대기값을 모두 rcs 쪽으로 보내기.
                            -- (CASE WHEN b.mst_type3 IS NOT NULL THEN (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END)
                    END AS mst_wait,
                    CASE WHEN (b.mst_type2 = 'rcs' OR b.mst_type2 = 'rc' OR b.mst_type2 = 'rcm' OR b.mst_type2 = 'rct')
                        THEN
                            -- rsc + 문자 모두 대기값을 rcs 대기값으로 설정.
                            (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END)
                            -- (CASE WHEN b.mst_type3 IS NULL THEN (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END)
                        ELSE
                            0
                    END AS mst_rcs_wait
            FROM (SELECT *
                  FROM cb_wt_msg_sent b
";
        $sql .= $sub_where2.") AS b ) AS total
        GROUP BY mst_mem_id) AS sb ON sa.mem_id = sb.mst_mem_id
";
        $sql .= $orderby_str;

        // log_message("ERROR", "SQL : ".$sql);
        return $this->db->query($sql )->result();
    }

    function get_sales_monthly_report_new($Data)
    {
        // from 절 첫번째 sub query 조건
        $sub_where1 = "";
        // from 절 두번째 sub query 조건
        $sub_where2 = "";
        // from 절 join 형태 : LEFT JOIN -> 미발송 업체 포함, RIGHT JOIN -> 미발송 업체 미포함
        $join_str = "";
        // 정렬 문자열
        $orderby_str = "";

        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['startDate'] : ". $Data['startDate']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['pf_key'] : ". $Data['pf_key']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['branch'] : ". $Data['branch']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['salesperson'] : ". $Data['salesperson']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['zero_include'] : ". $Data['zero_include']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['parent_id'] : ". $Data['parent_id']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['parent_name'] : ". $Data['parent_name']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['orderby'] : ". $Data['orderby']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['sales_id'] : ". $Data['sales_id']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Data['managerType'] : ". $Data['managerType']); // option

        if($Data['orderby'] && $Data['orderby'] == "1") {   // 관리업체명, 업체명
            $orderby_str = "ORDER BY sa.parent_name, sa.mem_username";
        } else if ($Data['orderby'] && $Data['orderby'] == "2") {   // 지점, 영업자
            $orderby_str = "ORDER BY sa.dbo_name, sa.ds_name";
        } else if ($Data['orderby'] && $Data['orderby'] == "3") {   // 발송수량
            $orderby_str = "ORDER BY sb.mst_qty DESC";
        }

        if($Data['pf_key'] && $Data['pf_key'] != 'ALL') {
            //$where .=  " and mst_profile='".$Data['pf_key']."' ";
            $sub_where1 .= "        WHERE   acb.mem_id='".$Data['pf_key']."'
";
            $sub_where2 .= "                  WHERE b.mst_mem_id='".$Data['pf_key']."'
";
        } else {
//             $sub_where2 .= "                  WHERE b.mst_mem_id in (
//                             WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
//                             SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//                             FROM cb_member_register cmr
//                             JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//                             WHERE cm.mem_id = '".$this->member->item('mem_id')."' AND cmr.mem_id <> '".$this->member->item('mem_id')."'
//                             UNION ALL
//                             SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//                             FROM cb_member_register cmr
//                             JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//                             JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
//                             SELECT distinct mem_id
//                             FROM cmem
//                             UNION ALL
//                             SELECT ".$this->member->item('mem_id')." mem_id)
// ";
            if ($Data['managerType'] == "S") {
                $sub_where2 .= "                  WHERE b.mst_mem_id in (
                                SELECT mem_id FROM cb_member WHERE mem_id = '".$this->member->item("mem_id")."' OR mem_sales_mng_id = ".$Data['sales_id'].") ";
            } else if ($Data['managerType'] == "M") {
                $sub_where2 .= "                  WHERE b.mst_mem_id in (
                                SELECT mem_id FROM cb_member WHERE mem_id = '".$this->member->item("mem_id")."' OR mem_management_mng_id = ".$Data['sales_id'].") ";
            } else if ($Data['managerType'] == "O") {
                $sub_where2 .= "                  WHERE b.mst_mem_id in (
                                SELECT mem_id FROM cb_member WHERE mem_id = '".$this->member->item("mem_id")."' OR (mem_management_mng_id = ".$Data['sales_id']." AND mem_sales_mng_id <> mem_management_mng_id)) ";
            } else {
                $sub_where2 .= "                  WHERE b.mst_mem_id in (
                                SELECT mem_id FROM cb_member WHERE mem_id = '".$this->member->item("mem_id")."' OR mem_management_mng_id = ".$Data['sales_id']." OR mem_sales_mng_id = ".$Data['sales_id'].") ";
            }

        }

        if($Data['branch'] && $Data['branch'] != 'ALL') {
            if ($sub_where1 == "") {
                $sub_where1 .= "        WHERE   acb.mem_sale_point_id in (".$Data['branch'].")
";
            } else {
                $sub_where1 .= "                AND acb.mem_sale_point_id in (".$Data['branch'].")
";
            }
        }

        if($Data['salesperson'] && $Data['salesperson'] != 'ALL') {
            if ($sub_where1 == "") {
                $sub_where1 .= "        WHERE   acb.mem_sales_mng_id in (".$Data['salesperson'].")
";
            } else {
                $sub_where1 .= "                AND acb.mem_sales_mng_id in (".$Data['salesperson'].")
";
            }
        }

        if($Data['startDate']) {
            $sub_where2 .= "                        AND (( b.mst_reserved_dt <> '00000000000000' AND (STR_TO_DATE(b.mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$Data['startDate']."-01 00:00:00' AND '".$Data['startDate']."-31 23:59:59'))
                                OR (b.mst_reserved_dt = '00000000000000' AND (b.mst_datetime BETWEEN  '".$Data['startDate']."-01 00:00:00' AND '".$Data['startDate']."-31 23:59:59')))";
            //             $sub_where2 .= " AND (
            // 		                	 ( mst_reserved_dt <> '00000000000000' AND
            //                               (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$Data['startDate']."-01 00:00:00"."' AND '".$Data['startDate']."-31 23:59:59"."')
            //                              ) OR
            //                              ( mst_reserved_dt = '00000000000000' AND
            //                               (mst_datetime BETWEEN  '".$Data['startDate']."-01 00:00:00"."' AND '".$Data['startDate']."-31 23:59:59"."')
            //                              )
            //                             )";
        }

        if($Data['zero_include'] == "YES") { // 미발송업체 포함
            $join_str = "        LEFT JOIN -- 미발송 업체 포함
";
        } else { // 미발송업체 미포함
            // $join_str = "        RIGHT JOIN -- 미발송 업체 미포함";
            $join_str = "        INNER JOIN -- 미발송 업체 미포함
";
        }

        // query문 생성
        $sql = "select  mem_id,
        mem_userid,
        mem_username,
        parent_mem_id,
        parent_name,
        dbo_name,
        ds_name,
        ds_position,
        mst_id,
        ifnull(mst_qty, 0) AS mst_qty,
        ifnull(reserved_qty, 0) AS reserved_qty,
        ifnull(mst_ft, 0) AS mst_ft,
        ifnull(mst_ft_img, 0) AS mst_ft_img,
        ifnull(mst_ft_wide_img, 0) AS mst_ft_wide_img,
        ifnull(mst_at, 0) AS mst_at,
        ifnull(mst_ai, 0) AS mst_ai,
        ifnull(mst_sms, 0) AS mst_sms,
        ifnull(mst_lms, 0) AS mst_lms,
        ifnull(mst_mms, 0) AS mst_mms,
        ifnull(mst_grs, 0) AS mst_grs,
        ifnull(mst_grs_sms, 0) AS mst_grs_sms,
        ifnull(mst_grs_biz_qty, 0) AS mst_grs_biz_qty,
        ifnull(mst_nas, 0) AS mst_nas,
        ifnull(mst_nas_sms, 0) AS mst_nas_sms,
        ifnull(mst_015, 0) AS mst_015,
        ifnull(mst_phn, 0) AS mst_phn,
        ifnull(mst_grs_mms, 0) AS mst_grs_mms,
        ifnull(mst_nas_mms, 0) AS mst_nas_mms,
        ifnull(mst_smt, 0) AS mst_smt,
        ifnull(mst_smt_sms, 0) AS mst_smt_sms,
        ifnull(mst_smt_mms, 0) AS mst_smt_mms,
        ifnull(mst_rcs_sms, 0) AS mst_rcs_sms,
        ifnull(mst_rcs, 0) AS mst_rcs,
        ifnull(mst_rcs_mms, 0) AS mst_rcs_mms,
        ifnull(mst_rcs_tpl, 0) AS mst_rcs_tpl,
        ifnull(err_at, 0) AS err_at,
        ifnull(err_ai, 0) AS err_ai,
        ifnull(err_ft, 0) AS err_ft,
        ifnull(err_ft_img, 0) AS err_ft_img,
        ifnull(err_ft_wide_img, 0) AS err_ft_wide_img,
        ifnull(qty_sms, 0) AS qty_sms,
        ifnull(qty_lms, 0) AS qty_lms,
        ifnull(qty_mms, 0) AS qty_mms,
        ifnull(qty_grs, 0) AS qty_grs,
        ifnull(qty_grs_sms, 0) AS qty_grs_sms,
        ifnull(qty_nas, 0) AS qty_nas,
        ifnull(qty_nas_sms, 0) AS qty_nas_sms,
        ifnull(qty_015, 0) AS qty_015,
        ifnull(qty_phn, 0) AS qty_phn,
        ifnull(qty_grs_mms, 0) AS qty_grs_mms,
        ifnull(qty_nas_mms, 0) AS qty_nas_mms,
        ifnull(qty_smt, 0) AS qty_smt,
        ifnull(qty_smt_sms, 0) AS qty_smt_sms,
        ifnull(qty_smt_mms, 0) AS qty_smt_mms,
        ifnull(qty_rcs_sms, 0) AS qty_rcs_sms,
        ifnull(qty_rcs, 0) AS qty_rcs,
        ifnull(qty_rcs_mms, 0) AS qty_rcs_mms,
        ifnull(qty_rcs_tpl, 0) AS qty_rcs_tpl,
        ifnull(qty_wait, 0) AS qty_wait,
        ifnull(qty_rcs_wait, 0) AS qty_rcs_wait,
        mem_emp_phone,
        (ifnull(mst_ft, 0) + ifnull(mst_ft_img, 0) + ifnull(mst_ft_wide_img, 0)
         + ifnull(mst_at, 0) + ifnull(mst_ai, 0) + ifnull(mst_smt, 0) + ifnull(mst_smt_sms, 0)
         + ifnull(mst_smt_mms, 0) + ifnull(mst_rcs, 0) + ifnull(mst_rcs_sms, 0) + ifnull(mst_rcs_mms, 0) + ifnull(mst_rcs_tpl, 0)) * cwma.mad_incentive AS incen,
        cwma.mad_incentive
FROM    (SELECT acb.mem_id, acb.mem_userid, acb.mem_username, acb.mem_emp_phone,
                (SELECT dbo_name FROM dhn_branch_office2 WHERE dbo_id = acb.mem_sale_point_id AND dbo_part='BO') AS dbo_name,
                (SELECT ds_name FROM dhn_salesperson2 WHERE ds_id = acb.mem_sales_mng_id) AS ds_name,
                (SELECT CASE ds_position WHEN 'SD' THEN '이사' WHEN 'BM' THEN '지점장' WHEN 'DI' THEN '부장' WHEN 'DG' THEN '차장' WHEN 'MA' THEN '과장'
                        WHEN 'AM' THEN '대리' WHEN 'SS' THEN '주임' WHEN 'ST' THEN '' ELSE '' END
                FROM    dhn_salesperson2 WHERE ds_id = acb.mem_sales_mng_id) AS ds_position,
                rec.parent_id AS parent_mem_id,
                rec.parent_name AS parent_name
        FROM    cb_member acb INNER JOIN ";

        if ($Data['managerType'] == "S") {
            $sql .= "
                (SELECT mem_id, ".$this->member->item("mem_id")." parent_id, (SELECT mem_username FROM cb_member WHERE mem_id = ".$this->member->item("mem_id").") parent_name
                 FROM cb_member WHERE mem_sales_mng_id = ".$Data['sales_id'];
        } else if ($Data['managerType'] == "M") {
            $sql .= "
                (SELECT mem_id, ".$this->member->item("mem_id")." parent_id, (SELECT mem_username FROM cb_member WHERE mem_id = ".$this->member->item("mem_id").") parent_name
                 FROM cb_member WHERE mem_management_mng_id = ".$Data['sales_id'];
        } else if ($Data['managerType'] == "O") {
            $sql .= "
                (SELECT mem_id, ".$this->member->item("mem_id")." parent_id, (SELECT mem_username FROM cb_member WHERE mem_id = ".$this->member->item("mem_id").") parent_name
                 FROM cb_member WHERE mem_management_mng_id = ".$Data['sales_id']. " AND mem_sales_mng_id <> mem_management_mng_id ";
        } else {
            $sql .= "
                (SELECT mem_id, ".$this->member->item("mem_id")." parent_id, (SELECT mem_username FROM cb_member WHERE mem_id = ".$this->member->item("mem_id").") parent_name
                 FROM cb_member WHERE mem_management_mng_id = ".$Data['sales_id']." OR mem_sales_mng_id = ".$Data['sales_id'];
        }

        $sql .= "                 UNION ALL
                 SELECT ".$this->member->item("mem_id")." mem_id, ".$Data['parent_id']." parent_id, '".$Data['parent_name']."' parent_name) AS rec
                     ON rec.mem_id = acb.mem_id ";
//                 (WITH RECURSIVE cmem(mem_id, parent_id, parent_name) AS (
//                     SELECT  cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//                     FROM    cb_member_register cmr
//                     JOIN    cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//                     WHERE cm.mem_id = '".$this->member->item('mem_id')."' AND cmr.mem_id <> '".$this->member->item('mem_id')."'
//                     UNION ALL
//                     SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//                     FROM cb_member_register cmr
//                     JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//                     JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
//                     SELECT distinct mem_id, parent_id, parent_name
//                     FROM cmem
//                     UNION ALL
//                     SELECT ".$this->member->item('mem_id')." mem_id, ".$Data['parent_id']." parent_id, '".$Data['parent_name']."' parent_name) AS rec
//                         ON rec.mem_id = acb.mem_id
// ";
        $sql .= $sub_where1."        ) AS sa
        LEFT JOIN
        cb_wt_member_addon cwma ON sa.mem_id = cwma.mad_mem_id
";
        $sql .= $join_str."        (SELECT mst_mem_id,
                group_concat(mst_id) AS mst_id,
                sum(mst_qty) AS mst_qty,
                sum(reserved_qty) AS reserved_qty,
                sum(mst_ft) AS mst_ft,
                sum(mst_ft_img) AS mst_ft_img,
                sum(mst_ft_wide_img) AS mst_ft_wide_img,
                sum(mst_at) AS mst_at,
                sum(mst_ai) AS mst_ai,
                sum(mst_sms) AS mst_sms,
                sum(mst_lms) AS mst_lms,
                sum(mst_mms) AS mst_mms,
                sum(mst_grs) AS mst_grs,
                sum(mst_grs_sms) AS mst_grs_sms,
                sum(mst_grs_biz_qty) AS mst_grs_biz_qty,
                sum(mst_nas) AS mst_nas,
                sum(mst_nas_sms) AS mst_nas_sms,
                sum(mst_015) AS mst_015,
                sum(mst_phn) AS mst_phn,
                sum(mst_grs_mms) AS mst_grs_mms,
                sum(mst_nas_mms) AS mst_nas_mms,
                sum(mst_smt) AS mst_smt,
                sum(mst_smt_sms) AS mst_smt_sms,
                sum(mst_smt_mms) AS mst_smt_mms,
                sum(mst_rcs_sms) AS mst_rcs_sms,
                sum(mst_rcs) AS mst_rcs,
                sum(mst_rcs_mms) AS mst_rcs_mms,
                sum(mst_rcs_tpl) AS mst_rcs_tpl,
                sum(mst_err_at) AS err_at,
                sum(mst_err_ai) AS err_ai,
                sum(mst_err_ft) AS err_ft,
                sum(mst_err_ft_img) AS err_ft_img,
                sum(mst_err_ft_wide_img) AS err_ft_wide_img,
                sum(mst_err_sms) AS qty_sms,
                sum(mst_err_lms) AS qty_lms,
                sum(mst_err_mms) AS qty_mms,
                sum(mst_err_grs) AS qty_grs,
                sum(mst_err_grs_sms) AS qty_grs_sms,
                sum(mst_err_nas) AS qty_nas,
                sum(mst_err_nas_sms) AS qty_nas_sms,
                sum(mst_err_015) AS qty_015,
                sum(mst_err_phn) AS qty_phn,
                sum(mst_err_grs_mms) AS qty_grs_mms,
                sum(mst_err_nas_mms) AS qty_nas_mms,
                sum(mst_err_smt) AS qty_smt,
                sum(mst_err_smt_sms) AS qty_smt_sms,
                sum(mst_err_smt_mms) AS qty_smt_mms,
                sum(mst_err_rcs_sms) AS qty_rcs_sms,
                sum(mst_err_rcs) AS qty_rcs,
                sum(mst_err_rcs_mms) AS qty_rcs_mms,
                sum(mst_err_rcs_tpl) AS qty_rcs_tpl,
                sum(mst_wait) AS qty_wait,
                sum(mst_rcs_wait) AS qty_rcs_wait
        FROM (SELECT
                    b.mst_id,
                    b.mst_mem_id,
                    b.mst_qty,
                    (CASE WHEN b.mst_reserved_dt <> '00000000000000' AND b.mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') THEN b.mst_qty ELSE  0 END) reserved_qty,
                    b.mst_ft,
                    CASE WHEN (b.mst_type1 = 'fti') THEN b.mst_ft_img ELSE 0 END AS mst_ft_img,
                    CASE WHEN (b.mst_type1 = 'ftw') THEN b.mst_ft_img ELSE 0 END AS mst_ft_wide_img,
                    CASE WHEN (b.mst_type1 = 'at' or b.mst_type2 = 'at') THEN mst_at ELSE 0 END AS mst_at,
                    CASE WHEN (b.mst_type1 = 'ai' or b.mst_type2 = 'ai') THEN mst_at ELSE 0 END AS mst_ai,
                    b.mst_sms,
                    b.mst_lms,
                    b.mst_mms,
                    CASE WHEN (b.mst_type2 = 'wa' OR b.mst_type3 = 'wa') THEN (CASE WHEN (b.mst_type3 = 'wa') THEN b.mst_grs ELSE (b.mst_grs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs,
                    CASE WHEN (b.mst_type2 = 'was' OR b.mst_type3 = 'was') THEN (CASE WHEN (b.mst_type3 = 'was') THEN b.mst_grs_sms ELSE (b.mst_grs_sms + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs_sms,
                    b.mst_grs_biz_qty,
                    CASE WHEN (b.mst_type2 = 'wb' OR b.mst_type3 = 'wb') THEN (CASE WHEN (b.mst_type3 = 'wb') THEN b.mst_nas ELSE (b.mst_nas + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas,
                    CASE WHEN (b.mst_type2 = 'wbs' OR b.mst_type3 = 'wbs') THEN (CASE WHEN (b.mst_type3 = 'wbs') THEN b.mst_nas_sms ELSE (b.mst_nas_sms + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas_sms,
                    b.mst_015,
                    b.mst_phn,
                    CASE WHEN (b.mst_type2 = 'wam' OR b.mst_type3 = 'wam') THEN (CASE WHEN (b.mst_type3 = 'wam') THEN b.mst_grs ELSE (b.mst_grs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_grs_mms,
                    CASE WHEN (b.mst_type2 = 'wbm' OR b.mst_type3 = 'wbm') THEN (CASE WHEN (b.mst_type3 = 'wbm') THEN b.mst_nas ELSE (b.mst_nas + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_nas_mms,
                    CASE WHEN (b.mst_type2 = 'wc' OR b.mst_type3 = 'wc') THEN (CASE WHEN (b.mst_type3 = 'wc') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt,
                    CASE WHEN (b.mst_type2 = 'wcs' OR b.mst_type3 = 'wcs') THEN (CASE WHEN (b.mst_type3 = 'wcs') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt_sms,
                    CASE WHEN (b.mst_type2 = 'wcm' OR b.mst_type3 = 'wcm') THEN (CASE WHEN (b.mst_type3 = 'wcm') THEN b.mst_smt ELSE (b.mst_smt + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) END) ELSE 0 END AS mst_smt_mms,
                    CASE WHEN (b.mst_type2 = 'rcs') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_sms,
                    CASE WHEN (b.mst_type2 = 'rc') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs,
                    CASE WHEN (b.mst_type2 = 'rcm') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_mms,
                    CASE WHEN (b.mst_type2 = 'rct') THEN (b.mst_rcs + CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END AS mst_rcs_tpl,
                    CASE WHEN (b.mst_type1 = 'at' or b.mst_type2 = 'at') THEN b.mst_err_at ELSE 0 END AS mst_err_at,
                    CASE WHEN (b.mst_type1 = 'ai' or b.mst_type2 = 'ai') THEN b.mst_err_at ELSE 0 END AS mst_err_ai,
                    b.mst_err_ft,
                    CASE WHEN (b.mst_type1 = 'fti') THEN b.mst_err_ft_img ELSE 0 END AS mst_err_ft_img,
                    CASE WHEN (b.mst_type1 = 'ftw') THEN b.mst_err_ft_img ELSE 0 END AS mst_err_ft_wide_img,
                    b.mst_err_sms,
                    b.mst_err_lms,
                    b.mst_err_mms,
                    CASE WHEN (b.mst_type2 = 'wa') THEN b.mst_err_grs ELSE 0 END AS mst_err_grs,
                    b.mst_err_grs_sms,
                    CASE WHEN (b.mst_type2 = 'wb') THEN b.mst_err_nas ELSE 0 END AS mst_err_nas,
                    b.mst_err_nas_sms,
                    b.mst_err_015,
                    b.mst_err_phn,
                    CASE WHEN (b.mst_type2 = 'wam') THEN b.mst_err_grs ELSE 0 END AS mst_err_grs_mms,
                    CASE WHEN (b.mst_type2 = 'wbm') THEN b.mst_err_nas ELSE 0 END AS mst_err_nas_mms,
                    CASE WHEN (b.mst_type2 = 'wc') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt,
                    CASE WHEN (b.mst_type2 = 'wcs') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt_sms,
                    CASE WHEN (b.mst_type2 = 'wcm') THEN b.mst_err_smt ELSE 0 END AS mst_err_smt_mms,
                    CASE WHEN (b.mst_type2 = 'rcs') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_sms,
                    CASE WHEN (b.mst_type2 = 'rc') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs,
                    CASE WHEN (b.mst_type2 = 'rcm') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_mms,
                    CASE WHEN (b.mst_type2 = 'rct') THEN b.mst_err_rcs ELSE 0 END AS mst_err_rcs_tpl,
                    -- CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END mst_wait,
                    CASE WHEN NOT (b.mst_type2 = 'rcs' OR b.mst_type2 = 'rc' OR b.mst_type2 = 'rcm' OR b.mst_type2 = 'rct')
                        THEN
                            (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END)
                        ELSE
                            0  -- rsc + 문자 대기값을 모두 rcs 쪽으로 보내기.
                            -- (CASE WHEN b.mst_type3 IS NOT NULL THEN (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END)
                    END AS mst_wait,
                    CASE WHEN (b.mst_type2 = 'rcs' OR b.mst_type2 = 'rc' OR b.mst_type2 = 'rcm' OR b.mst_type2 = 'rct')
                        THEN
                            -- rsc + 문자 모두 대기값을 rcs 대기값으로 설정.
                            (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END)
                            -- (CASE WHEN b.mst_type3 IS NULL THEN (CASE WHEN (b.mst_reserved_dt > current_timestamp() + 0) THEN 0 ELSE b.mst_wait END) ELSE 0 END)
                        ELSE
                            0
                    END AS mst_rcs_wait
            FROM (SELECT *
                  FROM cb_wt_msg_sent b
";
        $sql .= $sub_where2.") AS b ) AS total
        GROUP BY mst_mem_id) AS sb ON sa.mem_id = sb.mst_mem_id
";
        $sql .= $orderby_str;

        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ". $sql);
        // log_message("ERROR", "SQL : ".$sql);
        return $this->db->query($sql )->result();
    }


    function get_monthly_report($Date)
    {
        //         $param = array();
        $where = "";

        if($Date['startDate']) {
            $where .= " and (
		                	 ( mst_reserved_dt <> '00000000000000' AND
                              (STR_TO_DATE(mst_reserved_dt,'%Y%m%d%H%i%s') BETWEEN '".$Date['startDate']."-01 00:00:00"."' AND '".$Date['startDate']."-31 23:59:59"."')
                             ) OR
                             ( mst_reserved_dt = '00000000000000' AND
                              (mst_datetime BETWEEN  '".$Date['startDate']."-01 00:00:00"."' AND '".$Date['startDate']."-31 23:59:59"."')
                             )
                            )";
            //             array_push($param, $Date['startDate']." 00:00:00" );
            //             array_push($param, $Date['startDate']." 23:59:59" );
            //             array_push($param, $Date['startDate']." 00:00:00" );
            //             array_push($param, $Date['startDate']." 23:59:59" );
        }

        if($Date['pf_key'] && $Date['pf_key'] != 'ALL') {
            //$where .=  " and mst_profile='".$Date['pf_key']."' ";
            $where .=  " and mst_mem_id='".$Date['pf_key']."' ";
        }

        $sql = "select
					mem_username,
                    (select cm.mem_username from cb_member cm inner join cb_member_register cmr on cm.mem_id = cmr.mrg_recommend_mem_id where cmr.mem_id = mst_mem_id) as parent_name,
					sum(mst_qty) mst_qty,
                    sum(reserved_qty) reserved_qty,
					sum(mst_ft) mst_ft,
					sum(mst_ft_img) mst_ft_img,
                    sum(mst_ft_wide_img) mst_ft_wide_img,
					sum(mst_at) mst_at,
					sum(mst_ai) mst_ai,
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
					sum(mst_err_ai) err_ai,
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
                    mem_userid,
                    group_concat(mst_id ) as mst_id,
                    mst_mem_id,
                    (select mrg_recommend_mem_id from cb_member_register where mem_id = mst_mem_id) as parent_mem_id
                from(
                select mem_username,
					mst_qty,
                    (case when mst_reserved_dt <> '00000000000000' AND mst_reserved_dt > DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') then mst_qty else  0 end) reserved_qty,
					mst_ft,
					case when (mst_type1 = 'fti') then mst_ft_img else 0 end as mst_ft_img,
                    case when (mst_type1 = 'ftw') then mst_ft_img else 0 end as mst_ft_wide_img,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_at else 0 end as mst_at,
					case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_at else 0 end as mst_ai,
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
					case when (mst_type2 = 'wc') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt,
					case when (mst_type2 = 'wcs') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt_sms,
					case when (mst_type2 = 'wcm') then (mst_smt + case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end) else 0 end as mst_smt_mms,
					case when (mst_type1 = 'at' or mst_type2 = 'at') then mst_err_at else 0 end as mst_err_at,
					case when (mst_type1 = 'ai' or mst_type2 = 'ai') then mst_err_at else 0 end as mst_err_ai,
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
					case when (mst_type2 = 'wc') then mst_err_smt else 0 end as mst_err_smt,
					case when (mst_type2 = 'wcs') then mst_err_smt else 0 end as mst_err_smt_sms,
					case when (mst_type2 = 'wcm') then mst_err_smt else 0 end as mst_err_smt_mms,
					-- mst_wait,
                    case when (mst_reserved_dt > current_timestamp() + 0) then 0 else mst_wait end mst_wait,
					mem_userid,
                	mst_id,
                	mst_mem_id
				from cb_wt_msg_sent_v where mst_mem_id in (
                					WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
                    	UNION ALL
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                    )
                    SELECT distinct mem_id
                    FROM cmem
                    UNION ALL
                    SELECT ".$this->member->item('mem_id')." mem_id
                    )  ".$where.") temp group by mem_username, mst_mem_id order by 2, 1";

        //         $sql = "select
        // 					mem_username,
        //                     (select cm.mem_username from cb_member cm inner join cb_member_register cmr on cm.mem_id = cmr.mrg_recommend_mem_id where cmr.mem_id = mst_mem_id) as parent_name,
        // 					sum(mst_qty) mst_qty,
        // 					sum(mst_ft) mst_ft,
        // 					sum(mst_ft_img) mst_ft_img,
        // 					sum(mst_at) mst_at,
        // 					sum(mst_sms) mst_sms,
        // 					sum(mst_lms) mst_lms,
        // 					sum(mst_mms) mst_mms,
        //                     sum(mst_grs) mst_grs,
        //                     sum(mst_grs_sms) mst_grs_sms,
        //                     sum(mst_nas) mst_nas,
        //                     sum(mst_nas_sms) mst_nas_sms,
        //                     sum(mst_015) mst_015,
        // 					sum(mst_phn) mst_phn,
        // 					sum(mst_err_at) err_at,
        // 					sum(mst_err_ft) err_ft,
        // 					sum(mst_err_ft_img) err_ft_img,
        // 					sum(mst_err_sms) qty_sms,
        // 					sum(mst_err_lms) qty_lms,
        // 					sum(mst_err_mms) qty_mms,
        //                     sum(mst_err_grs) qty_grs,
        //                     sum(mst_err_grs_sms) qty_grs_sms,
        //                     sum(mst_err_nas) qty_nas,
        //                     sum(mst_err_nas_sms) qty_nas_sms,
        //                     sum(mst_err_015) qty_015,
        // 					sum(mst_err_phn) qty_phn,
        // 					sum(mst_wait) qty_wait,
        //                     mem_userid,
        //                     group_concat(mst_id ) as mst_id,
        //                     mst_mem_id,
        //                     (select mrg_recommend_mem_id from cb_member_register where mem_id = mst_mem_id) as parent_mem_id
        // 				from cb_wt_msg_sent_v where mst_mem_id in (
        //                 					WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
        //                     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //                     	FROM cb_member_register cmr
        //                     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //                     	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
        //                     	UNION ALL
        //                     	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
        //                     	FROM cb_member_register cmr
        //                     	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
        //                     	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
        //                     )
        //                     SELECT distinct mem_id
        //                     FROM cmem
        //                     ) ".$where." group by mem_username, mst_mem_id order by 2, 1";
        //log_message("ERROR", "SQL : ".$sql);
        return $this->db->query($sql )->result();
    }


    /* CB 모듈에서 무통장 입금이 확인저장되면 amt로그에 기록 - 2021-11-05 선충전관련 수정 */
    function deposit_appr($mem_id, $dep_id, $dep_kind = "")
    {
        $deposit = $this->db->query("select a.*, b.mem_userid from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id where a.mem_id=? and a.dep_id=?", array($mem_id, $dep_id))->row();
        /* 포인트 가중치를 입금자의 것으로 적용해야함! */
        $adPoint = 0;
        $weight = $this->db->query("select * from cb_wt_member_addon where mad_mem_id=?", array($deposit->mem_id))->row_array();
        if($weight && count($weight) > 0) {
            $adPoint = intval($deposit->dep_deposit * ($weight["mad_weight".($this->deposit[$deposit->dep_deposit] + 1)] / 100));
        }
        if($deposit && $deposit->dep_status > 0 && $deposit->dep_deposit != 0) {
            $dData['amt_datetime'] = cdate('Y-m-d H:i:s');
            if(empty($dep_kind) || $dep_kind == '40'){ //선충전차감 amt_kind를 차감으로 처리
                $dData['amt_kind'] = ($deposit->dep_deposit > 0) ? '1' : '2';
            }else{
                $dData['amt_kind'] = $dep_kind;
            }

            $dData['amt_amount'] = abs($deposit->dep_deposit);
            $dData['amt_point'] = $adPoint;
            $dData['amt_memo'] = $deposit->dep_content;
            $dData['amt_reason'] = $deposit->dep_id;
            $this->db->insert("cb_amt_".$deposit->mem_userid, $dData);
            if ($this->db->error()['code'] > 0) { return 0; }
            //- 충전인 경우만 보너스 포인트 처리
            if($deposit->dep_deposit > 0) {
                /* 포인트 보너스 */
                $sql = "update cb_member set mem_point = ifnull(mem_point, 0) + ".$adPoint." where mem_id=?";
                $this->db->query($sql, array($deposit->mem_id));
                $pData = array();
                $pData['mem_id'] = $deposit->mem_id;
                $pData['poi_datetime'] = cdate('Y-m-d H:i:s');
                $pData['poi_content'] = "예치금 보너스 적립";
                $pData['poi_point'] = $adPoint;
                $pData['poi_type'] = "deposit";
                $pData['poi_related_id'] = $deposit->mem_id;
                $pData['poi_action'] = "예치금충전(".number_format($deposit->dep_deposit).")";
                $this->db->insert("cb_point", $pData);
                if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
            }
        }
        return 0;
    }

    function get_partner_list($param, $where, $page=1, $perpage=20, $flag=false) {
        $isAdmin = 'N';
        /*
         if($this->member->item("mem_level")>=100) {
         $param = array_merge(array($param[0]), $param);
         //$param = array_merge(array($param[0], $param[0]), $param);
         $isAdmin = 'Y';
         }
         */

        $sql = "
                WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	 WHERE cm.mem_id = ? AND cmr.mem_id <> cm.mem_id
                    UNION ALL
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	  JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                	)
                SELECT count(1) as cnt, d.mdd_dormant_flag
                FROM cmem c
                INNER JOIN cb_member a on a.mem_id = c.mem_id
                                       and a.mem_useyn = 'Y'
                LEFT JOIN cb_member_dormant_dhn d on a.mem_id = d.mem_id
                LEFT JOIN cb_wt_voucher_addon e on a.mem_id = e.vad_mem_id
			where ".$where;
        // log_message("error", "1111111 sql : ".$sql);
        $this->total_rows = $this->db->query($sql, $param )->row()->cnt;


        /*
         $this->total_rows = $this->get_table_count("cb_member a inner join cb_member_register b on a.mem_id=b.mem_id
         inner join
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ?) initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         ".(($this->member->item("mem_level")>=100) ? "union select ? mem_id, ? mrg_recommend_mem_id" : "")."
         ) c on a.mem_id = c.mem_id", $where, $param);
         */
        if ($flag){
            $sql2 = "LEFT JOIN (
                SELECT
                    mst_mem_id,
                    MAX(mst_datetime) AS max_date
                FROM
                    cb_wt_msg_sent
                WHERE
                    /* mst_reserved_dt = '00000000000000'
                    AND */
                        mst_qty >= 10
                        AND
                        (mst_reserved_dt = '00000000000000' OR DATE_FORMAT(mst_reserved_dt,'%Y-%m-%d %H:%i:%s') < NOW())
                        AND
                        mst_sent_platform = 'S'
                GROUP BY
                    mst_mem_id
            ) g ON a.mem_id = g.mst_mem_id";
        } else {
            $sql2 = '';
        }
        $sql = "
                WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	 WHERE cm.mem_id = ? AND cmr.mem_id <> cm.mem_id
                    UNION ALL
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	  JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                	)
                SELECT a.*,
                       a.mem_deposit total_deposit,
                       c.parent_id AS mrg_recommend_mem_id,
                       e.vad_mem_id,
                		 IFNULL(
                		   (SELECT mem_username
                			   FROM cb_member
                			  WHERE mem_id = c.parent_id),
                			 '') mrg_recommend_mem_username, d.mdd_dormant_flag,
                       e.*,
                       f.*,
                       " . ($flag ? 'g.max_date,' : '') . "
                       h.pre_cash,
                       (select ds_name from dhn_salesperson2 where a.mem_management_mng_id = ds_id) as ds_name
                FROM cmem c
                INNER JOIN cb_member a on a.mem_id = c.mem_id
                                       and a.mem_useyn = 'Y'
                LEFT JOIN cb_member_dormant_dhn d on a.mem_id = d.mem_id
                LEFT JOIN cb_wt_voucher_addon e on a.mem_id = e.vad_mem_id
                LEFT JOIN cb_wt_member_addon f on a.mem_id = f.mad_mem_id
                " . $sql2 . "
                LEFT JOIN cb_pre_deposit h ON a.mem_id = h.mem_id
			where ".$where."
			order by c.parent_id, a.mem_level desc, a.mem_id desc, a.mem_username limit ".(($page - 1) * $perpage).", ".$perpage;
            // log_message("error", "1111111 sql : ".$sql);
        /*
         $sql = "
         select a.*,
         a.mem_deposit total_deposit, c.mrg_recommend_mem_id, ifnull((select mem_username from cb_member where mem_id=c.mrg_recommend_mem_id), '') mrg_recommend_mem_username
         from cb_member a
         inner join cb_member_register b on a.mem_id=b.mem_id
         inner join
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ?) initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         ".(($this->member->item("mem_level")>=100) ? "union select ? mem_id, ? mrg_recommend_mem_id" : "")."
         ) c on a.mem_id = c.mem_id
         where ".$where."
         order by c.mrg_recommend_mem_id, a.mem_level desc, a.mem_username limit ".(($page - 1) * $perpage).", ".$perpage;
         */
        // log_message("error", "sql123 : ".$sql);
        //echo $sql ."<br>";
        $this->lists = $this->db->query($sql, $param)->result();
        return $this;
    }

    function get_no_partner_list($param, $where, $page=1, $perpage=20) {
        $isAdmin = 'N';
        /*
         if($this->member->item("mem_level")>=100) {
         $param = array_merge(array($param[0]), $param);
         //$param = array_merge(array($param[0], $param[0]), $param);
         $isAdmin = 'Y';
         }
         */

        $sql = "
                WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	 WHERE cm.mem_id = ? AND cmr.mem_id <> cm.mem_id
                    UNION ALL
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	  JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                	)
                SELECT count(1) as cnt, d.mdd_dormant_flag
                FROM cmem c
                INNER JOIN cb_member a on a.mem_id = c.mem_id
                                       and a.mem_useyn = 'N'
                LEFT JOIN cb_member_dormant_dhn d on a.mem_id = d.mem_id
                LEFT JOIN cb_wt_voucher_addon e on a.mem_id = e.vad_mem_id
			where ".$where;
        // log_message("error", "1111111 sql : ".$sql);
        $this->total_rows = $this->db->query($sql, $param )->row()->cnt;


        /*
         $this->total_rows = $this->get_table_count("cb_member a inner join cb_member_register b on a.mem_id=b.mem_id
         inner join
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ?) initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         ".(($this->member->item("mem_level")>=100) ? "union select ? mem_id, ? mrg_recommend_mem_id" : "")."
         ) c on a.mem_id = c.mem_id", $where, $param);
         */
        $sql = "
                WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	 WHERE cm.mem_id = ? AND cmr.mem_id <> cm.mem_id
                    UNION ALL
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	  JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                	)
                SELECT a.*,
                       a.mem_deposit total_deposit,
                       c.parent_id AS mrg_recommend_mem_id,
                       e.vad_mem_id,
                		 IFNULL(
                		   (SELECT mem_username
                			   FROM cb_member
                			  WHERE mem_id = c.parent_id),
                			 '') mrg_recommend_mem_username, d.mdd_dormant_flag,
                       e.*,
                       f.*,
                       g.max_date,
                       h.pre_cash,
                       (select ds_name from dhn_salesperson2 where a.mem_management_mng_id = ds_id) as ds_name
                FROM cmem c
                INNER JOIN cb_member a on a.mem_id = c.mem_id
                                       and a.mem_useyn = 'N'
                LEFT JOIN cb_member_dormant_dhn d on a.mem_id = d.mem_id
                LEFT JOIN cb_wt_voucher_addon e on a.mem_id = e.vad_mem_id
                LEFT JOIN cb_wt_member_addon f on a.mem_id = f.mad_mem_id
                LEFT JOIN (
                    SELECT
                    	mst_mem_id,
                    	MAX(mst_datetime) AS max_date
                    FROM
                    	cb_wt_msg_sent
                    WHERE
                    	/* mst_reserved_dt = '00000000000000'
                    	AND */
                    		mst_qty >= 10
                            AND
                            DATE_FORMAT(mst_reserved_dt,'%Y-%m-%d %H:%i:%s') < NOW()
                            AND
                            mst_sent_platform = 'S'
                    GROUP BY
                    	mst_mem_id
                ) g ON a.mem_id = g.mst_mem_id
                LEFT JOIN cb_pre_deposit h ON a.mem_id = h.mem_id
			where ".$where."
			order by c.parent_id, a.mem_level desc, a.mem_id desc, a.mem_username limit ".(($page - 1) * $perpage).", ".$perpage;
        /*
         $sql = "
         select a.*,
         a.mem_deposit total_deposit, c.mrg_recommend_mem_id, ifnull((select mem_username from cb_member where mem_id=c.mrg_recommend_mem_id), '') mrg_recommend_mem_username
         from cb_member a
         inner join cb_member_register b on a.mem_id=b.mem_id
         inner join
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ?) initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         ".(($this->member->item("mem_level")>=100) ? "union select ? mem_id, ? mrg_recommend_mem_id" : "")."
         ) c on a.mem_id = c.mem_id
         where ".$where."
         order by c.mrg_recommend_mem_id, a.mem_level desc, a.mem_username limit ".(($page - 1) * $perpage).", ".$perpage;
         */
        // log_message("error", "sql123 : ".$sql);
        //echo $sql ."<br>";
        $this->lists = $this->db->query($sql, $param)->result();
        return $this;
    }

    function get_sales_partner_list($param, $where, $page=1, $perpage=20) {
        $isAdmin = 'N';

        $sql = "
                SELECT count(1) as cnt, d.mdd_dormant_flag, ? as mid
                FROM cb_member a
                LEFT JOIN cb_member_dormant_dhn d on a.mem_id = d.mem_id
                LEFT JOIN cb_wt_voucher_addon e on a.mem_id = e.vad_mem_id
                LEFT JOIN cb_wt_member_addon f on a.mem_id = f.mad_mem_id
                LEFT JOIN cb_member_register c on a.mem_id = c.mem_id

			where ".$where;

        $this->total_rows = $this->db->query($sql, $param)->row()->cnt;

        $sql = "

                SELECT a.*, ? as mid,
                       a.mem_deposit total_deposit,
                       c.mrg_recommend_mem_id,
                       e.vad_mem_id,
                		 IFNULL(
                		   (SELECT mem_username
                			   FROM cb_member
                			  WHERE mem_id = c.mrg_recommend_mem_id),
                			 '') mrg_recommend_mem_username, d.mdd_dormant_flag,
                       e.*,
                       f.*,
                       g.max_date,
                       h.pre_cash,
                       (select ds_name from dhn_salesperson2 where a.mem_management_mng_id = ds_id) as ds_name
                from
                cb_member a
                LEFT JOIN cb_member_dormant_dhn d on a.mem_id = d.mem_id
                LEFT JOIN cb_wt_voucher_addon e on a.mem_id = e.vad_mem_id
                LEFT JOIN cb_wt_member_addon f on a.mem_id = f.mad_mem_id
                LEFT JOIN cb_member_register c on a.mem_id = c.mem_id
                LEFT JOIN (
                    SELECT
                    	mst_mem_id,
                    	MAX(mst_datetime) AS max_date
                    FROM
                    	cb_wt_msg_sent
                    WHERE
                    	/* mst_reserved_dt = '00000000000000'
                    	AND */
                    		mst_qty >= 10
                            AND
                            DATE_FORMAT(mst_reserved_dt,'%Y-%m-%d %H:%i:%s') < NOW()
                            AND
                            mst_sent_platform = 'S'
                    GROUP BY
                    	mst_mem_id
                ) g ON a.mem_id = g.mst_mem_id
                LEFT JOIN cb_pre_deposit h ON a.mem_id = h.mem_id
			where ".$where."
			order by c.mrg_recommend_mem_id, a.mem_level desc, a.mem_id desc, a.mem_username limit ".(($page - 1) * $perpage).", ".$perpage;


        $this->lists = $this->db->query($sql, $param)->result();
        return $this;
    }

    function get_partners($mem_id) {

        $sql = "select a.* from cb_member a inner join
			(WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = '".$mem_id."' and cmr.mem_id <> cm.mem_id
                    	UNION ALL
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                    )
                    SELECT distinct mem_id
                    FROM cmem
                    UNION ALL
                    SELECT ".$mem_id." mem_id
                    ) b on a.mem_id = b.mem_id
			order by a.mem_username";

        $lists = $this->db->query($sql)->result();
        return $lists;
    }

    // 2023-03-03 영업자 하위 partner가져오기.
    // $option S : 영업자 담당자, M : 관리 담당자, A : 전체
    function get_sales_partners($mem_id, $option) {
        $sql = "select count(1) as cnt from cb_dhn_sales_member where dsm_mem_id = '".$mem_id."'";

        $sales_cnt = $this->db->query($sql)->row()->cnt;

        if ($sales_cnt > 0) {
            $sql = "select * from cb_dhn_sales_member where dsm_mem_id = '".$this->member->item('mem_id')."'";
            $sales_id = $this->db->query($sql)->row()->ds_id;

            $sql = "SELECT * FROM cb_member WHERE mem_id = ".$mem_id ." ";
            if ($option == "S") {
                $sql .= "OR mem_sales_mng_id = ".$sales_id.";";
            } else if ($option == "M" ) {
                $sql .= "OR mem_management_mng_id = ".$sales_id.";";
            }  else if ($option == "O" ) {
                $sql .= "OR (mem_management_mng_id = ".$sales_id." AND mem_sales_mng_id <> mem_management_mng_id);";
            } else {
                $sql .= "OR mem_management_mng_id = ".$sales_id." OR mem_sales_mng_id = ".$sales_id.";";
            }
        } else {
            $sql = "select a.* from cb_member a inner join
    			(WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                        	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                        	FROM cb_member_register cmr
                        	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                        	WHERE cm.mem_id = '".$mem_id."' and cmr.mem_id <> cm.mem_id
                        	UNION ALL
                        	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                        	FROM cb_member_register cmr
                        	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                        	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                        )
                        SELECT distinct mem_id
                        FROM cmem
                        UNION ALL
                        SELECT ".$mem_id." mem_id
                        ) b on a.mem_id = b.mem_id
    			order by a.mem_username";
        }
        $lists = $this->db->query($sql)->result();
        return $lists;
    }

    function get_Children($mem_id) {
        $sql = "
            SELECT a.mem_id, a.mem_userid, d.mrg_recommend_mem_id as parent_id, a.mem_username
            from cb_member a
            	inner JOIN (
            		WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            			SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            			FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            			WHERE cm.mem_id = ".$mem_id." and cmr.mem_id <> cm.mem_id
            			UNION ALL
            			SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            			FROM cb_member_register cmr
            				JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            				JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            		)
            		SELECT DISTINCT mem_id
            		FROM cmem
            	) b on a.mem_id = b.mem_id
            	LEFT JOIN cb_member_register d on a.mem_id = d.mem_id
            WHERE a.mem_useyn = 'Y' AND a.mem_level <= 30
            order by a.mem_username";
        //log_message("ERROR", "get_Children : ".$sql);
        $lists = $this->db->query($sql)->result();
        return $lists;
    }

    function get_child_partner_info($mem_id, $where)
    {
        // $sql = "
		// select a.*
		// 	from cb_member a inner join
		// 		(select * from cb_member_register
		// 		where
		// 			FIND_IN_SET(mem_id,
		// 				(SELECT GROUP_CONCAT(memid SEPARATOR ',') memid FROM (
		// 					SELECT DISTINCT @pv:=(SELECT GROUP_CONCAT(mem_id SEPARATOR ',') FROM cb_member_register
		// 					WHERE FIND_IN_SET(mrg_recommend_mem_id, @pv)) AS memid FROM cb_member_register
		// 					JOIN (SELECT @pv:='".$mem_id."') tmp
		// 				) a)
		// 			) > 0  or mem_id='".$mem_id."'
		// 		) c on a.mem_useyn='Y' and a.mem_id = c.mem_id
		// 	where ".$where."
		// 	order by c.mrg_recommend_mem_id, a.mem_level desc, a.mem_username";

        $sql = "
            select a.*
            from cb_member a inner join
            	(WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            		SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            		FROM cb_member_register cmr
            			JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            		WHERE cm.mem_id = '".$mem_id."' AND cmr.mem_id <> '".$mem_id."'
            		UNION ALL
            		SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            		FROM cb_member_register cmr
            			JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            			JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            		)
            		SELECT DISTINCT mem_id, parent_id
            		FROM cmem
            		UNION ALL
                    select mem_id, (select mrg_recommend_mem_id from cb_member_register where mem_id = '".$mem_id."') as parent_id
                      from cb_member cm
                     where cm.mem_id = '".$mem_id."'
            	) c on a.mem_useyn='Y' and a.mem_id = c.mem_id
            where a.mem_level>=10
            order by c.parent_id, a.mem_level desc, a.mem_username
        ";
        // log_message("ERROR", "GET Child Partner Info : ".$sql);
        return $this->db->query($sql)->result();
    }

    function get_child_query($id) {
        // 주어진 회원과 하위 회원 모두를 반환
        $sql = "
			select  mem_id, mrg_recommend_mem_id
			from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
				(select @pv := {$id}) initialisation
			where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
			union select '{$id}' mem_id, '{$id}' mrg_recommend_mem_id
		";
    }

    function get_partner_profile() {
        $param = array($this->member->item('mem_id'), $this->member->item('mem_id')/*, $this->member->item('mem_id')*/);
        $sql = "select a.* from cb_wt_send_profile a inner join
			(WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = ? and cmr.mem_id <> cm.mem_id
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
                     where cm.mem_id = ?
                    ) b on a.spf_mem_id = b.mem_id
			where a.spf_use='Y' and a.spf_key<>'' and a.spf_status='A'
			order by a.spf_company, a.spf_friend
		";
        //log_message("ERROR", "SOF : ".$sql.", ID :".$param[0]);
        /*		$sql = "select a.* from cb_wt_send_profile a inner join
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ?) initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         union select ? mem_id, ? mrg_recommend_mem_id
         ) b on a.spf_mem_id = b.mem_id
         where a.spf_use='Y' and a.spf_key<>''
         order by a.spf_company, a.spf_friend
         ";*/
        return $this->db->query($sql, $param)->result();
    }

    function get_profile_group() {
        //$param = array($this->member->item('mem_id'), $this->member->item('mem_id')/*, $this->member->item('mem_id')*/);
        $sql = "select  * from cb_wt_send_profile_group where spg_p_mem_id is null";
        /*		$sql = "select a.* from cb_wt_send_profile a inner join
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ?) initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         union select ? mem_id, ? mrg_recommend_mem_id
         ) b on a.spf_mem_id = b.mem_id
         where a.spf_use='Y' and a.spf_key<>''
         order by a.spf_company, a.spf_friend
         ";*/
        return $this->db->query($sql)->result();
    }

    function get_partner_profile_count($uid, $where, $param) {
        $sql = "select count(1) as cnt
        	    from cb_wt_send_profile a inner join
        	    (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = '".$uid."' and cmr.mem_id <> cm.mem_id
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
                     where cm.mem_id = '".$uid."'
                    ) b on a.spf_mem_id = b.mem_id
        	        where a.spf_key<>'' and a.spf_use='Y' ".$where;

        $total_rows =  $this->db->query($sql, $param)->row()->cnt;
        /*		$page_cfg['total_rows'] = $this->Biz_model->get_table_count("cb_wt_send_profile a inner join
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ?) initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         union select ? mem_id, ? mrg_recommend_mem_id
         ) b on a.spf_mem_id = b.mem_id", "a.spf_key<>'' and a.spf_use='Y'", $param);*/
        //log_message("ERROR", "CNT where : ".$where);
        return $total_rows;
    }

    function get_partner_profile_list($uid, $page, $perpage, $where, $param) {
        $sql = "
			select a.*
			from cb_wt_send_profile a inner join
				(WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = '".$uid."' and cmr.mem_id <> cm.mem_id
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
                     where cm.mem_id = '".$uid."'
                    ) b on a.spf_mem_id = b.mem_id
			where a.spf_key<>'' and a.spf_use='Y' ".$where." order by a.spf_datetime desc limit ".($page * $perpage).", ".$perpage;
        //log_message("ERROR", "aaaa : ".$uid);
        //log_message("ERROR", "list where : ".$where);
        //log_message("ERROR", "sql where : ".$sql);
        /*		$sql = "
         select a.*
         from cb_wt_send_profile a inner join
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ?) initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         union select ? mem_id, ? mrg_recommend_mem_id
         ) b on a.spf_mem_id = b.mem_id
         where a.spf_key<>'' and a.spf_use='Y' order by a.spf_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];*/
        return $this->db->query($sql, $param)->result();
    }

    function get_template_count($uid, $where, $param) {
        $total_rows = $this->get_table_count("cb_wt_template a inner join
				(WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = '".$uid."' and cmr.mem_id <> cm.mem_id
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
                     where cm.mem_id = '".$uid."'
                    ) b on a.tpl_mem_id = b.mem_id", $where, $param);
        return $total_rows;
    }

    function get_public_template_count($where, $param) {
        $total_rows = $this->get_table_count("cb_wt_template", $where, $param);
        return $total_rows;
    }

    function get_template_no_from_code($code, $pfkey) {
        $sql = "select tpl_id from cb_wt_template where tpl_code=? and tpl_profile_key=? and tpl_inspect_status='APR' and tpl_use='Y'";
        $query = $this->db->query($sql, array($code, $pfkey));
        if($query) {
            $row = $query->row();
            return $row->tpl_id;
        }
        return "";
    }

    function get_template_list($uid, $page, $perpage, $where, $param) {
        $sql = "
			select a.*, c.spf_friend, c.spf_key_type
			from cb_wt_template a inner join
				cb_wt_send_profile c on a.tpl_profile_key=c.spf_key and c.spf_use='Y' inner join
				(WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = '".$uid."' and cmr.mem_id <> cm.mem_id
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
                     where cm.mem_id = '".$uid."'
                    ) b on a.tpl_mem_id = b.mem_id
			where ".$where." order by tpl_datetime desc limit ".($page * $perpage).", ".$perpage;
        //log_message("ERROR", "get_template_list sql".$sql);
        return $this->db->query($sql, $param)->result();
    }

    function get_public_template_list($page, $perpage, $where, $param) {
        $sql = "
			select a.*, c.spf_friend, c.spf_key_type
			from cb_wt_template a inner join
				cb_wt_send_profile c on a.tpl_profile_key=c.spf_key and c.spf_use='Y' where ".$where." order by tpl_datetime desc limit ".($page * $perpage).", ".$perpage;
        //log_message("ERROR", "get_public_template_list sql".$sql);
        return $this->db->query($sql, $param)->result();
    }

    function get_sub_manager($mem_id, $level = null)
    {
        if (!empty($level)){
            $lvl = $level;
        } else {
            $lvl = $this->manager_lv;
        }
        //echo $mem_id ."<br>";
        if($mem_id == "2") $mem_id = "3";
        $sql = "
			select a.mem_id, a.mem_userid, a.mem_username from cb_member a inner join
			(WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                    	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                    	FROM cb_member_register cmr
                    	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                    	WHERE cm.mem_id = '".$mem_id."' and cmr.mem_id <> cm.mem_id
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
                     where cm.mem_id = '".$mem_id."'
                    ) b on a.mem_id=b.mem_id
			where a.mem_useyn='Y' and a.mem_level >= ".$lvl."
			order by a.mem_username, a.mem_userid
		";
        return $this->db->query($sql)->result();
    }

    function get_sales_sub_manager($mem_id, $ds_id)
    {
        //echo $mem_id ."<br>";
        if($mem_id == "2") $mem_id = "3";
        $sql = "
			select a.mem_id, a.mem_userid, a.mem_username from cb_member a inner join
			(select distinct(mrg_recommend_mem_id) as mrg_recommend_mem_id from cb_member_register where mem_id in (select mem_id from cb_member cbm where
                    cbm.mem_id <> '".$mem_id."' AND ( cbm.mem_management_mng_id = '".$ds_id."' OR  cbm.mem_sales_mng_id = '".$ds_id."'
            ))) b ON b.mrg_recommend_mem_id = a.mem_id
			where a.mem_useyn='Y'
			order by a.mem_username, a.mem_userid
		";
        return $this->db->query($sql)->result();
    }

    function get_child_member($mem_id, $level="")
    {
        // $sql = "
		// 	SELECT a.mem_userid, a.mem_username from cb_member a inner join
		// 	(	select mem_id, mrg_recommend_mem_id from cb_member_register
		// 		where
		// 			FIND_IN_SET(mem_id,
		// 				(SELECT GROUP_CONCAT(memid SEPARATOR ',') memid FROM (
		// 					SELECT @pv:=(SELECT GROUP_CONCAT(mem_id SEPARATOR ',') FROM cb_member_register
		// 					WHERE FIND_IN_SET(mrg_recommend_mem_id, @pv)) AS memid FROM cb_member_register
		// 					JOIN (SELECT @pv:=".$mem_id.") tmp
		// 				) a)
		// 			) > 0
		// 		) b on a.mem_id=b.mem_id
		// ".(($level) ? " where a.mem_useyn='Y' and a.mem_level >= '".$level."'" : "")."
		// 	order by a.mem_username, a.mem_userid
		// ";


        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	 WHERE cm.mem_id = ".$mem_id." and cmr.mem_id <> cm.mem_id
                    UNION ALL
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	  JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                	)
			SELECT a.mem_userid, a.mem_username from cb_member a
		".(($level) ? " where a.mem_useyn='Y' and a.mem_level >= '".$level."' and a.mem_level <> '17'" : "")."
			order by a.mem_username, a.mem_userid
		";



        // log_message("ERROR", "/application/models/Biz_model/get_child_member > ". $sql);
        return $this->db->query($sql)->result();
    }

    function get_member_from_profile_key($key) {
        $result = $this->db->query("select * from cb_wt_send_profile where spf_key=? and spf_use='Y' and spf_key<>''", array($key));
        return ($result) ? $result->row() : '';
    }

    function get_member_profile_key($mem_id) {
        $result = $this->db->query("select * from cb_wt_send_profile where spf_mem_id=? and spf_use='Y' and spf_key<>''", array($mem_id));
        return ($result) ? $result->row() : '';
    }

    function get_member_profile_key_dhn($mem_id) {
        $result = $this->db->query("select * from cb_wt_send_profile_dhn where spf_mem_id=? and spf_use='Y' and spf_key<>''", array($mem_id));
        return ($result) ? $result->row() : '';
    }

    function check_template_code_dup($profile_key, $code) {
        $result = $this->db->query("select count(*) cnt from cb_wt_template where tpl_profile_key=? and tpl_code=? and tpl_use='Y'", array($profile_key, $code))->row();
        return $result->cnt;
    }

    function get_customer_from_id($userid, $id)
    {
        if(trim($id)=='') return "";
        $sql = "select * from ;".$userid." where ab_id in (".$id.") and ab_status='1'";
        return $this->db->query($sql)->result();
    }

    function check_sending_kakao() {
        $sql = "select count(*) CNT from TBL_REQUEST";
        $rs1 = $this->db->query($sql)->row();
        $totalrow = $rs1->CNT;
        $regdate = "";
        if($totalrow > 0) {
            $sql = "select REG_DT from TBL_REQUEST ORDER BY REG_DT LIMIT 1";
            $rs2 = $this->db->query($sql)->row();
            $regdate = $rs2->REG_DT;
        }
        return array("agent"=>"kakao", "name"=>"카카오", "row"=>$totalrow, "data"=>$regdate);
    }

    function check_sending_agent() {
        $result = array();
        $CI = & get_instance();
        foreach($CI->config->config['send_agent'] AS $key => $val) {
            $sql = "select count(*) CNT from ".$CI->config->config['send_agent_table'][$key];
            $rs1 = $this->db->query($sql)->row();
            $totalrow = $rs1->CNT;
            $regdate = "";
            if($totalrow > 0) {
                $sql = "select ".$CI->config->config['send_agent_regdate'][$key]." from ".$CI->config->config['send_agent_table'][$key]." ORDER BY ".$CI->config->config['send_agent_regdate'][$key]." LIMIT 1";
                $rs2 = $this->db->query($sql)->row();
                $regdate = $rs2->{$CI->config->config['send_agent_regdate'][$key]};
            }
            array_push($result, array("agent"=>$key, "name"=>$CI->config->config['send_agent_name'][$key], "row"=>$totalrow, "data"=>$regdate));
        }
        return $result;
    }

    function sending_wait_remove($time, $agent) {
        $where = "";
        $CI = & get_instance();
        if($time != "all") {
            $where = $CI->config->config['send_agent_regdate'][$agent]."<'".$dt = date("Y-m-d H:i:s",strtotime ("-".$time." hours"))."'";
        }
        if($CI->config->config['send_agent_fail'][$agent]) {
            $sql = $CI->config->config['send_agent_fail'][$agent].(($where) ? " where ".$where : "");
            $this->db->query($sql);
            if ($this->db->error()['code'] < 1) {
                return json_encode(array("code"=>"ok", "message"=>""));
            } else {
                return json_encode(array("code"=>"fail", "message"=>$this->db->error()['message']));
            }
        } else {
            return json_encode(array("code"=>"fail", "message"=>"해당 발신 서비스는 대기열 삭제를 지원하지 않습니다."));
        }
        return json_encode(array("code"=>"fail", "message"=>$sql));
    }

    function get_sebdbox_from_history($id, $kind) {
        $sql = "select * from cb_wt_msg_sent where mst_kind=? and mst_id=?";
        return $this->db->query($sql, array($kind, $id))->row();
    }

    function get_sebdbox_from_save($userid, $id, $kind) {
        $sql = "select * from dhn_save.cb_save_".$userid." where cs_type=? and cs_id=?";
        return $this->db->query($sql, array($kind, $id))->row();
    }

    function make_msg_log_table($userid)
    {
        /*
         스윗트렉커의 에이전트 결과 테이블과 동일한 형태 유지 + mem_userid 필트를 추가함 17/12/6
         에이전트가 결과를 처리하여 TBL_REQUST_RESULT에 저장하면 이 테이블로 옮겨온다.
         */
        // 2019.01.23 이수환 ENGINE=MyISAM => ENGINE=InnoDB로 변경
        $sql = "
			CREATE TABLE IF NOT EXISTS `cb_msg_".$userid."`
			(
			  `MSGID` varchar(20) NOT NULL,
			  `AD_FLAG` varchar(1) DEFAULT NULL,
			  `BUTTON1` longtext,
			  `BUTTON2` longtext,
			  `BUTTON3` longtext,
			  `BUTTON4` longtext,
			  `BUTTON5` longtext,
			  `CODE` varchar(4) DEFAULT NULL,
			  `IMAGE_LINK` longtext,
			  `IMAGE_URL` longtext,
			  `KIND` varchar(1) DEFAULT NULL,
			  `MESSAGE` longtext,
			  `MESSAGE_TYPE` varchar(2) DEFAULT NULL,
			  `MSG` longtext ,
			  `MSG_SMS` longtext,
			  `ONLY_SMS` varchar(1) DEFAULT NULL,
			  `P_COM` varchar(2) DEFAULT NULL,
			  `P_INVOICE` varchar(100) DEFAULT NULL,
			  `PHN` varchar(15) NOT NULL,
			  `PROFILE` varchar(50) DEFAULT NULL,
			  `REG_DT` datetime NOT NULL,
			  `REMARK1` varchar(50) DEFAULT NULL,
			  `REMARK2` varchar(50) DEFAULT NULL,
			  `REMARK3` varchar(50) DEFAULT NULL,
			  `REMARK4` varchar(50) DEFAULT NULL,
			  `REMARK5` varchar(50) DEFAULT NULL,
			  `RES_DT` datetime DEFAULT NULL,
			  `RESERVE_DT` varchar(14) NOT NULL,
			  `RESULT` varchar(1) DEFAULT NULL,
			  `S_CODE` varchar(2) DEFAULT NULL,
			  `SMS_KIND` varchar(1) DEFAULT NULL,
			  `SMS_LMS_TIT` varchar(120) DEFAULT NULL,
			  `SMS_SENDER` varchar(15) DEFAULT NULL,
			  `SYNC` varchar(1) NOT NULL,
			  `TMPL_ID` varchar(30) DEFAULT NULL,
			  `mem_userid` varchar(50) default '',
			  `wide` char(1) default 'N',
			  PRIMARY KEY (`MSGID`),
              INDEX `cb_msg_".$userid."_IDX01` (`REMARK4`, `MESSAGE_TYPE`, `MSGID`),
              INDEX `cb_msg_".$userid."_IDX02` (`REMARK4`, `MSGID`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8
		";
        $this->db->query($sql);
        if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
    }

    function make_customer_book($userid)
    {
        $sql = "CREATE TABLE IF NOT EXISTS `cb_ab_".$userid."` (
		  `ab_id` 			bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
		  `ab_name` 		varchar(100)  			DEFAULT '' 		comment '고객명',
		  `ab_tel` 			varchar(15)   			DEFAULT '' 		comment '전화번호',
		  `ab_kind` 		varchar(50)   			DEFAULT '' 		comment '구분',
		  `ab_status` 		char(1)   				DEFAULT '1' 	comment '상태',
		  `ab_memo` 		varchar(1000) 			DEFAULT '' 		comment '메모',
		  `ab_group` 		varchar(1000) 			DEFAULT '' 		comment '고객분류',
		  `ab_group_id` 		bigint 			DEFAULT NULL 		comment '그룹번호',
		  `ab_send_count`	bigint 		  			DEFAULT '0'		COMMENT '발송횟수',
		  `ab_datetime` 	datetime 				DEFAULT NULL 	comment '등록일시',
		  `ab_last_datetime` datetime 				DEFAULT NULL 	comment '최종발송일시',
		  `ab_addr` 		varchar(256) 			DEFAULT '' 		comment '주소',
		  PRIMARY KEY (`ab_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
        $this->db->query($sql);
        if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
    }

    function make_user_image_table($userid)
    {
        $sql = "
			CREATE TABLE IF NOT EXISTS `cb_img_".$userid."` (
			  `img_id` 			bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
			  `img_filename` 	varchar(200)  			DEFAULT '' 		comment '파일명',
			  `img_url` 		varchar(200) 			default '' 		comment '이미지URL',
			  `img_originname` varchar(200)   		DEFAULT '' 		comment '실제이름',
			  `img_filesize` 	bigint unsigned		DEFAULT '0' 	comment '파일크기',
			  `img_width` 		int    unsigned  		DEFAULT '0' 	comment '넓이',
			  `img_height` 	int    unsigned		DEFAULT '0'		comment '높이',
			  `img_type` 		varchar(20) 			DEFAULT '' 		comment 'MIME',
			  `img_is_image`	char(1) 	  				DEFAULT '1'		COMMENT '이미지여부',
              `img_wide`	char(1) 	  				DEFAULT 'N'		COMMENT '와이드여부',
			  PRIMARY KEY (`img_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		";
        $this->db->query($sql);
        if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
    }

    function make_user_deposit_table($userid) {
        $exist = $this->db->query("show tables like 'cb_amt_".$userid."'")->result_array();
        if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
        //- 없으면 생성
        if(count($exist) < 1) {
            $sql = "
    			CREATE TABLE /*IF NOT EXISTS*/ `cb_amt_".$userid."` (
    			  `amt_datetime`	datetime   	  			DEFAULT null 	comment '발생일시',
    			  `amt_kind` 		char(1) 		  			DEFAULT '' 		COMMENT '구분(충전,사용,현금환불,사용취소)',
    			  `amt_amount` 	decimal(14,2) 			DEFAULT '0.0' 	comment '금액',
    			  `amt_deposit`	decimal(14,2) 			DEFAULT '0.0' 	comment '예치금사용금액',
    			  `amt_point` 		decimal(14,2) 			DEFAULT '0.0' 	comment '포인트사용금액',
    			  `amt_memo` 		varchar(100) 			DEFAULT '' 		comment '내용',
    			  `amt_reason` 	varchar(50) 			DEFAULT '' 		comment '근거자료',
    			  `amt_payback`	decimal(14,2)			default '0.0'	comment '수익금',
    			  `amt_admin`		decimal(14,2)			default '0.0'	comment '관리자단가',
    			  `amt_deduct`		tinyint(4)			default '1'	comment '+1.증가, -1.차감'
    			) ENGINE=MyISAM DEFAULT CHARSET=utf8
    		";
            $this->db->query($sql);
            if ($this->db->error()['code'] > 0) { return 0; }
            /*
             $sql = "
             CREATE TRIGGER ins_cb_amt_".$userid." BEFORE INSERT ON cb_amt_".$userid." FOR EACH ROW
             BEGIN
             if NEW.amt_kind IN ('1', '3', '4', '5', '6', '9') then
             set NEW.amt_deduct := 1;
             ELSE
             set NEW.amt_deduct := -1;
             END if;
             END ";
             */
            $sql = "
CREATE TRIGGER `ins_cb_amt_".$userid."` BEFORE INSERT ON `cb_amt_".$userid."`
FOR EACH ROW
BEGIN
	if NEW.amt_kind IN ('1', '3', '4','5','6','9') then
		set NEW.amt_deduct := 1;
	ELSE
		set NEW.amt_deduct := -1;
	END if;
END";
            //log_message("ERROR", "/application/models/Biz_model/make_user_deposit_table > ". $sql);
            $this->db->query($sql);
            if ($this->db->error()['code'] < 1) { return 1; } else { echo '<pre> :: ';print_r($this->db->error()); exit; return 0; }
        }
    }

    function make_send_cache_table($userid)
    {
        if($init) {
            $this->db->delete("sc_".$userid);
        }
        $sql = "
			CREATE TABLE IF NOT EXISTS  `cb_sc_".$userid."` (
			  `sc_id` 				bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
			  `sc_name` 			varchar(100) 			DEFAULT '' 		COMMENT '고객명',
			  `sc_tel` 				varchar(15) 			DEFAULT '' 		COMMENT '전화번호',
			  `sc_content` 		varchar(5000) 			DEFAULT '' 		COMMENT '내용(친구톡/알림톡)',
			  `sc_button1` 		varchar(600) 			DEFAULT '' 		COMMENT '버튼1',
			  `sc_button2` 		varchar(600) 			DEFAULT '' 		COMMENT '버튼2',
			  `sc_button3`			varchar(600) 			DEFAULT '' 		COMMENT '버튼3',
			  `sc_button4` 		varchar(600) 			DEFAULT '' 		COMMENT '버튼4',
			  `sc_button5` 		varchar(600) 			DEFAULT '' 		COMMENT '버튼5',
			  `sc_sms_yn` 			char(1) 					DEFAULT 'N' 	COMMENT 'SMS재발신여부',
			  `sc_lms_content` 	varchar(5000) 			DEFAULT '' 		COMMENT '내용LMS',
			  `sc_sms_callback`	varchar(15) 			DEFAULT '' 		COMMENT 'SMS발신번호',
			  `sc_img_url` 		varchar(1000) 			DEFAULT '' 		COMMENT '이미지URL',
			  `sc_img_link` 		varchar(1000) 			DEFAULT '' 		COMMENT '이미지Link',
			  `sc_template` 		varchar(30) 			DEFAULT ''		COMMENT '템플릿코드',
			  `sc_p_com`			varchar(5)				default ''		comment '택배사코드',
			  `sc_p_invoice`		varchar(100)			default ''		comment '택배송장번호',
			  `sc_s_code`			varchar(5)				default ''		comment '쇼핑몰코드',
			  `sc_reserve_dt`		varchar(14)				default ''		comment '예약전송일시',
			  `supplement`			text				default NULL,
			  `price`		int(11)			default NULL,
			  `currency_type`			char(3)				default NULL,
			  `title`		char(50)				default NULL,
			  PRIMARY KEY (`sc_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8
		";
        $this->db->query($sql);
        if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
    }

    function make_msg_save($userid)
    {
        $sql = "CREATE TABLE IF NOT EXISTS `dhn_save`.`cb_save_".$userid."` (
		  `cs_id` 			bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '식별번호',
		  `cs_datetime` 	datetime 				DEFAULT NULL 	comment '등록일시',
		  `cs_send` 		char(1)		 			DEFAULT 'N'		comment '발송여부',
		  `cs_subject` 	varchar(120)  			DEFAULT '' 		comment '제목',
		  `cs_type` 		char(2)					DEFAULT ''		comment '구분:알림톡,친구톡',
		  `cs_image_link` longtext					DEFAULT NULL	comment '이미지링크',
		  `cs_image_url`  longtext					DEFAULT NULL	comment '이미지URL',
		  `cs_kakao` 		longtext					DEFAULT NULL	comment '카카오내용',
		  `cs_button` 		varchar(5000)			DEFAULT ''		comment '카카오버튼',
		  `cs_resend` 		longtext					DEFAULT NULL	comment '재발신내용',
		  `cs_profile`		varchar(50)				DEFAULT ''		comment '프로필키',
		  `cs_tmpl_id`		varchar(30)				DEFAULT ''		comment '템플릿코드',
		  `cs_moddatetime` datetime 				DEFAULT NULL 	comment '수정일시',
		  `cs_useyn` 		char(1)		 			DEFAULT 'Y' 	comment '사용여부',
		  PRIMARY KEY (`cs_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
        $this->db->query($sql);
        if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
    }

    function get_partner_list_for_management($param, $where, $page=1, $perpage=20) {
        $isAdmin = 'N';
        /*
         if($this->member->item("mem_level")>=100) {
         $param = array_merge(array($param[0]), $param);
         //$param = array_merge(array($param[0], $param[0]), $param);
         $isAdmin = 'Y';
         }
         */

        $sql = "
                WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	 WHERE cm.mem_id = ? AND cmr.mem_id <> cm.mem_id
                    UNION ALL
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	  FROM cb_member_register cmr
                	  JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	  JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
                	)
                SELECT count(1) as cnt
                FROM cmem c
                INNER JOIN cb_member a on a.mem_id = c.mem_id
                                       and a.mem_useyn = 'Y'
                LEFT JOIN cb_member_dormant_dhn d on a.mem_id = d.mem_id
                LEFT JOIN (
                SELECT
                mst_mem_id,
                MAX(mst_datetime) AS max_date
                FROM
                cb_wt_msg_sent
                WHERE
                (mst_reserved_dt = '00000000000000' OR DATE_FORMAT(mst_reserved_dt,'%Y-%m-%d %H:%i:%s') < NOW())
                AND
                    mst_qty >= 10
                    AND
                    mst_sent_platform = 'S'
                GROUP BY
                mst_mem_id
                )  e ON a.mem_id = e.mst_mem_id

			where ".$where;
        // log_message("error", "1111111 sql : ".$sql);
        $this->total_rows = $this->db->query($sql, $param )->row()->cnt;


        /*
         $this->total_rows = $this->get_table_count("cb_member a inner join cb_member_register b on a.mem_id=b.mem_id
         inner join
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ?) initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         ".(($this->member->item("mem_level")>=100) ? "union select ? mem_id, ? mrg_recommend_mem_id" : "")."
         ) c on a.mem_id = c.mem_id", $where, $param);
         */
        $sql = "
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            WHERE cm.mem_id = ? AND cmr.mem_id <> cm.mem_id
            UNION ALL
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            SELECT a.*,
            a.mem_deposit total_deposit,
            a.mem_id AS memid,
            a.mem_userid AS userid,
            c.parent_id AS mrg_recommend_mem_id,
            IFNULL(
              (SELECT mem_username
                  FROM cb_member
                 WHERE mem_id = c.parent_id),
                '') mrg_recommend_mem_username,
            d.*,
            e.max_date,
            f.*,
            g.*
            FROM cmem c
            INNER JOIN cb_member a on a.mem_id = c.mem_id
                           and a.mem_useyn = 'Y'
            LEFT JOIN cb_member_dormant_dhn d on a.mem_id = d.mem_id
            LEFT JOIN cb_wt_voucher_addon f on a.mem_id = f.vad_mem_id
            LEFT JOIN cb_wt_member_addon g on a.mem_id = g.mad_mem_id
            LEFT JOIN (
            SELECT
            mst_mem_id,
            MAX(mst_datetime) AS max_date
            FROM
            cb_wt_msg_sent
            WHERE
            (mst_reserved_dt = '00000000000000' OR DATE_FORMAT(mst_reserved_dt,'%Y-%m-%d %H:%i:%s') < NOW())
            AND
                mst_qty >= 10
                AND
                mst_sent_platform = 'S'
            GROUP BY
            mst_mem_id
            ) e ON a.mem_id = e.mst_mem_id
			where ".$where."
			order by c.parent_id, a.mem_level desc, a.mem_id desc, a.mem_username limit ".(($page - 1) * $perpage).", ".$perpage;
        $this->lists = $this->db->query($sql, $param)->result();
        // log_message("error", $sql);
        return $this;
    }
}
?>
