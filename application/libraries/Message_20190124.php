<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message {
    
    public $userId = "dhn";
    var $agent = 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 5.0)';
    
    public function getUserId()
    {
        return $this->userId;
    }
    
    public function sendAgent($msgType)
    {
        $CI =& get_instance();
        $ok = 0; $err = 0;
        /* 메시지 목록을 sc테이블에 담았다가 발송 */
        $param = array(
            '',											// 전화번호
            $CI->input->post('templi_cont'),			// 템플릿내용
            '',
            '',
            '',
            '',
            '',
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",		// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            '',
            '',
            ''			// 템플릿코드
        );
        $tel_number = $CI->input->post('tel_number');
        
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        for($n=0; $n<count($tel_number); $n++) {
            $param[0] = $tel_number[$n];
            
            /*
             * 2019.01.19 SSG
             * 수신전화번호 체크
             * 수신 전화번호가 8자리보다 짧으면 발송 불가
             */
            if(strlen($param[0]) >= 8) {
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    					(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
    				values ('', ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";
                $CI->db->query($sql, $param);
                if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error()); $err++; } else { $ok++; }
            }
        }
        return $ok;
    }
    
    public function phoneBookToSendCache($msgType, $customer_filter="")
    {
        $CI =& get_instance();
        /* 고객전체 발송 -> sendCache 삭제후 AddrBook으로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        /* 고객 정보를 sendCache에 insert */
        $param = array(
            $CI->input->post('templi_cont'),	// 템플릿내용
            '',
            '',
            '',
            '',
            '',
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            null,
            null,
            '' // 템플릿코드
        );
        // 고객 필터 추가 2017.12.19
        // 고객 필터에 고객구분없음 추가 2019.01.17 이수환 수정
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }
        //$sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        //		(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        //	select ab_name, ab_tel, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1'".(($customer_filter) ? " and  ab_kind like ? " : "");
        
        /*
         * 2019.01.19 SSG
         * 수신전화번호 체크
         * 수신 전화번호가 8자리보다 짧으면 발송 불가
         * length(ab_tel) >=8
         */
        
        if($customer_filter == "NONE") {
            //array_push($param, "");
            $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
				    (sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
			     select ab_name, ab_tel, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8  and  ifnull(ab_kind, '') = ''";
            
        } else {
            // 2019.01.18 이수환 차후 고객구분 멀티 선택시 처리를 위해 변경(LIKE => IN)
            //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }
            if($customer_filter) { array_push($param, $customer_filter); }
            
            // 2019.01.18 이수환 차후 고객구분 멀티 선택시 처리를 위해 변경(LIKE => IN)
            // $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
            //	    (sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
            //     select ab_name, ab_tel, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1'".(($customer_filter) ? " and ifnull(ab_kind, '') like ? " : "");
            
            /*
             * 2019.01.19 SSG
             * 수신전화번호 체크
             * 수신 전화번호가 8자리보다 짧으면 발송 불가
             * length(ab_tel) >=8
             */
            $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
            	    (sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
                select ab_name, ab_tel, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 ".(($customer_filter) ? " and ifnull(ab_kind, '') in (?) " : "");
        }
        
        log_message("ERROR", "lms SQL : ".$sql);
        $CI->db->query($sql, $param);
        
        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
    }
    
    /*
     * 2019.01.17 ssg
     * 실패건을 이용한 발신용 Function
     */
    public function FailmsgToSendCache($msgType, $mst_id)
    {
        $CI =& get_instance();
        /* 고객전체 발송 -> sendCache 삭제후 AddrBook으로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        /* 고객 정보를 sendCache에 insert */
        $param = array(
            $CI->input->post('templi_cont'),	// 템플릿내용
            '',
            '',
            '',
            '',
            '',
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            null,
            null,
            '' // 템플릿코드
        );
        
        $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
				    (sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
			     SELECT NULL, phn, NULL, NULL, NULL, NULL, NULL, NULL, NULL, cwm.mst_lms_content, cmd.SMS_SENDER, NULL, NULL, NULL
                   FROM cb_msg_".$CI->member->item('mem_userid')." cmd INNER JOIN cb_wt_msg_sent cwm ON cmd.remark4 = cwm.mst_id
                  WHERE cmd.remark4 = '".$mst_id."' and cmd.RESULT = 'N' and cmd.MESSAGE not like '%대기%'";
        
        /**/
        //
        $CI->db->query($sql, $param);
        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
    }
    
    public function moveUploadToSendCache($msgType, $mem_id, $callback="")
    {
        $CI =& get_instance();
        /* 업로드 목록 발송 -> sendCache 삭제후 sc_userid로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        $param = array(
            (($callback) ? $callback : $CI->input->post('senderBox')),
            ($msgType=="at") ? $CI->input->post('templi_code')	: '',
            $mem_id
        );
        $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
			(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
			select
				sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, (case when sc_lms_content='' then 'N' else 'Y' end), sc_lms_content, ?, sc_img_url, sc_img_link, ?
			from cb_wt_send_cache where sc_mem_id=?";
        $CI->db->query($sql, $param);
        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
    }
    
    
    public function NewUploadToSendCache($msgType )
    {
        $CI =& get_instance();
        /* 고객전체 발송 -> sendCache 삭제후 AddrBook으로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        /* 고객 정보를 sendCache에 insert */
        $param = array(
            $CI->input->post('templi_cont'),	// 템플릿내용
            '',
            '',
            '',
            '',
            '',
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            null,
            null,
            '' // 템플릿코드
        );
        // 고객 필터 추가 2017.12.19
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }
        
        /*
         * 2019.01.19 SSG
         * 수신전화번호 체크
         * 수신 전화번호가 8자리보다 짧으면 발송 불가
         * length(tel_no) >=8
         */
        $sql = "";
        $forsql = "select * from cb_tel_uploads where length(tel_no) >=8 and mem_id = '".$CI->member->item('mem_id')."'";
        $lists = $CI->db->query($forsql)->result();
        
        foreach($lists as $r)
        {
            $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
    			values('', '".$r->tel_no."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            
            $CI->db->query($sql, $param);
            if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
        }
        return 1;
    }
    
    public function sendAgentSendCache($msgType, $group_id, $mem_id, $mem_userid, $pf_key, $reserveDt='00000000000000', $limit=9999999)
    {
        $CI =&get_instance();
        $param = array(
            "N",	//(($msgType=="at") ? 'N'	: 'Y'),		// 광고구분(친구톡 필수 광고성정보 구분:내용에 (광고)글자가 있는 경우 N으로 보내면 됨)
            'ph',									// ft, at
            'N',											// onlySMS $CI->input->post('smsOnly')
            null,											// P_COM 택배사코드
            $pf_key,											// P_INVOICE 송장번호
            'PHONE',										// 프로필키
            $group_id,									// msg_sent에서 확인하기 위한 group id
            $reserveDt,									// 예약발송일시
            null,											// 쇼핑몰코드
            $msgType												// 전환발송시 SMS/LMS구분
        );
        $sql = "insert into TBL_REQUEST_RESULT
			(
				MSGID, AD_FLAG,
				BUTTON1, BUTTON2, BUTTON3, BUTTON4, BUTTON5,
				IMAGE_LINK, IMAGE_URL,
				MESSAGE_TYPE, MSG, MSG_SMS, ONLY_SMS, P_COM, P_INVOICE,
				PHN, PROFILE, REG_DT, REMARK4,  REMARK5,
				RESERVE_DT, S_CODE, SMS_KIND, SMS_LMS_TIT, SMS_SENDER, TMPL_ID, RESULT, CODE, MESSAGE,  REMARK2
			)
			select
				concat('".$mem_id."_', sc_id), ?,
				sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
				sc_img_link IMAGE_LINK, sc_img_url IMAGE_URL,
				?, sc_content, sc_lms_content, ? , ?, ?,
				(CASE WHEN SUBSTR(sc_tel, 1, 2)='01' THEN CONCAT('82', SUBSTR(sc_tel,2)) ELSE sc_tel END), ?, now(), ?, sc_sms_yn,
				?, ?, ?, substr(sc_lms_content, 1, 20), sc_sms_callback, sc_template, 'N', 'K999', 'PHONE ONLY', '".$mem_id."'
			from cb_sc_".$mem_userid.(($limit < 9999999) ? " limit ".$limit : "");
        //			echo '<pre> :: ';print_r($sql);
        // log_message("ERROR", $sql);
        $CI->db->query($sql, $param);
        $mst_qty = $CI->db->affected_rows();
        
        $sql = "update cb_wt_msg_sent set mst_qty=".($mst_qty)." where mst_id = '".$group_id."'";
        $CI->db->query($sql);
        
        if($CI->db->error()['code'] > 0) {return 0; } else { return $mst_qty; }
        
    }
}