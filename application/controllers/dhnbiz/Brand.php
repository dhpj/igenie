<?php
class Brand extends CB_Controller {
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
        $this->load->library(array('querystring', 'funn', 'dhnkakao', 'KTrcs', 'funn'));
    }

    public function index()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        //$this->sync_template_status();

        $view['param']['page'] = ($this->input->get('page')) ? $this->input->get('page') : 1;
        // $view['param']['pf_ynm'] = $this->input->post('pf_ynm');							//한국산업인력공단
        // $view['param']['inspect_status'] = $this->input->post('inspect_status');	//REG
        // $view['param']['tmpl_status'] = $this->input->post('tmpl_status');			//R
        // $view['param']['comment_status'] = $this->input->post('comment_status');	//INQ
        $view['sfield'] = $this->input->get('sfield');			//pf_ynm
        $view['skeyword'] = $this->input->get('skeyword');					//한국
        $param = array();	//$this->member->item('mem_id'));
        $where = " brd_use='Y' ";	//and tpl_mem_id=? ";
        if(!empty($view['sfield'])&&!empty($view['skeyword'])){
            if($view['sfield']=="1"){
                $where .= " AND brd_company like '%".$view['skeyword']."%'";
            }else if($view['sfield']=="2"){
                $where .= " AND brd_brand like '%".$view['skeyword']."%'";
            }
        }
        // if($view['param']['pf_ynm']!="ALL" && $view['param']['pf_ynm']) { $where.=" and tpl_profile_key=? "; array_push($param, $view['param']['pf_ynm']); }
        // if($view['param']['inspect_status']!="ALL" && $view['param']['inspect_status']) { $where.=" and tpl_inspect_status=? "; array_push($param, $view['param']['inspect_status']); }
        // if($view['param']['tmpl_status']!="ALL" && $view['param']['tmpl_status']) { $where.=" and tpl_status=? "; array_push($param, $view['param']['tmpl_status']); }
        // if($view['param']['comment_status']!="ALL" && $view['param']['comment_status']) { $where.=" and tpl_comment_status=? "; array_push($param, $view['param']['comment_status']); }
        // if($view['param']['tmpl_search']!="ALL" && $view['param']['tmpl_search'] && $view['param']['searchStr']) { $where.=" and tpl_".$view['param']['tmpl_search']." like ? "; array_push($param, '%'.$view['param']['searchStr'].'%'); }
        $sql = "
        	select count(1) as cnt
        	from cb_wt_brand a join cb_member_register b on a.brd_mem_id = b.mem_id join cb_member c on b.mrg_recommend_mem_id = c.mem_id
        	where ".$where;
         $view['total_rows'] = $this->db->query($sql)->row()->cnt;
        // $view['total_rows'] = $this->Biz_dhn_model->get_template_count($this->member->item('mem_id'), $where, $param);	//get_table_count("cb_wt_template_dhn", $where, $param);
        $sql = "
        	select a.*, c.mem_username as adminname
        	from cb_wt_brand a join cb_member_register b on a.brd_mem_id = b.mem_id join cb_member c on b.mrg_recommend_mem_id = c.mem_id
        	where ".$where." order by brd_id desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql)->result();
        // $view['profile'] = $this->Biz_dhn_model->get_partner_profile();

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'search_template';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();


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
            'path' => 'biz/dhnbrand',
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


    public function tel()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        //$this->sync_template_status();

        $view['param']['page'] = ($this->input->get('page')) ? $this->input->get('page') : 1;
        // $view['param']['pf_ynm'] = $this->input->post('pf_ynm');							//한국산업인력공단
        // $view['param']['inspect_status'] = $this->input->post('inspect_status');	//REG
        // $view['param']['tmpl_status'] = $this->input->post('tmpl_status');			//R
        // $view['param']['comment_status'] = $this->input->post('comment_status');	//INQ
        $view['sfield'] = $this->input->get('sfield');			//pf_ynm
        $view['skeyword'] = $this->input->get('skeyword');					//한국
        $view['comp'] = $this->input->get('comp');


        $param = array();	//$this->member->item('mem_id'));
        // $where = " brt_use='Y' ";	//and tpl_mem_id=? ";
        $where = " 1=1 ";
        if(!empty($view['sfield'])&&!empty($view['skeyword'])){
            if($view['sfield']=="1"){
                $where .= " AND brt_tel_no like '%".$view['skeyword']."%'";
            }else if($view['sfield']=="2"){
                $where .= " AND brt_company like '%".$view['skeyword']."%'";
            }else if($view['sfield']=="3"){
                $where .= " AND brt_brand like '%".$view['skeyword']."%'";
            }
        }

        if(!empty($view['comp'])&&$view['comp']!="ALL"){
            $sql = "
            	select brd_brand
            	from cb_wt_brand where brd_id = '".$view['comp']."'
            	";
            $view['brandid'] = $this->db->query($sql)->row()->brd_brand;

            $where .= " AND brt_brand = '".$view['brandid']."' ";
        }
        // if($view['param']['pf_ynm']!="ALL" && $view['param']['pf_ynm']) { $where.=" and tpl_profile_key=? "; array_push($param, $view['param']['pf_ynm']); }
        // if($view['param']['inspect_status']!="ALL" && $view['param']['inspect_status']) { $where.=" and tpl_inspect_status=? "; array_push($param, $view['param']['inspect_status']); }
        // if($view['param']['tmpl_status']!="ALL" && $view['param']['tmpl_status']) { $where.=" and tpl_status=? "; array_push($param, $view['param']['tmpl_status']); }
        // if($view['param']['comment_status']!="ALL" && $view['param']['comment_status']) { $where.=" and tpl_comment_status=? "; array_push($param, $view['param']['comment_status']); }
        // if($view['param']['tmpl_search']!="ALL" && $view['param']['tmpl_search'] && $view['param']['searchStr']) { $where.=" and tpl_".$view['param']['tmpl_search']." like ? "; array_push($param, '%'.$view['param']['searchStr'].'%'); }
        $sql = "
        	select count(1) as cnt
        	from cb_wt_brand_tel a join cb_member_register b on a.brt_mem_id = b.mem_id join cb_member c on b.mrg_recommend_mem_id = c.mem_id
        	where ".$where;
         $view['total_rows'] = $this->db->query($sql)->row()->cnt;
        // $view['total_rows'] = $this->Biz_dhn_model->get_template_count($this->member->item('mem_id'), $where, $param);	//get_table_count("cb_wt_template_dhn", $where, $param);
        $sql = "
        	select a.*, c.mem_username as adminname
        	from cb_wt_brand_tel a join cb_member_register b on a.brt_mem_id = b.mem_id join cb_member c on b.mrg_recommend_mem_id = c.mem_id
        	where ".$where." order by brt_id desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql)->result();
        // $view['profile'] = $this->Biz_dhn_model->get_partner_profile();

        $sql = "
        	select brd_company, brd_id, brd_brand
        	from cb_wt_brand
        	";
        $view['company'] = $this->db->query($sql)->result();
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'search_template';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();


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
            'path' => 'biz/dhnbrand',
            'layout' => 'layout',
            'skin' => 'tel',
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


    public function template($tmp_flag)
    {

        if(empty($tmp_flag)){
            $tmp_flag = "brand";
        }


        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;
        $view['tmp_flag'] = $tmp_flag;
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        //$this->sync_template_status();

        $view['param']['page'] = ($this->input->get('page')) ? $this->input->get('page') : 1;
        // $view['param']['pf_ynm'] = $this->input->post('pf_ynm');							//한국산업인력공단
        // $view['param']['inspect_status'] = $this->input->post('inspect_status');	//REG
        // $view['param']['tmpl_status'] = $this->input->post('tmpl_status');			//R
        // $view['param']['comment_status'] = $this->input->post('comment_status');	//INQ
        $view['sfield'] = $this->input->get('sfield');			//pf_ynm
        $view['skeyword'] = $this->input->get('skeyword');					//한국
        $view['comp'] = $this->input->get('comp');


        $param = array();	//$this->member->item('mem_id'));
        if($tmp_flag=="brand"){
            $where = " tpl_brand <> 'common' ";
        }else{
            $where = " tpl_brand = 'common' ";
        }
        	//and tpl_mem_id=? ";
        // $where = " tpl_use='Y' ";	//and tpl_mem_id=? ";
        if(!empty($view['sfield'])&&!empty($view['skeyword'])){
            if($view['sfield']=="1"){
                $where .= " AND tpl_messagebaseId like '%".$view['skeyword']."%'";
            }else if($view['sfield']=="2"){
                $where .= " AND tpl_company like '%".$view['skeyword']."%'";
            }else if($view['sfield']=="2"){
                $where .= " AND tpl_brand like '%".$view['skeyword']."%'";
            }
        }

        if(!empty($view['comp'])&&$view['comp']!="ALL"){
            $sql = "
            	select brd_brand
            	from cb_wt_brand where brd_id = '".$view['comp']."'
            	";
            $view['brandid'] = $this->db->query($sql)->row()->brd_brand;

            $where .= " AND tpl_brand = '".$view['brandid']."' ";
        }
        // if($view['param']['pf_ynm']!="ALL" && $view['param']['pf_ynm']) { $where.=" and tpl_profile_key=? "; array_push($param, $view['param']['pf_ynm']); }
        // if($view['param']['inspect_status']!="ALL" && $view['param']['inspect_status']) { $where.=" and tpl_inspect_status=? "; array_push($param, $view['param']['inspect_status']); }
        // if($view['param']['tmpl_status']!="ALL" && $view['param']['tmpl_status']) { $where.=" and tpl_status=? "; array_push($param, $view['param']['tmpl_status']); }
        // if($view['param']['comment_status']!="ALL" && $view['param']['comment_status']) { $where.=" and tpl_comment_status=? "; array_push($param, $view['param']['comment_status']); }
        // if($view['param']['tmpl_search']!="ALL" && $view['param']['tmpl_search'] && $view['param']['searchStr']) { $where.=" and tpl_".$view['param']['tmpl_search']." like ? "; array_push($param, '%'.$view['param']['searchStr'].'%'); }
        $sql = "
        	select count(1) as cnt
        	from cb_wt_brand_template
        	where ".$where;
         $view['total_rows'] = $this->db->query($sql)->row()->cnt;
        // $view['total_rows'] = $this->Biz_dhn_model->get_template_count($this->member->item('mem_id'), $where, $param);	//get_table_count("cb_wt_template_dhn", $where, $param);
        $sql = "
        	select *
        	from cb_wt_brand_template
        	where ".$where." order by tpl_id desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->db->query($sql)->result();
        // $view['profile'] = $this->Biz_dhn_model->get_partner_profile();

        $sql = "
        	select brd_company, brd_id, brd_brand
        	from cb_wt_brand
        	";
        $view['company'] = $this->db->query($sql)->result();
        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'search_template';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();


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
            'path' => 'biz/dhnbrand',
            'layout' => 'layout',
            'skin' => 'template',
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

    public function get_brand_list()
    {

        // $tcode = $this->input->post('tcode');
        $result = array();
        $result["msg"] = $this->ktrcs->get_brand();
        if($result["msg"]=="가져올 새 브랜드가 없습니다."){
            $result["code"] = '0';
        }else{
            $result["code"] = '1';
        }
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        //echo '{"code": '.$rtn.' }';
        echo $json;
    }

    public function set_chatbot(){
        $brand = $this->input->post('brand');
        $bid = $this->input->post('bid');
        $result = array();

        if(!empty($brand)&&!empty($bid)){
            $sql = "
            	select brt_tel_no
            	from cb_wt_brand_tel
            	where brt_id = '".$bid."' ";
             $tel_no = $this->db->query($sql)->row()->brt_tel_no;

             $sql = "
             	select count(1) as cnt
             	from cb_wt_brand_tel
             	where brt_brand = '".$brand."' ";
              $brand_cnt = $this->db->query($sql)->row()->cnt;
             $sql = "UPDATE cb_wt_brand
                    SET brd_sms_callback='".$tel_no."'
                    WHERE brd_brand='".$brand."'";
             $this->db->query($sql);

             $sql = "UPDATE cb_wt_brand_tel
                    SET brt_use='Y'
                    WHERE brt_id ='".$bid."'";
             $this->db->query($sql);

             if($brand_cnt>1){
                 $sql = "UPDATE cb_wt_brand_tel
                        SET brt_use='N'
                        WHERE brt_brand='".$brand."' and brt_id <> '".$bid."'";
                 $this->db->query($sql);
             }

             $result["code"] = '1';
             $result["msg"]="정상적으로 설정되었습니다.";
        }else{
            $result["code"] = '0';
            $result["msg"]="설정하는데 문제가 있습니다.";
        }
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        //echo '{"code": '.$rtn.' }';
        echo $json;
    }

    public function chatbot_list()
    {

        $brandid = $this->input->post('brandid');

        $result = array();

        $where = " brt_brand = '".$brandid."' ";

        $sql = "
        	select count(1) as cnt
        	from cb_wt_brand_tel
        	where ".$where;
         $result['total'] = $this->db->query($sql)->row()->cnt;
        // $view['total_rows'] = $this->Biz_dhn_model->get_template_count($this->member->item('mem_id'), $where, $param);	//get_table_count("cb_wt_template_dhn", $where, $param);
        $result["result"]  = array();

        $sql = "
        	select *
        	from cb_wt_brand_tel
        	where ".$where." order by brt_id desc ";
        $result["result"] = $this->db->query($sql)->result();

        if($result['total']==0){
            $result['code'] = '0';
            $result['msg'] = '등록된 발신번호가 없습니다';
        }else{
            $result['code'] = '1';
        }
        // $result["result"] = $this->ktrcs->brand_chatbot_list($brandid, 0, 100);
        // if($result["msg"]=="업데이트할 브랜드가 없습니다."){
        //     $result["code"] = '0';
        // }else{
        //     $result["code"] = '1';
        // }
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        //echo '{"code": '.$rtn.' }';
        echo $json;
    }

    public function get_chatbot()
    {

        $brandid = $this->input->post('brandid');

        $result = array();

        $result["msg"] = $this->ktrcs->get_chatbot($brandid);
        if($result["msg"]=="가져올 새 발신번호가 없습니다."){
            $result["code"] = '0';
        }else{
            $result["code"] = '1';
        }
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        //echo '{"code": '.$rtn.' }';
        echo $json;
    }

    public function get_template()
    {

        $brandid = $this->input->post('brandid');

        $result = array();

        $result["msg"] = $this->ktrcs->get_template($brandid);
        if($result["msg"]=="가져올 새 템플릿이 없습니다."){
            $result["code"] = '0';
        }else{
            $result["code"] = '1';
        }
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        //echo '{"code": '.$rtn.' }';
        echo $json;
    }

    //템플릿 > 광고성 알림톡 여부 업데이트
    public function tmpl_use_yn(){
        $tpl_id = $this->input->post('tpl_id'); //템플릿번호
        $tpl_use = $this->input->post('tpl_use'); //템플릿 사용 여부
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpl_id : ". $tpl_id .", tpl_adv_yn : ". $tpl_adv_yn);
        $where = array();
        $where["tpl_id"] = $tpl_id; //템플릿번호
        $data = array();
        $data["tpl_use"] = $tpl_use; //광고성 알림톡 여부
        $rtn = $this->db->update("cb_wt_brand_template", $data, $where);
        header('Content-Type: application/json');
        echo '{"code":"200", "message":"success"}';
    }

    //템플릿 > 광고성 알림톡 메인 여부 업데이트
    public function tmpl_main_yn(){
        $tpl_id = $this->input->post('tpl_id'); //템플릿번호
        $tpl_mem_id = $this->input->post('tpl_mem_id'); //mem_id
        $tpl_main_yn = $this->input->post('tpl_main_yn'); //템플릿 사용 여부
        //log_message("ERROR", $_SERVER['REQUEST_URI'] ." > tpl_id : ". $tpl_id .", tpl_adv_main_yn : ". $tpl_adv_main_yn);
        //기존 메인 사용 => 사안안함 처리
        if($tpl_main_yn=='Y'){
            $where = array();
            $where["tpl_use"] = "Y";
            $where["tpl_main_yn"] = "Y"; //메인 여부
            $where["tpl_mem_id"] = $tpl_mem_id;
            $data = array();
            $data["tpl_main_yn"] = "N"; //메인 여부
            $rtn = $this->db->update("cb_wt_template_dhn", $data, $where);
        }

        //신규 메인 사용
        $where = array();
        $where["tpl_use"] = "Y";
        $where["tpl_id"] = $tpl_id; //템플릿번호
        $where["tpl_mem_id"] = $tpl_mem_id;
        $data = array();
        $data["tpl_main_yn"] = $tpl_main_yn; //광메인 여부
        $rtn = $this->db->update("cb_wt_brand_template", $data, $where);
        header('Content-Type: application/json');
        echo '{"code":"200", "message":"success"}';
    }

    public function lists()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['perpage'] = 20;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        //$this->sync_template_status();

        $view['param']['page'] = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $view['param']['pf_ynm'] = $this->input->post('pf_ynm');							//한국산업인력공단
        $view['param']['inspect_status'] = $this->input->post('inspect_status');	//REG
        $view['param']['tmpl_status'] = $this->input->post('tmpl_status');			//R
        $view['param']['comment_status'] = $this->input->post('comment_status');	//INQ
        $view['param']['tmpl_search'] = $this->input->post('tmpl_search');			//pf_ynm
        $view['param']['searchStr'] = $this->input->post('searchStr');					//한국
        $param = array();	//$this->member->item('mem_id'));
        $where = " tpl_use='Y' ";	//and tpl_mem_id=? ";
        if($view['param']['pf_ynm']!="ALL" && $view['param']['pf_ynm']) { $where.=" and tpl_profile_key=? "; array_push($param, $view['param']['pf_ynm']); }
        if($view['param']['inspect_status']!="ALL" && $view['param']['inspect_status']) { $where.=" and tpl_inspect_status=? "; array_push($param, $view['param']['inspect_status']); }
        if($view['param']['tmpl_status']!="ALL" && $view['param']['tmpl_status']) { $where.=" and tpl_status=? "; array_push($param, $view['param']['tmpl_status']); }
        if($view['param']['comment_status']!="ALL" && $view['param']['comment_status']) { $where.=" and tpl_comment_status=? "; array_push($param, $view['param']['comment_status']); }
        if($view['param']['tmpl_search']!="ALL" && $view['param']['tmpl_search'] && $view['param']['searchStr']) { $where.=" and tpl_".$view['param']['tmpl_search']." like ? "; array_push($param, '%'.$view['param']['searchStr'].'%'); }

        $view['total_rows'] = $this->Biz_dhn_model->get_template_count($this->member->item('mem_id'), $where, $param);	//get_table_count("cb_wt_template_dhn", $where, $param);

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'search_template';
        $page_cfg['base_url'] = '';
        $page_cfg['total_rows'] = $view['total_rows'];
        $page_cfg['per_page'] = $view['perpage'];
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($view['param']['page']);
        $view['page_html'] = $this->pagination->create_links();

        //$sql = "
        //	select a.*, b.spf_friend, b.spf_key_type
        //	from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y'
        //	where ".$where." order by tpl_datetime desc limit ".(($view['param']['page'] - 1) * $view['perpage']).", ".$view['perpage'];
        $view['list'] = $this->Biz_dhn_model->get_template_list($this->member->item('mem_id'), ($view['param']['page']-1), $view['perpage'], $where, $param);	//$this->db->query($sql, $param)->result();
        $view['profile'] = $this->Biz_dhn_model->get_partner_profile();
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
            'path' => 'biz/dhnbrand',
            'layout' => 'layout',
            'skin' => 'lists',
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

    //템플릿 > 상세정보
    public function view()
    {
        $key = (count($_GET) > 0) ? array_keys($_GET)[0] : "";
        if(empty($key)) {
            $key = $this->input->post("tmpl_id");
        }
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();
        $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback, b.spf_appr_id from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where tpl_id=? limit 1", array($key))->row();
        if(!$view['rs']) { show_404(); exit; }
        $this->load->library('dhnkakao'); //템플릿 상태조회
        //log_message('error', $_SERVER['REQUEST_URI'] .' > key : '. $key .', rs tpl_profile_key : '. $view['rs']->tpl_profile_key .', tpl_code : '. $view['rs']->tpl_code);
        $sendtype = ($view['rs']->tpl_share_flag == 'P')?"G":"S";
        $view['tpl_status'] = $this->dhnkakao->template($view['rs']->tpl_profile_key, $view['rs']->tpl_code, $sendtype);
        //log_message('error', $_SERVER['REQUEST_URI'] .' > view[tpl_status]->code : '. $view['tpl_status']->code);

        if($view['tpl_status']->code=="200") {
            //log_message('error', 'rs status : '.$view['rs']->tpl_inspect_status.' / '.$view['tpl_status']->data->inspectionStatus);
            if(($view['rs']->tpl_inspect_status!=$view['tpl_status']->data->inspectionStatus) || $view['tpl_status']->data->status!=$view['rs']->tpl_status) {
                if($view['rs']->tpl_inspect_status!=$view['tpl_status']->data->status) {
                    $sql = "update cb_wt_template_dhn set tpl_inspect_status='".$view['tpl_status']->data->inspectionStatus."', tpl_appr_id='admin', tpl_appr_datetime='".cdate('Y-m-d H:i:s')."' where tpl_id=?";
                } else {
                    $sql = "update cb_wt_template_dhn set tpl_status='".$view['tpl_status']->data->status."' where tpl_id=?";
                }
                $this->db->query($sql, array($key));
            }
            $view['rs'] = $this->db->query("select a.*, b.spf_friend, b.spf_key_type, b.spf_sms_callback, b.spf_appr_id from cb_wt_template_dhn a inner join cb_wt_send_profile_dhn b on a.tpl_profile_key=b.spf_key and b.spf_use='Y' where tpl_id=? limit 1", array($key))->row();
        }

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
            'path' => 'biz/dhnbrand',
            'layout' => 'layout',
            'skin' => 'view',
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

}
?>
