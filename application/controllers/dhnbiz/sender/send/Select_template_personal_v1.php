<?php
class Select_template_personal_v1 extends CB_Controller {
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
		$this->load->library(array('querystring', 'dhnlurl', 'kakaolib'));
    }

    public function index()
    {
		$view = array();
		$view['view'] = array();
		$view['pcr_id'] = $this->input->post('pcr_id');
		$mem_id = $this->member->item("mem_id");

        $view['crs'] = $this->db
            ->select('*')
            ->from('cb_personal_creative')
            ->where('pcr_id', $view['pcr_id'])
            ->get()->row();

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

        if(!$profileKey) {
            $view['rs'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from ".config_item('ib_profile')." where spf_mem_id=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();
        } else {
            $view['rs'] = $this->db->query("select A.*, B.mem_linkbtn_name FRom (select * from ".config_item('ib_profile')." where spf_key=? and spf_appr_id<>'' and spf_use='Y' and spf_status='A') as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($profileKey))->row();
        }


		$sms_callback = $view['rs']->spf_sms_callback;

		$view['mem1'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);


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
                $view['short_url'] = $this->base62->encode($mem_id.$surl_id);
                $view['dhnl_url'] = '';
            }
        }

        $sql= "SELECT mad_free_hp from cb_wt_member_addon where mad_mem_id = '".$this->member->item("mem_id")."'";
        // $view['order_url'] = $this->db->query($sql)->row()->mad_free_hp;
        $view['order_url'] = (!empty($this->member->item("mem_alim_btn_url3")))? $this->member->item("mem_alim_btn_url3") : $this->db->query($sql)->row()->mad_free_hp;

		if(empty($sms_callback)) $sms_callback = $this->member->item("mem_phone");

		if(!empty($sms_callback)){
			$sms_callback = preg_replace("/[^0-9]*/s", "", $sms_callback); //숫자만 출력하기
		}

		$sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$sms_callback."' and use_flag = 'Y' and auth_flag = 'O'";
		$view['sendtelauth'] = $this->db->query($sql)->row()->cnt;

        $this->load->library('base62');
		$surl_id = cdate('YmdHis');
		$view['short_url'] = $this->base62->encode($mem_id.$surl_id);
		$view['dhnl_url'] = "http://smart.dhn.kr/at/".$view['short_url'];
        $view['dhnl_url_s'] = 'http://'.$this->dhnlurl->get_short("http://smart.dhn.kr/at/".$view['short_url']);
		$view['psd_code'] = $this->input->post('psd_code');
		if($view['psd_code'] != ""){
			$view['psd_url'] = "http://smart.dhn.kr/smart/view/". $view['psd_code'];
			$view['psd_url_s'] = 'http://'. $this->dhnlurl->get_short("http://smart.dhn.kr/smart/view/". $view['psd_code']);
		}else{
			$view['psd_url'] = '';
            $view['psd_url_s'] = '';
		}
		$view['pcd_code'] = $this->input->post('pcd_code');
		$view['pcd_type'] = $this->input->post('pcd_type');
		if($view['pcd_code'] != ""){
			$view['psd_url'] = "http://smart.dhn.kr/smart/coupon/". $view['pcd_code'];
			$view['pcd_url_s'] = 'http://'. $this->dhnlurl->get_short("http://smart.dhn.kr/smart/coupon/". $view['pcd_code']);
		}else{
			$view['pcd_url'] = '';
            $view['pcd_url_s'] = '';
		}

        $view['home_code'] = $this->input->post('home_code');

        if($view['home_code'] != ""){
			$view['home_url'] = 'http://'. $this->dhnlurl->get_short("http://smart.dhn.kr/smart/shome/". $view['home_code']);
		}else{
            //스마트홈 코드 생성
            $shurl_id = $this->member->item("mem_id").cdate('YmdHis');
            $sh_code = $this->base62->encode($shurl_id);
            $view['home_code'] = $sh_code;
            $view['home_url'] = 'http://'. $this->dhnlurl->get_short("http://smart.dhn.kr/smart/shome/". $sh_code);
		}

		$view['add'] = $this->input->post("add");
		$skin = "select_template_personal_v1";
		$add = $view['add'];
		if($add != "") $skin .= $add;
		$this->load->view('biz/dhnsender/send/'. $skin, $view);
    }

}
?>
