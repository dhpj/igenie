<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 관리자>예치금>무통장입금알림 controller 입니다.
 */
class Bizmsgapi extends CB_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('depositlib'));
    }


    public function alimtalk()
    {
        $kakao_h = getallheaders();
        $kakao_d = json_decode(file_get_contents('php://input'));
        $debug = file_get_contents('php://input');
        //log_message("ERROR","API Call : ". $debug);
        $kakao = array();

        $kakao['profile_key'] = $kakao_h["Authorization"];

        $kakao['tmp_number'] = $kakao_d->tmp_number;
        $kakao['kakao_url'] = $kakao_d->kakao_url;
        $kakao['kakao_sender']= $kakao_d->kakao_sender;
        $kakao['kakao_name']= $kakao_d->kakao_name;
        $kakao['kakao_phone']= $kakao_d->kakao_phone;
        $kakao['kakao_add1']= $kakao_d->kakao_add1;
        $kakao['kakao_add2']= $kakao_d->kakao_add2;
        $kakao['kakao_add3']= $kakao_d->kakao_add3;
        $kakao['kakao_add4']= $kakao_d->kakao_add4;
        $kakao['kakao_add5']= $kakao_d->kakao_add5;
        $kakao['kakao_add6']= $kakao_d->kakao_add6;
        $kakao['kakao_add7']= $kakao_d->kakao_add7;
        $kakao['kakao_add8']= $kakao_d->kakao_add8;
        $kakao['kakao_add9']= $kakao_d->kakao_add9;
        $kakao['kakao_add10']= $kakao_d->kakao_add10;
        $kakao['kakao_080']= $kakao_d->kakao_080;
        $kakao['kakao_res']= $kakao_d->kakao_res;
        $kakao['kakao_res_date']= $kakao_d->kakao_res_date;
        $kakao['tran_replace_type']= $kakao_d->TRAN_REPLACE_TYPE;
        $kakao['kakao_url1_1']= $kakao_d->kakao_url1_1;
        $kakao['kakao_url1_2']= $kakao_d->kakao_url1_2;
        $kakao['kakao_url2_1']= $kakao_d->kakao_url2_1;
        $kakao['kakao_url2_2']= $kakao_d->kakao_url2_2;
        $kakao['kakao_url3_1']= $kakao_d->kakao_url3_1;
        $kakao['kakao_url3_2']= $kakao_d->kakao_url3_2;
        $kakao['kakao_url4_1']= $kakao_d->kakao_url4_1;
        $kakao['kakao_url4_2']= $kakao_d->kakao_url4_2;
        $kakao['kakao_url5_1']= $kakao_d->kakao_url5_1;
        $kakao['kakao_url5_2']= $kakao_d->kakao_url5_2;

        //Log_message("ERROR", "Alimtalk API : profile_key => ".$kakao['profile_key'].", tmp_number => ".$kakao['tmp_number'].", kakao_sender => ".$kakao['kakao_sender']);
        //Log_message("ERROR", "Alimtalk API : kakao_name => ".$kakao['kakao_name'].", kakao_phone => ".$kakao['kakao_phone'].", kakao_add1 => ".$kakao['kakao_add1']);
        //Log_message("ERROR", "Alimtalk API : kakao_add2 => ".$kakao['kakao_add2'].", kakao_add3 => ".$kakao['kakao_add3'].", kakao_add4 => ".$kakao['kakao_add4']);
        //Log_message("ERROR", "Alimtalk API : kakao_add5 => ".$kakao['kakao_add5'].", kakao_add6 => ".$kakao['kakao_add6'].", kakao_add7 => ".$kakao['kakao_add7']);
        //Log_message("ERROR", "Alimtalk API : kakao_add8 => ".$kakao['kakao_add8'].", kakao_add9 => ".$kakao['kakao_add9'].", kakao_add10 => ".$kakao['kakao_add10']);
        //Log_message("ERROR", "Alimtalk API : kakao_080 => ".$kakao['kakao_080'].", kakao_res => ".$kakao['kakao_res'].", kakao_res_date => ".$kakao['kakao_res_date']);
        //Log_message("ERROR", "Alimtalk API : tran_replace_type => ".$kakao['tran_replace_type'].", kakao_url1_1 => ".$kakao['kakao_url1_1'].", kakao_url1_2 => ".$kakao['kakao_url1_2']);
        //Log_message("ERROR", "Alimtalk API : kakao_url2_1 => ".$kakao['kakao_url2_1'].", kakao_url2_2 => ".$kakao['kakao_url2_2'].", kakao_url3_1 => ".$kakao['kakao_url3_1']);
        //Log_message("ERROR", "Alimtalk API : kakao_url3_2 => ".$kakao['kakao_url3_2'].", kakao_url4_1 => ".$kakao['kakao_url4_1'].", kakao_url4_1 => ".$kakao['kakao_url4_1']);
        //Log_message("ERROR", "Alimtalk API : kakao_url5_1 => ".$kakao['kakao_url5_1'].", kakao_url5_2 => ".$kakao['kakao_url5_2']);
        $respo_no = 0;
        $respo_msg = "success";

        $this->db->insert('kakao_api_call', $kakao);
        if ($this->db->error()['code'] == 0) {
            if(!empty($kakao['profile_key'])) {
                //$cnt = $this->biz_model->get_table_count("cb_wt_send_profile", "spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'");
                // $sql = "select count(1) as cnt,max(b.mem_userid) as userid, max(b.mem_id) as mem_id , max(a.spf_sms_callback) as sender_callback from cb_wt_send_profile a inner join cb_member b on b.mem_id = a.spf_mem_id where spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'";
                $sql = "select count(1) as cnt,max(b.mem_userid) as userid, max(b.mem_id) as mem_id , max(a.spf_sms_callback) as sender_callback from cb_wt_send_profile_dhn a inner join cb_member b on b.mem_id = a.spf_mem_id where spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'";
                $profile = $this->db->query($sql)->row();
                $cnt = $profile->cnt;
                $userid = $profile->userid;
                $mem_id = $profile->mem_id;
                $sender_callback = $profile->sender_callback;

                // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                $sql2 = "select mad_mem_id, mad_price_at, mad_price_ft, mad_price_ft_img, mad_price_grs, mad_price_grs_sms, mad_price_grs_mms, mad_price_nas, mad_price_nas_sms, mad_price_nas_mms, mad_price_ft_w_img, mad_price_smt, mad_price_smt_sms, mad_price_smt_mms from cb_wt_member_addon where mad_mem_id=".$mem_id;
                $price = $this->db->query($sql2)->row();
                $mst_price_at = $price->mad_price_at;
                $mst_price_ft = $price->mad_price_ft;
                $mst_price_ft_img = $price->mad_price_ft_img;
                $mst_price_ft_w_img = $price->mad_price_ft_w_img;
                $mst_price_grs = $price->mad_price_grs;
                $mst_price_grs_sms = $price->mad_price_grs_sms;
                $mst_price_grs_mms = $price->mad_price_grs_mms;
                $mst_price_nas = $price->mad_price_nas;
                $mst_price_nas_sms = $price->mad_price_nas_sms;
                $mst_price_nas_mms = $price->mad_price_nas_mms;
                $mst_price_smt = $price->mad_price_smt;
                $mst_price_smt_sms = $price->mad_price_smt_sms;
                $mst_price_smt_mms = $price->mad_price_smt_mms;


                if($cnt>0) {
                    /*$sql = "select *
                              from cb_wt_template
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_profile_key = '".$kakao['profile_key']."'
                            ";*/
                    $sql = "select *
                              from cb_wt_template_dhn
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_profile_key = '".$kakao['profile_key']."'
                            ";
                    $tmp = $this->db->query($sql)->row();

                    if(empty($tmp)) {
                        /*$sql = "select *
                              from cb_wt_template
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_mem_id = '1'
                            ";*/
                        $sql = "select *
                              from cb_wt_template_dhn
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_mem_id = '1'
                            ";
                        $tmp = $this->db->query($sql)->row();

                        log_message('ERROR', " TEMP : ".$sql);
                    }

                    if ($this->db->error()['code'] == 0) {
                        if(!empty($tmp->tpl_id)) {
                            $tmp_str = $tmp->tpl_contents;

                            $pattern = '/\#{[^}]*}/';
                            $m_cnt = preg_match_all($pattern, $tmp_str, $match );
                            $idx = 0;
                            $m_idx = 0;

                            while($m_cnt >= 1) {
                                //log_message('ERROR', 'MAT : '.$match[0][$idx]);
                                if($match[0][$idx] === '#{고객명}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_name,$tmp_str);
                                    $m_idx = 1;
                                    #{추가정보1}
                                } else if($match[0][$idx] === '#{추가정보1}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add1,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보2}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add2,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보3}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add3,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보4}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add4,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보5}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add5,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보6}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add6,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보7}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add7,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보8}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add8,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보9}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add9,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보10}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add10,$tmp_str);
                                    //                                 } else if($match[0][$idx] === '#{변수}') {
                                    //                                     if ($idx == 0) {
                                    //                                         $tmp_str= preg_replace('/\#{변수}/', $kakao_d->kakao_add1,$tmp_str, 1);
                                    //                                     } else if ($idx == 1) {
                                    //                                         $tmp_str= preg_replace('/\#{변수}/', $kakao_d->kakao_add2,$tmp_str, 1);
                                    //                                     } else if ($idx == 2) {
                                    //                                         $tmp_str= preg_replace('/\#{변수}/', $kakao_d->kakao_add3,$tmp_str, 1);
                                    //                                     } else if ($idx == 3) {
                                    //                                         $tmp_str= preg_replace('/\#{변수}/', $kakao_d->kakao_add4,$tmp_str, 1);
                                    //                                     } else if ($idx == 4) {
                                    //                                         $tmp_str= preg_replace('/\#{변수}/', $kakao_d->kakao_add5,$tmp_str, 1);
                                    //                                     } else if ($idx == 5) {
                                    //                                         $tmp_str= preg_replace('/\#{변수}/', $kakao_d->kakao_add6,$tmp_str, 1);
                                    //                                     } else if ($idx == 6) {
                                    //                                         $tmp_str= preg_replace('/\#{변수}/', $kakao_d->kakao_add7,$tmp_str, 1);
                                    //                                     } else if ($idx == 7) {
                                    //                                         $tmp_str= preg_replace('/\#{변수}/', $kakao_d->kakao_add8,$tmp_str, 1);
                                    //                                     } else if ($idx == 8) {
                                    //                                         $tmp_str= preg_replace('/\#{변수}/', $kakao_d->kakao_add9,$tmp_str, 1);
                                    //                                     } else if ($idx == 9) {
                                    //                                         $tmp_str= preg_replace('/\#{변수}/', $kakao_d->kakao_add10,$tmp_str, 1);
                                    //                                     }
                                } else {
                                    $variable = '/\\'.$match[0][$idx].'/';
                                    //log_message('ERROR', 'variable : '.$variable);
                                    if (($idx - $m_idx) == 0) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add1,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 1) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add2,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 2) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add3,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 3) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add4,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 4) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add5,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 5) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add6,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 6) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add7,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 7) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add8,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 8) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add9,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 9) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add10,$tmp_str, 1);
                                    }
                                }
                                $m_cnt = $m_cnt - 1;
                                $idx = $idx + 1;
                            }

                            $btns = json_decode($tmp->tpl_button);
                            $btn = array();
                            $btn_cnt = count($btns);
                            $idx = 0;

                            $kakao_url_m = array($kakao['kakao_url1_1'], $kakao['kakao_url2_1'], $kakao['kakao_url3_1'], $kakao['kakao_url4_1'], $kakao['kakao_url5_1']);
                            $kakao_url_pc = array($kakao['kakao_url1_2'], $kakao['kakao_url2_2'], $kakao['kakao_url3_2'], $kakao['kakao_url4_2'], $kakao['kakao_url5_2']);

                            //echo var_dump($btns)."<BR>";
                            while($btn_cnt >=1) {
                                $btnstr = array();
                                $btnstr['type'] = $btns[$idx]->linkType;
                                $btnstr['name'] = $btns[$idx]->name;

                                if (strpos($btns[$idx]->linkMo, "#{") !==false) {
                                    if (empty($kakao_url_m[$idx])) {
                                        $btnstr['url_mobile'] = $btns[$idx]->linkMo;
                                    } else {
                                        $loc = 0;
                                        if(strpos($btns[$idx]->linkMo, "://") !== false){
                                            $loc = strpos($btns[$idx]->linkMo, "://") + 3;
                                        }
                                        $pre_url = substr($btns[$idx]->linkMo, 0, $loc);
                                        $loc = 0;
                                        if(strpos($kakao_url_m[$idx], "://") !== false){
                                            $loc = strpos($kakao_url_m[$idx], "://") + 3;
                                        }
                                        $aft_url = substr($kakao_url_m[$idx],$loc);
                                        $btnstr['url_mobile'] = $pre_url.$aft_url;
                                    }
                                } else {
                                    $btnstr['url_mobile'] = $btns[$idx]->linkMo;
                                }

                                if (strpos($btns[$idx]->linkPc, "#{") !==false) {
                                    if (empty($kakao_url_pc[$idx])) {
                                        $btnstr['url_pc'] = $btns[$idx]->linkPc;
                                    } else {
                                        $loc = 0;
                                        if(strpos($btns[$idx]->linkPc, "://") !== false){
                                            $loc = strpos($btns[$idx]->linkPc, "://") + 3;
                                        }
                                        $pre_url = substr($btns[$idx]->linkPc, 0, $loc);
                                        $loc = 0;
                                        if(strpos($kakao_url_pc[$idx], "://") !== false){
                                            $loc = strpos($kakao_url_pc[$idx], "://") + 3;
                                        }
                                        $aft_url = substr($kakao_url_pc[$idx],$loc);
                                        $btnstr['url_pc'] = $pre_url.$aft_url;
                                    }
                                } else {
                                    $btnstr['url_pc'] = $btns[$idx]->linkPc;
                                }

                                $btn[$idx] = json_encode($btnstr,JSON_UNESCAPED_UNICODE);
                                $btn[$idx] = str_replace("\\","",$btn[$idx]);
                                $btn_cnt = $btn_cnt - 1;
                                //echo var_dump($btn[$idx])."<BR>";
                                $idx = $idx + 1;
                            }

                            if(!$this->db->table_exists('cb_api_sc_'.$userid)) {
                                $sql = "CREATE TABLE `cb_api_sc_".$userid."` (
                                                	`sc_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '식별번호',
                                                	`sc_name` VARCHAR(100) NULL DEFAULT '' COMMENT '고객명',
                                                	`sc_tel` VARCHAR(15) NULL DEFAULT '' COMMENT '전화번호',
                                                	`sc_content` VARCHAR(5000) NULL DEFAULT '' COMMENT '내용(친구톡/알림톡)',
                                                	`sc_button1` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼1',
                                                	`sc_button2` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼2',
                                                	`sc_button3` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼3',
                                                	`sc_button4` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼4',
                                                	`sc_button5` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼5',
                                                	`sc_sms_yn` CHAR(1) NULL DEFAULT 'N' COMMENT 'SMS재발신여부',
                                                	`sc_lms_content` VARCHAR(5000) NULL DEFAULT '' COMMENT '내용LMS',
                                                	`sc_sms_callback` VARCHAR(15) NULL DEFAULT '' COMMENT 'SMS발신번호',
                                                	`sc_img_url` VARCHAR(1000) NULL DEFAULT '' COMMENT '이미지URL',
                                                	`sc_img_link` VARCHAR(1000) NULL DEFAULT '' COMMENT '이미지Link',
                                                	`sc_template` VARCHAR(30) NULL DEFAULT '' COMMENT '템플릿코드',
                                                	`sc_p_com` VARCHAR(5) NULL DEFAULT '' COMMENT '택배사코드',
                                                	`sc_p_invoice` VARCHAR(100) NULL DEFAULT '' COMMENT '택배송장번호',
                                                	`sc_s_code` VARCHAR(5) NULL DEFAULT '' COMMENT '쇼핑몰코드',
                                                	`sc_reserve_dt` VARCHAR(14) NULL DEFAULT '' COMMENT '예약전송일시',
                                                	`sc_status` VARCHAR(1) NULL DEFAULT '0' COMMENT '상테',
                                                    `sc_group_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Group ID',
                                                	PRIMARY KEY (`sc_id`),
                                                    INDEX `sc_group_id` (`sc_group_id`)
                                                )
                                                COLLATE='utf8_general_ci'
                                                ENGINE=InnoDB
                                                AUTO_INCREMENT=1";

                                $this->db->query($sql);
                            }

                            $sc = array();
                            $sc['sc_tel'] = str_replace('-', '', $kakao['kakao_phone']);
                            $sc['sc_content'] = $tmp_str;
                            $sc['sc_button1'] = $btn[0];
                            $sc['sc_button2'] = $btn[1];
                            $sc['sc_button3'] = $btn[2];
                            $sc['sc_button4'] = $btn[3];
                            $sc['sc_button5'] = $btn[4];
                            $sc['sc_sms_yn'] = 'L';
                            /*$sc['sc_lms_content'] = '';*/
                            $sc['sc_sms_callback'] = str_replace('-', '', $kakao_d->kakao_sender);
                            /*$sc['sc_img_url']*/
                            /*$sc['sc_img_link']*/
                            $sc['sc_template'] = $kakao['tmp_number'];
                            /*$sc['sc_p_com']
                             $sc['sc_p_invoice']
                             $sc['sc_s_code']
                             $sc['sc_reserve_dt']*/
                            $this->db->insert('cb_api_sc_'.$userid, $sc);

                            if ($this->db->error()['code'] > 0) {
                                $respo_no = 106;
                                $respo_msg = "API Table Insert Error( 관리자에게 문의 하세요. )";

                            } else {
                                $sc_id = $this->db->insert_id();

                                //- 발송내역에 기록
                                $data = array();
                                $data['mst_mem_id'] = $mem_id;
                                //$data['mst_template'] = '';
								$data['mst_template'] = $kakao['tmp_number']; //2020-12-11 추가
                                $data['mst_profile'] = $kakao['profile_key'];
                                $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
                                $data['mst_kind'] =  "at";
                                $data['mst_content'] =  $tmp_str;
                                $data['mst_sms_send'] =  "";
                                $data['mst_lms_send'] =  "";
                                $data['mst_mms_send'] =  "";
                                $data['mst_sms_content'] =  '';
                                $data['mst_lms_content'] =  "";
                                $data['mst_mms_content'] =  "";
                                $data['mst_img_content'] =  "";
                                //$data['mst_button'] = json_encode(array($btn[0], $btn[1], $btn[2], $btn[3], $btn[4]), JSON_UNESCAPED_UNICODE);
								$data['mst_button'] = json_encode(array("[". $btn[0] ."]", "[". $btn[1] ."]", "[". $btn[2] ."]", "[". $btn[3] ."]", "[". $btn[4] ."]"), JSON_UNESCAPED_UNICODE); //2020-12-11
                                $data['mst_reserved_dt'] =  "00000000000000";
                                $data['mst_sms_callback'] =  $sender_callback; //$kakao_d->kakao_sender;
                                $data['mst_status'] = '1';
                                $data['mst_qty'] =  1;
                                $data['mst_amount'] = 0;	// ($this->Biz_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));
                                // 2019-02-18 이수환 추가
                                $data['mst_type1'] = "at";
                                // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                                $data['mst_price_at'] = $mst_price_at;
                                $data['mst_price_ft'] = $mst_price_ft;
                                $data['mst_price_ft_img'] = $mst_price_ft_img;
                                $data['mst_price_ft_w_img'] = $mst_price_ft_w_img;
                                $data['mst_price_grs'] = $mst_price_grs;
                                $data['mst_price_grs_sms'] = $mst_price_grs_sms;
                                $data['mst_price_grs_mms'] = $mst_price_grs_mms;
                                $data['mst_price_nas'] = $mst_price_nas;
                                $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                                $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                                $data['mst_price_nas'] = $mst_price_nas;
                                $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                                $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                                $data['mst_smt_price'] = $mst_price_smt;
                                $data['mst_price_smt_sms'] = $mst_price_smt_sms;
                                $data['mst_price_smt_mms'] = $mst_price_smt_mms;

                                //echo '<pre> :: ';print_r($data);
                                //exit;

                                $this->db->insert("cb_wt_msg_sent", $data);
                                $gid = $this->db->insert_id();


                                $this->load->library('Dhnkakao');
                                $this->dhnkakao->sendAgentSendAPI('at', $gid,$mem_id, $userid,$kakao['profile_key'],'00000000000000', $sc_id);
                            }

                        } else {
                            $respo_no = 105;
                            $respo_msg = "템플릿 코드를 확인 하세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "템플릿 코드를 확인 하세요.";
                    }
                } else {
                    $respo_no = 103;
                    $respo_msg = "인증키를 확인 하세요.";
                }
            } else {
                $respo_no = 102;
                $respo_msg = "인증키를 확인 하세요.";
            }
        } else {
            $respo_no = 101;
            $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
            Log_message("ERROR", "Alimtalk API Database 오류 : ( ".$this->db->error()['message']." )");
        }

        header('Content-Type: application/json');
        echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'"}';
        // echo var_dump($kakao_d);
        /*
         * {"response_code":406,"response_message":"Auth Key is needed"}
         *
         $bank_num = $this->input->post('_bank_num');
         $sender = $this->input->post('_sender');
         $money = $this->input->post('_money');
         $oid = $this->input->post('_oid');
         $usmsid = $this->input->post('_usmsid');
         $remain = $this->input->post('_remain');

         $req = $this->db->query("select * from cb_deposit where dep_id = '".$oid."' and status <> 'OK'" )->row();

         $this->db->select_sum('dep_deposit');
         $this->db->where(array('mem_id' => $req->mem_id, 'dep_status' => 1));
         $result = $this->db->get('deposit');
         $dpsum = $result->row_array();

         $sum = isset($dpsum['dep_deposit']) ? $dpsum['dep_deposit'] : 0;

         if( $req->dep_cash_request == $money) {
         $deposit_sum = $sum + $money;
         $updatedata['dep_deposit_datetime'] = cdate('Y-m-d H:i:s');
         $updatedata['dep_cash'] = $money;
         $updatedata['dep_deposit'] = $money;
         $updatedata['dep_deposit_sum'] = $deposit_sum;
         $updatedata['dep_status'] = 1;
         $updatedata['dep_admin_memo'] = '자동입금처리';
         $updatedata['status'] = 'OK';

         $this->db->where('dep_id', $req->dep_id);
         $this->db->update('deposit',$updatedata );
         $this->depositlib->update_member_deposit($req->mem_id);
         */
    }

    public function alimtalk_test()
    {
        $kakao_h = getallheaders();
        $kakao_d = json_decode(file_get_contents('php://input'));

        $kakao = array();

        $kakao['profile_key'] = $kakao_h["Authorization"];

        $kakao['tmp_number'] = $kakao_d->tmp_number;
        $kakao['kakao_url'] = $kakao_d->kakao_url;
        $kakao['kakao_sender']= $kakao_d->kakao_sender;
        $kakao['kakao_name']= $kakao_d->kakao_name;
        $kakao['kakao_phone']= $kakao_d->kakao_phone;
        $kakao['kakao_add1']= $kakao_d->kakao_add1;
        $kakao['kakao_add2']= $kakao_d->kakao_add2;
        $kakao['kakao_add3']= $kakao_d->kakao_add3;
        $kakao['kakao_add4']= $kakao_d->kakao_add4;
        $kakao['kakao_add5']= $kakao_d->kakao_add5;
        $kakao['kakao_add6']= $kakao_d->kakao_add6;
        $kakao['kakao_add7']= $kakao_d->kakao_add7;
        $kakao['kakao_add8']= $kakao_d->kakao_add8;
        $kakao['kakao_add9']= $kakao_d->kakao_add9;
        $kakao['kakao_add10']= $kakao_d->kakao_add10;
        $kakao['kakao_080']= $kakao_d->kakao_080;
        $kakao['kakao_res']= $kakao_d->kakao_res;
        $kakao['kakao_res_date']= $kakao_d->kakao_res_date;
        $kakao['tran_replace_type']= $kakao_d->TRAN_REPLACE_TYPE;
        $kakao['kakao_url1_1']= $kakao_d->kakao_url1_1;
        $kakao['kakao_url1_2']= $kakao_d->kakao_url1_2;
        $kakao['kakao_url2_1']= $kakao_d->kakao_url2_1;
        $kakao['kakao_url2_2']= $kakao_d->kakao_url2_2;
        $kakao['kakao_url3_1']= $kakao_d->kakao_url3_1;
        $kakao['kakao_url3_2']= $kakao_d->kakao_url3_2;
        $kakao['kakao_url4_1']= $kakao_d->kakao_url4_1;
        $kakao['kakao_url4_2']= $kakao_d->kakao_url4_2;
        $kakao['kakao_url5_1']= $kakao_d->kakao_url5_1;
        $kakao['kakao_url5_2']= $kakao_d->kakao_url5_2;
        $kakao['kakao_2nd']= $kakao_d->kakao_2nd;

        $respo_no = 0;
        $respo_msg = "success";

        $this->db->insert('kakao_api_call', $kakao);
        if ($this->db->error()['code'] == 0) {
            if(!empty($kakao['profile_key'])) {
                //$cnt = $this->biz_model->get_table_count("cb_wt_send_profile", "spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'");
                //$sql = "select count(1) as cnt,max(b.mem_userid) as userid, max(b.mem_id) as mem_id, b.mem_2nd_send  from cb_wt_send_profile a inner join cb_member b on b.mem_id = a.spf_mem_id where spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'";
                $sql = "select count(1) as cnt,max(b.mem_userid) as userid, max(b.mem_id) as mem_id, b.mem_2nd_send  from cb_wt_send_profile_dhn a inner join cb_member b on b.mem_id = a.spf_mem_id where spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'";
                //log_message('ERROR', '/bizmsgapi/alimtalk_test > sql : '. $sql);
                $cnt = $this->db->query($sql)->row()->cnt;
                $userid = $this->db->query($sql)->row()->userid;
                $mem_id = $this->db->query($sql)->row()->mem_id;
                $mem_2nd_send = $this->db->query($sql)->row()->mem_2nd_send;

                // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                $sql2 = "select mad_mem_id, mad_price_at, mad_price_ft, mad_price_ft_img, mad_price_grs, mad_price_grs_sms, mad_price_grs_mms, mad_price_nas, mad_price_nas_sms, mad_price_nas_mms, mad_price_ft_w_img, mad_price_smt, mad_price_smt_sms, mad_price_smt_mms from cb_wt_member_addon where mad_mem_id=".$mem_id;
                //log_message('ERROR', '/bizmsgapi/alimtalk_test > sql2 : '. $sql2);
                $price = $this->db->query($sql2)->row();
                $mst_price_at = $price->mad_price_at;
                $mst_price_ft = $price->mad_price_ft;
                $mst_price_ft_img = $price->mad_price_ft_img;
                $mst_price_ft_w_img = $price->mad_price_ft_w_img;
                $mst_price_grs = $price->mad_price_grs;
                $mst_price_grs_sms = $price->mad_price_grs_sms;
                $mst_price_grs_mms = $price->mad_price_grs_mms;
                $mst_price_nas = $price->mad_price_nas;
                $mst_price_nas_sms = $price->mad_price_nas_sms;
                $mst_price_nas_mms = $price->mad_price_nas_mms;
                $mst_price_smt = $price->mad_price_smt;
                $mst_price_smt_sms = $price->mad_price_smt_sms;
                $mst_price_smt_mms = $price->mad_price_smt_mms;

                if($cnt>0) {
                    //$sql = "select * from cb_wt_template where tpl_code = '".$kakao['tmp_number']."' and tpl_profile_key = '".$kakao['profile_key']."'";
                    $sql = "select * from cb_wt_template_dhn where tpl_code = '".$kakao['tmp_number']."' and tpl_profile_key = '".$kakao['profile_key']."'";
                    //log_message('ERROR', '/bizmsgapi/alimtalk_test > sql : '. $sql);
                    $tmp = $this->db->query($sql)->row();
                    if(empty($tmp)) {
                        //$sql = "select * from cb_wt_template where tpl_code = '".$kakao['tmp_number']."' and tpl_mem_id = '1'";
                        $sql = "select * from cb_wt_template_dhn where tpl_code = '".$kakao['tmp_number']."' and tpl_mem_id = '0'";
                        $tmp = $this->db->query($sql)->row();
                        //log_message('ERROR', "/bizmsgapi/alimtalk_test > sql : ". $sql);
                    }

                    if ($this->db->error()['code'] == 0) {
                        if(!empty($tmp->tpl_id)) {
                            $tmp_str = $tmp->tpl_contents;

                            $pattern = '/\#{[^}]*}/';
                            $m_cnt = preg_match_all($pattern, $tmp_str, $match );
                            $idx = 0;
                            $m_idx = 0;

                            while($m_cnt >= 1) {
                                //log_message('ERROR', 'MAT : '.$match[0][$idx]);
                                if($match[0][$idx] === '#{고객명}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_name,$tmp_str);
                                    $m_idx = 1;
                                    #{추가정보1}
                                } else if($match[0][$idx] === '#{추가정보1}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add1,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보2}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add2,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보3}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add3,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보4}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add4,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보5}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add5,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보6}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add6,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보7}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add7,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보8}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add8,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보9}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add9,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보10}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add10,$tmp_str);
                                } else {
                                    $variable = '/\\'.$match[0][$idx].'/';
                                    //log_message('ERROR', 'variable : '.$variable);
                                    if (($idx - $m_idx) == 0) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add1,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 1) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add2,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 2) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add3,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 3) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add4,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 4) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add5,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 5) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add6,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 6) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add7,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 7) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add8,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 8) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add9,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 9) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add10,$tmp_str, 1);
                                    }
                                }
                                $m_cnt = $m_cnt - 1;
                                $idx = $idx + 1;
                            }

                            $btns = json_decode($tmp->tpl_button);
                            $btn = array();
                            $btn_cnt = count($btns);
                            $idx = 0;

                            $kakao_url_m = array($kakao['kakao_url1_1'], $kakao['kakao_url2_1'], $kakao['kakao_url3_1'], $kakao['kakao_url4_1'], $kakao['kakao_url5_1']);
                            $kakao_url_pc = array($kakao['kakao_url1_2'], $kakao['kakao_url2_2'], $kakao['kakao_url3_2'], $kakao['kakao_url4_2'], $kakao['kakao_url5_2']);

                            //echo var_dump($btns)."<BR>";
                            while($btn_cnt >=1) {
                                $btnstr = array();
                                $btnstr['type'] = $btns[$idx]->linkType;
                                $btnstr['name'] = $btns[$idx]->name;

                                if (strpos($btns[$idx]->linkMo, "#{") !==false) {
                                    if (empty($kakao_url_m[$idx])) {
                                        $btnstr['url_mobile'] = $btns[$idx]->linkMo;
                                    } else {
                                        $loc = 0;
                                        if(strpos($btns[$idx]->linkMo, "://") !== false){
                                            $loc = strpos($btns[$idx]->linkMo, "://") + 3;
                                        }
                                        $pre_url = substr($btns[$idx]->linkMo, 0, $loc);
                                        $loc = 0;
                                        if(strpos($kakao_url_m[$idx], "://") !== false){
                                            $loc = strpos($kakao_url_m[$idx], "://") + 3;
                                        }
                                        $aft_url = substr($kakao_url_m[$idx],$loc);
                                        $btnstr['url_mobile'] = $pre_url.$aft_url;
                                    }
                                } else {
                                    $btnstr['url_mobile'] = $btns[$idx]->linkMo;
                                }

                                if (strpos($btns[$idx]->linkPc, "#{") !==false) {
                                    if (empty($kakao_url_pc[$idx])) {
                                        $btnstr['url_pc'] = $btns[$idx]->linkPc;
                                    } else {
                                        $loc = 0;
                                        if(strpos($btns[$idx]->linkPc, "://") !== false){
                                            $loc = strpos($btns[$idx]->linkPc, "://") + 3;
                                        }
                                        $pre_url = substr($btns[$idx]->linkPc, 0, $loc);
                                        $loc = 0;
                                        if(strpos($kakao_url_pc[$idx], "://") !== false){
                                            $loc = strpos($kakao_url_pc[$idx], "://") + 3;
                                        }
                                        $aft_url = substr($kakao_url_pc[$idx],$loc);
                                        $btnstr['url_pc'] = $pre_url.$aft_url;
                                    }
                                } else {
                                    $btnstr['url_pc'] = $btns[$idx]->linkPc;
                                }
								$btn[$idx] = json_encode($btnstr,JSON_UNESCAPED_UNICODE);
                                $btn[$idx] = str_replace("\\","",$btn[$idx]);
                                $btn_cnt = $btn_cnt - 1;
                                //echo var_dump($btn[$idx])."<BR>";
                                $idx = $idx + 1;
                            }

                            $exist = $this->db->query("show tables like 'cb_api_sc_".$userid."'")->result_array();
                            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
                            //- API 처럼 사용자이면 테이별 생성
                            if(count($exist) < 1) {
                                $sql = "CREATE TABLE `cb_api_sc_".$userid."` (
                                    `sc_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '식별번호',
                                    `sc_name` VARCHAR(100) NULL DEFAULT '' COMMENT '고객명',
                                    `sc_tel` VARCHAR(15) NULL DEFAULT '' COMMENT '전화번호',
                                    `sc_content` VARCHAR(5000) NULL DEFAULT '' COMMENT '내용(친구톡/알림톡)',
                                    `sc_button1` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼1',
                                    `sc_button2` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼2',
                                    `sc_button3` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼3',
                                    `sc_button4` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼4',
                                    `sc_button5` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼5',
                                    `sc_sms_yn` CHAR(1) NULL DEFAULT 'N' COMMENT 'SMS재발신여부',
                                    `sc_lms_content` VARCHAR(5000) NULL DEFAULT '' COMMENT '내용LMS',
                                    `sc_sms_callback` VARCHAR(15) NULL DEFAULT '' COMMENT 'SMS발신번호',
                                    `sc_img_url` VARCHAR(1000) NULL DEFAULT '' COMMENT '이미지URL',
                                    `sc_img_link` VARCHAR(1000) NULL DEFAULT '' COMMENT '이미지Link',
                                    `sc_template` VARCHAR(30) NULL DEFAULT '' COMMENT '템플릿코드',
                                    `sc_p_com` VARCHAR(5) NULL DEFAULT '' COMMENT '택배사코드',
                                    `sc_p_invoice` VARCHAR(100) NULL DEFAULT '' COMMENT '택배송장번호',
                                    `sc_s_code` VARCHAR(5) NULL DEFAULT '' COMMENT '쇼핑몰코드',
                                    `sc_reserve_dt` VARCHAR(14) NULL DEFAULT '' COMMENT '예약전송일시',
                                    `sc_status` VARCHAR(1) NULL DEFAULT '0' COMMENT '상테',
                                     `sc_group_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Group ID',
                                    PRIMARY KEY (`sc_id`),
                                    INDEX `sc_group_id` (`sc_group_id`)
                                    )
                                    COLLATE='utf8_general_ci'
                                    ENGINE=InnoDB
                                    AUTO_INCREMENT=1
                                    ";

                                $this->db->query($sql);
                                if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
                            }

                            // 2019-11-04 삭제부분 빠져서 추가
                            $del = "delete from cb_api_sc_".$userid;
                            $this->db->query($del);

                            $mem_send = '';
                            $sms_kind = '';

                            //log_message("ERROR", "/bizmsgapi/alimtalk_test > kakao_2nd : ". $kakao['kakao_2nd']);
							if(!empty($kakao['kakao_2nd']) && $kakao['kakao_2nd'] == 'Y' ){
                                $mem_send = $mem_2nd_send;
                                $msg_length = mb_strwidth($tmp_str, 'UTF-8');
                                if($msg_length > 90) {
                                    $sms_kind = 'L';
									//2차 문자 내용에 버튼정보 추가 시작 2021-03-05 ---------------
									$tmp_lms_str = $tmp_str;
									$sms_button = "";
									if(!empty($btns)){
										$tmp_no = 0;
										$sms_button .= "\n\n";
										foreach ($btns as $arr) {
											$tmp_no++;
											//echo "tmp_no : ". $tmp_no ."<br>";
											if($arr->name != ""){
												$sms_button .= $arr->name ."\n". $kakao['kakao_url'. $tmp_no .'_1'];
											}
										}
									}
									$tmp_lms_str .= $sms_button;
									//log_message("ERROR", "/bizmsgapi/alimtalk_test > tmp_lms_str : ". $tmp_lms_str);
									//2차 문자 내용에 버튼정보 추가 종료 2021-03-05 ---------------
                                } else {
                                    $sms_kind = 'S';
                                }
                            }

                            //- 발송내역에 기록
                            $data = array();
                            $data['mst_mem_id'] = $mem_id;
                            //$data['mst_template'] = '';
                            $data['mst_template'] = $kakao['tmp_number']; //2020-12-11 추가
                            $data['mst_profile'] = $kakao['profile_key'];
                            $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
                            $data['mst_kind'] =  "at";
                            $data['mst_content'] =  $tmp_str;
                            $data['mst_sms_send'] =  ( $sms_kind == 'S' ? 'S': 'N');
                            $data['mst_lms_send'] =  ( $sms_kind == 'L' ? 'L': '');
                            $data['mst_mms_send'] =  "N";
                            $data['mst_sms_content'] =  ( $sms_kind == 'S' ? $tmp_str: '');
                            $data['mst_lms_content'] =  ( $sms_kind == 'L' ? $tmp_lms_str: '');
                            $data['mst_mms_content'] =  "";
                            $data['mst_img_content'] =  "";
                            //$data['mst_button'] = json_encode(array($btn[0], $btn[1], $btn[2], $btn[3], $btn[4]), JSON_UNESCAPED_UNICODE);
                            $data['mst_button'] = json_encode(array("[". $btn[0] ."]", "[". $btn[1] ."]", "[". $btn[2] ."]", "[". $btn[3] ."]", "[". $btn[4] ."]"), JSON_UNESCAPED_UNICODE); //2020-12-11
							$data['mst_reserved_dt'] =  "00000000000000";
                            $data['mst_sms_callback'] =  $kakao_d->kakao_sender;
                            $data['mst_status'] = '1';
                            $data['mst_qty'] =  1;
                            $data['mst_amount'] = 0;	// ($this->Biz_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));
                            // 2019-02-18 이수환 추가
                            $data['mst_type1'] = "at";
                            if($kakao['kakao_2nd'] == 'Y') {
                                if ($mem_2nd_send == "GREEN_SHOT") {
                                    if ($sms_kind == 'L') {
                                        $data['mst_type2'] = "wa";
                                    } else if ($sms_kind == 'S') {
                                        $data['mst_type2'] = "was";
                                    }
                                } else if ($mem_2nd_send == "NASELF") {
                                    if ($sms_kind == 'L') {
                                        $data['mst_type2'] = "wb";
                                    } else if ($sms_kind == 'S') {
                                        $data['mst_type2'] = "wbs";
                                    }
                                } else if ($mem_2nd_send == "SMART") {
                                    if ($sms_kind == 'L') {
                                        $data['mst_type2'] = "wc";
                                    } else if ($sms_kind == 'S') {
                                        $data['mst_type2'] = "wcs";
                                    }
                                }
                            }

                            // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                            $data['mst_price_at'] = $mst_price_at;
                            $data['mst_price_ft'] = $mst_price_ft;
                            $data['mst_price_ft_img'] = $mst_price_ft_img;
                            $data['mst_price_ft_w_img'] = $mst_price_ft_w_img;
                            $data['mst_price_grs'] = $mst_price_grs;
                            $data['mst_price_grs_sms'] = $mst_price_grs_sms;
                            $data['mst_price_grs_mms'] = $mst_price_grs_mms;
                            $data['mst_price_nas'] = $mst_price_nas;
                            $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                            $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                            $data['mst_price_nas'] = $mst_price_nas;
                            $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                            $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                            $data['mst_smt_price'] = $mst_price_smt;
                            $data['mst_price_smt_sms'] = $mst_price_smt_sms;
                            $data['mst_price_smt_mms'] = $mst_price_smt_mms;

                            $this->db->insert("cb_wt_msg_sent", $data);
                            $gid = $this->db->insert_id();

                            $sc = array();
                            $sc['sc_tel'] = str_replace('-','',$kakao['kakao_phone']);
                            $sc['sc_content'] = $tmp_str;
                            $sc['sc_button1'] = $btn[0];
                            $sc['sc_button2'] = $btn[1];
                            $sc['sc_button3'] = $btn[2];
                            $sc['sc_button4'] = $btn[3];
                            $sc['sc_button5'] = $btn[4];
                            $sc['sc_sms_yn'] = 'L';
                            $sc['sc_lms_content'] = $tmp_lms_str;
                            $sc['sc_sms_callback'] = str_replace('-','',$kakao_d->kakao_sender);
                            /*$sc['sc_img_url']*/
                            /*$sc['sc_img_link']*/
                            $sc['sc_template'] = $kakao['tmp_number'];
                            /*$sc['sc_p_com']
                             $sc['sc_p_invoice']
                             $sc['sc_s_code']
                             $sc['sc_reserve_dt']*/
                            $sc['sc_group_id'] = $gid;
                            $this->db->insert('cb_api_sc_'.$userid, $sc);
                            $sc_id = $this->db->insert_id();


                            $this->load->library('Dhnkakao');
                            $this->dhnkakao->sendAgent2SendAPIs('at', $gid,$mem_id, $userid,$kakao['profile_key'],'00000000000000', $sc_id, $mem_send, $sms_kind);

                        } else {
                            $respo_no = 105;
                            $respo_msg = "템플릿 코드를 확인 하세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "템플릿 코드를 확인 하세요.";
                    }
                } else {
                    $respo_no = 103;
                    $respo_msg = "인증키를 확인 하세요.";
                }
            } else {
                $respo_no = 102;
                $respo_msg = "인증키를 확인 하세요.";
            }
        } else {
            $respo_no = 101;
            $respo_msg = "Database 오류 : ".$this->db->error()['message'];
        }

        header('Content-Type: application/json');
        echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'"}';
    }


    public function alimtalk2nd()
    {
        $kakao_h = getallheaders();
        $kakao_d = json_decode(file_get_contents('php://input'));

        $kakao = array();

        $kakao['profile_key'] = $kakao_h["Authorization"];

        $kakao['tmp_number'] = $kakao_d->tmp_number;
        $kakao['kakao_url'] = $kakao_d->kakao_url;
        $kakao['kakao_sender']= $kakao_d->kakao_sender;
        $kakao['kakao_name']= $kakao_d->kakao_name;
        $kakao['kakao_phone']= $kakao_d->kakao_phone;
        $kakao['kakao_add1']= $kakao_d->kakao_add1;
        $kakao['kakao_add2']= $kakao_d->kakao_add2;
        $kakao['kakao_add3']= $kakao_d->kakao_add3;
        $kakao['kakao_add4']= $kakao_d->kakao_add4;
        $kakao['kakao_add5']= $kakao_d->kakao_add5;
        $kakao['kakao_add6']= $kakao_d->kakao_add6;
        $kakao['kakao_add7']= $kakao_d->kakao_add7;
        $kakao['kakao_add8']= $kakao_d->kakao_add8;
        $kakao['kakao_add9']= $kakao_d->kakao_add9;
        $kakao['kakao_add10']= $kakao_d->kakao_add10;
        $kakao['kakao_080']= $kakao_d->kakao_080;
        $kakao['kakao_res']= $kakao_d->kakao_res;
        $kakao['kakao_res_date']= $kakao_d->kakao_res_date;
        $kakao['tran_replace_type']= $kakao_d->TRAN_REPLACE_TYPE;
        $kakao['kakao_url1_1']= $kakao_d->kakao_url1_1;
        $kakao['kakao_url1_2']= $kakao_d->kakao_url1_2;
        $kakao['kakao_url2_1']= $kakao_d->kakao_url2_1;
        $kakao['kakao_url2_2']= $kakao_d->kakao_url2_2;
        $kakao['kakao_url3_1']= $kakao_d->kakao_url3_1;
        $kakao['kakao_url3_2']= $kakao_d->kakao_url3_2;
        $kakao['kakao_url4_1']= $kakao_d->kakao_url4_1;
        $kakao['kakao_url4_2']= $kakao_d->kakao_url4_2;
        $kakao['kakao_url5_1']= $kakao_d->kakao_url5_1;
        $kakao['kakao_url5_2']= $kakao_d->kakao_url5_2;
        $kakao['kakao_2nd']= $kakao_d->kakao_2nd;

        $respo_no = 0;
        $respo_msg = "success";

        $this->db->insert('kakao_api_call', $kakao);
        if ($this->db->error()['code'] == 0) {
            if(!empty($kakao['profile_key'])) {
                //$cnt = $this->biz_model->get_table_count("cb_wt_send_profile", "spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'");
                // $sql = "select count(1) as cnt,max(b.mem_userid) as userid, max(b.mem_id) as mem_id, b.mem_2nd_send  from cb_wt_send_profile a inner join cb_member b on b.mem_id = a.spf_mem_id where spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'";
                $sql = "select count(1) as cnt,max(b.mem_userid) as userid, max(b.mem_id) as mem_id, b.mem_2nd_send  from cb_wt_send_profile_dhn a inner join cb_member b on b.mem_id = a.spf_mem_id where spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'";
                log_message("ERROR", $sql);

                $cnt = $this->db->query($sql)->row()->cnt;
                $userid = $this->db->query($sql)->row()->userid;
                $mem_id = $this->db->query($sql)->row()->mem_id;
                $mem_2nd_send = $this->db->query($sql)->row()->mem_2nd_send;

                // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                $sql2 = "select mad_mem_id, mad_price_at, mad_price_ft, mad_price_ft_img, mad_price_grs, mad_price_grs_sms, mad_price_grs_mms, mad_price_nas, mad_price_nas_sms, mad_price_nas_mms, mad_price_ft_w_img, mad_price_smt, mad_price_smt_sms, mad_price_smt_mms from cb_wt_member_addon where mad_mem_id=".$mem_id;
                $price = $this->db->query($sql2)->row();
                $mst_price_at = $price->mad_price_at;
                $mst_price_ft = $price->mad_price_ft;
                $mst_price_ft_img = $price->mad_price_ft_img;
                $mst_price_ft_w_img = $price->mad_price_ft_w_img;
                $mst_price_grs = $price->mad_price_grs;
                $mst_price_grs_sms = $price->mad_price_grs_sms;
                $mst_price_grs_mms = $price->mad_price_grs_mms;
                $mst_price_nas = $price->mad_price_nas;
                $mst_price_nas_sms = $price->mad_price_nas_sms;
                $mst_price_nas_mms = $price->mad_price_nas_mms;
                $mst_price_smt = $price->mad_price_smt;
                $mst_price_smt_sms = $price->mad_price_smt_sms;
                $mst_price_smt_mms = $price->mad_price_smt_mms;

                if($cnt>0) {
                    /*$sql = "select *
                              from cb_wt_template
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_profile_key = '".$kakao['profile_key']."'
                            ";*/
                    $sql = "select *
                              from cb_wt_template_dhn
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_profile_key = '".$kakao['profile_key']."'
                            ";
                    $tmp = $this->db->query($sql)->row();

                    if(empty($tmp)) {
                        /*$sql = "select *
                              from cb_wt_template
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_mem_id = '1'
                            ";*/
                        $sql = "select *
                              from cb_wt_template_dhn
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_mem_id = '1'
                            ";
                        $tmp = $this->db->query($sql)->row();

                        log_message('ERROR', " TEMP : ".$sql);
                    }

                    if ($this->db->error()['code'] == 0) {
                        if(!empty($tmp->tpl_id)) {
                            $tmp_str = $tmp->tpl_contents;

                            $pattern = '/\#{[^}]*}/';
                            $m_cnt = preg_match_all($pattern, $tmp_str, $match );
                            $idx = 0;
                            $m_idx = 0;

                            while($m_cnt >= 1) {
                                //log_message('ERROR', 'MAT : '.$match[0][$idx]);
                                if($match[0][$idx] === '#{고객명}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_name,$tmp_str);
                                    $m_idx = 1;
                                    #{추가정보1}
                                } else if($match[0][$idx] === '#{추가정보1}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add1,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보2}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add2,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보3}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add3,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보4}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add4,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보5}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add5,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보6}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add6,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보7}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add7,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보8}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add8,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보9}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add9,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보10}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add10,$tmp_str);
                                } else {
                                    $variable = '/\\'.$match[0][$idx].'/';
                                    //log_message('ERROR', 'variable : '.$variable);
                                    if (($idx - $m_idx) == 0) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add1,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 1) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add2,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 2) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add3,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 3) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add4,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 4) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add5,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 5) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add6,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 6) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add7,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 7) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add8,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 8) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add9,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 9) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add10,$tmp_str, 1);
                                    }
                                }
                                $m_cnt = $m_cnt - 1;
                                $idx = $idx + 1;
                            }

                            $btns = json_decode($tmp->tpl_button);
                            $btn = array();
                            $btn_cnt = count($btns);
                            $idx = 0;

                            $kakao_url_m = array($kakao['kakao_url1_1'], $kakao['kakao_url2_1'], $kakao['kakao_url3_1'], $kakao['kakao_url4_1'], $kakao['kakao_url5_1']);
                            $kakao_url_pc = array($kakao['kakao_url1_2'], $kakao['kakao_url2_2'], $kakao['kakao_url3_2'], $kakao['kakao_url4_2'], $kakao['kakao_url5_2']);

                            //echo var_dump($btns)."<BR>";
                            while($btn_cnt >=1) {
                                $btnstr = array();
                                $btnstr['type'] = $btns[$idx]->linkType;
                                $btnstr['name'] = $btns[$idx]->name;

                                if (strpos($btns[$idx]->linkMo, "#{") !==false) {
                                    if (empty($kakao_url_m[$idx])) {
                                        $btnstr['url_mobile'] = $btns[$idx]->linkMo;
                                    } else {
                                        $loc = 0;
                                        if(strpos($btns[$idx]->linkMo, "://") !== false){
                                            $loc = strpos($btns[$idx]->linkMo, "://") + 3;
                                        }
                                        $pre_url = substr($btns[$idx]->linkMo, 0, $loc);
                                        $loc = 0;
                                        if(strpos($kakao_url_m[$idx], "://") !== false){
                                            $loc = strpos($kakao_url_m[$idx], "://") + 3;
                                        }
                                        $aft_url = substr($kakao_url_m[$idx],$loc);
                                        $btnstr['url_mobile'] = $pre_url.$aft_url;
                                    }
                                } else {
                                    $btnstr['url_mobile'] = $btns[$idx]->linkMo;
                                }

                                if (strpos($btns[$idx]->linkPc, "#{") !==false) {
                                    if (empty($kakao_url_pc[$idx])) {
                                        $btnstr['url_pc'] = $btns[$idx]->linkPc;
                                    } else {
                                        $loc = 0;
                                        if(strpos($btns[$idx]->linkPc, "://") !== false){
                                            $loc = strpos($btns[$idx]->linkPc, "://") + 3;
                                        }
                                        $pre_url = substr($btns[$idx]->linkPc, 0, $loc);
                                        $loc = 0;
                                        if(strpos($kakao_url_pc[$idx], "://") !== false){
                                            $loc = strpos($kakao_url_pc[$idx], "://") + 3;
                                        }
                                        $aft_url = substr($kakao_url_pc[$idx],$loc);
                                        $btnstr['url_pc'] = $pre_url.$aft_url;
                                    }
                                } else {
                                    $btnstr['url_pc'] = $btns[$idx]->linkPc;
                                }

                                $btn[$idx] = json_encode($btnstr,JSON_UNESCAPED_UNICODE);
                                $btn[$idx] = str_replace("\\","",$btn[$idx]);
                                $btn_cnt = $btn_cnt - 1;
                                //echo var_dump($btn[$idx])."<BR>";
                                $idx = $idx + 1;
                            }

                            $exist = $this->db->query("show tables like 'cb_api_sc_".$userid."'")->result_array();
                            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
                            //- API 처럼 사용자이면 테이별 생성
                            if(count($exist) < 1) {
                                $sql = "CREATE TABLE `cb_api_sc_".$userid."` (
                                    `sc_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '식별번호',
                                    `sc_name` VARCHAR(100) NULL DEFAULT '' COMMENT '고객명',
                                    `sc_tel` VARCHAR(15) NULL DEFAULT '' COMMENT '전화번호',
                                    `sc_content` VARCHAR(5000) NULL DEFAULT '' COMMENT '내용(친구톡/알림톡)',
                                    `sc_button1` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼1',
                                    `sc_button2` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼2',
                                    `sc_button3` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼3',
                                    `sc_button4` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼4',
                                    `sc_button5` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼5',
                                    `sc_sms_yn` CHAR(1) NULL DEFAULT 'N' COMMENT 'SMS재발신여부',
                                    `sc_lms_content` VARCHAR(5000) NULL DEFAULT '' COMMENT '내용LMS',
                                    `sc_sms_callback` VARCHAR(15) NULL DEFAULT '' COMMENT 'SMS발신번호',
                                    `sc_img_url` VARCHAR(1000) NULL DEFAULT '' COMMENT '이미지URL',
                                    `sc_img_link` VARCHAR(1000) NULL DEFAULT '' COMMENT '이미지Link',
                                    `sc_template` VARCHAR(30) NULL DEFAULT '' COMMENT '템플릿코드',
                                    `sc_p_com` VARCHAR(5) NULL DEFAULT '' COMMENT '택배사코드',
                                    `sc_p_invoice` VARCHAR(100) NULL DEFAULT '' COMMENT '택배송장번호',
                                    `sc_s_code` VARCHAR(5) NULL DEFAULT '' COMMENT '쇼핑몰코드',
                                    `sc_reserve_dt` VARCHAR(14) NULL DEFAULT '' COMMENT '예약전송일시',
                                    `sc_status` VARCHAR(1) NULL DEFAULT '0' COMMENT '상테',
                                     `sc_group_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Group ID',
                                    PRIMARY KEY (`sc_id`),
                                    INDEX `sc_group_id` (`sc_group_id`)
                                    )
                                    COLLATE='utf8_general_ci'
                                    ENGINE=InnoDB
                                    AUTO_INCREMENT=1
                                    ";

                                $this->db->query($sql);
                                if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
                            }

                            // 2019-11-04 삭제부분 빠져서 추가
                            $del = "delete from cb_api_sc_".$userid;
                            $this->db->query($del);

                            $mem_send = '';
                            $sms_kind = '';

                            if(!empty($kakao['kakao_2nd']) && $kakao['kakao_2nd'] == 'Y' ) {
                                $mem_send = $mem_2nd_send;
                                $msg_length = mb_strwidth($tmp_str, 'UTF-8');
                                if($msg_length > 90) {
                                    $sms_kind = 'L';
                                } else {
                                    $sms_kind = 'S';
                                }
                            }

                            //- 발송내역에 기록
                            $data = array();
                            $data['mst_mem_id'] = $mem_id;
                            //$data['mst_template'] = '';
                            $data['mst_template'] = $kakao['tmp_number']; //2020-12-11 추가
                            $data['mst_profile'] = $kakao['profile_key'];
                            $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
                            $data['mst_kind'] =  "at";
                            $data['mst_content'] =  $tmp_str;
                            $data['mst_sms_send'] =  ( $sms_kind == 'S' ? 'S': 'N');
                            $data['mst_lms_send'] =  ( $sms_kind == 'L' ? 'L': '');
                            $data['mst_mms_send'] =  "N";
                            $data['mst_sms_content'] =  ( $sms_kind == 'S' ? $tmp_str: '');
                            $data['mst_lms_content'] =  ( $sms_kind == 'L' ? $tmp_str: '');
                            $data['mst_mms_content'] =  "";
                            $data['mst_img_content'] =  "";
                            //$data['mst_button'] = json_encode(array($btn[0], $btn[1], $btn[2], $btn[3], $btn[4]), JSON_UNESCAPED_UNICODE);
							$data['mst_button'] = json_encode(array("[". $btn[0] ."]", "[". $btn[1] ."]", "[". $btn[2] ."]", "[". $btn[3] ."]", "[". $btn[4] ."]"), JSON_UNESCAPED_UNICODE); //2020-12-11
                            $data['mst_reserved_dt'] =  "00000000000000";
                            $data['mst_sms_callback'] =  $kakao_d->kakao_sender;
                            $data['mst_status'] = '1';
                            $data['mst_qty'] =  1;
                            $data['mst_amount'] = 0;	// ($this->Biz_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));
                            // 2019-02-18 이수환 추가
                            $data['mst_type1'] = "at";
                            if($kakao['kakao_2nd'] == 'Y') {
                                if ($mem_2nd_send == "GREEN_SHOT") {
                                    if ($sms_kind == 'L') {
                                        $data['mst_type2'] = "wa";
                                    } else if ($sms_kind == 'S') {
                                        $data['mst_type2'] = "was";
                                    }
                                } else if ($mem_2nd_send == "NASELF") {
                                    if ($sms_kind == 'L') {
                                        $data['mst_type2'] = "wb";
                                    } else if ($sms_kind == 'S') {
                                        $data['mst_type2'] = "wbs";
                                    }
                                } else if ($mem_2nd_send == "SMART") {
                                    if ($sms_kind == 'L') {
                                        $data['mst_type2'] = "wc";
                                    } else if ($sms_kind == 'S') {
                                        $data['mst_type2'] = "wcs";
                                    }
                                }
                            }

                            // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                            $data['mst_price_at'] = $mst_price_at;
                            $data['mst_price_ft'] = $mst_price_ft;
                            $data['mst_price_ft_img'] = $mst_price_ft_img;
                            $data['mst_price_ft_w_img'] = $mst_price_ft_w_img;
                            $data['mst_price_grs'] = $mst_price_grs;
                            $data['mst_price_grs_sms'] = $mst_price_grs_sms;
                            $data['mst_price_grs_mms'] = $mst_price_grs_mms;
                            $data['mst_price_nas'] = $mst_price_nas;
                            $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                            $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                            $data['mst_price_nas'] = $mst_price_nas;
                            $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                            $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                            $data['mst_smt_price'] = $mst_price_smt;
                            $data['mst_price_smt_sms'] = $mst_price_smt_sms;
                            $data['mst_price_smt_mms'] = $mst_price_smt_mms;

                            $this->db->insert("cb_wt_msg_sent", $data);
                            $gid = $this->db->insert_id();

                            $sc = array();
                            $sc['sc_tel'] = str_replace('-','',$kakao['kakao_phone']);
                            $sc['sc_content'] = $tmp_str;
                            $sc['sc_button1'] = $btn[0];
                            $sc['sc_button2'] = $btn[1];
                            $sc['sc_button3'] = $btn[2];
                            $sc['sc_button4'] = $btn[3];
                            $sc['sc_button5'] = $btn[4];
                            $sc['sc_sms_yn'] = 'L';
                            $sc['sc_lms_content'] = $tmp_str;
                            $sc['sc_sms_callback'] = str_replace('-','',$kakao_d->kakao_sender);
                            /*$sc['sc_img_url']*/
                            /*$sc['sc_img_link']*/
                            $sc['sc_template'] = $kakao['tmp_number'];
                            /*$sc['sc_p_com']
                             $sc['sc_p_invoice']
                             $sc['sc_s_code']
                             $sc['sc_reserve_dt']*/
                            $sc['sc_group_id'] = $gid;
                            $this->db->insert('cb_api_sc_'.$userid, $sc);
                            $sc_id = $this->db->insert_id();


                            $this->load->library('Dhnkakao');
                            $this->dhnkakao->sendAgent2SendAPIs('at', $gid,$mem_id, $userid,$kakao['profile_key'],'00000000000000', $sc_id, $mem_send, $sms_kind);

                        } else {
                            $respo_no = 105;
                            $respo_msg = "템플릿 코드를 확인 하세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "템플릿 코드를 확인 하세요.";
                    }
                } else {
                    $respo_no = 103;
                    $respo_msg = "인증키를 확인 하세요.";
                }
            } else {
                $respo_no = 102;
                $respo_msg = "인증키를 확인 하세요.";
            }
        } else {
            $respo_no = 101;
            $respo_msg = "Database 오류 : ".$this->db->error()['message'];
        }

        header('Content-Type: application/json');
        echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'"}';
    }

    public function alimtalk2nds()
    {
        $kakao_h = getallheaders();
        $kakao_d = json_decode(file_get_contents('php://input'));

        $kakao = array();

        $kakao['profile_key'] = $kakao_h["Authorization"];

        $kakao['tmp_number'] = $kakao_d->tmp_number;
        $kakao['kakao_url'] = $kakao_d->kakao_url;
        $kakao['kakao_sender']= $kakao_d->kakao_sender;
        $kakao['kakao_name']= $kakao_d->kakao_name;
        $kakao['kakao_phone']= $kakao_d->kakao_phone;
        $kakao['kakao_add1']= $kakao_d->kakao_add1;
        $kakao['kakao_add2']= $kakao_d->kakao_add2;
        $kakao['kakao_add3']= $kakao_d->kakao_add3;
        $kakao['kakao_add4']= $kakao_d->kakao_add4;
        $kakao['kakao_add5']= $kakao_d->kakao_add5;
        $kakao['kakao_add6']= $kakao_d->kakao_add6;
        $kakao['kakao_add7']= $kakao_d->kakao_add7;
        $kakao['kakao_add8']= $kakao_d->kakao_add8;
        $kakao['kakao_add9']= $kakao_d->kakao_add9;
        $kakao['kakao_add10']= $kakao_d->kakao_add10;
        $kakao['kakao_080']= $kakao_d->kakao_080;
        $kakao['kakao_res']= $kakao_d->kakao_res;
        $kakao['kakao_res_date']= $kakao_d->kakao_res_date;
        $kakao['tran_replace_type']= $kakao_d->TRAN_REPLACE_TYPE;
        $kakao['kakao_url1_1']= $kakao_d->kakao_url1_1;
        $kakao['kakao_url1_2']= $kakao_d->kakao_url1_2;
        $kakao['kakao_url2_1']= $kakao_d->kakao_url2_1;
        $kakao['kakao_url2_2']= $kakao_d->kakao_url2_2;
        $kakao['kakao_url3_1']= $kakao_d->kakao_url3_1;
        $kakao['kakao_url3_2']= $kakao_d->kakao_url3_2;
        $kakao['kakao_url4_1']= $kakao_d->kakao_url4_1;
        $kakao['kakao_url4_2']= $kakao_d->kakao_url4_2;
        $kakao['kakao_url5_1']= $kakao_d->kakao_url5_1;
        $kakao['kakao_url5_2']= $kakao_d->kakao_url5_2;
        $kakao['kakao_2nd']= $kakao_d->kakao_2nd;

        $respo_no = 0;
        $respo_msg = "success";

        $this->db->insert('kakao_api_call', $kakao);
        if ($this->db->error()['code'] == 0) {
            if(!empty($kakao['profile_key'])) {
                //$cnt = $this->biz_model->get_table_count("cb_wt_send_profile", "spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'");
                // $sql = "select count(1) as cnt,max(b.mem_userid) as userid, max(b.mem_id) as mem_id  from cb_wt_send_profile a inner join cb_member b on b.mem_id = a.spf_mem_id where spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'";
                $sql = "select count(1) as cnt,max(b.mem_userid) as userid, max(b.mem_id) as mem_id  from cb_wt_send_profile_dhn a inner join cb_member b on b.mem_id = a.spf_mem_id where spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'";
                $cnt = $this->db->query($sql)->row()->cnt;
                $userid = $this->db->query($sql)->row()->userid;
                $mem_id = $this->db->query($sql)->row()->mem_id;

                // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                $sql2 = "select mad_mem_id, mad_price_at, mad_price_ft, mad_price_ft_img, mad_price_grs, mad_price_grs_sms, mad_price_grs_mms, mad_price_nas, mad_price_nas_sms, mad_price_nas_mms, mad_price_ft_w_img, mad_price_smt, mad_price_smt_sms, mad_price_smt_mms from cb_wt_member_addon where mad_mem_id=".$mem_id;
                $price = $this->db->query($sql2)->row();
                $mst_price_at = $price->mad_price_at;
                $mst_price_ft = $price->mad_price_ft;
                $mst_price_ft_img = $price->mad_price_ft_img;
                $mst_price_ft_w_img = $price->mad_price_ft_w_img;
                $mst_price_grs = $price->mad_price_grs;
                $mst_price_grs_sms = $price->mad_price_grs_sms;
                $mst_price_grs_mms = $price->mad_price_grs_mms;
                $mst_price_nas = $price->mad_price_nas;
                $mst_price_nas_sms = $price->mad_price_nas_sms;
                $mst_price_nas_mms = $price->mad_price_nas_mms;
                $mst_price_smt = $price->mad_price_smt;
                $mst_price_smt_sms = $price->mad_price_smt_sms;
                $mst_price_smt_mms = $price->mad_price_smt_mms;

                if($cnt>0) {
                    /*$sql = "select *
                              from cb_wt_template
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_profile_key = '".$kakao['profile_key']."'
                            ";*/
                    $sql = "select *
                              from cb_wt_template_dhn
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_profile_key = '".$kakao['profile_key']."'
                            ";
                    $tmp = $this->db->query($sql)->row();

                    if(empty($tmp)) {
                        /*$sql = "select *
                              from cb_wt_template
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_mem_id = '1'
                            ";*/
                        $sql = "select *
                              from cb_wt_template_dhn
                             where tpl_code = '".$kakao['tmp_number']."'
                               and tpl_mem_id = '1'
                            ";
                        $tmp = $this->db->query($sql)->row();

                        log_message('ERROR', " TEMP : ".$sql);
                    }

                    if ($this->db->error()['code'] == 0) {
                        if(!empty($tmp->tpl_id)) {
                            $tmp_str = $tmp->tpl_contents;

                            $pattern = '/\#{[^}]*}/';
                            $m_cnt = preg_match_all($pattern, $tmp_str, $match );
                            $idx = 0;
                            $m_idx = 0;

                            while($m_cnt >= 1) {
                                //log_message('ERROR', 'MAT : '.$match[0][$idx]);
                                if($match[0][$idx] === '#{고객명}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_name,$tmp_str);
                                    #{추가정보1}
                                } else if($match[0][$idx] === '#{추가정보1}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add1,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보2}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add2,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보3}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add3,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보4}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add4,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보5}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add5,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보6}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add6,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보7}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add7,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보8}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add8,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보9}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add9,$tmp_str);
                                } else if($match[0][$idx] === '#{추가정보10}') {
                                    $tmp_str= str_replace($match[0][$idx], $kakao_d->kakao_add10,$tmp_str);
                                } else {
                                    $variable = '/\\'.$match[0][$idx].'/';
                                    //log_message('ERROR', 'variable : '.$variable);
                                    if (($idx - $m_idx) == 0) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add1,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 1) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add2,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 2) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add3,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 3) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add4,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 4) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add5,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 5) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add6,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 6) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add7,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 7) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add8,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 8) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add9,$tmp_str, 1);
                                    } else if (($idx - $m_idx) == 9) {
                                        $tmp_str= preg_replace($variable, $kakao_d->kakao_add10,$tmp_str, 1);
                                    }
                                }
                                $m_cnt = $m_cnt - 1;
                                $idx = $idx + 1;
                            }

                            $btns = json_decode($tmp->tpl_button);
                            $btn = array();
                            $btn_cnt = count($btns);
                            $idx = 0;

                            $kakao_url_m = array($kakao['kakao_url1_1'], $kakao['kakao_url2_1'], $kakao['kakao_url3_1'], $kakao['kakao_url4_1'], $kakao['kakao_url5_1']);
                            $kakao_url_pc = array($kakao['kakao_url1_2'], $kakao['kakao_url2_2'], $kakao['kakao_url3_2'], $kakao['kakao_url4_2'], $kakao['kakao_url5_2']);

                            //echo var_dump($btns)."<BR>";
                            while($btn_cnt >=1) {
                                $btnstr = array();
                                $btnstr['type'] = $btns[$idx]->linkType;
                                $btnstr['name'] = $btns[$idx]->name;

                                if (strpos($btns[$idx]->linkMo, "#{") !==false) {
                                    if (empty($kakao_url_m[$idx])) {
                                        $btnstr['url_mobile'] = $btns[$idx]->linkMo;
                                    } else {
                                        $loc = 0;
                                        if(strpos($btns[$idx]->linkMo, "://") !== false){
                                            $loc = strpos($btns[$idx]->linkMo, "://") + 3;
                                        }
                                        $pre_url = substr($btns[$idx]->linkMo, 0, $loc);
                                        $loc = 0;
                                        if(strpos($kakao_url_m[$idx], "://") !== false){
                                            $loc = strpos($kakao_url_m[$idx], "://") + 3;
                                        }
                                        $aft_url = substr($kakao_url_m[$idx],$loc);
                                        $btnstr['url_mobile'] = $pre_url.$aft_url;
                                    }
                                } else {
                                    $btnstr['url_mobile'] = $btns[$idx]->linkMo;
                                }

                                if (strpos($btns[$idx]->linkPc, "#{") !==false) {
                                    if (empty($kakao_url_pc[$idx])) {
                                        $btnstr['url_pc'] = $btns[$idx]->linkPc;
                                    } else {
                                        $loc = 0;
                                        if(strpos($btns[$idx]->linkPc, "://") !== false){
                                            $loc = strpos($btns[$idx]->linkPc, "://") + 3;
                                        }
                                        $pre_url = substr($btns[$idx]->linkPc, 0, $loc);
                                        $loc = 0;
                                        if(strpos($kakao_url_pc[$idx], "://") !== false){
                                            $loc = strpos($kakao_url_pc[$idx], "://") + 3;
                                        }
                                        $aft_url = substr($kakao_url_pc[$idx],$loc);
                                        $btnstr['url_pc'] = $pre_url.$aft_url;
                                    }
                                } else {
                                    $btnstr['url_pc'] = $btns[$idx]->linkPc;
                                }

                                $btn[$idx] = json_encode($btnstr,JSON_UNESCAPED_UNICODE);
                                $btn[$idx] = str_replace("\\","",$btn[$idx]);
                                $btn_cnt = $btn_cnt - 1;
                                //echo var_dump($btn[$idx])."<BR>";
                                $idx = $idx + 1;
                            }

                            $exist = $this->db->query("show tables like 'cb_api_sc_".$userid."'")->result_array();
                            if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
                            //- API 처럼 사용자이면 테이별 생성
                            if(count($exist) < 1) {
                                $sql = "CREATE TABLE `cb_api_sc_".$userid."` (
                                    `sc_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '식별번호',
                                    `sc_name` VARCHAR(100) NULL DEFAULT '' COMMENT '고객명',
                                    `sc_tel` VARCHAR(15) NULL DEFAULT '' COMMENT '전화번호',
                                    `sc_content` VARCHAR(5000) NULL DEFAULT '' COMMENT '내용(친구톡/알림톡)',
                                    `sc_button1` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼1',
                                    `sc_button2` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼2',
                                    `sc_button3` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼3',
                                    `sc_button4` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼4',
                                    `sc_button5` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼5',
                                    `sc_sms_yn` CHAR(1) NULL DEFAULT 'N' COMMENT 'SMS재발신여부',
                                    `sc_lms_content` VARCHAR(5000) NULL DEFAULT '' COMMENT '내용LMS',
                                    `sc_sms_callback` VARCHAR(15) NULL DEFAULT '' COMMENT 'SMS발신번호',
                                    `sc_img_url` VARCHAR(1000) NULL DEFAULT '' COMMENT '이미지URL',
                                    `sc_img_link` VARCHAR(1000) NULL DEFAULT '' COMMENT '이미지Link',
                                    `sc_template` VARCHAR(30) NULL DEFAULT '' COMMENT '템플릿코드',
                                    `sc_p_com` VARCHAR(5) NULL DEFAULT '' COMMENT '택배사코드',
                                    `sc_p_invoice` VARCHAR(100) NULL DEFAULT '' COMMENT '택배송장번호',
                                    `sc_s_code` VARCHAR(5) NULL DEFAULT '' COMMENT '쇼핑몰코드',
                                    `sc_reserve_dt` VARCHAR(14) NULL DEFAULT '' COMMENT '예약전송일시',
                                    `sc_status` VARCHAR(1) NULL DEFAULT '0' COMMENT '상테',
                                    `sc_group_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Group ID',
                                    PRIMARY KEY (`sc_id`),
                                    INDEX `sc_group_id` (`sc_group_id`)
                                    )
                                    COLLATE='utf8_general_ci'
                                    ENGINE=InnoDB
                                    AUTO_INCREMENT=1
                                    ";

                                $this->db->query($sql);
                                if ($this->db->error()['code'] > 0) { echo '<pre> :: ';print_r($this->db->error()); exit; }
                            }

                            $del = "delete from cb_api_sc_".$userid;
                            $this->db->query($del);

                            $phns = explode(',', $kakao['kakao_phone']);
                            $msg_cnt = 0;


                            $mem_send = 'SMART';
                            $sms_kind = 'L';

                            if(!empty($kakao['kakao_2nd']) && $kakao['kakao_2nd'] == 'Y' ) {
                                $mem_send = 'SMART';
                                $msg_length = mb_strwidth($tmp_str, 'UTF-8');
                                if($msg_length > 90) {
                                    $sms_kind = 'L';
                                } else {
                                    $sms_kind = 'S';
                                }
                            }

                            $data = array();
                            $data['mst_mem_id'] = $mem_id;
                            //$data['mst_template'] = '';
							$data['mst_template'] = $kakao['tmp_number']; //2020-12-11 추가
                            $data['mst_profile'] = $kakao['profile_key'];
                            $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
                            $data['mst_kind'] =  "at";
                            $data['mst_content'] =  $tmp_str;
                            $data['mst_sms_send'] =  ( $sms_kind == 'S' ? 'S': 'N');
                            $data['mst_lms_send'] =  ( $sms_kind == 'L' ? 'L': '');
                            $data['mst_mms_send'] =  "N";
                            $data['mst_sms_content'] =  ( $sms_kind == 'S' ? $tmp_str: '');
                            $data['mst_lms_content'] =  ( $sms_kind == 'L' ? $tmp_str: '');
                            $data['mst_mms_content'] =  "";
                            $data['mst_img_content'] =  "";
                            //$data['mst_button'] = json_encode(array($btn[0], $btn[1], $btn[2], $btn[3], $btn[4]), JSON_UNESCAPED_UNICODE);
							$data['mst_button'] = json_encode(array("[". $btn[0] ."]", "[". $btn[1] ."]", "[". $btn[2] ."]", "[". $btn[3] ."]", "[". $btn[4] ."]"), JSON_UNESCAPED_UNICODE); //2020-12-11
                            $data['mst_reserved_dt'] =  "00000000000000";
                            $data['mst_sms_callback'] =  $kakao_d->kakao_sender;
                            $data['mst_status'] = '1';
                            $data['mst_qty'] =  1;
                            $data['mst_amount'] = 0;	// ($this->Biz_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));

                            // 2019-02-18 이수환 추가
                            $data['mst_type1'] = "at";
                            if($kakao['kakao_2nd'] == 'Y') {
                                if ($mem_2nd_send == "GREEN_SHOT") {
                                    if ($sms_kind == 'L') {
                                        $data['mst_type2'] = "wa";
                                    } else if ($sms_kind == 'S') {
                                        $data['mst_type2'] = "was";
                                    }
                                } else if ($mem_2nd_send == "NASELF") {
                                    if ($sms_kind == 'L') {
                                        $data['mst_type2'] = "wb";
                                    } else if ($sms_kind == 'S') {
                                        $data['mst_type2'] = "wbs";
                                    }
                                } else if ($mem_2nd_send == "SMART") {
                                    if ($sms_kind == 'L') {
                                        $data['mst_type2'] = "wc";
                                    } else if ($sms_kind == 'S') {
                                        $data['mst_type2'] = "wcs";
                                    }
                                }
                            }

                            // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                            $data['mst_price_at'] = $mst_price_at;
                            $data['mst_price_ft'] = $mst_price_ft;
                            $data['mst_price_ft_img'] = $mst_price_ft_img;
                            $data['mst_price_ft_w_img'] = $mst_price_ft_w_img;
                            $data['mst_price_grs'] = $mst_price_grs;
                            $data['mst_price_grs_sms'] = $mst_price_grs_sms;
                            $data['mst_price_grs_mms'] = $mst_price_grs_mms;
                            $data['mst_price_nas'] = $mst_price_nas;
                            $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                            $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                            $data['mst_price_nas'] = $mst_price_nas;
                            $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                            $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                            $data['mst_smt_price'] = $mst_price_smt;
                            $data['mst_price_smt_sms'] = $mst_price_smt_sms;
                            $data['mst_price_smt_mms'] = $mst_price_smt_mms;

                            //echo '<pre> :: ';print_r($data);
                            //exit;

                            $this->db->insert("cb_wt_msg_sent", $data);
                            $gid = $this->db->insert_id();

                            foreach($phns as $phn) {

                                $sc = array();
                                $sc['sc_tel'] = str_replace('-','',$phn);
                                $sc['sc_content'] = $tmp_str;
                                $sc['sc_button1'] = $btn[0];
                                $sc['sc_button2'] = $btn[1];
                                $sc['sc_button3'] = $btn[2];
                                $sc['sc_button4'] = $btn[3];
                                $sc['sc_button5'] = $btn[4];
                                $sc['sc_sms_yn'] = 'L';
                                $sc['sc_lms_content'] = $tmp_str;
                                $sc['sc_sms_callback'] = str_replace('-','',$kakao_d->kakao_sender);
                                /*$sc['sc_img_url']*/
                                /*$sc['sc_img_link']*/
                                $sc['sc_template'] = $kakao['tmp_number'];
                                /*$sc['sc_p_com']
                                 $sc['sc_p_invoice']
                                 $sc['sc_s_code']
                                 $sc['sc_reserve_dt']*/
                                $sc['sc_group_id'] = $gid;
                                $this->db->insert('cb_api_sc_'.$userid, $sc);
                                $this->db->insert_id();
                            }

                            $this->load->library('Dhnkakao');
                            $this->dhnkakao->sendAgent2SendAPIs('at', $gid,$mem_id, $userid,$kakao['profile_key'],'00000000000000', $sc_id, $mem_send, $sms_kind);

                        } else {
                            $respo_no = 105;
                            $respo_msg = "템플릿 코드를 확인 하세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "템플릿 코드를 확인 하세요.";
                    }
                } else {
                    $respo_no = 103;
                    $respo_msg = "인증키를 확인 하세요.";
                }
            } else {
                $respo_no = 102;
                $respo_msg = "인증키를 확인 하세요.";
            }
        } else {
            $respo_no = 101;
            $respo_msg = "Database 오류";
        }

        header('Content-Type: application/json');
        echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'"}';
    }
            
    public function testalimtk()
    {
        $kakao_h = getallheaders();
        $kakao_d = json_decode(file_get_contents('php://input'));
        $debug = file_get_contents('php://input');
        //log_message("ERROR", $debug."/".);
        $r_phn = $kakao_d->receive_phn;
        
        if(!empty($r_phn)) {
            $date = new DateTime();
            $selstr = "select count(1) as cnt from cb_wt_test_send where receive_phn = '".$r_phn."' and reg_date >='".$date->format('Y-m-d')."' ";
            //log_message("ERROR",  $_SERVER['REQUEST_URI'] ." > selstr : ". $selstr);
            $send_cnt = $this->db->query($selstr)->row();
            
            if($send_cnt->cnt < $kakao_d->day_test_cnt) {
                
                $kakao = array();
                
				//$kakao['profile_key'] = 'a1ecba7023ae98fb15039c339afd73ae2e17d515';
                //$kakao['tmp_number'] = '2020090101';
                //$kakao['kakao_url'] = '';
                //$kakao['kakao_name']= '';
                //$kakao['kakao_sender']= '0557137985';
                //$kakao['kakao_phone']= $r_phn;
                //$kakao['kakao_add1']= '대형마트';
                //$kakao['kakao_add2']= '2020년 7월7일';
                //$kakao['kakao_add3']= '1522-7985';
				//$kakao['kakao_add4']= '
//
//★점장님이쏜다!카카오톡친구에 한함/2만원이상 구매시 구매가능★
//
//대파 1단 3,900원→1,980원[1인1단/100단한정]
//팽이버섯 3묶음 1,900원→500원[1인1묶음/10박스한정]
//활전복 5미 13,800원→7,900원[60팩한정]
//초코오징어(국산) 3미 14,800원→8,900원[150마리한정]
//
//▶정육코너 5만원이상 구매시 한돈찌개거리 500g증정(6천원상당)	
//
//▼정육코너
//한우모듬구이1등급(꽃등심/채끝/앞치마살) 100g 11,000원→7,590원[80팩한정]
//한우차돌박이1등급 100g 8,200원→5,580원[80팩한정]
//한우양지국거리1등급 100g 5,900원→3,980원[3마리분량한정]
//한우불고기1등급 100g 4,480원→2,990원[3마리분량한정]
//목우촌미박삼겹살 100g 2,890원→1,590원[200kg한정]
//흑돼지소금구이(구이/수육용) 100g 1,950원→990원[200kg한정]
//한돈냉장목살 100g 2,480원→990원[1인3kg한정/300kg한정]
//생오리양념불고기 100g 1,580원→850원[100kg한정]
//';
                //$kakao['kakao_add5']= '';
                //$kakao['kakao_add6']= '1522-7985';
                //$kakao['kakao_add7']= '';
                //$kakao['kakao_add8']= '';
                //$kakao['kakao_add9']= '';
                //$kakao['kakao_add10']= '';
                //$kakao['kakao_080']= '';
                //$kakao['kakao_res']= '';
                //$kakao['kakao_res_date']= '';
                //$kakao['tran_replace_type']= '';
                //$kakao['kakao_url1_1']= 'http://igenie.co.kr/smart/view/eSNlpqmw';
                //$kakao['kakao_url1_2']= 'http://igenie.co.kr/smart/view/eSNlpqmw';
                //$kakao['kakao_url2_1']= 'http://swmart.pfmall.co.kr';
                //$kakao['kakao_url2_2']= 'http://swmart.pfmall.co.kr';
				//$respo_no = 0;
                //$respo_msg = "요청하신 번호로 알림톡이 발송되었습니다.";

                $kakao['profile_key'] = $kakao_d->profile_key;
                $kakao['tmp_number'] =$kakao_d->tmp_number;
                $kakao['kakao_url'] = $kakao_d->kakao_url;
                $kakao['kakao_name']= $kakao_d->kakao_name;
				$kakao['kakao_sender']= $kakao_d->kakao_sender;
                $kakao['kakao_phone']= $r_phn;
                $kakao['kakao_add1']= $kakao_d->kakao_add1;
                $kakao['kakao_add2']= $kakao_d->kakao_add2;
                $kakao['kakao_add3']= $kakao_d->kakao_add3;
                $kakao['kakao_add4']= $kakao_d->kakao_add4;
                $kakao['kakao_add5']= $kakao_d->kakao_add5;
                $kakao['kakao_add6']= $kakao_d->kakao_add6;
                $kakao['kakao_add7']= $kakao_d->kakao_add7;
                $kakao['kakao_add8']= $kakao_d->kakao_add8;
                $kakao['kakao_add9']= $kakao_d->kakao_add9;
                $kakao['kakao_add10']= $kakao_d->kakao_add10;
                $kakao['kakao_080']= $kakao_d->kakao_080;
                $kakao['kakao_res']= $kakao_d->kakao_res;
                $kakao['kakao_res_date']= $kakao_d->kakao_res_date;
                $kakao['tran_replace_type']= $kakao_d->tran_replace_type;
                $kakao['kakao_url1_1']= $kakao_d->kakao_url1_1;
                $kakao['kakao_url1_2']= $kakao_d->kakao_url1_2;
                $kakao['kakao_url2_1']= $kakao_d->kakao_url2_1;
                $kakao['kakao_url2_2']= $kakao_d->kakao_url2_2;
                $kakao['kakao_url3_1']= $kakao_d->kakao_url3_1;
                $kakao['kakao_url3_2']= $kakao_d->kakao_url3_2;
                $kakao['kakao_url4_1']= $kakao_d->kakao_url4_1;
                $kakao['kakao_url4_2']= $kakao_d->kakao_url4_2;
                $kakao['kakao_url5_1']= $kakao_d->kakao_url5_1;
                $kakao['kakao_url5_2']= $kakao_d->kakao_url5_2;
                $respo_no = 0;
                $respo_msg = $kakao_d->respo_msg;
                
                $this->db->insert('kakao_api_call', $kakao);
                if ($this->db->error()['code'] == 0) {
                    if(!empty($kakao['profile_key'])) {
                        //$cnt = $this->biz_model->get_table_count("cb_wt_send_profile", "spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'");
                        $sql = "select count(1) as cnt,max(b.mem_userid) as userid, max(b.mem_id) as mem_id , max(a.spf_sms_callback) as sender_callback from cb_wt_send_profile_dhn a inner join cb_member b on b.mem_id = a.spf_mem_id where spf_key = '".$kakao['profile_key']."' and spf_use = 'Y'";
                        //log_message("ERROR",  $_SERVER['REQUEST_URI'] ." > selstr : ". $sql);
						$profile = $this->db->query($sql)->row();
                        $cnt = $profile->cnt;
                        $userid = $profile->userid;
                        $mem_id = $profile->mem_id;
                        $sender_callback = $profile->sender_callback;
                        
                        // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                        $sql2 = "select mad_mem_id, mad_price_at, mad_price_ft, mad_price_ft_img, mad_price_grs, mad_price_grs_sms, mad_price_grs_mms, mad_price_nas, mad_price_nas_sms, mad_price_nas_mms, mad_price_ft_w_img, mad_price_smt, mad_price_smt_sms, mad_price_smt_mms from cb_wt_member_addon where mad_mem_id=".$mem_id;
                        $price = $this->db->query($sql2)->row();
                        $mst_price_at = $price->mad_price_at;
                        $mst_price_ft = $price->mad_price_ft;
                        $mst_price_ft_img = $price->mad_price_ft_img;
                        $mst_price_ft_w_img = $price->mad_price_ft_w_img;
                        $mst_price_grs = $price->mad_price_grs;
                        $mst_price_grs_sms = $price->mad_price_grs_sms;
                        $mst_price_grs_mms = $price->mad_price_grs_mms;
                        $mst_price_nas = $price->mad_price_nas;
                        $mst_price_nas_sms = $price->mad_price_nas_sms;
                        $mst_price_nas_mms = $price->mad_price_nas_mms;
                        $mst_price_smt = $price->mad_price_smt;
                        $mst_price_smt_sms = $price->mad_price_smt_sms;
                        $mst_price_smt_mms = $price->mad_price_smt_mms;
                        
                        
                        if($cnt>0) {
                            $sql = "select *
                                      from cb_wt_template_dhn
                                     where tpl_code = '".$kakao['tmp_number']."'
                                       and tpl_profile_key = '".$kakao['profile_key']."'
                                    ";
                            //log_message('ERROR', " sql : ".$sql);
							$tmp = $this->db->query($sql)->row();
                            
                            if(empty($tmp)) {
                                $sql = "select *
                                      from cb_wt_template_dhn
                                     where tpl_code = '".$kakao['tmp_number']."'
                                       and tpl_mem_id in (0, 1)
                                    ";
                                //log_message('ERROR', " sql : ".$sql);
                                $tmp = $this->db->query($sql)->row();
                            }
                            
                            if ($this->db->error()['code'] == 0) {
                                if(!empty($tmp->tpl_id)) {
                                    $tmp_str = $tmp->tpl_contents;
                                    
                                    $pattern = '/\#{[^}]*}/';
                                    $m_cnt = preg_match_all($pattern, $tmp_str, $match );
                                    $idx = 0;
                                    $m_idx = 0;
                                    
                                    while($m_cnt >= 1) {
                                        //log_message('ERROR', 'MAT : '.$match[0][$idx]);
                                        if($match[0][$idx] === '#{고객명}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_name'],$tmp_str);
                                            $m_idx = 1;
                                            #{추가정보1}
                                        } else if($match[0][$idx] === '#{추가정보1}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_add1'],$tmp_str);
                                        } else if($match[0][$idx] === '#{추가정보2}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_add2'],$tmp_str);
                                        } else if($match[0][$idx] === '#{추가정보3}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_add3'],$tmp_str);
                                        } else if($match[0][$idx] === '#{추가정보4}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_add4'],$tmp_str);
                                        } else if($match[0][$idx] === '#{추가정보5}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_add5'],$tmp_str);
                                        } else if($match[0][$idx] === '#{추가정보6}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_add6'],$tmp_str);
                                        } else if($match[0][$idx] === '#{추가정보7}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_add7'],$tmp_str);
                                        } else if($match[0][$idx] === '#{추가정보8}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_add8'],$tmp_str);
                                        } else if($match[0][$idx] === '#{추가정보9}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_add9'],$tmp_str);
                                        } else if($match[0][$idx] === '#{추가정보10}') {
                                            $tmp_str= str_replace($match[0][$idx], $kakao['kakao_add10'],$tmp_str);
                                        } else {
                                            $variable = '/\\'.$match[0][$idx].'/';
                                            //log_message('ERROR', 'variable : '.$variable);
                                            if (($idx - $m_idx) == 0) {
                                                $tmp_str= preg_replace($variable, $kakao['kakao_add1'],$tmp_str, 1);
                                            } else if (($idx - $m_idx) == 1) {
                                                $tmp_str= preg_replace($variable, $kakao['kakao_add2'],$tmp_str, 1);
                                            } else if (($idx - $m_idx) == 2) {
                                                $tmp_str= preg_replace($variable, $kakao['kakao_add3'],$tmp_str, 1);
                                            } else if (($idx - $m_idx) == 3) {
                                                $tmp_str= preg_replace($variable, $kakao['kakao_add4'],$tmp_str, 1);
                                            } else if (($idx - $m_idx) == 4) {
                                                $tmp_str= preg_replace($variable, $kakao['kakao_add5'],$tmp_str, 1);
                                            } else if (($idx - $m_idx) == 5) {
                                                $tmp_str= preg_replace($variable, $kakao['kakao_add6'],$tmp_str, 1);
                                            } else if (($idx - $m_idx) == 6) {
                                                $tmp_str= preg_replace($variable, $kakao['kakao_add7'],$tmp_str, 1);
                                            } else if (($idx - $m_idx) == 7) {
                                                $tmp_str= preg_replace($variable, $kakao['kakao_add8'],$tmp_str, 1);
                                            } else if (($idx - $m_idx) == 8) {
                                                $tmp_str= preg_replace($variable, $kakao['kakao_add9'],$tmp_str, 1);
                                            } else if (($idx - $m_idx) == 9) {
                                                $tmp_str= preg_replace($variable, $kakao['kakao_add10'],$tmp_str, 1);
                                            }
                                        }
                                        $m_cnt = $m_cnt - 1;
                                        $idx = $idx + 1;
                                        //log_message('ERROR', 'variable : '.$tmp_str);
                                    }
                                    
                                    $btns = json_decode($tmp->tpl_button);
                                    $btn = array();
                                    $btn_cnt = count($btns);
                                    $idx = 0;
                                    
                                    $murl = array('kakao_url1_1', 'kakao_url2_1','kakao_url3_1','kakao_url4_1','kakao_url5_1');
                                    $pcurl = array('kakao_url1_2', 'kakao_url2_2','kakao_url3_2','kakao_url4_2','kakao_url5_2');
                                    
                                    while($btn_cnt >=1) {
                                        $btnstr = array();
                                        $btnstr['type'] = $btns[$idx]->linkType;
                                        $btnstr['name'] = $btns[$idx]->name;
                                        $btnstr['url_mobile'] = $kakao[$murl[$idx]];
                                        $btnstr['url_pc'] = $kakao[$pcurl[$idx]];
                                        
                                        $btn[$idx] = json_encode($btnstr,JSON_UNESCAPED_UNICODE);
                                        $btn[$idx] = str_replace("\\","",$btn[$idx]);
                                        $btn_cnt = $btn_cnt - 1;
                                        //echo var_dump($btn[$idx])."<BR>";
                                        $idx = $idx + 1;
                                    }
                                    
                                    if(!$this->db->table_exists('cb_api_sc_'.$userid)) {
                                        $sql = "CREATE TABLE `cb_api_sc_".$userid."` (
                                                        	`sc_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '식별번호',
                                                        	`sc_name` VARCHAR(100) NULL DEFAULT '' COMMENT '고객명',
                                                        	`sc_tel` VARCHAR(15) NULL DEFAULT '' COMMENT '전화번호',
                                                        	`sc_content` VARCHAR(5000) NULL DEFAULT '' COMMENT '내용(친구톡/알림톡)',
                                                        	`sc_button1` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼1',
                                                        	`sc_button2` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼2',
                                                        	`sc_button3` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼3',
                                                        	`sc_button4` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼4',
                                                        	`sc_button5` VARCHAR(600) NULL DEFAULT '' COMMENT '버튼5',
                                                        	`sc_sms_yn` CHAR(1) NULL DEFAULT 'N' COMMENT 'SMS재발신여부',
                                                        	`sc_lms_content` VARCHAR(5000) NULL DEFAULT '' COMMENT '내용LMS',
                                                        	`sc_sms_callback` VARCHAR(15) NULL DEFAULT '' COMMENT 'SMS발신번호',
                                                        	`sc_img_url` VARCHAR(1000) NULL DEFAULT '' COMMENT '이미지URL',
                                                        	`sc_img_link` VARCHAR(1000) NULL DEFAULT '' COMMENT '이미지Link',
                                                        	`sc_template` VARCHAR(30) NULL DEFAULT '' COMMENT '템플릿코드',
                                                        	`sc_p_com` VARCHAR(5) NULL DEFAULT '' COMMENT '택배사코드',
                                                        	`sc_p_invoice` VARCHAR(100) NULL DEFAULT '' COMMENT '택배송장번호',
                                                        	`sc_s_code` VARCHAR(5) NULL DEFAULT '' COMMENT '쇼핑몰코드',
                                                        	`sc_reserve_dt` VARCHAR(14) NULL DEFAULT '' COMMENT '예약전송일시',
                                                        	`sc_status` VARCHAR(1) NULL DEFAULT '0' COMMENT '상테',
                                                            `sc_group_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Group ID',
                                                        	PRIMARY KEY (`sc_id`),
                                                            INDEX `sc_group_id` (`sc_group_id`)
                                                        )
                                                        COLLATE='utf8_general_ci'
                                                        ENGINE=InnoDB
                                                        AUTO_INCREMENT=1";
                                        
                                        $this->db->query($sql);
                                    }
                                    
                                    $sc = array();
                                    $sc['sc_tel'] = str_replace('-', '', $kakao['kakao_phone']);
                                    $sc['sc_content'] = $tmp_str;
                                    $sc['sc_button1'] = $btn[0];
                                    $sc['sc_button2'] = $btn[1];
                                    $sc['sc_button3'] = $btn[2];
                                    $sc['sc_button4'] = $btn[3];
                                    $sc['sc_button5'] = $btn[4];
                                    $sc['sc_sms_yn'] = 'L';
                                    /*$sc['sc_lms_content'] = '';*/
                                    $sc['sc_sms_callback'] = str_replace('-', '', $kakao['kakao_sender']);
                                    /*$sc['sc_img_url']*/
                                    /*$sc['sc_img_link']*/
                                    $sc['sc_template'] = $kakao['tmp_number'];
                                    /*$sc['sc_p_com']
                                     $sc['sc_p_invoice']
                                     $sc['sc_s_code']
                                     $sc['sc_reserve_dt']*/
                                    $this->db->insert('cb_api_sc_'.$userid, $sc);
                                    $sc_id = $this->db->insert_id();
                                    //break;
                                    if ($this->db->error()['code'] > 0  ) {
                                        $respo_no = 106;
                                        $respo_msg = "API Table Insert Error( 관리자에게 문의 하세요. )";
                                        
                                    } else {
                                        //- 발송내역에 기록
                                        $data = array();
                                        $data['mst_mem_id'] = $mem_id;
                                        //$data['mst_template'] = '';
										$data['mst_template'] = $kakao['tmp_number']; //2020-12-11 추가
                                        $data['mst_profile'] = $kakao['profile_key'];
                                        $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
                                        $data['mst_kind'] =  "at";
                                        $data['mst_content'] =  $tmp_str;
                                        $data['mst_sms_send'] =  "";
                                        $data['mst_lms_send'] =  "";
                                        $data['mst_mms_send'] =  "";
                                        $data['mst_sms_content'] =  '';
                                        $data['mst_lms_content'] =  "";
                                        $data['mst_mms_content'] =  "";
                                        $data['mst_img_content'] =  "";
                                        //$data['mst_button'] = json_encode(array($btn[0], $btn[1], $btn[2], $btn[3], $btn[4]), JSON_UNESCAPED_UNICODE);
										$data['mst_button'] = json_encode(array("[". $btn[0] ."]", "[". $btn[1] ."]", "[". $btn[2] ."]", "[". $btn[3] ."]", "[". $btn[4] ."]"), JSON_UNESCAPED_UNICODE); //2020-12-11
                                        $data['mst_reserved_dt'] =  "00000000000000";
                                        $data['mst_sms_callback'] =  $sender_callback; //$kakao_d->kakao_sender;
                                        $data['mst_status'] = '1';
                                        $data['mst_qty'] =  1;
                                        $data['mst_amount'] = 0;	// ($this->Biz_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));
                                        // 2019-02-18 이수환 추가
                                        $data['mst_type1'] = "at";
                                        // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                                        $data['mst_price_at'] = $mst_price_at;
                                        $data['mst_price_ft'] = $mst_price_ft;
                                        $data['mst_price_ft_img'] = $mst_price_ft_img;
                                        $data['mst_price_ft_w_img'] = $mst_price_ft_w_img;
                                        $data['mst_price_grs'] = $mst_price_grs;
                                        $data['mst_price_grs_sms'] = $mst_price_grs_sms;
                                        $data['mst_price_grs_mms'] = $mst_price_grs_mms;
                                        $data['mst_price_nas'] = $mst_price_nas;
                                        $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                                        $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                                        $data['mst_price_nas'] = $mst_price_nas;
                                        $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                                        $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                                        $data['mst_smt_price'] = $mst_price_smt;
                                        $data['mst_price_smt_sms'] = $mst_price_smt_sms;
                                        $data['mst_price_smt_mms'] = $mst_price_smt_mms;
                                        
                                        //echo '<pre> :: ';print_r($data);
                                        //exit;
                                        
                                        $this->db->insert("cb_wt_msg_sent", $data);
                                        $gid = $this->db->insert_id();
                                        
                                        
                                        $this->load->library('dhnkakao');
                                        $this->dhnkakao->sendAgentSendAPI('at', $gid,$mem_id, $userid,$kakao['profile_key'],'00000000000000', $sc_id);
                                    }
                                    
                                } else {
                                    $respo_no = 105;
                                    $respo_msg = "템플릿 코드를 확인 하세요.";
                                }
                            } else {
                                $respo_no = 104;
                                $respo_msg = "템플릿 코드를 확인 하세요.";
                            }
                        } else {
                            $respo_no = 103;
                            $respo_msg = "인증키를 확인 하세요.";
                        }
                    } else {
                        $respo_no = 102;
                        $respo_msg = "인증키를 확인 하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    //Log_message("ERROR", "Alimtalk API Database 오류 : ( ".$this->db->error()['message']." )");
                }
                
                if($respo_no == '0') {
                    $testsend = array();
                    $testsend['receive_phn'] = $r_phn;
                    
                    $this->db->insert('cb_wt_test_send', $testsend);
                }
                
                
                header('Content-Type: application/json');
                echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'"}';
                
                
            } else {
                header('Content-Type: application/json');
                echo '{"response_code": "999", "response_message":"일일 테스트 발송량이 초과 되었습니다."}';
            }
        } else {
            header('Content-Type: application/json');
            echo '{"response_code": "999", "response_message":"전화번호를 입력해 주세요.'.$r_phn.'"}';
        }
    }

}
