<?php
class Api extends CB_Controller {
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
        $this->load->library(array('querystring'));
    }

    // function test(){
        // $db1 = $this->load->database("59g", true);
        // $db2 = $this->load->database("ppu", true);
        //
        // $sql = "
        //     SELECT
        //         *
        //     FROM
        //         cb_alimtalk_ums
        //     WHERE
        //         creation_date BETWEEN '2022-06-30 00:00:00' AND '2022-07-05 23:59:59'
        // ";
        //
        // $a = $db1->query($sql)->result();
        // $c = array();
        // foreach($a as $b){
        //     if ($b->link_type == "home") {
        //         $d = explode("/", $b->home_url)[3];
        //         array_push($c, array("short_url" => $d, "url" => "http://igenie2.co.kr/smart/shome/" . $b->home_code));
        //     } else if ($b->link_type == "smart"){
        //         $d = explode("/", $b->psd_url)[3];
        //         array_push($c, array("short_url" => $d, "url" => "http://igenie2.co.kr/smart/view/" . $b->psd_code));
        //     } else if ($b->link_type == "smartEditor"){
        //         $d = explode("/", $b->url)[3];
        //         array_push($c, array("short_url" => $d, "url" => "http://igenie2.co.kr/at/" . $b->short_url));
        //         $d = explode("/", $b->psd_url)[3];
        //         array_push($c, array("short_url" => $d, "url" => "http://igenie2.co.kr/smart/view/" . $b->psd_code));
        //     } else if ($b->link_type == "editor"){
        //         $d = explode("/", $b->url)[3];
        //         array_push($c, array("short_url" => $d, "url" => "http://igenie2.co.kr/at/" . $b->short_url));
        //     }
        // }
        //
        // $db2->insert_batch("cb_url_short_url", $c);
    // }

    public function get_mem_coin(){
        $mem_id = $this->input->post('mem_id');
        $mem_userid = $this->input->post('mem_userid');
        $sql ="		select sum(cnt_ft) cnt_ft,
            sum(cnt_ft_lms) cnt_ft_lms,
            sum(cnt_ft_sms) cnt_ft_sms,
            sum(cnt_ft_pms) cnt_ft_pms,
            sum(cnt_fti_pms) cnt_fti_pms,
            sum(cnt_ft_img) cnt_ft_img,
            sum(cnt_at) cnt_at,
            sum(cnt_at_lms) cnt_at_lms,
            sum(cnt_at_sms) cnt_at_sms,
            sum(cnt_ai) cnt_ai,
            sum(cnt_ai_lms) cnt_ai_lms,
            sum(cnt_ai_sms) cnt_ai_sms,
            -- sum(cnt_phn) cnt_phn,
            sum(cnt_pms) cnt_pms,
            sum(cnt_sms) cnt_sms,
            sum(cnt_lms) cnt_lms,
            sum(cnt_mms) cnt_mms
        from (
            select
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and ifnull(a.MSG_SMS, '')='' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ft,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ft_lms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ft_sms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wp2' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ft_pms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')<>'' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wp2' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_fti_pms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')<>'' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ft_img,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')='' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_at,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_at_lms,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_at_sms,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')='' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N'  then 1 else 0 end) cnt_ai,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ai_lms,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_ai_sms,
                -- sum(case when a.MESSAGE_TYPE='pn' then 1 else 0 end) cnt_phn,
                sum(case when a.MESSAGE_TYPE='pm' then 1 else 0 end) cnt_pms,
                sum(case when a.MESSAGE_TYPE='sm' then 1 else 0 end) cnt_sms,
                sum(case when a.MESSAGE_TYPE='lm' then 1 else 0 end) cnt_lms,
                sum(case when a.MESSAGE_TYPE='mm' then 1 else 0 end) cnt_mms
            from DHN_REQUEST a
            where a.REMARK2 = ? and a.RESERVE_DT <> '00000000000000'
            union all
            select
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and ifnull(a.MSG_SMS, '')='' then 1 else 0 end) cnt_ft,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 then 1 else 0 end) cnt_ft_lms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 then 1 else 0 end) cnt_ft_sms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')='' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wp2' then 1 else 0 end) cnt_ft_pms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')<>'' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wp2' then 1 else 0 end) cnt_fti_pms,
                sum(case when a.MESSAGE_TYPE='ft' and ifnull(a.IMAGE_URL, '')<>'' then 1 else 0 end) cnt_ft_img,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')='' then 1 else 0 end) cnt_at,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 then 1 else 0 end) cnt_at_lms,
                sum(case when a.MESSAGE_TYPE='at' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 then 1 else 0 end) cnt_at_sms,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')='' then 1 else 0 end) cnt_ai,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 2 then 1 else 0 end) cnt_ai_lms,
                sum(case when a.MESSAGE_TYPE='ai' and ifnull(a.MSG_SMS, '')<>'' and (select CHAR_LENGTH(mst_type2) from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 3 then 1 else 0 end) cnt_ai_sms,
                -- sum(case when a.MESSAGE_TYPE='ph' then 1 else 0 end) cnt_phn,
                sum(case when a.MESSAGE_TYPE='ph' and P_INVOICE='SMART' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wp2' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_pms,
                sum(case when a.MESSAGE_TYPE='ph' and P_INVOICE='SMART' and a.sms_kind ='S' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wcs' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_sms,
                sum(case when a.MESSAGE_TYPE='ph' and P_INVOICE='SMART' and a.sms_kind ='L' and (select mst_type2 from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'wc' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_lms,
                sum(case when a.MESSAGE_TYPE='ph' and P_INVOICE='SMART' and a.sms_kind ='M' and (select mst_sent_voucher from cb_wt_msg_sent cwms where mst_id=a.REMARK4) = 'N' then 1 else 0 end) cnt_mms
            from DHN_REQUEST_RESULT a
            where a.REMARK2 = ? and a.RESERVE_DT <> '00000000000000'
            ) as b";
        $query = $this->db->query($sql, array($mem_id, $mem_id));
        $mem = $this->get_member($mem_userid, true);
        if($mem->mem_pay_type == 'A' || $mem->mem_pay_type == 'T') {
            $mCoin = 999999999;
        } else {
            //$sql = "SELECT
        	//		SUM(amt_amount * amt_deduct) amt
        	//	FROM cb_amt_".$mem_userid;
            //$query = $this->db->query($sql);
            //$wait = $query->row();

            //$mCoin = $wait->amt;
            $mCoin = /*$mem->mem_point +*/ $mem->total_deposit;			/* 포인트 + 예치금 */
        }

        if($query) {	/* 발송대기중인 메시지의 요금을 차감한다. : lms 기본 발송 -> phn */
            $wait = $query->row();
            if($wait->cnt_ft) { $mCoin -= ($wait->cnt_ft * $this->price_ft); }
            if($wait->cnt_ft_lms) { $mCoin -= ($wait->cnt_ft_lms * $this->price_smt); }
            if($wait->cnt_ft_pms) { $mCoin -= ($wait->cnt_ft_pms * $this->price_ft_img); }
            if($wait->cnt_fti_pms) { $mCoin -= ($wait->cnt_fti_pms * $this->price_ft_img); }
            if($wait->cnt_ft_sms) { $mCoin -= ($wait->cnt_ft_lms * $this->mad_price_ft); }
            if($wait->cnt_ft_img) { $mCoin -= ($wait->cnt_ft_img * $this->price_ft_img); }
            if($wait->cnt_at) { $mCoin -= ($wait->cnt_at * $this->price_at); }
            if($wait->cnt_at_lms) { $mCoin -= ($wait->cnt_at_lms * $this->price_smt); }
            if($wait->cnt_at_sms) { $mCoin -= ($wait->cnt_at_lms * $this->price_smt_sms); }
            if($wait->cnt_ai) { $mCoin -= ($wait->cnt_ai * $this->price_at); }
            if($wait->cnt_ai_lms) { $mCoin -= ($wait->cnt_ai_lms * $this->price_smt); }
            if($wait->cnt_ai_sms) { $mCoin -= ($wait->cnt_ai_lms * $this->price_smt_sms); }
            //if($wait->cnt_phn) { $mCoin -= ($wait->cnt_phn * $this->price_phn); }
            if($wait->cnt_pms) { $mCoin -= ($wait->cnt_pms * $this->price_imc); }
            if($wait->cnt_sms) { $mCoin -= ($wait->cnt_sms * $this->price_smt_sms); }
            if($wait->cnt_lms) { $mCoin -= ($wait->cnt_lms * $this->price_smt); }
            if($wait->cnt_mms) { $mCoin -= ($wait->cnt_mms * $this->price_smt_mms); }
        }

        $this->db->select_sum('dep_deposit');
        $this->db->where(array('mem_id' => $mem_id, 'dep_status' => 1));
        $result = $this->db->get('deposit');
        $sum = $result->row_array();

        $sum_coin = isset($sum['dep_deposit']) ? $sum['dep_deposit'] : 0;
        log_message("error", $mCoin);
        log_message("error", $sum_coin);

        header('Content-Type: application/json');
        echo '{"coin" : "' . $mCoin . '", "sum_coin" : "' . $sum_coin . '"}';
    }

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
          			 FROM cb_amt_".$userid." WHERE FIND_IN_SET('바우처', amt_memo)=0 AND amt_kind != '9') as total_deposit, ";
               $sql = $sql."
                   (SELECT
           			SUM(amt_amount * amt_deduct) amt
           			 FROM cb_amt_".$userid." WHERE FIND_IN_SET('바우처', amt_memo) OR amt_kind = '9') as voucher_deposit, ";

            }
            $sql = $sql."
					c.*
				from cb_member a
					inner join cb_member_register b on a.mem_id=b.mem_id
					left join cb_wt_member_addon c on a.mem_id=c.mad_mem_id
                    left join cb_wt_voucher_addon d on a.mem_id=d.vad_mem_id
				where a.mem_useyn='Y' and a.mem_userid=?";
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

}
?>
