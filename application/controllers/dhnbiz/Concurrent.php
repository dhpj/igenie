<?php
class Concurrent extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    
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
 
    }
    
    public function check_expiration_date()
    {
       /*
        * 정액제 사용자들에 대한 만료 일자 사전 공지..
        */ 
        $sql = "select mem_fixed_to_date, mem_id from cb_member cb where cb.mem_pay_type = 'T'";
        $list = $this->db->query($sql)->result_array();
        foreach($list as $r)
        {
            
            if(!empty($r['mem_fixed_to_date'])  && ($r['mem_fixed_to_date'] < cdate('Y-m-d H:i:s',strtotime('+40 day')))) {
                log_message("ERROR", $r['mem_id']."-". $r['mem_fixed_to_date'].cdate('Y-m-d H:i:s',strtotime('+40 day')));
                $msg = array (
                    "from_mem_id" => "3",
                    "title" => "정액제 기간 만료 알림",
                    "msg" => "안녕하세요.<BR> 사용하시는 정액 요금의 만료일이 도래 하여 사전에 알립니다. <BR> 만료 기일을 사전에 점검 하셔서 메세지 발송에 <BR> 차질이 없도록 준비 하시기 바랍니다.<BR> 만료일 : ".$r['mem_fixed_to_date'],
                    "send_date" => date("Y-m-d H:i:s")
                );
                $this->db->insert('message', $msg);
                $msg_id = $this->db->insert_id();
                
                $msg_to = array (
                    "msg_id" => $msg_id,
                    "to_mem_id" => $r['mem_id'],
                    "is_alam" => "Y"
                );
                $this->db->insert('message_receiver', $msg_to);
            }
        }
    }
    
    
    public function migration() {
        $str = "SELECT distinct cm.mem_userid FROM cb_wt_msg_sent a  inner JOIN cb_member cm on a.mst_mem_id = cm.mem_id WHERE a.mst_nas > 0";
        $rs = $this->db->query($str)->result();
        
        foreach ($rs as $r) {
            $ins = "insert into cb_block_number_temp SELECT distinct a.SMS_SENDER, CONCAT('0',substr(a.PHN, (LENGTH(a.phn) -2) * -1)) FROM cb_msg_".$r->mem_userid." a WHERE a.RESULT = 'N' AND a.MESSAGE = '수신거부'";
            $this->db->query($ins);
        }
        
        log_message("ERROR", "The End");
    }
}
?>
