<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Depositlist class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>예치금>충전내역 controller 입니다.
 */
class Deposit extends CB_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = '/biz/manager/deposit';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Deposit', 'Unique_id', 'Biz');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'Deposit_model';

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
        $this->load->library(array('pagination', 'querystring', 'depositlib'));
    }

    public function index()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_depositlist_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;
        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;

        $findex = $this->input->get('findex', null, 'dep_deposit_datetime');
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->{$this->modelname}->allow_search_field = array('dep_id', 'member.mem_nickname', 'deposit.mem_realname', 'member.mem_userid', 'dep_content', 'dep_deposit', 'dep_cash', 'dep_admin_memo', 'dep_ip', 'dep_point', 'member.mem_id', 'member.mem_username'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('member.mem_id', 'dep_id', 'dep_deposit', 'dep_cash', 'dep_point'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('dep_id', 'dep_deposit_datetime'); // 정렬이 가능한 필드

        $where = array('dep_status' => 1);
        $view['view']['deptype'] = $deptype = $this->depositlib->deptype;
        if ($this->input->get('dep_from_type') && array_key_exists($this->input->get('dep_from_type'), $deptype)) {
            $where['dep_from_type'] = $this->input->get('dep_from_type');
        }
        if ($this->input->get('dep_to_type') && array_key_exists($this->input->get('dep_to_type'), $deptype)) {
            $where['dep_to_type'] = $this->input->get('dep_to_type');
        }
        if ($this->input->get('dep_pay_type')) {
            $where['dep_pay_type'] = $this->input->get('dep_pay_type');
        }

        $pluswhere = "";
        if (!empty($this->input->get('p'))&&$this->input->get('p')=='y') {
            // $where['dep_pay_type'] = 'pservice';
            // $where['dep_content like '] = "%선충전%";
            $pluswhere = "( (dep_content like '%선충전%' and dep_datetime < '2020-12-01 00:00:00') OR dep_pay_type = 'pservice' )";
        }

        $where['dep_content !='] = "예치금 이벤트 (서비스입금)";

        if($this->member->item('mem_level') == '50') {
            $exists = "EXISTS( SELECT 1 FROM cb_member_register  WHERE cb_member_register.mrg_recommend_mem_id = '".$this->member->item('mem_id')."' AND cb_member.mem_id = cb_member_register.mem_id)";
        }

        $result = $this->{$this->modelname}
        ->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword, 'OR',$exists, $pluswhere);

        /* 결제정보 추가 입력 */
        for($n=0;$n<count($result['list']);$n++) {
            $result['list'][$n]['amt_deposit'] = "0"; $result['list'][$n]['amt_point'] = "0";
            $amt_rs = $this->db->query("select * from cb_amt_".$result['list'][$n]['mem_userid']." where amt_kind='1' and amt_reason=?", array($result['list'][$n]['dep_id']));
            $myadmin = $this->db->query("select * from cb_member_register where mem_id = ".$result['list'][$n]['mem_id']." LIMIT 1")->row();
            $memadmin = $this->db->query("select * from cb_member where mem_id = ".$myadmin->mrg_recommend_mem_id." LIMIT 1")->row();
            $idatetime = $result['list'][$n]['dep_datetime'];
            $sql = "select * from cb_amt_".$result['list'][$n]['mem_userid']." where amt_kind='1' and( amt_memo = '예치금 이벤트 (서비스입금)' or amt_memo = '예치금 이벤트 (서비스입금),보너스') and amt_datetime between DATE_ADD('".$idatetime."', INTERVAL -10 SECOND) and DATE_ADD('".$idatetime."', INTERVAL 10 SECOND) LIMIT 1";
            // log_message("ERROR", "deposit > serviceplus : ". $sql);
            $serviceplus = $this->db->query($sql)->row();
            // $serviceplus = $this->db->query("select * from cb_amt_".$result['list'][$n]['mem_userid']." where amt_kind='1' and amt_memo = '예치금 이벤트 (서비스입금)' and date_format(amt_datetime,'%Y%m%d%H') = date_format(?,'%Y%m%d%H')"." LIMIT 1", array($result['list'][$n]['dep_datetime']))->row();
            if($amt_rs) {
                $amt = $amt_rs->row();
                $result['list'][$n]['amt_deposit'] = $amt->amt_deposit;
                $result['list'][$n]['amt_point'] = $amt->amt_point;
                $result['list'][$n]['myadmin'] = $memadmin->mem_username;
                if($result['list'][$n]['dep_deposit'] > 0){
                    $result['list'][$n]['service_amt'] = $serviceplus->amt_amount;
                }

            }
        }
        /*--------------------*/

        $list_num = $result['total_rows'] - ($page - 1) * $per_page;
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                $result['list'][$key]['display_name'] = display_username(
                    element('mem_userid', $val),
                    element('mem_nickname', $val),
                    element('mem_icon', $val)
                    );
                $result['list'][$key]['dep_type_display'] = element(element('dep_from_type', $val), $deptype) . '=>' . element(element('dep_to_type', $val), $deptype);
                $result['list'][$key]['num'] = $list_num--;
            }
        }
        $view['view']['data'] = $result;


        /**
         * primary key 정보를 저장합니다
         */
        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

        /*
		//페이지네이션을 생성합니다
        $config['base_url'] = $this->pagedir . '?' . $param->replace('page');
        $config['total_rows'] = $result['total_rows'];
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $view['view']['paging'] = $this->pagination->create_links();
        $view['view']['page'] = $page;
		*/
		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $result['total_rows'];
		$page_cfg['per_page'] = $per_page;
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($page);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

        /**
         * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
         */
        $search_option = array('member.mem_nickname' => '회원명', 'member.mem_username' => '업체명', 'deposit.mem_realname' => '회원실명', 'member.mem_userid' => '회원아이디', 'dep_content' => '내용', 'dep_deposit' => '예치금', 'dep_cash' => '결제금액', 'dep_point' => '포인트');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
        $view['view']['listall_url'] = $this->pagedir;
        $view['view']['write_url'] = $this->pagedir . '/write';

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array(
            'path' => 'biz/manager/deposit',
            'layout' => 'layout',
            'skin' => 'index',
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
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());//$this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 게시판 글쓰기 또는 수정 페이지를 가져오는 메소드입니다
     */
    public function write($pid = 0)
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_depositlist_write';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['deptype'] = $deptype = $this->depositlib->deptype;
        $view['view']['paymethodtype'] = $paymethodtype = $this->depositlib->paymethodtype;
        $rs = $this->Biz_model->get_partner_list(array($this->member->item('mem_id')), '1=1', 1, 1000);
        $view['rs'] = $rs->lists;
        /**
         * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
         */
        if ($pid) {
            $pid = (int) $pid;
            if (empty($pid) OR $pid < 1) {
                show_404();
            }
        }
        $primary_key = $this->{$this->modelname}->primary_key;

        /**
         * 수정 페이지일 경우 기존 데이터를 가져옵니다
         */
        $getdata = array();
        if ($pid) {
            $getdata = $this->{$this->modelname}->get_one($pid);
            $getdata['member'] = $member = $this->Member_model->get_one(element('mem_id', $getdata));
            /* 결제정보 추가 입력 */
            $getdata['amt_deposit'] = "0"; $getdata['amt_point'] = "0";
            $amt_rs = $this->db->query("select * from cb_amt_".$getdata['member']['mem_userid']." where amt_kind='1' and amt_reason=?", array($getdata['dep_id']));
            if($amt_rs) {
                $amt = $amt_rs->row();
                $getdata['amt_deposit'] = $amt->amt_deposit;
                $getdata['amt_point'] = $amt->amt_point;
            }
            /*--------------------*/
        }

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');


        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'dep_content',
                'label' => '내용',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'dep_admin_memo',
                'label' => '관리자 메모',
                'rules' => 'trim',
            ),
        );
        if ($this->input->post($primary_key)) {

        } else {
            $config[] = array(
                'field' => 'mem_userid',
                'label' => '회원아이디',
                'rules' => 'trim|required',
            );
            $config[] = array(
                'field' => 'findname',
                'label' => '아이디확인',
                'rules' => 'trim|required',
            );
            $config[] = array(
                'field' => 'dep_deposit',
                'label' => '예치금',
                'rules' => 'trim|numeric|callback__deposit_sum_check',
            );
            $config[] = array(
                'field' => 'dep_kind',
                'label' => '선택항목',
                'rules' => 'trim|required',
            );
        }
        $this->form_validation->set_rules($config);


        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            $view['view']['data'] = $getdata;

            /**
             * primary key 정보를 저장합니다
             */
            $view['view']['primary_key'] = $primary_key;

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

            /**
             * 어드민 레이아웃을 정의합니다
             */
            $writeskin = $pid ? 'modify' : 'write';
            $layoutconfig = array(
                'path' => 'biz/manager/deposit',
                'layout' => 'layout',
                'skin' => $writeskin,
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
            $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());//$this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));

        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            /**
             * 게시물을 수정하는 경우입니다
             */
            if ($this->input->post($primary_key)) {
                $updatedata = array(
                    'dep_content' => $this->input->post('dep_content', null, ''),
                    'dep_admin_memo' => $this->input->post('dep_admin_memo', null, ''),
                );
                $this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);
                $this->session->set_flashdata(
                    'message',
                    '정상적으로 수정되었습니다'
                    );
            } else {
                log_message("ERROR", $_SERVER['REQUEST_URI'] ." > dep_deposit : ". $this->input->post('dep_deposit') .", dep_kind : ". $this->input->post('dep_kind'));
                /**
                 * 게시물을 새로 입력하는 경우입니다
                 */
                $mb = $this->Member_model->get_by_userid($this->input->post('mem_userid'), 'mem_id, mem_nickname');
                $mem_id = element('mem_id', $mb);

                $sum = $this->Deposit_model->get_deposit_sum($mem_id);
                $deposit_pm = 0;
                if($this->input->post('dep_kind')=='1'||$this->input->post('dep_kind')=='4'||$this->input->post('dep_kind')=='5'||$this->input->post('dep_kind')=='9' ||$this->input->post('dep_kind')=='6'){
                    $deposit_sum = $sum + $this->input->post('dep_deposit', null, 0);
                }else if($this->input->post('dep_kind')=='2'||$this->input->post('dep_kind')=='40' ||$this->input->post('dep_kind')=='7'){ //40은 선충전차감
                    $deposit_sum = $sum - $this->input->post('dep_deposit', null, 0);
                }

                $sql = " SELECT count(*) AS cnt FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$mem_id;
                $kvd_cnt = $this->db->query($sql)->row()->cnt;
                // log_message("ERROR", $_SERVER['REQUEST_URI']."cb_kvoucher_deposit > sql >".$sql);
                if($kvd_cnt>0&&$this->input->post('dep_kind')=='9'){

                    echo "<script>";
                    echo "  alert('이미 바우처충전된 업체입니다');";

                    echo "  history.back();";
                    echo "</script>";
                    return;
                }


                $dep_id = $this->Unique_id_model->get_id($this->input->ip_address());

                // if ($this->input->post('dep_deposit') > 0) {
                //     $dep_from_type = 'service';
                //     $dep_to_type = 'deposit';
                //     $dep_pay_type = 'service';
                // } else {
                //     $dep_from_type = 'deposit';
                //     $dep_to_type = 'service';
                //     $dep_pay_type = '';
                // }
                $dep_deposit = $this->input->post('dep_deposit') ? $this->input->post('dep_deposit') : 0;

                if($this->input->post('dep_kind')=='1'||$this->input->post('dep_kind')=='4'){
                    $dep_from_type = 'service';
                    $dep_to_type = 'deposit';
                    if($this->input->post('dep_kind')=='1'){
                        $dep_pay_type = 'service';
                    }else if($this->input->post('dep_kind')=='4'){
                        $dep_pay_type = 'pservice';
                    }
                }else if($this->input->post('dep_kind')=='40'){ //40은 선충전차감
                    $dep_from_type = 'deposit';
                    $dep_to_type = 'pservice';
                    $dep_pay_type = 'pservice';
                    if($dep_deposit>0){
                        $dep_deposit = $dep_deposit*-1;
                    }
                }else if($this->input->post('dep_kind')=='2'){
                    $dep_from_type = 'deposit';
                    $dep_to_type = 'service';
                    $dep_pay_type = '';
                    if($dep_deposit>0){
                        $dep_deposit = $dep_deposit*-1;
                    }
                }else if($this->input->post('dep_kind')=='5'){
                    $dep_from_type = 'service';
                    $dep_to_type = 'deposit';
                    $dep_pay_type = 'service';
                }else if($this->input->post('dep_kind')=='9'){
                    $dep_from_type = 'voucher';
                    $dep_to_type = 'vcash';
                    $dep_pay_type = 'voucher';
                } else if($this->input->post('dep_kind')=='6'){
                    $dep_from_type = 'removecash';
                    $dep_to_type = 'deposit';
                    $dep_pay_type = 'removecash';
                } else if($this->input->post('dep_kind')=='7'){
                    $dep_from_type = 'deposit';
                    $dep_to_type = 'removededuct';
                    $dep_pay_type = 'deduction';
                    if($dep_deposit>0){
                        $dep_deposit = $dep_deposit*-1;
                    }
                }



                $insertdata = array(
                    'dep_id' => $dep_id,
                    'mem_id' => $mem_id,
                    'mem_nickname' => element('mem_nickname', $mb),
                    'dep_from_type' => $dep_from_type,
                    'dep_to_type' => $dep_to_type,
                    'dep_pay_type' => $dep_pay_type,
                    'dep_deposit_request' => $dep_deposit,
                    'dep_deposit' => $dep_deposit,
                    'dep_deposit_sum' => $deposit_sum,
                    'dep_content' => $this->input->post('dep_content', null, ''),
                    'dep_admin_memo' => $this->input->post('dep_admin_memo', null, ''),
                    'dep_datetime' => cdate('Y-m-d H:i:s'),
                    'dep_deposit_datetime' => cdate('Y-m-d H:i:s'),
                    'dep_ip' => $this->input->ip_address(),
                    'dep_useragent' => $this->agent->agent_string(),
                    'dep_status' => 1,
                );
                $this->{$this->modelname}->insert($insertdata);

                if ($this->input->post('dep_kind')=='40'){
                    $sql = "
                        SELECT
                            IFNULL(SUM(a.pdt_amount), 0) AS sum
                        FROM
                            cb_pre_deposit_tg a
                        WHERE
                            a.pdt_mem_id = " . $mem_id . "
                    ";
                    log_message("error", $sql);

                    $sum = $this->db->query($sql)->row()->sum;

                    if ($sum > 0){
                        $insertdata2 = array();
                        $insertdata2["pdt_mem_id"] = $mem_id;
                        $insertdata2["pdt_process_object"] = "dhn";
                        $insertdata2["pdt_dep_id"] = $dep_id;
                        $dep_deposit2 = $dep_deposit * (-1);
                        if ($sum > $dep_deposit2){
                            $insertdata2["pdt_amount"] = $dep_deposit2;
                        } else if ($sum <= $dep_deposit2) {
                            $insertdata2["pdt_amount"] = $sum * (-1);
                        }
                        $this->db->insert("cb_pre_deposit_tg", $insertdata2);
                    }
                }

                if($this->input->post('dep_kind')=='4' || $this->input->post('dep_kind')=='40'){ //선충전
                    $sql = " SELECT count(*) AS cnt FROM cb_pre_deposit WHERE mem_id = ".$mem_id;
                    $pre_cnt = $this->db->query($sql)->row()->cnt;
                    if($pre_cnt>0){
                        $sql = " SELECT pre_cash FROM cb_pre_deposit WHERE mem_id = ".$mem_id;
                        $pre_cash = $this->db->query($sql)->row()->pre_cash;
                        $updatedeposit = 0;
                        if($pre_cash>0){
                            $updatedeposit = $pre_cash + $dep_deposit;
                        }else{
                            $updatedeposit = $dep_deposit;
                        }
                        $insertD = array();
                        $insertD['pre_cash'] = $updatedeposit;
                        $this->db->update("cb_pre_deposit", $insertD, array("mem_id"=>$mem_id));

                    }else{
                        $insertD = array();
                        $insertD['mem_id'] = $mem_id;
                        if($this->input->post('dep_kind')=='4'){
                            $insertD['pre_cash'] = $dep_deposit;
                        }else if($this->input->post('dep_kind')=='40'){
                            $insertD['pre_cash'] = $dep_deposit*-1;
                        }
                        $this->db->insert("cb_pre_deposit", $insertD);
                    }
                }

                if($this->input->post('dep_kind')=='9'){

                    // if($kvd_cnt>0){
                        // $sql = " SELECT kvd_cash FROM cb_kvoucher_deposit WHERE kvd_mem_id = ".$mem_id;
                        // $kvd_cash = $this->db->query($sql)->row()->kvd_cash;
                        // $updatedeposit = 0;
                        // if($kvd_cash>0){
                        //     $updatedeposit = $kvd_cash + $dep_deposit;
                        // }else{
                        //      $updatedeposit = $dep_deposit;
                        //  }
                        // $insertD = array();
                        // $insertD['kvd_cash'] = $updatedeposit;
                        // $insertD['kvd_sdate'] = $this->input->post("startDate", null, '');
                        // $insertD['kvd_edate'] = $this->input->post("endDate", null, '');
                        // $this->db->update("cb_kvoucher_deposit", $insertD, array("kvd_mem_id"=>$mem_id));

                    // }else{
                        $insertD = array();
                        $insertD['kvd_mem_id'] = $mem_id;
                        $insertD['kvd_cash'] = $dep_deposit;
                        $insertD['kvd_sdate'] = $this->input->post("startDate", null, '');
                        $insertD['kvd_edate'] = $this->input->post("endDate", null, '');

                        $this->db->insert("cb_kvoucher_deposit", $insertD);
                    // }
                }
                /*

                이 부분에 예치금 저장 프로세스를 넣어야 함 : 현재는 추가 버튼을 막아 skip

                */
                $this->Biz_model->deposit_appr($mem_id, $dep_id, $this->input->post('dep_kind'));

                $this->session->set_flashdata(
                    'message',
                    '정상적으로 수정되었습니다'
                    );
            }

            $this->depositlib->update_member_deposit($mem_id);

            // 이벤트가 존재하면 실행합니다
            Events::trigger('after', $eventname);

            /**
             * 게시물의 신규입력 또는 수정작업이 끝난 후 목록 페이지로 이동합니다
             */
            $param =& $this->querystring;
            $redirecturl = $this->pagedir . '?' . $param->output();

            redirect($redirecturl);
        }
    }

    public function prelists(){

        $view = array();
        $view['view'] = array();
        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;
        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;

        $findex = $this->input->get('findex', null, 'dep_deposit_datetime');
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        if($sfield != '' || !empty($sfield)){
            $where = "AND ". $sfield." LIKE '%".$skeyword."%'";
        }else{
            $where = "";
        }

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;

        $sql = "SELECT
                dep1.dep_id,
                dep1.mem_id,
                (SELECT sum(dep_deposit) FROM cb_deposit as dep
                WHERE
                (dep_pay_type = 'pservice' OR dep_admin_memo LIKE '%선충전%' OR dep_content LIKE '%선충전%') and dep_pay_type <> 'bank'  AND dep.mem_id = dep1.mem_id AND dep.dep_deposit > 0) as sum_dep,
                (SELECT sum(dep_deposit) FROM cb_deposit as dep
                WHERE
                (dep_pay_type = 'pservice' OR dep_admin_memo LIKE '%선충전%' OR dep_content LIKE '%선충전%') and dep_pay_type <> 'bank'  AND dep.mem_id = dep1.mem_id AND dep.dep_deposit <= 0) as ser_dep,
                dep1.dep_from_type,
                dep1.dep_pay_type,
                dep1.dep_to_type,
                mem.mem_id,
                mem.mem_userid,
                mem.mem_username,
                mem.mem_nickname,
                (SELECT dep_content FROM cb_deposit where mem_id = dep1.mem_id order by dep_id DESC limit 1) dep_content,
                (SELECT dep_admin_memo FROM cb_deposit where mem_id = dep1.mem_id order by dep_id DESC limit 1) dep_admin_memo,
                -- dep1.dep_content,
                mem.mem_is_admin,
                mem.mem_point,
                mem.mem_deposit,
                pre.pre_cash,
                pre.pre_upd_date
            FROM
                cb_deposit as dep1 LEFT JOIN cb_member as mem ON dep1.mem_id = mem.mem_id LEFT JOIN cb_pre_deposit as pre ON dep1.mem_id = pre.mem_id
            WHERE
                (dep_content LIKE '%선충전%' OR dep_admin_memo LIKE '%선충전%' OR dep_pay_type = 'pservice') and dep_pay_type <> 'bank' AND mem.mem_id IS NOT NULL ".$where."
                GROUP BY dep1.mem_id ORDER BY pre.pre_upd_date DESC LIMIT ".$offset.','.($per_page);
                //echo $offset.','.($per_page * $page).', '.($per_page);
                // log_message("ERROR", "deposit > prelists --------------- : ". $sql);
        $view['view']['list'] = $this->db->query($sql)->result_array();

        // $total = "SELECT count(*) as cnt FROM (SELECT dep1.mem_id FROM cb_deposit as dep1 LEFT JOIN cb_member as mem ON dep1.mem_id = mem.mem_id WHERE (dep_content LIKE '%선충전%' OR dep_admin_memo LIKE '%선충전%' OR dep_pay_type = 'pservice') AND mem.mem_id IS NOT NULL GROUP BY mem_id) A";
        $total = "SELECT count(*) as cnt FROM (SELECT dep1.mem_id FROM cb_deposit as dep1 LEFT JOIN cb_member as mem ON dep1.mem_id = mem.mem_id LEFT JOIN cb_pre_deposit as pre ON dep1.mem_id = pre.mem_id WHERE (dep_content LIKE '%선충전%' OR dep_admin_memo LIKE '%선충전%' OR dep_pay_type = 'pservice') and dep_pay_type <> 'bank' AND mem.mem_id IS NOT NULL GROUP BY dep1.mem_id) A ";
        $view['view']['total_rows'] = $this->db->query($total)->row()->cnt;
        $result = $view['view'];


        /* 결제정보 추가 입력 */
        for($n=0;$n<count($result['list']);$n++) {
            $result['list'][$n]['amt_deposit'] = "0"; $result['list'][$n]['amt_point'] = "0";
            $amt_rs = $this->db->query("select * from cb_amt_".$result['list'][$n]['mem_userid']." where amt_kind='1' and amt_reason=?", array($result['list'][$n]['dep_id']));

            //echo "select * from cb_amt_".$result['list'][$n]['mem_userid']." where amt_kind='1' and amt_reason='2020120316171787'";

            $myadmin = $this->db->query("select * from cb_member_register where mem_id = '".$result['list'][$n]['mem_id']."' LIMIT 1")->row();
            $memadmin = $this->db->query("select * from cb_member where mem_id = '".$myadmin->mrg_recommend_mem_id."' LIMIT 1")->row();

            //echo "select * from cb_member_register where mem_id = '".$result['list'][$n]['mem_id']."' LIMIT 1";
            //exit;
            $idatetime = $result['list'][$n]['dep_datetime'];

            $sql = "select * from cb_amt_".$result['list'][$n]['mem_userid']." where amt_kind='1' LIMIT 1";
            //$sql = "select * from cb_amt_".$result['list'][$n]['mem_userid']." where amt_kind='1' and amt_memo = '예치금 이벤트 (서비스입금)' and amt_datetime between DATE_ADD('".$idatetime."', INTERVAL -10 SECOND) and DATE_ADD('".$idatetime."', INTERVAL 10 SECOND) LIMIT 1";
            //log_message("ERROR", "deposit > serviceplus --------------- : ". $sql);

            //echo $sql.'<br>';
            //$serviceplus = $this->db->query($sql)->row();
            if($amt_rs) {
                $amt = $amt_rs->row();
                $result['list'][$n]['amt_deposit'] = $amt->amt_deposit;
                $result['list'][$n]['amt_point'] = $amt->amt_point;
                $result['list'][$n]['myadmin'] = $memadmin->mem_username;
                if($result['list'][$n]['dep_deposit'] > 0){
                    $result['list'][$n]['service_amt'] = $serviceplus->amt_amount;
                }
            }
        }
        /*--------------------*/

        $list_num = $result['total_rows'] - ($page - 1) * $per_page;
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                //print_r( $key." : ".$val."<br>" );
                $result['list'][$key]['display_name'] = display_username(
                    element('mem_userid', $val),
                    element('mem_nickname', $val),
                    element('mem_icon', $val)
                    );
                $result['list'][$key]['dep_type_display'] = element(element('dep_from_type', $val), $deptype) . '=>' . element(element('dep_to_type', $val), $deptype);
                $result['list'][$key]['num'] = $list_num--;
            }
        }
        $view['view']['data'] = $result;

        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

        /* 페이지네이션 */
        $this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $result['total_rows'];
		$page_cfg['per_page'] = $per_page;
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($page);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

        /**
         * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
         */
        $search_option = array('mem.mem_username' => '업체명', 'dep1.dep_content' => '내용');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
        $view['view']['listall_url'] = $this->pagedir;
        $view['view']['write_url'] = $this->pagedir . '/write';


        $layoutconfig = array(
            'path' => 'biz/manager/deposit',
            'layout' => 'layout',
            'skin' => 'prelists',
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
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());//$this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    public function pre_list(){

        $result = array();
        $mem_id = $this->input->post("mem_id");

        $result['perpage'] = (!empty($this->input->post("perpage")))? $this->input->post("perpage") : "10";
        $result['page'] = (!empty($this->input->post("page")))? $this->input->post("page") : "1";

        $sql = "SELECT count(*) as cnt
                FROM
                cb_deposit as dep1
                LEFT JOIN cb_member as mem ON dep1.mem_id = mem.mem_id
                LEFT JOIN cb_pre_deposit as pre ON dep1.mem_id = pre.mem_id
                LEFT JOIN cb_member_register as reg ON mem.mem_id = reg.mem_id
                WHERE
                    (dep_content LIKE '%선충전%' OR dep_admin_memo LIKE '%선충전%' OR dep_pay_type = 'pservice') AND mem.mem_id = '$mem_id'
                ORDER BY dep1.dep_deposit_datetime DESC";

        $view['list_cnt'] = $this->db->query($sql)->row()->cnt;

        $result['total'] = $view['list_cnt'];

        $sql = "SELECT
                dep1.mem_id,
                dep1.dep_deposit,
                mem.mem_userid,
                mem.mem_username,
                -- reg.mrg_recommend_mem_id as mrg,
                -- (SELECT mem_username FROM cb_member WHERE mem_id = mrg limit 1) as mrg_name,
                -- mem.mem_nickname,
                dep1.dep_content,
                dep1.dep_admin_memo,
                -- mem.mem_is_admin,
                -- mem.mem_point,
                -- mem.mem_deposit,
                dep1.dep_deposit_datetime
            FROM
                cb_deposit as dep1
            LEFT JOIN cb_member as mem ON dep1.mem_id = mem.mem_id
            -- LEFT JOIN cb_pre_deposit as pre ON dep1.mem_id = pre.mem_id
            -- LEFT JOIN cb_member_register as reg ON mem.mem_id = reg.mem_id
            WHERE
               (dep_content LIKE '%선충전%' OR dep_admin_memo LIKE '%선충전%' OR dep_pay_type = 'pservice') AND mem.mem_id = '$mem_id'
            ORDER BY dep1.dep_deposit_datetime DESC LIMIT ". (($result['page'] - 1) * $result['perpage'] ) .", ".$result['perpage'];

        // log_message("ERROR", "deposit > pre_list --------------- : ". $sql);
        $view['list'] = $this->db->query($sql)->result();

        $html = "";
        if(!empty($view['list'])){
            foreach($view['list'] as $r){
                $html .="
                <tr>
                    <td>".$r->dep_deposit_datetime."</td>";
                if (empty($r->dep_admin_memo)) {
                    $html .="
                    <td>".$r->dep_content."</td>";
                } else {
                    $html .="
                    <td>".$r->dep_content." - ".$r->dep_admin_memo."</td>";
                }
                $html .="
                    <td>".number_format($r->dep_deposit)."</td>
                </tr>";
            }
        }

        //페이징 처리
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'pre_list_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $result['total'];
        $page_cfg['per_page'] = $result['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($result['page']);
        $result['page_html'] = $this->pagination->create_links();
        $result['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='searchLibraryPage($1)'><a herf='#' ",$result['page_html']);

        $result['html'] = $html;
        $result['mem_id'] = $mem_id;
        /*
        $sql = "
        SELECT DISTINCT mem.mem_biz_reg_name as mem_username
        FROM
        cb_deposit as dep1
        LEFT JOIN cb_member as mem ON dep1.mem_id = mem.mem_id
        WHERE dep1.mem_id ='".$mem_id."'";

        $result['mem_username'] = $this->db->query($sql)->row()->mem_username;
        */
        $result['mem_username'] = $view['list'][0]->mem_username;

        $json = json_encode($result, JSON_UNESCAPED_UNICODE);
        $json = str_replace('null', '""', $json);
        //header('Content-Type: application/json');
        echo $json;
    }

    /**
     * 금액이 올바르게 입력되었는지를 체크합니다
     */
    public function _deposit_sum_check($deposit)
    {
        is_numeric($deposit) OR $deposit = 0;
        $deposit = (int) $deposit;

        $mb = $this->Member_model->get_by_userid($this->input->post('mem_userid'), 'mem_id, mem_denied');
        if ( ! element('mem_id', $mb)) {
            $this->form_validation->set_message(
                '_deposit_sum_check',
                $this->input->post('mem_userid') . ' 은 존재하지 않는 회원아이디입니다'
                );
            return false;
        }
        if (element('mem_denied', $mb)) {
            $this->form_validation->set_message(
                '_deposit_sum_check',
                $this->input->post('mem_userid') . ' 은 탈퇴, 차단된 회원아이디입니다'
                );
            return false;
        }

        if ($deposit === 0) {
            $this->form_validation->set_message(
                '_deposit_sum_check',
                '예치금 변동 금액을 양수 또는 음수로 입력하여 주십시오'
                );
            return false;
        }

        $sum = $this->Deposit_model->get_deposit_sum(element('mem_id', $mb));
        $deposit_sum = $sum + $deposit;

        if ($deposit_sum < 0) {
            $this->form_validation->set_message(
                '_deposit_sum_check',
                ' 회원님의 총 잔액이 0 보다 작아지므로 진행할 수 없습니다'
                );
            return false;
        }

        return true;
    }


    public function prelists_download() {
        $view = array();

        $view['param']['dep_to_type'] = $this->input->post("dep_to_type");
        $view['param']['dep_pay_type'] = $this->input->post("dep_pay_type");
        $view['param']['start_date'] = $this->input->post("start_date");
        $view['param']['end_date'] = $this->input->post("end_date");


        $param = array();
        $sql = "
SELECT
    dep1.dep_id,
    dep1.mem_id,
    (SELECT sum(dep_deposit) FROM cb_deposit as dep
    WHERE
    (dep_pay_type = 'pservice' OR dep_admin_memo LIKE '%선충전%' OR dep_content LIKE '%선충전%') and dep_pay_type <> 'bank'  AND dep.mem_id = dep1.mem_id AND dep.dep_deposit > 0) as sum_dep,
    (SELECT sum(dep_deposit) FROM cb_deposit as dep
    WHERE
    (dep_pay_type = 'pservice' OR dep_admin_memo LIKE '%선충전%' OR dep_content LIKE '%선충전%') and dep_pay_type <> 'bank'  AND dep.mem_id = dep1.mem_id AND dep.dep_deposit <= 0) as ser_dep,
    dep1.dep_from_type,
    dep1.dep_pay_type,
    dep1.dep_to_type,
    (SELECT mem_username from cb_member cm2 where mem_id = (SELECT mrg_recommend_mem_id from cb_member_register where mem_id=mem.mem_id)) as parent_name,
    mem.mem_id,
    mem.mem_userid,
    mem.mem_username,
    mem.mem_nickname,
    (SELECT dep_content FROM cb_deposit where mem_id = dep1.mem_id order by dep_id DESC limit 1) dep_content,
    (SELECT dep_admin_memo FROM cb_deposit where mem_id = dep1.mem_id order by dep_id DESC limit 1) dep_admin_memo,
    -- dep1.dep_content,
    mem.mem_is_admin,
    mem.mem_point,
    mem.mem_deposit,
    pre.pre_cash,
    pre.pre_upd_date
FROM
    cb_deposit as dep1 LEFT JOIN cb_member as mem ON dep1.mem_id = mem.mem_id LEFT JOIN cb_pre_deposit as pre ON dep1.mem_id = pre.mem_id
WHERE
    (dep_content LIKE '%선충전%' OR dep_admin_memo LIKE '%선충전%' OR dep_pay_type = 'pservice') and dep_pay_type <> 'bank' AND mem.mem_id IS NOT NULL
    -- AND pre_upd_date BETWEEN '".$view['param']['start_date']." 00:00:00' AND '".$view['param']['end_date']." 23:59:59'
GROUP BY dep1.mem_id
ORDER BY pre.pre_upd_date ASC";

        /*
         $sql = "select t.*, cc.mrg_recommend_mem_id, ccc.mem_username as adminname, tt.* from (
         select a.*, b.mem_username, date_format(a.dep_datetime,'%Y%m%d%I%i') as itime
         from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id inner join
         (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
         FROM cb_member_register cmr
         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
         WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
         UNION ALL
         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
         FROM cb_member_register cmr
         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
         JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
         SELECT distinct mem_id
         FROM cmem
         union all
         select mem_id
         from cb_member cm
         where cm.mem_id = '".$this->member->item('mem_id')."'
         )  c on a.mem_id = c.mem_id
         where dep_datetime BETWEEN '".$view['param']['start_date']." 00:00:00' AND '".$view['param']['end_date']." 23:59:59'

         ";

         if ($view['param']['dep_to_type'] != "") {
         $sql .= " and dep_to_type='".$view['param']['dep_to_type']."' ";
         }

         if ($view['param']['dep_pay_type'] != "") {
         $sql .= " and dep_pay_type='".$view['param']['dep_pay_type']."' ";
         }
         $sql .= " and a.dep_content != '예치금 이벤트 (서비스입금)' ";
         $sql .= " order by a.dep_datetime DESC ) t left join cb_member_register cc ON cc.mem_id = t.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id left join (select date_format(dep_datetime,'%Y%m%d%I%i') as itime, dep_deposit as amt from cb_deposit WHERE dep_content = '예치금 이벤트 (서비스입금)') tt on t.itime = tt.itime";
         */

        log_message("ERROR", "deposit > deposit_download : ". $sql);
        $list = $this->db->query($sql, $param)->result();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

        $tmp_id = $this->input->post('tmp_id');
        $ids = explode(',', $tmp_id);

        // 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:G1');

        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(90);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);


        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "일시");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "소속");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "내용");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "선충전금액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "선충전차감금액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "최종금액");


        $deptype = $this->depositlib->deptype;

        $cnt = 1;
        $row = 2;
        //number_format(($r->dep_datetime, 1)
        foreach($list as $r) {
            $str2n = "";
            // switch($r->mem_2nd_send) { case 'GREEN_SHOT' : $str2n = '웹(A)'; break; case 'NASELF': $str2n = '웹(B)'; break; case 'SMART':$str2n = '웹(C)'; break; default:$str2n = '';}
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->pre_upd_date);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->parent_name);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->mem_username);
            if (empty($r->dep_admin_memo)) {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->dep_content);
            } else {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->dep_content." - ".$r->dep_admin_memo);
            }
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->sum_dep);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->ser_dep);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->pre_cash);





            /*
             $descript = "";
             if($r->dep_deposit >= 0) {
             $descript .= "[충전] ";
             } else {
             $descript .= "[사용] ";
             }
             $descript .= element($r->dep_from_type, $deptype) . '=>' . element($r->dep_to_type, $deptype);
             $dep_con = $r->dep_content;
             if(!empty($r->dep_admin_memo)){
             $dep_con .='_' .$r->dep_admin_memo;
             }
             $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $descript);  // 구분

             $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->dep_datetime);
             $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $dep_con);
             $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, number_format($r->dep_deposit));
             $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->amt);
             */

            $row++;
            $cnt++;
        }

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="pre_list.xls"');
        header('Cache-Control: max-age=0');

        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
    }

    public function prelists_detail_download() {
        // $view = array();

        $pram_mem_id = $this->input->post("mem_id");

        $sql = "
SELECT
    dep1.mem_id,
    dep1.dep_deposit,
    mem.mem_userid,
    mem.mem_username,
    -- reg.mrg_recommend_mem_id as mrg,
    -- (SELECT mem_username FROM cb_member WHERE mem_id = mrg limit 1) as mrg_name,
    -- mem.mem_nickname,
    dep1.dep_content,
    dep1.dep_admin_memo,
    -- mem.mem_is_admin,
    -- mem.mem_point,
    -- mem.mem_deposit,
    dep1.dep_deposit_datetime
FROM
    cb_deposit as dep1
LEFT JOIN cb_member as mem ON dep1.mem_id = mem.mem_id
-- LEFT JOIN cb_pre_deposit as pre ON dep1.mem_id = pre.mem_id
-- LEFT JOIN cb_member_register as reg ON mem.mem_id = reg.mem_id
WHERE
   (dep_content LIKE '%선충전%' OR dep_admin_memo LIKE '%선충전%' OR dep_pay_type = 'pservice') AND mem.mem_id = '".$pram_mem_id."'
ORDER BY dep1.dep_deposit_datetime ASC";

        log_message("ERROR", "deposit > deposit_detail_download : ". $sql);
        $list = $this->db->query($sql, $param)->result();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

        // 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 16),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:C1');
        $this->excel->getActiveSheet()->mergeCells('A1:C1');

        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A3:C3');


        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
        $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        $this->excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(100);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);


        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, "일시");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, "내용");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 3, "금액");


        $head_title = $list[0]->mem_username." 선충전 상세내역";
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $head_title);

        $cnt = 1;
        $row = 4;
        //number_format(($r->dep_datetime, 1)
        foreach($list as $r) {
            $str2n = "";
            // switch($r->mem_2nd_send) { case 'GREEN_SHOT' : $str2n = '웹(A)'; break; case 'NASELF': $str2n = '웹(B)'; break; case 'SMART':$str2n = '웹(C)'; break; default:$str2n = '';}
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->dep_deposit_datetime);
            if (empty($r->dep_admin_memo)) {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->dep_content);
            } else {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->dep_content." - ".$r->dep_admin_memo);
            }
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->dep_deposit);

            $row++;
            $cnt++;
        }

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="pre_detail_list.xls"');
        header('Cache-Control: max-age=0');

        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');

    }

    public function deposit_download() {
        $view = array();

        $view['param']['dep_to_type'] = $this->input->post("dep_to_type");
        $view['param']['dep_pay_type'] = $this->input->post("dep_pay_type");
        $view['param']['start_date'] = $this->input->post("start_date");
        $view['param']['end_date'] = $this->input->post("end_date");
        $view['param']['pre_list'] = $this->input->post("pre_list");


        $param = array();

        $sql = "select t.*, cc.mrg_recommend_mem_id, ccc.mem_username as adminname, tt.* from (
            select a.*, b.mem_username, date_format(a.dep_datetime,'%Y%m%d%I%i') as itime
            from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id inner join
                (WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	FROM cb_member_register cmr
                	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	WHERE cm.mem_id = '".$this->member->item('mem_id')."' and cmr.mem_id <> '".$this->member->item('mem_id')."'
                	UNION ALL
                	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                	FROM cb_member_register cmr
                	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id)
                SELECT distinct mem_id
                FROM cmem
                union all
                select mem_id
                from cb_member cm
                where cm.mem_id = '".$this->member->item('mem_id')."'
                )  c on a.mem_id = c.mem_id
            where dep_datetime BETWEEN '".$view['param']['start_date']." 00:00:00' AND '".$view['param']['end_date']." 23:59:59'

        ";

        if ($view['param']['dep_to_type'] != "") {
            $sql .= " and dep_to_type='".$view['param']['dep_to_type']."' ";
        }

        if ($view['param']['dep_pay_type'] != "") {
            $sql .= " and dep_pay_type='".$view['param']['dep_pay_type']."' ";
        }

        if (!empty($view['param']['pre_list'])&& $view['param']['pre_list']=='y') {
            $sql .= "and ((dep_content like '%선충전%' and dep_datetime < '2020-12-01 00:00:00') OR dep_pay_type = 'pservice') ";
        }
        $sql .= " and a.dep_content != '예치금 이벤트 (서비스입금)' ";
        $sql .= " order by a.dep_datetime DESC ) t left join cb_member_register cc ON cc.mem_id = t.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id left join (select date_format(dep_datetime,'%Y%m%d%I%i') as itime, dep_deposit as amt from cb_deposit WHERE dep_content = '예치금 이벤트 (서비스입금)') tt on t.itime = tt.itime  order by t.dep_datetime ASC";
        log_message("ERROR", "deposit > deposit_download : ". $sql);
        $list = $this->db->query($sql, $param)->result();

        $Acol = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

        $tmp_id = $this->input->post('tmp_id');
        $ids = explode(',', $tmp_id);

        // 라이브러리를 로드한다.
        $this->load->library('excel');

        // 시트를 지정한다.
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Sheet1');

        // 필드명을 기록한다.
        // 글꼴 및 정렬
        $this->excel->getActiveSheet()->duplicateStyleArray( array('font' => array('bold' => true, 'size' => 12),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'DDDDDD')),
            'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER )
        ),	'A1:H1');

        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);


        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "소속");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "입금자명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "구분");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "일시");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "내용");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "현금/카드 금액");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "이벤트 금액");

        $deptype = $this->depositlib->deptype;

        $cnt = 1;
        $row = 2;
        //number_format(($r->dep_datetime, 1)
        foreach($list as $r) {
            $str2n = "";
            // switch($r->mem_2nd_send) { case 'GREEN_SHOT' : $str2n = '웹(A)'; break; case 'NASELF': $str2n = '웹(B)'; break; case 'SMART':$str2n = '웹(C)'; break; default:$str2n = '';}
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->adminname);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->mem_username);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->mem_realname);
/*
$sTime = date("Y-m-d H:i:s", strtotime('2020-12-01 00:00:00')); // 시작 시간
            <? $dTime = date("Y-m-d H:i:s", strtotime(element('dep_datetime', $result))); // 등록시간 ?>
                    <?php if (element('dep_deposit', $result) >= 0) { ?>
                        <?php if ((element('dep_pay_type', $result) == 'pservice' || strpos(element('dep_content', $result),"선충전")!==false || strpos(element('dep_admin_memo', $result),"선충전")!==false) && $dTime>$sTime){ ?>
                            <button type="button" class="btn btn-xs btn-primary" >+ 선충전</button>
                        <? }else{ ?>
                            <button type="button" class="btn btn-xs btn-primary" >충전</button>
                        <?} ?>
                    <?php } else { ?>
                        <?php if ((element('dep_pay_type', $result) == 'pservice' || element('dep_content', $result) == '예치금 선충전 차감'|| strpos(element('dep_content', $result),"선충전")!==false || strpos(element('dep_admin_memo', $result),"선충전")!==false) && $dTime>$sTime) { ?>
                            <button type="button" class="btn btn-xs btn-danger" >- 선충전</button>
                        <? }else{ ?>
                            <button type="button" class="btn btn-xs btn-danger" >사용</button>
                        <?} ?>
					<?php } ?>
*/

            $dep_con = $r->dep_content;
            if(!empty($r->dep_admin_memo)){
                $dep_con .='_' .$r->dep_admin_memo;
            }

            $sTime = date("Y-m-d H:i:s", strtotime('2020-12-01 00:00:00'));
            $dTime = date("Y-m-d H:i:s", strtotime($r->dep_datetime));

            $descript = "";
            /*
            if($r->dep_deposit >= 0) {
                if (!empty($view['param']['pre_list'])&& $view['param']['pre_list']=='y') {
                    $descript .= "[+ 선충전] ";
                } else {
                    $descript .= "[충전] ";
                }
            } else {
                if (!empty($view['param']['pre_list'])&& $view['param']['pre_list']=='y') {
                    $descript .= "[- 선충전] ";
                } else {
                    $descript .= "[사용] ";
                }
            }
            */
            if ($r->dep_deposit >= 0) {
                if (($r->dep_pay_type == 'pservice' || strpos($dep_con,"선충전")!==false) && $dTime>$sTime) {
                    $descript .= "[+ 선충전] ";
                } else {
                    $descript .= "[충전] ";
                }
            } else {
                if (($r->dep_pay_type == 'pservice' || $r->dep_content == "예치금 선충전 차감" || strpos($dep_con,"선충전")!==false) && $dTime>$sTime) {
                    $descript .= "[- 선충전] ";
                } else {
                    $descript .= "[사용] ";
                }
            }


            $descript .= element($r->dep_from_type, $deptype) . '=>' . element($r->dep_to_type, $deptype);

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $descript);  // 구분

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->dep_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $dep_con);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, number_format($r->dep_deposit));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->amt);
            $row++;
            $cnt++;
        }

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="deposit_list.xls"');
        header('Cache-Control: max-age=0');

        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
    }

    public function get_tg_amt(){
        $result = array();

        $sql="
            SELECT
                pda_amount
            FROM
                cb_pre_deposit_amt
            WHERE
                pda_mem_id = 962
        ";
        $result["amt"] = $this->db->query($sql)->row()->pda_amount;

        $json = json_encode($result, JSON_UNESCAPED_UNICODE);
        $json = str_replace('null', '""', $json);
        header('Content-Type: application/json');
        echo $json;
    }

    public function set_tg_amt(){
        $result = array();
        $sql = "
            UPDATE
                cb_pre_deposit_amt
            SET
                pda_amount = " . $this->input->post("amt") . "
            WHERE
                pda_mem_id = 962
        ";
        $this->db->query($sql);

        $json = json_encode($result, JSON_UNESCAPED_UNICODE);
        $json = str_replace('null', '""', $json);
        header('Content-Type: application/json');
        echo $json;
    }
}
