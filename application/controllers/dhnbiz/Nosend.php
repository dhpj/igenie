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
class Nosend extends CB_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = '/dhnbiz/nosend';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array();

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = '';

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
        $this->load->library(array('pagination', 'querystring'));
    }

    /**
     * 목록을 가져오는 메소드입니다
     */
    public function index()
    {
        $mem_id = $this->member->item("mem_id");

        if (empty($mem_id)){
            Redirect("/");
        }

        $view = array();
        $view['view'] = array();
        $view['perpage'] = (!empty($this->input->get("perpage"))) ? $this->input->get("perpage") : "20";
        $view['page'] = (!empty($this->input->get("page"))) ? $this->input->get("page") : "1";
        $view['userid'] = (!empty($this->input->get("userid"))) ? $this->input->get("userid") : "ALL";
        $view['dormancy_yn'] = (!empty($this->input->get("dormancy_yn"))) ? $this->input->get("dormancy_yn") : "ALL";
        $view['searchType'] = (!empty($this->input->get("searchType"))) ? $this->input->get("searchType") : "userid";
        $view['searchStr'] = (!empty($this->input->get("searchStr"))) ? $this->input->get("searchStr") : "";

        $where = " ";

        if ($view["userid"] != "ALL"){
            $where .= " AND b.spf_mem_id = " . $view["userid"] . " ";
        }

        if ($view["dormancy_yn"] != "ALL"){
            if ($view["dormancy_yn"] == "N"){
                $where .= " AND cndd.mdd_dormant_flag = 0 ";
            } else {
                $where .= " AND cndd.mdd_dormant_flag = 1 ";
            }
        }

        if (!empty($view['searchStr'])){
            if ($view['searchType'] == "userid"){
                $where .= " AND b.spf_mem_id LIKE '%" . $view['searchStr'] . "%' ";
            } else if ($view['searchType'] == "mem_username"){
                $where .= " AND a.mem_username LIKE '%" . $view['searchStr'] . "%' ";
            } else if ($view['searchType'] == "mem_nickname"){
                $where .= " AND a.mem_nickname LIKE '%" . $view['searchStr'] . "%' ";
            } else if ($view['searchType'] == "salesoffice"){
                $where .= " AND dbo.dbo_name LIKE '%" . $view['searchStr'] . "%' ";
            } else if ($view['searchType'] == "salesperson"){
                $where .= " AND ds.ds_name LIKE '%" . $view['searchStr'] . "%' ";
            }
        }

        $sql = "
            SELECT
                COUNT(*) as cnt
            FROM
                cb_wt_send_profile_dhn b
            LEFT JOIN
            	cb_member a
            	ON
            	b.spf_mem_id = a.mem_id
            LEFT JOIN
            	cb_member_dormant_dhn cndd
            	ON
            	a.mem_id = cndd.mem_id
            LEFT JOIN
                dhn_salesperson2 ds
                ON
                a.mem_sales_mng_id = ds.ds_id
            LEFT JOIN
                dhn_branch_office2 dbo
                ON
                a.mem_sale_point_id = dbo.dbo_id
            WHERE
            	b.spf_mem_id IN (
            		WITH RECURSIVE
            			cmem(mem_id, parent_id, mem_username) AS (
                    		SELECT
                    			cmr.mem_id
                    		  , cmr.mrg_recommend_mem_id AS parent_id
                    		  , cm.mem_username
            		        FROM
            		        	cb_member_register cmr
            		        JOIN
            		        	cb_member cm
            		        	ON
            		        	cmr.mrg_recommend_mem_id = cm.mem_id
            		        WHERE
            		        	cm.mem_id = '" . $mem_id . "'
            		        	AND
            		        	cmr.mem_id <> '" . $mem_id . "'
            		        UNION ALL
                    		SELECT
                    			cmr.mem_id
                    		  , cmr.mrg_recommend_mem_id AS parent_id
                    		  , cm.mem_username
            		        FROM
            		        	cb_member_register cmr
                    		JOIN
                    			cb_member cm
                    			ON
                    			cmr.mrg_recommend_mem_id = cm.mem_id
                    		JOIN
                    			cmem c
                    			ON
                    			cmr.mrg_recommend_mem_id = c.mem_id
                    	)
                    SELECT DISTINCT mem_id
                    FROM cmem
                    UNION ALL
                    SELECT " . $mem_id . " mem_id
            	) AND spf_mem_id NOT IN (
            		SELECT DISTINCT
            			mst_mem_id
            		FROM
            			cb_wt_msg_sent
            	)
                " . $where . "  and a.mem_userid is not null
        ";
        $view["total_rows"] = $this->db->query($sql)->row()->cnt;

        $sql = "
            SELECT
                b.spf_mem_id
              , a.mem_userid
              , a.mem_username
              , a.mem_nickname
              , a.mem_emp_phone
              , a.mem_note
              , a.mem_nickname
              , a.mem_useyn
              , a.mem_cont_cancel_yn
              , a.mem_cont_cancel_date
              , a.mem_emp_phone
              , a.mem_sales_name
              , a.mem_lastlogin_datetime
              , a.mem_lastlogin_ip
              , a.mem_register_datetime
              , ddd.mem_username as adminname
              , ds.ds_name
              , dbo.dbo_name
              , cndd.mdd_dormant_flag
            FROM
                cb_wt_send_profile_dhn b
            LEFT JOIN
            	cb_member a
            	ON
            	b.spf_mem_id = a.mem_id
            LEFT JOIN
            	cb_member_register ccc
            	ON
            	a.mem_id = ccc.mem_id
            LEFT JOIN
            	cb_member ddd
            	ON
            	ccc.mrg_recommend_mem_id = ddd.mem_id
            LEFT JOIN
            	cb_member_dormant_dhn cndd
            	ON
            	a.mem_id = cndd.mem_id
            LEFT JOIN
                dhn_salesperson2 ds
                ON
                a.mem_sales_mng_id = ds.ds_id
            LEFT JOIN
                dhn_branch_office2 dbo
                ON
                a.mem_sale_point_id = dbo.dbo_id
            WHERE
            	b.spf_mem_id IN (
            		WITH RECURSIVE
            			cmem(mem_id, parent_id, mem_username) AS (
                    		SELECT
                    			cmr.mem_id
                    		  , cmr.mrg_recommend_mem_id AS parent_id
                    		  , cm.mem_username
            		        FROM
            		        	cb_member_register cmr
            		        JOIN
            		        	cb_member cm
            		        	ON
            		        	cmr.mrg_recommend_mem_id = cm.mem_id
            		        WHERE
            		        	cm.mem_id = '" . $mem_id . "'
            		        	AND
            		        	cmr.mem_id <> '" . $mem_id . "'
            		        UNION ALL
                    		SELECT
                    			cmr.mem_id
                    		  , cmr.mrg_recommend_mem_id AS parent_id
                    		  , cm.mem_username
            		        FROM
            		        	cb_member_register cmr
                    		JOIN
                    			cb_member cm
                    			ON
                    			cmr.mrg_recommend_mem_id = cm.mem_id
                    		JOIN
                    			cmem c
                    			ON
                    			cmr.mrg_recommend_mem_id = c.mem_id
                    	)
                    SELECT DISTINCT mem_id
                    FROM cmem
                    UNION ALL
                    SELECT " . $mem_id . " mem_id
            	) AND spf_mem_id NOT IN (
            		SELECT DISTINCT
            			mst_mem_id
            		FROM
            			cb_wt_msg_sent
            	)
                " . $where . " and a.mem_userid is not null
            ORDER BY
                a.mem_username ASC
            LIMIT ". (($view['page'] - 1) * $view['perpage'] ) .", ".$view['perpage'];

        $view["list"] = $this->db->query($sql)->result();

        log_message("error", $sql);

        $sql = "
            SELECT
                b.spf_mem_id
              , a.mem_username
            FROM
                cb_wt_send_profile_dhn b
            LEFT JOIN
                cb_member a
                ON
                b.spf_mem_id = a.mem_id
            WHERE
                b.spf_mem_id IN (
                    WITH RECURSIVE
                        cmem(mem_id, parent_id, mem_username) AS (
                            SELECT
                                cmr.mem_id
                              , cmr.mrg_recommend_mem_id AS parent_id
                              , cm.mem_username
                            FROM
                                cb_member_register cmr
                            JOIN
                                cb_member cm
                                ON
                                cmr.mrg_recommend_mem_id = cm.mem_id
                            WHERE
                                cm.mem_id = '" . $mem_id . "'
                                AND
                                cmr.mem_id <> '" . $mem_id . "'
                            UNION ALL
                            SELECT
                                cmr.mem_id
                              , cmr.mrg_recommend_mem_id AS parent_id
                              , cm.mem_username
                            FROM
                                cb_member_register cmr
                            JOIN
                                cb_member cm
                                ON
                                cmr.mrg_recommend_mem_id = cm.mem_id
                            JOIN
                                cmem c
                                ON
                                cmr.mrg_recommend_mem_id = c.mem_id
                        )
                    SELECT DISTINCT mem_id
                    FROM cmem
                    UNION ALL
                    SELECT " . $mem_id . " mem_id
                ) AND spf_mem_id NOT IN (
                    SELECT DISTINCT
                        mst_mem_id
                    FROM
                        cb_wt_msg_sent
                )
            ORDER BY
                a.mem_username ASC
        ";
        $view["select"] = $this->db->query($sql)->result();

		$this->load->library('pagination');
		$page_cfg['link_mode'] = 'open_page';
		$page_cfg['base_url'] = '';
		$page_cfg['total_rows'] = $view['total_rows'];
		$page_cfg['per_page'] = $view['perpage'];
		$this->pagination->initialize($page_cfg);
		$this->pagination->cur_page = intval($page);
		$view['page_html'] = $this->pagination->create_links();
		$view['page_html'] = preg_replace("/\<li\>\<a\ href\=\"\?page\=([0-9]*)\"/","<li style='cursor: pointer;' onclick='open_page($1)'><a herf='#' ",$view['page_html']);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array(
            'path' => 'dhnbiz/nosend',
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

    function download_excel(){
        $mem_id = $this->member->item("mem_id");

        if (empty($mem_id)){
            Redirect("/");
        }

        $view = array();
        $view['view'] = array();
        $view['perpage'] = (!empty($this->input->get("perpage"))) ? $this->input->get("perpage") : "10";
        $view['page'] = (!empty($this->input->get("page"))) ? $this->input->get("page") : "1";
        $view['userid'] = (!empty($this->input->get("userid"))) ? $this->input->get("userid") : "ALL";
        $view['dormancy_yn'] = (!empty($this->input->get("dormancy_yn"))) ? $this->input->get("dormancy_yn") : "ALL";
        $view['searchType'] = (!empty($this->input->get("searchType"))) ? $this->input->get("searchType") : "userid";
        $view['searchStr'] = (!empty($this->input->get("searchStr"))) ? $this->input->get("searchStr") : "";

        $where = " ";

        if ($view["userid"] != "ALL"){
            $where .= " AND b.spf_mem_id = " . $view["userid"] . " ";
        }

        if ($view["dormancy_yn"] != "ALL"){
            if ($view["dormancy_yn"] == "N"){
                $where .= " AND cndd.mdd_dormant_flag = 0 ";
            } else {
                $where .= " AND cndd.mdd_dormant_flag = 1 ";
            }
        }

        if (!empty($view['searchStr'])){
            if ($view['searchType'] == "userid"){
                $where .= " AND b.spf_mem_id LIKE '%" . $view['searchStr'] . "%' ";
            } else if ($view['searchType'] == "mem_username"){
                $where .= " AND a.mem_username LIKE '%" . $view['searchStr'] . "%' ";
            } else if ($view['searchType'] == "mem_nickname"){
                $where .= " AND a.mem_nickname LIKE '%" . $view['searchStr'] . "%' ";
            } else if ($view['searchType'] == "salesoffice"){
                $where .= " AND dbo.dbo_name LIKE '%" . $view['searchStr'] . "%' ";
            } else if ($view['searchType'] == "salesperson"){
                $where .= " AND ds.ds_name LIKE '%" . $view['searchStr'] . "%' ";
            }
        }

        $sql = "
            SELECT
                b.spf_mem_id
              , a.mem_userid
              , a.mem_username
              , a.mem_nickname
              , a.mem_emp_phone
              , a.mem_note
              , a.mem_nickname
              , a.mem_useyn
              , a.mem_cont_cancel_yn
              , a.mem_cont_cancel_date
              , a.mem_emp_phone
              , a.mem_sales_name
              , a.mem_lastlogin_datetime
              , a.mem_lastlogin_ip
              , a.mem_register_datetime
              , ddd.mem_username as adminname
              , ds.ds_name
              , dbo.dbo_name
              , cndd.mdd_dormant_flag
            FROM
                cb_wt_send_profile_dhn b
            LEFT JOIN
            	cb_member a
            	ON
            	b.spf_mem_id = a.mem_id
            LEFT JOIN
            	cb_member_register ccc
            	ON
            	a.mem_id = ccc.mem_id
            LEFT JOIN
            	cb_member ddd
            	ON
            	ccc.mrg_recommend_mem_id = ddd.mem_id
            LEFT JOIN
            	cb_member_dormant_dhn cndd
            	ON
            	a.mem_id = cndd.mem_id
            LEFT JOIN
                dhn_salesperson ds
                ON
                a.mem_sales_mng_id = ds.ds_id
            LEFT JOIN
                dhn_branch_office dbo
                ON
                a.mem_sale_point_id = dbo.dbo_id
            WHERE
            	b.spf_mem_id IN (
            		WITH RECURSIVE
            			cmem(mem_id, parent_id, mem_username) AS (
                    		SELECT
                    			cmr.mem_id
                    		  , cmr.mrg_recommend_mem_id AS parent_id
                    		  , cm.mem_username
            		        FROM
            		        	cb_member_register cmr
            		        JOIN
            		        	cb_member cm
            		        	ON
            		        	cmr.mrg_recommend_mem_id = cm.mem_id
            		        WHERE
            		        	cm.mem_id = '" . $mem_id . "'
            		        	AND
            		        	cmr.mem_id <> '" . $mem_id . "'
            		        UNION ALL
                    		SELECT
                    			cmr.mem_id
                    		  , cmr.mrg_recommend_mem_id AS parent_id
                    		  , cm.mem_username
            		        FROM
            		        	cb_member_register cmr
                    		JOIN
                    			cb_member cm
                    			ON
                    			cmr.mrg_recommend_mem_id = cm.mem_id
                    		JOIN
                    			cmem c
                    			ON
                    			cmr.mrg_recommend_mem_id = c.mem_id
                    	)
                    SELECT DISTINCT mem_id
                    FROM cmem
                    UNION ALL
                    SELECT " . $mem_id . " mem_id
            	) AND spf_mem_id NOT IN (
            		SELECT DISTINCT
            			mst_mem_id
            		FROM
            			cb_wt_msg_sent
            	)
                " . $where . "
            ORDER BY
                a.mem_username ASC";

        $list = $this->db->query($sql)->result();

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
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "소속");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "가입일");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "계정");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "업체명");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "담당자");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "담당자연락처");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "지점");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, "영업자");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, "휴면상태");
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, "비고");

        $cnt = 1;
        $row = 2;

        foreach($list as $a) {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $a->adminname);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $a->mem_register_datetime);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $a->mem_userid);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $a->mem_username);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $a->mem_nickname);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $a->mem_emp_phone);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $a->dbo_name);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $a->ds_name);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, ($a->mdd_dormant_flag == "1" ? "휴면" : "정상"));
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $a->mem_note);

            $row++;
            $cnt++;
        }

        // 파일로 내보낸다. 파일명은 'filename.xls' 이다.
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="result_list.xls"');
        header('Cache-Control: max-age=0');



        // Excel5 포맷(excel 2003 .XLS file)으로 저장한다.
        // 두 번째 매개변수를 'Excel2007'로 바꾸면 Excel 2007 .XLSX 포맷으로 저장한다.
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // 이용자가 다운로드하여 컴퓨터 HD에 저장하도록 강제한다.
        $objWriter->save('php://output');
    }

    public function change_note(){

        $userid = $this->input->post("mem_userid");
        $message = $this->input->post("message");
        $sql = "UPDATE cb_member SET mem_note = '" . $message . "' WHERE mem_userid = '" . $userid . "'";
        $this->db->query($sql);
        header('Content-Type: application/json');
        echo "";
        return;
    }
}
