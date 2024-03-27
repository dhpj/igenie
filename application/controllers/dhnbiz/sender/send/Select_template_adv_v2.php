<?php
class Select_template_adv_v2 extends CB_Controller {
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

        $tmp_code = $this->input->post('tmp_code');
		$tmp_profile = $this->input->post('tmp_profile');
        //ib플래그 + 그룹추가
        $tmp_profile2 = $this->input->post('tmp_profile2');
		$iscoupon = $this->input->post('iscoupon');
		$view = array();
		$view['view'] = array();
		$save_msg_id = $this->input->post('save_msg_id'); //알림톡 저장 일련번호
		$mem_id = $this->member->item("mem_id"); //회원번호
		$tmp_flag = $this->input->post('tmp_flag'); //템플릿구분
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_code : ". $tmp_code .", tmp_profile : ". $tmp_profile .", save_msg_id : ".$save_msg_id);
		if(!empty($save_msg_id)) {
            $sql = "select * from cb_alim_ad_msg where id = ".$save_msg_id;
            $view['savemsg'] = $this->db->query($sql)->row();

            $tmp_code = $view['savemsg']->temp_id;
            //log_message("ERROR", "tmp_code : ".$tmp_code);

            $sql = "select * from cb_alim_ad_msg_var where adv_idx = ".$save_msg_id." order by id ";
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
            $view['varvalue'] = $this->db->query($sql)->result();
        } else if($tmp_flag == "temp"){ //임시저장 내용 가져오기
			//임시저장된 알림톡 변수내용 조회
			$sql = "
			SELECT *
			FROM cb_alim_ad_msg_var
			WHERE adv_idx = (SELECT MAX(id) FROM cb_alim_ad_msg WHERE mem_id = '". $mem_id ."' AND use_yn = 'N')
			ORDER BY id ASC ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
			$view['varvalue'] = $this->db->query($sql)->result();
		}
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_profile : ".$tmp_profile);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_profile2 : ".$tmp_profile2);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_code : ".$tmp_code);
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > iscoupon : ".$iscoupon);
        //ib플래그 + 그룹추가
		if($tmp_profile && $tmp_code && $iscoupon == "undefined") {
		    $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from ".config_item('ib_template')." a inner join ".config_item('ib_profile')." b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_id=? and (tpl_profile_key=? or tpl_profile_key=?) order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile, $tmp_profile2))->row();
		} else if($tmp_profile && $tmp_code && $view['view']['iscoupon'] !="undefined") {
		    $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback, cc.* from ".config_item('ib_template')." a inner join ".config_item('ib_profile')." b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' inner join cb_coupon cc on cc.cc_tpl_id = a.tpl_id and cc.cc_idx = '$iscoupon' where /*tpl_mem_id=? and */tpl_id=? and (tpl_profile_key=? or tpl_profile_key=?) order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile, $tmp_profile2))->row();
		}



		//발신번호 조회
        //ib플래그
		$view['rs1'] = $this->db->query("select A.*, B.mem_linkbtn_name from (select * from ".config_item('ib_profile')." where spf_mem_id=? and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();

		$sms_callback = $view['rs']->spf_sms_callback;

		$view['mem1'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);

        $view['temp_pro2'] = "";
        if($view['rs']->tpl_profile_key==$tmp_profile2){
            $view['temp_pro2'] = "Y";
        }
		// log_message("ERROR", "temp_pro2 : ".$view['temp_pro2']);

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
		//echo $_SERVER['REQUEST_URI'] ." > psd_code : ". $view['psd_code'] ."<br>";
		//echo $_SERVER['REQUEST_URI'] ." > psd_url : ". $view['psd_url'] ."<br>";
		//echo $_SERVER['REQUEST_URI'] ." > sms_callback : ". $sms_callback ."<br>";

		if(empty($sms_callback)) $sms_callback = $this->member->item("mem_phone");
		//echo $_SERVER['REQUEST_URI'] ." > sms_callback : ". $sms_callback ."<br>";

		if(!empty($sms_callback)){
			$sms_callback = preg_replace("/[^0-9]*/s", "", $sms_callback); //숫자만 출력하기
		}
		//echo $_SERVER['REQUEST_URI'] ." > sms_callback : ". $sms_callback ."<br>";

		$sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$sms_callback."' and use_flag = 'Y' and auth_flag = 'O'";
		//echo $_SERVER['REQUEST_URI'] ." > Query : ". $sql ."<br>";
        log_message("ERROR",  $_SERVER['REQUEST_URI'] ." > 2Query : ". $sql);
		$view['sendtelauth'] = $this->db->query($sql)->row()->cnt;

		// 템플릿 카테고리 조회 2020-09-19
        //ib플래그
		$sql = "SELECT COUNT(cwtc.tc_id) as category_cnt FROM ".config_item('ib_category')." cwtc WHERE cwtc.tc_useyn='Y' and cwtc.tc_type='T'";
		$view['category_cnt'] =  $this->db->query($sql)->row()->category_cnt;
        //ib플래그
		$sql = "SELECT cwtc.tc_id, cwtc.tc_description, cwtc.tc_seq FROM ".config_item('ib_category')." cwtc WHERE cwtc.tc_useyn='Y' and cwtc.tc_type='T' ORDER BY cwtc.tc_seq ASC";
		$view['category_list'] =  $this->db->query($sql)->result();

		// 템플릿 버튼갯수 최대값 조회 2020-09-19
		//$sql = "SELECT MAX(cwtc.tci_btn_cnt) AS max_btn_cnt FROM cb_wt_template_categoryid cwtc WHERE cwtc.tci_type ='T'";
        //ib플래그
		$sql = "SELECT COUNT(DISTINCT cwtc.tci_btn_cnt) AS max_btn_cnt FROM ".config_item('ib_categoryid')." cwtc WHERE cwtc.tci_type ='T'";
		$view['max_btn_cnt'] = $this->db->query($sql)->row()->max_btn_cnt;
        //ib플래그
		$sql = "SELECT DISTINCT cwtc.tci_btn_cnt FROM ".config_item('ib_categoryid')." cwtc WHERE cwtc.tci_type ='T' ORDER BY cwtc.tci_btn_cnt ASC";
		//$view['max_btn_cnt'] = $this->db->query($sql)->row()->max_btn_cnt;
		$view['max_btn_cnt_list'] = $this->db->query($sql)->result();



		$view['add'] = $this->input->post("add");
		$skin = "select_template_adv_v2";
		$add = $view['add'];
		if($add != "") $skin .= $add;
		$this->load->view('biz/dhnsender/send/'. $skin, $view);
    }

}
?>
