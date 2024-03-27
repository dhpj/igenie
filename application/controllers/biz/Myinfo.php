<?php
class Myinfo extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Board', 'Biz', 'Biz_dhn');

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

    private $upload_path; //업로드 경로
    private $upload_max_size; //파일 제한 사이지

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring', 'funn'));

        /* 이미지 등록될 기본 디렉토리 설정 */
        $this->upload_path =  config_item('uploads_dir') .'/member_photo/'; //스마트전단 업로드 경로

        /* 업로드할 용량지정 kb단위 */
        $this->upload_max_size = 10 * 1024; //파일 제한 사이지(kb 단위) (10MB)

        /* 기본 폴더가 없다면 생성을 위해 실행 */
        $this->funn->create_upload_dir($this->upload_path);

        /* 년 폴더가 없다면 생성을 위해 실행 */
        $this->funn->create_upload_dir($this->upload_path . date("Y") ."/");

        /* 월 폴더가 없다면 생성을 위해 실행 */
        $this->funn->create_upload_dir($this->upload_path . date("Y") ."/". date("m") ."/");

        $this->upload_path = $this->upload_path . date("Y") ."/". date("m") ."/";
        //echo "upload_path : ". $this->upload_path;
    }

    public function index()
    {
    }

    public function info()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        //회원정보
        $view['rs'] = $this->Biz_dhn_model->get_member($this->member->item('mem_userid'), true);

        //대분류 리스트
        $sql = "SELECT id, code, description FROM cb_images_category WHERE useyn = 'Y' AND parent_id IS NULL ORDER BY id ASC";
        //echo $sql ."<br>";
        $view['category_list'] = $this->db->query($sql)->result();

        //주문설정 정보 2021-03-25
        $sql = "select * from cb_orders_setting where mem_id = '". $this->member->item('mem_id') ."' ";
        $view['os'] = $this->db->query($sql)->row();

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/myinfo',
            'layout' => 'layout',
            'skin' => 'info',
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

    public function password()
    {
        $mb_passwd = $this->input->post('mb_passwd');
        header('Content-Type: application/json');
        echo '{"result": '.((password_verify($mb_passwd, $this->member->item('mem_password'))) ? 'true' : 'false').'}';
    }

    //메장정보 > 계정 정보 수정
    public function modify(){

        $mb_passwd = $this->input->post('mb_passwd');
        if(!password_verify($mb_passwd, $this->member->item('mem_password'))) { show_404(); exit; }

        $new_mb_passwd = $this->input->post('new_mb_passwd');
        //$bisness_file = $this->input->post('bisness_file');
        $admin_name = $this->input->post('admin_name');
        $admin_tel = $this->input->post('admin_tel');
        $emp_tel = $this->input->post('emp_tel');
        $admin_email = $this->input->post('admin_email');
        $phn_free = $this->input->post('phn_free');
        $mem_2nd_send = $this->input->post('mem_2nd_send');

        $data = array();
        if($new_mb_passwd) {
            if ( ! function_exists('password_hash')) { $this->load->helper('password'); }
            $data['mem_password'] = password_hash($new_mb_passwd, PASSWORD_BCRYPT);
            $data['mem_shop_code'] = $this->funn->encrypt($new_mb_passwd); //2020-09-15
        }
        $data['mem_nickname'] = $admin_name;
        $data['mem_email'] = $admin_email;
        $data['mem_phone'] = $admin_tel;
        $data['mem_emp_phone'] = $emp_tel;
        $data['mem_alim_btn_url1'] = $this->input->post('mem_alim_btn_url1'); //알림톡 '행사보기' 버튼 URL
        $data['mem_alim_btn_url2'] = $this->input->post('mem_alim_btn_url2'); //알림톡 '전단보기' 버튼 URL
        $data['mem_alim_btn_url3'] = $this->input->post('mem_alim_btn_url3'); //알림톡 '주문하기' 버튼 URL
        $data['mem_stmall_alim_phn'] = $this->input->post('mem_stmall_alim_phn'); //주문 알림 수신 번호(구분자 콤마) 2021-02-25
        $data['mem_img_lib_category1'] = $this->input->post('mem_img_lib_category1'); //주문 알림 수신 번호(구분자 콤마) 2021-02-25
        $data['mem_img_lib_category2'] = $this->input->post('mem_img_lib_category2'); //주문 알림 수신 번호(구분자 콤마) 2021-02-25

        $data['mem_is_pay'] = $this->input->post('hdn_is_pay'); //지역화폐 사용유무 2021-06-16

        $data['mem_is_account'] = $this->input->post('hdn_is_account'); //계최이체 사용유무 2021-07-29

        $data['account_info'] = $this->input->post('hdn_is_account_info');// 계좌정보 저장 2021-07-30

        $data['mem_is_stan'] = $this->input->post('hdn_is_stan'); //규격검색 사용유무 2021-08-03

        //주문 배달 가능금액 설정 2021-03-25
        $sql = "select count(*) as cnt from cb_orders_setting where mem_id = '". $this->member->item("mem_id") ."' ";
        $os_cnt = $this->db->query($sql)->row()->cnt;
        $setting = array();
        $setting["min_amt"] = $this->input->post("min_amt"); //주문 최소금액
        $setting["delivery_amt"] = $this->input->post("delivery_amt"); //배달비
        $setting["free_delivery_amt"] = $this->input->post("free_delivery_amt"); //무료배달 최소금액
        if($os_cnt == 0){ //등록
            $setting["mem_id"] = $this->member->item("mem_id"); //회원번호
            $rtn = $this->db->insert("cb_orders_setting", $setting); //데이타 등록
        }else{ //수정
            $where = array();
            $where["mem_id"] = $this->member->item("mem_id"); //회원번호
            $rtn = $this->db->update("cb_orders_setting", $setting, $where); //데이타 수정
        }

        $db = $this->load->database("218g", TRUE);
        $sql = "select count(1) as cnt from cb_orders_setting where mem_id = '".$this->member->item("mem_id")."' and os_from = '133g'";
        $setcnt = $db->query($sql)->row()->cnt;

        if($setcnt==0){
            $setting["mem_id"] = $this->member->item("mem_id"); //회원번호
            $setting["os_from"] = "133g";
            $rtn2 = $db->insert("cb_orders_setting", $setting); //데이타 등록
        }else{
            $where = array();
            $where["mem_id"] = $this->member->item("mem_id"); //회원번호
            $where["os_from"] = "133g"; //회원번호
            $rtn2 = $db->update("cb_orders_setting", $setting, $where);
        }

        if($_SERVER['REMOTE_ADDR']==$this->config->config['developer_ip'] || $this->config->config['use_multi_agent'])
        { $data['mem_2nd_send'] = $mem_2nd_send; }

        if(isset($_FILES)) {
            $this->load->library('upload');
            if ($this->cbconfig->item('use_member_photo')
                && $this->cbconfig->item('member_photo_width') > 0
                && $this->cbconfig->item('member_photo_height') > 0) {
                    if (isset($_FILES['bisness_file']) && isset($_FILES['bisness_file']['name']) && $_FILES['bisness_file']['name']) {
                        $upload_path = config_item('uploads_dir') . '/member_photo/';
                        if (is_dir($upload_path) === false) {
                            mkdir($upload_path, 0707);
                            $file = $upload_path . 'index.php';
                            $f = @fopen($file, 'w');
                            @fwrite($f, '');
                            @fclose($f);
                            @chmod($file, 0644);
                        }
                        $upload_path .= cdate('Y') . '/';
                        if (is_dir($upload_path) === false) {
                            mkdir($upload_path, 0707);
                            $file = $upload_path . 'index.php';
                            $f = @fopen($file, 'w');
                            @fwrite($f, '');
                            @fclose($f);
                            @chmod($file, 0644);
                        }
                        $upload_path .= cdate('m') . '/';
                        if (is_dir($upload_path) === false) {
                            mkdir($upload_path, 0707);
                            $file = $upload_path . 'index.php';
                            $f = @fopen($file, 'w');
                            @fwrite($f, '');
                            @fclose($f);
                            @chmod($file, 0644);
                        }

                        $uploadconfig = '';
                        $uploadconfig['upload_path'] = $upload_path;
                        $uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif';
                        $uploadconfig['max_size'] = '2000';
                        $uploadconfig['max_width'] = '1000';
                        $uploadconfig['max_height'] = '2000';
                        $uploadconfig['encrypt_name'] = true;

                        $this->upload->initialize($uploadconfig);

                        if ($this->upload->do_upload('bisness_file')) {
                            $img = $this->upload->data();
                            $data['mem_photo'] = cdate('Y') . '/' . cdate('m') . '/' . $img['file_name'];
                        } else {
                            $file_error = $this->upload->display_errors();
                        }
                    }
                }
        }
        $this->db->update("cb_member", $data, array("mem_userid"=>$this->member->item('mem_userid')));
        if ($this->db->error()['code'] > 0) {
            echo "<script type='text/javascript'>alert('저장 오류입니다.');history.back();</script>";
        } else {
            $this->db->query("update cb_wt_member_addon set mad_free_hp=? where mad_mem_id=?", array($phn_free, $this->member->item('mem_id')));
            echo "<script type='text/javascript'>document.location.href='/biz/myinfo/info';</script>";
        }
    }

    //메장정보 > 매장 정보 수정
    public function shop_modify(){
        $db = $this->load->database("218g", TRUE);

        $data = array();
        $data['mem_shop_img'] = $this->input->post('imgpath_shop'); //매장 사진 경로
        $data['mem_shop_intro'] = $this->input->post('mem_shop_intro'); //매장 소개글
        $data['mem_notice'] = $this->input->post('mem_notice'); //매장 공지사항
        $data['mem_shop_time'] = $this->input->post('mem_shop_time'); //매장 운영시간
        $data['mem_shop_holiday'] = $this->input->post('mem_shop_holiday'); //매장 휴무일
        $data['mem_shop_phone'] = $this->input->post('mem_shop_phone'); //매장 전화번호
        $data['mem_shop_addr'] = $this->input->post('mem_shop_addr'); //매장 주소
        $data['mem_map_node'] = $this->input->post('mem_map_node'); //다음지도 노드
        $data['mem_map_key'] = $this->input->post('mem_map_key'); //다음지도 Key

        $data['mem_cart_info'] = $this->input->post('mem_cart_info'); //장바구니 하단 정보
        $data['mem_shop_notice'] = $this->input->post('mem_shop_notice'); //스마트홈 공지사항 에디터 2022-08-18 윤재박
        $data['mem_shop_template'] = $this->input->post('smarthome_type'); //스마트홈 디자인 타입 2022-08-18 윤재박

        $this->db->update("cb_member", $data, array("mem_userid"=>$this->member->item('mem_userid')));

        $sql = "select count(1) as cnt from cb_shop_member_data where smd_mem_id = '".$this->member->item("mem_id")."' and smd_from = '133g'";
        $shopcnt = $db->query($sql)->row()->cnt;

        if($shopcnt == 0){
            $sql = "select spf_friend from cb_wt_send_profile_dhn where spf_mem_id = '".$this->member->item("mem_id")."' limit 1";
            $spf_friend = $this->db->query($sql)->row()->spf_friend;
            $mem_data = array();
            $mem_data["smd_mem_id"] = $this->member->item("mem_id");
            $mem_data["smd_mem_userid"] = $this->member->item("mem_userid");
            $mem_data["smd_mem_username"] = $this->member->item("mem_username");
            $mem_data["smd_mem_shop_img"] = $this->input->post('imgpath_shop');
            $mem_data["smd_mem_shop_intro"] = $this->input->post('mem_shop_intro');
            $mem_data["smd_mem_notice"] = $this->input->post('mem_notice');
            $mem_data["smd_mem_shop_time"] = $this->input->post('mem_shop_time');
            $mem_data["smd_mem_shop_holiday"] = $this->input->post('mem_shop_holiday');
            $mem_data["smd_mem_shop_phone"] = $this->input->post('mem_shop_phone');
            $mem_data["smd_mem_shop_addr"] = $this->input->post('mem_shop_addr');
            $mem_data["smd_mem_shop_notice"] = $this->input->post('mem_shop_notice');
            $mem_data["smd_mem_shop_template"] = $this->input->post('smarthome_type');
            $mem_data["smd_spf_friend"] = $spf_friend;
            $mem_data["smd_mem_stmall_yn"] = $this->member->item("mem_stmall_yn");
            $mem_data["smd_mem_stmall_alim_phn"] = $this->member->item("mem_stmall_alim_phn");
            $mem_data["smd_mem_cart_info"] = $this->input->post('mem_cart_info');
            $mem_data["smd_from"] = "133g";
            $db->insert("cb_shop_member_data", $mem_data); //데이타 추가
        }else{
            $mem_data = array();
            $mem_data['smd_mem_shop_img'] = $this->input->post('imgpath_shop'); //매장 사진 경로
            $mem_data['smd_mem_shop_intro'] = $this->input->post('mem_shop_intro'); //매장 소개글
            $mem_data['smd_mem_notice'] = $this->input->post('mem_notice'); //매장 공지사항
            $mem_data['smd_mem_shop_time'] = $this->input->post('mem_shop_time'); //매장 운영시간
            $mem_data['smd_mem_shop_holiday'] = $this->input->post('mem_shop_holiday'); //매장 휴무일
            $mem_data['smd_mem_shop_phone'] = $this->input->post('mem_shop_phone'); //매장 전화번호
            $mem_data['smd_mem_shop_addr'] = $this->input->post('mem_shop_addr'); //매장 주소
            $mem_data['smd_mem_cart_info'] = $this->input->post('mem_cart_info'); //장바구니 하단 정보
            $mem_data['smd_mem_shop_notice'] = $this->input->post('mem_shop_notice'); //스마트홈 공지사항 에디터 2022-08-18 윤재박
            $mem_data['smd_mem_shop_template'] = $this->input->post('smarthome_type'); //스마트홈 디자인 타입 2022-08-18 윤재박
            $db->update("cb_shop_member_data", $mem_data, array("smd_mem_userid"=>$this->member->item('mem_userid'), "smd_from"=>"133g"));
        }

        if ($this->db->error()['code'] > 0) {
            echo "<script type='text/javascript'>alert('저장 오류입니다.');history.back();</script>";
        } else {
            echo "<script type='text/javascript'>document.location.href='/biz/myinfo/info';</script>";
        }
    }

    public function my_price()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        
        log_message('error', $this->session->userdata('login_stack'));
        
        $view['second_send_flag'] = false;
        if ($this->member->item('mem_level') >= 50 || !empty($this->session->userdata('login_stack'))){
            $view['second_send_flag'] = true;
        }
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['rs'] = $this->Biz_model->get_member($this->member->item('mem_userid'), true);

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/myinfo',
            'layout' => 'layout',
            'skin' => 'my_price',
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

    public function charge()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/myinfo',
            'layout' => 'layout',
            'skin' => 'charge',
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

    public function charge_history()
    {
        $this->Biz_model->make_user_deposit_table($this->member->item('mem_userid'));
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['view']['canonical'] = site_url();

        $skin = "charge_history";
        $add = $this->input->get("add");
        $view['add'] = $add;
        if($add != "") $skin .= $add;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['set_date'] = $this->input->post('set_date');
        $term = $this->Biz_model->set_Term($view['param']['set_date']);
        $view['param']['endDate'] = $term->endDate;
        $view['param']['startDate'] = $term->startDate;
        $param = array($view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59");

        //전체건수
        $view['total_rows'] = $this->Biz_model->get_table_count("cb_amt_".$this->member->item('mem_userid'), "amt_kind<'9' and amt_datetime between ? and ?", $param);

        //현황
        $sql = "
		select *
		from cb_amt_".$this->member->item('mem_userid')."
		where amt_kind<'9' and amt_datetime between ? and ? order by amt_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //echo $sql ."<br>param[startDate] : ". $param[0] .", param[endDate] : ". $param[1] ."<br>";
        //$view['list'] = $this->db->query($sql, $param)->result();

        //검색조건
        $sch = "";
        $sch .= "AND amt_kind < '9' ";
        $sch .= "AND amt_datetime BETWEEN '". $view['param']['startDate'] ." 00:00:00' AND '". $view['param']['endDate'] ." 23:59:59' ";

        //전체건수
        $sql = "
		SELECT SUM(cnt) AS cnt
		FROM (
			SELECT
				1 AS cnt
			FROM cb_amt_".$this->member->item('mem_userid')."
			WHERE 1=1 ". $sch ."
			GROUP BY amt_kind, DATE_FORMAT(amt_datetime, '%Y-%m-%d'), amt_memo
		) t ";
        $view['total_rows'] = $this->db->query($sql)->row()->cnt;

        //현황
        $sql = "
		SELECT
			  DATE_FORMAT(amt_datetime, '%Y-%m-%d') AS amt_datetime /* 충전 일시 */
			, amt_kind /* 구분(1.충전 금액, 3.환불 금액) */
			, amt_memo /* 비고 */
			, sum(amt_amount) AS amt_amount /* 금액 */
		FROM cb_amt_".$this->member->item('mem_userid')."
		WHERE 1=1 ". $sch ."
		GROUP BY amt_kind, DATE_FORMAT(amt_datetime, '%Y-%m-%d'), amt_memo
		ORDER BY amt_datetime DESC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql, $param)->result();

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/myinfo',
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

    public function refund()
    {
        if($this->input->post('refund_amount_req')) { $this->refund_save(); return; }
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        // $view['total_deposit'] = $this->Biz_dhn_model->getAbleCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
        $coin = $this->Biz_dhn_model->getAbleCoin_new($this->member->item('mem_id'), $this->member->item('mem_userid'));
        $view['total_deposit'] = $coin['mCoin'];

        $sql = "SELECT sum(dep_deposit) as sum_event from cb_deposit where (dep_content = '예치금 이벤트 (서비스입금)' or dep_content = '예치금 이벤트 (서비스수동입금)') and mem_id = '".$this->member->item("mem_id")."'";
        $view['total_event'] = $this->db->query($sql)->row()->sum_event;
        //log_message('error', 'coin : '.$view['view']['total_deposit']);
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/myinfo',
            'layout' => 'layout',
            'skin' => 'refund',
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

    public function refund_save()
    {
         $total_deposit = $this->Biz_dhn_model->getAbleCoin($this->member->item('mem_id'), $this->member->item('mem_userid'));
        //if($this->member->item('mem_deposit') < $this->input->post('refund_amount_req')) { show_404(); exit; }
        if($total_deposit < $this->input->post('refund_amount_req')) { show_404(); exit; }
        if(!password_verify($this->input->post('refund_pw'), $this->member->item('mem_password'))) {
            header('Content-Type: application/json');
            echo '{"result":false,"message": "비밀번호가 일치하지 않습니다."}';
            exit;
        }
        /* 잔액에서 환불신청한 금액을 제외시켜야 함 */
        $this->ref_mem_id = $this->member->item('mem_id');
        $this->ref_datetime = cdate('Y-m-d H:i:s');
        $this->ref_amount = $this->input->post('refund_amount_req');
        $this->ref_appr_amount = $this->input->post('refund_amount');
        $this->ref_bank_name = $this->input->post('bank_nm');
        $this->ref_account = $this->input->post('bank_account');
        $this->ref_acc_master = $this->input->post('bank_depositor');
        $this->ref_memo = $this->input->post('refund_resn');
        $this->ref_tel = $this->input->post('contact');
        $this->ref_stat = "-";
        if(isset($_FILES)) {
            $this->load->library('upload');
            if ($this->cbconfig->item('use_member_photo')
                && $this->cbconfig->item('member_photo_width') > 0
                && $this->cbconfig->item('member_photo_height') > 0) {
                    if (isset($_FILES['file_attach']) && isset($_FILES['file_attach']['name']) && $_FILES['file_attach']['name']) {
                        $upload_path = config_item('uploads_dir') . '/bank_depositor/';
                        if (is_dir($upload_path) === false) {
                            mkdir($upload_path, 0707);
                            $file = $upload_path . 'index.php';
                            $f = @fopen($file, 'w');
                            @fwrite($f, '');
                            @fclose($f);
                            @chmod($file, 0644);
                        }
                        $upload_path .= cdate('Y') . '/';
                        if (is_dir($upload_path) === false) {
                            mkdir($upload_path, 0707);
                            $file = $upload_path . 'index.php';
                            $f = @fopen($file, 'w');
                            @fwrite($f, '');
                            @fclose($f);
                            @chmod($file, 0644);
                        }
                        $upload_path .= cdate('m') . '/';
                        if (is_dir($upload_path) === false) {
                            mkdir($upload_path, 0707);
                            $file = $upload_path . 'index.php';
                            $f = @fopen($file, 'w');
                            @fwrite($f, '');
                            @fclose($f);
                            @chmod($file, 0644);
                        }

                        $uploadconfig = '';
                        $uploadconfig['upload_path'] = $upload_path;
                        $uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif';
                        $uploadconfig['max_size'] = '2000';
                        $uploadconfig['max_width'] = '1000';
                        $uploadconfig['max_height'] = '1000';
                        $uploadconfig['encrypt_name'] = true;

                        $this->upload->initialize($uploadconfig);

                        if ($this->upload->do_upload('file_attach')) {
                            $img = $this->upload->data();
                            $this->ref_sheet = cdate('Y') . '/' . cdate('m') . '/' . $img['file_name'];
                        } else {
                            $file_error = $this->upload->display_errors();

                        }
                    }
                }
        }
        $this->db->insert("cb_wt_refund", $this);
        header('Content-Type: application/json');
        if ($this->db->error()['code'] > 0) {
            echo '{"result":false,"message": "저장오류입니다.('.$this->db->error()['message'].')"}';
            exit;
        }
        $ref_id = $this->db->insert_id();
        $data = array();
        $data['amt_datetime'] = cdate('Y-m-d H:i:s');
        $data['amt_kind'] = "2";
        $data['amt_amount'] = $this->input->post('refund_amount_req');
        $data['amt_memo'] = "환불신청";
        $data['amt_reason'] = $ref_id;
        $this->db->insert("cb_amt_".$this->member->item('mem_userid'), $data);
        if ($this->db->error()['code'] > 0) {	// 오류시 환불 신청 취소
            $this->db->delete("cb_wt_refund", array("ref_id"=>$ref_id));
            if(file_exists(config_item('uploads_dir').'/bank_depositor/'.$this->ref_sheet)) {
                unlink(config_item('uploads_dir').'/bank_depositor/'.$this->ref_sheet);
            }
            echo '{"result":false,"message": "저장오류입니다.('.$this->db->error()['message'].')"}';
            exit;
        }
        echo '{"result":true,"message": null}';
    }

    public function use_history()
    {
        $this->Biz_model->make_user_deposit_table($this->member->item('mem_userid'));
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['view']['canonical'] = site_url();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['set_date'] = $this->input->post('set_date');
        $term = $this->Biz_model->set_Term($view['param']['set_date']);
        $view['param']['endDate'] = $term->endDate;
        $view['param']['startDate'] = $term->startDate;
        $param = array($view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59");

        //$view['total_rows'] = $this->Biz_model->get_table_count("cb_amt_".$this->member->item('mem_userid'), "amt_kind>='A' and amt_datetime between ? and ?", $param);

        $sql = "
			select *
			from cb_amt_".$this->member->item('mem_userid')."
			where amt_kind>='A' and amt_datetime between ? and ? order by amt_datetime desc, amt_kind asc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //log_message("ERROR", $sql);
        //$view['list'] = $this->db->query($sql, $param)->result();

        //검색조건
        $sch = "";
        $sch .= "AND amt_kind >= 'A' ";
        $sch .= "AND amt_datetime BETWEEN '". $view['param']['startDate'] ." 00:00:00' AND '". $view['param']['endDate'] ." 23:59:59' ";

        //전체건수
        $sql = "
		SELECT SUM(cnt) AS cnt
		FROM (
			SELECT
				1 AS cnt
			FROM cb_amt_".$this->member->item('mem_userid')."
			WHERE 1=1 ". $sch ."
			GROUP BY amt_kind, DATE_FORMAT(amt_datetime, '%Y-%m-%d'), amt_memo
		) t ";
        $view['total_rows'] = $this->db->query($sql)->row()->cnt;

        //현황
        $sql = "
		SELECT
			  DATE_FORMAT(amt_datetime, '%Y-%m-%d') AS amt_datetime /* 충전 일시 */
			, amt_kind /* 구분(A.알림톡, P.문자, I.친구톡(이미지)) */
			, amt_memo /* 사용내역 */
			, sum(amt_amount) AS amt_amount /* 금액 */
			, COUNT(*) AS amt_cnt /* 개수 */
		FROM cb_amt_".$this->member->item('mem_userid')."
		WHERE 1=1 ". $sch ."
		GROUP BY amt_kind, DATE_FORMAT(amt_datetime, '%Y-%m-%d'), amt_memo
		ORDER BY amt_datetime DESC
		LIMIT ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql, $param)->result();

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        $layoutconfig = array(
            'path' => 'biz/myinfo',
            'layout' => 'layout',
            'skin' => 'use_history',
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

    public function download_charge_history()
    {
        $value = json_decode($this->input->post('value'));

        // 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:D1');

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A3:D3');

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(40);

        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '충 전 내 역');

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '충전일시');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '충전금액');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '환불금액');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '비고');

        $row = 4;
        foreach($value as $val) {
            // 데이터를 읽어서 순차로 기록한다.
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->amt_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, ($val->amt_kind=="1") ? $val->amt_amount : "");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, ($val->amt_kind=="3") ? $val->amt_amount : "");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->amt_memo);
            $row++;
        }

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A4:A'.$row);
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'B4:C'.$row);
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'D4:D'.$row);

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="charge_list.xls"');
        header('Cache-Control: max-age=0');



        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
    }

    public function download_all_history()
    {
        // $value = json_decode($this->input->post('value'));
        $sql = "SELECT *
		FROM
		(
		SELECT
    t.idate
		, t.amt_datetime
		, t.amt_kind
		, t.amt_memo
		, t.amt_deduct
		, t.amt_cnt
		, t.amt_amount
    -- , t.dep_admin_memo
        , (@subtotal := @subtotal + (CASE
        WHEN FIND_IN_SET('바우처', t.amt_memo)=0 AND FIND_IN_SET('보너스', t.amt_memo)=0 AND t.amt_kind != '9'
        THEN t.amt_amount*t.amt_deduct
        ELSE 0
    END)) AS tamt
        , (@vtotal := @vtotal + (CASE
        WHEN FIND_IN_SET('바우처', t.amt_memo) OR t.amt_kind = '9'
        THEN t.amt_amount*t.amt_deduct
        ELSE 0
    END)) AS vamt
    , (@btotal := @btotal + (CASE
        WHEN FIND_IN_SET('보너스', t.amt_memo) AND t.amt_kind != '9'
        THEN t.amt_amount*t.amt_deduct
        ELSE 0
    END)) AS bamt

        FROM ( SELECT @subtotal := 0, @vtotal := 0, @btotal := 0) i
  		JOIN
  		(SELECT
  			 a.amt_datetime as idate
			 ,  DATE_FORMAT(a.amt_datetime, '%Y-%m-%d') AS amt_datetime /* 충전 일시 */
			, a.amt_kind /* 구분(A.알림톡, P.문자, I.친구톡(이미지)) */
			, a.amt_memo /* 사용내역 */
			, a.amt_amount as oriamount /* 금액 */
			, SUM(a.amt_amount) AS amt_amount
      		, a.amt_deduct
      		, COUNT(1) AS amt_cnt
          -- , aaa.dep_admin_memo
		FROM cb_amt_".$this->member->item('mem_userid')." a /*LEFT JOIN cb_deposit aaa ON (a.amt_kind = '1' OR a.amt_kind = '2') AND a.amt_reason = aaa.dep_id*/

    -- GROUP BY a.amt_kind , DATE_FORMAT(a.amt_datetime, '%Y%m%d%I'), a.amt_memo  ORDER by idate, amt_memo DESC) t  ) aa WHERE 1=1 ".$sch."
    GROUP BY a.amt_kind , DATE_FORMAT(a.amt_datetime, '%Y%m%d%I'), a.amt_memo  ORDER by null) t  ) aa WHERE 1=1 ".$sch."
		ORDER BY aa.idate DESC, aa.amt_memo ASC";
        $value = $this->db->query($sql, $param)->result();

        // 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:E1');

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A3:E3');

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

        $this->excel->getActiveSheet()->mergeCells('A1:E1');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '예 치 금 사 용 내 역');

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '사용일시');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '사용내역');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '개수');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '가격');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, '잔액');

        $row = 5;
        foreach($value as $val) {
            $minusP=$val->amt_amount;
            if($val->amt_kind!='1'&&$val->amt_kind!='3'){
              $minusP = $val->amt_amount * -1;
            }
            // 데이터를 읽어서 순차로 기록한다.
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->amt_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->amt_memo);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val->amt_cnt);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $minusP);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val->tamt);
            $row++;
        }

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A5:C'.$row);
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'E5:E'.$row);

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="use_list.xls"');
        header('Cache-Control: max-age=0');

        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
    }

    public function download_use_history()
    {
        $value = json_decode($this->input->post('value'));

        // 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:D1');

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 10),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A3:D3');

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '예 치 금 사 용 내 역');

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, '사용일시');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, '사용내역');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, '개수');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 3, '가격');

        $row = 4;
        foreach($value as $val) {
            // 데이터를 읽어서 순차로 기록한다.
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val->amt_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val->amt_memo);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, "1");
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val->amt_amount);
            $row++;
        }

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A4:C'.$row);
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => false, 'size' => 10),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'D4:D'.$row);

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="use_list.xls"');
        header('Cache-Control: max-age=0');

        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
    }

    //이미지 저장
    public function imgfile_save(){
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > _FILES[imgfile] : ". $_FILES['imgfile']['name']);
        $imgpath = "";
        if(empty($_FILES['imgfile']['name']) == false){ //이미지 업로드
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > upload_max_size : ". $this->upload_max_size);
            $img_data = $this->img_upload($this->upload_path, 'imgfile', $this->upload_max_size, "");
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > img_data : ". $img_data);
            if( is_array($img_data) && !empty($img_data) ){
                $imgpath = '/'.$this->upload_path.$img_data['file_name']; //이미지 경로
                //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > imgpath : ". $imgpath);
            }
        }
        $result = array();
        $result['code'] = '0';
        $result['imgpath'] = $imgpath;
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $json;
    }

    //이미지 업로드에 실시하는 함수
    public function img_upload($upload_img_path = null, $field_name = null, $size = null, $thumb = "" ){
        //log_message("ERROR", "maker/img_upload Start");
        if( is_dir($upload_img_path)  == false ){
            //alert('해당 디렉도리가 존재 하지 않음'); exit();
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 해당 디렉도리가 존재 하지 않음");
        }
        if ( is_null($field_name) ){
            //alert('필드값을 기입하지 않았습니다.'); exit();
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 필드값을 기입하지 않았습니다.");
        }
        if( is_null($size) || is_numeric($size) == false || $size == ''){
            $size = 10 * 1024; //파일 제한 사이지(kb 단위) (10MB)
        }
        if(is_array($_FILES) && empty($_FILES[$field_name]['name']) == false){
            $upload_img_config = '';
            $upload_img_config['upload_path'] = $upload_img_path;
            $upload_img_config['allowed_types'] = 'gif|jpg|png|jpeg';
            $upload_img_config['encrypt_name'] = true;
            $upload_img_config['max_size'] = $size;
            $upload_img_config['max_width'] = 1200;
            $upload_img_config['max_height'] = 1200;
            $this->load->library('upload',$upload_img_config);
             $this->load->library('image_api');
            $this->upload->initialize($upload_img_config);
            if ($this->upload->do_upload($field_name)) {
                //업로드 성공
                $filedata = $this->upload->data();
                //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filedata : ". $filedata .", thumb : ". $thumb);
                if($thumb != ""){ //썸네일 추가
                    $this->load->library('image_lib');
                    $this->image_lib->clear();
                    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > full_path : ". $filedata['full_path']);
                    $simgconfig = array();
                    $simgconfig['image_library'] = 'gd2';
                    $simgconfig['source_image']	= $filedata['full_path'];
                    $simgconfig['maintain_ratio'] = TRUE;
                    $simgconfig['width']	= $thumb; //썸네일 가로 사이즈
                    $simgconfig['height']= $thumb; //썸네일 가로 사이즈
                    $simgconfig['new_image'] = $filedata['file_path'].$filedata['raw_name'].'_thumb'.$filedata['file_ext'];
                    //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > new_image : ". $simgconfig['new_image']);
                    $this->image_lib->initialize($simgconfig);
                    if(!$this->image_lib->resize()) {
                        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 썸네일 이미지 저장 실패 : ". $this->upload->display_errors());
                    }else{
                        $str_path_thumb = str_replace("/var/www/igenie", "", $simgconfig['new_image']);
                        $this->image_api->api_image($str_path_thumb);
                    }
                }
                //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > filedata : ". $filedata);
                $str_path = str_replace("/var/www/igenie", "", $filedata['full_path']);
                $this->image_api->api_image($str_path);
                return $filedata;
            }else{
                //업로드 실패
                $error = $this->upload->display_errors();
                //alert($error, '/maker'); exit('');
                //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 업로드 실패");
            }
        } else {
            //alert('파일이 업로드 되지 않았습니다.'); exit('');
            //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 파일이 업로드 되지 않았습니다.");
        }
    }

}
