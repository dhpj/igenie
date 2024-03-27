<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_model extends CB_Model
{
    
    /**
     * 테이블명
     */
    public $_table = 'member';
    
    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'mem_id'; // 사용되는 테이블의 프라이머리키
    
    public $search_sfield = '';
    
    function __construct()
    {
        parent::__construct();
    }
    
    
    public function get_by_memid($memid = 0, $select = '')
    {
        $memid = (int) $memid;
        if (empty($memid) OR $memid < 1) {
            return false;
        }
        $where = array('mem_id' => $memid);
        return $this->get_one('', $select, $where);
    }
    
    
    public function get_by_userid($userid = '', $select = '')
    {
        if (empty($userid)) {
            return false;
        }
        $where = array('mem_userid' => $userid);
        return $this->get_one('', $select, $where);
    }
    
    
    public function get_by_email($email = '', $select = '')
    {
        if (empty($email)) {
            return false;
        }
        $where = array('mem_email' => $email);
        return $this->get_one('', $select, $where);
    }
    
    
    public function get_by_both($str = '', $select = '')
    {
        if (empty($str)) {
            return false;
        }
        if ($select) {
            $this->db->select($select);
        }
        $this->db->from($this->_table);
        $this->db->where('mem_userid', $str);
        $this->db->or_where('mem_email', $str);
        $result = $this->db->get();
        return $result->row_array();
    }
    
    
    public function get_superadmin_list($select='')
    {
        $where = array(
            'mem_is_admin' => 1,
            'mem_denied' => 0,
        );
        $result = $this->get('', $select, $where);
        
        return $result;
    }
    
    
    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $join = array();
        if (isset($where['mgr_id'])) {
            $select = 'member.*';
            $join[] = array('table' => 'member_group_member', 'on' => 'member.mem_id = member_group_member.mem_id', 'type' => 'left');
        }
        $result = $this->_get_list_common($select = '', $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        
        return $result;
    }
    
    
    public function get_register_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';
        
        $this->db->select('count(*) as cnt, left(mem_register_datetime, ' . $left . ') as day ', false);
        $this->db->where('left(mem_register_datetime, 10) >=', $start_date);
        $this->db->where('left(mem_register_datetime, 10) <=', $end_date);
        $this->db->where('mem_denied', 0);
        $this->db->group_by('day');
        $this->db->order_by('mem_register_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();
        
        return $result;
    }
    
    // 무통장 입금계좌 번호 정보 조
    public function payment_bank_info($memid = 0)
    {
        
        $memid = (int) $memid;
        if (empty($memid) OR $memid < 1) {
            return false;
        }
        //log_message('ERROR', 'MEM_ID = '.$memid );
        $sql = "select (case when length((select mem_payment_desc from cb_member cm where cm.mem_id =  cmr.mrg_recommend_mem_id))< 1 then
                               (select mem_payment_desc from cb_member cm where cm.mem_id = 3)
                             else
                               (select mem_payment_desc from cb_member cm where cm.mem_id =  cmr.mrg_recommend_mem_id)
                        end ) as payment_bank_info
                  from cb_member_register cmr where cmr.mem_id = ".$memid;
        //log_message('ERROR', 'QUERY = '.$sql );
        //$result = '';
        $result = $this->db->query($sql);
        //log_message('ERROR', 'result = '.$result->row()->payment_bank_info );
        return $result->row()->payment_bank_info;
    }
    
    // 무통장 입금계좌 번호 정보 조
    public function min_charge_amt($memid = 0)
    {
        
        $memid = (int) $memid;
        if (empty($memid) OR $memid < 1) {
            return false;
        }
        //log_message('ERROR', 'MEM_ID = '.$memid );
        $sql = "select (case when (select mem_min_charge_amt from cb_member cm where cm.mem_id =  cmr.mrg_recommend_mem_id) is null then
                               (select mem_min_charge_amt from cb_member cm where cm.mem_id = 3)
                             else
                               (select mem_min_charge_amt from cb_member cm where cm.mem_id =  cmr.mrg_recommend_mem_id)
                        end ) as mem_min_charge_amt
                  from cb_member_register cmr where cmr.mem_id = ".$memid;
        //log_message('ERROR', 'QUERY = '.$sql );
        //$result = '';
        $result = $this->db->query($sql);
        //log_message('ERROR', 'result = '.$result->row()->payment_bank_info );
        return $result->row()->mem_min_charge_amt;
    }
    
    public function payment_method_info($memid = 0)
    {
        
        $memid = (int) $memid;
        if (empty($memid) OR $memid < 1) {
            return false;
        }
        //log_message('ERROR', 'MEM_ID = '.$memid );
        $sql = "SELECT  cm.mem_payment_bank,
                        cm.mem_payment_card,
                        cm.mem_payment_realtime,
                        cm.mem_payment_vbank,
                        cm.mem_payment_phone
                   FROM cb_member_register cmr
                            INNER JOIN
                        cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                  where cmr.mem_id = ".$memid;
        //log_message('ERROR', 'QUERY = '.$sql );
        //$result = '';
        $result = $this->db->query($sql);
        //log_message('ERROR', 'result = '.$result->row()->payment_bank_info );
        return $result->row();
    }
    
    //박하원 2020-07-02 추가
    public function get_by_profile_key($profile_key = '', $select = '')
    {
        if (empty($profile_key)) {
            return false;
        }
        //$where = array('mem_profile_key' => $profile_key);
        //return $this->get_one('', $select, $where);
		$sql = "SELECT  ". $select ."
		FROM cb_member cm
		LEFT JOIN cb_wt_send_profile_dhn sp ON cm.mem_id = sp.spf_mem_id
		WHERE sp.spf_key = '". $profile_key ."' ";
        //log_message('ERROR', '/models/Member_model/get_by_profile_key > sql : '. $sql);
        $result = $this->db->query($sql)->row();
		return $result;
    }

	//박하원 2020-07-03 추가
	public function update_login_log($mem_id= 0, $userid = '', $success= 0, $reason = '', $useragent = '', $referer = '')
    {
        $success = $success ? 1 : 0;
        $mem_id = (int) $mem_id ? (int) $mem_id : 0;
        $reason = isset($reason) ? $reason : '';
        $useragent = ($useragent == "") ? "-" : $useragent;
        $loginlog = array(
            'mll_success' => $success,
            'mem_id' => $mem_id,
            'mll_userid' => $userid,
            'mll_datetime' => cdate('Y-m-d H:i:s'),
            'mll_ip' => $_SERVER['REMOTE_ADDR'],
            'mll_reason' => $reason,
            'mll_useragent' => $useragent,
            'mll_url' => current_full_url(),
            'mll_referer' => $referer,
        );
        $this->load->model('Member_login_log_model');
        $this->Member_login_log_model->insert($loginlog);
        return true;
    }
    
}
