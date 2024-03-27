<?php
class Select_profile_v5 extends CB_Controller {
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
        $this->load->library(array('querystring', 'dhnlurl'));
    }

    public function index()
    {
        $profileKey = $this->input->post('profileKey');

        $view = array();
        $view['view'] = array();

		$view['add'] = $this->input->post("add");
		$skin = "select_profile_v5";
		$add = $view['add'];
		if($add != "") $skin .= $add;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > add : ". $add .", skin : ". $skin);

        // 20190107 이수환 수정 mem_linkbtn_name 조회구문에 추가
        /* 		if(!$profileKey) {
         $view['rs'] = $this->db->query("select * from ".config_item('ib_profile')." where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1", array($this->member->item('mem_id')))->row();
         } else {
         $view['rs'] = $this->db->query("select * from ".config_item('ib_profile')." where spf_key=? and spf_appr_id<>'' and spf_use='Y'  and spf_status='A'", array($profileKey))->row();
         }  */
         //ib플래그
        if(!$profileKey) {
            $view['rs'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from ".config_item('ib_profile')." where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();
        } else {
            $view['rs'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from ".config_item('ib_profile')." where spf_key=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A') as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($profileKey))->row();
            // log_message('error', "select A.*, B.mem_linkbtn_name FRom (select * from ".config_item('ib_profile')." where spf_key=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A') as A Left join cb_member B ON A.spf_mem_id = B.mem_id");
        }
        $sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$view['rs']->spf_sms_callback."' and use_flag = 'Y' and auth_flag = 'O'";
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);
        $view['sendtelauth'] = $this->db->query($sql)->row()->cnt;

        $view['mem'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        $view['iscoupon'] = $this->input->post('iscoupon');
        // 20190315 이수환 추가 Profile들 조회 발신 아이디 Selectbox값.
        //ib플래그
        $sqlProfile = "
			select *
			from ".config_item('ib_profile')."
			where spf_key<>'' and spf_use='Y' and spf_mem_id=".$this->member->item('mem_id')." order by spf_datetime desc;";
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sqlProfile);
        $view['profile'] = $this->db->query($sqlProfile)->result();

        $this->load->library('base62');
		$surl_id = cdate('YmdHis');
		$view['short_url'] = $this->base62->encode($mem_id.$surl_id);
		$view['dhnl_url'] = 'http://'.$this->dhnlurl->get_short('http://'. $_SERVER['HTTP_HOST'] .'/at/'.$view['short_url']);
		log_message("ERROR", $_SERVER['REQUEST_URI'] ." > dhnl_url : ". $view['dhnl_url']);
		$view['psd_code'] = $this->input->post('psd_code');
		if($view['psd_code'] != ""){
			//$view['psd_url'] = 'http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $view['psd_code'];
			$view['psd_url'] = 'http://'. $this->dhnlurl->get_short('http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $view['psd_code']);
            log_message("ERROR", $_SERVER['REQUEST_URI'] ." > url_detail : ".'http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $view['psd_code']);
		}else{
			$view['psd_url'] = '';
		}
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > psd_code : ". $view['psd_code']);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > psd_url : ". $view['psd_url']);
		$view['pcd_code'] = $this->input->post('pcd_code');
		$view['pcd_type'] = $this->input->post('pcd_type');
		if($view['pcd_code'] != ""){
			//$view['psd_url'] = 'http://'. $_SERVER['HTTP_HOST'] ."/smart/coupon/". $view['psd_code'];
			$view['pcd_url'] = 'http://'. $this->dhnlurl->get_short('http://'. $_SERVER['HTTP_HOST'] ."/smart/coupon/". $view['pcd_code']);
		}else{
			$view['pcd_url'] = '';
		}

        $this->load->view('biz/dhnsender/send/'. $skin, $view);

    }
}
?>
