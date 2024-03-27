<?php

class Partner_move extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    //protected $models = array('Board', 'Biz_dhn');
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

        $view = array();
        $view['view'] = array();

        if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

        $sql = "select mem_id, mem_username from cb_member where mem_level >= 50 and mem_useyn = 'Y' and mem_id not in (2, 738, 746, 1028) ";
        $view['uppartner'] = $this->db->query($sql)->result();
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/partner_move',
            'layout' => 'layout',
            'skin' => 'write',
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

    public function partner_search(){
        if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$uid = $this->input->post("uid");
		$result = array();
        if(!empty($uid)){
            $sql = "select count(1) as cnt from cb_member where mem_userid='".$uid."'";
            $pre_cnt = $this->db->query($sql)->row()->cnt;
            if($pre_cnt>0){
                $result["code"] = "1";
                $result["msg"] = "파트너가 이미 등록되어 있습니다.";
            }else{
                $sql= "select EXISTS (
                          SELECT 1 FROM Information_schema.tables
                          WHERE table_schema = 'igenie'
                          AND table_name = 'cb_msg_".$uid ."'
                        ) AS flag";
                $table_exist = $this->db->query($sql)->row()->flag;
                if($table_exist>0){
                    $result["code"] = "1";
                    $result["msg"] = "cb_member에는 데이터가 없으나 해당 id 테이블이 있습니다. 확인이 필요합니다.";
                }else{
                    $result["code"] = "0";
                    $result["msg"] = "파트너가 이전가능한 상태입니다.";
                }
            }


        }else{
            $result["code"] = "1";
            $result["msg"] = "userid 값이 넘어오지 않았습니다";
        }
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}


    public function partner_move(){
        if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$uid = $this->input->post("uid");
        $uppartner = $this->input->post("uppartner");

		$result = array();
        $result["mem_photo"] = "";
        $result["mem_contract_filepath"] = "";
        $result["mem_shop_img"] = "";
        if(!empty($uid)){
            $sql = "select *
             from cb_g2_cb_member_temp
             where mem_userid='".$uid."' and mem_useyn = 'Y'";
             $cbmemberdata = $this->db->query($sql)->row();


             if(!empty($cbmemberdata)){

                 $result["mem_photo"] = "/uploads/member_photo/".$cbmemberdata->mem_photo;
                 $result["mem_contract_filepath"] = "/uploads/member_contract/".$cbmemberdata->mem_contract_filepath;
                 $result["mem_shop_img"] = $cbmemberdata->mem_shop_img;

                 $sql = "insert into cb_member (mem_userid,mem_email,mem_password,mem_username,mem_nickname,mem_level,mem_deposit,mem_point,mem_homepage,mem_phone,mem_birthday,mem_sex,mem_zipcode,mem_address1,mem_address2,mem_address3,mem_address4,mem_receive_email,mem_use_note,mem_receive_sms,mem_open_profile,mem_denied,mem_email_cert,mem_register_datetime,mem_register_ip,mem_lastlogin_datetime,mem_lastlogin_ip,mem_is_admin,mem_profile_content,mem_adminmemo,mem_following,mem_followed,mem_icon,mem_linkbtn_name,mem_photo,mem_phn_agent,mem_sms_agent,mem_useyn,mem_day_limit,mem_2nd_send,mem_payment_desc,mem_payment_bank,mem_payment_card,mem_payment_realtime,mem_payment_vbank,mem_payment_phone,mem_pay_type,mem_min_charge_amt,mem_fixed_from_date,mem_fixed_to_date,mem_emp_phone,mem_bank_user,mem_pf_manager,mem_pf_cnt,mem_full_care_yn,mem_full_care_type,mem_full_care_max,mem_sales_name,mem_customer_up_filename,mem_cont_from_date,mem_cont_to_date,mem_biz_reg_name,mem_biz_reg_no,mem_biz_reg_rep_name,mem_biz_reg_zip_code,mem_biz_reg_add1,mem_biz_reg_add2,mem_biz_reg_add3,mem_biz_reg_div,mem_biz_reg_biz,mem_biz_reg_sector,mem_biz_reg_email1,mem_biz_reg_email2,mem_full_care_from_date,mem_full_care_to_date,mem_cont_cancel_yn,mem_cont_cancel_date,mem_biz_reg_tel,mem_biz_reg_bigo,mem_contract_filename,mem_contract_filepath,mem_shop_img,mem_shop_intro,mem_shop_time,mem_shop_holiday,mem_shop_phone,mem_shop_addr,mem_map_node,mem_map_key,mem_shop_code,mem_contract_type,mem_mall_id,mem_mall_key,mem_purigo_yn,mem_talk_time_clear_yn,mem_monthly_fee,mem_site_key,mem_pos_yn,mem_pos_id,mem_pos_port,mem_ip,mem_alim_btn_url1,mem_alim_btn_url2,mem_alim_btn_url3,mem_bigpos_yn,mem_tex_io_date,mem_stmall_yn,mem_stmall_alim_phn,mem_mapadd_day,mem_img_lib_category1,mem_img_lib_category2,mem_is_pay,mem_is_account,account_info,mem_is_stan,mem_sales_point,mem_sales_mng_id,mem_design_mng_id,mem_sale_point_id,mem_cart_info,mem_management_mng_id,mem_management_point_id,mem_note) ".
                 "
                 select
                 mem_userid,mem_email,mem_password,mem_username,mem_nickname,mem_level,mem_deposit,mem_point,mem_homepage,mem_phone,mem_birthday,mem_sex,mem_zipcode,mem_address1,mem_address2,mem_address3,mem_address4,mem_receive_email,mem_use_note,mem_receive_sms,mem_open_profile,mem_denied,mem_email_cert,mem_register_datetime,mem_register_ip,mem_lastlogin_datetime,mem_lastlogin_ip,mem_is_admin,mem_profile_content,mem_adminmemo,mem_following,mem_followed,mem_icon,mem_linkbtn_name,mem_photo,mem_phn_agent,mem_sms_agent,mem_useyn,mem_day_limit,mem_2nd_send,mem_payment_desc,mem_payment_bank,mem_payment_card,mem_payment_realtime,mem_payment_vbank,mem_payment_phone,mem_pay_type,mem_min_charge_amt,mem_fixed_from_date,mem_fixed_to_date,mem_emp_phone,mem_bank_user,mem_pf_manager,mem_pf_cnt,mem_full_care_yn,mem_full_care_type,mem_full_care_max,mem_sales_name,mem_customer_up_filename,mem_cont_from_date,mem_cont_to_date,mem_biz_reg_name,mem_biz_reg_no,mem_biz_reg_rep_name,mem_biz_reg_zip_code,mem_biz_reg_add1,mem_biz_reg_add2,mem_biz_reg_add3,mem_biz_reg_div,mem_biz_reg_biz,mem_biz_reg_sector,mem_biz_reg_email1,mem_biz_reg_email2,mem_full_care_from_date,mem_full_care_to_date,mem_cont_cancel_yn,mem_cont_cancel_date,mem_biz_reg_tel,mem_biz_reg_bigo,mem_contract_filename,mem_contract_filepath,mem_shop_img,mem_shop_intro,mem_shop_time,mem_shop_holiday,mem_shop_phone,mem_shop_addr,mem_map_node,mem_map_key,mem_shop_code,mem_contract_type,mem_mall_id,mem_mall_key,mem_purigo_yn,mem_talk_time_clear_yn,mem_monthly_fee,mem_site_key,mem_pos_yn,mem_pos_id,mem_pos_port,mem_ip,mem_alim_btn_url1,mem_alim_btn_url2,mem_alim_btn_url3,mem_bigpos_yn,mem_tex_io_date,mem_stmall_yn,mem_stmall_alim_phn,mem_mapadd_day,mem_img_lib_category1,mem_img_lib_category2,mem_is_pay,mem_is_account,account_info,mem_is_stan,mem_sales_point,mem_sales_mng_id,mem_design_mng_id,mem_sale_point_id,mem_cart_info,mem_management_mng_id,mem_management_point_id,mem_note
                  from cb_g2_cb_member_temp
                  where mem_userid='".$uid."' and mem_useyn = 'Y'";

                  // log_message("ERROR", "cb_member : ".$sql);
                  $this->db->query($sql);

                  $mem_id = $this->db->insert_id();

                  log_message("ERROR", "지니2-지니1 이전 : id - ".$cbmemberdata->mem_userid." / name - ".$cbmemberdata->mem_username." / pre_mem_id - ".$cbmemberdata->mem_id." / new_mem_id - ".$mem_id);

                  $sql = "insert into cb_member_userid (mem_id,mem_userid,mem_status)
                  values
                  ('".$mem_id."','".$uid."', 0)";

                  // log_message("ERROR", "cb_member_userid : ".$sql);
                  $this->db->query($sql);

                  $sql = "insert into cb_member_nickname (mem_id,mni_nickname,mni_start_datetime)
                  values
                  ('".$mem_id."','".$cbmemberdata->mem_nickname."', '".$cbmemberdata->mem_register_datetime."')";

                  // log_message("ERROR", "cb_member_nickname : ".$sql);
                  $this->db->query($sql);


                  $sql = "insert into cb_member_register (mem_id,mrg_ip,mrg_datetime,mrg_recommend_mem_id,mrg_useragent,mrg_referer)
                  select
                  '".$mem_id."' as mem_id, mrg_ip,mrg_datetime, '".$uppartner."', mrg_useragent,'http://igenie.co.kr/'
                   from cb_g2_cb_member_register_temp
                   where mem_id='".$cbmemberdata->mem_id."'";

                  // log_message("ERROR", "cb_member_register : ".$sql);
                  $this->db->query($sql);

                  $sql = "insert into cb_wt_member_addon (mad_mem_id,mad_free_hp,mad_price_ft,mad_price_ft_img,mad_price_ft_w_img,mad_price_at,mad_price_sms,mad_price_lms,mad_price_mms,mad_price_grs,mad_price_nas,mad_price_015,mad_price_phn,mad_weight1,mad_weight2,mad_weight3,mad_weight4,mad_weight5,mad_weight6,mad_weight7,mad_weight8,mad_weight9,mad_weight10,mad_price_grs_sms,mad_price_nas_sms,mad_price_dooit,mad_price_grs_mms,mad_price_nas_mms,mad_price_smt,mad_price_smt_sms,mad_price_smt_mms,mad_price_imc,mad_price_full_care,mad_price_grs_biz)
                  select
                  '".$mem_id."' as mad_mem_id, mad_free_hp,mad_price_ft,mad_price_ft_img,mad_price_ft_w_img,mad_price_at,mad_price_sms,mad_price_lms,mad_price_mms,mad_price_grs,mad_price_nas,mad_price_015,mad_price_phn,mad_weight1,mad_weight2,mad_weight3,mad_weight4,mad_weight5,mad_weight6,mad_weight7,mad_weight8,mad_weight9,mad_weight10,mad_price_grs_sms,mad_price_nas_sms,mad_price_dooit,mad_price_grs_mms,mad_price_nas_mms,mad_price_smt,mad_price_smt_sms,mad_price_smt_mms,mad_price_imc,mad_price_full_care,mad_price_grs_biz
                   from cb_g2_cb_wt_member_addon_temp
                   where mad_mem_id='".$cbmemberdata->mem_id."'";

                  // log_message("ERROR", "cb_wt_member_addon : ".$sql);
                  $this->db->query($sql);

                  $sql = "insert into cb_member_template_group (mtg_mem_id, mtg_profile, mtg_useyn, mtg_cre_date)
                  values
                  ('".$mem_id."','A', 'Y','".$cbmemberdata->mem_register_datetime."')";
                  $this->db->query($sql);

                  $sql = "insert into cb_member_template_group (mtg_mem_id, mtg_profile, mtg_useyn, mtg_cre_date)
                  values
                  ('".$mem_id."','B', 'Y','".$cbmemberdata->mem_register_datetime."')";
                  $this->db->query($sql);

                  $sql = "select count(1) as cnt from cb_g2_cb_orders_setting_temp where mem_id = '".$cbmemberdata->mem_id."'";
                  $o_cnt = $this->db->query($sql)->row()->cnt;

                  if($o_cnt>0){
                      $sql = "insert into cb_orders_setting (mem_id, min_amt, delivery_amt, free_delivery_amt)
                      select
                      '".$mem_id."' as mad_mem_id, min_amt, delivery_amt, free_delivery_amt
                       from cb_g2_cb_orders_setting_temp
                       where mem_id='".$cbmemberdata->mem_id."'";
                       $this->db->query($sql);
                   }


                  $sql = "select count(1) as cnt from cb_g2_cb_send_tel_no_list_temp cstnl where mem_id = '".$cbmemberdata->mem_id."'";
                  $tel_cnt = $this->db->query($sql)->row()->cnt;

                  if($tel_cnt>0){
                      $sql = "insert into cb_send_tel_no_list (mem_id,send_tel_no,auth_type,auth_flag,reg_date,appr_date,memo,att_url,use_flag)
                      select
                      '".$mem_id."' as mem_id,send_tel_no,auth_type,auth_flag,reg_date,appr_date,memo,att_url,use_flag
                       from cb_g2_cb_send_tel_no_list_temp
                       where mem_id='".$cbmemberdata->mem_id."'";
                       $this->db->query($sql);
                  }

                  $sql = "create TABLE IF NOT EXISTS `cb_msg_".$uid."`
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
                          INDEX `cb_msg_".$uid."_IDX01` (`REMARK4`, `MESSAGE_TYPE`, `MSGID`),
                          INDEX `cb_msg_".$uid."_IDX02` (`REMARK4`, `MSGID`)
                       ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

                        // log_message("ERROR", "create cb_msg_ : ".$sql);
                        $this->db->query($sql);

                        $sql = "create TABLE IF NOT EXISTS `cb_ab_".$uid."` (
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

                      // log_message("ERROR", "create cb_ab_ : ".$sql);
                      $this->db->query($sql);


                      $sql = "create TABLE IF NOT EXISTS `cb_img_".$uid."` (
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
                       ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

                    // log_message("ERROR", "create cb_img_ : ".$sql);
                    $this->db->query($sql);

                    $sql = "create TABLE IF NOT EXISTS `cb_amt_".$uid."` (
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
                           ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

                  // log_message("ERROR", "create cb_amt_ : ".$sql);
                  $this->db->query($sql);

                      $sql = "create TRIGGER `ins_cb_amt_".$uid."` BEFORE INSERT ON `cb_amt_".$uid."`
                                FOR EACH ROW
                                BEGIN
                                   if NEW.amt_kind IN ('1', '3', '4','5','6','9') then
                                       set NEW.amt_deduct := 1;
                                   ELSE
                                       set NEW.amt_deduct := -1;
                                   END if;
                                END";

                    // log_message("ERROR", "create trigger cb_amt_ : ".$sql);
                    $this->db->query($sql);

                        $sql = "create TABLE IF NOT EXISTS  `cb_sc_".$uid."` (
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
                          `supplement` text DEFAULT NULL,
                          `price` int(11) DEFAULT NULL,
                          `currency_type` char(3) DEFAULT NULL,
                          `title` char(50) DEFAULT NULL,
                         PRIMARY KEY (`sc_id`)
                       ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

                      // log_message("ERROR", "create cb_sc_ : ".$sql);
                      $this->db->query($sql);





                // $cbmemberdata = $this->db->query($sql)->row();

                // $mem_id = $this->db->insert_id();

                // $CI = &get_instance();
                // $this->db2 = $CI->load->database('59g', TRUE);

                // $cbmemberdata = $this->db2->query($sql)->row();

                // $dbt = $this->load->database("59g", TRUE);
                //
                //
                // $cbmemberdata = $dbt->query($sql)->row();
                // log_message("ERROR", "userid : ".$cbmemberdata->mem_userid." / username : ".$cbmemberdata->mem_username);
                $result["code"] = "0";
                $result["msg"] = "이전되었습니다";
             }



        }else{
            $result["code"] = "1";
            $result["msg"] = "userid 값이 넘어오지 않았습니다";
        }
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}


    // 삭제는 dhn 통합 사이트에서만 가능
    // function del() {
    //     $person = $this->input->post("person");
    //     $db = $this->load->database("dhn", TRUE);
    //
    //     $result = array();
    //     $s = 0;
    //
    //     $sql = "select count(1) as cnt from cb_partners_v where mem_sales_mng_id = '".$person['ds_id']."'";
    //     $dbt = $this->load->database("58", TRUE);
    //     $result['58'] = $dbt->query($sql)->row()->cnt ;
    //     $s = $s + $dbt->query($sql)->row()->cnt ;
    //
    //     $sql = "select count(1) as cnt from cb_partners_v where mem_sales_mng_id = '".$person['ds_id']."'";
    //     $dbt = $this->load->database("133g", TRUE);
    //     $result['133g'] = $dbt->query($sql)->row()->cnt ;
    //     $s = $s + $dbt->query($sql)->row()->cnt ;
    //
    //     $sql = "select count(1) as cnt from cb_partners_v where mem_sales_mng_id = '".$person['ds_id']."'";
    //     $dbt = $this->load->database("133o", TRUE);
    //     $result['133o'] = $dbt->query($sql)->row()->cnt ;
    //     $s = $s + $dbt->query($sql)->row()->cnt ;
    //
    //     $sql = "select count(1) as cnt from cb_partners_v where mem_sales_mng_id = '".$person['ds_id']."'";
    //     $dbt = $this->load->database("59g", TRUE);
    //     $result['59g'] = $dbt->query($sql)->row()->cnt ;
    //     $s = $s + $dbt->query($sql)->row()->cnt ;
    //
    //     if(!empty($person['ds_id']) && $s == 0) {
    //         $sql = "delete from dhn_salesperson where ds_id = '".$person['ds_id']."'";
    //         $db->query($sql);
    //         header('Content-Type: application/json');
    //         echo '{"code": "OK"}';
    //
    //     } else {
    //         $result['code'] = "ER";
    //         header('Content-Type: application/json');
    //         echo json_encode($result,JSON_UNESCAPED_UNICODE);
    //
    //     }
    //
    // }



}
?>
