<?php
class Select_profile_wide extends CB_Controller {
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
        $profileKey = $this->input->post('profileKey');
        
        $view = array();
        $view['view'] = array();
        // 20190107 이수환 수정 mem_linkbtn_name 조회구문에 추가
        /* 		if(!$profileKey) {
         $view['rs'] = $this->db->query("select * from cb_wt_send_profile_dhn where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1", array($this->member->item('mem_id')))->row();
         } else {
         $view['rs'] = $this->db->query("select * from cb_wt_send_profile_dhn where spf_key=? and spf_appr_id<>'' and spf_use='Y'  and spf_status='A'", array($profileKey))->row();
         }  */
        if(!$profileKey) {
            $view['rs'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from cb_wt_send_profile_dhn where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();
        } else {
            $view['rs'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from cb_wt_send_profile_dhn where spf_key=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A') as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($profileKey))->row();
        }
        $sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$view['rs']->spf_sms_callback."' and use_flag = 'Y' and auth_flag = 'O'";
        log_message("ERROR", "0000000000000000000000".$sql);
        $view['sendtelauth'] = $this->db->query($sql)->row()->cnt;
        
        $view['mem'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        
        // 20190315 이수환 추가 Profile들 조회 발신 아이디 Selectbox값.
        $sqlProfile = "
			select *
			from cb_wt_send_profile_dhn
			where spf_key<>'' and spf_use='Y' and spf_mem_id=".$this->member->item('mem_id')." order by spf_datetime desc;";
        log_message("ERROR", "0000000000000000000000".$sqlProfile);
        $view['profile'] = $this->db->query($sqlProfile)->result();
        
        $this->load->view('biz/sender/send/select_profile_wide',$view);
        
        
    }
}
?>