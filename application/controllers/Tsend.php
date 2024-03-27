<?php
class Tsend extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

    protected $PW = 'dhn7985!';

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring', 'funn'));
    }

    public function index() {
        $page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'tsend',
			'layout' => 'layout_home',
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

    public function check_pw(){
        $result = array();
        $result['code'] = '1';
        $request_pw = $this->input->post('pw');

        if ($request_pw == $this->PW){
            $html = '';
            $html .= '<div id=\'div_send\'>';
            $html .= '  <div>알림톡</div>';
            $html .= '  <div><input type="radio" id="r_at_A" name="r_at" value="A" checked><label for="r_at_A">템플릿A</label></div>';
            $html .= '  <div><input type="radio" id="r_at_B" name="r_at" value="B"><label for="r_at_B">템플릿B</label></div>';
            $html .= '  <div><input type="radio" id="r_at_C" name="r_at" value="C"><label for="r_at_C">템플릿C</label></div>';
            $html .= '  <input type=\'text\' id=\'tel\' class=\'tel\' maxlength=\'11\' oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\..*)\./g, \'$1\');"/>';
            $html .= '  <button id=\'send\'>발송하기</button>';
            $html .= '</div>';
            $html .= '<br>';
            $html .= '<div id=\'div_send\'>';
            $html .= '  <div>친구톡</div>';
            $html .= '  <div><input type="radio" id="r_ft_A" name="r_ft" value="A" checked><label for="r_ft_A">템플릿A</label></div>';
            // $html .= '  <div><input type="radio" id="r_ft_B" name="r_ft" value="B"><label for="r_ft_B">템플릿B</label></div>';
            $html .= '  <input type=\'text\' id=\'tel2\' class=\'tel\' maxlength=\'11\' oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\..*)\./g, \'$1\');"/>';
            $html .= '  <button id=\'send2\'>발송하기</button>';
            $html .= '</div>';
            $result['html'] = $html;
        } else {
            $result['code'] = '0';
            $result['msg'] = '비밀번호가 틀렸습니다.';
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
    }

    //알림톡 샘플발송신청
	public function sending(){
        $data = array();
        $tel = $this->input->post('tel');
        $r_at = $this->input->post('r_at');

        $tem_code = '';
        $username = '';
        $s_url = '';
        if ($r_at == 'A'){
            $tem_code = '(wt)220616-1';
            $username = '대형할인마트';
            $s_url = 'http://dhnl.kr/buqyo';
        } else if ($r_at == 'B'){
            $tem_code = '(wt)220616-8';
            $username = '가꿈HAIR by 허 하이 실장';
            $s_url = 'http://dhnl.kr/buqyp';
        } else if ($r_at == 'C'){
            $tem_code = '(wt)221226-1';
            $username = '백채김치찌개(t)';
            $s_url = 'http://dhnl.kr/buqzk';
        }

        $postfields = array(
            "day_test_cnt" => 20 //일일 테스트 발송량
          , "receive_phn" => $tel//수신번호
          , "profile_key" => "e928cfe1258ede6069fde1b680aa24a25e38e148" //프로필키
          , "tmp_number" => $tem_code //템플릿번호
          , "kakao_url" => ""
          , "kakao_name" => ""
          , "kakao_sender" => "0557137985" //발신번호
          , "kakao_add1" => $username //변수내용1 (업체명)
          , "kakao_add2" => "" //변수내용3 (변수내용)
          , "kakao_add3" => "" //변수내용4 (업체명)
          , "kakao_add4" => "" //변수내용4 (업체명)
          , "kakao_add5" => "" //변수내용5 (업체전화번호)
          , "kakao_add6" => "" //변수내용6 (업체전화번호)
          , "kakao_add7" => "" //변수내용7
          , "kakao_add8" => "" //변수내용8
          , "kakao_add9" => "" //변수내용9
          , "kakao_add10" => "" //변수내용10
          , "kakao_080" => ""
          , "kakao_res" => ""
          , "kakao_res_date" => ""
          , "tran_replace_type" => ""
          , "kakao_url1_1" => $s_url //버튼링크1 모바일 (행사보기)
          , "kakao_url1_2" => $s_url //버튼링크1 PC (행사보기)
          , "kakao_url2_1" => "" //버튼링크2 모바일 (전단보기)
          , "kakao_url2_2" => "" //버튼링크2 PC (전단보기)
          , "kakao_url3_1" => "" //버튼링크2 모바일 (주문하기)
          , "kakao_url3_2" => "" //버튼링크2 PC (주문하기)
          , "kakao_url4_1" => "" //버튼링크4 모바일
          , "kakao_url4_2" => "" //버튼링크4 PC
          , "kakao_url5_1" => "" //버튼링크5 모바일
          , "kakao_url5_2" => "" //버튼링크5 PC
          , "respo_msg" => "요청하신 번호로 알림톡이 발송되었습니다." //성공 메시지
        );

        // if ($r_at == 'A'){
        //     $postfields['kakao_add1'] = '대형마트';
        //     $postfields['kakao_url1_1'] = 'http://dhnl.kr/brqeu';
        //     $postfields['kakao_url1_2'] = 'http://dhnl.kr/brqeu';
        // } else if ($r_at == 'B'){
        //     $postfields['kakao_add1'] = '대형마트';
        //     $postfields['kakao_url1_1'] = 'http://dhnl.kr/bse1d';
        //     $postfields['kakao_url1_2'] = 'http://dhnl.kr/bse1d';
        // } else if ($r_at == 'C'){
        //     $postfields['kakao_url2_1'] = 'http://dhnl.kr/buqzk';
        //     $postfields['kakao_url2_2'] = 'http://dhnl.kr/buqzk';
        // }

      $postitem = json_encode($postfields, JSON_UNESCAPED_UNICODE);
      $ch = curl_init();

      $options = array(
          CURLOPT_URL => base_url('bizmsgapi/sample_img_alimtk'),
          CURLOPT_HEADER => false,
          CURLOPT_POST => 1,
          CURLOPT_HTTPHEADER => array("Content-Type:application/json"),
          CURLOPT_POSTFIELDS => $postitem,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_USERAGENT => $this->agent,
          CURLOPT_REFERER => "",
          CURLOPT_TIMEOUT => 10
      );
      curl_setopt_array($ch, $options);
      $buffer = curl_exec ($ch);
      $cinfo = curl_getinfo($ch);
      curl_close($ch);
      echo $buffer;
    }

    //마트톡 샘플발송신청
	public function sending2(){
        $tel = $this->input->post('tel');
        $r_ft = $this->input->post('r_ft');
        $this->load->model('Biz_dhn_model');
        $this->load->library('dhnkakao');

        $param = array(
            '',											// 전화번호
            '이벤트 no1(최고)
(선물)추석 한정 특가, 선물세트(선물)
이벤트 no2(브이)
(축하)고객감사 특가 라이브커머스(축하)

(별)채널추가 후
할인쿠폰을 받아보세요(별)

모든 혜택 받으시고,
풍요로운 추석 되시길 바랍니다:-)',			// 템플릿내용
            '{"type":"WL","name":"★추석 특가,선물세트 보러가기★","url_mobile":"http://smart.dhn.kr/smart/view/b5omytkdds44","url_pc":"http://smart.dhn.kr/smart/view/b5omytkdds44"}',
            '{"type":"WL","name":"★채널추가 할인쿠폰 받기★","url_mobile":"http://pf.kakao.com/_xlKcgC/101840041","url_pc":"http://pf.kakao.com/_xlKcgC/101840041"}',
            '{"type":"WL","name":"★라이브 방송 보러가기★","url_mobile":"https://tv.kakao.com/v/412466082","url_pc":"https://tv.kakao.com/v/412466082"}',
            '',
            '',
            'S',		// SMS재발신여부
            '',				// LMS/SMS내용
            '0557137985',
            'https://mud-kage.kakao.com/dn/cLtp1d/btssVIZEk3U/v4UqKlYf25FFugxSXk7eYk/img_l.jpg',
            'http://smart.dhn.kr/smart/view/b5omytkdds44',
            ''			// 템플릿코드
        );
        $param[0] = $tel;

        $this->db->query("delete from cb_sc_igenie_t1");
        $sql = "insert into cb_sc_igenie_t1
                (sc_name , sc_tel, sc_content, sc_button1, sc_button2, sc_button3, sc_button4, sc_button5, sc_sms_yn, sc_lms_content, sc_sms_callback, sc_img_url, sc_img_link, sc_template)
            values ('', ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";
        $this->db->query($sql, $param);

        $data = array();
        $data['mst_mem_id'] = 2464;
        $data['mst_template'] = '';
        $data['mst_profile'] = "e928cfe1258ede6069fde1b680aa24a25e38e148";
        $data['mst_datetime'] =  cdate('Y-m-d H:i:s');
        $data['mst_kind'] =  "ft";
        $data['mst_content'] =  '이벤트 no1(최고)
(선물)추석 한정 특가, 선물세트(선물)
이벤트 no2(브이)
(축하)고객감사 특가 라이브커머스(축하)

(별)채널추가 후
할인쿠폰을 받아보세요(별)

모든 혜택 받으시고,
풍요로운 추석 되시길 바랍니다:-)';
        $data['mst_img_content'] =  'https://mud-kage.kakao.com/dn/cLtp1d/btssVIZEk3U/v4UqKlYf25FFugxSXk7eYk/img_l.jpg';
        $data['mst_img_link_url'] = 'http://smart.dhn.kr/smart/view/b5omytkdds44';
        $data['mst_button'] = '["[{\"type\":\"WL\",\"name\":\"♥ 행사보기 ♥\",\"url_mobile\":\"http:\/\/smart.dhn.kr\/smart_mt\/view\/aB4YYczuW\",\"url_pc\":\"http:\/\/smart.dhn.kr\/smart_mt\/view\/aB4YYczuW\"}]","[{\"type\":\"WL\",\"name\":\"★ 쿠폰받기 ★\",\"url_mobile\":\"http:\/\/pf.kakao.com\/_xlKcgC\/99470434\",\"url_pc\":\"http:\/\/pf.kakao.com\/_xlKcgC\/99470434\"}]","[{\"type\":\"WL\",\"name\":\"▶ 홈페이지 바로가기 ◀\",\"url_mobile\":\"http:\/\/dhncorp.co.kr\/\",\"url_pc\":\"http:\/\/dhncorp.co.kr\/\"}]","[]","[]"]';
        $data['mst_reserved_dt'] =  '00000000000000';
        $data['mst_sms_callback'] =  '0557137985';
        $data['mst_status'] = '1';
        $data['mst_qty'] =  1;
        $data['mst_amount'] = 0;	// ($this->Biz_dhn_model->price_ft * (($customer_all_count) ? $customer_all_count : count($tel_number)));

        $data['mst_type1'] =  'ft';    // 2019.01.31 이수환 추가 ; mst_type1(1차 문자 = 카카오 관련 문자)

        $data['mst_price_ft'] = $this->Biz_dhn_model->price_ft;             // 친구톡단가
        $data['mst_price_ft_img'] = $this->Biz_dhn_model->price_ft_img;     // 친구톡이미지단가
        $data['mst_price_at'] = $this->Biz_dhn_model->price_at;             // 알림톡단가
        $data['mst_smt_price'] = $this->Biz_dhn_model->price_smt == 0 ? $this->Biz_dhn_model->price_nas : $this->Biz_dhn_model->price_smt;           // 그린샷 웹(C) lms 단가
        $data['mst_price_smt_sms'] = $this->Biz_dhn_model->price_smt_sms == 0 ? $this->Biz_dhn_model->price_nas_sms : $this->Biz_dhn_model->price_smt_sms;   // 그린샷 웹(C) sms 단가
        $data['mst_price_smt_mms'] = $this->Biz_dhn_model->price_smt_mms == 0 ? $this->Biz_dhn_model->price_nas_mms : $this->Biz_dhn_model->price_smt_mms;   // 그린샷 웹(C) mms 단가
        $data['mst_price_grs'] = $this->Biz_dhn_model->price_grs;           // 그린샷 웹(A) lms 단가
        $data['mst_price_grs_sms'] = $this->Biz_dhn_model->price_grs_sms;   // 그린샷 웹(A) sms 단가
        $data['mst_price_grs_mms'] = $this->Biz_dhn_model->price_grs_mms;   // 그린샷 웹(A) mms 단가
        $data['mst_price_nas'] = $this->Biz_dhn_model->price_nas;           // 그린샷 웹(B) lms 단가
        $data['mst_price_nas_sms'] = $this->Biz_dhn_model->price_nas_sms;   // 그린샷 웹(B) sms 단가
        $data['mst_price_nas_mms'] = $this->Biz_dhn_model->price_nas_mms;   // 그린샷 웹(B) mms 단가
        $data['mst_price_ft_w_img'] = $this->Biz_dhn_model->price_ft_w_img;   // 친구톡 와이드이미지 단가

        $data['send_mem_id'] = 2464;   // 발송자 mem_id(풀케어)
        $data['send_mem_user_id'] = 'igenie_t1';   // 발송자 mem_userid(풀케어)

        $this->db->insert("cb_wt_msg_sent", $data);
        $gid = $this->db->insert_id();

        $this->dhnkakao->sendAgentSendCache("ft", $gid, '2464', 'igenie_t1', 'e928cfe1258ede6069fde1b680aa24a25e38e148');

        header('Content-Type: application/json');
        echo '{"message": "'.(($ok) ? '' : $this->db->error()['message']).'", "code": "'.(($ok) ? 'success' : 'fail').'"}';
        return;
    }

}
?>
