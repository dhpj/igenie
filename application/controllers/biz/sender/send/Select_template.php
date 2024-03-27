<?php
class Select_template extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz');
    
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
        $tmp_code = $this->input->post('tmp_code');
        $tmp_profile = $this->input->post('tmp_profile');
        $iscoupon = $this->input->post('iscoupon');
        $view = array();
        $view['view'] = array();
        
        //log_message("ERROR", "this->member->item('mem_id') : ".$this->member->item('mem_id'));
        //log_message("ERROR", "tmp_code : ".$tmp_code);
        //log_message("ERROR", "tmp_profile : ".$tmp_profile);
        
        if($tmp_profile && $tmp_code && $iscoupon == "undefined") {
            $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
        } else if($tmp_profile && $tmp_code && $view['view']['iscoupon'] !="undefined") {
            $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback, cc.* from cb_wt_template a inner join cb_wt_send_profile b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' inner join cb_coupon cc on cc.cc_tpl_id = a.tpl_id and cc.cc_idx = '$iscoupon' where /*tpl_mem_id=? and */tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
        }
        
        $view['rs1'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from cb_wt_send_profile where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();
        
        $sms_callback = $view['rs']->spf_sms_callback;
        
        $view['mem1'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);

        //log_message("ERROR", "st Coupon : ".$view['rs']->tpl_profile_key);
        //log_message("ERROR", "view['rs']->spf_sms_callback : ".$view['rs']->spf_sms_callback);
        
        // 2019.08.08 쿠폰 url 기본 주소
        $view['coupon_url'] = base_url()."short_url/coupon/";
        
        if(empty($sms_callback)) {
            $sms_callback = $this->member->item("mem_phone");
        }
        
        //log_message("ERROR", "this->member->item('mem_phone') : ".$this->member->item("mem_phone"));
         
        $sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$sms_callback."' and use_flag = 'Y' and auth_flag = 'O'";
        
        $view['sendtelauth'] = $this->db->query($sql)->row()->cnt;
        
        $this->load->view('biz/sender/send/select_template',$view);
    }

    
}
?>