<?php
class Select_template_rcs_v2 extends CB_Controller {
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
		$tmp_brand = $this->input->post('tmp_brand');
		// $iscoupon = $this->input->post('iscoupon');
		$view = array();
		$view['view'] = array();
		$save_msg_id = $this->input->post('save_msg_id'); //알림톡 저장 일련번호
		$mem_id = $this->member->item("mem_id"); //회원번호
		$tmp_flag = $this->input->post('tmp_flag'); //템플릿구분
        $view['rcs_type'] = ($this->input->post('rcs_type'))? $this->input->post('rcs_type') : "T" ; //rcs 발송 타입

        if($view['rcs_type'] != "T"){
            if($view['rcs_type'] == "S"){
                $view['rcs_tmpl'] = "SS000000";
            }else if($view['rcs_type'] == "L"){
                $view['rcs_tmpl'] = "SL000000";
            }
        }

        $sql = "SELECT count(1) as cnt, tpl_cardType from cb_wt_brand_template where tpl_brand = '".$tmp_brand."' and tpl_use = 'Y' limit 1 ";
        $view['intemplate'] = $this->db->query($sql)->row();

        $this->load->library('base62');
		$surl_id = cdate('YmdHis');
		$view['short_url'] = $this->base62->encode($mem_id.$surl_id);
		$view['dhnl_url'] = 'http://'.$this->dhnlurl->get_short('http://'. $_SERVER['HTTP_HOST'] .'/at/'.$view['short_url']);
		// log_message("ERROR", $_SERVER['REQUEST_URI'] ." > dhnl_url : ". $view['dhnl_url']);
		$view['psd_code'] = $this->input->post('psd_code');
		if($view['psd_code'] != ""){
			//$view['psd_url'] = 'http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $view['psd_code'];
			$view['psd_url'] = 'http://'. $this->dhnlurl->get_short('http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $view['psd_code']);
            // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > url_detail : ".'http://'. $_SERVER['HTTP_HOST'] ."/smart/view/". $view['psd_code']);
		}else{
			$view['psd_url'] = '';
		}
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > psd_code : ". $view['psd_code']);
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > psd_url : ". $view['psd_url']);
		$view['pcd_code'] = $this->input->post('pcd_code');
		$view['pcd_type'] = $this->input->post('pcd_type');
		if($view['pcd_code'] != ""){
			//$view['psd_url'] = 'http://'. $_SERVER['HTTP_HOST'] ."/smart/coupon/". $view['psd_code'];
			$view['pcd_url'] = 'http://'. $this->dhnlurl->get_short('http://'. $_SERVER['HTTP_HOST'] ."/smart/coupon/". $view['pcd_code']);
		}else{
			$view['pcd_url'] = '';
		}

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

        $view['rs'] = $this->db->query("select a.*, b.brd_sms_callback from cb_wt_brand_template a inner join cb_wt_brand b on a.tpl_brand=b.brd_brand where b.brd_use='Y' and tpl_id=? and tpl_brand=? order by tpl_id desc limit 1", array($tmp_code, $tmp_brand))->row();


		//발신번호 조회
		// $view['rs1'] = $this->db->query("select A.*, B.mem_linkbtn_name from (select * from cb_wt_send_profile_dhn where spf_mem_id=? and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();



		$view['mem1'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);

		//log_message("ERROR", "st Coupon : ".$view['rs']->tpl_profile_key);

		$this->load->library('base62');
		$surl_id = cdate('YmdHis');
		$view['short_url'] = $this->base62->encode($mem_id.$surl_id);
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
        $sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$this->member->item("mem_phone")."' and use_flag = 'Y' and auth_flag = 'O'";
		//echo $_SERVER['REQUEST_URI'] ." > Query : ". $sql ."<br>";
		$view['sendtelauth'] = $this->db->query($sql)->row()->cnt;

		// if(empty($sms_callback)) $sms_callback = $this->member->item("mem_phone");
		// //echo $_SERVER['REQUEST_URI'] ." > sms_callback : ". $sms_callback ."<br>";
        //
		// if(!empty($sms_callback)){
		// 	$sms_callback = preg_replace("/[^0-9]*/s", "", $sms_callback); //숫자만 출력하기
		// }
		//echo $_SERVER['REQUEST_URI'] ." > sms_callback : ". $sms_callback ."<br>";



        // 템플릿 카테고리 조회 2020-09-19
		// $sql = "SELECT COUNT(cwtc.tc_id) as category_cnt FROM cb_wt_template_category cwtc WHERE cwtc.tc_useyn='Y' and cwtc.tc_type='T'";
		// $view['category_cnt'] =  $this->db->query($sql)->row()->category_cnt;
        //
		// $sql = "SELECT cwtc.tc_id, cwtc.tc_description, cwtc.tc_seq FROM cb_wt_template_category cwtc WHERE cwtc.tc_useyn='Y' and cwtc.tc_type='T' ORDER BY cwtc.tc_seq ASC";
		// $view['category_list'] =  $this->db->query($sql)->result();

		// 템플릿 버튼갯수 최대값 조회 2020-09-19
		//$sql = "SELECT MAX(cwtc.tci_btn_cnt) AS max_btn_cnt FROM cb_wt_template_categoryid cwtc WHERE cwtc.tci_type ='T'";
		// $sql = "SELECT COUNT(DISTINCT cwtc.tci_btn_cnt) AS max_btn_cnt FROM cb_wt_template_categoryid cwtc WHERE cwtc.tci_type ='T'";
		// $view['max_btn_cnt'] = $this->db->query($sql)->row()->max_btn_cnt;

		// $sql = "SELECT DISTINCT cwtc.tci_btn_cnt FROM cb_wt_template_categoryid cwtc WHERE cwtc.tci_type ='T' ORDER BY cwtc.tci_btn_cnt ASC";
		//$view['max_btn_cnt'] = $this->db->query($sql)->row()->max_btn_cnt;
		// $view['max_btn_cnt_list'] = $this->db->query($sql)->result();

		$view['add'] = $this->input->post("add");
		$skin = "select_template_rcs_v2";
		$add = $view['add'];
		if($add != "") $skin .= $add;
		$this->load->view('biz/dhnsender/send/'. $skin, $view);
    }

}
?>
