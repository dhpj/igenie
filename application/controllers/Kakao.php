<?php
class Kakao extends CB_Controller {
    /**
     * 모델을 로딩합니다
     */

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');
    private $parameters = array(
        'date' => array(
            '날짜'
          , '${date1}'
          , '${date2}'
          , '${date3}'
          , '${date4}'
        )
      , 'site_name' => array(
            '사이트 이름'
          , '${site_name1}'
        )
      , 'brand_name' => array(
            '브랜드 이름'
          , '${brand_name1}'
        )
      , 'user_name' => array(
            '고객 이름'
          , '${user_name1}'
        )
      , 'user_id' => array(
            '고객 ID'
          , '${user_id1}'
        )
      , 'user_rating' => array(
            '고객 등급'
          , '${user_rating1}'
        )
      , 'available_point' => array(
            '적립금'
          , '${available_point1}'
        )
      , 'available_coupon' => array(
            '쿠폰 개수'
          , '${available_coupon1}'
      )
      , 'product_id' => array(
            '상품 ID'
          , '${product_id1}'
          , '${product_id2}'
          , '${product_id3}'
          , '${product_id4}'
          , '${product_id5}'
          , '${product_id6}'
          , '${product_id7}'
      )
      , 'product_name' => array(
            '상품명'
          , '${product_name1}'
          , '${product_name2}'
          , '${product_name3}'
          , '${product_name4}'
          , '${product_name5}'
          , '${product_name6}'
          , '${product_name7}'
      )
      , 'price' => array(
            '상품 가격 - 정가'
          , '${price1}'
          , '${price2}'
          , '${price3}'
          , '${price4}'
          , '${price5}'
          , '${price6}'
          , '${price7}'
      )
      , 'sal_price' => array(
            '상품 가격 - 세일가'
          , '${sale_price1}'
          , '${sale_price2}'
          , '${sale_price3}'
          , '${sale_price4}'
          , '${sale_price5}'
          , '${sale_price6}'
          , '${sale_price7}'
      )
      , 'discount_amount' => array(
            '할인금액'
          , '${discount_amount1}'
          , '${discount_amount2}'
          , '${discount_amount3}'
          , '${discount_amount4}'
          , '${discount_amount5}'
          , '${discount_amount6}'
          , '${discount_amount7}'
      )
      , 'discount_percent' => array(
            '할인율'
          , '${discount_percent1}'
          , '${discount_percent2}'
          , '${discount_percent3}'
          , '${discount_percent4}'
          , '${discount_percent5}'
          , '${discount_percent6}'
          , '${discount_percent7}'
      )
      // , 'image_url' => array(
      //       '${image_url1}'
      //     , '${image_url2}'
      //     , '${image_url3}'
      //     , '${image_url4}'
      //     , '${image_url5}'
      //     , '${image_url6}'
      //     , '${image_url7}'
      // )
      // , 'video_url' => array(
      //       '${video_url}'
      // )
      // , 'mobile_url' => array(
      //       '${mobile_url1}'
      //     , '${mobile_url2}'
      //     , '${mobile_url3}'
      // )
      // , 'pc_url' => array(
      //       '${pc_url1}'
      //     , '${pc_url2}'
      //     , '${pc_url3}'
      // )
    );

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring', 'kakaolib'));
    }

    public function creative_lists()
	{
		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'kakao',
			'layout' => 'layout',
			'skin' => 'creative_lists',
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

    public function creative_write()
	{
        $view['date1'] = $this->parameters['date1'];
        $view['adglist'] = $this->get_ad_groups_in_local();
        $view['parameters'] = $this->parameters;
        $view['id'] = $this->input->get('id');
        if (!empty($view['id'])){
            $token = $this->input->get('token');
            $adid = $this->input->get('adid');
            $view['data'] = $this->kakaolib->get_creative($token, $adid, $view['id']);
        }

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'kakao',
			'layout' => 'layout',
			'skin' => 'creative_write',
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

    public function creative_write_wm()
	{

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'kakao',
			'layout' => 'layout',
			'skin' => 'creative_write_wm',
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

    public function creative_write_wl()
	{

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'kakao',
			'layout' => 'layout',
			'skin' => 'creative_write_wl',
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

    public function campaign_write()
	{

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'kakao',
			'layout' => 'layout',
			'skin' => 'campaign_write',
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

    public function campaign_lists($flag)
	{
        $page = !empty($this->input->post('page')) ? $this->input->post('page') : 1;
        $perpage = 10;
        $view['flag'] = $flag;
        $view['search_sel'] = !empty($this->input->get('search_sel')) ? $this->input->get('search_sel') : '1';
        $view['search_txt'] = !empty($this->input->get('search_txt')) ? $this->input->get('search_txt') : '';

        if ($flag == 'c'){
            $view['tr'] = $this->db
                ->select('count(*) as cnt')
                ->from('cb_personal_campaign')
                ->where(array(
                    'pc_mem_id' => $this->member->item('mem_id')
                  , 'pc_useyn' => 'Y'
                ))
                ->get()->row()->cnt;
            $this->db
                ->select('
                    pc_id as id
                  , pc_mem_id as mem_id
                  , pc_c_id as kakao_id
                  , pc_name as name
                  , pc_status as status
                  , pc_create_datetime as ct
                  , pc_ad_id as ad_id
                  , pc_ch_id as ch_id
                  , pc_ad_id as ad_id
                  , pc_ch_name as name2
                ')
                ->from('cb_personal_campaign')
                ->where(array(
                    'pc_mem_id' => $this->member->item('mem_id')
                  , 'pc_useyn' => 'Y'
                ));

            if (!empty($view['search_txt'])){
                $this->db
                    ->like('pc_name', $view['search_txt']);
            }

            $view['list'] = $this->db->order_by('pc_create_datetime', 'desc')
                ->limit($perpage, ($page - 1) * $perpage)
                ->get()->result();
        } else if ($flag == 'a'){
            $view['tr'] = $this->db
                ->select('count(*) as cnt')
                ->from('cb_personal_adgroup')
                ->where(array(
                    'pa_mem_id' => $this->member->item('mem_id')
                  , 'pa_useyn' => 'Y'
                ))
                ->get()->row()->cnt;
            $this->db
                ->select('
                    a.pa_id as id
                  , a.pa_mem_id as mem_id
                  , a.pa_a_id as kakao_id
                  , a.pa_name as name
                  , a.pa_status as status
                  , a.pa_create_datetime as ct
                  , b.pc_ad_id as ad_id
                  , b.pc_ch_id as ch_id
                  , b.pc_name as name2
                  , b.pc_c_id as c_id
                  , b.pc_ad_id as ad_id
                ')
                ->from('cb_personal_adgroup a')
                ->join('cb_personal_campaign b', 'a.pa_pc_id = b.pc_id', 'left')
                ->where(array(
                    'a.pa_mem_id' => $this->member->item('mem_id')
                  , 'a.pa_useyn' => 'Y'
            ));

            if (!empty($view['search_txt'])){
                if ($view['search_sel'] == '1'){
                    $this->db
                        ->like('a.pa_name', $view['search_txt']);
                } else if ($view['search_sel'] == '2'){
                    $this->db
                        ->like('b.pc_name', $view['search_txt']);
                }
            }

            $view['list'] = $this->db
                ->order_by('pa_create_datetime', 'desc')
                ->limit($perpage, ($page - 1) * $perpage)
                ->get()->result();
        } else if ($flag == 'cr'){
            $view['tr'] = $this->db
                ->select('count(*) as cnt')
                ->from('cb_personal_creative')
                ->where(array(
                    'pcr_mem_id' => $this->member->item('mem_id')
                  , 'pcr_useyn' => 'Y'
                ))
                ->get()->row()->cnt;
            $this->db
                ->select('
                    a.pcr_id as id
                  , a.pcr_mem_id as mem_id
                  , a.pcr_cr_id as kakao_id
                  , a.pcr_name as name
                  , a.pcr_status as status
                  , a.pcr_create_datetime as ct
                  , a.pcr_type
                  , b.pa_name as name2
                  , c.pc_ad_id as ad_id
                ')
                ->from('cb_personal_creative a')
                ->join('cb_personal_adgroup b', 'a.pcr_pa_id = b.pa_id', 'left')
                ->join('cb_personal_campaign c', 'b.pa_pc_id = c.pc_id', 'left')
                ->where(array(
                    'a.pcr_mem_id' => $this->member->item('mem_id')
                  , 'a.pcr_useyn' => 'Y'
            ));
            if (!empty($view['search_txt'])){
                if ($view['search_sel'] == '1'){
                    $this->db
                        ->like('a.pcr_name', $view['search_txt']);
                } else if ($view['search_sel'] == '2'){
                    $this->db
                        ->like('b.pa_name', $view['search_txt']);
                }
            }

            $view['list'] = $this->db
                ->order_by('a.pcr_create_datetime', 'desc')
                ->limit($perpage, ($page - 1) * $perpage)
                ->get()->result();
        }

        $this->load->library('pagination');
        $page_cfg['link_mode'] = 'open_page';
        $page_cfg['base_url'] = $page;
        $page_cfg['total_rows'] = $tr;
        $page_cfg['per_page'] = $perpage;
        $this->pagination->initialize($page_cfg);
        $this->pagination->cur_page = intval($page);
        $result['page'] = str_replace('<ul class="pagination">', '<ul class="pagination" style="display:none;">', $this->pagination->create_links());

		$page_title = $this->cbconfig->item('site_meta_title_main');
		$meta_description = $this->cbconfig->item('site_meta_description_main');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
		$meta_author = $this->cbconfig->item('site_meta_author_main');
		$page_name = $this->cbconfig->item('site_page_name_main');

		$layoutconfig = array(
			'path' => 'kakao',
			'layout' => 'layout',
			'skin' => 'campaign_lists',
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

    public function creative_save(){
        $result['code'] = 0;
        $token = $this->input->post('token');
        $mem_id = $this->member->item('mem_id');
        $adid = $this->input->post('adid');
        $aid = $this->input->post('aid');
        $para = $this->input->post('para');

        if(!empty($token)){
            $res = $this->kakaolib->set_personal_msg($token, $adid, $aid, $para, 'post');
            if(!empty($res->id)){
                $result['code'] = 1;
                $this->db
                    ->set('p_mem_id', $mem_id)
                    ->set('p_adid', $adid)
                    ->set('p_aid', $aid)
                    ->set('p_creative_id', $res->id)
                    ->set('p_para', json_encode($para, JSON_UNESCAPED_UNICODE))
                    ->insert('cb_wt_personal');
            } else {
                $result['res_code'] = $res->code;
                $result['msg'] = $res->msg;
                $result['detailCode'] = $res->extras->detailCode;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //소재 이미지 불러오기
    public function get_creative_img(){
        $result['text'] = '';
        $page = $this->input->post('page');
        $file_name = $this->input->post('file_name');
        $perpage = 10;

        $tr = $this->db
            ->select('count(*) as cnt')
            ->from('cb_creative_img')
            ->where(array(
                'ci_mem_id' => $this->member->item('mem_id')
              , 'ci_useyn' => 'Y'
            ))
            ->like('ci_name', $file_name)
            ->get()->row()->cnt;

        $list = $this->db
            ->select('*')
            ->from('cb_creative_img')
            ->where(array(
                'ci_mem_id' => $this->member->item('mem_id')
              , 'ci_useyn' => 'Y'
            ))
            ->like('ci_name', $file_name)
            ->order_by('ci_id DESC')
            ->limit($perpage, ($page - 1) * $perpage)
            ->get()->result();

        if (!empty($list)){
            $html = '';
            foreach($list as $key => $a){
                $html .= '<li class="m1_img_list">';
                $html .= '    <a href="#"><img src="' . $a->ci_path . '" alt=""></a>';
                $html .= '    <span>' . $a->ci_width . 'x' . $a->ci_height . '</span>';
                $html .= '    <span>' . $a->ci_name . '.' . $a->ci_ext . '</span>';
                $html .= '    <input type="hidden" data-path="' . $a->ci_path . '" data-name="' . $a->ci_name . '">';
                $html .= '</li>';
            }

            $this->load->library('pagination');
            $page_cfg['link_mode'] = 'open_page2';
            $page_cfg['base_url'] = $page;
            $page_cfg['total_rows'] = $tr;
            $page_cfg['per_page'] = $perpage;
            $this->pagination->initialize($page_cfg);
            $this->pagination->cur_page = intval($page);
            $result['page'] = str_replace('<ul class="pagination">', '<ul class="pagination" style="display:none;">', $this->pagination->create_links());

            $result['text'] = $html;
        }

        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //소재 이미지 저장
    public function set_creative_img(){
        // $files = $this->input->post('files');

        $mem_id = $this->member->item('mem_id');
        foreach($_FILES['imgfile']['name'] as $key => $a){
            $files = array();
            $files['imgfile']['name'] = $_FILES['imgfile']['name'][$key];
            $files['imgfile']['type'] = $_FILES['imgfile']['type'][$key];
            $files['imgfile']['tmp_name'] = $_FILES['imgfile']['tmp_name'][$key];
            $files['imgfile']['error'] = $_FILES['imgfile']['error'][$key];
            $files['imgfile']['size'] = $_FILES['imgfile']['size'][$key];
            $path = $this->image_upload($files, $mem_id, 'creative');
            $size = getimagesize('/var/www/igenie' . $path[0]);
            $this->db
                ->set('ci_mem_id', $mem_id)
                ->set('ci_name', explode('.', $_FILES['imgfile']['name'][$key])[0])
                ->set('ci_path', $path[0])
                ->set('ci_ext', explode('/', $_FILES['imgfile']['tmp_name'][$key])[1])
                ->set('ci_volume', $_FILES['imgfile']['size'][$key])
                ->set('ci_width', $size[0])
                ->set('ci_height', $size[1])
                ->set('ci_useyn', 'Y')
                ->insert('cb_creative_img');
        }

        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function get_kakaotv_ch_movs_modal(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $chid = $this->input->post('chid');
        $page = $this->input->post('page');
        $file_name = $this->input->post('file_name');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->get_kakaotv_ch_movs($token, $chid);
            // log_message('error', json_encode($result['data'], JSON_UNESCAPED_UNICODE));
            if(!empty($result['data'])){
                $result['code'] = 's';
                $html = '';
                if (count($result['data']->list) > 0){
                    $data_list = $result['data']->list;
                    if (!empty($file_name)){
                        foreach($data_list as $key => $a){
                            if (mb_strpos($a->displayTitle, $file_name, 0, 'UTF-8') !== false){
                            }else {
                                unset($data_list[$key]);

                            }
                        }
                        $data_list = array_values($data_list);
                    }
                    for($a=0;$a<10;$a++){
                        if (empty($data_list[$a + (($page - 1) * 10)])){
                            break;
                        }
                        $b = $data_list[$a + (($page - 1) * 10)];
                        $html .= '<li>';
                        $html .= '    <a class="m2_video_list" href="#"><img src="' . $b->clip->thumbnailUrl . '" alt=""></a>';
                        $html .= '    <span>' . $b->displayTitle . '</span>';
                        $html .= '    <button type="button" name="button" onclick="window.open(\'' . $b->linkUrl . '\')">미리보기</button>';
                        $html .= '    <input type="hidden" data-liid="' . $b->id . '" data-thumb="' . $b->clip->thumbnailUrl . '" data-name="' . $b->displayTitle . '">';
                        $html .= '</li>';
                    }

                    $perpage = 10;

                    $this->load->library('pagination');
                    $page_cfg['link_mode'] = 'open_page2';
                    $page_cfg['base_url'] = $page;
                    $page_cfg['total_rows'] = count($data_list);
                    $page_cfg['per_page'] = $perpage;
                    $this->pagination->initialize($page_cfg);
                    $this->pagination->cur_page = intval($page);
                    $result['page'] = str_replace('<ul class="pagination">', '<ul class="pagination2">', $this->pagination->create_links());
                    $result['cur_page'] = $page;
                    $result['text'] = $html;
                } else {
                    $result['code'] = 'z';
                }
            } else {
                $result['code'] = 'f';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //토근 정보 보기
    public function get_token_info(){
        $result['code'] = '';
        $token = $this->input->post('token');
        if(!empty($token)){
            $result['code'] = 'login';
            $result['data'] = $this->kakaolib->get_token_info($token);
            if ($result['data']->expires_in <= 100){
                $result['code'] = 'logout';
            }
        }

        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //캠페인 목록 보기
    public function get_campaigns(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        if(!empty($token)){
            $result['data'] = $this->kakaolib->get_campaigns($token, $adid);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //캠페인 목록 보기
    public function get_campaigns_in_local(){
        $result['code'] = '';
        $adid = $this->input->post('adid');
        $result['data'] = $this->db
            ->select('*')
            ->from('cb_personal_campaign')
            ->where(array(
                'pc_ad_id' => $adid
              , 'pc_mem_id' => $this->member->item('mem_id')
              , 'pc_useyn' => 'Y'
            ))
            ->order_by('pc_update_datetime', 'DESC')
            ->get()->result();
            // ->get_compiled_select();
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //캠페인 보기
    public function get_campaign(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $cid = $this->input->post('cid');
        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_campaigns($token, $adid, $cid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = '';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //카카오톡 채널 프로필 목록 보기
    public function get_ch_profile(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        if(!empty($token)){
            $result['data'] = $this->kakaolib->get_ch_profile($token, $adid);
            if(!empty($result['data'])){
                $result['code'] = '';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //캠페인 생성하기
    public function set_campaign(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $chid = $this->input->post('chid');
        $chname = $this->input->post('chname');
        $cid = $this->input->post('cid');
        $name = $this->input->post('name');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->set_campaign($token, $adid, $chid, $name);
            if(!empty($result['data'])){
                $result['code'] = '';
                if (!empty($result['data']->id)){
                    if (!empty($cid)){
                        $this->db
                            ->set('pc_name', $name)
                            ->where('pc_c_id', $cid)
                            ->update('cb_personal_campaign');
                    } else {
                        $this->db
                            ->set('pc_mem_id', $this->member->item('mem_id'))
                            ->set('pc_ad_id', $adid)
                            ->set('pc_ch_id', $chid)
                            ->set('pc_ch_name', $chname)
                            ->set('pc_c_id', $result['data']->id)
                            ->set('pc_name', $result['data']->name)
                            ->set('pc_status', ($result['data']->config == 'ON' ? 'Y' : 'N'))
                            ->insert('cb_personal_campaign');
                    }
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //픽셀 & SDK 목록 보기
    public function get_pixel_sdk(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_pixel_sdk($token, $adid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //캠페인 수정하기
    public function put_campaign(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $cid = $this->input->post('cid');
        $name = $this->input->post('name');
        $trackId = $this->input->post('trackId');
        $dailyBudgetAmount = $this->input->post('dailyBudgetAmount');
        if(!empty($token)){
            $result['data'] = $this->kakaolib->put_campaign($token, $adid, $cid, $name, $trackId, $dailyBudgetAmount);
            if ($result['data']->id == $cid){
                $this->db
                    ->set('pc_name', $name)
                    ->where('pc_c_id', $cid)
                    ->update('cb_personal_campaign');
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //캠페인 상태 변경하기
    public function put_campaign_status(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $cid = $this->input->post('cid');
        $config = $this->input->post('config');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->put_campaign_status($token, $adid, $cid, $config);
            if (empty($result['data']->code)){
                $this->db
                    ->set('pc_status', ($config == 'ON' ? 'Y' : 'N'))
                    ->where('pc_c_id', $cid)
                    ->update('cb_personal_campaign');
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //캠페인 삭제하기
    public function delete_campaign(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $cid = $this->input->post('cid');
        $pcid = $this->input->post('pcid');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->delete_campaign($token, $adid, $cid);
            if(empty($result['data'])){
                $sql = "
                    delete from cb_personal_creative
                    where pcr_pa_id in (
                        select pa_id
                        from cb_personal_adgroup
                        where pa_pc_id = " . $pcid . "
                    )
                ";
                $this->db->query($sql);
                $sql = "
                    delete from cb_personal_adgroup
                    where pa_pc_id = " . $pcid . "
                ";
                $this->db->query($sql);
                $sql = "
                    delete from cb_personal_campaign
                    where pc_id = " . $pcid . "
                ";
                $this->db->query($sql);
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //캠페인 시스템 정지 사유 보기
    public function get_campaign_reason(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $cid = $this->input->post('cid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_campaign_reason($token, $adid, $cid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //캠페인 시스템 정지 사유 목록 보기
    public function get_campaign_reasons(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $cid = $this->input->post('cid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_campaign_reasons($token, $adid, $cid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //광고그룹 목록 보기
    public function get_ad_groups(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $cid = $this->input->post('cid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_ad_groups($token, $adid, $cid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function get_ad_groups_in_local(){
        return $this->db
            ->select('*')
            ->from('cb_personal_adgroup a')
            ->join('cb_personal_campaign b', 'a.pa_pc_id = b.pc_id')
            ->where(array(
                'b.pc_mem_id' => $this->member->item('mem_id')
              , 'a.pa_useyn' => 'Y'
            ))
            ->order_by('a.pa_update_datetime', 'DESC')
            ->get()->result();

    }

    //광고그룹 보기
    public function get_ad_group(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $aid = $this->input->post('aid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_ad_group($token, $adid, $aid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //개인화 메시지 광고그룹 생성하기
    public function set_ad_group(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $cid = $this->input->post('cid');
        $aid = $this->input->post('aid');
        $name = $this->input->post('name');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->set_ad_group($token, $adid, $cid, $name);
            if(!empty($result['data'])){
                $result['code'] = '';
                if (!empty($result['data']->id)){
                    if (!empty($aid)){
                        $this->db
                            ->set('pa_name', $name)
                            ->where('pa_a_id', $aid)
                            ->update('cb_personal_adgroup');
                    } else {
                        $pc_id = $this->db
                            ->select('pc_id')
                            ->from('cb_personal_campaign')
                            ->where('pc_c_id', $cid)
                            ->get()->row()->pc_id;

                        $this->db
                            ->set('pa_mem_id', $this->member->item('mem_id'))
                            ->set('pa_pc_id', !empty($pc_id) ? $pc_id : '0')
                            ->set('pa_a_id', $result['data']->id)
                            ->set('pa_name', $result['data']->name)
                            ->set('pa_status', ($result['data']->config == 'ON' ? 'Y' : 'N'))
                            ->insert('cb_personal_adgroup');
                    }
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //개인화 메시지 광고그룹 수정하기
    public function put_ad_group(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $cid = $this->input->post('cid');
        $aid = $this->input->post('aid');
        $name = $this->input->post('name');
        if(!empty($token)){
            $result['data'] = $this->kakaolib->put_ad_group($token, $adid, $cid, $aid, $name);
            if(!empty($result['data'])){
                if ($result['data']->id == $aid){
                    $this->db
                        ->set('pa_name', $name)
                        ->where('pa_a_id', $aid)
                        ->update('cb_personal_adgroup');
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //광고그룹 상태 변경하기
    public function put_ad_group_status(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $aid = $this->input->post('aid');
        $config = $this->input->post('config');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->put_ad_group_status($token, $adid, $aid, $config);
            if (empty($result['data']->code)){
                $this->db
                    ->set('pa_status', ($config == 'ON' ? 'Y' : 'N'))
                    ->where('pa_a_id', $aid)
                    ->update('cb_personal_adgroup');
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //광고그룹 삭제하기
    public function delete_ad_group(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $aid = $this->input->post('aid');
        $paid = $this->input->post('paid');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->delete_ad_group($token, $adid, $aid);
            if(empty($result['data'])){
                $sql = "
                    delete from cb_personal_creative
                    where pcr_pa_id = " . $paid . "
                ";
                $this->db->query($sql);
                $sql = "
                    delete from cb_personal_adgroup
                    where pa_id = " . $paid . "
                ";
                $this->db->query($sql);
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //광고그룹 시스템 정지 사유 보기
    public function get_ad_group_reason(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $aid = $this->input->post('aid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_ad_group_reason($token, $adid, $aid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //광고그룹 시스템 정지 사유 목록 보기
    public function get_ad_group_reasons(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $aid = $this->input->post('aid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_ad_group_reasons($token, $adid, $aid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //소재 목록 보기
    public function get_creatives(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $aid = $this->input->post('aid');
        $config = $this->input->post('config');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_creatives($token, $adid, $aid, $config), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //소재 보기
    public function get_creative(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $crid = $this->input->post('crid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_creative($token, $adid, $crid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //소재 상태 변경하기
    public function put_creative_status(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $crid = $this->input->post('crid');
        $config = $this->input->post('config');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->put_creative_status($token, $adid, $crid, $config);
            if (empty($result['data']->code)){
                $this->db
                    ->set('pcr_status', ($config == 'ON' ? 'Y' : 'N'))
                    ->where('pcr_cr_id', $crid)
                    ->update('cb_personal_creative');
                $this->db->query($sql);
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //소재 삭제하기
    public function delete_creative(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $crid = $this->input->post('crid');
        $pcrid = $this->input->post('pcrid');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->delete_creative($token, $adid, $crid);
            if(empty($result['data'])){
                $sql = "
                    delete from cb_personal_creative
                    where pcr_id = " . $pcrid . "
                ";
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //소재 시스템 정지 사유 보기
    public function get_creative_reason(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $crid = $this->input->post('crid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_creative_reason($token, $adid, $crid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //소재 시스템 정지 사유 목록 보기
    public function get_creative_reasons(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $crid = $this->input->post('crid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_creative_reasons($token, $adid, $crid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //개인화 메시지 소재 생성하기(기본텍스트)
    public function set_personal_msg1(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $aid = $this->input->post('aid');
        $para = $this->input->post('para');
        $crid = $this->input->post('crid');
        $pa_id = $this->input->post('pa_id');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->set_personal_msg($token, $adid, $aid, $para, 'post');
            if(!empty($result['data'])){
                if (!empty($result['data']->id)){
                    $result['code'] = 's';
                    if (!empty($crid)){
                        $this->db
                            ->set('pcr_name', $name)
                            ->set('pcr_para', json_encode($para, JSON_UNESCAPED_UNICODE))
                            ->where('pcr_cr_id', $crid)
                            ->update('cb_personal_creative');
                    } else {
                        $this->db
                            ->set('pcr_mem_id', $this->member->item('mem_id'))
                            ->set('pcr_pa_id', $pa_id)
                            ->set('pcr_cr_id', $result['data']->id)
                            ->set('pcr_type', 'bt')
                            ->set('pcr_name', $result['data']->name)
                            ->set('pcr_para', json_encode($para, JSON_UNESCAPED_UNICODE))
                            ->insert('cb_personal_creative');
                    }
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    function set_personal_data($data){

    }

    //개인화 메시지 소재 생성하기(와이드이미지)
    public function set_personal_msg2(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $aid = $this->input->post('aid');

        $para = array(
            'format' => 'WIDE_MESSAGE'
          , 'name' => '와이드이미지_테스트_' . date('YmdHis')
          , 'profileId' => '_xeJlKb'
          , 'adFlag' => 'true'
          , 'csInfo' => 'csInfo'
          , 'title' => '테스트 입니다 ....' . date('YmdHis')
          , 'button' => array(
                array(
                    'ordering' => '0'
                  , 'pcLandingUrl' => 'http://naver.com'
                  , 'mobileLandingUrl' => 'http://naver.com'
                  , 'title' => '버튼테스트1'
                  , 'landingType' => 'LANDING_URL'
                )
              , array(
                    'ordering' => '1'
                  , 'pcLandingUrl' => 'http://google.com'
                  , 'mobileLandingUrl' => 'http://google.com'
                  , 'title' => '버튼테스트2'
                  , 'landingType' => 'LANDING_URL'
                )
            )
          , 'item' => array(
                array(
                    'landingType' => 'CHANNEL_POST'
                  , 'channelPostId' => '102056777'
                  , 'mobileLandingUrl' => 'http://naver.com'
                  // , 'pcLandingUrl' => 'http://naver.com'
                  , 'imageFile' => array(
                        'path' => '/var/www/igenie/uploads/products/2023/09/bcbde78730cb418fb59fcdeff17c1484.jpg'
                      , 'name' => 'bcbde78730cb418fb59fcdeff17c1484.jpg'
                      , 'type' => 'image/jpg'
                    )
                )
            )
        );

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->set_personal_msg($token, $adid, $aid, $para, 'post'), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //개인화 메시지 소재 생성하기(와이드리스트)
    public function set_personal_msg3(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $aid = $this->input->post('aid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->set_personal_msg($token, $adid, $aid, $para, 'post'), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //개인화 메시지 이미지 업로드하기
    public function set_personal_img_upload(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $para = array(
            array(
                'path' => '/var/www/igenie/uploads/maker_goods/57e9444bccb320413bc0a37765271671.jpg'
              , 'name' => 'bcbde78730cb418fb59fcdeff17c1484.jpg'
              , 'type' => 'image/jpg'
            )
          , array(
                'path' => '/var/www/igenie/uploads/maker_goods/57e9444bccb320413bc0a37765271671.jpg'
              , 'name' => 'bcbde78730cb418fb59fcdeff17c1484.jpg'
              , 'type' => 'image/jpg'
            )
        );

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->set_personal_img_upload($token, $adid, $para), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //카카오TV 채널 목록 보기
    public function get_kakaotv_chs(){
        $result['code'] = '';
        $token = $this->input->post('token');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->get_kakaotv_chs($token);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //카카오TV 채널 상세 보기
    public function get_kakaotv_ch(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $chid = $this->input->post('chid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_kakaotv_ch($token, $chid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //카카오TV 채널 영상 목록 보기
    public function get_kakaotv_ch_movs(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $chid = $this->input->post('chid');

        if(!empty($token)){
            $result['data'] = $this->kakaolib->get_kakaotv_ch_movs($token, $chid);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //카카오TV 채널 영상 상세 보기
    public function get_kakaotv_ch_mov(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $chid = $this->input->post('chid');
        $liid = $this->input->post('liid');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_kakaotv_ch_mov($token, $chid, $liid), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //광고계정 보고서 보기
    public function get_ad_account_report(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $para = $this->input->post('para');
        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_ad_account_report($token, $para), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = '';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //캠페인 보고서 보기
    public function get_campaign_report(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $para = $this->input->post('para');
        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_campaign_report($token, $para), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = '';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //광고그룹 보고서 보기
    public function get_ad_group_report(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $para = $this->input->post('para');
        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_ad_group_report($token, $para), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = '';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //소재 보고서 보기
    public function get_creative_report(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $para = $this->input->post('para');
        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->get_creative_report($token, $para), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = '';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //개인화 메시지 테스트 발송하기
    public function send_personal_message_test(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $crid = $this->input->post('crid');
        $para['receiverKey'] = $this->input->post('phone');
        $para['variables']['image_url1'] = $this->input->post('var_image_url1');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->send_personal_message_test($token, $adid, $crid, $para), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //개인화 메시지 단건 발송 요청하기
    public function send_personal_message(){
        $result['code'] = '';
        $token = $this->input->post('token');
        $adid = $this->input->post('adid');
        $crid = $this->input->post('crid');
        $para['messageSerialNumber'] = date('Ymd') . '-' . $crid . '-' . date('YmdHis');
        $para['receiverKey'] = $this->input->post('phone');
        $para['variables']['image_url1'] = $this->input->post('var_image_url1');

        if(!empty($token)){
            $result['data'] = json_encode($this->kakaolib->send_personal_message($token, $adid, $crid, $para), JSON_UNESCAPED_UNICODE);
            if(!empty($result['data'])){
                $result['code'] = 's';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    function image_upload($files, $mem_id, $path = 'personal'){
        if (isset($files) && isset($files['imgfile']) && isset($files['imgfile']['name'])) { //이미지 업로드
            $res_path = array();
            $this->load->library('upload');

            // 업로드 기본 폴더
            $upload_path = config_item('uploads_dir') . '/' . $path . '/' . $mem_id . '/';

            // 기본 폴더가 없다면 생성을 위해 실행
            $this->funn->create_upload_dir($upload_path);

            // 년 폴더가 없다면 생성을 위해 실행
            $this->funn->create_upload_dir($upload_path . date("Y") ."/");

            // 월 폴더가 없다면 생성을 위해 실행
            $this->funn->create_upload_dir($upload_path . date("Y") ."/". date("m") ."/");

            // 최종 업로드 경로
            $upload_path = $upload_path . date("Y") ."/". date("m") ."/";

            $uploadconfig = '';
            $uploadconfig['upload_path'] = $upload_path;
            $uploadconfig['allowed_types'] = 'gif|jpg|png';
            $uploadconfig['max_size'] = 1024 * 1024;
            $uploadconfig['encrypt_name'] = true;
            $this->upload->initialize($uploadconfig);
            $_FILES['userfile']['name'] = $files['imgfile']['name'];
            $_FILES['userfile']['type'] = $files['imgfile']['type'];
            $_FILES['userfile']['tmp_name'] = $files['imgfile']['tmp_name'];
            $_FILES['userfile']['error'] = $files['imgfile']['error'];
            $_FILES['userfile']['size'] = $files['imgfile']['size'];
            if ($this->upload->do_upload()) {
                $this->load->library('image_lib');
                $this->image_lib->clear();
                $imgconfig = array();
                $filedata = $this->upload->data();
                $imgconfig['image_library'] = 'gd2';
                $imgconfig['source_image']	= $filedata['full_path'];
                $imgconfig['maintain_ratio'] = TRUE;
                $imgconfig['new_image'] = $filedata['full_path'];
                $this->image_lib->initialize($imgconfig);
                if($this->image_lib->resize()) {
                    array_push($res_path, str_replace($_SERVER["DOCUMENT_ROOT"],'',$imgconfig['new_image'])); //이미지경로
                } else {
                }
                $thumb = "200"; //썸네일 사이즈
                if($thumb != ""){ //썸네일 추가
                    $this->load->library('image_lib');
                    $this->image_lib->clear();
                    $simgconfig = array();
                    $simgconfig['image_library'] = 'gd2';
                    $simgconfig['source_image']	= $filedata['full_path'];
                    $simgconfig['maintain_ratio'] = TRUE;
                    $simgconfig['width']	= $thumb; //썸네일 가로 사이즈
                    $simgconfig['height']= $thumb; //썸네일 가로 사이즈
                    $simgconfig['new_image'] = $filedata['file_path'].$filedata['raw_name'].'_thumb'.$filedata['file_ext'];
                    $this->image_lib->initialize($simgconfig);
                    if($this->image_lib->resize()) {
                        array_push($res_path, str_replace($_SERVER["DOCUMENT_ROOT"],'',$simgconfig['new_image'])); //이미지경로
                    } else {
                    }
                }
            } else {
                $file_error = $this->upload->display_errors();
            }
        }
        return $res_path;
    }

}
?>
