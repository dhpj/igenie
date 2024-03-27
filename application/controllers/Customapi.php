<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Group class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Customapi extends CB_Controller {
	/**
	* 모델을 로딩합니다
	*/
	protected $models = array('Board');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'dhtml_editor');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring', 'board_group', 'funn'));

	}

	//고객분포
	public function index(){
	    if($this->member->is_member() == false) { //로그인이 안된 경우
	        redirect('login');
	    }

        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        $layoutconfig = array(
            'path' => 'customapi',
            'layout' => 'layout',
            'skin' => 'main',
            'layout_dir' => $this->cbconfig->item('layout_main'),
            'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_main'),
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => $this->cbconfig->item('skin_main'),
            'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_main'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
	}


    public function sendat_custom(){

        if($this->member->is_member() == false) { //로그인이 안된 경우
           redirect('login');
       }

       if($this->member->item('mem_level') < 100) { //
          redirect('Home');
       }

	    $mem_id = $this->member->item('mem_id');

        $coName = $this->input->post('coName');
        $profile = $this->input->post('profile');
        $code = "a-1";

        $tel = $this->input->post('sendNum');





        // $sql = "SELECT emg_id, emg_cnt FROM cb_evemaker_goods WHERE emg_emd_id ='".$emd_id."' and emg_useyn= 'Y'";
        // $eve_result = $this->db->query($sql)->result();
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > SQL : ".$sql);

        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > mem_id : ".$mem_id." / coName : ".$coName." / profile : ".$profile." / tel : ".$tel);

        $result = array();



        // $kakao = array();




        // $this->db->insert('kakao_api_call', $kakao);

        // if ($this->db->error()['code'] == 0) {
            if(!empty($profile)) {
                //$cnt = $this->biz_model->get_table_count("cb_wt_send_profile", "spf_key = '".$profile."' and spf_use = 'Y'");
                //$sql = "select count(1) as cnt,max(b.mem_userid) as userid, max(b.mem_id) as mem_id, b.mem_2nd_send  from cb_wt_send_profile a inner join cb_member b on b.mem_id = a.spf_mem_id where spf_key = '".$profile."' and spf_use = 'Y'";
                //ib플래그
                if(!empty($mem_id)){
                    $sql = "select count(1) as cnt, mem_userid, mem_id, mem_2nd_send from cb_member where mem_id = ".$mem_id." ";
                }
                log_message('ERROR', '/customapi/sendat_custom > sql : '. $sql);
                $cnt = $this->db->query($sql)->row()->cnt;
                $userid = $this->db->query($sql)->row()->mem_userid;
                // $mem_id = $this->db->query($sql)->row()->mem_id;
                $mem_2nd_send = $this->db->query($sql)->row()->mem_2nd_send;

                // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                $sql2 = "select mad_mem_id, mad_price_at, mad_price_ft, mad_price_ft_img, mad_price_grs, mad_price_grs_sms, mad_price_grs_mms, mad_price_nas, mad_price_nas_sms, mad_price_nas_mms, mad_price_ft_w_img, mad_price_smt, mad_price_smt_sms, mad_price_smt_mms from cb_wt_member_addon where mad_mem_id=".$mem_id;
                log_message('ERROR', '/customapi/sendat_custom > sql2 : '. $sql2);
                $price = $this->db->query($sql2)->row();
                $mst_price_at = $price->mad_price_at;
                $mst_price_ft = $price->mad_price_ft;
                $mst_price_ft_img = $price->mad_price_ft_img;
                $mst_price_ft_w_img = $price->mad_price_ft_w_img;
                $mst_price_grs = $price->mad_price_grs;
                $mst_price_grs_sms = $price->mad_price_grs_sms;
                $mst_price_grs_mms = $price->mad_price_grs_mms;
                $mst_price_nas = $price->mad_price_nas;
                $mst_price_nas_sms = $price->mad_price_nas_sms;
                $mst_price_nas_mms = $price->mad_price_nas_mms;
                $mst_price_smt = $price->mad_price_smt;
                $mst_price_smt_sms = $price->mad_price_smt_sms;
                $mst_price_smt_mms = $price->mad_price_smt_mms;

                if($cnt>0) {




                            $tmp_str = "안녕하세요. ".$coName."입니다.

회원가입(이용)을 해주셔서 감사합니다.

고객님의 편의를 위해 다양한 소식을 카카오톡 채널을 통해 알려드리겠습니다.";


                            $btn = array();

                            $idx = 0;


                                $btnstr = array();
                                $btnstr['type'] = "AC";
                                $btnstr['name'] = "채널 추가";


								$btn[$idx] = json_encode($btnstr,JSON_UNESCAPED_UNICODE);
                                $btn[$idx] = str_replace("\\","",$btn[$idx]);




                            // 2019-11-04 삭제부분 빠져서 추가
                            $del = "delete from cb_api_sc_".$userid;
                            $this->db->query($del);

                            $mem_send = 'NONE';
                            $sms_kind = '';



                            //- 발송내역에 기록
                            $data = array();
                            $data['mst_mem_id'] = $mem_id;
                            //$data['mst_template'] = '';
                            $data['mst_template'] = $code; //2020-12-11 추가
                            $data['mst_profile'] = $profile;
                            $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
                            $data['mst_kind'] =  "at";
                            $data['mst_content'] =  $tmp_str;
                            $data['mst_sms_send'] =  'N';
                            $data['mst_lms_send'] =  '';
                            $data['mst_mms_send'] =  "N";
                            $data['mst_sms_content'] =  '';
                            $data['mst_lms_content'] =  '';
                            $data['mst_mms_content'] =  "";
                            $data['mst_img_content'] =  "";
                            //$data['mst_button'] = json_encode(array($btn[0], $btn[1], $btn[2], $btn[3], $btn[4]), JSON_UNESCAPED_UNICODE);
                            $data['mst_button'] = json_encode(array("[". $btn[0] ."]", "[". $btn[1] ."]", "[". $btn[2] ."]", "[". $btn[3] ."]", "[". $btn[4] ."]"), JSON_UNESCAPED_UNICODE); //2020-12-11
							$data['mst_reserved_dt'] =  "00000000000000";
                            $data['mst_sms_callback'] =  '';
                            $data['mst_status'] = '1';
                            $data['mst_qty'] =  1;
                            $data['mst_amount'] = 0;	// ($this->Biz_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));
                            // 2019-02-18 이수환 추가
                            $data['mst_type1'] = "at";
                            $data['mst_type2'] = "";


                            // 2019-02-25 이수환 cb_wt_msg_sent에 단가(9개 필드) 추가
                            $data['mst_price_at'] = $mst_price_at;
                            $data['mst_price_ft'] = $mst_price_ft;
                            $data['mst_price_ft_img'] = $mst_price_ft_img;
                            $data['mst_price_ft_w_img'] = $mst_price_ft_w_img;
                            $data['mst_price_grs'] = $mst_price_grs;
                            $data['mst_price_grs_sms'] = $mst_price_grs_sms;
                            $data['mst_price_grs_mms'] = $mst_price_grs_mms;
                            $data['mst_price_nas'] = $mst_price_nas;
                            $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                            $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                            $data['mst_price_nas'] = $mst_price_nas;
                            $data['mst_price_nas_sms'] = $mst_price_nas_sms;
                            $data['mst_price_nas_mms'] = $mst_price_nas_mms;
                            $data['mst_smt_price'] = $mst_price_smt;
                            $data['mst_price_smt_sms'] = $mst_price_smt_sms;
                            $data['mst_price_smt_mms'] = $mst_price_smt_mms;

                            // 2021-10-14 발송 플랫폼 추가
                            $data['mst_sent_platform'] = 'A';
                            $sql = "
                                SELECT SUM(amt_amount * amt_deduct) as bamt FROM cb_amt_" . $userid . " WHERE FIND_IN_SET('바우처', amt_memo)=0 AND FIND_IN_SET('보너스', amt_memo)>0 AND amt_kind != '9'
                            ";
                            $bamt = $this->db->query($sql)->row()->bamt;
                            $data['mst_sent_voucher'] = $bamt > 0 ? 'B' : 'N';

                            $this->db->insert("cb_wt_msg_sent", $data);
                            $gid = $this->db->insert_id();

                            $sc = array();
                            $sc['sc_tel'] = str_replace('-','',$tel);
                            $sc['sc_content'] = $tmp_str;
                            $sc['sc_button1'] = $btn[0];
                            $sc['sc_button2'] = $btn[1];
                            $sc['sc_button3'] = $btn[2];
                            $sc['sc_button4'] = $btn[3];
                            $sc['sc_button5'] = $btn[4];
                            $sc['sc_sms_yn'] = 'N';
                            $sc['sc_lms_content'] = "";
                            $sc['sc_sms_callback'] = "";
                            /*$sc['sc_img_url']*/
                            /*$sc['sc_img_link']*/
                            $sc['sc_template'] = $code;
                            /*$sc['sc_p_com']
                             $sc['sc_p_invoice']
                             $sc['sc_s_code']
                             $sc['sc_reserve_dt']*/
                            $sc['sc_group_id'] = $gid;
                            $this->db->insert('cb_api_sc_'.$userid, $sc);
                            $sc_id = $this->db->insert_id();
                            //ib플래그

                            $this->load->library('Dhnkakao');
                            $this->dhnkakao->sendAgent2SendAPIs('at', $gid,$mem_id, $userid,$profile,'00000000000000', $sc_id, $mem_send, $sms_kind);



                    $result["code"] = '0';

                    $result["msg"] = '';
                } else {

                    $result["code"] = '103';

                    $result["msg"] = '멤버정보가 없습니다.';
                }
            } else {

                $result["code"] = '102';

                $result["msg"] = '프로필키가 없습니다.';
            }


	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

}
?>
