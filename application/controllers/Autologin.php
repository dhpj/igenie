<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 자동 로그인 controller 입니다.
 */
class Autologin extends CB_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array();
    
    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'string');
    
    function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        //$this->load->library(array('depositlib'));
    }

	//타사이트에서 프로필키로 자동 로그인 요청
	public function login_profile_key(){
		//log_message("ERROR", "/autologin/login_profile_key 타사이트에서 프로필키로 자동 로그인 요청");
		
		$eventname = 'event_login_index';
        $this->load->event($eventname);
		
		$get_h = getallheaders();
		$get_d = json_decode(file_get_contents('php://input'));

		$profile_key = $get_h["Authorization"]; //프로필 키
		$req_site_url = $get_d->req_site_url; //요청 사이트 URL
		$req_site_name = $get_d->req_site_name; //요청 사이트 이름
		$req_user_agent = $get_d->req_user_agent; //요청 사이트 HTTP_USER_AGENT
		$rtn_url = $get_d->rtn_url; //로그인 후 연결 URL
		if($rtn_url == ""){
			$rtn_url = "/";
		}
		$code = "0";
		$message = "success";
		//log_message("ERROR", "/autologin/login_profile_key > profile_key : ". $profile_key .", req_site_name : ". $req_site_name .", req_site_url : ". $req_site_url .", rtn_url : ". $rtn_url .", req_user_agent : ". $req_user_agent);

		$is_dormant_member = false;
		$userselect = "mem_id, mem_userid";
		$profile = $this->Member_model->get_by_profile_key($profile_key, $userselect);
		if ($profile) {
			$mem_id = $profile->mem_id; //회원번호
			$mem_userid = $profile->mem_userid; //회원ID
			$userinfo = $this->Member_model->get_by_userid($mem_userid, 'mem_id, mem_userid');
			//$userinfo = $this->Member_model->get_by_both($mem_userid, 'mem_id, mem_userid');
			if ($userinfo) {
				$is_dormant_member = true;
				//log_message("ERROR", "/autologin/login_profile_key > element(mem_id) : ". element('mem_id', $userinfo) .", element(mem_userid) : ". element('mem_userid', $userinfo));
				//$this->Member_model->update_login_log(element('mem_id', $userinfo), element('mem_userid', $userinfo), 1, '로그인 성공 ('. $req_site_name .')', $req_user_agent, $req_site_url);
				//log_message("ERROR", "/autologin/login_profile_key > update_login_log 기록 ");
				//$this->session->set_userdata(
				//	'mem_id', element('mem_id', $userinfo)
				//);
				
				$this->member->update_login_log($mem_id, $mem_userid, 1, '로그인 성공 ('. $req_site_name .')');
				$this->session->set_userdata(
				    'mem_id',
				   $mem_id
				    );
				log_message("ERROR", "/autologin/login_profile_key > set_userdata ");
				log_message("ERROR", "/autologin/login_profile_key > session : ". $this->session->userdata('mem_id'));
				log_message("ERROR", "/autologin/login_profile_key > is_member : ". $this->member->is_member());
				log_message("ERROR", "/autologin/login_profile_key > rtn_url : ". $rtn_url .", site_url() : ". site_url() .", ip_address : ". $this->input->ip_address());
				
				if ($this->member->is_member() != "") {
					
					/*
					//log_message("ERROR", "/autologin/login_profile_key > this->member->is_member()");
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
					
					$cookie_name = 'autologin';
					$cookie_value = $hash;
					$cookie_expire = (60*60*24*30); // 30일간 저장
					log_message("ERROR", "/autologin/login_profile_key > cookie_name : ". $cookie_name .", cookie_value : ". $cookie_value .", cookie_expire : ". $cookie_expire);

					$this->load->helper('cookie');
					set_cookie($cookie_name, $cookie_value, $cookie_expire);
					//$this->input->set_cookie($cookie_name, $cookie_value, $cookie_expire, '.o2omsg.com', '/');
					//set_cookie($cookie_name, $cookie_value, $cookie_expire, ".o2omsg.com", "/");
					log_message("ERROR", "/autologin/login_profile_key > get_cookie('autologin') : ". get_cookie($cookie_name));
					
					//setcookie($cookie_name, $cookie_value, $cookie_expire);
					//setcookie($cookie_name, $cookie_value, $cookie_expire, "/");
					//setcookie($cookie_name, $cookie_value, $cookie_expire, "/", "http://o2omsg.com");
					//setcookie($cookie_name, $cookie_value, $cookie_expire, "/", ".o2omsg.com");
					//log_message("ERROR", "/autologin/login_profile_key > _COOKIE['autologin'] : ". $_COOKIE['autologin']);
					
					//$url_after_login = ($rtn_url) ? site_url() . urldecode($rtn_url) : site_url();
					//$url_after_login = site_url();
					$url_after_login = "http://o2omsg.com/dhnbiz/sender/send/message";
					//$url_after_login = "/dhnbiz/sender/send/message";
					log_message("ERROR", "/autologin/login_profile_key > url_after_login : ". $url_after_login);
					redirect($url_after_login);
					//redirect(site_url());
					//return;
					//Header("Location: /");
					//exit;
					//return;
					*/

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
					//return;

				}else{
					$respo_no = 104;
					$respo_msg = "회원정보 세션이 존재하지 않습니다.";
					header('Content-Type: application/json');
					echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'"}';
				}
			}else{
				$respo_no = 103;
				$respo_msg = "회원정보가 존재하지 않습니다.";
				header('Content-Type: application/json');
				echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'"}';
			}
		}else{
			$respo_no = 102;
			$respo_msg = "인증키를 확인 하세요.";
			header('Content-Type: application/json');
			echo '{"response_code": "'.$respo_no.'", "response_message":"'.$respo_msg.'"}';
		}
	}

}
