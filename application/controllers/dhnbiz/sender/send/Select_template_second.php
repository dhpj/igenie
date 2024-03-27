<?php
class Select_template_second extends CB_Controller {
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
		$iscoupon = $this->input->post('iscoupon');
		if($iscoupon == "") $iscoupon = "undefined";
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

		//2020-12-14
		if($tmp_profile == ""){
			$tmp_profile = "c03b09ba2a86fc2984b940d462265dd4dbddb105"; //프로필키
			if($this->member->item("mem_userid") == "dhn"){
				//echo "tmp_profile : ". $tmp_profile ."<br>";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_profile : ".$tmp_profile);
			}
		}

		//2020-12-14
		if($tmp_code == ""){
			//광고성 알림톡 메인 템플릿번호 조회
			$sql = "
			SELECT
				IFNULL(MAX(a.tpl_id),94) AS tmp_code /* 템플릿번호 */
			FROM cb_wt_template_dhn a 
			INNER JOIN cb_wt_send_profile_dhn b ON a.tpl_profile_key=b.spf_key AND b.spf_use='Y' 
			WHERE a.tpl_profile_key = '". $tmp_profile ."' /* 프로필키 */
			AND a.tpl_adv_yn = 'Y' /* 광고성 알림톡 여부 */
			AND a.tpl_adv_main_yn = 'Y' /* 광고성 알림톡 메인 여부 */ ";
			$tmp_code = $this->db->query($sql)->row()->tmp_code;
			if($this->member->item("mem_userid") == "dhn"){
				//echo "tmp_code : ". $tmp_code ."<br>";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_code : ".$tmp_code);
			}
		}
		$view['tmp_code'] = $tmp_code;
		//echo "tmp_code : ". $tmp_code .", tmp_profile : ". $tmp_profile ."<br>";

		//광고성 알림톡 메인 템플릿번호 조회
		$sql = "
		SELECT
			IFNULL(MAX(a.tpl_id),94) AS tmp_code /* 템플릿번호 */
		FROM cb_wt_template_dhn a 
		INNER JOIN cb_wt_send_profile_dhn b ON a.tpl_profile_key=b.spf_key AND b.spf_use='Y' 
		WHERE a.tpl_profile_key = '". $tmp_profile ."' /* 프로필키 */
		AND a.tpl_adv_yn = 'Y' /* 광고성 알림톡 여부 */
		AND a.tpl_adv_main_yn = 'Y' /* 광고성 알림톡 메인 여부 */ ";
		$base_tmp_code = $this->db->query($sql)->row()->tmp_code;

		//스마트쿠폰 템플릿번호 조회
		$sql = "
		SELECT
			IFNULL(MAX(a.tpl_id),". $base_tmp_code .") AS tmp_code /* 템플릿번호 */
		FROM cb_wt_template_dhn a 
		INNER JOIN cb_wt_send_profile_dhn b ON a.tpl_profile_key=b.spf_key AND b.spf_use='Y' 
		WHERE a.tpl_profile_key =  '". $tmp_profile ."' /* 프로필키 */
		AND a.tpl_adv_yn = 'Y' /* 광고성 알림톡 여부 */
		AND a.tpl_btn1_type = 'C' /* 버튼1타입(L.스마트전단, C.스마트쿠폰, E.에디터사용, S.직접입력) */ ";
		$coupon_tmp_code = $this->db->query($sql)->row()->tmp_code;

		$view['param']['base_tmp_code'] = $base_tmp_code; //광고성 알림톡 메인 템플릿번호
		$view['param']['coupon_tmp_code'] = $coupon_tmp_code; //스마트쿠폰 템플릿번호

		if($tmp_profile && $tmp_code && $iscoupon == "undefined") {
		    $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
			if($this->member->item("mem_userid") == "dhn"){
				//echo "11111111<br>";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 1111111111");
			}
		} else if($tmp_profile && $tmp_code && $view['view']['iscoupon'] !="undefined") {
		    $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback, cc.* from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' inner join cb_coupon cc on cc.cc_tpl_id = a.tpl_id and cc.cc_idx = '$iscoupon' where /*tpl_mem_id=? and */tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
			if($this->member->item("mem_userid") == "dhn"){
				//echo "222222222<br>";
				//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 2222222222");
			}
		}
		
		//발신번호 조회
		$view['rs1'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from cb_wt_send_profile_dhn where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();
		
		$sms_callback = $view['rs']->spf_sms_callback;
		
		$view['mem1'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpl_profile_key : ".$view['rs']->tpl_profile_key);
		
		$this->load->library('base62');
		$surl_id = cdate('YmdHis');
		$view['short_url'] = $this->base62->encode($surl_id);
		$view['dhnl_url'] = 'http://'.$this->dhnlurl->get_short('http://'. $_SERVER['HTTP_HOST'] .'/at/'.$view['short_url']);
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > dhnl_url : ". $view['dhnl_url']);
		$view['psd_code'] = $this->input->post('psd_code');
		if($view['psd_code'] != ""){
			//$view['psd_url'] = 'http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $view['psd_code'];
			$view['psd_url'] = 'http://'. $this->dhnlurl->get_short('http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $view['psd_code']);
		}else{
			$view['psd_url'] = '';
		}
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
		$view['sendtelauth'] = $this->db->query($sql)->row()->cnt;
		
		$view['add'] = $this->input->post("add");
		$skin = "select_template_second";
		$add = $view['add'];
		if($add != "") $skin .= $add;
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > skin : ". $skin);
		$this->load->view('biz/dhnsender/send/'. $skin, $view);
    }
    
}
?>