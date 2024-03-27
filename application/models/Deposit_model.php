<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Deposit model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Deposit_model extends CB_Model
{

    /**
     * 테이블명
     */
    public $_table = 'deposit';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'dep_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function insert($data=FALSE)
    {
        if ($data !== FALSE) {
            $return = $this->db->insert($this->_table, $data);
            $respose = json_encode($this->send_bankinfor($data));

            $res = explode(';', str_replace('"', '', $respose));
            //log_message("ERROR", 'Redbanking 요청 결과 : '.$res[0]);
            if( $res[0] == '2') {

                $this->load->library(array('depositlib'));

                $updatedata['dep_deposit_datetime'] = cdate('Y-m-d H:i:s');
                $updatedata['dep_cash'] = $data['dep_cash_request'];
                $updatedata['dep_deposit'] = $data['dep_cash_request'];
                $updatedata['dep_deposit_sum'] = $data['dep_cash_request'];
                $updatedata['dep_status'] = 1;
                $updatedata['dep_admin_memo'] = '자동입금처리';
                $updatedata['status'] = 'OK';

                $this->db->where('dep_id', $data['dep_id']);
                $this->db->update('deposit',$updatedata );
                $this->depositlib->update_member_deposit($data['mem_id']);

                //$this->deposit_appr($data['mem_id'], $data['dep_id']);

                $this->depositlib->alarm('approve_bank_to_deposit', $data['dep_id']);
            }

            return $return;
        } else {
            return FALSE;
        }
    }

    public function send_bankinfor($data) {
        if($this->config->item('base_url') == 'http://igenie.co.kr/') {
            $url = "http://www.redbanking.com/redbank.php";
            $postfields = array(
                "_bank_num"=>'52204693604037',
                "_sender"=>$data['mem_realname'],
                "_money"=>$data['dep_cash_request'],
                "_oid"=>$data['dep_id'],
                "_customer"=>'sjk4556',
                "_encode"=>'UTF-8',
                "_mid"=>$data['mem_id'],
                "v"=>'2'
            );
            //log_message("ERROR", 'Redbanking 등록 : '.$data['dep_id']);
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => array("Content-Type:multipart/form-data"),
                CURLOPT_POSTFIELDS => $postfields,
                //CURLOPT_INFILESIZE => $filesize,
                CURLOPT_RETURNTRANSFER => true,
                //CURLOPT_USERAGENT => $this->agent,
                CURLOPT_REFERER => "",
                CURLOPT_TIMEOUT => 10
                //CURLOPT_SSLVERSION => 3,			// SSL 버젼 (https 접속시에 필요)
                //CURLOPT_SSL_VERIFYPEER => false	// 인증서 체크같은데 true 시 안되는 경우가 많다. default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
            ); // cURL options
            curl_setopt_array($ch, $options);
            $buffer = curl_exec ($ch);
            //log_message("ERROR", $buffer);
            $cinfo = curl_getinfo($ch);
            curl_close($ch);
            if ($cinfo['http_code'] != 200) { return $buffer; } else { return $buffer; }
        }
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR', $exists = '', $pluswhere = '')
    {
        //log_message("ERROR", "QUERY:".$exists);
        $select = 'deposit.*, member.mem_id, member.mem_userid, member.mem_username, member.mem_nickname, member.mem_is_admin, member.mem_icon, member.mem_point, member.mem_deposit';
        $join[] = array('table' => 'member', 'on' => 'deposit.mem_id = member.mem_id', 'type' => 'left');
        $result = $this->ex_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop, $exists, $pluswhere);
        return $result;
    }


    public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR', $pluswhere = '')
    {
        $result = $this->_get_list_common($select = '', $join = '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop, $pluswhere);
        return $result;
    }

    public function ex_get_list_common($select = '', $join = '', $limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR', $exists = '', $pluswhere = '')
    {

        if (empty($findex) OR ! in_array($findex, $this->allow_order_field)) {
            $findex = $this->primary_key;
        }

        $forder = (strtoupper($forder) === 'ASC') ? 'ASC' : 'DESC';
        $sop = (strtoupper($sop) === 'AND') ? 'AND' : 'OR';

        $count_by_where = array();
        $search_where = array();
        $search_like = array();
        $search_or_like = array();
        if ($sfield && is_array($sfield)) {
            foreach ($sfield as $skey => $sval) {
                $ssf = $sval;
                if ($skeyword && $ssf && in_array($ssf, $this->allow_search_field)) {
                    if (in_array($ssf, $this->search_field_equal)) {
                        $search_where[$ssf] = $skeyword;
                    } else {
                        $swordarray = explode(' ', $skeyword);
                        foreach ($swordarray as $str) {
                            if (empty($ssf)) {
                                continue;
                            }
                            if ($sop === 'AND') {
                                $search_like[] = array($ssf => $str);
                            } else {
                                $search_or_like[] = array($ssf => $str);
                            }
                        }
                    }
                }
            }
        } else {
            $ssf = $sfield;
            if ($skeyword && $ssf && in_array($ssf, $this->allow_search_field)) {
                if (in_array($ssf, $this->search_field_equal)) {
                    $search_where[$ssf] = $skeyword;
                } else {
                    $swordarray = explode(' ', $skeyword);
                    foreach ($swordarray as $str) {
                        if (empty($ssf)) {
                            continue;
                        }
                        if ($sop === 'AND') {
                            $search_like[] = array($ssf => $str);
                        } else {
                            $search_or_like[] = array($ssf => $str);
                        }
                    }
                }
            }
        }

        if ($select) {
            $this->db->select($select);
        }
        $this->db->from($this->_table);
        if ( ! empty($join['table']) && ! empty($join['on'])) {
            if (empty($join['type'])) {
                $join['type'] = 'left';
            }
            $this->db->join($join['table'], $join['on'], $join['type']);
        } elseif (is_array($join)) {
            foreach ($join as $jkey => $jval) {
                if ( ! empty($jval['table']) && ! empty($jval['on'])) {
                    if (empty($jval['type'])) {
                        $jval['type'] = 'left';
                    }
                    $this->db->join($jval['table'], $jval['on'], $jval['type']);
                }
            }
        }

        if ($where) {
            $this->db->where($where);
        }
        if ($pluswhere) {
            $this->db->where($pluswhere);
            // log_message("ERROR", "pluswhere:".$pluswhere);
        }

        //log_message("ERROR", "QUERY:".$exists);
        if ($exists) {
            log_message("ERROR", "QUERY:".$exists);
            $this->db->where($exists);
        }

        if ($search_where) {
            $this->db->where($search_where);
        }
        if ($like) {
            $this->db->like($like);
        }
        if ($search_like) {
            foreach ($search_like as $item) {
                foreach ($item as $skey => $sval) {
                    $this->db->like($skey, $sval);
                }
            }
        }
        if ($search_or_like) {
            $this->db->group_start();
            foreach ($search_or_like as $item) {
                foreach ($item as $skey => $sval) {
                    $this->db->or_like($skey, $sval);
                }
            }
            $this->db->group_end();
        }
        if ($count_by_where) {
            $this->db->where($count_by_where);
        }

        $this->db->order_by($findex, $forder);
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        //log_message("ERROR", "QUERY:".$this->db->get_where());
        $qry = $this->db->get();

        $result['list'] = $qry->result_array();

        $this->db->select('count(*) as rownum');
        $this->db->from($this->_table);
        if ( ! empty($join['table']) && ! empty($join['on'])) {
            if (empty($join['type'])) {
                $join['type'] = 'left';
            }
            $this->db->join($join['table'], $join['on'], $join['type']);
        } elseif (is_array($join)) {
            foreach ($join as $jkey => $jval) {
                if ( ! empty($jval['table']) && ! empty($jval['on'])) {
                    if (empty($jval['type'])) {
                        $jval['type'] = 'left';
                    }
                    $this->db->join($jval['table'], $jval['on'], $jval['type']);
                }
            }
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($pluswhere) {
            $this->db->where($pluswhere);
            // log_message("ERROR", "pluswhere:".$pluswhere);
        }

        if ($exists) {
            //log_message("ERROR", "QUERY:".$exists);
            $this->db->where($exists);
        }

        if ($search_where) {
            $this->db->where($search_where);
        }
        if ($like) {
            $this->db->like($like);
        }
        if ($search_like) {
            foreach ($search_like as $item) {
                foreach ($item as $skey => $sval) {
                    $this->db->like($skey, $sval);
                }
            }
        }
        if ($search_or_like) {
            $this->db->group_start();
            foreach ($search_or_like as $item) {
                foreach ($item as $skey => $sval) {
                    $this->db->or_like($skey, $sval);
                }
            }
            $this->db->group_end();
        }
        if ($count_by_where) {
            $this->db->where($count_by_where);
        }
        $qry = $this->db->get();
        $rows = $qry->row_array();
        $result['total_rows'] = $rows['rownum'];

        return $result;
    }

    public function get_deposit_sum($mem_id = 0)
    {
        $mem_id = (int) $mem_id;
        if (empty($mem_id) OR $mem_id < 1) {
            return 0;
        }
        $this->db->select_sum('dep_deposit');
        $this->db->where(array('mem_id' => $mem_id, 'dep_status' => 1));
        $result = $this->db->get($this->_table);
        $sum = $result->row_array();

        return isset($sum['dep_deposit']) ? $sum['dep_deposit'] : 0;
    }


    public function get_graph_count($type = 'd', $start_date = '', $end_date = '', $where = '')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);

        $this->db->select_sum('dep_deposit');
        $this->db->select_sum('dep_cash');
        $this->db->select_sum('dep_point');
        $this->db->select('count(*) as cnt, left(dep_deposit_datetime, ' . $left . ') as day ', false);
        $this->db->where('dep_deposit_datetime >=', $start_date . ' 00:00:00');
        $this->db->where('dep_deposit_datetime <=', $end_date . ' 23:59:59');
        $this->db->where('dep_status', 1);
        if ($where) {
            $this->db->where($where);
        }
        $this->db->group_by(array('day'));
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }


    public function get_graph_paycount($type = 'd', $start_date = '', $end_date = '', $where = '', $orderby = '')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        if (empty($orderby)) {
            $orderby = 'cnt desc';
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);

        $this->db->select_sum('dep_deposit');
        $this->db->select_sum('dep_cash');
        $this->db->select_sum('dep_point');
        $this->db->select('count(*) as cnt, mem_id', false);
        $this->db->where('dep_status', 1);
        if ($where) {
            $this->db->where($where);
        }
        $this->db->where('dep_deposit_datetime >=', $start_date . ' 00:00:00');
        $this->db->where('dep_deposit_datetime <=', $end_date . ' 23:59:59');
        $this->db->group_by(array('mem_id'));
        $this->db->order_by($orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }
}
