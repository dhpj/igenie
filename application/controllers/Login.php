<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Login class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 로그인 페이지와 관련된 controller 입니다.
 */
class Login extends CB_Controller
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

    }

    /**
     * 로그인 페이지입니다
     */
    public function index()
    {
        //log_message("ERROR", "/login/index");
		$this->proc();
    }

    public function proc()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_login_index';
        $this->load->event($eventname);
        if ($this->member->is_member() !== false && ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
			//redirect();
			redirect("/home");
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $this->load->library(array('form_validation'));

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        $use_login_account = $this->cbconfig->item('use_login_account');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        if ($use_login_account === 'both') {
            $config[] = array(
                'field' => 'mem_userid',
                'label' => '아이디 또는 이메일',
                'rules' => 'trim|required',
            );
            $view['view']['userid_label_text'] = '아이디 또는 이메일';
        } elseif ($use_login_account === 'email') {
            $config[] = array(
                'field' => 'mem_userid',
                'label' => '이메일',
                'rules' => 'trim|required|valid_email',
            );
            $view['view']['userid_label_text'] = '이메일';
        } else {
            $config[] = array(
                'field' => 'mem_userid',
                'label' => '아이디',
                'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]',
            );
            $view['view']['userid_label_text'] = '아이디';
        }
        $config[] = array(
            'field' => 'mem_password',
            'label' => '패스워드',
            'rules' => 'trim|required|min_length[4]|callback__check_id_pw[' . $this->input->post('mem_userid') . ']',
        );

        $this->form_validation->set_rules($config);


        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            if ($this->input->post('returnurl')) {
                if (validation_errors('<div class="alert alert-warning" role="alert">', '</div>')) {
                    $this->session->set_flashdata(
                        'loginvalidationmessage',
                        validation_errors('<div class="alert alert-warning" role="alert">', '</div>')
                        );
                }
                $this->session->set_flashdata(
                    'loginuserid',
                    $this->input->post('mem_userid')
                    );
                redirect(urldecode($this->input->post('returnurl')));
            }

            $view['view']['canonical'] = site_url('login');

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_login');
            $meta_description = $this->cbconfig->item('site_meta_description_login');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_login');
            $meta_author = $this->cbconfig->item('site_meta_author_login');
            $page_name = $this->cbconfig->item('site_page_name_login');

            $layoutconfig = array(
                'path' => 'login',
                'layout' => 'layout_login',
                'skin' => 'login',
                'layout_dir' => $this->cbconfig->item('layout_login'),
                'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_login'),
                'use_sidebar' => $this->cbconfig->item('sidebar_login'),
                'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_login'),
                'skin_dir' => $this->cbconfig->item('skin_login'),
                'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_login'),
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
        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            if ($use_login_account === 'both') {
                $userinfo = $this->Member_model->get_by_both($this->input->post('mem_userid'), 'mem_id, mem_userid');
            } elseif ($use_login_account === 'email') {
                $userinfo = $this->Member_model->get_by_email($this->input->post('mem_userid'), 'mem_id, mem_userid');
            } else {
                $userinfo = $this->Member_model->get_by_userid($this->input->post('mem_userid'), 'mem_id, mem_userid');
            }

            // 2021.12.30 조지영 휴면 계정 로그인 팝업
            $sql = "
            SELECT
              count(1) as cnt
              FROM cb_member_dormant_dhn
              WHERE mem_id ='".element('mem_id', $userinfo)."' AND mdd_dormant_flag = '1'
            ";
            $cnt = $this->db->query($sql)->row()->cnt;

            if ($cnt == 0){
                $this->member->update_login_log(element('mem_id', $userinfo), $this->input->post('mem_userid'), 1, '로그인 성공');
                $sql = "select mll_id from cb_member_login_log where mem_id = '".element('mem_id', $userinfo)."' order by mll_id desc LIMIT 1";
                $mllid = $this->db->query($sql)->row()->mll_id;

                $this->session->set_userdata(
                    'mem_id',
                    element('mem_id', $userinfo)
                    );

                $this->session->set_userdata(
                    'mll_id',
                    $mllid
                    );

                if ($this->input->post('autologin')) {
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
                    $this->Autologin_model->insert($insertautologin);

                    $cookie_name = 'autologin';
                    $cookie_value = $hash;
                    $cookie_expire = 2592000; // 30일간 저장
                    set_cookie($cookie_name, $cookie_value, $cookie_expire);
                }

                $change_password_date = $this->cbconfig->item('change_password_date');

                $site_title = $this->cbconfig->item('site_title');
                //log_message("ERROR", " PASS DATE : ".$change_password_date);
                /*
                 if ($change_password_date) {

                 $meta_change_pw_datetime = $this->member->item('meta_change_pw_datetime');
                 //log_message("ERROR", " PASS DATE : ".$meta_change_pw_datetime);
                 if ( ctimestamp() - strtotime($meta_change_pw_datetime) > $change_password_date * 86400) {
                 $this->session->set_userdata(
                 'membermodify',
                 '1'
                 );
                 $this->session->set_flashdata(
                 'message',
                 html_escape($site_title) . ' 은(는) 회원님의 비밀번호를 주기적으로 변경하도록 권장합니다.
                 <br /> 오래된 비밀번호를 사용중인 회원님께서는 안전한 서비스 이용을 위해 비밀번호 변경을 권장합니다'
                 );
                 redirect('membermodify/password_modify');
                 }
                 }
                 */
                $url_after_login = $this->cbconfig->item('url_after_login');
                if ($url_after_login) { //다음페이지가 있는 경우
                    $url_after_login = site_url($url_after_login);
                }
                if (empty($url_after_login)) { //다음페이지가 없는 경우
                    //$url_after_login = $this->input->get_post('url') ? urldecode($this->input->get_post('url')) : site_url();
    				$url_after_login = "/home";
                }

                // 이벤트가 존재하면 실행합니다
                Events::trigger('after', $eventname);
                //log_message("ERROR", " URL : ".$url_after_login);
                if($this->member->item("mem_pay_type") == "B" ) {
                    //if($this->member->item("mem_id") == "3" ) {
                    $sql = "select count(1) as cnt from cb_message_receiver where to_mem_id = '". $this->member->item("mem_id")."' and remain_date ='".cdate('Ymd')."'";
                    $cnt = $this->db->query($sql)->row()->cnt;

                    if($cnt == 0) {
                        $sql = "SELECT SUM(amt_amount * amt_deduct) amt FROM cb_amt_".$this->member->item("mem_userid");
                        $amt = $this->db->query($sql)->row()->amt;

                        if($amt < 30000) {
                            $this->session->set_userdata('isNotice', 'Y');
                            $message = array();
                            $receiver = $this->input->post("receiver_list");
                            $message['title'] = "잔액 확인 공지";
                            $message['msg'] ="잔액이 30,000원 미만입니다.\n발송 전 충전 하세요.";
                            $message['expiration_date'] = cdate('Y-m-d');
                            $message['send_date'] = cdate('Y-m-d');
                            $message['from_mem_id'] = "2";

                            $this->db->insert("message", $message);
                            $msg_id = $this->db->insert_id();

                            $msg_receiver = array();
                            $msg_receiver['msg_id'] = $msg_id;
                            $msg_receiver['to_mem_id'] = $this->member->item("mem_id");
                            $msg_receiver['is_alam'] = 'Y';
                            $msg_receiver['remain_date'] = cdate('Ymd');

                            $this->db->insert("message_receiver", $msg_receiver);

                        }
                    }
                }
                redirect($url_after_login);
            }else {
                $this->member->update_login_log(element('mem_id', $userinfo), $this->input->post('mem_userid'), 0, '휴면 계정');
                $view["dormancy"] = 1;
                $layoutconfig = array(
                    'path' => 'login',
                    'layout' => 'layout_login',
                    'skin' => 'login',
                    'layout_dir' => $this->cbconfig->item('layout_login'),
                    'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_login'),
                    'use_sidebar' => $this->cbconfig->item('sidebar_login'),
                    'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_login'),
                    'skin_dir' => $this->cbconfig->item('skin_login'),
                    'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_login'),
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


        }
    }


    /**
     * 로그인시 아이디와 패스워드가 일치하는지 체크합니다
     */
    public function _check_id_pw($password, $userid)
    {
        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        $max_login_try_count = (int) $this->cbconfig->item('max_login_try_count');
        $max_login_try_limit_second = (int) $this->cbconfig->item('max_login_try_limit_second');

        $loginfailnum = 0;
        $loginfailmessage = '';
        if ($max_login_try_count && $max_login_try_limit_second) {
            $select = 'mll_id, mll_success, mem_id, mll_ip, mll_datetime';
            $where = array(
                'mll_ip' => $this->input->ip_address(),
                'mll_datetime > ' => strtotime(ctimestamp() - 86400 * 30),
            );
            $this->load->model('Member_login_log_model');
            $logindata = $this->Member_login_log_model
            ->get('', $select, $where, '', '', 'mll_id', 'DESC');

            if ($logindata && is_array($logindata)) {
                foreach ($logindata as $key => $val) {
                    if ((int) $val['mll_success'] === 0) {
                        $loginfailnum++;
                    } elseif ((int) $val['mll_success'] === 1) {
                        break;
                    }
                }
            }
            if ($loginfailnum > 0 && $loginfailnum % $max_login_try_count === 0) {
                $lastlogintrydatetime = $logindata[0]['mll_datetime'];
                $next_login = strtotime($lastlogintrydatetime)
                + $max_login_try_limit_second
                - ctimestamp();
                if ($next_login > 0) {
                    $this->form_validation->set_message(
                        '_check_id_pw',
                        '회원님은 패스워드를 연속으로 ' . $loginfailnum . '회 잘못 입력하셨기 때문에 '
                        . $next_login . '초 후에 다시 로그인 시도가 가능합니다'
                        );
                    return false;
                }
            }
            $loginfailmessage = '<br />회원님은 ' . ($loginfailnum + 1)
            . '회 연속으로 패스워드를 잘못입력하셨습니다. ';
        }

        $use_login_account = $this->cbconfig->item('use_login_account');

        $this->load->model(array('Member_dormant_model'));

        $userselect = 'mem_id, mem_password, mem_denied, mem_email_cert, mem_is_admin, mem_useyn';
        $is_dormant_member = false;
        if ($use_login_account === 'both') {
            $userinfo = $this->Member_model->get_by_both($userid, $userselect);
            if ( ! $userinfo) {
                $userinfo = $this->Member_dormant_model->get_by_both($userid, $userselect);
                if ($userinfo) {
                    $is_dormant_member = true;
                }
            }
        } elseif ($use_login_account === 'email') {
            $userinfo = $this->Member_model->get_by_email($userid, $userselect);
            if ( ! $userinfo) {
                $userinfo = $this->Member_dormant_model->get_by_email($userid, $userselect);
                if ($userinfo) {
                    $is_dormant_member = true;
                }
            }
        } else {
            $userinfo = $this->Member_model->get_by_userid($userid, $userselect);
            if ( ! $userinfo) {
                $userinfo = $this->Member_dormant_model->get_by_userid($userid, $userselect);
                if ($userinfo) {
                    $is_dormant_member = true;
                }
            }
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);
        if (element('mem_useyn', $userinfo)!="Y" OR ! element('mem_id', $userinfo) OR ! element('mem_password', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원 아이디와 패스워드가 서로 맞지 않습니다' . $loginfailmessage
                );
            $this->member->update_login_log(0, $userid, 0, '회원 아이디가 존재하지 않습니다');
            return false;
        } elseif ( ! password_verify($password, element('mem_password', $userinfo))) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원 아이디와 패스워드가 서로 맞지 않습니다' . $loginfailmessage
                );
            $this->member->update_login_log(element('mem_id', $userinfo), $userid, 0, '패스워드가 올바르지 않습니다');
            return false;
        } elseif (element('mem_denied', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원님의 아이디는 접근이 금지된 아이디입니다'
                );
            $this->member->update_login_log(element('mem_id', $userinfo), $userid, 0, '접근이 금지된 아이디입니다');
            return false;
        } elseif ($this->cbconfig->item('use_register_email_auth') && ! element('mem_email_cert', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원님은 아직 이메일 인증을 받지 않으셨습니다'
                );
            $this->member->update_login_log(element('mem_id', $userinfo), $userid, 0, '이메일 인증을 받지 않은 회원아이디입니다');
            return false;
        } elseif (element('mem_is_admin', $userinfo) && $this->input->post('autologin')) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '최고관리자는 자동로그인 기능을 사용할 수 없습니다'
                );
            return false;
        }

        if ($is_dormant_member === true) {
            $this->member->recover_from_dormant(element('mem_id', $userinfo));
        }

        return true;
    }


    /**
     * 로그아웃합니다
     */
    public function logout()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_logout_index';
        $this->load->event($eventname);

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        if ($this->member->is_member() === false) {
            redirect();
        }

        if(!empty($this->session->userdata('mll_id'))){
            $outid = $this->session->userdata('mll_id');
        }
        // else{
        //$ip = $_SERVER['REMOTE_ADDR'];
        //     $sql = "select mll_id from cb_member_login_log where mll_ip = '".$ip."' AND mem_id ='".$this->member->item("mem_id")."' order by mll_id desc limit 1";
        //     $outid = $this->db->query($sql)->row()->mll_id;
        // }

        $this->member->update_login_log($this->member->item('mem_id'), $this->member->item('mem_userid'), 1, '로그아웃||'.$outid);


        $where = array(
            'mem_id' => $this->member->item('mem_id'),
        );
        $this->load->model(array('Autologin_model'));
        $this->Autologin_model->delete_where($where);

        delete_cookie('autologin');

        $this->session->sess_destroy();
        $url_after_logout = $this->cbconfig->item('url_after_logout');
        if ($url_after_logout) {
            $url_after_logout = site_url($url_after_logout);
        }
        if (empty($url_after_logout)) {
            $url_after_logout = $this->input->get_post('url') ? $this->input->get_post('url') : site_url();
        }

        // 이벤트가 존재하면 실행합니다
        Events::trigger('after', $eventname);

        redirect($url_after_logout, 'refresh');
    }

    Public function loginapi()
    {
        $data = json_decode( file_get_contents('php://input') );
        $userid = $data->userid;
        $password = $data->password;
        //log_message("ERROR", "/login/loginapi > userid : ".$userid.", password : ".$password);
        $result = "";

        $userinfo = $this->Member_model->get_by_userid($userid, $userselect);
        /*if ( ! $userinfo) {
            $userinfo = $this->Member_dormant_model->get_by_userid($userid, $userselect);
            if ($userinfo) {
                $is_dormant_member = true;
            }
        }*/
        $ip = $_SERVER['REMOTE_ADDR'];

        $hash = password_hash($password, PASSWORD_BCRYPT);
        //log_message("ERROR", "PW : ".$hash);
        if ( ! element('mem_id', $userinfo) OR ! element('mem_password', $userinfo)) {
            $result = '회원 아이디와 패스워드가 서로 맞지 않습니다';
        } elseif ( ! password_verify($password, element('mem_password', $userinfo))) {
            $result = '회원 아이디와 패스워드가 서로 맞지 않습니다';
        } elseif (element('mem_denied', $userinfo)) {
            $result = '회원님의 아이디는 접근이 금지된 아이디입니다';
        } elseif ($this->cbconfig->item('use_register_email_auth') && ! element('mem_email_cert', $userinfo)) {
            $result = '회원님은 아직 이메일 인증을 받지 않으셨습니다';
        } elseif (element('mem_is_admin', $userinfo) && $this->input->post('autologin')) {
            $result = '최고관리자는 자동로그인 기능을 사용할 수 없습니다';
        }
        $code ="";

        $sql = "update cb_member cm set cm.mem_ip = '".$ip."' where cm.mem_id = '".element('mem_id', $userinfo)."'";
        $this->db->query($sql);

        if(empty($result)) {
            $code = "OK";
            $result = "정상 로그인 되었습니다.";
        } else {
            $code = "ERROR";
        }

        header('Content-Type: application/json');
        echo '{"code": "'.$code.'", "msg":"'.$result.'"}';
    }

	//샘플발송신청
	public function alimtalk_test(){
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 샘플발송신청");
		/*$postfields = array(
            "receive_phn"=>$this->input->post('receive_phn')
        );*/
        $send_arr = array();
        $data = array();
        //발송번호
        $phone_num = $this->input->post('receive_phn');
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > phone_num : " . $phone_num);
        //현재날짜
        $today = date("Y-m-d");
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > today : " . $today);
        $sql = "SELECT idx , send_count, phone_num FROM cb_sample_send_check WHERE phone_num = '".$phone_num."' AND STR_TO_DATE(send_datetime, '%Y-%m-%d') = '".$today."' AND send_p='M' ";
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : " . $sql );
        $send_arr['data'] = $this->db->query($sql)->row();
        $send_yn='N';
        //샘플발송 count 판별
        if(empty($send_arr['data'])){
          $sample_arr = array();
          $sample_arr['phone_num'] = $phone_num;
          $sample_arr['send_datetime'] = date('Y-m-d H:i:s');
          $sample_arr['send_p'] = "M";
          $sample_arr['send_count'] = 1;
          $this->db->insert("cb_sample_send_check", $sample_arr);
          $send_yn = 'Y';
        }else{
          $data['idx'] = $send_arr['data']->idx;
          $data['send_count'] = $send_arr['data']->send_count;
          $data['phone_num'] = $send_arr['data']->phone_num;
          if($data['send_count'] <1){
            $sql ="UPDATE cb_sample_send_check SET send_count = send_count + 1 WHERE idx = ".$data['idx'];
            $this->db->query($sql);
            $send_yn = 'N';
          }else{
            $send_yn='N';
          }
        }
        //샘플메시지 발송
        if($send_yn =='Y'){
$postfields = array(
//           "day_test_cnt" => 20 //일일 테스트 발송량
//          ,"receive_phn" => $this->input->post('receive_phn') //수신번호
//          ,"profile_key" => "a1ecba7023ae98fb15039c339afd73ae2e17d515" //프로필키
//          ,"tmp_number" => "2020111311" //템플릿번호
//          ,"kakao_url" => ""
//          ,"kakao_name" => ""
//          ,"kakao_sender" => "0557137985" //발신번호
//          ,"kakao_add1" => "대형마트" //변수내용1 (업체명)
//          ,"kakao_add2" => date("Y년 n월 j일 ") //변수내용2 (날짜)
//          ,"kakao_add3" => "★점장님이쏜다!카카오톡친구에 한함/2만원이상 구매시 구매가능★
//
// 대파 1단 3,900원→1,980원[1인1단/100단한정]
// 팽이버섯 3묶음 1,900원→500원[1인1묶음/10박스한정]
// 활전복 5미 13,800원→7,900원[60팩한정]
// 초코오징어(국산) 3미 14,800원→8,900원[150마리한정]
//
// ▶정육코너 5만원이상 구매시 한돈찌개거리 500g증정(6천원상당)
//
// ▼정육코너
// 한우모듬구이1등급(꽃등심/채끝/앞치마살) 100g 11,000원→7,590원[80팩한정]
// 한우차돌박이1등급 100g 8,200원→5,580원[80팩한정]
// 한우양지국거리1등급 100g 5,900원→3,980원[3마리분량한정]
// 한우불고기1등급 100g 4,480원→2,990원[3마리분량한정]
// 목우촌미박삼겹살 100g 2,890원→1,590원[200kg한정]
// 흑돼지소금구이(구이/수육용) 100g 1,950원→990원[200kg한정]
// 한돈냉장목살 100g 2,480원→990원[1인3kg한정/300kg한정]
// 생오리양념불고기 100g 1,580원→850원[100kg한정]" //변수내용3 (변수내용)
//          ,"kakao_add4" => "대형마트" //변수내용4 (업체명)
//          ,"kakao_add5" => "1522-7985" //변수내용5 (업체전화번호)
//          ,"kakao_add6" => "1522-7985" //변수내용6 (업체전화번호)
//          ,"kakao_add7" => "" //변수내용7
//          ,"kakao_add8" => "" //변수내용8
//          ,"kakao_add9" => "" //변수내용9
//          ,"kakao_add10" => "" //변수내용10
//          ,"kakao_080" => ""
//          ,"kakao_res" => ""
//          ,"kakao_res_date" => ""
//          ,"tran_replace_type" => ""
//          ,"kakao_url1_1" => "http://igenie.co.kr/smart/view/eSNlpqmw" //버튼링크1 모바일 (행사보기)
//          ,"kakao_url1_2" => "http://igenie.co.kr/smart/view/eSNlpqmw" //버튼링크1 PC (행사보기)
//          ,"kakao_url2_1" => "http://igenie.co.kr/at/eSNGASxT" //버튼링크2 모바일 (전단보기)
//          ,"kakao_url2_2" => "http://igenie.co.kr/at/eSNGASxT" //버튼링크2 PC (전단보기)
//          ,"kakao_url3_1" => "http://dhmart.pfmall.co.kr" //버튼링크2 모바일 (주문하기)
//          ,"kakao_url3_2" => "http://dhmart.pfmall.co.kr" //버튼링크2 PC (주문하기)
//          ,"kakao_url4_1" => "" //버튼링크4 모바일
//          ,"kakao_url4_2" => "" //버튼링크4 PC
//          ,"kakao_url5_1" => "" //버튼링크5 모바일
//          ,"kakao_url5_2" => "" //버튼링크5 PC
//          ,"respo_msg" => "요청하신 번호로 알림톡이 발송되었습니다." //성공 메시지

          "day_test_cnt" => 20 //일일 테스트 발송량
          ,"receive_phn" => $this->input->post('receive_phn') //수신번호
          ,"profile_key" => "a1ecba7023ae98fb15039c339afd73ae2e17d515" //프로필키
          ,"tmp_number" => "210818-19" //템플릿번호
          ,"kakao_url" => ""
          ,"kakao_name" => ""
          ,"kakao_sender" => "0557137985" //발신번호
          ,"kakao_add1" => "대형마트" //변수내용1 (업체명)
          ,"kakao_add2" => "★점장님이쏜다!카카오톡친구에 한함/2만원이상 구매시 구매가능★

대파 1단 3,900원→1,980원[1인1단/100단한정]
팽이버섯 3묶음 1,900원→500원[1인1묶음/10박스한정]
활전복 5미 13,800원→7,900원[60팩한정]
초코오징어(국산) 3미 14,800원→8,900원[150마리한정]

▶정육코너 5만원이상 구매시 한돈찌개거리 500g증정(6천원상당)

▼정육코너
한우모듬구이1등급(꽃등심/채끝/앞치마살) 100g 11,000원→7,590원[80팩한정]
한우차돌박이1등급 100g 8,200원→5,580원[80팩한정]
한우양지국거리1등급 100g 5,900원→3,980원[3마리분량한정]
한우불고기1등급 100g 4,480원→2,990원[3마리분량한정]
목우촌미박삼겹살 100g 2,890원→1,590원[200kg한정]
흑돼지소금구이(구이/수육용) 100g 1,950원→990원[200kg한정]
한돈냉장목살 100g 2,480원→990원[1인3kg한정/300kg한정]
생오리양념불고기 100g 1,580원→850원[100kg한정]" //변수내용3 (변수내용)
          ,"kakao_add3" => "1522-7985" //변수내용4 (업체명)
          ,"kakao_add4" => "" //변수내용4 (업체명)
          ,"kakao_add5" => "" //변수내용5 (업체전화번호)
          ,"kakao_add6" => "" //변수내용6 (업체전화번호)
          ,"kakao_add7" => "" //변수내용7
          ,"kakao_add8" => "" //변수내용8
          ,"kakao_add9" => "" //변수내용9
          ,"kakao_add10" => "" //변수내용10
          ,"kakao_080" => ""
          ,"kakao_res" => ""
          ,"kakao_res_date" => ""
          ,"tran_replace_type" => ""
          ,"kakao_url1_1" => "http://igenie.co.kr/smart/view/eSNlpqmw" //버튼링크1 모바일 (행사보기)
          ,"kakao_url1_2" => "http://igenie.co.kr/smart/view/eSNlpqmw" //버튼링크1 PC (행사보기)
          ,"kakao_url2_1" => "http://igenie.co.kr/at/eSNGASxT" //버튼링크2 모바일 (전단보기)
          ,"kakao_url2_2" => "http://igenie.co.kr/at/eSNGASxT" //버튼링크2 PC (전단보기)
          ,"kakao_url3_1" => "" //버튼링크2 모바일 (주문하기)
          ,"kakao_url3_2" => "" //버튼링크2 PC (주문하기)
          ,"kakao_url4_1" => "" //버튼링크4 모바일
          ,"kakao_url4_2" => "" //버튼링크4 PC
          ,"kakao_url5_1" => "" //버튼링크5 모바일
          ,"kakao_url5_2" => "" //버튼링크5 PC
          ,"respo_msg" => "요청하신 번호로 알림톡이 발송되었습니다." //성공 메시지

      );
      $postitem = json_encode($postfields, JSON_UNESCAPED_UNICODE);
      $ch = curl_init();

      $options = array(
          //CURLOPT_URL => base_url('bizmsgapi/testalimtk'),
          CURLOPT_URL => base_url('bizmsgapi/sample_img_alimtk'),
          CURLOPT_HEADER => false,
          CURLOPT_POST => 1,
          CURLOPT_HTTPHEADER => array("Content-Type:application/json"),
          CURLOPT_POSTFIELDS => $postitem,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_USERAGENT => $this->agent,
          CURLOPT_REFERER => "",
          CURLOPT_TIMEOUT => 10
      );
      curl_setopt_array($ch, $options);
      $buffer = curl_exec ($ch);
      $cinfo = curl_getinfo($ch);
      //log_message("ERROR", base_url('bizmsgapi/testalimtk')."/".$buffer);
      curl_close($ch);
      header('Content-Type: application/json');
      echo $buffer;
        }else{
          $result_f = array();
          $result_f['code'] = '0';
          $json = json_encode($result_f,JSON_UNESCAPED_UNICODE);
          header('Content-Type: application/json');
          echo $json;
        }
    }

    //샘플발송신청
  	public function alimtalk_test2(){
          log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 샘플발송신청");
          $send_arr = array();
          $data = array();
          //발송번호
          $phone_num = $this->input->post('receive_phn');
          // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > phone_num : " . $phone_num);
          //현재날짜
          $today = date("Y-m-d");
          // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > today : " . $today);
          $sql = "SELECT idx , send_count, phone_num FROM cb_sample_send_check WHERE phone_num = '".$phone_num."' AND STR_TO_DATE(send_datetime, '%Y-%m-%d') = '".$today."' AND send_p='S' ";
          // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql : " . $sql );
          $send_arr['data'] = $this->db->query($sql)->row();
          $send_yn='N';
          //샘플발송 count 판별
          if(empty($send_arr['data'])){
            $sample_arr = array();
            $sample_arr['phone_num'] = $phone_num;
            $sample_arr['send_datetime'] = date('Y-m-d H:i:s');
            $sample_arr['send_p'] = "S";
            $sample_arr['send_count'] = 1;
            $this->db->insert("cb_sample_send_check", $sample_arr);
            $send_yn = 'Y';
          }else{
            $data['idx'] = $send_arr['data']->idx;
            $data['send_count'] = $send_arr['data']->send_count;
            $data['phone_num'] = $send_arr['data']->phone_num;
            if($data['send_count'] <1){
              $sql ="UPDATE cb_sample_send_check SET send_count = send_count + 1 WHERE idx = ".$data['idx'];
              $this->db->query($sql);
              $send_yn = 'N';
            }else{
              $send_yn='N';
            }
          }
          //샘플메시지 발송
          if($send_yn =='Y'){
            $postfields = array(
//                     "day_test_cnt" => 20 //일일 테스트 발송량
//                    ,"receive_phn" => $this->input->post('receive_phn') //수신번호
//                    ,"profile_key" => "a1ecba7023ae98fb15039c339afd73ae2e17d515" //프로필키
//                    ,"tmp_number" => "2020111308" //템플릿번호
//                    ,"kakao_url" => ""
//                    ,"kakao_name" => ""
//                    ,"kakao_sender" => "0557137985" //발신번호
//                    ,"kakao_add1" => "카카오지니 정육점" //변수내용1 (업체명)
//                    ,"kakao_add2" => date("Y년 n월 j일 ") //변수내용2 (날짜)
//                    ,"kakao_add3" => "여름 휴가 맞이 할인행사 진행합니다.
//
// ★토마호크 바베큐 세트★
// -토마호크 바베큐 (고기만)
// 80,000원 -> 45,000원
// -토마호크 바베큐 (채소세트)
// 95,000원 -> 55,000원
// -토마호크 바베큐 (채소,소스 세트)
// 10,000원 -> 58,000원
//
// ☆한우 행사☆
// -한우 치마살 (100g당)
// 9,000원 -> 3,000원
// -한우 차돌박이 (100g당)
// 8,500원 -> 3,450원
//
// ☆한돈 행사☆
// -목살 (100g당)
// 3,500원 -> 1,750원
// -삼겹살 (100g당)
// 2,900원 -> 1,350원" //변수내용3 (변수내용)
//                    ,"kakao_add4" => "카카오지니 정육점" //변수내용4 (업체명)
//                    ,"kakao_add5" => "1522-7985" //변수내용5 (업체전화번호)
//                    ,"kakao_add6" => "1522-7985" //변수내용6 (업체전화번호)
//                    ,"kakao_add7" => "" //변수내용7
//                    ,"kakao_add8" => "" //변수내용8
//                    ,"kakao_add9" => "" //변수내용9
//                    ,"kakao_add10" => "" //변수내용10
//                    ,"kakao_080" => ""
//                    ,"kakao_res" => ""
//                    ,"kakao_res_date" => ""
//                    ,"tran_replace_type" => ""
//                    ,"kakao_url1_1" => "http://igenie.co.kr/smart/view/hf6yrz1aw" //버튼링크1 모바일 (행사보기)
//                    ,"kakao_url1_2" => "http://igenie.co.kr/smart/view/hf6yrz1aw" //버튼링크1 PC (행사보기)
//                    ,"kakao_url2_1" => "http://igenie.co.kr/smart/coupon/hf6yrddj4" //버튼링크2 모바일 (전단보기)
//                    ,"kakao_url2_2" => "http://igenie.co.kr/smart/coupon/hf6yrddj4" //버튼링크2 PC (전단보기)
//                    ,"kakao_url3_1" => "http://dhmart.pfmall.co.kr" //버튼링크2 모바일 (주문하기)
//                    ,"kakao_url3_2" => "http://dhmart.pfmall.co.kr" //버튼링크2 PC (주문하기)
//                    ,"kakao_url4_1" => "" //버튼링크4 모바일
//                    ,"kakao_url4_2" => "" //버튼링크4 PC
//                    ,"kakao_url5_1" => "" //버튼링크5 모바일
//                    ,"kakao_url5_2" => "" //버튼링크5 PC
//                    ,"respo_msg" => "요청하신 번호로 알림톡이 발송되었습니다." //성공 메시지
                      "day_test_cnt" => 20 //일일 테스트 발송량
                      ,"receive_phn" => $this->input->post('receive_phn') //수신번호
                      ,"profile_key" => "a1ecba7023ae98fb15039c339afd73ae2e17d515" //프로필키
                      ,"tmp_number" => "210826" //템플릿번호
                      ,"kakao_url" => ""
                      ,"kakao_name" => ""
                      ,"kakao_sender" => "0557137985" //발신번호
                      ,"kakao_add1" => "카카오지니 정육점" //변수내용1 (업체명)
                      ,"kakao_add2" => "여름 휴가 맞이 할인행사 진행합니다.

★토마호크 바베큐 세트★
-토마호크 바베큐 (고기만)
80,000원 -> 45,000원
-토마호크 바베큐 (채소세트)
95,000원 -> 55,000원
-토마호크 바베큐 (채소,소스 세트)
10,000원 -> 58,000원

☆한우 행사☆
-한우 치마살 (100g당)
9,000원 -> 3,000원
-한우 차돌박이 (100g당)
8,500원 -> 3,450원

☆한돈 행사☆
-목살 (100g당)
3,500원 -> 1,750원
-삼겹살 (100g당)
2,900원 -> 1,350원" //변수내용3 (변수내용)
                ,"kakao_add3" => "1522-7985" //변수내용4 (업체명)
                ,"kakao_add4" => "" //변수내용4 (업체명)
                ,"kakao_add5" => "" //변수내용5 (업체전화번호)
                ,"kakao_add6" => "" //변수내용6 (업체전화번호)
                ,"kakao_add7" => "" //변수내용7
                ,"kakao_add8" => "" //변수내용8
                ,"kakao_add9" => "" //변수내용9
                ,"kakao_add10" => "" //변수내용10
                ,"kakao_080" => ""
                ,"kakao_res" => ""
                ,"kakao_res_date" => ""
                ,"tran_replace_type" => ""
                ,"kakao_url1_1" => "http://igenie.co.kr/smart/view/hf6yrz1aw" //버튼링크1 모바일 (행사보기)
                ,"kakao_url1_2" => "http://igenie.co.kr/smart/view/hf6yrz1aw" //버튼링크1 PC (행사보기)
                ,"kakao_url2_1" => "" //버튼링크2 모바일 (전단보기)
                ,"kakao_url2_2" => "" //버튼링크2 PC (전단보기)
                ,"kakao_url3_1" => "" //버튼링크2 모바일 (주문하기)
                ,"kakao_url3_2" => "" //버튼링크2 PC (주문하기)
                ,"kakao_url4_1" => "" //버튼링크4 모바일
                ,"kakao_url4_2" => "" //버튼링크4 PC
                ,"kakao_url5_1" => "" //버튼링크5 모바일
                ,"kakao_url5_2" => "" //버튼링크5 PC
                ,"respo_msg" => "요청하신 번호로 알림톡이 발송되었습니다." //성공 메시지
                );
                $postitem = json_encode($postfields, JSON_UNESCAPED_UNICODE);
                $ch = curl_init();

                $options = array(
                  //CURLOPT_URL => base_url('bizmsgapi/testalimtk'),
                    CURLOPT_URL => base_url('bizmsgapi/sample_img_alimtk'),
                    CURLOPT_HEADER => false,
                    CURLOPT_POST => 1,
                    CURLOPT_HTTPHEADER => array("Content-Type:application/json"),
                    CURLOPT_POSTFIELDS => $postitem,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_USERAGENT => $this->agent,
                    CURLOPT_REFERER => "",
                    CURLOPT_TIMEOUT => 10
                );
                curl_setopt_array($ch, $options);
                $buffer = curl_exec ($ch);
                $cinfo = curl_getinfo($ch);
                //log_message("ERROR", base_url('bizmsgapi/testalimtk')."/".$buffer);
                curl_close($ch);
                header('Content-Type: application/json');
                echo $buffer;
          }else{
            $result_f = array();
            $result_f['code'] = '0';
            $json = json_encode($result_f,JSON_UNESCAPED_UNICODE);
            header('Content-Type: application/json');
            echo $json;
          }

      }

    //타사이트에서 로그인
	Public function igenie_loginapi(){
        $site_url = $this->input->post('site_url'); //로그인 사이트
        $userid = $this->input->post('userid'); //사이트 연동 ID
        $site_key = $this->input->post('site_key'); //사이트 연동 Key
        $url_after_login = $this->input->post('url_after_login'); //다음페이지 URL
        //log_message("ERROR", "/login/loginapi > site_url : ". $site_url .", userid : ". $userid .", site_key  : ". $site_key .", url_after_login  : ". $url_after_login);
		//echo "site_url : ". $site_url .", userid : ". $userid .", site_key  : ". $site_key .", url_after_login  : ". $url_after_login ."<br>";
        $result = "";
        if($userid == ""){
            $result = '사이트 연동 ID 비어있습니다.';
		}elseif($site_key == ""){
            $result = '사이트 연동 Key가 비어있습니다.';
		}else{
			$userinfo = $this->Member_model->get_by_userid($userid, $userselect);
			if(!element('mem_id', $userinfo)){
				$result = '사이트 연동 ID가 일치하지 않습니다.';
			}elseif (element('mem_site_key', $userinfo) != $site_key){
				$result = '사이트 연동 Key가 일치하지 않습니다.';
				//$result .= '\n'.'userid : '. $userid .'\n'.'site_key : '. $site_key .'\n'.'mem_site_key:'. element('mem_site_key', $userinfo) .')';
			}elseif (element('mem_denied', $userinfo)) {
				$result = '회원님의 아이디는 접근이 금지된 아이디입니다.';
			}elseif ($this->cbconfig->item('use_register_email_auth') && ! element('mem_email_cert', $userinfo)) {
				$result = '회원님은 아직 이메일 인증을 받지 않으셨습니다.';
			}
		}
		//echo "username : ". $username .", mem_username  : ". element('mem_username', $userinfo) ."<br>";
		//echo "result  : ". $result ."<br>";

        if(empty($result)) {
            //로그 기록
			$this->member->update_login_log(element('mem_id', $userinfo), $userid, 1, '로그인 성공 ('. $site_url .')');
            //로그인 세션처리
			$this->session->set_userdata(
                'mem_id',
                element('mem_id', $userinfo)
            );
			//다음페이지 설정
			if(empty($url_after_login)){
                $url_after_login = site_url();
            }else{
				$url_after_login = urldecode($url_after_login);
			}
			//echo "url_after_login  : ". $url_after_login ."<br>";
            //log_message("ERROR", "/login/loginapi > url_after_login : ". $url_after_login .", ss_mem_id : ". $this->session->userdata['mem_id']);
            redirect($url_after_login);
        } else {
            //로그 기록
			$this->member->update_login_log(element('mem_id', $userinfo), $userid, 1, $result .'('. $site_url .')');
			echo "<script>";
			echo "  alert('". $result ."');";
			if($userid == ""){
				//echo "  opener.location.reload();";
			}
			echo "  window.close();";
			echo "</script>";
        }
    }

    //마트톡에서 넘어온 미처리 받는 부분 2021-11-04
    public function movetoapi(){

        $site_url = $this->input->post('site_url'); //로그인 사이트
        $mem_userid = $this->input->post('mem_userid'); //사이트 연동 ID
        $mem_level = $this->input->post('mem_level'); //레벨
        $site_key = $this->input->post('mem_site_key'); //사이트 연동 Key
        $url_after_api = $this->input->post('url_after_api'); //다음페이지 URL
        $usmsid = $this->input->post('usmsid');

        if($mem_level>=100){
            $mem_userid = "dhn";
        }else{
            echo "<script>";
            echo "  alert('허용된 사용자가 아닙니다');";

            echo "  window.close();";
            echo "</script>";
        }
        // log_message("ERROR", "/login/loginapi > site_url : ". $site_url .", mem_userid : ". $mem_userid .", url_after_api  : ". $url_after_api);
		//echo "site_url : ". $site_url .", userid : ". $userid .", site_key  : ". $site_key .", url_after_login  : ". $url_after_login ."<br>";
        $result = "";
        $userinfo = $this->Member_model->get_by_userid($mem_userid, $userselect);
        // log_message("ERROR", "/login/loginapi > userinfo : ". $userinfo);
        if($mem_userid == ""){
            $result = '로그 상태입니다.';
		}else if(!element('mem_id', $userinfo)){
            $result = '사이트 연동 ID가 일치하지 않습니다.';
		}elseif (element('mem_site_key', $userinfo) != $site_key){
            $result = '사이트 연동 Key가 일치하지 않습니다.';
        }else if (element('mem_denied', $userinfo)) {
            $result = '회원님의 아이디는 접근이 금지된 아이디입니다.';
        }else if ($this->cbconfig->item('use_register_email_auth') && ! element('mem_email_cert', $userinfo)) {
            $result = '회원님은 아직 이메일 인증을 받지 않으셨습니다.';
        }
		//echo "username : ". $username .", mem_username  : ". element('mem_username', $userinfo) ."<br>";
		//echo "result  : ". $result ."<br>";

        if(empty($result)) {
            //로그 기록
			$this->member->update_login_log(element('mem_id', $userinfo), $mem_userid, 1, '로그인 성공 ('. $site_url .')');
            //로그인 세션처리
			$this->session->set_userdata(
                'mem_id',
                element('mem_id', $userinfo)
            );
			//다음페이지 설정
			if(empty($url_after_api)){
                $url_after_api = site_url();
            }else{
				$url_after_api = urldecode($url_after_api);
			}
			//echo "url_after_login  : ". $url_after_login ."<br>";
            // log_message("ERROR", "/login/loginapi > url_after_login : ". $url_after_api .", ss_mem_id : ". $this->session->userdata['mem_id']);
            $sql = "SELECT count(*) as cnt FROM cb_redbank_data where usmsid ='".$usmsid."' ";
            $cnt = $this->db->query($sql)->row()->cnt;
            if($cnt==0){
                $datain = array();
                $datain["usmsid"] =$usmsid;
                $datain["sender"] =$this->input->post('sender');
                $datain["money"] =$this->input->post('money');
                $datain["remain"] =0;
                $datain["oid"] =0;
                $datain["bank_num"] =$this->input->post('bank_num');
                $datain["plus"] ='1';
                $datain["mem_id"] =0;
                $datain["state"] ='F';
                $datain["useyn"] ='Y';
                $datain["cre_date"] =$this->input->post('cre_date');

                $this->db->insert("cb_redbank_data", $datain);
                redirect($url_after_api);
            }else{
                echo "<script>";
    			echo "  alert('이미 등록된 미처리 내용입니다');";

    			echo "  window.close();";
    			echo "</script>";
            }



        } else {
            //로그 기록
			$this->member->update_login_log(element('mem_id', $userinfo), $mem_userid, 1, $result .'('. $site_url .')');
			echo "<script>";
			echo "  alert('". $result ."');";
			if($userid == ""){
				//echo "  opener.location.reload();";
			}
			echo "  window.close();";
			echo "</script>";
        }

    }

    public function emsg_login(){
		$mem_eve_yn = $this->member->item('mem_eve_yn'); //플친몰 연동여부
		//echo "mem_mall_yn : ". $mem_mall_yn ."<br>";
		if($mem_eve_yn == "N"){ //플친몰 연동여부
			//쇼핑몰 안내 페이지 이동
			redirect("/home");
		}else{
			//쇼핑몰 이동
			$view = array();
			$view["login_url"] = "http://speedmobile.kr/login/loginapi"; //URL
			$view["userid"] = $this->member->item("mem_eve_id"); //ID
			$view["site_key"] = $this->member->item("mem_eve_key"); //Key
            $view["psd_url"] = $this->input->get("psd_url"); //Key
            $view["pcd_url"] = $this->input->get("pcd_url"); //Key
            if(!empty($view["psd_url"])){
                $view["url_after_login"] = "/dhnbiz/sender/send/utalk_v1?s_psd_url=".$view["psd_url"]; //다음페이지 URL
            }else if(!empty($view["pcd_url"])){
                $view["url_after_login"] = "/dhnbiz/sender/send/utalk_v1?s_pcd_url=".$view["pcd_url"]; //다음페이지 URL
            }else{
                $view["url_after_login"] = "/dhnbiz/sender/send/utalk_v1"; //다음페이지 URL
            }




            log_message("ERROR", "/login/emsg_login > psd_url :".$view["psd_url"]);
			//$view["userid"] = "12121";
			//$view["username"] = "jhkhk";
			$layoutconfig = array(
				'path' => 'login',
				'layout' => 'login',
				'skin' => 'site_login',
				'layout_dir' => $layout_dir,
				'mobile_layout_dir' => $mobile_layout_dir,
				'use_sidebar' => $use_sidebar,
				'use_mobile_sidebar' => $use_mobile_sidebar,
				'skin_dir' => $skin_dir,
				'mobile_skin_dir' => $mobile_skin_dir,
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
    }

	//쇼핑몰 바로가기 (플친몰 자동 로그인)
	public function pfmall_login(){
		$mem_contract_type = $this->member->item('mem_contract_type'); //계약형태 (S.스텐다드, P.프리미엄)
		//echo "mem_contract_type : ". $mem_contract_type ."<br>";
		if($mem_contract_type == "S"){ //계약형태 (S.스텐다드, P.프리미엄)
			//쇼핑몰 안내 페이지 이동
			redirect("/mall/info");
		}else{
			//쇼핑몰 이동
			$view = array();
			$view["login_url"] = "http://pfmall.co.kr/login/loginapi"; //URL
			$view["userid"] = $this->member->item("mem_mall_id"); //플친몰 ID
			$view["site_key"] = $this->member->item("mem_mall_key"); //플친몰 Key
			$view["url_after_login"] = "/"; //다음페이지 URL
			//$view["userid"] = "12121";
			//$view["username"] = "jhkhk";
			$layoutconfig = array(
				'path' => 'login',
				'layout' => 'layout_login',
				'skin' => 'site_login',
				'layout_dir' => $layout_dir,
				'mobile_layout_dir' => $mobile_layout_dir,
				'use_sidebar' => $use_sidebar,
				'use_mobile_sidebar' => $use_mobile_sidebar,
				'skin_dir' => $skin_dir,
				'mobile_skin_dir' => $mobile_skin_dir,
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
    }

    public function nicedocu()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

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
            'path' => 'login',
            'layout' => 'empty2',
            'skin' => 'nicedocu',
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

}
