<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * member table �쓣 愿�由ы븯�뒗 class �엯�땲�떎.
 */
class Member extends CI_Controller
{
    
    private $CI;
    
    private $mb;
    
    private $member_group;
    
    
    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->model( array('Member_model'));
        $this->CI->load->helper( array('array'));
    }
    
    
    /**
     * �젒�냽�븳 �쑀��媛� �쉶�썝�씤吏� �븘�땶吏�瑜� �뙋�떒�빀�땲�떎
     */
    public function is_member()
    {
        if ($this->CI->session->userdata('mem_id')) {
            return $this->CI->session->userdata('mem_id');
        } else {
            return false;
        }
    }
    
    
    /**
     * �젒�냽�븳 �쑀��媛� 愿�由ъ옄�씤吏� �븘�땶吏�瑜� �뙋�떒�빀�땲�떎
     */
    public function is_admin($check = array())
    {
        if ($this->item('mem_is_admin')) {
            return 'super';
        }
        if (element('group_id', $check)) {
            $this->CI->load->library('board_group');
            $is_group_admin = $this->CI->board_group->is_admin(element('group_id', $check));
            if ($is_group_admin) {
                return 'group';
            }
        }
        if (element('board_id', $check)) {
            $this->CI->load->library('board');
            $is_board_admin = $this->CI->board->is_admin(element('board_id', $check));
            if ($is_board_admin) {
                return 'board';
            }
        }
        return false;
    }
    
    
    /**
     * member, member_extra_vars, member_meta �뀒�씠釉붿뿉�꽌 �젙蹂대�� 媛��졇�샃�땲�떎
     */
    public function get_member()
    {
        if ($this->is_member()) {
            if (empty($this->mb)) {
                $member = $this->CI->Member_model->get_by_memid($this->is_member());
                $extras = $this->get_all_extras(element('mem_id', $member));
                if (is_array($extras)) {
                    $member = array_merge($member, $extras);
                }
                $metas = $this->get_all_meta(element('mem_id', $member));
                if (is_array($metas)) {
                    $member = array_merge($member, $metas);
                }
                $member['social'] = $this->get_all_social_meta(element('mem_id', $member));
                $member['paymemt_bank_info'] = $this->CI->Member_model->payment_bank_info($this->is_member());
                $member['min_charge_amt'] = $this->CI->Member_model->min_charge_amt($this->is_member());
                $paymentMethod = $this->CI->Member_model->payment_method_info($this->is_member());
                $member['payment_bank'] = $paymentMethod->mem_payment_bank;
                $member['payment_card'] = $paymentMethod->mem_payment_card;
                $member['payment_realtime'] = $paymentMethod->mem_payment_realtime;
                $member['payment_vbank'] = $paymentMethod->mem_payment_vbank;
                $member['payment_phone'] = $paymentMethod->mem_payment_phone;
                $this->mb = $member;
                //log_message('ERROR',$member['paymemt_bank_info']);
            }
            return $this->mb;
        } else {
            return false;
        }
    }
    
    
    /**
     * get_member �뿉�꽌 媛��졇�삩 �뜲�씠�꽣�쓽 item �쓣 蹂댁뿬以띾땲�떎
     */
    public function item($column = '')
    {
        if (empty($column)) {
            return false;
        }
        if (empty($this->mb)) {
            $this->get_member();
        }
        if (empty($this->mb)) {
            return false;
        }
        $member = $this->mb;
        
        return isset($member[$column]) ? $member[$column] : false;
    }
    
    /**
     * get_member �뿉�꽌 媛��졇�삩 �뜲�씠�꽣�쓽 item �쓣 蹂댁뿬以띾땲�떎
     */
    public function socialitem($column = '')
    {
        if (empty($column)) {
            return false;
        }
        if (empty($this->mb)) {
            $this->get_member();
        }
        if (empty($this->mb)) {
            return false;
        }
        $member = $this->mb;
        
        return isset($member['social']) && isset($member['social'][$column]) ? $member['social'][$column] : false;
    }
    
    
    /**
     * �쉶�썝�씠 �냽�븳 洹몃９ �젙蹂대�� 媛��졇�샃�땲�떎
     */
    public function group()
    {
        if (empty($this->member_group)) {
            $where = array(
                'mem_id' => $this->item('mem_id'),
            );
            $this->CI->load->model('Member_group_member_model');
            $this->member_group = $this->CI->Member_group_member_model->get('', '', $where, '', 0, 'mgm_id', 'ASC');
        }
        return $this->member_group;
    }
    
    
    /**
     * member_extra_vars �뀒�씠釉붿뿉�꽌 媛��졇�샃�땲�떎
     */
    public function get_all_extras($mem_id = 0)
    {
        if (empty($mem_id)) {
            return false;
        }
        
        $this->CI->load->model('Member_extra_vars_model');
        $result = $this->CI->Member_extra_vars_model->get_all_meta($mem_id);
        return $result;
    }
    
    
    /**
     * member_meta �뀒�씠釉붿뿉�꽌 媛��졇�샃�땲�떎
     */
    public function get_all_meta($mem_id = 0)
    {
        $mem_id = (int) $mem_id;
        if (empty($mem_id) OR $mem_id < 1) {
            return false;
        }
        
        $this->CI->load->model('Member_meta_model');
        $result = $this->CI->Member_meta_model->get_all_meta($mem_id);
        return $result;
    }
    
    
    /**
     * social_meta �뀒�씠釉붿뿉�꽌 媛��졇�샃�땲�떎
     */
    public function get_all_social_meta($mem_id = 0)
    {
        $mem_id = (int) $mem_id;
        if (empty($mem_id) OR $mem_id < 1) {
            return false;
        }
        if ($this->CI->db->table_exists('social_meta') === false) {
            return;
        }
        
        $this->CI->load->model('Social_meta_model');
        $result = $this->CI->Social_meta_model->get_all_meta($mem_id);
        return $result;
    }
    
    /**
     * 濡쒓렇�씤 湲곕줉�쓣 �궓源곷땲�떎
     */
    public function update_login_log($mem_id= 0, $userid = '', $success= 0, $reason = '')
    {
        //log_message("ERROR", "update_login_log > : ".$this->CI->agent->agent_string());
        $success = $success ? 1 : 0;
        $mem_id = (int) $mem_id ? (int) $mem_id : 0;
        $reason = isset($reason) ? $reason : '';
        $referer = $this->CI->input->get_post('url', null, '');
        $loginlog = array(
            'mll_success' => $success,
            'mem_id' => $mem_id,
            'mll_userid' => $userid,
            'mll_datetime' => cdate('Y-m-d H:i:s'),
            'mll_ip' => $this->CI->input->ip_address(),
            'mll_reason' => $reason,
            'mll_useragent' => $this->CI->agent->agent_string(),
            'mll_url' => current_full_url(),
            'mll_referer' => $referer,
        );
        $this->CI->load->model('Member_login_log_model');
        $this->CI->Member_login_log_model->insert($loginlog);
        
        return true;
    }
    
    /**
     * �쉶�썝�궘�젣 �궓源곷땲�떎
     */
    public function delete_member($mem_id = 0)
    {
        $mem_id = (int) $mem_id;
        if (empty($mem_id) OR $mem_id < 1) {
            return false;
        }
        
        $this->CI->load->model(
            array(
                'Autologin_model', 'Board_admin_model', 'Board_group_admin_model',
                'Cmall_cart_model', 'Cmall_wishlist_model', 'Follow_model',
                'Member_model', 'Member_auth_email_model', 'Member_dormant_model',
                'Member_dormant_notify_model', 'Member_extra_vars_model', 'Member_group_member_model',
                'Member_level_history_model', 'Member_login_log_model', 'Member_meta_model',
                'Member_register_model', 'Notification_model', 'Point_model',
                'Scrap_model', 'Sms_member_model', 'Social_meta_model',
                'Tempsave_model', 'Member_userid_model',
            )
            );
        
        $deletewhere = array(
            'mem_id' => $mem_id,
        );
        $this->CI->Autologin_model->delete_where($deletewhere);
        $this->CI->Board_admin_model->delete_where($deletewhere);
        $this->CI->Board_group_admin_model->delete_where($deletewhere);
        $this->CI->Cmall_cart_model->delete_where($deletewhere);
        $this->CI->Cmall_wishlist_model->delete_where($deletewhere);
        $this->CI->Follow_model->delete_where($deletewhere);
        $this->CI->Member_model->delete_where($deletewhere);
        $this->CI->Member_auth_email_model->delete_where($deletewhere);
        $this->CI->Member_dormant_model->delete_where($deletewhere);
        $this->CI->Member_dormant_notify_model->delete_where($deletewhere);
        $this->CI->Member_extra_vars_model->delete_where($deletewhere);
        $this->CI->Member_group_member_model->delete_where($deletewhere);
        $this->CI->Member_level_history_model->delete_where($deletewhere);
        $this->CI->Member_login_log_model->delete_where($deletewhere);
        $this->CI->Member_meta_model->delete_where($deletewhere);
        $this->CI->Member_register_model->delete_where($deletewhere);
        $this->CI->Notification_model->delete_where($deletewhere);
        $this->CI->Point_model->delete_where($deletewhere);
        $this->CI->Scrap_model->delete_where($deletewhere);
        $this->CI->Sms_member_model->delete_where($deletewhere);
        $this->CI->Social_meta_model->delete_where($deletewhere);
        $this->CI->Tempsave_model->delete_where($deletewhere);
        
        $this->CI->Member_userid_model->update($mem_id, array('mem_status' => 1));
        
        return true;
    }
    
    /**
     * �쑕硫댁쿂由ъ떆 蹂꾨룄�쓽 ���옣�냼濡� �씠�룞�븯�뒗 �봽濡쒖꽭�뒪�엯�땲�떎
     */
    public function archive_to_dormant($mem_id = 0)
    {
        $mem_id = (int) $mem_id;
        if (empty($mem_id) OR $mem_id < 1) {
            return false;
        }
        
        $this->CI->load->model(array('Member_model', 'Member_dormant_model', 'Member_meta_model', 'Member_userid_model'));
        
        $data = $this->CI->Member_model->get_one($mem_id);
        $cleanpoint = (-1) * element('mem_point', $data);
        $point_content = '�쑕硫댄쉶�썝 �쟾�솚�쑝濡� �씤�븳 �룷�씤�듃 珥덇린�솕 (' . cdate('Y-m-d H:i:s') . ')';
        
        if ($this->CI->cbconfig->item('member_dormant_reset_point')) {
            $this->CI->load->library('point');
            $point = $this->CI->point->insert_point(
                $mem_id,
                $cleanpoint,
                $point_content,
                '@member_dormant',
                $mem_id,
                $mem_id . '-' . uniqid('')
                );
        }
        
        $this->CI->Member_dormant_model->insert($data);
        $this->CI->Member_model->delete($mem_id);
        $metadata = array('archived_dormant_datetime' => cdate('Y-m-d H:i:s'));
        $this->CI->Member_meta_model->save($mem_id, $metadata);
        $this->CI->Member_userid_model->update($mem_id, array('mem_status' => 2));
        
        
        return true;
    }
    
    /**
     * �쑕硫댁긽�깭�뿉 �엳�뜕 �쉶�썝�쓣 �썝�옒 �쉶�썝 �뵒鍮꾨줈 蹂듭썝�븯�뒗 �봽濡쒖꽭�뒪�엯�땲�떎
     */
    public function recover_from_dormant($mem_id = 0)
    {
        $mem_id = (int) $mem_id;
        if (empty($mem_id) OR $mem_id < 1) {
            return false;
        }
        
        $this->CI->load->model(array('Member_model', 'Member_dormant_model', 'Member_meta_model', 'Member_userid_model'));
        
        $data = $this->CI->Member_dormant_model->get_one($mem_id);
        $this->CI->Member_model->insert($data);
        $this->CI->Member_dormant_model->delete($mem_id);
        $metadata = array('archived_dormant_datetime' => '');
        $this->CI->Member_meta_model->save($mem_id, $metadata);
        $this->CI->Member_userid_model->update($mem_id, array('mem_status' => 0));
        
        return true;
    }


	/**
     * 자동 로그인 쿠키 생성
     */
	public function autologin_cookie($hash, $url_after_login){
		//log_message("ERROR", "/login/autologin_cookie > hash : ". $hash);

		$cookie_name = 'autologin';
		$cookie_value = $hash;
		$cookie_expire = (60*60*24*30); // 30일간 저장
		//log_message("ERROR", "/autologin/login_profile_key > cookie_name : ". $cookie_name .", cookie_value : ". $cookie_value .", cookie_expire : ". $cookie_expire);

		setcookie($cookie_name, $cookie_value, $cookie_expire, "/"); //30일간 이메일 쿠키저장
		//log_message("ERROR", "/libraries/Member/autologin_cookie > _COOKIE('autologin') : ". $_COOKIE["autologin"]);

		//set_cookie($cookie_name, $cookie_value, $cookie_expire);
		//set_cookie($cookie_name, $cookie_value, $cookie_expire, '.o2omsg.com', '/');
		//$this->input->set_cookie($cookie_name, $cookie_value, $cookie_expire, '.o2omsg.com', '/');
		//set_cookie($cookie_name, $cookie_value, $cookie_expire, ".o2omsg.com", "/");
		//log_message("ERROR", "/autologin/login_profile_key > get_cookie('autologin') : ". get_cookie($cookie_name));

		redirect($url_after_logout, 'refresh');
		return true;
	}
    
}
