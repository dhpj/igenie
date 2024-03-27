<?php
class Talk_adv_v2_2 extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz_dhn');

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

    public $nft = "";
    //ib플래그 + 그룹추가
	private $tmp_profile; //프로필키
    private $tmp_profile2; //프로필키

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring' ));
        //ib플래그 + 그룹추가
        if(config_item('ib_flag')=="Y"){
    		$this->tmp_profile = "4e546c8c5b7a904a1c7926e8a8487f4c18cc377b"; //프로필키
            $this->tmp_profile2 = "01341cd3aee55b35e8d078049dfd2da31bd9c294"; //프로필키(화이트)
        }else{
            $this->tmp_profile = "c03b09ba2a86fc2984b940d462265dd4dbddb105"; //프로필키
            $this->tmp_profile2 = "0681d073d38f4124a5a34b2eab602f8a1e0509e9"; //프로필키(화이트)
        }
    }

    public function index()
    {
        if ($this->member->item("mem_level") < 100){
        	echo "<script>";
            echo "  alert('잘못된 경로로 접근하였습니다.페이지 이동합니다.');";
            echo "  location.replace('http://igenie.co.kr/dhnbiz/sender/send/talk_adv_v3');";
            echo "</script>";
        }
        $this->Biz_dhn_model->make_msg_log_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_customer_book($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_user_image_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_user_deposit_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_send_cache_table($this->member->item('mem_userid'));
        $this->Biz_dhn_model->make_user_group_table($this->member->item('mem_userid')); //고객별 그룹정보 테이블 확인

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
		$view['add'] = $this->input->get("add");
		if($view['add'] == "") $view['add'] = $this->input->post("add");
		$skin = "talk_adv_v2_2";
		$add = $view['add'];
		if($add != "") $skin .= $add;
        $mem_id = $this->member->item("mem_id"); //회원번호
		$view['param']['tmp_code'] = $this->input->post('tmp_code'); //템플릿번호
        $view['param']['tmp_flag'] = $this->input->post('tmp_flag'); //템플릿구분
        $view['param']['tmp_profile'] = $this->tmp_profile; //프로필키
        //ib플래그 + 그룹추가
        $view['param']['tmp_profile2'] = $this->tmp_profile2; //프로필키
        $view['param']['iscoupon'] = "undefined";

		//스마트전단 코드
		$view['psd_code'] = $this->input->get('psd_code');
		if($view['psd_code'] == ""){
			$view['psd_code'] = $this->input->post('psd_code');
		}
		//echo "psd_code : ". $view['psd_code'] ."<br>";

		//스마트쿠폰 코드
		$view['pcd_code'] = $this->input->get('pcd_code');
		$view['pcd_type'] = $this->input->get('pcd_type');
		if($view['pcd_code'] == ""){
			$view['pcd_code'] = $this->input->post('pcd_code');
		}
		if($view['pcd_type'] == ""){
			$view['pcd_type'] = $this->input->post('pcd_type');
		}
		//echo "psd_code : ". $view['psd_code'] ."<br>";

		//광고성 알림톡 메인 템플릿번호 조회
        //ib플래그 + 그룹추가
        if(config_item('ib_flag')=="Y"){
    		$sql = "
    		SELECT
    			IFNULL(MAX(a.tpl_id),19) AS tmp_code /* 템플릿번호 */
    		FROM ".config_item('ib_template')." a
    		INNER JOIN ".config_item('ib_profile')." b ON a.tpl_profile_key=b.spf_key AND b.spf_use='Y'
    		WHERE ( a.tpl_profile_key = '". $this->tmp_profile ."' OR a.tpl_profile_key = '". $this->tmp_profile2 ."') /* 프로필키 */
    		AND a.tpl_adv_yn = 'Y' /* 광고성 알림톡 여부 */
    		/*AND a.tpl_adv_main_yn = 'Y'  광고성 알림톡 메인 여부 */ ";
        }else{
            $sql = "
    		SELECT
    			IFNULL(MAX(a.tpl_id),409) AS tmp_code /* 템플릿번호 */
    		FROM ".config_item('ib_template')." a
    		INNER JOIN ".config_item('ib_profile')." b ON a.tpl_profile_key=b.spf_key AND b.spf_use='Y'
    		WHERE ( a.tpl_profile_key = '". $this->tmp_profile ."' OR a.tpl_profile_key = '". $this->tmp_profile2 ."') /* 프로필키 */
    		AND a.tpl_adv_yn = 'Y' /* 광고성 알림톡 여부 */
    		AND a.tpl_adv_main_yn = 'Y' /* 광고성 알림톡 메인 여부 */ ";
        }
		$base_tmp_code = $this->db->query($sql)->row()->tmp_code;

		//스마트쿠폰 템플릿번호 조회
        //ib플래그 + 그룹추가
		$sql = "
		SELECT
			IFNULL(MAX(a.tpl_id),". $base_tmp_code .") AS tmp_code /* 템플릿번호 */
		FROM ".config_item('ib_template')." a
		INNER JOIN ".config_item('ib_profile')." b ON a.tpl_profile_key=b.spf_key AND b.spf_use='Y'
		WHERE ( a.tpl_profile_key = '". $this->tmp_profile ."' OR a.tpl_profile_key = '". $this->tmp_profile2 ."') /* 프로필키 */
		AND a.tpl_adv_yn = 'Y' /* 광고성 알림톡 여부 */
		AND a.tpl_btn1_type = 'C' /* 버튼1타입(L.스마트전단, C.스마트쿠폰, E.에디터사용, S.직접입력) */ ";
		$coupon_tmp_code = $this->db->query($sql)->row()->tmp_code;

        $sql = "select b.mtg_useyn as mtg_useyn1, c.mtg_useyn as mtg_useyn2 from cb_member a left join cb_member_template_group b ON a.mem_id = b.mtg_mem_id and b.mtg_profile = 'A' LEFT JOIN cb_member_template_group c ON a.mem_id = c.mtg_mem_id and c.mtg_profile = 'B'  where a.mem_id = '".$this->member->item("mem_id")."' ";
        $temp_group = $this->db->query($sql)->row();
		//if($add == "") $view['param']['tmp_code'] = "94";
		if($view['param']['tmp_code'] == ""){
			if($view['pcd_code'] != ""){ //스마트쿠폰 코드가 있는 경우
				$par_tmp_code = $coupon_tmp_code;
			}else{
                //ib플래그 + 그룹추가
                if(config_item('ib_flag')=="Y"){
                    $par_tmp_code = "19";
                }else{
                    //ib플래그 + 그룹추가
                    if(config_item('ib_flag')=="Y"){
                        $par_tmp_code = "19";
                    }else{
                        $par_tmp_code = "409";
                        // $par_tmp_code = $base_tmp_code;

                    }
    			}
			}
			$view['param']['tmp_code'] = $par_tmp_code;
		}
		$view['param']['base_tmp_code'] = $base_tmp_code;
		$view['param']['coupon_tmp_code'] = $coupon_tmp_code;

		if($view['param']['tmp_flag'] == "temp"){ //임시저장 내용 가져오기
			//임시저장된 알림톡 변수내용 조회
			$sql = "
			SELECT *
			FROM cb_alim_ad_msg_var
			WHERE adv_idx = (SELECT MAX(id) FROM cb_alim_ad_msg WHERE mem_id = '". $mem_id ."' AND use_yn = 'N')
			ORDER BY id ASC ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
			$view['tmp_msg_var'] = $this->db->query($sql)->result();
		}

        if(empty($this->nft)) {
             $this->nft = $this->input->post('nft');
        }
        // 이벤트가 존재하면 실행합니다
        //log_message("ERROR", "NFT : ".$this->nft."/".$this->input->post('nft'));

        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $tmp_code = $view['param']['tmp_code'];
        $tmp_profile = $view['param']['tmp_profile'];
        //ib플래그 + 그룹추가
        $tmp_profile2 = $view['param']['tmp_profile2'];
        //$view['view']['iscoupon'] = $this->input->post('iscoupon');
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tmp_code : ". $tmp_code .", tmp_profile : ". $tmp_profile);
        //ib플래그
        $sql = "select * from ".config_item('ib_profile')." where spf_mem_id=".$this->member->item('mem_id')." and spf_use = 'Y'";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ". $sql);
		$view['spf'] = $this->db->query($sql)->row();
        //ib플래그 + 그룹추가
        if(($tmp_profile && $tmp_code)||($tmp_profile2 && $tmp_code)){
            // log_message("ERROR", "Coupon : ".$view['view']['iscoupon']);
            //$view['tpl'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback from ".config_item('ib_template')." a inner join ".config_item('ib_profile')." b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where /*tpl_mem_id=? and */tpl_id=? and tpl_profile_key=? order by tpl_id desc limit 1", array($this->member->item('mem_id'), $tmp_code, $tmp_profile))->row();
			$sql = "
			select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback
			from ".config_item('ib_template')." a
			inner join ".config_item('ib_profile')." b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
			where /*tpl_mem_id='". $this->member->item('mem_id') ."' and */
			tpl_id = '". $tmp_code ."'
			and (tpl_profile_key = '". $tmp_profile ."' OR tpl_profile_key = '". $tmp_profile2 ."')
			order by tpl_id desc
			limit 1 ";
			$view['tpl'] = $this->db->query($sql)->row();
      log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ". $sql);
            //log_message("ERROR", "row count ".$this->db->affected_rows());
        }

        $view['temp_pro2'] = "";
        if($view['tpl']->tpl_profile_key==$tmp_profile2){
            $view['temp_pro2'] = "Y";
        }
        //log_message("ERROR", "tmp_code : ".$tmp_code.'/'.$tmp_profile);
        $view['mem'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);
        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        // 2019.01.17. 이수환 고객구분 select box값 조회(NULL값을 공백처리해서 길이가 0초가한 값만)
        //$sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid');
        //$sql = "select distinct ab_kind from cb_ab_".$this->member->item('mem_userid')." where length( ifnull(ab_kind,'') ) > 0 ";
        $sql = "select ab_kind, count(ab_kind) ab_kind_cnt from (select IFNULL(ab_kind, '') as ab_kind, ab_tel from cb_ab_".$this->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8) a group by ab_kind";
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > ". $sql);
        $view['kind'] = $this->db->query($sql)->result();
        $view['nft'] = $this->nft;

        // 2019-07-09 친구여부 추가
        $ftCountSql = "select count(*) ftcnt from cb_ab_".$this->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 and exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
        $nftCountSql = "select count(*) nftcnt from cb_ab_".$this->member->item('mem_userid')." where ab_status='1' and length(ab_tel) >=8 and not exists ( select 1 from cb_friend_list b where b.mem_id = '".$this->member->item("mem_id")."' and b.phn = ab_tel)";
        //log_message("ERROR", "ftCountSql : ".$ftCountSql);
        //log_message("ERROR", "nftCountSql : ".$nftCountSql);
        $ftCount = $this->db->query($ftCountSql)->row()->ftcnt;
        $nftCount = $this->db->query($nftCountSql)->row()->nftcnt;
        $view['ftCount'] = $ftCount;
        $view['nftCount'] = $nftCount;
        //log_message("ERROR", "ftcnt : ".$ftCount);
        //log_message("ERROR", "nftcnt : ".$ftCount);
        // 2019-07-09 친구여부 추가 끝

		//고객 전체수
		$sql = "SELECT COUNT(*) AS cnt FROM cb_ab_". $this->member->item('mem_userid') ." WHERE ab_status = '1' AND length(ab_tel) >=8 ";
		//echo $sql ."<br>";
		$view['customer_cnt'] = $this->db->query($sql)->row()->cnt;

		//고객그룹 조회
		$sql = "
		SELECT
			  cg_id /* 그룹번호 */
			, cg_parent_id /* 부모번호 */
			, cg_level /* 레벨 */
			, cg_gname /* 그룹명 */
			, (CASE WHEN cg_level = 1 THEN lv1_cnt ELSE lv2_cnt END) AS cg_cnt /* 고객수 */
			, rowspan /* 라인수 */
		FROM (
			SELECT
				  a.*
				, (SELECT COUNT(*)
				FROM cb_ab_". $this->member->item('mem_userid') ." u
				LEFT JOIN cb_ab_". $this->member->item('mem_userid') ."_group b ON u.ab_id = b.aug_ab_id AND u.ab_status = '1' AND length(u.ab_tel) >=8
				WHERE b.aug_group_id IN (
					SELECT cg_id FROM cb_customer_group c WHERE c.cg_parent_id = a.cg_id AND c.cg_use_yn = 'Y'
				)) AS lv1_cnt /* 그룹1차 고객수 */
				, (SELECT COUNT(*)
				FROM cb_ab_". $this->member->item('mem_userid') ." u
				LEFT JOIN cb_ab_". $this->member->item('mem_userid') ."_group b ON u.ab_id = b.aug_ab_id AND u.ab_status = '1' AND length(u.ab_tel) >=8
				WHERE a.cg_id = b.aug_group_id
				) AS lv2_cnt /* 그룹2차 고객수 */
				, (SELECT COUNT(*) FROM cb_customer_group c WHERE c.cg_parent_id = a.cg_parent_id AND c.cg_use_yn = 'Y') AS rowspan /* 라인수 */
			FROM cb_customer_group a
			WHERE cg_mem_id = '". $this->member->item('mem_id') ."'
			AND cg_use_yn = 'Y'
		) t
		WHERE (CASE WHEN cg_level = 1 THEN lv1_cnt ELSE lv2_cnt END) > 0 /* 등록된 고객이 있는 경우 */
		ORDER BY cg_sort ASC ";
		//echo $sql ."<br>";
		$view['customer_group'] = $this->db->query($sql)->result();

		//포스고객 전체수 2021-02-02
		$sql = "SELECT COUNT(*) AS cnt FROM cb_tel_pos WHERE ab_mem_id = '". $this->member->item('mem_id') ."' AND length(ab_tel) >=8 ";
		//echo $sql ."<br>";
		$view['pos_user_cnt'] = $this->db->query($sql)->row()->cnt;

    $sql = "SELECT mrg_recommend_mem_id FROM cb_member_register WHERE mem_id ='".$this->member->item("mem_id")."'";
    $view['parent_id'] = $this->db->query($sql)->row()->mrg_recommend_mem_id;
    //발신번호 조회
    //ib플래그
		$view['rs1'] = $this->db->query("select A.*, B.mem_linkbtn_name from (select * from ".config_item('ib_profile')." where spf_mem_id=? and spf_use='Y' and spf_status='A' order by spf_appr_datetime desc limit 1) as A Left join cb_member B ON A.spf_mem_id = B.mem_id", array($this->member->item('mem_id')))->row();

		$sms_callback = $view['rs1']->spf_sms_callback;
    $sql = "select count(1) as cnt from cb_send_tel_no_list where mem_id = '".$this->member->item('mem_id')."' and send_tel_no = '".$sms_callback."' and use_flag = 'Y' and auth_flag = 'O'";
        log_message("ERROR",  $_SERVER['REQUEST_URI'] ." > Query : ". $sql);
		// echo $_SERVER['REQUEST_URI'] ." > Query : ". $sql ."<br>";
		$view['sendtelauth'] = $this->db->query($sql)->row()->cnt;
        /*
         * 2019.01.24 SSG
         * 관리자 DHN 으로 들어 왔을경우 "특정 기능 및 항목"을 보이게 하기 위해
         * session 변수를 이용함.
         */
        if($this->member->item('mem_id') != '3') {
            if($this->session->userdata('login_stack')) {
                $login_stack = $this->session->userdata('login_stack');
                //log_message("ERROR",'Login Stack : '. $login_stack[0]);
                if($login_stack[0] == '3') {
                    $view['isManager'] = 'Y';
                }
            }
        } else {
            $view['isManager'] = 'Y';
        }
		//log_message("ERROR", "isManager : ".$view['isManager']);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        //$link_talk = ($this->nft == 'NFT') ? 'nft_talk' : 'talk_adv';

        $layoutconfig = array(
            'path' => 'biz/dhnsender/send',
            'layout' => 'layout',
            'skin' => $skin,
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

    public function nft_talk() {
        $this->nft = "NFT";
        $this->index();
    }

    public function load_customer()
    {
        $cnt = $this->Biz_dhn_model->get_table_count("cb_ab_".$this->member->item('mem_userid'), "ab_status<>'0'");
        header('Content-Type: application/json');
        echo '{"customer_count":'.$cnt.'}';
        exit;
    }

    //알림톡 내용저장
	public function save_adv_msg() {
		$code = "0";
		$message = "success";
		$mem_id = $this->member->item('mem_id'); //회원번호
		$temp_id = $this->input->post('temp_id'); //템플릿번호
		$use_yn = ($this->input->post("use_yn")) ? $this->input->post("use_yn") : "Y"; //사용여부
		if($use_yn == "N"){
			//알림톡 내용 기존 임시저장 내역 삭제
			$sql = "delete from cb_alim_ad_msg where mem_id = '". $mem_id ."' and use_yn = 'N' ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 알림톡 내용 기존 임시저장 내역 삭제 : ". $sql);
			$this->db->query($sql);
		}

		//알림톡 내용 저장
		$admsg = array();
		$admsg['mem_id'] = $mem_id; //회원번호
		$admsg['temp_id'] = $temp_id; //템플릿번호
		$admsg['url_link'] = $this->input->post('umsurl'); //UMS 링크주소
		$admsg['ums'] = $this->input->post('ums_cont'); //UMS 내용
		$admsg['temp_cont'] = $this->input->post('temp_cont'); //알림톡 내용
		$admsg['lms_msg'] = $this->input->post('lms_msg'); //2차문자내용
		$admsg['use_yn'] = $use_yn; //사용여부
		$this->db->insert("alim_ad_msg", $admsg);
		$idx = $this->db->insert_id();
		if($idx == ""){
			$code = "101";
			$message = "알림톡 내용 저장 오류";
		}

		//알림톡 변수 내용 저장
		$vars = $this->input->post('vars'); //알림톡 변수 내용
		if(!empty($vars)){
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > vars : ". $vars);
			foreach($vars as $v) {
				$var = array();
				$var['adv_idx'] = $idx;
				$var['var_text'] = $v;
				$this->db->insert('alim_ad_msg_var', $var);
			}
		}else{
			$code = "102";
			$message = "알림톡 변수 저장 오류";
		}

		header('Content-Type: application/json');
		//echo '{"code":"success"}';
		echo '{"code":"'. $code .'", "message":"'. $message .'"}';
	}

    //알림톡 불러오기
	public function msg_save_list() {
        if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		$del_ids = $this->input->post('del_ids');

        if($del_ids && count($del_ids) > 0 && $del_ids[0]) {
            foreach($del_ids as $did) {
                $this->db->delete("alim_ad_msg", array("id"=>$did));
                $this->db->delete("alim_ad_msg_var", array("adv_idx"=>$did));
            }
        }
        $sql = "select count(*) as cnt from cb_member_template_group where mtg_mem_id = '".$this->member->item("mem_id")."' and mtg_profile = 'A'";
        $temp_cnt_a = $this->db->query($sql)->row()->cnt;

        $sql = "select count(*) as cnt from cb_member_template_group where mtg_mem_id = '".$this->member->item("mem_id")."' and mtg_profile = 'B'";
        $temp_cnt_b = $this->db->query($sql)->row()->cnt;

        $view = array();
        $view['view'] = array();
        $view['perpage'] = 6;
        $view['param']['search_msg'] = $this->input->post("search_msg");
        $view['param']['page'] = ($this->input->post("page")) ? $this->input->post("page") : 1;

        $where = " mem_id = ".$this->member->item('mem_id')." ";
        $param = array();

        if($view['param']['search_msg']) {
            $where .= " and temp_cont like ? ";
            array_push($param, '%'.$view['param']['search_msg'].'%');
        }


		//2020-11-12
		if($this->input->post("use_yn") == "N") {
            $where .= " and use_yn = 'N' ";
        }else{
			$where .= " and use_yn = 'Y' ";
		}

        if($temp_cnt_a==0&&$temp_cnt_b==0){

        }else{
            if($temp_cnt_a!=0&&$temp_cnt_b!=0){

            }else{
                if($temp_cnt_a!=0){
                    $where .= " and tpl_profile_key = '".$this->tmp_profile."' ";
                }
                if($temp_cnt_b!=0){
                    $where .= " and tpl_profile_key = '".$this->tmp_profile2."' ";
                }
            }
        }

		// 2021-09-02 이수환 알림톡 메시지 저장만 불러오기 기능 추가, Query문 조건값에 텍스트 타입일 경우 추가.
		$where .= " and b.tpl_emphasizetype <> 'IMAGE' ";

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';

        // 2021-09-02 이수환 알림톡 메시지 저장만 불러오기 기능 추가, total_rows값 조회부분 수정.
        //ib플래그
        $sql = "
			select count(*) cnt
			from cb_alim_ad_msg a
			LEFT OUTER JOIN ".config_item('ib_template')." b ON a.temp_id = b.tpl_id
			where ".$where;
        $page_cfg['total_rows'] = $this->db->query($sql, $param)->row()->cnt;
        // $page_cfg['total_rows'] = $this->Biz_dhn_model->get_table_count("cb_alim_ad_msg", $where, $param);

        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = str_replace('open_page','open_page_user_lms_msg',$this->pagination->create_links());
        //ib플래그 + 그룹추가
        $sql = "
			select
				  a.*
				, DATE_FORMAT(reg_date, '%y.%m.%d %H:%i') as reg_dt /* 등록일자 */
				, b.tpl_button /* 버튼정보 */
			from cb_alim_ad_msg a
			LEFT OUTER JOIN ".config_item('ib_template')." b ON a.temp_id = b.tpl_id
			where ".$where." order by id desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //echo $sql ."<br>";
		//log_message("ERROR", $sql);
        $view['list'] = $this->db->query($sql, $param)->result();
        $view['view']['canonical'] = site_url();

        $this->load->view('biz/dhnsender/adv_save_msg_list',$view);
    }

	//광고성 알림톡 조회
	public function ajax_adv_template_data_ori(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 광고성 알림톡 조회");
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}

		//광고성 알림톡 리스트
        //ib플래그 + 그룹추가
		$sql = "
		SELECT
			  a.tpl_id /* 템플릿번호 */
			, a.tpl_contents /* 템플릿 내용 */
			, a.tpl_button /* 템플릿 버튼 */
			, a.tpl_premium_yn /* 템플릿 프리미엄 여부 */
		FROM ".config_item('ib_template')." a
		INNER JOIN ".config_item('ib_profile')." b ON a.tpl_profile_key=b.spf_key AND b.spf_use='Y'
		WHERE ( a.tpl_profile_key = '". $this->tmp_profile ."' OR a.tpl_profile_key = '". $this->tmp_profile2 ."')
		AND a.tpl_use = 'Y'
		AND a.tpl_adv_yn = 'Y'
        AND a.tpl_emphasizetype <> 'IMAGE'
		ORDER BY a.tpl_adv_sort DESC, tpl_id DESC ";
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
		$result = $this->db->query($sql)->result();
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}

	//광고성 알림톡 조회 - 202109-19 수정
	public function ajax_adv_template_data(){
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 광고성 알림톡 조회");
	    if($this->member->is_member() == false) { //로그인이 안된 경우
	        redirect('login');
	    }

	    $category_ids = ($this->input->post("category_ids")) ? $this->input->post("category_ids") : 'ALL';
	    $tpl_btn_cnts = ($this->input->post("tpl_btn_cnts") || $this->input->post("tpl_btn_cnts") == '0') ? $this->input->post("tpl_btn_cnts") : 'ALL';

	    $where = "";
	    if ($category_ids != 'ALL') {
	        $where .= " AND a.tci_category_id in (".$category_ids.") ";
	    }
	    if ($tpl_btn_cnts != 'ALL') {
	        $where .= " AND a.tci_btn_cnt in (".$tpl_btn_cnts.") "; ;
	    }
        //ib플래그 + 그룹추가
        $sql = "select b.mtg_useyn as mtg_useyn1, c.mtg_useyn as mtg_useyn2 from cb_member a left join cb_member_template_group b ON a.mem_id = b.mtg_mem_id and b.mtg_profile = 'A' LEFT JOIN cb_member_template_group c ON a.mem_id = c.mtg_mem_id and c.mtg_profile = 'B'  where a.mem_id = '".$this->member->item("mem_id")."' ";
        $temp_group = $this->db->query($sql)->row();
        $whereplus = "WHERE 1=1 ";
        if(empty($temp_group->mtg_useyn1)&&empty($temp_group->mtg_useyn2)){
            $whereplus .= " AND (cwtd.tpl_profile_key in ('".$this->tmp_profile."', '".$this->tmp_profile2."')) ";
        }else{
            if(!empty($temp_group->mtg_useyn1)){
                if(!empty($temp_group->mtg_useyn2)){
                    $whereplus .= " AND (cwtd.tpl_profile_key in ('".$this->tmp_profile."', '".$this->tmp_profile2."')) ";
                }else{
                    $whereplus .= " AND cwtd.tpl_profile_key = '".$this->tmp_profile."'  AND cwtd.tpl_profile_key != '".$this->tmp_profile2."' ";
                }
            }else{
                if(!empty($temp_group->mtg_useyn2)){
                    $whereplus .= " AND cwtd.tpl_profile_key = '".$this->tmp_profile2."'  AND cwtd.tpl_profile_key != '".$this->tmp_profile."' ";
                }else{
                    $whereplus .= " AND cwtd.tpl_profile_key != '".$this->tmp_profile2."'  AND cwtd.tpl_profile_key != '".$this->tmp_profile."' ";
                }
            }
        }



	    //광고성 알림톡 리스트
        //ib플래그
	    $sql = "
            SELECT cwtd.*
            FROM ".config_item('ib_template')." cwtd
            	JOIN (SELECT DISTINCT tci_tem_id as tpl_id
            			FROM ".config_item('ib_categoryid')." a
            			WHERE a.tci_type = 'T' ".$where;
        //ib플래그 + 그룹추가
	    $sql .= "
            		) as sd
            		ON cwtd.tpl_id = sd.tpl_id AND cwtd.tpl_adv_yn = 'Y' ".$whereplus."
            ORDER BY cwtd.tpl_adv_sort DESC, cwtd.tpl_id DESC";
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
	    $result = $this->db->query($sql)->result();
	    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);

	    $json = json_encode($result,JSON_UNESCAPED_UNICODE);
	    header('Content-Type: application/json');
	    echo $json;
	}

	//광고성 알림톡 정렬순서 변경
	public function ajax_adv_template_sort(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 광고성 알림톡 정렬순서 변경");
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$seq = $this->input->post('seq'); //템플릿번호 순번
		$sort = count($seq); //정렬순번
		for($row = 0; $row < count($seq); $row ++) {
            //ib플래그
			$sql = "update ".config_item('ib_template')." set tpl_adv_sort = '". $sort ."' where tpl_id = '". $seq[$row] ."'";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
	        $this->db->query($sql);
			$sort--;
		}
	}

}
?>
