<?php

class Salesman extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */
    //protected $models = array('Board', 'Biz_dhn');
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
        $this->load->library(array('querystring'));
    }

    public function index()
    {

        $view = array();
        $view['view'] = array();
//
//         $db = $this->load->database("dhn", TRUE);
//
        $view['param']['search_state'] = $this->input->post('search_state');
        $view['param']['position_flag'] = $this->input->post('position_flag');

        $sql = "select * from dhn_salesperson where ds_flag = 'Y'".(empty($view['param']['search_state'])?"":" and ds_task_state = '".$view['param']['search_state']."'").($this->member->item("mem_id") == '962'?" and ds_dbo_id = '6'":"");

        $view['rs'] = $this->db->query($sql)->result();

        $sql = "select * from dhn_branch_office";

        $view['br'] = $this->db->query($sql)->result();

        //echo $this->member->item("mem_id");

        $sql = "select * from dhn_position";
        $view['ps'] = $this->db->query($sql)->result();
        $sql = "select * from dhn_position WHERE pos_flag = '1'";
        $view['ps1'] = $this->db->query($sql)->result();
        $sql2 = "select * from dhn_position WHERE pos_flag = '2'";
        $view['ps2'] = $this->db->query($sql2)->result();

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'biz/salesman',
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

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));


    }

    function save() {
        $person = $this->input->post("person");
        //log_message("error", "Row : ".$person['ds_id']);

        if(empty($person['ds_id'])) {
            $sql = "insert into dhn_salesperson(ds_dbo_id, ds_name, ds_task_state, ds_position_id)
                    values
                    ( '".$person['ds_dbo_id']."'
                     ,'".$person['ds_name']."'
                     ,'".$person['ds_task_state']."'
                     ,'".$person['ds_position_id']."')";
            $this->db->query($sql);
        } else {
            $sql = "update dhn_salesperson ds
                    set ds_dbo_id = '".$person['ds_dbo_id']."'
                       ,ds_name = '".$person['ds_name']."'
                       ,ds_task_state = '".$person['ds_task_state']."'
                       ,ds_position_id = '".$person['ds_position_id']."'
                    where ds_id = '".$person['ds_id']."'";
            $this->db->query($sql);
        }

    }

    // 삭제는 dhn 통합 사이트에서만 가능
    // function del() {
    //     $person = $this->input->post("person");
    //     $db = $this->load->database("dhn", TRUE);
    //
    //     $result = array();
    //     $s = 0;
    //
    //     $sql = "select count(1) as cnt from cb_partners_v where mem_sales_mng_id = '".$person['ds_id']."'";
    //     $dbt = $this->load->database("58", TRUE);
    //     $result['58'] = $dbt->query($sql)->row()->cnt ;
    //     $s = $s + $dbt->query($sql)->row()->cnt ;
    //
    //     $sql = "select count(1) as cnt from cb_partners_v where mem_sales_mng_id = '".$person['ds_id']."'";
    //     $dbt = $this->load->database("133g", TRUE);
    //     $result['133g'] = $dbt->query($sql)->row()->cnt ;
    //     $s = $s + $dbt->query($sql)->row()->cnt ;
    //
    //     $sql = "select count(1) as cnt from cb_partners_v where mem_sales_mng_id = '".$person['ds_id']."'";
    //     $dbt = $this->load->database("133o", TRUE);
    //     $result['133o'] = $dbt->query($sql)->row()->cnt ;
    //     $s = $s + $dbt->query($sql)->row()->cnt ;
    //
    //     $sql = "select count(1) as cnt from cb_partners_v where mem_sales_mng_id = '".$person['ds_id']."'";
    //     $dbt = $this->load->database("59g", TRUE);
    //     $result['59g'] = $dbt->query($sql)->row()->cnt ;
    //     $s = $s + $dbt->query($sql)->row()->cnt ;
    //
    //     if(!empty($person['ds_id']) && $s == 0) {
    //         $sql = "delete from dhn_salesperson where ds_id = '".$person['ds_id']."'";
    //         $db->query($sql);
    //         header('Content-Type: application/json');
    //         echo '{"code": "OK"}';
    //
    //     } else {
    //         $result['code'] = "ER";
    //         header('Content-Type: application/json');
    //         echo json_encode($result,JSON_UNESCAPED_UNICODE);
    //
    //     }
    //
    // }

    function dbo_remove() {
        $brach = $this->input->post("branch");

        $sql = "select count(1) as cnt from dhn_salesperson where ds_dbo_id = '".$brach['dbo_id']."'";
        $s = $this->$db->query($sql)->row()->cnt;

        if(!empty($brach['dbo_id']) && $s == 0) {
            $sql = "delete from dhn_branch_office where dbo_id = '".$brach['dbo_id']."'";
            $this->db->query($sql);

            header('Content-Type: application/json');
            echo '{"code": "OK"}';

        } else {
            $result['code'] = "ER";
            header('Content-Type: application/json');
            echo '{"code": "ER"}';
        }
    }

    function dbo_save() {
        $branch = $this->input->post("branch");

        //log_message("error", "Row : ".$person['ds_id']);

        if(empty($branch['dbo_id'])) {
            $sql = "insert into dhn_branch_office(dbo_name)
                    values
                    ( '".$branch['dbo_name']."' )";
            $this->db->query($sql);
        } else {
            $sql = "update dhn_branch_office ds
                    set dbo_name = '".$branch['dbo_name']."'
                    where dbo_id = '".$branch['dbo_id']."'";
            $this->db->query($sql);
        }

    }

    function pos_remove() {
        $pos = $this->input->post("pos");

        $sql = "select count(1) as cnt from dhn_salesperson where ds_position_id = '".$pos['pos_id']."'";
        //log_message("ERROR", $sql);
        $s = $this->db->query($sql)->row()->cnt;

        if(!empty($pos['pos_id']) && $s == 0) {
            $sql = "delete from dhn_position where pos_id = '".$pos['pos_id']."'";
            $this->db->query($sql);

            header('Content-Type: application/json');
            echo '{"code": "OK"}';

        } else {
            $result['code'] = "ER";
            header('Content-Type: application/json');
            echo '{"code": "ER"}';
        }
    }

    function pos_save() {
        $pos = $this->input->post("pos");

        //log_message("error", "Row : ".$person['ds_id']);

        if(empty($pos['pos_id'])) {
            $sql = "insert into dhn_position(pos_name, pos_flag)
                    values
                    ( '".$pos['pos_name']."', '".$pos['pos_flag']."' )";
            $this->db->query($sql);
            //$db->query($sql);
        } else {
            $sql = "update dhn_position ds
                    set pos_name = '".$pos['pos_name']."' AND pos_flag = '2'
                    where pos_id = '".$pos['pos_id']."'";
            $this->db->query($sql);
            //$db->query($sql);
        }

    }

    function position() {
        $point_val = $this->input->post("point_val");

        if($point_val == '6'){
            $sql = "select pos_name, pos_id, pos_flag from dhn_position where pos_flag = '2'";
        }else{
            $sql = "select pos_name, pos_id, pos_flag from dhn_position where pos_flag = '1'";
        }

        $result = $this->db->query($sql)->result();

        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

}
?>
