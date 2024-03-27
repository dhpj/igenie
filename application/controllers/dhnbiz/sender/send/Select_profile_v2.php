<?php
class Select_profile_v2 extends CB_Controller {
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
		$skin = "select_profile_v2";
		$add = $view['add'];
		
		// 20230314 이수환 Kakao Channel Public Id 처리부 시작
		$sql = "select count(1) as cnt from cb_wt_send_profile_dhn where spf_mem_id=".$this->member->item('mem_id')." and spf_appr_id<>'' and spf_use='Y' and spf_status='A'";
		$tmp_cnt = $this->db->query($sql)->row()->cnt;
		
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_cnt sql : ". $sql);
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_cnt : ". $tmp_cnt);
		if ($tmp_cnt > 0) {
		    $sql = "select spf_public_id, spf_friend, spf_key from cb_wt_send_profile_dhn where spf_mem_id=".$this->member->item('mem_id')." and spf_appr_id<>'' and spf_use='Y' and spf_status='A'";
		    $tmp_result = $this->db->query($sql)->row();
		    $tmp_public_id = $tmp_result->spf_public_id;
		    $tmp_uuid = $tmp_result->spf_friend;
		    $tmp_profile =  $tmp_result->spf_key;
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_public_id : ". $tmp_public_id);
		    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_uuid : ". $tmp_uuid);
		    if (empty($tmp_public_id)) {   // kakao channel public id 가 없으면
		        $channel_public_id = $this->funn->get_kakao_channel_public_id($tmp_uuid);
		        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > channel_public_id : ". $channel_public_id);
		        if (!empty($channel_public_id)) {   // kakao channel public id을 가져오면 cb_wt_send_profile_dhn 테이블에 업데이트
		            $update_sql = "update cb_wt_send_profile_dhn set spf_public_id='".$channel_public_id."' where spf_mem_id=".$this->member->item('mem_id')." and spf_key='".$tmp_profile."' and spf_appr_id<>'' and spf_use='Y' and spf_status='A'";
		            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > update_sql : ". $update_sql);
		            $this->db->query($update_sql);
		        }
		    }
		}
		// 20230314 이수환 Kakao Channel Public Id 처리부 끝
		
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

        //관리자계정에서 전환했는지 확인
        $view['isdhn'] = "N";
        if($this->member->item('mem_level') >= 100) {
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
