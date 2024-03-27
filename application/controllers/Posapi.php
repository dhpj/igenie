<?php 
//defined('BASEPATH') OR exit('No direct script access allowed');

class Posapi extends CB_Controller
{
    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'string');
    
    function __construct()
    {
        parent::__construct();
        //$this->load->library(array('depositlib'));
        header('Access-Control-Allow-Origin: *');
        
    }
    
    public function login(){
        $loginHead = getallheaders();
        $loginData = json_decode(file_get_contents('php://input'));

        log_message("ERROR","API login Call - userid : ". $loginData->userid);
        log_message("ERROR","API login Call - Authorization : ". $loginHead["Authorization"]);
        $loginKey = $loginHead["Authorization"];
        $loginUserID = $loginData->userid;

        log_message("ERROR","API login Call2 - userid : ". $loginUserID);
        log_message("ERROR","API login Call2 - Authorization : ". $loginKey);
        
        if (!empty($loginKey)) {
            if (!empty($loginUserID)) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from cb_member where mem_userid='".$loginUserID."' and mem_site_key='".$loginKey."'";
                $poslogin = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $poslogin->cnt;
                    $userid = $poslogin->userid;
                    $mem_id = $poslogin->mem_id;
                    
                    log_message("ERROR","API import_tel Call - count : ".$cnt.",  userid : ".$userid.",  mem_id : ".$mem_id);
                    
                    if($cnt > 0) {
                        $userinfo = $this->Member_model->get_by_userid($userid, 'mem_id, mem_userid');
                        $this->member->update_login_log($mem_id, $userid, 1, '로그인 성공 (포스기)');
                        $this->session->set_userdata(
                            'mem_id',
                            $mem_id
                            );
                        log_message("ERROR", "/autologin/login_profile_key > set_userdata ");
                        log_message("ERROR", "/autologin/login_profile_key > session : ". $this->session->userdata('mem_id'));
                        log_message("ERROR", "/autologin/login_profile_key > is_member : ". $this->member->is_member());
                        log_message("ERROR", "/autologin/login_profile_key > rtn_url : ". $rtn_url .", site_url() : ". site_url() .", ip_address : ". $this->input->ip_address());
                        if ($this->member->is_member() != "") {
                            
                            $vericode = array('$', '/', '.');
                            $hash = str_replace(
                                $vericode,
                                '',
                                password_hash(random_string('alnum', 10) . element('mem_id', $userinfo) . ctimestamp() . element('mem_userid', $userinfo), PASSWORD_BCRYPT)
                                );
                            $insertautologin = array(
                                'mem_id' => element('mem_id', $userinfo),
                                'aul_key' => $hash,
                                'aul_ip' => $this->input->ip_address(),
                                'aul_datetime' => cdate('Y-m-d H:i:s'),
                            );
                            $this->load->model(array('Autologin_model'));
                            $this->Autologin_model->insert($insertautologin); //자동 로그인 로그 기록
                            
                            $url_after_login = "http://o2omsg.com/dhnbiz/sender/send/message";
                            //$this->load->library('member');
                            //$this->member->autologin_cookie($hash, $url_after_login);
                            $cookie_name = 'autologin';
                            $cookie_value = $hash;
                            $cookie_expire = 2592000; // 30일간 저장
                            log_message("ERROR", "/autologin/login_profile_key > hash1 : ".$hash);
                            set_cookie($cookie_name, $cookie_value, $cookie_expire);
                            log_message("ERROR", "/autologin/login_profile_key > hash2 : ".$hash);
                            try {
                                redirect($url_after_login);
                            } catch (Exception $e) {
                                log_message("ERROR", "/autologin/login_profile_key > Exception : ".$e);
                            }
                            log_message("ERROR", "/autologin/login_profile_key > END : ");
                        } else {
                            $respo_no = 105;
                            $respo_msg = "회원정보 세션이 존재하지 않습니다.";
                            header('Content-Type: application/json');
                            echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'", "Authorization_Key":"'.$loginKey.'", "userId":"'.$loginUserID.'"}';
                        }
                        
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다. 사용자 계정과 계정 key를 확인하세요.";
                        header('Content-Type: application/json');
                        echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'", "Authorization_Key":"'.$loginKey.'", "userId":"'.$loginUserID.'"}';
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                    header('Content-Type: application/json');
                    echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'", "Authorization_Key":"'.$loginKey.'", "userId":"'.$loginUserID.'"}';
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
                header('Content-Type: application/json');
                echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'", "Authorization_Key":"'.$loginKey.'", "userId":"'.$loginUserID.'"}';
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
            header('Content-Type: application/json');
            echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'", "Authorization_Key":"'.$loginKey.'", "userId":"'.$loginUserID.'"}';
        }
        
    }
    
    public function import_tel() {
        log_message("ERROR","API import_tel Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        //log_message("ERROR","API import_tel importData : ". file_get_contents('php://input'));
        log_message("ERROR","API import_tel importData1 : ". json_encode($importData));
        $importDebug = file_get_contents('php://input');

        log_message("ERROR","API import_tel Call : ". $importData->userid);
        $importTel = array();
        
        $importTel['login_key'] = $importHead["Authorization"];
        $importTel['userid'] = $importData->userid;
        
        // 기존Data에서 전화번호 추가 'i', 기존Data 전체 삭제 후 전화번호 등록 'n', 기존 Data 선택 삭제 'd'
        $importTel['flag'] = $importData->flag;   
        $importTel['tel_info'] = $importData->tel_info;
        
        foreach($importTel['tel_info'] as $p) {
            log_message("ERROR","API import_tel Call - name : ".$p->name.",  number : ".$p->number);
        }
        
        //header('Content-Type: application/json');
        //echo '{"login_key": "'.$importTel['login_key'].'", "flag":"'.$importTel['flag'].'", "tel_info":"'.$importTel['tel_info'].'"}';
        //return;
        
        $respo_no = 0;
        $respo_msg = "등록 성공";
        $total_cnt = 0;         // 전체 카운트
        $success_cnt = 0;       // 성공 카운트
        $oberlap_cnt = 0;       // 중복 카운트
        $wrongformat_cnt = 0;   // 전화번호 형식 오류
        
        log_message("ERROR","API import_tel Call - importTel['login_key'] : ".$importTel['login_key']);
        if (!empty($importTel['login_key'])) {
            log_message("ERROR","API import_tel Call - importTel['userid'] : ".$importTel['userid']);
            if (!empty($importTel['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from cb_member where mem_userid='".$importTel['userid']."' and mem_site_key='".$importTel['login_key']."'";
                log_message("ERROR","API import_tel Call - sql1 : ".$cnt);
                $postel = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $postel->cnt;
                    $userid = $postel->userid;
                    $mem_id = $postel->mem_id;
                    
                    log_message("ERROR","API import_tel Call - count : ".$cnt.",  userid : ".$userid.",  mem_id : ".$mem_id);
                    
                    if($cnt > 0) {
                        if($importTel['flag'] == "d") {     // 기존 데이터 선택 삭제
                            log_message("ERROR","API import_tel Call - flag d 실행");
                            
                            foreach($importTel['tel_info'] as $p) {
                                $tel_number = preg_replace("/[^0-9]/", "", $p->number);
                                if(strlen($tel_number) >= 10 && strlen($tel_number) <= 11) {
                                    $delsql = "delete from cb_tel_pos where ab_mem_id=".$mem_id." and ab_tel='".$tel_number."'";
                                    $this->db->query($delsql);
                                    $success_cnt += 1;
                                } else {
                                    $wrongformat_cnt += 1;
                                }
                                $total_cnt += 1;
                            }
                            $respo_msg = "삭제 성공";
                        } else {    // flag = 'n' or 'i'
                            log_message("ERROR","API import_tel Call - flag n/i 실행");
                            if($importTel['flag'] == "n") {     // 기존 데이터 전체 삭제 후 insert
                                log_message("ERROR","API import_tel Call - flag n 실행");
                                $delsql = "delete from cb_tel_pos where ab_mem_id=".$mem_id;
                                $this->db->query($delsql);
                            }
                            
                            // 데이터 insert (flag : n, i)
                            foreach($importTel['tel_info'] as $p) {
                                $tel_number = preg_replace("/[^0-9]/", "", $p->number);
                                if(strlen($tel_number) >= 10 && strlen($tel_number) <= 11) {
                                    $sql = "select count(1) as cnt from cb_tel_pos where ab_mem_id=".$mem_id." and ab_tel='".$tel_number."'";
                                    $tel_info = $this->db->query($sql)->row();
                                    
                                    if($tel_info->cnt < 1) {
                                        $data = array();
                                        $data['ab_mem_id'] = $mem_id;
                                        $data['ab_name'] = $p->name;
                                        $data['ab_tel'] = $tel_number;
                                        
                                        $this->db->insert("cb_tel_pos", $data);
                                        $success_cnt += 1;
                                    } else {
                                        $oberlap_cnt += 1;
                                    }
                                } else {
                                    $wrongformat_cnt += 1;
                                }
                                $total_cnt += 1;
                            }
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다. 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "total_count":'.$total_cnt.', "success_count":'.$success_cnt.', "oberlap_count":'.$oberlap_cnt.', "wrongformat_count":'.$wrongformat_cnt.'}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "Authorization_Key":"'.$importTel['login_key'].'", "userId":"'.$importTel['userid'].'"}';
        }
    }
    
    public function import_goods() {
        log_message("ERROR","API import_goods Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API import_goods Call : ". $importData->userid);
        $importGoods = array();
        
        $importGoods['login_key'] = $importHead["Authorization"];
        $importGoods['userid'] = $importData->userid;
        
        // 기존Data에서 상품 추가 'i', 기존Data 전체 삭제 후 상품 등록 'n', 기존 Data 선택 삭제 'd'
        $importGoods['flag'] = $importData->flag;
        $importGoods['goods_info'] = $importData->goods_info;
        
        foreach($importGoods['goods_info'] as $p) {
            log_message("ERROR","API import_goods Call - barcode : ".$p->barcode.", - name : ".$p->name.",  option : ".$p->option.",  price : ".$p->price.",  dcprice : ".$p->dcprice);
        }
        
        $respo_no = 0;
        $respo_msg = "등록 성공";
        $total_cnt = 0;         // 전체 카운트
        $success_cnt = 0;       // 성공 카운트
        $oberlap_cnt = 0;       // 중복 카운트
        
        if (!empty($importGoods['login_key'])) {
            if (!empty($importGoods['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from cb_member where mem_userid='".$importGoods['userid']."' and mem_site_key='".$importGoods['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    
                    log_message("ERROR","API import_goods Call - count : ".$cnt.",  userid : ".$userid.",  mem_id : ".$mem_id);
                    
                    if($cnt > 0) {
                        if($importGoods['flag'] == "d") {     // 기존 데이터 선택 삭제
                            log_message("ERROR","API import_goods Call - flag d 실행");

                            foreach($importGoods['goods_info'] as $p) {
                                $delsql = "delete from cb_pop_screen_goods_pos where psg_mem_id=".$mem_id." and psg_code='".$p->barcode."'";
                                $this->db->query($delsql);
                                
                                $success_cnt += 1;
                                $total_cnt += 1;
                            }
                            $respo_msg = "삭제 성공";
                        } else {    // flag = 'n' or 'i'
                            log_message("ERROR","API import_goods Call - flag n/i 실행");
                            if($importGoods['flag'] == "n") {     // 기존 데이터 전체 삭제 후 insert
                                log_message("ERROR","API import_goods Call - flag n 실행");
                                $delsql = "delete from cb_pop_screen_goods_pos where psg_mem_id=".$mem_id;
                                $this->db->query($delsql);
                            }
                            
                            // 데이터 insert (flag : n, i)
                            foreach($importGoods['goods_info'] as $p) {
                                $price = preg_replace("/[^0-9]/", "", $p->price);
                                $dcprice = preg_replace("/[^0-9]/", "", $p->dcprice);
                                
                                $sql = "select count(1) as cnt from cb_pop_screen_goods_pos where psg_mem_id=".$mem_id." and psg_code='".$p->barcode."'";
                                $goods_info = $this->db->query($sql)->row();
                                
                                if($goods_info->cnt < 1) {
                                    $data = array();
                                    $data['psg_mem_id'] = $mem_id;
                                    $data['psg_code'] = $p->barcode;
                                    $data['psg_name'] = $p->name;
                                    $data['psg_option'] = $p->option;
                                    $data['psg_price'] = $p->price;
                                    $data['psg_dcprice'] = $p->dcprice;
                                    
                                    $this->db->insert("cb_pop_screen_goods_pos", $data);
                                    $success_cnt += 1;
                                } else {
                                    $oberlap_cnt += 1;
                                }
                                $total_cnt += 1;
                            }
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다. 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "total_count":'.$total_cnt.', "success_count":'.$success_cnt.', "oberlap_count":'.$oberlap_cnt.'}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "Authorization_Key":"'.$importGoods['login_key'].'", "userId":"'.$importGoods['userid'].'"}';
        }
    }

    public function import_goods_plus() {
        log_message("ERROR","API import_goods Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API import_goods Call : ". $importData->userid);
        $importGoods = array();
        
        $importGoods['login_key'] = $importHead["Authorization"];
        $importGoods['userid'] = $importData->userid;
        // 플친몰 사용 유무 - 플친몰,지니 모두 'a', 지니 'g', 플친몰 'm'
        $importGoods['mall_flag'] = $importData->mall_flag;
        
        // 기존Data에서 상품 추가 'i', 기존Data 전체 삭제 후 상품 등록 'n', 기존 Data 선택 삭제 'd'
        $importGoods['flag'] = $importData->flag;
        $importGoods['goods_info'] = $importData->goods_info;
        
        foreach($importGoods['goods_info'] as $p) {
            log_message("ERROR","API import_goods Call - barcode : ".$p->barcode.", - name : ".$p->name.",  option : ".$p->option.",  price : ".$p->price.",  dcprice : ".$p->dcprice);
        }
        
        $respo_no = 0;
        $respo_msg = "등록 성공";
        $total_cnt = 0;         // 전체 카운트
        $success_cnt = 0;       // 성공 카운트
        $oberlap_cnt = 0;       // 중복 카운트
        
        if (!empty($importGoods['login_key'])) {
            if (!empty($importGoods['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importGoods['userid']."' and mem_site_key='".$importGoods['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    
                    log_message("ERROR","API import_goods Call - count : ".$cnt.",  userid : ".$userid.",  mem_id : ".$mem_id);
                    
                    if (strtolower($importGoods['mall_flag']) == 'a' || strtolower($importGoods['mall_flag']) == 'm') {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API import_goods Call - mall count : ".$cnt.",  mall userid : ".$mall_mem_userid.",  mall mem_id : ".$mall_mem_id);
                    }
                    
                    //return;
                    
                    if($cnt > 0) {
                        if($importGoods['flag'] == "d") {     // 기존 데이터 선택 삭제
                            log_message("ERROR","API import_goods Call - flag d 실행");
                            
                            if (strtolower($importGoods['mall_flag']) == 'a' || strtolower($importGoods['mall_flag']) == 'g') {
                                foreach($importGoods['goods_info'] as $p) {
                                    $delsql = "delete from cb_pop_screen_goods_pos where psg_mem_id=".$mem_id." and psg_code='".$p->barcode."'";
                                    $this->db->query($delsql);
                                    
                                    $success_cnt += 1;
                                    $total_cnt += 1;
                                }
                            }
                            
                            if(strtolower($importGoods['mall_flag']) == 'a' || strtolower($importGoods['mall_flag']) == 'm') {
                                if($mall_mem_id != "") {
                                    foreach($importGoods['goods_info'] as $p) {
                                        $delsql = "delete from mall.dm_goods_temp where mem_id=".$mall_mem_id." and code='".$p->barcode."'";
                                        $this->db->query($delsql);
                                        
                                        $success_cnt += 1;
                                        $total_cnt += 1;
                                    }
                                } else {
                                    $respo_no = 105;
                                    $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                                }
                            }
                            if($respo_no == 0) {
                                $respo_msg = "삭제 성공";
                            }
                            
                        } else {    // flag = 'n' or 'i'
                            log_message("ERROR","API import_goods_puls Call - flag n/i 실행");
                            if($importGoods['flag'] == "n") {     // 기존 데이터 전체 삭제 후 insert
                                log_message("ERROR","API import_goods Call - flag n 실행");
                                $delsql = "delete from cb_pop_screen_goods_pos where psg_mem_id=".$mem_id;
                                $this->db->query($delsql);
                                log_message("ERROR","API import_goods_puls Call - mall \"n\" delete : ".$importGoods['mall_flag']);
                                
                                if(strtolower($importGoods['mall_flag']) == 'a' || strtolower($importGoods['mall_flag']) == 'm') {
                                    log_message("ERROR","API import_goods_puls Call - mall \"n\" delete : ".$mall_mem_id);
                                    if($mall_mem_id != "") {
                                        $delsql = "delete from mall.dm_goods_temp where mem_id=".$mall_mem_id;
                                        $this->db->query($delsql);
                                    } else {
                                        $respo_no = 105;
                                        $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                                    }
                                }
                            }
                            
                            if($respo_no == 0) {
                                // 데이터 insert (flag : n, i)
                                foreach($importGoods['goods_info'] as $p) {
                                    $price = preg_replace("/[^0-9]/", "", $p->price);
                                    $dcprice = preg_replace("/[^0-9]/", "", $p->dcprice);
                                    
                                    $sql = "select count(1) as cnt from cb_pop_screen_goods_pos where psg_mem_id=".$mem_id." and psg_code='".$p->barcode."'";
                                    $goods_info = $this->db->query($sql)->row();
                                    
                                    if($goods_info->cnt < 1) {
                                        $data = array();
                                        $data['psg_mem_id'] = $mem_id;
                                        $data['psg_code'] = $p->barcode;
                                        $data['psg_name'] = $p->name;
                                        $data['psg_option'] = $p->option;
                                        $data['psg_price'] = $p->price;
                                        $data['psg_dcprice'] = $p->dcprice;
                                        
                                        $this->db->insert("cb_pop_screen_goods_pos", $data);        // 지니 상품 저장
                                        if(strtolower($importGoods['mall_flag']) == 'a' || strtolower($importGoods['mall_flag']) == 'm') {
                                            if($mall_mem_id != "") {
                                                log_message("ERROR","API import_goods_puls Call - mall insert : ".$p->dcdateflag);
                                                
                                                $sql_var = "INSERT INTO mall.dm_goods_temp (mem_id, code, description, option, unit_price, dc_unit_price";
                                                $sql_value = " VALUES ('".$mall_mem_id."', '".$p->barcode."', '".$p->name."', '".$p->option."', '".$p->price."', '".$p->dcprice."'";
                                                
                                                if($p->dcdateflag == "Y") {
                                                    $sql_var .= ", dc_date_flag";
                                                    $sql_value .= ", 'Y'";
                                                    
                                                    if ($p->dcdatestart) {
                                                        $sql_var .= ", dc_date_sdt";
                                                        $sql_value .= ", '".$p->dcdatestart."'";
                                                    }
                                                    if ($p->dcdateend) {
                                                        $sql_var .= ", dc_date_edt";
                                                        $sql_value .= ", '".$p->dcdateend."'";
                                                    }
                                                    if ($p->dcdateprice) {
                                                        $sql_var .= ", dc_date_price";
                                                        $sql_value .= ", '".$p->dcdateprice."'";
                                                    }
                                                } else {
                                                    $sql_var .= ", dc_date_flag";
                                                    $sql_value .= ", 'N'";
                                                }
                                                
                                                $sql_var .= ") ";
                                                $sql_value .= ") ";
                                                
                                                $sql = $sql_var.$sql_value;
                                                
                                                log_message("ERROR","API import_goods_puls Call - mall insert : ".$sql);
                                                $this->db->query($sql);
                                            } else {
                                                $respo_no = 105;
                                                $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                                            }
                                        }
                                        $success_cnt += 1;
                                    } else {
                                        if(strtolower($importGoods['mall_flag']) == 'a' || strtolower($importGoods['mall_flag']) == 'm') {
                                            if($mall_mem_id != "") {
                                                $sql = "select count(1) as cnt from mall.dm_goods_temp where mem_id=".$mall_mem_id." and code='".$p->barcode."'";
                                                $goods_info = $this->db->query($sql)->row();
                                                
                                                log_message("ERROR","API import_goods_puls Call - genie duple mall insert : ".$p->dcdateflag);
                                                if($goods_info->cnt < 1) {
                                                    $sql_var = "INSERT INTO mall.dm_goods_temp (mem_id, code, description, option, unit_price, dc_unit_price";
                                                    $sql_value = " VALUES ('".$mall_mem_id."', '".$p->barcode."', '".$p->name."', '".$p->option."', '".$p->price."', '".$p->dcprice."'";
                                                    
                                                    if($p->dcdateflag == "Y") {
                                                        $sql_var .= ", dc_date_flag";
                                                        $sql_value .= ", 'Y'";
                                                        
                                                        if ($p->dcdatestart) {
                                                            $sql_var .= ", dc_date_sdt";
                                                            $sql_value .= ", '".$p->dcdatestart."'";
                                                        }
                                                        if ($p->dcdateend) {
                                                            $sql_var .= ", dc_date_edt";
                                                            $sql_value .= ", '".$p->dcdateend."'";
                                                        }
                                                        if ($p->dcdateprice) {
                                                            $sql_var .= ", dc_date_price";
                                                            $sql_value .= ", '".$p->dcdateprice."'";
                                                        }
                                                    } else {
                                                        $sql_var .= ", dc_date_flag";
                                                        $sql_value .= ", 'N'";
                                                    }
                                                    
                                                    $sql_var .= ") ";
                                                    $sql_value .= ") ";
                                                    
                                                    $sql = $sql_var.$sql_value;
                                                    
                                                    log_message("ERROR","API import_goods_puls Call - genie duple mall insert : ".$sql);
                                                    $this->db->query($sql);
                                                    $success_cnt += 1;
                                                } else {
                                                    $oberlap_cnt += 1;
                                                }
                                            } else {
                                                $respo_no = 105;
                                                $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                                            }
                                        } else {
                                            $oberlap_cnt += 1;
                                        }
                                    }
                                    $total_cnt += 1;
                                }
                            }
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "total_count":'.$total_cnt.', "success_count":'.$success_cnt.', "oberlap_count":'.$oberlap_cnt.'}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "Authorization_Key":"'.$importGoods['login_key'].'", "userId":"'.$importGoods['userid'].'"}';
        }
    }
    
    public function export_order() {
        log_message("ERROR","API export_order Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API export_order Call : ". $importData->userid);
        $importGoods = array();
        
        $importGoods['login_key'] = $importHead["Authorization"];
        $importGoods['userid'] = $importData->userid;
        
        $respo_no = 0;
        $respo_msg = "조회성공";
        
        if (!empty($importGoods['login_key'])) {
            if (!empty($importGoods['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importGoods['userid']."' and mem_site_key='".$importGoods['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    $orders_json = "";
                    $orders_details_json= "";
                    $orders_count = 0;
                    $orders_details_count = 0;
                    
                    if($cnt > 0) {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API export_order Call - mall count : ".$cnt.",  mall userid : ".$mall_mem_userid.",  mall mem_id : ".$mall_mem_id);
                        
                        if($mall_mem_id != "") {
                            $sql = "select * from mall.dm_orders where pos_send_yn = 'N' and mem_id = ".$mall_mem_id;
                            
                            log_message("ERROR","API export_order Call - order select : ".$sql);
                            $orders = $this->db->query($sql)->result();
                            
                            $orders_json = "[";
                            foreach($orders as $r) {
                                if ($orders_count > 0) {
                                    $orders_json .= ", {";
                                } else {
                                    $orders_json .= "{";
                                }
                                
                                $orders_json .= '"order_id":"'.$r->id.'", "phone_no":"'.$r->phnno.'", "receiver":"'.$r->receiver.'", "address":"'.$r->addr.'", ';
                                $orders_json .= '"deliv_date":"'.$r->deliv_date.'", "deliv_time":"'.$r->deliv_time.'", "deliv_type":"'.$r->deliv_type.'", "charge_type":"'.$r->charge_type.'", "user_req":"'.$r->user_req.'"';
                                
                                log_message("ERROR","API export_order Call - mall insert : ".$r->id);
                                
                                $sql_order_amount = "select sum(amount) order_amount from mall.dm_order_details where order_id=".$r->id;
                                log_message("ERROR","API export_order Call - order_amount SQL : ".$sql_details);
                                $order_amount_row = $this->db->query($sql_order_amount)->row();
                                //$order_amount = $order_amount_row->order_amount;
                                
                                $orders_json .= '"order_amount":"'.$order_amount_row->order_amount.'", ';
                                
                                $sql_details = "select a.*, b.code from mall.dm_order_details a left join mall.dm_goods b on b.mem_id=".$mall_mem_id." and a.goodsid = b.id where order_id=".$r->id;
                                
                                log_message("ERROR","API export_order Call - order_details select : ".$sql_details);
                                $order_details = $this->db->query($sql_details)->result();
                                
                                $orders_details_count = 0;
                                foreach($order_details as $r_d) {
                                    log_message("ERROR","API export_order Call - mall insert : ".$r_d->id.", ".$r_d->code);
                                    if ($orders_details_count > 0) {
                                        $orders_details_json .= ", {";
                                    } else {
                                        $orders_details_json = "{";
                                    }
                                    $orders_details_json .= '"details_id":"'.$r_d->id.'", "goods_id":"'.$r_d->goodsid.'", "bar_code":"'.$r_d->code.'", "price":"'.$r_d->price.'", ';
                                    $orders_details_json .= '"dcprice":"'.$r_d->dcprice.'", "qty":"'.$r_d->qty.'", "amount":"'.$r_d->amount.'", "org_dcprice":"'.$r_d->org_dcprice.'"';
                                    $orders_details_json .= "}";
                                    $orders_details_count++;
                                }
                                $orders_json .= '"details_count":"'.$orders_details_count.'", "details":[';
                                
                                $orders_json .= $orders_details_json;
                                $orders_json .= ']';
                                $orders_json .= '}';
                                $orders_count++;
                            }
                            $orders_json .= "]";
                            
                            
                            
                        } else {
                            $respo_no = 105;
                            $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }

        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "total_count":'.$orders_count.', "orders":'.$orders_json.'}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "Authorization_Key":"'.$importGoods['login_key'].'", "userId":"'.$importGoods['userid'].'"}';
        }
    }

    public function export_genie_order() {
        log_message("ERROR","API export_genie_order Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API export_genie_order Call : ". $importData->userid);
        $importGoods = array();
        
        $importGoods['login_key'] = $importHead["Authorization"];
        $importGoods['userid'] = $importData->userid;
        
        $respo_no = 0;
        $respo_msg = "조회성공";
        
        if (!empty($importGoods['login_key'])) {
            if (!empty($importGoods['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importGoods['userid']."' and mem_site_key='".$importGoods['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    $orders_json = "";
                    $orders_details_json= "";
                    $orders_count = 0;
                    $orders_details_count = 0;
                    
                    if($cnt > 0) {
                        $sql = "select * from cb_orders where pos_send_yn = 'N' and status<> '9' and mem_id = ".$mem_id;
                        
                        log_message("ERROR","API export_genie_order Call - order select : ".$sql);
                        $orders = $this->db->query($sql)->result();
                        
                        $orders_json = "[";
                        foreach($orders as $r) {
                            if ($orders_count > 0) {
                                $orders_json .= ", {";
                            } else {
                                $orders_json .= "{";
                            }
                            
                            // 결재수단 (1:현장결제(카드), 2:현장결제(현금), 3:현장결제(지역화폐), 4:계좌이체, 5:전자결제(집배송), 6.전자결제(방문전), 7.현장결제(카드,현금))
                            $charge_type = "";
                            // 구분(1 : 배달, 2 : 포장)
                            $delivery_type = "";
                            
//                             if($r->charge_type == 1) {
//                                 $charge_type = "CARD";
//                             } else if($r->charge_type == 2) {
//                                 $charge_type = "CASH";
//                             } else if($r->charge_type == 3) {
//                                 $charge_type = "LOC";
//                             }
                            
                            
                            
                            if($r->charge_type == 1) {
                                $charge_type = "C1";
                            } else if($r->charge_type == 2) {
                                $charge_type = "C2";
                            } else if($r->charge_type == 3) {
                                $charge_type = "C3";
                            } else if($r->charge_type == 4) {
                                $charge_type = "C4";
                            } else if($r->charge_type == 5) {
                                $charge_type = "C5";
                            } else if($r->charge_type == 7) {
                                $charge_type = "C7";
                            }
                            
                            if ($r->division == 1) {
                                $delivery_type = "D1";
                            } else if($r->division == 2) {
                                $delivery_type = "D2";
                            }
                            
                            
                            $orders_json .= '"order_id":"'.$r->id.'", "order_no":"'.$r->orderno.'", "phone_no":"'.$r->phnno.'", "receiver":"'.$r->receiver.'", "address":"'.$r->addr1.' '.$r->addr2.'", ';
                            $orders_json .= '"deliv_type":"'.$delivery_type.'", "charge_type":"'.$charge_type.'", "user_req":"'.$r->user_req.'", "status":"'.$r->status.'", ';
                            
                            log_message("ERROR","API export_genie_order Call - mall insert : ".$r->id);
                            
                            $sql_order_amount = "select sum(amount) order_amount from cb_order_details where order_id=".$r->id;
                            log_message("ERROR","API export_order Call - order_amount SQL : ".$sql_details);
                            $order_amount_row = $this->db->query($sql_order_amount)->row();
                            //$order_amount = $order_amount_row->order_amount;
                            
                            $orders_json .= '"order_amount":"'.$order_amount_row->order_amount.'", ';
                            
                            $sql_details = "select * from cb_order_details where order_id=".$r->id;
                            
                            log_message("ERROR","API export_genie_order Call - order_details select : ".$sql_details);
                            $order_details = $this->db->query($sql_details)->result();
                            
                            $orders_details_count = 0;
                            foreach($order_details as $r_d) {
                                log_message("ERROR","API export_genie_order Call - mall insert : ".$r_d->id.", ".$r_d->code);
                                if ($orders_details_count > 0) {
                                    $orders_details_json .= ", {";
                                } else {
                                    $orders_details_json = "{";
                                }
                                $orders_details_json .= '"details_id":"'.$r_d->id.'", "goods_id":"'.$r_d->goodsid.'", "bar_code":"'.$r_d->barcode.'", "price":"'.$r_d->price.'", ';
                                $orders_details_json .= '"dcprice":"'.$r_d->dcprice.'", "qty":"'.$r_d->qty.'", "amount":"'.$r_d->amount.'"';
                                $orders_details_json .= "}";
                                $orders_details_count++;
                            }
                            $orders_json .= '"details_count":"'.$orders_details_count.'", "details":[';
                            
                            $orders_json .= $orders_details_json;
                            $orders_json .= ']';
                            $orders_json .= '}';
                            $orders_count++;
                        }
                        $orders_json .= "]";
                        
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "total_count":'.$orders_count.', "orders":'.$orders_json.'}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "Authorization_Key":"'.$importGoods['login_key'].'", "userId":"'.$importGoods['userid'].'"}';
        }
    }
    
    public function export_order_success() {
        log_message("ERROR","API export_order_success Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API export_order_success Call : ". $importData->userid);
        $importGoods = array();
        
        $importGoods['login_key'] = $importHead["Authorization"];
        $importGoods['userid'] = $importData->userid;
        $importGoods['orderid'] = $importData->orderid;
        
        $respo_no = 0;
        $respo_msg = "설정 성공";
        
        if (!empty($importGoods['login_key'])) {
            if (!empty($importGoods['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importGoods['userid']."' and mem_site_key='".$importGoods['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    
                    if($cnt > 0) {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API export_order Call - mall count : ".$cnt.",  mall userid : ".$mall_mem_userid.",  mall mem_id : ".$mall_mem_id);
                        
                        if($mall_mem_id != "") {
                            $sql = "update mall.dm_orders set pos_send_yn = 'Y' where mem_id = ".$mall_mem_id." and id = ".$importGoods['orderid'];
                            
                            log_message("ERROR","API export_order_success Call - mall insert : ".$sql);
                            $this->db->query($sql);
                        } else {
                            $respo_no = 105;
                            $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        }
    }

    public function export_order_successes() {
        log_message("ERROR","API export_order_success Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API export_order_success Call : ". $importData->userid);
        $importGoods = array();
        
        $importGoods['login_key'] = $importHead["Authorization"];
        $importGoods['userid'] = $importData->userid;
        $importGoods['order_count'] = (int)$importData->order_count;
        $importGoods['orderid'] = $importData->orderid;
        
        $respo_no = 0;
        $respo_msg = "설정 성공";
        $warning_cnt = 0;
        $warning_order_id = "";
        $success_cnt = 0;
        $success_order_id = "";
        
        
        if (!empty($importGoods['login_key'])) {
            if (!empty($importGoods['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importGoods['userid']."' and mem_site_key='".$importGoods['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    
                    if($cnt > 0) {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API export_order Call - mall count : ".$cnt.",  mall userid : ".$mall_mem_userid.",  mall mem_id : ".$mall_mem_id);
                        
                        if($mall_mem_id != "") {
                            $arr_order_id = explode(',', $importGoods['orderid']);
                            
                            if(count($arr_order_id) == $importGoods['order_count']) {
                                foreach($arr_order_id as $order) {
                                    $order = trim($order, " ");
                                    $sql = "select count(*) cnt from mall.dm_orders where mem_id = ".$mall_mem_id." and id = ".$order." and pos_send_yn = 'Y'";
                                    
                                    $ord_cnt = $this->db->query($sql)->row();
                                    if ($ord_cnt->cnt < 1) {
                                        //$sql = "update mall.dm_orders set pos_send_yn = 'Y' where mem_id = ".$mall_mem_id." and id = ".$importGoods['orderid'];
                                        //$sql = "update mall.dm_orders set pos_send_yn = 'Y' where mem_id = ".$mall_mem_id." and id in (".$importGoods['orderid'].")";
                                        $sql = "update mall.dm_orders set pos_send_yn = 'Y' where mem_id = ".$mall_mem_id." and id = ".$order;
                                        
                                        log_message("ERROR","API export_order_success Call - mall insert : ".$sql);
                                        $this->db->query($sql);
                                        if ($success_cnt > 0) {
                                            $success_order_id .= ", ";
                                        }
                                        $success_order_id .= $order;
                                        $success_cnt++;
                                        
                                    } else {
                                        if ($warning_cnt > 0) {
                                            $warning_order_id .= ", ";
                                        }
                                        $warning_order_id .= $order;
                                        $warning_cnt++;
                                    }
                                }
                            } else {
                                $respo_no = 106;
                                $respo_msg = "조회 order_count(".$importArray['order_count'].")와 변경할 orderid 수(".count($arr_order_id).")가 일치하지 않습니다.";
                                
                            }
                        } else {
                            $respo_no = 105;
                            $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "order_count":'.$importGoods['order_count'].', "success_count":'.$success_cnt.', "success_order_ids":"'.$success_order_id.'", "already_count":'.$warning_cnt.', "already_order_ids":"'.$warning_order_id.'"}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        }
    }

    public function export_genie_order_success() {
        log_message("ERROR","API export_genie_order_success Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API export_genie_order_success Call : ". $importData->userid);
        $importGoods = array();
        
        $importGoods['login_key'] = $importHead["Authorization"];
        $importGoods['userid'] = $importData->userid;
        $importGoods['order_count'] = (int)$importData->order_count;
        $importGoods['orderid'] = $importData->orderid;
        
        $respo_no = 0;
        $respo_msg = "설정 성공";
        $warning_cnt = 0;
        $warning_order_id = "";
        $success_cnt = 0;
        $success_order_id = "";
        
        
        if (!empty($importGoods['login_key'])) {
            if (!empty($importGoods['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importGoods['userid']."' and mem_site_key='".$importGoods['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    
                    if($cnt > 0) {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API export_genie_order_success Call - mall count : ".$cnt.",  mall userid : ".$mall_mem_userid.",  mall mem_id : ".$mall_mem_id);
                        
                        $arr_order_id = explode(',', $importGoods['orderid']);
                        
                        if(count($arr_order_id) == $importGoods['order_count']) {
                            foreach($arr_order_id as $order) {
                                $order = trim($order, " ");
                                $sql = "select count(*) cnt from cb_orders where mem_id = ".$mem_id." and id = ".$order." and pos_send_yn = 'Y'";
                                
                                $ord_cnt = $this->db->query($sql)->row();
                                if ($ord_cnt->cnt < 1) {
                                    //$sql = "update mall.dm_orders set pos_send_yn = 'Y' where mem_id = ".$mall_mem_id." and id = ".$importGoods['orderid'];
                                    //$sql = "update mall.dm_orders set pos_send_yn = 'Y' where mem_id = ".$mall_mem_id." and id in (".$importGoods['orderid'].")";
                                    $sql = "update cb_orders set pos_send_yn = 'Y' where mem_id = ".$mem_id." and id = ".$order;
                                    
                                    log_message("ERROR","API export_genie_order_success Call - mall insert : ".$sql);
                                    $this->db->query($sql);
                                    if ($success_cnt > 0) {
                                        $success_order_id .= ", ";
                                    }
                                    $success_order_id .= $order;
                                    $success_cnt++;
                                    
                                } else {
                                    if ($warning_cnt > 0) {
                                        $warning_order_id .= ", ";
                                    }
                                    $warning_order_id .= $order;
                                    $warning_cnt++;
                                }
                            }
                        } else {
                            $respo_no = 106;
                            $respo_msg = "조회 order_count(".$importArray['order_count'].")와 변경할 orderid 수(".count($arr_order_id).")가 일치하지 않습니다.";
                            
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "order_count":'.$importGoods['order_count'].', "success_count":'.$success_cnt.', "success_order_ids":"'.$success_order_id.'", "already_count":'.$warning_cnt.', "already_order_ids":"'.$warning_order_id.'"}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        }
    }
    
    public function get_order_cancel() {
        log_message("ERROR","API get_order_cancel Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API get_order_cancel Call : ". $importData->userid);
        $importGoods = array();
        
        $importGoods['login_key'] = $importHead["Authorization"];
        $importGoods['userid'] = $importData->userid;
        $importGoods['orderid'] = $importData->orderid;
        
        $respo_no = 0;
        $respo_msg = "조회 성공";
        $respo_cancel = "N";            // 주문취소 상태이면 'Y', 주문취소 상태가 아니면 'N'
        
        if (!empty($importGoods['login_key'])) {
            if (!empty($importGoods['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importGoods['userid']."' and mem_site_key='".$importGoods['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    
                    if($cnt > 0) {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API get_order_cancel Call - mall count : ".$cnt.",  mall userid : ".$mall_mem_userid.",  mall mem_id : ".$mall_mem_id);
                        
                        if($mall_mem_id != "") {
                            $sql = "select status from mall.dm_orders where mem_id = ".$mall_mem_id." and id = ".$importGoods['orderid'];
                            
                            log_message("ERROR","API export_order_success Call - mall insert : ".$sql);
                            $order = $this->db->query($sql)->row();
                            
                            if ($order->status == 4) {
                                $respo_cancel = "Y";
                            }
                        } else {
                            $respo_no = 105;
                            $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "order_cancel":"'.$respo_cancel.'"}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        }
        
    }

    public function get_order_cancels() {
        log_message("ERROR","API get_order_cancels Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API get_order_cancels Call : ". $importData->userid);
        $importArray = array();
        
        $importArray['login_key'] = $importHead["Authorization"];
        $importArray['userid'] = $importData->userid;
        $importArray['search_count'] = (int)$importData->search_count;
        $importArray['orderid'] = $importData->orderid;
        
        $respo_no = 0;
        $respo_msg = "조회 성공";
        $respo_cancel_count = 0;
        $respo_cancel = "";            // 주문 취소된 orderid들 ,로 구분
        
        if (!empty($importArray['login_key'])) {
            if (!empty($importArray['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importArray['userid']."' and mem_site_key='".$importArray['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    
                    if($cnt > 0) {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API get_order_cancels Call - mall count : ".$cnt.",  mall userid : ".$mall_mem_userid.",  mall mem_id : ".$mall_mem_id);
                        
                        if($mall_mem_id != "") {
                            log_message("ERROR","API get_order_cancels Call - orderid : ".$importArray['orderid']);
                            $arr_order_id = explode(',', $importArray['orderid']);
                            if(count($arr_order_id) == $importArray['search_count']) {
                                $sql = "select id as orderid from mall.dm_orders where mem_id = ".$mall_mem_id." and id in (".$importArray['orderid'].") and status=4";
                                log_message("ERROR","API get_order_cancels Call - mall insert : ".$sql);
                                $orders = $this->db->query($sql)->result();
                                
                                foreach($orders as $r) {
                                    if ($respo_cancel_count > 0) {
                                        $respo_cancel .= ", ";
                                    } 
                                    $respo_cancel .= $r->orderid;
                                    $respo_cancel_count++;
                                }
                            } else {
                                $respo_no = 106;
                                $respo_msg = "조회 order search_count(".$importArray['search_count'].")와 조회할 orderid 수(".count($arr_order_id).")가 일치하지 않습니다.";
                            }
                        } else {
                            $respo_no = 105;
                            $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }

        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "cancel_count":"'.$respo_cancel_count.'", "cancel_order_ids":"'.$respo_cancel.'"}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        }
        
    }

    public function get_genie_order_cancel() {
        log_message("ERROR","API get_genie_order_cancel Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API get_genie_order_cancel Call : ". $importData->userid);
        $importArray = array();
        
        $importArray['login_key'] = $importHead["Authorization"];
        $importArray['userid'] = $importData->userid;
        $importArray['search_count'] = (int)$importData->search_count;
        $importArray['orderid'] = $importData->orderid;
        
        $respo_no = 0;
        $respo_msg = "조회 성공";
        $respo_cancel_count = 0;
        $respo_cancel = "";            // 주문 취소된 orderid들 ,로 구분
        
        if (!empty($importArray['login_key'])) {
            if (!empty($importArray['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importArray['userid']."' and mem_site_key='".$importArray['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    
                    if($cnt > 0) {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API get_genie_order_cancel Call - orderid : ".$importArray['orderid']);
                        $arr_order_id = explode(',', $importArray['orderid']);
                        if(count($arr_order_id) == $importArray['search_count']) {
                            $sql = "select id as orderid from cb_orders where mem_id = ".$mem_id." and id in (".$importArray['orderid'].") and status=4";
                            log_message("ERROR","API get_genie_order_cancel Call - mall insert : ".$sql);
                            $orders = $this->db->query($sql)->result();
                            
                            foreach($orders as $r) {
                                if ($respo_cancel_count > 0) {
                                    $respo_cancel .= ", ";
                                }
                                $respo_cancel .= $r->orderid;
                                $respo_cancel_count++;
                            }
                        } else {
                            $respo_no = 106;
                            $respo_msg = "조회 order search_count(".$importArray['search_count'].")와 조회할 orderid 수(".count($arr_order_id).")가 일치하지 않습니다.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "cancel_count":"'.$respo_cancel_count.'", "cancel_order_ids":"'.$respo_cancel.'"}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        }
        
    }
    
    public function set_order_status() {
        log_message("ERROR","API set_order_status Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API set_order_status Call : ". $importData->userid);
        $importGoods = array();
        
        $importGoods['login_key'] = $importHead["Authorization"];
        $importGoods['userid'] = $importData->userid;
        $importGoods['orderid'] = $importData->orderid;
        $importGoods['status'] = $importData->status; // 배송 진행상태 (1:배송준비중, 2:배송시작, 3:배송완료, 4:주문취소)

        $respo_no = 0;
        $respo_msg = "진행상태 설정 성공";
        
        if (!empty($importGoods['login_key'])) {
            if (!empty($importGoods['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importGoods['userid']."' and mem_site_key='".$importGoods['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    
                    if($cnt > 0) {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API set_order_status Call - mall count : ".$cnt.",  mall userid : ".$mall_mem_userid.",  mall mem_id : ".$mall_mem_id);
                        
                        if($mall_mem_id != "") {
                            $sql = "update mall.dm_orders set status = ".$importGoods['status']." where mem_id = ".$mall_mem_id." and id = ".$importGoods['orderid'];
                            
                            log_message("ERROR","API set_order_status Call - mall insert : ".$sql);
                            $this->db->query($sql);
                            
                            $tempstr = "";
                            if ($importGoods['status'] == 1) {
                                $tempstr = "배송중비중으로 변경";
                            } else if ($importGoods['status'] == 2) {
                                $tempstr = "배송시작으로 변경";
                            } else if ($importGoods['status'] == 3) {
                                $tempstr = "배송완료로 변경";
                            }  else if ($importGoods['status'] == 4) {
                                $tempstr = "주문취소로 변경";
                            }
                            
                            $respo_msg .= " - ".$tempstr;
                        } else {
                            $respo_no = 105;
                            $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        }
        
    }
    
    public function set_order_statuses() {
        log_message("ERROR","API set_order_statuses Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API set_order_statuses Call : ". $importData->userid);
        $importArray = array();
        
        $importArray['login_key'] = $importHead["Authorization"];
        $importArray['userid'] = $importData->userid;
        $importArray['orders_info_count'] = (int)$importData->orders_info_count;
        $importArray['orders_info'] = $importData->orders_info;
        //$importArray['orderid'] = $importData->orderid;
        //$importArray['status'] = $importData->status; // 배송 진행상태 (1:배송준비중, 2:배송시작, 3:배송완료, 4:주문취소)
        
        $respo_no = 0;
        $respo_msg = "성공";
        $result_array = "";
        $result_count = 0;
        
        if (!empty($importArray['login_key'])) {
            if (!empty($importArray['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importArray['userid']."' and mem_site_key='".$importArray['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    
                    if($cnt > 0) {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API set_order_statuses Call - mall count : ".$cnt.",  mall userid : ".$mall_mem_userid.",  mall mem_id : ".$mall_mem_id);
                        
                        if($mall_mem_id != "") {
                            $info_count = count($importArray['orders_info']);
                            
                            log_message("ERROR","API set_order_statuses Call - info_count : ".$info_count);
                            log_message("ERROR","API set_order_statuses Call - importArray['orders_info'] : ".json_encode($importArray['orders_info']));
                            
                            if ($importArray['orders_info_count'] == $info_count) {
                                $result_array = "[";
                                foreach($importArray['orders_info'] as $p) {
                                    if ($result_count > 0) {
                                        $result_array .= ", {";
                                    } else {
                                        $result_array .= "{";
                                    }

                                    $sql = "select count(id) cnt, status from mall.dm_orders where mem_id = ".$mall_mem_id." and id = ".$p->orderid;
                                    $order = $this->db->query($sql)->row();
                                    
                                    if ($order->cnt > 0 && $order->status != 4) {
                                        $sql = "update mall.dm_orders set status = ".$p->status." where mem_id = ".$mall_mem_id." and id = ".$p->orderid;
                                        log_message("ERROR","API set_order_statuses Call - mall update : ".$sql);
                                        
                                        $result = $this->db->query($sql);
                                        if($result == 1)  {
                                            $result_array .= '"order_id":"'.$p->orderid.'", "status":"'.$p->status.'", "result_code":0, "result_msg":"변경 완료"';
                                        } else {
                                            $result_array .= '"order_id":"'.$p->orderid.'", "status":"'.$p->status.'", "result_code":-1, "result_msg":"DB 오류"';
                                        }
                                    } else {
                                        if ($order->status == 4) {      // 주문취소
                                            $result_array .= '"order_id":"'.$p->orderid.'", "status":"'.$p->status.'", "result_code":1, "result_msg":"오류: 주문취소상태(변경불가)"';
                                        } else {
                                            $result_array .= '"order_id":"'.$p->orderid.'", "status":"'.$p->status.'", "result_code":2, "result_msg":"오류: 주문번호가 없음"';
                                        }
                                    }
                                    $result_count++;
                                    $result_array .= "}";
                                }
                                $result_array .= "]";
                            } else {
                                $respo_no = 106;
                                $respo_msg = "상태를 변경할 order orders_info_count(".$importArray['orders_info_count'].")와 orders_info수(".$info_count.")가 일치하지 않습니다.";
                            }
                        } else {
                            $respo_no = 105;
                            $respo_msg = "플친몰과 연결할 수 없습니다. 플친몰 사용자 계정이 등록되었이는지, 설정확인이 필요합니다. 관리자에게 문의해주세요.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "result_count":'.$result_count.', "result":'.$result_array.'}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        }
        
    }

    public function set_genie_order_status() {
        log_message("ERROR","API set_genie_order_status Call Start.");
        $importHead = getallheaders();
        $importData = json_decode(file_get_contents('php://input'));
        $importDebug = file_get_contents('php://input');
        
        log_message("ERROR","API set_genie_order_status Call : ". $importData->userid);
        $importArray = array();
        
        $importArray['login_key'] = $importHead["Authorization"];
        $importArray['userid'] = $importData->userid;
        $importArray['orders_info_count'] = (int)$importData->orders_info_count;
        $importArray['orders_info'] = $importData->orders_info;
        //$importArray['orderid'] = $importData->orderid;
        //$importArray['status'] = $importData->status; // 배송 진행상태 (1:배송준비중, 2:배송시작, 3:배송완료, 4:주문취소)
        
        $respo_no = 0;
        $respo_msg = "성공";
        $result_array = "";
        $result_count = 0;
        
        if (!empty($importArray['login_key'])) {
            if (!empty($importArray['userid'])) {
                $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id, max(mem_mall_id) as mem_mall_id, max(mem_mall_key) as mem_mall_key from cb_member where mem_userid='".$importArray['userid']."' and mem_site_key='".$importArray['login_key']."'";
                $posgoods = $this->db->query($sql)->row();
                
                if ($this->db->error()['code'] == 0) {
                    $cnt = $posgoods->cnt;
                    $userid = $posgoods->userid;
                    $mem_id = $posgoods->mem_id;
                    $mem_mall_userid = $posgoods->mem_mall_id;
                    $mem_mall_key = $posgoods->mem_mall_key;
                    $mall_mem_id = '';
                    $mall_mem_userid = '';
                    
                    if($cnt > 0) {
                        $sql = "select count(1) as cnt, max(mem_userid) as userid, max(mem_id) as mem_id from mall.dm_member where mem_userid='".$mem_mall_userid."' and mem_site_key='".$mem_mall_key."'";
                        $posmallgoods = $this->db->query($sql)->row();
                        $mall_mem_id = $posmallgoods->mem_id;
                        $mall_mem_userid = $posmallgoods->userid;
                        
                        log_message("ERROR","API set_genie_order_status Call - mall count : ".$cnt.",  mall userid : ".$mall_mem_userid.",  mall mem_id : ".$mall_mem_id);
                        
                        $info_count = count($importArray['orders_info']);
                        
                        log_message("ERROR","API set_order_statuses Call - info_count : ".$info_count);
                        log_message("ERROR","API set_order_statuses Call - importArray['orders_info'] : ".json_encode($importArray['orders_info']));
                        
                        if ($importArray['orders_info_count'] == $info_count) {
                            $result_array = "[";
                            foreach($importArray['orders_info'] as $p) {
                                if ($result_count > 0) {
                                    $result_array .= ", {";
                                } else {
                                    $result_array .= "{";
                                }
                                
                                $sql = "select count(id) cnt, status from cb_orders where mem_id = ".$mem_id." and id = ".$p->orderid;
                                $order = $this->db->query($sql)->row();
                                
                                if ($order->cnt > 0 && $order->status != 4) {
                                    $sql = "update cb_orders set status = ".$p->status." where mem_id = ".$mem_id." and id = ".$p->orderid;
                                    log_message("ERROR","API set_genie_order_status Call - mall update : ".$sql);
                                    
                                    $result = $this->db->query($sql);
                                    if($result == 1)  {
                                        $result_array .= '"order_id":"'.$p->orderid.'", "status":"'.$p->status.'", "result_code":0, "result_msg":"변경 완료"';
                                    } else {
                                        $result_array .= '"order_id":"'.$p->orderid.'", "status":"'.$p->status.'", "result_code":-1, "result_msg":"DB 오류"';
                                    }
                                } else {
                                    if ($order->status == 4) {      // 주문취소
                                        $result_array .= '"order_id":"'.$p->orderid.'", "status":"'.$p->status.'", "result_code":1, "result_msg":"오류: 주문취소상태(변경불가)"';
                                    } else {
                                        $result_array .= '"order_id":"'.$p->orderid.'", "status":"'.$p->status.'", "result_code":2, "result_msg":"오류: 주문번호가 없음"';
                                    }
                                }
                                $result_count++;
                                $result_array .= "}";
                            }
                            $result_array .= "]";
                        } else {
                            $respo_no = 106;
                            $respo_msg = "상태를 변경할 order orders_info_count(".$importArray['orders_info_count'].")와 orders_info수(".$info_count.")가 일치하지 않습니다.";
                        }
                    } else {
                        $respo_no = 104;
                        $respo_msg = "등록된 사용자가 없습니다(지니). 사용자 계정과 계정 key를 확인하세요.";
                    }
                } else {
                    $respo_no = 101;
                    $respo_msg = "Database 오류 : ( ".$this->db->error()['message']." )";
                    Log_message("ERROR", "POS API Database 오류 : ( ".$this->db->error()['message']." )");
                }
            } else {
                $respo_no = 103;
                $respo_msg = "사용자 계정을 확인하세요.";
            }
        } else {
            $respo_no = 102;
            $respo_msg = "사이트 연동키를 확인하세요.";
        }
        
        header('Content-Type: application/json');
        if ($respo_no == 0) {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'", "result_count":'.$result_count.', "result":'.$result_array.'}';
        } else {
            echo '{"response_code": '.$respo_no.', "response_message":"'.$respo_msg.'"}';
        }
        
    }
}
?>