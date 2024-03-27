<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 스윗트렉커 처리 모듈 : ?를 /로 바꿈 crontab 5초단위 실행
 * *?1 * * * * root cat /dev/null > /root/script/msg_check.txt; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5; /root/script/msg_check.sh & sleep 5
 */

class Sejong {
    
    public function sendAgent($msgType)
    {
        $CI =& get_instance();
        $ok = 0; $err = 0;
        /* 메시지 목록을 sc테이블에 담았다가 발송 */
        
        $couponid = $CI->input->post('couponid');
        $btn1 = $CI->input->post('btn1');
        //log_message("ERROR", "sendAgent coupon id : ".$couponid);
        $mem_url="";
        $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
        if($url_cnt->cnt > 0) {
            $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
            $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
        }
        if(empty($mem_url)) {
            $mem_url = $CI->input->post('img_link');
        }
        
        $param = array(
            '',											// 전화번호
            $CI->input->post('templi_cont'),			// 템플릿내용
            ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $btn1),
            ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
            ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
            ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
            ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",		// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
            ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
            ($msgType=="at") ? $CI->input->post('templi_code') : ''			// 템플릿코드
        );
        $tel_number = $CI->input->post('tel_number');
        //log_message("ERROR", var_dump($param));
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        for($n=0; $n<count($tel_number); $n++) {
            $param[0] = $tel_number[$n];
            /*
             * 2019.01.19 SSG
             * 수신전화번호 체크
             * 수신 전화번호가 8자리보다 짧으면 발송 불가
             */
            if(strlen($param[0]) >= 8) {
                if($couponid) {
                    $btn1_array = json_decode($btn1);
                    
                    $gurl = base_url()."couponview/index/".$couponid."/".$tel_number[$n];
                    $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                    
                    $shorturl = array();
                    $shorturl["short_url"] = $md5;
                    $shorturl["url"] = $gurl;
                    
                    $CI->db->insert("cb_short_url", $shorturl);
                    
                    $btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
                    $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
                    
                    $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                    $param[2] = str_replace(array("[", "]", "\\"), "", $btn1);
                }
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    					(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
    				values ('', ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";
                $CI->db->query($sql, $param);
                if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error()); $err++; } else { $ok++; }
            }
        }
        return $ok;
    }
    
    public function phoneBookToSendCache($msgType, $customer_filter="", $limit = 9999999)
    {
        $CI =& get_instance();
        /* 고객전체 발송 -> sendCache 삭제후 AddrBook으로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        $couponid = $CI->input->post('couponid');
        $btn1 = $CI->input->post('btn1');
        log_message("ERROR", "phoneBookToSendCache");
        
        $mem_url="";
        $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
        if($url_cnt->cnt > 0) {
            $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
            $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
        }
        if(empty($mem_url)) {
            $mem_url = $CI->input->post('img_link');
        }
        
        /* 고객 정보를 sendCache에 insert */
        $param = array(
            $CI->input->post('templi_cont'),	// 템플릿내용
            ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $btn1),
            ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
            ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
            ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
            ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
            ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
            ($msgType=="at") ? $CI->input->post('templi_code')	: '' // 템플릿코드
        );
        // 고객 필터 추가 2017.12.17
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }
        
        // 2019.01.17 이수환 customer_filter 'NONE'값(고객구분없음) 수정및 추가
        /*
         * 2019.01.19 SSG
         * 수신전화번호 체크
         * 수신 전화번호가 8자리보다 짧으면 발송 불가
         * length(ab_tel) >=8
         */
        
        $sql = "";
        if($customer_filter == "FT") {
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 and exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)".(($limit < 9999999) ? " limit ".$limit : "");
        } else if($customer_filter == "NFT") {
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)".(($limit < 9999999) ? " limit ".$limit : "");
        } else if($customer_filter == "NONE") { // 추가 2019.01.17
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 ".(($customer_filter) ? " and  ifNULL(ab_kind, '') = ''" : "").(($limit < 9999999) ? " limit ".$limit : "");
        } else {
            // 2019.01.18 이수환 차후 고객구분 멀티 선택시 처리를 위해 변경(LIKE => IN) 및 추가
            $customer_filter_array = explode(',', $customer_filter);
            log_message("ERROR", "-------------- customer_filter_array Count : ".count($customer_filter_array));
            $customer_filter_temp = "";
            for($i=0; $i < count($customer_filter_array); $i++) {
                if ($i == 0) { 
                    $customer_filter_temp.= "'".$customer_filter_array[$i]."'"; 
                } else {
                    $customer_filter_temp.= ",'".$customer_filter_array[$i]."'";
                }
            }
            ////$forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1'".(($customer_filter) ? " and  ab_kind like '%".$customer_filter."%'" : "").(($limit < 9999999) ? " limit ".$limit : "");
            $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 ".(($customer_filter) ? " and  ab_kind in (".$customer_filter_temp.")" : "").(($limit < 9999999) ? " limit ".$limit : "");
        }
        log_message("ERROR", "ForSql :".$forsql);
        //log_message("ERROR", "I : ".$forsql);
        $lists = $CI->db->query($forsql)->result();
        //$i = 1;
        foreach($lists as $r)
        {
            //log_message("ERROR", "I : ".$i);
            //$i ++;
            if($couponid) {
                $btn1_array = json_decode($btn1);
                
                $gurl = base_url()."couponview/index/".$couponid."/".$r->ab_tel;
                $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                
                $CI->db->insert("cb_short_url", $shorturl);
                
                $btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
                $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
                
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        				values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
                
            } else {
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        			values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            }
            
            $CI->db->query($sql, $param);
            if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
        }
        return 1;
    }
    
    public function phoneBookToSendCacheNFT($msgType )
    {
        $CI =& get_instance();
        /* 고객전체 발송 -> sendCache 삭제후 AddrBook으로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        $couponid = $CI->input->post('couponid');
        $btn1 = $CI->input->post('btn1');
        log_message("ERROR", "phoneBookToSendCacheNFT");
        
        $mem_url="";
        $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
        if($url_cnt->cnt > 0) {
            $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
            $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
        }
        if(empty($mem_url)) {
            $mem_url = $CI->input->post('img_link');
        }
        
        /* 고객 정보를 sendCache에 insert */
        $param = array(
            $CI->input->post('templi_cont'),	// 템플릿내용
            ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn1')),
            ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
            ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
            ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
            ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
            ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
            ($msgType=="at") ? $CI->input->post('templi_code')	: '' // 템플릿코드
        );
        // 고객 필터 추가 2017.12.19
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }
        $sql = "";

        /*
         * 2019.01.19 SSG
         * 수신전화번호 체크
         * 수신 전화번호가 8자리보다 짧으면 발송 불가
         * length(ab_tel) >=8
         */
        $forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)";
        
        //$forsql = "select * from cb_ab_".$CI->member->item('mem_userid')." where ab_status='1' and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$CI->member->item("mem_id")."' and b.phn = ab_tel)" ;
        $lists = $CI->db->query($forsql)->result();
        foreach($lists as $r)
        {
            if($couponid) {
                $btn1_array = json_decode($btn1);
                
                $gurl = base_url()."couponview/index/".$couponid."/".$r->ab_tel;
                $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                
                $CI->db->insert("cb_short_url", $shorturl);
                
                $btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
                $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
                
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
                  values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            } else {
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        			values('".$r->ab_name."', '".$r->ab_tel."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            }
            $CI->db->query($sql, $param);
            if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
        }
        return 1;
    }
    
    public function moveUploadToSendCache($msgType, $mem_id, $callback="")
    {
        $CI =& get_instance();
        /* 업로드 목록 발송 -> sendCache 삭제후 sc_userid로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        $couponid = $CI->input->post('couponid');
        //$btn1 = $CI->input->post('btn1');
        log_message("ERROR", "moveUploadToSendCache");
        
        $param = array(
            (($callback) ? $callback : $CI->input->post('senderBox')),
            ($msgType=="at") ? $CI->input->post('templi_code')	: '',
            $mem_id
        );
        //log_message("ERROR", "BTN 1 : ". $btn1);
        $sql = "";
        $forsql = "select sc.*, (case when sc_lms_content='' then 'N' else 'Y' end) as sc_lm_,  '".$param[0]."' as callback, '".$param[1]."' as templi_code
			from cb_wt_send_cache sc where sc_mem_id='".$mem_id."'";
        $lists = $CI->db->query($forsql)->result();
        foreach($lists as $r)
        {
            if($couponid) {
                $btn1_array = json_decode($r->sc_button1);
                
                $gurl = base_url()."couponview/index/".$couponid."/".$r->sc_tel;
                $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                
                $CI->db->insert("cb_short_url", $shorturl);
                
                // $btn1_array = array();
                $btn1_array->url_mobile = base_url()."short_url/coupon/".$md5;
                $btn1_array->url_pc = base_url()."short_url/coupon/".$md5;
                
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $btn1 = str_replace(array("[", "]", "\\"), "", $btn1);
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    			  (sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
                   sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
            values('".$r->sc_name."', '".$r->sc_tel."', '".$r->sc_content."', '".$btn1."','".$r->sc_button2."', '".$r->sc_button3."','".$r->sc_button4."', '".$r->sc_button5."',
                 '".$r->sc_lm_."', '".$r->sc_lms_content."', '".$r->callback."', '".$r->sc_img_url."', '".$r->sc_img_link."', '".$r->templi_code."' )";
            } else {
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
    			(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
                 sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
            values('".$r->sc_name."', '".$r->sc_tel."', '".$r->sc_content."', '".$r->sc_button1."','".$r->sc_button2."', '".$r->sc_button3."','".$r->sc_button4."', '".$r->sc_button5."',
                 '".$r->sc_lm_."', '".$r->sc_lms_content."', '".$r->callback."', '".$r->sc_img_url."', '".$r->sc_img_link."', '".$r->templi_code."' )";
            }
            
            $CI->db->query($sql, $param);
            if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
        }
        return 1;
    }
    
    public function NewUploadToSendCache($msgType )
    {
        $CI =& get_instance();
        /* 고객전체 발송 -> sendCache 삭제후 AddrBook으로 insert */
        $CI->db->query("delete from cb_sc_".$CI->member->item('mem_userid'));
        if($CI->db->error()['code'] > 0) { return 0; }
        
        $couponid = $CI->input->post('couponid');
        $btn1 = $CI->input->post('btn1');
        log_message("ERROR", "NewUploadToSendCache");
        
        $mem_url="";
        $url_cnt = $CI->db->query("SELECT count(1) as cnt FROM cb_wt_member_addon where mad_free_hp like '%pf.kakao.com%' and mad_mem_id = '".$CI->member->item('mem_id')."'")->row();
        if($url_cnt->cnt > 0) {
            $sql = "select mad_free_hp from cb_wt_member_addon a where a.mad_mem_id = '".$CI->member->item('mem_id')."'";
            $mem_url = $CI->db->query($sql)->row()->mad_free_hp;
        }
        if(empty($mem_url)) {
            $mem_url = $CI->input->post('img_link');
        }
        
        /* 고객 정보를 sendCache에 insert */
        $param = array(
            $CI->input->post('templi_cont'),	// 템플릿내용
            ($CI->input->post('btn1')=="[]") ? null : str_replace(array("[", "]"), "", $btn1),
            ($CI->input->post('btn2')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn2')),
            ($CI->input->post('btn3')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn3')),
            ($CI->input->post('btn4')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn4')),
            ($CI->input->post('btn5')=="[]") ? null : str_replace(array("[", "]"), "", $CI->input->post('btn5')),
            ($CI->input->post('kind')) ? $CI->input->post('kind') : "N",			// SMS재발신여부
            $CI->input->post('msg'),				// LMS/SMS내용
            $CI->input->post('senderBox'),
            ($CI->input->post('img_url')) ? $CI->input->post('img_url') : null,
            ($mem_url) ? $mem_url : null, //($CI->input->post('img_link')) ? $CI->input->post('img_link') : null,
            ($msgType=="at") ? $CI->input->post('templi_code')	: '' // 템플릿코드
        );
        // 고객 필터 추가 2017.12.19
        //if($customer_filter) { array_push($param, "%".$customer_filter."%"); }
        
        $sql = "";
        $forsql = "select * from cb_tel_uploads where mem_id = '".$CI->member->item('mem_id')."'";
        //log_message("ERROR", "Query : ".$CI->input->post('msg'));
        
        $lists = $CI->db->query($forsql)->result();
        foreach($lists as $r)
        {
            if($couponid) {
                $btn1_array = json_decode($btn1);
                
                $gurl = base_url()."couponview/index/".$couponid."/".$r->ab_tel;
                $md5 = md5($gurl).":".crc32($couponid.cdate('His'));
                
                $shorturl = array();
                $shorturl["short_url"] = $md5;
                $shorturl["url"] = $gurl;
                
                $CI->db->insert("cb_short_url", $shorturl);
                
                $btn1_array[0]->url_mobile = base_url()."short_url/coupon/".$md5;
                $btn1_array[0]->url_pc = base_url()."short_url/coupon/".$md5;
                
                $btn1 = json_encode($btn1_array, JSON_UNESCAPED_UNICODE);
                $param[1] = str_replace(array("[", "]", "\\"), "", $btn1);
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        				values('', '".$r->tel_no."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
                
            } else {
                
                $sql = "insert into cb_sc_".$CI->member->item('mem_userid')."
        				(sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
        			values('', '".$r->tel_no."', ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
            }
            //log_message("ERROR", "QUERY : ".$sql);
            $CI->db->query($sql, $param);
            if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; }
        }
        return 1;
    }
    
    public function sendAgentSendCache($msgType, $group_id, $mem_id, $mem_userid, $pf_key, $reserveDt='00000000000000', $limit=9999999)
    {
        $CI =& get_instance();
        $param = array(
            "N",	//(($msgType=="at") ? 'N'	: 'Y'),		// 광고구분(친구톡 필수 광고성정보 구분:내용에 (광고)글자가 있는 경우 N으로 보내면 됨)
            $msgType,									// ft, at
            'N',											// onlySMS $CI->input->post('smsOnly')
            null,											// P_COM 택배사코드
            null,											// P_INVOICE 송장번호
            $pf_key,										// 프로필키
            $group_id,									// msg_sent에서 확인하기 위한 group id
            $reserveDt,									// 예약발송일시
            null,											// 쇼핑몰코드
            ''												// 전환발송시 SMS/LMS구분
        );
        $sql = "insert ignore into TBL_REQUEST
			(
				MSGID, AD_FLAG,
				BUTTON1, BUTTON2, BUTTON3, BUTTON4, BUTTON5,
				IMAGE_LINK, IMAGE_URL,
				MESSAGE_TYPE, MSG, MSG_SMS, ONLY_SMS, P_COM, P_INVOICE,
				PHN, PROFILE, REG_DT, REMARK4,  REMARK5,
				RESERVE_DT, S_CODE, SMS_KIND, SMS_LMS_TIT, SMS_SENDER, TMPL_ID, REMARK2
			)
			select
				concat('".$mem_id."_', sc_id), ?,
				sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
				sc_img_link IMAGE_LINK, sc_img_url IMAGE_URL,
				?, sc_content, sc_lms_content, ? , ?, ?,
				(CASE WHEN SUBSTR(sc_tel, 1, 2)='01' THEN CONCAT('82', SUBSTR(sc_tel,2)) ELSE sc_tel END), ?, now(), ?, sc_sms_yn,
				?, ?, ?, substr(sc_lms_content, 1, 20), sc_sms_callback, sc_template, '".$mem_id."'
			from cb_sc_".$mem_userid.(($limit < 9999999) ? " limit ".$limit : "");
        //			echo '<pre> :: ';print_r($sql);
        $CI->db->query($sql, $param);
        $mst_qty = $CI->db->affected_rows();
        
        $sql = "update cb_wt_msg_sent set mst_qty=".($mst_qty)." where mst_id = '".$group_id."'";
        $CI->db->query($sql);
        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
    }
    
    public function sendAgentSendAPI($msgType, $group_id, $mem_id, $mem_userid, $pf_key, $reserveDt='00000000000000', $sc_id)
    {
        $CI =& get_instance();
        $param = array(
            "N",	//(($msgType=="at") ? 'N'	: 'Y'),		// 광고구분(친구톡 필수 광고성정보 구분:내용에 (광고)글자가 있는 경우 N으로 보내면 됨)
            $msgType,									// ft, at
            'N',											// onlySMS $CI->input->post('smsOnly')
            null,											// P_COM 택배사코드
            null,											// P_INVOICE 송장번호
            $pf_key,										// 프로필키
            $group_id,									// msg_sent에서 확인하기 위한 group id
            $reserveDt,									// 예약발송일시
            null,											// 쇼핑몰코드
            ''												// 전환발송시 SMS/LMS구분
        );
        $sql = "insert ignore into TBL_REQUEST
			(
				MSGID, AD_FLAG,
				BUTTON1, BUTTON2, BUTTON3, BUTTON4, BUTTON5,
				IMAGE_LINK, IMAGE_URL,
				MESSAGE_TYPE, MSG, MSG_SMS, ONLY_SMS, P_COM, P_INVOICE,
				PHN, PROFILE, REG_DT, REMARK4,  REMARK5,
				RESERVE_DT, S_CODE, SMS_KIND, SMS_LMS_TIT, SMS_SENDER, TMPL_ID, REMARK2
			)
			select
				concat('".$mem_id."_', sc_id, '_API'), ?,
				sc_button1, sc_button2, sc_button3, sc_button4, sc_button5,
				sc_img_link IMAGE_LINK, sc_img_url IMAGE_URL,
				?, sc_content, sc_lms_content, ? , ?, ?,
				(CASE WHEN SUBSTR(sc_tel, 1, 2)='01' THEN CONCAT('82', SUBSTR(sc_tel,2)) ELSE sc_tel END), ?, now(), ?, sc_sms_yn,
				?, ?, ?, substr(sc_lms_content, 1, 20), sc_sms_callback, sc_template, '".$mem_id."'
			from cb_api_sc_".$mem_userid." where sc_id='".$sc_id."'";
        //			echo '<pre> :: ';print_r($sql);
        $CI->db->query($sql, $param);
        $mst_qty = $CI->db->affected_rows();
        
        $sql = "update cb_wt_msg_sent set mst_qty=".($mst_qty)." where mst_id = '".$group_id."'";
        $CI->db->query($sql);
        if($CI->db->error()['code'] > 0) { echo '<pre> :: ';print_r($CI->db->error());return 0; } else { return 1; }
    }
}