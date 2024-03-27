<?php
class Select_template_img_adv_v3 extends CB_Controller {
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
        //ib플래그 + 그룹추가
		if($tmp_profile && $tmp_code && $iscoupon == "undefined") {
		    $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from ".config_item('ib_template')." a inner join ".config_item('ib_profile')." b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_id=? and (tpl_profile_key=? or tpl_profile_key=?) order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile,  $tmp_profile2))->row();
		} else if($tmp_profile && $tmp_code && $view['view']['iscoupon'] !="undefined") {
		    $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback, cc.* from ".config_item('ib_template')." a inner join ".config_item('ib_profile')." b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' inner join cb_coupon cc on cc.cc_tpl_id = a.tpl_id and cc.cc_idx = '$iscoupon' where /*tpl_mem_id=? and */tpl_id=? and (tpl_profile_key=? or tpl_profile_key=?) order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile, $tmp_profile2))->row();
		}

        //직접입력 선택적 불가 부분
        $view['isdhn'] = "N";
        if($this->member->item('mem_level') >= 100||$this->member->item('mem_id')=='1256') {
            $view['isdhn'] = "Y";
        }
        if($this->member->item('mem_id') != '3') {
            $login_stack = $this->session->userdata('login_stack');
            if($this->session->userdata('login_stack')) {
                $login_stack = $this->session->userdata('login_stack');
                if($login_stack[0] == '3') {
                   $view['isdhn'] = "Y";

               }
            }
        }

		//발신번호 조회
        //ib플래그
		$view['rs1'] = $this->db->query("select A.*, B.mem_linkbtn_name from (select * from ".config_item('ib_profile')." where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();

		$sms_callback = $view['rs']->spf_sms_callback;

		$view['mem1'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);

		//log_message("ERROR", "st Coupon : ".$view['rs']->tpl_profile_key);

		$this->load->library('base62');
		$surl_id = cdate('YmdHis');
        $view['editor_url'] = $this->input->post('editor_url');
        $view['short_url'] = $this->input->post('short_url');

        if($view['short_url'] != ""){
            $view['dhnl_url'] = 'http://smart.dhn.kr/at/'.$view['short_url'];
        }else{
            $surl_id = cdate('YmdHis');
            $view['short_url'] = $this->base62->encode($mem_id.$surl_id);
            if($view['editor_url'] != ""){
                $view['short_url'] = $view['editor_url'];
                $view['dhnl_url'] = 'http://smart.dhn.kr/at/'.$view['editor_url'];
            }else{
                // $surl_id = cdate('YmdHis');
                $view['short_url'] = $this->base62->encode($mem_id.$surl_id);
                $view['dhnl_url'] = '';
            }
        }
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > dhnl_url : ". $view['dhnl_url']);
        $view['psd_code'] = $this->input->post('psd_code');
		if($view['psd_code'] != ""){
			//$view['psd_url'] = 'http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $view['psd_code'];
			$view['psd_url'] = "http://smart.dhn.kr/smart/view/". $view['psd_code'];
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > url_detail : ".'http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $view['psd_code']);
		}else{
			$view['psd_url'] = '';
		}
		$view['pcd_code'] = $this->input->post('pcd_code');
		$view['pcd_type'] = $this->input->post('pcd_type');
        if($view['pcd_code'] != ""){
			//$view['psd_url'] = 'http://'. $_SERVER['HTTP_HOST'] ."/smart/coupon/". $view['psd_code'];
			$view['pcd_url'] = "http://smart.dhn.kr/smart/coupon/". $view['pcd_code'];
		}else{
			$view['pcd_url'] = '';
		}

        $view['home_code'] = $this->input->post('home_code');

		if($view['home_code'] != ""){
			$view['home_url'] = 'http://'. $this->dhnlurl->get_short("http://smart.dhn.kr/smart/shome/". $view['home_code']);
            log_message("ERROR", $_SERVER['REQUEST_URI'] ." > url_detail : ".'http://'. $_SERVER['HTTP_HOST'] ."/smart/shome/". $view['home_code']);
		}else{
            //스마트홈 코드 생성
            // $this->load->library('base62');
            $shurl_id = $this->member->item("mem_id").cdate('YmdHis');
            $sh_code = $this->base62->encode($shurl_id);
            $view['home_code'] = $sh_code;
            $view['home_url'] = 'http://'. $this->dhnlurl->get_short("http://smart.dhn.kr/smart/shome/". $sh_code);
			// $view['home_url'] = '';
		}

        $sql= "SELECT mad_free_hp from cb_wt_member_addon where mad_mem_id = '".$this->member->item("mem_id")."'";
        // $view['order_url'] = $this->db->query($sql)->row()->mad_free_hp;
        $view['order_url'] = (!empty($this->member->item("mem_alim_btn_url3")))? $this->member->item("mem_alim_btn_url3") : $this->db->query($sql)->row()->mad_free_hp;
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

        // 템플릿 카테고리 조회 2020-09-19
        //ib플래그
		$sql = "SELECT COUNT(cwtc.tc_id) as category_cnt FROM ".config_item('ib_category')." cwtc WHERE cwtc.tc_useyn='Y' and cwtc.tc_type='I'";
		$view['category_cnt'] =  $this->db->query($sql)->row()->category_cnt;
        //ib플래그
		$sql = "SELECT cwtc.tc_id, cwtc.tc_description, cwtc.tc_seq FROM ".config_item('ib_category')." cwtc WHERE cwtc.tc_useyn='Y' and cwtc.tc_type='I' ORDER BY cwtc.tc_seq ASC";
		$view['category_list'] =  $this->db->query($sql)->result();

		// 템플릿 버튼갯수 최대값 조회 2020-09-19
        //ib플래그
		$sql = "SELECT COUNT(DISTINCT cwtc.tci_btn_cnt) AS max_btn_cnt FROM ".config_item('ib_categoryid')." cwtc WHERE cwtc.tci_type ='I'";
		$view['max_btn_cnt'] = $this->db->query($sql)->row()->max_btn_cnt;

		//$sql = "SELECT MAX(cwtc.tci_btn_cnt) AS max_btn_cnt FROM ".config_item('ib_category')."id_ib cwtc WHERE cwtc.tci_type ='I'";
        //ib플래그
		$sql = "SELECT DISTINCT cwtc.tci_btn_cnt FROM ".config_item('ib_categoryid')." cwtc WHERE cwtc.tci_type ='I' and cwtc.tci_btn_cnt < 2 ORDER BY cwtc.tci_btn_cnt ASC";
		//$view['max_btn_cnt'] = $this->db->query($sql)->row()->max_btn_cnt;
		$view['max_btn_cnt_list'] = $this->db->query($sql)->result();



		$view['add'] = $this->input->post("add");
		$skin = "select_template_img_adv_v3";
		$add = $view['add'];
		if($add != "") $skin .= $add;
		$this->load->view('biz/dhnsender/send/'. $skin, $view);
    }

}
?>
