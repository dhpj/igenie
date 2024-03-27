<?php
class Select_coupon extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz_dhn');
    
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
        log_message("ERROR", "st Coupon : ".$iscoupon);
        $view = array();
        $view['view'] = array();
        if($tmp_profile && $tmp_code && $iscoupon == "undefined") {
            $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
        } else if($tmp_profile && $tmp_code && $view['view']['iscoupon'] !="undefined") {
            $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback, cc.* from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' inner join cb_coupon cc on cc.cc_tpl_id = a.tpl_id and cc.cc_idx = '$iscoupon' where /*tpl_mem_id=? and */tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
        }
        $sms_callback = $view['rs']->spf_sms_callback;
        
        $view['mem1'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);

        // 2019.08.08 쿠폰 url 기본 주소
        $view['coupon_url'] = base_url()."short_url/coupon/";
        
        if(empty($sms_callback))
            $sms_callback = $this->member->item("mem_phone");
            $sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$sms_callback."' and use_flag = 'Y' and auth_flag = 'O'";
        
        $view['sendtelauth'] = $this->db->query($sql)->row()->cnt;
        
        $this->load->view('biz/dhnsender/send/select_coupon',$view);
    }
}
?>