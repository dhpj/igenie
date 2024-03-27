<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once '/var/www/igenie/application/third_party/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Pendingbank class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>예치금>무통장입금알림 controller 입니다.
 */
class Pendingbank extends CB_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = '/biz/manager/pendingbank';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Deposit', 'Biz', 'Biz_dhn');

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
        $this->load->library(array('pagination', 'querystring', 'depositlib', 'funn'));
    }

    /**
     * 목록을 가져오는 메소드입니다
     */
    public function _index()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_pendingbank_index';
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
        //$page = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $view['view']['sort'] = array(
            'dep_id' => $param->sort('dep_id', 'asc'),
            'dep_pay_type' => $param->sort('dep_pay_type', 'asc'),
        );
        $findex = $this->input->get('findex') ? $this->input->get('findex') : $this->{$this->modelname}->primary_key;
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');
        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;


        // $param = array($view['param']['startDate']." 00:00:00", $view['param']['endDate']." 23:59:59");
        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->{$this->modelname}->allow_search_field = array('deposit.mem_id', 'dep_deposit_request', 'dep_cash_request', 'dep_point', 'dep_pay_type', 'dep_content', 'dep_admin_memo', 'dep_ip', 'deposit.mem_nickname', 'member.mem_userid'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('deposit.mem_id', 'dep_deposit_request', 'dep_cash_request', 'dep_point', 'dep_pay_type'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('dep_id', 'deposit.mem_id', 'dep_pay_type'); // 정렬이 가능한 필드

        $where = array(
            'dep_from_type' => 'cash',
            'dep_pay_type' => 'bank',

        );


        if ($this->input->get('dep_status') === 'Y') {
            $where['dep_status'] = 1;
        }
        if ($this->input->get('dep_status') === 'N') {
            $where['dep_status'] = 0;
        }



        // $sch .= $where." AND cre_date BETWEEN '". $view['param']['startDate'] ." 00:00:00' AND '". $view['param']['endDate'] ." 23:59:59' ";

        if($this->member->item('mem_level') == '50') {
            $exists = "EXISTS( SELECT 1 FROM cb_member_register  WHERE cb_member_register.mrg_recommend_mem_id = '".$this->member->item('mem_id')."' AND cb_member.mem_id = cb_member_register.mem_id)";
        }

        $result = $this->{$this->modelname}
        ->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword, 'OR',$exists );
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                $result['list'][$key]['display_name'] = display_username(
                    element('mem_userid', $val),
                    element('mem_nickname', $val)
                    );
            }
        }
        $view['view']['data'] = $result;


        /**
         * primary key 정보를 저장합니다
         */
        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

        /**
         * 페이지네이션을 생성합니다
         */
        $config['base_url'] = $this->pagedir . '?' . $param->replace('page');
        $config['total_rows'] = $result['total_rows'];
        // log_message("ERROR", "TR".$result['total_rows']);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $view['view']['paging'] = $this->pagination->create_links();
        $view['view']['page'] = $page;

        /**
         * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
         */
        $search_option = array('dep_deposit_request' => $this->depositconfig->item('deposit_name'), 'dep_cash_request' => '총결제해야할 금액', 'dep_ip' => 'IP', 'deposit.mem_nickname' => '회원명', 'member.mem_userid' => '회원아이디');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
        $view['view']['listall_url'] = $this->pagedir;
        $view['view']['list_delete_url'] = $this->pagedir . '/listdelete/?' . $param->output();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array(
            'path' => 'biz/manager/pendingbank',
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
     * 입금내역
     */
    public function index()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_pendingbank_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $where = " 1=1 and dep_pay_type = 'bank' ";
        // 이벤트가 존재하면 실행합니다
        // $view['view']['event']['before'] = Events::trigger('before', $eventname);

        // 세금계산서 관련 변경 20을 200으로 변경 - 2022-04-06
        $view['perpage'] = ($this->input->get("perpage")) ? $this->input->get("perpage") : 200;
        $view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $view['param']['dep_status'] = $this->input->get("dep_status");

        // 2022-04-06 세금계산서 발행 관련으로 추가 시작
        // 세금계산서 발행(세무서에 등록 완료) - 2022-04-06
        $view['bill_issuer'] = ($this->input->get("issuer")) ? $this->input->get("issuer") : "A";
        if($view['bill_issuer'] == 'Y' || $view['bill_issuer'] == 'N') {
            $where .= " and aa.taxbill_state = '".$view['bill_issuer']."'";
        }

        // 세금계산서 엑셀파일 다운로드 - 2022-04-06
        $view['bill_down'] = ($this->input->get("down")) ? $this->input->get("down") : "A";
        if($view['bill_down'] == 'Y' || $view['bill_down'] == 'N') {
            $where .= " and aa.taxbill_down = '".$view['bill_down']."'";
        }
        // 2022-04-06 세금계산서 발행 관련으로 추가 끝


        $view['skeyword'] = $this->input->get("skeyword");
        $view['sfield'] = $this->input->get("sfield");
        if($view['sfield']=='1'){
            $view['param']['search_type'] = "a.mem_realname"; //입금자
        }else{
            $view['param']['search_type'] = "b.mem_username"; //회원명
        }


        //$view['param']['search_type'] = $this->input->get("sfield"); //검색조건
        $view['param']['search_for'] = $this->input->get("skeyword"); //검색내용

        $sfield = $this->input->post("sfield");
        $skeyword = $this->input->post("skeyword");


        if($view['param']['dep_status'] == '0' || $view['param']['dep_status'] == '1') {
            $where .= " and dep_status = '".$view['param']['dep_status']."'";
        }

        $param = array();
        if($view['param']['search_type'] && $view['param']['search_for']){
            $where .= " and ".$view['param']['search_type']." like ?";
            array_push($param, "%".$view['param']['search_for']."%");
        }
        // $view['param']['startDate'] = ($this->input->get("startDate"))? $this->input->get("startDate") : date('Y-m-d');
        // $view['param']['endDate'] = ($this->input->get("endDate")) ? $this->input->get("endDate") : date('Y-m-d');
        $now_date = date("Y-m-d",strtotime('first day of -2 month'));

        // date('Y-m-t', strtotime('first day of -2 month', strtotime($nowDate)));

        $view['startDate'] = ($this->input->get("startDate"))? $this->input->get("startDate") : date($now_date, mktime(0, 0, 0, intval(date('m')), 1, intval(date('Y'))  ));
        $view['endDate'] = ($this->input->get("endDate")) ? $this->input->get("endDate") : date("Y-m-d", mktime(0, 0, 0, intval(date('m'))+1, 0, intval(date('Y'))  ));

        $sch = "  where dep_datetime BETWEEN '".$view['startDate'] ." 00:00:00' AND '". $view['endDate'] ." 23:59:59' ";

        $sclimit = "  where dep_datetime BETWEEN '".$view['startDate'] ." 00:00:00' AND '". $view['endDate'] ." 23:59:59' order by dep_datetime desc ";


        // echo "where : ". $where ."<br>";
//         $view['total_rows'] = $this->Biz_model->get_table_count("cb_redbank_data aa LEFT JOIN cb_deposit a ON aa.oid = a.dep_id inner join cb_member b on a.mem_id=b.mem_id inner join
// 				(	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
//                 	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//                 	FROM cb_member_register cmr
//                 	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//                 	WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
//                 	UNION ALL
//                 	SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//                 	FROM cb_member_register cmr
//                 	JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//                 	JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
//                 )
//                 SELECT distinct mem_id
//                 FROM cmem
//                 union all
//                 select mem_id
//                 from cb_member cm
//                 where cm.mem_id = ".$this->member->item('mem_id')."
// ) c on a.mem_id = c.mem_id", $where.$sch, $param);

//         $rec_a = " and a.mem_id in (select mem_id from (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
//             SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//             FROM cb_member_register cmr
//             JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//             WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
//             UNION ALL
//             SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//             FROM cb_member_register cmr
//             JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//             JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
//         )
//         SELECT distinct mem_id
//         FROM cmem
//         union all
//         select mem_id
//         from cb_member cm
//         where cm.mem_id = ".$this->member->item('mem_id')."
// ) c ) ";

$rec_b = " and a.mem_id in (select mem_id from (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
    SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
    FROM cb_member_register cmr
    JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
    WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
    UNION ALL
    SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
    FROM cb_member_register cmr
    JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
    JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
)
SELECT distinct mem_id
FROM cmem
union all
select mem_id
from cb_member cm
where cm.mem_id = ".$this->member->item('mem_id')."
) c ) ";

        $view['total_rows'] = $this->Biz_model->get_table_count(" (select * from cb_deposit ".$sch.") as a LEFT JOIN  cb_redbank_data aa ON a.dep_id = aa.oid left join cb_member b on a.mem_id=b.mem_id
                ", $where.$rec_b, $param);

        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > total_rows~~~~~~~~~~~1 : ".$view['total_rows']);
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > where~~~~~~~~~~~1 : ".$where);
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sch~~~~~~~~~~~1 : ".$sch);
        /*
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ".$this->member->item('mem_id').") initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         union select ".$this->member->item('mem_id')." mem_id, ".$this->member->item('mem_id')." mrg_recommend_mem_id
         ) c on a.mst_mem_id = c.mem_id", $where, $param);
         */
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        $view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

        $search_option = array('a.mem_realname' => '입금자', 'b.mem_username' => '회원명');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);

        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */

        // 2022-04-06 세금계산서 발행 관련으로 추가( aa.taxbill_state as taxbill_state, aa.taxbill_down as taxbill_down)
//         $sql = "
// 			select a.*, b.*, ccc.mem_username as adminname, aa.taxbill_state as taxbill_state, aa.taxbill_down as taxbill_down
// 			from cb_redbank_data aa LEFT JOIN cb_deposit a ON aa.oid = a.dep_id inner join cb_member b on a.mem_id=b.mem_id  left join cb_member_register cc ON cc.mem_id = b.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id  inner join
// (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
//     SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//     FROM cb_member_register cmr
//     JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//     WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
//     UNION ALL
//     SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//     FROM cb_member_register cmr
//     JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//     JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
// )
// SELECT distinct mem_id
// FROM cmem
// union all
// select mem_id
// from cb_member cm
// where cm.mem_id = ".$this->member->item('mem_id')."
// )  c on a.mem_id = c.mem_id
// 			where ".$where.$sch." order by a.dep_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];

        $sql = "
            select a.*, b.*, ccc.mem_username as adminname, aa.taxbill_state as taxbill_state, aa.taxbill_down as taxbill_down
            from (select * from cb_deposit ".$sclimit."  limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'].") as a LEFT JOIN  cb_redbank_data aa ON a.dep_id = aa.oid left join cb_member b on a.mem_id=b.mem_id  left join cb_member_register cc ON cc.mem_id = b.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id

            where ".$where.$rec_b." order by a.dep_datetime desc ";
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~2 : ".$sql);
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~2 param : ".$param);
        $view['list'] = $this->db->query($sql, $param)->result();
        $view['view']['canonical'] = site_url();


//         $sql = "
//         select sum(a.dep_deposit) as tsum
//         from cb_redbank_data aa LEFT JOIN cb_deposit a ON aa.oid = a.dep_id inner join cb_member b on a.mem_id=b.mem_id  left join cb_member_register cc ON cc.mem_id = b.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id  inner join
// (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
//     SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//     FROM cb_member_register cmr
//     JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//     WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
//     UNION ALL
//     SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//     FROM cb_member_register cmr
//     JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//     JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
// )
// SELECT distinct mem_id
// FROM cmem
// union all
// select mem_id
// from cb_member cm
// where cm.mem_id = ".$this->member->item('mem_id')."
// )  c on a.mem_id = c.mem_id
//         where ".$where.$sch." LIMIT 1";
//         log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~3 : ".$sql);
//         $view['tsum'] = $this->db->query($sql, $param)->row()->tsum;
//
//         $sql = "
//         select MONTH(a.dep_datetime) as mon, sum(a.dep_deposit) as tsum
//         from cb_redbank_data aa LEFT JOIN cb_deposit a ON aa.oid = a.dep_id inner join cb_member b on a.mem_id=b.mem_id  left join cb_member_register cc ON cc.mem_id = b.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id  inner join
// (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
//     SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//     FROM cb_member_register cmr
//     JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//     WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
//     UNION ALL
//     SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
//     FROM cb_member_register cmr
//     JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
//     JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
// )
// SELECT distinct mem_id
// FROM cmem
// union all
// select mem_id
// from cb_member cm
// where cm.mem_id = ".$this->member->item('mem_id')."
// )  c on a.mem_id = c.mem_id
//         where ".$where.$sch." GROUP BY mon";



        $sql = "
        select sum(a.dep_deposit) as tsum
        from  (select * from cb_deposit ".$sch.") as a LEFT JOIN  cb_redbank_data aa ON a.dep_id = aa.oid left join cb_member b on a.mem_id=b.mem_id

        where ".$where.$rec_b." LIMIT 1";
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~3 : ".$sql);
        $view['tsum'] = $this->db->query($sql, $param)->row()->tsum;

        $sql = "
        select MONTH(a.dep_datetime) as mon, sum(a.dep_deposit) as tsum
        from  (select * from cb_deposit ".$sch.") as a LEFT JOIN  cb_redbank_data aa ON a.dep_id = aa.oid left join cb_member b on a.mem_id=b.mem_id

        where ".$where.$rec_b." GROUP BY mon";
        $view['monthlist'] = $this->db->query($sql, $param)->result();
        // $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array(
            'path' => 'biz/manager/pendingbank',
            'layout' => 'layout',
            'skin' => 'list',
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


    public function bank_stat()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_pendingbank_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $where = " 1=1 and dep_pay_type = 'bank' ";
        // 이벤트가 존재하면 실행합니다
        // $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['mon'] = ($this->input->get("mon")) ? $this->input->get("mon") : '12';
        $view['mart'] = ($this->input->get("mart")) ? $this->input->get("mart") : '1';
        $view['group'] = ($this->input->get("group")) ? $this->input->get("group") : '1';

        // 세금계산서 관련 변경 20을 40으로 변경 - 2022-04-06
        $view['perpage'] = ($this->input->get("perpage")) ? $this->input->get("perpage") : 40;
        $view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
        // $view['page']=($this->input->get("page")) ? $this->input->get("page") : '1';
        $view['param']['dep_status'] = $this->input->get("dep_status");
        $view['skeyword'] = $this->input->get("skeyword");
        $view['sfield'] = $this->input->get("sfield");
        if($view['sfield']=='1'){
            $view['param']['search_type'] = "a.mem_realname"; //입금자
        }else{
            $view['param']['search_type'] = "b.mem_username"; //회원명
        }


        //$view['param']['search_type'] = $this->input->get("sfield"); //검색조건
        $view['param']['search_for'] = $this->input->get("skeyword"); //검색내용

        $sfield = $this->input->post("sfield");
        $skeyword = $this->input->post("skeyword");


        if($view['param']['dep_status'] == '0' || $view['param']['dep_status'] == '1') {
            $where .= " and dep_status = '".$view['param']['dep_status']."'";
        }


        $param = array();
        if($view['param']['search_type'] && $view['param']['search_for']){
            $where .= " and ".$view['param']['search_type']." like ?";
            array_push($param, "%".$view['param']['search_for']."%");
        }
        // $view['param']['startDate'] = ($this->input->get("startDate"))? $this->input->get("startDate") : date('Y-m-d');
        // $view['param']['endDate'] = ($this->input->get("endDate")) ? $this->input->get("endDate") : date('Y-m-d');
        $montext = (int)$view['mon']-1;


        $now_date = date("Y-m-d",strtotime('first day of -'.$montext.' month'));
        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > now_date : ".$now_date);

        // date('Y-m-t', strtotime('first day of -2 month', strtotime($nowDate)));
        $view['startDate'] = ($this->input->get("startDate"))? $this->input->get("startDate") : $now_date;
        // $view['startDate'] = ($this->input->get("startDate"))? $this->input->get("startDate") : date($now_date, mktime(0, 0, 0, intval(date('m')), 1, intval(date('Y'))  ));
        $view['endDate'] = ($this->input->get("endDate")) ? $this->input->get("endDate") : date("Y-m-d", mktime(0, 0, 0, intval(date('m'))+1, 0, intval(date('Y'))  ));



        $sch = "  AND a.mem_id <> 3 AND dep_datetime BETWEEN '".$view['startDate'] ." 00:00:00' AND '". $view['endDate'] ." 23:59:59' ";


        // echo "where : ". $where ."<br>";
        //         $view['total_rows'] = $this->Biz_model->get_table_count("cb_deposit a inner join cb_member b on a.mem_id=b.mem_id inner join
        // 				(	SELECT distinct a.mem_id
        //             FROM
        //                 (SELECT
        //                     GET_PARENT_EQ_PRIOR_ID(mem_id) AS _id, @level AS level
        //                 FROM
        //                     (SELECT @start_with:=".$this->member->item('mem_id').", @id:=@start_with) vars, cb_member_register
        //                 WHERE
        //                     @id IS NOT NULL) ho
        //                     LEFT JOIN
        //                 cb_member_register c ON c.mem_id = ho._id
        //                     INNER JOIN
        //                 cb_member a ON (c.mem_id = a.mem_id or a.mem_id =".$this->member->item('mem_id')." )
        //                     AND a.mem_useyn = 'Y'
        // ) c on a.mem_id = c.mem_id", $where.$sch, $param);

        $rec_b = " and a.mem_id in (select mem_id from (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
            UNION ALL
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
        )
        SELECT distinct mem_id
        FROM cmem
        union all
        select mem_id
        from cb_member cm
        where cm.mem_id = ".$this->member->item('mem_id')."
        ) c ) ";

        // 2022-04-06 세금계산서 발행 관련으로 변경 시작
        $sql = "
select sum(dd.cnt1_sub) as cnt1
    , sum(cnt1_taxbill_sub) as cnt1_taxbill
    , sum(dd.cnt2_sub) as cnt2
    , sum(cnt2_taxbill_sub) as cnt2_taxbill
FROM (
    select a.*
        , count(ccc.mem_username) as cnt1_sub
        , count(b.mem_username) as cnt2_sub
        -- , b.mem_username, ccc.mem_username as adminname
        , count(case when aa.taxbill_state='Y' then ccc.mem_username end) cnt1_taxbill_sub
        , count(case when aa.taxbill_state='Y' then b.mem_username end) as cnt2_taxbill_sub
    from cb_redbank_data aa
        LEFT JOIN cb_deposit a ON aa.oid = a.dep_id
        inner join cb_member b on a.mem_id=b.mem_id
        left join cb_member_register cc ON cc.mem_id = b.mem_id
        left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id

    where ".$where.$rec_b.$sch."
    group by mem_id) dd
limit 1
        ";

        $query_row = $this->db->query($sql, $param)->row();
        if($view['mart']=='2'){
            $view['total_rows'] = $query_row->cnt2;
            $view['total_rows_taxbill'] = $query_row->cnt2_taxbill;
        }else{
            $view['total_rows'] = $query_row->cnt1;
            $view['total_rows_taxbill'] = $query_row->cnt1_taxbill;
        }
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~1 : ".$sql);

        /*
         $sql = "select count(dd.adminname) as cnt1
         , count(dd.mem_username) as cnt2
         FROM
         (select a.*, b.mem_username, ccc.mem_username as adminname
         from cb_redbank_data aa LEFT JOIN cb_deposit a ON aa.oid = a.dep_id inner join cb_member b on a.mem_id=b.mem_id  left join cb_member_register cc ON cc.mem_id = b.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id  inner join
         (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
         FROM cb_member_register cmr
         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
         WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
         UNION ALL
         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
         FROM cb_member_register cmr
         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
         JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
         )
         SELECT distinct mem_id
         FROM cmem
         union all
         select mem_id
         from cb_member cm
         where cm.mem_id = ".$this->member->item('mem_id')."
         )  c on a.mem_id = c.mem_id where ".$where.$sch." group by mem_id) dd  limit 1";
         if($view['mart']=='2'){
         $view['total_rows'] = $this->db->query($sql, $param)->row()->cnt2;
         }else{
         $view['total_rows'] = $this->db->query($sql, $param)->row()->cnt1;
         }
         log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~1 : ".$sql);
         */
        // 2022-04-06 세금계산서 발행 관련으로 변경 끝

        /*
         (select  mem_id, mrg_recommend_mem_id
         from (select * from cb_member_register order by mrg_recommend_mem_id, mem_id) folders_sorted,
         (select @pv := ".$this->member->item('mem_id').") initialisation
         where   find_in_set(mrg_recommend_mem_id, @pv) > 0 and @pv := concat(@pv, ',', mem_id)
         union select ".$this->member->item('mem_id')." mem_id, ".$this->member->item('mem_id')." mrg_recommend_mem_id
         ) c on a.mst_mem_id = c.mem_id", $where, $param);
         */


        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        $view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

        $search_option = array('a.mem_realname' => '입금자', 'b.mem_username' => '회원명');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);

        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */

        $year = (int)date("Y");
        $month = (int)date("m");
        // $tempdate = date("Y-m-d", mktime(0, 0, 0, intval(date('m'))-1, 0, intval(date('Y'))  ));

        // $testdate = date("m", $tempdate);
        // $testtest = (int)$testdate;
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > year : ".$year." - month : ".$month." - tempdate : ".$tempdate." - testdate : ".$testdate." - testtest : ".$testtest);
        $sum_case="";
        $imonth="";
        for($i=0;$i<12;$i++){
            if($month<10){
                $imonth = "0".$month;
            }else{
                $imonth = $month;
            }

            // 2022-04-06 세금계산서 발행 관련으로 변경 시작
            if($view['group']=='1'){
                $sum_case .= "    , sum(case when DATE(a.dep_datetime) >= DATE('".$year."-".$imonth."-01') AND DATE(a.dep_datetime) < DATE_ADD(DATE('".$year."-".$imonth."-01'), INTERVAL 1 MONTH) then a.dep_deposit end) as m".$i." \n";
                $sum_case .= "    , sum(case when taxbill_state='Y' AND DATE(a.dep_datetime) >= DATE('".$year."-".$imonth."-01') AND DATE(a.dep_datetime) < DATE_ADD(DATE('".$year."-".$imonth."-01'), INTERVAL 1 MONTH) then a.dep_deposit end) as m".$i."_taxbill \n";
            }else{
                $sum_case .= "    , sum(case when DATE(a.dep_datetime) >= DATE('".$year."-".$imonth."-01') AND DATE(a.dep_datetime) < DATE_ADD(DATE('".$year."-".$imonth."-01'), INTERVAL 1 MONTH) then 1 end) as m".$i." \n";
                $sum_case .= "    , sum(case when taxbill_state='Y' AND DATE(a.dep_datetime) >= DATE('".$year."-".$imonth."-01') AND DATE(a.dep_datetime) < DATE_ADD(DATE('".$year."-".$imonth."-01'), INTERVAL 1 MONTH) then 1 end) as m".$i."_taxbill \n";
            }
            // 2022-04-06 세금계산서 발행 관련으로 변경 끝

            if($month>1){
                $month=$month-1;
            }else if($month==1){
                $month = 12;
                $year = $year-1;
            }
        }

        $groupby = "adminname";
        if($view['mart']=='2'){
            $groupby = "mem_username";
        }


        // 2022-04-06 세금계산서 발행 관련으로 변경 시작
        $sql = "
select a.adminname
    , a.mem_username
    ".$sum_case."
FROM (
    select a.*, b.mem_username, ccc.mem_username as adminname, aa.taxbill_state
    from cb_redbank_data aa
        LEFT JOIN cb_deposit a ON aa.oid = a.dep_id
        inner join cb_member b on a.mem_id=b.mem_id
        left join cb_member_register cc ON cc.mem_id = b.mem_id
        left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id
        inner join (
            WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
                UNION ALL
                SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
                FROM cb_member_register cmr
                JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
                JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
            )
            SELECT distinct mem_id
            FROM cmem
            union all
            select mem_id
            from cb_member cm
            where cm.mem_id = ".$this->member->item('mem_id')."
        )  c on a.mem_id = c.mem_id
    where  ".$where.$sch.") a
group by ".$groupby." order by adminname limit ".(($view['param']['page'] - 1) * $view['perpage']).",  ".$view['perpage'];

        /*
         $sql = "
         select a.adminname
         , a.mem_username
         ".$sum_case."
         FROM
         (select a.*, b.mem_username, ccc.mem_username as adminname
         from cb_redbank_data aa LEFT JOIN cb_deposit a ON aa.oid = a.dep_id inner join cb_member b on a.mem_id=b.mem_id  left join cb_member_register cc ON cc.mem_id = b.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id  inner join
         (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
         FROM cb_member_register cmr
         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
         WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
         UNION ALL
         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
         FROM cb_member_register cmr
         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
         JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
         )
         SELECT distinct mem_id
         FROM cmem
         union all
         select mem_id
         from cb_member cm
         where cm.mem_id = ".$this->member->item('mem_id')."
         )  c on a.mem_id = c.mem_id where ".$where.$sch." ) a group by ".$groupby." order by adminname limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
         */

        // 2022-04-06 세금계산서 발행 관련으로 변경 끝

        //         $sql = "
        // 			select a.*, b.*, ccc.mem_username as adminname
        // 			from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id  left join cb_member_register cc ON cc.mem_id = b.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id  inner join
        // (	SELECT distinct a.mem_id
        //             FROM
        //                 (SELECT
        //                     GET_PARENT_EQ_PRIOR_ID(mem_id) AS _id, @level AS level
        //                 FROM
        //                     (SELECT @start_with:=".$this->member->item('mem_id').", @id:=@start_with) vars, cb_member_register
        //                 WHERE
        //                     @id IS NOT NULL) ho
        //                     LEFT JOIN
        //                 cb_member_register c ON c.mem_id = ho._id
        //                     INNER JOIN
        //                 cb_member a ON (c.mem_id = a.mem_id or a.mem_id =".$this->member->item('mem_id')." )
        //                     AND a.mem_useyn = 'Y'
        // )  c on a.mem_id = c.mem_id
        // 			where ".$where.$sch." order by a.dep_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~2 : ".$sql);
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~2 param : ".$param);
        $view['list'] = $this->db->query($sql, $param)->result();
        $view['view']['canonical'] = site_url();


        // 2022-04-06 세금계산서 발행 관련으로 변경 시작
        $sql = "
select sum(a.dep_deposit) as tsum
    , sum(case when aa.taxbill_state = 'Y' then a.dep_deposit end) as tsum_taxbill
from cb_redbank_data aa
    LEFT JOIN cb_deposit a ON aa.oid = a.dep_id
    inner join cb_member b on a.mem_id=b.mem_id
    left join cb_member_register cc ON cc.mem_id = b.mem_id
    left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id
    inner join (
        WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
            UNION ALL
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
        )
        SELECT distinct mem_id
        FROM cmem
        union all
        select mem_id
        from cb_member cm
        where cm.mem_id = ".$this->member->item('mem_id')."
    )  c on a.mem_id = c.mem_id
where ".$where.$sch." LIMIT 1
        ";
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~3 : ".$sql);
        $query_row2 = $this->db->query($sql, $param)->row();
        $view['tsum'] = $query_row2->tsum;
        $view['tsum_taxbill'] = $query_row2->tsum_taxbill;

        /*
         $sql = "
         select sum(a.dep_deposit) as tsum
         from cb_redbank_data aa LEFT JOIN cb_deposit a ON aa.oid = a.dep_id inner join cb_member b on a.mem_id=b.mem_id  left join cb_member_register cc ON cc.mem_id = b.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id  inner join
         (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
         FROM cb_member_register cmr
         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
         WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
         UNION ALL
         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
         FROM cb_member_register cmr
         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
         JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
         )
         SELECT distinct mem_id
         FROM cmem
         union all
         select mem_id
         from cb_member cm
         where cm.mem_id = ".$this->member->item('mem_id')."
         )  c on a.mem_id = c.mem_id
         where ".$where.$sch." LIMIT 1";
         log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~3 : ".$sql);
         $view['tsum'] = $this->db->query($sql, $param)->row()->tsum;
         */
        // 2022-04-06 세금계산서 발행 관련으로 변경 끝

        // 2022-04-06 세금계산서 발행 관련으로 변경 시작
        $sql = "
select '1' as allss
    ".$sum_case."
from cb_redbank_data aa
    LEFT JOIN cb_deposit a ON aa.oid = a.dep_id
    inner join cb_member b on a.mem_id=b.mem_id
    left join cb_member_register cc ON cc.mem_id = b.mem_id
    left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id
    inner join (
        WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
            UNION ALL
            SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
            FROM cb_member_register cmr
            JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
            JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
        )
        SELECT distinct mem_id
        FROM cmem
        union all
        select mem_id
        from cb_member cm
        where cm.mem_id = ".$this->member->item('mem_id')."
    )  c on a.mem_id = c.mem_id
where  ".$where.$sch;
        /*
         $sql = "
         select '1' as allss
         ".$sum_case."
         from cb_redbank_data aa LEFT JOIN cb_deposit a ON aa.oid = a.dep_id inner join cb_member b on a.mem_id=b.mem_id  left join cb_member_register cc ON cc.mem_id = b.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id  inner join
         (	WITH RECURSIVE cmem(mem_id, parent_id, mem_username) AS (
         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
         FROM cb_member_register cmr
         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
         WHERE cm.mem_id = ".$this->member->item('mem_id')." and cmr.mem_id <> ".$this->member->item('mem_id')."
         UNION ALL
         SELECT cmr.mem_id, cmr.mrg_recommend_mem_id AS parent_id, cm.mem_username
         FROM cb_member_register cmr
         JOIN cb_member cm ON cmr.mrg_recommend_mem_id = cm.mem_id
         JOIN cmem c ON cmr.mrg_recommend_mem_id = c.mem_id
         )
         SELECT distinct mem_id
         FROM cmem
         union all
         select mem_id
         from cb_member cm
         where cm.mem_id = ".$this->member->item('mem_id')."
         )  c on a.mem_id = c.mem_id
         where ".$where.$sch;
         */
        // 2022-04-06 세금계산서 발행 관련으로 변경 끝
        // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~4 : ".$sql);
        $view['monthlist'] = $this->db->query($sql)->row();


        // $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array(
            'path' => 'biz/manager/pendingbank',
            'layout' => 'layout',
            'skin' => 'bank_stat',
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
     * 미수내역
     */
    public function errlist()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_pendingbank_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['perpage'] = ($this->input->get("perpage")) ? $this->input->get("perpage") : 20;
        $view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $view['param']['dep_status'] = $this->input->get("dep_status");

        $sch = "and state != 'T' /* 처리상태(T.정상처리, F.처리안됨, E.deposit 반영실패) */ ";

        $sql = "select count(1) as cnt from cb_redbank_data where 1=1 ". $sch;
        $view['total_rows'] = $this->db->query($sql)->row()->cnt;
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        $sql = "
		select *
		from cb_redbank_data
		where 1=1 ". $sch ."
		order by idx desc
		limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        //log_message("ERROR", $sql);
        $view['list'] = $this->db->query($sql, $param)->result();

        $view['view']['canonical'] = site_url();
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array(
            'path' => 'biz/manager/pendingbank',
            'layout' => 'layout',
            'skin' => 'errlist',
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
     * 입금내역 수정
     */
    public function write($pid = 0)
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_pendingbank_write';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

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

            if (element('dep_status', $getdata) === '1') {
                alert('이미 완납처리한 입금내역은 더 이상 수정할 수 없습니다');
            }
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
                'field' => 'dep_cash_status',
                'label' => '결제상태',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'dep_cash',
                'label' => '실제결제한 금액',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'dep_deposit_datetime',
                'label' => '결제일시',
                'rules' => 'trim',
            ),
            array(
                'field' => 'dep_admin_memo',
                'label' => '관리자 메모',
                'rules' => 'trim',
            ),
        );
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
            $layoutconfig = array(
                'path' => 'biz/manager/pendingbank',
                'layout' => 'layout',
                'skin' => 'write',
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
                    'dep_admin_memo' => $this->input->post('dep_admin_memo', null, ''),
                );
                if ($this->input->post('dep_cash_status') === 'not') {
                    $updatedata['dep_cash'] = 0;
                    $msg = '정상적으로 수정되었습니다';
                }
                if ($this->input->post('dep_cash_status') === 'some') {
                    $updatedata['dep_cash'] = (int) $this->input->post('dep_cash', null, 0);
                    $msg = '정상적으로 수정되었습니다';
                }
                if ($this->input->post('dep_cash_status') === 'all') {

                    $sum = $this->Deposit_model->get_deposit_sum(element('mem_id', $getdata));
                    $deposit_sum = $sum + element('dep_deposit_request', $getdata);

                    $updatedata['dep_deposit_datetime'] = $this->input->post('dep_deposit_datetime');
                    $updatedata['dep_cash'] = element('dep_cash_request', $getdata);
                    $updatedata['dep_deposit'] = element('dep_deposit_request', $getdata);
                    $updatedata['dep_deposit_sum'] = $deposit_sum;
                    $updatedata['dep_status'] = 1;
                    $msg = '완납처리가 되었습니다';
                }
                $this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);

                if ($this->input->post('dep_cash_status') === 'all' && ! element('dep_status', $getdata)) {
                    $this->depositlib->update_member_deposit(element('mem_id', $getdata));
                    /* 2017.11.27 회원 예치금로그 */
                    $this->load->model("Biz_model");
                    $this->Biz_model->deposit_appr(element('mem_id', $getdata), element('dep_id', $getdata));
                    /*----------------------------*/
                    $this->depositlib->alarm('approve_bank_to_deposit', element('dep_id', $getdata));
                }

                $this->session->set_flashdata('message', $msg);
            }

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

    /**
     * 미처리내역 수정화면
     */
    public function errwrite($usmsid = "")
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_pendingbank_write';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

		//회원 목록 조회
		//$view['member'] = $this->Biz_dhn_model->get_child_member($this->member->item('mem_id'), 1);

		//레드뱅킹 미처리 내역 조회
		$sql = "select * from cb_redbank_data where usmsid = '". $usmsid ."' ";
		$view['red'] = $this->db->query($sql)->row();
		 if($view['red']->state == 'T') { //처리상태(T.정상처리, F.처리안됨, E.deposit 반영실패)
			alert('이미 정상처리한 내역은 더 이상 수정할 수 없습니다.');
		}
		 if($view['red']->usmsid == '') {
			alert('잘못된 접근이거나 데이타가 존재하지 않습니다.');
		}

		$page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array(
			'path' => 'biz/manager/pendingbank',
			'layout' => 'layout',
			'skin' => 'errwrite',
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

    /**
     * 미처리내역 수정화면 => 저장
     */
    public function errwrite_save()
    {
		$idx = $this->input->post("idx"); //일련번호
		$usmsid = $this->input->post("usmsid"); //레드뱅킹ID
		$memid = $this->input->post("memid"); //회원번호
		$memo = $this->input->post("memo"); //관리자 메모

		//회원번호 조회
		$sql = "select * from cb_member where mem_id = '". $memid ."' /* 회원번호 */ ";
		$mem = $this->db->query($sql)->row();
		$mem_id = $mem->mem_id; //회원번호

		if($mem_id == ""){
			$code = "1";
			$msg = "일치하는 회원정보가 없습니다.";
		}else{
			//레드뱅킹 등록수 확인
			$sql = "
			select *
			from cb_redbank_data
			where idx = '". $idx ."' /* 일련번호 */
			and usmsid = '". $usmsid ."' /* 레드뱅킹ID */ ";
			$red = $this->db->query($sql)->row();
			if($red->usmsid != ""){
				//가맹점거래번호 생성
				$this->load->model('Unique_id_model');
				$SERVER_ADDR = $_SERVER['SERVER_ADDR'];
				$unique_id = $this->Unique_id_model->get_id($SERVER_ADDR);

				//전체 충전금액 조회
				$this->db->select_sum('dep_deposit');
				$this->db->where(array('mem_id' => $mem_id, 'dep_status' => 1));
				$result = $this->db->get('deposit');
				$dpsum = $result->row_array();
				$sum = isset($dpsum['dep_deposit']) ? $dpsum['dep_deposit'] : 0;
				$deposit_sum = $sum + $red->money;

				//은행계좌
				$bank_info = $this->funn->fnBankInfo($red->bank_num);
				//log_message('ERROR', $_SERVER['REQUEST_URI'] .' > red->bank_num : '. $red->bank_num .', bank_info : '. $bank_info);

				//충전내역 추가
				$insertdata = array();
				$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
				$insertdata['dep_deposit_datetime'] = cdate('Y-m-d H:i:s');
				$insertdata['dep_deposit_request'] = 0;
				$insertdata['dep_deposit'] = $red->money;
				$insertdata['dep_deposit_sum'] = $deposit_sum;
				$insertdata['mem_realname'] = $red->sender;
				$insertdata['dep_cash_request'] = $red->money;
				$insertdata['dep_cash'] = $red->money;
				$insertdata['dep_status'] = 1;
				$insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 적립 (무통장입금)';
				$insertdata['dep_id'] = $unique_id;
				$insertdata['mem_id'] = $mem_id;
				$insertdata['mem_nickname'] = $mem->mem_nickname;
				$insertdata['mem_email'] = $mem->mem_email;
				$insertdata['mem_phone'] = $mem->mem_phone;
				$insertdata['dep_from_type'] = 'cash';
				$insertdata['dep_to_type'] = 'deposit';
				$insertdata['dep_pay_type'] = 'bank';
				$insertdata['dep_admin_memo'] = $memo;
				$insertdata['dep_bank_info'] = $bank_info;
				$insertdata['status'] = 'OK';
				//$insertdata['dep_ip'] = $this->input->ip_address();
				//$insertdata['dep_useragent'] = $this->agent->agent_string();
				$insertdata['is_test'] = $this->cbconfig->item('use_pg_test');
				$dep_rtn = $this->db->insert("cb_deposit", $insertdata); //충전내역 추가

                $sum2 = $this->db
                    ->select('IFNULL(SUM(pdt_amount), 0) AS sum')
                    ->from('cb_pre_deposit_tg')
                    ->where('pdt_mem_id', $mem_id)
                    ->get()
                    ->row()
                    ->sum;

                if ($sum2 > 0){
                    if ($red->money >= $sum2){
                        $ins_sum = $sum2;
                    } else {
                        $ins_sum = $red->money;
                    }
                    $insertdata2 = array(
                        'pdt_mem_id' => $mem_id,
                        'pdt_amount' => ((-1) * $ins_sum),
                        'pdt_dep_id' => $unique_id,
                        'pdt_process_object' => "dhn"
                    );
                    $this->db->insert("cb_pre_deposit_tg", $insertdata2);
                }

				if($dep_rtn){
					$this->depositlib->update_member_deposit($mem_id);
					$this->deposit_appr($mem_id, $unique_id);
					$this->depositlib->alarm('approve_bank_to_deposit', $unique_id);
                    //이벤트 관련 2021-10-06 윤재박 추가
                    $this->deposit_eventplus($mem_id, $unique_id);
                    $this->pre_deposit($mem_id, $unique_id);//선충전차감
					//레드뱅킹 처리상태 수정
					$where = array();
					$where["idx"] = $idx; //일련번호
					$where["usmsid"] = $usmsid; //레드뱅킹ID
					$data = array();
					$data["oid"] = $unique_id; //가맹점거래번호
					$data["mem_id"] = $mem_id; //회원번호
					$data["state"] = "T"; //처리상태(T.정상처리, F.처리안됨, E.deposit 반영실패)
					$red_rtn = $this->db->update("cb_redbank_data", $data, $where); //레드뱅킹 처리상태 수정

					$code = "0";
					$msg = "OK";
				}else{
					$code = "3";
					$msg = "충전내역 추가 오류 입니다.";
				}
			}else{
				$code = "2";
				$msg = "일치하는 레드뱅킹 정보가 없습니다.";
			}
		}

		$result = array();
		$result['code'] = $code;
		$result['msg'] = $msg;
		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
    }

    //회원별 충전내역 처리
	function deposit_appr($mem_id, $dep_id)
    {
        $deposit = $this->db->query("select a.*, b.mem_userid from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id where a.mem_id=? and a.dep_id=?", array($mem_id, $dep_id))->row();
        /* 포인트 가중치를 입금자의 것으로 적용해야함! */
        $adPoint = 0;
        $weight = $this->db->query("select * from cb_wt_member_addon where mad_mem_id=?", array($deposit->mem_id))->row_array();
        if($weight && count($weight) > 0) {
            $adPoint = intval($deposit->dep_deposit * ($weight["mad_weight".($this->deposit[$deposit->dep_deposit] + 1)] / 100));
        }
        if($deposit && $deposit->dep_status > 0 && $deposit->dep_deposit != 0) {
            $dData['amt_datetime'] = cdate('Y-m-d H:i:s');
            $dData['amt_kind'] = ($deposit->dep_deposit > 0) ? '1' : '2';
            $dData['amt_amount'] = abs($deposit->dep_deposit);
            $dData['amt_point'] = $adPoint;
            $dData['amt_memo'] = $deposit->dep_content;
            $dData['amt_reason'] = $deposit->dep_id;
            $this->db->insert("cb_amt_".$deposit->mem_userid, $dData);

            if ($this->db->error()['code'] > 0) { return 0; }
            //- 충전인 경우만 보너스 포인트 처리
            if($deposit->dep_deposit > 0) {
                /* 포인트 보너스 */
                $sql = "update cb_member set mem_point = ifnull(mem_point, 0) + ".$adPoint." where mem_id=?";
                $this->db->query($sql, array($deposit->mem_id));
                $pData = array();
                $pData['mem_id'] = $deposit->mem_id;
                $pData['poi_datetime'] = cdate('Y-m-d H:i:s');
                $pData['poi_content'] = "예치금 보너스 적립";
                $pData['poi_point'] = $adPoint;
                $pData['poi_type'] = "deposit";
                $pData['poi_related_id'] = $deposit->mem_id;
                $pData['poi_action'] = "예치금충전(".number_format($deposit->dep_deposit).")";
                $this->db->insert("cb_point", $pData);
                if ($this->db->error()['code'] < 1) { return 1; } else { return 0; }
            }
        }
        return 0;
    }

    //선충전 차감 2021-11-01 윤재박
    function pre_deposit($mem_id, $dep_id){
        $deposit = $this->db->query("select a.*, b.mem_userid from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id where a.mem_id=? and a.dep_id=?", array($mem_id, $dep_id))->row();
        // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > pre_deposit');
        if($deposit && $deposit->dep_status > 0 && $deposit->dep_deposit != 0) {
            $sql = " SELECT count(*) AS cnt FROM cb_pre_deposit WHERE mem_id = ".$mem_id;
            // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > sql : '.$sql);
            $pre_cnt = $this->db->query($sql)->row()->cnt;
            // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > pre_cnt : '.$pre_cnt);
            if($pre_cnt>0){
                $sql = " SELECT pre_cash FROM cb_pre_deposit WHERE mem_id = ".$mem_id;
                $pre_cash = $this->db->query($sql)->row()->pre_cash;
                // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > sql : '.$sql);
                if($pre_cash>0){
                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > pre_cash : '.$pre_cash);
                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > deposit->dep_deposit : '.$deposit->dep_deposit);
                    $sql = "select * from cb_member where mem_useyn = 'Y' and mem_id = '". $mem_id ."' ";
                    $mem = $this->db->query($sql)->row();
                    $minusdeposit = 0;
                    $remaindeposit = 0;
                    if($pre_cash >= $deposit->dep_deposit){
                        $remaindeposit = $pre_cash - $deposit->dep_deposit;
                        $minusdeposit = $deposit->dep_deposit;
                        $minusdeposit = $minusdeposit*-1;
                    }else{
                        $remaindeposit=0;
                        $minusdeposit = $pre_cash;
                        $minusdeposit = $minusdeposit*-1;
                    }

                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > minusdeposit : '.$minusdeposit);
                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > remaindeposit : '.$remaindeposit);

                    //아이디 생성
    				$this->load->model('Unique_id_model');
    				$SERVER_ADDR = $_SERVER['SERVER_ADDR'];
    				$unique_id = $this->Unique_id_model->get_id($SERVER_ADDR);

                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > unique_id : '.$unique_id);

                    //합계구함
                    $this->load->model('Deposit_model');
                    $sum = $this->Deposit_model->get_deposit_sum($mem_id);
                    $deposit_sum = $sum + $minusdeposit;

                    // log_message('ERROR', $_SERVER['REQUEST_URI'] .' > deposit_sum : '.$deposit_sum);

                    //충전내역 추가
    				$insertdata = array();
    				$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
    				$insertdata['dep_deposit_datetime'] = date('Y-m-d H:i:s');
    				$insertdata['dep_deposit_request'] = $minusdeposit;
    				$insertdata['dep_deposit'] = $minusdeposit;
                    $insertdata['dep_deposit_sum'] = $deposit_sum;
    				$insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 선충전 차감';
                    $insertdata['dep_admin_memo'] = '선충전 차감';
    				$insertdata['dep_id'] = $unique_id;
    				$insertdata['mem_id'] = $mem_id;
    				$insertdata['mem_nickname'] = $mem->mem_nickname;
    				$insertdata['dep_from_type'] = 'service';
    				$insertdata['dep_to_type'] = 'deposit';
    				$insertdata['dep_pay_type'] = '';
                    $insertdata['dep_status'] = 1;
    				//$insertdata['dep_ip'] = $this->input->ip_address();
    				//$insertdata['dep_useragent'] = $this->agent->agent_string();
    				// $insertdata['is_test'] = $this->cbconfig->item('use_pg_test');
    				$this->db->insert("cb_deposit", $insertdata); //충전내역 추가



                    $amtData = array();
                    $amtData['amt_datetime'] = cdate('Y-m-d H:i:s');
                    $amtData['amt_kind'] = '2';
                    $amtData['amt_amount'] = abs($minusdeposit);
                    $amtData['amt_point'] = 0;
                    $amtData['amt_memo'] = $insertdata['dep_content'];
                    $amtData['amt_reason'] = $unique_id;
                    $amtData['amt_deduct'] = -1;
                    $this->db->insert("cb_amt_".$deposit->mem_userid, $amtData);

                    $insertD = array();
                    $insertD['pre_cash'] = $remaindeposit;
                    $this->db->where('mem_id', $mem_id);
                    $this->db->update('cb_pre_deposit',$insertD );
                    // $this->db->update("cb_pre_deposit", $insertD, array("mem_id"=>$mem_id));

                }
            }

        }

    }

    //이벤트충전 2021-10-06 윤재박
	function deposit_eventplus($mem_id, $dep_id)
    {
        $deposit = $this->db->query("select a.*, b.mem_userid from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id where a.mem_id=? and a.dep_id=?", array($mem_id, $dep_id))->row();
        /* 포인트 가중치를 입금자의 것으로 적용해야함! */
        $adPoint = 0;
        $weight = $this->db->query("select * from cb_wt_member_addon where mad_mem_id=?", array($deposit->mem_id))->row_array();
        if($weight && count($weight) > 0) {
            $adPoint = intval($deposit->dep_deposit * ($weight["mad_weight".($this->deposit[$deposit->dep_deposit] + 1)] / 100));
        }
        if($deposit && $deposit->dep_status > 0 && $deposit->dep_deposit != 0) {

            // log_message('ERROR', 'Rbank_proc > 4');

            if($deposit->dep_deposit > 0){
                $amt_kind  = 1;
            }
            //이벤트 가능업체 여부
            $where_mem1 = "";
            $eve_cnt = 0;
            if(!empty(config_item('eve1_member'))){
                foreach(config_item('eve1_member') as $r){
                    if($where_mem1!=""){
                        $where_mem1.=",";
                    }
                    $where_mem1.=$r;
                }
                if(!empty($where_mem1)){
                    $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE  cmr.mrg_recommend_mem_id in ( ".$where_mem1." ) and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
                    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql." / mem_id > ".$mem_id);
                    $eve_cnt = $this->db->query($sql)->row()->cnt;
                }
            }
            $where_mem2 = "";
            $eve_cnt2 = 0;
            if(!empty(config_item('eve2_member'))){
                foreach(config_item('eve2_member') as $r){
                    if($where_mem2!=""){
                        $where_mem2.=",";
                    }
                    $where_mem2.=$r;
                }
                if(!empty($where_mem2)){
                    $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE  cmr.mrg_recommend_mem_id in ( ".$where_mem2." ) and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
                    // log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql > ".$sql." / mem_id > ".$mem_id);
                    $eve_cnt2 = $this->db->query($sql)->row()->cnt;
                }
            }
            // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE ( cmr.mrg_recommend_mem_id = 3 or cmr.mrg_recommend_mem_id = 1260) and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
            // $eve_cnt = $this->db->query($sql)->row()->cnt;
            // $sql = "select count(*) as cnt from cb_member cm left Join cb_member_register cmr ON cm.mem_id = cmr.mem_id WHERE cmr.mrg_recommend_mem_id = 962 and cm.mem_level = 1 and cm.mem_id = '".$mem_id."'";
            // $eve_cnt2 = $this->db->query($sql)->row()->cnt;
            log_message('ERROR', 'Pendingbank > eve_cnt'.$eve_cnt.', eve_cnt2'.$eve_cnt2);
            log_message('ERROR', 'Pendingbank > mem_id'.$mem_id);
            log_message('ERROR', 'Pendingbank > dep_id'.$dep_id);
            if($amt_kind=='1' && ($eve_cnt > 0 || $eve_cnt2 > 0)){
            // if($amt_kind=='1' && $eve_cnt > 0){
                $nowTime = date("Y-m-d H:i:s"); // 현재 시간
                if($eve_cnt > 0){ //dhn
                    $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime1'))); // 시작 시간
                    $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime1'))); // 종료 시간
                }else{ //티지
                    $startTime = date("Y-m-d H:i:s", strtotime(config_item('eve_startTime2'))); // 시작 시간
                    $expTime = date("Y-m-d H:i:s", strtotime(config_item('eve_expTime2'))); // 종료 시간
                }
                if($nowTime < $expTime && $nowTime >= $startTime){


                $sql = "select * from cb_member where mem_useyn = 'Y' and mem_id = '". $mem_id ."' ";
                $mem = $this->db->query($sql)->row();


                //금액별 계산
                $bonus_amt = 0;
                if($eve_cnt > 0){ //dhn+대전연합
                    // if($deposit->dep_deposit>=300000 && $deposit->dep_deposit<500000){
                    //   $bonus_amt = 15000;
                    // }else if($deposit->dep_deposit>=500000 && $deposit->dep_deposit<1000000){
                    //   $bonus_amt = 35000;
                    // }else if($deposit->dep_deposit>=1000000 && $deposit->dep_deposit<2000000){
                    //   $bonus_amt = 80000;
                    // }else if($deposit->dep_deposit>=2000000 && $deposit->dep_deposit<3000000){
                    //   $bonus_amt = 180000;
                    // }else if($deposit->dep_deposit>=3000000){
                    //   $bonus_amt = 300000;
                    // }

                    // if($deposit->dep_deposit>=500000 && $deposit->dep_deposit<1000000){
                    //   $bonus_amt = 35000;
                    // }else if($deposit->dep_deposit>=1000000 && $deposit->dep_deposit<2000000){
                    //   $bonus_amt = 90000;
                    // }else if($deposit->dep_deposit>=2000000 && $deposit->dep_deposit<3000000){
                    //   $bonus_amt = 200000;
                    // }else if($deposit->dep_deposit>=3000000 && $deposit->dep_deposit<4000000){
                    //   $bonus_amt = 390000;
                    // }else if($deposit->dep_deposit>=4000000 && $deposit->dep_deposit<5000000){
                    //   $bonus_amt = 600000;
                    // }else if($deposit->dep_deposit>=5000000 && $deposit->dep_deposit<10000000){
                    //   $bonus_amt = 850000;
                    // }else if($deposit->dep_deposit>=10000000){
                    //   $bonus_amt = 2000000;
                    // }

                    if($deposit->dep_deposit>=300000 && $deposit->dep_deposit<500000){
                      $bonus_amt = 15000;
                    }else if($deposit->dep_deposit>=500000 && $deposit->dep_deposit<1000000){
                      $bonus_amt = 35000;
                    }else if($deposit->dep_deposit>=1000000 && $deposit->dep_deposit<3000000){
                      $bonus_amt = 100000;
                    }else if($deposit->dep_deposit>=3000000 && $deposit->dep_deposit<5000000){
                      $bonus_amt = 390000;
                    }else if($deposit->dep_deposit>=5000000 && $deposit->dep_deposit<10000000){
                      $bonus_amt = 850000;
                    }else if($deposit->dep_deposit>=10000000){
                      $bonus_amt = 2000000;
                    }
                }else if($eve_cnt2 > 0){ //티지
                    if($deposit->dep_deposit>=300000 && $deposit->dep_deposit<500000){
                      $bonus_amt = 15000;
                    }else if($deposit->dep_deposit>=500000 && $deposit->dep_deposit<1000000){
                      $bonus_amt = 35000;
                    }else if($deposit->dep_deposit>=1000000 && $deposit->dep_deposit<2000000){
                      $bonus_amt = 80000;
                    }else if($deposit->dep_deposit>=2000000 && $deposit->dep_deposit<3000000){
                      $bonus_amt = 180000;
                    }else if($deposit->dep_deposit>=3000000){
                      $bonus_amt = 300000;
                    }
                }

                if($bonus_amt>0){
                    //아이디 생성
    				$this->load->model('Unique_id_model');
    				$SERVER_ADDR = $_SERVER['SERVER_ADDR'];
    				$unique_id = $this->Unique_id_model->get_id($SERVER_ADDR);

                    //합계구함
                    $this->load->model('Deposit_model');
                    $sum = $this->Deposit_model->get_deposit_sum($mem_id);
                    $deposit_sum = $sum + $this->input->post('dep_deposit', null, 0);

                    //충전내역 추가
    				$insertdata = array();
    				$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
    				$insertdata['dep_deposit_datetime'] = date('Y-m-d H:i:s');
    				$insertdata['dep_deposit_request'] = $bonus_amt;
    				$insertdata['dep_deposit'] = $bonus_amt;
                    $insertdata['dep_deposit_sum'] = $deposit_sum;
    				$insertdata['mem_realname'] = '';
    				$insertdata['dep_cash_request'] = $bonus_amt;
    				$insertdata['dep_cash'] = 0;
    				$insertdata['dep_status'] = 0;
    				$insertdata['dep_content'] = $this->depositconfig->item('deposit_name') . ' 이벤트 (서비스입금)';
                    $insertdata['dep_admin_memo'] = '이벤트 서비스입금';
    				$insertdata['dep_id'] = $unique_id;
    				$insertdata['mem_id'] = $mem_id;
    				$insertdata['mem_nickname'] = $mem->mem_nickname;
    				$insertdata['mem_email'] = '';
    				$insertdata['mem_phone'] = '';
    				$insertdata['dep_from_type'] = 'service';
    				$insertdata['dep_to_type'] = 'deposit';
    				$insertdata['dep_pay_type'] = 'service';
                    $insertdata['dep_status'] = 1;
    				//$insertdata['dep_ip'] = $this->input->ip_address();
    				//$insertdata['dep_useragent'] = $this->agent->agent_string();
    				// $insertdata['is_test'] = $this->cbconfig->item('use_pg_test');
    				$this->db->insert("cb_deposit", $insertdata); //충전내역 추가

                    $amtData = array();
                    $amtData['amt_datetime'] = cdate('Y-m-d H:i:s');
                    $amtData['amt_kind'] = '1';
                    $amtData['amt_amount'] = abs($bonus_amt);
                    $amtData['amt_point'] = $adPoint;
                    $amtData['amt_memo'] = $insertdata['dep_content'].",보너스";
                    $amtData['amt_reason'] = $unique_id;
                    $this->db->insert("cb_amt_".$deposit->mem_userid, $amtData);
                }
              }


            }
            if ($this->db->error()['code'] > 0) { return 0; }
            //- 충전인 경우만 보너스 포인트 처리

        }
        return 0;
    }

    /**
     * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
     */
    public function listdelete()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_pendingbank_listdelete';
        $this->load->event($eventname);

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        /**
         * 체크한 게시물의 삭제를 실행합니다
         */
        if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
            foreach ($this->input->post('chk') as $val) {
                if ($val) {
                    $this->{$this->modelname}->delete($val);
                }
            }
        }

        // 이벤트가 존재하면 실행합니다
        Events::trigger('after', $eventname);

        /**
         * 삭제가 끝난 후 목록페이지로 이동합니다
         */
        $this->session->set_flashdata(
            'message',
            '정상적으로 삭제되었습니다'
            );
        $param =& $this->querystring;
        $redirecturl = $this->pagedir . '?' . $param->output();
        redirect($redirecturl);
    }

	//회원번호 조회
	public function search_mem_id(){
		//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > 스마트전단 데이타 조회");
		if($this->member->is_member() == false) { //로그인이 안된 경우
			redirect('login');
		}
		$result = array();
		$sc = $this->input->post('sc'); //검색타입
		$sv = $this->input->post('sv'); //검색내용
		if($sc != "" and $sv != ""){
		$sql = "
			SELECT
				  mem_id /* 회원번호 */
				, mem_userid /* 회원ID */
				, mem_username /* 업체명 */
				, mem_nickname /* 담당자명 */
			FROM cb_member
			WHERE mem_". $sc ." LIKE '%". addslashes($sv) ."%' and mem_useyn='Y'
			ORDER BY mem_username ASC ";
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > Query : ".$sql);
			$result = $this->db->query($sql)->result();
			//log_message("ERROR", $_SERVER['REQUEST_URI'] ." > result : ".$result);
		}

		$json = json_encode($result,JSON_UNESCAPED_UNICODE);
		header('Content-Type: application/json');
		echo $json;
	}


	// 무통장입금목록 전체목록
	public function due_download() {
	    $view = array();

	    $view['param']['search_type'] = $this->input->post("search_type");
	    $view['param']['start_date'] = $this->input->post("start_date");
	    $view['param']['end_date'] = $this->input->post("end_date");


	    $param = array();

	    $sql = "
            select a.*, b.*, ccc.mem_username as adminname
			from cb_deposit a inner join cb_member b on a.mem_id=b.mem_id  left join cb_member_register cc ON cc.mem_id = b.mem_id left join cb_member ccc ON cc.mrg_recommend_mem_id = ccc.mem_id  inner join
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
            where dep_pay_type = 'bank'
        ";

	    if ($view['param']['search_type'] == "reg") {
	        $sql .= " and dep_datetime BETWEEN '".$view['param']['start_date']."' AND '".$view['param']['end_date']."' order by a.dep_datetime DESC";
	    } else if ($view['param']['search_type'] == "due") {
	        $sql .= " and dep_deposit_datetime BETWEEN '".$view['param']['start_date']."' AND '".$view['param']['end_date']."' order by a.dep_deposit_datetime DESC";
	    } else {
	        $sql .= " order by a.dep_datetime DESC";
	    }

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
	    ),	'A1:J1');

	    $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "회원아이디");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "소속");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "업체명");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "예금주");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "연락처");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "요청일시");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "처리일시");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "총결제해야할 금액");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "결제금액");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "미수금액");

	    $cnt = 1;
	    $row = 2;
	    //number_format(($r->dep_datetime, 1)
	    foreach($list as $r) {
	        $str2n = "";
	        switch($r->mem_2nd_send) { case 'GREEN_SHOT' : $str2n = '웹(A)'; break; case 'NASELF': $str2n = '웹(B)'; break; case 'SMART':$str2n = '웹(C)'; break; default:$str2n = '';}
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->mem_userid);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->adminname);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $r->mem_username);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $r->mem_realname);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $r->mem_phone);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $r->dep_datetime);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $r->dep_deposit_datetime);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $r->dep_cash_request);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $r->dep_cash);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format($r->dep_cash_request - abs($r->dep_cash)));
	        $row++;
	        $cnt++;
	    }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="readbank_deposit_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');

	}


	// 무통장입금 미처리 목록
	public function unprocessed_download() {
	    $view = array();

	    $view['param']['start_date'] = $this->input->post("start_date");
	    $view['param']['end_date'] = $this->input->post("end_date");


	    $param = array();

	    $sql = "
    		select *
    		from cb_redbank_data
    		where state != 'T' and cre_date BETWEEN '".$view['param']['start_date']."' AND '".$view['param']['end_date']."'
    		order by idx desc
        ";


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
	    ),	'A1:D1');

	    $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
	    $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

	    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);

	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "입금자명(사업자번호)");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "요청일시");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "결체한 금액");
	    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "미처리 금액");


	    $cnt = 1;
	    $row = 2;
	    //number_format(($r->dep_datetime, 1)
	    foreach($list as $r) {
	        $str2n = "";
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $r->sender);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $r->cre_date);
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, number_format($r->money));
	        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, number_format($r->money));

	        $row++;
	        $cnt++;
	    }

	    // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="readbank_unprocessed_list.xls"');
	    header('Cache-Control: max-age=0');

	    // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
	    // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
	    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

	    // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
	    $objWriter->save('php://output');

	}

	// 2022-04-06 세금계산서 발행으로 함수 추가
	// 무통장입금 테이블의 taxbill_state필드의 값을 'Y' 업데이트
	public function bill_issuer_update() {
	    $oids = $this->input->post('oids');

	    if(!$oids) {
	        echo '{"success": false}';
	        exit;
	    }

	    $sql = "
            UPDATE igenie.cb_redbank_data
            SET taxbill_state='Y'
            WHERE oid in (".$oids.");
        ";

	    //        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~update : ".$sql);

	    $param = Array();
	    $this->db->query($sql, $param);

	    header('Content-Type: application/json');
	    if ($this->db->error()['code'] < 1) {
	        echo '{"success": true}';
	    } else {
	        echo '{"success": false}';
	    }

	    //         header('Content-Type: application/json');
	    //         echo '{"success": true}';
	}

	// 2022-04-06 세금계산서 발행으로 함수 추가
	// 무통장입금 테이블의 taxbill_down필드의 값을 'Y' 업데이트
	public function bill_down_update() {
	    $oids = $this->input->post('oids');

	    if(!$oids) {
	        echo '{"success": false}';
	        exit;
	    }

	    $sql = "
            UPDATE igenie.cb_redbank_data
            SET taxbill_down='Y'
            WHERE oid in (".$oids.");
        ";

	    //        log_message("ERROR", $_SERVER['REQUEST_URI'] ." > sql~~~~~~~~~~~update : ".$sql);

	    $param = Array();
	    $this->db->query($sql, $param);

	    header('Content-Type: application/json');
	    if ($this->db->error()['code'] < 1) {
	        echo '{"success": true}';
	    } else {
	        echo '{"success": false}';
	    }

	    //         header('Content-Type: application/json');
	    //         echo '{"success": true}';
	}

	// 2022-04-06 세금계산서 발행으로 세금계산서 엑셀 다운로드 함수 추가
	public function download_taxbill() {
	    $oids = $this->input->post('oids');
	    $uri = $this->input->post('uri');

	    $sql = "
SELECT
    a.dep_id, DATE_FORMAT(CURDATE(), '%Y%m%d') AS cur_date, DATE_FORMAT(CURDATE(), '%d') AS cur_day, a.mem_id, a.dep_cash,
    b.mem_biz_reg_no, b.mem_biz_reg_name, b.mem_biz_reg_rep_name, b.mem_biz_reg_add1, b.mem_biz_reg_add2, b.mem_biz_reg_add3,
    b.mem_biz_reg_biz, b.mem_biz_reg_sector, b.mem_email, b.mem_biz_reg_email1, b.mem_biz_reg_email2, b.mem_biz_reg_bigo
FROM igenie.cb_deposit a LEFT JOIN igenie.cb_member b on a.mem_id = b.mem_id
WHERE dep_id IN (".$oids.")
ORDER BY a.dep_id ASC
        ";
	    //log_message("ERROR", $value->mem_id);
	    //log_message("ERROR", $sql);
	    $result_query = $this->db->query($sql)->result();

	    $spreadsheet = new Spreadsheet();
	    $spreadsheet->getDefaultStyle()->getFont()->setName('돋움');
	    $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
	    $cur_worksheet = $spreadsheet->getActiveSheet();
	    $cur_worksheet->getRowDimension(2)->setRowHeight(25);

	    $cur_worksheet->setTitle('엑셀업로드양식');
	    $cur_worksheet->setCellValue("A6", "전자(세금)계산서 종류
(01:일반, 02:영세율)")->getStyle("A6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("B6", "작성일자
(8자리,
YYYYMMDD 형식)")->getStyle("B6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("C6", "공급받는자 등록번호
(\"-\" 없이 입력)")->getStyle("C6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("D6", "공급받는자
종사업장번호")->getStyle("D6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("E6", "공급받는자 상호")->getStyle("E6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("F6", "공급받는자 성명")->getStyle("F6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("G6", "공급받는자 사업장주소")->getStyle("G6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("H6", "공급받는자 업태")->getStyle("H6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("I6", "공급받는자 종목")->getStyle("I6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("J6", "공급받는자 이메일1")->getStyle("J6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("K6", "공급받는자 이메일2")->getStyle("K6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("L6", "공급가액")->getStyle("L6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("M6", "세액")->getStyle("M6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("N6", "비고")->getStyle("N6")->getAlignment()->setWrapText(true);

	    $cur_worksheet->setCellValue("O6", "일자1
(2자리, 작성
년월 제외)")->getStyle("O6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("P6", "품목1")->getStyle("P6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("Q6", "규격1")->getStyle("Q6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("R6", "수량1")->getStyle("R6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("S6", "단가1")->getStyle("S6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("T6", "공급가액")->getStyle("T6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("U6", "세액1")->getStyle("U6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("V6", "품목비고1")->getStyle("V6")->getAlignment()->setWrapText(true);

	    $cur_worksheet->setCellValue("W6", "일자2
(2자리, 작성
년월 제외)")->getStyle("W6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("X6", "품목2")->getStyle("X6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("Y6", "규격2")->getStyle("Y6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("Z6", "수량2")->getStyle("Z6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AA6", "단가2")->getStyle("AA6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AB6", "공급가액2")->getStyle("AB6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AC6", "세액2")->getStyle("AC6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AD6", "품목비고2")->getStyle("AD6")->getAlignment()->setWrapText(true);

	    $cur_worksheet->setCellValue("AE6", "일자3
(2자리, 작성
년월 제외)")->getStyle("AE6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AF6", "품목3")->getStyle("AF6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AG6", "규격3")->getStyle("AG6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AH6", "수량3")->getStyle("AH6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AI6", "단가3")->getStyle("AI6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AJ6", "공급가액3")->getStyle("AJ6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AK6", "세액3")->getStyle("AK6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AL6", "품목비고3")->getStyle("AL6")->getAlignment()->setWrapText(true);

	    $cur_worksheet->setCellValue("AM6", "일자4
(2자리, 작성
년월 제외)")->getStyle("AM6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AN6", "품목4")->getStyle("AN6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AO6", "규격4")->getStyle("AO6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AP6", "수량4")->getStyle("AP6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AQ6", "단가4")->getStyle("AQ6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AR6", "공급가액4")->getStyle("AR6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AS6", "세액4")->getStyle("AS6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AT6", "품목비고4")->getStyle("AT6")->getAlignment()->setWrapText(true);

	    $cur_worksheet->setCellValue("AU6", "현금")->getStyle("AU6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AV6", "수표")->getStyle("AV6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AW6", "어음")->getStyle("AW6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AX6", "외상미수금")->getStyle("AX6")->getAlignment()->setWrapText(true);
	    $cur_worksheet->setCellValue("AY6", "영수(01),
청구(02)")->getStyle("AY6")->getAlignment()->setWrapText(true);

	    $next_row_count = 6;

	    if ($result_query) {
	        foreach ($result_query as $r) {
	            $next_row_count++;
	            $cur_worksheet->setCellValueExplicit('A'.$next_row_count, "01", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('B'.$next_row_count, $r->cur_date, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

	            $biz_reg_add = $r->mem_biz_reg_add1;
	            if ($r->mem_biz_reg_add2 != "") {
	                $biz_reg_add .= " ".$r->mem_biz_reg_add2;
	            }
	            if ($r->mem_biz_reg_add3 != "") {
	                $biz_reg_add .= " ".$r->mem_biz_reg_add3;
	            }
	            $tot_amount = round($r->dep_cash / 1.1); //공급가액
	            // $tot_amount_tax = round($tot_amount * 0.1); //공급가 세액
	            $tot_amount_tax = $r->dep_cash - $tot_amount; //공급가 세액

	            $cur_worksheet->setCellValueExplicit('C'.$next_row_count, $r->mem_biz_reg_no, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('E'.$next_row_count, $r->mem_biz_reg_name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('F'.$next_row_count, $r->mem_biz_reg_rep_name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('G'.$next_row_count, $biz_reg_add, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('H'.$next_row_count, $r->mem_biz_reg_biz, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('I'.$next_row_count, $r->mem_biz_reg_sector, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('J'.$next_row_count, $r->mem_email, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('K'.$next_row_count, $r->mem_biz_reg_email2, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('L'.$next_row_count, $tot_amount, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('M'.$next_row_count, $tot_amount_tax, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('N'.$next_row_count, $r->mem_biz_reg_bigo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

	            $cur_worksheet->setCellValueExplicit('O'.$next_row_count, $r->cur_day, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('P'.$next_row_count, "충전금액", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('T'.$next_row_count, $tot_amount, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	            $cur_worksheet->setCellValueExplicit('U'.$next_row_count, $tot_amount_tax, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

	            $cur_worksheet->setCellValueExplicit('AY'.$next_row_count, "01", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

	        }
	    }

	    $filename = '세금계산서_'.$value->period_name;

	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
	    // header('Cache-Control: max-age=0');

	    // $writer = new Xlsx($spreadsheet);
	    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

	    ob_start();
	    $writer->save('php://output');
	    $xlsData = ob_get_contents();
	    ob_end_clean();

	    $response =  array(
	        'op' => 'ok',
	        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
	    );
	    die(json_encode($response));
	}

    public function secondlist()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_deposit_pendingbank_index';
        $this->load->event($eventname);

        $per_page = 50;

        $view = array();
        $view['view'] = array();
        $view['param']['page'] = ($this->input->get("page")) ? $this->input->get("page") : 1;

        $this->db
            ->select("*")
            ->from("cb_redbank_2nd_data");
        $query = $this->db->get();
        $total_row = $query->num_rows();
        $view["total_row"] = $total_row;
        $this->db
            ->select(array("a.*", "b.*", "c.*", "e.mem_username AS adminname"))
            ->from("cb_redbank_2nd_data a")
            ->join("cb_member b", "a.mem_id = b.mem_id", "left")
            ->join("cb_redbank_data c", "a.oid = c.oid", "left")
            ->join("cb_member_register d", "d.mem_id = b.mem_id", "left")
            ->join("cb_member e", "d.mrg_recommend_mem_id = e.mem_id", "left")
            ->order_by("a.oid", "desc")
            ->limit($per_page, ($view['param']['page'] - 1) * $per_page);
        $query = $this->db->get();
        $view["list"] = $query->result();

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $total_row;
        $page_cfg['per_page'] = $per_page;
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();
        $view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array(
            'path' => 'biz/manager/pendingbank',
            'layout' => 'layout',
            'skin' => 'secondlist',
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
}
?>
